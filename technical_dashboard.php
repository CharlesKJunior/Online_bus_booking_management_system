<?php
session_start();
require 'db.php';

// Check if user is logged in and is a technical team member
if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "technical") {
    header("Location: login.php");
    exit();
}

// Fetch total users
$totalUsersQuery = $conn->query("SELECT COUNT(*) AS total FROM users");
$totalUsers = $totalUsersQuery->fetch_assoc()['total'];

// Fetch active users (assuming all are active)
$activeUsersQuery = $conn->query("SELECT COUNT(*) AS active FROM users");
$activeUsers = $activeUsersQuery->fetch_assoc()['active'];

// Fetch total messages
$messagesQuery = $conn->query("SELECT COUNT(*) AS messages FROM contact_messages");
$messagesReceived = $messagesQuery->fetch_assoc()['messages'];

// Fetch resolved issues
$resolvedIssuesQuery = $conn->query("SELECT COUNT(*) AS resolved FROM contact_messages WHERE status = 'Resolved'");
$issuesResolved = $resolvedIssuesQuery->fetch_assoc()['resolved'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technical Dashboard | Analytics</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/dashboard.css">
    <style>

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

                <a href="manage_trip.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fa-solid fa-users-cog me-2"></i> Manage Trips
                </a>

                <a href="manage_bookings.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fa-solid fa-ticket-alt me-2"></i> Manage Bookings
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
                        Welcome, Technical Team
                    </span>
                </div>
            </nav>

            <!-- Main Dashboard Content -->
            <div class="container-fluid mt-4">
                <div class="row">
                    <!-- Analytics Cards -->
                    <div class="row">
                <!-- Total Users -->
                <div class="col-md-3">
                    <div class="card shadow-lg border-0 text-center">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fa-solid fa-users text-primary me-2"></i> Total Users</h5>
                            <h3 class="fw-bold"><?= $totalUsers ?></h3>
                            <a href="manage_users.php" class="btn btn-primary btn-sm mt-2">View Users</a>
                        </div>
                    </div>
                </div>
                <!-- Active Users -->
                <div class="col-md-3">
                    <div class="card shadow-lg border-0 text-center">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fa-solid fa-user-check text-success me-2"></i> Active Users</h5>
                            <h3 class="fw-bold"><?= $activeUsers ?></h3>
                            <a href="manage_users.php" class="btn btn-success btn-sm mt-2">View Active Users</a>
                        </div>
                    </div>
                </div>
                <!-- Messages -->
                <div class="col-md-3">
                    <div class="card shadow-lg border-0 text-center">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fa-solid fa-envelope text-warning me-2"></i> Messages</h5>
                            <h3 class="fw-bold"><?= $messagesReceived ?></h3>
                            <a href="view_messages.php" class="btn btn-warning btn-sm mt-2">View Messages</a>
                        </div>
                    </div>
                </div>
                <!-- Issues Resolved -->
                <div class="col-md-3">
                    <div class="card shadow-lg border-0 text-center">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fa-solid fa-check-circle text-danger me-2"></i> Issues Resolved</h5>
                            <h3 class="fw-bold"><?= $issuesResolved ?></h3>
                            <a href="view_messages.php" class="btn btn-danger btn-sm mt-2">Manage Issues</a>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Charts Section -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card shadow-lg border-0">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fa-solid fa-chart-line me-2"></i> User Activity</h5>
                                <canvas id="userActivityChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-lg border-0">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fa-solid fa-chart-pie me-2"></i> Issue Resolution Rate</h5>
                                <canvas id="issuesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div> <!-- End of page-content-wrapper -->
    </div> <!-- End of wrapper -->

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js Script -->
    <script>
        // User Activity Chart
        const ctx1 = document.getElementById('userActivityChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Active Users',
                    data: [30, 40, 35, 50, 60, 70, 65],
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderWidth: 1
                }]
            }
        });

        // Issue Resolution Chart
        const ctx2 = document.getElementById('issuesChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Resolved', 'Pending'],
                datasets: [{
                    data: [<?= $issuesResolved ?>, <?= $messagesReceived - $issuesResolved ?>],
                    backgroundColor: ['#28a745', '#dc3545']
                }]
            }
        });
        // Sidebar Toggle
        document.getElementById("menu-toggle").addEventListener("click", function() {
            document.getElementById("wrapper").classList.toggle("toggled");
        });
    </script>
    <!-- Footer -->

</body>
</html>
