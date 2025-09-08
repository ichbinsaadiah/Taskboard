// login.js
document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.getElementById("loginForm");
  const result = document.getElementById("result");
  const emailInput = document.getElementById("email");
  const rememberMe = document.getElementById("rememberMe");

  // === Auto-fill saved email if Remember Me was checked before ===
  if (localStorage.getItem("rememberMe") === "true") {
    emailInput.value = localStorage.getItem("savedEmail") || "";
    rememberMe.checked = true;
  }

  loginForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData();
    formData.append("email", emailInput.value);
    formData.append("password", document.getElementById("password").value);
    formData.append("remember", rememberMe.checked ? "1" : "0");

    fetch("login.php", {
      method: "POST",
      body: formData
    })
      .then(res => {
        if (!res.ok) {
          throw new Error("Server error: " + res.status);
        }
        return res.json();
      })
      .then(data => {
        result.textContent = data.message;
        result.className = data.status === "success" ? "text-success" : "text-danger";

        if (data.status === "success") {
          // === Handle Remember Me ===
          if (rememberMe.checked) {
            localStorage.setItem("rememberMe", "true");
            localStorage.setItem("savedEmail", emailInput.value);
          } else {
            localStorage.removeItem("rememberMe");
            localStorage.removeItem("savedEmail");
          }

          // Redirect to dashboard
          setTimeout(() => {
            window.location.href = "dashboard.php";
          }, 1000);
        }
      })
      .catch(err => {
        console.error("Login error:", err);
        result.textContent = "Error: " + err.message;
        result.className = "text-danger";
      });
  });
});