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
