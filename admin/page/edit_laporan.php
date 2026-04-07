<?php
// ================= KONEKSI DATABASE =================
include 'koneksi.php';

// ================= AMBIL DATA LAPORAN =================
if (!isset($_GET['id'])) {
  echo "<script>alert('ID laporan tidak ditemukan!'); window.location='index.php?page=laporan_rakyat';</script>";
  exit;
}

$id = intval($_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM laporan WHERE id = '$id'");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
  echo "<script>alert('Data laporan tidak ditemukan!'); window.location='index.php?page=laporan_rakyat';</script>";
  exit;
}

// ================= SIMPAN PERUBAHAN =================
if (isset($_POST['simpan'])) {
  $nama      = htmlspecialchars($_POST['nama']);
  $deskripsi = htmlspecialchars($_POST['deskripsi']);

  $update = mysqli_query($koneksi, "UPDATE laporan SET 
      nama='$nama',
      email='$email',
      deskripsi='$deskripsi'
      WHERE id='$id'");

  if ($update) {
    echo "<script>alert('Data laporan berhasil diperbarui'); window.location='index.php?page=laporan_rakyat';</script>";
  } else {
    echo "<script>alert('Gagal memperbarui data laporan');</script>";
  }
}
?>

<!-- ================= FORM EDIT LAPORAN ================= -->
<h4 class="mt-4 mb-3">Edit Data Laporan</h4>

<form method="POST">
  <div class="mb-3">
    <label>Nama</label>
    <input type="text" name="nama" class="form-control" 
           value="<?= htmlspecialchars($data['nama']) ?>" required>
  </div>

  <div class="mb-3">
    <label>Deskripsi</label>
    <textarea name="deskripsi" class="form-control" rows="6" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
  </div>

  <button type="submit" name="simpan" class="btn btn-primary">Simpan Perubahan</button>
  <a href="index.php?page=laporan_rakyat" class="btn btn-secondary">Kembali</a>
</form>
