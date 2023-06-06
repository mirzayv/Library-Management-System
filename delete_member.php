<?php
require_once "db_connection.php";

// Check if member ID is provided
if (isset($_GET['id'])) {
    // Retrieve the member ID from the URL parameter
    $memberId = $_GET['id'];

    // Prepare the SQL statement to delete the member
    $stmt = $conn->prepare("DELETE FROM members WHERE member_id = ?");
    $stmt->bind_param("i", $memberId);
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
