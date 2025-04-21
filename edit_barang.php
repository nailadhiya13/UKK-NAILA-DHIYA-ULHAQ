<?php
include 'koneksi.php';
include 'header.php';

$id = $_GET['id'];
$data = $koneksi->query("SELECT * FROM barang WHERE id_barang = $id")->fetch_assoc();
$kategori = $koneksi->query("SELECT * FROM kategori");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama_barang'];
    $kategori_id = $_POST['id_kategori'];
    $stok = $_POST['jumlah_stok'];
    $harga = $_POST['harga_barang'];
    $tanggal = $_POST['tanggal_masuk']; 

    $stmt = $koneksi->prepare("UPDATE barang SET nama_barang=?, id_kategori=?, jumlah_stok=?, harga_barang=?, tanggal_masuk=? WHERE id_barang=?");
    $stmt->bind_param("siidsi", $nama, $kategori_id, $stok, $harga, $tanggal, $id);
    $stmt->execute();

    echo "<div class='alert alert-success'>Data berhasil diperbarui!</div>";
    $data = $koneksi->query("SELECT * FROM barang WHERE id_barang = $id")->fetch_assoc();
}
?>

<h2>Edit Barang</h2>
<form method="POST" class="row g-3">
    <div class="col-md-6">
        <label>Nama Barang</label>
        <input type="text" name="nama_barang" class="form-control" value="<?= $data['nama_barang'] ?>" required>
    </div>
    <div class="col-md-6">
        <label>Kategori</label>
        <select name="id_kategori" class="form-select" required>
            <?php while ($row = $kategori->fetch_assoc()) {
                $selected = $row['id_kategori'] == $data['id_kategori'] ? "selected" : "";
                echo "<option value='{$row['id_kategori']}' $selected>{$row['nama_kategori']}</option>";
            } ?>
        </select>
    </div>
    <div class="col-md-4">
        <label>Jumlah Stok</label>
        <input type="number" name="jumlah_stok" class="form-control" value="<?= $data['jumlah_stok'] ?>" required>
    </div>
    <div class="col-md-4">
        <label>Harga Barang</label>
        <input type="number" name="harga_barang" class="form-control" value="<?= $data['harga_barang'] ?>" required>
    </div>
    <div class="col-md-4">
        <label>Tanggal Masuk</label>
        <input type="date" name="tanggal_masuk" class="form-control" value="<?= $data['tanggal_masuk'] ?>" required>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-success">Update</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </div>
</form>

<?php include 'footer.php'; ?>