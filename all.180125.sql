-- MySQL dump 10.13  Distrib 5.7.17, for macos10.12 (x86_64)
--
-- Host: localhost    Database: adm
-- ------------------------------------------------------
-- Server version	5.7.15

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `adm_category`
--

DROP TABLE IF EXISTS `adm_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adm_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adm_category`
--

LOCK TABLES `adm_category` WRITE;
/*!40000 ALTER TABLE `adm_category` DISABLE KEYS */;
INSERT INTO `adm_category` VALUES (1,'分类一','C1',10,1,'2017-09-27 17:06:34','2017-09-27 17:06:34'),(2,'分类二','C2',10,1,'2017-09-27 17:06:53','2017-09-27 17:06:53');
/*!40000 ALTER TABLE `adm_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `adm_post_click_log`
--

DROP TABLE IF EXISTS `adm_post_click_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adm_post_click_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `click_reward` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adm_post_click_log`
--

LOCK TABLES `adm_post_click_log` WRITE;
/*!40000 ALTER TABLE `adm_post_click_log` DISABLE KEYS */;
INSERT INTO `adm_post_click_log` VALUES (2,2,3,0.00,'2018-01-06 15:25:22','2018-01-06 15:25:22');
/*!40000 ALTER TABLE `adm_post_click_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `adm_posts`
--

DROP TABLE IF EXISTS `adm_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adm_posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` smallint(5) unsigned NOT NULL,
  `total_price` decimal(8,2) NOT NULL,
  `click_price` decimal(8,2) NOT NULL,
  `click_times` int(10) unsigned NOT NULL DEFAULT '1',
  `has_click_times` int(10) unsigned NOT NULL DEFAULT '0',
  `last_days` smallint(5) unsigned NOT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `start_hour` int(11) NOT NULL,
  `end_hour` int(11) NOT NULL,
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `posts_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pay_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0',
  `up_times` smallint(5) unsigned NOT NULL DEFAULT '0',
  `down_times` smallint(5) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adm_posts`
--

LOCK TABLES `adm_posts` WRITE;
/*!40000 ALTER TABLE `adm_posts` DISABLE KEYS */;
INSERT INTO `adm_posts` VALUES (1,1,'真的么',2,0.00,1.00,2555,0,25,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20171013/1507829057451103.png','能不能发布成功我也不知道','',0,0,NULL,0,0,0,'2017-10-12 17:25:21','2017-10-12 17:25:21'),(2,1,'真的好么',2,0.00,1.00,555555,1,21,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20171013/1507830361999045.png','谁说女子不如男，你是我的','',1,1,NULL,0,4,1,'2017-10-12 17:46:11','2018-01-06 15:25:22'),(3,1,'真的好么',2,0.00,1.00,555555,0,21,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20171013/1507830361999045.png','谁说女子不如男','',1,1,NULL,0,0,0,'2017-10-12 17:47:00','2017-10-17 08:36:27'),(4,1,'真的好么',2,0.00,1.00,555555,0,21,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20171013/1507830361999045.png','谁说女子不如男','',1,1,NULL,0,0,0,'2017-10-12 17:47:30','2017-10-17 08:36:09'),(5,1,'真的好么',2,0.00,1.00,555555,0,21,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20171013/1507830361999045.png','谁说女子不如男','',1,1,NULL,0,2,0,'2017-10-12 17:47:56','2017-10-15 03:23:23'),(6,1,'这样真的好吗',2,0.00,0.50,4000,0,21,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20171013/1507878497593968.png','大家快来赞我','',1,1,NULL,0,1,23,'2017-10-13 07:08:24','2017-10-14 15:55:38'),(7,3,'这是一个广告标题',2,0.00,0.20,10000,0,15,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20180102/1514882499331886.jpeg','HelloHello   q你知道这里有什么呈return response()->json([\n					\'errcode\' => 10001,\n					\'msg\' => \'您选择的广告分类没找到哦\'','',0,0,NULL,0,0,0,'2018-01-02 08:41:39','2018-01-02 16:15:11'),(8,3,'这是一个广告标题',2,0.00,0.10,10001,0,15,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20180106/1515252792260583.jpeg','不能上传图片','',0,0,NULL,0,0,0,'2018-01-06 15:33:13','2018-01-06 15:33:13'),(9,1,'什么东西',1,0.00,0.10,10000,0,15,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20180120/1516455752391801.png','为什么会这样的','',1,1,NULL,0,0,0,'2018-01-20 13:42:33','2018-01-20 13:42:33'),(10,1,'测试的',1,0.00,0.10,100000,0,15,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20180122/1516622506693589.jpeg','这是什么鬼','#',1,1,NULL,0,0,0,'2018-01-22 12:01:46','2018-01-22 12:01:46'),(11,1,'测试的',1,0.00,0.10,100000,0,15,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20180122/1516622506693589.jpeg','这是什么鬼','#',1,1,NULL,0,0,0,'2018-01-22 12:05:07','2018-01-22 12:05:07'),(12,1,'测试的',1,0.00,0.10,100000,0,15,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20180122/1516622506693589.jpeg','这是什么鬼','#',1,1,NULL,0,0,0,'2018-01-22 12:05:48','2018-01-22 12:05:48'),(13,1,'测试的',1,0.00,0.10,100000,0,15,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20180122/1516622506693589.jpeg','这是什么鬼','#',1,1,NULL,0,0,0,'2018-01-22 12:05:49','2018-01-22 12:05:49'),(14,1,'测试的',1,0.00,0.10,100000,0,15,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20180122/1516622506693589.jpeg','这是什么鬼','#',1,1,NULL,0,0,0,'2018-01-22 12:05:56','2018-01-22 12:05:56'),(15,1,'测试的',1,0.00,0.10,100000,0,15,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20180122/1516622506693589.jpeg','这是什么鬼','#',1,1,NULL,0,0,0,'2018-01-22 12:07:13','2018-01-22 12:07:13'),(16,1,'测试的',1,0.00,0.10,100000,0,15,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20180122/1516622506693589.jpeg','这是什么鬼','#',1,1,NULL,0,0,0,'2018-01-22 12:07:28','2018-01-22 12:07:28'),(17,1,'测试的',1,0.00,0.10,100000,0,15,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20180122/1516622506693589.jpeg','这是什么鬼','#',1,1,NULL,0,0,0,'2018-01-22 12:08:37','2018-01-22 12:08:37'),(18,1,'测试的',1,0.00,0.10,100000,0,15,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20180122/1516622506693589.jpeg','这是什么鬼','#',1,1,NULL,0,0,0,'2018-01-22 12:10:07','2018-01-22 12:10:07'),(19,1,'测试的',1,0.00,0.10,100000,0,15,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20180122/1516622506693589.jpeg','这是什么鬼','#',0,0,NULL,0,0,0,'2018-01-22 12:10:53','2018-01-22 12:10:53'),(20,1,'测试的',1,0.00,0.10,100000,0,15,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20180122/1516622506693589.jpeg','这是什么鬼','#',0,0,NULL,0,0,0,'2018-01-22 12:10:57','2018-01-22 12:10:57'),(21,1,'测试的',1,0.00,0.10,100000,0,15,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20180122/1516622506693589.jpeg','这是什么鬼','#',1,1,NULL,0,0,0,'2018-01-22 12:11:53','2018-01-22 12:11:53'),(22,1,'测试的',1,10000.00,0.10,100000,0,15,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20180122/1516622506693589.jpeg','这是什么鬼','#',0,0,NULL,0,0,0,'2018-01-22 12:13:32','2018-01-22 12:13:32'),(23,1,'测试的',1,10000.00,0.10,100000,0,15,NULL,NULL,0,24,'/uploads/ueditor/php/upload/image/20180122/1516622506693589.jpeg','这是什么鬼','#',0,0,NULL,0,0,0,'2018-01-22 12:13:48','2018-01-22 12:13:48');
/*!40000 ALTER TABLE `adm_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `adm_posts_praise_log`
--

DROP TABLE IF EXISTS `adm_posts_praise_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adm_posts_praise_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adm_posts_praise_log`
--

LOCK TABLES `adm_posts_praise_log` WRITE;
/*!40000 ALTER TABLE `adm_posts_praise_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `adm_posts_praise_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `adm_user_cash`
--

DROP TABLE IF EXISTS `adm_user_cash`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adm_user_cash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `event` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_id` int(10) unsigned NOT NULL,
  `target_desc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `act` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cash` decimal(8,2) NOT NULL,
  `extra_cash` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adm_user_cash`
--

LOCK TABLES `adm_user_cash` WRITE;
/*!40000 ALTER TABLE `adm_user_cash` DISABLE KEYS */;
INSERT INTO `adm_user_cash` VALUES (1,1,'create_post',5,'发布广告:真的好么','out',0.00,0.00,'2017-10-12 17:47:56','2017-10-12 17:47:56'),(2,1,'create_post',6,'发布广告:这样真的好吗','out',0.00,0.00,'2017-10-13 07:08:24','2017-10-13 07:08:24'),(3,1,'create_post',4,'发布广告:真的好么','out',0.00,0.00,'2017-10-17 08:36:09','2017-10-17 08:36:09'),(4,1,'create_post',3,'发布广告:真的好么','out',0.00,0.00,'2017-10-17 08:36:27','2017-10-17 08:36:27'),(5,1,'create_post',2,'发布广告:真的好么','out',0.00,0.00,'2017-10-17 09:26:42','2017-10-17 09:26:42'),(6,1,'bigsmall',3,'发起比大小战斗','out',235.00,0.00,'2017-11-15 03:45:15','2017-11-15 03:45:15'),(7,1,'bigsmall',4,'发起比大小战斗','out',100.00,0.00,'2017-11-15 03:48:51','2017-11-15 03:48:51'),(8,1,'bigsmall',5,'发起比大小战斗','out',23.00,0.00,'2017-11-15 03:51:29','2017-11-15 03:51:29'),(9,1,'bigsmall',6,'发起比大小战斗','out',22.00,0.00,'2017-11-15 03:54:36','2017-11-15 03:54:36'),(10,2,'bigsmallrefund',3,'比大小拒绝战斗退还保证金','in',235.00,0.00,'2017-11-21 06:55:29','2017-11-21 06:55:29'),(11,1,'bigsmall',7,'发起比大小战斗','out',12.00,0.00,'2017-12-13 09:26:18','2017-12-13 09:26:18'),(12,1,'stolen',1,'偷取','in',4073.00,0.00,'2017-12-13 12:33:38','2017-12-13 12:33:38'),(13,2,'stolen',1,'被偷取','out',4073.00,0.00,'2017-12-13 12:33:38','2017-12-13 12:33:38'),(14,1,'stolen',2,'偷取','in',1178.00,0.00,'2017-12-13 12:33:51','2017-12-13 12:33:51'),(15,2,'stolen',2,'被偷取','out',1178.00,0.00,'2017-12-13 12:33:51','2017-12-13 12:33:51'),(16,1,'stolen',3,'偷取','in',5686.00,0.00,'2017-12-13 12:34:31','2017-12-13 12:34:31'),(17,2,'stolen',3,'被偷取','out',5686.00,0.00,'2017-12-13 12:34:31','2017-12-13 12:34:31'),(18,1,'stolen',4,'偷取','in',4074.00,0.00,'2017-12-22 02:51:49','2017-12-22 02:51:49'),(19,2,'stolen',4,'被偷取','out',4074.00,0.00,'2017-12-22 02:51:49','2017-12-22 02:51:49'),(20,3,'stolen',5,'偷取','in',8819.00,0.00,'2017-12-25 07:27:29','2017-12-25 07:27:29'),(21,1,'stolen',5,'被偷取','out',8819.00,0.00,'2017-12-25 07:27:29','2017-12-25 07:27:29'),(22,3,'stolen',6,'偷取','in',2744.00,0.00,'2017-12-25 07:41:19','2017-12-25 07:41:19'),(23,1,'stolen',6,'被偷取','out',2744.00,0.00,'2017-12-25 07:41:19','2017-12-25 07:41:19'),(24,3,'stolen',7,'偷取','in',1792.00,0.00,'2017-12-25 07:53:17','2017-12-25 07:53:17'),(25,1,'stolen',7,'被偷取','out',1792.00,0.00,'2017-12-25 07:53:17','2017-12-25 07:53:17'),(26,2,'stolen',8,'偷取','in',1255.00,0.00,'2017-12-25 09:02:56','2017-12-25 09:02:56'),(27,3,'stolen',8,'被偷取','out',1255.00,0.00,'2017-12-25 09:02:57','2017-12-25 09:02:57'),(28,3,'stolen',9,'偷取','in',924.00,0.00,'2017-12-25 09:03:48','2017-12-25 09:03:48'),(29,2,'stolen',9,'被偷取','out',924.00,0.00,'2017-12-25 09:03:48','2017-12-25 09:03:48'),(30,2,'stolen',10,'偷取','in',472.00,0.00,'2017-12-25 09:06:06','2017-12-25 09:06:06'),(31,3,'stolen',10,'被偷取','out',472.00,0.00,'2017-12-25 09:06:06','2017-12-25 09:06:06'),(32,2,'stolen',11,'偷取','in',598.00,0.00,'2017-12-25 09:08:59','2017-12-25 09:08:59'),(33,3,'stolen',11,'被偷取','out',598.00,0.00,'2017-12-25 09:08:59','2017-12-25 09:08:59'),(34,2,'stolen',12,'偷取','in',123.00,0.00,'2017-12-25 09:12:35','2017-12-25 09:12:35'),(35,3,'stolen',12,'被偷取','out',123.00,0.00,'2017-12-25 09:12:35','2017-12-25 09:12:35'),(36,3,'stolen',13,'偷取','in',6320.00,0.00,'2017-12-25 09:15:39','2017-12-25 09:15:39'),(37,2,'stolen',13,'被偷取','out',6320.00,0.00,'2017-12-25 09:15:39','2017-12-25 09:15:39'),(38,2,'stolen',14,'偷取','in',930.00,0.00,'2017-12-25 09:15:47','2017-12-25 09:15:47'),(39,3,'stolen',14,'被偷取','out',930.00,0.00,'2017-12-25 09:15:47','2017-12-25 09:15:47'),(40,2,'stolen',15,'偷取','in',400.00,0.00,'2017-12-25 09:16:37','2017-12-25 09:16:37'),(41,3,'stolen',15,'被偷取','out',400.00,0.00,'2017-12-25 09:16:37','2017-12-25 09:16:37'),(42,2,'stolen',16,'偷取','in',1398.00,0.00,'2017-12-25 09:17:09','2017-12-25 09:17:09'),(43,3,'stolen',16,'被偷取','out',1398.00,0.00,'2017-12-25 09:17:09','2017-12-25 09:17:09'),(44,3,'stolen',17,'偷取','in',4522.00,0.00,'2017-12-25 09:17:41','2017-12-25 09:17:41'),(45,2,'stolen',17,'被偷取','out',4522.00,0.00,'2017-12-25 09:17:41','2017-12-25 09:17:41'),(46,2,'stolen',18,'偷取','in',1517.00,0.00,'2017-12-25 09:18:26','2017-12-25 09:18:26'),(47,3,'stolen',18,'被偷取','out',1517.00,0.00,'2017-12-25 09:18:26','2017-12-25 09:18:26'),(48,2,'stolen',19,'偷取','in',623.00,0.00,'2017-12-25 09:18:57','2017-12-25 09:18:57'),(49,3,'stolen',19,'被偷取','out',623.00,0.00,'2017-12-25 09:18:57','2017-12-25 09:18:57'),(50,2,'stolen',20,'偷取','in',1471.00,0.00,'2017-12-25 09:19:41','2017-12-25 09:19:41'),(51,3,'stolen',20,'被偷取','out',1471.00,0.00,'2017-12-25 09:19:41','2017-12-25 09:19:41'),(52,2,'stolen',21,'偷取','in',1587.00,0.00,'2017-12-25 09:19:58','2017-12-25 09:19:58'),(53,3,'stolen',21,'被偷取','out',1587.00,0.00,'2017-12-25 09:19:58','2017-12-25 09:19:58'),(54,2,'stolen',22,'偷取','in',101.00,0.00,'2017-12-25 09:20:32','2017-12-25 09:20:32'),(55,3,'stolen',22,'被偷取','out',101.00,0.00,'2017-12-25 09:20:32','2017-12-25 09:20:32'),(56,2,'stolen',23,'偷取','in',903.00,0.00,'2017-12-25 09:21:27','2017-12-25 09:21:27'),(57,3,'stolen',23,'被偷取','out',903.00,0.00,'2017-12-25 09:21:27','2017-12-25 09:21:27'),(58,3,'stolen',24,'偷取','in',6959.00,0.00,'2017-12-25 09:27:06','2017-12-25 09:27:06'),(59,2,'stolen',24,'被偷取','out',6959.00,0.00,'2017-12-25 09:27:06','2017-12-25 09:27:06'),(60,3,'bigsmall',8,'发起比大小战斗','out',50.00,0.00,'2017-12-25 10:14:12','2017-12-25 10:14:12'),(61,3,'stolen',25,'偷取','in',1905.00,0.00,'2018-01-05 05:06:50','2018-01-05 05:06:50'),(62,1,'stolen',25,'被偷取','out',1905.00,0.00,'2018-01-05 05:06:50','2018-01-05 05:06:50'),(63,3,'stolen',26,'偷取','in',9826.00,0.00,'2018-01-05 16:05:51','2018-01-05 16:05:51'),(64,1,'stolen',26,'被偷取','out',9826.00,0.00,'2018-01-05 16:05:51','2018-01-05 16:05:51'),(65,2,'bigsmall',9,'发起比大小战斗','out',100.00,0.00,'2018-01-08 12:54:44','2018-01-08 12:54:44'),(66,2,'bigsmall',10,'发起比大小战斗','out',100.00,0.00,'2018-01-08 12:55:26','2018-01-08 12:55:26'),(67,2,'bigsmall',11,'发起比大小战斗','out',100.00,0.00,'2018-01-08 12:56:00','2018-01-08 12:56:00'),(68,2,'bigsmall',12,'发起比大小战斗','out',10.00,0.00,'2018-01-08 12:58:58','2018-01-08 12:58:58'),(69,2,'bigsmall',13,'发起比大小战斗','out',50.00,0.00,'2018-01-08 13:01:25','2018-01-08 13:01:25'),(70,2,'bigsmall',14,'发起比大小战斗','out',50.00,0.00,'2018-01-08 13:03:35','2018-01-08 13:03:35'),(71,2,'bigsmall',15,'发起比大小战斗','out',50.00,0.00,'2018-01-08 13:03:57','2018-01-08 13:03:57'),(72,2,'bigsmall',16,'发起比大小战斗','out',200.00,0.00,'2018-01-08 13:51:16','2018-01-08 13:51:16'),(73,2,'bigsmall',17,'发起比大小战斗','out',200.00,0.00,'2018-01-08 13:51:46','2018-01-08 13:51:46'),(74,2,'bigsmall',18,'发起比大小战斗','out',200.00,0.00,'2018-01-08 13:52:20','2018-01-08 13:52:20'),(75,2,'bigsmallrefund',18,'比大小拒绝战斗退还保证金','in',200.00,0.00,'2018-01-08 16:22:54','2018-01-08 16:22:54'),(79,1,'bigsmall',17,'接受比大小战斗','out',200.00,0.00,'2018-01-08 16:26:24','2018-01-08 16:26:24'),(80,2,'bigsmallrefund',17,'比大小获胜退还保证金','in',200.00,0.00,'2018-01-08 16:26:24','2018-01-08 16:26:24'),(82,1,'bigsmall',16,'接受比大小战斗','out',200.00,0.00,'2018-01-08 16:28:18','2018-01-08 16:28:18'),(83,2,'bigsmallrefund',16,'比大小获胜退还保证金','in',200.00,0.00,'2018-01-08 16:28:18','2018-01-08 16:28:18'),(84,1,'bigsmallrefund',16,'比大小退还保证金','in',200.00,0.00,'2018-01-08 16:52:50','2018-01-08 16:52:50'),(85,2,'bigsmall',19,'发起比大小战斗','out',40.00,0.00,'2018-01-10 13:09:38','2018-01-10 13:09:38'),(86,1,'bigsmall',20,'发起比大小战斗','out',100.00,0.00,'2018-01-10 14:25:11','2018-01-10 14:25:11'),(87,1,'bigsmall',21,'发起比大小战斗','out',60.00,0.00,'2018-01-10 14:27:27','2018-01-10 14:27:27'),(88,1,'bigsmall',22,'发起比大小战斗','out',99.00,0.00,'2018-01-10 14:30:53','2018-01-10 14:30:53'),(89,1,'bigsmall',23,'发起比大小战斗','out',88.00,0.00,'2018-01-10 14:33:15','2018-01-10 14:33:15'),(90,1,'bigsmall',24,'发起比大小战斗','out',78.00,0.00,'2018-01-10 14:37:39','2018-01-10 14:37:39'),(91,1,'bigsmall',25,'发起比大小战斗','out',66.00,0.00,'2018-01-10 14:38:49','2018-01-10 14:38:49'),(92,2,'bigsmall',25,'接受比大小战斗','out',66.00,0.00,'2018-01-10 14:38:57','2018-01-10 14:38:57'),(93,1,'bigsmallrefund',25,'比大小获胜退还保证金','in',66.00,0.00,'2018-01-10 14:38:57','2018-01-10 14:38:57'),(94,2,'bigsmall',26,'发起比大小战斗','out',100.00,0.00,'2018-01-10 15:22:05','2018-01-10 15:22:05'),(96,1,'bigsmall',26,'接受比大小战斗','out',100.00,0.00,'2018-01-10 15:22:51','2018-01-10 15:22:51'),(97,2,'bigsmallrefund',26,'比大小获胜退还保证金','in',100.00,0.00,'2018-01-10 15:22:51','2018-01-10 15:22:51'),(98,1,'bigsmall',27,'发起比大小战斗','out',50.00,0.00,'2018-01-12 06:29:33','2018-01-12 06:29:33'),(99,1,'bigsmall',28,'发起比大小战斗','out',46.00,0.00,'2018-01-12 06:30:12','2018-01-12 06:30:12'),(100,1,'bigsmall',29,'发起比大小战斗','out',46.00,0.00,'2018-01-12 06:30:26','2018-01-12 06:30:26'),(101,1,'bigsmall',30,'发起比大小战斗','out',99.00,0.00,'2018-01-12 06:31:24','2018-01-12 06:31:24'),(102,1,'bigsmall',31,'发起比大小战斗','out',34.00,0.00,'2018-01-12 06:56:26','2018-01-12 06:56:26'),(103,1,'bigsmall',32,'发起比大小战斗','out',56.00,0.00,'2018-01-12 07:11:25','2018-01-12 07:11:25'),(104,1,'bigsmallrefund',32,'比大小拒绝战斗退还保证金','in',56.00,0.00,'2018-01-12 07:29:52','2018-01-12 07:29:52'),(107,2,'bigsmallrefund',19,'比大小拒绝战斗退还保证金','in',40.00,0.00,'2018-01-13 15:30:12','2018-01-13 15:30:12'),(109,1,'bigsmall',15,'接受比大小战斗','out',50.00,0.00,'2018-01-13 15:31:39','2018-01-13 15:31:39'),(110,2,'bigsmallrefund',15,'比大小获胜退还保证金','in',50.00,0.00,'2018-01-13 15:31:39','2018-01-13 15:31:39'),(111,1,'bigsmall',12,'接受比大小战斗','out',10.00,0.00,'2018-01-13 15:32:11','2018-01-13 15:32:11'),(112,2,'bigsmallrefund',12,'比大小获胜退还保证金','in',10.00,0.00,'2018-01-13 15:32:11','2018-01-13 15:32:11'),(113,1,'bigsmall',14,'接受比大小战斗','out',50.00,0.00,'2018-01-13 15:32:45','2018-01-13 15:32:45'),(114,2,'bigsmallrefund',14,'比大小获胜退还保证金','in',50.00,0.00,'2018-01-13 15:32:45','2018-01-13 15:32:45'),(118,1,'bigsmall',11,'接受比大小战斗','out',100.00,0.00,'2018-01-13 15:33:21','2018-01-13 15:33:21'),(119,2,'bigsmallrefund',11,'比大小获胜退还保证金','in',100.00,0.00,'2018-01-13 15:33:21','2018-01-13 15:33:21'),(120,1,'bigsmall',13,'接受比大小战斗','out',50.00,0.00,'2018-01-13 15:33:45','2018-01-13 15:33:45'),(121,2,'bigsmallrefund',13,'比大小获胜退还保证金','in',50.00,0.00,'2018-01-13 15:33:45','2018-01-13 15:33:45'),(122,2,'bigsmall',31,'接受比大小战斗','out',34.00,0.00,'2018-01-13 15:38:36','2018-01-13 15:38:36'),(123,1,'bigsmallrefund',31,'比大小获胜退还保证金','in',34.00,0.00,'2018-01-13 15:38:36','2018-01-13 15:38:36'),(124,2,'bigsmall',27,'接受比大小战斗','out',50.00,0.00,'2018-01-13 15:38:48','2018-01-13 15:38:48'),(125,1,'bigsmallrefund',27,'比大小获胜退还保证金','in',50.00,0.00,'2018-01-13 15:38:48','2018-01-13 15:38:48'),(127,2,'bigsmall',24,'接受比大小战斗','out',78.00,0.00,'2018-01-13 15:39:02','2018-01-13 15:39:02'),(128,1,'bigsmallrefund',24,'比大小获胜退还保证金','in',78.00,0.00,'2018-01-13 15:39:02','2018-01-13 15:39:02'),(132,2,'bigsmall',22,'接受比大小战斗','out',99.00,0.00,'2018-01-13 15:40:02','2018-01-13 15:40:02'),(133,1,'bigsmallrefund',22,'比大小获胜退还保证金','in',99.00,0.00,'2018-01-13 15:40:02','2018-01-13 15:40:02'),(134,2,'bigsmall',23,'接受比大小战斗','out',88.00,0.00,'2018-01-13 15:40:21','2018-01-13 15:40:21'),(135,1,'bigsmallrefund',23,'比大小获胜退还保证金','in',88.00,0.00,'2018-01-13 15:40:21','2018-01-13 15:40:21'),(136,2,'bigsmall',28,'接受比大小战斗','out',46.00,0.00,'2018-01-13 15:44:20','2018-01-13 15:44:20'),(137,1,'bigsmallrefund',28,'比大小获胜退还保证金','in',46.00,0.00,'2018-01-13 15:44:20','2018-01-13 15:44:20'),(142,2,'bigsmall',30,'接受比大小战斗','out',99.00,0.00,'2018-01-13 15:50:16','2018-01-13 15:50:16'),(143,1,'bigsmallrefund',30,'比大小获胜退还保证金','in',99.00,0.00,'2018-01-13 15:50:16','2018-01-13 15:50:16'),(144,2,'bigsmall',33,'发起比大小战斗','out',55.00,0.00,'2018-01-13 15:50:39','2018-01-13 15:50:39'),(145,1,'bigsmall',33,'接受比大小战斗','out',55.00,0.00,'2018-01-13 15:50:51','2018-01-13 15:50:51'),(146,2,'bigsmallrefund',33,'比大小获胜退还保证金','in',55.00,0.00,'2018-01-13 15:50:51','2018-01-13 15:50:51'),(147,2,'bigsmall',34,'发起比大小战斗','out',55.00,0.00,'2018-01-13 15:50:58','2018-01-13 15:50:58'),(148,1,'bigsmall',34,'接受比大小战斗','out',55.00,0.00,'2018-01-13 15:51:07','2018-01-13 15:51:07'),(149,2,'bigsmallrefund',34,'比大小获胜退还保证金','in',55.00,0.00,'2018-01-13 15:51:07','2018-01-13 15:51:07'),(150,2,'bigsmall',35,'发起比大小战斗','out',55.00,0.00,'2018-01-13 15:51:12','2018-01-13 15:51:12'),(151,1,'bigsmall',35,'接受比大小战斗','out',55.00,0.00,'2018-01-13 15:51:17','2018-01-13 15:51:17'),(152,2,'bigsmallrefund',35,'比大小获胜退还保证金','in',55.00,0.00,'2018-01-13 15:51:17','2018-01-13 15:51:17'),(153,2,'bigsmall',36,'发起比大小战斗','out',55.00,0.00,'2018-01-13 15:51:25','2018-01-13 15:51:25'),(156,1,'bigsmall',36,'接受比大小战斗','out',55.00,0.00,'2018-01-13 15:57:46','2018-01-13 15:57:46'),(157,1,'bigsmallrefund',36,'比大小获胜退还保证金','in',55.00,0.00,'2018-01-13 15:57:46','2018-01-13 15:57:46'),(158,1,'bigsmallrefund',36,'比大小退还保证金','in',55.00,0.00,'2018-01-13 15:57:50','2018-01-13 15:57:50'),(159,2,'bigsmall',37,'发起比大小战斗','out',55.00,0.00,'2018-01-15 12:23:40','2018-01-15 12:23:40'),(160,1,'stolen',1,'偷取','in',6446.00,0.00,'2018-01-15 12:27:23','2018-01-15 12:27:23'),(161,2,'stolen',1,'被偷取','out',6446.00,0.00,'2018-01-15 12:27:23','2018-01-15 12:27:23'),(162,1,'bigsmall',37,'接受比大小战斗','out',55.00,0.00,'2018-01-15 12:27:32','2018-01-15 12:27:32'),(163,2,'bigsmallrefund',37,'比大小获胜退还保证金','in',55.00,0.00,'2018-01-15 12:27:32','2018-01-15 12:27:32'),(164,2,'bigsmall',38,'发起比大小战斗','out',66.00,0.00,'2018-01-16 16:05:55','2018-01-16 16:05:55'),(165,1,'bigsmall',39,'发起比大小战斗','out',22.00,0.00,'2018-01-16 16:06:21','2018-01-16 16:06:21'),(166,2,'bigsmall',40,'发起比大小战斗','out',23.00,0.00,'2018-01-16 16:14:38','2018-01-16 16:14:38'),(167,1,'bigsmall',41,'发起比大小战斗','out',77.00,0.00,'2018-01-16 16:15:03','2018-01-16 16:15:03'),(168,2,'bigsmall',41,'接受比大小战斗','out',77.00,0.00,'2018-01-16 16:15:15','2018-01-16 16:15:15'),(169,2,'bigsmallrefund',41,'比大小获胜退还保证金','in',77.00,0.00,'2018-01-16 16:15:15','2018-01-16 16:15:15'),(170,1,'create_post',9,'发布广告:什么东西','out',0.00,0.00,'2018-01-20 13:42:33','2018-01-20 13:42:33'),(171,1,'create_post',10,'发布广告:测试的','out',0.00,0.00,'2018-01-22 12:01:46','2018-01-22 12:01:46'),(172,1,'create_post',11,'发布广告:测试的','out',0.00,0.00,'2018-01-22 12:05:07','2018-01-22 12:05:07'),(173,1,'create_post',12,'发布广告:测试的','out',0.00,0.00,'2018-01-22 12:05:48','2018-01-22 12:05:48'),(174,1,'create_post',13,'发布广告:测试的','out',0.00,0.00,'2018-01-22 12:05:49','2018-01-22 12:05:49'),(175,1,'create_post',14,'发布广告:测试的','out',0.00,0.00,'2018-01-22 12:05:56','2018-01-22 12:05:56'),(176,1,'create_post',15,'发布广告:测试的','out',0.00,0.00,'2018-01-22 12:07:13','2018-01-22 12:07:13'),(177,1,'create_post',16,'发布广告:测试的','out',0.00,0.00,'2018-01-22 12:07:28','2018-01-22 12:07:28'),(178,1,'create_post',17,'发布广告:测试的','out',0.00,0.00,'2018-01-22 12:08:37','2018-01-22 12:08:37'),(179,1,'create_post',18,'发布广告:测试的','out',0.00,0.00,'2018-01-22 12:10:07','2018-01-22 12:10:07'),(180,1,'create_post',21,'发布广告:测试的','out',0.00,0.00,'2018-01-22 12:11:53','2018-01-22 12:11:53'),(181,2,'bigsmall',42,'发起比大小战斗','out',77.00,0.00,'2018-01-22 12:51:44','2018-01-22 12:51:44'),(182,2,'bigsmall',43,'发起比大小战斗','out',77.00,0.00,'2018-01-22 12:59:14','2018-01-22 12:59:14'),(183,2,'bigsmall',44,'发起比大小战斗','out',77.00,0.00,'2018-01-22 12:59:45','2018-01-22 12:59:45'),(184,2,'bigsmall',45,'发起比大小战斗','out',77.00,0.00,'2018-01-22 13:00:14','2018-01-22 13:00:14'),(185,2,'bigsmall',46,'发起比大小战斗','out',77.00,0.00,'2018-01-22 13:03:50','2018-01-22 13:03:50'),(186,2,'bigsmall',47,'发起比大小战斗','out',77.00,0.00,'2018-01-22 13:04:25','2018-01-22 13:04:25'),(187,2,'bigsmall',48,'发起比大小战斗','out',77.00,0.00,'2018-01-22 13:04:58','2018-01-22 13:04:58'),(188,2,'bigsmall',49,'发起比大小战斗','out',77.00,0.00,'2018-01-22 13:05:21','2018-01-22 13:05:21'),(189,2,'bigsmall',50,'发起比大小战斗','out',77.00,0.00,'2018-01-22 13:05:28','2018-01-22 13:05:28'),(190,2,'bigsmall',51,'发起比大小战斗','out',77.00,0.00,'2018-01-22 13:06:13','2018-01-22 13:06:13'),(191,2,'bigsmall',52,'发起比大小战斗','out',77.00,0.00,'2018-01-22 13:07:41','2018-01-22 13:07:41'),(192,2,'bigsmall',53,'发起比大小战斗','out',77.00,0.00,'2018-01-22 13:11:08','2018-01-22 13:11:08'),(193,2,'bigsmall',54,'发起比大小战斗','out',77.00,0.00,'2018-01-22 13:11:52','2018-01-22 13:11:52'),(194,2,'bigsmall',55,'发起比大小战斗','out',77.00,0.00,'2018-01-22 13:12:39','2018-01-22 13:12:39'),(195,2,'bigsmall',56,'发起比大小战斗','out',77.00,0.00,'2018-01-22 13:13:26','2018-01-22 13:13:26'),(196,2,'bigsmall',57,'发起比大小战斗','out',77.00,0.00,'2018-01-22 13:14:08','2018-01-22 13:14:08'),(197,2,'bigsmall',58,'发起比大小战斗','out',77.00,0.00,'2018-01-22 13:15:08','2018-01-22 13:15:08'),(198,2,'bigsmall',59,'发起比大小战斗','out',44.00,0.00,'2018-01-22 13:16:29','2018-01-22 13:16:29'),(199,2,'bigsmall',60,'发起比大小战斗','out',44.00,0.00,'2018-01-22 13:18:47','2018-01-22 13:18:47'),(200,2,'bigsmall',61,'发起比大小战斗','out',44.00,0.00,'2018-01-22 13:19:50','2018-01-22 13:19:50');
/*!40000 ALTER TABLE `adm_user_cash` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `adm_user_currency`
--

DROP TABLE IF EXISTS `adm_user_currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adm_user_currency` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `event` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_id` int(10) unsigned NOT NULL,
  `target_desc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `act` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cash` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adm_user_currency`
--

LOCK TABLES `adm_user_currency` WRITE;
/*!40000 ALTER TABLE `adm_user_currency` DISABLE KEYS */;
INSERT INTO `adm_user_currency` VALUES (2,3,'click_reward',2,'查看真的好么获取佣金','in',0.00,'2018-01-06 15:25:22','2018-01-06 15:25:22');
/*!40000 ALTER TABLE `adm_user_currency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_config`
--

DROP TABLE IF EXISTS `admin_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_config_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_config`
--

LOCK TABLES `admin_config` WRITE;
/*!40000 ALTER TABLE `admin_config` DISABLE KEYS */;
INSERT INTO `admin_config` VALUES (1,'MIN_WITHDRAW','20','最小提现铜板','2017-09-25 13:51:33','2017-09-25 13:51:33'),(2,'ADV_MIN_SECONDS','5','页面停留时间 ，停留多少秒后可以获得奖励','2017-09-25 13:52:40','2017-09-25 13:52:40'),(3,'ADV_MIN_SHOW_DAY','5','广告最小展示天数','2017-09-25 13:53:38','2017-09-25 13:53:38'),(4,'ADV_MIN_CLICK_NUMS','1000','广告最小点击数值。用于广告发布时规定总次数的最小值','2017-09-25 13:59:08','2017-09-25 13:59:08'),(5,'WITHDRAW_MAX_RATE','80','最大提现额度。用户提现最大能提百分之N。','2017-09-25 14:02:42','2017-09-25 14:02:42'),(6,'CHARGE_BONUS_RULES',',100|20\r\n100,200|25\r\n200,500|30\r\n500,|35','区间为半开半闭区间，不限起点则逗号左边不填写，不限制上限则逗号右边不填写。用竖线隔开赠送比例。20%写20就可。如果逗号左右相等表示充值对应铜板送多少钱。每行一条规则','2017-09-27 17:00:47','2017-09-27 17:01:06'),(7,'CLICK_REWARD','0.1\r\n0.2\r\n0.5\r\n1.0\r\n1.5\r\n2.0\r\n3.5','点击最小收益值','2017-09-27 17:08:14','2017-09-27 17:08:14'),(8,'PRICE_FILTER',',0.5|<0.5元\r\n0.5,1.0|0.5~1.0元\r\n1.0,2.0|1.0~2.0元\r\n2.0,|>2.0元','每行一个值，区间以逗号隔开，不填写表示不限。竖线右边表示展示给用户的值。','2017-09-27 17:10:50','2017-09-27 17:10:50'),(9,'CHARGE_BONUS_DESC','0','充值赠送说明。可写填写赠送规则以及活动时间。文字不得超过255个字符。如需要不显示则填写0.','2017-09-27 17:12:55','2017-09-27 17:12:55'),(10,'INVITE_REWARD','2','每邀请成功一个用户送2点狗粮','2017-09-28 03:38:01','2017-09-28 03:38:01'),(11,'SHARE_REWARD','1|0.05\r\n2|0.02\r\n3|0.005','向上级分享奖励\r\n一级分享者 享受当前收益的0.05\r\n二级分享者 享受当前收益的0.02\r\n三级分享者 享受当前收益的0.005','2017-09-28 03:44:08','2017-09-28 03:44:08'),(12,'MIN_BASEINFO_EDIT','30','基本信息多少天后可以再次修改 单位为天','2017-10-02 08:06:34','2017-10-02 08:06:34'),(13,'MIN_PUP_CASH','1000','最小发布铜板','2017-10-13 05:58:37','2017-10-13 05:58:37'),(14,'NOTICE_CATE_ID','1','通知分类的ID编号','2017-10-15 03:27:58','2017-10-15 03:27:58'),(15,'ABOUT_US','5','关于我们的文章编号','2017-10-17 14:37:03','2017-10-17 14:37:03'),(16,'HELP','6','帮助说明文章编号','2017-10-17 14:37:23','2017-10-17 14:37:23'),(17,'BIG_SMALL_DESC','比大小是一个比手气的游戏，系统会为您和对手抽取一张牌，点数大的一方为胜利方，胜利方可以将失败方的保证金暂时作为战利品。当然胜利方可以退还战利品给失败方，如不退还系统将在半年后退还保证金给失败方，但会记一次失败方差评。\r\n您可以对好友或陌生人发起比大小邀请，您先得付一定的狗粮作为保证金，而应战方将以同样的数目狗粮作为保证金。牌面点数大的为胜方。','比大小的说明文案','2017-11-12 09:11:32','2017-11-12 09:11:44'),(18,'BIG_SMALL_MIN_POINT','10','比大小最小保证金点数','2017-11-12 09:12:22','2017-11-12 09:12:22'),(19,'BIG_SMALL_MAX_POINT','100','比大小保证金最大点数','2017-11-12 09:12:44','2017-11-12 09:12:44'),(20,'STOLEN_MAX_TIMES','20','每个用户被最大偷取次数','2017-12-13 12:00:00','2017-12-13 12:00:00'),(21,'STOLEN_RATE','10','最大偷取百分比','2017-12-13 12:00:33','2017-12-13 12:00:33'),(22,'CASH_RATE','100','狗粮和现金的对比值 100狗粮等于 1元钱','2018-01-13 16:16:21','2018-01-13 16:16:21'),(23,'BIG_SMALL_COMMISSION','10','按百分比扣取','2018-01-15 14:00:51','2018-01-15 14:00:51');
/*!40000 ALTER TABLE `admin_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_menu`
--

DROP TABLE IF EXISTS `admin_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uri` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_menu`
--

LOCK TABLES `admin_menu` WRITE;
/*!40000 ALTER TABLE `admin_menu` DISABLE KEYS */;
INSERT INTO `admin_menu` VALUES (1,0,1,'仪表盘','fa-bar-chart','/',NULL,'2017-09-25 13:49:18'),(2,0,2,'系统管理','fa-tasks','',NULL,'2017-09-25 13:49:35'),(3,2,3,'管理员管理','fa-users','auth/users',NULL,'2017-09-25 13:49:44'),(4,2,4,'角色管理','fa-user','auth/roles',NULL,'2017-09-25 13:49:53'),(5,2,5,'权限管理','fa-ban','auth/permissions',NULL,'2017-09-25 13:50:01'),(6,2,6,'菜单管理','fa-bars','auth/menu',NULL,'2017-09-25 13:50:10'),(7,2,7,'操作日志','fa-history','auth/logs',NULL,'2017-09-25 13:50:18'),(8,0,14,'日志查看','fa-database','logs','2017-09-25 07:57:18','2017-09-27 17:03:03'),(9,18,9,'配置管理','fa-toggle-on','config','2017-09-25 07:57:54','2017-09-27 16:51:42'),(10,0,15,'定时任务查看','fa-clock-o','scheduling','2017-09-25 07:58:11','2017-09-27 17:03:03'),(11,0,16,'内容管理','fa-bars','cms/posts','2017-09-25 07:58:49','2017-09-27 17:03:03'),(12,11,17,'内容管理','fa-file-powerpoint-o','cms/posts','2017-09-25 07:59:29','2017-09-27 17:03:03'),(13,11,18,'分类管理','fa-sort','cms/category','2017-09-25 08:00:11','2017-09-27 17:03:03'),(14,11,19,'广告位管理','fa-adn','cms/advtarget','2017-09-25 14:03:49','2017-09-27 17:03:03'),(15,11,20,'广告管理','fa-barcode','cms/adv','2017-09-25 14:04:48','2017-09-27 17:03:03'),(16,11,21,'意见反馈','fa-feed','cms/feedback','2017-09-25 14:05:30','2017-09-27 17:03:03'),(17,11,22,'单页面管理','fa-bars','cms/singlepage','2017-09-25 14:06:24','2017-09-27 17:03:03'),(18,0,8,'基础设置','fa-asterisk','config','2017-09-27 16:51:26','2017-09-27 16:51:42'),(19,18,10,'职业管理','fa-user-md','career','2017-09-27 16:52:13','2017-09-27 17:03:03'),(20,18,11,'学历管理','fa-book','degree','2017-09-27 16:53:04','2017-09-27 17:03:03'),(21,0,12,'广告管理','fa-trademark','admposts','2017-09-27 17:02:22','2017-09-27 17:03:03'),(22,21,13,'分类管理','fa-bars','category','2017-09-27 17:02:49','2017-09-27 17:03:03'),(23,21,0,'广告管理','fa-bars','advposts','2017-09-27 17:03:23','2017-09-27 17:14:24'),(24,0,0,'消息管理','fa-bars','message','2017-10-21 07:51:58','2017-10-21 07:51:58'),(25,0,0,'财务管理','fa-bars','finance','2018-01-14 16:05:38','2018-01-14 16:05:38'),(26,25,0,'提现管理','fa-bars','withdraw','2018-01-14 16:05:51','2018-01-14 16:05:51');
/*!40000 ALTER TABLE `admin_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_messages`
--

DROP TABLE IF EXISTS `admin_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_messages`
--

LOCK TABLES `admin_messages` WRITE;
/*!40000 ALTER TABLE `admin_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_operation_log`
--

DROP TABLE IF EXISTS `admin_operation_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_operation_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `input` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_operation_log_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=557 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_operation_log`
--

LOCK TABLES `admin_operation_log` WRITE;
/*!40000 ALTER TABLE `admin_operation_log` DISABLE KEYS */;
INSERT INTO `admin_operation_log` VALUES (1,1,'admin/cms/posts','GET','127.0.0.1','[]','2017-09-25 07:56:46','2017-09-25 07:56:46'),(2,1,'admin','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 07:57:01','2017-09-25 07:57:01'),(3,1,'admin','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 07:57:02','2017-09-25 07:57:02'),(4,1,'admin','GET','127.0.0.1','[]','2017-09-25 07:57:24','2017-09-25 07:57:24'),(5,1,'admin','GET','127.0.0.1','[]','2017-09-25 07:57:59','2017-09-25 07:57:59'),(6,1,'admin','GET','127.0.0.1','[]','2017-09-25 07:58:27','2017-09-25 07:58:27'),(7,1,'admin/auth/menu','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 07:58:34','2017-09-25 07:58:34'),(8,1,'admin/auth/menu','POST','127.0.0.1','{\"parent_id\":\"0\",\"title\":\"\\u5185\\u5bb9\\u7ba1\\u7406\",\"icon\":\"fa-bars\",\"uri\":\"cms\\/posts\",\"roles\":[\"1\",\"\"],\"_token\":\"MWWr4U7gTUcmDh7odBNzWLrEPy5G86ajdgnFiAzO\"}','2017-09-25 07:58:49','2017-09-25 07:58:49'),(9,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 07:58:50','2017-09-25 07:58:50'),(10,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 07:58:52','2017-09-25 07:58:52'),(11,1,'admin/auth/menu','POST','127.0.0.1','{\"parent_id\":\"0\",\"title\":\"\\u5185\\u5bb9\\u7ba1\\u7406\",\"icon\":\"fa-file-powerpoint-o\",\"uri\":\"cms\\/posts\",\"roles\":[\"1\",\"\"],\"_token\":\"MWWr4U7gTUcmDh7odBNzWLrEPy5G86ajdgnFiAzO\"}','2017-09-25 07:59:28','2017-09-25 07:59:28'),(12,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 07:59:29','2017-09-25 07:59:29'),(13,1,'admin/auth/menu','POST','127.0.0.1','{\"parent_id\":\"0\",\"title\":\"\\u5206\\u7c7b\\u7ba1\\u7406\",\"icon\":\"fa-sort\",\"uri\":\"cms\\/category\",\"roles\":[\"1\",\"\"],\"_token\":\"MWWr4U7gTUcmDh7odBNzWLrEPy5G86ajdgnFiAzO\"}','2017-09-25 08:00:10','2017-09-25 08:00:10'),(14,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 08:00:11','2017-09-25 08:00:11'),(15,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 08:00:26','2017-09-25 08:00:26'),(16,1,'admin/auth/menu','POST','127.0.0.1','{\"_token\":\"MWWr4U7gTUcmDh7odBNzWLrEPy5G86ajdgnFiAzO\",\"_order\":\"[{\\\"id\\\":1},{\\\"id\\\":2,\\\"children\\\":[{\\\"id\\\":3},{\\\"id\\\":4},{\\\"id\\\":5},{\\\"id\\\":6},{\\\"id\\\":7}]},{\\\"id\\\":8},{\\\"id\\\":9},{\\\"id\\\":10},{\\\"id\\\":11,\\\"children\\\":[{\\\"id\\\":12},{\\\"id\\\":13}]}]\"}','2017-09-25 08:00:55','2017-09-25 08:00:55'),(17,1,'admin/auth/menu','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 08:00:56','2017-09-25 08:00:56'),(18,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 08:00:57','2017-09-25 08:00:57'),(19,1,'admin/cms/posts','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 08:01:01','2017-09-25 08:01:01'),(20,1,'admin/cms/posts','GET','127.0.0.1','[]','2017-09-25 08:07:15','2017-09-25 08:07:15'),(21,1,'admin/cms/posts','GET','127.0.0.1','[]','2017-09-25 08:12:31','2017-09-25 08:12:31'),(22,1,'admin/cms/posts','GET','127.0.0.1','[]','2017-09-25 08:13:23','2017-09-25 08:13:23'),(23,1,'admin/cms/posts','GET','127.0.0.1','[]','2017-09-25 08:13:24','2017-09-25 08:13:24'),(24,1,'admin/cms/posts','GET','127.0.0.1','[]','2017-09-25 08:14:03','2017-09-25 08:14:03'),(25,1,'admin/cms/posts/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 08:14:12','2017-09-25 08:14:12'),(26,1,'admin/cms/posts','GET','127.0.0.1','[]','2017-09-25 08:14:13','2017-09-25 08:14:13'),(27,1,'admin/cms/posts','GET','127.0.0.1','[]','2017-09-25 09:03:23','2017-09-25 09:03:23'),(28,1,'admin/cms/posts/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 09:03:26','2017-09-25 09:03:26'),(29,1,'admin/cms/posts','GET','127.0.0.1','[]','2017-09-25 09:03:26','2017-09-25 09:03:26'),(30,1,'admin/cms/posts','GET','127.0.0.1','[]','2017-09-25 09:05:59','2017-09-25 09:05:59'),(31,1,'admin/cms/posts/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 09:06:01','2017-09-25 09:06:01'),(32,1,'admin/cms/posts','GET','127.0.0.1','[]','2017-09-25 09:06:02','2017-09-25 09:06:02'),(33,1,'admin/cms/posts','GET','127.0.0.1','[]','2017-09-25 09:06:34','2017-09-25 09:06:34'),(34,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 09:06:36','2017-09-25 09:06:36'),(35,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 09:07:09','2017-09-25 09:07:09'),(36,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 09:07:57','2017-09-25 09:07:57'),(37,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 09:08:51','2017-09-25 09:08:51'),(38,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 09:39:44','2017-09-25 09:39:44'),(39,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 09:40:15','2017-09-25 09:40:15'),(40,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 10:12:06','2017-09-25 10:12:06'),(41,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 10:12:08','2017-09-25 10:12:08'),(42,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 10:12:19','2017-09-25 10:12:19'),(43,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 10:12:20','2017-09-25 10:12:20'),(44,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 10:13:06','2017-09-25 10:13:06'),(45,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 10:13:26','2017-09-25 10:13:26'),(46,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 10:14:37','2017-09-25 10:14:37'),(47,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 10:15:48','2017-09-25 10:15:48'),(48,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 10:16:27','2017-09-25 10:16:27'),(49,1,'admin/cms/category','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 10:17:01','2017-09-25 10:17:01'),(50,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 10:17:01','2017-09-25 10:17:01'),(51,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 13:36:19','2017-09-25 13:36:19'),(52,1,'admin/cms/category','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:36:24','2017-09-25 13:36:24'),(53,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 13:36:24','2017-09-25 13:36:24'),(54,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 13:43:25','2017-09-25 13:43:25'),(55,1,'admin/cms/category','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:43:30','2017-09-25 13:43:30'),(56,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 13:43:30','2017-09-25 13:43:30'),(57,1,'admin/cms/category','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:43:33','2017-09-25 13:43:33'),(58,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 13:43:33','2017-09-25 13:43:33'),(59,1,'admin/cms/posts/create','GET','127.0.0.1','[]','2017-09-25 13:44:05','2017-09-25 13:44:05'),(60,1,'admin/cms/category','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:44:09','2017-09-25 13:44:09'),(61,1,'admin/cms/category/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:44:12','2017-09-25 13:44:12'),(62,1,'admin/cms/category/create','GET','127.0.0.1','[]','2017-09-25 13:44:37','2017-09-25 13:44:37'),(63,1,'admin/cms/category','POST','127.0.0.1','{\"parent_id\":\"0\",\"name\":\"\\u9876\\u7ea7\\u5206\\u7c7b\",\"keyword\":\"\",\"description\":\"\",\"content\":\"\",\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\"}','2017-09-25 13:44:56','2017-09-25 13:44:56'),(64,1,'admin/cms/category/create','GET','127.0.0.1','[]','2017-09-25 13:44:56','2017-09-25 13:44:56'),(65,1,'admin/cms/category','POST','127.0.0.1','{\"parent_id\":\"0\",\"name\":\"\\u9876\\u7ea7\\u5206\\u7c7b\",\"keyword\":\"\",\"description\":\"\",\"content\":\"\",\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\"}','2017-09-25 13:45:10','2017-09-25 13:45:10'),(66,1,'admin/cms/category/create','GET','127.0.0.1','[]','2017-09-25 13:45:11','2017-09-25 13:45:11'),(67,1,'admin/cms/category','POST','127.0.0.1','{\"parent_id\":\"0\",\"name\":\"\\u9876\\u7ea7\\u5206\\u7c7b\",\"keyword\":\"\",\"description\":\"\",\"content\":\"\",\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\"}','2017-09-25 13:45:38','2017-09-25 13:45:38'),(68,1,'admin/cms/category','GET','127.0.0.1','[]','2017-09-25 13:45:38','2017-09-25 13:45:38'),(69,1,'admin/cms/posts','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:45:45','2017-09-25 13:45:45'),(70,1,'admin/cms/posts/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:45:47','2017-09-25 13:45:47'),(71,1,'admin/cms/posts','POST','127.0.0.1','{\"title\":\"\\u6d4b\\u8bd5\\u8d44\\u8baf\\u53d1\\u5e03\",\"category_id\":\"1\",\"author\":\"Contact Us\",\"keyword\":\"\",\"description\":\"\",\"content\":\"<p style=\\\"box-sizing: border-box; line-height: 1.7; margin-top: 10px; margin-bottom: 20px; font-size: 14.5px; color: rgb(82, 82, 82); font-family: &quot;Source Sans Pro&quot;, sans-serif; white-space: normal; background-color: rgb(255, 255, 255);\\\">You may rollback a limited number of migrations by providing the&nbsp;<code class=\\\" language-php\\\" style=\\\"box-sizing: border-box; font-family: &quot;Operator Mono&quot;, &quot;Fira Code&quot;, Consolas, Monaco, &quot;Andale Mono&quot;, monospace; font-size: 11px; color: rgb(244, 100, 95); border-radius: 3px; background: rgb(240, 242, 241); padding: 1px 5px; white-space: pre; word-spacing: normal; word-break: normal; direction: ltr; text-shadow: rgb(255, 255, 255) 0px 1px; line-height: 2; tab-size: 4; hyphens: none; box-shadow: rgba(0, 0, 0, 0.125) 0px 1px 1px; vertical-align: middle;\\\">step<\\/code>&nbsp;option to the&nbsp;<code class=\\\" language-php\\\" style=\\\"box-sizing: border-box; font-family: &quot;Operator Mono&quot;, &quot;Fira Code&quot;, Consolas, Monaco, &quot;Andale Mono&quot;, monospace; font-size: 11px; color: rgb(244, 100, 95); border-radius: 3px; background: rgb(240, 242, 241); padding: 1px 5px; white-space: pre; word-spacing: normal; word-break: normal; direction: ltr; text-shadow: rgb(255, 255, 255) 0px 1px; line-height: 2; tab-size: 4; hyphens: none; box-shadow: rgba(0, 0, 0, 0.125) 0px 1px 1px; vertical-align: middle;\\\">rollback<\\/code>command. For example, the following command will rollback the last five migrations:<\\/p><p><br\\/><\\/p>\",\"is_top\":\"on\",\"is_pic\":\"on\",\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/cms\\/posts\"}','2017-09-25 13:46:20','2017-09-25 13:46:20'),(72,1,'admin/cms/posts','GET','127.0.0.1','[]','2017-09-25 13:46:20','2017-09-25 13:46:20'),(73,1,'admin/cms/posts','GET','127.0.0.1','[]','2017-09-25 13:46:49','2017-09-25 13:46:49'),(74,1,'admin/cms/posts','GET','127.0.0.1','[]','2017-09-25 13:47:00','2017-09-25 13:47:00'),(75,1,'admin/cms/posts','GET','127.0.0.1','[]','2017-09-25 13:47:16','2017-09-25 13:47:16'),(76,1,'admin/cms/posts','GET','127.0.0.1','{\"trash\":\"1\",\"_pjax\":\"#pjax-container\"}','2017-09-25 13:47:38','2017-09-25 13:47:38'),(77,1,'admin/cms/posts','GET','127.0.0.1','{\"trash\":\"0\",\"_pjax\":\"#pjax-container\"}','2017-09-25 13:47:40','2017-09-25 13:47:40'),(78,1,'admin/cms/posts/1','DELETE','127.0.0.1','{\"_method\":\"delete\",\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\"}','2017-09-25 13:47:50','2017-09-25 13:47:50'),(79,1,'admin/cms/posts','GET','127.0.0.1','{\"trash\":\"0\",\"_pjax\":\"#pjax-container\"}','2017-09-25 13:47:50','2017-09-25 13:47:50'),(80,1,'admin/cms/posts','GET','127.0.0.1','{\"trash\":\"1\",\"_pjax\":\"#pjax-container\"}','2017-09-25 13:48:00','2017-09-25 13:48:00'),(81,1,'admin/cms/posts/1','DELETE','127.0.0.1','{\"_method\":\"delete\",\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\"}','2017-09-25 13:48:05','2017-09-25 13:48:05'),(82,1,'admin/cms/posts','GET','127.0.0.1','{\"trash\":\"1\"}','2017-09-25 13:48:08','2017-09-25 13:48:08'),(83,1,'admin/cms/posts','GET','127.0.0.1','{\"trash\":\"1\"}','2017-09-25 13:48:46','2017-09-25 13:48:46'),(84,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:48:51','2017-09-25 13:48:51'),(85,1,'admin/scheduling','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:48:52','2017-09-25 13:48:52'),(86,1,'admin/cms/posts','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:48:55','2017-09-25 13:48:55'),(87,1,'admin/cms/category','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:48:56','2017-09-25 13:48:56'),(88,1,'admin/cms/posts','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:48:58','2017-09-25 13:48:58'),(89,1,'admin/auth/menu','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:49:05','2017-09-25 13:49:05'),(90,1,'admin/auth/menu/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:49:09','2017-09-25 13:49:09'),(91,1,'admin/auth/menu/1','PUT','127.0.0.1','{\"parent_id\":\"0\",\"title\":\"\\u4eea\\u8868\\u76d8\",\"icon\":\"fa-bar-chart\",\"uri\":\"\\/\",\"roles\":[\"\"],\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/auth\\/menu\"}','2017-09-25 13:49:18','2017-09-25 13:49:18'),(92,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 13:49:18','2017-09-25 13:49:18'),(93,1,'admin/auth/menu/2/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:49:21','2017-09-25 13:49:21'),(94,1,'admin/auth/menu/2','PUT','127.0.0.1','{\"parent_id\":\"0\",\"title\":\"\\u7cfb\\u7edf\\u7ba1\\u7406\",\"icon\":\"fa-tasks\",\"uri\":\"\",\"roles\":[\"1\",\"\"],\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/auth\\/menu\"}','2017-09-25 13:49:35','2017-09-25 13:49:35'),(95,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 13:49:35','2017-09-25 13:49:35'),(96,1,'admin/auth/menu/3/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:49:38','2017-09-25 13:49:38'),(97,1,'admin/auth/menu/3','PUT','127.0.0.1','{\"parent_id\":\"2\",\"title\":\"\\u7ba1\\u7406\\u5458\\u7ba1\\u7406\",\"icon\":\"fa-users\",\"uri\":\"auth\\/users\",\"roles\":[\"\"],\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/auth\\/menu\"}','2017-09-25 13:49:44','2017-09-25 13:49:44'),(98,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 13:49:44','2017-09-25 13:49:44'),(99,1,'admin/auth/menu/4/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:49:47','2017-09-25 13:49:47'),(100,1,'admin/auth/menu/4','PUT','127.0.0.1','{\"parent_id\":\"2\",\"title\":\"\\u89d2\\u8272\\u7ba1\\u7406\",\"icon\":\"fa-user\",\"uri\":\"auth\\/roles\",\"roles\":[\"\"],\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/auth\\/menu\"}','2017-09-25 13:49:53','2017-09-25 13:49:53'),(101,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 13:49:53','2017-09-25 13:49:53'),(102,1,'admin/auth/menu/5/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:49:55','2017-09-25 13:49:55'),(103,1,'admin/auth/menu/5','PUT','127.0.0.1','{\"parent_id\":\"2\",\"title\":\"\\u6743\\u9650\\u7ba1\\u7406\",\"icon\":\"fa-ban\",\"uri\":\"auth\\/permissions\",\"roles\":[\"\"],\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/auth\\/menu\"}','2017-09-25 13:50:01','2017-09-25 13:50:01'),(104,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 13:50:01','2017-09-25 13:50:01'),(105,1,'admin/auth/menu/6/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:50:04','2017-09-25 13:50:04'),(106,1,'admin/auth/menu/6','PUT','127.0.0.1','{\"parent_id\":\"2\",\"title\":\"\\u83dc\\u5355\\u7ba1\\u7406\",\"icon\":\"fa-bars\",\"uri\":\"auth\\/menu\",\"roles\":[\"\"],\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/auth\\/menu\"}','2017-09-25 13:50:10','2017-09-25 13:50:10'),(107,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 13:50:10','2017-09-25 13:50:10'),(108,1,'admin/auth/menu/7/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:50:13','2017-09-25 13:50:13'),(109,1,'admin/auth/menu/7','PUT','127.0.0.1','{\"parent_id\":\"2\",\"title\":\"\\u64cd\\u4f5c\\u65e5\\u5fd7\",\"icon\":\"fa-history\",\"uri\":\"auth\\/logs\",\"roles\":[\"\"],\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/auth\\/menu\"}','2017-09-25 13:50:18','2017-09-25 13:50:18'),(110,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 13:50:18','2017-09-25 13:50:18'),(111,1,'admin/auth/menu/8/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:50:21','2017-09-25 13:50:21'),(112,1,'admin/auth/menu/8','PUT','127.0.0.1','{\"parent_id\":\"0\",\"title\":\"\\u65e5\\u5fd7\\u67e5\\u770b\",\"icon\":\"fa-database\",\"uri\":\"logs\",\"roles\":[\"\"],\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/auth\\/menu\"}','2017-09-25 13:50:32','2017-09-25 13:50:32'),(113,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 13:50:32','2017-09-25 13:50:32'),(114,1,'admin/auth/menu/9/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:50:35','2017-09-25 13:50:35'),(115,1,'admin/auth/menu/9','PUT','127.0.0.1','{\"parent_id\":\"0\",\"title\":\"\\u914d\\u7f6e\\u7ba1\\u7406\",\"icon\":\"fa-toggle-on\",\"uri\":\"config\",\"roles\":[\"\"],\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/auth\\/menu\"}','2017-09-25 13:50:40','2017-09-25 13:50:40'),(116,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 13:50:41','2017-09-25 13:50:41'),(117,1,'admin/auth/menu/10/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:50:45','2017-09-25 13:50:45'),(118,1,'admin/auth/menu/10','PUT','127.0.0.1','{\"parent_id\":\"0\",\"title\":\"\\u5b9a\\u65f6\\u4efb\\u52a1\\u67e5\\u770b\",\"icon\":\"fa-clock-o\",\"uri\":\"scheduling\",\"roles\":[\"\"],\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/auth\\/menu\"}','2017-09-25 13:50:52','2017-09-25 13:50:52'),(119,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 13:50:53','2017-09-25 13:50:53'),(120,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 13:50:54','2017-09-25 13:50:54'),(121,1,'admin/scheduling','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:50:56','2017-09-25 13:50:56'),(122,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:50:58','2017-09-25 13:50:58'),(123,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:51:00','2017-09-25 13:51:00'),(124,1,'admin/config','POST','127.0.0.1','{\"name\":\"MIN_WITHDRAW\",\"value\":\"20\",\"description\":\"\\u6700\\u5c0f\\u63d0\\u73b0\\u91d1\\u989d\",\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/config\"}','2017-09-25 13:51:33','2017-09-25 13:51:33'),(125,1,'admin/config','GET','127.0.0.1','[]','2017-09-25 13:51:34','2017-09-25 13:51:34'),(126,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:51:40','2017-09-25 13:51:40'),(127,1,'admin/config','POST','127.0.0.1','{\"name\":\"ADV_MIN_SECONDS\",\"value\":\"5\",\"description\":\"\\u9875\\u9762\\u505c\\u7559\\u65f6\\u95f4 \\uff0c\\u505c\\u7559\\u591a\\u5c11\\u79d2\\u540e\\u53ef\\u4ee5\\u83b7\\u5f97\\u5956\\u52b1\",\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/config\"}','2017-09-25 13:52:40','2017-09-25 13:52:40'),(128,1,'admin/config','GET','127.0.0.1','[]','2017-09-25 13:52:40','2017-09-25 13:52:40'),(129,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:53:07','2017-09-25 13:53:07'),(130,1,'admin/config','POST','127.0.0.1','{\"name\":\"ADV_MIN_SHOW_DAY\",\"value\":\"5\",\"description\":\"\\u5e7f\\u544a\\u6700\\u5c0f\\u5c55\\u793a\\u5929\\u6570\",\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/config\"}','2017-09-25 13:53:38','2017-09-25 13:53:38'),(131,1,'admin/config','GET','127.0.0.1','[]','2017-09-25 13:53:38','2017-09-25 13:53:38'),(132,1,'admin/config','GET','127.0.0.1','[]','2017-09-25 13:57:32','2017-09-25 13:57:32'),(133,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:57:36','2017-09-25 13:57:36'),(134,1,'admin/config/create','GET','127.0.0.1','[]','2017-09-25 13:57:57','2017-09-25 13:57:57'),(135,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:58:10','2017-09-25 13:58:10'),(136,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 13:58:22','2017-09-25 13:58:22'),(137,1,'admin/config','POST','127.0.0.1','{\"name\":\"ADV_MIN_CLICK_NUMS\",\"value\":\"1000\",\"description\":\"\\u5e7f\\u544a\\u6700\\u5c0f\\u70b9\\u51fb\\u6570\\u503c\\u3002\\u7528\\u4e8e\\u5e7f\\u544a\\u53d1\\u5e03\\u65f6\\u89c4\\u5b9a\\u603b\\u6b21\\u6570\\u7684\\u6700\\u5c0f\\u503c\",\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/config\"}','2017-09-25 13:59:08','2017-09-25 13:59:08'),(138,1,'admin/config','GET','127.0.0.1','[]','2017-09-25 13:59:08','2017-09-25 13:59:08'),(139,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 14:01:49','2017-09-25 14:01:49'),(140,1,'admin/config','POST','127.0.0.1','{\"name\":\"WITHDRAW_MAX_RATE\",\"value\":\"80\",\"description\":\"\\u6700\\u5927\\u63d0\\u73b0\\u989d\\u5ea6\\u3002\\u7528\\u6237\\u63d0\\u73b0\\u6700\\u5927\\u80fd\\u63d0\\u767e\\u5206\\u4e4bN\\u3002\",\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/config\"}','2017-09-25 14:02:42','2017-09-25 14:02:42'),(141,1,'admin/config','GET','127.0.0.1','[]','2017-09-25 14:02:42','2017-09-25 14:02:42'),(142,1,'admin/auth/menu','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 14:03:04','2017-09-25 14:03:04'),(143,1,'admin/auth/menu','POST','127.0.0.1','{\"parent_id\":\"11\",\"title\":\"\\u5e7f\\u544a\\u4f4d\\u7ba1\\u7406\",\"icon\":\"fa-adn\",\"uri\":\"cms\\/advtarget\",\"roles\":[\"1\",\"\"],\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\"}','2017-09-25 14:03:49','2017-09-25 14:03:49'),(144,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 14:03:49','2017-09-25 14:03:49'),(145,1,'admin/auth/menu','POST','127.0.0.1','{\"parent_id\":\"11\",\"title\":\"\\u5e7f\\u544a\\u7ba1\\u7406\",\"icon\":\"fa-barcode\",\"uri\":\"cms\\/adv\",\"roles\":[\"1\",\"\"],\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\"}','2017-09-25 14:04:48','2017-09-25 14:04:48'),(146,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 14:04:48','2017-09-25 14:04:48'),(147,1,'admin/auth/menu','POST','127.0.0.1','{\"parent_id\":\"11\",\"title\":\"\\u610f\\u89c1\\u53cd\\u9988\",\"icon\":\"fa-feed\",\"uri\":\"cms\\/feedback\",\"roles\":[\"1\",\"\"],\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\"}','2017-09-25 14:05:30','2017-09-25 14:05:30'),(148,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 14:05:30','2017-09-25 14:05:30'),(149,1,'admin/auth/menu','POST','127.0.0.1','{\"parent_id\":\"11\",\"title\":\"\\u5355\\u9875\\u9762\\u7ba1\\u7406\",\"icon\":\"fa-bars\",\"uri\":\"cms\\/singlepage\",\"roles\":[\"1\",\"\"],\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\"}','2017-09-25 14:06:24','2017-09-25 14:06:24'),(150,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 14:06:24','2017-09-25 14:06:24'),(151,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 14:06:27','2017-09-25 14:06:27'),(152,1,'admin/cms/advtarget','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 14:06:32','2017-09-25 14:06:32'),(153,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 14:06:32','2017-09-25 14:06:32'),(154,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-25 14:07:07','2017-09-25 14:07:07'),(155,1,'admin/cms/advtarget','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 14:07:11','2017-09-25 14:07:11'),(156,1,'admin/cms/advtarget/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 14:22:54','2017-09-25 14:22:54'),(157,1,'admin/cms/advtarget','POST','127.0.0.1','{\"name\":\"\\u79fb\\u52a8\\u7aef\\u9996\\u9875BANNER\",\"slug\":\"WAP_INDEX_BANNER\",\"description\":\"\\u79fb\\u52a8\\u7aef\\u9996\\u9875banner\",\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/cms\\/advtarget\"}','2017-09-25 14:23:21','2017-09-25 14:23:21'),(158,1,'admin/cms/advtarget/create','GET','127.0.0.1','[]','2017-09-25 14:23:21','2017-09-25 14:23:21'),(159,1,'admin/cms/advtarget/create','GET','127.0.0.1','[]','2017-09-25 14:24:11','2017-09-25 14:24:11'),(160,1,'admin/cms/advtarget','POST','127.0.0.1','{\"title\":\"\\u79fb\\u52a8\\u7aef\\u9996\\u9875BANNER\",\"slug\":\"WAP_INDEX_BANNER\",\"description\":\"\\u79fb\\u52a8\\u7aef\\u9996\\u9875BANNER\",\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\"}','2017-09-25 14:24:18','2017-09-25 14:24:18'),(161,1,'admin/cms/advtarget','GET','127.0.0.1','[]','2017-09-25 14:24:18','2017-09-25 14:24:18'),(162,1,'admin/cms/adv','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 14:24:24','2017-09-25 14:24:24'),(163,1,'admin/cms/advtarget','GET','127.0.0.1','[]','2017-09-25 14:24:24','2017-09-25 14:24:24'),(164,1,'admin/cms/adv','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 14:24:45','2017-09-25 14:24:45'),(165,1,'admin/cms/adv','GET','127.0.0.1','[]','2017-09-25 14:24:57','2017-09-25 14:24:57'),(166,1,'admin/cms/adv','GET','127.0.0.1','[]','2017-09-25 14:25:24','2017-09-25 14:25:24'),(167,1,'admin/cms/adv/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 14:25:27','2017-09-25 14:25:27'),(168,1,'admin/cms/adv/create','GET','127.0.0.1','[]','2017-09-25 14:26:05','2017-09-25 14:26:05'),(169,1,'admin/cms/adv','POST','127.0.0.1','{\"title\":\"\\u8f6e\\u64ad1\",\"target_id\":\"1\",\"link\":\"#\",\"start_at\":\"2017-09-25\",\"end_at\":\"2017-09-29\",\"sort\":\"3\",\"display\":\"on\",\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\"}','2017-09-25 14:27:16','2017-09-25 14:27:16'),(170,1,'admin/cms/adv/create','GET','127.0.0.1','[]','2017-09-25 14:27:16','2017-09-25 14:27:16'),(171,1,'admin/cms/adv','POST','127.0.0.1','{\"title\":\"\\u8f6e\\u64ad1\",\"target_id\":\"1\",\"link\":\"#\",\"start_at\":\"2017-09-25\",\"end_at\":\"2017-09-29\",\"sort\":\"3\",\"display\":\"on\",\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\"}','2017-09-25 14:28:31','2017-09-25 14:28:31'),(172,1,'admin/cms/adv','GET','127.0.0.1','[]','2017-09-25 14:28:31','2017-09-25 14:28:31'),(173,1,'admin/cms/adv','GET','127.0.0.1','[]','2017-09-25 14:29:11','2017-09-25 14:29:11'),(174,1,'admin/cms/singlepage','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 14:30:14','2017-09-25 14:30:14'),(175,1,'admin/cms/adv','GET','127.0.0.1','[]','2017-09-25 14:30:15','2017-09-25 14:30:15'),(176,1,'admin/cms/singlepage','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 14:30:35','2017-09-25 14:30:35'),(177,1,'admin/cms/singlepage','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 14:30:49','2017-09-25 14:30:49'),(178,1,'admin/cms/singlepage','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 14:30:57','2017-09-25 14:30:57'),(179,1,'admin/cms/singlepage','GET','127.0.0.1','[]','2017-09-25 14:31:20','2017-09-25 14:31:20'),(180,1,'admin/cms/singlepage/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 14:31:24','2017-09-25 14:31:24'),(181,1,'admin/cms/singlepage','POST','127.0.0.1','{\"title\":\"\\u5173\\u4e8e\\u6211\\u4eec\",\"author\":\"\\u5173\\u4e8e\\u6211\\u4eec\",\"keyword\":\"\\u5173\\u4e8e\\u6211\\u4eec\",\"description\":\"\\u5173\\u4e8e\\u6211\\u4eec\",\"content\":\"<p>\\u5173\\u4e8e\\u6211\\u4eec<\\/p>\",\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/cms\\/singlepage\"}','2017-09-25 14:31:49','2017-09-25 14:31:49'),(182,1,'admin/cms/singlepage/create','GET','127.0.0.1','[]','2017-09-25 14:31:49','2017-09-25 14:31:49'),(183,1,'admin/cms/singlepage','POST','127.0.0.1','{\"title\":\"\\u5173\\u4e8e\\u6211\\u4eec\",\"author\":\"\\u5173\\u4e8e\\u6211\\u4eec\",\"keyword\":\"\\u5173\\u4e8e\\u6211\\u4eec\",\"description\":\"\\u5173\\u4e8e\\u6211\\u4eec\",\"content\":\"<p>\\u5173\\u4e8e\\u6211\\u4eec<\\/p>\",\"_token\":\"wvDOOZ3xVuISae7o8eOj7VQBb6joxvFvxSxqUefc\"}','2017-09-25 14:32:03','2017-09-25 14:32:03'),(184,1,'admin/cms/singlepage','GET','127.0.0.1','[]','2017-09-25 14:32:03','2017-09-25 14:32:03'),(185,1,'admin/cms/singlepage/5/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-25 14:32:09','2017-09-25 14:32:09'),(186,1,'admin/cms/singlepage/5/edit','GET','127.0.0.1','[]','2017-09-25 14:32:18','2017-09-25 14:32:18'),(187,1,'admin/cms/singlepage/5/edit','GET','127.0.0.1','[]','2017-09-25 14:32:30','2017-09-25 14:32:30'),(188,1,'admin/cms/feedback','GET','127.0.0.1','[]','2017-09-26 06:46:59','2017-09-26 06:46:59'),(189,1,'admin/cms/feedback','GET','127.0.0.1','[]','2017-09-26 06:47:01','2017-09-26 06:47:01'),(190,1,'admin/cms/feedback','GET','127.0.0.1','[]','2017-09-26 06:47:38','2017-09-26 06:47:38'),(191,1,'admin/cms/feedback','GET','127.0.0.1','[]','2017-09-26 06:48:20','2017-09-26 06:48:20'),(192,1,'admin/cms/feedback','GET','127.0.0.1','[]','2017-09-26 06:48:35','2017-09-26 06:48:35'),(193,1,'admin/cms/feedback','GET','127.0.0.1','[]','2017-09-26 06:51:43','2017-09-26 06:51:43'),(194,1,'admin/cms/feedback','GET','127.0.0.1','[]','2017-09-26 06:51:52','2017-09-26 06:51:52'),(195,1,'admin/cms/feedback','GET','127.0.0.1','[]','2017-09-26 07:04:41','2017-09-26 07:04:41'),(196,1,'admin/cms/feedback','GET','127.0.0.1','[]','2017-09-26 07:06:05','2017-09-26 07:06:05'),(197,1,'admin','GET','127.0.0.1','[]','2017-09-27 07:06:30','2017-09-27 07:06:30'),(198,1,'admin/logs','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 07:09:06','2017-09-27 07:09:06'),(199,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 09:44:30','2017-09-27 09:44:30'),(200,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 09:44:34','2017-09-27 09:44:34'),(201,1,'admin/config/create','GET','127.0.0.1','[]','2017-09-27 16:33:52','2017-09-27 16:33:52'),(202,1,'admin/career','GET','127.0.0.1','[]','2017-09-27 16:33:58','2017-09-27 16:33:58'),(203,1,'admin/career','GET','127.0.0.1','[]','2017-09-27 16:34:08','2017-09-27 16:34:08'),(204,1,'admin/career','GET','127.0.0.1','[]','2017-09-27 16:35:38','2017-09-27 16:35:38'),(205,1,'admin/career','GET','127.0.0.1','[]','2017-09-27 16:35:50','2017-09-27 16:35:50'),(206,1,'admin/career','GET','127.0.0.1','[]','2017-09-27 16:36:02','2017-09-27 16:36:02'),(207,1,'admin/career','GET','127.0.0.1','[]','2017-09-27 16:36:08','2017-09-27 16:36:08'),(208,1,'admin/career','GET','127.0.0.1','[]','2017-09-27 16:38:51','2017-09-27 16:38:51'),(209,1,'admin/career/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 16:38:54','2017-09-27 16:38:54'),(210,1,'admin/career/create','GET','127.0.0.1','[]','2017-09-27 16:39:16','2017-09-27 16:39:16'),(211,1,'admin/career','POST','127.0.0.1','{\"title\":\"\\u8f6f\\u4ef6\\u5de5\\u7a0b\\u5e08\",\"sort\":\"10\",\"display\":\"on\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/career\"}','2017-09-27 16:39:31','2017-09-27 16:39:31'),(212,1,'admin/career/create','GET','127.0.0.1','[]','2017-09-27 16:39:32','2017-09-27 16:39:32'),(213,1,'admin/career','POST','127.0.0.1','{\"title\":\"\\u8f6f\\u4ef6\\u5de5\\u7a0b\\u5e08\",\"sort\":\"10\",\"display\":\"on\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\"}','2017-09-27 16:41:23','2017-09-27 16:41:23'),(214,1,'admin/career','GET','127.0.0.1','[]','2017-09-27 16:41:23','2017-09-27 16:41:23'),(215,1,'admin/career/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 16:41:25','2017-09-27 16:41:25'),(216,1,'admin/career','POST','127.0.0.1','{\"title\":\"\\u8f6f\\u4ef6\\u5de5\\u7a0b\\u5e08\",\"sort\":\"10\",\"display\":\"on\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/career\"}','2017-09-27 16:41:32','2017-09-27 16:41:32'),(217,1,'admin/career/create','GET','127.0.0.1','[]','2017-09-27 16:41:32','2017-09-27 16:41:32'),(218,1,'admin/career/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 16:41:36','2017-09-27 16:41:36'),(219,1,'admin/career','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 16:41:39','2017-09-27 16:41:39'),(220,1,'admin/career/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 16:41:40','2017-09-27 16:41:40'),(221,1,'admin/career/1','PUT','127.0.0.1','{\"title\":\"\\u8f6f\\u4ef6\\u5de5\\u7a0b\\u5e08\",\"sort\":\"10\",\"display\":\"on\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/career\"}','2017-09-27 16:41:42','2017-09-27 16:41:42'),(222,1,'admin/career/1/edit','GET','127.0.0.1','[]','2017-09-27 16:41:42','2017-09-27 16:41:42'),(223,1,'admin/career','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 16:41:44','2017-09-27 16:41:44'),(224,1,'admin/career/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 16:47:11','2017-09-27 16:47:11'),(225,1,'admin/career/1','PUT','127.0.0.1','{\"title\":\"\\u8f6f\\u4ef6\\u5de5\\u7a0b\\u5e08\",\"sort\":\"10\",\"display\":\"on\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/career\"}','2017-09-27 16:47:13','2017-09-27 16:47:13'),(226,1,'admin/career','GET','127.0.0.1','[]','2017-09-27 16:47:13','2017-09-27 16:47:13'),(227,1,'admin/career','GET','127.0.0.1','[]','2017-09-27 16:47:39','2017-09-27 16:47:39'),(228,1,'admin/career','GET','127.0.0.1','[]','2017-09-27 16:50:07','2017-09-27 16:50:07'),(229,1,'admin/auth/menu','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 16:50:23','2017-09-27 16:50:23'),(230,1,'admin/auth/menu','POST','127.0.0.1','{\"parent_id\":\"0\",\"title\":\"\\u57fa\\u7840\\u8bbe\\u7f6e\",\"icon\":\"fa-asterisk\",\"uri\":\"config\",\"roles\":[\"1\",\"\"],\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\"}','2017-09-27 16:51:26','2017-09-27 16:51:26'),(231,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-27 16:51:26','2017-09-27 16:51:26'),(232,1,'admin/auth/menu','POST','127.0.0.1','{\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_order\":\"[{\\\"id\\\":1},{\\\"id\\\":2,\\\"children\\\":[{\\\"id\\\":3},{\\\"id\\\":4},{\\\"id\\\":5},{\\\"id\\\":6},{\\\"id\\\":7}]},{\\\"id\\\":18,\\\"children\\\":[{\\\"id\\\":9}]},{\\\"id\\\":8},{\\\"id\\\":10},{\\\"id\\\":11,\\\"children\\\":[{\\\"id\\\":12},{\\\"id\\\":13},{\\\"id\\\":14},{\\\"id\\\":15},{\\\"id\\\":16},{\\\"id\\\":17}]}]\"}','2017-09-27 16:51:42','2017-09-27 16:51:42'),(233,1,'admin/auth/menu','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 16:51:43','2017-09-27 16:51:43'),(234,1,'admin/auth/menu','POST','127.0.0.1','{\"parent_id\":\"18\",\"title\":\"\\u804c\\u4e1a\\u7ba1\\u7406\",\"icon\":\"fa-user-md\",\"uri\":\"career\",\"roles\":[\"1\",\"\"],\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\"}','2017-09-27 16:52:13','2017-09-27 16:52:13'),(235,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-27 16:52:13','2017-09-27 16:52:13'),(236,1,'admin/auth/menu','POST','127.0.0.1','{\"parent_id\":\"18\",\"title\":\"\\u5b66\\u5386\\u7ba1\\u7406\",\"icon\":\"fa-book\",\"uri\":\"degree\",\"roles\":[\"1\",\"\"],\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\"}','2017-09-27 16:53:04','2017-09-27 16:53:04'),(237,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-27 16:53:04','2017-09-27 16:53:04'),(238,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-27 16:55:11','2017-09-27 16:55:11'),(239,1,'admin/degree','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 16:55:20','2017-09-27 16:55:20'),(240,1,'admin/degree/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 16:55:23','2017-09-27 16:55:23'),(241,1,'admin/degree','POST','127.0.0.1','{\"title\":\"\\u5927\\u4e13\",\"sort\":\"10\",\"display\":\"on\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/degree\"}','2017-09-27 16:55:30','2017-09-27 16:55:30'),(242,1,'admin/degree','GET','127.0.0.1','[]','2017-09-27 16:55:30','2017-09-27 16:55:30'),(243,1,'admin/degree/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 16:55:32','2017-09-27 16:55:32'),(244,1,'admin/degree','POST','127.0.0.1','{\"title\":\"\\u672c\\u79d1\",\"sort\":\"10\",\"display\":\"on\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/degree\"}','2017-09-27 16:55:39','2017-09-27 16:55:39'),(245,1,'admin/degree','GET','127.0.0.1','[]','2017-09-27 16:55:39','2017-09-27 16:55:39'),(246,1,'admin/degree/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 16:55:40','2017-09-27 16:55:40'),(247,1,'admin/degree','POST','127.0.0.1','{\"title\":\"\\u5927\\u4e13\",\"sort\":\"10\",\"display\":\"on\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/degree\"}','2017-09-27 16:55:44','2017-09-27 16:55:44'),(248,1,'admin/degree','GET','127.0.0.1','[]','2017-09-27 16:55:44','2017-09-27 16:55:44'),(249,1,'admin/degree/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 16:55:47','2017-09-27 16:55:47'),(250,1,'admin/degree','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 16:55:50','2017-09-27 16:55:50'),(251,1,'admin/degree/3/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 16:55:52','2017-09-27 16:55:52'),(252,1,'admin/degree/3','PUT','127.0.0.1','{\"title\":\"\\u7855\\u58eb\",\"sort\":\"10\",\"display\":\"on\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/degree\"}','2017-09-27 16:56:18','2017-09-27 16:56:18'),(253,1,'admin/degree','GET','127.0.0.1','[]','2017-09-27 16:56:18','2017-09-27 16:56:18'),(254,1,'admin/degree/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 16:56:38','2017-09-27 16:56:38'),(255,1,'admin/degree','POST','127.0.0.1','{\"title\":\"\\u5927\\u4e13\",\"sort\":\"10\",\"display\":\"on\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/degree\"}','2017-09-27 16:56:41','2017-09-27 16:56:41'),(256,1,'admin/degree/create','GET','127.0.0.1','[]','2017-09-27 16:56:42','2017-09-27 16:56:42'),(257,1,'admin/degree','POST','127.0.0.1','{\"title\":\"\\u535a\\u58eb\",\"sort\":\"10\",\"display\":\"on\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\"}','2017-09-27 16:56:48','2017-09-27 16:56:48'),(258,1,'admin/degree','GET','127.0.0.1','[]','2017-09-27 16:56:48','2017-09-27 16:56:48'),(259,1,'admin/degree/4','PUT','127.0.0.1','{\"display\":\"off\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_method\":\"PUT\"}','2017-09-27 16:56:51','2017-09-27 16:56:51'),(260,1,'admin/degree','GET','127.0.0.1','[]','2017-09-27 16:56:53','2017-09-27 16:56:53'),(261,1,'admin/degree/4','PUT','127.0.0.1','{\"display\":\"on\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_method\":\"PUT\"}','2017-09-27 16:56:56','2017-09-27 16:56:56'),(262,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 16:57:03','2017-09-27 16:57:03'),(263,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 16:57:10','2017-09-27 16:57:10'),(264,1,'admin/config','POST','127.0.0.1','{\"name\":\"CHARGE_BONUS_RULES\",\"value\":\",100|20\\r\\n100,200|25\\r\\n200,500|30\\r\\n500,|35\",\"description\":\"\\u533a\\u95f4\\u4e3a\\u534a\\u5f00\\u534a\\u95ed\\u533a\\u95f4\\uff0c\\u4e0d\\u9650\\u8d77\\u70b9\\u5219\\u9017\\u53f7\\u5de6\\u8fb9\\u4e0d\\u586b\\u5199\\uff0c\\u4e0d\\u9650\\u5236\\u4e0a\\u9650\\u5219\\u9017\\u53f7\\u53f3\\u8fb9\\u4e0d\\u586b\\u5199\\u3002\\u7528\\u7ad6\\u7ebf\\u9694\\u5f00\\u8d60\\u9001\\u6bd4\\u4f8b\\u300220%\\u519920\\u5c31\\u53ef\\u3002\\u5982\\u679c\\u9017\\u53f7\\u5de6\\u53f3\\u76f8\\u7b49\\u8868\\u793a\\u5145\\u503c\\u5bf9\\u5e94\\u91d1\\u989d\\u9001\\u591a\\u5c11\\u94b1\\u3002\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/config\"}','2017-09-27 17:00:47','2017-09-27 17:00:47'),(265,1,'admin/config','GET','127.0.0.1','[]','2017-09-27 17:00:47','2017-09-27 17:00:47'),(266,1,'admin/config/6/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 17:00:58','2017-09-27 17:00:58'),(267,1,'admin/config/6','PUT','127.0.0.1','{\"name\":\"CHARGE_BONUS_RULES\",\"value\":\",100|20\\r\\n100,200|25\\r\\n200,500|30\\r\\n500,|35\",\"description\":\"\\u533a\\u95f4\\u4e3a\\u534a\\u5f00\\u534a\\u95ed\\u533a\\u95f4\\uff0c\\u4e0d\\u9650\\u8d77\\u70b9\\u5219\\u9017\\u53f7\\u5de6\\u8fb9\\u4e0d\\u586b\\u5199\\uff0c\\u4e0d\\u9650\\u5236\\u4e0a\\u9650\\u5219\\u9017\\u53f7\\u53f3\\u8fb9\\u4e0d\\u586b\\u5199\\u3002\\u7528\\u7ad6\\u7ebf\\u9694\\u5f00\\u8d60\\u9001\\u6bd4\\u4f8b\\u300220%\\u519920\\u5c31\\u53ef\\u3002\\u5982\\u679c\\u9017\\u53f7\\u5de6\\u53f3\\u76f8\\u7b49\\u8868\\u793a\\u5145\\u503c\\u5bf9\\u5e94\\u91d1\\u989d\\u9001\\u591a\\u5c11\\u94b1\\u3002\\u6bcf\\u884c\\u4e00\\u6761\\u89c4\\u5219\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/config\"}','2017-09-27 17:01:06','2017-09-27 17:01:06'),(268,1,'admin/config','GET','127.0.0.1','[]','2017-09-27 17:01:06','2017-09-27 17:01:06'),(269,1,'admin/auth/menu','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 17:01:48','2017-09-27 17:01:48'),(270,1,'admin/auth/menu','POST','127.0.0.1','{\"parent_id\":\"0\",\"title\":\"\\u5e7f\\u544a\\u7ba1\\u7406\",\"icon\":\"fa-trademark\",\"uri\":\"admposts\",\"roles\":[\"1\",\"\"],\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\"}','2017-09-27 17:02:22','2017-09-27 17:02:22'),(271,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-27 17:02:22','2017-09-27 17:02:22'),(272,1,'admin/auth/menu','POST','127.0.0.1','{\"parent_id\":\"21\",\"title\":\"\\u5206\\u7c7b\\u7ba1\\u7406\",\"icon\":\"fa-bars\",\"uri\":\"category\",\"roles\":[\"1\",\"\"],\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\"}','2017-09-27 17:02:49','2017-09-27 17:02:49'),(273,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-27 17:02:50','2017-09-27 17:02:50'),(274,1,'admin/auth/menu','POST','127.0.0.1','{\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_order\":\"[{\\\"id\\\":1},{\\\"id\\\":2,\\\"children\\\":[{\\\"id\\\":3},{\\\"id\\\":4},{\\\"id\\\":5},{\\\"id\\\":6},{\\\"id\\\":7}]},{\\\"id\\\":18,\\\"children\\\":[{\\\"id\\\":9},{\\\"id\\\":19},{\\\"id\\\":20}]},{\\\"id\\\":21,\\\"children\\\":[{\\\"id\\\":22}]},{\\\"id\\\":8},{\\\"id\\\":10},{\\\"id\\\":11,\\\"children\\\":[{\\\"id\\\":12},{\\\"id\\\":13},{\\\"id\\\":14},{\\\"id\\\":15},{\\\"id\\\":16},{\\\"id\\\":17}]}]\"}','2017-09-27 17:03:03','2017-09-27 17:03:03'),(275,1,'admin/auth/menu','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 17:03:03','2017-09-27 17:03:03'),(276,1,'admin/auth/menu','POST','127.0.0.1','{\"parent_id\":\"21\",\"title\":\"\\u5e7f\\u544a\\u7ba1\\u7406\",\"icon\":\"fa-bars\",\"uri\":\"admposts\",\"roles\":[\"1\",\"\"],\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\"}','2017-09-27 17:03:23','2017-09-27 17:03:23'),(277,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-27 17:03:23','2017-09-27 17:03:23'),(278,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-27 17:03:26','2017-09-27 17:03:26'),(279,1,'admin/category','GET','127.0.0.1','[]','2017-09-27 17:06:20','2017-09-27 17:06:20'),(280,1,'admin/category/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 17:06:23','2017-09-27 17:06:23'),(281,1,'admin/category','POST','127.0.0.1','{\"title\":\"\\u5206\\u7c7b\\u4e00\",\"title_en\":\"C1\",\"sort\":\"10\",\"display\":\"on\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/category\"}','2017-09-27 17:06:34','2017-09-27 17:06:34'),(282,1,'admin/category','GET','127.0.0.1','[]','2017-09-27 17:06:35','2017-09-27 17:06:35'),(283,1,'admin/category/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 17:06:36','2017-09-27 17:06:36'),(284,1,'admin/category','POST','127.0.0.1','{\"title\":\"\\u5206\\u7c7b\\u4e00\",\"title_en\":\"C2\",\"sort\":\"10\",\"display\":\"on\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/category\"}','2017-09-27 17:06:43','2017-09-27 17:06:43'),(285,1,'admin/category/create','GET','127.0.0.1','[]','2017-09-27 17:06:43','2017-09-27 17:06:43'),(286,1,'admin/category','POST','127.0.0.1','{\"title\":\"\\u5206\\u7c7b\\u4e8c\",\"title_en\":\"C2\",\"sort\":\"10\",\"display\":\"on\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\"}','2017-09-27 17:06:53','2017-09-27 17:06:53'),(287,1,'admin/category','GET','127.0.0.1','[]','2017-09-27 17:06:53','2017-09-27 17:06:53'),(288,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 17:07:06','2017-09-27 17:07:06'),(289,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 17:07:11','2017-09-27 17:07:11'),(290,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 17:07:16','2017-09-27 17:07:16'),(291,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 17:07:30','2017-09-27 17:07:30'),(292,1,'admin/config','POST','127.0.0.1','{\"name\":\"CLICK_REWARD\",\"value\":\"0.1\\r\\n0.2\\r\\n0.5\\r\\n1.0\\r\\n1.5\\r\\n2.0\\r\\n3.5\",\"description\":\"\\u70b9\\u51fb\\u6700\\u5c0f\\u6536\\u76ca\\u503c\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/config\"}','2017-09-27 17:08:14','2017-09-27 17:08:14'),(293,1,'admin/config','GET','127.0.0.1','[]','2017-09-27 17:08:14','2017-09-27 17:08:14'),(294,1,'admin/config/7/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 17:08:19','2017-09-27 17:08:19'),(295,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 17:08:23','2017-09-27 17:08:23'),(296,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 17:08:37','2017-09-27 17:08:37'),(297,1,'admin/config','POST','127.0.0.1','{\"name\":\"PRICE_FILTER\",\"value\":\",0.5|<0.5\\u5143\\r\\n0.5,1.0|0.5~1.0\\u5143\\r\\n1.0,2.0|1.0~2.0\\u5143\\r\\n2.0,|>2.0\\u5143\",\"description\":\"\\u6bcf\\u884c\\u4e00\\u4e2a\\u503c\\uff0c\\u533a\\u95f4\\u4ee5\\u9017\\u53f7\\u9694\\u5f00\\uff0c\\u4e0d\\u586b\\u5199\\u8868\\u793a\\u4e0d\\u9650\\u3002\\u7ad6\\u7ebf\\u53f3\\u8fb9\\u8868\\u793a\\u5c55\\u793a\\u7ed9\\u7528\\u6237\\u7684\\u503c\\u3002\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/config\"}','2017-09-27 17:10:49','2017-09-27 17:10:49'),(298,1,'admin/config','GET','127.0.0.1','[]','2017-09-27 17:10:50','2017-09-27 17:10:50'),(299,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 17:11:05','2017-09-27 17:11:05'),(300,1,'admin/config','POST','127.0.0.1','{\"name\":\"CHARGE_BONUS_DESC\",\"value\":\"\",\"description\":\"\\u5145\\u503c\\u8d60\\u9001\\u8bf4\\u660e\\u3002\\u53ef\\u5199\\u586b\\u5199\\u8d60\\u9001\\u89c4\\u5219\\u4ee5\\u53ca\\u6d3b\\u52a8\\u65f6\\u95f4\\u3002\\u6587\\u5b57\\u4e0d\\u5f97\\u8d85\\u8fc7255\\u4e2a\\u5b57\\u7b26\\u3002\\u5982\\u679c\\u4e0d\\u586b\\u5199\\uff0c\\u5219\\u4e0d\\u663e\\u793a\\u3002\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/config\"}','2017-09-27 17:12:31','2017-09-27 17:12:31'),(301,1,'admin/config/create','GET','127.0.0.1','[]','2017-09-27 17:12:32','2017-09-27 17:12:32'),(302,1,'admin/config','POST','127.0.0.1','{\"name\":\"CHARGE_BONUS_DESC\",\"value\":\"0\",\"description\":\"\\u5145\\u503c\\u8d60\\u9001\\u8bf4\\u660e\\u3002\\u53ef\\u5199\\u586b\\u5199\\u8d60\\u9001\\u89c4\\u5219\\u4ee5\\u53ca\\u6d3b\\u52a8\\u65f6\\u95f4\\u3002\\u6587\\u5b57\\u4e0d\\u5f97\\u8d85\\u8fc7255\\u4e2a\\u5b57\\u7b26\\u3002\\u5982\\u9700\\u8981\\u4e0d\\u663e\\u793a\\u5219\\u586b\\u51990.\",\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\"}','2017-09-27 17:12:55','2017-09-27 17:12:55'),(303,1,'admin/config','GET','127.0.0.1','[]','2017-09-27 17:12:55','2017-09-27 17:12:55'),(304,1,'admin/config/create','GET','127.0.0.1','[]','2017-09-27 17:14:10','2017-09-27 17:14:10'),(305,1,'admin/auth/menu','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 17:14:14','2017-09-27 17:14:14'),(306,1,'admin/auth/menu/23/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 17:14:19','2017-09-27 17:14:19'),(307,1,'admin/auth/menu/23','PUT','127.0.0.1','{\"parent_id\":\"21\",\"title\":\"\\u5e7f\\u544a\\u7ba1\\u7406\",\"icon\":\"fa-bars\",\"uri\":\"advposts\",\"roles\":[\"1\",\"\"],\"_token\":\"Y12u9TLDlrPhUzE8MLyhsaEDHidmnrFHatuHResl\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/auth\\/menu\"}','2017-09-27 17:14:24','2017-09-27 17:14:24'),(308,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-27 17:14:24','2017-09-27 17:14:24'),(309,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-09-27 17:14:26','2017-09-27 17:14:26'),(310,1,'admin/advposts','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-27 17:14:28','2017-09-27 17:14:28'),(311,1,'admin/advposts','GET','127.0.0.1','[]','2017-09-27 17:17:04','2017-09-27 17:17:04'),(312,1,'admin/advposts','GET','127.0.0.1','[]','2017-09-27 17:17:18','2017-09-27 17:17:18'),(313,1,'admin/advposts','GET','127.0.0.1','[]','2017-09-27 17:17:38','2017-09-27 17:17:38'),(314,1,'admin/advposts','GET','127.0.0.1','[]','2017-09-27 17:18:10','2017-09-27 17:18:10'),(315,1,'admin/advposts','GET','127.0.0.1','[]','2017-09-28 03:28:39','2017-09-28 03:28:39'),(316,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-28 03:28:42','2017-09-28 03:28:42'),(317,1,'admin/config/6/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-28 03:28:46','2017-09-28 03:28:46'),(318,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-28 03:29:11','2017-09-28 03:29:11'),(319,1,'admin/config/7/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-28 03:29:15','2017-09-28 03:29:15'),(320,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-28 03:29:46','2017-09-28 03:29:46'),(321,1,'admin/config/8/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-28 03:29:49','2017-09-28 03:29:49'),(322,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-28 03:31:44','2017-09-28 03:31:44'),(323,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-28 03:37:18','2017-09-28 03:37:18'),(324,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-28 03:37:20','2017-09-28 03:37:20'),(325,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-28 03:37:23','2017-09-28 03:37:23'),(326,1,'admin/config','POST','127.0.0.1','{\"name\":\"INVITE_REWARD\",\"value\":\"2\",\"description\":\"\\u6bcf\\u9080\\u8bf7\\u6210\\u529f\\u4e00\\u4e2a\\u7528\\u6237\\u90012\\u70b9\\u72d7\\u7cae\",\"_token\":\"HVPpGhsjgI0ZDJBfWwl4xYl7NabfndLVlrngttRS\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/config\"}','2017-09-28 03:38:01','2017-09-28 03:38:01'),(327,1,'admin/config','GET','127.0.0.1','[]','2017-09-28 03:38:01','2017-09-28 03:38:01'),(328,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-28 03:40:10','2017-09-28 03:40:10'),(329,1,'admin/config','POST','127.0.0.1','{\"name\":\"SHARE_REWARD\",\"value\":\"1|0.05\\r\\n2|0.02\\r\\n3|0.005\",\"description\":\"\\u5411\\u4e0a\\u7ea7\\u5206\\u4eab\\u5956\\u52b1\\r\\n\\u4e00\\u7ea7\\u5206\\u4eab\\u8005 \\u4eab\\u53d7\\u5f53\\u524d\\u6536\\u76ca\\u76840.05\\r\\n\\u4e8c\\u7ea7\\u5206\\u4eab\\u8005 \\u4eab\\u53d7\\u5f53\\u524d\\u6536\\u76ca\\u76840.02\\r\\n\\u4e09\\u7ea7\\u5206\\u4eab\\u8005 \\u4eab\\u53d7\\u5f53\\u524d\\u6536\\u76ca\\u76840.005\",\"_token\":\"HVPpGhsjgI0ZDJBfWwl4xYl7NabfndLVlrngttRS\",\"_previous_\":\"http:\\/\\/localhost:8000\\/admin\\/config\"}','2017-09-28 03:44:08','2017-09-28 03:44:08'),(330,1,'admin/config','GET','127.0.0.1','[]','2017-09-28 03:44:09','2017-09-28 03:44:09'),(331,1,'admin','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-28 05:13:32','2017-09-28 05:13:32'),(332,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-09-28 05:15:23','2017-09-28 05:15:23'),(333,1,'admin','GET','127.0.0.1','[]','2017-10-02 08:05:43','2017-10-02 08:05:43'),(334,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-02 08:05:47','2017-10-02 08:05:47'),(335,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-02 08:05:50','2017-10-02 08:05:50'),(336,1,'admin/config','POST','127.0.0.1','{\"name\":\"MIN_BASEINFO_EDIT\",\"value\":\"30\",\"description\":\"\\u57fa\\u672c\\u4fe1\\u606f\\u591a\\u5c11\\u5929\\u540e\\u53ef\\u4ee5\\u518d\\u6b21\\u4fee\\u6539 \\u5355\\u4f4d\\u4e3a\\u5929\",\"_token\":\"E3cyfvsX3pYBIIHqWcJPMLIsIUQLepmBVxSAAshA\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/config\"}','2017-10-02 08:06:34','2017-10-02 08:06:34'),(337,1,'admin/config','GET','127.0.0.1','[]','2017-10-02 08:06:34','2017-10-02 08:06:34'),(338,1,'admin','GET','127.0.0.1','[]','2017-10-09 08:45:22','2017-10-09 08:45:22'),(339,1,'admin/cms/posts','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:45:30','2017-10-09 08:45:30'),(340,1,'admin/cms/posts','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:45:35','2017-10-09 08:45:35'),(341,1,'admin/cms/adv','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:46:02','2017-10-09 08:46:02'),(342,1,'admin/cms/adv','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:46:05','2017-10-09 08:46:05'),(343,1,'admin/cms/adv','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:46:06','2017-10-09 08:46:06'),(344,1,'admin/cms/adv','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:46:06','2017-10-09 08:46:06'),(345,1,'admin/cms/adv','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:46:06','2017-10-09 08:46:06'),(346,1,'admin/cms/feedback','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:46:08','2017-10-09 08:46:08'),(347,1,'admin/cms/adv','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:46:09','2017-10-09 08:46:09'),(348,1,'admin/cms/adv/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:46:15','2017-10-09 08:46:15'),(349,1,'admin/cms/adv/1','PUT','127.0.0.1','{\"title\":\"\\u8f6e\\u64ad1\",\"target_id\":\"1\",\"link\":\"#\",\"start_at\":\"2017-09-25\",\"end_at\":\"2017-12-21\",\"sort\":\"3\",\"display\":\"on\",\"_token\":\"Ls2Wmu6KHH1an8DNuwaU8Qw7Am3x8szDCPmwWmGu\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/cms\\/adv\"}','2017-10-09 08:46:59','2017-10-09 08:46:59'),(350,1,'admin/cms/adv','GET','127.0.0.1','[]','2017-10-09 08:46:59','2017-10-09 08:46:59'),(351,1,'admin/cms/adv/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:47:02','2017-10-09 08:47:02'),(352,1,'admin/cms/adv/1/edit','GET','127.0.0.1','[]','2017-10-09 08:47:11','2017-10-09 08:47:11'),(353,1,'admin/cms/adv/1/edit','GET','127.0.0.1','[]','2017-10-09 08:47:24','2017-10-09 08:47:24'),(354,1,'admin/cms/adv/1','PUT','127.0.0.1','{\"title\":\"\\u8f6e\\u64ad1\",\"target_id\":\"1\",\"link\":\"#\",\"start_at\":\"2017-09-25\",\"end_at\":\"2017-12-21\",\"sort\":\"3\",\"display\":\"on\",\"_token\":\"Ls2Wmu6KHH1an8DNuwaU8Qw7Am3x8szDCPmwWmGu\",\"_method\":\"PUT\"}','2017-10-09 08:47:59','2017-10-09 08:47:59'),(355,1,'admin/cms/adv','GET','127.0.0.1','[]','2017-10-09 08:47:59','2017-10-09 08:47:59'),(356,1,'admin/cms/adv/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:48:01','2017-10-09 08:48:01'),(357,1,'admin/cms/adv/1/edit','GET','127.0.0.1','[]','2017-10-09 08:50:01','2017-10-09 08:50:01'),(358,1,'admin/cms/adv','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:50:09','2017-10-09 08:50:09'),(359,1,'admin/cms/adv/1','DELETE','127.0.0.1','{\"_method\":\"delete\",\"_token\":\"Ls2Wmu6KHH1an8DNuwaU8Qw7Am3x8szDCPmwWmGu\"}','2017-10-09 08:50:11','2017-10-09 08:50:11'),(360,1,'admin/cms/adv','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:50:12','2017-10-09 08:50:12'),(361,1,'admin/cms/adv/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:50:14','2017-10-09 08:50:14'),(362,1,'admin/cms/adv','POST','127.0.0.1','{\"title\":\"\\u6d4b\\u8bd5\\u5e7f\\u544a\",\"target_id\":\"1\",\"link\":\"#\",\"start_at\":\"2017-10-09\",\"end_at\":\"\",\"sort\":\"0\",\"display\":\"off\",\"_token\":\"Ls2Wmu6KHH1an8DNuwaU8Qw7Am3x8szDCPmwWmGu\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/cms\\/adv\"}','2017-10-09 08:50:34','2017-10-09 08:50:34'),(363,1,'admin/cms/adv','GET','127.0.0.1','[]','2017-10-09 08:50:34','2017-10-09 08:50:34'),(364,1,'admin/cms/adv/2/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:50:36','2017-10-09 08:50:36'),(365,1,'admin/cms/adv/2/edit','GET','127.0.0.1','[]','2017-10-09 08:50:44','2017-10-09 08:50:44'),(366,1,'admin/cms/adv/2','PUT','127.0.0.1','{\"title\":\"\\u6d4b\\u8bd5\\u5e7f\\u544a\",\"target_id\":\"1\",\"link\":\"#\",\"start_at\":\"2017-10-09\",\"end_at\":\"\",\"sort\":\"0\",\"display\":\"off\",\"_token\":\"Ls2Wmu6KHH1an8DNuwaU8Qw7Am3x8szDCPmwWmGu\",\"_method\":\"PUT\"}','2017-10-09 08:54:23','2017-10-09 08:54:23'),(367,1,'admin/cms/adv/2/edit','GET','127.0.0.1','[]','2017-10-09 08:54:23','2017-10-09 08:54:23'),(368,1,'admin/cms/adv/2','PUT','127.0.0.1','{\"title\":\"\\u6d4b\\u8bd5\\u5e7f\\u544a\",\"target_id\":\"1\",\"link\":\"#\",\"start_at\":\"2017-10-09\",\"end_at\":\"2017-10-31\",\"sort\":\"0\",\"display\":\"off\",\"_token\":\"Ls2Wmu6KHH1an8DNuwaU8Qw7Am3x8szDCPmwWmGu\",\"_method\":\"PUT\"}','2017-10-09 08:54:36','2017-10-09 08:54:36'),(369,1,'admin/cms/adv','GET','127.0.0.1','[]','2017-10-09 08:54:37','2017-10-09 08:54:37'),(370,1,'admin/cms/adv/2/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:54:40','2017-10-09 08:54:40'),(371,1,'admin/cms/adv','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:54:54','2017-10-09 08:54:54'),(372,1,'admin/cms/adv/2','DELETE','127.0.0.1','{\"_method\":\"delete\",\"_token\":\"Ls2Wmu6KHH1an8DNuwaU8Qw7Am3x8szDCPmwWmGu\"}','2017-10-09 08:54:56','2017-10-09 08:54:56'),(373,1,'admin/cms/adv','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:54:57','2017-10-09 08:54:57'),(374,1,'admin/cms/adv/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:55:45','2017-10-09 08:55:45'),(375,1,'admin/cms/adv','POST','127.0.0.1','{\"title\":\"\\u626b\\u7801\\u540e\\u5982\\u4f55\\u5728\\u5bb6\\u590d\\u8bca\\u53ca\\u53d6\\u836f\",\"target_id\":\"1\",\"link\":\"#\",\"start_at\":\"\",\"end_at\":\"\",\"sort\":\"1\",\"display\":\"on\",\"_token\":\"Ls2Wmu6KHH1an8DNuwaU8Qw7Am3x8szDCPmwWmGu\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/cms\\/adv\"}','2017-10-09 08:55:59','2017-10-09 08:55:59'),(376,1,'admin/cms/adv','GET','127.0.0.1','[]','2017-10-09 08:55:59','2017-10-09 08:55:59'),(377,1,'admin/cms/adv','GET','127.0.0.1','[]','2017-10-09 08:56:01','2017-10-09 08:56:01'),(378,1,'admin/cms/adv/3/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:56:05','2017-10-09 08:56:05'),(379,1,'admin/cms/adv/3/edit','GET','127.0.0.1','[]','2017-10-09 08:56:09','2017-10-09 08:56:09'),(380,1,'admin/cms/adv','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:56:39','2017-10-09 08:56:39'),(381,1,'admin/cms/adv/3/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:56:44','2017-10-09 08:56:44'),(382,1,'admin/cms/advtarget','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-09 08:58:34','2017-10-09 08:58:34'),(383,1,'admin','GET','127.0.0.1','[]','2017-10-12 09:47:34','2017-10-12 09:47:34'),(384,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-12 09:51:00','2017-10-12 09:51:00'),(385,1,'admin/config','GET','127.0.0.1','[]','2017-10-13 01:26:50','2017-10-13 01:26:50'),(386,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-13 01:42:06','2017-10-13 01:42:06'),(387,1,'admin/config','GET','127.0.0.1','[]','2017-10-13 05:58:16','2017-10-13 05:58:16'),(388,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-13 05:58:21','2017-10-13 05:58:21'),(389,1,'admin/config','POST','127.0.0.1','{\"name\":\"MIN_PUP_CASH\",\"value\":\"1000\",\"description\":\"\\u6700\\u5c0f\\u53d1\\u5e03\\u91d1\\u989d\",\"_token\":\"GYEpP6ZymCkNdHx2Yb2xVd3DoDIsix7hX3yUOaXh\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/config\"}','2017-10-13 05:58:37','2017-10-13 05:58:37'),(390,1,'admin/config','GET','127.0.0.1','[]','2017-10-13 05:58:38','2017-10-13 05:58:38'),(391,1,'admin/config','GET','127.0.0.1','[]','2017-10-14 15:18:12','2017-10-14 15:18:12'),(392,1,'admin/config','GET','127.0.0.1','[]','2017-10-15 03:24:05','2017-10-15 03:24:05'),(393,1,'admin/logs','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-15 03:24:06','2017-10-15 03:24:06'),(394,1,'admin/advposts','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-15 03:24:13','2017-10-15 03:24:13'),(395,1,'admin/logs','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-15 03:24:21','2017-10-15 03:24:21'),(396,1,'admin/logs','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-15 03:24:24','2017-10-15 03:24:24'),(397,1,'admin/scheduling','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-15 03:24:25','2017-10-15 03:24:25'),(398,1,'admin/cms/posts','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-15 03:24:32','2017-10-15 03:24:32'),(399,1,'admin/cms/category','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-15 03:24:33','2017-10-15 03:24:33'),(400,1,'admin/cms/category/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-15 03:24:35','2017-10-15 03:24:35'),(401,1,'admin/cms/category','POST','127.0.0.1','{\"parent_id\":\"0\",\"name\":\"\\u7f51\\u7ad9\\u516c\\u544a\",\"keyword\":\"\\u7f51\\u7ad9\\u516c\\u544a\",\"description\":\"\\u7f51\\u7ad9\\u516c\\u544a\",\"content\":\"<p>\\u7f51\\u7ad9\\u516c\\u544a<\\/p>\",\"_token\":\"ZaEhQagMz2izkPW2bY3wj9TOXeZ8uWTKP0BTSx9e\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/cms\\/category\"}','2017-10-15 03:25:22','2017-10-15 03:25:22'),(402,1,'admin/cms/category','GET','127.0.0.1','[]','2017-10-15 03:25:22','2017-10-15 03:25:22'),(403,1,'admin/cms/category/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-15 03:25:33','2017-10-15 03:25:33'),(404,1,'admin/cms/category/1','PUT','127.0.0.1','{\"parent_id\":\"0\",\"name\":\"\\u9876\\u7ea7\\u5206\\u7c7b\",\"keyword\":\"\",\"description\":\"\",\"content\":\"\",\"_token\":\"ZaEhQagMz2izkPW2bY3wj9TOXeZ8uWTKP0BTSx9e\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/cms\\/category\"}','2017-10-15 03:25:47','2017-10-15 03:25:47'),(405,1,'admin/cms/category','GET','127.0.0.1','[]','2017-10-15 03:25:47','2017-10-15 03:25:47'),(406,1,'admin/cms/category/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-15 03:25:49','2017-10-15 03:25:49'),(407,1,'admin/cms/category/1','PUT','127.0.0.1','{\"parent_id\":\"0\",\"name\":\"\\u7f51\\u7ad9\\u516c\\u544a\",\"keyword\":\"\\u7f51\\u7ad9\\u516c\\u544a\",\"description\":\"\",\"content\":\"\",\"_token\":\"ZaEhQagMz2izkPW2bY3wj9TOXeZ8uWTKP0BTSx9e\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/cms\\/category\"}','2017-10-15 03:26:01','2017-10-15 03:26:01'),(408,1,'admin/cms/category','GET','127.0.0.1','[]','2017-10-15 03:26:02','2017-10-15 03:26:02'),(409,1,'admin/cms/category/2/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-15 03:26:04','2017-10-15 03:26:04'),(410,1,'admin/cms/category/2','PUT','127.0.0.1','{\"parent_id\":\"0\",\"name\":\"\\u8d5a\\u94b1\\u5b9d\",\"keyword\":\"\\u8d5a\\u94b1\\u5b9d\",\"description\":\"\\u8d5a\\u94b1\\u5b9d\",\"content\":\"<p>\\u8d5a\\u94b1\\u5b9d<\\/p>\",\"_token\":\"ZaEhQagMz2izkPW2bY3wj9TOXeZ8uWTKP0BTSx9e\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/cms\\/category\"}','2017-10-15 03:26:27','2017-10-15 03:26:27'),(411,1,'admin/cms/category','GET','127.0.0.1','[]','2017-10-15 03:26:27','2017-10-15 03:26:27'),(412,1,'admin/cms/posts','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-15 03:26:30','2017-10-15 03:26:30'),(413,1,'admin/cms/posts/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-15 03:26:33','2017-10-15 03:26:33'),(414,1,'admin/cms/posts','POST','127.0.0.1','{\"title\":\"\\u5145\\u503c\\u5c31\\u900120%\",\"category_id\":\"1\",\"author\":\"\\u5145\\u503c\\u5c31\\u9001\",\"keyword\":\"\\u5145\\u503c\\u5c31\\u9001\",\"description\":\"\\u5145\\u503c\\u5c31\\u9001\",\"content\":\"<p>\\u5145\\u503c\\u5c31\\u9001\\u5145\\u503c\\u5c31\\u9001\\u5145\\u503c\\u5c31\\u9001\\u5145\\u503c\\u5c31\\u9001\\u5145\\u503c\\u5c31\\u9001\\u5145\\u503c\\u5c31\\u9001<\\/p>\",\"is_top\":\"on\",\"is_pic\":\"on\",\"_token\":\"ZaEhQagMz2izkPW2bY3wj9TOXeZ8uWTKP0BTSx9e\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/cms\\/posts\"}','2017-10-15 03:27:32','2017-10-15 03:27:32'),(415,1,'admin/cms/posts','GET','127.0.0.1','[]','2017-10-15 03:27:32','2017-10-15 03:27:32'),(416,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-15 03:27:39','2017-10-15 03:27:39'),(417,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-15 03:27:44','2017-10-15 03:27:44'),(418,1,'admin/config','POST','127.0.0.1','{\"name\":\"NOTICE_CATE_ID\",\"value\":\"1\",\"description\":\"\\u901a\\u77e5\\u5206\\u7c7b\\u7684ID\\u7f16\\u53f7\",\"_token\":\"ZaEhQagMz2izkPW2bY3wj9TOXeZ8uWTKP0BTSx9e\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/config\"}','2017-10-15 03:27:58','2017-10-15 03:27:58'),(419,1,'admin/config','GET','127.0.0.1','[]','2017-10-15 03:27:58','2017-10-15 03:27:58'),(420,1,'admin/config','GET','127.0.0.1','[]','2017-10-15 03:28:51','2017-10-15 03:28:51'),(421,1,'admin/cms/posts','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-15 03:30:13','2017-10-15 03:30:13'),(422,1,'admin/cms/posts/2/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-15 03:30:21','2017-10-15 03:30:21'),(423,1,'admin/cms/posts','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-15 03:30:28','2017-10-15 03:30:28'),(424,1,'admin','GET','127.0.0.1','[]','2017-10-17 14:36:07','2017-10-17 14:36:07'),(425,1,'admin/cms/singlepage','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-17 14:36:13','2017-10-17 14:36:13'),(426,1,'admin/cms/singlepage/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-17 14:36:16','2017-10-17 14:36:16'),(427,1,'admin/cms/singlepage','POST','127.0.0.1','{\"title\":\"\\u5e2e\\u52a9\\u8bf4\\u660e\",\"author\":\"\\u5e2e\\u52a9\\u8bf4\\u660e\",\"keyword\":\"\\u5e2e\\u52a9\\u8bf4\\u660e\",\"description\":\"\\u5e2e\\u52a9\\u8bf4\\u660e\",\"content\":\"<p>\\u5e2e\\u52a9\\u8bf4\\u660e\\u5e2e\\u52a9\\u8bf4\\u660e\\u5e2e\\u52a9\\u8bf4\\u660e<\\/p>\",\"_token\":\"QsQulZChSxAOGgmruOWOos0gRtUSvDnt7WNHC1Vg\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/cms\\/singlepage\"}','2017-10-17 14:36:34','2017-10-17 14:36:34'),(428,1,'admin/cms/singlepage','GET','127.0.0.1','[]','2017-10-17 14:36:34','2017-10-17 14:36:34'),(429,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-17 14:36:45','2017-10-17 14:36:45'),(430,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-17 14:36:49','2017-10-17 14:36:49'),(431,1,'admin/config','POST','127.0.0.1','{\"name\":\"ABOUT_US\",\"value\":\"5\",\"description\":\"\\u5173\\u4e8e\\u6211\\u4eec\\u7684\\u6587\\u7ae0\\u7f16\\u53f7\",\"_token\":\"QsQulZChSxAOGgmruOWOos0gRtUSvDnt7WNHC1Vg\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/config\"}','2017-10-17 14:37:03','2017-10-17 14:37:03'),(432,1,'admin/config','GET','127.0.0.1','[]','2017-10-17 14:37:04','2017-10-17 14:37:04'),(433,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-17 14:37:07','2017-10-17 14:37:07'),(434,1,'admin/config','POST','127.0.0.1','{\"name\":\"HELP\",\"value\":\"6\",\"description\":\"\\u5e2e\\u52a9\\u8bf4\\u660e\\u6587\\u7ae0\\u7f16\\u53f7\",\"_token\":\"QsQulZChSxAOGgmruOWOos0gRtUSvDnt7WNHC1Vg\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/config\"}','2017-10-17 14:37:23','2017-10-17 14:37:23'),(435,1,'admin/config','GET','127.0.0.1','[]','2017-10-17 14:37:23','2017-10-17 14:37:23'),(436,1,'admin','GET','127.0.0.1','[]','2017-10-19 02:29:53','2017-10-19 02:29:53'),(437,1,'admin/cms/feedback','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-19 02:30:06','2017-10-19 02:30:06'),(438,1,'admin/cms/singlepage','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-19 02:30:11','2017-10-19 02:30:11'),(439,1,'admin/cms/feedback','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-19 02:30:13','2017-10-19 02:30:13'),(440,1,'admin/cms/category','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-19 02:30:14','2017-10-19 02:30:14'),(441,1,'admin/cms/posts','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-19 02:30:20','2017-10-19 02:30:20'),(442,1,'admin','GET','127.0.0.1','[]','2017-10-21 06:53:38','2017-10-21 06:53:38'),(443,1,'admin/auth/menu','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-21 06:53:45','2017-10-21 06:53:45'),(444,1,'admin/auth/menu','POST','127.0.0.1','{\"parent_id\":\"0\",\"title\":\"\\u6d88\\u606f\\u7ba1\\u7406\",\"icon\":\"fa-bars\",\"uri\":\"message\",\"roles\":[\"1\",\"\"],\"_token\":\"8ccI8cztVpcyTFLKhHgAmPqriAfTp66IIZQg6H0u\"}','2017-10-21 07:51:58','2017-10-21 07:51:58'),(445,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-10-21 07:51:58','2017-10-21 07:51:58'),(446,1,'admin/auth/menu','GET','127.0.0.1','[]','2017-10-21 07:52:00','2017-10-21 07:52:00'),(447,1,'admin/message','GET','127.0.0.1','[]','2017-10-21 07:52:52','2017-10-21 07:52:52'),(448,1,'admin/message','GET','127.0.0.1','[]','2017-10-21 07:54:30','2017-10-21 07:54:30'),(449,1,'admin/message','GET','127.0.0.1','[]','2017-10-21 07:55:42','2017-10-21 07:55:42'),(450,1,'admin/message','GET','127.0.0.1','[]','2017-10-21 07:55:49','2017-10-21 07:55:49'),(451,1,'admin/message','GET','127.0.0.1','[]','2017-10-21 07:56:09','2017-10-21 07:56:09'),(452,1,'admin/message/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-21 07:56:18','2017-10-21 07:56:18'),(453,1,'admin/message/create','GET','127.0.0.1','[]','2017-10-21 09:14:07','2017-10-21 09:14:07'),(454,1,'admin/message/create','GET','127.0.0.1','[]','2017-10-22 09:19:42','2017-10-22 09:19:42'),(455,1,'admin/message','POST','127.0.0.1','{\"mobile\":\"15986633874\",\"message\":\"hello world\",\"_token\":\"C5fipvPhJzCOlnznKTVyGw9pHBxMYgwFNMesHz84\"}','2017-10-22 09:21:20','2017-10-22 09:21:20'),(456,1,'admin/message','GET','127.0.0.1','[]','2017-10-22 09:21:21','2017-10-22 09:21:21'),(457,1,'admin/message/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-22 09:21:51','2017-10-22 09:21:51'),(458,1,'admin/message','POST','127.0.0.1','{\"mobile\":\"15986633874\",\"message\":\"123\",\"_token\":\"C5fipvPhJzCOlnznKTVyGw9pHBxMYgwFNMesHz84\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/message\"}','2017-10-22 09:21:59','2017-10-22 09:21:59'),(459,1,'admin/message','GET','127.0.0.1','[]','2017-10-22 09:22:00','2017-10-22 09:22:00'),(460,1,'admin/message/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-22 09:22:28','2017-10-22 09:22:28'),(461,1,'admin/message','POST','127.0.0.1','{\"mobile\":\"15986633874\",\"message\":\"hello world\",\"_token\":\"C5fipvPhJzCOlnznKTVyGw9pHBxMYgwFNMesHz84\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/message\"}','2017-10-22 09:22:38','2017-10-22 09:22:38'),(462,1,'admin/message','GET','127.0.0.1','[]','2017-10-23 08:31:30','2017-10-23 08:31:30'),(463,1,'admin/message/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-23 08:31:39','2017-10-23 08:31:39'),(464,1,'admin/message','POST','127.0.0.1','{\"mobile\":\"15986633874,18589027176\",\"message\":\"hello world !!!!\",\"_token\":\"aF8paETUbCI7d9AxIhCl72q038lVsNCQ0h27BrPo\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/message\"}','2017-10-23 08:31:58','2017-10-23 08:31:58'),(465,1,'admin/message/create','GET','127.0.0.1','[]','2017-10-23 08:31:58','2017-10-23 08:31:58'),(466,1,'admin/message','POST','127.0.0.1','{\"mobile\":\"15986633874,18589027176\",\"message\":\"hello world !!!!\",\"_token\":\"aF8paETUbCI7d9AxIhCl72q038lVsNCQ0h27BrPo\"}','2017-10-23 08:32:23','2017-10-23 08:32:23'),(467,1,'admin/message/create','GET','127.0.0.1','[]','2017-10-23 08:32:23','2017-10-23 08:32:23'),(468,1,'admin/message/create','GET','127.0.0.1','[]','2017-10-23 08:32:30','2017-10-23 08:32:30'),(469,1,'admin/message','POST','127.0.0.1','{\"mobile\":\"15986633874,18589027176\",\"message\":\"helloworld\",\"_token\":\"aF8paETUbCI7d9AxIhCl72q038lVsNCQ0h27BrPo\"}','2017-10-23 08:32:37','2017-10-23 08:32:37'),(470,1,'admin/message/create','GET','127.0.0.1','[]','2017-10-23 08:32:37','2017-10-23 08:32:37'),(471,1,'admin/message/create','GET','127.0.0.1','[]','2017-10-23 08:33:09','2017-10-23 08:33:09'),(472,1,'admin/message','POST','127.0.0.1','{\"mobile\":\"15986633874,18589027176\",\"message\":\"hello world !!!!\",\"_token\":\"aF8paETUbCI7d9AxIhCl72q038lVsNCQ0h27BrPo\"}','2017-10-23 08:33:17','2017-10-23 08:33:17'),(473,1,'admin/message','GET','127.0.0.1','[]','2017-10-23 08:33:17','2017-10-23 08:33:17'),(474,1,'admin/message','GET','127.0.0.1','[]','2017-10-23 08:35:21','2017-10-23 08:35:21'),(475,1,'admin/message','GET','127.0.0.1','[]','2017-10-23 08:36:23','2017-10-23 08:36:23'),(476,1,'admin/message/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-23 08:36:39','2017-10-23 08:36:39'),(477,1,'admin/message','POST','127.0.0.1','{\"mobile\":\"15986633874\",\"message\":\"i am fine !\",\"_token\":\"aF8paETUbCI7d9AxIhCl72q038lVsNCQ0h27BrPo\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/message\"}','2017-10-23 08:36:53','2017-10-23 08:36:53'),(478,1,'admin/message','GET','127.0.0.1','[]','2017-10-23 08:36:53','2017-10-23 08:36:53'),(479,1,'admin','GET','127.0.0.1','[]','2017-10-26 17:29:52','2017-10-26 17:29:52'),(480,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-26 17:29:56','2017-10-26 17:29:56'),(481,1,'admin/config','GET','127.0.0.1','[]','2017-10-26 17:30:37','2017-10-26 17:30:37'),(482,1,'admin/config/6/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-10-26 17:31:26','2017-10-26 17:31:26'),(483,1,'admin/config/6/edit','GET','127.0.0.1','[]','2017-10-26 17:40:12','2017-10-26 17:40:12'),(484,1,'admin','GET','127.0.0.1','[]','2017-11-12 08:55:11','2017-11-12 08:55:11'),(485,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-11-12 08:57:41','2017-11-12 08:57:41'),(486,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-11-12 08:57:46','2017-11-12 08:57:46'),(487,1,'admin/config','POST','127.0.0.1','{\"name\":\"big_small_desc\",\"value\":\"\\u6bd4\\u5927\\u5c0f\\u662f\\u4e00\\u4e2a\\u6bd4\\u624b\\u6c14\\u7684\\u6e38\\u620f\\uff0c\\u7cfb\\u7edf\\u4f1a\\u4e3a\\u60a8\\u548c\\u5bf9\\u624b\\u62bd\\u53d6\\u4e00\\u5f20\\u724c\\uff0c\\u70b9\\u6570\\u5927\\u7684\\u4e00\\u65b9\\u4e3a\\u80dc\\u5229\\u65b9\\uff0c\\u80dc\\u5229\\u65b9\\u53ef\\u4ee5\\u5c06\\u5931\\u8d25\\u65b9\\u7684\\u4fdd\\u8bc1\\u91d1\\u6682\\u65f6\\u4f5c\\u4e3a\\u6218\\u5229\\u54c1\\u3002\\u5f53\\u7136\\u80dc\\u5229\\u65b9\\u53ef\\u4ee5\\u9000\\u8fd8\\u6218\\u5229\\u54c1\\u7ed9\\u5931\\u8d25\\u65b9\\uff0c\\u5982\\u4e0d\\u9000\\u8fd8\\u7cfb\\u7edf\\u5c06\\u5728\\u534a\\u5e74\\u540e\\u9000\\u8fd8\\u4fdd\\u8bc1\\u91d1\\u7ed9\\u5931\\u8d25\\u65b9\\uff0c\\u4f46\\u4f1a\\u8bb0\\u4e00\\u6b21\\u5931\\u8d25\\u65b9\\u5dee\\u8bc4\\u3002\\r\\n\\u60a8\\u53ef\\u4ee5\\u5bf9\\u597d\\u53cb\\u6216\\u964c\\u751f\\u4eba\\u53d1\\u8d77\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7\\uff0c\\u60a8\\u5148\\u5f97\\u4ed8\\u4e00\\u5b9a\\u7684\\u72d7\\u7cae\\u4f5c\\u4e3a\\u4fdd\\u8bc1\\u91d1\\uff0c\\u800c\\u5e94\\u6218\\u65b9\\u5c06\\u4ee5\\u540c\\u6837\\u7684\\u6570\\u76ee\\u72d7\\u7cae\\u4f5c\\u4e3a\\u4fdd\\u8bc1\\u91d1\\u3002\\u724c\\u9762\\u70b9\\u6570\\u5927\\u7684\\u4e3a\\u80dc\\u65b9\\u3002\",\"description\":\"\\u6bd4\\u5927\\u5c0f\\u7684\\u8bf4\\u660e\\u6587\\u6848\",\"_token\":\"98vikfSBt5kIs49JeceHRY0Rjr4YyYZCcBvBSuYx\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/config\"}','2017-11-12 09:11:32','2017-11-12 09:11:32'),(488,1,'admin/config','GET','127.0.0.1','[]','2017-11-12 09:11:32','2017-11-12 09:11:32'),(489,1,'admin/config/17/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-11-12 09:11:37','2017-11-12 09:11:37'),(490,1,'admin/config/17','PUT','127.0.0.1','{\"name\":\"BIG_SMALL_DESC\",\"value\":\"\\u6bd4\\u5927\\u5c0f\\u662f\\u4e00\\u4e2a\\u6bd4\\u624b\\u6c14\\u7684\\u6e38\\u620f\\uff0c\\u7cfb\\u7edf\\u4f1a\\u4e3a\\u60a8\\u548c\\u5bf9\\u624b\\u62bd\\u53d6\\u4e00\\u5f20\\u724c\\uff0c\\u70b9\\u6570\\u5927\\u7684\\u4e00\\u65b9\\u4e3a\\u80dc\\u5229\\u65b9\\uff0c\\u80dc\\u5229\\u65b9\\u53ef\\u4ee5\\u5c06\\u5931\\u8d25\\u65b9\\u7684\\u4fdd\\u8bc1\\u91d1\\u6682\\u65f6\\u4f5c\\u4e3a\\u6218\\u5229\\u54c1\\u3002\\u5f53\\u7136\\u80dc\\u5229\\u65b9\\u53ef\\u4ee5\\u9000\\u8fd8\\u6218\\u5229\\u54c1\\u7ed9\\u5931\\u8d25\\u65b9\\uff0c\\u5982\\u4e0d\\u9000\\u8fd8\\u7cfb\\u7edf\\u5c06\\u5728\\u534a\\u5e74\\u540e\\u9000\\u8fd8\\u4fdd\\u8bc1\\u91d1\\u7ed9\\u5931\\u8d25\\u65b9\\uff0c\\u4f46\\u4f1a\\u8bb0\\u4e00\\u6b21\\u5931\\u8d25\\u65b9\\u5dee\\u8bc4\\u3002\\r\\n\\u60a8\\u53ef\\u4ee5\\u5bf9\\u597d\\u53cb\\u6216\\u964c\\u751f\\u4eba\\u53d1\\u8d77\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7\\uff0c\\u60a8\\u5148\\u5f97\\u4ed8\\u4e00\\u5b9a\\u7684\\u72d7\\u7cae\\u4f5c\\u4e3a\\u4fdd\\u8bc1\\u91d1\\uff0c\\u800c\\u5e94\\u6218\\u65b9\\u5c06\\u4ee5\\u540c\\u6837\\u7684\\u6570\\u76ee\\u72d7\\u7cae\\u4f5c\\u4e3a\\u4fdd\\u8bc1\\u91d1\\u3002\\u724c\\u9762\\u70b9\\u6570\\u5927\\u7684\\u4e3a\\u80dc\\u65b9\\u3002\",\"description\":\"\\u6bd4\\u5927\\u5c0f\\u7684\\u8bf4\\u660e\\u6587\\u6848\",\"_token\":\"98vikfSBt5kIs49JeceHRY0Rjr4YyYZCcBvBSuYx\",\"_method\":\"PUT\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/config\"}','2017-11-12 09:11:44','2017-11-12 09:11:44'),(491,1,'admin/config','GET','127.0.0.1','[]','2017-11-12 09:11:44','2017-11-12 09:11:44'),(492,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-11-12 09:11:50','2017-11-12 09:11:50'),(493,1,'admin/config','POST','127.0.0.1','{\"name\":\"BIG_SMALL_MIN_POINT\",\"value\":\"10\",\"description\":\"\\u6bd4\\u5927\\u5c0f\\u6700\\u5c0f\\u4fdd\\u8bc1\\u91d1\\u70b9\\u6570\",\"_token\":\"98vikfSBt5kIs49JeceHRY0Rjr4YyYZCcBvBSuYx\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/config\"}','2017-11-12 09:12:22','2017-11-12 09:12:22'),(494,1,'admin/config','GET','127.0.0.1','[]','2017-11-12 09:12:23','2017-11-12 09:12:23'),(495,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-11-12 09:12:25','2017-11-12 09:12:25'),(496,1,'admin/config','POST','127.0.0.1','{\"name\":\"BIG_SMALL_MAX_POINT\",\"value\":\"100\",\"description\":\"\\u6bd4\\u5927\\u5c0f\\u4fdd\\u8bc1\\u91d1\\u6700\\u5927\\u70b9\\u6570\",\"_token\":\"98vikfSBt5kIs49JeceHRY0Rjr4YyYZCcBvBSuYx\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/config\"}','2017-11-12 09:12:44','2017-11-12 09:12:44'),(497,1,'admin/config','GET','127.0.0.1','[]','2017-11-12 09:12:45','2017-11-12 09:12:45'),(498,1,'admin/config','GET','127.0.0.1','[]','2017-11-12 09:13:48','2017-11-12 09:13:48'),(499,1,'admin','GET','127.0.0.1','[]','2017-12-13 11:06:10','2017-12-13 11:06:10'),(500,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-12-13 11:06:15','2017-12-13 11:06:15'),(501,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-12-13 11:59:34','2017-12-13 11:59:34'),(502,1,'admin/config','POST','127.0.0.1','{\"name\":\"STOLEN_MAX_TIMES\",\"value\":\"20\",\"description\":\"\\u6bcf\\u4e2a\\u7528\\u6237\\u88ab\\u6700\\u5927\\u5077\\u53d6\\u6b21\\u6570\",\"_token\":\"3JHlQuNrgLAweYaPq8V8uMuT7mEx4G7TGEE1GbDS\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/config\"}','2017-12-13 12:00:00','2017-12-13 12:00:00'),(503,1,'admin/config','GET','127.0.0.1','[]','2017-12-13 12:00:00','2017-12-13 12:00:00'),(504,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-12-13 12:00:07','2017-12-13 12:00:07'),(505,1,'admin/config','POST','127.0.0.1','{\"name\":\"STOLEN_RATE\",\"value\":\"10\",\"description\":\"\\u6700\\u5927\\u5077\\u53d6\\u767e\\u5206\\u6bd4\",\"_token\":\"3JHlQuNrgLAweYaPq8V8uMuT7mEx4G7TGEE1GbDS\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/config\"}','2017-12-13 12:00:33','2017-12-13 12:00:33'),(506,1,'admin/config','GET','127.0.0.1','[]','2017-12-13 12:00:33','2017-12-13 12:00:33'),(507,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2017-12-13 12:00:35','2017-12-13 12:00:35'),(508,1,'admin','GET','127.0.0.1','[]','2018-01-13 16:03:38','2018-01-13 16:03:38'),(509,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2018-01-13 16:03:44','2018-01-13 16:03:44'),(510,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\",\"page\":\"2\"}','2018-01-13 16:15:22','2018-01-13 16:15:22'),(511,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2018-01-13 16:15:25','2018-01-13 16:15:25'),(512,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2018-01-13 16:15:55','2018-01-13 16:15:55'),(513,1,'admin/config','POST','127.0.0.1','{\"name\":\"CASH_RATE\",\"value\":\"100\",\"description\":\"\\u72d7\\u7cae\\u548c\\u73b0\\u91d1\\u7684\\u5bf9\\u6bd4\\u503c 100\\u72d7\\u7cae\\u7b49\\u4e8e 1\\u5143\\u94b1\",\"_token\":\"24GoKjDzgESPYV4HJRHNlKMkRqGISeeOPQiEqRIT\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/config\"}','2018-01-13 16:16:21','2018-01-13 16:16:21'),(514,1,'admin/config','GET','127.0.0.1','[]','2018-01-13 16:16:21','2018-01-13 16:16:21'),(515,1,'admin','GET','127.0.0.1','[]','2018-01-14 16:05:13','2018-01-14 16:05:13'),(516,1,'admin/auth/menu','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2018-01-14 16:05:27','2018-01-14 16:05:27'),(517,1,'admin/auth/menu','POST','127.0.0.1','{\"parent_id\":\"0\",\"title\":\"\\u8d22\\u52a1\\u7ba1\\u7406\",\"icon\":\"fa-bars\",\"uri\":\"finance\",\"roles\":[\"1\",\"\"],\"_token\":\"QJjUluPUUh6pC0HGzirQX38RuiFBZzidCBXXRrUz\"}','2018-01-14 16:05:38','2018-01-14 16:05:38'),(518,1,'admin/auth/menu','GET','127.0.0.1','[]','2018-01-14 16:05:38','2018-01-14 16:05:38'),(519,1,'admin/auth/menu','POST','127.0.0.1','{\"parent_id\":\"25\",\"title\":\"\\u63d0\\u73b0\\u7ba1\\u7406\",\"icon\":\"fa-bars\",\"uri\":\"withdraw\",\"roles\":[\"\"],\"_token\":\"QJjUluPUUh6pC0HGzirQX38RuiFBZzidCBXXRrUz\"}','2018-01-14 16:05:51','2018-01-14 16:05:51'),(520,1,'admin/auth/menu','GET','127.0.0.1','[]','2018-01-14 16:05:51','2018-01-14 16:05:51'),(521,1,'admin/auth/menu','GET','127.0.0.1','[]','2018-01-14 16:05:54','2018-01-14 16:05:54'),(522,1,'admin/withdraw','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2018-01-14 16:05:57','2018-01-14 16:05:57'),(523,1,'admin/withdraw','GET','127.0.0.1','[]','2018-01-14 16:06:09','2018-01-14 16:06:09'),(524,1,'admin/withdraw/1/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2018-01-14 16:06:13','2018-01-14 16:06:13'),(525,1,'admin/withdraw/1/edit','GET','127.0.0.1','[]','2018-01-14 16:06:38','2018-01-14 16:06:38'),(526,1,'admin/withdraw/1/edit','GET','127.0.0.1','[]','2018-01-14 16:07:06','2018-01-14 16:07:06'),(527,1,'admin/withdraw/1/edit','GET','127.0.0.1','[]','2018-01-14 16:07:08','2018-01-14 16:07:08'),(528,1,'admin/withdraw/1/edit','GET','127.0.0.1','[]','2018-01-14 16:07:10','2018-01-14 16:07:10'),(529,1,'admin/withdraw/1/edit','GET','127.0.0.1','[]','2018-01-14 16:08:14','2018-01-14 16:08:14'),(530,1,'admin/withdraw/1','PUT','127.0.0.1','{\"status\":\"2\",\"remark\":\"\",\"_token\":\"QJjUluPUUh6pC0HGzirQX38RuiFBZzidCBXXRrUz\",\"_method\":\"PUT\"}','2018-01-14 16:08:48','2018-01-14 16:08:48'),(531,1,'admin/withdraw/1/edit','GET','127.0.0.1','[]','2018-01-14 16:08:48','2018-01-14 16:08:48'),(532,1,'admin','GET','127.0.0.1','[]','2018-01-15 13:58:34','2018-01-15 13:58:34'),(533,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2018-01-15 13:58:38','2018-01-15 13:58:38'),(534,1,'admin/config/19/edit','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2018-01-15 13:59:05','2018-01-15 13:59:05'),(535,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2018-01-15 13:59:06','2018-01-15 13:59:06'),(536,1,'admin/config','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\",\"page\":\"2\"}','2018-01-15 13:59:11','2018-01-15 13:59:11'),(537,1,'admin/config/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2018-01-15 13:59:19','2018-01-15 13:59:19'),(538,1,'admin/config','POST','127.0.0.1','{\"name\":\"BIG_SMALL_COMMISSION\",\"value\":\"10\",\"description\":\"\\u6309\\u767e\\u5206\\u6bd4\\u6263\\u53d6\",\"_token\":\"eubCussByj7V9OlSEPm5l2vEPqi6hi1KAoWC9isl\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/config?&page=2\"}','2018-01-15 14:00:51','2018-01-15 14:00:51'),(539,1,'admin/config','GET','127.0.0.1','{\"page\":\"2\"}','2018-01-15 14:00:51','2018-01-15 14:00:51'),(540,1,'admin','GET','127.0.0.1','[]','2018-01-24 16:55:16','2018-01-24 16:55:16'),(541,1,'admin/career','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2018-01-24 16:55:21','2018-01-24 16:55:21'),(542,1,'admin/career/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2018-01-24 16:55:23','2018-01-24 16:55:23'),(543,1,'admin/career','POST','127.0.0.1','{\"title\":\"\\u6e38\\u620f\",\"sort\":\"10\",\"display\":\"on\",\"_token\":\"mE43lND4ZRi11LwS7GmVkAIfX6kHJTmCGm1leNhV\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/career\"}','2018-01-24 16:55:30','2018-01-24 16:55:30'),(544,1,'admin/career','GET','127.0.0.1','[]','2018-01-24 16:55:30','2018-01-24 16:55:30'),(545,1,'admin/career/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2018-01-24 16:55:32','2018-01-24 16:55:32'),(546,1,'admin/career','POST','127.0.0.1','{\"title\":\"\\u8d2d\\u7269\",\"sort\":\"10\",\"display\":\"on\",\"_token\":\"mE43lND4ZRi11LwS7GmVkAIfX6kHJTmCGm1leNhV\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/career\"}','2018-01-24 16:55:36','2018-01-24 16:55:36'),(547,1,'admin/career','GET','127.0.0.1','[]','2018-01-24 16:55:36','2018-01-24 16:55:36'),(548,1,'admin/career/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2018-01-24 16:55:37','2018-01-24 16:55:37'),(549,1,'admin/career','POST','127.0.0.1','{\"title\":\"\\u804a\\u5929\",\"sort\":\"10\",\"display\":\"on\",\"_token\":\"mE43lND4ZRi11LwS7GmVkAIfX6kHJTmCGm1leNhV\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/career\"}','2018-01-24 16:55:44','2018-01-24 16:55:44'),(550,1,'admin/career','GET','127.0.0.1','[]','2018-01-24 16:55:44','2018-01-24 16:55:44'),(551,1,'admin/career/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2018-01-24 16:55:46','2018-01-24 16:55:46'),(552,1,'admin/career','POST','127.0.0.1','{\"title\":\"\\u5496\\u5561\",\"sort\":\"10\",\"display\":\"on\",\"_token\":\"mE43lND4ZRi11LwS7GmVkAIfX6kHJTmCGm1leNhV\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/career\"}','2018-01-24 16:55:50','2018-01-24 16:55:50'),(553,1,'admin/career','GET','127.0.0.1','[]','2018-01-24 16:55:50','2018-01-24 16:55:50'),(554,1,'admin/career/create','GET','127.0.0.1','{\"_pjax\":\"#pjax-container\"}','2018-01-24 16:55:51','2018-01-24 16:55:51'),(555,1,'admin/career','POST','127.0.0.1','{\"title\":\"\\u7ea2\\u9152\",\"sort\":\"10\",\"display\":\"on\",\"_token\":\"mE43lND4ZRi11LwS7GmVkAIfX6kHJTmCGm1leNhV\",\"_previous_\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/career\"}','2018-01-24 16:55:55','2018-01-24 16:55:55'),(556,1,'admin/career','GET','127.0.0.1','[]','2018-01-24 16:55:55','2018-01-24 16:55:55');
/*!40000 ALTER TABLE `admin_operation_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_permissions`
--

DROP TABLE IF EXISTS `admin_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `http_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `http_path` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_permissions_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_permissions`
--

LOCK TABLES `admin_permissions` WRITE;
/*!40000 ALTER TABLE `admin_permissions` DISABLE KEYS */;
INSERT INTO `admin_permissions` VALUES (1,'All permission','*','','*',NULL,NULL),(2,'Dashboard','dashboard','GET','/',NULL,NULL),(3,'Login','auth.login','','/auth/login\r\n/auth/logout',NULL,NULL),(4,'User setting','auth.setting','GET,PUT','/auth/setting',NULL,NULL),(5,'Auth management','auth.management','','/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs',NULL,NULL),(6,'Logs','ext.log-viewer',NULL,'/logs*','2017-09-25 07:57:18','2017-09-25 07:57:18'),(7,'Admin Config','ext.config',NULL,'/config*','2017-09-25 07:57:54','2017-09-25 07:57:54'),(8,'Scheduling','ext.scheduling',NULL,'/scheduling*','2017-09-25 07:58:11','2017-09-25 07:58:11');
/*!40000 ALTER TABLE `admin_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_role_menu`
--

DROP TABLE IF EXISTS `admin_role_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_role_menu` (
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `admin_role_menu_role_id_menu_id_index` (`role_id`,`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_role_menu`
--

LOCK TABLES `admin_role_menu` WRITE;
/*!40000 ALTER TABLE `admin_role_menu` DISABLE KEYS */;
INSERT INTO `admin_role_menu` VALUES (1,2,NULL,NULL),(1,11,NULL,NULL),(1,12,NULL,NULL),(1,13,NULL,NULL),(1,14,NULL,NULL),(1,15,NULL,NULL),(1,16,NULL,NULL),(1,17,NULL,NULL),(1,18,NULL,NULL),(1,19,NULL,NULL),(1,20,NULL,NULL),(1,21,NULL,NULL),(1,22,NULL,NULL),(1,23,NULL,NULL),(1,24,NULL,NULL),(1,25,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_role_permissions`
--

DROP TABLE IF EXISTS `admin_role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `admin_role_permissions_role_id_permission_id_index` (`role_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_role_permissions`
--

LOCK TABLES `admin_role_permissions` WRITE;
/*!40000 ALTER TABLE `admin_role_permissions` DISABLE KEYS */;
INSERT INTO `admin_role_permissions` VALUES (1,1,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_role_users`
--

DROP TABLE IF EXISTS `admin_role_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_role_users` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `admin_role_users_role_id_user_id_index` (`role_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_role_users`
--

LOCK TABLES `admin_role_users` WRITE;
/*!40000 ALTER TABLE `admin_role_users` DISABLE KEYS */;
INSERT INTO `admin_role_users` VALUES (1,1,NULL,NULL);
/*!40000 ALTER TABLE `admin_role_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_roles`
--

DROP TABLE IF EXISTS `admin_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_roles`
--

LOCK TABLES `admin_roles` WRITE;
/*!40000 ALTER TABLE `admin_roles` DISABLE KEYS */;
INSERT INTO `admin_roles` VALUES (1,'Administrator','administrator','2017-09-25 07:54:52','2017-09-25 07:54:52');
/*!40000 ALTER TABLE `admin_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_user_permissions`
--

DROP TABLE IF EXISTS `admin_user_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_user_permissions` (
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `admin_user_permissions_user_id_permission_id_index` (`user_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_user_permissions`
--

LOCK TABLES `admin_user_permissions` WRITE;
/*!40000 ALTER TABLE `admin_user_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_user_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'admin','$2y$10$BPv4glKeEVFzdbdUBfnH6.FOVTXwU1X8RLtK7Fbg0US.XdMXKGzM6','Administrator',NULL,NULL,'2017-09-25 07:54:52','2017-09-25 07:54:52');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `big_small`
--

DROP TABLE IF EXISTS `big_small`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `big_small` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `invite_user_id` int(10) unsigned NOT NULL,
  `cash_deposit` int(10) unsigned NOT NULL,
  `deposit_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `user_num` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `inviter_num` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `big_small`
--

LOCK TABLES `big_small` WRITE;
/*!40000 ALTER TABLE `big_small` DISABLE KEYS */;
INSERT INTO `big_small` VALUES (3,2,1,235,1,3,3,0,'2017-11-15 03:45:15','2017-11-21 06:55:29'),(4,1,2,100,1,0,9,0,'2017-11-15 03:48:51','2017-11-15 03:48:51'),(5,1,2,23,1,0,7,0,'2017-11-15 03:51:29','2017-11-15 03:51:29'),(6,1,2,22,1,0,3,0,'2017-11-15 03:54:36','2017-11-15 03:54:36'),(7,1,2,12,1,0,1,0,'2017-12-13 09:26:18','2017-12-13 09:26:18'),(8,3,2,50,1,0,1,0,'2017-12-25 10:14:12','2017-12-25 10:14:12'),(9,2,1,100,1,0,6,0,'2018-01-08 12:54:44','2018-01-08 12:54:44'),(10,2,1,100,1,0,11,0,'2018-01-08 12:55:26','2018-01-08 12:55:26'),(11,2,1,100,1,1,6,3,'2018-01-08 12:56:00','2018-01-13 15:33:21'),(12,2,1,10,1,1,11,5,'2018-01-08 12:58:57','2018-01-13 15:32:11'),(13,2,1,50,1,1,6,5,'2018-01-08 13:01:25','2018-01-13 15:33:45'),(14,2,1,50,1,1,13,3,'2018-01-08 13:03:35','2018-01-13 15:32:45'),(15,2,1,50,1,1,9,8,'2018-01-08 13:03:57','2018-01-13 15:31:39'),(16,2,1,200,1,2,3,2,'2018-01-08 13:51:16','2018-01-08 16:52:50'),(17,2,1,200,1,1,3,3,'2018-01-08 13:51:46','2018-01-08 16:26:24'),(18,2,1,200,1,3,13,0,'2018-01-08 13:52:20','2018-01-08 16:22:54'),(19,2,1,40,1,3,5,0,'2018-01-10 13:09:38','2018-01-13 15:30:12'),(20,1,2,100,1,0,2,0,'2018-01-10 14:25:11','2018-01-10 14:25:11'),(21,1,2,60,1,0,3,0,'2018-01-10 14:27:27','2018-01-10 14:27:27'),(22,1,2,99,1,1,4,2,'2018-01-10 14:30:53','2018-01-13 15:40:02'),(23,1,2,88,1,1,2,1,'2018-01-10 14:33:15','2018-01-13 15:40:21'),(24,1,2,78,1,1,4,1,'2018-01-10 14:37:39','2018-01-13 15:39:02'),(25,1,2,66,1,1,6,1,'2018-01-10 14:38:49','2018-01-10 14:38:57'),(26,2,1,100,1,1,9,7,'2018-01-10 15:22:05','2018-01-10 15:22:51'),(27,1,2,50,1,1,13,2,'2018-01-12 06:29:33','2018-01-13 15:38:48'),(28,1,2,46,1,1,3,1,'2018-01-12 06:30:12','2018-01-13 15:44:20'),(29,1,2,46,1,0,9,0,'2018-01-12 06:30:26','2018-01-12 06:30:26'),(30,1,2,99,1,1,10,1,'2018-01-12 06:31:24','2018-01-13 15:50:16'),(31,1,2,34,1,1,7,2,'2018-01-12 06:56:26','2018-01-13 15:38:36'),(32,1,2,56,1,4,13,0,'2018-01-12 07:11:25','2018-01-12 07:29:52'),(33,2,1,55,1,1,11,2,'2018-01-13 15:50:39','2018-01-13 15:50:51'),(34,2,1,55,1,1,10,8,'2018-01-13 15:50:58','2018-01-13 15:51:07'),(35,2,1,55,1,1,13,1,'2018-01-13 15:51:12','2018-01-13 15:51:17'),(36,2,1,55,1,2,2,5,'2018-01-13 15:51:25','2018-01-13 15:57:50'),(37,2,1,55,1,1,8,5,'2018-01-15 12:23:40','2018-01-15 12:27:32'),(38,2,1,66,1,0,13,0,'2018-01-16 16:05:55','2018-01-16 16:05:55'),(39,1,2,22,1,0,3,0,'2018-01-16 16:06:21','2018-01-16 16:06:21'),(40,2,1,23,1,0,5,0,'2018-01-16 16:14:38','2018-01-16 16:14:38'),(41,1,2,77,1,1,1,8,'2018-01-16 16:15:03','2018-01-16 16:15:15'),(42,2,1,77,1,0,11,0,'2018-01-22 12:51:44','2018-01-22 12:51:44'),(43,2,1,77,1,0,4,0,'2018-01-22 12:59:14','2018-01-22 12:59:14'),(44,2,1,77,1,0,7,0,'2018-01-22 12:59:45','2018-01-22 12:59:45'),(45,2,1,77,1,0,11,0,'2018-01-22 13:00:14','2018-01-22 13:00:14'),(46,2,1,77,1,0,2,0,'2018-01-22 13:03:50','2018-01-22 13:03:50'),(47,2,1,77,1,0,12,0,'2018-01-22 13:04:25','2018-01-22 13:04:25'),(48,2,1,77,1,0,8,0,'2018-01-22 13:04:58','2018-01-22 13:04:58'),(49,2,1,77,1,0,11,0,'2018-01-22 13:05:21','2018-01-22 13:05:21'),(50,2,1,77,1,0,5,0,'2018-01-22 13:05:28','2018-01-22 13:05:28'),(51,2,1,77,1,0,13,0,'2018-01-22 13:06:13','2018-01-22 13:06:13'),(52,2,1,77,1,0,10,0,'2018-01-22 13:07:41','2018-01-22 13:07:41'),(53,2,1,77,1,0,1,0,'2018-01-22 13:11:08','2018-01-22 13:11:08'),(54,2,1,77,1,0,6,0,'2018-01-22 13:11:52','2018-01-22 13:11:52'),(55,2,1,77,1,0,1,0,'2018-01-22 13:12:39','2018-01-22 13:12:39'),(56,2,1,77,1,0,9,0,'2018-01-22 13:13:26','2018-01-22 13:13:26'),(57,2,1,77,1,0,12,0,'2018-01-22 13:14:08','2018-01-22 13:14:08'),(58,2,1,77,1,0,12,0,'2018-01-22 13:15:08','2018-01-22 13:15:08'),(59,2,1,44,1,0,13,0,'2018-01-22 13:16:29','2018-01-22 13:16:29'),(60,2,1,44,1,0,5,0,'2018-01-22 13:18:47','2018-01-22 13:18:47'),(61,2,1,44,1,0,5,0,'2018-01-22 13:19:50','2018-01-22 13:19:50');
/*!40000 ALTER TABLE `big_small` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `charge_log`
--

DROP TABLE IF EXISTS `charge_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `charge_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `out_trade_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `trade_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `charge` decimal(8,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'wait',
  `notice_time` int(10) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `charge_log`
--

LOCK TABLES `charge_log` WRITE;
/*!40000 ALTER TABLE `charge_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `charge_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_adv`
--

DROP TABLE IF EXISTS `cms_adv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_adv` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_at` timestamp NULL DEFAULT NULL,
  `end_at` timestamp NULL DEFAULT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_adv`
--

LOCK TABLES `cms_adv` WRITE;
/*!40000 ALTER TABLE `cms_adv` DISABLE KEYS */;
INSERT INTO `cms_adv` VALUES (3,'扫码后如何在家复诊及取药',1,'images/banner.jpg','#',NULL,NULL,1,1,'2017-10-09 08:55:59','2017-10-09 08:55:59');
/*!40000 ALTER TABLE `cms_adv` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_adv_target`
--

DROP TABLE IF EXISTS `cms_adv_target`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_adv_target` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_adv_target`
--

LOCK TABLES `cms_adv_target` WRITE;
/*!40000 ALTER TABLE `cms_adv_target` DISABLE KEYS */;
INSERT INTO `cms_adv_target` VALUES (1,'移动端首页BANNER','WAP_INDEX_BANNER','移动端首页BANNER','2017-09-25 14:24:18','2017-09-25 14:24:18');
/*!40000 ALTER TABLE `cms_adv_target` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_blog`
--

DROP TABLE IF EXISTS `cms_blog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_blog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0',
  `is_top` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `views` smallint(5) unsigned NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_blog`
--

LOCK TABLES `cms_blog` WRITE;
/*!40000 ALTER TABLE `cms_blog` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_blog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_category`
--

DROP TABLE IF EXISTS `cms_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0',
  `is_recom` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_category`
--

LOCK TABLES `cms_category` WRITE;
/*!40000 ALTER TABLE `cms_category` DISABLE KEYS */;
INSERT INTO `cms_category` VALUES (1,'网站公告','网站公告','',0,'images/d461fe9673d69b53590f76a0bc0346fd.png','',0,0,'2017-09-25 13:45:38','2017-10-15 03:26:01'),(2,'赚钱宝','赚钱宝','赚钱宝',0,'images/logo.png','<p>赚钱宝</p>',0,0,'2017-10-15 03:25:22','2017-10-15 03:26:27');
/*!40000 ALTER TABLE `cms_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_feedback`
--

DROP TABLE IF EXISTS `cms_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_feedback`
--

LOCK TABLES `cms_feedback` WRITE;
/*!40000 ALTER TABLE `cms_feedback` DISABLE KEYS */;
INSERT INTO `cms_feedback` VALUES (1,1,'15986633874','有时候真的不知道说什么啊',0,'2017-10-18 05:10:35','2017-10-18 05:10:35'),(2,0,'15986633871','for   test what the same',0,'2017-12-31 05:28:14','2017-12-31 05:28:14');
/*!40000 ALTER TABLE `cms_feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_posts`
--

DROP TABLE IF EXISTS `cms_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int(10) unsigned NOT NULL DEFAULT '0',
  `author` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0',
  `is_top` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_pic` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `views` smallint(5) unsigned NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_posts`
--

LOCK TABLES `cms_posts` WRITE;
/*!40000 ALTER TABLE `cms_posts` DISABLE KEYS */;
INSERT INTO `cms_posts` VALUES (1,'测试资讯发布','','',1,'Contact Us','images/bg_netcall_fullscreen.png','<p style=\"box-sizing: border-box; line-height: 1.7; margin-top: 10px; margin-bottom: 20px; font-size: 14.5px; color: rgb(82, 82, 82); font-family: &quot;Source Sans Pro&quot;, sans-serif; white-space: normal; background-color: rgb(255, 255, 255);\">You may rollback a limited number of migrations by providing the&nbsp;<code class=\" language-php\" style=\"box-sizing: border-box; font-family: &quot;Operator Mono&quot;, &quot;Fira Code&quot;, Consolas, Monaco, &quot;Andale Mono&quot;, monospace; font-size: 11px; color: rgb(244, 100, 95); border-radius: 3px; background: rgb(240, 242, 241); padding: 1px 5px; white-space: pre; word-spacing: normal; word-break: normal; direction: ltr; text-shadow: rgb(255, 255, 255) 0px 1px; line-height: 2; tab-size: 4; hyphens: none; box-shadow: rgba(0, 0, 0, 0.125) 0px 1px 1px; vertical-align: middle;\">step</code>&nbsp;option to the&nbsp;<code class=\" language-php\" style=\"box-sizing: border-box; font-family: &quot;Operator Mono&quot;, &quot;Fira Code&quot;, Consolas, Monaco, &quot;Andale Mono&quot;, monospace; font-size: 11px; color: rgb(244, 100, 95); border-radius: 3px; background: rgb(240, 242, 241); padding: 1px 5px; white-space: pre; word-spacing: normal; word-break: normal; direction: ltr; text-shadow: rgb(255, 255, 255) 0px 1px; line-height: 2; tab-size: 4; hyphens: none; box-shadow: rgba(0, 0, 0, 0.125) 0px 1px 1px; vertical-align: middle;\">rollback</code>command. For example, the following command will rollback the last five migrations:</p><p><br/></p>',0,1,1,0,'2017-09-25 13:47:50','2017-09-25 13:46:20','2017-09-25 13:47:50'),(2,'充值就送20%','充值就送','充值就送',1,'充值就送','images/2d80057ee7d64a4e7d4f832090b5ce5c.png','<p>充值就送充值就送充值就送充值就送充值就送充值就送</p>',0,1,1,0,NULL,'2017-10-15 03:27:32','2017-10-15 03:27:32');
/*!40000 ALTER TABLE `cms_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_singlepage`
--

DROP TABLE IF EXISTS `cms_singlepage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_singlepage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 NOT NULL COMMENT '标题',
  `author` varchar(45) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '作者',
  `keyword` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '关键词',
  `description` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '描述',
  `cover` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '封面',
  `content` text CHARACTER SET utf8mb4 COMMENT '内容',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_singlepage`
--

LOCK TABLES `cms_singlepage` WRITE;
/*!40000 ALTER TABLE `cms_singlepage` DISABLE KEYS */;
INSERT INTO `cms_singlepage` VALUES (5,'关于我们','关于我们','关于我们','关于我们',NULL,'<p>关于我们</p>','2017-09-25 14:32:03','2017-09-25 14:32:03'),(6,'帮助说明','帮助说明','帮助说明','帮助说明',NULL,'<p>帮助说明帮助说明帮助说明</p>','2017-10-17 14:36:34','2017-10-17 14:36:34');
/*!40000 ALTER TABLE `cms_singlepage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `target_id` int(10) unsigned NOT NULL,
  `target_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `prase` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_count` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,1,'blog',1,'不是说你想做好就能做好，但是不想做好一定做不好',0,1,0,'2017-11-23 10:16:57','2017-11-23 10:16:57'),(2,2,'blog',1,'我写的内容你都看得懂不',0,1,5,'2017-12-13 04:12:59','2017-12-13 07:42:24'),(3,2,'blog',1,'走吧走吧',0,1,1,'2017-12-13 04:36:29','2017-12-13 07:44:24'),(4,2,'blog',1,'走吧  走吧',0,0,0,'2017-12-13 04:36:41','2017-12-13 04:36:41'),(5,2,'comment',1,'有时候你都不知道自己在说什么',0,1,0,'2017-12-13 07:21:55','2017-12-13 07:21:55'),(6,2,'comment',1,'看不看得懂关系不大',0,0,0,'2017-12-13 07:42:24','2017-12-13 07:42:24'),(7,2,'comment',1,'看不看得懂关系不大',0,0,0,'2017-12-13 07:42:24','2017-12-13 07:42:24'),(8,2,'comment',1,'看不看得懂关系不大',0,0,0,'2017-12-13 07:42:24','2017-12-13 07:42:24'),(9,2,'comment',1,'看不看得懂关系不大',0,0,0,'2017-12-13 07:42:24','2017-12-13 07:42:24'),(10,2,'blog',1,'你以为我说的不对是吧',0,0,0,'2017-12-13 07:44:14','2017-12-13 07:44:14'),(11,3,'comment',1,'真好玩',0,0,0,'2017-12-13 07:44:24','2017-12-13 07:44:24'),(12,8,'blog',3,'y知说佬什么',0,1,0,'2018-01-03 17:08:31','2018-01-03 17:08:31'),(13,8,'blog',3,'真是个好玩的事情',0,0,0,'2018-01-03 17:09:41','2018-01-03 17:09:41'),(14,8,'blog',3,'心胆俱裂 new style',0,0,0,'2018-01-03 17:15:22','2018-01-03 17:15:22'),(15,7,'blog',3,'SB',0,1,2,'2018-01-03 17:28:05','2018-01-03 17:47:28'),(16,7,'blog',3,'FHW',0,0,0,'2018-01-03 17:28:36','2018-01-03 17:28:36'),(17,15,'comment',3,'我真是',0,0,0,'2018-01-03 17:45:53','2018-01-03 17:45:53'),(18,15,'comment',3,'真是好玩',0,0,0,'2018-01-03 17:47:28','2018-01-03 17:47:28'),(19,9,'blog',1,'什么东西',0,1,0,'2018-01-20 13:49:11','2018-01-20 13:49:11'),(20,10,'blog',1,'marry',0,0,1,'2018-01-20 13:51:51','2018-01-20 13:52:03'),(21,20,'comment',1,'hello boy',0,0,0,'2018-01-20 13:52:03','2018-01-20 13:52:03');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `im_blacklist`
--

DROP TABLE IF EXISTS `im_blacklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `im_blacklist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `friend_user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `im_blacklist`
--

LOCK TABLES `im_blacklist` WRITE;
/*!40000 ALTER TABLE `im_blacklist` DISABLE KEYS */;
/*!40000 ALTER TABLE `im_blacklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `im_friends`
--

DROP TABLE IF EXISTS `im_friends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `im_friends` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `friend_user_id` int(10) unsigned NOT NULL,
  `alias_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_one_way_friend` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `im_friends`
--

LOCK TABLES `im_friends` WRITE;
/*!40000 ALTER TABLE `im_friends` DISABLE KEYS */;
INSERT INTO `im_friends` VALUES (1,2,1,NULL,1,1,'2018-01-07 13:48:37','2018-01-08 12:38:48'),(2,1,2,NULL,1,1,'2018-01-10 13:20:55','2018-01-10 14:15:53');
/*!40000 ALTER TABLE `im_friends` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `im_message_logs`
--

DROP TABLE IF EXISTS `im_message_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `im_message_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` int(10) unsigned NOT NULL,
  `receiver_id` int(10) unsigned NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `send_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `msg_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ext` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `im_message_logs`
--

LOCK TABLES `im_message_logs` WRITE;
/*!40000 ALTER TABLE `im_message_logs` DISABLE KEYS */;
INSERT INTO `im_message_logs` VALUES (1,2,1,'{\"id\":\"412419138657456624\",\"type\":\"chat\",\"from\":\"sherry2\",\"to\":\"sherry\",\"data\":\"not found\",\"ext\":{\"weichat\":{\"originType\":\"webim\"}},\"error\":false,\"errorText\":\"\",\"errorCode\":\"\"}','1513588613','412419138657456624','chat','','2017-12-18 09:16:53','2017-12-18 09:16:53'),(2,2,1,'{\"id\":\"412422803006947824\",\"type\":\"chat\",\"from\":\"sherry2\",\"to\":\"sherry\",\"data\":\"pdf\",\"ext\":{\"weichat\":{\"originType\":\"webim\"}},\"error\":false,\"errorText\":\"\",\"errorCode\":\"\"}','1513589467','412422803006947824','chat','','2017-12-18 09:31:07','2017-12-18 09:31:07'),(3,2,1,'{\"id\":\"412423177671541220\",\"type\":\"chat\",\"from\":\"sherry2\",\"to\":\"sherry\",\"data\":\"p j waft aisle\",\"ext\":{\"weichat\":{\"originType\":\"webim\"}},\"error\":false,\"errorText\":\"\",\"errorCode\":\"\"}','1513589554','412423177671541220','chat','','2017-12-18 09:32:34','2017-12-18 09:32:34'),(4,2,1,'{\"id\":\"412423494152749564\",\"type\":\"chat\",\"from\":\"sherry2\",\"to\":\"sherry\",\"data\":\"real data\",\"ext\":{\"weichat\":{\"originType\":\"webim\"}},\"error\":false,\"errorText\":\"\",\"errorCode\":\"\"}','1513589627','412423494152749564','chat','','2017-12-18 09:33:47','2017-12-18 09:33:47'),(5,2,1,'{\"id\":\"412424197977932284\",\"type\":\"txt\",\"from\":\"sherry2\",\"to\":\"sherry\",\"data\":\"not all the same\",\"ext\":{\"weichat\":{\"originType\":\"webim\"}},\"error\":false,\"errorText\":\"\",\"errorCode\":\"\"}','1513589791','412424197977932284','txt','','2017-12-18 09:36:31','2017-12-18 09:36:31'),(6,1,2,'{\"id\":\"412426192575005160\",\"type\":\"txt\",\"from\":\"sherry\",\"to\":\"sherry2\",\"data\":\"\\u771f\\u597d\\u73a9\",\"ext\":{\"weichat\":{\"originType\":\"webim\"}},\"error\":false,\"errorText\":\"\",\"errorCode\":\"\"}','1513590256','412426192575005160','txt','','2017-12-18 09:44:16','2017-12-18 09:44:16'),(7,1,2,'{\"id\":\"412426479444427240\",\"type\":\"txt\",\"from\":\"sherry\",\"to\":\"sherry2\",\"data\":\"\\u795e\\u63d0\\u793a\",\"ext\":{\"weichat\":{\"originType\":\"webim\"}},\"error\":false,\"errorText\":\"\",\"errorCode\":\"\"}','1513590322','412426479444427240','txt','','2017-12-18 09:45:22','2017-12-18 09:45:22'),(8,1,2,'{\"id\":\"412426650555253236\",\"type\":\"txt\",\"from\":\"sherry\",\"to\":\"sherry2\",\"data\":\"\\u6709\\u4ec0\\u4e48\\u6bd4\\u6d4b\\u8bd5\\u8fd9\\u4e2a\\u66f4\\u597d\\u73a9\\u5462\",\"ext\":{\"weichat\":{\"originType\":\"webim\"}},\"error\":false,\"errorText\":\"\",\"errorCode\":\"\"}','1513590362','412426650555253236','txt','','2017-12-18 09:46:02','2017-12-18 09:46:02'),(9,2,1,'{\"id\":\"412426699213373948\",\"type\":\"txt\",\"from\":\"sherry2\",\"to\":\"sherry\",\"data\":\"vfb yi j thnn imya p wh\",\"ext\":{\"weichat\":{\"originType\":\"webim\"}},\"error\":false,\"errorText\":\"\",\"errorCode\":\"\"}','1513590374','412426699213373948','txt','','2017-12-18 09:46:14','2017-12-18 09:46:14'),(10,2,1,'{\"id\":\"412432104463270380\",\"type\":\"txt\",\"from\":\"sherry2\",\"to\":\"sherry\",\"data\":\"p j waft aisle\",\"ext\":{\"weichat\":{\"originType\":\"webim\"}},\"error\":false,\"errorText\":\"\",\"errorCode\":\"\"}','1513591632','412432104463270380','txt','','2017-12-18 10:07:12','2017-12-18 10:07:12'),(11,2,1,'{\"id\":\"412432151674356204\",\"type\":\"image\",\"from\":\"sherry2\",\"to\":\"sherry\",\"url\":\"https:\\/\\/a1.easemob.com\\/1174171106115681\\/demoim\\/chatfiles\\/3dbaba40-e3db-11e7-aaa0-9d5fcd8ac15b\",\"secret\":\"Pbq6SuPbEeePkOuLCC5XdBoSiJdBKp6lriqnFkD0m0sEzmgX\",\"filename\":\"guide.1.png\",\"file_length\":\"\",\"width\":0,\"height\":0,\"filetype\":\"\",\"accessToken\":\"YWMtK9Fn3uPMEeeZMT-vc0DZiepqgnDC2RHnr_mbNS3Dl1V3JnLAxTAR54iaJ5txDlR6AwMAAAFgaLTYrgBPGgCtO5iuh43G_LytuZiRRCryUovTJGaDUqkgXCSZW9OVzQ\",\"ext\":{\"weichat\":{\"originType\":\"webim\"}},\"error\":false,\"errorText\":\"\",\"errorCode\":\"\"}','1513591643','412432151674356204','image','','2017-12-18 10:07:23','2017-12-18 10:07:23'),(12,2,1,'{\"id\":\"412435674424148460\",\"type\":\"image\",\"from\":\"sherry2\",\"to\":\"sherry\",\"url\":\"https:\\/\\/a1.easemob.com\\/1174171106115681\\/demoim\\/chatfiles\\/2699f130-e3dd-11e7-9946-c9c8d6722bdb\",\"secret\":\"JpnxOuPdEeeGK_0V7W6wJi4uxRbZsFrqX-Sdcbht66KQ9CdG\",\"filename\":\"guide.1.png\",\"file_length\":\"\",\"width\":0,\"height\":0,\"filetype\":\"\",\"accessToken\":\"YWMtK9Fn3uPMEeeZMT-vc0DZiepqgnDC2RHnr_mbNS3Dl1V3JnLAxTAR54iaJ5txDlR6AwMAAAFgaLTYrgBPGgCtO5iuh43G_LytuZiRRCryUovTJGaDUqkgXCSZW9OVzQ\",\"ext\":{\"weichat\":{\"originType\":\"webim\"}},\"error\":false,\"errorText\":\"\",\"errorCode\":\"\"}','1513592463','412435674424148460','image','','2017-12-18 10:21:03','2017-12-18 10:21:03'),(13,2,1,'{\"id\":\"412436129208336880\",\"type\":\"image\",\"from\":\"sherry2\",\"to\":\"sherry\",\"url\":\"https:\\/\\/a1.easemob.com\\/1174171106115681\\/demoim\\/chatfiles\\/65be5720-e3dd-11e7-b3ff-6f965f40b883\",\"secret\":\"Zb5XKuPdEeeBua1-dOT4CYqgoWe3IGMxNVBbJfN1WJkU1hig\",\"filename\":\"guide.1.png\",\"file_length\":\"\",\"width\":0,\"height\":0,\"filetype\":\"\",\"accessToken\":\"YWMtK9Fn3uPMEeeZMT-vc0DZiepqgnDC2RHnr_mbNS3Dl1V3JnLAxTAR54iaJ5txDlR6AwMAAAFgaLTYrgBPGgCtO5iuh43G_LytuZiRRCryUovTJGaDUqkgXCSZW9OVzQ\",\"ext\":{\"weichat\":{\"originType\":\"webim\"}},\"error\":false,\"errorText\":\"\",\"errorCode\":\"\"}','1513592569','412436129208336880','image','','2017-12-18 10:22:49','2017-12-18 10:22:49'),(31,0,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\\u8bf7\\u6c42\\u52a0\\u60a8\\u4e3a\\u597d\\u53cb\\uff0c\\u662f\\u5426\\u540c\\u610f?\"}','1515332917','1','addfriend','','2018-01-07 13:48:37','2018-01-07 13:48:37'),(32,1,2,'{\"data\":\"\\u6211\\u4eec\\u5df2\\u7ecf\\u662f\\u597d\\u53cb\\u4e86\\uff0c\\u5feb\\u6765\\u8ddf\\u6211\\u804a\\u5929\\u5427!\"}','1515339084','0','txt','','2018-01-07 15:31:24','2018-01-07 15:31:24'),(33,0,2,'{\"data\":\"\\u6211\\u4eec\\u5df2\\u7ecf\\u662f\\u597d\\u53cb\\u4e86\\uff0c\\u5feb\\u6765\\u8ddf\\u6211\\u804a\\u5929\\u5427!\"}','1515339152','0','txt','','2018-01-07 15:32:32','2018-01-07 15:32:32'),(34,0,2,'{\"data\":\"\\u6211\\u4eec\\u5df2\\u7ecf\\u662f\\u597d\\u53cb\\u4e86\\uff0c\\u5feb\\u6765\\u8ddf\\u6211\\u804a\\u5929\\u5427!\"}','1515339202','0','txt','','2018-01-07 15:33:22','2018-01-07 15:33:22'),(35,0,2,'{\"data\":\"\\u6211\\u4eec\\u5df2\\u7ecf\\u662f\\u597d\\u53cb\\u4e86\\uff0c\\u5feb\\u6765\\u8ddf\\u6211\\u804a\\u5929\\u5427!\"}','1515339221','0','txt','','2018-01-07 15:33:41','2018-01-07 15:33:41'),(36,1,2,'{\"data\":\"\\u6211\\u4eec\\u5df2\\u7ecf\\u662f\\u597d\\u53cb\\u4e86\\uff0c\\u5feb\\u6765\\u8ddf\\u6211\\u804a\\u5929\\u5427!\"}','1515339734','0','txt','','2018-01-07 15:42:14','2018-01-07 15:42:14'),(37,1,2,'{\"data\":\"\\u6211\\u4eec\\u5df2\\u7ecf\\u662f\\u597d\\u53cb\\u4e86\\uff0c\\u5feb\\u6765\\u8ddf\\u6211\\u804a\\u5929\\u5427!\"}','1515415129','0','txt','','2018-01-08 12:38:49','2018-01-08 12:38:49'),(38,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515416160','11','invite_bigsmall','','2018-01-08 12:56:00','2018-01-08 12:56:00'),(39,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515416338','12','invite_bigsmall','','2018-01-08 12:58:58','2018-01-08 12:58:58'),(40,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515416615','14','invite_bigsmall','','2018-01-08 13:03:35','2018-01-08 13:03:35'),(41,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515416638','15','invite_bigsmall','','2018-01-08 13:03:58','2018-01-08 13:03:58'),(42,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515419477','16','invite_bigsmall','','2018-01-08 13:51:17','2018-01-08 13:51:17'),(43,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515419506','17','invite_bigsmall','','2018-01-08 13:51:46','2018-01-08 13:51:46'),(44,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515419540','18','invite_bigsmall','','2018-01-08 13:52:20','2018-01-08 13:52:20'),(45,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515589778','19','invite_bigsmall','','2018-01-10 13:09:38','2018-01-10 13:09:38'),(46,2,1,'{\"data\":\"\\u597d\\u5403\\u7684\\u86cb\\u7cd5\",\"type\":\"txt\",\"to\":\"15986633874\",\"from\":\"15986633871\",\"id\":\"421014234596377072\"}','1515589815','421014234596377072','txt','','2018-01-10 13:10:15','2018-01-10 13:10:15'),(47,2,1,'{\"data\":\"\\u8fd9\\u4e0d\\u662f\\u771f\\u7684\",\"type\":\"txt\",\"to\":\"15986633874\",\"from\":\"15986633871\",\"id\":\"421014322739675632\"}','1515589835','421014322739675632','txt','','2018-01-10 13:10:35','2018-01-10 13:10:35'),(48,2,1,'{\"data\":\"\\u8fd9\\u771f\\u7684\\u4e0d\\u9519\",\"type\":\"txt\",\"to\":\"15986633874\",\"from\":\"15986633871\",\"id\":\"421014481519247848\"}','1515589872','421014481519247848','txt','','2018-01-10 13:11:12','2018-01-10 13:11:12'),(49,2,1,'{\"data\":\"\\u53ef\\u4ee5\\u53ef\\u4ee5\",\"type\":\"txt\",\"to\":\"15986633874\",\"from\":\"15986633871\",\"id\":\"421014518684975592\"}','1515589881','421014518684975592','txt','','2018-01-10 13:11:21','2018-01-10 13:11:21'),(50,2,1,'{\"data\":\"\\u5c0f\\u72d7 \\u53eb\\u53eb\",\"type\":\"txt\",\"to\":\"15986633874\",\"from\":\"15986633871\",\"id\":\"421014662537019880\"}','1515589915','421014662537019880','txt','','2018-01-10 13:11:55','2018-01-10 13:11:55'),(51,2,1,'{\"data\":\"66666\",\"type\":\"txt\",\"to\":\"15986633874\",\"from\":\"15986633871\",\"id\":\"421014775787422184\"}','1515589941','421014775787422184','txt','','2018-01-10 13:12:21','2018-01-10 13:12:21'),(52,1,2,'{\"data\":\"6666\",\"type\":\"txt\",\"to\":\"15986633871\",\"from\":\"15986633874\",\"id\":\"421014810486899176\"}','1515589949','421014810486899176','txt','','2018-01-10 13:12:29','2018-01-10 13:12:29'),(53,1,2,'{\"data\":\"99999\",\"type\":\"txt\",\"to\":\"15986633871\",\"from\":\"15986633874\",\"id\":\"421014970197606888\"}','1515589986','421014970197606888','txt','','2018-01-10 13:13:06','2018-01-10 13:13:06'),(54,0,2,'{\"data\":\"\\u65e0\\u6094\\u8bf7\\u6c42\\u52a0\\u60a8\\u4e3a\\u597d\\u53cb\\uff0c\\u662f\\u5426\\u540c\\u610f?\"}','1515590455','2','addfriend','','2018-01-10 13:20:55','2018-01-10 13:20:55'),(55,0,2,'{\"data\":\"\\u65e0\\u6094\\u8bf7\\u6c42\\u52a0\\u60a8\\u4e3a\\u597d\\u53cb\\uff0c\\u662f\\u5426\\u540c\\u610f?\"}','1515590651','2','addfriend','','2018-01-10 13:24:11','2018-01-10 13:24:11'),(56,0,2,'{\"data\":\"\\u65e0\\u6094\\u8bf7\\u6c42\\u52a0\\u60a8\\u4e3a\\u597d\\u53cb\\uff0c\\u662f\\u5426\\u540c\\u610f?\"}','1515593255','2','addfriend','','2018-01-10 14:07:35','2018-01-10 14:07:35'),(57,0,2,'{\"data\":\"\\u65e0\\u6094\\u8bf7\\u6c42\\u52a0\\u60a8\\u4e3a\\u597d\\u53cb\\uff0c\\u662f\\u5426\\u540c\\u610f?\"}','1515593307','2','addfriend','','2018-01-10 14:08:27','2018-01-10 14:08:27'),(58,0,2,'{\"data\":\"\\u65e0\\u6094\\u8bf7\\u6c42\\u52a0\\u60a8\\u4e3a\\u597d\\u53cb\\uff0c\\u662f\\u5426\\u540c\\u610f?\"}','1515593587','2','addfriend','','2018-01-10 14:13:07','2018-01-10 14:13:07'),(59,0,2,'{\"data\":\"\\u65e0\\u6094\\u8bf7\\u6c42\\u52a0\\u60a8\\u4e3a\\u597d\\u53cb\\uff0c\\u662f\\u5426\\u540c\\u610f?\"}','1515593600','2','addfriend','','2018-01-10 14:13:20','2018-01-10 14:13:20'),(60,0,2,'{\"data\":\"\\u65e0\\u6094\\u8bf7\\u6c42\\u52a0\\u60a8\\u4e3a\\u597d\\u53cb\\uff0c\\u662f\\u5426\\u540c\\u610f?\"}','1515593703','2','addfriend','','2018-01-10 14:15:03','2018-01-10 14:15:03'),(61,2,1,'{\"data\":\"\\u6211\\u4eec\\u5df2\\u7ecf\\u662f\\u597d\\u53cb\\u4e86\\uff0c\\u5feb\\u6765\\u8ddf\\u6211\\u804a\\u5929\\u5427!\"}','1515593753','0','txt','','2018-01-10 14:15:53','2018-01-10 14:15:53'),(62,1,2,'{\"data\":\"\\u65e0\\u6094\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515594312','20','invite_bigsmall','','2018-01-10 14:25:12','2018-01-10 14:25:12'),(63,1,2,'{\"data\":\"\\u65e0\\u6094\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515594448','21','invite_bigsmall','','2018-01-10 14:27:28','2018-01-10 14:27:28'),(64,1,2,'{\"data\":\"\\u65e0\\u6094\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515594653','22','invite_bigsmall','','2018-01-10 14:30:53','2018-01-10 14:30:53'),(65,1,2,'{\"data\":\"\\u65e0\\u6094\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515594795','23','invite_bigsmall','','2018-01-10 14:33:15','2018-01-10 14:33:15'),(66,1,2,'{\"data\":\"\\u65e0\\u6094\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515595060','24','invite_bigsmall','','2018-01-10 14:37:40','2018-01-10 14:37:40'),(67,1,2,'{\"data\":\"\\u65e0\\u6094\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515595129','25','invite_bigsmall','','2018-01-10 14:38:49','2018-01-10 14:38:49'),(68,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515597726','26','invite_bigsmall','','2018-01-10 15:22:06','2018-01-10 15:22:06'),(69,2,1,'{\"data\":\"\\u53ef\\u4ee5\\u804a\\u5929\",\"type\":\"txt\",\"to\":\"15986633874\",\"from\":\"15986633871\",\"id\":\"421048261999067632\"}','1515597738','421048261999067632','txt','','2018-01-10 15:22:18','2018-01-10 15:22:18'),(70,1,2,'{\"data\":\"\\u65e0\\u6094\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515738573','27','invite_bigsmall','','2018-01-12 06:29:33','2018-01-12 06:29:33'),(71,1,2,'{\"data\":\"\\u65e0\\u6094\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515738612','28','invite_bigsmall','','2018-01-12 06:30:12','2018-01-12 06:30:12'),(72,1,2,'{\"data\":\"\\u65e0\\u6094\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515738626','29','invite_bigsmall','','2018-01-12 06:30:26','2018-01-12 06:30:26'),(73,1,2,'{\"data\":\"\\u65e0\\u6094\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515738684','30','invite_bigsmall','','2018-01-12 06:31:24','2018-01-12 06:31:24'),(74,1,2,'{\"data\":\"\\u65e0\\u6094\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515740186','31','invite_bigsmall','','2018-01-12 06:56:26','2018-01-12 06:56:26'),(75,1,2,'{\"data\":\"\\u65e0\\u6094\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515741085','32','invite_bigsmall','','2018-01-12 07:11:25','2018-01-12 07:11:25'),(76,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515858640','33','invite_bigsmall','','2018-01-13 15:50:40','2018-01-13 15:50:40'),(77,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515858659','34','invite_bigsmall','','2018-01-13 15:50:59','2018-01-13 15:50:59'),(78,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515858672','35','invite_bigsmall','','2018-01-13 15:51:12','2018-01-13 15:51:12'),(79,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\\u5411\\u4f60\\u53d1\\u8d77\\u4e86\\u6bd4\\u5927\\u5c0f\\u9080\\u8bf7!\\u662f\\u5426\\u63a5\\u53d7\\u9080\\u8bf7\\uff1f\"}','1515858685','36','invite_bigsmall','','2018-01-13 15:51:25','2018-01-13 15:51:25'),(80,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516019021','37','invite_bigsmall','','2018-01-15 12:23:41','2018-01-15 12:23:41'),(81,1,2,'{\"data\":\"asdfasdfs\",\"type\":\"txt\",\"to\":\"15986633871\",\"from\":\"15986633874\",\"id\":\"423285935640150508\"}','1516118737','423285935640150508','txt','','2018-01-16 16:05:37','2018-01-16 16:05:37'),(82,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516118756','38','invite_bigsmall','','2018-01-16 16:05:56','2018-01-16 16:05:56'),(83,1,2,'{\"data\":\"\\u65e0\\u6094\"}','1516118781','39','invite_bigsmall','','2018-01-16 16:06:21','2018-01-16 16:06:21'),(84,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516119279','40','invite_bigsmall','','2018-01-16 16:14:39','2018-01-16 16:14:39'),(85,1,2,'{\"data\":\"\\u65e0\\u6094\"}','1516119304','41','invite_bigsmall','','2018-01-16 16:15:04','2018-01-16 16:15:04'),(86,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516625505','42','invite_bigsmall','','2018-01-22 12:51:45','2018-01-22 12:51:45'),(87,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516625955','43','invite_bigsmall','','2018-01-22 12:59:15','2018-01-22 12:59:15'),(88,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516625986','44','invite_bigsmall','','2018-01-22 12:59:46','2018-01-22 12:59:46'),(89,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516626015','45','invite_bigsmall','','2018-01-22 13:00:15','2018-01-22 13:00:15'),(90,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516626231','46','invite_bigsmall','','2018-01-22 13:03:51','2018-01-22 13:03:51'),(91,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516626265','47','invite_bigsmall','','2018-01-22 13:04:25','2018-01-22 13:04:25'),(92,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516626298','48','invite_bigsmall','','2018-01-22 13:04:58','2018-01-22 13:04:58'),(93,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516626322','49','invite_bigsmall','','2018-01-22 13:05:22','2018-01-22 13:05:22'),(94,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516626329','50','invite_bigsmall','','2018-01-22 13:05:29','2018-01-22 13:05:29'),(95,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516626374','51','invite_bigsmall','','2018-01-22 13:06:14','2018-01-22 13:06:14'),(96,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516626461','52','invite_bigsmall','','2018-01-22 13:07:41','2018-01-22 13:07:41'),(97,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516626668','53','invite_bigsmall','','2018-01-22 13:11:08','2018-01-22 13:11:08'),(98,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516626712','54','invite_bigsmall','','2018-01-22 13:11:52','2018-01-22 13:11:52'),(99,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516626760','55','invite_bigsmall','','2018-01-22 13:12:40','2018-01-22 13:12:40'),(100,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516626806','56','invite_bigsmall','','2018-01-22 13:13:26','2018-01-22 13:13:26'),(101,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516626849','57','invite_bigsmall','','2018-01-22 13:14:09','2018-01-22 13:14:09'),(102,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516626909','58','invite_bigsmall','','2018-01-22 13:15:09','2018-01-22 13:15:09'),(103,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516626990','59','invite_bigsmall','','2018-01-22 13:16:30','2018-01-22 13:16:30'),(104,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516627128','60','invite_bigsmall','','2018-01-22 13:18:48','2018-01-22 13:18:48'),(105,2,1,'{\"data\":\"\\u5c0f\\u5c0f\\u660e\"}','1516627190','61','invite_bigsmall','','2018-01-22 13:19:50','2018-01-22 13:19:50');
/*!40000 ALTER TABLE `im_message_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,1,'hello world !!!!',NULL,'2017-10-23 08:33:17','2017-10-23 08:33:17'),(2,1,'i am fine !','2017-10-23 12:00:43','2017-10-23 08:36:53','2017-10-23 12:00:43');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `microblog`
--

DROP TABLE IF EXISTS `microblog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `microblog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cate_id` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `content` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extra` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `prase` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_count` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `microblog`
--

LOCK TABLES `microblog` WRITE;
/*!40000 ALTER TABLE `microblog` DISABLE KEYS */;
INSERT INTO `microblog` VALUES (1,1,1,'','{\"type\":\"image\",\"data\":[\"\\/uploads\\/ueditor\\/php\\/upload\\/image\\/20171123\\/1511419519136623.jpeg\",\"\\/uploads\\/ueditor\\/php\\/upload\\/image\\/20171123\\/1511419519319672.jpeg\"]}',41,1,1,'2017-11-23 06:55:59','2017-12-13 08:47:27'),(2,1,1,'这是什么东西   好看吗','{\"type\":\"image\",\"data\":[\"\\/uploads\\/ueditor\\/php\\/upload\\/image\\/20171212\\/1513070047709679.png\"]}',12,1,4,'2017-12-12 09:14:07','2017-12-13 07:44:14'),(3,1,1,'不是所有的事情都有道理','{\"type\":\"image\",\"data\":null}',1,0,0,'2017-12-19 12:31:15','2017-12-19 12:31:17'),(4,3,2,'这是什么东西','{\"type\":\"image\",\"data\":[\"\\/uploads\\/ueditor\\/php\\/upload\\/image\\/20180102\\/1514866680515186.jpeg\",\"\\/uploads\\/ueditor\\/php\\/upload\\/image\\/20180102\\/1514866680525013.jpeg\"]}',0,0,0,'2018-01-02 04:18:01','2018-01-02 04:18:01'),(5,3,2,'这是什么东西','{\"type\":\"image\",\"data\":[\"\\/uploads\\/ueditor\\/php\\/upload\\/image\\/20180102\\/1514866680515186.jpeg\",\"\\/uploads\\/ueditor\\/php\\/upload\\/image\\/20180102\\/1514866680525013.jpeg\"]}',0,0,0,'2018-01-02 04:18:46','2018-01-02 04:18:46'),(6,3,2,'这是什么东西','{\"type\":\"image\",\"data\":[\"\\/uploads\\/ueditor\\/php\\/upload\\/image\\/20180102\\/1514866680515186.jpeg\",\"\\/uploads\\/ueditor\\/php\\/upload\\/image\\/20180102\\/1514866680525013.jpeg\"]}',0,0,0,'2018-01-02 04:18:58','2018-01-02 04:18:58'),(7,3,1,'海上升明月','{\"type\":\"image\",\"data\":[\"\\/uploads\\/ueditor\\/php\\/upload\\/image\\/20180102\\/1514866793210884.jpeg\"]}',1,1,2,'2018-01-02 04:19:53','2018-01-03 17:28:36'),(8,3,1,'分享什么才可以','{\"type\":\"image\",\"data\":[\"\\/uploads\\/ueditor\\/php\\/upload\\/image\\/20180102\\/1514866850278438.jpeg\"]}',1,1,3,'2018-01-02 04:20:50','2018-01-03 17:15:22'),(9,1,1,'What happend','{\"type\":\"image\",\"data\":[null]}',1,0,1,'2018-01-20 13:46:11','2018-01-20 13:49:11'),(10,1,2,'SomeThing worng','{\"type\":\"image\",\"data\":[\"\\/uploads\\/ueditor\\/php\\/upload\\/image\\/20180120\\/1516456301152594.png\"]}',1,0,1,'2018-01-20 13:51:41','2018-01-20 13:51:51'),(11,1,1,'真的么','{\"type\":\"image\",\"data\":[\"\\/uploads\\/ueditor\\/php\\/upload\\/image\\/20180122\\/1516621930136410.jpeg\"]}',1,0,0,'2018-01-22 11:52:11','2018-01-22 11:52:12');
/*!40000 ALTER TABLE `microblog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (34,'2014_10_12_000000_create_users_table',1),(35,'2014_10_12_100000_create_password_resets_table',1),(36,'2016_01_04_173148_create_admin_tables',1),(37,'2017_07_17_040159_create_config_table',1),(38,'2017_07_17_040159_create_messages_table',1),(39,'2017_09_25_100502_create_jobs_table',1),(40,'2017_09_25_101101_create_failed_jobs_table',1),(41,'2017_09_25_104030_create_notifications_table',1),(42,'2017_09_25_123914_createCmsTable',1),(43,'2017_09_27_151243_create_adm_system',2),(44,'2017_10_19_110705_create_messages_table',3),(45,'2016_06_01_000001_create_oauth_auth_codes_table',4),(46,'2016_06_01_000002_create_oauth_access_tokens_table',4),(47,'2016_06_01_000003_create_oauth_refresh_tokens_table',4),(48,'2016_06_01_000004_create_oauth_clients_table',4),(49,'2016_06_01_000005_create_oauth_personal_access_clients_table',4),(50,'2017_11_13_190429_add_api_token_to_users',5),(51,'2017_11_15_100305_create_big_small_table',6),(53,'2017_11_23_092605_create_microblog',7),(54,'2017_12_13_111502_create_praise_log_table',8),(55,'2017_12_13_173206_create_stolen_log_table',9),(56,'2017_12_14_150556_create_im_friends_table',10),(57,'2017_12_14_152229_create_im_message_logs_table',11),(58,'2017_12_26_194030_modify_user',12),(59,'2018_01_03_231023_focus_table',13),(60,'2018_01_06_224505_adm_posts_praise_log',14),(61,'2018_01_14_235556_modify_user_table',14),(62,'2018_01_24_002200_charge_log',15);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` int(10) unsigned NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_id_notifiable_type_index` (`notifiable_id`,`notifiable_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_access_tokens`
--

LOCK TABLES `oauth_access_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_auth_codes`
--

LOCK TABLES `oauth_auth_codes` WRITE;
/*!40000 ALTER TABLE `oauth_auth_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_clients`
--

LOCK TABLES `oauth_clients` WRITE;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
INSERT INTO `oauth_clients` VALUES (1,NULL,'Laravel Personal Access Client','Iuufcf2ClEp0N0V5GDV5N0FH3HKit25PlopLYtLa','http://localhost',1,0,0,'2017-11-12 07:41:40','2017-11-12 07:41:40'),(2,NULL,'Laravel Password Grant Client','kPn3lh3xb13AXtiRgib4l7IAk8UsVaBYEZXU1Rp6','http://localhost',0,1,0,'2017-11-12 07:41:40','2017-11-12 07:41:40'),(3,1,'cin','K7oKqfMKQlEnzgSkgRtyMjEORQEM2A33zTtHrWpY','http://localhost:8000/auth/callback',0,0,0,'2017-11-12 08:11:17','2017-11-12 08:11:17');
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_personal_access_clients_client_id_index` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_personal_access_clients`
--

LOCK TABLES `oauth_personal_access_clients` WRITE;
/*!40000 ALTER TABLE `oauth_personal_access_clients` DISABLE KEYS */;
INSERT INTO `oauth_personal_access_clients` VALUES (1,1,'2017-11-12 07:41:40','2017-11-12 07:41:40');
/*!40000 ALTER TABLE `oauth_personal_access_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_refresh_tokens`
--

LOCK TABLES `oauth_refresh_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `praise_logs`
--

DROP TABLE IF EXISTS `praise_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `praise_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `praise_logs`
--

LOCK TABLES `praise_logs` WRITE;
/*!40000 ALTER TABLE `praise_logs` DISABLE KEYS */;
INSERT INTO `praise_logs` VALUES (4,1,'comment',2,'2017-12-13 06:45:26','2017-12-13 06:45:26'),(5,1,'comment',5,'2017-12-13 07:22:06','2017-12-13 07:22:06'),(6,1,'comment',3,'2017-12-13 07:46:52','2017-12-13 07:46:52'),(9,1,'microblog',2,'2017-12-13 08:47:11','2017-12-13 08:47:11'),(10,1,'comment',1,'2017-12-13 08:47:31','2017-12-13 08:47:31'),(11,1,'microblog',1,'2017-12-13 08:48:38','2017-12-13 08:48:38'),(12,1,'adv',2,'2017-12-13 09:16:58','2017-12-13 09:16:58'),(14,3,'microblog',8,'2018-01-03 17:21:54','2018-01-03 17:21:54'),(16,3,'comment',12,'2018-01-03 17:27:22','2018-01-03 17:27:22'),(19,3,'microblog',7,'2018-01-03 17:28:29','2018-01-03 17:28:29'),(20,3,'comment',15,'2018-01-03 17:45:59','2018-01-03 17:45:59'),(21,3,'adv',2,'2018-01-06 15:25:21','2018-01-06 15:25:21'),(22,1,'comment',19,'2018-01-20 13:51:02','2018-01-20 13:51:02');
/*!40000 ALTER TABLE `praise_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stolen_logs`
--

DROP TABLE IF EXISTS `stolen_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stolen_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `from_user_id` int(10) unsigned NOT NULL,
  `cash` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stolen_logs`
--

LOCK TABLES `stolen_logs` WRITE;
/*!40000 ALTER TABLE `stolen_logs` DISABLE KEYS */;
INSERT INTO `stolen_logs` VALUES (1,1,2,6446,'2018-01-15 12:27:23','2018-01-15 12:27:23');
/*!40000 ALTER TABLE `stolen_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_careers`
--

DROP TABLE IF EXISTS `sys_careers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_careers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_careers`
--

LOCK TABLES `sys_careers` WRITE;
/*!40000 ALTER TABLE `sys_careers` DISABLE KEYS */;
INSERT INTO `sys_careers` VALUES (1,'软件工程师',10,1,'2017-09-27 16:41:23','2017-09-27 16:41:23'),(2,'游戏',10,1,'2018-01-24 16:55:30','2018-01-24 16:55:30'),(3,'购物',10,1,'2018-01-24 16:55:36','2018-01-24 16:55:36'),(4,'聊天',10,1,'2018-01-24 16:55:44','2018-01-24 16:55:44'),(5,'咖啡',10,1,'2018-01-24 16:55:50','2018-01-24 16:55:50'),(6,'红酒',10,1,'2018-01-24 16:55:55','2018-01-24 16:55:55');
/*!40000 ALTER TABLE `sys_careers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_degrees`
--

DROP TABLE IF EXISTS `sys_degrees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_degrees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_degrees`
--

LOCK TABLES `sys_degrees` WRITE;
/*!40000 ALTER TABLE `sys_degrees` DISABLE KEYS */;
INSERT INTO `sys_degrees` VALUES (1,'大专',10,1,'2017-09-27 16:55:30','2017-09-27 16:55:30'),(2,'本科',10,1,'2017-09-27 16:55:39','2017-09-27 16:55:39'),(3,'硕士',10,1,'2017-09-27 16:55:44','2017-09-27 16:56:18'),(4,'博士',10,1,'2017-09-27 16:56:48','2017-09-27 16:56:56');
/*!40000 ALTER TABLE `sys_degrees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_bank`
--

DROP TABLE IF EXISTS `user_bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_bank` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_account` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_bank`
--

LOCK TABLES `user_bank` WRITE;
/*!40000 ALTER TABLE `user_bank` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_bank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_focus`
--

DROP TABLE IF EXISTS `user_focus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_focus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `focus_user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_focus`
--

LOCK TABLES `user_focus` WRITE;
/*!40000 ALTER TABLE `user_focus` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_focus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_withdraw`
--

DROP TABLE IF EXISTS `user_withdraw`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_withdraw` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `used` decimal(8,2) NOT NULL,
  `rate` int(10) unsigned NOT NULL DEFAULT '0',
  `cash` decimal(8,2) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `admin_id` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_withdraw`
--

LOCK TABLES `user_withdraw` WRITE;
/*!40000 ALTER TABLE `user_withdraw` DISABLE KEYS */;
INSERT INTO `user_withdraw` VALUES (1,2222.00,1,2222.00,1,0,0,NULL,NULL,'alipay','sherry24love@163.com','2018-01-13 16:19:45','2018-01-13 16:19:45');
/*!40000 ALTER TABLE `user_withdraw` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `mobile` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nickname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `birth_day` int(10) unsigned NOT NULL DEFAULT '0',
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `degree` smallint(5) unsigned NOT NULL DEFAULT '0',
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cash_recharge` decimal(8,2) NOT NULL DEFAULT '0.00',
  `cash_reward` decimal(8,2) NOT NULL DEFAULT '0.00',
  `cash_frozen` decimal(8,2) NOT NULL DEFAULT '0.00',
  `last_update` int(10) unsigned NOT NULL DEFAULT '0',
  `invite_code` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invite_by` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `register_ip` int(10) unsigned NOT NULL,
  `last_login_time` int(10) unsigned NOT NULL,
  `last_login_ip` int(10) unsigned NOT NULL,
  `delete_at` timestamp NULL DEFAULT NULL,
  `api_token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_big_small` int(10) unsigned NOT NULL,
  `lose_big_small` int(10) unsigned NOT NULL,
  `not_pay_big_small` int(10) unsigned NOT NULL,
  `lose_big_small_cash` int(10) unsigned NOT NULL,
  `not_pay_big_small_cash` int(10) unsigned NOT NULL,
  `alipay_account` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `mobile_UNIQUE` (`mobile`),
  UNIQUE KEY `users_api_token_unique` (`api_token`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'15986633874',NULL,'$2y$10$KSwbgUAcZb/FbT60OpsoAu4FiIYqS1WyZzpjiWrE.U.B101/gC7t6','kUDE975E7nF6LodIJfpj5lu66ISyqw3YljFkxbsSfYnjO5artOTqn62tbXd5','2018-01-06 15:37:04','2018-01-24 17:16:09','15986633874',NULL,'无悔',1,1516204800,NULL,1,'北京 东城区',NULL,100000.00,9999.99,0.00,1516814169,'TX2js4ml','',2130706433,1516621900,2130706433,NULL,'0311nK2GeIEG1G5p2bX3uaVOf9RG1R66MGfCQCM2Ex4ZDgs3QekGLG38wN86',20,11,0,657,0,''),(2,'15986633871',NULL,'$2y$10$a35MF8G.C2ZY56C83SMNHeR54kAXUAU55AC6J6Sg6ookEJzrI9Kki','yasDWMZsaOjOl673yVjdi8vnTO4UpY2GpbtnjJ1nXH9XNsJePWkeu3dkxHxD','2018-01-07 13:45:05','2018-01-12 06:26:57','15986633871',NULL,'小小明',0,0,'0',0,NULL,NULL,100000.00,8558.99,0.00,0,'8WaAsfuz','TX2js4ml',2130706433,1515738417,2130706433,NULL,'dz9pRL6NJYokrfKCjcsNBxJnC8DoViGKmlhy49H3xumystkxoHgw9gGGFzUv',20,9,0,615,0,'');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `view_logs`
--

DROP TABLE IF EXISTS `view_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `view_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `view_logs`
--

LOCK TABLES `view_logs` WRITE;
/*!40000 ALTER TABLE `view_logs` DISABLE KEYS */;
INSERT INTO `view_logs` VALUES (1,1,'microblog',2,'2017-12-13 03:29:41','2017-12-13 03:29:41'),(2,1,'microblog',1,'2017-12-13 08:47:27','2017-12-13 08:47:27'),(3,1,'microblog',3,'2017-12-19 12:31:17','2017-12-19 12:31:17'),(4,3,'microblog',8,'2018-01-03 17:13:40','2018-01-03 17:13:40'),(5,3,'microblog',7,'2018-01-03 17:27:58','2018-01-03 17:27:58'),(6,3,'adv',2,'2018-01-06 15:28:33','2018-01-06 15:28:33'),(7,1,'adv',2,'2018-01-07 13:33:47','2018-01-07 13:33:47'),(8,1,'adv',4,'2018-01-07 13:34:16','2018-01-07 13:34:16'),(9,1,'adv',6,'2018-01-07 13:34:25','2018-01-07 13:34:25'),(10,1,'adv',9,'2018-01-20 13:44:54','2018-01-20 13:44:54'),(11,1,'microblog',9,'2018-01-20 13:46:13','2018-01-20 13:46:13'),(12,1,'microblog',10,'2018-01-20 13:51:43','2018-01-20 13:51:43'),(13,1,'microblog',11,'2018-01-22 11:52:12','2018-01-22 11:52:12');
/*!40000 ALTER TABLE `view_logs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-01-25 15:45:59
