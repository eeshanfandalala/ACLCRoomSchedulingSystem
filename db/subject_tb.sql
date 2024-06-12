-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2024 at 07:10 PM
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
(65, 'IT Major Elective 4', '3', '', 'Computer Studies', 'Laboratory'),
(66, 'Production Operations Management with TQM', '', '', 'Business Administration', 'Lecture'),
(67, 'Macroeconomics Theory and Practice', '', '', 'Business Administration', 'Lecture'),
(68, 'Banking and Financial Institutions', '', '', 'Business Administration', 'Lecture'),
(69, 'Fundamentals of Accounting Theory and Practice 1B', '', '', 'Business Administration', 'Laboratory'),
(70, 'Law on Obligations and Contracts', '', '', 'Business Administration', 'Lecture'),
(71, 'Phil Tax System and Income Tax', '', '', 'Business Administration', 'Lecture'),
(72, 'Financial Management', '', '', 'Business Administration', 'Lecture'),
(73, 'Special Topics in Financial Management', '', '', 'Business Administration', 'Lecture'),
(74, 'Business Ethics with Good Gov\'t and Social Resp.', '', '', 'Business Administration', 'Lecture'),
(75, 'Human Resource Management', '', '', 'Business Administration', 'Lecture'),
(76, 'Database Management System (Oracle)', '', '', 'Computer Studies', 'Laboratory'),
(77, 'Monetary Policy and Central Banking', '', '', 'Business Administration', 'Lecture'),
(78, 'Accounting Information System', '', '', 'Business Administration', 'Laboratory'),
(79, 'Credit and Collection', '', '', 'Business Administration', 'Lecture'),
(80, 'International Business Trade', '', '', 'Business Administration', 'Lecture'),
(81, 'Financial Analysis and Reporting', '', '', 'Business Administration', 'Lecture'),
(82, 'Security Analysis', '', '', 'Business Administration', 'Lecture'),
(83, 'Investment and Portfolio Management', '', '', 'Business Administration', 'Lecture'),
(84, 'Business Research', '', '', 'Business Administration', 'Lecture'),
(85, 'Capital Markets', '', '', 'Business Administration', 'Lecture'),
(86, 'IT Application Tools in Business', '', '', 'Business Administration', 'Laboratory'),
(87, 'Elective 1 - Public Finance', '', '', 'Business Administration', 'Laboratory'),
(88, 'Elective 2 - Treasury Management', '', '', 'Business Administration', 'Lecture'),
(89, 'Thesis Writing 1', '', '', 'Business Administration', 'Lecture'),
(90, 'Elective 3 - Financial Controllership', '', '', 'Business Administration', 'Lecture'),
(91, 'Elective 4 - Personal Finance', '', '', 'Business Administration', 'Lecture'),
(92, 'Elective 5 - Venture Capital', '', '', 'Business Administration', 'Lecture'),
(93, 'Elective 6 - Risk Management', '', '', 'Business Administration', 'Lecture'),
(94, 'Elective 7 - Cooperative Management', '', '', 'Business Administration', 'Lecture'),
(95, 'Thesis Writing 2', '', '', 'Business Administration', 'Lecture'),
(96, 'Strategic Management', '', '', 'Business Administration', 'Lecture'),
(97, 'Practicum (600 Hours)', '', '', 'Business Administration', 'Lecture'),
(98, 'Philippine Tourism, Geography and Culture', '', '', 'Hospitality Management', 'Lecture'),
(99, 'Operation Management', '', '', 'Hospitality Management', 'Lecture'),
(100, 'Risk Management Applied to Safe Security and Sanitation', '', '', 'Hospitality Management', 'Lecture'),
(101, 'Entrepreneurship in Tourism and Hospitality', '', '', 'Hospitality Management', 'Lecture'),
(102, 'Legal Aspects in Tourism and Hospitality', '', '', 'Hospitality Management', 'Lecture'),
(103, 'Multicultural Diversity in Workplace for the Tourism Professional', '', '', 'Hospitality Management', 'Lecture'),
(104, 'Fundamentals of Food Service Operations', '', '', 'Hospitality Management', 'Lecture'),
(105, 'Kitchen Essentials and Basic Food Preparation', '', '', 'Hospitality Management', 'Lecture'),
(106, 'Micro Perspective of Tourism and Hospitality', '', '', 'Hospitality Management', 'Lecture'),
(107, 'Fundamentals in Lodging Operations', '', '', 'Hospitality Management', 'Lecture'),
(108, 'Macro Perspective of Tourism and Hospitality', '', '', 'Hospitality Management', 'Lecture'),
(109, 'Tourism and Hospitality Marketing', '', '', 'Hospitality Management', 'Lecture'),
(110, 'Supply Chain Management in Hospitality Industry', '', '', 'Hospitality Management', 'Lecture'),
(111, 'Foreign Language 1 - Spanish', '', '', 'Hospitality Management', 'Lecture'),
(112, 'Tourism and Hospitality Service Quality Management', '', '', 'Hospitality Management', 'Lecture'),
(113, 'Supply Chain Management in Hospitality Industry 2', '', '', 'Hospitality Management', 'Lecture'),
(114, 'Professional Elective 1 - Crowd and Crisis Management', '', '', 'Hospitality Management', 'Lecture'),
(115, 'Strategic Management and TQM', '', '', 'Hospitality Management', 'Lecture'),
(116, 'Foreign Language 2 - Spanish', '', '', 'Hospitality Management', 'Lecture'),
(117, 'Professional Elective 2 - Introduction to Transport Services', '', '', 'Hospitality Management', 'Lecture'),
(118, 'Professional Elective 3 - Catering Management', '', '', 'Hospitality Management', 'Lecture'),
(119, 'Applied Business Tools and Technologies', '', '', 'Hospitality Management', 'Lecture'),
(120, 'Professional Elective 4 - Bread and Pastry', '', '', 'Hospitality Management', 'Lecture'),
(121, 'Professional Elective 5 - Food and Beverage Cost Control', '', '', 'Hospitality Management', 'Lecture'),
(122, 'Meetings Incentives Conferences, Events Management', '', '', 'Hospitality Management', 'Lecture'),
(123, 'Ergonomics and Facilities Planning for the Hospitality Industry', '', '', 'Hospitality Management', 'Lecture'),
(124, 'Professional Elective 6 - Oenology', '', '', 'Hospitality Management', 'Lecture'),
(125, 'Professional Development and Applied Ethics', '', '', 'Hospitality Management', 'Lecture'),
(126, 'Professional Elective 7 - Industry Trends and Innovations in Hospitality', '', '', 'Hospitality Management', 'Lecture'),
(127, 'Professional Elective 8 - Franchising', '', '', 'Hospitality Management', 'Lecture'),
(128, 'Research in Hospitality', '', '', 'Hospitality Management', 'Lecture'),
(129, 'Practicum (min. 600 Hours)', '', '', 'Hospitality Management', 'Lecture'),
(130, 'Discrete Structures 1', '', '', 'Computer Studies', 'Lecture'),
(131, 'Discrete Structures 2', '', '', 'Computer Studies', 'Lecture'),
(132, 'Object-oriented Programming', '', '', 'Computer Studies', 'Laboratory'),
(133, 'Calculus 2', '', '', 'General', 'Lecture'),
(134, 'Algorithms and Complexity', '', '', 'Computer Studies', 'Lecture'),
(135, 'Logic Design and Digital Computer Circuits', '', '', 'Computer Studies', 'Laboratory'),
(136, 'Automata Theory and Formal Language', '', '', 'Computer Studies', 'Lecture'),
(137, 'Architecture and Organization', '', '', 'Computer Studies', 'Laboratory'),
(138, 'Programming Languages with Compiler', '', '', 'Computer Studies', 'Laboratory'),
(139, 'CS Major Elective 1', '', '', 'Computer Studies', 'Laboratory'),
(140, 'Software Engineering 2', '', '', 'Computer Studies', 'Laboratory'),
(141, 'CS Major Elective 2', '', '', 'Computer Studies', 'Laboratory'),
(142, 'Modeling and Simulation', '', '', 'Computer Studies', 'Laboratory'),
(143, 'CS Practicum (162 Hours)', '', '', 'Computer Studies', 'Lecture'),
(144, 'CS Design Project 1', '', '', 'Computer Studies', 'Lecture'),
(145, 'CS Major Elective 3', '', '', 'Computer Studies', 'Laboratory'),
(146, 'CS Major Elective 4', '', '', 'Computer Studies', 'Laboratory'),
(147, 'CS Design Project 2', '', '', 'Computer Studies', 'Laboratory'),
(148, 'Physical Education', '', '', 'General', 'Lecture'),
(149, '3LP Seminar', '', '', 'Hospitality Management', 'Lecture'),
(150, 'Supervised Industry Training (200 Hours)', '', '', 'General', 'Lecture'),
(151, 'Financial Accounting and Reporting', '', '', 'Business Administration', 'Lecture'),
(152, 'Managerial Economics', '', '', 'Business Administration', 'Lecture'),
(153, 'Conceptual Framework and Accounting Standards', '', '', 'Business Administration', 'Lecture'),
(154, 'Cost Accounting and Control System', '', '', 'Business Administration', 'Lecture'),
(155, 'Intermediate Accounting 1', '', '', 'Business Administration', 'Lecture'),
(156, 'Income Taxation', '', '', 'Business Administration', 'Lecture'),
(157, 'Introduction to Accounting Information System', '', '', 'Business Administration', 'Lecture'),
(158, 'Information System Analysis and Design', '', '', 'Business Administration', 'Lecture'),
(159, 'Intermediate Accounting 2', '', '', 'Business Administration', 'Lecture'),
(160, 'Business Taxation', '', '', 'Business Administration', 'Lecture'),
(161, 'Regulatory Framework and Legal Issues in Business', '', '', 'Business Administration', 'Lecture'),
(162, 'Living in the IT Era', '', '', 'Business Administration', 'Lecture'),
(163, 'The Entrepreneurial Mind', '', '', 'Business Administration', 'Lecture'),
(164, 'Intermediate Accounting 3', '', '', 'Business Administration', 'Lecture'),
(165, 'Accounting Research Methods', '', '', 'Business Administration', 'Lecture'),
(166, 'Financial Markets', '', '', 'Business Administration', 'Lecture'),
(167, 'Strategic Business Analysis', '', '', 'Business Administration', 'Lecture'),
(168, 'Enterprise Resource Planning System Implementation and Management', '', '', 'Business Administration', 'Lecture'),
(169, 'Management Information System', '', '', 'Computer Studies', 'Laboratory'),
(170, 'Managing Information and Technology', '', '', 'Computer Studies', 'Lecture'),
(171, 'Information Security and Management', '', '', 'Computer Studies', 'Lecture'),
(172, 'Information Systems Operations and Maintenance', '', '', 'Computer Studies', 'Lecture'),
(173, 'Statistical Analysis with Software Application', '', '', 'Computer Studies', 'Laboratory'),
(174, 'Risk Management and Internal Control', '', '', 'Business Administration', 'Lecture'),
(175, 'Managerial Science', '', '', 'Business Administration', 'Lecture'),
(176, 'Supervised Industry Training (162 Hours)', '', '', 'Computer Studies', 'Lecture'),
(177, 'IT Major Elective 5', '', '', 'Computer Studies', 'Laboratory'),
(178, 'IT Major Elective 6', '', '', 'Computer Studies', 'Laboratory'),
(179, '21st Century Literature from the Regions', '', '', 'General', 'Lecture'),
(180, 'Earth and Life Science ', '', '', 'General', 'Lecture'),
(181, 'General Mathematics', '', '', 'General', 'Lecture'),
(182, 'Komunikasyon at Pananaliksik', '', '', 'General', 'Lecture'),
(183, 'Oral Communication in Context', '', '', 'General', 'Lecture'),
(184, 'Personal Development', '', '', 'General', 'Lecture'),
(185, 'Physical Education and Health', '', '', 'General', 'Lecture'),
(186, 'Empowerment Technologies', '', '', 'Computer Studies', 'Lecture'),
(187, 'Organization and Management', '', '', 'Business Administration', 'Lecture'),
(188, 'Contemporary Philippine Arts', '', '', 'General', 'Lecture'),
(189, 'Pagbasa at Pagsusuri ng Iba\'t ibang Teksto', '', '', 'General', 'Lecture'),
(190, 'Reading and Writing Skills', '', '', 'General', 'Lecture'),
(191, 'Statistics and Probability', '', '', 'General', 'Lecture'),
(192, 'Entrepreneurship', '', '', 'General', 'Lecture'),
(193, 'Practical Research 1', '', '', 'General', 'Lecture'),
(194, 'Fundamentals of ABM 1', '', '', 'Business Administration', 'Lecture'),
(195, 'Principles of Marketing', '', '', 'Business Administration', 'Lecture'),
(196, 'Introduction to the Philosophy', '', '', 'General', 'Lecture'),
(197, 'Understanding Culture, Society, and Politics', '', '', 'General', 'Lecture'),
(198, 'English for Academic and Professional Purposes', '', '', 'General', 'Lecture'),
(199, 'Pagsulat sa Filipino sa Piling Larangan', '', '', 'General', 'Lecture'),
(200, 'Practical Research 2', '', '', 'General', 'Lecture'),
(201, 'Business Math', '', '', 'Business Administration', 'Lecture'),
(202, 'Fundamentals of ABM 2', '', '', 'Business Administration', 'Lecture'),
(203, 'Media and Information Literacy', '', '', 'General', 'Lecture'),
(204, 'Physical Science', '', '', 'General', 'Lecture'),
(205, 'Inquiries, Investigation, and Immersion', '', '', 'General', 'Lecture'),
(206, 'Applied Economics', '', '', 'Business Administration', 'Lecture'),
(207, 'Business Ethics and Social Responsibility', '', '', 'Business Administration', 'Lecture'),
(208, 'Business Finance', '', '', 'Business Administration', 'Lecture'),
(209, 'Work Immersion ABM', '', '', 'Business Administration', 'Lecture'),
(210, 'Computer System Servicing 1', '', '', 'Computer Studies', 'Lecture'),
(211, 'Computer System Servicing 2', '', '', 'Computer Studies', 'Lecture'),
(212, 'Physical Education and Health 2', '', '', 'General', 'Lecture'),
(213, 'Physical Education and Health 3', '', '', 'General', 'Lecture'),
(214, 'Physical Education and Health 4', '', '', 'General', 'Lecture'),
(215, 'Computer System Servicing 3', '', '', 'Computer Studies', 'Lecture'),
(216, 'Computer System Servicing 4', '', '', 'Computer Studies', 'Lecture'),
(217, 'Work Immersion/Research/Career Advocacy/Culminating Activity', '', '', 'General', 'Lecture'),
(218, 'Animation 1', '', '', 'Computer Studies', 'Lecture'),
(219, 'Animation 2', '', '', 'Computer Studies', 'Lecture'),
(220, 'Animation 3', '', '', 'Computer Studies', 'Lecture'),
(221, 'Animation 4', '', '', 'Computer Studies', 'Lecture'),
(222, 'Programming 1', '', '', 'Computer Studies', 'Lecture'),
(223, 'Programming 2', '', '', 'Computer Studies', 'Lecture'),
(224, 'Programming 3', '', '', 'Computer Studies', 'Lecture'),
(225, 'Programming 4', '', '', 'Computer Studies', 'Lecture'),
(226, 'Pre-Calculus', '', '', 'General', 'Lecture'),
(227, 'Basic Calculus', '', '', 'General', 'Lecture'),
(228, 'General Biology 1', '', '', 'General', 'Lecture'),
(229, 'General Physics 1', '', '', 'General', 'Lecture'),
(230, 'General Chemistry 1', '', '', 'General', 'Lecture'),
(231, 'General Biology 2', '', '', 'General', 'Lecture'),
(232, 'General Physics 2', '', '', 'General', 'Lecture'),
(233, 'General Chemistry 2', '', '', 'General', 'Lecture'),
(234, 'Disaster Readiness and Risk Reduction', '', '', 'General', 'Lecture'),
(235, 'NC II Food and Beverage Services', '', '', 'Hospitality Management', 'Lecture'),
(236, 'NC II Bread and Pastry', '', '', 'Hospitality Management', 'Lecture'),
(237, 'NC II Housekeeping', '', '', 'Hospitality Management', 'Lecture'),
(238, 'NC II Local Tour Guiding', '', '', 'Hospitality Management', 'Lecture'),
(239, 'Introduction to World Religion', '', '', 'General', 'Lecture'),
(240, 'Creative Writing', '', '', 'General', 'Lecture'),
(241, 'Discriplines and Ideas in the Social Science', '', '', 'General', 'Lecture'),
(242, 'Creative Non-Fiction', '', '', 'General', 'Lecture'),
(243, 'Discriplines and Ideas of the Applied Social Science', '', '', 'General', 'Lecture'),
(244, 'Community Engagement and Solidarity', '', '', 'General', 'Lecture'),
(245, 'Trends Network and Critical Thinking', '', '', 'General', 'Lecture');

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
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
