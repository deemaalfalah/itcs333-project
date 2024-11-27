-- Create the `rooms` table with an auto-increment primary key for `room_id` and a unique constraint on `room_num`
CREATE TABLE `rooms` (
  `room_id` INT(11) NOT NULL AUTO_INCREMENT,  -- Added an auto-increment column
  `department` VARCHAR(255) NOT NULL,
  `room_num` INT(255) NOT NULL,
  `capacity` INT(255) NOT NULL,
  `lab` TINYINT(1) NOT NULL,
  `smartboard` TINYINT(1) NOT NULL,
  `datashow` TINYINT(1) NOT NULL,
  PRIMARY KEY (`room_id`),  -- Set the primary key to the auto-increment column
  UNIQUE KEY `room_num_unique` (`room_num`)  -- Ensure that room_num is unique
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `transaction`
CREATE TABLE `transaction` (
  `record_id` INT(11) NOT NULL AUTO_INCREMENT, -- Use backticks and specify size for INT
  `userid` INT(9) NOT NULL,
  `room_num` INT(10) NOT NULL, -- Use backticks and remove hyphen (use underscores for column names)
  `semester` VARCHAR(50) NOT NULL,
  `start_date` DATE NOT NULL, -- Use underscores for column names
  `end_date` DATE NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  PRIMARY KEY (`record_id`) -- Primary key for `record_id`
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Table structure for table `users`
CREATE TABLE `users` (
  `username` VARCHAR(50) NOT NULL,
  `userid` INT(9) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `usertype` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`userid`)  -- Primary key for the `userid` column
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data for users table
INSERT INTO `users` (`username`, `userid`, `password`, `email`, `usertype`) VALUES
('bayan', 76767676, '$2y$10$Krkqyf9cCd3Sscb5xIxl0udtg7kN8NnbsXuT.9gN5SZArHP9arzV6', 'bayan@gmail.com', 'admin');
