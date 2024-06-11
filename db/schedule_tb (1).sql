-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2024 at 03:11 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aclc_room_scheduling2`
--

-- --------------------------------------------------------

--
-- Table structure for table `schedule_tb`
--

CREATE TABLE `schedule_tb` (
  `schedule_id` int(11) NOT NULL,
  `schedule_time_start` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `schedule_time_end` text NOT NULL,
  `schedule_day` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `schedule_semester` varchar(100) NOT NULL,
  `schedule_SY` varchar(100) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule_tb`
--

INSERT INTO `schedule_tb` (`schedule_id`, `schedule_time_start`, `schedule_time_end`, `schedule_day`, `schedule_semester`, `schedule_SY`, `teacher_id`, `class_id`, `subject_id`, `room_id`) VALUES
(1, '', '', 'momday', '', '', 0, 0, 0, 0),
(2, '', '', 'wednesday', '', '', 0, 0, 0, 0),
(3, '', '', 'friday', '', '', 0, 0, 0, 0),
(4, '10:30', '11:30', 'Monday', '1st', '2023-2024', 2, 1, 6, 1),
(5, '10:20', '11:20', 'Wednesday', '1st', '2023-2024', 2, 1, 6, 1),
(6, '10:20', '11:20', 'Thursday', '1st', '2023-2024', 2, 1, 6, 1),
(7, '12:00', '13:30', 'Monday', '1st', '2023-2024', 1, 1, 2, 3),
(9, '10:30', '13:00', 'Friday', '1st', '2023-2024', 1, 10, 11, 5),
(10, '11:00', '12:00', 'Tuesday', '1st', '2023-2024', 1, 1, 3, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `schedule_tb`
--
ALTER TABLE `schedule_tb`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `room_id` (`room_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `schedule_tb`
--
ALTER TABLE `schedule_tb`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
