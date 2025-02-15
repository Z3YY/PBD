<?php
include 'component/head.php';

$stmt = $pdo->query("SELECT * FROM users WHERE deleted_at IS NOT NULL");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container p-6 mx-auto">
    <h2 class="mb-4 text-xl font-semibold text-gray-800">Daftar Akun yang Dihapus</h2>

    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full border border-gray-300">
            <thead class="text-white bg-gray-800">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Role</th>
                    <th class="px-4 py-2">Tanggal Dihapus</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr class="text-gray-700 border-b hover:bg-gray-100">
                        <td class="px-4 py-2"><?= $user['id'] ?></td>
                        <td class="px-4 py-2"><?= $user['name'] ?></td>
                        <td class="px-4 py-2"><?= $user['email'] ?></td>
                        <td class="px-4 py-2"><?= $user['role'] ?></td>
                        <td class="px-4 py-2"><?= $user['deleted_at'] ?></td>
                        <td class="px-4 py-2 text-center">
                            <a href="restore_pegawai.php?id=<?= $user['id'] ?>" class="px-3 py-1 text-white bg-blue-500 rounded hover:bg-blue-700">Restore</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
