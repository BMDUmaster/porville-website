<?php
/**
 * FarmSea API Integration Helper
 * ================================
 * Drop this file into your "farmsea.in-website-vishal" PHP project.
 * Usage: require_once 'farmsea-api.php';
 *
 * Base URL: http://127.0.0.1:8000/api
 * (Change to your production domain when deploying)
 */

define('FARMSEA_API_BASE', 'http://127.0.0.1:8000/api');

/**
 * Core cURL helper
 */
function farmsea_request(string $method, string $endpoint, array $data = [], string $token = ''): array
{
    $url = FARMSEA_API_BASE . $endpoint;
    $ch  = curl_init();

    $headers = ['Content-Type: application/json', 'Accept: application/json'];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }

    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    } elseif ($method === 'GET' && !empty($data)) {
        curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($data));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $decoded = json_decode($response, true) ?? [];
    $decoded['_http_code'] = $httpCode;
    return $decoded;
}

// ─────────────────────────────────────────────────────────────
// AUTH
// ─────────────────────────────────────────────────────────────

/**
 * Register a new user
 * Returns: ['success', 'token', 'user']
 */
function farmsea_register(string $name, string $email, string $password, string $phone = ''): array
{
    return farmsea_request('POST', '/register', [
        'name'                  => $name,
        'email'                 => $email,
        'password'              => $password,
        'password_confirmation' => $password,
        'phone'                 => $phone,
    ]);
}

/**
 * Login user
 * Returns: ['success', 'token', 'user']
 */
function farmsea_login(string $email, string $password): array
{
    return farmsea_request('POST', '/login', [
        'email'    => $email,
        'password' => $password,
    ]);
}

/**
 * Logout (invalidates token)
 */
function farmsea_logout(string $token): array
{
    return farmsea_request('POST', '/logout', [], $token);
}

/**
 * Get current user profile
 */
function farmsea_me(string $token): array
{
    return farmsea_request('GET', '/me', [], $token);
}

// ─────────────────────────────────────────────────────────────
// PRODUCTS
// ─────────────────────────────────────────────────────────────

/**
 * Get all products (with optional filters)
 * $filters: ['category_id' => 1, 'search' => 'fish', 'per_page' => 20]
 */
function farmsea_get_products(array $filters = []): array
{
    return farmsea_request('GET', '/products', $filters);
}

/**
 * Get single product by ID
 */
function farmsea_get_product(int $id): array
{
    return farmsea_request('GET', '/products/' . $id);
}

/**
 * Get product by slug
 */
function farmsea_get_product_by_slug(string $slug): array
{
    return farmsea_request('GET', '/products/slug/' . $slug);
}

// ─────────────────────────────────────────────────────────────
// CATEGORIES
// ─────────────────────────────────────────────────────────────

/**
 * Get all categories (with subcategories nested)
 */
function farmsea_get_categories(): array
{
    return farmsea_request('GET', '/categories');
}

/**
 * Get all subcategories
 */
function farmsea_get_subcategories(): array
{
    return farmsea_request('GET', '/subcategories');
}

// ─────────────────────────────────────────────────────────────
// ORDERS
// ─────────────────────────────────────────────────────────────

/**
 * Place a new order (requires token)
 *
 * $items = [
 *   ['product_id' => 1, 'quantity' => 2, 'variant_index' => 0],
 * ]
 * $address = [
 *   'name' => '...', 'phone' => '...', 'address' => '...', 'city' => '...', 'pincode' => '...'
 * ]
 */
function farmsea_place_order(string $token, array $items, array $address, string $paymentMethod = 'COD', string $couponCode = ''): array
{
    $payload = [
        'items'            => $items,
        'shipping_address' => $address,
        'payment_method'   => $paymentMethod,
    ];
    if ($couponCode) {
        $payload['coupon_code'] = $couponCode;
    }
    return farmsea_request('POST', '/orders', $payload, $token);
}

/**
 * Get user's order history (requires token)
 */
function farmsea_get_orders(string $token): array
{
    return farmsea_request('GET', '/orders', [], $token);
}

/**
 * Get single order details (requires token)
 */
function farmsea_get_order(string $token, int $orderId): array
{
    return farmsea_request('GET', '/orders/' . $orderId, [], $token);
}

// ─────────────────────────────────────────────────────────────
// COUPONS
// ─────────────────────────────────────────────────────────────

/**
 * Validate a coupon code
 */
function farmsea_validate_coupon(string $code, float $orderAmount): array
{
    return farmsea_request('POST', '/coupons/validate', [
        'code'         => $code,
        'order_amount' => $orderAmount,
    ]);
}

// ─────────────────────────────────────────────────────────────
// SESSION TOKEN HELPERS
// ─────────────────────────────────────────────────────────────

/**
 * Save token to PHP session after login
 */
function farmsea_save_token(string $token, array $user): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['farmsea_token'] = $token;
    $_SESSION['farmsea_user']  = $user;
}

/**
 * Get saved token from session
 */
function farmsea_get_token(): string
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    return $_SESSION['farmsea_token'] ?? '';
}

/**
 * Check if user is logged in
 */
function farmsea_is_logged_in(): bool
{
    return !empty(farmsea_get_token());
}

/**
 * Clear session on logout
 */
function farmsea_clear_session(): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    unset($_SESSION['farmsea_token'], $_SESSION['farmsea_user']);
}
