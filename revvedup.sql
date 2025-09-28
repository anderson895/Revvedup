-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2025 at 07:15 PM
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
-- Table structure for table `deduction`
--

CREATE TABLE `deduction` (
  `deduction_id` int(11) NOT NULL,
  `deduction_date` varchar(60) NOT NULL,
  `deduction_emp_id` int(11) NOT NULL,
  `deduction_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deduction`
--

INSERT INTO `deduction` (`deduction_id`, `deduction_date`, `deduction_emp_id`, `deduction_amount`) VALUES
(1, 'September 2025 Week 5 ', 3, 50.00),
(2, 'September 2025 Week 5 ', 2, 300.00);

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
(3, 'product 2', 5.00, 11.00, 205, 'item_68d60b3664f741.76866050.png', 1),
(4, 'product 1', 130.00, 150.00, 87, 'item_68d6430495df58.36133211.jpg', 1),
(5, 'product 3', 280.00, 300.00, 63, 'item_68d643420bba08.70461903.jpg', 1),
(6, 'product 6', 50.00, 55.00, 54, 'item_68d77ad7dc2db3.03460954.avif', 1),
(7, 'test', 500.00, 600.00, 99, 'item_68d776d7db06b6.21276657.jpg', 1);

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
(36, '2025-09-27 06:02:45', '[]', '[{\"item_id\":\"29\",\"prod_id\":\"4\",\"name\":\"product 1\",\"qty\":\"1\",\"subtotal\":\"150\",\"capital\":\"130\"},{\"item_id\":\"28\",\"prod_id\":\"6\",\"name\":\"product 6\",\"qty\":\"5\",\"subtotal\":\"275\",\"capital\":\"50\"}]', 0.00, 51.00, 476.00, 500.00, 24.00, 1),
(37, '2025-09-27 06:34:25', '[]', '[{\"item_id\":\"30\",\"prod_id\":\"4\",\"name\":\"product 1\",\"qty\":\"1\",\"subtotal\":\"150\",\"capital\":\"130\"}]', 0.00, 18.00, 168.00, 200.00, 32.00, 1),
(38, '2025-09-27 06:36:49', '[{\"service_id\":\"24\",\"name\":\"chang oil\",\"price\":\"100\",\"emp_id\":\"2\"}]', '[{\"item_id\":\"33\",\"prod_id\":\"4\",\"name\":\"product 1\",\"qty\":\"1\",\"subtotal\":\"150\",\"capital\":\"130\"},{\"item_id\":\"32\",\"prod_id\":\"4\",\"name\":\"product 1\",\"qty\":\"5\",\"subtotal\":\"750\",\"capital\":\"130\"},{\"item_id\":\"31\",\"prod_id\":\"6\",\"name\":\"product 6\",\"qty\":\"1\",\"subtotal\":\"55\",\"capital\":\"50\"}]', 0.00, 114.60, 1169.60, 1200.00, 30.40, 1),
(39, '2025-10-27 06:38:06', '[]', '[{\"item_id\":\"35\",\"prod_id\":\"3\",\"name\":\"product 2\",\"qty\":\"5\",\"subtotal\":\"55\",\"capital\":\"5\"},{\"item_id\":\"34\",\"prod_id\":\"7\",\"name\":\"test\",\"qty\":\"1\",\"subtotal\":\"600\",\"capital\":\"500\"}]', 0.00, 78.60, 733.60, 750.00, 16.40, 1),
(40, '2025-09-28 14:14:08', '[{\"service_id\":\"25\",\"name\":\"change color\",\"price\":\"500\",\"emp_id\":\"3\"}]', '[{\"item_id\":\"36\",\"prod_id\":\"5\",\"name\":\"product 3\",\"qty\":\"5\",\"subtotal\":\"1500\",\"capital\":\"280\"}]', 0.00, 180.00, 2180.00, 2200.00, 20.00, 1),
(41, '2025-08-28 14:28:21', '[{\"service_id\":\"28\",\"name\":\"cvt cleaning\",\"price\":\"300\",\"emp_id\":\"1\"},{\"service_id\":\"26\",\"name\":\"change oil\",\"price\":\"100\",\"emp_id\":\"1\"},{\"service_id\":\"27\",\"name\":\"gear oil\",\"price\":\"50\",\"emp_id\":\"4\"}]', '[]', 0.00, 0.00, 450.00, 500.00, 50.00, 1),
(42, '2025-09-28 14:29:33', '[{\"service_id\":\"29\",\"name\":\"bulcanize\",\"price\":\"60\",\"emp_id\":\"1\"}]', '[]', 0.00, 0.00, 60.00, 100.00, 40.00, 1),
(43, '2025-09-25 15:22:13', '[{\"service_id\":\"30\",\"name\":\"cvt repair\",\"price\":\"1500\",\"emp_id\":\"1\"}]', '[]', 0.00, 0.00, 1500.00, 15000.00, 13500.00, 1),
(44, '2025-09-28 15:33:50', '[{\"service_id\":\"32\",\"name\":\"car wash\",\"price\":\"150\",\"emp_id\":\"1\"},{\"service_id\":\"31\",\"name\":\"car wash\",\"price\":\"150\",\"emp_id\":\"4\"}]', '[{\"item_id\":\"37\",\"prod_id\":\"5\",\"name\":\"product 3\",\"qty\":\"1\",\"subtotal\":\"300\",\"capital\":\"280\"}]', 0.00, 36.00, 636.00, 700.00, 64.00, 1),
(45, '2025-09-28 16:01:16', '[{\"service_id\":\"34\",\"name\":\"painting\",\"price\":\"500\",\"emp_id\":\"2\"},{\"service_id\":\"33\",\"name\":\"change color\",\"price\":\"250\",\"emp_id\":\"3\"}]', '[]', 0.00, 0.00, 750.00, 1000.00, 250.00, 1);

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
-- Indexes for table `deduction`
--
ALTER TABLE `deduction`
  ADD PRIMARY KEY (`deduction_id`),
  ADD KEY `deduction_emp_id` (`deduction_emp_id`);

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
-- AUTO_INCREMENT for table `deduction`
--
ALTER TABLE `deduction`
  MODIFY `deduction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `item_cart`
--
ALTER TABLE `item_cart`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `prod_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `service_cart`
--
ALTER TABLE `service_cart`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deduction`
--
ALTER TABLE `deduction`
  ADD CONSTRAINT `deduction_ibfk_1` FOREIGN KEY (`deduction_emp_id`) REFERENCES `employee` (`emp_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
