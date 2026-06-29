"""Genereer TaskCheck blogartikelen als Blade-bestand."""

from __future__ import annotations

import json
from datetime import datetime
from typing import Any

from app.ai.brain import AIBrain
from app.seo.page_registry import get_page_registry
from app.utils.config import get_config
from app.utils.files import slugify, write_text


class BlogWriter:
    def __init__(self) -> None:
        self.config = get_config()
        self.brain = AIBrain()
        self.registry = get_page_registry()

    def create_blog(self, topic: str, slug: str | None = None, source: str = "") -> dict[str, Any]:
        slug = slug or slugify(topic)

        existing = self.registry.get_page(slug)
        if existing and existing.get("source") in {"live_blog", "route_only_blog", "pending", "generated"}:
            raise ValueError(f"Blog bestaat al of staat klaar als concept: {slug}")

        payload = self.brain.generate_blog_content(topic, source=source)
        blade = self._render_blog(payload, slug)

        generated_path = self.config.generated_dir / f"blog-{slug}.blade.php"
        pending_path = self.config.pending_dir / f"blog-{slug}.blade.php"
        write_text(generated_path, blade)
        write_text(pending_path, blade)

        return {
            "topic": topic,
            "slug": slug,
            "path": str(generated_path),
            "pending_path": str(pending_path),
            "route_name": f"blog.{slug}",
            "url": f"https://{self.config.site_domain}/blog/{slug}",
            "source": source,
            "content": payload,
        }

    def _render_blog(self, data: dict[str, Any], slug: str) -> str:
        title = data.get("seo_title", "")
        desc = data.get("seo_description", "")
        h1 = data.get("h1", "")
        intro = data.get("intro", "")
        date_iso = data.get("published_at_iso") or datetime.utcnow().strftime("%Y-%m-%dT08:00:00+00:00")
        date_label = data.get("published_at_label", "Vandaag")
        category = data.get("category", "Nieuws")
        source_name = data.get("source_name", "TaskCheck redactie")
        image = data.get("hero_image", "images/taskcheck-horeca-blog-hero.webp")
        image_alt = data.get("hero_alt", h1)
        read_minutes = data.get("read_minutes", "6 min lezen")
        sections = data.get("sections", [])
        related = data.get("related_routes", [])

        section_html = "\n".join(
            f"""        <section class="mt-10">
            <h2 class="text-2xl font-bold text-slate-900">{self._esc(s.get("title", ""))}</h2>
            <div class="mt-3 text-slate-600 leading-relaxed">{s.get("body_html", "")}</div>
        </section>"""
            for s in sections[:8]
        )
        related_html = "\n".join(
            f"""            <a href="{{{{ route('{r.get("route", "blog")}') }}}}" class="group flex gap-3 rounded-xl border border-slate-200 bg-white p-4 hover:border-blue-300">
                <span class="text-sm font-semibold text-slate-900 group-hover:text-blue-700">{self._esc(r.get("label", ""))}</span>
            </a>"""
            for r in related[:4]
        )

        return f"""<!DOCTYPE html>
<html lang="{{{{ str_replace('_', '-', app()->getLocale()) }}}}">
<head>
    @php
        $seoTitle = {json.dumps(title, ensure_ascii=False)};
        $seoDescription = {json.dumps(desc, ensure_ascii=False)};
        $seoUrl = route('blog.{slug}');
        $seoImage = asset('{image}');
    @endphp
    <title>{{{{ $seoTitle }}}}</title>
    @include('components.head')
    <meta name="description" content="{{{{ $seoDescription }}}}">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="{{{{ $seoUrl }}}}">
    <meta property="og:type" content="article">
    <meta property="article:published_time" content="{date_iso}">
    <meta property="og:title" content="{{{{ $seoTitle }}}}">
    <meta property="og:description" content="{{{{ $seoDescription }}}}">
    <meta property="og:url" content="{{{{ $seoUrl }}}}">
    <meta property="og:image" content="{{{{ $seoImage }}}}">
    <script type="application/ld+json">
    {{
      "@@context":"https://schema.org",
      "@@type":"Article",
      "headline": {json.dumps(h1, ensure_ascii=False)},
      "datePublished":"{date_iso}",
      "author":{{"@@type":"Organization","name":"TaskCheck"}},
      "publisher":{{"@@type":"Organization","name":"TaskCheck"}},
      "mainEntityOfPage":{{"@@type":"WebPage","@@id":"{{{{ $seoUrl }}}}"}}
    }}
    </script>
</head>
<body class="min-h-screen bg-white text-slate-900 antialiased">
@include('components.header')

<header class="border-b border-slate-200 bg-white pt-28 pb-10">
    <div class="max-w-3xl mx-auto px-6">
        <nav class="mb-5 flex items-center gap-2 text-xs text-slate-400">
            <a href="{{{{ route('blog') }}}}" class="hover:text-blue-600">Blog</a>
            <span>/</span>
            <span class="text-slate-500">{self._esc(category)}</span>
        </nav>
        <div class="mb-4 flex flex-wrap items-center gap-3">
            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">{self._esc(category)}</span>
            <span class="text-xs text-slate-400">{self._esc(date_label)} · {self._esc(read_minutes)}</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight text-slate-900">{self._esc(h1)}</h1>
        <p class="mt-4 text-lg text-slate-500 leading-relaxed">{self._esc(intro)}</p>
        <aside class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
            Bron: {self._esc(source_name)}
        </aside>
    </div>
</header>

<main class="max-w-3xl mx-auto px-6 py-10">
    <figure class="mb-10 overflow-hidden rounded-2xl ring-1 ring-slate-200/80">
        <img src="{{{{ asset('{image}') }}}}" alt="{self._esc(image_alt)}" class="w-full object-cover" loading="eager">
    </figure>

{section_html}

    <div class="mt-12 border-t border-slate-200 pt-8">
        <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Meer lezen</h3>
        <div class="mt-4 grid gap-3 sm:grid-cols-2">
{related_html}
        </div>
    </div>
</main>

@include('components.footer')
</body>
</html>
"""

    def _esc(self, text: str) -> str:
        return (
            str(text)
            .replace("&", "&amp;")
            .replace("<", "&lt;")
            .replace(">", "&gt;")
            .replace('"', "&quot;")
        )
