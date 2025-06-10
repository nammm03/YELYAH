-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2025 at 08:02 PM
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
-- Database: `jrzdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `parent_id`) VALUES
(1, 'DB Schenker', NULL),
(2, 'NutriAsia', NULL),
(3, 'UPPH', NULL),
(5, 'DB Schenker - Zenith', 1),
(6, 'DB Schenker - Krispy Kreme', 1),
(8, 'PAULINE MY LABS', NULL),
(12, 'POGI', NULL),
(13, 'TUBI', NULL),
(14, 'Wilkins', 8),
(16, 'TAYLOR SWIFT', 1);

-- --------------------------------------------------------

--
-- Table structure for table `billing_dbschenker`
--

CREATE TABLE `billing_dbschenker` (
  `billing_id` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `otrp_id` int(11) DEFAULT NULL,
  `origin` varchar(255) DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `plate_number` int(11) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `DR` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fleet`
--

CREATE TABLE `fleet` (
  `id` int(11) NOT NULL,
  `plate_number` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `ownership` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fleet`
--

INSERT INTO `fleet` (`id`, `plate_number`, `type`, `ownership`) VALUES
(1, 'CBA 4348', '10W', 'Owned'),
(2, 'CBQ 3983', '10W', 'Owned'),
(3, 'CBP 8015', '10W', 'Owned'),
(4, 'CCO 8521', '10W', 'Rented'),
(5, 'CBQ 6115', '10W', 'Owned'),
(6, 'CAK 8563', '10W', 'Owned'),
(7, 'NHE 8519', '6W', 'Owned');

-- --------------------------------------------------------

--
-- Table structure for table `otrp`
--

CREATE TABLE `otrp` (
  `id` int(11) NOT NULL,
  `account` int(11) NOT NULL,
  `origin` varchar(255) NOT NULL,
  `destination` text NOT NULL,
  `truck_size` varchar(10) DEFAULT NULL,
  `rate` decimal(10,2) NOT NULL,
  `driver` decimal(10,2) NOT NULL,
  `helper1` decimal(10,2) NOT NULL,
  `helper2` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otrp`
--

INSERT INTO `otrp` (`id`, `account`, `origin`, `destination`, `truck_size`, `rate`, `driver`, `helper1`, `helper2`) VALUES
(1, 5, 'Calamba', 'eaasd', '12w', 5000.00, 3000.00, 2000.00, 2000.00),
(2, 8, 'HAHAHA', 'asdasdasdss', '10W', 5555.00, 2222.00, 3333.00, 1233.00),
(4, 2, 'Calamba', 'asdasd', '10W', 1313.00, 31.00, 31.00, 31.00),
(5, 6, 'Calamba', 'bbbjmk', '10W', 5000.00, 4000.00, 300.00, 2000.00),
(6, 12, 'Calamba', 'sadasd', '10W', 13123.00, 12313.00, 13.00, 13.00),
(7, 13, 'Calamba', 'sad', '12w', 51515.00, 144.00, 444.00, 444.00),
(8, 14, 'tata', 'asdad', '12w', 13123.00, 123123.00, 12313.00, 1313.00),
(9, 16, 'asdasd', 'dasd', '10W', 123.00, 123.00, 123.00, 123.00),
(10, 2, '13ff', '123ff', '6w', 13123.00, 12313.00, 1313.00, 123123.00),
(11, 6, '13asd', '123asd', '4w', 123123.00, 12312.00, 123123.00, 123123.00),
(12, 1, '132dd', 'dd123', '12w', 123123.00, 123123.00, 123123.00, 1313.00);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `username`, `login_time`, `logout_time`) VALUES
(1, 'juan', '2025-05-30 09:01:30', NULL),
(2, 'juan', '2025-06-01 16:54:09', NULL),
(3, 'juan', '2025-06-02 09:23:39', NULL),
(4, 'juan', '2025-06-02 09:28:29', NULL),
(5, 'juan', '2025-06-02 09:35:00', '2025-06-02 09:38:39'),
(6, 'juan', '2025-06-02 09:44:09', NULL),
(7, 'juan', '2025-06-02 09:46:46', NULL),
(8, 'juan', '2025-06-02 09:51:03', '2025-06-02 09:52:48'),
(9, 'juan', '2025-06-02 09:57:25', '2025-06-02 10:01:31'),
(10, 'admin10', '2025-06-02 10:09:16', '2025-06-02 10:09:31'),
(11, 'juan', '2025-06-02 16:23:28', NULL),
(12, 'juan', '2025-06-03 10:25:18', NULL),
(13, 'admin7', '2025-06-04 18:11:49', '2025-06-04 19:44:16'),
(14, 'admin5', '2025-06-04 19:45:08', '2025-06-04 19:55:23'),
(15, 'admin1', '2025-06-04 19:56:01', '2025-06-04 20:11:08'),
(16, 'admin1', '2025-06-04 20:11:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'juan', '$2y$10$q.bHXfRTw2l76YUnE5xsk.ljWc6cPLpGngGk/Ldixn0P.iLBvesyO', 'admin'),
(2, 'admin1', '$2y$10$wNjNc7Iv7cLKHIQTvLG7kOkN56fRQUc8khkzirXog7O4A1SnZxvqK', 'admin'),
(3, 'admin2', '$2y$10$vezjuRrjLK9iPzsik2.bbOMLOXjceNtFTvpp2dH78XIZYdjPeEoii', 'admin'),
(4, 'admin3', '$2y$10$yYlsn5Rh7z1vrkw6VnTwVuZCj/Pr2zrt3FLNYvRBpaUTVYGX0HT56', 'admin'),
(5, 'admin4', '$2y$10$hAp0Nr5vNwF5AQOEVCsa5uEbQsteonr.gNb19SH4BGoVw.QYhG25m', 'admin'),
(6, 'admin5', '$2y$10$TzdR3R8DYkh949mC1jDWiOIUi/w5QBTQZyrBtY5Yni6WxZLC8Xhvm', 'admin'),
(7, 'admin6', '$2y$10$LfnhME7Vu0wgsHdOYb/loOQ8CnsMaG6nxYqgwWkTsAgU7o57bGj9S', 'admin'),
(8, 'admin7', '$2y$10$CKEL67rSszlIdfWxymJE/OqhQ8Xym8PLZOwzh2G9hPgN2ndJRIjkS', 'admin'),
(9, 'admin8', '$2y$10$.T3F3Ppym9WBIFREyveU2udpAaLosqXiEAjqnRyPA/YC4tCqRwTRm', 'admin'),
(10, 'admin9', '$2y$10$Cfw5R./.CC2tjSb6fsk6Kux9kGeK/r.8o1LN2e.zyFKACu5MyzsqG', 'admin'),
(11, 'admin10', '$2y$10$d4jJC07Ry2QQ5.BUIsXmM.HVxzigXCd4uwOLzs0lAnaC6EWdq.pg2', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `billing_dbschenker`
--
ALTER TABLE `billing_dbschenker`
  ADD PRIMARY KEY (`billing_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `otrp_id` (`otrp_id`),
  ADD KEY `plate_number` (`plate_number`);

--
-- Indexes for table `fleet`
--
ALTER TABLE `fleet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otrp`
--
ALTER TABLE `otrp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_otrp_account` (`account`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `billing_dbschenker`
--
ALTER TABLE `billing_dbschenker`
  MODIFY `billing_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fleet`
--
ALTER TABLE `fleet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `otrp`
--
ALTER TABLE `otrp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `billing_dbschenker`
--
ALTER TABLE `billing_dbschenker`
  ADD CONSTRAINT `billing_dbschenker_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `billing_dbschenker_ibfk_2` FOREIGN KEY (`otrp_id`) REFERENCES `otrp` (`id`),
  ADD CONSTRAINT `billing_dbschenker_ibfk_3` FOREIGN KEY (`plate_number`) REFERENCES `fleet` (`id`);

--
-- Constraints for table `otrp`
--
ALTER TABLE `otrp`
  ADD CONSTRAINT `fk_otrp_account` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
