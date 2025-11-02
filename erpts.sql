-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2025 at 05:34 PM
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
  `user_id` int(11) DEFAULT NULL,
  `action` mediumtext DEFAULT NULL,
  `log_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`log_id`, `user_id`, `action`, `log_time`) VALUES
(81, 12, 'Failed login attempt - Incorrect password (Attempt 1 from IP: ::1)', '2025-10-27 13:23:48'),
(82, 12, 'Failed login attempt - Incorrect password (Attempt 1 from IP: ::1)', '2025-10-27 13:25:00'),
(83, 12, 'Failed login attempt - Incorrect password (Attempt 1 from IP: ::1)', '2025-10-27 13:25:08'),
(84, 12, 'Failed login attempt - Incorrect password (Attempt 1 from IP: ::1)', '2025-10-27 13:27:57'),
(88, 9, 'Exported statistics chart\n• Chart Type: Classification\n• Chart Title: Land Classification Distribution\n• Export Format: PNG Image\n• Export Time: 2025-10-28 11:35:27', '2025-10-28 10:35:27'),
(89, 9, 'Printed Property Report\n• Classification: All\n• Province: All\n• Municipality/City: All\n• District: All\n• Barangay: All\n• Date Range: All', '2025-10-28 15:31:29'),
(90, 9, 'Printed Property Report\n• Classification: Agricultural\n• Province: Camarines Norte\n• Municipality/City: All\n• District: All\n• Barangay: All\n• Date Range: All', '2025-10-28 15:32:19'),
(91, 9, 'Printed Property Report\n• Classification: Agricultural\n• Province: Camarines Norte\n• Municipality/City: All\n• District: All\n• Barangay: All\n• Date Range: All', '2025-10-28 15:32:47'),
(92, 9, 'Printed Property Report\n• Classification: Agricultural\n• Province: Camarines Norte\n• Municipality/City: All\n• District: All\n• Barangay: All\n• Date Range: All', '2025-10-28 15:35:41'),
(93, 9, 'Printed Property Report\n• Classification: Agricultural\n• Province: Camarines Norte\n• Municipality/City: All\n• District: All\n• Barangay: All\n• Date Range: All', '2025-10-28 15:37:23'),
(94, 9, 'Printed Property Report\n• Classification: All\n• Province: Camarines Norte\n• Municipality/City: All\n• District: All\n• Barangay: All\n• Date Range: September 15, 2025 - October 15, 2025', '2025-10-28 15:38:37'),
(95, 9, 'Printed Property Report\n• Classification: All\n• Province: Camarines Norte\n• Municipality/City: All\n• District: All\n• Barangay: All\n• Date Range: September 01, 2025 - October 15, 2025', '2025-10-28 15:39:04'),
(96, 9, 'Printed Property Report\n• Classification: All\n• Province: Camarines Norte\n• Municipality/City: Daet\n• District: District 2\n• Barangay: All\n• Date Range: September 01, 2025 - October 15, 2025', '2025-10-28 15:39:20'),
(97, 9, 'Logged out of the system', '2025-10-28 16:53:33'),
(98, 12, 'Failed login attempt \n• Incorrect Password', '2025-10-28 16:56:28'),
(99, 12, 'Failed login attempt \n• Incorrect Password', '2025-10-28 16:56:32'),
(100, 12, 'Failed login attempt \n• Incorrect Password', '2025-10-28 16:56:37'),
(101, 12, 'Failed login attempt \n• Incorrect Password', '2025-10-28 16:56:41'),
(102, 9, 'User logged in to the system', '2025-10-28 16:56:48'),
(103, 9, 'Logged out of the system', '2025-10-28 16:56:55'),
(104, 9, 'User logged in to the system', '2025-10-28 16:57:00'),
(105, 9, 'Logged out of the system', '2025-10-28 16:59:28'),
(106, NULL, 'Failed login attempt \n• Username \'fasdf\' does not exist in Database', '2025-10-28 16:59:32'),
(107, NULL, 'Failed login attempt \n• Username \'asdgasd\' does not exist in Database', '2025-10-28 16:59:36'),
(108, NULL, 'Failed login attempt \n• Username \'uasdfasd\' does not exist in Database', '2025-10-28 16:59:40'),
(109, NULL, 'Failed login attempt \n• Username \'uasdgasdg\' does not exist in Database', '2025-10-28 16:59:44'),
(110, 9, 'User logged in to the system from IP: ::1', '2025-10-28 17:06:07'),
(111, 9, 'Logged out of the system', '2025-10-28 17:06:30'),
(112, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:06:44'),
(113, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:06:57'),
(114, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:07:28'),
(115, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:07:35'),
(116, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:07:39'),
(117, 9, 'User logged in to the system from IP: ::1', '2025-10-28 17:23:14'),
(118, 9, 'Logged out of the system', '2025-10-28 17:23:27'),
(119, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:23:36'),
(120, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:23:48'),
(121, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:23:55'),
(122, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:24:09'),
(123, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:24:15'),
(124, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:29:50'),
(125, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:29:53'),
(126, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:29:56'),
(127, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:30:00'),
(128, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:30:03'),
(129, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:30:42'),
(130, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:30:48'),
(131, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:30:52'),
(132, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:35:30'),
(133, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:35:35'),
(134, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:35:39'),
(135, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:42:12'),
(136, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:42:15'),
(137, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:42:19'),
(138, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:50:59'),
(139, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:51:03'),
(140, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:51:07'),
(141, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:55:14'),
(142, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:55:21'),
(143, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:55:25'),
(144, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 17:59:58'),
(145, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 18:00:06'),
(146, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 18:00:10'),
(147, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 18:01:41'),
(148, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 18:01:45'),
(149, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 18:01:49'),
(150, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 18:06:28'),
(151, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 18:06:31'),
(152, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 18:06:36'),
(153, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 18:09:19'),
(154, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 18:09:22'),
(155, NULL, 'Failed login attempt from IP: ::1 - Username \'username\' not found', '2025-10-28 18:09:27'),
(156, NULL, 'Failed login from IP: ::1 - Username \'username\' not found', '2025-10-28 18:12:48'),
(157, NULL, 'Failed login from IP: ::1 - Username \'username\' not found', '2025-10-28 18:12:53'),
(158, NULL, 'Failed login from IP: ::1 - Username \'username\' not found', '2025-10-28 18:13:00'),
(159, NULL, 'Temporary lock activated from IP: ::1', '2025-10-28 18:13:00'),
(160, NULL, 'Failed login from IP: ::1 - Username \'username\' not found', '2025-10-28 18:20:41'),
(161, NULL, 'Failed login from IP: ::1 - Username \'username\' not found', '2025-10-28 18:20:46'),
(162, NULL, 'Failed login from IP: ::1 - Username \'username\' not found', '2025-10-28 18:20:50'),
(163, NULL, 'Temporary lock activated from IP: ::1', '2025-10-28 18:20:50'),
(164, 9, 'Logged in from IP: ::1', '2025-10-28 22:54:49'),
(165, 9, 'Exported statistics chart from Dashboard\n• Chart Type: Property\n• Chart Title: Property Statistics Overview\n• Export Format: PNG Image\n• Export Time: 2025-10-29 00:24:30', '2025-10-28 23:24:30'),
(166, 9, 'Logged out of the system', '2025-11-02 13:06:40'),
(167, NULL, 'Temporary lock activated for IP: ::1', '2025-11-02 13:06:52'),
(168, NULL, 'Failed login from IP: ::1 - Username not found', '2025-11-02 13:06:52'),
(169, NULL, 'Permanent lock activated for IP: ::1', '2025-11-02 13:12:05'),
(170, NULL, 'Failed login from IP: ::1 - Username not found', '2025-11-02 13:12:05'),
(171, 9, 'Unbanned IP address: ::1', '2025-11-02 15:25:07'),
(172, 9, 'Unbanned IP address: ::1', '2025-11-02 15:55:23'),
(173, NULL, 'Permanent lock activated for IP: ::1', '2025-11-02 15:56:59'),
(174, NULL, 'Failed login from IP: ::1 - Username not found', '2025-11-02 15:56:59'),
(175, 9, 'Unbanned IP address: ::1', '2025-11-02 16:06:23');

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
(6, 'Mark Bertillo', NULL, 'Provincial Assessor', 'active', 'provincial_assessor', '2025-10-07 15:39:00', '2025-10-08 21:54:51'),
(8, 'Jonard Canaria', NULL, 'Local Assessment Operations Officer IV', 'active', 'none', '2025-10-07 16:38:54', '2025-10-27 01:26:03'),
(9, 'Ma. Salome Bertillo', NULL, 'Assistant Assessor', 'active', 'verifier', '2025-10-07 16:42:41', '2025-10-08 21:54:51'),
(10, 'James Gacho', NULL, 'Administrative Clerk III', 'active', 'none', '2025-10-27 01:25:45', '2025-10-27 01:25:45');

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
(2, 'Malapajo, Antonio Menorca', 'Lingon, Nestor Jacolbia', 'Malapajo, Antonio Menorca', '2025-04-28', 'Malapajo, Antonio Menorca', 'Lingon, Nestor Jacolbia', '2025-04-28', 'Lingon, Nestor Jacolbia', '2025-04-28', 0, 0, 56);

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
(43, 157, NULL),
(47, 162, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ip_ban_history`
--

CREATE TABLE `ip_ban_history` (
  `history_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `banned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `unbanned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `unbanned_by` int(11) DEFAULT NULL,
  `unbanned_by_name` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ip_ban_history`
--

INSERT INTO `ip_ban_history` (`history_id`, `ip_address`, `banned_at`, `unbanned_at`, `unbanned_by`, `unbanned_by_name`) VALUES
(1, '::1', '2025-11-02 15:56:59', '2025-11-02 16:06:23', 9, 'John Lloyd Zuelos');

-- --------------------------------------------------------

--
-- Table structure for table `ip_lockout`
--

CREATE TABLE `ip_lockout` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `attempts` int(11) DEFAULT 0,
  `lock_until` int(11) DEFAULT 0,
  `is_permanent` tinyint(1) DEFAULT 0,
  `last_attempt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `faas_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `land`
--

INSERT INTO `land` (`land_id`, `oct_no`, `survey_no`, `north`, `east`, `south`, `west`, `boun_desc`, `last_name`, `first_name`, `middle_name`, `contact_no`, `email`, `house_street`, `barangay`, `district`, `municipality`, `province`, `land_desc`, `classification`, `sub_class`, `area`, `actual_use`, `unit_value`, `market_value`, `adjust_factor`, `adjust_percent`, `adjust_value`, `adjust_mv`, `assess_lvl`, `assess_value`, `faas_id`, `created_at`, `updated_at`) VALUES
(56, '1234', '3412', 'Lot 22', 'Barangay Road', 'Vacant Lot', 'Riverbank', 'Commercial property near public market', 'Reyes', 'Maria', 'Lopez', '09181234567', 'maria.reyes@example.com', 'Mabini Street', 'Gahon', 'District 2', 'Daet', 'Camarines Norte', 'Commercial lot', 'Commercial', 'Business Establishment', 432, 'Commercial', 34.00, 14688.00, 'Standard', 0.00, 0.00, 14688.00, 34.00, 4993.92, 33, '2025-08-27 16:41:18', '2025-09-09 14:40:08'),
(61, '56789', '98765', 'Main Road', 'Creek', 'Farm Lot', 'River', 'Bounded by creek and residential area', 'Villanueva', 'Carlos', 'M.', '09171234567', 'carlos.villanueva@example.com', 'Purok 5, Rizal Street', 'Lag-on', 'District 4', 'Daet', 'Camarines Norte', 'Agricultural lot used for coconut plantation', 'Agricultural', 'Coconut Farm', 1200, 'Agricultural', 50.00, 60000.00, 'Depreciation', 10.00, -6000.00, 54000.00, 40.00, 21600.00, NULL, '2025-10-10 16:04:03', '2025-10-26 20:27:40'),
(63, '56789', '98765', 'Main Road', 'Creek', 'Farm Lot', 'River', 'Bounded by creek and residential area', 'Villanueva', 'Carlos', 'M.', '09171234567', 'carlos.villanueva@example.com', 'Purok 5, Rizal Street', 'Lag-on', 'District 4', 'Daet', 'Camarines Norte', 'Agricultural lot used for coconut plantation', 'Agricultural', 'Coconut Farm', 1200, 'Agricultural', 50.00, 60000.00, 'Depreciation', 10.00, -6000.00, 54000.00, 34.00, 4993.92, 36, '2025-10-19 16:09:02', '2025-10-19 16:09:02');

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
(26, 'Removed', 9, 144, 9, 1, 'Removed: Elena Mendoza Lopez (Purok 1, Barangay 9, Labo, Camarines Norte) from property 144', '2025-10-02 15:21:57'),
(27, 'Removed', 12, 147, 9, 3, 'Removed: Ramon Torres Mendoza (San Isidro, Barangay 12, Daet, Camarines Norte) from property 147', '2025-10-11 10:43:15'),
(28, 'Snapshot', 2, 147, 9, 3, '{\"rpu_dec\":{\"dec_id\":3,\"arp_no\":\"GR-2023-II-01-012-00023\",\"pro_assess\":\"Mark Bertillo\",\"pro_date\":\"2025-09-10\",\"mun_assess\":\"Maria Reyes\",\"mun_date\":\"2025-09-11\",\"td_cancel\":0,\"previous_pin\":110,\"tax_year\":\"2025-09-27\",\"entered_by\":2,\"entered_year\":\"2025-09-23\",\"prev_own\":\"No\",\"prev_assess\":\"5000.00\",\"faas_id\":36,\"total_property_value\":\"64993.92\"},\"rpu_idnum\":{\"rpu_id\":62,\"arp\":\"GR-2023-II-01-012-00023\",\"pin\":\"110123456789\",\"taxability\":\"taxable\",\"effectivity\":\"2025\",\"faas_id\":36},\"p_info\":{\"p_id\":147,\"house_no\":23,\"block_no\":3,\"province\":\"Camarines Norte\",\"city\":\"Daet\",\"district\":\"District 2\",\"barangay\":\"Gahon\",\"street\":\"Mabini Street\",\"house_tag_no\":0,\"land_area\":453,\"desc_land\":\"Commercial lot with Affidavit and Barangay Clearan\",\"documents\":\"Affidavit, Barangay Clearance\",\"created_at\":\"2025-09-01 03:01:44\",\"updated_at\":\"2025-10-11 18:24:47\",\"is_active\":1,\"disabled_at\":null,\"disabled_by\":null},\"land\":[{\"land_id\":63,\"oct_no\":\"56789\",\"survey_no\":\"98765\",\"north\":\"Main Road\",\"east\":\"Creek\",\"south\":\"Farm Lot\",\"west\":\"River\",\"boun_desc\":\"Bounded by creek and residential area\",\"last_name\":\"Villanueva\",\"first_name\":\"Carlos\",\"middle_name\":\"M.\",\"contact_no\":\"09171234567\",\"email\":\"carlos.villanueva@example.com\",\"house_street\":\"Purok 5, Rizal Street\",\"barangay\":\"Lag-on\",\"district\":\"District 4\",\"municipality\":\"Daet\",\"province\":\"Camarines Norte\",\"land_desc\":\"Agricultural lot used for coconut plantation\",\"classification\":\"Agricultural\",\"sub_class\":\"Coconut Farm\",\"area\":1200,\"actual_use\":\"Agricultural\",\"unit_value\":\"50.00\",\"market_value\":\"60000.00\",\"adjust_factor\":\"Depreciation\",\"adjust_percent\":\"10.00\",\"adjust_value\":\"-6000.00\",\"adjust_mv\":\"54000.00\",\"assess_lvl\":\"34.00\",\"assess_value\":\"4993.92\",\"faas_id\":36,\"created_at\":\"2025-10-20 00:09:02\",\"updated_at\":\"2025-10-20 00:09:02\"}]}', '2025-10-26 17:50:59'),
(30, 'Snapshot', 12, 157, 9, 4, '{\"rpu_dec\":{\"dec_id\":4,\"arp_no\":\"GR-2023-II-03-014-00342\",\"pro_assess\":\"Juan Dela Cruz\",\"pro_date\":\"2025-10-15\",\"mun_assess\":\"Maria Reyes\",\"mun_date\":\"2025-10-15\",\"td_cancel\":0,\"previous_pin\":2147483647,\"tax_year\":\"2025-10-15\",\"entered_by\":0,\"entered_year\":\"2025-10-15\",\"prev_own\":\"\",\"prev_assess\":\"0.00\",\"faas_id\":43,\"total_property_value\":\"81600.00\"},\"rpu_idnum\":{\"rpu_id\":64,\"arp\":\"3212-3412-3121-422\",\"pin\":\"5324234134512\",\"taxability\":\"taxable\",\"effectivity\":\"2027\",\"faas_id\":43},\"p_info\":{\"p_id\":157,\"house_no\":5345,\"block_no\":4,\"province\":\"Camarines Norte\",\"city\":\"Daet\",\"district\":\"District 2\",\"barangay\":\"Camambugan\",\"street\":\"San Roque\",\"house_tag_no\":0,\"land_area\":5345,\"desc_land\":\"Residential lot with Barangay Clearance\",\"documents\":\"Barangay Clearance\",\"created_at\":\"2025-09-05 22:13:20\",\"updated_at\":\"2025-09-20 21:22:18\",\"is_active\":1,\"disabled_at\":null,\"disabled_by\":null},\"land\":[{\"land_id\":61,\"oct_no\":\"56789\",\"survey_no\":\"98765\",\"north\":\"Main Road\",\"east\":\"Creek\",\"south\":\"Farm Lot\",\"west\":\"River\",\"boun_desc\":\"Bounded by creek and residential area\",\"last_name\":\"Villanueva\",\"first_name\":\"Carlos\",\"middle_name\":\"M.\",\"contact_no\":\"09171234567\",\"email\":\"carlos.villanueva@example.com\",\"house_street\":\"Purok 5, Rizal Street\",\"barangay\":\"Lag-on\",\"district\":\"District 4\",\"municipality\":\"Daet\",\"province\":\"Camarines Norte\",\"land_desc\":\"Agricultural lot used for coconut plantation\",\"classification\":\"Agricultural\",\"sub_class\":\"Coconut Farm\",\"area\":1200,\"actual_use\":\"Agricultural\",\"unit_value\":\"50.00\",\"market_value\":\"60000.00\",\"adjust_factor\":\"Depreciation\",\"adjust_percent\":\"10.00\",\"adjust_value\":\"-6000.00\",\"adjust_mv\":\"54000.00\",\"assess_lvl\":\"40.00\",\"assess_value\":\"21600.00\",\"faas_id\":43,\"created_at\":\"2025-10-11 00:04:03\",\"updated_at\":\"2025-10-11 00:04:03\"}]}', '2025-10-26 20:27:40');

-- --------------------------------------------------------

--
-- Table structure for table `print_certifications`
--

CREATE TABLE `print_certifications` (
  `cert_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `faas_id` int(11) NOT NULL,
  `owner_admin` varchar(255) NOT NULL COMMENT 'Owner or Administrator name',
  `certification_date` date NOT NULL COMMENT 'Date of certification',
  `certification_fee` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Certification fee amount',
  `or_number` varchar(50) NOT NULL COMMENT 'Official Receipt Number',
  `date_paid` date NOT NULL COMMENT 'Date payment was made',
  `created_by` int(11) NOT NULL COMMENT 'User ID who created this record',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores certification details for tax declaration prints';

--
-- Dumping data for table `print_certifications`
--

INSERT INTO `print_certifications` (`cert_id`, `property_id`, `faas_id`, `owner_admin`, `certification_date`, `certification_fee`, `or_number`, `date_paid`, `created_by`, `created_at`) VALUES
(1, 144, 33, 'Renz Dioneda', '2025-10-15', 500.00, '5234', '2025-10-15', 9, '2025-10-16 00:14:57'),
(2, 157, 43, 'John Lloyd Zuelos', '2025-10-15', 67.00, '04323', '2025-10-15', 9, '2025-10-16 02:20:15'),
(3, 157, 43, 'John Lloyd Zuelos', '2025-10-15', 70.00, '094234', '2025-10-15', 9, '2025-10-16 05:04:21'),
(4, 157, 43, 'John Lloyd Zuelos', '2025-10-15', 34.00, '5234', '2025-10-15', 9, '2025-10-16 05:07:59'),
(5, 157, 43, 'John Lloyd Zuelos', '2025-10-15', 34.00, '5234', '2025-10-15', 9, '2025-10-16 05:11:20'),
(6, 157, 43, 'John Lloyd Zuelos', '2025-10-15', 34.00, '5234', '2025-10-15', 9, '2025-10-16 05:12:51'),
(7, 157, 43, 'John Lloyd Zuelos', '2025-10-15', 34.00, '5234', '2025-10-15', 9, '2025-10-16 05:19:37'),
(8, 157, 43, 'John Lloyd Zuelos', '2025-10-15', 43.00, '52345', '2025-10-15', 9, '2025-10-16 05:52:59'),
(9, 157, 43, 'John Lloyd Zuelos', '2025-10-15', 43.00, '523456', '2025-10-15', 9, '2025-10-16 05:54:06'),
(10, 157, 43, 'John Lloyd Zuelos', '2025-10-15', 43.00, '523456', '2025-10-15', 9, '2025-10-16 05:56:45'),
(11, 157, 43, 'Renz Dioneda', '2025-10-16', 56.00, '523457', '2025-10-16', 9, '2025-10-16 06:12:35'),
(12, 157, 43, 'Renz Dioneda', '2025-10-16', 55.00, '6423452', '2025-10-16', 9, '2025-10-16 11:36:51'),
(13, 144, 33, 'James Gacho', '2025-10-19', 45.00, '5443212', '2025-10-19', 9, '2025-10-19 19:50:15'),
(14, 144, 33, 'James Gacho', '2025-10-19', 60.00, '6432312', '2025-10-19', 9, '2025-10-19 20:02:09'),
(15, 144, 33, 'James Gacho', '2025-10-19', 60.00, '6432311', '2025-10-19', 9, '2025-10-19 20:07:31');

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
(66, 147, 12, 0, '2025-09-21 13:37:29', NULL),
(72, 157, 12, 0, '2025-09-21 13:37:29', NULL),
(74, 144, 14, 0, '2025-09-23 22:34:55', 9),
(75, 144, 8, 0, '2025-09-23 22:35:46', 9),
(76, 144, 11, 0, '2025-09-23 23:10:56', 9),
(77, 144, 12, 0, '2025-09-23 23:33:55', 9),
(78, 144, 10, 0, '2025-09-24 00:51:05', 9),
(79, 144, 22, 1, '2025-09-24 00:53:05', 9),
(80, 144, 4, 1, '2025-10-02 15:21:57', 9),
(81, 147, 2, 0, '2025-10-11 10:43:15', 9),
(82, 161, 4, 1, '2025-10-14 17:17:40', NULL),
(83, 162, 4, 1, '2025-10-15 06:50:06', NULL),
(84, 147, 11, 1, '2025-10-26 17:50:59', 9),
(86, 157, 4, 1, '2025-10-26 20:27:40', 9);

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
(147, 23, 3, 'Camarines Norte', 'Daet', 'District 2', 'Gahon', 'Mabini Street', 0, 453, 'Commercial lot with Affidavit and Barangay Clearan', 'Affidavit, Barangay Clearance', '2025-08-31 19:01:44', '2025-10-11 10:24:47', 1, NULL, NULL),
(156, 42134, 4, 'Camarines Norte', 'Daet', 'District 2', 'Bagasbas', 'Quezon Avenue', 1, 432, 'Agricultural lot with supporting affidavit', 'Affidavit', '2025-09-05 14:01:18', '2025-09-20 13:22:18', 0, '2025-09-13 14:29:21', 9),
(157, 5345, 4, 'Camarines Norte', 'Daet', 'District 2', 'Camambugan', 'San Roque', 0, 5345, 'Residential lot with Barangay Clearance', 'Barangay Clearance', '2025-09-05 14:13:20', '2025-09-20 13:22:18', 1, NULL, NULL),
(162, 432, 0, 'Camarines Norte', 'Talisay', 'District 2', 'Gabon', '', 0, 532, '   ', 'barangay', '2025-10-15 06:50:06', '2025-10-15 06:50:06', 1, NULL, NULL);

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
(27, 54, 'RCV-1011', 'Liza Sarmiento', '+639271112222', 'Transfer Certificate of Title', 'Clerk01 B. Clerk', '2025-10-02 18:11:44', 'Pending notarization', 'received', '2025-10-02 10:11:44'),
(46, 65, '58187', 'John Lloyd Zuelos', '+639165217083', 'Simple Transfer of Ownership', 'John Lloyd C. Zuelos', '2025-10-29 07:16:32', '', 'received', '2025-10-28 23:16:32');

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
  `faas_id` int(11) DEFAULT NULL,
  `total_property_value` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rpu_dec`
--

INSERT INTO `rpu_dec` (`dec_id`, `arp_no`, `pro_assess`, `pro_date`, `mun_assess`, `mun_date`, `td_cancel`, `previous_pin`, `tax_year`, `entered_by`, `entered_year`, `prev_own`, `prev_assess`, `faas_id`, `total_property_value`) VALUES
(1, 'GR-2023-II-02-012-00231', 'Provincial Assessor Maria Santos', '2025-08-05', 'Municipal Assessor Luis Cruz', '2025-08-06', 0, 110, '2025-08-07', 1, '2025-08-07', 'Ricardo Delos Reyes', 7000.00, 33, 20157.56),
(3, 'GR-2023-II-01-012-00023', 'Mark Bertillo', '2025-09-10', 'Maria Reyes', '2025-09-11', 0, 110, '2025-09-27', 2, '2025-09-23', 'No', 5000.00, 36, 64993.92),
(4, 'GR-2023-II-03-014-00342', 'Juan Dela Cruz', '2025-10-15', 'Maria Reyes', '2025-10-15', 0, 2147483647, '2025-10-15', 0, '2025-10-15', '', 0.00, NULL, 81600.00);

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
(46, '4234-2423-4224-342', '1103456423442', 'taxable', '2025', 33),
(62, 'GR-2023-II-01-012-00023', '110123456789', 'taxable', '2025', 36),
(63, '423234', '110-42342342-', 'taxable', '2025', 42),
(64, '3212-3412-3121-422', '5324234134512', 'taxable', '2027', 43);

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
(44, 'TX-1001', 'Juan Dela Cruz', '+639171423268', 'Transfer of Ownership request #TX-1001 received 10/29/2025. Your application is now pending review. For more info. visit https://erptstrack.erpts.online', 'Pending', '2025-10-02 10:19:56', '2025-10-28 23:07:30', 'Simple Transfer of Ownership'),
(45, 'TX-1002', 'Maria Santos', '+639181753453', 'New Property Declaration #TX-1002 is being processed. Documents under review as of 10/29/2025. For more info. visit https://erptstrack.erpts.online', 'In Progress', '2025-10-02 10:19:56', '2025-10-28 23:07:54', 'New Declaration of Real Property'),
(47, 'TX-1004', 'Josefa Manalo', '+639423645758', 'Property Consolidation request #TX-1004 received 10/29/2025. Your application is now pending review. For more info. visit https://erptstrack.erpts.online', 'Pending', '2025-10-02 10:19:56', '2025-10-28 23:08:05', 'Consolidation'),
(48, 'TX-1005', 'Carlos Cruz', '+639254353421', 'Transfer of Ownership #TX-1005 is being processed. Documents under review as of 10/29/2025. For more info. visit https://erptstrack.erpts.online', 'In Progress', '2025-10-02 10:19:56', '2025-10-28 23:08:13', 'Simple Transfer of Ownership'),
(51, 'TX-1008', 'Elena Bautista', '+639165217083', 'Property Consolidation #TX-1008 is being processed. Documents under review as of 11/02/2025. For more info. visit https://erptstrack.erpts.online', 'In Progress', '2025-10-02 10:19:56', '2025-11-02 15:05:00', 'Consolidation');

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
(50, 42, 'uploads/transaction_42/tx_68d34d6e2b15f_business-document-template_1435-229.jpg', '2025-09-24 01:46:22'),
(81, 57, 'uploads/transaction_57/1760932323_17609323098217198016003940274513.pdf', '2025-10-20 03:52:03'),
(82, 57, 'uploads/transaction_57/1760932323_Messenger_creation_CEAD8B37-7258-45FB-80AD-2F2C99F21150.pdf', '2025-10-20 03:52:03'),
(83, 57, 'uploads/transaction_57/1760932323_Messenger_creation_B731EE51-8920-4726-839A-8BBBB70FA178.pdf', '2025-10-20 03:52:03'),
(84, 62, 'uploads/transaction_62/1760932471_Screenshot_20251020_002219.pdf', '2025-10-20 03:54:31'),
(85, 62, 'uploads/transaction_62/1760932471_17609324673033823871870564330439.pdf', '2025-10-20 03:54:31'),
(86, 62, 'uploads/transaction_62/ERPTS.pdf', '2025-10-20 03:58:51');

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
(120, 43, '38162', 'Papers Received', 'Papers received by client', 9, '2025-10-02 10:00:29'),
(121, 58, 'TX-1015', 'Updated', 'Transaction updated', 9, '2025-10-12 10:47:39'),
(122, 58, 'TX-1015', 'Document Uploaded', 'uploads/transaction_58/tx_68eb8dca999f1_photo-1500462918059-b1a0cb512f1d.avif', 9, '2025-10-12 11:15:22'),
(123, 58, 'TX-1015', 'Updated', 'Transaction updated', 9, '2025-10-12 11:15:22'),
(124, 58, 'TX-1015', 'Updated', 'Transaction updated', 9, '2025-10-12 11:27:02'),
(125, 56, NULL, 'Deleted', 'Transaction deleted', 9, '2025-10-12 11:27:30'),
(126, 58, 'TX-1015', 'Document Deleted', 'Deleted document: uploads/transaction_58/tx_68eb8dca999f1_photo-1500462918059-b1a0cb512f1d.avif', 9, '2025-10-12 11:28:39'),
(127, 58, 'TX-1015', 'Papers Received', 'Papers received by client', 9, '2025-10-12 11:37:36'),
(128, 55, 'TX-1012', 'Document Uploaded', 'uploads/transaction_55/tx_68eb97dd597fe_1473216391897_gif__1600__900_.gif', 9, '2025-10-12 11:58:21'),
(129, 55, 'TX-1012', 'Updated', 'Transaction updated', 9, '2025-10-12 11:58:21'),
(130, 55, 'TX-1012', 'Document Deleted', 'Deleted document: uploads/transaction_55/tx_68eb97dd597fe_1473216391897_gif__1600__900_.gif', 9, '2025-10-12 11:58:30'),
(131, 57, 'TX-1014', 'Mobile Upload', 'uploads/transaction_57/1760276805_17602767976178486282545429110742.jpg', 0, '2025-10-12 13:46:45'),
(132, 57, 'TX-1014', 'Mobile Upload', 'uploads/transaction_57/1760277091_1760277078085458691757065411211.jpg', 0, '2025-10-12 13:51:31'),
(133, 57, 'TX-1014', 'Mobile Upload', 'uploads/transaction_57/1760285454_17602854373913725204252983909266.jpg', 0, '2025-10-12 16:10:54'),
(134, 57, 'TX-1014', 'Document Deleted', 'Deleted document: uploads/transaction_57/1760277091_1760277078085458691757065411211.jpg', 9, '2025-10-12 16:11:10'),
(135, 57, 'TX-1014', 'Mobile Upload', 'uploads/transaction_57/1760285658_FB_IMG_1760253383644.jpg', 0, '2025-10-12 16:14:18'),
(136, 57, 'TX-1014', 'Mobile Upload', 'uploads/transaction_57/1760285658_FB_IMG_1760254477732.jpg', 0, '2025-10-12 16:14:18'),
(137, 57, 'TX-1014', 'Document Deleted', 'Deleted document: uploads/transaction_57/1760276805_17602767976178486282545429110742.jpg', 9, '2025-10-12 16:15:16'),
(138, 57, 'TX-1014', 'Document Deleted', 'Deleted document: uploads/transaction_57/1760285454_17602854373913725204252983909266.jpg', 9, '2025-10-12 16:15:18'),
(139, 57, 'TX-1014', 'Document Deleted', 'Deleted document: uploads/transaction_57/1760285658_FB_IMG_1760253383644.jpg', 9, '2025-10-12 16:15:19'),
(140, 57, 'TX-1014', 'Document Deleted', 'Deleted document: uploads/transaction_57/1760285658_FB_IMG_1760254477732.jpg', 9, '2025-10-12 16:15:20'),
(141, 57, 'TX-1014', 'Mobile Upload', 'uploads/transaction_57/1760286019_scanned_images.pdf', 0, '2025-10-12 16:20:19'),
(142, 57, 'TX-1014', 'Mobile Upload', 'uploads/transaction_57/1760286363_Screenshot_20251012_175126.pdf', 0, '2025-10-12 16:26:03'),
(143, 57, 'TX-1014', 'Mobile Upload', 'uploads/transaction_57/1760286363_Screenshot_20251012_181741.pdf', 0, '2025-10-12 16:26:03'),
(144, 57, 'TX-1014', 'Mobile Upload', 'uploads/transaction_57/1760286363_FB_IMG_1760254477732.pdf', 0, '2025-10-12 16:26:03'),
(145, 57, 'TX-1014', 'Mobile Upload', 'uploads/transaction_57/1760286363_FB_IMG_1760253383644.pdf', 0, '2025-10-12 16:26:03'),
(146, 57, 'TX-1014', 'Document Deleted', 'Deleted document: uploads/transaction_57/1760286019_scanned_images.pdf', 9, '2025-10-12 16:26:13'),
(147, 57, 'TX-1014', 'Document Deleted', 'Deleted document: uploads/transaction_57/1760286363_Screenshot_20251012_181741.pdf', 9, '2025-10-12 16:32:28'),
(148, 57, 'TX-1014', 'Document Deleted', 'Deleted document: uploads/transaction_57/1760286363_Screenshot_20251012_175126.pdf', 9, '2025-10-12 16:41:58'),
(149, 57, 'TX-1014', 'Mobile Upload', 'uploads/transaction_57/1760287394_Screenshot_20251012_175126.pdf', 0, '2025-10-12 16:43:14'),
(150, 57, 'TX-1014', 'Mobile Upload', 'uploads/transaction_57/1760287394_Screenshot_20251012_181741.pdf', 0, '2025-10-12 16:43:14'),
(151, 57, 'TX-1014', 'Mobile Upload', 'uploads/transaction_57/1760287394_FB_IMG_1760253383644.pdf', 0, '2025-10-12 16:43:14'),
(152, 57, 'TX-1014', 'Mobile Upload', 'uploads/transaction_57/1760287394_FB_IMG_1760254477732.pdf', 0, '2025-10-12 16:43:14'),
(153, 57, 'TX-1014', 'Document Deleted', 'Deleted document: uploads/transaction_57/1760287394_Screenshot_20251012_175126.pdf', 9, '2025-10-13 15:42:27'),
(154, 59, '01303', 'Linked Pending Upload', 'uploads/transaction_59/1760371613_FB_IMG_1760359050496.pdf', 9, '2025-10-13 16:07:23'),
(155, 59, '01303', 'Linked Pending Upload', 'uploads/transaction_59/1760371613_Screenshot_20251013_204606.pdf', 9, '2025-10-13 16:07:23'),
(156, 59, '01303', 'Created', 'Transaction created', 9, '2025-10-13 16:07:23'),
(157, 59, NULL, 'Deleted', 'Transaction deleted', 9, '2025-10-13 16:32:53'),
(158, 60, '55664', 'Created', 'Transaction created', 9, '2025-10-13 16:34:47'),
(159, 61, '69155', 'Document Uploaded', 'uploads/transaction_61/tx_68ed3712442ee_zuelos.png', 9, '2025-10-13 17:29:54'),
(160, 61, '69155', 'Document Uploaded', 'uploads/transaction_61/tx_68ed3712464c7_Gacho.png', 9, '2025-10-13 17:29:54'),
(161, 61, '69155', 'Created', 'Transaction created', 9, '2025-10-13 17:29:54'),
(162, 61, NULL, 'Deleted', 'Transaction deleted', 9, '2025-10-13 17:30:21'),
(163, 60, NULL, 'Deleted', 'Transaction deleted', 9, '2025-10-13 17:56:19'),
(164, 62, '02603', 'Document Uploaded', 'uploads/transaction_62/tx_68ed3fb03f5b6_zuelos.pdf', 9, '2025-10-13 18:06:40'),
(165, 62, '02603', 'Document Uploaded', 'uploads/transaction_62/tx_68ed3fb041734_Gacho.pdf', 9, '2025-10-13 18:06:40'),
(166, 62, '02603', 'Created', 'Transaction created', 9, '2025-10-13 18:06:40'),
(167, 62, '02603', 'Document Deleted', 'Deleted document: uploads/transaction_62/tx_68ed3fb041734_Gacho.pdf', 9, '2025-10-13 18:12:24'),
(168, 62, '02603', 'Document Deleted', 'Deleted document: uploads/transaction_62/tx_68ed3fb03f5b6_zuelos.pdf', 9, '2025-10-13 18:12:27'),
(169, 62, '02603', 'Document Uploaded', 'uploads/transaction_62/tx_68ed4116ecfe0_zuelos.pdf', 9, '2025-10-13 18:12:38'),
(170, 62, '02603', 'Document Uploaded', 'uploads/transaction_62/tx_68ed4116ef098_Gacho.pdf', 9, '2025-10-13 18:12:38'),
(171, 62, '02603', 'Updated', 'Transaction updated', 9, '2025-10-13 18:12:38'),
(172, 57, 'TX-1014', 'Document Deleted', 'Deleted document: uploads/transaction_57/1760287394_FB_IMG_1760253383644.pdf', 9, '2025-10-13 18:14:14'),
(173, 57, 'TX-1014', 'Document Deleted', 'Deleted document: uploads/transaction_57/1760287394_FB_IMG_1760254477732.pdf', 9, '2025-10-13 18:14:16'),
(174, 62, '02603', 'Document Deleted', 'Deleted document: uploads/transaction_62/tx_68ed4116ef098_Gacho.pdf', 9, '2025-10-13 18:15:42'),
(175, 62, '02603', 'Document Deleted', 'Deleted document: uploads/transaction_62/tx_68ed4116ecfe0_zuelos.pdf', 9, '2025-10-13 18:15:45'),
(176, 62, '02603', 'Document Deleted', 'Deleted document: uploads/transaction_62/1760379631_17603796241224363308576054988599.pdf', 9, '2025-10-13 18:20:47'),
(177, 62, '02603', 'Document Deleted', 'Deleted document: uploads/transaction_62/1760930452_17609304497356340843951931172250.pdf', 9, '2025-10-20 03:21:12'),
(178, 62, '02603', 'Document Deleted', 'Deleted document: uploads/transaction_62/1760930452_Messenger_creation_B731EE51-8920-4726-839A-8BBBB70FA178.pdf', 9, '2025-10-20 03:21:15'),
(179, 62, '02603', 'Document Deleted', 'Deleted document: uploads/transaction_62/1760930452_Messenger_creation_CEAD8B37-7258-45FB-80AD-2F2C99F21150.pdf', 9, '2025-10-20 03:21:17'),
(180, 57, 'TX-1014', 'Document Deleted', 'Deleted document: uploads/transaction_57/Jm_Tin.pdf', 9, '2025-10-20 03:50:27'),
(181, 57, 'TX-1014', 'Document Deleted', 'Deleted document: uploads/transaction_57/1760286363_FB_IMG_1760253383644.pdf', 9, '2025-10-20 03:50:30'),
(182, 57, 'TX-1014', 'Document Deleted', 'Deleted document: uploads/transaction_57/Birth_Cert.pdf', 9, '2025-10-20 03:50:32'),
(183, 62, '02603', 'Updated', 'Transaction updated', 9, '2025-10-28 08:11:59'),
(184, 62, '02603', 'Updated', 'Transaction updated', 9, '2025-10-28 08:13:39'),
(185, 63, '18422', 'Created', 'Transaction created', 9, '2025-10-28 08:15:29'),
(186, 63, '18422', 'Updated', 'Transaction updated', 9, '2025-10-28 08:18:23'),
(187, 63, '18422', 'SMS Sent', 'Notification sent to +639165217083', 9, '2025-10-28 08:18:23'),
(188, 64, '50241', 'Created', 'Transaction created', 9, '2025-10-28 09:00:59'),
(189, 64, '50241', 'SMS Sent', 'Notification sent to +639165217083', 9, '2025-10-28 09:00:59'),
(190, 55, 'TX-1012', 'Updated', 'Transaction updated', 9, '2025-10-28 09:02:40'),
(191, 55, 'TX-1012', 'SMS Sent', 'Notification sent to +639165217083', 9, '2025-10-28 09:02:41'),
(192, 64, '50241', 'Updated', 'Transaction updated', 9, '2025-10-28 09:14:44'),
(193, 64, '50241', 'SMS Sent', 'Notification sent to +639165217083', 9, '2025-10-28 09:14:45'),
(194, 64, '50241', 'Updated', 'Transaction updated', 9, '2025-10-28 09:17:49'),
(195, 64, '50241', 'Updated', 'Transaction updated', 9, '2025-10-28 09:18:07'),
(196, 55, 'TX-1012', 'Papers Received', 'Papers received by client', 9, '2025-10-28 09:30:00'),
(197, 50, NULL, 'Deleted', 'Transaction deleted', 9, '2025-10-28 10:27:21'),
(198, 64, '50241', 'Updated', 'Transaction updated', 9, '2025-10-28 11:08:18'),
(199, 64, '50241', 'Papers Received', 'Papers received by client', 9, '2025-10-28 11:08:55'),
(200, 63, '18422', 'Updated', 'Transaction updated', 9, '2025-10-28 12:38:40'),
(201, 63, '18422', 'Updated', 'Transaction updated', 9, '2025-10-28 12:40:04'),
(202, 49, 'TX-1006', 'Papers Received', 'Papers received by client', 9, '2025-10-28 13:08:36'),
(203, 63, '18422', 'Papers Received', 'Papers received by client', 9, '2025-10-28 13:14:01'),
(204, 46, 'TX-1003', 'Papers Received', 'Papers received by client', 9, '2025-10-28 13:15:06'),
(205, 54, NULL, 'Deleted', 'Transaction deleted', 9, '2025-10-28 13:16:22'),
(206, 62, '02603', 'Updated', 'Transaction updated', 9, '2025-10-28 13:20:00'),
(207, 62, '02603', 'Papers Received', 'Papers received by client', 9, '2025-10-28 13:40:45'),
(208, 57, 'TX-1014', 'Updated', 'Transaction updated', 9, '2025-10-28 13:41:17'),
(209, 57, 'TX-1014', 'Papers Received', 'Papers received by client', 9, '2025-10-28 13:41:23'),
(210, 53, NULL, 'Deleted', 'Transaction deleted', 9, '2025-10-28 13:44:14'),
(211, 51, 'TX-1008', 'Updated', 'Transaction updated', 9, '2025-10-28 14:15:18'),
(212, 65, '58187', 'Created', 'Transaction created', 9, '2025-10-28 14:21:16'),
(213, 65, '58187', 'SMS Sent', 'Notification sent to +639165217083', 9, '2025-10-28 14:21:17'),
(214, 48, 'TX-1005', 'Updated', 'Transaction updated', 9, '2025-10-28 16:31:07'),
(215, 65, '58187', 'Updated', 'Transaction updated', 9, '2025-10-28 23:00:56'),
(216, 65, '58187', 'Updated', 'Transaction updated', 9, '2025-10-28 23:04:46'),
(217, 65, '58187', 'Updated', 'Transaction updated', 9, '2025-10-28 23:04:59'),
(218, 65, '58187', 'SMS Sent', 'Status changed: \'In Progress\' → \'Completed\'. Notification sent to +639165217083', 9, '2025-10-28 23:05:00'),
(219, 47, 'TX-1004', 'Updated', 'Transaction updated', 9, '2025-10-28 23:06:41'),
(220, 45, 'TX-1002', 'Updated', 'Transaction updated', 9, '2025-10-28 23:06:50'),
(221, 44, 'TX-1001', 'Updated', 'Transaction updated', 9, '2025-10-28 23:07:30'),
(222, 45, 'TX-1002', 'Updated', 'Transaction updated', 9, '2025-10-28 23:07:54'),
(223, 47, 'TX-1004', 'Updated', 'Transaction updated', 9, '2025-10-28 23:08:05'),
(224, 48, 'TX-1005', 'Updated', 'Transaction updated', 9, '2025-10-28 23:08:13'),
(225, 65, '58187', 'Papers Received', 'Papers received by client', 9, '2025-10-28 23:16:32'),
(226, 65, '58187', 'SMS Sent', 'Papers received confirmation sent to +639165217083', 9, '2025-10-28 23:16:32'),
(227, 51, 'TX-1008', 'Updated', 'Transaction updated', 9, '2025-11-02 15:05:00'),
(228, 51, 'TX-1008', 'SMS Sent', 'Status changed: \'Completed\' → \'In Progress\'. Notification sent to +639165217083', 9, '2025-11-02 15:05:01');

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

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_print_certifications`
-- (See below for the actual view)
--
CREATE TABLE `vw_print_certifications` (
`cert_id` int(11)
,`property_id` int(11)
,`faas_id` int(11)
,`owner_admin` varchar(255)
,`certification_date` date
,`certification_fee` decimal(10,2)
,`or_number` varchar(50)
,`date_paid` date
,`created_by` int(11)
,`created_at` datetime
,`house_no` int(10)
,`barangay` varchar(30)
,`city` varchar(30)
,`province` varchar(30)
,`tax_declaration_number` varchar(150)
,`created_by_name` varchar(101)
);

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
-- Indexes for table `ip_ban_history`
--
ALTER TABLE `ip_ban_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `idx_ip_address` (`ip_address`),
  ADD KEY `idx_unbanned_at` (`unbanned_at`),
  ADD KEY `fk_unbanned_by` (`unbanned_by`);

--
-- Indexes for table `ip_lockout`
--
ALTER TABLE `ip_lockout`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ip_address` (`ip_address`),
  ADD KEY `idx_ip` (`ip_address`);

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
-- Indexes for table `print_certifications`
--
ALTER TABLE `print_certifications`
  ADD PRIMARY KEY (`cert_id`),
  ADD KEY `idx_property_id` (`property_id`),
  ADD KEY `idx_faas_id` (`faas_id`),
  ADD KEY `idx_or_number` (`or_number`),
  ADD KEY `idx_created_at` (`created_at`);

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
  ADD UNIQUE KEY `unique_transaction_code` (`transaction_code`),
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
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- AUTO_INCREMENT for table `admin_certification`
--
ALTER TABLE `admin_certification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `brgy`
--
ALTER TABLE `brgy`
  MODIFY `brgy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=283;

--
-- AUTO_INCREMENT for table `certification`
--
ALTER TABLE `certification`
  MODIFY `cert_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `faas_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `ip_ban_history`
--
ALTER TABLE `ip_ban_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ip_lockout`
--
ALTER TABLE `ip_lockout`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `land`
--
ALTER TABLE `land`
  MODIFY `land_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

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
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `print_certifications`
--
ALTER TABLE `print_certifications`
  MODIFY `cert_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `propertyowner`
--
ALTER TABLE `propertyowner`
  MODIFY `pO_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `province`
--
ALTER TABLE `province`
  MODIFY `province_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `p_info`
--
ALTER TABLE `p_info`
  MODIFY `p_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `received_papers`
--
ALTER TABLE `received_papers`
  MODIFY `received_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `region`
--
ALTER TABLE `region`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rpu_dec`
--
ALTER TABLE `rpu_dec`
  MODIFY `dec_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rpu_idnum`
--
ALTER TABLE `rpu_idnum`
  MODIFY `rpu_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `subclass`
--
ALTER TABLE `subclass`
  MODIFY `sc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `transaction_files`
--
ALTER TABLE `transaction_files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `transaction_logs`
--
ALTER TABLE `transaction_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=229;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

-- --------------------------------------------------------

--
-- Structure for view `vw_print_certifications`
--
DROP TABLE IF EXISTS `vw_print_certifications`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_print_certifications`  AS SELECT `pc`.`cert_id` AS `cert_id`, `pc`.`property_id` AS `property_id`, `pc`.`faas_id` AS `faas_id`, `pc`.`owner_admin` AS `owner_admin`, `pc`.`certification_date` AS `certification_date`, `pc`.`certification_fee` AS `certification_fee`, `pc`.`or_number` AS `or_number`, `pc`.`date_paid` AS `date_paid`, `pc`.`created_by` AS `created_by`, `pc`.`created_at` AS `created_at`, `p`.`house_no` AS `house_no`, `p`.`barangay` AS `barangay`, `p`.`city` AS `city`, `p`.`province` AS `province`, `rd`.`arp_no` AS `tax_declaration_number`, concat(`u`.`first_name`,' ',`u`.`last_name`) AS `created_by_name` FROM ((((`print_certifications` `pc` join `p_info` `p` on(`pc`.`property_id` = `p`.`p_id`)) join `faas` `f` on(`pc`.`faas_id` = `f`.`faas_id`)) left join `rpu_dec` `rd` on(`f`.`faas_id` = `rd`.`faas_id`)) left join `users` `u` on(`pc`.`created_by` = `u`.`user_id`)) ORDER BY `pc`.`created_at` DESC ;

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
-- Constraints for table `ip_ban_history`
--
ALTER TABLE `ip_ban_history`
  ADD CONSTRAINT `fk_ip_history_unbanned_by` FOREIGN KEY (`unbanned_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `land`
--
ALTER TABLE `land`
  ADD CONSTRAINT `fk_land_faas` FOREIGN KEY (`faas_id`) REFERENCES `faas` (`faas_id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `municipality`
--
ALTER TABLE `municipality`
  ADD CONSTRAINT `municipality_ibfk_1` FOREIGN KEY (`r_id`) REFERENCES `region` (`r_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `print_certifications`
--
ALTER TABLE `print_certifications`
  ADD CONSTRAINT `fk_print_cert_faas` FOREIGN KEY (`faas_id`) REFERENCES `faas` (`faas_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_print_cert_property` FOREIGN KEY (`property_id`) REFERENCES `p_info` (`p_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rpu_dec`
--
ALTER TABLE `rpu_dec`
  ADD CONSTRAINT `faas_id` FOREIGN KEY (`faas_id`) REFERENCES `faas` (`faas_id`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
