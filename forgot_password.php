<?php
session_start();
include("connection.php");  // Database connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Function to generate a 6-digit OTP
function generateOTP() {
    return rand(100000, 999999);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } else {
        // Check if email exists in users table
        $sql = "SELECT email FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Email found, generate OTP
            $otp = generateOTP();
            $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes")); // OTP expires in 10 minutes

            // Store OTP in the database
            $sql = "INSERT INTO password_reset_otp (email, otp) VALUES (?, ?) 
                    ON DUPLICATE KEY UPDATE otp=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $email, $otp, $otp);
            $stmt->execute();

            // Send OTP via Email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Your SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'kkavindya51@gmail.com'; // Your email
                $mail->Password = 'ietecvavxecrhxtg'; // Your email password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('kkavindya51@gmail.com', 'Your Website');
                $mail->addAddress($email);
                $mail->Subject = "Your OTP for Password Reset";
                $mail->Body = "Your OTP for resetting the password is: $otp. This OTP is valid for 10 minutes.";

                $mail->send();
                $_SESSION['reset_email'] = $email;
                header("Location: verify_otp2.php"); // Redirect to OTP verification page
                exit();
            } catch (Exception $e) {
                $error = "Failed to send OTP. Error: " . $mail->ErrorInfo;
            }
        } else {
            $error = "No account found with this email!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
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
        <label>Enter your registered email:</label>
        <input type="email" name="email" required>
        <button type="submit">Send OTP</button>
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
