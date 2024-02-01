<?php require __DIR__ . "/parts/db_connect_midterm.php";
    
    $cart_id = isset($_GET['cart_id']) ? intval($_GET['cart_id']) : 0;
    $sql = "DELETE FROM ca_cart WHERE cart_id=$cart_id";
    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來
    
    $goto = empty($_SERVER['HTTP_REFERER']) ? 'ca_cart_list_admin.php' : $_SERVER['HTTP_REFERER'];
    header('Location: ' . $goto);