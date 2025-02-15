<?php
include 'component/head.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role']; // Ambil role dari form
    $photo_path = null;

    // Upload foto jika ada
    if (!empty($_FILES['photo']['name'])) {
        $photo = $_FILES['photo'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        
        if (!in_array($photo['type'], $allowed_types)) {
            die("Format gambar tidak valid! Hanya diperbolehkan JPG, JPEG, atau PNG.");
        }
        
        $photo_name = time() . '_' . $photo['name'];
        $photo_path = "uploads/" . $photo_name;
        move_uploaded_file($photo['tmp_name'], $photo_path);
    }

    try {
        $pdo->beginTransaction();
        
        // Tambahkan user ke tabel users
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, photo_profile) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $role, $photo_path]);

        $user_id = $pdo->lastInsertId();

        // Jika peran adalah pegawai, tambahkan ke tabel pegawai
        if ($role === 'pegawai') {
            $jabatan = $_POST['jabatan'] ?? null;
            $nip = $_POST['nip'] ?? null;
            $gaji = $_POST['gaji'] ?? 0;

            $stmt = $pdo->prepare("INSERT INTO pegawai (user_id, nip, jabatan, gaji) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $nip, $jabatan, $gaji]);
        }

        $pdo->commit();
        header("Location: index.php?success=Akun berhasil ditambahkan");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Gagal menambahkan akun: " . $e->getMessage());
    }
}
?>
