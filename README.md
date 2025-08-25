# PHP Mini API (Invoices/Items)

Demo API in **PHP 8.2** senza framework pesanti (solo PDO + routing minimale).

## Endpoints
- `GET /health` → `{"ok":true}`
- `GET /items` → lista items
- `POST /items` → crea item `{ "title": "..." }`
- `GET /invoices` → lista fatture (mock)

## Requisiti
- PHP >= 8.1 con estensione PDO SQLite
- Composer (per autoload PSR-4 e PHPUnit)

## Setup rapido
```bash
composer install
php -S 127.0.0.1:8080 -t public
# in un altro terminale:
curl http://127.0.0.1:8080/health
```

## Test
```bash
./vendor/bin/phpunit
```

## Struttura
```
public/index.php      # front controller + routing
src/                  # domain + repo
tests/                # unit test
data/app.db           # sqlite db (auto-creato al primo run)
```