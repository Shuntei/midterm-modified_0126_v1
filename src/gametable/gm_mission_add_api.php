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

    // $last_update = empty($_POST['skin_last_update']) ? null : $_POST['skin_last_update'];
    // $last_update = strtotime($last_update); #轉換為timestamp
    // if($last_update===false) {
    //     $last_update = null;
    // }else {
    //     // $last_update = date('Y-m-d H:i:s', $last_update);
    //     $last_update = date($last_update);
    // }

    $sql = "INSERT INTO `gm_mission`(`mission_id`, `mission_name`, `mission_description`) VALUES (?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
        $_POST['mission_id'],
        $_POST['mission_name'],
        $_POST['mission_description'],
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