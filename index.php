<?php

session_start();
$username = $_SESSION['username']??null;

include 'admin/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Desa Baturusa</title>
<base href="http://localhost/web_baturusa/">
<link rel="stylesheet" href="/web_baturusa/css/style.css">

<style></style>

</head>

<body>

 <!-- ================= HEADER ================= -->
<header>
  <div class="tulisan_desa">
    <h1>Desa Baturusa</h1>
  </div>

  <!-- Tombol Hamburger (muncul di HP) -->
  <div class="hamburger" id="hamburger">
    <span></span>
    <span></span>
    <span></span>
  </div>

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

    <!-- ================= HERO SECTION ================= -->
    <section class="hero-slider">
      <div class="slides">

        <div class="slide active" style="background-image: url('./image/Jembatan.jpg');"></div>
          <div class="overlay">
            <h1>Selamat Datang di Desa Baturusa</h1>
            <p>Desa yang asri dan bersahabat</p>
          </div>
        </div>

        <div class="slide" style="background-image: url('./image/sun.jpg')">
          <div class="overlay">
            <h1>Budaya & Alam yang Indah</h1>
            <p>Menjaga tradisi dan lingkungan</p>
          </div>
        </div>

        <div class="slide" style="background-image: url('./image/perahu.jpg')">
          <div class="overlay">
            <h1>Menuju Desa Digital</h1>
            <p>Inovasi dan pelayanan masyarakat</p>
          </div>
        </div>

      </div>

      <!-- Tombol navigasi -->
      <button class="prev">&#10094;</button>
      <button class="next">&#10095;</button>
    </section>


    <!-- ================= Tentang Desa Baturusa ================= -->
    <section class="tentang-singkat">
      <h2>Tentang Desa Baturusa</h2>
      <p class="deskripsi">
        Sekilas tentang Desa Baturusa, desa yang penuh potensi dan semangat gotong royong masyarakatnya.
      </p>

      <div class="card-container">

        <div class="card">
          <h3>Sejarah Desa</h3>
          <p>Kenali sejarah dan perkembangan Desa Baturusa</p>
          <a href="sejarah.php" class="btn-card">Lihat Selengkapnya</a>
        </div>

        <div class="card">
          <h3>Visi & Misi</h3>
          <p>Mengetahui arah dan tujuan pembangunan desa kami.</p>
          <a href="visi_misi.php" class="btn-card">Lihat Selengkapnya</a>
        </div>

        <div class="card">
          <h3>Struktur Organisasi</h3>
          <p>Kenali susunan perangkat desa yang siap melayani masyarakat.</p>
          <a href="struktur.php" class="btn-card">Lihat Selengkapnya</a>
        </div>

        <div class="card">
          <h3>Geografi</h3>
          <p>Pelajari letak geografis dan kondisi alam Desa Baturusa.</p>
          <a href="geografis.php" class="btn-card">Lihat Selengkapnya</a>
        </div>

      </div>
    </section>


    <!-- ================= UMKM ================= -->
<section class="berita-singkat">
  <h2>UMKM Desa Baturusa</h2>
  <p class="deskripsi">
    UMKM Warga Desa Baturusa.
  </p>

  <div class="berita-singkat-container">
    <?php
    include 'admin/koneksi.php';

    // Ambil 3 berita terbaru
    $berita = mysqli_query($koneksi, "SELECT * FROM umkm ORDER BY id DESC LIMIT 3");

    if (mysqli_num_rows($berita) > 0) {
      while ($row = mysqli_fetch_assoc($berita)) {
    ?>
        <div class="berita-singkat-card">
          <img src="admin/assets/images/umkm/<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['judul']); ?>">
          <div class="berita-singkat-content">
            <h3><?= htmlspecialchars($row['judul']); ?></h3>
            <p><?= substr(strip_tags($row['isi']), 0, 100); ?>...</p>
            <a href="umkm_detail.php?id=<?= $row['id']; ?>" class="btn-berita-singkat">Baca Selengkapnya</a>
          </div>
        </div>
    <?php
      }
    } else {
      echo "<p>Tidak ada berita terbaru saat ini.</p>";
    }
    ?>
  </div>

  <!-- Tombol Lihat Semua Berita -->
  <div class="berita-singkat-btn-container">
    <a href="umkm.php" class="btn-berita-singkat-all">Lihat Semua UMKM</a>
  </div>
</section>

    <!-- ================= CALL TO ACTION ================= -->
    <section id="call-to-action" class="call-to-action section dark-background">
      <img src="./image/baturusa_1.jpg" alt="">
      <div class="container">
        <div class="row justify-content-center" data-aos="zoom-in" data-aos-delay="100">
          <div class="col-xl-10">
            <div class="text-center">
              <h3>Buat Laporan</h3>
              <p>Berikan atau sampaikan informasi yang ingin diungkapkan</p>
              <a class="cta-btn" href="pengaduan.php">Lapor</a>
            </div>
          </div>
        </div>
      </div>
    </section>


    <!-- ================= BERITA TERBARU ================= -->
<section class="berita-singkat">
  <h2>Berita Terbaru</h2>
  <p class="deskripsi">
    Dapatkan informasi terkini seputar kegiatan, pengumuman, dan berita Desa Baturusa.
  </p>

  <div class="berita-singkat-container">
    <?php
    include 'admin/koneksi.php';

    // Ambil 3 berita terbaru
    $berita = mysqli_query($koneksi, "SELECT * FROM berita ORDER BY id DESC LIMIT 3");

    if (mysqli_num_rows($berita) > 0) {
      while ($row = mysqli_fetch_assoc($berita)) {
    ?>
        <div class="berita-singkat-card">
          <img src="admin/assets/images/berita/<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['judul']); ?>">
          <div class="berita-singkat-content">
            <h3><?= htmlspecialchars($row['judul']); ?></h3>
            <p><?= substr(strip_tags($row['isi']), 0, 100); ?>...</p>
            <a href="berita_detail.php?id=<?= $row['id']; ?>" class="btn-berita-singkat">Baca Selengkapnya</a>
          </div>
        </div>
    <?php
      }
    } else {
      echo "<p>Tidak ada berita terbaru saat ini.</p>";
    }
    ?>
  </div>

  <!-- Tombol Lihat Semua Berita -->
  <div class="berita-singkat-btn-container">
    <a href="berita.php" class="btn-berita-singkat-all">Lihat Semua Berita</a>
  </div>
</section>

    <!-- ================= FLOATING BUTTONS ================= -->
    <a href="https://wa.me/6285371336779" target="_blank" class="floating-cta-btn">Hubungi Kami</a>
    <a href="#" class="back-to-top-btn" id="backToTop">^</a>

  </main>


  <!-- ================= FOOTER ================= -->
  <footer class="footer">
    <div class="footer-kotak">

      <!-- Kolom 1 -->
      <div class="footer-kolom">
        <img src="image/logo baturusa.jpg" alt="Logo Desa" class="footer-logo">
      </div>

      <!-- Kolom 2 -->
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

      <!-- Kolom 3 -->
      <div class="footer-kolom">
        <h4>Hubungi Kami</h4>
        <p>📞 0853-7133-6779</p>
        <p>✉ <a href="mailto:desa@gmail.com">pemdesbaturusa5@gmail.com</a></p>
      </div>

      <!-- Kolom 4 -->
      <div class="footer-kolom">
        <h4>Lokasi Desa</h4>
        <div class="footer-maps">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.373870884327!2d106.145997!3d-2.115732!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e22a9e8b8f693f5%3A0x8f2c6e6ef29d6d8!2sDesa%20Baturusa%2C%20Kabupaten%20Bangka!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid"
            width="100%"
            height="200"
            style="border:0; border-radius: 8px;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
      </div>

    </div>

    <!-- Footer Bawah -->
    <div class="footer-bawah">
      <p>&copy; 2025 Pemerintah Desa Baturusa | Powered by Okki Darmawan &amp; Davin Pramudia</p>
    </div>
  </footer>

  <script src="js/script.js"></script>

</body>
</html>
