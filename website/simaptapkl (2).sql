-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2023 at 04:38 AM
-- Server version: 10.4.19-MariaDB-log
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simaptapkl`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabel_absensi`
--

CREATE TABLE `tabel_absensi` (
  `ID` int(11) NOT NULL,
  `KD_PST` varchar(50) DEFAULT NULL,
  `TGL` date DEFAULT NULL,
  `CHECK_IN` time DEFAULT NULL,
  `LOKASI_CHECK_IN` text DEFAULT NULL,
  `CHECK_OUT` time DEFAULT NULL,
  `LOKASI_CHECK_OUT` text DEFAULT NULL,
  `KETERANGAN` varchar(200) DEFAULT NULL,
  `KEHADIRAN` varchar(50) DEFAULT NULL,
  `SURAT` varchar(50) DEFAULT NULL,
  `LOKASI_KIRIM_SURAT` text DEFAULT NULL,
  `STATUS_SURAT` varchar(50) DEFAULT NULL,
  `KEGIATAN` text DEFAULT NULL,
  `STATUS` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tabel_absensi`
--

INSERT INTO `tabel_absensi` (`ID`, `KD_PST`, `TGL`, `CHECK_IN`, `LOKASI_CHECK_IN`, `CHECK_OUT`, `LOKASI_CHECK_OUT`, `KETERANGAN`, `KEHADIRAN`, `SURAT`, `LOKASI_KIRIM_SURAT`, `STATUS_SURAT`, `KEGIATAN`, `STATUS`) VALUES
(84, 'MH000001', '2023-05-03', '07:00:00', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', '15:30:00', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', NULL, 'tepat waktu', NULL, NULL, '-', 'membaca', 'hadir'),
(86, 'MH000001', '2023-05-05', NULL, NULL, NULL, NULL, 'sakit', NULL, '2031730017.jpg', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', 'approve', NULL, 'izin'),
(112, 'MH000001', '2023-05-11', '12:35:37', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', '16:43:04', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', NULL, 'terlambat', NULL, NULL, '-', 'membaca', 'hadir'),
(128, 'MH000001', '2023-05-02', '11:16:21', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', '13:14:13', 'Jln. Teratai, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', NULL, 'terlambat', NULL, NULL, '-', 'mengerjakan tugas akhir', 'hadir'),
(155, 'MH000001', '2023-04-24', '09:48:43', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', '16:49:59', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', NULL, 'terlambat', NULL, NULL, '-', 'membaca', 'hadir'),
(178, 'MH000001', '2023-05-04', NULL, NULL, NULL, NULL, 'bus hatuh', NULL, 'dc20230705221600.jpg', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', 'waiting', NULL, 'izin'),
(179, 'MH000001', '2023-05-09', NULL, NULL, NULL, NULL, 'contoh', NULL, 'AK00000120230905111923.jpg', 'Jln. Lingkar Maskumambang, Ds. Sukorame, Kec. Mojoroto, Kab. i, Prov. Jawa Timur, 64119', 'approve', NULL, 'izin'),
(189, 'MH000007', '2023-08-04', NULL, NULL, NULL, NULL, 'rolling', NULL, 'dc20231505082039.jpg', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', 'waiting', NULL, 'izin'),
(190, 'MH000132', '2023-08-04', NULL, NULL, NULL, NULL, 'catatan', NULL, 'dc20231505082334.jpg', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', 'waiting', NULL, 'izin'),
(191, 'MH000049', '2023-08-04', NULL, NULL, NULL, NULL, 'cantik', NULL, 'AK00012720231505082444.jpg', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', 'waiting', NULL, 'izin'),
(192, 'MH000141', '2023-08-04', NULL, NULL, NULL, NULL, 'tugas akhir', NULL, 'dc20231505082620.jpg', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', 'approve', NULL, 'izin'),
(193, 'MH000006', '2023-08-04', '08:30:15', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', '15:50:20', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', NULL, 'terlambat', NULL, NULL, '-', 'mengaji', 'hadir'),
(194, 'MH000044', '2023-07-11', '08:31:26', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', '15:50:20', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', NULL, 'terlambat', NULL, NULL, '-', 'mengaji', 'hadir'),
(231, 'MH000005', '2023-08-04', NULL, NULL, NULL, NULL, 'vhjj', NULL, 'AK00000820230806164012.jpg', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', 'approve', NULL, 'izin'),
(235, 'MH000005', '2023-07-12', NULL, NULL, NULL, NULL, 'gbhvbnj', NULL, 'AK00000820230906065635.jpg', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', 'approve', NULL, 'izin'),
(246, 'MH000001', '2023-08-04', '19:16:47', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', '19:17:35', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', NULL, 'tepat waktu', NULL, NULL, '-', 'melanjutkan proyek', 'hadir'),
(248, 'MH000001', '2023-07-10', NULL, NULL, NULL, NULL, 'sebelumta', NULL, 'AK00000120231007201100.jpg', 'Jln. Nusa Indah, Ds. Sumberejo, Kec. Ngasem, Kab. Kediri, Prov. Jawa Timur, 64182', 'approve', NULL, 'izin'),
(253, 'MH000001', '2023-07-12', '09:50:29', 'Jln. Lingkar Maskumambang, Ds. Sukorame, Kec. Mojoroto, Kab. i, Prov. Jawa Timur, 64119', '09:52:02', 'Jln. Lingkar Maskumambang, Ds. Sukorame, Kec. Mojoroto, Kab. i, Prov. Jawa Timur, 64119', NULL, 'terlambat', NULL, NULL, '-', 'melanjutkan proyek', 'hadir');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_akun`
--

CREATE TABLE `tabel_akun` (
  `KD_AKUN` varchar(50) NOT NULL,
  `EMAIL` text DEFAULT NULL,
  `PASSWORD` varchar(200) DEFAULT NULL,
  `LEVEL` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tabel_akun`
--

INSERT INTO `tabel_akun` (`KD_AKUN`, `EMAIL`, `PASSWORD`, `LEVEL`) VALUES
('AK000001', 'achamadchoirul@gmail.com', 'ZGdPSnR6YzFLSDJxOUpJSUpUMDRhQT09', 'peserta'),
('AK000002', 'achamadsholeh@gmail.com', 'WGpvNXc2UW9nVEVJUldIejF5dU9WUT09', 'karyawan'),
('AK000003', 'achamad@gmail.com', 'bzVhcFN5dDgvdlpqZ1dGQ21yNmdHQT09', 'admin'),
('AK000004', 'dani@gmail.com', 'QWNmSlQzL2lGTmFHZllNc0liWTZaZz09', 'peserta'),
('AK000005', 'bima@gmail.com', 'NytrZ3dlcURMSkNuNnVKMi9RVVFHUT09', 'peserta'),
('AK000006', 'anisa@gmail.com', 'UTRUVnVjbUZnYWh4bExkOEVmc1lCZz09', 'karyawan'),
('AK000007', 'a@gmail.com', 'bElsaE5FelVvc1RXNHJvMG5YTndFQT09', 'peserta'),
('AK000008', 'alifta@gmail.com', 'OTl4QnlpSm1nYlpvYnNnOGJlVFhLZz09', 'peserta'),
('AK000009', 'ibnui@gmail.com', 'V2dCS0N5ci8xSEtoYjdSSUhRSUJPQT09', 'karyawan'),
('AK000010', 'wikai@gmail.com', 'Wis0RE1iQnpyMXUySHc4eHNXRnUwZz09', 'karyawan'),
('AK000011', 'suwan@gmail.com', 'S0YySk9TaVM3R3AwdnIzd3pTOHlqZz09', 'karyawan'),
('AK000012', 'farma@gmail.com', 'SFJkRUsyd3BlRHQzK2FudUErbnRvUT09', 'karyawan'),
('AK000013', 'djoko@gmail.com', 'V05ITDladzRqNXZNMzlnTG4vYUJjQT09', 'karyawan'),
('AK000014', 'yulia@gmail.com', 'b1ZwR1lJT3RFd2VmMDlxb0x1czlaQT09', 'karyawan'),
('AK000015', 'sutar@gmail.com', 'VDVRVnBsY1A3dGJ1aUtuWllKTHVYZz09', 'karyawan'),
('AK000016', 'nurdi@gmail.com', 'U2M5a1ZnZloySDc3d3NTVWpSam9VZz09', 'karyawan'),
('AK000017', 'arles@gmail.com', 'RHBpd1Aya3lGYnlMMGVmOTFpWDFCUT09', 'karyawan'),
('AK000018', 'welly@gmail.com', 'MmZlYzkvZk5pYmRLY21wb1E1cDRrQT09', 'karyawan'),
('AK000019', 'nunin@gmail.com', 'ZkswQmtwdnFUSUtnbHVHVmoyVHRrUT09', 'karyawan'),
('AK000020', 'achma@gmail.com', 'aG4vaDNTQ29NVGgrbHN1RVBvdXNCUT09', 'karyawan'),
('AK000021', 'munim@gmail.com', 'cndBUFJDWU1GQ3FkWW0vSWI0ci9wQT09', 'karyawan'),
('AK000022', 'setia@gmail.com', 'MlBkODhJWGxjeDdwVk1IMUR6OFVvdz09', 'karyawan'),
('AK000023', 'kustya@gmail.com', 'dDNvZjhyNFdUZ0J1bzAxdlh4UnRuUT09', 'karyawan'),
('AK000024', 'endah@gmail.com', 'ZzhJTWREUWJRcndseU1hSEVSaDkxZz09', 'karyawan'),
('AK000025', 'yusup@gmail.com', 'bzVXVnZPaXdaWU1Rb1lza2p5N3FwZz09', 'karyawan'),
('AK000026', 'pitas@gmail.com', 'QVdhTkVOaWVMZlZkek83dVBSaFhhdz09', 'karyawan'),
('AK000027', 'karti@gmail.com', 'MHlXbXU2ZDdsdnMydHM2WCthOUZoZz09', 'admin'),
('AK000028', 'harti@gmail.com', 'UFd4R1JHSFNtWHEyOFNMZUs3ZTludz09', 'karyawan'),
('AK000029', 'hariy@gmail.com', 'NnQzRG85dEJmL1h6YmZ1dUlYY3BCQT09', 'karyawan'),
('AK000030', 'yudha@gmail.com', 'cTFqTWZUdG05ZzJ5K0cyc0JjOEkyZz09', 'karyawan'),
('AK000031', 'indah@gmail.com', 'QXMyaFNmbW55dHBWajYvYUlkNXArQT09', 'karyawan'),
('AK000032', 'sukma@gmail.com', 'ZTFVM0VZMFA1QmNMY1NZRllYMG9sUT09', 'karyawan'),
('AK000033', 'zuhad@gmail.com', 'MllrK2VVMWVXMmc4dTNoWCtaaFE3dz09', 'karyawan'),
('AK000034', 'hendra@gmail.com', 'ajRRMnEyQkMxOFVURStBd2lFME54QT09', 'karyawan'),
('AK000035', 'aniqo@gmail.com', 'OXE0MXl2aytqVGJ3MWRQcFZieW9Ndz09', 'karyawan'),
('AK000036', 'winar@gmail.com', 'MVpCQjNQdisxMkhrQ01sNWJBdktLQT09', 'karyawan'),
('AK000037', 'sumad@gmail.com', 'a0ZCeDM1MDVXS01Ccm1qNlFIMS9wZz09', 'karyawan'),
('AK000038', 'rahma@gmail.com', 'UTVSZFJOSG5kWTd5M1liTWkrU1o5UT09', 'karyawan'),
('AK000039', 'andik@gmail.com', 'bDFNd0gwcnlDU1RRbW52L3NBdnVqQT09', 'admin'),
('AK000040', 'logik@gmail.com', 'VWx6Rjg0dldQOVdKekxNbTJ0L0RNZz09', 'admin'),
('AK000041', 'suyoko@gmail.com', 'UGU5M1RVRDBpL0VTRkE3bGtEcWRDUT09', 'karyawan'),
('AK000042', 'novia@gmail.com', 'dmJYQlNzaGNTNjdTQ1pjeEVBLzlrQT09', 'karyawan'),
('AK000043', 'bagos@gmail.com', 'Q0xEbWliVGRDVjdwYzgxMU13eTFNUT09', 'karyawan'),
('AK000044', 'feriy@gmail.com', 'QkNWSDFFdVV6SVc0djZFSVQvaXRhQT09', 'karyawan'),
('AK000045', 'viant@gmail.com', 'NUVCSXlrRFY5dy92T2g2N000MnozZz09', 'karyawan'),
('AK000046', 'faisa@gmail.com', 'MVVWak12cFpVZ0ZRSHZqR0lRWWt2Zz09', 'karyawan'),
('AK000047', 'gatot@gmail.com', 'ZHRZSk0ydGx0MlZEN1JRNzBmMDhzdz09', 'karyawan'),
('AK000048', 'delia@gmail.com', 'WTY0QTFvTFNhVGJXM2JUVXNXdkN2Zz09', 'karyawan'),
('AK000049', 'santo@gmail.com', 'RmNXUXBDVkx0ZTFQbkcyNkNFd3F0dz09', 'karyawan'),
('AK000050', 'septu@gmail.com', 'QkNLWHNaZGZtNG5HMTRDOGdDTDM1QT09', 'karyawan'),
('AK000051', 'didit@@gmail.com', 'AKFNWkFoYjhPeVBUWXoxUzhNOGt3dz09', 'karyawan'),
('AK000052', 'rikho@gmail.com', 'L1YxOExvY2xaQWM0dWYraDRjQU1Jdz09', 'karyawan'),
('AK000053', 'febri@gmail.com', 'MGRVUDVCNU1maGVHTzI0a3hIcGRmQT09', 'karyawan'),
('AK000054', 'sudjo@gmail.com', 'TzMrT1NDLzRudmdwSGxPekRrS1FKUT09', 'karyawan'),
('AK000055', 'sukar@gmail.com', 'WHJJaFIzdGwrdndzanB3TUwrMStGQT09', 'karyawan'),
('AK000056', 'brian@gmail.com', 'T2FscWtGZyswMVppd3h1Q3l2ZnYvQT09', 'karyawan'),
('AK000057', 'paska@gmail.com', 'SERsYXd6bWI1aFgrSUtsZE1sd1lGUT09', 'karyawan'),
('AK000058', 'heraw@gmail.com', 'YlpTNVIxMUM0SWNvdHBvaXdIb083dz09', 'karyawan'),
('AK000059', 'chula@gmail.com', 'RlBZOVhJK0Z4dk00b0JKbHR5cy9yZz09', 'karyawan'),
('AK000060', 'wisnu@gmail.com', 'RGdlOEVnTVcvdjVXZ3RBenJob29xZz09', 'karyawan'),
('AK000061', 'danan@gmail.com', 'V2pOY295eVhWcnJFWFhmTnk2azBkdz09', 'karyawan'),
('AK000062', 'safit@gmail.com', 'azBvYzBZU1dadllYT1hDYVpwK1hCUT09', 'karyawan'),
('AK000063', 'puput@gmail.com', 'cDRvc2h1cFlzbTlZWEtPcjBVTkRlUT09', 'karyawan'),
('AK000064', 'muham@gmail.com', 'ZkJNeGYrWE51dUJiMXdpZ1Y0QkwwZz09', 'karyawan'),
('AK000065', 'moham@gmail.com', 'WG9uTWNzOHpoaTNERUJEbTUweVluUT09', 'karyawan'),
('AK000066', 'jerem@gmail.com', 'bTZKT2pkZ2JUaDcwYitiUVRCQzZwZz09', 'karyawan'),
('AK000067', 'setiy@gmail.com', 'TjIyNXZIU0o4R3JrU3Y3VWw4N2xndz09', 'karyawan'),
('AK000068', 'indri@gmail.com', 'Q3BzQndpanc0NjBCLzFpbDZ1M3hhdz09', 'karyawan'),
('AK000069', 'david@gmail.com', 'ZlBIS1FhWTNyYUFac0h1ZjVDZmRLdz09', 'karyawan'),
('AK000070', 'bagas@gmail.com', 'UnUwT2JsbFYvU0pvdTlCQTNsbVFVUT09', 'karyawan'),
('AK000071', 'aadhan@gmail.com', 'UUtyQ2dSMnFmK09DS3phdFpnSFhGUT09', 'karyawan'),
('AK000072', 'saiul@gmail.com', 'enJ3Wk13b25wSUdRMW5EZWw0SFgxdz09', 'karyawan'),
('AK000073', 'merdi@gmail.com', 'YkNENE5VK1REN2hqenJ2RlVDL1Vodz09', 'karyawan'),
('AK000074', 'hajar@gmail.com', 'K2F4WitnRWtCNVdUNFM1USt6Q1dRUT09', 'karyawan'),
('AK000075', 'meyrin@gmail.com', 'OWFxY0hSbUo0Y2V6aFJIZ3lUc0xoUT09', 'karyawan'),
('AK000076', 'cicil@gmail.com', 'S0hiR09UL1ptSnJWWHlFQXQ5TzZNdz09', 'karyawan'),
('AK000077', 'supri@gmail.com', 'aytJeXZTcEEzQkRubjZwRjJLeTJTdz09', 'karyawan'),
('AK000078', 'muham@gmail.com', 'c0xwVkxRNDVnZnFaRUsyaU5oUXJLUT09', 'karyawan'),
('AK000079', 'ardhi@gmail.com', 'bE8wRStCb0VWMEljNG91cE8rZC84Zz09', 'karyawan'),
('AK000080', 'muhyi@gmail.com', 'cGoraGN2eVFrSmV6QVJwQ1ZHU3dZUT09', 'karyawan'),
('AK000081', 'erzha@gmail.com', 'MSt1QzZLVVNZb1I3Sk1UaWJuS0Z0dz09', 'karyawan'),
('AK000082', 'febri@gmail.com', 'YlVBOEc5bnQwOXIvY2M2MTBVTTVMdz09', 'karyawan'),
('AK000083', 'rizal@gmail.com', 'RGl6ZXJ3aW15QnNYMU9yR09IRllxUT09', 'karyawan'),
('AK000084', 'budia@gmail.com', 'Z2grUlQxZzU1WmVKbndpS2lsVEVCdz09', 'peserta'),
('AK000085', 'anisa@gmail.com', 'Yk8zWkFsM3Vwci94NlMwTm1IaTVKQT09', 'peserta'),
('AK000086', 'dwise@gmail.com', 'WFVSRXpYTUQ5S1g2S1JJamZUS0IxUT09', 'peserta'),
('AK000087', 'arifr@gmail.com', 'cFdEbWRMSVZhbUdmSmtzMGtRTE80QT09', 'peserta'),
('AK000088', 'niaku@gmail.com', 'bXhRaGVOSFhBa2M0MEgzaTQwQjduQT09', 'peserta'),
('AK000089', 'denip@gmail.com', 'VWxaa3NncmlCbmZ4RDdEQ2FQL3diZz09', 'peserta'),
('AK000090', 'risma@gmail.com', 'bkNHeHVnYnRKNlNXQzAxZmwrNVhrQT09', 'peserta'),
('AK000091', 'jokos@gmail.com', 'MERmVzB1dkdScGgvV2t4aE5RUVdCZz09', 'peserta'),
('AK000092', 'sitin@gmail.com', 'RTAwNDFJS1dqU1FKSVVaU2F6bGx0Zz09', 'peserta'),
('AK000093', 'imams@gmail.com', 'S3c2SGF3VEtPeDgySS9ROFNIckZrdz09', 'peserta'),
('AK000094', 'yulia@gmail.com', 'NHQrV2llV0dIRGY4S0FGcDlHSG5kUT09', 'peserta'),
('AK000095', 'arifr@gmail.com', 'Y0lkc1RWSTlzTkxhZEFNbXk0RGZuZz09', 'peserta'),
('AK000096', 'sitia@gmail.com', 'MXdxaFZsemgrajI5ckJ6WXQzZkIvQT09', 'peserta'),
('AK000097', 'bagus@gmail.com', 'UzdScmtXUTZITURlVlI1NnpmQ25qQT09', 'peserta'),
('AK000098', 'novit@gmail.com', 'TVZDY3B5ckhVZGJrcjJGTHFUZmZYZz09', 'peserta'),
('AK000099', 'dedita@gmail.com', 'SWxWUWgvOG9iQTM4TXVWSHBaek5HQT09', 'peserta'),
('AK000100', 'nurfa@gmail.com', 'MkxDOG00WVg4VlhTWkxIQmdkUU50dz09', 'peserta'),
('AK000101', 'fadli@gmail.com', 'YXVzeEdrRVh3NUNIVGhEc2tnN1RoZz09', 'peserta'),
('AK000102', 'mardi@gmail.com', 'L09XSURNSGNnWnNZZkJrWXpWWTlFUT09', 'peserta'),
('AK000103', 'busdi@gmail.com', 'V2Fja0VVcG43dEhPcmtaT0Vxa3VlZz09', 'peserta'),
('AK000104', 'rudis@gmail.com', 'Zjc3QVVQYjVKZi9IWGI1OUZpYWVGQT09', 'peserta'),
('AK000105', 'putri@gmail.com', 'V2RzeUlZVE9ub2JWeHFzMCtTSS9VQT09', 'peserta'),
('AK000106', 'dedip@gmail.com', 'andXRHJlWVlaM0R3MlFXNkV3bUtGUT09', 'peserta'),
('AK000107', 'amina@gmail.com', 'MEtyQ2NvdDhmcjdyVXpzMUJKYjZwQT09', 'peserta'),
('AK000108', 'riyan@gmail.com', 'bmFPT0cwYkJoS25SbTJQR2pKM2hFdz09', 'peserta'),
('AK000109', 'ekasa@gmail.com', 'M0puSU5JK0t4U1FBVlIxV1htTFhvQT09', 'peserta'),
('AK000110', 'nurais@gmail.com', 'MnN4TE1JTjV2MTUvN1JLU0Y3dDhmUT09', 'peserta'),
('AK000111', 'nisas@gmail.com', 'eTNyUnVIeWlMc2M5bEUvcFFzSkNZUT09', 'peserta'),
('AK000112', 'rifai@gmail.com', 'MmVXbmVOUGxFamxWSWRWVHdMWHZSdz09', 'peserta'),
('AK000113', 'puspi@gmail.com', 'aWhzT25Hb0hjRWNiQ1ZvZUZtQ2I0UT09', 'peserta'),
('AK000114', 'fadil@gmail.com', 'T0VGTS9hUFc2Ym5rQmxwY1N4eXRKUT09', 'peserta'),
('AK000115', 'anali@gmail.com', 'YnBJanZndTIrRGxwK29ETFVjTDBtQT09', 'peserta'),
('AK000116', 'madza@gmail.com', 'c0VzWUFVUk01R0RQU3NjRDhnNXZydz09', 'peserta'),
('AK000117', 'fitri@gmail.com', 'THNtRFV0cEJoTDViazkrS3A3OWdJQT09', 'peserta'),
('AK000118', 'hiday@gmail.com', 'OUhrZkUrM0w5MEFoZm9lSTBjaXp5dz09', 'peserta'),
('AK000119', 'mukmi@gmail.com', 'Ky8rZFJFdzFURFZFTXQ2WjF6ZHVIZz09', 'peserta'),
('AK000120', 'nisad@gmail.com', 'Nk1LaDUzR2JLQkJ1MTgzVnFEemZQdz09', 'peserta'),
('AK000121', 'ridwa@gmail.com', 'Z1YxSUg2R3JTZDdXRzgxMzRmeVJwUT09', 'peserta'),
('AK000122', 'mifta@gmail.com', 'N1BGTkZpUHN3QmcxdkNYM1I5RHFCZz09', 'peserta'),
('AK000123', 'bagus@gmail.com', 'dFppTUMvdUNSTlgvdHVxVDhkTTFoZz09', 'peserta'),
('AK000124', 'qbksefihp@gmail.com', 'dzZPU3dzYys5bUJpRFBTckJ2OW54QT09', 'peserta'),
('AK000125', 'zptcqyvxo@gmail.com', 'SFhHRjF2bE9kTlRmcE1tZlozQzNmQT09', 'peserta'),
('AK000126', 'fdxrvhajt@gmail.com', 'MmFtUjNEcXFlNllPZXhVT08wZ1BzQT09', 'peserta'),
('AK000127', 'kdlfqorze@gmail.com', 'WndxRUZOdHlEUEJJQkhJT2lySnMzUT09', 'peserta'),
('AK000128', 'oirzjwcvd@gmail.com', 'K3pmWUJhdTNtS0JhMXpNbTdPWUJhZz09', 'peserta'),
('AK000129', 'guxqmjtao@gmail.com', 'NGl5RWNDNnFzWmhuYkZBOWIvajhHQT09', 'peserta'),
('AK000130', 'pwbtknryi@gmail.com', 'ZkNOV0kzZmFscjJHNmcrOHJmdEd1Zz09', 'peserta'),
('AK000131', 'hxycakwjb@gmail.com', 'cGhEMTUvaDIxcXNDMU9JRWdVNVZyQT09', 'peserta'),
('AK000132', 'ueclnfdmx@gmail.com', 'AKpudFU1SFNuQmFjYUhua0NBdmN6Zz09', 'peserta'),
('AK000133', 'avcyslxik@gmail.com', 'SWM0MDZwcE0veVVQeElOelczK29uUT09', 'peserta'),
('AK000134', 'rqzwkjtno@gmail.com', 'UlZwdjNMOEsrN3dDMWc0SElxTTZWdz09', 'peserta'),
('AK000135', 'vbsugxmqh@gmail.com', 'ZDBpQUF6MkZxbGFrWmo4VlkySGF6Zz09', 'peserta'),
('AK000136', 'ifjyqhrwx@gmail.com', 'S3FZK01aRmVNODlGM3FHNEVmZGdkZz09', 'peserta'),
('AK000137', 'ytfhksjmi@gmail.com', 'THBIbUxhNDZmTElhWXVVdGNwN1U3Zz09', 'peserta'),
('AK000138', 'bjxozsver@gmail.com', 'VGJDRFBDbjA3ZmRObjB5RWRYaDhWQT09', 'peserta'),
('AK000139', 'cmkuyqfwg@gmail.com', 'MGZ6ZnptVWhpVGxsOS9BeUkvTWt3UT09', 'peserta'),
('AK000140', 'nrcukatpj@gmail.com', 'RU93cS9KN3krM0dBMlFxNXdNbnNldz09', 'peserta'),
('AK000141', 'xtlbmnzfy@gmail.com', 'V3ZrTzhxN3FKY1hjYWFqN1h0U1JUdz09', 'peserta'),
('AK000142', 'zwbcryqxg@gmail.com', 'c0JuY2JtWnBZdDl4NnZiQTE4QW5VZz09', 'peserta'),
('AK000143', 'jbxhrslzc@gmail.com', 'Q1M4RDFXbVR0RGtydWpTUFN6VFVtQT09', 'peserta'),
('AK000144', 'tyxugfzis@gmail.com', 'dTNSR1c1dnpZRWxFdk9ySHUvNVM4QT09', 'peserta'),
('AK000145', 'nkqrjebsg@gmail.com', 'Z0J3TC84M0ROTVRNa1VjM0xwamlHUT09', 'peserta'),
('AK000146', 'pcbyvhnkt@gmail.com', 'WXpMWFBFL05aL3A1UXZPZlFCVmZJdz09', 'peserta'),
('AK000147', 'ivujyslwo@gmail.com', 'TmJQM21zTHZad1JRMUh2dlFONTRYdz09', 'peserta'),
('AK000148', 'kdyjhxuma@gmail.com', 'TGpraUFadEFvWHExa3lpQ1g4Vjc1dz09', 'peserta'),
('AK000149', 'qsrxcmnjv@gmail.com', 'RUVJdzFScjJ0R1ludkZSbFlEYU5HZz09', 'peserta'),
('AK000150', 'zhyomqevt@gmail.com', 'OWxZZ0tOY2sybFFiMXRaSlFsSWtBZz09', 'peserta'),
('AK000151', 'uqtrdxboi@gmail.com', 'RmxLWnNjZEtHaDBPV2xhY0dSNW5sUT09', 'peserta'),
('AK000152', 'wmrfkujhg@gmail.com', 'ZXA4b3hBN2lnT3gzMjZCU09YZDluUT09', 'peserta'),
('AK000153', 'lsayqxjkw@gmail.com', 'b3o0Q2xlV0VubnJBdGZqWDlibkg5Zz09', 'peserta'),
('AK000154', 'tknacjxse@gmail.com', 'djB5Z3lBOEVERi9DNzJXZWc1aWQ1Zz09', 'peserta'),
('AK000155', 'ezfakjvbw@gmail.com', 'MmYwV25ldnphZWJLVG5kczJkbU5mQT09', 'peserta'),
('AK000156', 'jblwtqczx@gmail.com', 'OU1HTlB4SzBiTzh2QnB2NWdOZzFSQT09', 'peserta'),
('AK000157', 'orqbmjatn@gmail.com', 'S1BGeTFqL2xnMlltaVpxc1JHak5lUT09', 'peserta'),
('AK000158', 'pgsqybuva@gmail.com', 'bmI1azJGeXg0NmFaK09EdVc3a0xXdz09', 'peserta'),
('AK000159', 'vczjkbhls@gmail.com', 'VDBtSTJKL3IyK2tDVzc3em1RSVlIQT09', 'peserta'),
('AK000160', 'ybgfeqwnu@gmail.com', 'QzVQSVJRK3VRZkl1eVdqZGFFY05qQT09', 'peserta'),
('AK000161', 'iohjnulds@gmail.com', 'SnRsME96cklaR0pPc0VxZFZIT2I1Zz09', 'peserta'),
('AK000162', 'dlfhgrzme@gmail.com', 'N3kvYWJmWFpHYjd2WWtkQTlLK1pqZz09', 'peserta'),
('AK000163', 'mnzcsikfy@gmail.com', 'SkJVTHNSc0NjUDNYMHFmTEtNNkFFQT09', 'peserta'),
('AK000164', 'qbmzopkci@gmail.com', 'TEhNUEN2a1BrSUlXVDlrc2F1QUphdz09', 'peserta'),
('AK000165', 'tjnzurpdx@gmail.com', 'VnR1MlRxaEdmZVI0ZHZ5V0dxaGorUT09', 'peserta'),
('AK000166', 'ewykvjrhl@gmail.com', 'YUFCUk9kTzFlV3Y5Wm9oQkFSakVVdz09', 'peserta'),
('AK000167', 'hxmbgwikp@gmail.com', 'c2wzSzdWNmtIOExNTXI1L2w3dTJkQT09', 'peserta'),
('AK000168', 'fnwypjzvu@gmail.com', 'L0ZJZ3lFTDRYa0xBVkpEaDgyQ3NtUT09', 'peserta'),
('AK000169', 'yhvkfgmsi@gmail.com', 'TEljdWJpb1BQdFcxdVBGSGNIL3AxQT09', 'peserta'),
('AK000170', 'kdyrnbjup@gmail.com', 'VkNSRG9kQjNFdm5wK0EvdHErb3NwUT09', 'peserta'),
('AK000171', 'lqbzmwjti@gmail.com', 'VnB0R2k4Q2oyVUlRTjV5a0tJTW00UT09', 'peserta'),
('AK000172', 'spcbfuqty@gmail.com', 'c21ZcHF4L0R0UVdvL1NNQ1NUcm5udz09', 'peserta'),
('AK000173', 'xmskayvjr@gmail.com', 'Wkg4bXZiVDhkN2w2TFlySG9SZTVWUT09', 'peserta'),
('AK000174', 'bwutcdlym@gmail.com', 'dit2aU1YSDRPaVNaREs3ZHJCWXlIdz09', 'peserta'),
('AK000175', 'ivhtgjkal@gmail.com', 'L091TnpGc1NzK0NrSU0rbURQUm1RUT09', 'peserta'),
('AK000176', 'jckibpemn@gmail.com', 'T2FsOGZpKytUWm5kbUFocFJTejZJQT09', 'peserta'),
('AK000177', 'nbyvfutrx@gmail.com', 'Rlg5K3Z3MEVIYkUyS1FlSGQvTVVkQT09', 'peserta'),
('AK000178', 'qfxlhtnog@gmail.com', 'ZWIxUTkrY3p2WFZuR1UxOUxObzFRdz09', 'peserta'),
('AK000179', 'rpvmxuyiw@gmail.com', 'UzNsN1o1MEEydnh4UkVBY0FxdFEydz09', 'peserta'),
('AK000180', 'tyfnbucml@gmail.com', 'Y0NMcEw0M3dkWU9mNWptbVVuUUI0QT09', 'peserta'),
('AK000181', 'uasvhlkny@gmail.com', 'L3dVbjdQSStNREFXL08xeTFlWnUrQT09', 'peserta'),
('AK000182', 'zwrjqdixt@gmail.com', 'U0hjS0ZKei9SRi9IQzdjWTkxcndvUT09', 'peserta'),
('AK000183', 'cgwkvysja@gmail.com', 'bzIva2sycnMzNlNLNlBEdnBXcFZMQT09', 'peserta'),
('AK000184', 'efjhwnmzg@gmail.com', 'Rld4M0ZMRVBxdDQzQ0RHKzlSSHJ6QT09', 'peserta'),
('AK000185', 'gxudjtsap@gmail.com', 'M0FJSWVZbVhKcEoreFVUMHQ3SlYwUT09', 'peserta'),
('AK000186', 'isfpnybtw@gmail.com', 'TmNyei9GYlFnK3B2SFlMMWc1SEplQT09', 'peserta'),
('AK000187', 'krujgacol@gmail.com', 'N21pZk5BYWJIYUVBYkFTWVNhME5sdz09', 'peserta'),
('AK000188', 'mvlxwpdqy@gmail.com', 'TnFKYzZGaEkrWFdVbERvcDZmNk5wZz09', 'peserta'),
('AK000189', 'nykoxcpwr@gmail.com', 'SFNRY1l5by9wTHlSQk00TnovWCtVQT09', 'peserta'),
('AK000190', 'obufgzrsl@gmail.com', 'S3ZzdmplU1Y2S0Nud3ZtaGRrZ01rUT09', 'peserta'),
('AK000191', 'pjmdtieqk@gmail.com', 'c3oxQ0hUamFCakNxejhnUGxOWWtqQT09', 'peserta'),
('AK000192', 'qynwvrsth@gmail.com', 'R1JlcGVkY0dNOEltTEtNNkMvcXpmZz09', 'peserta'),
('AK000193', 'rdgfmklpu@gmail.com', 'a2JJTzlCdURMUHZoWW1zd3JMbEt2QT09', 'peserta'),
('AK000194', 'sxhtvozbi@gmail.com', 'OTJueXZRdm1Ebi9hbHlaeGtIclVjZz09', 'peserta'),
('AK000195', 'soccerfan1234@gmail.com', 'R3h3TmhvdnM5bG1LVWFRMnJ2Y2VaZz09', 'peserta'),
('AK000196', 'musiclover5678@gmail.com', 'RmJSOE1DVFduNFR4bVF0NEJTT0hDQT09', 'peserta'),
('AK000197', 'naturelover7890@gmail.com', 'cVZ4RDBtcml1bHhKcHNBa2tPZG96dz09', 'peserta'),
('AK000198', 'adventureseeker4567@gmail.com', 'bEI3WWRqTFBDbzZCSklHZEoxeTE4Zz09', 'peserta'),
('AK000199', 'techgeek222@gmail.com', 'd0J2bWN1VlhZaGpaZE96VFhQc2FQZz09', 'peserta'),
('AK000200', 'bookworm6789@gmail.com', 'YnFGS0JCU2JGNXdwN0liR1FSSlNmZz09', 'peserta'),
('AK000201', 'foodie12345@gmail.com', 'RDBqdFdnaHRIQzVaaXNIa1pnZlVhdz09', 'peserta'),
('AK000202', 'fitnessjunkie321@gmail.com', 'QVV1bFFEUmNZUGdLbC9XdWVrM2pSUT09', 'peserta'),
('AK000203', 'moviebuff5678@gmail.com', 'dndkY0ZNczZpRFVybHZkNTFmWW1WQT09', 'peserta'),
('AK000204', 'gamer4567@gmail.com', 'aUVQTElWWVFZOGVPZFZRVVo5QmYyQT09', 'peserta'),
('AK000205', 'beachlover1234@gmail.com', 'ZVNaZUgrZVAyWHlYaTBqQ3FUQzdxZz09', 'peserta'),
('AK000206', 'animallover7890@gmail.com', 'UFhndnBoTHVyMmhWRGkxcW5Ma0xTdz09', 'peserta'),
('AK000207', 'traveler2222@gmail.com', 'Q0wwNTQ1QnM4V3h6N3B1MzdCb1hhZz09', 'peserta'),
('AK000208', 'sportsfan1234@gmail.com', 'THhId2VBRE12ZWZiVmNSN2ZaaVB4QT09', 'peserta'),
('AK000209', 'artenthusiast5678@gmail.com', 'N0JSREdQSnltY3U0Y2YxTVdTUlZudz09', 'peserta'),
('AK000210', 'fashionista7890@gmail.com', 'cnlQa2h2blVVc0RKSXhFV0FFd3BZUT09', 'peserta'),
('AK000211', 'healthnut4567@gmail.com', 'K2Q2QWQveXA4d1Z1aFQxUWxsZkVxZz09', 'peserta'),
('AK000212', 'photographer2222@gmail.com', 'MGRLVm82a2I3bjg3Vmk2bWVaN2dFQT09', 'peserta'),
('AK000213', 'yogalover12345@gmail.com', 'WGEyMU81UjdCWXdaOWhCODRDa21Wdz09', 'peserta'),
('AK000214', 'carenthusiast5678@gmail.com', 'dU9TYUlITk9GazRmZ3ZwdWhFYytSUT09', 'peserta'),
('AK000215', 'gardener7890@gmail.com', 'eStDdmhOcVd6NjJwenFCZ1oxNUIyZz09', 'peserta'),
('AK000216', 'musicmaker4567@gmail.com', 'TXRrZ1dJaVJyYmVIUFF0emtIaGREdz09', 'peserta'),
('AK000217', 'doglover2222@gmail.com', 'cDdiRTZmNEpiVFEvVGNhYTFSZjVBQT09', 'peserta'),
('AK000218', 'icedcoffee12345@gmail.com', 'bjFucUJWdnowR3ZXOFpQdE1sV3Zzdz09', 'peserta'),
('AK000219', 'cyclist5678@gmail.com', 'SDNuK05OS0JQemsrRE5LUk1XTlJhQT09', 'peserta'),
('AK000220', 'dafadas@gmail.com', 'bVUwUmdLM2FnZ0JTd2hZQTdkcVJ3QT09', 'peserta'),
('AK000221', 'asasas@gmail.com', 'UjBCQjRWRDBBalZGZURuZHhQSkNBdz09', 'karyawan'),
('AK000222', 'jajaja@gmail.com', 'c3NyaDBuN0doN2NCMllNOGZZemZ6QT09', 'peserta'),
('AK000223', 'lalala@gmail.com', 'QnpWTElQUWU1NTNjSVptSGZzM3JZQT09', 'peserta'),
('AK000224', 'qqqqqqqq@gmail.com', 'dGlFZUN1TEMzMXBWdUF1ZWNveFd6UT09', 'peserta'),
('AK000225', 'vvvvvvvvvvv@gmail.com', 'bFgxcnFzakdKeEpFS0d1RlhXWWtSZz09', 'peserta');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_asal`
--

CREATE TABLE `tabel_asal` (
  `KD_ASAL` varchar(50) NOT NULL,
  `NAMA_ASAL` varchar(200) DEFAULT NULL,
  `KATEGORI_ASAL` varchar(50) DEFAULT NULL,
  `ALAMAT_ASAL` varchar(200) DEFAULT NULL,
  `TELP_ASAL` varchar(50) DEFAULT NULL,
  `FAX_ASAL` varchar(50) DEFAULT NULL,
  `WEBSITE_ASAL` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tabel_asal`
--

INSERT INTO `tabel_asal` (`KD_ASAL`, `NAMA_ASAL`, `KATEGORI_ASAL`, `ALAMAT_ASAL`, `TELP_ASAL`, `FAX_ASAL`, `WEBSITE_ASAL`) VALUES
('KM000001', 'Politeknik Negeri Malang', 'perguruan tinggi', 'Jl. Soekarno Hatta No. 9 Malang. Kota : Kota Malang - Provinsi Jawa Timur - Indonesia.', '0341-404424, 404425', '0341-404420', 'https://www.polinema.ac.id'),
('KM000002', 'universitas negeri surabaya', 'perguruan tinggi', 'Jl. Lidah Wetan, Surabaya (60213)', '(031) - 51169397', '(031) - 51169397', 'https://www.unesa.ac.id/'),
('KM000003', 'SMK Negeri 1 Kediri', 'smk', 'Jl. Veteran No.9, Mojoroto, Kec. Mojoroto, Kota Kediri, Jawa Timur 64114', '(0354) 772271', '(0354) 773276', 'https://smkn1kediri.sch.id/'),
('KM000004', 'SMK Negeri 1 Ngasem', 'smk', 'Joho, Sumberejo, Kec. Ngasem, Kabupaten Kediri, Jawa Timur 64183', '0354 547762', '354545573', 'https://smkn1ngasem-kediri.sch.id/'),
('KM000005', 'SMK Negeri 1 Plosoklaten', 'smk', 'Jalan Pare-Wates Km. 7 Desa Sumberagung Kecamatan Plosoklaten 64175 Kabupaten Kediri', '0354 - 392619', '354392619', 'http://smkn1plosoklaten.sch.id/site/'),
('KM000006', 'SMK Negeri 1 Semen', 'smk', 'Desa Titik eks SDN Semen 2, Titik, Kec. Semen, Kabupaten Kediri, Jawa Timur 64161', '(0354) 3782013', '(0542)4567980', 'http://smkn1semen.sch.id/'),
('KM000007', 'SMKS PGRI 1 Kediri', 'smk', 'Jl. Himalaya No.06, Sukorame, Bandar Lor, Kec. Mojoroto, Kota Kediri, Jawa Timur 64114', '(0354) 771130', '771130', 'https://smkpgri1kediri.sch.id/'),
('KM000008', 'SMKS PGRI 2 Kediri', 'smk', 'Jl. KH. Abd Karim No.5, Bandar Lor, Kec. Mojoroto, Kabupaten Kediri, Jawa Timur 64117', '(0354) 771661', '354771661', 'https://smkpgri2kdr.sch.id/'),
('KM000009', 'SMKS Al Huda Kediri', 'smk', 'Jl. Masjid Al Huda No.196, Ngadirejo, Kec. Kota, Kota Kediri, Jawa Timur 64122', '(0354) 699544', '0354-699544', 'https://smkalhudakdr.sch.id/'),
('KM000010', 'SMK Negeri 3 Kediri', 'smk', 'Jl. Hasanudin No.10, Dandangan, Kec. Kota, Kota Kediri, Jawa Timur 64121', '(0354) 682261', '(0354) 682-261.', 'https://smkn3kediri.sch.id/'),
('KM000011', 'Universitas Nusantara Kediri', 'perguruan tinggi', 'Jl. Ahmad Dahlan No.76, Mojoroto, Kec. Mojoroto, Kota Kediri, Jawa Timur 64112', '0812-5917-1000', '(0354) 771576', 'https://unpkediri.ac.id/'),
('KM000012', 'Universitas Kahuripan Kediri', 'perguruan tinggi', 'Jl. Pb. Sudirman No.25, Plongko, Pare, Kec. Pare, Kabupaten Kediri, Jawa Timur 64212', '0812-1720-1788', '0354-391977', 'https://www.kahuripan.ac.id/'),
('KM000013', 'Universitas Kadiri', 'perguruan tinggi', 'Pojok, Kec. Mojoroto, Kabupaten Kediri, Jawa Timur 64115', '(0354) 771649', '(0354) 773032', 'https://unik-kediri.ac.id/');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_dtl_tim_peserta`
--

CREATE TABLE `tabel_dtl_tim_peserta` (
  `DTL_ID` int(11) NOT NULL,
  `KD_TIM` varchar(50) DEFAULT NULL,
  `KD_PST` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tabel_dtl_tim_peserta`
--

INSERT INTO `tabel_dtl_tim_peserta` (`DTL_ID`, `KD_TIM`, `KD_PST`) VALUES
(119, 'T000003', 'MH000004'),
(124, 'T000004', 'MH000137'),
(125, 'T000004', 'MH000140'),
(126, 'T000004', 'MH000133'),
(127, 'T000004', 'MH000138'),
(128, 'T000005', 'MH000139'),
(129, 'T000005', 'MH000136'),
(130, 'T000006', 'MH000134'),
(131, 'T000006', 'MH000131'),
(132, 'T000007', 'MH000135'),
(133, 'T000007', 'MH000130'),
(134, 'T000007', 'MH000129'),
(135, 'T000007', 'MH000045'),
(140, 'T000010', 'MH000053'),
(141, 'T000010', 'MH000046'),
(142, 'T000010', 'MH000047'),
(143, 'T000010', 'MH000054'),
(144, 'T000011', 'MH000051'),
(145, 'T000011', 'MH000050'),
(146, 'T000011', 'MH000048'),
(153, 'T000014', 'MH000062'),
(154, 'T000014', 'MH000061'),
(155, 'T000014', 'MH000058'),
(156, 'T000015', 'MH000009'),
(157, 'T000015', 'MH000063'),
(158, 'T000015', 'MH000065'),
(159, 'T000016', 'MH000059'),
(160, 'T000016', 'MH000010'),
(161, 'T000017', 'MH000057'),
(162, 'T000017', 'MH000056'),
(163, 'T000018', 'MH000064'),
(164, 'T000018', 'MH000060'),
(165, 'T000019', 'MH000071'),
(166, 'T000019', 'MH000068'),
(167, 'T000020', 'MH000011'),
(168, 'T000020', 'MH000019'),
(169, 'T000020', 'MH000018'),
(170, 'T000021', 'MH000017'),
(171, 'T000021', 'MH000016'),
(172, 'T000021', 'MH000012'),
(173, 'T000022', 'MH000013'),
(174, 'T000022', 'MH000014'),
(175, 'T000022', 'MH000015'),
(176, 'T000023', 'MH000066'),
(177, 'T000023', 'MH000067'),
(178, 'T000023', 'MH000069'),
(179, 'T000024', 'MH000070'),
(180, 'T000024', 'MH000072'),
(181, 'T000025', 'MH000077'),
(182, 'T000025', 'MH000080'),
(183, 'T000026', 'MH000020'),
(184, 'T000026', 'MH000073'),
(185, 'T000026', 'MH000075'),
(186, 'T000027', 'MH000076'),
(187, 'T000027', 'MH000079'),
(188, 'T000028', 'MH000074'),
(189, 'T000028', 'MH000078'),
(190, 'T000029', 'MH000082'),
(191, 'T000030', 'MH000021'),
(192, 'T000030', 'MH000022'),
(193, 'T000030', 'MH000084'),
(194, 'T000031', 'MH000081'),
(195, 'T000031', 'MH000083'),
(196, 'T000032', 'MH000088'),
(197, 'T000032', 'MH000089'),
(198, 'T000033', 'MH000023'),
(199, 'T000033', 'MH000024'),
(200, 'T000034', 'MH000025'),
(201, 'T000034', 'MH000087'),
(202, 'T000035', 'MH000085'),
(203, 'T000035', 'MH000086'),
(204, 'T000035', 'MH000090'),
(205, 'T000036', 'MH000096'),
(206, 'T000037', 'MH000094'),
(207, 'T000037', 'MH000092'),
(208, 'T000038', 'MH000029'),
(209, 'T000038', 'MH000091'),
(210, 'T000038', 'MH000028'),
(211, 'T000039', 'MH000093'),
(212, 'T000039', 'MH000027'),
(213, 'T000039', 'MH000095'),
(214, 'T000040', 'MH000026'),
(215, 'T000041', 'MH000098'),
(216, 'T000042', 'MH000099'),
(217, 'T000043', 'MH000030'),
(218, 'T000043', 'MH000031'),
(219, 'T000043', 'MH000032'),
(220, 'T000044', 'MH000033'),
(221, 'T000044', 'MH000100'),
(222, 'T000044', 'MH000101'),
(223, 'T000045', 'MH000097'),
(224, 'T000046', 'MH000109'),
(225, 'T000046', 'MH000108'),
(226, 'T000047', 'MH000106'),
(227, 'T000047', 'MH000112'),
(228, 'T000047', 'MH000110'),
(229, 'T000048', 'MH000102'),
(230, 'T000048', 'MH000104'),
(231, 'T000049', 'MH000034'),
(232, 'T000049', 'MH000041'),
(233, 'T000049', 'MH000040'),
(234, 'T000049', 'MH000113'),
(235, 'T000050', 'MH000107'),
(236, 'T000050', 'MH000105'),
(237, 'T000050', 'MH000103'),
(238, 'T000050', 'MH000111'),
(239, 'T000051', 'MH000114'),
(240, 'T000051', 'MH000118'),
(241, 'T000052', 'MH000119'),
(242, 'T000053', 'MH000035'),
(243, 'T000053', 'MH000117'),
(244, 'T000053', 'MH000116'),
(245, 'T000054', 'MH000036'),
(246, 'T000054', 'MH000121'),
(247, 'T000055', 'MH000115'),
(248, 'T000055', 'MH000120'),
(249, 'T000056', 'MH000123'),
(250, 'T000056', 'MH000126'),
(251, 'T000057', 'MH000127'),
(252, 'T000057', 'MH000122'),
(253, 'T000058', 'MH000125'),
(254, 'T000058', 'MH000037'),
(255, 'T000058', 'MH000042'),
(256, 'T000058', 'MH000039'),
(257, 'T000058', 'MH000038'),
(258, 'T000059', 'MH000124'),
(259, 'T000059', 'MH000128'),
(283, 'T000060', 'MH000143'),
(288, 'T000012', 'MH000007'),
(289, 'T000012', 'MH000006'),
(290, 'T000012', 'MH000008'),
(291, 'T000013', 'MH000049'),
(292, 'T000013', 'MH000052'),
(293, 'T000013', 'MH000055'),
(294, 'T000008', 'MH000044'),
(295, 'T000008', 'MH000043'),
(298, 'T000009', 'MH000132'),
(299, 'T000009', 'MH000141'),
(300, 'T000001', 'MH000001'),
(301, 'T000001', 'MH000003'),
(302, 'T000001', 'MH000002'),
(304, 'T000002', 'MH000005'),
(306, 'T000061', 'MH000144');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_jabatan`
--

CREATE TABLE `tabel_jabatan` (
  `KD_JBTN` varchar(50) NOT NULL,
  `NAMA_JBTN` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tabel_jabatan`
--

INSERT INTO `tabel_jabatan` (`KD_JBTN`, `NAMA_JBTN`) VALUES
('JB0001', 'Plt. Kepala Dinas'),
('JB0002', 'Kepala Bidang Pelayanan Informasi dan Komunikasi'),
('JB0003', 'Kepala Bidang Infrastruktur'),
('JB0004', 'Kepala Bidang E-Government'),
('JB0005', 'Kepala Sub Bagian Umum dan Kepegawaian'),
('JB0006', 'Kepala Sub Bagian Penyusunan Program'),
('JB0007', 'Kepala Seksi Kehumasan'),
('JB0008', 'Kepala Seksi Pelayanan Komunikasi Publik'),
('JB0009', 'Kepala Seksi Penyelenggaraan dan Pemantauan Informasi Publik'),
('JB0010', 'Kepala Seksi Analisa dan Penyebaran Informasi Publik'),
('JB0011', 'Kepala Seksi Pengelolaan dan Pelayanan Informasi Publik'),
('JB0012', 'Kepala Seksi Tata Kelola Layanan E-Government dan Sistem Informasi'),
('JB0013', 'Kasi Pengembangan Aplikasi Sistem Informasi Manajemen'),
('JB0014', 'Kepala Seksi Pengembangan Sumber Daya Manusia Teknologi Informasi dan Komunikasi'),
('JB0015', 'Pengadministrasi Keuangan'),
('JB0016', 'Penyusun Bahan Informasi'),
('JB0017', 'Operator Sandi Telekomunikasi'),
('JB0018', 'Pengadministrasi Perencanaan Dan Program'),
('JB0019', 'Pengolahan Data'),
('JB0020', 'Pengelola Dokumentasi'),
('JB0021', 'Penyusun Berita dan Pendapat Umum'),
('JB0022', 'Pengelola Teknologi Informasi'),
('JB0023', 'Pengelola Kepegawaian'),
('JB0024', 'Pengadministrasi Sarana dan Prasarana'),
('JB0025', 'Bendahara Sistem Informasi'),
('JB0026', 'Analisis Sistem Informasi'),
('JB0027', 'Pranata Humas'),
('JB0028', 'Pengelola Keamanan Sistem Informasi'),
('JB0029', 'Pengemudi'),
('JB0030', 'Tenaga Kontrak');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_karyawan`
--

CREATE TABLE `tabel_karyawan` (
  `KD_KAWAN` varchar(50) NOT NULL,
  `KD_AKUN` varchar(50) DEFAULT NULL,
  `KD_JBTN` varchar(50) DEFAULT NULL,
  `NIP_KAWAN` varchar(50) DEFAULT NULL,
  `NAMA_KAWAN` varchar(100) DEFAULT NULL,
  `JK_KAWAN` varchar(20) DEFAULT NULL,
  `AGAMA_KAWAN` varchar(50) DEFAULT NULL,
  `ALAMAT_KAWAN` varchar(200) DEFAULT NULL,
  `NOHP_KAWAN` varchar(20) DEFAULT NULL,
  `FOTO_KAWAN` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tabel_karyawan`
--

INSERT INTO `tabel_karyawan` (`KD_KAWAN`, `KD_AKUN`, `KD_JBTN`, `NIP_KAWAN`, `NAMA_KAWAN`, `JK_KAWAN`, `AGAMA_KAWAN`, `ALAMAT_KAWAN`, `NOHP_KAWAN`, `FOTO_KAWAN`) VALUES
('KY000001', 'AK000002', 'JB0001', NULL, 'Achmad Robil Kurniawan', 'Laki-laki', 'Islam', 'Indonesia', '085341463195', 'dc20230705214623.jpg'),
('KY000002', 'AK000003', 'JB0002', NULL, 'Achamad Shaputra dio', 'perempuan', 'islam', 'Indonesia', '087770018971', '1.jpg'),
('KY000003', 'AK000006', 'JB0002', NULL, 'Muhamad Yusuf', 'Laki-laki', 'Islam', 'Kota kediri', '082331623374', 'profil.png'),
('KY000004', 'AK000009', 'JB0001', '197210102005011014', 'Ibnu Imad, S.Sos', 'Laki-laki', 'Islam', 'Jl. Raya Kepung No. 15, Desa Ngampel, Kecamatan Gurah', '085694974953', 'profil.png'),
('KY000005', 'AK000010', 'JB0002', '196412311997031052', 'Wikainun, SE, M.Si.', 'Perempuan', 'Islam', 'Jl. Diponegoro No. 20, Desa Sidomulyo, Kecamatan Papar', '082188769679', 'profil.png'),
('KY000006', 'AK000011', 'JB0003', '197304152005011011', 'Suwanto, S.Kom.', 'Laki-laki', 'Islam', 'Jl. Kalimantan No. 8, Desa Tunggulsari, Kecamatan Mojo', '081281710958', 'profil.png'),
('KY000007', 'AK000012', 'JB0004', '196807181992031009', 'Farman, S.Sos. M.Si.', 'Laki-laki', 'Islam', 'Jl. Ahmad Yani No. 11, Desa Ngepoh, Kecamatan Pare', '082278931567', 'profil.png'),
('KY000008', 'AK000013', 'JB0005', '196403291992031005', 'Djoko Prasetyo, SH,MM', 'Laki-laki', 'Islam', 'Jl. Raya Karangsono No. 32, Desa Blimbing, Kecamatan Kandangan', '087721998656', 'profil.png'),
('KY000009', 'AK000014', 'JB0006', '197907182010012003', 'Yulia Puji Rahayu, SE.', 'Perempuan', 'Islam', 'Jl. Pahlawan No. 7, Desa Ngadiluhur, Kecamatan Ringinrejo', '085747520884', 'profil.png'),
('KY000010', 'AK000015', 'JB0007', '196812311990111005', 'Sutarja, SE.', 'Laki-laki', 'Islam', 'Jl. Panglima Sudirman No. 43, Desa Karangan, Kecamatan Puncu', '089668116937', 'profil.png'),
('KY000011', 'AK000016', 'JB0008', '198302262006042018', 'Efa Nurdiana, ST', 'Perempuan', 'Islam', 'Jl. Jendral Sudirman No. 14, Desa Jambu, Kecamatan Mojoagung', '088270918876', 'profil.png'),
('KY000012', 'AK000017', 'JB0009', '198001022010012014', 'Dian Arlesti Lukman, SH. MH.', 'Perempuan', 'Islam', 'Jl. Raya Solo No. 22, Desa Ngasem, Kecamatan Ngancar', '083840442502', 'profil.png'),
('KY000013', 'AK000018', 'JB0010', '196410281985031010', 'Drs. Welly Markosa', 'Laki-laki', 'Islam', 'Jl. Veteran No. 9, Desa Sumberejo, Kecamatan Kepung', '081245786730', 'profil.png'),
('KY000014', 'AK000019', 'JB0011', '197912102005012014', 'Nuning Susilowati, S.Sos, MA.', 'Perempuan', 'Islam', 'Jl. Pemuda No. 17, Desa Ngujung, Kecamatan Badas', '082192860448', 'profil.png'),
('KY000015', 'AK000020', 'JB0012', '197809102005011006', 'Achmad Sholeh Mustaqim, ST MT.', 'Perempuan', 'Islam', 'Jl. Merdeka No. 25, Desa Ngaglik, Kecamatan Ngadiluwih', '081363489750', 'profil.png'),
('KY000016', 'AK000021', 'JB0013', '198103082009011003', 'Ali Munim, S.Kom., M.Si.', 'Laki-laki', 'Islam', 'Jl. Gajah Mada No. 12, Desa Dukuh, Kecamatan Gampengrejo', '087838658884', 'profil.png'),
('KY000017', 'AK000022', 'JB0014', '197008131999011001', 'Agus Setiawan, SE.', 'Laki-laki', 'Budha', 'Jl. Pahlawan Revolusi No. 29, Desa Banaran, Kecamatan Purwoasri', '085374122463', 'profil.png'),
('KY000018', 'AK000023', 'JB0015', '196907111998032007', 'Yuli Kustyani, SE.', 'Perempuan', 'Islam', 'Jl. Raya Kertosono No. 6, Desa Ngrejo, Kecamatan Kertosono', '085200431322', 'profil.png'),
('KY000019', 'AK000024', 'JB0016', '197010031990032004', 'Rr. Endah Susilowati, S.Sos', 'Perempuan', 'Islam', 'Jl. Trunojoyo No. 18, Desa Wates, Kecamatan Ngancar', '082315934802', 'profil.png'),
('KY000020', 'AK000025', 'JB0017', '197711282003121005', 'Moch. Yusup, ST. MT', 'Laki-laki', 'Islam', 'Jl. Imam Bonjol No. 37, Desa Gampeng, Kecamatan Papar', '082253681195', 'profil.png'),
('KY000021', 'AK000026', 'JB0018', '197203101999032005', 'Pitasari, S.Sos.', 'Perempuan', 'Islam', 'Jl. Ahmad Dahlan No. 9, Desa Pagersari, Kecamatan Plemahan', '0895361806893', 'profil.png'),
('KY000022', 'AK000027', 'JB0019', '196604211994012001', 'Kartiyah, S.Sos.', 'Perempuan', 'Islam', 'Jl. Raya Tawangrejo No. 4, Desa Tawangrejo, Kecamatan Kras', '083120554460', 'profil.png'),
('KY000023', 'AK000028', 'JB0020', '196409271992102001', 'Hartini', 'Perempuan', 'Islam', 'Jl. Diponegoro No. 21, Desa Gunung Anyar, Kecamatan Wates', '081293227003', 'profil.png'),
('KY000024', 'AK000029', 'JB0015', '196404261985031011', 'Achmad Hariyanto', 'Laki-laki', 'Islam', 'Jl. Gajah Mada No. 8, Desa Jambearum, Kecamatan Banyakan', '0895628074371', 'profil.png'),
('KY000025', 'AK000030', 'JB0021', '198506202015021001', 'Yudha Yogi Prabawa, S.I.Kom.', 'Laki-laki', 'Islam', 'Jl. Raya Ngadirenggo No. 11, Desa Ngepung, Kecamatan Ngadirenggo', '085733273167', 'profil.png'),
('KY000026', 'AK000031', 'JB0021', '199108252015032002', 'Indah Angriani, S.Kom', 'Perempuan', 'Islam', 'Jl. Pemuda No. 10, Desa Wonosari, Kecamatan Gurah', '0895631596030', 'profil.png'),
('KY000027', 'AK000032', 'JB0022', '198307152010011040', 'Sukma Fadly Nurlana, A.Md.', 'Laki-laki', 'Islam', 'Jl. Raya Puncu No. 18, Desa Bendo, Kecamatan Puncu', '0895385285001', 'profil.png'),
('KY000028', 'AK000033', 'JB0021', '198705202010011004', 'M. Ali Zuhadi Mabrur, A.Md.', 'Laki-laki', 'Islam', 'Jl. Sisingamangaraja No. 14, Desa Kedawung, Kecamatan Tarokan', '087787463076', 'profil.png'),
('KY000029', 'AK000034', 'JB0022', '198806242011011003', 'Hendra Setiawan, A.Md.', 'Laki-laki', 'Islam', 'Jl. A. Yani No. 32, Desa Ngampel, Kecamatan Ngancar', '081287701070', 'profil.png'),
('KY000030', 'AK000035', 'JB0023', '198206112010012023', 'Aniqotul Lutfiyah, A.Md.', 'Perempuan', 'Islam', 'Jl. Merdeka No. 12, Desa Sumber Rejo, Kecamatan Ngadiluwih', '085712510251', 'profil.png'),
('KY000031', 'AK000036', 'JB0024', '197509192009011009', 'Aris Winarko', 'Laki-laki', 'Islam', 'Jl. Pahlawan No. 44, Desa Karanganyar, Kecamatan Kepung', '085261781320', 'profil.png'),
('KY000032', 'AK000037', 'JB0025', '198004272009011010', 'Sumadi', 'Laki-laki', 'Islam', 'Jl. Diponegoro No. 8A, Desa Pagerwojo, Kecamatan Puncu', '085156047067', 'profil.png'),
('KY000033', 'AK000038', 'JB0026', '199206102020121013', 'Rahmad Hidayat Hadi Subroto, S.Kom', 'Laki-laki', 'Islam', 'Jl. A. Yani No. 63, Desa Sumberbendo, Kecamatan Gampengrejo', '085772371299', 'profil.png'),
('KY000034', 'AK000039', 'JB0019', '198007222012121003', 'Andik yulianto', 'Laki-laki', 'Islam', 'Jl. Trunojoyo No. 24, Desa Sidomulyo, Kecamatan Badas', '085731156577', 'profil.png'),
('KY000035', 'AK000040', 'JB0019', '198211122010011004', 'Logika Prasasta Yoga', 'Laki-laki', 'Islam', 'Jl. Cokroaminoto No. 50, Desa Sumberagung, Kecamatan Mojo', '085802446005', 'profil.png'),
('KY000036', 'AK000041', 'JB0027', '196510251992031004', 'Joko Suyoko, BA', 'Laki-laki', 'Islam', 'Jl. Jendral Ahmad Yani No. 17, Desa Tulungrejo, Kecamatan Tarokan', '081995261607', 'profil.png'),
('KY000037', 'AK000042', 'JB0026', '199412092019031006', 'Ardy Novian Erwanda S.Kom', 'Laki-laki', 'Islam', 'Jl. Kartini No. 32, Desa Kertosari, Kecamatan Kandangan', '081298253566', 'profil.png'),
('KY000038', 'AK000043', 'JB0022', '199101072019031007', 'Bagos Anggara A.Md', 'Laki-laki', 'Islam', 'Jl. Pahlawan No. 19, Desa Sambirejo, Kecamatan Semen', '087826753532', 'profil.png'),
('KY000039', 'AK000044', 'JB0028', '198702152019031005', 'Arik Fefriyono A.Md', 'Laki-laki', 'Islam', 'Jl. Jawa No. 6, Desa Sumberjo, Kecamatan Ngasem', '089505217840', 'profil.png'),
('KY000040', 'AK000045', 'JB0015', '198508122014082004', 'Veni Vianti', 'Perempuan', 'Islam', 'Jl. Mawar No. 27, Desa Sukorejo, Kecamatan Wates', '081338293378', 'profil.png'),
('KY000041', 'AK000046', 'JB0022', '199704262020121006', 'Faisal Budi Aji, A.Md', 'Laki-laki', 'Islam', 'Jl. Sisingamangaraja No. 3, Desa Karangsono, Kecamatan Ngancar', '081230671405', 'profil.png'),
('KY000042', 'AK000047', 'JB0029', '197003292010011001', 'Gatot Siswoyo', 'Laki-laki', 'Islam', 'Jl. Basuki Rahmat No. 11, Desa Sidorejo, Kecamatan Puncu', '087809227150', 'profil.png'),
('KY000043', 'AK000048', 'JB0030', NULL, 'Bety Delia', 'Perempuan', 'Islam', 'Jl. Airlangga No. 7, Desa Sumberjo, Kecamatan Grogol', '081333550746', 'profil.png'),
('KY000044', 'AK000049', 'JB0030', NULL, 'Joko Santoso', 'Laki-laki', 'Islam', 'Jl. Raya Kediri-Malang No. 13, Desa Karangrejo, Kecamatan Wates', '081295563139', 'profil.png'),
('KY000045', 'AK000050', 'JB0030', NULL, 'Luke Septufuri', 'Perempuan', 'Islam', 'Jl. Pemuda No. 29, Desa Sidomukti, Kecamatan Ngasem', '089516460707', 'profil.png'),
('KY000046', 'AK000051', 'JB0030', NULL, 'Didit Kurniawan', 'Laki-laki', 'Islam', 'Jl. Brawijaya No. 28, Desa Kedungsari, Kecamatan Mojo', '081357639361', 'profil.png'),
('KY000047', 'AK000052', 'JB0030', NULL, 'Rikho G. Widyandoko', 'Laki-laki', 'Islam', 'Jl. Perintis Kemerdekaan No. 54, Desa Bulusari, Kecamatan Puncu', '085941451764', 'profil.png'),
('KY000048', 'AK000053', 'JB0030', NULL, 'Febri Dwi Mutiarasari', 'Laki-laki', 'Islam', 'Jl. Veteran No. 10, Desa Tumpakrejo, Kecamatan Gampengrejo', '083835377446', 'profil.png'),
('KY000049', 'AK000054', 'JB0030', NULL, 'Sudjono', 'Laki-laki', 'Islam', 'Jl. Pucang Adi No. 22, Desa Wonokoyo, Kecamatan Wlingi', '085798805385', 'profil.png'),
('KY000050', 'AK000055', 'JB0030', NULL, 'Sukarno', 'Laki-laki', 'Islam', 'Jl. Raya Kediri-Tulungagung No. 39, Desa Kandangan, Kecamatan Kandangan', '083811321404', 'profil.png'),
('KY000051', 'AK000056', 'JB0030', NULL, 'Brian Adi Tama Putra', 'Laki-laki', 'Islam', 'Jl. Imam Bonjol No. 26, Desa Wringin Anom, Kecamatan Ngadiluwih', '089626057371', 'profil.png'),
('KY000052', 'AK000057', 'JB0030', NULL, 'Paskan Adi Tama Putra', 'Laki-laki', 'Islam', 'Jl. Merdeka No. 16, Desa Sumbermanjing, Kecamatan Wates', '089674685554', 'profil.png'),
('KY000053', 'AK000058', 'JB0030', NULL, 'Dian Ayu Herawati', 'Perempuan', 'Islam', 'Jl. Gajah Mada No. 30, Desa Pagerwojo, Kecamatan Grogol', '088296302005', 'profil.png'),
('KY000054', 'AK000059', 'JB0030', NULL, 'Chula Chusnita', 'Perempuan', 'Islam', 'Jl. Dagen No. 9, Desa Kertosari, Kecamatan Gampengrejo', '083865644834', 'profil.png'),
('KY000055', 'AK000060', 'JB0030', NULL, 'Wisnu Setiawan', 'Laki-laki', 'Islam', 'Jl. Raya Solo No. 48, Desa Karang Tengah, Kecamatan Ngancar', '085886801756', 'profil.png'),
('KY000056', 'AK000061', 'JB0030', NULL, 'Danang Adikrisna', 'Laki-laki', 'Islam', 'Jl. Ahmad Yani No. 13, Desa Jati, Kecamatan Kepung', '087815917808', 'profil.png'),
('KY000057', 'AK000062', 'JB0030', NULL, 'One Ulfi Safitri', 'Perempuan', 'Islam', 'Jl. Raya Sumberharjo No. 45, Desa Sumberharjo, Kecamatan Plemahan', '083129239772', 'profil.png'),
('KY000058', 'AK000063', 'JB0030', NULL, 'Puput Ariyanti', 'Perempuan', 'Islam', 'Jl. Diponegoro No. 20, Desa Ploso, Kecamatan Gampengrejo', '088233667453', 'profil.png'),
('KY000059', 'AK000064', 'JB0030', NULL, 'Muhammad Fauzi Amrulloh', 'Laki-laki', 'Islam', 'Jl. Raya Kras No. 31, Desa Karangjati, Kecamatan Kandat', '08895824301', 'profil.png'),
('KY000060', 'AK000065', 'JB0030', NULL, 'Mohammad Yanuar Setya Wibowo', 'Laki-laki', 'Islam', 'Jl. Kartini No. 8, Desa Wonorejo, Kecamatan Mojo', '083198651041', 'profil.png'),
('KY000061', 'AK000066', 'JB0030', NULL, 'Jeremy Candra Pranata', 'Laki-laki', 'Islam', 'Jl. Raya Tamanan No. 50, Desa Tamanan, Kecamatan Ngadiluwih', '087779302469', 'profil.png'),
('KY000062', 'AK000067', 'JB0030', NULL, 'Doni Setiyo Budi Santoso', 'Laki-laki', 'Islam', 'Jl. Raya Gondanglegi No. 17, Desa Ngebruk, Kecamatan Gondang', '08156715273', 'profil.png'),
('KY000063', 'AK000068', 'JB0030', NULL, 'R. Rexy Indrianto Pratama', 'Laki-laki', 'Islam', 'Jl. MT Haryono No. 12, Desa Pagerwojo, Kecamatan Puncu', '088230258587', 'profil.png'),
('KY000064', 'AK000069', 'JB0030', NULL, 'David Chrisna Valentino', 'Laki-laki', 'Islam', 'Jl. Raya Pare No. 21, Desa Dadapan, Kecamatan Pare', '081210357184', 'profil.png'),
('KY000065', 'AK000070', 'JB0030', NULL, 'Bagas Prasetya Putra', 'Laki-laki', 'Islam', 'Jl. Raya Bandungrejo No. 6, Desa Bandungrejo, Kecamatan Pagu', '085789076904', 'profil.png'),
('KY000066', 'AK000071', 'JB0030', NULL, 'Aadhan Jorghi Kresnawan', 'Laki-laki', 'Budha', 'Jl. Raya Ngasem No. 43, Desa Sukorejo, Kecamatan Ngasem', '081373927998', 'profil.png'),
('KY000067', 'AK000072', 'JB0030', NULL, 'Saiul Ridwan', 'Laki-laki', 'Islam', 'Jl. Ahmad Dahlan No. 25, Desa Sumbersuko, Kecamatan Wates', '081281996033', 'profil.png'),
('KY000068', 'AK000073', 'JB0030', NULL, 'Evi Merdika Listiani', 'Perempuan', 'Islam', 'Jl. Raya Kandangan No. 37, Desa Kandangan, Kecamatan Badas', '085877333363', 'profil.png'),
('KY000069', 'AK000074', 'JB0030', NULL, 'Elly Hajar Mastrin', 'Perempuan', 'Islam', 'Jl. Raya Ngadirejo No. 12, Desa Ngadirejo, Kecamatan Puncu', '085761356851', 'profil.png'),
('KY000070', 'AK000075', 'JB0030', NULL, 'Meyrinda Tobing', 'Perempuan', 'Islam', 'Jl. Dr. Wahidin No. 7, Desa Jetis, Kecamatan Plemahan', '081380569783', 'profil.png'),
('KY000071', 'AK000076', 'JB0030', NULL, 'Oky Cicilia Damayanti', 'Perempuan', 'Islam', 'Jl. Raya Kertosono No. 50, Desa Mojopuro, Kecamatan Kertosono', '088214535126', 'profil.png'),
('KY000072', 'AK000077', 'JB0030', NULL, 'Supriyadi', 'Laki-laki', 'Islam', 'Jl. Letjen Sutoyo No. 35, Desa Purwodadi, Kecamatan Wlingi', '085952719471', 'profil.png'),
('KY000073', 'AK000078', 'JB0030', NULL, 'Muhammad Husnul Yaqin', 'Laki-laki', 'Islam', 'Jl. Raya Karanglo No. 14, Desa Karanglo, Kecamatan Kras', '082175852791', 'profil.png'),
('KY000074', 'AK000079', 'JB0030', NULL, 'Ardhian Ahmadi', 'Laki-laki', 'Islam', 'Jl. Gajah Mada No. 9, Desa Kertosari, Kecamatan Gondang', '085753965790', 'profil.png'),
('KY000075', 'AK000080', 'JB0030', NULL, 'Muhyi Irmawan', 'Laki-laki', 'Islam', 'Jl. Raya Rejotangan No. 6, Desa Sambikerep, Kecamatan Rejotangan', '088269551618', 'profil.png'),
('KY000076', 'AK000081', 'JB0030', NULL, 'Erzha Yuli Triapanta', 'Laki-laki', 'Islam', 'Jl. Yos Sudarso No. 27, Desa Ringinrejo, Kecamatan Plosoklaten', '089656881965', 'profil.png'),
('KY000077', 'AK000082', 'JB0030', NULL, 'Ardy Febri Sustrissyah', 'Laki-laki', 'Islam', 'Jl. Raya Kandat No. 33, Desa Karangnongko, Kecamatan Kandat', '085346859028', 'profil.png'),
('KY000078', 'AK000083', 'JB0030', NULL, 'Rizal Mustiko Adji', 'Laki-laki', 'Islam', 'Jl. Ahmad Yani No. 8, Desa Karanganyar, Kecamatan Kepung', '085329556664', 'profil.png');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_konfigurasi`
--

CREATE TABLE `tabel_konfigurasi` (
  `KD_KONF` varchar(5) NOT NULL,
  `NAMA_SISTEM` text DEFAULT NULL,
  `LOGO_SISTEM` text DEFAULT NULL,
  `SINGKATAN` text DEFAULT NULL,
  `VERSI` text DEFAULT NULL,
  `PRE_SEKAM_MULAI` time DEFAULT NULL,
  `PRE_SEKAM_SELESAI` time DEFAULT NULL,
  `PRE_SEKAM_OUT` time DEFAULT NULL,
  `PRE_JUM_MULAI` time DEFAULT NULL,
  `PRE_JUM_SELESAI` time DEFAULT NULL,
  `PRE_JUM_OUT` time DEFAULT NULL,
  `LATITUDE_KONF` text DEFAULT NULL,
  `LONGITUDE_KONF` text DEFAULT NULL,
  `RADIUS_KONF` text DEFAULT NULL,
  `JUDUL_RADIUS` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tabel_konfigurasi`
--

INSERT INTO `tabel_konfigurasi` (`KD_KONF`, `NAMA_SISTEM`, `LOGO_SISTEM`, `SINGKATAN`, `VERSI`, `PRE_SEKAM_MULAI`, `PRE_SEKAM_SELESAI`, `PRE_SEKAM_OUT`, `PRE_JUM_MULAI`, `PRE_JUM_SELESAI`, `PRE_JUM_OUT`, `LATITUDE_KONF`, `LONGITUDE_KONF`, `RADIUS_KONF`, `JUDUL_RADIUS`) VALUES
('1', 'Sistem Informasi Monitoring dan Presensi Peserta PKL', 'logoKominfo.png', 'SIMAPTA-PKL', '27.3', '06:00:00', '15:30:00', '16:00:00', '07:00:00', '18:00:00', '19:00:00', '-7.815196519524177', '112.03517150133848', '100f', 'dinas komunikasi dan informatika');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_libur_nasional`
--

CREATE TABLE `tabel_libur_nasional` (
  `ID_LBR` int(11) NOT NULL,
  `TANGGAL_LBR` date DEFAULT NULL,
  `KEGIATAN_LBR` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tabel_libur_nasional`
--

INSERT INTO `tabel_libur_nasional` (`ID_LBR`, `TANGGAL_LBR`, `KEGIATAN_LBR`) VALUES
(9, '2023-01-01', 'Tahun Baru Masehi'),
(10, '2023-01-22', 'Tahun Baru Imlek'),
(11, '2023-02-18', 'Isra Miraj'),
(12, '2023-03-22', 'Hari Raya Nyepi'),
(13, '2023-04-07', 'Wafat Isa Al-Masih'),
(14, '2023-04-22', 'Hari Raya Idul Fitri'),
(15, '2023-04-23', 'Hari Raya Idul Fitri'),
(16, '2023-05-01', 'Hari Buruh'),
(17, '2023-05-18', 'Kenaikan Isa Al-Masih'),
(18, '2023-06-01', 'Hari Lahir Pancasila'),
(19, '2023-06-04', 'Hari Raya Waisak'),
(20, '2023-06-29', 'Hari Raya Idul Adha'),
(21, '2023-07-19', 'Tahun Baru Islam 1445 Hijriyah'),
(22, '2023-08-17', 'HUT RI KE-78'),
(23, '2023-09-28', 'Mulid Nabi Muhammad SAW'),
(24, '2023-12-30', 'Hari Raya Natal');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_logpos`
--

CREATE TABLE `tabel_logpos` (
  `ID_LOG` int(11) NOT NULL,
  `TGL_LOG` date DEFAULT NULL,
  `KD_PST` varchar(50) DEFAULT NULL,
  `KETERANGAN_LOG` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tabel_logpos`
--

INSERT INTO `tabel_logpos` (`ID_LOG`, `TGL_LOG`, `KD_PST`, `KETERANGAN_LOG`) VALUES
(1, '2023-04-04', 'MH000001', 'Pada jam 11:00:00 peserta berada diluar radius DISKOMINFO.'),
(2, '2023-04-30', 'MH000001', 'Pada jam 07:04:01 peserta berada diluar radius DISKOMINFO.'),
(3, '2023-04-30', 'MH000001', 'Pada jam 07:04:28 peserta berada diluar radius DISKOMINFO.'),
(4, '2023-04-30', 'MH000001', 'Pada jam 07:04:51 peserta berada diluar radius DISKOMINFO.'),
(5, '2023-04-30', 'MH000001', 'Pada jam 07:41:39 peserta berada diluar radius DISKOMINFO.'),
(6, '2023-04-30', 'MH000001', 'Pada jam 07:42:00 peserta berada diluar radius DISKOMINFO.'),
(7, '2023-04-30', 'MH000001', 'Pada jam 07:43:06 peserta berada diluar radius DISKOMINFO.'),
(8, '2023-04-30', 'MH000001', 'Pada jam 12:43:52 peserta berada diluar radius DISKOMINFO.'),
(9, '2023-04-24', 'MH000001', 'Pada jam 08:53:14 peserta berada diluar radius DISKOMINFO.'),
(10, '2023-04-24', 'MH000001', 'Pada jam 08:53:43 peserta berada diluar radius DISKOMINFO.'),
(11, '2023-04-24', 'MH000001', 'Pada jam 08:54:12 peserta berada diluar radius DISKOMINFO.'),
(12, '2023-05-01', 'MH000001', 'Pada jam 09:08:47 peserta berada diluar radius DISKOMINFO.'),
(13, '2023-05-01', 'MH000001', 'Pada jam 12:46:08 peserta berada diluar radius DISKOMINFO.'),
(14, '2023-05-01', 'MH000001', 'Pada jam 12:46:08 peserta berada diluar radius DISKOMINFO.'),
(15, '2023-05-01', 'MH000001', 'Pada jam 13:27:03 peserta berada diluar radius DISKOMINFO.'),
(16, '2023-05-01', 'MH000001', 'Pada jam 13:32:21 peserta berada diluar radius DISKOMINFO.'),
(17, '2023-05-02', 'MH000001', 'Pada jam 11:08:04 peserta berada diluar radius DISKOMINFO.'),
(18, '2023-05-02', 'MH000001', 'Pada jam 11:08:04 peserta berada diluar radius DISKOMINFO.'),
(19, '2023-05-02', 'MH000001', 'Pada jam 14:53:41 peserta berada diluar radius DISKOMINFO.'),
(20, '2023-05-02', 'MH000003', 'Pada jam 14:54:38 peserta berada diluar radius DISKOMINFO.'),
(21, '2023-05-02', 'MH000001', 'Pada jam 14:56:07 peserta berada diluar radius DISKOMINFO.'),
(22, '2023-05-02', 'MH000004', 'Pada jam 14:57:45 peserta berada diluar radius DISKOMINFO.'),
(26, '2023-05-02', 'MH000001', 'Pada jam 15:03:09 peserta berada diluar radius DISKOMINFO.'),
(27, '2023-05-02', 'MH000001', 'Pada jam 15:14:05 peserta berada diluar radius DISKOMINFO.'),
(29, '2023-05-02', 'MH000001', 'Pada jam 13:45:49 peserta berada diluar radius DISKOMINFO.'),
(30, '2023-05-02', 'MH000003', 'Pada jam 13:50:22 peserta berada diluar radius DISKOMINFO.'),
(31, '2023-05-02', 'MH000001', 'Pada jam 13:50:46 peserta berada diluar radius DISKOMINFO.'),
(32, '2023-05-02', 'MH000001', 'Pada jam 14:05:21 peserta berada diluar radius DISKOMINFO.'),
(33, '2023-05-04', 'MH000001', 'Pada jam 09:52:42 peserta berada diluar radius DISKOMINFO.'),
(34, '2023-05-04', 'MH000001', 'Pada jam 10:02:12 peserta berada diluar radius DISKOMINFO.'),
(35, '2023-05-04', 'MH000001', 'Pada jam 10:02:49 peserta berada diluar radius DISKOMINFO.'),
(36, '2023-05-04', 'MH000001', 'Pada jam 10:07:45 peserta berada diluar radius DISKOMINFO.'),
(37, '2023-05-04', 'MH000001', 'Pada jam 10:08:58 peserta berada diluar radius DISKOMINFO.'),
(38, '2023-05-04', 'MH000001', 'Pada jam 10:15:40 peserta berada diluar radius DISKOMINFO.'),
(39, '2023-05-08', 'MH000001', 'Pada jam 07:52:59 peserta berada diluar radius DISKOMINFO.'),
(40, '2023-05-08', 'MH000001', 'Pada jam 07:52:59 peserta berada diluar radius DISKOMINFO.'),
(41, '2023-05-08', 'MH000001', 'Pada jam 07:52:59 peserta berada diluar radius DISKOMINFO.'),
(42, '2023-05-08', 'MH000001', 'Pada jam 07:53:21 peserta berada diluar radius DISKOMINFO.'),
(43, '2023-05-08', 'MH000001', 'Pada jam 07:54:16 peserta berada diluar radius DISKOMINFO.'),
(44, '2023-05-08', 'MH000001', 'Pada jam 07:54:41 peserta berada diluar radius DISKOMINFO.'),
(45, '2023-05-08', 'MH000001', 'Pada jam 07:55:55 peserta berada diluar radius DISKOMINFO.'),
(46, '2023-05-08', 'MH000001', 'Pada jam 07:56:41 peserta berada diluar radius DISKOMINFO.'),
(47, '2023-05-08', 'MH000001', 'Pada jam 07:57:05 peserta berada diluar radius DISKOMINFO.'),
(48, '2023-05-08', 'MH000001', 'Pada jam 07:58:11 peserta berada diluar radius DISKOMINFO.'),
(49, '2023-05-08', 'MH000001', 'Pada jam 08:02:46 peserta berada diluar radius DISKOMINFO.'),
(50, '2023-05-08', 'MH000001', 'Pada jam 08:07:59 peserta berada diluar radius DISKOMINFO.'),
(51, '2023-05-08', 'MH000001', 'Pada jam 08:10:59 peserta berada diluar radius DISKOMINFO.'),
(52, '2023-05-08', 'MH000001', 'Pada jam 08:11:17 peserta berada diluar radius DISKOMINFO.'),
(53, '2023-05-08', 'MH000001', 'Pada jam 08:11:36 peserta berada diluar radius DISKOMINFO.'),
(54, '2023-05-08', 'MH000001', 'Pada jam 08:12:12 peserta berada diluar radius DISKOMINFO.'),
(55, '2023-05-08', 'MH000001', 'Pada jam 08:13:25 peserta berada diluar radius DISKOMINFO.'),
(56, '2023-05-08', 'MH000001', 'Pada jam 08:13:47 peserta berada diluar radius DISKOMINFO.'),
(57, '2023-05-08', 'MH000001', 'Pada jam 08:24:42 peserta berada diluar radius DISKOMINFO.'),
(58, '2023-05-08', 'MH000001', 'Pada jam 08:25:18 peserta berada diluar radius DISKOMINFO.'),
(59, '2023-05-08', 'MH000001', 'Pada jam 08:27:23 peserta berada diluar radius DISKOMINFO.'),
(60, '2023-05-08', 'MH000001', 'Pada jam 08:28:37 peserta berada diluar radius DISKOMINFO.'),
(61, '2023-05-08', 'MH000001', 'Pada jam 08:29:32 peserta berada diluar radius DISKOMINFO.'),
(62, '2023-05-08', 'MH000001', 'Pada jam 08:32:03 peserta berada diluar radius DISKOMINFO.'),
(63, '2023-05-08', 'MH000001', 'Pada jam 08:33:51 peserta berada diluar radius DISKOMINFO.'),
(64, '2023-05-08', 'MH000001', 'Pada jam 08:36:21 peserta berada diluar radius DISKOMINFO.'),
(65, '2023-05-08', 'MH000001', 'Pada jam 08:38:34 peserta berada diluar radius DISKOMINFO.'),
(66, '2023-05-08', 'MH000001', 'Pada jam 08:41:25 peserta berada diluar radius DISKOMINFO.'),
(67, '2023-05-08', 'MH000001', 'Pada jam 08:42:36 peserta berada diluar radius DISKOMINFO.'),
(68, '2023-05-08', 'MH000001', 'Pada jam 08:43:05 peserta berada diluar radius DISKOMINFO.'),
(69, '2023-05-08', 'MH000001', 'Pada jam 08:44:19 peserta berada diluar radius DISKOMINFO.'),
(70, '2023-05-08', 'MH000001', 'Pada jam 08:45:23 peserta berada diluar radius DISKOMINFO.'),
(71, '2023-05-08', 'MH000001', 'Pada jam 08:47:15 peserta berada diluar radius DISKOMINFO.'),
(72, '2023-05-08', 'MH000001', 'Pada jam 08:47:40 peserta berada diluar radius DISKOMINFO.'),
(73, '2023-05-08', 'MH000001', 'Pada jam 08:48:20 peserta berada diluar radius DISKOMINFO.'),
(74, '2023-05-08', 'MH000001', 'Pada jam 08:48:47 peserta berada diluar radius DISKOMINFO.'),
(75, '2023-05-08', 'MH000001', 'Pada jam 08:50:58 peserta berada diluar radius DISKOMINFO.'),
(76, '2023-05-08', 'MH000001', 'Pada jam 08:51:15 peserta berada diluar radius DISKOMINFO.'),
(77, '2023-05-08', 'MH000001', 'Pada jam 08:51:16 peserta berada diluar radius DISKOMINFO.'),
(78, '2023-05-08', 'MH000001', 'Pada jam 08:51:36 peserta berada diluar radius DISKOMINFO.'),
(79, '2023-05-08', 'MH000001', 'Pada jam 08:51:52 peserta berada diluar radius DISKOMINFO.'),
(80, '2023-05-08', 'MH000001', 'Pada jam 08:52:08 peserta berada diluar radius DISKOMINFO.'),
(81, '2023-05-08', 'MH000001', 'Pada jam 08:53:57 peserta berada diluar radius DISKOMINFO.'),
(82, '2023-05-08', 'MH000001', 'Pada jam 08:55:48 peserta berada diluar radius DISKOMINFO.'),
(83, '2023-05-08', 'MH000001', 'Pada jam 09:02:25 peserta berada diluar radius DISKOMINFO.'),
(84, '2023-05-08', 'MH000001', 'Pada jam 09:04:30 peserta berada diluar radius DISKOMINFO.'),
(85, '2023-05-08', 'MH000001', 'Pada jam 09:05:09 peserta berada diluar radius DISKOMINFO.'),
(86, '2023-05-08', 'MH000001', 'Pada jam 09:09:36 peserta berada diluar radius DISKOMINFO.'),
(87, '2023-05-08', 'MH000001', 'Pada jam 09:09:51 peserta berada diluar radius DISKOMINFO.'),
(88, '2023-05-08', 'MH000001', 'Pada jam 09:23:56 peserta berada diluar radius DISKOMINFO.'),
(89, '2023-05-08', 'MH000001', 'Pada jam 09:24:13 peserta berada diluar radius DISKOMINFO.'),
(90, '2023-05-08', 'MH000001', 'Pada jam 09:24:41 peserta berada diluar radius DISKOMINFO.'),
(91, '2023-05-08', 'MH000001', 'Pada jam 09:49:18 peserta berada diluar radius DISKOMINFO.'),
(92, '2023-05-08', 'MH000001', 'Pada jam 09:57:38 peserta berada diluar radius DISKOMINFO.'),
(93, '2023-05-08', 'MH000001', 'Pada jam 09:57:38 peserta berada diluar radius DISKOMINFO.'),
(94, '2023-05-08', 'MH000001', 'Pada jam 09:58:56 peserta berada diluar radius DISKOMINFO.'),
(95, '2023-05-08', 'MH000001', 'Pada jam 10:15:21 peserta berada diluar radius DISKOMINFO.'),
(96, '2023-05-08', 'MH000001', 'Pada jam 10:16:35 peserta berada diluar radius DISKOMINFO.'),
(97, '2023-05-08', 'MH000001', 'Pada jam 10:20:51 peserta berada diluar radius DISKOMINFO.'),
(98, '2023-05-08', 'MH000001', 'Pada jam 10:21:17 peserta berada diluar radius DISKOMINFO.'),
(99, '2023-05-08', 'MH000001', 'Pada jam 10:21:34 peserta berada diluar radius DISKOMINFO.'),
(100, '2023-05-08', 'MH000001', 'Pada jam 10:48:34 peserta berada diluar radius DISKOMINFO.'),
(101, '2023-05-08', 'MH000001', 'Pada jam 10:49:02 peserta berada diluar radius DISKOMINFO.'),
(102, '2023-05-08', 'MH000001', 'Pada jam 14:49:18 peserta berada diluar radius DISKOMINFO.'),
(103, '2023-05-08', 'MH000001', 'Pada jam 14:49:18 peserta berada diluar radius DISKOMINFO.'),
(104, '2023-05-08', 'MH000001', 'Pada jam 14:49:43 peserta berada diluar radius DISKOMINFO.'),
(105, '2023-05-08', 'MH000001', 'Pada jam 14:50:16 peserta berada diluar radius DISKOMINFO.'),
(106, '2023-05-09', 'MH000001', 'Pada jam 10:54:37 peserta berada diluar radius DISKOMINFO.'),
(107, '2023-05-09', 'MH000001', 'Pada jam 10:55:14 peserta berada diluar radius DISKOMINFO.'),
(108, '2023-05-09', 'MH000001', 'Pada jam 11:10:08 peserta berada diluar radius DISKOMINFO.'),
(109, '2023-05-10', 'MH000001', 'Pada jam 08:03:46 peserta berada diluar radius DISKOMINFO.'),
(110, '2023-05-10', 'MH000001', 'Pada jam 08:15:20 peserta berada diluar radius DISKOMINFO.'),
(111, '2023-05-10', 'MH000001', 'Pada jam 08:30:00 peserta berada diluar radius DISKOMINFO.'),
(112, '2023-05-10', 'MH000001', 'Pada jam 08:35:18 peserta berada diluar radius DISKOMINFO.'),
(113, '2023-05-10', 'MH000001', 'Pada jam 08:43:15 peserta berada diluar radius DISKOMINFO.'),
(114, '2023-05-10', 'MH000001', 'Pada jam 08:57:40 peserta berada diluar radius DISKOMINFO.'),
(115, '2023-05-10', 'MH000005', 'Pada jam 07:06:02 peserta berada diluar radius DISKOMINFO.'),
(116, '2023-05-10', 'MH000132', 'Pada jam 07:07:02 peserta berada diluar radius DISKOMINFO.'),
(117, '2023-05-10', 'MH000049', 'Pada jam 09:08:07 peserta berada diluar radius DISKOMINFO.'),
(118, '2023-05-10', 'MH000044', 'Pada jam 07:14:49 peserta berada diluar radius DISKOMINFO.'),
(119, '2023-05-10', 'MH000044', 'Pada jam 07:16:21 peserta berada diluar radius DISKOMINFO.'),
(120, '2023-05-10', 'MH000005', 'Pada jam 07:16:50 peserta berada diluar radius DISKOMINFO.'),
(121, '2023-05-10', 'MH000005', 'Pada jam 07:19:41 peserta berada diluar radius DISKOMINFO.'),
(122, '2023-05-10', 'MH000005', 'Pada jam 07:22:25 peserta berada diluar radius DISKOMINFO.'),
(123, '2023-05-10', 'MH000005', 'Pada jam 07:24:01 peserta berada diluar radius DISKOMINFO.'),
(124, '2023-05-10', 'MH000008', 'Pada jam 07:26:53 peserta berada diluar radius DISKOMINFO.'),
(125, '2023-05-10', 'MH000007', 'Pada jam 07:29:49 peserta berada diluar radius DISKOMINFO.'),
(126, '2023-05-10', 'MH000006', 'Pada jam 07:30:45 peserta berada diluar radius DISKOMINFO.'),
(127, '2023-05-10', 'MH000001', 'Pada jam 07:38:19 peserta berada diluar radius DISKOMINFO.'),
(128, '2023-05-10', 'MH000001', 'Pada jam 14:44:51 peserta berada diluar radius DISKOMINFO.'),
(134, '2023-05-15', 'MH000001', 'Pada jam 08:26:25 peserta berada diluar radius DISKOMINFO.'),
(135, '2023-05-15', 'MH000005', 'Pada jam 11:11:09 peserta berada diluar radius DISKOMINFO.'),
(136, '2023-05-15', 'MH000005', 'Pada jam 11:14:16 peserta berada diluar radius DISKOMINFO.'),
(137, '2023-05-15', 'MH000005', 'Pada jam 11:16:36 peserta berada diluar radius DISKOMINFO.'),
(138, '2023-05-15', 'MH000001', 'Pada jam 08:16:49 peserta berada diluar radius DISKOMINFO.'),
(139, '2023-05-15', 'MH000007', 'Pada jam 08:20:01 peserta berada diluar radius DISKOMINFO.'),
(140, '2023-05-15', 'MH000132', 'Pada jam 08:23:01 peserta berada diluar radius DISKOMINFO.'),
(141, '2023-05-15', 'MH000049', 'Pada jam 08:24:36 peserta berada diluar radius DISKOMINFO.'),
(142, '2023-05-15', 'MH000141', 'Pada jam 08:26:05 peserta berada diluar radius DISKOMINFO.'),
(143, '2023-05-15', 'MH000141', 'Pada jam 08:27:03 peserta berada diluar radius DISKOMINFO.'),
(144, '2023-05-15', 'MH000141', 'Pada jam 08:27:04 peserta berada diluar radius DISKOMINFO.'),
(145, '2023-05-15', 'MH000006', 'Pada jam 08:29:15 peserta berada diluar radius DISKOMINFO.'),
(146, '2023-05-15', 'MH000006', 'Pada jam 08:30:09 peserta berada diluar radius DISKOMINFO.'),
(147, '2023-05-15', 'MH000044', 'Pada jam 08:31:15 peserta berada diluar radius DISKOMINFO.'),
(148, '2023-05-15', 'MH000005', 'Pada jam 08:32:04 peserta berada diluar radius DISKOMINFO.'),
(149, '2023-05-15', 'MH000001', 'Pada jam 09:38:31 peserta berada diluar radius DISKOMINFO.'),
(150, '2023-05-15', 'MH000001', 'Pada jam 10:30:07 peserta berada diluar radius DISKOMINFO.'),
(151, '2023-05-15', 'MH000001', 'Pada jam 10:43:22 peserta berada diluar radius DISKOMINFO.'),
(152, '2023-05-15', 'MH000001', 'Pada jam 11:19:09 peserta berada diluar radius DISKOMINFO.'),
(153, '2023-05-15', 'MH000001', 'Pada jam 11:20:44 peserta berada diluar radius DISKOMINFO.'),
(154, '2023-05-15', 'MH000001', 'Pada jam 11:22:41 peserta berada diluar radius DISKOMINFO.'),
(155, '2023-05-15', 'MH000001', 'Pada jam 11:25:35 peserta berada diluar radius DISKOMINFO.'),
(156, '2023-05-15', 'MH000001', 'Pada jam 11:28:43 peserta berada diluar radius DISKOMINFO.'),
(157, '2023-05-15', 'MH000001', 'Pada jam 11:30:17 peserta berada diluar radius DISKOMINFO.'),
(158, '2023-05-15', 'MH000001', 'Pada jam 11:37:18 peserta berada diluar radius DISKOMINFO.'),
(159, '2023-05-15', 'MH000001', 'Pada jam 11:41:50 peserta berada diluar radius DISKOMINFO.'),
(160, '2023-05-15', 'MH000001', 'Pada jam 11:43:22 peserta berada diluar radius DISKOMINFO.'),
(161, '2023-05-15', 'MH000001', 'Pada jam 11:44:10 peserta berada diluar radius DISKOMINFO.'),
(162, '2023-05-15', 'MH000001', 'Pada jam 11:45:44 peserta berada diluar radius DISKOMINFO.'),
(163, '2023-05-15', 'MH000001', 'Pada jam 11:47:07 peserta berada diluar radius DISKOMINFO.'),
(164, '2023-05-15', 'MH000001', 'Pada jam 11:49:00 peserta berada diluar radius DISKOMINFO.'),
(165, '2023-05-15', 'MH000005', 'Pada jam 11:53:12 peserta berada diluar radius DISKOMINFO.'),
(166, '2023-05-15', 'MH000001', 'Pada jam 07:21:23 peserta berada diluar radius DISKOMINFO.'),
(167, '2023-05-15', 'MH000001', 'Pada jam 07:32:28 peserta berada diluar radius DISKOMINFO.'),
(168, '2023-05-15', 'MH000001', 'Pada jam 07:33:47 peserta berada diluar radius DISKOMINFO.'),
(169, '2023-05-15', 'MH000001', 'Pada jam 07:53:03 peserta berada diluar radius DISKOMINFO.'),
(170, '2023-05-15', 'MH000001', 'Pada jam 07:54:48 peserta berada diluar radius DISKOMINFO.'),
(171, '2023-05-15', 'MH000001', 'Pada jam 07:58:51 peserta berada diluar radius DISKOMINFO.'),
(172, '2023-05-15', 'MH000001', 'Pada jam 08:04:17 peserta berada diluar radius DISKOMINFO.'),
(173, '2023-05-15', 'MH000001', 'Pada jam 08:06:06 peserta berada diluar radius DISKOMINFO.'),
(174, '2023-05-15', 'MH000001', 'Pada jam 08:08:58 peserta berada diluar radius DISKOMINFO.'),
(175, '2023-05-15', 'MH000001', 'Pada jam 08:23:12 peserta berada diluar radius DISKOMINFO.'),
(176, '2023-05-15', 'MH000001', 'Pada jam 08:24:09 peserta berada diluar radius DISKOMINFO.'),
(177, '2023-05-15', 'MH000001', 'Pada jam 08:25:11 peserta berada diluar radius DISKOMINFO.'),
(178, '2023-05-15', 'MH000001', 'Pada jam 08:26:28 peserta berada diluar radius DISKOMINFO.'),
(179, '2023-05-15', 'MH000001', 'Pada jam 08:29:25 peserta berada diluar radius DISKOMINFO.'),
(180, '2023-05-15', 'MH000001', 'Pada jam 08:30:36 peserta berada diluar radius DISKOMINFO.'),
(181, '2023-05-15', 'MH000001', 'Pada jam 08:31:13 peserta berada diluar radius DISKOMINFO.'),
(182, '2023-05-15', 'MH000001', 'Pada jam 08:36:45 peserta berada diluar radius DISKOMINFO.'),
(183, '2023-05-15', 'MH000001', 'Pada jam 08:38:29 peserta berada diluar radius DISKOMINFO.'),
(184, '2023-05-15', 'MH000001', 'Pada jam 08:39:01 peserta berada diluar radius DISKOMINFO.'),
(185, '2023-05-15', 'MH000001', 'Pada jam 08:44:29 peserta berada diluar radius DISKOMINFO.'),
(186, '2023-05-15', 'MH000005', 'Pada jam 08:44:52 peserta berada diluar radius DISKOMINFO.'),
(187, '2023-05-15', 'MH000005', 'Pada jam 08:45:43 peserta berada diluar radius DISKOMINFO.'),
(188, '2023-05-15', 'MH000005', 'Pada jam 08:48:26 peserta berada diluar radius DISKOMINFO.'),
(189, '2023-05-15', 'MH000005', 'Pada jam 08:55:20 peserta berada diluar radius DISKOMINFO.'),
(190, '2023-05-16', 'MH000007', 'Pada jam 09:08:04 peserta berada diluar radius DISKOMINFO.'),
(191, '2023-05-16', 'MH000001', 'Pada jam 09:20:21 peserta berada diluar radius DISKOMINFO.'),
(192, '2023-05-16', 'MH000007', 'Pada jam 09:27:15 peserta berada diluar radius DISKOMINFO.'),
(193, '2023-05-16', 'MH000001', 'Pada jam 09:29:26 peserta berada diluar radius DISKOMINFO.'),
(194, '2023-05-16', 'MH000001', 'Pada jam 09:30:45 peserta berada diluar radius DISKOMINFO.'),
(195, '2023-05-16', 'MH000001', 'Pada jam 09:44:19 peserta berada diluar radius DISKOMINFO.'),
(196, '2023-05-16', 'MH000007', 'Pada jam 09:51:05 peserta berada diluar radius DISKOMINFO.'),
(197, '2023-05-16', 'MH000007', 'Pada jam 09:56:01 peserta berada diluar radius DISKOMINFO.'),
(198, '2023-05-16', 'MH000007', 'Pada jam 10:13:19 peserta berada diluar radius DISKOMINFO.'),
(199, '2023-05-16', 'MH000007', 'Pada jam 10:15:12 peserta berada diluar radius DISKOMINFO.'),
(200, '2023-05-16', 'MH000007', 'Pada jam 10:23:05 peserta berada diluar radius DISKOMINFO.'),
(201, '2023-05-16', 'MH000001', 'Pada jam 10:59:37 peserta berada diluar radius DISKOMINFO.'),
(202, '2023-05-16', 'MH000007', 'Pada jam 11:03:53 peserta berada diluar radius DISKOMINFO.'),
(203, '2023-05-16', 'MH000001', 'Pada jam 13:00:49 peserta berada diluar radius DISKOMINFO.'),
(204, '2023-05-16', 'MH000007', 'Pada jam 13:04:30 peserta berada diluar radius DISKOMINFO.'),
(205, '2023-05-17', 'MH000005', 'Pada jam 08:43:19 peserta berada diluar radius DISKOMINFO.'),
(206, '2023-05-17', 'MH000005', 'Pada jam 08:43:20 peserta berada diluar radius DISKOMINFO.'),
(207, '2023-05-17', 'MH000005', 'Pada jam 10:03:17 peserta berada diluar radius DISKOMINFO.'),
(208, '2023-05-17', 'MH000005', 'Pada jam 10:07:31 peserta berada diluar radius DISKOMINFO.'),
(209, '2023-05-17', 'MH000005', 'Pada jam 10:08:50 peserta berada diluar radius DISKOMINFO.'),
(210, '2023-05-17', 'MH000005', 'Pada jam 10:12:36 peserta berada diluar radius DISKOMINFO.'),
(211, '2023-05-17', 'MH000005', 'Pada jam 10:14:33 peserta berada diluar radius DISKOMINFO.'),
(212, '2023-05-16', 'MH000001', 'Pada jam 09:29:43 peserta berada diluar radius DISKOMINFO.'),
(213, '2023-05-17', 'MH000001', 'Pada jam 09:34:21 peserta berada diluar radius DISKOMINFO.'),
(214, '2023-05-17', 'MH000001', 'Pada jam 09:37:48 peserta berada diluar radius DISKOMINFO.'),
(215, '2023-05-17', 'MH000001', 'Pada jam 08:29:45 peserta berada diluar radius DISKOMINFO.'),
(216, '2023-05-17', 'MH000005', 'Pada jam 08:31:42 peserta berada diluar radius DISKOMINFO.'),
(217, '2023-05-17', 'MH000005', 'Pada jam 08:33:45 peserta berada diluar radius DISKOMINFO.'),
(218, '2023-05-17', 'MH000005', 'Pada jam 08:35:57 peserta berada diluar radius DISKOMINFO.'),
(219, '2023-05-17', 'MH000005', 'Pada jam 08:36:26 peserta berada diluar radius DISKOMINFO.'),
(220, '2023-05-17', 'MH000005', 'Pada jam 08:42:50 peserta berada diluar radius DISKOMINFO.'),
(221, '2023-05-17', 'MH000005', 'Pada jam 08:44:18 peserta berada diluar radius DISKOMINFO.'),
(222, '2023-05-17', 'MH000005', 'Pada jam 08:45:14 peserta berada diluar radius DISKOMINFO.'),
(223, '2023-05-30', 'MH000005', 'Pada jam 09:34:29 peserta berada diluar radius DISKOMINFO.'),
(224, '2023-05-30', 'MH000005', 'Pada jam 09:34:29 peserta berada diluar radius DISKOMINFO.'),
(225, '2023-05-30', 'MH000005', 'Pada jam 09:35:48 peserta berada diluar radius DISKOMINFO.'),
(226, '2023-05-30', 'MH000005', 'Pada jam 09:36:43 peserta berada diluar radius DISKOMINFO.'),
(227, '2023-05-17', 'MH000005', 'Pada jam 09:38:55 peserta berada diluar radius DISKOMINFO.'),
(228, '2023-05-17', 'MH000005', 'Pada jam 09:39:57 peserta berada diluar radius DISKOMINFO.'),
(229, '2023-05-17', 'MH000005', 'Pada jam 09:41:43 peserta berada diluar radius DISKOMINFO.'),
(230, '2023-05-17', 'MH000005', 'Pada jam 09:42:34 peserta berada diluar radius DISKOMINFO.'),
(231, '2023-05-17', 'MH000005', 'Pada jam 09:44:27 peserta berada diluar radius DISKOMINFO.'),
(232, '2023-05-30', 'MH000005', 'Pada jam 09:45:34 peserta berada diluar radius DISKOMINFO.'),
(233, '2023-05-30', 'MH000005', 'Pada jam 09:49:17 peserta berada diluar radius DISKOMINFO.'),
(234, '2023-05-30', 'MH000005', 'Pada jam 09:50:49 peserta berada diluar radius DISKOMINFO.'),
(235, '2023-05-30', 'MH000005', 'Pada jam 09:52:39 peserta berada diluar radius DISKOMINFO.'),
(236, '2023-05-30', 'MH000005', 'Pada jam 09:54:16 peserta berada diluar radius DISKOMINFO.'),
(237, '2023-05-30', 'MH000005', 'Pada jam 09:54:55 peserta berada diluar radius DISKOMINFO.'),
(238, '2023-05-30', 'MH000005', 'Pada jam 10:06:43 peserta berada diluar radius DISKOMINFO.'),
(239, '2023-05-30', 'MH000005', 'Pada jam 10:09:19 peserta berada diluar radius DISKOMINFO.'),
(240, '2023-05-30', 'MH000005', 'Pada jam 10:09:47 peserta berada diluar radius DISKOMINFO.'),
(241, '2023-05-30', 'MH000005', 'Pada jam 10:15:33 peserta berada diluar radius DISKOMINFO.'),
(242, '2023-05-30', 'MH000005', 'Pada jam 10:16:43 peserta berada diluar radius DISKOMINFO.'),
(243, '2023-05-30', 'MH000005', 'Pada jam 10:17:40 peserta berada diluar radius DISKOMINFO.'),
(244, '2023-05-30', 'MH000005', 'Pada jam 10:19:15 peserta berada diluar radius DISKOMINFO.'),
(245, '2023-05-30', 'MH000005', 'Pada jam 10:20:28 peserta berada diluar radius DISKOMINFO.'),
(246, '2023-05-30', 'MH000005', 'Pada jam 10:22:29 peserta berada diluar radius DISKOMINFO.'),
(247, '2023-05-30', 'MH000005', 'Pada jam 10:25:16 peserta berada diluar radius DISKOMINFO.'),
(248, '2023-05-30', 'MH000005', 'Pada jam 10:29:56 peserta berada diluar radius DISKOMINFO.'),
(249, '2023-05-30', 'MH000005', 'Pada jam 10:31:14 peserta berada diluar radius DISKOMINFO.'),
(250, '2023-05-30', 'MH000005', 'Pada jam 10:31:52 peserta berada diluar radius DISKOMINFO.'),
(251, '2023-05-30', 'MH000005', 'Pada jam 12:49:12 peserta berada diluar radius DISKOMINFO.'),
(252, '2023-05-30', 'MH000007', 'Pada jam 12:51:11 peserta berada diluar radius DISKOMINFO.'),
(253, '2023-05-30', 'MH000007', 'Pada jam 12:53:15 peserta berada diluar radius DISKOMINFO.'),
(254, '2023-05-17', 'MH000001', 'Pada jam 08:28:13 peserta berada diluar radius DISKOMINFO.'),
(255, '2023-05-17', 'MH000001', 'Pada jam 10:54:58 peserta berada diluar radius DISKOMINFO.'),
(256, '2023-05-17', 'MH000001', 'Pada jam 10:55:17 peserta berada diluar radius DISKOMINFO.'),
(257, '2023-05-17', 'MH000001', 'Pada jam 10:59:46 peserta berada diluar radius DISKOMINFO.'),
(258, '2023-05-17', 'MH000001', 'Pada jam 11:08:20 peserta berada diluar radius DISKOMINFO.'),
(259, '2023-05-16', 'MH000001', 'Pada jam 11:09:25 peserta berada diluar radius DISKOMINFO.'),
(260, '2023-05-16', 'MH000001', 'Pada jam 11:10:01 peserta berada diluar radius DISKOMINFO.'),
(261, '2023-05-16', 'MH000001', 'Pada jam 11:12:12 peserta berada diluar radius DISKOMINFO.'),
(262, '2023-05-16', 'MH000001', 'Pada jam 11:12:59 peserta berada diluar radius DISKOMINFO.'),
(263, '2023-05-16', 'MH000001', 'Pada jam 11:21:26 peserta berada diluar radius DISKOMINFO.'),
(264, '2023-06-05', 'MH000001', 'Pada jam 09:42:08 peserta berada diluar radius DISKOMINFO.'),
(265, '2023-06-09', 'MH000001', 'Pada jam 09:42:43 peserta berada diluar radius DISKOMINFO.'),
(266, '2023-06-06', 'MH000001', 'Pada jam 08:43:47 peserta berada diluar radius DISKOMINFO.'),
(267, '2023-06-07', 'MH000001', 'Pada jam 09:24:57 peserta berada diluar radius DISKOMINFO.'),
(268, '2023-06-07', 'MH000001', 'Pada jam 09:24:57 peserta berada diluar radius DISKOMINFO.'),
(269, '2023-06-07', 'MH000001', 'Pada jam 09:27:58 peserta berada diluar radius DISKOMINFO.'),
(270, '2023-06-07', 'MH000001', 'Pada jam 09:29:27 peserta berada diluar radius DISKOMINFO.'),
(271, '2023-06-07', 'MH000001', 'Pada jam 09:33:58 peserta berada diluar radius DISKOMINFO.'),
(272, '2023-06-07', 'MH000001', 'Pada jam 09:34:57 peserta berada diluar radius DISKOMINFO.'),
(273, '2023-06-07', 'MH000001', 'Pada jam 09:39:49 peserta berada diluar radius DISKOMINFO.'),
(274, '2023-06-07', 'MH000001', 'Pada jam 09:40:35 peserta berada diluar radius DISKOMINFO.'),
(275, '2023-06-07', 'MH000001', 'Pada jam 09:42:33 peserta berada diluar radius DISKOMINFO.'),
(276, '2023-06-07', 'MH000001', 'Pada jam 09:44:36 peserta berada diluar radius DISKOMINFO.'),
(277, '2023-06-07', 'MH000001', 'Pada jam 09:44:50 peserta berada diluar radius DISKOMINFO.'),
(278, '2023-06-07', 'MH000001', 'Pada jam 09:48:23 peserta berada diluar radius DISKOMINFO.'),
(279, '2023-06-07', 'MH000001', 'Pada jam 09:49:37 peserta berada diluar radius DISKOMINFO.'),
(280, '2023-06-07', 'MH000001', 'Pada jam 09:50:35 peserta berada diluar radius DISKOMINFO.'),
(281, '2023-06-07', 'MH000001', 'Pada jam 09:51:31 peserta berada diluar radius DISKOMINFO.'),
(282, '2023-06-07', 'MH000001', 'Pada jam 09:53:01 peserta berada diluar radius DISKOMINFO.'),
(283, '2023-06-07', 'MH000001', 'Pada jam 09:56:23 peserta berada diluar radius DISKOMINFO.'),
(284, '2023-06-07', 'MH000001', 'Pada jam 09:57:09 peserta berada diluar radius DISKOMINFO.'),
(285, '2023-06-07', 'MH000001', 'Pada jam 10:50:19 peserta berada diluar radius DISKOMINFO.'),
(286, '2023-06-07', 'MH000001', 'Pada jam 11:54:52 peserta berada diluar radius DISKOMINFO.'),
(287, '2023-06-07', 'MH000001', 'Pada jam 12:00:02 peserta berada diluar radius DISKOMINFO.'),
(288, '2023-06-07', 'MH000001', 'Pada jam 12:02:04 peserta berada diluar radius DISKOMINFO.'),
(289, '2023-06-07', 'MH000001', 'Pada jam 12:03:48 peserta berada diluar radius DISKOMINFO.'),
(290, '2023-06-07', 'MH000001', 'Pada jam 12:05:05 peserta berada diluar radius DISKOMINFO.'),
(291, '2023-06-07', 'MH000001', 'Pada jam 12:15:11 peserta berada diluar radius DISKOMINFO.'),
(292, '2023-06-07', 'MH000001', 'Pada jam 12:20:46 peserta berada diluar radius DISKOMINFO.'),
(293, '2023-06-07', 'MH000001', 'Pada jam 12:32:15 peserta berada diluar radius DISKOMINFO.'),
(294, '2023-06-07', 'MH000001', 'Pada jam 12:33:09 peserta berada diluar radius DISKOMINFO.'),
(295, '2023-06-07', 'MH000001', 'Pada jam 12:39:58 peserta berada diluar radius DISKOMINFO.'),
(296, '2023-06-07', 'MH000001', 'Pada jam 12:43:01 peserta berada diluar radius DISKOMINFO.'),
(297, '2023-06-07', 'MH000001', 'Pada jam 14:08:10 peserta berada diluar radius DISKOMINFO.'),
(298, '2023-06-07', 'MH000001', 'Pada jam 14:14:42 peserta berada diluar radius DISKOMINFO.'),
(299, '2023-06-08', 'MH000144', 'Pada jam 09:13:15 peserta berada diluar radius DISKOMINFO.'),
(300, '2023-06-08', 'MH000143', 'Pada jam 09:15:54 peserta berada diluar radius DISKOMINFO.'),
(301, '2023-06-08', 'MH000143', 'Pada jam 09:23:51 peserta berada diluar radius DISKOMINFO.'),
(302, '2023-06-08', 'MH000143', 'Pada jam 09:23:51 peserta berada diluar radius DISKOMINFO.'),
(303, '2023-06-08', 'MH000143', 'Pada jam 09:32:09 peserta berada diluar radius DISKOMINFO.'),
(304, '2023-06-08', 'MH000001', 'Pada jam 10:27:23 peserta berada diluar radius DISKOMINFO.'),
(305, '2023-06-08', 'MH000001', 'Pada jam 10:43:56 peserta berada diluar radius DISKOMINFO.'),
(306, '2023-05-17', 'MH000001', 'Pada jam 12:55:19 peserta berada diluar radius DISKOMINFO.'),
(307, '2023-05-17', 'MH000005', 'Pada jam 12:57:31 peserta berada diluar radius DISKOMINFO.'),
(308, '2023-06-08', 'MH000005', 'Pada jam 13:01:35 peserta berada diluar radius DISKOMINFO.'),
(309, '2023-06-08', 'MH000005', 'Pada jam 07:53:12 peserta berada diluar radius DISKOMINFO.'),
(310, '2023-06-09', 'MH000005', 'Pada jam 06:31:24 peserta berada diluar radius DISKOMINFO.'),
(311, '2023-06-09', 'MH000005', 'Pada jam 06:33:02 peserta berada diluar radius DISKOMINFO.'),
(312, '2023-06-09', 'MH000005', 'Pada jam 06:43:12 peserta berada diluar radius DISKOMINFO.'),
(313, '2023-06-09', 'MH000005', 'Pada jam 06:53:34 peserta berada diluar radius DISKOMINFO.'),
(314, '2023-06-09', 'MH000005', 'Pada jam 06:57:21 peserta berada diluar radius DISKOMINFO.'),
(315, '2023-06-09', 'MH000005', 'Pada jam 06:57:21 peserta berada diluar radius DISKOMINFO.'),
(316, '2023-06-16', 'MH000001', 'Pada jam 06:57:46 peserta berada diluar radius DISKOMINFO.'),
(317, '2023-06-16', 'MH000001', 'Pada jam 06:57:46 peserta berada diluar radius DISKOMINFO.'),
(318, '2023-06-16', 'MH000001', 'Pada jam 06:58:49 peserta berada diluar radius DISKOMINFO.'),
(319, '2023-06-16', 'MH000001', 'Pada jam 07:11:01 peserta berada diluar radius DISKOMINFO.'),
(322, '2023-06-16', 'MH000001', 'Pada jam 11:03:09 peserta berada diluar radius DISKOMINFO.'),
(323, '2023-06-16', 'MH000001', 'Pada jam 11:05:01 peserta berada diluar radius DISKOMINFO.'),
(324, '2023-06-16', 'MH000001', 'Pada jam 11:10:40 peserta berada diluar radius DISKOMINFO.'),
(325, '2023-06-19', 'MH000001', 'Pada jam 08:19:41 peserta berada diluar radius DISKOMINFO.'),
(326, '2023-06-19', 'MH000001', 'Pada jam 08:19:42 peserta berada diluar radius DISKOMINFO.'),
(327, '2023-06-19', 'MH000001', 'Pada jam 08:27:38 peserta berada diluar radius DISKOMINFO.'),
(328, '2023-06-19', 'MH000001', 'Pada jam 10:11:39 peserta berada diluar radius DISKOMINFO.'),
(329, '2023-06-19', 'MH000001', 'Pada jam 12:08:03 peserta berada diluar radius DISKOMINFO.'),
(330, '2023-06-19', 'MH000001', 'Pada jam 12:08:50 peserta berada diluar radius DISKOMINFO.'),
(331, '2023-06-19', 'MH000001', 'Pada jam 12:09:38 peserta berada diluar radius DISKOMINFO.'),
(332, '2023-06-19', 'MH000001', 'Pada jam 12:12:13 peserta berada diluar radius DISKOMINFO.'),
(333, '2023-06-19', 'MH000001', 'Pada jam 12:13:54 peserta berada diluar radius DISKOMINFO.'),
(334, '2023-06-20', 'MH000001', 'Pada jam 07:28:45 peserta berada diluar radius DISKOMINFO.'),
(335, '2023-06-20', 'MH000001', 'Pada jam 07:58:27 peserta berada diluar radius DISKOMINFO.'),
(336, '2023-06-20', 'MH000001', 'Pada jam 08:03:47 peserta berada diluar radius DISKOMINFO.'),
(337, '2023-06-20', 'MH000001', 'Pada jam 08:10:49 peserta berada diluar radius DISKOMINFO.'),
(338, '2023-06-20', 'MH000044', 'Pada jam 08:13:22 peserta berada diluar radius DISKOMINFO.'),
(339, '2023-06-20', 'MH000001', 'Pada jam 08:23:22 peserta berada diluar radius DISKOMINFO.'),
(340, '2023-06-20', 'MH000001', 'Pada jam 08:54:44 peserta berada diluar radius DISKOMINFO.'),
(341, '2023-06-20', 'MH000001', 'Pada jam 08:55:25 peserta berada diluar radius DISKOMINFO.'),
(342, '2023-06-20', 'MH000001', 'Pada jam 08:55:54 peserta berada diluar radius DISKOMINFO.'),
(343, '2023-06-21', 'MH000044', 'Pada jam 08:44:20 peserta berada diluar radius DISKOMINFO.'),
(344, '2023-06-21', 'MH000001', 'Pada jam 09:04:27 peserta berada diluar radius DISKOMINFO.'),
(345, '2023-06-21', 'MH000044', 'Pada jam 09:05:25 peserta berada diluar radius DISKOMINFO.'),
(346, '2023-07-07', 'MH000001', 'Pada jam 12:18:59 peserta berada diluar radius DISKOMINFO.'),
(347, '2023-07-07', 'MH000001', 'Pada jam 12:19:35 peserta berada diluar radius DISKOMINFO.'),
(348, '2023-07-07', 'MH000001', 'Pada jam 12:19:56 peserta berada diluar radius DISKOMINFO.'),
(349, '2023-07-07', 'MH000001', 'Pada jam 12:21:21 peserta berada diluar radius DISKOMINFO.'),
(350, '2023-07-07', 'MH000001', 'Pada jam 14:39:30 peserta berada diluar radius DISKOMINFO.'),
(351, '2023-07-07', 'MH000001', 'Pada jam 14:44:14 peserta berada diluar radius DISKOMINFO.'),
(352, '2023-07-07', 'MH000001', 'Pada jam 14:52:57 peserta berada diluar radius DISKOMINFO.'),
(353, '2023-07-07', 'MH000001', 'Pada jam 14:55:10 peserta berada diluar radius DISKOMINFO.'),
(354, '2023-07-07', 'MH000001', 'Pada jam 14:56:53 peserta berada diluar radius DISKOMINFO.'),
(355, '2023-07-07', 'MH000001', 'Pada jam 15:19:49 peserta berada diluar radius DISKOMINFO.'),
(356, '2023-07-07', 'MH000001', 'Pada jam 15:20:33 peserta berada diluar radius DISKOMINFO.'),
(357, '2023-07-07', 'MH000001', 'Pada jam 15:22:08 peserta berada diluar radius DISKOMINFO.'),
(358, '2023-07-07', 'MH000001', 'Pada jam 15:23:49 peserta berada diluar radius DISKOMINFO.'),
(359, '2023-07-07', 'MH000001', 'Pada jam 15:26:18 peserta berada diluar radius DISKOMINFO.'),
(360, '2023-07-07', 'MH000001', 'Pada jam 15:27:53 peserta berada diluar radius DISKOMINFO.'),
(361, '2023-07-07', 'MH000001', 'Pada jam 15:48:10 peserta berada diluar radius DISKOMINFO.'),
(362, '2023-07-07', 'MH000001', 'Pada jam 15:49:41 peserta berada diluar radius DISKOMINFO.'),
(363, '2023-07-07', 'MH000001', 'Pada jam 15:51:01 peserta berada diluar radius DISKOMINFO.'),
(364, '2023-07-07', 'MH000001', 'Pada jam 15:51:27 peserta berada diluar radius DISKOMINFO.'),
(365, '2023-07-07', 'MH000001', 'Pada jam 15:51:45 peserta berada diluar radius DISKOMINFO.'),
(366, '2023-07-07', 'MH000001', 'Pada jam 15:52:03 peserta berada diluar radius DISKOMINFO.'),
(367, '2023-07-07', 'MH000001', 'Pada jam 15:52:24 peserta berada diluar radius DISKOMINFO.'),
(368, '2023-07-07', 'MH000001', 'Pada jam 15:53:48 peserta berada diluar radius DISKOMINFO.'),
(369, '2023-07-07', 'MH000001', 'Pada jam 15:54:47 peserta berada diluar radius DISKOMINFO.'),
(370, '2023-07-07', 'MH000001', 'Pada jam 15:55:52 peserta berada diluar radius DISKOMINFO.'),
(371, '2023-07-07', 'MH000001', 'Pada jam 15:56:32 peserta berada diluar radius DISKOMINFO.'),
(372, '2023-07-07', 'MH000001', 'Pada jam 15:59:23 peserta berada diluar radius DISKOMINFO.'),
(373, '2023-07-07', 'MH000001', 'Pada jam 16:11:10 peserta berada diluar radius DISKOMINFO.'),
(374, '2023-07-07', 'MH000001', 'Pada jam 16:18:20 peserta berada diluar radius DISKOMINFO.'),
(375, '2023-07-07', 'MH000001', 'Pada jam 16:27:21 peserta berada diluar radius DISKOMINFO.'),
(376, '2023-07-07', 'MH000001', 'Pada jam 16:27:47 peserta berada diluar radius DISKOMINFO.'),
(377, '2023-07-07', 'MH000001', 'Pada jam 16:40:33 peserta berada diluar radius DISKOMINFO.'),
(378, '2023-07-07', 'MH000001', 'Pada jam 16:42:27 peserta berada diluar radius DISKOMINFO.'),
(379, '2023-07-07', 'MH000001', 'Pada jam 16:44:13 peserta berada diluar radius DISKOMINFO.'),
(380, '2023-07-07', 'MH000001', 'Pada jam 16:44:53 peserta berada diluar radius DISKOMINFO.'),
(381, '2023-07-07', 'MH000001', 'Pada jam 16:53:14 peserta berada diluar radius DISKOMINFO.'),
(382, '2023-07-07', 'MH000001', 'Pada jam 16:55:22 peserta berada diluar radius DISKOMINFO.'),
(383, '2023-07-07', 'MH000001', 'Pada jam 17:00:03 peserta berada diluar radius DISKOMINFO.'),
(384, '2023-07-07', 'MH000001', 'Pada jam 17:39:08 peserta berada diluar radius DISKOMINFO.'),
(385, '2023-07-07', 'MH000001', 'Pada jam 17:40:53 peserta berada diluar radius DISKOMINFO.'),
(386, '2023-07-07', 'MH000001', 'Pada jam 17:46:08 peserta berada diluar radius DISKOMINFO.'),
(387, '2023-07-07', 'MH000001', 'Pada jam 17:50:45 peserta berada diluar radius DISKOMINFO.'),
(388, '2023-07-07', 'MH000001', 'Pada jam 17:54:42 peserta berada diluar radius DISKOMINFO.'),
(389, '2023-07-07', 'MH000001', 'Pada jam 17:56:33 peserta berada diluar radius DISKOMINFO.'),
(390, '2023-07-07', 'MH000001', 'Pada jam 17:56:40 peserta berada diluar radius DISKOMINFO.'),
(391, '2023-07-07', 'MH000001', 'Pada jam 17:56:43 peserta berada diluar radius DISKOMINFO.'),
(392, '2023-07-07', 'MH000001', 'Pada jam 17:56:46 peserta berada diluar radius DISKOMINFO.'),
(393, '2023-07-07', 'MH000001', 'Pada jam 17:56:49 peserta berada diluar radius DISKOMINFO.'),
(394, '2023-07-07', 'MH000001', 'Pada jam 17:56:52 peserta berada diluar radius DISKOMINFO.'),
(395, '2023-07-07', 'MH000001', 'Pada jam 17:56:55 peserta berada diluar radius DISKOMINFO.'),
(396, '2023-07-07', 'MH000001', 'Pada jam 17:56:58 peserta berada diluar radius DISKOMINFO.'),
(397, '2023-07-07', 'MH000001', 'Pada jam 17:57:01 peserta berada diluar radius DISKOMINFO.'),
(398, '2023-07-07', 'MH000001', 'Pada jam 17:57:04 peserta berada diluar radius DISKOMINFO.'),
(399, '2023-07-07', 'MH000001', 'Pada jam 17:57:10 peserta berada diluar radius DISKOMINFO.'),
(400, '2023-07-07', 'MH000001', 'Pada jam 17:57:10 peserta berada diluar radius DISKOMINFO.'),
(401, '2023-07-07', 'MH000001', 'Pada jam 17:57:13 peserta berada diluar radius DISKOMINFO.'),
(402, '2023-07-07', 'MH000001', 'Pada jam 17:57:14 peserta berada diluar radius DISKOMINFO.'),
(403, '2023-07-07', 'MH000001', 'Pada jam 17:57:18 peserta berada diluar radius DISKOMINFO.'),
(404, '2023-07-07', 'MH000001', 'Pada jam 17:57:20 peserta berada diluar radius DISKOMINFO.'),
(405, '2023-07-07', 'MH000001', 'Pada jam 17:57:23 peserta berada diluar radius DISKOMINFO.'),
(406, '2023-07-07', 'MH000001', 'Pada jam 17:57:32 peserta berada diluar radius DISKOMINFO.'),
(407, '2023-07-07', 'MH000001', 'Pada jam 18:00:52 peserta berada diluar radius DISKOMINFO.'),
(408, '2023-07-07', 'MH000001', 'Pada jam 18:07:26 peserta berada diluar radius DISKOMINFO.'),
(409, '2023-07-07', 'MH000001', 'Pada jam 18:08:31 peserta berada diluar radius DISKOMINFO.'),
(410, '2023-07-07', 'MH000001', 'Pada jam 18:25:07 peserta berada diluar radius DISKOMINFO.'),
(411, '2023-07-07', 'MH000001', 'Pada jam 18:27:07 peserta berada diluar radius DISKOMINFO.'),
(412, '2023-07-07', 'MH000001', 'Pada jam 18:29:30 peserta berada diluar radius DISKOMINFO.'),
(413, '2023-07-07', 'MH000001', 'Pada jam 18:49:39 peserta berada diluar radius DISKOMINFO.'),
(414, '2023-07-07', 'MH000001', 'Pada jam 18:51:18 peserta berada diluar radius DISKOMINFO.'),
(415, '2023-07-07', 'MH000001', 'Pada jam 18:51:40 peserta berada diluar radius DISKOMINFO.'),
(416, '2023-07-07', 'MH000001', 'Pada jam 18:51:53 peserta berada diluar radius DISKOMINFO.'),
(417, '2023-07-07', 'MH000001', 'Pada jam 19:24:01 peserta berada diluar radius DISKOMINFO.'),
(418, '2023-07-07', 'MH000001', 'Pada jam 19:24:42 peserta berada diluar radius DISKOMINFO.'),
(419, '2023-07-05', 'MH000001', 'Pada jam 07:44:50 peserta berada diluar radius DISKOMINFO.'),
(420, '2023-07-05', 'MH000001', 'Pada jam 07:45:04 peserta berada diluar radius DISKOMINFO.'),
(421, '2023-07-05', 'MH000001', 'Pada jam 07:45:32 peserta berada diluar radius DISKOMINFO.'),
(422, '2023-07-05', 'MH000001', 'Pada jam 07:46:18 peserta berada diluar radius DISKOMINFO.'),
(423, '2023-07-05', 'MH000001', 'Pada jam 07:49:21 peserta berada diluar radius DISKOMINFO.'),
(424, '2023-07-05', 'MH000001', 'Pada jam 07:50:21 peserta berada diluar radius DISKOMINFO.'),
(425, '2023-07-05', 'MH000001', 'Pada jam 07:50:37 peserta berada diluar radius DISKOMINFO.'),
(426, '2023-07-05', 'MH000001', 'Pada jam 07:51:21 peserta berada diluar radius DISKOMINFO.'),
(427, '2023-07-05', 'MH000001', 'Pada jam 07:56:39 peserta berada diluar radius DISKOMINFO.'),
(428, '2023-07-05', 'MH000001', 'Pada jam 07:58:28 peserta berada diluar radius DISKOMINFO.'),
(429, '2023-07-10', 'MH000001', 'Pada jam 09:32:04 peserta berada diluar radius DISKOMINFO.'),
(430, '2023-07-10', 'MH000001', 'Pada jam 09:32:04 peserta berada diluar radius DISKOMINFO.'),
(431, '2023-07-10', 'MH000001', 'Pada jam 09:42:14 peserta berada diluar radius DISKOMINFO.'),
(432, '2023-07-10', 'MH000001', 'Pada jam 10:47:10 peserta berada diluar radius DISKOMINFO.'),
(433, '2023-07-10', 'MH000001', 'Pada jam 10:48:24 peserta berada diluar radius DISKOMINFO.'),
(434, '2023-07-10', 'MH000001', 'Pada jam 11:08:02 peserta berada diluar radius DISKOMINFO.'),
(435, '2023-07-10', 'MH000001', 'Pada jam 11:14:41 peserta berada diluar radius DISKOMINFO.'),
(436, '2023-07-10', 'MH000001', 'Pada jam 11:15:34 peserta berada diluar radius DISKOMINFO.'),
(437, '2023-07-10', 'MH000001', 'Pada jam 12:04:26 peserta berada diluar radius DISKOMINFO.'),
(438, '2023-07-10', 'MH000001', 'Pada jam 12:04:45 peserta berada diluar radius DISKOMINFO.'),
(439, '2023-07-10', 'MH000001', 'Pada jam 12:06:46 peserta berada diluar radius DISKOMINFO.'),
(440, '2023-07-10', 'MH000001', 'Pada jam 12:07:16 peserta berada diluar radius DISKOMINFO.'),
(441, '2023-07-10', 'MH000001', 'Pada jam 12:09:03 peserta berada diluar radius DISKOMINFO.'),
(442, '2023-07-10', 'MH000001', 'Pada jam 12:10:46 peserta berada diluar radius DISKOMINFO.'),
(443, '2023-07-10', 'MH000001', 'Pada jam 12:13:40 peserta berada diluar radius DISKOMINFO.'),
(444, '2023-07-10', 'MH000001', 'Pada jam 12:18:26 peserta berada diluar radius DISKOMINFO.'),
(445, '2023-07-10', 'MH000001', 'Pada jam 12:18:43 peserta berada diluar radius DISKOMINFO.'),
(446, '2023-07-10', 'MH000001', 'Pada jam 12:21:06 peserta berada diluar radius DISKOMINFO.'),
(447, '2023-07-10', 'MH000001', 'Pada jam 12:34:39 peserta berada diluar radius DISKOMINFO.'),
(448, '2023-07-10', 'MH000001', 'Pada jam 12:37:53 peserta berada diluar radius DISKOMINFO.'),
(449, '2023-07-10', 'MH000001', 'Pada jam 13:13:30 peserta berada diluar radius DISKOMINFO.'),
(450, '2023-07-10', 'MH000001', 'Pada jam 13:51:53 peserta berada diluar radius DISKOMINFO.'),
(451, '2023-07-10', 'MH000001', 'Pada jam 14:57:56 peserta berada diluar radius DISKOMINFO.'),
(452, '2023-07-10', 'MH000001', 'Pada jam 15:00:26 peserta berada diluar radius DISKOMINFO.'),
(453, '2023-07-10', 'MH000001', 'Pada jam 15:50:14 peserta berada diluar radius DISKOMINFO.'),
(454, '2023-07-10', 'MH000001', 'Pada jam 15:52:24 peserta berada diluar radius DISKOMINFO.'),
(455, '2023-07-10', 'MH000001', 'Pada jam 15:54:55 peserta berada diluar radius DISKOMINFO.'),
(456, '2023-07-10', 'MH000001', 'Pada jam 15:58:08 peserta berada diluar radius DISKOMINFO.'),
(457, '2023-07-10', 'MH000001', 'Pada jam 15:58:08 peserta berada diluar radius DISKOMINFO.'),
(458, '2023-07-10', 'MH000001', 'Pada jam 16:05:59 peserta berada diluar radius DISKOMINFO.'),
(459, '2023-07-11', 'MH000001', 'Pada jam 07:56:56 peserta berada diluar radius DISKOMINFO.'),
(460, '2023-07-11', 'MH000001', 'Pada jam 07:56:56 peserta berada diluar radius DISKOMINFO.'),
(461, '2023-07-11', 'MH000001', 'Pada jam 07:57:35 peserta berada diluar radius DISKOMINFO.'),
(462, '2023-07-11', 'MH000001', 'Pada jam 08:09:10 peserta berada diluar radius DISKOMINFO.'),
(463, '2023-07-11', 'MH000001', 'Pada jam 09:26:51 peserta berada diluar radius DISKOMINFO.'),
(464, '2023-07-11', 'MH000001', 'Pada jam 09:33:00 peserta berada diluar radius DISKOMINFO.'),
(465, '2023-07-11', 'MH000001', 'Pada jam 09:36:59 peserta berada diluar radius DISKOMINFO.'),
(466, '2023-07-11', 'MH000001', 'Pada jam 09:37:47 peserta berada diluar radius DISKOMINFO.'),
(467, '2023-07-11', 'MH000001', 'Pada jam 09:38:54 peserta berada diluar radius DISKOMINFO.'),
(468, '2023-07-11', 'MH000001', 'Pada jam 09:43:53 peserta berada diluar radius DISKOMINFO.'),
(469, '2023-07-14', 'MH000001', 'Pada jam 15:09:44 peserta berada diluar radius DISKOMINFO.'),
(470, '2023-07-14', 'MH000001', 'Pada jam 15:15:47 peserta berada diluar radius DISKOMINFO.'),
(471, '2023-07-14', 'MH000001', 'Pada jam 15:55:13 peserta berada diluar radius DISKOMINFO.'),
(472, '2023-07-26', 'MH000001', 'Pada jam 09:57:43 peserta berada diluar radius DISKOMINFO.'),
(473, '2023-07-26', 'MH000001', 'Pada jam 10:03:50 peserta berada diluar radius DISKOMINFO.'),
(474, '2023-07-26', 'MH000001', 'Pada jam 10:04:32 peserta berada diluar radius DISKOMINFO.'),
(475, '2023-08-04', 'MH000001', 'Pada jam 09:11:41 peserta berada diluar radius DISKOMINFO.');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_logservice`
--

CREATE TABLE `tabel_logservice` (
  `ID_LOGSER` int(11) NOT NULL,
  `TGL_LOGSER` date DEFAULT NULL,
  `KD_PST` varchar(50) DEFAULT NULL,
  `KETERANGAN_LOGSER` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tabel_logservice`
--

INSERT INTO `tabel_logservice` (`ID_LOGSER`, `TGL_LOGSER`, `KD_PST`, `KETERANGAN_LOGSER`) VALUES
(1, '2023-07-17', 'MH000001', 'Pada jam 11:00:00 peserta telah mematikan service lokasi/menghentikan aplikasi.'),
(2, '2023-07-17', 'MH000001', 'Pada jam 11:17:25 peserta telah mematikan service lokasi/menghentikan aplikasi'),
(3, '2023-07-17', 'MH000001', 'Pada jam 15:16:04 peserta telah mematikan service lokasi/menghentikan aplikasi'),
(4, '2023-07-17', 'MH000001', 'Pada jam 15:55:23 peserta telah mematikan service lokasi/menghentikan aplikasi'),
(5, '2023-07-17', 'MH000001', 'Pada jam 07:27:03 peserta telah mematikan service lokasi/menghentikan aplikasi'),
(6, '2023-07-17', 'MH000001', 'Pada jam 07:29:01 peserta telah mematikan service lokasi/menghentikan aplikasi'),
(7, '2023-07-17', 'MH000001', 'Pada jam 07:29:26 peserta telah mematikan service lokasi/menghentikan aplikasi'),
(8, '2023-07-17', 'MH000001', 'Pada jam 07:31:47 peserta telah mematikan service lokasi/menghentikan aplikasi'),
(9, '2023-07-17', 'MH000001', 'Pada jam 07:32:12 peserta telah mematikan service lokasi/menghentikan aplikasi'),
(10, '2023-07-17', 'MH000001', 'Pada jam 07:40:09 peserta telah mematikan service lokasi/menghentikan aplikasi'),
(11, '2023-07-17', 'MH000001', 'Pada jam 07:41:52 peserta telah mematikan service lokasi/menghentikan aplikasi'),
(12, '2023-07-17', 'MH000001', 'Pada jam 07:42:48 peserta telah mematikan service lokasi/menghentikan aplikasi'),
(13, '2023-07-17', 'MH000001', 'Pada jam 07:48:08 peserta telah mematikan service lokasi/menghentikan aplikasi'),
(14, '2023-07-20', 'MH000001', 'Pada jam 08:00:09 peserta telah mematikan service lokasi/menghentikan aplikasi'),
(15, '2023-07-20', 'MH000001', 'Pada jam 08:03:22 peserta telah mematikan service lokasi/menghentikan aplikasi'),
(16, '2023-07-20', 'MH000001', 'Pada jam 12:16:03 peserta telah mematikan service lokasi/menghentikan aplikasi'),
(17, '2023-07-20', 'MH000001', 'Pada jam 12:17:05 peserta telah mematikan service lokasi/menghentikan aplikasi'),
(18, '2023-07-20', 'MH000001', 'Pada jam 12:18:37 peserta telah mematikan service lokasi/menghentikan aplikasi'),
(19, '2023-07-25', 'MH000001', 'Pada jam 07:15:00 peserta telah mematikan service lokasi/menghentikan aplikasi'),
(20, '2023-07-26', 'MH000001', 'Pada jam 07:34:05 peserta telah mematikan service lokasi/menghentikan aplikasi'),
(21, '2023-07-26', 'MH000001', 'Pada jam 10:04:06 peserta telah mematikan service lokasi/menghentikan aplikasi'),
(22, '2023-07-26', 'MH000001', 'Pada jam 10:05:16 peserta telah mematikan service lokasi/menghentikan aplikasi');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_lokasi`
--

CREATE TABLE `tabel_lokasi` (
  `KD_PST` varchar(50) DEFAULT NULL,
  `TANGGAL_LOK` date DEFAULT NULL,
  `LATITUDE_LOK` varchar(100) DEFAULT NULL,
  `LONGITUDE_LOK` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tabel_lokasi`
--

INSERT INTO `tabel_lokasi` (`KD_PST`, `TANGGAL_LOK`, `LATITUDE_LOK`, `LONGITUDE_LOK`) VALUES
('MH000003', '2023-08-04', '-7.81837', '112.03956'),
('MH000004', '2023-08-04', '-7.81613', '112.04269'),
('MH000006', '2023-08-04', '-7.81584', '112.04411'),
('MH000008', '2023-08-04', '-7.81702', '112.04488'),
('MH000049', '2023-08-04', '-7.81823', '112.04372'),
('MH000055', '2023-08-04', '-7.81977', '112.04269'),
('MH000132', '2023-08-04', '-7.81515', '112.04151'),
('MH000141', '2023-08-04', '-7.81545', '112.04226');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_peserta`
--

CREATE TABLE `tabel_peserta` (
  `KD_PST` varchar(50) NOT NULL,
  `KD_ASAL` varchar(50) DEFAULT NULL,
  `KD_AKUN` varchar(50) DEFAULT NULL,
  `NAMA_PST` varchar(200) DEFAULT NULL,
  `JK_PST` varchar(20) DEFAULT NULL,
  `AGAMA_PST` varchar(20) DEFAULT NULL,
  `ALAMAT_PST` varchar(200) DEFAULT NULL,
  `NOHP_PST` varchar(20) DEFAULT NULL,
  `FOTO_PST` varchar(200) DEFAULT NULL,
  `TAHUN_PST` varchar(100) DEFAULT NULL,
  `STATUS_PST` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tabel_peserta`
--

INSERT INTO `tabel_peserta` (`KD_PST`, `KD_ASAL`, `KD_AKUN`, `NAMA_PST`, `JK_PST`, `AGAMA_PST`, `ALAMAT_PST`, `NOHP_PST`, `FOTO_PST`, `TAHUN_PST`, `STATUS_PST`) VALUES
('MH000001', 'KM000001', 'AK000001', 'Ahmad Choirul Huda', 'Laki-laki', 'Islam', 'Dsn. joho ds. sumberejo kec.ngasem kab.kediri prov. jawa timur', '0895807277776', 'dc20230706095820.jpg', '2023', 'aktif'),
('MH000002', 'KM000001', 'AK000004', 'dani rahmat putra', 'Laki-laki', 'Islam', 'Kota tulungagung', '089514912477', 'profil.png', '2023', 'aktif'),
('MH000003', 'KM000001', 'AK000005', 'bima akbar ikhsan prakasa', 'Laki-laki', 'Islam', 'desa purwoasri', '082328753061', 'profil.png', '2023', 'aktif'),
('MH000004', 'KM000002', 'AK000007', 'dias rachma rosalina', 'Perempuan', 'Islam', 'Kota madiun', '083897038041', 'profil.png', '2023', 'tidak aktif'),
('MH000005', 'KM000002', 'AK000008', 'alifta kusuma wulandari', 'laki-laki', 'Budha', 'Kota kediri', '085782427431', 'profil.png', '2023', 'tidak aktif'),
('MH000006', 'KM000003', 'AK000084', 'Budi Santoso', 'Laki-laki', 'Islam', 'Jl. Diponegoro, Ngancar, Kepung, Kediri', '085156854807', 'profil.png', '2023', 'aktif'),
('MH000007', 'KM000003', 'AK000085', 'Anisa Rahmadani', 'Perempuan', 'Islam', 'Dsn. Sumberwono, Muncar, Plemahan, Kediri', '083180840362', 'profil.png', '2023', 'aktif'),
('MH000008', 'KM000003', 'AK000086', 'Dwi Setiawan', 'Laki-laki', 'Kristen', 'Jl. Semeru, Kandat, Kandangan, Kediri', '0895320091720', 'profil.png', '2023', 'aktif'),
('MH000009', 'KM000004', 'AK000087', 'Arif Rahman', 'Laki-laki', 'Kristen', 'Jl. Pemuda, Badas, Pesantren, Kediri', '08990663133', 'profil.png', '2020', 'tidak aktif'),
('MH000010', 'KM000004', 'AK000088', 'Nia Kurniawati', 'Perempuan', 'Islam', 'Dsn. Ngijo, Tawangrejo, Pagu, Kediri', '085757757726', 'profil.png', '2020', 'tidak aktif'),
('MH000011', 'KM000005', 'AK000089', 'Deni Pratama', 'Laki-laki', 'Islam', 'Jl. Raya Mojoroto, Ngampel, Kediri', '083847036840', 'profil.png', '2020', 'tidak aktif'),
('MH000012', 'KM000005', 'AK000090', 'Risma Safitri', 'Perempuan', 'Islam', 'Jl. Cempaka, Ngaran, Papar, Kediri', '081394521769', 'profil.png', '2020', 'tidak aktif'),
('MH000013', 'KM000005', 'AK000091', 'Joko Susilo', 'Laki-laki', 'Islam', 'Dsn. Tempuran, Dawuhan, Pare, Kediri', '0895800109592', 'profil.png', '2020', 'tidak aktif'),
('MH000014', 'KM000005', 'AK000092', 'Siti Nur Aini', 'Perempuan', 'Islam', 'Jl. Trunojoyo, Plosokerep, Ngadiluwih, Kediri', '085336127597', 'profil.png', '2020', 'tidak aktif'),
('MH000015', 'KM000005', 'AK000093', 'Imam Santoso', 'Laki-laki', 'Islam', 'Dsn. Krajan, Kedungjambal, Kepung, Kediri', '085703276364', 'profil.png', '2020', 'tidak aktif'),
('MH000016', 'KM000005', 'AK000094', 'Yulia Setyowati', 'Perempuan', 'Kristen', 'Jl. Diponegoro, Wonokerso, Kepung, Kediri', '087875736841', 'profil.png', '2020', 'tidak aktif'),
('MH000017', 'KM000005', 'AK000095', 'Arif Fathoni', 'Laki-laki', 'Islam', 'Dsn. Bakalan, Mancilan, Gampengrejo, Kediri', '082299495640', 'profil.png', '2020', 'tidak aktif'),
('MH000018', 'KM000005', 'AK000096', 'Siti Aisyah', 'Perempuan', 'Islam', 'Jl. Melati, Karangrejo, Kepung, Kediri', '083879453415', 'profil.png', '2020', 'tidak aktif'),
('MH000019', 'KM000005', 'AK000097', 'Bagus Wicaksono', 'Laki-laki', 'Islam', 'Jl. Raya Ngringo, Kedungtaman, Ngadiluwih, Kediri', '08882534898', 'profil.png', '2020', 'tidak aktif'),
('MH000020', 'KM000006', 'AK000098', 'Novita Wahyuni', 'Perempuan', 'Islam', 'Jl. Mawar, Gading, Ngadiluwih, Kediri', '081317798267', 'profil.png', '2020', 'tidak aktif'),
('MH000021', 'KM000007', 'AK000099', 'Dedy Ariyanto', 'Laki-laki', 'Islam', 'Dsn. Tulungrejo, Kras, Plosokerep, Kediri', '083169693085', 'profil.png', '2020', 'tidak aktif'),
('MH000022', 'KM000007', 'AK000100', 'Siti Nurfadilah', 'Perempuan', 'Islam', 'Dsn. Ngampel, Pagerwojo, Ngadiluwih, Kediri', '085158671455', 'profil.png', '2020', 'tidak aktif'),
('MH000023', 'KM000008', 'AK000101', 'Ahmad Fadli', 'Laki-laki', 'Islam', 'Jl. Pahlawan No. 10, Desa Sidorejo, Kecamatan Ngasem', '082113146464', 'profil.png', '2020', 'tidak aktif'),
('MH000024', 'KM000008', 'AK000102', 'Rina Mardiana', 'Perempuan', 'Islam', 'Jl. Merdeka No. 5, Desa Sukorejo, Kecamatan Pare', '085640652148', 'profil.png', '2020', 'tidak aktif'),
('MH000025', 'KM000008', 'AK000103', 'Budi Setiawan', 'Laki-laki', 'Islam', 'Jl. Diponegoro No. 15, Desa Sidomulyo, Kecamatan Mojo', '08997796616', 'profil.png', '2020', 'tidak aktif'),
('MH000026', 'KM000009', 'AK000104', 'Rudi Santoso', 'Laki-laki', 'Islam', 'Jl. Slamet Riyadi No. 20, Desa Jatimulyo, Kecamatan Ngadiluwih', '0851742080020', 'profil.png', '2021', 'tidak aktif'),
('MH000027', 'KM000009', 'AK000105', 'Putri Lestari', 'Perempuan', 'Islam', 'Jl. Proklamasi No. 8, Desa Gondang, Kecamatan Badas', '081227432593', 'profil.png', '2021', 'tidak aktif'),
('MH000028', 'KM000009', 'AK000106', 'Dedi Prasetyo', 'Laki-laki', 'Islam', 'Jl. Panglima Sudirman No. 12, Desa Kedungkandang, Kecamatan Kandangan', '082284609925', 'profil.png', '2021', 'tidak aktif'),
('MH000029', 'KM000009', 'AK000107', 'Siti Aminah', 'Perempuan', 'Islam', 'Jl. Pemuda No. 3, Desa Wonorejo, Kecamatan Mojoagung', '081517320361', 'profil.png', '2021', 'tidak aktif'),
('MH000030', 'KM000010', 'AK000108', 'Agus Riyanto', 'Laki-laki', 'Islam', 'Jl. Gatot Subroto No. 25, Desa Plosorejo, Kecamatan Kertosono', '082281237500', 'profil.png', '2021', 'tidak aktif'),
('MH000031', 'KM000010', 'AK000109', 'Eka Sari', 'Perempuan', 'Islam', 'Jl. Veteran No. 9, Desa Sambirejo, Kecamatan Ngadirejo', '087837156540', 'profil.png', '2021', 'tidak aktif'),
('MH000032', 'KM000010', 'AK000110', 'Siti Nur Aisyah', 'Perempuan', 'Islam', 'Jl. Diponegoro No. 30, Desa Wates, Kecamatan Pagu', '083170601003', 'profil.png', '2021', 'tidak aktif'),
('MH000033', 'KM000010', 'AK000111', 'Nisa Safitri', 'Perempuan ', 'Islam', 'Jl. Dahlia No. 17, Desa Kalitengah, Kecamatan Pare', '083861205123', 'profil.png', '2021', 'tidak aktif'),
('MH000034', 'KM000011', 'AK000112', 'Ahmad Rifai', 'Laki-laki', 'Islam', 'Jl. Raya Kepatihan No. 43, Desa Kepatihan, Kecamatan Kepung', '085174902345', 'profil.png', '2021', 'tidak aktif'),
('MH000035', 'KM000012', 'AK000113', 'Rina Puspita', 'Perempuan ', 'Kristen', 'Jl. Cokroaminoto No. 25, Desa Sukorejo, Kecamatan Gampengrejo', '085731618404', 'profil.png', '2021', 'tidak aktif'),
('MH000036', 'KM000012', 'AK000114', 'Muhammad Fadil', 'Laki-laki', 'Islam', 'Jl. Pahlawan No. 21, Desa Pesantren, Kecamatan Mojo', '085773878366', 'profil.png', '2021', 'tidak aktif'),
('MH000037', 'KM000013', 'AK000115', 'Yuliana', 'Perempuan ', 'Kristen ', 'Jl. Diponegoro No. 11, Desa Ngringo, Kecamatan Ngadiluwih', '088973693940', 'profil.png', '2021', 'tidak aktif'),
('MH000038', 'KM000013', 'AK000116', 'Ahmad Zaki', 'Laki-laki', 'Islam', 'Jl. Sultan Agung No. 8, Desa Jambu, Kecamatan Wates', '0895365614939', 'profil.png', '2021', 'tidak aktif'),
('MH000039', 'KM000013', 'AK000117', 'Fitri Nurul', 'Perempuan ', 'Islam', 'Jl. Veteran No. 4, Desa Sumberjati, Kecamatan Grogol', '081263528188', 'profil.png', '2021', 'tidak aktif'),
('MH000040', 'KM000011', 'AK000118', 'Siti Hidayah', 'Perempuan ', 'Islam', 'Jl. Raya Ngancar No. 22, Desa Ngancar, Kecamatan Ngancar', '081383767956', 'profil.png', '2021', 'tidak aktif'),
('MH000041', 'KM000011', 'AK000119', 'Siti Hidayah', 'Laki-laki', 'Islam', 'Jl. Imam Bonjol No. 10, Desa Banaran, Kecamatan Kandat', '085668916407', 'profil.png', '2021', 'tidak aktif'),
('MH000042', 'KM000013', 'AK000120', 'Anisa Dwi', 'Perempuan ', 'Islam', 'Jl. Raya Jombang No. 17, Desa Plosorejo, Kecamatan Pagu', '089603929565', 'profil.png', '2021', 'tidak aktif'),
('MH000043', 'KM000001', 'AK000121', 'Ridwan Maulana', 'Laki-laki', 'Islam', 'Jl. Cempaka No. 5, Desa Kepuhkembeng, Kecamatan Badas', '081939497547', 'profil.png', '2023', 'aktif'),
('MH000044', 'KM000001', 'AK000122', 'Miftahul Jannah', 'Perempuan ', 'Islam', 'Jl. Kartini No. 12, Desa Gumul, Kecamatan Papar', '087754528181', 'profil.png', '2023', 'aktif'),
('MH000045', 'KM000001', 'AK000123', 'Bagus Surya', 'Laki-laki', 'Islam', 'Jl. Ahmad Yani No. 9, Desa Puncu, Kecamatan Puncu', '085226079062', 'profil.png', '2021', 'tidak aktif'),
('MH000046', 'KM000003', 'AK000124', 'Anisa Putri', 'Laki-laki', 'Islam', 'Jl. Diponegoro, Ngancar, Kepung, Kediri', '085156854807', 'profil.png', '2019', 'tidak aktif'),
('MH000047', 'KM000003', 'AK000125', 'Bambang Santoso', 'Perempuan', 'Islam', 'Dsn. Sumberwono, Muncar, Plemahan, Kediri', '083180840362', 'profil.png', '2019', 'tidak aktif'),
('MH000048', 'KM000003', 'AK000126', 'Cita Lestari', 'Laki-laki', 'Islam', 'Jl. Semeru, Kandat, Kandangan, Kediri', '0895320091720', 'profil.png', '2020', 'tidak aktif'),
('MH000049', 'KM000003', 'AK000127', 'Denny Widodo', 'Laki-laki', 'Islam', 'Jl. Pemuda, Badas, Pesantren, Kediri', '08990663133', 'profil.png', '2023', 'aktif'),
('MH000050', 'KM000003', 'AK000128', 'Elisa Pangestu', 'Perempuan', 'Islam', 'Dsn. Ngijo, Tawangrejo, Pagu, Kediri', '085757757726', 'profil.png', '2020', 'tidak aktif'),
('MH000051', 'KM000003', 'AK000129', 'Farhan Hakim', 'Laki-laki', 'Islam', 'Jl. Raya Mojoroto, Ngampel, Kediri', '083847036840', 'profil.png', '2020', 'tidak aktif'),
('MH000052', 'KM000003', 'AK000130', 'Gita Kusuma', 'Laki-laki', 'Islam', 'Jl. Cempaka, Ngaran, Papar, Kediri', '081394521769', 'profil.png', '2023', 'aktif'),
('MH000053', 'KM000003', 'AK000131', 'Hadi Wibowo', 'Perempuan', 'Islam', 'Dsn. Tempuran, Dawuhan, Pare, Kediri', '0895800109592', 'profil.png', '2019', 'tidak aktif'),
('MH000054', 'KM000003', 'AK000132', 'Intan Septiani', 'Laki-laki', 'Islam', 'Jl. Trunojoyo, Plosokerep, Ngadiluwih, Kediri', '085336127597', 'profil.png', '2019', 'tidak aktif'),
('MH000055', 'KM000003', 'AK000133', 'Joko Sutomo', 'Perempuan', 'Islam', 'Dsn. Krajan, Kedungjambal, Kepung, Kediri', '085703276364', 'profil.png', '2023', 'aktif'),
('MH000056', 'KM000004', 'AK000134', 'Kiki Nurhayati', 'Laki-laki', 'Islam', 'Jl. Diponegoro, Wonokerso, Kepung, Kediri', '087875736841', 'profil.png', '2021', 'tidak aktif'),
('MH000057', 'KM000004', 'AK000135', 'Lina Anggraeni', 'Perempuan', 'Islam', 'Dsn. Bakalan, Mancilan, Gampengrejo, Kediri', '082299495640', 'profil.png', '2021', 'tidak aktif'),
('MH000058', 'KM000004', 'AK000136', 'Malik Fauzi', 'Perempuan', 'Islam', 'Jl. Melati, Karangrejo, Kepung, Kediri', '083879453415', 'profil.png', '2019', 'tidak aktif'),
('MH000059', 'KM000004', 'AK000137', 'Nanda Pratama', 'Laki-laki', 'Islam', 'Jl. Raya Ngringo, Kedungtaman, Ngadiluwih, Kediri', '08882534898', 'profil.png', '2020', 'tidak aktif'),
('MH000060', 'KM000004', 'AK000138', 'Oktavia Dewi', 'Laki-laki', 'Islam', 'Jl. Mawar, Gading, Ngadiluwih, Kediri', '081317798267', 'profil.png', '2022', 'tidak aktif'),
('MH000061', 'KM000004', 'AK000139', 'Purnama Sari', 'Perempuan', 'Islam', 'Dsn. Tulungrejo, Kras, Plosokerep, Kediri', '083169693085', 'profil.png', '2019', 'tidak aktif'),
('MH000062', 'KM000004', 'AK000140', 'Qori Ramadhan', 'Laki-laki', 'Islam', 'Dsn. Ngampel, Pagerwojo, Ngadiluwih, Kediri', '085158671455', 'profil.png', '2019', 'tidak aktif'),
('MH000063', 'KM000004', 'AK000141', 'Rina Maulida', 'Laki-laki', 'Islam', 'Jl. Pahlawan No. 10, Desa Sidorejo, Kecamatan Ngasem', '082113146464', 'profil.png', '2020', 'tidak aktif'),
('MH000064', 'KM000004', 'AK000142', 'Sigit Wijaya', 'Perempuan', 'Islam', 'Jl. Merdeka No. 5, Desa Sukorejo, Kecamatan Pare', '085640652148', 'profil.png', '2022', 'tidak aktif'),
('MH000065', 'KM000004', 'AK000143', 'Tika Rachmawati', 'Laki-laki', 'Islam', 'Jl. Diponegoro No. 15, Desa Sidomulyo, Kecamatan Mojo', '08997796616', 'profil.png', '2020', 'tidak aktif'),
('MH000066', 'KM000005', 'AK000144', 'Umar Syarif', 'Perempuan', 'Islam', 'Jl. Slamet Riyadi No. 20, Desa Jatimulyo, Kecamatan Ngadiluwih', '0851742080020', 'profil.png', '2021', 'tidak aktif'),
('MH000067', 'KM000005', 'AK000145', 'Vivi Susanti', 'Perempuan', 'Islam', 'Jl. Proklamasi No. 8, Desa Gondang, Kecamatan Badas', '081227432593', 'profil.png', '2021', 'tidak aktif'),
('MH000068', 'KM000005', 'AK000146', 'Wahyu Kurniawan', 'Laki-laki', 'Islam', 'Jl. Panglima Sudirman No. 12, Desa Kedungkandang, Kecamatan Kandangan', '082284609925', 'profil.png', '2019', 'tidak aktif'),
('MH000069', 'KM000005', 'AK000147', 'Xanthe Yulianti', 'Laki-laki', 'Islam', 'Jl. Pemuda No. 3, Desa Wonorejo, Kecamatan Mojoagung', '081517320361', 'profil.png', '2021', 'tidak aktif'),
('MH000070', 'KM000005', 'AK000148', 'Yuda Pramana', 'Laki-laki', 'Islam', 'Jl. Gatot Subroto No. 25, Desa Plosorejo, Kecamatan Kertosono', '082281237500', 'profil.png', '2022', 'tidak aktif'),
('MH000071', 'KM000005', 'AK000149', 'Zainab Rohmani', 'Perempuan', 'Islam', 'Jl. Veteran No. 9, Desa Sambirejo, Kecamatan Ngadirejo', '087837156540', 'profil.png', '2019', 'tidak aktif'),
('MH000072', 'KM000005', 'AK000150', 'Adi Nugroho', 'Perempuan', 'Islam', 'Jl. Diponegoro No. 30, Desa Wates, Kecamatan Pagu', '083170601003', 'profil.png', '2022', 'tidak aktif'),
('MH000073', 'KM000006', 'AK000151', 'Bella Suryadi', 'Laki-laki', 'Islam', 'Jl. Dahlia No. 17, Desa Kalitengah, Kecamatan Pare', '083861205123', 'profil.png', '2020', 'tidak aktif'),
('MH000074', 'KM000006', 'AK000152', 'Candra Wijaya', 'Perempuan', 'Islam', 'Jl. Raya Kepatihan No. 43, Desa Kepatihan, Kecamatan Kepung', '085174902345', 'profil.png', '2022', 'tidak aktif'),
('MH000075', 'KM000006', 'AK000153', 'Dara Putri', 'Perempuan', 'Islam', 'Jl. Cokroaminoto No. 25, Desa Sukorejo, Kecamatan Gampengrejo', '085731618404', 'profil.png', '2020', 'tidak aktif'),
('MH000076', 'KM000006', 'AK000154', 'Eko Prasetyo', 'Laki-laki', 'Islam', 'Jl. Pahlawan No. 21, Desa Pesantren, Kecamatan Mojo', '085773878366', 'profil.png', '2021', 'tidak aktif'),
('MH000077', 'KM000006', 'AK000155', 'Fira Safitri', 'Perempuan', 'Islam', 'Jl. Diponegoro No. 11, Desa Ngringo, Kecamatan Ngadiluwih', '088973693940', 'profil.png', '2019', 'tidak aktif'),
('MH000078', 'KM000006', 'AK000156', 'Gilang Santosa', 'Perempuan', 'Islam', 'Jl. Sultan Agung No. 8, Desa Jambu, Kecamatan Wates', '0895365614939', 'profil.png', '2022', 'tidak aktif'),
('MH000079', 'KM000006', 'AK000157', 'Hana Maharani', 'Laki-laki', 'Islam', 'Jl. Veteran No. 4, Desa Sumberjati, Kecamatan Grogol', '081263528188', 'profil.png', '2021', 'tidak aktif'),
('MH000080', 'KM000006', 'AK000158', 'Ivan Setiawan', 'Laki-laki', 'Islam', 'Jl. Raya Ngancar No. 22, Desa Ngancar, Kecamatan Ngancar', '081383767956', 'profil.png', '2019', 'tidak aktif'),
('MH000081', 'KM000007', 'AK000159', 'Jihan Fajri', 'Laki-laki', 'Islam', 'Jl. Imam Bonjol No. 10, Desa Banaran, Kecamatan Kandat', '085668916407', 'profil.png', '2021', 'tidak aktif'),
('MH000082', 'KM000007', 'AK000160', 'Kusuma Wardhani', 'Laki-laki', 'Islam', 'Jl. Raya Jombang No. 17, Desa Plosorejo, Kecamatan Pagu', '089603929565', 'profil.png', '2019', 'tidak aktif'),
('MH000083', 'KM000007', 'AK000161', 'Lutfi Maulana', 'Perempuan', 'Islam', 'Jl. Cempaka No. 5, Desa Kepuhkembeng, Kecamatan Badas', '081939497547', 'profil.png', '2021', 'tidak aktif'),
('MH000084', 'KM000007', 'AK000162', 'Mawar Sari', 'Laki-laki', 'Islam', 'Jl. Kartini No. 12, Desa Gumul, Kecamatan Papar', '087754528181', 'profil.png', '2020', 'tidak aktif'),
('MH000085', 'KM000008', 'AK000163', 'Nadia Utami', 'Perempuan', 'Islam', 'Jl. Ahmad Yani No. 9, Desa Puncu, Kecamatan Puncu', '085226079062', 'profil.png', '2022', 'tidak aktif'),
('MH000086', 'KM000008', 'AK000164', 'Oktavianus', 'Laki-laki', 'Islam', 'Jl. Diponegoro, Ngancar, Kepung, Kediri', '085156854807', 'profil.png', '2022', 'tidak aktif'),
('MH000087', 'KM000008', 'AK000165', 'Putra Nugraha', 'Perempuan', 'Islam', 'Dsn. Sumberwono, Muncar, Plemahan, Kediri', '083180840362', 'profil.png', '2020', 'tidak aktif'),
('MH000088', 'KM000008', 'AK000166', 'Qonita Dewi', 'Perempuan', 'Islam', 'Jl. Semeru, Kandat, Kandangan, Kediri', '0895320091720', 'profil.png', '2019', 'tidak aktif'),
('MH000089', 'KM000008', 'AK000167', 'Ratna Kumala', 'Laki-laki', 'Islam', 'Jl. Pemuda, Badas, Pesantren, Kediri', '08990663133', 'profil.png', '2019', 'tidak aktif'),
('MH000090', 'KM000008', 'AK000168', 'Surya Perdana', 'Laki-laki', 'Islam', 'Dsn. Ngijo, Tawangrejo, Pagu, Kediri', '085757757726', 'profil.png', '2022', 'tidak aktif'),
('MH000091', 'KM000009', 'AK000169', 'Tika Ardianto', 'Perempuan', 'Islam', 'Jl. Raya Mojoroto, Ngampel, Kediri', '083847036840', 'profil.png', '2021', 'tidak aktif'),
('MH000092', 'KM000009', 'AK000170', 'Umi Khairani', 'Laki-laki', 'Islam', 'Jl. Cempaka, Ngaran, Papar, Kediri', '081394521769', 'profil.png', '2020', 'tidak aktif'),
('MH000093', 'KM000009', 'AK000171', 'Vina Rizki', 'Perempuan', 'Islam', 'Dsn. Tempuran, Dawuhan, Pare, Kediri', '0895800109592', 'profil.png', '2021', 'tidak aktif'),
('MH000094', 'KM000009', 'AK000172', 'Wawan Suryanto', 'Laki-laki', 'Islam', 'Jl. Trunojoyo, Plosokerep, Ngadiluwih, Kediri', '085336127597', 'profil.png', '2020', 'tidak aktif'),
('MH000095', 'KM000009', 'AK000173', 'Yeni Fitriani', 'Laki-laki', 'Islam', 'Dsn. Krajan, Kedungjambal, Kepung, Kediri', '085703276364', 'profil.png', '2021', 'tidak aktif'),
('MH000096', 'KM000009', 'AK000174', 'Zahra Wijaya', 'Laki-laki', 'Islam', 'Jl. Diponegoro, Wonokerso, Kepung, Kediri', '087875736841', 'profil.png', '2019', 'tidak aktif'),
('MH000097', 'KM000010', 'AK000175', 'Agus Santoso', 'Perempuan', 'Islam', 'Dsn. Bakalan, Mancilan, Gampengrejo, Kediri', '082299495640', 'profil.png', '2022', 'tidak aktif'),
('MH000098', 'KM000010', 'AK000176', 'Bunga Puspita', 'Perempuan', 'Islam', 'Jl. Melati, Karangrejo, Kepung, Kediri', '083879453415', 'profil.png', '2019', 'tidak aktif'),
('MH000099', 'KM000010', 'AK000177', 'Cici Ardhana', 'Laki-laki', 'Islam', 'Jl. Raya Ngringo, Kedungtaman, Ngadiluwih, Kediri', '08882534898', 'profil.png', '2020', 'tidak aktif'),
('MH000100', 'KM000010', 'AK000178', 'Dian Purnomo', 'Perempuan', 'Islam', 'Jl. Mawar, Gading, Ngadiluwih, Kediri', '081317798267', 'profil.png', '2021', 'tidak aktif'),
('MH000101', 'KM000010', 'AK000179', 'Eka Septiana', 'Laki-laki', 'Islam', 'Dsn. Tulungrejo, Kras, Plosokerep, Kediri', '083169693085', 'profil.png', '2021', 'tidak aktif'),
('MH000102', 'KM000011', 'AK000180', 'Fitri Handayani', 'Laki-laki', 'Islam', 'Dsn. Ngampel, Pagerwojo, Ngadiluwih, Kediri', '085158671455', 'profil.png', '2020', 'tidak aktif'),
('MH000103', 'KM000011', 'AK000181', 'Galang Ramadhan', 'Perempuan', 'Islam', 'Jl. Pahlawan No. 10, Desa Sidorejo, Kecamatan Ngasem', '082113146464', 'profil.png', '2022', 'tidak aktif'),
('MH000104', 'KM000011', 'AK000182', 'Hesty Nurhayati', 'Laki-laki', 'Islam', 'Jl. Merdeka No. 5, Desa Sukorejo, Kecamatan Pare', '085640652148', 'profil.png', '2020', 'tidak aktif'),
('MH000105', 'KM000011', 'AK000183', 'Irfan Mardianto', 'Perempuan', 'Islam', 'Jl. Diponegoro No. 15, Desa Sidomulyo, Kecamatan Mojo', '08997796616', 'profil.png', '2022', 'tidak aktif'),
('MH000106', 'KM000011', 'AK000184', 'Jaka Maulana', 'Perempuan', 'Islam', 'Jl. Slamet Riyadi No. 20, Desa Jatimulyo, Kecamatan Ngadiluwih', '0851742080020', 'profil.png', '2020', 'tidak aktif'),
('MH000107', 'KM000011', 'AK000185', 'Kania Fitriani', 'Laki-laki', 'Islam', 'Jl. Proklamasi No. 8, Desa Gondang, Kecamatan Badas', '081227432593', 'profil.png', '2022', 'tidak aktif'),
('MH000108', 'KM000011', 'AK000186', 'Lala Saputra', 'Laki-laki', 'Islam', 'Jl. Panglima Sudirman No. 12, Desa Kedungkandang, Kecamatan Kandangan', '082284609925', 'profil.png', '2019', 'tidak aktif'),
('MH000109', 'KM000011', 'AK000187', 'Mira Permata', 'Laki-laki', 'Islam', 'Jl. Pemuda No. 3, Desa Wonorejo, Kecamatan Mojoagung', '081517320361', 'profil.png', '2019', 'tidak aktif'),
('MH000110', 'KM000011', 'AK000188', 'Nana Sari', 'Perempuan', 'Islam', 'Jl. Gatot Subroto No. 25, Desa Plosorejo, Kecamatan Kertosono', '082281237500', 'profil.png', '2020', 'tidak aktif'),
('MH000111', 'KM000011', 'AK000189', 'Oktavian Putra', 'Laki-laki', 'Islam', 'Jl. Veteran No. 9, Desa Sambirejo, Kecamatan Ngadirejo', '087837156540', 'profil.png', '2022', 'tidak aktif'),
('MH000112', 'KM000011', 'AK000190', 'Putri Maharani', 'Laki-laki', 'Islam', 'Jl. Diponegoro No. 30, Desa Wates, Kecamatan Pagu', '083170601003', 'profil.png', '2020', 'tidak aktif'),
('MH000113', 'KM000011', 'AK000191', 'Qorina Ayu', 'Perempuan', 'Islam', 'Jl. Dahlia No. 17, Desa Kalitengah, Kecamatan Pare', '083861205123', 'profil.png', '2021', 'tidak aktif'),
('MH000114', 'KM000012', 'AK000192', 'Rendra Pradana', 'Perempuan', 'Islam', 'Jl. Raya Kepatihan No. 43, Desa Kepatihan, Kecamatan Kepung', '085174902345', 'profil.png', '2019', 'tidak aktif'),
('MH000115', 'KM000012', 'AK000193', 'Sari Ayu', 'Laki-laki', 'Islam', 'Jl. Cokroaminoto No. 25, Desa Sukorejo, Kecamatan Gampengrejo', '085731618404', 'profil.png', '2022', 'tidak aktif'),
('MH000116', 'KM000012', 'AK000194', 'Teguh Setiawan', 'Perempuan', 'Islam', 'Jl. Pahlawan No. 21, Desa Pesantren, Kecamatan Mojo', '085773878366', 'profil.png', '2021', 'tidak aktif'),
('MH000117', 'KM000012', 'AK000195', 'Uli Arini', 'Laki-laki', 'Islam', 'Jl. Diponegoro No. 11, Desa Ngringo, Kecamatan Ngadiluwih', '088973693940', 'profil.png', '2021', 'tidak aktif'),
('MH000118', 'KM000012', 'AK000196', 'Vina Lestari', 'Perempuan', 'Islam', 'Jl. Sultan Agung No. 8, Desa Jambu, Kecamatan Wates', '0895365614939', 'profil.png', '2019', 'tidak aktif'),
('MH000119', 'KM000012', 'AK000197', 'Wira Dharma', 'Laki-laki', 'Islam', 'Jl. Veteran No. 4, Desa Sumberjati, Kecamatan Grogol', '081263528188', 'profil.png', '2020', 'tidak aktif'),
('MH000120', 'KM000012', 'AK000198', 'Yani Prayoga', 'Laki-laki', 'Islam', 'Jl. Raya Ngancar No. 22, Desa Ngancar, Kecamatan Ngancar', '081383767956', 'profil.png', '2022', 'tidak aktif'),
('MH000121', 'KM000012', 'AK000199', 'Zainal Abidin', 'Perempuan', 'Islam', 'Jl. Imam Bonjol No. 10, Desa Banaran, Kecamatan Kandat', '085668916407', 'profil.png', '2021', 'tidak aktif'),
('MH000122', 'KM000013', 'AK000200', 'Aditya Pratama', 'Perempuan', 'Islam', 'Jl. Raya Jombang No. 17, Desa Plosorejo, Kecamatan Pagu', '089603929565', 'profil.png', '2020', 'tidak aktif'),
('MH000123', 'KM000013', 'AK000201', 'Bella Aprillia', 'Laki-laki', 'Islam', 'Jl. Cempaka No. 5, Desa Kepuhkembeng, Kecamatan Badas', '081939497547', 'profil.png', '2019', 'tidak aktif'),
('MH000124', 'KM000013', 'AK000202', 'Chandra Widjaja', 'Laki-laki', 'Islam', 'Jl. Kartini No. 12, Desa Gumul, Kecamatan Papar', '087754528181', 'profil.png', '2022', 'tidak aktif'),
('MH000125', 'KM000013', 'AK000203', 'Dwi Agus Setiawan', 'Laki-laki', 'Islam', 'Jl. Ahmad Yani No. 9, Desa Puncu, Kecamatan Puncu', '085226079062', 'profil.png', '2021', 'tidak aktif'),
('MH000126', 'KM000013', 'AK000204', 'Fadhilah Nurhaliza', 'Perempuan', 'Islam', 'Jl. Diponegoro, Ngancar, Kepung, Kediri', '085156854807', 'profil.png', '2019', 'tidak aktif'),
('MH000127', 'KM000013', 'AK000205', 'Gina Putri', 'Laki-laki', 'Islam', 'Dsn. Sumberwono, Muncar, Plemahan, Kediri', '083180840362', 'profil.png', '2020', 'tidak aktif'),
('MH000128', 'KM000013', 'AK000206', 'Herman Susanto', 'Perempuan', 'Islam', 'Jl. Semeru, Kandat, Kandangan, Kediri', '0895320091720', 'profil.png', '2022', 'tidak aktif'),
('MH000129', 'KM000001', 'AK000207', 'Indah Permata', 'Laki-laki', 'Islam', 'Jl. Pemuda, Badas, Pesantren, Kediri', '08990663133', 'profil.png', '2021', 'tidak aktif'),
('MH000130', 'KM000001', 'AK000208', 'Jodi Susanto', 'Perempuan', 'Islam', 'Dsn. Ngijo, Tawangrejo, Pagu, Kediri', '085757757726', 'profil.png', '2021', 'tidak aktif'),
('MH000131', 'KM000001', 'AK000209', 'Kadek Supriyanto', 'Perempuan', 'Islam', 'Jl. Raya Mojoroto, Ngampel, Kediri', '083847036840', 'profil.png', '2020', 'tidak aktif'),
('MH000132', 'KM000001', 'AK000210', 'Lestari Ayu', 'Laki-laki', 'Islam', 'Jl. Cempaka, Ngaran, Papar, Kediri', '081394521769', 'profil.png', '2023', 'aktif'),
('MH000133', 'KM000001', 'AK000211', 'Muhammad Arief', 'Laki-laki', 'Islam', 'Dsn. Tempuran, Dawuhan, Pare, Kediri', '0895800109592', 'profil.png', '2019', 'tidak aktif'),
('MH000134', 'KM000001', 'AK000212', 'Nindi Safitri', 'Perempuan', 'Islam', 'Jl. Trunojoyo, Plosokerep, Ngadiluwih, Kediri', '085336127597', 'profil.png', '2020', 'tidak aktif'),
('MH000135', 'KM000001', 'AK000213', 'Okta Puspitasari', 'Laki-laki', 'Islam', 'Dsn. Krajan, Kedungjambal, Kepung, Kediri', '085703276364', 'profil.png', '2021', 'tidak aktif'),
('MH000136', 'KM000001', 'AK000214', 'Putri Kusuma', 'perempuan', 'Islam', 'Jl. Diponegoro, Wonokerso, Kepung, Kediri', '087875736841', 'profil.png', '2020', 'tidak aktif'),
('MH000137', 'KM000001', 'AK000215', 'Qoriah Sulistyaningrum', 'Laki-laki', 'Islam', 'Dsn. Bakalan, Mancilan, Gampengrejo, Kediri', '082299495640', 'profil.png', '2019', 'tidak aktif'),
('MH000138', 'KM000001', 'AK000216', 'Rifki Pratama', 'Laki-laki', 'Islam', 'Jl. Melati, Karangrejo, Kepung, Kediri', '083879453415', 'profil.png', '2019', 'tidak aktif'),
('MH000139', 'KM000001', 'AK000217', 'Sari Dewi', 'Perempuan', 'Islam', 'Jl. Raya Ngringo, Kedungtaman, Ngadiluwih, Kediri', '08882534898', 'profil.png', '2020', 'tidak aktif'),
('MH000140', 'KM000001', 'AK000218', 'Tania Novita', 'Perempuan', 'Islam', 'Jl. Mawar, Gading, Ngadiluwih, Kediri', '081317798267', 'profil.png', '2019', 'tidak aktif'),
('MH000141', 'KM000001', 'AK000219', 'Umar Faruk', 'Laki-laki', 'Islam', 'Dsn. Tulungrejo, Kras, Plosokerep, Kediri', '083169693085', 'profil.png', '2023', 'aktif'),
('MH000142', 'KM000001', 'AK000220', 'dfgdfgdfgdfg', 'Laki-laki', 'Budha', 'Sdfsdfsdf', '0868765864364', 'profil.png', '2023', 'aktif'),
('MH000143', 'KM000005', 'AK000222', 'jjjjjjjjjj', 'Laki-laki', 'Hindu', 'Jjjjjjjjj', '0868765864364', 'profil.png', '2023', 'aktif'),
('MH000144', 'KM000004', 'AK000223', 'Llllll', 'Laki-laki', 'Islam', 'Llllllllll', '0868765864364', 'profil.png', '2023', 'aktif'),
('MH000145', 'KM000009', 'AK000224', 'qqqqqqqq', 'Laki-laki', 'Islam', 'qqqqqqqqqq', '0868765864364', 'profil.png', '2023', 'aktif'),
('MH000146', 'KM000001', 'AK000225', 'vvvvvvvvvvv', 'Laki-laki', 'Budha', 'Vvvvvvvvvvv', '0868765864364', 'profil.png', '2023', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_tim_peserta`
--

CREATE TABLE `tabel_tim_peserta` (
  `KD_TIM` varchar(50) NOT NULL,
  `KD_KAWAN` varchar(50) DEFAULT NULL,
  `NAMA_TIM` varchar(200) DEFAULT NULL,
  `KD_ASAL` varchar(50) DEFAULT NULL,
  `TGL_MULAI_TIM` date DEFAULT NULL,
  `TGL_SELESAI_TIM` date DEFAULT NULL,
  `TAHUN_TIM` varchar(10) DEFAULT NULL,
  `STATUS_TIM` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tabel_tim_peserta`
--

INSERT INTO `tabel_tim_peserta` (`KD_TIM`, `KD_KAWAN`, `NAMA_TIM`, `KD_ASAL`, `TGL_MULAI_TIM`, `TGL_SELESAI_TIM`, `TAHUN_TIM`, `STATUS_TIM`) VALUES
('T000001', 'KY000015', 'Tim F Polinema PSDKU Kediri', 'KM000001', '2023-04-01', '2023-08-31', '2023', 'aktif'),
('T000002', 'KY000015', 'tim a Universitas Negeri Surabaya', 'KM000002', '2023-04-06', '2023-05-31', '2023', 'tidak aktif'),
('T000003', 'KY000003', 'Tim H Polinema PSDKU Kediri', 'KM000001', '2023-01-07', '2023-03-01', '2023', 'tidak aktif'),
('T000004', 'KY000015', 'Tim A Polinema PSDKU Kediri', 'KM000001', '2019-01-01', '2019-01-31', '2019', 'tidak aktif'),
('T000005', 'KY000015', 'Tim B Polinema PSDKU Kediri', 'KM000001', '2020-01-01', '2020-01-31', '2020', 'tidak aktif'),
('T000006', 'KY000015', 'Tim C Polinema PSDKU Kediri', 'KM000001', '2020-02-01', '2020-02-29', '2020', 'tidak aktif'),
('T000007', 'KY000015', 'Tim D Polinema PSDKU Kediri', 'KM000001', '2021-01-01', '2021-01-31', '2021', 'tidak aktif'),
('T000008', 'KY000015', 'Tim E Polinema PSDKU Kediri', 'KM000001', '2023-04-01', '2023-08-31', '2023', 'aktif'),
('T000009', 'KY000001', 'Tim G Polinema PSDKU Kediri', 'KM000001', '2023-04-01', '2023-08-31', '2023', 'aktif'),
('T000010', 'KY000015', 'Tim A SMK Negeri 1 Kediri', 'KM000003', '2019-02-01', '2019-02-28', '2019', 'tidak aktif'),
('T000011', 'KY000015', 'Tim B SMK Negeri 1 Kediri', 'KM000003', '2020-03-01', '2020-03-31', '2020', 'tidak aktif'),
('T000012', 'KY000015', 'Tim C SMK Negeri 1 Kediri', 'KM000003', '2023-04-01', '2023-08-31', '2023', 'aktif'),
('T000013', 'KY000015', 'Tim D SMK Negeri 1 Kediri', 'KM000003', '2023-04-01', '2023-08-31', '2023', 'aktif'),
('T000014', 'KY000007', 'Tim A SMK Negeri 1 Ngasem', 'KM000004', '2019-03-01', '2019-03-31', '2019', 'tidak aktif'),
('T000015', 'KY000007', 'Tim B SMK Negeri 1 Ngasem', 'KM000004', '2020-05-01', '2020-05-31', '2020', 'tidak aktif'),
('T000016', 'KY000007', 'Tim C SMK Negeri 1 Ngasem', 'KM000004', '2020-06-01', '2020-06-30', '2020', 'tidak aktif'),
('T000017', 'KY000007', 'Tim D SMK Negeri 1 Ngasem', 'KM000004', '2021-03-01', '2021-03-31', '2021', 'tidak aktif'),
('T000018', 'KY000007', 'Tim E SMK Negeri 1 Ngasem', 'KM000004', '2022-03-01', '2022-03-31', '2022', 'tidak aktif'),
('T000019', 'KY000019', 'Tim A SMK Negeri 1 Plosoklaten', 'KM000005', '2019-04-01', '2019-04-30', '2019', 'tidak aktif'),
('T000020', 'KY000019', 'Tim B SMK Negeri 1 Plosoklaten', 'KM000005', '2020-07-01', '2020-07-31', '2020', 'tidak aktif'),
('T000021', 'KY000019', 'Tim C SMK Negeri 1 Plosoklaten', 'KM000005', '2020-08-01', '2020-08-31', '2020', 'tidak aktif'),
('T000022', 'KY000019', 'Tim D SMK Negeri 1 Plosoklaten', 'KM000005', '2020-09-01', '2020-09-30', '2020', 'tidak aktif'),
('T000023', 'KY000019', 'Tim E SMK Negeri 1 Plosoklaten', 'KM000005', '2021-04-01', '2021-04-30', '2021', 'tidak aktif'),
('T000024', 'KY000019', 'Tim F SMK Negeri 1 Plosoklaten', 'KM000005', '2022-04-01', '2022-04-30', '2022', 'tidak aktif'),
('T000025', 'KY000034', 'Tim A SMK Negeri 1 Semen', 'KM000006', '2019-05-01', '2019-05-31', '2019', 'tidak aktif'),
('T000026', 'KY000034', 'Tim B SMK Negeri 1 Semen', 'KM000006', '2020-10-01', '2020-10-31', '2020', 'tidak aktif'),
('T000027', 'KY000034', 'Tim C SMK Negeri 1 Semen', 'KM000006', '2021-05-01', '2021-05-31', '2021', 'tidak aktif'),
('T000028', 'KY000034', 'Tim D SMK Negeri 1 Semen', 'KM000006', '2022-05-01', '2022-05-31', '2022', 'tidak aktif'),
('T000029', 'KY000021', 'Tim A SMKS PGRI 1 Kediri', 'KM000007', '2019-06-01', '2019-06-30', '2019', 'tidak aktif'),
('T000030', 'KY000021', 'Tim B SMKS PGRI 1 Kediri', 'KM000007', '2020-11-01', '2020-11-30', '2020', 'tidak aktif'),
('T000031', 'KY000021', 'Tim C SMKS PGRI 1 Kediri', 'KM000007', '2021-06-18', '2021-06-20', '2021', 'tidak aktif'),
('T000032', 'KY000021', 'Tim D SMKS PGRI 2 Kediri', 'KM000008', '2019-07-01', '2019-07-31', '2019', 'tidak aktif'),
('T000033', 'KY000021', 'Tim E SMKS PGRI 2 Kediri', 'KM000008', '2020-12-01', '2020-12-31', '2020', 'tidak aktif'),
('T000034', 'KY000021', 'Tim F SMKS PGRI 2 Kediri', 'KM000008', '2020-04-13', '2020-04-17', '2020', 'tidak aktif'),
('T000035', 'KY000021', 'Tim G SMKS PGRI 2 Kediri', 'KM000008', '2022-06-01', '2022-06-30', '2022', 'tidak aktif'),
('T000036', 'KY000027', 'Tim A SMKS Al Huda Kediri', 'KM000009', '2019-08-01', '2019-08-31', '2019', 'tidak aktif'),
('T000037', 'KY000027', 'Tim B SMKS Al Huda Kediri', 'KM000009', '2020-05-22', '2020-05-26', '2020', 'tidak aktif'),
('T000038', 'KY000027', 'Tim C SMKS Al Huda Kediri', 'KM000009', '2021-06-01', '2021-06-30', '2021', 'tidak aktif'),
('T000039', 'KY000027', 'Tim D SMKS Al Huda Kediri', 'KM000009', '2021-07-01', '2021-07-31', '2021', 'tidak aktif'),
('T000040', 'KY000027', 'Tim E SMKS Al Huda Kediri', 'KM000009', '2021-08-01', '2021-08-31', '2021', 'tidak aktif'),
('T000041', 'KY000027', 'Tim A SMK Negeri 3 Kediri', 'KM000010', '2019-09-01', '2019-09-30', '2019', 'tidak aktif'),
('T000042', 'KY000016', 'Tim B SMK Negeri 3 Kediri', 'KM000010', '2020-06-22', '2020-06-26', '2020', 'tidak aktif'),
('T000043', 'KY000016', 'Tim C SMK Negeri 3 Kediri', 'KM000010', '2021-09-01', '2021-09-30', '2021', 'tidak aktif'),
('T000044', 'KY000016', 'Tim D SMK Negeri 3 Kediri', 'KM000010', '2021-10-01', '2021-10-31', '2021', 'tidak aktif'),
('T000045', 'KY000016', 'Tim E SMK Negeri 3 Kediri', 'KM000010', '2022-07-01', '2022-07-31', '2022', 'tidak aktif'),
('T000046', 'KY000016', 'Tim A Universitas Nusantara Kediri', 'KM000011', '2019-10-01', '2019-10-31', '2019', 'tidak aktif'),
('T000047', 'KY000016', 'Tim B Universitas Nusantara Kediri', 'KM000011', '2020-07-31', '2020-08-02', '2020', 'tidak aktif'),
('T000048', 'KY000016', 'Tim C Universitas Nusantara Kediri', 'KM000011', '2020-09-25', '2020-09-27', '2020', 'tidak aktif'),
('T000049', 'KY000009', 'Tim D Universitas Nusantara Kediri', 'KM000011', '2021-11-01', '2021-11-30', '2021', 'tidak aktif'),
('T000050', 'KY000009', 'Tim E Universitas Nusantara Kediri', 'KM000011', '2021-12-01', '2021-12-31', '2022', 'tidak aktif'),
('T000051', 'KY000009', 'Tim A Universitas Kahuripan Kediri', 'KM000012', '2019-11-01', '2019-11-30', '2019', 'tidak aktif'),
('T000052', 'KY000009', 'Tim B Universitas Kahuripan Kediri', 'KM000012', '2020-10-29', '2020-11-01', '2020', 'tidak aktif'),
('T000053', 'KY000009', 'Tim C Universitas Kahuripan Kediri', 'KM000012', '2021-03-08', '2021-03-14', '2021', 'tidak aktif'),
('T000054', 'KY000009', 'Tim D Universitas Kahuripan Kediri', 'KM000012', '2021-04-02', '2021-04-04', '2021', 'tidak aktif'),
('T000055', 'KY000033', 'Tim E Universitas Kahuripan Kediri', 'KM000012', '2022-08-01', '2022-08-31', '2022', 'tidak aktif'),
('T000056', 'KY000033', 'Tim A Universitas Kadiri', 'KM000013', '2019-12-01', '2019-12-31', '2019', 'tidak aktif'),
('T000057', 'KY000033', 'Tim B Universitas Kadiri', 'KM000013', '2020-12-24', '2020-12-27', '2020', 'tidak aktif'),
('T000058', 'KY000033', 'Tim C Universitas Kadiri', 'KM000013', '2021-05-13', '2021-05-15', '2021', 'tidak aktif'),
('T000059', 'KY000033', 'Tim D Universitas Kadiri', 'KM000013', '2022-09-01', '2022-09-30', '2022', 'tidak aktif'),
('T000060', 'KY000016', 'SDFADFSDF', 'KM000003', '2023-07-01', '2023-08-31', '2023', 'aktif'),
('T000061', 'KY000015', 'aqaqaq', 'KM000004', '2023-07-01', '2023-08-31', '2023', 'aktif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_absensi`
--
ALTER TABLE `tabel_absensi`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_RELATIONSHIP_8` (`KD_PST`);

--
-- Indexes for table `tabel_akun`
--
ALTER TABLE `tabel_akun`
  ADD PRIMARY KEY (`KD_AKUN`);

--
-- Indexes for table `tabel_asal`
--
ALTER TABLE `tabel_asal`
  ADD PRIMARY KEY (`KD_ASAL`);

--
-- Indexes for table `tabel_dtl_tim_peserta`
--
ALTER TABLE `tabel_dtl_tim_peserta`
  ADD PRIMARY KEY (`DTL_ID`),
  ADD KEY `FK_RELATIONSHIP_14` (`KD_PST`),
  ADD KEY `FK_RELATIONSHIP_15` (`KD_TIM`);

--
-- Indexes for table `tabel_jabatan`
--
ALTER TABLE `tabel_jabatan`
  ADD PRIMARY KEY (`KD_JBTN`);

--
-- Indexes for table `tabel_karyawan`
--
ALTER TABLE `tabel_karyawan`
  ADD PRIMARY KEY (`KD_KAWAN`),
  ADD KEY `FK_RELATIONSHIP_13` (`KD_JBTN`),
  ADD KEY `FK_RELATIONSHIP_7` (`KD_AKUN`);

--
-- Indexes for table `tabel_konfigurasi`
--
ALTER TABLE `tabel_konfigurasi`
  ADD PRIMARY KEY (`KD_KONF`);

--
-- Indexes for table `tabel_libur_nasional`
--
ALTER TABLE `tabel_libur_nasional`
  ADD PRIMARY KEY (`ID_LBR`);

--
-- Indexes for table `tabel_logpos`
--
ALTER TABLE `tabel_logpos`
  ADD PRIMARY KEY (`ID_LOG`),
  ADD KEY `FK_RELATIONSHIP_9` (`KD_PST`);

--
-- Indexes for table `tabel_logservice`
--
ALTER TABLE `tabel_logservice`
  ADD PRIMARY KEY (`ID_LOGSER`),
  ADD KEY `FK_LOGSERVICE_PESERTA` (`KD_PST`);

--
-- Indexes for table `tabel_lokasi`
--
ALTER TABLE `tabel_lokasi`
  ADD KEY `FK_RELATIONSHIP_16` (`KD_PST`);

--
-- Indexes for table `tabel_peserta`
--
ALTER TABLE `tabel_peserta`
  ADD PRIMARY KEY (`KD_PST`),
  ADD KEY `FK_RELATIONSHIP_6` (`KD_ASAL`);

--
-- Indexes for table `tabel_tim_peserta`
--
ALTER TABLE `tabel_tim_peserta`
  ADD PRIMARY KEY (`KD_TIM`),
  ADD KEY `FK_RELATIONSHIP_10` (`KD_ASAL`),
  ADD KEY `FK_RELATIONSHIP_11` (`KD_KAWAN`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tabel_absensi`
--
ALTER TABLE `tabel_absensi`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=254;

--
-- AUTO_INCREMENT for table `tabel_dtl_tim_peserta`
--
ALTER TABLE `tabel_dtl_tim_peserta`
  MODIFY `DTL_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=308;

--
-- AUTO_INCREMENT for table `tabel_libur_nasional`
--
ALTER TABLE `tabel_libur_nasional`
  MODIFY `ID_LBR` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tabel_logpos`
--
ALTER TABLE `tabel_logpos`
  MODIFY `ID_LOG` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=476;

--
-- AUTO_INCREMENT for table `tabel_logservice`
--
ALTER TABLE `tabel_logservice`
  MODIFY `ID_LOGSER` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tabel_absensi`
--
ALTER TABLE `tabel_absensi`
  ADD CONSTRAINT `FK_RELATIONSHIP_8` FOREIGN KEY (`KD_PST`) REFERENCES `tabel_peserta` (`KD_PST`);

--
-- Constraints for table `tabel_dtl_tim_peserta`
--
ALTER TABLE `tabel_dtl_tim_peserta`
  ADD CONSTRAINT `FK_RELATIONSHIP_14` FOREIGN KEY (`KD_PST`) REFERENCES `tabel_peserta` (`KD_PST`),
  ADD CONSTRAINT `FK_RELATIONSHIP_15` FOREIGN KEY (`KD_TIM`) REFERENCES `tabel_tim_peserta` (`KD_TIM`);

--
-- Constraints for table `tabel_karyawan`
--
ALTER TABLE `tabel_karyawan`
  ADD CONSTRAINT `FK_RELATIONSHIP_13` FOREIGN KEY (`KD_JBTN`) REFERENCES `tabel_jabatan` (`KD_JBTN`),
  ADD CONSTRAINT `FK_RELATIONSHIP_7` FOREIGN KEY (`KD_AKUN`) REFERENCES `tabel_akun` (`KD_AKUN`);

--
-- Constraints for table `tabel_logpos`
--
ALTER TABLE `tabel_logpos`
  ADD CONSTRAINT `FK_RELATIONSHIP_9` FOREIGN KEY (`KD_PST`) REFERENCES `tabel_peserta` (`KD_PST`);

--
-- Constraints for table `tabel_logservice`
--
ALTER TABLE `tabel_logservice`
  ADD CONSTRAINT `FK_LOGSERVICE_PESERTA` FOREIGN KEY (`KD_PST`) REFERENCES `tabel_peserta` (`KD_PST`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tabel_lokasi`
--
ALTER TABLE `tabel_lokasi`
  ADD CONSTRAINT `FK_RELATIONSHIP_16` FOREIGN KEY (`KD_PST`) REFERENCES `tabel_peserta` (`KD_PST`);

--
-- Constraints for table `tabel_peserta`
--
ALTER TABLE `tabel_peserta`
  ADD CONSTRAINT `FK_RELATIONSHIP_6` FOREIGN KEY (`KD_ASAL`) REFERENCES `tabel_asal` (`KD_ASAL`);

--
-- Constraints for table `tabel_tim_peserta`
--
ALTER TABLE `tabel_tim_peserta`
  ADD CONSTRAINT `FK_RELATIONSHIP_10` FOREIGN KEY (`KD_ASAL`) REFERENCES `tabel_asal` (`KD_ASAL`),
  ADD CONSTRAINT `FK_RELATIONSHIP_11` FOREIGN KEY (`KD_KAWAN`) REFERENCES `tabel_karyawan` (`KD_KAWAN`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
