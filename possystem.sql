-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2025 at 04:40 PM
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
-- Database: `possystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `productId` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `productType` varchar(255) NOT NULL,
  `type` int(11) NOT NULL COMMENT '0-In, 1-Out\r\n',
  `requestedBy` int(11) NOT NULL COMMENT '0-admin, 1-staff',
  `transactionId` int(11) NOT NULL,
  `transactionStatus` int(11) NOT NULL COMMENT '0-pending, 1-approved, 2-rejected\r\n',
  `dateOfIn` date NOT NULL,
  `dateOfOut` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`productId`, `productName`, `quantity`, `productType`, `type`, `requestedBy`, `transactionId`, `transactionStatus`, `dateOfIn`, `dateOfOut`) VALUES
(4, 'Bans', 5, '', 1, 0, 0, 1, '0000-00-00', '2025-04-17'),
(5, 'Bans', 5, '', 1, 0, 0, 1, '0000-00-00', '2025-04-17'),
(6, 'Sugar', 100, '2', 1, 0, 0, 1, '0000-00-00', '2025-04-17'),
(7, 'Sugar', 100, '2', 1, 0, 0, 1, '0000-00-00', '2025-04-17'),
(8, 'Bans', 20, '1', 0, 0, 0, 1, '2025-04-17', '0000-00-00'),
(9, 'Bans', 200, '', 0, 0, 0, 0, '0000-00-00', '0000-00-00'),
(10, 'Bans', 200, '', 0, 0, 0, 0, '0000-00-00', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `menuId` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `productPrice` int(11) NOT NULL,
  `productImage` varchar(255) NOT NULL,
  `productType` int(11) NOT NULL COMMENT '0-Shawarma, 1-Burger, 2-Fries, 3-Rice, 4-Drinks\r\n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menuId`, `productName`, `productPrice`, `productImage`, `productType`) VALUES
(3, 'Shawarma Large', 100, 'uploads/1744512698_shawarma.jpg', 0),
(4, 'Chicken Shawarma Burger', 120, 'uploads/1744513920_chickenShawarma.jpg', 1),
(5, 'Shawarma Rice', 130, 'uploads/1744515135_ShawarmaRice.jpg', 3),
(6, 'Coke', 30, 'uploads/1744515167_coke.jpg', 4),
(7, 'Fries', 45, 'uploads/1744515238_Fries.jpg', 2),
(8, 'Shawarma Solo', 80, 'uploads/1744515758_shawarma.jpg', 0),
(9, 'Fires (BBQ)', 45, 'uploads/1744516008_bbq-fries.jpg', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notificationId` int(11) NOT NULL,
  `notificationTarget` int(11) NOT NULL,
  `notificationType` int(11) NOT NULL COMMENT '0-Request, 1-Alert, 2-Response',
  `transactionKey` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `notificationStatus` int(11) NOT NULL COMMENT '0-unread, 1-mark as read\r\n',
  `requestStatus` int(11) NOT NULL COMMENT '0 - pending, 1 - Finish',
  `notificationMessage` varchar(255) NOT NULL,
  `notes` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notificationId`, `notificationTarget`, `notificationType`, `transactionKey`, `productId`, `notificationStatus`, `requestStatus`, `notificationMessage`, `notes`) VALUES
(1, 0, 0, 21, 0, 1, 0, 'You have a request for In item that need your approval', 'No notes provided'),
(2, 0, 0, 23, 0, 1, 0, 'You have a request for Out item that need your approval', 'eme lang'),
(3, 0, 0, 24, 0, 1, 0, 'You have a request for In item that need your approval', 'pukpok mo sa ulo mo'),
(4, 0, 0, 25, 0, 1, 0, 'You have a request for In item that need your approval', 'pamukpok sa ulo'),
(5, 0, 0, 26, 0, 1, 0, 'You have a request for Out item that need your approval', 'No notes provided'),
(6, 0, 0, 27, 0, 1, 0, 'You have a request for Out item that need your approval', 'Pang bato sa ulo'),
(7, 0, 0, 28, 0, 1, 0, 'You have a request for In item that need your approval', 'Pamalo sa Ulo'),
(8, 0, 0, 29, 0, 1, 0, 'You have a request for In item that need your approval', 'No notes provided');

-- --------------------------------------------------------

--
-- Table structure for table `orderedhistory`
--

CREATE TABLE `orderedhistory` (
  `historyId` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  `totalCost` float NOT NULL,
  `dateOfOrder` date NOT NULL,
  `timeOfOrder` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderedhistory`
--

INSERT INTO `orderedhistory` (`historyId`, `orderId`, `totalCost`, `dateOfOrder`, `timeOfOrder`) VALUES
(1, 1, 210, '2025-04-13', '00:00:00'),
(2, 2, 180, '2025-04-13', '07:05:19'),
(3, 3, 310, '2025-04-13', '07:07:01'),
(4, 4, 255, '2025-04-13', '07:27:50'),
(5, 5, 450, '2025-04-13', '07:34:31'),
(6, 6, 510, '2025-04-13', '07:44:55'),
(7, 7, 240, '2025-04-16', '03:55:01'),
(8, 8, 260, '2025-04-17', '09:13:24'),
(9, 9, 120, '2025-04-17', '09:43:13'),
(10, 10, 240, '2025-04-17', '09:46:28'),
(11, 11, 240, '2025-04-17', '09:47:04'),
(12, 12, 200, '2025-04-17', '09:51:35'),
(13, 13, 180, '2025-04-17', '09:55:20'),
(14, 14, 200, '2025-04-17', '09:55:39'),
(15, 15, 240, '2025-04-17', '10:01:23'),
(16, 16, 180, '2025-04-17', '10:02:23'),
(17, 17, 160, '2025-04-17', '10:06:32'),
(18, 18, 80, '2025-04-17', '10:07:28'),
(19, 19, 600, '2025-04-17', '10:17:55'),
(20, 20, 350, '2025-04-17', '10:36:17'),
(21, 21, 230, '2025-04-17', '11:42:44'),
(22, 22, 240, '2025-04-17', '11:46:54'),
(23, 23, 180, '2025-04-17', '11:50:13'),
(24, 24, 425, '2025-04-17', '12:16:25'),
(25, 25, 200, '2025-04-17', '14:22:41'),
(26, 26, 350, '2025-04-21', '15:57:54');

-- --------------------------------------------------------

--
-- Table structure for table `ordereditemhistory`
--

CREATE TABLE `ordereditemhistory` (
  `historyId` int(11) NOT NULL,
  `itemKey` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `productPrice` float NOT NULL,
  `quantity` int(11) NOT NULL,
  `totalPrice` float NOT NULL,
  `dateOfOrder` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ordereditemhistory`
--

INSERT INTO `ordereditemhistory` (`historyId`, `itemKey`, `productName`, `productPrice`, `quantity`, `totalPrice`, `dateOfOrder`) VALUES
(1, 1, 'Shawarma Solo', 80, 1, 80, '2025-04-13'),
(2, 1, 'Shawarma Large', 100, 1, 100, '2025-04-13'),
(3, 1, 'Coke', 30, 1, 30, '2025-04-13'),
(12, 2, 'Shawarma Large', 100, 1, 100, '2025-04-13'),
(13, 2, 'Shawarma Solo', 80, 1, 80, '2025-04-13'),
(14, 3, 'Chicken Shawarma Burger', 120, 1, 120, '2025-04-13'),
(15, 3, 'Shawarma Rice', 130, 1, 130, '2025-04-13'),
(16, 3, 'Coke', 30, 2, 60, '2025-04-13'),
(17, 4, 'Shawarma Solo', 80, 1, 80, '2025-04-13'),
(18, 4, 'Fries', 45, 1, 45, '2025-04-13'),
(19, 4, 'Shawarma Rice', 130, 1, 130, '2025-04-13'),
(20, 5, 'Chicken Shawarma Burger', 120, 1, 120, '2025-04-13'),
(21, 5, 'Shawarma Solo', 80, 1, 80, '2025-04-13'),
(22, 5, 'Shawarma Large', 100, 1, 100, '2025-04-13'),
(23, 5, 'Coke', 30, 2, 60, '2025-04-13'),
(24, 5, 'Fries', 45, 2, 90, '2025-04-13'),
(25, 6, 'Chicken Shawarma Burger', 120, 4, 480, '2025-04-13'),
(26, 6, 'Coke', 30, 1, 30, '2025-04-13'),
(27, 7, 'Chicken Shawarma Burger', 120, 2, 240, '2025-04-16'),
(28, 8, 'Shawarma Rice', 130, 2, 260, '2025-04-17'),
(29, 9, 'Chicken Shawarma Burger', 120, 1, 120, '2025-04-17'),
(30, 10, 'Chicken Shawarma Burger', 120, 2, 240, '2025-04-17'),
(31, 11, 'Chicken Shawarma Burger', 120, 2, 240, '2025-04-17'),
(32, 12, 'Chicken Shawarma Burger', 120, 1, 120, '2025-04-17'),
(33, 12, 'Shawarma Solo', 80, 1, 80, '2025-04-17'),
(34, 13, 'Shawarma Large', 100, 1, 100, '2025-04-17'),
(35, 13, 'Shawarma Solo', 80, 1, 80, '2025-04-17'),
(36, 14, 'Chicken Shawarma Burger', 120, 1, 120, '2025-04-17'),
(37, 14, 'Shawarma Solo', 80, 1, 80, '2025-04-17'),
(38, 15, 'Chicken Shawarma Burger', 120, 2, 240, '2025-04-17'),
(39, 16, 'Shawarma Solo', 80, 1, 80, '2025-04-17'),
(40, 16, 'Shawarma Large', 100, 1, 100, '2025-04-17'),
(41, 17, 'Shawarma Solo', 80, 2, 160, '2025-04-17'),
(42, 18, 'Shawarma Solo', 80, 1, 80, '2025-04-17'),
(43, 19, 'Shawarma Large', 100, 2, 200, '2025-04-17'),
(44, 19, 'Shawarma Solo', 80, 1, 80, '2025-04-17'),
(45, 19, 'Shawarma Rice', 130, 2, 260, '2025-04-17'),
(46, 19, 'Coke', 30, 2, 60, '2025-04-17'),
(47, 20, 'Shawarma Rice', 130, 2, 260, '2025-04-17'),
(48, 20, 'Fries', 45, 2, 90, '2025-04-17'),
(49, 21, 'Shawarma Large', 100, 2, 200, '2025-04-17'),
(50, 21, 'Coke', 30, 1, 30, '2025-04-17'),
(51, 22, 'Chicken Shawarma Burger', 120, 2, 240, '2025-04-17'),
(52, 23, 'Shawarma Solo', 80, 1, 80, '2025-04-17'),
(53, 23, 'Shawarma Large', 100, 1, 100, '2025-04-17'),
(54, 24, 'Fires (BBQ)', 45, 1, 45, '2025-04-17'),
(55, 24, 'Fries', 45, 2, 90, '2025-04-17'),
(56, 24, 'Shawarma Rice', 130, 2, 260, '2025-04-17'),
(57, 24, 'Coke', 30, 1, 30, '2025-04-17'),
(58, 25, 'Shawarma Large', 100, 2, 200, '2025-04-17'),
(59, 26, 'Shawarma Solo', 80, 1, 80, '2025-04-21'),
(60, 26, 'Chicken Shawarma Burger', 120, 2, 240, '2025-04-21'),
(61, 26, 'Coke', 30, 1, 30, '2025-04-21');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `transactionId` int(11) NOT NULL,
  `requestorId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `productType` varchar(255) NOT NULL,
  `transactionType` int(11) NOT NULL COMMENT '0-In, 1-Out',
  `transactionStatus` int(11) NOT NULL COMMENT '0-Pending, 1-Approved, 2-Declined',
  `dateOfRequest` date NOT NULL,
  `notes` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`transactionId`, `requestorId`, `productId`, `productName`, `quantity`, `productType`, `transactionType`, `transactionStatus`, `dateOfRequest`, `notes`) VALUES
(1, 5, 0, 'Patty\'s', 100, '1', 0, 0, '2025-04-17', ''),
(2, 6, 0, 'Bans', 200, '1', 0, 0, '2025-04-17', ''),
(3, 6, 0, 'Bans', 200, '1', 0, 0, '2025-04-17', ''),
(4, 6, 0, 'Bans', 200, '1', 0, 0, '2025-04-17', ''),
(5, 6, 0, 'Bans', 200, '1', 0, 0, '2025-04-17', ''),
(6, 6, 0, 'Bans', 200, '1', 0, 0, '2025-04-17', ''),
(7, 6, 0, 'Bans', 200, '1', 0, 1, '2025-04-17', ''),
(8, 6, 0, 'Bans', 200, '1', 0, 0, '2025-04-17', ''),
(9, 6, 0, 'Bans', 200, '1', 0, 0, '2025-04-17', ''),
(10, 6, 0, 'Bans', 200, '1', 0, 0, '2025-04-17', ''),
(11, 6, 0, 'Beef', 300, '2', 0, 0, '2025-04-17', ''),
(12, 7, 8, 'Bans', 20, '', 1, 0, '2025-04-19', ''),
(13, 7, 0, 'meat', 50, '2', 0, 0, '2025-04-19', ''),
(14, 7, 0, 'meat', 50, '2', 0, 0, '2025-04-19', ''),
(15, 7, 0, 'meat', 50, '2', 0, 0, '2025-04-19', ''),
(16, 7, 0, 'Bans', 100, '3', 0, 0, '2025-04-19', ''),
(17, 7, 0, 'banana', 20, '1', 0, 0, '2025-04-19', ''),
(18, 7, 0, 'Banana', 20, '2', 0, 0, '2025-04-19', ''),
(19, 7, 0, 'Hotdog', 10, '1', 0, 0, '2025-04-19', ''),
(20, 7, 0, 'Hotdog', 20, '1', 0, 0, '2025-04-19', ''),
(21, 7, 0, 'cheese', 30, '3', 0, 0, '2025-04-19', ''),
(22, 7, 8, 'Bans', 10, '', 1, 0, '2025-04-19', 'eme lang'),
(23, 7, 8, 'Bans', 10, '', 1, 0, '2025-04-19', 'eme lang'),
(24, 7, 0, 'Hotdog', 10, '1', 0, 0, '2025-04-19', 'pukpok mo sa ulo mo'),
(25, 7, 0, 'Banana', 50, '1', 0, 0, '2025-04-19', 'pamukpok sa ulo'),
(26, 7, 8, 'Bans', 10, '', 1, 0, '2025-04-20', ''),
(27, 7, 8, 'Bans', 20, '', 1, 0, '2025-04-20', 'Pang bato sa ulo'),
(28, 7, 0, 'Hotdog', 100, '2', 0, 0, '2025-04-20', 'Pamalo sa Ulo'),
(29, 7, 0, 'Bans', 20, '1', 0, 0, '2025-04-21', '');

-- --------------------------------------------------------

--
-- Table structure for table `userstable`
--

CREATE TABLE `userstable` (
  `usersId` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) NOT NULL COMMENT '0-admin, 1-users'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userstable`
--

INSERT INTO `userstable` (`usersId`, `username`, `email`, `password`, `role`) VALUES
(7, 'staff', 'staff@gmail.com', '$2y$10$N9g3lchYZdEvXYjT0eEI1.UXTcwVAnCuxUEOGfoERoNR0BwOqZvmm', 1),
(8, 'admin', 'admin@gmail.com', '$2y$10$feIxMZi97grNE8WmkuxfFusz5wXqUVJcWj4m6Fw4Jh3Uzqu3yRTu6', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`productId`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menuId`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notificationId`);

--
-- Indexes for table `orderedhistory`
--
ALTER TABLE `orderedhistory`
  ADD PRIMARY KEY (`historyId`);

--
-- Indexes for table `ordereditemhistory`
--
ALTER TABLE `ordereditemhistory`
  ADD PRIMARY KEY (`historyId`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`transactionId`);

--
-- Indexes for table `userstable`
--
ALTER TABLE `userstable`
  ADD PRIMARY KEY (`usersId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `productId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menuId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notificationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orderedhistory`
--
ALTER TABLE `orderedhistory`
  MODIFY `historyId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `ordereditemhistory`
--
ALTER TABLE `ordereditemhistory`
  MODIFY `historyId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transactionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `userstable`
--
ALTER TABLE `userstable`
  MODIFY `usersId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
