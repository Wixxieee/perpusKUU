<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", '', "perpusku");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $id_buku = $_POST['id_buku'];
    $id_perpustakaan = $_POST['id_perpustakaan']; // Menambahkan id_perpustakaan
    $judul_buku = $_POST['judul_buku'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $jumlah_buku = $_POST['jumlah_buku'];

    // Menggunakan prepared statement untuk menghindari SQL injection
    $stmt = $conn->prepare("INSERT INTO koleksi (id_buku, id_perpustakaan, judul_buku, pengarang, penerbit, tahun_terbit, jumlah_buku) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $id_buku, $id_perpustakaan, $judul_buku, $pengarang, $penerbit, $tahun_terbit, $jumlah_buku);
    
    // Eksekusi query
    if ($stmt->execute()) {
        echo "Buku berhasil ditambahkan!";
    } else {
        echo "Gagal menambahkan buku: " . $stmt->error;
    }

    // Menutup prepared statement dan koneksi
    $stmt->close();
}

$conn->close();
?>