// todo_form.js
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("taskModalForm");
  const messageBox = document.getElementById("formMessage");

  const cancelBtn = document.getElementById("cancelAddTask");
  if (cancelBtn) {
    cancelBtn.addEventListener("click", () => {
      form.reset();
      dueDateHiddenInput.value = "";
      dueDateSelector.textContent = "No due date";
      document.getElementById("list").value = "Inbox";
      messageBox.textContent = '';
    });
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData();
    formData.append("title", document.getElementById("title").value);
    formData.append("description", document.getElementById("description").value);
    formData.append("status", "Pending");
    formData.append("list", document.getElementById("list").value);
    formData.append("due_date", document.getElementById("due_date").value);

    fetch("add_todo.php", {
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
        messageBox.textContent = data.message;
        messageBox.className = data.status === "success" ? "text-success" : "text-danger";

        if (data.status === "success") {
          // Reset the form properly
          form.reset();

          // Reset due date + list to defaults
          dueDateHiddenInput.value = "";
          dueDateSelector.textContent = "No due date";
          document.getElementById("list").value = "Inbox";

          // Reload todos immediately
          if (window.loadTodos) window.loadTodos();

          // Close modal
          const modal = bootstrap.Modal.getInstance(document.getElementById("addTaskModal"));
          modal.hide();

          // Clear success message after short delay (user sees feedback)
          setTimeout(() => {
            messageBox.textContent = '';
          }, 1000);
        }

        // Extra small buffer for async DB write
        setTimeout(() => {
          if (window.loadTodos) window.loadTodos();
        }, 200);
      })
      .catch(err => {
        messageBox.textContent = "Error: " + err.message;
        messageBox.className = "text-danger";
      });
  });


  // Due Date
  const dueDateSelector = document.getElementById("dueDateSelector");
  const dueDateHiddenInput = document.getElementById("due_date");

  document.querySelectorAll(".due-option").forEach(option => {
    option.addEventListener("click", function () {
      const offset = parseInt(this.getAttribute("data-offset"), 10);
      const selectedDate = new Date();
      selectedDate.setDate(selectedDate.getDate() + offset);

      const yyyy = selectedDate.getFullYear();
      const mm = String(selectedDate.getMonth() + 1).padStart(2, "0");
      const dd = String(selectedDate.getDate()).padStart(2, "0");
      const formatted = `${yyyy}-${mm}-${dd}`;

      dueDateHiddenInput.value = formatted;
      dueDateSelector.textContent = this.textContent;
    });
  });

  // Flatpickr (popup anchored to the button)
  const fp = flatpickr(dueDateSelector, {
    dateFormat: "Y-m-d",
    clickOpens: true,   // open directly on button click
    allowInput: false,
    onChange: function (selectedDates, dateStr) {
      if (dateStr) {
        dueDateHiddenInput.value = dateStr;
        dueDateSelector.textContent = new Date(dateStr).toDateString(); // show nicely
      }
    }
  });

  // Keep "Pick a date" handler (still closes dropdown before opening)
  document.getElementById("pickDateOption").addEventListener("click", (e) => {
    e.preventDefault();
    const dd = bootstrap.Dropdown.getOrCreateInstance(dueDateSelector);
    dd.hide();
    fp.open();
  });

  // ===================== Edit Modal Due Date =====================
  const editDueDateSelector = document.getElementById("editDueDateSelector");
  const editDueDateHiddenInput = document.getElementById("edit_due_date");

  const editFp = flatpickr(editDueDateSelector, {
    dateFormat: "Y-m-d",
    clickOpens: true,
    allowInput: false,
    onChange: function (selectedDates, dateStr) {
      if (dateStr) {
        editDueDateHiddenInput.value = dateStr;
        editDueDateSelector.textContent = new Date(dateStr).toDateString();
      }
    }
  });

  document.querySelectorAll(".edit-due-option").forEach(option => {
    option.addEventListener("click", function () {
      const offset = parseInt(this.getAttribute("data-offset"), 10);
      const selectedDate = new Date();
      selectedDate.setDate(selectedDate.getDate() + offset);

      const yyyy = selectedDate.getFullYear();
      const mm = String(selectedDate.getMonth() + 1).padStart(2, "0");
      const dd = String(selectedDate.getDate()).padStart(2, "0");
      const formatted = `${yyyy}-${mm}-${dd}`;

      editDueDateHiddenInput.value = formatted;
      editDueDateSelector.textContent = this.textContent;
    });
  });

  // Open calendar when "Pick a date..." clicked
  document.getElementById("editPickDateOption").addEventListener("click", (e) => {
    e.preventDefault();
    const dd = bootstrap.Dropdown.getOrCreateInstance(editDueDateSelector);
    dd.hide();
    editFp.open();
  });
});