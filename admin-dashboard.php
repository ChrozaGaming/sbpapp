<?php
session_start();

// Asumsikan ID user sudah tersimpan di dalam variabel sesi ($_SESSION['user_id']) setelah login berhasil
// Jika tidak, Anda perlu mengatur proses login terlebih dahulu agar ID user disimpan dalam sesi

$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'sbpapp';

// Membuat koneksi
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$userID = $_SESSION['user_id'];

// Membuat kueri untuk mengambil nama pengguna dari database
$sql = $conn->prepare("SELECT id FROM users WHERE id = ?");
$sql->bind_param("i", $userID);
$sql->execute();
$result = $sql->get_result();
$userData = $result->fetch_assoc();

// Menyiapkan query untuk menghitung jumlah tugas per status berdasarkan status_id
$query = "SELECT status_id, COUNT(*) as jumlah FROM tasks GROUP BY status_id";

// Menjalankan query dan menyimpan hasilnya dalam array
$statusCounts = $conn->query($query);

// Inisialisasi counter untuk masing-masing status task
$completedCount = 0;
$inprogressCount = 0;
$onHoldCount = 0;
$pendingCount = 0;
$reviewCount = 0;

// Mengisi counter berdasarkan hasil query
if ($statusCounts) {
    while ($row = $statusCounts->fetch_assoc()) {
        switch ($row['status_id']) {
            case 1:
                $completedCount = $row['jumlah'];
                break;
            case 2:
                $inprogressCount = $row['jumlah'];
                break;
            case 3:
                $onHoldCount = $row['jumlah'];
                break;
            case 4:
                $pendingCount = $row['jumlah'];
                break;
            case 5:
                $reviewCount = $row['jumlah'];
                break;
        }
    }
}

// Mengambil jumlah projects dari database

$query = $conn->query("SELECT COUNT(id) AS project_count FROM projects");
$row = $query->fetch_assoc();
$totalProjects = $row['project_count'];

<<<<<<< HEAD
$sql = "SELECT * FROM projects";
$result = $conn->query($sql);
$projects = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}

=======
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
// Mengambil jumlah klien dari database

$query = $conn->query("SELECT COUNT(client_id) AS client_count FROM clients");
$row = $query->fetch_assoc();
$totalClients = $row['client_count'];

// Mengambil jumlah task dari database

$query = $conn->query("SELECT COUNT(task_id) AS task_count FROM tasks");
$row = $query->fetch_assoc();
$totalTasks = $row['task_count'];

// Mengambil jumlah employee(pegawai) dari database

$query = $conn->query("SELECT COUNT(employee_id) AS employee_count FROM employees");
$row = $query->fetch_assoc();
$totalEmployees = $row['employee_count'];

// Mengambil data pendapatan untuk bulan ini dan bulan sebelumnya
$query = "SELECT amount, record_date FROM earnings ORDER BY record_date DESC LIMIT 2";
$result = $conn->query($query);
$earnings = $result->fetch_all(MYSQLI_ASSOC);

$currentEarnings = 0;
$previousEarnings = 0;
if (count($earnings) == 2) {
    $currentEarnings = $earnings[0]['amount'];
    $previousEarnings = $earnings[1]['amount'];
}

// Query untuk menghitung total jumlah tugas
$queryTotal = "SELECT COUNT(*) as total FROM tasks";
$resultTotal = $conn->query($queryTotal);
$total = $resultTotal->fetch_assoc()['total'];

// Query untuk menghitung jumlah tugas per status
$query = "SELECT status_id, COUNT(*) as jumlah FROM tasks GROUP BY status_id";
$result = $conn->query($query);

$progressData = [];

while ($row = $result->fetch_assoc()) {
    // Menghitung persentase
    $persentase = $total > 0 ? ($row['jumlah'] / $total * 100) : 0;
    $progressData[$row['status_id']] = ['jumlah' => $row['jumlah'], 'persentase' => $persentase];
}

// Menghitung persentase pertumbuhan
$growth = 0;
if ($previousEarnings > 0) {
    $growth = (($currentEarnings - $previousEarnings) / $previousEarnings) * 100;
}

// Menghitung persentase pertumbuhan
$growth = 0;
if ($previousEarnings > 0) {
    $growth = (($currentEarnings - $previousEarnings) / $previousEarnings) * 100;
}

// Query SQL untuk menghitung tugas yang memiliki status_id lebih dari 1
$query = "SELECT COUNT(*) as jumlah_overdue FROM tasks WHERE status_id > 1";

// Menjalankan Query
$result = $conn->query($query);

// Mengambil data hasil query
if ($result) {
    $data = $result->fetch_assoc();
    $jumlah_overdue = $data['jumlah_overdue'];
} else {
    $jumlah_overdue = 0; // Nilai default jika query tidak menghasilkan data atau terjadi error
}


// Query SQL untuk mengambil dua data biaya terakhir
$sql = "SELECT `jumlah`, `tanggal` FROM `biaya` ORDER BY `tanggal` DESC LIMIT 2";

$result = $conn->query($sql);

$biaya_bulan_ini = 0;
$biaya_bulan_sebelum = 0;
$persentase_penurunan = 0;
if ($result->num_rows > 0) {
    // mengambil data untuk bulan ini dan bulan sebelumnya
    $row = $result->fetch_assoc();
    $biaya_bulan_ini = $row['jumlah'];

    $row = $result->fetch_assoc();
    $biaya_bulan_sebelum = $row['jumlah'];

    // hitung penurunan
    if ($biaya_bulan_sebelum > 0) {
        $persentase_penurunan = ($biaya_bulan_sebelum - $biaya_bulan_ini) / $biaya_bulan_sebelum * 100;
    }
}

$query = "SELECT month_year, profit, previous_month_profit, profit_change_percentage FROM monthly_profits ORDER BY month_year DESC LIMIT 1";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $latestProfitData = $result->fetch_assoc();
} else {
    $latestProfitData = null;
}

$query = "SELECT leaves_taken, total_leaves FROM daily_leaves WHERE date = CURDATE()";
$result = $conn->query($query);

$todayLeave = 0;
$totalLeave = 0;

if ($result && $result->num_rows > 0) {
    // output data setiap baris
    while ($row = $result->fetch_assoc()) {
        $todayLeave = $row["leaves_taken"];
        $totalLeave = $row["total_leaves"];
    }
}

$query = "SELECT COUNT(*) as total_invoices, SUM(is_paid = 0) as pending_invoices FROM invoices";
$result = $conn->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $totalInvoices = $row["total_invoices"];
    $pendingInvoices = $row["pending_invoices"];
} else {
    echo "Error: " . $conn->error;
}

<<<<<<< HEAD
// Mencari jumlah proyek yang telah selesai
$sql = "SELECT COUNT(*) as jumlah_selesai FROM projects WHERE status = 'Completed'";
$result = $conn->query($sql);
$jumlahSelesai = 0;

if ($result && $result->num_rows > 0) {
    // output data dari setiap baris
    while ($row = $result->fetch_assoc()) {
        $jumlahSelesai = $row["jumlah_selesai"];
    }
}

// Mendapatkan total jumlah proyek
$sqlTotal = "SELECT COUNT(*) as total_proyek FROM projects";
$resultTotal = $conn->query($sqlTotal);
$totalProyek = 0;

if ($resultTotal && $resultTotal->num_rows > 0) {
    while ($rowTotal = $resultTotal->fetch_assoc()) {
        $totalProyek = $rowTotal["total_proyek"];
    }
}


=======
$query = "SELECT COUNT(*) as total_proyek, COUNT(IF(status='selesai', 1, NULL)) as proyek_selesai FROM proyek";
$result = $conn->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $totalProyek = $row["total_proyek"];
    $proyekSelesai = $row["proyek_selesai"];
} else {
    echo "Error: " . $conn->error;
}

>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
$query = "SELECT COUNT(*) as total_tiket, COUNT(IF(status='terbuka', 1, NULL)) as tiket_terbuka FROM tiket";
$result = $conn->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $totalTiket = $row["total_tiket"];
    $tiketTerbuka = $row["tiket_terbuka"];
} else {
    echo "Error: " . $conn->error;
}

// Mengambil data clients
$sql = "SELECT client_id, client_name, email, status FROM clients";
$result = $conn->query($sql);

$clients = [];
if ($result->num_rows > 0) {
    // Mengisi array dengan data
    while ($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }
} else {
    echo "0 results";
}

// Kueri jumlah tiket tertutup
$query = "SELECT COUNT(*) as total_tiket_tertutup FROM tiket WHERE status='tertutup'";
$result = $conn->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $tiketTertutup = $row["total_tiket_tertutup"];
} else {
    echo "Error: " . $conn->error;
}

// Kueri jumlah total tugas
$query = "SELECT COUNT(*) as total_tugas FROM tugas";
$result = $conn->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $totalTugas = $row["total_tugas"];
} else {
    echo "Error: " . $conn->error;
}

$sql = "SELECT * FROM status_progres";
$result = $conn->query($sql);

$progresData = [];
if ($result->num_rows > 0) {
    // Keluarkan data setiap baris
    while ($row = $result->fetch_assoc()) {
        array_push($progresData, $row);
    }
} else {
    echo "0 hasil";
}

// Membuat array kosong untuk menyimpan data
$invoices = [];

// SQL untuk mengambil data invoice
$sql = "SELECT * FROM invoices";
$result = $conn->query($sql);

// Memeriksa apakah ada hasil dan mengisinya ke array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $invoices[] = $row;
    }
} else {
    echo "0 results";
}

// Membuat string query
$query = "SELECT kategori, jumlah FROM tugas";
$result = $conn->query($query);

$tasks = [];

// Ambil data dan masukkan ke array $tasks
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($tasks, $row);
    }
} else {
    echo "0 hasil";
}


// Fungsi untuk mendapatkan jumlah tugas per status
function getTaskCountByStatus($statusName, $conn)
{
    // SQL query untuk menghitung jumlah tugas per status
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM tasks JOIN task_status ON tasks.status_id = task_status.status_id WHERE status_name = ?");
    $stmt->bind_param("s", $statusName);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return $result['count'];
}

$result = $conn->query("SELECT nama, tanggal, status FROM absensi");


// // Menyimpan jumlah tugas per status dalam variabel

$userNameDisplay = $userData ? $userData['id'] : "Guest";

$conn->close();

// Mengatur variabel nama pengguna yang akan ditampilkan


?>

<!DOCTYPE html>
<<<<<<< HEAD
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
      data-sidebar-image="none">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="Smarthr - Bootstrap Admin Template"/>
    <meta name="keywords"
          content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects"/>
    <meta name="author" content="Dreamguys - Bootstrap Admin Template"/>
    <title>Dashboard - HRMS admin template</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png"/>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css"/>

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css"/>
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css"/>

    <!-- Lineawesome CSS -->
    <link rel="stylesheet" href="assets/css/line-awesome.min.css"/>
    <link rel="stylesheet" href="assets/css/material.css"/>

    <!-- Chart CSS -->
    <link rel="stylesheet" href="assets/plugins/morris/morris.css"/>

    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css"/>
</head>

<body>
<!-- Main Wrapper -->
<div class="main-wrapper">
    <!-- Header -->
    <div class="header">
        <!-- Logo -->
        <div class="header-left">
            <a href="admin-dashboard.html" class="logo">
                <img src="assets/img/logo.png" width="40" height="40" alt="Logo"/>
            </a>
            <a href="admin-dashboard.html" class="logo2">
                <img src="assets/img/logo2.png" width="40" height="40" alt="Logo"/>
            </a>
        </div>
        <!-- /Logo -->

        <a id="toggle_btn" href="javascript:void(0);">
=======
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Smarthr - Bootstrap Admin Template" />
    <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects" />
    <meta name="author" content="Dreamguys - Bootstrap Admin Template" />
    <title>Dashboard - HRMS admin template</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />

    <!-- Lineawesome CSS -->
    <link rel="stylesheet" href="assets/css/line-awesome.min.css" />
    <link rel="stylesheet" href="assets/css/material.css" />

    <!-- Chart CSS -->
    <link rel="stylesheet" href="assets/plugins/morris/morris.css" />

    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>
    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Header -->
        <div class="header">
            <!-- Logo -->
            <div class="header-left">
                <a href="admin-dashboard.html" class="logo">
                    <img src="assets/img/logo.png" width="40" height="40" alt="Logo" />
                </a>
                <a href="admin-dashboard.html" class="logo2">
                    <img src="assets/img/logo2.png" width="40" height="40" alt="Logo" />
                </a>
            </div>
            <!-- /Logo -->

            <a id="toggle_btn" href="javascript:void(0);">
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                <span class="bar-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
<<<<<<< HEAD
        </a>

        <!-- Header Title -->
        <div class="page-title-box">
            <h3>SBP MANAGEMENT ADMINISTRATION APP</h3>
        </div>
        <!-- /Header Title -->

        <a id="mobile_btn" class="mobile_btn" href="#sidebar"><i class="fa-solid fa-bars"></i></a>

        <!-- Header Menu -->
        <ul class="nav user-menu">
            <!-- Search -->
            <li class="nav-item">
                <div class="top-nav-search">
                    <a href="javascript:void(0);" class="responsive-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </a>
                    <form action="search.html">
                        <input class="form-control" type="text" placeholder="Search here"/>
                        <button class="btn" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                </div>
            </li>
            <!-- /Search -->

            <!-- Flag -->
            <li class="nav-item dropdown has-arrow flag-nav">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
                    <img src="assets/img/flags/us.png" alt="Flag" height="20"/>
                    <span>English</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="assets/img/flags/us.png" alt="Flag" height="16"/>
                        English
                    </a>
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="assets/img/flags/fr.png" alt="Flag" height="16"/>
                        French
                    </a>
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="assets/img/flags/es.png" alt="Flag" height="16"/>
                        Spanish
                    </a>
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="assets/img/flags/de.png" alt="Flag" height="16"/>
                        German
                    </a>
                </div>
            </li>
            <!-- /Flag -->

            <!-- Notifications -->
            <li class="nav-item dropdown">
                <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                    <i class="fa-regular fa-bell"></i>
                    <span class="badge rounded-pill">3</span>
                </a>
                <div class="dropdown-menu notifications">
                    <div class="topnav-dropdown-header">
                        <span class="notification-title">Notifications</span>
                        <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
                    </div>
                    <div class="noti-content">
                        <ul class="notification-list">
                            <li class="notification-message">
                                <a href="activities.html">
                                    <div class="chat-block d-flex">
                                            <span class="avatar flex-shrink-0">
                                                <img src="assets/img/profiles/avatar-02.jpg" alt="User Image"/>
                                            </span>
                                        <div class="media-body flex-grow-1">
                                            <p class="noti-details">
                                                <span class="noti-title">John Doe</span> added new
                                                task
                                                <span class="noti-title">Patient appointment booking</span>
                                            </p>
                                            <p class="noti-time">
                                                <span class="notification-time">4 mins ago</span>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="notification-message">
                                <a href="activities.html">
                                    <div class="chat-block d-flex">
                                            <span class="avatar flex-shrink-0">
                                                <img src="assets/img/profiles/avatar-03.jpg" alt="User Image"/>
                                            </span>
                                        <div class="media-body flex-grow-1">
                                            <p class="noti-details">
                                                <span class="noti-title">Tarah Shropshire</span>
                                                changed the task name
                                                <span class="noti-title">Appointment booking with payment gateway</span>
                                            </p>
                                            <p class="noti-time">
                                                <span class="notification-time">6 mins ago</span>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="notification-message">
                                <a href="activities.html">
                                    <div class="chat-block d-flex">
                                            <span class="avatar flex-shrink-0">
                                                <img src="assets/img/profiles/avatar-06.jpg" alt="User Image"/>
                                            </span>
                                        <div class="media-body flex-grow-1">
                                            <p class="noti-details">
                                                <span class="noti-title">Misty Tison</span> added
                                                <span class="noti-title">Domenic Houston</span> and
                                                <span class="noti-title">Claire Mapes</span> to
                                                project
                                                <span class="noti-title">Doctor available module</span>
                                            </p>
                                            <p class="noti-time">
                                                <span class="notification-time">8 mins ago</span>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="notification-message">
                                <a href="activities.html">
                                    <div class="chat-block d-flex">
                                            <span class="avatar flex-shrink-0">
                                                <img src="assets/img/profiles/avatar-17.jpg" alt="User Image"/>
                                            </span>
                                        <div class="media-body flex-grow-1">
                                            <p class="noti-details">
                                                <span class="noti-title">Rolland Webber</span>
                                                completed task
                                                <span class="noti-title">Patient and Doctor video conferencing</span>
                                            </p>
                                            <p class="noti-time">
                                                <span class="notification-time">12 mins ago</span>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="notification-message">
                                <a href="activities.html">
                                    <div class="chat-block d-flex">
                                            <span class="avatar flex-shrink-0">
                                                <img src="assets/img/profiles/avatar-13.jpg" alt="User Image"/>
                                            </span>
                                        <div class="media-body flex-grow-1">
                                            <p class="noti-details">
                                                <span class="noti-title">Bernardo Galaviz</span>
                                                added new task
                                                <span class="noti-title">Private chat module</span>
                                            </p>
                                            <p class="noti-time">
                                                <span class="notification-time">2 days ago</span>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="topnav-dropdown-footer">
                        <a href="activities.html">View all Notifications</a>
                    </div>
                </div>
            </li>
            <!-- /Notifications -->

            <!-- Message Notifications -->
            <li class="nav-item dropdown">
                <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                    <i class="fa-regular fa-comment"></i><span class="badge rounded-pill">8</span>
                </a>
                <div class="dropdown-menu notifications">
                    <div class="topnav-dropdown-header">
                        <span class="notification-title">Messages</span>
                        <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
                    </div>
                    <div class="noti-content">
                        <ul class="notification-list">
                            <li class="notification-message">
                                <a href="chat.html">
                                    <div class="list-item">
                                        <div class="list-left">
                                                <span class="avatar">
                                                    <img src="assets/img/profiles/avatar-09.jpg" alt="User Image"/>
                                                </span>
                                        </div>
                                        <div class="list-body">
                                            <span class="message-author">Richard Miles </span>
                                            <span class="message-time">12:28 AM</span>
                                            <div class="clearfix"></div>
                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur
                                                    adipiscing</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="notification-message">
                                <a href="chat.html">
                                    <div class="list-item">
                                        <div class="list-left">
                                                <span class="avatar">
                                                    <img src="assets/img/profiles/avatar-02.jpg" alt="User Image"/>
                                                </span>
                                        </div>
                                        <div class="list-body">
                                            <span class="message-author">John Doe</span>
                                            <span class="message-time">6 Mar</span>
                                            <div class="clearfix"></div>
                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur
                                                    adipiscing</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="notification-message">
                                <a href="chat.html">
                                    <div class="list-item">
                                        <div class="list-left">
                                                <span class="avatar">
                                                    <img src="assets/img/profiles/avatar-03.jpg" alt="User Image"/>
                                                </span>
                                        </div>
                                        <div class="list-body">
                                            <span class="message-author"> Tarah Shropshire </span>
                                            <span class="message-time">5 Mar</span>
                                            <div class="clearfix"></div>
                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur
                                                    adipiscing</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="notification-message">
                                <a href="chat.html">
                                    <div class="list-item">
                                        <div class="list-left">
                                                <span class="avatar">
                                                    <img src="assets/img/profiles/avatar-05.jpg" alt="User Image"/>
                                                </span>
                                        </div>
                                        <div class="list-body">
                                            <span class="message-author">Mike Litorus</span>
                                            <span class="message-time">3 Mar</span>
                                            <div class="clearfix"></div>
                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur
                                                    adipiscing</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="notification-message">
                                <a href="chat.html">
                                    <div class="list-item">
                                        <div class="list-left">
                                                <span class="avatar">
                                                    <img src="assets/img/profiles/avatar-08.jpg" alt="User Image"/>
                                                </span>
                                        </div>
                                        <div class="list-body">
                                                <span class="message-author">
                                                    Catherine Manseau
                                                </span>
                                            <span class="message-time">27 Feb</span>
                                            <div class="clearfix"></div>
                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur
                                                    adipiscing</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="topnav-dropdown-footer">
                        <a href="chat.html">View all Messages</a>
                    </div>
                </div>
            </li>
            <!-- /Message Notifications -->

            <li class="nav-item dropdown has-arrow main-drop">
                <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                        <span class="user-img"><img src="assets/img/profiles/avatar-21.jpg" alt="User Image"/>
                            <span class="status online"></span></span>
                    <span>Admin</span>
                </a>
                <div class="dropdown-menu">
=======
            </a>

            <!-- Header Title -->
            <div class="page-title-box">
                <h3>SBP MANAGEMENT ADMINISTRATION APP</h3>
            </div>
            <!-- /Header Title -->

            <a id="mobile_btn" class="mobile_btn" href="#sidebar"><i class="fa-solid fa-bars"></i></a>

            <!-- Header Menu -->
            <ul class="nav user-menu">
                <!-- Search -->
                <li class="nav-item">
                    <div class="top-nav-search">
                        <a href="javascript:void(0);" class="responsive-search">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </a>
                        <form action="search.html">
                            <input class="form-control" type="text" placeholder="Search here" />
                            <button class="btn" type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </form>
                    </div>
                </li>
                <!-- /Search -->

                <!-- Flag -->
                <li class="nav-item dropdown has-arrow flag-nav">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
                        <img src="assets/img/flags/us.png" alt="Flag" height="20" />
                        <span>English</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="javascript:void(0);" class="dropdown-item">
                            <img src="assets/img/flags/us.png" alt="Flag" height="16" />
                            English
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item">
                            <img src="assets/img/flags/fr.png" alt="Flag" height="16" />
                            French
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item">
                            <img src="assets/img/flags/es.png" alt="Flag" height="16" />
                            Spanish
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item">
                            <img src="assets/img/flags/de.png" alt="Flag" height="16" />
                            German
                        </a>
                    </div>
                </li>
                <!-- /Flag -->

                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                        <i class="fa-regular fa-bell"></i>
                        <span class="badge rounded-pill">3</span>
                    </a>
                    <div class="dropdown-menu notifications">
                        <div class="topnav-dropdown-header">
                            <span class="notification-title">Notifications</span>
                            <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
                        </div>
                        <div class="noti-content">
                            <ul class="notification-list">
                                <li class="notification-message">
                                    <a href="activities.html">
                                        <div class="chat-block d-flex">
                                            <span class="avatar flex-shrink-0">
                                                <img src="assets/img/profiles/avatar-02.jpg" alt="User Image" />
                                            </span>
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details">
                                                    <span class="noti-title">John Doe</span> added new
                                                    task
                                                    <span class="noti-title">Patient appointment booking</span>
                                                </p>
                                                <p class="noti-time">
                                                    <span class="notification-time">4 mins ago</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="activities.html">
                                        <div class="chat-block d-flex">
                                            <span class="avatar flex-shrink-0">
                                                <img src="assets/img/profiles/avatar-03.jpg" alt="User Image" />
                                            </span>
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details">
                                                    <span class="noti-title">Tarah Shropshire</span>
                                                    changed the task name
                                                    <span class="noti-title">Appointment booking with payment gateway</span>
                                                </p>
                                                <p class="noti-time">
                                                    <span class="notification-time">6 mins ago</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="activities.html">
                                        <div class="chat-block d-flex">
                                            <span class="avatar flex-shrink-0">
                                                <img src="assets/img/profiles/avatar-06.jpg" alt="User Image" />
                                            </span>
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details">
                                                    <span class="noti-title">Misty Tison</span> added
                                                    <span class="noti-title">Domenic Houston</span> and
                                                    <span class="noti-title">Claire Mapes</span> to
                                                    project
                                                    <span class="noti-title">Doctor available module</span>
                                                </p>
                                                <p class="noti-time">
                                                    <span class="notification-time">8 mins ago</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="activities.html">
                                        <div class="chat-block d-flex">
                                            <span class="avatar flex-shrink-0">
                                                <img src="assets/img/profiles/avatar-17.jpg" alt="User Image" />
                                            </span>
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details">
                                                    <span class="noti-title">Rolland Webber</span>
                                                    completed task
                                                    <span class="noti-title">Patient and Doctor video conferencing</span>
                                                </p>
                                                <p class="noti-time">
                                                    <span class="notification-time">12 mins ago</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="activities.html">
                                        <div class="chat-block d-flex">
                                            <span class="avatar flex-shrink-0">
                                                <img src="assets/img/profiles/avatar-13.jpg" alt="User Image" />
                                            </span>
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details">
                                                    <span class="noti-title">Bernardo Galaviz</span>
                                                    added new task
                                                    <span class="noti-title">Private chat module</span>
                                                </p>
                                                <p class="noti-time">
                                                    <span class="notification-time">2 days ago</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="topnav-dropdown-footer">
                            <a href="activities.html">View all Notifications</a>
                        </div>
                    </div>
                </li>
                <!-- /Notifications -->

                <!-- Message Notifications -->
                <li class="nav-item dropdown">
                    <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                        <i class="fa-regular fa-comment"></i><span class="badge rounded-pill">8</span>
                    </a>
                    <div class="dropdown-menu notifications">
                        <div class="topnav-dropdown-header">
                            <span class="notification-title">Messages</span>
                            <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
                        </div>
                        <div class="noti-content">
                            <ul class="notification-list">
                                <li class="notification-message">
                                    <a href="chat.html">
                                        <div class="list-item">
                                            <div class="list-left">
                                                <span class="avatar">
                                                    <img src="assets/img/profiles/avatar-09.jpg" alt="User Image" />
                                                </span>
                                            </div>
                                            <div class="list-body">
                                                <span class="message-author">Richard Miles </span>
                                                <span class="message-time">12:28 AM</span>
                                                <div class="clearfix"></div>
                                                <span class="message-content">Lorem ipsum dolor sit amet, consectetur
                                                    adipiscing</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="chat.html">
                                        <div class="list-item">
                                            <div class="list-left">
                                                <span class="avatar">
                                                    <img src="assets/img/profiles/avatar-02.jpg" alt="User Image" />
                                                </span>
                                            </div>
                                            <div class="list-body">
                                                <span class="message-author">John Doe</span>
                                                <span class="message-time">6 Mar</span>
                                                <div class="clearfix"></div>
                                                <span class="message-content">Lorem ipsum dolor sit amet, consectetur
                                                    adipiscing</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="chat.html">
                                        <div class="list-item">
                                            <div class="list-left">
                                                <span class="avatar">
                                                    <img src="assets/img/profiles/avatar-03.jpg" alt="User Image" />
                                                </span>
                                            </div>
                                            <div class="list-body">
                                                <span class="message-author"> Tarah Shropshire </span>
                                                <span class="message-time">5 Mar</span>
                                                <div class="clearfix"></div>
                                                <span class="message-content">Lorem ipsum dolor sit amet, consectetur
                                                    adipiscing</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="chat.html">
                                        <div class="list-item">
                                            <div class="list-left">
                                                <span class="avatar">
                                                    <img src="assets/img/profiles/avatar-05.jpg" alt="User Image" />
                                                </span>
                                            </div>
                                            <div class="list-body">
                                                <span class="message-author">Mike Litorus</span>
                                                <span class="message-time">3 Mar</span>
                                                <div class="clearfix"></div>
                                                <span class="message-content">Lorem ipsum dolor sit amet, consectetur
                                                    adipiscing</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="chat.html">
                                        <div class="list-item">
                                            <div class="list-left">
                                                <span class="avatar">
                                                    <img src="assets/img/profiles/avatar-08.jpg" alt="User Image" />
                                                </span>
                                            </div>
                                            <div class="list-body">
                                                <span class="message-author">
                                                    Catherine Manseau
                                                </span>
                                                <span class="message-time">27 Feb</span>
                                                <div class="clearfix"></div>
                                                <span class="message-content">Lorem ipsum dolor sit amet, consectetur
                                                    adipiscing</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="topnav-dropdown-footer">
                            <a href="chat.html">View all Messages</a>
                        </div>
                    </div>
                </li>
                <!-- /Message Notifications -->

                <li class="nav-item dropdown has-arrow main-drop">
                    <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                        <span class="user-img"><img src="assets/img/profiles/avatar-21.jpg" alt="User Image" />
                            <span class="status online"></span></span>
                        <span>Admin</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="profile.html">My Profile</a>
                        <a class="dropdown-item" href="settings.html">Settings</a>
                        <a class="dropdown-item" href="index.html">Logout</a>
                    </div>
                </li>
            </ul>
            <!-- /Header Menu -->

            <!-- Mobile Menu -->
            <div class="dropdown mobile-user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                    <a class="dropdown-item" href="profile.html">My Profile</a>
                    <a class="dropdown-item" href="settings.html">Settings</a>
                    <a class="dropdown-item" href="index.html">Logout</a>
                </div>
<<<<<<< HEAD
            </li>
        </ul>
        <!-- /Header Menu -->

        <!-- Mobile Menu -->
        <div class="dropdown mobile-user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i
                        class="fa-solid fa-ellipsis-vertical"></i></a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="profile.html">My Profile</a>
                <a class="dropdown-item" href="settings.html">Settings</a>
                <a class="dropdown-item" href="index.html">Logout</a>
            </div>
        </div>
        <!-- /Mobile Menu -->
    </div>
    <!-- /Header -->

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-inner slimscroll">
            <div id="sidebar-menu" class="sidebar-menu">
                <nav class="greedys sidebar-horizantal">
                    <ul class="list-inline-item list-unstyled links">
=======
            </div>
            <!-- /Mobile Menu -->
        </div>
        <!-- /Header -->

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <nav class="greedys sidebar-horizantal">
                        <ul class="list-inline-item list-unstyled links">
                            <li class="menu-title">
                                <span>Main</span>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="la la-dashboard"></i> <span> Dashboard</span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="admin-dashboard.html">Admin Dashboard</a></li>
                                    <li>
                                        <a href="employee-dashboard.html">Employee Dashboard</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="la la-cube"></i> <span> Apps</span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="chat.html">Chat</a></li>
                                    <li class="submenu">
                                        <a href="#"><span> Calls</span> <span class="menu-arrow"></span></a>
                                        <ul>
                                            <li><a href="voice-call.html">Voice Call</a></li>
                                            <li><a href="video-call.html">Video Call</a></li>
                                            <li><a href="outgoing-call.html">Outgoing Call</a></li>
                                            <li><a href="incoming-call.html">Incoming Call</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="events.html">Calendar</a></li>
                                    <li><a href="contacts.html">Contacts</a></li>
                                    <li><a href="inbox.html">Email</a></li>
                                    <li><a href="file-manager.html">File Manager</a></li>
                                </ul>
                            </li>
                            <li class="menu-title">
                                <span>Employees</span>
                            </li>
                            <li class="submenu">
                                <a href="#" class="noti-dot"><i class="la la-user"></i> <span> Employees</span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="employees.html">All Employees</a></li>
                                    <li><a href="holidays.html">Holidays</a></li>
                                    <li>
                                        <a href="leaves.html">Leaves (Admin)
                                            <span class="badge rounded-pill bg-primary float-end">1</span></a>
                                    </li>
                                    <li>
                                        <a href="leaves-employee.html">Leaves (Employee)</a>
                                    </li>
                                    <li><a href="leave-settings.html">Leave Settings</a></li>
                                    <li><a href="attendance.html">Attendance (Admin)</a></li>
                                    <li>
                                        <a href="attendance-employee.html">Attendance (Employee)</a>
                                    </li>
                                    <li><a href="departments.html">Departments</a></li>
                                    <li><a href="designations.html">Designations</a></li>
                                    <li><a href="timesheet.html">Timesheet</a></li>
                                    <li>
                                        <a href="shift-scheduling.html">Shift & Schedule</a>
                                    </li>
                                    <li><a href="overtime.html">Overtime</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="clients.html"><i class="la la-users"></i> <span>Clients</span></a>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="la la-rocket"></i> <span> Projects</span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="projects.html">Projects</a></li>
                                    <li><a href="tasks.html">Tasks</a></li>
                                    <li><a href="task-board.html">Task Board</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="leads.html"><i class="la la-user-secret"></i> <span>Leads</span></a>
                            </li>
                            <li>
                                <a href="tickets.html"><i class="la la-ticket"></i> <span>Tickets</span></a>
                            </li>
                            <li class="menu-title">
                                <span>HR</span>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="la la-files-o"></i> <span> Sales </span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="estimates.html">Estimates</a></li>
                                    <li><a href="invoices.html">Invoices</a></li>
                                    <li><a href="payments.html">Payments</a></li>
                                    <li><a href="expenses.html">Expenses</a></li>
                                    <li><a href="provident-fund.html">Provident Fund</a></li>
                                    <li><a href="taxes.html">Taxes</a></li>
                                </ul>
                            </li>
                        </ul>
                        <button class="viewmoremenu">More Menu</button>
                        <ul class="hidden-links hidden">
                            <li class="submenu">
                                <a href="#"><i class="la la-files-o"></i> <span> Accounting </span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="categories.html">Categories</a></li>
                                    <li><a href="budgets.html">Budgets</a></li>
                                    <li><a href="budget-expenses.html">Budget Expenses</a></li>
                                    <li><a href="budget-revenues.html">Budget Revenues</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="la la-money"></i> <span> Payroll </span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="salary.html"> Employee Salary </a></li>
                                    <li><a href="salary-view.html"> Payslip </a></li>
                                    <li><a href="payroll-items.html"> Payroll Items </a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="policies.html"><i class="la la-file-pdf-o"></i> <span>Policies</span></a>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="la la-pie-chart"></i> <span> Reports </span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="expense-reports.html"> Expense Report </a></li>
                                    <li><a href="invoice-reports.html"> Invoice Report </a></li>
                                    <li>
                                        <a href="payments-reports.html"> Payments Report </a>
                                    </li>
                                    <li><a href="project-reports.html"> Project Report </a></li>
                                    <li><a href="task-reports.html"> Task Report </a></li>
                                    <li><a href="user-reports.html"> User Report </a></li>
                                    <li>
                                        <a href="employee-reports.html"> Employee Report </a>
                                    </li>
                                    <li><a href="payslip-reports.html"> Payslip Report </a></li>
                                    <li>
                                        <a href="attendance-reports.html"> Attendance Report </a>
                                    </li>
                                    <li><a href="leave-reports.html"> Leave Report </a></li>
                                    <li><a href="daily-reports.html"> Daily Report </a></li>
                                </ul>
                            </li>
                            <li class="menu-title">
                                <span>Performance</span>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="la la-graduation-cap"></i>
                                    <span> Performance </span> <span class="menu-arrow"></span></a>
                                <ul>
                                    <li>
                                        <a href="performance-indicator.html">
                                            Performance Indicator
                                        </a>
                                    </li>
                                    <li><a href="performance.html"> Performance Review </a></li>
                                    <li>
                                        <a href="performance-appraisal.html">
                                            Performance Appraisal
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="la la-crosshairs"></i> <span> Goals </span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="goal-tracking.html"> Goal List </a></li>
                                    <li><a href="goal-type.html"> Goal Type </a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="la la-edit"></i> <span> Training </span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="training.html"> Training List </a></li>
                                    <li><a href="trainers.html"> Trainers</a></li>
                                    <li><a href="training-type.html"> Training Type </a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="promotion.html"><i class="la la-bullhorn"></i> <span>Promotion</span></a>
                            </li>
                            <li>
                                <a href="resignation.html"><i class="la la-external-link-square"></i>
                                    <span>Resignation</span></a>
                            </li>
                            <li>
                                <a href="termination.html"><i class="la la-times-circle"></i>
                                    <span>Termination</span></a>
                            </li>
                            <li class="menu-title">
                                <span>Administration</span>
                            </li>
                            <li>
                                <a href="assets.html"><i class="la la-object-ungroup"></i> <span>Assets</span></a>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="la la-briefcase"></i> <span> Jobs </span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="user-dashboard.html"> User Dasboard </a></li>
                                    <li><a href="jobs-dashboard.html"> Jobs Dasboard </a></li>
                                    <li><a href="jobs.html"> Manage Jobs </a></li>
                                    <li><a href="manage-resumes.html"> Manage Resumes </a></li>
                                    <li>
                                        <a href="shortlist-candidates.html">
                                            Shortlist Candidates
                                        </a>
                                    </li>
                                    <li>
                                        <a href="interview-questions.html">
                                            Interview Questions
                                        </a>
                                    </li>
                                    <li>
                                        <a href="offer_approvals.html"> Offer Approvals </a>
                                    </li>
                                    <li>
                                        <a href="experiance-level.html"> Experience Level </a>
                                    </li>
                                    <li><a href="candidates.html"> Candidates List </a></li>
                                    <li>
                                        <a href="schedule-timing.html"> Schedule timing </a>
                                    </li>
                                    <li>
                                        <a href="apptitude-result.html"> Aptitude Results </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="knowledgebase.html"><i class="la la-question"></i>
                                    <span>Knowledgebase</span></a>
                            </li>
                            <li>
                                <a href="activities.html"><i class="la la-bell"></i> <span>Activities</span></a>
                            </li>
                            <li>
                                <a href="users.html"><i class="la la-user-plus"></i> <span>Users</span></a>
                            </li>
                            <li>
                                <a href="settings.html"><i class="la la-cog"></i> <span>Settings</span></a>
                            </li>
                            <li class="menu-title">
                                <span>Pages</span>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="la la-user"></i> <span> Profile </span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="profile.html"> Employee Profile </a></li>
                                    <li><a href="client-profile.html"> Client Profile </a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="la la-key"></i> <span> Authentication </span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="index.html"> Login </a></li>
                                    <li><a href="register.html"> Register </a></li>
                                    <li>
                                        <a href="forgot-password.html"> Forgot Password </a>
                                    </li>
                                    <li><a href="otp.html"> OTP </a></li>
                                    <li><a href="lock-screen.html"> Lock Screen </a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="la la-exclamation-triangle"></i>
                                    <span> Error Pages </span> <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="error-404.html">404 Error </a></li>
                                    <li><a href="error-500.html">500 Error </a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="la la-hand-o-up"></i>
                                    <span> Subscriptions </span> <span class="menu-arrow"></span></a>
                                <ul>
                                    <li>
                                        <a href="subscriptions.html"> Subscriptions (Admin) </a>
                                    </li>
                                    <li>
                                        <a href="subscriptions-company.html">
                                            Subscriptions (Company)
                                        </a>
                                    </li>
                                    <li>
                                        <a href="subscribed-companies.html">
                                            Subscribed Companies</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="la la-columns"></i> <span> Pages </span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="search.html"> Search </a></li>
                                    <li><a href="faq.html"> FAQ </a></li>
                                    <li><a href="terms.html"> Terms </a></li>
                                    <li><a href="privacy-policy.html"> Privacy Policy </a></li>
                                    <li><a href="blank-page.html"> Blank Page </a></li>
                                </ul>
                            </li>
                            <li class="menu-title">
                                <span>UI Interface</span>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="fa-brands fa-get-pocket"></i>
                                    <span> Base UI </span> <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="alerts.html">Alerts</a></li>
                                    <li><a href="accordions.html">Accordions</a></li>
                                    <li><a href="avatar.html">Avatar</a></li>
                                    <li><a href="badges.html">Badges</a></li>
                                    <li><a href="buttons.html">Buttons</a></li>
                                    <li><a href="buttongroup.html">Button Group</a></li>
                                    <li><a href="breadcrumbs.html">Breadcrumb</a></li>
                                    <li><a href="cards.html">Cards</a></li>
                                    <li><a href="carousel.html">Carousel</a></li>
                                    <li><a href="dropdowns.html">Dropdowns</a></li>
                                    <li><a href="grid.html">Grid</a></li>
                                    <li><a href="images.html">Images</a></li>
                                    <li><a href="lightbox.html">Lightbox</a></li>
                                    <li><a href="media.html">Media</a></li>
                                    <li><a href="modal.html">Modals</a></li>
                                    <li><a href="offcanvas.html">Offcanvas</a></li>
                                    <li><a href="pagination.html">Pagination</a></li>
                                    <li><a href="popover.html">Popover</a></li>
                                    <li><a href="progress.html">Progress Bars</a></li>
                                    <li><a href="placeholders.html">Placeholders</a></li>
                                    <li><a href="rangeslider.html">Range Slider</a></li>
                                    <li><a href="spinners.html">Spinner</a></li>
                                    <li><a href="sweetalerts.html">Sweet Alerts</a></li>
                                    <li><a href="tab.html">Tabs</a></li>
                                    <li><a href="toastr.html">Toasts</a></li>
                                    <li><a href="tooltip.html">Tooltip</a></li>
                                    <li><a href="typography.html">Typography</a></li>
                                    <li><a href="video.html">Video</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="la la-dropbox"></i> <span> Elements </span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="ribbon.html">Ribbon</a></li>
                                    <li><a href="clipboard.html">Clipboard</a></li>
                                    <li><a href="drag-drop.html">Drag & Drop</a></li>
                                    <li><a href="rating.html">Rating</a></li>
                                    <li><a href="text-editor.html">Text Editor</a></li>
                                    <li><a href="counter.html">Counter</a></li>
                                    <li><a href="scrollbar.html">Scrollbar</a></li>
                                    <li><a href="notification.html">Notification</a></li>
                                    <li><a href="stickynote.html">Sticky Note</a></li>
                                    <li><a href="timeline.html">Timeline</a></li>
                                    <li>
                                        <a href="horizontal-timeline.html">Horizontal Timeline</a>
                                    </li>
                                    <li><a href="form-wizard.html">Form Wizard</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="la la-chart-bar"></i> <span> Charts </span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="chart-apex.html">Apex Charts</a></li>
                                    <li><a href="chart-js.html">Chart Js</a></li>
                                    <li><a href="chart-morris.html">Morris Charts</a></li>
                                    <li><a href="chart-flot.html">Flot Charts</a></li>
                                    <li><a href="chart-peity.html">Peity Charts</a></li>
                                    <li><a href="chart-c3.html">C3 Charts</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="la la-icons"></i> <span> Icons </span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li>
                                        <a href="icon-fontawesome.html">Fontawesome Icons</a>
                                    </li>
                                    <li><a href="icon-feather.html">Feather Icons</a></li>
                                    <li><a href="icon-ionic.html">Ionic Icons</a></li>
                                    <li><a href="icon-material.html">Material Icons</a></li>
                                    <li><a href="icon-pe7.html">Pe7 Icons</a></li>
                                    <li><a href="icon-simpleline.html">Simpleline Icons</a></li>
                                    <li><a href="icon-themify.html">Themify Icons</a></li>
                                    <li><a href="icon-weather.html">Weather Icons</a></li>
                                    <li><a href="icon-typicon.html">Typicon Icons</a></li>
                                    <li><a href="icon-flag.html">Flag Icons</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="la la-object-group"></i> <span> Forms </span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="form-basic-inputs.html">Basic Inputs </a></li>
                                    <li><a href="form-input-groups.html">Input Groups </a></li>
                                    <li><a href="form-horizontal.html">Horizontal Form </a></li>
                                    <li><a href="form-vertical.html"> Vertical Form </a></li>
                                    <li><a href="form-mask.html"> Form Mask </a></li>
                                    <li>
                                        <a href="form-validation.html"> Form Validation </a>
                                    </li>
                                    <li><a href="form-select2.html">Form Select2 </a></li>
                                    <li><a href="form-fileupload.html">File Upload </a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="#"><i class="la la-table"></i> <span> Tables </span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="tables-basic.html">Basic Tables </a></li>
                                    <li><a href="data-tables.html">Data Table </a></li>
                                </ul>
                            </li>
                            <li class="menu-title">
                                <span>Extras</span>
                            </li>
                            <li>
                                <a href="#"><i class="la la-file-text"></i>
                                    <span>Documentation</span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);"><i class="la la-info"></i> <span>Change Log</span>
                                    <span class="badge badge-primary ms-auto">v3.4</span></a>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);"><i class="la la-share-alt"></i> <span>Multi Level</span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li class="submenu">
                                        <a href="javascript:void(0);">
                                            <span>Level 1</span> <span class="menu-arrow"></span></a>
                                        <ul>
                                            <li>
                                                <a href="javascript:void(0);"><span>Level 2</span></a>
                                            </li>
                                            <li class="submenu">
                                                <a href="javascript:void(0);">
                                                    <span> Level 2</span>
                                                    <span class="menu-arrow"></span></a>
                                                <ul>
                                                    <li><a href="javascript:void(0);">Level 3</a></li>
                                                    <li><a href="javascript:void(0);">Level 3</a></li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">
                                                    <span>Level 2</span></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);"> <span>Level 1</span></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                    <ul class="sidebar-vertical">
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                        <li class="menu-title">
                            <span>Main</span>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="la la-dashboard"></i> <span> Dashboard</span>
                                <span class="menu-arrow"></span></a>
                            <ul>
<<<<<<< HEAD
                                <li><a href="admin-dashboard.html">Admin Dashboard</a></li>
=======
                                <li>
                                    <a class="active" href="admin-dashboard.html">Admin Dashboard</a>
                                </li>
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                                <li>
                                    <a href="employee-dashboard.html">Employee Dashboard</a>
                                </li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="la la-cube"></i> <span> Apps</span>
                                <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="chat.html">Chat</a></li>
                                <li class="submenu">
                                    <a href="#"><span> Calls</span> <span class="menu-arrow"></span></a>
                                    <ul>
                                        <li><a href="voice-call.html">Voice Call</a></li>
                                        <li><a href="video-call.html">Video Call</a></li>
                                        <li><a href="outgoing-call.html">Outgoing Call</a></li>
                                        <li><a href="incoming-call.html">Incoming Call</a></li>
                                    </ul>
                                </li>
                                <li><a href="events.html">Calendar</a></li>
                                <li><a href="contacts.html">Contacts</a></li>
                                <li><a href="inbox.html">Email</a></li>
                                <li><a href="file-manager.html">File Manager</a></li>
                            </ul>
                        </li>
                        <li class="menu-title">
                            <span>Employees</span>
                        </li>
                        <li class="submenu">
                            <a href="#" class="noti-dot"><i class="la la-user"></i> <span> Employees</span>
                                <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="employees.html">All Employees</a></li>
                                <li><a href="holidays.html">Holidays</a></li>
                                <li>
                                    <a href="leaves.html">Leaves (Admin)
                                        <span class="badge rounded-pill bg-primary float-end">1</span></a>
                                </li>
<<<<<<< HEAD
                                <li>
                                    <a href="leaves-employee.html">Leaves (Employee)</a>
                                </li>
=======
                                <li><a href="leaves-employee.html">Leaves (Employee)</a></li>
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                                <li><a href="leave-settings.html">Leave Settings</a></li>
                                <li><a href="attendance.html">Attendance (Admin)</a></li>
                                <li>
                                    <a href="attendance-employee.html">Attendance (Employee)</a>
                                </li>
                                <li><a href="departments.html">Departments</a></li>
                                <li><a href="designations.html">Designations</a></li>
                                <li><a href="timesheet.html">Timesheet</a></li>
<<<<<<< HEAD
                                <li>
                                    <a href="shift-scheduling.html">Shift & Schedule</a>
                                </li>
=======
                                <li><a href="shift-scheduling.html">Shift & Schedule</a></li>
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                                <li><a href="overtime.html">Overtime</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="clients.html"><i class="la la-users"></i> <span>Clients</span></a>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="la la-rocket"></i> <span> Projects</span>
                                <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="projects.html">Projects</a></li>
                                <li><a href="tasks.html">Tasks</a></li>
                                <li><a href="task-board.html">Task Board</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="leads.html"><i class="la la-user-secret"></i> <span>Leads</span></a>
                        </li>
                        <li>
                            <a href="tickets.html"><i class="la la-ticket"></i> <span>Tickets</span></a>
                        </li>
                        <li class="menu-title">
                            <span>HR</span>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="la la-files-o"></i> <span> Sales </span>
                                <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="estimates.html">Estimates</a></li>
                                <li><a href="invoices.html">Invoices</a></li>
                                <li><a href="payments.html">Payments</a></li>
                                <li><a href="expenses.html">Expenses</a></li>
                                <li><a href="provident-fund.html">Provident Fund</a></li>
                                <li><a href="taxes.html">Taxes</a></li>
                            </ul>
                        </li>
<<<<<<< HEAD
                    </ul>
                    <button class="viewmoremenu">More Menu</button>
                    <ul class="hidden-links hidden">
=======
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                        <li class="submenu">
                            <a href="#"><i class="la la-files-o"></i> <span> Accounting </span>
                                <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="categories.html">Categories</a></li>
                                <li><a href="budgets.html">Budgets</a></li>
                                <li><a href="budget-expenses.html">Budget Expenses</a></li>
                                <li><a href="budget-revenues.html">Budget Revenues</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="la la-money"></i> <span> Payroll </span>
                                <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="salary.html"> Employee Salary </a></li>
                                <li><a href="salary-view.html"> Payslip </a></li>
                                <li><a href="payroll-items.html"> Payroll Items </a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="policies.html"><i class="la la-file-pdf-o"></i> <span>Policies</span></a>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="la la-pie-chart"></i> <span> Reports </span>
                                <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="expense-reports.html"> Expense Report </a></li>
                                <li><a href="invoice-reports.html"> Invoice Report </a></li>
<<<<<<< HEAD
                                <li>
                                    <a href="payments-reports.html"> Payments Report </a>
                                </li>
                                <li><a href="project-reports.html"> Project Report </a></li>
                                <li><a href="task-reports.html"> Task Report </a></li>
                                <li><a href="user-reports.html"> User Report </a></li>
                                <li>
                                    <a href="employee-reports.html"> Employee Report </a>
                                </li>
=======
                                <li><a href="payments-reports.html"> Payments Report </a></li>
                                <li><a href="project-reports.html"> Project Report </a></li>
                                <li><a href="task-reports.html"> Task Report </a></li>
                                <li><a href="user-reports.html"> User Report </a></li>
                                <li><a href="employee-reports.html"> Employee Report </a></li>
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                                <li><a href="payslip-reports.html"> Payslip Report </a></li>
                                <li>
                                    <a href="attendance-reports.html"> Attendance Report </a>
                                </li>
                                <li><a href="leave-reports.html"> Leave Report </a></li>
                                <li><a href="daily-reports.html"> Daily Report </a></li>
                            </ul>
                        </li>
                        <li class="menu-title">
                            <span>Performance</span>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="la la-graduation-cap"></i>
                                <span> Performance </span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li>
                                    <a href="performance-indicator.html">
                                        Performance Indicator
                                    </a>
                                </li>
                                <li><a href="performance.html"> Performance Review </a></li>
                                <li>
                                    <a href="performance-appraisal.html">
                                        Performance Appraisal
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="la la-crosshairs"></i> <span> Goals </span>
                                <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="goal-tracking.html"> Goal List </a></li>
                                <li><a href="goal-type.html"> Goal Type </a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="la la-edit"></i> <span> Training </span>
                                <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="training.html"> Training List </a></li>
                                <li><a href="trainers.html"> Trainers</a></li>
                                <li><a href="training-type.html"> Training Type </a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="promotion.html"><i class="la la-bullhorn"></i> <span>Promotion</span></a>
                        </li>
                        <li>
                            <a href="resignation.html"><i class="la la-external-link-square"></i>
                                <span>Resignation</span></a>
                        </li>
                        <li>
                            <a href="termination.html"><i class="la la-times-circle"></i>
                                <span>Termination</span></a>
                        </li>
                        <li class="menu-title">
                            <span>Administration</span>
                        </li>
                        <li>
                            <a href="assets.html"><i class="la la-object-ungroup"></i> <span>Assets</span></a>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="la la-briefcase"></i> <span> Jobs </span>
                                <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="user-dashboard.html"> User Dasboard </a></li>
                                <li><a href="jobs-dashboard.html"> Jobs Dasboard </a></li>
                                <li><a href="jobs.html"> Manage Jobs </a></li>
                                <li><a href="manage-resumes.html"> Manage Resumes </a></li>
                                <li>
                                    <a href="shortlist-candidates.html">
                                        Shortlist Candidates
                                    </a>
                                </li>
                                <li>
<<<<<<< HEAD
                                    <a href="interview-questions.html">
                                        Interview Questions
                                    </a>
                                </li>
                                <li>
                                    <a href="offer_approvals.html"> Offer Approvals </a>
                                </li>
=======
                                    <a href="interview-questions.html"> Interview Questions </a>
                                </li>
                                <li><a href="offer_approvals.html"> Offer Approvals </a></li>
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                                <li>
                                    <a href="experiance-level.html"> Experience Level </a>
                                </li>
                                <li><a href="candidates.html"> Candidates List </a></li>
<<<<<<< HEAD
                                <li>
                                    <a href="schedule-timing.html"> Schedule timing </a>
                                </li>
=======
                                <li><a href="schedule-timing.html"> Schedule timing </a></li>
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                                <li>
                                    <a href="apptitude-result.html"> Aptitude Results </a>
                                </li>
                            </ul>
                        </li>
                        <li>
<<<<<<< HEAD
                            <a href="knowledgebase.html"><i class="la la-question"></i>
                                <span>Knowledgebase</span></a>
=======
                            <a href="knowledgebase.html"><i class="la la-question"></i> <span>Knowledgebase</span></a>
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                        </li>
                        <li>
                            <a href="activities.html"><i class="la la-bell"></i> <span>Activities</span></a>
                        </li>
                        <li>
                            <a href="users.html"><i class="la la-user-plus"></i> <span>Users</span></a>
                        </li>
                        <li>
                            <a href="settings.html"><i class="la la-cog"></i> <span>Settings</span></a>
                        </li>
                        <li class="menu-title">
                            <span>Pages</span>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="la la-user"></i> <span> Profile </span>
                                <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="profile.html"> Employee Profile </a></li>
                                <li><a href="client-profile.html"> Client Profile </a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="la la-key"></i> <span> Authentication </span>
                                <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="index.html"> Login </a></li>
                                <li><a href="register.html"> Register </a></li>
<<<<<<< HEAD
                                <li>
                                    <a href="forgot-password.html"> Forgot Password </a>
                                </li>
=======
                                <li><a href="forgot-password.html"> Forgot Password </a></li>
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                                <li><a href="otp.html"> OTP </a></li>
                                <li><a href="lock-screen.html"> Lock Screen </a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="la la-exclamation-triangle"></i>
                                <span> Error Pages </span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="error-404.html">404 Error </a></li>
                                <li><a href="error-500.html">500 Error </a></li>
                            </ul>
                        </li>
                        <li class="submenu">
<<<<<<< HEAD
                            <a href="#"><i class="la la-hand-o-up"></i>
                                <span> Subscriptions </span> <span class="menu-arrow"></span></a>
=======
                            <a href="#"><i class="la la-hand-o-up"></i> <span> Subscriptions </span>
                                <span class="menu-arrow"></span></a>
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                            <ul>
                                <li>
                                    <a href="subscriptions.html"> Subscriptions (Admin) </a>
                                </li>
                                <li>
                                    <a href="subscriptions-company.html">
                                        Subscriptions (Company)
                                    </a>
                                </li>
                                <li>
                                    <a href="subscribed-companies.html">
                                        Subscribed Companies</a>
                                </li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="la la-columns"></i> <span> Pages </span>
                                <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="search.html"> Search </a></li>
                                <li><a href="faq.html"> FAQ </a></li>
                                <li><a href="terms.html"> Terms </a></li>
                                <li><a href="privacy-policy.html"> Privacy Policy </a></li>
                                <li><a href="blank-page.html"> Blank Page </a></li>
                            </ul>
                        </li>
                        <li class="menu-title">
                            <span>UI Interface</span>
                        </li>
                        <li class="submenu">
<<<<<<< HEAD
                            <a href="#"><i class="fa-brands fa-get-pocket"></i>
                                <span> Base UI </span> <span class="menu-arrow"></span></a>
=======
                            <a href="#"><i class="la la-get-pocket"></i> <span> Base UI </span>
                                <span class="menu-arrow"></span></a>
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                            <ul>
                                <li><a href="alerts.html">Alerts</a></li>
                                <li><a href="accordions.html">Accordions</a></li>
                                <li><a href="avatar.html">Avatar</a></li>
                                <li><a href="badges.html">Badges</a></li>
                                <li><a href="buttons.html">Buttons</a></li>
                                <li><a href="buttongroup.html">Button Group</a></li>
                                <li><a href="breadcrumbs.html">Breadcrumb</a></li>
                                <li><a href="cards.html">Cards</a></li>
                                <li><a href="carousel.html">Carousel</a></li>
                                <li><a href="dropdowns.html">Dropdowns</a></li>
                                <li><a href="grid.html">Grid</a></li>
                                <li><a href="images.html">Images</a></li>
                                <li><a href="lightbox.html">Lightbox</a></li>
                                <li><a href="media.html">Media</a></li>
                                <li><a href="modal.html">Modals</a></li>
                                <li><a href="offcanvas.html">Offcanvas</a></li>
                                <li><a href="pagination.html">Pagination</a></li>
                                <li><a href="popover.html">Popover</a></li>
                                <li><a href="progress.html">Progress Bars</a></li>
                                <li><a href="placeholders.html">Placeholders</a></li>
                                <li><a href="rangeslider.html">Range Slider</a></li>
                                <li><a href="spinners.html">Spinner</a></li>
                                <li><a href="sweetalerts.html">Sweet Alerts</a></li>
                                <li><a href="tab.html">Tabs</a></li>
                                <li><a href="toastr.html">Toasts</a></li>
                                <li><a href="tooltip.html">Tooltip</a></li>
                                <li><a href="typography.html">Typography</a></li>
                                <li><a href="video.html">Video</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="la la-dropbox"></i> <span> Elements </span>
                                <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="ribbon.html">Ribbon</a></li>
                                <li><a href="clipboard.html">Clipboard</a></li>
                                <li><a href="drag-drop.html">Drag & Drop</a></li>
                                <li><a href="rating.html">Rating</a></li>
                                <li><a href="text-editor.html">Text Editor</a></li>
                                <li><a href="counter.html">Counter</a></li>
                                <li><a href="scrollbar.html">Scrollbar</a></li>
                                <li><a href="notification.html">Notification</a></li>
                                <li><a href="stickynote.html">Sticky Note</a></li>
                                <li><a href="timeline.html">Timeline</a></li>
                                <li>
                                    <a href="horizontal-timeline.html">Horizontal Timeline</a>
                                </li>
                                <li><a href="form-wizard.html">Form Wizard</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="la la-chart-bar"></i> <span> Charts </span>
                                <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="chart-apex.html">Apex Charts</a></li>
                                <li><a href="chart-js.html">Chart Js</a></li>
                                <li><a href="chart-morris.html">Morris Charts</a></li>
                                <li><a href="chart-flot.html">Flot Charts</a></li>
                                <li><a href="chart-peity.html">Peity Charts</a></li>
                                <li><a href="chart-c3.html">C3 Charts</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="la la-icons"></i> <span> Icons </span>
                                <span class="menu-arrow"></span></a>
                            <ul>
<<<<<<< HEAD
                                <li>
                                    <a href="icon-fontawesome.html">Fontawesome Icons</a>
                                </li>
=======
                                <li><a href="icon-fontawesome.html">Fontawesome Icons</a></li>
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                                <li><a href="icon-feather.html">Feather Icons</a></li>
                                <li><a href="icon-ionic.html">Ionic Icons</a></li>
                                <li><a href="icon-material.html">Material Icons</a></li>
                                <li><a href="icon-pe7.html">Pe7 Icons</a></li>
                                <li><a href="icon-simpleline.html">Simpleline Icons</a></li>
                                <li><a href="icon-themify.html">Themify Icons</a></li>
                                <li><a href="icon-weather.html">Weather Icons</a></li>
                                <li><a href="icon-typicon.html">Typicon Icons</a></li>
                                <li><a href="icon-flag.html">Flag Icons</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="la la-object-group"></i> <span> Forms </span>
                                <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="form-basic-inputs.html">Basic Inputs </a></li>
                                <li><a href="form-input-groups.html">Input Groups </a></li>
                                <li><a href="form-horizontal.html">Horizontal Form </a></li>
                                <li><a href="form-vertical.html"> Vertical Form </a></li>
                                <li><a href="form-mask.html"> Form Mask </a></li>
<<<<<<< HEAD
                                <li>
                                    <a href="form-validation.html"> Form Validation </a>
                                </li>
=======
                                <li><a href="form-validation.html"> Form Validation </a></li>
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                                <li><a href="form-select2.html">Form Select2 </a></li>
                                <li><a href="form-fileupload.html">File Upload </a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="#"><i class="la la-table"></i> <span> Tables </span>
                                <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="tables-basic.html">Basic Tables </a></li>
                                <li><a href="data-tables.html">Data Table </a></li>
                            </ul>
                        </li>
                        <li class="menu-title">
                            <span>Extras</span>
                        </li>
                        <li>
<<<<<<< HEAD
                            <a href="#"><i class="la la-file-text"></i>
                                <span>Documentation</span></a>
=======
                            <a href="#"><i class="la la-file-text"></i> <span>Documentation</span></a>
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                        </li>
                        <li>
                            <a href="javascript:void(0);"><i class="la la-info"></i> <span>Change Log</span>
                                <span class="badge badge-primary ms-auto">v3.4</span></a>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="la la-share-alt"></i> <span>Multi Level</span>
                                <span class="menu-arrow"></span></a>
                            <ul>
                                <li class="submenu">
                                    <a href="javascript:void(0);">
                                        <span>Level 1</span> <span class="menu-arrow"></span></a>
                                    <ul>
                                        <li>
                                            <a href="javascript:void(0);"><span>Level 2</span></a>
                                        </li>
                                        <li class="submenu">
                                            <a href="javascript:void(0);">
<<<<<<< HEAD
                                                <span> Level 2</span>
                                                <span class="menu-arrow"></span></a>
=======
                                                <span> Level 2</span> <span class="menu-arrow"></span></a>
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                                            <ul>
                                                <li><a href="javascript:void(0);">Level 3</a></li>
                                                <li><a href="javascript:void(0);">Level 3</a></li>
                                            </ul>
                                        </li>
                                        <li>
<<<<<<< HEAD
                                            <a href="javascript:void(0);">
                                                <span>Level 2</span></a>
=======
                                            <a href="javascript:void(0);"> <span>Level 2</span></a>
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"> <span>Level 1</span></a>
                                </li>
                            </ul>
                        </li>
                    </ul>
<<<<<<< HEAD
                </nav>
                <ul class="sidebar-vertical">
                    <li class="menu-title">
                        <span>Main</span>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-dashboard"></i> <span> Dashboard</span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li>
                                <a class="active" href="admin-dashboard.html">Admin Dashboard</a>
                            </li>
                            <li>
                                <a href="employee-dashboard.html">Employee Dashboard</a>
                            </li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-cube"></i> <span> Apps</span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="chat.html">Chat</a></li>
                            <li class="submenu">
                                <a href="#"><span> Calls</span> <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="voice-call.html">Voice Call</a></li>
                                    <li><a href="video-call.html">Video Call</a></li>
                                    <li><a href="outgoing-call.html">Outgoing Call</a></li>
                                    <li><a href="incoming-call.html">Incoming Call</a></li>
                                </ul>
                            </li>
                            <li><a href="events.html">Calendar</a></li>
                            <li><a href="contacts.html">Contacts</a></li>
                            <li><a href="inbox.html">Email</a></li>
                            <li><a href="file-manager.html">File Manager</a></li>
                        </ul>
                    </li>
                    <li class="menu-title">
                        <span>Employees</span>
                    </li>
                    <li class="submenu">
                        <a href="#" class="noti-dot"><i class="la la-user"></i> <span> Employees</span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="employees.html">All Employees</a></li>
                            <li><a href="holidays.html">Holidays</a></li>
                            <li>
                                <a href="leaves.html">Leaves (Admin)
                                    <span class="badge rounded-pill bg-primary float-end">1</span></a>
                            </li>
                            <li><a href="leaves-employee.html">Leaves (Employee)</a></li>
                            <li><a href="leave-settings.html">Leave Settings</a></li>
                            <li><a href="attendance.html">Attendance (Admin)</a></li>
                            <li>
                                <a href="attendance-employee.html">Attendance (Employee)</a>
                            </li>
                            <li><a href="departments.html">Departments</a></li>
                            <li><a href="designations.html">Designations</a></li>
                            <li><a href="timesheet.html">Timesheet</a></li>
                            <li><a href="shift-scheduling.html">Shift & Schedule</a></li>
                            <li><a href="overtime.html">Overtime</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="clients.html"><i class="la la-users"></i> <span>Clients</span></a>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-rocket"></i> <span> Projects</span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="projects.html">Projects</a></li>
                            <li><a href="tasks.html">Tasks</a></li>
                            <li><a href="task-board.html">Task Board</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="leads.html"><i class="la la-user-secret"></i> <span>Leads</span></a>
                    </li>
                    <li>
                        <a href="tickets.html"><i class="la la-ticket"></i> <span>Tickets</span></a>
                    </li>
                    <li class="menu-title">
                        <span>HR</span>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-files-o"></i> <span> Sales </span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="estimates.html">Estimates</a></li>
                            <li><a href="invoices.html">Invoices</a></li>
                            <li><a href="payments.html">Payments</a></li>
                            <li><a href="expenses.html">Expenses</a></li>
                            <li><a href="provident-fund.html">Provident Fund</a></li>
                            <li><a href="taxes.html">Taxes</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-files-o"></i> <span> Accounting </span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="categories.html">Categories</a></li>
                            <li><a href="budgets.html">Budgets</a></li>
                            <li><a href="budget-expenses.html">Budget Expenses</a></li>
                            <li><a href="budget-revenues.html">Budget Revenues</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-money"></i> <span> Payroll </span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="salary.html"> Employee Salary </a></li>
                            <li><a href="salary-view.html"> Payslip </a></li>
                            <li><a href="payroll-items.html"> Payroll Items </a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="policies.html"><i class="la la-file-pdf-o"></i> <span>Policies</span></a>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-pie-chart"></i> <span> Reports </span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="expense-reports.html"> Expense Report </a></li>
                            <li><a href="invoice-reports.html"> Invoice Report </a></li>
                            <li><a href="payments-reports.html"> Payments Report </a></li>
                            <li><a href="project-reports.html"> Project Report </a></li>
                            <li><a href="task-reports.html"> Task Report </a></li>
                            <li><a href="user-reports.html"> User Report </a></li>
                            <li><a href="employee-reports.html"> Employee Report </a></li>
                            <li><a href="payslip-reports.html"> Payslip Report </a></li>
                            <li>
                                <a href="attendance-reports.html"> Attendance Report </a>
                            </li>
                            <li><a href="leave-reports.html"> Leave Report </a></li>
                            <li><a href="daily-reports.html"> Daily Report </a></li>
                        </ul>
                    </li>
                    <li class="menu-title">
                        <span>Performance</span>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-graduation-cap"></i>
                            <span> Performance </span> <span class="menu-arrow"></span></a>
                        <ul>
                            <li>
                                <a href="performance-indicator.html">
                                    Performance Indicator
                                </a>
                            </li>
                            <li><a href="performance.html"> Performance Review </a></li>
                            <li>
                                <a href="performance-appraisal.html">
                                    Performance Appraisal
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-crosshairs"></i> <span> Goals </span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="goal-tracking.html"> Goal List </a></li>
                            <li><a href="goal-type.html"> Goal Type </a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-edit"></i> <span> Training </span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="training.html"> Training List </a></li>
                            <li><a href="trainers.html"> Trainers</a></li>
                            <li><a href="training-type.html"> Training Type </a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="promotion.html"><i class="la la-bullhorn"></i> <span>Promotion</span></a>
                    </li>
                    <li>
                        <a href="resignation.html"><i class="la la-external-link-square"></i>
                            <span>Resignation</span></a>
                    </li>
                    <li>
                        <a href="termination.html"><i class="la la-times-circle"></i>
                            <span>Termination</span></a>
                    </li>
                    <li class="menu-title">
                        <span>Administration</span>
                    </li>
                    <li>
                        <a href="assets.html"><i class="la la-object-ungroup"></i> <span>Assets</span></a>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-briefcase"></i> <span> Jobs </span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="user-dashboard.html"> User Dasboard </a></li>
                            <li><a href="jobs-dashboard.html"> Jobs Dasboard </a></li>
                            <li><a href="jobs.html"> Manage Jobs </a></li>
                            <li><a href="manage-resumes.html"> Manage Resumes </a></li>
                            <li>
                                <a href="shortlist-candidates.html">
                                    Shortlist Candidates
                                </a>
                            </li>
                            <li>
                                <a href="interview-questions.html"> Interview Questions </a>
                            </li>
                            <li><a href="offer_approvals.html"> Offer Approvals </a></li>
                            <li>
                                <a href="experiance-level.html"> Experience Level </a>
                            </li>
                            <li><a href="candidates.html"> Candidates List </a></li>
                            <li><a href="schedule-timing.html"> Schedule timing </a></li>
                            <li>
                                <a href="apptitude-result.html"> Aptitude Results </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="knowledgebase.html"><i class="la la-question"></i> <span>Knowledgebase</span></a>
                    </li>
                    <li>
                        <a href="activities.html"><i class="la la-bell"></i> <span>Activities</span></a>
                    </li>
                    <li>
                        <a href="users.html"><i class="la la-user-plus"></i> <span>Users</span></a>
                    </li>
                    <li>
                        <a href="settings.html"><i class="la la-cog"></i> <span>Settings</span></a>
                    </li>
                    <li class="menu-title">
                        <span>Pages</span>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-user"></i> <span> Profile </span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="profile.html"> Employee Profile </a></li>
                            <li><a href="client-profile.html"> Client Profile </a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-key"></i> <span> Authentication </span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="index.html"> Login </a></li>
                            <li><a href="register.html"> Register </a></li>
                            <li><a href="forgot-password.html"> Forgot Password </a></li>
                            <li><a href="otp.html"> OTP </a></li>
                            <li><a href="lock-screen.html"> Lock Screen </a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-exclamation-triangle"></i>
                            <span> Error Pages </span> <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="error-404.html">404 Error </a></li>
                            <li><a href="error-500.html">500 Error </a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-hand-o-up"></i> <span> Subscriptions </span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li>
                                <a href="subscriptions.html"> Subscriptions (Admin) </a>
                            </li>
                            <li>
                                <a href="subscriptions-company.html">
                                    Subscriptions (Company)
                                </a>
                            </li>
                            <li>
                                <a href="subscribed-companies.html">
                                    Subscribed Companies</a>
                            </li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-columns"></i> <span> Pages </span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="search.html"> Search </a></li>
                            <li><a href="faq.html"> FAQ </a></li>
                            <li><a href="terms.html"> Terms </a></li>
                            <li><a href="privacy-policy.html"> Privacy Policy </a></li>
                            <li><a href="blank-page.html"> Blank Page </a></li>
                        </ul>
                    </li>
                    <li class="menu-title">
                        <span>UI Interface</span>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-get-pocket"></i> <span> Base UI </span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="alerts.html">Alerts</a></li>
                            <li><a href="accordions.html">Accordions</a></li>
                            <li><a href="avatar.html">Avatar</a></li>
                            <li><a href="badges.html">Badges</a></li>
                            <li><a href="buttons.html">Buttons</a></li>
                            <li><a href="buttongroup.html">Button Group</a></li>
                            <li><a href="breadcrumbs.html">Breadcrumb</a></li>
                            <li><a href="cards.html">Cards</a></li>
                            <li><a href="carousel.html">Carousel</a></li>
                            <li><a href="dropdowns.html">Dropdowns</a></li>
                            <li><a href="grid.html">Grid</a></li>
                            <li><a href="images.html">Images</a></li>
                            <li><a href="lightbox.html">Lightbox</a></li>
                            <li><a href="media.html">Media</a></li>
                            <li><a href="modal.html">Modals</a></li>
                            <li><a href="offcanvas.html">Offcanvas</a></li>
                            <li><a href="pagination.html">Pagination</a></li>
                            <li><a href="popover.html">Popover</a></li>
                            <li><a href="progress.html">Progress Bars</a></li>
                            <li><a href="placeholders.html">Placeholders</a></li>
                            <li><a href="rangeslider.html">Range Slider</a></li>
                            <li><a href="spinners.html">Spinner</a></li>
                            <li><a href="sweetalerts.html">Sweet Alerts</a></li>
                            <li><a href="tab.html">Tabs</a></li>
                            <li><a href="toastr.html">Toasts</a></li>
                            <li><a href="tooltip.html">Tooltip</a></li>
                            <li><a href="typography.html">Typography</a></li>
                            <li><a href="video.html">Video</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-dropbox"></i> <span> Elements </span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="ribbon.html">Ribbon</a></li>
                            <li><a href="clipboard.html">Clipboard</a></li>
                            <li><a href="drag-drop.html">Drag & Drop</a></li>
                            <li><a href="rating.html">Rating</a></li>
                            <li><a href="text-editor.html">Text Editor</a></li>
                            <li><a href="counter.html">Counter</a></li>
                            <li><a href="scrollbar.html">Scrollbar</a></li>
                            <li><a href="notification.html">Notification</a></li>
                            <li><a href="stickynote.html">Sticky Note</a></li>
                            <li><a href="timeline.html">Timeline</a></li>
                            <li>
                                <a href="horizontal-timeline.html">Horizontal Timeline</a>
                            </li>
                            <li><a href="form-wizard.html">Form Wizard</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-chart-bar"></i> <span> Charts </span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="chart-apex.html">Apex Charts</a></li>
                            <li><a href="chart-js.html">Chart Js</a></li>
                            <li><a href="chart-morris.html">Morris Charts</a></li>
                            <li><a href="chart-flot.html">Flot Charts</a></li>
                            <li><a href="chart-peity.html">Peity Charts</a></li>
                            <li><a href="chart-c3.html">C3 Charts</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-icons"></i> <span> Icons </span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="icon-fontawesome.html">Fontawesome Icons</a></li>
                            <li><a href="icon-feather.html">Feather Icons</a></li>
                            <li><a href="icon-ionic.html">Ionic Icons</a></li>
                            <li><a href="icon-material.html">Material Icons</a></li>
                            <li><a href="icon-pe7.html">Pe7 Icons</a></li>
                            <li><a href="icon-simpleline.html">Simpleline Icons</a></li>
                            <li><a href="icon-themify.html">Themify Icons</a></li>
                            <li><a href="icon-weather.html">Weather Icons</a></li>
                            <li><a href="icon-typicon.html">Typicon Icons</a></li>
                            <li><a href="icon-flag.html">Flag Icons</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-object-group"></i> <span> Forms </span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="form-basic-inputs.html">Basic Inputs </a></li>
                            <li><a href="form-input-groups.html">Input Groups </a></li>
                            <li><a href="form-horizontal.html">Horizontal Form </a></li>
                            <li><a href="form-vertical.html"> Vertical Form </a></li>
                            <li><a href="form-mask.html"> Form Mask </a></li>
                            <li><a href="form-validation.html"> Form Validation </a></li>
                            <li><a href="form-select2.html">Form Select2 </a></li>
                            <li><a href="form-fileupload.html">File Upload </a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-table"></i> <span> Tables </span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="tables-basic.html">Basic Tables </a></li>
                            <li><a href="data-tables.html">Data Table </a></li>
                        </ul>
                    </li>
                    <li class="menu-title">
                        <span>Extras</span>
                    </li>
                    <li>
                        <a href="#"><i class="la la-file-text"></i> <span>Documentation</span></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);"><i class="la la-info"></i> <span>Change Log</span>
                            <span class="badge badge-primary ms-auto">v3.4</span></a>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><i class="la la-share-alt"></i> <span>Multi Level</span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <span>Level 1</span> <span class="menu-arrow"></span></a>
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);"><span>Level 2</span></a>
                                    </li>
                                    <li class="submenu">
                                        <a href="javascript:void(0);">
                                            <span> Level 2</span> <span class="menu-arrow"></span></a>
                                        <ul>
                                            <li><a href="javascript:void(0);">Level 3</a></li>
                                            <li><a href="javascript:void(0);">Level 3</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);"> <span>Level 2</span></a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0);"> <span>Level 1</span></a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- /Sidebar -->

    <!-- Two Col Sidebar -->
    <div class="two-col-bar" id="two-col-bar">
        <div class="sidebar sidebar-twocol" id="navbar-nav">
            <div class="sidebar-left slimscroll">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="v-pills-dashboard-tab" title="Dashboard" data-bs-toggle="pill"
                       href="#v-pills-dashboard" role="tab" aria-controls="v-pills-dashboard" aria-selected="true">
                        <span class="material-icons-outlined"> home </span>
                    </a>
                    <a class="nav-link" id="v-pills-apps-tab" title="Apps" data-bs-toggle="pill" href="#v-pills-apps"
                       role="tab" aria-controls="v-pills-apps" aria-selected="false">
                        <span class="material-icons-outlined"> dashboard </span>
                    </a>
                    <a class="nav-link" id="v-pills-employees-tab" title="Employees" data-bs-toggle="pill"
                       href="#v-pills-employees" role="tab" aria-controls="v-pills-employees" aria-selected="false">
                        <span class="material-icons-outlined"> people </span>
                    </a>
                    <a class="nav-link" id="v-pills-clients-tab" title="Clients" data-bs-toggle="pill"
                       href="#v-pills-clients" role="tab" aria-controls="v-pills-clients" aria-selected="false">
                        <span class="material-icons-outlined"> person </span>
                    </a>
                    <a class="nav-link" id="v-pills-projects-tab" title="Projects" data-bs-toggle="pill"
                       href="#v-pills-projects" role="tab" aria-controls="v-pills-projects" aria-selected="false">
                        <span class="material-icons-outlined"> topic </span>
                    </a>
                    <a class="nav-link" id="v-pills-leads-tab" title="Leads" data-bs-toggle="pill" href="#v-pills-leads"
                       role="tab" aria-controls="v-pills-leads" aria-selected="false">
                        <span class="material-icons-outlined"> leaderboard </span>
                    </a>
                    <a class="nav-link" id="v-pills-tickets-tab" title="Tickets" data-bs-toggle="pill"
                       href="#v-pills-tickets" role="tab" aria-controls="v-pills-tickets" aria-selected="false">
                            <span class="material-icons-outlined">
                                confirmation_number
                            </span>
                    </a>
                    <a class="nav-link" id="v-pills-sales-tab" title="Sales" data-bs-toggle="pill" href="#v-pills-sales"
                       role="tab" aria-controls="v-pills-sales" aria-selected="false">
                        <span class="material-icons-outlined"> shopping_bag </span>
                    </a>
                    <a class="nav-link" id="v-pills-accounting-tab" title="Accounting" data-bs-toggle="pill"
                       href="#v-pills-accounting" role="tab" aria-controls="v-pills-accounting" aria-selected="false">
                            <span class="material-icons-outlined">
                                account_balance_wallet
                            </span>
                    </a>
                    <a class="nav-link" id="v-pills-payroll-tab" title="Payroll" data-bs-toggle="pill"
                       href="#v-pills-payroll" role="tab" aria-controls="v-pills-payroll" aria-selected="false">
                        <span class="material-icons-outlined"> request_quote </span>
                    </a>
                    <a class="nav-link" id="v-pills-policies-tab" title="Policies" data-bs-toggle="pill"
                       href="#v-pills-policies" role="tab" aria-controls="v-pills-policies" aria-selected="false">
                        <span class="material-icons-outlined"> verified_user </span>
                    </a>
                    <a class="nav-link" id="v-pills-reports-tab" title="Reports" data-bs-toggle="pill"
                       href="#v-pills-reports" role="tab" aria-controls="v-pills-reports" aria-selected="false">
                            <span class="material-icons-outlined">
                                report_gmailerrorred
                            </span>
                    </a>
                    <a class="nav-link" id="v-pills-performance-tab" title="Performance" data-bs-toggle="pill"
                       href="#v-pills-performance" role="tab" aria-controls="v-pills-performance" aria-selected="false">
                        <span class="material-icons-outlined"> shutter_speed </span>
                    </a>
                    <a class="nav-link" id="v-pills-goals-tab" title="Goals" data-bs-toggle="pill" href="#v-pills-goals"
                       role="tab" aria-controls="v-pills-goals" aria-selected="false">
                        <span class="material-icons-outlined"> track_changes </span>
                    </a>
                    <a class="nav-link" id="v-pills-training-tab" title="Training" data-bs-toggle="pill"
                       href="#v-pills-training" role="tab" aria-controls="v-pills-training" aria-selected="false">
                        <span class="material-icons-outlined"> checklist_rtl </span>
                    </a>
                    <a class="nav-link" id="v-pills-promotion-tab" title="Promotions" data-bs-toggle="pill"
                       href="#v-pills-promotion" role="tab" aria-controls="v-pills-promotion" aria-selected="false">
                        <span class="material-icons-outlined"> auto_graph </span>
                    </a>
                    <a class="nav-link" id="v-pills-resignation-tab" title="Resignation" data-bs-toggle="pill"
                       href="#v-pills-resignation" role="tab" aria-controls="v-pills-resignation" aria-selected="false">
                            <span class="material-icons-outlined">
                                do_not_disturb_alt
                            </span>
                    </a>
                    <a class="nav-link" id="v-pills-termination-tab" title="Termination" data-bs-toggle="pill"
                       href="#v-pills-termination" role="tab" aria-controls="v-pills-termination" aria-selected="false">
                            <span class="material-icons-outlined">
                                indeterminate_check_box
                            </span>
                    </a>
                    <a class="nav-link" id="v-pills-assets-tab" title="Assets" data-bs-toggle="pill"
                       href="#v-pills-assets" role="tab" aria-controls="v-pills-assets" aria-selected="false">
                        <span class="material-icons-outlined"> web_asset </span>
                    </a>
                    <a class="nav-link" id="v-pills-jobs-tab" title="Jobs" data-bs-toggle="pill" href="#v-pills-jobs"
                       role="tab" aria-controls="v-pills-jobs" aria-selected="false">
                        <span class="material-icons-outlined"> work_outline </span>
                    </a>
                    <a class="nav-link" id="v-pills-knowledgebase-tab" title="Knowledgebase" data-bs-toggle="pill"
                       href="#v-pills-knowledgebase" role="tab" aria-controls="v-pills-knowledgebase"
                       aria-selected="false">
                        <span class="material-icons-outlined"> school </span>
                    </a>
                    <a class="nav-link" id="v-pills-activities-tab" title="Activities" data-bs-toggle="pill"
                       href="#v-pills-activities" role="tab" aria-controls="v-pills-activities" aria-selected="false">
                        <span class="material-icons-outlined"> toggle_off </span>
                    </a>
                    <a class="nav-link" id="v-pills-users-tab" title="Users" data-bs-toggle="pill" href="#v-pills-users"
                       role="tab" aria-controls="v-pills-users" aria-selected="false">
                        <span class="material-icons-outlined"> group_add </span>
                    </a>
                    <a class="nav-link" id="v-pills-settings-tab" title="Settings" data-bs-toggle="pill"
                       href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                        <span class="material-icons-outlined"> settings </span>
                    </a>
                    <a class="nav-link" id="v-pills-profile-tab" title="Profile" data-bs-toggle="pill"
                       href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                        <span class="material-icons-outlined"> manage_accounts </span>
                    </a>
                    <a class="nav-link" id="v-pills-authentication-tab" title="Authentication" data-bs-toggle="pill"
                       href="#v-pills-authentication" role="tab" aria-controls="v-pills-authentication"
                       aria-selected="false">
                            <span class="material-icons-outlined">
                                perm_contact_calendar
                            </span>
                    </a>
                    <a class="nav-link" id="v-pills-errorpages-tab" title="Error Pages" data-bs-toggle="pill"
                       href="#v-pills-errorpages" role="tab" aria-controls="v-pills-errorpages" aria-selected="false">
                        <span class="material-icons-outlined"> announcement </span>
                    </a>
                    <a class="nav-link" id="v-pills-subscriptions-tab" title="Subscriptions" data-bs-toggle="pill"
                       href="#v-pills-subscriptions" role="tab" aria-controls="v-pills-subscriptions"
                       aria-selected="false">
                        <span class="material-icons-outlined"> loyalty </span>
                    </a>
                    <a class="nav-link" id="v-pills-pages-tab" title="Pages" data-bs-toggle="pill" href="#v-pills-pages"
                       role="tab" aria-controls="v-pills-pages" aria-selected="false">
                        <span class="material-icons-outlined"> layers </span>
                    </a>
                    <a class="nav-link" id="v-pills-baseui-tab" title="Base-UI" data-bs-toggle="pill"
                       href="#v-pills-baseui" role="tab" aria-controls="v-pills-baseui" aria-selected="false">
                        <span class="material-icons-outlined"> foundation </span>
                    </a>
                    <a class="nav-link" id="v-pills-elements-tab" title="Elements" data-bs-toggle="pill"
                       href="#v-pills-elements" role="tab" aria-controls="v-pills-elements" aria-selected="false">
                        <span class="material-icons-outlined"> bento </span>
                    </a>
                    <a class="nav-link" id="v-pills-charts-tab" title="Charts" data-bs-toggle="pill"
                       href="#v-pills-charts" role="tab" aria-controls="v-pills-charts" aria-selected="false">
                        <span class="material-icons-outlined"> bar_chart </span>
                    </a>
                    <a class="nav-link" id="v-pills-icons-tab" title="Icons" data-bs-toggle="pill" href="#v-pills-icons"
                       role="tab" aria-controls="v-pills-icons" aria-selected="false">
                        <span class="material-icons-outlined"> grading </span>
                    </a>
                    <a class="nav-link" id="v-pills-forms-tab" title="Forms" data-bs-toggle="pill" href="#v-pills-forms"
                       role="tab" aria-controls="v-pills-forms" aria-selected="false">
                        <span class="material-icons-outlined"> view_day </span>
                    </a>
                    <a class="nav-link" id="v-pills-tables-tab" title="Tables" data-bs-toggle="pill"
                       href="#v-pills-tables" role="tab" aria-controls="v-pills-tables" aria-selected="false">
                        <span class="material-icons-outlined"> table_rows </span>
                    </a>
                    <a class="nav-link" id="v-pills-documentation-tab" title="Documentation" data-bs-toggle="pill"
                       href="#v-pills-documentation" role="tab" aria-controls="v-pills-documentation"
                       aria-selected="false">
                        <span class="material-icons-outlined"> description </span>
                    </a>
                    <a class="nav-link" id="v-pills-changelog-tab" title="Changelog" data-bs-toggle="pill"
                       href="#v-pills-changelog" role="tab" aria-controls="v-pills-changelog" aria-selected="false">
                        <span class="material-icons-outlined"> sync_alt </span>
                    </a>
                    <a class="nav-link" id="v-pills-multilevel-tab" title="Multilevel" data-bs-toggle="pill"
                       href="#v-pills-multilevel" role="tab" aria-controls="v-pills-multilevel" aria-selected="false">
                        <span class="material-icons-outlined"> library_add_check </span>
                    </a>
                </div>
            </div>

            <div class="sidebar-right">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-dashboard" role="tabpanel"
                         aria-labelledby="v-pills-dashboard-tab">
                        <p>Dashboard</p>
                        <ul>
                            <li>
                                <a href="admin-dashboard.html" class="active">Admin Dashboard</a>
                            </li>
                            <li>
                                <a href="employee-dashboard.html">Employee Dashboard</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-apps" role="tabpanel" aria-labelledby="v-pills-apps-tab">
                        <p>App</p>
                        <ul>
                            <li>
                                <a href="chat.html">Chat</a>
                            </li>
                            <li class="sub-menu">
                                <a href="#">Calls <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="voice-call.html">Voice Call</a></li>
                                    <li><a href="video-call.html">Video Call</a></li>
                                    <li><a href="outgoing-call.html">Outgoing Call</a></li>
                                    <li><a href="incoming-call.html">Incoming Call</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="calender.html">Calendar</a>
                            </li>
                            <li>
                                <a href="contacts.html">Contacts</a>
                            </li>
                            <li>
                                <a href="inbox.html">Email</a>
                            </li>
                            <li>
                                <a href="file-manager.html">File Manager</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-employees" role="tabpanel"
                         aria-labelledby="v-pills-employees-tab">
                        <p>Employees</p>
                        <ul>
                            <li><a href="employees.html">All Employees</a></li>
                            <li><a href="holidays.html">Holidays</a></li>
                            <li>
                                <a href="leaves.html">Leaves (Admin)
                                    <span class="badge rounded-pill bg-primary float-end">1</span></a>
                            </li>
                            <li><a href="leaves-employee.html">Leaves (Employee)</a></li>
                            <li><a href="leave-settings.html">Leave Settings</a></li>
                            <li><a href="attendance.html">Attendance (Admin)</a></li>
                            <li>
                                <a href="attendance-employee.html">Attendance (Employee)</a>
                            </li>
                            <li><a href="departments.html">Departments</a></li>
                            <li><a href="designations.html">Designations</a></li>
                            <li><a href="timesheet.html">Timesheet</a></li>
                            <li><a href="shift-scheduling.html">Shift & Schedule</a></li>
                            <li><a href="overtime.html">Overtime</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-clients" role="tabpanel"
                         aria-labelledby="v-pills-clients-tab">
                        <p>Clients</p>
                        <ul>
                            <li><a href="clients.html">Clients</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-projects" role="tabpanel"
                         aria-labelledby="v-pills-projects-tab">
                        <p>Projects</p>
                        <ul>
                            <li><a href="projects.html">Projects</a></li>
                            <li><a href="tasks.html">Tasks</a></li>
                            <li><a href="task-board.html">Task Board</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-leads" role="tabpanel" aria-labelledby="v-pills-leads-tab">
                        <p>Leads</p>
                        <ul>
                            <li><a href="leads.html">Leads</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-tickets" role="tabpanel"
                         aria-labelledby="v-pills-tickets-tab">
                        <p>Tickets</p>
                        <ul>
                            <li><a href="tickets.html">Tickets</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-sales" role="tabpanel" aria-labelledby="v-pills-sales-tab">
                        <p>Sales</p>
                        <ul>
                            <li><a href="estimates.html">Estimates</a></li>
                            <li><a href="invoices.html">Invoices</a></li>
                            <li><a href="payments.html">Payments</a></li>
                            <li><a href="expenses.html">Expenses</a></li>
                            <li><a href="provident-fund.html">Provident Fund</a></li>
                            <li><a href="taxes.html">Taxes</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-accounting" role="tabpanel"
                         aria-labelledby="v-pills-accounting-tab">
                        <p>Accounting</p>
                        <ul>
                            <li><a href="categories.html">Categories</a></li>
                            <li><a href="budgets.html">Budgets</a></li>
                            <li><a href="budget-expenses.html">Budget Expenses</a></li>
                            <li><a href="budget-revenues.html">Budget Revenues</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-payroll" role="tabpanel"
                         aria-labelledby="v-pills-payroll-tab">
                        <p>Payroll</p>
                        <ul>
                            <li><a href="salary.html"> Employee Salary </a></li>
                            <li><a href="salary-view.html"> Payslip </a></li>
                            <li><a href="payroll-items.html"> Payroll Items </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-policies" role="tabpanel"
                         aria-labelledby="v-pills-policies-tab">
                        <p>Policies</p>
                        <ul>
                            <li><a href="policies.html"> Policies </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-reports" role="tabpanel"
                         aria-labelledby="v-pills-reports-tab">
                        <p>Reports</p>
                        <ul>
                            <li><a href="expense-reports.html"> Expense Report </a></li>
                            <li><a href="invoice-reports.html"> Invoice Report </a></li>
                            <li><a href="payments-reports.html"> Payments Report </a></li>
                            <li><a href="project-reports.html"> Project Report </a></li>
                            <li><a href="task-reports.html"> Task Report </a></li>
                            <li><a href="user-reports.html"> User Report </a></li>
                            <li><a href="employee-reports.html"> Employee Report </a></li>
                            <li><a href="payslip-reports.html"> Payslip Report </a></li>
                            <li>
                                <a href="attendance-reports.html"> Attendance Report </a>
                            </li>
                            <li><a href="leave-reports.html"> Leave Report </a></li>
                            <li><a href="daily-reports.html"> Daily Report </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-performance" role="tabpanel"
                         aria-labelledby="v-pills-performance-tab">
                        <p>Performance</p>
                        <ul>
                            <li>
                                <a href="performance-indicator.html">
                                    Performance Indicator
                                </a>
                            </li>
                            <li><a href="performance.html"> Performance Review </a></li>
                            <li>
                                <a href="performance-appraisal.html">
                                    Performance Appraisal
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-goals" role="tabpanel" aria-labelledby="v-pills-goals-tab">
                        <p>Goals</p>
                        <ul>
                            <li><a href="goal-tracking.html"> Goal List </a></li>
                            <li><a href="goal-type.html"> Goal Type </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-training" role="tabpanel"
                         aria-labelledby="v-pills-training-tab">
                        <p>Training</p>
                        <ul>
                            <li><a href="training.html"> Training List </a></li>
                            <li><a href="trainers.html"> Trainers</a></li>
                            <li><a href="training-type.html"> Training Type </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-promotion" role="tabpanel"
                         aria-labelledby="v-pills-promotion-tab">
                        <p>Promotion</p>
                        <ul>
                            <li><a href="promotion.html"> Promotion </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-resignation" role="tabpanel"
                         aria-labelledby="v-pills-resignation-tab">
                        <p>Resignation</p>
                        <ul>
                            <li><a href="resignation.html"> Resignation </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-termination" role="tabpanel"
                         aria-labelledby="v-pills-termination-tab">
                        <p>Termination</p>
                        <ul>
                            <li><a href="termination.html"> Termination </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-assets" role="tabpanel" aria-labelledby="v-pills-assets-tab">
                        <p>Assets</p>
                        <ul>
                            <li><a href="assets.html"> Assets </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-jobs" role="tabpanel" aria-labelledby="v-pills-jobs-tab">
                        <p>Jobs</p>
                        <ul>
                            <li>
                                <a href="user-dashboard.html" class="active">
                                    User Dasboard
                                </a>
                            </li>
                            <li><a href="jobs-dashboard.html"> Jobs Dasboard </a></li>
                            <li><a href="jobs.html"> Manage Jobs </a></li>
                            <li><a href="job-applicants.html"> Applied Jobs </a></li>
                            <li><a href="manage-resumes.html"> Manage Resumes </a></li>
                            <li>
                                <a href="shortlist-candidates.html">
                                    Shortlist Candidates
                                </a>
                            </li>
                            <li>
                                <a href="interview-questions.html"> Interview Questions </a>
                            </li>
                            <li><a href="offer_approvals.html"> Offer Approvals </a></li>
                            <li>
                                <a href="experiance-level.html"> Experience Level </a>
                            </li>
                            <li><a href="candidates.html"> Candidates List </a></li>
                            <li><a href="schedule-timing.html"> Schedule timing </a></li>
                            <li>
                                <a href="apptitude-result.html"> Aptitude Results </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-knowledgebase" role="tabpanel"
                         aria-labelledby="v-pills-knowledgebase-tab">
                        <p>Knowledgebase</p>
                        <ul>
                            <li><a href="knowledgebase.html"> Knowledgebase </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-activities" role="tabpanel"
                         aria-labelledby="v-pills-activities-tab">
                        <p>Activities</p>
                        <ul>
                            <li><a href="activities.html"> Activities </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-users" role="tabpanel"
                         aria-labelledby="v-pills-activities-tab">
                        <p>Users</p>
                        <ul>
                            <li><a href="users.html"> Users </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-settings" role="tabpanel"
                         aria-labelledby="v-pills-settings-tab">
                        <p>Settings</p>
                        <ul>
                            <li><a href="settings.html"> Settings </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                         aria-labelledby="v-pills-profile-tab">
                        <p>Profile</p>
                        <ul>
                            <li><a href="profile.html"> Employee Profile </a></li>
                            <li><a href="client-profile.html"> Client Profile </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-authentication" role="tabpanel"
                         aria-labelledby="v-pills-authentication-tab">
                        <p>Authentication</p>
                        <ul>
                            <li><a href="index.html"> Login </a></li>
                            <li><a href="register.html"> Register </a></li>
                            <li><a href="forgot-password.html"> Forgot Password </a></li>
                            <li><a href="otp.html"> OTP </a></li>
                            <li><a href="lock-screen.html"> Lock Screen </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-errorpages" role="tabpanel"
                         aria-labelledby="v-pills-errorpages-tab">
                        <p>Error Pages</p>
                        <ul>
                            <li><a href="error-404.html">404 Error </a></li>
                            <li><a href="error-500.html">500 Error </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-subscriptions" role="tabpanel"
                         aria-labelledby="v-pills-subscriptions-tab">
                        <p>Subscriptions</p>
                        <ul>
                            <li>
                                <a href="subscriptions.html"> Subscriptions (Admin) </a>
                            </li>
                            <li>
                                <a href="subscriptions-company.html">
                                    Subscriptions (Company)
                                </a>
                            </li>
                            <li>
                                <a href="subscribed-companies.html">
                                    Subscribed Companies</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-pages" role="tabpanel" aria-labelledby="v-pills-pages-tab">
                        <p>Pages</p>
                        <ul>
                            <li><a href="search.html"> Search </a></li>
                            <li><a href="faq.html"> FAQ </a></li>
                            <li><a href="terms.html"> Terms </a></li>
                            <li><a href="privacy-policy.html"> Privacy Policy </a></li>
                            <li><a href="blank-page.html"> Blank Page </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-baseui" role="tabpanel" aria-labelledby="v-pills-baseui-tab">
                        <p>Base-UI</p>
                        <ul>
                            <li><a href="alerts.html">Alerts</a></li>
                            <li><a href="accordions.html">Accordions</a></li>
                            <li><a href="avatar.html">Avatar</a></li>
                            <li><a href="badges.html">Badges</a></li>
                            <li><a href="buttons.html">Buttons</a></li>
                            <li><a href="buttongroup.html">Button Group</a></li>
                            <li><a href="breadcrumbs.html">Breadcrumb</a></li>
                            <li><a href="cards.html">Cards</a></li>
                            <li><a href="carousel.html">Carousel</a></li>
                            <li><a href="dropdowns.html">Dropdowns</a></li>
                            <li><a href="grid.html">Grid</a></li>
                            <li><a href="images.html">Images</a></li>
                            <li><a href="lightbox.html">Lightbox</a></li>
                            <li><a href="media.html">Media</a></li>
                            <li><a href="modal.html">Modals</a></li>
                            <li><a href="offcanvas.html">Offcanvas</a></li>
                            <li><a href="pagination.html">Pagination</a></li>
                            <li><a href="popover.html">Popover</a></li>
                            <li><a href="progress.html">Progress Bars</a></li>
                            <li><a href="placeholders.html">Placeholders</a></li>
                            <li><a href="rangeslider.html">Range Slider</a></li>
                            <li><a href="spinners.html">Spinner</a></li>
                            <li><a href="sweetalerts.html">Sweet Alerts</a></li>
                            <li><a href="tab.html">Tabs</a></li>
                            <li><a href="toastr.html">Toasts</a></li>
                            <li><a href="tooltip.html">Tooltip</a></li>
                            <li><a href="typography.html">Typography</a></li>
                            <li><a href="video.html">Video</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-elements" role="tabpanel"
                         aria-labelledby="v-pills-elements-tab">
                        <p>Elements</p>
                        <ul>
                            <li><a href="ribbon.html">Ribbon</a></li>
                            <li><a href="clipboard.html">Clipboard</a></li>
                            <li><a href="drag-drop.html">Drag & Drop</a></li>
                            <li><a href="rating.html">Rating</a></li>
                            <li><a href="text-editor.html">Text Editor</a></li>
                            <li><a href="counter.html">Counter</a></li>
                            <li><a href="scrollbar.html">Scrollbar</a></li>
                            <li><a href="notification.html">Notification</a></li>
                            <li><a href="stickynote.html">Sticky Note</a></li>
                            <li><a href="timeline.html">Timeline</a></li>
                            <li>
                                <a href="horizontal-timeline.html">Horizontal Timeline</a>
                            </li>
                            <li><a href="form-wizard.html">Form Wizard</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-charts" role="tabpanel" aria-labelledby="v-pills-charts-tab">
                        <p>Charts</p>
                        <ul>
                            <li><a href="chart-apex.html">Apex Charts</a></li>
                            <li><a href="chart-js.html">Chart Js</a></li>
                            <li><a href="chart-morris.html">Morris Charts</a></li>
                            <li><a href="chart-flot.html">Flot Charts</a></li>
                            <li><a href="chart-peity.html">Peity Charts</a></li>
                            <li><a href="chart-c3.html">C3 Charts</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-icons" role="tabpanel" aria-labelledby="v-pills-icons-tab">
                        <p>Icons</p>
                        <ul>
                            <li><a href="icon-fontawesome.html">Fontawesome Icons</a></li>
                            <li><a href="icon-feather.html">Feather Icons</a></li>
                            <li><a href="icon-ionic.html">Ionic Icons</a></li>
                            <li><a href="icon-material.html">Material Icons</a></li>
                            <li><a href="icon-pe7.html">Pe7 Icons</a></li>
                            <li><a href="icon-simpleline.html">Simpleline Icons</a></li>
                            <li><a href="icon-themify.html">Themify Icons</a></li>
                            <li><a href="icon-weather.html">Weather Icons</a></li>
                            <li><a href="icon-typicon.html">Typicon Icons</a></li>
                            <li><a href="icon-flag.html">Flag Icons</a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-forms" role="tabpanel" aria-labelledby="v-pills-forms-tab">
                        <p>Forms</p>
                        <ul>
                            <li><a href="form-basic-inputs.html">Basic Inputs </a></li>
                            <li><a href="form-input-groups.html">Input Groups </a></li>
                            <li><a href="form-horizontal.html">Horizontal Form </a></li>
                            <li><a href="form-vertical.html"> Vertical Form </a></li>
                            <li><a href="form-mask.html"> Form Mask </a></li>
                            <li><a href="form-validation.html"> Form Validation </a></li>
                            <li><a href="form-select2.html">Form Select2 </a></li>
                            <li><a href="form-fileupload.html">File Upload </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-tables" role="tabpanel" aria-labelledby="v-pills-tables-tab">
                        <p>Tables</p>
                        <ul>
                            <li><a href="tables-basic.html">Basic Tables </a></li>
                            <li><a href="data-tables.html">Data Table </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-documentation" role="tabpanel"
                         aria-labelledby="v-pills-documentation-tab">
                        <p>Documentation</p>
                        <ul>
                            <li><a href="#">Documentation </a></li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-changelog" role="tabpanel"
                         aria-labelledby="v-pills-changelog-tab">
                        <p>Change Log</p>
                        <ul>
                            <li>
                                <a href="#"><span>Change Log</span>
                                    <span class="badge badge-primary ms-auto">v3.4</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="v-pills-multilevel" role="tabpanel"
                         aria-labelledby="v-pills-multilevel-tab">
                        <p>Multi Level</p>
                        <ul>
                            <li class="sub-menu">
                                <a href="javascript:void(0);">Level 1 <span class="menu-arrow"></span></a>
                                <ul class="ms-3">
                                    <li class="sub-menu">
                                        <a href="javascript:void(0);">Level 1 <span class="menu-arrow"></span></a>
                                        <ul>
                                            <li><a href="javascript:void(0);">Level 2</a></li>
                                            <li><a href="javascript:void(0);">Level 3</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="javascript:void(0);">Level 2</a></li>
                            <li><a href="javascript:void(0);">Level 3</a></li>
                        </ul>
=======
                </div>
            </div>
        </div>

        <!-- /Sidebar -->

        <!-- Two Col Sidebar -->
        <div class="two-col-bar" id="two-col-bar">
            <div class="sidebar sidebar-twocol" id="navbar-nav">
                <div class="sidebar-left slimscroll">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-dashboard-tab" title="Dashboard" data-bs-toggle="pill" href="#v-pills-dashboard" role="tab" aria-controls="v-pills-dashboard" aria-selected="true">
                            <span class="material-icons-outlined"> home </span>
                        </a>
                        <a class="nav-link" id="v-pills-apps-tab" title="Apps" data-bs-toggle="pill" href="#v-pills-apps" role="tab" aria-controls="v-pills-apps" aria-selected="false">
                            <span class="material-icons-outlined"> dashboard </span>
                        </a>
                        <a class="nav-link" id="v-pills-employees-tab" title="Employees" data-bs-toggle="pill" href="#v-pills-employees" role="tab" aria-controls="v-pills-employees" aria-selected="false">
                            <span class="material-icons-outlined"> people </span>
                        </a>
                        <a class="nav-link" id="v-pills-clients-tab" title="Clients" data-bs-toggle="pill" href="#v-pills-clients" role="tab" aria-controls="v-pills-clients" aria-selected="false">
                            <span class="material-icons-outlined"> person </span>
                        </a>
                        <a class="nav-link" id="v-pills-projects-tab" title="Projects" data-bs-toggle="pill" href="#v-pills-projects" role="tab" aria-controls="v-pills-projects" aria-selected="false">
                            <span class="material-icons-outlined"> topic </span>
                        </a>
                        <a class="nav-link" id="v-pills-leads-tab" title="Leads" data-bs-toggle="pill" href="#v-pills-leads" role="tab" aria-controls="v-pills-leads" aria-selected="false">
                            <span class="material-icons-outlined"> leaderboard </span>
                        </a>
                        <a class="nav-link" id="v-pills-tickets-tab" title="Tickets" data-bs-toggle="pill" href="#v-pills-tickets" role="tab" aria-controls="v-pills-tickets" aria-selected="false">
                            <span class="material-icons-outlined">
                                confirmation_number
                            </span>
                        </a>
                        <a class="nav-link" id="v-pills-sales-tab" title="Sales" data-bs-toggle="pill" href="#v-pills-sales" role="tab" aria-controls="v-pills-sales" aria-selected="false">
                            <span class="material-icons-outlined"> shopping_bag </span>
                        </a>
                        <a class="nav-link" id="v-pills-accounting-tab" title="Accounting" data-bs-toggle="pill" href="#v-pills-accounting" role="tab" aria-controls="v-pills-accounting" aria-selected="false">
                            <span class="material-icons-outlined">
                                account_balance_wallet
                            </span>
                        </a>
                        <a class="nav-link" id="v-pills-payroll-tab" title="Payroll" data-bs-toggle="pill" href="#v-pills-payroll" role="tab" aria-controls="v-pills-payroll" aria-selected="false">
                            <span class="material-icons-outlined"> request_quote </span>
                        </a>
                        <a class="nav-link" id="v-pills-policies-tab" title="Policies" data-bs-toggle="pill" href="#v-pills-policies" role="tab" aria-controls="v-pills-policies" aria-selected="false">
                            <span class="material-icons-outlined"> verified_user </span>
                        </a>
                        <a class="nav-link" id="v-pills-reports-tab" title="Reports" data-bs-toggle="pill" href="#v-pills-reports" role="tab" aria-controls="v-pills-reports" aria-selected="false">
                            <span class="material-icons-outlined">
                                report_gmailerrorred
                            </span>
                        </a>
                        <a class="nav-link" id="v-pills-performance-tab" title="Performance" data-bs-toggle="pill" href="#v-pills-performance" role="tab" aria-controls="v-pills-performance" aria-selected="false">
                            <span class="material-icons-outlined"> shutter_speed </span>
                        </a>
                        <a class="nav-link" id="v-pills-goals-tab" title="Goals" data-bs-toggle="pill" href="#v-pills-goals" role="tab" aria-controls="v-pills-goals" aria-selected="false">
                            <span class="material-icons-outlined"> track_changes </span>
                        </a>
                        <a class="nav-link" id="v-pills-training-tab" title="Training" data-bs-toggle="pill" href="#v-pills-training" role="tab" aria-controls="v-pills-training" aria-selected="false">
                            <span class="material-icons-outlined"> checklist_rtl </span>
                        </a>
                        <a class="nav-link" id="v-pills-promotion-tab" title="Promotions" data-bs-toggle="pill" href="#v-pills-promotion" role="tab" aria-controls="v-pills-promotion" aria-selected="false">
                            <span class="material-icons-outlined"> auto_graph </span>
                        </a>
                        <a class="nav-link" id="v-pills-resignation-tab" title="Resignation" data-bs-toggle="pill" href="#v-pills-resignation" role="tab" aria-controls="v-pills-resignation" aria-selected="false">
                            <span class="material-icons-outlined">
                                do_not_disturb_alt
                            </span>
                        </a>
                        <a class="nav-link" id="v-pills-termination-tab" title="Termination" data-bs-toggle="pill" href="#v-pills-termination" role="tab" aria-controls="v-pills-termination" aria-selected="false">
                            <span class="material-icons-outlined">
                                indeterminate_check_box
                            </span>
                        </a>
                        <a class="nav-link" id="v-pills-assets-tab" title="Assets" data-bs-toggle="pill" href="#v-pills-assets" role="tab" aria-controls="v-pills-assets" aria-selected="false">
                            <span class="material-icons-outlined"> web_asset </span>
                        </a>
                        <a class="nav-link" id="v-pills-jobs-tab" title="Jobs" data-bs-toggle="pill" href="#v-pills-jobs" role="tab" aria-controls="v-pills-jobs" aria-selected="false">
                            <span class="material-icons-outlined"> work_outline </span>
                        </a>
                        <a class="nav-link" id="v-pills-knowledgebase-tab" title="Knowledgebase" data-bs-toggle="pill" href="#v-pills-knowledgebase" role="tab" aria-controls="v-pills-knowledgebase" aria-selected="false">
                            <span class="material-icons-outlined"> school </span>
                        </a>
                        <a class="nav-link" id="v-pills-activities-tab" title="Activities" data-bs-toggle="pill" href="#v-pills-activities" role="tab" aria-controls="v-pills-activities" aria-selected="false">
                            <span class="material-icons-outlined"> toggle_off </span>
                        </a>
                        <a class="nav-link" id="v-pills-users-tab" title="Users" data-bs-toggle="pill" href="#v-pills-users" role="tab" aria-controls="v-pills-users" aria-selected="false">
                            <span class="material-icons-outlined"> group_add </span>
                        </a>
                        <a class="nav-link" id="v-pills-settings-tab" title="Settings" data-bs-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                            <span class="material-icons-outlined"> settings </span>
                        </a>
                        <a class="nav-link" id="v-pills-profile-tab" title="Profile" data-bs-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                            <span class="material-icons-outlined"> manage_accounts </span>
                        </a>
                        <a class="nav-link" id="v-pills-authentication-tab" title="Authentication" data-bs-toggle="pill" href="#v-pills-authentication" role="tab" aria-controls="v-pills-authentication" aria-selected="false">
                            <span class="material-icons-outlined">
                                perm_contact_calendar
                            </span>
                        </a>
                        <a class="nav-link" id="v-pills-errorpages-tab" title="Error Pages" data-bs-toggle="pill" href="#v-pills-errorpages" role="tab" aria-controls="v-pills-errorpages" aria-selected="false">
                            <span class="material-icons-outlined"> announcement </span>
                        </a>
                        <a class="nav-link" id="v-pills-subscriptions-tab" title="Subscriptions" data-bs-toggle="pill" href="#v-pills-subscriptions" role="tab" aria-controls="v-pills-subscriptions" aria-selected="false">
                            <span class="material-icons-outlined"> loyalty </span>
                        </a>
                        <a class="nav-link" id="v-pills-pages-tab" title="Pages" data-bs-toggle="pill" href="#v-pills-pages" role="tab" aria-controls="v-pills-pages" aria-selected="false">
                            <span class="material-icons-outlined"> layers </span>
                        </a>
                        <a class="nav-link" id="v-pills-baseui-tab" title="Base-UI" data-bs-toggle="pill" href="#v-pills-baseui" role="tab" aria-controls="v-pills-baseui" aria-selected="false">
                            <span class="material-icons-outlined"> foundation </span>
                        </a>
                        <a class="nav-link" id="v-pills-elements-tab" title="Elements" data-bs-toggle="pill" href="#v-pills-elements" role="tab" aria-controls="v-pills-elements" aria-selected="false">
                            <span class="material-icons-outlined"> bento </span>
                        </a>
                        <a class="nav-link" id="v-pills-charts-tab" title="Charts" data-bs-toggle="pill" href="#v-pills-charts" role="tab" aria-controls="v-pills-charts" aria-selected="false">
                            <span class="material-icons-outlined"> bar_chart </span>
                        </a>
                        <a class="nav-link" id="v-pills-icons-tab" title="Icons" data-bs-toggle="pill" href="#v-pills-icons" role="tab" aria-controls="v-pills-icons" aria-selected="false">
                            <span class="material-icons-outlined"> grading </span>
                        </a>
                        <a class="nav-link" id="v-pills-forms-tab" title="Forms" data-bs-toggle="pill" href="#v-pills-forms" role="tab" aria-controls="v-pills-forms" aria-selected="false">
                            <span class="material-icons-outlined"> view_day </span>
                        </a>
                        <a class="nav-link" id="v-pills-tables-tab" title="Tables" data-bs-toggle="pill" href="#v-pills-tables" role="tab" aria-controls="v-pills-tables" aria-selected="false">
                            <span class="material-icons-outlined"> table_rows </span>
                        </a>
                        <a class="nav-link" id="v-pills-documentation-tab" title="Documentation" data-bs-toggle="pill" href="#v-pills-documentation" role="tab" aria-controls="v-pills-documentation" aria-selected="false">
                            <span class="material-icons-outlined"> description </span>
                        </a>
                        <a class="nav-link" id="v-pills-changelog-tab" title="Changelog" data-bs-toggle="pill" href="#v-pills-changelog" role="tab" aria-controls="v-pills-changelog" aria-selected="false">
                            <span class="material-icons-outlined"> sync_alt </span>
                        </a>
                        <a class="nav-link" id="v-pills-multilevel-tab" title="Multilevel" data-bs-toggle="pill" href="#v-pills-multilevel" role="tab" aria-controls="v-pills-multilevel" aria-selected="false">
                            <span class="material-icons-outlined"> library_add_check </span>
                        </a>
                    </div>
                </div>

                <div class="sidebar-right">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-dashboard" role="tabpanel" aria-labelledby="v-pills-dashboard-tab">
                            <p>Dashboard</p>
                            <ul>
                                <li>
                                    <a href="admin-dashboard.html" class="active">Admin Dashboard</a>
                                </li>
                                <li>
                                    <a href="employee-dashboard.html">Employee Dashboard</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-apps" role="tabpanel" aria-labelledby="v-pills-apps-tab">
                            <p>App</p>
                            <ul>
                                <li>
                                    <a href="chat.html">Chat</a>
                                </li>
                                <li class="sub-menu">
                                    <a href="#">Calls <span class="menu-arrow"></span></a>
                                    <ul>
                                        <li><a href="voice-call.html">Voice Call</a></li>
                                        <li><a href="video-call.html">Video Call</a></li>
                                        <li><a href="outgoing-call.html">Outgoing Call</a></li>
                                        <li><a href="incoming-call.html">Incoming Call</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="calender.html">Calendar</a>
                                </li>
                                <li>
                                    <a href="contacts.html">Contacts</a>
                                </li>
                                <li>
                                    <a href="inbox.html">Email</a>
                                </li>
                                <li>
                                    <a href="file-manager.html">File Manager</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-employees" role="tabpanel" aria-labelledby="v-pills-employees-tab">
                            <p>Employees</p>
                            <ul>
                                <li><a href="employees.html">All Employees</a></li>
                                <li><a href="holidays.html">Holidays</a></li>
                                <li>
                                    <a href="leaves.html">Leaves (Admin)
                                        <span class="badge rounded-pill bg-primary float-end">1</span></a>
                                </li>
                                <li><a href="leaves-employee.html">Leaves (Employee)</a></li>
                                <li><a href="leave-settings.html">Leave Settings</a></li>
                                <li><a href="attendance.html">Attendance (Admin)</a></li>
                                <li>
                                    <a href="attendance-employee.html">Attendance (Employee)</a>
                                </li>
                                <li><a href="departments.html">Departments</a></li>
                                <li><a href="designations.html">Designations</a></li>
                                <li><a href="timesheet.html">Timesheet</a></li>
                                <li><a href="shift-scheduling.html">Shift & Schedule</a></li>
                                <li><a href="overtime.html">Overtime</a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-clients" role="tabpanel" aria-labelledby="v-pills-clients-tab">
                            <p>Clients</p>
                            <ul>
                                <li><a href="clients.html">Clients</a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-projects" role="tabpanel" aria-labelledby="v-pills-projects-tab">
                            <p>Projects</p>
                            <ul>
                                <li><a href="projects.html">Projects</a></li>
                                <li><a href="tasks.html">Tasks</a></li>
                                <li><a href="task-board.html">Task Board</a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-leads" role="tabpanel" aria-labelledby="v-pills-leads-tab">
                            <p>Leads</p>
                            <ul>
                                <li><a href="leads.html">Leads</a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-tickets" role="tabpanel" aria-labelledby="v-pills-tickets-tab">
                            <p>Tickets</p>
                            <ul>
                                <li><a href="tickets.html">Tickets</a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-sales" role="tabpanel" aria-labelledby="v-pills-sales-tab">
                            <p>Sales</p>
                            <ul>
                                <li><a href="estimates.html">Estimates</a></li>
                                <li><a href="invoices.html">Invoices</a></li>
                                <li><a href="payments.html">Payments</a></li>
                                <li><a href="expenses.html">Expenses</a></li>
                                <li><a href="provident-fund.html">Provident Fund</a></li>
                                <li><a href="taxes.html">Taxes</a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-accounting" role="tabpanel" aria-labelledby="v-pills-accounting-tab">
                            <p>Accounting</p>
                            <ul>
                                <li><a href="categories.html">Categories</a></li>
                                <li><a href="budgets.html">Budgets</a></li>
                                <li><a href="budget-expenses.html">Budget Expenses</a></li>
                                <li><a href="budget-revenues.html">Budget Revenues</a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-payroll" role="tabpanel" aria-labelledby="v-pills-payroll-tab">
                            <p>Payroll</p>
                            <ul>
                                <li><a href="salary.html"> Employee Salary </a></li>
                                <li><a href="salary-view.html"> Payslip </a></li>
                                <li><a href="payroll-items.html"> Payroll Items </a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-policies" role="tabpanel" aria-labelledby="v-pills-policies-tab">
                            <p>Policies</p>
                            <ul>
                                <li><a href="policies.html"> Policies </a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-reports" role="tabpanel" aria-labelledby="v-pills-reports-tab">
                            <p>Reports</p>
                            <ul>
                                <li><a href="expense-reports.html"> Expense Report </a></li>
                                <li><a href="invoice-reports.html"> Invoice Report </a></li>
                                <li><a href="payments-reports.html"> Payments Report </a></li>
                                <li><a href="project-reports.html"> Project Report </a></li>
                                <li><a href="task-reports.html"> Task Report </a></li>
                                <li><a href="user-reports.html"> User Report </a></li>
                                <li><a href="employee-reports.html"> Employee Report </a></li>
                                <li><a href="payslip-reports.html"> Payslip Report </a></li>
                                <li>
                                    <a href="attendance-reports.html"> Attendance Report </a>
                                </li>
                                <li><a href="leave-reports.html"> Leave Report </a></li>
                                <li><a href="daily-reports.html"> Daily Report </a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-performance" role="tabpanel" aria-labelledby="v-pills-performance-tab">
                            <p>Performance</p>
                            <ul>
                                <li>
                                    <a href="performance-indicator.html">
                                        Performance Indicator
                                    </a>
                                </li>
                                <li><a href="performance.html"> Performance Review </a></li>
                                <li>
                                    <a href="performance-appraisal.html">
                                        Performance Appraisal
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-goals" role="tabpanel" aria-labelledby="v-pills-goals-tab">
                            <p>Goals</p>
                            <ul>
                                <li><a href="goal-tracking.html"> Goal List </a></li>
                                <li><a href="goal-type.html"> Goal Type </a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-training" role="tabpanel" aria-labelledby="v-pills-training-tab">
                            <p>Training</p>
                            <ul>
                                <li><a href="training.html"> Training List </a></li>
                                <li><a href="trainers.html"> Trainers</a></li>
                                <li><a href="training-type.html"> Training Type </a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-promotion" role="tabpanel" aria-labelledby="v-pills-promotion-tab">
                            <p>Promotion</p>
                            <ul>
                                <li><a href="promotion.html"> Promotion </a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-resignation" role="tabpanel" aria-labelledby="v-pills-resignation-tab">
                            <p>Resignation</p>
                            <ul>
                                <li><a href="resignation.html"> Resignation </a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-termination" role="tabpanel" aria-labelledby="v-pills-termination-tab">
                            <p>Termination</p>
                            <ul>
                                <li><a href="termination.html"> Termination </a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-assets" role="tabpanel" aria-labelledby="v-pills-assets-tab">
                            <p>Assets</p>
                            <ul>
                                <li><a href="assets.html"> Assets </a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-jobs" role="tabpanel" aria-labelledby="v-pills-jobs-tab">
                            <p>Jobs</p>
                            <ul>
                                <li>
                                    <a href="user-dashboard.html" class="active">
                                        User Dasboard
                                    </a>
                                </li>
                                <li><a href="jobs-dashboard.html"> Jobs Dasboard </a></li>
                                <li><a href="jobs.html"> Manage Jobs </a></li>
                                <li><a href="job-applicants.html"> Applied Jobs </a></li>
                                <li><a href="manage-resumes.html"> Manage Resumes </a></li>
                                <li>
                                    <a href="shortlist-candidates.html">
                                        Shortlist Candidates
                                    </a>
                                </li>
                                <li>
                                    <a href="interview-questions.html"> Interview Questions </a>
                                </li>
                                <li><a href="offer_approvals.html"> Offer Approvals </a></li>
                                <li>
                                    <a href="experiance-level.html"> Experience Level </a>
                                </li>
                                <li><a href="candidates.html"> Candidates List </a></li>
                                <li><a href="schedule-timing.html"> Schedule timing </a></li>
                                <li>
                                    <a href="apptitude-result.html"> Aptitude Results </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-knowledgebase" role="tabpanel" aria-labelledby="v-pills-knowledgebase-tab">
                            <p>Knowledgebase</p>
                            <ul>
                                <li><a href="knowledgebase.html"> Knowledgebase </a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-activities" role="tabpanel" aria-labelledby="v-pills-activities-tab">
                            <p>Activities</p>
                            <ul>
                                <li><a href="activities.html"> Activities </a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-users" role="tabpanel" aria-labelledby="v-pills-activities-tab">
                            <p>Users</p>
                            <ul>
                                <li><a href="users.html"> Users </a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                            <p>Settings</p>
                            <ul>
                                <li><a href="settings.html"> Settings </a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                            <p>Profile</p>
                            <ul>
                                <li><a href="profile.html"> Employee Profile </a></li>
                                <li><a href="client-profile.html"> Client Profile </a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-authentication" role="tabpanel" aria-labelledby="v-pills-authentication-tab">
                            <p>Authentication</p>
                            <ul>
                                <li><a href="index.html"> Login </a></li>
                                <li><a href="register.html"> Register </a></li>
                                <li><a href="forgot-password.html"> Forgot Password </a></li>
                                <li><a href="otp.html"> OTP </a></li>
                                <li><a href="lock-screen.html"> Lock Screen </a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-errorpages" role="tabpanel" aria-labelledby="v-pills-errorpages-tab">
                            <p>Error Pages</p>
                            <ul>
                                <li><a href="error-404.html">404 Error </a></li>
                                <li><a href="error-500.html">500 Error </a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-subscriptions" role="tabpanel" aria-labelledby="v-pills-subscriptions-tab">
                            <p>Subscriptions</p>
                            <ul>
                                <li>
                                    <a href="subscriptions.html"> Subscriptions (Admin) </a>
                                </li>
                                <li>
                                    <a href="subscriptions-company.html">
                                        Subscriptions (Company)
                                    </a>
                                </li>
                                <li>
                                    <a href="subscribed-companies.html">
                                        Subscribed Companies</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-pages" role="tabpanel" aria-labelledby="v-pills-pages-tab">
                            <p>Pages</p>
                            <ul>
                                <li><a href="search.html"> Search </a></li>
                                <li><a href="faq.html"> FAQ </a></li>
                                <li><a href="terms.html"> Terms </a></li>
                                <li><a href="privacy-policy.html"> Privacy Policy </a></li>
                                <li><a href="blank-page.html"> Blank Page </a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-baseui" role="tabpanel" aria-labelledby="v-pills-baseui-tab">
                            <p>Base-UI</p>
                            <ul>
                                <li><a href="alerts.html">Alerts</a></li>
                                <li><a href="accordions.html">Accordions</a></li>
                                <li><a href="avatar.html">Avatar</a></li>
                                <li><a href="badges.html">Badges</a></li>
                                <li><a href="buttons.html">Buttons</a></li>
                                <li><a href="buttongroup.html">Button Group</a></li>
                                <li><a href="breadcrumbs.html">Breadcrumb</a></li>
                                <li><a href="cards.html">Cards</a></li>
                                <li><a href="carousel.html">Carousel</a></li>
                                <li><a href="dropdowns.html">Dropdowns</a></li>
                                <li><a href="grid.html">Grid</a></li>
                                <li><a href="images.html">Images</a></li>
                                <li><a href="lightbox.html">Lightbox</a></li>
                                <li><a href="media.html">Media</a></li>
                                <li><a href="modal.html">Modals</a></li>
                                <li><a href="offcanvas.html">Offcanvas</a></li>
                                <li><a href="pagination.html">Pagination</a></li>
                                <li><a href="popover.html">Popover</a></li>
                                <li><a href="progress.html">Progress Bars</a></li>
                                <li><a href="placeholders.html">Placeholders</a></li>
                                <li><a href="rangeslider.html">Range Slider</a></li>
                                <li><a href="spinners.html">Spinner</a></li>
                                <li><a href="sweetalerts.html">Sweet Alerts</a></li>
                                <li><a href="tab.html">Tabs</a></li>
                                <li><a href="toastr.html">Toasts</a></li>
                                <li><a href="tooltip.html">Tooltip</a></li>
                                <li><a href="typography.html">Typography</a></li>
                                <li><a href="video.html">Video</a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-elements" role="tabpanel" aria-labelledby="v-pills-elements-tab">
                            <p>Elements</p>
                            <ul>
                                <li><a href="ribbon.html">Ribbon</a></li>
                                <li><a href="clipboard.html">Clipboard</a></li>
                                <li><a href="drag-drop.html">Drag & Drop</a></li>
                                <li><a href="rating.html">Rating</a></li>
                                <li><a href="text-editor.html">Text Editor</a></li>
                                <li><a href="counter.html">Counter</a></li>
                                <li><a href="scrollbar.html">Scrollbar</a></li>
                                <li><a href="notification.html">Notification</a></li>
                                <li><a href="stickynote.html">Sticky Note</a></li>
                                <li><a href="timeline.html">Timeline</a></li>
                                <li>
                                    <a href="horizontal-timeline.html">Horizontal Timeline</a>
                                </li>
                                <li><a href="form-wizard.html">Form Wizard</a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-charts" role="tabpanel" aria-labelledby="v-pills-charts-tab">
                            <p>Charts</p>
                            <ul>
                                <li><a href="chart-apex.html">Apex Charts</a></li>
                                <li><a href="chart-js.html">Chart Js</a></li>
                                <li><a href="chart-morris.html">Morris Charts</a></li>
                                <li><a href="chart-flot.html">Flot Charts</a></li>
                                <li><a href="chart-peity.html">Peity Charts</a></li>
                                <li><a href="chart-c3.html">C3 Charts</a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-icons" role="tabpanel" aria-labelledby="v-pills-icons-tab">
                            <p>Icons</p>
                            <ul>
                                <li><a href="icon-fontawesome.html">Fontawesome Icons</a></li>
                                <li><a href="icon-feather.html">Feather Icons</a></li>
                                <li><a href="icon-ionic.html">Ionic Icons</a></li>
                                <li><a href="icon-material.html">Material Icons</a></li>
                                <li><a href="icon-pe7.html">Pe7 Icons</a></li>
                                <li><a href="icon-simpleline.html">Simpleline Icons</a></li>
                                <li><a href="icon-themify.html">Themify Icons</a></li>
                                <li><a href="icon-weather.html">Weather Icons</a></li>
                                <li><a href="icon-typicon.html">Typicon Icons</a></li>
                                <li><a href="icon-flag.html">Flag Icons</a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-forms" role="tabpanel" aria-labelledby="v-pills-forms-tab">
                            <p>Forms</p>
                            <ul>
                                <li><a href="form-basic-inputs.html">Basic Inputs </a></li>
                                <li><a href="form-input-groups.html">Input Groups </a></li>
                                <li><a href="form-horizontal.html">Horizontal Form </a></li>
                                <li><a href="form-vertical.html"> Vertical Form </a></li>
                                <li><a href="form-mask.html"> Form Mask </a></li>
                                <li><a href="form-validation.html"> Form Validation </a></li>
                                <li><a href="form-select2.html">Form Select2 </a></li>
                                <li><a href="form-fileupload.html">File Upload </a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-tables" role="tabpanel" aria-labelledby="v-pills-tables-tab">
                            <p>Tables</p>
                            <ul>
                                <li><a href="tables-basic.html">Basic Tables </a></li>
                                <li><a href="data-tables.html">Data Table </a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-documentation" role="tabpanel" aria-labelledby="v-pills-documentation-tab">
                            <p>Documentation</p>
                            <ul>
                                <li><a href="#">Documentation </a></li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-changelog" role="tabpanel" aria-labelledby="v-pills-changelog-tab">
                            <p>Change Log</p>
                            <ul>
                                <li>
                                    <a href="#"><span>Change Log</span>
                                        <span class="badge badge-primary ms-auto">v3.4</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="v-pills-multilevel" role="tabpanel" aria-labelledby="v-pills-multilevel-tab">
                            <p>Multi Level</p>
                            <ul>
                                <li class="sub-menu">
                                    <a href="javascript:void(0);">Level 1 <span class="menu-arrow"></span></a>
                                    <ul class="ms-3">
                                        <li class="sub-menu">
                                            <a href="javascript:void(0);">Level 1 <span class="menu-arrow"></span></a>
                                            <ul>
                                                <li><a href="javascript:void(0);">Level 2</a></li>
                                                <li><a href="javascript:void(0);">Level 3</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="javascript:void(0);">Level 2</a></li>
                                <li><a href="javascript:void(0);">Level 3</a></li>
                            </ul>
                        </div>
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                    </div>
                </div>
            </div>
        </div>
<<<<<<< HEAD
    </div>
    <!-- /Two Col Sidebar -->

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Selamat datang <b>Admin</b> <?php echo $userNameDisplay; ?>!</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Awal baris untuk widget -->
            <div class="row">
                <!-- Widget Projek -->
                <div class="col-xl-3 col-md-6 col-sm-6">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <span class="dash-widget-icon"><i class="fa-solid fa-cubes"></i></span>
                            <div class="dash-widget-info">
                                <h3><?php echo $totalProjects; ?></h3>
                                <span>Projects</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Widget Klien -->
                <div class="col-xl-3 col-md-6 col-sm-6">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <span class="dash-widget-icon"><i class="fa-solid fa-dollar-sign"></i></span>
                            <div class="dash-widget-info">
                                <h3><?php echo $totalClients; ?></h3>
                                <span>Clients</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Widget Tugas -->
                <div class="col-xl-3 col-md-6 col-sm-6">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <span class="dash-widget-icon"><i class="fa-regular fa-gem"></i></span>
                            <div class="dash-widget-info">
                                <h3><?php echo $totalTasks; ?></h3>
                                <span>Tasks</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Widget Pegawai -->
                <div class="col-xl-3 col-md-6 col-sm-6">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <span class="dash-widget-icon"><i class="fa-solid fa-user"></i></span>
                            <div class="dash-widget-info">
                                <h3><?php echo $totalEmployees; ?></h3>
                                <span>Employees</span>
=======
        <!-- /Two Col Sidebar -->

        <!-- Page Wrapper -->
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title">Selamat datang <b>Admin</b> <?php echo $userNameDisplay; ?>!</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Awal baris untuk widget -->
                <div class="row">
                    <!-- Widget Projek -->
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fa-solid fa-cubes"></i></span>
                                <div class="dash-widget-info">
                                    <h3><?php echo $totalProjects; ?></h3>
                                    <span>Projects</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Widget Klien -->
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fa-solid fa-dollar-sign"></i></span>
                                <div class="dash-widget-info">
                                    <h3><?php echo $totalClients; ?></h3>
                                    <span>Clients</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Widget Tugas -->
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fa-regular fa-gem"></i></span>
                                <div class="dash-widget-info">
                                    <h3><?php echo $totalTasks; ?></h3>
                                    <span>Tasks</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Widget Pegawai -->
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fa-solid fa-user"></i></span>
                                <div class="dash-widget-info">
                                    <h3><?php echo $totalEmployees; ?></h3>
                                    <span>Employees</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Akhir baris untuk widget -->
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <!-- Kartu Total Pendapatan dengan lebar 6 kolom untuk medium ke atas -->
                        <div class="col-md-6 text-center">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">Total Pendapatan</h3>
                                    <div id="revenue_chart" style="height: 250px;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Kartu Ikhtisar Penjualan dengan lebar 6 kolom untuk medium ke atas -->
                        <div class="col-md-6 text-center">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">Ikhtisar Penjualan</h3>
                                    <div id="line-charts" style="height: 250px;"></div>
                                </div>
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<<<<<<< HEAD
            <!-- Akhir baris untuk widget -->
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <!-- Kartu Total Pendapatan dengan lebar 6 kolom untuk medium ke atas -->
                    <div class="col-md-6 text-center">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Total Pendapatan</h3>
                                <div id="revenue_chart" style="height: 250px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Ikhtisar Penjualan dengan lebar 6 kolom untuk medium ke atas -->
                    <div class="col-md-6 text-center">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Ikhtisar Penjualan</h3>
                                <div id="line-charts" style="height: 250px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="card-group mb-30">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <span class="d-block">Pendapatan</span>
                                </div>
                                <div>
                                    <span class="text-success"><?= round($growth, 1); ?>%</span>
                                </div>
                            </div>
                            <h3 class="mb-3">$<?= number_format($currentEarnings, 2); ?></h3>
                            <div class="progress height-five mb-2">
                                <div class="progress-bar bg-primary" style="width: <?= min(round($growth, 1), 100); ?>%"
                                     role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="mb-0">
                                Bulan Sebelumnya <span
                                        class="text-muted">$<?= number_format($previousEarnings, 2); ?></span>
                            </p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <span class="d-block">Biaya</span>
                                </div>
                                <div>
                                    <span class="text-danger"><?= round($persentase_penurunan, 1) ?>%</span>
                                </div>
                            </div>
                            <h3 class="mb-3">$<?= number_format($biaya_bulan_ini, 2) ?></h3>
                            <div class="progress height-five mb-2">
                                <div class="progress-bar bg-primary"
                                     style="width: <?= min(100 - round(abs($persentase_penurunan), 1), 100) ?>%"
                                     role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="mb-0">Bulan Lalu <span
                                        class="text-muted">$<?= number_format($biaya_bulan_sebelum, 2) ?></span></p>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <span class="d-block">Keuntungan</span>
                                </div>
                                <div>
                                    <span class="text-danger">-75%</span>
                                </div>
                            </div>
                            <h3 class="mb-3">$<?= number_format(112000, 2); ?></h3>
                            <div class="progress height-five mb-2">
                                <div class="progress-bar bg-primary w-70" role="progressbar" aria-valuenow="40"
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="mb-0">
                                Bulan Sebelumnya <span class="text-muted">$<?= number_format(142000, 2); ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Widget -->
        <div class="row">
            <div class="col-md-12 col-lg-12 col-xl-4 d-flex">
                <div class="card flex-fill dash-statistics">
                    <div class="card-body">
                        <h5 class="card-title">Statistik</h5>
                        <div class="stats-list">
                            <div class="stats-info">
                                <p>Cuti Hari Ini <strong><?php echo $todayLeave; ?>
                                        <small>/ <?php echo $totalLeave; ?></small></strong></p>
                                <div class="progress">
                                    <?php
                                    $progress = ($totalLeave > 0) ? ($todayLeave / $totalLeave) * 100 : 0;
                                    ?>
                                    <div class="progress-bar bg-primary"
                                         style="width: <?php echo number_format($progress, 2); ?>%" role="progressbar"
                                         aria-valuenow="<?php echo number_format($progress, 2); ?>" aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="stats-info">
                                <p>Faktur Tertunda <strong><?php echo $pendingInvoices; ?>
                                        <small>/ <?php echo $totalInvoices; ?></small></strong></p>
                                <div class="progress">
                                    <?php
                                    $width = ($totalInvoices > 0) ? ($pendingInvoices / $totalInvoices) * 100 : 0;
                                    ?>
                                    <div class="progress-bar bg-warning"
                                         style="width: <?php echo number_format($width, 2); ?>%" role="progressbar"
                                         aria-valuenow="<?php echo number_format($width, 2); ?>" aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="stats-info">
                                <p>Proyek Selesai <strong><?php echo $jumlahSelesai; ?>
                                        <small>/ <?php echo $totalProyek; ?></small></strong></p>
                                <div class="progress">
                                    <div class="progress-bar bg-success"
                                         style="width: <?php echo ($totalProyek > 0) ? (number_format(($jumlahSelesai / $totalProyek) * 100, 2)) : 0; ?>%;"
                                         role="progressbar"
                                         aria-valuenow="<?php echo ($totalProyek > 0) ? (number_format(($jumlahSelesai / $totalProyek) * 100, 2)) : 0; ?>"
                                         aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="stats-info">
                                <p>Tiket Terbuka <strong><?php echo $tiketTerbuka; ?>
                                        <small>/ <?php echo $totalTiket; ?></small></strong></p>
                                <div class="progress">
                                    <?php
                                    $width = ($totalTiket > 0) ? ($tiketTerbuka / $totalTiket) * 100 : 0;
                                    ?>
                                    <div class="progress-bar bg-danger"
                                         style="width: <?php echo number_format($width, 2); ?>%;" role="progressbar"
                                         aria-valuenow="<?php echo number_format($width, 2); ?>" aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="stats-info">
                                <p>Tiket Tertutup <strong><?php echo $tiketTertutup; ?> <small>/ 212</small></strong>
                                </p>
                                <div class="progress">
                                    <?php
                                    $persentase = (212 > 0) ? ($tiketTertutup / 212) * 100 : 0;
                                    ?>
                                    <div class="progress-bar bg-info"
                                         style="width: <?php echo number_format($persentase, 2); ?>%;"
                                         role="progressbar" aria-valuenow="<?php echo number_format($persentase, 2); ?>"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-12 col-lg-6 col-xl-4 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <h4 class="card-title">Task Statistik</h4>
                        <div class="statistics">
                            <div class="row">
                                <div class="col-md-6 col-6 text-center">
                                    <div class="stats-box mb-4">
                                        <p>Total Tugas</p>
                                        <h3><?php echo $totalTasks; ?></h3>
                                    </div>
                                </div>
                                <div class="col-md-6 col-6 text-center">
                                    <div class="stats-box mb-4">
                                        <p>Overdue Tasks</p>
                                        <h3><?php echo $jumlah_overdue; ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="progress mb-4">
                            <?php foreach ($progressData as $statusId => $data) : ?>
                                <?php
                                // Menentukan warna berdasarkan status_id
                                $warna = '';
                                switch ($statusId) {
                                    case 1:
                                        $warna = 'bg-purple'; // Untuk Completed
                                        break;
                                    case 2:
                                        $warna = 'bg-warning'; // Untuk Inprogress
                                        break;
                                    case 3:
                                        $warna = 'bg-success'; // Untuk On Hold
                                        break;
                                    case 4:
                                        $warna = 'bg-danger'; // Untuk Pending
                                        break;
                                    case 5:
                                        $warna = 'bg-info'; // Untuk Review
                                        break;
                                }
                                ?>

                                <div class="progress-bar <?= $warna; ?>" role="progressbar"
                                     style="width: <?= number_format($data['persentase'], 2); ?>%"
                                     aria-valuenow="<?= $data['persentase']; ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?= number_format($data['persentase'], 2); ?>%
                                </div>

                            <?php endforeach; ?>

                        </div>
                        <p>
                            <i class="fa-regular fa-circle-dot text-purple me-2"></i>Completed Tasks <span
                                    class="float-end"><?= $completedCount; ?></span>
                        </p>
                        <p>
                            <i class="fa-regular fa-circle-dot text-warning me-2"></i>Inprogress Tasks <span
                                    class="float-end"><?= $inprogressCount; ?></span>
                        </p>
                        <p>
                            <i class="fa-regular fa-circle-dot text-success me-2"></i>On Hold Tasks <span
                                    class="float-end"><?= $onHoldCount; ?></span>
                        </p>
                        <p>
                            <i class="fa-regular fa-circle-dot text-danger me-2"></i>Pending Tasks <span
                                    class="float-end"><?= $pendingCount; ?></span>
                        </p>
                        <p class="mb-0">
                            <i class="fa-regular fa-circle-dot text-info me-2"></i>Review Tasks <span
                                    class="float-end"><?= $reviewCount; ?></span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-6 col-xl-4 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <h4 class="card-title">
                            Today Absent
                            <span class="badge bg-inverse-danger ms-2">COMING SOON!</span>
                        </h4>
                        <!-- <div class="leave-info-box">
                            <div class="media d-flex align-items-center">
                                <a href="profile.html" class="avatar"><img src="assets/img/user.jpg" alt="User Image" /></a>
                                <div class="media-body flex-grow-1">
                                    <div class="text-sm my-0">Martin Lewis</div>
                                </div>
                            </div>
                            <div class="row align-items-center mt-3">
                                <div class="col-6">
                                    <h6 class="mb-0">4 Sep 2019</h6>
                                    <span class="text-sm text-muted">Leave Date</span>
                                </div>
                                <div class="col-6 text-end">
                                    <span class="badge bg-inverse-danger">Pending</span>
                                </div>
                            </div>
                        </div>

                        <div class="leave-info-box">
                            <div class="media d-flex align-items-center">
                                <a href="profile.html" class="avatar"><img src="assets/img/user.jpg" alt="User Image" /></a>
                                <div class="media-body flex-grow-1">
                                    <div class="text-sm my-0">Martin Lewis</div>
                                </div>
                            </div>
                            <div class="row align-items-center mt-3">
                                <div class="col-6">
                                    <h6 class="mb-0">4 Sep 2019</h6>
                                    <span class="text-sm text-muted">Leave Date</span>
                                </div>
                                <div class="col-6 text-end">
                                    <span class="badge bg-inverse-success">Approved</span>
                                </div>
                            </div>
                        </div>
                        <div class="load-more text-center">
                            <a class="text-dark" href="javascript:void(0);">Load More</a>
                        </div> -->
                        <h1>COMING SOON!</h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Statistics Widget -->

        <div class="row">
            <div class="col-md-6 d-flex">
                <div class="card card-table flex-fill">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Invoices</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-nowrap custom-table mb-0">
                                <thead>
                                <tr>
                                    <th>Invoice ID</th>
                                    <th>Client</th>
                                    <th>Due Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($invoices as $invoice) : ?>
                                    <tr>
                                        <td><a href="invoice-view.html">#INV-<?= $invoice['id']; ?></a></td>
                                        <td>
                                            <h2><a href="#">Nama Client Disini</a></h2>
                                        </td>
                                        <td><?= $invoice['due_date']; ?></td>
                                        <td>$<?= $invoice['amount_due']; ?></td>
                                        <td>
                                                    <span class="badge <?= $invoice['is_paid'] ? 'bg-inverse-success' : 'bg-inverse-warning'; ?>">
                                                        <?= $invoice['is_paid'] ? 'Dibayar' : 'Belum Bayar'; ?>
                                                    </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="invoices.html">View all invoices</a>
                    </div>
                </div>
            </div>
            <!-- Bagian untuk pembayaran bisa ditambahkan di sini mirip dengan bagian invoice -->
        </div>

        <div class="row">
            <div class="col-md-6 d-flex">
                <div class="card card-table flex-fill">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Clients</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table custom-table mb-0">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($clients as $client) : ?>
                                    <tr>
                                        <td>
                                            <h2 class="table-avatar">
                                                <!-- <a href="#" class="avatar"><img src="assets/img/profiles/avatar-default.jpg" alt="User Image" /></a> -->
                                                <a href="client-profile.html"><?php echo htmlspecialchars($client['client_name']); ?>
                                                    <span></span></a>
                                            </h2>
                                        </td>
                                        <td><?php echo htmlspecialchars($client['email']); ?></td>
                                        <!-- ... (HTML sebelumnya) ... -->
                                        <td>
                                            <div class="dropdown action-label">
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                                   data-bs-toggle="dropdown" aria-expanded="false"
                                                   id="status-<?= $client['client_id']; ?>">
                                                    <i class="fa-regular fa-circle-dot <?= $client['status'] ? 'text-success' : 'text-danger'; ?>"></i>
                                                    <?= $client['status'] ? 'Aktif' : 'Nonaktif'; ?>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#"
                                                       onclick="updateClientStatus(<?= $client['client_id']; ?>, '1');">
                                                        <i class="fa-regular fa-circle-dot text-success"></i> Aktif
                                                    </a>
                                                    <a class="dropdown-item" href="#"
                                                       onclick="updateClientStatus(<?= $client['client_id']; ?>, '0');">
                                                        <i class="fa-regular fa-circle-dot text-danger"></i> Nonaktif
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <!-- ... (HTML setelahnya) ... -->

                                        <td class="text-end">
                                            <!-- Dropdown Action -->
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="clients.html">View all clients</a>
                    </div>
                </div>
            </div>
            <!-- ... bagian awal HTML dan head ... -->

            <div class="col-md-6 d-flex">
                <div class="card card-table flex-fill">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Recent Projects</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table custom-table mb-0">
                                <thead>
                                <tr>
                                    <th>Project Name</th>
                                    <th>Progress</th>
                                    <th class="text-end">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- Blok PHP untuk mengisi tabel dengan data -->
                                <?php foreach ($projects as $project): ?>
                                    <?php
                                    $progress = $project['tasks_total'] > 0 ? round(($project['tasks_completed'] / $project['tasks_total']) * 100) : 0;
                                    $openTasks = $project['tasks_total'] - $project['tasks_completed'];
                                    ?>
                                    <tr>
                                        <td>
                                            <h2>
                                                <a href="project-view.html"><?php echo htmlspecialchars($project['name']); ?></a>
                                            </h2>
                                            <small class="block text-ellipsis">
                                                <span><?php echo $openTasks; ?></span>
                                                <span class="text-muted">open tasks left, </span>
                                                <span><?php echo $project['tasks_completed']; ?></span>
                                                <span class="text-muted">tasks completed</span>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="progress progress-xs progress-striped">
                                                <div class="progress-bar" style="width:<?php echo $progress; ?>%"
                                                     role="progressbar" data-bs-toggle="tooltip"
                                                     title="<?php echo $progress; ?>%"></div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle"
                                                   data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="material-icons">more_vert</i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="javascript:void(0)">
                                                        <i class="fa-solid fa-pencil m-r-5"></i>Edit</a>
                                                    <a class="dropdown-item" href="javascript:void(0)">
                                                        <i class="fa-regular fa-trash-can m-r-5"></i>Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="projects.html">View all projects</a>
                    </div>
                </div>
            </div>
        </div> <!-- Akhir dari row -->
        <!-- ... bagian akhir HTML ... -->

    </div>
    <!-- /Page Content -->
</div>
<!-- /Page Wrapper -->
</div>
<!-- /Main Wrapper -->

<div class="settings-icon">
    <span data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas"
          aria-controls="theme-settings-offcanvas"><i class="las la-cog"></i></span>
</div>
<div class="offcanvas offcanvas-end border-0" tabindex="-1" id="theme-settings-offcanvas">
    <div class="sidebar-headerset">
        <div class="sidebar-headersets">
            <h2>Customizer</h2>
            <h3>Customize your overview Page layout</h3>
        </div>
        <div class="sidebar-headerclose">
            <a data-bs-dismiss="offcanvas" aria-label="Close"><img src="assets/img/close.png" alt="Close Icon"/></a>
        </div>
    </div>
    <div class="offcanvas-body p-0">
        <div data-simplebar class="h-100">
            <div class="settings-mains">
                <div class="layout-head">
                    <h5>Layout</h5>
                    <h6>Choose your layout</h6>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-check card-radio p-0">
                            <input id="customizer-layout01" name="data-layout" type="radio" value="vertical"
                                   class="form-check-input"/>
                            <label class="form-check-label avatar-md w-100" for="customizer-layout01">
                                <img src="assets/img/vertical.png" alt="Layout Image"/>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Vertical</h5>
                    </div>
                    <div class="col-4">
                        <div class="form-check card-radio p-0">
                            <input id="customizer-layout02" name="data-layout" type="radio" value="horizontal"
                                   class="form-check-input"/>
                            <label class="form-check-label avatar-md w-100" for="customizer-layout02">
                                <img src="assets/img/horizontal.png" alt="Layout Image"/>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Horizontal</h5>
                    </div>
                    <div class="col-4">
                        <div class="form-check card-radio p-0">
                            <input id="customizer-layout03" name="data-layout" type="radio" value="twocolumn"
                                   class="form-check-input"/>
                            <label class="form-check-label avatar-md w-100" for="customizer-layout03">
                                <img src="assets/img/two-col.png" alt="Layout Image"/>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Two Column</h5>
                    </div>
                </div>
                <div class="layout-head pt-3">
                    <h5>Color Scheme</h5>
                    <h6>Choose Light or Dark Scheme.</h6>
                </div>
                <div class="colorscheme-cardradio">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-check card-radio p-0">
                                <input class="form-check-input" type="radio" name="data-layout-mode"
                                       id="layout-mode-orange" value="orange"/>
                                <label class="form-check-label avatar-md w-100" for="layout-mode-orange">
                                    <img src="assets/img/vertical.png" alt="Layout Image"/>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Orange</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio p-0">
                                <input class="form-check-input" type="radio" name="data-layout-mode"
                                       id="layout-mode-light" value="light"/>
                                <label class="form-check-label avatar-md w-100" for="layout-mode-light">
                                    <img src="assets/img/vertical.png" alt="Layout Image"/>
=======


            <div class="row">
                <div class="col-md-12">
                    <div class="card-group mb-30">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <span class="d-block">Pendapatan</span>
                                    </div>
                                    <div>
                                        <span class="text-success"><?= round($growth, 1); ?>%</span>
                                    </div>
                                </div>
                                <h3 class="mb-3">$<?= number_format($currentEarnings, 2); ?></h3>
                                <div class="progress height-five mb-2">
                                    <div class="progress-bar bg-primary" style="width: <?= min(round($growth, 1), 100); ?>%" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mb-0">
                                    Bulan Sebelumnya <span class="text-muted">$<?= number_format($previousEarnings, 2); ?></span>
                                </p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <span class="d-block">Biaya</span>
                                    </div>
                                    <div>
                                        <span class="text-danger"><?= round($persentase_penurunan, 1) ?>%</span>
                                    </div>
                                </div>
                                <h3 class="mb-3">$<?= number_format($biaya_bulan_ini, 2) ?></h3>
                                <div class="progress height-five mb-2">
                                    <div class="progress-bar bg-primary" style="width: <?= min(100 - round(abs($persentase_penurunan), 1), 100) ?>%" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mb-0">Bulan Lalu <span class="text-muted">$<?= number_format($biaya_bulan_sebelum, 2) ?></span></p>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <span class="d-block">Keuntungan</span>
                                    </div>
                                    <div>
                                        <span class="text-danger">-75%</span>
                                    </div>
                                </div>
                                <h3 class="mb-3">$<?= number_format(112000, 2); ?></h3>
                                <div class="progress height-five mb-2">
                                    <div class="progress-bar bg-primary w-70" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mb-0">
                                    Bulan Sebelumnya <span class="text-muted">$<?= number_format(142000, 2); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Widget -->
            <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-4 d-flex">
                    <div class="card flex-fill dash-statistics">
                        <div class="card-body">
                            <h5 class="card-title">Statistik</h5>
                            <div class="stats-list">
                                <div class="stats-info">
                                    <p>Cuti Hari Ini <strong><?php echo $todayLeave; ?> <small>/ <?php echo $totalLeave; ?></small></strong></p>
                                    <div class="progress">
                                        <?php
                                        $progress = ($totalLeave > 0) ? ($todayLeave / $totalLeave) * 100 : 0;
                                        ?>
                                        <div class="progress-bar bg-primary" style="width: <?php echo number_format($progress, 2); ?>%" role="progressbar" aria-valuenow="<?php echo number_format($progress, 2); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Faktur Tertunda <strong><?php echo $pendingInvoices; ?> <small>/ <?php echo $totalInvoices; ?></small></strong></p>
                                    <div class="progress">
                                        <?php
                                        $width = ($totalInvoices > 0) ? ($pendingInvoices / $totalInvoices) * 100 : 0;
                                        ?>
                                        <div class="progress-bar bg-warning" style="width: <?php echo number_format($width, 2); ?>%" role="progressbar" aria-valuenow="<?php echo number_format($width, 2); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Proyek Selesai <strong><?php echo $proyekSelesai; ?> <small>/ <?php echo $totalProyek; ?></small></strong></p>
                                    <div class="progress">
                                        <?php
                                        $width = ($totalProyek > 0) ? ($proyekSelesai / $totalProyek) * 100 : 0;
                                        ?>
                                        <div class="progress-bar bg-success" style="width: <?php echo number_format($width, 2); ?>%;" role="progressbar" aria-valuenow="<?php echo number_format($width, 2); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Tiket Terbuka <strong><?php echo $tiketTerbuka; ?> <small>/ <?php echo $totalTiket; ?></small></strong></p>
                                    <div class="progress">
                                        <?php
                                        $width = ($totalTiket > 0) ? ($tiketTerbuka / $totalTiket) * 100 : 0;
                                        ?>
                                        <div class="progress-bar bg-danger" style="width: <?php echo number_format($width, 2); ?>%;" role="progressbar" aria-valuenow="<?php echo number_format($width, 2); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Tiket Tertutup <strong><?php echo $tiketTertutup; ?> <small>/ 212</small></strong></p>
                                    <div class="progress">
                                        <?php
                                        $persentase = (212 > 0) ? ($tiketTertutup / 212) * 100 : 0;
                                        ?>
                                        <div class="progress-bar bg-info" style="width: <?php echo number_format($persentase, 2); ?>%;" role="progressbar" aria-valuenow="<?php echo number_format($persentase, 2); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-12 col-lg-6 col-xl-4 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <h4 class="card-title">Task Statistik</h4>
                            <div class="statistics">
                                <div class="row">
                                    <div class="col-md-6 col-6 text-center">
                                        <div class="stats-box mb-4">
                                            <p>Total Tugas</p>
                                            <h3><?php echo $totalTasks; ?></h3>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-6 text-center">
                                        <div class="stats-box mb-4">
                                            <p>Overdue Tasks</p>
                                            <h3><?php echo $jumlah_overdue; ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="progress mb-4">
                                <?php foreach ($progressData as $statusId => $data) : ?>
                                    <?php
                                    // Menentukan warna berdasarkan status_id
                                    $warna = '';
                                    switch ($statusId) {
                                        case 1:
                                            $warna = 'bg-purple'; // Untuk Completed
                                            break;
                                        case 2:
                                            $warna = 'bg-warning'; // Untuk Inprogress
                                            break;
                                        case 3:
                                            $warna = 'bg-success'; // Untuk On Hold
                                            break;
                                        case 4:
                                            $warna = 'bg-danger'; // Untuk Pending
                                            break;
                                        case 5:
                                            $warna = 'bg-info'; // Untuk Review
                                            break;
                                    }
                                    ?>

                                    <div class="progress-bar <?= $warna; ?>" role="progressbar" style="width: <?= number_format($data['persentase'], 2); ?>%" aria-valuenow="<?= $data['persentase']; ?>" aria-valuemin="0" aria-valuemax="100">
                                        <?= number_format($data['persentase'], 2); ?>%
                                    </div>

                                <?php endforeach; ?>

                            </div>
                            <p>
                                <i class="fa-regular fa-circle-dot text-purple me-2"></i>Completed Tasks <span class="float-end"><?= $completedCount; ?></span>
                            </p>
                            <p>
                                <i class="fa-regular fa-circle-dot text-warning me-2"></i>Inprogress Tasks <span class="float-end"><?= $inprogressCount; ?></span>
                            </p>
                            <p>
                                <i class="fa-regular fa-circle-dot text-success me-2"></i>On Hold Tasks <span class="float-end"><?= $onHoldCount; ?></span>
                            </p>
                            <p>
                                <i class="fa-regular fa-circle-dot text-danger me-2"></i>Pending Tasks <span class="float-end"><?= $pendingCount; ?></span>
                            </p>
                            <p class="mb-0">
                                <i class="fa-regular fa-circle-dot text-info me-2"></i>Review Tasks <span class="float-end"><?= $reviewCount; ?></span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-lg-6 col-xl-4 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <h4 class="card-title">
                                Today Absent
                                <span class="badge bg-inverse-danger ms-2">COMING SOON!</span>
                            </h4>
                            <!-- <div class="leave-info-box">
                                <div class="media d-flex align-items-center">
                                    <a href="profile.html" class="avatar"><img src="assets/img/user.jpg" alt="User Image" /></a>
                                    <div class="media-body flex-grow-1">
                                        <div class="text-sm my-0">Martin Lewis</div>
                                    </div>
                                </div>
                                <div class="row align-items-center mt-3">
                                    <div class="col-6">
                                        <h6 class="mb-0">4 Sep 2019</h6>
                                        <span class="text-sm text-muted">Leave Date</span>
                                    </div>
                                    <div class="col-6 text-end">
                                        <span class="badge bg-inverse-danger">Pending</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="leave-info-box">
                                <div class="media d-flex align-items-center">
                                    <a href="profile.html" class="avatar"><img src="assets/img/user.jpg" alt="User Image" /></a>
                                    <div class="media-body flex-grow-1">
                                        <div class="text-sm my-0">Martin Lewis</div>
                                    </div>
                                </div>
                                <div class="row align-items-center mt-3">
                                    <div class="col-6">
                                        <h6 class="mb-0">4 Sep 2019</h6>
                                        <span class="text-sm text-muted">Leave Date</span>
                                    </div>
                                    <div class="col-6 text-end">
                                        <span class="badge bg-inverse-success">Approved</span>
                                    </div>
                                </div>
                            </div>
                            <div class="load-more text-center">
                                <a class="text-dark" href="javascript:void(0);">Load More</a>
                            </div> -->
                            <h1>COMING SOON!</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Statistics Widget -->

            <div class="row">
                <div class="col-md-6 d-flex">
                    <div class="card card-table flex-fill">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Invoices</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-nowrap custom-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Invoice ID</th>
                                            <th>Client</th>
                                            <th>Due Date</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($invoices as $invoice) : ?>
                                            <tr>
                                                <td><a href="invoice-view.html">#INV-<?= $invoice['id']; ?></a></td>
                                                <td>
                                                    <h2><a href="#">Nama Client Disini</a></h2>
                                                </td>
                                                <td><?= $invoice['due_date']; ?></td>
                                                <td>$<?= $invoice['amount_due']; ?></td>
                                                <td>
                                                    <span class="badge <?= $invoice['is_paid'] ? 'bg-inverse-success' : 'bg-inverse-warning'; ?>">
                                                        <?= $invoice['is_paid'] ? 'Dibayar' : 'Belum Bayar'; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="invoices.html">View all invoices</a>
                        </div>
                    </div>
                </div>
                <!-- Bagian untuk pembayaran bisa ditambahkan di sini mirip dengan bagian invoice -->
            </div>

            <div class="row">
                <div class="col-md-6 d-flex">
                    <div class="card card-table flex-fill">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Clients</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($clients as $client) : ?>
                                            <tr>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <!-- <a href="#" class="avatar"><img src="assets/img/profiles/avatar-default.jpg" alt="User Image" /></a> -->
                                                        <a href="client-profile.html"><?php echo htmlspecialchars($client['client_name']); ?><span></span></a>
                                                    </h2>
                                                </td>
                                                <td><?php echo htmlspecialchars($client['email']); ?></td>
                                                <!-- ... (HTML sebelumnya) ... -->
                                                <td>
                                                    <div class="dropdown action-label">
                                                        <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false" id="status-<?= $client['client_id']; ?>">
                                                            <i class="fa-regular fa-circle-dot <?= $client['status'] ? 'text-success' : 'text-danger'; ?>"></i>
                                                            <?= $client['status'] ? 'Aktif' : 'Nonaktif'; ?>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="#" onclick="updateClientStatus(<?= $client['client_id']; ?>, '1');">
                                                                <i class="fa-regular fa-circle-dot text-success"></i> Aktif
                                                            </a>
                                                            <a class="dropdown-item" href="#" onclick="updateClientStatus(<?= $client['client_id']; ?>, '0');">
                                                                <i class="fa-regular fa-circle-dot text-danger"></i> Nonaktif
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <!-- ... (HTML setelahnya) ... -->

                                                <td class="text-end">
                                                    <!-- Dropdown Action -->
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="clients.html">View all clients</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-flex">
                    <div class="card card-table flex-fill">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Recent Projects</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Project Name</th>
                                            <th>Progress</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <h2>
                                                    <a href="project-view.html">Office Management</a>
                                                </h2>
                                                <small class="block text-ellipsis">
                                                    <span>1</span>
                                                    <span class="text-muted">open tasks, </span>
                                                    <span>9</span>
                                                    <span class="text-muted">tasks completed</span>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="progress progress-xs progress-striped">
                                                    <div class="progress-bar w-65" role="progressbar" data-bs-toggle="tooltip" title="65%"></div>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="javascript:void(0)"><i class="fa-solid fa-pencil m-r-5"></i>
                                                            Edit</a>
                                                        <a class="dropdown-item" href="javascript:void(0)"><i class="fa-regular fa-trash-can m-r-5"></i>
                                                            Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h2>
                                                    <a href="project-view.html">Project Management</a>
                                                </h2>
                                                <small class="block text-ellipsis">
                                                    <span>2</span>
                                                    <span class="text-muted">open tasks, </span>
                                                    <span>5</span>
                                                    <span class="text-muted">tasks completed</span>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="progress progress-xs progress-striped">
                                                    <div class="progress-bar w-15" role="progressbar" data-bs-toggle="tooltip" title="15%"></div>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="javascript:void(0)"><i class="fa-solid fa-pencil m-r-5"></i>
                                                            Edit</a>
                                                        <a class="dropdown-item" href="javascript:void(0)"><i class="fa-regular fa-trash-can m-r-5"></i>
                                                            Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h2>
                                                    <a href="project-view.html">Video Calling App</a>
                                                </h2>
                                                <small class="block text-ellipsis">
                                                    <span>3</span>
                                                    <span class="text-muted">open tasks, </span>
                                                    <span>3</span>
                                                    <span class="text-muted">tasks completed</span>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="progress progress-xs progress-striped">
                                                    <div class="progress-bar w-50" role="progressbar" data-bs-toggle="tooltip" title="50%"></div>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="javascript:void(0)"><i class="fa-solid fa-pencil m-r-5"></i>
                                                            Edit</a>
                                                        <a class="dropdown-item" href="javascript:void(0)"><i class="fa-regular fa-trash-can m-r-5"></i>
                                                            Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h2>
                                                    <a href="project-view.html">Hospital Administration</a>
                                                </h2>
                                                <small class="block text-ellipsis">
                                                    <span>12</span>
                                                    <span class="text-muted">open tasks, </span>
                                                    <span>4</span>
                                                    <span class="text-muted">tasks completed</span>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="progress progress-xs progress-striped">
                                                    <div class="progress-bar w-88" role="progressbar" data-bs-toggle="tooltip" title="88%"></div>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="javascript:void(0)"><i class="fa-solid fa-pencil m-r-5"></i>
                                                            Edit</a>
                                                        <a class="dropdown-item" href="javascript:void(0)"><i class="fa-regular fa-trash-can m-r-5"></i>
                                                            Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h2>
                                                    <a href="project-view.html">Digital Marketplace</a>
                                                </h2>
                                                <small class="block text-ellipsis">
                                                    <span>7</span>
                                                    <span class="text-muted">open tasks, </span>
                                                    <span>14</span>
                                                    <span class="text-muted">tasks completed</span>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="progress progress-xs progress-striped">
                                                    <div class="progress-bar w-100" role="progressbar" data-bs-toggle="tooltip" title="100%"></div>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="javascript:void(0)"><i class="fa-solid fa-pencil m-r-5"></i>
                                                            Edit</a>
                                                        <a class="dropdown-item" href="javascript:void(0)"><i class="fa-regular fa-trash-can m-r-5"></i>
                                                            Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="projects.html">View all projects</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
    </div>
    <!-- /Main Wrapper -->

    <div class="settings-icon">
        <span data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas"><i class="las la-cog"></i></span>
    </div>
    <div class="offcanvas offcanvas-end border-0" tabindex="-1" id="theme-settings-offcanvas">
        <div class="sidebar-headerset">
            <div class="sidebar-headersets">
                <h2>Customizer</h2>
                <h3>Customize your overview Page layout</h3>
            </div>
            <div class="sidebar-headerclose">
                <a data-bs-dismiss="offcanvas" aria-label="Close"><img src="assets/img/close.png" alt="Close Icon" /></a>
            </div>
        </div>
        <div class="offcanvas-body p-0">
            <div data-simplebar class="h-100">
                <div class="settings-mains">
                    <div class="layout-head">
                        <h5>Layout</h5>
                        <h6>Choose your layout</h6>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-check card-radio p-0">
                                <input id="customizer-layout01" name="data-layout" type="radio" value="vertical" class="form-check-input" />
                                <label class="form-check-label avatar-md w-100" for="customizer-layout01">
                                    <img src="assets/img/vertical.png" alt="Layout Image" />
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Vertical</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio p-0">
                                <input id="customizer-layout02" name="data-layout" type="radio" value="horizontal" class="form-check-input" />
                                <label class="form-check-label avatar-md w-100" for="customizer-layout02">
                                    <img src="assets/img/horizontal.png" alt="Layout Image" />
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Horizontal</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio p-0">
                                <input id="customizer-layout03" name="data-layout" type="radio" value="twocolumn" class="form-check-input" />
                                <label class="form-check-label avatar-md w-100" for="customizer-layout03">
                                    <img src="assets/img/two-col.png" alt="Layout Image" />
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Two Column</h5>
                        </div>
                    </div>
                    <div class="layout-head pt-3">
                        <h5>Color Scheme</h5>
                        <h6>Choose Light or Dark Scheme.</h6>
                    </div>
                    <div class="colorscheme-cardradio">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-check card-radio p-0">
                                    <input class="form-check-input" type="radio" name="data-layout-mode" id="layout-mode-orange" value="orange" />
                                    <label class="form-check-label avatar-md w-100" for="layout-mode-orange">
                                        <img src="assets/img/vertical.png" alt="Layout Image" />
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Orange</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check card-radio p-0">
                                    <input class="form-check-input" type="radio" name="data-layout-mode" id="layout-mode-light" value="light" />
                                    <label class="form-check-label avatar-md w-100" for="layout-mode-light">
                                        <img src="assets/img/vertical.png" alt="Layout Image" />
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Light</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check card-radio dark p-0">
                                    <input class="form-check-input" type="radio" name="data-layout-mode" id="layout-mode-dark" value="dark" />
                                    <label class="form-check-label avatar-md w-100" for="layout-mode-dark">
                                        <img src="assets/img/vertical.png" alt="Layout Image" />
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Dark</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check card-radio blue p-0">
                                    <input class="form-check-input" type="radio" name="data-layout-mode" id="layout-mode-blue" value="blue" />
                                    <label class="form-check-label avatar-md w-100" for="layout-mode-blue">
                                        <img src="assets/img/vertical.png" alt="Layout Image" />
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Blue</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check card-radio maroon p-0">
                                    <input class="form-check-input" type="radio" name="data-layout-mode" id="layout-mode-maroon" value="maroon" />
                                    <label class="form-check-label avatar-md w-100" for="layout-mode-maroon">
                                        <img src="assets/img/vertical.png" alt="Layout Image" />
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Maroon</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check card-radio purple p-0">
                                    <input class="form-check-input" type="radio" name="data-layout-mode" id="layout-mode-purple" value="purple" />
                                    <label class="form-check-label avatar-md w-100" for="layout-mode-purple">
                                        <img src="assets/img/vertical.png" alt="Layout Image" />
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Purple</h5>
                            </div>
                        </div>
                    </div>

                    <div id="layout-width">
                        <div class="layout-head pt-3">
                            <h5>Layout Width</h5>
                            <h6>Choose Fluid or Boxed layout.</h6>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-check card-radio p-0">
                                    <input class="form-check-input" type="radio" name="data-layout-width" id="layout-width-fluid" value="fluid" />
                                    <label class="form-check-label avatar-md w-100" for="layout-width-fluid">
                                        <img src="assets/img/vertical.png" alt="Layout Image" />
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Fluid</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check card-radio p-0">
                                    <input class="form-check-input" type="radio" name="data-layout-width" id="layout-width-boxed" value="boxed" />
                                    <label class="form-check-label avatar-md w-100 px-2" for="layout-width-boxed">
                                        <img src="assets/img/boxed.png" alt="Layout Image" />
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Boxed</h5>
                            </div>
                        </div>
                    </div>

                    <div id="layout-position">
                        <div class="layout-head pt-3">
                            <h5>Layout Position</h5>
                            <h6>Choose Fixed or Scrollable Layout Position.</h6>
                        </div>
                        <div class="btn-group bor-rad-50 overflow-hidden radio" role="group">
                            <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-fixed" value="fixed" />
                            <label class="btn btn-light w-sm" for="layout-position-fixed">Fixed</label>

                            <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-scrollable" value="scrollable" />
                            <label class="btn btn-light w-sm ms-0" for="layout-position-scrollable">Scrollable</label>
                        </div>
                    </div>
                    <div class="layout-head pt-3">
                        <h5>Topbar Color</h5>
                        <h6>Choose Light or Dark Topbar Color.</h6>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-check card-radio p-0">
                                <input class="form-check-input" type="radio" name="data-topbar" id="topbar-color-light" value="light" />
                                <label class="form-check-label avatar-md w-100" for="topbar-color-light">
                                    <img src="assets/img/vertical.png" alt="Layout Image" />
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Light</h5>
                        </div>
                        <div class="col-4">
<<<<<<< HEAD
                            <div class="form-check card-radio dark p-0">
                                <input class="form-check-input" type="radio" name="data-layout-mode"
                                       id="layout-mode-dark" value="dark"/>
                                <label class="form-check-label avatar-md w-100" for="layout-mode-dark">
                                    <img src="assets/img/vertical.png" alt="Layout Image"/>
=======
                            <div class="form-check card-radio p-0">
                                <input class="form-check-input" type="radio" name="data-topbar" id="topbar-color-dark" value="dark" />
                                <label class="form-check-label avatar-md w-100" for="topbar-color-dark">
                                    <img src="assets/img/dark.png" alt="Layout Image" />
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Dark</h5>
                        </div>
<<<<<<< HEAD
                        <div class="col-4">
                            <div class="form-check card-radio blue p-0">
                                <input class="form-check-input" type="radio" name="data-layout-mode"
                                       id="layout-mode-blue" value="blue"/>
                                <label class="form-check-label avatar-md w-100" for="layout-mode-blue">
                                    <img src="assets/img/vertical.png" alt="Layout Image"/>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Blue</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio maroon p-0">
                                <input class="form-check-input" type="radio" name="data-layout-mode"
                                       id="layout-mode-maroon" value="maroon"/>
                                <label class="form-check-label avatar-md w-100" for="layout-mode-maroon">
                                    <img src="assets/img/vertical.png" alt="Layout Image"/>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Maroon</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio purple p-0">
                                <input class="form-check-input" type="radio" name="data-layout-mode"
                                       id="layout-mode-purple" value="purple"/>
                                <label class="form-check-label avatar-md w-100" for="layout-mode-purple">
                                    <img src="assets/img/vertical.png" alt="Layout Image"/>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Purple</h5>
                        </div>
                    </div>
                </div>

                <div id="layout-width">
                    <div class="layout-head pt-3">
                        <h5>Layout Width</h5>
                        <h6>Choose Fluid or Boxed layout.</h6>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-check card-radio p-0">
                                <input class="form-check-input" type="radio" name="data-layout-width"
                                       id="layout-width-fluid" value="fluid"/>
                                <label class="form-check-label avatar-md w-100" for="layout-width-fluid">
                                    <img src="assets/img/vertical.png" alt="Layout Image"/>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Fluid</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio p-0">
                                <input class="form-check-input" type="radio" name="data-layout-width"
                                       id="layout-width-boxed" value="boxed"/>
                                <label class="form-check-label avatar-md w-100 px-2" for="layout-width-boxed">
                                    <img src="assets/img/boxed.png" alt="Layout Image"/>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Boxed</h5>
                        </div>
                    </div>
                </div>

                <div id="layout-position">
                    <div class="layout-head pt-3">
                        <h5>Layout Position</h5>
                        <h6>Choose Fixed or Scrollable Layout Position.</h6>
                    </div>
                    <div class="btn-group bor-rad-50 overflow-hidden radio" role="group">
                        <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-fixed"
                               value="fixed"/>
                        <label class="btn btn-light w-sm" for="layout-position-fixed">Fixed</label>

                        <input type="radio" class="btn-check" name="data-layout-position"
                               id="layout-position-scrollable" value="scrollable"/>
                        <label class="btn btn-light w-sm ms-0" for="layout-position-scrollable">Scrollable</label>
                    </div>
                </div>
                <div class="layout-head pt-3">
                    <h5>Topbar Color</h5>
                    <h6>Choose Light or Dark Topbar Color.</h6>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-check card-radio p-0">
                            <input class="form-check-input" type="radio" name="data-topbar" id="topbar-color-light"
                                   value="light"/>
                            <label class="form-check-label avatar-md w-100" for="topbar-color-light">
                                <img src="assets/img/vertical.png" alt="Layout Image"/>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Light</h5>
                    </div>
                    <div class="col-4">
                        <div class="form-check card-radio p-0">
                            <input class="form-check-input" type="radio" name="data-topbar" id="topbar-color-dark"
                                   value="dark"/>
                            <label class="form-check-label avatar-md w-100" for="topbar-color-dark">
                                <img src="assets/img/dark.png" alt="Layout Image"/>
                            </label>
                        </div>
                        <h5 class="fs-13 text-center mt-2">Dark</h5>
                    </div>
                </div>

                <div id="sidebar-size">
                    <div class="layout-head pt-3">
                        <h5>Sidebar Size</h5>
                        <h6>Choose a size of Sidebar.</h6>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio p-0">
                                <input class="form-check-input" type="radio" name="data-sidebar-size"
                                       id="sidebar-size-default" value="lg"/>
                                <label class="form-check-label avatar-md w-100" for="sidebar-size-default">
                                    <img src="assets/img/vertical.png" alt="Layout Image"/>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Default</h5>
                        </div>

                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio p-0">
                                <input class="form-check-input" type="radio" name="data-sidebar-size"
                                       id="sidebar-size-compact" value="md"/>
                                <label class="form-check-label avatar-md w-100" for="sidebar-size-compact">
                                    <img src="assets/img/compact.png" alt="Layout Image"/>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Compact</h5>
                        </div>

                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio p-0">
                                <input class="form-check-input" type="radio" name="data-sidebar-size"
                                       id="sidebar-size-small-hover" value="sm-hover"/>
                                <label class="form-check-label avatar-md w-100" for="sidebar-size-small-hover">
                                    <img src="assets/img/small-hover.png" alt="Layout Image"/>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Small Hover View</h5>
                        </div>
                    </div>
                </div>

                <div id="sidebar-view">
                    <div class="layout-head pt-3">
                        <h5>Sidebar View</h5>
                        <h6>Choose Default or Detached Sidebar view.</h6>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio p-0">
                                <input class="form-check-input" type="radio" name="data-layout-style"
                                       id="sidebar-view-default" value="default"/>
                                <label class="form-check-label avatar-md w-100" for="sidebar-view-default">
                                    <img src="assets/img/compact.png" alt="Layout Image"/>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Default</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio p-0">
                                <input class="form-check-input" type="radio" name="data-layout-style"
                                       id="sidebar-view-detached" value="detached"/>
                                <label class="form-check-label avatar-md w-100" for="sidebar-view-detached">
                                    <img src="assets/img/detached.png" alt="Layout Image"/>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Detached</h5>
                        </div>
                    </div>
                </div>
                <div id="sidebar-color">
                    <div class="layout-head pt-3">
                        <h5>Sidebar Color</h5>
                        <h6>Choose a color of Sidebar.</h6>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio p-0" data-bs-toggle="collapse"
                                 data-bs-target="#collapseBgGradient.show">
                                <input class="form-check-input" type="radio" name="data-sidebar"
                                       id="sidebar-color-light" value="light"/>
                                <label class="form-check-label avatar-md w-100" for="sidebar-color-light">
                                    <span class="bg-light bg-sidebarcolor"></span>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Light</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio p-0" data-bs-toggle="collapse"
                                 data-bs-target="#collapseBgGradient.show">
                                <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-dark"
                                       value="dark"/>
                                <label class="form-check-label avatar-md w-100" for="sidebar-color-dark">
                                    <span class="bg-darks bg-sidebarcolor"></span>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Dark</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check sidebar-setting card-radio p-0">
                                <input class="form-check-input" type="radio" name="data-sidebar"
                                       id="sidebar-color-gradient" value="gradient"/>
                                <label class="form-check-label avatar-md w-100" for="sidebar-color-gradient">
                                    <span class="bg-gradients bg-sidebarcolor"></span>
                                </label>
                            </div>
                            <h5 class="fs-13 text-center mt-2">Gradient</h5>
                        </div>
                        <div class="col-4 d-none">
                            <button class="btn btn-link avatar-md w-100 p-0 overflow-hidden border collapsed"
                                    type="button" data-bs-toggle="collapse" data-bs-target="#collapseBgGradient"
                                    aria-expanded="false">
=======
                    </div>

                    <div id="sidebar-size">
                        <div class="layout-head pt-3">
                            <h5>Sidebar Size</h5>
                            <h6>Choose a size of Sidebar.</h6>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio p-0">
                                    <input class="form-check-input" type="radio" name="data-sidebar-size" id="sidebar-size-default" value="lg" />
                                    <label class="form-check-label avatar-md w-100" for="sidebar-size-default">
                                        <img src="assets/img/vertical.png" alt="Layout Image" />
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Default</h5>
                            </div>

                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio p-0">
                                    <input class="form-check-input" type="radio" name="data-sidebar-size" id="sidebar-size-compact" value="md" />
                                    <label class="form-check-label avatar-md w-100" for="sidebar-size-compact">
                                        <img src="assets/img/compact.png" alt="Layout Image" />
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Compact</h5>
                            </div>

                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio p-0">
                                    <input class="form-check-input" type="radio" name="data-sidebar-size" id="sidebar-size-small-hover" value="sm-hover" />
                                    <label class="form-check-label avatar-md w-100" for="sidebar-size-small-hover">
                                        <img src="assets/img/small-hover.png" alt="Layout Image" />
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Small Hover View</h5>
                            </div>
                        </div>
                    </div>

                    <div id="sidebar-view">
                        <div class="layout-head pt-3">
                            <h5>Sidebar View</h5>
                            <h6>Choose Default or Detached Sidebar view.</h6>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio p-0">
                                    <input class="form-check-input" type="radio" name="data-layout-style" id="sidebar-view-default" value="default" />
                                    <label class="form-check-label avatar-md w-100" for="sidebar-view-default">
                                        <img src="assets/img/compact.png" alt="Layout Image" />
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Default</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio p-0">
                                    <input class="form-check-input" type="radio" name="data-layout-style" id="sidebar-view-detached" value="detached" />
                                    <label class="form-check-label avatar-md w-100" for="sidebar-view-detached">
                                        <img src="assets/img/detached.png" alt="Layout Image" />
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Detached</h5>
                            </div>
                        </div>
                    </div>
                    <div id="sidebar-color">
                        <div class="layout-head pt-3">
                            <h5>Sidebar Color</h5>
                            <h6>Choose a color of Sidebar.</h6>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio p-0" data-bs-toggle="collapse" data-bs-target="#collapseBgGradient.show">
                                    <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-light" value="light" />
                                    <label class="form-check-label avatar-md w-100" for="sidebar-color-light">
                                        <span class="bg-light bg-sidebarcolor"></span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Light</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio p-0" data-bs-toggle="collapse" data-bs-target="#collapseBgGradient.show">
                                    <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-dark" value="dark" />
                                    <label class="form-check-label avatar-md w-100" for="sidebar-color-dark">
                                        <span class="bg-darks bg-sidebarcolor"></span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Dark</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio p-0">
                                    <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-gradient" value="gradient" />
                                    <label class="form-check-label avatar-md w-100" for="sidebar-color-gradient">
                                        <span class="bg-gradients bg-sidebarcolor"></span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Gradient</h5>
                            </div>
                            <div class="col-4 d-none">
                                <button class="btn btn-link avatar-md w-100 p-0 overflow-hidden border collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBgGradient" aria-expanded="false">
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                                    <span class="d-flex gap-1 h-100">
                                        <span class="flex-shrink-0">
                                            <span class="bg-vertical-gradient d-flex h-100 flex-column gap-1 p-1">
                                                <span class="d-block p-1 px-2 bg-soft-light rounded mb-2"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                            </span>
                                        </span>
                                        <span class="flex-grow-1">
                                            <span class="d-flex h-100 flex-column">
                                                <span class="bg-light d-block p-1"></span>
                                                <span class="bg-light d-block p-1 mt-auto"></span>
                                            </span>
                                        </span>
                                    </span>
<<<<<<< HEAD
                            </button>
                            <h5 class="fs-13 text-center mt-2">Gradient</h5>
=======
                                </button>
                                <h5 class="fs-13 text-center mt-2">Gradient</h5>
                            </div>
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
                        </div>
                    </div>
                </div>
            </div>
        </div>
<<<<<<< HEAD
    </div>
    <div class="offcanvas-footer border-top p-3 text-center">
        <div class="row">
            <div class="col-6">
                <button type="button" class="btn btn-light w-100 bor-rad-50" id="reset-layout">
                    Reset
                </button>
            </div>
            <div class="col-6">
                <a href="https://themeforest.net/item/smarthr-bootstrap-admin-panel-template/21153150" target="_blank"
                   class="btn btn-primary w-100 bor-rad-50">Buy Now</a>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="assets/js/jquery-3.7.1.min.js"></script>

<!-- Bootstrap Core JS -->
<script src="assets/js/bootstrap.bundle.min.js"></script>

<!-- Slimscroll JS -->
<script src="assets/js/jquery.slimscroll.min.js"></script>

<!-- Chart JS -->
<script src="assets/plugins/morris/morris.min.js"></script>
<script src="assets/plugins/raphael/raphael.min.js"></script>
<script src="assets/js/chart.js"></script>
<script src="assets/js/greedynav.js"></script>

<!-- Theme Settings JS -->
<script src="assets/js/layout.js"></script>
<script src="assets/js/theme-settings.js"></script>

<!-- Custom JS -->
<script src="assets/js/app.js"></script>
=======
        <div class="offcanvas-footer border-top p-3 text-center">
            <div class="row">
                <div class="col-6">
                    <button type="button" class="btn btn-light w-100 bor-rad-50" id="reset-layout">
                        Reset
                    </button>
                </div>
                <div class="col-6">
                    <a href="https://themeforest.net/item/smarthr-bootstrap-admin-panel-template/21153150" target="_blank" class="btn btn-primary w-100 bor-rad-50">Buy Now</a>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap Core JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <!-- Slimscroll JS -->
    <script src="assets/js/jquery.slimscroll.min.js"></script>

    <!-- Chart JS -->
    <script src="assets/plugins/morris/morris.min.js"></script>
    <script src="assets/plugins/raphael/raphael.min.js"></script>
    <script src="assets/js/chart.js"></script>
    <script src="assets/js/greedynav.js"></script>

    <!-- Theme Settings JS -->
    <script src="assets/js/layout.js"></script>
    <script src="assets/js/theme-settings.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/app.js"></script>
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
</body>

</html>

<script type="text/javascript">
<<<<<<< HEAD
    $(document).ready(function () {
        // Mengambil data dari PHP dan merepresentasikan ke dalam grafik
        $.getJSON('get_revenuechart.php', function (data) {
=======
    $(document).ready(function() {
        // Mengambil data dari PHP dan merepresentasikan ke dalam grafik
        $.getJSON('get_revenuechart.php', function(data) {
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
            Morris.Bar({
                element: 'revenue_chart',
                data: data,
                xkey: 'year',
                ykeys: ['income', 'expense'],
                labels: ['Income', 'Expense'],
                barColors: ['#ff9b44', '#fc6075'],
                barGap: 4, // Gap antara grup bar
                barSizeRatio: 0.55, // Ukuran lebar bar relatif terhadap gap
            });
        });
    });

<<<<<<< HEAD
    $(document).ready(function () {
        $.getJSON('get_saleschart.php', function (data) {
=======
    $(document).ready(function() {
        $.getJSON('get_saleschart.php', function(data) {
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
            Morris.Line({
                element: 'line-charts', // Perbaikan di sini: dari 'line_charts' menjadi 'line-charts'
                data: data,
                xkey: 'year',
                ykeys: ['totalSales', 'totalRevenue'],
                labels: ['Total Sales', 'Total Revenue'],
                lineColors: ['#ff9b44', '#fc6075'],
                lineWidth: 3,
                resize: true,
                redraw: true
            });
        });
    });
</script>

<script>
    function updateClientStatus(clientId, status) {
<<<<<<< HEAD
        var httpRequest = new XMLHttpRequest();
        if (!httpRequest) {
=======
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
            alert('Gagal :( Tidak dapat membuat instance XMLHTTP');
            return false;
        }

<<<<<<< HEAD
        httpRequest.onreadystatechange = function () {
=======
        httpRequest.onreadystatechange = function() {
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
            if (httpRequest.readyState === XMLHttpRequest.DONE) {
                if (httpRequest.status === 200) {
                    // Perbarui status pada HTML
                    var statusElem = document.getElementById('status-' + clientId);
                    if (status === '1') {
                        statusElem.innerHTML = '<i class="fa-regular fa-circle-dot text-success"></i> Aktif';
                    } else {
                        statusElem.innerHTML = '<i class="fa-regular fa-circle-dot text-danger"></i> Nonaktif';
                    }
                } else {
                    alert('Ada masalah dengan request.');
                }
            }
        };

        httpRequest.open('POST', 'update_client_status.php', true);
        httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        httpRequest.send('client_id=' + clientId + '&status=' + status);
    }
<<<<<<< HEAD
</script>

<script>
    // Ini akan me-trigger scroll ketika roda mouse digunakan
    document.addEventListener('wheel', function (e) {
        // Menggunakan deltaX dan deltaY dari event untuk mendapatkan arah dan kecepatan scroll
        window.scrollBy(e.deltaX, e.deltaY);
    });
</script>
=======
</script>
>>>>>>> 5677572ac33a2a9ad097a1eaec891461a9dfeabd
