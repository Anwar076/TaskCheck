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

# Marker + grid (marker is optioneel voor backwards compat, grid is verplicht).
GRID_MARKER = re.compile(
    r"((?:\{\{--\s*Raster\s*--\}\}\s*)?<div class=\"grid gap-6 sm:gap-8 md:grid-cols-2\">)\s*",
    re.MULTILINE,
)

CATEGORY_DOTS: dict[str, str] = {
    "horeca": "bg-orange-500",
    "nieuws": "bg-amber-500",
    "nvwa": "bg-rose-500",
    "schoonmaak": "bg-blue-600",
    "praktijk": "bg-emerald-500",
}
DEFAULT_DOT = "bg-blue-600"

ARROW_SVG = (
    '<svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" '
    'viewBox="0 0 24 24" aria-hidden="true">'
    '<path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>'
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
            raise RuntimeError(f"blog.blade.php niet gevonden: {index_file}")

        route_token = f"route('blog.{slug}')"
        content = read_text(index_file)
        if route_token in content:
            logger.info("Blogkaart bestaat al op index: %s", slug)
            return {"added": False, "reason": "kaart bestaat al", "slug": slug}

        if blade_path and blade_path.exists():
            meta = extract_blog_card_meta(read_text(blade_path))
        else:
            meta = {
                "h1": slug.replace("-", " ").title(),
                "intro": "",
                "category": "Nieuws",
                "date_label": "6 min lezen",
                "image": "images/taskcheck-horeca-blog-hero.webp",
            }

        card = render_blog_card(slug, meta)
        match = GRID_MARKER.search(content)
        if not match:
            raise RuntimeError(
                "Artikel-raster niet gevonden in blog.blade.php. "
                "Verwacht een div met class \"grid gap-6 sm:gap-8 md:grid-cols-2\" "
                "(bij voorkeur met {{-- Raster --}} erboven)."
            )

        insert_at = match.end()
        # Zorg dat de marker blijft staan voor volgende inserts.
        prefix = content[: match.start()]
        grid_open = match.group(1)
        if "{{-- Raster --}}" not in grid_open and "{{--Raster--}}" not in grid_open.replace(" ", ""):
            grid_open = "{{-- Raster --}}\n            " + grid_open.lstrip()
        new_content = prefix + grid_open + "\n" + card + "\n" + content[insert_at:]
        write_text(index_file, new_content)
        logger.info("Blogkaart toegevoegd aan index: %s", slug)
        return {"added": True, "slug": slug, "path": str(index_file)}


def extract_blog_card_meta(content: str) -> dict[str, str]:
    """Haal kaart-metadata uit een blog Blade-bestand (oud + layouts.blog-article)."""
    seo_title, seo_description = extract_blade_meta(content)

    h1_match = re.search(r"<h1[^>]*>([^<]+)</h1>", content)
    h1 = h1_match.group(1).strip() if h1_match else seo_title

    intro = ""
    for pattern in (
        r'<p class="fade-up delay-2 mt-4 text-lg leading-relaxed text-slate-500">([^<]+)</p>',
        r'<p class="mt-4 text-lg leading-relaxed text-slate-500">([^<]+)</p>',
        r'<p class="mt-4 text-lg text-slate-500 leading-relaxed">([^<]+)</p>',
    ):
        intro_match = re.search(pattern, content)
        if intro_match:
            intro = intro_match.group(1).strip()
            break
    if not intro:
        intro = seo_description

    category = "Nieuws"
    for pattern in (
        r'<span class="blog-tag">[\s\S]*?</span>([^<]+)</span>',
        r'<span class="rounded-full[^"]*"[^>]*>([^<]+)</span>',
    ):
        cat_match = re.search(pattern, content)
        if cat_match:
            category = cat_match.group(1).strip().split("|")[0].strip() or "Nieuws"
            break

    date_label = "6 min lezen"
    for pattern in (
        r'<span class="text-xs font-medium text-slate-400">([^<]+)</span>',
        r'<span class="text-xs text-slate-400">([^<]+)</span>',
    ):
        date_match = re.search(pattern, content)
        if date_match:
            date_label = date_match.group(1).strip()
            break

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


def _category_dot(category: str) -> str:
    key = category.lower().split("|")[0].strip()
    for token, dot in CATEGORY_DOTS.items():
        if token in key:
            return dot
    return DEFAULT_DOT


def _esc(text: str) -> str:
    return (
        str(text)
        .replace("&", "&amp;")
        .replace("<", "&lt;")
        .replace(">", "&gt;")
        .replace('"', "&quot;")
    )


def render_blog_card(slug: str, meta: dict[str, str]) -> str:
    """Genereer een blogkaart-blok voor blog.blade.php (huidige design)."""
    h1 = _esc(meta.get("h1", ""))
    intro = _esc(meta.get("intro", ""))
    if len(intro) > 180:
        intro = intro[:177].rstrip() + "..."
    category = _esc(meta.get("category", "Nieuws").split("|")[0].strip())
    date_label = _esc(meta.get("date_label", "6 min lezen"))
    image = meta.get("image", "images/taskcheck-horeca-blog-hero.webp")
    image_alt = _esc(meta.get("image_alt", meta.get("h1", "")))
    dot = _category_dot(meta.get("category", ""))

    return f"""                <article class="fade-up group blog-card">
                    <a href="{{{{ route('blog.{slug}') }}}}" class="blog-card__media block">
                        <img src="{{{{ asset('{image}') }}}}"
                             alt="{image_alt}"
                             loading="lazy"
                             decoding="async"
                             width="1024"
                             height="682">
                    </a>
                    <div class="flex flex-1 flex-col p-5 sm:p-6">
                        <div class="mb-3 flex flex-wrap items-center gap-2">
                            <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full {dot}"></span>{category}</span>
                            <span class="text-xs text-slate-400">{date_label}</span>
                        </div>
                        <h2 class="text-lg font-extrabold leading-snug text-slate-900 transition group-hover:text-blue-700 sm:text-xl">
                            <a href="{{{{ route('blog.{slug}') }}}}">{h1}</a>
                        </h2>
                        <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-500">{intro}</p>
                        <a href="{{{{ route('blog.{slug}') }}}}" class="blog-link mt-4">
                            Lees artikel
                            {ARROW_SVG}
                        </a>
                    </div>
                </article>"""
