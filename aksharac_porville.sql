-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 29, 2026 at 01:59 AM
-- Server version: 10.6.20-MariaDB-cll-lve
-- PHP Version: 8.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aksharac_porville`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

CREATE TABLE `app_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_settings`
--

INSERT INTO `app_settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(6, 'delivery_charge', '50', '2026-09-17 18:04:51', '2026-09-28 17:02:52');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `tag` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_enquiry_only` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `tag`, `image`, `parent_id`, `is_active`, `is_enquiry_only`, `created_at`, `updated_at`) VALUES
(1, 'Chicken', 'chicken', 'Fresh, tender, and hygienically cut chicken parts and whole birds.', 'Farm Fresh', 'categories/rbpn9Ujg9Rb5wTaGVjd3753xsJrvSHqe0I7z16WE.avif', NULL, 1, 0, '2026-09-17 16:51:23', '2026-09-23 14:45:17'),
(2, 'Mutton', 'mutton', 'Premium quality goat and lamb cuts, rich in flavor and nutrition', 'Farm Fresh', 'categories/t6zUvB9YLzVXl0kW6DuXvykXeeE8NjkW0PGyrniM.avif', NULL, 1, 0, '2026-09-17 17:05:29', '2026-09-23 14:45:38'),
(6, 'Live Stock', 'live-stock', 'Healthy live farm birds and livestock raised under premium guidelines.', 'Farm Fresh', 'categories/nOZ3GT1qH1BWHIhGbAqGPuP9SYgX13s2rQY8agzt.avif', NULL, 1, 1, '2026-09-17 17:21:55', '2026-09-23 14:50:44'),
(8, 'Eggs', 'eggs', 'Organic, farm-fresh eggs loaded with proteins and nutrients.', 'Farm Fresh', 'categories/65k8zzlijt5hIv2BDvRCAxkXeJEtgqCXrp8FDBWo.avif', NULL, 1, 0, '2026-09-22 21:01:24', '2026-09-23 14:43:45'),
(10, 'Pork', 'pork', 'Large white, hygenic and carefully packed according to you need right to your kitchen', 'Farm Fresh', 'categories/ZcR7HgQVj2rYw85IFiYAE2te0HsJfdQmCwkCJcyU.avif', NULL, 1, 0, '2026-09-23 14:44:39', '2026-09-28 17:17:53'),
(11, 'Quail', 'quail', 'Nutritious and flavor-packed quail meat, sourced from selected farms', 'Farm Fresh', 'categories/HgO1ABV5CHrbulHU9elIompbfb5BKDgSEwG2WU3D.avif', NULL, 1, 0, '2026-09-23 14:48:27', '2026-09-23 14:48:27'),
(12, 'Ready To Eat', 'ready-to-eat', 'Pre-marinated, smoked, and fully cooked premium meat delicacies.', 'Farm Fresh', 'categories/g5w629Ua9W3SJOZenty0eQIQ0dmpyi5gIL5k7tVk.avif', NULL, 1, 0, '2026-09-23 14:54:33', '2026-09-23 14:54:33'),
(13, 'Special', 'special', 'Porville specialty cuts, exotic meats, and limited-time offers.', 'Farm Fresh', 'categories/QeVQ5FFZDLaUNXE6GTCSmCsUznauWX35EEmOdmb3.avif', NULL, 1, 0, '2026-09-23 14:56:28', '2026-09-28 14:45:46'),
(24, 'Duck', 'duck', 'Premium duck meat, freshly cut into perfectly sized pieces for a delicious curry.\r\nTender, flavorful, and perfect for preparing a rich and hearty home-cooked meal.', 'Farm Fresh', 'categories/GgaVMBPF0sdWuxqnHrKJFg7pzY0i0aU89rbE54uh.avif', NULL, 1, 0, '2026-09-28 02:34:47', '2026-09-28 02:34:47');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department` varchar(255) NOT NULL DEFAULT 'Customer Support',
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `department`, `name`, `email`, `message`, `read_at`, `created_at`, `updated_at`) VALUES
(2, 'Delivery Help', 'Testing', 'bmdumanojkumar@gmail.com', 'Testing', NULL, '2026-09-28 22:00:05', '2026-09-28 22:00:05');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `entry_type` varchar(255) NOT NULL DEFAULT 'coupon',
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `type` enum('flat','percent') NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `min_order_amount` decimal(10,2) DEFAULT NULL,
  `max_uses` int(11) DEFAULT NULL,
  `per_user_limit` int(10) UNSIGNED DEFAULT NULL,
  `starts_at` datetime DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `entry_type`, `title`, `description`, `product_id`, `code`, `type`, `value`, `min_order_amount`, `max_uses`, `per_user_limit`, `starts_at`, `used_count`, `expires_at`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'coupon', NULL, NULL, NULL, '123123', 'percent', 10.00, 100.00, 10, 6, NULL, 1, '2026-09-30 17:00:00', 1, '2026-09-28 17:00:40', '2026-09-28 20:14:59'),
(2, 'offer', 'testing offer', 'testing offer Description', 19, 'AUTO-OFFER-HVOJ7CGE', 'percent', 10.00, 100.00, 12, 2, '2026-09-22 00:00:00', 0, '2026-09-30 04:00:00', 1, '2026-09-28 17:02:00', '2026-09-28 17:22:01');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_user_usages`
--

CREATE TABLE `coupon_user_usages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `coupon_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `usage_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupon_user_usages`
--

INSERT INTO `coupon_user_usages` (`id`, `coupon_id`, `user_id`, `usage_count`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, '2026-09-28 20:00:06', '2026-09-28 20:00:06');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_boys`
--

CREATE TABLE `delivery_boys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `partner_name` varchar(255) NOT NULL,
  `phone_number` varchar(25) NOT NULL,
  `area` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `last_assigned` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_boys`
--

INSERT INTO `delivery_boys` (`id`, `partner_name`, `phone_number`, `area`, `status`, `last_assigned`, `created_at`, `updated_at`) VALUES
(1, 'amit', '9089786756', 'sec 62', 'active', '2026-09-28 16:59:01', '2026-09-28 16:58:46', '2026-09-28 16:59:01');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_slots`
--

CREATE TABLE `delivery_slots` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_slots`
--

INSERT INTO `delivery_slots` (`id`, `date`, `start_time`, `end_time`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '2026-09-17', '14:03:00', '17:03:00', 1, '2026-09-17 18:03:33', '2026-09-17 18:03:33'),
(2, '2026-09-17', '17:03:00', '19:08:00', 1, '2026-09-17 18:03:44', '2026-09-17 18:03:44'),
(3, '2026-09-18', '15:03:00', '16:03:00', 1, '2026-09-17 18:03:56', '2026-09-17 18:03:56'),
(4, '2026-09-18', '17:04:00', '18:04:00', 1, '2026-09-17 18:04:08', '2026-09-17 18:04:08'),
(5, '2026-09-20', '13:04:00', '14:04:00', 1, '2026-09-17 18:04:31', '2026-09-17 18:04:31'),
(6, '2026-09-23', '16:04:00', '18:04:00', 1, '2026-09-17 18:04:42', '2026-09-17 18:04:42'),
(7, '2026-09-23', '17:25:00', '21:29:00', 1, '2026-09-22 21:25:35', '2026-09-22 21:25:35'),
(8, '2026-09-23', '21:25:00', '23:25:00', 1, '2026-09-22 21:25:51', '2026-09-22 21:25:51'),
(9, '2026-09-25', '19:28:00', '21:28:00', 1, '2026-09-22 21:28:38', '2026-09-22 21:28:38'),
(10, '2026-09-28', '18:03:00', '19:03:00', 1, '2026-09-28 17:03:07', '2026-09-28 17:03:07'),
(11, '2026-09-29', '11:03:00', '13:03:00', 1, '2026-09-28 17:03:30', '2026-09-28 17:03:30');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `faq_category_id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `faq_category_id`, `question`, `answer`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'How do I place an order on Porville?', 'You can browse products, add your selected items to the cart, and complete checkout by entering your delivery details and payment information. After the order is placed, you will receive order confirmation and can track the order status from your account or order tracking page.', 1, 1, '2026-09-17 17:40:56', '2026-09-17 17:40:56'),
(2, 1, 'Can I order without creating an account?', 'If guest checkout is enabled on the website, you can place an order without creating an account. However, creating an account makes it easier to view past orders, track current orders, and save your details for future purchases.', 2, 1, '2026-09-17 17:41:36', '2026-09-17 17:41:36');

-- --------------------------------------------------------

--
-- Table structure for table `faq_categories`
--

CREATE TABLE `faq_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faq_categories`
--

INSERT INTO `faq_categories` (`id`, `title`, `slug`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'orders', 'order', 1, 1, '2026-09-17 17:40:13', '2026-09-17 17:41:45');

-- --------------------------------------------------------

--
-- Table structure for table `home_banners`
--

CREATE TABLE `home_banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `mobile_image` varchar(255) DEFAULT NULL,
  `badge` varchar(255) DEFAULT NULL,
  `title_1` varchar(255) NOT NULL,
  `title_2` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `button_text` varchar(255) NOT NULL DEFAULT 'Shop Now',
  `link_url` varchar(255) DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `home_banners`
--

INSERT INTO `home_banners` (`id`, `image`, `mobile_image`, `badge`, `title_1`, `title_2`, `description`, `button_text`, `link_url`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'home-banners/1sfY8cu6P3aEfpWQIS51YAryibXDBYfZsaSqk4t0.avif', 'home-banners/mobile/Tx2ado5wV6oh0IgFocX4iXtxBwqiHXznVGROeetX.avif', NULL, 'Banner', NULL, NULL, 'Shop Now', NULL, 0, 1, '2026-09-17 17:52:02', '2026-09-23 22:40:59');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_01_01_000001_create_users_table', 1),
(2, '2024_01_01_000002_create_categories_table', 1),
(3, '2024_01_01_000003_create_products_table', 1),
(4, '2024_01_01_000004_create_orders_table', 1),
(5, '2024_01_01_000005_create_order_items_table', 1),
(6, '2024_01_01_000006_create_coupons_table', 1),
(7, '2024_01_01_000007_create_notifications_table', 1),
(8, '2026_04_20_180536_add_variants_subcategory_to_products_table', 1),
(9, '2026_04_21_124448_add_extra_fields_to_order_items_table', 1),
(10, '2026_04_21_124525_add_extra_fields_to_orders_table', 1),
(11, '2026_04_22_050025_create_personal_access_tokens_table', 1),
(12, '2026_04_25_073500_add_videos_to_products_table', 1),
(13, '2026_04_25_111500_create_delivery_boys_table', 1),
(14, '2026_04_25_190000_add_offer_fields_to_coupons_table', 1),
(15, '2026_04_26_090000_add_delivery_boy_assignment_to_orders_table', 1),
(16, '2026_04_28_090000_add_pack_details_to_order_items_table', 1),
(17, '2026_04_30_140000_add_delivery_slot_to_orders_table', 1),
(18, '2026_04_30_150000_create_app_settings_table', 1),
(19, '2026_05_20_000001_add_delivery_charge_to_users_table', 1),
(20, '2026_06_14_000001_add_pricing_day_to_order_items_table', 1),
(21, '2026_06_14_000002_create_home_banners_table', 1),
(22, '2026_06_15_000001_create_contact_messages_table', 1),
(23, '2026_06_15_000002_add_recipient_to_notifications_table', 1),
(24, '2026_06_25_190000_add_delivery_day_to_orders_table', 1),
(25, '2026_06_27_160000_add_per_user_limit_to_coupons', 1),
(26, '2026_07_27_180000_create_reviews_table', 1),
(27, '2026_07_27_190000_add_product_and_display_to_reviews_table', 1),
(28, '2026_07_28_110000_add_mobile_image_to_home_banners_table', 1),
(29, '2026_08_03_120000_add_product_targeting_to_coupons_table', 1),
(30, '2026_08_08_151434_add_ordering_settings_to_app_settings_table', 1),
(31, '2026_08_09_000001_create_razorpay_payments_table', 1),
(32, '2026_08_20_000001_add_date_of_birth_and_gender_to_users_table', 1),
(33, '2026_08_27_000001_add_daywise_ordering_settings_to_app_settings_table', 1),
(34, '2026_09_16_000001_create_delivery_slots_table', 1),
(35, '2026_09_16_000002_create_faq_tables', 1),
(36, '2026_09_17_000001_add_delivery_date_to_orders_table', 1),
(37, '2026_09_17_000002_create_service_charge_tiers_table', 1),
(38, '2026_09_17_000003_add_enquiry_fields', 1),
(39, '2026_09_17_032533_add_processing_delivery_note_to_products_table', 1),
(40, '2026_09_17_034208_remove_orphaned_ordering_app_settings', 1),
(41, '2026_09_22_181100_add_tag_to_categories_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `sent_by` bigint(20) UNSIGNED DEFAULT NULL,
  `recipient_id` bigint(20) UNSIGNED DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `subject`, `message`, `sent_by`, `recipient_id`, `read_at`, `created_at`, `updated_at`) VALUES
(4, 'Order ORD-XVLOSDLTTNDO Confirmed! - Porville', 'Your order ORD-XVLOSDLTTNDO of Rs1,605.90 has been confirmed. We are getting your fresh items ready.', 1, 6, NULL, '2026-09-23 19:18:53', '2026-09-23 19:18:53'),
(5, 'Order ORD-XVLOSDLTTNDO Out for Delivery! 🚚 - Porville', 'Your order ORD-XVLOSDLTTNDO is out for delivery with amit. Please be ready to receive your fresh package!', 1, 6, NULL, '2026-09-28 16:59:01', '2026-09-28 16:59:01'),
(6, 'Order ORD-CRQKHV6ETUO8 Placed Successfully - Porville', 'Thank you for your order ORD-CRQKHV6ETUO8 of Rs870.00! We have received your order and will process it shortly.', 1, 2, NULL, '2026-09-28 20:00:06', '2026-09-28 20:00:06');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `delivery_boy_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `delivery_charge` decimal(10,2) NOT NULL DEFAULT 0.00,
  `platform_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `vendor_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `admin_commission` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`shipping_address`)),
  `payment_method` varchar(255) NOT NULL DEFAULT 'COD',
  `payment_status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `delivery_slot` varchar(255) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `delivery_day` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `delivery_boy_id`, `status`, `subtotal`, `discount`, `shipping_cost`, `delivery_charge`, `platform_fee`, `vendor_total`, `admin_commission`, `tax`, `total`, `shipping_address`, `payment_method`, `payment_status`, `delivery_slot`, `delivery_date`, `delivery_day`, `created_at`, `updated_at`) VALUES
(1, 'ORD-6TJNQ7SAVYSR', 2, NULL, 'cancelled', 400.00, 0.00, 100.00, 100.00, 40.00, 0.00, 0.00, 0.00, 540.00, '{\"name\":\"manoj\",\"phone\":\"9084978571\",\"address\":\"RAMAN PADA CHHATA\",\"city\":\"chhata\",\"state\":\"up\",\"pincode\":\"281401\",\"sector\":\"sc 63\"}', 'online', 'failed', '17:04-18:04', '2026-09-18', 'today', '2026-09-17 18:05:23', '2026-09-17 18:05:44'),
(2, 'ORD-F3FNOULQPJ2K', 2, NULL, 'pending', 400.00, 0.00, 100.00, 100.00, 40.00, 0.00, 0.00, 0.00, 540.00, '{\"name\":\"manoj\",\"phone\":\"9084978571\",\"address\":\"RAMAN PADA CHHATA\",\"city\":\"chhata\",\"state\":\"up\",\"pincode\":\"281401\",\"sector\":\"sc 63\"}', 'COD', 'pending', '17:03-19:08', '2026-09-17', 'today', '2026-09-17 18:05:58', '2026-09-17 18:05:58'),
(3, 'ORD-TWFOOXNLRTHN', 2, NULL, 'pending', 1.00, 0.00, 1.00, 1.00, 0.10, 0.00, 0.00, 0.00, 2.10, '{\"name\":\"manoj\",\"phone\":\"9084978571\",\"address\":\"RAMAN PADA CHHATA\",\"city\":\"chhata\",\"state\":\"up\",\"pincode\":\"281401\",\"sector\":\"sc 63\"}', 'online', 'pending', '17:03-19:08', '2026-09-17', 'today', '2026-09-17 18:59:22', '2026-09-17 18:59:22'),
(4, 'ORD-FMKEGZF6JNER', 3, NULL, 'pending', 1.00, 0.00, 1.00, 1.00, 0.10, 0.00, 0.00, 0.00, 2.10, '{\"name\":\"Ggh\",\"phone\":\"8449201182\",\"address\":\"Gg\",\"city\":\"Delhi\",\"state\":\"Uk\",\"pincode\":\"244713\",\"sector\":null}', 'online', 'pending', '17:03-19:08', '2026-09-17', 'today', '2026-09-17 19:12:00', '2026-09-17 19:12:00'),
(5, 'ORD-XRAA10XETG5Y', 2, NULL, 'cancelled', 300.09, 0.00, 1.00, 1.00, 30.01, 0.00, 0.00, 0.00, 331.10, '{\"name\":\"manoj\",\"phone\":\"9084978571\",\"address\":\"RAMAN PADA CHHATA\",\"city\":\"chhata\",\"state\":\"up\",\"pincode\":\"281401\",\"sector\":\"sc 63\"}', 'online', 'failed', '17:03-19:08', '2026-09-17', 'today', '2026-09-17 19:17:10', '2026-09-17 19:18:05'),
(6, 'ORD-NRTRQNUY3URK', 2, NULL, 'pending', 1.00, 0.00, 1.00, 1.00, 0.10, 0.00, 0.00, 0.00, 2.10, '{\"name\":\"manoj\",\"phone\":\"9084978571\",\"address\":\"RAMAN PADA CHHATA\",\"city\":\"chhata\",\"state\":\"up\",\"pincode\":\"281401\",\"sector\":\"sc 63\"}', 'online', 'pending', '17:03-19:08', '2026-09-17', 'today', '2026-09-17 19:18:28', '2026-09-17 19:18:28'),
(7, 'ORD-ZVLJCUI7OY6R', 4, NULL, 'pending', 359.00, 0.00, 1.00, 1.00, 35.90, 0.00, 0.00, 0.00, 395.90, '{\"name\":\"manoj\",\"phone\":\"9084978578\",\"address\":\"RAMAN PADA d\",\"city\":\"noida\",\"state\":\"up\",\"pincode\":\"121212\",\"sector\":\"dfgdgdf\"}', 'COD', 'pending', '19:28-21:28', '2026-09-25', 'today', '2026-09-23 17:29:31', '2026-09-23 17:29:31'),
(8, 'ORD-XVLOSDLTTNDO', 6, 1, 'out_for_delivery', 1459.00, 0.00, 1.00, 1.00, 145.90, 0.00, 0.00, 0.00, 1605.90, '{\"name\":\"Akshat\",\"phone\":\"9910313899\",\"address\":\"noida sec-66\",\"city\":\"noida\",\"state\":\"uttar pardesh\",\"pincode\":\"201301\",\"sector\":null}', 'COD', 'pending', '16:04-18:04', '2026-09-23', 'today', '2026-09-23 19:16:12', '2026-09-28 16:59:01'),
(9, 'ORD-CRQKHV6ETUO8', 2, NULL, 'pending', 820.00, 82.00, 50.00, 50.00, 82.00, 0.00, 0.00, 0.00, 870.00, '{\"name\":\"manoj\",\"phone\":\"9084978571\",\"address\":\"RAMAN PADA CHHATA\",\"city\":\"chhata\",\"state\":\"up\",\"pincode\":\"281401\",\"sector\":\"sc 63\"}', 'COD', 'pending', '11:03-13:03', '2026-09-29', 'today', '2026-09-28 20:00:06', '2026-09-28 20:00:06');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `pack_quantity` decimal(10,2) DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `mrp` decimal(10,2) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `variant_label` varchar(255) DEFAULT NULL,
  `pricing_day` varchar(20) NOT NULL DEFAULT 'today',
  `save_offer` decimal(5,2) DEFAULT NULL,
  `vendor_amount` decimal(10,2) DEFAULT NULL,
  `admin_amount` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `pack_quantity`, `unit_price`, `mrp`, `unit`, `variant_label`, `pricing_day`, `save_offer`, `vendor_amount`, `admin_amount`, `subtotal`, `created_at`, `updated_at`) VALUES
(7, 7, 17, 1, 450.00, 359.00, 350.00, 'Gram', '450 Gram', 'today', NULL, NULL, NULL, 359.00, '2026-09-23 17:29:31', '2026-09-23 17:29:31'),
(8, 8, 17, 1, 450.00, 359.00, 350.00, 'Gram', '450 Gram', 'today', NULL, NULL, NULL, 359.00, '2026-09-23 19:16:12', '2026-09-23 19:16:12'),
(9, 8, 17, 1, 900.00, 600.00, 600.00, 'Gram', '900 Gram', 'today', NULL, NULL, NULL, 600.00, '2026-09-23 19:16:12', '2026-09-23 19:16:12'),
(10, 8, 61, 1, 1.00, 500.00, 500.00, 'Pc', '1 Pc', 'today', NULL, NULL, NULL, 500.00, '2026-09-23 19:16:12', '2026-09-23 19:16:12'),
(11, 9, 19, 1, 450.00, 320.00, 320.00, 'Gram', '450 Gram', 'today', NULL, NULL, NULL, 320.00, '2026-09-28 20:00:06', '2026-09-28 20:00:06'),
(12, 9, 61, 1, 450.00, 500.00, 500.00, 'Gram', '450 Gram', 'today', NULL, NULL, NULL, 500.00, '2026-09-28 20:00:06', '2026-09-28 20:00:06');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `mrp` decimal(10,2) DEFAULT NULL,
  `weight` varchar(255) DEFAULT NULL,
  `unit` varchar(255) NOT NULL DEFAULT 'Kg',
  `contact_number` varchar(20) DEFAULT NULL,
  `processing_note` varchar(255) DEFAULT NULL,
  `delivery_note` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `videos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`videos`)),
  `variants` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variants`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `subcategory_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `mrp`, `weight`, `unit`, `contact_number`, `processing_note`, `delivery_note`, `stock`, `images`, `videos`, `variants`, `is_active`, `created_at`, `updated_at`, `subcategory_id`) VALUES
(9, 1, 'Desi chicken whole', 'desi-chicken-whole', 'Whole Desi Chicken – Fresh, naturally flavorful country chicken, carefully selected for authentic taste and quality. Perfect for a wholesome meal with the rich, traditional taste of desi chicken.', 850.00, 850.00, 'gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/gH8MMy4BXeLdA7KHBKFfiIvcTRs8cH3zCL3btb3Q.avif\"]', '[]', '[{\"quantity\":\"1 piece (1000g to 1200g)\",\"unit\":\"Gram\",\"piece\":\"1\",\"mrp\":850,\"selling_price\":850,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 15:03:17', '2026-09-29 12:57:10', NULL),
(10, 1, 'Desi Chicken Mix Curry Cut', 'desi-chicken-mix-curry-cut', 'Desi Chicken – Authentic country chicken with a naturally rich taste, tender texture, and fresh quality. A wholesome choice for those who love the true taste of chicken.', 600.00, 600.00, 'gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/Wf8I9IzRxOwUxNUqrTNKPHmRyseeUewN0Ecg7KED.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":600,\"selling_price\":600,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":1100,\"selling_price\":1100.02,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 15:43:30', '2026-09-23 15:43:30', NULL),
(11, 1, 'Chicken Whole', 'chicken-whole', 'Whole broiler chicken bird, dressed, cleaned, and gutted. Perfect for whole roasts or family curries.', 450.00, 450.00, 'Piece', 'Pc', NULL, NULL, NULL, 1, '[\"products\\/sWNCKVDNtIZp7pJjFZn4QIW8uODMrGp9Qh0G0EM2.avif\"]', '[]', '[{\"quantity\":\"1 Piece (1 Kg- 1.5 kg)\",\"unit\":\"Pc\",\"piece\":\"\",\"mrp\":450,\"selling_price\":450,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 15:47:29', '2026-09-23 15:47:29', NULL),
(12, 1, 'Chicken Leg Piece', 'chicken-leg-piece', 'Tender chicken drumsticks and thighs, perfect for roasting, curries, or tandoori preparation.', 350.00, 359.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/W3rkp3srrkb7BGLVIpy5X8d5GmGD4p5Q3o2ovkM3.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":359,\"selling_price\":350,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"2.5% OFF\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":650,\"selling_price\":650,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 15:49:22', '2026-09-23 15:49:22', NULL),
(13, 1, 'Chicken Wings', 'chicken-wings', 'Juicy, plump chicken wings. Ideal for barbecue grilling, baking, or frying.', 350.00, 350.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/f5oRyikiLlwMKUCm4vwnNn4zDRF3mFw417uegg1M.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":350,\"selling_price\":350,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":650,\"selling_price\":650,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 15:51:30', '2026-09-23 15:53:40', NULL),
(14, 1, 'Chicken Breast Boneless', 'chicken-breast-boneless', '100% skinless boneless chicken breast fillets. High-protein, low-fat premium cut for gym-goers or grilling.', 375.00, 375.00, 'gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/nrVcfBiH4cunjbNSHyk3zH3881VL9fNPAyaDIrwy.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":375,\"selling_price\":375,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":600,\"selling_price\":600,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 15:54:47', '2026-09-23 15:55:09', NULL),
(15, 1, 'Chicken Liver', 'chicken-liver', 'Nutrient-rich, extremely fresh chicken liver. Soft texture, ideal for sautéing or gourmet pâtés.', 350.00, 350.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/lERiATDbbfbTjR3jzumiTdPZGX906l5a1fgl9pnL.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":350,\"selling_price\":350,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":450,\"selling_price\":450,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 15:56:43', '2026-09-23 15:56:43', NULL),
(17, 1, 'Chicken Gizzards (ready to cook)', 'chicken-gizzards-ready-to-cook', 'Fresh, cleaned chicken gizzards. Tenderized and packed with high protein and low fat.', 359.00, 350.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/d7dyNmAiiOj9tXl7iIshgz6CnJeK6VR69sXjHdCH.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":350,\"selling_price\":359,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":600,\"selling_price\":600,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 15:58:17', '2026-09-28 12:52:57', NULL),
(18, 1, 'Chicken Mix Curry Cut', 'chicken-mix-curry-cut', 'Perfect mix of bone-in and boneless pieces for traditional curries, stews, or biryanis.', 300.00, 300.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/ktQcNzxR3QDqBMQAniAOOK6RERupADewiZYIctDa.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":300,\"selling_price\":300,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":550,\"selling_price\":550,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 15:59:51', '2026-09-23 15:59:51', NULL),
(19, 1, 'chicken feet (ready to cook)', 'chicken-feet-ready-to-cook', 'Fresh and cleaned chicken feet, rich in collagen and perfect for rich soups or broths.', 320.00, 320.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/CvdGSrlTRdW5mapAoiS3oW0hQ2SZZTUi5Yb8npTY.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":320,\"selling_price\":320,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":600,\"selling_price\":600,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:02:04', '2026-09-23 16:02:04', NULL),
(20, 2, 'Mutton Fat', 'mutton-fat', 'Fresh mutton fat chunks, processed hygienically to add richness to your home-cooked mutton dishes.', 600.00, 600.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/dUjoulKvex3xqTMOAO92OVtCulAsCRD39kxRrkn8.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":600,\"selling_price\":600,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":1100,\"selling_price\":1100,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:03:37', '2026-09-23 16:03:37', NULL),
(21, 2, 'Mutton Mince', 'mutton-mince', 'Finely minced mutton (keema) made from fresh, fat-free boneless goat meat. Ideal for kebabs or mince curry.', 800.00, 800.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/DW1h43xIqs1JJz5Wxh783WzfRf99HTRw2Q5l8TtQ.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":800,\"selling_price\":800,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":1500,\"selling_price\":1500,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:04:52', '2026-09-23 16:04:52', NULL),
(22, 2, 'Mutton Liver', 'mutton-liver', 'Fresh, nutritious mutton liver (kaleji). Rich iron content, soft texture, clean cut.', 500.00, 500.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/pr6pMpOilr9ubcvK6ULH8mA5Sq9j5opYJq2p04IJ.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":500,\"selling_price\":500,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":950,\"selling_price\":950,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:06:35', '2026-09-23 16:06:35', NULL),
(23, 2, 'Mutton Lungs', 'mutton-lungs', 'Fresh, cleaned goat lungs. Nutritious and processed under strict temperature control.', 450.00, 450.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/oMIBJtzFE2WqVP3FAoHdYGNiEjNYTZLrSya3yrz1.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":450,\"selling_price\":450,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":850,\"selling_price\":850,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:07:51', '2026-09-23 16:07:51', NULL),
(24, 2, 'Mutton Totters', 'mutton-totters', 'Cleaned mutton trotters (paya), processed thoroughly. Perfect for preparing traditional rich paya soup.', 700.00, 700.00, 'Piece', 'Pc', NULL, NULL, NULL, 1, '[\"products\\/ilIYHP7RiCoMSInqt9vYYr8wkgvKhiqX1n3SQaZn.avif\"]', '[]', '[{\"quantity\":\"2\",\"unit\":\"Pc\",\"piece\":\"\",\"mrp\":700,\"selling_price\":700,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"4\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":1300,\"selling_price\":1300,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:09:12', '2026-09-23 16:09:12', NULL),
(25, 2, 'Mutton Boneless', 'mutton-boneless', '100% boneless goat meat chunks cut from prime leg and shoulder portions. Extremely tender.', 950.00, 950.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/ZgyvbUrCRRIpVqyyb3RaYASJKjao2sAjZYN1KnNs.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":950,\"selling_price\":950,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":1950,\"selling_price\":1950,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:10:35', '2026-09-23 16:10:35', NULL),
(26, 2, 'Mutton Chops', 'mutton-chops', 'Premium mutton rib chops. Tender meat on the bone, ideal for grilling, frying, or chops masala.', 850.00, 850.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/2rKCop1vStAH7EYXAUQWUYXlc6sRZIDATAAGm4YL.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":850,\"selling_price\":850,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":1650,\"selling_price\":1650,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:12:06', '2026-09-23 16:12:06', NULL),
(27, 2, 'Mutton Mix Curry Cut', 'mutton-mix-curry-cut', 'Pasture-raised, tender goat mutton mix cut. Perfect bone-in and boneless pieces for traditional curries.', 750.00, 750.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/agSOZShU5qEfFb2RVPJkx54ZhkbC0UdyEamA335g.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":750,\"selling_price\":750,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":1450,\"selling_price\":1450,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:14:04', '2026-09-23 16:14:04', NULL),
(28, 11, 'Quail Whole', 'quail-whole', 'Cleaned, dressed, and skinless whole quail bird. Premium gamey meat perfect for roasting or baking.', 350.00, 350.00, 'Piece', 'Pc', NULL, NULL, NULL, 1, '[\"products\\/3l4eLbE6gC2xMAwllStQci3mOU1Xnq8NwLLcgvxf.avif\"]', '[]', '[{\"quantity\":\"1\",\"unit\":\"Pc\",\"piece\":\"\",\"mrp\":350,\"selling_price\":350,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:15:40', '2026-09-23 16:15:40', NULL),
(29, 11, 'Quail Mix Curry Cut', 'quail-mix-curry-cut', 'Farm-fresh quail meat cut into perfect pieces for curry or frying. Extremely high nutrient value.', 800.00, 800.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/c5lIvuveGquowSrL4WfIv3qaMRZPtH3knTy5vmpY.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":800,\"selling_price\":800,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:16:42', '2026-09-23 16:16:42', NULL),
(30, 10, 'Pork belly slice', 'pork-belly-slice', 'Premium quality Pork Belly Slices – fresh, tender, and perfectly cut for BBQ, grilling, roasting, or crispy bacon-style recipes. Hygienically packed to deliver rich flavor and superior quality in every bite.', 400.00, 400.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/n1OaU7R9kL3ytevV52TYVsUn6URVWkGxVDei7f9V.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":400,\"selling_price\":400,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":750,\"selling_price\":750,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:19:11', '2026-09-23 16:19:11', NULL),
(31, 10, 'Pork Minced', 'pork-minced', 'Fresh minced pork, finely ground and easy to cook. Perfect for kebabs, meatballs, curries, stuffing, stir-fries, or flavorful home-style dishes.', 397.00, 397.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/PtbHdpjVO1ol0hxxhzqR0eprXSGWuhDVPLDEHqLa.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":397,\"selling_price\":397,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":750,\"selling_price\":750,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:20:20', '2026-09-23 16:20:20', NULL),
(32, 10, 'Pork Tenderloin', 'pork-tenderloin', 'Fresh pork tenderloin, lean, soft, and tender in texture. Perfect for grilling, roasting, frying, or preparing light curries with rich flavor and easy cooking.', 500.00, 500.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/tgrY7bjO5ga3iPLU30gxvZigRukAjeGBDtIlNCUl.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":500,\"selling_price\":500,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":950,\"selling_price\":950,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:21:37', '2026-09-23 16:21:37', NULL),
(33, 10, 'Pork Chop Without Skin', 'pork-chop-without-skin', 'Fresh pork chop without skin, tender and meaty with rich flavor. Perfect for grilling, frying, roasting, or preparing flavorful curries with spices.', 700.00, 700.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/oc2BOEKYkTraqjFfhWNwHjnxhKK1dW8deaFU2HcC.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":700,\"selling_price\":700,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":1350,\"selling_price\":1350,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:22:50', '2026-09-23 16:22:50', NULL),
(34, 10, 'Pork Chop With Skin', 'pork-chop-with-skin', 'Fresh pork chop with skin, juicy and rich in flavor. Perfect for grilling, frying, roasting, or curries, with the skin adding extra taste and texture.', 600.00, 600.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/0oSviQTgbPewD0SKdSTJ7N2eiB2GwY92t1Rw1XNR.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":600,\"selling_price\":600,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":1150,\"selling_price\":1150,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:24:18', '2026-09-23 16:24:18', NULL),
(35, 10, 'Pork Boneless', 'pork-boneless', 'Fresh boneless pork, tender and easy to cook. Perfect for curries, frying, roasting, grilling, or stir-fry dishes, with juicy pieces that absorb spices beautifully.', 400.00, 400.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/RYeEx1L5zBji1fQUp0rrIb7hdbwKyVGbYZ4VWJtY.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":400,\"selling_price\":400,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":799,\"selling_price\":799,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:25:37', '2026-09-23 16:25:37', NULL),
(36, 10, 'Pork Ribs With Skin', 'pork-ribs-with-skin', 'Fresh pork ribs with skin, rich in flavor and perfect for curries, roasting, grilling, or slow cooking. The skin and bone add extra taste, texture, and depth to traditional dishes.', 500.00, 500.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/i5TWqD3CnkyV1qEOVCHnPge11ZdAvLuJ23mSR5OY.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":500,\"selling_price\":500,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"950\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":950,\"selling_price\":959,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:27:11', '2026-09-23 16:27:11', NULL),
(37, 10, 'Pork Spare Ribs Without Skin', 'pork-spare-ribs-without-skin', 'Fresh pork ribs without skin, meaty and flavorful, ideal for grilling, roasting, curries, and slow-cooked dishes. Bone-in pieces add rich taste and depth to every preparation.', 600.00, 600.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/DrsSbqsYDItHxYxxYy2mLTV8s0uPUU8tQyWxZTZO.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":600,\"selling_price\":600,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":1150,\"selling_price\":1150,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:28:22', '2026-09-23 16:28:22', NULL),
(38, 10, 'Pork Spare Ribs with Skin', 'pork-spare-ribs-with-skin', 'Fresh pork spare ribs with skin, juicy and rich in flavor. Perfect for grilling, roasting, curries, or slow cooking, with skin and bone adding extra taste and texture.', 497.00, 497.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/kAJeAcldvG269mkerunQXGb4hoxCuNPlGme3cihh.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":497,\"selling_price\":497,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":950,\"selling_price\":950,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:30:00', '2026-09-23 16:30:00', NULL),
(39, 10, 'Pork Spare Ribs with Skin', 'pork-spare-ribs-with-skin-2', 'Fresh pork spare ribs with skin, juicy and rich in flavor. Perfect for grilling, roasting, curries, or slow cooking, with skin and bone adding extra taste and texture.', 497.00, 497.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/XY5jP060lmhGoYVqWDUHXBL317uPnEVNmGqarcVm.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":497,\"selling_price\":497,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":950,\"selling_price\":950,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:30:03', '2026-09-23 16:30:03', NULL),
(40, 10, 'Pork Ribs Without Skin', 'pork-ribs-without-skin', 'Fresh pork ribs without skin, meaty and rich in flavor. Perfect for curries, grilling, roasting, or slow cooking, with bone-in pieces that add deep taste to every dish.', 600.00, 600.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/yei7Y5ZqyD51jpJvcZn64DSt0PODrmVJEPJdzBzu.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":600,\"selling_price\":600,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":1150,\"selling_price\":1150,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:32:02', '2026-09-23 16:32:02', NULL),
(41, 10, 'Pork Shoulder Cut', 'pork-shoulder-cut', 'Fresh pork shoulder, tender and flavorful with a balanced mix of meat and fat. Ideal for curries, roasting, grilling, or slow cooking for rich traditional taste.', 350.00, 350.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/Odh2a0Ho5nuTGO9i62sIAPMv53tdAoo8tNa2Wxlk.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":350,\"selling_price\":350,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":700,\"selling_price\":700,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:32:57', '2026-09-23 16:32:57', NULL),
(42, 10, 'Pork Thai Cut', 'pork-thai-cut', 'Fresh pork thigh cut, tender and flavorful, perfect for curries, frying, roasting, or slow cooking. Juicy meat pieces that absorb spices well for rich traditional dishes.', 350.00, 350.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/gCrgky9TbuahpyskeruAeZTXdVjjb76P9W2oVrDm.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":350,\"selling_price\":350,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":700,\"selling_price\":700,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:34:23', '2026-09-23 16:34:23', NULL),
(43, 10, 'Pork Shank', 'pork-shank', 'Fresh pork shank, rich in flavor and perfect for slow cooking, soups, stews, and curries. Its firm meat and bone-in texture add deep taste to traditional dishes.', 400.00, 400.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/tyGYI3XdEC69fcRjehsKiYd4KZA414kAlrZ2Yaum.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":400,\"selling_price\":400,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":747,\"selling_price\":747,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:35:45', '2026-09-23 16:35:45', NULL),
(44, 10, 'Pork Lungs', 'pork-lungs', 'Fresh pork lungs, light and soft in texture, suitable for traditional curries, frying, or slow cooking with spices. Ideal for preparing rich and flavorful home-style dishes.', 349.00, 349.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/CRnUjnmHkxmcOxJQfseH4vc31Mf4vBnCAGSCe5zU.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":349,\"selling_price\":349,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":699,\"selling_price\":699,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:36:39', '2026-09-23 16:46:28', NULL),
(45, 10, 'Pork Kidney', 'pork-kidney', 'Fresh pork kidney, known for its distinct taste and firm texture. Ideal for frying, sautéing, or preparing flavorful curries with spices and traditional seasoning.', 200.00, 200.00, '1 piece (150gm- 200gm)', 'Pc', NULL, NULL, NULL, 1, '[\"products\\/Wmlcvo26bMfuhvvPT8IN9k4yQVmdXjxiE8fzirY1.avif\"]', '[]', '[{\"quantity\":\"1\",\"unit\":\"Pc\",\"piece\":\"\",\"mrp\":200,\"selling_price\":200,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:38:29', '2026-09-23 16:39:19', NULL),
(46, 10, 'Pork heart', 'pork-heart', 'Fresh pork heart, firm in texture and rich in flavor. Ideal for curries, stir-fries, grilling, or slow cooking with spices for a hearty traditional dish.', 200.00, 200.00, 'Piece', 'Pc', NULL, NULL, NULL, 1, '[\"products\\/TPMQswLhZff18viQZJh86ID8bYvJ5RV9YUgmZlM8.avif\"]', '[]', '[{\"quantity\":\"1\",\"unit\":\"Pc\",\"piece\":\"\",\"mrp\":200,\"selling_price\":200,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:39:11', '2026-09-23 16:39:11', NULL),
(47, 10, 'Pork Head Cut', 'pork-head-cut', 'Fresh pork head cut, rich in flavor and ideal for curries, soups, stews, and slow-cooked traditional dishes. Perfect for adding deep taste and texture to hearty meals.', 342.00, 342.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/86jpmbr9fuKClOUJY4Dy4Vsh0PvvIqKNQoWPZ9Wt.avif\"]', '[]', '[{\"quantity\":\"400\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":342,\"selling_price\":342,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":698,\"selling_price\":698,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:40:36', '2026-09-23 16:40:36', NULL),
(48, 10, 'Pork trotters', 'pork-trotters', 'Fresh pork trotters, rich in flavor and natural gelatin, perfect for soups, stews, curries, and slow-cooked traditional dishes. Ideal for adding deep taste and texture.', 459.00, 450.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/uq9s6Suyd2AK4e4m03EdsEnaNbzA0AEQC4Ps18hH.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":450,\"selling_price\":459,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":847,\"selling_price\":847,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:41:39', '2026-09-23 16:41:39', NULL),
(49, 10, 'Pork Belly Slab', 'pork-belly-slab', 'Fresh pork belly slab with rich layers of meat and fat, perfect for roasting, grilling, slow cooking, or making flavorful curries. Juicy, tender, and ideal for deep, authentic taste.', 500.00, 600.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/MJ6Akm6w7ZQBNHnv3Gb4v6Z83HCLcnKVZXR8B04M.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":600,\"selling_price\":500,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"16.7% OFF\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":1050,\"selling_price\":900,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"14.3% OFF\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:48:07', '2026-09-28 03:29:29', NULL),
(50, 10, 'Pork Brain', 'pork-brain', 'Fresh pork brain, soft and delicate in texture, ideal for frying, sautéing, or preparing rich traditional curries. Best cooked with spices for a creamy and flavorful dish.', 398.00, 398.00, 'Gram', 'Pc', NULL, NULL, NULL, 1, '[\"products\\/OKn2FA1pHE1UaA7Z5szUZPOJRtQrKabJHqQ4C8Si.avif\"]', '[]', '[{\"quantity\":\"1\",\"unit\":\"Pc\",\"piece\":\"\",\"mrp\":398,\"selling_price\":398,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:49:17', '2026-09-23 16:49:17', NULL),
(51, 10, 'Pork Mix Curry Cut', 'pork-mix-curry-cut', 'Fresh pork mix curry cut, perfectly portioned for rich and flavorful curries. Tender, juicy pieces with a balanced mix of meat and fat, ideal for traditional home-style cooking.', 399.00, 399.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/kibCRzsyJk3lKbVfnCSUcUmBF7hsKRyUTpLhE52I.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":399,\"selling_price\":399,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":750,\"selling_price\":750,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:50:33', '2026-09-23 16:50:33', NULL),
(54, 8, 'Premium Desi Eggs', 'premium-desi-eggs', 'Organic free-range desi country eggs. Deep orange yolks and rich nutrition. Tray of 25 pieces.', 550.00, 550.00, '1 tray / 25 pieces', 'Pc', NULL, NULL, NULL, 1, '[\"products\\/Z6RPqnoo5TSPv2Fm0XSyovuo5l1EzNlpDi5dAogp.avif\"]', '[]', '[{\"quantity\":\"1\",\"unit\":\"Pc\",\"piece\":\"\",\"mrp\":550,\"selling_price\":550,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:53:48', '2026-09-23 16:54:11', NULL),
(55, 8, 'Premium Eggs', 'premium-eggs', 'Cleaned, farm-fresh premium table eggs. Loaded with high protein. Tray of 25 pieces.', 400.00, 400.00, '1 tray / 25 pieces', 'Pc', NULL, NULL, NULL, 1, '[\"products\\/hp6ieXkfqOdbWxjEfMfqFNLhcL6agTCx4HHNC9Ig.avif\"]', '[]', '[{\"quantity\":\"1\",\"unit\":\"Pc\",\"piece\":\"\",\"mrp\":400,\"selling_price\":400,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:55:13', '2026-09-23 16:55:13', NULL),
(56, 8, 'Quail Eggs', 'quail-eggs', 'Tiny, speckled quail eggs packed with vitamins, iron, and rich yolk flavor. Pack of 30.', 450.00, 450.00, '1 tray / 25 pieces', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/Wy22A8k3Izo4o0a51Q0ENddQ6jK1eKRBzasRbwlP.avif\"]', '[]', '[{\"quantity\":\"1\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":450,\"selling_price\":450,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:56:24', '2026-09-23 16:56:24', NULL),
(57, 12, 'Pork Fat oil', 'pork-fat-oil', 'Crafted from premium-quality pork fat, carefully prepared to deliver a rich and authentic experience. Pork fat Oil — timeless quality, refined naturally.', 350.00, 350.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/DslQzFW43vJ3b5F2UK2XI6Zw1KLpRcGvS1e4aYDR.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":350,\"selling_price\":350,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":500,\"selling_price\":500,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:58:10', '2026-09-23 16:58:25', NULL),
(58, 12, 'Pork Sausage( Gujji)', 'pork-sausage-gujji', 'Fresh Pork Sausage made with quality pork and balanced seasoning for a rich, juicy, and flavourful taste. Perfect for grilling, frying, pan-searing, or adding to rolls, sandwiches, breakfast plates, and quick meals. A delicious choice for meat lovers who enjoy bold and smoky flavours.', 449.00, 449.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/KVb99zab1WchgGqC1vNDqhV4zZEWHpdnmrVx4bn8.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":449,\"selling_price\":449,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":849,\"selling_price\":849,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 16:59:30', '2026-09-23 16:59:30', NULL),
(59, 13, 'Pork Pickle', 'pork-pickle', 'Flavorful pork pickle made with tender pork pieces, aromatic spices, and rich seasoning. Perfect as a spicy side dish with rice, roti, paratha, or traditional meals', 1200.00, 1200.00, 'Gram', 'Pc', NULL, NULL, NULL, 1, '[\"products\\/fhe5PczNq7w0KFjIsVrHG25aV1LclQGaWcCeMSLL.avif\"]', '[]', '[{\"quantity\":\"1\",\"unit\":\"Pc\",\"piece\":\"\",\"mrp\":1200,\"selling_price\":1200,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 17:00:54', '2026-09-23 17:02:59', NULL),
(60, 13, 'Pork teeth', 'pork-teeth', 'Pork teeth, 1 piece, approximately 2 inches in size. Cleaned and naturally firm, suitable for traditional use, display, or specific preparation needs.', 1099.00, 1099.00, '1 Piece', 'Pc', NULL, NULL, NULL, 1, '[\"products\\/cWUPg53lbpRzaEdcloTk46MRKOoIXa21wzddKVo8.avif\"]', '[]', '[{\"quantity\":\"1\",\"unit\":\"Pc\",\"piece\":\"\",\"mrp\":1099,\"selling_price\":1099,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 17:02:30', '2026-09-23 17:02:30', NULL),
(61, 13, 'Pork Cooking Oil', 'pork-cooking-oil', 'Pure, premium-quality cooking oil for delicious taste and everyday cooking.', 500.00, 500.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/4IUAOu1FnCncN73JuLxGk9OtJ55auOj6TgPTrJtd.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":500,\"selling_price\":500,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 17:03:50', '2026-09-25 15:45:21', NULL),
(62, 13, 'Chicken Pickle', 'chicken-pickle', 'Boneless chicken meat pieces pickled in traditional Punjabi style with premium spices and oils.', 1300.00, 1300.00, 'Gram', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/tHpWRmAMRG8bYLrc4eYHpRzImYaWflp0DVFR2Ixr.avif\"]', '[]', '[{\"quantity\":\"1\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":1300,\"selling_price\":1300,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-23 17:04:35', '2026-09-23 17:04:35', NULL),
(63, 6, 'Chicken Live', 'chicken-live', 'Fresh live chicken, carefully sourced for quality and freshness. Ideal for preparing homemade chicken curry, roast, fry, biryani, and other traditional dishes.', 0.00, 0.00, NULL, 'Unit', '9217577006', 'Freshly sliced, vacuum packed immediately', 'Chilled home delivery in 2 hours across Delhi', 1, '[\"products\\/qVdM4QRrwLUejjDGP2SX8lAK0TwWuETgRtgzt502.avif\"]', '[]', '[]', 1, '2026-09-23 17:06:43', '2026-09-23 17:06:43', NULL),
(64, 6, 'Desi Chicken Live', 'desi-chicken-live', 'Fresh live desi chicken, known for its natural taste, firm texture, and rich flavour. Ideal for traditional curries, slow-cooked dishes, and homemade chicken recipes.', 0.00, 0.00, NULL, 'Unit', '9217577006', 'Freshly sliced, vacuum packed immediately', 'Chilled home delivery in 2 hours across Delhi', 1, '[\"products\\/HlHACsJKMrBBNpySU33v2P8HEtbi6KhkdI6T42l7.avif\"]', '[]', '[]', 1, '2026-09-23 17:07:49', '2026-09-23 17:07:49', NULL),
(65, 6, 'Pork Live', 'pork-live', 'Fresh live pig, suitable for quality pork production and traditional meat preparation. The pig appears to be a **Large White/Yorkshire-type breed**, known for its clean pink skin, good body growth, lean meat quality, and tender texture. Ideal for customers looking for a healthy live pig with good meat yield, natural freshness, and rich pork flavour for curry, roast, BBQ, slow-cooked dishes, and homemade recipes.', 0.00, 0.00, NULL, 'Unit', '9217577006', 'Freshly sliced, vacuum packed immediately', 'Chilled home delivery in 2 hours across Delhi', 1, '[\"products\\/gTDRfl1HRnin4wMB0m0BNdPGP28Tv3jkQ6HVRkYt.avif\"]', '[]', '[]', 1, '2026-09-23 17:08:50', '2026-09-23 17:08:50', NULL),
(66, 6, 'Goat Live', 'goat-live', 'Healthy pasture-raised live goats, raised organically on sustainable green pastures. Call to order.', 0.00, 0.00, NULL, 'Unit', '9217577006', 'Freshly sliced, vacuum packed immediately', 'Chilled home delivery in 2 hours across Delhi', 1, '[\"products\\/vr1lIw675MUhxS7BHMT1asjN6BGNwcu8zm9p02hd.avif\"]', '[]', '[]', 1, '2026-09-23 17:09:49', '2026-09-23 17:09:49', NULL),
(67, 6, 'Duck Live', 'duck-live', 'Healthy farm-bred live duck. Contact us at 9217577006 to purchase.', 0.00, 0.00, NULL, 'Unit', '9217577006', 'Freshly sliced, vacuum packed immediately', 'Chilled home delivery in 2 hours across Delhi', 1, '[\"products\\/J9it3bfjGkpqqYOAT1xREFGs0hQYkAd5HCqIC6QA.avif\"]', '[]', '[]', 1, '2026-09-23 17:10:40', '2026-09-23 17:10:40', NULL),
(68, 6, 'Quail Live', 'quail-live', 'Healthy and premium pasture-raised live quail (Batair) birds.', 0.00, 0.00, NULL, 'Unit', '9217577006', 'Freshly sliced, vacuum packed immediately', 'Chilled home delivery in 2 hours across Delhi', 1, '[\"products\\/qg1vPdIDHD8JeVYvG8NUDZyvlDEUsYwB3O1C4zFl.avif\"]', '[]', '[]', 1, '2026-09-23 17:13:01', '2026-09-23 17:13:01', NULL),
(73, 24, 'Duck MixCurry Cut', 'duck-mixcurry-cut', 'Premium duck meat, freshly cut into perfectly sized pieces for a delicious curry.\r\nTender, flavorful, and perfect for preparing a rich and hearty home-cooked meal.', 600.00, 650.00, '450gm', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/OTnGVdjrPVFqPWxQm8p44PSKfsoePsIZ5Ymo9kIJ.avif\"]', '[]', '[{\"quantity\":\"450\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":650,\"selling_price\":600,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"7.7% OFF\",\"admin_amount\":0,\"vendor_amount\":0},{\"quantity\":\"900\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":1300,\"selling_price\":1050,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"19.2% OFF\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-28 13:40:41', '2026-09-28 13:47:40', NULL),
(74, 24, 'Duck whole', 'duck-whole', 'Premium whole duck, carefully selected for its tender meat and rich, natural flavor.\r\nFresh, juicy, and perfect for roasting, grilling, or preparing a delicious curry.', 1050.00, 1250.00, '1.kg to 1200gm', 'Gram', NULL, NULL, NULL, 1, '[\"products\\/f33JxWY9ssc6aVHgyFqT6ixnMvczwVqWzFgoaFZU.avif\"]', '[]', '[{\"quantity\":\"1kg to 1200\",\"unit\":\"Gram\",\"piece\":\"\",\"mrp\":1250,\"selling_price\":1050,\"today_price\":null,\"tomorrow_price\":null,\"save_offer\":\"16% OFF\",\"admin_amount\":0,\"vendor_amount\":0}]', 1, '2026-09-28 13:53:58', '2026-09-28 13:53:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `razorpay_payments`
--

CREATE TABLE `razorpay_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `razorpay_order_id` varchar(255) NOT NULL,
  `razorpay_payment_id` varchar(255) DEFAULT NULL,
  `razorpay_signature` varchar(255) DEFAULT NULL,
  `amount` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `razorpay_payments`
--

INSERT INTO `razorpay_payments` (`id`, `order_id`, `razorpay_order_id`, `razorpay_payment_id`, `razorpay_signature`, `amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'order_Td2XZWno2a8gbS', NULL, NULL, 54000, 'cancelled', '2026-09-17 18:05:24', '2026-09-17 18:05:44'),
(2, 3, 'order_Td3SayxZHGvY3C', NULL, NULL, 210, 'initiated', '2026-09-17 18:59:23', '2026-09-17 18:59:23'),
(3, 4, 'order_Td3fwJZY6JDKyn', NULL, NULL, 210, 'initiated', '2026-09-17 19:12:01', '2026-09-17 19:12:01'),
(4, 5, 'order_Td3lOYexRvEHvb', NULL, NULL, 33110, 'cancelled', '2026-09-17 19:17:11', '2026-09-17 19:18:05'),
(5, 6, 'order_Td3mlpdoAwBkig', NULL, NULL, 210, 'initiated', '2026-09-17 19:18:29', '2026-09-17 19:18:29');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `display_on` varchar(20) NOT NULL DEFAULT 'home',
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_charge_tiers`
--

CREATE TABLE `service_charge_tiers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `min_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `max_amount` decimal(10,2) DEFAULT NULL,
  `percent` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(30) DEFAULT NULL,
  `delivery_charge` decimal(10,2) DEFAULT NULL,
  `role` enum('customer','admin') NOT NULL DEFAULT 'customer',
  `status` enum('active','blocked') NOT NULL DEFAULT 'active',
  `photo` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `date_of_birth`, `gender`, `delivery_charge`, `role`, `status`, `photo`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Porville Admin', 'admin@porville.in', '$2y$12$izdVOKoAnSZCbtTfkAZlY.yxkNlG.Q8PIutYnBqkmZb7K43Uqx2Ze', '9999999999', NULL, NULL, NULL, 'admin', 'active', 'profiles/porPoC0g5prJvD6CeyPXucwO8ZJCdBCBHPAoIRUl.avif', NULL, '8AVvXCy0sGykVAaAV1uPeHcwdJIA2f1E2jzETloIltDESSo7pMpWyOWj3Z28', '2026-09-17 06:02:37', '2026-09-28 17:04:27'),
(2, 'manoj', 'mt6835176@gmail.com', '$2y$12$kDr3/DDjBOSANU8Q/L26pupykx2IeEpkN4SvN3yVFYU5a2PFmahre', '9084978571', NULL, NULL, NULL, 'customer', 'active', NULL, NULL, 'Go9VnBwSNlEXyvune8tkW4YafQhkSS8f9bzFsC05bFSvNGrc1e1YiDZU7VbQ', '2026-09-17 18:03:06', '2026-09-17 18:05:23'),
(3, 'Ggh', 'arpittdigital@gmail.com', '$2y$12$aPgI3gyogWboU3r.94/E8u9F.dUOxD032Ck4pzKb3lVR0TvszuQyu', '8449201182', NULL, NULL, NULL, 'customer', 'active', NULL, NULL, NULL, '2026-09-17 19:11:19', '2026-09-17 19:12:00'),
(4, 'manoj', 'bmdumanojkumar@gmail.com', '$2y$12$nwwG/lDQXcQUip6nKitTO.WkYwa7QY6i1FQyJE9KDE.jnmQWhylP2', '9084978578', '2004-06-27', 'male', NULL, 'customer', 'active', NULL, NULL, '1MdyutZ91Yl7fIMDKgUypn3FHijblUhWCzforTHluyHkLBGciCqyu0SnZZAs', '2026-09-22 21:28:06', '2026-09-28 22:06:00'),
(5, 'manoj', 'user@gmail.com', '$2y$12$E3RYoQiBCF13hHc2o0Oj6OP.QUz45AVb2jELG/O/.iDCxTKIG0ZI6', NULL, NULL, NULL, NULL, 'customer', 'active', NULL, NULL, NULL, '2026-09-23 19:08:35', '2026-09-23 19:08:35'),
(6, 'Akshat', 'adityapatel@gmail.com', '$2y$12$xZ5HaapFw5bZCeQAWmLl4elubsEyf2W1qDYPjuqDL5cghPh10JkEu', '9910313899', NULL, NULL, NULL, 'customer', 'active', NULL, NULL, NULL, '2026-09-23 19:10:41', '2026-09-23 19:16:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `app_settings_key_unique` (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`),
  ADD KEY `coupons_product_id_foreign` (`product_id`),
  ADD KEY `coupons_entry_type_product_id_is_active_index` (`entry_type`,`product_id`,`is_active`);

--
-- Indexes for table `coupon_user_usages`
--
ALTER TABLE `coupon_user_usages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupon_user_usages_coupon_id_user_id_unique` (`coupon_id`,`user_id`),
  ADD KEY `coupon_user_usages_user_id_foreign` (`user_id`);

--
-- Indexes for table `delivery_boys`
--
ALTER TABLE `delivery_boys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_slots`
--
ALTER TABLE `delivery_slots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `delivery_slots_date_start_time_end_time_unique` (`date`,`start_time`,`end_time`),
  ADD KEY `delivery_slots_date_index` (`date`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faqs_faq_category_id_foreign` (`faq_category_id`);

--
-- Indexes for table `faq_categories`
--
ALTER TABLE `faq_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `faq_categories_slug_unique` (`slug`);

--
-- Indexes for table `home_banners`
--
ALTER TABLE `home_banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_sent_by_foreign` (`sent_by`),
  ADD KEY `notifications_recipient_id_foreign` (`recipient_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_delivery_boy_id_foreign` (`delivery_boy_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_subcategory_id_foreign` (`subcategory_id`);

--
-- Indexes for table `razorpay_payments`
--
ALTER TABLE `razorpay_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `razorpay_payments_razorpay_order_id_unique` (`razorpay_order_id`),
  ADD KEY `razorpay_payments_order_id_foreign` (`order_id`),
  ADD KEY `razorpay_payments_razorpay_order_id_index` (`razorpay_order_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviews_order_id_unique` (`order_id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_status_created_at_index` (`status`,`created_at`),
  ADD KEY `reviews_product_id_status_display_on_index` (`product_id`,`status`,`display_on`);

--
-- Indexes for table `service_charge_tiers`
--
ALTER TABLE `service_charge_tiers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `app_settings`
--
ALTER TABLE `app_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `coupon_user_usages`
--
ALTER TABLE `coupon_user_usages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `delivery_boys`
--
ALTER TABLE `delivery_boys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `delivery_slots`
--
ALTER TABLE `delivery_slots`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `faq_categories`
--
ALTER TABLE `faq_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `home_banners`
--
ALTER TABLE `home_banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `razorpay_payments`
--
ALTER TABLE `razorpay_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_charge_tiers`
--
ALTER TABLE `service_charge_tiers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `coupons`
--
ALTER TABLE `coupons`
  ADD CONSTRAINT `coupons_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `coupon_user_usages`
--
ALTER TABLE `coupon_user_usages`
  ADD CONSTRAINT `coupon_user_usages_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_user_usages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `faqs`
--
ALTER TABLE `faqs`
  ADD CONSTRAINT `faqs_faq_category_id_foreign` FOREIGN KEY (`faq_category_id`) REFERENCES `faq_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_recipient_id_foreign` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `notifications_sent_by_foreign` FOREIGN KEY (`sent_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_delivery_boy_id_foreign` FOREIGN KEY (`delivery_boy_id`) REFERENCES `delivery_boys` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `razorpay_payments`
--
ALTER TABLE `razorpay_payments`
  ADD CONSTRAINT `razorpay_payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
