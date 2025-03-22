-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 21, 2025 at 02:15 PM
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
(53, '001', 'Bulala', 'Active', 0),
(54, '002', 'bautista', 'Active', 0),
(55, '004', 'fjsdfpnadf', 'Active', 0),
(56, '004', 'tayabas', 'Active', 0),
(57, '001', 'Bulala', 'Active', 0),
(58, '001', 'Bulala', 'Active', 0);

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
(11, '1234', 'District 2', 'Active', 2),
(12, '13123', 'District 2', 'Active', 2);

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
(18, '[43]', 129, 44, NULL, NULL, NULL),
(19, '[44]', 130, NULL, NULL, NULL, NULL),
(20, '[45]', 131, NULL, NULL, NULL, NULL),
(21, '[46,47]', 132, NULL, NULL, NULL, NULL),
(22, '[48]', 133, NULL, NULL, NULL, NULL),
(23, '[49]', 134, NULL, NULL, NULL, NULL),
(24, '[50]', 135, NULL, NULL, NULL, NULL),
(25, '[51]', 136, NULL, NULL, NULL, NULL),
(26, '[52,53]', 137, NULL, NULL, NULL, NULL);

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
(1, 4611, 'Sta. Elena', 'Active', 5),
(2, 4600, 'Daet', 'Active', 5);

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
(1, 'zuelos', '', 'john lloyd', 0, '', 'luzaragga', 'kalamunding', '2', 'labo', 'camarines norte', ''),
(7, 'Renz', 'Dionela', 'Dioneda', 4234, '32423', '5', 'Bautista', 'District One', 'Sta Elena', 'Camarines Norte', 'Telephone: , Fax: , Email: , Website: ');

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
(1, 126, 7, 0),
(14, 108, 1, 1),
(17, 111, 1, 1),
(23, 115, 1, 1),
(33, 125, 7, 0),
(41, 128, 1, 1),
(42, 128, 7, 1),
(43, 129, 7, 1),
(44, 130, 1, 1),
(45, 131, 1, 1),
(46, 132, 7, 1),
(47, 132, 1, 1),
(48, 133, 7, 1),
(49, 134, 7, 1),
(50, 135, 7, 1),
(51, 136, 1, 1),
(52, 137, 7, 1),
(53, 137, 1, 1);

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
(129, 7, 12345, 0, 'Province 1', 'Labo', 'District 2', 'Bautista', '', 42355, 234233, '   ', 'affidavit, barangay'),
(130, 1, 1234, 0, 'Province 1', 'Labo', 'District 1', 'Bautista', '', 0, 4324, '   ', 'barangay'),
(131, 1, 42342, 0, 'Province 1', 'Labo', 'District 2', 'Bautista', '', 0, 4234, '   ', 'barangay'),
(132, 7, 432, 0, 'Province 1', 'Labo', 'District 1', 'Kalamunding', '', 0, 23423, '   ', 'barangay'),
(133, 7, 4324, 0, 'Province 1', 'Labo', 'District 1', 'Kalamunding', '', 0, 4234, '   ', 'barangay'),
(134, 7, 423, 0, 'Province 1', 'Labo', 'District 2', 'Kalamunding', '', 0, 4324, '   ', 'barangay'),
(135, 7, 4234, 0, 'Province 1', 'Daet', 'District 2', 'Kalamunding', '', 0, 4234, '   ', 'barangay'),
(136, 1, 324, 0, 'Province 1', 'Labo', 'District 2', 'Kalamunding', '', 0, 4234, '   ', 'land_tagging'),
(137, 7, 34534, 0, 'Province 1', 'Daet', 'District 1', 'Kalamunding', '', 0, 4234, '   ', 'affidavit, barangay');

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
(38, 12314514, 12312412, 'special', '1234'),
(39, 12354, 12345, 'special', '12325'),
(40, 123545, 123115, 'exempt', '1234'),
(41, 213423, 25234, 'exempt', '23453'),
(42, 23445, 235256, 'special', '1231'),
(43, 23544, 23523, 'exempt', '4235'),
(44, 1234567, 44, 'special', '4235532');

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
(6, 'username', '$2y$10$hUAqqmcCIDXnVvR81hmjuO7r.2x3tnlKu6yruJFKjr6LnIoPgnRK.', 'name', 'user', '', 'Male', '2003-11-14', 'Single', '1234', '1', '1', '1', '1', '1', '1', '1', 'testing@testing.com', 0, 'user'),
(8, 'RIP_FISHY12', '$2y$10$smEhcMQrkEwgF/Whm1BrDeT0ru.898kqGKwEjVl1HhkR7Bpj/vdP.', 'Zuelos', 'Jomel', 'Villacruel', 'Female', '2012-12-19', 'Married', '32423423', '32', 'Luzaragga', 'Kalamunding', '2', 'Labo', 'Camarines Norte', '09923648721', 'sbjomel19@gmail.com', 0, 'admin');

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
  MODIFY `brgy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `district`
--
ALTER TABLE `district`
  MODIFY `district_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `faas`
--
ALTER TABLE `faas`
  MODIFY `faas_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `municipality`
--
ALTER TABLE `municipality`
  MODIFY `m_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `owners_tb`
--
ALTER TABLE `owners_tb`
  MODIFY `own_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `propertyowner`
--
ALTER TABLE `propertyowner`
  MODIFY `pO_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `p_info`
--
ALTER TABLE `p_info`
  MODIFY `p_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `region`
--
ALTER TABLE `region`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rpu_idnum`
--
ALTER TABLE `rpu_idnum`
  MODIFY `rpu_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
-- Constraints for table `municipality`
--
ALTER TABLE `municipality`
  ADD CONSTRAINT `municipality_ibfk_1` FOREIGN KEY (`r_id`) REFERENCES `region` (`r_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `p_info`
--
ALTER TABLE `p_info`
  ADD CONSTRAINT `ownID_FK` FOREIGN KEY (`ownID_Fk`) REFERENCES `owners_tb` (`own_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
