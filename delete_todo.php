<?php
session_start();
require_once 'includes/config.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$id = $_POST['id'];
$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $userId);
$stmt->execute();

echo json_encode(['status' => 'success', 'message' => 'Task deleted']);

$stmt->close();
$conn->close();
