# Porville

Online fresh-meat / fresh-cut store for **porville.com**: a customer website, an admin dashboard and a small JSON API, all in one Laravel app.

## Tech Stack

- Laravel 11 · PHP 8.2+
- MySQL
- Blade views, Tailwind CSS (CDN), Chart.js, Font Awesome 6 — there is no npm/Vite build step
- Laravel Sanctum (API tokens)
- Razorpay (online payments)
- Gmail SMTP (OTP and order emails)

## Local Setup

```bash
composer install
cp .env.example .env          # then fill in DB_*, MAIL_*, RAZORPAY_*
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
```

The seeder is intentionally empty, so there is no default login. Create the first main admin with tinker:

```bash
php artisan tinker
>>> App\Models\User::create(['name' => 'Admin', 'email' => 'you@example.com', 'password' => 'choose-a-password', 'role' => 'admin']);
```

(The password is hashed automatically by the `User` model.) Sub admins are then created from **Dashboard → Admins**.

## Deploying to Live

1. Upload the code (including `vendor/`, or run `composer install --no-dev` on the server).
2. Create/update `.env` on the server. On live, always use:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://porville.com
   ```
3. Run the setup command. It migrates, links storage, clears caches and checks permissions and GD:
   ```bash
   php artisan porville:setup
   ```
4. Make sure `storage/` and `bootstrap/cache/` are writable (`chmod -R 775 storage bootstrap/cache`).
5. Add the cron job (see below).

**Document root:** point it at `public/` if the host allows. If it can only point at the project folder, the root [.htaccess](.htaccess) already rewrites every request into `public/`.

**Subfolder install** (e.g. `example.com/porville`): set `APP_URL=https://example.com/porville` and `APP_SUBDIRECTORY=porville` in `.env`, and uncomment `RewriteBase /porville/` in [public/.htaccess](public/.htaccess).

**Shared hosting without symlinks:** if `public/storage` can't be created, the `/storage/{path}` route in [routes/web.php](routes/web.php) serves uploaded files from `storage/app/public` instead.

### Cron job

One scheduled task, `product-slots:notify`, emails customers when a product's ordering slot opens. It runs every minute:

```
* * * * * cd /path/to/porville && php artisan schedule:run >> /dev/null 2>&1
```

Web requests also trigger this check, so the site still works without cron, but alerts can go out late on quiet hours.

## Environment Variables

| Variable | What it does |
|---|---|
| `APP_URL`, `APP_SUBDIRECTORY` | Site URL; subdirectory only for a subfolder install |
| `DB_*` | MySQL connection |
| `MAIL_*` | Gmail SMTP. `MAIL_PASSWORD` must be a Google **App Password**, not the normal Gmail password |
| `RAZORPAY_KEY_ID`, `RAZORPAY_KEY_SECRET` | Razorpay keys (test keys locally, live keys on production) |
| `IMAGE_FORMAT` | Uploaded image conversion: `auto` (default), `avif`, `webp` or `original` |
| `IMAGE_QUALITY` | Conversion quality 1–100 (default 85) |
| `DELIVERY_TIMEZONE` | Defaults to `Asia/Kolkata` |
| `DELIVERY_EVENING_SLOT_START`, `DELIVERY_LAST_SLOT_END` | Fallback evening delivery window (defaults `16:00`–`20:00`) |

Delivery slots, service-charge tiers, delivery areas and ordering days are managed from the dashboard and stored in the database. Fallback defaults are in [config/delivery.php](config/delivery.php) and [config/order_pricing.php](config/order_pricing.php).

## Project Structure

| Path | Contents |
|---|---|
| [app/Http/Controllers/Dashboard/](app/Http/Controllers/Dashboard/) | Admin panel |
| [app/Http/Controllers/Frontend/](app/Http/Controllers/Frontend/) | Customer website (shop, cart, checkout, Razorpay, account) |
| [app/Http/Controllers/Api/](app/Http/Controllers/Api/) | JSON API |
| [app/Support/](app/Support/) | Business logic: pricing (`OrderPricing`, `ProductDayPricing`), delivery charges, slots and areas, service charge, product slots, image conversion (`WebpImage`), mail (`PorvilleMail`), admin permissions (`AdminModules`) |
| [resources/views/dashboard/](resources/views/dashboard/) | Admin Blade views |
| [resources/views/frontend/](resources/views/frontend/) | Customer Blade views |
| [routes/web.php](routes/web.php) | Website and dashboard routes |
| [routes/api.php](routes/api.php) | API routes |
| [routes/console.php](routes/console.php) | `porville:setup`, `product-slots:notify` and the schedule |

## Admin Panel

Login at `/login` (forgot password sends an OTP by email).

There are two staff roles:
- **admin**: the main admin. Can do everything and is the only role that can manage other admins.
- **sub_admin**: can only open the modules the main admin assigns to them.

Modules are defined in [app/Support/AdminModules.php](app/Support/AdminModules.php):

Dashboard · Product Management (categories, subcategories, products) · Order Management (orders, history, reports) · Website Banners · Contact Us messages · Reviews · Customers · Delivery Boys · Notifications · Offers & Coupons · Delivery Slots · Product Slots · Delivery Areas · Service Charge · Website FAQ · SEO Management (pages + category SEO content)

## Customer Website

| Page | URL |
|---|---|
| Home | `/` |
| Shop / product | `/shop`, `/shop/{slug}` |
| Category listing | `/{category}/{subcategory?}` |
| Cart / Checkout | `/cart`, `/checkout` |
| Login / Register (email OTP) | `/account/login`, `/account/register` |
| My orders | `/account/orders` |
| Track order | `/track-order` |
| Static pages | `/about-us`, `/our-farms`, `/contact-us`, `/faq`, policy pages |
| SEO | `/sitemap.xml`, `/robots.txt` |

**Payments:** checkout creates the order, then `/checkout/pay/{order}` opens Razorpay. Razorpay posts back to `/checkout/razorpay/callback`, which is CSRF-exempt, and the payment is recorded in the `razorpay_payments` table.

## API

Base URL: `https://porville.com/api`

| Method | Endpoint | Auth |
|---|---|---|
| POST | `/register`, `/login` | — |
| GET | `/products`, `/products/{id}`, `/products/slug/{slug}` | — |
| GET | `/categories`, `/categories/{id}`, `/subcategories` | — |
| POST | `/coupons/validate` | — |
| POST | `/logout` · GET `/me` | Sanctum token |
| GET / POST | `/orders`, GET `/orders/{id}` | Sanctum token |

Send the token from `/login` as `Authorization: Bearer <token>`.

## Diagnostics and Utility URLs

| URL | Purpose | Login |
|---|---|---|
| `/dashboard/system-check` | Full system check (PHP, extensions, storage, DB) | Admin |
| `/server-check` | Server diagnostics | **Public** |
| `/server-check.php` | Plain-PHP check that works even if Laravel won't boot | **Public** |
| `/clear-cache` | Clears route, view and app cache | **Public** |

## Known Issues / Handover Notes

- `.env` and the SQL dump `aksharac_porville.sql` are committed to git. Remove them from the repo (`git rm --cached`), add them to `.gitignore`, and rotate the Gmail app password and Razorpay secret, because both are in git history.
- `/server-check`, `/server-check.php` and `/clear-cache` have no login. Protect or remove them once the site is stable.
- `QUEUE_CONNECTION=sync`, so emails are sent during the web request. A slow SMTP connection makes checkout/OTP pages slow too.
- The `origin` remote still points to the old `farmsea.in-website` repo. The Porville repo is the `porville` remote (`BMDUmaster/porville-website`).
