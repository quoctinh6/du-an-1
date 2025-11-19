-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 19, 2025 at 08:29 AM
-- Server version: 8.0.44
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `du_an_1`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `blog_category_id` bigint NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('published','draft') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` bigint NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Casio', 'casio', '2025-11-10 08:29:50', '2025-11-10 08:29:50'),
(2, 'Rolex', 'rolex', '2025-11-10 08:29:50', '2025-11-10 08:29:50'),
(3, 'Seiko', 'seiko', '2025-11-10 08:29:50', '2025-11-10 08:29:50');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint NOT NULL,
  `cart_id` bigint NOT NULL,
  `variant_id` bigint NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'sản phẩm nổi bật', 'san-pham-noi-bat', '2025-11-10 08:54:30', '2025-11-14 08:13:15'),
(2, 'sản phẩm xu hướng', 'san-pham-xu-huong', '2025-11-10 08:54:30', '2025-11-14 14:43:50'),
(3, 'Bộ sưu tập đẹp nhất\n', 'bo-suu-tap-dep-nhat', '2025-11-10 08:54:30', '2025-11-14 14:50:48');

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` bigint NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Black', '#000000', '2025-11-10 08:54:30', '2025-11-17 05:24:30'),
(2, 'Silver', '#C0C0C0', '2025-11-10 08:54:30', '2025-11-17 05:24:30'),
(3, 'Gold', '#FFD700', '2025-11-10 08:54:30', '2025-11-17 05:24:30'),
(4, 'Rose Gold', '#B76E79', '2025-11-10 08:54:30', '2025-11-17 05:24:30'),
(5, 'Blue', '#0000FF', '2025-11-10 08:54:30', '2025-11-17 05:24:30'),
(6, 'White', '#FFFFFF', '2025-11-10 08:54:30', '2025-11-17 05:24:30');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `content` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint DEFAULT NULL,
  `commentable_id` bigint NOT NULL,
  `commentable_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('percent','fixed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(15,2) NOT NULL,
  `usage_limit` int DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `shipping_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','processing','shipped','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method_id` bigint NOT NULL,
  `shipping_method_id` bigint NOT NULL,
  `coupon_id` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `shipping_address`, `phone_number`, `status`, `payment_method_id`, `shipping_method_id`, `coupon_id`, `created_at`, `updated_at`) VALUES
(1, 1, 11550000.00, '123 Đường ABC, Quận 1, TP. HCM', '0909123456', 'processing', 1, 2, NULL, '2025-11-10 08:58:18', '2025-11-10 08:58:18'),
(2, 1, 351530000.00, '456 Đường XYZ, Quận Hoàn Kiếm, Hà Nội', '0909123456', 'pending', 2, 1, NULL, '2025-11-10 08:58:18', '2025-11-10 08:58:18');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint NOT NULL,
  `order_id` bigint NOT NULL,
  `variant_id` bigint NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Thanh toán khi nhận hàng (COD)', '2025-11-10 08:58:18', '2025-11-10 08:58:18'),
(2, 'Chuyển khoản ngân hàng', '2025-11-10 08:58:18', '2025-11-10 08:58:18');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand_id` bigint NOT NULL,
  `category_id` bigint NOT NULL,
  `status` enum('published','draft','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `slug`, `brand_id`, `category_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Casio G-Shock GA-2100', 'Đồng hồ Casio G-Shock GA-2100 \"CasiOak\" siêu mỏng, siêu bền.', 'casio-g-shock-ga-2100', 1, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(2, 'Rolex Datejust 41', 'Đồng hồ Rolex Datejust 41mm, mặt xanh, cọc số kim cương.', 'rolex-datejust-41', 2, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(3, 'Seiko 5 Sports SRPD', 'Đồng hồ cơ tự động Seiko 5 Sports SRPD, phong cách lặn.', 'seiko-5-sports-srpd', 3, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(4, 'Rolex Lady-Datejust 28', 'Đồng hồ Rolex Lady-Datejust 28mm, vàng hồng Everose.', 'rolex-lady-datejust-28', 2, 2, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(5, 'Casio A168WG Vintage', 'Đồng hồ Casio A168WG-9WDF, thiết kế vintage, mạ vàng.', 'casio-a168wg-vintage', 1, 2, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(6, 'Seiko Presage Cocktail Time', 'Đồng hồ Seiko Presage \"Cocktail Time\" SSA343J1 mặt xanh băng.', 'seiko-presage-cocktail-time', 3, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(7, 'Casio G-Shock DW-5600', 'Đồng hồ Casio G-Shock DW-5600E-1VDF, kiểu dáng vuông cổ điển.', 'casio-g-shock-dw-5600', 1, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(8, 'Rolex Submariner Date', 'Đồng hồ Rolex Submariner Date 126610LN, biểu tượng của đồng hồ lặn.', 'rolex-submariner-date', 2, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(9, 'Seiko Prospex \"Tuna\"', 'Đồng hồ lặn chuyên nghiệp Seiko Prospex \"Tuna\" SNE541P1.', 'seiko-prospex-tuna', 3, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(10, 'Casio Baby-G BGD-560', 'Đồng hồ Casio Baby-G BGD-560-1DR cho nữ, chống va đập.', 'casio-baby-g-bgd-560', 1, 2, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(11, 'Rolex Cosmograph Daytona', 'Đồng hồ Rolex Cosmograph Daytona 116500LN, mặt gốm Cerachrom.', 'rolex-cosmograph-daytona', 2, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(12, 'Seiko SKX007', 'Đồng hồ lặn huyền thoại Seiko SKX007 (đã ngưng sản xuất).', 'seiko-skx007', 3, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(13, 'Casio Edifice EFR-526D', 'Đồng hồ Casio Edifice EFR-526D-1AVUEF Chronograph.', 'casio-edifice-efr-526d', 1, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(14, 'Rolex Oyster Perpetual 36', 'Đồng hồ Rolex Oyster Perpetual 36, mặt màu kẹo ngọt.', 'rolex-oyster-perpetual-36', 2, 2, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(15, 'Seiko Astron GPS Solar', 'Đồng hồ Seiko Astron GPS Solar, tự động cập nhật múi giờ.', 'seiko-astron-gps-solar', 3, 3, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(16, 'Casio MTP-V006D', 'Đồng hồ Casio MTP-V006D-7BUDF, mặt trắng, dây thép, giá rẻ.', 'casio-mtp-v006d', 1, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(17, 'Rolex GMT-Master II \"Pepsi\"', 'Đồng hồ Rolex GMT-Master II 126710BLRO \"Pepsi\" Jubilee.', 'rolex-gmt-master-ii-pepsi', 2, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(18, 'Seiko 5 \"Tuxedo\" SRPE', 'Đồng hồ Seiko 5 SRPE51K1 \"Tuxedo\" mặt xám.', 'seiko-5-tuxedo-srpe', 3, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(19, 'Casio F-91W', 'Đồng hồ Casio F-91W-1DG, huyền thoại điện tử, pin 10 năm.', 'casio-f-91w', 1, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(20, 'Rolex Sky-Dweller', 'Đồng hồ Rolex Sky-Dweller, cơ chế phức tạp, hiển thị 2 múi giờ.', 'rolex-sky-dweller', 2, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(21, 'Seiko Grand Seiko SBGA211', 'Đồng hồ Grand Seiko \"Snowflake\" SBGA211, máy Spring Drive.', 'grand-seiko-snowflake-sbga211', 3, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(22, 'Casio Pro Trek PRG-600', 'Đồng hồ Casio Pro Trek PRG-600Y-1DR, la bàn, đo độ cao.', 'casio-pro-trek-prg-600', 1, 3, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(23, 'Rolex Explorer 36', 'Đồng hồ Rolex Explorer 36 124270, đơn giản, bền bỉ.', 'rolex-explorer-36', 2, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(24, 'Seiko Alpinist SARB017', 'Đồng hồ Seiko Alpinist SARB017 mặt xanh lá, la bàn xoay.', 'seiko-alpinist-sarb017', 3, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(25, 'Casio G-Shock GMW-B5000', 'Đồng hồ Casio G-Shock GMW-B5000D-1 \"Full Metal\" Bạc.', 'casio-g-shock-gmw-b5000', 1, 3, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(26, 'Rolex Milgauss 116400GV', 'Đồng hồ Rolex Milgauss 116400GV, mặt kính xanh, kim giây hình tia sét.', 'rolex-milgauss-116400gv', 2, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(27, 'Seiko 5 GMT SSK001', 'Đồng hồ Seiko 5 Sports Style GMT SSK001, máy 4R34.', 'seiko-5-gmt-ssk001', 3, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(28, 'Casio SHEEN SHE-3047PG', 'Đồng hồ Casio SHEEN SHE-3047PG-9AUDR, đính đá Swarovski.', 'casio-sheen-she-3047pg', 1, 2, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(29, 'Rolex Yacht-Master 40', 'Đồng hồ Rolex Yacht-Master 40, vàng Everose, dây Oysterflex.', 'rolex-yacht-master-40', 2, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30'),
(30, 'Seiko King Seiko SBPK', 'Tái bản đồng hồ King Seiko SBPK, thiết kế KSK cổ điển.', 'king-seiko-sbpk', 3, 1, 'published', '2025-11-10 08:54:30', '2025-11-10 08:54:30');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint NOT NULL,
  `product_id` bigint NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `alt_text`, `created_at`, `updated_at`) VALUES
(6, 1, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Casio G-Shock GA-2100', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(7, 2, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Rolex Datejust 41', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(8, 3, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Seiko 5 Sports SRPD', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(9, 4, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Rolex Lady-Datejust 28', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(10, 5, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Casio A168WG Vintage', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(11, 6, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Seiko Presage Cocktail Time', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(12, 7, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Casio G-Shock DW-5600', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(13, 8, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Rolex Submariner Date', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(14, 9, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Seiko Prospex \"Tuna\"', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(15, 10, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Casio Baby-G BGD-560', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(16, 11, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Rolex Cosmograph Daytona', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(17, 12, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Seiko SKX007', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(18, 13, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Casio Edifice EFR-526D', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(19, 14, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Rolex Oyster Perpetual 36', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(20, 15, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Seiko Astron GPS Solar', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(21, 16, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Casio MTP-V006D', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(22, 17, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Rolex GMT-Master II \"Pepsi\"', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(23, 18, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Seiko 5 \"Tuxedo\" SRPE', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(24, 19, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Casio F-91W', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(25, 20, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Rolex Sky-Dweller', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(26, 21, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Seiko Grand Seiko SBGA211', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(27, 22, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Casio Pro Trek PRG-600', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(28, 23, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Rolex Explorer 36', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(29, 24, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Seiko Alpinist SARB017', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(30, 25, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Casio G-Shock GMW-B5000', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(31, 26, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Rolex Milgauss 116400GV', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(32, 27, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Seiko 5 GMT SSK001', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(33, 28, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Casio SHEEN SHE-3047PG', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(34, 29, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'Rolex Yacht-Master 40', '2025-11-14 08:31:00', '2025-11-14 08:31:00'),
(35, 30, 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', 'King Seiko SBPK', '2025-11-14 08:31:00', '2025-11-14 08:31:00');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_methods`
--

CREATE TABLE `shipping_methods` (
  `id` bigint NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_methods`
--

INSERT INTO `shipping_methods` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Giao hàng Tiêu chuẩn', '2025-11-10 08:58:18', '2025-11-10 08:58:18'),
(2, 'Giao hàng Nhanh', '2025-11-10 08:58:18', '2025-11-10 08:58:18');

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `id` bigint NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`id`, `name`, `price`, `created_at`, `updated_at`) VALUES
(1, '40mm', 3500000.00, '2025-11-10 08:29:50', '2025-11-19 08:23:38'),
(2, '42mm', 3500000.00, '2025-11-10 08:29:50', '2025-11-19 08:23:38'),
(3, '38mm', 280000000.00, '2025-11-10 08:29:50', '2025-11-19 08:23:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone_number`, `created_at`, `updated_at`) VALUES
(1, 'Khách Hàng A', 'khachhang_a@gmail.com', '$2y$10$E.gL3h3m/xY.7q.Z.B8q.eK.9j/8U/9U.u3/8i/9q.tX/q.o/6u', 'customer', '0909123456', '2025-11-10 08:58:18', '2025-11-10 08:58:18');

-- --------------------------------------------------------

--
-- Table structure for table `variants`
--

CREATE TABLE `variants` (
  `id` bigint NOT NULL,
  `product_id` bigint NOT NULL,
  `color_id` bigint DEFAULT NULL,
  `size_id` bigint DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `sku` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `variants`
--

INSERT INTO `variants` (`id`, `product_id`, `color_id`, `size_id`, `quantity`, `sku`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 0, 'TEST', 'https://cdn.tgdd.vn/Products/Images/7077/308485/garmin-epix-pro-gen-2-1-750x500.jpg', '2025-11-14 12:13:58', '2025-11-19 08:02:09'),
(6, 1, 1, 2, 50, 'CS-GA2100-BLK-40', 'https://cdn.tgdd.vn/Products/Images/7077/308485/garmin-epix-pro-gen-2-3-750x500.jpg', '2025-11-10 09:00:19', '2025-11-18 14:11:29'),
(7, 2, 5, 2, 5, 'RLX-DJ41-BLU-42', 'product-2-blue.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(8, 3, 1, 2, 30, 'SK-SRPD-BLK-42', 'product-3-black.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(9, 3, 5, 2, 20, 'SK-SRPD-BLU-42', 'product-3-blue.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(10, 4, 4, 3, 3, 'RLX-LDJ28-RG-38', 'product-4-rosegold.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(11, 5, 3, 3, 100, 'CS-A168WG-GLD-38', 'product-5-gold.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(12, 6, 5, 1, 15, 'SK-SSA343-BLU-40', 'product-6-blue.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(13, 7, 1, 1, 75, 'CS-DW5600-BLK-40', 'product-7-black.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(14, 8, 1, 1, 2, 'RLX-SUBD-BLK-40', 'product-8-black.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(15, 9, 1, 2, 10, 'SK-SNE541-BLK-42', 'product-9-black.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(16, 10, 6, 3, 40, 'CS-BGD560-WHT-38', 'product-10-white.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(17, 11, 6, 1, 1, 'RLX-DAYTONA-WHT-40', 'product-11-white.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(18, 12, 1, 2, 0, 'SK-SKX007-BLK-42', 'product-12-black.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(19, 13, 2, 2, 25, 'CS-EFR526-SLV-42', 'product-13-silver.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(20, 14, 5, 3, 3, 'RLX-OP36-BLU-38', 'product-14-blue.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(21, 15, 1, 2, 5, 'SK-ASTRON-BLK-42', 'product-15-black.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(22, 16, 6, 1, 200, 'CS-MTPV006-WHT-40', 'product-16-white.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(23, 17, 5, 1, 1, 'RLX-GMTII-PEPSI-40', 'product-17-pepsi.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(24, 18, 1, 1, 18, 'SK-SRPE51-GRY-40', 'product-18-grey.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(25, 19, 1, 3, 500, 'CS-F91W-BLK-38', 'product-19-black.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(26, 20, 2, 2, 1, 'RLX-SKYD-SLV-42', 'product-20-silver.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(27, 21, 6, 1, 4, 'GS-SBGA211-WHT-40', 'product-21-snowflake.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(28, 22, 1, 2, 12, 'CS-PRG600-BLK-42', 'product-22-black.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(29, 23, 1, 3, 3, 'RLX-EXP36-BLK-38', 'product-23-black.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(30, 24, 5, 3, 7, 'SK-SARB017-GRN-38', 'product-24-green.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(31, 25, 2, 2, 8, 'CS-GMWB5000-SLV-42', 'product-25-silver.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(32, 26, 1, 1, 2, 'RLX-MILGAUSS-BLK-40', 'product-26-black.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(33, 27, 1, 2, 22, 'SK-SSK001-BLK-42', 'product-27-black.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(34, 28, 4, 3, 14, 'CS-SHEEN-RG-38', 'product-28-rosegold.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(35, 29, 1, 1, 1, 'RLX-YM40-BLK-40', 'product-29-black.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(36, 30, 2, 3, 6, 'KS-SBPK-SLV-38', 'product-30-silver.jpg', '2025-11-10 09:00:19', '2025-11-10 09:00:19'),
(37, 1, 2, 1, 20, 'CS-GA2100-SLV-40', 'https://images.unsplash.com/photo-1456086272139-7d6b1047d1a2?auto=format&fit=crop&w=600&q=80', '2025-11-19 07:55:44', '2025-11-19 07:55:44'),
(38, 1, 2, 2, 15, 'CS-GA2100-SLV-42', 'https://images.unsplash.com/photo-1456086272139-7d6b1047d1a2?auto=format&fit=crop&w=600&q=80', '2025-11-19 07:55:44', '2025-11-19 08:02:09'),
(39, 1, 3, 1, 10, 'CS-GA2100-GLD-40', 'https://images.unsplash.com/photo-1518732714860-80c9b84c93fb?auto=format&fit=crop&w=600&q=80', '2025-11-19 07:55:44', '2025-11-19 07:55:44'),
(40, 1, 3, 2, 8, 'CS-GA2100-GLD-42', 'https://images.unsplash.com/photo-1518732714860-80c9b84c93fb?auto=format&fit=crop&w=600&q=80', '2025-11-19 07:55:44', '2025-11-19 08:02:09'),
(41, 1, 5, 1, 30, 'CS-GA2100-BLU-40', 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', '2025-11-19 07:55:44', '2025-11-19 07:55:44'),
(42, 1, 5, 2, 25, 'CS-GA2100-BLU-42', 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80', '2025-11-19 07:55:44', '2025-11-19 08:02:09'),
(43, 1, 6, 3, 50, 'CS-GA2100-WHT-38', 'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?auto=format&fit=crop&w=400&q=80', '2025-11-19 07:55:44', '2025-11-19 07:55:44'),
(44, 1, 6, 1, 40, 'CS-GA2100-WHT-40', 'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?auto=format&fit=crop&w=400&q=80', '2025-11-19 07:55:44', '2025-11-19 08:02:09'),
(45, 1, 4, 3, 12, 'CS-GA2100-RGLD-38', 'https://images.unsplash.com/photo-1491553895911-0055eca6402d?auto=format&fit=crop&w=400&q=80', '2025-11-19 07:55:44', '2025-11-19 07:55:44'),
(46, 1, 1, 3, 60, 'CS-GA2100-BLK-38', 'https://images.unsplash.com/photo-1518551937742-d7cb8f32a9b8?auto=format&fit=crop&w=600&q=80', '2025-11-19 07:55:44', '2025-11-19 08:02:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `blog_category_id` (`blog_category_id`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `payment_method_id` (`payment_method_id`),
  ADD KEY `shipping_method_id` (`shipping_method_id`),
  ADD KEY `coupon_id` (`coupon_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_number` (`phone_number`);

--
-- Indexes for table `variants`
--
ALTER TABLE `variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `color_id` (`color_id`),
  ADD KEY `size_id` (`size_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `variants`
--
ALTER TABLE `variants`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `addresses_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `blogs_ibfk_2` FOREIGN KEY (`blog_category_id`) REFERENCES `blog_categories` (`id`),
  ADD CONSTRAINT `blogs_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `blogs_ibfk_4` FOREIGN KEY (`blog_category_id`) REFERENCES `blog_categories` (`id`);

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`),
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`),
  ADD CONSTRAINT `cart_items_ibfk_3` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`),
  ADD CONSTRAINT `cart_items_ibfk_4` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`shipping_method_id`) REFERENCES `shipping_methods` (`id`),
  ADD CONSTRAINT `orders_ibfk_4` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`),
  ADD CONSTRAINT `orders_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_6` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`),
  ADD CONSTRAINT `orders_ibfk_7` FOREIGN KEY (`shipping_method_id`) REFERENCES `shipping_methods` (`id`),
  ADD CONSTRAINT `orders_ibfk_8` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`),
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_4` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  ADD CONSTRAINT `products_ibfk_4` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `product_images_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `variants`
--
ALTER TABLE `variants`
  ADD CONSTRAINT `variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `variants_ibfk_2` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`),
  ADD CONSTRAINT `variants_ibfk_3` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`),
  ADD CONSTRAINT `variants_ibfk_4` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `variants_ibfk_5` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`),
  ADD CONSTRAINT `variants_ibfk_6` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
