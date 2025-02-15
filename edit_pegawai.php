<?php
include 'component/head.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user['role'] === 'pegawai') {
    $stmtPegawai = $pdo->prepare("SELECT * FROM pegawai WHERE user_id = ?");
    $stmtPegawai->execute([$id]);
    $pegawai = $stmtPegawai->fetch(PDO::FETCH_ASSOC);
}
?>

<main class="pt-20 p-5">
    <h1 class="text-2xl font-semibold">Edit Akun</h1>
    <form action="proses_edit_akun.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md mt-4">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700">Foto Profil</label>
                <input type="file" name="photo" class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block text-gray-700">Nama</label>
                <input type="text" name="name" value="<?= $user['name'] ?>" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" value="<?= $user['email'] ?>" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label class="block text-gray-700">Role</label>
                <select name="role" class="w-full p-2 border rounded" required>
                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="pegawai" <?= $user['role'] === 'pegawai' ? 'selected' : '' ?>>Pegawai</option>
                </select>
            </div>
            
            <?php if ($user['role'] === 'pegawai'): ?>
            <div>
                <label class="block text-gray-700">Jabatan</label>
                <input type="text" name="jabatan" value="<?= $pegawai['jabatan'] ?>" class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block text-gray-700">NIP</label>
                <input type="text" name="nip" value="<?= $pegawai['nip'] ?>" class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block text-gray-700">Gaji</label>
                <input type="number" name="gaji" value="<?= $pegawai['gaji'] ?>" class="w-full p-2 border rounded">
            </div>
            <?php endif; ?>
        </div>
        <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Simpan Perubahan</button>
    </form>
</main>
