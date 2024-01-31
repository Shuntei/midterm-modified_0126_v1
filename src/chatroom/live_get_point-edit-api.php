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

$get_point_id = isset($_POST['get_point_id']) ? intval($_POST['get_point_id']) : 0;
if (empty($get_point_id)) {
    $output['error'] = '沒有資料編號';
    $output['code'] = 401;
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$date_get_point = empty($_POST['date_get_point']) ? null : $_POST['date_get_point'];
$date_get_point = strtotime($date_get_point); #轉換為timestamp
if ($date_get_point === false) {
    $date_get_point = null;
} else {
    $date_get_point = date('Y-m-d H-i-s', $date_get_point);
}

$sql = "UPDATE `live_get_point` SET 
    `user_id`=?,
    `received_point`=?,
    `point_source`=?,
    `date_get_point`=?
    WHERE get_point_id=? ";

$stmt = $pdo->prepare($sql);
try {
    $stmt->execute([
        $_POST['user_id'],
        $_POST['received_point'],
        $_POST['point_source'],
        $date_get_point,
        $get_point_id
    ]);
} catch (PDOException $e) {
    $output['error'] = 'SQL failed : ' . $e->getMessage();
}


// $stmt->rowCount(); # 新增幾筆
$output['success'] = boolval($stmt->rowCount());

echo json_encode($output, JSON_UNESCAPED_UNICODE);
