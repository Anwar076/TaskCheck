"""Controleer of git klaar is voor 24/7 server publishing."""

from __future__ import annotations

import subprocess
from dataclasses import dataclass
from pathlib import Path

from app.utils.config import get_config


@dataclass
class GitHealth:
    ok: bool
    repo_root: str
    laravel_root: str
    branch: str
    remote_url: str
    issues: list[str]
    hints: list[str]

    def summary(self) -> str:
        lines = [
            f"Git repo: {self.repo_root or '—'}",
            f"Laravel: {self.laravel_root}",
            f"Branch: {self.branch or '—'}",
            f"Remote: {self.remote_url or '—'}",
            f"Status: {'✅ OK' if self.ok else '❌ Probleem'}",
        ]
        for issue in self.issues:
            lines.append(f"⚠️ {issue}")
        for hint in self.hints:
            lines.append(f"💡 {hint}")
        return "\n".join(lines)


def _run_git(args: list[str], cwd: Path) -> subprocess.CompletedProcess[str]:
    return subprocess.run(
        ["git", *args],
        cwd=cwd,
        capture_output=True,
        text=True,
        check=False,
    )


def find_git_root(start: Path) -> Path | None:
    current = start.resolve()
    for _ in range(10):
        if (current / ".git").exists():
            return current
        parent = current.parent
        if parent == current:
            break
        current = parent
    return None


def check_git_health() -> GitHealth:
    config = get_config()
    issues: list[str] = []
    hints: list[str] = []

    laravel = str(config.laravel_root)
    repo = find_git_root(config.laravel_root) or find_git_root(config.project_root.parent)

    if not repo:
        issues.append("Geen .git gevonden")
        if (config.project_root.parent.parent / "git").is_dir():
            hints.append(
                "Plesk: zet GIT_REPO_ROOT=/var/www/vhosts/taskcheck.nl/git/laravel_e55cd2 in .env"
            )
        hints.append("Run de agent vanuit de git clone, niet alleen httpdocs")
        return GitHealth(False, "", laravel, "", "", issues, hints)

    branch = ""
    out = _run_git(["rev-parse", "--abbrev-ref", "HEAD"], repo)
    if out.returncode == 0:
        branch = out.stdout.strip()

    remote_url = ""
    remote = config.git_remote
    rem = _run_git(["remote", "get-url", remote], repo)
    if rem.returncode == 0:
        remote_url = rem.stdout.strip()
    else:
        issues.append(f"Git remote '{remote}' niet geconfigureerd")

    if config.publish_mode == "git_only" and laravel != str(repo):
        hints.append(f"GIT_REPO_ROOT actief: bestanden gaan naar {laravel}")

    name = _run_git(["config", "user.name"], repo)
    email = _run_git(["config", "user.email"], repo)
    if not name.stdout.strip() or not email.stdout.strip():
        issues.append("git user.name / user.email niet ingesteld (nodig voor commits)")
        hints.append(f'git config user.email "seo@taskcheck.nl" && git config user.name "TaskCheck SEO Agent"')

    ok = not issues
    return GitHealth(ok, str(repo), laravel, branch, remote_url, issues, hints)
