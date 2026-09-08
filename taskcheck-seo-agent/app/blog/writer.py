"""Genereer TaskCheck blogartikelen als Blade-bestand."""

from __future__ import annotations

import json
import re
from datetime import datetime
from typing import Any

from app.ai.brain import AIBrain
from app.seo.page_registry import get_page_registry
from app.utils.config import get_config
from app.utils.files import slugify, write_blade

_CATEGORY_DOT = {
    "Horeca": "bg-orange-500",
    "NVWA": "bg-rose-500",
    "Nieuws": "bg-blue-600",
    "Praktijk": "bg-emerald-500",
}


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
        self._assert_shared_layout(blade, slug)

        generated_path = self.config.generated_dir / f"blog-{slug}.blade.php"
        pending_path = self.config.pending_dir / f"blog-{slug}.blade.php"
        write_blade(generated_path, blade)
        write_blade(pending_path, blade)

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
        cta_heading = data.get("cta_title", "Wil je dit direct toepassen in jouw team?")
        cta_lead = data.get(
            "cta_text",
            "Start met TaskCheck en zet je eerste digitale checklist live. 14 dagen gratis, zonder creditcard.",
        )
        sections = data.get("sections", [])
        related = data.get("related_routes", [])

        tag_dot = _CATEGORY_DOT.get(category, "bg-blue-600")

        section_html = "\n\n".join(
            f"""        <h2>{self._esc(s.get("title", ""))}</h2>
        {s.get("body_html", "")}"""
            for s in sections[:8]
        )

        solution_items = []
        blog_items = []
        for r in related[:6]:
            route = str(r.get("route", "")).strip()
            label = str(r.get("label", "")).strip()
            description = str(r.get("description", "Bekijk gerelateerde TaskCheck-oplossing.")).strip()
            if not route or not label:
                continue
            if route.startswith("seo.") or route in {"pricing", "contact", "features"}:
                solution_items.append((label, description, route))
            else:
                blog_items.append((label, route))

        if not solution_items:
            solution_items = [
                ("Horeca App", "Checklists, HACCP en werkcontrole voor restaurantteams.", "seo.horeca-app"),
                ("HACCP App", "Digitale HACCP-registratie met bewijs.", "seo.haccp-app"),
                ("Restaurant Checklist App", "Opening, sluiting en hygiëne digitaal afvinken.", "seo.restaurant-checklist-app"),
            ]

        solutions_php = ",\n            ".join(
            f"[{json.dumps(title, ensure_ascii=False)}, {json.dumps(desc, ensure_ascii=False)}, {json.dumps(route, ensure_ascii=False)}]"
            for title, desc, route in solution_items[:3]
        )

        if blog_items:
            related_html = "\n".join(
                f"""            <a href="{{{{ route('{route}') }}}}" class="blog-readmore group">
                <div>
                    <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full {tag_dot}"></span>{self._esc(category)}</span>
                    <p class="mt-2 text-sm font-extrabold leading-snug text-slate-900 transition group-hover:text-blue-700">{self._esc(label)}</p>
                </div>
            </a>"""
                for label, route in blog_items[:4]
            )
            related_block = f"""
    <div class="mt-14 fade-up">
        <p class="blog-kicker">Meer lezen</p>
        <div class="mt-5 grid gap-4 sm:grid-cols-2">
{related_html}
        </div>
    </div>"""
        else:
            related_block = ""

        return f"""@php
    $seoTitle = {json.dumps(title, ensure_ascii=False)};
    $seoDescription = {json.dumps(desc, ensure_ascii=False)};
    $seoUrl = route('blog.{slug}');
    $seoImage = asset('{image}');
    $publishedAt = {json.dumps(date_iso, ensure_ascii=False)};
    $ctaHeading = {json.dumps(cta_heading, ensure_ascii=False)};
    $ctaLead = {json.dumps(cta_lead, ensure_ascii=False)};
@endphp

@extends('layouts.blog-article')

@push('head')
<script type="application/ld+json">
{{
  "@@context":"https://schema.org",
  "@@type":"Article",
  "headline": {json.dumps(h1, ensure_ascii=False)},
  "datePublished":{json.dumps(date_iso, ensure_ascii=False)},
  "author":{{"@@type":"Organization","name":"TaskCheck"}},
  "publisher":{{"@@type":"Organization","name":"TaskCheck"}},
  "image": "{{{{ $seoImage }}}}",
  "description": "{{{{ $seoDescription }}}}",
  "mainEntityOfPage":{{"@@type":"WebPage","@@id":"{{{{ $seoUrl }}}}"}}
}}
</script>
@endpush

@section('hero')
    <nav class="fade-up mb-5 flex items-center gap-2 text-xs text-slate-400">
        <a href="{{{{ route('blog') }}}}" class="transition hover:text-blue-600">Blog</a>
        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-500">{self._esc(category)}</span>
    </nav>
    <div class="fade-up delay-1 mb-4 flex flex-wrap items-center gap-2">
        <span class="blog-tag"><span class="h-1.5 w-1.5 rounded-full {tag_dot}"></span>{self._esc(category)}</span>
        <span class="text-xs font-medium text-slate-400">{self._esc(date_label)} · {self._esc(read_minutes)}</span>
    </div>
    <h1 class="fade-up delay-1 text-3xl font-extrabold leading-[1.08] tracking-[-.045em] text-slate-900 sm:text-4xl lg:text-5xl">{self._esc(h1)}</h1>
    <p class="fade-up delay-2 mt-4 text-lg leading-relaxed text-slate-500">{self._esc(intro)}</p>
@endsection

@section('content')
    <figure class="blog-figure mb-10 fade-up">
        <img src="{{{{ $seoImage }}}}"
             alt="{self._esc(image_alt)}"
             width="1200"
             height="800"
             loading="eager">
        <figcaption>{self._esc(image_alt)}</figcaption>
    </figure>

    <aside class="blog-aside fade-up mb-8">
        Bron: {self._esc(source_name)}
    </aside>

    <article class="prose-article fade-up">
{section_html}
    </article>

    @include('components.blog-related-solutions', [
        'solutions' => [
            {solutions_php},
        ],
    ])
{related_block}
@endsection
"""

    def _assert_shared_layout(self, blade: str, slug: str) -> None:
        """Blokkeer oude standalone HTML; elke blog gebruikt layouts.blog-article."""
        if "@extends('layouts.blog-article')" not in blade and '@extends("layouts.blog-article")' not in blade:
            raise RuntimeError(
                f"Blog '{slug}' mist @extends('layouts.blog-article'). "
                "Nieuwe blogs moeten altijd de gedeelde blog-layout gebruiken."
            )
        if "<!DOCTYPE html>" in blade or re.search(r"<html\b", blade, re.I):
            raise RuntimeError(
                f"Blog '{slug}' bevat nog een standalone HTML-document. "
                "Gebruik @extends('layouts.blog-article') in plaats van een eigen <html>/<body>."
            )

    def _esc(self, text: str) -> str:
        return (
            str(text)
            .replace("&", "&amp;")
            .replace("<", "&lt;")
            .replace(">", "&gt;")
            .replace('"', "&quot;")
        )
