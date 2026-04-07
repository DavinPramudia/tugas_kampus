<?php
include 'koneksi.php';

// ==== PROSES HAPUS APARATUR ====
if (isset($_GET['action']) && $_GET['action'] == "hapus") {
    $id = $_GET['id'];

    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT foto FROM aparatur WHERE id = '$id'"));
    if ($data && file_exists("./assets/images/aparatur/" . $data['foto'])) {
        unlink("./assets/images/aparatur/" . $data['foto']);
    }

    $sql = mysqli_query($koneksi, "DELETE FROM aparatur WHERE id = '$id'");
    echo $sql
      ? "<script>alert('Data aparatur berhasil dihapus'); window.location='index.php?page=aparatur';</script>"
      : "<script>alert('Gagal menghapus data');</script>";
    exit;
}

$query = mysqli_query($koneksi, "SELECT * FROM aparatur ORDER BY id ASC");
?>

<div class="aparatur-container mt-4 mb-5">
  <div class="aparatur-card shadow-sm">
    <div class="aparatur-card-header d-flex justify-content-between align-items-center">
      👨‍💼 Daftar Aparatur Desa Baturusa
      <a href="index.php?page=post_aparatur" class="btn btn-light btn-sm">+ Tambah Aparatur</a>
    </div>

    <div class="aparatur-card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle aparatur-table">
          <thead class="table-success text-center">
            <tr>
              <th>No</th>
              <th>Foto</th>
              <th>Nama</th>
              <th>Jabatan</th>
              <th>Tempat, Tgl Lahir</th>
              <th>Masa Jabatan</th>
              <th width="15%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no = 1;
            while ($row = mysqli_fetch_assoc($query)) { 
              $foto_path = $row['foto'] ? "./assets/images/aparatur/" . $row['foto'] : "noimage.jpg";
            ?>
            <tr>
              <td class="text-center"><?= $no++ ?></td>
              <td class="text-center">
                <img src="<?= $foto_path ?>" alt="Foto" class="aparatur-img-thumb" data-bs-toggle="modal" data-bs-target="#aparaturImgModal" data-img="<?= $foto_path ?>">
              </td>
              <td><?= htmlspecialchars($row['nama']) ?></td>
              <td><?= htmlspecialchars($row['jabatan']) ?></td>
              <td><?= htmlspecialchars($row['tempat_tanggal_lahir']) ?></td>
              <td><?= htmlspecialchars($row['masa_jabatan']) ?></td>
              <td class="text-center">
                <div class="aparatur-btn-group btn-group">
                  <a href="index.php?page=edit_aparatur&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning text-white">✏️ Edit</a>
                  <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#hapusModal<?= $row['id'] ?>">🗑 Hapus</button>
                </div>

                <!-- Modal Konfirmasi Hapus -->
                <div class="modal fade" id="hapusModal<?= $row['id'] ?>" tabindex="-1">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                      <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title fw-bold">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body text-center">
                        <p>Apakah Anda yakin ingin menghapus aparatur <strong><?= htmlspecialchars($row['nama']) ?></strong>?</p>
                        <p class="text-muted small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
                      </div>
                      <div class="modal-footer justify-content-center">
                        <a href="index.php?page=aparatur&action=hapus&id=<?= $row['id'] ?>" class="btn btn-danger text-white">🗑 Hapus Sekarang</a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      </div>
                    </div>
                  </div>
                </div>

              </td>
            </tr>
            <?php } ?>
            <?php if (mysqli_num_rows($query) == 0): ?>
              <tr><td colspan="7" class="text-center text-muted">Belum ada data aparatur.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Preview Gambar -->
<div class="modal fade" id="aparaturImgModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <img id="aparaturModalImg" src="" class="aparatur-modal-img">
    </div>
  </div>
</div>


<!-- Modal Preview Gambar -->
<div class="modal fade" id="imgModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <img id="modalImg" src="" class="img-fluid rounded shadow">
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const aparaturImgModal = document.getElementById('aparaturImgModal');
  aparaturImgModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const imgSrc = button.getAttribute('data-img');
    document.getElementById('aparaturModalImg').src = imgSrc;
  });
</script>


</body>
</html>
