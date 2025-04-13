<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Default role for new users
    $role = "passenger"; 

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists
    $check_query = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $_SESSION["error"] = "Email already exists!";
    } else {
        // Insert user into database with role "passenger"
        $query = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hashed_password, $role);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION["success"] = "Registration successful! You can now log in.";
            header("Location: login.php");
            exit();
        } else {
            $_SESSION["error"] = "Registration failed. Try again.";
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Register Page</title>
  <!-- Link to external CSS -->
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <div class="container register-container">
    <!-- Left side: Welcome text -->
    <div class="welcome-text">
      <h1>WELCOME BACK!</h1>
      <p>Dapo Travels Securing your Travels.</p>
    </div>
    <!-- Right side: Register Form -->
    <div class="register-form">
      <h2>Sign Up</h2>
      <?php if (isset($_SESSION["error"])): ?>
        <p class="error"><?php echo $_SESSION["error"]; unset($_SESSION["error"]); ?></p>
      <?php endif; ?>
      <?php if (isset($_SESSION["success"])): ?>
        <p class="success"><?php echo $_SESSION["success"]; unset($_SESSION["success"]); ?></p>
      <?php endif; ?>
      <form action="register.php" method="post">
        <div class="input-group">
         
          <input type="text" name="name" id="register-username" placeholder="Enter your full name" required />
        </div>
        <div class="input-group">
       
          <input type="email" name="email" id="register-email" placeholder="Enter your email" required />
        </div>
        <div class="input-group">
        
          <input type="password" name="password" id="register-password" placeholder="Enter your password" required />
          <input type="password" id="confirm-password" placeholder="Confirm password" required />
        </div>
        <button type="submit">Sign Up</button>
      </form>
      <p><br>
        Already have an account?
        <a href="login.php">Login</a>
      </p>
    </div>
  </div>

  <!-- Link to external JavaScript -->
  <script src="script.js"></script>
</body>
</html>
