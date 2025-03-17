<?php
session_start();
require 'function.php';

// Check if user is logged in
if (!isset($_SESSION['nomor_induk'])) {
    header("Location: index.php");
    exit();
}

// Get employee data
if (!isset($_GET['id'])) {
    header("Location: lihat.php");
    exit();
}

$nomor_induk = $_GET['id'];
$employee = getEmployeeById($nomor_induk);

if (!$employee) {
    header("Location: lihat.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = updateKaryawan($_POST, $_FILES);
    if ($result === "success") {
        $success_message = "Data karyawan berhasil diperbarui.";
        $employee = getEmployeeById($_POST['nomor_induk']);
    } else {
        $error_message = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="text-primary text-center">Edit Data Karyawan</h2>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"> <?php echo $error_message; ?> </div>
            <?php endif; ?>
            
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success"> <?php echo $success_message; ?> </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nomor_induk" class="form-label">Nomor Induk:</label>
                    <input type="text" id="nomor_induk" name="nomor_induk" class="form-control" value="<?php echo $employee['nomor_induk']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="nama_karyawan" class="form-label">Nama Karyawan:</label>
                    <input type="text" id="nama_karyawan" name="nama_karyawan" class="form-control" value="<?php echo $employee['nama_karyawan']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="foto_karyawan" class="form-label">Foto Karyawan:</label>
                    <input type="file" id="foto_karyawan" name="foto_karyawan" class="form-control">
                    <?php if (!empty($employee['foto_karyawan'])): ?>
                        <img src="image karyawan/<?php echo $employee['foto_karyawan']; ?>" alt="Foto Karyawan" class="img-thumbnail mt-2" width="150">
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin:</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="Laki-laki" <?php echo ($employee['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                        <option value="Perempuan" <?php echo ($employee['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo $employee['email']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="no_telepon" class="form-label">No. Telepon:</label>
                    <input type="text" id="no_telepon" name="no_telepon" class="form-control" value="<?php echo $employee['no_telepon']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="jabatan" class="form-label">Jabatan:</label>
                    <input type="text" id="jabatan" name="jabatan" class="form-control" value="<?php echo $employee['jabatan']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat:</label>
                    <textarea id="alamat" name="alamat" class="form-control" required><?php echo $employee['alamat']; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir:</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control" value="<?php echo $employee['tanggal_lahir']; ?>">
                </div>
                <div class="mb-3">
                    <label for="tanggal_bergabung" class="form-label">Tanggal Bergabung:</label>
                    <input type="date" id="tanggal_bergabung" name="tanggal_bergabung" class="form-control" value="<?php echo $employee['tanggal_bergabung']; ?>">
                </div>
                <div class="mb-3">
                    <label for="gaji" class="form-label">Gaji:</label>
                    <input type="number" step="0.01" id="gaji" name="gaji" class="form-control" value="<?php echo $employee['gaji']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Karyawan:</label>
                    <select name="status_karyawan" class="form-select">
                        <option value="Tetap" <?php echo ($employee['status_karyawan'] == 'Tetap') ? 'selected' : ''; ?>>Tetap</option>
                        <option value="Kontrak" <?php echo ($employee['status_karyawan'] == 'Kontrak') ? 'selected' : ''; ?>>Kontrak</option>
                        <option value="Magang" <?php echo ($employee['status_karyawan'] == 'Magang') ? 'selected' : ''; ?>>Magang</option>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="lihat.php" class="btn btn-secondary w-50 me-2">Kembali</a>
                    <button type="submit" class="btn btn-primary w-50">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
