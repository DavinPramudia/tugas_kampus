<?php
include './config/koneksi.php';

if (isset($_POST['kirim'])) {
    $nama = $_POST['nama'];
    $no_hp = $_POST['no_hp'];
    $dusun = $_POST['dusun'];
    $keperluan = $_POST['keperluan'];

    $uploadDir = "upload/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    // Upload KTP
    $foto_ktp = $_FILES['foto_ktp']['name'];
    $tmp_ktp = $_FILES['foto_ktp']['tmp_name'];
    $newKTP = null;
    if (!empty($foto_ktp)) {
        $ext = strtolower(pathinfo($foto_ktp, PATHINFO_EXTENSION));
        $newKTP = uniqid("ktp_", true) . "." . $ext;
        move_uploaded_file($tmp_ktp, $uploadDir . $newKTP);
    }

    // Upload KK
    $foto_kk = $_FILES['foto_kk']['name'];
    $tmp_kk = $_FILES['foto_kk']['tmp_name'];
    $newKK = null;
    if (!empty($foto_kk)) {
        $ext = strtolower(pathinfo($foto_kk, PATHINFO_EXTENSION));
        $newKK = uniqid("kk_", true) . "." . $ext;
        move_uploaded_file($tmp_kk, $uploadDir . $newKK);
    }

    // Simpan ke database
    $query = mysqli_query($koneksi, "INSERT INTO penyuratan (id_user, nama, no_hp, dusun, foto_ktp, foto_kk, keperluan)
                                     VALUES (1, '$nama', '$no_hp', '$dusun', '$newKTP', '$newKK', '$keperluan')");

    if ($query) {
        $last_id = mysqli_insert_id($koneksi);
        echo "<script>alert('Surat berhasil diajukan!'); window.location='status_penyuratan.php?id=$last_id';</script>";
    } else {
        echo "<script>alert('Gagal mengajukan surat!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kirim Surat | Desa Baturusa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
  <div class="card shadow-sm p-4">
    <h3 class="mb-3 text-primary fw-bold text-center">Form Permohonan Surat</h3>
    <p class="text-muted text-center mb-4">Silakan isi data surat dengan lengkap.</p>
    <form method="POST" enctype="multipart/form-data">

      <div class="mb-3">
        <label class="form-label fw-semibold">Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama Anda" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Nomor HP</label>
        <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 08123456789" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Dusun</label>
        <select name="dusun" class="form-select" required>
          <option value="">-- Pilih Dusun --</option>
          <option>Dusun 1</option>
          <option>Dusun 2</option>
          <option>Dusun 3</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Foto KTP</label>
        <input type="file" name="foto_ktp" class="form-control" accept="image/*">
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Foto KK</label>
        <input type="file" name="foto_kk" class="form-control" accept="image/*">
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Keperluan Surat</label>
        <textarea name="keperluan" rows="4" class="form-control" placeholder="Tuliskan keperluan surat..." required></textarea>
      </div>

      <button type="submit" name="kirim" class="btn btn-primary w-100">Ajukan Surat</button>
    </form>
  </div>
</div>

</body>
</html>
