# Task Management System

A simple and efficient task management web application built with Laravel. This system allows teams to organize daily work, track task progress, and manage tasks through a clean admin interface.

---

## Live Demo

- **Application URL:** `your-live-url-here`
- **Demo Video (Loom):** `your-loom-video-link-here`
- **GitHub Repository:** `your-github-repo-link-here`

---

## Features

- Create, update, and delete tasks
- Track task status — Pending, In Progress, Completed
- Set task priority — Low, Medium, High
- Set due dates with overdue detection
- Quick status update via dropdown (AJAX — no page reload)
- Server-side DataTable with search and pagination
- Form validation with meaningful error messages
- Flash notifications on every action
- Error logging for all critical operations

---

## Technologies Used

| Layer | Technology |
|---|---|
| Backend Framework | Laravel 11 |
| Language | PHP 8.2+ |
| Database | MySQL |
| Frontend | AdminLTE 3 (Bootstrap 4) |
| DataTable | Yajra Laravel DataTables |
| Testing | PHPUnit (Laravel built-in) |
| Version Control | Git |

---

## Requirements

- PHP >= 8.2
- Composer
- MySQL
- Node.js & NPM
- Laravel 11

---

## Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database

Open `.env` file and update your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Install Frontend Dependencies

```bash
npm install
npm run dev
```

### 7. Start the Application

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

---

## Running Tests

This project uses PHPUnit for unit and feature testing.

### Run All Tests

```bash
composer test
```

### Run Only Unit Tests

```bash
php artisan test --testsuite=Unit
```

### Run Only Feature Tests

```bash
php artisan test --testsuite=Feature
```

### Run a Specific Test File

```bash
php artisan test tests/Feature/TaskControllerTest.php
php artisan test tests/Unit/TaskModelTest.php
```

---

## Testing Approach

### What is Tested

**Unit Tests** (`tests/Unit/TaskModelTest.php`) — tests the Task model in isolation:

- Fillable fields are correctly defined
- Task can be created using factory
- Status only accepts valid values (pending, in_progress, completed)
- Priority only accepts valid values (low, medium, high)
- Description field accepts null
- Due date field accepts null

**Feature Tests** (`tests/Feature/TaskControllerTest.php`) — tests HTTP behavior end-to-end:

- Index page loads successfully
- Create page is accessible
- Task is stored in database with valid data
- Task can be created without optional fields (description, due_date)
- Validation rejects missing title
- Validation rejects invalid status value
- Validation rejects invalid priority value
- Validation rejects past due dates
- Edit page loads with correct task data
- Task is updated correctly in database
- Validation rejects update with missing title
- Task is deleted and removed from database
- Deleting a non-existent task returns error response
- Task status updates correctly via AJAX
- Invalid status value is rejected on status update

### Testing Strategy

- **SQLite in-memory database** is used during testing — no real database is affected
- **`RefreshDatabase` trait** resets the database between each test for isolation
- **`withoutMiddleware()`** is used in `TestCase.php` to bypass authentication during tests
- **Task Factory** generates realistic fake data for tests using Faker
- Tests are written to be independent — each test sets up its own data

---

## Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── TaskController.php       # CRUD + status update logic
│   │   └── Requests/
│   │       ├── StoreTaskRequest.php     # Validation for creating tasks
│   │       └── UpdateTaskRequest.php    # Validation for updating tasks
│   └── Models/
│       └── Task.php                     # Task eloquent model
│
├── database/
│   ├── factories/
│   │   └── TaskFactory.php              # Fake data generator for tests
│   └── migrations/
│       └── xxxx_create_tasks_table.php  # Tasks table schema
│
├── resources/views/admin/tasks/
│   ├── index.blade.php                  # Task list with DataTable
│   ├── create.blade.php                 # Add new task form
│   └── edit.blade.php                   # Edit existing task form
│
├── routes/
│   └── web.php                          # Application routes
│
├── tests/
│   ├── Unit/
│   │   └── TaskModelTest.php            # Model unit tests
│   └── Feature/
│       └── TaskControllerTest.php       # Controller feature tests
│
└── phpunit.xml                          # PHPUnit configuration
```

---

## API / Routes

| Method | URI | Name | Description |
|---|---|---|---|
| GET | `/tasks` | `tasks.index` | List all tasks |
| GET | `/tasks/create` | `tasks.create` | Show create form |
| POST | `/tasks` | `tasks.store` | Store new task |
| GET | `/tasks/{task}` | `tasks.show` | Show edit form |
| PUT/PATCH | `/tasks/{task}` | `tasks.update` | Update task |
| DELETE | `/tasks/{task}` | `tasks.destroy` | Delete task |
| POST | `/task-status-update` | `task.statusUpdate` | Update task status via AJAX |

---

## Assumptions & Decisions Made

**1. Status values are fixed**
Three statuses are supported: `pending`, `in_progress`, and `completed`. These cover the standard task lifecycle and were deemed sufficient for the client's needs.

**2. Priority is required**
Priority (`low`, `medium`, `high`) is a required field. It was added to help teams focus on what matters most, which aligns with the client's goal of efficient daily work management.

**3. Due date is optional**
Not all tasks have a fixed deadline. The due date field is optional but validates that future dates are selected when provided.

**4. No authentication system was built from scratch**
The project uses an existing `auth_check` middleware already present in the codebase. The middleware is bypassed during testing using `withoutMiddleware()` to keep tests focused on business logic.

**5. DataTables for task listing**
Yajra DataTables was used for server-side rendering of the task list. This handles search, pagination, and ordering efficiently even with large datasets.

**6. AJAX for status updates**
Status changes are handled via AJAX dropdown to allow quick updates without full page reloads, improving the user experience.

**7. SQLite for testing**
Tests run on an in-memory SQLite database to ensure speed and isolation. This avoids any risk of test data polluting the development or production database.

**8. Soft deletes not implemented**
Hard deletes were used for simplicity. Soft deletes could be added in a future iteration if task history becomes a requirement.

---

## Notes

- Flash notifications use the `alert-type` session key — make sure the layout blade handles `success` and `error` alert types
- The `select2bs4` class on dropdowns requires Select2 and Bootstrap 4 to be loaded in the admin layout
- DataTable columns use `rawColumns()` for HTML rendering — be careful about XSS if user input is rendered raw in future columns

---

## Author

**Your Name**
- GitHub: [@your-username](https://github.com/your-username)
- Email: your-email@example.com
