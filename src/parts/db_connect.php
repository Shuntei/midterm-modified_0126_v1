<?php
    $db_host = 'localhost';
<<<<<<< HEAD
    $db_name = 'prj57';
=======
    $db_name = 'proj57';
>>>>>>> f3a15239ed2d7d65cbb4681d5a94064ce94ad1fb
    $db_user = 'root';
    $db_pass = '';
    
    $dsn = "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4";
    // $pdo = new PDO($dsn, $db_user, $db_pass, $pdo_options);
    
    $pdo_options = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
    // $stmt = $pdo->query("SELECT * FROM address_book LIMIT 2");
    
    try{
        $pdo = new PDO($dsn, $db_user, $db_pass, $pdo_options);
    }catch(PDOException $e) {
        echo $e->getMessage();
    }

    # 啟動 session 的功能
    if(!isset($_SESSION)) {
        session_start();
    }