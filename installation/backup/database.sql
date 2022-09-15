-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jul 05, 2022 at 10:33 AM
-- Server version: 5.7.34
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `6cash_installation`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `receiver` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `business_settings`
--

CREATE TABLE `business_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_settings`
--

INSERT INTO `business_settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'business_name', '6cash', NULL, NULL),
(2, 'currency', 'BDT', NULL, NULL),
(3, 'pagination_limit', '15', NULL, NULL),
(4, 'timezone', 'Asia/Dhaka', NULL, NULL),
(5, 'agent_commission_percent', '10', NULL, NULL),
(6, 'cashout_charge_percent', '5', NULL, NULL),
(7, 'addmoney_charge_percent', '12', NULL, NULL),
(8, 'sendmoney_charge_flat', '10', NULL, NULL),
(9, 'logo', '2022-04-17-625bc937c0958.png', NULL, NULL),
(10, 'phone', '000000000', NULL, NULL),
(11, 'email', 'demo@gmail.com', NULL, NULL),
(12, 'theme', 'theme_1', NULL, NULL),
(13, 'two_factor', '1', NULL, NULL),
(14, 'phone_verification', '1', NULL, NULL),
(15, 'email_verification', NULL, NULL, NULL),
(16, 'refer_commission', NULL, NULL, NULL),
(17, 'address', 'road 12, Dhaka', NULL, NULL),
(18, 'footer_text', 'This is footer test for 6cash', NULL, NULL),
(19, 'currency_symbol_position', 'right', NULL, NULL),
(20, 'admin_commission', NULL, NULL, NULL),
(21, 'country', 'BD', NULL, NULL),
(22, 'ssl_commerz_payment', '{\"status\":\"0\",\"store_id\":\"\",\"store_password\":\"@ssl\"}', '2022-04-07 08:27:08', '2022-04-07 08:27:16'),
(23, 'money_transfer_message', '{\"status\":1,\"message\":\"EMoney Transfer from admin\"}', NULL, NULL),
(24, 'cash_in', '{\"status\":1,\"message\":\"Cash In successfully completed.\"}', NULL, NULL),
(25, 'cash_out', '{\"status\":1,\"message\":\"Cash Out\"}', NULL, NULL),
(26, 'send_money', '{\"status\":1,\"message\":\"Send Money successfully completed.\"}', NULL, NULL),
(27, 'request_money', '{\"status\":1,\"message\":\"Money successfully requested.\"}', NULL, NULL),
(28, 'denied_money', '{\"status\":1,\"message\":\"Denied Money\"}', NULL, NULL),
(29, 'approved_money', '{\"status\":1,\"message\":\"Approved Money\"}', NULL, NULL),
(30, 'add_money', '{\"status\":1,\"message\":\"Added to your account.\"}', NULL, NULL),
(31, 'received_money', '{\"status\":1,\"message\":\"Received Money\"}', NULL, NULL),
(32, 'push_notification_key', 'add-token', NULL, NULL),
(33, 'language', '[{\"id\":\"1\",\"name\":\"english\",\"direction\":\"ltr\",\"code\":\"en\",\"status\":1,\"default\":false},{\"id\":2,\"name\":\"English\",\"direction\":\"ltr\",\"code\":\"en-US\",\"status\":0,\"default\":false},{\"id\":3,\"name\":\"Bangla\",\"direction\":\"ltr\",\"code\":\"bn\",\"status\":1,\"default\":true},{\"id\":4,\"name\":\"Arabic\",\"direction\":\"rtl\",\"code\":\"ar\",\"status\":0,\"default\":false}]', NULL, '2022-04-19 09:20:45'),
(34, 'paypal', '{\"status\":\"0\",\"paypal_client_id\":\"\",\"paypal_secret\":\"\"}', '2022-04-10 09:41:04', '2022-04-10 09:41:24'),
(35, 'privacy_policy', '', NULL, NULL),
(36, 'terms_and_conditions', '', NULL, NULL),
(37, 'inactive_auth_minute', '30', NULL, NULL),
(38, 'about_us', '', NULL, NULL),
(39, 'stripe', '{\"status\":\"0\",\"api_key\":\"\",\"published_key\":\"\"}', '2022-04-16 07:43:45', '2022-04-19 06:30:02'),
(40, 'razor_pay', '{\"status\":\"0\",\"razor_key\":\"\",\"razor_secret\":\"\"}', '2022-04-16 08:04:01', '2022-04-19 06:31:39'),
(41, 'paystack', '{\"status\":\"0\",\"publicKey\":\"\",\"secretKey\":\"\",\"paymentUrl\":\"https:\\/\\/api.paystack.co\",\"merchantEmail\":\"demo@gmail.com\"}', '2022-04-16 08:04:29', '2022-04-19 06:29:56'),
(42, 'bkash', '{\"status\":\"0\",\"api_key\":null,\"api_secret\":null,\"username\":null,\"password\":null}', NULL, NULL),
(43, 'paymob', '{\"status\":\"0\",\"api_key\":\"==\",\"iframe_id\":\"\",\"integration_id\":\"\",\"hmac\":\"\"}', NULL, NULL),
(44, 'mercadopago', '{\"status\":\"0\",\"public_key\":\"\",\"access_token\":\"\"}', NULL, NULL),
(45, 'flutterwave', '{\"status\":\"0\",\"public_key\":\"\",\"secret_key\":\"\",\"hash\":\"\"}', NULL, NULL),
(46, 'senang_pay', '{\"status\":\"0\",\"secret_key\":\"\",\"merchant_id\":\"\"}', '2022-04-16 08:05:57', '2022-04-16 08:17:12'),
(47, 'app_theme', '1', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exchange_rate` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `country`, `currency_code`, `currency_symbol`, `exchange_rate`, `created_at`, `updated_at`) VALUES
(1, 'US Dollar', 'USD', '$', '1.00', NULL, NULL),
(2, 'Canadian Dollar', 'CAD', 'CA$', '1.00', NULL, NULL),
(3, 'Euro', 'EUR', '€', '1.00', NULL, NULL),
(4, 'United Arab Emirates Dirham', 'AED', 'د.إ.‏', '1.00', NULL, NULL),
(5, 'Afghan Afghani', 'AFN', '؋', '1.00', NULL, NULL),
(6, 'Albanian Lek', 'ALL', 'L', '1.00', NULL, NULL),
(7, 'Armenian Dram', 'AMD', '֏', '1.00', NULL, NULL),
(8, 'Argentine Peso', 'ARS', '$', '1.00', NULL, NULL),
(9, 'Australian Dollar', 'AUD', '$', '1.00', NULL, NULL),
(10, 'Azerbaijani Manat', 'AZN', '₼', '1.00', NULL, NULL),
(11, 'Bosnia-Herzegovina Convertible Mark', 'BAM', 'KM', '1.00', NULL, NULL),
(12, 'Bangladeshi Taka', 'BDT', '৳', '1.00', NULL, NULL),
(13, 'Bulgarian Lev', 'BGN', 'лв.', '1.00', NULL, NULL),
(14, 'Bahraini Dinar', 'BHD', 'د.ب.‏', '1.00', NULL, NULL),
(15, 'Burundian Franc', 'BIF', 'FBu', '1.00', NULL, NULL),
(16, 'Brunei Dollar', 'BND', 'B$', '1.00', NULL, NULL),
(17, 'Bolivian Boliviano', 'BOB', 'Bs', '1.00', NULL, NULL),
(18, 'Brazilian Real', 'BRL', 'R$', '1.00', NULL, NULL),
(19, 'Botswanan Pula', 'BWP', 'P', '1.00', NULL, NULL),
(20, 'Belarusian Ruble', 'BYN', 'Br', '1.00', NULL, NULL),
(21, 'Belize Dollar', 'BZD', '$', '1.00', NULL, NULL),
(22, 'Congolese Franc', 'CDF', 'FC', '1.00', NULL, NULL),
(23, 'Swiss Franc', 'CHF', 'CHf', '1.00', NULL, NULL),
(24, 'Chilean Peso', 'CLP', '$', '1.00', NULL, NULL),
(25, 'Chinese Yuan', 'CNY', '¥', '1.00', NULL, NULL),
(26, 'Colombian Peso', 'COP', '$', '1.00', NULL, NULL),
(27, 'Costa Rican Colón', 'CRC', '₡', '1.00', NULL, NULL),
(28, 'Cape Verdean Escudo', 'CVE', '$', '1.00', NULL, NULL),
(29, 'Czech Republic Koruna', 'CZK', 'Kč', '1.00', NULL, NULL),
(30, 'Djiboutian Franc', 'DJF', 'Fdj', '1.00', NULL, NULL),
(31, 'Danish Krone', 'DKK', 'Kr.', '1.00', NULL, NULL),
(32, 'Dominican Peso', 'DOP', 'RD$', '1.00', NULL, NULL),
(33, 'Algerian Dinar', 'DZD', 'د.ج.‏', '1.00', NULL, NULL),
(34, 'Estonian Kroon', 'EEK', 'kr', '1.00', NULL, NULL),
(35, 'Egyptian Pound', 'EGP', 'E£‏', '1.00', NULL, NULL),
(36, 'Eritrean Nakfa', 'ERN', 'Nfk', '1.00', NULL, NULL),
(37, 'Ethiopian Birr', 'ETB', 'Br', '1.00', NULL, NULL),
(38, 'British Pound Sterling', 'GBP', '£', '1.00', NULL, NULL),
(39, 'Georgian Lari', 'GEL', 'GEL', '1.00', NULL, NULL),
(40, 'Ghanaian Cedi', 'GHS', 'GH¢', '1.00', NULL, NULL),
(41, 'Guinean Franc', 'GNF', 'FG', '1.00', NULL, NULL),
(42, 'Guatemalan Quetzal', 'GTQ', 'Q', '1.00', NULL, NULL),
(43, 'Hong Kong Dollar', 'HKD', 'HK$', '1.00', NULL, NULL),
(44, 'Honduran Lempira', 'HNL', 'L', '1.00', NULL, NULL),
(45, 'Croatian Kuna', 'HRK', 'kn', '1.00', NULL, NULL),
(46, 'Hungarian Forint', 'HUF', 'Ft', '1.00', NULL, NULL),
(47, 'Indonesian Rupiah', 'IDR', 'Rp', '1.00', NULL, NULL),
(48, 'Israeli New Sheqel', 'ILS', '₪', '1.00', NULL, NULL),
(49, 'Indian Rupee', 'INR', '₹', '1.00', NULL, NULL),
(50, 'Iraqi Dinar', 'IQD', 'ع.د', '1.00', NULL, NULL),
(51, 'Iranian Rial', 'IRR', '﷼', '1.00', NULL, NULL),
(52, 'Icelandic Króna', 'ISK', 'kr', '1.00', NULL, NULL),
(53, 'Jamaican Dollar', 'JMD', '$', '1.00', NULL, NULL),
(54, 'Jordanian Dinar', 'JOD', 'د.ا‏', '1.00', NULL, NULL),
(55, 'Japanese Yen', 'JPY', '¥', '1.00', NULL, NULL),
(56, 'Kenyan Shilling', 'KES', 'Ksh', '1.00', NULL, NULL),
(57, 'Cambodian Riel', 'KHR', '៛', '1.00', NULL, NULL),
(58, 'Comorian Franc', 'KMF', 'FC', '1.00', NULL, NULL),
(59, 'South Korean Won', 'KRW', 'CF', '1.00', NULL, NULL),
(60, 'Kuwaiti Dinar', 'KWD', 'د.ك.‏', '1.00', NULL, NULL),
(61, 'Kazakhstani Tenge', 'KZT', '₸.', '1.00', NULL, NULL),
(62, 'Lebanese Pound', 'LBP', 'ل.ل.‏', '1.00', NULL, NULL),
(63, 'Sri Lankan Rupee', 'LKR', 'Rs', '1.00', NULL, NULL),
(64, 'Lithuanian Litas', 'LTL', 'Lt', '1.00', NULL, NULL),
(65, 'Latvian Lats', 'LVL', 'Ls', '1.00', NULL, NULL),
(66, 'Libyan Dinar', 'LYD', 'د.ل.‏', '1.00', NULL, NULL),
(67, 'Moroccan Dirham', 'MAD', 'د.م.‏', '1.00', NULL, NULL),
(68, 'Moldovan Leu', 'MDL', 'L', '1.00', NULL, NULL),
(69, 'Malagasy Ariary', 'MGA', 'Ar', '1.00', NULL, NULL),
(70, 'Macedonian Denar', 'MKD', 'Ден', '1.00', NULL, NULL),
(71, 'Myanma Kyat', 'MMK', 'K', '1.00', NULL, NULL),
(72, 'Macanese Pataca', 'MOP', 'MOP$', '1.00', NULL, NULL),
(73, 'Mauritian Rupee', 'MUR', 'Rs', '1.00', NULL, NULL),
(74, 'Mexican Peso', 'MXN', '$', '1.00', NULL, NULL),
(75, 'Malaysian Ringgit', 'MYR', 'RM', '1.00', NULL, NULL),
(76, 'Mozambican Metical', 'MZN', 'MT', '1.00', NULL, NULL),
(77, 'Namibian Dollar', 'NAD', 'N$', '1.00', NULL, NULL),
(78, 'Nigerian Naira', 'NGN', '₦', '1.00', NULL, NULL),
(79, 'Nicaraguan Córdoba', 'NIO', 'C$', '1.00', NULL, NULL),
(80, 'Norwegian Krone', 'NOK', 'kr', '1.00', NULL, NULL),
(81, 'Nepalese Rupee', 'NPR', 'Re.', '1.00', NULL, NULL),
(82, 'New Zealand Dollar', 'NZD', '$', '1.00', NULL, NULL),
(83, 'Omani Rial', 'OMR', 'ر.ع.‏', '1.00', NULL, NULL),
(84, 'Panamanian Balboa', 'PAB', 'B/.', '1.00', NULL, NULL),
(85, 'Peruvian Nuevo Sol', 'PEN', 'S/', '1.00', NULL, NULL),
(86, 'Philippine Peso', 'PHP', '₱', '1.00', NULL, NULL),
(87, 'Pakistani Rupee', 'PKR', 'Rs', '1.00', NULL, NULL),
(88, 'Polish Zloty', 'PLN', 'zł', '1.00', NULL, NULL),
(89, 'Paraguayan Guarani', 'PYG', '₲', '1.00', NULL, NULL),
(90, 'Qatari Rial', 'QAR', 'ر.ق.‏', '1.00', NULL, NULL),
(91, 'Romanian Leu', 'RON', 'lei', '1.00', NULL, NULL),
(92, 'Serbian Dinar', 'RSD', 'din.', '1.00', NULL, NULL),
(93, 'Russian Ruble', 'RUB', '₽.', '1.00', NULL, NULL),
(94, 'Rwandan Franc', 'RWF', 'FRw', '1.00', NULL, NULL),
(95, 'Saudi Riyal', 'SAR', 'ر.س.‏', '1.00', NULL, NULL),
(96, 'Sudanese Pound', 'SDG', 'ج.س.', '1.00', NULL, NULL),
(97, 'Swedish Krona', 'SEK', 'kr', '1.00', NULL, NULL),
(98, 'Singapore Dollar', 'SGD', '$', '1.00', NULL, NULL),
(99, 'Somali Shilling', 'SOS', 'Sh.so.', '1.00', NULL, NULL),
(100, 'Syrian Pound', 'SYP', 'LS‏', '1.00', NULL, NULL),
(101, 'Thai Baht', 'THB', '฿', '1.00', NULL, NULL),
(102, 'Tunisian Dinar', 'TND', 'د.ت‏', '1.00', NULL, NULL),
(103, 'Tongan Paʻanga', 'TOP', 'T$', '1.00', NULL, NULL),
(104, 'Turkish Lira', 'TRY', '₺', '1.00', NULL, NULL),
(105, 'Trinidad and Tobago Dollar', 'TTD', '$', '1.00', NULL, NULL),
(106, 'New Taiwan Dollar', 'TWD', 'NT$', '1.00', NULL, NULL),
(107, 'Tanzanian Shilling', 'TZS', 'TSh', '1.00', NULL, NULL),
(108, 'Ukrainian Hryvnia', 'UAH', '₴', '1.00', NULL, NULL),
(109, 'Ugandan Shilling', 'UGX', 'USh', '1.00', NULL, NULL),
(110, 'Uruguayan Peso', 'UYU', '$', '1.00', NULL, NULL),
(111, 'Uzbekistan Som', 'UZS', 'so\'m', '1.00', NULL, NULL),
(112, 'Venezuelan Bolívar', 'VEF', 'Bs.F.', '1.00', NULL, NULL),
(113, 'Vietnamese Dong', 'VND', '₫', '1.00', NULL, NULL),
(114, 'CFA Franc BEAC', 'XAF', 'FCFA', '1.00', NULL, NULL),
(115, 'CFA Franc BCEAO', 'XOF', 'CFA', '1.00', NULL, NULL),
(116, 'Yemeni Rial', 'YER', '﷼‏', '1.00', NULL, NULL),
(117, 'South African Rand', 'ZAR', 'R', '1.00', NULL, NULL),
(118, 'Zambian Kwacha', 'ZMK', 'ZK', '1.00', NULL, NULL),
(119, 'Zimbabwean Dollar', 'ZWL', 'Z$', '1.00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `e_money`
--

CREATE TABLE `e_money` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `current_balance` double(14,2) NOT NULL DEFAULT '0.00',
  `charge_earned` double(14,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `funds`
--

CREATE TABLE `funds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `amount` double(14,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tran_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `help_topics`
--

CREATE TABLE `help_topics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci,
  `answer` text COLLATE utf8mb4_unicode_ci,
  `ranking` int(11) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `linked_websites`
--

CREATE TABLE `linked_websites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `linked_websites`
--

INSERT INTO `linked_websites` (`id`, `name`, `image`, `url`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Daraz', '2022-04-18-625cfbf0275c8.png', 'https://daraz.com/', 1, '2022-04-07 08:23:08', '2022-04-18 09:49:36'),
(2, 'Amazon', '2022-04-18-625cf5e5dd9b3.png', 'http://www.amazon.com/', 1, '2022-04-07 08:27:25', '2022-04-18 09:23:49'),
(3, 'AS', '2022-04-18-625cfc0663881.png', 'https://www.facebook.com/', 1, '2022-04-18 09:49:58', '2022-04-18 09:49:58'),
(4, 'FF', '2022-04-18-625cfc1f1b14b.png', 'https://www.linkedin.com/signup', 1, '2022-04-18 09:50:23', '2022-04-18 09:50:23');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(241, '2014_10_12_000000_create_users_table', 1),
(242, '2014_10_12_100000_create_password_resets_table', 1),
(243, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(244, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(245, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(246, '2016_06_01_000004_create_oauth_clients_table', 1),
(247, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(248, '2019_08_19_000000_create_failed_jobs_table', 1),
(249, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(250, '2021_11_18_104105_create_business_settings_table', 1),
(251, '2021_11_20_090531_create_currencies_table', 1),
(252, '2021_11_22_065212_add_last_active_at_to_users_table', 1),
(253, '2021_11_23_090107_create_linked_websites_table', 1),
(254, '2021_11_23_104425_add_reference_columns_to_users_table', 1),
(255, '2021_11_23_123056_create_notifications_table', 1),
(256, '2021_11_27_041913_create_phone_verifications_table', 1),
(257, '2021_11_27_052236_add_columns_to_users_table', 1),
(258, '2021_11_29_100204_create_transfers_table', 1),
(259, '2021_12_01_053955_create_transactions_table', 1),
(260, '2021_12_01_063108_create_e_money_table', 1),
(261, '2021_12_04_113130_create_request_money_table', 1),
(262, '2021_12_05_051247_create_funds_table', 1),
(263, '2021_12_06_101224_create_purposes_table', 1),
(264, '2021_12_14_104755_add_note_column_to_transaction', 1),
(265, '2021_12_19_071059_add_twofactor_and_fcmtoken_to_users_table', 1),
(266, '2021_12_21_110529_create_banners_table', 1),
(267, '2021_12_22_121505_add_receiver_column_to_notifications', 1),
(268, '2021_12_26_061202_create_help_topics_table', 1),
(269, '2022_02_01_041254_add_transaction_i_d_to_transactions', 1),
(270, '2022_02_01_065231_type_change_of_ref_trans_id_to_transactions', 1),
(271, '2022_04_07_045435_add_receiver_to_banner_table', 2),
(272, '2022_04_07_060244_add_is_active_column_to_to_users_table', 3),
(273, '2021_06_17_054551_create_soft_credentials_table', 4),
(274, '2022_06_30_051435_add_column_to_user_table', 5),
(275, '2022_07_05_102531_change_data_type_of_transfer_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `receiver` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 'bR3oc2ab81JlHVMcsIXdFcAhBuXQTipmcIcd7Fno', NULL, 'http://localhost', 1, 0, 0, '2022-04-07 08:03:44', '2022-04-07 08:03:44'),
(2, NULL, 'Laravel Password Grant Client', 'UhJDtulOAo0vdfuaHCtQ4KT4QOiYDdmImM9x48Ax', 'users', 'http://localhost', 0, 1, 0, '2022-04-07 08:03:44', '2022-04-07 08:03:44'),
(3, NULL, 'Laravel Personal Access Client', 'HpFYsWP7yU6grzZOwpsWP1oACFUuOwMHHMGuqYgl', NULL, 'http://localhost', 1, 0, 0, '2022-04-07 08:03:56', '2022-04-07 08:03:56'),
(4, NULL, 'Laravel Password Grant Client', 'P03bw333WuSbG3KcWW0VYBBOOTAhcCZaCB3rTt3V', 'users', 'http://localhost', 0, 1, 0, '2022-04-07 08:03:56', '2022-04-07 08:03:56');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2022-04-07 08:03:44', '2022-04-07 08:03:44'),
(2, 3, '2022-04-07 08:03:56', '2022-04-07 08:03:56');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phone_verifications`
--

CREATE TABLE `phone_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purposes`
--

CREATE TABLE `purposes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_money`
--

CREATE TABLE `request_money` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from_user_id` bigint(20) NOT NULL,
  `to_user_id` bigint(20) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(14,2) NOT NULL DEFAULT '0.00',
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `soft_credentials`
--

CREATE TABLE `soft_credentials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `ref_trans_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `debit` double(14,2) NOT NULL DEFAULT '0.00',
  `credit` double(14,2) NOT NULL DEFAULT '0.00',
  `balance` double(14,2) NOT NULL DEFAULT '0.00',
  `from_user_id` bigint(20) DEFAULT NULL,
  `to_user_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unique_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender` bigint(20) NOT NULL,
  `receiver` bigint(20) NOT NULL,
  `receiver_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(14,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `f_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `l_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint(1) NOT NULL COMMENT '[''Admin''=>0, ''Agent''=>1, ''Customer''=>2]',
  `role` tinyint(1) DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_phone_verified` tinyint(1) NOT NULL DEFAULT '0',
  `is_email_verified` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_active_at` timestamp NULL DEFAULT NULL,
  `unique_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referral_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `occupation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor` tinyint(1) NOT NULL DEFAULT '0',
  `fcm_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `identification_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identification_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identification_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '[]',
  `is_kyc_verified` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_settings`
--
ALTER TABLE `business_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `e_money`
--
ALTER TABLE `e_money`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `funds`
--
ALTER TABLE `funds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_topics`
--
ALTER TABLE `help_topics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `linked_websites`
--
ALTER TABLE `linked_websites`
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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_phone_index` (`phone`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `phone_verifications`
--
ALTER TABLE `phone_verifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purposes`
--
ALTER TABLE `purposes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_money`
--
ALTER TABLE `request_money`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `soft_credentials`
--
ALTER TABLE `soft_credentials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
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
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `business_settings`
--
ALTER TABLE `business_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `e_money`
--
ALTER TABLE `e_money`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `funds`
--
ALTER TABLE `funds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `help_topics`
--
ALTER TABLE `help_topics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `linked_websites`
--
ALTER TABLE `linked_websites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=276;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phone_verifications`
--
ALTER TABLE `phone_verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purposes`
--
ALTER TABLE `purposes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `request_money`
--
ALTER TABLE `request_money`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `soft_credentials`
--
ALTER TABLE `soft_credentials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
