-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2025 at 07:27 PM
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
-- Table structure for table `reserve_logs`
--

CREATE TABLE `reserve_logs` (
  `log_id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  `action` enum('Approved','Rejected') NOT NULL,
  `admin_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reserve_logs`
--

INSERT INTO `reserve_logs` (`log_id`, `res_id`, `action`, `admin_id`, `timestamp`) VALUES
(1, 2, 'Approved', 1, '2025-05-07 19:09:49'),
(2, 3, 'Approved', 1, '2025-05-07 20:52:25'),
(3, 4, 'Approved', 1, '2025-05-07 20:59:42'),
(4, 5, 'Approved', 1, '2025-05-07 21:00:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reserve_logs`
--
ALTER TABLE `reserve_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `req_id` (`res_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reserve_logs`
--
ALTER TABLE `reserve_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
