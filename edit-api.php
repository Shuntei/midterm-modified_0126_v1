<?php
require __DIR__ . '/admin-required.php';
require __DIR__ .'/parts/db_connect1.php';
header('Content-Type: application/json');

$output = [
    'success'=> false,
    "error"=> "",
    "code"=> "0",
    "postDara"=> $_POST,
    "errors"=> [],
];

// TODO: 資料輸入之前, 要做檢查
# filter_var('bob@example.com', FILTER_VALIDATE_EMAIL) #檢查email可用

$sid= isset($_POST['sid']) ? intval($_POST['sid']) : 0;
if(empty($sid)){
    $output['error']= '沒有資料編號';
    $output['code']= 401;
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit();
}


$birthday= empty($_POST['birthday']) ? null : $_POST['birthday'];
$birthday= strtotime($birthday);
if($birthday===false){
    $birthday = null; 
}else{
    $birthday = date('Y-m-d', $birthday);
}


$sql= "UPDATE `address_book` SET `name`=?,`email`=?,`mobile`=?,`birthday`=?,`address`=? WHERE sid=? ";

$stmt= $pdo->prepare($sql);

try{
    $stmt->execute([
        $_POST['name'],
        $_POST['email'],
        $_POST['mobile'],
        $birthday,
        $_POST['address'],
        $sid
    ]);
} catch(PDOException $e) {
    $output['error'] = 'SQL出錯'. $e->getMessage();
}

// $stmt->rowCount(); # 影響幾筆(增刪修)
$output['success']= boolval($stmt->rowCount());


echo json_encode($output, JSON_UNESCAPED_UNICODE);
