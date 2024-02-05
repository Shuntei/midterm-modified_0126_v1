<?php

require './admin-required.php';
require './parts/db_connect_midterm.php';
header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if(isset($data['userIds']) && is_array($data['userIds'])){
    $userIds = array_map('intval', $data['userIds']);
    $placeholders = implode(',', array_fill(0, count($userIds), '?'));

    $sql = "DELETE from mb_user WHERE user_id IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($userIds);

    $output = ['success'=> true];
} else {
    $output = ['success'=> false];
}

$userId = isset($_GET['userId']) ? $_GET['userId'] : 0;

$sql = "DELETE from mb_user where user_id=$userId";

$result = $pdo->query($sql);

if($result){
    $output = [
        'success'=> true,
    ];
} else {
    $output = [
        'success'=> false,
    ];
}

echo json_encode($output);
