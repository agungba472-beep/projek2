-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 26 Nov 2025 pada 03.15
-- Versi server: 12.2.0-MariaDB-log
-- Versi PHP: 8.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `sarpras_jtik`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`admin_id`, `user_id`, `nama`, `jabatan`) VALUES
(1, 2, 'agung hidayat', 'admin jtik');

-- --------------------------------------------------------

--
-- Struktur dari tabel `aset`
--

CREATE TABLE `aset` (
  `aset_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis` varchar(50) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `tanggal_peroleh` date DEFAULT NULL,
  `umur_maksimal` int(11) DEFAULT NULL,
  `nilai` decimal(15,2) DEFAULT NULL,
  `kondisi` enum('Baik','Rusak','Hilang','Dipinjam') DEFAULT 'Baik',
  `status` enum('Tersedia','Dipinjam','Diperbaiki') DEFAULT 'Tersedia',
  `tanggal_input` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aset`
--

INSERT INTO `aset` (`aset_id`, `nama`, `jenis`, `lokasi`, `tanggal_peroleh`, `umur_maksimal`, `nilai`, `kondisi`, `status`, `tanggal_input`) VALUES
(1, 'pc', 'hardware', 'gudang', '2020-01-18', 5, 1000.00, 'Baik', 'Tersedia', '2025-11-17'),
(4, 'pc 2', 'elektronik', 'gudang', '2014-01-19', 30, 1000000.00, 'Rusak', 'Tersedia', '2025-11-19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen`
--

CREATE TABLE `dosen` (
  `dosen_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `nidn` varchar(50) DEFAULT NULL,
  `prodi` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `komplain`
--

CREATE TABLE `komplain` (
  `komplain_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `peminjaman_id` int(11) DEFAULT NULL,
  `kategori` varchar(50) NOT NULL,
  `deskripsi` text NOT NULL,
  `status` varchar(20) DEFAULT 'menunggu',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `komplain`
--

INSERT INTO `komplain` (`komplain_id`, `user_id`, `peminjaman_id`, `kategori`, `deskripsi`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, NULL, 'ruangan', 'teori 2 ac rusak', 'menunggu', '2025-11-23 16:39:44', '2025-11-23 16:39:44');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_inventaris`
--

CREATE TABLE `laporan_inventaris` (
  `laporan_id` int(11) NOT NULL,
  `periode` varchar(20) NOT NULL,
  `periode_awal` date DEFAULT NULL,
  `periode_akhir` date DEFAULT NULL,
  `tanggal_dibuat` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `laporan_inventaris`
--

INSERT INTO `laporan_inventaris` (`laporan_id`, `periode`, `periode_awal`, `periode_akhir`, `tanggal_dibuat`) VALUES
(1, '2025-11', NULL, NULL, '2025-11-18 20:58:35'),
(2, '2025-11', NULL, NULL, '2025-11-18 21:21:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_inventaris_detail`
--

CREATE TABLE `laporan_inventaris_detail` (
  `detail_id` int(11) NOT NULL,
  `laporan_id` int(11) NOT NULL,
  `aset_id` int(11) NOT NULL,
  `kondisi` enum('baik','rusak','pemutihan') NOT NULL,
  `catatan` text DEFAULT NULL,
  `tanggal_pemutihan` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `laporan_inventaris_detail`
--

INSERT INTO `laporan_inventaris_detail` (`detail_id`, `laporan_id`, `aset_id`, `kondisi`, `catatan`, `tanggal_pemutihan`) VALUES
(1, 1, 1, 'baik', 'Aset melewati umur maksimal dan perlu dilakukan pemutihan.', '2025-01-18'),
(2, 2, 4, 'rusak', 'Aset dalam kondisi rusak dan belum dipemutihkan.', '2025-11-19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_kerusakan`
--

CREATE TABLE `laporan_kerusakan` (
  `laporan_id` int(11) NOT NULL,
  `aset_id` int(11) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('Menunggu','Diverifikasi','SedangDikerjakan','Selesai') DEFAULT 'Menunggu',
  `tanggal_lapor` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `mahasiswa_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `nim` varchar(50) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `maintenance`
--

CREATE TABLE `maintenance` (
  `maintenance_id` int(11) NOT NULL,
  `aset_id` int(11) NOT NULL,
  `teknisi_id` int(11) DEFAULT NULL,
  `jenis` enum('Rutin','Insiden') NOT NULL DEFAULT 'Rutin',
  `tanggal_dijadwalkan` date NOT NULL,
  `tanggal_mulai` datetime DEFAULT NULL,
  `tanggal_selesai` datetime DEFAULT NULL,
  `status` enum('Terjadwal','Proses','Selesai','Gagal') NOT NULL DEFAULT 'Terjadwal',
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `maintenance`
--

INSERT INTO `maintenance` (`maintenance_id`, `aset_id`, `teknisi_id`, `jenis`, `tanggal_dijadwalkan`, `tanggal_mulai`, `tanggal_selesai`, `status`, `catatan`) VALUES
(18, 4, 7, 'Rutin', '2025-11-26', NULL, NULL, 'Terjadwal', 'asd');

-- --------------------------------------------------------

--
-- Struktur dari tabel `maintenance_detail`
--

CREATE TABLE `maintenance_detail` (
  `detail_id` int(11) NOT NULL,
  `maintenance_id` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `kondisi_sebelum` varchar(255) DEFAULT NULL,
  `kondisi_sesudah` varchar(255) DEFAULT NULL,
  `tindakan` varchar(255) NOT NULL DEFAULT 'Pengecekan',
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `maintenance_detail`
--

INSERT INTO `maintenance_detail` (`detail_id`, `maintenance_id`, `deskripsi`, `kondisi_sebelum`, `kondisi_sesudah`, `tindakan`, `foto`) VALUES
(3, 18, 'sad', 'aman', 'aman', 'tidak ada', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman_fasilitas`
--

CREATE TABLE `peminjaman_fasilitas` (
  `peminjaman_fasilitas_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nama_peminjam` varchar(100) DEFAULT NULL,
  `jam_pinjam` time NOT NULL,
  `jam_kembali` time NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('Menunggu','Disetujui','Dipinjam','Dikembalikan','ditolak') DEFAULT 'Menunggu',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjaman_fasilitas`
--

INSERT INTO `peminjaman_fasilitas` (`peminjaman_fasilitas_id`, `user_id`, `nama_peminjam`, `jam_pinjam`, `jam_kembali`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 'jaenal', '07:01:00', '10:02:00', NULL, 'ditolak', '2025-11-23 17:03:57', '2025-11-25 18:01:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman_fasilitas_detail`
--

CREATE TABLE `peminjaman_fasilitas_detail` (
  `id` int(11) NOT NULL,
  `peminjaman_fasilitas_id` int(11) NOT NULL,
  `nama_fasilitas` varchar(100) DEFAULT NULL,
  `jumlah` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjaman_fasilitas_detail`
--

INSERT INTO `peminjaman_fasilitas_detail` (`id`, `peminjaman_fasilitas_id`, `nama_fasilitas`, `jumlah`) VALUES
(1, 1, 'Proyektor', 1),
(2, 1, 'Terminal', 1),
(3, 1, 'Kabel HDMI', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman_ruangan`
--

CREATE TABLE `peminjaman_ruangan` (
  `peminjaman_ruangan_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nama_peminjam` varchar(100) DEFAULT NULL,
  `nama_ruangan` varchar(100) NOT NULL,
  `mata_kuliah` varchar(100) DEFAULT NULL,
  `dosen_pengampu` varchar(100) DEFAULT NULL,
  `jam_pinjam` datetime DEFAULT NULL,
  `jam_kembali` datetime DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('Menunggu','Disetujui','Dipinjam','Dikembalikan') DEFAULT 'Menunggu',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjaman_ruangan`
--

INSERT INTO `peminjaman_ruangan` (`peminjaman_ruangan_id`, `user_id`, `nama_peminjam`, `nama_ruangan`, `mata_kuliah`, `dosen_pengampu`, `jam_pinjam`, `jam_kembali`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 'agung', 'teori 2', 'matematika', 'masesa', '2025-11-23 01:23:00', '2025-11-23 03:23:00', NULL, 'Disetujui', '2025-11-23 14:24:26', '2025-11-25 17:56:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `teknisi`
--

CREATE TABLE `teknisi` (
  `teknisi_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `bidang` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tugas_perbaikan`
--

CREATE TABLE `tugas_perbaikan` (
  `tugas_id` int(11) NOT NULL,
  `laporan_id` int(11) DEFAULT NULL,
  `teknisi_id` int(11) DEFAULT NULL,
  `status` enum('Baru','Proses','Selesai','Gagal') DEFAULT 'Baru',
  `waktu_mulai` datetime DEFAULT NULL,
  `waktu_selesai` datetime DEFAULT NULL,
  `hasil` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Mahasiswa','Dosen','Admin','Teknisi') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `last_seen` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `role`, `created_at`, `status`, `last_seen`) VALUES
(1, 'agung', '$2y$12$NsqOmXuixQ9tYmjzdY/3tuDXUysyEOSO3h2V.T6K7C/exWMEtVjam', 'Mahasiswa', '2025-11-04 14:23:39', 'aktif', '2025-11-25 10:07:46'),
(2, 'agung2', '$2y$12$xw47hsnINFStOG9kka4kDukEHelEYcbXJ1Eh.0WECVjzMkV2wj1eG', 'Admin', '2025-11-08 17:44:37', 'aktif', '2025-11-25 11:18:18'),
(3, 'agung5', '$2y$12$0vn5eE8ZDXnGIhUPgHBZ0OK3kFdUStjfVX9gRP2NZzyAWa3BaQaOy', 'Dosen', '2025-11-08 17:44:37', 'nonaktif', '2025-11-24 16:55:33'),
(5, 'jaenal', '$2y$12$8u5ABXW.NMw2qrvFx36Vve8tuI8qrljWPJSiDobgfit/cWiSjE036', 'Mahasiswa', '2025-11-23 13:45:23', 'nonaktif', '2025-11-24 06:56:22'),
(7, 'naura', '$2y$12$Ma.eVGu/w1KrJb7UsVaqvOWSWzYQL9oxcjjyWUdkn1nJhdzTH.O3u', 'Teknisi', '2025-11-24 23:57:20', 'nonaktif', '2025-11-25 08:31:26'),
(8, 'ihsan', '$2y$12$8GrZKZciBrlFpISAbdbRa.R06naHYAGfvDk9W0IXGoW5LiCR/ScZ6', 'Teknisi', '2025-11-25 14:35:59', 'nonaktif', NULL);

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `fk_admin_user` (`user_id`);

--
-- Indeks untuk tabel `aset`
--
ALTER TABLE `aset`
  ADD PRIMARY KEY (`aset_id`);

--
-- Indeks untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`dosen_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `komplain`
--
ALTER TABLE `komplain`
  ADD PRIMARY KEY (`komplain_id`);

--
-- Indeks untuk tabel `laporan_inventaris`
--
ALTER TABLE `laporan_inventaris`
  ADD PRIMARY KEY (`laporan_id`);

--
-- Indeks untuk tabel `laporan_inventaris_detail`
--
ALTER TABLE `laporan_inventaris_detail`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `laporan_id` (`laporan_id`),
  ADD KEY `aset_id` (`aset_id`);

--
-- Indeks untuk tabel `laporan_kerusakan`
--
ALTER TABLE `laporan_kerusakan`
  ADD PRIMARY KEY (`laporan_id`),
  ADD KEY `aset_id` (`aset_id`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`mahasiswa_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `maintenance`
--
ALTER TABLE `maintenance`
  ADD PRIMARY KEY (`maintenance_id`);

--
-- Indeks untuk tabel `maintenance_detail`
--
ALTER TABLE `maintenance_detail`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `fk_detail_maintenance` (`maintenance_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `peminjaman_fasilitas`
--
ALTER TABLE `peminjaman_fasilitas`
  ADD PRIMARY KEY (`peminjaman_fasilitas_id`);

--
-- Indeks untuk tabel `peminjaman_fasilitas_detail`
--
ALTER TABLE `peminjaman_fasilitas_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peminjaman_fasilitas_id` (`peminjaman_fasilitas_id`);

--
-- Indeks untuk tabel `peminjaman_ruangan`
--
ALTER TABLE `peminjaman_ruangan`
  ADD PRIMARY KEY (`peminjaman_ruangan_id`);

--
-- Indeks untuk tabel `teknisi`
--
ALTER TABLE `teknisi`
  ADD PRIMARY KEY (`teknisi_id`),
  ADD KEY `fk_teknisi_user` (`user_id`);

--
-- Indeks untuk tabel `tugas_perbaikan`
--
ALTER TABLE `tugas_perbaikan`
  ADD PRIMARY KEY (`tugas_id`),
  ADD UNIQUE KEY `laporan_id` (`laporan_id`),
  ADD KEY `teknisi_id` (`teknisi_id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `aset`
--
ALTER TABLE `aset`
  MODIFY `aset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `dosen`
--
ALTER TABLE `dosen`
  MODIFY `dosen_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `komplain`
--
ALTER TABLE `komplain`
  MODIFY `komplain_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `laporan_inventaris`
--
ALTER TABLE `laporan_inventaris`
  MODIFY `laporan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `laporan_inventaris_detail`
--
ALTER TABLE `laporan_inventaris_detail`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `laporan_kerusakan`
--
ALTER TABLE `laporan_kerusakan`
  MODIFY `laporan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `mahasiswa_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `maintenance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `maintenance_detail`
--
ALTER TABLE `maintenance_detail`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `peminjaman_fasilitas`
--
ALTER TABLE `peminjaman_fasilitas`
  MODIFY `peminjaman_fasilitas_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `peminjaman_fasilitas_detail`
--
ALTER TABLE `peminjaman_fasilitas_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `peminjaman_ruangan`
--
ALTER TABLE `peminjaman_ruangan`
  MODIFY `peminjaman_ruangan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `teknisi`
--
ALTER TABLE `teknisi`
  MODIFY `teknisi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tugas_perbaikan`
--
ALTER TABLE `tugas_perbaikan`
  MODIFY `tugas_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `fk_admin_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD CONSTRAINT `1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `laporan_inventaris_detail`
--
ALTER TABLE `laporan_inventaris_detail`
  ADD CONSTRAINT `1` FOREIGN KEY (`laporan_id`) REFERENCES `laporan_inventaris` (`laporan_id`),
  ADD CONSTRAINT `2` FOREIGN KEY (`aset_id`) REFERENCES `aset` (`aset_id`);

--
-- Ketidakleluasaan untuk tabel `laporan_kerusakan`
--
ALTER TABLE `laporan_kerusakan`
  ADD CONSTRAINT `1` FOREIGN KEY (`aset_id`) REFERENCES `aset` (`aset_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `maintenance_detail`
--
ALTER TABLE `maintenance_detail`
  ADD CONSTRAINT `fk_detail_maintenance` FOREIGN KEY (`maintenance_id`) REFERENCES `maintenance` (`maintenance_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `peminjaman_fasilitas_detail`
--
ALTER TABLE `peminjaman_fasilitas_detail`
  ADD CONSTRAINT `1` FOREIGN KEY (`peminjaman_fasilitas_id`) REFERENCES `peminjaman_fasilitas` (`peminjaman_fasilitas_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `teknisi`
--
ALTER TABLE `teknisi`
  ADD CONSTRAINT `fk_teknisi_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Ketidakleluasaan untuk tabel `tugas_perbaikan`
--
ALTER TABLE `tugas_perbaikan`
  ADD CONSTRAINT `1` FOREIGN KEY (`laporan_id`) REFERENCES `laporan_kerusakan` (`laporan_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `2` FOREIGN KEY (`teknisi_id`) REFERENCES `teknisi` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
