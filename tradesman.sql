-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2020 at 12:22 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tradesman`
--

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `password` text NOT NULL,
  `email` text NOT NULL,
  `city` text NOT NULL,
  `map` text NOT NULL,
  `buildings` text NOT NULL,
  `buildings_cost` text NOT NULL,
  `warehouse` text NOT NULL,
  `prices` text NOT NULL,
  `prices_exchange` text NOT NULL,
  `expertise` text NOT NULL,
  `year` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `money` int(11) NOT NULL,
  `citizens` int(11) NOT NULL,
  `demolish_cost` int(11) NOT NULL,
  `builds` int(11) NOT NULL,
  `demolitions` int(11) NOT NULL,
  `tax` int(11) NOT NULL,
  `difficulty` int(11) NOT NULL,
  `score` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `online`
--

CREATE TABLE `online` (
  `user_id` int(11) NOT NULL,
  `session` char(100) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `password` text NOT NULL,
  `email` text NOT NULL,
  `city` text NOT NULL,
  `map` text NOT NULL,
  `buildings` text NOT NULL,
  `buildings_cost` text NOT NULL,
  `warehouse` text NOT NULL,
  `prices` text NOT NULL,
  `prices_exchange` text NOT NULL,
  `expertise` text NOT NULL,
  `year` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `money` int(11) NOT NULL,
  `citizens` int(11) NOT NULL,
  `demolish_cost` int(11) NOT NULL,
  `builds` int(11) NOT NULL,
  `demolitions` int(11) NOT NULL,
  `tax` int(11) NOT NULL,
  `difficulty` int(11) NOT NULL,
  `score` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
