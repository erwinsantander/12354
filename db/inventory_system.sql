-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2024 at 04:39 AM
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
-- Database: `inventory_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(15, 'Alcoholic Drinks'),
(6, 'Cans'),
(4, 'Junkfoods'),
(2, 'Milk'),
(3, 'Rice'),
(1, 'Softdrinks');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `barcode` varchar(100) NOT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `buy_price` decimal(25,2) DEFAULT NULL,
  `sale_price` decimal(25,2) NOT NULL,
  `categorie_id` int(11) UNSIGNED NOT NULL,
  `media_id` int(11) DEFAULT 0,
  `date` datetime NOT NULL,
  `expiration_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `barcode`, `quantity`, `buy_price`, `sale_price`, `categorie_id`, `media_id`, `date`, `expiration_date`) VALUES
(15, 'Super Crunch', '', '1000', 10.00, 12.00, 4, 0, '2024-11-30 08:26:15', '2024-12-31'),
(16, 'Bear Brand', '', '145', 10.00, 12.00, 2, 0, '2024-11-30 08:27:42', '2024-12-28'),
(17, 'Princess Mia 5kls', '', '100', 280.00, 300.00, 3, 0, '2024-11-30 08:28:43', '2025-02-28'),
(18, 'Grandeur 5kls', '', '77', 280.00, 300.00, 3, 0, '2024-11-30 08:29:41', '2024-12-12'),
(19, 'Lion Ivory 50kls', '', '45', 2900.00, 3000.00, 3, 0, '2024-11-30 08:30:45', '2025-01-15'),
(20, 'Broken Rice 50kls', '', '45', 2400.00, 2500.00, 3, 0, '2024-11-30 08:32:18', '2024-12-28'),
(21, 'Mobi', '', '167', 7.00, 8.00, 4, 0, '2024-11-30 08:33:19', '2024-12-20'),
(22, 'Fish Crackers', '', '157', 7.00, 8.00, 4, 0, '2024-11-30 08:33:59', '2024-12-20'),
(23, 'Family Sardines', '', '77', 24.00, 26.00, 6, 0, '2024-11-30 08:35:11', '2024-12-13'),
(24, 'Holiday Beefloaf', '', '234', 21.00, 24.00, 6, 0, '2024-11-30 08:36:55', '2024-12-13'),
(25, 'Tanduay Select', '', '123', 115.00, 125.00, 15, 0, '2024-11-30 22:58:24', '2025-05-30'),
(26, 'Red Horse', '', '134', 115.00, 120.00, 15, 0, '2024-11-30 22:59:24', '2025-11-20'),
(27, 'Cream All Purpose', '4806516763639', '100', 60.00, 70.00, 2, 0, '2024-12-01 02:40:31', '2024-12-07'),
(29, 'Carne Norte', '4800066122519', '100', 35.00, 38.00, 6, 0, '2024-12-01 03:14:08', '2026-10-21'),
(30, 'Family Sardines Brand', '4806503244080', '100', 25.00, 26.00, 6, 0, '2024-12-01 03:15:26', '2026-07-17'),
(31, 'Argentina Cornbeef ', '748485800431', '100', 25.00, 30.00, 6, 0, '2024-12-01 03:17:50', '2027-07-20'),
(32, 'American Corn Beef', '4806508628519', '100', 25.00, 30.00, 6, 0, '2024-12-01 03:19:25', '2026-12-12');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `product_id`, `qty`, `price`, `date`) VALUES
(10, 16, 5, 60.00, '2024-11-30'),
(11, 15, 995, 11940.00, '2024-11-30'),
(12, 17, 100, 30000.00, '2024-11-30'),
(13, 18, 77, 23100.00, '2024-11-30'),
(14, 19, 45, 135000.00, '2024-11-30'),
(15, 20, 45, 112500.00, '2024-11-30'),
(16, 21, 157, 1256.00, '2024-11-30'),
(17, 22, 157, 1256.00, '2024-11-30'),
(18, 23, 77, 2002.00, '2024-11-30'),
(19, 24, 234, 5616.00, '2024-11-30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `email` varchar(100) NOT NULL,
  `status` int(1) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `code` varchar(100) NOT NULL,
  `verified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `user_level`, `image`, `email`, `status`, `last_login`, `code`, `verified`) VALUES
(1, 'Erwin Santander', 'Erwin', '931ed15601fb709207f2b569e97a41cc7860e565', 1, '8umq2ct1.jpeg', '', 1, '2024-12-05 04:38:52', '', 0),
(2, 'Manuel Jarina', 'Manuel', '25b99bacd5f00970e7f5003b4463b2456c22f73c', 2, '7g7f4u22.jpg', '', 1, '2024-12-01 04:53:02', '', 0),
(3, 'John Carlo Jagdon', 'jagdon', '2494361cb50f4d4c87c70472caa05c4d9b2e616a', 3, 'aot1c1l93.jpeg', '', 1, '2024-12-01 01:10:46', '', 0),
(5, 'Kevin', 'Kevin', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 3, 'no_image.png', '', 1, '2021-04-04 19:54:29', '', 0),
(6, '233', '', '$2y$10$kVHXMIcKW1ddfS1TCH1dC.LfM033JIa1vreZhIy83jCR02GIhXvou', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '0329bd034ca93e23a0e0bb632730bae8', 0),
(7, 'james', '', '$2y$10$fZgvgqZgGxtwEcPAsJ1C3OmNAHUNXt0zFUoSn2WvfR0FDU5RJZYu.', 2, 'no_image.jpg', '123@gmail.com', 0, NULL, '05f46d45deb22a3f4f91259ad771d214', 0),
(8, '123', '33333', '$2y$10$U1bWUeAuKLGnSZc/kIj1ceNRLQb9lH6M1z41Z7lsoRcq/NzksOgiO', 2, 'no_image.jpg', '123@gmail', 0, NULL, '180a1e1b95004e54735e6ac8e2f57538', 0),
(9, '123', '', '$2y$10$9jLXdEWsndlxsVRwiP42I.bnCsAzP54h9vDSputj96BWEXMYbS/GK', 2, 'no_image.jpg', '123@gmail.com', 0, NULL, 'a53277ebb1adf1962ab21ab16fb729b5', 0),
(10, '123', '', '$2y$10$S8gxDyxTiUswHVd4OX9cke8yDKa/GM0BierVOUVxXLAJlZlUstTg2', 2, 'no_image.jpg', '123@gmail.com', 0, NULL, '1cb9663eae0bf1f1e26d56e5dac718bb', 0),
(11, '13', '', '$2y$10$K2rC.FB6XgoRCZ6DnxO21uZUYr/6gWZFQ6T3hXMJUa.H2qGGz0Nzm', 2, 'no_image.jpg', '123@gmail.com', 0, NULL, '02be79a461d289c0067805877ddf4301', 0),
(12, 'hoh', '', '$2y$10$avXUMTA/fjSNHpV7nWqaruVFX2a/JvraaJFemVtI8iU9Rm57TeH0K', 2, 'no_image.jpg', 'admin@pass.com', 0, NULL, '00818', 0),
(13, '123', '', '$2y$10$NsRZBLWYL/hYmsAUN4rPp.Xm4oySJBDZ8EPW9OmzTIFtKrNql/CRa', 2, 'no_image.jpg', 'johnchristian@gmail.com', 0, NULL, '46520', 0),
(14, '123', '', '$2y$10$n5gdpYP1iBHEgmW5hvrU0etnKBA8Pc0hK1Bm5/G0MA2exHT369o0q', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '83047', 0),
(15, '123', '', '$2y$10$a0jJEpH7VzoQc2jRiGea0.gJTk4kU7iiKdVBJudiYh3zdQ4uNwsYS', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '65229', 0),
(16, '123', '', '$2y$10$uzB0GwNJcRVCmqK7TRuIv.A8nCWUBs75fpN0sgV9yyznCPDHtoo46', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '65652', 0),
(17, '123', '', '$2y$10$UHLYJrobVg59j2EHWys8OuWRWNIb6HrOnA4MvgPqGvWMO6bZ1OUdS', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '96893', 0),
(18, '123', 'uu', '$2y$10$P0IwpUN5DcqFs6btJwvyeuFUZOY57qZoy5/k..6mGa1OtrLzGkuCS', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '03137', 0),
(19, '123', 'uu', '$2y$10$uOCHo7DZE8R.5btjpwdmmulJ0n9udbcvD61w5dREHSOmYHqzJ0A.a', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '00592', 0),
(20, '123', 'uu', '$2y$10$06p1c3mlYJ2DyvbZpZn2X.raBkFw5GDi/yBjzIvBM404x7of.PZG2', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '30521', 0),
(21, '123', 'uu', '$2y$10$Yp5Fff4yY4GNqJwBTf56K.HHABXVP/vp7Bn96.vSniA564WDn6E1a', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '07121', 0),
(22, '123', 'uu', '$2y$10$843to0fWPReNEpLMR2o3pOXJqqDvFC22YXQNy/vnmE7Ebi2sMuyW.', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '12642', 0),
(23, '123', 'uu', '$2y$10$Z4hnz5XvUmTtT1YFk8TPaegvUr8FfxD69Reh5TwEtrRfikE8swZRG', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '46933', 0),
(24, '123', 'uu', '$2y$10$XDrOTH17P8qywU5FGrAem.EYGIHf1jUG.kgZqfYEnpNMUqvRBWRhq', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '62952', 0),
(25, '123', 'uu', '$2y$10$UYPFzHmB7rEm5Z3KwP2B6etjRs5yCkeblTBqmfKEhhUnvJ.vB5rjS', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '22519', 0),
(26, '123', 'uu', '$2y$10$WaTDV/rcn7nfBHe2stz78uuYpkAj/uZsTKFOsgxCjYYYIlRbyT1Be', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '40346', 0),
(27, '123', 'uu', '$2y$10$XME4c4jS/b46IFGWtG6wIu8nMj3UV3MWDbVdEcF8037UxQpbIbI1i', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '69352', 0),
(28, '123', 'uu', '$2y$10$mTCERTF95SlDIk/f/SQxKeq5YuSyk35xM1emQ262AWHD.KTQfH0Dq', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '45010', 0),
(29, '123', 'uu', '$2y$10$ioqhUhxoUYzh6lK3Wucpeu9adn5qgb6/DO/YgEmJ7TCL001GMIuau', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '11010', 0),
(30, '123', 'uu', '$2y$10$BGaF6ORmXCzg0jwen4ubq.D5iR/AZZMMiqtDHtr/ofg6ABNpkwieO', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '64234', 0),
(31, '123', 'uu', '$2y$10$ks0MTq6ilbz86DTTARmwnOD27G4.dRcNz0SCaodVbJIivF.Ak8F22', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '08002', 0),
(32, '123', 'uu', '$2y$10$Totlkwhz7pHSuJ5OZEuy/O6xGT4SQNtkllXV0GIsyrbmE3UDxPbXi', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '18615', 0),
(33, '123', 'uu', '$2y$10$N/xf.JiBfepeaqsaYU1tN.ZESYhx3K9Dpceel1tM1exzSNMvuvWIm', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '88392', 0),
(34, '123', 'uu', '$2y$10$gtoatweQyIH8tysD.dF5fe3lHCdRnPPY92MgVDOvEQjutODn6rGDq', 2, 'no_image.jpg', 'johnchristianfariola@gmail.com', 0, NULL, '04609', 0),
(35, '123', '213', '$2y$10$8JtIqgNazyzCQcLKquz4qOQsFxLMZ67Fh9CPBLuwgZQTZ49rwXfkC', 2, 'no_image.jpg', 'johnhriat2@gmail.com', 0, NULL, '50174', 0),
(36, '123', '213', '$2y$10$IOwjOBye9iuZ.n2bQE0xKOqKmDXCZnfknKo0ITpfjuyKIvqa3VHQO', 2, 'no_image.jpg', 'johnhriat2@gmail.com', 0, NULL, '85407', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `group_name`, `group_level`, `group_status`) VALUES
(1, 'Admin', 1, 1),
(2, 'Cashier', 2, 1),
(3, 'User', 3, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `categorie_id` (`categorie_id`),
  ADD KEY `media_id` (`media_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_level` (`user_level`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_level` (`group_level`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_products` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `SK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_user` FOREIGN KEY (`user_level`) REFERENCES `user_groups` (`group_level`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
