<?php
// cancel_trip.php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

// Check if booking ID is provided
if (!isset($_GET['booking_id']) || empty($_GET['booking_id'])) {
    die("Invalid booking selection.");
}

$booking_id = intval($_GET['booking_id']);

// Fetch booking details
$booking_query = "SELECT bookings.*, trips.trip_name, trips.origin, trips.destination, trips.departure_time, trips.expected_arrival_time, trips.fare
                  FROM bookings
                  JOIN trips ON bookings.trip_id = trips.id
                  WHERE bookings.id = ? AND bookings.email = ?";
$stmt = $conn->prepare($booking_query);
$stmt->bind_param("is", $booking_id, $_SESSION['user_email']);
$stmt->execute();
$booking_result = $stmt->get_result();
$booking = $booking_result->fetch_assoc();
$stmt->close();

if (!$booking) {
    die("Booking not found or you do not have permission to cancel this booking.");
}

// Handle cancellation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_cancel'])) {
    $delete_query = "DELETE FROM bookings WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $booking_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Booking cancelled successfully.";
        header("Location: confirmed_trips.php"); // Redirect to confirmed trips page
        exit();
    } else {
        $_SESSION['error'] = "Error cancelling booking.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cancel Booking</title>
    <style>
        body { font-family: sans-serif; }
        .cancel-details { border: 1px solid #ccc; padding: 20px; margin: 20px; }
    </style>
</head>
<body>
    <h1>Cancel Booking</h1>

    <div class="cancel-details">
        <h2>Booking Details</h2>
        <p><strong>Booking ID:</strong> <?php echo $booking['id']; ?></p>
        <p><strong>Trip Name:</strong> <?php echo $booking['trip_name']; ?></p>
        <p><strong>From:</strong> <?php echo $booking['origin']; ?></p>
        <p><strong>To:</strong> <?php echo $booking['destination']; ?></p>
        <p><strong>Departure:</strong> <?php echo date("g:ia", strtotime($booking['departure_time'])); ?></p>
        <p><strong>Arrival:</strong> <?php echo date("g:ia", strtotime($booking['expected_arrival_time'])); ?></p>
        <p><strong>Seats Booked:</strong> <?php echo $booking['seats']; ?></p>
        <p><strong>Total Fare:</strong> UGX <?php echo number_format($booking['fare'] * $booking['seats']); ?></p>
    </div>

    <p>Are you sure you want to cancel this booking?</p>

    <form method="post">
        <button type="submit" name="confirm_cancel">Confirm Cancellation</button>
        <a href="confirmed_trips.php">Go Back</a>
    </form>
</body>
</html>

