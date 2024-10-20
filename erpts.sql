-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2024 at 04:16 PM
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
(1, 23432, 23423, 'Item 3', '(City)', 'Item 2', '(City)', 4234, 2342, '423423 4234 423423 23423', ''),
(2, 23432, 23423, 'Item 3', '(City)', 'Item 2', '(City)', 4234, 2342, '423423 4234 423423 23423', ''),
(3, 23432, 23423, 'Item 3', '(City)', 'Item 2', '(City)', 4234, 2342, '423423 4234 423423 23423', ''),
(4, 24234, 4234, 'Item 2', '(City)', 'Item 2', '(City)', 23432, 42342, '4234 4234 23423 23423', ''),
(5, 24234, 4234, 'Item 2', '(City)', 'Item 2', '(City)', 23432, 42342, '4234 4234 23423 23423', ''),
(6, 24234, 4234, 'Item 2', '(City)', 'Item 2', '(City)', 23432, 42342, '4234 4234 23423 23423', ''),
(7, 24234, 4234, 'Item 2', '(City)', 'Item 2', '(City)', 23432, 42342, '4234 4234 23423 23423', ''),
(8, 24234, 4234, 'Item 2', '(City)', 'Item 2', '(City)', 23432, 42342, '4234 4234 23423 23423', ''),
(9, 2342, 4234, 'Item 2', '(City)', 'Item 3', '(City)', 4234, 2342, '423423 4234 24342 23423', ''),
(10, 42354, 53245, 'Item 2', '(City)', 'Item 2', '(City)', 42345, 52345, '5345 5345 54345 34523', ''),
(11, 2342, 23423, 'Item 2', '(City)', 'Item 3', '(City)', 42342, 4234, '423432 4234 423423 23423', ''),
(12, 0, 0, 'Province', '(City)', 'District', '(City)', 0, 0, '   ', 'affidavit, barangay, land_tagg'),
(13, 0, 0, 'Province', '(City)', 'District', '(City)', 0, 0, '   ', ''),
(14, 0, 0, 'Province', '(City)', 'District', '(City)', 0, 0, '   ', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `p_info`
--
ALTER TABLE `p_info`
  ADD PRIMARY KEY (`p_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `p_info`
--
ALTER TABLE `p_info`
  MODIFY `p_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
