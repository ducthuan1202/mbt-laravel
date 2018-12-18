-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 18, 2018 at 04:55 PM
-- Server version: 5.7.21
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mbt_laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `cares`
--

DROP TABLE IF EXISTS `cares`;
CREATE TABLE IF NOT EXISTS `cares` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `start_date` date DEFAULT NULL COMMENT 'ngày gọi chăm sóc',
  `end_date` date DEFAULT NULL COMMENT 'ngày hẹn gọi lại',
  `customer_note` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'mô tả về KH',
  `status` int(11) NOT NULL COMMENT '[1: báo giá, 2: xin viêc, ..., 13: cmsn]',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cares`
--

INSERT INTO `cares` (`id`, `user_id`, `customer_id`, `start_date`, `end_date`, `customer_note`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 4, '2018-12-16', '2018-12-16', 'Mô tả : không có gì', 1, '2018-12-15 19:04:39', '2018-12-17 05:35:46'),
(2, 1, 2, '2018-12-10', '2018-12-16', 'Khách hàng tiềm năng', 3, '2018-12-15 19:12:07', '2018-12-17 05:34:31'),
(3, 1, 2, '2018-12-05', '2018-12-12', 'đã ok', 5, '2018-12-15 19:44:11', '2018-12-17 05:34:19'),
(4, 2, 3, '2018-12-11', '2018-12-13', 'ok', 2, '2018-12-15 20:27:33', '2018-12-15 22:13:56'),
(5, 1, 4, '2018-12-13', '2018-12-20', 'ok', 1, '2018-12-15 20:28:34', '2018-12-15 22:13:03');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
CREATE TABLE IF NOT EXISTS `cities` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Hà Nội', '2018-12-15 04:44:51', '2018-12-15 04:44:51'),
(2, 'Vĩnh Phúc', '2018-12-15 08:45:30', '2018-12-15 08:45:30'),
(3, 'Phú Thọ', '2018-12-15 08:46:07', '2018-12-15 08:46:07'),
(4, 'Sơn La', '2018-12-15 08:46:18', '2018-12-15 08:46:18'),
(5, 'Hà Giang', '2018-12-15 08:46:23', '2018-12-15 08:46:23'),
(6, 'Tuyên Quang', '2018-12-15 08:46:30', '2018-12-15 08:46:30'),
(7, 'Vinh', '2018-12-15 08:46:34', '2018-12-15 08:46:34'),
(8, 'Huế', '2018-12-15 08:46:37', '2018-12-15 08:46:37'),
(9, 'Nha Trang', '2018-12-15 08:46:41', '2018-12-15 08:46:41'),
(10, 'Hải Phòng', '2018-12-15 08:46:46', '2018-12-15 08:46:46'),
(11, 'Bắc Ninh', '2018-12-15 08:46:50', '2018-12-15 08:46:50'),
(12, 'Bắc Giang', '2018-12-15 08:46:56', '2018-12-15 08:46:56');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'mã KH',
  `user_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'địa chỉ chi tiết',
  `name` varchar(63) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(63) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Chức vụ',
  `mobile` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `average_sale` int(11) DEFAULT NULL COMMENT 'doanh số trung bình',
  `status` tinyint(1) NOT NULL COMMENT '[1: đã mua, 2: chưa mua]',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `code`, `user_id`, `city_id`, `company`, `address`, `name`, `position`, `mobile`, `average_sale`, `status`, `created_at`, `updated_at`) VALUES
(2, 'MBT-KH00002', 3, 2, 'Sài Gòn Smile', 'Ngõ 63, Lê Đức Thọ', 'Triệu Thị Huyền Trang', 'Kế Toán', '0974477193', 0, 1, '2018-12-15 08:40:38', '2018-12-17 09:54:25'),
(3, 'MBT-KH00003', 2, 5, 'TNHH Tam Mao', NULL, 'Nguyễn Văn Tam', 'Trợ lý giám đốc', '0998856985', 0, 2, '2018-12-15 20:07:08', '2018-12-15 21:27:54'),
(4, 'MBT-KH00004', 1, 1, 'Bênh Viện quân y 108', 'bệnh viên 108', 'Phan Thanh Tùng', 'Bác Sỹ', '0974556655', 1580000, 1, '2018-12-15 20:08:52', '2018-12-15 20:08:52');

-- --------------------------------------------------------

--
-- Table structure for table `debts`
--

DROP TABLE IF EXISTS `debts`;
CREATE TABLE IF NOT EXISTS `debts` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `total_money` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '[1: nợ cũ, 2: nợ mới]',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `debts`
--

INSERT INTO `debts` (`id`, `customer_id`, `order_id`, `total_money`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 345000, 2, '2018-12-18 06:39:29', '2018-12-18 09:52:57'),
(2, 3, 2, 425000, 2, '2018-12-18 06:47:43', '2018-12-18 09:52:50'),
(3, 2, NULL, 6430, 1, '2018-12-18 07:13:27', '2018-12-18 07:31:05'),
(4, 4, NULL, 64000, 1, '2018-12-18 07:27:23', '2018-12-18 07:30:38');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2018_12_10_023530_create_users_table', 1),
(2, '2018_12_10_023531_create_password_resets_table', 1),
(3, '2018_12_10_023533_create_cities', 1),
(4, '2018_12_10_023535_create_customers', 1),
(5, '2018_12_10_023537_create_cares', 1),
(6, '2018_12_10_023753_create_price_quotations', 1),
(10, '2018_12_10_023808_create_debts', 2),
(8, '2018_12_10_023827_create_orders', 1),
(9, '2018_12_10_023858_create_payment_schedules', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'mã đơn hàng',
  `user_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `total_money` int(11) NOT NULL,
  `power` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'công suất',
  `voltage_input` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'điện áp vào',
  `voltage_output` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'điện áp ra',
  `standard_output` varchar(31) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'tiêu chuẩn xuất máy',
  `standard_real` varchar(31) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'tiêu chuẩn xuất thực',
  `guarantee` int(11) NOT NULL COMMENT 'thời gian bảo hành (tháng)',
  `product_number` varchar(31) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'số máy',
  `product_skin` tinyint(1) NOT NULL COMMENT 'ngoại hình máy',
  `product_type` tinyint(1) NOT NULL COMMENT '[1: máy, 2: tủ/trạm]',
  `setup_at` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'địa chỉ nơi lắp',
  `delivery_at` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'địa chỉ giao hàng',
  `start_date` date DEFAULT NULL COMMENT 'ngày vào sản xuất',
  `shipped_date` date DEFAULT NULL COMMENT 'ngày dự kiến giao hàng',
  `shipped_date_real` date DEFAULT NULL COMMENT 'ngày dự kiến giao hàng',
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ghi chú đơn hàng',
  `status` tinyint(1) NOT NULL COMMENT '[1: đã giao, 2: chưa giao, 3: đã hủy]',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `code`, `user_id`, `customer_id`, `amount`, `price`, `total_money`, `power`, `voltage_input`, `voltage_output`, `standard_output`, `standard_real`, `guarantee`, `product_number`, `product_skin`, `product_type`, `setup_at`, `delivery_at`, `start_date`, `shipped_date`, `shipped_date_real`, `note`, `status`, `created_at`, `updated_at`) VALUES
(1, 'MBT-DH00001', 1, 4, 1, 600000, 600000, '22', '32', '0.35', '8525-2010', '8525-2010', 24, 'mc0211', 4, 2, 'Giáp Thượng - Đức Bác - Sông Lô', 'Giáp Thượng - Đức Bác - Sông Lô', '2018-12-16', '2019-01-16', NULL, 'Giáp Thượng - Đức Bác - Sông Lô', 2, '2018-12-16 09:24:53', '2018-12-18 07:26:21'),
(2, 'MBT-DH00002', 2, 3, 3, 352000, 1056000, '22', '30', '0.32', 'qđ62', 'qđ62', 12, 'mc02223', 3, 2, 'Vĩnh Yên - Vĩnh PHúc', 'Vĩnh Yên - Vĩnh PHúc', '2018-12-16', '2019-01-16', NULL, 'Vĩnh Yên - Vĩnh PHúc', 1, '2018-12-16 09:55:13', '2018-12-16 09:55:13');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_schedules`
--

DROP TABLE IF EXISTS `payment_schedules`;
CREATE TABLE IF NOT EXISTS `payment_schedules` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `money` int(11) NOT NULL,
  `payment_date` timestamp NULL DEFAULT NULL COMMENT 'ngày thanh toán',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '[1:đã thanh toán, 2: hẹn thanh toán, 3: chậm thanh toán]',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `price_quotations`
--

DROP TABLE IF EXISTS `price_quotations`;
CREATE TABLE IF NOT EXISTS `price_quotations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `total_money` int(11) NOT NULL,
  `quotations_date` date NOT NULL COMMENT 'ngày báo giá',
  `power` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'công suất',
  `voltage_input` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'điện áp vào',
  `voltage_output` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'điện áp ra',
  `standard_output` varchar(31) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'tiêu chuẩn xuất máy',
  `guarantee` int(11) NOT NULL COMMENT 'thời gian bảo hành (tháng)',
  `product_skin` tinyint(1) NOT NULL COMMENT 'ngoại hình máy',
  `product_type` tinyint(1) NOT NULL COMMENT '[1: máy, 2: tủ/trạm]',
  `setup_at` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'địa chỉ lắp đặt',
  `delivery_at` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'địa chỉ giao hàng',
  `order_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '[1: đã ký, 2: chưa ký]',
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ghi chú',
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'lý do thất bại hoặc thành công',
  `status` tinyint(1) NOT NULL COMMENT '[1: thành công, 2: thất bại, 3: đang theo]',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `price_quotations`
--

INSERT INTO `price_quotations` (`id`, `user_id`, `customer_id`, `amount`, `price`, `total_money`, `quotations_date`, `power`, `voltage_input`, `voltage_output`, `standard_output`, `guarantee`, `product_skin`, `product_type`, `setup_at`, `delivery_at`, `order_status`, `note`, `reason`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 1, 125000, 125000, '2018-12-10', '25', '35', '0.4', '3079', 12, 3, 1, 'Số 02 Lê Đức Thọ - Hà Nội', 'Số 02 Lê Đức Thọ - Hà Nội', '1', '', '', 3, '2018-12-16 04:12:57', '2018-12-16 04:12:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(63) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(31) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` tinyint(1) NOT NULL COMMENT '[1: admin, 2: quản lý, 3: nhân viên]',
  `status` tinyint(1) NOT NULL COMMENT '[1: hoạt động, 2: bị khóa]',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `mobile`, `email`, `password`, `role`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Đức Thuận', '0974600428', 'ducthuan1202@gmail.com', '$2y$10$Uny1WPEkQ0QygsKLCgTqyuxz5QcCjhwaugC97JxbXkJkAT3wSYiqC', 1, 1, 'eA2CYEUknCaaZZ6UqS9bB5BU5J78b0XcaoaqRTnK7Nss7aUH0vkmp8FLKvc3', '2018-12-15 03:16:31', '2018-12-15 03:58:13'),
(2, 'Nguyễn Đức Thuận', '0969886690', NULL, '$2y$10$Uny1WPEkQ0QygsKLCgTqyuxz5QcCjhwaugC97JxbXkJkAT3wSYiqC', 3, 1, 'KwJcMmQPXN0qOIm1saQS1BFmsijFGgA5GIec4lXDqDvk17O9hSPlsMyJE1jp', '2018-12-15 04:08:30', '2018-12-15 04:08:30'),
(3, 'Doãn Mạnh Tú', '0982838383', NULL, '$2y$10$Xsp0jtQsgxF5XbhQeVIWd.m/WRQlyLwajzKYIe3VKqYlVuMBSLLbC', 3, 1, NULL, '2018-12-17 09:53:56', '2018-12-17 09:53:56');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
