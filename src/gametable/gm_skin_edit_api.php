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
    
    $skin_id = isset($_POST['skin_id']) ? intval($_POST['skin_id']) : 0;
    if(empty($skin_id)) {
        $output['error'] = '沒有資料編號';
        $output['code'] = 401;
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $skin_last_update = empty($_POST['skin_last_update']) ? null : $_POST['skin_last_update'];
    $skin_last_update = strtotime($skin_last_update); #轉換為timestamp
    if($skin_last_update===false) {
        $skin_last_update = null;
    }else {
        $skin_last_update = date($skin_last_update);
    }

    $sql = "UPDATE `gm_skin` SET 
    `skin_id`=?,
    `skin_name`=?,
    `skin_model_id`=?,
    `role`=?,
    `file`=?,
    `skin_last_update`=?
    WHERE skin_id=? ";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['skin_id'],
            $_POST['skin_name'],
            $_POST['skin_model_id'],
            $_POST['role'],
            $_POST['file'],
            $_POST['skin_last_update'],
        ]);
    }catch(PDOException $e) {
        $output['error'] = 'SQL failed : ' . $e->getMessage();
    }


    // $stmt->rowCount(); # 新增幾筆
    $output['success'] = boolval($stmt->rowCount());

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
