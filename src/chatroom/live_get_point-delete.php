<?php require __DIR__ . "/parts-get-point/db_connect_midterm.php";

$get_point_id = isset($_GET['get_point_id']) ? intval($_GET['get_point_id']) : 0;

$sql = "DELETE FROM live_get_point WHERE 
get_point_id=$get_point_id";

$pdo->query($sql);

$goto = empty($_SERVER['HTTP_REFERER']) ? 'live_get_point-list-admin.php' : $_SERVER['HTTP_REFERER'];
header('Location: ' . $goto);
