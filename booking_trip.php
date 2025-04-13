<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

// Check if a trip ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid trip selection.");
}

$trip_id = intval($_GET['id']);

// Fetch trip details from the database
$trip_query = "SELECT * FROM trips WHERE id = ?";
$stmt = $conn->prepare($trip_query);
$stmt->bind_param("i", $trip_id);
$stmt->execute();
$trip_result = $stmt->get_result();
$trip = $trip_result->fetch_assoc();

if (!$trip) {
    die("Trip not found.");
}

$stmt->close();

// Pre-fill email from session if available
$user_email = $_SESSION['user_email']; // Retrieve user email from session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book Trip - Dapo Travels</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/booking_trip.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="images/logo.jpg" alt="Dapo Travels" height="50">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="features.php">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
                <l class="nav-item">
                <a class="btn btn-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


    <main class="container my-5">
        <h2 class="text-center fw-bold mb-4"><i class="bi bi-calendar-check"></i> Book Your Trip</h2>

        <div class="card p-4 shadow-sm mb-4">
            <h4 class="fw-bold mb-3"><i class="bi bi-bus-front"></i> Trip Details</h4>
            <div class="row">
                <div class="col-md-6">
                    <p><i class="bi bi-geo-alt"></i> <strong>Trip:</strong> <?php echo htmlspecialchars($trip['trip_name']); ?></p>
                    <p><i class="bi bi-arrow-right-short"></i> <strong>From:</strong> <?php echo htmlspecialchars($trip['origin']); ?></p>
                    <p><i class="bi bi-signpost-2"></i> <strong>Stopovers:</strong> <?php echo htmlspecialchars($trip['stopovers']); ?></p>
                </div>
                <div class="col-md-6">
                    <p><i class="bi bi-flag-fill"></i> <strong>To:</strong> <?php echo htmlspecialchars($trip['destination']); ?></p>
                    <p><i class="bi bi-clock"></i> <strong>Departure:</strong> <?php echo htmlspecialchars(date("g:ia", strtotime($trip['departure_time']))); ?></p>
                    <p><i class="bi bi-clock-fill"></i> <strong>Arrival:</strong> <?php echo htmlspecialchars(date("g:ia", strtotime($trip['expected_arrival_time']))); ?></p>
                    <p><i class="bi bi-currency-dollar"></i> <strong>Fare (UGX):</strong> <?php echo htmlspecialchars($trip['fare']); ?></p>
                </div>
            </div>
        </div>

        <form action="process_booking.php" method="POST" class="mt-4">
            <input type="hidden" name="trip_id" value="<?php echo $trip_id; ?>">
            <div class="mb-3">
                <label for="name" class="form-label"><i class="bi bi-person"></i> Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label"><i class="bi bi-envelope"></i> Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label"><i class="bi bi-telephone"></i> Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="mb-3">
                <label for="seats" class="form-label"><i class="bi bi-chair"></i> Number of Seats</label>
                <input type="number" class="form-control" id="seats" name="seats" min="1" required>
            </div>
            <a href="index.php" class="btn btn-secondary w-100"><i class="bi bi-box-arrow-left"></i>  Back</a>
            <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-circle"></i> Confirm Booking</button>
        </form>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2025 Dapo Travels. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/booking_trip.js"></script>
</body>
</html>