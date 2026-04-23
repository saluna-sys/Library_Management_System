-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 18, 2026 at 09:25 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms2`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminid` int(11) NOT NULL,
  `username` varchar(111) NOT NULL,
  `fullname` varchar(111) NOT NULL,
  `adminemail` varchar(111) NOT NULL,
  `password` varchar(111) NOT NULL,
  `pic` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminid`, `username`, `fullname`, `adminemail`, `password`, `pic`) VALUES
(1, 'admin', 'Tahmid', 'tahmid@gmail.com', 'admin', 'dipen.jpg'),
(2, 'admin', 'admin', 'admin@gmail.com', 'admin', 'dipen.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `authorid` int(11) NOT NULL,
  `authorname` varchar(111) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`authorid`, `authorname`) VALUES
(11, 'Anthony Brun'),
(14, 'E. Balagurusamy'),
(15, 'M. Morris Mano'),
(16, 'George B. Thomas');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `bookid` int(11) NOT NULL,
  `bookpic` varchar(500) DEFAULT NULL,
  `bookname` varchar(100) DEFAULT NULL,
  `authorid` int(11) DEFAULT NULL,
  `categoryid` int(11) DEFAULT NULL,
  `ISBN` varchar(100) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`bookid`, `bookpic`, `bookname`, `authorid`, `categoryid`, `ISBN`, `price`, `quantity`, `status`) VALUES
(20, 'cplus.jpg', 'C++', 11, 2, '27899', 250, 9, 'Available'),
(22, 'python2.jpg', 'Python Programming', 11, 2, '2456', 600, 4, 'Available'),
(24, 'dipen.jpg', 'C++!!fgh', 14, 1, '10', 200, 2, 'Available'),
(25, 'dipen.jpg', 'Digital Design', 15, 4, '9780132774208', 200, 9, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryid` int(11) NOT NULL,
  `categoryname` varchar(111) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryid`, `categoryname`) VALUES
(1, 'Science Fiction'),
(2, 'Computer Programming'),
(3, 'Novel'),
(4, 'Hardware'),
(5, 'Mathematics');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `stdid` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` varchar(1000) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `issueinfo`
--

CREATE TABLE `issueinfo` (
  `studentid` int(11) DEFAULT NULL,
  `bookid` int(11) DEFAULT NULL,
  `issuedate` date DEFAULT NULL,
  `returndate` date DEFAULT NULL,
  `approve` varchar(200) DEFAULT NULL,
  `fine` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issueinfo`
--

INSERT INTO `issueinfo` (`studentid`, `bookid`, `issuedate`, `returndate`, `approve`, `fine`, `id`) VALUES
(2, 20, '2026-04-18', '2026-04-18', 'returned', 0, 11),
(2, 22, '2026-04-17', '2026-04-17', 'returned', 0, 12),
(2, 23, '2026-04-18', '2026-05-02', 'yes', 0, 13),
(2, 24, '2026-04-18', '2026-04-18', 'returned', 0, 14),
(2, 25, '2026-04-18', '2026-04-18', 'returned', 0, 15),
(2, 24, '2026-04-18', '2026-05-02', 'yes', 0, 16),
(2, 22, NULL, NULL, 'no', 0, 17),
(2, 25, NULL, NULL, 'no', 0, 18);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `message` varchar(1000) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `sender` varchar(100) DEFAULT NULL,
  `date` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `username`, `message`, `status`, `sender`, `date`) VALUES
(1, 'testuser', 'Hello admin', 'no', 'student', '2024-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `studentid` int(11) NOT NULL,
  `student_username` varchar(111) DEFAULT NULL,
  `FullName` varchar(111) DEFAULT NULL,
  `Email` varchar(111) DEFAULT NULL,
  `Password` varchar(111) DEFAULT NULL,
  `PhoneNumber` varchar(111) DEFAULT NULL,
  `studentpic` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`studentid`, `student_username`, `FullName`, `Email`, `Password`, `PhoneNumber`, `studentpic`) VALUES
(1, 'testuser', 'Test User', 'test@gmail.com', '123', '9800000000', 'user.png'),
(2, 'saluna', 'Saluna Shrestha', 'saluna@gmail.com', '1234', '9874561231', 'ddddd.webp');

-- --------------------------------------------------------

--
-- Table structure for table `timer`
--

CREATE TABLE `timer` (
  `stdid` int(11) DEFAULT NULL,
  `bid` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timer`
--

INSERT INTO `timer` (`stdid`, `bid`, `date`) VALUES
(2, 23, '2026-05-02 08:47:00'),
(2, 24, '2026-05-02 08:55:00');

-- --------------------------------------------------------

--
-- Table structure for table `trendingbook`
--

CREATE TABLE `trendingbook` (
  `bookid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminid`);

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`authorid`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`bookid`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryid`);

--
-- Indexes for table `issueinfo`
--
ALTER TABLE `issueinfo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`studentid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `authorid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `bookid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `issueinfo`
--
ALTER TABLE `issueinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `studentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
