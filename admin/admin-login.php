<?php
session_start();
include './assets/config/koneksi.php';

if (isset($_POST['submit'])) {
  $username = mysqli_real_escape_string($koneksi, $_POST['username']);
  $password = md5($_POST['password']);

  $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
  $data = mysqli_fetch_assoc($query);

  if ($data) {
    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];
    echo "<script>window.location='index.php';</script>";
    exit;
  } else {
    echo "<script>alert('Username atau password salah!');</script>";
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login</title>
  <link rel="shortcut icon" type="image/png" href="./assets/images/Logo Batu Rusa.jpg" />
  <link rel="stylesheet" href="./assets/css/styles.min.css" />
</head>
<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="../index.php" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="./assets/images/Logo Batu Rusa.jpg" alt="" width="45">
                </a>
                <p class="text-center">Login Admin / Kades</p>
                <form method="post" action="">
                  <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="username" required>
                  </div>
                  <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                  </div>
                  <input type="submit" name="submit" value="Login" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="./assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="./assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
