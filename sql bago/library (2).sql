-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2025 at 04:08 AM
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
(1, 'Batasan', 'Star Wars: A new Hope', 'George Clooney', 1999, 36, 'Fiction'),
(2, 'San Francisco', 'Harry Potter: The Legend of Encantadia', 'Pepito Manaloto', 1995, 36, 'Fiction'),
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
(71, '21-2121', 1, '844D66A8', '2025-05-15 20:05:14', '2025-05-15 20:05:28', 'Returned'),
(72, '21-2121', 2, 'CADB4611', '2025-05-15 20:05:43', '2025-05-15 20:05:50', 'Returned'),
(73, '23-2283', 2, 'C07830F7', '2025-05-15 20:06:13', '2025-05-15 20:06:25', 'Returned');

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
(6, 7, '', 'Approved', 1, 'San Bartolome', '2025-05-16 02:04:10');

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
(7, '21-2121', '2025-05-15 20:03:47', 'Approved', 'San Bartolome');

-- --------------------------------------------------------

--
-- Table structure for table `ebooks`
--

CREATE TABLE `ebooks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `file_link` varchar(255) NOT NULL,
  `available` tinyint(1) DEFAULT 1,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ebooks`
--

INSERT INTO `ebooks` (`id`, `title`, `author`, `category`, `file_link`, `available`, `added_on`) VALUES
(1, 'Ang Buhay Sa Likod Ng Isang Tunay', 'Bini Maloi', 'Authobiography', 'https://www.youtube.com/watch?v=Gg2G-rAzWj4', 1, '2025-05-15 14:19:54'),
(2, 'Hello love kain', 'Daniel Padilla', 'Romance', 'www.ayokokumain.com', 1, '2025-05-15 18:11:35');

-- --------------------------------------------------------

--
-- Table structure for table `ebook_borrowings`
--

CREATE TABLE `ebook_borrowings` (
  `id` int(11) NOT NULL,
  `ebook_id` int(11) NOT NULL,
  `student_number` varchar(50) NOT NULL,
  `borrow_date` datetime NOT NULL DEFAULT current_timestamp(),
  `expire_date` datetime NOT NULL,
  `status` enum('Active','Expired','Returned') NOT NULL DEFAULT 'Active',
  `access_link` varchar(255) NOT NULL,
  `borrowed_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ebook_borrowings`
--

INSERT INTO `ebook_borrowings` (`id`, `ebook_id`, `student_number`, `borrow_date`, `expire_date`, `status`, `access_link`, `borrowed_at`, `expires_at`) VALUES
(6, 1, '21-2121', '2025-05-16 01:59:54', '0000-00-00 00:00:00', 'Active', '', '2025-05-15 19:59:54', '2025-05-15 20:59:54');

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
(14, 1, '21-2121', '2025-05-15 20:02:00', 'Group Study', 'Approved');

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
(25, 14, 'Approved', 1, '2025-05-15 18:02:37');

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
(0, '23-2323', 'jonard ediwow', '$2y$10$vMDYhueoj17JY9wZhsxKy.F2dI2da.QEIOgJ46QI1laUT/hD/.M0.', 'student'),
(0, '23-2111', 'Sean Fernandez', '$2y$10$kN.0kjoTBKylwpDLvH0oO.YkZg.2jO4TIXFOHzC3md2./Q/liyk4y', 'student'),
(0, '22-1111', 'Juan Dela Cruz', '$2y$10$ueIQl8Vxs2K4zxLrg0E.7O7ctH/UENHWxS3NjA.fu1PlhVT0aFfGq', 'student'),
(0, '22-1234', 'Juan Dela Cruz', '$2y$10$A8zcbmEfsVgKY1yIlc.jGes9lUpO6Fe2d7aOlURNIC5biRYFIkaLO', 'student'),
(0, '21-2121', 'Juan Dela Cruz', '$2y$10$rdIStVzpy9HKmHMfkXvoqOJa2SOQgckN8BqeuQ9MtIAW7PE06hz92', 'student');

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
-- Indexes for table `comp_logs`
--
ALTER TABLE `comp_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `comp_request`
--
ALTER TABLE `comp_request`
  ADD PRIMARY KEY (`req_id`);

--
-- Indexes for table `ebooks`
--
ALTER TABLE `ebooks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebook_borrowings`
--
ALTER TABLE `ebook_borrowings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ebook` (`ebook_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `borrowings_temp`
--
ALTER TABLE `borrowings_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `comp_logs`
--
ALTER TABLE `comp_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `comp_request`
--
ALTER TABLE `comp_request`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ebooks`
--
ALTER TABLE `ebooks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ebook_borrowings`
--
ALTER TABLE `ebook_borrowings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `mr_requests`
--
ALTER TABLE `mr_requests`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `mr_reservations`
--
ALTER TABLE `mr_reservations`
  MODIFY `res_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `request_logs`
--
ALTER TABLE `request_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `reserve_logs`
--
ALTER TABLE `reserve_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ebook_borrowings`
--
ALTER TABLE `ebook_borrowings`
  ADD CONSTRAINT `fk_ebook` FOREIGN KEY (`ebook_id`) REFERENCES `ebooks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
