// todo_form.js
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("taskModalForm");
  const messageBox = document.getElementById("formMessage");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData();
    formData.append("title", document.getElementById("title").value);
    formData.append("description", document.getElementById("description").value);

    fetch("add_todo.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      messageBox.textContent = data.message;
      messageBox.className = data.status === "success" ? "text-success" : "text-danger";

      if (data.status === "success") {
        form.reset();

        // ✅ Wait briefly before fetching the updated list
        setTimeout(() => {
          if (window.loadTodos) window.loadTodos();
        }, 200);

        // ✅ Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById("addTaskModal"));
        modal.hide();
      }
    });
  });
});
