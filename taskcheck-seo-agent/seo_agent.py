"""Legacy wrapper — gebruik run.py voor de nieuwe agent."""

import sys
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parent))

from app.agent import SEOAgent

if __name__ == "__main__":
    agent = SEOAgent()
    agent.run_full_pipeline()
