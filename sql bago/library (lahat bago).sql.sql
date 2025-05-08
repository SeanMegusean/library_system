-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2025 at 07:25 PM
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
  `campus` varchar(25) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `campus`, `title`, `author`, `year`, `quantity`, `category`) VALUES
(1, 'San Francisco', 'Star Wars: A new Hope', 'George Clooney', 1999, 11, 'Fiction'),
(2, 'San Francisco', 'Harry Potter: The Legend of Encantadia', 'Pepito Manaloto', 1995, 13, 'Fiction'),
(3, 'San Bartolme', 'IT: Chapter 69', 'Robert J. Oppenheimer', 1945, 11, 'Mystery'),
(4, 'San Bartolme', 'Dune', 'Frank Herbert', 1965, 4, 'Sci-Fi'),
(5, 'San Bartolme', 'Ender\'s Game', 'Orson Scott Card', 1985, 4, 'Sci-Fi'),
(6, 'San Bartolme', 'Foundation', 'Isaac Asimov', 1951, 3, 'Sci-Fi'),
(7, 'San Bartolme', 'Pride and Prejudice', 'Jane Austen', 1813, 4, 'Romance'),
(8, 'San Francisco', 'Crimino and Educ', 'Filodor Dospordostoevsky', 2025, 1, 'Pyschololomo');

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
(11, 1, '6127DA01', '2025-05-04', '12-3456', 'Returned', NULL),
(12, 4, 'E3CCB81E', '2025-05-04', '12-3456', 'Returned', NULL),
(13, 5, '368A1E5F', '2025-05-04', '12-3456', 'Returned', NULL),
(14, 5, 'E28532A2', '2025-05-04', '23-2283', 'Returned', NULL),
(15, 6, '14BD46A2', '2025-05-04', '23-2283', 'Returned', NULL);

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
(8, 1, '12-3456', '2025-05-04 08:19:07', 'mag aral', 'Approved'),
(9, 1, '12-3456', '2025-05-04 08:46:30', 'sdasdas', 'Approved'),
(10, 1, 'student123', '2025-05-07 20:15:11', 'idk', 'Approved'),
(11, 1, 'student123', '2025-05-07 20:22:54', 'hell ye', 'Approved'),
(12, 1, '12-3456', '2025-05-07 20:25:36', 'asd', 'Approved'),
(13, 2, '12-3456', '2025-05-07 20:25:39', 'asd', 'Approved'),
(14, 1, 'student123', '2025-05-07 20:28:44', 'asd', 'Approved'),
(15, 1, '12-3456', '2025-05-07 20:36:04', 'z', 'Approved'),
(16, 1, '12-3456', '2025-05-07 20:41:08', '1', 'Approved'),
(17, 2, '12-3456', '2025-05-07 20:57:24', '1', 'Rejected'),
(18, 1, '12-3456', '2025-05-07 21:51:40', 'zxc', 'Approved'),
(19, 1, '23-1000', '2025-05-07 22:57:28', 'rt ddgh', 'Approved'),
(20, 1, '12-3456', '2025-05-07 22:57:47', 'hghkghk', 'Approved'),
(21, 4, '12-3456', '2025-05-07 22:57:57', 'gcbncvbncbn', 'Pending'),
(22, 4, '12-3456', '2025-05-07 23:17:39', 'zxc', 'Pending'),
(23, 4, '12-3456', '2025-05-07 23:17:44', 'zxc', 'Pending'),
(24, 3, '12-3456', '2025-05-07 23:17:47', 'zxc', 'Pending'),
(25, 1, '12-3456', '2025-05-07 23:18:28', 'fsd', 'Pending'),
(26, 1, '12-3456', '2025-05-07 23:18:35', 'asd', 'Pending'),
(27, 1, '23-1000', '2025-05-07 23:18:46', 'asd', 'Pending'),
(28, 3, '23-1000', '2025-05-07 23:18:49', 'sad', 'Pending'),
(29, 1, '12-3456', '2025-05-07 23:21:32', 'asd', 'Pending');

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

-- --------------------------------------------------------

--
-- Table structure for table `request_logs`
--

CREATE TABLE `request_logs` (
  `log_id` int(11) NOT NULL,
  `req_id` int(11) NOT NULL,
  `action` enum('Approved','Rejected') NOT NULL,
  `admin_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_logs`
--

INSERT INTO `request_logs` (`log_id`, `req_id`, `action`, `admin_id`, `timestamp`) VALUES
(19, 8, 'Approved', 1, '2025-05-04 06:19:37'),
(20, 9, 'Approved', 1, '2025-05-04 06:46:57'),
(21, 10, 'Approved', 1, '2025-05-07 18:32:58'),
(22, 11, 'Approved', 1, '2025-05-07 18:33:03'),
(23, 15, 'Approved', 1, '2025-05-07 18:36:11'),
(24, 12, 'Approved', 1, '2025-05-07 18:38:52'),
(25, 13, 'Approved', 1, '2025-05-07 18:39:04'),
(26, 14, 'Approved', 1, '2025-05-07 18:39:20'),
(27, 16, 'Approved', 1, '2025-05-07 18:47:21'),
(28, 17, 'Rejected', 1, '2025-05-07 18:57:36'),
(29, 18, 'Approved', 1, '2025-05-07 19:52:34'),
(30, 20, 'Approved', 1, '2025-05-07 20:59:11'),
(31, 19, 'Approved', 1, '2025-05-07 20:59:22');

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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `student_number` varchar(20) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `student_number`, `full_name`, `password`, `role`) VALUES
(1, '1111111', 'Mike Kosa', '$2y$10$THpAVFDpPVXY5GdrMprCkuh2844G/LTEHO82eLTuxE3TCY068azq2', 'admin'),
(2, '12-3456', 'Jose Mandarambong', '$2y$10$bLFarHxLFUpYlpbFNKpobuxf/3oUY3bMzh2G6OPZORLQfdVH7wQO6', 'student'),
(3, '23-4567', 'Wally Ayolab', '$2y$10$DVdFY6zt1AnMgKHqN76G.Od6qf3Rb2paitJbRLIrw5KdjKqEP1Zce', 'student'),
(0, '23-2283', 'Sean Fernandez', '$2y$10$/kBMUiVWuLIkOyOi2YdeHODM/XGHw9n5mAEfASchWqzUkr3Gu9M7C', 'student'),
(0, '23-1000', 'Andro', '$2y$10$S2XloQv/kqmqSUjevwkMzu7tnQ7SGZhnbW3tMSfCMqsE9FM1FQfAG', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `borrowings`
--
ALTER TABLE `borrowings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mr_requests`
--
ALTER TABLE `mr_requests`
  ADD PRIMARY KEY (`req_id`);

--
-- Indexes for table `mr_reservations`
--
ALTER TABLE `mr_reservations`
  ADD PRIMARY KEY (`res_id`);

--
-- Indexes for table `request_logs`
--
ALTER TABLE `request_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `req_id` (`req_id`),
  ADD KEY `admin_id` (`admin_id`);

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
-- AUTO_INCREMENT for table `borrowings`
--
ALTER TABLE `borrowings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `mr_requests`
--
ALTER TABLE `mr_requests`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `mr_reservations`
--
ALTER TABLE `mr_reservations`
  MODIFY `res_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `request_logs`
--
ALTER TABLE `request_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `reserve_logs`
--
ALTER TABLE `reserve_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
