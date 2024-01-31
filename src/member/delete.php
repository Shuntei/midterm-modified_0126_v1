<?php

require './parts/db_connect_midterm.php';

$userId = isset($_GET['userId']) ? $_GET['userId'] : 0;

$sql = "DELETE from mb_user where user_id=$userId";

$pdo->query($sql);

$goto = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'member.php';

header('location: ' . $goto);