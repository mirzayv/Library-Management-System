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

// Check if the form is submitted for updating the profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Update the member details in the database
    $stmt = $conn->prepare("UPDATE members SET username = ?, email = ? WHERE member_id = ?");
    $stmt->bind_param("ssi", $name, $email, $member_id);
    $stmt->execute();

    // Redirect to the profile page
    header("Location: profile.php");
    exit();
}

// Check if the form is submitted for changing the password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    // Retrieve the form data
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Verify the current password
    if (password_verify($currentPassword, $member['password'])) {
        // Check if the new password and confirm password match
        if ($newPassword === $confirmPassword) {
            // Update the password in the database
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE members SET password = ? WHERE member_id = ?");
            $stmt->bind_param("si", $hashedPassword, $member_id);
            $stmt->execute();

            // Redirect to the profile page
            header("Location: profile.php");
            exit();
        } else {
            $passwordError = "New password and confirm password do not match";
        }
    } else {
        $passwordError = "Incorrect current password";
    }
}

// Close the database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Management System - Edit Profile</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Library Management System</a>
        </div>
    </nav>

    <!-- Content -->
    <section id="content" class="container mt-4">
        <h2>Edit Profile</h2>

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $member['username']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $member['email']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>

        <hr>

        <h2>Change Password</h2>

        <?php if (isset($passwordError)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $passwordError; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <div class="mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="change_password">Change Password</button>
        </form>
    </section>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-3z1a+M4zSS3X9UYa7N9xv8LpAtL6vqdBLI4BLFFcbefRHg+gCFr9mkdXuwGLBrO3" crossorigin="anonymous"></script>
</body>
</html>
