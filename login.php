<?php
// Secure Session Settings (Move this BEFORE session_start)
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);
session_start();
session_regenerate_id(true);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Database connection
$servername = "localhost";
$dbname = "slvoting";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Rate Limiting (Prevent Brute Force Attacks)
$max_attempts = 5; // Max failed attempts before lockout
$lockout_time = 300; // Lockout time in seconds (5 minutes)

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if ($_SESSION['login_attempts'] >= $max_attempts) {
    if (time() - $_SESSION['last_attempt_time'] < $lockout_time) {
        die("Too many failed attempts. Try again after " . ($lockout_time - (time() - $_SESSION['last_attempt_time'])) . " seconds.");
    } else {
        $_SESSION['login_attempts'] = 0; // Reset attempts after lockout time
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Verify reCAPTCHA
    if (empty($recaptcha_response)) {
        die("⚠ Please complete the CAPTCHA.");
    }

    $secretKey = "6LcpjfoqAAAAAPpiiGGBUP2FuLvGBS83L4iroiVB";  // Replace with your reCAPTCHA Secret Key
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptcha_response";
    
    $response = file_get_contents($url);
    $responseData = json_decode($response);

    if (!$responseData->success) {
        die("⚠ CAPTCHA verification failed. Try again.");
    }

    // Fetch user details
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR name = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // Generate OTP
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;

            // Send OTP via email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'kaviya20020806@gmail.com'; // Gmail
                $mail->Password = 'ceuj jbgr nlfk eigm'; // App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('kaviya20020806@gmail.com', 'SL Voting System');
                $mail->addAddress($user['email']);
                $mail->Subject = 'Your OTP Code';
                $mail->Body = "Your OTP Code is: $otp";

                $mail->send();
                echo "OTP email sent successfully!";
                header("Location: verify_otp.php");
                exit();
            } catch (Exception $e) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            }
        } else {
            $_SESSION['login_attempts']++; // Increase failed attempts
            $_SESSION['last_attempt_time'] = time();
            echo "Invalid password.";
        }
    } else {
        $_SESSION['login_attempts']++; // Increase failed attempts
        $_SESSION['last_attempt_time'] = time();
        echo "User not found.";
    }
}
?>
