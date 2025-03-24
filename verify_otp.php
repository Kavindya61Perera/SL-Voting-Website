<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if OTP is set before using it
    if (isset($_POST['otp'])) {
        $entered_otp = $_POST['otp'];

        // Check if the OTP session exists before comparing
        if (isset($_SESSION['otp']) && $entered_otp == $_SESSION['otp']) {
            unset($_SESSION['otp']); // Clear OTP after successful verification
            header("Location: verifyquestion.html"); // Redirect to the security questions page
            exit();
        } else {
            echo "Invalid OTP. Please try again.";
        }
    } else {
        echo "Error: OTP was not entered.";
    }
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Font Awesome -->
  
  <!-- Google Translate Widget -->
  <div id="google_translate_element"></div>
  <script type="text/javascript">
    function googleTranslateElementInit() {
      new google.translate.TranslateElement({
        pageLanguage: 'en',  // Default language of the site
        includedLanguages: 'en,si,ta', // Languages available for translation
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE
      }, 'google_translate_element');
    }
  </script>
  <script type="text/javascript" 
    src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
  </script>
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
        <h1>Enter OTP</h1>
        <form action="verify_otp.php" method="post">
            <input type="text" name="otp" placeholder="Enter OTP" required>
            <button type="submit">Verify</button>
        </form>
    </div>


                    <!-- Footer Section -->
  <footer>
    <div class="container">
      <p>&copy; 2024 Sri Lanka Online Voting System. All Rights Reserved.</p>
      <ul>
        <li><a href="#privacy">Privacy Policy</a></li>
        <li><a href="#terms">Terms & Conditions</a></li>
      </ul>
    </div>
  </footer>

  <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- For Icons -->
  <script src="script.js"></script> <!-- Your JS for Hamburger Menu -->
  <script>
    document.getElementById("section-1").addEventListener("click", function() {
      window.location.href = "polling.html"; // Navigate to the voting page
    });

    document.getElementById("section-2").addEventListener("click", function() {
      window.location.href = "About.html"; // Navigate to the voting page
    });

    document.getElementById("section-3").addEventListener("click", function() {
      window.location.href = "Contact.html"; // Navigate to the voting page
    });

    document.getElementById("section-4").addEventListener("click", function() {
      window.location.href = "Result.html"; // Navigate to the voting page
    });
  </script>


</body>
</html>
