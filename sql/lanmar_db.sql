-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 12, 2024 at 01:31 PM
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
-- Database: `lanmar_db`
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
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `image_path` text NOT NULL,
  `description` text NOT NULL,
  `capacity` int(11) NOT NULL,
  `is_featured` tinyint(1) NOT NULL
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
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `firstname`, `lastname`, `contact_number`, `email`, `password`, `role`) VALUES
(1, ' kristian', 'devs', '0901454644', 'deverakristian0789@gmail.com', '$2y$10$ZL8MlHWCD/GwatMlELX9iOEgRbHhuafvU9rufXmN7o63NObQBPidW', 'admin'),
(5, 'dsada', 'asdad', '0901454644', '0789@gmail.com', '$2y$10$Vsy.Qbw/Gd0qoCAYJeCJGuas.o7GZgY9y60GcHFPSR/Y2PIBs93mG', ''),
(6, 'asda', 'asdad', '548479797', 'dev012@gmail.com', '$2y$10$ALOj3QcHD0WfGoGsXyAflOvEnkjdsPKQV8kUnDooQ5mI5GQrm2Cl2', ''),
(7, 'sadasdas', 'asdad', '12313123213', '123@gmail.com', '$2y$10$CFINF3PQlN3O4w30.xZfH.8EO4798NPe0Faq0929SpIefoJ/Al/42', ''),
(8, 'Nhatalie', 'Paras', '09552658953', 'nhatalieparas@gmail.com', '$2y$10$YsyZ2mrifsUWV/ffyttVce8xern1aOEG9VoZULjPc8DBThOVIpgFW', 'admin'),
(9, 'kristian', 'devera', '095099952', 'admin123@gmail.com', '$2y$10$kDLyQfzpwuEJgjHkgagBd.7tw75SxRYH0uTmWtPxMH2zOQLpw13UK', 'admin'),
(10, 'dex', 'dela cruz', '09509044466', 'user123@gmail.com', '$2y$10$wSavKQRY3zlaLiuVJpimNeqjZIiqpnARRhipnmPziCW.hrUlx2xeq', 'user'),
(11, 'xample', 'xample', '09876546788', 'xample@gmail.com', '$2y$10$OyNGZNLrq8BuLCWxRK6IWuyn3iX8Hs1TbyA1ef1B.VaNzekHkkHbu', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bg_tbl`
--
ALTER TABLE `bg_tbl`
  ADD PRIMARY KEY (`bg_id`);

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
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

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
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
