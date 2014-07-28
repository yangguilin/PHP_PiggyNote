/*
 Navicat Premium Data Transfer

 Source Server         : piggynote_mysql
 Source Server Type    : MySQL
 Source Server Version : 50148
 Source Host           : bdm-018.hichina.com
 Source Database       : bdm0180543_db

 Target Server Type    : MySQL
 Target Server Version : 50148
 File Encoding         : utf-8

 Date: 07/17/2014 15:02:31 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `piggynote_friendnotes`
-- ----------------------------
DROP TABLE IF EXISTS `piggynote_friendnotes`;
CREATE TABLE `piggynote_friendnotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `details` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `owneruserid` varchar(50) DEFAULT NULL,
  `offeruserid` varchar(50) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `createtime` datetime NOT NULL,
  `closetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=gbk;

SET FOREIGN_KEY_CHECKS = 1;
