<?php
session_start();

if (!isset($_SESSION['login'])) {
    $_SESSION['pesan'] = "Silakan login terlebih dahulu untuk mengirim pengaduan.";
    header("Location: login.php?redirect=pengaduan.php");
    exit;
}

include 'admin/koneksi.php'; // koneksi mysqli

// ==== CEK APAKAH USER SUDAH PUNYA LAPORAN AKTIF ====
$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    $cek_laporan = mysqli_query($koneksi, "
        SELECT id FROM laporan 
        WHERE user_id = '$user_id' 
        AND status NOT IN ('Selesai', 'Ditolak')
        LIMIT 1
    ");

    if (mysqli_num_rows($cek_laporan) > 0) {
        $data = mysqli_fetch_assoc($cek_laporan);
        $laporan_id = $data['id'];

        echo "<script>
            alert('Anda masih memiliki laporan yang sedang diproses. Silakan pantau statusnya.');
            window.location = 'status_pengaduan.php?id=$laporan_id';
        </script>";
        exit;
    }
}

$nama_session = $_SESSION['nama'] ?? '';

// ========== PROSES KIRIM LAPORAN ==========
if (isset($_POST['kirim'])) {
    $nama = trim($_POST['nama']); // dari session, readonly
    $no_hp = trim($_POST['no_hp']);
    $dusun = trim($_POST['dusun']);
    $alamat = trim($_POST['alamat']);
    $deskripsi = trim($_POST['deskripsi']);
    $tanggal = date('Y-m-d H:i:s');

    // ===================== VALIDASI BACKEND =====================
    $errors = [];

    if (!preg_match("/^[0-9]{10,13}$/", $no_hp)) {
        $errors[] = "Nomor HP harus 10–13 digit angka.";
    }

    if (strlen($alamat) < 10) {
        $errors[] = "Alamat minimal 10 karakter.";
    }

    if (strlen($deskripsi) < 20) {
        $errors[] = "Deskripsi minimal 20 karakter.";
    }

    if (count($errors) > 0) {
        $msg = implode('\n', $errors);
        echo "<script>alert('$msg'); window.history.back();</script>";
        exit;
    }

    // Folder upload
    $uploadDir = "upload/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    // Upload foto (opsional)
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    if (!empty($foto)) {
        $ext = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
        $newName = uniqid("foto_", true) . "." . $ext;
        $folder = $uploadDir . $newName;
        move_uploaded_file($tmp, $folder);
    } else {
        $newName = null;
    }

    // Simpan ke database
    $query = mysqli_query($koneksi, "
        INSERT INTO laporan (user_id, nama, no_hp, dusun, alamat, foto, deskripsi, status, tanggal)
        VALUES ('$user_id', '$nama', '$no_hp', '$dusun', '$alamat', '$newName', '$deskripsi', 'Menunggu Verifikasi', '$tanggal')
    ");

    if ($query) {
        $last_id = mysqli_insert_id($koneksi);
        echo "<script>alert('Laporan berhasil dikirim!'); window.location='status_pengaduan.php?id=$last_id';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal mengirim laporan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pengaduan</title>
<base href="http://localhost/web_baturusa/">
<link rel="stylesheet" href="/web_baturusa/css/style.css">
</head>
<body>

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
    <h1>Pengaduan</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="index.html">Beranda</a></li>
        <li class="current">Pengaduan</li>
      </ol>
    </nav>
  </div>
</div>

<section class="berita-section">
  <div class="berita-kiri">
    <article class="pengaduan-konten">
      <h2 class="pengaduan-judul">Form Pengaduan Masyarakat</h2>
      <p class="pengaduan-deskripsi">
        Laporkan keluhan, saran, atau aspirasi Anda kepada pemerintah desa.  
        Kolom bertanda * wajib diisi.
      </p>

      <form class="pengaduan-form" method="post" onsubmit="return validateForm()" enctype="multipart/form-data">
        <div class="pengaduan-group">
          <label class="pengaduan-label">Nama Lengkap *</label>
          <input name="nama" type="text" class="pengaduan-input" value="<?= htmlspecialchars($nama_session) ?>" readonly required>
        </div>

        <div class="pengaduan-group">
          <label class="pengaduan-label">Nomor HP *</label>
          <input name="no_hp" type="text" class="pengaduan-input" placeholder="Contoh: 08123456789" required>
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
          <label class="pengaduan-label">Alamat *</label>
          <input name="alamat" type="text" class="pengaduan-input" placeholder="Contoh: jalan air rt 1" minlength="10" required>
        </div>

        <div class="pengaduan-group">
          <label class="pengaduan-label">Foto (opsional)</label>
          <input name="foto" type="file" class="pengaduan-file" accept="image/*">
          <p class="pengaduan-keterangan">Format JPG/PNG, maksimal 2MB.</p>
        </div>

        <div class="pengaduan-group">
          <label class="pengaduan-label">Deskripsi Masalah *</label>
          <textarea name="deskripsi" class="pengaduan-textarea"
            placeholder="Tuliskan kronologi atau detail masalah..." required></textarea>
        </div>

        <div class="pengaduan-actions">
          <button type="submit" name="kirim" class="pengaduan-btn">Kirim Pengaduan</button>
          <button type="reset" class="pengaduan-btn pengaduan-btn-reset">Reset</button>
        </div>
      </form>
    </article>
  </div>

  <!-- Sidebar Berita -->
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
function validateForm() {
    const no_hp = document.querySelector('[name="no_hp"]').value;
    const alamat = document.querySelector('[name="alamat"]').value;
    const deskripsi = document.querySelector('[name="deskripsi"]').value;

    if (!/^[0-9]{10,13}$/.test(no_hp)) {
        alert("Nomor HP harus 10–13 digit angka!");
        return false;
    }

    if (alamat.length < 10) {
        alert("Alamat minimal 10 karakter!");
        return false;
    }

    if (deskripsi.length < 20) {
        alert("Deskripsi minimal 20 karakter!");
        return false;
    }

    return true;
}

// Back to top button
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
