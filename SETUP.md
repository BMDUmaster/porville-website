# FarmSea Dashboard - Setup Instructions

## Prerequisites
- PHP 8.2+
- Composer
- MySQL (XAMPP/WAMP)
- Node.js (optional, for npm)

## Step-by-Step Setup

### 1. Install Dependencies
```bash
cd FarmSea-dashboard
composer install
```

### 2. Generate App Key
```bash
php artisan key:generate
```

### 3. Configure Database
Edit `.env` file:
```
DB_DATABASE=farmsea_dashboard
DB_USERNAME=root
DB_PASSWORD=
```

Create the database in phpMyAdmin or MySQL:
```sql
CREATE DATABASE farmsea_dashboard;
```

### 4. Run Migrations & Seed
```bash
php artisan migrate --seed
```

### 5. Create Storage Link
```bash
php artisan storage:link
```

### 6. Run the App
```bash
php artisan serve
```
Or access via XAMPP: `http://localhost/FarmSea-dashboard/public`

## Default Login Credentials
| Role  | Email                  | Password |
|-------|------------------------|----------|
| Admin | admin@farmsea.in       | password |
| Vendor| vendor@farmsea.in      | password |

## Dashboard Pages
| Page              | URL                        |
|-------------------|----------------------------|
| Login             | /login                     |
| Dashboard Home    | /dashboard                 |
| Categories        | /categories                |
| Sub-Categories    | /subcategories             |
| Products          | /products                  |
| Live Orders       | /orders                    |
| Order History     | /orders/history            |
| Total Orders      | /orders/total              |
| Category Orders   | /orders/category           |
| Orders Report     | /orders/report             |
| Users             | /users                     |
| Notifications     | /notifications             |
| Coupons & Offers  | /coupons                   |
| Profile           | /profile                   |
