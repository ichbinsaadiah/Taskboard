<?php
include "includes/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($token) || empty($password)) {
        exit("Invalid request");
    }

    // Validate confirm match
    if ($password !== $confirm_password) {
        exit("Passwords do not match.");
    }

    // Validate rules
    if (
        strlen($password) < 7 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[0-9]/', $password) ||
        !preg_match('/[^a-zA-Z0-9]/', $password)
    ) {
        exit("Password must be at least 7 characters with uppercase, lowercase, number, and symbol.");
    }

    // Check token validity
    $stmt = $conn->prepare("SELECT email, expires_at FROM password_resets WHERE token = ? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();
        $conn->close();
        exit("Invalid or expired token.");
    }

    $stmt->bind_result($email, $expires);
    $stmt->fetch();
    $stmt->close();

    // Check expiry time
    if (strtotime($expires) < time()) {
        $conn->close();
        exit("Token has expired.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update password
    $stmt2 = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt2->bind_param("ss", $hashedPassword, $email);
    $stmt2->execute();

    if ($stmt2->errno) {
        $stmt2->close();
        $conn->close();
        exit("Database error while updating password: " . $stmt2->error);
    }

    if ($stmt2->affected_rows === 0) {
        $message = "Password updated successfully (same as old password).";
    } else {
        $message = "Password updated successfully.";
    }

    $stmt2->close();

    // Remove used token
    $stmt3 = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
    $stmt3->bind_param("s", $email);
    $stmt3->execute();
    $stmt3->close();
    $conn->close();

    // Show styled success message
    echo "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Password Reset Success</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
<script>
    Swal.fire({
    icon: 'success',
    title: 'Password Updated!',
    text: 'Your password has been changed successfully.',
    confirmButtonText: 'Go to Login',
    confirmButtonColor: '#3085d6',
    timer: 5000, // auto close in 5 sec
    timerProgressBar: true
}).then((result) => {
    window.location.href = 'login.html';
});
</script>
</body>
</html>
";
    exit();
}
