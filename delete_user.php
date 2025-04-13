<?php
session_start();
require 'db.php';

// Ensure only technical team members can delete users
if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "technical") {
    $_SESSION["error"] = "You must be a technical team member to delete users.";
    header("Location: manage_users.php");
    exit();
}

// Check if user ID is provided
if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $user_id = $_GET["id"];

    // Prevent deleting the last technical team member
    $check_technical = "SELECT COUNT(*) AS count FROM users WHERE role='technical'";
    $result = $conn->query($check_technical);
    $row = $result->fetch_assoc();

    if ($row['count'] <= 1) {
        $_SESSION["error"] = "At least one technical team member must remain!";
        header("Location: manage_users.php");
        exit();
    }

    // Delete the user
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        $_SESSION["error"] = "SQL error: " . $conn->error;
        header("Location: manage_users.php");
        exit();
    }

    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $_SESSION["success"] = "User deleted successfully!";
    } else {
        $_SESSION["error"] = "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION["error"] = "Invalid request.";
}

$conn->close();
header("Location: manage_users.php");
exit();
