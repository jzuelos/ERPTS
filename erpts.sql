-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2024 at 03:28 PM
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
  `brgy_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brgy`
--

INSERT INTO `brgy` (`brgy_id`, `brgy_name`) VALUES
(1, 'Anameam'),
(2, 'Awitan'),
(3, 'Baay'),
(4, 'Bagacay'),
(5, 'Bagong Silang I'),
(6, 'Bagong Silang II'),
(7, 'Bakiad'),
(8, 'Bautista'),
(9, 'Bayabas'),
(10, 'Bayan-bayan'),
(11, 'Benit'),
(12, 'Anahaw'),
(13, 'Gumamela'),
(14, 'San Francisco'),
(15, 'Kalamunding'),
(16, 'Bulhao'),
(17, 'Cabatuhan'),
(18, 'Cabusay'),
(19, 'Calabasa'),
(20, 'Canapawan'),
(21, 'Daguit'),
(22, 'Dalas'),
(23, 'Dumagmang'),
(24, 'Exciban'),
(25, 'Fundado'),
(26, 'Guinacutan'),
(27, 'Guisican'),
(28, 'Iberica'),
(29, 'Lugui'),
(30, 'Mabilo I'),
(31, 'Mabilo II'),
(32, 'Macogon'),
(33, 'Mahawan-hawan'),
(34, 'Malangcao-Basud'),
(35, 'Malasugui'),
(36, 'Malatap'),
(37, 'Malaya'),
(38, 'Malibago'),
(39, 'Maot'),
(40, 'Masalong'),
(41, 'Matanlang'),
(42, 'Napaod'),
(43, 'Pag-Asa'),
(44, 'Pangpang'),
(45, 'Pinya'),
(46, 'San Antonio'),
(47, 'Santa Cruz'),
(48, 'Bagong Silang III'),
(49, 'Submakin'),
(50, 'Talobatib'),
(51, 'Tigbinan'),
(52, 'Tulay Na Lupa');

-- --------------------------------------------------------

--
-- Table structure for table `faas`
--

CREATE TABLE `faas` (
  `faas_id` int(50) NOT NULL,
  `propertyowner_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`propertyowner_id`)),
  `pro_id` int(50) NOT NULL,
  `land_id` int(50) DEFAULT NULL,
  `plants_id` int(50) DEFAULT NULL,
  `valuation_id` int(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faas`
--

INSERT INTO `faas` (`faas_id`, `propertyowner_id`, `pro_id`, `land_id`, `plants_id`, `valuation_id`) VALUES
(13, '34', 125, NULL, NULL, NULL),
(14, '37', 126, NULL, NULL, NULL),
(15, '38', 126, NULL, NULL, NULL),
(16, '[41,42]', 128, NULL, NULL, NULL);

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
(7, 'Renz', 'Dionela', 'Dioneda', 4234, '32423', 'asdfasd', 'fasdf', 'asdf', 'fasdf', 'fdasf', 'Telephone: , Fax: , Email: , Website: ');

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
(42, 128, 7, 1);

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
  `house_tag_no` int(10) NOT NULL,
  `land_area` int(50) NOT NULL,
  `desc_land` varchar(50) NOT NULL,
  `documents` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `p_info`
--

INSERT INTO `p_info` (`p_id`, `ownID_Fk`, `house_no`, `block_no`, `province`, `city`, `district`, `barangay`, `house_tag_no`, `land_area`, `desc_land`, `documents`) VALUES
(108, 1, 3123, 0, 'Province 1', 'Daet', 'District 1', 'Bautista', 3123, 312, '   ', 'affidavit'),
(111, 1, 412341, 0, 'Province 2', 'Daet', 'District 1', 'Kalamunding', 423, 4324, '   ', 'affidavit, barangay'),
(115, 7, 4234, 4234, 'Province 1', 'Labo', 'District 1', 'Kalamunding', 123, 3123, '   ', 'affidavit'),
(125, 7, 4234, 432, 'Province 1', 'Labo', 'District 1', 'Bautista', 0, 4324, '   ', 'barangay'),
(126, 7, 3123, 31231, 'Province 2', 'Labo', 'District 1', 'Bautista', 3123, 3123, '   ', 'affidavit'),
(128, 7, 3123, 0, 'Province 2', 'Daet', 'District 1', 'Kalamunding', 312, 312, '   ', 'affidavit');

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
  `user_type` varchar(20) NOT NULL DEFAULT 'To Be Fixed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `last_name`, `first_name`, `middle_name`, `gender`, `birthdate`, `marital_status`, `tin`, `house_number`, `street`, `barangay`, `district`, `municipality`, `province`, `contact_number`, `email`, `status`, `user_type`) VALUES
(6, 'username', '$2y$10$hUAqqmcCIDXnVvR81hmjuO7r.2x3tnlKu6yruJFKjr6LnIoPgnRK.', 'name', 'user', '', 'Male', '2003-11-14', 'Single', '1234', '1', '1', '1', '1', '1', '1', '1', 'testing@testing.com', 0, 'To Be Fixed');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brgy`
--
ALTER TABLE `brgy`
  ADD PRIMARY KEY (`brgy_id`);

--
-- Indexes for table `faas`
--
ALTER TABLE `faas`
  ADD PRIMARY KEY (`faas_id`),
  ADD KEY `propertyowner_id` (`propertyowner_id`(768)),
  ADD KEY `pro_id` (`pro_id`);

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
  MODIFY `brgy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `faas`
--
ALTER TABLE `faas`
  MODIFY `faas_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `owners_tb`
--
ALTER TABLE `owners_tb`
  MODIFY `own_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `propertyowner`
--
ALTER TABLE `propertyowner`
  MODIFY `pO_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `p_info`
--
ALTER TABLE `p_info`
  MODIFY `p_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `faas`
--
ALTER TABLE `faas`
  ADD CONSTRAINT `pro_id` FOREIGN KEY (`pro_id`) REFERENCES `p_info` (`p_id`) ON DELETE CASCADE;

--
-- Constraints for table `p_info`
--
ALTER TABLE `p_info`
  ADD CONSTRAINT `ownID_FK` FOREIGN KEY (`ownID_Fk`) REFERENCES `owners_tb` (`own_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
