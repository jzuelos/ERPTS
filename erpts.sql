-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2025 at 01:14 PM
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
  `brgy_name` varchar(20) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `m_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brgy`
--

INSERT INTO `brgy` (`brgy_id`, `brgy_code`, `brgy_name`, `status`, `m_id`) VALUES
(66, '051603001', 'Alawihao', 'Active', 3),
(67, '051603002', 'Awitan', 'Active', 3),
(68, '051603003', 'Bagasbas', 'Active', 3),
(69, '051603004', 'Barangay I (Poblacio', 'Active', 3),
(70, '051607001', 'Apuao', 'Active', 4),
(71, '051607002', 'Barangay I', 'Active', 3),
(72, '051607009', 'Caringo', 'Active', 3),
(73, '051612001', 'Aguit-It', 'Active', 6),
(74, '051612002', 'Banocboc', 'Active', 3),
(75, '051612003', 'Cagbalogo', 'Active', 3);

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
(19, 'Malapajo, Antonio Menorca', 'Lingon, Nestor Jacolbia', 'Malapajo, Antonio Menorca', '2025-04-29', 'Malapajo, Antonio Menorca', 'Lingon, Nestor Jacolbia', '2025-04-29', 'Lingon, Nestor Jacolbia', '2025-04-29', 0, 0, 57);

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
  `propertyowner_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`propertyowner_id`)),
  `pro_id` int(50) DEFAULT NULL,
  `rpu_idno` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faas`
--

INSERT INTO `faas` (`faas_id`, `propertyowner_id`, `pro_id`, `rpu_idno`) VALUES
(33, '[62,63]', 144, 46),
(36, '[66]', 147, NULL);

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
  `faas_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `land`
--

INSERT INTO `land` (`land_id`, `oct_no`, `survey_no`, `north`, `east`, `south`, `west`, `boun_desc`, `last_name`, `first_name`, `middle_name`, `contact_no`, `email`, `house_street`, `barangay`, `district`, `municipality`, `province`, `land_desc`, `classification`, `sub_class`, `area`, `actual_use`, `unit_value`, `market_value`, `adjust_factor`, `adjust_percent`, `adjust_value`, `adjust_mv`, `assess_lvl`, `assess_value`, `faas_id`) VALUES
(55, 25634, '423', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 800, '', 20.00, 16000.00, '', 17.00, -13280.00, 2720.00, 100.00, 2720.00, 33),
(56, 1234, '3412', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 432, '1', 34.00, 14688.00, '', 100.00, 0.00, 14688.00, 34.00, 4993.92, 33),
(57, 3421, '4324', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'asdfasdfa', '', '', 800, '1', 200.00, 160000.00, '', 100.00, 0.00, 160000.00, 65.00, 104000.00, 36);

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
(1, 'SC', 'SC234', '', 60.00, 'Active');

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
(66, 147, 12, 1);

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
  `ownID_Fk` int(50) NOT NULL,
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
  `documents` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `p_info`
--

INSERT INTO `p_info` (`p_id`, `ownID_Fk`, `house_no`, `block_no`, `province`, `city`, `district`, `barangay`, `street`, `house_tag_no`, `land_area`, `desc_land`, `documents`) VALUES
(144, 12, 23, 0, 'Province 1', 'Daet', 'District 1', 'Kalamunding', '', 0, 302, '   ', 'affidavit, barangay'),
(147, 12, 23, 0, '54', '3', 'District 2', '67', '', 453, 3453, '   ', 'affidavit, barangay');

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
  `land_id` int(50) NOT NULL,
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
  `faas_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rpu_dec`
--

INSERT INTO `rpu_dec` (`dec_id`, `arp_no`, `land_id`, `pro_assess`, `pro_date`, `mun_assess`, `mun_date`, `td_cancel`, `previous_pin`, `tax_year`, `entered_by`, `entered_year`, `prev_own`, `prev_assess`, `faas_id`) VALUES
(2, 2342, 55, 'fasdf', '2025-04-11', 'asdf', '2025-04-14', 0, 0, '0000-00-00', 234234, '0000-00-00', '34234', 42342.00, 33);

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
(46, 2342, 3456, 'special', '2024', 33);

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
(8, 'RIP_FISHY12', '$2y$10$smEhcMQrkEwgF/Whm1BrDeT0ru.898kqGKwEjVl1HhkR7Bpj/vdP.', 'Zuelos', 'Jomel', 'Villacruel', 'Female', '2012-12-19', 'Married', '32423423', '32', 'Luzaragga', 'Kalamunding', '2', 'Labo', 'Camarines Norte', '09923648721', 'sbjomel19@gmail.com', 1, 'admin'),
(9, 'admin', '$2y$10$cyc4ZGr2yJ6FhQWqomCbQuNbWB9ADdj0HQAFE2U4dU.fR9aRdteha', 'Admin', 'Admin', 'Admin', 'Male', '2001-11-11', 'Single', '000-123-456-789', '5', 'Purok', '66', '18', '14', 'Camarines Norte', '09123456789', 'admin@gmail.com', 1, 'admin'),
(10, 'user', '$2y$10$XCD.AAvKsPiW0N5LsPJLNO8QlVaX3pcx2hBWCc5R3/951/g2tkTEi', 'User', 'User', 'User', 'Male', '2002-02-02', 'Single', '000-321-654-987', '1', 'Purok 2', '71', '18', '4', 'Camarines Norte', '09876543210', 'user@gmail.com', 1, 'user');

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
  ADD KEY `propertyowner_id` (`propertyowner_id`(768)),
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
  ADD PRIMARY KEY (`p_id`),
  ADD KEY `ownID_FK` (`ownID_Fk`);

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
  MODIFY `brgy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `certification`
--
ALTER TABLE `certification`
  MODIFY `cert_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `classification`
--
ALTER TABLE `classification`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `district`
--
ALTER TABLE `district`
  MODIFY `district_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `faas`
--
ALTER TABLE `faas`
  MODIFY `faas_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `land`
--
ALTER TABLE `land`
  MODIFY `land_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `land_use`
--
ALTER TABLE `land_use`
  MODIFY `lu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `pO_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `province`
--
ALTER TABLE `province`
  MODIFY `province_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `p_info`
--
ALTER TABLE `p_info`
  MODIFY `p_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `region`
--
ALTER TABLE `region`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rpu_dec`
--
ALTER TABLE `rpu_dec`
  MODIFY `dec_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rpu_idnum`
--
ALTER TABLE `rpu_idnum`
  MODIFY `rpu_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

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
-- Constraints for table `p_info`
--
ALTER TABLE `p_info`
  ADD CONSTRAINT `ownID_FK` FOREIGN KEY (`ownID_Fk`) REFERENCES `owners_tb` (`own_id`);

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
