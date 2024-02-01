<?php
session_start();

$dbHost     = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName     = 'sbpapp';

// Membuat koneksi
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Jika metode request adalah POST, proses data login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    
    // Mengambil user dari database
    $query = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if($query->num_rows > 0){
        $userData = $query->fetch_assoc();
        if(password_verify($password, $userData['password'])){
            // Jika password cocok, buat session
            $_SESSION['user_id'] = $userData['id'];
            header("Location: admin-dashboard.php"); // Asumsi ada halaman dashboard
            exit;
        } else {
            $pesan_error = 'Password salah.';
        }
    } else {
        $pesan_error = 'Email tidak ditemukan.';
    }
}

// Tutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <!-- Tambahkan referensi CSS Anda di sini, jika ada -->
</head>
<body>

<div class="main-wrapper">
    <!-- Konten Form Login -->
    <!-- ... Kode HTML Anda ... -->
    <form action="index.php" method="post">
        <!-- ... -->
        <div class="input-block mb-4">
            <label class="col-form-label">Email Address</label>
            <input name="email" class="form-control" type="text" required>
        </div>
        <div class="input-block mb-4">
            <!-- ... -->
            <div class="position-relative">
            <label class="col-form-label">Password</label>
                <input name="password" class="form-control" type="password" required>
                <span class="fa-solid fa-eye-slash" id="toggle-password"></span>
            </div>
        </div>
        <!-- ... -->
        <div class="input-block mb-4 text-center">
            <button class="btn btn-primary account-btn" type="submit">Login</button>
        </div>
        <!-- ... -->
    </form>
    <!-- ... -->
</div>

<!-- Tambahkan skrip JavaScript Anda di sini, jika ada -->

</body>
</html>
