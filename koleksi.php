<?php

// Koneksi ke database
$conn = new mysqli("localhost", "root", '', "perpusku");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

session_start();
$id_perpustakaan = $_SESSION['id_perpustakaan'];

if (!$id_perpustakaan) {
    die("ID Perpustakaan tidak ditemukan dalam session. Harap login ulang.");
}

// Proses penghapusan data jika parameter 'hapus' ada
if (isset($_GET['hapus'])) {
    $id_buku = $_GET['hapus'];

    // Query untuk menghapus buku
    $query = "DELETE FROM koleksi WHERE id_buku = ? AND id_perpustakaan = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Kesalahan pada query: " . $conn->error);
    }

    $stmt->bind_param("ss", $id_buku, $id_perpustakaan);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Buku berhasil dihapus.'); window.location.href='koleksi.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus buku atau buku tidak ditemukan.'); window.location.href='koleksi.php';</script>";
    }
}

// Mengambil data koleksi buku berdasarkan id_perpustakaan
$sql = "SELECT * FROM koleksi WHERE id_perpustakaan = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Kesalahan pada query: " . $conn->error);
}

$stmt->bind_param("s", $id_perpustakaan);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Kesalahan dalam pengambilan data: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koleksi Buku</title>
    <link rel="stylesheet" href="/perpusKU/1_css/koleksi.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

<!-- Tombol Tambah Buku -->
<a href="javascript:void(0)" id="tambahBukuBtn" class="add-btn">Tambah Buku</a>

<!-- Modal untuk tambah buku -->
<div id="modalTambahBuku" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h2>Tambah Buku Baru</h2>
        <form id="formTambahBuku" method="POST" action="tambah_buku.php">
            <input type="hidden" id="id_perpustakaan" name="id_perpustakaan" value="<?php echo $id_perpustakaan; ?>">

            <label for="id_buku">ID Buku:</label>
            <input type="text" id="id_buku" name="id_buku" required><br>

            <label for="judul_buku">Judul Buku:</label>
            <input type="text" id="judul_buku" name="judul_buku" required><br>

            <label for="pengarang">Pengarang:</label>
            <input type="text" id="pengarang" name="pengarang" required><br>

            <label for="penerbit">Penerbit:</label>
            <input type="text" id="penerbit" name="penerbit" required><br>

            <label for="tahun_terbit">Tahun Terbit:</label>
            <input type="number" id="tahun_terbit" name="tahun_terbit" required><br>

            <label for="jumlah_buku">Jumlah Buku:</label>
            <input type="number" id="jumlah_buku" name="jumlah_buku" required><br>

            <button type="submit">Tambah Buku</button>
        </form>
    </div>
</div>

<!-- Tabel koleksi buku -->
<div class="container">
    <div class="content">
        <table class="book-table">
            <thead>
                <tr>
                    <th>ID Buku</th>
                    <th>Judul Buku</th>
                    <th>Pengarang</th>
                    <th>Penerbit</th>
                    <th>Tahun Terbit</th>
                    <th>Jumlah Buku</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="book-table-body">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_buku']; ?></td>
                        <td><?php echo $row['judul_buku']; ?></td>
                        <td><?php echo $row['pengarang']; ?></td>
                        <td><?php echo $row['penerbit']; ?></td>
                        <td><?php echo $row['tahun_terbit']; ?></td>
                        <td><?php echo $row['jumlah_buku']; ?></td>
                        <td>
                            <a href="edit_buku.php?id=<?php echo $row['id_buku']; ?>" class="edit-btn">Edit</a>
                            <a href="?hapus=<?php echo $row['id_buku']; ?>" class="delete-btn" onclick="return confirm('Apakah Anda yakin ingin menghapus?');">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p>&copy; 2024 PerpusKU. All Rights Reserved.</p>
</div>

<script>
    // Modal Tambah Buku
    document.getElementById('tambahBukuBtn').addEventListener('click', function() {
        document.getElementById('modalTambahBuku').style.display = 'block';
    });
    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('modalTambahBuku').style.display = 'none';
    });
    
</script>

</body>
</html>
