<?php
require_once "db_connection.php";
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}

// Check if the book ID is provided in the URL
if (!isset($_GET['book_id'])) {
    // Redirect to the books.php page
    header("Location: books.php");
    exit();
}

// Get the book ID from the URL
$bookId = $_GET['book_id'];

// Retrieve the book details from the database
$stmt = $conn->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->bind_param("i", $bookId);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Check if the book exists
if (!$book) {
    // Redirect to the books.php page
    header("Location: books.php");
    exit();
}

// Update the book details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $isbn = $_POST['isbn'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publicationYear = $_POST['publication_year'];
    $publisher = $_POST['publisher'];
    $totalCopies = $_POST['total_copies'];

    // Prepare the SQL statement to update the book
    $stmt = $conn->prepare("UPDATE books SET isbn = ?, title = ?, author = ?, publication_year = ?, publisher = ?, total_copies = ? WHERE book_id = ?");
    $stmt->bind_param("ssssssi", $isbn, $title, $author, $publicationYear, $publisher, $totalCopies, $bookId);
    $stmt->execute();
    $stmt->close();

    // Redirect to the books.php page
    header("Location: books.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Book - Library Management System</title>
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
                        <a class="nav-link" href="books.php">Books</a>
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

    <!-- Edit Book Form -->
    <div class="container mt-4">
        <h2>Edit Book</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . '?book_id=' . $bookId; ?>">
            <div class="mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" id="isbn" name="isbn" value="<?php echo $book['ISBN']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo $book['title']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Author</label>
                <input type="text" class="form-control" id="author" name="author" value="<?php echo $book['author']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="publication_year" class="form-label">Publication Year</label>
                <input type="text" class="form-control" id="publication_year" name="publication_year" value="<?php echo $book['publication_year']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="publisher" class="form-label">Publisher</label>
                <input type="text" class="form-control" id="publisher" name="publisher" value="<?php echo $book['publisher']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="total_copies" class="form-label">Total Copies</label>
                <input type="number" class="form-control" id="total_copies" name="total_copies" value="<?php echo $book['total_copies']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Book</button>
        </form>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
