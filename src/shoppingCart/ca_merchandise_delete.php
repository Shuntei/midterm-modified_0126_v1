<?php require __DIR__ . "/parts/db_connect_midterm.php";
    
    $item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;
    $sql = "DELETE FROM ca_merchandise WHERE item_id=$item_id";
    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來
    
    $goto = empty($_SERVER['HTTP_REFERER']) ? 'ca_merchandise_list_admin.php' : $_SERVER['HTTP_REFERER'];
    header('Location: ' . $goto);