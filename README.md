# School Management System

A comprehensive School Management System built with PHP 8.1+, MySQL, and Bootstrap 5.3.

## Features
- **Multi-Role Authentication**: Admin, Teacher, Student, Parent.
- **Student Information System**: Admissions, profiles, class allocation.
- **Academic Management**: Classes, Sections, Subjects, Exams, Marks/Grades.
- **Attendance**: Daily marking and reporting.
- **Fees**: Fee assignment and payment tracking.
- **Dashboards**: Role-specific views and statistics.

## Requirements
- XAMPP / LAMP Stack
- PHP 8.1 or higher
- MySQL 8.0
- Web Browser

## Installation

1. **Database Setup**:
   - Open PHPMyAdmin (`http://localhost/phpmyadmin`).
   - Create a new database named `school_db`.
   - Import the `database.sql` file provided in the root directory.

2. **Configuration**:
   - Open `includes/config/config.php`.
   - Update `DB_USER` and `DB_PASS` if your MySQL credentials differ from `root`/`[empty]`.
   - Update `BASE_URL` if you are hosting in a subfolder other than `/school antigravity/`.

3. **Run Application**:
   - Place the project folder in `htdocs`.
   - Navigate to `http://localhost/school antigravity/` in your browser.

## Default Credentials (from Sample Data)

| Role    | Email (Login)          | Password      |
|---------|------------------------|---------------|
| Admin   | admin@school.com       | password123   |
| Teacher | smith@school.com       | password123   |
| Student | john@student.com       | password123   |
| Parent  | johnson@parent.com     | password123   |

*Note: All passwords in sample data are 'password123'.*

## Directory Structure
- `admin/`: Admin modules (CRUD for all entities).
- `teacher/`: Teacher modules (Attendance, Marks).
- `student/`: Student modules (View Results, Fees).
- `parent/`: Parent modules (View Child Data).
- `includes/`: Core classes and configuration.
- `assets/`: CSS, JS, Images.

## Security Features
- Password Hashing (Bcrypt).
- PDO Prepared Statements (SQL Injection Prevention).
- Session Management.
- Input Sanitization.
