<?php require __DIR__ . "/parts/db_connect_midterm.php";

$sticker_inventory_id = isset($_GET['sticker_inventory_id']) ? intval($_GET['sticker_inventory_id']) : 0;

$sql = "DELETE FROM live_sticker_inventory WHERE 
sticker_inventory_id=$sticker_inventory_id";

$pdo->query($sql);

$goto = empty($_SERVER['HTTP_REFERER']) ? 'live_sticker_inventory-list-admin.php' : $_SERVER['HTTP_REFERER'];
header('Location: ' . $goto);
