<?php
session_start();

// Cek jika admin sudah login
if (!isset($_SESSION['id_perpustakaan'])) {
    header('Location: login.php');
    exit;
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "perpusku");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil id_perpustakaan yang login
$id_perpustakaan = $_SESSION['id_perpustakaan'];

// Query untuk jumlah total koleksi buku berdasarkan id_perpustakaan
$totalBukuQuery = "SELECT COUNT(*) AS total FROM koleksi WHERE id_perpustakaan = ?";
$totalBukuStmt = $conn->prepare($totalBukuQuery);
$totalBukuStmt->bind_param("s", $id_perpustakaan);
$totalBukuStmt->execute();
$totalBukuResult = $totalBukuStmt->get_result();
$totalBuku = $totalBukuResult->fetch_assoc()['total'];

// Query untuk jumlah buku dipinjam berdasarkan id_perpustakaan
$bukuDipinjamQuery = "SELECT COUNT(*) AS dipinjam FROM peminjaman WHERE id_perpustakaan = ? AND status = 'Dipinjam'";
$bukuDipinjamStmt = $conn->prepare($bukuDipinjamQuery);
$bukuDipinjamStmt->bind_param("s", $id_perpustakaan);
$bukuDipinjamStmt->execute();
$bukuDipinjamResult = $bukuDipinjamStmt->get_result();
$bukuDipinjam = $bukuDipinjamResult->fetch_assoc()['dipinjam'];

// Query untuk jumlah buku yang tersedia berdasarkan id_perpustakaan
$bukuTersediaQuery = "SELECT COUNT(*) AS tersedia FROM koleksi WHERE id_perpustakaan = ? AND jumlah_buku > 0";
$bukuTersediaStmt = $conn->prepare($bukuTersediaQuery);
$bukuTersediaStmt->bind_param("s", $id_perpustakaan);
$bukuTersediaStmt->execute();
$bukuTersediaResult = $bukuTersediaStmt->get_result();
$bukuTersedia = $bukuTersediaResult->fetch_assoc()['tersedia'];

// Ambil nama instansi dari sesi
$nama_instansi = $_SESSION['nama_instansi'];

// Tutup koneksi
$totalBukuStmt->close();
$bukuDipinjamStmt->close();
$bukuTersediaStmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Perpustakaan</title>
    <link rel="stylesheet" href="/PerpusKU/1_css/dashb.css">
</head>
<body>
    <!-- Topbar -->
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

    <!-- Dashboard Container -->
    <div class="dashboard-container">
        <h2>Selamat datang, <?php echo htmlspecialchars($nama_instansi); ?></h2>

        <!-- Highlight Data -->
        <div class="highlight-data">
            <div class="highlight-item">
                <i class="icon-book"></i>
                <p><?php echo $totalBuku; ?></p>
                <span>Total Koleksi</span>
            </div>
            <div class="highlight-item">
                <i class="icon-user"></i>
                <p><?php echo $bukuDipinjam; ?></p>
                <span>Buku Dipinjam</span>
            </div>
            <div class="highlight-item">
                <i class="icon-check"></i>
                <p><?php echo $bukuTersedia; ?></p>
                <span>Buku Tersedia</span>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="quick-links">
            <h3>Fitur Cepat</h3>
            <div class="link-container">
                <a href="koleksi.php" class="quick-link">Kelola Koleksi Buku</a>
                <a href="peminjaman.php" class="quick-link">Lihat Peminjaman</a>
                <a href="panduan.php" class="quick-link">Panduan Penggunaan</a>
            </div>
        </div>

        <!-- Pengumuman -->
        <div class="announcement">
            <h3>Pengumuman</h3>
            <p>Sistem akan menjalani pemeliharaan pada 30 November 2024. Pastikan data sudah diperbarui sebelum tanggal tersebut.</p>
        </div>

        <!-- Galeri Buku Populer -->
        <div class="book-gallery">
            <h3>Buku Populer</h3>
            <div class="book-items">
                <div class="book-item">
                    <img src="/PerpusKU/3_img/book1.jpg" alt="Sebuah Seni Untuk Bersikap Bodo Amat">
                    <p>Sebuah Seni Untuk Bersikap Bodo Amat</p>
                </div>
                <div class="book-item">
                    <img src="/PerpusKU/3_img/book2.jpg" alt="How To Win Friends and Influence People">
                    <p>How To Win Friends and Influence People</p>
                </div>
                <div class="book-item">
                    <img src="/PerpusKU/3_img/book3.jpg" alt="Guru Aini">
                    <p>Guru Aini</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 PerpusKU. All Rights Reserved.</p>
    </div>
</body>
</html>
