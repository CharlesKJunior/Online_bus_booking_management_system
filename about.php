<?php
session_start();
require 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Dapo Travels</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/about.css">
</head>
<body>

    <!-- Navbar -->
    <header class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Dapo Travels</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="fw-bold">About Dapo Travels</h1>
            <p class="lead">Innovating the future of travel with seamless technology.</p>
        </div>
    </section>

    <!-- About Us Content -->
    <section class="container my-5">
        <div class="row">
            <div class="col-lg-6">
                <h2 class="fw-bold">Who We Are</h2>
                <p>At Dapo Travels, we are revolutionizing the way people book and experience travel. Our mission is to provide seamless online bookings, real-time bus tracking, and secure payments to ensure every journey is smooth and efficient.</p>
            </div>
            <div class="col-lg-6">
                <img src="images/team.webp" class="img-fluid rounded" alt="Team">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="container my-5">
        <h2 class="text-center fw-bold">Our Key Features</h2>
        <p class="text-center text-muted mb-4">Enhancing your travel experience with cutting-edge solutions.</p>

        <div class="row">
            <!-- Feature 1 -->
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-laptop feature-icon"></i>
                        <h5 class="card-title">Seamless Online Booking</h5>
                        <p class="card-text">Book tickets instantly from anywhere with our user-friendly platform.</p>
                    </div>
                </div>
            </div>
            <!-- Feature 2 -->
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-shield-lock feature-icon"></i>
                        <h5 class="card-title">Secure Payments</h5>
                        <p class="card-text">Your transactions are encrypted, ensuring complete security.</p>
                    </div>
                </div>
            </div>
            <!-- Feature 3 -->
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-geo-alt feature-icon"></i>
                        <h5 class="card-title">Real-Time Bus Tracking</h5>
                        <p class="card-text">Track your bus live and plan your journey with precision.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- High-End JavaScript -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cards = document.querySelectorAll(".card");

            cards.forEach(card => {
                card.addEventListener("mouseenter", () => {
                    card.classList.add("shadow-lg");
                });

                card.addEventListener("mouseleave", () => {
                    card.classList.remove("shadow-lg");
                });
            });
        });
    </script>

</body>
</html>
