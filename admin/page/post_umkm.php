<form action="simpan_umkm.php" method="POST" enctype="multipart/form-data">
  <div class="mb-3">
    <label for="judul" class="form-label">Judul umkm</label>
    <input type="text" name="judul" id="judul" class="form-control" required>
  </div>
  <div class="mb-3">
    <label for="isi" class="form-label">Isi umkm</label>
    <textarea name="isi" id="isi" class="form-control" rows="5" required></textarea>
  </div>
  <div class="mb-3">
    <label for="gambar" class="form-label">Upload Gambar</label>
    <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*" required>
  </div>
  <button type="submit" class="btn btn-primary">Posting</button>
</form>
