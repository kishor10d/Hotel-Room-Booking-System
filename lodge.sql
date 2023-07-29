-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 29, 2023 at 11:06 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lodge`
--

-- --------------------------------------------------------

--
-- Table structure for table `ldg_bookings`
--

CREATE TABLE `ldg_bookings` (
  `bookingId` int(11) NOT NULL,
  `customerId` int(11) NOT NULL DEFAULT 0,
  `bookingDtm` datetime NOT NULL,
  `floorId` int(11) DEFAULT NULL,
  `roomSizeId` int(11) DEFAULT NULL,
  `roomId` int(11) NOT NULL,
  `bookStartDate` datetime NOT NULL,
  `bookEndDate` datetime NOT NULL,
  `bookingComments` varchar(2048) DEFAULT NULL,
  `isDeleted` tinyint(4) NOT NULL,
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `ldg_bookings`
--

INSERT INTO `ldg_bookings` (`bookingId`, `customerId`, `bookingDtm`, `floorId`, `roomSizeId`, `roomId`, `bookStartDate`, `bookEndDate`, `bookingComments`, `isDeleted`, `createdBy`, `createdDtm`, `updatedBy`, `updatedDtm`) VALUES
(1, 1, '2020-05-24 18:48:20', 1, 3, 6, '2021-01-11 00:00:00', '2021-01-11 00:00:00', NULL, 0, 1, '2020-05-24 18:48:20', NULL, NULL),
(2, 3, '2021-03-19 19:56:20', 1, 2, 4, '2021-03-14 00:00:00', '2021-03-15 00:00:00', '<p>Demo to shrikant is done</p>', 0, 1, '2021-03-19 19:56:20', NULL, NULL),
(3, 1, '2020-05-24 18:56:07', 1, 3, 6, '2020-05-19 00:00:00', '2020-05-20 00:00:00', NULL, 0, 1, '2020-05-24 18:56:07', NULL, NULL),
(4, 2, '2020-09-16 06:58:24', 2, 1, 11, '2020-09-18 00:00:00', '2020-09-18 00:00:00', NULL, 0, 1, '2020-09-16 06:58:25', NULL, NULL),
(5, 2, '2020-09-16 07:10:24', 1, 1, 1, '2020-09-17 00:00:00', '2020-09-18 00:00:00', NULL, 0, 1, '2020-09-16 07:10:24', NULL, NULL),
(6, 2, '2020-09-16 07:11:42', 1, 2, 4, '2020-09-18 00:00:00', '2020-09-25 00:00:00', NULL, 0, 1, '2020-09-16 07:11:42', NULL, NULL),
(7, 3, '2021-03-13 15:12:07', 2, 1, 12, '2021-03-10 00:00:00', '2021-03-12 00:00:00', '<p>Booking detail</p>', 0, 1, '2021-03-13 15:12:07', NULL, NULL),
(8, 3, '2021-03-13 15:28:02', 2, 1, 11, '2021-03-10 00:00:00', '2021-03-12 00:00:00', '<p>Booking another room</p>', 0, 1, '2021-03-13 15:28:02', NULL, NULL),
(9, 1, '2021-03-13 15:40:51', 1, 2, 5, '2021-03-15 00:00:00', '2021-03-16 00:00:00', '<p>Booking for 2 Adults and 3 Childs</p>', 0, 1, '2021-03-13 15:40:51', NULL, NULL),
(10, 3, '2021-03-19 19:58:33', 1, 2, 4, '2021-03-14 00:00:00', '2021-03-15 00:00:00', '<p>Demo to shrikant is doneÂ 2</p>', 0, 1, '2021-03-19 19:58:33', NULL, NULL),
(11, 3, '2021-03-19 20:13:19', 2, 1, 12, '2021-03-20 00:00:00', '2021-03-24 00:00:00', '<p>New Booking Description updated</p>', 0, 1, '2021-03-19 20:13:19', NULL, NULL),
(12, 3, '2021-06-01 16:46:02', 1, 4, 8, '2021-06-16 00:00:00', '2021-06-17 00:00:00', '<p>Customer will checkin on 16th June, 2021 with 3 guests.</p>', 0, 1, '2021-06-01 16:46:02', NULL, NULL),
(13, 1, '2021-06-01 16:48:18', 1, 4, 10, '2021-06-16 00:00:00', '2021-06-16 00:00:00', '', 0, 1, '2021-06-01 16:48:18', NULL, NULL),
(14, 3, '2022-01-01 15:47:02', 2, 1, 12, '2022-01-09 00:00:00', '2022-01-10 00:00:00', '', 0, 1, '2022-01-01 15:47:02', NULL, NULL),
(15, 3, '2023-07-29 22:21:06', 1, 2, 5, '2023-07-31 00:00:00', '2023-08-01 00:00:00', '<p>Booking is confirmed.</p>\r\n<p>Payment done by UPI.</p>', 0, 1, '2023-07-29 22:21:06', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ldg_booking_customer_master`
--

CREATE TABLE `ldg_booking_customer_master` (
  `bcmId` bigint(20) NOT NULL,
  `bookingId` int(11) DEFAULT NULL,
  `customerId` int(11) DEFAULT NULL,
  `createdDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ldg_customer`
--

CREATE TABLE `ldg_customer` (
  `customerId` int(11) NOT NULL,
  `customerName` varchar(50) NOT NULL,
  `customerAddress` varchar(2048) DEFAULT NULL,
  `customerPhone` varchar(15) DEFAULT NULL,
  `customerEmail` varchar(128) DEFAULT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `ldg_customer`
--

INSERT INTO `ldg_customer` (`customerId`, `customerName`, `customerAddress`, `customerPhone`, `customerEmail`, `isDeleted`, `createdBy`, `createdDtm`, `updatedBy`, `updatedDtm`) VALUES
(1, 'John Doe', 'The Big Street Address, Near Corner', '123456789', '', 0, 1, '2017-08-02 18:25:01', 1, '2018-12-30 06:47:31'),
(2, 'Alexander', 'Flamingo', '', 'email@outlook.com', 0, 1, '2017-08-02 18:35:04', 1, '2019-01-26 10:57:42'),
(3, 'Kishor Mali', 'Flat No 7, Govind Apt, Varali, Mumbai', '9890098900', 'customer@example.com', 0, 1, '2021-03-13 15:06:54', NULL, NULL),
(4, 'Nilesh Mali', 'Mumbai', '7410852000', 'nilesh@example.com', 0, 1, '2022-01-01 15:52:38', NULL, NULL),
(5, 'Pralhad Mali', 'Nagpur', '8520741000', 'pralhad@example.com', 0, 1, '2022-01-01 15:53:00', NULL, NULL),
(6, 'Shrikant Mali', 'Nashik', '963', '', 0, 1, '2022-01-01 15:53:30', NULL, NULL),
(7, 'Salad Mali', 'Jalgaon', '', '', 0, 1, '2022-01-01 15:54:28', NULL, NULL),
(8, 'Mahesh Mali', 'Pune', '', 'mahesh@example.com', 0, 1, '2022-01-01 15:54:42', NULL, NULL),
(9, 'Pravin Mali', 'Beed', '7410014788', '', 0, 1, '2022-01-01 15:55:13', NULL, NULL),
(10, 'Quazi Singh', 'Nanded', '', '', 0, 1, '2022-01-01 15:55:54', NULL, NULL),
(11, 'Sallu Patil', '', '', '', 0, 1, '2022-01-01 15:56:06', NULL, NULL),
(12, 'Tarak Mehta', 'Mumbai', '', '', 0, 1, '2022-01-01 15:56:17', NULL, NULL),
(13, 'Jethalal Gada', 'Mumbai', '', '', 0, 1, '2022-01-01 15:56:27', NULL, NULL),
(14, 'Dayaben Gada', 'Surat', '', '', 0, 1, '2022-01-01 15:56:37', NULL, NULL),
(15, 'Tappu Gada', 'Mumbai', '', '', 0, 1, '2022-01-01 15:56:49', NULL, NULL),
(16, 'Atmaram Bhide', 'Mumbai', '', '', 0, 1, '2022-01-01 15:57:01', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ldg_floor`
--

CREATE TABLE `ldg_floor` (
  `floorId` tinyint(4) NOT NULL,
  `floorCode` varchar(10) NOT NULL,
  `floorName` varchar(50) NOT NULL,
  `floorDescription` text NOT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Floor Table';

--
-- Dumping data for table `ldg_floor`
--

INSERT INTO `ldg_floor` (`floorId`, `floorCode`, `floorName`, `floorDescription`, `isDeleted`, `createdBy`, `createdDtm`, `updatedBy`, `updatedDtm`) VALUES
(1, 'GROUND', 'Ground Floor', '<p>Ground Floor having <strong>6</strong> precious rooms and <strong>2</strong> toilet bathrooms.</p>', 0, 1, '2016-12-31 19:25:12', 1, '2017-01-04 18:03:23'),
(2, 'FIRST', 'First Floor', '<p>First Floor having <strong>20</strong> Deluxe Single Bed Rooms, <strong>4</strong> Toilets, <strong>4</strong> Bathrooms in each corner in common.</p>', 0, 1, '2017-01-04 18:01:16', 1, '2017-01-04 18:03:00'),
(3, 'SECOND', 'Second Floor', '<p>Second Floor having <strong>10</strong> double bed rooms with <strong>4</strong> Toilets and <strong>4</strong> Bathrooms in each corner in common.</p>', 0, 1, '2017-01-04 18:02:25', NULL, NULL),
(4, 'THIRD', 'Third Floor', '<p>Third Floor havingÂ <strong>10</strong>Â double bed rooms withÂ <strong>4</strong>Â Toilets andÂ <strong>4</strong>Â Bathrooms in each corner in common.</p>', 0, 1, '2021-03-13 10:49:19', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ldg_lodge`
--

CREATE TABLE `ldg_lodge` (
  `lodgeId` int(11) NOT NULL,
  `lodgeName` varchar(128) NOT NULL,
  `lodgeAddress` varchar(512) NOT NULL,
  `lodgeCity` varchar(50) NOT NULL,
  `lodgeState` varchar(50) NOT NULL,
  `lodgeCountry` varchar(50) DEFAULT NULL,
  `lodgePincode` varchar(10) DEFAULT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` int(11) NOT NULL DEFAULT 0,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Information of lodge';

-- --------------------------------------------------------

--
-- Table structure for table `ldg_reset_password`
--

CREATE TABLE `ldg_reset_password` (
  `id` bigint(20) NOT NULL,
  `email` varchar(128) NOT NULL,
  `activation_id` varchar(32) NOT NULL,
  `agent` varchar(512) NOT NULL,
  `client_ip` varchar(32) NOT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` bigint(20) NOT NULL DEFAULT 1,
  `createdDtm` datetime NOT NULL,
  `updatedBy` bigint(20) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ldg_reset_password`
--

INSERT INTO `ldg_reset_password` (`id`, `email`, `activation_id`, `agent`, `client_ip`, `isDeleted`, `createdBy`, `createdDtm`, `updatedBy`, `updatedDtm`) VALUES
(25, 'email@gmail.com', 'nxwY5JKbbNcTRju', 'Chrome 56.0.2924.87', '0.0.0.0', 0, 1, '2017-03-22 18:11:25', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ldg_roles`
--

CREATE TABLE `ldg_roles` (
  `roleId` tinyint(4) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Information of roles';

--
-- Dumping data for table `ldg_roles`
--

INSERT INTO `ldg_roles` (`roleId`, `role`) VALUES
(1, 'System Administrator'),
(2, 'Lodge Manager'),
(3, 'Booker');

-- --------------------------------------------------------

--
-- Table structure for table `ldg_rooms`
--

CREATE TABLE `ldg_rooms` (
  `roomId` int(11) NOT NULL,
  `roomNumber` varchar(50) NOT NULL,
  `roomSizeId` int(11) NOT NULL COMMENT 'FK : ldg_room_sizes',
  `floorId` tinyint(4) NOT NULL COMMENT 'FK : ldg_floor',
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` int(11) NOT NULL DEFAULT 0,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Information of rooms';

--
-- Dumping data for table `ldg_rooms`
--

INSERT INTO `ldg_rooms` (`roomId`, `roomNumber`, `roomSizeId`, `floorId`, `isDeleted`, `createdBy`, `createdDtm`, `updatedBy`, `updatedDtm`) VALUES
(1, 'G101', 1, 1, 0, 1, '2017-01-10 17:26:22', NULL, NULL),
(2, 'G102', 1, 1, 0, 1, '2017-01-10 17:29:34', NULL, NULL),
(3, 'G103', 1, 1, 0, 1, '2017-01-10 17:29:43', NULL, NULL),
(4, 'G104', 2, 1, 0, 1, '2017-01-18 18:13:45', NULL, NULL),
(5, 'G105', 2, 1, 0, 1, '2017-01-18 18:15:22', NULL, NULL),
(6, 'G106', 3, 1, 0, 1, '2017-01-18 18:15:43', NULL, NULL),
(7, 'G107', 3, 1, 0, 1, '2017-01-18 18:15:52', NULL, NULL),
(8, 'G108', 4, 1, 0, 1, '2017-01-18 18:16:08', NULL, NULL),
(9, 'G109', 5, 1, 0, 1, '2017-01-18 18:16:51', NULL, NULL),
(10, 'G110', 4, 1, 0, 1, '2017-01-18 18:16:35', 1, '2017-02-11 18:46:23'),
(11, 'F101', 1, 2, 0, 1, '2019-02-01 19:14:26', NULL, NULL),
(12, 'F102', 1, 2, 0, 1, '2019-02-01 19:14:37', NULL, NULL),
(13, 'F103', 2, 2, 0, 1, '2019-02-01 19:14:46', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ldg_room_base_fare`
--

CREATE TABLE `ldg_room_base_fare` (
  `bfId` bigint(20) NOT NULL,
  `sizeId` int(11) NOT NULL,
  `baseFareHour` double NOT NULL,
  `baseFareDay` double NOT NULL,
  `serviceTax` double NOT NULL,
  `serviceCharge` double NOT NULL,
  `fareTotal` double NOT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `ldg_room_base_fare`
--

INSERT INTO `ldg_room_base_fare` (`bfId`, `sizeId`, `baseFareHour`, `baseFareDay`, `serviceTax`, `serviceCharge`, `fareTotal`, `isDeleted`, `createdBy`, `createdDtm`, `updatedBy`, `updatedDtm`) VALUES
(1, 1, 40, 500, 15, 3.5, 592.5, 0, 1, '2017-02-11 19:14:24', 1, '2017-02-11 19:16:14'),
(2, 2, 55, 700, 15, 3.5, 829.5, 0, 1, '2017-02-11 19:19:52', 1, '2017-02-11 19:25:38'),
(3, 3, 60, 800, 15, 3.5, 948, 0, 1, '2017-02-11 19:20:07', NULL, NULL),
(4, 4, 70, 900, 15, 3.5, 1066.5, 0, 1, '2017-02-11 19:20:35', NULL, NULL),
(5, 5, 100, 1000, 15, 3.5, 1185, 0, 1, '2017-02-11 19:20:52', 1, '2019-01-26 11:05:08');

-- --------------------------------------------------------

--
-- Table structure for table `ldg_room_sizes`
--

CREATE TABLE `ldg_room_sizes` (
  `sizeId` int(11) NOT NULL,
  `sizeTitle` varchar(512) NOT NULL,
  `sizeDescription` text NOT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` int(11) NOT NULL DEFAULT 0,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Information of room sizes';

--
-- Dumping data for table `ldg_room_sizes`
--

INSERT INTO `ldg_room_sizes` (`sizeId`, `sizeTitle`, `sizeDescription`, `isDeleted`, `createdBy`, `createdDtm`, `updatedBy`, `updatedDtm`) VALUES
(1, 'Deluxe Single Bed Room', '<ol>\r\n<li>Single Bed</li>\r\n<li>Toilet + Bathroom</li>\r\n<li>Hot Water : Morning 5am to 9am</li>\r\n</ol>', 0, 1, '2017-01-04 16:55:01', 1, '2020-05-25 09:04:27'),
(2, 'Deluxe Double Bed Room', '<ol>\r\n<li>Joint Double Bed</li>\r\n<li>Toilet + Bathroom</li>\r\n<li>Hot Water : Morning 5am to 9am</li>\r\n</ol>', 0, 1, '2017-01-04 18:05:53', 1, '2017-01-04 18:06:34'),
(3, 'Premium Double Bed Room', '<ol>\r\n<li>Two Single Beds</li>\r\n<li>Toilet + Bathroom</li>\r\n<li>Hot Water : Morning 5am to 9am</li>\r\n</ol>', 0, 1, '2017-01-04 18:07:56', 1, '2017-01-04 18:08:19'),
(4, 'AC Single Bed Room', '<ol>\r\n<li>Single Bed</li>\r\n<li>Toilet + Bathroom</li>\r\n<li>Hot Water : Morning 5am to 9am</li>\r\n<li>Air Conditioned Room</li>\r\n</ol>', 0, 1, '2017-01-04 18:09:09', 1, '2017-01-04 18:11:05'),
(5, 'AC Double Bed Room', '<ol>\r\n<li>Double Joint Bed</li>\r\n<li>Toilet + Bathroom</li>\r\n<li>Hot Water : Morning 5am to 9am</li>\r\n<li>Air Conditioned</li>\r\n</ol>', 0, 1, '2017-01-04 18:09:47', 1, '2021-03-13 10:50:07');

-- --------------------------------------------------------

--
-- Table structure for table `ldg_users`
--

CREATE TABLE `ldg_users` (
  `userId` int(11) NOT NULL,
  `userEmail` varchar(128) NOT NULL,
  `userPassword` varchar(128) NOT NULL,
  `userName` varchar(50) NOT NULL,
  `userPhone` varchar(20) NOT NULL,
  `userAddress` varchar(1024) NOT NULL,
  `roleId` tinyint(4) NOT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` int(11) NOT NULL DEFAULT 0,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Information of administrative users';

--
-- Dumping data for table `ldg_users`
--

INSERT INTO `ldg_users` (`userId`, `userEmail`, `userPassword`, `userName`, `userPhone`, `userAddress`, `roleId`, `isDeleted`, `createdBy`, `createdDtm`, `updatedBy`, `updatedDtm`) VALUES
(1, 'email@gmail.com', '$2y$10$W0JwINh/A4eadWvp1.AxkejudEgv8Wg5vUMCcX4MKtdoCimQieBdK', 'Super Admin', '9890098900', 'Pune India', 1, 0, 1, '2017-01-01 00:00:00', NULL, NULL),
(2, 'subadmin@gmail.com', '$2y$10$sqyx0XUQhJxIJ6lq9adpV.ioq97zngNXeT33b/n5M2KbWdyzfALie', 'Sub Admin', '9890098900', '', 2, 0, 1, '2017-03-23 18:19:38', 1, '2023-07-29 22:37:44'),
(3, 'admin@example.com', '$2y$10$W0JwINh/A4eadWvp1.AxkejudEgv8Wg5vUMCcX4MKtdoCimQieBdK', 'Book Admin', '9890098900', '', 3, 0, 1, '2017-03-24 16:26:31', 1, '2023-07-29 22:38:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ldg_bookings`
--
ALTER TABLE `ldg_bookings`
  ADD PRIMARY KEY (`bookingId`);

--
-- Indexes for table `ldg_booking_customer_master`
--
ALTER TABLE `ldg_booking_customer_master`
  ADD PRIMARY KEY (`bcmId`);

--
-- Indexes for table `ldg_customer`
--
ALTER TABLE `ldg_customer`
  ADD PRIMARY KEY (`customerId`);

--
-- Indexes for table `ldg_floor`
--
ALTER TABLE `ldg_floor`
  ADD PRIMARY KEY (`floorId`),
  ADD UNIQUE KEY `floorCode` (`floorCode`);

--
-- Indexes for table `ldg_lodge`
--
ALTER TABLE `ldg_lodge`
  ADD PRIMARY KEY (`lodgeId`);

--
-- Indexes for table `ldg_reset_password`
--
ALTER TABLE `ldg_reset_password`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ldg_roles`
--
ALTER TABLE `ldg_roles`
  ADD PRIMARY KEY (`roleId`);

--
-- Indexes for table `ldg_rooms`
--
ALTER TABLE `ldg_rooms`
  ADD PRIMARY KEY (`roomId`);

--
-- Indexes for table `ldg_room_base_fare`
--
ALTER TABLE `ldg_room_base_fare`
  ADD PRIMARY KEY (`bfId`);

--
-- Indexes for table `ldg_room_sizes`
--
ALTER TABLE `ldg_room_sizes`
  ADD PRIMARY KEY (`sizeId`);

--
-- Indexes for table `ldg_users`
--
ALTER TABLE `ldg_users`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ldg_bookings`
--
ALTER TABLE `ldg_bookings`
  MODIFY `bookingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `ldg_booking_customer_master`
--
ALTER TABLE `ldg_booking_customer_master`
  MODIFY `bcmId` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ldg_customer`
--
ALTER TABLE `ldg_customer`
  MODIFY `customerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `ldg_floor`
--
ALTER TABLE `ldg_floor`
  MODIFY `floorId` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ldg_lodge`
--
ALTER TABLE `ldg_lodge`
  MODIFY `lodgeId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ldg_reset_password`
--
ALTER TABLE `ldg_reset_password`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `ldg_roles`
--
ALTER TABLE `ldg_roles`
  MODIFY `roleId` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ldg_rooms`
--
ALTER TABLE `ldg_rooms`
  MODIFY `roomId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `ldg_room_base_fare`
--
ALTER TABLE `ldg_room_base_fare`
  MODIFY `bfId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ldg_room_sizes`
--
ALTER TABLE `ldg_room_sizes`
  MODIFY `sizeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ldg_users`
--
ALTER TABLE `ldg_users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
