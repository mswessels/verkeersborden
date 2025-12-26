# DeVerkeersborden.nl

Oefen gratis verkeersborden en theorie-examen vragen. De site biedt:

- Verkeersborden oefenen (quiz met resultaten)
- Overzicht van alle verkeersborden
- Theorie-examen oefenpagina
- Inloggen/registreren en abonnementen via Stripe

## Tech stack

- Laravel 11 (PHP 8.2+)
- Vite (frontend assets)
- Stripe (Cashier)
- Social login via Socialite

## Lokaal draaien

1) Maak een `.env` en genereer een key
```
cp .env.example .env
php artisan key:generate
```

2) Gebruik SQLite voor snel lokaal testen
```
touch database/database.sqlite
```
Zet in `.env`:
```
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

3) Installeer dependencies en draai migraties
```
composer install
php artisan migrate
npm install
```

4) Start de app
```
php artisan serve
npm run dev
```
Open daarna `http://localhost:8000`.

## Tests

```
php artisan test
```

## Belangrijke env-variabelen

- `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET`
- `FACEBOOK_CLIENT_ID`, `FACEBOOK_CLIENT_SECRET`, `FACEBOOK_REDIRECT_URI`
- `TWITTER_CLIENT_ID`, `TWITTER_CLIENT_SECRET`, `TWITTER_REDIRECT_URI`

## Product beschrijving

DeVerkeersborden.nl helpt leerlingen en examenkandidaten om verkeersborden te leren en
hun kennis te testen. Je kunt direct oefenen, je resultaten bekijken en optioneel een
abonnement afsluiten voor extra functionaliteit. De focus ligt op snelle oefening en
duidelijke feedback.
