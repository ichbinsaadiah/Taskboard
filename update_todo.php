<?php
session_start();
header('Content-Type: application/json');
require_once 'includes/config.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing fields']);
    exit;
}

$id = $_POST['id'];
$userId = $_SESSION['user_id'];

// Use null if fields are not provided
$title = isset($_POST['title']) ? trim($_POST['title']) : null;
$desc  = isset($_POST['description']) ? trim($_POST['description']) : null;
$list  = isset($_POST['list']) ? trim($_POST['list']) : null;
$status = isset($_POST['status']) ? trim($_POST['status']) : null;
$dueDate = trim($_POST['due_date'] ?? '');

// Status-only update (via checkbox)
if ($status !== null && $title === null && $desc === null && $list === null) {
    $stmt = $conn->prepare("UPDATE todos SET status = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $status, $id, $userId);

// Full update (from Edit modal)
} elseif ($title !== null && $desc !== null && $list !== null) {
    $stmt = $conn->prepare("UPDATE todos SET title = ?, description = ?, list = ?, due_date = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssssii", $title, $desc, $list, $dueDate, $id, $userId);

// Invalid case
} else {
    echo json_encode(['status' => 'error', 'message' => 'Incomplete update fields']);
    exit;
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Task updated']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Update failed']);
}

$stmt->close();
$conn->close();
