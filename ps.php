<?php
include("connection.php"); // Ensure this path is correct

// Fetch polling station details
$sql = "SELECT * FROM pollingStations";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Polling Stationst</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Font Awesome -->
    <style>
       {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    background-color: #f4f4f4;
}

.container {
    width: 80%;
    margin: 0 auto;
    max-width: 1200px;
}
/* Header Section */
header {
    background-color: #003B6F; /* Dark Blue */
    color: white;
    padding: 10px 0;
}

/* Logo and Title Styling */
header .container {
    display: flex;
    justify-content: space-between; /* Aligns logo and nav on opposite sides */
    align-items: center;
    max-width: 1200px; /* Optional: Limits the width of the container */
    margin: 0 auto; /* Center the container */
}

header .logo {
    display: flex;
    align-items: center;
    margin-right: 250px; /* Add space between the logo and the menu */
}

header .logo img {
    width: 70px;
    height: auto;
    max-width: 100%;
}

header .logo-text {
    margin-left: 15px;
}

header .logo-text .title {
    font-size: 1.5rem;
    font-weight: bold;
}

header .logo-text .subtitle {
    display: block;
    font-size: 1.2rem;
    font-weight: normal;
    margin-top: -5px;
}

/* Navbar Styles */
nav {
    
    display: flex;
    justify-content: flex-start;
    align-items: center;
    flex-grow: 1;
}

nav ul {
    list-style: none;
    display: flex;
    gap: 30px;
}

nav ul li {
    display: inline;
    position: relative;
}

nav ul li a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s;
}

nav ul li a:hover {
    color: #A3D5A9; /* Light Green */
}

/* Dropdown Menu */
nav ul li.dropdown {
    position: relative;
}

nav ul li.dropdown:hover .dropdown-menu {
    display: block;
}

nav .dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background-color: #003B6F;
    padding: 10px 0;
    list-style: none;
    margin: 0;
    border-radius: 5px;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
}

nav .dropdown-menu li {
    padding: 10px;
}

nav .dropdown-menu li a {
    color: white;
    text-decoration: none;
    padding: 5px 10px;
}

nav .dropdown-menu li a:hover {
    background-color: #A3D5A9;
    color: black;
}

/* Hamburger Icon */
.hamburger {
    display: none; /* Hidden by default */
    cursor: pointer;
    font-size: 24px;
    color: white;
}

/* Login Icon */
.login-section {
    display: flex;
    flex-direction: column; /* Stack the icon and text vertically */
    align-items: center; /* Center the content */
    gap: 5px; /* Add some space between the icon and the text */
    cursor: pointer;
    margin-left: 10px; /* Reduce space between menu and login */

}

.login-section i {
    font-size: 24px;
    color: white;
}

.login-section span {
    color: white;
    font-weight: bold;
    font-size: 1rem;
    margin-top: -5px;
}
footer{
    margin-top: 50px;
}
    </style>
</head>
<body>
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

<section id="polling-stations">
    <h2>Polling Stations</h2>
    <?php if ($result->num_rows > 0) { ?>
        <table>
            <tr>
                <th>Polling Station Name</th>
                <th>Opening Hours</th>
                <th>Address</th>
                <th>Map</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['PollingStationName']); ?></td>
                    <td><?php echo htmlspecialchars($row['OpeningHours']); ?></td>
                    <td><?php echo htmlspecialchars($row['Address']); ?></td>
                    <td>
                        <a href="<?php echo htmlspecialchars($row['MapLink']); ?>" target="_blank" style="color: blue; text-decoration: underline;">
                            View Full Map
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No polling stations found.</p>
    <?php } ?>
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

    </body>
</html>
