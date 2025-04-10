<?php
require_once 'config.php';
session_start();

if(!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if(!isset($input['receiver_id']) || !isset($input['message'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid input']);
    exit();
}

try {
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->execute([
        $_SESSION['user_id'],
        $input['receiver_id'],
        $input['message']
    ]);
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} catch(PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Failed to send message']);
}
?>
