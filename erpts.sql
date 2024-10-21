-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2024 at 04:15 PM
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
  `own_name` varchar(50) NOT NULL,
  `tin_no` int(20) NOT NULL,
  `house_no` varchar(20) NOT NULL,
  `street` varchar(30) NOT NULL,
  `barangay` varchar(30) NOT NULL,
  `district` varchar(20) NOT NULL,
  `city` varchar(20) NOT NULL,
  `province` varchar(20) NOT NULL,
  `own_info` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `documents` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `p_info`
--

INSERT INTO `p_info` (`p_id`, `house_no`, `block_no`, `province`, `city`, `district`, `barangay`, `house_tag_no`, `land_area`, `desc_land`, `documents`) VALUES
(16, 4234, 2345234, 'Item 2', '(City)', 'Item 2', '(City)', 234, 42453, '2342 4234 42342 ', 'affidavit, barangay, land_tagg');

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
  ADD PRIMARY KEY (`p_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `owners_tb`
--
ALTER TABLE `owners_tb`
  MODIFY `own_id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `p_info`
--
ALTER TABLE `p_info`
  MODIFY `p_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
