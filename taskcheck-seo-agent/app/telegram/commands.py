"""Telegram bot commando's."""

from __future__ import annotations

import subprocess
from typing import TYPE_CHECKING

from app.ai.brain import AIBrain
from app.ai.page_writer import PageAlreadyExistsError, PageWriter
from app.blog.writer import BlogWriter
from app.gsc.client import GSCClient
from app.gsc.post_publish import format_gsc_telegram, run_post_deploy_gsc
from app.laravel.publisher import LaravelPublisher
from app.memory.store import MemoryStore
from app.reporting.daily_report import DailyReporter
from app.seo.analyzer import SEOAnalyzer
from app.seo.optimizer import PageOptimizer
from app.seo.page_registry import get_page_registry
from app.gsc.periods import resolve_gsc_period
from app.telegram.chat import ChatRouter
from app.telegram.intents import (
    detect_intent,
    extract_blog_topics_from_history,
    extract_compare_dates,
    extract_create_blog_topic,
    extract_create_page_keyword,
    extract_page_keyword_from_history,
)
from app.utils.config import get_config
from app.utils.files import slugify
from app.utils.logger import setup_logger

if TYPE_CHECKING:
    from app.telegram.bot import SEOBot

logger = setup_logger(__name__)


class CommandHandler:
    def __init__(self, bot: "SEOBot") -> None:
        self.bot = bot
        self.config = get_config()
        self.analyzer = SEOAnalyzer()
        self.brain = AIBrain()
        self.writer = PageWriter()
        self.blog_writer = BlogWriter()
        self.optimizer = PageOptimizer()
        self.publisher = LaravelPublisher()
        self.memory = MemoryStore()
        self.reporter = DailyReporter()
        self.gsc = GSCClient()
        self.registry = get_page_registry()
        self.chat_router = ChatRouter()

    async def cmd_start(self, update, context) -> None:
        name = self.config.owner_name or "daar"
        await update.message.reply_text(
            f"👋 Hoi {name}! Ik ben je TaskCheck SEO-assistent.\n\n"
            "Je kunt gewoon met me praten — stel vragen, vraag advies, of geef opdrachten in normale taal.\n\n"
            "Voorbeelden:\n"
            "• Hoe gaat SEO deze week?\n"
            "• Wat zijn onze grootste kansen?\n"
            "• Maak een blog over NVWA inspecties\n"
            "• Schrijf een pagina voor HACCP checklist\n"
            "• Ja, pas het toe / push naar live\n\n"
            "Ik onthoud het gesprek en werk met live Search Console-data.\n"
            "Typ /help voor alle commando's."
        )

    async def cmd_help(self, update, context) -> None:
        await update.message.reply_text(
            "💬 Chatbot-modus: praat gewoon met me in normale taal.\n\n"
            "Commando's (optioneel):\n"
            "/status [week|maand|3m] — SEO-overzicht\n"
            "/report — Dagelijks rapport\n"
            "/kansen /stijgers /dalers — Data uit Search Console\n"
            "/nieuw [zoekwoord] — SEO-pagina concept\n"
            "/blog [onderwerp] — Blog concept\n"
            "/verbeter [slug] — Pagina optimaliseren\n"
            "/approve — Concept goedkeuren (of: ja toepassen)\n"
            "/push — Naar GitHub main + live deploy\n"
            "/pending — Wat wacht op goedkeuring\n"
            "/volgende — Andere SEO-kans\n"
            "/cancel /hold — Concept afwijzen of parkeren"
        )

    def _period_from_context(self, context):
        from app.gsc.periods import resolve_gsc_period

        args = getattr(context, "args", None) or []
        return resolve_gsc_period(" ".join(args) if args else None)

    def _period_line(self, analysis: dict) -> str:
        p = analysis.get("gsc_period", {})
        if not p:
            return ""
        start, end = p.get("start"), p.get("end")
        label = p.get("label", "")
        if start and end:
            return f"Periode: {label} ({start} t/m {end})\n"
        return f"Periode: {label}\n"

    async def cmd_status(self, update, context) -> None:
        from app.utils.git_health import check_git_health

        period = self._period_from_context(context)
        await update.message.reply_text(f"📊 SEO status ophalen ({period.label})...")
        try:
            git = check_git_health()
            analysis = self.analyzer.find_opportunities(
                days=period.days,
                trend_days=period.trend_days,
                period_label=period.label,
            )
            summary = analysis["summary"]
            cur = summary["current"]
            changes = summary["changes"]

            text = f"""📊 SEO Status — TaskCheck
{self._period_line(analysis)}
🖥 Runtime
{git.summary()}

Impressies: {cur['impressions']:,} ({changes['impressions']:+,})
Klikken: {cur['clicks']:,} ({changes['clicks']:+,})
CTR: {cur['ctr']}% ({changes['ctr']:+.1f}%)
Positie: {cur['position']} ({changes['position']:+.1f})

Beste stijger: {self._best_riser(analysis)}
Nieuwe kansen: {len(analysis.get('new_page_opportunities', []))}
Te verbeteren: {len(analysis.get('improve_opportunities', []))}"""
            await update.message.reply_text(text)
        except Exception as exc:
            logger.exception("Status fout")
            await update.message.reply_text(f"⚠️ Kon status niet ophalen: {exc}")

    async def cmd_report(self, update, context) -> None:
        period = self._period_from_context(context)
        await update.message.reply_text(f"📋 Rapport genereren ({period.label})...")
        try:
            report = self.reporter.generate(
                days=period.days,
                trend_days=period.trend_days,
                period_label=period.label,
            )
            await update.message.reply_text(report)
        except Exception as exc:
            await update.message.reply_text(f"⚠️ Rapport mislukt: {exc}")

    async def cmd_compare_dates(self, update, context, day_a, day_b) -> None:
        await update.message.reply_text(
            f"📊 SEO vergelijken: {day_a.strftime('%d-%m-%Y')} vs {day_b.strftime('%d-%m-%Y')}..."
        )
        try:
            data = self.gsc.compare_two_days(day_a, day_b)
            a, b = data["day_a"], data["day_b"]
            ch = data["changes"]
            text = f"""📊 SEO-vergelijking TaskCheck

{a['date']} → {b['date']}

Impressies: {a['impressions']:,} → {b['impressions']:,} ({ch['impressions']:+,})
Klikken: {a['clicks']:,} → {b['clicks']:,} ({ch['clicks']:+,})
CTR: {a['ctr']}% → {b['ctr']}% ({ch['ctr']:+.2f}%)
Positie: {a['position']} → {b['position']} ({ch['position']:+.1f})

ℹ️ GSC-data loopt ~3 dagen achter; recente dagen kunnen 0 tonen."""
            await update.message.reply_text(text)
        except Exception as exc:
            logger.exception("Datumvergelijking mislukt")
            await update.message.reply_text(f"⚠️ Kon datums niet vergelijken: {exc}")

    async def cmd_kansen(self, update, context) -> None:
        try:
            period = self._period_from_context(context)
            analysis = self.analyzer.find_opportunities(
                days=period.days,
                trend_days=period.trend_days,
                period_label=period.label,
            )
            opps = analysis.get("new_page_opportunities", [])[:5]
            if not opps:
                await update.message.reply_text(f"Geen nieuwe pagina-kansen ({period.label}).")
                return
            lines = [f"🎯 Grootste SEO-kansen ({period.label}):\n"]
            for i, o in enumerate(opps, 1):
                lines.append(f"{i}. {o['keyword']}\n   Pos {o['position']} · {o['impressions']} imp · {o['reason']}")
            await update.message.reply_text("\n".join(lines))
        except Exception as exc:
            await update.message.reply_text(f"⚠️ {exc}")

    async def cmd_stijgers(self, update, context) -> None:
        try:
            period = self._period_from_context(context)
            trends = self.gsc.compare_queries(days=period.trend_days)
            rising = trends.get("rising", [])[:8]
            if not rising:
                await update.message.reply_text(f"Geen stijgers ({period.label}).")
                return
            lines = [f"📈 Stijgers ({period.label}):\n"]
            for r in rising:
                lines.append(f"• {r['query']}: {r.get('prev_position', '?')} → {r['position']} (+{r.get('change_position', 0)})")
            await update.message.reply_text("\n".join(lines))
        except Exception as exc:
            await update.message.reply_text(f"⚠️ {exc}")

    async def cmd_dalers(self, update, context) -> None:
        try:
            period = self._period_from_context(context)
            trends = self.gsc.compare_queries(days=period.trend_days)
            falling = trends.get("falling", [])[:8]
            if not falling:
                await update.message.reply_text(f"Geen dalers ({period.label}).")
                return
            lines = [f"📉 Dalers ({period.label}):\n"]
            for f in falling:
                lines.append(f"• {f['query']}: {f.get('prev_position', '?')} → {f['position']} ({f.get('change_position', 0)})")
            await update.message.reply_text("\n".join(lines))
        except Exception as exc:
            await update.message.reply_text(f"⚠️ {exc}")

    async def cmd_nieuw(self, update, context) -> None:
        keyword = " ".join(context.args) if context.args else None
        if not keyword:
            await update.message.reply_text("Gebruik: /nieuw [zoekwoord]\nBijv: /nieuw NVWA checklist restaurant")
            return

        await update.message.reply_text(f"✍️ Pagina schrijven voor: {keyword}...")
        try:
            existing = self.registry.find_seo_match(keyword)
            blog_match = self.registry.find_blog_match(keyword) if not existing else None
            if blog_match and not existing:
                await update.message.reply_text(
                    f"ℹ️ Er is wel een blog met vergelijkbare slug: {blog_match.url}\n"
                    f"Ik maak een aparte SEO-landingspagina (niet de blog)."
                )

            if existing:
                if existing.source in ("pending", "generated"):
                    await update.message.reply_text(
                        f"ℹ️ Concept bestaat al: `{existing.slug}`\n"
                        f"Match: {existing.reason}\n\n"
                        f"Gebruik /approve om te publiceren of /verbeter {existing.slug} om te optimaliseren."
                    )
                    return

                await update.message.reply_text(
                    f"ℹ️ Deze pagina bestaat al op TaskCheck:\n"
                    f"→ {existing.url}\n"
                    f"Match: {existing.reason}\n\n"
                    f"Ik ga `/verbeter {existing.slug}` uitvoeren i.p.v. een duplicaat."
                )
                if self.memory.has_pending_for_slug(existing.slug):
                    await update.message.reply_text(
                        f"⏸️ Er wacht al een optimalisatie voor `{existing.slug}`.\n"
                        "Stuur 'ja toepassen' of /cancel voordat ik opnieuw begin."
                    )
                    return
                context.args = [existing.slug]
                await self.cmd_verbeter(update, context)
                return

            slug = slugify(keyword)
            result = self.writer.create_page(keyword, slug)
            action_id = self.memory.add_pending_action({
                "type": "create_page",
                "keyword": keyword,
                "slug": slug,
                "path": result["path"],
            })
            self.bot.notifier.notify_new_page({
                "keyword": keyword,
                "reason": "Handmatig aangevraagd via Telegram",
                "path": result["path"],
                "action_id": action_id,
            })
            await update.message.reply_text(f"✅ Concept klaar: {result['path']}\nGebruik /approve om te publiceren.")
        except PageAlreadyExistsError as exc:
            existing = exc.existing
            if existing:
                await update.message.reply_text(
                    f"ℹ️ Pagina bestaat al: {existing.url}\n"
                    f"Gebruik: /verbeter {existing.slug}"
                )
            else:
                await update.message.reply_text(f"ℹ️ {exc}")
        except Exception as exc:
            logger.exception("Nieuw pagina fout")
            await update.message.reply_text(f"⚠️ {exc}")

    async def cmd_verbeter(self, update, context) -> None:
        slug = context.args[0] if context.args else None
        if not slug:
            await update.message.reply_text("Gebruik: /verbeter [slug]\nBijv: /verbeter haccp-app")
            return

        await update.message.reply_text(f"🔧 Pagina optimaliseren: {slug}...")
        try:
            if not self.registry.is_seo_slug(slug):
                blog = self.registry.find_blog_match(slug, slug)
                if blog:
                    await update.message.reply_text(
                        f"ℹ️ `{slug}` is een blog, geen SEO-pagina.\n"
                        f"Blog: {blog.url}\n\n"
                        f"Gebruik /blog om een nieuw artikel te maken, of /nieuw voor een SEO-landingspagina."
                    )
                    return
                await update.message.reply_text(
                    f"⚠️ Geen SEO-pagina gevonden: `{slug}`\n"
                    f"Controleer de slug in resources/views/seo/"
                )
                return

            if self.memory.has_pending_for_slug(slug):
                await update.message.reply_text(
                    f"⏸️ Er wacht al een optimalisatie voor `{slug}`.\n"
                    "Stuur 'ja toepassen' of /cancel."
                )
                return
            result = self.optimizer.optimize_page(slug)
            action_id = self.memory.add_pending_action({
                "type": "optimize_page",
                "slug": slug,
                "path": result["pending_path"],
            })
            self.bot.notifier.notify_page_optimized(result)
            await update.message.reply_text(f"✅ Optimalisatie klaar. Gebruik /approve om toe te passen.")
        except Exception as exc:
            await update.message.reply_text(f"⚠️ {exc}")

    async def cmd_blog(self, update, context) -> None:
        topic = " ".join(context.args).strip() if context.args else ""
        if not topic:
            await update.message.reply_text(
                "Gebruik: /blog [onderwerp]\n"
                "Bijv: /blog NVWA update over inspecties in horeca"
            )
            return

        await update.message.reply_text(f"📰 Blogconcept maken voor: {topic}...")
        try:
            result = self.blog_writer.create_blog(topic, source="Handmatig via Telegram")
            action_id = self.memory.add_pending_action({
                "type": "create_blog",
                "keyword": topic,
                "slug": result["slug"],
                "path": result["pending_path"],
                "reason": "Handmatig aangevraagd blogonderwerp",
            })
            self.bot.notifier.notify_new_blog({
                "topic": topic,
                "reason": "Handmatig aangevraagd via Telegram",
                "path": result["pending_path"],
                "action_id": action_id,
            })
            await update.message.reply_text(
                f"✅ Blogconcept klaar: {result['pending_path']}\nGebruik /approve om via Git klaar te zetten."
            )
        except Exception as exc:
            logger.exception("Blog maken mislukt")
            await update.message.reply_text(f"⚠️ Blog maken mislukt: {exc}")

    async def cmd_blogs_batch(self, update, context, topics: list[str] | None = None) -> None:
        history = self.memory.get_chat_history(limit=12)
        topics = topics or extract_blog_topics_from_history(history)
        if not topics:
            await update.message.reply_text(
                "Ik zie geen blog-onderwerpen in ons gesprek.\n"
                "Stuur eerst ideeën, of: /blog NVWA controles zomer 2026"
            )
            return

        await update.message.reply_text(
            f"📰 {len(topics)} blogconcept(en) maken uit ons gesprek..."
        )
        created: list[str] = []
        errors: list[str] = []
        for topic in topics:
            try:
                result = self.blog_writer.create_blog(topic, source="Batch via Telegram")
                action_id = self.memory.add_pending_action({
                    "type": "create_blog",
                    "keyword": topic,
                    "slug": result["slug"],
                    "path": result["pending_path"],
                    "reason": "Batch bloguit gesprek",
                })
                self.bot.notifier.notify_new_blog({
                    "topic": topic,
                    "reason": "Batch blog uit gesprek",
                    "path": result["pending_path"],
                    "action_id": action_id,
                })
                created.append(f"• {topic}\n  → {result['slug']}")
            except Exception as exc:
                logger.exception("Batch blog mislukt: %s", topic)
                errors.append(f"• {topic}: {exc}")

        lines = [f"✅ {len(created)} blogconcept(en) klaar:\n", *created]
        if errors:
            lines.append("\n⚠️ Mislukt:")
            lines.extend(errors)
        lines.append('\nStuur "ja toepassen" per concept, of /pending voor het overzicht.')
        await update.message.reply_text("\n".join(lines))

    async def cmd_approve(self, update, context) -> None:
        await self._proceed(update, applying=True)

    async def cmd_volgende(self, update, context) -> None:
        await self._proceed(update, applying=False)

    async def _proceed(self, update, applying: bool = True) -> None:
        """Goedkeuren als er een concept is, anders volgende SEO-kans."""
        live = await self._live_slugs()
        self.memory.close_stale_pending(live)
        action = self.memory.get_latest_pending(live)

        if action and applying:
            label = action.get("keyword") or action.get("slug", "")
            await self._reply(update, f"⏳ Bezig met toepassen: {label}...")
            message = await self._apply_action(action, live)
            await self._reply(update, message)
            return

        if applying:
            await self._reply(update, "ℹ️ Geen openstaand concept. Ik zoek de volgende SEO-kans...")
        else:
            await self._reply(update, "🔍 Ik pak de volgende SEO-kans (huidige concept wordt overgeslagen)...")
        try:
            message = await self._run_next_action()
        except Exception as exc:
            logger.exception("Volgende actie mislukt")
            message = f"⚠️ Kon volgende actie niet starten: {exc}"
        await self._reply(update, message)

    async def _apply_action(self, action: dict, live: set[str]) -> str:
        slug = action.get("slug", "")
        if slug in live and action.get("type") == "create_page":
            self.memory.update_pending_action(action["id"], "superseded")
            return (
                f"ℹ️ {slug} staat al live — niets opnieuw gepubliceerd.\n\n"
                f"{await self._run_next_action()}"
            )

        try:
            if action["type"] == "create_page":
                result = self.publisher.publish_page(action["slug"])
                self.memory.update_pending_action(action["id"], "approved")
                self.memory.add_published_page(action["slug"], action.get("keyword", ""))
                if action.get("keyword"):
                    self.memory.add_processed_keyword(action["keyword"])
                self._queue_gsc_url(result)
                self.bot.notifier.notify_action_approved(action)
                if result.get("mode") == "git_only":
                    return (
                        "✅ Klaargezet via Git (nog niet live op productie).\n\n"
                        f"Repo: {result.get('repo_root', '—')}\n"
                        f"Branch: {result.get('branch')}\n"
                        f"Commit: {result.get('commit_sha') or 'nog niet gecommit'}\n"
                        f"Bestanden: {result.get('paths')}\n"
                        f"{self._discovery_summary(result)}\n\n"
                        "Volgende stap: /push → GitHub → Plesk deployt automatisch."
                    )
                gsc_note = await self._maybe_run_direct_gsc(result)
                return (
                    f"✅ Gepubliceerd!\n\nPagina: {result['url']}\n"
                    f"Zoekwoord: {action.get('keyword', slug)}\n"
                    f"{self._discovery_summary(result)}\n{gsc_note}"
                )
            if action["type"] == "optimize_page":
                result = self.publisher.apply_optimization(action["slug"])
                self.memory.update_pending_action(action["id"], "approved")
                self.memory.add_optimized_page(action["slug"], ["Goedgekeurd via Telegram"])
                self.bot.notifier.notify_action_approved(action)
                if result.get("mode") == "git_only":
                    return (
                        "✅ Optimalisatie klaargezet via Git (niet live gezet).\n\n"
                        f"Branch: {result.get('branch')}\n"
                        f"Commit: {result.get('commit_sha') or 'nog niet gecommit'}\n"
                        f"Bestanden: {result.get('paths')}\n"
                        f"{self._discovery_summary(result)}\n\n"
                        "Volgende stap: /push naar productie."
                    )
                return (
                    f"✅ Optimalisatie toegepast!\n\nPagina: {slug}\n"
                    f"Bestand: {result['view_path']}\n{self._discovery_summary(result)}"
                )
            if action["type"] == "create_blog":
                result = self.publisher.publish_blog(action["slug"])
                self.memory.update_pending_action(action["id"], "approved")
                if action.get("keyword"):
                    self.memory.add_processed_keyword(action["keyword"])
                self._queue_gsc_url(result)
                self.bot.notifier.notify_action_approved(action)
                if result.get("mode") == "git_only":
                    return (
                        "✅ Blog klaargezet via Git (niet live gezet).\n\n"
                        f"Branch: {result.get('branch')}\n"
                        f"Commit: {result.get('commit_sha') or 'nog niet gecommit'}\n"
                        f"Bestanden: {result.get('paths')}\n"
                        f"{self._discovery_summary(result)}\n\n"
                        "Volgende stap: /push — daarna GSC sitemap + URL-inspectie."
                    )
                gsc_note = await self._maybe_run_direct_gsc(result)
                return (
                    f"✅ Blog gepubliceerd!\n\nURL: {result['url']}\n"
                    f"Onderwerp: {action.get('keyword', slug)}\n"
                    f"{self._discovery_summary(result)}\n{gsc_note}"
                )
            self.memory.update_pending_action(action["id"], "approved")
            return "✅ Actie goedgekeurd."
        except Exception as exc:
            logger.exception("Goedkeuring mislukt")
            return f"⚠️ Goedkeuring mislukt: {exc}"

    async def cmd_cancel(self, update, context) -> None:
        message = await self._execute_cancel()
        await self._reply(update, message)

    async def cmd_hold(self, update, context) -> None:
        message = await self._execute_hold()
        await self._reply(update, message)

    async def cmd_callback(self, update, context) -> None:
        """Inline knoppen: Toepassen / Annuleren / Later."""
        query = update.callback_query
        await query.answer()

        if query.data == "approve":
            await query.edit_message_reply_markup(reply_markup=None)
            await self._proceed(update, applying=True)
        elif query.data == "cancel":
            await query.edit_message_reply_markup(reply_markup=None)
            message = await self._execute_cancel()
            await query.message.reply_text(message)
        elif query.data == "hold":
            await query.edit_message_reply_markup(reply_markup=None)
            message = await self._execute_hold()
            await query.message.reply_text(message)

    async def _run_next_action(self) -> str:
        """Pak de volgende SEO-kans (ander zoekwoord)."""
        from app.agent import SEOAgent

        agent = SEOAgent()
        result = agent.execute_best_action()
        action = result.get("action", "monitor")
        if action == "skipped":
            return f"Geen nieuwe actie: {result.get('reason', '')}"
        if action == "monitor":
            decision = result.get("decision", {})
            kw = decision.get("keyword") or decision.get("target_page") or "—"
            return f"Geen urgente actie. Advies: {decision.get('reason', 'monitor')} ({kw})"
        if action == "create_page":
            return (
                f"🆕 Volgende kans: {result.get('keyword', '')}\n"
                f"Concept klaar — stuur \"ja toepassen\" om te publiceren."
            )
        if action == "optimize_page":
            return (
                f"✏️ Volgende actie: pagina {result.get('slug', '')} verbeteren.\n"
                f"Stuur \"ja toepassen\" om toe te passen."
            )
        if action == "create_blog":
            return (
                f"📰 Volgende blogkans: {result.get('topic', result.get('keyword', ''))}\n"
                f"Concept klaar — stuur \"ja toepassen\" om via Git klaar te zetten."
            )
        return f"Actie uitgevoerd: {action}"

    async def _live_slugs(self) -> set[str]:
        return (
            set(self.registry.list_slugs("live"))
            | set(self.registry.list_slugs("live_blog"))
            | self.memory.get_published_slugs()
        )

    async def _execute_cancel(self) -> str:
        live = await self._live_slugs()
        action = self.memory.get_latest_pending(live)
        if not action:
            return "Geen openstaande acties om te annuleren."
        self.memory.update_pending_action(action["id"], "cancelled")
        self.bot.notifier.notify_action_cancelled(action)
        label = action.get("keyword") or action.get("slug", action.get("type", ""))
        return f"❌ Geannuleerd: {label}"

    async def _execute_hold(self) -> str:
        live = await self._live_slugs()
        action = self.memory.get_latest_pending(live)
        if not action:
            return "Geen openstaande acties om te parkeren."
        self.memory.update_pending_action(action["id"], "on_hold")
        label = action.get("keyword") or action.get("slug", action.get("type", ""))
        return f"⏸️ Geparkeerd: {label}\n\nStuur later \"ja toepassen\" of klik ✅ Toepassen."

    async def _reply(self, update, text: str) -> None:
        if update.callback_query:
            await update.callback_query.message.reply_text(text)
        elif update.message:
            await update.message.reply_text(text)

    async def cmd_pending(self, update, context) -> None:
        live = await self._live_slugs()
        actions = self.memory.get_all_open_pending(live)
        if not actions:
            await update.message.reply_text("Geen openstaande acties. Stuur /volgende voor een nieuwe kans.")
            return
        lines = ["📋 Openstaande acties:\n"]
        for a in actions:
            label = a.get("keyword") or a.get("slug", a.get("type", ""))
            lines.append(f"• [{a['id']}] {a['type']}: {label}")
        await update.message.reply_text("\n".join(lines))

    async def cmd_push(self, update, context) -> None:
        await self._reply(update, "🚀 Push naar main gestart...")
        result = await self._push_to_main()
        await self._reply(update, result)

    async def handle_message(self, update, context) -> None:
        """Chatbot: natuurlijke taal + acties + gespreksgeheugen."""
        from telegram.constants import ChatAction

        message = update.message.text.strip()
        if not message:
            return

        chat_id = update.effective_chat.id
        regex_intent = detect_intent(message)

        # Snelle route voor expliciete workflow-acties
        if regex_intent == "approve":
            await self._proceed(update, applying=True)
            return
        if regex_intent == "cancel":
            await self._reply_and_remember(update, message, await self._execute_cancel())
            return
        if regex_intent == "hold":
            await self._reply_and_remember(update, message, await self._execute_hold())
            return
        if regex_intent == "pending":
            await self.cmd_pending(update, context)
            return
        if regex_intent == "status":
            await self.cmd_status(update, context)
            return
        if regex_intent == "report":
            await self.cmd_report(update, context)
            return
        if regex_intent == "compare_dates":
            dates = extract_compare_dates(message)
            if dates:
                await self.cmd_compare_dates(update, context, dates[0], dates[1])
            else:
                await update.message.reply_text(
                    "Geef twee datums, bijv:\n"
                    "28-06-2026 & 29-06-2026\n"
                    "of: vergelijk 28 juni en 29 juni"
                )
            return
        if regex_intent == "create_blogs_batch":
            await self.cmd_blogs_batch(update, context)
            return
        if regex_intent == "next":
            await self._proceed(update, applying=False)
            return
        if regex_intent == "push_main":
            await self._reply_and_remember(update, message, await self._push_to_main())
            return
        if regex_intent == "create_blog":
            topic = extract_create_blog_topic(message)
            if not topic:
                history = self.memory.get_chat_history(limit=12)
                topics = extract_blog_topics_from_history(history)
                if topics:
                    if len(topics) > 1:
                        await self.cmd_blogs_batch(update, context, topics)
                    else:
                        fake_ctx = type("Ctx", (), {"args": topics[0].split()})()
                        await self.cmd_blog(update, fake_ctx)
                    return
                topic = message
            fake_ctx = type("Ctx", (), {"args": topic.split()})()
            await self.cmd_blog(update, fake_ctx)
            return
        if regex_intent == "create_page":
            keyword = extract_create_page_keyword(message)
            if not keyword:
                history = self.memory.get_chat_history(limit=12)
                keyword = extract_page_keyword_from_history(history)
            if not keyword:
                await update.message.reply_text(
                    "Voor welk zoekwoord wil je een SEO-pagina?\n"
                    "Bijv: maak een pagina voor HACCP lijsten binnen 30 seconden"
                )
                return
            fake_ctx = type("Ctx", (), {"args": keyword.split()})()
            await self.cmd_nieuw(update, fake_ctx)
            return

        await context.bot.send_chat_action(chat_id=chat_id, action=ChatAction.TYPING)
        self.memory.append_chat("user", message)

        try:
            chat_context = await self._build_chat_context()
            history = self.memory.get_chat_history(limit=14)
            route = self.chat_router.route(message, chat_context, history)
            intent = route.get("intent", "chat")
            params = route.get("params") or {}

            if intent != "chat":
                action_reply = await self._execute_chat_intent(update, context, intent, params)
                if action_reply is not None:
                    if action_reply:
                        await self._reply_and_remember(update, message, action_reply, skip_user=True)
                    return

            reply = self.chat_router.reply(message, chat_context, history)
            pending = chat_context.get("pending_action")
            if pending and any(w in message.lower() for w in ("toepas", "goedkeur", "live", "public")):
                label = pending.get("slug") or pending.get("keyword") or "concept"
                reply += f"\n\n💡 Open concept: {label} — zeg \"ja toepassen\" om door te gaan."

            await self._reply_and_remember(update, message, reply[:4096], skip_user=True)
        except Exception as exc:
            logger.exception("Chat fout")
            await update.message.reply_text(f"⚠️ {exc}")

    async def _build_chat_context(self) -> dict:
        live = await self._live_slugs()
        pending = self.memory.get_latest_pending(live)
        try:
            analysis = self.analyzer.find_opportunities(exclude_handled=False)
            summary = analysis.get("summary", {})
        except Exception:
            analysis = {}
            summary = {}
        return {
            "owner": self.config.owner_name,
            "pending_action": pending,
            "open_pending_count": len(self.memory.get_all_open_pending(live)),
            "summary": summary,
            "top_opportunities": analysis.get("new_page_opportunities", [])[:3],
            "improve_opportunities": analysis.get("improve_opportunities", [])[:3],
            "rising": analysis.get("trends", {}).get("rising", [])[:3],
            "falling": analysis.get("trends", {}).get("falling", [])[:3],
            "recent_published": self.memory.load().get("published_pages", [])[-3:],
        }

    async def _execute_chat_intent(
        self,
        update,
        context,
        intent: str,
        params: dict,
    ) -> str | None:
        """Voer AI-actie uit. None = al afgehandeld via commando; str = tekstantwoord."""
        period_token = (params.get("period") or "").strip()
        fake_ctx = type("Ctx", (), {"args": period_token.split() if period_token else []})()

        if intent == "help":
            await self.cmd_help(update, context)
            return None

        if intent == "status":
            await self.cmd_status(update, fake_ctx)
            return None

        if intent == "report":
            await self.cmd_report(update, fake_ctx)
            return None

        if intent == "compare_dates":
            dates = extract_compare_dates(
                (params.get("period") or "") + " " + (update.message.text if update.message else "")
            )
            if not dates:
                dates = extract_compare_dates(update.message.text if update.message else "")
            if dates:
                await self.cmd_compare_dates(update, context, dates[0], dates[1])
            else:
                return "Geef twee datums, bijv: 28-06-2026 & 29-06-2026"
            return None

        if intent == "create_blogs_batch":
            await self.cmd_blogs_batch(update, context)
            return None

        if intent == "kansen":
            await self.cmd_kansen(update, fake_ctx)
            return None

        if intent == "stijgers":
            await self.cmd_stijgers(update, fake_ctx)
            return None

        if intent == "dalers":
            await self.cmd_dalers(update, fake_ctx)
            return None

        if intent == "approve":
            await self._proceed(update, applying=True)
            return None

        if intent == "cancel":
            return await self._execute_cancel()

        if intent == "hold":
            return await self._execute_hold()

        if intent == "pending":
            await self.cmd_pending(update, context)
            return None

        if intent == "push":
            return await self._push_to_main()

        if intent == "next":
            await self._proceed(update, applying=False)
            return None

        if intent == "create_blog":
            topic = (params.get("topic") or params.get("keyword") or "").strip()
            if not topic:
                history = self.memory.get_chat_history(limit=12)
                topics = extract_blog_topics_from_history(history)
                if len(topics) > 1:
                    await self.cmd_blogs_batch(update, context, topics)
                    return None
                topic = topics[0] if topics else ""
            if not topic:
                return 'Over welk onderwerp? Of stuur "maak de blogs" na blog-ideeën.'
            fake_ctx.args = topic.split()
            await self.cmd_blog(update, fake_ctx)
            return None

        if intent == "create_page":
            keyword = (params.get("keyword") or params.get("topic") or "").strip()
            if not keyword:
                history = self.memory.get_chat_history(limit=12)
                keyword = extract_page_keyword_from_history(history) or ""
            if not keyword:
                return 'Voor welk zoekwoord? Bijv: "schrijf een pagina voor HACCP checklist".'
            fake_ctx.args = keyword.split()
            await self.cmd_nieuw(update, fake_ctx)
            return None

        if intent == "improve_page":
            slug = (params.get("slug") or params.get("keyword") or "").strip()
            if not slug:
                return "Welke pagina wil je verbeteren? Noem de slug, bijv. horeca-check-app."
            fake_ctx.args = [slugify(slug)]
            await self.cmd_verbeter(update, fake_ctx)
            return None

        return None

    async def _reply_and_remember(
        self,
        update,
        user_message: str,
        reply: str,
        *,
        skip_user: bool = False,
    ) -> None:
        if not skip_user:
            self.memory.append_chat("user", user_message)
        if reply:
            self.memory.append_chat("assistant", reply)
            await self._reply(update, reply)

    def _best_riser(self, analysis: dict) -> str:
        rising = analysis.get("trends", {}).get("rising", [])
        if rising:
            return rising[0]["query"]
        opps = analysis.get("new_page_opportunities", [])
        return opps[0]["keyword"] if opps else "—"

    async def _push_to_main(self) -> str:
        repo_root = self.publisher._repo_root()
        if not repo_root:
            return (
                "⚠️ Geen git repository op deze machine (.git ontbreekt).\n\n"
                "Gebruik /push op je Windows-pc (waar git staat), of commit handmatig."
            )
        base = self.config.git_base_branch
        remote = self.config.git_remote
        try:
            status = self._git(["status", "--porcelain"], repo_root)
            if status.stdout.strip():
                return "⚠️ Git status is niet schoon. Commit/stash eerst lokale wijzigingen."

            branch_out = self._git(["rev-parse", "--abbrev-ref", "HEAD"], repo_root)
            current = branch_out.stdout.strip()
            if not current:
                return "⚠️ Kon huidige branch niet bepalen."

            self._git(["fetch", remote], repo_root)
            self._git(["checkout", base], repo_root)
            self._git(["pull", remote, base], repo_root)

            if current != base:
                self._git(["merge", "--no-ff", current, "-m", f"Merge {current} into {base}"], repo_root)

            self._git(["push", remote, base], repo_root)

            if current != base:
                self._git(["checkout", current], repo_root)

            gsc_note = ""
            if self.config.auto_gsc_after_push:
                queue = self.memory.drain_gsc_queue()
                urls = [item["url"] for item in queue if item.get("url")]
                try:
                    gsc_result = run_post_deploy_gsc(urls)
                    gsc_note = "\n\n" + format_gsc_telegram(gsc_result)
                except Exception as exc:
                    gsc_note = f"\n\n⚠️ GSC na push mislukt: {exc}"

            return f"✅ Gepusht naar {remote}/{base} vanaf branch {current}.{gsc_note}"
        except Exception as exc:
            return f"⚠️ Push naar main mislukt: {exc}"

    def _git(self, args: list[str], cwd) -> subprocess.CompletedProcess[str]:
        proc = subprocess.run(
            ["git", *args],
            cwd=cwd,
            capture_output=True,
            text=True,
            check=False,
        )
        if proc.returncode != 0:
            err = (proc.stderr or "").strip() or (proc.stdout or "").strip() or "onbekende git-fout"
            raise RuntimeError(f"git {' '.join(args)} -> {err}")
        return proc

    def _queue_gsc_url(self, result: dict) -> None:
        url = result.get("url")
        if url:
            self.memory.add_gsc_queue_item(url)

    async def _maybe_run_direct_gsc(self, result: dict) -> str:
        if not self.config.auto_gsc_on_direct_publish:
            return ""
        url = result.get("url")
        if not url:
            return ""
        try:
            gsc_result = run_post_deploy_gsc([url])
            return format_gsc_telegram(gsc_result)
        except Exception as exc:
            return f"⚠️ GSC: {exc}"

    def _discovery_summary(self, result: dict) -> str:
        discovery = result.get("discovery")
        if not discovery or not isinstance(discovery, dict):
            return ""
        lines: list[str] = []
        if discovery.get("url"):
            lines.append(f"🌐 {discovery['url']}")
        sitemap = discovery.get("sitemap", {})
        if sitemap.get("added"):
            lines.append("🗺️ Toegevoegd aan sitemap.xml")
        elif sitemap.get("updated"):
            lines.append("🗺️ Sitemap lastmod bijgewerkt")
        llms = discovery.get("llms", {})
        if llms.get("added"):
            lines.append(f"📄 Toegevoegd aan llms.txt ({llms.get('section', '')})")
        blog_index = discovery.get("blog_index", {})
        if blog_index.get("added"):
            lines.append("📰 Blogkaart toegevoegd op /blog")
        return "\n".join(lines)
