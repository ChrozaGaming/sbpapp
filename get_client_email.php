<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sbpapp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get client_name from POST data
$client_name = $_POST['client_name'];

// Perform query
$sql = "SELECT email FROM clients WHERE client_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $client_name);
$stmt->execute();
$result = $stmt->get_result();

// Fetch email
$email = '';
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $email = $row['email'];
}

echo $email;

$conn->close();
?>