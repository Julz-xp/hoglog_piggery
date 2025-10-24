-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2025 at 06:23 AM
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
-- Database: `hoglog`
--

-- --------------------------------------------------------

--
-- Table structure for table `ai_attempt`
--

CREATE TABLE `ai_attempt` (
  `ai_id` int(11) NOT NULL,
  `sow_id` int(11) NOT NULL,
  `heat_detection_date` date DEFAULT NULL,
  `ai_date` date DEFAULT NULL,
  `breeding_type` varchar(50) DEFAULT NULL,
  `boar_source` varchar(100) DEFAULT NULL,
  `farm_vet` varchar(100) DEFAULT NULL,
  `pregnancy_check_date` date DEFAULT NULL,
  `confirmation` enum('Positive','Negative','Pending') DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dry_sow_stage`
--

CREATE TABLE `dry_sow_stage` (
  `dry_id` int(11) NOT NULL,
  `sow_id` int(11) NOT NULL,
  `weaning_date` date DEFAULT NULL,
  `heat_detection_date` date DEFAULT NULL,
  `weaning_to_estrus` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `farms`
--

CREATE TABLE `farms` (
  `farm_id` int(11) NOT NULL,
  `farm_name` varchar(100) NOT NULL,
  `owner_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `farm_address` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `farm_size` varchar(50) DEFAULT NULL,
  `farm_type` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `date_registered` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farms`
--

INSERT INTO `farms` (`farm_id`, `farm_name`, `owner_name`, `email`, `username`, `contact_number`, `farm_address`, `address`, `farm_size`, `farm_type`, `password`, `date_registered`, `status`, `remarks`) VALUES
(1, 'BTTC  FARMS', 'MICHAEL JAMES EVALLAR', 'evallarmichaeljames@gmail.com', 'michael', '09696088645', '1247 zone 6', NULL, 'Medium', 'Commercial', '$2y$10$Zv.EWIrgYsbQEloPbyMDyejBpK3ChJvaFCfiKjsNI.bJ1SQOUGcdG', '2025-10-24 02:11:48', 'Active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fattener_expenses`
--

CREATE TABLE `fattener_expenses` (
  `expense_id` int(11) NOT NULL,
  `fattener_id` int(11) DEFAULT NULL,
  `total_feed_cost` decimal(10,2) DEFAULT NULL,
  `total_health_cost` decimal(10,2) DEFAULT NULL,
  `total_expenses` decimal(10,2) GENERATED ALWAYS AS (`total_feed_cost` + `total_health_cost`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fattener_feed_consumption`
--

CREATE TABLE `fattener_feed_consumption` (
  `feed_id` int(11) NOT NULL,
  `fattener_id` int(11) DEFAULT NULL,
  `feed_type` enum('Pre-Starter','Starter','Grower','Finisher') DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `daily_intake` decimal(6,2) DEFAULT NULL,
  `total_days` int(11) GENERATED ALWAYS AS (to_days(`end_date`) - to_days(`start_date`)) STORED,
  `total_feed_consumed` decimal(8,2) GENERATED ALWAYS AS (`daily_intake` * `total_days`) STORED,
  `price_per_kg` decimal(10,2) DEFAULT NULL,
  `total_feed_cost` decimal(10,2) GENERATED ALWAYS AS (`total_feed_consumed` * `price_per_kg`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fattener_growth_record`
--

CREATE TABLE `fattener_growth_record` (
  `growth_id` int(11) NOT NULL,
  `fattener_id` int(11) DEFAULT NULL,
  `stage` enum('Weaning-Starter','Starter-Grower','Grower-Finisher','Finisher-Market') DEFAULT NULL,
  `initial_weight` decimal(6,2) DEFAULT NULL,
  `final_weight` decimal(6,2) DEFAULT NULL,
  `initial_date` date DEFAULT NULL,
  `final_date` date DEFAULT NULL,
  `feed_consumed` decimal(8,2) DEFAULT NULL,
  `days_in_stage` int(11) GENERATED ALWAYS AS (to_days(`final_date`) - to_days(`initial_date`)) STORED,
  `adg` decimal(6,3) GENERATED ALWAYS AS (case when `days_in_stage` > 0 then (`final_weight` - `initial_weight`) / `days_in_stage` else 0 end) STORED,
  `fcr` decimal(6,3) GENERATED ALWAYS AS (case when `final_weight` - `initial_weight` > 0 then `feed_consumed` / (`final_weight` - `initial_weight`) else 0 end) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fattener_health_record`
--

CREATE TABLE `fattener_health_record` (
  `health_id` int(11) NOT NULL,
  `fattener_id` int(11) DEFAULT NULL,
  `record_type` enum('Disease','Vaccination','Deworming','Vitamins') DEFAULT NULL,
  `record_date` date DEFAULT NULL,
  `stage` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `treatment` text DEFAULT NULL,
  `farm_vet` varchar(100) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fattener_records`
--

CREATE TABLE `fattener_records` (
  `fattener_id` int(11) NOT NULL,
  `farm_id` int(11) DEFAULT NULL,
  `ear_tag_no` varchar(50) DEFAULT NULL,
  `batch_no` varchar(50) DEFAULT NULL,
  `sex` enum('Male','Female') DEFAULT NULL,
  `breed_line` varchar(100) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `weaning_date` date DEFAULT NULL,
  `weaning_weight` decimal(6,2) DEFAULT NULL,
  `status` enum('Active','Market Ready','Sold','Dead') DEFAULT 'Active',
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fattener_sales_record`
--

CREATE TABLE `fattener_sales_record` (
  `sale_id` int(11) NOT NULL,
  `fattener_id` int(11) DEFAULT NULL,
  `sale_date` date DEFAULT NULL,
  `market_weight` decimal(6,2) DEFAULT NULL,
  `price_per_kg` decimal(10,2) DEFAULT NULL,
  `total_revenue` decimal(10,2) GENERATED ALWAYS AS (`market_weight` * `price_per_kg`) STORED,
  `total_feed_cost` decimal(10,2) DEFAULT NULL,
  `total_health_cost` decimal(10,2) DEFAULT NULL,
  `total_expenses` decimal(10,2) GENERATED ALWAYS AS (`total_feed_cost` + `total_health_cost`) STORED,
  `net_profit` decimal(10,2) GENERATED ALWAYS AS (`total_revenue` - `total_expenses`) STORED,
  `buyer_name` varchar(100) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gestating_stage`
--

CREATE TABLE `gestating_stage` (
  `gest_id` int(11) NOT NULL,
  `sow_id` int(11) NOT NULL,
  `breeding_date` date DEFAULT NULL,
  `ai_id` int(11) DEFAULT NULL,
  `expected_farrowing_date` date DEFAULT NULL,
  `body_condition_score` decimal(3,1) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gilt_stage`
--

CREATE TABLE `gilt_stage` (
  `gilt_id` int(11) NOT NULL,
  `sow_id` int(11) NOT NULL,
  `heat_detection_date` date DEFAULT NULL,
  `breeding_date` date DEFAULT NULL,
  `service_type` enum('AI','Natural Service') DEFAULT NULL,
  `boar_source` varchar(100) DEFAULT NULL,
  `technician` varchar(100) DEFAULT NULL,
  `heat_notes` text DEFAULT NULL,
  `pregnancy_check_date` date DEFAULT NULL,
  `pregnancy_result` enum('Positive','Negative','Pending') DEFAULT NULL,
  `rebreeding_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lactating_stage`
--

CREATE TABLE `lactating_stage` (
  `lact_id` int(11) NOT NULL,
  `sow_id` int(11) NOT NULL,
  `farrowing_date` date DEFAULT NULL,
  `avg_birth_weight` decimal(6,2) DEFAULT NULL,
  `total_piglets_born` int(11) DEFAULT NULL,
  `stillborn` int(11) DEFAULT NULL,
  `mummified` int(11) DEFAULT NULL,
  `piglets_alive` int(11) DEFAULT NULL,
  `piglets_weaned` int(11) DEFAULT NULL,
  `avg_weaning_weight` decimal(6,2) DEFAULT NULL,
  `survival_rate` decimal(5,2) DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sows`
--

CREATE TABLE `sows` (
  `sow_id` int(11) NOT NULL,
  `farm_id` int(11) DEFAULT NULL,
  `ear_tag_no` varchar(50) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `breed_line` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `selection_date` date DEFAULT NULL,
  `weight_at_selection` decimal(6,2) DEFAULT NULL,
  `source` enum('Purchased','Farm-bred') DEFAULT NULL,
  `boar_source` varchar(100) DEFAULT NULL,
  `sow_source` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` enum('Gilt','Gestating','Lactating','Dry','Culled') DEFAULT 'Gilt',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sow_exit_record`
--

CREATE TABLE `sow_exit_record` (
  `exit_id` int(11) NOT NULL,
  `sow_id` int(11) NOT NULL,
  `culling_date` date DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `last_parity_summary` text DEFAULT NULL,
  `final_weight` decimal(6,2) DEFAULT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `health_condition` text DEFAULT NULL,
  `culling_type` enum('Sold','Died','Culled') DEFAULT NULL,
  `disposal_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sow_expenses`
--

CREATE TABLE `sow_expenses` (
  `expense_id` int(11) NOT NULL,
  `sow_id` int(11) NOT NULL,
  `feed_cost` decimal(10,2) DEFAULT NULL,
  `health_cost` decimal(10,2) DEFAULT NULL,
  `total_expense` decimal(10,2) GENERATED ALWAYS AS (`feed_cost` + `health_cost`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sow_feed_consumption`
--

CREATE TABLE `sow_feed_consumption` (
  `feed_id` int(11) NOT NULL,
  `sow_id` int(11) NOT NULL,
  `stage` varchar(50) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `type_of_feed` varchar(100) DEFAULT NULL,
  `daily_intake` decimal(6,2) DEFAULT NULL,
  `total_days` int(11) GENERATED ALWAYS AS (to_days(`end_date`) - to_days(`start_date`)) STORED,
  `total_feed_consumed` decimal(8,2) GENERATED ALWAYS AS (`daily_intake` * `total_days`) STORED,
  `price_per_kg` decimal(10,2) DEFAULT NULL,
  `total_feed_cost` decimal(10,2) GENERATED ALWAYS AS (`total_feed_consumed` * `price_per_kg`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sow_health_record`
--

CREATE TABLE `sow_health_record` (
  `health_id` int(11) NOT NULL,
  `sow_id` int(11) NOT NULL,
  `record_type` enum('Disease','Vaccination','Deworming','Vitamins') DEFAULT NULL,
  `record_date` date DEFAULT NULL,
  `stage` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `treatment` text DEFAULT NULL,
  `farm_vet` varchar(100) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `farm_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `civil_status` varchar(50) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `home_address` text DEFAULT NULL,
  `tin_number` varchar(50) DEFAULT NULL,
  `sss_number` varchar(50) DEFAULT NULL,
  `philhealth_number` varchar(50) DEFAULT NULL,
  `pagibig_number` varchar(50) DEFAULT NULL,
  `emergency_contact_name` varchar(100) DEFAULT NULL,
  `emergency_contact_number` varchar(20) DEFAULT NULL,
  `emergency_address` text DEFAULT NULL,
  `medical_conditions` text DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `date_registered` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `farm_id`, `full_name`, `date_of_birth`, `gender`, `civil_status`, `nationality`, `contact_number`, `email`, `home_address`, `tin_number`, `sss_number`, `philhealth_number`, `pagibig_number`, `emergency_contact_name`, `emergency_contact_number`, `emergency_address`, `medical_conditions`, `username`, `password`, `position`, `profile_picture`, `date_registered`, `status`, `remarks`) VALUES
(1, 1, 'michael james iglesia Evallar', '2003-03-19', 'Male', 'Married', 'Filipino', '09696088645', 'evallarmichaeljames@gmail.com', '1247 zone 6', NULL, NULL, NULL, NULL, 'michael james iglesia Evallar', '09696088645', '1247 zone 6', '', 'michael', '$2y$10$QPTP3UEH0ubFNxSFppWuT.7b.i92eoGEbPQCtc5owP.qL4GY4Gos2', 'Farm Owner', '1761278947_Add a heading.png', '2025-10-24 04:09:07', 'Active', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ai_attempt`
--
ALTER TABLE `ai_attempt`
  ADD PRIMARY KEY (`ai_id`),
  ADD KEY `sow_id` (`sow_id`);

--
-- Indexes for table `dry_sow_stage`
--
ALTER TABLE `dry_sow_stage`
  ADD PRIMARY KEY (`dry_id`),
  ADD KEY `sow_id` (`sow_id`);

--
-- Indexes for table `farms`
--
ALTER TABLE `farms`
  ADD PRIMARY KEY (`farm_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `fattener_expenses`
--
ALTER TABLE `fattener_expenses`
  ADD PRIMARY KEY (`expense_id`),
  ADD KEY `fattener_id` (`fattener_id`);

--
-- Indexes for table `fattener_feed_consumption`
--
ALTER TABLE `fattener_feed_consumption`
  ADD PRIMARY KEY (`feed_id`),
  ADD KEY `fattener_id` (`fattener_id`);

--
-- Indexes for table `fattener_growth_record`
--
ALTER TABLE `fattener_growth_record`
  ADD PRIMARY KEY (`growth_id`),
  ADD KEY `fattener_id` (`fattener_id`);

--
-- Indexes for table `fattener_health_record`
--
ALTER TABLE `fattener_health_record`
  ADD PRIMARY KEY (`health_id`),
  ADD KEY `fattener_id` (`fattener_id`);

--
-- Indexes for table `fattener_records`
--
ALTER TABLE `fattener_records`
  ADD PRIMARY KEY (`fattener_id`),
  ADD KEY `farm_id` (`farm_id`);

--
-- Indexes for table `fattener_sales_record`
--
ALTER TABLE `fattener_sales_record`
  ADD PRIMARY KEY (`sale_id`),
  ADD KEY `fattener_id` (`fattener_id`);

--
-- Indexes for table `gestating_stage`
--
ALTER TABLE `gestating_stage`
  ADD PRIMARY KEY (`gest_id`),
  ADD KEY `sow_id` (`sow_id`);

--
-- Indexes for table `gilt_stage`
--
ALTER TABLE `gilt_stage`
  ADD PRIMARY KEY (`gilt_id`),
  ADD KEY `sow_id` (`sow_id`);

--
-- Indexes for table `lactating_stage`
--
ALTER TABLE `lactating_stage`
  ADD PRIMARY KEY (`lact_id`),
  ADD KEY `sow_id` (`sow_id`);

--
-- Indexes for table `sows`
--
ALTER TABLE `sows`
  ADD PRIMARY KEY (`sow_id`),
  ADD KEY `farm_id` (`farm_id`);

--
-- Indexes for table `sow_exit_record`
--
ALTER TABLE `sow_exit_record`
  ADD PRIMARY KEY (`exit_id`),
  ADD KEY `sow_id` (`sow_id`);

--
-- Indexes for table `sow_expenses`
--
ALTER TABLE `sow_expenses`
  ADD PRIMARY KEY (`expense_id`),
  ADD KEY `sow_id` (`sow_id`);

--
-- Indexes for table `sow_feed_consumption`
--
ALTER TABLE `sow_feed_consumption`
  ADD PRIMARY KEY (`feed_id`),
  ADD KEY `sow_id` (`sow_id`);

--
-- Indexes for table `sow_health_record`
--
ALTER TABLE `sow_health_record`
  ADD PRIMARY KEY (`health_id`),
  ADD KEY `sow_id` (`sow_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `farm_id` (`farm_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ai_attempt`
--
ALTER TABLE `ai_attempt`
  MODIFY `ai_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dry_sow_stage`
--
ALTER TABLE `dry_sow_stage`
  MODIFY `dry_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `farms`
--
ALTER TABLE `farms`
  MODIFY `farm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fattener_expenses`
--
ALTER TABLE `fattener_expenses`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fattener_feed_consumption`
--
ALTER TABLE `fattener_feed_consumption`
  MODIFY `feed_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fattener_growth_record`
--
ALTER TABLE `fattener_growth_record`
  MODIFY `growth_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fattener_health_record`
--
ALTER TABLE `fattener_health_record`
  MODIFY `health_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fattener_records`
--
ALTER TABLE `fattener_records`
  MODIFY `fattener_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fattener_sales_record`
--
ALTER TABLE `fattener_sales_record`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gestating_stage`
--
ALTER TABLE `gestating_stage`
  MODIFY `gest_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gilt_stage`
--
ALTER TABLE `gilt_stage`
  MODIFY `gilt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lactating_stage`
--
ALTER TABLE `lactating_stage`
  MODIFY `lact_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sows`
--
ALTER TABLE `sows`
  MODIFY `sow_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sow_exit_record`
--
ALTER TABLE `sow_exit_record`
  MODIFY `exit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sow_expenses`
--
ALTER TABLE `sow_expenses`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sow_feed_consumption`
--
ALTER TABLE `sow_feed_consumption`
  MODIFY `feed_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sow_health_record`
--
ALTER TABLE `sow_health_record`
  MODIFY `health_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ai_attempt`
--
ALTER TABLE `ai_attempt`
  ADD CONSTRAINT `ai_attempt_ibfk_1` FOREIGN KEY (`sow_id`) REFERENCES `sows` (`sow_id`) ON DELETE CASCADE;

--
-- Constraints for table `dry_sow_stage`
--
ALTER TABLE `dry_sow_stage`
  ADD CONSTRAINT `dry_sow_stage_ibfk_1` FOREIGN KEY (`sow_id`) REFERENCES `sows` (`sow_id`) ON DELETE CASCADE;

--
-- Constraints for table `fattener_expenses`
--
ALTER TABLE `fattener_expenses`
  ADD CONSTRAINT `fattener_expenses_ibfk_1` FOREIGN KEY (`fattener_id`) REFERENCES `fattener_records` (`fattener_id`) ON DELETE CASCADE;

--
-- Constraints for table `fattener_feed_consumption`
--
ALTER TABLE `fattener_feed_consumption`
  ADD CONSTRAINT `fattener_feed_consumption_ibfk_1` FOREIGN KEY (`fattener_id`) REFERENCES `fattener_records` (`fattener_id`) ON DELETE CASCADE;

--
-- Constraints for table `fattener_growth_record`
--
ALTER TABLE `fattener_growth_record`
  ADD CONSTRAINT `fattener_growth_record_ibfk_1` FOREIGN KEY (`fattener_id`) REFERENCES `fattener_records` (`fattener_id`) ON DELETE CASCADE;

--
-- Constraints for table `fattener_health_record`
--
ALTER TABLE `fattener_health_record`
  ADD CONSTRAINT `fattener_health_record_ibfk_1` FOREIGN KEY (`fattener_id`) REFERENCES `fattener_records` (`fattener_id`) ON DELETE CASCADE;

--
-- Constraints for table `fattener_records`
--
ALTER TABLE `fattener_records`
  ADD CONSTRAINT `fattener_records_ibfk_1` FOREIGN KEY (`farm_id`) REFERENCES `farms` (`farm_id`) ON DELETE CASCADE;

--
-- Constraints for table `fattener_sales_record`
--
ALTER TABLE `fattener_sales_record`
  ADD CONSTRAINT `fattener_sales_record_ibfk_1` FOREIGN KEY (`fattener_id`) REFERENCES `fattener_records` (`fattener_id`) ON DELETE CASCADE;

--
-- Constraints for table `gestating_stage`
--
ALTER TABLE `gestating_stage`
  ADD CONSTRAINT `gestating_stage_ibfk_1` FOREIGN KEY (`sow_id`) REFERENCES `sows` (`sow_id`) ON DELETE CASCADE;

--
-- Constraints for table `gilt_stage`
--
ALTER TABLE `gilt_stage`
  ADD CONSTRAINT `gilt_stage_ibfk_1` FOREIGN KEY (`sow_id`) REFERENCES `sows` (`sow_id`) ON DELETE CASCADE;

--
-- Constraints for table `lactating_stage`
--
ALTER TABLE `lactating_stage`
  ADD CONSTRAINT `lactating_stage_ibfk_1` FOREIGN KEY (`sow_id`) REFERENCES `sows` (`sow_id`) ON DELETE CASCADE;

--
-- Constraints for table `sows`
--
ALTER TABLE `sows`
  ADD CONSTRAINT `sows_ibfk_1` FOREIGN KEY (`farm_id`) REFERENCES `farms` (`farm_id`) ON DELETE CASCADE;

--
-- Constraints for table `sow_exit_record`
--
ALTER TABLE `sow_exit_record`
  ADD CONSTRAINT `sow_exit_record_ibfk_1` FOREIGN KEY (`sow_id`) REFERENCES `sows` (`sow_id`) ON DELETE CASCADE;

--
-- Constraints for table `sow_expenses`
--
ALTER TABLE `sow_expenses`
  ADD CONSTRAINT `sow_expenses_ibfk_1` FOREIGN KEY (`sow_id`) REFERENCES `sows` (`sow_id`) ON DELETE CASCADE;

--
-- Constraints for table `sow_feed_consumption`
--
ALTER TABLE `sow_feed_consumption`
  ADD CONSTRAINT `sow_feed_consumption_ibfk_1` FOREIGN KEY (`sow_id`) REFERENCES `sows` (`sow_id`) ON DELETE CASCADE;

--
-- Constraints for table `sow_health_record`
--
ALTER TABLE `sow_health_record`
  ADD CONSTRAINT `sow_health_record_ibfk_1` FOREIGN KEY (`sow_id`) REFERENCES `sows` (`sow_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`farm_id`) REFERENCES `farms` (`farm_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
