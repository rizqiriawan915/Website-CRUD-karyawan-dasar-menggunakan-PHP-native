<?php
require 'function.php';

$keyword = isset($_GET['search']) ? $_GET['search'] : "";
$karyawan = searchKaryawan($keyword);

if (!$karyawan) {
    echo "<tr><td colspan='8' class='text-center'>Data tidak ditemukan</td></tr>";
    exit;
}

$no = 1;
foreach ($karyawan as $row) {
    echo "<tr>";
    echo "<td>" . $no++ . "</td>";
    echo "<td>" . htmlspecialchars($row['nomor_induk']) . "</td>";
    echo "<td>" . htmlspecialchars($row['nama_karyawan']) . "</td>";
    echo "<td>" . htmlspecialchars($row['jenis_kelamin']) . "</td>";
    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
    echo "<td>" . htmlspecialchars($row['no_telepon']) . "</td>";
    echo "<td>" . htmlspecialchars($row['jabatan']) . "</td>";
    echo "<td>
            <a href='detail.php?id=" . htmlspecialchars($row['nomor_induk']) . "' class='btn btn-info btn-sm'>Detail</a>
            <a href='ubah.php?id=" . htmlspecialchars($row['nomor_induk']) . "' class='btn btn-warning btn-sm'>Edit</a>
            <a href='?delete=" . htmlspecialchars($row['nomor_induk']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\");'>Hapus</a>
          </td>";
    echo "</tr>";
}
?>