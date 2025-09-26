-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 26, 2025 at 12:09 PM
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
  `prod_price` decimal(10,2) NOT NULL,
  `prod_qty` int(11) NOT NULL,
  `prod_img` varchar(255) NOT NULL,
  `prod_status` int(11) NOT NULL DEFAULT 1 COMMENT '0=deleted,1=archived'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`prod_id`, `prod_name`, `prod_price`, `prod_qty`, `prod_img`, `prod_status`) VALUES
(3, 'product 2', 11.00, 222, 'item_68d60b3664f741.76866050.png', 1),
(4, 'product 1', 150.00, 100, 'item_68d6430495df58.36133211.jpg', 1),
(5, 'product 3', 300.00, 5, 'item_68d643420bba08.70461903.jpg', 1),
(6, 'product 6', 55.00, 66, 'item_68d665eca329d5.22334298.jpg', 1);

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

--
-- Dumping data for table `service_cart`
--

INSERT INTO `service_cart` (`service_id`, `service_user_id`, `service_name`, `service_price`, `service_employee_id`) VALUES
(6, 1, 'chang all', 100.00, 1),
(8, 1, 'car wash', 60.00, 1);

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
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `prod_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `service_cart`
--
ALTER TABLE `service_cart`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
