-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2024 at 12:51 PM
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
-- Table structure for table `subject_tb`
--

CREATE TABLE `subject_tb` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_units` varchar(255) NOT NULL,
  `subject_description` varchar(255) NOT NULL,
  `subject_department` varchar(200) NOT NULL,
  `subject_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_tb`
--

INSERT INTO `subject_tb` (`subject_id`, `subject_name`, `subject_units`, `subject_description`, `subject_department`, `subject_type`) VALUES
(4, 'Calculus 1', '3', 'MATH6100', 'General', 'Lecture'),
(5, 'Computing Fundamentals', '3', 'ITE6101', 'Computer Studies', 'Laboratory'),
(6, 'Computer Programming 1', '3', 'ITE6102', 'Computer Studies', 'Laboratory'),
(7, 'Understanding the Self', '3', 'GE6100', 'General', 'Lecture'),
(8, 'Purposive Communication 1', '3', 'GE6106', 'General', 'Lecture'),
(9, 'Euthenics', '1', 'ETHNS6101', 'General', 'Lecture'),
(10, 'Physical Fitness', '2', 'PHYED6101', 'General', 'Lecture'),
(11, 'National Service Training Program 1', '3', 'NSTP6101', 'General', 'Lecture'),
(12, 'Purposive Communication 2', '3', 'ENGL6100', 'General', 'Lecture'),
(13, 'The Contemporary World', '3', 'GE6102', 'General', 'Lecture'),
(14, 'Ethics', '3', 'GE6107', 'General', 'Lecture'),
(15, 'Computer Programming 2', '3', 'ITE6104', 'Computer Studies', 'Laboratory'),
(16, 'Discrete Mathematics', '3', 'CS6100', 'Computer Studies', 'Lecture'),
(17, 'Mathematics in the Modern World', '3', 'GE6114', 'General', 'Lecture'),
(18, 'Euthenics 2', '1', 'ETHNS6102', 'General', 'Lecture'),
(19, 'Rhythmic Activities', '2', 'PHYED6102', 'General', 'Lecture'),
(20, 'National Service Training Program 2', '3', 'NSTP6102', 'General', 'Lecture'),
(21, 'Introduction to Human Computer Interaction', '3', 'IT6200', 'Computer Studies', 'Laboratory'),
(22, 'Calculus-based Physics 1', '4', 'NSCI6100', 'General', 'Lecture'),
(23, 'Data Communications and Networking 1', '3', 'IT6201', 'Computer Studies', 'Laboratory'),
(24, 'Data Structures and Algorithms', '3', 'ITE6201', 'Computer Studies', 'Laboratory'),
(25, 'Database Management System 1', '3', 'IT6202', 'Computer Studies', 'Laboratory'),
(26, 'Science, Technology and Society', '3', 'GE6116', 'General', 'Lecture'),
(27, 'Data Analysis', '3', 'MATH6200', 'Computer Studies', 'Lecture'),
(28, 'Individual/Dual Sports', '2', 'PHYED6103', 'General', 'Lecture'),
(29, 'Database Management System 2', '3', 'IT6203', 'Computer Studies', 'Laboratory'),
(30, 'Application Development and Emerging Technology', '3', 'ITE6200', 'Computer Studies', 'Laboratory'),
(31, 'Information Management', '3', 'ITE6220', 'Computer Studies', 'Laboratory'),
(32, 'Data Communications and Networking 2', '3', 'IT6223', 'Computer Studies', 'Laboratory'),
(33, 'Information Assurance and Security 1', '3', 'IT6205', 'Computer Studies', 'Laboratory'),
(34, 'Wika, Lipunan at Kultura', '3', 'FILI6101', 'General', 'Lecture'),
(35, 'Calculus-based Physics 2', '4', 'NSCI6101', 'General', 'Lecture'),
(36, 'Quanitative Methods', '3', 'IT6210', 'Computer Studies', 'Lecture'),
(37, 'Team Sports', '2', 'PHYED6200', 'General', 'Lecture'),
(38, 'Information Assurance and Security 2', '3', 'IT6206', 'Computer Studies', 'Laboratory'),
(39, 'Data Communications and Networking 3', '3', 'IT6207', 'Computer Studies', 'Laboratory'),
(40, 'Principles of Operating Systems and its Application', '3', 'CS6206', 'Computer Studies', 'Laboratory'),
(41, 'Social and Professional Issues', '3', 'ITE6202', 'Computer Studies', 'Lecture'),
(42, 'Kritikal na Pagbasa, Pagsusulat at Pagsasalita', '3', 'FILI6201', 'General', 'Lecture'),
(43, 'Introduction to Multimedia', '3', 'IT6209', 'Computer Studies', 'Laboratory'),
(44, 'System Integration and Architecture 1', '3', 'IT6208', 'Computer Studies', 'Laboratory'),
(45, 'IT Major Elective 1', '3', '', 'Computer Studies', 'Laboratory'),
(46, 'Data Communications and Networking 4', '3', 'IT6300', 'Computer Studies', 'Laboratory'),
(47, 'System Administration and Maintenance', '3', 'IT6301', 'Computer Studies', 'Laboratory'),
(48, 'Cloud Computing and the Internet of Things', '3', 'ITE6300', 'Computer Studies', 'Laboratory'),
(49, 'Application Lifecycle Management', '3', 'CS6302', 'Computer Studies', 'Laboratory'),
(50, 'Current Trends and Issues', '3', 'COMP6103', 'Computer Studies', 'Laboratory'),
(51, 'Integrative Programming and Technology 1', '3', 'IT6302', 'Computer Studies', 'Lecture'),
(52, 'Pagsasaling Pampanitikan', '3', 'FILI6301', 'General', 'Lecture'),
(53, 'IT Major Elective 2', '3', '', 'Computer Studies', 'Laboratory'),
(54, 'IT Practicum (486 Hours)', '6', 'IT6397', 'Computer Studies', 'Lecture'),
(55, 'Software Engineering 1', '3', 'CS6209', 'Computer Studies', 'Laboratory'),
(56, 'Load Testing', '3', 'CS6303', 'Computer Studies', 'Laboratory'),
(57, 'Readings in Philippine History', '3', 'GE6105', 'General', 'Lecture'),
(58, 'IT Capstone Project 1', '3', 'IT6398', 'Computer Studies', 'Lecture'),
(59, 'IT Major Elective 3', '3', '', 'Computer Studies', 'Laboratory'),
(60, 'Technopreneurship', '3', 'ITE6301', 'Computer Studies', 'Lecture'),
(61, 'Art Appreciation', '3', 'GE6115', 'General', 'Lecture'),
(62, 'Life and Work of Rizal', '3', 'GE6301', 'General', 'Lecture'),
(63, 'Unified Functional Testing', '3', 'CS6306', 'Computer Studies', 'Laboratory'),
(64, 'IT Capstone Project 2', '3', 'IT6399', 'Computer Studies', 'Laboratory'),
(65, 'IT Major Elective 4', '3', '', 'Computer Studies', 'Laboratory');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `subject_tb`
--
ALTER TABLE `subject_tb`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `department_id` (`subject_department`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `subject_tb`
--
ALTER TABLE `subject_tb`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
