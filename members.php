<?php
require_once "db_connection.php";

// Fetch all members from the database
$stmt = $conn->prepare("SELECT * FROM members WHERE role = 'user'");
$stmt->execute();
$members = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Check if the search form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    // Retrieve the search keyword
    $keyword = $_POST['search'];

    // Prepare the SQL statement with a search query
    $stmt = $conn->prepare("SELECT * FROM members WHERE username LIKE ?");
    $searchKeyword = '%' . $keyword . '%';
    $stmt->bind_param("s", $searchKeyword);
    $stmt->execute();
    $members = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Close the prepared statement
$stmt->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Members - Library Management System</title>
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

    <!-- Members table -->
    <div class="container mt-4">
        <h2>Members</h2>
        <!-- Search bar -->
        <a href="add_member.php" class="btn btn-primary mb-3">Add Member</a>
        <form class="mb-3" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search members..." name="search">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member): ?>
                    <tr>
                        <td><?php echo $member['member_id']; ?></td>
                        <td><?php echo $member['username']; ?></td>
                        <td><?php echo $member['email']; ?></td>
                        <td>
                            <a href="edit_member.php?id=<?php echo $member['member_id']; ?>" class="btn btn-primary">Edit</a>
                            <a href="delete_member.php?id=<?php echo $member['member_id']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>
</html>
