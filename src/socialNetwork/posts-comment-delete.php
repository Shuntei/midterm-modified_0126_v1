<?php require __DIR__ . "/parts/db_connect.php";

    $comment_id = isset($_GET['comment_id']) ? intval($_GET['comment_id']) : 0;
    $sql = "DELETE FROM sn_comments WHERE comment_id=$comment_id";
    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來
    
    $goto = empty($_SERVER['HTTP_REFERER']) ? 'posts-list-no-admin.php' : $_SERVER['HTTP_REFERER'];
    header('Location: ' . $goto);