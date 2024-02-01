<?php
    require __DIR__ . '/parts/db_connect_midterm.php';
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
    
    $cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;
    if(empty($cart_id)) {
        $output['error'] = '沒有資料編號';
        $output['code'] = 401;
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // $birthday = empty($_POST['birthday']) ? null : $_POST['birthday'];
    // $birthday = strtotime($birthday); #轉換為timestamp
    // if($birthday===false) {
    //     $birthday = null;
    // }else {
    //     $birthday = date('Y-m-d', $birthday);
    // }


    $sql = "UPDATE `ca_cart` SET 
    `item_id`=?,
    `quantity`=?,
    `user_id`=?,
    `unit_price`=?
    WHERE cart_id=? ";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['item_id'],
            $_POST['quantity'],
            $_POST['user_id'],
            $_POST['unit_price'],
            $cart_id,
        ]);
    }catch(PDOException $e) {
        $output['error'] = 'SQL failed : ' . $e->getMessage();
    }


    // $stmt->rowCount(); # 新增幾筆
    $output['success'] = boolval($stmt->rowCount());

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
