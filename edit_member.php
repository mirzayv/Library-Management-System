<?php
require_once "db_connection.php";

// Check if member ID is provided
if (isset($_GET['id'])) {
    // Retrieve the member ID from the URL parameter
    $memberId = $_GET['id'];

    // Fetch member details from the database
    $stmt = $conn->prepare("SELECT * FROM members WHERE member_id = ?");
    $stmt->bind_param("i", $memberId);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();

    // Close the prepared statement
    $stmt->close();

    // Check if the member exists
    if (!$member) {
        echo "Member not found";
        exit();
    }
} else {
    echo "Member ID not provided";
    exit();
}

// Check if the update member form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Prepare the SQL statement to update the member
    $stmt = $conn->prepare("UPDATE members SET username = ?, email = ? WHERE member_id = ?");
    $stmt->bind_param("ssi", $name, $email, $memberId);
    $stmt->execute();

    // Close the prepared statement
    $stmt->close();

    // Redirect to the members.php page
    header("Location: members.php");
    exit();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Member - Library Management System</title>
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

    <!-- Edit Member Form -->
    <div class="container mt-4">
        <h2>Edit Member</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $memberId; ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $member['username']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $member['email']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Member</button>
        </form>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
