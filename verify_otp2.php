<?php
session_start();
include("connection.php");

// Set Sri Lanka time zone
date_default_timezone_set('Asia/Colombo');

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['reset_email'];
    $entered_otp = trim($_POST['otp']);

    // Debugging: Print email and OTP to check values
    echo "Email: " . $email . "<br>";
    echo "Entered OTP: " . $entered_otp . "<br>";

    // Check if OTP is correct
    $sql = "SELECT * FROM password_reset_otp WHERE email = ? AND otp = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error in preparing statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("ss", $email, $entered_otp);

    // Execute and check result
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $_SESSION['otp_verified'] = true;

            // Delete OTP from database after verification (optional but recommended)
            $delete_sql = "DELETE FROM password_reset_otp WHERE email = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("s", $email);
            $delete_stmt->execute();
            $delete_stmt->close();

            // Redirect to reset password page
            header("Location: reset_password.php");
            exit();
        } else {
            $error = "Invalid OTP!";
        }
    } else {
        $error = "Database query failed: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
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
    <h2>Enter OTP to Verify</h2>
    <form method="post">
        <label>Enter OTP:</label>
        <input type="text" name="otp" required>
        <button type="submit">Verify OTP</button>
    </form>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
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
