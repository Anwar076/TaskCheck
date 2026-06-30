"""Concurrentieanalyse via web scraping."""

from __future__ import annotations

import re
from typing import Any
from urllib.parse import parse_qs, unquote, urlparse

import requests
from bs4 import BeautifulSoup

from app.utils.config import get_config
from app.utils.files import load_lines
from app.utils.logger import setup_logger

logger = setup_logger(__name__)

HEADERS = {
    "User-Agent": "Mozilla/5.0 (compatible; TaskCheckSEOAgent/1.0; +https://taskcheck.nl)",
    "Accept-Language": "nl-NL,nl;q=0.9",
}

OWN_DOMAINS = ("taskcheck.nl", "localhost")


class CompetitorAnalyzer:
    def __init__(self) -> None:
        self.config = get_config()
        raw = load_lines(self.config.competitors_path)
        self.competitors = list(dict.fromkeys(raw))

    def analyze_url(self, url: str, timeout: int = 12) -> dict[str, Any] | None:
        """Analyseer een concurrentenpagina. Retourneert None bij fout."""
        try:
            response = requests.get(url, headers=HEADERS, timeout=timeout)
            response.raise_for_status()
        except requests.RequestException as exc:
            logger.debug("Concurrent pagina niet bereikbaar (%s): %s", url, exc)
            return None

        soup = BeautifulSoup(response.text, "html.parser")

        title = soup.title.string.strip() if soup.title and soup.title.string else ""
        meta_desc = ""
        meta_tag = soup.find("meta", attrs={"name": "description"})
        if meta_tag:
            meta_desc = meta_tag.get("content", "")

        h1s = [h.get_text(strip=True) for h in soup.find_all("h1")]
        h2s = [h.get_text(strip=True) for h in soup.find_all("h2")]

        body_text = soup.get_text(separator=" ", strip=True)
        word_count = len(re.findall(r"\w+", body_text))

        faq_items = self._extract_faq(soup)
        structured_data = self._extract_structured_data(response.text)
        internal_links = self._extract_internal_links(soup, url)
        images = [
            {"src": img.get("src", ""), "alt": img.get("alt", "")}
            for img in soup.find_all("img")
            if img.get("src")
        ][:10]

        return {
            "url": url,
            "domain": urlparse(url).netloc,
            "title": title,
            "meta_description": meta_desc,
            "h1s": h1s,
            "h2s": h2s[:15],
            "word_count": word_count,
            "faq_count": len(faq_items),
            "faq_items": faq_items[:8],
            "structured_data_types": structured_data,
            "internal_link_count": len(internal_links),
            "internal_links": internal_links[:10],
            "image_count": len(images),
            "images": images,
        }

    def analyze_keyword(self, keyword: str, max_results: int | None = None) -> list[dict[str, Any]]:
        """Analyseer concurrenten voor een zoekwoord."""
        configured_limit = self.config.competitor_max_results
        limit = max_results if max_results is not None else configured_limit
        if limit <= 0:
            limit = len(self.competitors) if self.competitors else 10

        urls: list[str] = []

        for competitor in self.competitors:
            domain = competitor.strip().replace("https://", "").replace("http://", "").rstrip("/")
            if domain:
                urls.append(f"https://{domain}")
        urls = list(dict.fromkeys(urls))

        if not urls:
            urls = self._find_serp_urls(keyword, max_results=limit)

        results: list[dict[str, Any]] = []
        for url in urls[:limit]:
            analysis = self.analyze_url(url)
            if analysis:
                analysis["keyword"] = keyword
                results.append(analysis)

        if not results:
            logger.info(
                "Geen concurrentie-data voor '%s' (configureer config/competitors.txt of controleer netwerk)",
                keyword,
            )

        return results

    def compare_with_taskcheck(
        self,
        keyword: str,
        taskcheck_page_path: str | None = None,
    ) -> dict[str, Any]:
        """Vergelijk TaskCheck met concurrenten."""
        from app.utils.files import read_text

        competitor_data = self.analyze_keyword(keyword)

        taskcheck = {"word_count": 0, "h2_count": 0, "faq_count": 0, "has_structured_data": False}
        if taskcheck_page_path:
            page_path = self.config.seo_views_dir / taskcheck_page_path
            if page_path.exists():
                content = read_text(page_path)
                taskcheck["word_count"] = len(re.findall(r"\w+", content))
                taskcheck["h2_count"] = len(re.findall(r"<h2", content, re.I))
                taskcheck["faq_count"] = content.count("Veelgestelde vragen") + content.count("$faqItems")
                taskcheck["has_structured_data"] = "FAQPage" in content or "application/ld+json" in content

        gaps = []
        if competitor_data:
            avg_words = sum(c.get("word_count", 0) for c in competitor_data) / len(competitor_data)
            avg_faq = sum(c.get("faq_count", 0) for c in competitor_data) / len(competitor_data)
            avg_h2 = sum(len(c.get("h2s", [])) for c in competitor_data) / len(competitor_data)

            if taskcheck_page_path and taskcheck["word_count"] < avg_words * 0.8:
                gaps.append(
                    f"Meer content nodig (wij: {taskcheck['word_count']} woorden, concurrent gem.: {int(avg_words)})"
                )
            if taskcheck_page_path and taskcheck["faq_count"] < avg_faq:
                gaps.append(f"FAQ uitbreiden (wij: {taskcheck['faq_count']}, concurrent gem.: {int(avg_faq)})")
            if taskcheck_page_path and taskcheck["h2_count"] < avg_h2:
                gaps.append(f"Meer H2-secties (wij: {taskcheck['h2_count']}, concurrent gem.: {int(avg_h2)})")
            if taskcheck_page_path and not taskcheck["has_structured_data"]:
                gaps.append("Structured data (FAQPage) ontbreekt")

            if not taskcheck_page_path:
                top = competitor_data[0]
                gaps.append(
                    f"Referentie: {top.get('domain')} — {top.get('word_count', 0)} woorden, "
                    f"{len(top.get('h2s', []))} H2's, {top.get('faq_count', 0)} FAQ's"
                )

        return {
            "keyword": keyword,
            "taskcheck": taskcheck,
            "competitors": competitor_data,
            "content_gaps": gaps,
            "recommendations": gaps,
        }

    def _find_serp_urls(self, keyword: str, max_results: int = 3) -> list[str]:
        """Zoek relevante concurrent-URL's via DuckDuckGo (geen API key nodig)."""
        query = f"{keyword} horeca HACCP"
        try:
            response = requests.post(
                "https://html.duckduckgo.com/html/",
                data={"q": query, "kl": "nl-nl"},
                headers=HEADERS,
                timeout=12,
            )
            response.raise_for_status()
        except requests.RequestException as exc:
            logger.debug("SERP-zoekopdracht mislukt voor '%s': %s", keyword, exc)
            return []

        soup = BeautifulSoup(response.text, "html.parser")
        urls: list[str] = []
        seen_domains: set[str] = set()

        for link in soup.select("a.result__a"):
            href = link.get("href", "")
            url = self._normalize_ddg_url(href)
            if not url:
                continue

            domain = urlparse(url).netloc.lower().removeprefix("www.")
            if any(own in domain for own in OWN_DOMAINS):
                continue
            if domain in seen_domains:
                continue

            seen_domains.add(domain)
            urls.append(url)
            if len(urls) >= max_results:
                break

        return urls

    def _normalize_ddg_url(self, href: str) -> str | None:
        if not href:
            return None
        if href.startswith("//duckduckgo.com/l/?"):
            href = "https:" + href
        if "duckduckgo.com/l/" in href:
            parsed = urlparse(href)
            params = parse_qs(parsed.query)
            uddg = params.get("uddg", [None])[0]
            if uddg:
                return unquote(uddg)
        if href.startswith("http"):
            return href
        return None

    def _extract_faq(self, soup: BeautifulSoup) -> list[dict[str, str]]:
        faqs = []
        for details in soup.find_all("details"):
            summary = details.find("summary")
            if summary:
                answer = details.get_text(strip=True).replace(summary.get_text(strip=True), "", 1).strip()
                faqs.append({"question": summary.get_text(strip=True), "answer": answer[:500]})
        return faqs

    def _extract_structured_data(self, html: str) -> list[str]:
        types = []
        for match in re.finditer(r'"@type"\s*:\s*"([^"]+)"', html):
            types.append(match.group(1))
        return list(set(types))

    def _extract_internal_links(self, soup: BeautifulSoup, base_url: str) -> list[str]:
        domain = urlparse(base_url).netloc
        links = []
        for a in soup.find_all("a", href=True):
            href = a["href"]
            if href.startswith("/") or domain in href:
                links.append(href)
        return links
