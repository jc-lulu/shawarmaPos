-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2025 at 04:31 AM
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
(5, 'Bans', 5, 1, 0, 0, 1, '0000-00-00', '2025-04-17'),
(6, 'Sugar', 100, 1, 0, 0, 1, '0000-00-00', '2025-04-17'),
(7, 'Sugar', 100, 1, 0, 0, 1, '0000-00-00', '2025-04-17'),
(11, 'Hotdog', 220, 0, 0, 0, 1, '2025-04-23', '0000-00-00'),
(13, 'cheese', 60, 0, 0, 0, 1, '2025-04-25', '0000-00-00'),
(14, 'Chicken', 120, 0, 0, 0, 1, '2025-04-10', '0000-00-00'),
(15, 'Beef', 290, 0, 0, 0, 1, '2025-04-27', '0000-00-00'),
(17, 'garlic', 100, 0, 0, 0, 1, '2025-04-26', '0000-00-00'),
(19, 'garlic', 50, 1, 0, 0, 1, '0000-00-00', '2025-04-27'),
(21, 'Cheese Dog', 100, 0, 0, 0, 1, '2025-04-27', '0000-00-00'),
(22, 'Egg', 100, 0, 0, 0, 1, '2025-04-29', '0000-00-00'),
(23, 'Beef', 10, 1, 0, 0, 1, '0000-00-00', '2025-04-29'),
(24, 'Beef', 100, 1, 0, 0, 1, '0000-00-00', '2025-04-29'),
(25, 'Bans', 20, 1, 0, 0, 1, '0000-00-00', '2025-04-29'),
(26, 'garlic', 10, 1, 0, 0, 1, '0000-00-00', '2025-04-29'),
(27, 'Cabage', 10, 1, 0, 0, 1, '0000-00-00', '2025-04-29'),
(28, 'cheese', 20, 1, 0, 0, 1, '0000-00-00', '2025-04-29');

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
  `notificationStatus` int(11) NOT NULL COMMENT '0-unread, 1-mark as read, 2 - archive\r\n',
  `requestStatus` int(11) NOT NULL COMMENT '0 - pending, 1 - Finish',
  `notificationMessage` varchar(255) NOT NULL,
  `notes` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notificationId`, `notificationTarget`, `notificationType`, `transactionKey`, `productId`, `notificationStatus`, `requestStatus`, `notificationMessage`, `notes`) VALUES
(1, 0, 0, 21, 0, 1, 1, 'You have a request for In item that need your approval', 'No notes provided'),
(2, 0, 0, 23, 0, 1, 0, 'You have a request for Out item that need your approval', 'eme lang'),
(3, 0, 0, 24, 0, 1, 1, 'You have a request for In item that need your approval', 'pukpok mo sa ulo mo'),
(4, 0, 0, 25, 0, 1, 1, 'You have a request for In item that need your approval', 'pamukpok sa ulo'),
(5, 0, 0, 26, 0, 1, 0, 'You have a request for Out item that need your approval', 'No notes provided'),
(6, 0, 0, 27, 0, 1, 1, 'You have a request for Out item that need your approval', 'Pang bato sa ulo'),
(7, 0, 0, 28, 0, 1, 1, 'You have a request for In item that need your approval', 'Pamalo sa Ulo'),
(8, 0, 0, 29, 0, 1, 1, 'You have a request for In item that need your approval', 'No notes provided'),
(9, 7, 2, 0, 0, 1, 0, 'Your request for Bans has been approved', ''),
(10, 7, 2, 0, 0, 1, 0, 'Your request for Hotdog has been approved', ''),
(11, 7, 2, 0, 0, 1, 0, 'Your request for Bans has been approved', ''),
(12, 7, 2, 0, 0, 1, 0, 'Your request for Banana has been approved', ''),
(13, 7, 2, 0, 0, 1, 0, 'Your request for Hotdog has been approved', ''),
(14, 7, 2, 0, 0, 1, 0, 'Your request for cheese has been approved', ''),
(15, 0, 0, 30, 0, 1, 1, 'You have a request for In item that need your approval', 'No notes provided'),
(16, 7, 2, 0, 0, 1, 0, 'Your request for Hotdog has been approved', ''),
(17, 0, 0, 31, 0, 1, 1, 'You have a request for In item that need your approval', 'No notes provided'),
(18, 7, 2, 0, 0, 1, 0, 'Your request for Chicken has been approved', ''),
(19, 0, 0, 32, 0, 1, 1, 'You have a request for In item that need your approval', 'No notes provided'),
(20, 7, 2, 0, 0, 1, 0, 'Your request for Chicken has been approved', ''),
(21, 0, 0, 33, 0, 1, 1, 'You have a request for In item that need your approval', 'No notes provided'),
(22, 7, 2, 0, 0, 1, 0, 'Your request for Beef has been approved', ''),
(23, 0, 0, 34, 0, 1, 1, 'You have a request for In item that need your approval', 'No notes provided'),
(24, 7, 2, 0, 0, 1, 0, 'Your request for garlic has been approved', ''),
(25, 0, 0, 35, 0, 1, 1, 'You have a request for Out item that need your approval', 'pang ulam ko muna'),
(26, 7, 2, 0, 0, 1, 0, 'Your request for Beef has been approved', ''),
(27, 0, 0, 36, 0, 1, 1, 'You have a request for Out item that need your approval', 'No notes provided'),
(28, 0, 0, 37, 0, 1, 1, 'You have a request for Out item that need your approval', 'No notes provided'),
(29, 0, 0, 38, 0, 1, 1, 'You have a request for Out item that need your approval', 'No notes provided'),
(30, 0, 0, 39, 0, 1, 1, 'You have a request for Out item that need your approval', 'No notes provided'),
(31, 7, 2, 0, 0, 1, 0, 'Your request for Bans has been approved', ''),
(32, 7, 2, 0, 0, 1, 0, 'Your request for  has been declined', ''),
(33, 7, 2, 0, 0, 1, 0, 'Your request for  has been declined', ''),
(34, 7, 2, 0, 0, 1, 0, 'Your request for  has been declined', ''),
(35, 7, 2, 0, 0, 1, 0, 'Your request for  has been declined', ''),
(36, 7, 2, 0, 0, 1, 0, 'Your request for  has been declined', ''),
(37, 0, 0, 40, 0, 1, 1, 'You have a request for Out item that need your approval', 'No notes provided'),
(38, 7, 2, 0, 0, 1, 0, 'Your request for  has been declined', ''),
(39, 0, 0, 41, 0, 1, 1, 'You have a request for In item that need your approval', 'No notes provided'),
(40, 7, 2, 0, 0, 1, 0, 'Your request for Cabage has been approved', ''),
(41, 0, 0, 42, 0, 1, 1, 'You have a request for In item that need your approval', 'No notes provided'),
(42, 7, 2, 0, 0, 1, 0, 'Your request for Hotdog has been approved', ''),
(43, 0, 1, 0, 8, 1, 0, 'Low stock alert! Product ID: 8 is running low on stock. Please restock it.', ''),
(44, 0, 1, 0, 18, 1, 0, 'Low stock alert! garlic is running low on stock (only 10 remaining). Please restock it.', ''),
(45, 0, 1, 0, 20, 1, 0, 'Low stock alert! Cabage is running low on stock (only 10 remaining). Please restock it.', ''),
(46, 0, 1, 0, 8, 1, 0, 'Low stock alert! Bans is running low on stock (only 20 remaining). Please restock it.', ''),
(47, 0, 1, 0, 18, 1, 0, 'Low stock alert! garlic is running low on stock (only 10 remaining). Please restock it.', ''),
(48, 0, 1, 0, 20, 1, 0, 'Low stock alert! Cabage is running low on stock (only 10 remaining). Please restock it.', ''),
(49, 0, 1, 0, 8, 1, 0, 'Low stock alert! Bans is running low on stock (only 20 remaining). Please restock it.', ''),
(50, 0, 1, 0, 20, 1, 0, 'Low stock alert! Cabage is running low on stock (only 10 remaining). Please restock it.', ''),
(51, 0, 1, 0, 8, 1, 0, 'Low stock alert! Bans is running low on stock (only 20 remaining). Please restock it.', ''),
(52, 0, 1, 0, 18, 1, 0, 'Low stock alert! garlic is running low on stock (only 10 remaining). Please restock it.', ''),
(53, 0, 1, 0, 8, 1, 0, 'Low stock alert! Bans is running low on stock (only 20 remaining). Please restock it.', ''),
(54, 0, 1, 0, 8, 1, 0, 'Low stock alert! Bans is running low on stock (only 20 remaining). Please restock it.', ''),
(55, 0, 1, 0, 8, 1, 0, 'Low stock alert! Bans is running low on stock (only 20 remaining). Please restock it.', ''),
(56, 0, 1, 0, 13, 1, 0, 'Low stock alert! cheese is running low on stock (only 10 remaining). Please restock it.', ''),
(57, 0, 1, 0, 13, 1, 0, 'Low stock alert! cheese is running low on stock (only 10 remaining). Please restock it.', ''),
(58, 0, 1, 0, 13, 1, 0, 'Low stock alert! cheese is running low on stock (only 10 remaining). Please restock it.', ''),
(59, 0, 0, 43, 0, 1, 1, 'You have a request for In item that need your approval', 'restock'),
(60, 0, 1, 0, 13, 0, 0, 'Low stock alert! cheese is running low on stock (only 10 remaining). Please restock it.', ''),
(61, 7, 2, 0, 0, 1, 0, 'Your request for cheese has been approved', '');

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
  `weekLabel` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderedhistory`
--

INSERT INTO `orderedhistory` (`historyId`, `orderId`, `totalCost`, `dateOfOrder`, `timeOfOrder`, `weekNumber`, `weekYear`, `weekLabel`) VALUES
(1, 1, 210, '2025-02-13', '00:00:00', 10, 2025, 'Week 10, 2025'),
(2, 2, 180, '2025-02-13', '07:05:19', 7, 2025, 'Week 7, 2025'),
(3, 3, 310, '2025-02-13', '07:07:01', 7, 2025, 'Week 7, 2025'),
(4, 4, 255, '2025-04-13', '07:27:50', 15, 2025, 'Week 15, 2025'),
(5, 5, 450, '2025-04-13', '07:34:31', 15, 2025, 'Week 15, 2025'),
(6, 6, 510, '2025-04-13', '07:44:55', 15, 2025, 'Week 15, 2025'),
(7, 7, 240, '2025-04-16', '03:55:01', 16, 2025, 'Week 16, 2025'),
(8, 8, 260, '2025-04-17', '09:13:24', 16, 2025, 'Week 16, 2025'),
(9, 9, 120, '2025-04-17', '09:43:13', 16, 2025, 'Week 16, 2025'),
(10, 10, 240, '2025-04-17', '09:46:28', 16, 2025, 'Week 16, 2025'),
(11, 11, 240, '2025-04-17', '09:47:04', 16, 2025, 'Week 16, 2025'),
(12, 12, 200, '2025-04-17', '09:51:35', 16, 2025, 'Week 16, 2025'),
(13, 13, 180, '2025-04-17', '09:55:20', 16, 2025, 'Week 16, 2025'),
(14, 14, 200, '2025-04-17', '09:55:39', 16, 2025, 'Week 16, 2025'),
(15, 15, 240, '2025-04-17', '10:01:23', 16, 2025, 'Week 16, 2025'),
(16, 16, 180, '2025-04-17', '10:02:23', 16, 2025, 'Week 16, 2025'),
(17, 17, 160, '2025-04-17', '10:06:32', 16, 2025, 'Week 16, 2025'),
(18, 18, 80, '2025-04-17', '10:07:28', 16, 2025, 'Week 16, 2025'),
(19, 19, 600, '2025-04-17', '10:17:55', 16, 2025, 'Week 16, 2025'),
(20, 20, 350, '2025-04-17', '10:36:17', 16, 2025, 'Week 16, 2025'),
(21, 21, 230, '2025-04-17', '11:42:44', 16, 2025, 'Week 16, 2025'),
(22, 22, 240, '2025-04-17', '11:46:54', 16, 2025, 'Week 16, 2025'),
(23, 23, 180, '2025-02-17', '11:50:13', 8, 2025, 'Week 8, 2025'),
(24, 24, 425, '2025-03-17', '12:16:25', 12, 2025, 'Week 12, 2025'),
(25, 25, 200, '2025-03-17', '14:22:41', 12, 2025, 'Week 12, 2025'),
(26, 26, 350, '2025-04-21', '15:57:54', 17, 2025, 'Week 17, 2025'),
(27, 27, 180, '2025-04-25', '01:11:59', 17, 2025, 'Week 17, 2025'),
(28, 28, 280, '2025-04-27', '06:38:50', 17, 2025, 'Week 17, 2025'),
(29, 29, 200, '2025-04-27', '07:34:21', 17, 2025, 'Week 17, 2025'),
(30, 30, 420, '2025-04-27', '08:01:12', 17, 2025, 'Week 17, 2025'),
(31, 31, 280, '2025-04-27', '08:11:07', 17, 2025, 'Week 17, 2025'),
(32, 32, 535, '2025-04-27', '08:15:51', 17, 2025, 'Week 17, 2025'),
(33, 33, 380, '2025-04-27', '08:15:55', 17, 2025, 'Week 17, 2025'),
(34, 34, 285, '2025-04-27', '13:43:48', 17, 2025, 'Week 17, 2025'),
(35, 35, 575, '2025-04-30', '04:19:06', 18, 2025, 'Week 18, 2025');

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
(84, 35, 'Coke', 30, 1, 30, '2025-04-30');

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
(7, 6, 0, 'Bans', 200, '1', 0, 2, '2025-04-17', ''),
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
(21, 7, 0, 'cheese', 30, '3', 0, 1, '2025-04-19', ''),
(22, 7, 8, 'Bans', 10, '', 1, 0, '2025-04-19', 'eme lang'),
(23, 7, 8, 'Bans', 10, '', 1, 2, '2025-04-19', 'eme lang'),
(24, 7, 0, 'Hotdog', 10, '1', 0, 1, '2025-04-19', 'pukpok mo sa ulo mo'),
(25, 7, 0, 'Banana', 50, '1', 0, 1, '2025-04-19', 'pamukpok sa ulo'),
(26, 7, 8, 'Bans', 10, '', 1, 2, '2025-04-20', ''),
(27, 7, 8, 'Bans', 20, '', 1, 2, '2025-04-20', 'Pang bato sa ulo'),
(28, 7, 0, 'Hotdog', 100, '2', 0, 1, '2025-04-20', 'Pamalo sa Ulo'),
(29, 7, 0, 'Bans', 20, '1', 0, 2, '2025-04-21', ''),
(30, 7, 0, 'Hotdog', 100, '1', 0, 1, '2025-04-26', ''),
(31, 7, 0, 'Chicken', 100, '1', 0, 1, '2025-04-26', ''),
(32, 7, 0, 'Chicken', 20, '1', 0, 1, '2025-04-26', ''),
(33, 7, 0, 'Beef', 500, '1', 0, 1, '2025-04-26', ''),
(34, 7, 0, 'garlic', 50, '1', 0, 1, '2025-04-26', ''),
(35, 7, 15, 'Beef', 100, '', 1, 1, '2025-04-26', 'pang ulam ko muna'),
(36, 7, 8, 'Bans', 20, '', 1, 2, '2025-04-26', ''),
(37, 7, 8, 'Bans', 19, '', 1, 2, '2025-04-27', ''),
(38, 7, 8, 'Bans', 10, '', 1, 2, '2025-04-27', ''),
(39, 7, 8, 'Bans', 5, '', 1, 1, '2025-04-27', ''),
(40, 7, 15, 'Beef', 100, '', 1, 2, '2025-04-27', ''),
(41, 7, 0, 'Cabage', 10, '1', 0, 1, '2025-04-27', ''),
(42, 7, 0, 'Hotdog', 10, '', 0, 1, '2025-04-27', ''),
(43, 7, 0, 'cheese', 50, '', 0, 1, '2025-04-29', 'restock');

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
  MODIFY `productId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menuId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notificationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `orderedhistory`
--
ALTER TABLE `orderedhistory`
  MODIFY `historyId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `ordereditemhistory`
--
ALTER TABLE `ordereditemhistory`
  MODIFY `historyId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transactionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `userstable`
--
ALTER TABLE `userstable`
  MODIFY `usersId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
