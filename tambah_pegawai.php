<?php
include 'component/head.php';
?>

<main class="pt-20 p-5">
    <h1 class="text-2xl font-semibold">Tambah Akun</h1>
    <form action="proses_tambah_akun.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md mt-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700">Foto Profil</label>
                <input type="file" name="photo" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label class="block text-gray-700">Nama</label>
                <input type="text" name="name" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label class="block text-gray-700">Peran</label>
                <select name="role" class="w-full p-2 border rounded" required>
                    <option value="pegawai">Pegawai</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div id="pegawaiFields">
                <div>
                    <label class="block text-gray-700">Jabatan</label>
                    <input type="text" name="jabatan" class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-gray-700">NIP</label>
                    <input type="text" name="nip" class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-gray-700">Gaji</label>
                    <input type="number" name="gaji" class="w-full p-2 border rounded">
                </div>
            </div>
        </div>
        <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Tambah Akun</button>
    </form>
</main>

<script>
    document.querySelector("select[name='role']").addEventListener("change", function() {
        const pegawaiFields = document.getElementById("pegawaiFields");
        if (this.value === "pegawai") {
            pegawaiFields.style.display = "block";
        } else {
            pegawaiFields.style.display = "none";
        }
    });
</script>
