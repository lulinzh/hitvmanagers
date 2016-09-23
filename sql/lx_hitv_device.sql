/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : db_brightstarthink

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2016-07-11 21:03:15
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `lx_hitv_device`
-- ----------------------------
DROP TABLE IF EXISTS `lx_hitv_device`;
CREATE TABLE `lx_hitv_device` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `device_type_id` tinyint(4) NOT NULL,
  `device_unique_id` varchar(32) NOT NULL,
  `user_id` mediumint(8) NOT NULL,
  `registered_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lx_hitv_device
-- ----------------------------
INSERT INTO `lx_hitv_device` VALUES ('1', '1', '1', '1', '1452844836');

-- ----------------------------
-- Table structure for `lx_hitv_device_type`
-- ----------------------------
DROP TABLE IF EXISTS `lx_hitv_device_type`;
CREATE TABLE `lx_hitv_device_type` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `icon_url` varchar(100) DEFAULT NULL,
  `info` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lx_hitv_device_type
-- ----------------------------
INSERT INTO `lx_hitv_device_type` VALUES ('1', '机顶盒', null, null);

-- ----------------------------
-- Table structure for `lx_hitv_order`
-- ----------------------------
DROP TABLE IF EXISTS `lx_hitv_order`;
CREATE TABLE `lx_hitv_order` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `app_id` mediumint(8) NOT NULL,
  `price` mediumint(16) NOT NULL,
  `gen_user_id` mediumint(8) DEFAULT NULL,
  `pay_user_id` mediumint(8) DEFAULT NULL,
  `app_order_id` varchar(32) NOT NULL,
  `title` varchar(32) NOT NULL,
  `info` varchar(128) DEFAULT NULL,
  `create_time` int(10) DEFAULT NULL,
  `pay_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lx_hitv_order
-- ----------------------------
INSERT INTO `lx_hitv_order` VALUES ('1', '1', '1000', null, null, '1', '大米', null, null, null);
INSERT INTO `lx_hitv_order` VALUES ('2', '1', '1000', null, null, '1', '大米', null, null, null);

-- ----------------------------
-- Table structure for `lx_hitv_req_session`
-- ----------------------------
DROP TABLE IF EXISTS `lx_hitv_req_session`;
CREATE TABLE `lx_hitv_req_session` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `action_type` smallint(4) NOT NULL,
  `tv_box_id` mediumint(8) NOT NULL,
  `user_id` mediumint(8) DEFAULT NULL,
  `action_param` char(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lx_hitv_req_session
-- ----------------------------
INSERT INTO `lx_hitv_req_session` VALUES ('1', '0', '1', '1', '看望我', '2');
INSERT INTO `lx_hitv_req_session` VALUES ('2', '0', '1', '1', '大asdasds', '2');
INSERT INTO `lx_hitv_req_session` VALUES ('3', '1', '1', '1', 'ac', '2');
INSERT INTO `lx_hitv_req_session` VALUES ('4', '0', '1', null, null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('5', '0', '1', '1', '大米', '2');
INSERT INTO `lx_hitv_req_session` VALUES ('6', '0', '10000', null, null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('7', '0', '10000', null, null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('8', '0', '10000', null, null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('9', '0', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('10', '0', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('11', '0', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('12', '0', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('13', '0', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('14', '0', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('15', '0', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('16', '0', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('17', '0', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('18', '0', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('19', '0', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('20', '0', '1', null, null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('21', '0', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('22', '0', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('23', '0', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('24', '0', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('25', '0', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('26', '0', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('27', '1', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('28', '1', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('29', '1', '1', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('30', '1', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('31', '2', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('32', '2', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('33', '2', '10000', '1', null, '0');
INSERT INTO `lx_hitv_req_session` VALUES ('34', '2', '10000', '1', null, '0');

-- ----------------------------
-- Table structure for `lx_hitv_user`
-- ----------------------------
DROP TABLE IF EXISTS `lx_hitv_user`;
CREATE TABLE `lx_hitv_user` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `user_nickname` char(16) NOT NULL,
  `user_phone` char(11) NOT NULL,
  `user_password` char(32) NOT NULL,
  `user_sex` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`user_phone`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lx_hitv_user
-- ----------------------------
INSERT INTO `lx_hitv_user` VALUES ('1', '金正日', '15676140127', '123123', '0');

-- ----------------------------
-- Table structure for `lx_hitv_webapp`
-- ----------------------------
DROP TABLE IF EXISTS `lx_hitv_webapp`;
CREATE TABLE `lx_hitv_webapp` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(32) NOT NULL,
  `app_secret` char(32) NOT NULL,
  `app_thumb` varchar(128) NOT NULL,
  `app_info` varchar(128) NOT NULL,
  `is_auth` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lx_hitv_webapp
-- ----------------------------
INSERT INTO `lx_hitv_webapp` VALUES ('1', '广电商城', '123123', 'http://cdn.v2ex.co/avatar/c46e/37f7/74212_large.png?m=1431920585', '无', '');
