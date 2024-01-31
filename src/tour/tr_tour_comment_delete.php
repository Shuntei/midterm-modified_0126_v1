<?php require __DIR__ . "/parts/db_connect_midterm.php";
    
    $tour_comment_id  = isset($_GET['tour_comment_id']) ? intval($_GET['tour_comment_id']) : 0;
    $sql = "DELETE FROM tr_tour_comment WHERE tour_comment_id=$tour_comment_id ";
    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來
    
    $goto = empty($_SERVER['HTTP_REFERER']) ? 'tr_tour_comment_list_admin.php' : $_SERVER['HTTP_REFERER'];
    header('Location: ' . $goto);