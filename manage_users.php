<?php
session_start();
require 'db.php';

// Ensure only technical team members can access this page
if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "technical") {
    header("Location: login.php");
    exit();
}

// Fetch all users
$query = "SELECT id, name, email, role FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | Tech Admin</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-dark text-white sidebar">
            <div class="sidebar-heading text-center py-4 fs-4 fw-bold border-bottom">
                <i class="fa-solid fa-server me-2"></i> Tech Admin
            </div>
            <div class="list-group list-group-flush">
                <a href="technical_dashboard.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fa-solid fa-gauge me-2"></i> Dashboard
                </a>
                <a href="manage_users.php" class="list-group-item list-group-item-action bg-primary text-white">
                    <i class="fa-solid fa-users-cog me-2"></i> Manage Users
                </a>
                <a href="logout.php" class="list-group-item list-group-item-action bg-danger text-white">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper" class="w-100">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-light" id="menu-toggle">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <span class="navbar-text text-white fw-bold">
                        Manage Users
                    </span>
                </div>
            </nav>

            <div class="container mt-4">
                <h2 class="text-center mb-4"><i class="fa-solid fa-users-gear"></i> Manage Users</h2>

                <!-- Success & Error Messages -->
                <?php if (isset($_SESSION["error"])): ?>
                    <div class="alert alert-danger alert-dismissible fade show text-center">
                        <?= $_SESSION["error"]; unset($_SESSION["error"]); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION["success"])): ?>
                    <div class="alert alert-success alert-dismissible fade show text-center">
                        <?= $_SESSION["success"]; unset($_SESSION["success"]); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Add User Button -->
                <div class="d-flex justify-content-end mb-3">
                    <a href="add_user.php" class="btn btn-primary">
                        <i class="fa-solid fa-user-plus"></i> Add New User
                    </a>
                </div>

                <!-- User Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr id="user-row-<?= $row['id'] ?>">
                                    <td><?= htmlspecialchars($row["name"]) ?></td>
                                    <td><?= htmlspecialchars($row["email"]) ?></td>
                                    <td><?= htmlspecialchars($row["role"]) ?></td>
                                    <td>
                                        <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </a>
                                        <button class="btn btn-danger btn-sm delete-user" data-id="<?= $row['id'] ?>">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- End of page-content-wrapper -->
    </div> <!-- End of wrapper -->

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript for Sidebar Toggle & Delete Action -->
    <script>
        document.getElementById("menu-toggle").addEventListener("click", function() {
            document.getElementById("wrapper").classList.toggle("toggled");
        });

        document.addEventListener("DOMContentLoaded", function() {
            const deleteButtons = document.querySelectorAll(".delete-user");

            deleteButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const userId = this.dataset.id;

                    if (confirm("Are you sure you want to delete this user?")) {
                        fetch("manage_users.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/x-www-form-urlencoded" },
                            body: `delete_id=${userId}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById(`user-row-${userId}`).remove();
                                alert("User deleted successfully!");
                            } else {
                                alert("Failed to delete user.");
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>

<?php
// Handle User Deletion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_id'])) {
    $userId = intval($_POST['delete_id']);

    if ($_SESSION["user_role"] === "technical") {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $success = $stmt->execute();

        echo json_encode(["success" => $success]);
        exit();
    }
}

$conn->close();
?>
