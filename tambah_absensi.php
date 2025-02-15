<?php
include 'component/head.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pegawai_id = $_POST['pegawai_id'];
    $tanggal = $_POST['tanggal'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO absensi (pegawai_id, tanggal, status) VALUES (?, ?, ?)");
    if ($stmt->execute([$pegawai_id, $tanggal, $status])) {
        echo "<script>alert('Absensi berhasil ditambahkan!'); window.location.href='absensi.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan absensi!');</script>";
    }
}
?>

<body class="bg-gray-100">
    <div class="min-h-screen p-5">
        <h1 class="text-2xl font-semibold">Tambah Absensi</h1>
        <form method="POST" class="bg-white p-6 rounded-lg shadow-md mt-4">
            <div>
                <label for="pegawai_id" class="block text-gray-700">Pegawai</label>
                <select id="pegawai_id" name="pegawai_id" class="w-full p-2 border rounded">
                    <?php
                    $pegawaiStmt = $pdo->query("SELECT id, (SELECT name FROM users WHERE users.id = pegawai.user_id) AS name FROM pegawai");
                    while ($pegawai = $pegawaiStmt->fetch()) {
                        echo "<option value='{$pegawai['id']}'>{$pegawai['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="tanggal" class="block text-gray-700">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label for="status" class="block text-gray-700">Status</label>
                <select id="status" name="status" class="w-full p-2 border rounded">
                    <option value="hadir">Hadir</option>
                    <option value="izin">Izin</option>
                    <option value="sakit">Sakit</option>
                    <option value="alpha">Alpha</option>
                </select>
            </div>
            <button type="submit" class="mt-4 bg-green-600 text-white px-4 py-2 rounded">Tambah</button>
        </form>
    </div>
</body>
</html>