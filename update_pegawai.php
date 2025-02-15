<?php
include 'component/head.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role']; // Tambahkan input role
    $photo_path = null;

    try {
        $pdo->beginTransaction();

        // Cek apakah ada upload foto baru
        if (!empty($_FILES['photo']['name'])) {
            $photo = $_FILES['photo'];
            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];

            if (!in_array($photo['type'], $allowed_types)) {
                die("Format gambar tidak valid! Hanya diperbolehkan JPG, JPEG, atau PNG.");
            }

            $photo_name = time() . '_' . $photo['name'];
            $photo_path = "uploads/" . $photo_name;
            move_uploaded_file($photo['tmp_name'], $photo_path);

            // Update foto profil user
            $stmt = $pdo->prepare("UPDATE users SET photo_profile = ? WHERE id = ?");
            $stmt->execute([$photo_path, $id]);
        }

        // Update data user (nama, email, dan role)
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->execute([$name, $email, $role, $id]);

        // Jika user adalah pegawai, update juga tabel pegawai
        if ($role === 'pegawai') {
            $jabatan = $_POST['jabatan'] ?? null;
            $nip = $_POST['nip'] ?? null;
            $gaji = $_POST['gaji'] ?? 0;

            // Cek apakah user sudah ada di tabel pegawai
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM pegawai WHERE user_id = ?");
            $stmt->execute([$id]);
            $isPegawai = $stmt->fetchColumn();

            if ($isPegawai) {
                // Jika sudah ada, update datanya
                $stmt = $pdo->prepare("UPDATE pegawai SET jabatan = ?, nip = ?, gaji = ? WHERE user_id = ?");
                $stmt->execute([$jabatan, $nip, $gaji, $id]);
            } else {
                // Jika belum ada, tambahkan ke tabel pegawai
                $stmt = $pdo->prepare("INSERT INTO pegawai (user_id, jabatan, nip, gaji) VALUES (?, ?, ?, ?)");
                $stmt->execute([$id, $jabatan, $nip, $gaji]);
            }
        } else {
            // Jika user diubah jadi admin, hapus datanya dari tabel pegawai
            $stmt = $pdo->prepare("DELETE FROM pegawai WHERE user_id = ?");
            $stmt->execute([$id]);
        }

        $pdo->commit();
        header("Location: index.php?success=Data berhasil diperbarui");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Gagal mengupdate akun: " . $e->getMessage());
    }
}
?>
