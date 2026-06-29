from openai import OpenAI
from dotenv import load_dotenv
import requests
import os

load_dotenv()

client = OpenAI(
    api_key=os.getenv("OPENAI_API_KEY")
)

response = client.responses.create(
    model="gpt-5",
    input="""
Je bent een SEO specialist voor TaskCheck.

Bedenk 5 SEO zoekwoorden voor:
- HACCP
- Horeca
- Digitale checklists
- Schoonmaakcontrole

Geef alleen de zoekwoorden.
"""
)

tekst = response.output_text

requests.post(
    f"https://api.telegram.org/bot{os.getenv('TELEGRAM_TOKEN')}/sendMessage",
    json={
        "chat_id": os.getenv("CHAT_ID"),
        "text": f"🤖 SEO Zoekwoorden\n\n{tekst}"
    }
)

print(tekst)