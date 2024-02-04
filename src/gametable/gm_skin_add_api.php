<?php
    require __DIR__ . '/parts/db_connect.php';
    $output = [
        "success" => false,
        "error" => "",
        "code" => 0,
        "postData" => $_POST,
        "errors" => [],
    ];
    // TODO: 資料輸入之前, 要做檢查
    # filter_var('bob@example.com', FILTER_VALIDATE_EMAIL);

    $last_update = empty($_POST['skin_last_update']) ? null : $_POST['skin_last_update'];
    $last_update = strtotime($last_update); #轉換為timestamp
    if($last_update===false) {
        $last_update = null;
    }else {
        // $last_update = date('Y-m-d H:i:s', $last_update);
        $last_update = date($last_update);
    }

    # 檢查是否有檔案上傳
if (!empty($_FILES['file'])) {
    $file = $_FILES['file'];
    $filename = $file['name'];
    $filepath = __DIR__ . '/3dmodel/' . $filename;

    # 將檔案移動到指定路徑
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $output['file'] = $filename;  // 將檔案名稱存入資料庫
        $output['success'] = true;
    } else {
        $output['error'] = '檔案移動失敗';
    }
} else {
    $output['error'] = '沒有上傳的檔案';
}

    $sql = "INSERT INTO `gm_skin`(`skin_id`, `skin_name`, `skin_model_id`, `role`, `file`, `skin_last_update`) VALUES (?, ?, ?, ?, ?, ?)";

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


    $stmt->rowCount(); # 新增幾筆
    $output['success'] = boolval($stmt->rowCount());
    $output['lastInsertId'] = $pdo-> lastInsertId();  // 取得最新建立資料的 PK

    header('Content-Type: application/json');
    echo json_encode($output);
    // if(!empty($_POST)) {
    //     echo json_encode($_POST, JSON_UNESCAPED_UNICODE);
    // }else {
    //     echo json_encode([]);
    // }