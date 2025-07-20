# ✅ TaskBoard

**TaskBoard** is a sleek and responsive task management web app designed to help users organize their daily workflow with ease. It features a clean Bootstrap 5 UI, dynamic task management with add/edit/delete features, and secure session-based login — making it a simple yet powerful productivity tool.

---

## 🚀 Features

📋 **Task Management**
- Add new to-dos with title & description
- Edit existing tasks in a modal form
- Delete tasks with confirmation
- Responsive card-based layout for tasks
- Filter tasks by category
- Collapse/expand completed tasks section

🔐 **User Authentication**
- Session-based login system
- Secure user registration
- Auto-redirect for unauthorized access

🎨 **UI/UX Highlights**
- Animated logo and button icons
- Custom modal for task input
- Empty state image when no tasks exist
- Bootstrap 5 + custom styles
- Mobile-friendly, clean layout

🛠️ **Modular Architecture**
- Separate JS files for logic and UI
- Organized file structure (CSS, JS, PHP, assets)
- Uses Bootstrap Icons for consistent UI

---

## 🛠️ Tech Stack

| Layer       | Tools/Languages              |
|-------------|------------------------------|
| Frontend    | HTML5, Bootstrap 5, JavaScript |
| Backend     | PHP (procedural, no framework) |
| Database    | MySQL (with MySQLi extension)  |
| Auth        | PHP Sessions                  |

---

## 📁 Folder Structure

TaskBoard/
├── assets/
│ ├── css/style.css
│ ├── img/logo.png, task-icon.png, no-data.png
│ └── js/dashboard.js, todo_form.js
├── includes/
│ └── config.php
├── add_todo.php
├── fetch_todos.php
├── update_todo.php
├── delete_todo.php
├── fetch_categories.php
├── login.php / login.html
├── register.php / register.html
├── dashboard.php
└── README.md

---

## 🗂️ Database Schema

**Database:** `taskboard`

### `users` table
| Column       | Type         |
|--------------|--------------|
| id           | INT, AUTO_INCREMENT, PRIMARY KEY |
| name         | VARCHAR(100) |
| email        | VARCHAR(100), UNIQUE |
| password     | VARCHAR(255) |
| created_at   | TIMESTAMP    |

### `todos` table
| Column       | Type         |
|--------------|--------------|
| id           | INT, AUTO_INCREMENT, PRIMARY KEY |
| user_id      | INT (foreign key)             |
| title        | VARCHAR(255)                  |
| description  | TEXT                          |
| list         | VARCHAR(100)                  |
| status       | VARCHAR(50)                   |
| created_at   | TIMESTAMP                     |

---

## 🧪 Setup Instructions

1. Clone this repository:
   ```bash
   git clone https://github.com/your-username/taskboard.git
Move the folder to your XAMPP htdocs directory:

C:\xampp\htdocs\TaskBoard
Import the SQL schema into your MySQL database (create taskboard DB and tables manually or via script)

Update your includes/config.php file:
$conn = new mysqli('localhost', 'root', '', 'taskboard');

Open the app in browser:
http://localhost/taskboard/login.php

---

## ✅ All Implemented Features
| Feature                            | Status |
| ---------------------------------- | ------ |
| 🔐 User Login/Registration         | ✅ Done |
| 📝 Add New Task (Modal Form)       | ✅ Done |
| ✏️ Edit Task                       | ✅ Done |
| 🗑️ Delete Task                    | ✅ Done |
| 📦 Task Cards                      | ✅ Done |
| 🖼️ Empty State UI                 | ✅ Done |
| ⚙️ Modular PHP + JS Code           | ✅ Done |
| 🎨 Bootstrap 5 + Custom Design     | ✅ Done |
| 📂 Category Dropdown + Color Badge | ✅ Done |
| 📁 Category Filtering              | ✅ Done |
| ✅ Completed Tasks Collapse UI      | ✅ Done |

---

## 🔮 Planned Enhancements
-- ⏳ Task statuses (Pending, In Progress, Done)
-- ⏰ Due dates & reminders
-- 🔍 Search functionality
-- 📊 Dashboard analytics (e.g. % completed)
-- 🧩 Google Login (OAuth2)
-- 🌍 Multi-language support (i18n)
-- 📬 Email notifications and summaries

---

## 🤝 Contributing
Have ideas to improve TaskBoard?
Fork this repo, make changes, and submit a pull request — contributions are welcome!

---

## 🧑‍💻 Author
Designed and developed by Saadiah Khan
🌐 Personal learning project to explore full-stack PHP web development