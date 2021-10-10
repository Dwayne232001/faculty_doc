-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2018 at 11:46 AM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 5.6.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `department`
--

-- --------------------------------------------------------

--
-- Table structure for table `invitedlec`
--

CREATE TABLE `invitedlec` (
	`invited_id` int(50) NOT NULL PRIMARY KEY,
  `Fac_ID` int(50) NOT NULL,
  `organized` varchar(500) NOT NULL,
  `durationf` date NOT NULL,
  `durationt` date NOT NULL,
  `award` varchar (500) NOT NULL,
   `topic` text,
   `details` text,
   `tdate` timestamp NOT NULL,
  `invitation_path` varchar(100) NOT NULL,
  `certificate_path` varchar(100) NOT NULL   
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `invitedlec`
--
ALTER TABLE `invitedlec`
  ADD PRIMARY KEY (`invited_id`);

--
-- AUTO_INCREMENT for dumped tables
--

ALTER TABLE `invitedlec`
  ADD CONSTRAINT `foreign key_Fac_ID` FOREIGN KEY (`Fac_ID`) REFERENCES `facultydetails` (`Fac_ID`) ON UPDATE CASCADE;
  COMMIT;

--
-- AUTO_INCREMENT for table `invitedlec`
--
ALTER TABLE `invitedlec`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;