"""Inventaris van bestaande TaskCheck SEO-pagina's."""

from __future__ import annotations

import re
from dataclasses import dataclass, field
from functools import lru_cache
from typing import Any

from app.utils.config import get_config
from app.utils.files import list_seo_pages, list_blog_pages, read_text, slugify, blade_slug

STOP_WORDS = {
    "de", "het", "een", "en", "van", "voor", "met", "in", "op", "te", "bij",
    "naar", "als", "is", "are", "the", "a", "an", "of", "uit", "door",
}

SEO_SOURCES = frozenset({"live", "route_only", "pending", "generated"})
BLOG_SOURCES = frozenset({"live_blog", "route_only_blog"})


@dataclass
class PageMatch:
    slug: str
    match_type: str
    confidence: float
    url: str
    title: str = ""
    reason: str = ""
    source: str = "live"

    def to_dict(self) -> dict[str, Any]:
        return {
            "slug": self.slug,
            "match_type": self.match_type,
            "confidence": self.confidence,
            "url": self.url,
            "title": self.title,
            "reason": self.reason,
            "source": self.source,
        }


@dataclass
class SEOPageRegistry:
    """Kent alle live, pending en gegenereerde SEO-pagina's op TaskCheck."""

    config: Any = field(default_factory=get_config)
    _pages: list[dict[str, Any]] = field(default_factory=list, init=False)

    def __post_init__(self) -> None:
        self.refresh()

    def refresh(self) -> None:
        self._pages = self._load_all_pages()

    def _load_all_pages(self) -> list[dict[str, Any]]:
        pages: dict[str, dict[str, Any]] = {}

        for info in list_seo_pages(self.config.seo_views_dir):
            slug = info["slug"]
            path = self.config.seo_views_dir / info["filename"]
            meta = self._extract_page_meta(read_text(path), slug)
            pages[slug] = {
                "slug": slug,
                "source": "live",
                "path": str(path),
                "route_name": f"seo.{slug}",
                "url": f"https://{self.config.site_domain}/{slug}",
                **meta,
            }

        for info in list_blog_pages(self.config.blog_views_dir):
            slug = info["slug"]
            path = self.config.blog_views_dir / info["filename"]
            meta = self._extract_page_meta(read_text(path), slug)
            pages[f"blog:{slug}"] = {
                "slug": slug,
                "source": "live_blog",
                "path": str(path),
                "route_name": f"blog.{slug}",
                "url": f"https://{self.config.site_domain}/blog/{slug}",
                **meta,
            }

        routes_content = read_text(self.config.web_routes_file)
        for match in re.finditer(
            r"Route::get\('/([^']+)',\s*function\s*\(\)\s*\{\s*return\s*view\('seo\.([^']+)'\)",
            routes_content,
        ):
            url_slug, view_slug = match.group(1), match.group(2)
            if view_slug not in pages:
                pages[view_slug] = {
                    "slug": view_slug,
                    "source": "route_only",
                    "path": "",
                    "route_name": f"seo.{view_slug}",
                    "url": f"https://{self.config.site_domain}/{url_slug}",
                    "title": "",
                    "tokens": self._tokenize(view_slug),
                }

        for match in re.finditer(
            r"Route::get\('/blog/([^']+)',\s*function\s*\(\)\s*\{\s*return\s*view\('blog\.([^']+)'\)",
            routes_content,
        ):
            url_slug, view_slug = match.group(1), match.group(2)
            key = f"blog:{view_slug}"
            if key not in pages:
                pages[key] = {
                    "slug": view_slug,
                    "source": "route_only_blog",
                    "path": "",
                    "route_name": f"blog.{view_slug}",
                    "url": f"https://{self.config.site_domain}/blog/{url_slug}",
                    "title": "",
                    "tokens": self._tokenize(view_slug),
                }

        for folder, source in (
            (self.config.pending_dir, "pending"),
            (self.config.generated_dir, "generated"),
        ):
            if not folder.exists():
                continue
            for path in folder.glob("*.blade.php"):
                if ".optimized." in path.name:
                    continue
                slug = blade_slug(path)
                if slug not in pages:
                    meta = self._extract_page_meta(read_text(path), slug)
                    pages[slug] = {
                        "slug": slug,
                        "source": source,
                        "path": str(path),
                        "route_name": f"seo.{slug}",
                        "url": f"https://{self.config.site_domain}/{slug}",
                        **meta,
                    }

        return list(pages.values())

    def find_match(self, keyword: str, slug: str | None = None) -> PageMatch | None:
        """Zoek een bestaande pagina (SEO of blog) die bij dit zoekwoord hoort."""
        return self._find_match_in(keyword, slug, sources=None)

    def find_seo_match(self, keyword: str, slug: str | None = None) -> PageMatch | None:
        """Zoek alleen een SEO-landingspagina — geen blog-match."""
        return self._find_match_in(keyword, slug, sources=SEO_SOURCES)

    def find_blog_match(self, keyword: str, slug: str | None = None) -> PageMatch | None:
        """Zoek alleen een blogpagina."""
        return self._find_match_in(keyword, slug, sources=BLOG_SOURCES)

    def is_seo_slug(self, slug: str) -> bool:
        self.refresh()
        return any(
            p["slug"] == slug and p.get("source") in SEO_SOURCES
            for p in self._pages
        )

    def _find_match_in(
        self,
        keyword: str,
        slug: str | None,
        sources: frozenset[str] | None,
    ) -> PageMatch | None:
        self.refresh()

        slug = slug or slugify(keyword)
        keyword_norm = self._normalize(keyword)
        slug_norm = self._normalize(slug)
        keyword_tokens = self._tokenize(keyword)

        pages = self._pages
        if sources is not None:
            pages = [p for p in pages if p.get("source") in sources]

        for page in pages:
            page_slug = page["slug"]
            page_slug_norm = self._normalize(page_slug)

            if page_slug == slug or page_slug_norm == slug_norm:
                return self._match(page, "exact_slug", 1.0, f"Exacte slug match: {page_slug}")

            if slug_norm and (slug_norm in page_slug_norm or page_slug_norm in slug_norm):
                return self._match(page, "slug_similar", 0.95, f"Vergelijkbare slug: {page_slug}")

        for page in pages:
            title = page.get("title", "")
            if title and keyword_norm in self._normalize(title):
                return self._match(page, "title_contains", 0.9, f"Titel bevat zoekwoord: {title[:60]}")

            if title:
                title_score = self._token_overlap(keyword_tokens, self._tokenize(title))
                if title_score >= 0.75:
                    return self._match(
                        page,
                        "title_similar",
                        title_score,
                        f"Vergelijkbare titel ({int(title_score * 100)}%): {title[:60]}",
                    )

        best: PageMatch | None = None
        for page in pages:
            page_tokens = page.get("tokens") or self._tokenize(page["slug"])
            slug_score = self._token_overlap(keyword_tokens, page_tokens)
            if slug_score >= 0.66:
                candidate = self._match(
                    page,
                    "keyword_overlap",
                    slug_score,
                    f"Overlappend zoekwoord ({int(slug_score * 100)}%): {page['slug']}",
                )
                if not best or candidate.confidence > best.confidence:
                    best = candidate

        return best

    def exists(self, keyword: str, slug: str | None = None) -> bool:
        return self.find_match(keyword, slug) is not None

    def exists_exact(self, slug: str) -> bool:
        self.refresh()
        slug_norm = self._normalize(slug)
        return any(
            p["slug"] == slug or self._normalize(p["slug"]) == slug_norm
            for p in self._pages
        )

    def list_slugs(self, source: str | None = None) -> list[str]:
        self.refresh()
        if source:
            return [p["slug"] for p in self._pages if p.get("source") == source]
        return [p["slug"] for p in self._pages]

    def get_page(self, slug: str) -> dict[str, Any] | None:
        self.refresh()
        for page in self._pages:
            if page["slug"] == slug:
                return page
        return None

    def _match(self, page: dict, match_type: str, confidence: float, reason: str) -> PageMatch:
        return PageMatch(
            slug=page["slug"],
            match_type=match_type,
            confidence=confidence,
            url=page.get("url", ""),
            title=page.get("title", ""),
            reason=reason,
            source=page.get("source", "live"),
        )

    def _extract_page_meta(self, content: str, slug: str) -> dict[str, Any]:
        title = ""
        for pattern in (
            r"\$seoTitle\s*=\s*'([^']+)'",
            r'\$seoTitle\s*=\s*"([^"]+)"',
        ):
            match = re.search(pattern, content)
            if match:
                title = match.group(1).strip()
                break

        tokens = self._tokenize(slug) | self._tokenize(title)
        return {"title": title, "tokens": tokens}

    def _normalize(self, text: str) -> str:
        text = text.lower().strip()
        text = re.sub(r"[^\w\s-]", " ", text)
        text = re.sub(r"[\s_-]+", " ", text)
        return text.strip()

    def _tokenize(self, text: str) -> set[str]:
        words = re.findall(r"[a-z0-9]+", text.lower())
        tokens: set[str] = set()
        for word in words:
            if word in STOP_WORDS or len(word) < 2:
                continue
            tokens.add(word)
            if len(word) > 5:
                tokens.add(word[:5])
        return tokens

    def _token_overlap(self, keyword_tokens: set[str], page_tokens: set[str]) -> float:
        if not keyword_tokens:
            return 0.0

        matched = 0
        for kw in keyword_tokens:
            if kw in page_tokens:
                matched += 1
                continue
            for pt in page_tokens:
                if kw in pt or pt in kw:
                    matched += 0.85
                    break

        return min(matched / len(keyword_tokens), 1.0)


@lru_cache(maxsize=1)
def get_laravel_route_names() -> frozenset[str]:
    """Alle Laravel route-namen uit web.php en auth.php."""
    config = get_config()
    names: set[str] = set()
    for route_file in (
        config.web_routes_file,
        config.web_routes_file.parent / "auth.php",
    ):
        if route_file.exists():
            names.update(re.findall(r"->name\('([^']+)'\)", read_text(route_file)))
    return frozenset(names)


def get_seo_route_names() -> list[str]:
    return sorted(r for r in get_laravel_route_names() if r.startswith("seo."))


def is_valid_route(name: str) -> bool:
    return name in get_laravel_route_names()


@lru_cache(maxsize=1)
def get_page_registry() -> SEOPageRegistry:
    return SEOPageRegistry()
