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
  <title>Geografis</title>
<base href="http://localhost/web_baturusa/">
<link rel="stylesheet" href="/web_baturusa/css/style.css"></head>

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

<!-- ================= Page Title ================= -->
  <div class="page-title" style="background-image: url('./image/page_title_1.jpg');">
  <div class="container">
    <h1>Geografis</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="index.html">Beranda</a></li>
        <li class="current">Geografis</li>
      </ol>
    </nav>
  </div>
</div>

<!-- ================= Konten + Sidebar ================= -->
<section class="umum-section">
  
  <!-- KIRI: konten teks -->
  <div class="umum-kiri">
    <article class="umum-konten-teks">
      <h2>Letak Geografis Desa Baturusa</h2>

      <p>
        Desa Baturusa merupakan salah satu desa di Kecamatan Merawang Kabupaten Bangka dengan luas wilayah sekitar <strong>1.675 Ha</strong>.
        Adapun batas-batas administrasinya adalah sebagai berikut:
      </p>

      <ul>
        <li>Sebelah Utara berbatasan dengan Desa Riding Panjang</li>
        <li>Sebelah Selatan berbatasan dengan Desa Pagarawan</li>
        <li>Sebelah Barat berbatasan dengan Desa Kimak</li>
        <li>Sebelah Timur berbatasan dengan Desa Air Anyir</li>
      </ul>

      <h3>Peta Kecamatan Merawang</h3>
      <img src="./image/Peta-Kecamatan-Merawang.jpg" alt="Peta Kecamatan Merawang" style="width:100%; border-radius:10px; margin-top:10px;">

      <p>
        Secara geografis, Desa Baturusa berbentuk jenis tanah perbukitan dataran rendah dengan kondisi tanah sedikit bergelombang,
        berjenis asosiasi podsolik coklat kekuningan dengan bahan induk kompleks batu pasir kwarsit dan batuan plitonik masam.
      </p>

      <h3>Peta Desa Baturusa</h3>
      <img src="./image/Peta-Desa-Baturusa.jpg" alt="Peta Desa Baturusa" style="width:100%; border-radius:10px; margin-top:10px;">

      <!-- ================= PETA SLIDESHOW SECTION ================= -->
        <section class="hero-slider">
          <div class="slides">

            <div class="slide active" style="background-image: url('./image/Peta-Dusun-Baturusa.jpg');">
              <div class="overlay">
                <h1>Peta Dusun I, II, III Desa Baturusa</h1>
                <p>Wilayah utama yang mencakup tiga dusun besar di Desa Baturusa</p>
              </div>
            </div>

            <div class="slide" style="background-image: url('./image/Peta-Rt-001-Desa-Baturusa.jpg');">
              <div class="overlay">
                <h1>Peta RT 001 Desa Baturusa</h1>
                <p>Wilayah RT 001 Desa Baturusa</p>
              </div>
            </div>

            <div class="slide" style="background-image: url('./image/Peta-Rt-002-Desa-Baturusa.jpg');">
              <div class="overlay">
                <h1>Peta RT 002 Desa Baturusa</h1>
                <p>Wilayah RT 002 Desa Baturusa</p>
              </div>
            </div>

            <div class="slide" style="background-image: url('./image/Peta-Rt-003-Desa-Baturusa.jpg');">
              <div class="overlay">
                <h1>Peta RT 003 Desa Baturusa</h1>
                <p>Wilayah RT 003 Desa Baturusa</p>
              </div>
            </div>

            <div class="slide" style="background-image: url('./image/Peta-Rt-004-Desa-Baturusa.jpg');">
              <div class="overlay">
                <h1>Peta RT 004 Desa Baturusa</h1>
                <p>Wilayah RT 004 Desa Baturusa</p>
              </div>
            </div>

            <div class="slide" style="background-image: url('./image/Peta-Rt-005-Desa-Baturusa.jpg');">
              <div class="overlay">
                <h1>Peta RT 005 Desa Baturusa</h1>
                <p>Wilayah RT 005 Desa Baturusa</p>
              </div>
            </div>

            <div class="slide" style="background-image: url('./image/Peta-Rt-006-Desa-Baturusa.jpg');">
              <div class="overlay">
                <h1>Peta RT 006 Desa Baturusa</h1>
                <p>Wilayah RT 006 Desa Baturusa</p>
              </div>
            </div>

            <div class="slide" style="background-image: url('./image/Peta-Rt-007-Desa-Baturusa.jpg');">
              <div class="overlay">
                <h1>Peta RT 007 Desa Baturusa</h1>
                <p>Wilayah RT 007 Desa Baturusa</p>
              </div>
            </div>

            <div class="slide" style="background-image: url('./image/Peta-Rt-008-Desa-Baturusa.jpg');">
              <div class="overlay">
                <h1>Peta RT 008 Desa Baturusa</h1>
                <p>Wilayah RT 008 Desa Baturusa</p>
              </div>
            </div>

            <div class="slide" style="background-image: url('./image/Peta-Rt-009-Desa-Baturusa.jpg');">
              <div class="overlay">
                <h1>Peta RT 009 Desa Baturusa</h1>
                <p>Wilayah RT 009 Desa Baturusa</p>
              </div>
            </div>

            <div class="slide" style="background-image: url('./image/Peta-Rt-010-Desa-Baturusa.jpg');">
              <div class="overlay">
                <h1>Peta RT 010 Desa Baturusa</h1>
                <p>Wilayah RT 010 Desa Baturusa</p>
              </div>
            </div>

            <div class="slide" style="background-image: url('./image/Peta-Rt-011-Desa-Baturusa.jpg');">
              <div class="overlay">
                <h1>Peta RT 011 Desa Baturusa</h1>
                <p>Wilayah RT 011 Desa Baturusa</p>
              </div>
            </div>

            <div class="slide" style="background-image: url('./image/Peta-Rt-012-Desa-Baturusa.jpg');">
              <div class="overlay">
                <h1>Peta RT 012 Desa Baturusa</h1>
                <p>Wilayah RT 012 Desa Baturusa</p>
              </div>
            </div>

          </div>

          <!-- Tombol navigasi -->
          <button class="prev">&#10094;</button>
          <button class="next">&#10095;</button>
        </section>

    </article>
  </div>

  <!-- KANAN: sidebar berita terdahulu -->
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
  <script>
      document.addEventListener("DOMContentLoaded", function() {
    const backToTopBtn = document.getElementById("backToTop");
    if (!backToTopBtn) return; // kalau tombolnya gak ada, berhenti di sini

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
