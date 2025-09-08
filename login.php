<?php

header('Content-Type: application/json');
require_once 'includes/config.php';

// Allow only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Only POST method allowed"]);
    exit;
}

// Get POST data
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$remember = isset($_POST['remember']) ? true : false; // Check remember field

// Validate
if (empty($email) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Email and password required"]);
    exit;
}

// Check if user exists
$stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Invalid email or password"]);
    exit;
}

$user = $result->fetch_assoc();

// Verify password
if (password_verify($password, $user['password'])) {
    session_start(); // Start session only after password is verified
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];

    // Handle Remember Me
    if ($remember) {
        $token = bin2hex(random_bytes(32));
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);

        // Save hashed token in DB
        $update = $conn->prepare("UPDATE users SET remember_token=? WHERE id=?");
        $update->bind_param("si", $hashedToken, $user['id']);
        $update->execute();

        // Set secure cookie (30 days)
        setcookie(
            "remember_me",
            $user['id'] . ":" . $token,
            time() + (30 * 24 * 60 * 60),
            "/",
            "",
            true,  // Secure (HTTPS only, if available)
            true   // HttpOnly
        );
    }

    echo json_encode([
        "status" => "success",
        "message" => "Login successful",
        "user" => [
            "id" => $user['id'],
            "name" => $user['name'],
            "email" => $user['email']
        ]
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid email or password"]);
}

$stmt->close();
$conn->close();
