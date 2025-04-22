-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2025 at 08:22 PM
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
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `year`, `quantity`, `category`) VALUES
(1, 'Star Wars: A new Hope', 'George Clooney', 2002, 3, 'Fiction'),
(2, 'Harry Potter: The Legend of Encantadia', 'Pepito Manaloto', 1995, 14, 'Fiction'),
(3, 'IT: Chapter 69', 'Robert J. Oppenheimer', 1945, 16, 'Mystery'),
(4, 'Dune', 'Frank Herbert', 1965, 5, 'Sci-Fi'),
(5, 'Ender\'s Game', 'Orson Scott Card', 1985, 4, 'Sci-Fi'),
(6, 'Foundation', 'Isaac Asimov', 1951, 3, 'Sci-Fi'),
(7, 'Pride and Prejudice', 'Jane Austen', 1813, 4, 'Romance');

-- --------------------------------------------------------

--
-- Table structure for table `borrowings`
--

CREATE TABLE `borrowings` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrow_ref` varchar(50) DEFAULT NULL,
  `borrow_date` date DEFAULT NULL,
  `student_number` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `return_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowings`
--

INSERT INTO `borrowings` (`id`, `book_id`, `borrow_ref`, `borrow_date`, `student_number`, `status`, `return_date`) VALUES
(50, 6, '9E3AE35B', '2025-04-20', 'student123', 'Returned', NULL),
(51, 1, '623F0A22', '2025-04-20', 'student123', 'Returned', NULL),
(52, 2, '645B8218', '2025-04-20', 'student123', 'Returned', NULL),
(53, 2, '0A611910', '2025-04-20', 'student456', 'Returned', NULL),
(54, 1, '620212D3', '2025-04-20', 'student456', 'Returned', NULL),
(55, 2, '77A83F3D', '2025-04-20', 'student456', 'Returned', NULL),
(56, 1, '0EBD5AE3', '2025-04-20', 'student456', 'Returned', NULL),
(57, 1, '5036ECA5', '2025-04-20', 'student456', 'Returned', NULL),
(58, 6, '031058C9', '2025-04-20', 'student456', 'Returned', NULL),
(59, 5, 'B77BB8FC', '2025-04-20', 'student456', 'Returned', NULL),
(60, 6, '9352DAF5', '2025-04-20', 'student123', 'Returned', NULL),
(61, 5, 'DE6F72F7', '2025-04-20', 'student123', 'Returned', NULL),
(62, 5, '767557ED', '2025-04-20', 'student123', 'Returned', NULL),
(63, 1, '76E6A646', '2025-04-20', 'student123', 'Returned', NULL),
(64, 1, '91EEEA61', '2025-04-20', 'student123', 'Returned', NULL),
(65, 1, 'F0CD4487', '2025-04-20', 'student123', 'Returned', NULL),
(66, 2, 'EE60CCD8', '2025-04-20', 'student123', 'Returned', NULL),
(67, 2, '51C3EA1B', '2025-04-20', 'student123', 'Returned', NULL),
(68, 1, '0C438161', '2025-04-20', 'student123', 'Returned', NULL),
(69, 1, 'E3E1CDD1', '2025-04-20', 'student123', 'Returned', NULL),
(70, 2, '2BFC53B5', '2025-04-20', 'student123', 'Pending', NULL),
(71, 2, '3962BC80', '2025-04-20', 'student123', 'Pending', NULL),
(72, 1, '62F02149', '2025-04-22', 'student123', 'Returned', NULL),
(73, 1, '2B83B8CD', '2025-04-22', 'student123', 'Pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `borrowings_temp`
--

CREATE TABLE `borrowings_temp` (
  `id` int(11) NOT NULL DEFAULT 0,
  `book_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_number` varchar(20) DEFAULT NULL,
  `borrow_ref` varchar(100) NOT NULL,
  `borrow_date` datetime NOT NULL,
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowings_temp`
--

INSERT INTO `borrowings_temp` (`id`, `book_id`, `student_id`, `student_number`, `borrow_ref`, `borrow_date`, `status`) VALUES
(42, 1, 2, 'student123', '4D58F9C7', '2025-04-20 05:20:15', 'Returned'),
(43, 2, 2, 'student123', 'ACD84D5E', '2025-04-20 05:22:44', 'Returned'),
(44, 2, 3, 'student456', '794BDF24', '2025-04-20 05:23:29', 'Returned'),
(45, 1, 2, 'student123', '86E1391B', '2025-04-20 05:26:25', 'Returned'),
(46, 2, 2, 'student123', 'DEEC21E5', '2025-04-20 05:26:27', 'Returned'),
(47, 2, 3, 'student456', 'CFECCD45', '2025-04-20 05:26:39', 'Returned'),
(48, 1, 2, 'student123', '4FE3B103', '2025-04-20 05:31:10', 'Returned'),
(49, 2, 2, 'student123', 'A92B298B', '2025-04-20 05:31:13', 'Returned'),
(50, 2, 3, 'student456', '98FCA98C', '2025-04-20 05:31:24', 'Returned'),
(51, 1, 2, 'student123', '019CA03E', '2025-04-20 05:41:11', 'Returned'),
(52, 2, 2, 'student123', '01DA5836', '2025-04-20 05:41:14', 'Returned'),
(53, 2, 3, 'student456', '24309EC8', '2025-04-20 05:41:22', 'Returned'),
(54, 1, 3, 'student456', 'A16168D6', '2025-04-20 05:42:03', 'Returned');

-- --------------------------------------------------------

--
-- Table structure for table `meeting_rooms`
--

CREATE TABLE `meeting_rooms` (
  `room_id` int(11) NOT NULL,
  `Status` enum('Available','Unavailable','In Session','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meeting_rooms`
--

INSERT INTO `meeting_rooms` (`room_id`, `Status`) VALUES
(1, 'Available'),
(2, 'Available'),
(3, 'Available'),
(4, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `mr_requests`
--

CREATE TABLE `mr_requests` (
  `req_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `student_number` varchar(20) NOT NULL,
  `date_requested` datetime NOT NULL,
  `reason` longtext NOT NULL,
  `status` enum('Pending','Approved','Rejected','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mr_requests`
--

INSERT INTO `mr_requests` (`req_id`, `room_id`, `student_number`, `date_requested`, `reason`, `status`) VALUES
(1, 1, 'student123', '2025-04-22 00:00:00', 'I want segsy time', 'Pending'),
(2, 2, 'student123', '2025-04-22 20:14:56', 'matotolog po', 'Pending'),
(3, 1, 'student123', '2025-04-22 20:16:30', 'matotolog po ko', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `student_number` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `student_number`, `password`, `role`) VALUES
(1, 'admin123', '$2y$10$THpAVFDpPVXY5GdrMprCkuh2844G/LTEHO82eLTuxE3TCY068azq2', 'admin'),
(2, 'student123', '$2y$10$bLFarHxLFUpYlpbFNKpobuxf/3oUY3bMzh2G6OPZORLQfdVH7wQO6', 'student'),
(3, 'student456', '$2y$10$DVdFY6zt1AnMgKHqN76G.Od6qf3Rb2paitJbRLIrw5KdjKqEP1Zce', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrowings`
--
ALTER TABLE `borrowings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meeting_rooms`
--
ALTER TABLE `meeting_rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `mr_requests`
--
ALTER TABLE `mr_requests`
  ADD PRIMARY KEY (`req_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_number` (`student_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `borrowings`
--
ALTER TABLE `borrowings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `meeting_rooms`
--
ALTER TABLE `meeting_rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mr_requests`
--
ALTER TABLE `mr_requests`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
