<?php

if(!isset($_SESSION)){
    session_start();
}

if (!isset($_SESSION["admin"]) && !isset($_SESSION["moderator"]) && !isset($_SESSION["viewer"])) {
    header("Location: login.php");
    exit();
}