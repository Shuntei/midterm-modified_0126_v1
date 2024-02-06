<?php

require "./parts/db_connect_midterm.php";
header("content-type: application/json");


$output = [
    "success" => false,
    "session" => false,
    "postData" => $_POST,
    "code" => 0, // 200, 302 etc 
    "error" => "",
];

if (empty($_POST['userName']) or empty($_POST['password'])) {
    $output['code'] = 1;
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "SELECT * from mb_team_member join mb_permission on fk_permission_id = permission_id where user_name=?";

$stmt = $pdo->prepare($sql);

$stmt->execute([$_POST["userName"]]);
$row = $stmt->fetch();

if (empty($row)) {
    $output['code'] = 2;
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$output['success'] = password_verify($_POST['password'], $row['password_hash']);

if ($output['success']) {

    if($row['permission_id'] == 1){
        $_SESSION['admin'] = true;
        $_SESSION['admin'] = [
        'id' => $row['id'],
        'userName' => $row['user_name'],
        'role' => $row['role']
    ];
    $output['session'] = true;
    } else if ($row['permission_id'] == 2){
        $_SESSION['moderator'] = true;
        $_SESSION['moderator'] = [
        'id' => $row['id'],
        'userName' => $row['user_name'],
        'role' => $row['role']
        ];
        $output['session'] = true;
    } else if ($row['permission_id'] == '3') {
        $_SESSION['viewer'] = true;
        $_SESSION['viewer'] = [
            'id' => $row['id'],
            'userName' => $row['user_name'],
            'role' => $row['role']
        ];
        $output['session'] = true;
    }
} else {
    $output['code'] = 3;
}



echo json_encode($output, JSON_UNESCAPED_UNICODE);
