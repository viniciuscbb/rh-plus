-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2020 at 05:31 PM
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
(2, 5, 'vini', '123', 'Vinicius Nunes Marciano', 'Estagiário', 1),
(3, 6, 'test', '123', 'Usuário Teste da Silva', 'Cargo de Teste', 4),
(4, 5, 'test2', '123', 'Clarice Alessandra Galvão', 'Auxiliar', 3),
(5, 5, 'rh', '123', 'R H', 'RH', 2);

-- --------------------------------------------------------

--
-- Table structure for table `colaboradores`
--

CREATE TABLE `colaboradores` (
  `id_colaborador` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cracha` varchar(10) NOT NULL,
  `cargo` varchar(50) NOT NULL,
  `turno` varchar(1) NOT NULL,
  `hora` varchar(10) NOT NULL,
  `id_setor` int(11) NOT NULL,
  `id_rota` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `colaboradores`
--

INSERT INTO `colaboradores` (`id_colaborador`, `nome`, `cracha`, `cargo`, `turno`, `hora`, `id_setor`, `id_rota`) VALUES
(1, 'Jacqueline meu amor S2', '451', 'Auxiliar', 'N', '6.75', 4, 1),
(2, 'Maria Aparecida da Rosa', '455', 'Analista', 'N', '7', 4, 1),
(5, 'Josenildo Das neves santana junior', '1141', 'Cargo de teste', 'M', '5', 4, 2),
(7, 'Bianca Nunes da Silva', '1244', 'Cargo de Teste', 'M', '10', 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `feriados`
--

CREATE TABLE `feriados` (
  `id_feriado` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `data` date NOT NULL,
  `tipo` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `feriados`
--

INSERT INTO `feriados` (`id_feriado`, `nome`, `data`, `tipo`) VALUES
(2, 'Dia dos namorados', '2020-06-12', 4),
(3, 'Corpus Crist', '2020-06-11', 1),
(4, 'Aniversário da cidade', '2020-06-28', 3),
(6, 'Hoje pra testar', '2020-06-21', 4);

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
(428, 142, 7, 1, 1, 20),
(431, 142, 2, 1, 1, 20),
(438, 144, 7, 1, 0, 20),
(441, 144, 2, 1, 0, 20),
(494, 155, 7, 1, 0, 0),
(532, 167, 7, 1, 1, 20),
(533, 167, 1, 1, 1, 20),
(534, 167, 5, 1, 1, 20),
(535, 167, 2, 1, 1, 20),
(537, 168, 7, 1, 1, 10),
(538, 168, 1, 1, 1, 10),
(539, 168, 5, 1, 1, 10),
(540, 168, 2, 1, 1, 10);

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
  `turno` int(1) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `motivo` varchar(200) DEFAULT NULL,
  `refazer` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `id_supervisor`, `id_setor`, `valor`, `transporte`, `alimentacao`, `horas`, `status`, `turno`, `data`, `motivo`, `refazer`) VALUES
(142, 2, 4, '880', '300', '105', '475', 3, 1, '2020-07-11', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s', NULL),
(144, 2, 4, '875', '300', '0', '575', 5, 1, '2020-08-29', 'onibus ficou parado', NULL),
(155, 2, 4, '300', '300', '0', '0', 3, 1, '2021-02-25', 'orem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s', NULL),
(167, 2, 4, '1015', '300', '140', '575', 1, 1, '2020-06-21', 'Dia de feriado', NULL),
(168, 2, 4, '727.5', '300', '140', '287.5', 1, 0, '2020-06-22', 'Não é feriado', NULL);

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
(5, 'Recursos Humanos', 2),
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
-- Indexes for table `feriados`
--
ALTER TABLE `feriados`
  ADD PRIMARY KEY (`id_feriado`);

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
  MODIFY `id_adm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `colaboradores`
--
ALTER TABLE `colaboradores`
  MODIFY `id_colaborador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `feriados`
--
ALTER TABLE `feriados`
  MODIFY `id_feriado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `lista`
--
ALTER TABLE `lista`
  MODIFY `id_lista` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=542;

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

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
