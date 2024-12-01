<?php
$host = "localhost"; // Ganti dengan host Anda
$user = "root"; // Ganti dengan username database
$password = ''; // Ganti dengan password database
$dbname = "perpusku"; // Ganti dengan nama database Anda

$conn = new mysqli($host, $user, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
