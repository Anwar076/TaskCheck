"""Proactieve SEO-kans meldingen via Telegram."""

from __future__ import annotations

from typing import Any

from app.ai.brain import AIBrain
from app.memory.store import MemoryStore
from app.seo.analyzer import SEOAnalyzer
from app.telegram.notifications import Notifier
from app.utils.config import get_config
from app.utils.logger import setup_logger

logger = setup_logger(__name__)


class OpportunityAlerter:
    def __init__(self) -> None:
        self.config = get_config()
        self.analyzer = SEOAnalyzer()
        self.brain = AIBrain()
        self.memory = MemoryStore()
        self.notifier = Notifier()

    def run(self) -> dict[str, Any]:
        """Scan kansen en stuur max. 1 alert als er iets interessants is."""
        if not self.config.proactive_alerts:
            return {"sent": False, "reason": "proactive_alerts uit"}

        handled = list(self.memory.get_completed_keywords()) + list(self.memory.get_completed_slugs())
        analysis = self.analyzer.find_opportunities(exclude_handled=True)
        decision = self.brain.analyze_opportunities(analysis, handled=handled)

        action = decision.get("action", "monitor")
        if action == "monitor":
            logger.info("Geen urgente kans voor alert")
            return {"sent": False, "reason": "monitor", "decision": decision}

        opp_key = self._opportunity_key(decision)
        if not self.memory.should_notify_opportunity(opp_key, self.config.opportunity_alert_cooldown_hours):
            logger.info("Kans recent al gemeld: %s", opp_key)
            return {"sent": False, "reason": "cooldown", "key": opp_key}

        self.notifier.notify_opportunity_alert(decision, analysis)
        self.memory.mark_notified_opportunity(opp_key)
        logger.info("Kans-alert verstuurd: %s", opp_key)
        return {"sent": True, "key": opp_key, "decision": decision}

    def _opportunity_key(self, decision: dict[str, Any]) -> str:
        parts = [
            decision.get("action", ""),
            (decision.get("keyword") or decision.get("topic") or "").lower().strip(),
            (decision.get("slug") or decision.get("target_page") or "").lower().strip(),
        ]
        return "|".join(p for p in parts if p)
