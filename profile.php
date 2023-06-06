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

// Retrieve the member details from the database
$stmt = $conn->prepare("SELECT * FROM members WHERE member_id = ?");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Fetch the member record
    $member = $result->fetch_assoc();
}

// Close the database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Management System - Profile</title>
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
                    <li class="nav-item">
                        <a class="nav-link" href="borrowings.php?member_id=<?php echo $member_id; ?>">Borrowings</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <section id="content" class="container mt-4">
        <h2>Profile</h2>

        <table class="table table-striped">
            <tbody>
                <tr>
                    <th>Member ID</th>
                    <td><?php echo $member['member_id']; ?></td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td><?php echo $member['username']; ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo $member['email']; ?></td>
                </tr>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-3z1a+M4zSS3X9UYa7N9xv8LpAtL6vqdBLI4BLFFcbefRHg+gCFr9mkdXuwGLBrO3" crossorigin="anonymous"></script>
</body>
</html>
