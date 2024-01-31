<?php
require __DIR__ .'/parts/db_connect_midterm.php';
header('Content-Type: application/json');

$output = [
    "success"=> false,
    "error"=> "",
    "code"=> "0",
    "postDara"=> $_POST,
    "errors"=> [],
];

// TODO: 資料輸入之前, 要做檢查
# filter_var('bob@example.com', FILTER_VALIDATE_EMAIL) #檢查email可用

$tour_comment_id= isset($_POST['tour_comment_id']) ? intval($_POST['tour_comment_id']) : 0;
if(empty($tour_comment_id)){
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

// 設定編輯資料指令
$sql= "UPDATE `tr_tour_comment` SET 
`user_id`=?,
`tour_id`=?,
`comment_content`=?,
`comment_parent_id`=?
WHERE tour_comment_id=? ";

$stmt= $pdo->prepare($sql);

try{
    $stmt->execute([
        $_POST['user_id'],
        $_POST['tour_id'],
        $_POST['comment_content'],
        $_POST['comment_parent_id'],
        $tour_comment_id
    ]);
} catch(PDOException $e) {
    $output['error'] = 'SQL出錯'. $e->getMessage();
}

// $stmt->rowCount(); # 影響幾筆(增刪修)
$output['success']= boolval($stmt->rowCount());


echo json_encode($output, JSON_UNESCAPED_UNICODE);
