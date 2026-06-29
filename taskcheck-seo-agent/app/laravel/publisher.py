"""Publiceer gegenereerde pagina's naar Laravel."""

from __future__ import annotations

import re
import shutil
import subprocess
from datetime import datetime
from pathlib import Path

from app.laravel.discovery_assets import DiscoveryAssets, extract_blade_meta
from app.seo.page_registry import get_page_registry
from app.utils.config import get_config
from app.utils.files import read_text, write_text, write_blade, blade_slug
from app.utils.logger import setup_logger

logger = setup_logger(__name__)


class LaravelPublisher:
    def __init__(self) -> None:
        self.config = get_config()
        self.registry = get_page_registry()
        self.discovery = DiscoveryAssets()

    def publish_page(self, slug: str, source_path: Path | None = None) -> dict[str, str]:
        """Kopieer blade-bestand naar Laravel en voeg route toe."""
        if source_path is None:
            source_path = self.config.pending_dir / f"{slug}.blade.php"
            if not source_path.exists():
                source_path = self.config.generated_dir / f"{slug}.blade.php"

        if not source_path.exists():
            raise FileNotFoundError(f"Bronbestand niet gevonden: {source_path}")

        target = self.config.seo_views_dir / f"{slug}.blade.php"
        self._install_blade(source_path, target)
        logger.info("Pagina gepubliceerd: %s", target)

        route_added = self._add_route(slug)
        discovery = self._register_discovery(target, slug, page_type="seo")
        discovery_paths = self._discovery_paths(discovery)

        if self._git_only():
            return self._commit_changes(
                slug=slug,
                action_type="create_page",
                changed_paths=[target, self.config.web_routes_file, *discovery_paths],
                url=discovery.get("url", f"https://{self.config.site_domain}/{slug}"),
                route_added=route_added,
                discovery=discovery,
            )

        return {
            "mode": "direct",
            "slug": slug,
            "view_path": str(target),
            "route_name": f"seo.{slug}",
            "route_added": str(route_added),
            "url": discovery.get("url", f"https://{self.config.site_domain}/{slug}"),
            "discovery": discovery,
        }

    def apply_optimization(self, slug: str) -> dict[str, str]:
        """Pas geoptimaliseerde versie toe op live pagina."""
        source = self.config.pending_dir / f"{slug}.optimized.blade.php"
        if not source.exists():
            raise FileNotFoundError(f"Geoptimaliseerde versie niet gevonden: {slug}")

        target = self.config.seo_views_dir / f"{slug}.blade.php"
        backup = self.config.data_dir / "backups" / f"{slug}.blade.php.bak"
        backup.parent.mkdir(parents=True, exist_ok=True)

        if target.exists():
            shutil.copy2(target, backup)

        self._install_blade(source, target)
        logger.info("Optimalisatie toegepast: %s", target)

        discovery = self.discovery.touch_url(slug, page_type="seo")
        discovery_paths = []
        if discovery.get("updated"):
            discovery_paths.append(self.config.sitemap_path)

        if self._git_only():
            return self._commit_changes(
                slug=slug,
                action_type="optimize_page",
                changed_paths=[target, *discovery_paths],
                backup=str(backup),
                discovery=discovery,
            )

        return {
            "mode": "direct",
            "slug": slug,
            "view_path": str(target),
            "backup": str(backup),
            "discovery": discovery,
        }

    def publish_blog(self, slug: str, source_path: Path | None = None) -> dict[str, str]:
        """Kopieer blog-concept naar Laravel blog map en voeg blogroute toe."""
        if source_path is None:
            source_path = self.config.pending_dir / f"blog-{slug}.blade.php"
            if not source_path.exists():
                source_path = self.config.generated_dir / f"blog-{slug}.blade.php"

        if not source_path.exists():
            raise FileNotFoundError(f"Blog bronbestand niet gevonden: {source_path}")

        target = self.config.blog_views_dir / f"{slug}.blade.php"
        self._install_blade(source_path, target)
        logger.info("Blog gepubliceerd: %s", target)

        route_added = self._add_blog_route(slug)
        discovery = self._register_discovery(target, slug, page_type="blog")
        discovery_paths = self._discovery_paths(discovery)

        if self._git_only():
            return self._commit_changes(
                slug=slug,
                action_type="create_blog",
                changed_paths=[target, self.config.web_routes_file, *discovery_paths],
                url=discovery.get("url", f"https://{self.config.site_domain}/blog/{slug}"),
                route_added=route_added,
                discovery=discovery,
            )

        return {
            "mode": "direct",
            "slug": slug,
            "view_path": str(target),
            "route_name": f"blog.{slug}",
            "route_added": str(route_added),
            "url": discovery.get("url", f"https://{self.config.site_domain}/blog/{slug}"),
            "discovery": discovery,
        }

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
            logger.warning("web.php niet gevonden: %s", routes_file)
            return False

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
            seo_marker = "->name('seo."
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
            logger.warning("web.php niet gevonden: %s", routes_file)
            return False

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
        """Kopieer Blade naar Laravel met JSON-LD escaping."""
        write_blade(target, read_text(source))

    def _git_only(self) -> bool:
        return self.config.publish_mode == "git_only"

    def _commit_changes(
        self,
        slug: str,
        action_type: str,
        changed_paths: list[Path],
        url: str | None = None,
        route_added: bool | None = None,
        backup: str | None = None,
        discovery: dict | None = None,
    ) -> dict[str, str]:
        """Maak branch + commit zodat deployment via git kan."""
        repo_root = self.config.project_root.parent
        rel_paths = [str(path.relative_to(repo_root)).replace("\\", "/") for path in changed_paths if path.exists()]
        branch = self._ensure_branch(repo_root, slug)

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

        result = {
            "mode": "git_only",
            "slug": slug,
            "view_path": str(self.config.seo_views_dir / f"{slug}.blade.php"),
            "branch": branch,
            "base_branch": self.config.git_base_branch,
            "commit_sha": commit_sha,
            "committed": str(created_commit),
            "paths": ", ".join(rel_paths),
        }
        if url:
            result["url"] = url
        if route_added is not None:
            result["route_added"] = str(route_added)
        if backup:
            result["backup"] = backup
        if discovery:
            result["discovery"] = discovery
        return result

    def _commit_prefix(self, action_type: str) -> str:
        if action_type == "create_page":
            return "create page"
        if action_type == "optimize_page":
            return "optimize page"
        if action_type == "create_blog":
            return "create blog"
        return action_type.replace("_", " ")

    def _ensure_branch(self, repo_root: Path, slug: str) -> str:
        timestamp = datetime.now().strftime("%Y%m%d-%H%M%S")
        branch = f"seo-agent/{slug}-{timestamp}"
        self._run_git(["checkout", "-b", branch], repo_root)
        return branch

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
