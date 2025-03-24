<?php
// Database connection
$servername = "localhost";
$dbname = "slvoting";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from the form
$voter_nic = $_POST['voter_nic'];
$candidate = $_POST['candidate'];

// Check if the user has already voted
$check_query = "SELECT status FROM users WHERE nic = ?";
$stmt = $conn->prepare($check_query);
$stmt->bind_param("s", $voter_nic);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($status);
$stmt->fetch();

if ($stmt->num_rows > 0) {
    if ($status === 'voted') {
        echo "<script>alert('You have already voted!'); window.location.href='Home.html';</script>";
        exit();
    }
    $stmt->close();

    // Insert vote into votes table
    $insert_query = "INSERT INTO votes (voter_nic, candidate) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ss", $voter_nic, $candidate);

    if ($stmt->execute()) {
        // Update voter's status
        $update_query = "UPDATE users SET status = 'voted' WHERE nic = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("s", $voter_nic);
        $stmt->execute();

        // Update candidate vote count
        $update_vote_sql = "UPDATE candidates SET votes = votes + 1 WHERE name = ?";
        $stmt = $conn->prepare($update_vote_sql);
        $stmt->bind_param("s", $candidate);
        $stmt->execute();

        echo "<script>alert('Your vote has been successfully submitted!'); window.location.href='login.html';</script>";
    } else {
        echo "<script>alert('Error submitting your vote. Please try again.'); window.location.href='vote.php';</script>";
    }
} else {
    echo "<script>alert('NIC not found. Please register first.'); window.location.href='vote.php';</script>";
}

$stmt->close();
$conn->close();
?>
