<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Change Password - TaskBoard</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <!-- Bootstrap 5 for Toaster -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h2>Change Password</h2>
    <form id="changePasswordForm" action="reset_password_process.php" method="POST">
      <label for="old_password">Old Password:</label>
      <input type="password" class="form-control" name="old_password" id="old_password" required>

      <label for="password" class="mt-3">New Password:</label>
      <input type="password" class="form-control" name="password" id="password" required>
      <small class="form-text text-muted">
        Password must be at least 7 characters, with uppercase, lowercase, number, and symbol.
      </small>

      <label for="confirm_password" class="mt-3">Confirm New Password:</label>
      <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>

      <button type="submit" class="btn btn-primary mt-3">Update Password</button>
    </form>
  </div>

  <!-- Toast Container -->
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="toastMessage" class="toast align-items-center text-white border-0" role="alert">
      <div class="d-flex">
        <div class="toast-body"></div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Intercept form submission
    document.getElementById("changePasswordForm").addEventListener("submit", async function(event) {
      event.preventDefault();

      let formData = new FormData(this);
      let response = await fetch("reset_password_process.php", {
        method: "POST",
        body: formData
      });
      let result = await response.text();

      showToast(result.includes("✅"), result);
    });

    function showToast(success, message) {
      const toastElement = document.getElementById("toastMessage");
      const toastBody = toastElement.querySelector(".toast-body");

      toastElement.classList.remove("bg-danger", "bg-success");
      toastElement.classList.add(success ? "bg-success" : "bg-danger");

      toastBody.innerText = message;

      const toast = new bootstrap.Toast(toastElement);
      toast.show();
    }
  </script>
</body>
</html>