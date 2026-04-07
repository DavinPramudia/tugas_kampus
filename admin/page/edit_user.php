<?php
// ================= KONEKSI DATABASE =================
include 'koneksi.php';

// ================= AMBIL DATA USER =================
if (!isset($_GET['id'])) {
  echo "<script>alert('ID user tidak ditemukan!'); window.location='index.php?page=user';</script>";
  exit;
}

$id = intval($_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM user WHERE id = '$id'");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
  echo "<script>alert('Data user tidak ditemukan!'); window.location='index.php?page=user';</script>";
  exit;
}

// ================= SIMPAN PERUBAHAN =================
if (isset($_POST['simpan'])) {
  $nik            = htmlspecialchars($_POST['nik']);
  $nama           = htmlspecialchars($_POST['nama']);
  $tempat         = htmlspecialchars($_POST['tempat']);
  $tanggal_lahir  = htmlspecialchars($_POST['tanggal_lahir']);
  $alamat         = htmlspecialchars($_POST['alamat']);
  $email          = htmlspecialchars($_POST['email']);
  $username       = trim($_POST['username']);
  $password_input = trim($_POST['password']); 

  // ====== VALIDASI PANJANG USERNAME ======
  if (strlen($username) < 5) {
    echo "<script>alert('Username minimal 5 karakter!');</script>";
    exit;
  }

  // ====== VALIDASI PANJANG PASSWORD (HANYA JIKA DIISI) ======
  if ($password_input !== '' && strlen($password_input) < 8) {
    echo "<script>alert('Password minimal 8 karakter!');</script>";
    exit;
  }

  // Jika admin mengisi password baru -> hash md5, kalau kosong -> pakai hash lama dari DB
  if ($password_input !== '') {
    $password_to_store = md5($password_input);
  } else {
    $password_to_store = $data['password'];
  }

  // ====== EKSEKUSI UPDATE ======
  $update = mysqli_query($koneksi, "UPDATE user SET 
      nik='$nik',
      nama='$nama',
      tempat='$tempat',
      tanggal_lahir='$tanggal_lahir',
      alamat='$alamat',
      email='$email',
      username='$username',
      password='$password_to_store'
      WHERE id='$id'");

  if ($update) {
    echo "<script>alert('Data user berhasil diperbarui'); window.location='index.php?page=user';</script>";
  } else {
    echo "<script>alert('Gagal memperbarui data user');</script>";
  }
}
?>

<!-- ================= FORM EDIT USER ================= -->
<h4 class="mt-4 mb-3">Edit Data User</h4>

<form method="POST">
  <div class="mb-3">
    <label>NIK</label>
    <input type="text" name="nik" class="form-control" 
           value="<?= htmlspecialchars($data['nik']) ?>" required>
  </div>

  <div class="mb-3">
    <label>Nama</label>
    <input type="text" name="nama" class="form-control" 
           value="<?= htmlspecialchars($data['nama']) ?>" required>
  </div>

  <div class="mb-3">
    <label>Tempat</label>
    <input type="text" name="tempat" class="form-control" 
           value="<?= htmlspecialchars($data['tempat']) ?>" required>
  </div>

  <div class="mb-3">
    <label>Tanggal Lahir</label>
    <input type="date" name="tanggal_lahir" class="form-control" 
           value="<?= htmlspecialchars($data['tanggal_lahir']) ?>" required>
  </div>

  <div class="mb-3">
    <label>Alamat</label>
    <textarea name="alamat" class="form-control" required><?= htmlspecialchars($data['alamat']) ?></textarea>
  </div>

  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" 
           value="<?= htmlspecialchars($data['email']) ?>" required>
  </div>

  <div class="mb-3">
    <label>Username <small class="text-muted">(minimal 5 karakter)</small></label>
    <input type="text" name="username" class="form-control" 
           value="<?= htmlspecialchars($data['username']) ?>" minlength="5" required>
  </div>

  <div class="mb-3">
    <label>Password <small class="text-muted">(kosongkan jika tidak ingin mengubah, minimal 8 karakter)</small></label>
    <input type="password" name="password" class="form-control" placeholder="Masukkan password baru jika ingin ganti" minlength="8">
  </div>

  <button type="submit" name="simpan" class="btn btn-primary">Simpan Perubahan</button>
  <a href="index.php?page=user" class="btn btn-secondary">Kembali</a>
</form>
