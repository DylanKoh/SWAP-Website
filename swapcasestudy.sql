-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2020 at 04:34 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `swapcasestudy`
--

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `messageId` int(10) UNSIGNED NOT NULL,
  `messageContent` text NOT NULL,
  `usersFkid` int(10) UNSIGNED NOT NULL,
  `providersFkid` int(10) UNSIGNED NOT NULL,
  `isSending` tinyint(1) NOT NULL,
  `isReceiving` tinyint(1) NOT NULL,
  `messageDate` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `ordersId` int(10) UNSIGNED NOT NULL,
  `customerFkid` int(10) UNSIGNED NOT NULL,
  `isAccepted` tinyint(1) DEFAULT NULL,
  `orderDate` date NOT NULL DEFAULT current_timestamp(),
  `comments` varchar(1000) DEFAULT NULL,
  `servicesFkid` int(10) UNSIGNED NOT NULL,
  `isCompleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`ordersId`, `customerFkid`, `isAccepted`, `orderDate`, `comments`, `servicesFkid`, `isCompleted`) VALUES
(4, 3, 1, '0000-00-00', 'Provider is required to finish the job within 2 weeks ', 1, 0),
(22, 3, 1, '2020-12-12', 'The provider will be required to quickly complete all the tasks', 1, 0),
(27, 7, 1, '2020-12-13', 'Please complete', 1, 0),
(28, 7, 0, '2020-12-13', 'Please complete within 1 week', 2, 0),
(37, 7, 1, '2020-12-13', 'adaa', 40, 1),
(38, 7, 1, '2020-12-13', 'dadasda', 40, 1),
(39, 7, 1, '2020-12-13', 'adas', 40, 1),
(40, 7, 1, '2020-12-13', 'Aadasd', 40, 1),
(41, 7, 1, '2020-12-13', 'asdas', 40, 1),
(42, 7, 1, '2020-12-13', 'asda', 40, 0),
(43, 7, 0, '2020-12-13', 'asdas', 40, 0),
(44, 8, 0, '2020-12-13', 'Provider complete', 40, 0);

-- --------------------------------------------------------

--
-- Table structure for table `providers`
--

CREATE TABLE `providers` (
  `providersId` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `salt_1` char(16) NOT NULL,
  `salt_2` char(16) NOT NULL,
  `googleSecret` char(16) DEFAULT NULL,
  `passwordDate` date NOT NULL,
  `username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `providers`
--

INSERT INTO `providers` (`providersId`, `name`, `email`, `password`, `salt_1`, `salt_2`, `googleSecret`, `passwordDate`, `username`) VALUES
(1, 'Jack', 'Jacki123@gmail.com', '1234', '1234', '1234', '', '0000-00-00', 'Bobthebuilder'),
(2, 'Yireng', 'Cyr@gmail.com', 'NDEwZWQ3OGQzNjI3MDAzZTU4MGFiMjcyM2JmN2I3N2IxODliYjQyYmRiYzBmYmIzMzQ3NTkzYTg2NTJhODQyMA==', '5XX2ZVAYLPR4PZLH', 'JMUMZJJWV2SMAB4I', NULL, '0000-00-00', 'Cyr'),
(3, 'CYR', 'Cyr2@gmail.com', 'MGYzYWE3OWFmYmRmOWRhMjIwNDcyOWE5MzYzZDQyNjdlNTcyYjM4ZGJjMDUxOTZjMGQ4ZmEzMmE4NDk1YzgxNA==', 'WIGTYNBXPIVK2TMB', 'LXXHMU67DHQX7GS7', NULL, '2020-12-11', 'cyr123'),
(4, 'CYRQ', 'cyrQ1@gmail.com', 'ZjQxMTE2N2I0OWU4NDcwZmI0NDY5NWNlZTVmZjBjMDEwYTAzMDEwYTE2MWVlMmI3YzViNDJiNGNlMDU4ZWUwZg==', 'G5LTCVDDA6I6XWWK', 'B4DXBVDMDZUXD5GD', NULL, '0000-00-00', 'CYRQ123'),
(5, 'Eon', 'Eon@gmail.com', 'MDYzZjk3NzQxMjNhN2U0ZjlhNTk5NGExMDE4NzgwNzFjZjgyZGUyYmE1ODhmNjRlMWI1YjU4MzZmOWM5MzhmNw==', '7YMLXAYJFBQQRUC3', 'EPUQFJDFSE76ILGE', NULL, '0000-00-00', 'Eon');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `reviewsId` int(10) UNSIGNED NOT NULL,
  `ordersFkid` int(11) UNSIGNED NOT NULL,
  `usersFkid` int(11) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL,
  `comments` text NOT NULL,
  `ratingDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`reviewsId`, `ordersFkid`, `usersFkid`, `rating`, `comments`, `ratingDate`) VALUES
(24, 4, 7, 5, 'Provider was really professional in completing the tasks!', '2020-12-13 12:48:56'),
(25, 4, 7, 5, 'Fantastic seller', '2020-12-13 12:55:40'),
(28, 43, 7, 5, 'Provider was efficient', '2020-12-13 16:51:00'),
(29, 44, 8, 5, 'Provider was fast in completing the tasks.', '2020-12-13 21:03:43');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `salesId` int(10) UNSIGNED NOT NULL,
  `creditCard` text NOT NULL,
  `expiryDate` text NOT NULL,
  `fourDigits` int(4) NOT NULL,
  `usersFkid` int(11) UNSIGNED NOT NULL,
  `secret` varchar(200) NOT NULL,
  `hash_1` char(16) NOT NULL,
  `hash_2` char(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `servicesId` int(10) UNSIGNED NOT NULL,
  `serviceName` varchar(100) NOT NULL,
  `serviceDesc` text NOT NULL,
  `providersFkid` int(10) UNSIGNED NOT NULL,
  `price` double UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`servicesId`, `serviceName`, `serviceDesc`, `providersFkid`, `price`) VALUES
(1, 'Pentesting services!', 'I provide Pentesting services to companies, prices are negotiable according to the difficulty', 1, 120),
(2, 'Cheap Pentesting 2', 'I provide cheap pentesting services for companies and organisations, price is negotiable', 1, 100),
(39, 'Pentesting services for Cheap', 'Cheap services!!', 3, 111),
(40, 'Pentesting services!!', 'I provide alot of cheap pentesting services!', 2, 150);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `usersId` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `salt_1` char(16) NOT NULL,
  `salt_2` char(16) NOT NULL,
  `googleSecret` char(16) DEFAULT NULL,
  `passwordDate` date NOT NULL,
  `username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`usersId`, `name`, `email`, `password`, `salt_1`, `salt_2`, `googleSecret`, `passwordDate`, `username`) VALUES
(2, 'Yireng', 'Cyr@gmail.com', 'YjdiNzRkMGRkODYyNjhlNWEyYTIxZDVjOGQ2N2JlMTFiNDI4MmMxOTFlOGNhMzc4ZjkxOWVjMGIzMDVlOTliZA==', 'ITAULQUY4ZOGOVOX', 'QSB3DRMZ36ENQNET', '2OUCOD6EMVFZSK5F', '0000-00-00', 'Cyr'),
(3, 'Yx', 'Yx@gmail.com', 'ZThlZTk0OTkwMWNmMGVmMTU5M2ExNjg0MzMwMzg0ZTJmMjhiOWJhNjUzYWRjODcwMzA2NDAwYjU0YzNlOWUyNw==', 'TZROQ5VWLBHJKOPW', 'GZBIQUQQVQYL5VRB', NULL, '0000-00-00', 'Yx123'),
(7, 'CYR', 'Cyr3@gmail.com', 'YTg0NmZjZDlmMjcwMTUwOGQzYjdjZGExYjE5YTFkMGRmOTUxN2UzYWU0ZWQ1YzYzMTFkYjI0NWQ3MTY1MzlmYQ==', '7ARBO65TNOPODKBO', 'TJDPRLOJVOKTFF2W', NULL, '0000-00-00', 'cyrQ'),
(8, 'Eon', 'Eon@gmail.com', 'MDA5MWY2ZWViMDUwY2UyNjQ4ZDI3YTRkMDZhOGViN2M3ZGRkM2UwZDdlNTQ1NTRkM2MxNGY4MjJkNDZhNTRjNQ==', 'QYWSPTPUMUTWABP6', 'AY6K2KPNHG6QFUTO', NULL, '0000-00-00', 'EonCus');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`messageId`),
  ADD KEY `usersFkid` (`usersFkid`),
  ADD KEY `providersFkid` (`providersFkid`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ordersId`),
  ADD KEY `customerFkid` (`customerFkid`),
  ADD KEY `servicesFkid` (`servicesFkid`);

--
-- Indexes for table `providers`
--
ALTER TABLE `providers`
  ADD PRIMARY KEY (`providersId`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`reviewsId`),
  ADD KEY `servicesId` (`ordersFkid`),
  ADD KEY `usersId` (`usersFkid`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`salesId`),
  ADD KEY `usersFkid` (`usersFkid`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`servicesId`),
  ADD KEY `userFkid` (`providersFkid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`usersId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `messageId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `ordersId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `providers`
--
ALTER TABLE `providers`
  MODIFY `providersId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `reviewsId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `salesId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `servicesId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `usersId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`usersFkid`) REFERENCES `users` (`usersId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`providersFkid`) REFERENCES `providers` (`providersId`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`servicesFkid`) REFERENCES `services` (`servicesId`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`customerFkid`) REFERENCES `users` (`usersId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`usersFkid`) REFERENCES `users` (`usersId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`ordersFkid`) REFERENCES `orders` (`ordersId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`usersFkid`) REFERENCES `users` (`usersId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`providersFkid`) REFERENCES `providers` (`providersId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
