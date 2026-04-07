<?php
session_start();

if (!isset($_SESSION['login'])) {
    $_SESSION['pesan'] = "Silakan login terlebih dahulu untuk membuat surat.";
    header("Location: login.php?redirect=penyuratan.php");
    exit;
}

include 'admin/koneksi.php';

// ==== CEK APAKAH USER SUDAH PUNYA SURAT AKTIF ====
$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    $cek_surat = mysqli_query($koneksi, "
      SELECT id FROM penyuratan 
      WHERE user_id = '$user_id' 
      AND status NOT IN ('Selesai', 'Ditolak')
      LIMIT 1
    ");

    if (mysqli_num_rows($cek_surat) > 0) {
        $data = mysqli_fetch_assoc($cek_surat);
        $surat_id = $data['id'];

        echo "<script>
            alert('Anda masih memiliki surat yang sedang diproses. Silakan pantau statusnya.');
            window.location = 'status_penyuratan.php?id=$surat_id';
        </script>";
        exit;
    }
}

$nama_session = $_SESSION['nama'] ?? '';
$uploadDir = "upload/";
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

// ==== PROSES KIRIM PENYURATAN ====
if (isset($_POST['kirim'])) {
    $nama = trim($_POST['nama']);
    $no_hp = trim($_POST['no_hp']);
    $dusun = trim($_POST['dusun']);
    $keperluan = trim($_POST['keperluan']);

    $errors = [];

    // Validasi nomor HP
    if (!preg_match("/^[0-9]{10,13}$/", $no_hp)) {
        $errors[] = "Nomor HP harus 10–13 digit angka.";
    }

    // Validasi keperluan minimal 10 karakter
    if (strlen($keperluan) < 10) {
        $errors[] = "Keperluan minimal 10 karakter.";
    }

    // Validasi wajib upload foto
    if (empty($_FILES['foto_ktp']['name']) || empty($_FILES['foto_kk']['name'])) {
        $errors[] = "Foto KTP dan KK wajib di-upload.";
    }

    if (count($errors) > 0) {
        $msg = implode('\n', $errors);
        echo "<script>alert('$msg'); window.history.back();</script>";
        exit;
    }

    // Upload KTP
    $ext = strtolower(pathinfo($_FILES['foto_ktp']['name'], PATHINFO_EXTENSION));
    $newKTP = uniqid("ktp_", true) . "." . $ext;
    move_uploaded_file($_FILES['foto_ktp']['tmp_name'], $uploadDir . $newKTP);

    // Upload KK
    $ext2 = strtolower(pathinfo($_FILES['foto_kk']['name'], PATHINFO_EXTENSION));
    $newKK = uniqid("kk_", true) . "." . $ext2;
    move_uploaded_file($_FILES['foto_kk']['tmp_name'], $uploadDir . $newKK);

    // Simpan ke database
    $query = mysqli_query($koneksi, "
        INSERT INTO penyuratan (user_id, nama, no_hp, dusun, foto_ktp, foto_kk, keperluan, status)
        VALUES ('$user_id', '$nama', '$no_hp', '$dusun', '$newKTP', '$newKK', '$keperluan', 'Menunggu Verifikasi')
    ");


    if ($query) {
        $last_id = mysqli_insert_id($koneksi);
        echo "<script>alert('Surat berhasil diajukan!'); window.location='status_penyuratan.php?id=$last_id';</script>";
        exit;
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
<title>Penyuratan | Desa Baturusa</title>
<base href="http://localhost/web_baturusa/">
<link rel="stylesheet" href="/web_baturusa/css/style.css">
</head>
<body>

<!-- ================= HEADER ================= -->
<header>
  <div class="tulisan_desa"><h1>Desa Baturusa</h1></div>
  <div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>
  <ul class="menu">
    <li><a href="index.php">BERANDA</a></li>
    <li class="dropdown">
      <a>TENTANG <span class="arrow">&#9662;</span></a>
      <ul class="dropdown-konten">
        <li><a href="sejarah.php">SEJARAH</a></li>
        <li><a href="visi_misi.php">VISI & MISI</a></li>
        <li><a href="aparatur.php">APARATUR DESA</a></li>
        <li><a href="struktur.php">STRUKTUR ORGANISASI</a></li>
        <li><a href="peta.php">PETA</a></li>
        <li><a href="geografis.php">GEOGRAFIS</a></li>
      </ul>
    </li>
    <li><a href="berita.php">BERITA</a></li>
    <li><a href="umkm.php">UMKM</a></li>
    <li class="dropdown">
      <a>LAYANAN <span class="arrow">&#9662;</span></a>
      <ul class="dropdown-konten">
        <li><a href="pengaduan.php">PENGADUAN</a></li>
        <li><a href="penyuratan.php">SURAT</a></li>
        <li><a href="history.php">HISTORI</a></li>
      </ul>
    </li>
    <?php if (isset($_SESSION['login'])): ?>
      <li><a href="logout.php" onclick="return confirm('Yakin ingin keluar?')">LOGOUT</a></li>
    <?php else: ?>
      <li><a href="login.php" target="_blank">LOGIN</a></li>
    <?php endif; ?>
  </ul>
</header>

<main>
<div class="page-title" style="background-image: url('./image/page_title_2.jpg');">
  <div class="container">
    <h1>Penyuratan</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="index.php">Beranda</a></li>
        <li class="current">Penyuratan</li>
      </ol>
    </nav>
  </div>
</div>

<section class="berita-section">
  <div class="berita-kiri">
    <article class="pengaduan-konten">
      <h2 class="pengaduan-judul">Form Permohonan Surat</h2>
      <p class="pengaduan-deskripsi">
        Ajukan permohonan surat sesuai kebutuhan Anda. Kolom bertanda * wajib diisi.
      </p>

      <form class="pengaduan-form" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
        <div class="pengaduan-group">
          <label class="pengaduan-label">Nama Lengkap *</label>
          <input name="nama" type="text" class="pengaduan-input" value="<?= htmlspecialchars($nama_session) ?>" readonly required>
        </div>

        <div class="pengaduan-group">
          <label class="pengaduan-label">Nomor HP *</label>
          <input name="no_hp" type="text" class="pengaduan-input" placeholder="Contoh: 08123456789" required minlength="10">
        </div>

        <div class="pengaduan-group">
          <label class="pengaduan-label">Dusun *</label>
          <select name="dusun" class="pengaduan-select" required>
            <option value="">-- Pilih Dusun --</option>
            <option value="Dusun 1">Dusun 1</option>
            <option value="Dusun 2">Dusun 2</option>
            <option value="Dusun 3">Dusun 3</option>
          </select>
        </div>

        <div class="pengaduan-group">
          <label class="pengaduan-label">Foto KTP *</label>
          <input name="foto_ktp" type="file" class="pengaduan-file" accept="image/*" required>
        </div>

        <div class="pengaduan-group">
          <label class="pengaduan-label">Foto KK *</label>
          <input name="foto_kk" type="file" class="pengaduan-file" accept="image/*" required>
        </div>

        <div class="pengaduan-group">
          <label class="pengaduan-label">Keperluan Surat *</label>
          <textarea name="keperluan" class="pengaduan-textarea" placeholder="Tuliskan keperluan surat..." required minlength="10"></textarea>
        </div>

        <div class="pengaduan-actions">
          <button type="submit" name="kirim" class="pengaduan-btn">Ajukan Surat</button>
          <button type="reset" class="pengaduan-btn pengaduan-btn-reset">Reset</button>
        </div>
      </form>
    </article>
  </div>

  <div class="berita-kanan">
    <div class="sidebar-item">
      <h3 class="widget-title">Berita Terdahulu</h3>
      <div class="sidebar-berita">
        <?php
        $sidebar = mysqli_query($koneksi, "SELECT * FROM berita ORDER BY id DESC LIMIT 6");
        while ($s = mysqli_fetch_assoc($sidebar)):
        ?>
        <a href="berita_detail.php?id=<?php echo $s['id']; ?>" class="sidebar-berita-item-link">
          <div class="sidebar-berita-item">
            <img src="admin/assets/images/berita/<?php echo htmlspecialchars($s['gambar']); ?>" alt="Berita Lama">
            <div class="sidebar-isi">
              <h4><?php echo htmlspecialchars($s['judul']); ?></h4>
              <small><?php echo date('d M Y', strtotime($s['tanggal'] ?? 'now')); ?></small>
            </div>
          </div>
        </a>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</section>

<a href="https://wa.me/6285371336779" target="_blank" class="floating-cta-btn">Hubungi Kami</a>
<a href="#" class="back-to-top-btn" id="backToTop">^</a>

<footer class="footer">
  <div class="footer-kotak">
    <div class="footer-kolom">
      <img src="image/logo baturusa.jpg" alt="Logo Desa" class="footer-logo">
    </div>
    <div class="footer-kolom">
      <h4>Pemerintah Desa Baturusa</h4>
      <p>
        Jalan Raya Sungailiat - Pangkalpinang<br>
        Kecamatan Merawang<br>
        Kabupaten Bangka<br>
        Provinsi Kepulauan Bangka Belitung<br>
        Kode Pos 33172<br>
      </p>
    </div>
    <div class="footer-kolom">
      <h4>Hubungi Kami</h4>
      <p>📞 0853-7133-6779</p>
      <p>✉ <a href="mailto:desa@gmail.com">pemdesbaturusa5@gmail.com</a></p>
    </div>
    <div class="footer-kolom">
      <h4>Lokasi Desa</h4>
      <div class="footer-maps">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.373870884327!2d106.145997!3d-2.115732!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e22a9e8b8f693f5%3A0x8f2c6e6ef29d6d8!2sDesa%20Baturusa%2C%20Kabupaten%20Bangka!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" width="100%" height="200" style="border:0; border-radius: 8px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </div>
  <div class="footer-bawah">
    <p>&copy; 2025 Pemerintah Desa Baturusa | Powered by Okki Darmawan &amp; Davin Pramudia</p>
  </div>
</footer>

<script src="js/script.js"></script>
<script>
// Back to top
document.addEventListener("DOMContentLoaded", function() {
    const backToTopBtn = document.getElementById("backToTop");
    if (!backToTopBtn) return;
    window.addEventListener("scroll", function() {
        if (window.scrollY > 200) backToTopBtn.classList.add("show");
        else backToTopBtn.classList.remove("show");
    });
    backToTopBtn.addEventListener("click", function(e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
});
</script>
</body>
</html>
