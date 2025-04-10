<?php
require_once 'config.php';
session_start();

if(!isset($_SESSION['user_id']) || !isset($_GET['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

try {
    $stmt = $pdo->prepare("
        SELECT * FROM messages 
        WHERE (sender_id = ? AND receiver_id = ?)
        OR (sender_id = ? AND receiver_id = ?)
        ORDER BY created_at ASC
    ");
    $stmt->execute([
        $_SESSION['user_id'], 
        $_GET['user_id'],
        $_GET['user_id'],
        $_SESSION['user_id']
    ]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Mark messages as read
    $pdo->prepare("
        UPDATE messages 
        SET is_read = 1 
        WHERE sender_id = ? AND receiver_id = ? AND is_read = 0
    ")->execute([$_GET['user_id'], $_SESSION['user_id']]);
    
    header('Content-Type: application/json');
    echo json_encode($messages);
} catch(PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Failed to load messages']);
}
?>
