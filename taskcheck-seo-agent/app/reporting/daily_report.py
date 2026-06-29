"""Dagelijks SEO rapport genereren."""

from __future__ import annotations

from typing import Any

from app.ai.brain import AIBrain
from app.competitor.analyzer import CompetitorAnalyzer
from app.memory.store import MemoryStore
from app.seo.analyzer import SEOAnalyzer
from app.utils.config import get_config
from app.utils.logger import setup_logger

logger = setup_logger(__name__)


class DailyReporter:
    def __init__(self) -> None:
        self.config = get_config()
        self.analyzer = SEOAnalyzer()
        self.brain = AIBrain()
        self.competitor = CompetitorAnalyzer()
        self.memory = MemoryStore()

    def generate(self) -> str:
        handled = list(self.memory.get_completed_keywords())
        analysis = self.analyzer.find_opportunities(exclude_handled=True)
        decision = self.brain.analyze_opportunities(analysis, handled=handled)

        summary = analysis["summary"]
        cur = summary["current"]
        prev = summary["previous"]
        changes = summary["changes"]
        trends = analysis.get("trends", {})

        rising = trends.get("rising", [])[:3]
        falling = trends.get("falling", [])[:3]
        new_kw = trends.get("new", [])[:5]
        new_opps = analysis.get("new_page_opportunities", [])[:5]
        improve_opps = analysis.get("improve_opportunities", [])[:3]
        skipped = analysis.get("skipped_handled", [])

        best_riser = rising[0]["query"] if rising else "—"
        new_opp_lines = "\n".join(
            f"- {o['keyword']} (pos {o['position']}, {o['impressions']} imp)"
            for o in new_opps
        ) or "- Geen nieuwe pagina-kansen"
        improve_lines = "\n".join(
            f"- {o.get('keyword') or o.get('slug', o.get('page', '?'))}"
            for o in improve_opps
        ) or "- Geen verbeter-kansen"
        stijger_lines = "\n".join(
            f"- {r['query']} ({r.get('prev_position', '?')} → {r['position']})"
            for r in rising
        ) or "- Geen stijgers"
        daler_lines = "\n".join(
            f"- {f['query']} ({f.get('prev_position', '?')} → {f['position']})"
            for f in falling
        ) or "- Geen dalers"

        top_keyword = (
            decision.get("keyword")
            or decision.get("target_page")
            or (new_opps[0]["keyword"] if new_opps else "—")
        )
        competitor_note = self._competitor_note(top_keyword if top_keyword != "—" else "")

        skipped_note = ""
        if skipped:
            skipped_note = "\n\nRecent afgehandeld (overgeslagen vandaag):\n" + "\n".join(
                f"- {s['keyword']}" for s in skipped[:3]
            )

        report = f"""Goedemorgen {self.config.owner_name} 👋

SEO Rapport — TaskCheck
({analysis.get('analyzed_queries', '?')} zoekwoorden geanalyseerd)

Impressies:
{changes['impressions']:+,} ({cur['impressions']:,} totaal)

Klikken:
{changes['clicks']:+,} ({cur['clicks']:,} totaal)

CTR:
{changes['ctr']:+.1f}% ({cur['ctr']}%)

Gemiddelde positie:
{prev['position']} → {cur['position']} ({changes['position']:+.1f})

Nieuwe zoekwoorden:
{len(new_kw)}

📈 Stijgers:
{stijger_lines}

📉 Dalers:
{daler_lines}

🎯 Nieuwe pagina-kansen:
{new_opp_lines}

✏️ Verbeter-kansen:
{improve_lines}{skipped_note}

Concurrenten:
{competitor_note}

Advies vandaag:
{decision.get('reason', 'Geen actie nodig')}

Verwachte impact:
{decision.get('expected_impact', '—')}

Actie:
{decision.get('action', 'monitor')} — {top_keyword}"""

        self.memory.save_report({
            "analysis": self._serialize_analysis(analysis),
            "decision": decision,
            "report_text": report,
        })

        return report

    def _competitor_note(self, keyword: str) -> str:
        if not keyword:
            return "Geen concurrentie-analyse vandaag"
        try:
            data = self.competitor.compare_with_taskcheck(keyword)
            gaps = data.get("content_gaps", [])
            if gaps:
                return gaps[0]
            competitors = data.get("competitors", [])
            if competitors:
                return f"{competitors[0].get('domain', 'Concurrent')} heeft {competitors[0].get('word_count', 0)} woorden op homepage"
            return "Geen opvallende concurrentie-wijzigingen"
        except Exception:
            return "Concurrentie-analyse niet beschikbaar"

    def _serialize_analysis(self, analysis: dict[str, Any]) -> dict[str, Any]:
        return {
            "summary": analysis.get("summary", {}),
            "new_opportunities_count": len(analysis.get("new_page_opportunities", [])),
            "improve_count": len(analysis.get("improve_opportunities", [])),
        }
