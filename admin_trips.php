<?php
session_start();
require 'db.php';

// Fetch trips
$trips_query = "SELECT * FROM trips";
$trips_result = $conn->query($trips_query);

// Handle trip deletion
if (isset($_GET['delete'])) {
    $trip_id = intval($_GET['delete']);
    $conn->query("DELETE FROM trips WHERE id = $trip_id");
    header("Location: admin_trips.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Trips</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Manage Trips</h2>
        <a href="add_trip.php" class="btn btn-primary mb-3">Add New Trip</a>
        <a href="technical_dashboard.php" class="btn btn-primary mb-3">Home</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Trip</th>
                    <th>Origin</th>
                    <th>Stopovers</th>
                    <th>Destination</th>
                    <th>Departure</th>
                    <th>Arrival</th>
                    <th>Fare (UGX)</th> <!-- Added Fare Column -->
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($trip = $trips_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($trip['trip_name']); ?></td>
                        <td><?= htmlspecialchars($trip['origin']); ?></td>
                        <td><?= htmlspecialchars($trip['stopovers']); ?></td>
                        <td><?= htmlspecialchars($trip['destination']); ?></td>
                        <td><?= htmlspecialchars(date("g:ia", strtotime($trip['departure_time']))); ?></td>
                        <td><?= htmlspecialchars(date("g:ia", strtotime($trip['expected_arrival_time']))); ?></td>
                        <td><?= htmlspecialchars($trip['fare']); ?></td> <!-- Display Fare -->
                        <td>
                            <a href="edit_trip.php?id=<?= $trip['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="admin_trips.php?delete=<?= $trip['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
