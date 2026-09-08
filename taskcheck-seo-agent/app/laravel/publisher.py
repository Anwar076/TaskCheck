"""Publiceer gegenereerde pagina's naar Laravel — altijd via git in git_only mode."""

from __future__ import annotations

import re
import shutil
import subprocess
import time
from pathlib import Path

from app.laravel.blog_index import BlogIndexUpdater
from app.laravel.discovery_assets import DiscoveryAssets, extract_blade_meta
from app.seo.page_registry import get_page_registry
from app.utils.config import get_config
from app.utils.files import read_text, write_text, write_blade, blade_slug
from app.utils.logger import setup_logger

logger = setup_logger(__name__)


class GitRepositoryRequiredError(RuntimeError):
    """Geen git repository — publiceren geblokkeerd in git_only mode."""


class LaravelPublisher:
    def __init__(self) -> None:
        self.config = get_config()
        self.registry = get_page_registry()
        self.discovery = DiscoveryAssets()
        self.blog_index = BlogIndexUpdater()
        self._cached_repo_root: Path | None = None
        self._active_branch: str | None = None

    def _repo_root(self) -> Path | None:
        if self._cached_repo_root is not None:
            return self._cached_repo_root

        candidates: list[Path] = [self.config.laravel_root]
        if self.config.git_repo_root:
            candidates.insert(0, Path(self.config.git_repo_root).expanduser().resolve())
        candidates.append(self.config.project_root.parent)

        seen: set[Path] = set()
        for start in candidates:
            if start in seen:
                continue
            seen.add(start)
            found = self._find_git_root(start)
            if found:
                self._cached_repo_root = found
                logger.info("Git repository: %s", found)
                return found

        self._cached_repo_root = None
        return None

    @staticmethod
    def _find_git_root(start: Path) -> Path | None:
        current = start.resolve()
        for _ in range(10):
            if (current / ".git").exists():
                return current
            parent = current.parent
            if parent == current:
                break
            current = parent
        return None

    def _uses_git(self) -> bool:
        return self.config.publish_mode == "git_only"

    def _require_git_repo(self) -> Path:
        repo = self._repo_root()
        if not repo:
            plesk_hint = ""
            near = self.config.project_root.parent
            if (near.parent / "git").is_dir():
                plesk_hint = (
                    f"\nPlesk gedetecteerd: zet in .env:\n"
                    f"GIT_REPO_ROOT={near.parent / 'git'}/laravel_e55cd2\n"
                    f"(pas mapnaam aan als die anders heet)\n"
                )
            raise GitRepositoryRequiredError(
                "Publiceren geblokkeerd: geen git repository (.git) gevonden.\n\n"
                f"Huidig Laravel pad: {self.config.laravel_root}\n"
                f"{plesk_hint}\n"
                "Aanbevolen: run de bot op je Windows-pc (C:\\laragon\\www\\surveycams).\n"
                "Workflow: goedkeuren → /push → GitHub → Plesk deployt."
            )
        return repo

    def _begin_git_publish(self, slug: str) -> tuple[Path, str]:
        """Maak branch vóór bestandswijzigingen — niets live zonder git."""
        repo = self._require_git_repo()
        branch = self._ensure_branch(repo, slug)
        self._active_branch = branch
        return repo, branch

    def publish_page(self, slug: str, source_path: Path | None = None) -> dict[str, str]:
        if source_path is None:
            source_path = self.config.pending_dir / f"{slug}.blade.php"
            if not source_path.exists():
                source_path = self.config.generated_dir / f"{slug}.blade.php"

        if not source_path.exists():
            raise FileNotFoundError(f"Bronbestand niet gevonden: {source_path}")

        branch = ""
        if self._uses_git():
            _, branch = self._begin_git_publish(slug)

        target = self.config.seo_views_dir / f"{slug}.blade.php"
        self._install_blade(source_path, target)
        logger.info("Pagina geschreven in git werkmap: %s", target)

        route_added = self._add_route(slug)
        discovery = self._register_discovery(target, slug, page_type="seo")
        discovery_paths = self._discovery_paths(discovery)

        if self._uses_git():
            return self._commit_changes(
                slug=slug,
                action_type="create_page",
                changed_paths=[target, self.config.web_routes_file, *discovery_paths],
                url=discovery.get("url", f"https://{self.config.site_domain}/{slug}"),
                route_added=route_added,
                discovery=discovery,
                branch=branch,
            )

        return self._direct_result(
            slug, target, discovery, route_added, f"seo.{slug}", page_type="seo"
        )

    def apply_optimization(self, slug: str) -> dict[str, str]:
        source = self.config.pending_dir / f"{slug}.optimized.blade.php"
        if not source.exists():
            raise FileNotFoundError(f"Geoptimaliseerde versie niet gevonden: {slug}")

        branch = ""
        if self._uses_git():
            _, branch = self._begin_git_publish(slug)

        target = self.config.seo_views_dir / f"{slug}.blade.php"
        backup = self.config.data_dir / "backups" / f"{slug}.blade.php.bak"
        backup.parent.mkdir(parents=True, exist_ok=True)

        if target.exists():
            shutil.copy2(target, backup)

        self._install_blade(source, target)
        logger.info("Optimalisatie geschreven in git werkmap: %s", target)

        discovery = self.discovery.touch_url(slug, page_type="seo")
        discovery_paths = []
        if discovery.get("updated"):
            discovery_paths.append(self.config.sitemap_path)

        if self._uses_git():
            return self._commit_changes(
                slug=slug,
                action_type="optimize_page",
                changed_paths=[target, *discovery_paths],
                backup=str(backup),
                discovery=discovery,
                branch=branch,
            )

        return self._direct_result(
            slug, target, discovery, False, f"seo.{slug}", backup=str(backup)
        )

    def publish_blog(self, slug: str, source_path: Path | None = None) -> dict[str, str]:
        if source_path is None:
            source_path = self.config.pending_dir / f"blog-{slug}.blade.php"
            if not source_path.exists():
                source_path = self.config.generated_dir / f"blog-{slug}.blade.php"

        if not source_path.exists():
            raise FileNotFoundError(f"Blog bronbestand niet gevonden: {source_path}")

        branch = ""
        if self._uses_git():
            _, branch = self._begin_git_publish(slug)

        target = self.config.blog_views_dir / f"{slug}.blade.php"
        self._install_blade(source_path, target)
        logger.info("Blog geschreven in git werkmap: %s", target)

        route_added = self._add_blog_route(slug)
        discovery = self._register_discovery(target, slug, page_type="blog")
        index_card = self.blog_index.add_card(slug, target)
        discovery_paths = self._discovery_paths(discovery)
        if index_card.get("added") and index_card.get("path"):
            discovery_paths.append(Path(index_card["path"]))

        if self._uses_git():
            return self._commit_changes(
                slug=slug,
                action_type="create_blog",
                changed_paths=[target, self.config.web_routes_file, *discovery_paths],
                url=discovery.get("url", f"https://{self.config.site_domain}/blog/{slug}"),
                route_added=route_added,
                discovery={**discovery, "blog_index": index_card},
                branch=branch,
            )

        return self._direct_result(
            slug, target, {**discovery, "blog_index": index_card}, route_added, f"blog.{slug}", page_type="blog"
        )

    def _direct_result(
        self,
        slug: str,
        target: Path,
        discovery: dict,
        route_added: bool,
        route_name: str,
        page_type: str = "seo",
        backup: str | None = None,
    ) -> dict:
        url = discovery.get("url") or (
            f"https://{self.config.site_domain}/blog/{slug}"
            if page_type == "blog"
            else f"https://{self.config.site_domain}/{slug}"
        )
        result: dict = {
            "mode": "direct",
            "slug": slug,
            "view_path": str(target),
            "route_name": route_name,
            "route_added": str(route_added),
            "url": url,
            "discovery": discovery,
        }
        if backup:
            result["backup"] = backup
        return result

    def _register_discovery(self, blade_path: Path, slug: str, page_type: str) -> dict:
        content = read_text(blade_path)
        title, description = extract_blade_meta(content)
        return self.discovery.register_page(
            slug=slug,
            page_type=page_type,
            title=title or None,
            description=description or None,
        )

    def _discovery_paths(self, discovery: dict) -> list[Path]:
        paths: list[Path] = []
        sitemap = discovery.get("sitemap", {})
        llms = discovery.get("llms", {})
        if sitemap.get("added") or sitemap.get("updated"):
            paths.append(self.config.sitemap_path)
        if llms.get("added"):
            paths.append(self.config.llms_txt_path)
        return paths

    def _add_route(self, slug: str) -> bool:
        routes_file = self.config.web_routes_file
        if not routes_file.exists():
            raise FileNotFoundError(f"web.php niet gevonden: {routes_file}")

        content = read_text(routes_file)
        route_name = f"seo.{slug}"

        if route_name in content:
            logger.info("Route bestaat al: %s", route_name)
            return False

        route_block = f"""
Route::get('/{slug}', function () {{
    return view('seo.{slug}');
}})->name('{route_name}');
"""

        marker = "// SEO Agent routes"
        if marker in content:
            content = content.replace(marker, f"{route_block}{marker}")
        else:
            last_seo = content.rfind("->name('seo.")
            if last_seo != -1:
                line_end = content.find("\n", last_seo)
                insert_pos = line_end + 1 if line_end != -1 else len(content)
                content = content[:insert_pos] + route_block + content[insert_pos:]
            else:
                content += f"\n{route_block}"

        write_text(routes_file, content)
        logger.info("Route toegevoegd: %s", route_name)
        return True

    def _add_blog_route(self, slug: str) -> bool:
        routes_file = self.config.web_routes_file
        if not routes_file.exists():
            raise FileNotFoundError(f"web.php niet gevonden: {routes_file}")

        content = read_text(routes_file)
        route_name = f"blog.{slug}"
        if route_name in content:
            logger.info("Blog route bestaat al: %s", route_name)
            return False

        route_block = f"""
Route::get('/blog/{slug}', function () {{
    return view('blog.{slug}');
}})->name('{route_name}');
"""
        blog_anchor = "Route::get('/blog'"
        anchor_pos = content.find(blog_anchor)
        if anchor_pos != -1:
            insert_pos = content.rfind("\n", anchor_pos)
            insert_pos = len(content) if insert_pos == -1 else insert_pos
            content = content[:insert_pos] + route_block + content[insert_pos:]
        else:
            content += f"\n{route_block}"

        write_text(routes_file, content)
        logger.info("Blog route toegevoegd: %s", route_name)
        return True

    def route_exists(self, slug: str) -> bool:
        return self.registry.exists_exact(slug)

    def page_exists_for_keyword(self, keyword: str, slug: str | None = None) -> bool:
        return self.registry.exists(keyword, slug)

    def list_pending_pages(self) -> list[str]:
        pending = []
        if self.config.pending_dir.exists():
            for path in self.config.pending_dir.glob("*.blade.php"):
                if ".optimized." not in path.name:
                    pending.append(blade_slug(path))
        return pending

    def _install_blade(self, source: Path, target: Path) -> None:
        content = read_text(source)
        if self._is_seo_view_target(target):
            self._assert_seo_shared_layout(content, target.name)
        write_blade(target, content)

    def _is_seo_view_target(self, target: Path) -> bool:
        try:
            return target.resolve().parent == self.config.seo_views_dir.resolve()
        except OSError:
            return "resources/views/seo" in str(target).replace("\\", "/")

    def _assert_seo_shared_layout(self, content: str, filename: str) -> None:
        """Elke SEO Blade moet layouts.seo-page gebruiken (geen standalone HTML)."""
        has_extends = (
            "@extends('layouts.seo-page')" in content
            or '@extends("layouts.seo-page")' in content
        )
        if not has_extends:
            raise RuntimeError(
                f"{filename} mist @extends('layouts.seo-page'). "
                "SEO-pagina's moeten de gedeelde layout gebruiken."
            )
        if "<!DOCTYPE html>" in content or re.search(r"<html\b", content, re.I):
            raise RuntimeError(
                f"{filename} bevat nog een standalone HTML-document. "
                "Gebruik @extends('layouts.seo-page') in plaats van eigen <html>/<body>."
            )

    def _commit_changes(
        self,
        slug: str,
        action_type: str,
        changed_paths: list[Path],
        url: str | None = None,
        route_added: bool | None = None,
        backup: str | None = None,
        discovery: dict | None = None,
        branch: str = "",
    ) -> dict[str, str]:
        repo_root = self._require_git_repo()
        rel_paths = [
            str(path.relative_to(repo_root)).replace("\\", "/")
            for path in changed_paths
            if path.exists()
        ]

        self._run_git(["add", "--", *rel_paths], repo_root)

        commit_message = (
            f"seo: {self._commit_prefix(action_type)} {slug}\n\n"
            "Approved by SEO agent via Telegram.\n"
            "Prepared for review and deploy via git."
        )

        created_commit = False
        commit_sha = ""
        if self.config.git_auto_commit:
            status = self._run_git(["diff", "--cached", "--name-only"], repo_root)
            if status.stdout.strip():
                self._run_git(["commit", "-m", commit_message], repo_root)
                sha_out = self._run_git(["rev-parse", "--short", "HEAD"], repo_root)
                commit_sha = sha_out.stdout.strip()
                created_commit = True

        merged_into_base = False
        if created_commit and branch and branch != self.config.git_base_branch:
            merged_into_base = self._merge_into_base(repo_root, branch)

        return {
            "mode": "git_only",
            "slug": slug,
            "view_path": str(self.config.seo_views_dir / f"{slug}.blade.php"),
            "branch": branch or self._active_branch or "",
            "base_branch": self.config.git_base_branch,
            "commit_sha": commit_sha,
            "committed": str(created_commit),
            "merged_into_base": str(merged_into_base),
            "live_branch": self.config.git_base_branch if merged_into_base else (branch or self._active_branch or ""),
            "paths": ", ".join(rel_paths),
            "repo_root": str(repo_root),
            **({"url": url} if url else {}),
            **({"route_added": str(route_added)} if route_added is not None else {}),
            **({"backup": backup} if backup else {}),
            **({"discovery": discovery} if discovery else {}),
        }

    def _merge_into_base(self, repo_root: Path, branch: str) -> bool:
        """Zet de commit ook op de basisbranch.

        Zonder dit blijft de pagina achter op een zijbranch: zodra je terug naar
        main gaat is het bestand en de route weer weg, en lijkt het alsof de
        agent niets heeft aangemaakt.
        """
        base = self.config.git_base_branch
        try:
            self._checkout_if_needed(repo_root, base)
            self._run_git(
                ["merge", "--no-ff", branch, "-m", f"Merge {branch} into {base}"],
                repo_root,
            )
        except RuntimeError as exc:
            logger.error("Samenvoegen met %s mislukt: %s", base, exc)
            try:
                self._checkout_if_needed(repo_root, branch)
                logger.info("Wijzigingen blijven op branch %s staan", branch)
            except RuntimeError:
                logger.exception("Kon niet terugschakelen naar %s", branch)
            return False

        self._active_branch = base
        logger.info("Pagina staat nu op %s (branch %s blijft bewaard)", base, branch)
        return True

    def _commit_prefix(self, action_type: str) -> str:
        if action_type == "create_page":
            return "create page"
        if action_type == "optimize_page":
            return "optimize page"
        if action_type == "create_blog":
            return "create blog"
        return action_type.replace("_", " ")

    def _current_branch(self, repo_root: Path) -> str:
        return (self._run_git(["rev-parse", "--abbrev-ref", "HEAD"], repo_root).stdout or "").strip()

    def _checkout_if_needed(self, repo_root: Path, branch: str) -> None:
        """Wissel alleen van branch als dat nodig is.

        Op Windows faalt `git checkout` van de huidige branch vaak met
        `couldn't set HEAD` als Cursor of GitHub Desktop .git/HEAD open heeft.
        """
        if self._current_branch(repo_root) == branch:
            logger.info("Al op branch %s — checkout overgeslagen", branch)
            return

        last_error: RuntimeError | None = None
        for attempt in range(1, 4):
            try:
                self._run_git(["checkout", branch], repo_root)
                return
            except RuntimeError as exc:
                last_error = exc
                text = str(exc)
                if "couldn't set 'HEAD'" not in text and "unable to update HEAD" not in text:
                    raise
                if self._current_branch(repo_root) == branch:
                    logger.warning("HEAD was vergrendeld, maar we staan al op %s", branch)
                    return
                logger.warning("Checkout %s poging %s mislukt: %s", branch, attempt, exc)
                time.sleep(0.4 * attempt)

        raise RuntimeError(
            f"{last_error}\n\n"
            "Git kan HEAD niet bijwerken omdat een ander programma de repo open heeft "
            "(vaak GitHub Desktop of Cursor). Sluit die even, of keur opnieuw goed "
            f"terwijl je al op `{branch}` staat."
        )

    def _ensure_branch(self, repo_root: Path, slug: str) -> str:
        base = self.config.git_base_branch
        self._assert_target_paths_clean(repo_root, slug)
        self._checkout_if_needed(repo_root, base)
        # Geen extra feature-branch: die vereist HEAD herschrijven en wordt
        # daarna sowieso terug gemerged naar main. Committen op main is genoeg
        # voor /push → GitHub → Plesk.
        self._active_branch = base
        return base

    def _assert_target_paths_clean(self, repo_root: Path, slug: str) -> None:
        """Blokkeer alleen als de bestanden die de agent zélf herschrijft
        openstaande wijzigingen hebben.

        Er wordt per pad gestaged (zie _commit_changes), dus overig werk in de
        repo komt nooit in de commit terecht en hoeft publiceren niet te blokkeren.
        """
        targets = self._target_rel_paths(repo_root, slug)
        if not targets:
            return

        status = self._run_git(["status", "--porcelain", "--", *targets], repo_root)
        dirty = sorted({line[3:].strip() for line in status.stdout.splitlines() if line.strip()})
        if dirty:
            raise RuntimeError(
                "Deze bestanden worden door de agent aangepast maar hebben nog "
                "niet-gecommitte wijzigingen:\n"
                + "\n".join(f"• {path}" for path in dirty)
                + "\n\nCommit of stash ze eerst, daarna opnieuw goedkeuren."
            )

    def _target_rel_paths(self, repo_root: Path, slug: str) -> list[str]:
        candidates = [
            self.config.seo_views_dir / f"{slug}.blade.php",
            self.config.blog_views_dir / f"{slug}.blade.php",
            self.config.web_routes_file,
            self.config.sitemap_path,
            self.config.llms_txt_path,
            self.config.laravel_root / "resources" / "views" / "blog.blade.php",
        ]

        rel_paths: list[str] = []
        for path in candidates:
            try:
                rel = path.resolve().relative_to(repo_root.resolve())
            except ValueError:
                continue
            rel_str = str(rel).replace("\\", "/")
            if rel_str not in rel_paths:
                rel_paths.append(rel_str)
        return rel_paths

    def _run_git(self, args: list[str], repo_root: Path) -> subprocess.CompletedProcess[str]:
        proc = subprocess.run(
            ["git", *args],
            cwd=repo_root,
            capture_output=True,
            text=True,
            check=False,
        )
        if proc.returncode != 0:
            stderr = (proc.stderr or "").strip()
            stdout = (proc.stdout or "").strip()
            output = stderr or stdout or "onbekende git-fout"
            raise RuntimeError(f"Git commando mislukt: git {' '.join(args)} -> {output}")
        return proc
