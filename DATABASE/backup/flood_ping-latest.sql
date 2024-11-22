-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2024 at 07:14 AM
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
-- Database: `flood_ping`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_type` varchar(50) NOT NULL,
  `category_value` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_type`, `category_value`) VALUES
(1, 'account_status', 'Active'),
(2, 'account_status', 'Deactivated'),
(3, 'civil_status', 'Single'),
(4, 'civil_status', 'Married'),
(5, 'civil_status', 'Separated'),
(6, 'civil_status', 'Widowed'),
(7, 'socioeconomic_category', 'Indigent'),
(8, 'socioeconomic_category', 'Single Parent'),
(9, 'socioeconomic_category', 'Senior Citizen'),
(10, 'socioeconomic_category', 'PWD'),
(11, 'socioeconomic_category', 'Indigenous Person'),
(12, 'socioeconomic_category', 'Solo Dweller'),
(13, 'socioeconomic_category', 'Child-Headed Household'),
(14, 'socioeconomic_category', 'Non-Vulnerable'),
(15, 'health_status', 'In good health'),
(16, 'health_status', 'Disabled'),
(17, 'health_status', 'Chronically ill'),
(18, 'health_status', 'Mentally ill'),
(19, 'health_status', 'Pregnant'),
(20, 'health_status', 'Veteran'),
(21, 'health_status', 'Elderly'),
(22, 'health_status', 'Medical care dependent'),
(23, 'sex', 'Male'),
(24, 'sex', 'Female');

-- --------------------------------------------------------

--
-- Table structure for table `residents`
--

CREATE TABLE `residents` (
  `id` int(11) NOT NULL,
  `resident_id` varchar(7) GENERATED ALWAYS AS (concat('00-',lpad(`id`,4,'0'))) VIRTUAL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `mobile_number` int(11) DEFAULT NULL,
  `email_address` varchar(100) DEFAULT NULL,
  `house_lot_number` varchar(50) DEFAULT NULL,
  `street_subdivision_name` varchar(100) DEFAULT NULL,
  `barangay` varchar(50) DEFAULT 'Bagbag',
  `municipality` varchar(50) DEFAULT 'Quezon City',
  `profile_photo_path` varchar(255) DEFAULT NULL,
  `account_status_id` int(11) DEFAULT NULL,
  `civil_status_id` int(11) DEFAULT NULL,
  `health_status_id` int(11) DEFAULT NULL,
  `sex_id` int(11) DEFAULT NULL,
  `socioeconomic_category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `residents`
--

INSERT INTO `residents` (`id`, `first_name`, `middle_name`, `last_name`, `suffix`, `date_of_birth`, `mobile_number`, `email_address`, `house_lot_number`, `street_subdivision_name`, `barangay`, `municipality`, `profile_photo_path`, `account_status_id`, `civil_status_id`, `health_status_id`, `sex_id`, `socioeconomic_category_id`) VALUES
(1, 'ROSE ANN', 'DE VERA', 'DOMINGO', '', '2002-09-23', 914748364, 'domingo.roseann.09232002@gmail.com', '299', 'P. Dela Cruz St', 'Bagbag', 'Quezon City', '../uploads/Pink and Black Pastel Floral Teacher Student School Desktop Wallpaper Background.png', 1, 4, 18, 24, 7),
(2, 'DANIEL JOHN', 'ARTIOLA', 'DOROTEO', 'mine', '2002-07-17', 923564189, 'mine@gmail.com', '17', 'Secret', 'Bagbag', 'Quezon City', '../uploads/tae.jpg', 1, 4, 15, 24, 7),
(3, 'John', 'A.', 'Doe', NULL, '1990-01-01', 2147483647, 'johndoe@example.com', '123', 'Sunset St.', 'Bagbag', 'Quezon City', NULL, 1, 3, 15, 23, 7),
(4, 'Jane', 'B.', 'Smith', 'Jr.', '1985-05-15', 2147483647, 'janesmith@example.com', '456', 'Maple Ave.', 'Bagbag', 'Quezon City', NULL, 1, 4, 16, 24, 8),
(5, 'Alice', 'C.', 'Johnson', NULL, '1992-07-20', 2147483647, 'alicej@example.com', '789', 'Oak Blvd.', 'Bagbag', 'Quezon City', NULL, 1, 3, 17, 23, 9),
(6, 'Bob', 'D.', 'Brown', NULL, '1988-12-10', 2147483647, 'bobbrown@example.com', '321', 'Pine St.', 'Bagbag', 'Quezon City', NULL, 1, 4, 18, 24, 10),
(7, 'Charlie', 'E.', 'Davis', NULL, '1995-03-25', 2147483647, 'charlied@example.com', '654', 'Elm St.', 'Bagbag', 'Quezon City', NULL, 1, 5, 19, 23, 11),
(8, 'Diana', 'F.', 'Miller', 'III', '1993-09-09', 2147483647, 'dianam@example.com', '987', 'Cedar Ave.', 'Bagbag', 'Quezon City', NULL, 1, 6, 20, 24, 12),
(9, 'Evan', 'G.', 'Wilson', NULL, '1997-11-30', 2147483647, 'evanw@example.com', '159', 'Birch Dr.', 'Bagbag', 'Quezon City', NULL, 1, 3, 21, 23, 13),
(10, 'Fiona', 'H.', 'Clark', NULL, '1994-06-06', 2147483647, 'fionac@example.com', '753', 'Spruce Way', 'Bagbag', 'Quezon City', NULL, 1, 4, 22, 24, 14),
(11, 'George', 'I.', 'Lewis', NULL, '1987-08-18', 2147483647, 'georgel@example.com', '951', 'Willow Rd.', 'Bagbag', 'Quezon City', NULL, 1, 5, 15, 23, 7),
(12, 'Hannah', 'J.', 'Martinez', NULL, '1991-02-14', 2147483647, 'hannahm@example.com', '852', 'Aspen Ln.', 'Bagbag', 'Quezon City', NULL, 1, 6, 16, 24, 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','local authority') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `residents`
--
ALTER TABLE `residents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_account_status` (`account_status_id`),
  ADD KEY `fk_civil_status` (`civil_status_id`),
  ADD KEY `fk_health_status` (`health_status_id`),
  ADD KEY `fk_sex` (`sex_id`),
  ADD KEY `fk_socioeconomic_category` (`socioeconomic_category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `residents`
--
ALTER TABLE `residents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `residents`
--
ALTER TABLE `residents`
  ADD CONSTRAINT `fk_account_status` FOREIGN KEY (`account_status_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `fk_civil_status` FOREIGN KEY (`civil_status_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `fk_health_status` FOREIGN KEY (`health_status_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `fk_sex` FOREIGN KEY (`sex_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `fk_socioeconomic_category` FOREIGN KEY (`socioeconomic_category_id`) REFERENCES `categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
