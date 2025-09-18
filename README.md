# ✅ TaskBoard

**TaskBoard** is a sleek and responsive task management web app designed to help users organize their daily workflow with ease.  
It features a clean Bootstrap 5 UI, dynamic task management (add/edit/delete), secure session-based login, and a **secure forgot/reset password flow** powered by SendGrid.

---

## 🚀 Features

📋 **Task Management**
- Add new to-dos with title & description
- Edit existing tasks in a modal form
- Delete tasks with confirmation
- Responsive card-based layout for tasks
- Filter tasks by category
- Collapse/expand completed tasks section
- Due dates with quick-select (`Today`, `Tomorrow`, `Next Week`, `Pick a date…`)

🔐 **User Authentication**
- Session-based login system
- Secure user registration
- Auto-redirect for unauthorized access
- Remember Me with cookies + sessions
- Forgot Password with email reset link (via SendGrid)
- Strong password validation rules

🎨 **UI/UX Highlights**
- Animated logo and styled login UI
- Password strength checklist with live validation
- Bootstrap 5 + custom styles
- SweetAlert2 for beautiful alerts
- Mobile-friendly, clean layout

🛠️ **Modular Architecture**
- Separate JS files for logic and UI (`dashboard.js`, `todo_form.js`, `login.js`, etc.)
- Organized file structure (CSS, JS, PHP, assets, includes)
- Uses Bootstrap Icons for consistent UI

---

## 🛠️ Tech Stack

| Layer       | Tools/Languages              |
|-------------|------------------------------|
| Frontend    | HTML5, Bootstrap 5, JavaScript |
| Backend     | PHP (procedural, no framework) |
| Database    | MySQL (with MySQLi extension)  |
| Auth        | PHP Sessions + Cookies        |
| Email       | SendGrid API (via phpdotenv)  |

---

## 📁 Folder Structure

TaskBoard/
├── assets/
│ ├── css/style.css
│ ├── img/logo.png, task.jpg, no-data.png
│ └── js/dashboard.js, login.js, register.js, reset-password.js, todo_form.js
├── includes/
│ └── config.php
├── logs/ (ignored by git)
├── vendor/ (ignored by git)
├── .env (ignored by git)
├── add_todo.php
├── fetch_todos.php
├── update_todo.php
├── delete_todo.php
├── fetch_categories.php
├── login.php / login.html
├── register.php / register.html
├── forgot_password.php
├── reset_password.php
├── reset_password_process.php
├── dashboard.php
└── README.md

---

## 🗂️ Database Schema

**Database:** `taskboard`

### `users` table
| Column         | Type         |
|----------------|--------------|
| id             | INT, AUTO_INCREMENT, PRIMARY KEY |
| name           | VARCHAR(100) |
| email          | VARCHAR(100), UNIQUE |
| password       | VARCHAR(255) |
| remember_token | VARCHAR(255), NULL |
| created_at     | TIMESTAMP    |

### `todos` table
| Column       | Type         |
|--------------|--------------|
| id           | INT, AUTO_INCREMENT, PRIMARY KEY |
| user_id      | INT (foreign key)             |
| title        | VARCHAR(255)                  |
| description  | TEXT                          |
| list         | VARCHAR(100)                  |
| status       | VARCHAR(50)                   |
| due_date     | DATE, NULL                    |
| created_at   | TIMESTAMP                     |

### `password_resets` table
| Column     | Type         |
|------------|--------------|
| id         | INT, AUTO_INCREMENT, PRIMARY KEY |
| email      | VARCHAR(100) |
| token      | VARCHAR(255) |
| expires_at | DATETIME     |

---

## 🧪 Setup Instructions

1. Clone this repository:
   ```bash
   git clone https://github.com/your-username/taskboard.git

2. Move the folder to your XAMPP htdocs:
C:\xampp\htdocs\TaskBoard

3. Install dependencies:
composer install

4. Create .env file (not tracked by Git):
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=taskboard

SENDGRID_API_KEY=your_sendgrid_api_key
APP_URL=http://localhost/TaskBoard

5. Import the database schema into MySQL (create taskboard DB + tables).

6. Start XAMPP and open:
http://localhost/TaskBoard/login.php

---

✅ Implemented Features
Feature	Status
|🔐 User Login/Registration | ✅ Done |
|🔑 Forgot Password (SendGrid) | ✅ Done |
|🔒 Reset Password (token-based)	| ✅ Done |
|📝 Add/Edit/Delete Tasks | ✅ Done |
|📂 Category Dropdown + Badges	| ✅ Done |
|📁 Category Filtering | ✅ Done |
|⏰ Due Dates with Calendar Picker | ✅ Done |
|✅ Completed Tasks Collapse | ✅ Done |
|🎨 Styled UI + SweetAlert | ✅ Done |

🔮 Planned Enhancements
- ⏳ Task statuses (Pending, In Progress, Done)
- 📊 Dashboard analytics (e.g. % completed)
- 📬 Email daily/weekly task summaries
- 🧩 Google Login (OAuth2)
- 🌍 Multi-language support (i18n)
- 🛡️ Brute-force protection on login & reset

---

🤝 Contributing
Got ideas to improve TaskBoard?
Fork this repo, make your changes, and submit a pull request — contributions are welcome!

---

🧑‍💻 Author
Designed and developed by Saadiah Khan
🌐 Personal learning project to explore full-stack PHP web development

---

✅ This version includes your new **forgot/reset password flow, .env setup, SendGrid integration, and updated DB schema**.  