<?php
include 'component/head.php';
?>

<main class="pt-20 p-5">
    <h1 class="text-2xl font-semibold">Slip Gaji Pegawai</h1>

    <!-- Tabel Slip Gaji -->
    <div class="mt-6 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Daftar Slip Gaji</h2>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">ID</th>
                    <th class="border p-2">Nama</th>
                    <th class="border p-2">Bulan</th>
                    <th class="border p-2">Tahun</th>
                    <th class="border p-2">Total Gaji</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT slip_gaji.id, users.name, slip_gaji.bulan, slip_gaji.tahun, slip_gaji.total_gaji 
                                     FROM slip_gaji 
                                     JOIN pegawai ON slip_gaji.pegawai_id = pegawai.id 
                                     JOIN users ON pegawai.user_id = users.id 
                                     ORDER BY slip_gaji.tahun DESC, slip_gaji.bulan DESC");

                while ($row = $stmt->fetch()) {
                    echo "<tr>
                            <td class='border p-2'>{$row['id']}</td>
                            <td class='border p-2'>{$row['name']}</td>
                            <td class='border p-2'>{$row['bulan']}</td>
                            <td class='border p-2'>{$row['tahun']}</td>
                            <td class='border p-2'>Rp " . number_format($row['total_gaji'], 0, ',', '.') . "</td>
                            <td class='border p-2'>
                                <a href='cetak_slip.php?id={$row['id']}' class='bg-blue-500 text-white px-2 py-1 rounded'>Cetak</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>
