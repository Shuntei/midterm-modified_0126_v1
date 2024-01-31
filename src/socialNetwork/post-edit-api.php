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
    
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    if(empty($post_id)) {
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

    $sql = "UPDATE `sn_posts` SET 
    `user_id`=?,
    `content`=?,
    `image_url`=?,
    `video_url`=?,
    `location`=?,
    `tagged_users`=?,
    `posts_timestamp`=?
    WHERE post_id=? ";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['user_id'],
            $_POST['content'],
            $_POST['image_url'],
            $_POST['video_url'],
            $_POST['location'],
            $_POST['tagged_users'],
            $_POST['posts_timestamp'],
            $post_id,
        ]);
    }catch(PDOException $e) {
        $output['error'] = 'SQL failed : ' . $e->getMessage();
    }


    // $stmt->rowCount(); # 新增幾筆
    $output['success'] = boolval($stmt->rowCount());

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
