-- Migracion inicial para entorno de pruebas
-- Crea la base de datos, la tabla `usuario` y carga datos seed.

SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE DATABASE IF NOT EXISTS `test`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `test`;

START TRANSACTION;

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `usuario_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id` VARCHAR(23) NOT NULL,
  `paterno` VARCHAR(30) NOT NULL,
  `materno` VARCHAR(30) NOT NULL,
  `nombres` VARCHAR(35) NOT NULL,
  `correo` VARCHAR(100) NOT NULL,
  `clave` VARCHAR(80) NOT NULL,
  `semilla` VARCHAR(10) NOT NULL,
  `registro_fecha` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_fecha` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`usuario_id`),
  UNIQUE KEY `unique_id` (`id`),
  UNIQUE KEY `unique_correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `usuario` (`id`, `paterno`, `materno`, `nombres`, `correo`, `clave`, `semilla`) VALUES
('00001', 'Smith', 'Johnson', 'John', 'john@example.com', 'password123', 'seed1'),
('00002', 'Williams', 'Jones', 'Emily', 'emily@example.com', 'password456', 'seed2'),
('00003', 'Brown', 'Davis', 'Michael', 'michael@example.com', 'password789', 'seed3'),
('00004', 'Miller', 'Wilson', 'Sarah', 'sarah@example.com', 'passwordabc', 'seed4'),
('00005', 'Taylor', 'Moore', 'Jessica', 'jessica@example.com', 'passworddef', 'seed5'),
('00006', 'Anderson', 'Jackson', 'Matthew', 'matthew@example.com', 'passwordghi', 'seed6'),
('00007', 'Thomas', 'White', 'Laura', 'laura@example.com', 'passwordjkl', 'seed7'),
('00008', 'Clark', 'Harris', 'David', 'david@example.com', 'passwordmno', 'seed8'),
('00009', 'Lewis', 'Martin', 'Jennifer', 'jennifer@example.com', 'passwordpqr', 'seed9'),
('00010', 'Hall', 'Thompson', 'Elizabeth', 'elizabeth@example.com', 'passwordstu', 'seed10');

COMMIT;

