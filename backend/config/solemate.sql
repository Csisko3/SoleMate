-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 18. Jun 2024 um 17:49
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `solemate`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `order_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `order_id`) VALUES
(72, 32, 1, 3, NULL),
(75, 32, 3, 1, NULL),
(80, 32, 2, 1, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `amount` float NOT NULL,
  `residual_value` float NOT NULL,
  `expiration_date` date NOT NULL,
  `expired` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `amount`, `residual_value`, `expiration_date`, `expired`) VALUES
(1, 'brKT8', 123, 123, '2024-07-06', 0),
(2, 'acE0P', 123, 123, '0000-00-00', 0),
(3, 'mAhhe', 123123, 123123, '2024-07-07', 0),
(4, 'LLCmW', 12, 12, '2024-07-05', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `order_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`order_details`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `name`, `address`, `payment_method`, `order_date`, `order_details`) VALUES
(27, 32, 'asd', 'asd2134', 'credit_card', '2024-06-16 02:53:18', '[{\"product_name\":\"Adidas Yeezy Boost 350 V2\",\"product_price\":\"299.00\",\"quantity\":2},{\"product_name\":\"Nike Dunk Low Black White\",\"product_price\":\"200.00\",\"quantity\":2}]'),
(28, 32, 'ROli', 'Rolistraße', 'credit_card', '2024-06-16 02:54:30', '[{\"product_name\":\" Air Jordan 4 Retro \",\"product_price\":\"209.99\",\"quantity\":1}]'),
(29, 32, 'ROli', 'Rolistraße', 'credit_card', '2024-06-16 02:56:06', '[{\"product_name\":\"Adidas Yeezy Boost 350 V2\",\"product_price\":\"299.00\",\"quantity\":1}]'),
(30, 32, 'asd', 'asd', 'paypal', '2024-06-16 02:56:58', '[{\"product_name\":\"Nike Dunk Low Black White\",\"product_price\":\"200.00\",\"quantity\":1}]'),
(31, 32, '123', '1233123', 'credit_card', '2024-06-16 02:57:34', '[{\"product_name\":\"Nike Air Force 1 \'07 Triple White\",\"product_price\":\"149.00\",\"quantity\":1},{\"product_name\":\"Adidas Yeezy Boost 350 V2\",\"product_price\":\"299.00\",\"quantity\":1}]'),
(32, 32, 'fhT', 'Hochstädterplatz 6', 'paypal', '2024-06-16 03:00:00', '[{\"product_name\":\"Nike Air Force 1 \'07 Triple White\",\"product_price\":\"149.00\",\"quantity\":2},{\"product_name\":\"Nike Dunk Low Black White\",\"product_price\":\"200.00\",\"quantity\":2}]'),
(33, 32, 'Mr T.', 'ATeam', 'credit_card', '2024-06-16 03:22:58', '[{\"product_name\":\"Adidas Yeezy Boost 350 V2\",\"product_price\":\"299.00\",\"quantity\":3}]'),
(34, 29, 'Test', 'test', 'credit_card', '2024-06-18 15:12:19', '[{\"product_name\":\"Adidas Yeezy Boost 350 V2\",\"product_price\":\"299.00\",\"quantity\":1},{\"product_name\":\"Nike Air Force 1 \'07 Triple White\",\"product_price\":\"149.00\",\"quantity\":1}]'),
(35, 29, 'asdqw', 'asdgasdgasd', 'paypal', '2024-06-18 15:28:00', '[{\"product_name\":\"Adidas Yeezy Boost 350 V2\",\"product_price\":\"299.00\",\"quantity\":1},{\"product_name\":\"Nike Dunk Low Black White\",\"product_price\":\"200.00\",\"quantity\":1},{\"product_name\":\"Nike Air Force 1 \'07 Triple White\",\"product_price\":\"149.00\",\"quantity\":1}]'),
(36, 33, 'Martha', 'Marthagasse', 'paypal', '2024-06-18 15:36:02', '[{\"product_name\":\"Adidas Yeezy Boost 350 V2\",\"product_price\":\"299.00\",\"quantity\":2},{\"product_name\":\" Vans Old Skool \",\"product_price\":\"75.00\",\"quantity\":1}]'),
(37, 34, 'Person Person', 'Personstraße 1234', 'credit_card', '2024-06-18 17:41:15', '[{\"product_name\":\"Nike Air Force 1 \'07 Triple White\",\"product_price\":\"149.00\",\"quantity\":1},{\"product_name\":\"Adidas Yeezy Boost 350 V2\",\"product_price\":\"299.00\",\"quantity\":1}]');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `products`
--

CREATE TABLE `products` (
  `ID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `picture` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `products`
--

INSERT INTO `products` (`ID`, `name`, `price`, `category`, `picture`) VALUES
(1, 'Nike Air Force 1 \'07 Triple White', '169.00', 'Nike', 'air-force-1.png'),
(2, 'Adidas Yeezy Boost 350 V2', '299.00', 'Adiddas', 'yeezy-350.jpeg'),
(3, 'Nike Dunk Low Black White', '200.00', 'Nike', 'nike-dunk.png'),
(4, 'Air Jordan 4 Military Black', '399.00', 'Jordan', 'jordan-4-military.jpg'),
(6, 'Adidas Campus 00 Grey White', '159.00', 'Addidas', 'adidas-campus.JPG'),
(25, ' Air Jordan 4 Retro ', '209.99', 'Jordan', '2332306_P.jpg'),
(26, ' Vans Old Skool ', '75.00', 'Vans', 'vans-old-skool-suede-canvas-black-true-white.jpg');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `ID` int(10) NOT NULL,
  `gender` int(1) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `adress` varchar(50) NOT NULL,
  `postcode` int(6) NOT NULL,
  `city` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `payment_info` varchar(50) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`ID`, `gender`, `firstname`, `lastname`, `adress`, `postcode`, `city`, `email`, `username`, `password`, `payment_info`, `is_admin`, `is_active`) VALUES
(29, 0, 'Test', 'Test', 'Testgasse 123', 1233664, 'Test', 'test@gmail.com', 'test', '$2y$10$efW4jgpeBXahe54Ke.rrK.72VEQczeCdAPEGg4p6MpiDJxM01DWq.', 'SEPA_Lastschrift', 0, 1),
(30, 2, 'admin', 'admin', 'admingasse', 1234, 'Admin', 'admin@gmail.com', 'admin', '$2y$10$xCijyjRuc49nEWPF589GO.Wa89YP5fm8MIAZ3plQRZdR0X8wmsjDm', 'SEPA_Lastschrift', 1, 1),
(31, 0, 'blabla', 'blabla', 'blablagasse ', 1234, 'Wien', 'blabla@gmail.com', 'bla', '$2y$10$sWjDIlbBd59d8D8Vm2mhaevtkDKJBURJ5UFLYA/RFl34EpZGdFln6', 'SEPA_Lastschrift', 0, 1),
(32, 0, 'Roli', 'Roli', 'Musterstraße 2', 1222, 'Wien ', 'roli@gmail.com', 'roli', '$2y$10$PEKLtNJo8Zalsm3HeBebIuf/uRze/Qr7fLzR0ddz2YJKs9kLkyERm', 'SEPA_Lastschrift', 0, 1),
(33, 1, 'Martha ', 'Musterfrau', 'Mustergasse', 1236, 'WIen ', 'martha@hotmail.com', 'martha', '$2y$10$8dVPeO/NOEFdSRAqLs1zFeiScJ9TEDgW95gQmTSAD66/4SqNBKOSC', 'banküberweisung', 0, 1),
(34, 0, 'Person', 'Person', 'Personstraße', 1234, 'Wien', 'person@gmail.com', 'person', '$2y$10$TiAS8jYx9jBzCZSsyTXGCOVmbLIWvbaBosJqazz/lVKmpqJXpeAaW', 'banküberweisung', 0, 0);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indizes für die Tabelle `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indizes für die Tabelle `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT für Tabelle `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT für Tabelle `products`
--
ALTER TABLE `products`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`ID`);

--
-- Constraints der Tabelle `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
