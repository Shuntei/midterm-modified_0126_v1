<?php

header('Content-Type: application/json');
require __DIR__ . "/parts/db_connect_midterm.php";

$output = [
    "success" => false,
    "postData" => $_POST,
    "code" => 0,
    "error" => "",
    "errors" => [],
];

$birthday = empty($_POST['birthday']) ? null : $_POST['birthday'];
$birthday = strtotime($birthday);

if ($birthday === false) {
    $birthday = null;
} else {
    $birthday = date('Y-m-d', $birthday);
}

$passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
$profilePic = empty($_POST['picture']) ? null : $_POST['picture'];

$checkEmailSql = "SELECT `user_id` FROM `mb_user` WHERE `email` = ?";
$checkEmailStmt = $pdo->prepare($checkEmailSql);

try {
    $checkEmailStmt->execute([$_POST['email']]);
    $resultEmail = $checkEmailStmt->rowCount();

    if ($resultEmail > 0) {
        $output['code'] = 1;
        $output['error'] = 'This email has been registered already';
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }
} catch (PDOException $e) { 
    error_log('Checking email error' . $e->getMessage());
    $output['error'] = 'SQL error' . $e->getMessage();
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$checkPhoneSql = "SELECT `user_id` FROM `mb_user` WHERE `phone` = ?";
$checkPhoneStmt = $pdo->prepare($checkPhoneSql);

try {
    $checkPhoneStmt->execute([$_POST['phone']]);
    $resultPhone = $checkPhoneStmt->rowCount();

    if ($resultPhone > 0) {
        $output['code'] = 2;
        $output['error'] = 'This phone number has been registered already';
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }
} catch (PDOException $e) {
    error_log('Checking phone error' . $e->getMessage());
    $output['error'] = 'SQL error' . $e->getMessage();
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}


$sql = "INSERT INTO `mb_user`(`name`, `email`, `phone`, `password_hash`, `profile_pic_url`, `birthday`, `created_at`, `fk_skin_id`) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)";

$stmt = $pdo->prepare($sql);


try {
    $stmt->execute([
        $_POST["name"],
        $_POST["email"],
        $_POST["phone"],
        $passwordHash,
        $profilePic,
        $birthday,
        $_POST["skinId"]
    ]);
} catch (PDOException $e) {
    error_log('Sql insert error' . $e->getMessage());
    $output['code'] = 3;
    $output['error'] = 'SQL error' . $e->getMessage();
}

$output['success'] = $stmt->rowCount();

echo json_encode($output, JSON_UNESCAPED_UNICODE);
