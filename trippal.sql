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
CREATE DATABASE IF NOT EXISTS `trippal` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin */;
USE `trippal`;

-- Dumping structure for table trippal.blogs
CREATE TABLE IF NOT EXISTS `blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table trippal.blogs: ~0 rows (approximately)
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` (`id`, `user_id`, `title`, `slug`, `caption`, `description`, `thumbnail`, `created`, `modified`) VALUES
	(1, 0, 'Cẩm nang', 'cam-nang', 'Mô tả ngắn', '<p>Nội dung sửa</p>\r\n<p><img src="http://localhost/trippal/files/uploads/1539070032-book.jpg" alt="" width="714" height="476" /></p>', 'files/uploads/1539070581-entertainment.jpg', '2018-10-09 07:27:15', '2018-10-09 07:36:21');
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;

-- Dumping structure for table trippal.bookings
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(128) NOT NULL,
  `amount` int(11) NOT NULL,
  `hotel_code` varchar(50) NOT NULL,
  `room_level` text NOT NULL,
  `num_room` int(12) NOT NULL,
  `people_amount` int(11) NOT NULL,
  `adult_fee` int(11) NOT NULL,
  `children_fee` int(11) NOT NULL,
  `holiday_fee` int(11) NOT NULL,
  `other_fee` int(11) NOT NULL,
  `note` text NOT NULL,
  `car` text NOT NULL,
  `service` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `price` bigint(20) NOT NULL,
  `revenue` int(11) NOT NULL,
  `status` int(2) NOT NULL,
  `other` text NOT NULL,
  `information` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

-- Dumping data for table trippal.bookings: ~10 rows (approximately)
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` (`id`, `code`, `user_id`, `item_id`, `type`, `full_name`, `phone`, `amount`, `hotel_code`, `room_level`, `num_room`, `people_amount`, `adult_fee`, `children_fee`, `holiday_fee`, `other_fee`, `note`, `car`, `service`, `start_date`, `end_date`, `price`, `revenue`, `status`, `other`, `information`, `created`, `modified`) VALUES
	(38, 'M000000038', 2, 14, 4, 'Nam Bùi Hoàng', '0945554266', 2, '', '2 sao', 0, 0, 0, 100000, 0, 0, '', '', '', '2019-01-14', '2019-01-16', 3400000, 100000, 1, '123', '', '2019-01-14 11:53:49', '2019-01-15 16:22:36'),
	(39, 'M000000039', 2, 14, 4, 'Nam Bùi Hoàng', '0945554266', 2, '123', '2 sao', 0, 0, 0, 100000, 0, 0, 'không có', '', '', '2019-01-14', '2019-01-16', 2600000, 100000, 1, '123', '', '2019-01-14 12:14:22', '2019-01-15 16:22:29'),
	(40, 'M000000040', 2, 2, 1, 'Nam Bùi Hoàng', '0945554266', 2, '', '', 0, 0, 0, 0, 0, 0, '', '', '', '2019-01-15', '2019-01-17', 3100000, 100000, 1, 'không có', '', '2019-01-14 15:05:41', '2019-01-15 16:22:17'),
	(41, 'M000000041', 3, 17, 4, 'Nam Bùi Hoàng', '0945554266', 2, '', '', 0, 0, 0, 0, 0, 0, '', 'lamboghini', 'không có', '2019-01-14', '2019-01-16', 44600000, 300000, 1, '123456', '123456', '2019-01-14 15:17:03', '2019-01-15 16:22:03'),
	(42, 'M000000042', 3, 2, 1, 'Nam Bùi Hoàng', '0945554266', 2, '', '', 0, 0, 0, 0, 0, 0, '', 'hãng xe nè', 'không có nè', '2019-01-15', '2019-01-17', 3100000, 100000, 1, 'không có', '', '2019-01-14 16:59:40', '2019-01-15 16:18:59'),
	(43, 'M000000043', 3, 14, 4, 'Nam Bùi Hoàng', '0945554266', 2, 'không có', 'không có', 0, 0, 0, 0, 0, 0, '1', '1', '1', '2019-01-15', '2019-01-17', 2600000, 100000, 1, '12345', '', '2019-01-15 17:15:10', '2019-01-16 11:34:07'),
	(44, 'M000000044', 2, 14, 4, 'Nam Bùi Hoàng', '0945554266', 2, '', '', 0, 0, 0, 0, 0, 0, '', '', '', '2019-01-15', '2019-01-17', 2600000, 0, 1, '123', '', '2019-01-15 17:24:25', '2019-01-16 11:34:00'),
	(45, 'M000000045', 2, 14, 4, 'Nam Bùi Hoàng', '0945554266', 2, '', '', 0, 0, 0, 0, 0, 0, '', '', '', '2019-01-15', '2019-01-17', 2600000, 300000, 1, '123', '', '2019-01-15 17:29:40', '2019-01-16 11:33:53'),
	(46, 'M000000046', 2, 14, 4, 'Nam Bùi Hoàng', '0945554266', 2, '', '', 0, 0, 0, 0, 0, 0, '', '', '', '2019-01-15', '2019-01-17', 2600000, 600000, 1, '123', '', '2019-01-15 17:33:51', '2019-01-16 10:59:17'),
	(47, 'M000000047', 3, 14, 4, 'Nam Bùi Hoàng', '0945554266', 2, '', '', 0, 0, 0, 0, 0, 0, '', '', '', '2019-01-15', '2019-01-16', 1300000, 600000, 1, '123', '123', '2019-01-15 18:13:43', '2019-01-16 10:58:59'),
	(48, 'M000000048', 2, 2, 1, 'Nam Bùi Hoàng', '0945554266', 3, '', '', 0, 0, 0, 0, 0, 0, '', '', '', '2019-01-17', '2019-01-19', 4650000, 900000, 1, 'khong co', '', '2019-01-16 11:35:32', '2019-01-16 11:35:45'),
	(49, 'M000000049', 2, 2, 2, 'Nam Bùi Hoàng', '0945554266', 3, '', '', 0, 0, 0, 0, 0, 0, '', '', '', '2019-01-16', '2019-01-18', 2520, 1350, 1, '213', '', '2019-01-16 11:49:10', '2019-01-16 11:49:19');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;

-- Dumping structure for table trippal.bookings_users
CREATE TABLE IF NOT EXISTS `bookings_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `revenue` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table trippal.bookings_users: ~0 rows (approximately)
/*!40000 ALTER TABLE `bookings_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `bookings_users` ENABLE KEYS */;

-- Dumping structure for table trippal.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;

-- Dumping data for table trippal.categories: ~65 rows (approximately)
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`id`, `parent_id`, `name`, `slug`, `created`, `modified`) VALUES
	(1, 0, 'Tiện ích Khách sạn', 'tien-ich-khach-san', '2018-10-04 04:19:39', '2018-10-04 04:19:39'),
	(2, 1, 'Điều hòa', 'dieu-hoa', '2018-10-04 04:20:18', '2018-10-04 04:20:18'),
	(3, 1, 'Ấm đun nước điện', 'am-dun-nuoc-dien', '2018-10-10 03:59:46', '2018-10-10 03:59:46'),
	(4, 1, 'Bàn làm việc', 'ban-lam-viec', '2018-10-10 04:00:03', '2018-10-10 04:00:03'),
	(5, 1, ' Chai nước', 'chai-nuoc', '2018-10-10 04:00:16', '2018-10-10 04:00:16'),
	(6, 1, 'Dầu gội đầu', 'dau-goi-dau', '2018-10-10 04:00:31', '2018-10-10 04:00:31'),
	(7, 1, 'Dịch vụ báo thức', 'dich-vu-bao-thuc', '2018-10-10 04:00:44', '2018-10-10 04:00:44'),
	(8, 1, 'Đồ vệ sinh cá nhân miễn phí', 'do-ve-sinh-ca-nhan-mien-phi', '2018-10-10 04:01:00', '2018-10-10 04:01:00'),
	(9, 1, 'Giấy vệ sinh', 'giay-ve-sinh', '2018-10-10 04:01:31', '2018-10-10 04:01:31'),
	(10, 1, 'Gối thường', 'goi-thuong', '2018-10-10 04:01:46', '2018-10-10 04:05:09'),
	(11, 1, ' Két an toàn', 'ket-an-toan', '2018-10-10 04:01:57', '2018-10-10 04:01:57'),
	(12, 1, 'Không gây dị ứng', 'khong-gay-di-ung', '2018-10-10 04:02:06', '2018-10-10 04:02:06'),
	(13, 1, 'Máy pha trà/cà phê', 'may-pha-traca-phe', '2018-10-10 04:02:19', '2018-10-10 04:02:19'),
	(14, 1, 'Minibar', 'minibar', '2018-10-10 04:02:34', '2018-10-10 04:02:34'),
	(15, 1, 'Nhìn ra địa danh nổi tiếng', 'nhin-ra-dia-danh-noi-tieng', '2018-10-10 04:02:47', '2018-10-10 04:02:47'),
	(16, 1, 'Ổ điện gần giường', 'o-dien-gan-giuong', '2018-10-10 04:02:57', '2018-10-10 04:02:57'),
	(17, 1, 'Phòng tắm phụ', 'phong-tam-phu', '2018-10-10 04:03:07', '2018-10-10 04:03:07'),
	(18, 1, 'Sàn lát gạch/đá cẩm thạch', 'san-lat-gachda-cam-thach', '2018-10-10 04:03:18', '2018-10-10 04:03:18'),
	(19, 1, 'Tầm nhìn ra khung cảnh', 'tam-nhin-ra-khung-canh', '2018-10-10 04:03:26', '2018-10-10 04:03:26'),
	(20, 1, 'Tiện nghi ủi', 'tien-nghi-ui', '2018-10-10 04:03:36', '2018-10-10 04:03:36'),
	(21, 1, 'Tủ lạnh', 'tu-lanh', '2018-10-10 04:03:47', '2018-10-10 04:03:47'),
	(22, 1, 'TV màn hình phẳng', 'tv-man-hinh-phang', '2018-10-10 04:03:58', '2018-10-10 04:03:58'),
	(23, 1, 'Bàn ăn', 'ban-an', '2018-10-10 04:04:09', '2018-10-10 04:04:09'),
	(24, 1, 'Bàn ủi', 'ban-ui', '2018-10-10 04:04:20', '2018-10-10 04:04:20'),
	(25, 1, 'Chậu rửa vệ sinh (bidet)', 'chau-rua-ve-sinh-bidet', '2018-10-10 04:04:33', '2018-10-10 04:04:33'),
	(26, 1, 'Dầu xả', 'dau-xa', '2018-10-10 04:05:43', '2018-10-10 04:05:43'),
	(27, 1, 'Điện thoại', 'dien-thoai', '2018-10-10 04:05:50', '2018-10-10 04:05:50'),
	(28, 1, 'Giá phơi quần áo', 'gia-phoi-quan-ao', '2018-10-10 04:05:58', '2018-10-10 04:05:58'),
	(29, 1, ' Giường xếp', 'giuong-xep', '2018-10-10 04:06:06', '2018-10-10 04:06:06'),
	(30, 1, 'Hệ thống cách âm', 'he-thong-cach-am', '2018-10-10 04:06:15', '2018-10-10 04:06:15'),
	(31, 1, 'Két an toàn cỡ laptop', 'ket-an-toan-co-laptop', '2018-10-10 04:06:28', '2018-10-10 04:06:28'),
	(32, 1, 'Khu vực phòng ăn', 'khu-vuc-phong-an', '2018-10-10 04:06:37', '2018-10-10 04:06:37'),
	(33, 1, 'Máy sấy quần áo', 'may-say-quan-ao', '2018-10-10 04:06:46', '2018-10-10 04:06:46'),
	(34, 1, 'Mũ tắm', 'mu-tam', '2018-10-10 04:06:56', '2018-10-10 04:06:56'),
	(35, 1, ' Nhìn ra sông', 'nhin-ra-song', '2018-10-10 04:07:03', '2018-10-10 04:07:03'),
	(36, 1, ' Ổ khóa mở bằng thẻ', 'o-khoa-mo-bang-the', '2018-10-10 04:07:12', '2018-10-10 04:07:12'),
	(37, 1, ' Phòng thay quần áo', 'phong-thay-quan-ao', '2018-10-10 04:07:20', '2018-10-10 04:07:20'),
	(38, 1, ' Sản phẩm lau rửa', 'san-pham-lau-rua', '2018-10-10 04:07:29', '2018-10-10 04:07:29'),
	(39, 1, ' Thiết bị báo cháy', 'thiet-bi-bao-chay', '2018-10-10 04:07:38', '2018-10-10 04:07:38'),
	(40, 1, ' Truyền hình cáp', 'truyen-hinh-cap', '2018-10-10 04:07:48', '2018-10-10 04:07:48'),
	(41, 1, 'Tủ/Phòng để quần áo', 'tuphong-de-quan-ao', '2018-10-10 04:07:59', '2018-10-10 04:07:59'),
	(42, 1, 'Vòi sen', 'voi-sen', '2018-10-10 04:08:12', '2018-10-10 04:08:12'),
	(43, 1, 'Bàn chải', 'ban-chai', '2018-10-10 04:08:21', '2018-10-10 04:08:21'),
	(44, 1, 'Bồn tắm hoặc Vòi sen', 'bon-tam-hoac-voi-sen', '2018-10-10 04:08:31', '2018-10-10 04:08:31'),
	(45, 1, 'Có thang máy', 'co-thang-may', '2018-10-10 04:08:44', '2018-10-10 04:08:44'),
	(46, 1, 'Dép', 'dep', '2018-10-10 04:08:52', '2018-10-10 04:08:52'),
	(47, 1, 'Điều hòa không khí', 'dieu-hoa-khong-khi', '2018-10-10 04:09:01', '2018-10-10 04:09:01'),
	(48, 1, 'Giá treo quần áo', 'gia-treo-quan-ao', '2018-10-10 04:09:10', '2018-10-10 04:09:10'),
	(49, 1, ' Gối lông vũ', 'goi-long-vu', '2018-10-10 04:09:20', '2018-10-10 04:09:20'),
	(50, 1, 'Hướng nhìn ra đường phố yên ắng', 'huong-nhin-ra-duong-pho-yen-ang', '2018-10-10 04:09:30', '2018-10-10 04:09:30'),
	(51, 1, 'Khăn tắm', 'khan-tam', '2018-10-10 04:09:38', '2018-10-10 04:09:38'),
	(52, 1, 'Lối vào riêng', 'loi-vao-rieng', '2018-10-10 04:09:45', '2018-10-10 04:09:45'),
	(53, 1, 'Máy sấy tóc', 'may-say-toc', '2018-10-10 04:09:54', '2018-10-10 04:09:54'),
	(54, 1, 'Nhà vệ sinh', 'nha-ve-sinh', '2018-10-10 04:10:02', '2018-10-10 04:10:02'),
	(55, 1, 'Nhìn ra thành phố', 'nhin-ra-thanh-pho', '2018-10-10 04:10:09', '2018-10-10 04:10:09'),
	(56, 1, 'Phòng tắm', 'phong-tam', '2018-10-10 04:10:23', '2018-10-10 04:10:23'),
	(57, 1, 'Ra trải giường', 'ra-trai-giuong', '2018-10-10 04:10:48', '2018-10-10 04:10:48'),
	(58, 1, 'Sàn trải thảm', 'san-trai-tham', '2018-10-10 04:10:56', '2018-10-10 04:10:56'),
	(59, 1, 'Thùng rác', 'thung-rac', '2018-10-10 04:11:05', '2018-10-10 04:11:05'),
	(60, 1, ' Truyền hình vệ tinh', 'truyen-hinh-ve-tinh', '2018-10-10 04:11:12', '2018-10-10 04:11:12'),
	(61, 1, 'TV', 'tv', '2018-10-10 04:11:20', '2018-10-10 04:11:20'),
	(62, 1, 'Xà phòng tắm', 'xa-phong-tam', '2018-10-10 04:11:30', '2018-10-10 04:11:30'),
	(63, 0, 'Danh mục Cẩm nang', 'danh-muc-cam-nang', '2018-11-22 09:44:07', '2018-11-22 09:44:07'),
	(64, 63, 'Review về các chuyến đi', 'review-ve-cac-chuyen-di', '2018-11-22 09:45:08', '2018-11-22 09:46:10'),
	(65, 63, 'Review về Trippal', 'review-ve-trippal', '2018-11-22 09:45:45', '2018-11-22 09:46:23');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Dumping structure for table trippal.combos
CREATE TABLE IF NOT EXISTS `combos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8 NOT NULL,
  `caption` text CHARACTER SET utf8 NOT NULL,
  `description` text COLLATE utf8mb4_bin NOT NULL,
  `promote` float NOT NULL,
  `addition_fee` int(11) NOT NULL,
  `departure_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `days` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `rating` float NOT NULL,
  `thumbnail` varchar(255) CHARACTER SET utf8 NOT NULL,
  `media` text CHARACTER SET utf8 NOT NULL,
  `fb_content` text COLLATE utf8mb4_bin NOT NULL,
  `icon_list` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table trippal.combos: ~8 rows (approximately)
/*!40000 ALTER TABLE `combos` DISABLE KEYS */;
INSERT INTO `combos` (`id`, `name`, `slug`, `caption`, `description`, `promote`, `addition_fee`, `departure_id`, `destination_id`, `days`, `rating`, `thumbnail`, `media`, `fb_content`, `icon_list`, `created`, `modified`) VALUES
	(1, 'Combo đi Đà Nẵng 4 ngày', 'combo-di-da-nang-4-ngay', '["Khách sạn view biển"]', '<p>sdfsdfsdfsdfsdfsdfs</p>', 15, 0, 2, 3, '4 ngày 3 đêm', 5, 'files/uploads/1542790115-room 11.jpg', '["files/uploads/1543409926-room 1.jpg","files/uploads/1543409926-room 2.jpg","files/uploads/1543409926-room 3.jpg","files/uploads/1543409926-room 4.jpg","files/uploads/1543409926-room 8.jpg","files/uploads/1543409927-room 9.jpg","files/uploads/1543409927-room 10.jpg","files/uploads/1543409928-room 11.jpg","files/uploads/1543409928-room 13.jpg"]', '', '', '2018-11-21 08:48:36', '2018-11-28 19:58:50'),
	(2, '1 Ocean 1 Aladinle', '1-ocean-1-aladinle', '{"2":{"icon":"fas fa-bus","content":"1 đêm khách sạn ocean "},"3":{"icon":"fas fa-plane","content":"1 đêm khách sạn adaline"},"4":{"icon":"fas fa-motorcycle","content":"Vé máy bay"},"5":{"icon":"far fa-clock","content":"Vé banahill"}}', '<p>- Đưa đón sân bay </p>\r\n<p>- Ắn sáng </p>\r\n<p>Tắm biển</p>', 10, 0, 6, 6, '3 ngày 2 đêm', 4, 'files/uploads/1544175064-132679690.jpg', '', '', '["fas fa-bus","fas fa-plane","far fa-clock"]', '2018-12-07 16:31:04', '2018-12-21 18:43:04'),
	(5, 'tour đi hang Navi', 'tour-di-hang-navi', '["asdasd"]', '<p>asd</p>', 1, 0, 5, 6, 'vài ngày gì đó', 5, 'files/uploads/1544783475-project_img6.jpg', '["files/uploads/1544783461-SVV logo.png"]', '', '', '2018-12-14 17:31:16', '2018-12-14 17:31:16'),
	(7, 'combo test', 'combo-test', '["combo rẻ cmn nhất quả đất"]', '<p>abcd1234</p>', 10, 0, 4, 4, 'vài ngày gì đó', 5, 'files/uploads/1545295817-8.jpg', '["files/uploads/1545295782-1.jpg","files/uploads/1545295782-2.jpg","files/uploads/1545295782-3.jpg","files/uploads/1545295782-4.jpg","files/uploads/1545295782-5.jpg","files/uploads/1545295787-6.jpg","files/uploads/1545295790-7.jpg"]', '', '', '2018-12-20 15:50:18', '2018-12-20 15:50:18'),
	(8, 'test', 'test', '["short gì gì đó"]', '<p>123</p>', 10, 0, 4, 4, 'vài ngày gì đó', 5, 'files/uploads/1545295964-5.jpg', '["files/uploads/1545295954-6.jpg"]', '', '', '2018-12-20 15:52:44', '2018-12-20 15:52:44'),
	(11, 'test icon', 'test-icon', '[{"icon":"fas fa-check","content":"fghjklfgh"}]', '<p>bbhjj</p>', 10, 100000, 4, 4, 'vài ngày gì đó', 5, 'files/uploads/1545393176-4.jpg', '', '', '["fas fa-bus","fas fa-plane"]', '2018-12-21 18:52:56', '2018-12-21 18:52:56'),
	(12, 'tour đi hang Navi', 'tour-di-hang-navi-1', '[{"icon":"fas fa-glass-martini","content":"11234"}]', '<p>asdasd</p>', 10, 100000, 4, 5, '3', 5, 'files/uploads/1546398121-48417226_1958541020849323_9173472707377889280_n.jpg', '', '11231214123', '["fas fa-bus"]', '2019-01-02 10:02:02', '2019-01-02 10:02:02'),
	(13, 'combo test ngày', 'combo-test-ngay', '[{"icon":"fas fa-plane","content":"máy bay"}]', '<p>combo để test</p>', 10, 100000, 4, 6, '5 ngày 4 đêm', 5, 'files/uploads/1547278851-logo.png', '', '123', '["fas fa-bus"]', '2019-01-12 14:40:52', '2019-01-12 14:40:52');
/*!40000 ALTER TABLE `combos` ENABLE KEYS */;

-- Dumping structure for table trippal.combos_hotels
CREATE TABLE IF NOT EXISTS `combos_hotels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `combo_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `days_attended` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table trippal.combos_hotels: ~9 rows (approximately)
/*!40000 ALTER TABLE `combos_hotels` DISABLE KEYS */;
INSERT INTO `combos_hotels` (`id`, `combo_id`, `hotel_id`, `days_attended`) VALUES
	(1, 1, 3, 3),
	(2, 1, 6, 1),
	(3, 2, 14, 1),
	(4, 2, 16, 1),
	(8, 5, 1, 3),
	(10, 7, 17, 5),
	(11, 8, 2, 3),
	(14, 11, 3, 5),
	(15, 12, 3, 3),
	(16, 13, 14, 2),
	(17, 13, 16, 3);
/*!40000 ALTER TABLE `combos_hotels` ENABLE KEYS */;

-- Dumping structure for table trippal.combos_rooms
CREATE TABLE IF NOT EXISTS `combos_rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `combo_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table trippal.combos_rooms: ~3 rows (approximately)
/*!40000 ALTER TABLE `combos_rooms` DISABLE KEYS */;
INSERT INTO `combos_rooms` (`id`, `combo_id`, `room_id`) VALUES
	(1, 1, 1),
	(2, 1, 3),
	(4, 2, 4);
/*!40000 ALTER TABLE `combos_rooms` ENABLE KEYS */;

-- Dumping structure for table trippal.comments
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `object_type` int(6) NOT NULL,
  `object_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table trippal.comments: ~0 rows (approximately)
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;

-- Dumping structure for table trippal.configs
CREATE TABLE IF NOT EXISTS `configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `value` text COLLATE utf8mb4_bin NOT NULL,
  `status` int(2) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table trippal.configs: ~6 rows (approximately)
/*!40000 ALTER TABLE `configs` DISABLE KEYS */;
INSERT INTO `configs` (`id`, `type`, `value`, `status`, `created`, `modified`) VALUES
	(1, 'chinh-sach-cong-tac-vien', '<p>đ&acirc;sdasdasdasdasdasdasdasdas</p>', 0, '0000-00-00 00:00:00', '2018-11-13 10:48:36'),
	(2, 'huong-dan-thanh-toan', '<p>Qu&yacute; kh&aacute;ch Vui l&ograve;ng thanh to&aacute;n trực tiếp tại c&ocirc;ng ty hoặc chuyển khoản v&agrave;o c&aacute;c t&agrave;i khoản sau, mọi giao dịch chuyển khoản cho Hướng dẫn vi&ecirc;n Online hoặc b&ecirc;n thứ ba Mustgo.vn kh&ocirc;ng t&iacute;nh l&agrave; giao dịch đặt ph&ograve;ng th&agrave;nh c&ocirc;ng v&agrave; kh&ocirc;ng chịu tr&aacute;ch nhiệm ho&agrave;n trả.</p>\r\n<p><strong>Thanh to&aacute;n trực tiếp bằng tiền mặt chuyển khoản</strong></p>\r\n<p>P.402 Tầng 04 T&ograve;a nh&agrave; Lake side số 71 phố Ch&ugrave;a L&aacute;ng, Phường L&aacute;ng Thượng, Quận Đồng Đa, H&agrave; Nội.<br />Thời gian l&agrave;m việc: 9:00 &ndash; 18:00 c&aacute;c ng&agrave;y từ thứ 2 đến thứ 6 v&agrave; 9:00 &ndash; 12:00 Thứ 7</p>\r\n<p><strong>Thanh to&aacute;n chuyển khoản<br /><br /></strong>Nội dung thanh to&aacute;n:<br />&ldquo;T&ecirc;n Kh&aacute;ch H&agrave;ng&rdquo; &ndash; &ldquo;M&atilde; Booking&rdquo;</p>\r\n<p><strong>T&Agrave;I KHOẢN C&Aacute; NH&Acirc;N</strong>:</p>\r\n<p><strong>Ng&acirc;n h&agrave;ng Vietcombank H&agrave; Nội</strong></p>\r\n<p>T&ecirc;n TK: Đinh Thị Thu Thảo<br />Số TK: 0011004077139<br />Chi nh&aacute;nh sở giao dịch<br /><br /><strong>Ng&acirc;n h&agrave;ng VPbank H&agrave; Nội</strong></p>\r\n<p>T&ecirc;n TK: Đinh Thị Thu Thảo</p>\r\n<p>Số TK: 149422358</p>\r\n<p>Chi nh&aacute;nh Thăng Long <br /><br /><strong>Ng&acirc;n h&agrave;ng BIDV H&agrave; Nội</strong><br />T&ecirc;n TK: Đinh Thị Thu Thảo<br />Số TK: 26810000258712</p>\r\n<p>Chi nh&aacute;nh Th&aacute;i H&agrave;<br /><br /><strong>Ng&acirc;n h&agrave;ng Techcombank H&agrave; Nội<br /></strong>T&ecirc;n TK: Đinh Thị Thu Thảo<br />Số TK: 19032573610016</p>\r\n<p>Chi nh&aacute;nh Hai B&agrave; Trưng</p>\r\n<p><br /><strong>Ng&acirc;n h&agrave;ng Sacombank H&agrave; Nội</strong><br />T&ecirc;n TK: Đinh Thị Thu Thảo</p>\r\n<p>Số TK: ‪020032383190<br />Chi nh&aacute;nh Thủ Đ&ocirc;<br /><br /><strong>T&Agrave;I KHOẢN DOANH NGHIỆP</strong></p>\r\n<p>Chủ t&agrave;i khoản: C&ocirc;ng ty Cổ Phần Du Lịch Li&ecirc;n Minh Việt Nam</p>\r\n<p>Số TK: 149330488</p>\r\n<p>Mở tại Ng&acirc;n h&agrave;ng Việt Nam Thịnh Vượng chi nh&aacute;nh Nam H&agrave; Nội.</p>\r\n<p>&nbsp;</p>', 0, '0000-00-00 00:00:00', '2019-01-03 19:45:44'),
	(3, 'chinh-sach-rieng-tu-bao-mat', '<p>Đ&acirc;y l&agrave; c&aacute;ch m&agrave; ch&uacute;ng t&ocirc;i sử dụng c&aacute;c cookie tr&ecirc;n trang web của ch&uacute;ng t&ocirc;i v&agrave; cũng l&agrave; c&aacute;ch m&agrave; ch&uacute;ng t&ocirc;i xử l&yacute;, bảo vệ những th&ocirc;ng tin c&aacute; nh&acirc;n của bạn:<br /><br />C&Aacute;C COOKIES<br />Cookie l&agrave; một file th&ocirc;ng tin nhỏ y&ecirc;u cầu được lưu trữ tr&ecirc;n ổ cứng m&aacute;y t&iacute;nh của bạn. Một khi bạn đồng &yacute;, file n&agrave;y sẽ được lưu trữ v&agrave; gi&uacute;p ph&acirc;n t&iacute;ch lưu lượng truy cập trang web hoặc cho bạn biết khi bạn truy cập một trang web cụ thể.<br /><br />C&aacute;c cookie cho ph&eacute;p c&aacute;c ứng dụng trang web phản hồi th&ocirc;ng tin đến bạn tr&ecirc;n phương diện c&aacute; nh&acirc;n. Ứng dụng trang web c&oacute; thể điều chỉnh c&aacute;c hoạt động nhằm đ&aacute;p ứng c&aacute;c nhu cầu, th&iacute;ch v&agrave; kh&ocirc;ng th&iacute;ch của bạn bằng c&aacute;ch thu thập v&agrave; lưu th&ocirc;ng tin về sở th&iacute;ch (preference) của bạn.<br /><br />Ch&uacute;ng t&ocirc;i sử dụng c&aacute;c cookie lưu lượng nhằm x&aacute;c định c&aacute;c trang n&agrave;o hiện đang được sử dụng. Điều n&agrave;y gi&uacute;p ch&uacute;ng t&ocirc;i ph&acirc;n t&iacute;ch dữ liệu về lưu lượng trang web v&agrave; n&acirc;ng cấp website của ch&uacute;ng t&ocirc;i nhằm đ&aacute;p ứng nhu cầu của kh&aacute;ch h&agrave;ng. Ch&uacute;ng t&ocirc;i chỉ sử dụng th&ocirc;ng tin n&agrave;y cho mục đ&iacute;ch ph&acirc;n t&iacute;ch th&ocirc;ng k&ecirc; v&agrave; sau đ&oacute; sẽ x&oacute;a dữ liệu khỏi hệ thống. B&ecirc;n cạnh đ&oacute;, c&aacute;c cookies c&ograve;n gi&uacute;p ch&uacute;ng t&ocirc;i mang lại cho bạn một website tốt hơn bằng c&aacute;ch tạo điều kiện cho ch&uacute;ng t&ocirc;i theo d&otilde;i c&aacute;c trang m&agrave; bạn nhận thấy hữu &iacute;ch hoặc kh&ocirc;ng hữu &iacute;ch. Một cookie kh&ocirc;ng thể n&agrave;o gi&uacute;p ch&uacute;ng t&ocirc;i truy cập v&agrave;o m&aacute;y t&iacute;nh của bạn hoặc bất kỳ th&ocirc;ng tin n&agrave;o về bạn ngo&agrave;i c&aacute;c dữ liệu m&agrave; bạn chọn để chia sẻ với ch&uacute;ng t&ocirc;i.<br /><br />Bạn c&oacute; thể lựa chọn chấp nhận hoặc từ chối c&aacute;c cookie. Hầu hết c&aacute;c tr&igrave;nh duyệt web tự động chấp nhận c&aacute;c cookie, nhưng bạn c&oacute; thể thường xuy&ecirc;n thay đổi setting tr&igrave;nh duyệt của bạn để từ chối c&aacute;c cookie nếu bạn muốn. Điều n&agrave;y c&oacute; thể g&acirc;y cản trở đến việc bạn nhận được lợi &iacute;ch tối đa từ website.<br /><br /><br /><br />CH&Iacute;NH S&Aacute;CH VỀ QUYỀN RI&Ecirc;NG TƯ<br />Ch&uacute;ng t&ocirc;i thu thập những g&igrave;<br /><br />Ch&uacute;ng t&ocirc;i c&oacute; thể thu thập những th&ocirc;ng tin sau đ&acirc;y:<br /><br />T&ecirc;n, giới t&iacute;nh, ng&agrave;y sinh, địa chỉ, email, số điện thoại, sở th&iacute;ch của bạn vv&hellip;<br /><br />Ch&uacute;ng t&ocirc;i l&agrave;m g&igrave; với những th&ocirc;ng tin m&agrave; ch&uacute;ng t&ocirc;i thu thập<br /><br />Ch&uacute;ng t&ocirc;i y&ecirc;u cầu những th&ocirc;ng tin n&agrave;y để nắm bắt nhu cầu của bạn v&agrave; mang lại cho bạn một dịch vụ tốt hơn, cụ thể l&agrave; v&igrave; những l&yacute; do dưới đ&acirc;y:<br /><br />&bull; Nhằm cung cấp cho c&aacute;c Nh&agrave; cung cấp dịch vụ li&ecirc;n quan ho&agrave;n tất việc đăng k&yacute; v&agrave; đặt tour của bạn.<br />&bull; Nhằm n&acirc;ng cấp c&aacute;c sản phẩm v&agrave; dịch vụ của ch&uacute;ng t&ocirc;i.<br />&bull; Gửi đến bạn điểm thưởng, c&aacute;c chương tr&igrave;nh khuyến mại theo m&ugrave;a hay những th&ocirc;ng tin đặc biệt khi c&oacute; vv&hellip;Bạn được cho l&agrave; đồng &yacute; với những th&ocirc;ng tin điện tử khi bạn thực hiện một đăng k&yacute; hay y&ecirc;u cầu. Nếu bạn kh&ocirc;ng muốn nhận bất kỳ một email về th&ocirc;ng tin khuyến mại n&agrave;o nữa từ ch&uacute;ng t&ocirc;i, bạn c&oacute; thể thay đổi mục preference của bạn tr&ecirc;n website của ch&uacute;ng t&ocirc;i, sử dụng c&ocirc;ng cụ &lsquo;unsubscribe&rsquo; của ch&uacute;ng t&ocirc;i hoặc li&ecirc;n hệ với ch&uacute;ng t&ocirc;i.<br />&bull; T&ugrave;y chỉnh website theo sở th&iacute;ch của bạn.<br />&bull; Đ&ocirc;i l&uacute;c c&oacute; thể chia sẻ v&agrave; tiết lộ th&ocirc;ng tin c&aacute; nh&acirc;n của bạn cho cơ quan chức năng hoặc cơ quan điều tra nếu được y&ecirc;u cầu theo luật định, hoặc cần thiết cho việc ngăn chặn, ph&aacute;t hiện c&aacute;c h&agrave;nh vi phạm tội v&agrave; truy tố trước ph&aacute;p luật để bảo vệ c&aacute;c quyền của kh&aacute;ch h&agrave;ng.<br />Ch&uacute;ng t&ocirc;i đ&aacute;nh gi&aacute; cao sự tin tưởng của bạn trong việc lựa chọn travel.com.vn để đăng k&yacute; trực tuyến. Do đ&oacute;, ch&uacute;ng t&ocirc;i sẽ lu&ocirc;n bảo đảm v&agrave; bảo vệ quyền ri&ecirc;ng tư v&agrave; t&iacute;nh bảo mật đối với những th&ocirc;ng tin c&aacute; nh&acirc;n của bạn với tinh thần tr&aacute;ch nhiệm cao nhất.<br /><br />NHỮNG TH&Ocirc;NG TIN C&Aacute; NH&Acirc;N<br />Ch&uacute;ng t&ocirc;i sẽ kh&ocirc;ng b&aacute;n, ph&acirc;n phối hoặc cho thu&ecirc; những th&ocirc;ng tin c&aacute; nh&acirc;n của bạn đến bất kỳ b&ecirc;n thứ ba n&agrave;o kh&aacute;c trừ phi bạn cho ph&eacute;p hoặc được y&ecirc;u cầu theo luật định.<br /><br />Nếu bạn cho rằng th&ocirc;ng tin về bạn m&agrave; ch&uacute;ng t&ocirc;i đang nắm giữ c&oacute; phần n&agrave;o kh&ocirc;ng ch&iacute;nh x&aacute;c hoặc chưa đầy đủ, xin vui l&ograve;ng gửi th&ocirc;ng b&aacute;o bằng văn bản hoặc email cho ch&uacute;ng t&ocirc;i c&agrave;ng sớm c&agrave;ng tốt theo địa chỉ ở tr&ecirc;n. Ch&uacute;ng t&ocirc;i sẽ hiệu chỉnh ngay mọi th&ocirc;ng tin chưa ch&iacute;nh x&aacute;c.<br /><br />BẢO MẬT TH&Ocirc;NG TIN<br />Ch&uacute;ng t&ocirc;i cam kết rằng những th&ocirc;ng tin của bạn được bảo mật. Để bảo vệ dữ liệu c&aacute; nh&acirc;n m&agrave; bạn đ&atilde; cung cấp cho ch&uacute;ng t&ocirc;i, ch&uacute;ng t&ocirc;i đ&atilde; thực hiện v&agrave; sử dụng c&aacute;c hệ thống v&agrave; quy tr&igrave;nh quản l&yacute; ph&ugrave; hợp.<br /><br />Hơn thế nữa, ch&uacute;ng t&ocirc;i cũng đ&atilde; thực hiện v&agrave; sử dụng c&aacute;c quy tr&igrave;nh bảo mật v&agrave; những hạn chế vật chất v&agrave; kỹ thuật đối với việc truy cập v&agrave; sử dụng những th&ocirc;ng tin c&aacute; nh&acirc;n. Chỉ những nh&acirc;n vi&ecirc;n được ủy nhiệm mới được ph&eacute;p truy cập những th&ocirc;ng tin c&aacute; nh&acirc;n để thực hiện nhiệm vụ của họ đối với c&aacute;c dịch vụ của ch&uacute;ng t&ocirc;i.<br /><br /><br /><br />CẬP NHẬT CH&Iacute;NH S&Aacute;CH VỀ QUYỀN RI&Ecirc;NG TƯ &amp; C&Aacute;C COOKIE<br />Ch&uacute;ng t&ocirc;i c&oacute; quyền thay đổi c&aacute;ch m&agrave; ch&uacute;ng t&ocirc;i sử dụng c&aacute;c cookie v&agrave; c&aacute;ch m&agrave; ch&uacute;ng t&ocirc;i thu thập, truyền tải v&agrave; xử l&yacute; những th&ocirc;ng tin c&aacute; nh&acirc;n của người sử dụng m&agrave; ch&uacute;ng t&ocirc;i cho l&agrave; cần thiết. Do đ&oacute;, ch&iacute;nh s&aacute;ch n&agrave;y c&oacute; thể được sửa đổi để phản &aacute;nh những thay đổi mới nhất. C&oacute; thể bạn kh&ocirc;ng cho l&agrave; n&oacute; giống nhau v&agrave; bạn đồng &yacute; kiểm lại ch&iacute;nh s&aacute;ch n&agrave;y mỗi lần bạn truy cập v&agrave;o trang web để xem những thay đổi. Việc bạn sử dụng thường xuy&ecirc;n website n&agrave;y chứng tỏ rằng bạn chấp thuận c&aacute;c điều khoản của Ch&iacute;nh s&aacute;ch về Quyền ri&ecirc;ng tư hoặc mọi sửa đổi của ch&uacute;ng t&ocirc;i.&nbsp;<br /><br /><br /></p>', 0, '0000-00-00 00:00:00', '2019-01-03 19:46:35'),
	(4, 'dieu-khoan-su-dung', '<p><strong>Ch&iacute;nh s&aacute;ch v&agrave; điều khoản chung</strong></p>\r\n<p>Trang web n&agrave;y được điều h&agrave;nh bởi VimiTravel. Xin vui l&ograve;ng đọc kỹ c&aacute;c Điều kiện &amp; Điều khoản trước khi sử dụng hoặc đăng k&yacute; tr&ecirc;n trang web n&agrave;y. Bạn phải ho&agrave;n to&agrave;n đồng &yacute; với c&aacute;c điều kiện v&agrave; điều khoản n&agrave;y nếu muốn sử dụng trang web. Nếu bạn kh&ocirc;ng đồng &yacute; với bất kỳ phần n&agrave;o trong c&aacute;c điều kiện v&agrave; điều khoản n&agrave;y, bạn sẽ kh&ocirc;ng thể sử dụng trang web n&agrave;y dưới bất kỳ h&igrave;nh thức n&agrave;o.</p>\r\n<p>Lưu &yacute; rằng trang web n&agrave;y được x&acirc;y dựng nhằm phục vụ truy cập tr&ecirc;n phạm vi to&agrave;n cầu đối với người sử dụng. Những th&ocirc;ng tin v&agrave; mức gi&aacute; tr&ecirc;n trang web n&agrave;y được &aacute;p dụng cho người sử dụng tr&ecirc;n phạm vi to&agrave;n cầu. Ch&uacute;ng t&ocirc;i c&oacute; quyền từ chối truy cập v&agrave;o trang web n&agrave;y bất cứ l&uacute;c n&agrave;o m&agrave; kh&ocirc;ng cần phải th&ocirc;ng b&aacute;o trước.</p>\r\n<p><strong>Luật điều chỉnh</strong></p>\r\n<p>Việc truy cập v&agrave;o trang web n&agrave;y c&oacute; điều kiện theo sự đồng &yacute; của bạn rằng to&agrave;n bộ những th&ocirc;ng tin tr&ecirc;n trang web v&agrave; to&agrave;n bộ c&aacute;c vấn đề ph&aacute;t sinh giữa bạn v&agrave; ch&uacute;ng t&ocirc;i sẽ được điều chỉnh bởi ph&aacute;p luật Việt Nam v&agrave; rằng mọi tranh chấp ph&aacute;t sinh giữa bạn v&agrave; ch&uacute;ng t&ocirc;i sẽ căn cứ v&agrave;o quyền hạn x&eacute;t xử của c&aacute;c t&ograve;a &aacute;n Việt Nam.</p>\r\n<p><strong>Nghĩa vụ của người sử dụng trang Web</strong></p>\r\n<p>Khi sử dụng trang web n&agrave;y, xin vui l&ograve;ng đồng &yacute; rằng:<br />1. Bạn chấp nhận tr&aacute;ch nhiệm về t&agrave;i ch&iacute;nh đối với to&agrave;n bộ c&aacute;c giao dịch được thực hiện theo t&ecirc;n v&agrave; t&agrave;i khoản của bạn.<br />2. Để c&oacute; năng lực ph&aacute;p l&yacute;, bạn phải từ 18 tuổi trở l&ecirc;n.<br />3. Bạn bảo đảm rằng mọi th&ocirc;ng tin m&agrave; bạn cung cấp về ch&iacute;nh bạn v&agrave; về bất cứ ai kh&aacute;c l&agrave; ho&agrave;n to&agrave;n ch&iacute;nh x&aacute;c.<br />4. Kh&ocirc;ng được sử dụng trang web n&agrave;y để thực hiện h&agrave;nh vi đăng k&yacute; sai tr&aacute;i, gian lận.<br />5. Nghi&ecirc;m cấm mọi h&agrave;nh vi ph&aacute;t t&aacute;n t&agrave;i liệu mang t&iacute;nh đe dọa, phỉ b&aacute;ng,khi&ecirc;u d&acirc;m, ch&iacute;nh trị hoặc ph&acirc;n biệt chủng tộc hoặc bất kỳ t&agrave;i liệu bất hợp ph&aacute;p hoặc khi&ecirc;u kh&iacute;ch n&agrave;o kh&aacute;c th&ocirc;ng qua trang web n&agrave;y.<br />6. Bạn kh&ocirc;ng được sửa đổi, sao ch&eacute;p, truyền tải, ph&acirc;n phối, b&aacute;n, ph&aacute;t t&aacute;n, hoặc cấp ph&eacute;p trang web n&agrave;y v&agrave; bất kỳ phần nội dung n&agrave;o của n&oacute; dưới bất kỳ h&igrave;nh thức n&agrave;o trừ trường hợp bạn sao ch&eacute;p th&ocirc;ng tin từ trang web n&agrave;y để sử dụng cho c&aacute; nh&acirc;n bạn v&agrave; mang t&iacute;nh phi thương mại.</p>\r\n<p><strong>C&aacute;c điều kiện đăng k&yacute;</strong></p>\r\n<p>Khi thực hiện đăng k&yacute; qua trang web của ch&uacute;ng t&ocirc;i, xin vui l&ograve;ng thực hiện mối quan hệ theo hợp đồng trực tiếp (r&agrave;ng buộc ph&aacute;p l&yacute;) với ch&uacute;ng t&ocirc;i. C&aacute;c điều kiện đăng k&yacute; bao gồm những giới hạn v&agrave; những loại trừ về tr&aacute;ch nhiệm ph&aacute;p l&yacute;, v&agrave; ph&iacute; hủy v&agrave; thay đổi phải thanh to&aacute;n nếu việc đăng k&yacute; bị hủy hoặc thay đổi sau khi đ&atilde; được x&aacute;c nhận. Ch&uacute;ng t&ocirc;i đề xuất rằng bạn n&ecirc;n đọc kỹ c&aacute;c điều kiện đăng k&yacute; được &aacute;p dụng khi thực hiện đăng k&yacute;. C&aacute;c điều kiện đăng k&yacute; bao gồm những giới hạn v&agrave; những loại trừ về tr&aacute;ch nhiệm ph&aacute;p l&yacute;, v&agrave; ph&iacute; hủy cũng như thay đổi phải thanh to&aacute;n nếu việc đăng k&yacute; bị hủy hoặc thay đổi sau khi đ&atilde; được x&aacute;c nhận.</p>\r\n<p><strong>Khởi h&agrave;nh đảm bảo</strong></p>\r\n<p>&ndash; Vimitravel bảo đảm khởi h&agrave;nh được đăng tải tr&ecirc;n trang web Mustgo.vn<br />&ndash; Những th&ocirc;ng tin về tour v&agrave; c&aacute;c ng&agrave;y khởi h&agrave;nh trong sổ tay hướng dẫn v&agrave; c&aacute;c t&agrave;i liệu được in ấn kh&aacute;c c&oacute; thể thay đổi t&ugrave;y l&uacute;c v&agrave; kh&ocirc;ng cấu th&agrave;nh một phần của chương tr&igrave;nh bảo đảm.<br />&ndash; Trang web của c&ocirc;ng ty l&agrave; nguồn duy nhất từ đ&oacute; c&ocirc;ng ty sẽ bảo đảm khởi h&agrave;nh. Một thời điểm khởi h&agrave;nh được bảo đảm chỉ khi kh&aacute;ch h&agrave;ng đ&atilde; x&aacute;c nhận đăng k&yacute; theo thời điểm khởi h&agrave;nh đ&oacute; (việc đăng k&yacute; phải bao gồm tối thiểu một khoản tiền đặt cọc được thanh to&aacute;n để được xem x&eacute;t &ldquo;đ&atilde; x&aacute;c nhận&rdquo;). Sự bảo đảm n&agrave;y vẫn sẽ căn cứ v&agrave;o c&aacute;c trường hợp Bất khả kh&aacute;ng.</p>\r\n<p><strong>C&aacute;c trường hợp bất khả kh&aacute;ng</strong></p>\r\n<p>Vimitravel kh&ocirc;ng chịu tr&aacute;ch nhiệm trước kh&aacute;ch h&agrave;ng về những thay đổi hoặc hủy bỏ v&igrave; những l&yacute; do kh&aacute;ch quan v&agrave; chủ quan như: Động đất, n&uacute;i lửa, b&atilde;o gi&oacute;, chiến tranh, đ&igrave;nh c&ocirc;ng, biểu t&igrave;nh, khủng bố, rối loạn ch&iacute;nh trị..C&aacute;c giải ph&aacute;p hoặc đền b&ugrave; (nếu c&oacute;) sẽ phụ thuộc v&agrave;o Nh&agrave; cung cấp dịch vụ li&ecirc;n quan.</p>\r\n<p><strong>Giấy th&ocirc;ng h&agrave;nh</strong></p>\r\n<p>Hộ chiếu c&oacute; gi&aacute; trị: Kh&aacute;ch h&agrave;ng phải c&oacute; hộ chiếu c&oacute; gi&aacute; trị để nhập cảnh, khởi h&agrave;nh v&agrave; đi lại qua mỗi điểm đến trong lịch tr&igrave;nh tour (hộ chiếu phải c&oacute; gi&aacute; trị từ 6 th&aacute;ng trở l&ecirc;n t&iacute;nh đến ng&agrave;y về).<br />C&aacute;c loại giấy tờ: Xin lưu &yacute; rằng &ldquo;Để x&uacute;c tiến việc cấp giấy th&ocirc;ng th&agrave;nh, to&agrave;n bộ c&aacute;c loại giấy tờ li&ecirc;n quan đến tour như c&aacute;c phiếu thanh to&aacute;n, lịch tr&igrave;nh tour, h&oacute;a đơn sẽ được gửi qua email hoặc sẽ c&oacute; tr&ecirc;n trang web Mustgo.vn&rdquo;, khi c&ocirc;ng ty đ&atilde; nhận được thanh to&aacute;n đầy đủ.</p>\r\n<p>Th&ocirc;ng tin chi tiết về chuyến đi: Kh&aacute;ch h&agrave;ng c&oacute; tr&aacute;ch nhiệm truy cập trang web &iacute;t nhất 72 giờ trước ng&agrave;y khởi h&agrave;nh để bảo đảm họ c&oacute; được th&ocirc;ng tin chi tiết nhất về chuyến đi bởi v&igrave; c&oacute; thể đ&atilde; c&oacute; những thay đổi nhỏ kể từ khi hồ sơ tour được c&ocirc;ng ty cung cấp l&uacute;c đầu.</p>\r\n<p><strong>Gi&aacute; tour</strong></p>\r\n<p>To&agrave;n bộ c&aacute;c mức gi&aacute; tour được thực hiện bằng Việt Nam Đồng (VND). C&aacute;c khoản thanh to&aacute;n bằng ngoại tệ kh&aacute;c sẽ được quy đổi ra Việt Nam Đồng (VND) theo tỷ gi&aacute; hối đo&aacute;i hiện h&agrave;nh. C&aacute;c mức gi&aacute; bao gồm c&aacute;c hạng mục được liệt k&ecirc; trong phần &ldquo;Bao gồm&rdquo; Ch&uacute;ng t&ocirc;i giảm gi&aacute; từ 25% &ndash; 50% đối với trẻ em v&agrave; miễn ph&iacute; đối với trẻ c&ograve;n ẵm ngửa.</p>\r\n<p>Những th&ocirc;ng tin v&agrave; mức gi&aacute; tr&ecirc;n trang web n&agrave;y kh&ocirc;ng mang t&iacute;nh &lsquo;trực tiếp&rsquo; v&agrave; đ&ocirc;i l&uacute;c được cập nhật. Mọi sự nỗ lực được thực hiện nhằm bảo đảm sự ch&iacute;nh x&aacute;c về th&ocirc;ng tin v&agrave; c&aacute;c mức gi&aacute;, nhưng đ&aacute;ng tiếc l&agrave; đ&ocirc;i khi vẫn xảy ra lỗi. Trong trường hợp một mức gi&aacute; kh&ocirc;ng ch&iacute;nh x&aacute;c do lỗi hệ thống, ch&uacute;ng t&ocirc;i kh&ocirc;ng chịu sự r&agrave;ng buộc bởi mức gi&aacute; đ&oacute;.</p>\r\n<p><strong>Thanh to&aacute;n</strong></p>\r\n<p>To&agrave;n bộ c&aacute;c tour hoặc dịch vụ cần phải được thanh to&aacute;n trước trừ phi c&oacute; quy định kh&aacute;c.</p>\r\n<p><strong>Cấp phiếu thanh to&aacute;n</strong></p>\r\n<p>Sau khi thanh to&aacute;n, Mustgo.vn sẽ gửi x&aacute;c nhận/phiếu thanh to&aacute;n qua email; phiếu n&agrave;y phải được in ra như l&agrave; bằng chứng c&ugrave;a việc mua b&aacute;n, v&agrave; sẽ được xuất tr&igrave;nh cho Nh&agrave; cung cấp dịch vụ.<br />Tất cả th&ocirc;ng tin về du kh&aacute;ch cần phải được cung cấp một c&aacute;ch ch&iacute;nh x&aacute;c tại thời điểm đăng k&yacute;.<br />Mọi y&ecirc;u cầu sửa đổi/bổ sung cần ph&agrave;i được gửi qua email đến Mustgo.vn</p>\r\n<p>Mustgo.vn kh&ocirc;ng chịu tr&aacute;ch nhiệm về bất cứ vấn đề g&igrave; c&oacute; thể xảy ra nếu bạn kh&ocirc;ng nhận hoặc đọc kỹ x&aacute;c nhận/phiếu thanh to&aacute;n của bạn. Trong trường hợp bạn chưa nhận được phiếu thanh to&aacute;n, bạn cần phải th&ocirc;ng b&aacute;o cho Mustgo.vn &iacute;t nhất 72 giờ trước ng&agrave;y cung cấp dịch vụ ấn định.</p>\r\n<p><strong>Quy tr&igrave;nh hủy dịch vụ v&agrave; ho&agrave;n trả</strong></p>\r\n<p>Việc hủy to&agrave;n bộ c&aacute;c dịch vụ bởi người tham gia tour phải được gửi v&agrave; nhận bằng văn bản: email hoặc fax. Mustgo.vn kh&ocirc;ng chấp nhận y&ecirc;u cầu hủy dịch vụ qua điện thoại.<br />Mustgo.vn kh&ocirc;ng chịu tr&aacute;ch nhiệm về mọi y&ecirc;u cầu hủy dịch vụ chưa nhận được v&agrave; chưa x&aacute;c nhận lại với bạn. Trong trường hợp n&agrave;y, sẽ &aacute;p dụng ph&iacute; hủy dịch vụ hoặc bỏ tour.</p>\r\n<p><strong>Khiếu nại v&agrave; đ&ograve;i bồi thường</strong></p>\r\n<p>Nếu kh&aacute;ch h&agrave;ng c&oacute; khiếu nại đến Mustgo.vn, trước hết kh&aacute;ch h&agrave;ng phải th&ocirc;ng b&aacute;o cho trưởng đo&agrave;n c&agrave;ng sớm c&agrave;ng tốt để khắc phục vấn đề khiếu nại đ&oacute;. Nếu kh&ocirc;ng h&agrave;i l&ograve;ng, kh&aacute;ch h&agrave;ng c&oacute; thể li&ecirc;n hệ với đại diện (b&aacute;n h&agrave;ng) của Mustgo.vn trong khi đi tour để Mustgo.vn &nbsp;c&oacute; cơ hội khắc phục vấn đề đ&oacute;. Nếu vẫn chưa h&agrave;i l&ograve;ng qua c&aacute;c k&ecirc;nh đ&oacute; trong chuyến đi th&igrave; kh&aacute;ch h&agrave;ng cần gửi mọi vấn đề khiếu nại trực tiếp đến Mustgo.vn trong v&ograve;ng 30 ng&agrave;y kể từ ng&agrave;y kết th&uacute;c chuyến đi. C&ocirc;ng ty sẽ kh&ocirc;ng nhận mọi tr&aacute;ch nhiệm đối với những khiếu nại nhận được sau thời hạn n&agrave;y.</p>\r\n<p><strong>Khuyến mại</strong></p>\r\n<p>C&aacute;c tour/g&oacute;i tour/c&aacute;c sản phẩm được giao dịch bằng m&atilde; code khuyến mại sẽ kh&ocirc;ng thể hủy v&agrave; kh&ocirc;ng được ho&agrave;n trả.<br />Hơn thế nữa, mọi khoản giảm gi&aacute; v&agrave; điểm thưởng được đăng tải tr&ecirc;n trang web n&agrave;y chỉ được &aacute;p dụng cho trang web Mustgo.vn v&agrave; c&oacute; thể thay đổi qua c&aacute;c trang web kh&aacute;c li&ecirc;n kết với Mustgo.vn.</p>\r\n<p><strong>Quan hệ thư từ</strong></p>\r\n<p>Khi ho&agrave;n th&agrave;nh một đăng k&yacute;, bạn đồng &yacute; nhận email m&agrave; ch&uacute;ng t&ocirc;i c&oacute; thể gửi đến bạn, cung cấp cho bạn những th&ocirc;ng tin về điểm đến v&agrave; những th&ocirc;ng tin cụ thể li&ecirc;n quan đến đăng k&yacute; v&agrave; c&aacute;c điểm đến của bạn, v&agrave; ch&uacute;ng t&ocirc;i c&oacute; thể gửi email mời bạn điền v&agrave;o mẫu đ&aacute;nh gi&aacute; của kh&aacute;ch h&agrave;ng.</p>\r\n<p><strong>Bản quyền v&agrave; thương hiệu</strong></p>\r\n<p>Bản quyền trong nội dung của trang web n&agrave;y thuộc về Mustgo.vn. Ch&uacute;ng t&ocirc;i bảo lưu bản quyền v&agrave; c&oacute; to&agrave;n bộ quyền sở hữu đối với trang web n&agrave;y v&agrave; to&agrave;n bộ nội dung của n&oacute;. Thương hiệu Mustgo.vn v&agrave; mọi k&yacute; hiệu, logo v&agrave; h&igrave;nh ảnh của Mustgo.vn tr&ecirc;n trang web n&agrave;y đ&atilde; được đăng k&yacute; bảo hộ bản quyền của Vimitravel. C&aacute;c thương hiệu của c&aacute;c c&ocirc;ng ty v&agrave; sản phẩm/dịch vụ kh&aacute;c tr&ecirc;n trang web n&agrave;y c&oacute; thể l&agrave; thương hiệu của c&aacute;c chủ sở hữu của c&aacute;c thương hiệu đ&oacute;. Bạn kh&ocirc;ng c&oacute; quyền v&agrave; kh&ocirc;ng được cấp ph&eacute;p sử dụng bất kỳ thương hiệu n&agrave;o trong số ấy.</p>\r\n<p><strong>Những thay đổi</strong></p>\r\n<p>Vimitravel c&oacute; thể thay đổi bất kỳ phương diện n&agrave;o của trang web n&agrave;y hoặc nội dung của n&oacute;, bao gồm những điểm đặc trưng, những th&ocirc;ng tin hoặc nội dung kh&aacute;c v&agrave;o bất cứ l&uacute;c n&agrave;o m&agrave; kh&ocirc;ng cần phải đưa ra th&ocirc;ng b&aacute;o trước.</p>\r\n<p>&nbsp;</p>', 0, '0000-00-00 00:00:00', '2019-01-03 19:47:40'),
	(5, 'cau-hoi-thuong-gap', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(6, 'chinh-sach-cam-ket-ga-tot-nhat', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(7, 'must-go-la-gi', '1234', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
	(8, 'ngay-het-han-khach-san', '90', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `configs` ENABLE KEYS */;

-- Dumping structure for table trippal.fanpages
CREATE TABLE IF NOT EXISTS `fanpages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `page_id` varchar(64) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table trippal.fanpages: ~2 rows (approximately)
/*!40000 ALTER TABLE `fanpages` DISABLE KEYS */;
INSERT INTO `fanpages` (`id`, `user_id`, `name`, `page_id`, `access_token`, `created`, `modified`) VALUES
	(1, 9, 'Booking LOTR', '1701207703507681', 'EAAKx59Tq1vYBAK3yZBmpBUlgytnrrAaaV25P3gUZC0hVkdKyoP4IHB2ZAYT5jQU8DG5MGfZCK34sfL9ZC4sZBGD9ZAT4iAZCpZB5I0TF16VCrw1ZCmfbPsVcqW9eFq1lzlFS3hPGsYqZAJA4EjorZCiLAd2rIl3ZA7PoPFm4gBZCXcLZBAcW0LbYTe6oxJuVgex5C1ZC8AZAvMgYnDCu39QZDZD', '2018-10-23 11:22:40', '2018-12-06 14:59:47'),
	(2, 9, 'Hà Ry Bọt Bèo', '2084819251803281', 'EAAKx59Tq1vYBAAEX2miqZCbknCUUaIEsgApTVm41FA38uTQ2xeEq1dF67VHvGkXJ3BZAvZBLpgfc7ZAxOZBdXNvPHnxZA6IJRrQvSMTHBFUaT5l1LBBSjrjs46AVjFufzFXaVXNqg3PQEZCTDerk1K7mm5ogUQlZAFqZC9nu2SpOAJF9tgu5SenR3hc13VtVYZAed6yS0hF0PSxgZDZD', '2018-10-23 11:22:40', '2018-12-06 14:59:47'),
	(3, 13, 'Rty', '308392379889976', 'EAAKx59Tq1vYBABSJDiatK0nIMjOObd14EWxEn3Mej4h9eVuCcSctwHXyuVdMHckTVbXirQqLSYZAOkmrigJbJln5bL04HVhfGR8uUvBkRPjZBcBlz7YAnf10TeUltrNWKTDZAhhl4CjPcOpiyx0iXCY6ubibrheex87XVVwzaw6iih4orRe1DGSjBzjXoLmTS2cZCmOI2QZDZD', '2019-01-02 11:04:01', '2019-01-08 09:41:22');
/*!40000 ALTER TABLE `fanpages` ENABLE KEYS */;

-- Dumping structure for table trippal.hotels
CREATE TABLE IF NOT EXISTS `hotels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_special` int(2) NOT NULL,
  `icon_list` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `caption` text NOT NULL,
  `location_id` int(11) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `media` text NOT NULL,
  `fb_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `rating` float NOT NULL,
  `address` varchar(255) NOT NULL,
  `map` text NOT NULL,
  `hotline` varchar(255) NOT NULL,
  `term` text NOT NULL,
  `email` text NOT NULL,
  `price_agency` int(11) NOT NULL,
  `price_customer` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- Dumping data for table trippal.hotels: ~12 rows (approximately)
/*!40000 ALTER TABLE `hotels` DISABLE KEYS */;
INSERT INTO `hotels` (`id`, `name`, `is_special`, `icon_list`, `slug`, `description`, `caption`, `location_id`, `thumbnail`, `media`, `fb_content`, `rating`, `address`, `map`, `hotline`, `term`, `email`, `price_agency`, `price_customer`, `created`, `modified`) VALUES
	(1, 'Khách sạn 5 sao', 0, '', 'khach-san-5-sao', '<p>M&ocirc; tả</p>', '', 2, 'files/uploads/1539331428-sport.jpg', '', '', 3, '', 'Map here', '09000009999', '<p>Ch&iacute;nh s&aacute;ch g&igrave; đ&oacute;</p>', '', 0, 0, '2018-10-11 03:28:18', '2018-12-14 17:31:16'),
	(2, 'Khách sạn 3 sao', 0, '', 'khach-san-3-sao', '<p>Đẹp kh&ocirc;ng kh&aacute;c g&igrave; 4 sao</p>', '', 3, 'files/uploads/1539760550-comic.jpg', '', '', 3, '', 'map', '0341641654165', '<p>Ch&iacute;nh s&aacute;ch</p>', '', 0, 0, '2018-10-17 07:15:51', '2018-12-20 15:52:44'),
	(3, 'Novotel', 0, '', 'novotel', '<p>Kh&aacute;ch sạn số 1 Đ&agrave; Th&agrave;nh</p>\r\n<p>H&agrave; Nội cũng c&oacute; v&agrave;i cơ sở đấy</p>', '', 3, 'files/uploads/1541737386-room 6.jpg', '', '', 5, '36 Bach Dang Street, Hai Chau District, Danang City', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d479.2214942668163!2d108.22337875286048!3d16.077319409026877!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xd52dbe9e28e83835!2sNovotel+Danang+Premier+Han+River!5e0!3m2!1svi!2shk!4v1541994737569" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>', '09898989989', '<p>Ch&iacute;nh s&aacute;ch 5*</p>', '', 0, 0, '2018-11-09 04:23:07', '2019-01-02 10:02:02'),
	(6, '12321', 0, '', '12321', '<p>dsads</p>', '', 1, 'files/uploads/1542711095-room 11.jpg', '["files/uploads/1542711085-room 8.jpg","files/uploads/1542711086-room 9.jpg","files/uploads/1542711426-room 13.jpg"]', '', 5, 'adasd', 'ádasd', '064646465465', '<p>&aacute;dasd</p>', '', 0, 0, '2018-11-20 10:51:35', '2018-12-14 17:30:17'),
	(7, 'sfsdfsdfsd', 0, '', 'sfsdfsdfsd', '<p>sdfsdfsdfsdf</p>', '', 3, 'files/uploads/1542712412-room 2.jpg', '["files/uploads/1542712191-room 2.jpg","files/uploads/1542712190-room 3.jpg","files/uploads/1542712190-room 4.jpg","files/uploads/1542712191-room 7.jpg"]', '', 5, '234234234', '23 23r sdv2w4t24tr2', '2342342342', '<p>gbdfbw 4t2 4 234</p>', '', 0, 0, '2018-11-20 11:13:33', '2018-11-20 11:15:46'),
	(14, 'ocean', 0, '["fas fa-plane"]', 'ocean', '<p>Tọa lạc tại khu vực Phước Mỹ, c&aacute;ch biển 300m Ocean Haven Hotel l&agrave; địa điểm ho&agrave;n hảo để cảm nhận Đ&agrave; Nẵng v&agrave; c&aacute;c địa điểm du lịch xung quanh. Với vị tr&iacute; thuận lợi, kh&aacute;ch sạn dễ d&agrave;ng tiếp cận những điểm tham quan du lịch nổi tiếng của th&agrave;nh phố.<br /><br />H&atilde;y tận hưởng hết v&ocirc; số dịch vụ v&agrave; tiện nghi kh&ocirc;ng g&igrave; s&aacute;nh được ở kh&aacute;ch sạn Đ&agrave; Nẵng n&agrave;y. Kh&aacute;ch sạn đem đến sự tiếp cận h&agrave;ng loạt dịch vụ như dịch vụ ph&ograve;ng 24 giờ, miễn ph&iacute; wifi tất cả c&aacute;c ph&ograve;ng, quầy lễ t&acirc;n 24 giờ, wifi c&ocirc;ng cộng, b&atilde;i đậu xe.<br /><br />Tất cả nơi ăn chốn ở của kh&aacute;ch đều c&oacute; trang bị tiện nghi chu đ&aacute;o để bảo đảm kh&aacute;ch c&oacute; cảm gi&aacute;c dễ chịu kh&ocirc;ng nơi n&agrave;o s&aacute;nh được. B&ecirc;n cạnh đ&oacute;, kh&aacute;ch sạn c&ograve;n gợi &yacute; cho bạn những hoạt động vui chơi giải tr&iacute; bảo đảm bạn lu&ocirc;n thấy hứng th&uacute; trong suốt k&igrave; nghỉ. Khi bạn t&igrave;m kiếm chỗ tạm tr&uacute; thoải m&aacute;i v&agrave; tiện nghi ở Đ&agrave; Nẵng, h&atilde;y bắt đầu cuộc h&agrave;nh tr&igrave;nh đến Ocean Haven Hotel.</p>', '[{"icon":"fas fa-car-alt","content":"12313"}]', 6, 'files/uploads/1544153645-5922e4bbb86081e75030041ffa36cb36.jpg', '["files/uploads/1544152974-2b534b50f85bc98ef5907e4b6ac59088.jpg","files/uploads/1544152974-33cbb4c3788410324fbf5cb0f6722036.jpg","files/uploads/1544152974-84e94b6ac5b050b436514cde8733e982.jpg","files/uploads/1544152974-0199e7808d0be689d64458c71bf1e984.jpg","files/uploads/1544152974-4982cb257e26e94adf0f3f13a6e75db8.jpg","files/uploads/1544152974-5922e4bbb86081e75030041ffa36cb36.jpg","files/uploads/1544152974-9997e7ad272cb22ce53f9276d56c9c0e.jpg","files/uploads/1544152974-079750f197e94174681d699c244dcddb.jpg","files/uploads/1544152974-b97c6e08e26cb38fabfb55b61bf48809.jpg","files/uploads/1544152974-c0206d31789704d0178b84ce4abb8dd4.jpg"]', '1234', 3, 'nhà 910 số 47 vũ trọng phụng, thanh xuân, hà nội', '<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15336.010829722478!2d108.2433675!3d16.0653493!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb204604b762284ec!2sOcean+Haven+Hotel!5e0!3m2!1svi!2s!4v1544152435563" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>', '0236 3959 955', '<p><strong>1. Phụ thu trẻ em (ở chung với cha mẹ):</strong></p>\r\n<p>&nbsp;- Từ 0 &ndash; dưới 6 tuổi : miễn ph&iacute; buffet s&aacute;ng, tối đa 2 trẻ.<br />&nbsp;- Từ 6 &ndash; dưới 12 tu&ocirc;̉i : phụ thu buffet s&aacute;ng 70.000VNĐ/trẻ.<br />&nbsp;- Từ 12 tuổi trở l&ecirc;n : phụ thu buffet s&aacute;ng 200.000 VNĐ/người/kh&ocirc;ng k&ecirc; giường phụ</p>\r\n<p>&nbsp;- K&ecirc; th&ecirc;m giường phụ : 250.000/đ&ecirc;m (nếu y&ecirc;u cầu)</p>\r\n<p>*Gi&aacute; ph&ograve;ng kh&ocirc;ng &aacute;p dung cho thời điểm bắn ph&aacute;o hoa quốc tế tại Đ&agrave; Nẵng h&agrave;ng năm<br /><br /><strong>2. Phụ thu lễ tết</strong><br />- Phụ thu 10%-30%/ph&ograve;ng/đ&ecirc;m đối với ng&agrave;y Quốc lễ (giỗ tổ H&ugrave;ng Vương, Quốc kh&aacute;nh, Gi&aacute;ng sinh,năm mới), thời gian phụ thu được t&iacute;nh theo số ng&agrave;y nghỉ lễ của Nh&agrave; Nước.<br />- Phụ thu 50% đối với cuộc thi ph&aacute;o hoa Quốc tế Đ&agrave; Nẵng.</p>\r\n<p>&nbsp;</p>\r\n<p><strong>3. Ch&iacute;nh s&aacute;ch nhận ph&ograve;ng &ndash; trả ph&ograve;ng</strong><br />- Nhận ph&ograve;ng l&agrave; sau 14:00, Trả ph&ograve;ng trước 12:00 trưa.<br />- Sau 12:00 trưa đến 15:00 chiều - thu 30% tiền ph&ograve;ng.<br />- Sau 15:00 chiều đến 18:00 tối - thu 50% tiền ph&ograve;ng.<br />- Sau 18:00 tối &ndash; thu 100% tiền ph&ograve;ng.</p>\r\n<p>&nbsp;</p>\r\n<p><strong>4. Ch&iacute;nh s&aacute;ch hủy ph&ograve;ng</strong></p>\r\n<p>-&nbsp; Đo&agrave;n dưới 15 ph&ograve;ng<br />o Điều chỉnh/hủy trước 7 ng&agrave;y miễn phạt<br />o Điều chỉnh/hủy trong 7 ng&agrave;y phạt 50%<br />o Điều chỉnh/hủy trước 48 giờ phạt 100%<br /><br /></p>\r\n<p>- Đối với c&aacute;c ng&agrave;y Quốc Lễ v&agrave; cuộc thi ph&aacute;o hoa Quốc Tế, y&ecirc;u cầu điều chỉnh hoặc hủy ph&ograve;ng phải được<br />th&ocirc;ng b&aacute;o trước 40 ng&agrave;y.<br />- Đối với những booking cận ng&agrave;y, việc điều chỉnh/hủy v&agrave; thanh to&aacute;n phải theo thỏa thuận với bộ phận kinh<br />doanh từng thời điểm, nếu kh&ocirc;ng, kh&aacute;ch sạn thực hiện t&iacute;nh ph&iacute; đ&ecirc;m đầu ti&ecirc;n cho tất cả c&aacute;c đ&ecirc;m.</p>', '["zzphonixzz@gmail.com","nambuihoang1999@gmail.com"]', 50000, 150000, '2018-12-07 10:34:05', '2019-01-16 10:35:34'),
	(16, 'adeline', 0, '["fas fa-bed"]', 'adeline', '<p>Khi đến thăm Đ&agrave; Nẵng, bạn sẽ cảm thấy như đang ở nh&agrave; tại Adaline Hotel &amp; Suite, nơi c&oacute; chất lượng tuyệt vời v&agrave; dịch vụ chu đ&aacute;o. Từ đ&acirc;y, kh&aacute;ch c&oacute; thể dễ d&agrave;ng tiếp cận được n&eacute;t đẹp sống động của th&agrave;nh phố ở mọi g&oacute;c cạnh. Với vị tr&iacute; thuận lợi, kh&aacute;ch sạn dễ d&agrave;ng tiếp cận những điểm tham quan du lịch nổi tiếng của th&agrave;nh phố.<br /><br />Tại Adaline Hotel &amp; Suite, dịch vụ ho&agrave;n hảo v&agrave; thiết bị tối t&acirc;n tạo n&ecirc;n một k&igrave; nghỉ kh&oacute; qu&ecirc;n. C&oacute; thể kể t&ecirc;n một v&agrave;i tiện nghi ở kh&aacute;ch sạn như dịch vụ ph&ograve;ng 24 giờ, miễn ph&iacute; wifi tất cả c&aacute;c ph&ograve;ng, an ninh 24 giờ, dịch vụ ph&ograve;ng h&agrave;ng ng&agrave;y, dịch vụ taxi.<br /><br />Kh&aacute;ch sạn rất ch&uacute; &yacute; đến việc trang bị đầy đủ tiện nghi để đạt được sự thoải m&aacute;i v&agrave; tiện lợi nhất. Trong một số ph&ograve;ng, kh&aacute;ch h&agrave;ng c&oacute; thể thấy tivi m&agrave;n h&igrave;nh phẳng, cafe h&ograve;a tan miễn ph&iacute;, tr&agrave; miễn ph&iacute;, nước uống ch&agrave;o đ&oacute;n miễn ph&iacute;, c&aacute;c loại khăn. Những phương tiện giải tr&iacute; của kh&aacute;ch sạn bao gồm bồn tắm nước n&oacute;ng, hồ bơi&thinsp;ngo&agrave;i trời được thiết kế để đem đến cho bạn sự thư gi&atilde;n thoải m&aacute;i. Cơ sở vật chất tuyệt vời v&agrave; vị tr&iacute; đắc địa l&agrave;m cho Adaline Hotel &amp; Suite trở th&agrave;nh nơi ho&agrave;n hảo để bạn tận hưởng kỳ nghỉ ở Đ&agrave; Nẵng.</p>', '[{"icon":"fas fa-motorcycle","content":"xe"}]', 6, 'files/uploads/1544157234-1899405_17041214310052340392.jpg', '["files/uploads/1544155381-1899405_17012009580050431168.jpg","files/uploads/1544155381-1899405_17012314110050486316.jpg","files/uploads/1544155381-1899405_17012314110050486317.jpg","files/uploads/1544155381-1899405_17012314110050486321.jpg","files/uploads/1544155381-1899405_17012314160050486436.jpg","files/uploads/1544155381-1899405_17021014450050907678.jpg","files/uploads/1544155381-1899405_17021016140050909969.jpg","files/uploads/1544155381-1899405_17021016220050910054.jpg","files/uploads/1544155381-1899405_17021016250050910136.jpg","files/uploads/1544155381-1899405_17041214310052340392.jpg"]', '1234', 4, 'nhà 910 số 47 vũ trọng phụng, thanh xuân, hà nội', '<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d30671.99618592295!2d108.24972063284912!3d16.065514535944153!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x61a9113df756b76c!2sAdaline+Hotel!5e0!3m2!1svi!2s!4v1544155585043" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>', '0236 3666 567', '<p><strong>1. Phụ thu trẻ em</strong></p>\r\n<p>Trẻ em (kh&ocirc;ng k&ecirc; th&ecirc;m giường phụ):<br />- Trẻ em dưới 5 tuổi: miễn ph&iacute;<br />- Trẻ em từ 6 tuổi &ndash; 10 tuổi: phụ thu 100.000 vnd/ trẻ<br />- Trẻ em từ 11 tuổi trở l&ecirc;n:200.000 vnd/1 người<br />-&nbsp;Trẻ em từ 11 tuổi trở l&ecirc;n:300.000 vnd/1 người ( c&oacute; k&ecirc; giường phụ)</p>\r\n<p>&nbsp;</p>\r\n<p><strong>2. Phụ thu lễ tết</strong></p>\r\n<p>Phụ thu 30% v&agrave;o những ng&agrave;y sau</p>\r\n<p>- Tết Nguy&ecirc;n Đ&aacute;n, Tết Dương lịch 2019<br />- Giỗ Tổ H&ugrave;ng Vương: 10 /03 &acirc;m lịch<br />- Thống nhất đất nước: 30/04/2019<br />- Quốc tế lao động: 01/05/2019<br />- Quốc kh&aacute;nh: 02/09/2019</p>\r\n<p>&nbsp;</p>\r\n<p><strong>3. Ch&iacute;nh s&aacute;ch nhận ph&ograve;ng, trả ph&ograve;ng</strong></p>\r\n<p><br />- Nhận ph&ograve;ng l&uacute;c 14:00 giờ. Trả ph&ograve;ng trước 12:00 giờ trưa.<br />- Nhận ph&ograve;ng sớm trước 6h00 s&aacute;ng: Phụ thu 70%&nbsp;<br />- Nhận ph&ograve;ng sớm từ 6h00 s&aacute;ng đến trước 11h00 s&aacute;ng: Phụ thu 35%.<br />- Nhận ph&ograve;ng sớm sau 11h: miễn ph&iacute; (nếu c&oacute; ph&ograve;ng).<br />- Trả ph&ograve;ng muộn đến trước 18h00: phụ thu 50%<br />- Trả ph&ograve;ng muộn sau 18h00: phụ thu 100%.</p>\r\n<p>&nbsp;</p>\r\n<p><strong>4. Ch&iacute;nh s&aacute;ch huỷ ph&ograve;ng</strong></p>\r\n<p><br />- Huỷ ph&ograve;ng c&oacute; th&ocirc;ng b&aacute;o trước 20 ng&agrave;y so với ng&agrave;y nhận ph&ograve;ng: Kh&ocirc;ng ho&agrave;n trả khoản đặt cọc được xem l&agrave; ph&iacute; huỷ ph&ograve;ng.<br />- Huỷ ph&ograve;ng c&oacute; th&ocirc;ng b&aacute;o trước 10 ng&agrave;y so với ng&agrave;y nhận ph&ograve;ng: Thu ph&iacute; huỷ ph&ograve;ng 50% đ&ecirc;m đầu ti&ecirc;n.<br />- Huỷ ph&ograve;ng c&oacute; th&ocirc;ng b&aacute;o trước 24 giờ: Thu ph&iacute; huỷ ph&ograve;ng 100% tổng gi&aacute; trị đặt ph&ograve;ng.<br />- Kh&aacute;ch kh&ocirc;ng đến thu ph&iacute; huỷ ph&ograve;ng 100% tổng gi&aacute; trị đặt ph&ograve;ng.</p>', '["zzphonixzz@gmail.com","nambuihoang1999@gmail.com"]', 50000, 150000, '2018-12-07 11:33:54', '2019-01-12 14:40:52'),
	(17, 'VinPearl', 1, '', 'vinpearl', '<p>test</p>', '["short gì gì đó","ngắn vãi lòn","vẫn ngắn","still ngắn af"]', 4, 'files/uploads/1545274572-9.jpg', '["files/uploads/1545274547-1.jpg","files/uploads/1545274547-2.jpg","files/uploads/1545274548-3.jpg","files/uploads/1545274548-4.jpg","files/uploads/1545274548-5.jpg","files/uploads/1545274550-6.jpg","files/uploads/1545274551-7.jpg","files/uploads/1545274552-8.jpg"]', '', 5, 'nhà 910 số 47 vũ trọng phụng, thanh xuân, hà nội', '', '0945554266', '<p>ch&iacute;nh s&aacute;ch 12345</p>', '["zzphonixzz@gmail.com","nambuihoang1999@gmail.com"]', 150000, 5000000, '2018-12-20 09:56:12', '2018-12-20 17:10:12'),
	(18, 'Flamingo đại cmn Lải', 1, '', 'flamingo-dai-cmn-lai', '<p>1234</p>', '["short gì gì đó"]', 4, 'files/uploads/1545278510-9.jpg', '["files/uploads/1545278496-1.jpg","files/uploads/1545278496-2.jpg","files/uploads/1545278496-4.jpg","files/uploads/1545278496-5.jpg","files/uploads/1545278497-6.jpg"]', '', 5, 'nhà 910 số 47 vũ trọng phụng, thanh xuân, hà nội', '', '0945554266', '<p>123456</p>', '', 222222, 333333, '2018-12-20 11:01:50', '2018-12-20 11:01:50'),
	(19, 'bà nà hilll', 1, '', 'ba-na-hilll', '<p>12345</p>', '["short gì gì đó"]', 4, 'files/uploads/1545294353-9.jpg', '["files/uploads/1545294336-1.jpg","files/uploads/1545294336-2.jpg","files/uploads/1545294336-3.jpg","files/uploads/1545294337-4.jpg","files/uploads/1545294336-5.jpg","files/uploads/1545294341-6.jpg","files/uploads/1545294346-8.jpg"]', '', 5, 'nhà 910 số 47 vũ trọng phụng, thanh xuân, hà nội', '', '0945554266', '<p>oki</p>', '', 111111, 333333, '2018-12-20 15:25:54', '2018-12-20 15:25:54'),
	(20, 'Nam Bùi Hoàng', 0, '', 'nam-bui-hoang', '<p>svgsf</p>', '[{"icon":"fas fa-plane","content":"fghjklfgh"}]', 4, 'files/uploads/1545297553-8.jpg', '', '', 0, 'nhà 910 số 47 vũ trọng phụng, thanh xuân, hà nội', '', '0945sad', '<p>&aacute;dasd</p>', '', 222222, 333333, '2018-12-20 16:19:13', '2018-12-20 16:19:13'),
	(21, 'testt', 1, '[]', 'testt', '<p>&aacute;d</p>', '[{"icon":"fas fa-bus","content":"12313"}]', 5, 'files/uploads/1546399434-48417226_1958541020849323_9173472707377889280_n.jpg', '["files/uploads/1546399108-44755781_1564412763704990_3773877274961510400_n.jpg"]', '12345', 5, 'nhà 910 số 47 vũ trọng phụng, thanh xuân, hà nội', '', '0945sad', '<p>abcd</p>', '', 222222, 333333, '2019-01-02 10:23:55', '2019-01-02 10:23:55'),
	(22, 'khách sạn test giá', 0, '["fas fa-plane"]', 'khach-san-test-gia', '<p>1 kh&aacute;ch sạn n&agrave;o đ&oacute;</p>', '[{"icon":"fas fa-check","content":"mô tả ngắn"}]', 4, 'files/uploads/1547001701-48417226_1958541020849323_9173472707377889280_n.jpg', '', 'không có', 5, 'số 47 vũ trọng phụng', '', '0945554266', '<p>ch&iacute;nh s&aacute;ch n&agrave;o đ&oacute;</p>', '["zzphonixzz@gmail.com"]', 1000000, 3000000, '2019-01-09 09:41:41', '2019-01-09 09:41:41');
/*!40000 ALTER TABLE `hotels` ENABLE KEYS */;

-- Dumping structure for table trippal.hotels_categories
CREATE TABLE IF NOT EXISTS `hotels_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=296 DEFAULT CHARSET=utf8;

-- Dumping data for table trippal.hotels_categories: ~155 rows (approximately)
/*!40000 ALTER TABLE `hotels_categories` DISABLE KEYS */;
INSERT INTO `hotels_categories` (`id`, `hotel_id`, `category_id`) VALUES
	(1, 1, 3),
	(2, 1, 12),
	(3, 1, 14),
	(4, 1, 18),
	(5, 1, 19),
	(6, 1, 22),
	(7, 1, 23),
	(8, 1, 32),
	(9, 1, 40),
	(10, 1, 50),
	(11, 2, 4),
	(12, 2, 12),
	(13, 2, 13),
	(14, 2, 15),
	(15, 2, 16),
	(16, 2, 28),
	(17, 2, 30),
	(18, 2, 34),
	(19, 3, 2),
	(20, 3, 5),
	(21, 3, 6),
	(22, 3, 11),
	(23, 3, 12),
	(24, 3, 13),
	(25, 3, 14),
	(26, 3, 15),
	(27, 3, 16),
	(28, 3, 20),
	(29, 3, 22),
	(30, 3, 26),
	(31, 3, 28),
	(32, 3, 31),
	(33, 3, 32),
	(34, 3, 33),
	(35, 3, 35),
	(36, 3, 36),
	(37, 3, 40),
	(38, 3, 41),
	(39, 3, 45),
	(40, 3, 52),
	(41, 4, 9),
	(42, 4, 12),
	(43, 4, 15),
	(44, 5, 9),
	(45, 5, 12),
	(46, 5, 15),
	(47, 5, 18),
	(48, 5, 21),
	(49, 5, 25),
	(50, 6, 18),
	(51, 6, 25),
	(52, 6, 27),
	(53, 6, 36),
	(54, 6, 42),
	(55, 6, 45),
	(56, 7, 2),
	(57, 7, 7),
	(58, 7, 9),
	(59, 7, 13),
	(60, 7, 19),
	(61, 7, 23),
	(62, 7, 28),
	(63, 7, 29),
	(64, 7, 36),
	(65, 7, 38),
	(66, 7, 40),
	(103, 14, 2),
	(104, 14, 3),
	(105, 14, 4),
	(106, 14, 5),
	(107, 14, 6),
	(109, 14, 8),
	(110, 14, 9),
	(112, 14, 11),
	(115, 14, 16),
	(116, 14, 19),
	(117, 14, 20),
	(118, 14, 21),
	(119, 14, 22),
	(120, 14, 24),
	(122, 14, 26),
	(123, 14, 27),
	(126, 14, 32),
	(127, 14, 33),
	(131, 14, 39),
	(132, 14, 40),
	(133, 14, 41),
	(134, 14, 42),
	(135, 14, 43),
	(136, 14, 44),
	(137, 14, 45),
	(138, 14, 46),
	(140, 14, 48),
	(141, 14, 49),
	(142, 14, 50),
	(143, 14, 51),
	(144, 14, 53),
	(145, 14, 54),
	(146, 14, 55),
	(147, 14, 56),
	(150, 14, 59),
	(152, 14, 61),
	(153, 14, 62),
	(205, 16, 2),
	(206, 16, 3),
	(207, 16, 4),
	(208, 16, 5),
	(209, 16, 8),
	(210, 16, 21),
	(211, 16, 22),
	(212, 16, 31),
	(213, 16, 40),
	(214, 16, 41),
	(215, 16, 42),
	(216, 16, 44),
	(217, 16, 45),
	(218, 16, 53),
	(219, 16, 54),
	(220, 16, 56),
	(221, 16, 59),
	(222, 16, 61),
	(223, 17, 3),
	(224, 17, 8),
	(225, 17, 11),
	(226, 17, 15),
	(227, 17, 18),
	(228, 17, 22),
	(229, 17, 27),
	(230, 17, 30),
	(231, 17, 31),
	(232, 17, 35),
	(233, 17, 37),
	(234, 17, 39),
	(235, 17, 40),
	(236, 17, 41),
	(237, 17, 42),
	(238, 17, 43),
	(239, 17, 44),
	(240, 17, 45),
	(241, 17, 46),
	(242, 17, 47),
	(243, 17, 48),
	(244, 17, 49),
	(245, 17, 50),
	(246, 17, 51),
	(247, 17, 52),
	(248, 17, 54),
	(249, 17, 55),
	(250, 17, 56),
	(251, 17, 57),
	(252, 17, 58),
	(253, 17, 59),
	(254, 17, 60),
	(255, 17, 61),
	(256, 17, 62),
	(257, 18, 22),
	(258, 18, 27),
	(259, 18, 31),
	(260, 18, 36),
	(261, 18, 38),
	(262, 18, 41),
	(263, 18, 44),
	(264, 18, 52),
	(265, 18, 56),
	(266, 19, 19),
	(267, 19, 20),
	(268, 19, 28),
	(269, 19, 31),
	(270, 19, 56),
	(271, 20, 31),
	(272, 20, 38),
	(273, 21, 7),
	(274, 21, 14),
	(275, 21, 18),
	(276, 21, 23),
	(277, 21, 24),
	(278, 22, 10),
	(279, 22, 13),
	(280, 22, 18),
	(281, 22, 21),
	(282, 22, 22),
	(283, 22, 24),
	(284, 22, 27),
	(285, 22, 29),
	(286, 22, 32),
	(287, 22, 40),
	(288, 22, 45),
	(289, 22, 46),
	(290, 22, 47),
	(291, 22, 49),
	(292, 22, 52),
	(293, 22, 56),
	(294, 22, 57),
	(295, 22, 60);
/*!40000 ALTER TABLE `hotels_categories` ENABLE KEYS */;

-- Dumping structure for table trippal.land_tours
CREATE TABLE IF NOT EXISTS `land_tours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `caption` text COLLATE utf8mb4_bin NOT NULL,
  `description` text COLLATE utf8mb4_bin NOT NULL,
  `phone` tinytext COLLATE utf8mb4_bin NOT NULL,
  `price` int(11) NOT NULL,
  `trippal_price` int(11) NOT NULL,
  `customer_price` int(11) NOT NULL,
  `promote` float NOT NULL,
  `departure_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `days` int(2) NOT NULL,
  `rating` float NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `media` text COLLATE utf8mb4_bin NOT NULL,
  `fb_content` text COLLATE utf8mb4_bin NOT NULL,
  `icon_list` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` int(2) NOT NULL,
  `term` text COLLATE utf8mb4_bin NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table trippal.land_tours: ~4 rows (approximately)
/*!40000 ALTER TABLE `land_tours` DISABLE KEYS */;
INSERT INTO `land_tours` (`id`, `user_id`, `name`, `slug`, `caption`, `description`, `phone`, `price`, `trippal_price`, `customer_price`, `promote`, `departure_id`, `destination_id`, `days`, `rating`, `thumbnail`, `media`, `fb_content`, `icon_list`, `start_date`, `end_date`, `status`, `term`, `created`, `modified`) VALUES
	(1, 2, 'Tour đi Đại Nội', 'tour-di-dai-noi', '["Thời tiết rất đẹp","Hướng dẫn viên nhiệt tình"]', '<p>V&agrave;o Đại Nội mặc &aacute;o vua</p>\r\n<p>Phụ nữ đ&oacute;ng vai c&ocirc;ng t&uacute;a</p>', '', 0, 0, 0, 10, 1, 1, 0, 5, '', '["files/uploads/1541823812-room 5.jpg","files/uploads/1541823812-room 6.jpg","files/uploads/1541823812-room 7.jpg","files/uploads/1541823813-room 8.jpg","files/uploads/1541823813-room 9.jpg","files/uploads/1541823813-room 10.jpg","files/uploads/1541823814-room 11.jpg","files/uploads/1541823814-room 12.jpg","files/uploads/1541823814-room 13.jpg"]', '', '', '2018-11-10', '2018-11-10', 0, '<p>Ch&iacute;nh cmn s&aacute;ch đổi trả</p>', '2018-11-10 04:25:58', '2018-11-10 04:33:18'),
	(6, 2, 'test test', 'test-test', '["short gì gì đó","short gì gì đó"]', '<p>abcd</p>', '', 0, 130, 150, 10, 4, 4, 0, 4, 'files/uploads/1545211695-8.jpg', '["files/uploads/1545211667-1.jpg","files/uploads/1545211667-2.jpg","files/uploads/1545211667-3.jpg","files/uploads/1545211667-4.jpg","files/uploads/1545211667-5.jpg","files/uploads/1545211669-6.jpg","files/uploads/1545211670-7.jpg","files/uploads/1545211670-8.jpg","files/uploads/1545211672-9.jpg"]', '', '', '2018-12-19', '2018-12-19', 0, '<p>12345</p>', '2018-12-19 16:28:15', '2018-12-19 16:28:15'),
	(7, 2, 'test', 'test', '["123456"]', '<p>1234</p>', '0945554266', 0, 130, 150, 10, 4, 4, 0, 5, 'files/uploads/1545211923-9.jpg', '["files/uploads/1545211824-1.jpg","files/uploads/1545211824-2.jpg","files/uploads/1545211824-3.jpg","files/uploads/1545211824-4.jpg","files/uploads/1545211824-5.jpg","files/uploads/1545211825-6.jpg","files/uploads/1545211826-7.jpg","files/uploads/1545211827-8.jpg","files/uploads/1545211828-9.jpg"]', '', '', '2018-12-20', '2018-12-27', 0, '<p>123456</p>', '2018-12-19 16:32:03', '2018-12-19 16:32:03'),
	(8, 2, 'tour test', 'tour-test', '[{"icon":"fas fa-plane","content":"12313"}]', '<p>&aacute;das</p>', '0945554266', 0, 130, 150, 10, 4, 4, 0, 3, 'files/uploads/1545302711-5.jpg', '', '2345', '', '2018-12-20', '2018-12-20', 0, '<p>&aacute;d</p>', '2018-12-20 17:45:11', '2018-12-20 17:45:11');
/*!40000 ALTER TABLE `land_tours` ENABLE KEYS */;

-- Dumping structure for table trippal.locations
CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `map` text NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `media` text NOT NULL,
  `hotel_count` int(4) NOT NULL,
  `combo_count` int(4) NOT NULL,
  `landtour_count` int(4) NOT NULL,
  `voucher_count` int(4) NOT NULL,
  `is_featured` int(2) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- Dumping data for table trippal.locations: ~7 rows (approximately)
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` (`id`, `name`, `slug`, `description`, `map`, `thumbnail`, `media`, `hotel_count`, `combo_count`, `landtour_count`, `voucher_count`, `is_featured`, `created`, `modified`) VALUES
	(4, 'Hà Nội', 'ha-noi', 'Thủ đô', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d59587.97785448771!2d105.80194413492788!3d21.02273601629448!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab9bd9861ca1%3A0xe7887f7b72ca17a9!2zSGFub2ksIEhvw6BuIEtp4bq_bSwgSGFub2k!5e0!3m2!1sen!2s!4v1543652942480" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>', 'files/uploads/1543652984-photo1518495993327-1518495993327705352127.jpg', '', 5, 3, 3, 1, 1, '2018-12-01 15:29:44', '2018-12-06 16:13:22'),
	(5, 'Hồ Chí Minh', 'ho-chi-minh', 'Thành phố Hồ Chí Minh (thường được gọi là Sài Gòn) là một thành phố ở miền nam Việt Nam nổi tiếng với vai trò nòng cốt trong chiến tranh Việt Nam. Sài Gòn cũng được biết đến với địa danh của thực dân Pháp, trong đó có Nhà thờ Đức Bà được xây dựng hoàn toà', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d501725.3382259616!2d106.41502848792288!3d10.755341089952083!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317529292e8d3dd1%3A0xf15f5aad773c112b!2sHo+Chi+Minh+City%2C+Vietnam!5e0!3m2!1sen!2s!4v1543653036038" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>', 'files/uploads/1543653065-tphcm-1505627221869.jpg', '', 1, 1, 0, 2, 1, '2018-12-01 15:31:05', '2018-12-01 15:31:05'),
	(6, 'Đà Nẵng', 'da-nang', 'Đà Nẵng là một thành phố thuộc trung ương, nằm trong vùng Nam Trung Bộ, Việt Nam, là trung tâm kinh tế, tài chính, chính trị, văn hoá, du lịch, xã hội, giáo dục, đào tạo, khoa học và công nghệ, y tế chuyên sâu của khu vực miền Trung', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d61349.62126476666!2d108.17168640746648!3d16.047248394807504!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314219c792252a13%3A0xfc14e3a044436487!2zRGEgTmFuZywgSOG6o2kgQ2jDonUsIERhIE5hbmcsIFZpZXRuYW0!5e0!3m2!1sen!2s!4v1543653227700" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>', 'files/uploads/1543653246-17180024054_5ce408a6ea_c.jpg', '', 2, 3, 0, 0, 1, '2018-12-01 15:34:06', '2018-12-01 15:34:06'),
	(7, 'Huế', 'hue', 'Huế là đô thị cấp quốc gia của Việt Nam và từng là kinh đô của Việt Nam thời phong kiến dưới triều nhà Nguyễn. Hiện nay, thành phố là trung tâm về nhiều mặt của miền Trung như văn hoá, chính trị, y tế, giáo dục, du lịch, khoa học', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d61222.96854771765!2d107.54209355859076!3d16.453469860520382!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3141a115e1a7935f%3A0xbf3b50af70b5c7b7!2sHu%E1%BA%BF%2C+Thua+Thien+Hue%2C+Vietnam!5e0!3m2!1sen!2s!4v1543653257419" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>', 'files/uploads/1543653292-57-1_xlmr.jpg', '', 0, 0, 0, 0, 1, '2018-12-01 15:34:52', '2018-12-01 15:34:52'),
	(8, 'Phú Quốc ', 'phu-quoc', 'Phú Quốc, còn được mệnh danh là Đảo Ngọc, là hòn đảo lớn nhất của Việt Nam, cũng là đảo lớn nhất trong quần thể 22 đảo tại đây, nằm trong vịnh Thái Lan. Đảo Phú Quốc cùng với các đảo khác tạo thành huyện đảo Phú Quốc trực thuộc tỉnh Kiên Giang.', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d251289.7061112148!2d103.81733648128296!3d10.22915336566681!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a78c62b49eda17%3A0x8aa79fbbdd72cdb!2zUGjDuiBRdeG7kWM!5e0!3m2!1sen!2s!4v1543653913036" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>', 'files/uploads/1543653953-tf_170812032421_614774.jpg', '', 0, 0, 0, 0, 1, '2018-12-01 15:45:53', '2018-12-01 15:45:53'),
	(9, 'SaPa', 'sapa', 'Sa Pa là một thị trấn vùng cao thuộc huyện Sa Pa, tỉnh Lào Cai, Việt Nam. Đây là vùng được biết đến là có cảnh quan đẹp, thu hút du lịch.', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d59042.55124655058!2d103.78191874647456!3d22.34760733639915!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x36cd416833ee9ad5%3A0xe1f42da2bbc76727!2stt.+Sa+Pa%2C+Sa+Pa%2C+Lao+Cai%2C+Vietnam!5e0!3m2!1sen!2s!4v1543653991746" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>', 'files/uploads/1543654152-Travelbay_Vietnam_Tours_-_Rice_Fields_Sapa.jpg', '', 0, 0, 0, 0, 1, '2018-12-01 15:49:12', '2018-12-01 15:49:12'),
	(10, 'Nha Trang', 'nha-trang', 'Nha Trang là một thành phố ven biển và là trung tâm chính trị, kinh tế, văn hóa, khoa học kỹ thuật và du lịch của tỉnh Khánh Hòa, Việt Nam. Trước khi trở thành phần đất của Việt Nam, Nha Trang thuộc về Chiêm Thành. ', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d249525.44778403552!2d109.10641382999812!3d12.25943461453622!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3170677811cc886f%3A0x5c4bbc0aa81edcb9!2sNha+Trang%2C+Khanh+Hoa+Province%2C+Vietnam!5e0!3m2!1sen!2s!4v1543654201628" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>', 'files/uploads/1543654256-luxury_hotel_spa_4_star_center_beach_nha_trang_bay.jpg', '', 0, 0, 0, 0, 1, '2018-12-01 15:50:56', '2018-12-01 15:50:56');
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;

-- Dumping structure for table trippal.price_hotels
CREATE TABLE IF NOT EXISTS `price_hotels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `price` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table trippal.price_hotels: ~19 rows (approximately)
/*!40000 ALTER TABLE `price_hotels` DISABLE KEYS */;
INSERT INTO `price_hotels` (`id`, `hotel_id`, `start_date`, `end_date`, `price`, `created`, `modified`) VALUES
	(1, 3, '2018-11-14', '2018-11-30', 4000000, '2018-11-14 08:55:19', '2018-11-21 10:20:07'),
	(2, 3, '2018-12-01', '2018-12-31', 3800000, '2018-11-14 08:55:19', '2018-11-21 10:23:00'),
	(3, 5, '2018-11-20', '2018-11-20', 120, '2018-11-20 10:36:03', '2018-11-20 10:36:03'),
	(4, 6, '2018-11-20', '2018-11-20', 1500000, '2018-11-20 10:51:35', '2018-11-21 10:24:18'),
	(5, 6, '2018-09-13', '2018-10-25', 1000000, '2018-11-20 10:58:01', '2018-11-21 10:24:18'),
	(6, 6, '2018-11-20', '2018-11-20', 1500000, '2018-11-20 10:58:01', '2018-11-21 10:24:18'),
	(7, 7, '2018-11-20', '2018-11-30', 3000000, '2018-11-20 11:13:33', '2018-11-20 11:15:46'),
	(8, 7, '2018-12-01', '2018-12-31', 2500000, '2018-11-20 11:15:46', '2018-11-20 11:15:46'),
	(9, 13, '2018-12-01', '2019-12-31', 10000000, '2018-12-04 16:33:39', '2018-12-04 16:33:39'),
	(10, 14, '2019-04-15', '2019-09-15', 650000, '2018-12-07 10:34:06', '2018-12-07 10:34:06'),
	(11, 14, '2018-12-07', '2019-04-14', 450000, '2018-12-07 10:34:06', '2018-12-07 10:34:06'),
	(12, 15, '2018-12-17', '2019-04-14', 450000, '2018-12-07 10:35:42', '2018-12-07 10:35:42'),
	(13, 14, '2019-09-16', '2019-12-31', 450000, '2018-12-07 10:38:41', '2018-12-07 10:38:41'),
	(14, 16, '2018-12-07', '2019-04-14', 700000, '2018-12-07 11:33:54', '2018-12-07 11:33:54'),
	(15, 16, '2019-04-15', '2019-09-30', 900000, '2018-12-07 11:33:54', '2018-12-07 11:33:54'),
	(16, 16, '2019-10-01', '2019-12-31', 700000, '2018-12-07 11:33:54', '2018-12-07 11:33:54'),
	(17, 17, '2018-12-21', '2019-01-23', 6000000, '2018-12-20 09:56:14', '2018-12-20 09:56:14'),
	(18, 18, '2018-12-20', '2018-12-20', 6000000, '2018-12-20 11:01:51', '2018-12-20 11:01:51'),
	(19, 19, '2018-12-20', '2018-12-20', 6000000, '2018-12-20 15:25:56', '2018-12-20 15:25:56'),
	(20, 20, '2018-12-20', '2018-12-20', 120000, '2018-12-20 16:19:14', '2018-12-20 16:19:14'),
	(21, 21, '2019-01-02', '2019-01-02', 120000, '2019-01-02 10:23:56', '2019-01-02 10:23:56'),
	(22, 22, '2019-01-09', '2019-02-28', 1000000, '2019-01-09 09:41:43', '2019-01-09 09:41:43');
/*!40000 ALTER TABLE `price_hotels` ENABLE KEYS */;

-- Dumping structure for table trippal.price_rooms
CREATE TABLE IF NOT EXISTS `price_rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `price` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table trippal.price_rooms: ~7 rows (approximately)
/*!40000 ALTER TABLE `price_rooms` DISABLE KEYS */;
INSERT INTO `price_rooms` (`id`, `room_id`, `start_date`, `end_date`, `price`, `created`, `modified`) VALUES
	(1, 1, '2018-10-15', '2018-10-18', 1500000, '2018-10-16 04:33:26', '2018-10-16 04:33:26'),
	(2, 1, '2018-10-19', '2018-10-24', 1300000, '2018-10-16 04:33:26', '2018-10-16 04:33:26'),
	(3, 1, '2018-10-25', '2018-10-31', 1100000, '2018-10-16 04:33:26', '2018-10-16 04:33:26'),
	(4, 2, '2018-10-18', '2018-10-31', 1500000, '2018-10-16 04:33:26', '2018-10-16 04:33:26'),
	(5, 3, '2018-10-17', '2018-10-31', 700000, '2018-10-17 07:18:15', '2018-10-17 07:18:15'),
	(6, 4, '2018-11-09', '2018-11-30', 10000000, '2018-11-09 04:37:11', '2018-11-09 04:37:11'),
	(7, 4, '2018-12-01', '2018-12-31', 8000000, '2018-11-09 04:38:37', '2018-11-09 04:38:37');
/*!40000 ALTER TABLE `price_rooms` ENABLE KEYS */;

-- Dumping structure for table trippal.promotes
CREATE TABLE IF NOT EXISTS `promotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `description` text COLLATE utf8mb4_bin NOT NULL,
  `type` int(4) NOT NULL,
  `object_id` int(11) NOT NULL,
  `num_booking` int(2) NOT NULL,
  `num_share` int(2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `revenue` bigint(20) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table trippal.promotes: ~4 rows (approximately)
/*!40000 ALTER TABLE `promotes` DISABLE KEYS */;
INSERT INTO `promotes` (`id`, `title`, `slug`, `description`, `type`, `object_id`, `num_booking`, `num_share`, `start_date`, `end_date`, `revenue`, `created`, `modified`) VALUES
	(1, 'Đăng ký và Kết nối Fanpage', '', 'sdkfjsldfjwkiefwe', 31, 0, 0, 0, '2019-01-01', '2019-06-30', 100000, '2019-01-03 10:55:58', '2019-01-03 10:55:58'),
	(2, 'Số Booking/share trong khoảng TG', '', 'Số Booking/share trong khoảng TG', 32, 0, 5, 0, '2019-01-03', '2019-06-30', 200000, '2019-01-03 10:57:08', '2019-01-03 10:57:08'),
	(3, 'Số Booking/share Khách sạn trong khoảng TG', '', 'sdfsdfsd', 33, 3, 4, 0, '2019-01-03', '2019-06-30', 200000, '2019-01-03 10:57:43', '2019-01-04 11:10:49'),
	(4, 'Số Booking/share Điểm đến trong khoảng TG', '', 'sadf 2t4 23t w2e fwsdf sd', 34, 6, 5, 0, '2019-01-03', '2019-06-30', 150000, '2019-01-03 10:58:25', '2019-01-03 18:57:41'),
	(5, 'huấn cmn luyện ctv', '', 'qưeqwe', 33, 3, 11, 11, '2019-01-16', '2019-03-02', 1111111, '2019-01-07 10:08:45', '2019-01-07 10:09:54');
/*!40000 ALTER TABLE `promotes` ENABLE KEYS */;

-- Dumping structure for table trippal.questions
CREATE TABLE IF NOT EXISTS `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `answer` text COLLATE utf8mb4_bin NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table trippal.questions: ~0 rows (approximately)
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` (`id`, `user_id`, `content`, `answer`, `created`, `modified`) VALUES
	(1, 1, 'Làm Cộng tác viên được gì?', '[{"content":"Kinh nghiệm"},{"content":"Tiền chứ còn cmg nữa"},{"content":"Tiền","is_correct":"on"},{"content":"Money"}]', '2018-10-25 08:15:26', '2018-10-25 08:15:59');
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;

-- Dumping structure for table trippal.request_vouchers
CREATE TABLE IF NOT EXISTS `request_vouchers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `time` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `price` varchar(64) COLLATE utf8mb4_bin NOT NULL,
  `full_name` varchar(64) COLLATE utf8mb4_bin NOT NULL,
  `phone` varchar(64) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(64) COLLATE utf8mb4_bin NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table trippal.request_vouchers: ~0 rows (approximately)
/*!40000 ALTER TABLE `request_vouchers` DISABLE KEYS */;
/*!40000 ALTER TABLE `request_vouchers` ENABLE KEYS */;

-- Dumping structure for table trippal.reviews
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `rating` int(2) NOT NULL,
  `price_start` int(10) NOT NULL,
  `price_end` int(10) NOT NULL,
  `status` int(2) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table trippal.reviews: ~2 rows (approximately)
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` (`id`, `category_id`, `location_id`, `title`, `slug`, `caption`, `content`, `thumbnail`, `rating`, `price_start`, `price_end`, `status`, `created`, `modified`) VALUES
	(1, 2, 2, 'Huế mộng mơ', 'hue-mong-mo', 'Đẹp lắm', '<p>Hehehehehehe</p>\r\n<p><img src="http://localhost/trippal/files/uploads/1539083209-film.jpg" alt="" width="714" height="380" /></p>', 'files/uploads/1539082340-sport.jpg', 0, 1000000, 3000000, 0, '2018-10-09 10:52:21', '2018-10-09 11:30:35'),
	(2, 0, 3, 'Novotel tuyệt vời', 'novotel-tuyet-voi', 'Đẹp và sang trọng ', '<p>Đẹp qu&aacute; mọi người ơi</p>', 'files/uploads/1541739460-room 10.jpg', 5, 4000000, 6000000, 1, '2018-11-09 04:57:40', '2018-11-09 05:00:49');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;

-- Dumping structure for table trippal.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table trippal.roles: ~3 rows (approximately)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `name`, `created`, `modified`) VALUES
	(1, 'Administrator', '2018-10-03 08:33:46', '2018-10-03 08:33:46'),
	(2, 'Sale Admin', '2018-10-03 08:35:09', '2018-10-03 08:35:09'),
	(3, 'Cộng tác viên', '2018-10-03 08:35:48', '2018-10-25 03:30:41');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Dumping structure for table trippal.rooms
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `area` float NOT NULL,
  `num_bed` int(2) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `media` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table trippal.rooms: ~4 rows (approximately)
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` (`id`, `hotel_id`, `name`, `slug`, `area`, `num_bed`, `thumbnail`, `media`, `created`, `modified`) VALUES
	(1, 1, 'Phòng tổng thống', 'phong-tong-thong', 100, 2, 'files/uploads/1539604681-sport.jpg', '', '2018-10-15 11:58:01', '2018-10-16 04:31:59'),
	(2, 1, 'Phòng Thương Gia', 'phong-thuong-gia', 80, 1, 'files/uploads/1539664406-Culture.jpg', '', '2018-10-16 04:33:26', '2018-10-16 04:33:26'),
	(3, 2, 'Phòng cao cấp', 'phong-cao-cap', 30, 2, 'files/uploads/1539760694-comic.jpg', '', '2018-10-17 07:18:14', '2018-10-17 07:18:14'),
	(4, 3, 'Phòng hạng nhất', 'phong-hang-nhat', 120, 3, 'files/uploads/1541738230-room 4.jpg', '["files/uploads/1541738223-room 6.jpg","files/uploads/1541738224-room 8.jpg","files/uploads/1541738224-room 9.jpg","files/uploads/1541738223-room 10.jpg","files/uploads/1541738225-room 11.jpg","files/uploads/1541738225-room 12.jpg","files/uploads/1541738225-room 13.jpg"]', '2018-11-09 04:37:11', '2018-11-09 04:38:37');
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;

-- Dumping structure for table trippal.rooms_categories
CREATE TABLE IF NOT EXISTS `rooms_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- Dumping data for table trippal.rooms_categories: ~41 rows (approximately)
/*!40000 ALTER TABLE `rooms_categories` DISABLE KEYS */;
INSERT INTO `rooms_categories` (`id`, `room_id`, `category_id`) VALUES
	(9, 1, 39),
	(12, 1, 4),
	(13, 1, 13),
	(14, 1, 14),
	(15, 1, 22),
	(16, 1, 43),
	(17, 2, 3),
	(18, 2, 4),
	(19, 2, 12),
	(20, 2, 17),
	(21, 2, 18),
	(22, 2, 19),
	(23, 2, 27),
	(24, 2, 28),
	(25, 2, 33),
	(26, 2, 37),
	(27, 3, 8),
	(28, 3, 10),
	(29, 3, 16),
	(30, 3, 17),
	(31, 3, 23),
	(32, 3, 25),
	(33, 3, 26),
	(34, 3, 27),
	(35, 3, 34),
	(36, 3, 36),
	(37, 3, 38),
	(38, 3, 42),
	(39, 3, 43),
	(40, 4, 4),
	(41, 4, 6),
	(42, 4, 8),
	(43, 4, 9),
	(44, 4, 10),
	(45, 4, 20),
	(46, 4, 21),
	(47, 4, 22),
	(48, 4, 27),
	(49, 4, 32),
	(50, 4, 33),
	(51, 4, 46);
/*!40000 ALTER TABLE `rooms_categories` ENABLE KEYS */;

-- Dumping structure for table trippal.sockets
CREATE TABLE IF NOT EXISTS `sockets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `resourceId` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table trippal.sockets: ~0 rows (approximately)
/*!40000 ALTER TABLE `sockets` DISABLE KEYS */;
/*!40000 ALTER TABLE `sockets` ENABLE KEYS */;

-- Dumping structure for table trippal.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(4) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` varchar(255) NOT NULL,
  `screen_name` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` text NOT NULL,
  `email_access_code` varchar(128) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `fbid` varchar(128) NOT NULL,
  `zalo` varchar(64) NOT NULL,
  `signature` text NOT NULL,
  `is_active` int(2) NOT NULL,
  `ref_code` varchar(128) NOT NULL,
  `revenue` bigint(20) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- Dumping data for table trippal.users: ~8 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `role_id`, `parent_id`, `username`, `password`, `screen_name`, `email`, `phone`, `email_access_code`, `access_token`, `avatar`, `fbid`, `zalo`, `signature`, `is_active`, `ref_code`, `revenue`, `created`, `modified`) VALUES
	(1, 1, 0, 'admin', '$2y$10$Up8TGLboF/sj9eisGCWJE.SvnKMztBeyd4byZZR02UP8C7Mm5MEYW', 'Cường Bùi Việt', 'cuongbv90@gmail.com', '', '112421423423423423', 'EAAKx59Tq1vYBAA2nkz4nhx5tQ1qoPrqQAaBVt2eSSj2ISIEuIl0OJodiWk0iDLJwl3pxa7uIzeoBs1ARqZAZCokekxjJoZAgcVDtW7AEMnPoZBhCHOuZA1KVpyPZBCpiD8jtKJruicZCu7Ba7nA6quX4A0q7ZB3MQeycWhfL30RSBwZDZD', 'https://platform-lookaside.fbsbx.com/platform/profilepic/?asid=2096154020695275&height=800&width=800&ext=1545021818&hash=AeRWtkvw1LaxsS7R', '', '', '', 1, '', 0, '2018-10-03 09:10:08', '2018-11-17 04:43:41'),
	(2, 2, 0, 'sale_admin', '$2y$10$k.ZxlyAKyqz2ufu.EV8yRO5Pv73QasCun/V9wV3c2FQ9rMSXpt81.', 'Sale Admin', 'zzphonixzz@gmail.com', '', 'mwhbzgcikbarqvgw', '', '', '', '', '<p>T&ecirc;n c&ocirc;ng ty: C&Ocirc;NG TY CỔ PHẦN DU LỊCH LI&Ecirc;N MINH VIỆT NAM<br />M&atilde; số thuế: 0108205732</p>\r\n<p>Địa chỉ xuất h&oacute;a đơn: Số 122 Trần Đại Nghĩa, P. Đồng T&acirc;m, Q. Hai B&agrave; Trưng, H&agrave; Nội</p>\r\n<p>Địa chỉ gửi thư: Số 71 Ch&ugrave;a L&aacute;ng, P. L&aacute;ng Thường, Q Đống Đa, H&agrave; Nội</p>\r\n<p>Người nhận: Ms. Quỳnh Anh, SĐT: 0869127716</p>\r\n<p><br />--------------------------------------------------------<br />NGUYỄN QUỲNH ANH | Sales &amp; Marketing | VIMI Travel</p>\r\n<p>Số 71 Ch&ugrave;a L&aacute;ng, P. L&aacute;ng Thượng, Q. Đống Đa, TP H&agrave; Nội</p>\r\n<p>DĐ: (+84) 0869127716| Email: sales2.vimitravel@gmail.com.</p>', 1, '', 1801350, '2018-10-03 10:58:59', '2019-01-16 11:49:19'),
	(3, 3, 2, 'ctv', '$2y$10$k.ZxlyAKyqz2ufu.EV8yRO5Pv73QasCun/V9wV3c2FQ9rMSXpt81.', 'GonDaR', 'zzphonixzz@gmail.com', '12344567', 'sdfdsdfsdfsd', 'EAAKx59Tq1vYBAJ2WfxCY4zZC6DHKZBqw896aW9M3rZASAsSpZBXNHi8NxMONrnPhSiwhAoa2LIZBfZCEcOvWSNT7Od7ZBuv4AMnwgDm65vcdUG0GGSupaz6vR5qT9NZAzHdLKuuvjUTGq0QJK7kqjrX4Hkwi31FvRSQD5BfVfzbdws5nSB3JM3ri', 'https://platform-lookaside.fbsbx.com/platform/profilepic/?asid=2096154020695275&height=800&width=800&ext=1545021818&hash=AeRWtkvw1LaxsS7R', '1956545287747997', '0945554266', '', 1, 'TJ7P1I8Q7LTHIR057H2IILSA', 700000, '2018-10-24 09:40:21', '2019-01-16 11:34:06'),
	(9, 3, 0, 'fb1956545287747997', '', 'Cường Bùi', 'gaproghe@gmail.com', '', '', 'EAAKx59Tq1vYBAMZCwUfuMPPpZAc0rvWEWovc1LbtwBEEhtwgVNhGRBZAKIOapjl9Yl3KeriEgscV0oKIacP3QxMY3ZCxeDmvWO9ZA2bZBcmiPrzMlVMeCP8vDCpb8hzutPNsIsFNZCR7PLhSkGnyyGaczCZAZBVviWvfkSjzLZBKvrU8xUZBqRDYBdljntdqXLOYGgZD', 'https://platform-lookaside.fbsbx.com/platform/profilepic/?asid=1956545287747997&height=800&width=800&ext=1546675183&hash=AeQKfYBS0csN4Age', 'https://facebook.com/profile.php?1956545287747997', '0973307590', '', 1, 'JSJI7FDPK5HAGCCXXAIQOBBX', 0, '2018-11-17 09:22:49', '2018-12-06 14:59:46'),
	(10, 3, 2, 'ctv2', '$2y$10$cY4655fHly57187O4BhrTupP6Ts2hZw.lZPmjyFYmxnOwEYMnMwNa', 'CTV', 'ctv2@gmail.com', '', '', '', '', '', '', '', 1, 'P3W0K2Y9MZPVRRU2ADAEN7G3', 0, '2018-11-27 18:18:14', '2018-11-27 18:18:14'),
	(11, 3, 2, 'ctv3', '$2y$10$mp8euBHD8iKJfbAoiYGvNuogW4YdL/qoGhmpGYNRC4YxR9u/JJ.dG', 'Cộng Tác viên 3', '923472823423', '', '', '', '', '', '', '', 1, '2f5yNpPOInL0KKDtnDD3zDv6', 0, '2018-11-30 18:05:18', '2018-11-30 18:05:18'),
	(12, 3, 2, 'cuopkiabacon', '$2y$10$19QfxoC1mhhu7yEbpVAY5e1LyfzB1xBI4DemA8dQUpPpIMgWsqUgW', 'Gondar', 'cuongbv90@gmail.com', '0945554266', '', '', '', 'https://www.facebook.com/nam.buihoang.900', '0945554266', '', 1, 'j40ecjNFyBNadSzsvCeLjNnO', 0, '2018-12-14 14:57:20', '2018-12-14 14:57:20'),
	(13, 3, 2, 'ctv1', '$2y$10$LTkcbfpgvGQHGhWP3lAFvuzfauQuR3IsC4XRknxDf.SMwxBst92.W', 'Gondar', 'ctv1@gmail.com', '0945554266', 'mwhbzgcikbarqvgw', '', '', 'https://www.facebook.com/nam.buihoang.900', '0945554266', '', 1, 'mKd4Fzp5xvsyrCzllwvLIjrG', 0, '2019-01-08 09:39:11', '2019-01-08 09:39:11');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Dumping structure for table trippal.user_sessions
CREATE TABLE IF NOT EXISTS `user_sessions` (
  `id` varchar(255) NOT NULL,
  `data` text,
  `user_id` int(10) unsigned DEFAULT NULL,
  `expires` int(10) unsigned DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table trippal.user_sessions: ~1 rows (approximately)
/*!40000 ALTER TABLE `user_sessions` DISABLE KEYS */;
INSERT INTO `user_sessions` (`id`, `data`, `user_id`, `expires`, `created`) VALUES
	('9d4j05rt5c9gtab355337ghcd7', 'Config|a:1:{s:4:"time";i:1547614667;}Auth|a:1:{s:4:"User";a:18:{s:2:"id";i:2;s:7:"role_id";i:2;s:9:"parent_id";i:0;s:8:"username";s:10:"sale_admin";s:11:"screen_name";s:10:"Sale Admin";s:5:"email";s:20:"zzphonixzz@gmail.com";s:5:"phone";s:0:"";s:17:"email_access_code";s:16:"mwhbzgcikbarqvgw";s:12:"access_token";s:0:"";s:6:"avatar";s:0:"";s:4:"fbid";s:0:"";s:4:"zalo";s:0:"";s:9:"signature";s:759:"<p>T&ecirc;n c&ocirc;ng ty: C&Ocirc;NG TY CỔ PHẦN DU LỊCH LI&Ecirc;N MINH VIỆT NAM<br />M&atilde; số thuế: 0108205732</p>\r\n<p>Địa chỉ xuất h&oacute;a đơn: Số 122 Trần Đại Nghĩa, P. Đồng T&acirc;m, Q. Hai B&agrave; Trưng, H&agrave; Nội</p>\r\n<p>Địa chỉ gửi thư: Số 71 Ch&ugrave;a L&aacute;ng, P. L&aacute;ng Thường, Q Đống Đa, H&agrave; Nội</p>\r\n<p>Người nhận: Ms. Quỳnh Anh, SĐT: 0869127716</p>\r\n<p><br />--------------------------------------------------------<br />NGUYỄN QUỲNH ANH | Sales &amp; Marketing | VIMI Travel</p>\r\n<p>Số 71 Ch&ugrave;a L&aacute;ng, P. L&aacute;ng Thượng, Q. Đống Đa, TP H&agrave; Nội</p>\r\n<p>DĐ: (+84) 0869127716| Email: sales2.vimitravel@gmail.com.</p>";s:9:"is_active";i:1;s:8:"ref_code";s:0:"";s:7:"revenue";i:0;s:7:"created";O:20:"Cake\\I18n\\FrozenTime":3:{s:4:"date";s:26:"2018-10-03 10:58:59.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:16:"Asia/Ho_Chi_Minh";}s:8:"modified";O:20:"Cake\\I18n\\FrozenTime":3:{s:4:"date";s:26:"2019-01-15 16:22:36.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:16:"Asia/Ho_Chi_Minh";}}}Flash|a:0:{}isShowPopupPromote|b:1;', 2, 1547616108, '2019-01-16 10:28:50'),
	('c6icaq5iapvdrk8b9cmommedg6', 'Config|a:1:{s:4:"time";i:1547550822;}Auth|a:1:{s:4:"User";a:18:{s:2:"id";i:3;s:7:"role_id";i:3;s:9:"parent_id";i:2;s:8:"username";s:3:"ctv";s:11:"screen_name";s:6:"GonDaR";s:5:"email";s:20:"zzphonixzz@gmail.com";s:5:"phone";s:8:"12344567";s:17:"email_access_code";s:12:"sdfdsdfsdfsd";s:12:"access_token";s:185:"EAAKx59Tq1vYBAJ2WfxCY4zZC6DHKZBqw896aW9M3rZASAsSpZBXNHi8NxMONrnPhSiwhAoa2LIZBfZCEcOvWSNT7Od7ZBuv4AMnwgDm65vcdUG0GGSupaz6vR5qT9NZAzHdLKuuvjUTGq0QJK7kqjrX4Hkwi31FvRSQD5BfVfzbdws5nSB3JM3ri";s:6:"avatar";s:137:"https://platform-lookaside.fbsbx.com/platform/profilepic/?asid=2096154020695275&height=800&width=800&ext=1545021818&hash=AeRWtkvw1LaxsS7R";s:4:"fbid";s:16:"1956545287747997";s:4:"zalo";s:10:"0945554266";s:9:"signature";s:0:"";s:9:"is_active";i:1;s:8:"ref_code";s:24:"TJ7P1I8Q7LTHIR057H2IILSA";s:7:"revenue";i:0;s:7:"created";O:20:"Cake\\I18n\\FrozenTime":3:{s:4:"date";s:26:"2018-10-24 09:40:21.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:16:"Asia/Ho_Chi_Minh";}s:8:"modified";O:20:"Cake\\I18n\\FrozenTime":3:{s:4:"date";s:26:"2019-01-15 16:22:03.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:16:"Asia/Ho_Chi_Minh";}}}isShowPopupPromote|b:1;Flash|a:0:{}', 3, 1547552265, '2019-01-15 18:09:39'),
	('m4r73u91i91atmecdg6mianhq5', 'Config|a:1:{s:4:"time";i:1547614165;}Auth|a:1:{s:4:"User";a:18:{s:2:"id";i:1;s:7:"role_id";i:1;s:9:"parent_id";i:0;s:8:"username";s:5:"admin";s:11:"screen_name";s:20:"Cường Bùi Việt";s:5:"email";s:19:"cuongbv90@gmail.com";s:5:"phone";s:0:"";s:17:"email_access_code";s:18:"112421423423423423";s:12:"access_token";s:179:"EAAKx59Tq1vYBAA2nkz4nhx5tQ1qoPrqQAaBVt2eSSj2ISIEuIl0OJodiWk0iDLJwl3pxa7uIzeoBs1ARqZAZCokekxjJoZAgcVDtW7AEMnPoZBhCHOuZA1KVpyPZBCpiD8jtKJruicZCu7Ba7nA6quX4A0q7ZB3MQeycWhfL30RSBwZDZD";s:6:"avatar";s:137:"https://platform-lookaside.fbsbx.com/platform/profilepic/?asid=2096154020695275&height=800&width=800&ext=1545021818&hash=AeRWtkvw1LaxsS7R";s:4:"fbid";s:0:"";s:4:"zalo";s:0:"";s:9:"signature";s:0:"";s:9:"is_active";i:1;s:8:"ref_code";s:0:"";s:7:"revenue";i:0;s:7:"created";O:20:"Cake\\I18n\\FrozenTime":3:{s:4:"date";s:26:"2018-10-03 09:10:08.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:16:"Asia/Ho_Chi_Minh";}s:8:"modified";O:20:"Cake\\I18n\\FrozenTime":3:{s:4:"date";s:26:"2018-11-17 04:43:41.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:16:"Asia/Ho_Chi_Minh";}}}', 1, 1547615605, '2019-01-16 11:33:49');
/*!40000 ALTER TABLE `user_sessions` ENABLE KEYS */;

-- Dumping structure for table trippal.user_shares
CREATE TABLE IF NOT EXISTS `user_shares` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` int(2) NOT NULL,
  `object_id` int(11) NOT NULL,
  `object_type` int(2) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table trippal.user_shares: ~1 rows (approximately)
/*!40000 ALTER TABLE `user_shares` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_shares` ENABLE KEYS */;

-- Dumping structure for table trippal.user_transactions
CREATE TABLE IF NOT EXISTS `user_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `revenue` bigint(20) NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table trippal.user_transactions: ~5 rows (approximately)
/*!40000 ALTER TABLE `user_transactions` DISABLE KEYS */;
INSERT INTO `user_transactions` (`id`, `booking_id`, `user_id`, `customer_name`, `revenue`, `reason`, `created`, `modified`) VALUES
	(11, 47, 3, 'Nam Bùi Hoàng', 600000, 'Đặt booking khách sạn ocean thành công', '2019-01-16 10:59:00', '2019-01-16 10:59:00'),
	(12, 46, 2, 'Nam Bùi Hoàng', 600000, 'Đặt booking khách sạn ocean thành công', '2019-01-16 10:59:17', '2019-01-16 10:59:17'),
	(13, 45, 2, 'Nam Bùi Hoàng', 300000, 'Đặt booking khách sạn ocean thành công', '2019-01-16 11:33:54', '2019-01-16 11:33:54'),
	(14, 44, 2, 'Nam Bùi Hoàng', 0, 'Đặt booking khách sạn ocean thành công', '2019-01-16 11:34:00', '2019-01-16 11:34:00'),
	(15, 43, 3, 'Nam Bùi Hoàng', 100000, 'Đặt booking khách sạn ocean thành công', '2019-01-16 11:34:07', '2019-01-16 11:34:07'),
	(16, 48, 2, 'Nam Bùi Hoàng', 900000, 'Đặt booking combo 1 Ocean 1 Aladinle thành công', '2019-01-16 11:35:45', '2019-01-16 11:35:45'),
	(17, 49, 2, 'Nam Bùi Hoàng', 1350, 'Đặt booking voucher voucher đi chơi thành công', '2019-01-16 11:49:19', '2019-01-16 11:49:19');
/*!40000 ALTER TABLE `user_transactions` ENABLE KEYS */;

-- Dumping structure for table trippal.vouchers
CREATE TABLE IF NOT EXISTS `vouchers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `caption` text NOT NULL,
  `description` text NOT NULL,
  `price` int(11) NOT NULL,
  `trippal_price` int(11) NOT NULL,
  `customer_price` int(11) NOT NULL,
  `promote` float NOT NULL,
  `departure_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `days_attended` int(11) NOT NULL,
  `rating` float NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `media` text NOT NULL,
  `fb_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `icon_list` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` int(2) NOT NULL DEFAULT '0',
  `term` text NOT NULL,
  `is_featured` int(2) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table trippal.vouchers: ~4 rows (approximately)
/*!40000 ALTER TABLE `vouchers` DISABLE KEYS */;
INSERT INTO `vouchers` (`id`, `user_id`, `hotel_id`, `name`, `slug`, `caption`, `description`, `price`, `trippal_price`, `customer_price`, `promote`, `departure_id`, `destination_id`, `days_attended`, `rating`, `thumbnail`, `media`, `fb_content`, `icon_list`, `start_date`, `end_date`, `status`, `term`, `is_featured`, `created`, `modified`) VALUES
	(1, 2, 3, 'Voucher nghỉ dưỡng ở Novotel', 'voucher-nghi-duong-o-novotel', '["Khách sạn view biển"]', '<p>Đẹp v&atilde;i</p>', 4000000, 4500000, 5000000, 15, 2, 3, 4, 5, 'files/uploads/1542793105-room 12.jpg', '["files/uploads/1542793522-room 2.jpg","files/uploads/1542793521-room 3.jpg","files/uploads/1542793521-room 4.jpg","files/uploads/1542793521-room 7.jpg"]', '', '', '2018-11-21', '2018-12-31', 0, '', 0, '2018-11-21 09:38:25', '2018-11-21 09:45:26'),
	(2, 2, 3, 'voucher đi chơi', 'voucher-di-choi', '[{"icon":"fas fa-plane","content":"ngắn vãi lòn"},{"icon":"far fa-clock","content":"213"}]', '<p>nội dung combo</p>', 0, 130, 150, 10, 4, 5, 3, 5, 'files/uploads/1544414216-Pikachu-Running-Picture.png', '["files/uploads/1544414189-617241-Books-1381638164-211-640x480.JPG","files/uploads/1544414189-44755781_1564412763704990_3773877274961510400_n.jpg","files/uploads/1544414189-44838467_732108043834302_1815030690840838144_n.jpg"]', '', '', '2018-12-31', '2019-01-02', 0, '', 0, '2018-12-10 10:56:56', '2018-12-20 17:13:23'),
	(3, 2, 3, 'voucher', 'voucher', '["short gì gì đó"]', '<p>abcd 1234</p>', 150, 130, 150, 12, 4, 4, 3, 5, 'files/uploads/1544429362-Pikachu-Running-Picture.png', '["files/uploads/1544429331-617241-Books-1381638164-211-640x480.JPG","files/uploads/1544429331-44755781_1564412763704990_3773877274961510400_n.jpg","files/uploads/1544429331-44838467_732108043834302_1815030690840838144_n.jpg"]', '', '', '2018-12-10', '2018-12-12', 0, '', 0, '2018-12-10 15:09:22', '2018-12-10 15:09:22'),
	(7, 2, 3, 'tét', 'tet', '["mô tả"]', '<p>&aacute;dasd</p>', 20000, 2000, 100000, 10, 4, 5, 4, 5, 'files/uploads/1544691853-Pikachu-Running-Picture.png', '["files/uploads/1544691784-44838467_732108043834302_1815030690840838144_n.jpg"]', '', '', '2018-12-05', '2018-12-12', 0, '', 0, '2018-12-13 16:04:13', '2018-12-13 16:04:13');
/*!40000 ALTER TABLE `vouchers` ENABLE KEYS */;

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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- Dumping data for table trippal.withdraws: ~0 rows (approximately)
/*!40000 ALTER TABLE `withdraws` DISABLE KEYS */;
INSERT INTO `withdraws` (`id`, `user_id`, `amount`, `bank_account`, `status`, `created`, `modified`) VALUES
	(21, 3, 10000, '12346', 0, '2018-12-07 16:06:11', '2018-12-07 16:06:11');
/*!40000 ALTER TABLE `withdraws` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
