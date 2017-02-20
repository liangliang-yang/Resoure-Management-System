-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2016 at 12:01 AM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erms_team26`
--
CREATE DATABASE IF NOT EXISTS erms_team26;
USE erms_team26;
-- --------------------------------------------------------

--
-- Table structure for table `additionalesfs`
--

CREATE TABLE `additionalesfs` (
  `ResourceID` bigint(20) NOT NULL,
  `AdditionalESFID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `additionalesfs`
--

INSERT INTO `additionalesfs` (`ResourceID`, `AdditionalESFID`) VALUES
(1, 1),
(1, 5),
(1, 13),
(3, 5),
(3, 13),
(4, 5),
(4, 9),
(4, 13),
(7, 3),
(7, 5),
(7, 9),
(7, 13);

-- --------------------------------------------------------

--
-- Table structure for table `capabilities`
--

CREATE TABLE `capabilities` (
  `ResourceID` bigint(20) NOT NULL,
  `Capability` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `capabilities`
--

INSERT INTO `capabilities` (`ResourceID`, `Capability`) VALUES
(3, 'Respond to accident'),
(3, 'Respond to crime'),
(4, 'Medical service'),
(4, 'Transport of patients to hospital'),
(7, 'Fight fire'),
(7, 'Fire-escape ladder equiped');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `UserName` varchar(50) NOT NULL,
  `Headquarter` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`UserName`, `Headquarter`) VALUES
('cocacola', 'Atlanta'),
('google', 'Menlo Park'),
('amazon', 'Seattle'),
('microsoft', 'Redmond'),
('intel', 'Santa Clara');

-- --------------------------------------------------------

--
-- Table structure for table `costper`
--

CREATE TABLE `costper` (
  `Unit` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `costper`
--

INSERT INTO `costper` (`Unit`) VALUES
('day'),
('each'),
('hour'),
('week');

-- --------------------------------------------------------

--
-- Table structure for table `esfs`
--

CREATE TABLE `esfs` (
  `ESFID` int(11) NOT NULL,
  `ESF_D` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `esfs`
--

INSERT INTO `esfs` (`ESFID`, `ESF_D`) VALUES
(1, 'Transportation'),
(2, 'Communications'),
(3, 'Public Works and Engineering'),
(4, 'Firefighting'),
(5, 'Emergency Management'),
(6, 'Mass Care, Emergency Assistance, Housing, and Human Services'),
(7, 'Logistics Management and Resource Support'),
(8, 'Public Health and Medical Services'),
(9, 'Search and Rescue'),
(10, 'Oil and Hazardous Materials Response'),
(11, 'Agriculture and Natural Resources'),
(12, 'Energy'),
(13, 'Public Safety and Security'),
(14, 'Long-Term Community Recovery'),
(15, 'External Affairs');

-- --------------------------------------------------------

--
-- Table structure for table `government_agency`
--

CREATE TABLE `government_agency` (
  `UserName` varchar(50) NOT NULL,
  `Jurisdiction` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `government_agency`
--

INSERT INTO `government_agency` (`UserName`, `Jurisdiction`) VALUES
('atlantadmv', 'Local'),
('firedepartment', 'Local'),
('traffic', 'local'),
('police', 'local'),
('hospital', 'local');

-- --------------------------------------------------------

--
-- Table structure for table `incident`
--

CREATE TABLE `incident` (
  `IncidentID` bigint(20) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `DateAdd` date NOT NULL,
  `Description` varchar(500) NOT NULL,
  `Latitude` float NOT NULL,
  `Longitude` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `incident`
--

INSERT INTO `incident` (`IncidentID`, `Username`, `DateAdd`, `Description`, `Latitude`, `Longitude`) VALUES
(1, 'johndeniel', '2016-11-22', 'Leg broken', 33.08, -86.68),
(2, 'johndeniel', '2016-11-22', 'fire at home', 33.08, -86.68),
(3, 'johndeniel', '2016-11-21', 'Robbed', 33.72, -86.11),
(4, 'cocacola', '2016-11-22', 'Flood', 33.98, -82.61),
(5, 'cocacola', '2016-11-20', 'Earthquake', 33.97, -82.59),
(6, 'cocacola', '2016-11-19', 'Power bump', 33.94, -82.62),
(7, 'cityofatlanta', '2016-11-02', 'Flash Flood in Fulton County', 32.185, -86.254),
(8, 'cityofatlanta', '2016-11-25', 'Forest Fire in NW Atlanta', 26.878, -78.544),
(9, 'johndeniel', '2016-12-02', 'Empty tank', 45.52, -122.68),
(10, 'johndeniel', '2016-11-05', 'car accident', 45.52, -122.68),
(11, 'firedepartment', '2016-12-01', 'Earthquake', 45.99, -88.88);

-- --------------------------------------------------------

--
-- Table structure for table `individual`
--

CREATE TABLE `individual` (
  `UserName` varchar(50) NOT NULL,
  `DateHired` date NOT NULL,
  `Jobtitle` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `individual`
--

INSERT INTO `individual` (`UserName`, `DateHired`, `Jobtitle`) VALUES
('johndeniel', '2016-01-01', 'Driver'),
('bob', '2015-07-01', 'Cooker'),
('david', '2016-06-01', 'Teacher'),
('jack', '2014-08-31', 'Programmer'),
('ted', '2015-09-20', 'Seller');

-- --------------------------------------------------------

--
-- Table structure for table `municipality`
--

CREATE TABLE `municipality` (
  `UserName` varchar(50) NOT NULL,
  `PopulationSize` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `municipality`
--

INSERT INTO `municipality` (`UserName`, `PopulationSize`) VALUES
('cityofatlanta', 447841),
('seattle', 652400),
('boston', 646000),
('portland', 609500),
('sacramento', 466400);

-- --------------------------------------------------------

--
-- Table structure for table `repair`
--

CREATE TABLE `repair` (
  `ResourceID` bigint(20) NOT NULL,
  `StartDate` date NOT NULL,
  `ReturnDate` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `repair`
--

INSERT INTO `repair` (`ResourceID`, `StartDate`, `ReturnDate`) VALUES
(12, '2016-11-10', '2016-11-15'),
(13, '2016-11-10', '2016-11-15'),
(11, '2016-11-10', '2016-11-15'),
(14, '2016-11-05', '2016-11-10'),
(20, '2016-11-10', '2016-11-15'),
(16, '2016-11-05', '2016-11-10'),
(19, '2016-11-10', '2016-11-15'),
(18, '2016-11-05', '2016-11-10'),
(15, '2016-11-05', '2016-11-10'),
(17, '2016-11-05', '2016-11-10'),
(12, '2016-12-10', '2016-12-15'),
(13, '2016-12-10', '2016-12-15'),
(11, '2016-12-10', '2016-12-15'),
(14, '2016-12-05', '2016-12-10'),
(20, '2016-12-10', '2016-12-15'),
(16, '2016-12-05', '2016-12-10'),
(19, '2016-12-10', '2016-12-15'),
(18, '2016-12-05', '2016-12-10'),
(15, '2016-12-05', '2016-12-10'),
(17, '2016-12-05', '2016-12-10');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `ResourceID` bigint(20) NOT NULL,
  `IncidentID` bigint(20) NOT NULL,
  `RequestTime` datetime NOT NULL,
  `RequestStatus` varchar(50) NOT NULL,
  `ExpectedReturnDate` date NOT NULL,
  `DeployDate` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`ResourceID`, `IncidentID`, `RequestTime`, `RequestStatus`, `ExpectedReturnDate`, `DeployDate`) VALUES
(16, 1, '2016-12-02 22:36:00', 'Accepted', '2016-12-05', '2016-12-02'),
(14, 1, '2016-12-02 22:36:19', 'Accepted', '2016-12-05', '2016-12-02'),
(15, 1, '2016-12-02 22:36:35', 'Accepted', '2016-12-05', '2016-12-02'),
(18, 7, '2016-12-02 22:37:58', 'Accepted', '2016-12-05', '2016-12-02'),
(17, 7, '2016-12-02 22:38:13', 'Accepted', '2016-12-05', '2016-12-02'),
(13, 4, '2016-12-02 23:05:35', 'Accepted', '2016-12-10', '2016-12-02'),
(11, 4, '2016-12-02 22:59:58', 'Accepted', '2016-12-10', '2016-12-02'),
(19, 4, '2016-12-02 23:00:30', 'Accepted', '2016-12-10', '2016-12-02'),
(12, 4, '2016-12-02 23:01:07', 'Accepted', '2016-12-10', '2016-12-02'),
(20, 4, '2016-12-02 23:09:28', 'Accepted', '2016-12-10', '2016-12-02'),
(30, 10, '2016-12-02 23:55:20', 'Pending', '2016-12-08', NULL),
(23, 10, '2016-12-02 23:55:42', 'Pending', '2016-12-08', NULL),
(29, 8, '2016-12-02 23:56:23', 'Pending', '2016-12-09', NULL),
(27, 8, '2016-12-02 23:57:01', 'Pending', '2016-12-15', NULL),
(21, 6, '2016-12-02 23:58:08', 'Pending', '2016-12-14', NULL),
(25, 6, '2016-12-02 23:58:23', 'Pending', '2016-12-23', NULL),
(24, 6, '2016-12-02 23:59:20', 'Pending', '2016-12-13', NULL),
(28, 8, '2016-12-03 00:00:27', 'Pending', '2016-12-24', NULL),
(26, 8, '2016-12-03 00:00:44', 'Pending', '2016-12-17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `resource`
--

CREATE TABLE `resource` (
  `ResourceID` bigint(20) NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `ResourceName` varchar(50) NOT NULL,
  `Model` varchar(50) DEFAULT NULL,
  `costvalue` float NOT NULL,
  `cost_per` varchar(50) NOT NULL,
  `Primary_ESFID` int(11) NOT NULL,
  `Latitude` float NOT NULL,
  `Longitude` float NOT NULL,
  `Resource_Status` varchar(50) NOT NULL,
  `NextAvailableDate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `resource`
--

INSERT INTO `resource` (`ResourceID`, `UserName`, `ResourceName`, `Model`, `costvalue`, `cost_per`, `Primary_ESFID`, `Latitude`, `Longitude`, `Resource_Status`, `NextAvailableDate`) VALUES
(1, 'cityofatlanta', 'Rescue Boat', 'Boat 2015', 150, 'day', 9, 33.865, -84.256, 'Available', '2016-11-10 03:14:07'),
(2, 'cityofatlanta', 'Car', 'BMW', 180, 'day', 2, 33.65, -84.25, 'Available', '2016-11-10 03:14:07'),
(3, 'cityofatlanta', 'Police Car', 'Ford', 100, 'day', 3, 33.85, -84.26, 'Available', '2016-11-05 03:14:07'),
(4, 'cityofatlanta', 'Ambulance', 'Ford', 300, 'day', 1, 33.86, -84.56, 'Available', '2016-11-10 03:14:07'),
(5, 'firedepartment', 'Truck', 'GMC', 80, 'day', 5, 33.864, -84.565, 'Available', '2016-11-10 03:14:07'),
(6, 'cocacola', 'Bus', 'BigBus', 500, 'day', 1, 33.8, -84.6, 'Available', '2016-11-10 03:14:07'),
(7, 'firedepartment', 'Fire engine 1st', 'Ford', 200, 'day', 4, 33.82, -84.15, 'Available', '2016-11-10 03:14:07'),
(8, 'johndeniel', '3 Start Moving', 'Volvo', 150, 'hour', 15, 33.81, -83.15, 'Available', '2016-12-01 08:14:15'),
(9, 'johndeniel', 'storage', 'small', 300, 'week', 13, 33.88, -84.16, 'Available', '2016-12-02 08:12:35'),
(10, 'cityofatlanta', 'gas station', 'Shell gas', 60, 'each', 12, 45.52, -122.71, 'Available', '2016-12-02 08:28:30'),
(11, 'cityofatlanta', 'Rescue Boat 2', 'Boat 2015', 150, 'day', 9, 33.865, -84.256, 'InUse', '2016-12-10 00:00:00'),
(12, 'cityofatlanta', 'Car 2', 'BMW', 180, 'day', 2, 33.65, -84.25, 'InUse', '2016-12-10 00:00:00'),
(13, 'cityofatlanta', 'Police Car 2', 'Ford', 100, 'day', 3, 33.85, -84.26, 'InUse', '2016-12-10 00:00:00'),
(14, 'cityofatlanta', 'Ambulance 2', 'Ford', 300, 'day', 1, 33.86, -84.56, 'InUse', '2016-12-05 00:00:00'),
(15, 'firedepartment', 'Truck 2', 'GMC', 80, 'day', 5, 33.864, -84.565, 'InUse', '2016-12-05 00:00:00'),
(16, 'cocacola', 'Bus 2', 'BigBus', 500, 'day', 1, 33.8, -84.6, 'InUse', '2016-12-05 00:00:00'),
(17, 'firedepartment', 'Fire engine 2nd', 'Ford', 200, 'day', 4, 33.82, -84.15, 'InUse', '2016-12-05 00:00:00'),
(18, 'johndeniel', '3 Start Moving 2', 'Volvo', 150, 'hour', 15, 33.81, -83.15, 'InUse', '2016-12-05 00:00:00'),
(19, 'johndeniel', 'storage 2', 'small', 300, 'week', 13, 33.88, -84.16, 'InUse', '2016-12-10 00:00:00'),
(20, 'cityofatlanta', 'gas station 2', 'Shell gas', 60, 'each', 12, 45.52, -122.71, 'InUse', '2016-12-10 00:00:00'),
(21, 'cityofatlanta', 'Rescue Boat 3', 'Boat 2015', 150, 'day', 9, 33.865, -84.256, 'Available', '2016-11-10 03:14:07'),
(22, 'cityofatlanta', 'Car 3', 'BMW', 180, 'day', 2, 33.65, -84.25, 'Available', '2016-11-10 03:14:07'),
(23, 'cityofatlanta', 'Police Car 3', 'Ford', 100, 'day', 3, 33.85, -84.26, 'Available', '2016-11-05 03:14:07'),
(24, 'cityofatlanta', 'Ambulance 3', 'Ford', 300, 'day', 1, 33.86, -84.56, 'Available', '2016-11-10 03:14:07'),
(25, 'firedepartment', 'Truck 3', 'GMC', 80, 'day', 5, 33.864, -84.565, 'Available', '2016-11-10 03:14:07'),
(26, 'cocacola', 'Bus 3', 'BigBus', 500, 'day', 1, 33.8, -84.6, 'Available', '2016-11-10 03:14:07'),
(27, 'firedepartment', 'Fire engine 3rd', 'Ford', 200, 'day', 4, 33.82, -84.15, 'Available', '2016-11-10 03:14:07'),
(28, 'johndeniel', '3 Start Moving 3', 'Volvo', 150, 'hour', 15, 33.81, -83.15, 'Available', '2016-12-01 08:14:15'),
(29, 'johndeniel', 'storage 3', 'small', 300, 'week', 13, 33.88, -84.16, 'Available', '2016-12-02 08:12:35'),
(30, 'cityofatlanta', 'gas station 3', 'Shell gas', 60, 'each', 12, 45.52, -122.71, 'Available', '2016-12-02 08:28:30');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserName` varchar(50) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserName`, `Name`, `Password`) VALUES
('cityofatlanta', 'City Of Atlanta', 'cityofatlanta123'),
('cocacola', 'CocaCola', 'cocacola123'),
('atlantadmv', 'Atlanta DMV', 'atlantadmv123'),
('johndeniel', 'John Deniel', 'johndeniel123'),
('firedepartment', 'Fire Department', 'fire123'),
('google', 'Google', 'google123'),
('amazon', 'Amazon', 'amazon123'),
('microsoft', 'Microsoft', 'microsoft123'),
('intel', 'Intel', 'intel123'),
('bob', 'Bob', 'bob123'),
('david', 'David', 'david123'),
('jack', 'Jack', 'jack123'),
('ted', 'Ted', 'ted123'),
('seattle', 'Seattle', 'seattle123'),
('boston', 'Boston', 'boston123'),
('portland', 'Portland', 'portland123'),
('sacramento', 'Sacramento', 'sacramento123'),
('traffic', 'Traffic Safety Commission', 'traffic123'),
('police', 'Police Station', 'police123'),
('hospital', 'Hospital', 'hospital123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additionalesfs`
--
ALTER TABLE `additionalesfs`
  ADD UNIQUE KEY `ResourceID` (`ResourceID`,`AdditionalESFID`),
  ADD KEY `AdditionalESFID` (`AdditionalESFID`);

--
-- Indexes for table `capabilities`
--
ALTER TABLE `capabilities`
  ADD UNIQUE KEY `ResourceID` (`ResourceID`,`Capability`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`UserName`);

--
-- Indexes for table `costper`
--
ALTER TABLE `costper`
  ADD PRIMARY KEY (`Unit`);

--
-- Indexes for table `esfs`
--
ALTER TABLE `esfs`
  ADD PRIMARY KEY (`ESFID`);

--
-- Indexes for table `government_agency`
--
ALTER TABLE `government_agency`
  ADD PRIMARY KEY (`UserName`);

--
-- Indexes for table `incident`
--
ALTER TABLE `incident`
  ADD PRIMARY KEY (`IncidentID`),
  ADD KEY `Username` (`Username`);

--
-- Indexes for table `individual`
--
ALTER TABLE `individual`
  ADD PRIMARY KEY (`UserName`);

--
-- Indexes for table `municipality`
--
ALTER TABLE `municipality`
  ADD PRIMARY KEY (`UserName`);

--
-- Indexes for table `repair`
--
ALTER TABLE `repair`
  ADD PRIMARY KEY (`ResourceID`,`StartDate`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD UNIQUE KEY `ResourceID` (`ResourceID`,`IncidentID`),
  ADD KEY `IncidentID` (`IncidentID`);

--
-- Indexes for table `resource`
--
ALTER TABLE `resource`
  ADD PRIMARY KEY (`ResourceID`),
  ADD KEY `UserName` (`UserName`),
  ADD KEY `cost_per` (`cost_per`),
  ADD KEY `Primary_ESFID` (`Primary_ESFID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `esfs`
--
ALTER TABLE `esfs`
  MODIFY `ESFID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `incident`
--
ALTER TABLE `incident`
  MODIFY `IncidentID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `resource`
--
ALTER TABLE `resource`
  MODIFY `ResourceID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
