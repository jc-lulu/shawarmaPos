-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 13, 2025 at 07:30 AM
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
  `transactionId` int(11) NOT NULL,
  `transactionStatus` int(11) NOT NULL,
  `dateOfIn` date NOT NULL,
  `dateOfOut` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`productId`, `productName`, `quantity`, `productType`, `type`, `transactionId`, `transactionStatus`, `dateOfIn`, `dateOfOut`) VALUES
(1, 'Bans', 10, '', 0, 0, 0, '2025-04-05', '0000-00-00');

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
(4, 4, 255, '2025-04-13', '07:27:50');

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
(19, 4, 'Shawarma Rice', 130, 1, 130, '2025-04-13');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `transactionId` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `transactionType` int(11) NOT NULL COMMENT '0-Out, 1-In',
  `transactionStatus` int(11) NOT NULL COMMENT '0-Pending, 1-Approved, 2-Declined',
  `dateOut` date NOT NULL,
  `dateIn` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'cedric', 'jcmaldia172002@gmail.com', '$2y$10$hL6fXyphh7z/gb6aRZaGR.ya1W2oZ2HTtwaSKRbG0oYCbGZ31CykC', 0),
(2, 'maineganda', 'mainegandap@gmail.com', '$2y$10$0TBwM3U5RrS8D92thTV8EubZrlZPWjyE4MP/xDUpodvm05UmKOioy', 1),
(3, 'joed', 'joed@gmail.com', '$2y$10$z/zNFymvjWTN6wKXNijGqOEj2fjLKY7tbnWZNs9liFAPtk9vILEzq', 1),
(4, 'jed123', 'jedmaldia@gmail.com', '$2y$10$y/Io0WdaH1pC1CnY5sFNuO1CHNyhvuu56wEaKZ0bUuoHnXnoxSV1i', 1),
(5, 'jed2', 'jed@gmail.com', '$2y$10$39IuPmcrXYHi.e0yDy2pyuKq0o/k9yRLe7aRdSFkMT8pkiRiHj.8i', 1);

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
  MODIFY `productId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menuId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orderedhistory`
--
ALTER TABLE `orderedhistory`
  MODIFY `historyId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ordereditemhistory`
--
ALTER TABLE `ordereditemhistory`
  MODIFY `historyId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transactionId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userstable`
--
ALTER TABLE `userstable`
  MODIFY `usersId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
