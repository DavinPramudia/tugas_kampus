<?php
include 'koneksi.php';

// ==== PROSES TERIMA / TOLAK / PROSES / SELESAI SURAT ====
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == 'Diterima') {
        mysqli_query($koneksi, "UPDATE penyuratan SET status='Diterima' WHERE id='$id'");
        echo "<script>alert('Surat diterima!'); window.location='index.php?page=penyuratan';</script>";
        exit;
    } 
    elseif ($action == 'tolak' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $alasan = mysqli_real_escape_string($koneksi, $_POST['alasan'] ?? '');
        mysqli_query($koneksi, "UPDATE penyuratan SET status='Ditolak', hasil_deskripsi='$alasan' WHERE id='$id'");
        echo "<script>alert('Surat ditolak dengan alasan!'); window.location='index.php?page=penyuratan';</script>";
        exit;
    }
    elseif ($action == 'proses') {
        mysqli_query($koneksi, "UPDATE penyuratan SET status='Diproses' WHERE id='$id'");
        echo "<script>alert('Surat diproses!'); window.location='index.php?page=penyuratan';</script>";
        exit;
    } 
    elseif ($action == 'selesai' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $hasil_deskripsi = mysqli_real_escape_string($koneksi, $_POST['hasil_deskripsi'] ?? '');
        $hasil_file = '';

        if (!empty($_FILES['hasil_file']['name'])) {
            $target_dir = "../upload/";
            $nama_file = time() . "_" . basename($_FILES["hasil_file"]["name"]);
            $target_file = $target_dir . $nama_file;
            $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            if (in_array($file_type, ['jpg','jpeg','png','pdf'])) {
                move_uploaded_file($_FILES["hasil_file"]["tmp_name"], $target_file);
                $hasil_file = $nama_file;
            }
        }

        $sql_update = "UPDATE penyuratan SET status='Selesai', hasil_deskripsi='$hasil_deskripsi'";
        if ($hasil_file) {
            $sql_update .= ", hasil_file='$hasil_file'";
        }
        $sql_update .= " WHERE id='$id'";
        mysqli_query($koneksi, $sql_update);

        echo "<script>alert('Surat selesai!'); window.location='index.php?page=penyuratan';</script>";
        exit;
    } 
    elseif ($action == 'hapus') {
        $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT hasil_file FROM penyuratan WHERE id='$id'"));
        if (!empty($data['hasil_file']) && file_exists("../upload/" . $data['hasil_file'])) {
            unlink("../upload/" . $data['hasil_file']);
        }
        $hapus = mysqli_query($koneksi, "DELETE FROM penyuratan WHERE id='$id'");
        echo $hapus
            ? "<script>alert('Surat berhasil dihapus'); window.location='index.php?page=penyuratan';</script>"
            : "<script>alert('Gagal menghapus surat');</script>";
        exit;
    }
}

// ==== AMBIL SEMUA DATA SURAT ====
$query = mysqli_query($koneksi, "SELECT * FROM penyuratan ORDER BY id ASC");
?>

<link rel="stylesheet" href="assets/css/user-daftar.css">

<div class="user-container mt-4 mb-5">
  <div class="user-card shadow-sm">
    <div class="user-card-header d-flex justify-content-between align-items-center">
      📋 Daftar Surat Warga Desa Baturusa
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
              <th>Foto KTP</th>
              <th>Foto KK</th>
              <th>Hasil File</th>
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
              <td><?= htmlspecialchars($row['keperluan']) ?></td>
              <td class="text-center">
              <?php if(!empty($row['foto_ktp'])): ?>
                    <img src="../upload/<?= htmlspecialchars($row['foto_ktp']) ?>" 
                        alt="Foto KTP" 
                        style="max-width: 80px; max-height: 80px; object-fit: cover; border-radius:4px; cursor:pointer;" 
                        onclick="previewImage('../upload/<?= htmlspecialchars($row['foto_ktp']) ?>')">
                <?php else: ?>
                    <span class="text-muted small">-</span>
                  <?php endif; ?>
                </td>

                <td class="text-center">
                  <?php if(!empty($row['foto_kk'])): ?>
                    <img src="../upload/<?= htmlspecialchars($row['foto_kk']) ?>" 
                        alt="Foto KK" 
                        style="max-width: 80px; max-height: 80px; object-fit: cover; border-radius:4px; cursor:pointer;" 
                        onclick="previewImage('../upload/<?= htmlspecialchars($row['foto_kk']) ?>')">
                  <?php else: ?>
                    <span class="text-muted small">-</span>
                  <?php endif; ?>
                </td>

              <td class="text-center">
                <?php if(!empty($row['hasil_file'])): ?>
                  <a href="../upload/<?= htmlspecialchars($row['hasil_file']) ?>" target="_blank">📄 Lihat File</a>
                <?php else: ?>
                  <span class="text-muted small">-</span>
                <?php endif; ?>
              </td>

              <td class="text-center fw-bold text-capitalize"><?= htmlspecialchars($status) ?></td>

              <!-- Kolom Lihat -->
              <td class="text-center">
                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#lihatModal<?= $row['id'] ?>">👁️ Lihat</button>
                <button class="btn btn-sm btn-primary mt-1" onclick="downloadPDF('detail<?= $row['id'] ?>')">📄 PDF</button>
              </td>

                <div class="modal fade" id="lihatModal<?= $row['id'] ?>" tabindex="-1">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">Detail Surat: <?= htmlspecialchars($row['nama']) ?></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body"id="detail<?= $row['id'] ?>">
                        <p><strong>Nama:</strong> <?= htmlspecialchars($row['nama']) ?></p>
                        <p><strong>Dusun:</strong> <?= htmlspecialchars($row['dusun']) ?></p>
                        <p><strong>Keperluan:</strong> <?= htmlspecialchars($row['keperluan']) ?></p>
                        <?php if(!empty($row['foto_ktp'])): ?>
                        <p><strong>Dokumentasi Laporan:</strong></p>
                        <img src="../upload/<?= htmlspecialchars($row['foto_ktp']) ?>" 
                            alt="Foto KTP" 
                            style="max-width: 600px; max-height: 600px; object-fit: cover; border-radius:4px; cursor:pointer;" 
                              onclick="previewImage('../upload/<?= htmlspecialchars($row['foto_ktp']) ?>')">
                        <?php endif; ?>

                        <?php if(!empty($row['foto_kk'])): ?>
                        <p><strong>Dokumentasi Laporan:</strong></p>
                        <img src="../upload/<?= htmlspecialchars($row['foto_kk']) ?>" 
                            alt="Foto KK" 
                            style="max-width: 600px; max-height: 600px; object-fit: cover; border-radius:4px; cursor:pointer;" 
                              onclick="previewImage('../upload/<?= htmlspecialchars($row['foto_kk']) ?>')">
                        <?php endif; ?>

                        <?php if(!empty($row['hasil_file'])): ?>
                        <p><strong>File / Foto Admin:</strong></p>
                        <img src="../upload/<?= htmlspecialchars($row['hasil_file']) ?>" 
                            alt="Foto Hasil" 
                            style="max-width: 600px; max-height: 600px; object-fit: cover; border-radius:4px; cursor:pointer;" 
                            onclick="previewImage('../upload/<?= htmlspecialchars($row['hasil_file']) ?>')">
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
              </td>

              <!-- Kolom Aksi -->
              <td class="text-center">
                <div class="user-btn-group btn-group">
                  <a href="index.php?page=edit_surat&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning text-white">✏️ Edit</a>
                  <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#hapusModal<?= $row['id'] ?>">🗑 Hapus</button>
                </div>


                  <!-- Modal Tolak -->
                  <div class="modal fade" id="tolakModal<?= $row['id'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                          <h5 class="modal-title">Tolak Surat: <?= htmlspecialchars($row['nama']) ?></h5>
                          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="index.php?page=penyuratan&action=tolak&id=<?= $row['id'] ?>">
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

                <?php if ($status == 'Diterima'): ?>
                  <div class="mt-2 user-btn-group btn-group">
                    <a href="index.php?page=penyuratan&action=proses&id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">⚙️ Proses</a>
                  </div>
                <?php endif; ?>

                <?php if ($status == 'Diproses'): ?>
                  <button type="button" class="btn btn-sm btn-success mt-2" data-bs-toggle="modal" data-bs-target="#selesaiModal<?= $row['id'] ?>">
                    ✅ Selesai
                  </button>

                <!-- Modal Selesai -->
                  <div class="modal fade" id="selesaiModal<?= $row['id'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                          <h5 class="modal-title">Selesaikan Surat: <?= htmlspecialchars($row['nama']) ?></h5>
                          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="index.php?page=penyuratan&action=selesai&id=<?= $row['id'] ?>" enctype="multipart/form-data">
                          <div class="modal-body">
                            <div class="mb-3">
                              <label for="hasil_deskripsi<?= $row['id'] ?>" class="form-label">Komentar / Deskripsi</label>
                              <textarea class="form-control" id="hasil_deskripsi<?= $row['id'] ?>" name="hasil_deskripsi" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                              <label for="hasil_file<?= $row['id'] ?>" class="form-label">Upload Foto / File (jpg, png, pdf)</label>
                              <input class="form-control" type="file" id="hasil_file<?= $row['id'] ?>" name="hasil_file">
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
                        <p>Apakah Anda yakin ingin menghapus surat <strong><?= htmlspecialchars($row['nama']) ?></strong>?</p>
                        <p class="text-muted small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
                      </div>
                      <div class="modal-footer justify-content-center">
                        <a href="index.php?page=penyuratan&action=hapus&id=<?= $row['id'] ?>" class="btn btn-danger text-white">🗑 Hapus Sekarang</a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      </div>
                    </div>
                  </div>
                </div>

              </td>
            </tr>
            <?php } ?>
            <?php if(mysqli_num_rows($query)==0): ?>
              <tr><td colspan="10" class="text-center text-muted">Belum ada data surat.</td></tr>
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

