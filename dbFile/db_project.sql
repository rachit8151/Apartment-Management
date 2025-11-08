-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2024 at 09:15 AM
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
-- Database: `db_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblannouncement`
--

CREATE TABLE `tblannouncement` (
  `announcement_id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `date` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `read_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblannouncement`
--

INSERT INTO `tblannouncement` (`announcement_id`, `type`, `title`, `description`, `date`, `user_id`, `read_status`) VALUES
(1, 'meeting', 'For Renovation', 'We are pleased to announce that the Community Center will undergo renovations starting thank you.', '2024-10-16', 1, 0),
(10, 'notice', 'nnnnnn', 'mmmmiiiiii', '2024-10-26', 1, 0),
(17, 'notice', 'Parking', 'all member is note during apartment color parking ....', '2024-11-15', 1, 0),
(18, 'meeting', 'qwert', 'qwert', '2024-11-18', 14, 0),
(19, 'meeting', 'qwertui', 'qwertyui', '2024-11-18', 14, 0),
(20, 'meeting', 'update', 'success script word in update', '2024-11-18', 14, 0),
(21, 'event', 'For Renovation', 'bopotstarp update', '2024-11-27', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblcomplaints`
--

CREATE TABLE `tblcomplaints` (
  `complaint_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `complaint_text` text NOT NULL,
  `complaint_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcomplaints`
--

INSERT INTO `tblcomplaints` (`complaint_id`, `user_id`, `complaint_text`, `complaint_date`, `status`) VALUES
(1, 1, 'leakage ', '2024-11-08 12:54:00', 'Pending'),
(2, 3, 'Light problem ', '2024-11-08 14:45:13', 'Resolved'),
(3, 3, 'Light ', '2024-11-08 14:45:31', 'Pending'),
(4, 14, 'check', '2024-11-10 16:07:05', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `tblevents`
--

CREATE TABLE `tblevents` (
  `event_id` int(11) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `description` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `read_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblevents`
--

INSERT INTO `tblevents` (`event_id`, `event_date`, `event_time`, `description`, `user_id`, `read_status`) VALUES
(12, '2024-10-28', '12:28:00', 'Award distribute and reach place on time thank you so much.', 1, 0),
(13, '2024-10-22', '01:46:00', 'For Parking during navratri', 1, 0),
(14, '2024-10-31', '12:05:00', 'diwali', 4, 0),
(15, '2024-10-20', '06:31:00', 'sfghj', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblexpenses`
--

CREATE TABLE `tblexpenses` (
  `expense_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblexpenses`
--

INSERT INTO `tblexpenses` (`expense_id`, `user_id`, `amount`, `name`, `date`) VALUES
(1, 1, 100.00, 'Office Supplies', '2024-09-09'),
(2, 2, 250.50, 'Travel Expenses', '2024-10-02'),
(3, 14, 75.75, 'Meal Expenses', '2024-10-03'),
(4, 1, 150.00, 'Marketing Materials', '2024-10-04'),
(5, 2, 300.25, 'Conference Fee', '2024-10-05'),
(6, 14, 50.00, 'Internet Bill', '2024-10-06'),
(7, 1, 200.00, 'Equipment Purchase', '2024-10-07'),
(8, 2, 80.50, 'Office Snacks', '2024-10-08'),
(9, 14, 120.00, 'Team Building Activity', '2024-09-17'),
(10, 1, 175.00, 'Training Session', '2024-10-10'),
(11, 2, 90.00, 'Taxi Fare', '2024-10-11'),
(12, 14, 60.00, 'Supplies Reimbursement', '2024-10-12'),
(15, 4, 250.00, 'Client Dinner', '2024-10-17'),
(16, 4, 95.75, 'Travel Reimbursement', '2024-10-18'),
(18, 4, 140.00, 'Team Lunch', '2024-10-20'),
(19, 1, 5000.00, 'Renovation', '2024-11-01'),
(20, 4, 3000.00, 'Hall Renovation', '2024-11-02'),
(21, 4, 1500.00, 'Security Upgrades', '2024-11-03'),
(22, 14, 2200.00, 'Water Pipe Maintenance', '2024-11-04'),
(23, 14, 4000.00, 'Lift Maintenance', '2024-11-05'),
(24, 1, 1000.00, 'Gardening', '2024-11-06'),
(25, 4, 2500.00, 'Common Area Lighting', '2024-11-07'),
(26, 14, 3500.00, 'Electrical Wiring', '2024-11-08'),
(27, 1, 2000.00, 'Paintwork', '2024-11-09'),
(28, 1, 2200.00, 'Elevator Overhaul', '2024-11-10'),
(29, 1, 800.00, 'Parking color', '2024-11-05');

-- --------------------------------------------------------

--
-- Table structure for table `tblhallbooking`
--

CREATE TABLE `tblhallbooking` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hall_name` varchar(100) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `end_time` time NOT NULL,
  `purpose` text NOT NULL,
  `payment_amount` decimal(10,2) NOT NULL,
  `payment_status` enum('Pending','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  `booking_status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `read_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblhallbooking`
--

INSERT INTO `tblhallbooking` (`booking_id`, `user_id`, `hall_name`, `booking_date`, `booking_time`, `end_time`, `purpose`, `payment_amount`, `payment_status`, `booking_status`, `created_at`, `read_status`) VALUES
(2, 2, 'Hall B', '2024-11-02', '13:00:00', '15:00:00', 'Wedding Reception', 500.00, 'Completed', 'Approved', '2024-10-25 18:07:40', 0),
(3, 14, 'Auditorium', '2024-11-03', '09:00:00', '11:00:00', 'Seminar', 200.00, 'Cancelled', 'Rejected', '2024-10-25 18:07:40', 0),
(4, 1, 'Room 101', '2024-11-04', '14:00:00', '16:00:00', 'Workshop', 100.00, 'Pending', 'Pending', '2024-10-25 18:07:40', 0),
(5, 2, 'Hall C', '2024-11-05', '08:00:00', '10:00:00', 'Birthday Party', 300.00, 'Completed', 'Approved', '2024-10-25 18:07:40', 0),
(6, 14, 'Hall C', '2024-11-06', '11:00:00', '13:00:00', 'Annual Meeting', 250.00, 'Pending', 'Pending', '2024-10-25 18:07:40', 0),
(7, 1, 'Conference Room B', '2024-11-07', '15:00:00', '17:00:00', 'Client Meeting', 175.00, 'Completed', 'Approved', '2024-10-25 18:07:40', 0),
(8, 2, 'Hall D', '2024-11-08', '12:00:00', '14:00:00', 'Product Launch', 600.00, 'Cancelled', 'Rejected', '2024-10-25 18:07:40', 0),
(14, 1, 'Laxmi vadi', '2024-10-29', '01:54:00', '00:54:00', 'no', 0.00, 'Pending', 'Rejected', '2024-10-29 17:24:16', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblhalls`
--

CREATE TABLE `tblhalls` (
  `hall_id` int(11) NOT NULL,
  `hall_name` varchar(100) NOT NULL,
  `capacity` int(11) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblhalls`
--

INSERT INTO `tblhalls` (`hall_id`, `hall_name`, `capacity`, `location`, `amenities`, `visible`) VALUES
(3, 'Laxmi Vadi', 800, 'Near Jahangirpura', 'all facilities', 1),
(4, 'Pragti vadi', 1500, 'Near Palanpur Patiya', 'all d', 1),
(5, 'Patel vadi', 200, 'Near Ugat road', 'Traditional ', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblmaintenance`
--

CREATE TABLE `tblmaintenance` (
  `maintenance_id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `penalty` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblmaintenance`
--

INSERT INTO `tblmaintenance` (`maintenance_id`, `year`, `amount`, `penalty`, `description`, `created_at`, `updated_at`) VALUES
(1, 2024, 8000.00, 0.00, 'AC WANT', '2024-11-23 19:52:47', '2024-11-23 20:03:48'),
(2, 2022, 8000.00, 0.00, 'AC WANT', '2022-11-28 03:47:06', '2022-11-28 04:47:20');

-- --------------------------------------------------------

--
-- Table structure for table `tblpayment`
--

CREATE TABLE `tblpayment` (
  `payment_id` int(11) NOT NULL,
  `maintenance_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_status` enum('Pending','Paid') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblpayment`
--

INSERT INTO `tblpayment` (`payment_id`, `maintenance_id`, `user_id`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Paid', '2024-11-23 19:52:47', '2024-11-23 20:04:01'),
(2, 1, 2, 'Paid', '2024-11-23 19:52:47', '2024-11-23 20:08:10'),
(3, 1, 3, 'Pending', '2024-11-23 19:52:47', '2024-11-23 19:52:47'),
(4, 1, 4, 'Pending', '2024-11-23 19:52:47', '2024-11-23 19:52:47'),
(5, 1, 5, 'Pending', '2024-11-23 19:52:47', '2024-11-23 19:52:47'),
(6, 1, 22, 'Pending', '2024-11-23 19:52:47', '2024-11-23 19:52:47'),
(7, 2, 1, 'Pending', '2022-11-28 03:47:06', '2024-11-28 04:48:40'),
(8, 2, 2, 'Pending', '2022-11-28 03:47:06', '2024-11-28 04:48:46'),
(9, 2, 3, 'Pending', '2022-11-28 03:47:06', '2024-11-28 04:48:56'),
(10, 2, 4, 'Pending', '2022-11-28 03:47:06', '2024-11-28 04:48:59'),
(11, 2, 5, 'Pending', '2022-11-28 03:47:06', '2024-11-28 04:49:04'),
(12, 2, 22, 'Pending', '2022-11-28 03:47:06', '2024-11-28 04:49:07'),
(13, 2, 25, 'Pending', '2022-11-28 03:47:06', '2024-11-28 04:49:10');

-- --------------------------------------------------------

--
-- Table structure for table `tblsecretaryapplications`
--

CREATE TABLE `tblsecretaryapplications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `votes` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsecretaryapplications`
--

INSERT INTO `tblsecretaryapplications` (`id`, `user_id`, `votes`, `created_at`) VALUES
(1, 1, 1, '2024-11-29 22:57:18');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `user_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_role` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `contact` bigint(20) NOT NULL,
  `aadhar_no` bigint(20) NOT NULL,
  `wings` varchar(1) NOT NULL,
  `flat_no` int(4) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`user_id`, `username`, `email`, `password`, `user_role`, `name`, `contact`, `aadhar_no`, `wings`, `flat_no`, `created_at`) VALUES
(1, 'Rachit_8151', 'rachit8151@gmail.com', '541402bd76d308b74abab869ed50fc91', 'secretary', 'Rachit Patel', 9081616891, 765467675432, 'A', 12, '2024-09-30 13:05:58'),
(2, 'Ishita_123', '22bmiit022@gmail.com', '3a340e9df39e7735852b1fbb2088d2d6', 'owner', 'Ishita Patel', 8907654321, 765432178907, 'A', 203, '2024-10-30 13:05:58'),
(3, 'Prachi_123', '22bmiit137@gmail.com', '368c601c9d0d574764e425ecc8eef60a', 'committee member', 'Prachi', 2222233333, 1212123333, 'D', 2, '2024-09-30 13:05:58'),
(4, 'Pooja_123', '22bmiit022@gmail.com', '4bcc674371a91bf32377cd878d754527', 'committee member', 'Pooja', 2222222222, 1212121212, 'D', 1, '2024-10-30 13:05:58'),
(5, 'Prachi_12', '22bmiit011@gmail.com', '513ed164da748f4b2842c12cf37b9563', 'committee member', 'Prachi', 2222233331, 1212123331, 'D', 3, '2024-10-30 13:05:58'),
(14, 'Admin_123', 'rachit1575@gmail.com', '0192023a7bbd73250516f069df18b500', 'admin', 'Aayman Shaikh', 4567898761, 654735241422, 'C', 14, '2024-10-26 13:05:58'),
(22, 'Dhruvil_123', 'rachitpatel8151@gmail.com', 'c943752742949a6a03e8d76b182e7a22', 'owner', 'Dhruvil Patel', 4567898765, 765432178888, 'C', 21, '2024-10-29 13:05:58'),
(23, 'Naishal_123', 'naishal036@gmail.com', 'ef2bc263dfe4143ca13bee83cddbad25', 'admin', 'Naishal Manish Doshi', 9326163059, 123456789101, 'A', 123, '2024-11-15 15:51:41'),
(25, 'Het_123', '22bmiit041@gmail.com', '649b1191c3d1ca4275745d6d9bd83a8a', 'owner', 'Het', 9081616898, 454567678976, 'B', 123, '2024-11-27 16:31:13');

-- --------------------------------------------------------

--
-- Table structure for table `tblvotes`
--

CREATE TABLE `tblvotes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblvotes`
--

INSERT INTO `tblvotes` (`id`, `user_id`, `candidate_id`, `created_at`) VALUES
(1, 1, 1, '2024-11-29 22:58:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblannouncement`
--
ALTER TABLE `tblannouncement`
  ADD PRIMARY KEY (`announcement_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tblcomplaints`
--
ALTER TABLE `tblcomplaints`
  ADD PRIMARY KEY (`complaint_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tblevents`
--
ALTER TABLE `tblevents`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `tblexpenses`
--
ALTER TABLE `tblexpenses`
  ADD PRIMARY KEY (`expense_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tblhallbooking`
--
ALTER TABLE `tblhallbooking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `tblhallbooking_ibfk_1` (`user_id`);

--
-- Indexes for table `tblhalls`
--
ALTER TABLE `tblhalls`
  ADD PRIMARY KEY (`hall_id`),
  ADD UNIQUE KEY `hall_name` (`hall_name`);

--
-- Indexes for table `tblmaintenance`
--
ALTER TABLE `tblmaintenance`
  ADD PRIMARY KEY (`maintenance_id`);

--
-- Indexes for table `tblpayment`
--
ALTER TABLE `tblpayment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `maintenance_id` (`maintenance_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tblsecretaryapplications`
--
ALTER TABLE `tblsecretaryapplications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `contact` (`contact`),
  ADD UNIQUE KEY `aadhar_no` (`aadhar_no`);

--
-- Indexes for table `tblvotes`
--
ALTER TABLE `tblvotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `candidate_id` (`candidate_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblannouncement`
--
ALTER TABLE `tblannouncement`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tblcomplaints`
--
ALTER TABLE `tblcomplaints`
  MODIFY `complaint_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblevents`
--
ALTER TABLE `tblevents`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tblexpenses`
--
ALTER TABLE `tblexpenses`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tblhallbooking`
--
ALTER TABLE `tblhallbooking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tblhalls`
--
ALTER TABLE `tblhalls`
  MODIFY `hall_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tblmaintenance`
--
ALTER TABLE `tblmaintenance`
  MODIFY `maintenance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblpayment`
--
ALTER TABLE `tblpayment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tblsecretaryapplications`
--
ALTER TABLE `tblsecretaryapplications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tblvotes`
--
ALTER TABLE `tblvotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblannouncement`
--
ALTER TABLE `tblannouncement`
  ADD CONSTRAINT `tblannouncement_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`user_id`);

--
-- Constraints for table `tblcomplaints`
--
ALTER TABLE `tblcomplaints`
  ADD CONSTRAINT `tblcomplaints_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`user_id`);

--
-- Constraints for table `tblhallbooking`
--
ALTER TABLE `tblhallbooking`
  ADD CONSTRAINT `tblhallbooking_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tblpayment`
--
ALTER TABLE `tblpayment`
  ADD CONSTRAINT `tblpayment_ibfk_1` FOREIGN KEY (`maintenance_id`) REFERENCES `tblmaintenance` (`maintenance_id`),
  ADD CONSTRAINT `tblpayment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`user_id`);

--
-- Constraints for table `tblsecretaryapplications`
--
ALTER TABLE `tblsecretaryapplications`
  ADD CONSTRAINT `tblsecretaryapplications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`user_id`);

--
-- Constraints for table `tblvotes`
--
ALTER TABLE `tblvotes`
  ADD CONSTRAINT `tblvotes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`user_id`),
  ADD CONSTRAINT `tblvotes_ibfk_2` FOREIGN KEY (`candidate_id`) REFERENCES `tblsecretaryapplications` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
