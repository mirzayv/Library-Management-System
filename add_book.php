<?php
require_once "db_connection.php";

// Check if the add book form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $isbn = $_POST['isbn'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $publisher = $_POST['publisher'];
    $totalCopies = $_POST['total_copies'];

    // Prepare the SQL statement to add the book
    $stmt = $conn->prepare("INSERT INTO books (ISBN, title, author, publication_year, publisher, total_copies, available_copies) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $isbn, $title, $author, $year, $publisher, $totalCopies, $totalCopies); // Set available copies equal to total copies
    $stmt->execute();

    // Close the prepared statement
    $stmt->close();

    // Redirect to the manage_books.php page
    header("Location: manage_books.php");
    exit();
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Add Book - Library Management System</title>
    <!-- Include Bootstrap CSS -->
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
                        <a class="nav-link" href="manage_books.php">Manage Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_members.php">Manage Members</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_borrowings.php">Manage Borrowings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Add Book Form -->
    <section id="content" class="container mt-4">
        <h2>Add Book</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" id="isbn" name="isbn" required>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Author</label>
                <input type="text" class="form-control" id="author" name="author" required>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Publication Year</label>
                <input type="text" class="form-control" id="year" name="year" required>
            </div>
            <div class="mb-3">
                <label for="publisher" class="form-label">Publisher</label>
                <input type="text" class="form-control" id="publisher" name="publisher" required>
            </div>
            <div class="mb-3">
                <label for="total_copies" class="form-label">Total Copies</label>
                <input type="number" class="form-control" id="total_copies" name="total_copies" required>
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
        </form>
    </section>

    <!-- Include Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
