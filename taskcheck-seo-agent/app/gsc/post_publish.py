"""GSC-acties na deploy: sitemap indienen + URL-inspectie."""

from __future__ import annotations

from typing import Any

from app.gsc.client import GSCClient
from app.utils.config import get_config
from app.utils.logger import setup_logger

logger = setup_logger(__name__)


def run_post_deploy_gsc(urls: list[str] | None = None) -> dict[str, Any]:
    """Dien sitemap in bij GSC en inspecteer opgegeven URLs."""
    config = get_config()
    urls = urls or []
    result: dict[str, Any] = {"urls": urls, "sitemap_submitted": False, "inspections": []}

    if not config.gsc_credentials.exists():
        result["skipped"] = "GSC credentials ontbreken"
        return result

    client = GSCClient()

    if config.gsc_submit_sitemap:
        try:
            client.submit_sitemap(config.sitemap_public_url)
            result["sitemap_submitted"] = True
            logger.info("Sitemap ingediend bij GSC: %s", config.sitemap_public_url)
        except Exception as exc:
            logger.exception("Sitemap indienen mislukt")
            result["sitemap_error"] = str(exc)

    if config.gsc_inspect_urls:
        for url in urls:
            try:
                inspection = client.inspect_url(url)
                result["inspections"].append(inspection)
            except Exception as exc:
                logger.warning("URL-inspectie mislukt voor %s: %s", url, exc)
                result["inspections"].append({"url": url, "error": str(exc)})

    return result


def format_gsc_telegram(result: dict[str, Any]) -> str:
    """Maak een korte Telegram-samenvatting van GSC-resultaten."""
    if result.get("skipped"):
        return f"ℹ️ GSC overgeslagen: {result['skipped']}"

    lines: list[str] = []

    if result.get("sitemap_submitted"):
        lines.append("🗺️ Sitemap ingediend bij Google Search Console")
    elif result.get("sitemap_error"):
        lines.append(f"⚠️ Sitemap indienen mislukt: {result['sitemap_error']}")

    inspections = result.get("inspections", [])
    if inspections:
        lines.append("🔍 URL-inspectie:")
        for item in inspections[:5]:
            url = item.get("url", "—")
            if item.get("error"):
                lines.append(f"• {url}\n  ⚠️ {item['error']}")
                continue
            status = item.get("coverage_state") or item.get("verdict") or "Onbekend"
            lines.append(f"• {url}\n  {status}")
            if item.get("indexing_state"):
                lines.append(f"  Indexering: {item['indexing_state']}")
        if len(inspections) > 5:
            lines.append(f"… en {len(inspections) - 5} meer")

    if not lines:
        return "ℹ️ GSC: geen acties uitgevoerd."

    lines.append(
        "\nℹ️ Google indexeert op eigen tempo. "
        "Sitemap + inspectie versnellen ontdekking, maar garanderen geen directe indexering."
    )
    return "\n".join(lines)
