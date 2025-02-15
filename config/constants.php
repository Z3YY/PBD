<?php
$host = "localhost";
$dbname = "uas_crud"; // Ganti dengan nama database yang kamu buat
$username = "root"; // Sesuaikan dengan username database kamu
$password = ""; // Sesuaikan jika ada password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>