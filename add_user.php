<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "technical") {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $role = $_POST["role"];

    // Check for duplicate email
    $check_query = "SELECT id FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $_SESSION["error"] = "Email already exists!";
        header("Location: add_user.php");
        exit();
    }
    $check_stmt->close();

    // Insert the user
    $query = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
    if (!$stmt = $conn->prepare($query)) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssss", $name, $email, $password, $role);
    if ($stmt->execute()) {
        $_SESSION["success"] = "User added successfully!";
        header("Location: manage_users.php");
        exit();
    } else {
        $_SESSION["error"] = "Error adding user: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <!-- Link Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Link Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Link Custom External CSS -->
    <link href="css/add_user.css" rel="stylesheet" />
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Add New User</h2>

        <?php if (isset($_SESSION["error"])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-x-circle"></i> <?php echo $_SESSION["error"]; unset($_SESSION["error"]); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION["success"])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo $_SESSION["success"]; unset($_SESSION["success"]); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="add_user.php" method="post" id="addUserForm">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name <i class="bi bi-person"></i></label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email <i class="bi bi-envelope"></i></label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password <i class="bi bi-lock"></i></label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role <i class="bi bi-person-circle"></i></label>
                <select name="role" class="form-select" required>
                    <option value="passenger">Passenger</option>
                    <option value="admin">Admin</option>
                    <option value="technical">Technical Team</option>
                </select>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Add User <i class="bi bi-person-plus"></i></button>
            </div>
        </form>
    </div>

    <!-- Link Bootstrap JS and Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <!-- Link Custom External JS -->
    <script src="js/add_user.js"></script>
</body>

</html>
