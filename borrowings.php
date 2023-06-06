<?php
require_once "db_connection.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}

// Fetch all borrowings from the database
$stmt = $conn->prepare("SELECT borrowings.*, books.ISBN, books.title, members.username FROM borrowings
                        INNER JOIN books ON borrowings.book_id = books.book_id
                        INNER JOIN members ON borrowings.member_id = members.member_id");
$stmt->execute();
$borrowings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Check if the return button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['return'])) {
    // Retrieve the borrowing ID
    $borrowingId = $_POST['borrowing_id'];

    // Retrieve the book ID from the borrowing record
    $stmt = $conn->prepare("SELECT book_id FROM borrowings WHERE borrowing_id = ?");
    $stmt->bind_param("i", $borrowingId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $bookId = $row['book_id'];
    $stmt->close();

    // Update the borrowings table with the return date
    $stmt = $conn->prepare("UPDATE borrowings SET return_date = CURRENT_DATE() WHERE borrowing_id = ?");
    $stmt->bind_param("i", $borrowingId);
    $stmt->execute();
    $stmt->close();

    // Update the available copies in the books table by incrementing
    $stmt = $conn->prepare("UPDATE `books` SET `available_copies` = `available_copies` + 1 WHERE `books`.`book_id` = ?");
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $stmt->close();

    // Redirect to the borrowings.php page
    header("Location: borrowings.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Borrowings - Library Management System</title>
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
                        <a class="nav-link" href="manage_books.php">Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="members.php">Members</a>
                    </li>
                    <li class="nav-item active">
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
        <h2>Borrowings</h2>
        <?php if (isset($error)) : ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Book ISBN</th>
                    <th>Book Title</th>
                    <th>Username</th>
                    <th>Borrowed Date</th>
                    <th>Due Date</th>
                    <th>Return Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($borrowings as $borrowing) : ?>
                    <tr>
                        <td><?php echo $borrowing['ISBN']; ?></td>
                        <td><?php echo $borrowing['title']; ?></td>
                        <td><?php echo $borrowing['username']; ?></td>
                        <td><?php echo $borrowing['borrow_date']; ?></td>
                        <td><?php echo $borrowing['due_date']; ?></td>
                        <td>
                            <?php if ($borrowing['return_date'] !== '0000-00-00') : ?>
                                <?php echo $borrowing['return_date']; ?>
                            <?php else : ?>
                                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <input type="hidden" name="borrowing_id" value="<?php echo $borrowing['borrowing_id']; ?>">
                                    <button type="submit" name="return" class="btn btn-primary btn-sm">Return</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Footer -->
    <footer class="bg-light text-center">
        <div class="container py-3">
            <p>&copy; 2023 Library Management System. All rights reserved.</p>
        </div>
    </footer>

    <!-- Include Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
