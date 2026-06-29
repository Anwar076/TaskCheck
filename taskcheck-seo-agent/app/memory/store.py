"""Persistente geheugenopslag voor de SEO agent."""

from __future__ import annotations

from datetime import datetime, timezone
from typing import Any

from app.utils.config import get_config
from app.utils.files import read_json, write_json


class MemoryStore:
    def __init__(self) -> None:
        config = get_config()
        config.data_dir.mkdir(parents=True, exist_ok=True)
        self.path = config.data_dir / "memory.json"
        self.pending_path = config.data_dir / "pending_actions.json"
        self.report_path = config.data_dir / "last_report.json"
        self.history_path = config.data_dir / "action_history.json"

    def _now(self) -> str:
        return datetime.now(timezone.utc).isoformat()

    def load(self) -> dict[str, Any]:
        return read_json(self.path, {
            "last_daily_run": None,
            "last_gsc_snapshot": None,
            "processed_keywords": [],
            "published_pages": [],
            "optimized_pages": [],
            "notified_opportunities": {},
        })

    def save(self, data: dict[str, Any]) -> None:
        write_json(self.path, data)

    def update(self, **kwargs: Any) -> dict[str, Any]:
        data = self.load()
        data.update(kwargs)
        self.save(data)
        return data

    def add_processed_keyword(self, keyword: str) -> None:
        data = self.load()
        keywords = data.setdefault("processed_keywords", [])
        if keyword.lower() not in [k.lower() for k in keywords]:
            keywords.append(keyword)
        self.save(data)

    def add_published_page(self, slug: str, keyword: str) -> None:
        data = self.load()
        pages = data.setdefault("published_pages", [])
        pages.append({"slug": slug, "keyword": keyword, "at": self._now()})
        self.save(data)

    def add_optimized_page(self, slug: str, improvements: list[str]) -> None:
        data = self.load()
        pages = data.setdefault("optimized_pages", [])
        pages.append({"slug": slug, "improvements": improvements, "at": self._now()})
        self.save(data)

    def get_pending_actions(self) -> list[dict[str, Any]]:
        return read_json(self.pending_path, [])

    def save_pending_actions(self, actions: list[dict[str, Any]]) -> None:
        write_json(self.pending_path, actions)

    def get_completed_slugs(self) -> set[str]:
        slugs: set[str] = set()
        for page in self.load().get("published_pages", []):
            if page.get("slug"):
                slugs.add(page["slug"])
        for page in self.load().get("optimized_pages", []):
            if page.get("slug"):
                slugs.add(page["slug"])
        for action in self.get_pending_actions():
            if action.get("status") in ("pending", "on_hold") and action.get("slug"):
                slugs.add(action["slug"])
        return slugs

    def get_completed_keywords(self) -> set[str]:
        keywords: set[str] = set()
        for page in self.load().get("published_pages", []):
            if page.get("keyword"):
                keywords.add(page["keyword"].lower())
        for action in self.get_pending_actions():
            if action.get("status") in ("pending", "on_hold") and action.get("keyword"):
                keywords.add(action["keyword"].lower())
        return keywords

    def has_pending_for_slug(self, slug: str) -> bool:
        for action in self.get_pending_actions():
            if action.get("slug") == slug and action.get("status") in ("pending", "on_hold"):
                return True
        return False

    def add_pending_action(self, action: dict[str, Any]) -> str:
        slug = action.get("slug")
        if slug:
            for existing in self.get_pending_actions():
                if (
                    existing.get("slug") == slug
                    and existing.get("type") == action.get("type")
                    and existing.get("status") in ("pending", "on_hold")
                ):
                    return existing["id"]

        actions = self.get_pending_actions()
        action_id = f"action_{len(actions) + 1}_{int(datetime.now().timestamp())}"
        action["id"] = action_id
        action["status"] = "pending"
        action["created_at"] = self._now()
        actions.append(action)
        self.save_pending_actions(actions)
        return action_id

    def update_pending_action(self, action_id: str, status: str) -> dict[str, Any] | None:
        actions = self.get_pending_actions()
        for action in actions:
            if action.get("id") == action_id:
                action["status"] = status
                action["updated_at"] = self._now()
                self.save_pending_actions(actions)
                return action
        return None

    def get_pending_by_keyword(self, keyword: str) -> dict[str, Any] | None:
        for action in self.get_pending_actions():
            if action.get("keyword", "").lower() == keyword.lower() and action.get("status") == "pending":
                return action
        return None

    def get_published_slugs(self) -> set[str]:
        return {
            page["slug"]
            for page in self.load().get("published_pages", [])
            if page.get("slug")
        }

    def close_stale_pending(self, live_slugs: set[str] | None = None) -> list[str]:
        """Sluit pending acties af die al live staan."""
        live_slugs = live_slugs or self.get_published_slugs()
        closed: list[str] = []
        actions = self.get_pending_actions()
        changed = False
        for action in actions:
            if action.get("status") not in ("pending", "on_hold"):
                continue
            slug = action.get("slug")
            if not slug:
                continue
            if slug in live_slugs or slug in self.get_published_slugs():
                action["status"] = "superseded"
                action["updated_at"] = self._now()
                action["superseded_reason"] = "Pagina staat al live op TaskCheck"
                closed.append(slug)
                changed = True
        if changed:
            self.save_pending_actions(actions)
        return closed

    def get_latest_pending(self, live_slugs: set[str] | None = None) -> dict[str, Any] | None:
        """Haal openstaande actie op. Live slugs blokkeren alleen nieuwe pagina's."""
        live_slugs = live_slugs or set()
        pending = [a for a in self.get_pending_actions() if a.get("status") in ("pending", "on_hold")]
        actionable = [a for a in pending if self._is_actionable(a, live_slugs)]
        return actionable[-1] if actionable else None

    def get_all_open_pending(self, live_slugs: set[str] | None = None) -> list[dict[str, Any]]:
        live_slugs = live_slugs or set()
        pending = [a for a in self.get_pending_actions() if a.get("status") in ("pending", "on_hold")]
        return [a for a in pending if self._is_actionable(a, live_slugs)]

    def _is_actionable(self, action: dict[str, Any], live_slugs: set[str]) -> bool:
        slug = action.get("slug", "")
        if action.get("type") == "optimize_page":
            return bool(slug)
        if action.get("type") in ("create_page", "create_blog"):
            return slug not in live_slugs
        return True

    def save_report(self, report: dict[str, Any]) -> None:
        write_json(self.report_path, report)

    def get_last_report(self) -> dict[str, Any]:
        return read_json(self.report_path, {})

    def log_action(self, action_type: str, details: dict[str, Any]) -> None:
        history = read_json(self.history_path, [])
        history.append({
            "type": action_type,
            "details": details,
            "at": self._now(),
        })
        if len(history) > 500:
            history = history[-500:]
        write_json(self.history_path, history)

    def should_notify_opportunity(self, key: str, cooldown_hours: int = 24) -> bool:
        if not key:
            return False
        data = self.load()
        notified = data.setdefault("notified_opportunities", {})
        last = notified.get(key)
        if not last:
            return True
        try:
            last_dt = datetime.fromisoformat(last.replace("Z", "+00:00"))
            if last_dt.tzinfo is None:
                last_dt = last_dt.replace(tzinfo=timezone.utc)
            elapsed = datetime.now(timezone.utc) - last_dt
            return elapsed.total_seconds() >= cooldown_hours * 3600
        except ValueError:
            return True

    def mark_notified_opportunity(self, key: str) -> None:
        if not key:
            return
        data = self.load()
        notified = data.setdefault("notified_opportunities", {})
        notified[key] = self._now()
        self.save(data)
