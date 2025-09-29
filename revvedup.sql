-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2025 at 08:44 AM
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
(3, 'September 2025 Week 5 ', 1, 100.00),
(4, 'September 2025 Week 5 ', 4, 50.00),
(5, 'September 2025 Week 5 ', 5, 100.00),
(6, 'September 2025 Week 1 ', 5, 100.00);

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
(4, 'maxine', 'santos', 1),
(5, 'andy', 'padilla', 1);

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
(3, 'product 2', 5.00, 11.00, 84, 'item_68d60b3664f741.76866050.png', 1),
(4, 'product 1', 130.00, 150.00, 78, 'item_68d6430495df58.36133211.jpg', 1),
(5, 'product 3', 280.00, 300.00, 48, 'item_68d643420bba08.70461903.jpg', 1),
(6, 'product 6', 50.00, 55.00, 44, 'item_68d77ad7dc2db3.03460954.avif', 1),
(7, 'test', 500.00, 600.00, 1, 'item_68d776d7db06b6.21276657.jpg', 1),
(8, 'test 2', 50.00, 60.00, 0, 'item_68d9f9e9b27152.72092957.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `return_id` int(11) NOT NULL,
  `return_transaction_item` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`return_transaction_item`)),
  `return_qty` int(11) NOT NULL,
  `return_transaction_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returns`
--

INSERT INTO `returns` (`return_id`, `return_transaction_item`, `return_qty`, `return_transaction_id`) VALUES
(8, '[{\"name\":\"product 3\",\"qty\":2,\"type\":\"refund\"}]', 2, 58),
(9, '[{\"name\":\"product 2\",\"qty\":1,\"type\":\"exchange\"}]', 1, 58),
(10, '[{\"name\":\"product 2\",\"qty\":1,\"type\":\"exchange\"}]', 1, 58),
(11, '[{\"name\":\"product 3\",\"qty\":1,\"type\":\"refund\"}]', 1, 58),
(12, '[{\"name\":\"product 3\",\"qty\":1,\"type\":\"refund\"}]', 1, 58),
(13, '[{\"name\":\"product 2\",\"qty\":1,\"type\":\"refund\"}]', 1, 58),
(14, '[{\"name\":\"product 2\",\"qty\":1,\"type\":\"refund\"}]', 1, 58);

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
(58, '2025-09-28 06:19:50', '[]', '[{\"item_id\":\"51\",\"prod_id\":\"3\",\"name\":\"product 2\",\"qty\":\"10\",\"subtotal\":\"110\",\"capital\":\"5\"},{\"item_id\":\"50\",\"prod_id\":\"5\",\"name\":\"product 3\",\"qty\":\"5\",\"subtotal\":\"1500\",\"capital\":\"280\"}]', 0.00, 193.20, 1803.20, 1900.00, 96.80, 1),
(59, '2025-09-29 06:44:32', '[{\"service_id\":\"43\",\"name\":\"change oil\",\"price\":\"100\",\"emp_id\":\"5\"}]', '[]', 0.00, 0.00, 100.00, 100.00, 0.00, 1);

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
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`return_id`),
  ADD KEY `return_transaction_id` (`return_transaction_id`);

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
  MODIFY `deduction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `item_cart`
--
ALTER TABLE `item_cart`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `prod_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `service_cart`
--
ALTER TABLE `service_cart`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

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
-- Constraints for table `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `returns_ibfk_1` FOREIGN KEY (`return_transaction_id`) REFERENCES `transaction` (`transaction_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
