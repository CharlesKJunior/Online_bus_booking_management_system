<?php
session_start();
require 'db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start output buffering
ob_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get and trim form data
    $trip_id = $_POST['trip_id'];
    $name = $_POST['name'];
    $email = trim($_POST['email']);
    $phone = $_POST['phone'];
    $seats = $_POST['seats'];

    // Fetch trip details from the database
    $trip_query = "SELECT * FROM trips WHERE id = ?";
    $stmt = $conn->prepare($trip_query);
    if (!$stmt) {
        die("Database prepare error: " . $conn->error);
    }
    $stmt->bind_param("i", $trip_id);
    $stmt->execute();
    $trip_result = $stmt->get_result();
    $trip = $trip_result->fetch_assoc();
    if (!$trip) {
        die("Trip not found.");
    }
    $stmt->close();

    // Use fare if available; otherwise default to 0.
    $fare = isset($trip['fare']) ? $trip['fare'] : 0;

    // Prepare the booking details text
    $booking_details = "Booking Confirmation\n\n";
    $booking_details .= "Trip Name: " . $trip['trip_name'] . "\n";
    $booking_details .= "From: " . $trip['origin'] . "\n";
    $booking_details .= "Stopovers: " . $trip['stopovers'] . "\n";
    $booking_details .= "To: " . $trip['destination'] . "\n";
    $booking_details .= "Departure: " . date("g:ia", strtotime($trip['departure_time'])) . "\n";
    $booking_details .= "Arrival: " . date("g:ia", strtotime($trip['expected_arrival_time'])) . "\n";
    $booking_details .= "Seats Booked: " . $seats . "\n";
    $booking_details .= "Total Fare: UGX " . number_format($fare * $seats) . "\n\n";
    $booking_details .= "Thank you for booking with Dapo Travels.";

    // Setup PHPMailer for Mailtrap
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';   // Mailtrap's SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = '024dfb7ec8577f';  // Your Mailtrap username
        $mail->Password = 'ef599c883a42cc';  // Your Mailtrap password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('noreply@dapotravels.com', 'Dapo Travels');
        $mail->addAddress($email, $name);

        // Content
        $mail->isHTML(false);
        $mail->Subject = 'Booking Confirmation - Dapo Travels';
        $mail->Body    = $booking_details;

        $mail->send();
        //Removed echo 'Booking confirmed. A confirmation email has been sent to your email address.<br>';
    } catch (Exception $e) {
        //Removed echo "Error sending email: {$mail->ErrorInfo}<br>";
    }

    // Save booking details into database including fare
    $stmt = $conn->prepare("INSERT INTO bookings (trip_id, name, email, phone, seats, fare) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Database prepare error (insert): " . $conn->error);
    }
    $stmt->bind_param("isssii", $trip_id, $name, $email, $phone, $seats, $fare);
    $stmt->execute();
    $booking_id = $conn->insert_id; // Get the ID of the inserted booking
    $stmt->close();

    // Store booking details in session for confirmed_trips.php
    $_SESSION['booking_details'] = [
        'booking_id' => $booking_id,
        'trip' => $trip,
        'seats' => $seats,
    ];

    // Redirect to confirmed_trips.php
    header("Location: confirmed_trips.php");
    exit();
}

// End output buffering (no output will be sent if the redirect happened)
ob_end_flush();
?>
