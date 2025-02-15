<?php
include 'component/head.php';

if (!isset($_GET['id'])) {
    die("ID Slip Gaji tidak ditemukan!");
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT slip_gaji.*, users.name FROM slip_gaji 
                       JOIN pegawai ON slip_gaji.pegawai_id = pegawai.id 
                       JOIN users ON pegawai.user_id = users.id 
                       WHERE slip_gaji.id = ?");
$stmt->execute([$id]);
$slip = $stmt->fetch();

if (!$slip) {
    die("Slip Gaji tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 50%; margin: auto; padding: 20px; border: 1px solid #ddd; }
        .btn-print { background: blue; color: white; padding: 10px; border: none; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <h2>Slip Gaji</h2>
    <p><strong>Nama:</strong> <?= $slip['name'] ?></p>
    <p><strong>Bulan:</strong> <?= $slip['bulan'] ?></p>
    <p><strong>Tahun:</strong> <?= $slip['tahun'] ?></p>
    <p><strong>Total Gaji:</strong> Rp <?= number_format($slip['total_gaji'], 0, ',', '.') ?></p>

    <button class="btn-print" onclick="window.print()">Cetak Slip</button>
</div>

</body>
</html>
