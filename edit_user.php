<?php
session_start();
require 'db.php';

// Ensure only technical team members can edit users
if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "technical") {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT name, email, role FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST["role"];

    $update_query = "UPDATE users SET role = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $role, $id);
    
    if ($stmt->execute()) {
        $_SESSION["success"] = "User updated successfully!";
        header("Location: manage_users.php");
        exit();
    } else {
        $_SESSION["error"] = "Error updating user.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
</head>
<body>
    <h2>Edit User Role</h2>
    <form method="post">
        <label for="role">Role:</label>
        <select name="role">
            <option value="passenger" <?= ($user['role'] == 'passenger') ? 'selected' : ''; ?>>Passenger</option>
            <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="technical" <?= ($user['role'] == 'technical') ? 'selected' : ''; ?>>Technical Team</option>
        </select>
        <button type="submit">Update</button>
    </form>
</body>
</html>
