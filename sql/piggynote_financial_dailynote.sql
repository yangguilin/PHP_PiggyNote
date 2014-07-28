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

 Date: 07/17/2014 15:02:21 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `piggynote_financial_dailynote`
-- ----------------------------
DROP TABLE IF EXISTS `piggynote_financial_dailynote`;
CREATE TABLE `piggynote_financial_dailynote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(50) CHARACTER SET gbk NOT NULL,
  `moneytype` varchar(45) CHARACTER SET gbk NOT NULL,
  `amount` float NOT NULL,
  `category` varchar(45) CHARACTER SET gbk NOT NULL,
  `remark` varchar(100) COLLATE gbk_bin DEFAULT NULL,
  `createtime` datetime NOT NULL,
  `lastmodifiedtime` datetime DEFAULT NULL,
  `stattype` int(11) NOT NULL DEFAULT '1' COMMENT '1=normal,2=stage,3=big',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2299 DEFAULT CHARSET=gbk COLLATE=gbk_bin;

SET FOREIGN_KEY_CHECKS = 1;
