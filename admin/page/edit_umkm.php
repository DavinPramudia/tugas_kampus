<?php
// ================= KONEKSI DATABASE =================
include 'koneksi.php';

// ================= AMBIL DATA UMKM =================
if (!isset($_GET['id'])) {
  echo "<script>alert('ID UMKM tidak ditemukan!'); window.location='index.php?page=umkm';</script>";
  exit;
}

$id = intval($_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM umkm WHERE id = '$id'");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
  echo "<script>alert('Data UMKM tidak ditemukan!'); window.location='index.php?page=umkm';</script>";
  exit;
}

// ================= SIMPAN PERUBAHAN =================
if (isset($_POST['simpan'])) {
  $judul = htmlspecialchars($_POST['judul']);
  $isi   = htmlspecialchars($_POST['isi']);

  // ====== CEK JIKA GANTI GAMBAR ======
  if ($_FILES['gambar']['name']) {
    $namaFile = $_FILES['gambar']['name'];
    $tmpFile  = $_FILES['gambar']['tmp_name'];
    $folder   = './assets/images/umkm/';
    $pathBaru = $folder . $namaFile;

    // Hapus gambar lama
    if ($data['gambar'] && file_exists($folder . $data['gambar'])) {
      unlink($folder . $data['gambar']);
    }

    // Upload baru
    move_uploaded_file($tmpFile, $pathBaru);

    $update = mysqli_query($koneksi, "UPDATE umkm SET 
        judul='$judul',
        isi='$isi',
        gambar='$namaFile'
        WHERE id='$id'");
  } else {
    $update = mysqli_query($koneksi, "UPDATE umkm SET 
        judul='$judul',
        isi='$isi'
        WHERE id='$id'");
  }

  if ($update) {
    echo "<script>alert('Data UMKM berhasil diperbarui'); window.location='index.php?page=umkm';</script>";
  } else {
    echo "<script>alert('Gagal memperbarui data UMKM');</script>";
  }
}
?>

<!-- ================= FORM EDIT UMKM ================= -->
<h4 class="mt-4 mb-3">Edit Data UMKM</h4>

<form method="POST" enctype="multipart/form-data">
  <div class="mb-3">
    <label>Judul</label>
    <input type="text" name="judul" class="form-control" 
           value="<?= htmlspecialchars($data['judul']) ?>" required>
  </div>

  <div class="mb-3">
    <label>Isi UMKM</label>
    <textarea name="isi" class="form-control" rows="6" required><?= htmlspecialchars($data['isi']) ?></textarea>
  </div>

  <div class="mb-3">
    <label>Gambar Saat Ini</label><br>
    <?php if ($data['gambar']) : ?>
      <img src="./assets/images/umkm/<?= htmlspecialchars($data['gambar']) ?>" width="150">
    <?php else : ?>
      <p><i>Tidak ada gambar</i></p>
    <?php endif; ?>
  </div>

  <div class="mb-3">
    <label>Ganti Gambar (jika perlu)</label>
    <input type="file" name="gambar" class="form-control">
  </div>

  <button type="submit" name="simpan" class="btn btn-primary">Simpan Perubahan</button>
  <a href="index.php?page=umkm" class="btn btn-secondary">Kembali</a>
</form>
