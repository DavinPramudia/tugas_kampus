<?php
// ================= KONEKSI DATABASE =================
include 'koneksi.php';

// ================= AMBIL DATA BERITA =================
if (!isset($_GET['id'])) {
  echo "<script>alert('ID berita tidak ditemukan!'); window.location='index.php?page=berita';</script>";
  exit;
}

$id = intval($_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM berita WHERE id = '$id'");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
  echo "<script>alert('Data berita tidak ditemukan!'); window.location='index.php?page=berita';</script>";
  exit;
}

// ================= SIMPAN PERUBAHAN =================
if (isset($_POST['simpan'])) {
  $judul = htmlspecialchars($_POST['judul']);
  $isi   = htmlspecialchars($_POST['isi']);

  // ====== PROSES GAMBAR BARU (JIKA ADA) ======
  if (!empty($_FILES['gambar']['name'])) {
    $namaFile = $_FILES['gambar']['name'];
    $tmpFile  = $_FILES['gambar']['tmp_name'];
    $folder   = './assets/images/berita/';
    $pathBaru = $folder . $namaFile;

    // Hapus gambar lama jika ada
    if ($data['gambar'] && file_exists($folder . $data['gambar'])) {
      unlink($folder . $data['gambar']);
    }

    // Pindahkan file baru
    move_uploaded_file($tmpFile, $pathBaru);

    // Update dengan gambar baru
    $sql = mysqli_query($koneksi, "UPDATE berita SET 
              judul='$judul',
              isi='$isi',
              gambar='$namaFile'
            WHERE id='$id'");
  } else {
    // Update tanpa ubah gambar
    $sql = mysqli_query($koneksi, "UPDATE berita SET 
              judul='$judul',
              isi='$isi'
            WHERE id='$id'");
  }

  if ($sql) {
    echo "<script>alert('Data berita berhasil diperbarui'); window.location='index.php?page=berita';</script>";
  } else {
    echo "<script>alert('Gagal memperbarui data berita');</script>";
  }
}
?>

<!-- ================= FORM EDIT BERITA ================= -->
<h4 class="mt-4 mb-3">Edit Berita</h4>

<form method="POST" enctype="multipart/form-data">
  <div class="mb-3">
    <label>Judul</label>
    <input type="text" name="judul" class="form-control" 
           value="<?= htmlspecialchars($data['judul']) ?>" required>
  </div>

  <div class="mb-3">
    <label>Isi Berita</label>
    <textarea name="isi" class="form-control" rows="6" required><?= htmlspecialchars($data['isi']) ?></textarea>
  </div>

  <div class="mb-3">
    <label>Gambar Saat Ini</label><br>
    <?php if ($data['gambar']) { ?>
      <img src="./assets/images/berita/<?= htmlspecialchars($data['gambar']) ?>" width="150" class="rounded shadow">
    <?php } else { ?>
      <p class="text-muted fst-italic">Tidak ada gambar</p>
    <?php } ?>
  </div>

  <div class="mb-3">
    <label>Ganti Gambar (jika perlu)</label>
    <input type="file" name="gambar" class="form-control">
  </div>

  <button type="submit" name="simpan" class="btn btn-primary">Simpan Perubahan</button>
  <a href="index.php?page=berita" class="btn btn-secondary">Kembali</a>
</form>
