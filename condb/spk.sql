-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2022 at 02:46 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk`
--

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(10) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `type` enum('benefit','cost') NOT NULL,
  `bobot` float NOT NULL,
  `ada_pilihan` tinyint(1) DEFAULT NULL,
  `urutan_order` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `nama`, `type`, `bobot`, `ada_pilihan`, `urutan_order`) VALUES
(11, 'C1', 'benefit', 1, 0, 0),
(12, 'C2', 'benefit', 0.75, 0, 1),
(13, 'C3', 'benefit', 0.5, 0, 2),
(14, 'C4', 'cost', 0.25, 0, 5),
(15, 'C5', 'cost', 0.5, 0, 20);

-- --------------------------------------------------------

--
-- Table structure for table `nilai_pegawai`
--

CREATE TABLE `nilai_pegawai` (
  `id_nilai_pegawai` int(11) NOT NULL,
  `id_pegawai` int(10) NOT NULL,
  `id_kriteria` int(10) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nilai_pegawai`
--

INSERT INTO `nilai_pegawai` (`id_nilai_pegawai`, `id_pegawai`, `id_kriteria`, `nilai`) VALUES
(25, 8, 11, 5),
(26, 8, 12, 4),
(27, 8, 13, 4),
(28, 8, 14, 4),
(30, 6, 11, 5),
(31, 6, 12, 4),
(32, 6, 13, 4),
(33, 6, 14, 5),
(35, 7, 11, 4),
(36, 7, 12, 5),
(37, 7, 13, 5),
(38, 7, 14, 5),
(43, 6, 15, 5),
(48, 7, 15, 5),
(53, 8, 15, 5),
(83, 9, 11, 4),
(84, 9, 12, 4),
(85, 9, 13, 5),
(86, 9, 14, 3),
(87, 9, 15, 4),
(88, 10, 11, 5),
(89, 10, 12, 5),
(90, 10, 13, 4),
(91, 10, 14, 5),
(92, 10, 15, 4),
(93, 11, 11, 4),
(94, 11, 12, 4),
(95, 11, 13, 4),
(96, 11, 14, 4),
(97, 11, 15, 5),
(98, 12, 11, 5),
(99, 12, 12, 5),
(100, 12, 13, 5),
(101, 12, 14, 5),
(102, 12, 15, 5),
(103, 13, 11, 5),
(104, 13, 12, 3),
(105, 13, 13, 4),
(106, 13, 14, 4),
(107, 13, 15, 3),
(108, 14, 11, 4),
(109, 14, 12, 4),
(110, 14, 13, 3),
(111, 14, 14, 2),
(112, 14, 15, 4),
(113, 15, 11, 4),
(114, 15, 12, 4),
(115, 15, 13, 5),
(116, 15, 14, 5),
(117, 15, 15, 5),
(118, 16, 11, 3),
(119, 16, 12, 4),
(120, 16, 13, 4),
(121, 16, 14, 4),
(122, 16, 15, 5),
(123, 17, 11, 3),
(124, 17, 12, 4),
(125, 17, 13, 4),
(126, 17, 14, 4),
(127, 17, 15, 4),
(133, 18, 11, 5),
(134, 18, 12, 5),
(135, 18, 13, 5),
(136, 18, 14, 5),
(137, 18, 15, 5),
(138, 19, 11, 5),
(139, 19, 12, 5),
(140, 19, 13, 5),
(141, 19, 14, 4),
(142, 19, 15, 5),
(143, 20, 11, 3),
(144, 20, 12, 4),
(145, 20, 13, 5),
(146, 20, 14, 4),
(147, 20, 15, 2),
(148, 21, 11, 5),
(149, 21, 12, 5),
(150, 21, 13, 5),
(151, 21, 14, 5),
(152, 21, 15, 5),
(153, 22, 11, 4),
(154, 22, 12, 5),
(155, 22, 13, 5),
(156, 22, 14, 4),
(157, 22, 15, 5),
(158, 23, 11, 2),
(159, 23, 12, 5),
(160, 23, 13, 5),
(161, 23, 14, 5),
(162, 23, 15, 5),
(163, 24, 11, 5),
(164, 24, 12, 4),
(165, 24, 13, 4),
(166, 24, 14, 3),
(167, 24, 15, 3);

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` int(10) NOT NULL,
  `nomer` varchar(6) NOT NULL,
  `nama` text NOT NULL,
  `tanggal_input` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `nomer`, `nama`, `tanggal_input`) VALUES
(6, 'A1', 'SARIMIN', '2017-05-23'),
(7, 'A2', 'RAHMAD NUR', '2017-05-24'),
(8, 'A3', 'FADJAR K', '2017-05-24'),
(9, 'A4', 'AGUS TRIANTO', '2022-06-23'),
(10, 'A5', 'M KHOZIN', '2022-06-23'),
(11, 'A6', 'MUJIONO', '2022-06-23'),
(12, 'A7', 'RUSMANTO', '2022-06-23'),
(13, 'A8', 'SUDARMANTO', '2022-06-23'),
(14, 'A9', 'SUDARMANTO', '2022-06-23'),
(15, 'A10', 'SUYANTO', '2022-06-23'),
(16, 'A11', 'ANDRY WIBOWO', '2022-06-23'),
(17, 'A12', 'WAHYUD H', '2022-06-23'),
(18, 'A13', 'ASYARIE', '2022-06-23'),
(19, 'A14', 'ARIFIN', '2022-06-23'),
(20, 'A15', 'ANSORI', '2022-06-23'),
(21, 'A16', 'RATNA SARI', '2022-06-23'),
(22, 'A17', 'SAMSUDIN', '2022-06-23'),
(23, 'A18', 'YUGA YULIANTO', '2022-06-23'),
(24, 'A19', 'SUPRIYONO A', '2022-06-23');

-- --------------------------------------------------------

--
-- Table structure for table `pilihan_kriteria`
--

CREATE TABLE `pilihan_kriteria` (
  `id_pil_kriteria` int(10) NOT NULL,
  `id_kriteria` int(10) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `nilai` float NOT NULL,
  `urutan_order` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(5) NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nama` varchar(70) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `role` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama`, `email`, `alamat`, `role`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'ArionoS', 'yn@gmail.com', 'Clumprit', '1'),
(7, 'petugas', '670489f94b6997a870b148f74744ee5676304925', 'RajenR', 'rjn@gmail.com', 'Wajak', '2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `nilai_pegawai`
--
ALTER TABLE `nilai_pegawai`
  ADD PRIMARY KEY (`id_nilai_pegawai`),
  ADD UNIQUE KEY `id_pegawai_2` (`id_pegawai`,`id_kriteria`),
  ADD KEY `id_pegawai` (`id_pegawai`),
  ADD KEY `id_kriteria` (`id_kriteria`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_pegawai`);

--
-- Indexes for table `pilihan_kriteria`
--
ALTER TABLE `pilihan_kriteria`
  ADD PRIMARY KEY (`id_pil_kriteria`),
  ADD KEY `id_kriteria` (`id_kriteria`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `nilai_pegawai`
--
ALTER TABLE `nilai_pegawai`
  MODIFY `id_nilai_pegawai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_pegawai` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `pilihan_kriteria`
--
ALTER TABLE `pilihan_kriteria`
  MODIFY `id_pil_kriteria` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `nilai_pegawai`
--
ALTER TABLE `nilai_pegawai`
  ADD CONSTRAINT `nilai_pegawai_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`),
  ADD CONSTRAINT `nilai_pegawai_ibfk_2` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`);

--
-- Constraints for table `pilihan_kriteria`
--
ALTER TABLE `pilihan_kriteria`
  ADD CONSTRAINT `pilihan_kriteria_ibfk_1` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
