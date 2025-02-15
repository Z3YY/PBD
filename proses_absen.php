<?php
session_start();
include 'config/constants.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Cari pegawai_id berdasarkan user_id
$stmt = $pdo->prepare("SELECT id FROM pegawai WHERE user_id = ?");
$stmt->execute([$user_id]);
$pegawai = $stmt->fetch();

if (!$pegawai) {
    die("Error: Pegawai tidak ditemukan.");
}

$pegawai_id = $pegawai['id']; // Ambil pegawai_id yang benar

// Insert ke absensi
$stmt = $pdo->prepare("INSERT INTO absensi (pegawai_id, tanggal, status) VALUES (?, NOW(), 'Hadir')");
$stmt->execute([$pegawai_id]);

header("Location: dashboard_pegawai.php?success=Absen berhasil");
exit();
?>
