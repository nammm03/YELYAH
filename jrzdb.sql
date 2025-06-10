-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2025 at 10:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
(10, 'admin10', '2025-06-02 10:09:16', '2025-06-02 10:09:31');

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
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
