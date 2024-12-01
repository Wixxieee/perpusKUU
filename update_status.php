<?php
session_start();
include('db.php'); // Pastikan koneksi database sudah terhubung

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_peminjaman']) && isset($_POST['status'])) {
        $idPeminjaman = $_POST['id_peminjaman'];
        $status = $_POST['status'];

        // Update status peminjaman
        $updateStatusQuery = "UPDATE peminjaman SET status = ? WHERE id_peminjaman = ?";
        $stmt = $conn->prepare($updateStatusQuery);
        if ($stmt) {
            $stmt->bind_param('si', $status, $idPeminjaman);
            if ($stmt->execute()) {
                // Dapatkan id_buku dari peminjaman yang diubah statusnya
                $getBukuQuery = "SELECT id_buku FROM peminjaman WHERE id_peminjaman = ?";
                $bukuStmt = $conn->prepare($getBukuQuery);
                $bukuStmt->bind_param('s', $idPeminjaman);
                $bukuStmt->execute();
                $bukuResult = $bukuStmt->get_result()->fetch_assoc();
                $idBuku = $bukuResult['id_buku'];

                // Hitung jumlah peminjam yang masih aktif (status 'Dipinjam') untuk buku tersebut
                $countQuery = "SELECT COUNT(*) AS total FROM peminjaman WHERE id_buku = ? AND status = 'Dipinjam'";
                $countStmt = $conn->prepare($countQuery);
                $countStmt->bind_param('s', $idBuku);
                $countStmt->execute();
                $countResult = $countStmt->get_result()->fetch_assoc();
                $totalPeminjam = $countResult['total'];

                // Update jumlah peminjam pada buku di tabel koleksi
                $updateCountQuery = "UPDATE koleksi SET jumlah_peminjam = ? WHERE id_buku = ?";
                $updateCountStmt = $conn->prepare($updateCountQuery);
                $updateCountStmt->bind_param('is', $totalPeminjam, $idBuku);
                $updateCountStmt->execute();

                echo "Status berhasil diperbarui dan jumlah peminjam telah dihitung.";
            } else {
                echo "Gagal mengubah status: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>
