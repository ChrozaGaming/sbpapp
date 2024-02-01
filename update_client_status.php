<?php
$dbServername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "sbpapp";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $clientId = $conn->real_escape_string($_POST['client_id']);
    $status = $conn->real_escape_string($_POST['status']);

    $sql = "UPDATE clients SET status = '$status' WHERE client_id = '$clientId'";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
}
?>
