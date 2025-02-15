-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Feb 2025 pada 12.25
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uas_crud`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi`
--

CREATE TABLE `absensi` (
  `id` int(11) NOT NULL,
  `pegawai_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('hadir','izin','sakit','alpha') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `absensi`
--

INSERT INTO `absensi` (`id`, `pegawai_id`, `tanggal`, `status`, `created_at`) VALUES
(11, 4, '2025-02-15', 'alpha', '2025-02-15 10:59:08'),
(12, 4, '2025-02-15', 'alpha', '2025-02-15 11:08:38');

--
-- Trigger `absensi`
--
DELIMITER $$
CREATE TRIGGER `auto_update_absensi` BEFORE INSERT ON `absensi` FOR EACH ROW BEGIN
    DECLARE pegawai_exist INT;

    -- Cek apakah pegawai dengan ID yang diberikan ada di tabel pegawai
    SELECT COUNT(*) INTO pegawai_exist FROM pegawai WHERE id = NEW.pegawai_id;

    IF pegawai_exist = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Pegawai tidak ditemukan, gagal menambahkan absensi';
    END IF;

    -- Jika absen dilakukan setelah pukul 09:00, set status menjadi Alpha
    IF CURRENT_TIME() > '09:00:00' THEN
        SET NEW.status = 'Alpha';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pegawai`
--

CREATE TABLE `pegawai` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `gaji` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pegawai`
--

INSERT INTO `pegawai` (`id`, `user_id`, `nip`, `jabatan`, `gaji`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 5, '10122392', 'Office Boy', 2000000.00, '2025-02-15 09:29:28', '2025-02-15 09:53:41', NULL);

--
-- Trigger `pegawai`
--
DELIMITER $$
CREATE TRIGGER `after_delete_pegawai` AFTER DELETE ON `pegawai` FOR EACH ROW INSERT INTO pegawai_deleted (pegawai_id, user_id, nip, jabatan, gaji, deleted_at)
VALUES (OLD.id, OLD.user_id, OLD.nip, OLD.jabatan, OLD.gaji, NOW())
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_pegawai` BEFORE INSERT ON `pegawai` FOR EACH ROW SET NEW.created_at = NOW(), NEW.updated_at = NOW()
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_pegawai` BEFORE UPDATE ON `pegawai` FOR EACH ROW SET NEW.updated_at = NOW()
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pegawai_deleted`
--

CREATE TABLE `pegawai_deleted` (
  `id` int(11) NOT NULL,
  `pegawai_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `gaji` decimal(15,2) DEFAULT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pegawai_deleted`
--

INSERT INTO `pegawai_deleted` (`id`, `pegawai_id`, `user_id`, `nip`, `jabatan`, `gaji`, `deleted_at`) VALUES
(1, 2, 3, '10122391', 'Office Boy', 2100000.00, '2025-02-14 23:12:20'),
(2, 1, 1, '10122390', 'Office Boy', 2000000.00, '2025-02-15 07:44:46'),
(3, 3, 4, '19122392', 'Office Boy', 2900000.00, '2025-02-15 08:19:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `slip_gaji`
--

CREATE TABLE `slip_gaji` (
  `id` int(11) NOT NULL,
  `pegawai_id` int(11) NOT NULL,
  `bulan` varchar(20) NOT NULL,
  `tahun` int(11) NOT NULL,
  `total_gaji` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `slip_gaji`
--

INSERT INTO `slip_gaji` (`id`, `pegawai_id`, `bulan`, `tahun`, `total_gaji`, `created_at`) VALUES
(3, 4, '01', 2025, 2000000.00, '2025-02-15 10:03:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL CHECK (`email` like '%@gmail.com' or `email` like '%@admin'),
  `password` varchar(255) NOT NULL,
  `role` enum('admin','pegawai') NOT NULL,
  `photo_profile` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `photo_profile`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'Tatsumaki v2', 'crowsansansan@gmail.com', '$2y$10$ZgEFbRHzMaoNEK8Mzp3mX.OG5r9joNFtedc5CS0x4jbh18cPMdXC.', 'admin', 'uploads/1739566796_wallpaperflare.com_wallpaper.jpg', '2025-02-14 20:59:56', '2025-02-14 20:59:56', NULL),
(5, 'Rappa', 'rappa@gmail.com', '$2y$10$qeDnBLDruA0t4jO7r2BYjerU9UdTb6LYDgbxGcHnYBJcAdVO/k8iq', 'pegawai', 'uploads/1739611768_ꕥ.jpg', '2025-02-15 09:29:28', '2025-02-15 09:53:41', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pegawai_id` (`pegawai_id`);

--
-- Indeks untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- Indeks untuk tabel `pegawai_deleted`
--
ALTER TABLE `pegawai_deleted`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `slip_gaji`
--
ALTER TABLE `slip_gaji`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pegawai_id` (`pegawai_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `pegawai_deleted`
--
ALTER TABLE `pegawai_deleted`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `slip_gaji`
--
ALTER TABLE `slip_gaji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `pegawai_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `slip_gaji`
--
ALTER TABLE `slip_gaji`
  ADD CONSTRAINT `slip_gaji_ibfk_1` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
