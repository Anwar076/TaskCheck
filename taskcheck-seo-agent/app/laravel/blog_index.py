"""Voeg nieuwe blogartikelen toe aan resources/views/blog.blade.php."""

from __future__ import annotations

import re
from pathlib import Path
from typing import Any

from app.laravel.discovery_assets import extract_blade_meta
from app.utils.config import get_config
from app.utils.files import read_text, write_text
from app.utils.logger import setup_logger

logger = setup_logger(__name__)

GRID_MARKER = re.compile(
    r"(\{\{-- Raster --\}\}\s*<div class=\"grid gap-6 sm:gap-8 md:grid-cols-2\">)\s*",
    re.MULTILINE,
)

CATEGORY_STYLES: dict[str, tuple[str, str, str]] = {
    "horeca": ("bg-orange-100", "text-orange-900", "ring-orange-200/70"),
    "nieuws": ("bg-amber-100", "text-amber-900", "ring-amber-200/60"),
    "nvwa": ("bg-amber-100", "text-amber-900", "ring-amber-200/60"),
    "schoonmaak": ("bg-blue-100", "text-blue-800", "ring-blue-200/60"),
    "praktijk": ("bg-blue-100", "text-blue-800", "ring-blue-200/60"),
}
DEFAULT_STYLE = ("bg-slate-100", "text-slate-700", "ring-slate-200/90")

ARROW_SVG = (
    '<svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" '
    'viewBox="0 0 24 24" aria-hidden="true">'
    '<path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>'
    "</svg>"
)


class BlogIndexUpdater:
    def __init__(self) -> None:
        self.config = get_config()

    @property
    def index_path(self) -> Path:
        return self.config.laravel_root / "resources" / "views" / "blog.blade.php"

    def add_card(self, slug: str, blade_path: Path | None = None) -> dict[str, Any]:
        """Plaats een blogkaart bovenaan het raster op de blog-overzichtspagina."""
        index_file = self.index_path
        if not index_file.exists():
            logger.warning("blog.blade.php niet gevonden: %s", index_file)
            return {"added": False, "reason": "blog.blade.php ontbreekt"}

        route_token = f"route('blog.{slug}')"
        content = read_text(index_file)
        if route_token in content:
            logger.info("Blogkaart bestaat al op index: %s", slug)
            return {"added": False, "reason": "kaart bestaat al", "slug": slug}

        if blade_path and blade_path.exists():
            meta = extract_blog_card_meta(read_text(blade_path))
        else:
            meta = {"h1": slug.replace("-", " ").title(), "intro": "", "category": "Nieuws",
                    "date_label": "6 min lezen", "image": "images/taskcheck-horeca-blog-hero.webp"}

        card = render_blog_card(slug, meta)
        match = GRID_MARKER.search(content)
        if not match:
            logger.warning("Raster-markering niet gevonden in blog.blade.php")
            return {"added": False, "reason": "raster-sectie niet gevonden"}

        insert_at = match.end()
        new_content = content[:insert_at] + "\n" + card + "\n" + content[insert_at:]
        write_text(index_file, new_content)
        logger.info("Blogkaart toegevoegd aan index: %s", slug)
        return {"added": True, "slug": slug, "path": str(index_file)}


def extract_blog_card_meta(content: str) -> dict[str, str]:
    """Haal kaart-metadata uit een blog Blade-bestand."""
    seo_title, seo_description = extract_blade_meta(content)

    h1_match = re.search(r"<h1[^>]*>([^<]+)</h1>", content)
    h1 = h1_match.group(1).strip() if h1_match else seo_title

    intro_match = re.search(
        r'<p class="mt-4 text-lg text-slate-500 leading-relaxed">([^<]+)</p>',
        content,
    )
    intro = intro_match.group(1).strip() if intro_match else seo_description

    cat_match = re.search(
        r'<div class="mb-4 flex flex-wrap items-center gap-3">\s*'
        r'<span class="rounded-full[^"]*"[^>]*>([^<]+)</span>',
        content,
    )
    category_raw = cat_match.group(1).strip() if cat_match else "Nieuws"
    category = category_raw.split("|")[0].strip() or "Nieuws"

    header_block = content
    header_start = content.find('<div class="mb-4 flex flex-wrap items-center gap-3">')
    if header_start != -1:
        header_end = content.find("<h1", header_start)
        header_block = content[header_start:header_end] if header_end != -1 else content[header_start:]

    date_match = re.search(r'<span class="text-xs text-slate-400">([^<]+)</span>', header_block)
    date_label = date_match.group(1).strip() if date_match else "6 min lezen"

    img_match = re.search(r"\$seoImage\s*=\s*asset\('([^']+)'\)", content)
    image = img_match.group(1) if img_match else "images/taskcheck-horeca-blog-hero.webp"

    return {
        "h1": h1,
        "intro": intro,
        "category": category,
        "date_label": date_label,
        "image": image,
        "image_alt": h1,
    }


def _category_style(category: str) -> tuple[str, str, str]:
    key = category.lower().split("|")[0].strip()
    for token, style in CATEGORY_STYLES.items():
        if token in key:
            return style
    return DEFAULT_STYLE


def _esc(text: str) -> str:
    return (
        str(text)
        .replace("&", "&amp;")
        .replace("<", "&lt;")
        .replace(">", "&gt;")
        .replace('"', "&quot;")
    )


def render_blog_card(slug: str, meta: dict[str, str]) -> str:
    """Genereer een blogkaart-blok voor blog.blade.php."""
    h1 = _esc(meta.get("h1", ""))
    intro = _esc(meta.get("intro", ""))
    if len(intro) > 180:
        intro = intro[:177].rstrip() + "..."
    category = _esc(meta.get("category", "Nieuws").split("|")[0].strip())
    date_label = _esc(meta.get("date_label", "6 min lezen"))
    image = meta.get("image", "images/taskcheck-horeca-blog-hero.webp")
    image_alt = _esc(meta.get("image_alt", meta.get("h1", "")))
    bg, text, ring = _category_style(meta.get("category", ""))

    return f"""            <article class="blog-reveal group blog-card">
                <a href="{{{{ route('blog.{slug}') }}}}" class="blog-card__media block">
                    <img src="{{{{ asset('{image}') }}}}"
                         alt="{image_alt}"
                         loading="lazy"
                         decoding="async"
                         width="800"
                         height="450">
                </a>
                <div class="flex flex-1 flex-col p-5 sm:p-6">
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        <span class="rounded-full {bg} px-3 py-1 text-xs font-semibold {text} ring-1 {ring}">{category}</span>
                        <span class="text-xs text-slate-400">{date_label}</span>
                    </div>
                    <h2 class="text-lg font-bold leading-snug text-slate-900 transition group-hover:text-blue-800 sm:text-xl">
                        <a href="{{{{ route('blog.{slug}') }}}}">{h1}</a>
                    </h2>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">{intro}</p>
                    <a href="{{{{ route('blog.{slug}') }}}}" class="blog-link-arrow mt-4">
                        Lees artikel
                        {ARROW_SVG}
                    </a>
                </div>
            </article>"""
