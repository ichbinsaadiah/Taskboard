<?php

header('Content-Type: application/json');
// Include the DB connections
require_once 'includes/config.php';

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Only POST method allowed"]);
    exit;
}

// Get and Post data
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

// Validate Input
if(empty($name) || empty($email) || empty($password)){
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    echo json_encode(["status" => "error", "message" => "Invalid email"]);
    exit;
}

// Check if user alraedy exists
$stmt = $conn-> prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if($stmt-> num_rows > 0){
    echo json_encode(["status" => "error", "message" => "Email already registered"]);
    exit;
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert into database
$stmt = $conn-> prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashedPassword);

if($stmt->execute()){
    echo json_encode(["status" => "success", "message" => "Registered successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Registration failed."]);
}

$stmt->close();
$conn->close();