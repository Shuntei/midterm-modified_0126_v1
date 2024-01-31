<?php

require __DIR__ . '/parts/db_connect.php';

if (isset($_GET['board_id'])) {
    $boardId = intval($_GET['board_id']);
    $sql = "SELECT * FROM sn_posts WHERE board_id = :board_id ORDER BY posts_timestamp DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':board_id', $boardId, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($posts);
    exit;
} else {
    // Handle the case where board_id is not provided.
    echo json_encode(['error' => 'Board ID not provided']);
    exit;
}
