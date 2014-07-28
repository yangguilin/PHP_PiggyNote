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

 Date: 07/17/2014 15:02:02 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `piggynote_authorised_users`
-- ----------------------------
DROP TABLE IF EXISTS `piggynote_authorised_users`;
CREATE TABLE `piggynote_authorised_users` (
  `username` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(40) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `LastAutoTaskExecuteTime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`username`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=gbk;

SET FOREIGN_KEY_CHECKS = 1;
