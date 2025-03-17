<?php
session_start();
require_once 'function.php';

// Cek apakah pengguna sudah login
checkUserLogin();

// Logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    logout();
    redirect('index.php');
}

// Ambil informasi pengguna
$nomor_induk = $_SESSION['nomor_induk'];
$user_data = getUserData($nomor_induk);
$username = $user_data['username'];

$success_message = '';
$error_message = '';

// Hapus data karyawan
if (isset($_GET['delete'])) {
    $nomor_induk_karyawan = $_GET['delete'];
    $delete_result = deleteKaryawan($nomor_induk_karyawan);

    if ($delete_result === true) {
        echo "<script>
            alert('Data berhasil dihapus!');
            window.location.href = 'lihat.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menghapus data: " . addslashes($delete_result) . "');
        </script>";
    }
}

// Pagination
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;
$total_data = countKaryawan();
$total_pages = ceil($total_data / $per_page);
$karyawan_data = getKaryawanWithPagination($start, $per_page);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Data Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary">Data Karyawan</h2>
            <a href="?action=logout" class="btn btn-danger">Logout</a>
        </div>
        
        <div class="alert alert-info">Selamat datang, <strong><?= $username; ?></strong></div>
        
        <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"> <?= $success_message; ?> </div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"> <?= $error_message; ?> </div>
        <?php endif; ?>
        
        <div class="d-flex justify-content-between mb-3">
            <a href="tambah.php" class="btn btn-success">Tambah Data</a>
            <div class="input-group w-50">
                <span class="input-group-text">🔍</span>
                <input type="text" id="search" class="form-control" placeholder="Cari data karyawan..." autocomplete="off">
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nomor Induk</th>
                        <th>Nama Karyawan</th>
                        <th>Jenis Kelamin</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Jabatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="karyawan-body">
                    <?php if (!empty($karyawan_data)): ?>
                        <?php $no = $start + 1; ?>
                        <?php foreach ($karyawan_data as $row): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['nomor_induk']; ?></td>
                            <td><?= $row['nama_karyawan']; ?></td>
                            <td><?= $row['jenis_kelamin']; ?></td>
                            <td><?= $row['email']; ?></td>
                            <td><?= $row['no_telepon']; ?></td>
                            <td><?= $row['jabatan']; ?></td>
                            <td>
                                <a href="detail_karyawan.php?nomor_induk=<?php echo $row['nomor_induk']; ?>" class="btn btn-info btn-sm">Detail</a>
                                <a href="ubah.php?id=<?= $row['nomor_induk']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="?delete=<?= $row['nomor_induk']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data karyawan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-3">
            <a href="?page=<?= max(1, $page - 1); ?>" class="btn btn-secondary <?= ($page == 1) ? 'disabled' : ''; ?>">Sebelumnya</a>
            <span class="fw-bold">Halaman <?= $page; ?> dari <?= $total_pages; ?></span>
            <a href="?page=<?= min($total_pages, $page + 1); ?>" class="btn btn-secondary <?= ($page == $total_pages) ? 'disabled' : ''; ?>">Selanjutnya</a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("search");
            const tableBody = document.getElementById("karyawan-body");
            
            searchInput.addEventListener("keyup", function () {
                const keyword = searchInput.value.trim();
                
                const xhr = new XMLHttpRequest();
                xhr.open("GET", "get_karyawan.php?search=" + encodeURIComponent(keyword), true);
                
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        tableBody.innerHTML = xhr.responseText;
                    }
                };
                
                xhr.onerror = function () {
                    console.error("Permintaan AJAX gagal");
                };
                
                xhr.send();
            });
        });
    </script>
</body>
</html>