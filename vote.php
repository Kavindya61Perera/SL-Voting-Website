<?php
// Database connection
$servername = "localhost"; // Change this to your database server
$dbname = "slvoting"; // Ensure this matches your database name
$username = "root"; // Change if necessary
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch candidates from the 'candidates' table
$sql = "SELECT name FROM candidates"; // Change 'name' to the correct column in your 'candidate' table
$result = $conn->query($sql);

// Get current time in 24-hour format
date_default_timezone_set("Asia/Colombo"); // Set the correct timezone
$current_time = date("H:i");
$start_time = "08:00";
$end_time = "16:00";
$can_vote = ($current_time >= $start_time && $current_time <= $end_time);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Page</title>
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
    
    <section id="login-section" class="login-container">
    <h1>Welcome to the Voting Page !</h1>
        <?php if (!$can_vote): ?>
            <p style="color: red; font-weight: bold;">Voting is only allowed between 8:00 AM and 3:00 PM.</p>
        <?php endif; ?>
        <form action="submitvote.php" method="POST" class="login-form">
        <div class="form-group">
        <h2>Vote for Your Candidate</h2>
              <label for="voter_nic">Your NIC:</label>
            <input type="text" name="voter_nic" id="voter_nic" placeholder="Enter your NIC" required <?php echo !$can_vote ? 'disabled' : ''; ?>>
        </div>
            
            <div class="form-group">
            <label for="candidate">Select a candidate:</label>
            <select name="candidate" id="candidate" required <?php echo !$can_vote ? 'disabled' : ''; ?>>
                <option value="">-- Select --</option>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
                <?php endwhile; ?>
            </select>

                </div>
            <br>
            <div class="form-group">
            <button type="submit" class="btn-login" <?php echo !$can_vote ? 'disabled' : ''; ?>>Submit Vote</button>
                </div>
        </form>
                </section>

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