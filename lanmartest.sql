-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 03, 2025 at 01:18 PM
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
-- Database: `lanmartest`
--

-- --------------------------------------------------------

--
-- Table structure for table `bg_tbl`
--

CREATE TABLE `bg_tbl` (
  `bg_id` int(10) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_tbl`
--

CREATE TABLE `booking_tbl` (
  `booking_id` int(10) UNSIGNED NOT NULL,
  `dateIn` date NOT NULL,
  `dateOut` date DEFAULT NULL,
  `checkin` varchar(12) NOT NULL,
  `checkout` varchar(12) NOT NULL,
  `hours` decimal(4,1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_tbl`
--

INSERT INTO `booking_tbl` (`booking_id`, `dateIn`, `dateOut`, `checkin`, `checkout`, `hours`) VALUES
(6, '2024-09-20', '2024-09-21', '13:00', '11:00', 22.0),
(7, '2024-09-29', '2024-09-29', '8:30', '20:30', 12.0),
(8, '2024-09-22', '2024-09-22', '07:00', '20:00', 13.0),
(9, '2024-09-28', '2024-09-29', '14:00', '05:00', 15.0),
(10, '2024-09-27', '2024-09-27', '06:00', '18:00', 12.0),
(11, '2024-09-25', '2024-09-27', '06:00', '04:00', 46.0),
(13, '2024-09-24', '2024-09-25', '06:00', '03:00', 21.0);

-- --------------------------------------------------------

--
-- Table structure for table `chat_sessions`
--

CREATE TABLE `chat_sessions` (
  `session_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `galley_id` int(11) NOT NULL,
  `image` text NOT NULL,
  `image_type` text NOT NULL,
  `caption` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`galley_id`, `image`, `image_type`, `caption`) VALUES
(12, 'uploads/pic 1.jpg', 'image/jpeg', 'Beside the seaside'),
(13, 'uploads/pic 2.jfif', 'image/jpeg', ''),
(14, 'uploads/pic 3.jfif', 'image/jpeg', '');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

CREATE TABLE `gallery_images` (
  `id` int(10) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `image_alt` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inclusion_tbl`
--

CREATE TABLE `inclusion_tbl` (
  `inclusion_id` int(11) UNSIGNED NOT NULL,
  `inclusion_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inclusion_tbl`
--

INSERT INTO `inclusion_tbl` (`inclusion_id`, `inclusion_name`) VALUES
(1, 'TV'),
(2, 'Wi-fi'),
(3, 'Air condition'),
(4, 'Bathroom');

-- --------------------------------------------------------

--
-- Table structure for table `message_tbl`
--

CREATE TABLE `message_tbl` (
  `msg_id` int(11) NOT NULL,
  `sender_id` int(11) UNSIGNED NOT NULL,
  `receiver_id` int(11) UNSIGNED NOT NULL,
  `msg` text DEFAULT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message_tbl`
--

INSERT INTO `message_tbl` (`msg_id`, `sender_id`, `receiver_id`, `msg`, `timestamp`, `is_read`) VALUES
(1, 11, 9, 'kupal', '2024-12-28 23:31:52', 0),
(2, 9, 11, 'binibining may salamander', '2024-12-28 23:38:28', 0),
(3, 9, 10, 'bakit natin pinipili ang mga bagay na sa tingin natin ay karapat-dapat', '2024-12-29 01:00:41', 0),
(4, 9, 10, 'hi', '2024-12-29 15:07:58', 0),
(5, 9, 11, 'okay', '2024-12-29 15:08:38', 0),
(6, 9, 11, 'a', '2024-12-29 15:17:46', 0),
(7, 9, 11, 'b', '2024-12-29 15:17:49', 0),
(8, 9, 11, 'c', '2024-12-29 15:17:52', 0),
(9, 9, 11, 'd', '2024-12-29 15:17:54', 0),
(10, 9, 11, 'e', '2024-12-29 15:17:58', 0),
(11, 9, 11, 'ayooo', '2024-12-29 16:52:56', 0),
(12, 11, 9, 'ano', '2024-12-29 16:53:31', 0),
(13, 9, 11, 'juok', '2024-12-29 16:55:13', 0),
(14, 11, 9, 'lokoko', '2024-12-29 16:56:17', 0),
(15, 9, 11, 'try natin', '2024-12-29 17:06:10', 0),
(16, 11, 9, 'ginulay ang mundo', '2024-12-29 17:07:03', 0),
(17, 10, 9, 'getsl', '2024-12-29 17:15:29', 0),
(18, 11, 9, 'hanip', '2024-12-29 17:37:18', 0),
(19, 9, 11, 'cool', '2024-12-29 17:38:08', 0),
(20, 12, 9, 'test', '2025-01-02 22:03:25', 0);

-- --------------------------------------------------------

--
-- Table structure for table `prices_tbl`
--

CREATE TABLE `prices_tbl` (
  `id` int(11) UNSIGNED NOT NULL,
  `payment_name` varchar(50) NOT NULL,
  `price` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prices_tbl`
--

INSERT INTO `prices_tbl` (`id`, `payment_name`, `price`) VALUES
(1, 'day time', 8000),
(2, 'night time', 10000),
(3, 'added pax', 500),
(4, 'downpayment', 5000),
(5, 'time extend', 3000);

-- --------------------------------------------------------

--
-- Table structure for table `reservationtype_tbl`
--

CREATE TABLE `reservationtype_tbl` (
  `id` int(11) UNSIGNED NOT NULL,
  `reservation_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservationtype_tbl`
--

INSERT INTO `reservationtype_tbl` (`id`, `reservation_type`) VALUES
(1, 'Regular'),
(2, 'Birthdays'),
(3, 'Family Gathering');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `image_path` text NOT NULL,
  `description` text NOT NULL,
  `minpax` smallint(2) UNSIGNED NOT NULL,
  `maxpax` smallint(2) UNSIGNED NOT NULL,
  `price` int(6) NOT NULL,
  `is_featured` tinyint(1) NOT NULL,
  `is_offered` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_name`, `image_path`, `description`, `minpax`, `maxpax`, `price`, `is_featured`, `is_offered`) VALUES
(1, 'Big Room 1', 'uploads/bigroom1.jpg', 'Lorem ipsum dolor sit amet. Vel minus iste eos ullam dolor aut provident illum! Aut culpa officiis eos cupiditate omnis vel autem eligendi est omnis maiores et consectetur ducimus At provident totam aut sunt maxime.', 20, 25, 2000, 1, 1),
(2, 'Big Room 2', 'uploads/bigroom2.jpg', 'Lorem ipsum dolor sit amet. Vel minus iste eos ullam dolor aut provident illum! Aut culpa officiis eos cupiditate omnis vel autem eligendi est omnis maiores et consectetur ducimus At provident totam aut sunt maxime.', 20, 25, 2000, 1, 1),
(3, 'Family Room 1', 'uploads/familyroom1.jpg', 'Lorem ipsum dolor sit amet. Vel minus iste eos ullam dolor aut provident illum!.', 10, 15, 2000, 1, 0),
(4, 'Family Room 2', 'uploads/familyroom2.jpg', 'Lorem ipsum dolor', 10, 15, 2000, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `room_inclusions`
--

CREATE TABLE `room_inclusions` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `inclusion_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_inclusions`
--

INSERT INTO `room_inclusions` (`id`, `room_id`, `inclusion_id`) VALUES
(3, 2, 3),
(4, 2, 4),
(5, 1, 3),
(6, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `settings_admin`
--

CREATE TABLE `settings_admin` (
  `id` int(11) NOT NULL,
  `image` text NOT NULL,
  `image_type` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings_admin`
--

INSERT INTO `settings_admin` (`id`, `image`, `image_type`) VALUES
(0, 'uploads/Screenshot 2024-05-14 113405.png', 'image/png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `contact_number` varchar(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `verification_code` text NOT NULL,
  `email_verify` date DEFAULT NULL,
  `forgot_code` varchar(255) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `profile` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `firstname`, `lastname`, `contact_number`, `email`, `password`, `role`, `verification_code`, `email_verify`, `forgot_code`, `status`, `profile`) VALUES
(1, ' kristian', 'devs', '0901454644', 'deverakristian0789@gmail.com', '$2y$10$ZL8MlHWCD/GwatMlELX9iOEgRbHhuafvU9rufXmN7o63NObQBPidW', 'admin', '', NULL, '', 1, ''),
(5, 'dsada', 'asdad', '0901454644', '0789@gmail.com', '$2y$10$Vsy.Qbw/Gd0qoCAYJeCJGuas.o7GZgY9y60GcHFPSR/Y2PIBs93mG', '', '', NULL, '', 0, ''),
(6, 'asda', 'asdad', '548479797', 'dev012@gmail.com', '$2y$10$ALOj3QcHD0WfGoGsXyAflOvEnkjdsPKQV8kUnDooQ5mI5GQrm2Cl2', '', '', NULL, '', 0, ''),
(7, 'sadasdas', 'asdad', '12313123213', '123@gmail.com', '$2y$10$CFINF3PQlN3O4w30.xZfH.8EO4798NPe0Faq0929SpIefoJ/Al/42', '', '', NULL, '', 0, ''),
(8, 'Nhatalie', 'Paras', '09552658953', 'nhatalieparas@gmail.com', '$2y$10$YsyZ2mrifsUWV/ffyttVce8xern1aOEG9VoZULjPc8DBThOVIpgFW', 'admin', '', NULL, '', 1, ''),
(9, 'kristian', 'devera', '095099952', 'admin123@gmail.com', '$2y$10$kDLyQfzpwuEJgjHkgagBd.7tw75SxRYH0uTmWtPxMH2zOQLpw13UK', 'admin', '', NULL, '', 1, ''),
(10, 'dex', 'dela cruz', '09509044466', 'user123@gmail.com', '$2y$10$wSavKQRY3zlaLiuVJpimNeqjZIiqpnARRhipnmPziCW.hrUlx2xeq', 'user', '', NULL, '', 1, ''),
(11, 'xample', 'xample', '09876546788', 'xample@gmail.com', '$2y$10$OyNGZNLrq8BuLCWxRK6IWuyn3iX8Hs1TbyA1ef1B.VaNzekHkkHbu', 'user', '', NULL, '', 1, ''),
(12, 'adrian', 'enriquez', '1232132131', 'enriquezadrianjensen@gmail.com', '$2y$10$uvTzEFUlT5Dyxo3X2F/qyO1PJpZocpnxq3GPXuyUsLjjEf1x8VcpS', 'user', '290051', '2025-01-01', '', 1, ''),
(13, 'Mark Ian', 'Cuaco', '13121212123', 'mikespruce49@gmail.com', '$2y$10$F3Nmq.YMqi2OMraz.TYJVuywK/36TJiEEehlRQ5PxZNHG1RPpX5XO', 'user', '136506', NULL, '', 1, ''),
(14, 'aj', 'enriquez', '12312312312', 'ajenriquez@gmail.com', '$2y$10$/5qVR1OF//9ey8UgaimF5OFxRQwas/06GpVxU9w/K7Wl34UdkU2v6', 'admin', '', NULL, '', 1, ''),
(15, 'bon', 'devera', '9509049952', 'fridaythe012@gmail.com', '$2y$10$StcOYVx/h5psZ3eK4U6NCOJPKtlYEkZ/9/p6sIfRMmjXOoAZULhmq', 'user', '192444', '2025-01-03', '152222', 1, ''),
(23, 'agaga', 'dasdasd', '1215151515', 'bargoedwin10@gmail.com', '$2y$10$OMm9oW5yY3cMfz0oNCFa0.Vranp2wPMX7g7cgtd48ebQ5golDHMfm', 'user', '155949', '2025-01-03', '', 1, 'profile/12.jfif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bg_tbl`
--
ALTER TABLE `bg_tbl`
  ADD PRIMARY KEY (`bg_id`);

--
-- Indexes for table `booking_tbl`
--
ALTER TABLE `booking_tbl`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `chat_sessions`
--
ALTER TABLE `chat_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`galley_id`);

--
-- Indexes for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inclusion_tbl`
--
ALTER TABLE `inclusion_tbl`
  ADD PRIMARY KEY (`inclusion_id`);

--
-- Indexes for table `message_tbl`
--
ALTER TABLE `message_tbl`
  ADD PRIMARY KEY (`msg_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `prices_tbl`
--
ALTER TABLE `prices_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservationtype_tbl`
--
ALTER TABLE `reservationtype_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `room_inclusions`
--
ALTER TABLE `room_inclusions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `inclusion_id` (`inclusion_id`) USING BTREE;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bg_tbl`
--
ALTER TABLE `bg_tbl`
  MODIFY `bg_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_tbl`
--
ALTER TABLE `booking_tbl`
  MODIFY `booking_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `chat_sessions`
--
ALTER TABLE `chat_sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `galley_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inclusion_tbl`
--
ALTER TABLE `inclusion_tbl`
  MODIFY `inclusion_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `message_tbl`
--
ALTER TABLE `message_tbl`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `prices_tbl`
--
ALTER TABLE `prices_tbl`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reservationtype_tbl`
--
ALTER TABLE `reservationtype_tbl`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `room_inclusions`
--
ALTER TABLE `room_inclusions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat_sessions`
--
ALTER TABLE `chat_sessions`
  ADD CONSTRAINT `chat_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `room_inclusions`
--
ALTER TABLE `room_inclusions`
  ADD CONSTRAINT `room_inclusions_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
