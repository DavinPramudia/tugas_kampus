<?php
include './assets/config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $judul = htmlspecialchars($_POST['judul']);
  $isi = htmlspecialchars($_POST['isi']);

  // Upload gambar
  $namaFile = $_FILES['gambar']['name'];
  $tmpFile = $_FILES['gambar']['tmp_name'];
  $folder = './assets/images/umkm/';
  $path = $folder . $namaFile;

  if (move_uploaded_file($tmpFile, $path)) {
    $query = "INSERT INTO umkm (judul, isi, gambar) VALUES ('$judul', '$isi', '$namaFile')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
      echo "<script>alert('Berita berhasil diposting'); window.location.href='index.php';</script>";    } else {
      echo "<script>alert('Gagal menyimpan ke database'); window.history.back();</script>";
    }
  } else {
    echo "<script>alert('Gagal upload gambar'); window.history.back();</script>";
  }
}
?>
