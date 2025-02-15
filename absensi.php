<?php
include 'component/head.php';

// Ambil daftar pegawai
$stmt = $pdo->query("SELECT pegawai.id, users.name FROM pegawai JOIN users ON pegawai.user_id = users.id");
$pegawaiList = $stmt->fetchAll();
?>
<body class="bg-gray-100">
    <div class="min-h-screen p-5">
        <h1 class="text-2xl font-semibold">Absensi Pegawai</h1>
        <a href="tambah_absensi.php" class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded">Tambah Absensi</a>

        <!-- Tabel Absensi Pegawai -->
        <div class="mt-6 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Riwayat Absensi</h2>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">ID</th>
                        <th class="border p-2">Nama Pegawai</th>
                        <th class="border p-2">Tanggal</th>
                        <th class="border p-2">Status</th>
                        <th class="border p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT absensi.id, users.name, absensi.tanggal, absensi.status FROM absensi 
                                        JOIN pegawai ON absensi.pegawai_id = pegawai.id 
                                        JOIN users ON pegawai.user_id = users.id 
                                        ORDER BY absensi.tanggal DESC");

                    while ($row = $stmt->fetch()) {
                        echo "<tr>
                                <td class='border p-2'>{$row['id']}</td>
                                <td class='border p-2'>{$row['name']}</td>
                                <td class='border p-2'>{$row['tanggal']}</td>
                                <td class='border p-2'>{$row['status']}</td>
                                <td class='border p-2'>
                                    <a href='edit_absensi.php?id={$row['id']}' class='bg-yellow-500 text-white px-2 py-1 rounded'>Edit</a>
                                    <a href='delete_absensi.php?id={$row['id']}' class='bg-red-500 text-white px-2 py-1 rounded' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>