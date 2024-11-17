-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2024 at 03:24 PM
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
-- Database: `la_sms_alert`
--

-- --------------------------------------------------------

--
-- Table structure for table `la_profile`
--

CREATE TABLE `la_profile` (
  `User_ID` int(255) NOT NULL,
  `First_Name` varchar(255) NOT NULL,
  `Middle_Name` varchar(255) NOT NULL,
  `Last_Name` varchar(255) NOT NULL,
  `Suffix` varchar(255) NOT NULL,
  `Birthday` date NOT NULL,
  `Mobile_Num` int(11) NOT NULL,
  `Email_Add` varchar(255) NOT NULL,
  `House_Lot_Num` varchar(255) NOT NULL,
  `Street_Subd_Name` varchar(255) NOT NULL,
  `Barangay` varchar(255) NOT NULL,
  `Municipality` varchar(255) NOT NULL,
  `Role` varchar(255) NOT NULL,
  `Position` varchar(255) NOT NULL,
  `Work_Day_Sched` varchar(255) NOT NULL,
  `Work_Time_Sched` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `status` enum('New') NOT NULL DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `la_profile`
--
ALTER TABLE `la_profile`
  ADD PRIMARY KEY (`User_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `la_profile`
--
ALTER TABLE `la_profile`
  MODIFY `User_ID` int(255) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
