"""Logging voor de SEO agent."""

from __future__ import annotations

import logging
import sys
from pathlib import Path

from app.utils.config import get_config


def _console_stream():
    """Windows-consoles gebruiken standaard cp1252; emoji in logregels laten
    logging dan crashen met een UnicodeEncodeError."""
    stream = sys.stdout
    reconfigure = getattr(stream, "reconfigure", None)
    if reconfigure is not None:
        try:
            reconfigure(encoding="utf-8", errors="replace")
        except (ValueError, OSError):
            pass
    return stream


def setup_logger(name: str = "seo_agent") -> logging.Logger:
    config = get_config()
    config.data_dir.mkdir(parents=True, exist_ok=True)

    logger = logging.getLogger(name)
    if logger.handlers:
        return logger

    logger.setLevel(logging.INFO)
    formatter = logging.Formatter(
        "%(asctime)s [%(levelname)s] %(name)s: %(message)s",
        datefmt="%Y-%m-%d %H:%M:%S",
    )

    console = logging.StreamHandler(_console_stream())
    console.setFormatter(formatter)
    logger.addHandler(console)

    log_file = config.data_dir / "agent.log"
    file_handler = logging.FileHandler(log_file, encoding="utf-8")
    file_handler.setFormatter(formatter)
    logger.addHandler(file_handler)

    return logger
