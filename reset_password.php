<?php
session_start();
include("connection.php");

if (!isset($_SESSION['reset_email']) || !isset($_SESSION['otp_verified'])) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['reset_email'];
    $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password hashing

    // Update password in users table
    $sql = "UPDATE users SET password=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $new_password, $email);
    $stmt->execute();

    // Remove OTP record after successful reset
    $sql = "DELETE FROM password_reset_otp WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Clear session and redirect
    session_destroy();
    header("Location: login.php?message=Password changed successfully");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Font Awesome -->
</head>
<body>
     <!-- Header Section -->
     <header>
    <div class="container">
    <!-- Logo and Title Section -->
    <div class="logo">
      <img src="t-removebg-preview (1).png" alt="Sri Lanka Online Voting Logo" />
      <div class="logo-text">
        <span class="title">SL Voting</span>
        <span class="subtitle">System</span>
      </div>
    </div>
    
    <!-- Navbar with Sub-menu -->
    <nav>
      <div class="hamburger" id="hamburger-icon">
        <i class="fas fa-bars"></i>
      </div>
      <ul id="nav-list">
        <li><a href="Home.html">Home</a></li>
        <li><a href="About.html">About</a></li>
        <li class="dropdown">
          <a href="#">E-Voting <i class="fas fa-caret-down"></i></a>
          <ul class="dropdown-menu">
            <li><a href="#">Candidates</a></li>
              <li><a href="#">Polling Stations</a></li>
              <li><a href="#">Election Results</a></li>
              <li><a href="#">Vote</a></li>
          </ul>
        </li>
        <li><a href="Contact.html">Contact</a></li>
      </ul>
    </nav>
    
    <!-- Login Icon -->
    <a href="Login.html" class="login-link">
    <div class="login-section">
      <i class="fas fa-user"></i>
      <span>Login</span>
    </div>
    </a>
  </div>
    </header>
    
<div id="login-section" class="login-container" style="padding: 12%;">
    <form method="post">
        <label>Enter New Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Reset Password</button>
    </form>
</div>

    <footer>
    <div class="container">
      <p>&copy; 2024 Sri Lanka Online Voting System. All Rights Reserved.</p>
      <ul>
        <li><a href="#privacy">Privacy Policy</a></li>
        <li><a href="#terms">Terms & Conditions</a></li>
      </ul>
    </div>
  </footer>
</body>
</html>
