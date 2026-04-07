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
  <title>Sejarah</title>
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
  <div class="page-title" style="background-image: url('./image/page_title_3.jpg');">
  <div class="container">
    <h1>Sejarah</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="index.html">Beranda</a></li>
        <li class="current">Sejarah</li>
      </ol>
    </nav>
  </div>
</div>

<!-- ================= Konten + Sidebar ================= -->
<section class="umum-section">
  <!-- KIRI: konten teks -->
  <div class="umum-kiri">
    <article class="umum-konten-teks">
      <h2>Legenda Asal Muasal Nama Batu Rusa</h2>

      <p>
        Pada zaman dahulu di sebuah perkampungan kecil dan sunyi, hiduplah sepasang suami istri. 
        Sepasang suami istri ini kesehariannya sebagai petani. Dalam menjalani kehidupannya sebagai 
        suami istri, datanglah saat-saat yang dinantikan, yakni istrinya mengandung. Tentu rasa haru 
        dan bahagia menyelimuti sepasang suami istri tersebut karena akan mendapatkan seorang anak 
        yang telah dinanti-nantikan.
      </p>

      <p>
        Dalam keadaan mengandung tersebut, sang istri memiliki keinginan atau ngidam. 
        Ngidam sang istri ingin sekali memakan daging <strong>Rusa</strong>. 
        Maka, sang istri pun meminta kepada suaminya untuk mencari daging Rusa. 
        Karena rasa cinta sang suami kepada istrinya, ia pun berangkat mencari Rusa 
        dengan cara <em>belalup</em> (cara orang zaman dahulu untuk mencari Rusa).
      </p>

      <p>
        Sang suami ditemani seekor anjing dengan harapan bisa membawa pulang daging Rusa 
        sebagaimana keinginan istrinya. Ia berjalan dari pagi sampai sore hari, 
        namun belum juga mendapatkan seekor Rusa. 
        Ia terus berjalan melewati hutan dan sungai tanpa lelah.
      </p>

      <p>
        Mulai muncul rasa cemas dan takut sang suami tidak bisa membawa pulang daging Rusa 
        untuk istrinya. Akhirnya ia menelusuri sungai dan berhenti di tepi sungai dengan harapan 
        ada Rusa lewat. Dalam keadaan cemas, tiba-tiba muncullah seekor Rusa yang sudah 
        dinanti-nantikannya.
      </p>

      <h3>Asal Muasal Nama Batu Rusa</h3>
      <p>
        Sangatlah senang sang suami karena harapannya akan segera tercapai. 
        Dengan tidak menyia-nyiakan kesempatan, sang suami segera mengintai Rusa 
        dan perlahan mendekatinya. Namun, tiba-tiba datang seseorang yang tidak ia kenal, 
        yaitu <strong>Si Pahit Lidah</strong>.
      </p>

      <p>
        Si Pahit Lidah memanggil sang suami, “Hey... Hey...”. 
        Karena takut Rusa lari, sang suami tidak menjawab sepatah kata pun. 
        Si Pahit Lidah pun berkata, 
        “Nanti jadi batu kalau dipanggil-panggil tidak menjawab.” 
        Seketika itu juga, sang suami dan Rusa berubah menjadi batu.
      </p>

      <p>
        Begitulah asal mula nama Desa Batu Rusa di Kecamatan Merawang, Kabupaten Bangka. 
        Cerita ini masih dipercaya masyarakat hingga kini. 
        Batu yang menyerupai hewan Rusa tersebut dapat dilihat di sungai Batu Rusa 
        ketika air sedang surut, biasanya sekitar bulan Mei atau saat masyarakat Tionghoa 
        sedang merayakan <em>pit cun</em>.
      </p>

      <p>
        <strong>*Si Pahit Lidah</strong> dalam cerita rakyat Nusantara dikenal sebagai Serunting, 
        seorang pangeran dari Sumatra Selatan yang memiliki kesaktian tinggi. 
        Segala ucapan yang keluar dari mulutnya akan menjadi kenyataan.
      </p>

      <p>
        <strong>*Pit cun</strong> adalah perayaan masyarakat Tionghoa Bangka yang bertepatan 
        dengan tanggal 5 bulan Mei dalam kalender Imlek.
      </p>

      <p><em>Sumber: BatuRusa "Dalam Cerita" karya Iswanto dan Zamzani</em></p>
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
