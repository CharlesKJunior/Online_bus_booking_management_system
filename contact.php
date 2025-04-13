<?php
// Include the database connection file
include('db.php'); // Assuming db.php is in the same directory

// Initialize variables for form data
$name = $email = $message = '';

// Check for any status message
$success_message = '';
$error_message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        $success_message = "Message sent successfully!";
    } elseif ($_GET['status'] == 'error') {
        $error_message = "There was an error sending your message. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Dapo Travels</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/contact.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Dapo Travels</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="container text-center">
            <h1>Get in Touch</h1>
            <p>We'd love to hear from you. Contact us through any of the channels below.</p>
        </div>
    </section>

    <!-- Contact Details -->
    <section class="contact-details py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-center">
                    <i class="fas fa-phone-alt fa-3x text-primary"></i>
                    <h4>Call Us</h4>
                    <p>+256 776 629 018</p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fas fa-envelope fa-3x text-danger"></i>
                    <h4>Email Us</h4>
                    <p>support@dapotravels.com</p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fas fa-map-marker-alt fa-3x text-success"></i>
                    <h4>Visit Us</h4>
                    <p>Kampala, Uganda</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form -->
    <section class="contact-form">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h3>Send us a Message</h3>
                    <form id="contactForm" method="POST" action="send_message.php">
                        <!-- Display success or error message -->
                        <?php if ($success_message) { echo "<div class='alert alert-success'>$success_message</div>"; } ?>
                        <?php if ($error_message) { echo "<div class='alert alert-danger'>$error_message</div>"; } ?>

                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label>Message</label>
                            <textarea class="form-control" name="message" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Send Message</button>
                    </form>
                </div>
                <div class="col-lg-6">
                    <h3>Find Us</h3>
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15955.07335124765!2d32.5801584!3d0.3475965!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177dbb5a33246c5d%3A0xb6b87bb4b3b73b7a!2sKampala!5e0!3m2!1sen!2sug!4v1632830427195" 
                        width="100%" height="300" style="border:0;" allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center p-3">
        <p>&copy; 2025 Dapo Travels | All Rights Reserved</p>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/ontact.js"></script>
</body>
</html>  
