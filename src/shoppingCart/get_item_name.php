<?php
require __DIR__ . '/parts/db_connect_midterm.php';

$itemId = $_GET['item_id'] ?? '';

$response = [];

if ($itemId !== '') {
    // 執行查詢以從 ca_merchandise 表獲取 item_name
    $sql = "SELECT item_name FROM ca_merchandise WHERE item_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$itemId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $response['success'] = true;
        $response['item_name'] = $result['item_name'];
    } else {
        $response['success'] = false;
    }
} else {
    $response['success'] = false;
}

header('Content-Type: application/json');
echo json_encode($response);
