/*
SQLyog Ultimate v12.5.1 (64 bit)
MySQL - 10.1.31-MariaDB : Database - qukuailian
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`qukuailian` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `qukuailian`;

/*Table structure for table `qkl_transaction_admin` */

DROP TABLE IF EXISTS `qkl_transaction_admin`;

CREATE TABLE `qkl_transaction_admin` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cash` decimal(8,2) NOT NULL DEFAULT '0.00',
  `coin` decimal(8,2) NOT NULL DEFAULT '0.00',
  `user_trans_id` bigint(20) unsigned NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `qkl_transaction_admin` */

/*Table structure for table `qkl_transaction_user` */

DROP TABLE IF EXISTS `qkl_transaction_user`;

CREATE TABLE `qkl_transaction_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user1_id` int(10) unsigned NOT NULL,
  `user2_id` int(10) unsigned NOT NULL,
  `amount` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `action` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0-"-" 1-"+"',
  `transaction_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1-deposit 2-transfer 3-pay 4-publish 5-accept',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1-payed 2-pending',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rate` decimal(5,2) unsigned NOT NULL DEFAULT '1.00' COMMENT 'exchange rate from yuan to time coin',
  `balance` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT 'User1''s time coin balance.',
  `currency_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1-time coin 2-cash',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4;

/*Data for the table `qkl_transaction_user` */

insert  into `qkl_transaction_user`(`id`,`user1_id`,`user2_id`,`amount`,`action`,`transaction_type`,`state`,`create_time`,`rate`,`balance`,`currency_type`) values 
(1,5,1,1000.00,1,1,1,'2018-05-10 22:13:41',1.00,2000.00,1),
(2,1,5,1000.00,1,1,1,'2018-05-10 22:13:41',1.00,0.00,0),
(3,5,1,1.00,1,1,1,'2018-05-10 23:52:30',1.00,1994.00,1),
(4,1,5,1.00,1,1,1,'2018-05-10 23:52:30',1.00,0.00,0),
(5,5,1,1.00,1,1,1,'2018-05-10 23:52:31',1.00,1995.00,1),
(6,1,5,1.00,1,1,1,'2018-05-10 23:52:31',1.00,0.00,0),
(7,5,1,1.00,1,1,1,'2018-05-10 23:52:32',1.00,1996.00,1),
(8,1,5,1.00,1,1,1,'2018-05-10 23:52:32',1.00,0.00,0),
(9,5,1,1.00,1,1,1,'2018-05-10 23:52:33',1.00,1997.00,1),
(10,1,5,1.00,1,1,1,'2018-05-10 23:52:33',1.00,0.00,0),
(11,5,1,1.00,1,1,1,'2018-05-10 23:52:35',1.00,1998.00,1),
(12,1,5,1.00,1,1,1,'2018-05-10 23:52:35',1.00,0.00,0),
(13,6,5,1.00,1,2,1,'2018-05-11 00:25:17',1.00,0.00,1),
(14,6,5,1.00,1,2,1,'2018-05-11 00:29:27',1.00,1.00,1),
(15,5,6,1.00,0,2,1,'2018-05-11 00:33:48',1.00,1990.00,1),
(16,6,5,1.00,1,2,1,'2018-05-11 00:33:48',1.00,2.00,1),
(17,5,6,1.00,0,2,1,'2018-05-11 00:35:53',1.00,1989.00,1),
(18,6,5,1.00,1,2,1,'2018-05-11 00:35:53',1.00,3.00,1),
(19,5,6,1.00,0,2,1,'2018-05-11 00:37:21',1.00,1988.00,1),
(20,6,5,1.00,1,2,1,'2018-05-11 00:37:21',1.00,4.00,1),
(21,5,6,1.00,0,2,1,'2018-05-11 00:47:52',1.00,1985.00,1),
(22,6,5,1.00,1,2,1,'2018-05-11 00:47:52',1.00,7.00,1),
(23,1,5,1.00,1,1,1,'2018-05-11 00:53:37',1.00,0.00,0),
(24,5,1,1.00,1,1,1,'2018-05-11 00:53:37',1.00,1985.00,1),
(25,1,5,1.00,1,1,1,'2018-05-11 00:53:39',1.00,0.00,0),
(26,5,1,1.00,1,1,1,'2018-05-11 00:53:39',1.00,1985.00,1),
(27,5,6,1.00,0,2,1,'2018-05-11 00:54:59',1.00,1985.00,1),
(28,6,5,1.00,1,2,1,'2018-05-11 00:54:59',1.00,7.00,1),
(29,5,6,1.00,0,2,1,'2018-05-11 00:55:45',1.00,1985.00,1),
(30,6,5,1.00,1,2,1,'2018-05-11 00:55:45',1.00,7.00,1),
(31,1,5,1.00,1,1,1,'2018-05-11 00:58:25',1.00,0.00,0),
(32,5,1,1.00,1,1,1,'2018-05-11 00:58:25',1.00,1985.00,1),
(33,1,5,1.00,1,1,1,'2018-05-11 00:58:27',1.00,0.00,0),
(34,5,1,1.00,1,1,1,'2018-05-11 00:58:27',1.00,1986.00,1),
(35,5,6,100.00,0,2,1,'2018-05-11 00:58:43',1.00,1987.00,1),
(36,6,5,100.00,1,2,1,'2018-05-11 00:58:43',1.00,7.00,1),
(37,5,6,1.00,0,2,1,'2018-05-11 01:01:52',1.00,1886.00,1),
(38,6,5,1.00,1,2,1,'2018-05-11 01:01:52',1.00,108.00,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
