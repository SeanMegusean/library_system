-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2025 at 06:44 PM
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
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Table structure for table `comp_logs`
--

CREATE TABLE `comp_logs` (
  `log_id` int(11) NOT NULL,
  `req_id` int(11) NOT NULL,
  `student_number` varchar(20) NOT NULL,
  `action` enum('Pending','Approved','Rejected') NOT NULL,
  `admin_id` int(11) NOT NULL,
  `campus` enum('San Bartolome','San Francisco','Batasan') NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comp_logs`
--

INSERT INTO `comp_logs` (`log_id`, `req_id`, `student_number`, `action`, `admin_id`, `campus`, `timestamp`) VALUES
(1, 2, '', 'Approved', 1, 'San Bartolome', '2025-05-16 00:38:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comp_logs`
--
ALTER TABLE `comp_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comp_logs`
--
ALTER TABLE `comp_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
