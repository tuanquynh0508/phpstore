/*
SQLyog Ultimate v11.11 (32 bit)
MySQL - 5.5.43-0ubuntu0.14.04.1 : Database - phpstore
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`phpstore` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `phpstore`;

/*Table structure for table `category` */

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `category` */

insert  into `category`(`id`,`title`,`slug`,`is_active`,`created_at`,`updated_at`) values (1,'Danh muc 1','1',1,'2015-09-19 09:26:24','2015-09-19 09:32:01'),(3,'Danh muc 2 A','2',0,'2015-09-19 09:33:27','2015-09-19 11:06:03'),(4,'Danh muc 3 A','3',1,'2015-09-19 09:33:27','2015-09-20 14:41:04'),(5,'Danh muc 10','4',1,'2015-09-19 09:33:27',NULL),(8,'Äiá»‡n thoáº¡i','5',1,'2015-09-19 10:22:48',NULL);

/*Table structure for table `firm` */

DROP TABLE IF EXISTS `firm`;

CREATE TABLE `firm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `firm` */

insert  into `firm`(`id`,`title`,`created_at`,`updated_at`) values (1,'Sony ','2015-09-19 13:02:18','2015-09-19 13:02:59'),(2,'Deawoo','2015-09-19 13:02:35',NULL),(3,'Samsung','2015-09-19 13:02:44',NULL);

/*Table structure for table `order_product` */

DROP TABLE IF EXISTS `order_product`;

CREATE TABLE `order_product` (
  `order_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `price` float NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`order_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_product_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `order_product_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `order_product` */

/*Table structure for table `orders` */

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_tel` varchar(20) NOT NULL,
  `customer_address` text NOT NULL,
  `note` text,
  `order_status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `orders` */

/*Table structure for table `product` */

DROP TABLE IF EXISTS `product`;

CREATE TABLE `product` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `price` float DEFAULT '0',
  `summary` text,
  `content` longtext NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `firm_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`),
  KEY `firm_id` (`firm_id`),
  CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `product_ibfk_2` FOREIGN KEY (`firm_id`) REFERENCES `firm` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `product` */

insert  into `product`(`id`,`title`,`slug`,`thumbnail`,`price`,`summary`,`content`,`is_active`,`created_at`,`updated_at`,`category_id`,`firm_id`) values (1,'Sáº£n pháº©m 1','1','IMac_G5_Rev._A_front.jpg',150000,'TÃ³m táº¯t vÃ­ dá»¥','Chi tiáº¿t sáº£n pháº£m 1',1,'2015-09-20 15:18:55','2015-09-20 15:35:44',3,2),(2,'Sáº£n pháº©m 2','2','336341-apple-imac-27-inch-intel-core-i5-4670-angle.jpg',0,'asfdsadf asdf asdf Ã¡d','fsda fasd fasdf adsf asd fads',1,'2015-09-20 15:44:46',NULL,1,3),(3,'iMac','3','1.jpg',0,'asdfasdfa sdfas d','fasd asdf safsad Ã¡d',1,'2015-09-20 15:45:13','2015-09-21 00:04:15',3,2),(4,'Iphone 4S','4','apple-iphone 5c - 8gb-white-450x350.png',0,'sadfa dfasd fdas fasd','sadfasdfasdfsaddfasd',1,'2015-09-20 15:45:33','2015-09-20 15:46:01',8,2),(5,'iphone 6','5','iPhone-5s-repair-services-same-day-London.jpg',0,'asdfa sdfas fas','fsadfas fsad',1,'2015-09-20 15:45:53',NULL,8,3);

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `passwd` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;