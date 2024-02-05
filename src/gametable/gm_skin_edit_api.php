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
    `skin_name`=?,
    `skin_model_id`=?,
    `role`=?,
    `file`=?,
    `skin_last_update`=?
    WHERE skin_id=? ";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['skin_name'],
            $_POST['skin_model_id'],
            $_POST['role'],
            $_POST['file'],
            $_POST['skin_last_update'],
            $skin_id,
        ]);
    }catch(PDOException $e) {
        $output['error'] = 'SQL failed : ' . $e->getMessage();
    }

// 檢查是否有檔案上傳的部分
$dir = __DIR__ . '/3dmodel/';  // 存放檔案的資料夾

$exts = [   // 檔案類型的篩選
    'model/gltf+json' => '.gltf',
    'application/octet-stream' => '.fbx',
    'text/plain' => '.obj',
];

$outputFile = [
    'success' => false,
    'file' => ''
];  // 輸出的格式

// 確保有上傳檔案，並且有 upload_file 欄位，並且沒有錯誤
if (!empty($_FILES) && !empty($_FILES['upload_file']) && $_FILES['upload_file']['error'] == 0) {
    // 如果類型有對應到副檔名
    if (!empty($exts[$_FILES['upload_file']['type']])) {
        $ext = $exts[$_FILES['upload_file']['type']];  // 副檔名
        $f = sha1($_FILES['upload_file']['name'] . uniqid());  // 隨機的主檔名
        if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $dir . $f . $ext)) {
            $outputFile['success'] = true;
            $outputFile['file'] = $f . $ext;
        }
    }
}

$output['file_upload'] = $outputFile;
$output['success'] = $output['success'] || $outputFile['success']; // update overall success

    // $stmt->rowCount(); # 新增幾筆
    // $output['success'] = boolval($stmt->rowCount());

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
