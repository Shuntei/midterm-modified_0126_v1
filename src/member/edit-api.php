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




