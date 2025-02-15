<?php
include 'component/head.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $pdo->beginTransaction();

        // Ambil informasi user sebelum dihapus
        $stmt = $pdo->prepare("SELECT role, photo_profile FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die("User tidak ditemukan.");
        }

        // Jika user adalah pegawai, update deleted_at di pegawai
        if ($user['role'] === 'pegawai') {
            $stmt = $pdo->prepare("UPDATE pegawai SET deleted_at = NOW() WHERE user_id = ?");
            $stmt->execute([$id]);
        }

        // Update deleted_at di users
        $stmt = $pdo->prepare("UPDATE users SET deleted_at = NOW() WHERE id = ?");
        $stmt->execute([$id]);

        $pdo->commit();
        header("Location: index.php?success=Akun berhasil dinonaktifkan");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Gagal menonaktifkan akun: " . $e->getMessage());
    }
} else {
    die("ID tidak valid.");
}
?>
