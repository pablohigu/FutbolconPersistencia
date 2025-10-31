/* 1. Creación de la Base de Datos */
CREATE DATABASE IF NOT EXISTS `competicion_db`;

USE `competicion_db`;

CREATE TABLE IF NOT EXISTS `Equipo` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(100) NOT NULL UNIQUE,
    `estadio` VARCHAR(100) NOT NULL
); 

/* 3. Creación de la tabla Partido */
CREATE TABLE IF NOT EXISTS `Partido` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `jornada` INT NOT NULL,
    `id_equipo_local` INT NOT NULL,
    `id_equipo_visitante` INT NOT NULL,
    `resultado` CHAR(1) NULL,  -- '1', 'X', '2'

    FOREIGN KEY (`id_equipo_local`) 
        REFERENCES `Equipo`(`id`) 
        ON DELETE CASCADE,

    FOREIGN KEY (`id_equipo_visitante`) 
        REFERENCES `Equipo`(`id`)
        ON DELETE CASCADE,

    CHECK (`resultado` IN ('1', 'X', '2')),
    CHECK (`id_equipo_local` != `id_equipo_visitante`)
); -- <-- AÑADIDO

/* 4. Datos de ejemplo */
INSERT INTO `Equipo` (nombre, estadio) VALUES
('Athletic Club', 'San Mamés'),
('Real Sociedad', 'Reale Arena'),
('Deportivo Alavés', 'Mendizorrotza'),
('CA Osasuna', 'El Sadar');

INSERT INTO `Partido` (jornada, id_equipo_local, id_equipo_visitante, resultado) VALUES
(1, 1, 2, '1'),
(1, 3, 4, 'X'),
(2, 2, 3, '2'),
(2, 4, 1, '1');