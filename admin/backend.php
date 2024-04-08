<?php
// Assuming you have a form field named 'tracker_number'
$trackerNumber = $_POST['tracker_number'];

// Connect to your database (Update the credentials as needed)
$servername = "localhost";
$username = "estherb";
$password = "";
$dbname = "tiizaco1_wp133";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search for the tracker number in the database
$stmt = $conn->prepare("SELECT TrackerNumber FROM tracker_id WHERE TrackerNumber = ?");
$stmt->bind_param("s", $trackerNumber);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Tracker number found in the database, proceed with form submission
    // You can insert further logic here to process the form submission
    echo "Tracker number validated successfully!";
} else {
    // Tracker number not found in the database, show an error message
    echo "Invalid tracker number!";
}

$stmt->close();
$conn->close();
?>