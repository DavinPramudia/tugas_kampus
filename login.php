<?php
session_start();
include 'admin/koneksi.php';

// Pesan login dari halaman lain
if (isset($_SESSION['pesan'])) {
  echo "<script>
          alert('⚠️ " . addslashes($_SESSION['pesan']) . "');
        </script>";
  unset($_SESSION['pesan']);
}

// Tangkap parameter redirect
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';

// Proses login
if (isset($_POST['login'])) {
  $username = mysqli_real_escape_string($koneksi, $_POST['username']);
  $password = md5($_POST['password']); // Pastikan di DB juga md5

  $result = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username' AND password='$password'");
  $data = mysqli_fetch_assoc($result);

  if ($data) {
    if ($data['status'] == 'pending') {
      echo "<script>alert('Akun kamu masih dalam proses verifikasi admin. Mohon tunggu.'); window.location='login.php';</script>";
      exit;
    } elseif ($data['status'] == 'ditolak') {
      echo "<script>alert('Pendaftaran kamu ditolak oleh admin. Silakan hubungi pihak desa.'); window.location='login.php';</script>";
      exit;
    } elseif ($data['status'] == 'diterima') {
      // Simpan session
      $_SESSION['login'] = true;
      $_SESSION['user_id'] = $data['id'];
      $_SESSION['username'] = $data['username'];
      $_SESSION['nama'] = $data['nama'];
      
      header("Location: $redirect");
      exit;
    } else {
      echo "<script>alert('Status akun tidak dikenali. Hubungi admin.'); window.history.back();</script>";
      exit;
    }
  } else {
    echo "<script>alert('Username atau password salah! / Kamu belum terdaftar');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Sistem Desa</title>
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

form {
  display: flex;
  flex-direction: column;
}

h2 {
  font-size: 2rem;
  margin-bottom: 18px;
  color: #fff;
}

/* input group */
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

/* floating label effect */
.input-field input:focus {
  border-color: rgba(255,152,0,0.9);
  box-shadow: 0 4px 18px rgba(255,152,0,0.06);
}

.input-field input:focus + label,
.input-field input.filled + label {
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
      <h2>Login</h2>

      <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">

      <div class="input-field">
        <input type="text" name="username" id="username" required minlength="5" />
        <label for="username">Username</label>
      </div>

      <div class="input-field">
        <input type="password" name="password" id="password" required minlength="8" />
        <label for="password">Password</label>
      </div>

      <button type="submit" name="login">Masuk</button>
      <button type="button" class="back-btn" onclick="window.location.href='index.php'">⬅ Kembali</button>

      <div class="register">
        <p>Belum punya akun? <a href="register.php">Daftar</a></p>
      </div>
    </form>
  </div>

</body>
</html>
