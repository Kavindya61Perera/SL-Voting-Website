<?php 
include("connection.php"); // Ensure this path is correct

// Fetch data for dynamic content if necessary
// Fetching polling stations
$sql_polling_stations = "SELECT * FROM pollingStations";
$result_polling_stations = $conn->query($sql_polling_stations);

// Fetching candidates
$sql_candidates = "SELECT * FROM candidates";
$result_candidates = $conn->query($sql_candidates);

// Handle form submissions for adding polling stations or candidates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add polling station
    if (isset($_POST['add_polling_station'])) {
        $station_name = $_POST['PollingStationName'];
        $opening_hours = $_POST['OpeningHours'];
        $address = $_POST['Address'];
        $map_link = $_POST['MapLink'];

         // Regular expression to validate time format (e.g., 8.00am - 4.00pm)
         $pattern = '/^([1-9]|1[0-2])\.[0-5][0-9](am|pm)\s*-\s*([1-9]|1[0-2])\.[0-5][0-9](am|pm)$/';

         // Check if the opening hours match the expected time format
         if (preg_match($pattern, $opening_hours)) {
             // If the format is valid, proceed with saving the data
        
        $sql = "INSERT INTO pollingStations (PollingStationName, OpeningHours, Address, MapLink) 
                VALUES ('$station_name', '$opening_hours', '$address', '$map_link')";
        $result = mysqli_query($conn, $sql);
        
        if (!$result) {
            die('Could not enter data: ' . mysqli_error($conn));
        } else {
            echo "Polling Station added successfully.";
        }
    } else {
        // If the format is invalid, show an error message
        echo "Invalid time format. Please use the format: 8.00am - 4.00pm.";
    }
}
    }
    
    // Add candidates
    if (isset($_POST['add_candidate'])) {
        $candidate_name = $_POST['CandidateName'];
        $party = $_POST['Party'];
        $details = $_POST['Details'];

        // Handle image upload
    $image = $_FILES['Image']['name'];
    $target = "uploads/candidates/" . basename($image);

    // Check if the directory exists, create it if it doesn't
    if (!is_dir('uploads/candidates')) {
        mkdir('uploads/candidates', 0755, true);
    }

    if (move_uploaded_file($_FILES['Image']['tmp_name'], $target)) {
        
        $sql = "INSERT INTO candidates (Name, Party, Details, Image) 
                VALUES ('$candidate_name', '$party', '$details', '$image')";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_query($conn, $sql)) {
            echo "Candidate added successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Failed to upload image.";
    }
}


// delete polling stations
    if (isset($_GET['action']) && $_GET['action'] === 'delete_polling_station' && isset($_GET['PollingStationID'])) {
        $stationId = $_GET['PollingStationID'];
    
        // Prepare the DELETE statement with the correct column name
        $stmt = $conn->prepare("DELETE FROM pollingStations WHERE PS_ID = ?");
        
        // Check if the prepare() method failed
        if ($stmt === false) {
            echo json_encode(["status" => "error", "message" => $conn->error]);
            exit;
        }
    
        // Bind the ID to the placeholder
        $stmt->bind_param("i", $stationId);
    
        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
    
        // Close the statement
        $stmt->close();
    }
    

// Handle delete candidate
if (isset($_GET['action']) && $_GET['action'] === 'delete_candidate' && isset($_GET['CandidateID'])) {
    $candidateId = $_GET['CandidateID'];
    
    $stmt = $conn->prepare("DELETE FROM candidates WHERE ID = ?");
    $stmt->bind_param("i", $candidateId);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
    
    $stmt->close();
}


// Closing connection

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Admin Panel - Sri Lanka Online Voting</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Font Awesome -->
    <style> 
    footer{
    margin-top: 50px;
}
</style>

    <script>
    // Function to handle the deletion of the polling station
    function deletePollingStation(stationId) {
        if (confirm("Are you sure you want to delete this polling station?")) {
            // Send AJAX request to delete the polling station
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'adminpanel.php?action=delete_polling_station&PollingStationID=' + stationId, true);

            // Set up the response handler
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText); // Parse the JSON response
                    if (response.status === "success") {
                        alert("Polling station deleted successfully.");
                        // Remove the deleted row from the table dynamically
                        var row = document.getElementById("station-row-" + stationId);
                        row.parentNode.removeChild(row);  // Remove the row from the DOM
                    } else {
                        alert("Error deleting polling station: " + response.message);
                    }
                }
            };

            xhr.onerror = function() {
                alert("An error occurred while trying to delete the polling station.");
            };

            xhr.send();
        }
    }

    // Function to handle the deletion of a candidate
    function deleteCandidate(candidateId) {
        if (confirm("Are you sure you want to delete this candidate?")) {
            // Send AJAX request to delete the candidate
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'adminpanel.php?action=delete_candidate&CandidateID=' + candidateId, true);

            // Set up the response handler
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText); // Parse the JSON response
                    if (response.status === "success") {
                        alert("Candidate deleted successfully.");
                        // Remove the deleted row from the table dynamically
                        var row = document.getElementById("candidate-row-" + candidateId);
                        row.parentNode.removeChild(row);  // Remove the row from the DOM
                    } else {
                        alert("Error deleting candidate: " + response.message);
                    }
                }
            };

            xhr.onerror = function() {
                alert("An error occurred while trying to delete the candidate.");
            };

            xhr.send();
        }
    }

</script>


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
            <span>Welcome to Admin Panel !</span>
             <!-- Logout link with an icon -->
             <a href="adminLogin.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
        </div>
    </header>

    <main>
    <section id="admin-stations">
        <section id="polling-stations">
            <h2>Manage Polling Stations</h2>

            <script>
    function validateOpeningHours() {
        var openingHours = document.getElementById('OpeningHours').value;
        var pattern = /^([1-9]|1[0-2])\.[0-5][0-9](am|pm)\s*-\s*([1-9]|1[0-2])\.[0-5][0-9](am|pm)$/;

        if (!pattern.test(openingHours)) {
            alert("Invalid time format. Please use the format: 8.00am - 4.00pm.");
            return false;  // Prevent form submission
        }
        return true;  // Allow form submission
    }
</script>

            <form method="POST" onsubmit="return validateOpeningHours()">
                <h3>Add New Polling Station</h3>
                <label for="PollingStationName">Polling Station Name:</label>
                <input type="text" id="PollingStationName" name="PollingStationName" required>
                
                <label for="OpeningHours">Opening Hours:</label>
                <input type="text" id="OpeningHours" name="OpeningHours" required placeholder="e.g., 8.00am - 4.00pm">
                
                <label for="Address">Address:</label>
                <input type="text" id="Address" name="Address" required>

                <label for="MapLink">Google Maps Link:</label>
                <input type="url" id="MapLink" name="MapLink" required placeholder="Enter Google Maps URL">
                
                <button type="submit" name="add_polling_station">Add Polling Station</button>
            </form>
            
           
            <h3>Polling Station List</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Opening Hours</th>
                        <th>Address</th>
                        <th>Map</th>
                        <th>Actions</th>
                        <!-- <th>Actions</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_polling_stations->num_rows > 0): ?>
                        <?php while($station = $result_polling_stations->fetch_assoc()): ?>
                            <tr id="station-row-<?php echo $station['PS_ID']; ?>"> <!-- Add ID to the row -->
    <td><?php echo $station['PS_ID']; ?></td>
    <td><?php echo $station['PollingStationName']; ?></td>
    <td><?php echo $station['OpeningHours']; ?></td>
    <td><?php echo $station['Address']; ?></td>
    <td>
    <iframe 
        src="<?php echo $station['MapLink']; ?>" 
        width="100" 
        height="100" 
        style="border:0;" 
        loading="lazy">
    </iframe>
    <br>
    <a href="<?php echo $station['MapLink']; ?>" target="_blank" style="color: #007bff; font-weight: bold;">View Full Map</a>
</td>
    <td>
    <a href="javascript:void(0);" onclick="deletePollingStation(<?php echo $station['PS_ID']; ?>);" class="delete-btn">Delete</a>
    </td>
</tr>

                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6">No polling stations found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
                   
        </section>

        <section id="candidates">
    <h2>Manage Candidates Details</h2>
    <form method="POST" enctype="multipart/form-data">
        <h3>Add New Candidate</h3>
        <label for="CandidateName">Candidate Name:</label>
        <input type="text" id="CandidateName" name="CandidateName" required>
        
        <label for="Party">Party:</label>
        <input type="text" id="Party" name="Party" required>
        
        <label for="Details">Details:</label>
        <input type="text" id="Details" name="Details" required>

        <label for="Image">Candidate Image:</label>
    <input type="file" id="Image" name="Image" accept="image/*" required>
        
        <button type="submit" name="add_candidate">Add Candidate</button>
    </form>
    
    <h3>Candidates List</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Party</th>
                <th>Details</th>
                <th>Image</th>
                <th>Actions</th>
                <!-- <th>Actions</th> -->
            </tr>
        </thead>
        <tbody>
            <?php if ($result_candidates->num_rows > 0): ?>
                <?php while($candidate = $result_candidates->fetch_assoc()): ?>
                    <tr id="candidate-row-<?php echo $candidate['ID']; ?>"> <!-- Add unique ID to the row -->
                        <td><?php echo $candidate['ID']; ?></td>
                        <td><?php echo $candidate['Name']; ?></td>
                        <td><?php echo $candidate['Party']; ?></td>
                        <td><?php echo $candidate['Details']; ?></td>
                        <td>
                        <img src="uploads/candidates/<?php echo $candidate['Image']; ?>" alt="Candidate Image" width="50" height="50">
                    </td>
                        <td>
                        <a href="javascript:void(0);" onclick="deleteCandidate(<?php echo $candidate['ID']; ?>);" class="delete-btn">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">No candidates found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>


        <section id="election-results">
            <h2>Generate Results Report</h2>
            <form action="report.php"  method="POST">
                <button type="submit" name="generate_results">Generate Election Results</button>
            </form>
            
            <?php
            if (isset($_POST['generate_results'])) {
                // Query to get the number of votes for each candidate
                $sql_votes = "SELECT CandidateName, Party, SUM(Votes) as TotalVotes FROM candidatesDetails GROUP BY CandidateName";
                $result_votes = $conn->query($sql_votes);

                echo "<h3>Election Results</h3>";
                if ($result_votes->num_rows > 0) {
                    echo "<table><tr><th>Candidate</th><th>Party</th><th>Total Votes</th></tr>";
                    while($vote = $result_votes->fetch_assoc()) {
                        echo "<tr><td>{$vote['CandidateName']}</td><td>{$vote['Party']}</td><td>{$vote['TotalVotes']}</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No votes found.";
                }
            }
            $conn->close();
?>
        </section>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Sri Lanka Online Voting System. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
