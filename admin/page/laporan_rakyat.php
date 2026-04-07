<?php
include 'koneksi.php';

// ==== PROSES TERIMA / TOLAK / PROSES / SELESAI / HAPUS ====
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == 'Diterima') {
        mysqli_query($koneksi, "UPDATE laporan SET status='Diterima' WHERE id='$id'");
        echo "<script>alert('Laporan diterima!'); window.location='index.php?page=laporan_rakyat';</script>";
        exit;
    } 
    elseif ($action == 'tolak' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $alasan = mysqli_real_escape_string($koneksi, $_POST['alasan'] ?? '');
        mysqli_query($koneksi, "UPDATE laporan SET status='Ditolak', hasil_deskripsi='$alasan' WHERE id='$id'");
        echo "<script>alert('Laporan ditolak dengan alasan!'); window.location='index.php?page=laporan_rakyat';</script>";
        exit;
    }
    elseif ($action == 'proses') {
        mysqli_query($koneksi, "UPDATE laporan SET status='Diproses' WHERE id='$id'");
        echo "<script>alert('Laporan diproses!'); window.location='index.php?page=laporan_rakyat';</script>";
        exit;
    } 
    elseif ($action == 'selesai' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $hasil_deskripsi = mysqli_real_escape_string($koneksi, $_POST['hasil_deskripsi'] ?? '');
        $hasil_foto = '';

        // Upload file/foto
        if (!empty($_FILES['hasil_foto']['name'])) {
            $target_dir = "../upload/";
            $nama_file = time() . "_" . basename($_FILES["hasil_foto"]["name"]);
            $target_file = $target_dir . $nama_file;
            $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            if (in_array($file_type, ['jpg','jpeg','png','pdf'])) {
                move_uploaded_file($_FILES["hasil_foto"]["tmp_name"], $target_file);
                $hasil_foto = $nama_file;
            } else {
                echo "<script>alert('Format file tidak diizinkan!'); window.history.back();</script>";
                exit;
            }
        }

        // Update database
        $sql_update = "UPDATE laporan SET status='Selesai', hasil_deskripsi='$hasil_deskripsi'";
        if ($hasil_foto) {
            $sql_update .= ", hasil_foto='$hasil_foto'";
        }
        $sql_update .= " WHERE id='$id'";
        mysqli_query($koneksi, $sql_update);

        echo "<script>alert('Laporan selesai!'); window.location='index.php?page=laporan_rakyat';</script>";
        exit;
    } 
    elseif ($action == 'hapus') {
        $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT hasil_foto FROM laporan WHERE id='$id'"));
        if (!empty($data['hasil_foto']) && file_exists("../upload/" . $data['hasil_foto'])) {
            unlink("../upload/" . $data['hasil_foto']);
        }
        $hapus = mysqli_query($koneksi, "DELETE FROM laporan WHERE id='$id'");
        echo $hapus
            ? "<script>alert('Pengaduan berhasil dihapus'); window.location='index.php?page=laporan_rakyat';</script>"
            : "<script>alert('Gagal menghapus pengaduan');</script>";
        exit;
    }
}

// ==== AMBIL SEMUA DATA PENGADUAN ====
$query = mysqli_query($koneksi, "SELECT * FROM laporan ORDER BY id ASC");
?>

<link rel="stylesheet" href="assets/css/user-daftar.css">

<div class="user-container mt-4 mb-5">
  <div class="user-card shadow-sm">
    <div class="user-card-header d-flex justify-content-between align-items-center">
      📋 Daftar Laporan Warga Desa Baturusa
    </div>

    <div class="user-card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle user-table">
          <thead class="table-success text-center">
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Dusun</th>
              <th>Keperluan</th>
              <th>Alamat</th>
              <th>Foto Laporan</th>
              <th>Hasil Foto</th>
              <th>Status</th>
              <th>Lihat</th>
              <th width="18%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($query)) { 
                $status = $row['status'] ?: 'Menunggu Verifikasi';
            ?>
            <tr class="<?= $status=='Ditolak' ? 'table-danger' : ($status=='Menunggu Verifikasi' ? 'table-warning' : ''); ?>">
              <td class="text-center"><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['nama']) ?></td>
              <td><?= htmlspecialchars($row['dusun']) ?></td>
              <td><?= htmlspecialchars($row['deskripsi']) ?></td>
              <td><?= htmlspecialchars($row['alamat']) ?></td>
              <td class="text-center">
              <?php if(!empty($row['foto'])): ?>
                  <img src="../upload/<?= htmlspecialchars($row['foto']) ?>" 
                      alt="Foto Laporan" 
                      style="max-width: 80px; max-height: 80px; object-fit: cover; border-radius:4px; cursor:pointer;" 
                      onclick="previewImage('../upload/<?= htmlspecialchars($row['foto']) ?>')">
                <?php else: ?>
                  <span class="text-muted small">-</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <?php if(!empty($row['hasil_foto'])): ?>
                  <a href="../upload/<?= htmlspecialchars($row['hasil_foto']) ?>" target="_blank">📄 Lihat File</a>
                <?php else: ?>
                  <span class="text-muted small">-</span>
                <?php endif; ?>
              </td>
              <td class="text-center fw-bold text-capitalize"><?= htmlspecialchars($status) ?></td>

              <!-- Kolom Lihat -->
                <td class="text-center">
                  <!-- Tombol lihat -->
                  <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#lihatModal<?= $row['id'] ?>">👁️ Lihat</button>
                  
                  <!-- Tombol PDF -->
                  <button class="btn btn-sm btn-primary mt-1" onclick="downloadPDF('pdfDetail<?= $row['id'] ?>')">📄 PDF</button>
                </td>

              <!-- Modal Lihat -->
              <div class="modal fade" id="lihatModal<?= $row['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                      <h5 class="modal-title">Detail Laporan: <?= htmlspecialchars($row['nama']) ?></h5>
                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="detail<?= $row['id'] ?>">
                      <p><strong>Nama:</strong> <?= htmlspecialchars($row['nama']) ?></p>
                      <p><strong>Dusun:</strong> <?= htmlspecialchars($row['dusun']) ?></p>
                      <p><strong>Alamat:</strong> <?= htmlspecialchars($row['alamat']) ?></p>
                      <p><strong>Keperluan:</strong> <?= htmlspecialchars($row['deskripsi']) ?></p>

                      <?php if(!empty($row['foto'])): ?>
                        <p><strong>Dokumentasi Laporan:</strong></p>
                        <img src="../upload/<?= htmlspecialchars($row['foto']) ?>" 
                            alt="Foto User" 
                            style="max-width: 300px; max-height: 300px; object-fit: cover; border-radius:4px; cursor:pointer;" 
                            onclick="previewImage('../upload/<?= htmlspecialchars($row['foto']) ?>')">
                      <?php endif; ?>

                      <?php if(!empty($row['hasil_foto'])): ?>
                        <p><strong>File / Foto Admin:</strong></p>
                        <img src="../upload/<?= htmlspecialchars($row['hasil_foto']) ?>" 
                            alt="Foto Admin" 
                            style="max-width: 300px; max-height: 300px; object-fit: cover; border-radius:4px; cursor:pointer;" 
                            onclick="previewImage('../upload/<?= htmlspecialchars($row['hasil_foto']) ?>')">
                      <?php endif; ?>

                      <?php if(!empty($row['hasil_deskripsi'])): ?>
                        <p><strong>Catatan Admin:</strong> <?= htmlspecialchars($row['hasil_deskripsi']) ?></p>
                      <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Modal PDF -->
              <div id="pdfDetail<?= $row['id'] ?>" style="display:none;">
                <h3>Detail Laporan: <?= htmlspecialchars($row['nama']) ?></h3>
                <p><strong>Nama:</strong> <?= htmlspecialchars($row['nama']) ?></p>
                <p><strong>Dusun:</strong> <?= htmlspecialchars($row['dusun']) ?></p>
                <p><strong>Alamat:</strong> <?= htmlspecialchars($row['alamat']) ?></p>
                <p><strong>Keperluan:</strong> <?= htmlspecialchars($row['deskripsi']) ?></p>

                <?php if(!empty($row['foto'])): ?>
                  <p><strong>Dokumentasi Laporan:</strong></p>
                  <img src="../upload/<?= htmlspecialchars($row['foto']) ?>" 
                      alt="Foto User" 
                      style="max-width: 600px; max-height: 600px; object-fit: cover; border-radius:4px;">
                <?php endif; ?>

                <?php if(!empty($row['hasil_foto'])): ?>
                  <p><strong>File / Foto Admin:</strong></p>
                  <img src="../upload/<?= htmlspecialchars($row['hasil_foto']) ?>" 
                      alt="Foto Admin" 
                      style="max-width: 600px; max-height: 600px; object-fit: cover; border-radius:4px;">
                <?php endif; ?>

                <?php if(!empty($row['hasil_deskripsi'])): ?>
                  <p><strong>Catatan Admin:</strong> <?= htmlspecialchars($row['hasil_deskripsi']) ?></p>
                <?php endif; ?>
              </div>

              <!-- Kolom Aksi -->
              <td class="text-center">
                <div class="user-btn-group btn-group">
                  <!-- <a href="index.php?page=edit_laporan&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning text-white">✏️ Edit</a> -->
                  <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#hapusModal<?= $row['id'] ?>">🗑 Hapus</button>
                </div>

                <?php if ($status == 'Menunggu Verifikasi'): ?>
                  <div class="mt-2 user-btn-group btn-group">
                    <a href="index.php?page=laporan_rakyat&action=Diterima&id=<?= $row['id'] ?>" class="btn btn-sm btn-success">✅ Terima</a>
                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#tolakModal<?= $row['id'] ?>">❌ Tolak</button>
                  </div>

                  <!-- Modal Tolak -->
                  <div class="modal fade" id="tolakModal<?= $row['id'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                          <h5 class="modal-title">Tolak Laporan: <?= htmlspecialchars($row['nama']) ?></h5>
                          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="index.php?page=laporan_rakyat&action=tolak&id=<?= $row['id'] ?>">
                          <div class="modal-body">
                            <div class="mb-3">
                              <label for="alasan<?= $row['id'] ?>" class="form-label">Alasan Penolakan</label>
                              <textarea class="form-control" id="alasan<?= $row['id'] ?>" name="alasan" rows="3" required></textarea>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Kirim & Tolak</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>

                <?php if ($status == 'Diterima'): ?>
                  <div class="mt-2 user-btn-group btn-group">
                    <a href="index.php?page=laporan_rakyat&action=proses&id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">⚙️ Proses</a>
                  </div>
                <?php endif; ?>

                <?php if ($status == 'Diproses'): ?>
                  <button type="button" class="btn btn-sm btn-success mt-2" data-bs-toggle="modal" data-bs-target="#selesaiModal<?= $row['id'] ?>">✅ Selesai</button>

                  <!-- Modal Selesai -->
                  <div class="modal fade" id="selesaiModal<?= $row['id'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                          <h5 class="modal-title">Selesaikan Laporan: <?= htmlspecialchars($row['nama']) ?></h5>
                          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="index.php?page=laporan_rakyat&action=selesai&id=<?= $row['id'] ?>" enctype="multipart/form-data">
                          <div class="modal-body">
                            <div class="mb-3">
                              <label for="hasil_deskripsi<?= $row['id'] ?>" class="form-label">Komentar / Deskripsi</label>
                              <textarea class="form-control" id="hasil_deskripsi<?= $row['id'] ?>" name="hasil_deskripsi" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                              <label for="hasil_foto<?= $row['id'] ?>" class="form-label">Upload Foto / File (jpg, png, pdf)</label>
                              <input class="form-control" type="file" id="hasil_foto<?= $row['id'] ?>" name="hasil_foto">
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Kirim & Selesai</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>

                <!-- Modal Hapus -->
                <div class="modal fade" id="hapusModal<?= $row['id'] ?>" tabindex="-1">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                      <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title fw-bold">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body text-center">
                        <p>Apakah Anda yakin ingin menghapus Laporan <strong><?= htmlspecialchars($row['nama']) ?></strong>?</p>
                        <p class="text-muted small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
                      </div>
                      <div class="modal-footer justify-content-center">
                        <a href="index.php?page=laporan_rakyat&action=hapus&id=<?= $row['id'] ?>" class="btn btn-danger text-white">🗑 Hapus Sekarang</a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      </div>
                    </div>
                  </div>
                </div>

              </td>
            </tr>
            <?php } ?>
            <?php if(mysqli_num_rows($query)==0): ?>
              <tr><td colspan="9" class="text-center text-muted">Belum ada data laporan.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Preview Gambar -->
<div class="modal fade" id="imgModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-transparent border-0 text-center">
      <img id="modalImg" src="" class="img-fluid rounded shadow" style="max-height:600px; width:auto;">
    </div>
  </div>
</div>

<script>
const imgModal = document.getElementById('imgModal');
imgModal.addEventListener('show.bs.modal', e => {
  document.getElementById('modalImg').src = e.relatedTarget.getAttribute('data-img');
});
</script>
<script>
function previewImage(src) {
    const modalImg = document.getElementById('modalImg');
    modalImg.src = src;
    const imgModal = new bootstrap.Modal(document.getElementById('imgModal'));
    imgModal.show();
}
</script>
<script>
function downloadPDF(elementId, modalId) {
    var modalEl = document.getElementById(modalId);
    var bsModal = bootstrap.Modal.getInstance(modalEl);
    if(bsModal) bsModal.hide();

    var content = document.getElementById(elementId).innerHTML;
    var myWindow = window.open('', '', 'width=800,height=600');
    myWindow.document.write('<html><head><title>Laporan</title></head><body>');
    myWindow.document.write(content);
    myWindow.document.write('</body></html>');
    myWindow.document.close();
    myWindow.focus();
    myWindow.print();
}

</script>

<script>
function downloadPDF(elementId) {
  var content = document.getElementById(elementId).innerHTML;
  var myWindow = window.open('', '', 'width=800,height=600');
  myWindow.document.write('<html><head><title>Laporan PDF</title>');
  myWindow.document.write('<style>body{font-family:sans-serif;padding:20px;} img{margin:10px 0;}</style>');
  myWindow.document.write('</head><body>');
  myWindow.document.write(content);
  myWindow.document.write('</body></html>');
  myWindow.document.close();
  myWindow.focus();
  myWindow.print();
}
</script>

