<?php

require "./parts/db_connect_midterm.php";
header('Content-Type: application/json');

$output = [
    "success"=> false,
    "postData"=> $_POST,
    "code" => 0,
    "error" => "",
    "errors" => [],
];

$birthday = empty($_POST['birthday']) ? null : $_POST['birthday'];
$birthday = strtotime($birthday);

if ($birthday===false) {
    $birthday = null;
} else {
    $birthday = date('Y-m-d', $birthday);
}

$passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
$profilePic = empty($_POST['picture']) ? null : $_POST['picture'];

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
 } catch(PDOException $e){
    $output['error'] = 'SQL error' . $e->getMessage();
 }

 $output['success'] = $stmt->rowCount();

 echo json_encode($output, JSON_UNESCAPED_UNICODE);




