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

$id_perpustakaan = $_SESSION['id_perpustakaan']; // Ambil id_perpustakaan dari session

// Ambil data peminjaman yang sesuai dengan id_perpustakaan yang login
$query = "SELECT * FROM peminjaman WHERE id_perpustakaan = ?"; 
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $id_perpustakaan);
$stmt->execute();
$result = $stmt->get_result();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cek jika data peminjaman ditambahkan
    if (isset($_POST['tambah_peminjaman'])) {
        $id_buku = $_POST['id_buku'];
        $nama_peminjam = $_POST['nama_peminjam'];
        $tanggal_pinjam = $_POST['tanggal_pinjam'];
        $tanggal_kembali = $_POST['tanggal_kembali'];
        $status = $_POST['status'];

        // Query untuk menambah data peminjaman
        $query = "INSERT INTO peminjaman (id_perpustakaan, id_buku, nama_peminjam, tanggal_pinjam, tanggal_kembali, status) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("ssssss", $id_perpustakaan, $id_buku, $nama_peminjam, $tanggal_pinjam, $tanggal_kembali, $status);
            if ($stmt->execute()) {
                $_SESSION['pesan'] = "Data peminjaman berhasil ditambahkan!";
            } else {
                $_SESSION['pesan'] = "Gagal menambahkan data: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['pesan'] = "Gagal mempersiapkan query: " . $conn->error;
        }
    }

    // Proses Update Data Peminjaman
    if (isset($_POST['update_peminjaman'])) {
        $idPeminjaman = $_POST['id'];
        $status = $_POST['status'];

        // Ubah status peminjaman
        $updateStatusQuery = "UPDATE peminjaman SET status = ? WHERE id_peminjaman = ?";
        $stmt = $conn->prepare($updateStatusQuery);
        if ($stmt) {
            $stmt->bind_param('si', $status, $idPeminjaman); // Pastikan idPeminjaman sesuai dengan tipe data
            if ($stmt->execute()) {
                $_SESSION['pesan'] = "Status berhasil diubah.";
            } else {
                $_SESSION['pesan'] = "Gagal mengubah status: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// Proses Hapus Data Peminjaman
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Query untuk hapus data peminjaman
    $query = "DELETE FROM peminjaman WHERE id_peminjaman=? AND id_perpustakaan=?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ss", $id, $id_perpustakaan); // Hapus hanya jika sesuai dengan id_perpustakaan
        if ($stmt->execute()) {
            $_SESSION['pesan'] = "Data peminjaman berhasil dihapus!";
        } else {
            $_SESSION['pesan'] = "Gagal menghapus data: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['pesan'] = "Gagal mempersiapkan query: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Buku - Admin Perpustakaan</title>
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

    <!-- Form Peminjaman -->
    <form action="" method="POST" class="form-tambah">
        <h3>Tambah Peminjaman</h3>
        <input type="text" name="id_perpustakaan" placeholder="ID Perpustakaan" value="<?php echo $id_perpustakaan; ?>" readonly required>
        <input type="text" name="id_buku" placeholder="ID Buku" required>
        <input type="text" name="nama_peminjam" placeholder="Nama Peminjam" required>
        <label for="tanggal_pinjam">Tanggal Pinjam</label>
        <input type="date" name="tanggal_pinjam" required>
        <label for="tanggal_kembali">Tanggal Kembali</label>
        <input type="date" name="tanggal_kembali" required>
        <select name="status" required>
            <option value="Dipinjam">Dipinjam</option>
            <option value="Kembali">Kembali</option>
        </select>
        <button type="submit" name="tambah_peminjaman" class="add-btn">Tambah Peminjaman</button>
    </form>

    <!-- Tabel Peminjaman -->
    <div class="dashboard-container">
        <table class="table-peminjaman">
            <thead>
                <tr>
                    <th>ID Peminjaman</th>
                    <th>ID Buku</th>
                    <th>Nama Peminjam</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_peminjaman']; ?></td>
                    <td><?php echo $row['id_buku']; ?></td>
                    <td><?php echo $row['nama_peminjam']; ?></td>
                    <td><?php echo $row['tanggal_pinjam']; ?></td>
                    <td><?php echo $row['tanggal_kembali']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="edit_peminjaman.php?id=<?php echo $row['id_peminjaman']; ?>" class="edit-btn">Edit</a>
                            <a href="?hapus=<?php echo $row['id_peminjaman']; ?>" class="delete-btn" onclick="return confirm('Apakah Anda yakin ingin menghapus?');">Hapus</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 PerpusKU. All Rights Reserved.</p>
    </div>

</body>
</html>

<?php
$conn->close();
?>
