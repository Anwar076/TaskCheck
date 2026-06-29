"""Werk sitemap.xml en llms.txt bij na nieuwe SEO-pagina's of blogs."""

from __future__ import annotations

import re
from datetime import date
from typing import Any

from app.utils.config import get_config
from app.utils.files import read_text, write_text
from app.utils.logger import setup_logger

logger = setup_logger(__name__)

SECTION_HACCP = "## HACCP & voedselveiligheid"
SECTION_HORECA = "## SEO pagina's – Horeca"
SECTION_SCHOONMAAK = "## SEO pagina's – Schoonmaak"
SECTION_CONTROLE = "## Controle & Taakbeheer"
SECTION_BLOG = "## Blog"

SITEMAP_BLOG_MARKER = "  <!-- Blog -->"
SITEMAP_CLOSE = "</urlset>"


class DiscoveryAssets:
    def __init__(self) -> None:
        self.config = get_config()

    def register_page(
        self,
        slug: str,
        page_type: str = "seo",
        title: str | None = None,
        description: str | None = None,
    ) -> dict[str, Any]:
        """Voeg URL toe aan sitemap.xml en llms.txt."""
        domain = self.config.site_domain
        if page_type == "blog":
            url = f"https://{domain}/blog/{slug}"
            changefreq = "monthly"
            priority = "0.7"
            section = SECTION_BLOG
        else:
            url = f"https://{domain}/{slug}"
            changefreq = "weekly"
            priority = "0.8"
            section = self._categorize_seo_slug(slug)

        display_title = title or slug.replace("-", " ").title()
        display_desc = description or display_title

        sitemap_result = self._add_to_sitemap(url, changefreq, priority, page_type)
        llms_result = self._add_to_llms(section, display_title, url, display_desc)

        return {
            "url": url,
            "sitemap": sitemap_result,
            "llms": llms_result,
            "section": section,
        }

    def touch_url(self, slug: str, page_type: str = "seo") -> dict[str, Any]:
        """Werk lastmod bij voor bestaande sitemap-entry (bij optimalisatie)."""
        domain = self.config.site_domain
        url = (
            f"https://{domain}/blog/{slug}"
            if page_type == "blog"
            else f"https://{domain}/{slug}"
        )
        path = self.config.sitemap_path
        if not path.exists():
            return {"updated": False, "reason": "sitemap ontbreekt"}

        content = read_text(path)
        if url not in content:
            return {"updated": False, "reason": "url niet in sitemap"}

        today = date.today().isoformat()
        pattern = rf"(<loc>{re.escape(url)}</loc>\s*<lastmod>)[^<]+(</lastmod>)"
        new_content, count = re.subn(pattern, rf"\g<1>{today}\g<2>", content, count=1)
        if count:
            write_text(path, new_content)
            logger.info("Sitemap lastmod bijgewerkt: %s", url)
            return {"updated": True, "url": url}
        return {"updated": False, "reason": "lastmod niet gevonden"}

    def _categorize_seo_slug(self, slug: str) -> str:
        s = slug.lower()
        if any(k in s for k in ("haccp", "temperatuur", "vriezer", "nvwa", "voedsel")):
            return SECTION_HACCP
        if any(k in s for k in ("schoonmaak", "cleaning")):
            return SECTION_SCHOONMAAK
        if any(k in s for k in ("horeca", "restaurant", "opening", "sluiting", "mise", "place")):
            return SECTION_HORECA
        return SECTION_CONTROLE

    def _add_to_sitemap(
        self,
        url: str,
        changefreq: str,
        priority: str,
        page_type: str,
    ) -> dict[str, Any]:
        path = self.config.sitemap_path
        if not path.exists():
            logger.warning("sitemap.xml niet gevonden: %s", path)
            return {"added": False, "reason": "bestand ontbreekt"}

        content = read_text(path)
        if url in content:
            touch = self.touch_url(url.split("/")[-1], page_type)
            return {"added": False, "updated": touch.get("updated", False), "url": url}

        today = date.today().isoformat()
        block = (
            f"  <url>\n"
            f"    <loc>{url}</loc>\n"
            f"    <lastmod>{today}</lastmod>\n"
            f"    <changefreq>{changefreq}</changefreq>\n"
            f"    <priority>{priority}</priority>\n"
            f"  </url>\n"
        )

        if page_type == "blog":
            marker = SITEMAP_CLOSE
        else:
            marker = SITEMAP_BLOG_MARKER if SITEMAP_BLOG_MARKER in content else SITEMAP_CLOSE

        if marker not in content:
            return {"added": False, "reason": "sitemap structuur onbekend"}

        content = content.replace(marker, block + marker, 1)
        write_text(path, content)
        logger.info("URL toegevoegd aan sitemap: %s", url)
        return {"added": True, "url": url}

    def _add_to_llms(self, section: str, title: str, url: str, description: str) -> dict[str, Any]:
        path = self.config.llms_txt_path
        if not path.exists():
            logger.warning("llms.txt niet gevonden: %s", path)
            return {"added": False, "reason": "bestand ontbreekt"}

        content = read_text(path)
        if url in content:
            return {"added": False, "url": url}

        if section not in content:
            content = content.rstrip() + f"\n\n{section}\n"
            insert_at = len(content)
        else:
            section_start = content.index(section) + len(section)
            next_section = re.search(r"\n## ", content[section_start:])
            insert_at = section_start + next_section.start() if next_section else len(content)

        safe_title = title.replace("[", "").replace("]", "")
        safe_desc = description.replace("\n", " ").strip()[:160]
        line = f"- [{safe_title}]({url}): {safe_desc}\n"

        content = content[:insert_at] + line + content[insert_at:]
        write_text(path, content)
        logger.info("Link toegevoegd aan llms.txt: %s", url)
        return {"added": True, "url": url, "section": section}


def extract_blade_meta(content: str) -> tuple[str, str]:
    """Haal seoTitle en seoDescription uit Blade."""
    title = ""
    description = ""
    for pattern in (r"\$seoTitle\s*=\s*'([^']+)'", r'\$seoTitle\s*=\s*"([^"]+)"'):
        match = re.search(pattern, content)
        if match:
            title = match.group(1).strip()
            break
    for pattern in (r"\$seoDescription\s*=\s*'([^']+)'", r'\$seoDescription\s*=\s*"([^"]+)"'):
        match = re.search(pattern, content)
        if match:
            description = match.group(1).strip()
            break
    return title, description
