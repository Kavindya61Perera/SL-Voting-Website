<?php
session_start();
$host = "localhost";
$dbname = "slvoting"; // Ensure this matches your database name
$username = "root"; // C
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Fetch user's security questions
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT Question1, Question2, Question3 FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $questions = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answer1 = $_POST['answer1'];
    $answer2 = $_POST['answer2'];
    $answer3 = $_POST['answer3'];

    // Fetch correct answers
    $stmt = $conn->prepare("SELECT Question1Ans, Question2Ans, Question3Ans FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $answers = $result->fetch_assoc();

        // Verify answers
        if (
            strtolower($answers['Question1Ans']) === strtolower($answer1) &&
            strtolower($answers['Question2Ans']) === strtolower($answer2) &&
            strtolower($answers['Question3Ans']) === strtolower($answer3)
        ) {
            echo "Verification successful. Welcome, " . $_SESSION['name'] . "!";
            header("Location:vote.php");
        } else {
            echo "Incorrect answers. Please try again.";
        }
    }
}
?>
