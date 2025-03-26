-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2025 at 10:30 PM
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
  `rpu_idno` int(20) DEFAULT NULL,
  `land_id` int(50) DEFAULT NULL,
  `plants_id` int(50) DEFAULT NULL,
  `valuation_id` int(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faas`
--

INSERT INTO `faas` (`faas_id`, `propertyowner_id`, `pro_id`, `rpu_idno`, `land_id`, `plants_id`, `valuation_id`) VALUES
(32, '[61]', 143, 45, NULL, NULL, NULL),
(33, '[62,63]', 144, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `land`
--

CREATE TABLE `land` (
  `land_id` int(50) NOT NULL,
  `oct_no` int(50) NOT NULL,
  `survey_no` varchar(250) NOT NULL,
  `boundaries` varchar(250) NOT NULL,
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
  `area` varchar(250) NOT NULL,
  `actual_use` varchar(250) NOT NULL,
  `unit_value` int(50) NOT NULL,
  `market_value` int(50) NOT NULL,
  `faas_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `land`
--

INSERT INTO `land` (`land_id`, `oct_no`, `survey_no`, `boundaries`, `boun_desc`, `last_name`, `first_name`, `middle_name`, `contact_no`, `email`, `house_street`, `barangay`, `district`, `municipality`, `province`, `land_desc`, `classification`, `sub_class`, `area`, `actual_use`, `unit_value`, `market_value`, `faas_id`) VALUES
(6, 46, 'PSU-98765', '', 'Containing an area of 1,000 square meters, more or less.', 'Dioneda', 'Renz', 'Balce', '09932007821', 'rdioneda4@gmail.com', '1', 'Purok 5', '', 'Sta. Elena', 'Camarines Norte', '', 'Residential', 'Rice Land', '1500', 'Farmland', 2000, 3000000, 32),
(7, 46, 'Lot 1234, Cad-5678', '', ' A rectangular lot with a frontage of 20 meters along Rizal Street, adjacent to commercial establishments.', 'Reyes', 'Carlos', 'Mendoza', '09171234567', 'carlos.reyes@email.com', '23-B', 'Mabini', '', 'San Fernando ', 'Pampanga', 'A 1,500 sq. m. residential lot along Rizal Street, fully fenced with road access and nearby utilities.', 'Residential', 'High-density Housing', '1500', 'Residential Property', 2000, 3000000, 32);

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
(61, 143, 12, 1),
(62, 144, 12, 1),
(63, 144, 9, 1);

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
(143, 12, 13, 8, 'Province 1', 'Daet', 'District 2', 'Bautista', '', 6, 100, '55   ', 'affidavit'),
(144, 12, 23, 0, 'Province 1', 'Daet', 'District 1', 'Kalamunding', '', 0, 302, '   ', 'affidavit, barangay');

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
-- Table structure for table `rpu_idnum`
--

CREATE TABLE `rpu_idnum` (
  `rpu_id` int(50) NOT NULL,
  `arp` int(50) NOT NULL,
  `pin` int(50) NOT NULL,
  `taxability` varchar(20) NOT NULL,
  `effectivity` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rpu_idnum`
--

INSERT INTO `rpu_idnum` (`rpu_id`, `arp`, `pin`, `taxability`, `effectivity`) VALUES
(45, 2147483647, 789456, 'exempt', '2023');

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
-- Indexes for table `rpu_idnum`
--
ALTER TABLE `rpu_idnum`
  ADD PRIMARY KEY (`rpu_id`);

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
-- AUTO_INCREMENT for table `district`
--
ALTER TABLE `district`
  MODIFY `district_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `faas`
--
ALTER TABLE `faas`
  MODIFY `faas_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `land`
--
ALTER TABLE `land`
  MODIFY `land_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `pO_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `p_info`
--
ALTER TABLE `p_info`
  MODIFY `p_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `region`
--
ALTER TABLE `region`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rpu_idnum`
--
ALTER TABLE `rpu_idnum`
  MODIFY `rpu_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `district`
--
ALTER TABLE `district`
  ADD CONSTRAINT `m_id` FOREIGN KEY (`m_id`) REFERENCES `municipality` (`m_id`) ON DELETE CASCADE;

--
-- Constraints for table `faas`
--
ALTER TABLE `faas`
  ADD CONSTRAINT `pro_id` FOREIGN KEY (`pro_id`) REFERENCES `p_info` (`p_id`);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
