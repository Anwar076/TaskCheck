"""Maak een SEO-pagina beter citeerbaar voor AI-antwoorden."""

from __future__ import annotations

import re
from typing import Any

from app.ai.brain import AIBrain
from app.seo.optimizer import PageOptimizer
from app.utils.config import get_config
from app.utils.files import read_text, write_blade
from app.utils.logger import setup_logger

logger = setup_logger(__name__)

FAQ_SCHEMA_SNIPPET = """
@push('head')
<script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "FAQPage",
        "mainEntity": [
            @foreach($faqItems as $i => [$q, $a])
            {
                "@@type": "Question",
                "name": @json($q),
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": @json($a)
                }
            }@if(!$loop->last),@endif
            @endforeach
        ]
    }
    </script>
@endpush
"""


class AEOOptimizer:
    def __init__(self) -> None:
        self.config = get_config()
        self.brain = AIBrain()
        self.page_optimizer = PageOptimizer()

    def optimize_page(self, slug: str) -> dict[str, Any]:
        page_path = self.config.seo_views_dir / f"{slug}.blade.php"
        if not page_path.exists():
            raise FileNotFoundError(f"Pagina niet gevonden: {slug}")

        content = read_text(page_path)
        if "$faqItems" not in content and "@php" in content:
            content = content.replace("@php", "@php\n        $faqItems = [\n        ];", 1)
        keyword = slug.replace("-", " ")
        improvements = self.brain.generate_aeo_improvements(slug, content)
        optimized = self.page_optimizer._apply_improvements(content, improvements)
        optimized = self._ensure_faq_schema(optimized)
        optimized = self._ensure_definition(optimized, improvements, keyword)

        backup_path = self.config.pending_dir / f"{slug}.optimized.blade.php"
        self.config.pending_dir.mkdir(parents=True, exist_ok=True)
        write_blade(backup_path, optimized)

        return {
            "slug": slug,
            "keyword": keyword,
            "improvements": improvements,
            "pending_path": str(backup_path),
            "original_path": str(page_path),
            "mode": "aeo",
        }

    def _ensure_faq_schema(self, content: str) -> str:
        if "FAQPage" in content:
            return content
        if "$faqItems" not in content:
            return content
        if "@push('head')" in content:
            return content.replace("@push('head')", "@push('head')\n" + FAQ_SCHEMA_SNIPPET.strip() + "\n", 1)
        if "@extends('layouts.seo-page')" in content:
            return content.replace(
                "@extends('layouts.seo-page')",
                "@extends('layouts.seo-page')\n\n" + FAQ_SCHEMA_SNIPPET.strip(),
                1,
            )
        return content

    def _ensure_definition(self, content: str, improvements: dict[str, Any], keyword: str) -> str:
        definition = (improvements.get("definition") or "").strip()
        if not definition:
            return content
        if re.search(r"wat is\b", content, re.IGNORECASE):
            return content
        extra = improvements.get("extra_content_section")
        if extra:
            return content
        title = keyword[:1].upper() + keyword[1:]
        block = f"""
        <section class="bg-white py-12 sm:py-16">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-slate-900">Wat is {title}?</h2>
                <p class="mt-4 text-base leading-relaxed text-slate-600">{definition}</p>
            </div>
        </section>
"""
        return self.page_optimizer._insert_extra_section(content, block)
