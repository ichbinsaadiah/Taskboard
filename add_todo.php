<?php
session_start();
header("Content-Type: application/json");
require_once "includes/config.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$list = trim($_POST['list'] ?? 'Inbox');
$status = trim($_POST['status'] ?? 'Pending');
$dueDate = $_POST['due_date'] ?? null;

if ($dueDate === '') {
    $dueDate = null;
}

if ($title === '') {
    echo json_encode(["status" => "error", "message" => "Title is required"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO todos (user_id, title, description, list, status, due_date) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssss", $user_id, $title, $description, $list, $status, $dueDate);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Task added successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database error"]);
}

$stmt->close();
$conn->close();
?>