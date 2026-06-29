"""GSC-periodes: dag, week, maand, kwartaal, etc."""

from __future__ import annotations

from dataclasses import dataclass

from app.utils.config import get_config


@dataclass(frozen=True)
class GSCPeriod:
    days: int
    trend_days: int
    label: str

    def row_limit(self) -> int:
        """Meer dagen → iets meer zoekwoorden ophalen (GSC max 25000)."""
        return min(2500, max(250, self.days * 12))


PRESETS: dict[str, GSCPeriod] = {
    "dag": GSCPeriod(days=1, trend_days=1, label="afgelopen dag"),
    "day": GSCPeriod(days=1, trend_days=1, label="afgelopen dag"),
    "1d": GSCPeriod(days=1, trend_days=1, label="afgelopen dag"),
    "gisteren": GSCPeriod(days=1, trend_days=1, label="gisteren (beschikbare GSC-data)"),
    "week": GSCPeriod(days=7, trend_days=7, label="afgelopen week"),
    "7d": GSCPeriod(days=7, trend_days=7, label="afgelopen 7 dagen"),
    "2w": GSCPeriod(days=14, trend_days=14, label="afgelopen 2 weken"),
    "14d": GSCPeriod(days=14, trend_days=14, label="afgelopen 14 dagen"),
    "maand": GSCPeriod(days=28, trend_days=14, label="afgelopen 28 dagen"),
    "month": GSCPeriod(days=28, trend_days=14, label="afgelopen 28 dagen"),
    "28d": GSCPeriod(days=28, trend_days=14, label="afgelopen 28 dagen"),
    "30d": GSCPeriod(days=30, trend_days=15, label="afgelopen 30 dagen"),
    "kwartaal": GSCPeriod(days=90, trend_days=30, label="afgelopen 3 maanden"),
    "3m": GSCPeriod(days=90, trend_days=30, label="afgelopen 3 maanden"),
    "90d": GSCPeriod(days=90, trend_days=30, label="afgelopen 90 dagen"),
    "halfjaar": GSCPeriod(days=180, trend_days=45, label="afgelopen 6 maanden"),
    "6m": GSCPeriod(days=180, trend_days=45, label="afgelopen 6 maanden"),
    "jaar": GSCPeriod(days=365, trend_days=90, label="afgelopen 12 maanden"),
    "12m": GSCPeriod(days=365, trend_days=90, label="afgelopen 12 maanden"),
}


def resolve_gsc_period(text: str | None = None) -> GSCPeriod:
    """Parse periode uit tekst, env-default of 'maand'."""
    config = get_config()
    default = GSCPeriod(
        days=config.gsc_default_days,
        trend_days=config.gsc_trend_days,
        label=f"afgelopen {config.gsc_default_days} dagen",
    )

    if not text or not str(text).strip():
        return default

    raw = str(text).strip().lower()
    # normaliseer Nederlandse zinnen
    for phrase, key in (
        ("afgelopen dag", "dag"),
        ("afgelopen week", "week"),
        ("afgelopen maand", "maand"),
        ("afgelopen 3 maanden", "3m"),
        ("afgelopen drie maanden", "3m"),
        ("laatste week", "week"),
        ("laatste maand", "maand"),
    ):
        if phrase in raw:
            return PRESETS[key]

    token = raw.split()[0].replace(",", "")
    if token in PRESETS:
        return PRESETS[token]

    if token.isdigit():
        days = max(1, min(int(token), 480))
        trend = max(1, min(days // 2, 90))
        return GSCPeriod(days=days, trend_days=trend, label=f"afgelopen {days} dagen")

    return default
