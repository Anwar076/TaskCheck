"""Legacy wrapper — gebruik app.agent.SEOAgent."""

import sys
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parent))

from app.agent import SEOAgent

if __name__ == "__main__":
    agent = SEOAgent()
    result = agent.run_analysis_only()
    decision = result["decision"]
    keyword = decision.get("keyword", "")
    if keyword:
        with open("selected_keyword.txt", "w", encoding="utf-8") as f:
            f.write(keyword)
        print(f"Keyword opgeslagen: {keyword}")
