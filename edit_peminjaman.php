<?php
session_start();

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "perpusku");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek jika admin sudah login
if (!isset($_SESSION['id_perpustakaan'])) {
    header('Location: login.php');
    exit;
}

// Cek apakah ada ID peminjaman di URL
if (isset($_GET['id'])) {
    $id_peminjaman = $_GET['id'];

    // Ambil data peminjaman dari database
    $query = "SELECT * FROM peminjaman WHERE id_peminjaman = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $id_peminjaman);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $_SESSION['pesan'] = "Data peminjaman tidak ditemukan.";
        header('Location: peminjaman.php');
        exit;
    }
} else {
    $_SESSION['pesan'] = "ID peminjaman tidak ditemukan.";
    header('Location: peminjaman.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Proses update data peminjaman
    $id_peminjaman = $_POST['id_peminjaman'];
    $id_perpustakaan = $_POST['id_perpustakaan'];
    $id_buku = $_POST['id_buku'];
    $nama_peminjam = $_POST['nama_peminjam'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $status = $_POST['status'];

    // Query untuk update data peminjaman
    $updateQuery = "UPDATE peminjaman SET id_perpustakaan = ?, id_buku = ?, nama_peminjam = ?, tanggal_pinjam = ?, tanggal_kembali = ?, status = ? WHERE id_peminjaman = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssssss", $id_perpustakaan, $id_buku, $nama_peminjam, $tanggal_pinjam, $tanggal_kembali, $status, $id_peminjaman);

    if ($stmt->execute()) {
        $_SESSION['pesan'] = "Data peminjaman berhasil diperbarui!";
        header('Location: peminjaman.php');
        exit;
    } else {
        $_SESSION['pesan'] = "Gagal memperbarui data: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Peminjaman - Admin Perpustakaan</title>
    <link rel="stylesheet" href="/perpusKU/1_css/peminjaman.css">
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

<!-- Notifikasi -->
<?php if (isset($_SESSION['pesan'])): ?>
    <div class="notification" style="background-color: #27ae60; color: white; padding: 10px; border-radius: 5px; margin: 20px; text-align: center;">
        <?php echo $_SESSION['pesan']; unset($_SESSION['pesan']); ?>
    </div>
<?php endif; ?>

<!-- Form Edit Peminjaman -->
<form action="edit_peminjaman.php?id=<?php echo $row['id_peminjaman']; ?>" method="POST" class="form-tambah">
    <h3>Edit Peminjaman</h3>
    <input type="hidden" name="id_peminjaman" value="<?php echo $row['id_peminjaman']; ?>">

    <input type="text" name="id_perpustakaan" value="<?php echo $row['id_perpustakaan']; ?>" placeholder="ID Perpustakaan" required>
    <input type="text" name="id_buku" value="<?php echo $row['id_buku']; ?>" placeholder="ID Buku" required>
    <input type="text" name="nama_peminjam" value="<?php echo $row['nama_peminjam']; ?>" placeholder="Nama Peminjam" required>
    <label for="tanggal_pinjam">Tanggal Pinjam</label>
    <input type="date" name="tanggal_pinjam" value="<?php echo $row['tanggal_pinjam']; ?>" required>
    <label for="tanggal_kembali">Tanggal Kembali</label>
    <input type="date" name="tanggal_kembali" value="<?php echo $row['tanggal_kembali']; ?>" required>
    <label for="status">Status</label>
    <select name="status" required>
        <option value="Dipinjam" <?php echo ($row['status'] == 'Dipinjam') ? 'selected' : ''; ?>>Dipinjam</option>
        <option value="Kembali" <?php echo ($row['status'] == 'Kembali') ? 'selected' : ''; ?>>Kembali</option>
    </select>
    <button type="submit" name="update_peminjaman" class="add-btn">Perbarui Peminjaman</button>
</form>

<!-- Footer -->
<div class="footer">
    <p>&copy; 2024 PerpusKU. All Rights Reserved.</p>
</div>

</body>
</html>

<?php
$conn->close();
?>
