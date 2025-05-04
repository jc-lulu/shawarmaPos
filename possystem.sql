-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2025 at 02:40 PM
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

INSERT INTO `inventory` (`productId`, `productName`, `quantity`, `type`, `requestedBy`, `transactionId`, `transactionStatus`, `dateOfIn`, `dateOfOut`) VALUES
(7, 'Sugar', 100, 1, 0, 0, 1, '0000-00-00', '2025-04-17'),
(11, 'Hotdog', 220, 0, 0, 0, 1, '2025-04-23', '0000-00-00'),
(14, 'Chicken', 10, 0, 0, 0, 1, '2025-04-10', '0000-00-00'),
(15, 'Beef', 90, 0, 0, 0, 1, '2025-04-27', '0000-00-00'),
(17, 'garlic', 100, 0, 0, 0, 1, '2025-04-26', '0000-00-00'),
(19, 'garlic', 50, 1, 0, 0, 1, '0000-00-00', '2025-04-27'),
(21, 'Cheese Dog', 50, 0, 0, 0, 1, '2025-04-27', '0000-00-00'),
(22, 'Egg', 90, 0, 0, 0, 1, '2025-04-29', '0000-00-00'),
(23, 'Beef', 10, 1, 0, 0, 1, '0000-00-00', '2025-04-29'),
(24, 'Beef', 100, 1, 0, 0, 1, '0000-00-00', '2025-04-29'),
(25, 'Bans', 220, 1, 0, 0, 1, '0000-00-00', '2025-04-29'),
(26, 'garlic', 10, 1, 0, 0, 1, '0000-00-00', '2025-04-29'),
(27, 'Cabage', 10, 1, 0, 0, 1, '0000-00-00', '2025-04-29'),
(28, 'cheese', 20, 1, 0, 0, 1, '0000-00-00', '2025-04-29'),
(29, 'Chicken', 100, 1, 0, 0, 1, '0000-00-00', '2025-04-30'),
(30, 'Chicken', 10, 1, 0, 0, 1, '0000-00-00', '2025-05-01'),
(31, 'cheese', 50, 1, 0, 0, 1, '0000-00-00', '2025-05-03'),
(32, 'Egg', 10, 1, 0, 0, 1, '0000-00-00', '2025-05-04'),
(33, 'Egg', 10, 0, 0, 0, 1, '2025-05-04', '0000-00-00'),
(34, 'cheese', 10, 1, 0, 0, 1, '0000-00-00', '2025-05-02'),
(35, 'Cheese Dog', 50, 1, 0, 0, 1, '0000-00-00', '2025-05-04'),
(36, 'Banananaaaaaaaaaaaaa', 2300, 0, 0, 0, 1, '2025-05-04', '0000-00-00'),
(37, 'Bans', 20, 0, 0, 0, 1, '2025-05-04', '0000-00-00');

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
  `notificationType` int(11) NOT NULL COMMENT '0-Request, 1-Alert, 2-Message\r\n',
  `transactionKey` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `notificationStatus` int(11) NOT NULL COMMENT '0-unread, 1-mark as read\r\n',
  `requestStatus` int(11) NOT NULL COMMENT '0 - pending, 1 - Finish',
  `notificationMessage` varchar(255) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `notificationFlag` int(11) NOT NULL COMMENT '0-unarchive, 1-archive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notificationId`, `notificationTarget`, `notificationType`, `transactionKey`, `productId`, `notificationStatus`, `requestStatus`, `notificationMessage`, `notes`, `notificationFlag`) VALUES
(1, 0, 0, 21, 0, 1, 1, 'You have a request for In item that need your approval', 'No notes provided', 0),
(2, 0, 0, 23, 0, 1, 0, 'You have a request for Out item that need your approval', 'eme lang', 0),
(3, 0, 0, 24, 0, 1, 1, 'You have a request for In item that need your approval', 'pukpok mo sa ulo mo', 0),
(4, 0, 0, 25, 0, 1, 1, 'You have a request for In item that need your approval', 'pamukpok sa ulo', 0),
(5, 0, 0, 26, 0, 1, 0, 'You have a request for Out item that need your approval', 'No notes provided', 0),
(6, 0, 0, 27, 0, 1, 1, 'You have a request for Out item that need your approval', 'Pang bato sa ulo', 0),
(7, 0, 0, 28, 0, 1, 1, 'You have a request for In item that need your approval', 'Pamalo sa Ulo', 0),
(8, 0, 0, 29, 0, 1, 1, 'You have a request for In item that need your approval', 'No notes provided', 0),
(9, 7, 2, 0, 0, 1, 0, 'Your request for Bans has been approved', '', 1),
(10, 7, 2, 0, 0, 1, 0, 'Your request for Hotdog has been approved', '', 1),
(11, 7, 2, 0, 0, 1, 0, 'Your request for Bans has been approved', '', 1),
(12, 7, 2, 0, 0, 1, 0, 'Your request for Banana has been approved', '', 1),
(13, 7, 2, 0, 0, 1, 0, 'Your request for Hotdog has been approved', '', 1),
(14, 7, 2, 0, 0, 1, 0, 'Your request for cheese has been approved', '', 1),
(15, 0, 0, 30, 0, 1, 1, 'You have a request for In item that need your approval', 'No notes provided', 0),
(16, 7, 2, 0, 0, 1, 0, 'Your request for Hotdog has been approved', '', 1),
(17, 0, 0, 31, 0, 1, 1, 'You have a request for In item that need your approval', 'No notes provided', 0),
(18, 7, 2, 0, 0, 1, 0, 'Your request for Chicken has been approved', '', 0),
(19, 0, 0, 32, 0, 1, 1, 'You have a request for In item that need your approval', 'No notes provided', 0),
(20, 7, 2, 0, 0, 1, 0, 'Your request for Chicken has been approved', '', 0),
(21, 0, 0, 33, 0, 1, 1, 'You have a request for In item that need your approval', 'No notes provided', 0),
(22, 7, 2, 0, 0, 1, 0, 'Your request for Beef has been approved', '', 0),
(23, 0, 0, 34, 0, 1, 1, 'You have a request for In item that need your approval', 'No notes provided', 0),
(24, 7, 2, 0, 0, 1, 0, 'Your request for garlic has been approved', '', 0),
(25, 0, 0, 35, 0, 1, 1, 'You have a request for Out item that need your approval', 'pang ulam ko muna', 0),
(26, 7, 2, 0, 0, 1, 0, 'Your request for Beef has been approved', '', 0),
(27, 0, 0, 36, 0, 1, 1, 'You have a request for Out item that need your approval', 'No notes provided', 0),
(28, 0, 0, 37, 0, 1, 1, 'You have a request for Out item that need your approval', 'No notes provided', 0),
(29, 0, 0, 38, 0, 1, 1, 'You have a request for Out item that need your approval', 'No notes provided', 0),
(30, 0, 0, 39, 0, 1, 1, 'You have a request for Out item that need your approval', 'No notes provided', 0),
(31, 7, 2, 0, 0, 1, 0, 'Your request for Bans has been approved', '', 0),
(32, 7, 2, 0, 0, 1, 0, 'Your request for  has been declined', '', 0),
(33, 7, 2, 0, 0, 1, 0, 'Your request for  has been declined', '', 0),
(34, 7, 2, 0, 0, 1, 0, 'Your request for  has been declined', '', 0),
(35, 7, 2, 0, 0, 1, 0, 'Your request for  has been declined', '', 0),
(36, 7, 2, 0, 0, 0, 0, 'Your request for  has been declined', '', 0),
(37, 0, 0, 40, 0, 1, 1, 'You have a request for Out item that need your approval', 'No notes provided', 0),
(38, 7, 2, 0, 0, 0, 0, 'Your request for  has been declined', '', 0),
(39, 0, 0, 41, 0, 1, 1, 'You have a request for In item that need your approval', 'No notes provided', 0),
(40, 7, 2, 0, 0, 0, 0, 'Your request for Cabage has been approved', '', 0),
(41, 0, 0, 42, 0, 1, 1, 'You have a request for In item that need your approval', 'No notes provided', 0),
(42, 7, 2, 0, 0, 1, 0, 'Your request for Hotdog has been approved', '', 0),
(55, 0, 1, 0, 8, 1, 0, 'Low stock alert! Bans is running low on stock (only 20 remaining). Please restock it.', '', 0),
(56, 0, 1, 0, 13, 1, 0, 'Low stock alert! cheese is running low on stock (only 10 remaining). Please restock it.', '', 0),
(57, 0, 1, 0, 13, 1, 0, 'Low stock alert! cheese is running low on stock (only 10 remaining). Please restock it.', '', 0),
(58, 0, 1, 0, 13, 1, 0, 'Low stock alert! cheese is running low on stock (only 10 remaining). Please restock it.', '', 0),
(59, 0, 0, 43, 0, 1, 1, 'You have a request for In item that need your approval', 'restock', 0),
(62, 0, 0, 44, 0, 1, 1, 'You have a request for In item that need your approval', 'No notes provided', 0),
(63, 0, 1, 0, 14, 1, 0, 'Low stock alert! Chicken is running low on stock (only 20 remaining). Please restock it.', '', 0),
(64, 0, 1, 0, 14, 1, 0, 'Low stock alert! Chicken is running low on stock (only 20 remaining). Please restock it.', '', 0),
(67, 0, 1, 0, 14, 1, 0, 'Low stock alert! Chicken is running low on stock (only 20 remaining). Please restock it.', '', 0),
(68, 0, 1, 0, 14, 1, 0, 'Low stock alert! Chicken is running low on stock (only 20 remaining). Please restock it.', '', 0),
(71, 0, 1, 0, 13, 1, 0, 'Low stock alert! cheese is running low on stock (only 10 remaining). Please restock it.', '', 0),
(72, 0, 1, 0, 14, 1, 0, 'Low stock alert! Chicken is running low on stock (only 10 remaining). Please restock it.', '', 1),
(73, 0, 1, 0, 14, 1, 0, 'Low stock alert! Chicken is running low on stock (only 10 remaining). Please restock it.', '', 1),
(74, 0, 1, 0, 13, 0, 0, 'Low stock alert! cheese is running low on stock (only 10 remaining). Please restock it.', '', 0),
(75, 0, 1, 0, 14, 0, 0, 'Low stock alert! Chicken is running low on stock (only 10 remaining). Please restock it.', '', 0),
(76, 0, 1, 0, 33, 0, 0, 'Low stock alert! Egg is running low on stock (only 10 remaining). Please restock it.', '', 0),
(77, 0, 1, 0, 37, 1, 0, 'Low stock alert! Bans is running low on stock (only 20 remaining). Please restock it.', '', 0),
(78, 0, 0, 45, 0, 1, 1, 'You have a request for Out item that need your approval', 'No notes provided', 0),
(79, 0, 0, 46, 0, 1, 1, 'You have a request for Out item that need your approval', 'No notes provided', 0),
(80, 7, 2, 0, 0, 0, 0, 'Your request for Beef has been approved', '', 0),
(81, 7, 2, 0, 0, 0, 0, 'Your request for Banananaaaaaaaaaaaaa has been approved', '', 0),
(82, 7, 2, 0, 0, 0, 0, 'Your request for Bans has been approved', '', 0),
(83, 0, 1, 0, 37, 0, 0, 'Low stock alert! Bans is running low on stock (only 20 remaining). Please restock it.', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orderedhistory`
--

CREATE TABLE `orderedhistory` (
  `historyId` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  `totalCost` float NOT NULL,
  `dateOfOrder` date NOT NULL,
  `timeOfOrder` time NOT NULL,
  `weekNumber` int(11) NOT NULL,
  `weekYear` int(11) NOT NULL,
  `weekLabel` varchar(20) NOT NULL,
  `historyStatus` int(11) NOT NULL COMMENT '0-unarchive, 1-archive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderedhistory`
--

INSERT INTO `orderedhistory` (`historyId`, `orderId`, `totalCost`, `dateOfOrder`, `timeOfOrder`, `weekNumber`, `weekYear`, `weekLabel`, `historyStatus`) VALUES
(1, 1, 210, '2025-02-13', '00:00:00', 10, 2025, 'Week 10, 2025', 0),
(2, 2, 180, '2025-02-13', '07:05:19', 7, 2025, 'Week 7, 2025', 0),
(3, 3, 310, '2025-02-13', '07:07:01', 7, 2025, 'Week 7, 2025', 0),
(4, 4, 255, '2025-04-13', '07:27:50', 15, 2025, 'Week 15, 2025', 0),
(5, 5, 450, '2025-04-13', '07:34:31', 15, 2025, 'Week 15, 2025', 0),
(6, 6, 510, '2025-04-13', '07:44:55', 15, 2025, 'Week 15, 2025', 0),
(7, 7, 240, '2025-04-16', '03:55:01', 16, 2025, 'Week 16, 2025', 0),
(8, 8, 260, '2025-04-17', '09:13:24', 16, 2025, 'Week 16, 2025', 0),
(9, 9, 120, '2025-04-17', '09:43:13', 16, 2025, 'Week 16, 2025', 0),
(10, 10, 240, '2025-04-17', '09:46:28', 16, 2025, 'Week 16, 2025', 0),
(11, 11, 240, '2025-04-17', '09:47:04', 16, 2025, 'Week 16, 2025', 0),
(12, 12, 200, '2025-04-17', '09:51:35', 16, 2025, 'Week 16, 2025', 0),
(13, 13, 180, '2025-04-17', '09:55:20', 16, 2025, 'Week 16, 2025', 0),
(14, 14, 200, '2025-04-17', '09:55:39', 16, 2025, 'Week 16, 2025', 0),
(15, 15, 240, '2025-04-17', '10:01:23', 16, 2025, 'Week 16, 2025', 0),
(16, 16, 180, '2025-04-17', '10:02:23', 16, 2025, 'Week 16, 2025', 0),
(17, 17, 160, '2025-04-17', '10:06:32', 16, 2025, 'Week 16, 2025', 0),
(18, 18, 80, '2025-04-17', '10:07:28', 16, 2025, 'Week 16, 2025', 0),
(19, 19, 600, '2025-04-17', '10:17:55', 16, 2025, 'Week 16, 2025', 0),
(20, 20, 350, '2025-04-17', '10:36:17', 16, 2025, 'Week 16, 2025', 0),
(21, 21, 230, '2025-04-17', '11:42:44', 16, 2025, 'Week 16, 2025', 0),
(22, 22, 240, '2025-04-17', '11:46:54', 16, 2025, 'Week 16, 2025', 0),
(23, 23, 180, '2025-02-17', '11:50:13', 8, 2025, 'Week 8, 2025', 0),
(24, 24, 425, '2025-03-17', '12:16:25', 12, 2025, 'Week 12, 2025', 0),
(25, 25, 200, '2025-03-17', '14:22:41', 12, 2025, 'Week 12, 2025', 0),
(26, 26, 350, '2025-04-21', '15:57:54', 17, 2025, 'Week 17, 2025', 0),
(27, 27, 180, '2025-04-25', '01:11:59', 17, 2025, 'Week 17, 2025', 0),
(28, 28, 280, '2025-04-27', '06:38:50', 17, 2025, 'Week 17, 2025', 0),
(29, 29, 200, '2025-04-27', '07:34:21', 17, 2025, 'Week 17, 2025', 0),
(30, 30, 420, '2025-04-27', '08:01:12', 17, 2025, 'Week 17, 2025', 0),
(31, 31, 280, '2025-04-27', '08:11:07', 17, 2025, 'Week 17, 2025', 0),
(32, 32, 535, '2025-04-27', '08:15:51', 17, 2025, 'Week 17, 2025', 0),
(33, 33, 380, '2025-04-27', '08:15:55', 17, 2025, 'Week 17, 2025', 0),
(34, 34, 285, '2025-04-27', '13:43:48', 17, 2025, 'Week 17, 2025', 0),
(35, 35, 575, '2025-04-30', '04:19:06', 18, 2025, 'Week 18, 2025', 0),
(36, 36, 200, '2025-05-01', '02:44:56', 18, 2025, 'Week 18, 2025', 0),
(37, 37, 100, '2025-05-01', '04:04:11', 18, 2025, 'Week 18, 2025', 0),
(38, 38, 280, '2025-05-01', '07:09:05', 18, 2025, 'Week 18, 2025', 0),
(39, 39, 200, '2025-05-01', '10:10:39', 18, 2025, 'Week 18, 2025', 0),
(40, 40, 160, '2025-05-01', '10:12:00', 18, 2025, 'Week 18, 2025', 0),
(41, 41, 240, '2025-05-01', '10:15:23', 18, 2025, 'Week 18, 2025', 0),
(42, 42, 240, '2025-05-01', '10:18:07', 18, 2025, 'Week 18, 2025', 0),
(43, 43, 320, '2025-05-04', '02:27:18', 18, 2025, 'Week 18, 2025', 0),
(44, 44, 180, '2025-05-04', '02:37:49', 18, 2025, 'Week 18, 2025', 0),
(45, 45, 280, '2025-05-04', '02:41:41', 18, 2025, 'Week 18, 2025', 0),
(46, 46, 280, '2025-05-04', '02:48:46', 18, 2025, 'Week 18, 2025', 0),
(47, 47, 200, '2025-05-04', '02:49:42', 18, 2025, 'Week 18, 2025', 0),
(48, 48, 200, '2025-05-04', '02:52:04', 18, 2025, 'Week 18, 2025', 0),
(49, 49, 450, '2025-05-04', '02:52:33', 18, 2025, 'Week 18, 2025', 0),
(50, 50, 240, '2025-05-04', '05:18:47', 18, 2025, 'Week 18, 2025', 0),
(51, 51, 370, '2025-05-04', '05:19:45', 18, 2025, 'Week 18, 2025', 0),
(52, 52, 240, '2025-05-04', '05:21:10', 18, 2025, 'Week 18, 2025', 1);

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
(61, 26, 'Coke', 30, 1, 30, '2025-04-21'),
(62, 27, 'Shawarma Solo', 80, 1, 80, '2025-04-25'),
(63, 27, 'Shawarma Large', 100, 1, 100, '2025-04-25'),
(64, 28, 'Chicken Shawarma Burger', 120, 1, 120, '2025-04-27'),
(65, 28, 'Shawarma Solo', 80, 2, 160, '2025-04-27'),
(66, 29, 'Chicken Shawarma Burger', 120, 1, 120, '2025-04-27'),
(67, 29, 'Shawarma Solo', 80, 1, 80, '2025-04-27'),
(68, 30, 'Chicken Shawarma Burger', 120, 3, 360, '2025-04-27'),
(69, 30, 'Coke', 30, 2, 60, '2025-04-27'),
(70, 31, 'Shawarma Large', 100, 2, 200, '2025-04-27'),
(71, 31, 'Shawarma Solo', 80, 1, 80, '2025-04-27'),
(72, 32, 'Shawarma Large', 100, 2, 200, '2025-04-27'),
(73, 32, 'Shawarma Solo', 80, 2, 160, '2025-04-27'),
(74, 32, 'Shawarma Rice', 130, 1, 130, '2025-04-27'),
(75, 32, 'Fries', 45, 1, 45, '2025-04-27'),
(76, 33, 'Shawarma Rice', 130, 2, 260, '2025-04-27'),
(77, 33, 'Chicken Shawarma Burger', 120, 1, 120, '2025-04-27'),
(78, 34, 'Chicken Shawarma Burger', 120, 2, 240, '2025-04-27'),
(79, 34, 'Fries', 45, 1, 45, '2025-04-27'),
(80, 35, 'Shawarma Large', 100, 3, 300, '2025-04-30'),
(81, 35, 'Chicken Shawarma Burger', 120, 1, 120, '2025-04-30'),
(82, 35, 'Shawarma Solo', 80, 1, 80, '2025-04-30'),
(83, 35, 'Fries', 45, 1, 45, '2025-04-30'),
(84, 35, 'Coke', 30, 1, 30, '2025-04-30'),
(85, 36, 'Shawarma Large', 100, 2, 200, '2025-05-01'),
(86, 37, 'Shawarma Large', 100, 1, 100, '2025-05-01'),
(87, 38, 'Shawarma Solo', 80, 2, 160, '2025-05-01'),
(88, 38, 'Chicken Shawarma Burger', 120, 1, 120, '2025-05-01'),
(89, 39, 'Shawarma Solo', 80, 1, 80, '2025-05-01'),
(90, 39, 'Chicken Shawarma Burger', 120, 1, 120, '2025-05-01'),
(91, 40, 'Shawarma Solo', 80, 2, 160, '2025-05-01'),
(92, 41, 'Chicken Shawarma Burger', 120, 2, 240, '2025-05-01'),
(93, 42, 'Chicken Shawarma Burger', 120, 2, 240, '2025-05-01'),
(94, 43, 'Chicken Shawarma Burger', 120, 2, 240, '2025-05-04'),
(95, 43, 'Shawarma Solo', 80, 1, 80, '2025-05-04'),
(96, 44, 'Shawarma Solo', 80, 1, 80, '2025-05-04'),
(97, 44, 'Shawarma Large', 100, 1, 100, '2025-05-04'),
(98, 45, 'Chicken Shawarma Burger', 120, 1, 120, '2025-05-04'),
(99, 45, 'Shawarma Solo', 80, 2, 160, '2025-05-04'),
(100, 46, 'Chicken Shawarma Burger', 120, 1, 120, '2025-05-04'),
(101, 46, 'Shawarma Solo', 80, 2, 160, '2025-05-04'),
(102, 47, 'Shawarma Large', 100, 2, 200, '2025-05-04'),
(103, 48, 'Shawarma Large', 100, 2, 200, '2025-05-04'),
(104, 49, 'Shawarma Large', 100, 2, 200, '2025-05-04'),
(105, 49, 'Chicken Shawarma Burger', 120, 1, 120, '2025-05-04'),
(106, 49, 'Shawarma Rice', 130, 1, 130, '2025-05-04'),
(107, 50, 'Chicken Shawarma Burger', 120, 2, 240, '2025-05-04'),
(108, 51, 'Chicken Shawarma Burger', 120, 2, 240, '2025-05-04'),
(109, 51, 'Shawarma Rice', 130, 1, 130, '2025-05-04'),
(110, 52, 'Chicken Shawarma Burger', 120, 2, 240, '2025-05-04');

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
  `notes` varchar(255) NOT NULL,
  `is_archived` int(11) NOT NULL COMMENT '0-unarchived, 1-archived\r\n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`transactionId`, `requestorId`, `productId`, `productName`, `quantity`, `productType`, `transactionType`, `transactionStatus`, `dateOfRequest`, `notes`, `is_archived`) VALUES
(1, 5, 0, 'Patty\'s', 100, '1', 0, 0, '2025-04-17', '', 0),
(2, 6, 0, 'Bans', 200, '1', 0, 0, '2025-04-17', '', 0),
(3, 6, 0, 'Bans', 200, '1', 0, 0, '2025-04-17', '', 0),
(4, 6, 0, 'Bans', 200, '1', 0, 0, '2025-04-17', '', 0),
(5, 6, 0, 'Bans', 200, '1', 0, 0, '2025-04-17', '', 0),
(6, 6, 0, 'Bans', 200, '1', 0, 0, '2025-04-17', '', 0),
(7, 6, 0, 'Bans', 200, '1', 0, 2, '2025-04-17', '', 0),
(8, 6, 0, 'Bans', 200, '1', 0, 0, '2025-04-17', '', 0),
(9, 6, 0, 'Bans', 200, '1', 0, 0, '2025-04-17', '', 0),
(10, 6, 0, 'Bans', 200, '1', 0, 0, '2025-04-17', '', 0),
(11, 6, 0, 'Beef', 300, '2', 0, 0, '2025-04-17', '', 0),
(12, 7, 8, 'Bans', 20, '', 1, 0, '2025-04-19', '', 0),
(13, 7, 0, 'meat', 50, '2', 0, 0, '2025-04-19', '', 0),
(14, 7, 0, 'meat', 50, '2', 0, 0, '2025-04-19', '', 0),
(15, 7, 0, 'meat', 50, '2', 0, 0, '2025-04-19', '', 0),
(16, 7, 0, 'Bans', 100, '3', 0, 0, '2025-04-19', '', 0),
(17, 7, 0, 'banana', 20, '1', 0, 0, '2025-04-19', '', 0),
(18, 7, 0, 'Banana', 20, '2', 0, 0, '2025-04-19', '', 0),
(19, 7, 0, 'Hotdog', 10, '1', 0, 0, '2025-04-19', '', 0),
(20, 7, 0, 'Hotdog', 20, '1', 0, 0, '2025-04-19', '', 0),
(21, 7, 0, 'cheese', 30, '3', 0, 1, '2025-04-19', '', 0),
(22, 7, 8, 'Bans', 10, '', 1, 0, '2025-04-19', 'eme lang', 0),
(23, 7, 8, 'Bans', 10, '', 1, 2, '2025-04-19', 'eme lang', 0),
(24, 7, 0, 'Hotdog', 10, '1', 0, 1, '2025-04-19', 'pukpok mo sa ulo mo', 0),
(25, 7, 0, 'Banana', 50, '1', 0, 1, '2025-04-19', 'pamukpok sa ulo', 0),
(26, 7, 8, 'Bans', 10, '', 1, 2, '2025-04-20', '', 0),
(27, 7, 8, 'Bans', 20, '', 1, 2, '2025-04-20', 'Pang bato sa ulo', 0),
(28, 7, 0, 'Hotdog', 100, '2', 0, 1, '2025-04-20', 'Pamalo sa Ulo', 0),
(29, 7, 0, 'Bans', 20, '1', 0, 2, '2025-04-21', '', 0),
(30, 7, 0, 'Hotdog', 100, '1', 0, 1, '2025-04-26', '', 0),
(31, 7, 0, 'Chicken', 100, '1', 0, 1, '2025-04-26', '', 0),
(32, 7, 0, 'Chicken', 20, '1', 0, 1, '2025-04-26', '', 0),
(33, 7, 0, 'Beef', 500, '1', 0, 1, '2025-04-26', '', 0),
(34, 7, 0, 'garlic', 50, '1', 0, 1, '2025-04-26', '', 0),
(35, 7, 15, 'Beef', 100, '', 1, 1, '2025-04-26', 'pang ulam ko muna', 0),
(36, 7, 8, 'Bans', 20, '', 1, 2, '2025-04-26', '', 1),
(39, 7, 8, 'Bans', 5, '', 1, 1, '2025-04-27', '', 0),
(40, 7, 15, 'Beef', 100, '', 1, 2, '2025-04-27', '', 1),
(41, 7, 0, 'Cabage', 10, '1', 0, 1, '2025-04-27', '', 0),
(42, 7, 0, 'Hotdog', 10, '', 0, 1, '2025-04-27', '', 0),
(43, 7, 0, 'cheese', 50, '', 0, 1, '2025-04-29', 'restock', 0),
(44, 7, 0, 'Bans', 200, '', 0, 1, '2025-04-30', '', 0),
(45, 7, 36, 'Banananaaaaaaaaaaaaa', 200, '', 1, 1, '2025-05-04', '', 0),
(46, 7, 15, 'Beef', 200, '', 1, 1, '2025-05-04', '', 1);

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
(8, 'admin', 'admin@gmail.com', '$2y$10$feIxMZi97grNE8WmkuxfFusz5wXqUVJcWj4m6Fw4Jh3Uzqu3yRTu6', 0),
(9, 'staff2', 'staff2@gmail.com', '$2y$10$1h7Ey7CMYVs1w0tQgekJ/.gUciyiGmCt.WBNt.CFesyCOOXay4xOO', 1);

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
  MODIFY `productId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menuId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notificationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `orderedhistory`
--
ALTER TABLE `orderedhistory`
  MODIFY `historyId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `ordereditemhistory`
--
ALTER TABLE `ordereditemhistory`
  MODIFY `historyId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transactionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `userstable`
--
ALTER TABLE `userstable`
  MODIFY `usersId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
