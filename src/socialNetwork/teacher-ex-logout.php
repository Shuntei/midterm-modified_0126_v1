<?php
    session_start();
    // session_destroy();

    unset($_SESSION['admin']);
    
    // 轉向direct
    header('Location: index_.php');