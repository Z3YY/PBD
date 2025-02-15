<?php
include 'component/head.php';
include 'admin_only.php';

// Ambil data pegawai (hanya yang berperan sebagai pegawai)
$stmt = $pdo->query("SELECT users.id, users.name, users.photo_profile, pegawai.jabatan, pegawai.nip, pegawai.gaji 
                    FROM users 
                    JOIN pegawai ON users.id = pegawai.user_id 
                    WHERE users.role = 'pegawai' AND users.deleted_at IS NULL");
$pegawai = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Ambil data admin
$stmtAdmin = $pdo->query("SELECT id, name, photo_profile FROM users WHERE role = 'admin' AND deleted_at IS NULL");
$admin = $stmtAdmin->fetchAll(PDO::FETCH_ASSOC);

// Ambil data statistik
$totalPegawai = $pdo->query("SELECT COUNT(*) FROM pegawai 
                             JOIN users ON pegawai.user_id = users.id 
                             WHERE users.deleted_at IS NULL")->fetchColumn();

$totalAdmin = $pdo->query("SELECT COUNT(*) FROM users 
                           WHERE role = 'admin' AND deleted_at IS NULL")->fetchColumn();

$totalAbsensi = $pdo->query("SELECT COUNT(*) FROM absensi 
                             JOIN pegawai ON absensi.pegawai_id = pegawai.id 
                             JOIN users ON pegawai.user_id = users.id 
                             WHERE users.deleted_at IS NULL")->fetchColumn();

$totalSlipGaji = $pdo->query("SELECT COUNT(*) FROM slip_gaji 
                              JOIN pegawai ON slip_gaji.pegawai_id = pegawai.id 
                              JOIN users ON pegawai.user_id = users.id 
                              WHERE users.deleted_at IS NULL")->fetchColumn();

$recentAbsensi = $pdo->query("SELECT users.name, absensi.tanggal, absensi.status FROM absensi 
                              JOIN pegawai ON absensi.pegawai_id = pegawai.id 
                              JOIN users ON pegawai.user_id = users.id 
                              ORDER BY absensi.tanggal DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT * FROM users WHERE deleted_at IS NULL");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<main class="p-5 pt-20">
    <h1 class="mb-4 text-2xl font-semibold">Dashboard Admin</h1>

    <!-- Statistik Dashboard -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="p-4 text-white bg-blue-500 rounded-lg shadow-md">
            <h2 class="text-lg">Total Pegawai</h2>
            <p class="text-3xl font-bold"><?= $totalPegawai ?></p>
        </div>
        <div class="p-4 text-white bg-purple-500 rounded-lg shadow-md">
            <h2 class="text-lg">Total Admin</h2>
            <p class="text-3xl font-bold"><?= $totalAdmin ?></p>
        </div>
        <div class="p-4 text-white bg-green-500 rounded-lg shadow-md">
            <h2 class="text-lg">Total Absensi</h2>
            <p class="text-3xl font-bold"><?= $totalAbsensi ?></p>
        </div>
        <div class="p-4 text-white bg-yellow-500 rounded-lg shadow-md">
            <h2 class="text-lg">Total Slip Gaji</h2>
            <p class="text-3xl font-bold"><?= $totalSlipGaji ?></p>
        </div>
    </div>

    <!-- Daftar Pegawai -->
    <div class="p-6 mb-6 bg-white rounded-lg shadow-md">
        <h2 class="mb-4 text-xl font-semibold">Daftar Pegawai</h2>
        <a href="tambah_pegawai.php" class="inline-block px-4 py-2 mt-4 text-white bg-green-600 rounded">Tambah Pegawai</a>
        <a href="deleted_pegawai.php" class="px-4 py-2 text-white bg-gray-600 rounded">Pegawai Terhapus</a>
        <table class="w-full mt-4 border border-collapse border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 border">ID</th>
                    <th class="p-2 border">Foto</th>
                    <th class="p-2 border">Nama</th>
                    <th class="p-2 border">Jabatan</th>
                    <th class="p-2 border">NIP</th>
                    <th class="p-2 border">Gaji</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody x-data="pegawaiData()">
                <?php $noPegawai = 1; ?>
                <?php foreach ($pegawai as $row): ?>
                    <tr>
                        <td class="p-2 border"><?= $noPegawai++ ?></td>
                        <td class="p-2 border"><img src="<?= $row['photo_profile'] ?>" class="w-10 h-10 rounded-full"></td>
                        <td class="p-2 border"><?= $row['name'] ?></td>
                        <td class="p-2 border"><?= $row['jabatan'] ?></td>
                        <td class="p-2 border"><?= $row['nip'] ?></td>
                        <td class="p-2 border">Rp <?= number_format($row['gaji'], 2, ',', '.') ?></td>
                        <td class="p-2 border">
                            <a href="edit_pegawai.php?id=<?= $row['id'] ?>" class="px-2 py-1 text-white bg-yellow-500 rounded">Edit</a>
                            <button @click="hapusPegawai(<?= $row['id'] ?>)" class="px-2 py-1 text-white bg-red-500 rounded">Hapus</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Daftar Admin -->
    <div class="p-6 bg-white rounded-lg shadow-md">
        <h2 class="mb-4 text-xl font-semibold">Daftar Admin</h2>
        <table class="w-full mt-4 border border-collapse border-gray-300" x-data="pegawaiData()">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 border">ID</th>
                    <th class="p-2 border">Foto</th>
                    <th class="p-2 border">Nama</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $noAdmin = 1; // Mulai nomor urut dari 1 
                ?>
                <?php foreach ($admin as $row): ?>
                    <tr>
                        <td class="p-2 border"><?= $noAdmin++ ?></td>
                        <td class="p-2 border"><img src="<?= $row['photo_profile'] ?>" class="w-10 h-10 rounded-full"></td>
                        <td class="p-2 border"><?= $row['name'] ?></td>
                        <td class="p-2 border">
                            <a href="edit_pegawai.php?id=<?= $row['id'] ?>" class="px-2 py-1 text-white bg-yellow-500 rounded">Edit</a>
                            <button @click="hapusPegawai(<?= $row['id'] ?>)" class="px-2 py-1 text-white bg-red-500 rounded">Hapus</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
</body>

</html>