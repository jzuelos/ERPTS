-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2025 at 02:59 PM
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
(17, 'Malapajo, Antonio Menorca', 'Lingon, Nestor Jacolbia', 'Malapajo, Antonio Menorca', '2025-09-20', 'Malapajo, Antonio Menorca', 'Lingon, Nestor Jacolbia', '2025-09-20', 'Lingon, Nestor Jacolbia', '2025-09-20', 0, 0, 55),
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
(55, 12345, '42322', 'Lot 15', 'Barangay Road', 'Rice Field', 'River', 'Bounded by residential and agricultural lands', 'Cruz', 'Juan', 'Dela', '09345678901', 'juan.cruz@example.com', 'Rizal Street', 'Kalamunding', 'District 1', 'Daet', 'Camarines Norte', 'Residential lot with improvements', 'Agricultural', '', 23, 'SC', 20.00, 460.00, 'Depreciation', 17.00, -381.80, 78.20, 20.00, 15.64, 33, '2025-08-27 16:41:18', '2025-09-20 08:44:08'),
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

INSERT INTO `owners_tb` (`own_id`, `own_fname`, `own_mname`, `own_surname`, `date_birth`, `tin_no`, `house_no`, `street`, `barangay`, `district`, `city`, `province`, `own_info`) VALUES
(8, 'Renz', 'Balce', 'Dioneda', '2015-09-16', 0, '5', 'Purok', 'Bulala', 'District 1', 'Santa Elena', 'Camarines Norte', 'Telephone: 09922007821, Fax: , Email: rdioneda4@gmail.com, Website: '),
(9, 'Jonard', 'Balce', 'Canaria', '2017-09-08', 0, '1', 'Purok 3', 'Alawihao', 'District 2', 'Santa elena', 'Camarines norte', 'Telephone: 09473846382, Fax: , Email: jonard@gmail.com, Website: '),
(10, 'Rommel James', 'Balce', 'Gacho', '2016-09-15', 0, '3', 'Purok 2', 'Bagacay', 'District 1', 'Labo', 'Camarines Norte', 'Telephone: 09738265234, Fax: , Email: rommel@gmail.com, Website: '),
(11, 'John Lloyd', 'Balce', 'Zuelos', '2018-09-17', 0, '1', 'Purok 2', 'Kalamunding', 'District 1', 'Labo', 'Camarines Norte', 'Telephone: 09643826422, Fax: , Email: jzuelos@gmail.com, Website: '),
(12, 'Mark', 'Balce', 'Bertillo', '2019-09-17', 0, '3', 'Purok 1', 'Pasig', 'District 2', 'Daet', 'Camarines norte', 'Telephone: 09634618435, Fax: , Email: markbertillo@gmail.com, Website:');

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
(1, 'Camarines Norte');

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
(156, 42134, 4, 'Camarines Norte', 'Daet', 'District 2', 'Bagasbas', 'Quezon Avenue', 1, 432, 'Agricultural lot with affidavit', 'Affidavit', '2025-09-05 14:01:18', '2025-09-13 06:29:21', 0, '2025-09-13 14:29:21', 9),
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
(1, 'RA', '32', 56.00, 'Active'),
(2, 'IA', '43', 45.00, 'Active'),
(3, '', '34', 78.00, 'Active');

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

-- --------------------------------------------------------

--
-- Table structure for table `transaction_logs`
--

CREATE TABLE `transaction_logs` (
  `log_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `details` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_logs`
--

INSERT INTO `transaction_logs` (`log_id`, `transaction_id`, `action`, `details`, `user_id`, `created_at`) VALUES
(34, 21, 'Document Uploaded', 'uploads/transaction_21/tx_68c70d34a5cda_premium_photo-1666900440561-94dcb6865554.avif', 9, '2025-09-14 18:45:08'),
(35, 21, 'Created', 'Transaction created', 9, '2025-09-14 18:45:08'),
(36, 21, 'Document Uploaded', 'uploads/transaction_21/tx_68c70d41598a0_photo-1500462918059-b1a0cb512f1d.avif', 9, '2025-09-14 18:45:21'),
(37, 21, 'Updated', 'Transaction updated', 9, '2025-09-14 18:45:21'),
(38, 21, 'Document Deleted', 'Deleted document: uploads/transaction_21/tx_68c70d41598a0_photo-1500462918059-b1a0cb512f1d.avif', 9, '2025-09-14 18:45:33'),
(39, 22, 'Created', 'Transaction created', 9, '2025-09-14 18:48:24'),
(40, 22, 'Document Uploaded', 'uploads/transaction_22/tx_68c70e5f3cc72_premium_photo-1666900440561-94dcb6865554.avif', 9, '2025-09-14 18:50:07'),
(41, 22, 'Document Uploaded', 'uploads/transaction_22/tx_68c70e5f3d936_photo-1493612276216-ee3925520721.avif', 9, '2025-09-14 18:50:07'),
(42, 22, 'Document Uploaded', 'uploads/transaction_22/tx_68c70e5f3e6b6_photo-1500462918059-b1a0cb512f1d.avif', 9, '2025-09-14 18:50:07'),
(43, 22, 'Updated', 'Transaction updated', 9, '2025-09-14 18:50:07'),
(44, 22, 'Deleted', 'Transaction deleted', 9, '2025-09-14 18:51:35'),
(45, 23, 'Document Uploaded', 'uploads/transaction_23/tx_68c70f2ad733a_premium_photo-1666900440561-94dcb6865554.avif', 9, '2025-09-14 18:53:30'),
(46, 23, 'Created', 'Transaction created', 9, '2025-09-14 18:53:30'),
(47, 23, 'Updated', 'Transaction updated', 9, '2025-09-15 15:58:48'),
(48, 23, 'Updated', 'Transaction updated', 9, '2025-09-15 16:54:45'),
(49, 23, 'Deleted', 'Transaction deleted', 9, '2025-09-15 16:55:16'),
(50, 21, 'Deleted', 'Transaction deleted', 9, '2025-09-15 16:56:01'),
(51, 24, 'Created', 'Transaction created', 10, '2025-09-14 17:12:23'),
(52, 25, 'Created', 'Transaction created', 10, '2025-09-16 10:49:03');

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
(11, 'dioneda', '$2y$10$dOOZplrD0.Lkexf7eX34z.a6yX1zJcfzkjC6NhtScOTiumbw/rw6m', 'Dioneda', 'Renz', 'Balce', 'Male', '2003-11-14', 'Married', 'NA', '5', 'Purok', '95', NULL, '5', 'Camarines Norte', '09922007821', 'rdioneda4@gmail.com', 0, 'user');

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
  ADD KEY `user_id` (`user_id`);

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
  MODIFY `brgy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=283;

--
-- AUTO_INCREMENT for table `certification`
--
ALTER TABLE `certification`
  MODIFY `cert_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `classification`
--
ALTER TABLE `classification`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `lu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `municipality`
--
ALTER TABLE `municipality`
  MODIFY `m_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `transaction_files`
--
ALTER TABLE `transaction_files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `transaction_logs`
--
ALTER TABLE `transaction_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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

--
-- Constraints for table `transaction_files`
--
ALTER TABLE `transaction_files`
  ADD CONSTRAINT `transaction_files_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
