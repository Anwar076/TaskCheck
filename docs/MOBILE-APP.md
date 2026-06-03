# TaskCheck mobiele app ↔ Laravel

De Expo-app staat in een **aparte map** (bijv. `c:\laragon\www\taskcheck-app`).  
Deze Laravel-app (`surveycams`) levert de API onder **`/api/mobile`**.

## Geen aparte “API key”

De app gebruikt **geen vaste API key** in `.env`. Flow:

1. `POST /api/mobile/login` met e-mail + wachtwoord (zelfde account als web)
2. Laravel geeft een **Sanctum Bearer token** terug
3. De app stuurt bij elk request: `Authorization: Bearer <token>`

## App configuratie (`taskcheck-app/.env`)

```env
EXPO_PUBLIC_API_URL=https://taskcheck.nl
EXPO_PUBLIC_USE_MOCK=false
```

Lokaal: `http://127.0.0.1:8000` of je Laragon-URL.

## Laravel configuratie (deze map)

**Belangrijk:** er moet een `.env` bestaan (kopie van `.env.example`). Zonder `APP_KEY` krijg je in de app **"Server Error"**.

```bash
copy .env.example .env
php artisan key:generate
php artisan migrate
```

Herstart daarna `php artisan serve` (Ctrl+C en opnieuw starten).

In je **echte** `.env` (niet alleen `.env.example`):

```env
APP_URL=https://taskcheck.nl

CORS_ALLOWED_ORIGINS=http://localhost:8081,http://127.0.0.1:8081,http://localhost:8082,http://127.0.0.1:8082
```

Na deploy of eerste keer lokaal:

```bash
php artisan migrate
php artisan storage:link
```

## Wat er in Laravel zat (na discard opnieuw toegevoegd)

| Onderdeel | Doel |
|-----------|------|
| `routes/api.php` → `Route::prefix('mobile')` | Alle mobile endpoints |
| `app/Http/Controllers/Api/Mobile/*` | Auth, taken, inzendingen, admin |
| `config/cors.php` | Expo web preview (poort 8082) |
| `EnsureMobileAdmin` middleware | Admin-routes in de app |
| `device_push_tokens` + `ExpoPushService` | Push naar telefoon |
| `ProofFileHelper` | Absolute URLs voor bewijs-foto’s |

## Andere keys in `.env` (niet voor de mobiele login)

| Key | Gebruik |
|-----|---------|
| `APP_KEY` | Laravel encryptie (standaard) |
| `OPENAI_API_KEY` | AI-import op web |
| `MOLLIE_API_KEY` | Betalingen |
| `VAPID_*` | Web push in browser (PWA) |

Die hebben **niets** te maken met de Expo-app login.
