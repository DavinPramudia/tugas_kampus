<form action="simpan_aparatur.php" method="POST" enctype="multipart/form-data">
  <div class="mb-3">
    <label for="nama" class="form-label">Nama Aparatur</label>
    <input type="text" name="nama" id="nama" class="form-control" required>
  </div>

  <div class="mb-3">
    <label for="jabatan" class="form-label">Jabatan</label>
    <input type="text" name="jabatan" id="jabatan" class="form-control" required>
  </div>

  <div class="mb-3">
    <label for="tempat_tanggal_lahir" class="form-label">Tempat, Tanggal Lahir</label>
    <input type="text" name="tempat_tanggal_lahir" id="tempat_tanggal_lahir" class="form-control" placeholder="Contoh: Pangkalpinang, 12 Mei 1990" required>
  </div>

  <div class="mb-3">
    <label for="masa_jabatan" class="form-label">Masa Jabatan</label>
    <input type="text" name="masa_jabatan" id="masa_jabatan" class="form-control" placeholder="Contoh: 2022 - 2028" required>
  </div>

  <div class="mb-3">
    <label for="foto" class="form-label">Upload Foto</label>
    <input type="file" name="foto" id="foto" class="form-control" accept="image/*" required>
  </div>

  <button type="submit" class="btn btn-primary">Simpan</button>
</form>
