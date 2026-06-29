"""Bestandshulp voor de SEO agent."""

from __future__ import annotations

import json
import re
from pathlib import Path
from typing import Any


def read_text(path: Path, default: str = "") -> str:
    if not path.exists():
        return default
    return path.read_text(encoding="utf-8")


def write_text(path: Path, content: str) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_text(content, encoding="utf-8")


def read_json(path: Path, default: Any = None) -> Any:
    if default is None:
        default = {}
    if not path.exists():
        return default
    try:
        return json.loads(path.read_text(encoding="utf-8"))
    except json.JSONDecodeError:
        return default


def write_json(path: Path, data: Any) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_text(json.dumps(data, indent=2, ensure_ascii=False), encoding="utf-8")


def slugify(text: str) -> str:
    slug = text.lower().strip()
    slug = re.sub(r"[^\w\s-]", "", slug)
    slug = re.sub(r"[\s_]+", "-", slug)
    return slug.strip("-")


def load_company_context(path: Path) -> str:
    content = read_text(path)
    match = re.search(r'COMPANY_CONTEXT\s*=\s*"""(.*?)"""', content, re.DOTALL)
    if match:
        return match.group(1).strip()
    return content.strip()


def load_lines(path: Path) -> list[str]:
    if not path.exists():
        return []
    return [
        line.strip()
        for line in path.read_text(encoding="utf-8").splitlines()
        if line.strip() and not line.strip().startswith("#")
    ]


def blade_slug(path: Path) -> str:
    """Haal slug uit Laravel blade bestandsnaam (bijv. haccp-app.blade.php → haccp-app)."""
    name = path.name
    if name.endswith(".blade.php"):
        return name[: -len(".blade.php")]
    return path.stem


def list_seo_pages(seo_dir: Path) -> list[dict[str, str]]:
    pages = []
    if not seo_dir.exists():
        return pages
    for path in sorted(seo_dir.glob("*.blade.php")):
        if path.name == "coming-soon.blade.php":
            continue
        slug = blade_slug(path)
        pages.append({"slug": slug, "filename": path.name, "path": str(path)})
    return pages


def list_blog_pages(blog_dir: Path) -> list[dict[str, str]]:
    pages = []
    if not blog_dir.exists():
        return pages
    for path in sorted(blog_dir.glob("*.blade.php")):
        slug = blade_slug(path)
        pages.append({"slug": slug, "filename": path.name, "path": str(path)})
    return pages


def extract_json_from_response(text: str) -> dict:
    """Parse JSON uit AI-response, ook als het in markdown fences staat."""
    text = text.strip()
    fence_match = re.search(r"```(?:json)?\s*([\s\S]*?)```", text)
    if fence_match:
        text = fence_match.group(1).strip()
    return json.loads(text)
