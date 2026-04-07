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
        echo "<script>alert('Registrasi gagal!'); window.history.back();</script>";
    }
}
?>

  <!DOCTYPE html>
  <html lang="id">
  <head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />  <title>Register | Sistem Desa</title>
  <style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Open Sans", sans-serif;
}

body {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  width: 100%;
  background: url("./image/Login-register-bg..jpg"), #000;
  background-position: center;
  background-size: cover;
  position: relative;
  padding: 0 10px;
}

/* wrapper glassmorphism */
.wrapper {
  width: 420px;
  border-radius: 12px;
  padding: 30px;
  text-align: center;
  border: 1px solid rgba(255, 255, 255, 0.12);
  backdrop-filter: blur(9px);
  -webkit-backdrop-filter: blur(9px);
  background: linear-gradient(135deg, rgba(255,255,255,0.04), rgba(255,255,255,0.02));
  box-shadow: 0 8px 30px rgba(0,0,0,0.4);
}

h2 {
  font-size: 2rem;
  margin-bottom: 18px;
  color: #fff;
}

/* input group (floating label) */
.input-field {
  position: relative;
  margin: 12px 0 6px;
}

.input-field input {
  width: 100%;
  padding: 12px 12px;
  border-radius: 8px;
  border: 1px solid rgba(255,255,255,0.12);
  background: rgba(255,255,255,0.02);
  color: #fff;
  outline: none;
  font-size: 15px;
  transition: border-color .15s ease, box-shadow .15s ease;
}

.input-field label {
  position: absolute;
  left: 12px;
  top: 12px;
  color: rgba(255,255,255,0.7);
  font-size: 14px;
  pointer-events: none;
  transition: all .18s ease;
  background: transparent;
  padding: 0 6px;
}

.input-field input:focus {
  border-color: rgba(255,152,0,0.9);
  box-shadow: 0 4px 18px rgba(255,152,0,0.06);
}

.input-field input:focus + label,
.input-field input:not(:placeholder-shown) + label {
  top: -10px;
  left: 8px;
  font-size: 12px;
  color: #FFB86B;
  background: linear-gradient(135deg, rgba(0,0,0,0.25), rgba(255,255,255,0.02));
  border-radius: 4px;
}

@media (max-width: 480px) {
  .wrapper { width: 92%; padding: 22px; }
  h2 { font-size: 1.6rem; }
}

button {
  background: #fff;
  color: #000;
  font-weight: 600;
  border: none;
  padding: 12px 20px;
  cursor: pointer;
  border-radius: 8px;
  font-size: 16px;
  border: 2px solid transparent;
  transition: 0.2s ease;
  margin-top: 12px;
}

button:hover {
  color: #fff;
  border-color: #fff;
  background: rgba(255, 255, 255, 0.12);
}

.back-btn {
  background: rgba(255, 255, 255, 0.06);
  color: #fff;
  border: 1px solid rgba(255,255,255,0.06);
}

.back-btn:hover {
  background: rgba(255, 255, 255, 0.12);
}

.register {
  text-align: center;
  margin-top: 12px;
  color: #fff;
  font-size: 14px;
}



  </style>
</head>

<body>
  <div class="wrapper">
    <form method="POST">
      <h2>Registrasi</h2>

      <div class="input-field">
        <input type="text" name="nik" required pattern="[0-9]{16}" placeholder=" ">
        <label for="nik">NIK (16 digit)</label>
      </div>

      <div class="input-field">
        <input type="text" name="nama" required minlength="3" placeholder=" ">
        <label for="nama">Nama Lengkap</label>
      </div>

      <div class="input-field">
        <input type="text" name="tempat" required minlength="3" placeholder=" ">
        <label for="tempat">Tempat Lahir</label>
      </div>

      <div class="input-field">
        <input type="date" name="tanggal_lahir" required placeholder=" ">
        <label for="tanggal_lahir" style="top:-10px;font-size:0.8rem;">Tanggal Lahir</label>
      </div>

      <div class="input-field">
        <input type="text" name="alamat" required minlength="5" placeholder=" ">
        <label for="alamat">Alamat Lengkap</label>
      </div>

      <div class="input-field">
        <input type="email" name="email" required placeholder=" ">
        <label for="email">Email</label>
      </div>

      <div class="input-field">
        <input type="text" name="username" required minlength="5" placeholder=" ">
        <label for="username">Username</label>
      </div>

      <div class="input-field">
        <input type="password" name="password" required minlength="8" placeholder=" ">
        <label for="password">Password</label>
      </div>

      <button type="submit" name="register">Daftar</button>
      <button type="button" class="back-btn" onclick="window.location.href='login.php'">⬅ Kembali</button>

      <div class="register">
        <p>Sudah punya akun? <a href="login.php" style="color:#FFB86B;">Login</a></p>
      </div>
    </form>
  </div>
</body>
</html>
