<?php
require_once "db_connection.php";

// Check if book ID is provided
if (isset($_GET['book_id'])) {
    // Retrieve the book ID from the URL parameter
    $bookId = $_GET['book_id'];

    // Delete related borrowings first
$stmt = $conn->prepare("DELETE FROM borrowings WHERE book_id = ?");
$stmt->bind_param("i", $bookId);
$stmt->execute();
$stmt->close();

    // Prepare the SQL statement to delete the book
    $stmt = $conn->prepare("DELETE FROM books WHERE book_id = ?");
    $stmt->bind_param("i", $bookId);
    $stmt->execute();

    // Close the prepared statement
    $stmt->close();

    // Redirect to the book.php page
    header("Location: manage_books.php");
    exit();
}

// Close the database connection
$conn->close();
?>
