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
-- Table structure for table `mr_reservations`
--

CREATE TABLE `mr_reservations` (
  `res_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `student_number` varchar(20) NOT NULL,
  `date_reserved` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `reason` longtext NOT NULL,
  `date_of_reservation` datetime NOT NULL,
  `status` enum('Pending','Approved','Rejected','Cancelled') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mr_reservations`
--

INSERT INTO `mr_reservations` (`res_id`, `room_id`, `student_number`, `date_reserved`, `start_time`, `end_time`, `reason`, `date_of_reservation`, `status`) VALUES
(2, 1, '12-3456', '2025-05-05', '14:00:00', '15:00:00', 'kain lang', '2025-05-04 08:18:55', 'Cancelled'),
(3, 2, '12-3456', '2025-05-13', '14:25:00', '02:25:00', 'asd', '2025-05-07 20:25:55', 'Cancelled'),
(4, 1, '23-1000', '2025-05-08', '16:58:00', '16:58:00', 'fgxfgcbc', '2025-05-07 22:58:24', 'Cancelled'),
(5, 4, '12-3456', '2025-05-08', '16:58:00', '16:58:00', 'xgcvbcbncv\r\n', '2025-05-07 22:58:48', 'Cancelled');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mr_reservations`
--
ALTER TABLE `mr_reservations`
  ADD PRIMARY KEY (`res_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mr_reservations`
--
ALTER TABLE `mr_reservations`
  MODIFY `res_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
