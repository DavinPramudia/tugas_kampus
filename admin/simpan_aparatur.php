<?php
include 'koneksi.php'; // pastikan path benar, sesuaikan jika di folder lain

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
  $jabatan = mysqli_real_escape_string($koneksi, $_POST['jabatan']);
  $tempat_tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tempat_tanggal_lahir']);
  $masa_jabatan = mysqli_real_escape_string($koneksi, $_POST['masa_jabatan']);

  // Pastikan folder upload ada
  $folder = "./assets/images/aparatur/"; // sesuaikan nama folder kamu
  if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
  }

  $namaFile = $_FILES['foto']['name'];
  $tmpFile = $_FILES['foto']['tmp_name'];
  $path = $folder . basename($namaFile);

  // Cek apakah ada file yang diupload
  if (!empty($namaFile)) {
    if (move_uploaded_file($tmpFile, $path)) {
      $query = "INSERT INTO aparatur (nama, jabatan, tempat_tanggal_lahir, masa_jabatan, foto)
                VALUES ('$nama', '$jabatan', '$tempat_tanggal_lahir', '$masa_jabatan', '$namaFile')";
      $result = mysqli_query($koneksi, $query);

      if ($result) {
        echo "<script>alert('Data aparatur berhasil disimpan'); window.location='index.php?page=aparatur';</script>";
      } else {
        echo "<script>alert('Gagal menyimpan ke database: " . mysqli_error($koneksi) . "'); window.history.back();</script>";
      }
    } else {
      echo "<script>alert('Gagal mengupload foto'); window.history.back();</script>";
    }
  } else {
    echo "<script>alert('Silakan pilih foto aparatur'); window.history.back();</script>";
  }
}
?>
