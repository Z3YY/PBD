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

    <!-- Wrapper -->
    <div x-data="{ open: false }" class="relative min-h-screen">

        <!-- Sidebar (Offcanvas) -->
        <aside x-show="open" x-cloak x-transition
            @click.away="open = false"
            class="fixed inset-y-0 left-0 z-50 w-64 p-5 transition-transform duration-300 ease-in-out transform bg-white shadow-lg lg:translate-x-0 dark:bg-gray-900"
            :class="open ? 'translate-x-0' : '-translate-x-full'">

            <!-- Header Sidebar -->
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-white">Menu</h2>
                <button @click="open = false" class="p-2 text-gray-600 rounded-lg hover:bg-gray-200">
                    ✖
                </button>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-5">
                <a href="index.php" class="flex items-center block p-3 text-gray-700 rounded-lg hover:bg-gray-200">
                    🏠 <span class="ml-2">Home</span>
                </a>
                <a href="absensi.php" class="flex items-center block p-3 text-gray-700 rounded-lg hover:bg-gray-200">
                    📅 <span class="ml-2">Absensi</span>
                </a>
                <a href="slip_gaji.php" class="flex items-center block p-3 text-gray-700 rounded-lg hover:bg-gray-200">
                    💰 <span class="ml-2">Slip Gaji</span>
                </a>


                <a href="logout.php" class="block mt-4 text-red-500 hover:underline">Logout</a>

            </nav>

        </aside>

        <!-- Navbar -->
        <header class="fixed top-0 left-0 z-40 flex items-center justify-between w-full p-4 bg-white shadow-md dark:bg-gray-900">

            <!-- Sidebar Toggle Button -->
            <button @click="open = !open" class="p-2 text-gray-600 rounded-lg hover:bg-gray-200">
                ☰
            </button>

            <!-- Logo -->
            <a href="#" class="mr-auto text-xl font-bold text-blue-600">MyLogo</a>



            <!-- Profile Photo -->
            <div class="w-10 h-10 overflow-hidden border-2 border-gray-300 rounded-full">
            <img src="<?= htmlspecialchars($user_photo) ?>" alt="User Profile" class="object-cover w-full h-full">
     
            </div>

        </header>


<script>
    document.addEventListener("alpine:init", () => {
    Alpine.data("pegawaiData", () => ({
        hapusPegawai(id) {
            if (confirm("Yakin ingin menghapus pegawai ini?")) {
                window.location.href = `hapus_pegawai.php?id=${id}`;
            }
        }
    }));
});
</script>