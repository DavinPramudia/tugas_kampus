<?php
include 'koneksi.php';

// ================= AMBIL DATA SURAT =================
if (!isset($_GET['id'])) {
    echo "<script>alert('ID surat tidak ditemukan!'); window.location='index.php?page=penyuratan';</script>";
    exit;
}

$id = intval($_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM penyuratan WHERE id = '$id'");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data surat tidak ditemukan!'); window.location='index.php?page=penyuratan';</script>";
    exit;
}

// ================= SIMPAN PERUBAHAN =================
if (isset($_POST['simpan'])) {
    $nama       = htmlspecialchars($_POST['nama']);
    $dusun      = htmlspecialchars($_POST['dusun']);
    $no_hp      = htmlspecialchars($_POST['no_hp']);
    $keperluan  = htmlspecialchars($_POST['keperluan']);

    // ====== UPLOAD FOTO KTP ======
    $foto_ktp = $data['foto_ktp'];
    if (!empty($_FILES['foto_ktp']['name'])) {
        $target_dir = "../upload/";
        $nama_file = time() . "_" . basename($_FILES["foto_ktp"]["name"]);
        $target_file = $target_dir . $nama_file;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (in_array($file_type, ['jpg','jpeg','png'])) {
            move_uploaded_file($_FILES["foto_ktp"]["tmp_name"], $target_file);
            $foto_ktp = $nama_file;
        }
    }

    // ====== UPLOAD FOTO KK ======
    $foto_kk = $data['foto_kk'];
    if (!empty($_FILES['foto_kk']['name'])) {
        $target_dir = "../upload/";
        $nama_file = time() . "_" . basename($_FILES["foto_kk"]["name"]);
        $target_file = $target_dir . $nama_file;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (in_array($file_type, ['jpg','jpeg','png'])) {
            move_uploaded_file($_FILES["foto_kk"]["tmp_name"], $target_file);
            $foto_kk = $nama_file;
        }
    }

    // ====== EKSEKUSI UPDATE ======
    $update = mysqli_query($koneksi, "UPDATE penyuratan SET 
        nama='$nama',
        dusun='$dusun',
        no_hp='$no_hp',
        keperluan='$keperluan',
        foto_ktp='$foto_ktp',
        foto_kk='$foto_kk'
        WHERE id='$id'");

    if ($update) {
        echo "<script>alert('Data surat berhasil diperbarui'); window.location='index.php?page=penyuratan';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data surat');</script>";
    }
}
?>

<h4 class="mt-4 mb-3">Edit Data Surat</h4>

<form method="POST" enctype="multipart/form-data">
  <div class="mb-3">
    <label>Nama</label>
    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" required>
  </div>

  <div class="mb-3">
    <label>Dusun</label>
    <input type="text" name="dusun" class="form-control" value="<?= htmlspecialchars($data['dusun']) ?>" required>
  </div>

  <div class="mb-3">
    <label>No. HP</label>
    <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($data['no_hp']) ?>" required>
  </div>

  <div class="mb-3">
    <label>Keperluan</label>
    <textarea name="keperluan" class="form-control" required><?= htmlspecialchars($data['keperluan']) ?></textarea>
  </div>

  <div class="mb-3">
    <label>Foto KTP <small class="text-muted">(opsional, JPG/PNG)</small></label><br>
    <?php if(!empty($data['foto_ktp'])): ?>
      <img src="../upload/<?= htmlspecialchars($data['foto_ktp']) ?>" style="width:100px; height:auto; margin-bottom:5px;"><br>
    <?php endif; ?>
    <input type="file" name="foto_ktp" class="form-control" accept=".jpg,.jpeg,.png">
  </div>

  <div class="mb-3">
    <label>Foto KK <small class="text-muted">(opsional, JPG/PNG)</small></label><br>
    <?php if(!empty($data['foto_kk'])): ?>
      <img src="../upload/<?= htmlspecialchars($data['foto_kk']) ?>" style="width:100px; height:auto; margin-bottom:5px;"><br>
    <?php endif; ?>
    <input type="file" name="foto_kk" class="form-control" accept=".jpg,.jpeg,.png">
  </div>

  <button type="submit" name="simpan" class="btn btn-primary">Simpan Perubahan</button>
  <a href="index.php?page=penyuratan" class="btn btn-secondary">Kembali</a>
</form>
