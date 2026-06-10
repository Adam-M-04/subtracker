# SubTracker - projekt zaliczeniowy

## 1. Temat projektu

Aplikacja webowa do zarzadzania subskrypcjami (`SubTracker`) z podzialem na role uzytkownik/admin.

## 2. Technologie

- Docker, Docker Compose
- Git
- HTML5, CSS3, JavaScript (Fetch API)
- PHP 8.5 (obiektowo, bez frameworka)
- PostgreSQL 16

## 3. Architektura

Projekt jest oparty o wzorzec **MVC** z wydzielona warstwa bezpieczenstwa i serwisow.

```
┌─────────────────────────────────────────────────────┐
│                   REQUEST (HTTP)                     │
└──────────────────────┬────────────────────────────────┘
                       │
                       ▼
        ┌──────────────────────────────┐
        │   public/index.php           │
        │ (Front Controller + Router)   │
        │ - Error/Exception handlers   │
        │ - Session init               │
        └───────────┬──────────────────┘
                    │
                    ▼
        ┌──────────────────────────────┐
        │   src/Core/Router.php        │
        │ (dispatch do kontrolera)      │
        └───────────┬──────────────────┘
                    │
         ┌──────────┴──────────┐
         │                     │
         ▼                     ▼
    ┌───────────────┐    ┌────────────────────┐
    │  Controller   │    │ Middleware/Core    │
    │               │    │ - Auth.php         │
    │ - Auth        │    │ - Csrf.php         │
    │ - Home        │    │ - LoginThrottle.php│
    │ - Settings    │    │ - SecurityLogger.php
    │ - Subscr.     │    │ - Controller.php   │
    │ - User        │    │ - JsonResponse.php │
    │ - Error       │    │                    │
    └───────┬───────┘    └────────────────────┘
            │
            │ (pobiera dane poprzez)
            ▼
    ┌──────────────────────────┐
    │  Repositories            │
    │                          │
    │ - UserRepository         │
    │ - SubscriptionRepository │
    │                          │
    │ (prepared statements)    │
    │ (transakcje)             │
    └────────┬─────────────────┘
             │
             ▼
    ┌──────────────────────────┐
    │   PostgreSQL Database    │
    │                          │
    │ - tables (users, subs)   │
    │ - views (summary etc.)   │
    │ - functions (counters)   │
    │ - triggers (on update)   │
    └──────────────────────────┘

    ┌──────────────────────────┐
    │     Front-end (JS)       │
    │                          │
    │ - Fetch API              │
    │ - CSRF header injection  │
    │ - Modal management       │
    │ - Form handling          │
    └──────────────────────────┘
         ^
         │ (render HTML views)
         │
    ┌────┴───────────────────────┐
    │   src/Views/               │
    │                            │
    │ - layout.php (template)    │
    │ - login/register (auth)    │
    │ - dashboard/subscriptions  │
    │ - settings/users           │
    │ - 400/401/403/404/500      │
    │ - partials/...             │
    └────────────────────────────┘
```

**Warstwa bezpieczeنstwa (Core):**
- `Auth.php` - sesje, role, regeneracja ID
- `Csrf.php` - generowanie/walidacja tokenow CSRF
- `LoginThrottle.php` - rate limiting
- `SecurityLogger.php` - audyt nieudanych logowan
- `Controller.php` - base class, CSRF validation helper

**E2E Flow przykład (zalogowanie):**
1. User widzi form `/login` z CSRF tokenem
2. POST `/login` z email + password + CSRF token
3. `AuthController::login()` waliduje CSRF, email format, throttle
4. `UserRepository::findByEmail()` wyszukuje - prepared statement
5. `password_verify()` porównuje hasła
6. `Auth::login()` regeneruje sesję, ustawia cookie (HttpOnly + SameSite)
7. Redirect -> `/` (dashboard)

## 4. Funkcjonalnosci aplikacji

- rejestracja i logowanie
- utrzymanie sesji i wylogowanie
- role i uprawnienia (admin/user)
- zarzadzanie uzytkownikami (dla admina)
- CRUD subskrypcji
- filtrowanie/listowanie subskrypcji

## 5. Bezpieczenstwo

Zaadresowane elementy:

- hashowanie hasel (`password_hash`, `password_verify`)
- prepared statements (PDO)
- CSRF tokeny dla formularzy i endpointow JSON
- sesja z flagami cookie (`HttpOnly`, `SameSite=Lax`, opcjonalnie `Secure`)
- `session_regenerate_id(true)` po zalogowaniu
- audyt nieudanych logowan do `storage/logs/security.log`
- globalna obsluga bledow i stron: 400/401/403/404/500
- brak pokazywania surowych bledow w trybie produkcyjnym (`APP_DEBUG=0`)

## 6. Baza danych

Skrypty:

- schemat: `docker/db/init/01_schema.sql`
- dane testowe: `docker/db/init/02_dummy_data.sql`

Uwzglednione wymagania:

- relacje 1:1 (`users` -> `user_profiles`)
- relacje 1:N (`users` -> `subscriptions`)
- relacje N:M (`subscriptions` <-> `tags` przez `subscription_tags`)
- 2 widoki:
  - `vw_subscription_details`
  - `vw_user_subscription_summary`
- 1 funkcja: `get_user_active_subscription_count(p_user_id)`
- 1 trigger: `trg_subscriptions_updated_at`
- transakcje z poziomem izolacji (`READ COMMITTED`)
- relacje PK/FK i zapytania z `JOIN`

Diagram ERD:

![Screenshot 2026-06-10 at 19.31.42.png](readme/Screenshot%202026-06-10%20at%2019.31.42.png)

## 7. Uruchomienie

### 7.1 Konfiguracja zmiennych srodowiskowych

Skopiuj plik:

```bash
cp .env.example .env
```

### 7.2 Start kontenerow

```bash
docker-compose up --build
```

Aplikacja jest mapowana na:

- `http://localhost:8080`

## 8. Testy

### 8.1 Testy jednostkowe

Dodane pliki:

- `composer.json`
- `phpunit.xml`
- `tests/Unit/CurrencyConverterTest.php`

Uruchomienie (jesli masz Composer lokalnie):

```bash
composer install
composer test
```

### 8.2 Test integracyjny endpointow (smoke)

Dodany skrypt:

- `scripts/integration_endpoints.sh`

Uruchomienie:

```bash
BASE_URL=http://localhost:8080 ./scripts/integration_endpoints.sh
```

## 9. PHP Security BINGO

![Screenshot 2026-06-10 at 19.16.27.png](readme/Screenshot%202026-06-10%20at%2019.16.27.png)

## 10. Scenariusz testowy (krok po kroku)

1. Wejdz na `/register` i utworz konto.
2. Zaloguj sie przez `/login`.
3. Sprawdz dostep do `/subscriptions`.
4. Dodaj subskrypcje, edytuj ja, zmien status, usun (przenies do historii).
5. Zaloguj sie jako admin i wejdz na `/users`.
6. Zmien role innego uzytkownika i przetestuj usuwanie konta.
7. Wejdz na nieistniejacy adres i potwierdz strone `404`.
8. Wyslij request bez CSRF do endpointu POST i potwierdz `403`.

## 11. Screeny

### Web

![Screenshot 2026-06-10 at 18.40.50.png](readme/Screenshot%202026-06-10%20at%2018.40.50.png)
![Screenshot 2026-06-10 at 18.40.56.png](readme/Screenshot%202026-06-10%20at%2018.40.56.png)
![Screenshot 2026-06-10 at 18.43.17.png](readme/Screenshot%202026-06-10%20at%2018.43.17.png)
![Screenshot 2026-06-10 at 18.43.54.png](readme/Screenshot%202026-06-10%20at%2018.43.54.png)
![Screenshot 2026-06-10 at 18.43.27.png](readme/Screenshot%202026-06-10%20at%2018.43.27.png)
![Screenshot 2026-06-10 at 18.43.59.png](readme/Screenshot%202026-06-10%20at%2018.43.59.png)
![Screenshot 2026-06-10 at 18.44.20.png](readme/Screenshot%202026-06-10%20at%2018.44.20.png)

### Mobile

![Screenshot 2026-06-10 at 18.44.52.png](readme/Screenshot%202026-06-10%20at%2018.44.52.png)
![Screenshot 2026-06-10 at 18.45.01.png](readme/Screenshot%202026-06-10%20at%2018.45.01.png)
![Screenshot 2026-06-10 at 18.45.50.png](readme/Screenshot%202026-06-10%20at%2018.45.50.png)
![Screenshot 2026-06-10 at 18.45.55.png](readme/Screenshot%202026-06-10%20at%2018.45.55.png)
![Screenshot 2026-06-10 at 18.46.15.png](readme/Screenshot%202026-06-10%20at%2018.46.15.png)
![Screenshot 2026-06-10 at 18.46.23.png](readme/Screenshot%202026-06-10%20at%2018.46.23.png)

## 12. Checklista wymagan

### Technologie i architektura

- [x] Docker
- [x] Git
- [x] HTML5/CSS/JavaScript (Fetch API)
- [x] PHP obiektowy
- [x] PostgreSQL
- [x] MVC
- [x] Bez frameworka i bez gotowych szablonow

### Elementy aplikacji

- [x] Logowanie
- [x] Rejestracja
- [x] Utrzymanie sesji
- [x] Role i uprawnienia
- [x] Zarzadzanie uzytkownikami
- [x] Wylogowanie
- [x] Funkcjonalnosc domenowa (zarzadzanie subskrypcjami)

### Baza danych

- [x] Relacje 1:1
- [x] Relacje 1:N
- [x] Relacje N:M
- [x] Co najmniej 2 widoki
- [x] Co najmniej 1 trigger
- [x] Co najmniej 1 funkcja
- [x] Transakcje na poziomie izolacji
- [x] PK/FK + JOIN
- [x] SQL schema + dane testowe w repo

### Bezpieczenstwo i bledy

- [x] Hasla hashowane
- [x] Ochrona CSRF
- [x] Ograniczenie prob logowania
- [x] Audyt nieudanych logowan
- [x] Obsluga stron 400/401/403/404/500

### Testy

- [x] Test(y) PHPUnit (symboliczne)
- [x] Test integracyjny endpointow (skrypt bash/curl)

## 13. Informacje koncowe

Projekt jest rozwijany commitami Git i przygotowany do publikacji/udostepnienia prowadzacemu.

Dane autora:

- Imie i nazwisko: `Adam Mąka`
- Grupa: `2`
- Rok akademicki: `2025/26`
