-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 01, 2025 at 05:37 AM
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
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `reference_number` int(255) NOT NULL,
  `service` varchar(255) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `appointment_customer_id` int(11) NOT NULL,
  `fullname` varchar(150) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `appointmentDate` date NOT NULL,
  `appointmentTime` time NOT NULL,
  `emergency` tinyint(1) DEFAULT 0,
  `status` enum('pending','request canceled','approved','canceled') DEFAULT 'pending',
  `seen` int(11) NOT NULL DEFAULT 0 COMMENT '0=unseen,1=seen',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `reference_number`, `service`, `employee_id`, `appointment_customer_id`, `fullname`, `contact`, `appointmentDate`, `appointmentTime`, `emergency`, `status`, `seen`, `created_at`) VALUES
(17, 561535, 'Brake Service', 8, 1, 'john doe', '09454454744', '2025-09-30', '14:45:00', 1, 'pending', 0, '2025-09-30 06:46:02');

-- --------------------------------------------------------

--
-- Table structure for table `business_details`
--

CREATE TABLE `business_details` (
  `business_id` int(11) NOT NULL,
  `business_name` varchar(60) NOT NULL,
  `business_address` text NOT NULL,
  `business_contact_num` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_details`
--

INSERT INTO `business_details` (`business_id`, `business_name`, `business_address`, `business_contact_num`) VALUES
(1, 'reveveveup', 'sta.maria bulacan', '09454454744');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `customer_fullname` varchar(60) NOT NULL,
  `customer_email` varchar(60) NOT NULL,
  `customer_password` varchar(255) NOT NULL,
  `customer_status` int(11) NOT NULL DEFAULT 1 COMMENT '0=notactive,1=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `customer_fullname`, `customer_email`, `customer_password`, `customer_status`) VALUES
(1, 'joshua', 'joshua@gmail.com', '$2y$10$q4ZHWGUQ4NHj8ySLgBzTXOmP2SGMDlfYrDxv8j89sDTaX5A/YPqV2', 1);

-- --------------------------------------------------------

--
-- Table structure for table `deduction`
--

CREATE TABLE `deduction` (
  `deduction_id` int(11) NOT NULL,
  `deduction_date` varchar(60) NOT NULL,
  `deduction_user_id` int(11) NOT NULL,
  `deduction_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deduction`
--

INSERT INTO `deduction` (`deduction_id`, `deduction_date`, `deduction_user_id`, `deduction_amount`) VALUES
(7, 'September 2025 Week 5 ', 1, 10.00);

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
  `prod_status` int(11) NOT NULL DEFAULT 1 COMMENT '0=deleted,1=archived',
  `prod_category` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`prod_id`, `prod_name`, `prod_capital`, `prod_price`, `prod_qty`, `prod_img`, `prod_status`, `prod_category`) VALUES
(3, 'product 2', 5.00, 11.00, 74, 'item_68d60b3664f741.76866050.png', 1, 'Exhaust Systems'),
(4, 'product 1', 130.00, 150.00, 78, 'item_68d6430495df58.36133211.jpg', 1, 'Exhaust Systems'),
(5, 'product 3', 280.00, 300.00, 46, 'item_68d643420bba08.70461903.jpg', 1, 'Wheels & Tires'),
(6, 'product 6', 50.00, 55.00, 44, 'item_68d77ad7dc2db3.03460954.avif', 1, 'Exhaust Systems'),
(7, 'test', 500.00, 600.00, 1, 'item_68d776d7db06b6.21276657.jpg', 1, 'Brakes'),
(8, 'test 2', 50.00, 60.00, 0, 'item_68d9f9e9b27152.72092957.jpg', 1, 'Engine & Transmission'),
(9, 'NSCJRIC', 83.00, 93.00, 99, 'item_68dbfe90131176.75854224.jpg', 1, 'Exhaust Systems'),
(10, 'YumBurger', 68.00, 78.00, 99, 'item_68dbfe7e4f9863.95790433.jpg', 1, 'Brakes');

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
(15, '[{\"name\":\"product 2\",\"qty\":1,\"type\":\"refund\"}]', 1, 64),
(16, '[{\"name\":\"NSCJRIC\",\"qty\":1,\"type\":\"refund\"}]', 1, 66),
(17, '[{\"name\":\"YumBurger\",\"qty\":1,\"type\":\"refund\"}]', 1, 66);

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
(62, '2025-09-29 10:35:12', '[{\"service_id\":\"49\",\"name\":\"change oil\",\"price\":\"100\",\"user_id\":\"1\"}]', '[]', 0.00, 0.00, 100.00, 100.00, 0.00, 1),
(63, '2025-09-29 10:42:33', '[{\"service_id\":\"50\",\"name\":\"change oil\",\"price\":\"100\",\"user_id\":\"7\"}]', '[{\"item_id\":\"52\",\"prod_id\":\"5\",\"name\":\"product 3\",\"qty\":\"1\",\"subtotal\":\"300\",\"capital\":\"280\"}]', 0.00, 36.00, 436.00, 500.00, 64.00, 1),
(64, '2025-09-29 10:49:56', '[{\"service_id\":\"51\",\"name\":\"repair\",\"price\":\"150\",\"user_id\":\"7\"}]', '[{\"item_id\":\"53\",\"prod_id\":\"3\",\"name\":\"product 2\",\"qty\":\"10\",\"subtotal\":\"110\",\"capital\":\"5\"}]', 0.00, 13.20, 273.20, 300.00, 26.80, 1),
(65, '2025-09-29 12:12:08', '[{\"service_id\":\"52\",\"name\":\"car wash\",\"price\":\"100\",\"user_id\":\"7\"}]', '[{\"item_id\":\"54\",\"prod_id\":\"5\",\"name\":\"product 3\",\"qty\":\"1\",\"subtotal\":\"300\",\"capital\":\"280\"}]', 0.00, 36.00, 436.00, 500.00, 64.00, 1),
(66, '2025-09-30 16:01:26', '[]', '[{\"item_id\":\"58\",\"prod_id\":\"9\",\"name\":\"NSCJRIC\",\"qty\":\"1\",\"subtotal\":\"93\",\"capital\":\"83\"},{\"item_id\":\"57\",\"prod_id\":\"10\",\"name\":\"YumBurger\",\"qty\":\"1\",\"subtotal\":\"78\",\"capital\":\"68\"}]', 0.00, 18.32, 171.00, 200.00, 29.00, 1),
(67, '2025-10-08 01:14:33', '[{\"service_id\":\"54\",\"name\":\"change oil\",\"price\":\"100\",\"user_id\":\"7\"}]', '[]', 0.00, 10.71, 100.00, 100.00, 0.00, 1),
(68, '2025-10-08 01:14:33', '[{\"service_id\":\"55\",\"name\":\"change oil\",\"price\":\"100\",\"user_id\":\"9\"}]', '[]', 0.00, 10.71, 100.00, 100.00, 0.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `firstname` varchar(60) NOT NULL,
  `lastname` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `username` varchar(60) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `pin` text DEFAULT NULL,
  `position` enum('admin','employee','','') NOT NULL DEFAULT 'employee',
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0=not active , 1=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `firstname`, `lastname`, `email`, `username`, `password`, `pin`, `position`, `status`) VALUES
(1, 'juan', 'dela cruz', 'admin@gmail.com', 'admin', '$2a$12$jsAMWl2OcxUyo8OP7mLH4uTRYd5ln04QTtjU5O3qS1Q5N1o6f6yzm', NULL, 'admin', 1),
(7, 'joshua', 'padilla', 'joshua@gmail.com', 'masterparj', NULL, '12345', 'employee', 1),
(8, 'john', 'doe', 'johndoe123@gmail.com', 'johndoe', NULL, '000000', 'employee', 1),
(9, 'johnloyd', 'richard', 'johnloyd@gmail.com', 'jhonload123', NULL, '3409834', 'employee', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `fk_employee` (`employee_id`),
  ADD KEY `fk_customer` (`appointment_customer_id`);

--
-- Indexes for table `business_details`
--
ALTER TABLE `business_details`
  ADD PRIMARY KEY (`business_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `deduction`
--
ALTER TABLE `deduction`
  ADD PRIMARY KEY (`deduction_id`),
  ADD KEY `deduction_emp_id` (`deduction_user_id`);

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
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `business_details`
--
ALTER TABLE `business_details`
  MODIFY `business_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deduction`
--
ALTER TABLE `deduction`
  MODIFY `deduction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `item_cart`
--
ALTER TABLE `item_cart`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `prod_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `service_cart`
--
ALTER TABLE `service_cart`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `fk_customer` FOREIGN KEY (`appointment_customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_employee` FOREIGN KEY (`employee_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `deduction`
--
ALTER TABLE `deduction`
  ADD CONSTRAINT `deduction_ibfk_1` FOREIGN KEY (`deduction_user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `returns_ibfk_1` FOREIGN KEY (`return_transaction_id`) REFERENCES `transaction` (`transaction_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `service_cart`
--
ALTER TABLE `service_cart`
  ADD CONSTRAINT `service_cart_ibfk_1` FOREIGN KEY (`service_employee_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `service_cart_ibfk_2` FOREIGN KEY (`service_user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
