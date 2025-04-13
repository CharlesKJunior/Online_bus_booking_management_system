<?php
session_start();
require 'db.php';

// Check if a booking ID is provided
if (!isset($_GET['booking_id'])) {
    header("Location: comfirm_trip.php");
    exit();
}

$booking_id = intval($_GET['booking_id']);

// Update the booking status from 'Pending' to 'PAID'
$stmt = $conn->prepare("UPDATE bookings SET status = 'PAID' WHERE id = ? AND status = 'Pending'");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$stmt->close();

// Redirect to receipt page (or another page) with the booking ID
header("Location: receipt.php?booking_id=" . $booking_id);
exit();
?>
