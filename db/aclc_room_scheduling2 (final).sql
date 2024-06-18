-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2024 at 08:22 AM
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
-- Table structure for table `archived_class_tb`
--

CREATE TABLE `archived_class_tb` (
  `class_id` int(11) NOT NULL,
  `class_courseStrand` varchar(100) NOT NULL,
  `class_year` varchar(100) NOT NULL,
  `class_section` varchar(100) NOT NULL,
  `class_department` int(11) DEFAULT NULL,
  `class_standing` varchar(100) NOT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archived_department_tb`
--

CREATE TABLE `archived_department_tb` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archived_room_tb`
--

CREATE TABLE `archived_room_tb` (
  `room_id` int(11) NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `room_type` varchar(100) NOT NULL,
  `room_floor` varchar(100) NOT NULL,
  `room_building` varchar(100) NOT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archived_schedule_tb`
--

CREATE TABLE `archived_schedule_tb` (
  `schedule_id` int(11) NOT NULL,
  `schedule_time_start` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `schedule_time_end` text NOT NULL,
  `schedule_day` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `schedule_semester` varchar(100) NOT NULL,
  `schedule_SY` varchar(100) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `delete_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archived_subject_tb`
--

CREATE TABLE `archived_subject_tb` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_units` varchar(255) NOT NULL,
  `subject_description` varchar(255) NOT NULL,
  `subject_department` int(11) DEFAULT NULL,
  `subject_type` varchar(100) NOT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
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
  `class_department` int(11) DEFAULT NULL,
  `class_standing` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_tb`
--

INSERT INTO `class_tb` (`class_id`, `class_courseStrand`, `class_year`, `class_section`, `class_department`, `class_standing`) VALUES
(1, 'BSIT', '1', 'A', 2, 'College'),
(2, 'BSIT', '2', 'A', 2, 'College'),
(3, 'BSIT', '3', 'A', 2, 'College'),
(4, 'BSIT', '4', 'A', 2, 'College'),
(5, 'BSBA', '1', 'A', 3, 'College'),
(6, 'STEM', '11', 'A', 1, 'SHS'),
(7, 'ABM', '12', 'A', 2, 'SHS');

--
-- Triggers `class_tb`
--
DELIMITER $$
CREATE TRIGGER `before_class_delete` BEFORE DELETE ON `class_tb` FOR EACH ROW BEGIN
INSERT INTO archived_class_tb (class_id, class_courseStrand, class_year, class_section, class_department, class_standing, deleted_at) VALUES (OLD.class_id, OLD.class_courseStrand, OLD.class_year, OLD.class_section, OLD.class_department, OLD.class_standing, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `prevent_duplicate_class_update` BEFORE UPDATE ON `class_tb` FOR EACH ROW BEGIN
    DECLARE duplicate_count INT;

    -- Check for existing duplicate combination
    SELECT COUNT(*)
    INTO duplicate_count
    FROM class_tb
    WHERE class_courseStrand = NEW.class_courseStrand
      AND class_year = NEW.class_year
      AND class_section = NEW.class_section
      AND class_department = NEW.class_department
      AND class_standing = NEW.class_standing
      AND class_id != OLD.class_id;  -- Exclude the current row being updated

    -- If a duplicate is found, raise an error
    IF duplicate_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Update prevented: A row with the same class_courseStrand, class_year, class_section, class_department, and class_standing already exists.';
    END IF;
END
$$
DELIMITER ;

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
(1, 'General'),
(2, 'Computer Studies'),
(3, 'Business Administration'),
(4, 'Hospitality Management');

--
-- Triggers `department_tb`
--
DELIMITER $$
CREATE TRIGGER `before_department_delete` BEFORE DELETE ON `department_tb` FOR EACH ROW BEGIN
    INSERT INTO archived_department_tb (department_id, department_name, deleted_at)
    VALUES (OLD.department_id, OLD.department_name, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `prevent_duplicate_department_update` BEFORE UPDATE ON `department_tb` FOR EACH ROW BEGIN
    DECLARE duplicate_count INT;

    -- Check for duplicate department names excluding the current row
    SELECT COUNT(*) INTO duplicate_count
    FROM department_tb
    WHERE department_name = NEW.department_name
      AND department_id != OLD.department_id;

    -- If duplicates are found, signal an error
    IF duplicate_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Update prevented: A department with the same name already exists.';
    END IF;
END
$$
DELIMITER ;

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
-- Triggers `room_tb`
--
DELIMITER $$
CREATE TRIGGER `before_room_delete` BEFORE DELETE ON `room_tb` FOR EACH ROW BEGIN
    INSERT INTO archived_room_tb (room_id, room_name, room_type, room_floor, room_building, deleted_at)
    VALUES (OLD.room_id, OLD.room_name, OLD.room_type, OLD.room_floor, OLD.room_building, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `prevent_duplicate_room_update` BEFORE UPDATE ON `room_tb` FOR EACH ROW BEGIN
    DECLARE duplicate_count INT;
 	SELECT COUNT(*)
    INTO duplicate_count
    FROM room_tb
    WHERE room_name = NEW.room_name
    	AND room_type = NEW.room_type
        AND room_floor = NEW.room_floor
        AND room_building = NEW.room_building
        AND room_id != OLD.room_id;
    
    IF duplicate_count > 0 THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Update prevented: A row with the same data already exist.';
    END IF;
    
 END
$$
DELIMITER ;

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
  `teacher_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule_tb`
--

INSERT INTO `schedule_tb` (`schedule_id`, `schedule_time_start`, `schedule_time_end`, `schedule_day`, `schedule_semester`, `schedule_SY`, `teacher_id`, `class_id`, `subject_id`, `room_id`) VALUES
(1, '13:30', '15:00', 'Monday', '1st', '2023-2024', 4, 2, 24, 15),
(2, '13:30', '15:00', 'Wednesday', '1st', '2023-2024', 4, 2, 24, 15),
(3, '13:30', '15:00', 'Friday', '1st', '2023-2024', 4, 2, 24, 15),
(4, '08:30', '09:30', 'Monday', '1st', '2023-2024', 1, 2, 4, 12),
(5, '08:00', '09:00', 'Tuesday', '1st', '2023-2024', 4, 2, 4, 12),
(6, '08:00', '09:00', 'Saturday', '1st', '2023-2024', 4, 2, 4, 12),
(7, '20:00', '21:30', 'Wednesday', '1st', '2023-2024', 4, 1, 6, 16),
(8, '20:00', '21:30', 'Friday', '1st', '2023-2024', 4, 1, 6, 16);

--
-- Triggers `schedule_tb`
--
DELIMITER $$
CREATE TRIGGER `before_schedule_delete` BEFORE DELETE ON `schedule_tb` FOR EACH ROW BEGIN
	INSERT INTO archived_schedule_tb (schedule_id, schedule_time_start, schedule_time_end, schedule_day, schedule_semester, schedule_SY, teacher_id, class_id, subject_id, room_id, delete_at) VALUES (OLD.schedule_id, OLD.schedule_time_start, OLD.schedule_time_end, OLD.schedule_day, OLD.schedule_semester, OLD.schedule_SY, OLD.teacher_id, OLD.class_id, OLD.subject_id, OLD.room_id, NOW());
END
$$
DELIMITER ;

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
(1, 'Carry', 'Jaucian', 'aclcormocadmin@gmail.com', '$2y$10$DWMkVLaBR78PYjBLokXCku2CJEpd9nPwtPmlT0oRk6Lv01YGxEml6', '0', 'user.png');

-- --------------------------------------------------------

--
-- Table structure for table `subject_tb`
--

CREATE TABLE `subject_tb` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_units` varchar(255) NOT NULL,
  `subject_description` varchar(255) NOT NULL,
  `subject_department` int(11) DEFAULT NULL,
  `subject_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_tb`
--

INSERT INTO `subject_tb` (`subject_id`, `subject_name`, `subject_units`, `subject_description`, `subject_department`, `subject_type`) VALUES
(4, 'Calculus 1', '3', 'MATH6100', 1, 'Lecture'),
(5, 'Computing Fundamentals', '3', 'ITE6101', 2, 'Laboratory'),
(6, 'Computer Programming 1', '3', 'ITE6102', 2, 'Laboratory'),
(7, 'Understanding the Self', '3', 'GE6100', 1, 'Lecture'),
(8, 'Purposive Communication 1', '3', 'GE6106', 1, 'Lecture'),
(9, 'Euthenics', '1', 'ETHNS6101', 1, 'Lecture'),
(10, 'Physical Fitness', '2', 'PHYED6101', 1, 'Lecture'),
(11, 'National Service Training Program 1', '3', 'NSTP6101', 1, 'Lecture'),
(12, 'Purposive Communication 2', '3', 'ENGL6100', 1, 'Lecture'),
(13, 'The Contemporary World', '3', 'GE6102', 1, 'Lecture'),
(14, 'Ethics', '3', 'GE6107', 1, 'Lecture'),
(15, 'Computer Programming 2', '3', 'ITE6104', 2, 'Laboratory'),
(16, 'Discrete Mathematics', '3', 'CS6100', 2, 'Lecture'),
(17, 'Mathematics in the Modern World', '3', 'GE6114', 1, 'Lecture'),
(18, 'Euthenics 2', '1', 'ETHNS6102', 1, 'Lecture'),
(19, 'Rhythmic Activities', '2', 'PHYED6102', 1, 'Lecture'),
(20, 'National Service Training Program 2', '3', 'NSTP6102', 1, 'Lecture'),
(21, 'Introduction to Human Computer Interaction', '3', 'IT6200', 2, 'Laboratory'),
(22, 'Calculus-based Physics 1', '4', 'NSCI6100', 1, 'Lecture'),
(23, 'Data Communications and Networking 1', '3', 'IT6201', 2, 'Laboratory'),
(24, 'Data Structures and Algorithms', '3', 'ITE6201', 2, 'Laboratory'),
(25, 'Database Management System 1', '3', 'IT6202', 2, 'Laboratory'),
(26, 'Science, Technology and Society', '3', 'GE6116', 1, 'Lecture'),
(27, 'Data Analysis', '3', 'MATH6200', 2, 'Lecture'),
(28, 'Individual/Dual Sports', '2', 'PHYED6103', 1, 'Lecture'),
(29, 'Database Management System 2', '3', 'IT6203', 2, 'Laboratory'),
(30, 'Application Development and Emerging Technology', '3', 'ITE6200', 2, 'Laboratory'),
(31, 'Information Management', '3', 'ITE6220', 2, 'Laboratory'),
(32, 'Data Communications and Networking 2', '3', 'IT6223', 2, 'Laboratory'),
(33, 'Information Assurance and Security 1', '3', 'IT6205', 2, 'Laboratory'),
(34, 'Wika, Lipunan at Kultura', '3', 'FILI6101', 1, 'Lecture'),
(35, 'Calculus-based Physics 2', '4', 'NSCI6101', 1, 'Lecture'),
(36, 'Quanitative Methods', '3', 'IT6210', 2, 'Lecture'),
(37, 'Team Sports', '2', 'PHYED6200', 1, 'Lecture'),
(38, 'Information Assurance and Security 2', '3', 'IT6206', 2, 'Laboratory'),
(39, 'Data Communications and Networking 3', '3', 'IT6207', 2, 'Laboratory'),
(40, 'Principles of Operating Systems and its Application', '3', 'CS6206', 2, 'Laboratory'),
(41, 'Social and Professional Issues', '3', 'ITE6202', 2, 'Lecture'),
(42, 'Kritikal na Pagbasa, Pagsusulat at Pagsasalita', '3', 'FILI6201', 1, 'Lecture'),
(43, 'Introduction to Multimedia', '3', 'IT6209', 2, 'Laboratory'),
(44, 'System Integration and Architecture 1', '3', 'IT6208', 2, 'Laboratory'),
(45, 'IT Major Elective 1', '3', '', 2, 'Laboratory'),
(46, 'Data Communications and Networking 4', '3', 'IT6300', 2, 'Laboratory'),
(47, 'System Administration and Maintenance', '3', 'IT6301', 2, 'Laboratory'),
(48, 'Cloud Computing and the Internet of Things', '3', 'ITE6300', 2, 'Laboratory'),
(49, 'Application Lifecycle Management', '3', 'CS6302', 2, 'Laboratory'),
(50, 'Current Trends and Issues', '3', 'COMP6103', 2, 'Laboratory'),
(51, 'Integrative Programming and Technology 1', '3', 'IT6302', 2, 'Lecture'),
(52, 'Pagsasaling Pampanitikan', '3', 'FILI6301', 1, 'Lecture'),
(53, 'IT Major Elective 2', '3', '', 2, 'Laboratory'),
(54, 'IT Practicum (486 Hours)', '6', 'IT6397', 2, 'Lecture'),
(55, 'Software Engineering 1', '3', 'CS6209', 2, 'Laboratory'),
(56, 'Load Testing', '3', 'CS6303', 2, 'Laboratory'),
(57, 'Readings in Philippine History', '3', 'GE6105', 1, 'Lecture'),
(58, 'IT Capstone Project 1', '3', 'IT6398', 2, 'Lecture'),
(59, 'IT Major Elective 3', '3', '', 2, 'Laboratory'),
(60, 'Technopreneurship', '3', 'ITE6301', 2, 'Lecture'),
(61, 'Art Appreciation', '3', 'GE6115', 1, 'Lecture'),
(62, 'Life and Work of Rizal', '3', 'GE6301', 1, 'Lecture'),
(63, 'Unified Functional Testing', '3', 'CS6306', 2, 'Laboratory'),
(64, 'IT Capstone Project 2', '3', 'IT6399', 2, 'Laboratory'),
(65, 'IT Major Elective 4', '3', '', 2, 'Laboratory'),
(66, 'Production Operations Management with TQM', '', '', 3, 'Lecture'),
(67, 'Macroeconomics Theory and Practice', '', '', 3, 'Lecture'),
(68, 'Banking and Financial Institutions', '', '', 3, 'Lecture'),
(69, 'Fundamentals of Accounting Theory and Practice 1B', '', '', 3, 'Laboratory'),
(70, 'Law on Obligations and Contracts', '', '', 3, 'Lecture'),
(71, 'Phil Tax System and Income Tax', '', '', 3, 'Lecture'),
(72, 'Financial Management', '', '', 3, 'Lecture'),
(73, 'Special Topics in Financial Management', '', '', 3, 'Lecture'),
(74, 'Business Ethics with Good Gov\'t and Social Resp.', '', '', 3, 'Lecture'),
(75, 'Human Resource Management', '', '', 3, 'Lecture'),
(76, 'Database Management System (Oracle)', '', '', 2, 'Laboratory'),
(77, 'Monetary Policy and Central Banking', '', '', 3, 'Lecture'),
(78, 'Accounting Information System', '', '', 3, 'Laboratory'),
(79, 'Credit and Collection', '', '', 3, 'Lecture'),
(80, 'International Business Trade', '', '', 3, 'Lecture'),
(81, 'Financial Analysis and Reporting', '', '', 3, 'Lecture'),
(82, 'Security Analysis', '', '', 3, 'Lecture'),
(83, 'Investment and Portfolio Management', '', '', 3, 'Lecture'),
(84, 'Business Research', '', '', 3, 'Lecture'),
(85, 'Capital Markets', '', '', 3, 'Lecture'),
(86, 'IT Application Tools in Business', '', '', 3, 'Laboratory'),
(87, 'Elective 1 - Public Finance', '', '', 3, 'Laboratory'),
(88, 'Elective 2 - Treasury Management', '', '', 3, 'Lecture'),
(89, 'Thesis Writing 1', '', '', 3, 'Lecture'),
(90, 'Elective 3 - Financial Controllership', '', '', 3, 'Lecture'),
(91, 'Elective 4 - Personal Finance', '', '', 3, 'Lecture'),
(92, 'Elective 5 - Venture Capital', '', '', 3, 'Lecture'),
(93, 'Elective 6 - Risk Management', '', '', 3, 'Lecture'),
(94, 'Elective 7 - Cooperative Management', '', '', 3, 'Lecture'),
(95, 'Thesis Writing 2', '', '', 3, 'Lecture'),
(96, 'Strategic Management', '', '', 3, 'Lecture'),
(97, 'Practicum (600 Hours)', '', '', 3, 'Lecture'),
(98, 'Philippine Tourism, Geography and Culture', '', '', 4, 'Lecture'),
(99, 'Operation Management', '', '', 4, 'Lecture'),
(100, 'Risk Management Applied to Safe Security and Sanitation', '', '', 4, 'Lecture'),
(101, 'Entrepreneurship in Tourism and Hospitality', '', '', 4, 'Lecture'),
(102, 'Legal Aspects in Tourism and Hospitality', '', '', 4, 'Lecture'),
(103, 'Multicultural Diversity in Workplace for the Tourism Professional', '', '', 4, 'Lecture'),
(104, 'Fundamentals of Food Service Operations', '', '', 4, 'Lecture'),
(105, 'Kitchen Essentials and Basic Food Preparation', '', '', 4, 'Lecture'),
(106, 'Micro Perspective of Tourism and Hospitality', '', '', 4, 'Lecture'),
(107, 'Fundamentals in Lodging Operations', '', '', 4, 'Lecture'),
(108, 'Macro Perspective of Tourism and Hospitality', '', '', 4, 'Lecture'),
(109, 'Tourism and Hospitality Marketing', '', '', 4, 'Lecture'),
(110, 'Supply Chain Management in Hospitality Industry', '', '', 4, 'Lecture'),
(111, 'Foreign Language 1 - Spanish', '', '', 4, 'Lecture'),
(112, 'Tourism and Hospitality Service Quality Management', '', '', 4, 'Lecture'),
(113, 'Supply Chain Management in Hospitality Industry 2', '', '', 4, 'Lecture'),
(114, 'Professional Elective 1 - Crowd and Crisis Management', '', '', 4, 'Lecture'),
(115, 'Strategic Management and TQM', '', '', 4, 'Lecture'),
(116, 'Foreign Language 2 - Spanish', '', '', 4, 'Lecture'),
(117, 'Professional Elective 2 - Introduction to Transport Services', '', '', 4, 'Lecture'),
(118, 'Professional Elective 3 - Catering Management', '', '', 4, 'Lecture'),
(119, 'Applied Business Tools and Technologies', '', '', 4, 'Lecture'),
(120, 'Professional Elective 4 - Bread and Pastry', '', '', 4, 'Lecture'),
(121, 'Professional Elective 5 - Food and Beverage Cost Control', '', '', 4, 'Lecture'),
(122, 'Meetings Incentives Conferences, Events Management', '', '', 4, 'Lecture'),
(123, 'Ergonomics and Facilities Planning for the Hospitality Industry', '', '', 4, 'Lecture'),
(124, 'Professional Elective 6 - Oenology', '', '', 4, 'Lecture'),
(125, 'Professional Development and Applied Ethics', '', '', 4, 'Lecture'),
(126, 'Professional Elective 7 - Industry Trends and Innovations in Hospitality', '', '', 4, 'Lecture'),
(127, 'Professional Elective 8 - Franchising', '', '', 4, 'Lecture'),
(128, 'Research in Hospitality', '', '', 4, 'Lecture'),
(129, 'Practicum (min. 600 Hours)', '', '', 4, 'Lecture'),
(130, 'Discrete Structures 1', '', '', 2, 'Lecture'),
(131, 'Discrete Structures 2', '', '', 2, 'Lecture'),
(132, 'Object-oriented Programming', '', '', 2, 'Laboratory'),
(133, 'Calculus 2', '', '', 1, 'Lecture'),
(134, 'Algorithms and Complexity', '', '', 2, 'Lecture'),
(135, 'Logic Design and Digital Computer Circuits', '', '', 2, 'Laboratory'),
(136, 'Automata Theory and Formal Language', '', '', 2, 'Lecture'),
(137, 'Architecture and Organization', '', '', 2, 'Laboratory'),
(138, 'Programming Languages with Compiler', '', '', 2, 'Laboratory'),
(139, 'CS Major Elective 1', '', '', 2, 'Laboratory'),
(140, 'Software Engineering 2', '', '', 2, 'Laboratory'),
(141, 'CS Major Elective 2', '', '', 2, 'Laboratory'),
(142, 'Modeling and Simulation', '', '', 2, 'Laboratory'),
(143, 'CS Practicum (162 Hours)', '', '', 2, 'Lecture'),
(144, 'CS Design Project 1', '', '', 2, 'Lecture'),
(145, 'CS Major Elective 3', '', '', 2, 'Laboratory'),
(146, 'CS Major Elective 4', '', '', 2, 'Laboratory'),
(147, 'CS Design Project 2', '', '', 2, 'Laboratory'),
(148, 'Physical Education', '', '', 1, 'Lecture'),
(149, '3LP Seminar', '', '', 4, 'Lecture'),
(150, 'Supervised Industry Training (200 Hours)', '', '', 1, 'Lecture'),
(151, 'Financial Accounting and Reporting', '', '', 3, 'Lecture'),
(152, 'Managerial Economics', '', '', 3, 'Lecture'),
(153, 'Conceptual Framework and Accounting Standards', '', '', 3, 'Lecture'),
(154, 'Cost Accounting and Control System', '', '', 3, 'Lecture'),
(155, 'Intermediate Accounting 1', '', '', 3, 'Lecture'),
(156, 'Income Taxation', '', '', 3, 'Lecture'),
(157, 'Introduction to Accounting Information System', '', '', 3, 'Lecture'),
(158, 'Information System Analysis and Design', '', '', 3, 'Lecture'),
(159, 'Intermediate Accounting 2', '', '', 3, 'Lecture'),
(160, 'Business Taxation', '', '', 3, 'Lecture'),
(161, 'Regulatory Framework and Legal Issues in Business', '', '', 3, 'Lecture'),
(162, 'Living in the IT Era', '', '', 3, 'Lecture'),
(163, 'The Entrepreneurial Mind', '', '', 3, 'Lecture'),
(164, 'Intermediate Accounting 3', '', '', 3, 'Lecture'),
(165, 'Accounting Research Methods', '', '', 3, 'Lecture'),
(166, 'Financial Markets', '', '', 3, 'Lecture'),
(167, 'Strategic Business Analysis', '', '', 3, 'Lecture'),
(168, 'Enterprise Resource Planning System Implementation and Management', '', '', 3, 'Lecture'),
(169, 'Management Information System', '', '', 2, 'Laboratory'),
(170, 'Managing Information and Technology', '', '', 2, 'Lecture'),
(171, 'Information Security and Management', '', '', 2, 'Lecture'),
(172, 'Information Systems Operations and Maintenance', '', '', 2, 'Lecture'),
(173, 'Statistical Analysis with Software Application', '', '', 2, 'Laboratory'),
(174, 'Risk Management and Internal Control', '', '', 3, 'Lecture'),
(175, 'Managerial Science', '', '', 3, 'Lecture'),
(176, 'Supervised Industry Training (162 Hours)', '', '', 2, 'Lecture'),
(177, 'IT Major Elective 5', '', '', 2, 'Laboratory'),
(178, 'IT Major Elective 6', '', '', 2, 'Laboratory'),
(179, '21st Century Literature from the Regions', '', '', 1, 'Lecture'),
(180, 'Earth and Life Science ', '', '', 1, 'Lecture'),
(181, 'General Mathematics', '', '', 1, 'Lecture'),
(182, 'Komunikasyon at Pananaliksik', '', '', 1, 'Lecture'),
(183, 'Oral Communication in Context', '', '', 1, 'Lecture'),
(184, 'Personal Development', '', '', 1, 'Lecture'),
(185, 'Physical Education and Health', '', '', 1, 'Lecture'),
(186, 'Empowerment Technologies', '', '', 2, 'Lecture'),
(187, 'Organization and Management', '', '', 3, 'Lecture'),
(188, 'Contemporary Philippine Arts', '', '', 1, 'Lecture'),
(189, 'Pagbasa at Pagsusuri ng Iba\'t ibang Teksto', '', '', 1, 'Lecture'),
(190, 'Reading and Writing Skills', '', '', 1, 'Lecture'),
(191, 'Statistics and Probability', '', '', 1, 'Lecture'),
(192, 'Entrepreneurship', '', '', 1, 'Lecture'),
(193, 'Practical Research 1', '', '', 1, 'Lecture'),
(194, 'Fundamentals of ABM 1', '', '', 3, 'Lecture'),
(195, 'Principles of Marketing', '', '', 3, 'Lecture'),
(196, 'Introduction to the Philosophy', '', '', 1, 'Lecture'),
(197, 'Understanding Culture, Society, and Politics', '', '', 1, 'Lecture'),
(198, 'English for Academic and Professional Purposes', '', '', 1, 'Lecture'),
(199, 'Pagsulat sa Filipino sa Piling Larangan', '', '', 1, 'Lecture'),
(200, 'Practical Research 2', '', '', 1, 'Lecture'),
(201, 'Business Math', '', '', 3, 'Lecture'),
(202, 'Fundamentals of ABM 2', '', '', 3, 'Lecture'),
(203, 'Media and Information Literacy', '', '', 1, 'Lecture'),
(204, 'Physical Science', '', '', 1, 'Lecture'),
(205, 'Inquiries, Investigation, and Immersion', '', '', 1, 'Lecture'),
(206, 'Applied Economics', '', '', 3, 'Lecture'),
(207, 'Business Ethics and Social Responsibility', '', '', 3, 'Lecture'),
(208, 'Business Finance', '', '', 3, 'Lecture'),
(209, 'Work Immersion ABM', '', '', 3, 'Lecture'),
(210, 'Computer System Servicing 1', '', '', 2, 'Lecture'),
(211, 'Computer System Servicing 2', '', '', 2, 'Lecture'),
(212, 'Physical Education and Health 2', '', '', 1, 'Lecture'),
(213, 'Physical Education and Health 3', '', '', 1, 'Lecture'),
(214, 'Physical Education and Health 4', '', '', 1, 'Lecture'),
(215, 'Computer System Servicing 3', '', '', 2, 'Lecture'),
(216, 'Computer System Servicing 4', '', '', 2, 'Lecture'),
(217, 'Work Immersion/Research/Career Advocacy/Culminating Activity', '', '', 1, 'Lecture'),
(218, 'Animation 1', '', '', 2, 'Lecture'),
(219, 'Animation 2', '', '', 2, 'Lecture'),
(220, 'Animation 3', '', '', 2, 'Lecture'),
(221, 'Animation 4', '', '', 2, 'Lecture'),
(222, 'Programming 1', '', '', 2, 'Lecture'),
(223, 'Programming 2', '', '', 2, 'Lecture'),
(224, 'Programming 3', '', '', 2, 'Lecture'),
(225, 'Programming 4', '', '', 2, 'Lecture'),
(226, 'Pre-Calculus', '', '', 1, 'Lecture'),
(227, 'Basic Calculus', '', '', 1, 'Lecture'),
(228, 'General Biology 1', '', '', 1, 'Lecture'),
(229, 'General Physics 1', '', '', 1, 'Lecture'),
(230, 'General Chemistry 1', '', '', 1, 'Lecture'),
(231, 'General Biology 2', '', '', 1, 'Lecture'),
(232, 'General Physics 2', '', '', 1, 'Lecture'),
(233, 'General Chemistry 2', '', '', 1, 'Lecture'),
(234, 'Disaster Readiness and Risk Reduction', '', '', 1, 'Lecture'),
(235, 'NC II Food and Beverage Services', '', '', 4, 'Lecture'),
(236, 'NC II Bread and Pastry', '', '', 4, 'Lecture'),
(237, 'NC II Housekeeping', '', '', 4, 'Lecture'),
(238, 'NC II Local Tour Guiding', '', '', 4, 'Lecture'),
(239, 'Introduction to World Religion', '', '', 1, 'Lecture'),
(240, 'Creative Writing', '', '', 1, 'Lecture'),
(241, 'Discriplines and Ideas in the Social Science', '', '', 1, 'Lecture'),
(242, 'Creative Non-Fiction', '', '', 1, 'Lecture'),
(243, 'Discriplines and Ideas of the Applied Social Science', '', '', 1, 'Lecture'),
(244, 'Community Engagement and Solidarity', '', '', 1, 'Lecture'),
(245, 'Trends Network and Critical Thinking', '', '', 1, 'Lecture');

--
-- Triggers `subject_tb`
--
DELIMITER $$
CREATE TRIGGER `before_subject_delete` BEFORE DELETE ON `subject_tb` FOR EACH ROW BEGIN
    INSERT INTO archived_subject_tb (subject_id, subject_name, subject_units, subject_description, subject_department, subject_type, deleted_at)
    VALUES (OLD.subject_id, OLD.subject_name, OLD.subject_units, OLD.subject_description, OLD.subject_department, OLD.subject_type, NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `prevent_duplicate_subject_update` BEFORE UPDATE ON `subject_tb` FOR EACH ROW BEGIN
    DECLARE duplicate_count INT;

    -- Check for existing duplicate combination
    SELECT COUNT(*)
    INTO duplicate_count
    FROM subject_tb
    WHERE subject_name = NEW.subject_name
    	AND subject_department = NEW.subject_department
        AND subject_type = NEW.subject_type
        AND subject_id != OLD.subject_type;
        
        IF duplicate_count > 0 THEN
        	SIGNAL SQLSTATE '45000'
        	SET MESSAGE_TEXT = 'Update Prevented';
        END IF;
        
END
$$
DELIMITER ;

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
  `teacher_department` int(11) DEFAULT NULL,
  `teacher_proficency` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `teacher_pic` varchar(100) NOT NULL,
  `SD_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_tb`
--

INSERT INTO `teacher_tb` (`teacher_id`, `teacher_name`, `teacher_email`, `teacher_password`, `teacher_number`, `teacher_department`, `teacher_proficency`, `status`, `teacher_pic`, `SD_id`) VALUES
(1, 'admin1234', 'admin@gmail.com', '$2y$10$E7uNZJwCzWq3ryPai.Ncd.ab.VHHFvIyM0l6Dex9bCj7KUWw10526', '923023940', 1, 'teacher', 1, 'user.png', 1),
(4, 'Ellen', 'El@aclc.com', '$2y$10$CCXi4iu7S9ThBYM9doMgyOL2EYDgo22gwB.v0608cD/We3WwX1i6i', '', 1, '', 1, 'user.png', 1),
(5, 'Jonas', 'Jonas@aclc.com', '$2y$10$OvO3w4m07MDfHMbt6MWeEee3wsr60xHn.vYLCmQ.SagX.0xeh4p0W', '', 1, '', 1, 'user.png', 1),
(6, 'Oso', 'Oso@aclc.com', '$2y$10$x4l.3ttC8ByoaVhfqVOY8OZFF8RegPJgXPzwt4qUAmx7r0u74M4Ba', '', 1, '', 1, 'user.png', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archived_class_tb`
--
ALTER TABLE `archived_class_tb`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `class_department` (`class_department`);

--
-- Indexes for table `archived_department_tb`
--
ALTER TABLE `archived_department_tb`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `archived_room_tb`
--
ALTER TABLE `archived_room_tb`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `archived_schedule_tb`
--
ALTER TABLE `archived_schedule_tb`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `subject_id` (`subject_id`,`room_id`);

--
-- Indexes for table `archived_subject_tb`
--
ALTER TABLE `archived_subject_tb`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `subject_department` (`subject_department`);

--
-- Indexes for table `class_tb`
--
ALTER TABLE `class_tb`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `department_id` (`class_department`);

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
  ADD KEY `SD_id` (`SD_id`),
  ADD KEY `teacher_department` (`teacher_department`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class_tb`
--
ALTER TABLE `class_tb`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `department_tb`
--
ALTER TABLE `department_tb`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `room_tb`
--
ALTER TABLE `room_tb`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `schedule_tb`
--
ALTER TABLE `schedule_tb`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sd_tb`
--
ALTER TABLE `sd_tb`
  MODIFY `SD_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subject_tb`
--
ALTER TABLE `subject_tb`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;

--
-- AUTO_INCREMENT for table `teacher_tb`
--
ALTER TABLE `teacher_tb`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class_tb`
--
ALTER TABLE `class_tb`
  ADD CONSTRAINT `class_tb_ibfk_1` FOREIGN KEY (`class_department`) REFERENCES `department_tb` (`department_id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `schedule_tb`
--
ALTER TABLE `schedule_tb`
  ADD CONSTRAINT `schedule_tb_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher_tb` (`teacher_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `schedule_tb_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room_tb` (`room_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `schedule_tb_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `class_tb` (`class_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `schedule_tb_ibfk_4` FOREIGN KEY (`subject_id`) REFERENCES `subject_tb` (`subject_id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `subject_tb`
--
ALTER TABLE `subject_tb`
  ADD CONSTRAINT `subject_tb_ibfk_1` FOREIGN KEY (`subject_department`) REFERENCES `department_tb` (`department_id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `teacher_tb`
--
ALTER TABLE `teacher_tb`
  ADD CONSTRAINT `teacher_tb_ibfk_1` FOREIGN KEY (`teacher_department`) REFERENCES `department_tb` (`department_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `teacher_tb_ibfk_2` FOREIGN KEY (`SD_id`) REFERENCES `sd_tb` (`SD_id`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
