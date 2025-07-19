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
  
    const categoryColors = {
    "Inbox": "primary",
    "Work": "info",
    "Personal": "success",
    "Shopping": "warning",
    "Errands": "danger",
    "Health": "secondary",
    "Study": "dark",
    "Projects": "primary",
    "Ideas": "info",
    "Planning": "success"
  };

  const badgeColor = categoryColors[todo.list?.trim()] || "secondary";

  card.innerHTML = `
  <div class="card todo-card shadow-sm">
    <div class="card-body">
      <h5 class="card-title d-flex justify-content-between align-items-start">
        <div class="d-flex align-items-center gap-2">
  <input class="form-check-input task-checkbox border-dark" type="checkbox" data-id="${todo.id}" style="width: 1em; height: 1em;" ${(todo.status || '').toLowerCase() === 'completed' ? 'checked' : ''}>
  <span class="task-title mb-0 ${(todo.status || '').toLowerCase() === 'completed' ? 'completed-task' : ''}">${todo.title}</span>
</div>
        <span>
          <button class="btn btn-sm btn-outline-primary me-1 edit-btn" 
                  data-id="${todo.id}" 
                  data-title="${todo.title}" 
                  data-description="${todo.description}"
                  data-list="${todo.list || ''}">
            <i class="bi bi-pencil"></i>
          </button>
          <button class="btn btn-sm btn-outline-danger delete-btn" 
                  data-id="${todo.id}">
            <i class="bi bi-trash"></i>
          </button>
        </span>
      </h5>
      <p class="card-text ${(todo.status || '').toLowerCase() === 'completed' ? 'completed-task' : ''}">${todo.description}</p>
      <span class="badge bg-${badgeColor} text-light">${todo.list?.trim() || 'Inbox'}</span>
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

  document.addEventListener("change", function (e) {
  if (e.target.classList.contains("task-checkbox")) {
    const taskId = e.target.dataset.id;
    const isChecked = e.target.checked;
    const newStatus = isChecked ? "completed" : "pending";

    // Call PHP backend to update status
    fetch("update_todo.php", {
      method: "POST",
      body: new URLSearchParams({
        id: taskId,
        status: newStatus
      })
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === "success") {
        console.log("Status updated:", newStatus);
        loadTodos(); // reload tasks after update
      } else {
        alert("Failed to update status");
      }
    });
  }
});

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
  const editBtn = e.target.closest(".edit-btn");

  // ✅ Add null check before using dataset
  if (!editBtn) return;

  document.getElementById("edit_id").value = editBtn.dataset.id;
  document.getElementById("edit_title").value = editBtn.dataset.title;
  document.getElementById("edit_description").value = editBtn.dataset.description;

  // Load categories into dropdown
  populateCategoryDropdown("edit_list");

  // Select the current category after dropdown loads
  setTimeout(() => {
    const currentCategory = editBtn.dataset.list;
    const select = document.getElementById("edit_list");

    if (select && currentCategory) {
      [...select.options].forEach(opt => {
        if (opt.value === currentCategory) opt.selected = true;
      });
    }
  }, 100);

  // Show the modal
  const modal = new bootstrap.Modal(document.getElementById("editTaskModal"));
  modal.show();
});

  // Edit submit handler
  document.getElementById("editForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData();
    formData.append("id", document.getElementById("edit_id").value);
    formData.append("title", document.getElementById("edit_title").value);
    formData.append("description", document.getElementById("edit_description").value);
    formData.append("list", document.getElementById("edit_list").value);

    console.log("Updating with:", {
  id: document.getElementById("edit_id").value,
  title: document.getElementById("edit_title").value,
  description: document.getElementById("edit_description").value,
  list: document.getElementById("edit_list").value
});


    fetch("update_todo.php", {
      method: "POST",
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        if (data.status === "success") {
          loadTodos();
          populateCategoryDropdown("edit_list");
          const modalEl = document.getElementById("editTaskModal");
          const modalInstance = bootstrap.Modal.getInstance(modalEl);
          if (modalInstance) modalInstance.hide();
        }
      });
  });
});

function populateCategoryDropdown(selectId, callback) {
  fetch('fetch_categories.php')
    .then(res => res.json())
    .then(data => {
      const select = document.getElementById(selectId);
      select.innerHTML = ''; // clear existing

      const defaultOptions = ["Inbox", "Work", "Personal", "Shopping", "Errands", "Health", 'Study', "Ideas", "Planning"];
      const uniqueCategories = new Set([...defaultOptions, ...data]);

      uniqueCategories.forEach(cat => {
        const opt = document.createElement("option");
        opt.value = cat;
        opt.textContent = cat;
        select.appendChild(opt);
      });

      if (typeof callback === 'function') {
        callback();
      }
    });
}

