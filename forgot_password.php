<?php
include "includes/config.php";  // DB connection
require 'vendor/autoload.php'; // Load SendGrid library

use SendGrid\Mail\Mail;

function logError($message) {
    $logFile = __DIR__ . "/logs/error.log";  // /logs folder inside TaskBoard
    if (!file_exists(dirname($logFile))) {
        mkdir(dirname($logFile), 0777, true); // create folder if missing
    }
    $timestamp = date("Y-m-d H:i:s");
    error_log("[$timestamp] $message\n", 3, $logFile);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // 1. Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    if (!$stmt) {
        die("Prepare failed (users): " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // 2. Generate token
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // 3. Save token in password_resets table
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        if (!$stmt) {
            logError("Prepare failed (password_resets): " . $conn->error);
            $message = '<div class="alert alert-danger">Something went wrong. Please try again later.</div>';
        } else {
            $stmt->bind_param("sss", $email, $token, $expires);
            $stmt->execute();
        }

        // 4. Create reset link
        $resetLink = "http://localhost/TaskBoard/reset_password.php?token=" . $token;

        // 5. Send email with SendGrid
        try {
            $emailObj = new Mail();
            $emailObj->setFrom("saadiahkhan24@gmail.com", "TaskBoard Support");
            $emailObj->setSubject("Password Reset Request - TaskBoard");
            $emailObj->addTo($email, "User");
            $emailObj->addContent(
                "text/html",
                "Hello,<br><br>Click the link below to reset your password:<br>
                <a href='$resetLink'>$resetLink</a><br><br>
                If you did not request this, please ignore."
            );

            $sendgrid = new \SendGrid($_ENV["SENDGRID_API_KEY"]);
            $response = $sendgrid->send($emailObj);

            if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
                $message = '<div class="alert alert-success">Reset link sent to your email.</div>';
            } else {
                $errorDetails = $response -> body();
                logError("SendGrid failed: ". $response->statusCode(). " - " . $errorDetails);
                $message = '<div class="alert alert-danger">Failed to send email. Try again later.</div>';
            }
        } catch (Exception $e) {
            $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">Email not found.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password - TaskBoard</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="login-bg">

    <div class="login-container">
        <!-- Left illustration -->
        <div class="login-illustration">
            <img src="assets/img/task.jpg" alt="Forgot Password" class="img-fluid">
        </div>

        <!-- Right form -->
        <div class="login-form">
            <h2 class="mb-3">Forgot Password</h2>
            <p class="text-muted mb-4">Enter your registered email to receive a reset link.</p>

            <?php if (!empty($message)) echo $message; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" required>
                </div>
                <button type="submit" class="btn btn-login w-100">Send Reset Link</button>
            </form>

            <div class="login-links mt-3">
                <a href="login.html">Back to Login</a>
            </div>
        </div>
    </div>

</body>

</html>