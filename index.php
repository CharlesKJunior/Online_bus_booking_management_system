<?php
session_start();
require 'db.php';

// Fetch available trips from the database
$trips_query = "SELECT * FROM trips";
$trips_result = $conn->query($trips_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dapo Travels | Bus Booking</title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="index.css">
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="index.php">
        <img src="images/logo.jpg" alt="Dapo Travels" height="50">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="features.php">Features</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="qrtest.php">test</a></li>
          <?php if (isset($_SESSION['user_name'])): ?>
            <li class="nav-item"><span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span></li>
            <li class="nav-item"><a class="btn btn-success" href="confirmed_trips.php"><i class="bi"></i>My Upcoming Trips</a></li>
            <li class="nav-item"><a class="btn btn-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="btn btn-primary" href="login.php"><i class="bi bi-person"></i> Sign In</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <header id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="images/bus2.jpg" class="d-block w-100" alt="Bus Travel">
        <div class="carousel-caption">
          <h1 class="fw-bold">Book Your Journey with Dapo Travels</h1>
          <p>Safe, Reliable & Affordable Bus Ticketing</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="images/bus3.jpg" class="d-block w-100" alt="Comfortable Buses">
        <div class="carousel-caption">
          <h1 class="fw-bold">Experience Luxury on Every Ride</h1>
          <p>Modern Buses | WiFi | AC | Comfortable Seats</p>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </header>

  <main class="container my-5">
    <h2 class="text-center fw-bold">Available Trips</h2>
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead class="table-dark">
          <tr>
            <th>Trip</th>
            <th>Origin</th>
            <th>Stopovers</th>
            <th>Destination</th>
            <th>Departure</th>
            <th>Arrival</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($trips_result && $trips_result->num_rows > 0): ?>
            <?php while ($trip = $trips_result->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($trip['trip_name']); ?></td>
                <td><?php echo htmlspecialchars($trip['origin']); ?></td>
                <td><?php echo htmlspecialchars($trip['stopovers']); ?></td>
                <td><?php echo htmlspecialchars($trip['destination']); ?></td>
                <td><?php echo htmlspecialchars(date("g:ia", strtotime($trip['departure_time']))); ?></td>
                <td><?php echo htmlspecialchars(date("g:ia", strtotime($trip['expected_arrival_time']))); ?></td>
                <td>
                  <?php if (isset($_SESSION['user_name'])): ?>
                    <a href="booking_trip.php?id=<?php echo $trip['id']; ?>" class="btn btn-primary btn-sm"><i class="bi bi-ticket"></i> Book Now</a>
                  <?php else: ?>
                    <a href="login.php" class="btn btn-secondary btn-sm"><i class="bi bi-person"></i> Sign In to Book</a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center">No trips available at the moment.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>

  <section class="container text-center my-5">
    <h3 class="fw-bold">Special Offers & Promotions</h3>
    <div class="row">
      <div class="col-md-4">
        <img src="images/dis2.jpg" class="img-fluid rounded" alt="Discount">
        <p>Get 20% Off on Your First Booking!</p>
      </div>
      <div class="col-md-4">
        <img src="images/dis4.jpg" class="img-fluid rounded" alt="Loyalty">
        <p>Earn Travel Points & Redeem Free Rides</p>
      </div>
      <div class="col-md-4">
        <img src="images/dis3.jpg" class="img-fluid rounded" alt="Seasonal Discount">
        <p>Special Holiday Discounts Available!</p>
      </div>
    </div>
  </section>

  <footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 Dapo Travels. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="index.js"></script>
</body>
</html>