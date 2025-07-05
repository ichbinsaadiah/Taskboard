<?php
session_start();
require_once 'includes/config.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['id']) || !isset($_POST['title'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing fields']);
    exit;
}

$id = $_POST['id'];
$title = $_POST['title'];
$desc = $_POST['description'];
$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("UPDATE todos SET title = ?, description = ? WHERE id = ? AND user_id = ?");
$stmt->bind_param("ssii", $title, $desc, $id, $userId);
$stmt->execute();

echo json_encode(['status' => 'success', 'message' => 'Task updated']);

$stmt->close();
$conn->close();
