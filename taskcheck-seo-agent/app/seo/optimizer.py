"""Optimaliseer bestaande SEO-pagina's."""

from __future__ import annotations

import re
from typing import Any

from app.ai.brain import AIBrain
from app.competitor.analyzer import CompetitorAnalyzer
from app.seo.page_registry import is_valid_route
from app.utils.config import get_config
from app.utils.files import read_text, write_blade
from app.utils.logger import setup_logger

logger = setup_logger(__name__)

RELATED_LINKS_FOREACH = re.compile(
    r"(Gerelateerde pagina.*?@foreach\(\[\s*)([\s\S]*?)(\s*\]\s+as\s+\$link\))",
    re.DOTALL | re.IGNORECASE,
)

# Door de agent gegenereerde pagina's zetten de gerelateerde links als losse
# <a>-tags in een flex-container in plaats van een @foreach.
RELATED_LINKS_STATIC = re.compile(
    r"(Gerelateerde pagina[\s\S]{0,400}?<div[^>]*>)([\s\S]*?)(\s*</div>)",
    re.IGNORECASE,
)

STATIC_LINK_CLASSES = (
    "inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white "
    "px-4 py-2 text-sm font-medium text-blue-700 transition hover:border-blue-200 hover:bg-blue-50"
)

# PageWriter schrijft meta-strings met json.dumps (dubbele quotes), de
# referentiepagina's gebruiken enkele quotes. Beide moeten matchen.
PHP_STRING = r"(?:'(?:\\.|[^'\\])*'|\"(?:\\.|[^\"\\])*\")"


def _php_assignment(variable: str) -> re.Pattern[str]:
    return re.compile(rf"(\${variable}\s*=\s*){PHP_STRING}")


SEO_TITLE_ASSIGNMENT = _php_assignment("seoTitle")
SEO_DESCRIPTION_ASSIGNMENT = _php_assignment("seoDescription")


def _php_single_quoted(value: str) -> str:
    escaped = str(value).replace("\\", "\\\\").replace("'", "\\'")
    return f"'{escaped}'"


def _escape_html(value: str) -> str:
    return (
        str(value)
        .replace("&", "&amp;")
        .replace("<", "&lt;")
        .replace(">", "&gt;")
        .replace('"', "&quot;")
    )


class PageOptimizer:
    def __init__(self) -> None:
        self.config = get_config()
        self.brain = AIBrain()
        self.competitor = CompetitorAnalyzer()

    def optimize_page(
        self,
        slug: str,
        gsc_data: dict[str, Any] | None = None,
    ) -> dict[str, Any]:
        page_path = self.config.seo_views_dir / f"{slug}.blade.php"
        if not page_path.exists():
            raise FileNotFoundError(f"Pagina niet gevonden: {slug}")

        content = read_text(page_path)
        keyword = slug.replace("-", " ")

        competitor_data = self.competitor.compare_with_taskcheck(keyword, f"{slug}.blade.php")
        improvements = self.brain.generate_improvements(slug, content, gsc_data, competitor_data)

        optimized = self._apply_improvements(content, improvements)
        backup_path = self.config.pending_dir / f"{slug}.optimized.blade.php"
        self.config.pending_dir.mkdir(parents=True, exist_ok=True)
        write_blade(backup_path, optimized)

        return {
            "slug": slug,
            "keyword": keyword,
            "improvements": improvements,
            "pending_path": str(backup_path),
            "original_path": str(page_path),
        }

    def _apply_improvements(self, content: str, improvements: dict[str, Any]) -> str:
        result = content

        if improvements.get("seo_title"):
            result = self._replace_php_string(
                result, SEO_TITLE_ASSIGNMENT, improvements["seo_title"], "seoTitle"
            )

        if improvements.get("seo_description"):
            result = self._replace_php_string(
                result, SEO_DESCRIPTION_ASSIGNMENT, improvements["seo_description"], "seoDescription"
            )

        new_faq = improvements.get("new_faq_items", [])
        if new_faq:
            result = self._add_faq_items(result, new_faq)

        extra_section = improvements.get("extra_content_section")
        if extra_section:
            result = result.replace("</main>", f"{extra_section}\n</main>", 1)

        links = improvements.get("internal_links_to_add", [])
        if links:
            result = self._add_internal_links(result, links)

        return result

    def _replace_php_string(
        self,
        content: str,
        pattern: re.Pattern[str],
        value: str,
        label: str,
    ) -> str:
        literal = _php_single_quoted(value)
        updated, count = pattern.subn(lambda m: f"{m.group(1)}{literal}", content, count=1)
        if not count:
            logger.warning("$%s niet gevonden in pagina; overgeslagen", label)
            return content
        return updated

    def _add_faq_items(self, content: str, new_items: list) -> str:
        match = re.search(r"(\$faqItems\s*=\s*\[)([\s\S]*?)(\];)", content)
        if not match:
            return content

        existing = match.group(2).strip()
        additions = []
        for item in new_items:
            q = item.get("question", "").replace("'", "\\'")
            a = item.get("answer", "").replace("'", "\\'")
            additions.append(f"            ['{q}', '{a}'],")

        if existing and not existing.endswith(","):
            existing += ","
        new_block = existing + "\n" + "\n".join(additions)
        return content[: match.start(2)] + new_block + content[match.end(2) :]

    def _add_internal_links(self, content: str, links: list) -> str:
        match = RELATED_LINKS_FOREACH.search(content)
        if match:
            return self._add_links_to_foreach(content, match, links)

        match = RELATED_LINKS_STATIC.search(content)
        if match:
            return self._add_links_to_static_block(content, match, links)

        logger.warning("Gerelateerde pagina's sectie niet gevonden; interne links overgeslagen")
        return content

    def _usable_links(self, links: list, existing_routes: set[str]) -> list[tuple[str, str]]:
        usable: list[tuple[str, str]] = []
        for link in links:
            label = link.get("label", "")
            route = link.get("route", "")
            if not route or not is_valid_route(route):
                logger.warning("Ongeldige route overgeslagen bij optimalisatie: %s", route)
                continue
            if route in existing_routes:
                continue
            usable.append((label, route))
            existing_routes.add(route)
        return usable

    def _add_links_to_foreach(self, content: str, match: re.Match[str], links: list) -> str:
        existing = match.group(2)
        existing_routes = set(re.findall(r"route\('([^']+)'\)", existing))
        additions = [
            f"                    [{_php_single_quoted(label)}, route('{route}')],"
            for label, route in self._usable_links(links, existing_routes)
        ]

        if not additions:
            return content

        separator = "" if not existing.strip() or existing.rstrip().endswith(",") else ",\n"
        new_block = existing + separator + "\n".join(additions)
        return content[: match.start(2)] + new_block + content[match.end(2) :]

    def _add_links_to_static_block(self, content: str, match: re.Match[str], links: list) -> str:
        existing = match.group(2)
        existing_routes = set(re.findall(r"route\(['\"]([^'\"]+)['\"]\)", existing))
        additions = [
            f"            <a href=\"{{{{ route('{route}') }}}}\" class=\"{STATIC_LINK_CLASSES}\">"
            f"{_escape_html(label)}</a>"
            for label, route in self._usable_links(links, existing_routes)
        ]

        if not additions:
            return content

        prefix = existing.rstrip()
        parts = [prefix, *additions] if prefix else ["", *additions]
        new_block = "\n".join(parts)
        return content[: match.start(2)] + new_block + content[match.end(2) :]
