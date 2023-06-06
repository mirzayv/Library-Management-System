<?php
require_once "db_connection.php";
// Start the session
session_start();

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    // Redirect to the appropriate page based on the user's role
    if ($_SESSION['role'] === 'admin') {
        header("Location: dashboard.php");
    } else {
        header("Location: index.php");
    }
    exit();
}

// Check if the login form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the login credentials
    // Replace the following code with your own authentication logic

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM members WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Fetch the user record
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Store the user details in the session
            $_SESSION['username'] = $username;
            // Fetch the user record
$member_id = $row['member_id'];
            $_SESSION['role'] = $row['role'];

            // Redirect to the appropriate page based on the user's role
            if ($row['role'] === 'admin') {
                header("Location: dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        }
    }

    // Display an error message for invalid credentials
    $error = "Invalid username or password";

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>

<!-- Rest of the login form HTML code -->


<!DOCTYPE html>
<html>
<head>
    <title>Login - Library Management System</title>
    <!-- Include CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Login form -->
    <div class="container">
        <div class="row">
            <div class="col-4 m-auto mt-5">
            <h1>Login</h1>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $error_message; ?>
    </div>
<?php endif; ?>

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
</form>
<p>Don't have an account? <a href="signup.php">Sign up</a></p>
            </div>
        </div>
    </div>
    </div>
</body>
</html>
