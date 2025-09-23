<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  require_once 'includes/config.php';

  if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];

    $stmt = $conn->prepare("SELECT id, name FROM users WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $user = $result->fetch_assoc();
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_name'] = $user['name'];
    } else {
      header("Location: login.html");
      exit;
    }
  } else {
    header("Location: login.html");
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>TaskBoard | Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <!-- Flatpickr CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

</head>

<body class="bg-light">
  <div class="d-flex">
  <!-- Sidebar -->
  <aside id="sidebar" class="bg-white border-end vh-100 p-3" style="width: 250px;">
    <!-- Profile -->
    <div class="text-center mb-4">
      <img src="assets/img/user.png" alt="User" class="rounded-circle mb-2" width="60">
      <h6 class="mb-0"><?= htmlspecialchars($_SESSION['user_name']); ?></h6>
      <small class="text-muted"><?= $_SESSION['user_email'] ?? ""; ?></small>
    </div>

    <!-- Menu -->
    <ul class="list-unstyled">
      <li><a href="#" class="d-flex align-items-center text-dark text-decoration-none"><i class="bi bi-brightness-alt-high me-2"></i> My Day</a></li>
      <li><a href="#" class="d-flex align-items-center text-dark text-decoration-none"><i class="bi bi-star me-2 text-danger"></i> Important</a></li>
      <li><a href="#" class="d-flex align-items-center text-dark text-decoration-none"><i class="bi bi-house me-2"></i> All Tasks</a></li>
      <hr>
    </ul>
  </aside>

  <!-- Main Dashboard -->
  <main id="mainContent" class="flex-grow-1 p-4" style="margin-left: 250px;">
    <div class="container-fluid px-4 py-3">

      <!-- Top Header -->
      <div class="header-bar mb-4">
        <div class="header-left">
          <img src="assets/img/logo.png" alt="Logo" class="rounded-circle logo-img logo-bounce">
          <h4 class="mb-0">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></h4>
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">+ Add New Task</button>
          <a href="logout.php" class="btn btn-danger d-flex align-items-center gap-1 shadow-sm px-3 rounded-pill">
            <i class="bi bi-box-arrow-right"></i> Logout
          </a>
        </div>
      </div>

      <!-- Search and Filter -->
      <div class="row mb-3">
        <div class="col-md-6">
          <div class="position-relative">
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0">
                <i class="bi bi-search text-muted"></i>
              </span>
              <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Search tasks...">
            </div>
            <button id="clearSearch"
              class="btn position-absolute top-50 end-0 translate-middle-y me-2 invisible opacity-0"
              type="button"
              style="border: none; background: transparent; transition: opacity 0.3s; z-index: 5;">
              <i class="bi bi-x text-muted"></i>
            </button>
          </div>
        </div>

        <div class="col-md-6">
          <select id="categoryFilter" class="form-select">
            <option value="All">All Categories</option>
            <option value="Inbox">Inbox</option>
            <option value="Work">Work</option>
            <option value="Personal">Personal</option>
            <option value="Shopping">Shopping</option>
            <option value="Errands">Errands</option>
            <option value="Health">Health</option>
            <option value="Study">Study</option>
            <option value="Projects">Projects</option>
            <option value="Ideas">Ideas</option>
            <option value="Planning">Planning</option>
          </select>
        </div>
      </div>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="addTaskModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addTaskLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form id="taskModalForm" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title d-flex align-items-center gap-2" id="addTaskLabel">
              <img src="assets/img/task-icon.png" width="24" height="24" class="icon-animate" alt="icon">
              Add New Task
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="title" class="form-label">To-Do Title</label>
              <input type="text" id="title" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="list" class="form-label">Category</label>
              <select id="list" name="list" class="form-select" required>
                <option value="Inbox">Inbox</option>
                <option value="Work">Work</option>
                <option value="Personal">Personal</option>
                <option value="Shopping">Shopping</option>
                <option value="Errands">Errands</option>
                <option value="Health">Health</option>
                <option value="Study">Study</option>
                <option value="Projects">Projects</option>
                <option value="Ideas">Ideas</option>
                <option value="Planning">Planning</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea id="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Due Date</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                <button type="button" id="dueDateSelector" class="form-control text-start bg-white" data-bs-toggle="dropdown" aria-expanded="false">
                  No due date
                </button>
                <ul class="dropdown-menu w-100" id="dueDateDropdown">
                  <li><a class="dropdown-item due-option" data-offset="0">Today</a></li>
                  <li><a class="dropdown-item due-option" data-offset="1">Tomorrow</a></li>
                  <li><a class="dropdown-item due-option" data-offset="7">Next Week</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item" id="pickDateOption">Pick a date...</a></li>
                </ul>
                <!-- Cancel button -->
                <button type="button" id="clearDueDate"
                  class="btn btn-sm btn-outline-secondary">
                  <i class="bi bi-x-circle"></i>
                </button>
              </div>

              <!-- Hidden input for flatpickr -->
              <input type="hidden" id="due_date" name="due_date">
            </div>

            <div id="formMessage" class="mt-2"></div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Add Task</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Edit Task Modal -->
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form id="editForm" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Task</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="edit_id">

            <div class="mb-3">
              <label for="edit_title" class="form-label">Title</label>
              <input type="text" id="edit_title" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="edit_description" class="form-label">Description</label>
              <textarea id="edit_description" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
              <label for="edit_list" class="form-label">Category</label>
              <select id="edit_list" class="form-select">
                <option value="Inbox">Inbox</option>
                <option value="Work">Work</option>
                <option value="Personal">Personal</option>
                <option value="Shopping">Shopping</option>
                <option value="Errands">Errands</option>
                <option value="Health">Health</option>
                <option value="Study">Study</option>
                <option value="Projects">Projects</option>
                <option value="Ideas">Ideas</option>
                <option value="Planning">Planning</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Due Date</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                <button type="button" id="editDueDateSelector" class="form-control text-start bg-white" data-bs-toggle="dropdown" aria-expanded="false">
                  No due date
                </button>
                <ul class="dropdown-menu w-100" id="editDueDateDropdown">
                  <li><a class="dropdown-item edit-due-option" data-offset="0">Today</a></li>
                  <li><a class="dropdown-item edit-due-option" data-offset="1">Tomorrow</a></li>
                  <li><a class="dropdown-item edit-due-option" data-offset="7">Next Week</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item" id="editPickDateOption">Pick a date...</a></li>
                </ul>
                <button type="button" id="editClearBtn"
                  class="btn btn-sm btn-outline-secondary">
                  <i class="bi bi-x-circle"></i>
                </button>
              </div>
              <!-- Hidden input for storing date -->
              <input type="hidden" id="edit_due_date" name="edit_due_date">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Update Task</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Heading and Placeholder -->
    <h4 id="todoHeading" class="mb-3" style="display: none;">Your To-Do Lists</h4>

    <div id="noData" class="flex-column justify-content-center align-items-center text-center" style="height: 60vh; display: none;">
      <img src="assets/img/no-data.png" alt="No Tasks" class="mb-3" style="max-width: 220px;">
      <p class="text-muted fs-5">No tasks found yet. Start by adding one!</p>
    </div>

    <!-- Task List Container (will be added by JS) -->
    <div id="todoList" class="row g-4"></div>

    <!-- Completed Tasks Section -->
    <div id="completedSection" class="mt-4" style="display: none;">
      <button class="btn btn-outline-secondary w-100" data-bs-toggle="collapse" data-bs-target="#completedTasks" aria-expanded="false" aria-controls="completedTasks">
        Show Completed Tasks
      </button>
      <div class="collapse mt-3" id="completedTasks">
        <h5>Completed Tasks</h5>
        <div id="completedList" class="row g-3"></div>
      </div>
    </div>

  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/dashboard.js"></script>
  <!-- Flatpickr JS -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="assets/js/todo_form.js"></script>

</body>

</html>