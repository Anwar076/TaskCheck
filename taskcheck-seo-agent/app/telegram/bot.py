"""Telegram bot — interactieve SEO assistent."""

from __future__ import annotations

from telegram.ext import (
    Application,
    CallbackQueryHandler,
    CommandHandler as TelegramCommandHandler,
    MessageHandler,
    filters,
)

from app.telegram.commands import CommandHandler as SEOCommandHandler
from app.telegram.notifications import Notifier
from app.utils.config import get_config
from app.utils.logger import setup_logger

logger = setup_logger(__name__)


class SEOBot:
    def __init__(self) -> None:
        config = get_config()
        self.token = config.telegram_token
        self.notifier = Notifier()
        self.commands = SEOCommandHandler(self)
        self.app: Application | None = None

    def build_app(self) -> Application:
        if not self.token:
            raise ValueError("TELEGRAM_TOKEN niet geconfigureerd")

        self.app = Application.builder().token(self.token).build()
        handlers = SEOCommandHandler(self)

        self.app.add_handler(TelegramCommandHandler("start", handlers.cmd_start))
        self.app.add_handler(TelegramCommandHandler("help", handlers.cmd_help))
        self.app.add_handler(TelegramCommandHandler("status", handlers.cmd_status))
        self.app.add_handler(TelegramCommandHandler("report", handlers.cmd_report))
        self.app.add_handler(TelegramCommandHandler("kansen", handlers.cmd_kansen))
        self.app.add_handler(TelegramCommandHandler("stijgers", handlers.cmd_stijgers))
        self.app.add_handler(TelegramCommandHandler("dalers", handlers.cmd_dalers))
        self.app.add_handler(TelegramCommandHandler("nieuw", handlers.cmd_nieuw))
        self.app.add_handler(TelegramCommandHandler("verbeter", handlers.cmd_verbeter))
        self.app.add_handler(TelegramCommandHandler("blog", handlers.cmd_blog))
        self.app.add_handler(TelegramCommandHandler("volgende", handlers.cmd_volgende))
        self.app.add_handler(TelegramCommandHandler("approve", handlers.cmd_approve))
        self.app.add_handler(TelegramCommandHandler("cancel", handlers.cmd_cancel))
        self.app.add_handler(TelegramCommandHandler("hold", handlers.cmd_hold))
        self.app.add_handler(TelegramCommandHandler("pending", handlers.cmd_pending))
        self.app.add_handler(TelegramCommandHandler("push", handlers.cmd_push))
        self.app.add_handler(CallbackQueryHandler(handlers.cmd_callback))

        self.app.add_handler(
            MessageHandler(filters.TEXT & ~filters.COMMAND, handlers.handle_message)
        )

        return self.app

    def run_polling(self) -> None:
        logger.info("Telegram bot starten (polling)...")
        app = self.build_app()
        app.run_polling(drop_pending_updates=True)

    async def send_message(self, text: str) -> None:
        self.notifier.send(text)
