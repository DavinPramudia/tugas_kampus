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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | Sistem Desa</title>
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

/* default label position (over input) */
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

/* when focused OR input has content (via .filled) -> floating label */
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

/* small responsive tweaks */
@media (max-width: 480px) {
  .wrapper { width: 92%; padding: 22px; }
  h2 { font-size: 1.6rem; }
}

/* buttons */
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

/* inline error message style (hidden by default) */
.form-error {
  display: none;
  margin: 8px 0 0;
  padding: 10px;
  background: rgba(255, 69, 58, 0.08);
  border-left: 4px solid rgba(255, 69, 58, 0.9);
  color: #ffdfdf;
  font-size: 13px;
  border-radius: 6px;
  text-align: left;
  white-space: pre-wrap;
}
  </style>
</head>

<body>
  <div class="wrapper">
    <form id="registerForm" method="POST" novalidate>
      <h2>Registrasi</h2>

      <!-- tempat untuk pesan error client-side -->
      <div id="formError" class="form-error" role="alert" aria-live="polite"></div>

      <div class="input-field">
        <input type="text" name="nik" id="nik" required pattern="[0-9]{16}" inputmode="numeric" />
        <label for="nik">NIK (16 digit)</label>
      </div>

      <div class="input-field">
        <input type="text" name="nama" id="nama" required minlength="3" />
        <label for="nama">Nama Lengkap</label>
      </div>

      <div class="input-field">
        <input type="text" name="tempat" id="tempat" required minlength="3" />
        <label for="tempat">Tempat Lahir</label>
      </div>

      <div class="input-field">
        <input type="date" name="tanggal_lahir" id="tanggal_lahir" required />
        <label for="tanggal_lahir" style="top:-10px;font-size:0.8rem;">Tanggal Lahir</label>
      </div>

      <div class="input-field">
        <input type="text" name="alamat" id="alamat" required minlength="5" />
        <label for="alamat">Alamat Lengkap</label>
      </div>

      <div class="input-field">
        <input type="email" name="email" id="email" required />
        <label for="email">Email</label>
      </div>

      <div class="input-field">
        <input type="text" name="username" id="username" required minlength="5" />
        <label for="username">Username</label>
      </div>

      <div class="input-field">
        <input type="password" name="password" id="password" required minlength="8" />
        <label for="password">Password</label>
      </div>

      <button type="submit" name="register">Daftar</button>
      <button type="button" class="back-btn" onclick="window.location.href='login.php'">⬅ Kembali</button>

      <div class="register">
        <p>Sudah punya akun? <a href="login.php">Login</a></p>
      </div>
    </form>
  </div>

  <script>
    // Helper: tampilkan pesan error client-side
    function showError(message) {
      const el = document.getElementById('formError');
      el.textContent = message;
      el.style.display = 'block';
      // scroll to error in small screens
      el.scrollIntoView({behavior: 'smooth', block: 'center'});
    }

    function clearError() {
      const el = document.getElementById('formError');
      el.textContent = '';
      el.style.display = 'none';
    }

    // Tambahkan behavior "filled" untuk input yang ada isinya
    function updateFilledClass(input) {
      if (input.value.trim() !== '') input.classList.add('filled');
      else input.classList.remove('filled');
    }

    document.querySelectorAll('#registerForm .input-field input').forEach(input => {
      // on load: set filled if value already present
      updateFilledClass(input);

      // saat user mengetik
      input.addEventListener('input', () => {
        updateFilledClass(input);
        clearError();
      });

      // saat blur, juga update (berguna jika browser auto-fill)
      input.addEventListener('blur', () => updateFilledClass(input));
    });

    // VALIDASI client-side saat submit tanpa reload
    document.getElementById('registerForm').addEventListener('submit', function(event) {
      event.preventDefault(); // HENTIKAN submit otomatis — kita tangani manual

      clearError();

      const nik = document.getElementById('nik').value.trim();
      const nama = document.getElementById('nama').value.trim();
      const tempat = document.getElementById('tempat').value.trim();
      const tanggal = document.getElementById('tanggal_lahir').value;
      const alamat = document.getElementById('alamat').value.trim();
      const email = document.getElementById('email').value.trim();
      const username = document.getElementById('username').value.trim();
      const password = document.getElementById('password').value;

      const clientErrors = [];

      if (!/^[0-9]{16}$/.test(nik)) {
        clientErrors.push('NIK harus 16 digit angka.');
      }
      if (nama.length < 3) clientErrors.push('Nama minimal 3 karakter.');
      if (tempat.length < 3) clientErrors.push('Tempat lahir minimal 3 karakter.');
      if (!tanggal) clientErrors.push('Tanggal lahir wajib diisi.');
      if (alamat.length < 5) clientErrors.push('Alamat terlalu pendek.');
      // simple email check
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) clientErrors.push('Format email tidak valid.');
      if (username.length < 5) clientErrors.push('Username minimal 5 karakter.');
      if (password.length < 8) clientErrors.push('Password minimal 8 karakter.');

      if (clientErrors.length > 0) {
        // gabungkan dan tampilkan di atas form (tanpa reload)
        showError(clientErrors.join('\n'));
        // jangan submit ke server
        return;
      }

      // semua validasi client-side lulus -> submit form ke server (PHP)
      this.submit();
    });
  </script>
</body>
</html>
