<?php
include 'koneksi.php';
include 'header.php';

// Ambil data kategori
$kategori = $koneksi->query("SELECT * FROM kategori");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama_barang'];
    $kategori_id = $_POST['id_kategori'];
    $stok = $_POST['jumlah_stok'];
    $harga = $_POST['harga_barang'];
    $tanggal = $_POST['tanggal_masuk'];

    // Validasi sederhana
    if ($nama && $stok >= 0 && $harga >= 0) {
        $stmt = $koneksi->prepare("INSERT INTO barang (nama_barang, id_kategori, jumlah_stok, harga_barang, tanggal_masuk) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siids", $nama, $kategori_id, $stok, $harga, $tanggal);
        $stmt->execute();

        echo "<div class='alert alert-success'>Barang berhasil ditambahkan!</div>";
    } else {
        echo "<div class='alert alert-danger'>Data tidak valid!</div>";
    }
}
?>

<h2></h2>
<form method="POST" class="row g-3">
        <div class="card-body">
            <form method="POST" class="row g-3">
            <div class="container mt-4">
    <div class="card shadow-lg border-0">
        <div class="card-header text-white" style="background: linear-gradient(135deg, #4e73df, #6f42c1);">
            <h4 class="mb-0">Tambah Barang</h4>
        </div>
        <div class="card-body bg-light">
            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <select name="id_kategori" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        <?php while ($row = $kategori->fetch_assoc()) {
                            echo "<option value='{$row['id_kategori']}'>{$row['nama_kategori']}</option>";
                        } ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jumlah Stok</label>
                    <input type="number" name="jumlah_stok" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Harga Barang</label>
                    <input type="number" name="harga_barang" class="form-control" step="0.01" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" class="form-control" required>
                </div>
                <div class="col-12 mt-3 d-flex justify-content-between">
                    <button type="submit" class="btn text-white" style="background-color: #4e73df;">💾 Simpan</button>
                    <a href="index.php" class="btn btn-outline-secondary">↩️ Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
