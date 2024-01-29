<?php
require __DIR__ . '/parts-get-point/db_connect_midterm.php';
$output = [
    "success" => false,
    "error" => "",
    "code" => 0,
    "postData" => $_POST,
    "errors" => [],
];

$sql = "INSERT INTO `live_get_point`(`get_point_id`, `user_id`, `received_point`, `point_source` `d`ate_get_point`) VALUES (?, ?, ?, ?,?)";

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

$stmt->rowCount();
$output['success'] = boolval($stmt->rowCount());
$output['lastInsertId'] = $pdo->lastInsertId();

header('Content-Type: application/json');
echo json_encode($output);
