<?php
//// sambungkan ke database
//$conn = new mysqli("localhost", "root", "password", "sbpapp");
//
//// Periksa koneksi
//if ($conn->connect_error) {
//    die("Koneksi gagal: " . $conn->connect_error);
//}
//
////inisialisasi variabel untuk pencarian, Anda dapat mengambil ini melalui $_GET atau $_POST jika datang dari input pengguna
//$search = ''; // Ubah ini berdasarkan input pencarian dari pengguna
//
//// SQL untuk mendapatkan data proyek
//$sql = "SELECT * FROM projects WHERE name LIKE '%$search%'"; // Ubah ini sesuai dengan kebutuhan pencarian Anda
//$result = $conn->query($sql);
//
//if ($result->num_rows > 0) {
//    // Output data setiap baris
//    while($row = $result->fetch_assoc()) {
//        // Sesuaikan dengan HTML baru Anda dan tambahkan data dari PHP
//        echo "
//        <div class='col-lg-4 col-sm-6 col-md-4 col-xl-3 d-flex'>
//            <div class='card w-100'>
//                <div class='card-body'>
//                    <div class='dropdown dropdown-action profile-action'>
//                        <!-- Konten dropdown untuk edit dan delete -->
//                    </div>
//                    <h4 class='project-title'><a href='project-view.html'>" . $row["name"] . "</a></h4>
//
//                    <!-- Contoh mengambil total open tasks, dan tasks completed dari database -->
//                    <small class='block text-ellipsis m-b-15'>
//                        <span class='text-xs'>" . $row["tasks_total"] . "</span> <span class='text-muted'>open tasks, </span>
//                        <span class='text-xs'>" . $row["tasks_completed"] . "</span> <span class='text-muted'>tasks completed</span>
//                    </small>
//
//                    <!-- Deskripsi dari database -->
//                    <p class='text-muted'>" . $row["description"] . "</p>
//
//                    <!-- Informasi tambahan seperti deadline dan anggota tim -->
//                    <!-- Sesuaikan dengan data yang tersedia di database Anda -->
//
//                    <p class='m-b-5'>Progress <span class='text-success float-end'>40%</span></p>
//                    <div class='progress progress-xs mb-0'>
//                        <div class='progress-bar bg-success' role='progressbar' style='width: 40%'></div> <!-- Asumsikan progress adalah 40%, sesuaikan ini dengan data dari database Anda -->
//                    </div>
//                </div>
//            </div>
//        </div>";
//    }
//} else {
//    echo "Tidak ada hasil.";
//}
//$conn->close();
//?>
