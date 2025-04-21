<?php
session_start();
include 'koneksi.php';
include 'header.php';

// Tambah kategori
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama_kategori'];
    $koneksi->query("INSERT INTO kategori (nama_kategori) VALUES ('$nama')");
    $_SESSION['pesan'] = "<div class='alert alert-success text-center mt-3'>✅ Kategori berhasil ditambahkan.</div>";
    header("Location: kategori.php");
    exit;
}

// Reset semua kategori + reset ID
if (isset($_GET['reset'])) {
    $hapus = $koneksi->query("DELETE FROM kategori");
    if ($hapus) {
        $reset_id = $koneksi->query("ALTER TABLE kategori AUTO_INCREMENT = 1");
        if ($reset_id) {
            $_SESSION['pesan'] = "<div class='alert alert-success text-center mt-3'>✅ Semua kategori telah dihapus & ID di-reset.</div>";
        } else {
            $_SESSION['pesan'] = "<div class='alert alert-danger text-center mt-3'>❌ Gagal reset AUTO_INCREMENT.</div>";
        }
    } else {
        $_SESSION['pesan'] = "<div class='alert alert-danger text-center mt-3'>❌ Gagal menghapus kategori. Pastikan tidak ada data barang yang terhubung.</div>";
    }
    header("Location: kategori.php");
    exit;
}

// Hapus satu kategori dengan cek foreign key
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $cek = $koneksi->query("SELECT COUNT(*) as total FROM barang WHERE id_kategori = $id");
    $data = $cek->fetch_assoc();

    if ($data['total'] > 0) {
        $_SESSION['pesan'] = "<div class='alert alert-warning text-center mt-3'>⚠️ Kategori tidak bisa dihapus karena masih digunakan pada barang.</div>";
    } else {
        $koneksi->query("DELETE FROM kategori WHERE id_kategori = $id");
        $_SESSION['pesan'] = "<div class='alert alert-success text-center mt-3'>✅ Kategori berhasil dihapus.</div>";
    }
    header("Location: kategori.php");
    exit;
}

$kategori = $koneksi->query("SELECT * FROM kategori");
?>

<div class="container mt-4">
    <?php
    if (isset($_SESSION['pesan'])) {
        echo $_SESSION['pesan'];
        unset($_SESSION['pesan']);
    }
    ?>

    <div class="card shadow-lg border-0">
        <div class="card-header text-white" style="background: linear-gradient(135deg, #1cc88a, #20c9a6);">
            <h4 class="mb-0">📂 Manajemen Kategori</h4>
        </div>
        <div class="card-body bg-light">
            <form method="POST" class="row g-3 mb-4">
                <div class="col-md-8">
                    <input type="text" name="nama_kategori" class="form-control" placeholder="📝 Nama Kategori" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" name="tambah" class="btn btn-success w-100">➕ Tambah Kategori</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover bg-white shadow-sm">
                    <thead class="table-success text-center">
                        <tr>
                            <th>ID</th>
                            <th>Nama Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $kategori->fetch_assoc()) { ?>
                            <tr class="align-middle text-center">
                                <td><?= $row['id_kategori'] ?></td>
                                <td><?= $row['nama_kategori'] ?></td>
                                <td>
                                    <a href="?hapus=<?= $row['id_kategori'] ?>" onclick="return confirm('Hapus kategori ini?')" class="btn btn-outline-danger btn-sm">🗑️ Hapus</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-3">
                <a href="?reset=true" onclick="return confirm('Yakin ingin menghapus semua kategori dan reset ID?')" class="btn btn-outline-secondary btn-sm">♻️ Reset Semua</a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
