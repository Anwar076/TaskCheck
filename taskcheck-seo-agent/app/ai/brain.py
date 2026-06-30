"""AI Brain — centrale besluitvorming en contentgeneratie."""

from __future__ import annotations

import json
from typing import Any

from openai import OpenAI

from app.seo.page_registry import get_seo_route_names
from app.utils.config import get_config
from app.utils.files import extract_json_from_response, load_company_context, read_text
from app.utils.logger import setup_logger

logger = setup_logger(__name__)


class AIBrain:
    def __init__(self) -> None:
        config = get_config()
        self.client = OpenAI(api_key=config.openai_api_key)
        self.model = config.openai_model
        self.company_context = load_company_context(config.company_context_path)

    def _ask(self, prompt: str, system: str | None = None, temperature: float = 0.7) -> str:
        messages = []
        if system:
            messages.append({"role": "system", "content": system})
        messages.append({"role": "user", "content": prompt})

        response = self.client.chat.completions.create(
            model=self.model,
            messages=messages,
            temperature=temperature,
        )
        return response.choices[0].message.content or ""

    def _ask_json(self, prompt: str, system: str | None = None, temperature: float = 0.3) -> str:
        return self._ask(prompt, system=system, temperature=temperature)

    def _ask_with_history(
        self,
        message: str,
        system: str,
        history: list[dict[str, str]] | None = None,
        extra_context: str | None = None,
        temperature: float = 0.7,
    ) -> str:
        messages: list[dict[str, str]] = [{"role": "system", "content": system}]
        for item in (history or [])[-14:]:
            role = item.get("role", "user")
            if role in ("user", "assistant"):
                messages.append({"role": role, "content": item.get("content", "")[:2000]})
        user_content = message
        if extra_context:
            user_content = f"{extra_context}\n\nBericht:\n{message}"
        messages.append({"role": "user", "content": user_content})
        response = self.client.chat.completions.create(
            model=self.model,
            messages=messages,
            temperature=temperature,
        )
        return response.choices[0].message.content or ""

    def analyze_opportunities(self, analysis: dict[str, Any], handled: list[str] | None = None) -> dict[str, Any]:
        """Laat AI de beste actie kiezen met onderbouwing."""
        handled = handled or []
        skipped = analysis.get("skipped_handled", [])

        prompt = f"""
{self.company_context}

Je bent een Senior SEO Specialist voor TaskCheck.

Analyseer ALLE Search Console data en kies de BESTE NIEUWE actie voor vandaag.
Kies een ander zoekwoord dan recent afgehandelde items.

Recent afgehandeld (NIET opnieuw voorstellen):
{json.dumps(handled, ensure_ascii=False)}

Overgeslagen omdat al gedaan:
{json.dumps(skipped, ensure_ascii=False, indent=2)}

Samenvatting:
{json.dumps(analysis.get('summary', {}), ensure_ascii=False, indent=2)}

Trends (stijgers/dalers/nieuw):
{json.dumps({
    'rising': analysis.get('trends', {}).get('rising', [])[:5],
    'falling': analysis.get('trends', {}).get('falling', [])[:5],
    'new': analysis.get('trends', {}).get('new', [])[:5],
}, ensure_ascii=False, indent=2)}

Nieuwe pagina kansen (nog niet gedaan):
{json.dumps(analysis.get('new_page_opportunities', [])[:8], ensure_ascii=False, indent=2)}

Verbeter kansen:
{json.dumps(analysis.get('improve_opportunities', [])[:8], ensure_ascii=False, indent=2)}

Lage CTR kansen:
{json.dumps(analysis.get('almost_page_one', [])[:5], ensure_ascii=False, indent=2)}

Blog kansen:
{json.dumps(analysis.get('blog_opportunities', [])[:6], ensure_ascii=False, indent=2)}

Geef ALLEEN geldige JSON terug:
{{
  "action": "create_page" | "improve_page" | "improve_ctr" | "create_blog" | "monitor",
  "keyword": "",
  "target_page": "",
  "slug": "",
  "priority": "high" | "medium" | "low",
  "reason": "",
  "expected_impact": "",
  "why_now": ""
}}
"""
        result = self._ask(prompt, system="Je bent een ervaren SEO specialist. Kies variatie — niet steeds hetzelfde zoekwoord. Antwoord alleen in JSON.")
        try:
            decision = extract_json_from_response(result)
        except json.JSONDecodeError:
            logger.error("Kon AI-analyse niet parsen: %s", result[:200])
            fallback = analysis.get("new_page_opportunities", [{}])
            best = fallback[0] if fallback else {}
            decision = {
                "action": "create_page" if best else "monitor",
                "keyword": best.get("keyword", ""),
                "slug": best.get("slug", ""),
                "priority": "medium",
                "reason": best.get("reason", "Geen duidelijke kans gevonden"),
                "expected_impact": "Meer organische impressies en klikken",
                "why_now": "Op basis van Search Console data",
            }

        keyword = (decision.get("keyword") or "").lower()
        slug = decision.get("slug") or ""
        if keyword in [h.lower() for h in handled] or slug in handled:
            candidates = []
            for opp in analysis.get("new_page_opportunities", []):
                candidates.append({**opp, "action": "create_page"})
            for opp in analysis.get("improve_opportunities", []):
                candidates.append({**opp, "action": "improve_page"})
            for opp in analysis.get("almost_page_one", []):
                candidates.append({**opp, "action": "improve_ctr"})
            for opp in analysis.get("blog_opportunities", []):
                candidates.append({
                    **opp,
                    "action": "create_blog",
                    "keyword": opp.get("topic", ""),
                })
            if candidates:
                candidates.sort(key=lambda x: x.get("priority_score", 0), reverse=True)
                next_action = candidates[0]
                decision = {
                    "action": next_action["action"],
                    "keyword": next_action.get("keyword", ""),
                    "slug": next_action.get("slug", ""),
                    "target_page": next_action.get("page", next_action.get("slug", "")),
                    "priority": "medium",
                    "reason": next_action.get("reason", ""),
                    "expected_impact": "Meer impressies en klikken",
                    "why_now": "Volgende beste kans na recent afgehandeld item",
                }
        return decision

    def generate_page_content(
        self,
        keyword: str,
        competitor_insights: dict[str, Any] | None = None,
    ) -> dict[str, Any]:
        """Genereer gestructureerde content voor een SEO-pagina."""
        reference = ""
        config = get_config()
        if config.reference_page.exists():
            reference = read_text(config.reference_page)[:8000]

        competitor_text = ""
        if competitor_insights:
            competitor_text = f"\nConcurrentie-inzichten:\n{json.dumps(competitor_insights, ensure_ascii=False, indent=2)}"

        valid_routes = ", ".join(get_seo_route_names()[:40])
        prompt = f"""
{self.company_context}

Schrijf content voor een SEO landingspagina op TaskCheck.nl.

Zoekwoord: {keyword}

Beschikbare Laravel route-namen voor related_links (gebruik ALLEEN deze exacte namen):
{valid_routes}, pricing, register, blog

Gebruik dezelfde stijl, toon en structuur als deze referentiepagina (HACCP app):
{reference[:6000]}
{competitor_text}

Geef ALLEEN geldige JSON terug met deze structuur:
{{
  "seo_title": "max 60 tekens, zoekwoord vooraan",
  "seo_description": "max 155 tekens, met CTA",
  "seo_keywords": "komma-gescheiden keywords",
  "badge": "korte badge tekst",
  "h1_part1": "tekst voor H1",
  "h1_highlight": "geaccentueerd woord in H1",
  "intro_p1": "eerste intro alinea",
  "intro_p2": "tweede intro alinea",
  "trust_badges": ["Geen creditcard nodig", "14 dagen gratis proberen", "..."],
  "stats": [
    {{"title": "Temperatuur", "subtitle": "koeling & vriezer"}},
    {{"title": "Schoonmaak", "subtitle": "roosters & taken"}}
  ],
  "section_why_title": "Waarom ...",
  "section_why_text": "HTML met <p> tags",
  "problems": ["probleem 1", "probleem 2"],
  "section_features_title": "Wat kun je registreren",
  "features_list": ["feature 1", "feature 2"],
  "section_checklists_title": "Controles digitaal uitvoeren",
  "section_checklists_text": "HTML met <p> tags",
  "checklist_chips": ["Opening keuken", "Sluiting restaurant"],
  "section_benefits_title": "Voordelen",
  "benefits": ["voordeel 1", "voordeel 2"],
  "section_targets_title": "Voor wie geschikt",
  "targets": ["Restaurants", "Hotels"],
  "faq": [
    {{"question": "...", "answer": "..."}}
  ],
  "cta_title": "Start met ...",
  "cta_text": "CTA tekst",
  "related_links": [
    {{"label": "HACCP app", "route": "seo.haccp-app"}},
    {{"label": "Temperatuurregistratie", "route": "seo.temperatuurregistratie-horeca"}}
  ],
  "hero_image": "images/taskcheck-horeca-seo-hero.webp",
  "hero_alt": "alt tekst voor hero afbeelding",
  "hero_caption": "korte caption onder hero"
}}

Schrijf in het Nederlands. Focus op horeca, HACCP, NVWA en digitale checklists.
Minimaal 5 FAQ items. Minimaal 6 features en 6 benefits.
"""
        result = self._ask(prompt, system="Je bent een SEO copywriter voor TaskCheck. Antwoord alleen in JSON.")
        return extract_json_from_response(result)

    def generate_improvements(
        self,
        page_slug: str,
        page_content: str,
        gsc_data: dict[str, Any] | None = None,
        competitor_insights: dict[str, Any] | None = None,
    ) -> dict[str, Any]:
        """Genereer verbeteringen voor een bestaande pagina."""
        valid_routes = ", ".join(get_seo_route_names()[:40])
        prompt = f"""
{self.company_context}

Je bent een Senior SEO Specialist. Analyseer deze bestaande SEO-pagina en stel verbeteringen voor.

Pagina: {page_slug}

Beschikbare Laravel route-namen voor internal_links_to_add (gebruik ALLEEN deze exacte namen):
{valid_routes}, pricing, register, blog

Huidige content (fragment):
{page_content[:12000]}

GSC data:
{json.dumps(gsc_data or {}, ensure_ascii=False, indent=2)}

Concurrentie:
{json.dumps(competitor_insights or {}, ensure_ascii=False, indent=2)}

Geef ALLEEN geldige JSON:
{{
  "improvements": [
    {{"type": "faq|meta|content|links|structured_data", "description": "...", "priority": "high|medium|low"}}
  ],
  "new_faq_items": [{{"question": "...", "answer": "..."}}],
  "seo_title": "verbeterde title of null",
  "seo_description": "verbeterde description of null",
  "extra_content_section": "HTML sectie om toe te voegen of null",
  "internal_links_to_add": [{{"label": "...", "route": "seo.xxx"}}],
  "internal_links_to_add_note": "Alleen routes uit de lijst hierboven; voeg links toe aan de sectie Gerelateerde pagina's, NIET aan trust badges",
  "expected_position_change": "8 → 5",
  "expected_ctr_change": "+1.2%",
  "summary": "korte samenvatting voor Telegram"
}}
"""
        result = self._ask(prompt, system="Je bent een SEO specialist. Antwoord alleen in JSON.")
        return extract_json_from_response(result)

    def generate_blog_content(self, topic: str, source: str = "") -> dict[str, Any]:
        """Genereer blog-content voor TaskCheck (inclusief nieuws/NVWA onderwerpen)."""
        reference = ""
        config = get_config()
        if config.reference_blog.exists():
            reference = read_text(config.reference_blog)[:7000]

        source_hint = f"Bron of context: {source}" if source else "Geen externe bron verplicht."
        prompt = f"""
{self.company_context}

Schrijf een Nederlandstalig blogartikel voor TaskCheck.
Onderwerp: {topic}
{source_hint}

Houd rekening met horeca/restaurant context, NVWA, HACCP en operationele controles.
Geen sensatie of juridische claims; praktisch, feitelijk en behulpzaam.

Gebruik als stijlreferentie:
{reference}

Geef ALLEEN geldige JSON:
{{
  "seo_title": "max 65 tekens",
  "seo_description": "max 155 tekens",
  "h1": "duidelijke kop",
  "intro": "korte intro",
  "category": "Nieuws|Horeca|NVWA|Praktijk",
  "source_name": "bijv. NVWA, TaskCheck redactie",
  "published_at_iso": "2026-06-29T08:00:00+02:00",
  "published_at_label": "29 jun 2026",
  "read_minutes": "6 min lezen",
  "hero_image": "images/taskcheck-horeca-blog-hero.webp",
  "hero_alt": "beschrijving afbeelding",
  "sections": [
    {{"title":"...", "body_html":"<p>...</p><p>...</p>"}}
  ],
  "related_routes": [
    {{"label":"...", "route":"seo.horeca-app"}},
    {{"label":"...", "route":"blog"}}
  ]
}}

Minimaal 4 secties.
"""
        result = self._ask(prompt, system="Je bent een SEO redacteur. Antwoord alleen in JSON.")
        return extract_json_from_response(result)

    def chat_response(
        self,
        message: str,
        context: dict[str, Any],
        history: list[dict[str, str]] | None = None,
    ) -> str:
        """Beantwoord een vraag via Telegram chat — met gespreksgeheugen."""
        owner = get_config().owner_name or "Anwar"
        system = f"""{self.company_context}

Je bent de TaskCheck SEO Agent — een proactieve Senior SEO specialist én vriendelijke chatbot.
Je praat met {owner} als collega: warm, direct, in het Nederlands (B1).

Je kunt:
- SEO-data uitleggen (Search Console, rankings, CTR, kansen)
- Advies geven over pagina's, blogs en optimalisatie
- Uitleggen hoe goedkeuren/push werkt (concept → ja toepassen → push)

Wees conversationeel. Geen lange commandolijsten tenzij gevraagd.
Gebruik emoji spaarzaam. Geef concrete vervolgstappen als dat helpt.
Als er een open concept is (pending_action), verwijs daar kort naar."""

        extra = f"Huidige data-context:\n{json.dumps(context, ensure_ascii=False, indent=2)}"
        return self._ask_with_history(
            message,
            system=system,
            history=history,
            extra_context=extra,
            temperature=0.75,
        )

    def format_daily_advice(self, analysis: dict[str, Any]) -> str:
        """Genereer kort dagelijks advies."""
        decision = self.analyze_opportunities(analysis)
        return decision.get("reason", "") + " — " + decision.get("expected_impact", "")
