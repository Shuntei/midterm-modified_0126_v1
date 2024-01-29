<?php
    require __DIR__ . '/parts/db_connect.php';
    header('Content-Type: application/json');

    $output = [
        "success" => false,
        "error" => "",
        "code" => 0,
        "postData" => $_POST,
        "errors" => [],
    ];
    // TODO: 資料輸入之前, 要做檢查
    # filter_var('bob@example.com', FILTER_VALIDATE_EMAIL);    
    
    $sid = isset($_POST['sid']) ? intval($_POST['sid']) : 0;
    if(empty($sid)) {
        $output['error'] = '沒有資料編號';
        $output['code'] = 401;
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $birthday = empty($_POST['birthday']) ? null : $_POST['birthday'];
    $birthday = strtotime($birthday); #轉換為timestamp
    if($birthday===false) {
        $birthday = null;
    }else {
        $birthday = date('Y-m-d', $birthday);
    }


    $sql = "UPDATE `address_book` SET 
    `name`=?,
    `email`=?,
    `mobile`=?,
    `birthday`=?,
    `address`=?
    WHERE sid=? ";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['name'],
            $_POST['email'],
            $_POST['mobile'],
            $birthday,
            $_POST['address'],
            $sid
        ]);
    }catch(PDOException $e) {
        $output['error'] = 'SQL failed : ' . $e->getMessage();
    }


    // $stmt->rowCount(); # 新增幾筆
    $output['success'] = boolval($stmt->rowCount());

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
