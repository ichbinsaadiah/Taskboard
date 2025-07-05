document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("registerForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData();
    formData.append("name", document.getElementById("name").value);
    formData.append("email", document.getElementById("email").value);
    formData.append("password", document.getElementById("password").value);

    fetch("register.php", {
      method: "POST",
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        const resultDiv = document.getElementById("result");
        resultDiv.textContent = data.message;
        resultDiv.className = data.status === "success" ? "text-success" : "text-danger";
      })
      .catch(error => {
        console.error("Error:", error);
      });
  });
});
