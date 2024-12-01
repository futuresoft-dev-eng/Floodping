-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2024 at 04:35 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
-- Table structure for table `sensor_data`
--

CREATE TABLE `sensor_data` (
  `id` int(11) NOT NULL,
  `meters` float DEFAULT NULL,
  `rate` float NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `alert_level` varchar(20) DEFAULT NULL,
  `status` enum('NEW','ENTRY') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sensor_data`
--

INSERT INTO `sensor_data` (`id`, `meters`, `rate`, `timestamp`, `alert_level`, `status`) VALUES
(5942, 0.9, 0.5, '2024-11-28 01:05:32', 'CRITICAL LEVEL', 'NEW'),
(5979, 1.5, 0.5, '2024-11-28 01:00:00', 'CRITICAL LEVEL', 'ENTRY'),
(5980, 1.5, 0.5, '2024-11-28 01:05:00', 'CRITICAL LEVEL', 'ENTRY'),
(5981, 1.2, 0.3, '2024-11-28 01:10:00', 'MEDIUM LEVEL', 'NEW'),
(5982, 1.3, 0.4, '2024-11-28 01:15:00', 'MEDIUM LEVEL', 'ENTRY'),
(5983, 1.4, 0.6, '2024-11-28 01:20:00', 'LOW LEVEL', 'NEW'),
(5984, 1.2, 0.3, '2024-11-28 01:25:00', 'LOW LEVEL', 'ENTRY'),
(5985, 1.6, 0.7, '2024-11-28 01:30:00', 'CRITICAL LEVEL', 'NEW'),
(5986, 1.4, 0.6, '2024-11-28 01:35:00', 'CRITICAL LEVEL', 'ENTRY'),
(5987, 2, 0.6, '2024-11-28 01:40:00', 'LOW LEVEL', 'NEW'),
(5988, 1.8, 0.5, '2024-11-28 01:45:00', 'LOW LEVEL', 'ENTRY'),
(5989, 1.6, 0.4, '2024-11-28 01:50:00', 'MEDIUM LEVEL', 'NEW'),
(5990, 5, 0.5, '2024-11-28 01:55:00', 'MEDIUM LEVEL', 'ENTRY'),
(5991, 5, 0.7, '2024-11-28 02:00:00', 'CRITICAL LEVEL', 'NEW'),
(5992, 1.6, 0.6, '2024-11-28 02:05:00', 'CRITICAL LEVEL', 'ENTRY'),
(5993, 1.2, 0.3, '2024-11-30 01:15:32', 'MEDIUM LEVEL', 'NEW'),
(5994, 3.4, 0.3, '0000-00-00 00:00:00', 'LOW LEVEL', 'NEW');

--
-- Triggers `sensor_data`
--
DELIMITER $$
CREATE TRIGGER `assign_status` BEFORE INSERT ON `sensor_data` FOR EACH ROW BEGIN
    DECLARE prev_alert_level VARCHAR(50);
    DECLARE prev_id INT;
    SELECT alert_level, id INTO prev_alert_level, prev_id
    FROM sensor_data
    ORDER BY id DESC
    LIMIT 1;
    IF prev_alert_level IS NULL OR NEW.alert_level != prev_alert_level THEN
        SET NEW.status = 'NEW';
    ELSE
        SET NEW.status = 'ENTRY';
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sensor_data`
--
ALTER TABLE `sensor_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sensor_data`
--
ALTER TABLE `sensor_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5995;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
