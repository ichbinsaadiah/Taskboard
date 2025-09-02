document.addEventListener("DOMContentLoaded", function () {
  const todoList = document.getElementById("todoList");
  const heading = document.getElementById("todoHeading");
  const noData = document.getElementById("noData");
  const completedSection = document.getElementById("completedSection");
  const completedList = document.getElementById("completedList");
  const searchInput = document.getElementById("searchInput");
  const clearSearch = document.getElementById("clearSearch");
  const dueDateInput = document.getElementById("due_date");
  const dueDateSelector = document.getElementById("dueDateSelector");
  const clearBtn = document.getElementById("clearDueDate");
  const editClearBtn = document.getElementById("editClearBtn");
  const editDueDateInput = document.getElementById("edit_due_date");
  const editDueDateSelector = document.getElementById("editDueDateSelector");

  // Cancel button handler
  clearBtn.addEventListener("click", function () {
    dueDateInput.value = ""; // clear hidden field
    dueDateSelector.textContent = "No due date"; // reset visible button text
  });

  if (editClearBtn) {
    editClearBtn.addEventListener("click", function () {
      editDueDateInput.value = ""; // clear hidden field
      editDueDateSelector.textContent = "No due date"; // reset visible button text
    });
  }

  // Show/hide clear button with fade
  searchInput.addEventListener("input", () => {
    if (searchInput.value.trim() !== "") {
      clearSearch.classList.remove("invisible", "opacity-0");
    } else {
      clearSearch.classList.add("invisible", "opacity-0");
    }
    loadTodos();
  });

  // Clear search on button click
  clearSearch.addEventListener("click", () => {
    searchInput.value = "";
    clearSearch.classList.add("invisible", "opacity-0");
    loadTodos();
  });

  function loadTodos() {
    fetch("fetch_todos.php")
      .then(res => res.json())
      .then(data => {
        const selectedCategory = document.getElementById("categoryFilter").value;
        const searchTerm = document.getElementById("searchInput").value.toLowerCase().trim();

        const filteredData = data.filter(todo => {
          const categoryMatch =
            selectedCategory === "All" || (todo.list || "Inbox").trim() === selectedCategory;
          const searchMatch =
            todo.title.toLowerCase().includes(searchTerm) ||
            (todo.description || "").toLowerCase().includes(searchTerm);
          return categoryMatch && searchMatch;
        });

        // Reset sections
        todoList.innerHTML = "";
        completedList.innerHTML = "";
        completedSection.style.display = "none";
        let hasCompleted = false;

        if (filteredData.length === 0) {
          heading.style.display = "none";
          noData.style.display = "flex";
        } else {
          heading.style.display = "block";
          noData.style.display = "none";

          filteredData.forEach(todo => {
            const card = document.createElement("div");
            card.className = "col-md-4";

            const categoryColors = {
              Inbox: "primary",
              Work: "info",
              Personal: "success",
              Shopping: "warning",
              Errands: "danger",
              Health: "secondary",
              Study: "dark",
              Projects: "primary",
              Ideas: "info",
              Planning: "success"
            };

            const badgeColor = categoryColors[todo.list?.trim()] || "secondary";

            let dueDateBadge = "";
            if (todo.due_date && !isNaN(new Date(todo.due_date))) {
              const options = { weekday: "short", month: "short", day: "2-digit", year: "numeric" };
              dueDateBadge = `<span class="badge bg-info ms-2">${new Date(todo.due_date).toLocaleDateString("en-US", options)}</span>`;
            }

            card.innerHTML = `
  <div class="card todo-card shadow-sm h-100 d-flex flex-column">
    <div class="card-body d-flex flex-column">
      <h5 class="card-title d-flex justify-content-between align-items-start">
        <div class="d-flex align-items-center gap-2">
          <input class="form-check-input task-checkbox border-dark" type="checkbox"
            data-id="${todo.id}" style="width: 1em; height: 1em;"
            ${(todo.status || '').toLowerCase() === 'completed' ? 'checked' : ''}>
          <span class="task-title mb-0 ${(todo.status || '').toLowerCase() === 'completed' ? 'completed-task' : ''}">
            ${todo.title}
          </span>
        </div>
        <span>
          <button class="btn btn-sm btn-outline-primary me-1 edit-btn"
                  data-id="${todo.id}"
                  data-title="${todo.title}"
                  data-description="${todo.description}"
                  data-list="${todo.list || ''}"
                  data-due_date="${todo.due_date || ''}">
            <i class="bi bi-pencil"></i>
          </button>
          <button class="btn btn-sm btn-outline-danger delete-btn"
                  data-id="${todo.id}">
            <i class="bi bi-trash"></i>
          </button>
        </span>
      </h5>
      <p class="card-text ${(todo.status || '').toLowerCase() === 'completed' ? 'completed-task' : ''}">
        ${todo.description}
      </p>
      <div class="justify-content-between align-items-center">
        <span class="badge bg-${badgeColor} text-light">${(todo.list || 'Inbox').trim()}</span>
        ${dueDateBadge}
      </div>
    </div>
  </div>
`;

            if ((todo.status || '').toLowerCase() === 'completed') {
              completedList.appendChild(card);
              hasCompleted = true;
            } else {
              todoList.appendChild(card);
            }
          });

          if (hasCompleted) {
            completedSection.style.display = "block";
          }
        }
      });
  }

  loadTodos();
  window.loadTodos = loadTodos;

  // Filters
  document.getElementById("categoryFilter").addEventListener("change", loadTodos);

  // Checkbox handler
  document.addEventListener("change", function (e) {
    if (e.target.classList.contains("task-checkbox")) {
      const taskId = e.target.dataset.id;
      const isChecked = e.target.checked;
      const newStatus = isChecked ? "completed" : "pending";

      fetch("update_todo.php", {
        method: "POST",
        body: new URLSearchParams({ id: taskId, status: newStatus })
      })
        .then(res => res.json())
        .then(data => {
          if (data.status === "success") {
            console.log("Status updated:", newStatus);
            loadTodos();
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

        fetch("delete_todo.php", { method: "POST", body: formData })
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
    if (!editBtn) return;

    document.getElementById("edit_id").value = editBtn.dataset.id;
    document.getElementById("edit_title").value = editBtn.dataset.title;
    document.getElementById("edit_description").value = editBtn.dataset.description;
    document.getElementById("edit_due_date").value = editBtn.dataset.due_date || "";
    const dueDateValue = editBtn.dataset.due_date;
    document.getElementById("edit_due_date").value = dueDateValue || "";

    if (dueDateValue && !isNaN(new Date(dueDateValue))) {
      document.getElementById("editDueDateSelector").textContent = new Date(dueDateValue).toDateString();
    } else {
      document.getElementById("editDueDateSelector").textContent = "No due date";
    }

    populateCategoryDropdown("edit_list");

    setTimeout(() => {
      const currentCategory = editBtn.dataset.list;
      const select = document.getElementById("edit_list");
      if (select && currentCategory) {
        [...select.options].forEach(opt => {
          if (opt.value === currentCategory) opt.selected = true;
        });
      }
    }, 100);

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
    formData.append("due_date", document.getElementById("edit_due_date").value);

    console.log("Updating with:", {
      id: document.getElementById("edit_id").value,
      title: document.getElementById("edit_title").value,
      description: document.getElementById("edit_description").value,
      list: document.getElementById("edit_list").value
    });

    fetch("update_todo.php", { method: "POST", body: formData })
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
  fetch("fetch_categories.php")
    .then(res => res.json())
    .then(data => {
      const select = document.getElementById(selectId);
      select.innerHTML = "";

      const defaultOptions = ["Inbox", "Work", "Personal", "Shopping", "Errands", "Health", "Study", "Ideas", "Planning"];
      const uniqueCategories = new Set([...defaultOptions, ...data]);

      uniqueCategories.forEach(cat => {
        const opt = document.createElement("option");
        opt.value = cat;
        opt.textContent = cat;
        select.appendChild(opt);
      });

      if (typeof callback === "function") {
        callback();
      }
    });
}