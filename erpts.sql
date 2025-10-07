-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2025 at 06:50 PM
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
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`log_id`, `user_id`, `action`, `log_time`) VALUES
(1, 12, 'Logged out of the system', '2025-09-21 13:05:14'),
(2, 9, 'Logged out of the system', '2025-09-21 13:05:23'),
(3, 9, 'Logged out of the system', '2025-09-21 13:08:00'),
(4, 9, 'Logged in to the system', '2025-09-21 13:08:14'),
(5, 12, 'Created new user: test', '2025-09-21 13:17:28'),
(6, 9, 'Created new user: test2', '2025-09-21 13:20:15'),
(7, 9, 'Updated user ID: 13', '2025-09-21 13:23:22'),
(8, 9, 'Logged in to the system', '2025-09-21 13:34:52'),
(9, 9, 'Added Municipality: Eefgsdgehjhqa', '2025-09-21 13:41:58'),
(10, 9, 'Added Barangay: 312312', '2025-09-21 13:45:24'),
(11, 9, 'Logged in to the system', '2025-09-21 15:19:01'),
(12, 9, 'Logged in to the system', '2025-09-21 17:25:56'),
(13, 9, 'Logged in to the system', '2025-09-22 16:20:38'),
(14, 9, 'Logged out of the system', '2025-09-23 15:02:53'),
(15, 9, 'Logged in to the system', '2025-09-23 15:03:25'),
(16, 9, 'Logged in to the system', '2025-09-24 00:50:10'),
(17, 9, 'Logged in to the system', '2025-10-01 13:01:12'),
(18, 9, 'Logged out of the system', '2025-10-01 13:09:16'),
(19, 9, 'Logged in to the system', '2025-10-01 13:27:00'),
(20, 9, 'Logged in to the system', '2025-10-02 08:52:13'),
(21, 9, 'Logged in to the system', '2025-10-02 09:24:29'),
(22, 9, 'Logged in to the system', '2025-10-02 10:15:40'),
(23, 9, 'Logged in to the system', '2025-10-02 10:24:32'),
(24, 9, 'Logged in to the system', '2025-10-03 12:45:32'),
(25, 9, 'Logged in to the system', '2025-10-05 14:01:05'),
(26, 9, 'Logged in to the system', '2025-10-07 07:12:48'),
(27, 9, 'Logged in to the system', '2025-10-07 13:52:04');

-- --------------------------------------------------------

--
-- Table structure for table `admin_certification`
--

CREATE TABLE `admin_certification` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `position` varchar(150) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `role` enum('none','provincial_assessor','verifier') NOT NULL DEFAULT 'none',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_certification`
--

INSERT INTO `admin_certification` (`id`, `name`, `description`, `position`, `status`, `role`, `created_at`, `updated_at`) VALUES
(6, 'Mark Bertillo', NULL, 'Provincial Assessor', 'active', 'provincial_assessor', '2025-10-07 15:39:00', '2025-10-07 23:30:18'),
(8, 'Jonard Canaria', NULL, 'Janitor', 'active', 'verifier', '2025-10-07 16:38:54', '2025-10-07 23:30:18'),
(9, 'Ma. Salome Bertillo', NULL, 'Assistant Assessor', 'active', 'none', '2025-10-07 16:42:41', '2025-10-07 16:42:41');

-- --------------------------------------------------------

--
-- Table structure for table `brgy`
--

CREATE TABLE `brgy` (
  `brgy_id` int(11) NOT NULL,
  `brgy_code` varchar(3) NOT NULL,
  `brgy_name` varchar(100) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `m_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brgy`
--

INSERT INTO `brgy` (`brgy_id`, `brgy_code`, `brgy_name`, `status`, `m_id`) VALUES
(1, '002', 'Alawihao', 'Active', 1),
(2, '009', 'Awitan', 'Active', 1),
(3, '015', 'Bagasbas', 'Active', 1),
(4, '025', 'Barangay I', 'Active', 1),
(5, '027', 'Barangay II', 'Active', 1),
(6, '030', 'Barangay III', 'Active', 1),
(7, '033', 'Barangay IV', 'Active', 1),
(8, '035', 'Barangay V', 'Active', 1),
(9, '036', 'Barangay VI', 'Active', 1),
(10, '038', 'Barangay VII', 'Active', 1),
(11, '040', 'Barangay VIII', 'Active', 1),
(12, '047', 'Bibirao', 'Active', 1),
(13, '051', 'Borabod', 'Active', 1),
(14, '067', 'Calasgasan', 'Active', 1),
(15, '071', 'Camambugan', 'Active', 1),
(16, '080', 'Cobangbang', 'Active', 1),
(17, '094', 'Dogongan', 'Active', 1),
(18, '102', 'Gahonon', 'Active', 1),
(19, '103', 'Gubat', 'Active', 1),
(20, '122', 'Lag-on', 'Active', 1),
(21, '141', 'Magang', 'Active', 1),
(22, '152', 'Mambalite', 'Active', 1),
(23, '158', 'Mancruz', 'Active', 1),
(24, '194', 'Pamorangon', 'Active', 1),
(25, '228', 'San Isidro', 'Active', 1),
(26, '007', 'Apuao', 'Active', 2),
(27, '024', 'Barangay I', 'Active', 2),
(28, '028', 'Barangay II', 'Active', 2),
(29, '031', 'Barangay III', 'Active', 2),
(30, '032', 'Barangay IV', 'Active', 2),
(31, '034', 'Barangay V', 'Active', 2),
(32, '037', 'Barangay VI', 'Active', 2),
(33, '039', 'Barangay VII', 'Active', 2),
(34, '074', 'Caringo', 'Active', 2),
(35, '077', 'Catandunganon', 'Active', 2),
(36, '079', 'Cayucyucan', 'Active', 2),
(37, '081', 'Colasi', 'Active', 2),
(38, '093', 'Del Rosario', 'Active', 2),
(39, '100', 'Gaboc', 'Active', 2),
(40, '111', 'Hamoraon', 'Active', 2),
(41, '113', 'Hinipaan', 'Active', 2),
(42, '123', 'Lalawigan', 'Active', 2),
(43, '128', 'Lanot', 'Active', 2),
(44, '153', 'Mambungalon', 'Active', 2),
(45, '164', 'Manguisoc', 'Active', 2),
(46, '171', 'Masalongsalong', 'Active', 2),
(47, '177', 'Matoogtoog', 'Active', 2),
(48, '193', 'Pambuhan', 'Active', 2),
(49, '216', 'Quinapaguian', 'Active', 2),
(50, '244', 'San Roque', 'Active', 2),
(51, '273', 'Tarum', 'Active', 2),
(52, '048', 'Binanuaan', 'Active', 3),
(53, '054', 'Caawigan', 'Active', 3),
(54, '060', 'Cahabaan', 'Active', 3),
(55, '069', 'Calintaan', 'Active', 3),
(56, '091', 'Del Carmen', 'Active', 3),
(57, '101', 'Gabon', 'Active', 3),
(58, '117', 'Itomang', 'Active', 3),
(59, '208', 'Poblacion', 'Active', 3),
(60, '226', 'San Francisco', 'Active', 3),
(61, '231', 'San Isidro', 'Active', 3),
(62, '235', 'San Jose', 'Active', 3),
(63, '238', 'San Nicolas', 'Active', 3),
(64, '249', 'Santa Cruz', 'Active', 3),
(65, '252', 'Santa Elena', 'Active', 3),
(66, '258', 'Santo Niño', 'Active', 3),
(67, '001', 'Aguit-It', 'Active', 4),
(68, '023', 'Banocboc', 'Active', 4),
(69, '059', 'Cagbalogo', 'Active', 4),
(70, '065', 'Calangcawan Norte', 'Active', 4),
(71, '066', 'Calangcawan Sur', 'Active', 4),
(72, '104', 'Guinacutan', 'Active', 4),
(73, '162', 'Mangcayo', 'Active', 4),
(74, '166', 'Manlucugan', 'Active', 4),
(75, '172', 'Matacong', 'Active', 4),
(76, '184', 'Napilihan', 'Active', 4),
(77, '201', 'Pinagtigasan', 'Active', 4),
(78, '257', 'Santo Domingo', 'Active', 4),
(79, '259', 'Singuay', 'Active', 4),
(80, '263', 'Tabas', 'Active', 4),
(81, '268', 'Talisay', 'Active', 4),
(82, '161', 'Mangcawayan', 'Active', 4),
(83, '247', 'San Vicente', 'Active', 4),
(84, '026', 'Barangay I (Poblacion)', 'Active', 4),
(85, '029', 'Barangay II (Poblacion)', 'Active', 4),
(86, '004', 'Anahaw', 'Active', 5),
(87, '005', 'Anameam', 'Active', 5),
(88, '011', 'Awitan', 'Active', 5),
(89, '012', 'Baay', 'Active', 5),
(90, '014', 'Bagacay', 'Active', 5),
(91, '017', 'Bagong Silang I', 'Active', 5),
(92, '018', 'Bagong Silang II', 'Active', 5),
(93, '019', 'Bagong Silang III', 'Active', 5),
(94, '022', 'Bakiad', 'Active', 5),
(95, '043', 'Bautista', 'Active', 5),
(96, '044', 'Bayabas', 'Active', 5),
(97, '045', 'Bayan-bayan', 'Active', 5),
(98, '046', 'Benit', 'Active', 5),
(99, '053', 'Bulhao', 'Active', 5),
(100, '057', 'Cabatuhan', 'Active', 5),
(101, '058', 'Cabusay', 'Active', 5),
(102, '063', 'Calabasa', 'Active', 5),
(103, '072', 'Canapawan', 'Active', 5),
(104, '085', 'Daguit', 'Active', 5),
(105, '087', 'Dalas', 'Active', 5),
(106, '096', 'Dumagmang', 'Active', 5),
(107, '097', 'Exciban', 'Active', 5),
(108, '099', 'Fundado', 'Active', 5),
(109, '105', 'Guinacutan', 'Active', 5),
(110, '107', 'Guisican', 'Active', 5),
(111, '109', 'Gumamela', 'Active', 5),
(112, '114', 'Iberica', 'Active', 5),
(113, '120', 'Kalamunding', 'Active', 5),
(114, '132', 'Lugui', 'Active', 5),
(115, '135', 'Mabilo I', 'Active', 5),
(116, '136', 'Mabilo II', 'Active', 5),
(117, '138', 'Macogon', 'Active', 5),
(118, '143', 'Mahawan-hawan', 'Active', 5),
(119, '147', 'Malangcao-Basud', 'Active', 5),
(120, '148', 'Malasugui', 'Active', 5),
(121, '149', 'Malatap', 'Active', 5),
(122, '150', 'Malaya', 'Active', 5),
(123, '151', 'Malibago', 'Active', 5),
(124, '169', 'Maot', 'Active', 5),
(125, '170', 'Masalong', 'Active', 5),
(126, '174', 'Matanlang', 'Active', 5),
(127, '183', 'Napaod', 'Active', 5),
(128, '189', 'Pag-asa', 'Active', 5),
(129, '195', 'Pangpang', 'Active', 5),
(130, '203', 'Pinya', 'Active', 5),
(131, '223', 'San Antonio', 'Active', 5),
(132, '225', 'San Francisco', 'Active', 5),
(133, '248', 'Santa Cruz', 'Active', 5),
(134, '261', 'Submakin', 'Active', 5),
(135, '269', 'Talobatib', 'Active', 5),
(136, '275', 'Tigbinan', 'Active', 5),
(137, '278', 'Tulay na Lupa', 'Active', 5),
(138, '010', 'Awitan', 'Active', 6),
(139, '020', 'Bagumbayan', 'Active', 6),
(140, '021', 'Bakal', 'Active', 6),
(141, '042', 'Batobalani', 'Active', 6),
(142, '064', 'Calaburnay', 'Active', 6),
(143, '073', 'Capacuan', 'Active', 6),
(144, '075', 'Casalugan', 'Active', 6),
(145, '083', 'Dagang', 'Active', 6),
(146, '088', 'Dalnac', 'Active', 6),
(147, '089', 'Dancalan', 'Active', 6),
(148, '110', 'Gumaus', 'Active', 6),
(149, '121', 'Labnig', 'Active', 6),
(150, '139', 'Macolabo Island', 'Active', 6),
(151, '145', 'Malacbang', 'Active', 6),
(152, '146', 'Malaguit', 'Active', 6),
(153, '155', 'Mampungo', 'Active', 6),
(154, '163', 'Mangkasay', 'Active', 6),
(155, '179', 'Maybato', 'Active', 6),
(156, '192', 'Palanas', 'Active', 6),
(157, '199', 'Pinagbirayan Malaki', 'Active', 6),
(158, '200', 'Pinagbirayan Munti', 'Active', 6),
(159, '213', 'Poblacion Norte', 'Active', 6),
(160, '214', 'Poblacion Sur', 'Active', 6),
(161, '264', 'Tabas', 'Active', 6),
(162, '270', 'Talusan', 'Active', 6),
(163, '274', 'Tawig', 'Active', 6),
(164, '277', 'Tugos', 'Active', 6),
(165, '016', 'Bagong Bayan', 'Active', 7),
(166, '068', 'Calero', 'Active', 7),
(167, '086', 'Dahican', 'Active', 7),
(168, '090', 'Dayhagan', 'Active', 7),
(169, '129', 'Larap', 'Active', 7),
(170, '133', 'Luklukan Norte', 'Active', 7),
(171, '134', 'Luklukan Sur', 'Active', 7),
(172, '181', 'Motherlode', 'Active', 7),
(173, '182', 'Nakalaya', 'Active', 7),
(174, '185', 'North Poblacion', 'Active', 7),
(175, '188', 'Osmeña', 'Active', 7),
(176, '190', 'Pag-asa', 'Active', 7),
(177, '196', 'Parang', 'Active', 7),
(178, '205', 'Plaridel', 'Active', 7),
(179, '219', 'Salvacion', 'Active', 7),
(180, '227', 'San Isidro', 'Active', 7),
(181, '234', 'San Jose', 'Active', 7),
(182, '237', 'San Martin', 'Active', 7),
(183, '241', 'San Pedro', 'Active', 7),
(184, '242', 'San Rafael', 'Active', 7),
(185, '250', 'Santa Cruz', 'Active', 7),
(186, '251', 'Santa Elena', 'Active', 7),
(187, '254', 'Santa Milagrosa', 'Active', 7),
(188, '255', 'Santa Rosa Norte', 'Active', 7),
(189, '256', 'Santa Rosa Sur', 'Active', 7),
(190, '260', 'South Poblacion', 'Active', 7),
(191, '271', 'Tamisan', 'Active', 7),
(192, '003', 'Alayao', 'Active', 8),
(193, '050', 'Binawangan', 'Active', 8),
(194, '061', 'Calabaca', 'Active', 8),
(195, '070', 'Camagsaan', 'Active', 8),
(196, '076', 'Catabaguangan', 'Active', 8),
(197, '078', 'Catioan', 'Active', 8),
(198, '092', 'Del Pilar', 'Active', 8),
(199, '116', 'Itok', 'Active', 8),
(200, '131', 'Lucbanan', 'Active', 8),
(201, '137', 'Mabini', 'Active', 8),
(202, '140', 'Mactang', 'Active', 8),
(203, '142', 'Magsaysay', 'Active', 8),
(204, '175', 'Mataque', 'Active', 8),
(205, '186', 'Old Camp', 'Active', 8),
(206, '207', 'Poblacion', 'Active', 8),
(207, '221', 'San Antonio', 'Active', 8),
(208, '230', 'San Isidro', 'Active', 8),
(209, '245', 'San Roque', 'Active', 8),
(210, '272', 'Tanawan', 'Active', 8),
(211, '279', 'Ubang', 'Active', 8),
(212, '280', 'Villa Aurora', 'Active', 8),
(213, '281', 'Villa Belen', 'Active', 8),
(214, '006', 'Angas', 'Active', 9),
(215, '013', 'Bactas', 'Active', 9),
(216, '049', 'Binatagan', 'Active', 9),
(217, '055', 'Caayunan', 'Active', 9),
(218, '106', 'Guinatungan', 'Active', 9),
(219, '112', 'Hinampacan', 'Active', 9),
(220, '124', 'Langa', 'Active', 9),
(221, '126', 'Laniton', 'Active', 9),
(222, '130', 'Lidong', 'Active', 9),
(223, '154', 'Mampili', 'Active', 9),
(224, '159', 'Mandazo', 'Active', 9),
(225, '160', 'Mangcamagong', 'Active', 9),
(226, '167', 'Manmuntay', 'Active', 9),
(227, '168', 'Mantugawe', 'Active', 9),
(228, '176', 'Matnog', 'Active', 9),
(229, '180', 'Mocong', 'Active', 9),
(230, '187', 'Oliva', 'Active', 9),
(231, '191', 'Pagsangahan', 'Active', 9),
(232, '202', 'Pinagwarasan', 'Active', 9),
(233, '206', 'Plaridel', 'Active', 9),
(234, '209', 'Poblacion 1', 'Active', 9),
(235, '210', 'Poblacion 2', 'Active', 9),
(236, '224', 'San Felipe', 'Active', 9),
(237, '233', 'San Jose', 'Active', 9),
(238, '239', 'San Pascual', 'Active', 9),
(239, '262', 'Taba-taba', 'Active', 9),
(240, '266', 'Tacad', 'Active', 9),
(241, '267', 'Taisan', 'Active', 9),
(242, '276', 'Tuaca', 'Active', 9),
(243, '008', 'Asdum', 'Active', 10),
(244, '056', 'Cabanbanan', 'Active', 10),
(245, '062', 'Calabagas', 'Active', 10),
(246, '098', 'Fabrica', 'Active', 10),
(247, '115', 'Iraya Sur', 'Active', 10),
(248, '157', 'Man-ogob', 'Active', 10),
(249, '211', 'Poblacion District I', 'Active', 10),
(250, '212', 'Poblacion District II', 'Active', 10),
(251, '232', 'San Jose', 'Active', 10),
(252, '082', 'Daculang Bolo', 'Active', 11),
(253, '084', 'Dagotdotan', 'Active', 11),
(254, '125', 'Langga', 'Active', 11),
(255, '127', 'Laniton', 'Active', 11),
(256, '144', 'Maisog', 'Active', 11),
(257, '156', 'Mampurog', 'Active', 11),
(258, '165', 'Manlimonsito', 'Active', 11),
(259, '173', 'Matacong', 'Active', 11),
(260, '218', 'Salvacion', 'Active', 11),
(261, '222', 'San Antonio', 'Active', 11),
(262, '229', 'San Isidro', 'Active', 11),
(263, '243', 'San Ramon', 'Active', 11),
(264, '041', 'Basiad', 'Active', 12),
(265, '052', 'Bulala', 'Active', 12),
(266, '095', 'Don Tomas', 'Active', 12),
(267, '108', 'Guitol', 'Active', 12),
(268, '118', 'Kabuluan', 'Active', 12),
(269, '119', 'Kagtalaba', 'Active', 12),
(270, '178', 'Maulawin', 'Active', 12),
(271, '197', 'Patag Ibaba', 'Active', 12),
(272, '198', 'Patag Ilaya', 'Active', 12),
(273, '204', 'Plaridel', 'Active', 12),
(274, '215', 'Polungguitguit', 'Active', 12),
(275, '217', 'Rizal', 'Active', 12),
(276, '220', 'Salvacion', 'Active', 12),
(277, '236', 'San Lorenzo', 'Active', 12),
(278, '240', 'San Pedro', 'Active', 12),
(279, '246', 'San Vicente', 'Active', 12),
(280, '253', 'Santa Elena (Poblacion)', 'Active', 12),
(281, '265', 'Tabugon', 'Active', 12),
(282, '282', 'Villa San Isidro', 'Active', 12);

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
(1, 'Malapajo, Antonio Menorca', 'Lingon, Nestor Jacolbia', 'Malapajo, Antonio Menorca', '2025-09-20', 'Malapajo, Antonio Menorca', 'Lingon, Nestor Jacolbia', '2025-09-20', 'Lingon, Nestor Jacolbia', '2025-09-20', 0, 0, 55),
(2, 'Malapajo, Antonio Menorca', 'Lingon, Nestor Jacolbia', 'Malapajo, Antonio Menorca', '2025-04-28', 'Malapajo, Antonio Menorca', 'Lingon, Nestor Jacolbia', '2025-04-28', 'Lingon, Nestor Jacolbia', '2025-04-28', 0, 0, 56),
(3, 'Malapajo, Antonio Menorca', 'Lingon, Nestor Jacolbia', 'Malapajo, Antonio Menorca', '2025-08-27', 'Malapajo, Antonio Menorca', 'Lingon, Nestor Jacolbia', '2025-08-27', 'Lingon, Nestor Jacolbia', '2025-08-27', 0, 0, 57);

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
(8, 'IN432', 'Industrial', 54.00, 'Active'),
(9, 'MI101', 'Mineral', 72.00, 'Active'),
(10, 'TI102', 'Timberland', 65.00, 'Active'),
(11, 'SP103', 'Special', 48.00, 'Active');

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
(1, '4600', 'District 2', 'Active', 1),
(2, '4601', 'District 2', 'Active', 2),
(3, '4602', 'District 2', 'Active', 3),
(4, '4603', 'District 2', 'Active', 4),
(5, '4604', 'District 1', 'Active', 5),
(6, '4605', 'District 1', 'Active', 6),
(7, '4606', 'District 1', 'Active', 7),
(8, '4607', 'District 1', 'Active', 8),
(9, '4608', 'District 1', 'Active', 9),
(10, '4609', 'District 2', 'Active', 10),
(11, '4610', 'District 2', 'Active', 11),
(12, '4611', 'District 1', 'Active', 12);

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
  `oct_no` varchar(50) NOT NULL,
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
(55, '12345', '42322', 'Lot 15', 'Barangay Road', 'Rice Field', 'River', 'Bounded by residential and agricultural lands', 'Cruz', 'Juan', 'Dela', '09345678901', 'juan.cruz@example.com', 'Rizal Street', 'Kalamunding', 'District 1', 'Daet', 'Camarines Norte', 'Residential lot with improvements', 'Agricultural', '', 23, 'SC', 20.00, 460.00, 'Depreciation', 17.00, -381.80, 78.20, 20.00, 15.64, 33, '2025-08-27 16:41:18', '2025-09-20 08:44:08'),
(56, '1234', '3412', 'Lot 22', 'Barangay Road', 'Vacant Lot', 'Riverbank', 'Commercial property near public market', 'Reyes', 'Maria', 'Lopez', '09181234567', 'maria.reyes@example.com', 'Mabini Street', 'Gahon', 'District 2', 'Daet', 'Camarines Norte', 'Commercial lot', 'Commercial', 'Business Establishment', 432, 'Commercial', 34.00, 14688.00, 'Standard', 0.00, 0.00, 14688.00, 34.00, 4993.92, 33, '2025-08-27 16:41:18', '2025-09-09 14:40:08'),
(57, '3421', '4321', 'Highway', 'Residential Subdivision', 'Barangay Hall', 'Rice Field', 'Prime residential land near highway', 'Santos', 'Pedro', 'Gonzales', '09201234567', 'pedro.santos@example.com', 'Quezon Avenue', 'Bagasbas', 'District 3', 'Daet', 'Camarines Norte', 'Residential land', 'Residential', 'Vacant Lot', 800, 'Residential', 200.00, 160000.00, 'Standard', 0.00, 0.00, 160000.00, 65.00, 104000.00, 36, '2025-08-27 16:41:18', '2025-09-09 14:40:08');

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
(5, 'SC', 'C2', 'DFS', 101.00, 'Active'),
(6, 'RES', 'R1', 'Residential Zone – Single Family', 80.00, 'Active'),
(7, 'RES', 'R2', 'Residential Zone – Multi Family', 90.00, 'Active'),
(8, 'RES', 'R3', 'Residential Zone – Condominium', 100.00, 'Active'),
(9, 'COM', 'C1', 'Commercial Zone – Retail', 120.00, 'Active'),
(10, 'COM', 'C2', 'Commercial Zone – Office', 130.00, 'Active'),
(11, 'IND', 'I1', 'Industrial Zone – Light Industry', 150.00, 'Active'),
(12, 'IND', 'I2', 'Industrial Zone – Heavy Industry', 180.00, 'Active'),
(13, 'AGR', 'A1', 'Agricultural Zone – Rice Land', 50.00, 'Active'),
(14, 'AGR', 'A2', 'Agricultural Zone – Coconut Plantation', 45.00, 'Active'),
(15, 'MIX', 'MX1', 'Mixed-Use Development Area', 110.00, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `municipality`
--

CREATE TABLE `municipality` (
  `m_id` int(11) NOT NULL,
  `m_code` varchar(11) NOT NULL,
  `m_description` varchar(50) NOT NULL,
  `m_status` enum('Active','Inactive') NOT NULL,
  `r_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `municipality`
--

INSERT INTO `municipality` (`m_id`, `m_code`, `m_description`, `m_status`, `r_id`) VALUES
(1, '03', 'Daet', 'Active', 5),
(2, '06', 'Mercedes', 'Active', 5),
(3, '11', 'Talisay', 'Active', 5),
(4, '12', 'Vinzons', 'Active', 5),
(5, '05', 'Labo', 'Active', 5),
(6, '07', 'Paracale', 'Active', 5),
(7, '04', 'Jose Panganiban', 'Active', 5),
(8, '02', 'Capalonga', 'Active', 5),
(9, '01', 'Basud', 'Active', 5),
(10, '09', 'San Vicente', 'Active', 5),
(11, '08', 'San Lorenzo Ruiz', 'Active', 5),
(12, '10', 'Santa Elena', 'Active', 5);

-- --------------------------------------------------------

--
-- Table structure for table `owners_tb`
--

CREATE TABLE `owners_tb` (
  `own_id` int(30) NOT NULL,
  `own_fname` varchar(20) NOT NULL,
  `own_mname` varchar(20) NOT NULL,
  `own_surname` varchar(20) NOT NULL,
  `owner_type` enum('individual','company') DEFAULT 'individual',
  `company_name` varchar(100) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at_owner` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at_owner` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_birth` date DEFAULT NULL,
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

INSERT INTO `owners_tb` (`own_id`, `own_fname`, `own_mname`, `own_surname`, `owner_type`, `company_name`, `created_by`, `updated_by`, `created_at_owner`, `updated_at_owner`, `date_birth`, `tin_no`, `house_no`, `street`, `barangay`, `district`, `city`, `province`, `own_info`) VALUES
(1, 'Juan', 'Santos', 'Dela Cruz', 'individual', NULL, NULL, NULL, '2025-09-24 01:03:40', '2025-09-24 01:03:40', '1985-05-10', 123456789, '12', 'Rizal St.', 'Barangay 1', 'District 1', 'Daet', 'Camarines Norte', 'Telephone: 09123456781, Email: juan.dc@example.com'),
(2, 'Maria', 'Lopez', 'Reyes', 'individual', NULL, NULL, NULL, '2025-09-24 01:03:40', '2025-09-24 01:03:40', '1990-08-15', 987654321, '34', 'Bonifacio St.', 'Barangay 2', 'District 1', 'Labo', 'Camarines Norte', 'Telephone: 09123456782, Email: maria.lr@example.com'),
(3, 'Pedro', 'Manuel', 'Santos', 'individual', NULL, NULL, NULL, '2025-09-24 01:03:40', '2025-09-24 01:03:40', '1978-03-22', 456789123, '56', 'Mabini St.', 'Barangay 3', 'District 2', 'Daet', 'Camarines Norte', 'Telephone: 09123456783, Email: pedro.ms@example.com'),
(4, 'Jose', 'Antonio', 'Cruz', 'individual', NULL, NULL, NULL, '2025-09-24 01:03:40', '2025-09-24 01:03:40', '1982-07-30', 741852963, '78', 'Quezon Ave.', 'Barangay 4', 'District 2', 'Labo', 'Camarines Norte', 'Telephone: 09123456784, Email: jose.cr@example.com'),
(5, 'Ana', 'Garcia', 'Torres', 'individual', NULL, NULL, NULL, '2025-09-24 01:03:40', '2025-09-24 01:03:40', '1995-11-02', 852369741, '90', 'Del Pilar St.', 'Barangay 5', 'District 3', 'Daet', 'Camarines Norte', 'Telephone: 09123456785, Email: ana.gt@example.com'),
(6, 'Luis', 'Domingo', 'Fernandez', 'individual', NULL, NULL, NULL, '2025-09-24 01:03:40', '2025-09-24 01:03:40', '1987-01-12', 963852741, '21', 'San Vicente', 'Barangay 6', 'District 1', 'Daet', 'Camarines Norte', 'Telephone: 09123456786, Email: luis.df@example.com'),
(7, 'Carmen', 'Santos', 'Gonzales', 'individual', NULL, NULL, NULL, '2025-09-24 01:03:40', '2025-09-24 01:03:40', '1992-04-25', 147258369, '32', 'Libertad St.', 'Barangay 7', 'District 2', 'Labo', 'Camarines Norte', 'Telephone: 09123456787, Email: carmen.sg@example.com'),
(8, 'Miguel', 'Reyes', 'Del Rosario', 'individual', NULL, NULL, NULL, '2025-09-24 01:03:40', '2025-09-24 01:03:40', '1980-09-18', 369258147, '43', 'San Roque', 'Barangay 8', 'District 1', 'Daet', 'Camarines Norte', 'Telephone: 09123456788, Email: miguel.dr@example.com'),
(9, 'Elena', 'Mendoza', 'Lopez', 'individual', NULL, NULL, NULL, '2025-09-24 01:03:40', '2025-09-24 01:03:40', '1993-02-14', 258147369, '54', 'Purok 1', 'Barangay 9', 'District 3', 'Labo', 'Camarines Norte', 'Telephone: 09123456789, Email: elena.ml@example.com'),
(10, 'Francisco', 'Cruz', 'Santos', 'individual', NULL, NULL, NULL, '2025-09-24 01:03:40', '2025-09-24 01:03:40', '1975-06-08', 321654987, '65', 'Purok 2', 'Barangay 10', 'District 2', 'Daet', 'Camarines Norte', 'Telephone: 09123456790, Email: francisco.cs@example.com'),
(11, 'Isabel', 'Delos', 'Ramos', 'individual', NULL, NULL, NULL, '2025-09-24 01:03:40', '2025-09-24 01:03:40', '1991-10-19', 741963852, '76', 'Burgos St.', 'Barangay 11', 'District 1', 'Labo', 'Camarines Norte', 'Telephone: 09123456791, Email: isabel.dr@example.com'),
(12, 'Ramon', 'Torres', 'Mendoza', 'individual', NULL, NULL, NULL, '2025-09-24 01:03:40', '2025-09-24 01:03:40', '1983-12-05', 159753486, '87', 'San Isidro', 'Barangay 12', 'District 3', 'Daet', 'Camarines Norte', 'Telephone: 09123456792, Email: ramon.tm@example.com'),
(13, 'Teresa', 'Lopez', 'Garcia', 'individual', NULL, NULL, NULL, '2025-09-24 01:03:40', '2025-09-24 01:03:40', '1996-07-23', 951357258, '98', 'Quezon St.', 'Barangay 13', 'District 2', 'Labo', 'Camarines Norte', 'Telephone: 09123456793, Email: teresa.lg@example.com'),
(14, 'Ricardo', 'Santos', 'Cruz', 'individual', NULL, NULL, NULL, '2025-09-24 01:03:40', '2025-09-24 01:03:40', '1979-04-11', 753951456, '11', 'Rizal Ext.', 'Barangay 14', 'District 1', 'Daet', 'Camarines Norte', 'Telephone: 09123456794, Email: ricardo.sc@example.com'),
(15, 'Lucia', 'Fernandez', 'Reyes', 'individual', NULL, NULL, NULL, '2025-09-24 01:03:40', '2025-09-24 01:03:40', '1988-09-29', 852147963, '22', 'Mabini Ext.', 'Barangay 15', 'District 3', 'Labo', 'Camarines Norte', 'Telephone: 09123456795, Email: lucia.fr@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `owner_audit_log`
--

CREATE TABLE `owner_audit_log` (
  `log_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tax-dec_id` int(11) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owner_audit_log`
--

INSERT INTO `owner_audit_log` (`log_id`, `action`, `owner_id`, `property_id`, `user_id`, `tax-dec_id`, `details`, `created_at`) VALUES
(1, 'Removed', 9, 144, 9, 2, 'Removed: Renz Balce Dioneda (Purok, 95, 5, Camarines Norte) from property 144', '2025-09-23 22:34:55'),
(5, 'Removed', 14, 144, 9, 2, 'Removed: Maria Luisa Santos (Quezon Avenue, Lag-on, Daet, Camarines Norte) from property 144', '2025-09-23 22:35:46'),
(9, 'Removed', 12, 144, 9, 2, 'Removed: Mark Odi Bertillo (Purok 1, Pasig, Daet, Camarines norte) from property 144', '2025-09-23 23:10:56'),
(13, 'Removed', 8, 144, 9, 2, 'Removed: Renz Balce Dioneda (Purok, 201, 8, Camarines Norte) from property 144', '2025-09-23 23:33:55'),
(16, 'Snapshot', 12, 144, 9, 2, '{\"dec_id\":2,\"arp_no\":0,\"pro_assess\":\"\",\"pro_date\":\"0000-00-00\",\"mun_assess\":\"\",\"mun_date\":\"0000-00-00\",\"td_cancel\":0,\"previous_pin\":0,\"tax_year\":\"0000-00-00\",\"entered_by\":0,\"entered_year\":\"0000-00-00\",\"prev_own\":\"\",\"prev_assess\":\"0.00\",\"faas_id\":33,\"total_property_value\":\"20157.00\"}', '2025-09-23 23:33:55'),
(18, 'Added', 10, 144, 9, 2, 'Added: Rommel James Balce Gacho (Purok 2, Bagacay, Labo, Camarines Norte) to property 144', '2025-09-24 00:51:05'),
(21, 'Removed', 10, 144, 9, 2, 'Removed: Rommel James Balce Gacho (Purok 2, Bagacay, Labo, Camarines Norte) from property 144', '2025-09-24 00:53:05'),
(25, 'Removed', 11, 144, 9, 1, 'Removed: Isabel Delos Ramos (Burgos St., Barangay 11, Labo, Camarines Norte) from property 144', '2025-10-02 15:18:15'),
(26, 'Removed', 9, 144, 9, 1, 'Removed: Elena Mendoza Lopez (Purok 1, Barangay 9, Labo, Camarines Norte) from property 144', '2025-10-02 15:21:57');

-- --------------------------------------------------------

--
-- Table structure for table `propertyowner`
--

CREATE TABLE `propertyowner` (
  `pO_id` int(50) NOT NULL,
  `property_id` int(50) NOT NULL,
  `owner_id` int(50) NOT NULL,
  `is_retained` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `propertyowner`
--

INSERT INTO `propertyowner` (`pO_id`, `property_id`, `owner_id`, `is_retained`, `created_at`, `created_by`) VALUES
(62, 144, 12, 1, '2025-09-21 13:37:29', NULL),
(63, 144, 9, 0, '2025-09-21 13:37:29', NULL),
(66, 147, 12, 1, '2025-09-21 13:37:29', NULL),
(72, 157, 12, 1, '2025-09-21 13:37:29', NULL),
(74, 144, 14, 0, '2025-09-23 22:34:55', 9),
(75, 144, 8, 0, '2025-09-23 22:35:46', 9),
(76, 144, 11, 0, '2025-09-23 23:10:56', 9),
(77, 144, 12, 0, '2025-09-23 23:33:55', 9),
(78, 144, 10, 0, '2025-09-24 00:51:05', 9),
(79, 144, 22, 1, '2025-09-24 00:53:05', 9),
(80, 144, 4, 1, '2025-10-02 15:21:57', 9);

-- --------------------------------------------------------

--
-- Table structure for table `province`
--

CREATE TABLE `province` (
  `province_id` int(11) NOT NULL,
  `province_name` varchar(255) NOT NULL,
  `province_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `province`
--

INSERT INTO `province` (`province_id`, `province_name`, `province_code`) VALUES
(1, 'Camarines Norte', 25);

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
(144, 23, 1, 'Camarines Norte', 'Labo', 'District 1', 'Kalamunding', 'Calabasa Street', 0, 302, 'Residential lot with Affidavit and Barangay Cleara', 'Affidavit, Barangay Clearance', '2025-08-31 19:01:44', '2025-09-21 18:03:49', 1, NULL, NULL),
(147, 23, 3, 'Camarines Norte', 'Daet', 'District 2', 'Gahon', 'Mabini Street', 0, 453, 'Commercial lot with Affidavit and Barangay Clearan', 'Affidavit, Barangay Clearance', '2025-08-31 19:01:44', '2025-09-20 13:22:18', 1, NULL, NULL),
(156, 42134, 4, 'Camarines Norte', 'Daet', 'District 2', 'Bagasbas', 'Quezon Avenue', 1, 432, 'Agricultural lot with supporting affidavit', 'Affidavit', '2025-09-05 14:01:18', '2025-09-20 13:22:18', 0, '2025-09-13 14:29:21', 9),
(157, 5345, 4, 'Camarines Norte', 'Daet', 'District 2', 'Camambugan', 'San Roque', 0, 5345, 'Residential lot with Barangay Clearance', 'Barangay Clearance', '2025-09-05 14:13:20', '2025-09-20 13:22:18', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `received_papers`
--

CREATE TABLE `received_papers` (
  `received_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `transaction_code` varchar(10) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `transaction_type` varchar(100) DEFAULT NULL,
  `received_by` varchar(150) DEFAULT NULL,
  `received_date` datetime NOT NULL DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL,
  `status` enum('received','processing','ready_for_pickup','completed') DEFAULT 'received',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `received_papers`
--

INSERT INTO `received_papers` (`received_id`, `transaction_id`, `transaction_code`, `client_name`, `contact_number`, `transaction_type`, `received_by`, `received_date`, `notes`, `status`, `created_at`) VALUES
(16, 43, '38162', 'Jonard Canaria', '+639563453242', 'Consolidation', 'Admin A. Admin', '2025-10-02 18:00:29', '', 'received', '2025-10-02 10:00:29'),
(17, 44, 'RCV-1001', 'Maria Santos', '+639171112222', 'Application', 'Admin A. Admin', '2025-10-02 18:11:44', 'Verified papers', 'received', '2025-10-02 10:11:44'),
(18, 45, 'RCV-1002', 'Pedro Ramirez', '+639182223333', 'Renewal', 'Clerk01 B. Clerk', '2025-10-02 18:11:44', 'Late submission', 'received', '2025-10-02 10:11:44'),
(19, 46, 'RCV-1003', 'Ana Dela Cruz', '+639193334444', 'Request', 'Staff02 C. Staff', '2025-10-02 18:11:44', 'With attachments', 'received', '2025-10-02 10:11:44'),
(20, 47, 'RCV-1004', 'Renz Bautista', '+639204445555', 'Simple Transfer of Ownership', 'Admin A. Admin', '2025-10-02 18:11:44', 'Initial review passed', 'received', '2025-10-02 10:11:44'),
(21, 48, 'RCV-1005', 'Leo Villanueva', '+639215556666', 'Mortgage', 'Clerk01 B. Clerk', '2025-10-02 18:11:44', 'For legal team review', 'received', '2025-10-02 10:11:44'),
(22, 49, 'RCV-1006', 'Grace Mendoza', '+639226667777', 'Lease', 'Staff02 C. Staff', '2025-10-02 18:11:44', 'Completed forms', 'received', '2025-10-02 10:11:44'),
(23, 50, 'RCV-1007', 'Mark Reyes', '+639237778888', 'Donation', 'Admin A. Admin', '2025-10-02 18:11:44', 'Missing ID copy', 'received', '2025-10-02 10:11:44'),
(24, 51, 'RCV-1008', 'Josefina Cruz', '+639248889999', 'Partition', 'Clerk01 B. Clerk', '2025-10-02 18:11:44', 'Needs barangay clearance', 'received', '2025-10-02 10:11:44'),
(25, 52, 'RCV-1009', 'Cynthia Navarro', '+639259990000', 'Exchange', 'Staff02 C. Staff', '2025-10-02 18:11:44', 'Verified and complete', 'received', '2025-10-02 10:11:44'),
(26, 53, 'RCV-1010', 'Michael Tan', '+639260001111', 'Inheritance', 'Admin A. Admin', '2025-10-02 18:11:44', 'Heirs approved', 'received', '2025-10-02 10:11:44'),
(27, 54, 'RCV-1011', 'Liza Sarmiento', '+639271112222', 'Transfer Certificate of Title', 'Clerk01 B. Clerk', '2025-10-02 18:11:44', 'Pending notarization', 'received', '2025-10-02 10:11:44');

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
  `arp_no` varchar(150) NOT NULL,
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
(1, '1484394354', 'Provincial Assessor Maria Santos', '2025-08-05', 'Municipal Assessor Luis Cruz', '2025-08-06', 0, 110, '2025-08-07', 1, '2025-08-07', 'Ricardo Delos Reyes', 7000.00, 33, 20157.00),
(3, '42342', 'Juan Dela Cruz', '2025-09-10', 'Maria Reyes', '2025-09-11', 0, 110, '2025-09-27', 2, '2025-09-23', 'None', 5000.00, 36, 264000.00);

-- --------------------------------------------------------

--
-- Table structure for table `rpu_idnum`
--

CREATE TABLE `rpu_idnum` (
  `rpu_id` int(50) NOT NULL,
  `arp` varchar(150) NOT NULL,
  `pin` varchar(13) DEFAULT NULL,
  `taxability` varchar(20) NOT NULL,
  `effectivity` varchar(255) NOT NULL,
  `faas_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rpu_idnum`
--

INSERT INTO `rpu_idnum` (`rpu_id`, `arp`, `pin`, `taxability`, `effectivity`, `faas_id`) VALUES
(46, '42342-42342-243423', '1103456423442', 'taxable', '2025', 33),
(62, '42342', '110123456789', 'exempt', '2025', 36),
(63, '423234', '110-42342342-', 'taxable', '2025', 42);

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
(1, 'RA1', 'Single Detached', 100.00, 'Active'),
(2, 'RA2', 'Townhouse', 120.00, 'Active'),
(3, 'RA3', 'Condominium', 150.00, 'Active'),
(4, 'CA1', 'Retail Store', 200.00, 'Active'),
(5, 'CA2', 'Office Building', 250.00, 'Active'),
(6, 'IA1', 'Light Manufacturing', 300.00, 'Active'),
(7, 'IA2', 'Heavy Manufacturing', 350.00, 'Active'),
(8, 'AG1', 'Rice Farm', 50.00, 'Active'),
(9, 'AG2', 'Coconut Plantation', 60.00, 'Active'),
(10, 'MX1', 'Residential and Commercial', 180.00, 'Active'),
(11, 'MI1', 'Quarry Site', 70.00, 'Active'),
(12, 'MI2', 'Mining Area', 85.00, 'Active'),
(13, 'SP1', 'Hospital', 90.00, 'Active'),
(14, 'SP2', 'School', 95.00, 'Active'),
(15, 'SP3', 'Government Building', 88.00, 'Active'),
(16, 'SP4', 'Church', 80.00, 'Active'),
(17, 'TI1', 'Forest Land', 60.00, 'Active'),
(18, 'TI2', 'Mangrove Area', 55.00, 'Active');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `transaction_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `transaction_code`, `name`, `contact_number`, `description`, `status`, `created_at`, `updated_at`, `transaction_type`) VALUES
(41, '95289', 'Jonard Canaria', '+639432441231', 'Property Revision request #37123 received 09/24/2025. Your application is now pending review. For more info. visit https://erptstrack.erpts.online', 'Pending', '2025-09-24 01:42:33', '2025-09-24 01:42:33', 'Revision/Correction'),
(44, 'TX-1001', 'Juan Dela Cruz', '+639171111111', 'Simple Transfer request #1001 received', 'Pending', '2025-10-02 10:19:56', '2025-10-02 10:19:56', 'Simple Transfer of Ownership'),
(45, 'TX-1002', 'Maria Santos', '+639181111111', 'New Property Declaration request #1002 received', 'In Progress', '2025-10-02 10:19:56', '2025-10-02 10:19:56', 'New Declaration of Real Property'),
(46, 'TX-1003', 'Pedro Ramirez', '+639191111111', 'Property Revision request #1003 received', 'Completed', '2025-10-02 10:19:56', '2025-10-02 10:19:56', 'Revision/Correction'),
(47, 'TX-1004', 'Josefa Manalo', '+639201111111', 'Property Consolidation request #1004 received', 'Pending', '2025-10-02 10:19:56', '2025-10-02 10:19:56', 'Consolidation'),
(48, 'TX-1005', 'Carlos Cruz', '+639211111111', 'Simple Transfer request #1005 received', 'In Progress', '2025-10-02 10:19:56', '2025-10-02 10:19:56', 'Simple Transfer of Ownership'),
(49, 'TX-1006', 'Ana Villanueva', '+639221111111', 'New Property Declaration request #1006 received', 'Completed', '2025-10-02 10:19:56', '2025-10-02 10:19:56', 'New Declaration of Real Property'),
(50, 'TX-1007', 'Miguel Reyes', '+639231111111', 'Property Revision request #1007 received', 'Pending', '2025-10-02 10:19:56', '2025-10-02 10:19:56', 'Revision/Correction'),
(51, 'TX-1008', 'Elena Bautista', '+639241111111', 'Consolidation request #1008 received', 'In Progress', '2025-10-02 10:19:56', '2025-10-02 10:19:56', 'Consolidation'),
(52, 'TX-1009', 'Roberto Flores', '+639251111111', 'Simple Transfer request #1009 received', 'Completed', '2025-10-02 10:19:56', '2025-10-02 10:19:56', 'Simple Transfer of Ownership'),
(53, 'TX-1010', 'Andrea Pascual', '+639261111111', 'New Property Declaration request #1010 received', 'Pending', '2025-10-02 10:19:56', '2025-10-02 10:19:56', 'New Declaration of Real Property'),
(54, 'TX-1011', 'Lorenzo Aquino', '+639271111111', 'Property Revision request #1011 received', 'In Progress', '2025-10-02 10:19:56', '2025-10-02 10:19:56', 'Revision/Correction'),
(55, 'TX-1012', 'Cecilia Navarro', '+639281111111', 'Consolidation request #1012 received', 'Completed', '2025-10-02 10:19:56', '2025-10-02 10:19:56', 'Consolidation'),
(56, 'TX-1013', 'Daniel Gomez', '+639291111111', 'Simple Transfer request #1013 received', 'Pending', '2025-10-02 10:19:56', '2025-10-02 10:19:56', 'Simple Transfer of Ownership'),
(57, 'TX-1014', 'Patricia Ramos', '+639301111111', 'New Property Declaration request #1014 received', 'In Progress', '2025-10-02 10:19:56', '2025-10-02 10:19:56', 'New Declaration of Real Property'),
(58, 'TX-1015', 'Fernando Torres', '+639311111111', 'Property Revision request #1015 received', 'Completed', '2025-10-02 10:19:56', '2025-10-02 10:19:56', 'Revision/Correction');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_files`
--

CREATE TABLE `transaction_files` (
  `file_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_files`
--

INSERT INTO `transaction_files` (`file_id`, `transaction_id`, `file_path`, `uploaded_at`) VALUES
(50, 42, 'uploads/transaction_42/tx_68d34d6e2b15f_business-document-template_1435-229.jpg', '2025-09-24 01:46:22');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_logs`
--

CREATE TABLE `transaction_logs` (
  `log_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `transaction_code` varchar(50) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `details` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_logs`
--

INSERT INTO `transaction_logs` (`log_id`, `transaction_id`, `transaction_code`, `action`, `details`, `user_id`, `created_at`) VALUES
(114, 42, '12084', 'Document Uploaded', 'uploads/transaction_42/tx_68d34d6e2b15f_business-document-template_1435-229.jpg', 9, '2025-09-24 01:46:22'),
(115, 42, '12084', 'Created', 'Transaction created', 9, '2025-09-24 01:46:22'),
(116, 42, '12084', 'Updated', 'Transaction updated', 9, '2025-10-02 09:48:35'),
(117, 42, '12084', 'Papers Received', 'Papers received by client', 9, '2025-10-02 09:48:44'),
(118, 43, '38162', 'Created', 'Transaction created', 9, '2025-10-02 09:49:11'),
(119, 43, '38162', 'Updated', 'Transaction updated', 9, '2025-10-02 09:49:48'),
(120, 43, '38162', 'Papers Received', 'Papers received by client', 9, '2025-10-02 10:00:29');

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
  `brgy_id` int(11) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `m_id` int(11) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `user_type` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `last_name`, `first_name`, `middle_name`, `gender`, `birthdate`, `marital_status`, `tin`, `house_number`, `street`, `brgy_id`, `district_id`, `m_id`, `province`, `contact_number`, `email`, `status`, `user_type`) VALUES
(9, 'admin', '$2y$10$uJGu7hoKfqtqSLE2EyV2GetTumt1zHaZOnvIpBeGC5dcwWBr25fc.', 'Zuelos', 'John Lloyd', 'Cruz', 'Male', '2001-11-11', 'Single', '000-123-456-789', '5', 'Purok', 66, 18, 14, 'Camarines Norte', '09123456789', 'johnlloydzuelos@gmail.com', 1, 'admin'),
(12, 'user', '$2y$10$gmDQWOOqOOy8uUra8gGPQOA.FUDHTpucmbrNQ7mk..FbM/3ndQNt2', 'Cruz', 'Juan', 'Dela', 'Male', '2000-01-01', 'Single', 'NA', '1', 'Purok 1', 4, NULL, 1, 'Camarines Norte', '09123456789', 'user@email.com', 1, 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `admin_certification`
--
ALTER TABLE `admin_certification`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`district_id`);

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
-- Indexes for table `owner_audit_log`
--
ALTER TABLE `owner_audit_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `user_id` (`user_id`);

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
-- Indexes for table `received_papers`
--
ALTER TABLE `received_papers`
  ADD PRIMARY KEY (`received_id`),
  ADD UNIQUE KEY `unique_transaction` (`transaction_id`),
  ADD KEY `received_by` (`received_by`);

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
-- Indexes for table `transaction_files`
--
ALTER TABLE `transaction_files`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `transaction_id` (`transaction_id`);

--
-- Indexes for table `transaction_logs`
--
ALTER TABLE `transaction_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `transaction_code` (`transaction_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_brgy` (`brgy_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `admin_certification`
--
ALTER TABLE `admin_certification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `brgy`
--
ALTER TABLE `brgy`
  MODIFY `brgy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=283;

--
-- AUTO_INCREMENT for table `certification`
--
ALTER TABLE `certification`
  MODIFY `cert_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `classification`
--
ALTER TABLE `classification`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `district`
--
ALTER TABLE `district`
  MODIFY `district_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `faas`
--
ALTER TABLE `faas`
  MODIFY `faas_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `land`
--
ALTER TABLE `land`
  MODIFY `land_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `land_use`
--
ALTER TABLE `land_use`
  MODIFY `lu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `municipality`
--
ALTER TABLE `municipality`
  MODIFY `m_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `owners_tb`
--
ALTER TABLE `owners_tb`
  MODIFY `own_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `owner_audit_log`
--
ALTER TABLE `owner_audit_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `propertyowner`
--
ALTER TABLE `propertyowner`
  MODIFY `pO_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

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
-- AUTO_INCREMENT for table `received_papers`
--
ALTER TABLE `received_papers`
  MODIFY `received_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `region`
--
ALTER TABLE `region`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rpu_dec`
--
ALTER TABLE `rpu_dec`
  MODIFY `dec_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rpu_idnum`
--
ALTER TABLE `rpu_idnum`
  MODIFY `rpu_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `subclass`
--
ALTER TABLE `subclass`
  MODIFY `sc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `transaction_files`
--
ALTER TABLE `transaction_files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `transaction_logs`
--
ALTER TABLE `transaction_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certification`
--
ALTER TABLE `certification`
  ADD CONSTRAINT `land_id` FOREIGN KEY (`land_id`) REFERENCES `land` (`land_id`) ON DELETE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
