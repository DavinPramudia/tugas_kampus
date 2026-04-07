  <?php
  session_start();
  include 'koneksi.php'; // koneksi ke MySQL

  if (!isset($_SESSION['username'])) {
    header("Location: ./admin-login.php");
    exit;
  }

  $ip = $_SERVER['REMOTE_ADDR'];
  $agent = $_SERVER['HTTP_USER_AGENT'];
  $waktu = date('Y-m-d H:i:s');
  $tanggal = date('Y-m-d');

  // catat pengunjung harian
  $cek = mysqli_query($koneksi, "SELECT * FROM pengunjung1 WHERE ip_address = '$ip' AND DATE(waktu_kunjungan) = '$tanggal'");
  if (mysqli_num_rows($cek) == 0) {
    mysqli_query($koneksi, "INSERT INTO pengunjung1 (ip_address, user_agent, waktu_kunjungan)
      VALUES ('$ip', '$agent', '$waktu')");
  }

  $result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengunjung1");
  $data = mysqli_fetch_assoc($result);
  $totalPengunjung = $data['total'];

  // catat user registered
  $totaluser = mysqli_fetch_assoc(result: mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM user"))['total'];

  // catat berita
  $totalberita = mysqli_fetch_assoc(result: mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM berita"))['total'];

  // catat pengaduan
  $totalpengaduan = mysqli_fetch_assoc(result: mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengaduan"))['total'];

  // catat surat
  $totalsurat = mysqli_fetch_assoc(result: mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM user"))['total'];

  // Hitung user baru
  $user_baru = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM user WHERE status = 'pending'")
  )['total'];

  // Hitung pengaduan baru
  $pengaduan_baru = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM laporan WHERE status = 'Menunggu Verifikasi'")
  )['total'];

  // Hitung surat baru
  $surat_baru = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM penyuratan WHERE status = 'Menunggu Verifikasi'")
  )['total'];

  ?>
  <!DOCTYPE html>
  <html lang="id">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Desa Baturusa</title>
    <link rel="shortcut icon" href="./assets/images/Lambang_Kabupaten_Bangka_Selatan.png" />
    <link rel="stylesheet" href="./assets/css/styles.min.css" />
    <link rel="stylesheet" href="./assets/css/style.css">
    <style>
      /* ========================= */
  /* ======= GLOBAL CSS ====== */
  /* ========================= */
  body {
    background-color: #f4f6f9;
    font-family: 'Poppins', sans-serif;
    overflow-x: hidden;
  }

  /* ========================= */
  /* ======= TOP BAR ========= */
  /* ========================= */
  .app-topstrip {
    width: 100%;
    background: #16A34A;
    color: #fff;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
  }

  .menu-toggle {
    display: none;
    font-size: 1.5rem;
    background: none;
    border: none;
    color: #fff;
  }

  /* ========================= */
  /* ======= SIDEBAR ========= */
  /* ========================= */
  .left-sidebar {
    position: fixed;
    top: 65px;
    left: 0;
    height: calc(100% - 65px);
    width: 250px;
    background: #fff;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
    z-index: 999;
    overflow-y: auto;
    transition: all 0.3s ease;
  }

  .left-sidebar.hide {
    left: -260px;
  }

  .brand-logo {
    padding: 20px 10px;
    border-bottom: 1px solid #eee;
  }

  .brand-logo h5 {
    font-weight: 600;
    color: #2b2b2b;
  }

  .sidebar-nav .sidebar-item a {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 15px;
    font-weight: 500;
    color: #444;
    padding: 10px 20px;
    border-radius: 8px;
    margin: 4px 10px;
    transition: all 0.3s;
  }

  .sidebar-nav .sidebar-item a:hover,
  .sidebar-nav .sidebar-item a.active {
    background-color: #15803D;
    color: #fff;
  }

  /* ========================= */
  /* ======= BODY WRAP ======= */
  /* ========================= */
  .body-wrapper {
    margin-left: 250px;
    padding-top: 80px;
    padding-right: 20px;
    padding-left: 20px;
    transition: margin-left 0.3s ease;
  }

  /* ========================= */
  /* ======= FOOTER ========== */
  /* ========================= */
  footer {
    background: #fff;
    border-top: 1px solid #ddd;
    text-align: center;
    padding: 15px;
    font-size: 14px;
    color: #555;
    margin-top: 40px;
    border-radius: 10px;
  }

  /* ========================= */
  /* ======= RESPONSIVE ====== */
  /* ========================= */
  @media (max-width: 991px) {
    .menu-toggle {
      display: inline-block;
    }

    .left-sidebar {
      left: -260px;
    }

    .left-sidebar.show {
      left: 0;
    }

    .body-wrapper {
      margin-left: 0;
      padding-top: 90px;
    }
  }

  /* ========================= */
  /* ====== APARATUR BASE ==== */
  /* ========================= */
  .aparatur-container,
  .user-container,
  .umkm-container,
  .laporan-container {
    margin-top: 1.5rem;
    margin-bottom: 3rem;
    background-color: #f6f8fa;
  }

  .aparatur-card,
  .user-card,
  .umkm-card,
  .laporan-card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
  }

  .aparatur-card-header,
  .user-card-header,
  .umkm-card-header,
  .laporan-card-header {
    background: linear-gradient(135deg, #16a34a, #22c55e);
    color: white;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
  }

  .aparatur-card-body,
  .user-card-body,
  .umkm-card-body,
  .laporan-card-body {
    padding: 1rem;
  }

  /* ========================= */
  /* ====== TABLE STYLE ====== */
  /* ========================= */
  .aparatur-table,
  .user-table,
  .umkm-table,
  .laporan-table {
    width: 100%;
    border-collapse: collapse;
  }

  .aparatur-table th,
  .aparatur-table td,
  .user-table th,
  .user-table td,
  .umkm-table th,
  .umkm-table td,
  .laporan-table th,
  .laporan-table td {
    vertical-align: middle;
    text-align: center;
    padding: 0.6rem;
  }

  .aparatur-table th,
  .user-table th,
  .umkm-table th,
  .laporan-table th {
    background-color: #d1fae5;
    font-weight: 600;
  }

  .aparatur-table tr:nth-child(even),
  .user-table tr:nth-child(even),
  .umkm-table tr:nth-child(even),
  .laporan-table tr:nth-child(even) {
    background-color: #f9fafb;
  }

  /* ========================= */
  /* ===== IMAGE THUMB ======= */
  /* ========================= */
  .aparatur-img-thumb,
  .user-img-thumb,
  .umkm-img-thumb,
  .laporan-img-thumb {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 10px;
    cursor: pointer;
    transition: transform 0.2s;
  }

  .aparatur-img-thumb:hover,
  .user-img-thumb:hover,
  .umkm-img-thumb:hover,
  .laporan-img-thumb:hover {
    transform: scale(1.05);
  }

  /* ========================= */
  /* ===== BUTTON STYLE ====== */
  /* ========================= */
  .aparatur-btn-group .btn,
  .user-btn-group .btn,
  .umkm-btn-group .btn,
  .laporan-btn-group .btn {
    font-size: 12px;
    padding: 5px 10px;
    border-radius: 6px;
  }

  /* ========================= */
  /* ===== MODAL IMAGE ======= */
  /* ========================= */
  .aparatur-modal-img,
  .user-modal-img,
  .umkm-modal-img,
  .laporan-modal-img {
    width: 100%;
    max-height: 80vh;
    object-fit: contain;
    border-radius: 10px;
  }

  /* ========================= */
  /* ===== LAPORAN STATUS ==== */
  /* ========================= */
  .laporan-status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 8px;
    color: #fff;
    font-size: 13px;
    font-weight: 600;
  }

  .laporan-status-Kosong,
  .laporan-status-Menunggu { background: #6b7280; }

  .laporan-status-Diterima { background: #0ea5e9; }

  .laporan-status-Diproses { background: #eab308; }

  .laporan-status-Selesai { background: #16a34a; }

  .laporan-status-Ditolak { background: #dc2626; }

  /* ========================= */
  /* ===== MOBILE TABLE ====== */
  /* ========================= */
  @media (max-width: 768px) {
    .aparatur-table thead,
    .user-table thead,
    .umkm-table thead,
    .laporan-table thead {
      display: none;
    }

    .aparatur-table,
    .user-table,
    .umkm-table,
    .laporan-table,
    .aparatur-table tbody,
    .user-table tbody,
    .umkm-table tbody,
    .laporan-table tbody,
    .aparatur-table tr,
    .user-table tr,
    .umkm-table tr,
    .laporan-table tr,
    .aparatur-table td,
    .user-table td,
    .umkm-table td,
    .laporan-table td {
      display: block;
      width: 100%;
    }

    .aparatur-table tr,
    .user-table tr,
    .umkm-table tr,
    .laporan-table tr {
      margin-bottom: 15px;
      border: 1px solid #e0e0e0;
      border-radius: 10px;
      background: #fff;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      padding: 10px;
    }

    .aparatur-table td,
    .user-table td,
    .umkm-table td,
    .laporan-table td {
      border: none;
      display: flex;
      justify-content: space-between;
      padding: 8px 10px;
      font-size: 14px;
    }

    .aparatur-table td::before,
    .user-table td::before,
    .umkm-table td::before,
    .laporan-table td::before {
      content: attr(data-label);
      font-weight: 600;
      color: #1d3557;
    }

    .aparatur-img-thumb,
    .user-img-thumb,
    .umkm-img-thumb,
    .laporan-img-thumb {
      width: 100%;
      max-width: 250px;
      height: auto;
      border-radius: 10px;
    }
  }

  /* ========================= */
  /* ===== TABLE COLOR ======= */
  /* ========================= */
  .table-warning {
    background-color: #fff8e1 !important;
  }

  .table-danger {
    background-color: #fee2e2 !important;
  }




    </style>
  </head>

  <body>
    <!-- HEADER -->
    <div class="app-topstrip">
      <div class="d-flex align-items-center gap-3">
        <button class="menu-toggle" id="menuToggle"><i class="ti ti-menu-2"></i></button>
        <h5 class="mb-0">👋 Selamat Datang, 
        </h5>
      </div>
      <h6 class="mb-0 d-none d-sm-block">Sistem Informasi Desa</h6>
    </div>

    <!-- SIDEBAR -->
    <aside class="left-sidebar" id="sidebar">
      <div class="brand-logo d-flex align-items-center justify-content-center text-center gap-3">
        <img src="./assets/images/Logo Batu Rusa.jpg" alt="Logo" width="45" />
        <h5>Desa Baturusa</h5>
      </div>
      <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
        <ul id="sidebarnav">
          <li class="nav-small-cap text-secondary fw-bold ps-3 mt-3">Menu Utama</li>

          <li class="sidebar-item">
              <a class="sidebar-link <?php if (isset($_GET['page']) && $_GET['page'] == 'home') echo 'active'; ?>" href="index.php?page=home">
                <i class="ti ti-home"></i> <span>Dasbor</span>
              </a>
            </li>

          <!-- MENU UTAMA -->
          <li class="sidebar-item">
                <a class="sidebar-link <?php if (isset($_GET['page']) && $_GET['page'] == 'user') echo 'active'; ?>" href="index.php?page=user">
                  <i class="ti ti-file-description"></i> 
                  <span>Pengguna</span>
                  <?php if ($user_baru > 0): ?>
                    <span class="notif-badge">
                      <?= ($user_baru > 99) ? '99+' : $user_baru; ?>
                    </span>
                  <?php endif; ?>
                </a>
              </li>

          <li class="sidebar-item">
                <a class="sidebar-link <?php if (isset($_GET['page']) && $_GET['page'] == 'laporan_rakyat') echo 'active'; ?>" href="index.php?page=laporan_rakyat">
                  <i class="ti ti-file-description"></i> 
                  <span>Pengaduan</span>
                  <?php if ($pengaduan_baru > 0): ?>
                    <span class="notif-badge">
                      <?= ($pengaduan_baru > 99) ? '99+' : $pengaduan_baru; ?>
                    </span>
                  <?php endif; ?>
                </a>
              </li>

          <li class="sidebar-item">
            <a class="sidebar-link <?php if (isset($_GET['page']) && $_GET['page'] == 'penyuratan') echo 'active'; ?>" href="index.php?page=penyuratan">
              <i class="ti ti-mail"></i> 
              <span>Surat</span>
              <?php if ($surat_baru > 0): ?>
                <span class="notif-badge">
                  <?= ($surat_baru > 99) ? '99+' : $surat_baru; ?>
                </span>
              <?php endif; ?>
            </a>
          </li>
            <li class="sidebar-item">
              <a class="sidebar-link <?php if (isset($_GET['page']) && $_GET['page'] == 'berita') echo 'active'; ?>" href="index.php?page=berita">
                <i class="ti ti-news"></i> <span>Postingan</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link <?php if (isset($_GET['page']) && $_GET['page'] == 'aparatur') echo 'active'; ?>" href="index.php?page=aparatur">
                <i class="ti ti-users"></i> <span>Aparatur Desa</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link <?php if (isset($_GET['page']) && $_GET['page'] == 'umkm') echo 'active'; ?>" href="index.php?page=umkm">
                <i class="ti ti-brand-appgallery"></i> <span>UMKM</span>
              </a>
            </li>

          


          <li class="sidebar-item mt-3">
            <a class="sidebar-link text-danger" href="index.php?page=logout">
              <i class="ti ti-logout"></i> <span>Logout</span>
            </a>
          </li>
        </ul>
      </nav>
    </aside>

    <!-- KONTEN -->
    <div class="body-wrapper">
      <div class="container-fluid">
        <?php
        $halaman = isset($_GET['page']) ? $_GET['page'] : "home";
        if (!file_exists("page/$halaman.php")) {
          echo "<div class='alert alert-danger mt-4'>Halaman tidak ditemukan!</div>";
        } else {
          include "page/$halaman.php";
        }
        ?>
        <footer>
          <p>© 2025 Desa Baturusa | Dikembangkan oleh <strong>Davin X Okki</strong></p>
        </footer>
      </div>
    </div>

    <script src="./assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="./assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/js/sidebarmenu.js"></script>
    <script src="./assets/js/app.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <script>
      const menuToggle = document.getElementById("menuToggle");
      const sidebar = document.getElementById("sidebar");

      menuToggle.addEventListener("click", () => {
        sidebar.classList.toggle("show");
      });
    </script>
  </body>

  </html>
