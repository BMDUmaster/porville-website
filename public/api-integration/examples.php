<?php
/**
 * FarmSea API - Usage Examples
 * ==============================
 * Copy these examples into your farmsea.in-website-vishal PHP project.
 */

require_once 'farmsea-api.php';

// ═══════════════════════════════════════════════════════════
// EXAMPLE 1: REGISTER
// ═══════════════════════════════════════════════════════════
$result = farmsea_register('John Doe', 'john@example.com', 'password123', '9876543210');
if ($result['success']) {
    farmsea_save_token($result['token'], $result['user']);
    echo "Registered! Token: " . $result['token'];
} else {
    echo "Error: " . ($result['message'] ?? 'Registration failed');
}

// ═══════════════════════════════════════════════════════════
// EXAMPLE 2: LOGIN
// ═══════════════════════════════════════════════════════════
$result = farmsea_login('john@example.com', 'password123');
if ($result['success']) {
    farmsea_save_token($result['token'], $result['user']);
    $token = $result['token'];
    echo "Logged in as: " . $result['user']['name'];
}

// ═══════════════════════════════════════════════════════════
// EXAMPLE 3: FETCH ALL PRODUCTS
// ═══════════════════════════════════════════════════════════
$result = farmsea_get_products(['per_page' => 12]);
if ($result['success']) {
    foreach ($result['data'] as $product) {
        echo $product['name'] . ' - ₹' . $product['price'];
        echo '<img src="' . $product['image'] . '">';
    }
}

// ═══════════════════════════════════════════════════════════
// EXAMPLE 4: FETCH PRODUCTS BY CATEGORY
// ═══════════════════════════════════════════════════════════
$result = farmsea_get_products(['category_id' => 1, 'per_page' => 20]);

// ═══════════════════════════════════════════════════════════
// EXAMPLE 5: SEARCH PRODUCTS
// ═══════════════════════════════════════════════════════════
$result = farmsea_get_products(['search' => 'chicken']);

// ═══════════════════════════════════════════════════════════
// EXAMPLE 6: SINGLE PRODUCT
// ═══════════════════════════════════════════════════════════
$result = farmsea_get_product(1);
if ($result['success']) {
    $p = $result['data'];
    echo $p['name'];
    echo $p['description'];
    echo '₹' . $p['price'];
    // Variants
    foreach ($p['variants'] as $variant) {
        echo $variant['quantity'] . ' ' . $variant['unit'] . ' - ₹' . $variant['selling_price'];
    }
}

// ═══════════════════════════════════════════════════════════
// EXAMPLE 7: CATEGORIES WITH SUBCATEGORIES
// ═══════════════════════════════════════════════════════════
$result = farmsea_get_categories();
foreach ($result['data'] as $category) {
    echo $category['name'];
    foreach ($category['subcategories'] as $sub) {
        echo '  └ ' . $sub['name'];
    }
}

// ═══════════════════════════════════════════════════════════
// EXAMPLE 8: VALIDATE COUPON
// ═══════════════════════════════════════════════════════════
$result = farmsea_validate_coupon('SAVE10', 500.00);
if ($result['success']) {
    echo "Discount: ₹" . $result['discount'];
}

// ═══════════════════════════════════════════════════════════
// EXAMPLE 9: PLACE ORDER
// ═══════════════════════════════════════════════════════════
$token = farmsea_get_token(); // from session after login

$result = farmsea_place_order(
    $token,
    items: [
        ['product_id' => 1, 'quantity' => 2, 'variant_index' => 0],
        ['product_id' => 3, 'quantity' => 1, 'variant_index' => 0],
    ],
    address: [
        'name'    => 'John Doe',
        'phone'   => '9876543210',
        'address' => '123 Main Street',
        'city'    => 'Noida',
        'pincode' => '201301',
    ],
    paymentMethod: 'COD',
    couponCode: 'SAVE10'
);

if ($result['success']) {
    $order = $result['data'];
    echo "Order placed! ID: " . $order['order_number'];
    echo "Total: ₹" . $order['total'];
} else {
    echo "Order failed: " . ($result['message'] ?? 'Unknown error');
}

// ═══════════════════════════════════════════════════════════
// EXAMPLE 10: GET ORDER HISTORY
// ═══════════════════════════════════════════════════════════
$result = farmsea_get_orders($token);
foreach ($result['data'] as $order) {
    echo $order['order_number'] . ' - ' . $order['status'] . ' - ₹' . $order['total'];
}
