import requests
from dotenv import load_dotenv
import os

load_dotenv()

token = os.getenv("TELEGRAM_TOKEN")
chat_id = os.getenv("CHAT_ID")

message = """
🚀 TaskCheck SEO Agent gestart
"""

requests.post(
    f"https://api.telegram.org/bot{token}/sendMessage",
    json={
        "chat_id": chat_id,
        "text": message
    }
)

print("Verstuurd")