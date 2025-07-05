// dashboard.js
document.addEventListener("DOMContentLoaded", function () {
  const todoList = document.getElementById("todoList");
  const heading = document.getElementById("todoHeading");
  const noData = document.getElementById("noData");

  function loadTodos() {
    fetch("fetch_todos.php")
      .then(res => res.json())
      .then(data => {
        todoList.innerHTML = "";

        if (data.length === 0) {
          heading.style.display = "none";
          noData.style.display = "flex";
        } else {
          heading.style.display = "block";
          noData.style.display = "none";

          data.forEach(todo => {
            const card = document.createElement("div");
            card.className = "col-md-4";
            card.innerHTML = `
              <div class="card todo-card shadow-sm">
                <div class="card-body">
                  <h5 class="card-title d-flex justify-content-between">
                    <span>${todo.title}</span>
                    <span>
                      <button class="btn btn-sm btn-outline-primary me-1 edit-btn" 
                              data-id="${todo.id}" 
                              data-title="${todo.title}" 
                              data-description="${todo.description}">
                        <i class="bi bi-pencil"></i>
                      </button>
                      <button class="btn btn-sm btn-outline-danger delete-btn" 
                              data-id="${todo.id}">
                        <i class="bi bi-trash"></i>
                      </button>
                    </span>
                  </h5>
                  <p class="card-text">${todo.description}</p>
                </div>
              </div>
            `;
            todoList.appendChild(card);
          });
        }
      });
  }

  loadTodos();
  window.loadTodos = loadTodos;

  // Delete handler
  document.addEventListener("click", function (e) {
    if (e.target.closest(".delete-btn")) {
      const id = e.target.closest(".delete-btn").dataset.id;
      if (confirm("Are you sure you want to delete this task?")) {
        const formData = new FormData();
        formData.append("id", id);

        fetch("delete_todo.php", {
          method: "POST",
          body: formData
        })
          .then(res => res.json())
          .then(data => {
            alert(data.message);
            if (data.status === "success") loadTodos();
          });
      }
    }
  });

  // Edit open handler
  document.addEventListener("click", function (e) {
    if (e.target.closest(".edit-btn")) {
      const btn = e.target.closest(".edit-btn");
      document.getElementById("edit_id").value = btn.dataset.id;
      document.getElementById("edit_title").value = btn.dataset.title;
      document.getElementById("edit_description").value = btn.dataset.description;

      const modal = new bootstrap.Modal(document.getElementById("editTaskModal"));
      modal.show();
    }
  });

  // Edit submit handler
  document.getElementById("editForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData();
    formData.append("id", document.getElementById("edit_id").value);
    formData.append("title", document.getElementById("edit_title").value);
    formData.append("description", document.getElementById("edit_description").value);

    fetch("update_todo.php", {
      method: "POST",
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        if (data.status === "success") {
          loadTodos();
          const modal = bootstrap.Modal.getInstance(document.getElementById("editTaskModal"));
          modal.hide();
        }
      });
  });
});
