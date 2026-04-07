<?php
session_start();
include 'admin/koneksi.php';

// Pesan login dari halaman lain
if (isset($_SESSION['pesan'])) {
  echo "<script>
          alert('" . addslashes($_SESSION['pesan']) . "⚠️⚠️⚠️');
        </script>";
  unset($_SESSION['pesan']);
}


// Tangkap parameter redirect
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';

// Proses login
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']); // pastikan di DB juga md5

    $result = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username' AND password='$password'");
    $data = mysqli_fetch_assoc($result);

    if ($data) {
        if ($data['status'] == 'pending') {
            echo "<script>alert('Akun kamu masih dalam proses verifikasi admin. Mohon tunggu.'); window.location='login.php'</script>";
            exit;
        } elseif ($data['status'] == 'ditolak') {
            echo "<script>alert('Pendaftaran kamu ditolak oleh admin.'); window.location='login.php'</script>";
            exit;
        } elseif ($data['status'] == 'diterima') {
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['nama'] = $data['nama'];

            header("Location: $redirect");
            exit;
        } else {
            echo "<script>alert('Status akun tidak dikenali. Hubungi admin.');</script>";
        }
    } else {
        echo "<script>alert('Username atau password salah! / Belum terdaftar');</script>";
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

/* focus effect */
.input-field input:focus {
  border-color: rgba(255,152,0,0.9);
  box-shadow: 0 4px 18px rgba(255,152,0,0.06);
}

/* floating label saat ada isi atau fokus */
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

/* inline error message */
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
    <form id="loginForm" method="POST" novalidate>
      <h2>Login</h2>

      <div id="formError" class="form-error" role="alert" aria-live="polite"></div>

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

  <script>
    function showError(message) {
      const el = document.getElementById('formError');
      el.textContent = message;
      el.style.display = 'block';
      el.scrollIntoView({behavior: 'smooth', block: 'center'});
    }

    function clearError() {
      const el = document.getElementById('formError');
      el.textContent = '';
      el.style.display = 'none';
    }

    function updateFilledClass(input) {
      if (input.value.trim() !== '') input.classList.add('filled');
      else input.classList.remove('filled');
    }

    document.querySelectorAll('#loginForm .input-field input').forEach(input => {
      updateFilledClass(input);
      input.addEventListener('input', () => {
        updateFilledClass(input);
        clearError();
      });
      input.addEventListener('blur', () => updateFilledClass(input));
    });

    // VALIDASI client-side
    document.getElementById('loginForm').addEventListener('submit', function(event) {
      event.preventDefault();
      clearError();

      const username = document.getElementById('username').value.trim();
      const password = document.getElementById('password').value;

      const clientErrors = [];
      if (username.length < 5) clientErrors.push('Username minimal 5 karakter.');
      if (password.length < 8) clientErrors.push('Password minimal 8 karakter.');

      if (clientErrors.length > 0) {
        showError(clientErrors.join('\n'));
        return;
      }

      this.submit();
    });
  </script>
</body>
</html>
