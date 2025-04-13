<?php
session_start();
if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] != "admin") {
    header("Location: login.php");
    exit();
}
echo "Welcome, Admin " . $_SESSION["user_name"];
?>
<a href="logout.php">Logout</a>
