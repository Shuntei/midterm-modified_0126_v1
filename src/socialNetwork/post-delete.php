<?php require __DIR__ . "/parts/db_connect.php";
    
    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
    $sql = "DELETE FROM sn_posts WHERE post_id=$post_id";
    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來
    
    $goto = empty($_SERVER['HTTP_REFERER']) ? 'posts-list-no-admin.php' : $_SERVER['HTTP_REFERER'];
    header('Location: ' . $goto);