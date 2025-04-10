<?php
require_once 'config.php';
session_start();

if(!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT id, name, phone_number, profile_pic FROM users WHERE id != ?");
    $stmt->execute([$_SESSION['user_id']]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($users);
} catch(PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Failed to load users']);
}
?>
