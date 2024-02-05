<?php

require './admin-required.php';
require "./parts/db_connect_midterm.php";

header('Content-Type: application/json');


$output = [
    "success"=> false,
    "postData"=> $_POST,
    "code" => 0,
    "error" => "",
    "errors" => [],
];

$userId = isset($_POST['userId']) ? $_POST['userId'] : 0;

if(empty($userId)){
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$birthday = empty($_POST['birthday']) ? null : $_POST['birthday'];
$birthday = strtotime($birthday);

if($birthday === false){
    $birthday = null;
} else {
    $birthday = date('Y-m-d', $birthday);
}

$checkEmailSql = "SELECT `user_id` FROM `mb_user` WHERE `email` = ? AND `user_id` != ?";
$checkEmailStmt = $pdo->prepare($checkEmailSql);

try {
    $checkEmailStmt->execute([$_POST['email'], $userId]);
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

$checkPhoneSql = "SELECT `user_id` FROM `mb_user` WHERE `phone` = ? AND `user_id` != ?";
$checkPhoneStmt = $pdo->prepare($checkPhoneSql);

try {
    $checkPhoneStmt->execute([$_POST['phone'], $userId]);
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
    $output['code'] = 3;
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "UPDATE `mb_user` 
SET `name`=?,
`email`=?,
`phone`=?,
`birthday`=?,
`fk_skin_id`=?
 WHERE `user_id`=?";

 $stmt = $pdo->prepare($sql);


 try {
     $stmt->execute([
        $_POST["name"],
        $_POST["email"],
        $_POST["phone"],
        $birthday,
        $_POST["skinId"],
        $userId
     ]);
 } catch(PDOException $e){
    $output['error'] = 'SQL error' . $e->getMessage();
 }

 $output['success'] = $stmt->rowCount();


 header('Content-Type: application/json');

 echo json_encode($output, JSON_UNESCAPED_UNICODE);




