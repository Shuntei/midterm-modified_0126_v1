<?php require __DIR__ . "/parts/db_connect_midterm.php";
    

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_ids'])) {
        $item_ids = $_POST['item_ids'];
    
        // 在這裡執行根據 $item_ids 刪除商品的操作
        // 例如，你可以使用 SQL 語句進行刪除
        // 並根據執行的結果返回相應的響應
        
        // 以下僅為示例，實際情況可能需要根據你的數據庫結構進行調整
        require __DIR__ . '/parts/db_connect_midterm.php';
    
        $delete_sql = "DELETE FROM ca_merchandise WHERE item_id IN (" . implode(',', $item_ids) . ")";
        $delete_stmt = $pdo->prepare($delete_sql);
        
        if ($delete_stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => '刪除成功']);
        } else {
            echo json_encode(['status' => 'error', 'message' => '刪除失敗']);
        }
    
    } else {
        echo json_encode(['status' => 'error', 'message' => '無效的請求']);
    }
    


    # $_SERVER['HTTP_REFERER'] # 人從哪裡來
    
    $goto = empty($_SERVER['HTTP_REFERER']) ? 'ca_merchandise_list_admin.php' : $_SERVER['HTTP_REFERER'];
    header('Location: ' . $goto);