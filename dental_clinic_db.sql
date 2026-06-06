-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 06, 2026 at 10:03 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dental_clinic_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `clinic`
--

CREATE TABLE `clinic` (
  `id` int NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `x_twitter` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(111) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clinic`
--

INSERT INTO `clinic` (`id`, `name`, `description`, `phone`, `facebook`, `instagram`, `x_twitter`, `logo`) VALUES
(1, 'Cameran Cooke', 'In exercitationem an', '+1 (879) 273-6691', 'https://www.widatomi.us', 'https://www.qyxehocitek.tv', 'https://www.wybujihycycidy.co.uk', 'logo_1780310626_6a1d6262851cc.png');

-- --------------------------------------------------------

--
-- Table structure for table `clinic_slots`
--

CREATE TABLE `clinic_slots` (
  `id` int NOT NULL,
  `booking_day` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم اليوم بالعربية مثل: السبت',
  `booking_date` date DEFAULT NULL,
  `time_range` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'النطاق الزمني مثل: 09:00 - 10:00',
  `is_booked` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'حالة الحجز: 0 متاح، 1 محجوز',
  `status` enum('0','1','2','3','4') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `user_id` int DEFAULT NULL,
  `service_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'وقت إنشاء الخانة الزمنية'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clinic_slots`
--

INSERT INTO `clinic_slots` (`id`, `booking_day`, `booking_date`, `time_range`, `is_booked`, `status`, `user_id`, `service_id`, `created_at`) VALUES
(45, 'السبت', '2026-05-30', '09:00 - 10:00', 1, '0', NULL, 8, '2026-06-04 13:55:51'),
(46, 'السبت', '2026-05-30', '10:00 - 11:00', 0, '0', NULL, NULL, '2026-06-04 13:55:51'),
(48, 'السبت', '2026-05-30', '12:00 - 13:00', 0, '0', NULL, NULL, '2026-06-04 13:55:51'),
(49, 'السبت', '2026-05-30', '13:00 - 14:00', 0, '0', NULL, NULL, '2026-06-04 13:55:51'),
(52, 'الإثنين', '2026-06-01', '09:00 - 10:00', 0, '0', NULL, NULL, '2026-06-04 13:56:13'),
(53, 'الإثنين', '2026-06-01', '10:00 - 11:00', 1, '0', NULL, 4, '2026-06-04 13:56:13'),
(54, 'الإثنين', '2026-06-01', '11:00 - 12:00', 1, '2', 14, 8, '2026-06-04 13:56:13'),
(56, 'الإثنين', '2026-06-01', '13:00 - 14:00', 0, '0', NULL, NULL, '2026-06-04 13:56:13'),
(57, 'الإثنين', '2026-06-01', '14:00 - 15:00', 0, '0', NULL, NULL, '2026-06-04 13:56:13'),
(58, 'الثلاثاء', '2026-06-02', '09:00 - 10:00', 0, '0', NULL, NULL, '2026-06-04 13:56:20'),
(59, 'الثلاثاء', '2026-06-02', '10:00 - 11:00', 0, '0', NULL, NULL, '2026-06-04 13:56:20'),
(60, 'الثلاثاء', '2026-06-02', '11:00 - 12:00', 0, '0', NULL, NULL, '2026-06-04 13:56:20'),
(61, 'الثلاثاء', '2026-06-02', '12:00 - 13:00', 0, '0', NULL, NULL, '2026-06-04 13:56:20'),
(62, 'الثلاثاء', '2026-06-02', '13:00 - 14:00', 0, '0', NULL, NULL, '2026-06-04 13:56:20'),
(63, 'الثلاثاء', '2026-06-02', '14:00 - 15:00', 1, '4', 30, 10, '2026-06-04 13:56:21'),
(64, 'الأربعاء', '2026-06-03', '09:00 - 10:00', 0, '0', NULL, NULL, '2026-06-04 13:56:21'),
(65, 'الأربعاء', '2026-06-03', '10:00 - 11:00', 0, '0', NULL, NULL, '2026-06-04 13:56:21'),
(66, 'الأربعاء', '2026-06-03', '11:00 - 12:00', 0, '0', NULL, NULL, '2026-06-04 13:56:21'),
(67, 'الأربعاء', '2026-06-03', '12:00 - 13:00', 1, '2', 31, 8, '2026-06-04 13:56:21'),
(68, 'الأربعاء', '2026-06-03', '13:00 - 14:00', 0, '0', NULL, NULL, '2026-06-04 13:56:21'),
(69, 'الأربعاء', '2026-06-03', '14:00 - 15:00', 1, '0', NULL, 10, '2026-06-04 13:56:21'),
(71, 'الثلاثاء', '2026-06-09', '10:00 - 11:00', 0, '0', NULL, NULL, '2026-06-04 13:56:32'),
(73, 'الثلاثاء', '2026-06-09', '12:00 - 13:00', 1, '1', 30, 10, '2026-06-04 13:56:32'),
(74, 'الثلاثاء', '2026-06-09', '13:00 - 14:00', 1, '1', 30, 6, '2026-06-04 13:56:32'),
(75, 'الثلاثاء', '2026-06-09', '14:00 - 15:00', 1, '2', 12, 10, '2026-06-04 13:56:32'),
(76, 'الأربعاء', '2026-06-10', '09:00 - 10:00', 1, '0', NULL, 4, '2026-06-04 13:56:32'),
(77, 'الأربعاء', '2026-06-10', '10:00 - 11:00', 1, '2', 30, 4, '2026-06-04 13:56:32'),
(78, 'الأربعاء', '2026-06-10', '11:00 - 12:00', 1, '1', 30, 11, '2026-06-04 13:56:32'),
(80, 'الأربعاء', '2026-04-08', '13:00 - 14:00', 1, '2', 28, 4, '2026-06-04 13:56:32'),
(81, 'الأربعاء', '2026-06-10', '14:00 - 15:00', 1, '3', 30, 7, '2026-06-04 13:56:32'),
(82, 'السبت', '2026-06-06', '09:00 - 10:00', 1, '3', 30, 5, '2026-06-04 14:48:15'),
(83, 'السبت', '2026-06-06', '10:00 - 11:00', 0, '0', NULL, NULL, '2026-06-04 14:48:15'),
(84, 'السبت', '2026-06-06', '11:00 - 12:00', 1, '2', 29, 7, '2026-06-04 14:48:15'),
(85, 'السبت', '2026-06-06', '12:00 - 13:00', 0, '0', NULL, NULL, '2026-06-04 14:48:15'),
(90, 'الإثنين', '2026-06-08', '09:00 - 10:00', 1, '2', 28, 7, '2026-06-04 14:48:29'),
(91, 'السبت', '2026-06-13', '09:00 - 10:00', 1, '4', 31, 4, '2026-06-06 09:10:56'),
(92, 'السبت', '2026-06-13', '10:00 - 11:00', 0, '0', NULL, NULL, '2026-06-06 09:10:56'),
(93, 'السبت', '2026-06-13', '11:00 - 12:00', 0, '0', NULL, NULL, '2026-06-06 09:10:56'),
(94, 'السبت', '2026-06-13', '12:00 - 13:00', 0, '0', NULL, NULL, '2026-06-06 09:10:56'),
(95, 'السبت', '2026-06-13', '13:00 - 14:00', 0, '0', NULL, NULL, '2026-06-06 09:10:56'),
(96, 'السبت', '2026-06-13', '14:00 - 15:00', 0, '0', NULL, NULL, '2026-06-06 09:10:56'),
(97, 'الإثنين', '2026-06-15', '09:00 - 10:00', 1, '2', 31, 4, '2026-06-06 09:10:56'),
(98, 'الإثنين', '2026-06-15', '10:00 - 11:00', 1, '0', NULL, 4, '2026-06-06 09:10:56'),
(99, 'الإثنين', '2026-06-15', '11:00 - 12:00', 1, '1', 12, 11, '2026-06-06 09:10:56'),
(100, 'الإثنين', '2026-06-15', '12:00 - 13:00', 1, '0', NULL, 6, '2026-06-06 09:10:56'),
(101, 'الإثنين', '2026-06-15', '13:00 - 14:00', 1, '2', 31, 11, '2026-06-06 09:10:56'),
(102, 'الإثنين', '2026-06-15', '14:00 - 15:00', 0, '0', NULL, NULL, '2026-06-06 09:10:56'),
(103, 'الثلاثاء', '2026-06-16', '09:00 - 10:00', 0, '0', NULL, NULL, '2026-06-06 09:10:56'),
(104, 'الثلاثاء', '2026-06-16', '10:00 - 11:00', 0, '0', NULL, NULL, '2026-06-06 09:10:56'),
(105, 'الثلاثاء', '2026-06-16', '11:00 - 12:00', 0, '0', NULL, NULL, '2026-06-06 09:10:56'),
(106, 'الثلاثاء', '2026-06-16', '12:00 - 13:00', 0, '0', NULL, NULL, '2026-06-06 09:10:56'),
(107, 'الثلاثاء', '2026-06-16', '13:00 - 14:00', 0, '0', NULL, NULL, '2026-06-06 09:10:56'),
(108, 'الثلاثاء', '2026-06-16', '14:00 - 15:00', 0, '0', NULL, NULL, '2026-06-06 09:10:56');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `img` varchar(111) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `email`, `password`, `phone`, `created_at`, `img`) VALUES
(1, 'خليل إسماعيل', 'snuora2019@gmail.com', '$2y$10$QKJMPhVlQ/74APx2P/CSn.TcddDEmyJTzRz0H8e3X7418DS95D5LO', '059000000', '2026-05-31 09:10:48', 'user_1780737282_6a23e50277bda.png');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int NOT NULL,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Comma separated keywords for chatbot lookup'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `keywords`) VALUES
(22, 'كيف يمكنني حجز موعد في العيادة؟', 'يمكنك حجز موعد بسهولة عبر الموقع الإلكتروني من خلال اختيار الخدمة المناسبة والوقت المتاح، أو التواصل معنا مباشرة عبر الهاتف.', NULL),
(23, 'ما هي الخدمات التي تقدمها العيادة؟', 'نقدم مجموعة متكاملة من خدمات طب الأسنان، تشمل الحشوات التجميلية، علاج العصب، تنظيف الأسنان، وتبييض الأسنان، وزراعة الأسنان، وتقويم الأسنان.', NULL),
(24, 'هل تتوفر خدمات طوارئ لآلام الأسنان الحادة؟', 'نعم، نخصص أوقاتاً لحالات الطوارئ، يرجى الاتصال بنا فوراً في حال الشعور بألم حاد ليتم تقديم الإسعافات اللازمة.', NULL),
(25, 'هل تستخدم العيادة تقنيات حديثة للتعقيم؟', 'نعم، سلامة المريض هي أولويتنا، ونلتزم بأعلى معايير التعقيم العالمية واستخدام أدوات معقمة لكل مريض على حدة.', NULL),
(26, 'كم تستغرق جلسة تنظيف الأسنان؟', 'تستغرق جلسة التنظيف عادةً ما بين 30 إلى 45 دقيقة، وتعتمد المدة على حالة اللثة وكمية الجير المتراكمة.', NULL),
(27, 'هل توفر العيادة خيارات تقسيط أو تسهيلات في الدفع؟', 'نحن نسعى لتسهيل الوصول للعلاج، ونوفر خطط دفع ميسرة للخدمات العلاجية الكبيرة، يمكنك الاستفسار عن التفاصيل عند زيارتك للعيادة.', NULL),
(28, 'ما هي الأعمار التي تستقبلها العيادة؟', 'نستقبل جميع الفئات العمرية، ولدينا تعامل خاص ومريح للأطفال لضمان تجربة علاجية إيجابية.', NULL),
(29, 'هل يمكنني تبييض أسناني في جلسة واحدة؟', 'نعم، تتوفر تقنيات التبييض الحديثة التي تمنحك نتائج ملحوظة في جلسة واحدة داخل العيادة.', NULL),
(30, 'كيف أعتني بأسناني بعد إجراء زراعة الأسنان؟', 'سنزودك بكتيب إرشادات كامل بعد العملية، يتضمن العناية بنظافة الفم واستخدام الفرشاة الطبية والمضمضة الموصى بها.', NULL),
(31, 'كم مرة يجب أن أزور عيادة الأسنان للفحص الدوري؟', 'ننصح بزيارة العيادة للفحص الدوري وتنظيف الأسنان كل 6 أشهر للحفاظ على صحة الفم وتجنب المشاكل قبل تفاقمها.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `message`, `link`, `is_read`, `created_at`) VALUES
(1, 'تم تسجيل موعد جديد من قبل 7', 'dates.php', 0, '2026-06-04 14:06:09'),
(2, 'تم تسجيل موعد جديد من قبل Chloe Turner', 'dates.php', 0, '2026-06-04 14:07:26'),
(3, 'تم دفع    لجلسة   50من قبل Chloe Turner', 'dates.php', 0, '2026-06-04 14:26:00'),
(4, 'تم تسجيل موعد جديد من قبل Chloe Turner', 'dates.php', 0, '2026-06-04 14:31:28'),
(5, 'تم تسجيل موعد جديد من قبل Chloe Turner', 'dates.php', 0, '2026-06-04 14:33:58'),
(6, 'تم تسجيل موعد جديد من قبل Chloe Turner', 'dates.php', 0, '2026-06-04 14:54:15'),
(7, 'تم دفع    لجلسة   47من قبل Chloe Turner', 'dates.php', 0, '2026-06-04 15:02:06'),
(8, 'تم تسجيل موعد جديد من قبل Chloe Turner', 'dates.php', 0, '2026-06-04 15:07:31'),
(9, 'تم تسجيل موعد جديد من قبل Chloe Turner', 'dates.php', 0, '2026-06-04 15:07:36'),
(10, 'تم تسجيل موعد جديد من قبل Chloe Turner', 'dates.php', 0, '2026-06-04 15:07:39'),
(11, 'تم تسجيل مستخدم جديد', 'users.php', 0, '2026-06-04 15:26:04'),
(12, 'تم تسجيل موعد جديد من قبل Dante Craft', 'dates.php', 0, '2026-06-04 15:26:32'),
(13, 'تم دفع    لجلسة   90من قبل Dante Craft', 'dates.php', 0, '2026-06-04 15:27:00'),
(14, 'تم تسجيل موعد جديد من قبل Dante Craft', 'dates.php', 0, '2026-06-04 15:30:30'),
(15, 'تم دفع    لجلسة   80من قبل Dante Craft', 'dates.php', 0, '2026-06-04 15:31:04'),
(16, 'تم تسجيل مستخدم جديد', 'users.php', 0, '2026-06-06 04:13:07'),
(17, 'تم تسجيل موعد جديد من قبل Tad Michael', 'dates.php', 0, '2026-06-06 04:13:21'),
(18, 'تم دفع    لجلسة   84من قبل Tad Michael', 'dates.php', 0, '2026-06-06 04:58:30'),
(19, 'تم تسجيل مستخدم جديد', 'users.php', 0, '2026-06-06 07:42:34'),
(20, 'تم تسجيل موعد جديد من قبل Imogene Prince', 'dates.php', 0, '2026-06-06 07:42:42'),
(21, 'تم دفع    لجلسة   82من قبل Imogene Prince', 'dates.php', 0, '2026-06-06 07:44:21'),
(22, 'تم تسجيل موعد جديد من قبل Imogene Prince', 'dates.php', 0, '2026-06-06 07:48:42'),
(23, 'تم دفع    لجلسة   73من قبل Imogene Prince', 'dates.php', 0, '2026-06-06 08:19:34'),
(24, 'تم دفع    لجلسة   74من قبل Imogene Prince', 'dates.php', 0, '2026-06-06 08:19:38'),
(25, 'تم دفع    لجلسة   77من قبل Imogene Prince', 'dates.php', 0, '2026-06-06 08:19:43'),
(26, 'تم تسجيل موعد جديد من قبل Imogene Prince', 'dates.php', 0, '2026-06-06 08:36:22'),
(27, 'تم دفع    لجلسة   78من قبل Imogene Prince', 'dates.php', 0, '2026-06-06 08:37:37'),
(28, 'تم دفع    لجلسة   78من قبل Imogene Prince', 'dates.php', 0, '2026-06-06 08:54:42'),
(29, 'تم تسجيل مستخدم جديد', 'users.php', 0, '2026-06-06 09:08:07'),
(30, 'تم تسجيل موعد جديد من قبل Angela Combs', 'dates.php', 0, '2026-06-06 09:15:08'),
(31, 'تم تسجيل موعد جديد من قبل Angela Combs', 'dates.php', 0, '2026-06-06 09:21:34'),
(32, 'تم تسجيل موعد جديد من قبل Angela Combs', 'dates.php', 0, '2026-06-06 09:41:09'),
(33, 'تم تسجيل موعد جديد من قبل Angela Combs', 'dates.php', 0, '2026-06-06 09:43:15'),
(34, 'تم دفع    لجلسة   91من قبل Angela Combs', 'dates.php', 0, '2026-06-06 09:43:43'),
(35, 'تم دفع    لجلسة   91من قبل Angela Combs', 'dates.php', 0, '2026-06-06 09:44:50'),
(36, 'تم تسجيل موعد جديد من قبل Angela Combs', 'dates.php', 0, '2026-06-06 09:45:42'),
(37, 'تم دفع    لجلسة   101من قبل Angela Combs', 'dates.php', 0, '2026-06-06 09:45:57'),
(38, 'تم دفع    لجلسة   97من قبل Angela Combs', 'dates.php', 0, '2026-06-06 10:02:24');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int NOT NULL,
  `date_id` int NOT NULL,
  `user_id` int NOT NULL,
  `doctor_id` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','card') COLLATE utf8mb4_unicode_ci DEFAULT 'card',
  `status` enum('pending','paid','refunded') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `date_id`, `user_id`, `doctor_id`, `price`, `payment_method`, `status`, `notes`, `created_at`) VALUES
(7, 90, 28, 1, 600.00, 'card', 'pending', NULL, '2026-06-04 15:27:00'),
(8, 80, 28, 1, 150.00, 'card', 'pending', NULL, '2026-06-04 15:31:04'),
(9, 84, 29, 1, 600.00, 'card', 'pending', NULL, '2026-06-06 04:58:30'),
(10, 82, 30, 1, 250.00, 'card', 'pending', NULL, '2026-06-06 07:44:21'),
(11, 73, 30, 1, 300.00, 'card', 'pending', NULL, '2026-06-06 08:19:34'),
(12, 74, 30, 1, 400.00, 'card', 'pending', NULL, '2026-06-06 08:19:38'),
(13, 77, 30, 1, 150.00, 'card', 'pending', NULL, '2026-06-06 08:19:43'),
(14, 78, 30, 1, 300.00, 'card', 'pending', NULL, '2026-06-06 08:37:37'),
(15, 78, 30, 1, 300.00, 'card', 'pending', NULL, '2026-06-06 08:54:42'),
(16, 91, 31, 1, 150.00, 'card', 'refunded', NULL, '2026-06-06 09:43:43'),
(17, 91, 31, 1, 150.00, 'card', 'refunded', NULL, '2026-06-06 09:44:50'),
(18, 101, 31, 1, 300.00, 'card', 'refunded', NULL, '2026-06-06 09:45:57'),
(19, 97, 31, 1, 150.00, 'card', 'refunded', NULL, '2026-06-06 10:02:24');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `slot_id` int NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `user_id`, `slot_id`, `rating`, `notes`, `created_at`) VALUES
(1, 29, 84, 4, 'test', '2026-06-06 05:14:15'),
(2, 29, 84, 4, 'asddsaasd', '2026-06-06 05:16:04'),
(3, 30, 82, 4, 'asdsaddsa', '2026-06-06 08:22:43'),
(4, 30, 63, 5, 'يسشسشيسيش', '2026-06-06 08:32:22'),
(5, 30, 63, 5, 'sadfsad', '2026-06-06 08:58:32'),
(6, 30, 63, 5, 'adsdadsa', '2026-06-06 08:58:44'),
(7, 31, 91, 5, 'فثفسسفيسش', '2026-06-06 10:00:08');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_time` int DEFAULT NULL COMMENT 'Duration in minutes',
  `sessions_count` int DEFAULT '1' COMMENT 'Number of sessions required',
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) DEFAULT '1' COMMENT '1=Active, 0=Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `price`, `duration_time`, `sessions_count`, `description`, `status`) VALUES
(4, 'تنظيف وتلميع الأسنان', 150.00, 45, 1, 'إزالة الجير والبلاك وتلميع الأسنان للحصول على ابتسامة مشرقة.', 1),
(5, 'حشوة تجميلية', 250.00, 60, 1, 'حشوات ضوئية تجميلية تطابق لون الأسنان الطبيعي.', 1),
(6, 'علاج العصب', 400.00, 90, 2, 'إزالة العصب الملتهب وتنظيف القنوات وحشوها.', 1),
(7, 'تبييض الأسنان بالليزر', 600.00, 60, 1, 'تبييض احترافي سريع ونتائج فورية لابتسامة بيضاء.', 1),
(8, 'زراعة أسنان (سن واحد)', 1500.00, 120, 3, 'زراعة سن متطور لتعويض الأسنان المفقودة بجودة عالية.', 1),
(9, 'تقويم الأسنان', 2500.00, 45, 12, 'علاج تقويم الأسنان لتصحيح اصطفافها ومظهرها.', 1),
(10, 'خلع ضرس العقل', 300.00, 45, 1, 'خلع جراحي لضرس العقل باستخدام التخدير الموضعي.', 1),
(11, 'تركيبات زيركون3', 300.00, 122, 1, 'تركيب تيجان الزيركون المتينة ذات المظهر الطبيعي.4', 1),
(12, 'asdsda', 213.00, 13, 2, 'sdadsadsa', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) DEFAULT '1' COMMENT '1=Active, 0=Blocked',
  `img` varchar(111) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default.png',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `status`, `img`, `created_at`) VALUES
(8, 'أحمد محمود أبو ناصر', 'ahmed.naser@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '0599123456', NULL, 1, 'default.png', '2026-06-04 15:18:15'),
(9, 'محمد عبد الكريم السويركي', 'mohammed.swirki@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '0599123457', NULL, 1, 'default.png', '2026-06-04 15:18:15'),
(10, 'محمود حسن خليل', 'mahmoud.khalil@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '0599123458', NULL, 1, 'default.png', '2026-06-04 15:18:15'),
(11, 'خالد إبراهيم العمصي', 'khaled.amassi@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '0599123459', NULL, 1, 'default.png', '2026-06-04 15:18:15'),
(12, 'ياسر محمد الزهار', 'yaser.zahar@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '0599123460', NULL, 1, 'default.png', '2026-06-04 15:18:15'),
(13, 'عمر إسماعيل بدوان', 'omar.badwan@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '0599123461', NULL, 1, 'default.png', '2026-06-04 15:18:15'),
(14, 'إبراهيم خليل النجار', 'ibrahim.najjar@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '0599123462', NULL, 1, 'default.png', '2026-06-04 15:18:15'),
(15, 'علي يوسف حمدان', 'ali.hamdan@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '0599123463', NULL, 1, 'default.png', '2026-06-04 15:18:15'),
(16, 'سعيد عبد الله الصواف', 'saeed.sawaf@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '0599123464', NULL, 1, 'default.png', '2026-06-04 15:18:15'),
(17, 'فادي محمد قديح', 'fadi.qudaih@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '0599123465', NULL, 1, 'default.png', '2026-06-04 15:18:15'),
(18, 'هاني سالم أبو عويضة', 'hani.oweida@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '0599123466', NULL, 1, 'default.png', '2026-06-04 15:18:15'),
(19, 'رامي إياد حلس', 'rami.hallas@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '0599123467', NULL, 1, 'default.png', '2026-06-04 15:18:15'),
(20, 'طارق ناصر أبو طه', 'tarek.taha@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '0599123468', NULL, 1, 'default.png', '2026-06-04 15:18:15'),
(21, 'سامر رفيق الغرة', 'samer.ghurra@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '0599123469', NULL, 1, 'default.png', '2026-06-04 15:18:15'),
(22, 'وائل فوزي شاهين', 'wael.shaheen@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '0599123470', NULL, 1, 'default.png', '2026-06-04 15:18:15'),
(23, 'تامر مروان الأغا', 'tamer.agha@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '0599123471', NULL, 1, 'default.png', '2026-06-04 15:18:15'),
(24, 'جهاد عادل أبو دقة', 'jehad.daqqa@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '0599123472', NULL, 1, 'default.png', '2026-06-04 15:18:15'),
(25, 'نادر سمير المصري', 'nader.masri@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '0599123473', NULL, 1, 'default.png', '2026-06-04 15:18:15'),
(26, 'باسم شكري الغول', 'basem.ghoul@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '0599123474', NULL, 1, 'default.png', '2026-06-04 15:18:15'),
(27, 'رائد صبحي كحيل', 'raed.kaheel@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '0599123475', NULL, 1, 'default.png', '2026-06-04 15:18:15'),
(28, 'Dante Craft', 'habuwal@mailinator.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '+1 (321) 698-9152', NULL, 1, 'default.png', '2026-06-04 15:26:04'),
(29, 'Tad Michael', 'lunuhoc@mailinator.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', '+1 (994) 436-4685', NULL, 1, 'default.png', '2026-06-06 04:13:07'),
(30, 'Imogene Prince', 'gulyvivoni@mailinator.com', '$2y$10$lmDhl/QH4NUcylWdJPbo3eIghtAOYrKbl1idecBQRA51oPhwWccq2', '+1 (827) 842-7522', 'Aspernatur possimus', 1, 'default.png', '2026-06-06 07:42:34'),
(31, 'Angela Combs', 'jirubahyt@mailinator.com', 'ac748cb38ff28d1ea98458b16695739d7e90f22d', '+1 (512) 813-2465', NULL, 1, 'default.png', '2026-06-06 09:08:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clinic`
--
ALTER TABLE `clinic`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinic_slots`
--
ALTER TABLE `clinic_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_booking_date` (`booking_date`),
  ADD KEY `user_id` (`user_id`,`service_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `date_id` (`date_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `slot_id` (`slot_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clinic`
--
ALTER TABLE `clinic`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `clinic_slots`
--
ALTER TABLE `clinic_slots`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clinic_slots`
--
ALTER TABLE `clinic_slots`
  ADD CONSTRAINT `clinic_slots_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clinic_slots_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_4` FOREIGN KEY (`date_id`) REFERENCES `clinic_slots` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`slot_id`) REFERENCES `clinic_slots` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
