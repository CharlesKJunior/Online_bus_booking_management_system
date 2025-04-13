<?php
session_start();
require 'db.php';

// Ensure user is logged in and authorized (optional)
if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Check if an ID was provided
if (isset($_GET['id'])) {
    $trip_id = intval($_GET['id']);

    // Prepare delete statement
    $stmt = $conn->prepare("DELETE FROM trips WHERE id = ?");
    $stmt->bind_param("i", $trip_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Trip deleted successfully!";
    } else {
        $_SESSION['message'] = "Failed to delete trip.";
    }

    $stmt->close();
}

// Redirect back to index.php
header("Location: index.php");
exit();
?>
