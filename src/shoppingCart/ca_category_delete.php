<?php require __DIR__ . "/parts/db_connect_midterm.php";
    
    $category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
    $sql2 = "DELETE FROM ca_category WHERE category_id=$category_id";
    $pdo->query($sql2);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來
    
    $goto = empty($_SERVER['HTTP_REFERER']) ? 'ca_merchandise_list_admin.php' : $_SERVER['HTTP_REFERER'];
    header('Location: ' . $goto);