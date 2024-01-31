<?php
require __DIR__ . '/parts-get-point/db_connect_midterm.php';
$output = [
    "success" => false,
    "error" => "",
    "code" => 0,
    "postData" => $_POST,
    "errors" => [],
];

$date_get_point = empty($_POST['date_get_point']) ? null : $_POST['date_get_point'];
$date_get_point = strtotime($date_get_point); #轉換為timestamp
if ($date_get_point === false) {
    $date_get_point = null;
} else {
    $date_get_point = date('Y-m-d H-i-s', $date_get_point);
}

$sql = "INSERT INTO `live_get_point`(`user_id`, `received_point`, `point_source`, `date_get_point`) VALUES (?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);
try {
    $stmt->execute([
        $_POST['user_id'],
        $_POST['received_point'],
        $_POST['point_source'],
        $date_get_point
    ]);
} catch (PDOException $e) {
    $output['error'] = 'SQL failed : ' . $e->getMessage();
}

$stmt->rowCount();
$output['success'] = boolval($stmt->rowCount());
$output['lastInsertId'] = $pdo->lastInsertId();

header('Content-Type: application/json');
echo json_encode($output);
