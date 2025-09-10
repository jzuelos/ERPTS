-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2025 at 06:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erpts`
--

-- --------------------------------------------------------

--
-- Table structure for table `brgy`
--

CREATE TABLE `brgy` (
  `brgy_id` int(11) NOT NULL,
  `brgy_code` varchar(10) NOT NULL,
  `brgy_name` varchar(100) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `m_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brgy`
--

INSERT INTO `brgy` (`brgy_id`, `brgy_code`, `brgy_name`, `status`, `m_id`) VALUES
(1, '051601001', 'Angas', 'Active', 11),
(2, '051601002', 'Bactas', 'Active', 11),
(3, '051601003', 'Binatagan', 'Active', 11),
(4, '051601004', 'Caayunan', 'Active', 11),
(5, '051601005', 'Guinatungan', 'Active', 11),
(6, '051601006', 'Hinampacan', 'Active', 11),
(7, '051601007', 'Langa', 'Active', 11),
(8, '051601008', 'Laniton', 'Active', 11),
(9, '051601009', 'Lidong', 'Active', 11),
(10, '051601010', 'Mampili', 'Active', 11),
(11, '051601011', 'Mandazo', 'Active', 11),
(12, '051601012', 'Mangcamagong', 'Active', 11),
(13, '051601013', 'Manmuntay', 'Active', 11),
(14, '051601014', 'Mantugawe', 'Active', 11),
(15, '051601015', 'Matnog', 'Active', 11),
(16, '051601016', 'Mocong', 'Active', 11),
(17, '051601017', 'Oliva', 'Active', 11),
(18, '051601018', 'Pagsangahan', 'Active', 11),
(19, '051601019', 'Pinagwarasan', 'Active', 11),
(20, '051601020', 'Plaridel', 'Active', 11),
(21, '051601021', 'Poblacion 1', 'Active', 11),
(22, '051601022', 'Poblacion 2', 'Active', 11),
(23, '051601023', 'San Felipe', 'Active', 11),
(24, '051601024', 'San Jose', 'Active', 11),
(25, '051601025', 'San Pascual', 'Active', 11),
(26, '051601026', 'Taba-taba', 'Active', 11),
(27, '051601027', 'Tacad', 'Active', 11),
(28, '051601028', 'Taisan', 'Active', 11),
(29, '051601029', 'Tuaca', 'Active', 11),
(30, '051602001', 'Alayao', 'Active', 10),
(31, '051602002', 'Binawangan', 'Active', 10),
(32, '051602003', 'Calabaca', 'Active', 10),
(33, '051602004', 'Camagsaan', 'Active', 10),
(34, '051602005', 'Catabaguangan', 'Active', 10),
(35, '051602006', 'Catioan', 'Active', 10),
(36, '051602007', 'Del Pilar', 'Active', 10),
(37, '051602008', 'Itok', 'Active', 10),
(38, '051602009', 'Lucbanan', 'Active', 10),
(39, '051602010', 'Mabini', 'Active', 10),
(40, '051602011', 'Mactang', 'Active', 10),
(41, '051602012', 'Mataque', 'Active', 10),
(42, '051602013', 'Old Camp', 'Active', 10),
(43, '051602014', 'Poblacion', 'Active', 10),
(44, '051602015', 'Magsaysay', 'Active', 10),
(45, '051602016', 'San Antonio', 'Active', 10),
(46, '051602017', 'San Isidro', 'Active', 10),
(47, '051602018', 'San Roque', 'Active', 10),
(48, '051602019', 'Tanauan', 'Active', 10),
(49, '051602020', 'Ubang', 'Active', 10),
(50, '051602021', 'Villa Aurora', 'Active', 10),
(51, '051602022', 'Villa Belen', 'Active', 10),
(52, '051603001', 'Alawihao', 'Active', 3),
(53, '051603002', 'Awitan', 'Active', 3),
(54, '051603003', 'Bagasbas', 'Active', 3),
(55, '051603004', 'Barangay I (Poblacion)', 'Active', 3),
(56, '051603005', 'Barangay II (Poblacion)', 'Active', 3),
(57, '051603006', 'Barangay III (Poblacion)', 'Active', 3),
(58, '051603007', 'Barangay IV (Poblacion)', 'Active', 3),
(59, '051603008', 'Barangay V (Poblacion)', 'Active', 3),
(60, '051603009', 'Barangay VI (Poblacion)', 'Active', 3),
(61, '051603010', 'Barangay VII (Poblacion)', 'Active', 3),
(62, '051603011', 'Barangay VIII (Poblacion)', 'Active', 3),
(63, '051603012', 'Barangay IX (Poblacion)', 'Active', 3),
(64, '051603013', 'Barangay X (Poblacion)', 'Active', 3),
(65, '051603014', 'Barangay XI (Poblacion)', 'Active', 3),
(66, '051603015', 'Barangay XII (Poblacion)', 'Active', 3),
(67, '051603016', 'Barangay XIII (Poblacion)', 'Active', 3),
(68, '051603017', 'Barangay XIV (Poblacion)', 'Active', 3),
(69, '051603018', 'Barangay XV (Poblacion)', 'Active', 3),
(70, '051603019', 'Barangay XVI (Poblacion)', 'Active', 3),
(71, '051603020', 'Barangay XVII (Poblacion)', 'Active', 3),
(72, '051603021', 'Barangay XVIII (Poblacion)', 'Active', 3),
(73, '051603022', 'Barangay XIX (Poblacion)', 'Active', 3),
(74, '051603023', 'Barangay XX (Poblacion)', 'Active', 3),
(75, '051603024', 'Cobangbang', 'Active', 3),
(76, '051603025', 'Gahonon', 'Active', 3),
(77, '051605001', 'Bagong Bayan', 'Active', 9),
(78, '051605002', 'Calero', 'Active', 9),
(79, '051605003', 'Dahican', 'Active', 9),
(80, '051605004', 'Dayhagan', 'Active', 9),
(81, '051605005', 'Larap', 'Active', 9),
(82, '051605006', 'Luklukan Norte', 'Active', 9),
(83, '051605007', 'Luklukan Sur', 'Active', 9),
(84, '051605008', 'Motherlode', 'Active', 9),
(85, '051605009', 'Nakalaya', 'Active', 9),
(86, '051605010', 'Osmeña', 'Active', 9),
(87, '051605011', 'Pag-Asa', 'Active', 9),
(88, '051605012', 'Parang', 'Active', 9),
(89, '051605013', 'Plaridel', 'Active', 9),
(90, '051605014', 'North Poblacion', 'Active', 9),
(91, '051605015', 'South Poblacion', 'Active', 9),
(92, '051605016', 'Salvacion', 'Active', 9),
(93, '051605017', 'San Isidro', 'Active', 9),
(94, '051605018', 'San Jose', 'Active', 9),
(95, '051605019', 'San Martin', 'Active', 9),
(96, '051605020', 'San Pedro', 'Active', 9),
(97, '051605021', 'San Rafael', 'Active', 9),
(98, '051605022', 'Santa Cruz', 'Active', 9),
(99, '051605023', 'Santa Elena', 'Active', 9),
(100, '051605024', 'Santa Milagrosa', 'Active', 9),
(101, '051605025', 'Santa Rosa Norte', 'Active', 9),
(102, '051605026', 'Santa Rosa Sur', 'Active', 9),
(103, '051605027', 'Tamisan', 'Active', 9),
(104, '051606001', 'Anahaw', 'Active', 7),
(105, '051606002', 'Anameam', 'Active', 7),
(106, '051606003', 'Awitan', 'Active', 7),
(107, '051606004', 'Baay', 'Active', 7),
(108, '051606005', 'Bagacay', 'Active', 7),
(109, '051606006', 'Bagong Silang I', 'Active', 7),
(110, '051606007', 'Bagong Silang II', 'Active', 7),
(111, '051606008', 'Bagong Silang III', 'Active', 7),
(112, '051606009', 'Bakiad', 'Active', 7),
(113, '051606010', 'Bautista', 'Active', 7),
(114, '051606011', 'Bayabas', 'Active', 7),
(115, '051606012', 'Bayan-bayan', 'Active', 7),
(116, '051606013', 'Benit', 'Active', 7),
(117, '051606014', 'Bulhao', 'Active', 7),
(118, '051606015', 'Cabusay', 'Active', 7),
(119, '051606016', 'Calabasa', 'Active', 7),
(120, '051606017', 'Canapawan', 'Active', 7),
(121, '051606018', 'Cayucyucan', 'Active', 7),
(122, '051606019', 'Daguit', 'Active', 7),
(123, '051606020', 'Dalas', 'Active', 7),
(124, '051606021', 'Dumagmang', 'Active', 7),
(125, '051606022', 'Exciban', 'Active', 7),
(126, '051606023', 'Fundado', 'Active', 7),
(127, '051606024', 'Guinacutan', 'Active', 7),
(128, '051606025', 'Guisican', 'Active', 7),
(129, '051606026', 'Gumamela', 'Active', 7),
(130, '051606027', 'Iberica', 'Active', 7),
(131, '051606028', 'Kalamunding', 'Active', 7),
(132, '051606029', 'Lugui', 'Active', 7),
(133, '051606030', 'Mabilo I', 'Active', 7),
(134, '051606031', 'Mabilo II', 'Active', 7),
(135, '051606032', 'Macogon', 'Active', 7),
(136, '051606033', 'Mahawan-hawan', 'Active', 7),
(137, '051606034', 'Malangcao-Basud', 'Active', 7),
(138, '051606035', 'Malasugui', 'Active', 7),
(139, '051606036', 'Malatap', 'Active', 7),
(140, '051606037', 'Malaya', 'Active', 7),
(141, '051606038', 'Malibago', 'Active', 7),
(142, '051606039', 'Maot', 'Active', 7),
(143, '051606040', 'Masalong', 'Active', 7),
(144, '051606041', 'Matanlang', 'Active', 7),
(145, '051606042', 'Napaod', 'Active', 7),
(146, '051606043', 'Pag-Asa', 'Active', 7),
(147, '051606044', 'Pangpang', 'Active', 7),
(148, '051606045', 'Pinya', 'Active', 7),
(149, '051606046', 'San Antonio', 'Active', 7),
(150, '051606047', 'San Francisco', 'Active', 7),
(151, '051606048', 'Santa Cruz', 'Active', 7),
(152, '051606049', 'Submakin', 'Active', 7),
(153, '051606050', 'Talobatib', 'Active', 7),
(154, '051606051', 'Tigbinan', 'Active', 7),
(155, '051606052', 'Tulay Na Lupa', 'Active', 7),
(156, '051607001', 'Apuao', 'Active', 4),
(157, '051607002', 'Caringo', 'Active', 4),
(158, '051607003', 'Catandunganon', 'Active', 4),
(159, '051607004', 'Cayucyucan', 'Active', 4),
(160, '051607005', 'Colasi', 'Active', 4),
(161, '051607006', 'Del Rosario', 'Active', 4),
(162, '051607007', 'Hinipaan', 'Active', 4),
(163, '051607008', 'Lalawigan', 'Active', 4),
(164, '051607009', 'Lanot', 'Active', 4),
(165, '051607010', 'Mambungalon', 'Active', 4),
(166, '051607011', 'Manguisoc', 'Active', 4),
(167, '051607012', 'Manlucugan', 'Active', 4),
(168, '051607013', 'Masalongsalong', 'Active', 4),
(169, '051607014', 'Pambuhan', 'Active', 4),
(170, '051607015', 'Quinapaguian', 'Active', 4),
(171, '051607016', 'San Roque', 'Active', 4),
(172, '051607017', 'San Jose (Poblacion)', 'Active', 4),
(173, '051607018', 'San Juan (Poblacion)', 'Active', 4),
(174, '051607019', 'San Vicente (Poblacion)', 'Active', 4),
(175, '051607020', 'Santa Elena (Poblacion)', 'Active', 4),
(176, '051607021', 'Tabas', 'Active', 4),
(177, '051607022', 'Tanawan', 'Active', 4),
(178, '051607023', 'Taytayan', 'Active', 4),
(179, '051607024', 'Tres Reyes', 'Active', 4),
(180, '051607025', 'Villa Hermosa', 'Active', 4),
(181, '051608001', 'Awitan', 'Active', 8),
(182, '051608002', 'Bagumbayan', 'Active', 8),
(183, '051608003', 'Batobalani', 'Active', 8),
(184, '051608004', 'Calaburnay', 'Active', 8),
(185, '051608005', 'Capacuan', 'Active', 8),
(186, '051608006', 'Casalugan', 'Active', 8),
(187, '051608007', 'Dagang', 'Active', 8),
(188, '051608008', 'Dalnac', 'Active', 8),
(189, '051608009', 'Gumaus', 'Active', 8),
(190, '051608010', 'Labnig', 'Active', 8),
(191, '051608011', 'Larap', 'Active', 8),
(192, '051608012', 'Macolabo Island', 'Active', 8),
(193, '051608013', 'Malacbang', 'Active', 8),
(194, '051608014', 'Malaguit', 'Active', 8),
(195, '051608015', 'Malaya', 'Active', 8),
(196, '051608016', 'Mampungo', 'Active', 8),
(197, '051608017', 'Mangkasay', 'Active', 8),
(198, '051608018', 'Maybato', 'Active', 8),
(199, '051608019', 'Palanas', 'Active', 8),
(200, '051608020', 'Poblacion Norte', 'Active', 8),
(201, '051608021', 'Poblacion Sur', 'Active', 8),
(202, '051608022', 'Talusan', 'Active', 8),
(203, '051608023', 'Tugos', 'Active', 8),
(204, '051608024', 'Pinagbirayan Malaki', 'Active', 8),
(205, '051608025', 'Pinagbirayan Munti', 'Active', 8),
(206, '051608026', 'Malaguit Island', 'Active', 8),
(207, '051608027', 'Batobalani Extension', 'Active', 8),
(208, '051609001', 'Daculang Bolo', 'Active', 13),
(209, '051609002', 'Dagotdotan', 'Active', 13),
(210, '051609003', 'Langga', 'Active', 13),
(211, '051609004', 'Laniton', 'Active', 13),
(212, '051609005', 'Maisog', 'Active', 13),
(213, '051609006', 'Mampurog', 'Active', 13),
(214, '051609007', 'Manlimonsito', 'Active', 13),
(215, '051609008', 'Matacong', 'Active', 13),
(216, '051609009', 'Salvacion', 'Active', 13),
(217, '051609010', 'San Isidro', 'Active', 13),
(218, '051609011', 'San Antonio', 'Active', 13),
(219, '051609012', 'San Ramon', 'Active', 13),
(220, '051613001', 'Asdum', 'Active', 12),
(221, '051613002', 'Cabanbanan', 'Active', 12),
(222, '051613003', 'Calabagas', 'Active', 12),
(223, '051613004', 'Fabrica', 'Active', 12),
(224, '051613005', 'Iraya Sur', 'Active', 12),
(225, '051613006', 'Man-Ogob', 'Active', 12),
(226, '051613007', 'Poblacion District I', 'Active', 12),
(227, '051613008', 'Poblacion District II', 'Active', 12),
(228, '051613009', 'San Jose', 'Active', 12),
(229, '051610001', 'Awitan', 'Active', 14),
(230, '051610002', 'Basiad', 'Active', 14),
(231, '051610003', 'Bulala', 'Active', 14),
(232, '051610004', 'Cabuluan', 'Active', 14),
(233, '051610005', 'Don Tomas', 'Active', 14),
(234, '051610006', 'Guitol', 'Active', 14),
(235, '051610007', 'Kabuluan', 'Active', 14),
(236, '051610008', 'Kagtalaba', 'Active', 14),
(237, '051610009', 'Magtang', 'Active', 14),
(238, '051610010', 'Maulawin', 'Active', 14),
(239, '051610011', 'Patag Ibaba', 'Active', 14),
(240, '051610012', 'Patag Iraya', 'Active', 14),
(241, '051610013', 'Plaridel', 'Active', 14),
(242, '051610014', 'Polungguitguit', 'Active', 14),
(243, '051610015', 'Rizal', 'Active', 14),
(244, '051610016', 'San Lorenzo', 'Active', 14),
(245, '051610017', 'San Pedro', 'Active', 14),
(246, '051610018', 'Santa Elena (Poblacion)', 'Active', 14),
(247, '051610019', 'Tabugon', 'Active', 14),
(248, '051611001', 'Binanuahan', 'Active', 5),
(249, '051611002', 'Caawigan', 'Active', 5),
(250, '051611003', 'Cahabaan', 'Active', 5),
(251, '051611004', 'Calintaan', 'Active', 5),
(252, '051611005', 'Del Carmen', 'Active', 5),
(253, '051611006', 'Gabon', 'Active', 5),
(254, '051611007', 'Itomang', 'Active', 5),
(255, '051611008', 'Poblacion', 'Active', 5),
(256, '051611009', 'San Francisco', 'Active', 5),
(257, '051611010', 'San Isidro', 'Active', 5),
(258, '051611011', 'San Jose', 'Active', 5),
(259, '051611012', 'San Nicolas', 'Active', 5),
(260, '051611013', 'Santa Cruz', 'Active', 5),
(261, '051611014', 'Santo Niño', 'Active', 5),
(262, '051611015', 'Tuaca', 'Active', 5),
(263, '051612001', 'Aguit-it', 'Active', 6),
(264, '051612002', 'Banocboc', 'Active', 6),
(265, '051612003', 'Barangay I (Poblacion)', 'Active', 6),
(266, '051612004', 'Barangay II (Poblacion)', 'Active', 6),
(267, '051612005', 'Barangay III (Poblacion)', 'Active', 6),
(268, '051612006', 'Barangay IV (Poblacion)', 'Active', 6),
(269, '051612007', 'Barangay V (Poblacion)', 'Active', 6),
(270, '051612008', 'Barangay VI (Poblacion)', 'Active', 6),
(271, '051612009', 'Barangay VII (Poblacion)', 'Active', 6),
(272, '051612010', 'Barangay VIII (Poblacion)', 'Active', 6),
(273, '051612011', 'Cagbalogo', 'Active', 6),
(274, '051612012', 'Calangcawan Norte', 'Active', 6),
(275, '051612013', 'Calangcawan Sur', 'Active', 6),
(276, '051612014', 'Mangcawayan', 'Active', 6),
(277, '051612015', 'Manlucugan', 'Active', 6),
(278, '051612016', 'Matango', 'Active', 6),
(279, '051612017', 'Singi', 'Active', 6),
(280, '051612018', 'Sula', 'Active', 6),
(281, '051612019', 'Tamisan', 'Active', 6);

-- --------------------------------------------------------

--
-- Table structure for table `certification`
--

CREATE TABLE `certification` (
  `cert_id` int(50) NOT NULL,
  `verified` varchar(50) NOT NULL,
  `noted` varchar(50) NOT NULL,
  `recom_approval` varchar(50) NOT NULL,
  `recom_date` date NOT NULL DEFAULT current_timestamp(),
  `plotted` varchar(50) NOT NULL,
  `appraised` varchar(50) NOT NULL,
  `appraised_date` date NOT NULL DEFAULT current_timestamp(),
  `approved` varchar(50) NOT NULL,
  `approved_date` date NOT NULL DEFAULT current_timestamp(),
  `idle` tinyint(4) NOT NULL,
  `contested` tinyint(4) NOT NULL,
  `land_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certification`
--

INSERT INTO `certification` (`cert_id`, `verified`, `noted`, `recom_approval`, `recom_date`, `plotted`, `appraised`, `appraised_date`, `approved`, `approved_date`, `idle`, `contested`, `land_id`) VALUES
(17, 'Malapajo, Antonio Menorca', 'Lingon, Nestor Jacolbia', 'Malapajo, Antonio Menorca', '2025-04-28', 'Malapajo, Antonio Menorca', 'Lingon, Nestor Jacolbia', '2025-04-28', 'Lingon, Nestor Jacolbia', '2025-04-28', 0, 0, 55),
(18, 'Malapajo, Antonio Menorca', 'Lingon, Nestor Jacolbia', 'Malapajo, Antonio Menorca', '2025-04-28', 'Malapajo, Antonio Menorca', 'Lingon, Nestor Jacolbia', '2025-04-28', 'Lingon, Nestor Jacolbia', '2025-04-28', 0, 0, 56),
(19, 'Malapajo, Antonio Menorca', 'Lingon, Nestor Jacolbia', 'Malapajo, Antonio Menorca', '2025-08-27', 'Malapajo, Antonio Menorca', 'Lingon, Nestor Jacolbia', '2025-08-27', 'Lingon, Nestor Jacolbia', '2025-08-27', 0, 0, 57);

-- --------------------------------------------------------

--
-- Table structure for table `classification`
--

CREATE TABLE `classification` (
  `c_id` int(11) NOT NULL,
  `c_code` varchar(10) NOT NULL,
  `c_description` varchar(255) NOT NULL,
  `c_uv` decimal(10,2) NOT NULL,
  `c_status` enum('Active','Inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classification`
--

INSERT INTO `classification` (`c_id`, `c_code`, `c_description`, `c_uv`, `c_status`) VALUES
(5, 'CO213', 'Residential', 35.00, 'Active'),
(6, 'AG34', 'Agricultural', 50.00, 'Active'),
(7, 'CO234', 'Commercial', 46.00, 'Inactive'),
(8, 'IN432', 'Industrial', 54.00, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `district`
--

CREATE TABLE `district` (
  `district_id` int(11) NOT NULL,
  `district_code` varchar(10) NOT NULL,
  `description` varchar(100) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `m_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `district`
--

INSERT INTO `district` (`district_id`, `district_code`, `description`, `status`, `m_id`) VALUES
(13, '1', 'District 1', 'Active', 7),
(14, '1', 'District 1', 'Active', 8),
(15, '1', 'District 1', 'Active', 9),
(16, '1', 'District 1', 'Active', 10),
(17, '1', 'District 1', 'Active', 14),
(18, '2', 'District 2', 'Active', 3),
(19, '2', 'District 2', 'Active', 4),
(20, '2', 'District 2', 'Active', 5),
(21, '2', 'District 2', 'Active', 6),
(22, '2', 'District 2', 'Active', 11),
(23, '2', 'San Vicente', 'Active', 12),
(24, '2', 'District 2', 'Active', 13);

-- --------------------------------------------------------

--
-- Table structure for table `faas`
--

CREATE TABLE `faas` (
  `faas_id` int(50) NOT NULL,
  `pro_id` int(50) DEFAULT NULL,
  `rpu_idno` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faas`
--

INSERT INTO `faas` (`faas_id`, `pro_id`, `rpu_idno`) VALUES
(33, 144, 46),
(36, 147, 62),
(42, 156, 63),
(43, 157, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `land`
--

CREATE TABLE `land` (
  `land_id` int(50) NOT NULL,
  `oct_no` int(50) NOT NULL,
  `survey_no` varchar(250) NOT NULL,
  `north` varchar(255) DEFAULT NULL,
  `east` varchar(255) DEFAULT NULL,
  `south` varchar(255) DEFAULT NULL,
  `west` varchar(255) DEFAULT NULL,
  `boun_desc` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `middle_name` varchar(250) NOT NULL,
  `contact_no` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `house_street` varchar(250) NOT NULL,
  `barangay` varchar(250) NOT NULL,
  `district` varchar(250) NOT NULL,
  `municipality` varchar(250) NOT NULL,
  `province` varchar(250) NOT NULL,
  `land_desc` varchar(250) NOT NULL,
  `classification` varchar(250) NOT NULL,
  `sub_class` varchar(250) NOT NULL,
  `area` int(250) NOT NULL,
  `actual_use` varchar(250) NOT NULL,
  `unit_value` decimal(10,2) NOT NULL,
  `market_value` decimal(10,2) NOT NULL,
  `adjust_factor` varchar(250) DEFAULT NULL,
  `adjust_percent` decimal(10,2) NOT NULL,
  `adjust_value` decimal(10,2) NOT NULL,
  `adjust_mv` decimal(10,2) NOT NULL,
  `assess_lvl` decimal(10,2) NOT NULL,
  `assess_value` decimal(10,2) NOT NULL,
  `faas_id` int(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `land`
--

INSERT INTO `land` (`land_id`, `oct_no`, `survey_no`, `north`, `east`, `south`, `west`, `boun_desc`, `last_name`, `first_name`, `middle_name`, `contact_no`, `email`, `house_street`, `barangay`, `district`, `municipality`, `province`, `land_desc`, `classification`, `sub_class`, `area`, `actual_use`, `unit_value`, `market_value`, `adjust_factor`, `adjust_percent`, `adjust_value`, `adjust_mv`, `assess_lvl`, `assess_value`, `faas_id`, `created_at`, `updated_at`) VALUES
(55, 25634, '423', 'Lot 15', 'Barangay Road', 'Rice Field', 'River', 'Bounded by residential and agricultural lands', 'Cruz', 'Juan', 'Dela', '09171234567', 'juan.cruz@example.com', 'Rizal Street', 'Kalamunding', 'District 1', 'Daet', 'Camarines Norte', 'Residential lot with improvements', 'Residential', 'House and Lot', 800, 'Residential', 20.00, 16000.00, 'Depreciation', 17.00, -13280.00, 2720.00, 20.00, 544.00, 33, '2025-08-27 16:41:18', '2025-09-09 14:40:08'),
(56, 1234, '3412', 'Lot 22', 'Barangay Road', 'Vacant Lot', 'Riverbank', 'Commercial property near public market', 'Reyes', 'Maria', 'Lopez', '09181234567', 'maria.reyes@example.com', 'Mabini Street', 'Gahon', 'District 2', 'Daet', 'Camarines Norte', 'Commercial lot', 'Commercial', 'Business Establishment', 432, 'Commercial', 34.00, 14688.00, 'Standard', 0.00, 0.00, 14688.00, 34.00, 4993.92, 33, '2025-08-27 16:41:18', '2025-09-09 14:40:08'),
(57, 3421, '4321', 'Highway', 'Residential Subdivision', 'Barangay Hall', 'Rice Field', 'Prime residential land near highway', 'Santos', 'Pedro', 'Gonzales', '09201234567', 'pedro.santos@example.com', 'Quezon Avenue', 'Bagasbas', 'District 3', 'Daet', 'Camarines Norte', 'Residential land', 'Residential', 'Vacant Lot', 800, 'Residential', 200.00, 160000.00, 'Standard', 0.00, 0.00, 160000.00, 65.00, 104000.00, 36, '2025-08-27 16:41:18', '2025-09-09 14:40:08');

-- --------------------------------------------------------

--
-- Table structure for table `land_use`
--

CREATE TABLE `land_use` (
  `lu_id` int(11) NOT NULL,
  `report_code` varchar(20) NOT NULL,
  `lu_code` varchar(10) NOT NULL,
  `lu_description` varchar(255) NOT NULL,
  `lu_al` decimal(10,2) NOT NULL,
  `lu_status` enum('Active','Inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `land_use`
--

INSERT INTO `land_use` (`lu_id`, `report_code`, `lu_code`, `lu_description`, `lu_al`, `lu_status`) VALUES
(2, 'SC', '43', 'SC', 100.00, 'Active'),
(3, 'SC', 'CS244', '', 54.00, 'Active'),
(4, 'SC', '65', 'Hello World', 0.00, 'Active'),
(5, 'SC', 'C2', 'DFS', 342.00, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `municipality`
--

CREATE TABLE `municipality` (
  `m_id` int(11) NOT NULL,
  `m_code` int(11) NOT NULL,
  `m_description` varchar(50) NOT NULL,
  `m_status` enum('Active','Inactive') NOT NULL,
  `r_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `municipality`
--

INSERT INTO `municipality` (`m_id`, `m_code`, `m_description`, `m_status`, `r_id`) VALUES
(3, 4600, 'Daet', 'Active', 5),
(4, 4601, 'Mercedes', 'Active', 5),
(5, 4602, 'Talisay', 'Active', 5),
(6, 4603, 'Vinzons', 'Active', 5),
(7, 4604, 'Labo', 'Active', 5),
(8, 4605, 'Paracale', 'Active', 5),
(9, 4606, 'Jose Panganiban', 'Active', 5),
(10, 4607, 'Capalonga', 'Active', 5),
(11, 4608, 'Basud', 'Active', 5),
(12, 4609, 'San Vicente', 'Active', 5),
(13, 4610, 'San Lorenzo Ruiz', 'Active', 5),
(14, 4611, 'Santa Elena', 'Active', 5);

-- --------------------------------------------------------

--
-- Table structure for table `owners_tb`
--

CREATE TABLE `owners_tb` (
  `own_id` int(30) NOT NULL,
  `own_fname` varchar(20) NOT NULL,
  `own_mname` varchar(20) NOT NULL,
  `own_surname` varchar(20) NOT NULL,
  `tin_no` int(20) NOT NULL,
  `house_no` varchar(20) NOT NULL,
  `street` varchar(30) NOT NULL,
  `barangay` varchar(30) NOT NULL,
  `district` varchar(20) NOT NULL,
  `city` varchar(20) NOT NULL,
  `province` varchar(20) NOT NULL,
  `own_info` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owners_tb`
--

INSERT INTO `owners_tb` (`own_id`, `own_fname`, `own_mname`, `own_surname`, `tin_no`, `house_no`, `street`, `barangay`, `district`, `city`, `province`, `own_info`) VALUES
(8, 'Renz', 'Balce', 'Dioneda', 0, '5', 'Purok', 'Bulala', 'District 1', 'Santa Elena', 'Camarines Norte', 'Telephone: 09922007821, Fax: , Email: rdioneda4@gmail.com, Website: '),
(9, 'Jonard', 'Balce', 'Canaria', 0, '1', 'Purok 3', 'Alawihao', 'District 2', 'Santa elena', 'Camarines norte', 'Telephone: 09473846382, Fax: , Email: jonard@gmail.com, Website: '),
(10, 'Rommel James', 'Balce', 'Gacho', 0, '3', 'Purok 2', 'Bagacay', 'District 1', 'Labo', 'Camarines Norte', 'Telephone: 09738265234, Fax: , Email: rommel@gmail.com, Website: '),
(11, 'John Lloyd', 'Balce', 'Zuelos', 0, '1', 'Purok 2', 'Kalamunding', 'District 1', 'Labo', 'Camarines Norte', 'Telephone: 09643826422, Fax: , Email: jzuelos@gmail.com, Website: '),
(12, 'Mark', 'Balce', 'Bertillo', 0, '3', 'Purok 1', 'Pasig', 'District 2', 'Daet', 'Camarines norte', 'Telephone: 09634618435, Fax: , Email: markbertillo@gmail.com, Website:');

-- --------------------------------------------------------

--
-- Table structure for table `propertyowner`
--

CREATE TABLE `propertyowner` (
  `pO_id` int(50) NOT NULL,
  `property_id` int(50) NOT NULL,
  `owner_id` int(50) NOT NULL,
  `is_retained` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `propertyowner`
--

INSERT INTO `propertyowner` (`pO_id`, `property_id`, `owner_id`, `is_retained`) VALUES
(62, 144, 12, 1),
(63, 144, 9, 1),
(66, 147, 12, 1),
(72, 157, 12, 1);

-- --------------------------------------------------------

--
-- Table structure for table `province`
--

CREATE TABLE `province` (
  `province_id` int(11) NOT NULL,
  `province_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `province`
--

INSERT INTO `province` (`province_id`, `province_name`) VALUES
(54, 'Camarines Norte');

-- --------------------------------------------------------

--
-- Table structure for table `p_info`
--

CREATE TABLE `p_info` (
  `p_id` int(50) NOT NULL,
  `house_no` int(10) NOT NULL,
  `block_no` int(10) NOT NULL,
  `province` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `district` varchar(30) NOT NULL,
  `barangay` varchar(30) NOT NULL,
  `street` varchar(50) NOT NULL,
  `house_tag_no` int(10) NOT NULL,
  `land_area` int(50) NOT NULL,
  `desc_land` varchar(50) NOT NULL,
  `documents` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `disabled_at` datetime DEFAULT NULL,
  `disabled_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `p_info`
--

INSERT INTO `p_info` (`p_id`, `house_no`, `block_no`, `province`, `city`, `district`, `barangay`, `street`, `house_tag_no`, `land_area`, `desc_land`, `documents`, `created_at`, `updated_at`, `is_active`, `disabled_at`, `disabled_by`) VALUES
(144, 23, 1, 'Camarines Norte', 'Daet', 'District 1', 'Kalamunding', 'Rizal Street', 0, 302, 'Residential lot with affidavit and barangay cleara', 'Affidavit, Barangay Clearance', '2025-08-31 19:01:44', '2025-08-31 19:01:44', 1, NULL, NULL),
(147, 23, 3, 'Camarines Norte', 'Daet', 'District 2', 'Gahon', 'Mabini Street', 0, 453, 'Commercial lot with affidavit and barangay clearan', 'Affidavit, Barangay Clearance', '2025-08-31 19:01:44', '2025-08-31 19:01:44', 1, NULL, NULL),
(156, 42134, 4, 'Camarines Norte', 'Daet', 'District 2', 'Bagasbas', 'Quezon Avenue', 1, 432, 'Agricultural lot with affidavit', 'Affidavit', '2025-09-05 14:01:18', '2025-09-10 14:43:52', 1, NULL, NULL),
(157, 5345, 4, 'Camarines Norte', 'Daet', 'District 2', 'Camambugan', 'San Roque', 0, 5345, 'Residential lot with barangay document', 'Barangay Clearance', '2025-09-05 14:13:20', '2025-09-10 16:00:54', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `region`
--

CREATE TABLE `region` (
  `r_id` int(11) NOT NULL,
  `r_no` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `region`
--

INSERT INTO `region` (`r_id`, `r_no`) VALUES
(5, 'Region V');

-- --------------------------------------------------------

--
-- Table structure for table `rpu_dec`
--

CREATE TABLE `rpu_dec` (
  `dec_id` int(10) NOT NULL,
  `arp_no` int(30) NOT NULL,
  `pro_assess` varchar(250) NOT NULL,
  `pro_date` date NOT NULL,
  `mun_assess` varchar(250) NOT NULL,
  `mun_date` date NOT NULL,
  `td_cancel` int(30) NOT NULL,
  `previous_pin` int(30) NOT NULL,
  `tax_year` date NOT NULL,
  `entered_by` int(30) NOT NULL,
  `entered_year` date NOT NULL,
  `prev_own` varchar(250) NOT NULL,
  `prev_assess` decimal(10,2) NOT NULL,
  `faas_id` int(11) NOT NULL,
  `total_property_value` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rpu_dec`
--

INSERT INTO `rpu_dec` (`dec_id`, `arp_no`, `pro_assess`, `pro_date`, `mun_assess`, `mun_date`, `td_cancel`, `previous_pin`, `tax_year`, `entered_by`, `entered_year`, `prev_own`, `prev_assess`, `faas_id`, `total_property_value`) VALUES
(28, 2342, 'Provincial Assessor Juan Dela Cruz', '2025-08-13', 'Municipal Assessor Maria Reyes', '2025-07-29', 0, 112233, '2025-08-05', 101, '2025-08-20', 'Pedro Santos', 2720.00, 33, 38401.00),
(29, 5345435, 'Provincial Assessor Juan Dela Cruz', '2025-07-15', 'Municipal Assessor Maria Reyes', '2025-07-16', 0, 445566, '2025-08-01', 102, '2025-08-20', 'Josefina Bautista', 5000.00, 36, 264000.00);

-- --------------------------------------------------------

--
-- Table structure for table `rpu_idnum`
--

CREATE TABLE `rpu_idnum` (
  `rpu_id` int(50) NOT NULL,
  `arp` int(50) NOT NULL,
  `pin` int(50) NOT NULL,
  `taxability` varchar(20) NOT NULL,
  `effectivity` varchar(255) NOT NULL,
  `faas_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rpu_idnum`
--

INSERT INTO `rpu_idnum` (`rpu_id`, `arp`, `pin`, `taxability`, `effectivity`, `faas_id`) VALUES
(46, 2147483647, 34564234, 'special', '2024', 33),
(62, 42342, 23423, 'exempt', '42342', 36),
(63, 423234, 423423, 'taxable', '2004', 42);

-- --------------------------------------------------------

--
-- Table structure for table `subclass`
--

CREATE TABLE `subclass` (
  `sc_id` int(11) NOT NULL,
  `sc_code` varchar(20) NOT NULL,
  `sc_description` varchar(255) NOT NULL,
  `sc_uv` decimal(10,2) NOT NULL,
  `sc_status` enum('Active','Inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subclass`
--

INSERT INTO `subclass` (`sc_id`, `sc_code`, `sc_description`, `sc_uv`, `sc_status`) VALUES
(1, 'RA', '32', 0.00, 'Active'),
(2, 'IA', '43', 0.00, 'Active'),
(3, '', '', 0.00, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `transaction_code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `status` enum('Pending','In Progress','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `transaction_code`, `name`, `contact_number`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, '002', 'test 1', NULL, 'test1', 'In Progress', '2025-08-20 14:43:32', '2025-08-20 14:43:32'),
(2, '001', 'test 2', NULL, 'test 2', 'Pending', '2025-08-20 14:45:05', '2025-08-20 14:45:05'),
(3, '003', 'test 3', NULL, 'test 3', 'Completed', '2025-08-20 14:46:42', '2025-08-20 14:46:42'),
(4, '004', 'test 4', NULL, 'test 4', 'Pending', '2025-08-20 14:56:05', '2025-08-20 14:56:05'),
(5, '005', 'name 5', NULL, 'test 5', 'Completed', '2025-08-20 15:00:40', '2025-08-20 15:00:40'),
(6, '1234', 'fsdaf', NULL, 'sdfg', 'In Progress', '2025-08-22 14:24:10', '2025-09-03 15:57:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `birthdate` date DEFAULT NULL,
  `marital_status` enum('Single','Married') NOT NULL,
  `tin` varchar(15) DEFAULT NULL,
  `house_number` varchar(10) DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `barangay` varchar(50) DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `municipality` varchar(50) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `user_type` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `last_name`, `first_name`, `middle_name`, `gender`, `birthdate`, `marital_status`, `tin`, `house_number`, `street`, `barangay`, `district`, `municipality`, `province`, `contact_number`, `email`, `status`, `user_type`) VALUES
(9, 'admin', '$2y$10$uJGu7hoKfqtqSLE2EyV2GetTumt1zHaZOnvIpBeGC5dcwWBr25fc.', 'Admin', 'Admin', 'Admin', 'Male', '2001-11-11', 'Single', '000-123-456-789', '5', 'Purok', '66', '18', '14', 'Camarines Norte', '09123456789', 'johnlloydzuelos@gmail.com', 1, 'admin'),
(10, 'user', '$2y$10$UkziCYNQ.FmUuB3V./d2xelvMkyVO8lvCCd9Yf/CvGMAZJ/jk9LB.', 'User', 'User', 'User', 'Male', '2002-02-02', 'Single', '000-321-654-987', '1', 'Purok 2', '71', '18', '4', 'Camarines Norte', '09876543210', 'user@gmail.com', 1, 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brgy`
--
ALTER TABLE `brgy`
  ADD PRIMARY KEY (`brgy_id`);

--
-- Indexes for table `certification`
--
ALTER TABLE `certification`
  ADD PRIMARY KEY (`cert_id`),
  ADD KEY `land_id` (`land_id`);

--
-- Indexes for table `classification`
--
ALTER TABLE `classification`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `district`
--
ALTER TABLE `district`
  ADD PRIMARY KEY (`district_id`),
  ADD KEY `m_id` (`m_id`);

--
-- Indexes for table `faas`
--
ALTER TABLE `faas`
  ADD PRIMARY KEY (`faas_id`),
  ADD KEY `pro_id` (`pro_id`);

--
-- Indexes for table `land`
--
ALTER TABLE `land`
  ADD PRIMARY KEY (`land_id`),
  ADD KEY `faas_id` (`faas_id`);

--
-- Indexes for table `land_use`
--
ALTER TABLE `land_use`
  ADD PRIMARY KEY (`lu_id`);

--
-- Indexes for table `municipality`
--
ALTER TABLE `municipality`
  ADD PRIMARY KEY (`m_id`),
  ADD KEY `r_id` (`r_id`);

--
-- Indexes for table `owners_tb`
--
ALTER TABLE `owners_tb`
  ADD PRIMARY KEY (`own_id`);

--
-- Indexes for table `propertyowner`
--
ALTER TABLE `propertyowner`
  ADD PRIMARY KEY (`pO_id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `province`
--
ALTER TABLE `province`
  ADD PRIMARY KEY (`province_id`);

--
-- Indexes for table `p_info`
--
ALTER TABLE `p_info`
  ADD PRIMARY KEY (`p_id`);

--
-- Indexes for table `region`
--
ALTER TABLE `region`
  ADD PRIMARY KEY (`r_id`);

--
-- Indexes for table `rpu_dec`
--
ALTER TABLE `rpu_dec`
  ADD PRIMARY KEY (`dec_id`),
  ADD KEY `faas_idrpudec` (`faas_id`);

--
-- Indexes for table `rpu_idnum`
--
ALTER TABLE `rpu_idnum`
  ADD PRIMARY KEY (`rpu_id`),
  ADD KEY `faas_idrpu` (`faas_id`);

--
-- Indexes for table `subclass`
--
ALTER TABLE `subclass`
  ADD PRIMARY KEY (`sc_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brgy`
--
ALTER TABLE `brgy`
  MODIFY `brgy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=282;

--
-- AUTO_INCREMENT for table `certification`
--
ALTER TABLE `certification`
  MODIFY `cert_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `classification`
--
ALTER TABLE `classification`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `district`
--
ALTER TABLE `district`
  MODIFY `district_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `faas`
--
ALTER TABLE `faas`
  MODIFY `faas_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `land`
--
ALTER TABLE `land`
  MODIFY `land_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `land_use`
--
ALTER TABLE `land_use`
  MODIFY `lu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `municipality`
--
ALTER TABLE `municipality`
  MODIFY `m_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `owners_tb`
--
ALTER TABLE `owners_tb`
  MODIFY `own_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `propertyowner`
--
ALTER TABLE `propertyowner`
  MODIFY `pO_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `province`
--
ALTER TABLE `province`
  MODIFY `province_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `p_info`
--
ALTER TABLE `p_info`
  MODIFY `p_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT for table `region`
--
ALTER TABLE `region`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rpu_dec`
--
ALTER TABLE `rpu_dec`
  MODIFY `dec_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `rpu_idnum`
--
ALTER TABLE `rpu_idnum`
  MODIFY `rpu_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `subclass`
--
ALTER TABLE `subclass`
  MODIFY `sc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certification`
--
ALTER TABLE `certification`
  ADD CONSTRAINT `land_id` FOREIGN KEY (`land_id`) REFERENCES `land` (`land_id`) ON DELETE CASCADE;

--
-- Constraints for table `district`
--
ALTER TABLE `district`
  ADD CONSTRAINT `m_id` FOREIGN KEY (`m_id`) REFERENCES `municipality` (`m_id`) ON DELETE CASCADE;

--
-- Constraints for table `faas`
--
ALTER TABLE `faas`
  ADD CONSTRAINT `pro_id` FOREIGN KEY (`pro_id`) REFERENCES `p_info` (`p_id`) ON DELETE CASCADE;

--
-- Constraints for table `land`
--
ALTER TABLE `land`
  ADD CONSTRAINT `faas_id` FOREIGN KEY (`faas_id`) REFERENCES `faas` (`faas_id`) ON DELETE CASCADE;

--
-- Constraints for table `municipality`
--
ALTER TABLE `municipality`
  ADD CONSTRAINT `municipality_ibfk_1` FOREIGN KEY (`r_id`) REFERENCES `region` (`r_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `propertyowner`
--
ALTER TABLE `propertyowner`
  ADD CONSTRAINT `property_id` FOREIGN KEY (`property_id`) REFERENCES `p_info` (`p_id`) ON DELETE CASCADE;

--
-- Constraints for table `rpu_dec`
--
ALTER TABLE `rpu_dec`
  ADD CONSTRAINT `faas_idrpudec` FOREIGN KEY (`faas_id`) REFERENCES `faas` (`faas_id`) ON DELETE CASCADE;

--
-- Constraints for table `rpu_idnum`
--
ALTER TABLE `rpu_idnum`
  ADD CONSTRAINT `faas_idrpu` FOREIGN KEY (`faas_id`) REFERENCES `faas` (`faas_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
