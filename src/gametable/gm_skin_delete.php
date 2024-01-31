<?php require __DIR__ . "/parts/db_connect.php";
    
    $skin_id = isset($_GET['skin_id']) ? intval($_GET['skin_id']) : 0;
    $sql = "DELETE FROM gm_skin WHERE skin_id=$skin_id";
    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來
    
    $goto = empty($_SERVER['HTTP_REFERER']) ? 'gm_skin_list_admin.php' : $_SERVER['HTTP_REFERER'];
    header('Location: ' . $goto);