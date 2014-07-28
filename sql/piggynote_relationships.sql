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

 Date: 07/17/2014 15:02:35 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `piggynote_relationships`
-- ----------------------------
DROP TABLE IF EXISTS `piggynote_relationships`;
CREATE TABLE `piggynote_relationships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(50) NOT NULL,
  `friendid` varchar(50) NOT NULL,
  `status` int(11) NOT NULL,
  `lastmodifiedtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=gbk;

SET FOREIGN_KEY_CHECKS = 1;
