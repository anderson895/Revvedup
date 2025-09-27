-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2025 at 07:37 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `revvedup`
--

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `emp_id` int(11) NOT NULL,
  `emp_fname` varchar(60) NOT NULL,
  `emp_lname` varchar(60) NOT NULL,
  `emp_status` int(11) NOT NULL DEFAULT 1 COMMENT '0=not active,1=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`emp_id`, `emp_fname`, `emp_lname`, `emp_status`) VALUES
(1, 'john', 'doe', 1),
(2, 'patrick', 'beverly', 1),
(3, 'mario', 'barrios', 1),
(4, 'maxine', 'santos', 1);

-- --------------------------------------------------------

--
-- Table structure for table `item_cart`
--

CREATE TABLE `item_cart` (
  `item_id` int(11) NOT NULL,
  `item_user_id` int(11) NOT NULL,
  `item_prod_id` int(60) NOT NULL,
  `item_qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `prod_id` int(11) NOT NULL,
  `prod_name` varchar(60) NOT NULL,
  `prod_capital` decimal(10,2) NOT NULL,
  `prod_price` decimal(10,2) NOT NULL,
  `prod_qty` int(11) NOT NULL,
  `prod_img` varchar(255) NOT NULL,
  `prod_status` int(11) NOT NULL DEFAULT 1 COMMENT '0=deleted,1=archived'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`prod_id`, `prod_name`, `prod_capital`, `prod_price`, `prod_qty`, `prod_img`, `prod_status`) VALUES
(3, 'product 2', 5.00, 11.00, 210, 'item_68d60b3664f741.76866050.png', 1),
(4, 'product 1', 130.00, 150.00, 95, 'item_68d6430495df58.36133211.jpg', 1),
(5, 'product 3', 280.00, 300.00, 70, 'item_68d643420bba08.70461903.jpg', 1),
(6, 'product 6', 50.00, 55.00, 60, 'item_68d665eca329d5.22334298.jpg', 1),
(7, 'test', 500.00, 600.00, 100, 'item_68d776d7db06b6.21276657.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `service_cart`
--

CREATE TABLE `service_cart` (
  `service_id` int(11) NOT NULL,
  `service_user_id` int(11) NOT NULL,
  `service_name` varchar(60) NOT NULL,
  `service_price` decimal(10,2) NOT NULL,
  `service_employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `transaction_id` int(11) NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `transaction_service` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`transaction_service`)),
  `transaction_item` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`transaction_item`)),
  `transaction_discount` decimal(10,2) NOT NULL,
  `transaction_vat` decimal(5,2) NOT NULL,
  `transaction_total` decimal(10,2) NOT NULL,
  `transaction_payment` decimal(10,2) NOT NULL,
  `transaction_change` decimal(10,2) NOT NULL,
  `transaction_status` int(11) NOT NULL COMMENT '0=archived,1=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`transaction_id`, `transaction_date`, `transaction_service`, `transaction_item`, `transaction_discount`, `transaction_vat`, `transaction_total`, `transaction_payment`, `transaction_change`, `transaction_status`) VALUES
(19, '2025-09-26 15:14:56', '[{\"name\":\"car wash\",\"price\":\"50\",\"emp_id\":\"3\"},{\"name\":\"change oil\",\"price\":\"100\",\"emp_id\":\"3\"},{\"name\":\"change all\",\"price\":\"100\",\"emp_id\":\"4\"}]', '[{\"name\":\"product 2\",\"qty\":\"1\",\"subtotal\":\"11\",\"prod_id\":\"3\"},{\"name\":\"product 3\",\"qty\":\"3\",\"subtotal\":\"900\",\"prod_id\":\"5\"}]', 0.00, 109.32, 1270.32, 10000.00, 8729.68, 1),
(20, '2025-09-26 15:16:50', '[{\"name\":\"car wash\",\"price\":\"50\",\"emp_id\":\"3\"},{\"name\":\"change oil\",\"price\":\"100\",\"emp_id\":\"3\"},{\"name\":\"change all\",\"price\":\"100\",\"emp_id\":\"4\"}]', '[{\"name\":\"product 2\",\"qty\":\"1\",\"subtotal\":\"11\",\"prod_id\":\"3\"},{\"name\":\"product 3\",\"qty\":\"3\",\"subtotal\":\"900\",\"prod_id\":\"5\"}]', 0.00, 109.32, 1270.32, 10000.00, 8729.68, 1),
(21, '2025-09-26 15:18:55', '[{\"name\":\"car wash\",\"price\":\"50\",\"emp_id\":\"3\"},{\"name\":\"change oil\",\"price\":\"100\",\"emp_id\":\"3\"},{\"name\":\"change all\",\"price\":\"100\",\"emp_id\":\"4\"}]', '[{\"name\":\"product 2\",\"qty\":\"1\",\"subtotal\":\"11\",\"prod_id\":\"3\"},{\"name\":\"product 3\",\"qty\":\"3\",\"subtotal\":\"900\",\"prod_id\":\"5\"}]', 100.00, 97.32, 1158.32, 10000.00, 8841.68, 1),
(22, '2025-09-26 15:20:16', '[{\"name\":\"car wash\",\"price\":\"50\",\"emp_id\":\"3\"},{\"name\":\"change oil\",\"price\":\"100\",\"emp_id\":\"3\"},{\"name\":\"change all\",\"price\":\"100\",\"emp_id\":\"4\"}]', '[{\"prod_id\":\"3\",\"name\":\"product 2\",\"qty\":\"1\",\"subtotal\":\"11\"},{\"prod_id\":\"5\",\"name\":\"product 3\",\"qty\":\"3\",\"subtotal\":\"900\"}]', 0.00, 109.32, 1270.32, 10000.00, 8729.68, 1),
(23, '2025-09-26 15:20:25', '[{\"name\":\"car wash\",\"price\":\"50\",\"emp_id\":\"3\"},{\"name\":\"change oil\",\"price\":\"100\",\"emp_id\":\"3\"},{\"name\":\"change all\",\"price\":\"100\",\"emp_id\":\"4\"}]', '[{\"prod_id\":\"3\",\"name\":\"product 2\",\"qty\":\"1\",\"subtotal\":\"11\"},{\"prod_id\":\"5\",\"name\":\"product 3\",\"qty\":\"3\",\"subtotal\":\"900\"}]', 0.00, 109.32, 1270.32, 0.00, 0.00, 1),
(24, '2025-09-26 15:20:58', '[{\"name\":\"car wash\",\"price\":\"50\",\"emp_id\":\"3\"},{\"name\":\"change oil\",\"price\":\"100\",\"emp_id\":\"3\"},{\"name\":\"change all\",\"price\":\"100\",\"emp_id\":\"4\"}]', '[{\"prod_id\":\"3\",\"name\":\"product 2\",\"qty\":\"1\",\"subtotal\":\"11\"},{\"prod_id\":\"5\",\"name\":\"product 3\",\"qty\":\"3\",\"subtotal\":\"900\"}]', 0.00, 109.32, 1270.32, 10000.00, 8729.68, 1),
(25, '2025-09-26 15:24:01', '[{\"service_id\":\"12\",\"name\":\"car wash\",\"price\":\"50\",\"emp_id\":\"3\"},{\"service_id\":\"11\",\"name\":\"change oil\",\"price\":\"100\",\"emp_id\":\"3\"},{\"service_id\":\"10\",\"name\":\"change all\",\"price\":\"100\",\"emp_id\":\"4\"}]', '[{\"item_id\":\"13\",\"prod_id\":\"3\",\"name\":\"product 2\",\"qty\":\"1\",\"subtotal\":\"11\"},{\"item_id\":\"12\",\"prod_id\":\"5\",\"name\":\"product 3\",\"qty\":\"3\",\"subtotal\":\"900\"}]', 0.00, 109.32, 1270.32, 10000.00, 8729.68, 1),
(26, '2025-09-26 16:07:42', '[{\"service_id\":\"17\",\"name\":\"parking\",\"price\":\"50\",\"emp_id\":\"1\"},{\"service_id\":\"16\",\"name\":\"painting\",\"price\":\"100\",\"emp_id\":\"1\"},{\"service_id\":\"18\",\"name\":\"washing\",\"price\":\"50\",\"emp_id\":\"3\"}]', '[{\"item_id\":\"15\",\"prod_id\":\"3\",\"name\":\"product 2\",\"qty\":\"1\",\"subtotal\":\"11\"},{\"item_id\":\"14\",\"prod_id\":\"5\",\"name\":\"product 3\",\"qty\":\"1\",\"subtotal\":\"300\"}]', 0.00, 37.32, 548.32, 800.00, 251.68, 1),
(27, '2025-09-27 00:09:35', '[{\"service_id\":\"21\",\"name\":\"gear oil\",\"price\":\"99\",\"emp_id\":\"1\"},{\"service_id\":\"20\",\"name\":\"car wash\",\"price\":\"50\",\"emp_id\":\"2\"},{\"service_id\":\"19\",\"name\":\"change oil\",\"price\":\"100\",\"emp_id\":\"2\"}]', '[{\"item_id\":\"17\",\"prod_id\":\"5\",\"name\":\"product 3\",\"qty\":\"8\",\"subtotal\":\"2400\"},{\"item_id\":\"16\",\"prod_id\":\"4\",\"name\":\"product 1\",\"qty\":\"1\",\"subtotal\":\"150\"}]', 0.00, 306.00, 3105.00, 10000.00, 6895.00, 1),
(28, '2025-09-27 00:12:12', '[{\"service_id\":\"22\",\"name\":\"service 1\",\"price\":\"999\",\"emp_id\":\"2\"}]', '[{\"item_id\":\"21\",\"prod_id\":\"3\",\"name\":\"product 2\",\"qty\":\"1\",\"subtotal\":\"11\"},{\"item_id\":\"19\",\"prod_id\":\"4\",\"name\":\"product 1\",\"qty\":\"1\",\"subtotal\":\"150\"}]', 0.00, 19.32, 1179.32, 1200.00, 20.68, 1),
(29, '2025-09-27 00:15:18', '[]', '[{\"item_id\":\"22\",\"prod_id\":\"4\",\"name\":\"product 1\",\"qty\":\"1\",\"subtotal\":\"150\"}]', 0.00, 18.00, 168.00, 1000.00, 832.00, 1),
(30, '2025-09-27 00:16:35', '[]', '[{\"item_id\":\"23\",\"prod_id\":\"4\",\"name\":\"product 1\",\"qty\":\"1\",\"subtotal\":\"150\"}]', 0.00, 18.00, 168.00, 200.00, 32.00, 1),
(31, '2025-09-27 00:16:54', '[]', '[{\"item_id\":\"24\",\"prod_id\":\"4\",\"name\":\"product 1\",\"qty\":\"1\",\"subtotal\":\"150\"}]', 0.00, 18.00, 168.00, 200.00, 32.00, 1),
(32, '2025-09-27 00:17:43', '[]', '[{\"item_id\":\"25\",\"prod_id\":\"6\",\"name\":\"product 6\",\"qty\":\"66\",\"subtotal\":\"3630\"}]', 0.00, 435.60, 4065.60, 5000.00, 934.40, 1),
(33, '2025-09-27 00:18:50', '[]', '[{\"item_id\":\"26\",\"prod_id\":\"6\",\"name\":\"product 6\",\"qty\":\"6\",\"subtotal\":\"330\"}]', 0.00, 39.60, 369.60, 400.00, 30.40, 1),
(34, '2025-09-27 00:25:19', '[{\"service_id\":\"23\",\"name\":\"change oil\",\"price\":\"50\",\"emp_id\":\"4\"}]', '[]', 0.00, 0.00, 50.00, 100.00, 50.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `firstname` varchar(60) NOT NULL,
  `lastname` varchar(60) NOT NULL,
  `username` varchar(60) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `pin` int(11) DEFAULT NULL,
  `position` enum('admin','employee','','') NOT NULL DEFAULT 'employee',
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0=not active , 1=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `firstname`, `lastname`, `username`, `password`, `pin`, `position`, `status`) VALUES
(1, 'juan', 'dela cruz', 'admin', '$2a$12$jsAMWl2OcxUyo8OP7mLH4uTRYd5ln04QTtjU5O3qS1Q5N1o6f6yzm', NULL, 'admin', 1),
(7, 'joshua', 'padilla', NULL, NULL, 12345, 'employee', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `item_cart`
--
ALTER TABLE `item_cart`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`prod_id`);

--
-- Indexes for table `service_cart`
--
ALTER TABLE `service_cart`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `service_employee_id` (`service_employee_id`),
  ADD KEY `service_user_id` (`service_user_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `item_cart`
--
ALTER TABLE `item_cart`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `prod_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `service_cart`
--
ALTER TABLE `service_cart`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `service_cart`
--
ALTER TABLE `service_cart`
  ADD CONSTRAINT `service_cart_ibfk_1` FOREIGN KEY (`service_employee_id`) REFERENCES `employee` (`emp_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `service_cart_ibfk_2` FOREIGN KEY (`service_user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
