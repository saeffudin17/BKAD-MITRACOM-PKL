-- Database Name: e_arsip_sp2d
CREATE DATABASE IF NOT EXISTS e_arsip_sp2d;
USE e_arsip_sp2d;

CREATE TABLE `users` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('Pegawai', 'Admin', 'Superadmin') NOT NULL DEFAULT 'Pegawai',
  `status` ENUM('Aktif', 'Nonaktif') DEFAULT 'Aktif',
  `foto` VARCHAR(255) DEFAULT 'default.png',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `instansi` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `nama_instansi` VARCHAR(150) NOT NULL,
  `alamat` TEXT,
  `no_telepon` VARCHAR(20),
  `email` VARCHAR(100),
  `status_aktif` ENUM('Aktif', 'Nonaktif') DEFAULT 'Aktif',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `kegiatan` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `jenis_kegiatan` VARCHAR(100) NOT NULL,
  `nama_kegiatan` VARCHAR(255) NOT NULL,
  `status_aktif` ENUM('Aktif', 'Nonaktif') DEFAULT 'Aktif',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `rak` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `kode_rak` VARCHAR(20) NOT NULL UNIQUE,
  `nama_rak` VARCHAR(100) NOT NULL,
  `lokasi` VARCHAR(100) NOT NULL,
  `kapasitas` INT(11) NOT NULL,
  `jumlah_arsip` INT(11) DEFAULT 0,
  `status` ENUM('Tersedia', 'Penuh') DEFAULT 'Tersedia',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `bindex` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `nomor_bindex` VARCHAR(50) NOT NULL UNIQUE,
  `nama_bindex` VARCHAR(100) NOT NULL,
  `id_rak` INT(11) NOT NULL,
  `keterangan` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`id_rak`) REFERENCES `rak`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `arsip_sp2d` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `id_instansi` INT(11) NOT NULL,
  `nomor_sp2d` VARCHAR(100) NOT NULL UNIQUE,
  `nomor_spm` VARCHAR(100) NULL,
  `tanggal_spm` DATE NULL,
  `jenis_kegiatan` VARCHAR(100) NOT NULL,
  `nama_kegiatan` VARCHAR(255) NOT NULL,
  `tanggal_sp2d` DATE NOT NULL,
  `tanggal_arsip` DATE NOT NULL,
  `id_bindex` INT(11) NOT NULL,
  `id_rak` INT(11) NOT NULL,
  `jumlah_halaman` INT(11) DEFAULT 0,
  `jumlah_sp2d` INT(11) DEFAULT 0,
  `status_arsip` ENUM('Diproses', 'Dikembalikan') DEFAULT 'Diproses',
  `status_pengembalian` ENUM('Disimpan', 'Dipinjam', 'Dikembalikan') DEFAULT 'Disimpan',
  `tanggal_pengembalian` DATE NULL,
  `penerima_pengembalian` VARCHAR(100) NULL,
  `file_pdf` VARCHAR(255) NULL,
  `catatan` TEXT,
  `id_user` INT(11) NOT NULL,
  `is_deleted` TINYINT(1) DEFAULT 0, 
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`id_instansi`) REFERENCES `instansi`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`id_bindex`) REFERENCES `bindex`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`id_rak`) REFERENCES `rak`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `activity_logs` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `id_user` INT(11) NOT NULL,
  `action` VARCHAR(50) NOT NULL, 
  `description` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`nama`, `username`, `password`, `role`, `status`) VALUES 
('Super Administrator', 'superadmin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Superadmin', 'Aktif'),
('Administrator', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'Aktif'),
('Pegawai Satu', 'pegawai1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pegawai', 'Aktif');

INSERT INTO `instansi` (`nama_instansi`, `alamat`, `no_telepon`, `email`, `status_aktif`) VALUES 
('Dinas Pendidikan', 'Jl. Pendidikan No. 1', '081234567890', 'diknas@example.com', 'Aktif'),
('Dinas Kesehatan', 'Jl. Kesehatan No. 2', '081234567891', 'dinkes@example.com', 'Aktif');

INSERT INTO `rak` (`kode_rak`, `nama_rak`, `lokasi`, `kapasitas`, `jumlah_arsip`, `status`) VALUES 
('RK-A1', 'Rak Arsip A1', 'Lantai 1 - Ruang Arsip Utama', 100, 0, 'Tersedia'),
('RK-A2', 'Rak Arsip A2', 'Lantai 1 - Ruang Arsip Utama', 100, 0, 'Tersedia');

INSERT INTO `bindex` (`nomor_bindex`, `nama_bindex`, `id_rak`, `keterangan`) VALUES 
('BDX-2026-001', 'Bindex SP2D Januari', 1, 'Bindex khusus bulan Januari'),
('BDX-2026-002', 'Bindex SP2D Februari', 1, 'Bindex khusus bulan Februari');
