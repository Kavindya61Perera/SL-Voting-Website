<?php
session_start(); // Start the session

// Database configuration
$host = "localhost";
$dbname = "slvoting";
$username = "root";
$password = "";

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle different steps based on the step field
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['step'] === 'register') {
        // Get form inputs
        $_SESSION['name'] = trim($_POST['name']);
        $_SESSION['email'] = trim($_POST['email']);
        $_SESSION['NIC'] = trim($_POST['NIC']);
        $_SESSION['number'] = trim($_POST['number']);
        $_SESSION['Street'] = trim($_POST['Street']);
        $_SESSION['District'] = trim($_POST['District']);
        $_SESSION['Province'] = trim($_POST['Province']);
        $_SESSION['password'] = $_POST['password'];
        $_SESSION['cpassword'] = $_POST['cpassword'];

        // // Validate NIC (Must be 12 digits)
        // if (!preg_match('/^\d{12}$/', $_SESSION['NIC'])) {
        //     echo "Invalid NIC number. It must be exactly 12 digits.";
        //     exit;
        // }

        // Check if NIC already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE NIC = ?");
        $stmt->bind_param("s", $_SESSION['NIC']);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            // Redirect to another page (e.g., nic_exists.html)
    header('Location: nic_exists.html');
    exit;
        }
        $stmt->close();

        // // Validate passwords
        // if ($_SESSION['password'] !== $_SESSION['cpassword']) {
        //     echo "Passwords do not match.";
        //     exit;
        // }

        // Redirect to security question page
        header('Location: question.html');
        exit;

    } elseif ($_POST['step'] === 'verify') {
        // Security Questions Verification Step
        $question1 = $_POST['question1'];
        $answer1 = $_POST['answer1'];
        $question2 = $_POST['question2'];
        $answer2 = $_POST['answer2'];
        $question3 = $_POST['question3'];
        $answer3 = $_POST['answer3'];

        // Hash the password securely
        $hashedPassword = password_hash($_SESSION['password'], PASSWORD_DEFAULT);

        // Insert into database
        $sql = "INSERT INTO users (name, email, NIC, contact_number, Street, District, Province, password, Question1, Question1Ans, Question2, Question2Ans, Question3, Question3Ans)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssssssssss",
            $_SESSION['name'],
            $_SESSION['email'],
            $_SESSION['NIC'],
            $_SESSION['number'],
            $_SESSION['Street'],
            $_SESSION['District'],
            $_SESSION['Province'],
            $hashedPassword,
            $question1,
            $answer1,
            $question2,
            $answer2,
            $question3,
            $answer3
        );

        if ($stmt->execute()) {
            // echo "Registration and verification successful!";
            header('Location: success.html');
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        session_destroy();
        exit;
    }
}

$conn->close();
?>