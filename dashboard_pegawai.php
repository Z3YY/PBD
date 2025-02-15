<?php
session_start();
include 'config/constants.php';

// Cek apakah user sudah login dan berperan sebagai pegawai
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pegawai') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data pegawai berdasarkan user_id
$stmt = $pdo->prepare("SELECT users.name, users.photo_profile, pegawai.jabatan, pegawai.gaji 
                       FROM users 
                       JOIN pegawai ON users.id = pegawai.user_id 
                       WHERE users.id = ?");
$stmt->execute([$user_id]);
$pegawai = $stmt->fetch();

// Cek status absen hari ini
$stmt = $pdo->prepare("SELECT * FROM absensi WHERE pegawai_id = ? AND DATE(tanggal) = CURDATE()");
$stmt->execute([$user_id]);
$absen_today = $stmt->fetch();

// Ambil data slip gaji pegawai
$stmt = $pdo->prepare("SELECT bulan, tahun, total_gaji FROM slip_gaji WHERE pegawai_id = ? ORDER BY tahun DESC, bulan DESC");
$stmt->execute([$user_id]);
$slip_gaji = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pegawai</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col items-center justify-center min-h-screen bg-gray-100">

    <div class="bg-white p-6 rounded-lg shadow-lg w-[400px] text-center">
        <h2 class="mb-4 text-2xl font-bold text-gray-700">Dashboard Pegawai</h2>
        
        <!-- Data Diri Pegawai -->
        <div class="flex flex-col items-center">
            <img src="<?= $pegawai['photo_profile'] ?>" class="w-24 h-24 mb-3 border-2 border-gray-300 rounded-full" alt="Foto Profil">
            <p class="text-xl font-semibold"><?= $pegawai['name'] ?></p>
            <p class="text-gray-600"><?= $pegawai['jabatan'] ?></p>
            <p class="font-bold text-gray-700">Gaji: Rp <?= number_format($pegawai['gaji'], 2, ',', '.') ?></p>
        </div>

        <!-- Status Absen -->
        <?php if ($absen_today): ?>
            <p class="mt-4 text-green-500">✅ Anda sudah absen hari ini</p>
        <?php else: ?>
            <form action="proses_absen.php" method="POST" class="mt-4">
                <input type="hidden" name="user_id" value="<?= $user_id ?>">
                <button type="submit"
                    class="px-4 py-2 font-bold text-white transition duration-300 bg-blue-600 rounded-lg hover:bg-blue-700">
                    Absen Sekarang
                </button>
            </form>
        <?php endif; ?>

        <!-- Riwayat Slip Gaji -->
        <div class="mt-6">
            <h3 class="mb-2 text-lg font-semibold">Riwayat Slip Gaji</h3>
            <div class="p-3 text-left bg-gray-200 rounded-lg">
                <?php if (!empty($slip_gaji)): ?>
                    <ul class="text-gray-700">
                        <?php foreach ($slip_gaji as $slip): ?>
                            <li class="flex justify-between py-1 border-b border-gray-300 last:border-b-0">
                                <span><?= $slip['bulan'] ?> <?= $slip['tahun'] ?></span>
                                <span class="font-bold">Rp <?= number_format($slip['total_gaji'], 2, ',', '.') ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-gray-500">Belum ada slip gaji tersedia.</p>
                <?php endif; ?>
            </div>
        </div>

        <a href="logout.php" class="block mt-6 text-red-500 hover:underline">Logout</a>
    </div>

</body>
</html>
