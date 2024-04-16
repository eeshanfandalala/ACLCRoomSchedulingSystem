-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2024 at 10:28 AM
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
-- Database: `aclc_room_scheduling`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance_tb`
--

CREATE TABLE `attendance_tb` (
  `attendance_id` int(11) NOT NULL,
  `attendance__date` datetime NOT NULL DEFAULT current_timestamp(),
  `attendance_status` varchar(100) NOT NULL,
  `attendance_confirmation` varchar(100) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `technical_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_tb`
--

CREATE TABLE `class_tb` (
  `class_id` int(11) NOT NULL,
  `class_courseStrand` varchar(255) NOT NULL,
  `class_year` varchar(255) NOT NULL,
  `class_section` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_tb`
--

CREATE TABLE `schedule_tb` (
  `schedule_id` int(11) NOT NULL,
  `schedule_time` time NOT NULL,
  `schedule_day` date NOT NULL,
  `schedule_semester` varchar(100) NOT NULL,
  `schedule_SY` varchar(100) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sd_tb`
--

CREATE TABLE `sd_tb` (
  `SD_id` int(11) NOT NULL,
  `SD_firstname` varchar(255) NOT NULL,
  `SD_lastname` varchar(255) NOT NULL,
  `SD_email` varchar(255) NOT NULL,
  `SD_password` varchar(255) NOT NULL,
  `SD_number` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject_tb`
--

CREATE TABLE `subject_tb` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_units` varchar(255) NOT NULL,
  `subject_description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_tb`
--

CREATE TABLE `teacher_tb` (
  `teacher_id` int(11) NOT NULL,
  `teacher_firstname` varchar(255) NOT NULL,
  `teacher_lastname` varchar(255) NOT NULL,
  `teacher_email` varchar(255) NOT NULL,
  `teacher_password` varchar(255) NOT NULL,
  `teacher_number` varchar(255) NOT NULL,
  `teacher_department` varchar(255) NOT NULL,
  `teacher_proficency` varchar(255) NOT NULL,
  `teacher_daysAvailable` varchar(255) NOT NULL,
  `SD_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `technical_tb`
--

CREATE TABLE `technical_tb` (
  `technical_id` int(11) NOT NULL,
  `technical_firstname` varchar(255) NOT NULL,
  `technical_lastname` varchar(255) NOT NULL,
  `technical_email` varchar(255) NOT NULL,
  `technical_password` varchar(255) NOT NULL,
  `technical_number` varchar(255) NOT NULL,
  `SD_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance_tb`
--
ALTER TABLE `attendance_tb`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `technical_id` (`technical_id`),
  ADD KEY `schedule_id` (`schedule_id`);

--
-- Indexes for table `class_tb`
--
ALTER TABLE `class_tb`
  ADD PRIMARY KEY (`class_id`);

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
-- Indexes for table `sd_tb`
--
ALTER TABLE `sd_tb`
  ADD PRIMARY KEY (`SD_id`);

--
-- Indexes for table `subject_tb`
--
ALTER TABLE `subject_tb`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `teacher_tb`
--
ALTER TABLE `teacher_tb`
  ADD PRIMARY KEY (`teacher_id`),
  ADD KEY `SD_id` (`SD_id`);

--
-- Indexes for table `technical_tb`
--
ALTER TABLE `technical_tb`
  ADD PRIMARY KEY (`technical_id`),
  ADD KEY `SD_id` (`SD_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance_tb`
--
ALTER TABLE `attendance_tb`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_tb`
--
ALTER TABLE `class_tb`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule_tb`
--
ALTER TABLE `schedule_tb`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sd_tb`
--
ALTER TABLE `sd_tb`
  MODIFY `SD_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject_tb`
--
ALTER TABLE `subject_tb`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teacher_tb`
--
ALTER TABLE `teacher_tb`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `technical_tb`
--
ALTER TABLE `technical_tb`
  MODIFY `technical_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
