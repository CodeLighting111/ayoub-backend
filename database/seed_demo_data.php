<?php

$pdo = new PDO('mysql:host=127.0.0.1;dbname=Ayoub;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$client = $pdo->query("
    SELECT c.*, g.name AS governorate_name, ci.name AS city_name, a.name AS area_name
    FROM clients c
    LEFT JOIN governorates g ON g.id = c.governorate_id
    LEFT JOIN cities ci ON ci.id = c.city_id
    LEFT JOIN areas a ON a.id = c.area_id
    WHERE c.id = 1
    LIMIT 1
")->fetch(PDO::FETCH_ASSOC);

if (! $client) {
    echo "Client 1 not found.\n";
    exit(1);
}

$products = $pdo->query("SELECT * FROM products WHERE status = 'active' ORDER BY id LIMIT 2")->fetchAll(PDO::FETCH_ASSOC);

if (count($products) < 1) {
    echo "No active products found.\n";
    exit(1);
}

$p1 = $products[0];
$p2 = $products[1] ?? $products[0];
$deliveryFee = 30.0;

function lineTotal(array $product, int $qty): float
{
    $price = (float) ($product['discount_price'] ?: $product['price']);

    return round($price * $qty, 2);
}

function buildOrderTotals(array $p1, array $p2, int $q1, int $q2, float $deliveryFee): array
{
    $l1 = lineTotal($p1, $q1);
    $l2 = lineTotal($p2, $q2);
    $subtotal = round($l1 + $l2, 2);

    return [
        'subtotal' => $subtotal,
        'total' => round($subtotal + $deliveryFee, 2),
        'items' => [
            ['product' => $p1, 'quantity' => $q1, 'line_total' => $l1],
            ['product' => $p2, 'quantity' => $q2, 'line_total' => $l2],
        ],
    ];
}

$demoOrders = [
    [
        'order_number' => 'ORD-2026-DEMO-007',
        'status' => 'delivered',
        'payment_method' => 'cash',
        'payment_status' => 'paid',
        'notes' => '[تجريبي] طلب يوليو — تم التوصيل',
        'created_at' => '2026-07-05 11:30:00',
        'delivered_at' => '2026-07-06 14:00:00',
        'quantities' => [2, 1],
    ],
    [
        'order_number' => 'ORD-2026-DEMO-008',
        'status' => 'delivered',
        'payment_method' => 'wallet',
        'payment_status' => 'paid',
        'notes' => '[تجريبي] طلب يوليو — تم التوصيل',
        'created_at' => '2026-07-18 09:15:00',
        'delivered_at' => '2026-07-19 16:30:00',
        'quantities' => [3, 2],
    ],
    [
        'order_number' => 'ORD-2026-DEMO-009',
        'status' => 'cancelled',
        'payment_method' => 'cash',
        'payment_status' => 'unpaid',
        'notes' => '[تجريبي] طلب يوليو — ملغى',
        'created_at' => '2026-07-25 18:45:00',
        'cancelled_at' => '2026-07-26 10:00:00',
        'quantities' => [1, 1],
    ],
    [
        'order_number' => 'ORD-2026-DEMO-010',
        'status' => 'delivered',
        'payment_method' => 'bank_transfer',
        'payment_status' => 'paid',
        'notes' => '[تجريبي] طلب يونيو — تم التوصيل',
        'created_at' => '2026-06-10 13:20:00',
        'delivered_at' => '2026-06-11 11:00:00',
        'quantities' => [4, 1],
    ],
    [
        'order_number' => 'ORD-2026-DEMO-011',
        'status' => 'delivered',
        'payment_method' => 'cash',
        'payment_status' => 'paid',
        'notes' => '[تجريبي] طلب يونيو — تم التوصيل',
        'created_at' => '2026-06-22 08:00:00',
        'delivered_at' => '2026-06-23 15:45:00',
        'quantities' => [2, 3],
    ],
    [
        'order_number' => 'ORD-2026-DEMO-012',
        'status' => 'delivered',
        'payment_method' => 'wallet',
        'payment_status' => 'paid',
        'notes' => '[تجريبي] طلب مايو — تم التوصيل',
        'created_at' => '2026-05-14 16:10:00',
        'delivered_at' => '2026-05-15 12:30:00',
        'quantities' => [5, 2],
    ],
    [
        'order_number' => 'ORD-2026-DEMO-013',
        'status' => 'delivered',
        'payment_method' => 'cash',
        'payment_status' => 'paid',
        'notes' => '[تجريبي] طلب أغسطس إضافي — تم التوصيل',
        'created_at' => '2026-08-10 10:00:00',
        'delivered_at' => '2026-08-11 13:00:00',
        'quantities' => [2, 2],
    ],
    [
        'order_number' => 'ORD-2026-DEMO-014',
        'status' => 'shipped',
        'payment_method' => 'cash',
        'payment_status' => 'unpaid',
        'notes' => '[تجريبي] طلب أغسطس — تم الشحن',
        'created_at' => '2026-08-15 14:30:00',
        'quantities' => [1, 2],
    ],
];

$orderInsert = $pdo->prepare('
    INSERT INTO orders (
        order_number, client_id, status, payment_method, payment_status,
        subtotal, delivery_fee, total, notes,
        client_name, client_phone, branch_name, delivery_address,
        governorate_name, city_name, area_name,
        preferred_delivery_at, expected_delivery_at, delivered_at, cancelled_at,
        created_at, updated_at
    ) VALUES (
        :order_number, :client_id, :status, :payment_method, :payment_status,
        :subtotal, :delivery_fee, :total, :notes,
        :client_name, :client_phone, :branch_name, :delivery_address,
        :governorate_name, :city_name, :area_name,
        :preferred_delivery_at, :expected_delivery_at, :delivered_at, :cancelled_at,
        :created_at, :updated_at
    )
    ON DUPLICATE KEY UPDATE
        status = VALUES(status),
        payment_method = VALUES(payment_method),
        payment_status = VALUES(payment_status),
        subtotal = VALUES(subtotal),
        delivery_fee = VALUES(delivery_fee),
        total = VALUES(total),
        notes = VALUES(notes),
        delivered_at = VALUES(delivered_at),
        cancelled_at = VALUES(cancelled_at),
        updated_at = VALUES(updated_at)
');

$itemInsert = $pdo->prepare('
    INSERT INTO order_items (
        order_id, product_id, product_name, unit_label, image_url,
        unit_price, quantity, line_total, created_at, updated_at
    ) VALUES (
        :order_id, :product_id, :product_name, :unit_label, :image_url,
        :unit_price, :quantity, :line_total, :created_at, :updated_at
    )
');

$ordersCreated = 0;

foreach ($demoOrders as $demo) {
    [$q1, $q2] = $demo['quantities'];
    $totals = buildOrderTotals($p1, $p2, $q1, $q2, $deliveryFee);
    $createdAt = $demo['created_at'];

    $orderInsert->execute([
        'order_number' => $demo['order_number'],
        'client_id' => $client['id'],
        'status' => $demo['status'],
        'payment_method' => $demo['payment_method'],
        'payment_status' => $demo['payment_status'],
        'subtotal' => $totals['subtotal'],
        'delivery_fee' => $deliveryFee,
        'total' => $totals['total'],
        'notes' => $demo['notes'],
        'client_name' => $client['name'],
        'client_phone' => $client['phone'],
        'branch_name' => $client['branch_name'],
        'delivery_address' => $client['address'],
        'governorate_name' => $client['governorate_name'],
        'city_name' => $client['city_name'],
        'area_name' => $client['area_name'],
        'preferred_delivery_at' => date('Y-m-d H:i:s', strtotime($createdAt.' +2 days')),
        'expected_delivery_at' => date('Y-m-d H:i:s', strtotime($createdAt.' +1 day')),
        'delivered_at' => $demo['delivered_at'] ?? null,
        'cancelled_at' => $demo['cancelled_at'] ?? null,
        'created_at' => $createdAt,
        'updated_at' => $createdAt,
    ]);

    $orderId = (int) $pdo->query("SELECT id FROM orders WHERE order_number = ".$pdo->quote($demo['order_number']))->fetchColumn();

    $pdo->prepare('DELETE FROM order_items WHERE order_id = ?')->execute([$orderId]);

    foreach ($totals['items'] as $line) {
        $product = $line['product'];
        $unitPrice = (float) ($product['discount_price'] ?: $product['price']);

        $itemInsert->execute([
            'order_id' => $orderId,
            'product_id' => $product['id'],
            'product_name' => $product['name'],
            'unit_label' => $product['unit_label'],
            'image_url' => $product['image_url'],
            'unit_price' => $unitPrice,
            'quantity' => $line['quantity'],
            'line_total' => $line['line_total'],
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);
    }

    $ordersCreated++;
}

echo "Seeded {$ordersCreated} additional demo orders for client «{$client['name']}».\n";

// Social media demo accounts
$iconDir = dirname(__DIR__).'/public/images/social-media-accounts';
if (! is_dir($iconDir)) {
    mkdir($iconDir, 0755, true);
}

$socialAccounts = [
    [
        'name' => 'فيسبوك',
        'url' => 'https://facebook.com/kashkoolgomla',
        'file' => 'demo_facebook.svg',
        'sort_order' => 1,
        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64"><rect width="64" height="64" rx="14" fill="#1877F2"/><path fill="#fff" d="M36.5 33.5h3.2l1.4-4.6h-4.6v-3c0-1.3.4-2.2 2.3-2.2h2.4V20.2c-.4 0-2-.3-3.9-.3-3.9 0-6.6 2.4-6.6 6.7v3.3h-4.4v4.6h4.4V44h5.3v-10.5z"/></svg>',
    ],
    [
        'name' => 'انستغرام',
        'url' => 'https://instagram.com/kashkoolgomla',
        'file' => 'demo_instagram.svg',
        'sort_order' => 2,
        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64"><defs><linearGradient id="g" x1="0%" y1="100%" x2="100%" y2="0%"><stop offset="0%" stop-color="#FD5949"/><stop offset="50%" stop-color="#D6249F"/><stop offset="100%" stop-color="#285AEB"/></linearGradient></defs><rect width="64" height="64" rx="14" fill="url(#g)"/><rect x="17" y="17" width="30" height="30" rx="8" fill="none" stroke="#fff" stroke-width="3"/><circle cx="32" cy="32" r="7" fill="none" stroke="#fff" stroke-width="3"/><circle cx="43" cy="21" r="2.5" fill="#fff"/></svg>',
    ],
    [
        'name' => 'واتساب',
        'url' => 'https://wa.me/201206027127',
        'file' => 'demo_whatsapp.svg',
        'sort_order' => 3,
        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64"><rect width="64" height="64" rx="14" fill="#25D366"/><path fill="#fff" d="M32 18c-7.7 0-14 6.1-14 13.6 0 2.4.6 4.7 1.8 6.8L18 46l8-1.7c2 1.1 4.2 1.7 6.5 1.7 7.7 0 14-6.1 14-13.6S39.7 18 32 18zm0 24.8c-2 0-3.9-.5-5.6-1.5l-.4-.2-4.8 1 1-4.7-.3-.5a11.2 11.2 0 0 1-1.7-6c0-6.2 5.3-11.2 11.8-11.2 6.5 0 11.8 5 11.8 11.2S38.5 42.8 32 42.8zm6.5-8.4c-.4-.2-2.3-1.1-2.6-1.2-.4-.1-.7-.2-1 .2-.3.4-1.1 1.2-1.4 1.5-.3.3-.5.3-1 .1-.4-.2-1.8-.7-3.4-2.1-1.3-1.1-2.1-2.5-2.3-2.9-.2-.4 0-.6.2-.8.2-.2.4-.5.6-.7.2-.2.2-.4.3-.6.1-.2 0-.5 0-.7 0-.2-.5-1.3-.7-1.8-.2-.5-.4-.4-.7-.4h-.6c-.2 0-.5.1-.7.3-.2.2-1 1-1 2.4s1 2.8 1.2 3c.2.2 2 3.1 4.9 4.3.7.3 1.2.5 1.6.6.7.2 1.3.2 1.8.1.5-.1 1.6-.7 1.8-1.3.2-.6.2-1.2.1-1.3-.1-.1-.4-.2-.8-.4z"/></svg>',
    ],
    [
        'name' => 'تيك توك',
        'url' => 'https://tiktok.com/@kashkoolgomla',
        'file' => 'demo_tiktok.svg',
        'sort_order' => 4,
        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64"><rect width="64" height="64" rx="14" fill="#010101"/><path fill="#25F4EE" d="M38 22v11.5a7.5 7.5 0 1 1-5.3-7.2v3.4a3.8 3.8 0 1 0 2.7 3.6V18h3.6a6.5 6.5 0 0 0 6.5 6.5V22a10 10 0 0 1-7.5-3.2z"/><path fill="#FE2C55" d="M40 20.8a6.5 6.5 0 0 0 4.6-1.9V22a10 10 0 0 1-7.5-3.2V31a7.5 7.5 0 1 1-5.3-7.2v3.4a3.8 3.8 0 1 0 2.7 3.6V18h3.6v2.8z"/></svg>',
    ],
];

$socialInsert = $pdo->prepare('
    INSERT INTO social_media_accounts (name, url, image_url, sort_order, created_at, updated_at)
    VALUES (:name, :url, :image_url, :sort_order, NOW(), NOW())
    ON DUPLICATE KEY UPDATE
        url = VALUES(url),
        image_url = VALUES(image_url),
        sort_order = VALUES(sort_order),
        updated_at = NOW()
');

// Remove old English "Facebook" demo if exists without matching Arabic name
$pdo->exec("DELETE FROM social_media_accounts WHERE name = 'Facebook' AND url NOT LIKE '%kashkool%'");

$socialCount = 0;
foreach ($socialAccounts as $account) {
    $filePath = $iconDir.'/'.$account['file'];
    file_put_contents($filePath, $account['svg']);
    $imageUrl = '/images/social-media-accounts/'.$account['file'];

    $existing = $pdo->prepare('SELECT id FROM social_media_accounts WHERE name = ? LIMIT 1');
    $existing->execute([$account['name']]);
    $existingId = $existing->fetchColumn();

    if ($existingId) {
        $pdo->prepare('UPDATE social_media_accounts SET url = ?, image_url = ?, sort_order = ?, updated_at = NOW() WHERE id = ?')
            ->execute([$account['url'], $imageUrl, $account['sort_order'], $existingId]);
    } else {
        $socialInsert->execute([
            'name' => $account['name'],
            'url' => $account['url'],
            'image_url' => $imageUrl,
            'sort_order' => $account['sort_order'],
        ]);
    }

    $socialCount++;
}

echo "Seeded {$socialCount} demo social media accounts.\n";

// Summary for client 1
$summary = $pdo->query("
    SELECT
        COUNT(*) AS total_orders,
        SUM(CASE WHEN status != 'cancelled' AND YEAR(created_at)=2026 AND MONTH(created_at)=8 THEN total ELSE 0 END) AS aug_total,
        SUM(CASE WHEN YEAR(created_at)=2026 AND MONTH(created_at)=8 THEN 1 ELSE 0 END) AS aug_count
    FROM orders WHERE client_id = 1
")->fetch(PDO::FETCH_ASSOC);

echo "Client 1 now has {$summary['total_orders']} orders.";
echo " August 2026: {$summary['aug_count']} orders, total ".number_format((float) $summary['aug_total'], 2)." EGP.\n";
