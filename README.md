# Library Management System

The Library Management System is a web application that allows users to manage books, borrowings, and user profiles in a library setting. It provides functionalities for users to login, view available books, borrow books, and update their profile details.

## Features

- User Authentication: Users can create an account, login, and logout.
- View Available Books: Users can browse and search for available books.
- Borrowing System: Users can borrow books and view their borrowed books, including due dates.
- Profile Management: Users can view and update their profile details, including name, email, and password.

## Technologies Used

- HTML
- CSS (Bootstrap framework)
- JavaScript (Bootstrap framework)
- PHP
- MySQL (Database)

## Getting Started

1. Clone the repository: git clone `https://github.com/mirzayv/Library-Management-System.git`

2. Import the database:

- Create a new database in your MySQL server.
- Import the `database.sql` file located in the `database` directory into your created database.

3. Configure the database connection:

- Open the `db_connection.php` file in the project root directory.
- Modify the database connection details according to your setup.

4. Start a local web server:

- You can use XAMPP, WAMP, or any other local development server.
- Make sure the web server is running and configured to serve PHP files.

5. Access the application:

- Open a web browser and navigate to `http://localhost/Library-Management-System`.

## Project Structure

- `index.php`: The homepage of the application.
- `login.php`: The login page.
- `signup.php`: The signup page.
- `profile.php`: The user profile page.
- `edit_profile.php`: The page for editing the user profile and changing the password.
- `books.php`: The page for viewing available books and searching.
- `db_connection.php`: The file containing the database connection setup.
- `styles.css`: The CSS file for custom styles.
- `database`: Directory containing the SQL file for database import.

## Contributing

Contributions are welcome! If you find any issues or have suggestions for improvements, please open an issue or submit a pull request.

## License

This project is licensed under the [MIT License](LICENSE).
