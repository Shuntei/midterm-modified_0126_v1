<?php
    $db_host = 'localhost';
    $db_name = 'midterm_db';
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