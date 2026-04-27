<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin User ────────────────────────────────────────
        $admin = User::create([
            'name'     => 'FarmSea Admin',
            'email'    => 'admin@farmsea.in',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'status'   => 'active',
            'phone'    => '9999999999',
        ]);

        // ── Sample Customers ──────────────────────────────────
        $customers = [];
        foreach (['Rahul Sharma','Anjali Gupta','Amit Kumar','Priya Das','Suresh Babu'] as $name) {
            $customers[] = User::create([
                'name'     => $name,
                'email'    => Str::slug($name).'@gmail.com',
                'password' => Hash::make('password'),
                'role'     => 'customer',
                'status'   => 'active',
                'phone'    => '98'.rand(10000000, 99999999),
            ]);
        }

        // ── Categories ────────────────────────────────────────
        $catData = [
            ['Fish',       'Fresh river and sea fish'],
            ['Chicken',    'Farm fresh chicken'],
            ['Mutton',     'Premium goat meat'],
            ['Vegetables', 'Fresh organic vegetables'],
            ['Fruits',     'Seasonal fresh fruits'],
            ['Eggs',       'Farm fresh eggs'],
            ['Seafood',    'Premium seafood'],
            ['Ready to Cook', 'Pre-marinated ready to cook items'],
        ];

        $categories = [];
        foreach ($catData as [$name, $desc]) {
            $categories[$name] = Category::create([
                'name'        => $name,
                'slug'        => Str::slug($name),
                'description' => $desc,
                'is_active'   => true,
            ]);
        }

        // ── Sub-categories ────────────────────────────────────
        $subCatData = [
            'Fish'    => ['River Fish', 'Sea Fish', 'Dry Fish'],
            'Chicken' => ['Broiler Chicken', 'Country Chicken', 'Boneless'],
            'Mutton'  => ['Curry Cut', 'Shoulder Cut', 'Keema'],
        ];

        foreach ($subCatData as $parentName => $subs) {
            foreach ($subs as $sub) {
                Category::create([
                    'name'      => $sub,
                    'slug'      => Str::slug($parentName.'-'.$sub),
                    'parent_id' => $categories[$parentName]->id,
                    'is_active' => true,
                ]);
            }
        }

        // ── Products ──────────────────────────────────────────
        $productData = [
            ['Rohu Fish (1kg)',          'fish',       249, 299, 50],
            ['Hilsa Fish (500g)',         'fish',       399, 449, 30],
            ['Broiler Chicken (1kg)',     'chicken',    189, 220, 80],
            ['Country Chicken (1kg)',     'chicken',    349, 399, 25],
            ['Mutton Curry Cut (500g)',   'mutton',     399, 449, 40],
            ['Mutton Keema (500g)',       'mutton',     349, 399, 35],
            ['Fresh Prawns (500g)',       'seafood',    299, 349, 45],
            ['Spinach (500g)',            'vegetables', 49,  59,  100],
            ['Tomatoes (1kg)',            'vegetables', 39,  49,  150],
            ['Banana (1 dozen)',          'fruits',     59,  69,  80],
        ];

        $products = [];
        foreach ($productData as [$name, $catKey, $price, $mrp, $stock]) {
            $catName = ucfirst($catKey);
            if ($catKey === 'fish') $catName = 'Fish';
            if ($catKey === 'chicken') $catName = 'Chicken';
            if ($catKey === 'mutton') $catName = 'Mutton';
            if ($catKey === 'seafood') $catName = 'Seafood';
            if ($catKey === 'vegetables') $catName = 'Vegetables';
            if ($catKey === 'fruits') $catName = 'Fruits';

            $products[] = Product::create([
                'category_id' => $categories[$catName]->id,
                'name'        => $name,
                'slug'        => Str::slug($name),
                'price'       => $price,
                'mrp'         => $mrp,
                'stock'       => $stock,
                'unit'        => 'Kg',
                'is_active'   => true,
            ]);
        }

        // ── Sample Orders ─────────────────────────────────────
        $statuses = ['pending', 'processing', 'out_for_delivery', 'delivered', 'cancelled'];
        foreach ($customers as $customer) {
            $order = Order::create([
                'user_id'        => $customer->id,
                'status'         => $statuses[array_rand($statuses)],
                'subtotal'       => 0,
                'total'          => 0,
                'payment_method' => ['COD', 'Online'][rand(0, 1)],
                'payment_status' => 'paid',
            ]);

            $selectedProducts = collect($products)->random(rand(1, 3));
            $subtotal = 0;
            foreach ($selectedProducts as $product) {
                $qty = rand(1, 3);
                $itemSubtotal = $product->price * $qty;
                $subtotal += $itemSubtotal;
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => $qty,
                    'unit_price' => $product->price,
                    'subtotal'   => $itemSubtotal,
                ]);
            }

            $order->update(['subtotal' => $subtotal, 'total' => $subtotal]);
        }
    }
}
