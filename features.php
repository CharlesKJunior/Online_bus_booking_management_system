<?php
session_start();
require 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Features - Dapo Travels</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/features.css">
</head>
<body>

    <!-- Header -->
    <header class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Dapo Travels</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Carousel Section -->
    <section class="carousel-section">
        <div id="featureCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="images/bg6.avif" class="d-block w-100" alt="Online Booking">
                    <div class="carousel-caption">
                        <h3>Seamless Online Booking</h3>
                        <p>Book your tickets with ease using our intuitive platform.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="images/bg5.avif" class="d-block w-100" alt="Secure Payments">
                    <div class="carousel-caption">
                        <h3>Secure Payments</h3>
                        <p>Make transactions with confidence using end-to-end encryption.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="images/bg7.avif" class="d-block w-100" alt="Real-time Tracking">
                    <div class="carousel-caption">
                        <h3>Real-Time Bus Tracking</h3>
                        <p>Stay updated with live location tracking of your bus.</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#featureCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#featureCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h1 class="text-center fw-bold mt-5">Our Premium Features</h1>
            <p class="text-center text-muted mb-4">Experience world-class travel services with cutting-edge technology.</p>

            <div class="row">
                <!-- Feature 1 -->
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center">
                            <i class="bi bi-laptop icon"></i>
                            <h5 class="card-title">Seamless Online Booking</h5>
                            <p class="card-text">Book your tickets effortlessly with our intuitive online platform.</p>
                        </div>
                    </div>
                </div>
                <!-- Feature 2 -->
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center">
                            <i class="bi bi-shield-lock icon"></i>
                            <h5 class="card-title">Secure Payments</h5>
                            <p class="card-text">Make transactions with confidence using end-to-end encryption.</p>
                        </div>
                    </div>
                </div>
                <!-- Feature 3 -->
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center">
                            <i class="bi bi-geo-alt icon"></i>
                            <h5 class="card-title">Real-Time Bus Tracking</h5>
                            <p class="card-text">Stay updated with live location tracking of your bus.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

        <!-- Footer Section -->
        <footer class="footer bg-dark text-light pt-4 pb-2 mt-5">
        <div class="container">
            <div class="row">
                <!-- Company Info -->
                <div class="col-md-4">
                    <h5 class="fw-bold">Dapo Travels</h5>
                    <p class="text-muted">Your reliable travel partner for seamless bookings and secure payments.</p>
                </div>

                <!-- Quick Links -->
                <div class="col-md-4">
                    <h5 class="fw-bold">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-light text-decoration-none">Home</a></li>
                        <li><a href="about.php" class="text-light text-decoration-none">About Us</a></li>
                        <li><a href="contact.php" class="text-light text-decoration-none">Contact</a></li>
                        <li><a href="terms.php" class="text-light text-decoration-none">Terms & Conditions</a></li>
                    </ul>
                </div>

                <!-- Social Media -->
                <div class="col-md-4">
                    <h5 class="fw-bold">Follow Us</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-light fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-light fs-5"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-light fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-light fs-5"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>

            <hr class="border-light">

            <!-- Copyright & Legal -->
            <div class="text-center">
                <p class="mb-0">© <?php echo date("Y"); ?> Dapo Travels. All Rights Reserved.</p>
            </div>
        </div>
    </footer>


    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
