<?php
session_start();
require 'db.php';

// Check if user is logged in and is a technical team member
if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "technical") {
    header("Location: login.php");
    exit();
}

// Handle "Mark as Resolved" action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['resolve_id'])) {
    $messageId = intval($_POST['resolve_id']);
    $updateQuery = $conn->query("UPDATE contact_messages SET status='Resolved' WHERE id=$messageId");

    if ($updateQuery) {
        header("Location: view_messages.php");
        exit();
    } else {
        $error = "Error updating status. Try again.";
    }
}

// Fetch messages
$messagesQuery = $conn->query("SELECT * FROM contact_messages ORDER BY date_submitted DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/dashboard.css">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .table thead {
            background-color: #007bff;
            color: white;
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .btn-success, .btn-warning {
            transition: 0.3s ease-in-out;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .btn-warning:hover {
            background-color: #ffc107;
        }
        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="d-flex" id="wrapper">
        <div class="bg-dark text-white sidebar">
            <div class="sidebar-heading text-center py-4 fs-4 fw-bold border-bottom">
                <i class="fa-solid fa-server me-2"></i> Tech Admin
            </div>
            <div class="list-group list-group-flush">
                <a href="technical_dashboard.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fa-solid fa-gauge me-2"></i> Dashboard
                </a>
                <a href="manage_users.php" class="list-group-item list-group-item-action bg-dark text-white">
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
                        Contact Messages
                    </span>
                </div>
            </nav>

            <div class="container mt-4">
                <h3><i class="fa-solid fa-envelope me-2 text-primary"></i> Contact Messages</h3>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Date Submitted</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $messagesQuery->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['message']) ?></td>
                                <td><?= $row['date_submitted'] ?></td>
                                <td>
                                    <?php if ($row['status'] === 'Pending'): ?>
                                        <span class="badge bg-warning text-dark"><i class="fa-solid fa-hourglass-half"></i> Pending</span>
                                    <?php else: ?>
                                        <span class="badge bg-success"><i class="fa-solid fa-check-circle"></i> Resolved</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['status'] === 'Pending'): ?>
                                        <form method="POST" action="" class="resolve-form">
                                            <input type="hidden" name="resolve_id" value="<?= $row['id'] ?>">
                                            <button type="submit" class="btn btn-success resolve-btn">
                                                <i class="fa-solid fa-check"></i> Mark as Resolved
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-secondary" disabled>
                                            <i class="fa-solid fa-check-double"></i> Resolved
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>&copy; <?= date("Y") ?> Tech Admin Panel | All Rights Reserved</p>
            </div>

        </div> <!-- End of Page Content -->
    </div> <!-- End of Wrapper -->

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar Toggle
        document.getElementById("menu-toggle").addEventListener("click", function() {
            document.getElementById("wrapper").classList.toggle("toggled");
        });

        // Confirmation Prompt for Marking as Resolved
        document.querySelectorAll('.resolve-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                let confirmAction = confirm("Are you sure you want to mark this message as resolved?");
                if (!confirmAction) {
                    event.preventDefault();
                }
            });
        });
    </script>

</body>
</html>
