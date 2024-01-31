<?php
require __DIR__ . '/parts/db_connect_midterm.php';
header('Content-Type: application/json');

$output = [
    "success" => false,
    "error" => "",
    "code" => 0,
    "postData" => $_POST,
    "errors" => [],
];

$sticker_inventory_id = isset($_POST['sticker_inventory_id']) ? intval($_POST['sticker_inventory_id']) : 0;
if (empty($sticker_inventory_id)) {
    $output['error'] = '沒有資料編號';
    $output['code'] = 401;
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "UPDATE `live_sticker_inventory` SET 
    `sticker_title`=?,
    `sticker_cost`=?,
    `sticker_pic`=?
    WHERE sticker_inventory_id=? ";

$stmt = $pdo->prepare($sql);
try {
    $stmt->execute([
        $_POST['sticker_title'],
        $_POST['sticker_cost'],
        $_POST['sticker_pic'],
        $sticker_inventory_id
    ]);
} catch (PDOException $e) {
    $output['error'] = 'SQL failed : ' . $e->getMessage();
}


// $stmt->rowCount(); # 新增幾筆
$output['success'] = boolval($stmt->rowCount());

echo json_encode($output, JSON_UNESCAPED_UNICODE);
