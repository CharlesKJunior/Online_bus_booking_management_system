<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'phpqrcode/qrlib.php';

// Test data for the QR code
$data = "https://www.example.com";
$file = 'test_qr.png';

// Generate the QR code and save it to the file
QRcode::png($data, $file);

// Output the result
if (file_exists($file)) {
    echo "QR code generated: <img src='$file' />";
} else {
    echo "Error generating QR code.";
}
?>
