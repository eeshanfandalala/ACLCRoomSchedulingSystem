-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2024 at 08:30 AM
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
  `class_section` varchar(255) NOT NULL,
  `class_department` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_tb`
--

INSERT INTO `class_tb` (`class_id`, `class_courseStrand`, `class_year`, `class_section`, `class_department`) VALUES
(1, 'BSIT', '3', 'A', 'IT'),
(2, 'BSIT', '2', 'A', 'IT'),
(4, 'BSIT', '2', 'B', 'IT');

-- --------------------------------------------------------

--
-- Table structure for table `department_tb`
--

CREATE TABLE `department_tb` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department_tb`
--

INSERT INTO `department_tb` (`department_id`, `department_name`) VALUES
(1, 'Computer of Computer Studies'),
(2, 'HM department');

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

--
-- Dumping data for table `room_tb`
--

INSERT INTO `room_tb` (`room_id`, `room_name`, `room_type`, `room_floor`, `room_building`) VALUES
(1, 'B201', 'lecture', '2', 'A'),
(2, 'B202', 'lecture', '2', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `schedule_tb`
--

CREATE TABLE `schedule_tb` (
  `schedule_id` int(11) NOT NULL,
  `schedule_time` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`schedule_time`)),
  `schedule_day` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`schedule_day`)),
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

INSERT INTO `schedule_tb` (`schedule_id`, `schedule_time`, `schedule_day`, `schedule_semester`, `schedule_SY`, `teacher_id`, `class_id`, `subject_id`, `room_id`) VALUES
(1, '{\"start\":\"08:41\",\"end\":\"09:41\"}', '[\"Monday\",\"Wednesday\"]', '', '', 1, 0, 2, 1),
(2, '{\"start\":\"\",\"end\":\"\"}', '[]', '', '', 0, 0, 0, 0),
(3, '{\"start\":\"\",\"end\":\"\"}', '[]', '', '', 0, 0, 0, 0),
(4, '{\"start\":\"\",\"end\":\"\"}', '[]', '', '', 0, 0, 0, 0),
(5, '{\"start\":\"\",\"end\":\"\"}', '[]', '', '', 0, 0, 0, 0),
(6, '{\"start\":\"\",\"end\":\"\"}', '[]', '', '', 0, 0, 0, 0),
(7, '{\"start\":\"\",\"end\":\"\"}', '[]', '2nd', '2024-2025', 0, 1, 0, 0),
(8, '{\"start\":\"\",\"end\":\"\"}', '[]', '', '', 0, 0, 0, 0),
(9, '{\"start\":\"09:45\",\"end\":\"10:45\"}', '[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\"]', '1st', '2024-2025', 1, 2, 2, 1),
(10, '{\"start\":\"13:00\",\"end\":\"14:30\"}', '[\"Monday\",\"Wednesday\",\"Friday\"]', '2nd', '2024-2025', 1, 4, 2, 1);

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
  `SD_number` varchar(255) NOT NULL,
  `SD_pic` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sd_tb`
--

INSERT INTO `sd_tb` (`SD_id`, `SD_firstname`, `SD_lastname`, `SD_email`, `SD_password`, `SD_number`, `SD_pic`) VALUES
(1, 'Carry', 'Jaucian', 'SD@gmail.com', '$2y$10$aW4YklCX1M1j9WNJyT6tUeHS70i2UwqNQd10KTV99pXtWrVlUaqNS', '123456789', 'user.png');

-- --------------------------------------------------------

--
-- Table structure for table `subject_tb`
--

CREATE TABLE `subject_tb` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_units` varchar(255) NOT NULL,
  `subject_description` varchar(255) NOT NULL,
  `subject_department` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_tb`
--

INSERT INTO `subject_tb` (`subject_id`, `subject_name`, `subject_units`, `subject_description`, `subject_department`) VALUES
(1, 'ITELCTIVE', '3', 'Major Elective 1', 'HM'),
(2, 'Data com 1', '3', 'networking', 'IT');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_tb`
--

CREATE TABLE `teacher_tb` (
  `teacher_id` int(11) NOT NULL,
  `teacher_name` varchar(255) NOT NULL,
  `teacher_email` varchar(255) NOT NULL,
  `teacher_password` varchar(255) NOT NULL,
  `teacher_number` varchar(255) NOT NULL,
  `teacher_department` varchar(200) NOT NULL,
  `teacher_proficency` varchar(255) NOT NULL,
  `teacher_daysAvailable` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `teacher_pic` varchar(100) NOT NULL,
  `SD_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_tb`
--

INSERT INTO `teacher_tb` (`teacher_id`, `teacher_name`, `teacher_email`, `teacher_password`, `teacher_number`, `teacher_department`, `teacher_proficency`, `teacher_daysAvailable`, `status`, `teacher_pic`, `SD_id`) VALUES
(1, 'admin1234', 'admin@gmail.com', '$2y$10$E7uNZJwCzWq3ryPai.Ncd.ab.VHHFvIyM0l6Dex9bCj7KUWw10526', '923023940', 'IT', 'teacher', '', 1, 'Untitled.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `technical_tb`
--

CREATE TABLE `technical_tb` (
  `technical_id` int(11) NOT NULL,
  `technical_name` varchar(255) NOT NULL,
  `technical_email` varchar(255) NOT NULL,
  `technical_password` varchar(255) NOT NULL,
  `technical_number` varchar(255) NOT NULL,
  `status` int(1) NOT NULL,
  `SD_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `technical_tb`
--

INSERT INTO `technical_tb` (`technical_id`, `technical_name`, `technical_email`, `technical_password`, `technical_number`, `status`, `SD_id`) VALUES
(1, 'admin2', 'christian42k02@gmail.com', '$2y$10$tof.xkt5ZSUYwsnQGePc6eXcOwQcbZsi1cSx7Ge8hkc3wU.dMIFjq', '', 0, 0);

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
-- Indexes for table `department_tb`
--
ALTER TABLE `department_tb`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `room_tb`
--
ALTER TABLE `room_tb`
  ADD PRIMARY KEY (`room_id`);

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
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `department_id` (`subject_department`);

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
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `department_tb`
--
ALTER TABLE `department_tb`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `room_tb`
--
ALTER TABLE `room_tb`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `schedule_tb`
--
ALTER TABLE `schedule_tb`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sd_tb`
--
ALTER TABLE `sd_tb`
  MODIFY `SD_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subject_tb`
--
ALTER TABLE `subject_tb`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `teacher_tb`
--
ALTER TABLE `teacher_tb`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `technical_tb`
--
ALTER TABLE `technical_tb`
  MODIFY `technical_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
