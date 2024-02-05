<?php

require "./parts/db_connect_midterm.php";
header('Content-Type: application/json');

$searchName = isset($_GET['searchName']) ? $_GET['searchName'] : '';
$searchEmail = isset($_GET['searchEmail']) ? $_GET['searchEmail'] : '';
$searchPhone = isset($_GET['searchPhone']) ? $_GET['searchPhone'] : '';
$sortDirection = isset($_GET['sort']) ? $_GET['sort'] : 'desc';

if($searchName){

    $sql = "SELECT * from mb_user where name like ? order by user_id $sortDirection";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        "%$searchName%"
    ]);
    $rows = $stmt->fetchAll();
    
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
} elseif ($searchEmail){
    $sql = "SELECT * from mb_user where email like ? order by user_id $sortDirection";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        "%$searchEmail%"
    ]);
    $rows = $stmt->fetchAll();
    
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
} elseif($searchPhone){
    $sql = "SELECT * from mb_user where phone like ? order by user_id $sortDirection";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        "%$searchPhone%"
    ]);
    $rows = $stmt->fetchAll();
    
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
} else {
    $sql = "SELECT * from mb_user where 1 order by user_id $sortDirection";
    
    $rows = $pdo->query($sql)->fetchAll();
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
}

