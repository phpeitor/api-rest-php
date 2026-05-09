-- Servidor: 127.0.0.1
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `test`
--

CREATE TABLE `usuario` (
  `usuario_id` int(5) UNSIGNED ZEROFILL NOT NULL,
  `id` varchar(23) NOT NULL,
  `paterno` varchar(30) NOT NULL,
  `materno` varchar(30) NOT NULL,
  `nombres` varchar(35) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `clave` varchar(80) NOT NULL,
  `semilla` varchar(10) NOT NULL,
  `registro_fecha` datetime DEFAULT NULL,
  `actualizado_fecha` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


INSERT INTO `usuario` (`usuario_id`, `id`, `paterno`, `materno`, `nombres`, `correo`, `clave`, `semilla`, `registro_fecha`, `actualizado_fecha`) VALUES
(00001, '00001', 'Smith', 'Johnson', 'John', 'john@example.com', 'password123', 'seed1', '2024-05-05 15:53:46', '2024-05-05 15:53:46'),
(00002, '00002', 'Williams', 'Jones', 'Emily', 'emily@example.com', 'password456', 'seed2', '2024-05-05 15:53:46', '2024-05-05 15:53:46'),
(00003, '00003', 'Brown', 'Davis', 'Michael', 'michael@example.com', 'password789', 'seed3', '2024-05-05 15:53:46', '2024-05-05 15:53:46'),
(00004, '00004', 'Miller', 'Wilson', 'Sarah', 'sarah@example.com', 'passwordabc', 'seed4', '2024-05-05 15:53:46', '2024-05-05 15:53:46'),
(00005, '00005', 'Taylor', 'Moore', 'Jessica', 'jessica@example.com', 'passworddef', 'seed5', '2024-05-05 15:53:46', '2024-05-05 15:53:46'),
(00006, '00006', 'Anderson', 'Jackson', 'Matthew', 'matthew@example.com', 'passwordghi', 'seed6', '2024-05-05 15:53:46', '2024-05-05 15:53:46'),
(00007, '00007', 'Thomas', 'White', 'Laura', 'laura@example.com', 'passwordjkl', 'seed7', '2024-05-05 15:53:46', '2024-05-05 15:53:46'),
(00008, '00008', 'Clark', 'Harris', 'David', 'david@example.com', 'passwordmno', 'seed8', '2024-05-05 15:53:46', '2024-05-05 15:53:46'),
(00009, '00009', 'Lewis', 'Martin', 'Jennifer', 'jennifer@example.com', 'passwordpqr', 'seed9', '2024-05-05 15:53:46', '2024-05-05 15:53:46'),
(00010, '00010', 'Hall', 'Thompson', 'Elizabeth', 'elizabeth@example.com', 'passwordstu', 'seed10', '2024-05-05 15:53:46', '2024-05-05 15:53:46');

ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usuario_id`),
  ADD UNIQUE KEY `unique_id` (`id`),
  ADD UNIQUE KEY `correo` (`correo`);


ALTER TABLE `usuario`
  MODIFY `usuario_id` int(5) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

