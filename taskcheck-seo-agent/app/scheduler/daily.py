"""Dagelijkse scheduler + 24/7 opportunity alerts + Telegram bot."""

from __future__ import annotations

import asyncio
import sys
from datetime import datetime, time, timedelta
from pathlib import Path

# Zorg dat project root in sys.path staat
PROJECT_ROOT = Path(__file__).resolve().parents[2]
if str(PROJECT_ROOT) not in sys.path:
    sys.path.insert(0, str(PROJECT_ROOT))

from app.agent import SEOAgent
from app.scheduler.opportunity_alerts import OpportunityAlerter
from app.telegram.bot import SEOBot
from app.utils.config import get_config
from app.utils.logger import setup_logger

logger = setup_logger(__name__)


def run_daily_job() -> None:
    """Voer de dagelijkse SEO run uit."""
    agent = SEOAgent()
    agent.run_daily()


def run_opportunity_alert() -> None:
    """Stuur proactieve kans-melding (zonder automatisch concept te maken)."""
    alerter = OpportunityAlerter()
    alerter.run()


async def scheduler_loop() -> None:
    """Dagelijks rapport + periodieke kans-alerts."""
    config = get_config()
    target_time = time(config.daily_report_hour, config.daily_report_minute)
    last_run_date = None
    last_alert_at: datetime | None = None
    alert_interval = timedelta(hours=max(1, config.opportunity_alert_interval_hours))

    logger.info(
        "Scheduler actief — rapport om %02d:%02d, kans-alerts elke %sh",
        config.daily_report_hour,
        config.daily_report_minute,
        config.opportunity_alert_interval_hours,
    )

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


def run_bot(with_scheduler: bool = True) -> None:
    """Start de Telegram bot. Standaard ook scheduler voor proactieve alerts."""
    if with_scheduler:
        start_background_scheduler()
    bot = SEOBot()
    bot.run_polling()


def main() -> None:
    """Start scheduler + Telegram bot tegelijk (24/7 modus)."""
    from app.utils.git_health import check_git_health

    health = check_git_health()
    logger.info("SEO Agent starten\n%s", health.summary())
    if not health.ok and get_config().publish_mode == "git_only":
        logger.warning("Git niet volledig geconfigureerd — goedkeuren kan falen")

    logger.info("SEO Agent volledig gestart (bot + scheduler + kans-alerts)")
    run_bot(with_scheduler=True)


if __name__ == "__main__":
    main()
