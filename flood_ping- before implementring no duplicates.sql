-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2024 at 06:40 PM
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
-- Table structure for table `flood_alerts`
--

CREATE TABLE `flood_alerts` (
  `flood_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `height` decimal(5,2) NOT NULL,
  `speed` decimal(5,2) NOT NULL,
  `flow` enum('Rising','Lowering') NOT NULL,
  `water_level` enum('Normal','Low','Moderate','Critical') NOT NULL,
  `status` enum('New') NOT NULL DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flood_alerts`
--

INSERT INTO `flood_alerts` (`flood_id`, `date`, `time`, `height`, `speed`, `flow`, `water_level`, `status`) VALUES
(1, '2024-11-17', '08:30:00', 10.00, 2.50, 'Rising', 'Low', 'New'),
(2, '2024-11-17', '14:45:00', 13.50, 3.20, 'Rising', 'Moderate', 'New'),
(3, '2024-11-17', '20:15:00', 15.00, 3.80, 'Rising', 'Critical', 'New'),
(4, '2024-11-17', '14:30:00', 10.50, 2.20, 'Rising', 'Low', 'New');

-- --------------------------------------------------------

--
-- Table structure for table `predefined_messages`
--

CREATE TABLE `predefined_messages` (
  `id` int(11) NOT NULL,
  `level` enum('Normal','Low','Moderate','Critical') NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `predefined_messages`
--

INSERT INTO `predefined_messages` (`id`, `level`, `message`) VALUES
(1, 'Low', 'Water level is 10m. Low flood risk identified. Please consider self-evacuation for safety. Stay updated.'),
(2, 'Moderate', 'Water level reached 13m, moderate flood risk. Immediate evacuation recommended for your safety.'),
(3, 'Critical', 'Water level at 15m, critical flood risk. Remain in evacuation sites until further notice for safety.');

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
  `mobile_number` varchar(11) DEFAULT NULL,
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
(1, 'ROSE ANN', 'DE VERA', 'DOMINGO', '', '2002-09-23', '914748364', 'domingo.roseann.09232002@gmail.com', '299', 'P. Dela Cruz St', 'Bagbag', 'Quezon City', '../uploads/Pink and Black Pastel Floral Teacher Student School Desktop Wallpaper Background.png', 1, 4, 18, 24, 7),
(2, 'DANIEL JOHN', 'ARTIOLA', 'DOROTEO', 'mine', '2002-07-17', '923564189', 'mine@gmail.com', '17', 'Secret', 'Bagbag', 'Quezon City', '../uploads/tae.jpg', 1, 4, 15, 24, 7),
(3, 'Johnhjjjj', 'A.', 'Doe', '', '1990-01-01', '2147483647', 'johndoe@example.com', '123', 'Sunset St.', 'Bagbag', 'Quezon City', '../uploads/taeee.jpg', 1, 3, 15, 23, 7),
(209, 'John', 'A.', 'Doe', NULL, '1990-01-01', '2147483647', 'johndoe@example.com', '123', 'Sunset St.', 'Bagbag', 'Quezon City', NULL, 1, 3, 15, 23, 7),
(210, 'Jane', 'B.', 'Smith', 'Jr.', '1985-05-15', '2147483647', 'janesmith@example.com', '456', 'Maple Ave.', 'Bagbag', 'Quezon City', NULL, 1, 4, 16, 24, 8),
(211, 'Alice', 'C.', 'Johnson', NULL, '1992-07-20', '2147483647', 'alicej@example.com', '789', 'Oak Blvd.', 'Bagbag', 'Quezon City', NULL, 1, 3, 17, 23, 9),
(212, 'Bob', 'D.', 'Brown', NULL, '1988-12-10', '2147483647', 'bobbrown@example.com', '321', 'Pine St.', 'Bagbag', 'Quezon City', NULL, 1, 4, 18, 24, 10),
(213, 'Charlie', 'E.', 'Davis', NULL, '1995-03-25', '2147483647', 'charlied@example.com', '654', 'Elm St.', 'Bagbag', 'Quezon City', NULL, 1, 5, 19, 23, 11),
(219, 'John', 'A.', 'Doe', NULL, '1990-01-01', '2147483647', 'johndoe@example.com', '123', 'Sunset St.', 'Bagbag', 'Quezon City', NULL, 1, 3, 15, 23, 7),
(220, 'Jane', 'B.', 'Smith', 'Jr.', '1985-05-15', '2147483647', 'janesmith@example.com', '456', 'Maple Ave.', 'Bagbag', 'Quezon City', NULL, 1, 4, 16, 24, 8),
(221, 'Alice', 'C.', 'Johnson', NULL, '1992-07-20', '2147483647', 'alicej@example.com', '789', 'Oak Blvd.', 'Bagbag', 'Quezon City', NULL, 1, 3, 17, 23, 9),
(222, 'Bob', 'D.', 'Brown', NULL, '1988-12-10', '2147483647', 'bobbrown@example.com', '321', 'Pine St.', 'Bagbag', 'Quezon City', NULL, 1, 4, 18, 24, 10),
(223, 'Charlie', 'E.', 'Davis', NULL, '1995-03-25', '2147483647', 'charlied@example.com', '654', 'Elm St.', 'Bagbag', 'Quezon City', NULL, 1, 5, 19, 23, 11),
(224, 'Diana', 'F.', 'Miller', 'III', '1993-09-09', '2147483647', 'dianam@example.com', '987', 'Cedar Ave.', 'Bagbag', 'Quezon City', NULL, 1, 6, 20, 24, 12),
(225, 'Evan', 'G.', 'Wilson', NULL, '1997-11-30', '2147483647', 'evanw@example.com', '159', 'Birch Dr.', 'Bagbag', 'Quezon City', NULL, 1, 3, 21, 23, 13),
(226, 'Fiona', 'H.', 'Clark', NULL, '1994-06-06', '2147483647', 'fionac@example.com', '753', 'Spruce Way', 'Bagbag', 'Quezon City', NULL, 1, 4, 22, 24, 14),
(227, 'George', 'I.', 'Lewis', NULL, '1987-08-18', '2147483647', 'georgel@example.com', '951', 'Willow Rd.', 'Bagbag', 'Quezon City', NULL, 1, 5, 15, 23, 7),
(228, 'Hannah', 'J.', 'Martinez', NULL, '1991-02-14', '2147483647', 'hannahm@example.com', '852', 'Aspen Ln.', 'Bagbag', 'Quezon City', NULL, 1, 6, 16, 24, 8),
(229, 'John', 'A.', 'Doe', NULL, '1990-01-01', '2147483647', 'johndoe@example.com', '123', 'Sunset St.', 'Bagbag', 'Quezon City', NULL, 1, 3, 15, 23, 7),
(230, 'Jane', 'B.', 'Smith', 'Jr.', '1985-05-15', '2147483647', 'janesmith@example.com', '456', 'Maple Ave.', 'Bagbag', 'Quezon City', NULL, 1, 4, 16, 24, 8),
(231, 'Alice', 'C.', 'Johnson', NULL, '1992-07-20', '2147483647', 'alicej@example.com', '789', 'Oak Blvd.', 'Bagbag', 'Quezon City', NULL, 1, 3, 17, 23, 9),
(232, 'Bob', 'D.', 'Brown', NULL, '1988-12-10', '2147483647', 'bobbrown@example.com', '321', 'Pine St.', 'Bagbag', 'Quezon City', NULL, 1, 4, 18, 24, 10),
(233, 'Charlie', 'E.', 'Davis', NULL, '1995-03-25', '2147483647', 'charlied@example.com', '654', 'Elm St.', 'Bagbag', 'Quezon City', NULL, 1, 5, 19, 23, 11),
(234, 'Diana', 'F.', 'Miller', 'III', '1993-09-09', '2147483647', 'dianam@example.com', '987', 'Cedar Ave.', 'Bagbag', 'Quezon City', NULL, 1, 6, 20, 24, 12),
(235, 'Evan', 'G.', 'Wilson', NULL, '1997-11-30', '2147483647', 'evanw@example.com', '159', 'Birch Dr.', 'Bagbag', 'Quezon City', NULL, 1, 3, 21, 23, 13),
(236, 'Fiona', 'H.', 'Clark', NULL, '1994-06-06', '2147483647', 'fionac@example.com', '753', 'Spruce Way', 'Bagbag', 'Quezon City', NULL, 1, 4, 22, 24, 14),
(237, 'George', 'I.', 'Lewis', NULL, '1987-08-18', '2147483647', 'georgel@example.com', '951', 'Willow Rd.', 'Bagbag', 'Quezon City', NULL, 1, 5, 15, 23, 7),
(238, 'Hannah', 'J.', 'Martinez', '', '1991-02-14', '09103165421', 'hannahm@example.com', '852', 'Aspen ln.', 'Bagbag', 'Quezon city', NULL, 1, 6, 16, 24, 8),
(239, 'John', 'A.', 'Doe', NULL, '1990-01-01', '2147483647', 'johndoe@example.com', '123', 'Sunset St.', 'Bagbag', 'Quezon City', NULL, 1, 3, 15, 23, 7),
(240, 'Jane', 'B.', 'Smith', 'Jr.', '1985-05-15', '2147483647', 'janesmith@example.com', '456', 'Maple Ave.', 'Bagbag', 'Quezon City', NULL, 1, 4, 16, 24, 8),
(241, 'Alice', 'C.', 'Johnson', NULL, '1992-07-20', '2147483647', 'alicej@example.com', '789', 'Oak Blvd.', 'Bagbag', 'Quezon City', NULL, 1, 3, 17, 23, 9),
(243, 'ROSE ANN', 'E.', 'DOMINGO', '', '2024-11-21', '2147483647', 'charlied@example.com', '654', 'Elm St.', 'Bagbag', 'Quezon City', '../uploads/taee.jpg', 1, 5, 19, 23, 11),
(244, 'TRY', 'KO', 'HA', 'III', '2024-11-21', '2147483647', 'dianam@example.com', '987', 'Cedar Ave.', 'Bagbag', 'Quezon City', NULL, 1, 6, 20, 23, 12),
(245, 'Taehyung', 'Rose', 'Kim', '', '1997-11-30', '2147483647', 'evanw@example.com', '159', 'Birch Dr.', 'Bagbag', 'Quezon City', '../uploads/taeee.jpg', 1, 3, 21, 23, 13),
(259, 'John', 'A.', 'Doe', NULL, '1990-01-01', '2147483647', 'johndoe@example.com', '123', 'Sunset St.', 'Bagbag', 'Quezon City', NULL, 1, 3, 15, 23, 7),
(260, 'Jane', 'B.', 'Smith', 'Jr.', '1985-05-15', '2147483647', 'janesmith@example.com', '456', 'Maple Ave.', 'Bagbag', 'Quezon City', NULL, 1, 4, 16, 24, 8),
(261, 'Alice', 'C.', 'Johnson', NULL, '1992-07-20', '2147483647', 'alicej@example.com', '789', 'Oak Blvd.', 'Bagbag', 'Quezon City', NULL, 1, 3, 17, 23, 9),
(262, 'Bob', 'D.', 'Brown', NULL, '1988-12-10', '2147483647', 'bobbrown@example.com', '321', 'Pine St.', 'Bagbag', 'Quezon City', NULL, 1, 4, 18, 24, 10),
(263, 'Charlie', 'E.', 'Davis', NULL, '1995-03-25', '2147483647', 'charlied@example.com', '654', 'Elm St.', 'Bagbag', 'Quezon City', NULL, 1, 5, 19, 23, 11),
(264, 'Diana', 'F.', 'Miller', 'III', '1993-09-09', '2147483647', 'dianam@example.com', '987', 'Cedar Ave.', 'Bagbag', 'Quezon City', NULL, 1, 6, 20, 24, 12),
(265, 'Evan', 'G.', 'Wilson', NULL, '1997-11-30', '2147483647', 'evanw@example.com', '159', 'Birch Dr.', 'Bagbag', 'Quezon City', NULL, 1, 3, 21, 23, 13),
(266, 'Fiona', 'H.', 'Clark', NULL, '1994-06-06', '2147483647', 'fionac@example.com', '753', 'Spruce Way', 'Bagbag', 'Quezon City', NULL, 1, 4, 22, 24, 14),
(267, 'George', 'I.', 'Lewis', NULL, '1987-08-18', '2147483647', 'georgel@example.com', '951', 'Willow Rd.', 'Bagbag', 'Quezon City', NULL, 1, 5, 15, 23, 7),
(268, 'Hannah', 'J.', 'Martinez', NULL, '1991-02-14', '2147483647', 'hannahm@example.com', '852', 'Aspen Ln.', 'Bagbag', 'Quezon City', NULL, 1, 6, 16, 24, 8),
(269, 'John', 'A.', 'Doe', NULL, '1990-01-01', '2147483647', 'johndoe@example.com', '123', 'Sunset St.', 'Bagbag', 'Quezon City', NULL, 1, 3, 15, 23, 7),
(270, 'Jane', 'B.', 'Smith', 'Jr.', '1985-05-15', '2147483647', 'janesmith@example.com', '456', 'Maple Ave.', 'Bagbag', 'Quezon City', NULL, 1, 4, 16, 24, 8),
(271, 'Alice', 'C.', 'Johnson', NULL, '1992-07-20', '2147483647', 'alicej@example.com', '789', 'Oak Blvd.', 'Bagbag', 'Quezon City', NULL, 1, 3, 17, 23, 9),
(272, 'Bob', 'D.', 'Brown', NULL, '1988-12-10', '2147483647', 'bobbrown@example.com', '321', 'Pine St.', 'Bagbag', 'Quezon City', NULL, 1, 4, 18, 24, 10),
(273, 'Charlie', 'E.', 'Davis', NULL, '1995-03-25', '2147483647', 'charlied@example.com', '654', 'Elm St.', 'Bagbag', 'Quezon City', NULL, 1, 5, 19, 23, 11),
(274, 'Diana', 'F.', 'Miller', 'III', '1993-09-09', '2147483647', 'dianam@example.com', '987', 'Cedar Ave.', 'Bagbag', 'Quezon City', NULL, 1, 6, 20, 24, 12),
(275, 'Evan', 'G.', 'Wilson', NULL, '1997-11-30', '2147483647', 'evanw@example.com', '159', 'Birch Dr.', 'Bagbag', 'Quezon City', NULL, 1, 3, 21, 23, 13),
(276, 'Fiona', 'H.', 'Clark', NULL, '1994-06-06', '2147483647', 'fionac@example.com', '753', 'Spruce Way', 'Bagbag', 'Quezon City', NULL, 1, 4, 22, 24, 14),
(277, 'George', 'I.', 'Lewis', NULL, '1987-08-18', '2147483647', 'georgel@example.com', '951', 'Willow Rd.', 'Bagbag', 'Quezon City', NULL, 1, 5, 15, 23, 7),
(278, 'Hannah', 'J.', 'Martinez', NULL, '1991-02-14', '2147483647', 'hannahm@example.com', '852', 'Aspen Ln.', 'Bagbag', 'Quezon City', NULL, 1, 6, 16, 24, 8),
(279, 'John', 'A.', 'Doe', NULL, '1990-01-01', '2147483647', 'johndoe@example.com', '123', 'Sunset St.', 'Bagbag', 'Quezon City', NULL, 1, 3, 15, 23, 7),
(280, 'Jane', 'B.', 'Smith', 'Jr.', '1985-05-15', '2147483647', 'janesmith@example.com', '456', 'Maple Ave.', 'Bagbag', 'Quezon City', NULL, 1, 4, 16, 24, 8),
(281, 'Alice', 'C.', 'Johnson', NULL, '1992-07-20', '2147483647', 'alicej@example.com', '789', 'Oak Blvd.', 'Bagbag', 'Quezon City', NULL, 1, 3, 17, 23, 9),
(282, 'Bob', 'D.', 'Brown', NULL, '1988-12-10', '2147483647', 'bobbrown@example.com', '321', 'Pine St.', 'Bagbag', 'Quezon City', NULL, 1, 4, 18, 24, 10),
(283, 'Charlie', 'E.', 'Davis', NULL, '1995-03-25', '2147483647', 'charlied@example.com', '654', 'Elm St.', 'Bagbag', 'Quezon City', NULL, 1, 5, 19, 23, 11),
(284, 'Diana', 'F.', 'Miller', 'III', '1993-09-09', '2147483647', 'dianam@example.com', '987', 'Cedar Ave.', 'Bagbag', 'Quezon City', NULL, 1, 6, 20, 24, 12),
(285, 'Evan', 'G.', 'Wilson', NULL, '1997-11-30', '2147483647', 'evanw@example.com', '159', 'Birch Dr.', 'Bagbag', 'Quezon City', NULL, 1, 3, 21, 23, 13),
(286, 'Fiona', 'H.', 'Clark', NULL, '1994-06-06', '2147483647', 'fionac@example.com', '753', 'Spruce Way', 'Bagbag', 'Quezon City', NULL, 1, 4, 22, 24, 14),
(287, 'George', 'I.', 'Lewis', NULL, '1987-08-18', '2147483647', 'georgel@example.com', '951', 'Willow Rd.', 'Bagbag', 'Quezon City', NULL, 1, 5, 15, 23, 7),
(288, 'Hannah', 'J.', 'Martinez', NULL, '1991-02-14', '2147483647', 'hannahm@example.com', '852', 'Aspen Ln.', 'Bagbag', 'Quezon City', NULL, 1, 6, 16, 24, 8),
(289, 'John', 'A.', 'Doe', NULL, '1990-01-01', '2147483647', 'johndoe@example.com', '123', 'Sunset St.', 'Bagbag', 'Quezon City', NULL, 1, 3, 15, 23, 7),
(290, 'Jane', 'B.', 'Smith', 'Jr.', '1985-05-15', '2147483647', 'janesmith@example.com', '456', 'Maple Ave.', 'Bagbag', 'Quezon City', NULL, 1, 4, 16, 24, 8),
(291, 'Alice', 'C.', 'Johnson', NULL, '1992-07-20', '2147483647', 'alicej@example.com', '789', 'Oak Blvd.', 'Bagbag', 'Quezon City', NULL, 1, 3, 17, 23, 9),
(292, 'Bob', 'D.', 'Brown', NULL, '1988-12-10', '2147483647', 'bobbrown@example.com', '321', 'Pine St.', 'Bagbag', 'Quezon City', NULL, 1, 4, 18, 24, 10),
(295, 'Evan', 'G.', 'Wilson', NULL, '1997-11-30', '2147483647', 'evanw@example.com', '159', 'Birch Dr.', 'Bagbag', 'Quezon City', NULL, 1, 3, 21, 23, 13),
(296, 'Fiona', 'H.', 'Clark', NULL, '1994-06-06', '2147483647', 'fionac@example.com', '753', 'Spruce Way', 'Bagbag', 'Quezon City', NULL, 1, 4, 22, 24, 14),
(299, 'John', 'A.', 'Doe', NULL, '1990-01-01', '2147483647', 'johndoe@example.com', '123', 'Sunset St.', 'Bagbag', 'Quezon City', NULL, 1, 3, 15, 23, 7),
(300, 'Jane', 'B.', 'Smith', 'Jr.', '1985-05-15', '2147483647', 'janesmith@example.com', '456', 'Maple Ave.', 'Bagbag', 'Quezon City', NULL, 1, 4, 16, 24, 8),
(301, 'Alice', 'C.', 'Johnson', NULL, '1992-07-20', '2147483647', 'alicej@example.com', '789', 'Oak Blvd.', 'Bagbag', 'Quezon City', NULL, 1, 3, 17, 23, 9),
(302, 'Bob', 'D.', 'Brown', NULL, '1988-12-10', '2147483647', 'bobbrown@example.com', '321', 'Pine St.', 'Bagbag', 'Quezon City', NULL, 1, 4, 18, 24, 10),
(303, 'Charlie', 'E.', 'Davis', NULL, '1995-03-25', '2147483647', 'charlied@example.com', '654', 'Elm St.', 'Bagbag', 'Quezon City', NULL, 1, 5, 19, 23, 11),
(304, 'Diana', 'F.', 'Miller', 'III', '1993-09-09', '2147483647', 'dianam@example.com', '987', 'Cedar Ave.', 'Bagbag', 'Quezon City', NULL, 1, 6, 20, 24, 12),
(305, 'Evan', 'G.', 'Wilson', NULL, '1997-11-30', '2147483647', 'evanw@example.com', '159', 'Birch Dr.', 'Bagbag', 'Quezon City', NULL, 1, 3, 21, 23, 13),
(306, 'Fiona', 'H.', 'Clark', NULL, '1994-06-06', '2147483647', 'fionac@example.com', '753', 'Spruce Way', 'Bagbag', 'Quezon City', NULL, 1, 4, 22, 24, 14),
(309, 'John', 'A.', 'Doe', NULL, '1990-01-01', '2147483647', 'johndoe@example.com', '123', 'Sunset St.', 'Bagbag', 'Quezon City', NULL, 1, 3, 15, 23, 7),
(310, 'Jane', 'B.', 'Smith', 'Jr.', '1985-05-15', '2147483647', 'janesmith@example.com', '456', 'Maple Ave.', 'Bagbag', 'Quezon City', NULL, 1, 4, 16, 24, 8),
(311, 'Alice', 'C.', 'Johnson', NULL, '1992-07-20', '2147483647', 'alicej@example.com', '789', 'Oak Blvd.', 'Bagbag', 'Quezon City', NULL, 1, 3, 17, 23, 9),
(312, 'Bob', 'D.', 'Brown', NULL, '1988-12-10', '2147483647', 'bobbrown@example.com', '321', 'Pine St.', 'Bagbag', 'Quezon City', NULL, 1, 4, 18, 24, 10),
(313, 'Charlie', 'E.', 'Davis', NULL, '1995-03-25', '2147483647', 'charlied@example.com', '654', 'Elm St.', 'Bagbag', 'Quezon City', NULL, 1, 5, 19, 23, 11),
(314, 'Diana', 'F.', 'Miller', 'III', '1993-09-09', '2147483647', 'dianam@example.com', '987', 'Cedar Ave.', 'Bagbag', 'Quezon City', NULL, 1, 6, 20, 24, 12),
(315, 'Evan', 'G.', 'Wilson', NULL, '1997-11-30', '2147483647', 'evanw@example.com', '159', 'Birch Dr.', 'Bagbag', 'Quezon City', NULL, 1, 3, 21, 23, 13),
(329, 'John', 'A.', 'Doe', NULL, '1990-01-01', '2147483647', 'johndoe@example.com', '123', 'Sunset St.', 'Bagbag', 'Quezon City', NULL, 1, 3, 15, 23, 7),
(330, 'Jane', 'B.', 'Smith', 'Jr.', '1985-05-15', '2147483647', 'janesmith@example.com', '456', 'Maple Ave.', 'Bagbag', 'Quezon City', NULL, 1, 4, 16, 24, 8),
(331, 'Alice', 'C.', 'Johnson', NULL, '1992-07-20', '2147483647', 'alicej@example.com', '789', 'Oak Blvd.', 'Bagbag', 'Quezon City', NULL, 2, 3, 17, 23, 9),
(332, 'Bob', 'D.', 'Brown', NULL, '1988-12-10', '2147483647', 'bobbrown@example.com', '321', 'Pine St.', 'Bagbag', 'Quezon City', NULL, 2, 4, 18, 24, 10),
(333, 'Charlie', 'E.', 'Davis', NULL, '1995-03-25', '2147483647', 'charlied@example.com', '654', 'Elm St.', 'Bagbag', 'Quezon City', NULL, 2, 5, 19, 23, 11),
(334, 'Diana', 'F.', 'Miller', 'III', '1993-09-09', '2147483647', 'dianam@example.com', '987', 'Cedar Ave.', 'Bagbag', 'Quezon City', NULL, 1, 6, 20, 24, 12),
(335, 'Evan', 'G.', 'Wilson', NULL, '1997-11-30', '2147483647', 'evanw@example.com', '159', 'Birch Dr.', 'Bagbag', 'Quezon City', NULL, 1, 3, 21, 23, 13),
(336, 'Fiona', 'H.', 'Clark', NULL, '1994-06-06', '2147483647', 'fionac@example.com', '753', 'Spruce Way', 'Bagbag', 'Quezon City', NULL, 1, 4, 22, 24, 14),
(337, 'Rose ann', 'Hdsagfhsadgfyhdsa', 'Ghdfdsaduigdfiaweegdf', '', '2000-09-23', '09212010213', 'georgel@gmail.com', '951', 'Hffhddhf', 'Bagbag', 'Quezon city', NULL, 1, 5, 15, 23, 7),
(339, 'hsduifhuowehrfo', 'I.kdjdj', 'GHDFDSADUIGDFIAWEEGDF', '', '2024-11-22', '09235698742', 'domingo.roseann.09232002@gmail.com', 'ewr', 'hffhddhf', 'Bagbag', 'Quezon City', '', 1, 3, 15, 24, 7),
(340, 'hsduifhuowehrfo', 'I.kdjdj', 'GHDFDSADUIGDFIAWEEGDF', '', '2024-11-30', '09103547896', 'roseanndomingo0923@gmail.com', 'ewr', 'hffhddhf', 'Bagbag', 'Quezon City', '', 1, 3, 15, 24, 7),
(341, 'hsduifhuowehrfo', 'I.kdjdj', 'GHDFDSADUIGDFIAWEEGDF', '', '2000-09-22', '09123654253', 'roseanndomingo0923@gmail.com', 'ewr', 'hffhddhf', 'Bagbag', 'Quezon City', '', 1, 3, 15, 24, 7);

--
-- Triggers `residents`
--
DELIMITER $$
CREATE TRIGGER `validate_age` BEFORE INSERT ON `residents` FOR EACH ROW BEGIN
    IF TIMESTAMPDIFF(YEAR, NEW.date_of_birth, CURDATE()) < 18 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid age. Resident must be at least 18 years old.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `validate_age_update` BEFORE UPDATE ON `residents` FOR EACH ROW BEGIN
    IF TIMESTAMPDIFF(YEAR, NEW.date_of_birth, CURDATE()) < 18 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid age. Resident must be at least 18 years old.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `validate_email_address` BEFORE INSERT ON `residents` FOR EACH ROW BEGIN
    IF NEW.email_address NOT REGEXP '^[A-Za-z0-9._%+-]+@gmail\.com$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid email address. Must end with @gmail.com.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `validate_email_address_update` BEFORE UPDATE ON `residents` FOR EACH ROW BEGIN
    IF NEW.email_address NOT REGEXP '^[A-Za-z0-9._%+-]+@gmail\.com$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid email address. Must end with @gmail.com.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `validate_mobile_number` BEFORE INSERT ON `residents` FOR EACH ROW BEGIN
    IF NEW.mobile_number NOT REGEXP '^09[0-9]{9}$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid mobile number. Must be 11 digits and start with 09.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `validate_mobile_number_update` BEFORE UPDATE ON `residents` FOR EACH ROW BEGIN
    IF NEW.mobile_number NOT REGEXP '^09[0-9]{9}$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid mobile number. Must be 11 digits and start with 09.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sms_alerts`
--

CREATE TABLE `sms_alerts` (
  `sms_alert_id` int(11) NOT NULL,
  `flood_id` int(11) NOT NULL,
  `date_sent` date NOT NULL,
  `time_sent` time NOT NULL,
  `recipients` int(11) NOT NULL,
  `sending_progress` varchar(50) DEFAULT 'Pending',
  `status` enum('Pending','Sent','Failed') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_logs_pending`
--

CREATE TABLE `sms_logs_pending` (
  `sending_id` int(11) NOT NULL,
  `date_sent` date NOT NULL,
  `recipients` int(11) NOT NULL,
  `flood_id` int(11) NOT NULL,
  `water_level` enum('Normal','Low','Moderate','Critical') NOT NULL,
  `sending_progress` varchar(50) DEFAULT 'In Progress'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_logs_sent`
--

CREATE TABLE `sms_logs_sent` (
  `sms_batch_id` int(11) NOT NULL,
  `date_sent` date NOT NULL,
  `time_sent` time NOT NULL,
  `success_count` int(11) NOT NULL,
  `failed_count` int(11) NOT NULL,
  `recipients` int(11) NOT NULL,
  `flood_id` int(11) NOT NULL,
  `water_level` enum('Normal','Low','Moderate','Critical') NOT NULL,
  `credits_consumed` int(11) NOT NULL,
  `sent_by` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sms_logs_sent`
--

INSERT INTO `sms_logs_sent` (`sms_batch_id`, `date_sent`, `time_sent`, `success_count`, `failed_count`, `recipients`, `flood_id`, `water_level`, `credits_consumed`, `sent_by`) VALUES
(1, '2024-11-17', '15:29:38', 140, 10, 150, 1, 'Low', 140, 'LA one');

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
-- Indexes for table `flood_alerts`
--
ALTER TABLE `flood_alerts`
  ADD PRIMARY KEY (`flood_id`);

--
-- Indexes for table `predefined_messages`
--
ALTER TABLE `predefined_messages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `level` (`level`);

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
-- Indexes for table `sms_alerts`
--
ALTER TABLE `sms_alerts`
  ADD PRIMARY KEY (`sms_alert_id`),
  ADD KEY `flood_id` (`flood_id`);

--
-- Indexes for table `sms_logs_pending`
--
ALTER TABLE `sms_logs_pending`
  ADD PRIMARY KEY (`sending_id`),
  ADD KEY `flood_id` (`flood_id`);

--
-- Indexes for table `sms_logs_sent`
--
ALTER TABLE `sms_logs_sent`
  ADD PRIMARY KEY (`sms_batch_id`),
  ADD KEY `flood_id` (`flood_id`);

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
-- AUTO_INCREMENT for table `flood_alerts`
--
ALTER TABLE `flood_alerts`
  MODIFY `flood_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `predefined_messages`
--
ALTER TABLE `predefined_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `residents`
--
ALTER TABLE `residents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=342;

--
-- AUTO_INCREMENT for table `sms_alerts`
--
ALTER TABLE `sms_alerts`
  MODIFY `sms_alert_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_logs_pending`
--
ALTER TABLE `sms_logs_pending`
  MODIFY `sending_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sms_logs_sent`
--
ALTER TABLE `sms_logs_sent`
  MODIFY `sms_batch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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

--
-- Constraints for table `sms_alerts`
--
ALTER TABLE `sms_alerts`
  ADD CONSTRAINT `sms_alerts_ibfk_1` FOREIGN KEY (`flood_id`) REFERENCES `flood_alerts` (`flood_id`) ON DELETE CASCADE;

--
-- Constraints for table `sms_logs_pending`
--
ALTER TABLE `sms_logs_pending`
  ADD CONSTRAINT `sms_logs_pending_ibfk_1` FOREIGN KEY (`flood_id`) REFERENCES `flood_alerts` (`flood_id`) ON DELETE CASCADE;

--
-- Constraints for table `sms_logs_sent`
--
ALTER TABLE `sms_logs_sent`
  ADD CONSTRAINT `sms_logs_sent_ibfk_1` FOREIGN KEY (`flood_id`) REFERENCES `flood_alerts` (`flood_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
