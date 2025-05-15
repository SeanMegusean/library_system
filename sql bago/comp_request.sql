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
-- Table structure for table `comp_request`
--

CREATE TABLE `comp_request` (
  `req_id` int(11) NOT NULL,
  `student_number` varchar(20) NOT NULL,
  `date_requested` datetime NOT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL,
  `campus` enum('San Bartolome','San Francisco','Batasan','') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comp_request`
--

INSERT INTO `comp_request` (`req_id`, `student_number`, `date_requested`, `status`, `campus`) VALUES
(1, '20-2020', '2025-05-15 18:23:40', 'Approved', 'San Bartolome'),
(2, '20-2020', '2025-05-15 18:38:12', 'Approved', 'San Bartolome');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comp_request`
--
ALTER TABLE `comp_request`
  ADD PRIMARY KEY (`req_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comp_request`
--
ALTER TABLE `comp_request`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
