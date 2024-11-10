-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2024 at 03:58 AM
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
(2, 'melanie', '', 'zuelos', 0, '', 'suzara', 'gumamela', '2', 'daet', 'camarines sur', ''),
(3, 'emel', 'v.', 'zuelos', 0, '', 'luzaragga', 'dalas', '2', 'labo', 'camarines sur', ''),
(4, 'jennifer', 'v.', 'zuelos', 0, '', 'luzaragga', 'gumamela', '2', 'labo', 'camarines sur', ''),
(5, 'zuelos', 'v.', 'jomel', 0, '', 'suzara', 'gumamela', '2', 'labo', 'camarines sur', ''),
(6, 'melchor', 'v', 'zuelos', 0, '', 'suzara', 'kalamunding', '2', 'daet', 'camarines norte', '');

-- --------------------------------------------------------

--
-- Table structure for table `p_info`
--

CREATE TABLE `p_info` (
  `p_id` int(10) NOT NULL,
  `house_no` int(10) NOT NULL,
  `block_no` int(10) NOT NULL,
  `province` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `district` varchar(30) NOT NULL,
  `barangay` varchar(30) NOT NULL,
  `house_tag_no` int(10) NOT NULL,
  `land_area` int(50) NOT NULL,
  `desc_land` varchar(50) NOT NULL,
  `documents` varchar(30) NOT NULL,
  `ownId_Fk` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `p_info`
--

INSERT INTO `p_info` (`p_id`, `house_no`, `block_no`, `province`, `city`, `district`, `barangay`, `house_tag_no`, `land_area`, `desc_land`, `documents`, `ownId_Fk`) VALUES
(76, 4234, 0, 'Province 1', 'Labo', 'District 1', 'Kalamunding', 0, 4234, '   ', 'affidavit, barangay', 2),
(77, 4234, 0, 'Province 1', 'Labo', 'District 1', 'Bautista', 0, 4234, '   ', 'affidavit, barangay', 1),
(80, 423, 0, 'Province 1', 'Labo', 'District 1', 'Bautista', 0, 423, '   ', 'affidavit, barangay', 1),
(81, 2147483647, 423, 'Province 2', 'Daet', 'District 1', 'Bautista', 0, 42342, '   ', 'affidavit, barangay, land_tagg', 1),
(82, 43, 0, 'Province 2', 'Labo', 'District 1', 'Bautista', 423, 33, '   ', 'barangay', 3),
(83, 423, 3, 'Province 1', 'Labo', 'District 1', 'Bautista', 432, 3242, '   ', 'affidavit', 5),
(84, 4234, 0, 'Province 1', 'Labo', 'District 2', 'Kalamunding', 423, 897, '   ', 'barangay', 1);

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
-- Indexes for table `owners_tb`
--
ALTER TABLE `owners_tb`
  ADD PRIMARY KEY (`own_id`);

--
-- Indexes for table `p_info`
--
ALTER TABLE `p_info`
  ADD PRIMARY KEY (`p_id`),
  ADD KEY `fk_owner` (`ownId_Fk`);

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
-- AUTO_INCREMENT for table `owners_tb`
--
ALTER TABLE `owners_tb`
  MODIFY `own_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `p_info`
--
ALTER TABLE `p_info`
  MODIFY `p_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `p_info`
--
ALTER TABLE `p_info`
  ADD CONSTRAINT `fk_owner` FOREIGN KEY (`ownId_Fk`) REFERENCES `owners_tb` (`own_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
