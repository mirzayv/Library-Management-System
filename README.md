# Library Management System

A PHP + MySQL web application for running a small library: members sign up and borrow books, and an admin manages the catalogue, members and borrowings. My first full web project (2023).

## Features
**Members**
- Sign up, log in and log out (passwords stored with `password_hash` / `password_verify`)
- Browse and search available books, borrow a book with a due date
- See their borrowed books ordered by due date; edit profile and change password

**Admin** (role-based access with PHP sessions)
- Dashboard
- Add, edit and delete books (ISBN, author, publisher, copies; available copies update on borrow and return)
- Add, edit, search and delete members
- View all borrowings and mark books as returned

All database queries use **mysqli prepared statements**.

## Tech
PHP · MySQL (mysqli) · HTML · CSS · Bootstrap · JavaScript

## Getting started
1. Clone the repository
   ```bash
   git clone https://github.com/mirzayv/Library-Management-System.git
   ```
2. Create the database — import [`database/schema.sql`](database/schema.sql) in phpMyAdmin or run
   ```bash
   mysql -u root -p < database/schema.sql
   ```
3. Check the connection settings in `db_connection.php` (defaults: `localhost`, user `root`, empty password, database `library`).
4. Serve the folder with a local PHP server — XAMPP / WAMP, or
   ```bash
   php -S localhost:8000
   ```
5. Open `http://localhost:8000` (or `http://localhost/Library-Management-System` under XAMPP), sign up, then make your account an admin with the SQL line at the end of `schema.sql`.

## Project structure
| File | Purpose |
|---|---|
| `index.php`, `login.php`, `signup.php`, `logout.php` | Entry and authentication |
| `books.php`, `profile.php`, `edit_profile.php` | Member pages: search and borrow, my books, profile |
| `dashboard.php` | Admin dashboard |
| `manage_books.php`, `add_book.php`, `edit_book.php`, `delete_book.php` | Book management |
| `members.php`, `add_member.php`, `edit_member.php`, `delete_member.php` | Member management |
| `borrowings.php` | All borrowings and returns |
| `db_connection.php` | MySQL connection |
| `database/schema.sql` | Tables: `members`, `books`, `borrowings` |
| `main.css`, `main.js` | Styles and scripts |

## License
[MIT](LICENSE)
