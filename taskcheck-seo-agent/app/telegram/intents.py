"""Herken intenties in vrije tekst (Nederlands)."""

from __future__ import annotations

import re
from datetime import date
from typing import Any


DUTCH_MONTHS = {
    "januari": 1, "februari": 2, "maart": 3, "april": 4,
    "mei": 5, "juni": 6, "juli": 7, "augustus": 8,
    "september": 9, "oktober": 10, "november": 11, "december": 12,
}


def detect_intent(message: str) -> str | None:
    """Return workflow intent or None (→ AI chat router)."""
    text = message.lower().strip()
    text = re.sub(r"[^\w\sà-ü]", " ", text)
    text = re.sub(r"\s+", " ", text).strip()

    if not text:
        return None

    if text in {"/approve", "approve", "doorvoeren", "doorvoer"}:
        return "approve"

    if text in {"/cancel", "cancel"}:
        return "cancel"

    if text in {"/hold", "hold"}:
        return "hold"

    approve_patterns = [
        r"\b(goedkeur|keur goed|akkoord)\b",
        r"\b(toepas|pas toe|toegepast|live zet|publiceren|publiceer)\b",
        r"\b(doorvoer|doorvoeren|uitvoeren|live)\b",
        r"\b(doe maar|doen we|mag door|mag het|is goed)\b",
        r"\b(ja+\s*(doen|graag|is goed|akkoord)?)\b",
        r"\b(oke|oké|ok)\s*(doen|toepassen|graag)?\b",
        r"kan je dat (toepassen|doen|publiceren|live zetten)",
        r"kun je dat (toepassen|doen|publiceren|live zetten)",
        r"wil je dat (toepassen|doen|publiceren)",
        r"(apply|publish|deploy)\b",
    ]

    cancel_patterns = [
        r"\b(annuleer|annuleren|afkeur|niet doen|laat maar|negeren)\b",
        r"\b(nee+\s*(dank|doen|akkoord)?)\b",
        r"\b(verwijder concept|gooi weg)\b",
    ]

    hold_patterns = [
        r"\b(later|park|parkeer|bewaar|on hold|wacht)\b",
        r"\b(nog niet|niet nu)\b",
    ]

    pending_patterns = [
        r"\b(openstaande|pending|wachtend)\b",
        r"wat wacht (op|nog)",
    ]

    compare_dates_patterns = [
        r"\b(vergelijk|verschil|verschillen|tussen)\b.*\b(\d{1,2})\b",
        r"\bseo\b.*\b(vergelijk|check|checken)\b",
        r"\brapport\b.*\b(\d{1,2})[-/]\d{1,2}",
        r"\b(\d{1,2})[-/](\d{1,2})[-/](\d{4})\b.*\b(\d{1,2})[-/](\d{1,2})[-/](\d{4})\b",
    ]

    status_patterns = [
        r"\b(hoe gaat (de )?seo|seo status|status vandaag)\b",
        r"\b(welke pagina.s stijgen|pagina.s stijgen)\b",
        r"^/status\b",
    ]

    report_patterns = [
        r"\b(rapport|raport|report|dagelijks rapport|dagelijks overzicht)\b",
        r"\bgeef (een )?rapport\b",
        r"\bstuur (een )?rapport\b",
    ]

    push_patterns = [
        r"\b(push)\b.*\b(main|master|live|productie)\b",
        r"\b(naar main|naar master|naar live)\b",
        r"\b(merge naar main|merge naar master)\b",
        r"\bdeploy\b",
    ]

    create_blogs_batch_patterns = [
        r"\bmaak\s+(maar\s+)?(de\s+)?blogs?\b",
        r"\bschrijf\s+(maar\s+)?(de\s+)?blogs?\b",
        r"\bcre[eë]er\s+(maar\s+)?(de\s+)?blogs?\b",
        r"\bmaak\s+ze\s+(maar\s+)?(allemaal)?\b",
    ]

    create_blog_patterns = [
        r"(?:maak|schrijf|cre[eë]er).{0,25}(?:blog|artikel).{0,15}(?:over|about)\s+(.+)",
        r"(?:blog|artikel)\s+(?:over|about)\s+(.+)",
        r"\bmaak\s+(?:de\s+)?blog\b",
    ]

    create_page_patterns = [
        r"(?:maak|schrijf|cre[eë]er).{0,25}(?:pagina|seo.?pagina|landingspagina).{0,15}(?:voor|over)\s+(.+)",
        r"nieuwe?\s+(?:seo\s+)?pagina\s+(?:voor|over)\s+(.+)",
        r"\bomzetten\s+naar\s+(?:een\s+)?seo\s+pagina\b",
        r"\b(yes|ja|graag).{0,30}(?:seo\s+pagina|omzetten)\b",
        r"\bmaak\s+(?:de\s+)?seo\s+pagina\b",
    ]

    kansen_patterns = [
        r"\b(grootste kansen|seo kansen|wat moet ik doen|wat raad je)\b",
    ]

    next_patterns = [
        r"\b(volgende|next)\b",
        r"\b(nieuwe kans|andere kans|ander zoekwoord)\b",
        r"\bwat nu\b",
    ]

    # Vergelijk datums vóór rapport/status (voorkomt verkeerde routing)
    for pattern in compare_dates_patterns:
        if re.search(pattern, text) and extract_compare_dates(message):
            return "compare_dates"

    for pattern in approve_patterns:
        if re.search(pattern, text):
            return "approve"

    for pattern in cancel_patterns:
        if re.search(pattern, text):
            return "cancel"

    for pattern in hold_patterns:
        if re.search(pattern, text):
            return "hold"

    for pattern in pending_patterns:
        if re.search(pattern, text):
            return "pending"

    for pattern in status_patterns:
        if re.search(pattern, text):
            return "status"

    for pattern in report_patterns:
        if re.search(pattern, text):
            return "report"

    for pattern in push_patterns:
        if re.search(pattern, text):
            return "push_main"

    for pattern in create_blogs_batch_patterns:
        if re.search(pattern, text):
            return "create_blogs_batch"

    for pattern in create_page_patterns:
        if re.search(pattern, text):
            return "create_page"

    for pattern in create_blog_patterns:
        if re.search(pattern, text):
            return "create_blog"

    for pattern in kansen_patterns:
        if re.search(pattern, text):
            return "next"

    for pattern in next_patterns:
        if re.search(pattern, text):
            return "next"

    return None


def extract_create_blog_topic(message: str) -> str | None:
    text = message.strip()
    for pattern in (
        r"(?:maak|schrijf|cre[eë]er).{0,25}(?:blog|artikel).{0,15}(?:over|about)\s+(.+)",
        r"(?:blog|artikel)\s+(?:over|about)\s+(.+)",
    ):
        match = re.search(pattern, text, re.IGNORECASE)
        if match:
            return match.group(1).strip()
    return None


def extract_create_page_keyword(message: str) -> str | None:
    text = message.strip()
    for pattern in (
        r"(?:maak|schrijf|cre[eë]er).{0,25}(?:pagina|seo.?pagina|landingspagina).{0,15}(?:voor|over)\s+(.+)",
        r"nieuwe?\s+(?:seo\s+)?pagina\s+(?:voor|over)\s+(.+)",
    ):
        match = re.search(pattern, text, re.IGNORECASE)
        if match:
            return match.group(1).strip()
    return None


def _parse_date(day: int, month: int, year: int | None = None) -> date:
    year = year or date.today().year
    return date(year, month, day)


def extract_compare_dates(message: str) -> tuple[date, date] | None:
    """Haal twee datums uit bericht, bijv. 28-06-2026 & 29-06-2026 of 28 juni en 29 juni."""
    text = message.lower()

    iso_dates = re.findall(r"(\d{1,2})[-/](\d{1,2})[-/](\d{4})", text)
    if len(iso_dates) >= 2:
        d1 = _parse_date(int(iso_dates[0][0]), int(iso_dates[0][1]), int(iso_dates[0][2]))
        d2 = _parse_date(int(iso_dates[1][0]), int(iso_dates[1][1]), int(iso_dates[1][2]))
        return d1, d2

    dutch_found: list[date] = []
    for match in re.finditer(r"(\d{1,2})\s+([a-z]+)(?:\s+(\d{4}))?", text):
        day_s, month_s, year_s = match.group(1), match.group(2), match.group(3)
        month = DUTCH_MONTHS.get(month_s)
        if not month:
            continue
        year = int(year_s) if year_s else date.today().year
        try:
            dutch_found.append(_parse_date(int(day_s), month, year))
        except ValueError:
            continue

    if len(dutch_found) >= 2:
        return dutch_found[0], dutch_found[1]

    return None


def extract_blog_topics_from_history(history: list[dict[str, Any]]) -> list[str]:
    """Haal genummerde blog-onderwerpen uit het laatste assistent-bericht."""
    topics: list[str] = []
    for entry in reversed(history):
        if entry.get("role") != "assistant":
            continue
        content = entry.get("content", "")
        for match in re.finditer(r"\*\*\d+\.\s*([^*\n]+)", content):
            title = match.group(1).strip()
            if ":" in title:
                title = title.split(":", 1)[1].strip() or title.split(":", 1)[0].strip()
            if title and len(title) > 8:
                topics.append(title)
        if not topics:
            for match in re.finditer(r"(?m)^\d+\.\s+(.+)$", content):
                line = match.group(1).strip()
                line = re.sub(r"\*\*([^*]+)\*\*", r"\1", line)
                if line and len(line) > 8:
                    topics.append(line)
        if topics:
            break
    return topics[:5]


def extract_page_keyword_from_history(history: list[dict[str, Any]]) -> str | None:
    """Zoek het laatste user-bericht met een SEO-pagina-verzoek."""
    for entry in reversed(history):
        if entry.get("role") != "user":
            continue
        content = entry.get("content", "").strip()
        lower = content.lower()
        if not any(w in lower for w in ("seo pagina", "seo-pagina", "landingspagina", "haccp lijsten")):
            continue
        kw = extract_create_page_keyword(content)
        if kw:
            return kw
        if "haccp" in lower:
            return content[:220]
    for entry in reversed(history):
        if entry.get("role") != "assistant":
            continue
        content = entry.get("content", "")
        match = re.search(r"\*\*Titel:\*\*\s*(.+)", content, re.IGNORECASE)
        if match:
            return match.group(1).strip()
        match = re.search(r"^##\s+(.+)$", content, re.MULTILINE)
        if match and "haccp" in match.group(1).lower():
            return match.group(1).strip()
    return None
