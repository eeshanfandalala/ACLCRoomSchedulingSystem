-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2024 at 05:18 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aclc_room_scheduling`
--

-- --------------------------------------------------------

--
-- Table structure for table `room_tb`
--

CREATE TABLE `room_tb` (
  `room_id` int(11) NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `room_type` varchar(100) NOT NULL,
  `room_floor` varchar(100) NOT NULL,
  `room_building` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `room_tb`
--

INSERT INTO `room_tb` (`room_id`, `room_name`, `room_type`, `room_floor`, `room_building`) VALUES
(12, 'A201', 'Lecture', '2', 'A'),
(13, 'A202', 'Lecture', '2', 'A'),
(14, 'A203', 'Lecture', '2', 'A'),
(15, 'SLAB1', 'Laboratory', '2', 'A'),
(16, 'SLAB2', 'Laboratory', '2', 'A'),
(17, 'SLAB3', 'Laboratory', '2', 'A'),
(18, 'SLAB4', 'Laboratory', '2', 'A'),
(19, 'A301', 'Lecture', '3', 'A'),
(20, 'A302', 'Lecture', '3', 'A'),
(21, 'A303', 'Lecture', '3', 'A'),
(22, 'A304', 'Lecture', '3', 'A'),
(23, 'A305', 'Lecture', '3', 'A'),
(24, 'A306', 'Lecture', '3', 'A'),
(25, 'A307', 'Lecture', '3', 'A'),
(26, 'A401', 'Lecture', '4', 'A'),
(27, 'A402', 'Lecture', '4', 'A'),
(28, 'A403', 'Lecture', '4', 'A'),
(29, 'A404', 'Lecture', '4', 'A'),
(30, 'A405', 'Lecture', '4', 'A'),
(31, 'LAUNDRY LAB', 'Laboratory', '5', 'A'),
(32, 'SCIENCE LABORATORY', 'Laboratory', '5', 'A'),
(33, 'CULINARY LABORATORY', 'Laboratory', '5', 'A'),
(34, 'HOTEL ROOM', 'Laboratory', '5', 'A'),
(35, 'F & B RESTAURANT', 'Laboratory', '5', 'A');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `room_tb`
--
ALTER TABLE `room_tb`
  ADD PRIMARY KEY (`room_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `room_tb`
--
ALTER TABLE `room_tb`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
