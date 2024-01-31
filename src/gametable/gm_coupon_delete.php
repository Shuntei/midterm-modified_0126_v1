<?php require __DIR__ . "/parts/db_connect_midterm.php";
    
    $coupon_id = isset($_GET['coupon_id']) ? intval($_GET['coupon_id']) : 0;
    $sql = "DELETE FROM gm_coupon WHERE coupon_id=$coupon_id";
    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來
    
    $goto = empty($_SERVER['HTTP_REFERER']) ? 'gm_coupon_list_admin.php' : $_SERVER['HTTP_REFERER'];
    header('Location: ' . $goto);