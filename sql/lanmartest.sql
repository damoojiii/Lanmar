-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2024 at 04:58 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
(3, 'added', 500),
(4, 'downpayment', 5000);

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
  `is_featured` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_inclusions`
--

CREATE TABLE `room_inclusions` (
  `inclusion_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `inclusion_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(0, 'uploads/456775232_2070272826702959_2662113685727986881_n.jpg', 'image/jpeg');

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
  `forgot_code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `firstname`, `lastname`, `contact_number`, `email`, `password`, `role`, `verification_code`, `email_verify`, `forgot_code`) VALUES
(1, ' kristian', 'devs', '0901454644', 'deverakristian0789@gmail.com', '$2y$10$ZL8MlHWCD/GwatMlELX9iOEgRbHhuafvU9rufXmN7o63NObQBPidW', 'admin', '', NULL, ''),
(5, 'dsada', 'asdad', '0901454644', '0789@gmail.com', '$2y$10$Vsy.Qbw/Gd0qoCAYJeCJGuas.o7GZgY9y60GcHFPSR/Y2PIBs93mG', '', '', NULL, ''),
(6, 'asda', 'asdad', '548479797', 'dev012@gmail.com', '$2y$10$ALOj3QcHD0WfGoGsXyAflOvEnkjdsPKQV8kUnDooQ5mI5GQrm2Cl2', '', '', NULL, ''),
(7, 'sadasdas', 'asdad', '12313123213', '123@gmail.com', '$2y$10$CFINF3PQlN3O4w30.xZfH.8EO4798NPe0Faq0929SpIefoJ/Al/42', '', '', NULL, ''),
(8, 'Nhatalie', 'Paras', '09552658953', 'nhatalieparas@gmail.com', '$2y$10$YsyZ2mrifsUWV/ffyttVce8xern1aOEG9VoZULjPc8DBThOVIpgFW', 'admin', '', NULL, ''),
(9, 'kristian', 'devera', '095099952', 'admin123@gmail.com', '$2y$10$kDLyQfzpwuEJgjHkgagBd.7tw75SxRYH0uTmWtPxMH2zOQLpw13UK', 'admin', '', NULL, ''),
(10, 'dex', 'dela cruz', '09509044466', 'user123@gmail.com', '$2y$10$wSavKQRY3zlaLiuVJpimNeqjZIiqpnARRhipnmPziCW.hrUlx2xeq', 'user', '', NULL, ''),
(11, 'xample', 'xample', '09876546788', 'xample@gmail.com', '$2y$10$OyNGZNLrq8BuLCWxRK6IWuyn3iX8Hs1TbyA1ef1B.VaNzekHkkHbu', 'user', '', NULL, ''),
(12, 'sad', 'sed', '12312313', 'sad@gmail.com', '$2y$10$LaCiGBdujK9PMoV56I7f/OuRiSSt/qtXuY.MBmc1khyVBj0j6A/Li', 'user', '458362', NULL, ''),
(13, 'sad', 'sed', '12312313', 'fridaythe012@gmail.com', '$2y$10$hwCofLLlYENQn276bNGp3elJ7DFQKqVG6aPwNMJkE92f0P6skvCyS', 'user', '321764', '2024-10-30', '190315');

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
-- Indexes for table `prices_tbl`
--
ALTER TABLE `prices_tbl`
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
  ADD PRIMARY KEY (`inclusion_id`),
  ADD KEY `room_id` (`room_id`);

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
-- AUTO_INCREMENT for table `prices_tbl`
--
ALTER TABLE `prices_tbl`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room_inclusions`
--
ALTER TABLE `room_inclusions`
  MODIFY `inclusion_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `room_inclusions`
--
ALTER TABLE `room_inclusions`
  ADD CONSTRAINT `room_inclusions_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
