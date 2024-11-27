SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `room-booking`
--

-- --------------------------------------------------------

--
-- Create the `rooms` table
--

CREATE TABLE `rooms` (
    `room_id` INT(11) NOT NULL AUTO_INCREMENT,
    `department` VARCHAR(255) NOT NULL,
    `room_num` INT(255) NOT NULL,
    `capacity` INT(255) NOT NULL,
    `lab` TINYINT(1) NOT NULL,
    `smartboard` TINYINT(1) NOT NULL,
    `datashow` TINYINT(1) NOT NULL,
    `image` LONGBLOB,  -- To store image data
    PRIMARY KEY (`room_id`),
    UNIQUE KEY `room_num_unique` (`room_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `rooms`
INSERT INTO `rooms` (`room_id`, `department`, `room_num`, `capacity`, `lab`, `smartboard`, `datashow`) VALUES
(1, 'CS', 2334, 33, 1, 0, 0);

-- --------------------------------------------------------

-- 
-- Create the `transaction` table
--

CREATE TABLE `transaction` (
  `record_id` int(11) NOT NULL,
  `userid` int(9) NOT NULL,
  `room_num` int(10) NOT NULL,
  `semester` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- 
-- Create the `users` table
--

CREATE TABLE `users` (
  `username` varchar(50) NOT NULL,
  `userid` int(9) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `usertype` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `users`
INSERT INTO `users` (`username`, `userid`, `password`, `email`, `usertype`) VALUES
('bayan', 76767676, '$2y$10$Krkqyf9cCd3Sscb5xIxl0udtg7kN8NnbsXuT.9gN5SZArHP9arzV6', 'bayan@gmail.com', 'admin');

-- --------------------------------------------------------

--
-- Indexes for table `rooms` (No need to re-add the primary key as it's already set in table creation)
--

-- You do not need to add PRIMARY KEY and UNIQUE KEY again, as they are already defined during table creation.

-- --------------------------------------------------------

--
-- Indexes for table `transaction`
--

ALTER TABLE `transaction`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `fk_room_num` (`room_num`);

-- --------------------------------------------------------

--
-- Indexes for table `users`
--

ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

-- --------------------------------------------------------

-- AUTO_INCREMENT for dumped tables

-- Set AUTO_INCREMENT for table `rooms`
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- Set AUTO_INCREMENT for table `transaction`
ALTER TABLE `transaction`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

-- Constraints for dumped tables

-- Add foreign key constraint for table `transaction`
ALTER TABLE `transaction`
  ADD CONSTRAINT `fk_room_num` FOREIGN KEY (`room_num`) REFERENCES `rooms` (`room_num`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

