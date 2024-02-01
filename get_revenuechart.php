<?php
// Koneksi ke database
$host = "localhost"; // Atau alamat server lain
$username = "root"; // Username untuk database
$password = ""; // Password untuk database
$dbname = "sbpapp"; // Nama database

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL untuk mengambil data revenue
$sql = "SELECT YEAR(year) as year, income, expense FROM revenue ORDER BY year ASC";
$result = $conn->query($sql);

// Inisialisasi array untuk menyimpan data
$revenue_data = [];

// Memeriksa hasil dan memasukkannya ke array
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $revenue_data[] = $row;
    }
}

// Meng-output data sebagai JSON
header('Content-Type: application/json');
echo json_encode($revenue_data);

$conn->close();
?>
