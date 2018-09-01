# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.21)
# Database: classeventie_db
# Generation Time: 2018-06-04 06:53:23 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table b_bookings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `b_bookings`;

CREATE TABLE `b_bookings` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `customers_id` int(11) DEFAULT NULL,
  `course_categories_id` int(11) DEFAULT NULL,
  `courses_id` int(11) DEFAULT NULL,
  `batches_id` int(11) DEFAULT NULL,
  `fees` int(11) DEFAULT NULL,
  `net_fees` int(11) DEFAULT NULL,
  `booking_date` date DEFAULT NULL,
  `batch_title` varchar(256) DEFAULT NULL,
  `batch_description` text,
  `batch_capacity` int(11) DEFAULT NULL,
  `batch_recurring` tinyint(4) DEFAULT NULL,
  `batch_weekdays` text,
  `batch_start_date` date DEFAULT NULL,
  `batch_end_date` date DEFAULT NULL,
  `batch_start_time` time DEFAULT NULL,
  `batch_end_time` time DEFAULT NULL,
  `course_title` varchar(256) DEFAULT NULL,
  `course_category_title` varchar(256) DEFAULT NULL,
  `customer_name` varchar(256) DEFAULT NULL,
  `customer_email` varchar(156) DEFAULT NULL,
  `customer_address` varchar(256) DEFAULT NULL,
  `customer_mobile` varchar(20) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `cancellation` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:disable;1:pending;2:approved;3:refunded;',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table b_bookings_members
# ------------------------------------------------------------

DROP TABLE IF EXISTS `b_bookings_members`;

CREATE TABLE `b_bookings_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(256) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(155) DEFAULT NULL,
  `address` varchar(256) DEFAULT NULL,
  `b_bookings_id` int(11) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table b_bookings_payments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `b_bookings_payments`;

CREATE TABLE `b_bookings_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `b_bookings_id` int(11) DEFAULT NULL,
  `paid_amount` int(11) DEFAULT NULL,
  `total_amount` int(11) NOT NULL DEFAULT '0',
  `payment_type` enum('locally','stripe','paypal') DEFAULT NULL,
  `payment_status` tinyint(4) DEFAULT NULL COMMENT '0:pending;1:successful;2:failed',
  `transactions_id` int(11) DEFAULT NULL,
  `currency` varchar(3) DEFAULT NULL,
  `tax_title` varchar(56) DEFAULT NULL,
  `tax_rate_type` enum('percent','fixed') DEFAULT NULL,
  `tax_rate` tinyint(4) DEFAULT NULL,
  `tax_net_price` enum('including','excluding') DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table batches
# ------------------------------------------------------------

DROP TABLE IF EXISTS `batches`;

CREATE TABLE `batches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) DEFAULT NULL,
  `description` text,
  `fees` int(11) DEFAULT NULL COMMENT 'per customer',
  `capacity` int(11) DEFAULT NULL COMMENT 'max customers',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL COMMENT '24 hours format',
  `end_time` time DEFAULT NULL COMMENT '24 hours format',
  `weekdays` text,
  `recurring` tinyint(1) DEFAULT '0',
  `recurring_type` enum('first_week','second_week','third_week','fourth_week','every_week') NOT NULL DEFAULT 'every_week',
  `status` tinyint(1) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `courses_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

LOCK TABLES `batches` WRITE;
/*!40000 ALTER TABLE `batches` DISABLE KEYS */;

INSERT INTO `batches` (`id`, `title`, `description`, `fees`, `capacity`, `start_date`, `end_date`, `start_time`, `end_time`, `weekdays`, `recurring`, `recurring_type`, `status`, `date_added`, `date_updated`, `courses_id`)
VALUES
	(1,'morning batch','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',500,50,'2018-08-27','2018-12-30','07:00:00','08:00:00','[\"1\",\"2\",\"3\",\"4\",\"5\"]',NULL,'every_week',1,'2017-07-12 09:00:15','2018-05-27 10:41:12',6),
	(2,'evening batch','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',500,50,'2018-07-23','2018-12-30','07:00:00','08:00:00','[\"1\",\"2\",\"3\",\"4\",\"5\"]',NULL,'every_week',1,'2017-07-12 09:01:04','2018-05-27 10:40:51',6),
	(3,'morning batch','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',300,50,'2018-07-30','2018-12-30','06:00:00','07:00:00','[\"1\",\"2\",\"3\",\"4\",\"5\"]',NULL,'every_week',1,'2017-07-12 09:03:59','2018-05-27 10:39:54',8),
	(4,'afternoon batch','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',350,30,'2018-06-26','2018-12-30','13:00:00','15:00:00','[\"1\",\"2\",\"3\",\"6\"]',NULL,'every_week',1,'2017-07-12 09:05:56','2018-05-27 10:39:09',4),
	(5,'morning batch','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',550,50,'2018-06-25','2018-12-31','07:00:00','09:00:00','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\"]',NULL,'every_week',1,'2017-07-12 09:06:59','2018-05-27 10:03:07',5),
	(6,'morning batch','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',300,40,'2018-11-23','2018-12-31','06:00:00','07:00:00','[\"1\",\"2\",\"3\",\"4\"]',NULL,'every_week',1,'2017-07-12 09:09:18','2018-05-27 10:43:02',7),
	(7,'evening batch','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',450,20,'2018-10-26','2018-12-31','17:00:00','18:00:00','[\"1\",\"2\",\"3\",\"4\",\"6\"]',NULL,'every_week',1,'2017-07-12 09:10:45','2018-05-27 10:42:33',1),
	(8,'morning batch','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',390,25,'2018-08-24','2018-12-31','10:00:00','11:00:00','[\"1\",\"2\",\"3\",\"4\",\"6\"]',NULL,'every_week',1,'2017-07-12 09:11:47','2018-05-27 10:41:37',2),
	(9,'evening batch','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',390,25,'2018-09-24','2018-12-29','16:00:00','18:00:00','[\"1\",\"2\",\"3\",\"4\",\"6\"]',NULL,'every_week',1,'2017-07-12 09:12:44','2018-05-27 10:42:09',2),
	(10,'personality development morning batch','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',25,100,'2018-06-24','2018-09-30','08:00:00','22:00:00','[\"0\",\"1\",\"2\",\"3\",\"4\",\"5\",\"6\"]',0,'every_week',1,'2018-05-27 12:43:24','2018-05-29 08:56:09',9),
	(11,'test batch recursive','<p>sadasdasd</p>',40,400,'2018-06-01','2018-09-30','06:00:00','09:00:00','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\"]',1,'second_week',1,'2018-05-28 15:11:57','2018-05-28 15:42:18',6),
	(12,'batch 1','<p>sdasadsda</p>',32,400,'2018-06-21','2018-07-11','05:00:00','10:00:00','[\"0\",\"1\",\"2\",\"3\",\"4\",\"5\",\"6\"]',1,'every_week',1,'2018-05-29 11:12:42','2018-05-29 11:12:42',9),
	(13,'batch 2','<p>asdasdasd</p>',32,32,'2018-06-19','2018-07-19','07:00:00','09:00:00','[\"0\",\"1\",\"2\",\"3\",\"4\",\"5\",\"6\"]',0,'every_week',1,'2018-05-29 11:13:22','2018-05-29 11:13:22',9);

/*!40000 ALTER TABLE `batches` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table batches_tutors
# ------------------------------------------------------------

DROP TABLE IF EXISTS `batches_tutors`;

CREATE TABLE `batches_tutors` (
  `users_id` int(11) DEFAULT NULL,
  `batches_id` int(11) DEFAULT NULL,
  UNIQUE KEY `users_id` (`users_id`,`batches_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `batches_tutors` WRITE;
/*!40000 ALTER TABLE `batches_tutors` DISABLE KEYS */;

INSERT INTO `batches_tutors` (`users_id`, `batches_id`)
VALUES
	(38,1),
	(38,5),
	(38,13),
	(39,1),
	(39,2),
	(39,5),
	(39,12),
	(40,3),
	(40,5),
	(41,3),
	(41,5),
	(41,6),
	(41,11),
	(41,12),
	(42,7),
	(42,8),
	(42,9),
	(42,13),
	(43,4),
	(44,10);

/*!40000 ALTER TABLE `batches_tutors` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table blogs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `blogs`;

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) DEFAULT NULL,
  `slug` varchar(512) DEFAULT NULL,
  `content` mediumtext,
  `users_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:disable;1:published;2:pending',
  `image` varchar(256) DEFAULT NULL,
  `meta_title` varchar(128) DEFAULT NULL,
  `meta_tags` varchar(256) DEFAULT NULL,
  `meta_description` text,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;

INSERT INTO `blogs` (`id`, `title`, `slug`, `content`, `users_id`, `status`, `image`, `meta_title`, `meta_tags`, `meta_description`, `date_added`, `date_updated`)
VALUES
	(1,'Neque porro quisquam est qui','neque-porro-quisquam-est-qui','<h5>\"There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...\"</h5>\r\n<hr />\r\n<div id=\"Content\">\r\n<div class=\"boxed\">\r\n<div id=\"lipsum\">\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec bibendum in lacus convallis dignissim. Praesent finibus tincidunt lacus, vitae suscipit arcu mollis eget. Aliquam et felis pulvinar, semper quam ut, rutrum metus. Morbi non iaculis odio, ac consectetur ante. Proin iaculis gravida ex. Vestibulum eu lobortis enim. Nulla ultrices dignissim mauris sed vulputate. Nam blandit velit at ipsum posuere vehicula. Quisque eu sapien nec lectus mattis malesuada at maximus lorem. Ut augue lacus, dapibus at eleifend vitae, accumsan quis lacus.</p>\r\n<p>Sed elementum enim finibus nisi laoreet, nec laoreet nisl tincidunt. Suspendisse porta fermentum nibh. Sed congue magna nisl, eu tempor ex pellentesque ac. Ut semper orci elit, ut viverra metus posuere ac. Maecenas eleifend arcu lacinia turpis dictum, eu viverra nisl rutrum. Proin egestas diam eu arcu lacinia, id pellentesque tortor vestibulum. Nam velit enim, placerat eu dignissim at, consequat at odio. Aenean massa lacus, placerat vitae tincidunt vel, elementum nec odio. Aenean dignissim enim non eros fringilla pharetra. Suspendisse potenti. Cras vel velit sit amet lacus tristique faucibus. Suspendisse tellus metus, blandit semper viverra eu, volutpat sed orci.</p>\r\n<p>Cras ante sapien, iaculis ac suscipit non, viverra ac dui. Proin accumsan nisi felis, commodo fringilla elit blandit id. Duis vestibulum cursus dapibus. Mauris metus sapien, sollicitudin eget dolor nec, lacinia auctor erat. Praesent eu elit nec ligula convallis mollis sed eu massa. Maecenas malesuada lacus orci, id posuere augue varius at. Fusce ac dolor sed tellus pharetra laoreet ac ut orci.</p>\r\n<p>Interdum et malesuada fames ac ante ipsum primis in faucibus. Nullam nunc justo, fermentum non tellus eget, suscipit congue diam. Maecenas blandit, tortor non condimentum aliquam, leo sem porttitor dui, nec ultrices justo lectus in quam. Quisque pharetra consectetur ante venenatis blandit. Fusce blandit orci et dui rutrum, vitae efficitur sapien dignissim. Pellentesque in porta ligula. Praesent sed dignissim turpis, eu rutrum tellus. Fusce volutpat sem at augue tincidunt rhoncus.</p>\r\n<p>Donec vitae velit velit. Phasellus sed ligula at nulla faucibus blandit. Cras nec egestas mi. Morbi sit amet erat malesuada, porttitor magna quis, placerat tellus. Ut sit amet risus finibus, bibendum enim malesuada, fermentum magna. Suspendisse potenti. Donec quam metus, ullamcorper id pellentesque condimentum, congue vehicula nunc. Nunc sit amet ligula imperdiet nibh dignissim mollis. Donec fringilla felis placerat pulvinar tristique. Proin faucibus purus quam, sit amet imperdiet arcu dictum eu. Donec ullamcorper, mauris vel hendrerit maximus, enim arcu pretium ante, quis sodales odio est et mi. In hac habitasse platea dictumst. Cras id nulla tristique, pellentesque erat quis, scelerisque leo. Morbi quis tortor quis ante fringilla maximus eu at nibh.</p>\r\n</div>\r\n</div>\r\n</div>',45,1,'1499843849677.jpeg','Neque porro quisquam est qui dolorem ipsum quia dolor sit amet','Neque porro quisquam est qui dolorem ipsum quia dolor sit amet','Cras ante sapien, iaculis ac suscipit non, viverra ac dui. Proin accumsan nisi felis, commodo fringilla elit blandit id. Duis vestibulum cursus dapibus. Mauris metus sapien, sollicitudin eget dolor nec, lacinia auctor erat. Praesent eu elit nec ligula convallis mollis sed eu massa. Maecenas malesuada lacus orci, id posuere augue varius at. Fusce ac dolor sed tellus pharetra laoreet ac ut orci.','2017-07-12 12:47:29','2017-07-12 13:22:06'),
	(2,'Sed elementum enim finibus nisi laoreet','sed-elementum-enim-finibus-nisi-laoreet','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec bibendum in lacus convallis dignissim. Praesent finibus tincidunt lacus, vitae suscipit arcu mollis eget. Aliquam et felis pulvinar, semper quam ut, rutrum metus. Morbi non iaculis odio, ac consectetur ante. Proin iaculis gravida ex. Vestibulum eu lobortis enim. Nulla ultrices dignissim mauris sed vulputate. Nam blandit velit at ipsum posuere vehicula. Quisque eu sapien nec lectus mattis malesuada at maximus lorem. Ut augue lacus, dapibus at eleifend vitae, accumsan quis lacus.</p>\r\n<p>Sed elementum enim finibus nisi laoreet, nec laoreet nisl tincidunt. Suspendisse porta fermentum nibh. Sed congue magna nisl, eu tempor ex pellentesque ac. Ut semper orci elit, ut viverra metus posuere ac. Maecenas eleifend arcu lacinia turpis dictum, eu viverra nisl rutrum. Proin egestas diam eu arcu lacinia, id pellentesque tortor vestibulum. Nam velit enim, placerat eu dignissim at, consequat at odio. Aenean massa lacus, placerat vitae tincidunt vel, elementum nec odio. Aenean dignissim enim non eros fringilla pharetra. Suspendisse potenti. Cras vel velit sit amet lacus tristique faucibus. Suspendisse tellus metus, blandit semper viverra eu, volutpat sed orci.</p>\r\n<p>Cras ante sapien, iaculis ac suscipit non, viverra ac dui. Proin accumsan nisi felis, commodo fringilla elit blandit id. Duis vestibulum cursus dapibus. Mauris metus sapien, sollicitudin eget dolor nec, lacinia auctor erat. Praesent eu elit nec ligula convallis mollis sed eu massa. Maecenas malesuada lacus orci, id posuere augue varius at. Fusce ac dolor sed tellus pharetra laoreet ac ut orci.</p>\r\n<p>Interdum et malesuada fames ac ante ipsum primis in faucibus. Nullam nunc justo, fermentum non tellus eget, suscipit congue diam. Maecenas blandit, tortor non condimentum aliquam, leo sem porttitor dui, nec ultrices justo lectus in quam. Quisque pharetra consectetur ante venenatis blandit. Fusce blandit orci et dui rutrum, vitae efficitur sapien dignissim. Pellentesque in porta ligula. Praesent sed dignissim turpis, eu rutrum tellus. Fusce volutpat sem at augue tincidunt rhoncus.</p>\r\n<p>Donec vitae velit velit. Phasellus sed ligula at nulla faucibus blandit. Cras nec egestas mi. Morbi sit amet erat malesuada, porttitor magna quis, placerat tellus. Ut sit amet risus finibus, bibendum enim malesuada, fermentum magna. Suspendisse potenti. Donec quam metus, ullamcorper id pellentesque condimentum, congue vehicula nunc. Nunc sit amet ligula imperdiet nibh dignissim mollis. Donec fringilla felis placerat pulvinar tristique. Proin faucibus purus quam, sit amet imperdiet arcu dictum eu. Donec ullamcorper, mauris vel hendrerit maximus, enim arcu pretium ante, quis sodales odio est et mi. In hac habitasse platea dictumst. Cras id nulla tristique, pellentesque erat quis, scelerisque leo. Morbi quis tortor quis ante fringilla maximus eu at nibh.</p>',45,1,'1499843895935.jpeg','Sed elementum enim finibus nisi laoreet','Sed elementum enim finibus nisi laoreet','Sed elementum enim finibus nisi laoreet','2017-07-12 12:48:15','2017-07-12 13:21:08'),
	(3,'Phasellus sed ligula at nulla faucibus blandit','phasellus-sed-ligula-at-nulla-faucibus-blandit','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec bibendum in lacus convallis dignissim. Praesent finibus tincidunt lacus, vitae suscipit arcu mollis eget. Aliquam et felis pulvinar, semper quam ut, rutrum metus. Morbi non iaculis odio, ac consectetur ante. Proin iaculis gravida ex. Vestibulum eu lobortis enim. Nulla ultrices dignissim mauris sed vulputate. Nam blandit velit at ipsum posuere vehicula. Quisque eu sapien nec lectus mattis malesuada at maximus lorem. Ut augue lacus, dapibus at eleifend vitae, accumsan quis lacus.</p>\r\n<p>Sed elementum enim finibus nisi laoreet, nec laoreet nisl tincidunt. Suspendisse porta fermentum nibh. Sed congue magna nisl, eu tempor ex pellentesque ac. Ut semper orci elit, ut viverra metus posuere ac. Maecenas eleifend arcu lacinia turpis dictum, eu viverra nisl rutrum. Proin egestas diam eu arcu lacinia, id pellentesque tortor vestibulum. Nam velit enim, placerat eu dignissim at, consequat at odio. Aenean massa lacus, placerat vitae tincidunt vel, elementum nec odio. Aenean dignissim enim non eros fringilla pharetra. Suspendisse potenti. Cras vel velit sit amet lacus tristique faucibus. Suspendisse tellus metus, blandit semper viverra eu, volutpat sed orci.</p>\r\n<p>Cras ante sapien, iaculis ac suscipit non, viverra ac dui. Proin accumsan nisi felis, commodo fringilla elit blandit id. Duis vestibulum cursus dapibus. Mauris metus sapien, sollicitudin eget dolor nec, lacinia auctor erat. Praesent eu elit nec ligula convallis mollis sed eu massa. Maecenas malesuada lacus orci, id posuere augue varius at. Fusce ac dolor sed tellus pharetra laoreet ac ut orci.</p>\r\n<p>Interdum et malesuada fames ac ante ipsum primis in faucibus. Nullam nunc justo, fermentum non tellus eget, suscipit congue diam. Maecenas blandit, tortor non condimentum aliquam, leo sem porttitor dui, nec ultrices justo lectus in quam. Quisque pharetra consectetur ante venenatis blandit. Fusce blandit orci et dui rutrum, vitae efficitur sapien dignissim. Pellentesque in porta ligula. Praesent sed dignissim turpis, eu rutrum tellus. Fusce volutpat sem at augue tincidunt rhoncus.</p>\r\n<p>Donec vitae velit velit. Phasellus sed ligula at nulla faucibus blandit. Cras nec egestas mi. Morbi sit amet erat malesuada, porttitor magna quis, placerat tellus. Ut sit amet risus finibus, bibendum enim malesuada, fermentum magna. Suspendisse potenti. Donec quam metus, ullamcorper id pellentesque condimentum, congue vehicula nunc. Nunc sit amet ligula imperdiet nibh dignissim mollis. Donec fringilla felis placerat pulvinar tristique. Proin faucibus purus quam, sit amet imperdiet arcu dictum eu. Donec ullamcorper, mauris vel hendrerit maximus, enim arcu pretium ante, quis sodales odio est et mi. In hac habitasse platea dictumst. Cras id nulla tristique, pellentesque erat quis, scelerisque leo. Morbi quis tortor quis ante fringilla maximus eu at nibh.</p>',45,1,'1499843931377.jpeg','Phasellus sed ligula at nulla faucibus blandit','Phasellus sed ligula at nulla faucibus blandit','Phasellus sed ligula at nulla faucibus blandit','2017-07-12 12:48:51','2017-07-12 13:21:12'),
	(4,'Members can add their own blogs','members-can-add-their-own-blogs','<h1>Lorem Ipsum</h1>\r\n<h4>\"Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...\"</h4>\r\n<h5>\"There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...\"</h5>\r\n<hr />\r\n<div id=\"Content\">\r\n<div id=\"Panes\">\r\n<div>\r\n<h2>What is Lorem Ipsum?</h2>\r\n<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n</div>\r\n<div>\r\n<h2>Why do we use it?</h2>\r\n<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>\r\n</div>\r\n</div>\r\n</div>',1,0,'1527663067526.jpg','Members can add their own blogs','Members can add their own blogs','Members can add their own blogs','2018-05-30 12:21:08','2018-05-30 12:21:08');

/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table captcha
# ------------------------------------------------------------

DROP TABLE IF EXISTS `captcha`;

CREATE TABLE `captcha` (
  `captcha_id` bigint(13) unsigned NOT NULL AUTO_INCREMENT,
  `captcha_time` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `word` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`captcha_id`),
  KEY `word` (`word`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

LOCK TABLES `captcha` WRITE;
/*!40000 ALTER TABLE `captcha` DISABLE KEYS */;

INSERT INTO `captcha` (`captcha_id`, `captcha_time`, `ip_address`, `word`)
VALUES
	(1,1499845980,'::1','X7G1G'),
	(2,1499846013,'::1','876Wk'),
	(3,1499867787,'::1','rvCB3'),
	(4,1527401207,'127.0.0.1','8G4q7'),
	(5,1527401618,'127.0.0.1','9T5Ct');

/*!40000 ALTER TABLE `captcha` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ce_sessn
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ce_sessn`;

CREATE TABLE `ce_sessn` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `anonym_sessn_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


# Dump of table controllers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `controllers`;

CREATE TABLE `controllers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

LOCK TABLES `controllers` WRITE;
/*!40000 ALTER TABLE `controllers` DISABLE KEYS */;

INSERT INTO `controllers` (`id`, `name`)
VALUES
	(1,'categories'),
	(2,'courses'),
	(3,'batches'),
	(4,'events'),
	(5,'eventtypes'),
	(6,'bbookings'),
	(7,'ebookings'),
	(8,'users'),
	(9,'testimonials'),
	(10,'gallaries'),
	(11,'blogs'),
	(12,'pages'),
	(13,'faqs'),
	(14,'languages'),
	(15,'emailtemplates'),
	(16,'currencies'),
	(17,'customfields'),
	(18,'taxes'),
	(19,'settings');

/*!40000 ALTER TABLE `controllers` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table course_categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `course_categories`;

CREATE TABLE `course_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) DEFAULT NULL,
  `description` text,
  `image` varchar(256) DEFAULT NULL,
  `icon` varchar(256) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

LOCK TABLES `course_categories` WRITE;
/*!40000 ALTER TABLE `course_categories` DISABLE KEYS */;

INSERT INTO `course_categories` (`id`, `title`, `description`, `image`, `icon`, `parent_id`, `status`, `date_added`, `date_updated`)
VALUES
	(1,'dance','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>',NULL,NULL,0,1,'2017-07-11 18:15:32','2017-07-11 18:15:32'),
	(2,'western dance','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>',NULL,NULL,1,1,'2017-07-11 18:16:08','2017-07-11 18:16:08'),
	(3,'jazz dance','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>',NULL,NULL,1,1,'2017-07-11 18:16:28','2017-07-11 18:33:25'),
	(4,'music','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>',NULL,NULL,0,1,'2017-07-11 18:17:33','2017-07-11 18:17:33'),
	(5,'instrumental music','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>',NULL,NULL,4,1,'2017-07-11 18:18:02','2017-07-11 18:18:02'),
	(6,'classical music','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>',NULL,NULL,4,1,'2017-07-11 18:18:21','2017-07-11 18:18:21'),
	(7,'fitness','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>',NULL,NULL,0,1,'2017-07-11 18:19:32','2017-07-11 18:19:32'),
	(8,'man workout','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>',NULL,NULL,7,1,'2017-07-11 18:19:56','2017-07-11 18:49:45'),
	(9,'woman workout','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>',NULL,NULL,7,1,'2017-07-11 18:20:19','2017-07-11 18:49:35'),
	(10,'yoga','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>',NULL,NULL,0,1,'2017-07-11 18:21:25','2017-07-11 18:21:25'),
	(11,'man yoga','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>',NULL,NULL,10,1,'2017-07-11 18:21:53','2017-07-11 18:21:53'),
	(12,'woman yoga','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>',NULL,NULL,10,1,'2017-07-11 18:22:10','2017-07-11 18:22:10'),
	(13,'body building','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>',NULL,NULL,8,1,'2017-07-11 18:56:23','2017-07-11 18:56:23'),
	(14,'strength training','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum&nbsp;dolor&nbsp;sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>',NULL,NULL,9,1,'2017-07-11 18:56:57','2017-07-11 18:56:57'),
	(15,'personality development','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum&nbsp;dolor&nbsp;sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>',NULL,NULL,0,1,'2018-05-27 12:33:23','2018-05-27 12:33:23');

/*!40000 ALTER TABLE `course_categories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table courses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `courses`;

CREATE TABLE `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) DEFAULT NULL,
  `description` text,
  `images` text,
  `course_categories_id` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `meta_title` varchar(128) DEFAULT NULL,
  `meta_tags` varchar(256) DEFAULT NULL,
  `meta_description` text,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;

INSERT INTO `courses` (`id`, `title`, `description`, `images`, `course_categories_id`, `status`, `featured`, `meta_title`, `meta_tags`, `meta_description`, `date_added`, `date_updated`)
VALUES
	(1,'hip hop dance','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>','[\"1499778062334.jpg\",\"1499778062274.jpg\",\"1499778062287.jpg\"]',2,1,1,'Dance - Western Dance - Hip Hop Dance','dance - western dance - hip hop dance','Lorem Ipsum is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','2017-07-11 18:31:02','2017-07-11 18:31:02'),
	(2,'swing and boogie woogie','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>','[\"1499778477137.jpg\",\"1499778478647.jpg\",\"1499778478116.jpg\"]',3,1,0,'Dance - Jazz Dance - Swing and Boogie Woogie','dance - jazz dance - swing and boogie woogie','Lorem Ipsum is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','2017-07-11 18:37:58','2017-07-11 18:37:58'),
	(3,'orchestra group','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>','[\"1499778830977.jpg\",\"149977883099.jpg\",\"1499778830934.jpg\"]',5,1,1,'Music - Instrumental Music - Orchestra Group','music - instrumental music - orchestra group','Lorem Ipsum is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','2017-07-11 18:43:52','2017-07-11 18:43:52'),
	(4,'contemporary classical music','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>','[\"1499779107189.jpg\",\"149977910821.jpg\",\"1499779108459.jpg\"]',6,1,0,'Music - Classical Music - Contemporary Classical Music','music - classical music - contemporary classical music','Lorem Ipsum is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','2017-07-11 18:48:29','2017-07-11 18:48:29'),
	(5,'full body workout','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum&nbsp;dolor&nbsp;sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>','[\"1499779796577.jpg\",\"1499779796956.jpg\",\"1499779797457.jpg\"]',13,1,0,'Fitness - Man Workout - Body Building - Full Body Workout','fitness - man workout - body building - full body workout','Lorem Ipsum is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.\r\n\r\nContrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.\r\n\r\nThe standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.','2017-07-11 18:59:57','2018-05-29 14:25:04'),
	(6,'aerobics & weight loss','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>','[\"1499780081634.jpg\",\"1499780081677.jpg\",\"1499780081969.jpg\"]',14,1,0,'Fitness - Woman Workout - Strength Training - Aerobics & Weight Loss','fitness - woman workout - strength training - aerobics & weight loss','Lorem Ipsum is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','2017-07-11 19:04:41','2018-05-29 14:24:33'),
	(7,'hatha yoga','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum&nbsp;dolor&nbsp;sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>','[\"1499780263691.jpg\",\"1499780263939.jpg\",\"1499780263666.jpg\"]',12,1,0,'Hatha Yoga','hatha yoga','Lorem Ipsum is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','2017-07-11 19:07:44','2017-07-11 19:07:44'),
	(8,'ashtanga aka power yoga','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum&nbsp;dolor&nbsp;sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>','[\"1499780484849.jpg\",\"1499780485243.jpg\",\"1499780485108.jpg\"]',11,1,0,'Ashtanga (aka Power Yoga)','ashtanga (aka power yoga)','The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.','2017-07-11 19:11:25','2017-07-15 17:53:44'),
	(9,'personality development repetitive classes','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum&nbsp;dolor&nbsp;sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>','[\"152740508855.jpeg\",\"1527405088493.jpeg\",\"1527405088506.jpeg\",\"1527405088966.jpeg\"]',15,1,1,'Personality Development Repetitive Classes','personality development repetitive classes','Personality Development Repetitive Classes','2018-05-27 12:41:28','2018-05-29 14:26:16'),
	(10,'personality development 2','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum&nbsp;dolor&nbsp;sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>','[\"152758443813.jpeg\",\"1527584438435.jpg\"]',15,1,0,'','','','2018-05-29 14:30:38','2018-05-29 14:30:38'),
	(11,'personality development 3','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum&nbsp;dolor&nbsp;sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>','[\"152758448680.jpg\"]',15,1,0,'','','','2018-05-29 14:31:26','2018-05-29 14:31:26'),
	(12,'personality development 5','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummied text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, connector, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubted source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum&nbsp;dolor&nbsp;sit amet..\", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>','[\"1527584516628.jpg\"]',15,1,0,'','','','2018-05-29 14:31:36','2018-05-29 14:31:56');

/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table currencies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `currencies`;

CREATE TABLE `currencies` (
  `iso_code` varchar(3) NOT NULL,
  `symbol` varchar(3) DEFAULT NULL,
  `unicode` varchar(8) DEFAULT NULL,
  `position` varchar(6) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`iso_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `currencies` WRITE;
/*!40000 ALTER TABLE `currencies` DISABLE KEYS */;

INSERT INTO `currencies` (`iso_code`, `symbol`, `unicode`, `position`, `status`, `date_updated`)
VALUES
	('AED','د.إ','','after',1,'2017-06-17 19:20:22'),
	('ANG','ƒ','&#x0192;','before',1,'2017-06-17 19:20:32'),
	('AOA','AOA','AOA','before',1,'2017-04-28 18:22:34'),
	('ARS','$','&#36;','before',1,'2017-04-28 16:55:02'),
	('AUD','$','&#36;','before',1,'2017-04-28 16:55:02'),
	('BAM','KM','KM','before',1,'2017-04-28 16:55:02'),
	('BBD','$','&#36;','before',1,'2017-04-28 16:55:02'),
	('BGL','лв','&#1083;&','before',1,'2017-04-28 16:55:02'),
	('BHD','BD','BD','after',1,'2017-04-28 16:55:02'),
	('BND','$','&#36;','before',1,'2017-04-28 16:55:02'),
	('BRL','R$','R&#36;','before',1,'2017-04-28 16:55:02'),
	('CAD','$','&#36;','before',1,'2017-04-28 16:55:02'),
	('CHF','Fr','Fr','before',1,'2017-04-28 16:55:02'),
	('CLF','UF','UF','after',1,'2017-04-28 16:55:02'),
	('CLP','$','&#36;','before',1,'2017-04-28 16:55:02'),
	('CNY','¥','&#165;','before',1,'2017-04-28 16:55:02'),
	('COP','$','&#36;','before',1,'2017-04-28 16:55:02'),
	('CRC','₡','&#x20A1;','before',1,'2017-04-28 16:55:02'),
	('CZK','Kč','K&#269;','after',1,'2017-04-28 16:55:02'),
	('DKK','kr','kr','before',1,'2017-04-28 16:55:02'),
	('EEK','KR','KR','before',1,'2017-04-28 16:55:02'),
	('EGP','E£','E&#163;','before',1,'2017-04-28 16:55:02'),
	('EUR','€','&#128;','before',1,'2017-04-28 16:55:02'),
	('FJD','FJ$','FJ&#36;','before',1,'2017-04-28 16:55:02'),
	('GBP','£','&#163;','before',1,'2017-04-28 16:55:02'),
	('GTQ','Q','Q','before',1,'2017-04-28 16:55:02'),
	('HKD','$','&#36;','before',1,'2017-04-28 16:55:02'),
	('HRK','kn','kn','before',1,'2017-04-28 16:55:02'),
	('HUF','Ft','Ft','before',1,'2017-04-28 16:55:02'),
	('IDR','Rp','Rp','before',1,'2017-04-28 16:55:02'),
	('ILS','₪','&#x20AA;','before',1,'2017-04-28 16:55:02'),
	('INR','Rs','Rs','before',1,'2017-04-28 16:55:02'),
	('JOD','د.ا','','',1,'2017-04-28 16:55:02'),
	('JPY','¥','&#165;','before',1,'2017-04-28 16:55:02'),
	('KES','KSh','Ksh','before',1,'2017-04-28 16:55:02'),
	('KRW','₩','&#x20A9;','before',1,'2017-04-28 16:55:02'),
	('KWD','KD','KD','after',1,'2017-04-28 16:55:02'),
	('KYD','$','&#36;','before',1,'2017-04-28 16:55:02'),
	('LTL','Lt','Lt','before',1,'2017-04-28 16:55:02'),
	('LVL','Ls','Ls','before',1,'2017-04-28 16:55:02'),
	('MAD','د.م','','',1,'2017-04-28 16:55:02'),
	('MVR','Rf','Rf','before',1,'2017-04-28 16:55:02'),
	('MXN','$','&#36;','before',1,'2017-04-28 16:55:02'),
	('MYR','RM','RM','before',1,'2017-04-28 16:55:02'),
	('NGN','₦','&#x20A6;','before',1,'2017-04-28 16:55:02'),
	('NOK','kr','kr','before',1,'2017-04-28 16:55:02'),
	('NZD','$','&#36;','before',1,'2017-04-28 16:55:02'),
	('OMR','OMR','&#65020;','after',1,'2017-04-28 16:55:02'),
	('PEN','S/.','S/.','before',1,'2017-04-28 16:55:02'),
	('PHP','₱','&#x20B1;','before',1,'2017-04-28 16:55:02'),
	('PLN','zł','Z&#322;','before',1,'2017-04-28 16:55:02'),
	('QAR','QAR','&#65020;','after',1,'2017-04-28 16:55:02'),
	('RON','lei','lei','before',1,'2017-04-28 16:55:02'),
	('RUB','руб','&#1088;&','after',1,'2017-04-28 16:55:02'),
	('SAR','SAR','&#65020;','after',1,'2017-04-28 16:55:02'),
	('SEK','kr','kr','before',1,'2017-04-28 16:55:02'),
	('SGD','$','&#36;','before',1,'2017-04-28 16:55:02'),
	('THB','฿','&#322;','before',1,'2017-04-28 16:55:02'),
	('TRY','TL','TL','before',1,'2017-04-28 16:55:02'),
	('TTD','$','&#36;','before',1,'2017-04-28 16:55:02'),
	('TWD','$','&#36;','before',1,'2017-04-28 16:55:02'),
	('UAH','₴','&#8372;','before',1,'2017-04-28 16:55:02'),
	('USD','$','&#36;','before',1,'2017-04-28 16:55:02'),
	('VEF','Bs ','Bs.','before',1,'2017-04-28 16:55:02'),
	('VND','₫','&#x20AB;','before',1,'2017-04-28 16:55:02'),
	('XCD','$','&#36;','before',1,'2017-04-28 16:55:02'),
	('ZAR','R','R','before',1,'2017-04-28 16:55:02');

/*!40000 ALTER TABLE `currencies` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table custom_fields
# ------------------------------------------------------------

DROP TABLE IF EXISTS `custom_fields`;

CREATE TABLE `custom_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `input_type` enum('input','textarea','radio','dropdown','file','email','checkbox') CHARACTER SET latin1 DEFAULT NULL,
  `options` text COMMENT 'Use for radio and dropdown: key|value on each line',
  `is_numeric` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'forces numeric keypad on mobile devices',
  `show_editor` enum('0','1') NOT NULL DEFAULT '0',
  `help_text` varchar(256) DEFAULT NULL,
  `validation` text,
  `label` varchar(128) DEFAULT NULL,
  `value` text COMMENT 'If translate is 1, just start with your default language',
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table e_bookings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `e_bookings`;

CREATE TABLE `e_bookings` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `customers_id` int(11) DEFAULT NULL,
  `event_types_id` int(11) DEFAULT NULL,
  `events_id` int(11) DEFAULT NULL,
  `fees` int(11) DEFAULT NULL,
  `net_fees` int(11) DEFAULT NULL,
  `booking_date` date DEFAULT NULL,
  `event_title` varchar(256) DEFAULT NULL,
  `event_description` text,
  `event_capacity` int(11) DEFAULT NULL,
  `event_weekdays` text,
  `event_recurring` tinyint(1) DEFAULT NULL,
  `event_start_date` date DEFAULT NULL,
  `event_end_date` date DEFAULT NULL,
  `event_start_time` time DEFAULT NULL,
  `event_end_time` time DEFAULT NULL,
  `event_type_title` varchar(256) DEFAULT NULL,
  `customer_name` varchar(256) DEFAULT NULL,
  `customer_email` varchar(156) DEFAULT NULL,
  `customer_address` varchar(256) DEFAULT NULL,
  `customer_mobile` varchar(20) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `cancellation` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:disable;1:pending;2:approved;3:refunded;',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table e_bookings_members
# ------------------------------------------------------------

DROP TABLE IF EXISTS `e_bookings_members`;

CREATE TABLE `e_bookings_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(256) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(155) DEFAULT NULL,
  `address` varchar(256) DEFAULT NULL,
  `e_bookings_id` int(11) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table e_bookings_payments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `e_bookings_payments`;

CREATE TABLE `e_bookings_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `e_bookings_id` int(11) DEFAULT NULL,
  `paid_amount` int(11) DEFAULT NULL,
  `total_amount` int(11) NOT NULL DEFAULT '0',
  `payment_type` enum('locally','stripe','paypal') DEFAULT NULL,
  `payment_status` tinyint(4) DEFAULT NULL COMMENT '0:pending;1:successful;2:failed',
  `transactions_id` int(11) DEFAULT NULL,
  `currency` varchar(3) DEFAULT NULL,
  `tax_title` varchar(56) DEFAULT NULL,
  `tax_rate_type` enum('percent','fixed') DEFAULT NULL,
  `tax_rate` tinyint(4) DEFAULT NULL,
  `tax_net_price` enum('including','excluding') DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table email_templates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `email_templates`;

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) DEFAULT NULL,
  `subject` varchar(256) DEFAULT NULL,
  `message` text,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

LOCK TABLES `email_templates` WRITE;
/*!40000 ALTER TABLE `email_templates` DISABLE KEYS */;

INSERT INTO `email_templates` (`id`, `title`, `subject`, `message`, `date_added`, `date_updated`)
VALUES
	(1,'batch booking confirmation','Batch Booking Successful','<p>&nbsp;</p>\r\n<p>Dear (t_user_name) ,</p>\r\n<p>Your Booking Has Been Successful For : (t_be_name).</p>\r\n<p>Your txn id : (t_txn_id)</p>\r\n<p>We\'ve received a total amount : (t_total_amount)</p>\r\n<p>&nbsp;</p>\r\n<p>Thank You.</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>','2017-04-28 19:22:14','2017-07-11 16:53:52'),
	(3,'events booking confirmation','Events Booking Successful','<p>&nbsp;</p>\r\n<p>Dear (t_user_name) ,</p>\r\n<p>Your Booking Has Been Successful For : (t_be_name).</p>\r\n<p>Your txn id : (t_txn_id)</p>\r\n<p>We\'ve received a total amount : (t_total_amount)</p>\r\n<p>&nbsp;</p>\r\n<p>Thank You.</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>','2017-07-11 16:53:29','2017-07-11 16:53:29'),
	(4,'register confirmation','Account Registration Successful','<p>&nbsp;</p>\r\n<p>Hello (t_user_name) ,</p>\r\n<p>Welcome To:&nbsp;(t_site_name)</p>\r\n<p>Your Account has been registered successfully : (t_be_name).</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>Thank You.</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>','2017-07-11 16:56:20','2017-07-11 16:56:20');

/*!40000 ALTER TABLE `email_templates` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table emails
# ------------------------------------------------------------

DROP TABLE IF EXISTS `emails`;

CREATE TABLE `emails` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `message` text,
  `created` datetime DEFAULT NULL,
  `read` datetime DEFAULT NULL,
  `read_by` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `title` (`title`),
  KEY `created` (`created`),
  KEY `read` (`read`),
  KEY `read_by` (`read_by`),
  KEY `email` (`email`(78))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `emails` WRITE;
/*!40000 ALTER TABLE `emails` DISABLE KEYS */;

INSERT INTO `emails` (`id`, `name`, `email`, `title`, `message`, `created`, `read`, `read_by`)
VALUES
	(1,'Deepak Panwar','deepak@mail.com','Hello World','Lorem Lipsum sit annum','2017-07-12 13:23:32','2017-07-14 09:25:00',1);

/*!40000 ALTER TABLE `emails` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table event_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `event_types`;

CREATE TABLE `event_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL,
  `icon` varchar(256) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

LOCK TABLES `event_types` WRITE;
/*!40000 ALTER TABLE `event_types` DISABLE KEYS */;

INSERT INTO `event_types` (`id`, `title`, `image`, `icon`, `status`, `date_added`, `date_updated`)
VALUES
	(1,'workshop',NULL,NULL,1,'2017-07-11 18:23:13','2017-07-11 18:23:13'),
	(2,'live concert',NULL,NULL,1,'2017-07-11 18:23:52','2017-07-11 18:23:52'),
	(3,'seminar',NULL,NULL,1,'2017-07-11 18:24:13','2017-07-11 18:24:18'),
	(4,'freshers day',NULL,NULL,1,'2017-07-11 18:24:31','2017-07-11 18:25:06'),
	(5,'party',NULL,NULL,1,'2017-07-11 18:25:01','2017-07-11 18:25:05'),
	(6,'weekly tests',NULL,NULL,1,'2017-07-12 12:11:58','2017-07-12 12:12:01');

/*!40000 ALTER TABLE `event_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table events
# ------------------------------------------------------------

DROP TABLE IF EXISTS `events`;

CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_types_id` int(11) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `description` text,
  `images` text,
  `fees` int(11) DEFAULT NULL COMMENT 'per customer',
  `capacity` int(11) DEFAULT NULL COMMENT 'max customers',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL COMMENT '24 hours format',
  `end_time` time DEFAULT NULL COMMENT '24 hours format',
  `weekdays` text,
  `recurring` tinyint(1) NOT NULL DEFAULT '0',
  `recurring_type` enum('first_week','second_week','third_week','fourth_week','every_week') NOT NULL DEFAULT 'every_week',
  `status` tinyint(1) DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `meta_title` varchar(128) DEFAULT NULL,
  `meta_tags` varchar(256) DEFAULT NULL,
  `meta_description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;

INSERT INTO `events` (`id`, `event_types_id`, `title`, `description`, `images`, `fees`, `capacity`, `start_date`, `end_date`, `start_time`, `end_time`, `weekdays`, `recurring`, `recurring_type`, `status`, `featured`, `date_added`, `date_updated`, `meta_title`, `meta_tags`, `meta_description`)
VALUES
	(1,4,'freshers day party 2017','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>&nbsp;</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>','[\"1499840055803.jpg\",\"1499840055740.png\",\"1499840055389.jpg\"]',1,500,'2018-10-30','2018-10-30','18:00:00','23:00:00',NULL,0,'every_week',1,0,'2017-07-12 11:44:15','2018-06-01 12:58:24','Freshers Day Party 2017','Freshers Day Party 2017','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
	(2,2,'stars music show','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>','[\"1499840388311.jpg\",\"149984038974.jpg\",\"1499840389302.jpg\"]',200,500,'2018-09-28','2018-09-29','19:00:00','23:00:00',NULL,0,'every_week',1,1,'2017-07-12 11:49:49','2018-05-27 11:21:41','Stars Music Show','Stars Music Show','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
	(3,3,'weekly fashion show','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>','[\"1527659408656.jpg\",\"1527659409936.jpg\",\"152765940990.jpg\"]',10,100,'2018-06-01','2018-12-31','10:00:00','12:00:00','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\"]',1,'second_week',1,1,'2017-07-12 11:54:22','2018-05-30 11:20:10','Weekly fashion show','Weekly fashion show','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
	(4,5,'new year celebration 2018','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>','[\"1499840977368.jpg\",\"1499840977171.jpg\",\"149984097713.jpg\"]',250,1000,'2018-12-25','2019-01-01','10:00:00','12:00:00',NULL,0,'every_week',1,0,'2017-07-12 11:59:37','2018-05-27 11:20:53','New Year Celebration 2018','New Year Celebration 2018','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
	(5,1,'man yoga workshop','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>','[\"1499841241863.jpg\",\"1499841242213.jpg\"]',10,100,'2018-07-23','2018-12-30','06:00:00','07:00:00','[\"0\"]',1,'second_week',1,0,'2017-07-12 12:04:02','2018-05-27 11:07:41','Man Yoga Workshop','Man Yoga Workshop','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
	(6,1,'health nutrition session','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>','[\"1499841521119.jpg\"]',1,100,'2018-08-30','2018-08-30','18:00:00','19:00:00',NULL,0,'every_week',1,0,'2017-07-12 12:08:41','2018-05-27 11:06:41','Health And Nutrition Education Session','Health And Nutrition Education Session','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
	(7,2,'live dance show','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>','[\"1499841850896.jpg\"]',100,500,'2018-09-26','2018-09-26','20:00:00','23:45:00',NULL,0,'every_week',1,0,'2017-07-12 12:14:10','2018-06-01 12:59:17','Live Dance Show','Live Dance Show','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
	(8,1,'international yoga day','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>','[\"1499841989254.jpg\"]',1,500,'2018-06-21','2018-06-21','06:00:00','08:00:00',NULL,0,'every_week',1,0,'2017-07-12 12:16:29','2017-07-12 12:16:29','International Yoga Day','International Yoga Day','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
	(9,4,'test event recursive','<p>dasds</p>',NULL,20,504,'2018-06-01','2018-07-31','06:00:00','09:00:00','[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\"]',1,'second_week',1,0,'2018-05-28 15:10:40','2018-05-28 15:21:08','','',''),
	(10,2,'trance tonic night show','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>','[\"1527838090159.jpg\",\"1527838090920.jpg\"]',25,100,'2018-09-24','2018-09-25','17:00:00','23:00:00',NULL,0,'every_week',1,1,'2018-06-01 12:58:11','2018-06-01 12:58:11','Trance Tonic Night Show','Trance Tonic Night Show','Trance Tonic Night Show'),
	(11,2,'david guetta live show','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>','[\"1527838325691.jpg\",\"1527838326100.jpg\"]',50,150,'2018-09-24','2018-09-24','20:00:00','21:00:00',NULL,0,'every_week',1,0,'2018-06-01 13:01:16','2018-06-01 13:02:06','David Guetta Live Show','David Guetta Live Show','David Guetta Live Show');

/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table events_tutors
# ------------------------------------------------------------

DROP TABLE IF EXISTS `events_tutors`;

CREATE TABLE `events_tutors` (
  `users_id` int(11) DEFAULT NULL,
  `events_id` int(11) DEFAULT NULL,
  UNIQUE KEY `users_id` (`users_id`,`events_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `events_tutors` WRITE;
/*!40000 ALTER TABLE `events_tutors` DISABLE KEYS */;

INSERT INTO `events_tutors` (`users_id`, `events_id`)
VALUES
	(38,1),
	(38,3),
	(38,4),
	(38,6),
	(38,7),
	(38,8),
	(39,1),
	(39,3),
	(39,4),
	(39,6),
	(39,7),
	(39,8),
	(40,1),
	(40,3),
	(40,4),
	(40,5),
	(40,6),
	(40,7),
	(40,8),
	(41,1),
	(41,3),
	(41,4),
	(41,5),
	(41,6),
	(41,7),
	(41,8),
	(42,1),
	(42,2),
	(42,3),
	(42,4),
	(42,7),
	(43,1),
	(43,2),
	(43,3),
	(43,4),
	(43,7),
	(44,1),
	(44,2),
	(44,3),
	(44,4),
	(44,5),
	(44,6),
	(44,7),
	(44,8),
	(44,9),
	(44,10),
	(44,11);

/*!40000 ALTER TABLE `events_tutors` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table faqs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `faqs`;

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(256) DEFAULT NULL,
  `answer` text,
  `status` tinyint(1) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;

INSERT INTO `faqs` (`id`, `question`, `answer`, `status`, `date_added`, `date_updated`)
VALUES
	(1,'What is Lorem Ipsum?','<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>',1,'2017-07-12 13:18:16','2017-07-12 13:18:16'),
	(2,'Why do we use it?','<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>',1,'2017-07-12 13:18:38','2017-07-12 13:18:38'),
	(3,'Where does it come from?','<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.</p>',1,'2017-07-12 13:18:56','2017-07-12 13:18:56'),
	(4,'Where can I get some?','<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>',1,'2017-07-12 13:19:14','2017-07-12 13:19:14');

/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table gallaries
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gallaries`;

CREATE TABLE `gallaries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(128) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

LOCK TABLES `gallaries` WRITE;
/*!40000 ALTER TABLE `gallaries` DISABLE KEYS */;

INSERT INTO `gallaries` (`id`, `image`, `date_added`)
VALUES
	(1,'1499843277173.jpg','2017-07-12 12:37:59'),
	(2,'1499843277669.jpg','2017-07-12 12:37:59'),
	(3,'1499843277893.jpg','2017-07-12 12:37:59'),
	(4,'1499843277461.jpg','2017-07-12 12:37:59'),
	(5,'1499843278581.jpg','2017-07-12 12:37:59'),
	(6,'1499843278549.jpg','2017-07-12 12:37:59'),
	(7,'1499843278251.jpg','2017-07-12 12:37:59'),
	(8,'1499843278915.jpg','2017-07-12 12:37:59'),
	(9,'1499843278764.jpg','2017-07-12 12:37:59');

/*!40000 ALTER TABLE `gallaries` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;

INSERT INTO `groups` (`id`, `name`, `description`, `date_added`, `date_updated`)
VALUES
	(1,'admin','Administrator','2017-07-09 09:26:25','2017-07-09 09:26:36'),
	(2,'tutors','Semi-admin','2017-07-09 09:26:25','2017-07-11 10:25:57'),
	(3,'customers','General User (non-admin)','2017-07-09 09:26:25','2017-07-09 09:26:36');

/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table languages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `languages`;

CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) DEFAULT NULL,
  `flag` varchar(256) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table login_attempts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `login_attempts`;

CREATE TABLE `login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table menus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `menus`;

CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) DEFAULT NULL,
  `slug` varchar(256) DEFAULT NULL,
  `position` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1:custom;2:top',
  `content` longtext,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;

INSERT INTO `menus` (`id`, `title`, `slug`, `position`, `content`, `date_added`, `date_updated`)
VALUES
	(1,'Categories','categories','2','[{\"id\":\"3\",\"parent_id\":\"0\",\"type\":\"page\",\"label\":\"Faq\",\"value\":\"faq\"},{\"id\":\"4\",\"parent_id\":\"0\",\"type\":\"page\",\"label\":\"Lorem Ipsum Sit Annum\",\"value\":\"lorem-ipsum-sit-annum\"}]','2017-06-16 14:35:15','2017-07-12 13:10:39');

/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table notifications
# ------------------------------------------------------------

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `n_type` enum('batches','events','bbookings','ebookings','contacts','users','b_cancellation','e_cancellation') DEFAULT NULL,
  `n_content` varchar(128) DEFAULT NULL,
  `n_url` text,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `users_id` int(11) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;




# Dump of table pages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) DEFAULT NULL,
  `slug` varchar(256) DEFAULT NULL,
  `content` text,
  `image` varchar(256) DEFAULT NULL,
  `meta_title` varchar(128) DEFAULT NULL,
  `meta_tags` varchar(256) DEFAULT NULL,
  `meta_description` text,
  `status` tinyint(1) DEFAULT NULL COMMENT '0:disable;1:published;2:draft;',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `image`, `meta_title`, `meta_tags`, `meta_description`, `status`, `date_added`, `date_updated`)
VALUES
	(1,'Faq','faq','<p>\r\n\r\n</p><h1>Lorem Ipsum</h1><h4>\"Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...\"</h4><h5>\"There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...\"</h5><div><div><div><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam dictum ornare dapibus. Quisque cursus ultricies mattis. Donec pretium eleifend leo, vitae commodo sapien scelerisque vel. Nullam hendrerit dapibus lorem sed dignissim. Curabitur convallis nibh et dolor posuere, et blandit magna lacinia. Fusce eu neque mattis, tempus metus non, dignissim dolor. Ut mi risus, aliquet non ultricies vitae, bibendum eget metus.</p><p>Fusce a euismod metus, vitae gravida tellus. Nunc in turpis vel tellus fermentum sodales id sit amet felis. Duis lorem augue, tincidunt id consectetur et, commodo in lorem. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed vel posuere augue. Aenean fringilla dui justo, id dapibus magna accumsan a. Vestibulum hendrerit neque feugiat arcu varius, in convallis magna fermentum. Nulla sodales porttitor est non feugiat. Maecenas vitae quam elit. Proin vel finibus orci, quis ultrices justo. Curabitur dignissim vitae orci a gravida. Curabitur pharetra dui non aliquet porttitor. Sed vitae ante nec magna varius faucibus. Aliquam erat volutpat. Nunc sit amet euismod turpis.</p><p>Nunc in urna sed ligula aliquet pretium ac nec dui. Etiam varius maximus ex. Morbi laoreet enim odio, non tristique nulla gravida quis. Duis commodo ut nunc a sodales. Fusce sit amet eros lorem. Aliquam rhoncus purus velit. Ut accumsan sem sit amet leo rhoncus, gravida auctor velit eleifend.</p><p>Nunc id ipsum gravida, cursus dui vitae, semper felis. Pellentesque in tincidunt dui. Donec arcu sapien, porttitor ornare quam ut, tincidunt dictum ex. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vestibulum eget interdum magna. Nulla facilisi. In finibus lacus et nunc consectetur, eget rhoncus quam mattis. Sed at consequat est, fermentum tincidunt tortor. Nulla facilisi. Cras in ipsum hendrerit, aliquam diam at, sollicitudin lectus. Curabitur dapibus imperdiet odio, non pulvinar lacus. Curabitur ut tempus est, et commodo nisl. Nulla facilisi. Duis eget ipsum non tellus sagittis tincidunt. Maecenas a mi laoreet, fermentum massa sed, posuere est.</p><p>Integer eget pellentesque augue. Etiam quis quam risus. Integer libero eros, porttitor et mollis ut, euismod quis nisi. Maecenas a ultrices eros. Quisque placerat dolor vel lobortis rhoncus. Proin fringilla tincidunt mauris, vitae tempor tortor consequat vel. Nulla facilisi. Curabitur pulvinar commodo turpis id congue. Pellentesque tempor volutpat dolor. Nulla in eleifend tortor. Integer leo lorem, placerat ac condimentum sit amet, fringilla ut lacus. Maecenas at mattis urna, eu rhoncus elit. Curabitur sed metus et purus bibendum consectetur. Nam porta fringilla lacus, a dapibus nibh tempor eget.</p></div><div>Generated 5 paragraphs, 406 words, 2730 bytes of <a target=\"_blank\" rel=\"nofollow\" href=\"http://www.lipsum.com/\">Lorem Ipsum</a></div></div></div>\r\n\r\n<p></p>','1499844777399.jpg','Lorem ipsum dolor sit amet','Lorem ipsum dolor sit amet','Lorem ipsum dolor sit amet',1,'2017-05-13 16:42:08','2017-07-12 13:02:57'),
	(2,'Lorem Ipsum Sit Annum','lorem-ipsum-sit-annum','<p>\r\n\r\n<p>And so when you are setting up a new site or trying out a new theme, it can be very handy to have some of this sample content around. It sure beats writing, “This is a test.” over and over and over.</p><p>However, even copying and pasting this text can become a bore if you need to do it more than a few times. A perfect solution, then, would be a plugin that automatically adds it for you. And because we’re dealing with WordPress, of course you know that someone has already thought of it.</p>\r\n\r\n\r\n\r\n<ul><li>It adds six types of posts:</li></ul><blockquote><ul><li>Multiple Paragraph Posts</li><li>Image Post</li><li>UL and OL Post</li><li>Blockquote Post</li><li>Post with links</li><li>Post with Header tags H1 through H5</li></ul></blockquote><ul><li>Allows you to remove all the added content with one click</li><li>Formats posts with different styles</li></ul>\r\n\r\n<br></p>','1499845336644.jpeg','Lorem Ipsum Sit Annum','Lorem Ipsum Sit Annum','Lorem Ipsum Sit Annum',1,'2017-05-18 13:31:24','2017-07-12 13:12:16');

/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `controllers_id` int(11) NOT NULL,
  `groups_id` int(11) NOT NULL,
  `p_view` int(11) NOT NULL,
  `p_add` int(11) NOT NULL,
  `p_edit` int(11) NOT NULL,
  `p_delete` int(11) NOT NULL,
  UNIQUE KEY `controllers_id` (`controllers_id`,`groups_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;

INSERT INTO `permissions` (`controllers_id`, `groups_id`, `p_add`, `p_edit`, `p_delete`)
VALUES
	(1,  2,  1,  1,  0),
	(2,  2,  1,  1,  0),
	(3,  2,  1,  1,  0),
	(4,  2,  1,  1,  0),
	(5,  2,  1,  1,  0),
	(6,  2,  1,  1,  0),
	(7,  2,  1,  1,  0),
	(8,  2,  1,  1,  0),
	(9,  2,  1,  1,  0),
	(10, 2,  1,  1,  0),
	(11, 2,  1,  1,  1),
	(12, 2,  1,  1,  0),
	(13, 2,  1,  1,  0),
	(14, 2,  1,  1,  0),
	(15, 2,  1,  1,  0),
	(16, 2,  1,  1,  0),
	(17, 2,  1,  1,  0),
	(18, 2,  1,  1,  0),
	(19, 2,  1,  0,  0);

/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `setting_type` enum('institute','site','home','theme','booking','email','login','payment','disqus','regional','ion_auth') NOT NULL,
  `name` varchar(256) DEFAULT NULL,
  `input_type` enum('input','textarea','radio','dropdown','timezones','file','languages','currencies','email','email_templates','taxes','files') CHARACTER SET latin1 NOT NULL,
  `options` text COMMENT 'Use for radio and dropdown: key|value on each line',
  `is_numeric` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'forces numeric keypad on mobile devices',
  `show_editor` enum('0','1') NOT NULL DEFAULT '0',
  `input_size` enum('large','medium','small') DEFAULT NULL,
  `translate` enum('0','1') NOT NULL DEFAULT '0',
  `help_text` varchar(256) DEFAULT NULL,
  `validation` varchar(256) NOT NULL,
  `sort_order` smallint(5) unsigned NOT NULL,
  `label` varchar(256) DEFAULT NULL,
  `value` text COMMENT 'If translate is 1, just start with your default language',
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;

INSERT INTO `settings` (`id`, `setting_type`, `name`, `input_type`, `options`, `is_numeric`, `show_editor`, `input_size`, `translate`, `help_text`, `validation`, `sort_order`, `label`, `value`, `last_update`, `updated_by`)
VALUES
	(1,'institute','institute_name','input',NULL,'0','0','large','0','Enter institute name','trim|required|min_length[3]|max_length[128]',10,'Institute Name','Classeventie','2018-05-29 14:13:13',1),
	(2,'institute','institute_logo','file',NULL,'0','0','large','0','Upload institute logo of size 72x72 pixels and format .png','trim',20,'Institute Logo','logo.png','2017-07-06 12:16:18',1),
	(3,'institute','institute_address','textarea',NULL,'0','0','large','0','Enter institute address','trim|required',30,'Institute Address','Somewhere on the planet Earth','2018-05-29 14:13:13',1),
	(4,'institute','institute_phone','input',NULL,'0','0','medium','0','Enter institute contact number','trim|required',40,'Institute Phone','+123 234 233','2018-05-29 14:13:13',1),
	(5,'institute','institute_website','input',NULL,'0','0','medium','0','Enter institute website','trim|required',50,'Institute Website','classeventie.classiebit.com','2018-05-29 14:13:13',1),
	(6,'site','site_name','input',NULL,'0','0','medium','0','Enter site name (generally its your domain name)','required|trim|min_length[3]|max_length[64]',60,'Site Name','Classeventie','2018-05-29 14:13:19',1),
	(7,'site','site_email','input',NULL,'0','0','medium','0','Email site email address (i.e youremail@yourdomain.com)','required|trim|valid_email',70,'Site Email','info@classiebit.com','2018-05-29 14:13:19',1),
	(8,'site','meta_title','input',NULL,'0','0','large','0','Enter meta title for home page','trim|min_length[3]|max_length[128]|required',80,'Meta Title','Class and event booking - multi purpose booking system','2018-05-29 14:13:19',1),
	(9,'site','meta_tags','input',NULL,'0','0','large','0','Comma-seperated list of site keywords','trim|min_length[3]|max_length[256]',90,'Meta Tags','courses, classiebit softwares, ibs, institute booking system','2018-05-29 14:13:19',1),
	(10,'site','meta_description','textarea',NULL,'0','0','large','0','Short description describing your site.','trim',100,'Meta Description','A product by Classiebit Softwares.','2018-05-29 14:13:19',1),
	(11,'site','terms_n_conditions','textarea',NULL,'0','1','large','0','Enter your site terms and conditions','trim',110,'Terms & Conditions','<h2>1. YOUR AGREEMENT</h2>\r\n<p>By using this Site, you agree to be bound by, and to comply with, these Terms and Conditions. If you do not agree to these Terms and Conditions, please do not use this site.</p>\r\n<blockquote>PLEASE NOTE: We reserve the right, at our sole discretion, to change, modify or otherwise alter these Terms and Conditions at any time. Unless otherwise indicated, amendments will become effective immediately. Please review these Terms and Conditions periodically. Your continued use of the Site following the posting of changes and/or modifications will constitute your acceptance of the revised Terms and Conditions and the reasonableness of these standards for notice of changes. For your information, this page was last updated as of the date at the top of these terms and conditions.</blockquote>\r\n<h2>2. PRIVACY</h2>\r\n<p>Please review our Privacy Policy, which also governs your visit to this Site, to understand our practices.</p>\r\n<h2>3. LINKED SITES</h2>\r\n<p>This Site may contain links to other independent third-party Web sites (\"Linked Sites&rdquo;). These Linked Sites are provided solely as a convenience to our visitors. Such Linked Sites are not under our control, and we are not responsible for and does not endorse the content of such Linked Sites, including any information or materials contained on such Linked Sites. You will need to make your own independent judgment regarding your interaction with these Linked Sites.</p>\r\n<h2>4. FORWARD LOOKING STATEMENTS</h2>\r\n<p>All materials reproduced on this site speak as of the original date of publication or filing. The fact that a document is available on this site does not mean that the information contained in such document has not been modified or superseded by events or by a subsequent document or filing. We have no duty or policy to update any information or statements contained on this site and, therefore, such information or statements should not be relied upon as being current as of the date you access this site.</p>\r\n<h2>5. DISCLAIMER OF WARRANTIES AND LIMITATION OF LIABILITY</h2>\r\n<p>A. THIS SITE MAY CONTAIN INACCURACIES AND TYPOGRAPHICAL ERRORS. WE DOES NOT WARRANT THE ACCURACY OR COMPLETENESS OF THE MATERIALS OR THE RELIABILITY OF ANY ADVICE, OPINION, STATEMENT OR OTHER INFORMATION DISPLAYED OR DISTRIBUTED THROUGH THE SITE. YOU EXPRESSLY UNDERSTAND AND AGREE THAT: (i) YOUR USE OF THE SITE, INCLUDING ANY RELIANCE ON ANY SUCH OPINION, ADVICE, STATEMENT, MEMORANDUM, OR INFORMATION CONTAINED HEREIN, SHALL BE AT YOUR SOLE RISK; (ii) THE SITE IS PROVIDED ON AN \"AS IS\" AND \"AS AVAILABLE\" BASIS; (iii) EXCEPT AS EXPRESSLY PROVIDED HEREIN WE DISCLAIM ALL WARRANTIES OF ANY KIND, WHETHER EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, WORKMANLIKE EFFORT, TITLE AND NON-INFRINGEMENT; (iv) WE MAKE NO WARRANTY WITH RESPECT TO THE RESULTS THAT MAY BE OBTAINED FROM THIS SITE, THE PRODUCTS OR SERVICES ADVERTISED OR OFFERED OR MERCHANTS INVOLVED; (v) ANY MATERIAL DOWNLOADED OR OTHERWISE OBTAINED THROUGH THE USE OF THE SITE IS DONE AT YOUR OWN DISCRETION AND RISK; and (vi) YOU WILL BE SOLELY RESPONSIBLE FOR ANY DAMAGE TO YOUR COMPUTER SYSTEM OR FOR ANY LOSS OF DATA THAT RESULTS FROM THE DOWNLOAD OF ANY SUCH MATERIAL.</p>\r\n<p>B. YOU UNDERSTAND AND AGREE THAT UNDER NO CIRCUMSTANCES, INCLUDING, BUT NOT LIMITED TO, NEGLIGENCE, SHALL WE BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, PUNITIVE OR CONSEQUENTIAL DAMAGES THAT RESULT FROM THE USE OF, OR THE INABILITY TO USE, ANY OF OUR SITES OR MATERIALS OR FUNCTIONS ON ANY SUCH SITE, EVEN IF WE HAVE BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES. THE FOREGOING LIMITATIONS SHALL APPLY NOTWITHSTANDING ANY FAILURE OF ESSENTIAL PURPOSE OF ANY LIMITED REMEDY.</p>\r\n<h2>6. EXCLUSIONS AND LIMITATIONS</h2>\r\n<p>SOME JURISDICTIONS DO NOT ALLOW THE EXCLUSION OF CERTAIN WARRANTIES OR THE LIMITATION OR EXCLUSION OF LIABILITY FOR INCIDENTAL OR CONSEQUENTIAL DAMAGES. ACCORDINGLY, OUR LIABILITY IN SUCH JURISDICTION SHALL BE LIMITED TO THE MAXIMUM EXTENT PERMITTED BY LAW.</p>\r\n<h2>7. OUR PROPRIETARY RIGHTS</h2>\r\n<p>This Site and all its Contents are intended solely for personal, non-commercial use. Except as expressly provided, nothing within the Site shall be construed as conferring any license under our or any third party\'s intellectual property rights, whether by estoppel, implication, waiver, or otherwise. Without limiting the generality of the foregoing, you acknowledge and agree that all content available through and used to operate the Site and its services is protected by copyright, trademark, patent, or other proprietary rights. You agree not to: (a) modify, alter, or deface any of the trademarks, service marks, trade dress (collectively \"Trademarks\") or other intellectual property made available by us in connection with the Site; (b) hold yourself out as in any way sponsored by, affiliated with, or endorsed by us, or any of our affiliates or service providers; (c) use any of the Trademarks or other content accessible through the Site for any purpose other than the purpose for which we have made it available to you; (d) defame or disparage us, our Trademarks, or any aspect of the Site; and (e) adapt, translate, modify, decompile, disassemble, or reverse engineer the Site or any software or programs used in connection with it or its products and services.</p>\r\n<p>The framing, mirroring, scraping or data mining of the Site or any of its content in any form and by any method is expressly prohibited.</p>\r\n<h2>8. INDEMNITY</h2>\r\n<p>By using the Site web sites you agree to indemnify us and affiliated entities (collectively \"Indemnities\") and hold them harmless from any and all claims and expenses, including (without limitation) attorney\'s fees, arising from your use of the Site web sites, your use of the Products and Services, or your submission of ideas and/or related materials to us or from any person\'s use of any ID, membership or password you maintain with any portion of the Site, regardless of whether such use is authorized by you.</p>\r\n<h2>9. COPYRIGHT AND TRADEMARK NOTICE</h2>\r\n<p>Except our generated dummy copy, which is free to use for private and commercial use, all other text is copyrighted. generator.lorem-ipsum.info &copy; 2013, all rights reserved</p>\r\n<h2>10. INTELLECTUAL PROPERTY INFRINGEMENT CLAIMS</h2>\r\n<p>It is our policy to respond expeditiously to claims of intellectual property infringement. We will promptly process and investigate notices of alleged infringement and will take appropriate actions under the Digital Millennium Copyright Act (\"DMCA\") and other applicable intellectual property laws. Notices of claimed infringement should be directed to:</p>\r\n<p>generator.lorem-ipsum.info</p>\r\n<p>126 Electricov St.</p>\r\n<p>Kiev, Kiev 04176</p>\r\n<p>Ukraine</p>\r\n<p>contact@lorem-ipsum.info</p>\r\n<h2>11. PLACE OF PERFORMANCE</h2>\r\n<p>This Site is controlled, operated and administered by us from our office in Kiev, Ukraine. We make no representation that materials at this site are appropriate or available for use at other locations outside of the Ukraine and access to them from territories where their contents are illegal is prohibited. If you access this Site from a location outside of the Ukraine, you are responsible for compliance with all local laws.</p>\r\n<h2>12. GENERAL</h2>\r\n<p>A. If any provision of these Terms and Conditions is held to be invalid or unenforceable, the provision shall be removed (or interpreted, if possible, in a manner as to be enforceable), and the remaining provisions shall be enforced. Headings are for reference purposes only and in no way define, limit, construe or describe the scope or extent of such section. Our failure to act with respect to a breach by you or others does not waive our right to act with respect to subsequent or similar breaches. These Terms and Conditions set forth the entire understanding and agreement between us with respect to the subject matter contained herein and supersede any other agreement, proposals and communications, written or oral, between our representatives and you with respect to the subject matter hereof, including any terms and conditions on any of customer\'s documents or purchase orders.</p>\r\n<p>B. No Joint Venture, No Derogation of Rights. You agree that no joint venture, partnership, employment, or agency relationship exists between you and us as a result of these Terms and Conditions or your use of the Site. Our performance of these Terms and Conditions is subject to existing laws and legal process, and nothing contained herein is in derogation of our right to comply with governmental, court and law enforcement requests or requirements relating to your use of the Site or information provided to or gathered by us with respect to such use.</p>','2018-05-29 14:13:19',1),
	(12,'home','banner_title_1','input',NULL,'0','0','large','0','Enter banner 1 title','trim|required|min_length[8]|max_length[64]',120,'Banner Title 1','Welcome to Classeventie','2018-05-29 14:17:39',1),
	(13,'home','banner_title_2','input',NULL,'0','0','large','0','Enter banner 2 title','trim|required|min_length[8]|max_length[64]',121,'Banner Title 2','Events booking and management','2018-05-29 14:17:39',1),
	(14,'home','banner_title_3','input',NULL,'0','0','large','0','Enter banner 3 title','trim|required|min_length[8]|max_length[64]',122,'Banner Title 3','Classes booking and management','2018-05-29 14:17:39',1),
	(15,'home','banner_title_4','input',NULL,'0','0','large','0','Enter banner 4 title','trim|required|min_length[8]|max_length[64]',123,'Banner Title 4','Stunning and super light user interface','2018-05-29 14:17:39',1),
	(16,'home','banner_title_5','input',NULL,'0','0','large','0','Enter banner 5 title','trim|required|min_length[8]|max_length[64]',124,'Banner Title 5','Multi lingual and multi currency','2018-05-29 14:17:39',1),
	(17,'home','banner_description_1','textarea',NULL,'0','0','large','0','Enter banner 1 short description','trim|required|min_length[16]|max_length[256]',130,'Banner Description 1','Organize events, repetitive events, classes, repetitive classes, sell tickets...','2018-05-29 14:17:39',1),
	(18,'home','banner_description_2','textarea',NULL,'0','0','large','0','Enter banner 2 short description','trim|required|min_length[16]|max_length[256]',131,'Banner Description 2','Earn money by organizing events worldwide, manage events bookings, scale your business...','2018-05-29 14:17:39',1),
	(19,'home','banner_description_3','textarea',NULL,'0','0','large','0','Enter banner 3 short description','trim|required|min_length[16]|max_length[256]',132,'Banner Description 3','Organize paid classes, repetitive batches for your own Institute...','2018-05-29 14:17:39',1),
	(20,'home','banner_description_4','textarea',NULL,'0','0','large','0','Enter banner 4 short description','trim|required|min_length[16]|max_length[256]',133,'Banner Description 4','Beautiful and user-friendly interface for hassle-free booking experience...','2018-05-29 14:17:39',1),
	(21,'home','banner_description_5','textarea',NULL,'0','0','large','0','Enter banner 5 short description','trim|required|min_length[16]|max_length[256]',134,'Banner Description 5','Run your business globally without any hassles...','2018-05-29 14:17:39',1),
	(22,'home','banner_image_1','file',NULL,'0','0','large','0','Upload banner 1 image of size 2000x1000 pixels and format .jpg','trim',140,'Banner Image 1','banner_image_1.jpg','2017-07-06 12:16:18',1),
	(23,'home','banner_image_2','file',NULL,'0','0','large','0','Upload banner 2 image of size 2000x1000 pixels and format .jpg','trim',141,'Banner Image 2','banner_image_2.jpg','2017-07-06 12:16:18',1),
	(24,'home','banner_image_3','file',NULL,'0','0','large','0','Upload banner 3 image of size 2000x1000 pixels and format .jpg','trim',142,'Banner Image 3','banner_image_3.jpg','2017-07-06 12:16:18',1),
	(25,'home','banner_image_4','file',NULL,'0','0','large','0','Upload banner 4 image of size 2000x1000 pixels and format .jpg','trim',143,'Banner Image 4','banner_image_4.jpg','2017-07-06 12:16:18',1),
	(26,'home','banner_image_5','file',NULL,'0','0','large','0','Upload banner 5 image of size 2000x1000 pixels and format .jpg','trim',144,'Banner Image 5','banner_image_5.jpg','2017-07-06 12:16:18',1),
	(27,'home','about_institute','textarea',NULL,'0','0','large','0','Enter some catchy info about your institute','trim|required|min_length[16]|max_length[256]',150,'About Institute','Classeventie gives you the power to organize Events and Classes, sell the tickets and to run your own Event organization business locally as well as globally seamlessly.','2018-05-29 14:17:39',1),
	(28,'home','intro_video_url','input',NULL,'0','0','large','0','Enter institute intro video URL (from youtube or vimeo)','trim',160,'Institute Intro Vide URL','','2018-05-29 14:17:39',1),
	(29,'home','social_facebook','input',NULL,'0','0','large','0','Enter facebook account URL','trim',170,'Facebook URL','https://facebook.com','2018-05-29 14:17:39',1),
	(30,'home','social_google','input',NULL,'0','0','large','0','Enter google+ account URL','trim',171,'Google+ URL','https://googleplus.com','2018-05-29 14:17:39',1),
	(31,'home','social_twitter','input',NULL,'0','0','large','0','Enter twitter account URL','trim',172,'Twitter URL','https://twitter.com','2018-05-29 14:17:39',1),
	(32,'home','social_linkedin','input',NULL,'0','0','large','0','Enter linkedin account URL','trim',173,'Linkedin URL','https://linkedin.com','2018-05-29 14:17:39',1),
	(33,'home','social_flickr','input',NULL,'0','0','large','0','Enter Flickr account URL','trim',174,'Flickr URL','https://flickr.com','2018-05-29 14:17:39',1),
	(34,'home','social_pinterest','input',NULL,'0','0','large','0','Enter Pinterest account URL','trim',175,'Pinterest URL','https://pinterest.com','2018-05-29 14:17:39',1),
	(35,'theme','admin_theme','dropdown','red|RED\npink|PINK\npurple|PURPLE\ndeep-purple|DEEP PURPLE\nindigo|INDIGO\nblue|BLUE\nlight-blue|LIGHT BLUE\ncyan|CYAN\nteal|TEAL\ngreen|GREEN\nlight-green|LIGHT GREEN\nlime|LIME\nyellow|YELLOW\namber|AMBER\norange|ORANGE\ndeep-orange|DEEP ORANGE\nbrown|BROWN\ngrey|GREY\nblue-grey|BLUE GREY\nblack|BLACK','0','0','medium','0','Select theme color for admin panel','required|trim|in_list[red,pink,purple,deep-purple,indigo,blue,light-blue,cyan,teal,green,light-green,lime,yellow,amber,orange,deep-orange,brown,grey,blue-grey,black]',180,'Admin Theme Color','blue','2018-05-26 16:00:17',1),
	(36,'booking','default_prebook_time','input',NULL,'1','0','small','0','In hours or 0 for anytime','required|trim|numeric|greater_than_equal_to[0]',190,'Default Prebooking Time','12','2017-07-11 16:56:40',1),
	(37,'booking','default_precancel_time','input',NULL,'1','0','small','0','In hours or 0 for anytime','required|trim|numeric|greater_than_equal_to[0]',200,'Default Precancelation Time','12','2017-07-11 16:56:40',1),
	(38,'booking','default_starting_booking_id','input',NULL,'1','0','small','0','Numeric value from where the booking id should start','trim|required|is_natural_no_zero',210,'Default Starting Booking Id','1008','2018-05-28 16:58:19',1),
	(39,'booking','default_e_booking_email_template','email_templates',NULL,'0','0','medium','0','Select default event booking confirmation email template (please select the currect template)','trim|required',220,'Default E-Booking Email Template','3','2017-07-11 16:56:40',1),
	(40,'booking','default_b_booking_email_template','email_templates',NULL,'0','0','medium','0','Select default batch booking confirmation email template (please select the currect template)','trim|required',230,'Default B-Booking Email Template','1','2017-07-11 16:56:40',1),
	(41,'booking','default_signup_email_template','email_templates',NULL,'0','0','medium','0','Select default signup email template','trim|required',240,'Default Signup Email Template','4','2017-07-11 16:56:40',1),
	(42,'booking','default_tax_id','taxes',NULL,'0','0','medium','0','Select default tax','trim|required|is_natural_no_zero',240,'Default Tax','2','2017-07-11 16:56:40',1),
	(43,'email','sender_name','input',NULL,'0','0','medium','0',NULL,'trim',250,'Sender Name','Classeventie','2018-06-04 12:07:07',1),
	(44,'email','sender_email','email',NULL,'0','0','medium','0',NULL,'trim|valid_email',260,'Sender Email','info@classiebit.com','2018-06-04 12:07:07',1),
	(45,'email','email_type','dropdown','html|HTML\ntext|TEXT','0','0','medium','0',NULL,'trim',270,'Email Type','html','2018-06-04 12:07:07',1),
	(46,'email','reply_to','dropdown','0|DISABLE\n1|ENABLE','0','0','medium','0',NULL,'trim',280,'Reply To','1','2018-06-04 12:07:07',1),
	(47,'email','smtp_server','input',NULL,'0','0','medium','0',NULL,'trim',290,'SMTP Server','','2018-06-04 12:07:07',1),
	(48,'email','smtp_username','input',NULL,'0','0','medium','0',NULL,'trim',300,'SMTP Username','','2018-06-04 12:07:07',1),
	(49,'email','smtp_password','input',NULL,'0','0','medium','0',NULL,'trim',310,'SMTP Password','','2018-06-04 12:07:07',1),
	(50,'email','smtp_port','input',NULL,'0','0','medium','0',NULL,'trim',320,'SMTP Port','','2018-06-04 12:07:07',1),
	(51,'login','g_client_id','input',NULL,'0','0','large','0','The client ID obtained from the Developers Console','trim',330,'Google Client ID','804059028467-05dkqia6g9roqn05dl6vu3eq413islme.apps.googleusercontent.com','2017-07-06 19:33:45',1),
	(52,'login','g_client_secret','input',NULL,'0','0','large','0','The client secret obtained from the Developers Console','trim',340,'Google Client Secret','Ir6a4ueZcGVG3r5B124HNh6E','2017-07-06 19:33:49',1),
	(53,'login','fb_app_id','input',NULL,'0','0','large','0','The App ID obtained from the Developers Console','trim',350,'Facebook App ID','448330088882738','2017-07-06 19:33:53',1),
	(54,'login','fb_app_secret','input',NULL,'0','0','large','0','The App Secret obtained from the Developers Console','trim',360,'Facebook App Secret','9c90de49007e0e9576f15b7009db23ef','2017-07-06 19:33:56',1),
	(55,'payment','pp_registered_email','email',NULL,'0','0','large','0','Enter Paypal registered business email id','trim',370,'Paypal Registered Email','info@classiebit.com','2017-07-11 17:05:44',1),
	(56,'payment','pp_sandbox','dropdown','0|No\r\n 1|Yes','0','0','large','0',NULL,'trim',380,'Paypal Sandbox Mode','1','2017-07-11 17:05:44',1),
	(57,'payment','s_secret_key','input',NULL,'0','0','large','0',NULL,'trim',390,'Stripe Secret Key','sk_test_OIDXdY0HkraiZTzT3Tw1eEgs','2017-07-11 17:05:44',1),
	(58,'payment','s_publishable_key','input',NULL,'0','0','large','0',NULL,'trim',400,'Stripe Publishable Key','pk_test_CgBOKUww73oDpWW7eGfOlcmJ','2017-07-11 17:05:44',1),
	(59,'disqus','disqus_short_name','input',NULL,'0','0','large','0','Enter Disqus account short name obtained from your Disqus account','trim',410,'Disqus Short Name','deepak0101pro','2017-07-06 19:34:14',1),
	(60,'regional','default_language','languages',NULL,'0','0','large','0',NULL,'required|trim',420,'Default Language','english','2018-05-29 13:08:17',1),
	(61,'regional','default_currency','currencies',NULL,'0','0','large','0',NULL,'required|trim',430,'Default Currency','USD','2018-05-29 13:08:17',1),
	(62,'regional','timezones','timezones',NULL,'0','0','large','0',NULL,'required|trim',440,'Default Timezone','UP55','2018-05-29 13:08:17',1),
	(63,'login','g_map_key','input',NULL,'0','0','large','0','The Google Map APi key obtained from the Developers Console','trim',341,'Google Map Key','AIzaSyDaAwwjtg_ozXRnjg6fjuMSRU4BfuMtRSA','2016-07-26 23:10:44',1),
	(64,'login','g_map_lat','input',NULL,'0','0','large','0','The Google Map Lattitude','trim',342,'Google Map Lat','-35.2835','2016-07-26 23:10:44',1),
	(65,'login','g_map_lng','input',NULL,'0','0','large','0','The Google Map Longitude','trim',343,'Google Map Lng','149.128','2016-07-26 23:10:44',1),
	(66,'ion_auth','i_admin_email','input',NULL,'0','0','large','0','Enter admin email','required|trim|min_length[3]|max_length[128]',350,'Admin Email','info@classiebit.com','2018-05-29 13:08:01',1),
	(67,'ion_auth','i_default_group','input',NULL,'0','0','large','0','Enter default member group name','trim|required',360,'Default Member Group Name','customers','2018-05-29 13:08:01',1),
	(68,'ion_auth','i_admin_group','input',NULL,'0','0','large','0','Enter admin group name','trim|required',370,'Admin Group Name','admin','2018-05-29 13:08:01',1),
	(69,'ion_auth','i_min_password','input',NULL,'0','0','large','0','Minimum Required Length of Password','trim|is_natural_no_zero|required',380,'Min Password Length','8','2018-05-29 13:08:01',1),
	(70,'ion_auth','i_max_password','input',NULL,'0','0','large','0','Maximum Allowed Length of Password','trim|is_natural_no_zero|required',390,'Max Password Length','32','2018-05-29 13:08:01',1),
	(71,'ion_auth','i_email_activation','input',NULL,'0','0','large','0','Email Activation for registration (0: false, 1: TRUE)','trim|in_list[0,1]|required',400,'Email Activation','1','2018-05-29 13:08:01',1),
	(72,'ion_auth','i_user_expire','input',NULL,'0','0','large','0','How long to remember the user (seconds). Set to zero for no expiration','trim|greater_than_equal_to[0]',410,'Login Session Expiration (seconds)','86500','2018-05-29 13:08:01',1),
	(73,'ion_auth','i_max_login_attempts','input',NULL,'0','0','large','0','The maximum number of failed login attempts allowed.','trim|required|is_natural_no_zero',420,'Max Login Attempts','5','2018-05-29 13:08:01',1),
	(74,'ion_auth','i_lockout_time','input',NULL,'0','0','large','0','The number of seconds to lockout an account due to exceeded attempts.','trim|required|is_natural_no_zero',430,'Lockout Time (seconds)','60','2018-05-29 13:08:01',1),
	(75,'ion_auth','i_forgot_password_expiration','input',NULL,'0','0','large','0','The number of milliseconds after which a forgot password request will expire. If set to 0, forgot password requests will not expire.','trim|greater_than_equal_to[0]',440,'Forgot Password Expiration','0','2018-05-29 13:08:01',1),
	(76,'ion_auth','i_recheck_timer','input',NULL,'0','0','large','0','The number of seconds after which the session is checked again against database to see if the user still exists and is active.','trim|greater_than_equal_to[0]',450,'Session Recheck Timer','0','2018-05-29 13:08:01',1),
  (81, 'login', 'ad_code', 'textarea', NULL, '0', '0', 'large', '0', 'Paste your adSense Ad Code here', 'trim', 361, 'adSense Ad Code', '', '2017-08-18 22:11:00', 1),
  (82, 'login', 'ad_verify', 'textarea', NULL, '0', '0', 'large', '0', 'Paste your adSense Ad Site Verification Code here', 'trim', 361, 'adSense Ad Site Verification Code', '', '2018-06-05 14:30:53', 1);

/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table taxes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `taxes`;

CREATE TABLE `taxes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) DEFAULT NULL,
  `rate_type` enum('percent','fixed') DEFAULT NULL,
  `rate` tinyint(4) DEFAULT NULL,
  `net_price` enum('including','excluding') DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `taxes` WRITE;
/*!40000 ALTER TABLE `taxes` DISABLE KEYS */;

INSERT INTO `taxes` (`id`, `title`, `rate_type`, `rate`, `net_price`, `status`, `date_added`, `date_updated`)
VALUES
	(1,'standard tax','fixed',123,'excluding',1,'2017-04-28 18:59:30','2017-05-06 18:18:56'),
	(2,'standard vat','percent',15,'including',1,'2017-05-04 18:53:08','2017-05-09 20:08:36');

/*!40000 ALTER TABLE `taxes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table testimonials
# ------------------------------------------------------------

DROP TABLE IF EXISTS `testimonials`;

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `t_name` varchar(128) DEFAULT NULL,
  `t_type` varchar(64) DEFAULT NULL,
  `t_feedback` varchar(256) DEFAULT NULL,
  `image` varchar(128) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;

INSERT INTO `testimonials` (`id`, `t_name`, `t_type`, `t_feedback`, `image`, `date_added`, `date_updated`, `status`)
VALUES
	(1,'Zhang San','Customer','Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...','1499846360602.jpg','2017-07-12 13:29:20','2017-07-12 13:29:20',1),
	(2,'Ruby Von Rails','Student','Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...','1499846425441.jpg','2017-07-12 13:30:25','2017-07-12 13:30:25',1),
	(3,'Niles Peppertrout','Client','Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...','1499846484676.jpg','2017-07-12 13:31:24','2017-07-12 13:31:24',1),
	(4,'Bailey Wonger','Student','Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...','1499846534907.jpg','2017-07-12 13:32:14','2017-07-12 13:32:14',1);

/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table transactions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `transactions`;

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payer_email` varchar(256) DEFAULT NULL,
  `payer_id` varchar(256) DEFAULT NULL,
  `payer_status` varchar(256) DEFAULT NULL,
  `payer_name` varchar(256) DEFAULT NULL,
  `payer_address` text,
  `txn_id` text,
  `currency` varchar(10) DEFAULT NULL,
  `total_amount` decimal(10,0) DEFAULT NULL,
  `protection_eligibility` varchar(20) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT NULL,
  `pending_reason` varchar(256) DEFAULT NULL,
  `payment_type` varchar(128) DEFAULT NULL,
  `item_name` varchar(256) DEFAULT NULL,
  `item_number` varchar(128) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `txn_type` varchar(128) DEFAULT NULL,
  `payment_date` varchar(128) DEFAULT NULL,
  `business` varchar(256) DEFAULT NULL,
  `notify_version` varchar(128) DEFAULT NULL,
  `verify_sign` text,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(128) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `salt` varchar(128) DEFAULT NULL,
  `first_name` varchar(128) DEFAULT NULL,
  `last_name` varchar(128) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `dob` date NOT NULL DEFAULT '1994-09-20',
  `profession` varchar(256) DEFAULT NULL,
  `experience` tinyint(4) DEFAULT NULL COMMENT 'in years',
  `about` varchar(256) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `address` varchar(256) DEFAULT NULL,
  `role` tinyint(4) NOT NULL DEFAULT '3' COMMENT '1:admin;2:tutors;3:customers',
  `image` varchar(256) DEFAULT NULL,
  `language` varchar(64) DEFAULT NULL,
  `fb_uid` varchar(256) DEFAULT NULL,
  `fb_token` mediumtext,
  `g_uid` varchar(256) DEFAULT NULL,
  `g_token` text,
  `ip_address` varchar(45) DEFAULT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `last_login` int(11) DEFAULT NULL,
  `active` tinyint(4) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `username`, `password`, `salt`, `first_name`, `last_name`, `gender`, `dob`, `profession`, `experience`, `about`, `email`, `mobile`, `address`, `role`, `image`, `language`, `fb_uid`, `fb_token`, `g_uid`, `g_token`, `ip_address`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `company`, `phone`, `date_added`, `date_updated`)
VALUES
	(1,'admin','$2y$08$eTaVR9aEQAVcCKiA7X2COO.jy0FMo8d2kF44o8s65ZXTFCvAFtXN6','66cb0ab1d9efe250b46e28ecb45eb33b3609f1efda37547409a113a2b84c3f94b6a0e738acc391e2dfa718676aa55adead05fcb12d2e32aee379e190511a3252','super','admin','male','1994-09-20','administrator',5,'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellendus, quaerat beatae nulla debitis vitae temporibus enim sed. Optio, reprehenderit, ex.Repellendus, quaerat beatae nulla debitis vitae','admin@admin.com','948399303','anonymous street',1,'1499754204581.png','english','google','','',NULL,'127.0.0.1',NULL,NULL,NULL,'vOnjZ2huWjTUejxtrLepSu',1268889823,1528094172,1,'IBS',NULL,'2013-01-01 00:00:00','2018-06-04 12:06:12'),
	(38,'john.doe','$2y$08$0b0FPbmDZ65fNty7AFwpkeK2YPfmuiHmA8p24DEHjXKeKXKJ1jJ6y','/baAykEvbbpbYiibNxVWH.','john','doe','male','1990-05-16','fitness trainer',5,'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua','john.doe@mail.com','9876543210','it is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.',2,'1499781701502.jpg','english',NULL,NULL,NULL,NULL,'::1','8a08b4e7078a36040ecf00adbd7892426266fc23',NULL,NULL,NULL,1499781701,NULL,1,NULL,NULL,'2017-07-11 19:31:41','2017-07-11 19:38:48'),
	(39,'jane.smith','$2y$08$bvxVyeju7rXEgpmTe2jWuebAYzydz6O0GmRS9sNAeFsq6oTWOb7q6','4tjbU2cI/VxIIawzJ1JO7e','jane','smith','male','1984-07-10','fitness trainer',4,'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua','jane.smith@mail.com','9876543210','lorem lipsum sit annum else where',2,'1499829871379.jpg','english',NULL,NULL,NULL,NULL,'::1','6b1858f1993e5a45f455eaa35097222f2a8638e8',NULL,NULL,NULL,1499781797,NULL,1,NULL,NULL,'2017-07-11 19:33:17','2017-07-12 08:54:31'),
	(40,'simon.campos','$2y$08$FziAViMq4XK.HbNlqmKF8OBe8fgjsVvETfTZYraTj0UPbqXcxGNZy','IBJigU58.TDmWWuBzxyDlO','simon','campos','male','1989-07-21','yoga trainer',7,'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua','simon.campos@mail.com','9876543210','lorem ipsum dolor sit amet, consectetur adipiscing elit.',2,'149978238952.jpg','english',NULL,NULL,NULL,NULL,'::1','45b58cae44d10d43d62d21ecd53e055c96957e76',NULL,NULL,NULL,1499782389,NULL,1,NULL,NULL,'2017-07-11 19:43:09','2017-07-11 19:43:29'),
	(41,'jeanette.sullivan','$2y$08$bgj8jbrwu66Lc4QlBZ1H1.Ts3OP6mjUpAEL6yIE3IMWoPyBrUhKEW','4o5oO3UB9GdcRID7gDMLO.','jeanette','sullivan','female','1989-11-21','yoga trainer',6,'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua','jeanette.sullivan@mail.com','9876543210','lorem ipsum dolor sit amet, consectetur adipiscing elit.',2,'1499782530895.jpg','english',NULL,NULL,NULL,NULL,'::1','1773b914c3837e08d5169c310d0a9f90078d5a84',NULL,NULL,NULL,1499782530,NULL,1,NULL,NULL,'2017-07-11 19:45:30','2017-07-11 19:46:05'),
	(42,'meaghan.park','$2y$08$yNLHTjv7GjzA7ZFxVpwoROoP6JT.SwkSNKCldA7HRvj/2nyPfkJoy','QEqn3WrtvKb40WBjSdC4uO','meaghan','park','female','1990-07-11','dance teacher',5,'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua','meaghan.park@mail.com','9877654320','lorem ipsum dolor sit amet, consectetur adipiscing elit.',2,'1499782666894.jpg','english',NULL,NULL,NULL,NULL,'::1','fd32653d988b74a081a700304c3f12be99213cfa',NULL,NULL,NULL,1499782666,NULL,1,NULL,NULL,'2017-07-11 19:47:46','2017-07-11 19:49:33'),
	(43,'kendall.zimmerman','$2y$08$XIlj6v60mivgIQgg6PpM/eg.LrNJBnKA5zv4Yu0Ppe.qLL4yE88cq','a5pDqZVn/YhwG20Gpxu74.','kendall','zimmerman','male','1985-08-21','music teacher',8,'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua','kendall.zimmerman@mail.com','9876543210','lorem ipsum dolor sit amet, consectetur adipiscing elit.',2,'1499782755203.jpg','english',NULL,NULL,NULL,NULL,'::1','9ac4cfe29a23ad5e410fb3906f234675605c1e2e',NULL,NULL,NULL,1499782755,NULL,1,NULL,NULL,'2017-07-11 19:49:15','2017-07-11 19:49:43'),
	(44,'brendan.chavez','$2y$08$HNXlPYtQB47U6ML2mATskO0YUCqUoyPawM6Bwj3VDV9YBZVwMwjdS','NMrh1GLuTWjgjAkYC51cte','brendan','chavez','female','1990-07-18','event manager',7,'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua','brendan.chavez@mail.com','9876543210','lorem ipsum dolor sit amet, consectetur adipiscing elit.',2,'1499783564646.jpg','english',NULL,NULL,NULL,NULL,'::1','372607e6845456b1cb9738156529c740e63d956a',NULL,NULL,NULL,1499783565,NULL,1,NULL,NULL,'2017-07-11 20:02:45','2017-07-11 20:03:04'),
	(45,'deepak.destinyahead','$2y$08$Qhe..vUkCGqxnYcE.03tr.HVw7sPMtr2yoYpCyCWQKy3Oy92uJFLu',NULL,'Deepak','Panwar','male','1994-09-20',NULL,NULL,NULL,'deepak.destinyahead@gmail.com','9876554320','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.',3,'1499843602542.jpg','english','1968757146688799','EAAGXwPuwojIBAEWgkmHY7KoLHhDCrg0pv3elHEwgXp4ulAuPanDK3q86fT4R3oTaymfPjxSaJDD3jCvK0dcXD7SfW2hi6dV8spy5zmRcZBmc5yQpxp7Yecu8cinDxptibznSxvDZBL1hXA0O6RlZANvTXswicoqgKyvTZBZBZCKzkAO1QE842QappG9mfplaAZD',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,'2017-07-12 12:38:49','2018-05-27 12:49:45');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users_groups`;

CREATE TABLE `users_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  UNIQUE KEY `user_id` (`user_id`,`group_id`),
  KEY `fk_users_groups_users1_idx` (`user_id`),
  KEY `fk_users_groups_groups1_idx` (`group_id`),
  CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

LOCK TABLES `users_groups` WRITE;
/*!40000 ALTER TABLE `users_groups` DISABLE KEYS */;

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`)
VALUES
	(3,1,1),
	(6,38,2),
	(19,39,2),
	(9,40,2),
	(11,41,2),
	(14,42,2),
	(15,43,2),
	(17,44,2),
	(20,45,3);

/*!40000 ALTER TABLE `users_groups` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table visitors
# ------------------------------------------------------------

DROP TABLE IF EXISTS `visitors`;

CREATE TABLE `visitors` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_ip` varchar(256) DEFAULT NULL,
  `total_visits` int(11) DEFAULT NULL,
  `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `visitors` WRITE;
/*!40000 ALTER TABLE `visitors` DISABLE KEYS */;

INSERT INTO `visitors` (`id`, `user_ip`, `total_visits`, `date_added`, `date_updated`)
VALUES
	(1,'::1',318,'2017-07-11 11:43:43','2017-07-15 12:21:04'),
	(2,'127.0.0.1',834,'2017-07-12 12:57:16','2018-06-04 12:22:53');

/*!40000 ALTER TABLE `visitors` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
