<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user_email'];

// Fetch all bookings for the logged-in user
$bookings_query = "SELECT bookings.*, trips.trip_name, trips.origin, trips.stopovers, trips.destination, trips.departure_time, trips.expected_arrival_time, trips.fare
                   FROM bookings
                   JOIN trips ON bookings.trip_id = trips.id
                   WHERE bookings.email = ?";
$stmt = $conn->prepare($bookings_query);
if (!$stmt) {
    die("Database prepare error: " . $conn->error);
}
$stmt->bind_param("s", $user_email);
$stmt->execute();
$bookings_result = $stmt->get_result();
$bookings = $bookings_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Confirmed Trips</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/confirmed_trips.css">
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="images/logo.jpg" alt="Dapo Travels" class="logo"> Dapo Travels
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="features.php">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Confirmed Trips Container -->
<div class="container mt-5">
    <h2 class="text-center fw-bold mb-4">Your Confirmed Trips</h2>

    <?php if (empty($bookings)) { ?>
        <p class="text-center">You have no confirmed trips.</p>
    <?php } else { ?>
        <?php foreach ($bookings as $booking) { ?>
            <div class="trip-card border p-3 mb-4">
                <h4><i class="bi bi-calendar-check icon"></i> Booking Confirmation</h4>
                <p class="details"><strong>Booking ID:</strong> <?php echo $booking['id']; ?></p>
                <p class="details"><i class="bi bi-bus-front icon"></i> <strong>Trip:</strong> <?php echo $booking['trip_name']; ?></p>
                <p class="details"><i class="bi bi-geo-alt icon"></i> <strong>From:</strong> <?php echo $booking['origin']; ?></p>
                <p class="details"><i class="bi bi-signpost-2 icon"></i> <strong>Stopovers:</strong> <?php echo $booking['stopovers']; ?></p>
                <p class="details"><i class="bi bi-flag-fill icon"></i> <strong>To:</strong> <?php echo $booking['destination']; ?></p>
                <p class="details"><i class="bi bi-clock icon"></i> <strong>Departure:</strong> <?php echo date("g:ia", strtotime($booking['departure_time'])); ?></p>
                <p class="details"><i class="bi bi-clock-fill icon"></i> <strong>Arrival:</strong> <?php echo date("g:ia", strtotime($booking['expected_arrival_time'])); ?></p>
                <p class="details"><i class="bi bi-chair icon"></i> <strong>Seats Booked:</strong> <?php echo $booking['seats']; ?></p>
                <p class="fare"><i class="bi bi-currency-dollar icon"></i> Total Fare: UGX <?php echo number_format($booking['fare'] * $booking['seats']); ?></p>
                
                <div class="d-flex justify-content-between mt-3">
                    <a href="cancel_trip.php?booking_id=<?php echo $booking['id']; ?>" class="btn btn-danger">Cancel Trip</a>
                    <!-- Modified PAY button with data attributes -->
                    <a href="#" 
                       class="btn btn-info pay-btn"
                       data-booking-id="<?php echo $booking['id']; ?>"
                       data-fare="<?php echo $booking['fare']; ?>"
                       data-trip-name="<?php echo htmlspecialchars($booking['trip_name']); ?>"
                       data-origin="<?php echo htmlspecialchars($booking['origin']); ?>"
                       data-stopovers="<?php echo htmlspecialchars($booking['stopovers']); ?>"
                       data-destination="<?php echo htmlspecialchars($booking['destination']); ?>"
                       data-departure="<?php echo date("g:ia", strtotime($booking['departure_time'])); ?>"
                       data-arrival="<?php echo date("g:ia", strtotime($booking['expected_arrival_time'])); ?>"
                       data-seats="<?php echo $booking['seats']; ?>"
                    >Confirm Trip</a>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>

<!-- Payment Modal (Bootstrap Modal) -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">Confirm Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>You are making payment of <strong><span id="modalFare"></span></strong> to Dapo Travels.</p>
        <hr>
        <p><strong>Trip:</strong> <span id="modalTripName"></span></p>
        <p><strong>From:</strong> <span id="modalOrigin"></span></p>
        <p><strong>Stopovers:</strong> <span id="modalStopovers"></span></p>
        <p><strong>To:</strong> <span id="modalDestination"></span></p>
        <p><strong>Departure:</strong> <span id="modalDeparture"></span></p>
        <p><strong>Arrival:</strong> <span id="modalArrival"></span></p>
        <p><strong>Seats:</strong> <span id="modalSeats"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel Payment</button>
        <a id="confirmPaymentBtn" class="btn btn-primary" href="#">Confirm Payment</a>
      </div>
    </div>
  </div>
</div>

<footer class="footer text-center mt-5">
    &copy; 2025 Dapo Travels. All rights reserved.
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Include the payment modal JavaScript -->
<script src="js/paymentModal.js"></script>
</body>
</html>
