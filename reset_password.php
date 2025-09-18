<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password - TaskBoard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-bg">

<div class="d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow-lg p-4 reset-card" style="max-width: 420px; width:100%;">
    <h3 class="text-center mb-3">
      <i class="bi bi-shield-lock-fill text-primary me-2"></i>🔑 Reset Password
    </h3>
    <p class="text-muted text-center">Enter your new password below</p>

    <?php if (!empty($message)): ?>
      <div class="alert-custom <?php echo strpos($message, 'success') !== false ? 'alert-success' : 'alert-danger'; ?>">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="reset_password_process.php">
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">

      <div class="mb-3">
        <label for="password" class="form-label">New Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>

      <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirm New Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
      </div>

      <!-- Password Rules Checklist -->
      <ul class="password-checklist list-unstyled small text-muted mb-3">
        <li id="length" class="invalid"><i class="bi bi-x-circle text-danger me-1"></i> At least 7 characters</li>
        <li id="uppercase" class="invalid"><i class="bi bi-x-circle text-danger me-1"></i> At least one uppercase letter</li>
        <li id="lowercase" class="invalid"><i class="bi bi-x-circle text-danger me-1"></i> At least one lowercase letter</li>
        <li id="number" class="invalid"><i class="bi bi-x-circle text-danger me-1"></i> At least one number</li>
        <li id="symbol" class="invalid"><i class="bi bi-x-circle text-danger me-1"></i> At least one special character</li>
        <li id="match" class="invalid"><i class="bi bi-x-circle text-danger me-1"></i> Passwords match</li>
      </ul>

      <button type="submit" id="submitBtn" class="btn btn-gradient w-100" disabled>Update Password</button>
    </form>
  </div>
</div>

<script src="assets/js/reset-password.js"></script>
</body>
</html>

