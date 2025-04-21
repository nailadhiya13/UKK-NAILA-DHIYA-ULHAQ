<?php include 'Koneksi.php'; include 'header.php'; ?>

<div class="container mt-4">
    <h2 class="fw-bold text-success mb-3">🔷 Daftar Barang</h2>

    <form method="GET" class="mb-3 row g-2">
        <div class="col-md-5">
            <input type="text" name="cari" class="form-control" placeholder="Cari nama barang..." 
                value="<?= isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : '' ?>">
        </div>
        <div class="col-md-4">
            <select name="kategori" class="form-select">
                <option value="">🔽 Semua Kategori</option>
                <?php
                $kategori_q = $koneksi->query("SELECT * FROM kategori");
                while ($kat = $kategori_q->fetch_assoc()) {
                    $selected = (isset($_GET['kategori']) && $_GET['kategori'] == $kat['id_kategori']) ? 'selected' : '';
                    echo "<option value='{$kat['id_kategori']}' $selected>{$kat['nama_kategori']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-success w-100" type="submit">🔍 Cari</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow-sm rounded">
            <thead class="text-center" style="background-color: #343a40; color: #198754;">
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Tanggal Masuk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $cari = isset($_GET['cari']) ? $_GET['cari'] : '';
                $kategori_filter = isset($_GET['kategori']) ? $_GET['kategori'] : '';

                $sql = "SELECT barang.*, kategori.nama_kategori 
                        FROM barang 
                        JOIN kategori ON barang.id_kategori = kategori.id_kategori 
                        WHERE 1=1"; // untuk mempermudah tambah filter dinamis

                if (!empty($cari)) {
                    $sql .= " AND barang.nama_barang LIKE '%" . $koneksi->real_escape_string($cari) . "%'";
                }

                if (!empty($kategori_filter)) {
                    $sql .= " AND barang.id_kategori = " . intval($kategori_filter);
                }

                $sql .= " ORDER BY barang.id_barang ASC";

                $result = $koneksi->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class='text-center'>
                                <td>{$row['id_barang']}</td>
                                <td>{$row['nama_barang']}</td>
                                <td>{$row['nama_kategori']}</td>
                                <td>{$row['jumlah_stok']}</td>
                                <td>Rp " . number_format($row['harga_barang'], 0, ',', '.') . "</td>
                                <td>{$row['tanggal_masuk']}</td>
                                <td>
                                    <a href='edit_barang.php?id={$row['id_barang']}' class='btn btn-success btn-sm me-1'>✏️ Edit</a>
                                    <a href='hapus_barang.php?id={$row['id_barang']}' class='btn btn-outline-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus?')\">🗑 Hapus</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center text-muted'>Tidak ada data barang ditemukan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="mb-3">
    <a href="export_excel.php?cari=<?= urlencode($cari) ?>&kategori=<?= urlencode($kategori_filter) ?>" class="btn btn-outline-success">
        🧾 Ekspor ke Excel
    </a>
</div>

    </div>
</div>

<?php include 'footer.php'; ?>
