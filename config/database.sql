
CREATE DATABASE IF NOT EXISTS db_parkir;
USE db_parkir;

-- Tabel admin
CREATE TABLE IF NOT EXISTS admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    nama_petugas VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

INSERT INTO admin (nama_petugas, username, password) 
VALUES ('Tukang Parkir Ajaib', 'admin', 'admin123');

-- Tabel parkir
CREATE TABLE IF NOT EXISTS tabel_parkir (
    id_parkir INT AUTO_INCREMENT PRIMARY KEY,
    nomor_plat VARCHAR(20) NOT NULL,
    jenis_kendaraan ENUM('Roda 2', 'Roda 4') NOT NULL,
    waktu_masuk DATETIME NOT NULL,
    waktu_keluar DATETIME NULL,
    status ENUM('Parkir', 'Selesai') DEFAULT 'Parkir',
    total_bayar INT DEFAULT 0
);

INSERT INTO tabel_parkir (nomor_plat, jenis_kendaraan, waktu_masuk, waktu_keluar, status, total_bayar) VALUES
('L 1234 AB', 'Roda 2', '2026-09-30 07:00:00', '2026-09-30 08:30:00', 'Selesai', 3000),
('N 5678 CD', 'Roda 4', '2026-09-30 07:30:00', '2026-09-30 10:00:00', 'Selesai', 7000),
('S 9999 XY', 'Roda 2', '2026-09-30 08:00:00', '2026-09-30 08:45:00', 'Selesai', 2000),
('W 4321 ZZ', 'Roda 4', '2026-09-30 08:15:00', '2026-09-30 11:30:00', 'Selesai', 8000),
('AE 1111 AA', 'Roda 2', '2026-09-30 09:00:00', NULL, 'Parkir', 0),
('B 2024 BC', 'Roda 4', '2026-09-30 09:15:00', NULL, 'Parkir', 0); 