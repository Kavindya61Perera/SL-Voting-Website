<?php
header("Content-Type: application/json");

// Database connection
$servername = "localhost";
$dbname = "kavi";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

$sql = "SELECT candidate_name, votes FROM votes ORDER BY votes DESC";
$result = $conn->query($sql);

if (!$result) {
    die(json_encode(["error" => "Query failed: " . $conn->error]));
}

$results = [];

while ($row = $result->fetch_assoc()) {
    $results[] = $row;
}

// If no results, return an empty array
echo json_encode($results ?: []);

$conn->close();
?>
