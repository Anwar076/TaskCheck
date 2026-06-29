"""Centrale configuratie voor de SEO agent."""

from __future__ import annotations

import os
from dataclasses import dataclass, field
from pathlib import Path

from dotenv import load_dotenv

load_dotenv()

PROJECT_ROOT = Path(__file__).resolve().parents[2]
LARAVEL_ROOT = PROJECT_ROOT.parent
SEO_VIEWS_DIR = LARAVEL_ROOT / "resources" / "views" / "seo"
BLOG_VIEWS_DIR = LARAVEL_ROOT / "resources" / "views" / "blog"
WEB_ROUTES_FILE = LARAVEL_ROOT / "routes" / "web.php"
SITEMAP_FILE = LARAVEL_ROOT / "public" / "sitemap.xml"
LLMS_TXT_FILE = LARAVEL_ROOT / "llms.txt"


@dataclass
class Config:
    openai_api_key: str = field(default_factory=lambda: os.getenv("OPENAI_API_KEY", ""))
    telegram_token: str = field(default_factory=lambda: os.getenv("TELEGRAM_TOKEN", ""))
    chat_id: str = field(default_factory=lambda: os.getenv("CHAT_ID", ""))
    owner_name: str = field(default_factory=lambda: os.getenv("OWNER_NAME", "Anwar"))

    gsc_site_url: str = field(default_factory=lambda: os.getenv("GSC_SITE_URL", "sc-domain:taskcheck.nl"))
    gsc_credentials: Path = field(
        default_factory=lambda: Path(os.getenv("GSC_CREDENTIALS", PROJECT_ROOT / "credentials.json"))
    )

    openai_model: str = field(default_factory=lambda: os.getenv("OPENAI_MODEL", "gpt-4.1"))
    daily_report_hour: int = field(default_factory=lambda: int(os.getenv("DAILY_REPORT_HOUR", "7")))
    daily_report_minute: int = field(default_factory=lambda: int(os.getenv("DAILY_REPORT_MINUTE", "0")))
    auto_publish: bool = field(default_factory=lambda: os.getenv("AUTO_PUBLISH", "false").lower() == "true")
    publish_mode: str = field(default_factory=lambda: os.getenv("PUBLISH_MODE", "git_only").strip().lower())
    git_base_branch: str = field(default_factory=lambda: os.getenv("GIT_BASE_BRANCH", "main"))
    git_remote: str = field(default_factory=lambda: os.getenv("GIT_REMOTE", "origin"))
    git_auto_commit: bool = field(default_factory=lambda: os.getenv("GIT_AUTO_COMMIT", "true").lower() == "true")
    proactive_alerts: bool = field(
        default_factory=lambda: os.getenv("PROACTIVE_ALERTS", "true").lower() == "true"
    )
    opportunity_alert_interval_hours: int = field(
        default_factory=lambda: int(os.getenv("OPPORTUNITY_ALERT_INTERVAL_HOURS", "6"))
    )
    opportunity_alert_cooldown_hours: int = field(
        default_factory=lambda: int(os.getenv("OPPORTUNITY_ALERT_COOLDOWN_HOURS", "24"))
    )
    daily_auto_action: bool = field(
        default_factory=lambda: os.getenv("DAILY_AUTO_ACTION", "true").lower() == "true"
    )
    sitemap_path: Path = field(default_factory=lambda: SITEMAP_FILE)
    llms_txt_path: Path = field(default_factory=lambda: LLMS_TXT_FILE)
    sitemap_public_url: str = field(
        default_factory=lambda: os.getenv("SITEMAP_PUBLIC_URL", "https://taskcheck.nl/sitemap.xml")
    )
    gsc_submit_sitemap: bool = field(
        default_factory=lambda: os.getenv("GSC_SUBMIT_SITEMAP", "true").lower() == "true"
    )
    gsc_inspect_urls: bool = field(
        default_factory=lambda: os.getenv("GSC_INSPECT_URLS", "true").lower() == "true"
    )
    auto_gsc_after_push: bool = field(
        default_factory=lambda: os.getenv("AUTO_GSC_AFTER_PUSH", "true").lower() == "true"
    )
    auto_gsc_on_direct_publish: bool = field(
        default_factory=lambda: os.getenv("AUTO_GSC_ON_DIRECT_PUBLISH", "true").lower() == "true"
    )

    project_root: Path = PROJECT_ROOT
    data_dir: Path = field(default_factory=lambda: PROJECT_ROOT / "data")
    generated_dir: Path = field(default_factory=lambda: PROJECT_ROOT / "generated")
    pending_dir: Path = field(default_factory=lambda: PROJECT_ROOT / "pending")
    template_path: Path = field(default_factory=lambda: PROJECT_ROOT / "seo_template.blade.php")
    reference_page: Path = field(
        default_factory=lambda: SEO_VIEWS_DIR / "haccp-app.blade.php"
    )
    reference_blog: Path = field(
        default_factory=lambda: BLOG_VIEWS_DIR / "nvwa-spoedsluitingen-plaagdieren-2026.blade.php"
    )
    allowed_keywords_path: Path = field(
        default_factory=lambda: PROJECT_ROOT / "allowed_keywords.txt"
    )
    competitors_path: Path = field(
        default_factory=lambda: PROJECT_ROOT / "config" / "competitors.txt"
    )
    company_context_path: Path = field(
        default_factory=lambda: PROJECT_ROOT / "company_context.py"
    )

    seo_views_dir: Path = SEO_VIEWS_DIR
    blog_views_dir: Path = BLOG_VIEWS_DIR
    web_routes_file: Path = WEB_ROUTES_FILE
    site_domain: str = "taskcheck.nl"


def get_config() -> Config:
    return Config()
