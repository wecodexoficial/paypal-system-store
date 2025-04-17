/*
Navicat MySQL Data Transfer

Source Server         : bgt
Source Server Version : 50719
Source Host           : localhost:3306
Source Database       : wicommer

Target Server Type    : MYSQL
Target Server Version : 50719
File Encoding         : 65001

Date: 2018-01-07 14:33:57
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for common_category
-- ----------------------------
DROP TABLE IF EXISTS `common_category`;
CREATE TABLE `common_category` (
  `id_category` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `short_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of common_category
-- ----------------------------

-- ----------------------------
-- Table structure for common_inventary
-- ----------------------------
DROP TABLE IF EXISTS `common_inventary`;
CREATE TABLE `common_inventary` (
  `id_inventary` int(11) NOT NULL AUTO_INCREMENT,
  `id_product` varchar(255) DEFAULT NULL,
  `id_user` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `type` int(255) DEFAULT NULL,
  `date_re` date DEFAULT NULL,
  PRIMARY KEY (`id_inventary`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of common_inventary
-- ----------------------------

-- ----------------------------
-- Table structure for common_mark
-- ----------------------------
DROP TABLE IF EXISTS `common_mark`;
CREATE TABLE `common_mark` (
  `id_mark` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(600) DEFAULT NULL,
  `short_name` varchar(255) DEFAULT NULL,
  `is_public` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_mark`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of common_mark
-- ----------------------------

-- ----------------------------
-- Table structure for common_notification
-- ----------------------------
DROP TABLE IF EXISTS `common_notification`;
CREATE TABLE `common_notification` (
  `id_notification` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `id_user_receiver` varchar(255) DEFAULT NULL,
  `id_user_transmitter` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `date_re` datetime DEFAULT NULL,
  `redir` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_notification`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of common_notification
-- ----------------------------

-- ----------------------------
-- Table structure for common_product
-- ----------------------------
DROP TABLE IF EXISTS `common_product`;
CREATE TABLE `common_product` (
  `id_product` int(10) NOT NULL AUTO_INCREMENT,
  `name` text,
  `sku` varchar(255) DEFAULT NULL,
  `inventary` int(20) DEFAULT NULL,
  `description` text,
  `key_unit` varchar(255) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `porcent_discount` int(11) DEFAULT NULL,
  `is_public` varchar(255) DEFAULT '0',
  `is_top` varchar(255) DEFAULT '0',
  `id_category` int(11) DEFAULT NULL,
  `id_provider` int(11) DEFAULT NULL,
  `id_mark` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of common_product
-- ----------------------------

-- ----------------------------
-- Table structure for common_product_sell
-- ----------------------------
DROP TABLE IF EXISTS `common_product_sell`;
CREATE TABLE `common_product_sell` (
  `id_sell_product` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `date_re` date DEFAULT NULL,
  `quantity` varchar(255) DEFAULT NULL,
  `subtotal` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_sell_product`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of common_product_sell
-- ----------------------------

-- ----------------------------
-- Table structure for common_product_storage
-- ----------------------------
DROP TABLE IF EXISTS `common_product_storage`;
CREATE TABLE `common_product_storage` (
  `id_image` int(11) NOT NULL AUTO_INCREMENT,
  `id_product` varchar(255) DEFAULT NULL,
  `rute` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_image`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of common_product_storage
-- ----------------------------

-- ----------------------------
-- Table structure for common_provider
-- ----------------------------
DROP TABLE IF EXISTS `common_provider`;
CREATE TABLE `common_provider` (
  `id_provider` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id_provider`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of common_provider
-- ----------------------------

-- ----------------------------
-- Table structure for common_rol
-- ----------------------------
DROP TABLE IF EXISTS `common_rol`;
CREATE TABLE `common_rol` (
  `id_rol` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `dir` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of common_rol
-- ----------------------------
INSERT INTO `common_rol` VALUES ('MASTER', 'Todos los permisos', '/admin');
INSERT INTO `common_rol` VALUES ('ADMIN', 'Permisos de panel ', '/admin');
INSERT INTO `common_rol` VALUES ('CLIENT', 'Permisos de usuario normal', '/client/profile');

-- ----------------------------
-- Table structure for common_sell
-- ----------------------------
DROP TABLE IF EXISTS `common_sell`;
CREATE TABLE `common_sell` (
  `id_sell` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `correlation_id` varchar(255) DEFAULT NULL,
  `build_number` varchar(255) DEFAULT NULL,
  `email_buyer` varchar(255) DEFAULT NULL,
  `currency_code_payment` varchar(255) DEFAULT NULL,
  `currency_code_sell` varchar(255) DEFAULT '',
  `token` varchar(255) DEFAULT NULL,
  `player_status` varchar(255) DEFAULT NULL,
  `atm` varchar(255) DEFAULT NULL,
  `itemamt` varchar(255) DEFAULT NULL,
  `taxatm` varchar(255) DEFAULT NULL,
  `date_re` date DEFAULT NULL,
  `payment_status` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `send_by` varchar(255) DEFAULT NULL,
  `number_send` varchar(255) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_sell`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of common_sell
-- ----------------------------

-- ----------------------------
-- Table structure for common_slider
-- ----------------------------
DROP TABLE IF EXISTS `common_slider`;
CREATE TABLE `common_slider` (
  `id_slider` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_public` int(255) DEFAULT NULL,
  `date_re` datetime DEFAULT NULL,
  PRIMARY KEY (`id_slider`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of common_slider
-- ----------------------------

-- ----------------------------
-- Table structure for common_user
-- ----------------------------
DROP TABLE IF EXISTS `common_user`;
CREATE TABLE `common_user` (
  `id_user` varchar(64) NOT NULL,
  `id_rol` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `last_access` datetime DEFAULT NULL,
  `attempts` int(11) NOT NULL DEFAULT '0',
  `locked` char(2) NOT NULL DEFAULT 'NO',
  `date_locked` datetime DEFAULT NULL,
  `is_login` char(2) NOT NULL DEFAULT 'NO',
  `is_active` char(2) NOT NULL DEFAULT 'SI',
  `country` varchar(255) DEFAULT NULL,
  `cp` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of common_user
-- ----------------------------
INSERT INTO `common_user` VALUES ('admin', 'MASTER', 'Administradors', 'Maestro', '', '$2y$10$hjo5wTjulMe4tMLx3snxoeuRmuYpA0ehZMCwXvGaE6zTDjmRVyWJ2', 'admin@mail.com', '123456789', 'Direccion', '2017-05-23 09:24:43', '0', 'NO', null, 'SI', 'SI', '1000', '', null);

-- ----------------------------
-- Table structure for core_settings
-- ----------------------------
DROP TABLE IF EXISTS `core_settings`;
CREATE TABLE `core_settings` (
  `id_config` int(11) NOT NULL,
  `ws_name` varchar(255) DEFAULT NULL,
  `ws_phone` varchar(255) DEFAULT NULL,
  `ws_mail_contact` varchar(255) DEFAULT NULL,
  `ws_mail_info` varchar(255) DEFAULT NULL,
  `ws_logo` varchar(255) DEFAULT NULL,
  `ws_facebook` varchar(255) DEFAULT NULL,
  `ws_twitter` varchar(255) DEFAULT NULL,
  `ws_youtube` varchar(255) DEFAULT NULL,
  `ws_googlemas` varchar(255) DEFAULT NULL,
  `ws_instagram` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_config`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of core_settings
-- ----------------------------
INSERT INTO `core_settings` VALUES ('1', 'Name Shop', '00000000', 'contact@mail.com', 'info@mail.com', '#', '#', '#', '#', '#', '#');
