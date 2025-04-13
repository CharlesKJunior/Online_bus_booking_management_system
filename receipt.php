<?php
// receipt.php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

// Validate booking ID
if (!isset($_GET['booking_id']) || empty($_GET['booking_id'])) {
    die("Invalid booking selection.");
}

$booking_id = intval($_GET['booking_id']);

// Fetch booking details
$receipt_query = "SELECT bookings.*, trips.trip_name, trips.origin, trips.destination, trips.departure_time, trips.fare
                  FROM bookings
                  JOIN trips ON bookings.trip_id = trips.id
                  WHERE bookings.id = ? AND bookings.email = ?";
$stmt = $conn->prepare($receipt_query);
$stmt->bind_param("is", $booking_id, $_SESSION['user_email']);
$stmt->execute();
$receipt_result = $stmt->get_result();
$receipt = $receipt_result->fetch_assoc();
$stmt->close();

if (!$receipt) {
    die("Receipt not found or you do not have permission to view this receipt.");
}

// Generate QR Code Data
$qrData = "Booking ID: " . $receipt['id'] . "\n" .
          "Trip: " . $receipt['trip_name'] . "\n" .
          "From: " . $receipt['origin'] . "\n" .
          "To: " . $receipt['destination'] . "\n" .
          "Departure: " . date("g:ia", strtotime($receipt['departure_time'])) . "\n" .
          "Seats: " . $receipt['seats'] . "\n" .
          "Total: UGX " . number_format($receipt['fare'] * $receipt['seats']) . "\n" .
          "Name: " . $receipt['name'] . "\n" .
          "Email: " . $receipt['email'] . "\n" .
          "Phone: " . $receipt['phone'];

// Include QR Code Library
include 'phpqrcode/qrlib.php'; 

// Generate QR Code as Base64
ob_start();
QRcode::png($qrData, null, QR_ECLEVEL_L, 10);
$qrImage = base64_encode(ob_get_clean());
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Receipt - Dapo Travels</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/receipt.css">
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Dapo Travels</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="features.php">Feature</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Receipt Container -->
<div class="container my-5">
    <div class="receipt border p-4 shadow-sm">
        <!-- Header Section -->
        <div class="header text-center mb-4">
            <img src="images/logo.jpg" alt="Dapo Travels" class="logo mb-3">
            <h2>Booking Receipt</h2>
            <p>Thank you for choosing Dapo Travels</p>
        </div>

        <!-- Customer Details Section -->
        <div class="receipt-body">
            <div class="section mb-4">
                <h4>Customer Details</h4>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($receipt['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($receipt['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($receipt['phone']); ?></p>
            </div>

            <!-- Trip Details Section -->
            <div class="section mb-4">
                <h4>Trip Details</h4>
                <p><strong>Trip Name:</strong> <?php echo htmlspecialchars($receipt['trip_name']); ?></p>
                <p><strong>From:</strong> <?php echo htmlspecialchars($receipt['origin']); ?></p>
                <p><strong>To:</strong> <?php echo htmlspecialchars($receipt['destination']); ?></p>
                <p><strong>Departure:</strong> <?php echo date("g:ia", strtotime($receipt['departure_time'])); ?></p>
                <p><strong>Seats Booked:</strong> <?php echo htmlspecialchars($receipt['seats']); ?></p>
                <p><strong>Total Fare:</strong> UGX <?php echo number_format($receipt['fare'] * $receipt['seats']); ?></p>
            </div>

            <!-- QR Code Section -->
            <div class="qr-section text-center mb-4">
                <h4>Scan QR Code</h4>
                <img src="data:image/png;base64,<?php echo $qrImage; ?>" alt="QR Code">
            </div>
        </div>

        <!-- Footer Section -->
        <div class="footer text-center">
            <button onclick="printReceipt()" class="btn btn-primary">Print Receipt</button>
            <a href="confirmed_trips.php" class="btn btn-secondary">Go Back</a>
        </div>
    </div>
</div>

<script>
    function printReceipt() {
        window.print();
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
