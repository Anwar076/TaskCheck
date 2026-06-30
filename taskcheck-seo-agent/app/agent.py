"""Hoofd-orchestrator — autonome SEO agent workflow."""

from __future__ import annotations

from datetime import datetime, timezone
from typing import Any

from app.ai.brain import AIBrain
from app.ai.page_writer import PageWriter
from app.blog.writer import BlogWriter
from app.competitor.analyzer import CompetitorAnalyzer
from app.laravel.publisher import LaravelPublisher
from app.memory.store import MemoryStore
from app.reporting.daily_report import DailyReporter
from app.seo.analyzer import SEOAnalyzer
from app.seo.optimizer import PageOptimizer
from app.seo.page_registry import get_page_registry
from app.telegram.notifications import Notifier
from app.utils.config import get_config
from app.utils.files import slugify
from app.utils.logger import setup_logger

logger = setup_logger(__name__)


class SEOAgent:
    """Autonome SEO-assistent die dagelijks werkt en acties voorstelt."""

    def __init__(self) -> None:
        self.config = get_config()
        self.analyzer = SEOAnalyzer()
        self.brain = AIBrain()
        self.writer = PageWriter()
        self.blog_writer = BlogWriter()
        self.optimizer = PageOptimizer()
        self.publisher = LaravelPublisher()
        self.competitor = CompetitorAnalyzer()
        self.reporter = DailyReporter()
        self.memory = MemoryStore()
        self.notifier = Notifier()
        self.registry = get_page_registry()

    def run_daily(self) -> dict[str, Any]:
        """Dagelijkse autonome run: rapport + beste actie uitvoeren."""
        logger.info("Dagelijkse SEO run gestart")
        results: dict[str, Any] = {"started_at": datetime.now(timezone.utc).isoformat()}

        try:
            report_text = self.reporter.generate()
            self.notifier.notify_daily_report(report_text)
            results["report_sent"] = True
        except Exception as exc:
            logger.exception("Rapport mislukt")
            self.notifier.notify_error(f"Rapport mislukt: {exc}")
            results["report_sent"] = False
            results["report_error"] = str(exc)

        try:
            if self.config.daily_auto_action:
                action_result = self.execute_best_action()
                results["action"] = action_result
            else:
                results["action"] = {"action": "skipped", "reason": "DAILY_AUTO_ACTION=false"}
        except Exception as exc:
            logger.exception("Actie mislukt")
            self.notifier.notify_error(f"Actie mislukt: {exc}")
            results["action_error"] = str(exc)

        self.memory.update(last_daily_run=datetime.now(timezone.utc).isoformat())
        logger.info("Dagelijkse SEO run afgerond")
        return results

    def execute_best_action(self) -> dict[str, Any]:
        """Analyseer en voer de beste SEO-actie uit (met goedkeuring)."""
        open_pending = self.memory.get_active_pending()
        if open_pending:
            latest = open_pending[-1]
            label = latest.get("keyword") or latest.get("slug") or latest.get("type", "")
            return {
                "action": "skipped",
                "reason": (
                    f"Er wacht al een concept voor '{label}'. "
                    "Stuur 'ja toepassen', /cancel of /hold voordat ik iets nieuws maak."
                ),
            }

        handled = list(self.memory.get_completed_keywords()) + list(self.memory.get_completed_slugs())
        analysis = self.analyzer.find_opportunities(exclude_handled=True)
        decision = self.brain.analyze_opportunities(analysis, handled=handled)
        self.memory.update(last_gsc_snapshot=analysis.get("summary"))

        action_type = decision.get("action", "monitor")
        if action_type == "monitor":
            logger.info("Geen actie nodig vandaag")
            return {"action": "monitor", "decision": decision}

        if action_type == "create_page":
            return self._create_page_action(decision, analysis)

        if action_type in ("improve_page", "improve_ctr"):
            return self._improve_page_action(decision, analysis)

        if action_type == "create_blog":
            return self._create_blog_action(decision)

        return {"action": action_type, "decision": decision}

    def _create_page_action(self, decision: dict, analysis: dict) -> dict[str, Any]:
        keyword = decision.get("keyword", "")
        slug = decision.get("slug") or slugify(keyword)

        if not keyword:
            return {"action": "skipped", "reason": "Geen keyword"}

        existing = self.registry.find_match(keyword, slug)
        if existing:
            logger.info(
                "SEO-pagina bestaat al voor '%s' → %s (%s)",
                keyword,
                existing.slug,
                existing.reason,
            )
            return self._improve_existing_instead(keyword, existing, decision)

        if self.registry.exists_exact(slug):
            logger.info("Pagina bestaat al: %s", slug)
            return {"action": "skipped", "reason": f"Pagina {slug} bestaat al op TaskCheck"}

        if self.memory.has_pending_for_slug(slug):
            return {"action": "skipped", "reason": f"Er wacht al een actie voor {slug}. Gebruik /approve of /cancel."}

        logger.info("Nieuwe pagina maken: %s", keyword)
        result = self.writer.create_page(keyword, slug)

        action_id = self.memory.add_pending_action({
            "type": "create_page",
            "keyword": keyword,
            "slug": slug,
            "path": result["path"],
            "reason": decision.get("reason", ""),
            "expected_impact": decision.get("expected_impact", ""),
        })

        self.notifier.notify_new_page({
            "keyword": keyword,
            "reason": decision.get("reason", ""),
            "expected_impact": decision.get("expected_impact", ""),
            "path": result["path"],
            "action_id": action_id,
        })

        if self.config.auto_publish:
            self.publisher.publish_page(slug)
            self.memory.update_pending_action(action_id, "approved")
            self.memory.add_published_page(slug, keyword)
            self.memory.add_processed_keyword(keyword)

        self.memory.log_action("create_page", {"keyword": keyword, "slug": slug})
        return {"action": "create_page", "keyword": keyword, "slug": slug, "path": result["path"]}

    def _improve_page_action(self, decision: dict, analysis: dict) -> dict[str, Any]:
        target = decision.get("target_page", "") or decision.get("keyword", "")
        slug = slugify(target.split("/")[-1]) if "/" in target else slugify(target)

        if not slug or not (self.config.seo_views_dir / f"{slug}.blade.php").exists():
            opps = analysis.get("improve_opportunities", [])
            found_slug = ""
            for opp in opps:
                page_url = opp.get("page", "")
                candidate = opp.get("slug") or slugify(page_url.split("/")[-1])
                if candidate and (self.config.seo_views_dir / f"{candidate}.blade.php").exists():
                    found_slug = candidate
                    break
            if found_slug:
                slug = found_slug
            else:
                return {"action": "skipped", "reason": "Geen pagina gevonden om te verbeteren"}

        if self.memory.has_pending_for_slug(slug):
            return {
                "action": "skipped",
                "reason": (
                    f"Er wacht al een optimalisatie voor {slug}. "
                    "Gebruik 'ja toepassen' of /cancel."
                ),
            }

        logger.info("Pagina optimaliseren: %s", slug)
        gsc_data = next(
            (
                o
                for o in analysis.get("improve_opportunities", [])
                if o.get("slug") == slug or slug in o.get("page", "")
            ),
            {},
        )
        result = self.optimizer.optimize_page(slug, gsc_data)

        action_id = self.memory.add_pending_action({
            "type": "optimize_page",
            "slug": slug,
            "keyword": decision.get("keyword", ""),
            "path": result["pending_path"],
            "reason": decision.get("reason", ""),
        })

        self.notifier.notify_page_optimized(result)

        if self.config.auto_publish:
            self.publisher.apply_optimization(slug)
            self.memory.update_pending_action(action_id, "approved")

        self.memory.log_action("optimize_page", {"slug": slug})
        return {"action": "optimize_page", "slug": slug, "path": result["pending_path"]}

    def _create_blog_action(self, decision: dict) -> dict[str, Any]:
        topic = decision.get("topic") or decision.get("keyword", "")
        if not topic:
            return {"action": "skipped", "reason": "Geen onderwerp voor blog"}
        slug = decision.get("slug") or slugify(topic)

        if self.memory.has_pending_for_slug(slug):
            return {"action": "skipped", "reason": f"Er wacht al een blog-actie voor {slug}. Gebruik /approve of /cancel."}

        result = self.blog_writer.create_blog(topic, slug=slug, source=decision.get("source_hint", ""))
        action_id = self.memory.add_pending_action({
            "type": "create_blog",
            "keyword": topic,
            "slug": slug,
            "path": result["pending_path"],
            "reason": decision.get("reason", ""),
            "expected_impact": decision.get("expected_impact", "Meer topical authority via blog content"),
        })

        self.notifier.notify_new_blog({
            "topic": topic,
            "reason": decision.get("reason", ""),
            "path": result["pending_path"],
            "action_id": action_id,
        })
        self.memory.log_action("create_blog", {"topic": topic, "slug": slug})
        return {"action": "create_blog", "topic": topic, "slug": slug, "path": result["pending_path"]}

    def _improve_existing_instead(
        self,
        keyword: str,
        existing: Any,
        decision: dict,
    ) -> dict[str, Any]:
        """Als pagina al bestaat, stuur verbeter-actie i.p.v. duplicaat."""
        slug = existing.slug
        page_info = self.registry.get_page(slug)

        if page_info and page_info.get("source") in ("pending", "generated"):
            return {
                "action": "skipped",
                "reason": (
                    f"Concept bestaat al als '{slug}' ({existing.reason}). "
                    "Gebruik /approve om te publiceren."
                ),
                "existing_slug": slug,
            }

        live_path = self.config.seo_views_dir / f"{slug}.blade.php"
        if not live_path.exists():
            return {
                "action": "skipped",
                "reason": (
                    f"Concept bestaat al als '{slug}' ({existing.reason}). "
                    "Gebruik /approve om te publiceren."
                ),
                "existing_slug": slug,
            }

        if self.memory.has_pending_for_slug(slug):
            return {
                "action": "skipped",
                "reason": (
                    f"Er wacht al een optimalisatie voor {existing.url}. "
                    "Stuur 'ja toepassen' of /cancel — geen duplicaat concept."
                ),
                "existing_slug": slug,
            }

        logger.info("Bestaande pagina gevonden, optimaliseren: %s", slug)
        self.notifier.send(
            f"ℹ️ Pagina bestaat al op TaskCheck\n\n"
            f"Zoekwoord: {keyword}\n"
            f"Bestaande pagina: {existing.url}\n"
            f"Match: {existing.reason}\n\n"
            f"Ik ga de bestaande pagina verbeteren i.p.v. een duplicaat maken."
        )

        improve_decision = {**decision, "target_page": slug, "keyword": keyword}
        return self._improve_page_action(improve_decision, {"improve_opportunities": []})

    def run_analysis_only(self) -> dict[str, Any]:
        """Alleen analyse uitvoeren (legacy compatibiliteit)."""
        handled = list(self.memory.get_completed_keywords()) + list(self.memory.get_completed_slugs())
        analysis = self.analyzer.find_opportunities(exclude_handled=True)
        decision = self.brain.analyze_opportunities(analysis, handled=handled)
        self.notifier.send(
            f"🤖 TaskCheck SEO Analyse\n\n"
            f"Actie: {decision.get('action')}\n"
            f"Keyword: {decision.get('keyword', '—')}\n"
            f"Reden: {decision.get('reason')}\n"
            f"Impact: {decision.get('expected_impact')}"
        )
        return {"analysis": analysis, "decision": decision}

    def run_full_pipeline(self) -> dict[str, Any]:
        """Volledige pipeline: analyse + pagina maken."""
        result = self.run_analysis_only()
        decision = result["decision"]

        if decision.get("action") == "create_page" and decision.get("keyword"):
            page_result = self._create_page_action(decision, result["analysis"])
            result["page"] = page_result
        elif decision.get("action") == "create_blog" and (decision.get("topic") or decision.get("keyword")):
            blog_result = self._create_blog_action(decision)
            result["blog"] = blog_result

        return result
