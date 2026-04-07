<?php
session_start();
include 'admin/koneksi.php';

// Pesan login dari halaman lain
if (isset($_SESSION['pesan'])) {
  echo '<p style="color: red; text-align:center; font-weight:bold;">
          ⚠️ ' . $_SESSION['pesan'] . '
        </p>';
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
            echo "<script>alert('Akun kamu masih dalam proses verifikasi admin. Mohon tunggu persetujuan.'); window.location='login.php'</script>";
            exit;
        } elseif ($data['status'] == 'ditolak') {
            echo "<script>alert('Pendaftaran kamu ditolak oleh admin. Silakan hubungi pihak desa.'); window.location='login.php'</script>";
            exit;
        } elseif ($data['status'] == 'diterima') {
            // Jika sudah diterima, simpan session dan lanjut
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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
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
    }

    form {
      background: #fff;
      padding: 25px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 320px;
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
  <h2>Login</h2>

  <form method="POST">
    <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>

    <button type="submit" name="login">Login</button>
    <button type="button" class="back-btn" onclick="window.location.href='index.php'">⬅ Kembali</button>
  </form>

  <p>Belum punya akun? <a href="register.php">Daftar</a></p>
</body>
</html>
