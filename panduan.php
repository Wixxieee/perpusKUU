<?php
session_start();

// Cek jika admin sudah login
if (!isset($_SESSION['id_perpustakaan'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panduan Penggunaan - Admin Perpustakaan</title>
    <link rel="stylesheet" href="/perpusKU/1_css/panduan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="topbar">
        <h1>PerpusKU</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="koleksi.php">Koleksi Buku</a></li>
                <li><a href="peminjaman.php">Peminjaman Buku</a></li>
                <li><a href="panduan.php">Panduan</a></li>
                <li><a href="logout.php" class="logout-button">Logout</a></li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="dashboard-container">
        <h2>Panduan Penggunaan</h2>

        <div class="panduan-section">
            <div class="panduan-text">
                <h3><i class="fas fa-sign-in-alt"></i> 1. Login</h3>
                <p>Untuk login, masukkan ID Perpustakaan dan password yang sudah didaftarkan.</p>
            </div>
        </div>

        <div class="panduan-section">
            <div class="panduan-text">
                <h3><i class="fas fa-book-open"></i> 2. Menambah Buku</h3>
                <p>Pada halaman Koleksi Buku, klik tombol "Tambah Buku" untuk menambahkan buku baru ke perpustakaan.</p>
            </div>
        </div>

        <div class="panduan-section">
            <div class="panduan-text">
                <h3><i class="fas fa-cogs"></i> 3. Mengelola Buku</h3>
                <p>Pada halaman Koleksi Buku, Anda dapat mengedit dan menghapus buku yang sudah ada di perpustakaan.</p>
            </div>
        </div>

        <div class="panduan-section">
            <div class="panduan-text">
                <h3><i class="fas fa-book-reader"></i> 4. Peminjaman Buku</h3>
                <p>Pada halaman Peminjaman, Anda dapat melihat peminjaman buku, mengubah status pengembalian buku, dan menghubungi peminjam jika buku terlambat dikembalikan.</p>
            </div>
        </div>

        <a href="dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 PerpusKU. All Rights Reserved.</p>
    </div>
</body>
</html>
