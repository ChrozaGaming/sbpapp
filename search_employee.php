<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "sbpapp";

// Buat dan periksa koneksi
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Dapatkan parameter q dari URL
$q = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

// SQL untuk pencarian data
$sql = "SELECT * FROM employees WHERE first_name LIKE '%$q%' OR last_name LIKE '%$q%'";

$result = $conn->query($sql);

// Tampilkan hasil pencarian
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3">'.
            '<div class="profile-widget">'.
            '<div class="profile-img">'.
            '<a href="profile.html" class="avatar">'.
            '<img src="'.htmlspecialchars($row['avatar_url']).'" alt="User Image">'.
            '</a>'.
            '</div>'.
            '<h4 class="user-name m-t-10 mb-0 text-ellipsis">'.
            '<a href="profile.html">'.htmlspecialchars($row['first_name']).' '.htmlspecialchars($row['last_name']).'</a>'.
            '</h4>'.
            '<div class="small text-muted">'.htmlspecialchars($row['position']).'</div>'.
            '</div>'.
            '</div>';
    }
} else {
    echo '<p>Data karyawan tidak ditemukan.</p>';
}

$conn->close();
?>
