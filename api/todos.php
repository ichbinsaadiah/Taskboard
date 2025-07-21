<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Fetch todos
    $stmt = $conn->prepare("SELECT * FROM todos WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $todos = [];
    while ($row = $result->fetch_assoc()) {
        $todos[] = $row;
    }
    echo json_encode($todos);

} elseif ($method === 'POST') {
    // Add todo
    $title = $_POST['title'] ?? '';
    $desc = $_POST['description'] ?? '';
    $list = $_POST['list'] ?? 'Inbox';

    $stmt = $conn->prepare("INSERT INTO todos (user_id, title, description, list) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $userId, $title, $desc, $list);
    $stmt->execute();

    echo json_encode(['status' => 'success', 'message' => 'Todo added']);
}
?>
