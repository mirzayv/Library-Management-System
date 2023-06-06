<?php
require_once "db_connection.php";
// Start the session
session_start();

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    // Redirect to the index page
    header("Location: index.php");
    exit();
}

$error = "";

// Check if the signup form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $email = $_POST['email'];

    // Validate the form data (You can add your own validation logic here)

    // Example validation: Check if the password and confirm password match
    if ($password !== $confirmPassword) {
        $error = "Passwords do not match";
    } else {
        // Store the user data in the database (You can add your own database logic here)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO members (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        // Execute the SQL statement
        if ($stmt->execute()) {
            // Example: Display a success message after successful signup
            $success = "Sign up successful! Please login.";
            header("Location: login.php");
            exit();
        } else {
            // Display an error message
            $error = "Failed to sign up. Please try again.";
        }

        // Close the statement
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - Library Management System</title>
    <!-- Include CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Sign up form -->
    <div class="container">
        <div class="row">
            <div class="col-4 mt-5 m-auto">
            <h1>Sign up</h1>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary w-100">Sign up</button>
            </div>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>

</body>
</html>
