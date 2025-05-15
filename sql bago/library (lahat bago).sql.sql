-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2025 at 04:27 AM
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
(1, 'Batasan', 'Star Wars: A new Hope', 'George Clooney', 1999, 27, 'Fiction'),
(2, 'San Francisco', 'Harry Potter: The Legend of Encantadia', 'Pepito Manaloto', 1995, 28, 'Fiction'),
(3, 'San Bartolme', 'IT: Chapter 69', 'Robert J. Oppenheimer', 1945, 11, 'Mystery'),
(4, 'San Bartolme', 'Dune', 'Frank Herbert', 1965, 4, 'Sci-Fi'),
(5, 'San Bartolme', 'Ender\'s Game', 'Orson Scott Card', 1985, 6, 'Sci-Fi'),
(6, 'San Bartolme', 'Foundation', 'Isaac Asimov', 1951, 5, 'Sci-Fi'),
(7, 'San Bartolme', 'Pride and Prejudice', 'Jane Austen', 1813, 4, 'Romance'),
(8, 'San Francisco', 'Crimino and Educ', 'Filodor Dospordostoevsky', 2025, 1, 'Pyschololomo');

-- --------------------------------------------------------

--
-- Table structure for table `borrowings`
--

CREATE TABLE `borrowings` (
  `id` int(11) NOT NULL,
  `student_number` varchar(50) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `borrow_ref` varchar(50) DEFAULT NULL,
  `borrow_date` datetime DEFAULT NULL,
  `return_date` datetime DEFAULT NULL,
  `status` enum('Pending','Borrowed','Returned') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowings`
--

INSERT INTO `borrowings` (`id`, `student_number`, `book_id`, `borrow_ref`, `borrow_date`, `return_date`, `status`) VALUES
(47, '12-3456', 2, 'E80A1E58', '2025-05-14 20:30:32', '2025-05-14 20:30:39', 'Returned'),
(48, '12-3456', 1, 'C8665B6C', '2025-05-14 20:30:30', '2025-05-14 20:30:40', 'Returned'),
(49, '23-4567', 2, 'CD88A149', '2025-05-14 20:31:03', '2025-05-14 20:31:31', 'Returned'),
(50, '23-4567', 1, '4A9B9770', '2025-05-14 20:31:27', '2025-05-14 20:31:34', 'Returned'),
(51, '23-2283', 2, 'E0B3C1EB', '2025-05-14 20:31:52', '2025-05-14 20:32:09', 'Returned'),
(52, '23-2283', 1, '6E3B03A2', '2025-05-14 20:32:03', '2025-05-14 20:32:07', 'Returned'),
(53, '23-2283', 5, '1F6F4933', '2025-05-14 20:32:15', '2025-05-14 20:32:53', 'Returned'),
(54, '23-2283', 6, '2277462F', '2025-05-14 20:32:13', '2025-05-14 20:32:54', 'Returned'),
(55, '12-3456', 5, '3C59F732', '2025-05-14 20:32:39', '2025-05-14 20:32:51', 'Returned'),
(56, '12-3456', 6, '26E9DED1', '2025-05-14 20:32:49', '2025-05-14 20:32:52', 'Returned');

-- --------------------------------------------------------

--
-- Table structure for table `borrowings_temp`
--

CREATE TABLE `borrowings_temp` (
  `id` int(11) NOT NULL,
  `student_number` varchar(50) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `borrow_ref` varchar(50) DEFAULT NULL,
  `borrow_date` datetime DEFAULT NULL,
  `status` enum('Pending') DEFAULT 'Pending'
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
(10, 3, '12-3456', '2025-05-14 20:37:19', 'hehe', 'Approved');

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
  `status` enum('Pending','Approved','Rejected','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mr_reservations`
--

INSERT INTO `mr_reservations` (`res_id`, `room_id`, `student_number`, `date_reserved`, `start_time`, `end_time`, `reason`, `date_of_reservation`, `status`) VALUES
(2, 1, '12-3456', '2025-05-05', '14:00:00', '15:00:00', 'kain lang', '2025-05-04 08:18:55', ''),
(3, 2, '12-3456', '2025-05-21', '17:34:00', '18:34:00', 'adasdas', '2025-05-12 11:34:50', ''),
(4, 1, '23-1234', '2025-05-14', '22:19:00', '23:19:00', 'la lang', '2025-05-14 16:20:03', 'Approved'),
(5, 3, '12-3456', '2025-05-15', '02:39:00', '14:39:00', 'hehe', '2025-05-14 20:39:30', '');

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
(21, 10, 'Approved', 1, '2025-05-14 18:41:34');

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
(4, 5, 'Approved', 1, '2025-05-07 21:00:11'),
(5, 3, 'Approved', 1, '2025-05-12 09:37:31'),
(6, 4, 'Approved', 1, '2025-05-14 14:21:00'),
(7, 2, 'Approved', 1, '2025-05-14 18:41:31'),
(8, 5, 'Approved', 1, '2025-05-14 18:41:31');

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
(0, '23-1234', 'jonard ediwow', '$2y$10$kERPZtaxMqzuiKTUO16VneZMusZK7OfC1JcTtzWdeHuumLylrasFy', 'student'),
(0, '23-2323', 'jonard ediwow', '$2y$10$vMDYhueoj17JY9wZhsxKy.F2dI2da.QEIOgJ46QI1laUT/hD/.M0.', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `borrowings`
--
ALTER TABLE `borrowings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrowings_temp`
--
ALTER TABLE `borrowings_temp`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `borrowings_temp`
--
ALTER TABLE `borrowings_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `mr_requests`
--
ALTER TABLE `mr_requests`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `mr_reservations`
--
ALTER TABLE `mr_reservations`
  MODIFY `res_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `request_logs`
--
ALTER TABLE `request_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `reserve_logs`
--
ALTER TABLE `reserve_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
