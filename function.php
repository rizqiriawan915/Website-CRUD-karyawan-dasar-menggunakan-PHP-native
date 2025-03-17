<?php
// Koneksi ke database
function connectDB() {
    $conn = mysqli_connect("localhost", "root", "", "crud_app");
    
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }
    
    return $conn;
}

// Fungsi untuk sanitasi input
function sanitizeInput($data) {
    $conn = connectDB();
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

// Fungsi untuk cek apakah nomor induk sudah terdaftar
function checkNomorInduk($nomor_induk) {
    $conn = connectDB();
    $nomor_induk = sanitizeInput($nomor_induk);
    
    $query_check = "SELECT * FROM users WHERE nomor_induk = '$nomor_induk'";
    $result_check = mysqli_query($conn, $query_check);
    
    $exists = mysqli_num_rows($result_check) > 0;
    mysqli_close($conn);
    
    return $exists;
}

// Fungsi untuk registrasi user baru
function registerUser($nomor_induk, $username, $password, $phone) {
    $conn = connectDB();
    
    // Sanitasi input
    $nomor_induk = sanitizeInput($nomor_induk);
    $username = sanitizeInput($username);
    $password = sanitizeInput($password);
    $phone = sanitizeInput($phone);
    
    // Query untuk insert data
    $query = "INSERT INTO users (nomor_induk, username, password, phone) 
              VALUES ('$nomor_induk', '$username', '$password', '$phone')";
    
    $result = mysqli_query($conn, $query);
    $error = mysqli_error($conn);
    
    mysqli_close($conn);
    
    if ($result) {
        return true;
    } else {
        return $error;
    }
}

// Fungsi untuk cek login
function checkLogin($nomor_induk, $password) {
    $conn = connectDB();
    
    // Sanitasi input
    $nomor_induk = sanitizeInput($nomor_induk);
    $password = sanitizeInput($password);
    
    // Cek apakah nomor induk ada di database
    $query = "SELECT * FROM users WHERE nomor_induk = '$nomor_induk' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    
    $is_valid = mysqli_num_rows($result) == 1;
    
    mysqli_close($conn);
    
    return $is_valid;
}

// Fungsi untuk set session
function setLoginSession($nomor_induk) {
    $_SESSION['nomor_induk'] = $nomor_induk;
}

// Fungsi untuk cek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['nomor_induk']);
}

// Fungsi untuk logout
function logout() {
    session_start();
    session_unset();
    session_destroy();
}

// Fungsi untuk redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Fungsi untuk cek user login
function checkUserLogin() {
    if (!isset($_SESSION['nomor_induk'])) {
        redirect('index.php');
    }
}

// Fungsi untuk mendapatkan data user
function getUserData($nomor_induk) {
    $conn = connectDB();
    $nomor_induk = sanitizeInput($nomor_induk);
    
    $user_query = "SELECT username FROM users WHERE nomor_induk = '$nomor_induk'";
    $user_result = mysqli_query($conn, $user_query);
    $user_data = mysqli_fetch_assoc($user_result);
    
    mysqli_close($conn);
    return $user_data;
}

// Fungsi untuk menghapus data karyawan
function deleteKaryawan($nomor_induk_karyawan) {
    $conn = connectDB();
    $nomor_induk_karyawan = sanitizeInput($nomor_induk_karyawan);
    
    $delete_query = "DELETE FROM karyawan WHERE nomor_induk = '$nomor_induk_karyawan'";
    $result = mysqli_query($conn, $delete_query);
    
    $success = $result;
    $error = mysqli_error($conn);
    
    mysqli_close($conn);
    
    if ($success) {
        return true;
    } else {
        return $error;
    }
}

// Fungsi untuk menghitung total data karyawan
function countKaryawan() {
    $conn = connectDB();
    
    $total_query = "SELECT COUNT(*) as total FROM karyawan";
    $total_result = mysqli_query($conn, $total_query);
    $total_row = mysqli_fetch_assoc($total_result);
    $total_data = $total_row['total'];
    
    mysqli_close($conn);
    return $total_data;
}

// Fungsi untuk mengambil data karyawan dengan pagination
function getKaryawanWithPagination($start, $per_page) {
    $conn = connectDB();
    
    $query = "SELECT * FROM karyawan LIMIT $start, $per_page";
    $result = mysqli_query($conn, $query);
    
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    mysqli_close($conn);
    return $data;
}

function searchKaryawan($keyword) {
    $conn = connectDB();
    $keyword = mysqli_real_escape_string($conn, $keyword);

    $query = "SELECT * FROM karyawan 
              WHERE nama_karyawan LIKE '%$keyword%' 
              OR nomor_induk LIKE '%$keyword%' 
              OR email LIKE '%$keyword%'
              OR jabatan LIKE '%$keyword%'
              LIMIT 10";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error SQL: " . mysqli_error($conn)); // Debugging SQL
    }

    $karyawan = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $karyawan[] = $row;
    }

    return $karyawan;
}

function tambahKaryawan($data, $file) {
    $conn = connectDB();

    $nomor_induk = htmlspecialchars($data["nomor_induk"]);
    $nama_karyawan = htmlspecialchars($data["nama_karyawan"]);
    $jenis_kelamin = htmlspecialchars($data["jenis_kelamin"]);
    $email = htmlspecialchars($data["email"]);
    $no_telepon = htmlspecialchars($data["no_telepon"]);
    $jabatan = htmlspecialchars($data["jabatan"]);
    $alamat = htmlspecialchars($data["alamat"]);
    $tanggal_lahir = htmlspecialchars($data["tanggal_lahir"]);
    $tanggal_bergabung = htmlspecialchars($data["tanggal_bergabung"]);
    $gaji = htmlspecialchars($data["gaji"]);
    $status_karyawan = htmlspecialchars($data["status_karyawan"]);

    // *Upload Foto*
    $namaFile = $file["foto_karyawan"]["name"];
    $tmpName = $file["foto_karyawan"]["tmp_name"];
    $error = $file["foto_karyawan"]["error"];

    if ($error === 4) {
        echo "<script>alert('Pilih foto terlebih dahulu!');</script>";
        return false;
    }

    $ekstensiValid = ["jpg", "jpeg", "png"];
    $ekstensiFile = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

    if (!in_array($ekstensiFile, $ekstensiValid)) {
        echo "<script>alert('Format file tidak didukung!');</script>";
        return false;
    }

    $namaFileBaru = uniqid() . '.' . $ekstensiFile;
    $folderTujuan = "image karyawan/" . $namaFileBaru;

    if (!move_uploaded_file($tmpName, $folderTujuan)) {
        echo "<script>alert('Gagal mengupload foto!');</script>";
        return false;
    }

    // *Simpan Data ke Database*
    $query = "INSERT INTO karyawan (nomor_induk, nama_karyawan, foto_karyawan, jenis_kelamin, email, no_telepon, jabatan, alamat, tanggal_lahir, tanggal_bergabung, gaji, status_karyawan)
              VALUES ('$nomor_induk', '$nama_karyawan', '$namaFileBaru', '$jenis_kelamin', '$email', '$no_telepon', '$jabatan', '$alamat', '$tanggal_lahir', '$tanggal_bergabung', '$gaji', '$status_karyawan')";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function updateKaryawan($data, $file) {
    $conn = connectDB();

    // Ambil data dari form
    $nomor_induk = $data['nomor_induk'];
    $nama_karyawan = $data['nama_karyawan'];
    $jenis_kelamin = $data['jenis_kelamin'];
    $email = $data['email'];
    $no_telepon = $data['no_telepon'];
    $jabatan = $data['jabatan'];
    $alamat = $data['alamat'];
    $tanggal_lahir = $data['tanggal_lahir'];
    $tanggal_bergabung = $data['tanggal_bergabung'];
    $gaji = $data['gaji'];
    $status_karyawan = $data['status_karyawan'];

    // Ambil data karyawan lama
    $query = "SELECT foto_karyawan FROM karyawan WHERE nomor_induk = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $nomor_induk);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $employee = mysqli_fetch_assoc($result);
    $foto_karyawan = $employee['foto_karyawan']; // Foto lama

    // Proses upload foto baru jika ada
    if (!empty($file['foto_karyawan']['name'])) {
        $folderTujuan = "image karyawan/";
        $namaFile = $file['foto_karyawan']['name'];
        $tmpName = $file['foto_karyawan']['tmp_name'];
        $ekstensiValid = ['jpg', 'jpeg', 'png', 'gif'];
        $ekstensiFile = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        if (in_array($ekstensiFile, $ekstensiValid)) {
            $namaFileBaru = uniqid() . '.' . $ekstensiFile;
            $pathFile = $folderTujuan . $namaFileBaru;

            // Hapus foto lama jika ada
            if (!empty($foto_karyawan) && file_exists($folderTujuan . $foto_karyawan)) {
                unlink($folderTujuan . $foto_karyawan);
            }

            // Pindahkan file ke folder tujuan
            move_uploaded_file($tmpName, $pathFile);
            $foto_karyawan = $namaFileBaru; // Simpan nama foto baru
        } else {
            return "Format file tidak didukung!";
        }
    }

    // Update data ke database
    $updateQuery = "UPDATE karyawan SET 
                    nomor_induk = ?, 
                    nama_karyawan = ?, 
                    jenis_kelamin = ?, 
                    email = ?, 
                    no_telepon = ?, 
                    jabatan = ?, 
                    alamat = ?, 
                    tanggal_lahir = ?, 
                    tanggal_bergabung = ?, 
                    gaji = ?, 
                    status_karyawan = ?, 
                    foto_karyawan = ?
                    WHERE nomor_induk = ?";
                    
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, "sssssssssdsss", 
        $nomor_induk,
        $nama_karyawan, 
        $jenis_kelamin, 
        $email, 
        $no_telepon, 
        $jabatan, 
        $alamat, 
        $tanggal_lahir, 
        $tanggal_bergabung, 
        $gaji, 
        $status_karyawan, 
        $foto_karyawan, 
        $nomor_induk
    );

    if (mysqli_stmt_execute($stmt)) {
        return "success";
    } else {
        return "Gagal memperbarui data!";
    }
}

function getEmployeeById($nomor_induk) {
    $conn = connectDB(); // Koneksi ke database
    
    // Gunakan prepared statement untuk mencegah SQL Injection
    $query = "SELECT * FROM karyawan WHERE nomor_induk = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $nomor_induk); // Bind parameter
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $employee = mysqli_fetch_assoc($result); // Ambil hasil query

    mysqli_stmt_close($stmt); // Tutup statement
    mysqli_close($conn); // Tutup koneksi
    return $employee; // Kembalikan data karyawan
}
?>