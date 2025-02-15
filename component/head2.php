<?php
include './config/constants.php';
session_start();

// Ambil data user dari database berdasarkan sesi
$user_id = $_SESSION['user_id'] ?? null;
$user_photo = "https://i.pravatar.cc/150"; // Default foto

if ($user_id) {
    $stmt = $pdo->prepare("SELECT photo_profile FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if ($user && !empty($user['photo_profile'])) {
        $user_photo = $user['photo_profile']; // Ambil dari database
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">


        <!-- Navbar -->
        <header class="fixed top-0 left-0 w-full bg-blue-600 shadow-md z-40 p-4 flex items-center justify-between text-white">
            <!-- Profile Photo (Dari Database) -->
            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white">
                <img src="<?= htmlspecialchars($user_photo) ?>" alt="User Profile" class="w-full h-full object-cover">
            </div>
        </header>
