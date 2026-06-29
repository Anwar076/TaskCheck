"""SEO kansen detecteren op basis van Search Console data."""

from __future__ import annotations

from typing import Any

from app.gsc.client import GSCClient
from app.memory.store import MemoryStore
from app.seo.page_registry import get_page_registry
from app.utils.config import get_config
from app.utils.files import load_lines, slugify
from app.utils.logger import setup_logger

logger = setup_logger(__name__)


class SEOAnalyzer:
    def __init__(self) -> None:
        self.config = get_config()
        self.gsc = GSCClient()
        self.registry = get_page_registry()
        self.memory = MemoryStore()

    def _existing_slugs(self) -> set[str]:
        return set(self.registry.list_slugs())

    def _is_relevant(self, query: str) -> bool:
        allowed = load_lines(self.config.allowed_keywords_path)
        if not allowed:
            return True
        q = query.lower()
        return any(term in q for term in allowed)

    def _is_excluded(self, query: str, slug: str, exclude_keywords: set[str], exclude_slugs: set[str]) -> bool:
        if slug in exclude_slugs:
            return True
        if query.lower() in exclude_keywords:
            return True
        return False

    def find_opportunities(
        self,
        exclude_handled: bool = True,
        days: int | None = None,
        trend_days: int | None = None,
        period_label: str | None = None,
    ) -> dict[str, Any]:
        """Detecteer SEO-kansen uit GSC data."""
        from app.gsc.periods import resolve_gsc_period

        if days is None:
            period = resolve_gsc_period()
            days = period.days
            trend_days = period.trend_days
            period_label = period.label
        else:
            trend_days = trend_days or max(1, min(days // 2, 90))
            period_label = period_label or f"afgelopen {days} dagen"

        exclude_slugs: set[str] = set()
        exclude_keywords: set[str] = set()
        if exclude_handled:
            exclude_slugs = self.memory.get_completed_slugs()
            exclude_keywords = self.memory.get_completed_keywords()

        summary = self.gsc.get_summary(days=days)
        queries = self.gsc.get_query_data(days=days)
        pages = self.gsc.get_page_data(days=days)
        trends = self.gsc.compare_queries(days=trend_days)
        existing_slugs = self._existing_slugs()

        new_page_opportunities = []
        blog_opportunities = []
        improve_opportunities = []
        low_ctr_pages = []
        almost_page_one = []
        skipped_handled = []

        for q in queries:
            query = q["query"]
            if not self._is_relevant(query):
                continue

            slug = slugify(query)

            if self._is_excluded(query, slug, exclude_keywords, exclude_slugs):
                if q["impressions"] >= 5:
                    skipped_handled.append({"keyword": query, "slug": slug, "reason": "recent afgehandeld"})
                continue

            existing = self.registry.find_match(query, slug)

            if existing:
                if q["impressions"] >= 10:
                    improve_opportunities.append({
                        "type": "improve_page",
                        "keyword": query,
                        "slug": existing.slug,
                        "page": existing.url,
                        "impressions": q["impressions"],
                        "clicks": q["clicks"],
                        "ctr": q["ctr"],
                        "position": q["position"],
                        "priority_score": q["impressions"] * (16 - min(q["position"], 15)),
                        "reason": f"Pagina bestaat al ({existing.slug}) — verbeteren i.p.v. nieuw: {existing.reason}",
                    })
                continue

            if q["impressions"] >= 5 and q["position"] <= 30:
                new_page_opportunities.append({
                    "type": "new_page",
                    "keyword": query,
                    "slug": slug,
                    "impressions": q["impressions"],
                    "clicks": q["clicks"],
                    "ctr": q["ctr"],
                    "position": q["position"],
                    "priority_score": self._score_new_page(q),
                    "reason": self._reason_new_page(q),
                })

            if q["position"] <= 15 and q["impressions"] >= 10 and q["ctr"] < 3:
                almost_page_one.append({
                    "type": "improve_ctr",
                    "keyword": query,
                    "impressions": q["impressions"],
                    "clicks": q["clicks"],
                    "ctr": q["ctr"],
                    "position": q["position"],
                    "priority_score": q["impressions"] * (15 - q["position"]),
                    "reason": f"Positie {q['position']} met lage CTR ({q['ctr']}%)",
                })

            q_lower = query.lower()
            if q["impressions"] >= 5 and any(
                term in q_lower
                for term in ("nvwa", "horeca", "restaurant", "voedselveilig", "haccp", "inspectie", "sluiting")
            ):
                blog_opportunities.append({
                    "type": "create_blog",
                    "topic": query,
                    "slug": slug,
                    "impressions": q["impressions"],
                    "clicks": q["clicks"],
                    "position": q["position"],
                    "priority_score": q["impressions"] * 1.4 + max(0, 25 - q["position"]),
                    "reason": f"Informatieve zoekintentie rondom '{query}' met {q['impressions']} impressies",
                    "source_hint": "Google Search Console trend",
                })

        for p in pages:
            if p["impressions"] >= 20 and p["ctr"] < 2:
                low_ctr_pages.append({
                    "type": "low_ctr_page",
                    "page": p["page"],
                    "impressions": p["impressions"],
                    "clicks": p["clicks"],
                    "ctr": p["ctr"],
                    "position": p["position"],
                    "priority_score": p["impressions"],
                    "reason": f"Lage CTR ({p['ctr']}%) bij {p['impressions']} impressies",
                })

            if 8 <= p["position"] <= 15 and p["impressions"] >= 15:
                improve_opportunities.append({
                    "type": "improve_page",
                    "page": p["page"],
                    "impressions": p["impressions"],
                    "clicks": p["clicks"],
                    "ctr": p["ctr"],
                    "position": p["position"],
                    "priority_score": p["impressions"] * (16 - p["position"]),
                    "reason": f"Bijna pagina 1: positie {p['position']}",
                })

        new_page_opportunities.sort(key=lambda x: x["priority_score"], reverse=True)
        improve_opportunities.sort(key=lambda x: x["priority_score"], reverse=True)
        low_ctr_pages.sort(key=lambda x: x["priority_score"], reverse=True)
        almost_page_one.sort(key=lambda x: x["priority_score"], reverse=True)
        blog_opportunities.sort(key=lambda x: x["priority_score"], reverse=True)

        return {
            "summary": summary,
            "trends": trends,
            "gsc_period": {
                "days": days,
                "trend_days": trend_days,
                "label": period_label,
                "start": summary.get("period", {}).get("start"),
                "end": summary.get("period", {}).get("end"),
            },
            "new_page_opportunities": new_page_opportunities[:10],
            "blog_opportunities": blog_opportunities[:10],
            "improve_opportunities": improve_opportunities[:10],
            "low_ctr_pages": low_ctr_pages[:10],
            "almost_page_one": almost_page_one[:10],
            "skipped_handled": skipped_handled[:5],
            "total_queries": len(queries),
            "existing_pages": len(existing_slugs),
            "analyzed_queries": len(queries),
        }

    def pick_next_action(self, analysis: dict[str, Any]) -> dict[str, Any] | None:
        """Kies de beste actie op priority score."""
        candidates = []
        for opp in analysis.get("new_page_opportunities", []):
            candidates.append({**opp, "action": "create_page"})
        for opp in analysis.get("improve_opportunities", []):
            candidates.append({**opp, "action": "improve_page"})
        for opp in analysis.get("almost_page_one", []):
            candidates.append({**opp, "action": "improve_ctr"})
        for opp in analysis.get("blog_opportunities", []):
            candidates.append({**opp, "action": "create_blog"})
        if not candidates:
            return None
        candidates.sort(key=lambda x: x.get("priority_score", 0), reverse=True)
        return candidates[0]

    def _score_new_page(self, q: dict) -> float:
        position_bonus = max(0, 20 - q["position"])
        return q["impressions"] * position_bonus + q["clicks"] * 5

    def _reason_new_page(self, q: dict) -> str:
        parts = [f"Positie {q['position']}", f"{q['impressions']} impressies"]
        if q["position"] <= 15:
            parts.append("bijna pagina 1")
        if q["clicks"] == 0:
            parts.append("nog geen klikken — kans op betere snippet")
        return ", ".join(parts)

    def get_best_action(self, analysis: dict[str, Any]) -> dict[str, Any] | None:
        """Kies de actie met hoogste verwachte impact."""
        return self.pick_next_action(analysis)
