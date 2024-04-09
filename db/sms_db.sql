-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2024 at 05:03 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `super_password` varchar(200) NOT NULL,
  `rank` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `super_password`, `rank`) VALUES
(1, 'GMT ADMIN', '$2y$10$uXUeVGUyNk.4yP9sKcE55OJTsQKDyjqdTU9XfER8.hYfSEZWsdJBW', 1);

-- --------------------------------------------------------

--
-- Table structure for table `api_key`
--

CREATE TABLE `api_key` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(200) NOT NULL,
  `prefix` varchar(20) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `api_key`
--

INSERT INTO `api_key` (`id`, `user_id`, `token`, `prefix`, `date_created`, `date_modified`) VALUES
(1, 1, '$2y$10$Ky0lTFaleLG8Zsa324h8C..OXgc78Xsprm/vBkwqR/s1D3LUtC2/i', '0157f1', '2022-07-28 09:43:30', '2023-08-21 00:20:23'),
(2, 2, '$2y$10$Fs1Ze1oEWHbieTqttGFVnuxQ1TsDLlCfxAsS00aF4a8HI7w6y7Y/W', 'd720d7', '2022-08-04 09:26:41', '2022-08-09 12:18:32'),
(11, 4, '$2y$10$QS.JHTGxzV8WEu12BUbqCeiU1flwOCD5x4MgREKVcHNOcvl6IC6Aq', '016d51', '2022-08-09 09:18:43', '2022-08-09 12:18:43'),
(12, 5, '$2y$10$qHKRrkT.asQgwjTKlnDXBOFWeBCqOQ4W7ZsmGwtMLT3gmUFMphH1u', '6dc295', '2022-08-09 01:41:39', '2022-09-15 10:33:45'),
(19, 7, '$2y$10$Mh45SwhjLZ4eFA6f2rjTjui2wGOIxHZ/qANnjehpbIR2yArTND1Fm', '53f6c3', '2024-04-04 10:34:24', '2024-04-07 23:03:45');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone_numbers` text NOT NULL,
  `status_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `deposit_transaction_status`
--

CREATE TABLE `deposit_transaction_status` (
  `id` int(11) NOT NULL,
  `payment_provider_id` tinyint(4) NOT NULL,
  `refNo` varchar(200) DEFAULT NULL,
  `ext_ref` varchar(200) DEFAULT NULL,
  `request_id` int(11) DEFAULT NULL,
  `organization` int(11) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `state_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `message_id` varchar(250) NOT NULL,
  `recipients` text DEFAULT NULL,
  `contact_id` int(11) DEFAULT NULL,
  `text` text NOT NULL,
  `statusCode` int(11) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user_id`, `transaction_id`, `message_id`, `recipients`, `contact_id`, `text`, `statusCode`, `status`, `date_created`, `date_modified`) VALUES
(1, 1, 2, 'ATXid_5017999bd02336b17fe98987ec7d96aa', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-08-10 15:16:19 . Balance:1,893,000/= Thank You!', 101, 'Success', '2022-08-10 19:22:38', '2022-08-10 19:22:38'),
(2, 1, 3, 'None', '+256737473337', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-08-10 15:16:19 . Balance:1,893,000/= Thank You!', 403, 'InvalidPhoneNumber', '2022-08-10 19:22:38', '2022-08-10 19:22:38'),
(3, 1, 4, 'ATXid_0b1bf2740e7dc9edf6befbb01f2cebd2', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-08-10 15:16:19 . Balance:1,893,000/= Thank You!', 101, 'Success', '2022-08-10 19:24:28', '2022-08-10 19:24:28'),
(4, 1, 5, 'None', '+256737473337', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-08-10 15:16:19 . Balance:1,893,000/= Thank You!', 403, 'InvalidPhoneNumber', '2022-08-10 19:24:28', '2022-08-10 19:24:28'),
(5, 1, 6, 'ATXid_33fa89613d434927590909ac9f2154fa', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-08-10 15:16:19 . Balance:1,893,000/= Thank You!', 101, 'Success', '2022-08-10 19:26:05', '2022-08-10 19:26:05'),
(6, 1, 7, 'None', '+256737473330', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-08-10 15:16:19 . Balance:1,893,000/= Thank You!', 403, 'InvalidPhoneNumber', '2022-08-10 19:26:05', '2022-08-10 19:26:05'),
(7, 1, 8, 'ATXid_94afab0d2ed4511afd7357c5550944ee', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-08-10 15:16:19 . Balance:1,893,000/= Thank You!', 101, 'Success', '2022-08-10 19:26:11', '2022-08-10 19:26:11'),
(8, 1, 9, 'ATXid_f2e4ebea5b9d10a8648adea7357fdf32', '+256757473330', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-08-10 15:16:19 . Balance:1,893,000/= Thank You!', 101, 'Success', '2022-08-10 19:26:11', '2022-08-10 19:26:11'),
(9, 1, 10, 'ATXid_df6bbd9f557442cca3c55ef0c16add2c', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-08-11 09:04:49 . Balance:1,893,000/= Thank You!', 101, 'Success', '2022-08-11 12:04:53', '2022-08-11 12:04:53'),
(10, 1, 13, 'ATXid_21bca19cd5f8ad6b64bd009900190980', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-08-15 08:21:53 . Balance:1,893,000/= Thank You!', 101, 'Success', '2022-08-15 11:21:55', '2022-08-15 11:21:55'),
(11, 1, 14, 'ATXid_240b8019d322111a5baf6fd077935462', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-08-15 08:22:20 . Balance:1,893,000/= Thank You!', 101, 'Success', '2022-08-15 11:22:29', '2022-08-15 11:22:29'),
(12, 1, 27, 'ATXid_7e0c8b0df34ede089c1eaa3be4e615c3', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-08-17 08:39:44 . Balance:1,893,000/= Thank You!', 101, 'Success', '2022-08-17 11:39:45', '2022-08-17 11:39:45'),
(13, 1, 31, 'ATXid_2871966ae93be39be52b0b0168c16165', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-08-22 14:20:47 . Balance:1,893,000/= Thank You!', 101, 'Success', '2022-08-22 17:20:49', '2022-08-22 17:20:49'),
(14, 1, 32, 'ATXid_a225f30302c1c2a56ffd7367c8ba5ee7', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-09-02 07:59:21 . Balance:1,893,000/= Thank You!', 101, 'Success', '2022-09-02 10:59:29', '2022-09-02 10:59:29'),
(15, 1, 36, 'ATXid_f80246b343eab5523eb5f2422c166cab', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-09-15 07:32:56 . Balance:1,893,000/= Thank You!', 101, 'Success', '2022-09-15 10:32:58', '2022-09-15 10:32:58'),
(16, 1, 37, 'ATXid_8d4d14461672316902768f0312cae36e', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-09-15 07:32:56 . Balance:1,893,000/= Thank You!', 101, 'Success', '2022-09-15 10:33:05', '2022-09-15 10:33:05'),
(17, 1, 38, 'ATXid_cfdfd91791f0527b069ac0a93fa86876', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-09-15 07:33:24 . Balance:1,893,000/= Thank You!', 101, 'Success', '2022-09-15 10:33:29', '2022-09-15 10:33:29'),
(18, 1, 40, 'ATXid_62130a38306aafdf2522456af9dcbf2c', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-09-26 08:23:47 . Balance:1,893,000/= Thank You!', 101, 'Success', '2022-09-26 11:23:49', '2022-09-26 11:23:49'),
(19, 1, 41, 'ATXid_adda1e912cc9a232d1d50413c0396938', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-09-28 13:16:06 . Balance:1,893,000/= Thank You!', 101, 'Success', '2022-09-28 16:21:14', '2022-09-28 16:21:14'),
(20, 1, 42, 'ATXid_13befd25c8692ea7924bdc3bd92b8b84', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-10-28 11:49:21 . Balance:1,893,000/= Thank You!', 101, 'Success', '2022-10-28 14:49:24', '2022-10-28 14:49:24'),
(21, 1, 43, 'ATXid_455bed8754daad79eed9f401846dafef', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2022-10-31 13:54:31 . Balance:1,893,000/= Thank You!', 101, 'Success', '2022-10-31 16:56:04', '2022-10-31 16:56:04'),
(22, 1, 48, 'ATXid_e591c8afaf4d5613eab37ffe1e8c3574', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2023-08-20 21:15:51 . Balance:1,893,000/= Thank You!', 101, 'Success', '2023-08-21 00:15:53', '2023-08-21 00:15:53'),
(23, 1, 49, 'ATXid_afd05b184bae99bfa22e02fa7435720b', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2023-08-20 21:15:51 . Balance:1,893,000/= Thank You!', 101, 'Success', '2023-08-21 00:16:07', '2023-08-21 00:16:07'),
(24, 1, 53, 'ATXid_a0d935ff5d44a1dfe75bbb9e964c9d68', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2024-03-13 19:40:15 . Balance:1,893,000/= Thank You!', 101, 'Success', '2024-03-13 22:40:26', '2024-03-13 22:40:26'),
(25, 1, 54, 'ATXid_c54628a282e224943814c930e640e9f4', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2024-03-13 20:26:24 . Balance:1,893,000/= Thank You!', 101, 'Success', '2024-03-13 23:26:35', '2024-03-13 23:26:35'),
(26, 1, 55, 'ATXid_bc265a9b6d8295d314cfd131a96b6a4d', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2024-03-13 20:26:24 . Balance:1,893,000/= Thank You!', 101, 'Success', '2024-03-13 23:27:42', '2024-03-13 23:27:42'),
(27, 1, 56, 'ATXid_bc265a9b6d8295d314cfd131a96b6a4d', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2024-03-13 20:26:24 . Balance:1,893,000/= Thank You!', 101, 'Success', '2024-03-13 23:34:44', '2024-03-13 23:34:44'),
(28, 1, 57, 'ATXid_bc265a9b6d8295d314cfd131a96b6a4d', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2024-03-13 20:26:24 . Balance:1,893,000/= Thank You!', 101, 'Success', '2024-03-13 23:35:00', '2024-03-13 23:35:00'),
(29, 1, 58, 'ATXid_842f51a5c6b0ad5172b5c99a6f1bf773', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2024-03-13 20:26:24 . Balance:1,893,000/= Thank You!', 101, 'Success', '2024-03-13 23:41:59', '2024-03-13 23:41:59'),
(30, 1, 59, 'ATXid_842f51a5c6b0ad5172b5c99a6f1bf773', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2024-03-13 20:26:24 . Balance:1,893,000/= Thank You!', 101, 'Success', '2024-03-13 23:43:59', '2024-03-13 23:43:59'),
(31, 1, 60, 'ATXid_842f51a5c6b0ad5172b5c99a6f1bf773', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2024-03-13 20:26:24 . Balance:1,893,000/= Thank You!', 101, 'Success', '2024-03-13 23:45:20', '2024-03-13 23:45:20'),
(32, 1, 61, 'ATXid_842f51a5c6b0ad5172b5c99a6f1bf773', '+256775959489', NULL, 'Dear Ajuna, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2024-03-13 20:26:24 . Balance:1,893,000/= Thank You!', 101, 'Success', '2024-03-13 23:46:06', '2024-03-13 23:46:06'),
(33, 1, 65, 'ATXid_82a4c606df36fcf18ad7879fc86735d0', '+256704147754', NULL, 'This is a test message', 101, 'Success', '2024-04-04 10:49:14', '2024-04-04 10:49:14'),
(34, 1, 66, 'ATXid_82a4c606df36fcf18ad7879fc86735d0', '+256704147754', NULL, 'This is a test message', 101, 'Success', '2024-04-04 11:12:29', '2024-04-04 11:12:29'),
(35, 7, 68, 'ATXid_11abd39191517099a7d4aa41b8caa009', '+256704147754', NULL, 'SACCONET: Dear Ssentamu, Deposit of Ugx:250,000 To your SACCO ACCOUNT Was Successful On:04/04/2024. at 08:04. Thank you.', 101, 'Success', '2024-04-04 11:56:30', '2024-04-04 11:56:30'),
(36, 7, 69, 'ATXid_11abd39191517099a7d4aa41b8caa009', '+256704147754', NULL, 'SACCONET: Dear Ssentamu, Deposit of Ugx:120,000 To your SACCO ACCOUNT Was Successful On:04/04/2024. at 09:04. Thank you.', 101, 'Success', '2024-04-04 12:03:36', '2024-04-04 12:03:36'),
(37, 7, 70, 'ATXid_11abd39191517099a7d4aa41b8caa009', '+256704147754', NULL, 'SACCONET: Dear Ssentamu, Deposit of Ugx:129,000 To your SACCO ACCOUNT Was Successful On:04/04/2024. at 09:04. Thank you.', 101, 'Success', '2024-04-04 12:31:04', '2024-04-04 12:31:04'),
(38, 7, 71, 'ATXid_11abd39191517099a7d4aa41b8caa009', '+256704147754', NULL, 'SACCONET: Dear Ssentamu, Deposit of Ugx:12,000,000 To your SACCO ACCOUNT Was Successful On:04/04/2024. at 09:04. Thank you.', 101, 'Success', '2024-04-04 12:32:57', '2024-04-04 12:32:57'),
(39, 7, 72, 'ATXid_11abd39191517099a7d4aa41b8caa009', '+256704147754', NULL, 'SACCONET: Dear Ssentamu, Deposit of Ugx:10,000 To your SACCO ACCOUNT Was Successful On:04/04/2024. at 09:04. Thank you.', 101, 'Success', '2024-04-04 12:36:35', '2024-04-04 12:36:35'),
(40, 7, 73, 'ATXid_11abd39191517099a7d4aa41b8caa009', '+256704147754', NULL, 'SACCONET: Dear Ssentamu, Deposit of Ugx:20,000 To your SACCO ACCOUNT Was Successful On:04/04/2024. at 09:04. Thank you.', 101, 'Success', '2024-04-04 12:48:08', '2024-04-04 12:48:08'),
(41, 1, 74, 'ATXid_b94afba6b7be0b7f8d167ddeef6a3d3d', '+256788611875', NULL, 'This message was sent from sacconet platform', 101, 'Success', '2024-04-05 15:20:36', '2024-04-05 15:20:36'),
(42, 7, 75, 'ATXid_11abd39191517099a7d4aa41b8caa009', '+256704147754', NULL, 'SACCONET: Dear Ssentamu, Deposit of Ugx:20,000 To your SACCO ACCOUNT Was Successful On:05/04/2024. at 12:04. Thank you.', 101, 'Success', '2024-04-05 15:43:56', '2024-04-05 15:43:56'),
(43, 7, 76, 'ATXid_11abd39191517099a7d4aa41b8caa009', '+256704147754', NULL, 'SACCONET: Dear Ssentamu, Deposit of Ugx:120,000 To your SACCO ACCOUNT Was Successful On:05/04/2024. at 01:04. Thank you.', 101, 'Success', '2024-04-05 16:11:26', '2024-04-05 16:11:26'),
(44, 7, 77, 'ATXid_11abd39191517099a7d4aa41b8caa009', '+256704147754', NULL, 'SACCONET: Dear Ssentamu, Deposit of Ugx:120,000 To your SACCO ACCOUNT Was Successful On:05/04/2024. at 01:04. Thank you.', 101, 'Success', '2024-04-05 16:15:24', '2024-04-05 16:15:24'),
(45, 7, 78, 'ATXid_11abd39191517099a7d4aa41b8caa009', '+256704147754', NULL, 'SACCONET: Dear Ssentamu, Deposit of Ugx:1,000,000 To your SACCO ACCOUNT Was Successful On:05/04/2024. at 01:04. Thank you.', 101, 'Success', '2024-04-05 16:40:46', '2024-04-05 16:40:46'),
(46, 7, 79, 'ATXid_0693c6b59b4590cc1045be5143f4bfdc', '+256702066819', NULL, 'SACCONET: Dear Mwanga, Deposit of Ugx:12,000 To your SACCO ACCOUNT Was Successful On:07/04/2024. at 04:04. Thank you.', 101, 'Success', '2024-04-07 19:53:25', '2024-04-07 19:53:25'),
(47, 7, 80, 'ATXid_0693c6b59b4590cc1045be5143f4bfdc', '+256702066819', NULL, 'SACCONET: Dear Mwanga, Deposit of Ugx:20,000 To your SACCO ACCOUNT Was Successful On:07/04/2024. at 08:04. Thank you.', 101, 'Success', '2024-04-07 23:05:13', '2024-04-07 23:05:13'),
(48, 1, 81, 'ATXid_0693c6b59b4590cc1045be5143f4bfdc', '+256702066819', NULL, 'Dear Aideed, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2024-04-07 22:07:39 . Balance:1,893,000/= Thank You!', 101, 'Success', '2024-04-07 23:07:53', '2024-04-07 23:07:53'),
(49, 1, 82, 'ATXid_671b4d0f5087d9c809460001af5aee2f', '+256702066819', NULL, 'Dear Aideed, Deposit on A/C xxxx673, amt: 20,000/=, Today: 2024-04-07 22:07:39 . Balance:1,893,000/= Thank You!', 101, 'Success', '2024-04-07 23:08:12', '2024-04-07 23:08:12'),
(50, 7, 83, 'ATXid_c46ea2c1ea372a5cd756d5de5ff355e6', '+256702066819', NULL, 'SACCONET: Dear Mwanga, Deposit of Ugx:20,000 To your SACCO ACCOUNT Was Successful On:07/04/2024. at 08:04. Thank you.', 101, 'Success', '2024-04-07 23:09:46', '2024-04-07 23:09:46'),
(51, 7, 84, 'ATXid_c46ea2c1ea372a5cd756d5de5ff355e6', '+256702066819', NULL, 'SACCONET: Dear Mwanga, Deposit of Ugx:20,000 To your SACCO ACCOUNT Was Successful On:07/04/2024. at 08:04. Thank you.', 101, 'Success', '2024-04-07 23:15:25', '2024-04-07 23:15:25'),
(52, 7, 85, 'ATXid_c46ea2c1ea372a5cd756d5de5ff355e6', '+256702066819', NULL, 'SACCONET: Dear Mwanga, Deposit of Ugx:200,000 To your SACCO ACCOUNT Was Successful On:07/04/2024. at 08:04. Thank you.', 101, 'Success', '2024-04-07 23:53:17', '2024-04-07 23:53:17'),
(53, 7, 86, 'ATXid_11abd39191517099a7d4aa41b8caa009', '+256704147754', NULL, 'SACCONET: Dear Ssentamu, Deposit of Ugx:23,000 To your SACCO ACCOUNT Was Successful On:08/04/2024. at 06:04. Thank you.', 101, 'Success', '2024-04-08 09:18:12', '2024-04-08 09:18:12'),
(54, 7, 87, 'ATXid_8a9367f7a7ba462a0f8bd75293c1268c', '+256704147754', NULL, 'SACCONET: Dear Ssentamu, Withdraw of Ugx:100,000 From your SACCO ACCOUNT Was Successful On:08/04/2024. at 08:04. Thank you.', 101, 'Success', '2024-04-08 09:46:16', '2024-04-08 09:46:16'),
(55, 7, 88, 'None', '+256711111111', NULL, 'SACCONET: Dear Mwanga, Deposit of Ugx:100 To your SACCO ACCOUNT Was Successful On:08/04/2024. at 09:04. Thank you.', 407, 'CouldNotRoute', '2024-04-08 12:30:41', '2024-04-08 12:30:41'),
(56, 7, 89, 'None', '+256711111111', NULL, 'SACCONET: Dear Mwanga, Deposit of Ugx:120,000 To your SACCO ACCOUNT Was Successful On:08/04/2024. at 12:04. Thank you.', 407, 'CouldNotRoute', '2024-04-08 15:03:32', '2024-04-08 15:03:32'),
(57, 7, 90, 'None', '+256711111111', NULL, 'SACCONET: Dear Mwanga, Deposit of Ugx:120,000 To your SACCO ACCOUNT Was Successful On:08/04/2024. at 12:04. Thank you.', 407, 'CouldNotRoute', '2024-04-08 15:07:27', '2024-04-08 15:07:27'),
(58, 7, 91, 'None', '+256711111111', NULL, 'SACCONET: Dear Mwanga, Deposit of Ugx:120,000 To your SACCO ACCOUNT Was Successful On:08/04/2024. at 12:04. Thank you.', 407, 'CouldNotRoute', '2024-04-08 15:11:01', '2024-04-08 15:11:01');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset`
--

CREATE TABLE `password_reset` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(200) NOT NULL,
  `expires_at` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment_provider`
--

CREATE TABLE `payment_provider` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `auth_name` varchar(200) DEFAULT NULL,
  `auth_password` varchar(200) DEFAULT NULL,
  `auth_api_key` varchar(200) DEFAULT NULL,
  `currency` varchar(10) DEFAULT 'UGX',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `status_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_provider`
--

INSERT INTO `payment_provider` (`id`, `name`, `auth_name`, `auth_password`, `auth_api_key`, `currency`, `date_created`, `date_modified`, `status_id`) VALUES
(1, 'Beyonic  API', '', '', '', 'UGX', '2021-08-01 17:45:23', '2021-08-01 17:47:27', 0),
(2, 'SentePay API', '', '', '', 'UGX', '2021-08-01 17:45:23', '2021-08-27 17:47:27', 1);

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` int(11) NOT NULL,
  `position` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `status_id` int(11) NOT NULL,
  `organisation_id` int(11) NOT NULL,
  `date_created` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `date_modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `position`, `description`, `status_id`, `organisation_id`, `date_created`, `created_by`, `date_modified`, `modified_by`) VALUES
(1, 'Administrator', 'sss', 1, 1, 1645908750, 2, '2022-02-26 23:52:30', NULL),
(2, 'C. E .0', 'sss', 1, 1, 1645908750, 2, '2022-02-26 23:52:30', NULL),
(3, 'FAM', 'sss', 1, 1, 1645908750, 2, '2022-02-26 23:52:30', NULL),
(4, 'Credit Manager', 'sss', 1, 1, 1645908750, 2, '2022-02-26 23:52:30', NULL),
(9, 'Software Developer', 'Software Developer', 1, 1, 16582255, 1, '2022-05-13 15:44:38', 1),
(10, 'ICT Infrastructure', 'ICT Infrastructure', 1, 1, 16582255, 1, '2022-05-13 15:44:38', NULL),
(11, 'Operations Manager', 'Operations Manager', 1, 1, 16582255, 1, '2022-05-13 15:46:00', 1),
(12, 'ICT Manager', 'ICT Manager', 1, 1, 16582255, 1, '2022-05-13 15:46:00', NULL),
(13, 'Accountant', 'Accountant', 1, 1, 16582255, 1, '2022-05-13 15:46:49', 1),
(14, 'ICT Officer', 'ICT Officer', 1, 1, 16582255, 1, '2022-05-13 15:46:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `description` varchar(20) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `description`, `date_created`, `date_modified`) VALUES
(1, 'Admin', '2021-06-29 22:29:48', '2021-06-29 22:29:48');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_modified` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`, `description`, `status_id`, `date_created`, `date_modified`, `created_by`, `modified_by`) VALUES
(1, 'Administrator', 'Admin for the system', 1, '2018-08-22 05:45:30', '2018-08-22 05:45:30', 1, 1),
(2, 'Supervisor', 'None', 1, '2022-04-12 03:24:39', '2022-04-12 03:24:39', 1, NULL),
(3, 'Manager', 'None', 1, '2018-08-30 11:39:36', '2018-08-30 11:39:36', 1, NULL),
(4, 'Auditor', 'Auditor', 1, '2022-04-12 03:25:40', '2022-04-12 03:25:40', 1, 1),
(5, 'Visitor', 'Visitor', 1, '2022-04-12 03:25:56', '2022-04-12 03:25:56', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sms_provider`
--

CREATE TABLE `sms_provider` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `auth_username` varchar(30) DEFAULT NULL,
  `auth_password` varchar(200) DEFAULT NULL,
  `auth_api_token` varchar(200) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sms_provider`
--

INSERT INTO `sms_provider` (`id`, `name`, `status`, `auth_username`, `auth_password`, `auth_api_token`, `date_created`, `date_modified`) VALUES
(1, 'Africastalking', 1, 'sacconet_technologies', NULL, '894fe36e99d01a17f05296dce9e0177983f7cb6e246ece7f6a61e73ad7defc2b', '2022-07-21 13:03:12', '1900-01-15 00:00:00'),
(2, 'Blue Global', 1, '', '', NULL, '2022-07-12 00:00:00', '1972-12-21 13:04:53');

-- --------------------------------------------------------

--
-- Table structure for table `sms_state`
--

CREATE TABLE `sms_state` (
  `id` int(11) NOT NULL,
  `description` varchar(20) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sms_state`
--

INSERT INTO `sms_state` (`id`, `description`, `date_created`, `date_modified`) VALUES
(1, 'pending', '2021-06-29 21:23:19', '2021-06-29 21:23:19'),
(2, 'sent', '2021-06-29 21:23:19', '2021-06-29 21:23:19'),
(3, 'failed', '2021-06-29 21:23:39', '2021-06-29 21:23:39');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `status_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `status_name`) VALUES
(1, 'Active'),
(2, 'Inactive'),
(3, 'Banned');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  `DEBIT` decimal(15,0) DEFAULT NULL,
  `CREDIT` decimal(15,0) DEFAULT NULL,
  `provider_cost` decimal(15,2) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `narrative` varchar(200) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `user_id`, `type`, `DEBIT`, `CREDIT`, `provider_cost`, `phone_number`, `narrative`, `status`, `date_created`, `date_modified`) VALUES
(1, 1, 'CREDIT', NULL, 10000, NULL, '+256000000000', 'DEPOSIT', 1, '2022-08-10 16:22:35', '2022-08-10 19:22:35'),
(2, 1, 'DEBIT', 30, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2022-08-10 16:22:38', '2022-08-10 19:22:38'),
(3, 1, 'DEBIT', 0, NULL, 0.00, '+256737473337', 'SMS CHARGES', 1, '2022-08-10 16:22:38', '2022-08-10 19:22:38'),
(4, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2022-08-10 16:24:28', '2022-08-10 19:24:28'),
(5, 1, 'DEBIT', 0, NULL, 0.00, '+256737473337', 'SMS CHARGES', 1, '2022-08-10 16:24:28', '2022-08-10 19:24:28'),
(6, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2022-08-10 16:26:05', '2022-08-10 19:26:05'),
(7, 1, 'DEBIT', 0, NULL, 0.00, '+256737473330', 'SMS CHARGES', 1, '2022-08-10 16:26:05', '2022-08-10 19:26:05'),
(8, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2022-08-10 16:26:11', '2022-08-10 19:26:11'),
(9, 1, 'DEBIT', 100, NULL, 35.00, '+256757473330', 'SMS CHARGES', 1, '2022-08-10 16:26:11', '2022-08-10 19:26:11'),
(10, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2022-08-11 09:04:53', '2022-08-11 12:04:53'),
(11, 1, 'CREDIT', NULL, 100, NULL, '+256000000000', 'DEPOSIT', 1, '2022-08-11 09:22:58', '2022-08-11 12:22:58'),
(12, 1, 'CREDIT', NULL, 10000, NULL, '+256000000000', 'DEPOSIT', 1, '2022-08-11 09:23:03', '2022-08-11 12:23:03'),
(13, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2022-08-15 08:21:55', '2022-08-15 11:21:55'),
(14, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2022-08-15 08:22:29', '2022-08-15 11:22:29'),
(15, 1, 'CREDIT', NULL, 100, NULL, '+256000000000', 'DEPOSIT', 1, '2022-08-17 08:15:12', '2022-08-17 11:15:12'),
(16, 1, 'CREDIT', NULL, 100, NULL, '+256000000000', 'DEPOSIT', 1, '2022-08-17 08:15:13', '2022-08-17 11:15:13'),
(17, 4, 'CREDIT', NULL, 100, NULL, '+256000000000', 'DEPOSIT', 1, '2022-08-17 08:18:12', '2022-08-17 11:18:12'),
(18, 4, 'CREDIT', NULL, 100, NULL, '+256000000000', 'DEPOSIT', 1, '2022-08-17 08:18:14', '2022-08-17 11:18:14'),
(19, 4, 'CREDIT', NULL, 100, NULL, '+256000000000', 'DEPOSIT', 1, '2022-08-17 08:19:13', '2022-08-17 11:19:13'),
(20, 4, 'CREDIT', NULL, 100, NULL, '+256000000000', 'DEPOSIT', 1, '2022-08-17 08:19:15', '2022-08-17 11:19:15'),
(21, 4, 'CREDIT', NULL, 1000, NULL, '+256000000000', 'DEPOSIT', 1, '2022-08-17 08:19:23', '2022-08-17 11:19:23'),
(22, 4, 'CREDIT', NULL, 1000, NULL, '+256000000000', 'DEPOSIT', 1, '2022-08-17 08:19:25', '2022-08-17 11:19:25'),
(23, 5, 'CREDIT', NULL, 100, NULL, '+256000000000', 'DEPOSIT', 1, '2022-08-17 08:19:34', '2022-08-17 11:19:34'),
(24, 2, 'CREDIT', NULL, 100, NULL, '+256000000000', 'DEPOSIT', 1, '2022-08-17 08:31:10', '2022-08-17 11:31:10'),
(25, 1, 'CREDIT', NULL, 100, NULL, '+256000000000', 'DEPOSIT', 1, '2022-08-17 08:35:16', '2022-08-17 11:35:16'),
(26, 4, 'CREDIT', NULL, 100, NULL, '+256000000000', 'DEPOSIT', 1, '2022-08-17 08:39:23', '2022-08-17 11:39:23'),
(27, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2022-08-17 08:39:45', '2022-08-17 11:39:45'),
(28, 1, 'CREDIT', NULL, 100, NULL, '+256000000000', 'DEPOSIT', 1, '2022-08-22 14:15:29', '2022-08-22 17:15:29'),
(29, 1, 'CREDIT', NULL, 100, NULL, '+256000000000', 'DEPOSIT', 1, '2022-08-22 14:15:31', '2022-08-22 17:15:31'),
(30, 1, 'CREDIT', NULL, 100, NULL, '+256000000000', 'DEPOSIT', 1, '2022-08-22 14:15:35', '2022-08-22 17:15:35'),
(31, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2022-08-22 14:20:49', '2022-08-22 17:20:49'),
(32, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2022-09-02 07:59:29', '2022-09-02 10:59:29'),
(33, 1, 'CREDIT', NULL, 100, NULL, '+256000000000', 'DEPOSIT', 1, '2022-09-02 08:35:33', '2022-09-02 11:35:33'),
(34, 1, 'CREDIT', NULL, 100, NULL, '+256000000000', 'DEPOSIT', 1, '2022-09-02 08:35:41', '2022-09-02 11:35:41'),
(35, 5, 'CREDIT', NULL, 10000, NULL, '+256000000000', 'DEPOSIT', 1, '2022-09-15 07:32:22', '2022-09-15 10:32:22'),
(36, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2022-09-15 07:32:58', '2022-09-15 10:32:58'),
(37, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2022-09-15 07:33:05', '2022-09-15 10:33:05'),
(38, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2022-09-15 07:33:29', '2022-09-15 10:33:29'),
(39, 5, 'CREDIT', NULL, 10000, NULL, '+256000000000', 'DEPOSIT', 1, '2022-09-15 07:33:40', '2022-09-15 10:33:40'),
(40, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2022-09-26 08:23:49', '2022-09-26 11:23:49'),
(41, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2022-09-28 13:21:14', '2022-09-28 16:21:14'),
(42, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2022-10-28 11:49:24', '2022-10-28 14:49:24'),
(43, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2022-10-31 13:56:04', '2022-10-31 16:56:04'),
(44, 1, 'CREDIT', NULL, 100, NULL, '+256000000000', 'DEPOSIT', 1, '2023-02-07 09:57:48', '2023-02-07 12:57:48'),
(45, 1, 'CREDIT', NULL, 100, NULL, '+256000000000', 'DEPOSIT', 1, '2023-02-07 10:00:18', '2023-02-07 13:00:18'),
(46, 1, 'CREDIT', NULL, 100, NULL, '+256000000000', 'DEPOSIT', 1, '2023-02-07 10:00:20', '2023-02-07 13:00:20'),
(47, 2, 'CREDIT', NULL, 10000, NULL, '+256000000000', 'DEPOSIT', 1, '2023-02-07 10:00:36', '2023-02-07 13:00:36'),
(48, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2023-08-20 21:15:53', '2023-08-21 00:15:53'),
(49, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2023-08-20 21:16:07', '2023-08-21 00:16:07'),
(50, 1, 'CREDIT', NULL, 3000, NULL, '+256000000000', 'DEPOSIT', 1, '2023-08-20 21:16:42', '2023-08-21 00:16:42'),
(51, 1, 'CREDIT', NULL, 3000, NULL, '+256000000000', 'DEPOSIT', 1, '2023-08-20 21:18:01', '2023-08-21 00:18:01'),
(52, 1, 'CREDIT', NULL, 3000, NULL, '+256000000000', 'DEPOSIT', 1, '2023-08-20 21:18:03', '2023-08-21 00:18:03'),
(53, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2024-03-13 19:40:26', '2024-03-13 22:40:26'),
(54, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2024-03-13 20:26:35', '2024-03-13 23:26:35'),
(55, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2024-03-13 20:27:42', '2024-03-13 23:27:42'),
(56, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2024-03-13 20:34:44', '2024-03-13 23:34:44'),
(57, 1, 'DEBIT', 100, NULL, 35.00, '+256775959489', 'SMS CHARGES', 1, '2024-03-13 20:35:00', '2024-03-13 23:35:00'),
(58, 1, 'DEBIT', 100, NULL, 27.00, '+256775959489', 'SMS CHARGES', 1, '2024-03-13 20:41:59', '2024-03-13 23:41:59'),
(59, 1, 'DEBIT', 100, NULL, 27.00, '+256775959489', 'SMS CHARGES', 1, '2024-03-13 20:43:59', '2024-03-13 23:43:59'),
(60, 1, 'DEBIT', 100, NULL, 27.00, '+256775959489', 'SMS CHARGES', 1, '2024-03-13 20:45:20', '2024-03-13 23:45:20'),
(61, 1, 'DEBIT', 100, NULL, 27.00, '+256775959489', 'SMS CHARGES', 1, '2024-03-13 20:46:06', '2024-03-13 23:46:06'),
(62, 4, 'CREDIT', NULL, 2000, NULL, '+256000000000', 'DEPOSIT', 1, '2024-03-13 20:48:34', '2024-03-13 23:48:34'),
(63, 1, 'CREDIT', NULL, 25000, NULL, '+256000000000', 'DEPOSIT', 1, '2024-04-03 11:31:14', '2024-04-03 12:31:14'),
(64, 1, 'CREDIT', NULL, 250000, NULL, '+256000000000', 'DEPOSIT', 1, '2024-04-04 09:14:41', '2024-04-04 10:14:41'),
(65, 1, 'DEBIT', 100, NULL, 25.00, '+256704147754', 'SMS CHARGES', 1, '2024-04-04 09:49:14', '2024-04-04 10:49:14'),
(66, 1, 'DEBIT', 100, NULL, 25.00, '+256704147754', 'SMS CHARGES', 1, '2024-04-04 10:12:29', '2024-04-04 11:12:29'),
(67, 7, 'CREDIT', NULL, 100000, NULL, '+256000000000', 'DEPOSIT', 1, '2024-04-04 10:34:03', '2024-04-04 11:34:03'),
(68, 7, 'DEBIT', 50, NULL, 25.00, '+256704147754', 'SMS CHARGES', 1, '2024-04-04 10:56:30', '2024-04-04 11:56:30'),
(69, 7, 'DEBIT', 50, NULL, 25.00, '+256704147754', 'SMS CHARGES', 1, '2024-04-04 11:03:36', '2024-04-04 12:03:36'),
(70, 7, 'DEBIT', 50, NULL, 25.00, '+256704147754', 'SMS CHARGES', 1, '2024-04-04 11:31:04', '2024-04-04 12:31:04'),
(71, 7, 'DEBIT', 50, NULL, 25.00, '+256704147754', 'SMS CHARGES', 1, '2024-04-04 11:32:57', '2024-04-04 12:32:57'),
(72, 7, 'DEBIT', 50, NULL, 25.00, '+256704147754', 'SMS CHARGES', 1, '2024-04-04 11:36:35', '2024-04-04 12:36:35'),
(73, 7, 'DEBIT', 50, NULL, 25.00, '+256704147754', 'SMS CHARGES', 1, '2024-04-04 11:48:08', '2024-04-04 12:48:08'),
(74, 1, 'DEBIT', 100, NULL, 27.00, '+256788611875', 'SMS CHARGES', 1, '2024-04-05 14:20:36', '2024-04-05 15:20:36'),
(75, 7, 'DEBIT', 50, NULL, 25.00, '+256704147754', 'SMS CHARGES', 1, '2024-04-05 14:43:56', '2024-04-05 15:43:56'),
(76, 7, 'DEBIT', 50, NULL, 25.00, '+256704147754', 'SMS CHARGES', 1, '2024-04-05 15:11:26', '2024-04-05 16:11:26'),
(77, 7, 'DEBIT', 50, NULL, 25.00, '+256704147754', 'SMS CHARGES', 1, '2024-04-05 15:15:24', '2024-04-05 16:15:24'),
(78, 7, 'DEBIT', 50, NULL, 25.00, '+256704147754', 'SMS CHARGES', 1, '2024-04-05 15:40:46', '2024-04-05 16:40:46'),
(79, 7, 'DEBIT', 50, NULL, 25.00, '+256702066819', 'SMS CHARGES', 1, '2024-04-07 18:53:25', '2024-04-07 19:53:25'),
(80, 7, 'DEBIT', 50, NULL, 25.00, '+256702066819', 'SMS CHARGES', 1, '2024-04-07 22:05:13', '2024-04-07 23:05:13'),
(81, 1, 'DEBIT', 100, NULL, 25.00, '+256702066819', 'SMS CHARGES', 1, '2024-04-07 22:07:53', '2024-04-07 23:07:53'),
(82, 1, 'DEBIT', 100, NULL, 25.00, '+256702066819', 'SMS CHARGES', 1, '2024-04-07 22:08:12', '2024-04-07 23:08:12'),
(83, 7, 'DEBIT', 50, NULL, 25.00, '+256702066819', 'SMS CHARGES', 1, '2024-04-07 22:09:46', '2024-04-07 23:09:46'),
(84, 7, 'DEBIT', 50, NULL, 25.00, '+256702066819', 'SMS CHARGES', 1, '2024-04-07 22:15:25', '2024-04-07 23:15:25'),
(85, 7, 'DEBIT', 50, NULL, 25.00, '+256702066819', 'SMS CHARGES', 1, '2024-04-07 22:53:17', '2024-04-07 23:53:17'),
(86, 7, 'DEBIT', 50, NULL, 25.00, '+256704147754', 'SMS CHARGES', 1, '2024-04-08 08:18:12', '2024-04-08 09:18:12'),
(87, 7, 'DEBIT', 50, NULL, 25.00, '+256704147754', 'SMS CHARGES', 1, '2024-04-08 08:46:16', '2024-04-08 09:46:16'),
(88, 7, 'DEBIT', 0, NULL, 0.00, '+256711111111', 'SMS CHARGES', 1, '2024-04-08 11:30:41', '2024-04-08 12:30:41'),
(89, 7, 'DEBIT', 0, NULL, 0.00, '+256711111111', 'SMS CHARGES', 1, '2024-04-08 14:03:32', '2024-04-08 15:03:32'),
(90, 7, 'DEBIT', 0, NULL, 0.00, '+256711111111', 'SMS CHARGES', 1, '2024-04-08 14:07:27', '2024-04-08 15:07:27'),
(91, 7, 'DEBIT', 0, NULL, 0.00, '+256711111111', 'SMS CHARGES', 1, '2024-04-08 14:11:01', '2024-04-08 15:11:01'),
(92, 7, 'CREDIT', NULL, 12000, NULL, '+256000000000', 'DEPOSIT', 1, '2024-04-09 11:05:04', '2024-04-09 12:05:04');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `organisation` text DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `mobile_number` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `role` int(11) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `status_id` tinyint(1) NOT NULL DEFAULT 1,
  `sms_rate` decimal(15,0) NOT NULL DEFAULT 30,
  `sms_provider_id` int(11) NOT NULL DEFAULT 3,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `organisation`, `email`, `mobile_number`, `password`, `role`, `user_type_id`, `status_id`, `sms_rate`, `sms_provider_id`, `date_created`, `date_modified`) VALUES
(1, 'sms', 'Admin', '', 'uccfsadmin@gmail.com', '+256702066819', '$2y$10$dRAlRU.DdxHG2evofaIHz.GClKsfWqzzl6Xtj1ntA5wkmL385DYkq', 0, 1, 1, 100, 1, '2022-08-09 16:35:02', '2024-04-09 11:42:28'),
(7, '', '', 'MALABA CROSSBORDER SACCO', 'malaba@gmail.com', '+256702066819', '', 0, 2, 1, 50, 1, '2024-04-04 11:33:29', '2024-04-07 23:52:02');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `status_id` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `date_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `user_id`, `role_id`, `status_id`, `date_created`, `created_by`, `date_modified`, `modified_by`) VALUES
(1, 1, 1, 1, 1630529422, 1, '2021-11-19 08:20:52', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `id` int(11) NOT NULL,
  `type` varchar(250) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`id`, `type`, `status`) VALUES
(1, 'Individual', 1),
(2, 'Sacco', 1),
(3, 'Business', 1),
(4, 'Organisation', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `api_key`
--
ALTER TABLE `api_key`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposit_transaction_status`
--
ALTER TABLE `deposit_transaction_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset`
--
ALTER TABLE `password_reset`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_provider`
--
ALTER TABLE `payment_provider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_provider`
--
ALTER TABLE `sms_provider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_state`
--
ALTER TABLE `sms_state`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_iddds` (`user_id`,`role_id`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `api_key`
--
ALTER TABLE `api_key`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposit_transaction_status`
--
ALTER TABLE `deposit_transaction_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `password_reset`
--
ALTER TABLE `password_reset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_provider`
--
ALTER TABLE `payment_provider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sms_provider`
--
ALTER TABLE `sms_provider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sms_state`
--
ALTER TABLE `sms_state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
