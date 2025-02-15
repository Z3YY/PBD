<?php
include 'component/head.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM absensi WHERE id = ?");
    $stmt->execute([$id]);
    $absensi = $stmt->fetch();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE absensi SET status = ? WHERE id = ?");
    if ($stmt->execute([$status, $id])) {
        echo "<script>alert('Absensi berhasil diperbarui!'); window.location.href='absensi.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui absensi!');</script>";
    }
}
?>

<body class="bg-gray-100">
    <div class="min-h-screen p-5">
        <h1 class="text-2xl font-semibold">Edit Absensi</h1>
        <form method="POST" class="bg-white p-6 rounded-lg shadow-md mt-4">
            <input type="hidden" name="id" value="<?= $absensi['id'] ?>">
            <div>
                <label for="status" class="block text-gray-700">Status</label>
                <select id="status" name="status" class="w-full p-2 border rounded">
                    <option value="hadir" <?= ($absensi['status'] == 'hadir') ? 'selected' : '' ?>>Hadir</option>
                    <option value="izin" <?= ($absensi['status'] == 'izin') ? 'selected' : '' ?>>Izin</option>
                    <option value="sakit" <?= ($absensi['status'] == 'sakit') ? 'selected' : '' ?>>Sakit</option>
                    <option value="alpha" <?= ($absensi['status'] == 'alpha') ? 'selected' : '' ?>>Alpha</option>
                </select>
            </div>
            <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
</body>
</html>
