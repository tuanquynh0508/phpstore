/*
SQLyog Ultimate v11.11 (64 bit)
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `category` */

insert  into `category`(`id`,`title`,`slug`,`is_active`,`created_at`,`updated_at`) values (1,'Vi xử lý CPU','cpu',1,'2015-09-23 10:00:48','2015-09-23 10:00:52'),(2,'Ram máy tính','ram',1,'2015-09-23 10:01:01','2015-09-23 16:09:17'),(3,'Bo mạch chủ','bo-mach-chu',1,'2015-09-23 10:01:10',NULL),(4,'Màn hình máy tính','man-hinh-may-tinh',0,'2015-09-23 14:54:57',NULL),(5,'Nguồn máy tính','nguon-may-tinh',0,'2015-09-23 14:55:06',NULL);

/*Table structure for table `firm` */

DROP TABLE IF EXISTS `firm`;

CREATE TABLE `firm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `firm` */

insert  into `firm`(`id`,`title`,`created_at`,`updated_at`) values (1,'Intel','2015-09-23 17:45:52',NULL),(2,'Gigabyte','2015-09-23 17:46:03',NULL),(3,'Asus','2015-09-23 17:46:09',NULL),(4,'Sony','2015-09-23 17:46:18',NULL),(5,'HP','2015-09-23 17:46:37','2015-09-23 17:46:57'),(6,'Kingmax','2015-09-24 10:43:08',NULL),(7,'Kingston','2015-09-24 10:43:18',NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `product` */

insert  into `product`(`id`,`title`,`slug`,`thumbnail`,`price`,`summary`,`content`,`is_active`,`created_at`,`updated_at`,`category_id`,`firm_id`) values (1,'Core i5','core-i5','81tnly3dtll-sl1500-.jpg',2200000,'CPU Core i5','CPU Core i5 chi tiết sản phẩm',1,'2015-09-23 18:14:20','2015-09-23 22:54:38',1,1),(2,'Bộ vi xử lý Intel Core i5 4460 / 3,2GHz / 6MB / Sk1150','bo-vi-xu-ly-intel-core-i5-4460--32ghz--6mb--sk1150','81tnly3dtll-sl1500--1.jpg',2500000,'Tóm tắt Bộ vi xử lý Intel Core i5 4460 / 3,2GHz / 6MB / Sk1150','Chi tiết Bộ vi xử lý Intel Core i5 4460 / 3,2GHz / 6MB / Sk1150',1,'2015-09-23 22:56:59','2015-09-23 23:15:27',1,1),(3,'Bộ vi xử lý Core i7 4790 / 4Ghz / 8MB / SK1150','bo-vi-xu-ly-core-i7-4790--4ghz--8mb--sk1150','micro-intel-core-i7-4790.jpg',7509000,'Tóm tắt Bộ vi xử lý Core i7 4790 / 4Ghz / 8MB / SK1150','Chi tiết Bộ vi xử lý Core i7 4790 / 4Ghz / 8MB / SK1150',1,'2015-09-23 23:20:24',NULL,1,1),(8,'RAM Kingston Value 4GB DDR3 Bus 1600','ram-kingston-value-4gb-ddr3-bus-1600','ram-kingston-value-4gb-ddr3-bus-1600.jpg',559000,'Tóm tắt RAM Kingston Value 4GB DDR3 Bus 1600','Chi tiết RAM Kingston Value 4GB DDR3 Bus 1600 A',1,'2015-09-24 10:58:25','2015-09-24 11:15:44',2,7),(10,'Bo mạch chủ GIGABYTE™ GA H97M-D3H','bo-mach-chu-gigabyte-ga-h97m-d3h','bo-mach-chu-gigabyte-ga-h97m-d3h.jpg',2749000,'Bo mạch chủ GIGABYTE™ GA H97M-D3H','Bo mạch chủ GIGABYTE™ GA H97M-<b><font color=\"#0000cc\">D3H</font></b>',1,'2015-09-24 11:21:05','2015-09-24 09:21:48',3,2);

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `reset_token` text,
  `reset_timeout` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`passwd`,`fullname`,`email`,`is_admin`,`is_active`,`reset_token`,`reset_timeout`,`created_at`,`updated_at`) values (1,'admin','94287db147a171de210fa289d5d9bb0e065377795f71e7ede2a2f19a2feb60db','Nguyễn Như Tuấn','tuanquynh0508@gmail.com',1,0,NULL,NULL,'2015-09-25 23:26:58','2015-09-29 15:02:48'),(2,'editor','55c6034c3f2ad82b9c60b21b7a96a92e9629032c4f18b627ef54e4f498db20ef','Nhập liệu','editor@gmail.com',0,1,NULL,NULL,'2015-09-28 22:04:06','2015-09-29 11:37:05');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
