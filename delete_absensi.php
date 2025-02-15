<?php
include 'component/head.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM absensi WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>alert('Absensi berhasil dihapus!'); window.location.href='absensi.php';</script>";
}
?>
