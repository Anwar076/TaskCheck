from google.oauth2 import service_account
from googleapiclient.discovery import build

SCOPES = ['https://www.googleapis.com/auth/webmasters.readonly']

credentials = service_account.Credentials.from_service_account_file(
    'credentials.json',
    scopes=SCOPES
)

service = build('searchconsole', 'v1', credentials=credentials)

request = {
    'startDate': '2026-05-25',
    'endDate': '2026-06-24',
    'dimensions': ['query'],
    'rowLimit': 20
}

response = service.searchanalytics().query(
    siteUrl='sc-domain:taskcheck.nl',
    body=request
).execute()

for row in response.get('rows', []):
    print(
        f"Keyword: {row['keys'][0]}"
        f" | Clicks: {row['clicks']}"
        f" | Impressions: {row['impressions']}"
        f" | Position: {round(row['position'],1)}"
    )