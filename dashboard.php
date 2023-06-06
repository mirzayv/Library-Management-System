<?php
session_start();
require_once "db_connection.php"; // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    // Redirect to the index page
    header("Location: index.php");
    exit();
}

// Fetch all borrowings from the database
$stmt = $conn->prepare("SELECT borrowings.borrowing_id, books.isbn, members.username, borrowings.borrow_date, borrowings.due_date FROM borrowings JOIN books ON borrowings.book_id = books.book_id JOIN members ON borrowings.member_id = members.member_id");
$stmt->execute();
$borrowings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Close the prepared statement
$stmt->close();

// Check if the borrow form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $bookISBN = $_POST['book_isbn'];
    $memberUsername = $_POST['member_username'];
    $borrowedDate = $_POST['borrowed_date'];
    $dueDate = $_POST['due_date'];

    // Prepare the SQL statement to add the borrowing
    $stmt = $conn->prepare("INSERT INTO borrowings (book_id, member_id, borrow_date, due_date) VALUES ((SELECT book_id FROM books WHERE ISBN = ?), (SELECT member_id FROM members WHERE username = ?), ?, ?)");
    $stmt->bind_param("ssss", $bookISBN, $memberUsername, $borrowedDate, $dueDate);
    $stmt->execute();

    // Close the prepared statement
    $stmt->close();

    // Redirect to the same page to prevent form resubmission
    header("Location: borrowings.php");
    exit();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Library Management System</title>
    <!-- Include CSS -->
    <link rel="stylesheet" href="styles.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Library Management System</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="manage_books.php">Books</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="members.php">Members</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="borrowings.php">Borrowings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    

    <!-- Add Borrowing Form -->
    <div class="container mt-4">
        <h2>Add Borrowing</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="book_isbn" class="form-label">Book ISBN</label>
                        <input type="text" class="form-control" id="book_isbn" name="book_isbn" required>
                    </div>
                    <div class="mb-3">
                        <label for="member_username" class="form-label">Member Username</label>
                        <input type="text" class="form-control" id="member_username" name="member_username" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="borrowed_date" class="form-label">Borrowed Date</label>
                        <input type="date" class="form-control" id="borrowed_date" name="borrowed_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" required>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Add Borrowing</button>
        </form>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2023 Library Management System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
