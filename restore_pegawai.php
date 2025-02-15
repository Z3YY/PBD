<?php
include 'component/head.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $pdo->beginTransaction();

        // Ambil informasi user sebelum direstore
        $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die("User tidak ditemukan.");
        }

        // Jika user adalah pegawai, kembalikan data pegawai
        if ($user['role'] === 'pegawai') {
            $stmt = $pdo->prepare("UPDATE pegawai SET deleted_at = NULL WHERE user_id = ?");
            $stmt->execute([$id]);
        }

        // Kembalikan akun di users
        $stmt = $pdo->prepare("UPDATE users SET deleted_at = NULL WHERE id = ?");
        $stmt->execute([$id]);

        $pdo->commit();
        header("Location: deleted_pegawai.php?success=Akun berhasil direstore");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Gagal merestore akun: " . $e->getMessage());
    }
} else {
    die("ID tidak valid.");
}
?>
