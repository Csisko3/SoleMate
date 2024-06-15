-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 15. Jun 2024 um 21:43
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
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(33, 29, 6, 1),
(34, 29, 3, 1);

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
(1, 'Nike Air Force 1 \'07 Triple White', '149.00', 'Nike', 'air-force-1.png'),
(2, 'Adidas Yeezy Boost 350 V2', '299.00', 'Adiddas', 'yeezy-350.jpeg'),
(3, 'Nike Dunk Low Black White', '200.00', 'Nike', 'nike-dunk.png'),
(4, 'Air Jordan 4 Military Black', '399.00', 'Jordan', 'jordan-4-military.jpg'),
(6, 'Adidas Campus 00 Grey White', '149.00', 'Addidas', 'adidas-campus.JPG'),
(25, ' Air Jordan 4 Retro ', '209.99', 'Jordan', '2332306_P.jpg');

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
(32, 0, 'Roland', 'Csiser', 'Musterstraße 2', 1222, 'Wien ', 'roli@gmail.com', 'roli', '$2y$10$PEKLtNJo8Zalsm3HeBebIuf/uRze/Qr7fLzR0ddz2YJKs9kLkyERm', 'SEPA_Lastschrift', 0, 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT für Tabelle `products`
--
ALTER TABLE `products`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

