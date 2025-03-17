<?php
require 'function.php';

if (isset($_GET['nomor_induk'])) {
    $nomor_induk = $_GET['nomor_induk'];
    $employee = getEmployeeById($nomor_induk);

    if (!$employee) {
        echo "Data karyawan tidak ditemukan!";
        exit;
    }
} else {
    echo "Nomor Induk tidak diberikan!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="text-center text-primary">Detail Karyawan</h2>
            <div class="row">
                <div class="col-md-4 text-center">
                    <?php if (!empty($employee['foto_karyawan'])): ?>
                        <img src="image karyawan/<?php echo $employee['foto_karyawan']; ?>" class="img-fluid rounded shadow" alt="Foto Karyawan">
                    <?php else: ?>
                        <p class="text-muted">Tidak ada foto tersedia</p>
                    <?php endif; ?>
                </div>
                <div class="col-md-8">
                    <table class="table">
                        <tr>
                            <th>Nomor Induk</th>
                            <td><?php echo $employee['nomor_induk']; ?></td>
                        </tr>
                        <tr>
                            <th>Nama Karyawan</th>
                            <td><?php echo $employee['nama_karyawan']; ?></td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td><?php echo $employee['jenis_kelamin']; ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo $employee['email']; ?></td>
                        </tr>
                        <tr>
                            <th>No. Telepon</th>
                            <td><?php echo $employee['no_telepon']; ?></td>
                        </tr>
                        <tr>
                            <th>Jabatan</th>
                            <td><?php echo $employee['jabatan']; ?></td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td><?php echo $employee['alamat']; ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Lahir</th>
                            <td><?php echo $employee['tanggal_lahir']; ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Bergabung</th>
                            <td><?php echo $employee['tanggal_bergabung']; ?></td>
                        </tr>
                        <tr>
                            <th>Gaji</th>
                            <td>Rp. <?php echo number_format($employee['gaji'], 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <th>Status Karyawan</th>
                            <td><?php echo $employee['status_karyawan']; ?></td>
                        </tr>
                    </table>
                    <a href="lihat.php" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
