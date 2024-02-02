<?php require __DIR__ . "/parts/db_connect.php";
    
    $friendship_id = isset($_GET['friendship_id']) ? intval($_GET['friendship_id']) : 0;
    $sql = "DELETE FROM sn_friends WHERE friendship_id=$friendship_id";
    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來
    
    $goto = empty($_SERVER['HTTP_REFERER']) ? 'friends.php' : $_SERVER['HTTP_REFERER'];
    header('Location: ' . $goto);