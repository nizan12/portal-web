-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 25 Bulan Mei 2026 pada 16.16
-- Versi server: 8.0.30
-- Versi PHP: 8.5.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `poltree_db`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_tambah_pengguna` (IN `p_nik` INT, IN `p_nama` VARCHAR(150), IN `p_password` VARCHAR(255), IN `p_email` VARCHAR(150), IN `p_jabatan` VARCHAR(50))   BEGIN
    INSERT INTO t_pengguna (nik, nama_user, password, email, jabatan)
    VALUES (p_nik, p_nama, p_password, p_email, p_jabatan);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_28_000003_add_health_check_columns_to_t_link_table', 1),
(5, '2026_04_29_090610_add_role_to_t_link_table', 2),
(6, '2026_04_29_091753_change_role_to_enum_in_t_link_table', 3),
(7, '2026_04_29_145554_add_nik_to_t_kategori_table', 4),
(8, '2026_04_29_151706_add_id_kategori_to_t_link_table', 5),
(9, '2026_04_29_151718_add_id_kategori_to_t_link_table', 5),
(10, '2026_04_29_155434_create_password_reset_codes_table', 6),
(11, '2026_04_29_155626_add_email_to_t_admin_table', 7),
(12, '2026_04_29_160545_add_nik_to_t_link_table', 8),
(13, '2026_04_30_085529_fix_t_kategori_nik_pengguna_nullable', 9),
(14, '2026_05_05_113705_create_tags_table', 10),
(15, '2026_05_05_115502_cleanup_and_migrate_database', 11),
(16, '2026_05_05_120055_drop_unused_columns_from_tables', 12);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_admin`
--

CREATE TABLE `t_admin` (
  `nik_admin` int NOT NULL,
  `nama` varchar(150) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `t_admin`
--

INSERT INTO `t_admin` (`nik_admin`, `nama`, `email`, `password`) VALUES
(222336, 'admin1', NULL, 'Admin23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_kategori`
--

CREATE TABLE `t_kategori` (
  `id_kategori` int NOT NULL,
  `nik` varchar(50) DEFAULT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `icon` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `t_kategori`
--

INSERT INTO `t_kategori` (`id_kategori`, `nik`, `nama_kategori`, `icon`) VALUES
(9, NULL, 'Umum', 'grid');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_laporan`
--

CREATE TABLE `t_laporan` (
  `id_laporan` int NOT NULL,
  `nik_pelapor` int NOT NULL,
  `jenis_laporan` enum('Penambahan Link','Masalah Website','Masalah Akun','Lainnya') NOT NULL,
  `isi_laporan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `t_laporan`
--

INSERT INTO `t_laporan` (`id_laporan`, `nik_pelapor`, `jenis_laporan`, `isi_laporan`) VALUES
(1, 115143, 'Masalah Akun', 'Tidak bisa login ke sistem meskipun username dan password sudah benar.'),
(2, 122288, 'Masalah Website', 'Halaman website tidak bisa diakses sejak pagi, muncul error 500.'),
(3, 225359, 'Masalah Akun', 'Proses backup data gagal dilakukan.'),
(4, 225361, 'Masalah Akun', 'Sinkronisasi data antar sistem tidak berjalan.'),
(5, 218292, 'Penambahan Link', 'Minta penambahan website layanan baru untuk administrasi.'),
(6, 224345, 'Penambahan Link', 'Mohon tambahkan link portal akademik terbaru.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_link`
--

CREATE TABLE `t_link` (
  `id_link` int NOT NULL,
  `id_kategori` bigint UNSIGNED DEFAULT NULL,
  `nik` varchar(50) DEFAULT NULL,
  `nama_web` varchar(150) NOT NULL,
  `url` varchar(255) NOT NULL,
  `deskripsi` text,
  `role` enum('Dosen','Tata Usaha','Laboran') DEFAULT NULL,
  `status` varchar(50) DEFAULT 'aktif',
  `status_checked_at` timestamp NULL DEFAULT NULL,
  `status_http_code` smallint UNSIGNED DEFAULT NULL,
  `status_response_time_ms` int UNSIGNED DEFAULT NULL,
  `status_summary` varchar(255) DEFAULT NULL,
  `hit_point` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `t_link`
--

INSERT INTO `t_link` (`id_link`, `id_kategori`, `nik`, `nama_web`, `url`, `deskripsi`, `role`, `status`, `status_checked_at`, `status_http_code`, `status_response_time_ms`, `status_summary`, `hit_point`) VALUES
(1, 1, NULL, 'Beranda Polibatam', 'https://www.polibatam.ac.id/', 'Beranda Utama untuk website polibatam untuk memudahkan dosen untuk mengakses layanan polibatam', 'Dosen', 'aktif', '2026-05-22 01:29:51', 200, 190, 'Website merespons normal (HTTP 200).', 120),
(2, 9, NULL, 'Tentang Polibatam', 'https://www.polibatam.ac.id/tentang-polibatam/', 'Profil kampus', 'Tata Usaha', 'aktif', '2026-05-22 01:29:51', 200, 181, 'Website merespons normal (HTTP 200).', 98),
(3, 9, NULL, 'Alumni', 'https://www.polibatam.ac.id/alumni/', 'Portal Alumni', 'Laboran', 'aktif', '2026-05-22 01:29:51', 200, 172, 'Website merespons normal (HTTP 200).', 766),
(4, 1, NULL, 'PBL Expo Polibatam', 'https://pbl.polibatam.ac.id/expo/', 'PBL Expo', 'Tata Usaha', 'aktif', '2026-05-22 01:29:52', 200, 149, 'Website merespons normal (HTTP 200).', 775),
(5, 9, NULL, 'Staff Polibatam', 'https://www.polibatam.ac.id/staff/', 'Portal Staff', 'Dosen', 'aktif', '2026-05-22 01:29:52', 200, 195, 'Website merespons normal (HTTP 200).', 816),
(6, 9, NULL, 'SAKIP Polibatam', 'https://www.polibatam.ac.id/sakip-polibatam/', 'SAKIP', 'Laboran', 'aktif', '2026-05-22 01:29:52', 200, 176, 'Website merespons normal (HTTP 200).', 825),
(7, 9, NULL, 'SIMPEG', 'https://simpeg.polibatam.ac.id', 'Layanan website Politeknik Negeri Batam yang tersedia di portal POLTREE.', 'Laboran', 'bermasalah', '2026-05-22 01:29:52', NULL, 9, 'Gagal terhubung ke website: cURL error 6: Could not resolve host: simpeg.polibatam.ac.id (see https://curl.haxx.se/libcurl/c/libcurl-errors.html) for https://simpeg.polibatam.ac.id', 0),
(15, NULL, '113103', 'youtube', 'https://youtu.be/aSugSGCC12I?si=Nz-vI6sMPwdSoiXO', 'youtube', 'Dosen', 'aktif', '2026-05-22 01:29:52', 200, 1523, 'Website merespons normal (HTTP 200).', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_link_tag`
--

CREATE TABLE `t_link_tag` (
  `id` bigint UNSIGNED NOT NULL,
  `id_link` int NOT NULL,
  `id_tag` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `t_link_tag`
--

INSERT INTO `t_link_tag` (`id`, `id_link`, `id_tag`) VALUES
(2, 1, 2),
(3, 2, 2),
(6, 5, 2),
(7, 6, 2),
(9, 3, 2),
(10, 4, 2),
(11, 7, 2),
(30, 3, 10),
(31, 1, 10),
(32, 4, 10),
(33, 6, 10),
(34, 7, 10),
(35, 5, 10),
(36, 2, 10),
(37, 15, 10),
(38, 15, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_password_reset_codes`
--

CREATE TABLE `t_password_reset_codes` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pengguna`
--

CREATE TABLE `t_pengguna` (
  `nik` int NOT NULL,
  `nama_user` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `jabatan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `t_pengguna`
--

INSERT INTO `t_pengguna` (`nik`, `nama_user`, `password`, `email`, `jabatan`) VALUES
(113103, 'Ir. Maria, S.ST., M.Sn., IPP', '123456#', 'maria.ipp@polibatam.ac.id', 'Dosen'),
(115143, 'Ahmad Hamim Thohari, S.S.T., M.T.', '123456#', 'ahmad.thohari@polibatam.ac.id', 'Dosen'),
(122288, 'Festy Winda Sari, S.Tr. Kom., M.Sc', '123456#', 'festy.winda@polibatam.ac.id', 'Dosen'),
(218292, 'Dede Nurdiansyah, S.Sos', '123456#', 'dede.nurdiansyah@polibatam.ac.id', 'Tata Usaha'),
(224345, 'Rhanna Mawira, S.E', '123456#', 'rhanna.mawira@polibatam.ac.id', 'Tata Usaha'),
(225359, 'Miftahul Husna Ghawa, S.Tr.Kom', '123456#', 'miftahul.husna@polibatam.ac.id', 'Laboran'),
(225361, 'Yogi Ilhami, S.Tr.T', '123456#', 'yogi.ilhami@polibatam.ac.id', 'Laboran');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_tag`
--

CREATE TABLE `t_tag` (
  `id_tag` bigint UNSIGNED NOT NULL,
  `nama_tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `t_tag`
--

INSERT INTO `t_tag` (`id_tag`, `nama_tag`) VALUES
(10, 'baru'),
(2, 'Utama');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_terdaftar`
--

CREATE TABLE `t_terdaftar` (
  `id` int NOT NULL,
  `id_kategori` int NOT NULL,
  `id_link` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `t_admin`
--
ALTER TABLE `t_admin`
  ADD PRIMARY KEY (`nik_admin`);

--
-- Indeks untuk tabel `t_kategori`
--
ALTER TABLE `t_kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `t_laporan`
--
ALTER TABLE `t_laporan`
  ADD PRIMARY KEY (`id_laporan`),
  ADD KEY `fk_laporan_pengguna` (`nik_pelapor`);

--
-- Indeks untuk tabel `t_link`
--
ALTER TABLE `t_link`
  ADD PRIMARY KEY (`id_link`);

--
-- Indeks untuk tabel `t_link_tag`
--
ALTER TABLE `t_link_tag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `t_link_tag_id_link_foreign` (`id_link`),
  ADD KEY `t_link_tag_id_tag_foreign` (`id_tag`);

--
-- Indeks untuk tabel `t_password_reset_codes`
--
ALTER TABLE `t_password_reset_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `t_password_reset_codes_email_index` (`email`);

--
-- Indeks untuk tabel `t_pengguna`
--
ALTER TABLE `t_pengguna`
  ADD PRIMARY KEY (`nik`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- Indeks untuk tabel `t_tag`
--
ALTER TABLE `t_tag`
  ADD PRIMARY KEY (`id_tag`),
  ADD UNIQUE KEY `t_tag_nama_tag_unique` (`nama_tag`);

--
-- Indeks untuk tabel `t_terdaftar`
--
ALTER TABLE `t_terdaftar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_terdaftar_kategori` (`id_kategori`),
  ADD KEY `fk_terdaftar_link` (`id_link`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `t_kategori`
--
ALTER TABLE `t_kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `t_laporan`
--
ALTER TABLE `t_laporan`
  MODIFY `id_laporan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `t_link`
--
ALTER TABLE `t_link`
  MODIFY `id_link` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `t_link_tag`
--
ALTER TABLE `t_link_tag`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT untuk tabel `t_password_reset_codes`
--
ALTER TABLE `t_password_reset_codes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `t_tag`
--
ALTER TABLE `t_tag`
  MODIFY `id_tag` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `t_terdaftar`
--
ALTER TABLE `t_terdaftar`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `t_laporan`
--
ALTER TABLE `t_laporan`
  ADD CONSTRAINT `fk_laporan_pengguna` FOREIGN KEY (`nik_pelapor`) REFERENCES `t_pengguna` (`nik`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_link_tag`
--
ALTER TABLE `t_link_tag`
  ADD CONSTRAINT `t_link_tag_id_link_foreign` FOREIGN KEY (`id_link`) REFERENCES `t_link` (`id_link`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_link_tag_id_tag_foreign` FOREIGN KEY (`id_tag`) REFERENCES `t_tag` (`id_tag`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_terdaftar`
--
ALTER TABLE `t_terdaftar`
  ADD CONSTRAINT `fk_terdaftar_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `t_kategori` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_terdaftar_link` FOREIGN KEY (`id_link`) REFERENCES `t_link` (`id_link`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
