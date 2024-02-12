<?php
// Koneksi ke database
$dbHost     = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName     = 'sbpapp';

// Membuat koneksi
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Jika metode request adalah POST, proses informasi formulir
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $repeatPassword = $conn->real_escape_string($_POST['repeat_password']);

    // Verifikasi apakah password sama
    // Verifikasi apakah password sama
    if ($password == $repeatPassword) {
        // Enkripsi password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Periksa apakah email sudah terdaftar
        $checkEmail = $conn->query("SELECT id FROM users WHERE email='$email'");
        if ($checkEmail->num_rows > 0) {
            echo "Email sudah terdaftar!";
        } else {
            // Masukkan data ke database
            $insert = $conn->query("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')");
            if ($insert) {
                echo "Registrasi berhasil!";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    } else {
        echo "Password yang dimasukkan tidak sama!";
    }
}

// Tutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Akun</title>
    <!-- Tambahkan referensi CSS Anda di sini jika ada -->
</head>
<body>

<!-- Form Registrasi -->
<form action="register.php" method="post">
    <div class="input-block mb-4">
        <label class="col-form-label">Nama Lengkap<span class="mandatory">*</span></label>
        <input name="name" class="form-control" type="text" required>
    </div>
    <div class="input-block mb-4">
        <label class="col-form-label">Email<span class="mandatory">*</span></label>
        <input name="email" class="form-control" type="text" required>
    </div>
    <div class="input-block mb-4">
        <label class="col-form-label">Password<span class="mandatory">*</span></label>
        <input name="password" class="form-control" type="password" required>
    </div>
    <div class="input-block mb-4">
        <label class="col-form-label">Ulangi Password<span class="mandatory">*</span></label>
        <input name="repeat_password" class="form-control" type="password" required>
    </div>
    <div class="input-block mb-4 text-center">
        <button class="btn btn-primary account-btn" type="submit">Daftar</button>
    </div>
    <div class="account-footer">
        <p>Sudah memiliki akun? <a href="index.php">Masuk</a></p>
    </div>
</form>

<!-- Tambahkan skrip JavaScript Anda di sini jika ada -->

</body>
</html>

