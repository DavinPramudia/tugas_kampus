<?php
include 'koneksi.php';

// ==== PROSES HAPUS BERITA ====
if (isset($_GET['action']) && $_GET['action'] == "hapus") {
    $id = $_GET['id'];

    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT gambar FROM berita WHERE id = '$id'"));
    if ($data && file_exists("./assets/images/berita/" . $data['gambar'])) {
        unlink("./assets/images/berita/" . $data['gambar']);
    }

    $sql = mysqli_query($koneksi, "DELETE FROM berita WHERE id = '$id'");
    echo $sql
      ? "<script>alert('Berita berhasil dihapus'); window.location='index.php?page=berita';</script>"
      : "<script>alert('Gagal menghapus berita');</script>";
    exit;
}

$query = mysqli_query($koneksi, "SELECT * FROM berita ORDER BY id DESC");
?>

<div class="aparatur-container mt-4 mb-5">
  <div class="aparatur-card shadow-sm">
    <div class="aparatur-card-header d-flex justify-content-between align-items-center">
      📰 Daftar Berita Desa Baturusa
      <a href="index.php?page=post_berita" class="btn btn-light btn-sm">+ Tambah Berita</a>
    </div>

    <div class="aparatur-card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle aparatur-table">
          <thead class="table-success text-center">
            <tr>
              <th>No</th>
              <th>Gambar</th>
              <th>Judul</th>
              <th>Isi Singkat</th>
              <th width="15%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no = 1;
            while ($row = mysqli_fetch_assoc($query)) { 
              $isi_singkat = substr(strip_tags($row['isi']), 0, 80) . '...';
              $gambar_path = $row['gambar'] ? "./assets/images/berita/" . $row['gambar'] : "noimage.jpg";
            ?>
            <tr>
              <td class="text-center"><?= $no++ ?></td>
              <td class="text-center">
                <img src="<?= $gambar_path ?>" alt="Gambar" class="aparatur-img-thumb" 
                     data-bs-toggle="modal" data-bs-target="#imgModal" data-img="<?= $gambar_path ?>">
              </td>
              <td><?= htmlspecialchars($row['judul']) ?></td>
              <td><?= htmlspecialchars($isi_singkat) ?></td>
              <td class="text-center">
                <div class="aparatur-btn-group btn-group">
                  <a href="index.php?page=edit_berita&id=<?= $row['id'] ?>" 
                     class="btn btn-sm btn-warning text-white">✏️ Edit</a>
                  <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" 
                          data-bs-target="#hapusModal<?= $row['id'] ?>">🗑 Hapus</button>
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
                        <p>Apakah Anda yakin ingin menghapus berita <strong><?= htmlspecialchars($row['judul']) ?></strong>?</p>
                        <p class="text-muted small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
                      </div>
                      <div class="modal-footer justify-content-center">
                        <a href="index.php?page=berita&action=hapus&id=<?= $row['id'] ?>" class="btn btn-danger text-white">🗑 Hapus Sekarang</a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            <?php } ?>
            <?php if (mysqli_num_rows($query) == 0): ?>
              <tr><td colspan="5" class="text-center text-muted">Belum ada berita yang diposting.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Preview Gambar -->
<div class="modal fade" id="imgModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <img id="modalImg" src="" class="aparatur-modal-img">
    </div>
  </div>
</div>

<script>
const imgModal = document.getElementById('imgModal');
imgModal.addEventListener('show.bs.modal', e => {
  document.getElementById('modalImg').src = e.relatedTarget.getAttribute('data-img');
});
</script>
