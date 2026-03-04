-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.11.14-MariaDB-0ubuntu0.24.04.1 - Ubuntu 24.04
-- Server OS:                    debian-linux-gnu
-- HeidiSQL Version:             12.15.0.7171
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table adminlte_ci.s_akses
DROP TABLE IF EXISTS `s_akses`;
CREATE TABLE IF NOT EXISTS `s_akses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Auto Increment ID',
  `id_grup` varchar(10) NOT NULL COMMENT 'Foreign Key to s_grup',
  `id_menu` varchar(10) NOT NULL COMMENT 'Foreign Key to s_menu',
  `id_parent` varchar(10) NOT NULL,
  `id_parent0` varchar(10) NOT NULL DEFAULT '0' COMMENT 'Parent Level 0 (Root)',
  `id_parent1` varchar(10) NOT NULL DEFAULT '0' COMMENT 'Parent Level 1 (Subfolder)',
  `id_parent2` varchar(10) NOT NULL DEFAULT '0' COMMENT 'Parent Level 2 (Sub-submenu)',
  `level` int(11) NOT NULL DEFAULT 1,
  `sort` int(11) NOT NULL DEFAULT 1 COMMENT 'Menu Sort Order',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_access` (`id_grup`,`id_menu`,`id_parent`,`level`) USING BTREE,
  KEY `idx_grup` (`id_grup`),
  KEY `idx_menu` (`id_menu`),
  KEY `idx_parent` (`id_parent0`,`id_parent1`,`id_parent2`),
  CONSTRAINT `fk_akses_grup` FOREIGN KEY (`id_grup`) REFERENCES `s_grup` (`id_grup`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_akses_menu` FOREIGN KEY (`id_menu`) REFERENCES `s_menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Access rights table linking groups to menus';

-- Dumping data for table adminlte_ci.s_akses: ~0 rows (approximately)

-- Dumping structure for table adminlte_ci.s_grup
DROP TABLE IF EXISTS `s_grup`;
CREATE TABLE IF NOT EXISTS `s_grup` (
  `id_grup` varchar(10) NOT NULL COMMENT 'Primary Key - Group ID',
  `nm_grup` varchar(50) NOT NULL COMMENT 'Group Name',
  PRIMARY KEY (`id_grup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master table for user groups';

-- Dumping data for table adminlte_ci.s_grup: ~0 rows (approximately)
INSERT INTO `s_grup` (`id_grup`, `nm_grup`) VALUES
	('1', 'ADMINISTRATOR');

-- Dumping structure for table adminlte_ci.s_menu
DROP TABLE IF EXISTS `s_menu`;
CREATE TABLE IF NOT EXISTS `s_menu` (
  `id_menu` varchar(10) NOT NULL COMMENT 'Primary Key - Menu ID',
  `jns_menu` char(1) NOT NULL DEFAULT '2' COMMENT 'Menu Type: 1=Folder, 2=Menu Link',
  `nm_menu` varchar(100) NOT NULL COMMENT 'Menu Name/Label',
  `link` varchar(100) DEFAULT NULL COMMENT 'Controller Link/URL',
  PRIMARY KEY (`id_menu`),
  KEY `idx_jns_menu` (`jns_menu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master table for application menus';

-- Dumping data for table adminlte_ci.s_menu: ~0 rows (approximately)

-- Dumping structure for table adminlte_ci.s_user
DROP TABLE IF EXISTS `s_user`;
CREATE TABLE IF NOT EXISTS `s_user` (
  `id_user` varchar(10) NOT NULL COMMENT 'Primary Key - User ID',
  `nama` varchar(100) NOT NULL COMMENT 'User Full Name',
  `username` varchar(50) NOT NULL COMMENT 'Login Username',
  `passwd` varchar(255) NOT NULL COMMENT 'Password Hash (MD5)',
  `id_grup` varchar(10) NOT NULL COMMENT 'Foreign Key to s_grup',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`),
  KEY `fk_user_grup` (`id_grup`),
  CONSTRAINT `fk_user_grup` FOREIGN KEY (`id_grup`) REFERENCES `s_grup` (`id_grup`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master table for users';

-- Dumping data for table adminlte_ci.s_user: ~0 rows (approximately)
INSERT INTO `s_user` (`id_user`, `nama`, `username`, `passwd`, `id_grup`) VALUES
	('1', 'Administrator', 'ADMIN', '21232f297a57a5a743894a0e4a801fc3', '1');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;