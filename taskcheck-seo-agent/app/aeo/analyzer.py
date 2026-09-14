"""AEO-scanner: hoe citeerbaar is TaskCheck voor AI-antwoorden?"""

from __future__ import annotations

import re
from pathlib import Path
from typing import Any

from app.seo.page_registry import get_page_registry
from app.utils.config import get_config
from app.utils.files import read_text
from app.utils.logger import setup_logger

logger = setup_logger(__name__)

FAQ_VAR = re.compile(r"\$faqItems\s*=")
FAQ_SCHEMA = re.compile(r"FAQPage", re.IGNORECASE)
ANY_SCHEMA = re.compile(r"application/ld\+json", re.IGNORECASE)
WAT_IS = re.compile(r"wat is\b", re.IGNORECASE)
QUESTION_HEADING = re.compile(r"<h[23][^>]*>[^<]*\?", re.IGNORECASE)
IS_EEN = re.compile(r"\bis een\b", re.IGNORECASE)


class AEOAnalyzer:
    def __init__(self) -> None:
        self.config = get_config()
        self.registry = get_page_registry()

    def site_report(self, limit: int = 8) -> dict[str, Any]:
        self.registry.refresh()
        pages = [p for p in self.registry._pages if p.get("source") == "live" and p.get("path")]
        scored = [self.score_page(p) for p in pages]
        scored.sort(key=lambda item: (item["score"], item["slug"]))
        if not scored:
            return {
                "page_count": 0,
                "average_score": 0,
                "weak": [],
                "strong": [],
                "llms_ok": self.config.llms_txt_path.exists(),
            }

        average = round(sum(item["score"] for item in scored) / len(scored))
        return {
            "page_count": len(scored),
            "average_score": average,
            "weak": scored[:limit],
            "strong": list(reversed(scored[-3:])),
            "llms_ok": self.config.llms_txt_path.exists(),
            "missing_llms": sum(1 for item in scored if not item["checks"]["in_llms"]),
            "missing_faq": sum(1 for item in scored if not item["checks"]["has_faq"]),
            "missing_schema": sum(1 for item in scored if not item["checks"]["has_faq_schema"]),
        }

    def score_slug(self, slug: str) -> dict[str, Any]:
        page = self.registry.get_page(slug)
        if not page or not page.get("path"):
            raise FileNotFoundError(f"Geen live SEO-pagina: {slug}")
        return self.score_page(page)

    def score_page(self, page: dict[str, Any]) -> dict[str, Any]:
        path = Path(page.get("path") or "")
        content = read_text(path) if page.get("path") else ""
        llms = read_text(self.config.llms_txt_path) if self.config.llms_txt_path.exists() else ""
        slug = page["slug"]
        url_token = f"/{slug}"

        checks = {
            "has_faq": bool(FAQ_VAR.search(content)),
            "has_faq_schema": bool(FAQ_SCHEMA.search(content)),
            "has_jsonld": bool(ANY_SCHEMA.search(content)),
            "has_definition": bool(WAT_IS.search(content) or IS_EEN.search(content[:2500])),
            "has_question_headings": bool(QUESTION_HEADING.search(content)),
            "in_llms": url_token in llms or slug.replace("-", " ")[:18].lower() in llms.lower(),
        }
        score = 0
        if checks["has_faq"]:
            score += 25
        if checks["has_faq_schema"]:
            score += 20
        if checks["has_definition"]:
            score += 15
        if checks["has_question_headings"]:
            score += 10
        if checks["in_llms"]:
            score += 20
        if checks["has_jsonld"]:
            score += 10

        gaps: list[str] = []
        if not checks["has_faq"]:
            gaps.append("geen FAQ")
        if not checks["has_faq_schema"]:
            gaps.append("geen FAQPage-schema")
        if not checks["has_definition"]:
            gaps.append("geen duidelijke definitie (X is …)")
        if not checks["in_llms"]:
            gaps.append("niet in llms.txt")
        if not checks["has_jsonld"]:
            gaps.append("geen JSON-LD")

        return {
            "slug": slug,
            "title": page.get("title") or slug,
            "url": page.get("url", ""),
            "score": min(100, score),
            "checks": checks,
            "gaps": gaps,
        }
