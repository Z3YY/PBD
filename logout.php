<?php
session_start();
session_unset(); // Hapus semua session
session_destroy(); // Hapus session dari server

header("Location: login.php"); // Redirect ke halaman login
exit();
?>
