"""Centrale configuratie voor de SEO agent."""

from __future__ import annotations

import os
from dataclasses import dataclass, field
from pathlib import Path

from dotenv import load_dotenv

load_dotenv()

PROJECT_ROOT = Path(__file__).resolve().parents[2]
_DEFAULT_LARAVEL_ROOT = PROJECT_ROOT.parent


def _laravel_root() -> Path:
    custom = os.getenv("GIT_REPO_ROOT", "").strip()
    if custom:
        return Path(custom).expanduser().resolve()
    return _DEFAULT_LARAVEL_ROOT.resolve()


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
    git_repo_root: str = field(default_factory=lambda: os.getenv("GIT_REPO_ROOT", "").strip())
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
    gsc_default_days: int = field(default_factory=lambda: int(os.getenv("GSC_DEFAULT_DAYS", "28")))
    gsc_trend_days: int = field(default_factory=lambda: int(os.getenv("GSC_TREND_DAYS", "14")))

    project_root: Path = field(default_factory=lambda: PROJECT_ROOT)
    data_dir: Path = field(default_factory=lambda: PROJECT_ROOT / "data")
    generated_dir: Path = field(default_factory=lambda: PROJECT_ROOT / "generated")
    pending_dir: Path = field(default_factory=lambda: PROJECT_ROOT / "pending")
    template_path: Path = field(default_factory=lambda: PROJECT_ROOT / "seo_template.blade.php")
    allowed_keywords_path: Path = field(
        default_factory=lambda: PROJECT_ROOT / "allowed_keywords.txt"
    )
    competitors_path: Path = field(
        default_factory=lambda: PROJECT_ROOT / "config" / "competitors.txt"
    )
    company_context_path: Path = field(
        default_factory=lambda: PROJECT_ROOT / "company_context.py"
    )
    site_domain: str = "taskcheck.nl"

    laravel_root: Path = field(init=False)
    seo_views_dir: Path = field(init=False)
    blog_views_dir: Path = field(init=False)
    web_routes_file: Path = field(init=False)
    sitemap_path: Path = field(init=False)
    llms_txt_path: Path = field(init=False)
    reference_page: Path = field(init=False)
    reference_blog: Path = field(init=False)

    def __post_init__(self) -> None:
        root = _laravel_root()
        self.laravel_root = root
        self.seo_views_dir = root / "resources" / "views" / "seo"
        self.blog_views_dir = root / "resources" / "views" / "blog"
        self.web_routes_file = root / "routes" / "web.php"
        self.sitemap_path = root / "public" / "sitemap.xml"
        self.llms_txt_path = root / "llms.txt"
        self.reference_page = self.seo_views_dir / "haccp-app.blade.php"
        self.reference_blog = self.blog_views_dir / "nvwa-spoedsluitingen-plaagdieren-2026.blade.php"


def get_config() -> Config:
    return Config()
