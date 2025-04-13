<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $trip_name = $_POST['trip_name'];
    $origin = $_POST['origin'];
    $stopovers = $_POST['stopovers'];
    $destination = $_POST['destination'];
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $fare = $_POST['fare']; // Adding fare field

    // Prepare and execute statement
    $stmt = $conn->prepare("INSERT INTO trips (trip_name, origin, stopovers, destination, departure_time, expected_arrival_time, fare) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $trip_name, $origin, $stopovers, $destination, $departure_time, $arrival_time, $fare);
    
    if ($stmt->execute()) {
        header("Location: admin_trips.php");
        exit();
    } else {
        echo "Error adding trip.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Trip</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Add New Trip</h2>
        <a href="technical_dashboard.php" class="btn btn-primary mb-3">Home</a>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Trip Name</label>
                <input type="text" class="form-control" name="trip_name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Origin</label>
                <input type="text" class="form-control" name="origin" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Stopovers</label>
                <input type="text" class="form-control" name="stopovers">
            </div>
            <div class="mb-3">
                <label class="form-label">Destination</label>
                <input type="text" class="form-control" name="destination" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Departure Time</label>
                <input type="time" class="form-control" name="departure_time" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Expected Arrival Time</label>
                <input type="time" class="form-control" name="arrival_time" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Fare (UGX)</label>
                <input type="number" class="form-control" name="fare" required>
            </div>
            <button type="submit" class="btn btn-success">Add Trip</button>
        </form>
    </div>

    

</body>
</html>
