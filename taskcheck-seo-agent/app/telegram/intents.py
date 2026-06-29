"""Herken intenties in vrije tekst (Nederlands)."""

from __future__ import annotations

import re


def detect_intent(message: str) -> str | None:
    """Return approve, cancel, hold, pending, status, report or None."""
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

    status_patterns = [
        r"\b(hoe gaat (de )?seo|seo status|status vandaag)\b",
        r"\b(welke pagina.s stijgen|pagina.s stijgen)\b",
    ]

    report_patterns = [
        r"\b(rapport|report|dagelijks rapport)\b",
    ]

    push_patterns = [
        r"\b(push)\b.*\b(main|master)\b",
        r"\b(naar main|naar master)\b",
        r"\b(merge naar main|merge naar master)\b",
    ]

    next_patterns = [
        r"\b(volgende|next)\b",
        r"\b(nieuwe kans|andere kans|ander zoekwoord)\b",
        r"\b(wat nu|wat raad je)\b",
    ]

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

    for pattern in next_patterns:
        if re.search(pattern, text):
            return "next"

    return None
