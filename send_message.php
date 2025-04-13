<?php
// Include the database connection file
include('db.php');

// Initialize variables for form data
$name = $email = $message = '';
$success_message = $error_message = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Sanitize input data to prevent SQL Injection
    $name = $conn->real_escape_string($name);
    $email = $conn->real_escape_string($email);
    $message = $conn->real_escape_string($message);

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    // Execute the query
    if ($stmt->execute()) {
        // Success, redirect back to contact.php with success message
        header("Location: contact.php?status=success");
    } else {
        // Error, redirect back to contact.php with error message
        header("Location: contact.php?status=error");
    }

    // Close the statement
    $stmt->close();

    // Close the connection
    $conn->close();

    exit(); // Exit after redirect to prevent further script execution
}
?>
