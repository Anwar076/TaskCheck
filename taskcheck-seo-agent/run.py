#!/usr/bin/env python3
"""TaskCheck SEO Agent — hoofdingang."""

from __future__ import annotations

import argparse
import sys
from pathlib import Path

PROJECT_ROOT = Path(__file__).resolve().parent
if str(PROJECT_ROOT) not in sys.path:
    sys.path.insert(0, str(PROJECT_ROOT))

from app.agent import SEOAgent
from app.scheduler.daily import main as run_daemon, run_bot, run_daily_job
from app.utils.logger import setup_logger

logger = setup_logger("main")


def main() -> None:
    parser = argparse.ArgumentParser(description="TaskCheck SEO Agent")
    parser.add_argument(
        "command",
        nargs="?",
        default="daemon",
        choices=["daemon", "daily", "bot", "analyze", "pipeline"],
        help="daemon=24/7 bot+alerts+scheduler, daily=eenmalige run, bot=alleen telegram, analyze=pipeline",
    )
    args = parser.parse_args()

    if args.command == "daemon":
        run_daemon()
    elif args.command == "daily":
        run_daily_job()
    elif args.command == "bot":
        run_bot()
    elif args.command == "analyze":
        agent = SEOAgent()
        agent.run_analysis_only()
    elif args.command == "pipeline":
        agent = SEOAgent()
        agent.run_full_pipeline()


if __name__ == "__main__":
    main()
