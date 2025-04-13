<?php
session_start();
require 'db.php';

// Ensure user is an admin
//if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "admin") {
//    header("Location: login.php");
//    exit();
//}

// Fetch trips
$trips_query = "SELECT * FROM trips";
$trips_result = $conn->query($trips_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Trips | Admin Panel</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

  <div class="container mt-5">
    <a href="technical_dashboard.php" class="btn btn-primary mb-3">Home</a>
    <h2 class="mb-4">Manage Trips</h2>

    <!-- Success/Error Message -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info">
            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <!-- Trip List -->
    <table class="table table-bordered">
      <thead class="table-dark">
        <tr>
          <th>Trip</th>
          <th>Origin</th>
          <th>Stopovers</th>
          <th>Destination</th>
          <th>Departure</th>
          <th>Arrival</th>
          <th>Fare (UGX)</th>
          <th>Actions</th>
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
              <td><?php echo htmlspecialchars(number_format($trip['fare'])); ?> UGX</td>
              <td>
                <a href="edit_trip.php?id=<?php echo $trip['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete_trip.php?id=<?php echo $trip['id']; ?>" class="btn btn-danger btn-sm"
                   onclick="return confirm('Are you sure you want to delete this trip?');">
                   Delete
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="8" class="text-center">No trips available.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <a href="add_trip.php" class="btn btn-primary">Add New Trip</a>
  </div>

</body>
</html>
