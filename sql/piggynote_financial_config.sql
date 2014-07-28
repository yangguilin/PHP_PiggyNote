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

 Date: 07/17/2014 15:02:17 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `piggynote_financial_config`
-- ----------------------------
DROP TABLE IF EXISTS `piggynote_financial_config`;
CREATE TABLE `piggynote_financial_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(50) CHARACTER SET gbk NOT NULL,
  `monthcostplan` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=gbk COLLATE=gbk_bin;

SET FOREIGN_KEY_CHECKS = 1;
