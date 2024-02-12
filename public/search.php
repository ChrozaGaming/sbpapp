<?php

// Kode untuk koneksi database di sini

// Cari nama proyek jika parameter project_name di-set
if(isset($_GET['project_name'])) {
    $project_name = $_GET['project_name'];
    // Query untuk mencari nama proyek
    $query = "SELECT name FROM projects WHERE name LIKE '%$project_name%'";
    // Jalankan query dan tampilkan hasilnya
}

// Cari nama karyawan jika parameter employee_name di-set
if(isset($_GET['employee_name'])) {
    $employee_name = $_GET['employee_name'];
    // Query untuk mencari nama karyawan
    $query = "SELECT first_name, last_name FROM employees WHERE first_name LIKE '%$employee_name%' OR last_name LIKE '%$employee_name%'";
    // Jalankan query dan tampilkan hasilnya
}

// Logika untuk menjalankan query dan menampilkan hasilnya di sini

?>
