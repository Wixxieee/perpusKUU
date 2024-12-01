<?php
// register.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_perpustakaan = trim($_POST['id_perpustakaan']);
    $nama_instansi = trim($_POST['nama_instansi']);
    $alamat = trim($_POST['alamat']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT); // Hash password

    // Koneksi ke database
    $conn = new mysqli("localhost", "root", '', "perpusku");

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Cek apakah ID Perpustakaan sudah ada
    $sql_check = "SELECT * FROM admins WHERE id_perpustakaan = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $id_perpustakaan);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Jika ID sudah terdaftar
        $error = "ID Perpustakaan sudah terdaftar!";
    } else {
        // Query untuk menambah data admin
        $sql = "INSERT INTO admins (id_perpustakaan, nama_instansi, alamat, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $id_perpustakaan, $nama_instansi, $alamat, $password);

        if ($stmt->execute()) {
            header('Location: login.php'); // Redirect ke halaman login setelah berhasil daftar
            exit;
        } else {
            $error = "Pendaftaran gagal! Silakan coba lagi.";
        }
        $stmt->close();
    }

    $stmt_check->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Admin Perpustakaan</title>
    <link rel="stylesheet" href="/perpusKU/1_css/reg.css">
</head>
<body>
    <div class="register-container">
        <h2>Daftar Admin Perpustakaan</h2>
        <form method="POST" action="">
            <label for="id_perpustakaan">ID Perpustakaan</label>
            <input type="text" name="id_perpustakaan" id="id_perpustakaan" 
                   pattern="[0-9]+" title="Hanya angka diperbolehkan" required>

            <label for="nama_instansi">Nama Instansi</label>
            <input type="text" name="nama_instansi" id="nama_instansi" required>

            <label for="alamat">Alamat</label>
            <input type="text" name="alamat" id="alamat" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" minlength="6" required>

            <!-- Pesan error jika ada -->
            <?php if (!empty($error)) { ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php } ?>

            <button type="submit">Daftar</button>
            <p>Sudah punya akun? <a href="login.php">Login disini</a></p>
        </form>
    </div>
</body>
</html>
