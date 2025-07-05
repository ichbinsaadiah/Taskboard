document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData();
    formData.append("email", document.getElementById("email").value);
    formData.append("password", document.getElementById("password").value);

    fetch("login.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      const result = document.getElementById("result");
      result.textContent = data.message;
      result.className = data.status === "success" ? "text-success" : "text-danger";

      if (data.status === "success") {
        // Redirect to dashboard
        setTimeout(() => {
          window.location.href = "dashboard.php";
        }, 1000);
      }
    })
    .catch(err => {
      console.error("Login error:", err);
    });
  });
});
