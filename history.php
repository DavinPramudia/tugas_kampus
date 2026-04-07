<?php
session_start();
include 'admin/koneksi.php';

// Cek login
if (!isset($_SESSION['login'])) {
  $_SESSION['pesan'] = "Silakan login terlebih dahulu untuk melihat riwayat.";
  header("Location: login.php?redirect=history.php");
  exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data laporan user
$query = mysqli_query($koneksi, "
  SELECT * FROM laporan 
  WHERE user_id = '$user_id' 
  ORDER BY tanggal DESC
");

// Ambil data surat user
$surat_query = mysqli_query($koneksi, "
  SELECT * FROM penyuratan 
  WHERE user_id = '$user_id'
  ORDER BY waktu_dibuat DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat Pengaduan</title>
  <base href="http://localhost/web_baturusa/">
  <link rel="stylesheet" href="/web_baturusa/css/style.css">
</head>
<body>

<!-- ================= HEADER ================= -->
<header>
  <div class="tulisan_desa">
    <h1>Desa Baturusa</h1>
  </div>

  <div class="hamburger" id="hamburger">
    <span></span><span></span><span></span>
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
  <!-- Judul Halaman -->
  <div class="page-title" style="background-image: url('./image/page_title_1.jpg');">
    <div class="container">
      <h1>Riwayat</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="index.php">Beranda</a></li>
          <li class="current">Riwayat</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Konten -->
  <section class="berita-section">
    <div class="berita-kiri">
      <article class="pengaduan-konten">
        <h2 class="pengaduan-judul">Daftar Riwayat Anda</h2>
        <p class="pengaduan-deskripsi">
          Berikut adalah riwayat laporan pengaduan dan surat yang telah Anda kirim.
        </p>

        <!-- ======== TABEL PENGADUAN ======== -->
        <?php if (mysqli_num_rows($query) > 0): ?>
          <table class="pengaduan-tabel">
            <thead>
              <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Dusun</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; while ($row = mysqli_fetch_assoc($query)): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= date('d M Y, H:i', strtotime($row['tanggal'])) ?></td>
                <td><?= htmlspecialchars($row['dusun']) ?></td>
                <td><?= htmlspecialchars(substr($row['deskripsi'], 0, 50)) ?>...</td>
                <td>
                  <span class="status <?= strtolower(str_replace(' ', '-', $row['status'])) ?>">
                    <?= htmlspecialchars($row['status']) ?>
                  </span>
                </td>
                <td><a href="status_pengaduan.php?id=<?= $row['id'] ?>" class="pengaduan-btn">Lihat</a></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p style="text-align:center; margin-top:20px;">Belum ada laporan yang Anda kirim.</p>
        <?php endif; ?>

        <!-- ======== TABEL PENYURATAN ======== -->
        <?php if (mysqli_num_rows($surat_query) > 0): ?>
          <table class="pengaduan-tabel">
            <thead>
              <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Dusun</th>
                <th>Keperluan</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; while ($row = mysqli_fetch_assoc($surat_query)): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= date('d M Y, H:i', strtotime($row['waktu_dibuat'])) ?></td>
                <td><?= htmlspecialchars($row['dusun']) ?></td>
                <td><?= htmlspecialchars(substr($row['keperluan'], 0, 50)) ?>...</td>
                <td>
                  <span class="status <?= strtolower(str_replace(' ', '-', $row['status'])) ?>">
                    <?= htmlspecialchars($row['status']) ?>
                  </span>
                </td>
                <td><a href="status_penyuratan.php?id=<?= $row['id'] ?>" class="pengaduan-btn">Lihat</a></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p style="text-align:center; margin-top:20px;">Belum ada surat yang Anda ajukan.</p>
        <?php endif; ?>

      </article>
    </div>

    <!-- SIDEBAR -->
    <div class="berita-kanan">
      <div class="sidebar-item">
        <h3 class="widget-title">Berita Terdahulu</h3>
        <div class="sidebar-berita">
          <?php
          $sidebar = mysqli_query($koneksi, "SELECT * FROM berita ORDER BY id DESC LIMIT 6");
          while ($s = mysqli_fetch_assoc($sidebar)):
          ?>
          <a href="berita_detail.php?id=<?= $s['id']; ?>" class="sidebar-berita-item-link">
            <div class="sidebar-berita-item">
              <img src="admin/assets/images/berita/<?= htmlspecialchars($s['gambar']); ?>" alt="">
              <div class="sidebar-isi">
                <h4><?= htmlspecialchars($s['judul']); ?></h4>
                <small><?= date('d M Y', strtotime($s['tanggal'] ?? 'now')); ?></small>
              </div>
            </div>
          </a>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- FLOATING BUTTONS -->
  <a href="https://wa.me/6285371336779" target="_blank" class="floating-cta-btn">Hubungi Kami</a>
  <a href="#" class="back-to-top-btn" id="backToTop">^</a>
</main>

<!-- ================= FOOTER ================= -->
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
      <p>📞 08xx-xxxx-xxxx</p>
      <p>✉ <a href="mailto:desa@gmail.com">desa@gmail.com</a></p>
    </div>

    <div class="footer-kolom">
      <h4>Lokasi Desa</h4>
      <div class="footer-maps">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.373870884327!2d106.145997!3d-2.115732!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e22a9e8b8f693f5%3A0x8f2c6e6ef29d6d8!2sDesa%20Baturusa%2C%20Kabupaten%20Bangka!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid"
          width="100%" height="200"
          style="border:0; border-radius:8px;"
          allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  </div>

  <div class="footer-bawah">
    <p>&copy; 2025 Pemerintah Desa Baturusa | Powered by Okki Darmawan &amp; Davin Pramudia</p>
  </div>
</footer>

<script src="js/script.js"></script>

<script>
  const backToTopBtn = document.getElementById("backToTop");
  window.addEventListener("scroll", () => {
    backToTopBtn.classList.toggle("show", window.scrollY > 200);
  });
  backToTopBtn.addEventListener("click", e => {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
</script>
</body>
</html>
