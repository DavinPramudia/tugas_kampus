<?php
include 'koneksi.php';

// ==== PROSES TERIMA / TOLAK USER ====
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == 'terima') {
        mysqli_query($koneksi, "UPDATE user SET status='diterima' WHERE id='$id'");
        echo "<script>alert('User diterima!'); window.location='index.php?page=user';</script>";
        exit;
    } elseif ($action == 'tolak') {
        mysqli_query($koneksi, "UPDATE user SET status='ditolak' WHERE id='$id'");
        echo "<script>alert('User ditolak!'); window.location='index.php?page=user';</script>";
        exit;
    } elseif ($action == 'hapus') {
        // ==== PROSES HAPUS USER ====
        $hapus = mysqli_query($koneksi, "DELETE FROM user WHERE id='$id'");
        echo $hapus
            ? "<script>alert('User berhasil dihapus'); window.location='index.php?page=user';</script>"
            : "<script>alert('Gagal menghapus user');</script>";
        exit;
    }
}

// ==== AMBIL SEMUA DATA USER ====
$query = mysqli_query($koneksi, "SELECT * FROM user ORDER BY id ASC");
?>

<link rel="stylesheet" href="assets/css/user-daftar.css">

<div class="user-container mt-4 mb-5">
  <div class="user-card shadow-sm">
    <div class="user-card-header d-flex justify-content-between align-items-center">
      👥 Daftar Pengguna Login Desa Baturusa
    </div>

    <div class="user-card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle user-table">
          <thead class="table-success text-center">
            <tr>
              <th>No</th>
              <th>NIK</th>
              <th>Nama</th>
              <th>Tempat</th>
              <th>Tanggal Lahir</th>
              <th>Alamat</th>
              <th>Email</th>
              <th>Username</th>
              <th>Status</th>
              <th width="18%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no = 1;
            while ($row = mysqli_fetch_assoc($query)) { ?>
            <tr class="<?=
              $row['status'] == 'ditolak' ? 'table-danger' :
              ($row['status'] == 'pending' ? 'table-warning' : '');
            ?>">
              <td class="text-center"><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['nik']) ?></td>
              <td><?= htmlspecialchars($row['nama']) ?></td>
              <td><?= htmlspecialchars($row['tempat']) ?></td>
              <td><?= htmlspecialchars($row['tanggal_lahir']) ?></td>
              <td><?= htmlspecialchars($row['alamat']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td class="text-center fw-bold text-capitalize">
                <?= htmlspecialchars($row['status']) ?>
              </td>
              <td class="text-center">
                <div class="user-btn-group btn-group">
                  <a href="index.php?page=edit_user&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning text-white">
                    ✏️ Edit
                  </a>

                  <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                          data-bs-target="#hapusModal<?= $row['id'] ?>">🗑 Hapus</button>
                </div>

                <!-- Tombol Terima / Tolak (hanya jika pending) -->
                <?php if ($row['status'] == 'pending'): ?>
                  <div class="mt-2 user-btn-group btn-group">
                    <a href="index.php?page=user&action=terima&id=<?= $row['id'] ?>" 
                       class="btn btn-sm btn-success">✅ Terima</a>
                    <a href="index.php?page=user&action=tolak&id=<?= $row['id'] ?>" 
                       class="btn btn-sm btn-danger">❌ Tolak</a>
                  </div>
                <?php endif; ?>

                <!-- Modal Konfirmasi Hapus -->
                <div class="modal fade" id="hapusModal<?= $row['id'] ?>" tabindex="-1">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                      <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title fw-bold">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body text-center">
                        <p>Apakah Anda yakin ingin menghapus user <strong><?= htmlspecialchars($row['nama']) ?></strong>?</p>
                        <p class="text-muted small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
                      </div>
                      <div class="modal-footer justify-content-center">
                        <a href="index.php?page=user&action=hapus&id=<?= $row['id'] ?>" class="btn btn-danger text-white">🗑 Hapus Sekarang</a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            <?php } ?>
            <?php if (mysqli_num_rows($query) == 0): ?>
              <tr><td colspan="10" class="text-center text-muted">Belum ada data user.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
