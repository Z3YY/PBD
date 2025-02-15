<?php
include 'component/head.php';

$query = "SELECT * FROM pegawai";
$result = $conn->query($query);
?>

<main class="pt-20 p-5">
    <h1 class="text-2xl font-semibold">Daftar Pegawai</h1>
    
    <a href="tambah_pegawai.php" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded">Tambah Pegawai</a>
    
    <div class="mt-4 bg-white p-6 rounded-lg shadow-md">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 p-2">ID</th>
                    <th class="border border-gray-300 p-2">Foto</th>
                    <th class="border border-gray-300 p-2">Nama</th>
                    <th class="border border-gray-300 p-2">Jabatan</th>
                    <th class="border border-gray-300 p-2">NIP</th>
                    <th class="border border-gray-300 p-2">Gaji</th>
                    <th class="border border-gray-300 p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="border border-gray-300 p-2"><?php echo $row['id']; ?></td>
                    <td class="border border-gray-300 p-2">
                        <img src="<?php echo $row['photo']; ?>" alt="Foto" class="w-12 h-12 rounded-full">
                    </td>
                    <td class="border border-gray-300 p-2"><?php echo $row['name']; ?></td>
                    <td class="border border-gray-300 p-2"><?php echo $row['position']; ?></td>
                    <td class="border border-gray-300 p-2"><?php echo $row['nip']; ?></td>
                    <td class="border border-gray-300 p-2">Rp <?php echo number_format($row['salary'], 0, ',', '.'); ?></td>
                    <td class="border border-gray-300 p-2">
                        <a href="edit_pegawai.php?id=<?php echo $row['id']; ?>" class="text-blue-500">Edit</a> |
                        <a href="delete_pegawai.php?id=<?php echo $row['id']; ?>" class="text-red-500" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
