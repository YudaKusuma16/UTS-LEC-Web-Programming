-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2024 at 01:29 PM
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
-- Database: `event_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `max_participants` int(11) NOT NULL,
  `current_participants` int(11) DEFAULT 0,
  `status` varchar(50) NOT NULL DEFAULT 'Open',
  `total_tickets` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `date`, `time`, `location`, `price`, `image`, `max_participants`, `current_participants`, `status`, `total_tickets`) VALUES
(4, 'Marketing Mastery', 'A comprehensive marketing event', '2024-11-19', '10:00:00', 'Online', 0.00, 'images/event4.jpeg', 15, 18, '0', NULL),
(5, 'Waste to Energy Summit', 'Focus on renewable energy', '2024-11-30', '09:00:00', 'Jakarta Convention Center', 0.00, 'images/event1.jpeg', 20, 3, 'Open', NULL),
(6, 'Leadership School', 'Leadership training event', '2024-11-25', '08:00:00', 'BSD City', 0.00, 'images/event2.jpeg', 35, 4, 'Open', NULL),
(7, 'AI Conference', 'A comprehensive conference on Artificial Intelligence and its applications.', '2024-11-15', '09:00:00', 'Online', 0.00, 'images/event3.jpeg', 20, 10, 'Open', NULL),
(8, 'Business Growth Summit', 'A summit focusing on business growth strategies and trends.', '2024-11-20', '09:00:00', 'Jakarta Convention Center', 0.00, 'images/event5.jpeg', 40, 3, '0', NULL),
(9, 'Marketing 101', 'An introductory workshop on marketing strategies and concepts.', '2024-12-01', '10:00:00', 'Online', 0.00, 'images/event6.jpeg', 45, 0, 'Open', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(4, 'totti.freda@gmail.com', '6d1a6dac36928365079fc6efc439e06677bba05e5ec84e8c3b54f79c13bbb07dab7bcb9db9f0ba099bd4074ec6e555c344b9', '2024-10-25 05:12:49', '2024-10-25 02:12:49'),
(7, 'qqq@gmail.com', '02044807aea2e77c97def2393cf58aca672add7578435740d5d6d4644461f9410585df6bcef5bb69338a8a8a2e4a78e99e11', '2024-10-25 05:39:09', '2024-10-25 02:39:09'),
(8, 'totti27@gmail.com', 'a3a3d56581c54f3ba96d389645d318a9e3114fc15804d517121a5993cbb89f148614edb4639feb21696cd4781fc67df322f6', '2024-10-25 13:57:56', '2024-10-25 10:57:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_image` varchar(255) DEFAULT 'fotoguest.jpeg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `profile_image`) VALUES
(2, 'ujangXD', 'ujang@gmail.com', '$2y$10$wc6sJUR44Jj4AzJ9K/HSgurSfeHCf5ZQMCsn2W1IFHfKwVnCf5HtW', '2024-10-24 09:40:41', 'fotoguest.jpg'),
(3, 'TOTTI FAWWAZ REDA', 'totti.freda@gmail.com', '$2y$10$OKDjatN7I2Yw6qCMG8Q55erfenO7XeskjjiNAYtf1j7qr2DHGVncy', '2024-10-24 09:50:59', 'uploads/671a2b5738027.jpeg'),
(4, 'Saber Roam', 'saberroam@gmail.com', '$2y$10$VDBRS7K3jIg8ULmn9V/enuxKinM3bQmlTk/mT4ph0X6zKlkk6VJ2W', '2024-10-24 14:17:04', 'uploads/احببت شيطانه.jpeg'),
(5, 'daimen', 'daimen@gmail.com', '$2y$10$Vx3oElARq07rVCS2J8kuc.ybVs9/V.pUMRevXYKpgzcHSdiqKFGMy', '2024-10-25 01:42:39', 'uploads/Hyouka Oreki 🎴.jpeg'),
(6, 'qqq', 'qqq@gmail.com', '$2y$10$ydkhY1hWY3NTVqCFJsqtI.Qr8w.MTPLifdTZRhyI38pvw7LCJYy56', '2024-10-25 01:46:29', 'uploads/download (2).jpeg'),
(7, 'TOTTI FAWWAZ REDA', 'totti.freda@admin.com', '$2y$10$3MuU16AJY2bZD1ED6U7FIuUdgrSSJU7a5XxiI1yuurGv5Wk8Zrbga', '2024-10-25 03:05:09', 'fotoguest.jpeg'),
(8, 'totti khaleed', 'khaleed@admin.com', '$2y$10$pU114YUXKieMPPjpzSChbuzPvRx0NfvUViQyxB2cWdceVaAEBoNdu', '2024-10-25 03:10:59', 'fotoguest.jpeg'),
(9, 'totti fawwaz', 'tottifawwaz@admin.com', '$2y$10$PyVPYSzU512TIOfTq7X2cerEDbU/3RF93HoSQih0rjiOetspkko0u', '2024-10-25 08:02:25', 'fotoguest.jpeg'),
(10, 'totti reda', 'tottireda@gmail.com', '$2y$10$R5oHNXG.hRd/S4DOMwho6.RMZt1U5Ai1CTWVRgT8njsWarcQf6TrG', '2024-10-25 10:39:12', 'uploads/Hyouka Oreki 🎴.jpeg'),
(12, 'totti fawwaz reda', 'totti27@gmail.com', '$2y$10$SrbhOekbHpii3Mcl5mMuw.Rxr1IuaQfFu6fQv7Z4mkzQZUIQPoD0q', '2024-10-25 10:51:57', 'uploads/Hyouka Oreki 🎴.jpeg'),
(13, 'lingroam', 'lingroam@admin.com', '$2y$10$wJpZigTC4JI0dhQ7RGY.Kek.NVxDJnOsNr48VQclbb2PQBVBcqGTK', '2024-10-25 10:56:51', 'fotoguest.jpeg'),
(14, 'Totti Fawwaz R', 'totti11@gmail.com', '$2y$10$9EBZPkvHpC7n/h24ahK14eC1CpmVMVO5FknIcCYfamkVSEv/4GANy', '2024-10-25 11:07:16', 'uploads/احببت شيطانه.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `user_tickets`
--

CREATE TABLE `user_tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `num_tickets` int(11) NOT NULL DEFAULT 1,
  `total_payment` decimal(10,2) DEFAULT 0.00,
  `payment_method` varchar(50) NOT NULL,
  `phone_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_tickets`
--

INSERT INTO `user_tickets` (`id`, `user_id`, `event_id`, `created_at`, `num_tickets`, `total_payment`, `payment_method`, `phone_number`) VALUES
(19, 14, 4, '2024-10-25 11:08:09', 3, 0.00, 'OVO', '08818134828'),
(21, 14, 7, '2024-10-25 11:09:13', 2, 0.00, 'OVO', '08818134828');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_tickets`
--
ALTER TABLE `user_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_tickets`
--
ALTER TABLE `user_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_tickets`
--
ALTER TABLE `user_tickets`
  ADD CONSTRAINT `user_tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_tickets_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
