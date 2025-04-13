<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'db.php';

// Fetch existing trip details
if (isset($_GET['id'])) {
    $trip_id = intval($_GET['id']);
    $trip_query = $conn->query("SELECT * FROM trips WHERE id = $trip_id");
    if (!$trip_query) {
        die("Error fetching trip: " . $conn->error);
    }
    $trip = $trip_query->fetch_assoc();
}

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $trip_name = $_POST['trip_name'];
    $origin = $_POST['origin'];
    $stopovers = $_POST['stopovers'];
    $destination = $_POST['destination'];
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $bus_capacity = $_POST['bus_capacity'];
    $fare = $_POST['fare']; // New fare field

    // Convert datetime-local format to time format (HH:MM:SS)
    $departure_time = date("H:i:s", strtotime($departure_time));
    $arrival_time = date("H:i:s", strtotime($arrival_time));

    // Prepare and execute update statement
    $stmt = $conn->prepare("UPDATE trips SET trip_name=?, origin=?, stopovers=?, destination=?, departure_time=?, expected_arrival_time=?, bus_capacity=?, fare=? WHERE id=?");
    if (!$stmt) {
        die("Error preparing the statement: " . $conn->error);
    }
    
    $stmt->bind_param("ssssssiid", $trip_name, $origin, $stopovers, $destination, $departure_time, $arrival_time, $bus_capacity, $fare, $trip_id);
    
    if ($stmt->execute()) {
        header("Location: admin_trips.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error updating trip: " . $stmt->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Trip</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Trip</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $trip['id']; ?>">

            <div class="mb-3">
                <label class="form-label">Trip Name</label>
                <input type="text" class="form-control" name="trip_name" value="<?= $trip['trip_name']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Origin</label>
                <input type="text" class="form-control" name="origin" value="<?= $trip['origin']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Stopovers</label>
                <input type="text" class="form-control" name="stopovers" value="<?= $trip['stopovers']; ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Destination</label>
                <input type="text" class="form-control" name="destination" value="<?= $trip['destination']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Departure Time</label>
                <input type="datetime-local" class="form-control" name="departure_time" value="<?= date('Y-m-d\TH:i', strtotime($trip['departure_time'])); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Arrival Time</label>
                <input type="datetime-local" class="form-control" name="arrival_time" value="<?= date('Y-m-d\TH:i', strtotime($trip['expected_arrival_time'])); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Bus Capacity</label>
                <input type="number" class="form-control" name="bus_capacity" value="<?= $trip['bus_capacity']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Fare (UGX)</label>
                <input type="number" class="form-control" name="fare" value="<?= $trip['fare']; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Trip</button>
            <a href="admin_trips.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
