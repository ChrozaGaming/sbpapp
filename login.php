<?php
// Koneksi database
$host = "localhost";
$username = "root";
$password = "";
$database = "sbpapp";

$conn = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}


// Logic login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            session_start();
            if ($login_success) {

                $_SESSION['user_id'] = $user_id;

                header("Location: admin-dashboard.php");
                exit;
            } else {

                // user tidak ditemukan
                // show error message

            }

            $_SESSION['email'] = $email;
            header("Location: admin-dashboard.php");
        } else {
            $error = "Password salah";
        }
    } else {
        $error = "Email belum terdaftar";
    }
}


?>


<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>

    <h2>Login</h2>

    <?php
    if (isset($error)) {
        echo '<p style="color: red;">' . $error . '</p>';
    }
    ?>

    <form action="" method="post">
        <input type="email" name="email" placeholder="Email" required>
        <br><br>
        <input type="password" name="password" placeholder="Password" required>
        <br><br>
        <input type="submit" value="Login">
    </form>

    <p>Belum punya akun? <a href="register.php">Register</a></p>

</body>

</html>