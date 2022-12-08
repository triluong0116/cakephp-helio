-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.34-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.5.0.5295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for trippal
CREATE DATABASE IF NOT EXISTS `trippal` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `trippal`;

-- Dumping structure for table trippal.withdraws
CREATE TABLE IF NOT EXISTS `withdraws` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `amount` int(11) NOT NULL DEFAULT '0',
  `bank_account` varchar(255) NOT NULL DEFAULT '0',
  `status` int(2) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- Dumping data for table trippal.withdraws: ~0 rows (approximately)
/*!40000 ALTER TABLE `withdraws` DISABLE KEYS */;
INSERT INTO `withdraws` (`id`, `user_id`, `amount`, `bank_account`, `status`, `created`, `modified`) VALUES
	(4, 3, 123456, '123', 0, '2018-12-07 11:50:24', '2018-12-07 11:50:24'),
	(5, 3, 1234, '1234', 0, '2018-12-07 11:58:27', '2018-12-07 11:58:27'),
	(6, 3, 1234, '123123', 0, '2018-12-07 12:02:02', '2018-12-07 12:02:02'),
	(7, 3, 12312, '123123', 0, '2018-12-07 12:02:56', '2018-12-07 12:02:56'),
	(8, 3, 12312, '123123', 0, '2018-12-07 12:03:27', '2018-12-07 12:03:27'),
	(9, 3, 123, '123', 0, '2018-12-07 12:04:01', '2018-12-07 12:04:01'),
	(10, 3, 1234, '1234', 0, '2018-12-07 14:27:34', '2018-12-07 14:27:34'),
	(11, 3, 1000, '123456', 0, '2018-12-07 15:31:58', '2018-12-07 15:31:58'),
	(12, 3, 1000, '123456', 0, '2018-12-07 15:32:51', '2018-12-07 15:32:51'),
	(13, 3, 1000, '1123456', 0, '2018-12-07 15:33:10', '2018-12-07 15:33:10'),
	(14, 3, 1000, '1123456', 0, '2018-12-07 15:34:16', '2018-12-07 15:34:16'),
	(15, 3, 1000, '11234566', 0, '2018-12-07 15:35:05', '2018-12-07 15:35:05'),
	(16, 3, 1000, '11234566', 0, '2018-12-07 15:36:47', '2018-12-07 15:36:47'),
	(17, 3, 1000, '11234566', 0, '2018-12-07 15:37:41', '2018-12-07 15:37:41'),
	(18, 3, 1000, '11234566', 0, '2018-12-07 15:39:15', '2018-12-07 15:39:15'),
	(19, 3, 2000, '123456', 0, '2018-12-07 15:42:50', '2018-12-07 15:42:50'),
	(20, 3, 10000, '112345', 0, '2018-12-07 15:43:52', '2018-12-07 15:43:52');
/*!40000 ALTER TABLE `withdraws` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
