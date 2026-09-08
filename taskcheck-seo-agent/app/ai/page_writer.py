"""Genereer Laravel Blade SEO-pagina's."""

from __future__ import annotations

import json
import re
from typing import Any

from app.ai.brain import AIBrain
from app.competitor.analyzer import CompetitorAnalyzer
from app.seo.page_registry import get_page_registry
from app.utils.config import get_config
from app.utils.files import slugify, sanitize_blade_ld_json, write_blade
from app.utils.logger import setup_logger

logger = setup_logger(__name__)


class PageAlreadyExistsError(Exception):
    def __init__(self, message: str, existing: Any = None) -> None:
        super().__init__(message)
        self.existing = existing


class PageWriter:
    def __init__(self) -> None:
        self.config = get_config()
        self.brain = AIBrain()
        self.competitor = CompetitorAnalyzer()
        self.registry = get_page_registry()

    def create_page(self, keyword: str, slug: str | None = None) -> dict[str, Any]:
        slug = slug or slugify(keyword)

        existing = self.registry.find_match(keyword, slug)
        if existing:
            raise PageAlreadyExistsError(
                f"SEO-pagina bestaat al: {existing.url} ({existing.reason})",
                existing=existing,
            )

        route_name = f"seo.{slug}"

        competitor_data = self.competitor.compare_with_taskcheck(keyword)
        content = self.brain.generate_page_content(keyword, competitor_data)

        blade = sanitize_blade_ld_json(self._render_blade(content, slug, route_name))
        output_path = self.config.generated_dir / f"{slug}.blade.php"
        self.config.generated_dir.mkdir(parents=True, exist_ok=True)
        write_blade(output_path, blade)

        pending_path = self.config.pending_dir / f"{slug}.blade.php"
        self.config.pending_dir.mkdir(parents=True, exist_ok=True)
        write_blade(pending_path, blade)

        return {
            "keyword": keyword,
            "slug": slug,
            "route_name": route_name,
            "path": str(output_path),
            "pending_path": str(pending_path),
            "content": content,
            "competitor_insights": competitor_data,
        }

    def _render_blade(self, data: dict[str, Any], slug: str, route_name: str) -> str:
        faq_items = data.get("faq", [])
        faq_php = self._render_faq_php(faq_items)
        stats_php = self._render_stats(data.get("stats", []))
        problems = self._render_problems(data.get("problems", []))
        features = self._render_features(data.get("features_list", []))
        benefits = self._render_benefits(data.get("benefits", []))
        targets = self._render_targets(data.get("targets", []))
        chips = self._render_chips(data.get("checklist_chips", []))
        related = self._render_related_links(data.get("related_links", []))
        trust_badges = self._render_trust_badges(data.get("trust_badges", []))
        pattern_id = slug.replace("-", "")[:12]

        h1_part1 = data.get("h1_part1", data.get("h1", keyword_fallback(data, slug)))
        h1_highlight = data.get("h1_highlight", "horeca")
        theme = self._infer_theme(slug, data)
        hero_image = data.get("hero_image", "images/taskcheck-horeca-seo-hero.webp")
        cta_heading = data.get("cta_title", "Start vandaag met TaskCheck")
        cta_lead = data.get("cta_text", "Start 14 dagen gratis en zet je eerste digitale checklist live.")

        return f"""@php
    $seoTitle       = {self._php_str(data.get("seo_title", ""))};
    $seoDescription = {self._php_str(data.get("seo_description", ""))};
    $seoKeywords    = {self._php_str(data.get("seo_keywords", ""))};
    $seoUrl         = route('{route_name}');
    $seoImage       = asset('{hero_image}');
    $faqItems = {faq_php};
    $ctaHeading = {self._php_str(cta_heading)};
    $ctaLead = {self._php_str(cta_lead)};
    $seoTheme = {self._php_str(theme)};
@endphp

@extends('layouts.seo-page')

@push('head')
<script type="application/ld+json">
    {{
        "@@context": "https://schema.org",
        "@@type": "FAQPage",
        "mainEntity": [
            @foreach($faqItems as $i => [$q, $a])
            {{
                "@@type": "Question",
                "name": @json($q),
                "acceptedAnswer": {{
                    "@@type": "Answer",
                    "text": @json($a)
                }}
            }}@if(!$loop->last),@endif
            @endforeach
        ]
    }}
    </script>
@endpush

@section('content')

<section class="relative overflow-hidden bg-white pt-24 pb-14 sm:pt-28 sm:pb-16">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <svg class="absolute inset-0 h-full w-full opacity-[.03]" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="seo-{pattern_id}-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1.2" fill="#334155"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#seo-{pattern_id}-dots)"/>
        </svg>
        <div class="absolute max-md:-right-[280px] max-md:top-[-200px] md:-right-[200px] md:-top-[300px] h-[min(520px,120vw)] w-[min(520px,120vw)] md:h-[800px] md:w-[800px] rounded-full bg-[radial-gradient(circle,rgba(99,102,241,.1)_0%,transparent_65%)]"></div>
        <div class="absolute max-md:-left-[120px] max-md:bottom-[-80px] md:bottom-0 md:left-[-100px] h-[280px] w-[280px] md:h-[400px] md:w-[400px] rounded-full bg-[radial-gradient(circle,rgba(37,99,235,.06)_0%,transparent_65%)]"></div>
    </div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="min-w-0 fade-up">
                <p class="blog-kicker fade-up mb-6 sm:mb-7">{self._escape_html(data.get("badge", ""))}</p>
                <h1 class="text-3xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl xl:text-[3.25rem]">
                    {self._escape_html(h1_part1)}
                    <span class="relative inline-block">
                        <span style="background:linear-gradient(135deg,#2563eb,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">{self._escape_html(h1_highlight)}</span>
                        <svg class="absolute -bottom-1 left-0 w-full" viewBox="0 0 300 8" preserveAspectRatio="none" style="height:5px" aria-hidden="true">
                            <path d="M1 6 C75 1, 225 1, 299 6" stroke="url(#seo-{pattern_id}-ul)" stroke-width="3" stroke-linecap="round" fill="none"/>
                            <defs>
                                <linearGradient id="seo-{pattern_id}-ul" x1="0" y1="0" x2="300" y2="0">
                                    <stop offset="0%" stop-color="#2563eb"/>
                                    <stop offset="100%" stop-color="#6366f1"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </span>
                </h1>
                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-500 sm:mt-6 sm:text-lg">
                    {self._escape_html(data.get("intro_p1", ""))}
                </p>
                <p class="mt-3 max-w-xl text-base leading-relaxed text-slate-500 sm:text-lg">
                    {self._escape_html(data.get("intro_p2", ""))}
                </p>
                <div class="mt-7 flex flex-col gap-3 sm:mt-8 sm:flex-row sm:flex-wrap">
                    @auth
                        <a href="{{{{ auth()->user()->homeDashboardUrl() }}}}" class="cta-btn inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-2xl px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200/60 transition-all sm:w-auto sm:min-h-0 touch-manipulation">
                            Naar dashboard
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @else
                        <a href="{{{{ route('register') }}}}" class="cta-btn inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-2xl px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200/60 transition-all sm:w-auto sm:min-h-0 touch-manipulation">
                            Start 14 dagen gratis
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @endauth
                    <a href="{{{{ route('contact') }}}}" class="inline-flex min-h-[3rem] w-full items-center justify-center gap-2 rounded-2xl border border-[#e6e8ec] bg-white px-6 py-3.5 text-sm font-bold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 sm:w-auto sm:min-h-0 touch-manipulation">
                        Plan een demo
                    </a>
                </div>
                <div class="mt-5 flex flex-wrap gap-x-5 gap-y-2 sm:mt-6 sm:gap-x-6">
{trust_badges}
                </div>
            </div>
            <div class="relative mx-auto w-full max-w-xl lg:mx-0 lg:max-w-none">
                <div class="seo-hero-frame">
                    <img src="{{{{ asset('{hero_image}') }}}}"
                         alt="{self._escape_html(data.get("hero_alt", data.get("seo_title", "")))}"
                         class="h-auto w-full object-cover" width="1200" height="800" loading="eager" fetchpriority="high">
                </div>
                <p class="mt-3 text-center text-xs text-slate-400 lg:text-left">{self._escape_html(data.get("hero_caption", ""))}</p>
            </div>
        </div>
    </div>
</section>

<section class="border-y border-[#e6e8ec] bg-[#f7f8fa]">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
{stats_php}
        </div>
    </div>
</section>

<x-seo-product-visuals theme="{theme}" placement="showcase" />

<div class="pb-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <section class="mt-16 sm:mt-20">
            <div class="grid items-start gap-12 lg:grid-cols-2 lg:gap-16">
                <div>
                    <span class="blog-kicker">Waarom digitaal</span>
                    <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">{self._escape_html(data.get("section_why_title", "Waarom digitaal werken?"))}</h2>
                    <div class="mt-4 text-lg leading-relaxed text-slate-500">{data.get("section_why_text", "")}</div>
                </div>
                <div class="grid gap-3">
{problems}
                </div>
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="blog-kicker">Functies</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">{self._escape_html(data.get("section_features_title", "Belangrijkste functies"))}</h2>
            </div>
            <ul class="mx-auto mt-10 grid max-w-4xl gap-3 sm:grid-cols-2">
{features}
            </ul>
        </section>

        <section class="mt-20 rounded-[18px] border border-[#e6e8ec] bg-[#f7f8fa] p-8 sm:p-12 sm:mt-24">
            <span class="blog-kicker">Checklists</span>
            <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">{self._escape_html(data.get("section_checklists_title", "Digitaal uitvoeren"))}</h2>
            <div class="mt-4 text-lg leading-relaxed text-slate-600">{data.get("section_checklists_text", "")}</div>
            <div class="mt-6 flex flex-wrap gap-2">
{chips}
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="blog-kicker">Voordelen</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">{self._escape_html(data.get("section_benefits_title", "Voordelen"))}</h2>
            </div>
            <div class="mx-auto mt-10 grid max-w-4xl gap-4 sm:grid-cols-2 lg:grid-cols-3">
{benefits}
            </div>
        </section>

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="blog-kicker">Doelgroep</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">{self._escape_html(data.get("section_targets_title", "Voor wie geschikt?"))}</h2>
            </div>
            <div class="mx-auto mt-10 flex max-w-3xl flex-wrap justify-center gap-3">
{targets}
            </div>
        </section>

        <x-seo-product-visuals theme="{theme}" placement="story" />

        <section class="mt-20 sm:mt-24">
            <div class="mx-auto max-w-2xl text-center">
                <span class="blog-kicker">FAQ</span>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Veelgestelde vragen</h2>
            </div>
            <div class="mx-auto mt-10 max-w-3xl space-y-3">
                @foreach($faqItems as [$q, $a])
                <details class="group cursor-pointer rounded-2xl border border-[#e6e8ec] bg-white px-5 py-4 transition hover:border-[#d7e2f7] sm:px-6">
                    <summary class="flex list-none items-center justify-between gap-3 font-semibold text-slate-900">
                        <span class="text-left">{{{{ $q }}}}</span>
                        <svg class="h-5 w-5 shrink-0 text-slate-400 transition-transform group-open:rotate-45" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </summary>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">{{{{ $a }}}}</p>
                </details>
                @endforeach
            </div>
        </section>

        <section class="mb-4 mt-16 border-t border-slate-200 pt-12 sm:mt-20">
            <p class="blog-kicker mx-auto">Gerelateerde pagina&rsquo;s</p>
            <div class="mx-auto mt-5 flex max-w-4xl flex-wrap justify-center gap-2">
{related}
            </div>
        </section>
    </div>
</div>
@endsection
"""

    def _php_str(self, value: str) -> str:
        return json.dumps(value, ensure_ascii=False)

    def _escape_html(self, text: str) -> str:
        return (
            str(text)
            .replace("&", "&amp;")
            .replace("<", "&lt;")
            .replace(">", "&gt;")
            .replace('"', "&quot;")
        )

    def _render_faq_php(self, faq: list) -> str:
        items = []
        for item in faq:
            if isinstance(item, dict):
                items.append([item.get("question", ""), item.get("answer", "")])
            elif isinstance(item, (list, tuple)) and len(item) >= 2:
                items.append([item[0], item[1]])
        return json.dumps(items, ensure_ascii=False, indent=12)

    def _render_faq_schema(self, faq: list) -> str:
        lines = []
        for i, item in enumerate(faq):
            q = item.get("question", "") if isinstance(item, dict) else item[0]
            a = item.get("answer", "") if isinstance(item, dict) else item[1]
            comma = "," if i < len(faq) - 1 else ""
            lines.append(f"""            {{
                "@@type": "Question",
                "name": {json.dumps(q, ensure_ascii=False)},
                "acceptedAnswer": {{
                    "@@type": "Answer",
                    "text": {json.dumps(a, ensure_ascii=False)}
                }}
            }}{comma}""")
        return "\n".join(lines)

    def _render_stats(self, stats: list) -> str:
        if not stats:
            return ""
        lines = []
        colors = ["text-blue-600", "text-sky-600", "text-emerald-600", "text-indigo-600"]
        for i, stat in enumerate(stats[:4]):
            color = colors[i % len(colors)]
            title = stat.get("title", "") if isinstance(stat, dict) else stat[0]
            sub = stat.get("subtitle", "") if isinstance(stat, dict) else stat[1] if len(stat) > 1 else ""
            icon_wrap = (
                "rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/25"
                if i == 0
                else f"rounded-xl border border-slate-200 bg-white {color}"
            )
            lines.append(f"""            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center {icon_wrap}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-slate-900">{self._escape_html(title)}</p>
                    <p class="mt-0.5 text-sm text-slate-500">{self._escape_html(sub)}</p>
                </div>
            </div>""")
        return "\n".join(lines)

    def _render_problems(self, items: list) -> str:
        lines = []
        for item in items:
            lines.append(f"""                    <div class="flex items-start gap-3 rounded-2xl border border-[#e6e8ec] bg-white px-4 py-3.5 shadow-sm">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-md bg-slate-100 text-slate-500">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                        </span>
                        <span class="text-sm text-slate-700">{self._escape_html(str(item))}</span>
                    </div>""")
        return "\n".join(lines)

    def _render_features(self, items: list) -> str:
        lines = []
        for item in items:
            lines.append(f"""                <li class="flex items-start gap-3 rounded-2xl border border-[#e6e8ec] bg-white px-4 py-3.5 text-sm text-slate-700 shadow-sm">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    {self._escape_html(str(item))}
                </li>""")
        return "\n".join(lines)

    def _render_benefits(self, benefits: list) -> str:
        lines = []
        for b in benefits:
            lines.append(f"""                <div class="flex items-center gap-3 rounded-2xl border border-[#e6e8ec] bg-white px-4 py-3.5 shadow-sm">
                    <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    <span class="text-sm font-medium text-slate-800">{self._escape_html(str(b))}</span>
                </div>""")
        return "\n".join(lines)

    def _render_targets(self, targets: list) -> str:
        return "\n".join(
            f'                <span class="rounded-full border border-[#e6e8ec] bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm">{self._escape_html(str(t))}</span>'
            for t in targets
        )

    def _render_chips(self, chips: list) -> str:
        return "\n".join(
            f'                <span class="blog-tag">{self._escape_html(str(c))}</span>'
            for c in chips
        )

    def _render_related_links(self, links: list) -> str:
        from app.seo.page_registry import is_valid_route

        lines = []
        for link in links:
            label = link.get("label", "") if isinstance(link, dict) else link[0]
            route = link.get("route", "") if isinstance(link, dict) else link[1]
            if route and not is_valid_route(route):
                continue
            lines.append(
                f'                <a href="{{{{ route(\'{route}\') }}}}" class="inline-flex items-center gap-1.5 rounded-full border border-[#e6e8ec] bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-[#d7e2f7] hover:text-blue-700">{self._escape_html(label)}</a>'
            )
        return "\n".join(lines)

    def _render_trust_badges(self, badges: list) -> str:
        return "\n".join(
            f"""                    <span class="flex items-center gap-1.5 text-xs text-slate-500">
                        <svg class="h-3.5 w-3.5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        {self._escape_html(str(b))}
                    </span>"""
            for b in badges
        )


    def _infer_theme(self, slug: str, data: dict[str, Any]) -> str:
        blob = " ".join(
            [
                slug,
                str(data.get("badge", "")),
                str(data.get("seo_title", "")),
                str(data.get("seo_keywords", "")),
                str(data.get("h1", "")),
            ]
        ).lower()
        if "schoonmaak" in blob:
            return "schoonmaak"
        if any(token in blob for token in ("haccp", "temperatuur", "vriezer", "hygiëne", "hygiene")):
            return "haccp"
        if any(token in blob for token in ("horeca", "restaurant", "keuken", "nvwa")):
            return "horeca"
        return "generic"


def keyword_fallback(data: dict, slug: str) -> str:
    return data.get("seo_title", slug.replace("-", " ").title())
