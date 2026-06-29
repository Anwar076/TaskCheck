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
            result = re.sub(
                r"\$seoTitle\s*=\s*'[^']*'",
                f"$seoTitle       = '{improvements['seo_title']}'",
                result,
                count=1,
            )

        if improvements.get("seo_description"):
            result = re.sub(
                r"\$seoDescription\s*=\s*'[^']*'",
                f"$seoDescription = '{improvements['seo_description']}'",
                result,
                count=1,
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
        if not match:
            logger.warning("Gerelateerde pagina's sectie niet gevonden; interne links overgeslagen")
            return content

        existing = match.group(2)
        existing_routes = set(re.findall(r"route\('([^']+)'\)", existing))
        additions: list[str] = []

        for link in links:
            label = link.get("label", "").replace("'", "\\'")
            route = link.get("route", "")
            if not route or not is_valid_route(route):
                logger.warning("Ongeldige route overgeslagen bij optimalisatie: %s", route)
                continue
            if route in existing_routes:
                continue
            additions.append(f"                    ['{label}', route('{route}')],")
            existing_routes.add(route)

        if not additions:
            return content

        separator = "" if not existing.strip() or existing.rstrip().endswith(",") else ",\n"
        new_block = existing + separator + "\n".join(additions)
        return content[: match.start(2)] + new_block + content[match.end(2) :]
