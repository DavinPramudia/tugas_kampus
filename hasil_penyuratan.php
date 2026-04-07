<?php
include 'admin/koneksi.php';

if (!isset($_GET['id'])) {
  die('ID surat tidak ditemukan.');
}
$id = intval($_GET['id']);
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM penyuratan WHERE id='$id'"));

if (!$data) {
  die('Data surat tidak ditemukan.');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hasil Penyuratan - Desa Baturusa</title>
  <style>
    :root {
      --hijau: #16a34a;
      --hijau-tua: #15803d;
      --abu: #f9fafb;
      --teks: #374151;
    }

    body {
      font-family: "Poppins", Arial, sans-serif;
      margin: 0;
      background: var(--abu);
      color: var(--teks);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 16px;
    }

    .container {
      background: white;
      padding: 24px;
      border-radius: 14px;
      max-width: 600px;
      width: 100%;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    h2 {
      color: var(--hijau);
      margin-bottom: 8px;
      text-align: center;
    }

    h3 {
      color: var(--hijau-tua);
      margin-top: 20px;
      font-size: 1.1rem;
    }

    p {
      line-height: 1.6;
      margin: 6px 0;
    }

    .info-box {
      background: #f3f4f6;
      border-left: 4px solid var(--hijau);
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 14px;
    }

    .hasil-file {
      display: block;
      margin-top: 8px;
      color: var(--hijau-tua);
      text-decoration: none;
      font-weight: 500;
    }

    .btn-back {
      display: block;
      text-align: center;
      margin-top: 24px;
      background: var(--hijau);
      color: white;
      padding: 12px 0;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: background 0.2s ease;
    }

    .btn-back:hover {
      background: var(--hijau-tua);
    }

    /* Responsif untuk layar kecil */
    @media (max-width: 600px) {
      body {
        padding: 12px;
      }

      .container {
        padding: 20px 18px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
      }

      h2 {
        font-size: 1.3rem;
      }

      p, h3 {
        font-size: 0.95rem;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>📋 Hasil Penyuratan</h2>
    
    <div class="info-box">
      <p><strong>Nama Pemohon:</strong><br><?= htmlspecialchars($data['nama']); ?></p>
      <p><strong>Dusun:</strong><br><?= htmlspecialchars($data['dusun']); ?></p>
      <p><strong>Keperluan Surat:</strong><br><?= nl2br(htmlspecialchars($data['keperluan'])); ?></p>
    </div>

    <?php if (!empty($data['hasil_deskripsi'])): ?>
      <h3>📝 Deskripsi Hasil</h3>
      <p><?= nl2br(htmlspecialchars($data['hasil_deskripsi'])); ?></p>
    <?php endif; ?>

    <?php if (!empty($data['hasil_file'])): ?>
      <h3>📄 File Hasil</h3>
      <a href="upload/<?= htmlspecialchars($data['hasil_file']); ?>" target="_blank" class="hasil-file"><?= htmlspecialchars($data['hasil_file']); ?></a>
    <?php endif; ?>

    <a href="status_penyuratan.php?id=<?= $data['id']; ?>" class="btn-back">← Kembali ke Status</a>
  </div>

</body>
</html>
