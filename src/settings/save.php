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

$id = isset($_POST['id']) ? $_POST['id'] : 0;
$permissionId = isset($_POST['permissionId']) ? $_POST['permissionId'] : 0;

if(empty($id) || empty($permissionId)){
    $output['code'] = 1;
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}


$sql = "UPDATE `mb_team_member` SET `fk_permission_id`= ? WHERE id = ?";

$stmt = $pdo->prepare($sql);


 try {
     $stmt->execute([
        $permissionId,
        $id
     ]);
 } catch(PDOException $e){
    $output['code'] = 2;
    $output['error'] = 'SQL error' . $e->getMessage();
 }

 $output['success'] = $stmt->rowCount();


 header('Content-Type: application/json');

 echo json_encode($output, JSON_UNESCAPED_UNICODE);




