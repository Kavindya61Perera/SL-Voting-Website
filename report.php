<?php
// Database connection
$servername = "localhost";
$dbname = "slvoting";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch candidates ordered by votes in descending order
$sql = "SELECT * FROM candidates ORDER BY votes DESC";
$result = $conn->query($sql);

$place = 1; // Initialize rank counter
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Voting Results</h2>
    <table>
        <tr>
            <th>Place</th>
            <th>ID</th>
            <th>Name</th>
            <th>Party</th>
            <th>Votes</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $place++; ?></td>  <!-- Auto-increment place -->
            <td><?php echo $row["ID"]; ?></td>
            <td><?php echo $row["Name"]; ?></td>
            <td><?php echo $row["Party"]; ?></td>
            <td><?php echo $row["votes"]; ?></td>
        </tr>
        <?php } ?>

    </table>
    
    <br>
    <form action="generate_pdf.php" method="post">
        <button type="submit">Download as PDF</button>
    </form>

</body>
</html>

<?php $conn->close(); ?>
