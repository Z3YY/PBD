<?php
session_start();

// Jika belum login atau bukan admin, kembalikan ke login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}
?>
