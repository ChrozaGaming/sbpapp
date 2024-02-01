<?php
// Konfigurasi koneksi ke database
$host = "localhost"; // Atau alamat server
$username = "root"; // Username database
$password = ""; // Password database
$dbname = "sbpapp"; // Nama database

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL untuk mengambil data sales overview
$sql = "SELECT year, totalSales, totalRevenue FROM sales_overview ORDER BY year ASC";
$result = $conn->query($sql);

$overview_data = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $overview_data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($overview_data);

$conn->close();
?>
