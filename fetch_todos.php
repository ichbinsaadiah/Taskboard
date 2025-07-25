<?php
session_start();
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Expires: 0');

file_put_contents("debug.txt", json_encode($_SESSION)); // DEBUG LINE

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

require_once 'includes/config.php';

$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, title, description, status, list FROM todos WHERE user_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$todos = [];
while ($row = $result->fetch_assoc()) {
    $todos[] = $row;
}

file_put_contents("todos_debug.json", json_encode($todos, JSON_PRETTY_PRINT));

echo json_encode($todos);

$stmt->close();
$conn->close();
?>
