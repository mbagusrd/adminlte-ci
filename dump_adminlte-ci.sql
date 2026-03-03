-- ========================================================
-- SQL Database Schema for Setting Module (AdminLTE)
-- Tables: s_grup, s_user, s_menu, s_akses
-- ========================================================

-- --------------------------------------------------------
-- Table structure for table `s_grup`
-- (User Groups/Role Management)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `s_grup`;
CREATE TABLE `s_grup` (
  `id_grup` varchar(10) NOT NULL COMMENT 'Primary Key - Group ID',
  `nm_grup` varchar(50) NOT NULL COMMENT 'Group Name',
  PRIMARY KEY (`id_grup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master table for user groups';

-- --------------------------------------------------------
-- Table structure for table `s_user`
-- (User Management)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `s_user`;
CREATE TABLE `s_user` (
  `id_user` varchar(10) NOT NULL COMMENT 'Primary Key - User ID',
  `nama` varchar(100) NOT NULL COMMENT 'User Full Name',
  `username` varchar(50) NOT NULL COMMENT 'Login Username',
  `passwd` varchar(255) NOT NULL COMMENT 'Password Hash (MD5)',
  `id_grup` varchar(10) NOT NULL COMMENT 'Foreign Key to s_grup',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`),
  KEY `fk_user_grup` (`id_grup`),
  CONSTRAINT `fk_user_grup` FOREIGN KEY (`id_grup`) REFERENCES `s_grup` (`id_grup`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master table for users';

-- --------------------------------------------------------
-- Table structure for table `s_menu`
-- (Menu Management)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `s_menu`;
CREATE TABLE `s_menu` (
  `id_menu` varchar(10) NOT NULL COMMENT 'Primary Key - Menu ID',
  `jns_menu` char(1) NOT NULL DEFAULT '2' COMMENT 'Menu Type: 1=Folder, 2=Menu Link',
  `nm_menu` varchar(100) NOT NULL COMMENT 'Menu Name/Label',
  `link` varchar(100) DEFAULT NULL COMMENT 'Controller Link/URL',
  PRIMARY KEY (`id_menu`),
  KEY `idx_jns_menu` (`jns_menu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master table for application menus';

-- --------------------------------------------------------
-- Table structure for table `s_akses`
-- (Group Access Rights for Menus)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `s_akses`;
CREATE TABLE `s_akses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Auto Increment ID',
  `id_grup` varchar(10) NOT NULL COMMENT 'Foreign Key to s_grup',
  `id_menu` varchar(10) NOT NULL COMMENT 'Foreign Key to s_menu',
  `id_parent0` varchar(10) NOT NULL DEFAULT '0' COMMENT 'Parent Level 0 (Root)',
  `id_parent1` varchar(10) NOT NULL DEFAULT '0' COMMENT 'Parent Level 1 (Subfolder)',
  `id_parent2` varchar(10) NOT NULL DEFAULT '0' COMMENT 'Parent Level 2 (Sub-submenu)',
  `sort` int(11) NOT NULL DEFAULT '1' COMMENT 'Menu Sort Order',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_access` (`id_grup`,`id_menu`,`id_parent0`,`id_parent1`,`id_parent2`),
  KEY `idx_grup` (`id_grup`),
  KEY `idx_menu` (`id_menu`),
  KEY `idx_parent` (`id_parent0`,`id_parent1`,`id_parent2`),
  CONSTRAINT `fk_akses_grup` FOREIGN KEY (`id_grup`) REFERENCES `s_grup` (`id_grup`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_akses_menu` FOREIGN KEY (`id_menu`) REFERENCES `s_menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Access rights table linking groups to menus';

-- ========================================================
-- SAMPLE DATA
-- ========================================================

-- --------------------------------------------------------
-- Sample Data: Administrator Group
-- --------------------------------------------------------
INSERT INTO `s_grup` (`id_grup`, `nm_grup`) VALUES
('1', 'ADMINISTRATOR');

-- --------------------------------------------------------
-- Sample Data: Administrator User
-- Password: admin (MD5 hash: 21232f297a57a5a743894a0e4a801fc3)
-- --------------------------------------------------------
INSERT INTO `s_user` (`id_user`, `nama`, `username`, `passwd`, `id_grup`) VALUES
('1', 'Administrator', 'ADMIN', '21232f297a57a5a743894a0e4a801fc3', '1');
