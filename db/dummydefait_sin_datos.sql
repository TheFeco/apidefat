-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.9.3-MariaDB - MariaDB Server
-- SO del servidor:              Linux
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando estructura para tabla defat.categorias
DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_deporte` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb3_spanish2_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `STATUS` binary(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla defat.ciclos
DROP TABLE IF EXISTS `ciclos`;
CREATE TABLE IF NOT EXISTS `ciclos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla defat.deportes
DROP TABLE IF EXISTS `deportes`;
CREATE TABLE IF NOT EXISTS `deportes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb3_spanish2_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyblob DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla defat.deportes_peso
DROP TABLE IF EXISTS `deportes_peso`;
CREATE TABLE IF NOT EXISTS `deportes_peso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_nivel` int(11) DEFAULT NULL,
  `id_deporte` int(11) DEFAULT NULL,
  `id_rama` int(11) DEFAULT NULL,
  `id_peso` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla defat.deportes_pruebas
DROP TABLE IF EXISTS `deportes_pruebas`;
CREATE TABLE IF NOT EXISTS `deportes_pruebas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_nivel` int(11) NOT NULL,
  `id_deporte` int(11) NOT NULL,
  `id_rama` int(11) NOT NULL,
  `id_prueba` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla defat.deportistas
DROP TABLE IF EXISTS `deportistas`;
CREATE TABLE IF NOT EXISTS `deportistas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folio` varchar(50) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `nombre` varchar(255) COLLATE utf8mb3_spanish2_ci NOT NULL,
  `apellidos` varchar(255) COLLATE utf8mb3_spanish2_ci NOT NULL,
  `fh_nacimiento` date NOT NULL,
  `curp` varchar(18) COLLATE utf8mb3_spanish2_ci NOT NULL,
  `cct` varchar(10) COLLATE utf8mb3_spanish2_ci NOT NULL,
  `escuela` varchar(255) COLLATE utf8mb3_spanish2_ci NOT NULL,
  `turno` varchar(255) COLLATE utf8mb3_spanish2_ci NOT NULL,
  `id_municipio` int(11) NOT NULL,
  `zona` int(11) NOT NULL,
  `id_ciclo` int(11) NOT NULL DEFAULT 0,
  `id_funcion` int(11) NOT NULL DEFAULT 0,
  `id_nivel` int(11) NOT NULL DEFAULT 0,
  `id_deporte` int(11) DEFAULT 0,
  `id_rama` int(11) DEFAULT 0,
  `id_prueba` int(11) DEFAULT 0,
  `id_categoria` int(11) DEFAULT 0,
  `id_peso` int(11) DEFAULT 0,
  `foto` varchar(255) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `acta_nacimiento` varchar(255) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `curp_pdf` varchar(255) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `cert_medico` varchar(255) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `carta_responsiva` varchar(255) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `ine` varchar(255) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `constancia_autorizacion` varchar(255) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `constancia_servicio` varchar(255) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `constanciaEstudio` varchar(255) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `id_usuairo` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deteled_at` timestamp NULL DEFAULT NULL,
  `id_prueba2` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6112 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla defat.escuelas
DROP TABLE IF EXISTS `escuelas`;
CREATE TABLE IF NOT EXISTS `escuelas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cct` varchar(50) COLLATE utf8mb3_spanish2_ci DEFAULT '0',
  `nombre` varchar(255) COLLATE utf8mb3_spanish2_ci DEFAULT '0',
  `id_municipio` int(11) DEFAULT NULL,
  `id_localidad` int(11) DEFAULT NULL,
  `control` varchar(255) COLLATE utf8mb3_spanish2_ci DEFAULT '0',
  `nivel` varchar(255) COLLATE utf8mb3_spanish2_ci DEFAULT '0',
  `servicio` varchar(255) COLLATE utf8mb3_spanish2_ci DEFAULT '0',
  `sostenimiento` varchar(50) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `supervision` varchar(50) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `zona` int(11) DEFAULT NULL,
  `id_turno` int(11) NOT NULL,
  `estatus` varchar(255) COLLATE utf8mb3_spanish2_ci DEFAULT 'ACTIVO',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3287 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla defat.funciones
DROP TABLE IF EXISTS `funciones`;
CREATE TABLE IF NOT EXISTS `funciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb3_spanish2_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyblob DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla defat.localidades
DROP TABLE IF EXISTS `localidades`;
CREATE TABLE IF NOT EXISTS `localidades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_municipio` int(11) NOT NULL,
  `id_localidad` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1686 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla defat.municipios
DROP TABLE IF EXISTS `municipios`;
CREATE TABLE IF NOT EXISTS `municipios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8mb3_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla defat.niveles
DROP TABLE IF EXISTS `niveles`;
CREATE TABLE IF NOT EXISTS `niveles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_At` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla defat.peso
DROP TABLE IF EXISTS `peso`;
CREATE TABLE IF NOT EXISTS `peso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb3_spanish2_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla defat.peso_pruebas
DROP TABLE IF EXISTS `peso_pruebas`;
CREATE TABLE IF NOT EXISTS `peso_pruebas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_peso` int(11) NOT NULL,
  `id_prueba` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla defat.pruebas
DROP TABLE IF EXISTS `pruebas`;
CREATE TABLE IF NOT EXISTS `pruebas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_deporte` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb3_spanish2_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla defat.ramas
DROP TABLE IF EXISTS `ramas`;
CREATE TABLE IF NOT EXISTS `ramas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb3_spanish2_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyblob DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla defat.usuarios
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `password` varchar(50) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `id_rol` varchar(50) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `id_nivel` varchar(50) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `Estado` varchar(50) COLLATE utf8mb3_spanish2_ci DEFAULT 'Activo',
  `sn_actualizar` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sn_eliminado` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla defat.usuarios_token
DROP TABLE IF EXISTS `usuarios_token`;
CREATE TABLE IF NOT EXISTS `usuarios_token` (
  `TokenId` int(11) NOT NULL AUTO_INCREMENT,
  `UsuarioId` varchar(45) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `Token` varchar(45) COLLATE utf8mb3_spanish2_ci DEFAULT NULL,
  `Estado` varchar(45) CHARACTER SET armscii8 DEFAULT NULL,
  `Fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`TokenId`)
) ENGINE=InnoDB AUTO_INCREMENT=429 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- La exportación de datos fue deseleccionada.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
