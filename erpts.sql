-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2025 at 08:58 PM
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
(1, 'DA1', 'Alawihao', 'Active', 1),
(2, 'DA2', 'Awitan', 'Active', 1),
(3, 'DA3', 'Bagasbas', 'Active', 1),
(4, 'DA4', 'Barangay I', 'Active', 1),
(5, 'DA5', 'Barangay II', 'Active', 1),
(6, 'DA6', 'Barangay III', 'Active', 1),
(7, 'DA7', 'Barangay IV', 'Active', 1),
(8, 'DA8', 'Barangay V', 'Active', 1),
(9, 'DA9', 'Barangay VI', 'Active', 1),
(10, 'DA10', 'Barangay VII', 'Active', 1),
(11, 'DA11', 'Barangay VIII', 'Active', 1),
(12, 'DA12', 'Bibirao', 'Active', 1),
(13, 'DA13', 'Borabod', 'Active', 1),
(14, 'DA14', 'Calasgasan', 'Active', 1),
(15, 'DA15', 'Camambugan', 'Active', 1),
(16, 'DA16', 'Cobangbang', 'Active', 1),
(17, 'DA17', 'Dogongan', 'Active', 1),
(18, 'DA18', 'Gahonon', 'Active', 1),
(19, 'DA19', 'Gubat', 'Active', 1),
(20, 'DA20', 'Lag-on', 'Active', 1),
(21, 'DA21', 'Magang', 'Active', 1),
(22, 'DA22', 'Mambalite', 'Active', 1),
(23, 'DA23', 'Mancruz', 'Active', 1),
(24, 'DA24', 'Pamorangon', 'Active', 1),
(25, 'DA25', 'San Isidro', 'Active', 1),
(26, 'ME1', 'Apuao', 'Active', 2),
(27, 'ME2', 'Barangay I', 'Active', 2),
(28, 'ME3', 'Barangay II', 'Active', 2),
(29, 'ME4', 'Barangay III', 'Active', 2),
(30, 'ME5', 'Barangay IV', 'Active', 2),
(31, 'ME6', 'Barangay V', 'Active', 2),
(32, 'ME7', 'Barangay VI', 'Active', 2),
(33, 'ME8', 'Barangay VII', 'Active', 2),
(34, 'ME9', 'Caringo', 'Active', 2),
(35, 'ME10', 'Catandunganon', 'Active', 2),
(36, 'ME11', 'Cayucyucan', 'Active', 2),
(37, 'ME12', 'Colasi', 'Active', 2),
(38, 'ME13', 'Del Rosario', 'Active', 2),
(39, 'ME14', 'Gaboc', 'Active', 2),
(40, 'ME15', 'Hamoraon', 'Active', 2),
(41, 'ME16', 'Hinipaan', 'Active', 2),
(42, 'ME17', 'Lalawigan', 'Active', 2),
(43, 'ME18', 'Lanot', 'Active', 2),
(44, 'ME19', 'Mambungalon', 'Active', 2),
(45, 'ME20', 'Manguisoc', 'Active', 2),
(46, 'ME21', 'Masalongsalong', 'Active', 2),
(47, 'ME22', 'Matoogtoog', 'Active', 2),
(48, 'ME23', 'Pambuhan', 'Active', 2),
(49, 'ME24', 'Quinapaguian', 'Active', 2),
(50, 'ME25', 'San Roque', 'Active', 2),
(51, 'ME26', 'Tarum', 'Active', 2),
(52, 'TA1', 'Binanuaan', 'Active', 3),
(53, 'TA2', 'Caawigan', 'Active', 3),
(54, 'TA3', 'Cahabaan', 'Active', 3),
(55, 'TA4', 'Calintaan', 'Active', 3),
(56, 'TA5', 'Del Carmen', 'Active', 3),
(57, 'TA6', 'Gabon', 'Active', 3),
(58, 'TA7', 'Itomang', 'Active', 3),
(59, 'TA8', 'Poblacion', 'Active', 3),
(60, 'TA9', 'San Francisco', 'Active', 3),
(61, 'TA10', 'San Isidro', 'Active', 3),
(62, 'TA11', 'San Jose', 'Active', 3),
(63, 'TA12', 'San Nicolas', 'Active', 3),
(64, 'TA13', 'Santa Cruz', 'Active', 3),
(65, 'TA14', 'Santa Elena', 'Active', 3),
(66, 'TA15', 'Santo Niño', 'Active', 3),
(67, 'VI1', 'Aguit-It', 'Active', 4),
(68, 'VI2', 'Banocboc', 'Active', 4),
(69, 'VI3', 'Cagbalogo', 'Active', 4),
(70, 'VI4', 'Calangcawan Norte', 'Active', 4),
(71, 'VI5', 'Calangcawan Sur', 'Active', 4),
(72, 'VI6', 'Guinacutan', 'Active', 4),
(73, 'VI7', 'Mangcayo', 'Active', 4),
(74, 'VI8', 'Manlucugan', 'Active', 4),
(75, 'VI9', 'Matacong', 'Active', 4),
(76, 'VI10', 'Napilihan', 'Active', 4),
(77, 'VI11', 'Pinagtigasan', 'Active', 4),
(78, 'VI12', 'Santo Domingo', 'Active', 4),
(79, 'VI13', 'Singuay', 'Active', 4),
(80, 'VI14', 'Tabas', 'Active', 4),
(81, 'VI15', 'Talisay', 'Active', 4),
(82, 'VI16', 'Mangcawayan', 'Active', 4),
(83, 'VI17', 'San Vicente', 'Active', 4),
(84, 'VI18', 'Barangay I (Poblacion)', 'Active', 4),
(85, 'VI19', 'Barangay II (Poblacion)', 'Active', 4),
(86, 'LA01', 'Anahaw', 'Active', 5),
(87, 'LA02', 'Anameam', 'Active', 5),
(88, 'LA03', 'Awitan', 'Active', 5),
(89, 'LA04', 'Baay', 'Active', 5),
(90, 'LA05', 'Bagacay', 'Active', 5),
(91, 'LA06', 'Bagong Silang I', 'Active', 5),
(92, 'LA07', 'Bagong Silang II', 'Active', 5),
(93, 'LA08', 'Bagong Silang III', 'Active', 5),
(94, 'LA09', 'Bakiad', 'Active', 5),
(95, 'LA10', 'Bautista', 'Active', 5),
(96, 'LA11', 'Bayabas', 'Active', 5),
(97, 'LA12', 'Bayan-bayan', 'Active', 5),
(98, 'LA13', 'Benit', 'Active', 5),
(99, 'LA14', 'Bulhao', 'Active', 5),
(100, 'LA15', 'Cabatuhan', 'Active', 5),
(101, 'LA16', 'Cabusay', 'Active', 5),
(102, 'LA17', 'Calabasa', 'Active', 5),
(103, 'LA18', 'Canapawan', 'Active', 5),
(104, 'LA19', 'Daguit', 'Active', 5),
(105, 'LA20', 'Dalas', 'Active', 5),
(106, 'LA21', 'Dumagmang', 'Active', 5),
(107, 'LA22', 'Exciban', 'Active', 5),
(108, 'LA23', 'Fundado', 'Active', 5),
(109, 'LA24', 'Guinacutan', 'Active', 5),
(110, 'LA25', 'Guisican', 'Active', 5),
(111, 'LA26', 'Gumamela', 'Active', 5),
(112, 'LA27', 'Iberica', 'Active', 5),
(113, 'LA28', 'Kalamunding', 'Active', 5),
(114, 'LA29', 'Lugui', 'Active', 5),
(115, 'LA30', 'Mabilo I', 'Active', 5),
(116, 'LA31', 'Mabilo II', 'Active', 5),
(117, 'LA32', 'Macogon', 'Active', 5),
(118, 'LA33', 'Mahawan-hawan', 'Active', 5),
(119, 'LA34', 'Malangcao-Basud', 'Active', 5),
(120, 'LA35', 'Malasugui', 'Active', 5),
(121, 'LA36', 'Malatap', 'Active', 5),
(122, 'LA37', 'Malaya', 'Active', 5),
(123, 'LA38', 'Malibago', 'Active', 5),
(124, 'LA39', 'Maot', 'Active', 5),
(125, 'LA40', 'Masalong', 'Active', 5),
(126, 'LA41', 'Matanlang', 'Active', 5),
(127, 'LA42', 'Napaod', 'Active', 5),
(128, 'LA43', 'Pag-asa', 'Active', 5),
(129, 'LA44', 'Pangpang', 'Active', 5),
(130, 'LA45', 'Pinya', 'Active', 5),
(131, 'LA46', 'San Antonio', 'Active', 5),
(132, 'LA47', 'San Francisco', 'Active', 5),
(133, 'LA48', 'Santa Cruz', 'Active', 5),
(134, 'LA49', 'Submakin', 'Active', 5),
(135, 'LA50', 'Talobatib', 'Active', 5),
(136, 'LA51', 'Tigbinan', 'Active', 5),
(137, 'LA52', 'Tulay na Lupa', 'Active', 5),
(138, 'PA01', 'Awitan', 'Active', 6),
(139, 'PA02', 'Bagumbayan', 'Active', 6),
(140, 'PA03', 'Bakal', 'Active', 6),
(141, 'PA04', 'Batobalani', 'Active', 6),
(142, 'PA05', 'Calaburnay', 'Active', 6),
(143, 'PA06', 'Capacuan', 'Active', 6),
(144, 'PA07', 'Casalugan', 'Active', 6),
(145, 'PA08', 'Dagang', 'Active', 6),
(146, 'PA09', 'Dalnac', 'Active', 6),
(147, 'PA10', 'Dancalan', 'Active', 6),
(148, 'PA11', 'Gumaus', 'Active', 6),
(149, 'PA12', 'Labnig', 'Active', 6),
(150, 'PA13', 'Macolabo Island', 'Active', 6),
(151, 'PA14', 'Malacbang', 'Active', 6),
(152, 'PA15', 'Malaguit', 'Active', 6),
(153, 'PA16', 'Mampungo', 'Active', 6),
(154, 'PA17', 'Mangkasay', 'Active', 6),
(155, 'PA18', 'Maybato', 'Active', 6),
(156, 'PA19', 'Palanas', 'Active', 6),
(157, 'PA20', 'Pinagbirayan Malaki', 'Active', 6),
(158, 'PA21', 'Pinagbirayan Munti', 'Active', 6),
(159, 'PA22', 'Poblacion Norte', 'Active', 6),
(160, 'PA23', 'Poblacion Sur', 'Active', 6),
(161, 'PA24', 'Tabas', 'Active', 6),
(162, 'PA25', 'Talusan', 'Active', 6),
(163, 'PA26', 'Tawig', 'Active', 6),
(164, 'PA27', 'Tugos', 'Active', 6),
(165, 'JP01', 'Bagong Bayan', 'Active', 7),
(166, 'JP02', 'Calero', 'Active', 7),
(167, 'JP03', 'Dahican', 'Active', 7),
(168, 'JP04', 'Dayhagan', 'Active', 7),
(169, 'JP05', 'Larap', 'Active', 7),
(170, 'JP06', 'Luklukan Norte', 'Active', 7),
(171, 'JP07', 'Luklukan Sur', 'Active', 7),
(172, 'JP08', 'Motherlode', 'Active', 7),
(173, 'JP09', 'Nakalaya', 'Active', 7),
(174, 'JP10', 'North Poblacion', 'Active', 7),
(175, 'JP11', 'Osmeña', 'Active', 7),
(176, 'JP12', 'Pag-asa', 'Active', 7),
(177, 'JP13', 'Parang', 'Active', 7),
(178, 'JP14', 'Plaridel', 'Active', 7),
(179, 'JP15', 'Salvacion', 'Active', 7),
(180, 'JP16', 'San Isidro', 'Active', 7),
(181, 'JP17', 'San Jose', 'Active', 7),
(182, 'JP18', 'San Martin', 'Active', 7),
(183, 'JP19', 'San Pedro', 'Active', 7),
(184, 'JP20', 'San Rafael', 'Active', 7),
(185, 'JP21', 'Santa Cruz', 'Active', 7),
(186, 'JP22', 'Santa Elena', 'Active', 7),
(187, 'JP23', 'Santa Milagrosa', 'Active', 7),
(188, 'JP24', 'Santa Rosa Norte', 'Active', 7),
(189, 'JP25', 'Santa Rosa Sur', 'Active', 7),
(190, 'JP26', 'South Poblacion', 'Active', 7),
(191, 'JP27', 'Tamisan', 'Active', 7),
(192, 'CA01', 'Alayao', 'Active', 8),
(193, 'CA02', 'Binawangan', 'Active', 8),
(194, 'CA03', 'Calabaca', 'Active', 8),
(195, 'CA04', 'Camagsaan', 'Active', 8),
(196, 'CA05', 'Catabaguangan', 'Active', 8),
(197, 'CA06', 'Catioan', 'Active', 8),
(198, 'CA07', 'Del Pilar', 'Active', 8),
(199, 'CA08', 'Itok', 'Active', 8),
(200, 'CA09', 'Lucbanan', 'Active', 8),
(201, 'CA10', 'Mabini', 'Active', 8),
(202, 'CA11', 'Mactang', 'Active', 8),
(203, 'CA12', 'Magsaysay', 'Active', 8),
(204, 'CA13', 'Mataque', 'Active', 8),
(205, 'CA14', 'Old Camp', 'Active', 8),
(206, 'CA15', 'Poblacion', 'Active', 8),
(207, 'CA16', 'San Antonio', 'Active', 8),
(208, 'CA17', 'San Isidro', 'Active', 8),
(209, 'CA18', 'San Roque', 'Active', 8),
(210, 'CA19', 'Tanawan', 'Active', 8),
(211, 'CA20', 'Ubang', 'Active', 8),
(212, 'CA21', 'Villa Aurora', 'Active', 8),
(213, 'CA22', 'Villa Belen', 'Active', 8),
(214, 'BA01', 'Angas', 'Active', 9),
(215, 'BA02', 'Bactas', 'Active', 9),
(216, 'BA03', 'Binatagan', 'Active', 9),
(217, 'BA04', 'Caayunan', 'Active', 9),
(218, 'BA05', 'Guinatungan', 'Active', 9),
(219, 'BA06', 'Hinampacan', 'Active', 9),
(220, 'BA07', 'Langa', 'Active', 9),
(221, 'BA08', 'Laniton', 'Active', 9),
(222, 'BA09', 'Lidong', 'Active', 9),
(223, 'BA10', 'Mampili', 'Active', 9),
(224, 'BA11', 'Mandazo', 'Active', 9),
(225, 'BA12', 'Mangcamagong', 'Active', 9),
(226, 'BA13', 'Manmuntay', 'Active', 9),
(227, 'BA14', 'Mantugawe', 'Active', 9),
(228, 'BA15', 'Matnog', 'Active', 9),
(229, 'BA16', 'Mocong', 'Active', 9),
(230, 'BA17', 'Oliva', 'Active', 9),
(231, 'BA18', 'Pagsangahan', 'Active', 9),
(232, 'BA19', 'Pinagwarasan', 'Active', 9),
(233, 'BA20', 'Plaridel', 'Active', 9),
(234, 'BA21', 'Poblacion 1', 'Active', 9),
(235, 'BA22', 'Poblacion 2', 'Active', 9),
(236, 'BA23', 'San Felipe', 'Active', 9),
(237, 'BA24', 'San Jose', 'Active', 9),
(238, 'BA25', 'San Pascual', 'Active', 9),
(239, 'BA26', 'Taba-taba', 'Active', 9),
(240, 'BA27', 'Tacad', 'Active', 9),
(241, 'BA28', 'Taisan', 'Active', 9),
(242, 'BA29', 'Tuaca', 'Active', 9),
(243, 'SV01', 'Asdum', 'Active', 10),
(244, 'SV02', 'Cabanbanan', 'Active', 10),
(245, 'SV03', 'Calabagas', 'Active', 10),
(246, 'SV04', 'Fabrica', 'Active', 10),
(247, 'SV05', 'Iraya Sur', 'Active', 10),
(248, 'SV06', 'Man-ogob', 'Active', 10),
(249, 'SV07', 'Poblacion District I', 'Active', 10),
(250, 'SV08', 'Poblacion District II', 'Active', 10),
(251, 'SV09', 'San Jose', 'Active', 10),
(252, 'SL01', 'Daculang Bolo', 'Active', 11),
(253, 'SL02', 'Dagotdotan', 'Active', 11),
(254, 'SL03', 'Langga', 'Active', 11),
(255, 'SL04', 'Laniton', 'Active', 11),
(256, 'SL05', 'Maisog', 'Active', 11),
(257, 'SL06', 'Mampurog', 'Active', 11),
(258, 'SL07', 'Manlimonsito', 'Active', 11),
(259, 'SL08', 'Matacong', 'Active', 11),
(260, 'SL09', 'Salvacion', 'Active', 11),
(261, 'SL10', 'San Antonio', 'Active', 11),
(262, 'SL11', 'San Isidro', 'Active', 11),
(263, 'SL12', 'San Ramon', 'Active', 11),
(264, 'SE01', 'Basiad', 'Active', 12),
(265, 'SE02', 'Bulala', 'Active', 12),
(266, 'SE03', 'Don Tomas', 'Active', 12),
(267, 'SE04', 'Guitol', 'Active', 12),
(268, 'SE05', 'Kabuluan', 'Active', 12),
(269, 'SE06', 'Kagtalaba', 'Active', 12),
(270, 'SE07', 'Maulawin', 'Active', 12),
(271, 'SE08', 'Patag Ibaba', 'Active', 12),
(272, 'SE09', 'Patag Ilaya', 'Active', 12),
(273, 'SE10', 'Plaridel', 'Active', 12),
(274, 'SE11', 'Polungguitguit', 'Active', 12),
(275, 'SE12', 'Rizal', 'Active', 12),
(276, 'SE13', 'Salvacion', 'Active', 12),
(277, 'SE14', 'San Lorenzo', 'Active', 12),
(278, 'SE15', 'San Pedro', 'Active', 12),
(279, 'SE16', 'San Vicente', 'Active', 12),
(280, 'SE17', 'Santa Elena (Poblacion)', 'Active', 12),
(281, 'SE18', 'Tabugon', 'Active', 12),
(282, 'SE19', 'Villa San Isidro', 'Active', 12);

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
(1, 4600, 'Daet', 'Active', 5),
(2, 4601, 'Mercedes', 'Active', 5),
(3, 4602, 'Talisay', 'Active', 5),
(4, 4603, 'Vinzons', 'Active', 5),
(5, 4604, 'Labo', 'Active', 5),
(6, 4605, 'Paracale', 'Active', 5),
(7, 4606, 'Jose Panganiban', 'Active', 5),
(8, 4607, 'Capalonga', 'Active', 5),
(9, 4608, 'Basud', 'Active', 5),
(10, 4609, 'San Vicente', 'Active', 5),
(11, 4610, 'San Lorenzo Ruiz', 'Active', 5),
(12, 4611, 'Santa Elena', 'Active', 5);

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `transaction_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `transaction_code`, `name`, `contact_number`, `description`, `status`, `created_at`, `updated_at`, `transaction_type`) VALUES
(21, '45206', 'James', '09232412', 'New Discovery', 'In Progress', '2025-09-14 18:45:08', '2025-09-14 18:45:21', 'New Declaration of Real Property'),
(23, '11385', 'Renz', '093445323', 'Request to Change Ownership', 'In Progress', '2025-09-14 18:53:30', '2025-09-14 18:53:30', 'Simple Transfer of Ownership');

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
(43, 21, 'uploads/transaction_21/tx_68c70d34a5cda_premium_photo-1666900440561-94dcb6865554.avif', '2025-09-14 18:45:08'),
(48, 23, 'uploads/transaction_23/tx_68c70f2ad733a_premium_photo-1666900440561-94dcb6865554.avif', '2025-09-14 18:53:30');

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
(46, 23, 'Created', 'Transaction created', 9, '2025-09-14 18:53:30');

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
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `transaction_files`
--
ALTER TABLE `transaction_files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `transaction_logs`
--
ALTER TABLE `transaction_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

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
