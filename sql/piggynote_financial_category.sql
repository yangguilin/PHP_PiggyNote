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

 Date: 07/17/2014 15:02:11 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `piggynote_financial_category`
-- ----------------------------
DROP TABLE IF EXISTS `piggynote_financial_category`;
CREATE TABLE `piggynote_financial_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(50) COLLATE gbk_bin NOT NULL,
  `categorydata` varchar(5000) COLLATE gbk_bin NOT NULL,
  `lastmodifiedtime` datetime NOT NULL,
  `sortedcategorydata` varchar(5000) COLLATE gbk_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=gbk COLLATE=gbk_bin;

SET FOREIGN_KEY_CHECKS = 1;
