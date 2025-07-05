<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

require_once "includes/config.php"; // adjust path if needed

$user_id = $_SESSION['user_id'];
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($title === '') {
    echo json_encode(["status" => "error", "message" => "Title is required"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO todos (user_id, title, description) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $title, $description);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Task added successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database error"]);
}
$stmt->close();
$conn->close();
?>
