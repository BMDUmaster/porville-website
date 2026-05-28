# FarmSea Dashboard

Laravel-based admin dashboard + customer frontend for FarmSea e-commerce platform.

## Tech Stack
- Laravel 11 · PHP 8.2+
- MySQL (XAMPP)
- Tailwind CSS (CDN)
- Chart.js · Font Awesome 6
- Laravel Sanctum (API auth)

## Quick Start

```bash
composer install
php artisan key:generate
# Configure .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

## Live / Production Checklist

### Subfolder install (e.g. `https://bmdublog.com/farmsea/`)

1. In `.env`:
   ```
   APP_URL=https://bmdublog.com/farmsea
   APP_SUBDIRECTORY=farmsea
   ```
2. In `public/.htaccess`, set `RewriteBase /farmsea/` (already set in repo).
3. Document root must point to Laravel **`public`** folder.
4. System check URL: **`/farmsea/dashboard/system-check`** or **`/farmsea/system-check`**
5. Quick PHP check (no Laravel): **`/farmsea/server-check.php`**

### All servers

1. Run `php artisan farmsea:setup` on the server.
2. Ensure `public/images/Farmsea.webp` exists and `storage` is writable (`chmod -R 775 storage bootstrap/cache`).
3. PHP extension `gd` recommended for image uploads.
4. `APP_DEBUG=false` on production.

## Default Credentials

| Role  | Email              | Password |
|-------|--------------------|----------|
| Admin | admin@farmsea.in   | password |

## URLs

| Area            | URL                          |
|-----------------|------------------------------|
| Admin Login     | /login                       |
| Admin Dashboard | /dashboard                   |
| User Home       | /home                        |
| Shop            | /shop                        |
| User Login      | /account/login               |
| User Register   | /account/register            |
| Cart            | /cart                        |
| Checkout        | /checkout                    |
| Track Order     | /track-order                 |

## API Base URL
`http://127.0.0.1:8000/api`

Key endpoints: `POST /api/login`, `POST /api/register`, `GET /api/products`, `POST /api/orders`
