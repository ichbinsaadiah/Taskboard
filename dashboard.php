<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
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
</head>
<body class="bg-light">
<div class="container-fluid px-4 py-3">

  <!-- Top Header -->
  <div class="header-bar mb-4">
    <div class="header-left">
      <img src="assets/img/logo.png" alt="Logo" class="rounded-circle logo-img logo-bounce">
      <h4 class="mb-0">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></h4>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">+ Add New Task</button>
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
            <label for="description" class="form-label">Description</label>
            <textarea id="description" class="form-control" rows="3"></textarea>
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

</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/dashboard.js"></script>
<script src="assets/js/todo_form.js"></script>

</body>
</html>