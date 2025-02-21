-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2024 at 04:56 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vrtxdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `playlists`
--

CREATE TABLE `playlists` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tracks`
--

CREATE TABLE `tracks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `artist` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `cover` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tracks`
--

INSERT INTO `tracks` (`id`, `name`, `artist`, `path`, `cover`) VALUES
(6, 'Diamond Eyes', 'Deftones', 'audio/Deftones - Diamond Eyes.mp3', 'cover/Diamond Eyes.jpg'),
(7, 'Ma Meilleure Ennemie', 'Arcane League of Legends', 'audio/Ma Meilleure Ennemie (from the series Arcane League of Legends).mp3', 'cover/Arcane.jpg'),
(8, 'One Hundred Sleepless Nights', 'Pierce The Veil', 'audio/Pierce The Veil - One Hundred Sleepless Nights.mp3', 'cover/Collide With The Sky.jpg'),
(9, 'My Mistakes Were Made For You', 'The Last Shadow Puppets', 'audio/The Last Shadow Puppets - My Mistakes Were Made For You.mp3', 'cover/My Mistakes Were Made For You.jpg'),
(12, 'Again', 'Noah Cyrus', 'audio/Noah Cyrus - Again.mp3', 'cover/Again.jpg'),
(15, 'Last Nite', 'The Strokes', 'audio/The Strokes - Last Nite.mp3', 'cover/Last Nite.jpg'),
(16, 'Money', 'The Drums', 'audio/The Drums - Money.mp3', 'cover/Money.jpg'),
(17, 'Somebody That You Used To Know', 'Gotye', 'audio/Gotye.mp3', 'cover/Gotye.jpg'),
(18, 'Neva Play', 'Megan Thee Stallion ft RM', 'audio/Neva Play.mp3', 'cover/Neva Play.jpg'),
(19, 'Needed Me', 'Rihanna', 'audio/Needed Me.mp3', 'cover/Needed Me.jpg'),
(20, 'Alright', 'Kendrick Lamar', 'audio/Alright.mp3', 'cover/Kendrick Lamar.jpg'),
(21, 'Get Away', 'The Internet', 'audio/Get Away.mp3', 'cover/The Internet.jpg'),
(22, 'Going Under', 'Evanescence', 'audio/Going Under.mp3', 'cover/Evanescence.jpg'),
(23, 'Party Monster', 'The Weeknd', 'audio/Party Monster.mp3', 'cover/The Weeknd.jpg'),
(24, 'The Ghost of You', 'My Chemical Romance', 'audio/The Ghost of You.mp3', 'cover/Chemical Romance.jpg'),
(25, '&burn', 'Billie Eilish with Vince Staples', 'audio/&burn.mp3', 'cover/Billie Eilish.jpg'),
(26, 'Crossing Field', 'LiSA', 'audio/Crossing Field.mp3', 'cover/LiSA.jpg'),
(27, 'Just Wanna Rock', 'Lil Uzi Vert', 'audio/Just Wanna Rock.mp3', 'cover/Lil Uzi Vert.jpg'),
(28, 'Cheating is a Crime', 'Takayan', 'audio/Cheating is  a Crime.mp3', 'cover/Takayan.jpg'),
(29, 'Pastel', 'Whee In', 'audio/Pastel.mp3', 'cover/Whee In.jpg'),
(31, 'Squabble Up', 'Kendrick Lamar', 'songs/Squabble Up.mp3', 'covers/Kendrick Lamar2.jpg'),
(34, 'Bomb Bomb', 'Kard', 'songs/Bomb Bomb.mp3', 'covers/Kard2.jpg'),
(36, 'GUNSHOT', 'Kard', 'songs/Gunshot.mp3', 'covers/Kard1.jpg'),
(38, 'She\'s Thunderstorms', 'Arctic Monkeys', 'songs/Arctic Monkeys - She-\'s Thunderstorms.mp3', 'covers/Suck It and See.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `userName` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dateMade` timestamp NOT NULL DEFAULT current_timestamp(),
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `userName`, `password`, `email`, `dateMade`, `name`) VALUES
(7, 'affogattoast', '$2y$10$wySnIBVujvERRE3Y5XlM3.rqxo8rtK2oJXI1ROi4kduc1rsZK1BCC', 'andreamndz101@gmail.com', '2024-11-24 21:36:30', 'Drea'),
(12, 'dummyummy', '$2y$10$SKQ/uDpRpzLgputKR.MUleE./0uqFJFGaTgJk2bezoUYNVyKx2ClS', 'dummy@gmail.com', '2024-12-10 19:08:42', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `playlists`
--
ALTER TABLE `playlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tracks`
--
ALTER TABLE `tracks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userName` (`userName`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `playlists`
--
ALTER TABLE `playlists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracks`
--
ALTER TABLE `tracks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `playlists`
--
ALTER TABLE `playlists`
  ADD CONSTRAINT `playlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
