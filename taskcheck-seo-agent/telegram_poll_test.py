"""Test Telegram polling + netwerk vanaf deze server."""

from __future__ import annotations

import os
import sys

import requests
from dotenv import load_dotenv

load_dotenv()

token = os.getenv("TELEGRAM_TOKEN", "")
if not token:
    print("TELEGRAM_TOKEN ontbreekt in .env")
    sys.exit(1)

base = f"https://api.telegram.org/bot{token}"

print("1. getMe...")
r = requests.get(f"{base}/getMe", timeout=20)
print(r.status_code, r.json())

print("\n2. getUpdates (polling test)...")
r = requests.get(f"{base}/getUpdates", params={"limit": 1, "timeout": 5}, timeout=25)
print(r.status_code, r.text[:500])

if r.status_code == 409:
    print(
        "\n❌ Conflict: bot draait al ergens anders (lokaal Windows of andere server). "
        "Stop die instantie eerst."
    )
elif r.status_code != 200:
    print("\n❌ Telegram API niet bereikbaar of token ongeldig.")
else:
    print("\n✅ Telegram polling werkt op deze server.")
