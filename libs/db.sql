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

insert  into `category`(`id`,`title`,`slug`,`is_active`,`created_at`,`updated_at`) values (1,'Vi xử lý CPU','cpu',1,'2015-09-23 10:00:48','2015-09-23 10:00:52'),(2,'Ram máy tính','ram',1,'2015-09-23 10:01:01','2015-09-23 16:09:17'),(3,'Bo mạch chủ','bo-mach-chu',1,'2015-09-23 10:01:10',NULL),(4,'Màn hình máy tính','man-hinh-may-tinh',0,'2015-09-23 14:54:57',NULL),(5,'Nguồn máy tính','nguon-may-tinh',0,'2015-09-23 14:55:06',NULL),(6,'Bàn phím - Chuột','keyboard-mouser',1,'2015-10-10 18:03:36','2015-10-10 18:05:08'),(7,'Ổ đĩa cứng - HDD','hdd',1,'2015-10-10 18:04:14','2015-10-10 18:06:35'),(8,'Ở DVD, CD','dvd-cd',1,'2015-10-10 18:04:26','2015-10-10 18:04:56'),(9,'Card màn hình - VGA','card-man-hinh-vga',1,'2015-10-10 18:07:21','2015-10-10 18:07:25'),(10,'Card âm thanh - Sound card','card-am-thanh-sound-card',1,'2015-10-10 18:07:38','2015-10-10 18:07:41'),(11,'Loa máy tính','loa-may-tinh',1,'2015-10-10 18:08:11',NULL);

/*Table structure for table `firm` */

DROP TABLE IF EXISTS `firm`;

CREATE TABLE `firm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Data for the table `firm` */

insert  into `firm`(`id`,`title`,`created_at`,`updated_at`) values (1,'Intel','2015-09-23 17:45:52',NULL),(2,'Gigabyte','2015-09-23 17:46:03',NULL),(3,'Asus','2015-09-23 17:46:09',NULL),(4,'Sony','2015-09-23 17:46:18',NULL),(5,'HP','2015-09-23 17:46:37','2015-09-23 17:46:57'),(6,'Kingmax','2015-09-24 10:43:08',NULL),(7,'Kingston','2015-09-24 10:43:18',NULL),(8,'Corsair','2015-10-10 17:24:46',NULL),(9,'GSKILL','2015-10-10 17:24:59',NULL),(10,'Foxconn','2015-10-10 17:31:57',NULL),(11,'MSI','2015-10-10 17:32:10',NULL),(12,'SamSung','2015-10-10 17:51:19',NULL),(13,'Dell','2015-10-10 17:51:46',NULL);

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

insert  into `order_product`(`order_id`,`product_id`,`price`,`quantity`) values (1,3,7509000,1),(1,8,559000,1),(1,10,2749000,1),(2,8,559000,5),(2,10,2749000,5),(3,27,3599000,1),(3,29,0,2),(4,28,0,1),(4,30,1999000,1),(4,31,3099000,1),(5,14,14018300,1),(5,28,0,1),(5,30,1999000,1),(5,33,5299000,1);

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `orders` */

insert  into `orders`(`id`,`customer_name`,`customer_email`,`customer_tel`,`customer_address`,`note`,`order_status`,`created_at`,`updated_at`) values (1,'Nguyễn Văn Test','test@gmail.com','0123456789','Hà Đông, Hà Nội','Chuyển hàng nhanh lên nhé',3,'2015-10-07 20:44:43','2015-10-07 20:46:42'),(2,'Lý Tử Long','tulong@gmail.com','0123456789','HongKong','',3,'2015-10-08 20:46:31','2015-10-08 22:40:58'),(3,'Trâu đất','test@gmail.com','0123456789','Hà Đông, Hà Nội','áddsafasfas',1,'2015-10-10 18:20:52',NULL),(4,'Nguyễn Văn Test','test@gmail.com','0123456789','Hà Đông, Hà Nội','',3,'2015-10-10 21:06:01','2015-10-10 21:06:17'),(5,'Nguyễn Văn Test','test@gmail.com','0123456789','Hà Đông, Hà Nội','',3,'2015-10-11 01:17:42','2015-10-11 01:18:03');

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
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

/*Data for the table `product` */

insert  into `product`(`id`,`title`,`slug`,`thumbnail`,`price`,`summary`,`content`,`is_active`,`created_at`,`updated_at`,`category_id`,`firm_id`) values (1,'Core i5','core-i5','81tnly3dtll-sl1500-.jpg',2200000,'CPU Core i5','CPU Core i5 chi tiết sản phẩm',1,'2015-09-23 18:14:20','2015-09-23 22:54:38',1,1),(2,'Bộ vi xử lý Intel Core i5 4460 / 3,2GHz / 6MB / Sk1150','bo-vi-xu-ly-intel-core-i5-4460--32ghz--6mb--sk1150','81tnly3dtll-sl1500--1.jpg',2500000,'Tóm tắt Bộ vi xử lý Intel Core i5 4460 / 3,2GHz / 6MB / Sk1150','Chi tiết Bộ vi xử lý Intel Core i5 4460 / 3,2GHz / 6MB / Sk1150',1,'2015-09-23 22:56:59','2015-09-23 23:15:27',1,1),(3,'Bộ vi xử lý Core i7 4790 / 4Ghz / 8MB / SK1150','bo-vi-xu-ly-core-i7-4790--4ghz--8mb--sk1150','micro-intel-core-i7-4790.jpg',7509000,'Tóm tắt Bộ vi xử lý Core i7 4790 / 4Ghz / 8MB / SK1150','Chi tiết Bộ vi xử lý Core i7 4790 / 4Ghz / 8MB / SK1150',1,'2015-09-23 23:20:24',NULL,1,1),(8,'RAM Kingston Value 4GB DDR3 Bus 1600','ram-kingston-value-4gb-ddr3-bus-1600','ram-kingston-value-4gb-ddr3-bus-1600.jpg',559000,'Tóm tắt RAM Kingston Value 4GB DDR3 Bus 1600','Chi tiết RAM Kingston Value 4GB DDR3 Bus 1600 A',1,'2015-09-24 10:58:25','2015-09-24 11:15:44',2,7),(10,'Bo mạch chủ GIGABYTE™ GA H97M-D3H','bo-mach-chu-gigabyte-ga-h97m-d3h','bo-mach-chu-gigabyte-ga-h97m-d3h.jpg',2749000,'Bo mạch chủ GIGABYTE™ GA H97M-D3H','Bo mạch chủ GIGABYTE™ GA H97M-<b><font color=\"#0000cc\">D3H</font></b>',1,'2015-09-24 11:21:05','2015-09-24 09:21:48',3,2),(11,'Bộ vi xử lý Intel Pentium G3450 3.40GHz / 3M / Sk1150','bo-vi-xu-ly-intel-pentium-g3450-340ghz--3m--sk1150','bo-vi-xu-ly-intel-pentium-g3450-3-40ghz--3m--sk1150.jpg',1479000,'Bộ vi xử lý Intel Pentium G3450 3.40GHz / 3M / Sk1150','Bộ vi xử lý Intel Pentium G3450 3.40GHz / 3M / Sk1150',1,'2015-10-10 17:01:54',NULL,1,1),(12,'Bộ vi xử lý Intel Pentium G3250 3.20GHz / 3M / Sk1150','bo-vi-xu-ly-intel-pentium-g3250-320ghz--3m--sk1150','3m-cache--intel®-hd-graphic--socket-1150.jpg',1239000,'Bộ vi xử lý Intel Pentium G3250 3.20GHz / 3M / Sk1150','Bộ vi xử lý Intel Pentium G3250 3.20GHz / 3M / Sk1150',1,'2015-10-10 17:03:34',NULL,1,1),(13,'Bộ vi xử lý Intel Xeon E3 1231V3 - 3.4GHz / 8MB / Sk1150','bo-vi-xu-ly-intel-xeon-e3-1231v3---34ghz--8mb--sk1150','81qhwxygp-l-sl1500-.jpg',5959000,'Bộ vi xử lý Intel Xeon E3 1231V3 - 3.4GHz / 8MB / Sk1150','Bộ vi xử lý Intel Xeon E3 1231V3 - 3.4GHz / 8MB / Sk1150',1,'2015-10-10 17:06:46',NULL,1,1),(14,'Bộ vi xử lý Intel Xeon E5 1650V3 - 3.5GHz / 15M / Sk 2011','bo-vi-xu-ly-intel-xeon-e5-1650v3---35ghz--15m--sk-2011','bo-vi-xu-ly-intel®-xeone5.jpg',14018300,'Bộ vi xử lý Intel Xeon E5 1650V3 - 3.5GHz / 15M / Sk 2011','Bộ vi xử lý Intel Xeon E5 1650V3 - 3.5GHz / 15M / Sk 2011',1,'2015-10-10 17:10:21',NULL,1,1),(15,'Bộ vi xử lý Intel Xeon E5 2620V3 - 2.4GHz / 20M / Sk 2011','bo-vi-xu-ly-intel-xeon-e5-2620v3---24ghz--20m--sk-2011','bo-vi-xu-ly-intel®-xeone5-1.jpg',9859000,'Bộ vi xử lý Intel Xeon E5 2620V3 - 2.4GHz / 20M / Sk 2011','Bộ vi xử lý Intel Xeon E5 2620V3 - 2.4GHz / 20M / Sk 2011',1,'2015-10-10 17:11:36',NULL,1,1),(16,'RAM Kingston HyperX Fury Red Kit 8GB 2x4G 1600MHz DDR3','ram-kingston-hyperx-fury-red-kit-8gb-2x4g-1600mhz-ddr3','hyperx-fury-1866mhz-8gb-ddr3-kit-2x4gb-angled.jpg',1759000,'RAM Kingston HyperX Fury Red Kit 8GB 2x4G 1600MHz DDR3','RAM Kingston HyperX Fury Red Kit 8GB 2x4G 1600MHz DDR3',1,'2015-10-10 17:16:45',NULL,2,7),(17,'RAM Kingston HyperX Fury Blue Kit 8GB 2x4G 1600MHz DDR3','ram-kingston-hyperx-fury-blue-kit-8gb-2x4g-1600mhz-ddr3','memoria-kingston-8gb-ddr3-hyper-x-fury-1600mhz.jpg',1759000,'RAM Kingston HyperX Fury Blue Kit 8GB 2x4G 1600MHz DDR3','RAM Kingston HyperX Fury Blue Kit 8GB 2x4G 1600MHz DDR3',1,'2015-10-10 17:18:30',NULL,2,7),(18,'RAM Kingston HyperX Fury Red 4GB 1866MHz DDR3','ram-kingston-hyperx-fury-red-4gb-1866mhz-ddr3','kingston-hyperx-fury.jpg',949000,'RAM Kingston HyperX Fury Red 4GB 1866MHz DDR3','RAM Kingston HyperX Fury Red 4GB 1866MHz DDR3',1,'2015-10-10 17:19:51',NULL,2,7),(19,'RAM Kingston HyperX Fury Blue 8GB 1600MHZ DDR3','ram-kingston-hyperx-fury-blue-8gb-1600mhz-ddr3','kingston-hyperx-fury-8gb1-1200x1200.jpg',1399000,'RAM Kingston HyperX Fury Blue 8GB 1600MHZ DDR3','RAM Kingston HyperX Fury Blue 8GB 1600MHZ DDR3',1,'2015-10-10 17:21:53',NULL,2,7),(20,'RAM Kingston HyperX Fury Black Kit 8GB 2x4G 1600MHz DDR3','ram-kingston-hyperx-fury-black-kit-8gb-2x4g-1600mhz-ddr3','02221.jpg',1759000,'RAM Kingston HyperX Fury Black Kit 8GB 2x4G 1600MHz DDR3','RAM Kingston HyperX Fury Black Kit 8GB 2x4G 1600MHz DDR3',1,'2015-10-10 17:23:51',NULL,2,7),(21,'RAM Corsair Vengeance Kit 4GB 2x2G DDR3 Bus 1600','ram-corsair-vengeance-kit-4gb-2x2g-ddr3-bus-1600','ram-corsair-vengeance-kit-4gb-2x2g-ddr3-bus-1600.jpg',1099000,'RAM Corsair Vengeance Kit 4GB 2x2G DDR3 Bus 1600','RAM Corsair Vengeance Kit 4GB 2x2G DDR3 Bus 1600',1,'2015-10-10 17:26:10',NULL,2,8),(22,'RAM G.SKILL RipjawsX 8GB bus 1600 DDR3','ram-gskill-ripjawsx-8gb-bus-1600-ddr3','memoria-ram-gskill-ripjawsx-roja-8gb.jpg',1729000,'RAM G.SKILL RipjawsX 8GB bus 1600 DDR3','RAM G.SKILL RipjawsX 8GB bus 1600 DDR3',1,'2015-10-10 17:27:26',NULL,2,9),(23,'Mainboard ASUS H81M-E/C/SI','mainboard-asus-h81m-ecsi','mainboard-asus-h81m-e-c-si.jpg',1459000,'Mainboard ASUS H81M-E/C/SI','Mainboard ASUS H81M-E/C/SI',1,'2015-10-10 17:33:26','2015-10-10 17:33:57',3,3),(24,'Mainboard GIGABYTE H81M-DS2','mainboard-gigabyte-h81m-ds2','bo-mach-chu-gigabyte-ga-h81m-ds2.jpg',1649000,'Mainboard GIGABYTE H81M-DS2','Mainboard GIGABYTE H81M-DS2',1,'2015-10-10 17:35:02',NULL,3,2),(25,'Mainboard GIGABYTE Z97X-GAMING 5','mainboard-gigabyte-z97x-gaming-5','bo-mach-chu-gigabyte-z97x-gaming5.jpg',4250000,'Mainboard GIGABYTE Z97X-GAMING 5','Mainboard GIGABYTE Z97X-GAMING 5',1,'2015-10-10 17:38:42',NULL,3,2),(26,'Mainboard MSI B85M-E45','mainboard-msi-b85m-e45','msi-a78m-e45-amd-a78-socket.jpg',1699000,'Mainboard MSI B85M-E45','Mainboard MSI B85M-E45',1,'2015-10-10 17:41:51','2015-10-10 17:44:02',3,11),(27,'Mainboard INTEL - DCCP847DYE','mainboard-intel---dccp847dye','mainboard-intel--dccp847dye.jpg',3599000,'Mainboard INTEL - DCCP847DYE','Mainboard INTEL - DCCP847DYE',1,'2015-10-10 17:46:50',NULL,3,1),(28,'Mainboard FOXCONN H61MXV','mainboard-foxconn-h61mxv','foxconn-h61mxv-20382-1.jpg',0,'Mainboard FOXCONN H61MXV','Mainboard FOXCONN H61MXV',1,'2015-10-10 17:48:26',NULL,3,10),(29,'Mainboard GIGABYTE B75-D3V','mainboard-gigabyte-b75-d3v','maxresdefault.jpg',0,'Mainboard GIGABYTE B75-D3V','Mainboard GIGABYTE B75-D3V',1,'2015-10-10 17:49:57',NULL,3,2),(30,'Màn hình Dell LED E1914H - 18,5\"','man-hinh-dell-led-e1914h---185','man-hinh-dell-20-e2014h.jpg',1999000,'Màn hình Dell LED E1914H - 18,5\"','Màn hình Dell LED E1914H - 18,5\"',1,'2015-10-10 17:53:32',NULL,4,13),(31,'Màn hình Samsung LCD LED S22D300 - 21,5\"','man-hinh-samsung-lcd-led-s22d300---215','s19d300-500x500.jpg',3099000,'Màn hình Samsung LCD LED S22D300 - 21,5\"','Màn hình Samsung LCD LED S22D300 - 21,5\"',1,'2015-10-10 17:55:12',NULL,4,12),(32,'Màn hình Samsung LED LS22E390HS/XV - 21.5\"','man-hinh-samsung-led-ls22e390hsxv---215','143168691632.jpg',3799000,'Màn hình Samsung LED LS22E390HS/XV - 21.5\"','Màn hình Samsung LED LS22E390HS/XV - 21.5\"',1,'2015-10-10 17:57:40',NULL,4,12),(33,'Màn hình Dell LED Ultrasharp U2414H - 23,8\"','man-hinh-dell-led-ultrasharp-u2414h---238','product-s3391.jpg',5299000,'Màn hình Dell LED Ultrasharp U2414H - 23,8\"','Màn hình Dell LED Ultrasharp U2414H - 23,8\"',1,'2015-10-10 17:59:47',NULL,4,13);

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

insert  into `user`(`id`,`username`,`passwd`,`fullname`,`email`,`is_admin`,`is_active`,`reset_token`,`reset_timeout`,`created_at`,`updated_at`) values (1,'admin','94287db147a171de210fa289d5d9bb0e065377795f71e7ede2a2f19a2feb60db','Nguyễn Như Tuấn','tuanquynh0508@gmail.com',1,1,NULL,NULL,'2015-09-25 23:26:58','2015-09-29 15:02:48'),(2,'editor','55c6034c3f2ad82b9c60b21b7a96a92e9629032c4f18b627ef54e4f498db20ef','Nhập liệu','editor@gmail.com',0,1,NULL,NULL,'2015-09-28 22:04:06','2015-09-29 11:37:05');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
