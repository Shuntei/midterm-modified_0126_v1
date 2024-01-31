<?php
require __DIR__ . '/parts/db_connect_midterm.php';
$output = [
    "success" => false,
    "error" => "",
    "code" => 0,
    "postData" => $_POST,
    "errors" => [],
];

$sql = "INSERT INTO `live_sticker_inventory`(`sticker_title`, `sticker_cost`, `sticker_pic`) VALUES (?, ?, ?)";

$stmt = $pdo->prepare($sql);
try {
    $stmt->execute([
        $_POST['sticker_title'],
        $_POST['sticker_cost'],
        $_POST['sticker_pic'],
    ]);
} catch (PDOException $e) {
    $output['error'] = 'SQL failed : ' . $e->getMessage();
}

$stmt->rowCount();
$output['success'] = boolval($stmt->rowCount());
$output['lastInsertId'] = $pdo->lastInsertId();

header('Content-Type: application/json');
echo json_encode($output);
