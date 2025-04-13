<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Retrieve user from database
    $query = "SELECT id, name, password, role FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $id, $name, $hashed_password, $role);
        mysqli_stmt_fetch($stmt);

        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["user_name"] = $name;
            $_SESSION["user_email"] = $email; // Store the user email
            $_SESSION["user_role"] = $role;

            // Redirect based on role
            if ($role == "admin" || $role == "technical") {
                header("Location: technical_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $_SESSION["error"] = "Invalid email or password.";
        }
    } else {
        $_SESSION["error"] = "Invalid email or password.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login Page</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <div class="container login-container">
    <div class="login-form">
      <h2>Login</h2>
      <?php if (isset($_SESSION["error"])): ?>
        <p class="error"><?php echo $_SESSION["error"]; unset($_SESSION["error"]); ?></p>
      <?php endif; ?>
      <form action="login.php" method="post">
        <div class="input-group">
          <input type="email" name="email" id="login-username" placeholder="Enter your email" required />
        </div>
        <div class="input-group password-group">
          <input type="password" name="password" id="login-password" placeholder="Enter your password" required />
          
        </div>
        <button type="submit">Login</button>
      </form>
      <p>
        <br>
        Don't have an account?
        <a href="register.php">Sign Up</a>
      </p>
    </div>
    <div class="welcome-text">
      <h1>WELCOME BACK!</h1>
      <p>Dapo Travels securing your travels.</p>
    </div>
  </div>

  <script src="login.js"></script>
</body>
</html>
