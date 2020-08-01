-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2020 at 07:36 PM
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
-- Dumping data for table `log`
--

INSERT INTO `log` (`id`, `name`, `password`, `email`, `city`, `map`, `buildings`, `buildings_cost`, `warehouse`, `prices`, `prices_exchange`, `year`, `end`, `money`, `citizens`, `demolish_cost`, `builds`, `demolitions`, `tax`, `difficulty`, `score`) VALUES
(23, '1', 'c4ca4238a0b923820dcc509a6f75849b', 'zozas@hotmail.com', '1', '1,1,1,2,0,1,0,0,0,1,1,1,0,0,0,1,1,1,3,0,0,2,3,0,0,3,0,1,0,2,1,2,1,0,0,1,2,1,3,0,0,1,3,0,0,3,1,1,0,0,2,3,0,0,1,0,0,1,3,0,3,1,3,0,1,3,1,1,1,3,0,2,0,0,3,1,0,3,0,2,3,0,0,3,0,0,1,1,2,2,1,3,0,0,2,3,0,1,0,1', '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '1350,644,1134,178,108,576,809,2061,310,2163,1887,1875,2512,85,93,612,67,1081,999,694,831,759,1758,1325,549,1908', '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '150,161,6,2,58,12,42,72,121,120,69,80,29,126,38,36,181,179,149,29,11,48,46,45,216,33', '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', 0, 365, 1000000, 3, 35, 0, 0, 1, 50, 14000),
(24, '2', 'c81e728d9d4c2f636f067f89cc14862c', 'zozas@hotmail.com', '2', '2,2,2,2,1,3,3,0,3,3,3,1,3,2,3,1,2,3,3,3,3,0,1,2,2,2,2,3,2,3,1,2,2,3,3,1,1,3,2,3,3,2,2,3,2,2,3,2,2,1,3,3,1,2,2,3,3,3,1,2,2,2,0,1,2,3,2,1,3,2,1,3,3,2,1,2,1,1,1,1,3,2,3,3,3,3,3,2,0,1,2,3,1,3,3,3,3,1,2,3', '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '977,868,429,2702,69,2032,564,1388,532,3824,3541,1247,3093,3151,2757,4817,1546,3177,3105,161,621,1907,2050,2012,338,1545', '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '49,64,158,16,94,311,111,31,467,23,105,251,79,51,70,68,228,345,48,37,34,376,482,101,328,265', '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', 0, 365, 1000, 3, 110, 0, 0, 1, 100, 0);

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
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `city`, `map`, `buildings`, `buildings_cost`, `warehouse`, `prices`, `prices_exchange`, `year`, `end`, `money`, `citizens`, `demolish_cost`, `builds`, `demolitions`, `tax`, `difficulty`, `score`) VALUES
(23, '1', 'c4ca4238a0b923820dcc509a6f75849b', 'zozas@hotmail.com', '1s', '1,1,1,2,0,1,0,0,0,1,1,1,0,0,0,1,1,1,3,0,0,2,3,0,0,3,0,1,0,2,1,2,1,0,0,1,2,1,3,0,0,1,3,0,0,3,1,1,0,0,2,3,0,0,1,0,0,1,3,0,3,1,3,0,1,3,1,1,1,3,0,2,0,0,3,1,0,3,0,2,3,0,0,3,0,0,1,1,2,2,1,3,0,0,2,3,0,1,0,1', '2,2,2,0,0,0,0,0,0,0,2,2,0,0,7,0,0,0,0,0,0,0,0,7,7,0,0,0,0,0,0,0,0,7,7,0,0,0,0,0,0,0,0,7,7,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '1704,998,1488,532,462,930,1163,2415,664,2517,2241,2229,2866,439,447,966,421,1435,1353,1048,1185,1113,2112,1679,903,2262', '0,10,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '250,986,136,156,188,102,262,660,762,447,175,244,89,329,141,78,369,631,330,455,93,158,311,254,786,505', '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', 180, 500, 55396, 7, 8, 14, 2, 5, 50, 16.234),
(24, '2', 'c81e728d9d4c2f636f067f89cc14862c', 'zozas@hotmail.com', '2', '2,2,2,2,1,3,3,0,3,3,3,1,3,2,3,1,2,3,3,3,3,0,1,2,2,2,2,3,2,3,1,2,2,3,3,1,1,3,2,3,3,2,2,3,2,2,3,2,2,1,3,3,1,2,2,3,3,3,1,2,2,2,0,1,2,3,2,1,3,2,1,3,3,2,1,2,1,1,1,1,3,2,3,3,3,3,3,2,0,1,2,3,1,3,3,3,3,1,2,3', '0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,5,5,0,0,0,0,0,0,0,0,5,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '991,882,443,2716,83,2046,578,1402,546,3838,3555,1261,3107,3165,2771,4831,1560,3191,3119,175,635,1921,2064,2026,352,1559', '1,0,0,0,3,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', '55,71,169,20,94,401,145,39,542,28,125,218,77,52,63,75,266,402,44,46,39,395,457,99,382,281', '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0', 7, 365, 76, 2, 110, 4, 0, 1, 100, 11.1732);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
