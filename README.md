# DeVerkeersborden.nl

Oefen gratis verkeersborden en theorie-examen vragen. De site biedt:

- Verkeersborden oefenen (quiz met resultaten)
- Overzicht van alle verkeersborden
- Theorie-examen oefenpagina
- Inloggen/registreren en abonnementen via Stripe

## Tech stack

- Laravel 12 (PHP 8.2+)
- Vite (frontend assets)
- Stripe (Cashier)

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

4) Start de app (2 terminals)
```
php artisan serve --host=127.0.0.1 --port=8000
```
```
npm run dev
```
Open daarna `http://localhost:8000`.  
Vite draait op `http://localhost:5173` (die pagina mag leeg/zwart zijn).

Zonder dev-server (alleen production assets):
```
npm run build
php artisan serve --host=127.0.0.1 --port=8000
```

## Tests

```
php artisan test
```

## Belangrijke env-variabelen

- `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET`

## Product beschrijving

DeVerkeersborden.nl helpt leerlingen en examenkandidaten om verkeersborden te leren en
hun kennis te testen. Je kunt direct oefenen, je resultaten bekijken en optioneel een
abonnement afsluiten voor extra functionaliteit. De focus ligt op snelle oefening en
duidelijke feedback.
