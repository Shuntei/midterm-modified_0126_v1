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

$tourid= isset($_POST['tour_id']) ? intval($_POST['tour_id']) : 0;
if(empty($tourid)){
    $output['error']= '沒有資料編號';
    $output['code']= 401;
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit();
}


// $birthday= empty($_POST['birthday']) ? null : $_POST['birthday'];
// $birthday= strtotime($birthday);
// if($birthday===false){
//     $birthday = null; 
// }else{
//     $birthday = date('Y-m-d', $birthday);
// }

// 設定編輯資料指令
$sql= "UPDATE `tr_tour` SET 
`user_id`=?,
`ruin_id`=?,
`event_date`=?,
`max_groupsize`=?,
`event_period`=?,
`level_id`=?,
`video_url`=?,
`title`=?,
`description`=?,
`content`=?
WHERE tour_id=? ";

$stmt= $pdo->prepare($sql);

try{
    $stmt->execute([
        $_POST['user_id'],
        $_POST['ruin_id'],
        $_POST['event_date'],
        $_POST['max_groupsize'],
        $_POST['event_period'],
        $_POST['level_id'],
        $_POST['video_url'],
        $_POST['title'],
        $_POST['description'],
        $_POST['content'],
        $tourid
    ]);
} catch(PDOException $e) {
    $output['error'] = 'SQL出錯'. $e->getMessage();
}

// $stmt->rowCount(); # 影響幾筆(增刪修)
$output['success']= boolval($stmt->rowCount());


echo json_encode($output, JSON_UNESCAPED_UNICODE);
