-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2021 at 10:44 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shopee`
--

-- --------------------------------------------------------

--
-- Table structure for table `brendovi`
--

CREATE TABLE `brendovi` (
  `brend_id` int(255) NOT NULL,
  `naziv` varchar(100) NOT NULL,
  `opis` text NOT NULL,
  `aktivno` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `kategorije`
--

CREATE TABLE `kategorije` (
  `kategorija_id` int(255) NOT NULL,
  `naziv` varchar(100) NOT NULL,
  `opis` text NOT NULL,
  `aktivno` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `kupci`
--

CREATE TABLE `kupci` (
  `kupac_id` int(255) NOT NULL,
  `lozinka` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `ime` int(50) NOT NULL,
  `prezime` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `narudzbe`
--

CREATE TABLE `narudzbe` (
  `narudzba_id` int(255) NOT NULL,
  `kupac_id` int(11) NOT NULL,
  `vrijeme` date NOT NULL,
  `status_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `podkategorije`
--

CREATE TABLE `podkategorije` (
  `podKat_id` int(255) NOT NULL,
  `kategorija_id` int(255) NOT NULL,
  `naziv` varchar(100) NOT NULL,
  `opis` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `popisi_proizvoda`
--

CREATE TABLE `popisi_proizvoda` (
  `narudzba_id` int(255) NOT NULL,
  `proizvod_id` int(255) NOT NULL,
  `kolicina` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `proizvodi`
--

CREATE TABLE `proizvodi` (
  `proizvod_id` int(255) NOT NULL,
  `naziv` varchar(200) NOT NULL,
  `brend_id` int(255) NOT NULL,
  `opis` text NOT NULL,
  `osnovna_cijena` double NOT NULL,
  `postotak_popusta` float NOT NULL,
  `dostupna_kolicina` int(255) NOT NULL,
  `zabrani_narucivanje` tinyint(1) NOT NULL,
  `izvodjeno` tinyint(1) NOT NULL,
  `aktivno` tinyint(1) NOT NULL,
  `neog_broj_foto` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `proizvod_kategorija`
--

CREATE TABLE `proizvod_kategorija` (
  `proizvod_id` int(255) NOT NULL,
  `kategorija_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `statusi_narudzba`
--

CREATE TABLE `statusi_narudzba` (
  `status_id` int(11) NOT NULL,
  `naziv` varchar(100) NOT NULL,
  `opis` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brendovi`
--
ALTER TABLE `brendovi`
  ADD PRIMARY KEY (`brend_id`);

--
-- Indexes for table `kategorije`
--
ALTER TABLE `kategorije`
  ADD PRIMARY KEY (`kategorija_id`);

--
-- Indexes for table `kupci`
--
ALTER TABLE `kupci`
  ADD PRIMARY KEY (`kupac_id`);

--
-- Indexes for table `narudzbe`
--
ALTER TABLE `narudzbe`
  ADD PRIMARY KEY (`narudzba_id`),
  ADD KEY `narudzba_to_kupac` (`kupac_id`),
  ADD KEY `narudzba_to_status` (`status_id`);

--
-- Indexes for table `podkategorije`
--
ALTER TABLE `podkategorije`
  ADD PRIMARY KEY (`podKat_id`),
  ADD KEY `podKat_to_kat` (`kategorija_id`);

--
-- Indexes for table `proizvodi`
--
ALTER TABLE `proizvodi`
  ADD PRIMARY KEY (`proizvod_id`),
  ADD KEY `brend_to_proizvod` (`brend_id`);

--
-- Indexes for table `statusi_narudzba`
--
ALTER TABLE `statusi_narudzba`
  ADD PRIMARY KEY (`status_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brendovi`
--
ALTER TABLE `brendovi`
  MODIFY `brend_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategorije`
--
ALTER TABLE `kategorije`
  MODIFY `kategorija_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kupci`
--
ALTER TABLE `kupci`
  MODIFY `kupac_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `narudzbe`
--
ALTER TABLE `narudzbe`
  MODIFY `narudzba_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `podkategorije`
--
ALTER TABLE `podkategorije`
  MODIFY `podKat_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proizvodi`
--
ALTER TABLE `proizvodi`
  MODIFY `proizvod_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `statusi_narudzba`
--
ALTER TABLE `statusi_narudzba`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `narudzbe`
--
ALTER TABLE `narudzbe`
  ADD CONSTRAINT `narudzba_to_kupac` FOREIGN KEY (`kupac_id`) REFERENCES `kupci` (`kupac_id`),
  ADD CONSTRAINT `narudzba_to_status` FOREIGN KEY (`status_id`) REFERENCES `statusi_narudzba` (`status_id`);

--
-- Constraints for table `podkategorije`
--
ALTER TABLE `podkategorije`
  ADD CONSTRAINT `podKat_to_kat` FOREIGN KEY (`kategorija_id`) REFERENCES `kategorije` (`kategorija_id`);

--
-- Constraints for table `proizvodi`
--
ALTER TABLE `proizvodi`
  ADD CONSTRAINT `brend_to_proizvod` FOREIGN KEY (`brend_id`) REFERENCES `brendovi` (`brend_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
