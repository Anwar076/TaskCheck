"""Google Search Console API client."""

from __future__ import annotations

from datetime import date, timedelta
from typing import Any

from google.oauth2 import service_account
from googleapiclient.discovery import build

from app.utils.config import get_config
from app.utils.logger import setup_logger

logger = setup_logger(__name__)

SCOPES = ["https://www.googleapis.com/auth/webmasters.readonly"]


class GSCClient:
    def __init__(self) -> None:
        config = get_config()
        credentials = service_account.Credentials.from_service_account_file(
            str(config.gsc_credentials),
            scopes=SCOPES,
        )
        self.service = build("searchconsole", "v1", credentials=credentials)
        self.site_url = config.gsc_site_url

    def _date_range(self, days: int = 28) -> tuple[str, str]:
        end = date.today() - timedelta(days=3)
        start = end - timedelta(days=days)
        return start.isoformat(), end.isoformat()

    def query(
        self,
        dimensions: list[str],
        days: int = 28,
        row_limit: int = 250,
        start_date: str | None = None,
        end_date: str | None = None,
    ) -> list[dict[str, Any]]:
        if not start_date or not end_date:
            start_date, end_date = self._date_range(days)

        body = {
            "startDate": start_date,
            "endDate": end_date,
            "dimensions": dimensions,
            "rowLimit": row_limit,
        }

        response = self.service.searchanalytics().query(
            siteUrl=self.site_url,
            body=body,
        ).execute()

        rows = []
        for row in response.get("rows", []):
            entry: dict[str, Any] = {
                "clicks": row.get("clicks", 0),
                "impressions": row.get("impressions", 0),
                "ctr": round(row.get("ctr", 0) * 100, 2),
                "position": round(row.get("position", 0), 1),
            }
            keys = row.get("keys", [])
            if "query" in dimensions:
                entry["query"] = keys[dimensions.index("query")]
            if "page" in dimensions:
                entry["page"] = keys[dimensions.index("page")]
            rows.append(entry)
        return rows

    def get_query_data(self, days: int = 28) -> list[dict[str, Any]]:
        return self.query(["query"], days=days)

    def get_page_data(self, days: int = 28) -> list[dict[str, Any]]:
        return self.query(["page"], days=days)

    def get_query_page_data(self, days: int = 28) -> list[dict[str, Any]]:
        return self.query(["query", "page"], days=days, row_limit=500)

    def get_summary(self, days: int = 28) -> dict[str, Any]:
        start, end = self._date_range(days)
        prev_start = (date.fromisoformat(start) - timedelta(days=days)).isoformat()
        prev_end = (date.fromisoformat(start) - timedelta(days=1)).isoformat()

        current = self.query([], days=days, start_date=start, end_date=end)
        previous = self.query([], days=days, start_date=prev_start, end_date=prev_end)

        cur = current[0] if current else {"clicks": 0, "impressions": 0, "ctr": 0, "position": 0}
        prev = previous[0] if previous else {"clicks": 0, "impressions": 0, "ctr": 0, "position": 0}

        return {
            "period": {"start": start, "end": end},
            "current": cur,
            "previous": prev,
            "changes": {
                "clicks": cur["clicks"] - prev["clicks"],
                "impressions": cur["impressions"] - prev["impressions"],
                "ctr": round(cur["ctr"] - prev["ctr"], 2),
                "position": round(cur["position"] - prev["position"], 1),
            },
        }

    def compare_queries(self, days: int = 14) -> dict[str, list[dict[str, Any]]]:
        """Vergelijk recente periode met vorige periode per zoekwoord."""
        end = date.today() - timedelta(days=3)
        mid = end - timedelta(days=days)
        start = mid - timedelta(days=days)

        current = {
            r["query"]: r
            for r in self.query(["query"], start_date=mid.isoformat(), end_date=end.isoformat(), row_limit=500)
        }
        previous = {
            r["query"]: r
            for r in self.query(["query"], start_date=start.isoformat(), end_date=(mid - timedelta(days=1)).isoformat(), row_limit=500)
        }

        rising, falling, new_keywords = [], [], []

        for query, cur in current.items():
            prev = previous.get(query)
            if not prev:
                if cur["impressions"] >= 3:
                    new_keywords.append({**cur, "change_position": None})
                continue

            pos_change = prev["position"] - cur["position"]
            entry = {**cur, "change_position": round(pos_change, 1), "prev_position": prev["position"]}

            if pos_change >= 1 and cur["impressions"] >= 5:
                rising.append(entry)
            elif pos_change <= -1 and cur["impressions"] >= 5:
                falling.append(entry)

        rising.sort(key=lambda x: x["change_position"], reverse=True)
        falling.sort(key=lambda x: x["change_position"])
        new_keywords.sort(key=lambda x: x["impressions"], reverse=True)

        return {
            "rising": rising[:15],
            "falling": falling[:15],
            "new": new_keywords[:15],
        }
