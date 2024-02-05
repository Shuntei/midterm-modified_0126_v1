<?php
require __DIR__ . '/parts/db_connect_midterm.php';

$keyword = $_GET['keyword'] ?? '';

$sql = "SELECT * FROM gm_user_get_coupon WHERE
        user_id LIKE ? OR
        user_get_coupon_id LIKE ? OR
        coupon_id LIKE ? OR
        last_update LIKE ?";
$stmt = $pdo->prepare($sql);
$stmt->execute(["%$keyword%","%$keyword%","%$keyword%","%$keyword%"]);
$rows = $stmt->fetchAll();
// $output = [
//     "success" => false,
//     "error" => "",
//     "code" => 0,
//     "postData" => $_POST,
//     "errors" => [],
// ];

// // Check if search parameter is provided in the URL
// $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// // Prepare the base SQL query
// $sql = "INSERT INTO `gm_coupon`(`coupon_id`, `coupon_model_id`, `denomination`, `coupon_name`) VALUES (?, ?, ?, ?)";

// // If a search term is provided, modify the SQL query
// if ($searchTerm != '') {
//     $sql = "INSERT INTO `gm_coupon`(`coupon_id`, `coupon_model_id`, `denomination`, `coupon_name`) 
//             VALUES (?, ?, ?, ?)
//             WHERE `coupon_name` LIKE ?";
// }

// $stmt = $pdo->prepare($sql);

// try {
//     if ($searchTerm != '') {
//         $stmt->execute([
//             $_POST['coupon_id'],
//             $_POST['coupon_model_id'],
//             $_POST['denomination'],
//             $_POST['coupon_name'],
//             '%' . $searchTerm . '%', // Add wildcards for partial matching
//         ]);
//     } else {
//         $stmt->execute([
//             $_POST['coupon_id'],
//             $_POST['coupon_model_id'],
//             $_POST['denomination'],
//             $_POST['coupon_name'],
//         ]);
//     }
// } catch (PDOException $e) {
//     $output['error'] = 'SQL failed: ' . $e->getMessage();
// }

// $output['success'] = boolval($stmt->rowCount());
// $output['lastInsertId'] = $pdo->lastInsertId();  // Get the latest inserted data's PK

// header('Content-Type: application/json');
echo json_encode($output);
?>
