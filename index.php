<?php
require_once "db_connection.php";
// Start the session
session_start();

// Check if the user is already logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'user') {
    header("Location: dashboard.php");
}

// Retrieve the member ID from the database
$stmt = $conn->prepare("SELECT member_id FROM members WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Fetch the member ID
    $row = $result->fetch_assoc();
    $member_id = $row['member_id'];
}

// Retrieve the user's borrowings
$stmt = $conn->prepare("SELECT b.title, br.borrow_date, br.due_date FROM borrowings br INNER JOIN books b ON br.book_id = b.book_id WHERE br.member_id = ? ORDER BY br.due_date");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$borrowings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Close the database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Management System</title>
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
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="books.php">Books</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="profile.php?member_id=<?php echo $member_id; ?>">Profile</a>
                    </li>
                    <li>
                        <a href="logout.php" class="nav-link">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <section id="content" class="container mt-4">
        <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
        <h3>Your Borrowings:</h3>

        <?php if (count($borrowings) > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Borrow Date</th>
                    <th>Due Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($borrowings as $borrowing): ?>
                    <tr>
                        <td><?php echo $borrowing['title']; ?></td>
                        <td><?php echo $borrowing['borrow_date']; ?></td>
                        <td><?php echo $borrowing['due_date']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You don't have any borrowings at the moment.</p>
    <?php endif; ?>
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
