<?php require __DIR__ . "/parts/db_connect.php";
    
    $sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0;
    $sql = "DELETE FROM address_book WHERE sid=$sid";
    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來
    
    $goto = empty($_SERVER['HTTP_REFERER']) ? 'list.php' : $_SERVER['HTTP_REFERER'];
    header('Location: ' . $goto);