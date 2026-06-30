"""AI-gestuurde chatrouter — begrijp natuurlijke taal en kies acties."""

from __future__ import annotations

import json
from typing import Any

from app.ai.brain import AIBrain
from app.utils.files import extract_json_from_response
from app.utils.logger import setup_logger

logger = setup_logger(__name__)

CHAT_ACTIONS = {
    "chat",
    "status",
    "report",
    "compare_dates",
    "kansen",
    "stijgers",
    "dalers",
    "create_blog",
    "create_blogs_batch",
    "create_page",
    "improve_page",
    "approve",
    "cancel",
    "hold",
    "pending",
    "push",
    "next",
    "help",
}


class ChatRouter:
    def __init__(self) -> None:
        self.brain = AIBrain()

    def route(self, message: str, context: dict[str, Any], history: list[dict[str, str]]) -> dict[str, Any]:
        """Bepaal intent + parameters uit vrije tekst."""
        history_lines = "\n".join(
            f"{h.get('role', 'user').upper()}: {h.get('content', '')[:300]}"
            for h in history[-8:]
        )
        prompt = f"""
Je bent de router voor de TaskCheck SEO Agent (Telegram chatbot).
Anwar spreekt je aan in het Nederlands — begrijp informele taal, spreektaal en korte berichten.

Recent gesprek:
{history_lines or "(geen eerdere berichten)"}

Huidige situatie:
{json.dumps(context, ensure_ascii=False, indent=2)}

Nieuw bericht van Anwar:
{message}

Kies ÉÉN intent en optionele parameters. Voorbeelden:
- "hoe gaat seo" / "status deze week" → status, period=week
- "stuur rapport" / "dagelijks overzicht" → report
- "vergelijk 28 juni en 29 juni" / "seo checken 28-06 vs 29-06" → compare_dates
- "grootste kansen" / "wat moet ik doen" → kansen (NIET next bij rapport/vergelijk!)
- "maak de blogs" / "schrijf ze maar" (na blog-ideeën) → create_blogs_batch
- "maak blog over NVWA" → create_blog, topic=NVWA inspecties
- "nieuwe pagina voor haccp app" / "yes omzetten naar seo pagina" → create_page
- "verbeter horeca-check-app" → improve_page, slug=...
- "ja doe maar" / "goedkeuren" (met open concept) → approve
- "push naar live" / "deploy" → push
- "wat wacht er" → pending
- gewone vraag over SEO/data → chat

BELANGRIJK:
- Bij "maak de blogs" na een lijst met onderwerpen: create_blogs_batch (niet chat).
- Bij "yes omzetten naar seo pagina" na een outline: create_page (niet alleen tekst teruggeven).
- Bij datumvergelijkingen: compare_dates (niet next of report tenzij expliciet rapport gevraagd).
- next alleen bij expliciet "volgende kans" — niet bij SEO-vragen of rapporten.

Geef ALLEEN geldige JSON:
{{
  "intent": "chat|status|report|compare_dates|kansen|stijgers|dalers|create_blog|create_blogs_batch|create_page|improve_page|approve|cancel|hold|pending|push|next|help",
  "params": {{
    "topic": "",
    "keyword": "",
    "slug": "",
    "period": ""
  }},
  "confidence": 0.0
}}
"""
        raw = self.brain._ask_json(
            prompt,
            system=(
                "Je classificeert berichten voor een SEO-assistent. "
                "Antwoord alleen met JSON. Bij twijfel: chat. "
                "approve alleen als er een pending_action in de context staat."
            ),
            temperature=0.2,
        )
        try:
            data = extract_json_from_response(raw)
        except json.JSONDecodeError:
            logger.warning("Chat router parse mislukt: %s", raw[:200])
            return {"intent": "chat", "params": {}, "confidence": 0.0}

        intent = str(data.get("intent", "chat")).lower().strip()
        if intent not in CHAT_ACTIONS:
            intent = "chat"
        params = data.get("params") or {}
        if not isinstance(params, dict):
            params = {}
        return {
            "intent": intent,
            "params": params,
            "confidence": float(data.get("confidence", 0.5)),
        }

    def reply(
        self,
        message: str,
        context: dict[str, Any],
        history: list[dict[str, str]],
    ) -> str:
        return self.brain.chat_response(message, context, history)
