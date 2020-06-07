-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2020 at 08:15 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `volare`
--
CREATE DATABASE IF NOT EXISTS `volare` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `volare`;

-- --------------------------------------------------------

--
-- Table structure for table `adm`
--

CREATE TABLE `adm` (
  `id_adm` int(11) NOT NULL,
  `id_setor` int(11) NOT NULL,
  `login` varchar(10) NOT NULL,
  `senha` varchar(20) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cargo` varchar(50) NOT NULL,
  `nivel` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `adm`
--

INSERT INTO `adm` (`id_adm`, `id_setor`, `login`, `senha`, `nome`, `cargo`, `nivel`) VALUES
(2, 5, 'vini', '123', 'Vinicius Nunes Marciano', 'Estagiário', 3),
(3, 6, 'test', '123', 'Usuário Teste da Silva', 'Cargo de Teste', 4),
(4, 5, 'test2', '123', 'Clarice Alessandra Galvão', 'Auxiliar', 2);

-- --------------------------------------------------------

--
-- Table structure for table `colaboradores`
--

CREATE TABLE `colaboradores` (
  `id_colaborador` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cracha` varchar(10) NOT NULL,
  `cargo` varchar(50) NOT NULL,
  `hora` varchar(10) NOT NULL,
  `id_setor` int(11) NOT NULL,
  `id_rota` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `colaboradores`
--

INSERT INTO `colaboradores` (`id_colaborador`, `nome`, `cracha`, `cargo`, `hora`, `id_setor`, `id_rota`) VALUES
(1, 'Jacqueline meu amor S2', '451', 'Auxiliar', '6.75', 4, 1),
(2, 'Maria Aparecida da Rosa', '455', 'Analista', '7', 4, 1),
(5, 'Josenildo Das neves santana junior', '1141', 'Cargo de teste', '5', 4, 2),
(7, 'Bianca Nunes da Silva', '1244', 'Cargo de Teste', '10', 5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `lista`
--

CREATE TABLE `lista` (
  `id_lista` int(11) NOT NULL,
  `id_reserva` int(11) NOT NULL,
  `id_colaborador` int(11) NOT NULL,
  `transporte` int(11) DEFAULT NULL,
  `alimentacao` int(11) DEFAULT NULL,
  `hora` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lista`
--

INSERT INTO `lista` (`id_lista`, `id_reserva`, `id_colaborador`, `transporte`, `alimentacao`, `hora`) VALUES
(297, 102, 7, 1, 1, 10),
(298, 102, 1, 1, 1, 20),
(299, 102, 5, 1, 1, 30),
(300, 102, 2, 1, 1, 40),
(302, 103, 7, 1, 1, 0),
(303, 103, 1, 1, 1, 0),
(304, 103, 5, 1, 1, 0),
(306, 104, 7, 1, 1, 0),
(307, 104, 1, 1, 1, 0),
(308, 104, 5, 1, 1, 0),
(309, 104, 2, 1, 1, 0),
(313, 106, 7, 0, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_supervisor` int(11) NOT NULL,
  `id_setor` int(11) NOT NULL,
  `valor` varchar(11) NOT NULL,
  `transporte` varchar(11) DEFAULT NULL,
  `alimentacao` varchar(11) DEFAULT NULL,
  `horas` varchar(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `data` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `id_supervisor`, `id_setor`, `valor`, `transporte`, `alimentacao`, `horas`, `status`, `data`) VALUES
(102, 2, 4, '1105', '300', '140', '665', 3, '2020-06-15'),
(103, 4, 5, '405', '300', '105', '0', 3, '2020-06-10'),
(104, 3, 6, '440', '300', '140', '0', 3, '2020-06-18'),
(106, 4, 5, '135', '0', '35', '100', 3, '2020-06-22');

-- --------------------------------------------------------

--
-- Table structure for table `rotas`
--

CREATE TABLE `rotas` (
  `id_rota` int(11) NOT NULL,
  `rota` varchar(5) NOT NULL,
  `capacidade` int(50) NOT NULL,
  `valor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rotas`
--

INSERT INTO `rotas` (`id_rota`, `rota`, `capacidade`, `valor`) VALUES
(1, '11', 50, 100),
(2, '10', 50, 200);

-- --------------------------------------------------------

--
-- Table structure for table `setores`
--

CREATE TABLE `setores` (
  `id_setor` int(11) NOT NULL,
  `setor` varchar(50) NOT NULL,
  `id_responsavel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `setores`
--

INSERT INTO `setores` (`id_setor`, `setor`, `id_responsavel`) VALUES
(4, 'Tecnologia da Informação', 2),
(5, 'Recursos Humanos', 4),
(6, 'Pintura', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adm`
--
ALTER TABLE `adm`
  ADD PRIMARY KEY (`id_adm`),
  ADD KEY `fk_id_seto` (`id_setor`);

--
-- Indexes for table `colaboradores`
--
ALTER TABLE `colaboradores`
  ADD PRIMARY KEY (`id_colaborador`),
  ADD UNIQUE KEY `cracha` (`cracha`),
  ADD KEY `fk_setor` (`id_setor`),
  ADD KEY `fk_id_rota` (`id_rota`);

--
-- Indexes for table `lista`
--
ALTER TABLE `lista`
  ADD PRIMARY KEY (`id_lista`),
  ADD KEY `fk_id_reserva` (`id_reserva`),
  ADD KEY `fk_id_colaboradorr` (`id_colaborador`);

--
-- Indexes for table `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `fk_id_supervisor` (`id_supervisor`),
  ADD KEY `fk_id_setor` (`id_setor`);

--
-- Indexes for table `rotas`
--
ALTER TABLE `rotas`
  ADD PRIMARY KEY (`id_rota`);

--
-- Indexes for table `setores`
--
ALTER TABLE `setores`
  ADD PRIMARY KEY (`id_setor`),
  ADD KEY `fk_id_responsavel` (`id_responsavel`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adm`
--
ALTER TABLE `adm`
  MODIFY `id_adm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `colaboradores`
--
ALTER TABLE `colaboradores`
  MODIFY `id_colaborador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `lista`
--
ALTER TABLE `lista`
  MODIFY `id_lista` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=315;

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `rotas`
--
ALTER TABLE `rotas`
  MODIFY `id_rota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `setores`
--
ALTER TABLE `setores`
  MODIFY `id_setor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adm`
--
ALTER TABLE `adm`
  ADD CONSTRAINT `fk_id_seto` FOREIGN KEY (`id_setor`) REFERENCES `setores` (`id_setor`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `colaboradores`
--
ALTER TABLE `colaboradores`
  ADD CONSTRAINT `fk_id_rota` FOREIGN KEY (`id_rota`) REFERENCES `rotas` (`id_rota`),
  ADD CONSTRAINT `fk_setor` FOREIGN KEY (`id_setor`) REFERENCES `setores` (`id_setor`);

--
-- Constraints for table `lista`
--
ALTER TABLE `lista`
  ADD CONSTRAINT `fk_id_colaboradorr` FOREIGN KEY (`id_colaborador`) REFERENCES `colaboradores` (`id_colaborador`),
  ADD CONSTRAINT `fk_id_reserva` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id_reserva`);

--
-- Constraints for table `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_id_setor` FOREIGN KEY (`id_setor`) REFERENCES `setores` (`id_setor`),
  ADD CONSTRAINT `fk_id_supervisor` FOREIGN KEY (`id_supervisor`) REFERENCES `adm` (`id_adm`);

--
-- Constraints for table `setores`
--
ALTER TABLE `setores`
  ADD CONSTRAINT `fk_id_responsavel` FOREIGN KEY (`id_responsavel`) REFERENCES `adm` (`id_adm`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
