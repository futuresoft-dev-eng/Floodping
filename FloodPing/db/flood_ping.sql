-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2024 at 07:48 AM
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
-- Table structure for table `archive_accounts`
--

CREATE TABLE `archive_accounts` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `suffix` varchar(50) DEFAULT NULL,
  `contact_no` varchar(15) DEFAULT NULL,
  `sex` enum('Male','Female') DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `house_lot_number` varchar(255) DEFAULT NULL,
  `street_subdivision_name` varchar(255) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `schedule` varchar(255) DEFAULT NULL,
  `shift` varchar(255) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `archived_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archive_accounts`
--

INSERT INTO `archive_accounts` (`id`, `user_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `contact_no`, `sex`, `birthdate`, `email`, `city`, `barangay`, `house_lot_number`, `street_subdivision_name`, `role`, `position`, `schedule`, `shift`, `profile_photo`, `archived_at`) VALUES
(34, '00003', 'Lumiere', 'Law', 'Choi', '', '09154664654', 'Male', '2024-11-23', 'angela@gmail.com', 'Quezon City', 'Bagbag', 'Blk 2 Lot 19', 'Blas Roque Subdivision', 'Admin', 'Executive Officer', 'TUE', 'Morning Shift', '', '2024-11-22 10:45:58'),
(35, '00003', 'Lumiere', 'Law', 'Choi', '', '09789456123', 'Male', '2024-11-05', 'terry@gmail.com', 'Quezon City', 'Bagbag', 'Blk 2 Lot 19', 'Blas Roque Subdivision', 'Admin', 'Executive Officer', NULL, NULL, '', '2024-11-22 11:05:07'),
(36, '00003', 'Lumiere', 'Law', 'Choi', '', '09654565455', 'Male', '2024-11-03', 'angelatallon.123@gmail.com', 'Quezon City', 'Bagbag', 'Blk 2 Lot 19', 'Blas Roque Subdivision', 'Admin', 'Executive Officer', NULL, NULL, 'uploads/67401d619b837_Screenshot 2024-08-28 155113.png', '2024-11-22 13:59:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_id` varchar(5) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `contact_no` varchar(15) NOT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `birthdate` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Local Authority') NOT NULL,
  `schedule` varchar(255) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `shift` varchar(255) DEFAULT NULL,
  `account_status` enum('Active','Inactive','Locked') DEFAULT 'Active',
  `position` varchar(255) DEFAULT NULL,
  `house_lot_number` varchar(255) DEFAULT NULL,
  `street_subdivision_name` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `failed_attempts` int(11) DEFAULT 0,
  `last_attempt_date` date DEFAULT NULL,
  `last_attempt_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `contact_no`, `sex`, `birthdate`, `email`, `password`, `role`, `schedule`, `profile_photo`, `shift`, `account_status`, `position`, `house_lot_number`, `street_subdivision_name`, `city`, `barangay`, `failed_attempts`, `last_attempt_date`, `last_attempt_time`) VALUES
(105, '00001', 'Beomgyu', 'Law', 'Choi', '', '09195738798', 'Male', '2024-03-13', 'beomgyu@gmail.com', '$2y$10$zMAxcmPJ3DQHyeX8CnNgXevkuO1ECMMsp0JZccbIWheYZbBK7AtVa', 'Admin', 'TUE, THU', 'uploads/1732107157_Screenshot 2024-11-13 140048.png', 'Mid Shift', 'Active', 'Executive Officer', 'Blk 2 Lot 19', 'Blas Roque Subdivision', 'Quezon City', 'Bagbag', 0, NULL, NULL),
(106, '00002', 'Taehyun', 'Law', 'Kang', '', '09303530960', 'Male', '2024-02-05', 'ms4ngela@gmail.com', '$2y$10$C6W4tpImoXtHv1zUSQ8XK.C0oivcVnwZixVSdCvC2JLxYr2hPrHou', 'Local Authority', 'MON, TUE, WED', 'uploads/1732107236_Screenshot 2024-11-13 135944.png', 'Morning Shift', 'Active', 'Executive Officer', 'Blk 2 Lot 19', 'Blas Roque Subdivision', 'Quezon City', 'Bagbag', 0, NULL, NULL);

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `check_duplicate_contact` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    -- Check if the contact number already exists in the database
    IF EXISTS (SELECT 1 FROM users WHERE contact_no = NEW.contact_no) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Contact number already exists.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_duplicate_contact_before_update` BEFORE UPDATE ON `users` FOR EACH ROW BEGIN
    IF OLD.contact_no != NEW.contact_no AND EXISTS (SELECT 1 FROM users WHERE contact_no = NEW.contact_no) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Contact number already exists.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_duplicate_email_before_update` BEFORE UPDATE ON `users` FOR EACH ROW BEGIN
    IF OLD.email != NEW.email AND EXISTS (SELECT 1 FROM users WHERE email = NEW.email) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Email address already exists.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `prevent_duplicate_email` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    IF EXISTS (
        SELECT 1
        FROM users
        WHERE email = NEW.email
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duplicate email addresses are not allowed.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `validate_contact_no` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    IF NEW.contact_no NOT REGEXP '^09[0-9]{9}$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid mobile number. Must be 11 digits and start with 09.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `validate_contact_no_update` BEFORE UPDATE ON `users` FOR EACH ROW BEGIN
    IF NEW.contact_no NOT REGEXP '^09[0-9]{9}$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid mobile number. Must be 11 digits and start with 09.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `validate_email` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    IF NEW.email NOT REGEXP '^[A-Za-z0-9._%+-]+@gmail\.com$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid email address. Must end with @gmail.com.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `validate_email_update` BEFORE UPDATE ON `users` FOR EACH ROW BEGIN
    IF NEW.email NOT REGEXP '^[A-Za-z0-9._%+-]+@gmail\.com$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid email address. Must end with @gmail.com.';
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archive_accounts`
--
ALTER TABLE `archive_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `archive_accounts`
--
ALTER TABLE `archive_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
