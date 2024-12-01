<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", '', "perpusku");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah ID buku ada di URL
if (isset($_GET['id'])) {
    $id_buku = $_GET['id'];

    // Ambil data buku berdasarkan ID
    $sql = "SELECT * FROM koleksi WHERE id_buku = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $id_buku);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Buku tidak ditemukan!";
        exit;
    }
} else {
    echo "ID buku tidak ditemukan!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Proses update data buku
    $id_perpustakaan = $_POST['id_perpustakaan'];
    $judul_buku = $_POST['judul_buku'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $jumlah_buku = $_POST['jumlah_buku'];

    // Update data ke database
    $sql_update = "UPDATE koleksi SET id_perpustakaan = ?, judul_buku = ?, pengarang = ?, penerbit = ?, tahun_terbit = ?, jumlah_buku = ? WHERE id_buku = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param('ssssdis', $id_perpustakaan, $judul_buku, $pengarang, $penerbit, $tahun_terbit, $jumlah_buku, $id_buku);

    if ($stmt_update->execute()) {
        echo "<script>alert('Data buku berhasil diperbarui.'); window.location.href='koleksi.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data buku.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku</title>
    <link rel="stylesheet" href="/perpusKU/1_css/koleksi.css">
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

<!-- Form Edit Buku -->
<div class="container">
    <div class="content">
        <h2>Edit Buku</h2>
        <form method="POST" action="edit_buku.php?id=<?php echo $row['id_buku']; ?>">
            <label for="id_perpustakaan">ID Perpustakaan:</label>
            <input type="text" id="id_perpustakaan" name="id_perpustakaan" value="<?php echo $row['id_perpustakaan']; ?>" required><br>

            <label for="id_buku">ID Buku:</label>
            <input type="text" id="id_buku" name="id_buku" value="<?php echo $row['id_buku']; ?>" disabled><br>

            <label for="judul_buku">Judul Buku:</label>
            <input type="text" id="judul_buku" name="judul_buku" value="<?php echo $row['judul_buku']; ?>" required><br>

            <label for="pengarang">Pengarang:</label>
            <input type="text" id="pengarang" name="pengarang" value="<?php echo $row['pengarang']; ?>" required><br>

            <label for="penerbit">Penerbit:</label>
            <input type="text" id="penerbit" name="penerbit" value="<?php echo $row['penerbit']; ?>" required><br>

            <label for="tahun_terbit">Tahun Terbit:</label>
            <input type="number" id="tahun_terbit" name="tahun_terbit" value="<?php echo $row['tahun_terbit']; ?>" required><br>

            <label for="jumlah_buku">Jumlah Buku:</label>
            <input type="number" id="jumlah_buku" name="jumlah_buku" value="<?php echo $row['jumlah_buku']; ?>" required><br>

            <button type="submit">Perbarui Buku</button>
        </form>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p>&copy; 2024 PerpusKU. All Rights Reserved.</p>
</div>

</body>
</html>
