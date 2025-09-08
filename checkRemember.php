<?php
session_start();
require_once 'includes/config.php';

// If session exists, user already logged in
if (isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "success",
        "message" => "Session active",
        "user" => [
            "id" => $_SESSION['user_id'],
            "name" => $_SESSION['user_name']
        ]
    ]);
    exit;
}

// If no session, check remember_me cookie
if (isset($_COOKIE['remember_me'])) {
    list($userId, $token) = explode(':', $_COOKIE['remember_me']);

    // Fetch user & stored hashed token
    $stmt = $conn->prepare("SELECT id, name, remember_token FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify token
        if (password_verify($token, $user['remember_token'])) {
            // Restore session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            echo json_encode([
                "status" => "success",
                "message" => "Auto-login successful",
                "user" => [
                    "id" => $user['id'],
                    "name" => $user['name']
                ]
            ]);
            exit;
        }
    }
}

// If nothing worked
echo json_encode([
    "status" => "error",
    "message" => "Not logged in"
]);
