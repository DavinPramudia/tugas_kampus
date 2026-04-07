<?php
// Pastikan session aktif sebelum dihapus
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hapus semua data session
session_unset();
session_destroy();

// Redirect ke halaman login (gunakan lokasi relatif)
echo "<script>
    alert('Anda berhasil logout.');
    window.location.href = 'index.php?page=admin-login';
</script>";
exit;
?>
