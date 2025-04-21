<?php
include 'Koneksi.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Data_Barang_" . date('Y-m-d') . ".xls");

$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
$kategori_filter = isset($_GET['kategori']) ? $_GET['kategori'] : '';

$sql = "SELECT barang.*, kategori.nama_kategori 
        FROM barang 
        JOIN kategori ON barang.id_kategori = kategori.id_kategori 
        WHERE 1=1";

if (!empty($cari)) {
    $sql .= " AND barang.nama_barang LIKE '%" . $koneksi->real_escape_string($cari) . "%'";
}

if (!empty($kategori_filter)) {
    $sql .= " AND barang.id_kategori = " . intval($kategori_filter);
}

$sql .= " ORDER BY barang.id_barang ASC";

$result = $koneksi->query($sql);

echo "<table border='1'>
        <tr style='background-color: #f2f2f2; font-weight: bold;'>
            <th>ID</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Stok</th>
            <th>Harga</th>
            <th>Tanggal Masuk</th>
        </tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id_barang']}</td>
            <td>{$row['nama_barang']}</td>
            <td>{$row['nama_kategori']}</td>
            <td>{$row['jumlah_stok']}</td>
            <td>" . number_format($row['harga_barang'], 0, ',', '.') . "</td>
            <td>{$row['tanggal_masuk']}</td>
          </tr>";
}

echo "</table>";
?>
