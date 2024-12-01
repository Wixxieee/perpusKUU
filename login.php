<?php
session_start();

// Cek jika user sudah login
if (isset($_SESSION['id_perpustakaan'])) {
    header('Location: dashboard.php'); // Redirect ke dashboard jika sudah login
    exit;
}

// Inisialisasi variabel untuk pesan error
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $id_perpustakaan = $_POST['id_perpustakaan'];
    $password = $_POST['password'];

    // Koneksi ke database
    $conn = new mysqli("localhost", "root", '', "perpusku");

    // Periksa koneksi database
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Query login dengan prepared statement
    $sql = "SELECT * FROM admins WHERE id_perpustakaan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_perpustakaan); // Bind parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Simpan sesi
            $_SESSION['id_perpustakaan'] = $id_perpustakaan;
            $_SESSION['nama_instansi'] = $row['nama_instansi'];  // Simpan nama instansi dari database

            // Redirect ke dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "ID Perpustakaan tidak ditemukan!";
    }

    // Tutup koneksi
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin Perpustakaan</title>
    <link rel="stylesheet" href="/perpusKU/1_css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Login Admin Perpustakaan</h2>
        <form method="POST" action="">
            <label for="id_perpustakaan">ID Perpustakaan</label>
            <input type="text" name="id_perpustakaan" id="id_perpustakaan" pattern="[0-9]+" 
                   title="Hanya angka diperbolehkan" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" minlength="6" required>

            <!-- Pesan error jika ada -->
            <?php if (!empty($error)) { ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php } ?>

            <button type="submit">Login</button>
            <p>Belum punya akun? <a href="daftar.php">Daftar disini</a></p>
        </form>
    </div>
</body>
</html>
