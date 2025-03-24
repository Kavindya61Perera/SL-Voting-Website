<?php 
session_start();
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if the input is an email or username
    $sql = "SELECT * FROM users WHERE email = ? OR name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Verify password (Assuming password is hashed in DB)
        if (password_verify($password, $row['password'])) {
            // Store session and redirect
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['name'];
            header("Location: adminpanel.php");
            exit();
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "Invalid username or password!";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="styles.css">
    <title>Admin Login - Sri Lanka Online Voting</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Font Awesome -->
    <style> 
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
            <div class="welcome-text">
            <span>Welcome to Admin Login !</span>
        </div>
        </div>
    </header>

    <section id="login-section">
    <div class="login-container">
      <div class="login-form">
        <h2>Login to Admin Account</h2>

        <?php if (isset($error)): ?>
                <p style="color: red;"><?= $error ?></p>
            <?php endif; ?>
  
        <form action="adminLogin.php" method="post">
          <div class="form-group">
            <label for="username">Username or Email</label>
            <input type="text" id="username" name="username" placeholder="Enter your username or email" value="" autocomplete="off" required>
          </div>
  
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" value="" autocomplete="off" required>
          </div>
  
          <button type="submit" class="btn-login">Login</button>
          
        </form>

      </div>
    </div>
  </section>
  
  <footer>
        <div class="container">
            <p>&copy; 2024 Sri Lanka Online Voting System. All Rights Reserved.</p>
        </div>
    </footer>
    </body>
</html>