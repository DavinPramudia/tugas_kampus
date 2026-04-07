<?php
include 'admin/koneksi.php';

if (isset($_POST['register'])) {
    $nik = trim($_POST['nik']);
    $nama = trim($_POST['nama']);
    $tempat = trim($_POST['tempat']);
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = trim($_POST['alamat']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // ===================== VALIDASI BACKEND =====================
    $errors = [];

    if (!preg_match('/^[0-9]{16}$/', $nik)) {
        $errors[] = "NIK harus 16 digit angka.";
    }
    
    if (strlen($nama) < 3) {
        $errors[] = "Nama minimal 3 karakter.";
    }

    if (strlen($tempat) < 3) {
        $errors[] = "Tempat lahir minimal 3 karakter.";
    }

    if (empty($tanggal_lahir)) {
        $errors[] = "Tanggal lahir wajib diisi.";
    }

    if (strlen($alamat) < 5) {
        $errors[] = "Alamat terlalu pendek.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    }

    if (strlen($username) < 5) {
        $errors[] = "Username minimal 5 karakter.";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password minimal 8 karakter.";
    }

    // Jika ada error validasi, tampilkan pesan
    if (count($errors) > 0) {
        $msg = implode('\n', $errors);
        echo "<script>alert('$msg'); window.history.back();</script>";
        exit;
    }

    // ===================== CEK DUPLIKAT =====================
    $cek_username = mysqli_query($koneksi, "SELECT id FROM user WHERE username='$username'");
    if (mysqli_num_rows($cek_username) > 0) {
        echo "<script>alert('Username sudah digunakan!'); window.history.back();</script>";
        exit;
    }

    // Enkripsi password
    $password_hash = md5($password);

    // Simpan user baru dengan status pending
    $query = mysqli_query($koneksi, "
        INSERT INTO user (nik, nama, tempat, tanggal_lahir, alamat, email, username, password, status)
        VALUES ('$nik','$nama','$tempat','$tanggal_lahir','$alamat','$email','$username','$password_hash','pending')
    ");

    if ($query) {
        echo "<script>alert('Registrasi berhasil! Silakan tunggu verifikasi admin sebelum bisa login.'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Registrasi gagal!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #fafafa;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
      padding: 20px;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    form {
      background: #fff;
      padding: 25px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 340px;
    }

    input {
      width: 100%;
      padding: 10px;
      margin-bottom: 12px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    input, button {
      box-sizing: border-box;
    }

    button {
      width: 100%;
      padding: 10px;
      background: #FF9800;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-bottom: 10px;
      font-size: 16px;
    }

    button:hover {
      background: #e68900;
    }

    .back-btn {
      background: #777;
    }

    .back-btn:hover {
      background: #555;
    }

    p {
      text-align: center;
      font-size: 14px;
    }

    @media (max-width: 400px) {
      form {
        padding: 20px 15px;
      }
      input, button {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>
  <h2>Form Registrasi</h2>

  <form method="POST" onsubmit="return validateForm()">
    <input type="text" name="nik" placeholder="NIK (16 digit)" pattern="[0-9]{16}" title="NIK harus 16 digit angka" required><br>
    <input type="text" name="nama" placeholder="Nama Lengkap" minlength="3" required><br>
    <input type="text" name="tempat" placeholder="Tempat Lahir" minlength="3" required><br>
    <input type="date" name="tanggal_lahir" required><br>
    <input type="text" name="alamat" placeholder="Alamat Lengkap" minlength="5" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="text" name="username" placeholder="Username (min. 5 karakter)" minlength="5" required><br>
    <input type="password" name="password" placeholder="Password (min. 8 karakter)" minlength="6" required><br>

    <button type="submit" name="register">Daftar</button>
    <button type="button" class="back-btn" onclick="window.history.back()">⬅ Kembali</button>
  </form>

  <p>Sudah punya akun? <a href="login.php">Login</a></p>

  <script>
    function validateForm() {
      const nik = document.querySelector('[name="nik"]').value;
      if (nik.length !== 16 || isNaN(nik)) {
        alert("NIK harus 16 digit angka!");
        return false;
      }
      return true;
    }
  </script>
</body>
</html>
