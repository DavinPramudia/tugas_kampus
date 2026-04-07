<?php
// ================= KONEKSI DATABASE =================
include 'admin/koneksi.php'; // Pastikan path ini benar

session_start(); // wajib sebelum cek session
include 'admin/koneksi.php';

if (!isset($_GET['id'])) {
  echo "<script>alert('ID laporan tidak ditemukan!'); 
  window.location='laporan.php';</script>";
  exit;
}

$id = intval($_GET['id']);
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM laporan WHERE id='$id'"));
if (!$data) {
  echo "<script>alert('Data laporan tidak ditemukan!'); 
  window.location='laporan.php';</script>";
  exit;
}

$status = strtolower($data['status']);
$alasan = $data['hasil_deskripsi'] ?? '';
$progressWidth = '0%';
if ($status == 'diterima') $progressWidth = '33%';
elseif ($status == 'diproses') $progressWidth = '66%';
elseif ($status == 'selesai') $progressWidth = '100%'; ?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Status Pengaduan</title>
  <base href="http://localhost/web_baturusa/">
  <link rel="stylesheet" href="/web_baturusa/css/style.css">
</head>
<style>
  .timeline-container {
    background: white;
    border-radius: 14px;
    padding: 30px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  }

  .timeline {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin: 40px 0;
  }

  .timeline::before {
    content: "";
    position: absolute;
    top: 22px;
    left: 0;
    right: 0;
    height: 6px;
    background: #e5e7eb;
    border-radius: 3px;
    z-index: 1;
  }

  .timeline-progress {
    position: absolute;
    top: 22px;
    left: 0;
    height: 6px;
    background: #16a34a;
    z-index: 2;
    border-radius: 3px;
    transition: width 0.4s ease-in-out;
  }

  .timeline-step {
    position: relative;
    text-align: center;
    flex: 1;
    z-index: 3;
  }

  .timeline-step .circle {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: #9ca3af;
    margin: 0 auto 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
    font-size: 16px;
    transition: 0.3s;
  }

  .timeline-step.active .circle,
  .timeline-step.done .circle {
    background: #16a34a;
    box-shadow: 0 0 10px rgba(22, 163, 74, 0.5);
  }

  .timeline-step p {
    font-size: 15px;
    font-weight: 500;
    color: #374151;
    margin: 0;
  }

  .lihat-hasil-link {
    display: inline-block;
    margin-top: 4px;
    font-size: 14px;
    color: #16a34a;
    text-decoration: underline;
    transition: 0.3s;
  }

  .lihat-hasil-link:hover {
    color: #15803d;
    text-decoration: none;
  }


  @media (max-width: 650px) {
    .timeline {
      flex-direction: column;
      align-items: flex-start;
      padding-left: 30px;
    }

    .timeline::before {
      width: 6px;
      height: 100%;
      left: 22px;
      top: 0;
    }

    .timeline-progress {
      width: 6px;
      height: var(--progress-height, 0%);
      left: 22px;
      top: 0;
    }

    .timeline-step {
      flex-direction: row;
      align-items: center;
      display: flex;
      gap: 15px;
      margin-bottom: 40px;
      text-align: left;
    }

    .timeline-step .circle {
      width: 40px;
      height: 40px;
      margin: 0;
    }
  }
</style>

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
  <div class="page-title" style="background-image: url('./image/page_title_3.jpg');">
      <div class="container">
        <h1>Status Laporan</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Beranda</a></li>
            <li class="current">Status Laporan</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- ================= Konten ================= -->
    <section class="berita-section">
      <!-- KIRI: status laporan -->
      <div class="berita-kiri">
        <article class="status-laporan">
          <h2 class="pengaduan-judul text-center">📊 Status Laporan Anda</h2>
          <p class="pengaduan-deskripsi text-center">
            Lacak progres laporan yang telah Anda kirim ke Pemerintah Desa Baturusa.
          </p>

          <div class="timeline-container">
            <div class="timeline">
              <div class="timeline-progress" style="width: <?= $progressWidth ?>;"></div>

              <div class="timeline-step <?= ($status == 'menunggu verifikasi' || $status == 'diterima' || $status == 'diproses' || $status == 'selesai') ? 'done' : '' ?>">
                <div class="circle">1</div>
                <p>Menunggu Verifikasi</p>
              </div>

              <div class="timeline-step <?= ($status == 'diterima' || $status == 'diproses' || $status == 'selesai') ? 'done' : '' ?>">
                <div class="circle">2</div>
                <p>Diterima</p>
              </div>

              <div class="timeline-step <?= ($status == 'diproses' || $status == 'selesai') ? 'done' : '' ?>">
                <div class="circle">3</div>
                <p>Diproses</p>
              </div>

              <div class="timeline-step <?= ($status == 'selesai') ? 'done' : '' ?>">
                <div class="circle">4</div>
                <p>
                  Selesai
                  <?php if ($status == 'selesai' && (!empty($data['hasil_deskripsi']) || !empty($data['hasil_foto']))) : ?>
                    <br>
                    <a href="hasil_pengaduan.php?id=<?= $data['id']; ?>" class="lihat-hasil-link">📄 Lihat Hasilnya</a>
                  <?php endif; ?>
                </p>
              </div>
            </div>

            <div class="text-center mt-4">
              <p><strong>Nama:</strong> <?= htmlspecialchars($data['nama']) ?></p>
              <p><strong>Dusun:</strong> <?= htmlspecialchars($data['dusun']) ?></p>
              <p><strong>Status Saat Ini:</strong>
                <?php if ($status == 'ditolak') { ?>
                  <span class="badge bg-danger">Ditolak</span>
                <?php } else { ?>
                  <span class="badge bg-success"><?= ucfirst($status ?: 'Menunggu Verifikasi') ?></span>
                <?php } ?>
              </p>

              <?php if ($status == 'ditolak' && !empty($alasan)) { ?>
                <div class="alert alert-danger mt-3">
                  <strong>Alasan Penolakan:</strong><br><?= nl2br(htmlspecialchars($alasan)) ?>
                </div>
              <?php } ?>
            </div>

            <?php if (!empty($data['foto'])) { ?>
              <div class="text-center mt-4">
                <p><strong>Foto Laporan:</strong></p>
                <img src="upload/<?= htmlspecialchars($data['foto']) ?>" class="img-fluid rounded shadow-sm" style="max-width: 350px;">
              </div>
            <?php } ?>
          </div>
        </article>
      </div>

      <!-- KANAN: sidebar berita terdahulu -->
      <div class="berita-kanan">
        <div class="sidebar-item">
          <h3 class="widget-title">Berita Terdahulu</h3>
          <div class="sidebar-berita">
            <?php
            $sidebar = mysqli_query($koneksi, "SELECT * FROM berita ORDER BY id DESC LIMIT 6");
            while ($s = mysqli_fetch_assoc($sidebar)): ?>
              <div class="sidebar-berita-item">
                <img src="admin/assets/images/berita/<?= htmlspecialchars($s['gambar']); ?>" alt="Berita Lama">
                <div class="sidebar-isi">
                  <h4>
                    <a href="detail_berita.php?id=<?= $s['id']; ?>">
                      <?= htmlspecialchars($s['judul']); ?>
                    </a>
                  </h4>
                  <small><?= date('d M Y', strtotime($s['tanggal'] ?? 'now')); ?></small>
                </div>
              </div>
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

  <!-- SweetAlert2 Library -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Pop Up SweetAlert -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const status = "<?= $status ?>";
      const alasan = "<?= addslashes($alasan) ?>";

      if (status === "ditolak") {
        Swal.fire({
          icon: "error",
          title: "Laporan Ditolak",
          html: `<p>Alasan: <strong>${alasan || 'Tidak ada alasan'}</strong></p><p>Ingin membuat laporan baru?</p>`,
          showCancelButton: true,
          confirmButtonText: "Ya, Buat Baru",
          cancelButtonText: "Tidak",
          confirmButtonColor: "#16a34a",
          cancelButtonColor: "#6b7280"
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "pengaduan.php";
          } else {
            window.location.href = "index.php";
          }
        });
      }

      if (status === "selesai") {
        Swal.fire({
          icon: "success",
          title: "Laporan Selesai 🎉",
          text: "Terima kasih! Laporan Anda telah selesai ditangani. Ingin melaporkan masalah lain?",
          showCancelButton: true,
          confirmButtonText: "Laporkan Lagi",
          cancelButtonText: "Tidak",
          confirmButtonColor: "#16a34a",
          cancelButtonColor: "#6b7280"
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "pengaduan.php";
          } 
        });
      }
    });
  </script>
  <script>
    const backToTopBtn = document.getElementById("backToTop");
    window.addEventListener("scroll", () => {
      if (window.scrollY > 200) backToTopBtn.classList.add("show");
      else backToTopBtn.classList.remove("show");
    });
    backToTopBtn.addEventListener("click", e => {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  </script>


</body>

</html>