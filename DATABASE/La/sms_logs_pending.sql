-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2024 at 03:02 PM
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
-- Database: `admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `sms_logs_pending`
--

CREATE TABLE `sms_logs_pending` (
  `sending_id` int(11) NOT NULL,
  `date_sent` date NOT NULL,
  `recipients` int(11) NOT NULL,
  `flood_id` int(11) NOT NULL,
  `flood_level` enum('Normal','Low','Moderate','Critical') NOT NULL,
  `sending_progress` varchar(50) DEFAULT 'In Progress'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sms_logs_pending`
--
ALTER TABLE `sms_logs_pending`
  ADD PRIMARY KEY (`sending_id`),
  ADD KEY `flood_id` (`flood_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sms_logs_pending`
--
ALTER TABLE `sms_logs_pending`
  MODIFY `sending_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sms_logs_pending`
--
ALTER TABLE `sms_logs_pending`
  ADD CONSTRAINT `sms_logs_pending_ibfk_1` FOREIGN KEY (`flood_id`) REFERENCES `flood_alerts` (`flood_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
