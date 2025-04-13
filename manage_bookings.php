<?php
session_start();
require 'db.php';  // Include the database connection

// --------- Handle CRUD Actions ---------

// DELETE a booking if "delete" is provided in the URL
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    // After deletion, redirect to refresh the page
    header("Location: manage_bookings.php");
    exit;
}

// Variables to control whether we're editing or copying an existing record
$editMode = false;
$bookingData = null;

// EDIT an existing booking
if (isset($_GET['edit'])) {
    $editMode = true;
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookingData = $result->fetch_assoc();
    $stmt->close();
}

// COPY an existing booking (pre-fills the create form)
// It works similar to edit but without the hidden id field.
if (isset($_GET['copy'])) {
    $id = intval($_GET['copy']);
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookingData = $result->fetch_assoc();
    $stmt->close();
    // $editMode remains false so that the form will run the create process.
}

// Process form submissions for creating or updating a booking
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Updating an existing booking
    if (isset($_POST['update'])) {
        $id      = intval($_POST['id']);
        $trip_id = intval($_POST['trip_id']);
        $name    = $_POST['name'];
        $email   = $_POST['email'];
        $phone   = $_POST['phone'];
        $seats   = intval($_POST['seats']);
        $fare    = intval($_POST['fare']);
        $status  = $_POST['status'];

        $stmt = $conn->prepare("UPDATE bookings SET trip_id=?, name=?, email=?, phone=?, seats=?, fare=?, status=? WHERE id=?");
        $stmt->bind_param("issssi si", $trip_id, $name, $email, $phone, $seats, $fare, $status, $id);
        // Alternatively, using "isssiisi" if fare is an integer:
        // $stmt->bind_param("isssiisi", $trip_id, $name, $email, $phone, $seats, $fare, $status, $id);
        $stmt->bind_param("isssiisi", $trip_id, $name, $email, $phone, $seats, $fare, $status, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_bookings.php");
        exit;
    }
    // Creating a new booking (or copying one)
    elseif (isset($_POST['create'])) {
        $trip_id = intval($_POST['trip_id']);
        $name    = $_POST['name'];
        $email   = $_POST['email'];
        $phone   = $_POST['phone'];
        $seats   = intval($_POST['seats']);
        $fare    = intval($_POST['fare']);
        $status  = $_POST['status'];

        $stmt = $conn->prepare("INSERT INTO bookings (trip_id, name, email, phone, seats, booking_date, fare, status) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)");
        $stmt->bind_param("isssiis", $trip_id, $name, $email, $phone, $seats, $fare, $status);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_bookings.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Bookings</title>
    <style>
        /* Simple styling for the table and form */
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        form { margin-top: 20px; }
        input[type="text"], input[type="number"], input[type="email"], select { width: 100%; padding: 6px; margin: 4px 0; }
        input[type="submit"] { padding: 8px 16px; }
    </style>
    <link rel="stylesheet" href="css/manage_bookings.css">
    <script src="js/manage-bookings.js"></script>
</head>
<body>
    <h1>Manage Bookings</h1>
    <a href="technical_dashboard.php" class="btn btn-primary mb-3">Home</a> <br><br>
    <!-- Display existing bookings in a table -->
    <table>
        <tr>
            <th>ID</th>
            <th>Trip ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Seats</th>
            <th>Fare</th>
            <th>Booking Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php
        // Fetch all bookings from the database, ordered by the latest booking date
        $result = $conn->query("SELECT * FROM bookings ORDER BY booking_date DESC");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['trip_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
            echo "<td>" . htmlspecialchars($row['seats']) . "</td>";
            echo "<td>" . htmlspecialchars($row['fare']) . "</td>";
            echo "<td>" . htmlspecialchars($row['booking_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
            echo "<td>
                <a href='manage_bookings.php?edit=" . $row['id'] . "'>Edit</a> | 
                <a href='manage_bookings.php?copy=" . $row['id'] . "'>Copy</a> | 
                <a href='manage_bookings.php?delete=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this booking?\")'>Delete</a>
            </td>";
            echo "</tr>";
        }
        ?>
    </table>

    <!-- Form for Creating a New Booking or Editing an Existing One -->
    <h2><?php echo ($bookingData && $editMode) ? "Edit Booking" : "Create New Booking"; ?></h2>
    <form method="POST" action="manage_bookings.php">
        <?php if ($bookingData && $editMode): ?>
            <!-- Include a hidden field with the booking id for updates -->
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($bookingData['id']); ?>">
        <?php endif; ?>

        <label>Trip ID:</label>
        <input type="number" name="trip_id" required value="<?php echo $bookingData ? htmlspecialchars($bookingData['trip_id']) : ''; ?>">
        
        <label>Name:</label>
        <input type="text" name="name" required value="<?php echo $bookingData ? htmlspecialchars($bookingData['name']) : ''; ?>">
        
        <label>Email:</label>
        <input type="email" name="email" required value="<?php echo $bookingData ? htmlspecialchars($bookingData['email']) : ''; ?>">
        
        <label>Phone:</label>
        <input type="text" name="phone" required value="<?php echo $bookingData ? htmlspecialchars($bookingData['phone']) : ''; ?>">
        
        <label>Seats:</label>
        <input type="number" name="seats" required value="<?php echo $bookingData ? htmlspecialchars($bookingData['seats']) : ''; ?>">
        
        <label>Fare:</label>
        <input type="number" name="fare" required value="<?php echo $bookingData ? htmlspecialchars($bookingData['fare']) : ''; ?>">

        <!-- New Status Field -->
        <label>Status:</label>
        <select name="status" required>
            <option value="Pending" <?php if(isset($bookingData['status']) && $bookingData['status'] === 'Pending') echo 'selected'; ?>>Pending</option>
            <option value="PAID" <?php if(isset($bookingData['status']) && $bookingData['status'] === 'PAID') echo 'selected'; ?>>PAID</option>
        </select>
        
        <br><br>
        <?php if ($bookingData && $editMode): ?>
            <input type="submit" name="update" value="Update Booking">
        <?php else: ?>
            <input type="submit" name="create" value="Create Booking">
        <?php endif; ?>
    </form>
    </form>

    <!-- Professional Footer Section -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-about">
                <h3>About Us</h3>
                <p>We offer reliable booking services. Connect with us to stay up to date with our latest offers and news.</p>
            </div>
            <div class="footer-social">
                <h3>Follow Us</h3>
                <ul class="social-links">
                    <li><a href="https://www.facebook.com/yourpage" target="_blank"><img src="images/Facebook_f_logo_(2021).svg.png" alt="Facebook"></a></li>
                    <li><a href="https://twitter.com/yourprofile" target="_blank"><img src="images/twiter.png" alt="Twitter"></a></li>
                    <li><a href="https://linkedin.com/in/yourprofile" target="_blank"><img src="images/linkedin.png" alt="LinkedIn"></a></li>
                    <li><a href="https://www.instagram.com/yourprofile" target="_blank"><img src="images/insta.png" alt="Instagram"></a></li>
                </ul>
            </div>
            <div class="footer-contact">
                <h3>Contact Us</h3>
                <p>Email: codeislandtechnologies@gmail.com<br>Phone: +256 776 629 018</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> CodeIsland Technologies. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
