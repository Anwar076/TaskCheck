"""Dagelijkse scheduler + 24/7 opportunity alerts + Telegram bot."""

from __future__ import annotations

import asyncio
import sys
from datetime import date, datetime, time, timedelta
from pathlib import Path

# Zorg dat project root in sys.path staat
PROJECT_ROOT = Path(__file__).resolve().parents[2]
if str(PROJECT_ROOT) not in sys.path:
    sys.path.insert(0, str(PROJECT_ROOT))

from app.agent import SEOAgent
from app.memory.store import MemoryStore
from app.scheduler.opportunity_alerts import OpportunityAlerter
from app.telegram.bot import SEOBot
from app.utils.config import get_config
from app.utils.lock import acquire_single_instance_lock
from app.utils.logger import setup_logger

logger = setup_logger(__name__)

ALREADY_RUNNING_EXIT_CODE = 3


def run_daily_job() -> None:
    """Voer de dagelijkse SEO run uit."""
    agent = SEOAgent()
    agent.run_daily()


def _last_daily_run_date() -> date | None:
    """Laatste dagelijkse run uit het geheugen, zodat een herstart niet opnieuw
    een volledige run (met rapport en AI-actie) afvuurt."""
    raw = MemoryStore().load().get("last_daily_run")
    if not raw:
        return None
    try:
        parsed = datetime.fromisoformat(raw)
    except (TypeError, ValueError):
        logger.warning("Onleesbare last_daily_run in geheugen: %r", raw)
        return None
    if parsed.tzinfo is not None:
        parsed = parsed.astimezone()
    return parsed.date()


def run_opportunity_alert() -> None:
    """Stuur proactieve kans-melding (zonder automatisch concept te maken)."""
    alerter = OpportunityAlerter()
    alerter.run()


async def scheduler_loop() -> None:
    """Dagelijks rapport + periodieke kans-alerts."""
    config = get_config()
    target_time = time(config.daily_report_hour, config.daily_report_minute)
    last_run_date = _last_daily_run_date()
    last_alert_at: datetime | None = None
    alert_interval = timedelta(hours=max(1, config.opportunity_alert_interval_hours))

    logger.info(
        "Scheduler actief — rapport om %02d:%02d, kans-alerts elke %sh",
        config.daily_report_hour,
        config.daily_report_minute,
        config.opportunity_alert_interval_hours,
    )
    if last_run_date:
        logger.info("Laatste dagelijkse run: %s", last_run_date)

    # Eerste kans-scan kort na start (zodat je direct feedback krijgt)
    if config.proactive_alerts:
        await asyncio.sleep(30)
        try:
            run_opportunity_alert()
            last_alert_at = datetime.now()
        except Exception:
            logger.exception("Eerste kans-alert mislukt")

    while True:
        now = datetime.now()

        if now.time() >= target_time and now.date() != last_run_date:
            logger.info("Dagelijkse run starten...")
            try:
                run_daily_job()
                last_run_date = now.date()
            except Exception:
                logger.exception("Dagelijkse run mislukt")

        if config.proactive_alerts:
            if last_alert_at is None or (now - last_alert_at) >= alert_interval:
                logger.info("Kans-alert scan starten...")
                try:
                    run_opportunity_alert()
                    last_alert_at = now
                except Exception:
                    logger.exception("Kans-alert mislukt")

        await asyncio.sleep(60)


def start_background_scheduler() -> None:
    """Start rapport + kans-alerts op achtergrondthread."""
    import threading

    thread = threading.Thread(
        target=lambda: asyncio.run(scheduler_loop()),
        daemon=True,
        name="seo-scheduler",
    )
    thread.start()
    logger.info("Achtergrond-scheduler gestart (rapport + kans-alerts)")


def run_bot(with_scheduler: bool = True) -> bool:
    """Start de Telegram bot. Standaard ook scheduler voor proactieve alerts.

    Retourneert False als er al een instantie draait.
    """
    lock = acquire_single_instance_lock()
    if lock is None:
        logger.error(
            "Er draait al een SEO Agent op deze machine. Stop die eerst — "
            "Telegram staat maar één polling-sessie per bot toe."
        )
        return False

    try:
        if with_scheduler:
            start_background_scheduler()
        logger.info("SEO Agent volledig gestart (bot + scheduler + kans-alerts)")
        bot = SEOBot()
        bot.run_polling()
    finally:
        lock.close()
    return True


def main() -> int:
    """Start scheduler + Telegram bot tegelijk (24/7 modus)."""
    from app.utils.git_health import check_git_health

    health = check_git_health()
    logger.info("SEO Agent starten\n%s", health.summary())
    if not health.ok and get_config().publish_mode == "git_only":
        logger.warning("Git niet volledig geconfigureerd — goedkeuren kan falen")

    if not run_bot(with_scheduler=True):
        return ALREADY_RUNNING_EXIT_CODE
    return 0


if __name__ == "__main__":
    sys.exit(main())
