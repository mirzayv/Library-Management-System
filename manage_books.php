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

// Fetch all books from the database
$stmt = $conn->prepare("SELECT *, (SELECT COUNT(*) FROM borrowings WHERE borrowings.book_id = books.book_id) AS borrowed_count, total_copies - (SELECT COUNT(*) FROM borrowings WHERE borrowings.book_id = books.book_id) AS available_copies FROM books");
$stmt->execute();
$books = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Check if the search form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    // Retrieve the search keyword
    $keyword = $_POST['search'];

    // Prepare the SQL statement with a search query
    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ?");
    $searchKeyword = '%' . $keyword . '%';
    $stmt->bind_param("s", $searchKeyword);
    $stmt->execute();
    $books = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Books - Library Management System</title>
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
                    <li class="nav-item active">
                        <a class="nav-link" href="manage_books.php">Books</a>
                    </li>
                    <li class="nav-item">
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

    <!-- Content -->
    <section id="content" class="container mt-4">
        <h2>Manage Books</h2>

        <form class="mb-3" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search books..." name="search">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Year</th>
                    <th>Publisher</th>
                    <th>Total</th>
                    <th>Available</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?php echo $book['title']; ?></td>
                        <td><?php echo $book['author']; ?></td>
                        <td><?php echo $book['ISBN']; ?></td>
                        <td><?php echo $book['publication_year']; ?></td>
                        <td><?php echo $book['publisher']; ?></td>
                        <td><?php echo $book['total_copies']; ?></td>
                        <td><?php echo $book['available_copies']; ?></td>
                        <td>
                                <a href="edit_book.php?book_id=<?php echo $book['book_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="delete_book.php?book_id=<?php echo $book['book_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container text-center mt-4">
            <p>&copy; <?php echo date("Y"); ?> Library Management System. All rights reserved.</p>
        </div>
    </footer>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-kHyIkJSyOadC5nHuL6QeV3u/4DnFd//h5Le5R4X2Gxd7OgEghBjE+qA5d+e5yDIv" crossorigin="anonymous"></script>
</body>
</html>
