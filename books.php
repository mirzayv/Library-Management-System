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

// Check if the search query is present
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Retrieve the books based on the search query
$stmt = $conn->prepare("SELECT * FROM books WHERE available_copies > 0 AND title LIKE CONCAT('%', ?, '%')");
$stmt->bind_param("s", $searchQuery);
$stmt->execute();
$books = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Close the database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Management System - Books</title>
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
                    <li class="nav-item active">
                        <a class="nav-link" href="books.php">Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php?member_id=<?php echo $member_id; ?>">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="borrowings.php?member_id=<?php echo $member_id; ?>">Borrowings</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <section id="content" class="container mt-4">
        <h2>Available Books</h2>

        <!-- Search Bar -->
        <form class="mb-3" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="GET">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search books..." name="search">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        <!-- Books -->
        <div class="row">
            <?php foreach ($books as $book): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $book['title']; ?></h5>
                            <p class="card-text">Author: <?php echo $book['author']; ?></p>
                            <?php if ($book['available_copies'] == 0): ?>
                                <a href="#" class="btn btn-primary disabled">Unavailable</a>  
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-kHyIkJSyOadC5nHuL6QeV3u/4DnFd//h5Le5R4X2Gxd7OgEghBjE+qA5d+e5yDIv" crossorigin="anonymous"></script>
</body>
</html>
