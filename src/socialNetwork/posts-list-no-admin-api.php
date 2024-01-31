<?php

require __DIR__ . '/parts/db_connect.php';

if (isset($_GET['comment_id'])) {
    $comment_id = intval($_GET['comment_id']);
    $sql = "SELECT * FROM sn_comments_reply WHERE parent_id = :comment_id ORDER BY comment_timestamp DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
    $stmt->execute();
    $replies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($replies);
    exit;
} else {
    // Handle the case where parent_id is not provided.
    echo json_encode(['error' => 'Comment ID not provided']);
    exit;
}
