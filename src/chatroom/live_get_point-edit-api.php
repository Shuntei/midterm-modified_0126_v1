<?php
require __DIR__ . '/parts-get-point/db_connect_midterm.php';
header('Content-Type: application/json');

$output = [
    "success" => false,
    "error" => "",
    "code" => 0,
    "postData" => $_POST,
    "errors" => [],
];
// TODO: 資料輸入之前, 要做檢查
# filter_var('bob@example.com', FILTER_VALIDATE_EMAIL);    

$sid = isset($_POST['get_point_id']) ? intval($_POST['get_point_id']) : 0;
if (empty($sid)) {
    $output['error'] = '沒有資料編號';
    $output['code'] = 401;
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "UPDATE `live_get_point` SET 
    `user_id`=?,
    `received_point`=?,
    `point_source`=?,
    `date_get_point`=?,
    WHERE get_point_id=? ";

$stmt = $pdo->prepare($sql);
try {
    $stmt->execute([
        $_POST['user_id'],
        $_POST['received_point'],
        $_POST['point_source'],
        $_POST['date_get_point'],
    ]);
} catch (PDOException $e) {
    $output['error'] = 'SQL failed : ' . $e->getMessage();
}


// $stmt->rowCount(); # 新增幾筆
$output['success'] = boolval($stmt->rowCount());

echo json_encode($output, JSON_UNESCAPED_UNICODE);
