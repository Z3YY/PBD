<?php
include 'component/head.php';

// Ambil bulan dan tahun sebelumnya
$bulan = date('m', strtotime('-1 month'));
$tahun = date('Y', strtotime('-1 month'));

// Cek apakah slip gaji bulan ini sudah dibuat
$cekSlip = $pdo->prepare("SELECT COUNT(*) FROM slip_gaji WHERE bulan = ? AND tahun = ?");
$cekSlip->execute([$bulan, $tahun]);
$slipExist = $cekSlip->fetchColumn();

if ($slipExist == 0) {
    // Ambil semua pegawai dan gajinya
    $pegawaiStmt = $pdo->query("SELECT id, gaji FROM pegawai");
    $pegawaiList = $pegawaiStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($pegawaiList as $pegawai) {
        $insertSlip = $pdo->prepare("INSERT INTO slip_gaji (pegawai_id, bulan, tahun, total_gaji) VALUES (?, ?, ?, ?)");
        $insertSlip->execute([$pegawai['id'], $bulan, $tahun, $pegawai['gaji']]);
    }

    echo "Slip gaji untuk bulan $bulan-$tahun berhasil dibuat.";
} else {
    echo "Slip gaji bulan ini sudah ada.";
}
?>