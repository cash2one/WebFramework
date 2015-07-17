-- MySQL dump 10.9
--
-- Host: tb029    Database: perfdata
-- ------------------------------------------------------
-- Server version	5.5.11-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

--
-- Table structure for table `dataTable`
--

DROP TABLE IF EXISTS `dataTable`;
CREATE TABLE `dataTable` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `indexID` varchar(32) NOT NULL,
  `time` int(11) NOT NULL,
  `keyName` varchar(64) DEFAULT NULL,
  `keyValue` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `time` (`time`)
) ENGINE=InnoDB AUTO_INCREMENT=153645980 DEFAULT CHARSET=utf8;

--
-- Table structure for table `indexTable`
--

DROP TABLE IF EXISTS `indexTable`;
CREATE TABLE `indexTable` (
  `id` varchar(32) NOT NULL,
  `ldap` varchar(1024) DEFAULT NULL,
  `product` varchar(1024) DEFAULT NULL,
  `type` varchar(1024) DEFAULT NULL,
  `cubetype` varchar(1024) DEFAULT NULL,
  `numvars` varchar(4096) DEFAULT NULL,
  `strvars` varchar(4096) DEFAULT NULL,
  `machinevars` varchar(4096) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `machineData`
--

DROP TABLE IF EXISTS `machineData`;
CREATE TABLE `machineData` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `machineID` int(32) NOT NULL,
  `keyName` varchar(64) NOT NULL,
  `time` int(11) NOT NULL,
  `keyValue` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `time` (`time`)
) ENGINE=InnoDB AUTO_INCREMENT=126028975 DEFAULT CHARSET=utf8;

--
-- Table structure for table `machineKeys`
--

DROP TABLE IF EXISTS `machineKeys`;
CREATE TABLE `machineKeys` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `keyName` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

--
-- Table structure for table `machines`
--

DROP TABLE IF EXISTS `machines`;
CREATE TABLE `machines` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `host` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

