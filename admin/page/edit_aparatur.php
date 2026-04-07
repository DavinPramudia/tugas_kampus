<?php
// ================= KONEKSI DATABASE =================
include 'koneksi.php';

// ================= AMBIL DATA APARATUR =================
if (!isset($_GET['id'])) {
  echo "<script>alert('ID aparatur tidak ditemukan!'); window.location='index.php?page=aparatur';</script>";
  exit;
}

$id = intval($_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM aparatur WHERE id = '$id'");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
  echo "<script>alert('Data aparatur tidak ditemukan!'); window.location='index.php?page=aparatur';</script>";
  exit;
}

// ================= SIMPAN PERUBAHAN =================
if (isset($_POST['simpan'])) {
  $nama                 = htmlspecialchars($_POST['nama']);
  $jabatan              = htmlspecialchars($_POST['jabatan']);
  $tempat_tanggal_lahir = htmlspecialchars($_POST['tempat_tanggal_lahir']);
  $masa_jabatan         = htmlspecialchars($_POST['masa_jabatan']);

  // ====== PROSES FOTO BARU (JIKA ADA) ======
  if (!empty($_FILES['foto']['name'])) {
    $namaFile = $_FILES['foto']['name'];
    $tmpFile  = $_FILES['foto']['tmp_name'];
    $folder   = './assets/images/aparatur/';
    $pathBaru = $folder . $namaFile;

    // Hapus foto lama jika ada
    if ($data['foto'] && file_exists($folder . $data['foto'])) {
      unlink($folder . $data['foto']);
    }

    // Pindahkan file baru
    move_uploaded_file($tmpFile, $pathBaru);

    // Update dengan foto baru
    $sql = mysqli_query($koneksi, "UPDATE aparatur SET 
              nama='$nama',
              jabatan='$jabatan',
              tempat_tanggal_lahir='$tempat_tanggal_lahir',
              masa_jabatan='$masa_jabatan',
              foto='$namaFile'
            WHERE id='$id'");
  } else {
    // Update tanpa ubah foto
    $sql = mysqli_query($koneksi, "UPDATE aparatur SET 
              nama='$nama',
              jabatan='$jabatan',
              tempat_tanggal_lahir='$tempat_tanggal_lahir',
              masa_jabatan='$masa_jabatan'
            WHERE id='$id'");
  }

  if ($sql) {
    echo "<script>alert('Data aparatur berhasil diperbarui'); window.location='index.php?page=aparatur';</script>";
  } else {
    echo "<script>alert('Gagal memperbarui data aparatur');</script>";
  }
}
?>

<!-- ================= FORM EDIT APARATUR ================= -->
<h4 class="mt-4 mb-3">Edit Data Aparatur</h4>

<form method="POST" enctype="multipart/form-data">
  <div class="mb-3">
    <label>Nama</label>
    <input type="text" name="nama" class="form-control" 
           value="<?= htmlspecialchars($data['nama']) ?>" required>
  </div>

  <div class="mb-3">
    <label>Jabatan</label>
    <input type="text" name="jabatan" class="form-control" 
           value="<?= htmlspecialchars($data['jabatan']) ?>" required>
  </div>

  <div class="mb-3">
    <label>Tempat, Tanggal Lahir</label>
    <input type="text" name="tempat_tanggal_lahir" class="form-control" 
           value="<?= htmlspecialchars($data['tempat_tanggal_lahir']) ?>" required>
  </div>

  <div class="mb-3">
    <label>Masa Jabatan</label>
    <input type="text" name="masa_jabatan" class="form-control" 
           value="<?= htmlspecialchars($data['masa_jabatan']) ?>" required>
  </div>

  <div class="mb-3">
    <label>Foto Saat Ini</label><br>
    <?php if ($data['foto']) { ?>
      <img src="./assets/images/aparatur/<?= htmlspecialchars($data['foto']) ?>" width="150" class="rounded shadow">
    <?php } else { ?>
      <p class="text-muted fst-italic">Tidak ada foto</p>
    <?php } ?>
  </div>

  <div class="mb-3">
    <label>Ganti Foto (jika perlu)</label>
    <input type="file" name="foto" class="form-control">
  </div>

  <button type="submit" name="simpan" class="btn btn-primary">Simpan Perubahan</button>
  <a href="index.php?page=aparatur" class="btn btn-secondary">Kembali</a>
</form>
