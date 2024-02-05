<?php
require __DIR__ . '/parts/db_connect_midterm.php';

$itemId = $_GET['item_id'] ?? '';
$quantity = $_GET['quantity'] ?? '';

$response = [];

if ($itemId !== '' && $quantity !== '') {
    // 執行查詢以從 ca_merchandise 表獲取 unit_price
    $sql = "SELECT unit_price FROM ca_merchandise WHERE item_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$itemId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $response['success'] = true;
        $response['total_price'] = (float)$result['unit_price'] * (int)$quantity;
    } else {
        $response['success'] = false;
    }
} else {
    $response['success'] = false;
}

header('Content-Type: application/json');
echo json_encode($response);
