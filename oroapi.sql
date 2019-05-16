/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 5.1.41 : Database - oroapi
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`oroapi` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `oroapi`;

/*Table structure for table `employee_masterlist` */

DROP TABLE IF EXISTS `employee_masterlist`;

CREATE TABLE `employee_masterlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Employee_code` char(25) DEFAULT NULL,
  `Firstname` char(25) DEFAULT NULL,
  `Middlename` char(25) DEFAULT NULL,
  `Lastname` char(25) DEFAULT NULL,
  `Address` char(120) DEFAULT NULL,
  `Contact` int(12) DEFAULT NULL,
  `Position` char(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `employee_masterlist` */

/*Table structure for table `list_of_charts_of_accounts` */

DROP TABLE IF EXISTS `list_of_charts_of_accounts`;

CREATE TABLE `list_of_charts_of_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account_list` char(25) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'account type ex. Liabality, assets etc.',
  `account_no` int(11) DEFAULT NULL,
  `account_title` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_description` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `list_of_charts_of_accounts` */

insert  into `list_of_charts_of_accounts`(`id`,`account_list`,`account_no`,`account_title`,`account_description`,`created_at`,`updated_at`) values (14,'asset',1001,'cash','petty cash funds','2019-02-04 08:34:24','2019-02-04 08:34:24');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values (5,'2014_10_12_000000_create_users_table',1),(6,'2014_10_12_100000_create_password_resets_table',1),(7,'2019_01_29_062855_create_module_auths_table',1),(8,'2019_01_29_063608_create_modules_table',1),(9,'2019_02_04_033730_create_list_of_charts_of_accounts_table',2);

/*Table structure for table `module_auths` */

DROP TABLE IF EXISTS `module_auths`;

CREATE TABLE `module_auths` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` int(11) DEFAULT NULL,
  `create` tinyint(2) DEFAULT '0',
  `read` tinyint(2) DEFAULT '0',
  `update` tinyint(2) DEFAULT '0',
  `delete` tinyint(2) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `module_auths` */

insert  into `module_auths`(`id`,`module_id`,`create`,`read`,`update`,`delete`,`created_at`,`updated_at`,`user_id`) values (3,3,1,1,1,1,NULL,NULL,6),(31,4,1,1,1,0,NULL,NULL,6),(32,5,1,1,1,0,NULL,NULL,6),(6,6,1,1,1,1,NULL,NULL,6),(7,3,1,1,1,1,NULL,NULL,14),(8,4,1,1,1,1,NULL,NULL,14),(9,5,1,1,0,1,NULL,NULL,14),(10,6,1,1,1,1,NULL,NULL,14),(11,9,1,1,1,1,NULL,NULL,6),(12,9,1,1,1,1,NULL,NULL,14),(13,10,1,1,1,1,NULL,NULL,6),(62,25,1,1,1,1,NULL,NULL,15),(20,11,1,1,1,1,NULL,NULL,6),(34,4,1,0,0,0,NULL,NULL,16),(42,12,1,0,0,0,NULL,NULL,6),(41,12,1,1,1,1,NULL,NULL,15),(43,13,0,1,0,0,NULL,NULL,6),(44,15,1,1,1,1,NULL,NULL,6),(45,14,1,1,1,1,NULL,NULL,6),(46,16,1,1,1,1,NULL,NULL,6),(47,15,1,1,1,1,NULL,NULL,16),(48,14,1,1,1,1,NULL,NULL,16),(49,17,1,1,1,1,NULL,NULL,6),(51,22,1,1,1,0,NULL,NULL,6),(53,19,0,0,0,0,NULL,NULL,6),(56,25,0,0,0,0,NULL,NULL,6),(60,24,0,0,0,0,NULL,NULL,6);

/*Table structure for table `modules` */

DROP TABLE IF EXISTS `modules`;

CREATE TABLE `modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_description` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `module_url` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `system_use` char(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_menu` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `modules` */

insert  into `modules`(`id`,`module_description`,`module_url`,`created_at`,`updated_at`,`system_use`,`parent_menu`) values (1,'Set Up Chart Of Accounts','setUpChartOfAccounts',NULL,NULL,'COA',0),(2,'Chart Of Accounts','chartofaccounts',NULL,NULL,'COA',0),(3,'Received Inventory','receivedInventory',NULL,NULL,'IFT',25),(4,'Inventory Monitoring Repo','monitoringreport',NULL,NULL,'IFT',25),(5,'Create PO','createPO',NULL,NULL,'IFT',25),(6,'PO Served','poServe',NULL,NULL,'IFT',25),(10,'Actual Inventory','actualinventory',NULL,NULL,'IFT',25),(9,'Store Mapping','storeMapping',NULL,NULL,'IFT',25),(11,'Inventory History','inventoryHistory',NULL,NULL,'IFT',25),(15,'Payment Entry','ongoing',NULL,NULL,'COD',22),(14,'Delivery Details','paymententry',NULL,NULL,'COD',22),(16,'List Of Delivery','listdelivery',NULL,NULL,'COD',22),(17,'Change Credentials','changecredential',NULL,NULL,'COD',22),(18,'Test','testing',NULL,NULL,'COD',0),(19,'Test1','test1',NULL,NULL,'COD',18),(22,'COD - Transactions','transactions',NULL,NULL,'COD',0),(23,'Test Parent 2','parent2',NULL,NULL,'COD',0),(24,'Test2','test2',NULL,NULL,'COD',23),(25,'IFT - Transactions','transactions',NULL,NULL,'IFT',0),(26,'Test parent 22','parent2',NULL,NULL,'COD',0),(27,'TestParent 3','lol',NULL,NULL,'COD',0),(28,'Test 22','ddawdwa',NULL,NULL,'COD',26),(29,'TestParent','testParent',NULL,NULL,'IFT',0);

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `usergroup` int(5) DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `temp_token` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`username`,`password`,`usergroup`,`remember_token`,`created_at`,`updated_at`,`temp_token`) values (6,'seph','jccruz@exxelprime.com','sephs','$2y$10$ZoSvrNBdSpnli358ouadee17BG0.onrBKTGL2d7s26PaCUJtYxSma',0,NULL,'2019-01-30 03:53:57','2019-04-15 08:08:12',NULL),(16,'RENSES SABADO','Renses@test.com','RENSES','$2y$10$dWdyCSSwu1qunI5jkf199u4JkSGAV9FmuT0Pn2MBh/wxtyH8.Uxc2',2,NULL,'2019-03-14 08:11:53','2019-03-14 08:11:53',NULL),(15,'gerry','gmconrado@gmail.com','gerry','$2y$10$jrFV9ujR2JKzpOMP/BDyweBxhqjhAhOsnc0N7sj0vyFZz6uUghutS',0,NULL,'2019-03-14 07:24:40','2019-03-14 07:24:40',NULL),(19,'TINA ISIP',NULL,'TINA','$2y$10$FSjjzKPgFSABQQ0NMP0mveciZRu4DQNOL9Zyu0YRclL2FdC.Be4FC',2,NULL,NULL,NULL,NULL),(21,'KATHY',NULL,'KATHY','$2y$10$EUpwHGK5cHuYs5X/7nml8.BUB9yb7dPaVLEOtp5YU14EGxwDZTMjO',2,NULL,NULL,NULL,NULL),(22,'KURT MICHAEL SIYTAOCO',NULL,'kurt','$2y$10$DD7Z/IlJ0omzTZ7MUeTU.ucHXIUrInH1NuxXQYDkV04zxjwtDWNTK',NULL,NULL,NULL,NULL,NULL),(23,'fwawa','fdawd@gmail.com','seps','$2y$10$LB4fem/649ODtqiDwFKTKeZqSORuvRf2gUMORxmfA2xZUCfaJu3Ce',2,NULL,'2019-04-15 06:46:47','2019-04-15 08:07:09',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
