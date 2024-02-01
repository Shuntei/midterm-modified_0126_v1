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
    
    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
    if(empty($item_id)) {
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


    $sql = "UPDATE `ca_merchandise` SET 
    `item_name`=?,
    `quantity`=?,
    `category_id`=?,
    `unit_price`=?,
    `product_img`=?,
    `description`=?
    WHERE item_id=? ";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['item_name'],
            $_POST['quantity'],
            $_POST['category_id'],
            $_POST['unit_price'],
            $_POST['product_img'],
            $_POST['description'],
            $item_id,
        ]);
    }catch(PDOException $e) {
        $output['error'] = 'SQL failed : ' . $e->getMessage();
    }


    // $stmt->rowCount(); # 新增幾筆
    $output['success'] = boolval($stmt->rowCount());

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
