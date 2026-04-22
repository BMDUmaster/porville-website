# FarmSea Vendor Dashboard

Laravel-based vendor/admin dashboard for FarmSea e-commerce platform.

## Installation

1. Copy `.env.example` to `.env`
2. Run `composer install`
3. Run `php artisan key:generate`
4. Configure database in `.env`
5. Run `php artisan migrate --seed`
6. Run `php artisan serve` or configure with Apache/Nginx

## Features

- Dashboard home with stats & charts
- Product management (Categories, Sub-categories, Products)
- Order management (Live orders, Order history)
- User management
- Notifications system
- Coupons & offers
- Reports (Total orders, Category orders)
- Vendor profile

## Default Login

- Email: `admin@farmsea.in`
- Password: `password`

## Tech Stack

- Laravel 11
- Tailwind CSS (via CDN)
- Chart.js
- Font Awesome 6
