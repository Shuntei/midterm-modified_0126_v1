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
    
    $coupon_id = isset($_POST['coupon_id']) ? intval($_POST['coupon_id']) : 0;
    if(empty($coupon_id)) {
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


    $sql = "UPDATE `gm_coupon` SET 
    `coupon_id`=?,
    `coupon_model_id`=?,
    `denomination`=?,
    `coupon_name`=?,
    WHERE coupon_id=? ";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['coupon_id'],
            $_POST['coupon_model_id'],
            $_POST['denomination'],
            $_POST['coupon_name'],
        ]);
    }catch(PDOException $e) {
        $output['error'] = 'SQL failed : ' . $e->getMessage();
    }


    // $stmt->rowCount(); # 新增幾筆
    $output['success'] = boolval($stmt->rowCount());

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
