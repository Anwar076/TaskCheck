"""Telegram notificaties voor de SEO agent."""

from __future__ import annotations

import requests

from app.utils.config import get_config
from app.utils.logger import setup_logger

logger = setup_logger(__name__)


class Notifier:
    def __init__(self) -> None:
        config = get_config()
        self.token = config.telegram_token
        self.chat_id = config.chat_id
        self.base_url = f"https://api.telegram.org/bot{self.token}"

    def send(self, text: str, parse_mode: str | None = None, reply_markup: dict | None = None) -> bool:
        if not self.token or not self.chat_id:
            logger.warning("Telegram niet geconfigureerd")
            return False

        payload: dict = {"chat_id": self.chat_id, "text": text[:4096]}
        if parse_mode:
            payload["parse_mode"] = parse_mode
        if reply_markup:
            payload["reply_markup"] = reply_markup

        try:
            response = requests.post(
                f"{self.base_url}/sendMessage",
                json=payload,
                timeout=30,
            )
            response.raise_for_status()
            return True
        except requests.RequestException as exc:
            logger.error("Telegram fout: %s", exc)
            return False

    def _approval_keyboard(self) -> dict:
        return {
            "inline_keyboard": [
                [
                    {"text": "✅ Toepassen", "callback_data": "approve"},
                    {"text": "❌ Annuleren", "callback_data": "cancel"},
                ],
                [{"text": "⏸ Later", "callback_data": "hold"}],
            ]
        }

    def notify_new_page(self, data: dict) -> None:
        text = f"""🤖 Nieuwe SEO-pagina aangemaakt

Zoekwoord:
{data.get('keyword', '')}

Waarom:
{data.get('reason', '')}

Verwachte impact:
{data.get('expected_impact', 'Meer impressies, klikken en hogere CTR')}

Bestand:
{data.get('path', '')}

Status:
Wacht op goedkeuring

Typ "ja toepassen" of klik hieronder:"""
        self.send(text, reply_markup=self._approval_keyboard())

    def notify_page_optimized(self, data: dict) -> None:
        improvements = data.get("improvements", {})
        items = improvements.get("improvements", [])
        improvement_lines = "\n".join(f"- {i.get('description', '')}" for i in items[:8])

        text = f"""✏️ Pagina geoptimaliseerd

Pagina: {data.get('slug', '')}

Verbeteringen:
{improvement_lines or '- Zie pending bestand'}

Verwachte stijging:
Positie {improvements.get('expected_position_change', '?')}
CTR {improvements.get('expected_ctr_change', '?')}

Status: Wacht op goedkeuring

Typ "ja toepassen" of klik hieronder:"""
        self.send(text, reply_markup=self._approval_keyboard())

    def notify_new_blog(self, data: dict) -> None:
        text = f"""📰 Nieuw blogconcept aangemaakt

Onderwerp:
{data.get('topic', '')}

Waarom:
{data.get('reason', 'Topical authority versterken rond horeca/NVWA onderwerpen')}

Bestand:
{data.get('path', '')}

Status:
Wacht op goedkeuring

Typ "ja toepassen" of klik hieronder:"""
        self.send(text, reply_markup=self._approval_keyboard())

    def notify_daily_report(self, report_text: str) -> None:
        self.send(report_text)

    def notify_error(self, message: str) -> None:
        self.send(f"⚠️ SEO Agent fout\n\n{message}")

    def notify_action_approved(self, action: dict) -> None:
        self.send(f"✅ Goedgekeurd: {action.get('type', '')} — {action.get('keyword') or action.get('slug', '')}")

    def notify_action_cancelled(self, action: dict) -> None:
        self.send(f"❌ Geannuleerd: {action.get('type', '')} — {action.get('keyword') or action.get('slug', '')}")

    def notify_opportunity_alert(self, decision: dict, analysis: dict) -> None:
        action = decision.get("action", "monitor")
        label = (
            decision.get("keyword")
            or decision.get("topic")
            or decision.get("target_page")
            or decision.get("slug")
            or "—"
        )
        action_labels = {
            "create_page": "nieuwe SEO-pagina",
            "improve_page": "bestaande pagina verbeteren",
            "improve_ctr": "CTR/snippet verbeteren",
            "create_blog": "nieuw blogartikel",
        }
        action_text = action_labels.get(action, action)

        rising = analysis.get("trends", {}).get("rising", [])[:2]
        rising_lines = "\n".join(
            f"• {r['query']} ({r.get('prev_position', '?')} → {r['position']})"
            for r in rising
        ) or "• Geen opvallende stijgers"

        text = f"""🎯 SEO-kans om te benutten

Actie: {action_text}
Onderwerp: {label}

Waarom nu:
{decision.get('reason', 'Op basis van Search Console data')}

Verwachte impact:
{decision.get('expected_impact', 'Meer impressies, klikken en betere posities')}

Stijgers:
{rising_lines}

Wat kun je doen?
• Stuur /volgende om een concept te laten maken
• Stuur /blog [onderwerp] voor een blog
• Stuur /nieuw [zoekwoord] voor een SEO-pagina
• Typ je vraag — ik antwoord met data"""
        self.send(text)
