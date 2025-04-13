<?php
session_start();
require 'db.php';

// Check if booking details exist
if (!isset($_SESSION['booking'])) {
    die("No booking information found.");
}
$booking = $_SESSION['booking'];

// Fetch trip details to check available seats
$trip_id = $booking['trip_id'];
$seat_query = "SELECT bus_capacity - COUNT(*) AS available_seats FROM bookings WHERE trip_id = ? AND status = 'confirmed'";
$stmt = $conn->prepare($seat_query);
$stmt->bind_param("i", $trip_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['available_seats'] <= 0) {
    die("<p>Sorry, this trip is fully booked.</p><a href='index.php' class='btn btn-danger'>Back to Home</a>");
}

// Assign a seat number (next available seat)
$seat_number = ($row['available_seats'] - 1) + 1;

// Insert booking into the database
$insert_query = "INSERT INTO bookings (trip_id, user_id, boarding_point, dropoff_point, booking_date, status, seat_number, payment_status) 
                 VALUES (?, ?, ?, ?, NOW(), 'pending', ?, 'unpaid')";
$stmt = $conn->prepare($insert_query);
$stmt->bind_param("iissi", $booking['trip_id'], $booking['user_id'], $booking['boarding_point'], $booking['dropoff_point'], $seat_number);
$stmt->execute();

// Get the booking ID
$booking_id = $stmt->insert_id;

// Clear session booking data
unset($_SESSION['booking']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Confirmation | Dapo Travels</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5 text-center">
        <h2>Booking Confirmed</h2>
        <p>Your booking has been successfully recorded.</p>
        <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($booking_id); ?></p>
        <p><strong>Seat Number:</strong> <?php echo htmlspecialchars($seat_number); ?></p>
        <p><strong>Payment Status:</strong> Unpaid</p>
        <a href="index.php" class="btn btn-primary">Back to Home</a>
    </div>
</body>
</html>
