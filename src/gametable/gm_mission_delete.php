<?php require __DIR__ . "/parts/db_connect.php";
    
    $skin_id = isset($_GET['mission_id']) ? intval($_GET['mission_id']) : 0;
    $sql = "DELETE FROM gm_mission WHERE skin_id=$mission_id";
    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來
    
    $goto = empty($_SERVER['HTTP_REFERER']) ? 'gm_mission_list_admin.php' : $_SERVER['HTTP_REFERER'];
    header('Location: ' . $goto);