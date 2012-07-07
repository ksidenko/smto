-- MySQL dump 10.13  Distrib 5.1.54, for debian-linux-gnu (i686)
--
-- Host: sks.211.ru    Database: smto_new
-- ------------------------------------------------------
-- Server version	5.1.41-3ubuntu12.10

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
-- Table structure for table `amplitude`
--

DROP TABLE IF EXISTS `amplitude`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `amplitude` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `number` int(10) DEFAULT NULL,
  `machine_id` int(10) DEFAULT NULL,
  `value` int(10) NOT NULL,
  `type` enum('zero','idle_run') NOT NULL,
  `rec_type` enum('real','template') NOT NULL DEFAULT 'real',
  PRIMARY KEY (`id`),
  KEY `machine_id` (`machine_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `amplitude`
--

LOCK TABLES `amplitude` WRITE;
/*!40000 ALTER TABLE `amplitude` DISABLE KEYS */;
INSERT INTO `amplitude` VALUES (1,1,1,110,'zero','template'),(2,2,1,120,'zero','template'),(3,3,1,130,'zero','template'),(4,4,1,140,'zero','template'),(5,1,1,150,'idle_run','template'),(6,2,1,160,'idle_run','template'),(7,3,1,170,'idle_run','template'),(8,4,1,180,'idle_run','template'),(9,1,2,100,'zero','real'),(10,2,2,101,'zero','real'),(11,3,2,102,'zero','real'),(12,4,2,103,'zero','real'),(13,1,2,104,'idle_run','real'),(14,2,2,105,'idle_run','real'),(15,3,2,106,'idle_run','real'),(16,4,2,107,'idle_run','real'),(17,1,3,110,'zero','real'),(18,2,3,120,'zero','real'),(19,3,3,130,'zero','real'),(20,4,3,140,'zero','real'),(21,1,3,150,'idle_run','real'),(22,2,3,160,'idle_run','real'),(23,3,3,170,'idle_run','real'),(24,4,3,180,'idle_run','real'),(25,1,4,110,'zero','real');
/*!40000 ALTER TABLE `amplitude` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detector`
--

DROP TABLE IF EXISTS `detector`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detector` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `number` int(10) DEFAULT NULL,
  `machine_id` int(10) DEFAULT NULL,
  `status` int(10) NOT NULL,
  `type` enum('digit','analog') NOT NULL,
  `rec_type` enum('real','template') NOT NULL DEFAULT 'real',
  PRIMARY KEY (`id`),
  KEY `machine_id` (`machine_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detector`
--

LOCK TABLES `detector` WRITE;
/*!40000 ALTER TABLE `detector` DISABLE KEYS */;
INSERT INTO `detector` VALUES (1,1,1,1,'digit','template'),(2,2,1,1,'digit','template'),(3,3,1,0,'digit','template'),(4,4,1,0,'digit','template'),(5,1,1,0,'analog','template'),(6,2,1,0,'analog','template'),(7,3,1,1,'analog','template'),(8,4,1,1,'analog','template'),(9,1,2,1,'digit','real'),(10,2,2,1,'digit','real'),(11,3,2,0,'digit','real'),(12,4,2,0,'digit','real'),(13,1,2,1,'analog','real'),(14,2,2,1,'analog','real'),(15,3,2,0,'analog','real'),(16,4,2,0,'analog','real'),(17,1,3,1,'digit','real'),(18,2,3,1,'digit','real'),(19,3,3,1,'digit','real'),(20,4,3,1,'digit','real'),(21,1,3,1,'analog','real'),(22,2,3,1,'analog','real'),(23,3,3,1,'analog','real'),(24,4,3,1,'analog','real'),(25,1,4,1,'digit','real'),(26,2,4,1,'digit','real'),(27,3,4,0,'digit','real'),(28,4,4,0,'digit','real'),(29,1,4,0,'analog','real'),(30,2,4,0,'analog','real'),(31,3,4,1,'analog','real'),(32,4,4,1,'analog','real');
/*!40000 ALTER TABLE `detector` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_color`
--

DROP TABLE IF EXISTS `event_color`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_color` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` varchar(512) NOT NULL,
  `name` varchar(512) NOT NULL DEFAULT '',
  `color` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `code` (`code`(255))
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_color`
--

LOCK TABLES `event_color` WRITE;
/*!40000 ALTER TABLE `event_color` DISABLE KEYS */;
INSERT INTO `event_color` VALUES (1,'work','Работа','#0ef21a'),(2,'work_not_valid','Необоснованный простой','#f20c33'),(3,'work_valid','Обоснованный простой','#3b08f5');
/*!40000 ALTER TABLE `event_color` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fkey`
--

DROP TABLE IF EXISTS `fkey`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fkey` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `number` int(10) DEFAULT NULL,
  `machine_id` int(10) DEFAULT NULL,
  `code` varchar(128) NOT NULL,
  `color` varchar(128) NOT NULL,
  `type` enum('work','valid','not_valid') NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `rec_type` enum('real','template') NOT NULL DEFAULT 'real',
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`machine_id`,`number`),
  KEY `machine_id` (`machine_id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fkey`
--

LOCK TABLES `fkey` WRITE;
/*!40000 ALTER TABLE `fkey` DISABLE KEYS */;
INSERT INTO `fkey` VALUES (1,1,1,'F-1','#00FF00','work',1,'template'),(2,2,1,'F-2','#00FF00','work',1,'template'),(3,3,1,'F-3','#00FF00','work',1,'template'),(4,4,1,'F-4','#00FF00','valid',1,'template'),(5,5,1,'F-5','#00FF00','valid',1,'template'),(6,6,1,'F-6','#00FF00','valid',1,'template'),(7,7,1,'F-7','#00FF00','valid',1,'template'),(8,8,1,'F-8','#00FF00','not_valid',1,'template'),(9,9,1,'F-9','#00FF00','not_valid',1,'template'),(10,10,1,'F-10','#00FF00','not_valid',0,'template'),(11,11,1,'F-11','#00FF00','not_valid',1,'template'),(12,12,1,'F-12','#00FF00','not_valid',0,'template'),(13,13,1,'F-13','#00FF00','not_valid',0,'template'),(14,14,1,'F-14','#00FF00','work',1,'template'),(15,15,1,'F-15','#00FF00','work',1,'template'),(16,16,1,'F-16','#00FF00','work',1,'template'),(17,1,2,'F-1','#00FF00','work',1,'real'),(18,2,2,'F-2','#00FF00','work',1,'real'),(19,3,2,'F-3','#00FF00','work',1,'real'),(20,4,2,'F-4','#00FF00','valid',1,'real'),(21,5,2,'F-5','#00FF00','valid',1,'real'),(22,6,2,'F-6','#00FF00','valid',1,'real'),(23,7,2,'F-7','#00FF00','valid',1,'real'),(24,8,2,'F-8','#00FF00','not_valid',1,'real'),(25,9,2,'F-9','#00FF00','not_valid',1,'real'),(26,10,2,'F-10','#00FF00','not_valid',0,'real'),(27,11,2,'F-11','#00FF00','not_valid',1,'real'),(28,12,2,'F-12','#00FF00','not_valid',0,'real'),(29,13,2,'F-13','#00FF00','not_valid',0,'real'),(30,14,2,'F-14','#00FF00','work',1,'real'),(31,15,2,'F-15','#00FF00','work',1,'real'),(32,16,2,'F-16','#00FF00','work',1,'real'),(33,1,3,'F-1','#00FF00','work',1,'real'),(34,2,3,'F-2','#00FF00','work',1,'real'),(35,3,3,'F-3','#00FF00','work',1,'real'),(36,4,3,'F-4','#00FF00','valid',1,'real'),(37,5,3,'F-5','#00FF00','valid',1,'real'),(38,6,3,'F-6','#00FF00','valid',1,'real'),(39,7,3,'F-7','#00FF00','valid',1,'real'),(40,8,3,'F-8','#00FF00','not_valid',1,'real'),(41,9,3,'F-9','#00FF00','not_valid',1,'real'),(42,10,3,'F-10','#00FF00','not_valid',0,'real'),(43,11,3,'F-11','#00FF00','not_valid',1,'real'),(44,12,3,'F-12','#2200ff','not_valid',1,'real'),(45,13,3,'F-13','#00FF00','not_valid',0,'real'),(46,14,3,'F-14','#00FF00','work',1,'real'),(47,15,3,'F-15','#00FF00','work',1,'real'),(48,16,3,'F-16','#e30d0d','not_valid',1,'real'),(49,1,4,'F-1','#00FF00','work',1,'real'),(50,2,4,'F-2','#00FF00','work',1,'real'),(51,3,4,'F-3','#00FF00','work',1,'real'),(52,4,4,'F-4','#00FF00','valid',1,'real'),(53,5,4,'F-5','#00FF00','valid',1,'real'),(54,6,4,'F-6','#00FF00','valid',1,'real'),(55,7,4,'F-7','#00FF00','valid',1,'real'),(56,8,4,'F-8','#00FF00','not_valid',1,'real'),(57,9,4,'F-9','#00FF00','not_valid',1,'real'),(58,10,4,'F-10','#00FF00','not_valid',0,'real'),(59,11,4,'F-11','#00FF00','not_valid',1,'real'),(60,12,4,'F-12','#00FF00','not_valid',0,'real'),(61,13,4,'F-13','#00FF00','not_valid',0,'real'),(62,14,4,'F-14','#00FF00','work',1,'real'),(63,15,4,'F-15','#00FF00','work',1,'real'),(64,16,4,'F-16','#00FF00','work',1,'real');
/*!40000 ALTER TABLE `fkey` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `machine`
--

DROP TABLE IF EXISTS `machine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `machine` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(512) NOT NULL,
  `code` varchar(512) NOT NULL,
  `ip` varchar(32) NOT NULL,
  `mac` varchar(16) NOT NULL,
  `work_type` enum('amplitude','average') NOT NULL,
  `time_idle_run` int(10) NOT NULL DEFAULT '0' COMMENT 'Время холостого хода, сек',
  `rec_type` enum('real','template') NOT NULL DEFAULT 'real',
  PRIMARY KEY (`id`),
  KEY `code` (`code`(255)),
  KEY `mac` (`mac`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `machine`
--

LOCK TABLES `machine` WRITE;
/*!40000 ALTER TABLE `machine` DISABLE KEYS */;
INSERT INTO `machine` VALUES (1,'название','код','192.168.0.1','AABBCCDDEE','amplitude',5,'template'),(2,'test machine','test_machine','192.168.0.1','00BD3B330571','amplitude',5,'real'),(3,'test1','111111','192.168.0.222','AABBCCDDEE','average',8,'real'),(4,'название','код','192.168.0.1','AABBCCDDEE','amplitude',5,'real');
/*!40000 ALTER TABLE `machine` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `machine_data`
--

DROP TABLE IF EXISTS `machine_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `machine_data` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `number` int(10) NOT NULL,
  `dt` datetime NOT NULL,
  `duration` int(10) DEFAULT NULL,
  `mac` varchar(16) NOT NULL,
  `machine_id` int(10) NOT NULL,
  `operator_id` int(10) DEFAULT NULL,
  `da_max1` int(10) NOT NULL,
  `da_max2` int(10) NOT NULL,
  `da_max3` int(10) NOT NULL,
  `da_max4` int(10) NOT NULL,
  `da_avg1` int(10) NOT NULL,
  `da_avg2` int(10) NOT NULL,
  `da_avg3` int(10) NOT NULL,
  `da_avg4` int(10) NOT NULL,
  `dd1` int(10) NOT NULL,
  `dd2` int(10) NOT NULL,
  `dd3` int(10) NOT NULL,
  `dd4` int(10) NOT NULL,
  `dd_change1` int(10) NOT NULL,
  `dd_change2` int(10) NOT NULL,
  `dd_change3` int(10) NOT NULL,
  `dd_change4` int(10) NOT NULL,
  `state` int(10) NOT NULL,
  `fkey_last` int(10) NOT NULL,
  `fkey_all` int(10) NOT NULL,
  `flags` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dt_mac` (`dt`,`mac`),
  KEY `machine_id` (`machine_id`),
  KEY `operator_id` (`operator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `machine_data`
--

LOCK TABLES `machine_data` WRITE;
/*!40000 ALTER TABLE `machine_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `machine_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `machine_log`
--

DROP TABLE IF EXISTS `machine_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `machine_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `number` int(10) NOT NULL,
  `dt` datetime NOT NULL,
  `duration` int(10) DEFAULT NULL,
  `mac` varchar(16) NOT NULL,
  `machine_id` int(10) NOT NULL,
  `operator_id` int(10) DEFAULT NULL,
  `da_max1` int(10) NOT NULL,
  `da_max2` int(10) NOT NULL,
  `da_max3` int(10) NOT NULL,
  `da_max4` int(10) NOT NULL,
  `da_avg1` int(10) NOT NULL,
  `da_avg2` int(10) NOT NULL,
  `da_avg3` int(10) NOT NULL,
  `da_avg4` int(10) NOT NULL,
  `dd1` int(10) NOT NULL,
  `dd2` int(10) NOT NULL,
  `dd3` int(10) NOT NULL,
  `dd4` int(10) NOT NULL,
  `dd_change1` int(10) NOT NULL,
  `dd_change2` int(10) NOT NULL,
  `dd_change3` int(10) NOT NULL,
  `dd_change4` int(10) NOT NULL,
  `state` int(10) NOT NULL,
  `fkey_last` int(10) NOT NULL,
  `fkey_all` int(10) NOT NULL,
  `flags` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dt_mac` (`dt`,`mac`),
  KEY `machine_id` (`machine_id`),
  KEY `operator_id` (`operator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `machine_log`
--

LOCK TABLES `machine_log` WRITE;
/*!40000 ALTER TABLE `machine_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `machine_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `machine_state`
--

DROP TABLE IF EXISTS `machine_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `machine_state` (
  `id` int(10) NOT NULL,
  `code` varchar(512) NOT NULL,
  `name` varchar(512) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `code` (`code`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `machine_state`
--

LOCK TABLES `machine_state` WRITE;
/*!40000 ALTER TABLE `machine_state` DISABLE KEYS */;
INSERT INTO `machine_state` VALUES (0,'off','выключен'),(1,'on','включен'),(2,'idle_run','холостой ход'),(3,'work','работает');
/*!40000 ALTER TABLE `machine_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operator`
--

DROP TABLE IF EXISTS `operator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operator` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `c1` int(10) unsigned NOT NULL,
  `c2` int(10) unsigned NOT NULL,
  `c3` int(10) unsigned NOT NULL,
  `full_name` varchar(1024) NOT NULL,
  `phone` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`c1`,`c2`,`c3`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operator`
--

LOCK TABLES `operator` WRITE;
/*!40000 ALTER TABLE `operator` DISABLE KEYS */;
INSERT INTO `operator` VALUES (1,59,16,48508,'test1',''),(2,11,22,33,'test2',''),(3,62,129,23484,'test3',''),(4,62,129,23485,'test4','');
/*!40000 ALTER TABLE `operator` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p2_auth_assignment`
--

DROP TABLE IF EXISTS `p2_auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `p2_auth_assignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` int(11) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`),
  KEY `fk_p2_auth_assignment_p2_user1` (`userid`),
  CONSTRAINT `fk_D1C17BEB-E339-4B75-9F2F-9DA964C8C4F2` FOREIGN KEY (`itemname`) REFERENCES `p2_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_p2_auth_assignment_p2_user1` FOREIGN KEY (`userid`) REFERENCES `p2_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p2_auth_assignment`
--

LOCK TABLES `p2_auth_assignment` WRITE;
/*!40000 ALTER TABLE `p2_auth_assignment` DISABLE KEYS */;
INSERT INTO `p2_auth_assignment` VALUES ('admin',1,'','s:0:\"\";'),('admin',3,'','s:0:\"\";'),('editor',1,'','s:0:\"\";'),('editor',2,'','s:0:\"\";'),('editor',3,'','s:0:\"\";'),('member',1,'','s:0:\"\";'),('member',2,'','s:0:\"\";'),('member',3,'','s:0:\"\";');
/*!40000 ALTER TABLE `p2_auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p2_auth_item`
--

DROP TABLE IF EXISTS `p2_auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `p2_auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p2_auth_item`
--

LOCK TABLES `p2_auth_item` WRITE;
/*!40000 ALTER TABLE `p2_auth_item` DISABLE KEYS */;
INSERT INTO `p2_auth_item` VALUES ('admin',2,NULL,NULL,NULL),('createFile',0,NULL,NULL,NULL),('createPage',0,NULL,NULL,NULL),('createUser',0,NULL,NULL,NULL),('createWidget',0,NULL,NULL,NULL),('deleteFile',0,NULL,NULL,NULL),('deletePage',0,NULL,NULL,NULL),('deleteUser',0,NULL,NULL,NULL),('deleteWidget',0,NULL,NULL,NULL),('editFile',0,NULL,NULL,NULL),('editor',2,NULL,NULL,NULL),('editPage',0,NULL,NULL,NULL),('editUser',0,NULL,NULL,NULL),('editWidget',0,NULL,NULL,NULL),('fileManager',1,NULL,NULL,NULL),('member',2,NULL,NULL,NULL),('pageManager',1,NULL,NULL,NULL),('smto-EventColorAdmin',0,NULL,NULL,'N;'),('smto-EventColorAdministrating',1,NULL,NULL,'N;'),('smto-EventColorCreate',0,NULL,NULL,'N;'),('smto-EventColorDelete',0,NULL,NULL,'N;'),('smto-EventColorIndex',0,NULL,NULL,'N;'),('smto-EventColorUpdate',0,NULL,NULL,'N;'),('smto-EventColorView',0,NULL,NULL,'N;'),('smto-EventColorViewing',1,NULL,NULL,'N;'),('smto-MachineAdmin',0,NULL,NULL,'N;'),('smto-MachineAdministrating',1,NULL,NULL,'N;'),('smto-MachineCreate',0,NULL,NULL,'N;'),('smto-MachineDataAdmin',0,NULL,NULL,'N;'),('smto-MachineDataAdministrating',1,NULL,NULL,'N;'),('smto-MachineDataCreate',0,NULL,NULL,'N;'),('smto-MachineDataDelete',0,NULL,NULL,'N;'),('smto-MachineDataIndex',0,NULL,NULL,'N;'),('smto-MachineDataUpdate',0,NULL,NULL,'N;'),('smto-MachineDataView',0,NULL,NULL,'N;'),('smto-MachineDataViewing',1,NULL,NULL,'N;'),('smto-MachineDelete',0,NULL,NULL,'N;'),('smto-MachineIndex',0,NULL,NULL,'N;'),('smto-MachineUpdate',0,NULL,NULL,'N;'),('smto-MachineView',0,NULL,NULL,'N;'),('smto-MachineViewing',1,NULL,NULL,'N;'),('smto-OperatorAdmin',0,NULL,NULL,'N;'),('smto-OperatorAdministrating',1,NULL,NULL,'N;'),('smto-OperatorCreate',0,NULL,NULL,'N;'),('smto-OperatorDelete',0,NULL,NULL,'N;'),('smto-OperatorIndex',0,NULL,NULL,'N;'),('smto-OperatorUpdate',0,NULL,NULL,'N;'),('smto-OperatorView',0,NULL,NULL,'N;'),('smto-OperatorViewing',1,NULL,NULL,'N;'),('userManager',1,NULL,NULL,NULL),('viewFile',0,NULL,NULL,NULL),('viewPage',0,NULL,NULL,NULL),('viewUser',0,NULL,NULL,NULL),('viewWidget',0,NULL,NULL,NULL),('widgetManager',1,NULL,NULL,NULL);
/*!40000 ALTER TABLE `p2_auth_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p2_auth_item_child`
--

DROP TABLE IF EXISTS `p2_auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `p2_auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `fk_751FF434-A447-407E-B89F-2A640F3268BD` (`child`),
  CONSTRAINT `fk_776C1569-3744-451E-96E2-D8F1891CC27A` FOREIGN KEY (`parent`) REFERENCES `p2_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_751FF434-A447-407E-B89F-2A640F3268BD` FOREIGN KEY (`child`) REFERENCES `p2_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p2_auth_item_child`
--

LOCK TABLES `p2_auth_item_child` WRITE;
/*!40000 ALTER TABLE `p2_auth_item_child` DISABLE KEYS */;
INSERT INTO `p2_auth_item_child` VALUES ('fileManager','createFile'),('pageManager','createPage'),('userManager','createUser'),('widgetManager','createWidget'),('fileManager','deleteFile'),('pageManager','deletePage'),('userManager','deleteUser'),('widgetManager','deleteWidget'),('fileManager','editFile'),('pageManager','editPage'),('userManager','editUser'),('widgetManager','editWidget'),('admin','fileManager'),('editor','fileManager'),('admin','pageManager'),('editor','pageManager'),('smto-EventColorAdministrating','smto-EventColorAdmin'),('admin','smto-EventColorAdministrating'),('editor','smto-EventColorAdministrating'),('smto-EventColorAdministrating','smto-EventColorCreate'),('smto-EventColorAdministrating','smto-EventColorDelete'),('smto-EventColorAdministrating','smto-EventColorIndex'),('smto-EventColorAdministrating','smto-EventColorUpdate'),('smto-EventColorViewing','smto-EventColorView'),('admin','smto-EventColorViewing'),('editor','smto-EventColorViewing'),('smto-MachineAdministrating','smto-MachineAdmin'),('admin','smto-MachineAdministrating'),('editor','smto-MachineAdministrating'),('smto-MachineAdministrating','smto-MachineCreate'),('smto-MachineDataAdministrating','smto-MachineDataAdmin'),('admin','smto-MachineDataAdministrating'),('editor','smto-MachineDataAdministrating'),('smto-MachineDataAdministrating','smto-MachineDataCreate'),('smto-MachineDataAdministrating','smto-MachineDataDelete'),('smto-MachineDataAdministrating','smto-MachineDataIndex'),('smto-MachineDataAdministrating','smto-MachineDataUpdate'),('smto-MachineDataViewing','smto-MachineDataView'),('admin','smto-MachineDataViewing'),('editor','smto-MachineDataViewing'),('smto-MachineAdministrating','smto-MachineDelete'),('smto-MachineAdministrating','smto-MachineIndex'),('smto-MachineAdministrating','smto-MachineUpdate'),('smto-MachineViewing','smto-MachineView'),('admin','smto-MachineViewing'),('editor','smto-MachineViewing'),('smto-OperatorAdministrating','smto-OperatorAdmin'),('admin','smto-OperatorAdministrating'),('editor','smto-OperatorAdministrating'),('smto-OperatorAdministrating','smto-OperatorCreate'),('smto-OperatorAdministrating','smto-OperatorDelete'),('smto-OperatorAdministrating','smto-OperatorIndex'),('smto-OperatorAdministrating','smto-OperatorUpdate'),('smto-OperatorViewing','smto-OperatorView'),('admin','smto-OperatorViewing'),('editor','smto-OperatorViewing'),('admin','userManager'),('editor','userManager'),('fileManager','viewFile'),('pageManager','viewPage'),('userManager','viewUser'),('widgetManager','viewWidget'),('admin','widgetManager'),('editor','widgetManager');
/*!40000 ALTER TABLE `p2_auth_item_child` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p2_cell`
--

DROP TABLE IF EXISTS `p2_cell`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `p2_cell` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classPath` varchar(255) NOT NULL COMMENT 'The path to the wiget class',
  `classProps` text COMMENT 'JSONObject with widget properties',
  `rank` int(11) NOT NULL DEFAULT '100' COMMENT 'Order within cell',
  `cellId` varchar(64) DEFAULT NULL,
  `moduleId` varchar(32) DEFAULT NULL,
  `controllerId` varchar(32) DEFAULT NULL,
  `actionName` varchar(32) DEFAULT NULL,
  `requestParam` varchar(32) DEFAULT NULL,
  `sessionParam` varchar(32) DEFAULT NULL,
  `cookieParam` varchar(32) DEFAULT NULL,
  `applicationParam` varchar(32) DEFAULT NULL,
  `moduleParam` varchar(32) DEFAULT NULL,
  `p2_infoId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p2_cell`
--

LOCK TABLES `p2_cell` WRITE;
/*!40000 ALTER TABLE `p2_cell` DISABLE KEYS */;
INSERT INTO `p2_cell` VALUES (1,'p2.widgets.html.P2HtmlWidget','{\"id\":\"1\"}',200,'mainCell','','site','index','','','','','',12),(12,'p2.widgets.html.P2HtmlWidget','{\"id\":\"4\"}',100,'mainCell','','site','page','pageId','','','','',30),(13,'p2.widgets.submenu.P2SubMenuWidget','{\"startNode\":\"1\",\"headline\":\"root\"}',100,'mainCell','','','','','','','','',31);
/*!40000 ALTER TABLE `p2_cell` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p2_config`
--

DROP TABLE IF EXISTS `p2_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `p2_config` (
  `key` varchar(64) NOT NULL,
  `value` text,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p2_config`
--

LOCK TABLES `p2_config` WRITE;
/*!40000 ALTER TABLE `p2_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `p2_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p2_file`
--

DROP TABLE IF EXISTS `p2_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `p2_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `filePath` varchar(255) NOT NULL,
  `fileType` varchar(32) DEFAULT NULL,
  `fileSize` int(11) NOT NULL,
  `fileOriginalName` varchar(128) NOT NULL,
  `fileMd5` varchar(32) NOT NULL,
  `fileInfo` text,
  `p2_infoId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uname` (`name`),
  UNIQUE KEY `ufile` (`filePath`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p2_file`
--

LOCK TABLES `p2_file` WRITE;
/*!40000 ALTER TABLE `p2_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `p2_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p2_html`
--

DROP TABLE IF EXISTS `p2_html`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `p2_html` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `html` text,
  `p2_infoId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uname` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p2_html`
--

LOCK TABLES `p2_html` WRITE;
/*!40000 ALTER TABLE `p2_html` DISABLE KEYS */;
INSERT INTO `p2_html` VALUES (1,'welcome','<p>\r\n	This is the homepage of <em>Website Name</em>. You may modify the following site by logging in as &#39;admin&#39;.</p>\r\n<h1>\r\n	<tt><u><big>Thank you for choosing p2!</big></u></tt></h1>\r\n',11),(4,'price','<p>\r\n	it&#39;s page price!</p>\r\n',29);
/*!40000 ALTER TABLE `p2_html` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p2_info`
--

DROP TABLE IF EXISTS `p2_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `p2_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(64) DEFAULT NULL COMMENT 'Model class this entry belongs to',
  `modelId` int(11) NOT NULL COMMENT 'Id for model',
  `language` varchar(16) DEFAULT NULL COMMENT 'Language for this item, NULL means available in all languages',
  `status` int(11) NOT NULL DEFAULT '30' COMMENT 'STATUS_DELETED    = 0;\nSTATUS_DRAFT      = 10;\nSTATUS_PENDING    = 20;\nSTATUS_ACTIVE     = 30;\nSTATUS_LOCKED     = 40;\nSTATUS_ARCHIVE    = 50;\n',
  `type` varchar(64) DEFAULT NULL COMMENT 'A configurable (see config/p2.php) type for items',
  `checkAccess` varchar(32) DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `modifiedBy` int(11) DEFAULT NULL,
  `modifiedAt` datetime NOT NULL,
  `begin` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `keywords` text COMMENT 'Tag-style information',
  `customData` text,
  `parentId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_model_id` (`id`,`model`),
  KEY `fk_p2_info_p2_user` (`createdBy`),
  KEY `fk_p2_info_p2_user1` (`modifiedBy`),
  KEY `fk_p2_info_p2_info1` (`parentId`),
  CONSTRAINT `fk_p2_info_p2_user` FOREIGN KEY (`createdBy`) REFERENCES `p2_user` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `fk_p2_info_p2_user1` FOREIGN KEY (`modifiedBy`) REFERENCES `p2_user` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `fk_p2_info_p2_info1` FOREIGN KEY (`parentId`) REFERENCES `p2_info` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p2_info`
--

LOCK TABLES `p2_info` WRITE;
/*!40000 ALTER TABLE `p2_info` DISABLE KEYS */;
INSERT INTO `p2_info` VALUES (3,'P2Page',1,NULL,30,NULL,NULL,1,'1970-01-01 00:00:00',1,'1970-01-01 00:00:00',NULL,NULL,NULL,NULL,NULL),(11,'P2Html',1,NULL,30,'',NULL,1,'2009-11-14 20:09:22',1,'2011-08-07 21:26:25','0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL),(12,'P2Cell',1,'0',30,'',NULL,1,'2009-11-14 20:09:27',1,'2011-08-07 21:54:51','0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL),(27,'P2Page',4,NULL,30,'',NULL,1,'2011-08-07 22:14:35',1,'2011-08-08 00:32:49','0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL),(28,'P2Page',5,NULL,30,'',NULL,1,'2011-08-07 22:15:33',1,'2011-08-08 00:54:04','0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL),(29,'P2Html',4,NULL,30,'',NULL,1,'2011-08-07 22:26:30',1,'2011-08-08 00:35:39','0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL),(30,'P2Cell',12,'en_us',30,'',NULL,1,'2011-08-08 00:03:16',1,'2011-08-08 00:55:47','0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL),(31,'P2Cell',13,'ru_ru',30,'',NULL,1,'2011-08-08 00:27:12',1,'2011-08-08 00:30:26','0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL),(32,'P2Page',6,'ru_ru',30,'',NULL,1,'2011-08-08 00:31:27',1,'2011-08-08 00:31:36','0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL);
/*!40000 ALTER TABLE `p2_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p2_log`
--

DROP TABLE IF EXISTS `p2_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `p2_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) DEFAULT NULL,
  `action` varchar(32) DEFAULT NULL,
  `model` varchar(64) DEFAULT NULL,
  `modelId` int(11) DEFAULT NULL,
  `changes` varchar(255) DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `createdAt` datetime DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`),
  KEY `fk_p2_log_p2_user` (`createdBy`),
  CONSTRAINT `fk_p2_log_p2_user` FOREIGN KEY (`createdBy`) REFERENCES `p2_user` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p2_log`
--

LOCK TABLES `p2_log` WRITE;
/*!40000 ALTER TABLE `p2_log` DISABLE KEYS */;
INSERT INTO `p2_log` VALUES (1,'User admin changed html, for P2Html #1.','CHANGE','P2Html',1,'html,',1,'2011-08-06 18:15:50','{\"id\":\"1\",\"name\":\"welcome\",\"html\":\"<p>\\r\\n\\tThis is the homepage of <em>Website Name<\\/em>. You may modify the following site by logging in as &#39;admin&#39;.<\\/p>\\r\\n<p>\\r\\n\\tThank you for choosing p2!<\\/p>\\r\\n\",\"p2_infoId\":\"11\"}'),(2,'User admin created P2Cell #2.','CREATE','P2Cell',2,'',1,'2011-08-06 18:17:57',NULL),(3,'User admin changed controllerId,actionName, for P2Cell #2.','CHANGE','P2Cell',2,'controllerId,actionName,',1,'2011-08-06 18:21:16','{\"id\":\"2\",\"classPath\":\"p2.widgets.submenu.P2SubMenuWidget\",\"classProps\":\"{\\\"startNode\\\":\\\"1\\\",\\\"headline\\\":\\\"test1\\\"}\",\"rank\":\"100\",\"cellId\":\"\",\"moduleId\":\"\",\"controllerId\":\"\",\"actionName\":\"\",\"requestParam\":\"\",\"sessionParam\":\"\",\"cookieParam\":\"\",\"applicationParam\":\"\",\"moduleParam\":\"\",\"p2_infoId\":\"13\"}'),(4,'User admin created P2Cell #3.','CREATE','P2Cell',3,'',1,'2011-08-06 18:24:58',NULL),(5,'User admin created P2Html #2.','CREATE','P2Html',2,'',1,'2011-08-06 18:25:49',NULL),(6,'User admin deleted P2Cell #3.','DELETE','P2Cell',3,'',1,'2011-08-06 19:02:25','{\"id\":\"3\",\"classPath\":\"p2.widgets.blog.P2BlogWidget\",\"classProps\":\"{\\\"type\\\":\\\"blog\\\",\\\"pageSize\\\":\\\"5\\\",\\\"showFullEntries\\\":\\\"1\\\",\\\"moreMarker\\\":\\\"<!--READMORE-->\\\",\\\"detailUrlText\\\":\\\"Read more ...\\\",\\\"detailUrl\\\":\\\"testpage\\\",\\\"listUrlText\\\":\\\"Overview\\\",\\\"listUrl\\\":\\\"\\\",\\\"headlineTag\\\":\\\"h3\\\",\\\"displayPager\\\":0,\\\"ongoing\\\":0,\\\"localized\\\":\\\"1\\\"}\",\"rank\":\"100\",\"cellId\":\"mainCell\",\"moduleId\":\"p2\",\"controllerId\":\"default\",\"actionName\":\"index\",\"requestParam\":\"\",\"sessionParam\":\"\",\"cookieParam\":\"\",\"applicationParam\":\"\",\"moduleParam\":\"\",\"p2_infoId\":\"14\"}'),(7,'User admin created P2Page #2.','CREATE','P2Page',2,'',1,'2011-08-06 19:03:25',NULL),(8,'User admin changed url, for P2Page #2.','CHANGE','P2Page',2,'url,',1,'2011-08-06 19:05:14','{\"id\":\"2\",\"name\":\"en--test\",\"descriptiveName\":\"test page\",\"url\":\"test_page\",\"parentId\":\"1\",\"rank\":\"100\",\"view\":\"\\/cms\\/default\",\"layout\":\"cms\",\"replaceMethod\":null,\"p2_infoId\":\"16\"}'),(9,'User admin changed view, for P2Page #2.','CHANGE','P2Page',2,'view,',1,'2011-08-06 19:05:41','{\"id\":\"2\",\"name\":\"en--test\",\"descriptiveName\":\"test page\",\"url\":\"\",\"parentId\":\"1\",\"rank\":\"100\",\"view\":\"\\/cms\\/default\",\"layout\":\"cms\",\"replaceMethod\":null,\"p2_infoId\":\"16\"}'),(10,'User admin changed view, for P2Page #2.','CHANGE','P2Page',2,'view,',1,'2011-08-06 19:18:16','{\"id\":\"2\",\"name\":\"en--test\",\"descriptiveName\":\"test page\",\"url\":\"\",\"parentId\":\"1\",\"rank\":\"100\",\"view\":\"\\/cms\\/simple\",\"layout\":\"cms\",\"replaceMethod\":null,\"p2_infoId\":\"16\"}'),(11,'User admin created P2Cell #4.','CREATE','P2Cell',4,'',1,'2011-08-06 19:27:20',NULL),(12,'User admin changed classProps, for P2Cell #4.','CHANGE','P2Cell',4,'classProps,',1,'2011-08-06 19:27:51','{\"id\":\"4\",\"classPath\":\"p2.widgets.html.P2HtmlWidget\",\"classProps\":\"{\\\"id\\\":\\\"\\\"}\",\"rank\":\"100\",\"cellId\":\"mainCell\",\"moduleId\":\"p2\",\"controllerId\":\"default\",\"actionName\":\"index\",\"requestParam\":\"\",\"sessionParam\":\"\",\"cookieParam\":\"\",\"applicationParam\":\"\",\"moduleParam\":\"\",\"p2_infoId\":\"17\"}'),(13,'User admin created P2Html #3.','CREATE','P2Html',3,'',1,'2011-08-06 19:28:46',NULL),(14,'User admin changed classProps, for P2Cell #4.','CHANGE','P2Cell',4,'classProps,',1,'2011-08-06 19:28:51','{\"id\":\"4\",\"classPath\":\"p2.widgets.html.P2HtmlWidget\",\"classProps\":\"{\\\"id\\\":\\\"2\\\"}\",\"rank\":\"100\",\"cellId\":\"mainCell\",\"moduleId\":\"p2\",\"controllerId\":\"default\",\"actionName\":\"index\",\"requestParam\":\"\",\"sessionParam\":\"\",\"cookieParam\":\"\",\"applicationParam\":\"\",\"moduleParam\":\"\",\"p2_infoId\":\"17\"}'),(15,'User admin created P2Cell #5.','CREATE','P2Cell',5,'',1,'2011-08-06 19:29:54',NULL),(16,'User admin deleted P2Cell #2.','DELETE','P2Cell',2,'',1,'2011-08-06 19:31:05','{\"id\":\"2\",\"classPath\":\"p2.widgets.submenu.P2SubMenuWidget\",\"classProps\":\"{\\\"startNode\\\":\\\"1\\\",\\\"headline\\\":\\\"test1\\\"}\",\"rank\":\"100\",\"cellId\":\"\",\"moduleId\":\"\",\"controllerId\":\"site\",\"actionName\":\"login\",\"requestParam\":\"\",\"sessionParam\":\"\",\"cookieParam\":\"\",\"applicationParam\":\"\",\"moduleParam\":\"\",\"p2_infoId\":\"13\"}'),(17,'User admin changed view, for P2Page #2.','CHANGE','P2Page',2,'view,',1,'2011-08-06 19:35:35','{\"id\":\"2\",\"name\":\"en--test\",\"descriptiveName\":\"test page\",\"url\":\"\",\"parentId\":\"1\",\"rank\":\"100\",\"view\":\"\\/cms\\/default\",\"layout\":\"cms\",\"replaceMethod\":null,\"p2_infoId\":\"16\"}'),(18,'User admin created P2Page #3.','CREATE','P2Page',3,'',1,'2011-08-07 17:51:14',NULL),(19,'User admin changed view,layout, for P2Page #3.','CHANGE','P2Page',3,'view,layout,',1,'2011-08-07 18:08:31','{\"id\":\"3\",\"name\":\"rrrrrrrrrrr\",\"descriptiveName\":\"rrrrrrrrrrrrrrrrr\",\"url\":\"\",\"parentId\":\"1\",\"rank\":\"100\",\"view\":\"\\/cms\\/default\",\"layout\":\"cms\",\"replaceMethod\":null,\"p2_infoId\":\"20\"}'),(20,'User admin changed view, for P2Page #3.','CHANGE','P2Page',3,'view,',1,'2011-08-07 18:09:04','{\"id\":\"3\",\"name\":\"rrrrrrrrrrr\",\"descriptiveName\":\"rrrrrrrrrrrrrrrrr\",\"url\":\"\",\"parentId\":\"1\",\"rank\":\"100\",\"view\":\"\\/layouts\\/main\",\"layout\":\"layouts\",\"replaceMethod\":null,\"p2_infoId\":\"20\"}'),(21,'User admin changed view, for P2Page #3.','CHANGE','P2Page',3,'view,',1,'2011-08-07 18:09:14','{\"id\":\"3\",\"name\":\"rrrrrrrrrrr\",\"descriptiveName\":\"rrrrrrrrrrrrrrrrr\",\"url\":\"\",\"parentId\":\"1\",\"rank\":\"100\",\"view\":\"\\/layouts\\/plain\",\"layout\":\"layouts\",\"replaceMethod\":null,\"p2_infoId\":\"20\"}'),(22,'User admin changed view,layout, for P2Page #3.','CHANGE','P2Page',3,'view,layout,',1,'2011-08-07 18:31:23','{\"id\":\"3\",\"name\":\"rrrrrrrrrrr\",\"descriptiveName\":\"rrrrrrrrrrrrrrrrr\",\"url\":\"\",\"parentId\":\"1\",\"rank\":\"100\",\"view\":\"\\/layouts\\/simple\",\"layout\":\"layouts\",\"replaceMethod\":null,\"p2_infoId\":\"20\"}'),(23,'User admin deleted P2Html #2.','DELETE','P2Html',2,'',1,'2011-08-07 20:33:23','{\"id\":\"2\",\"name\":\"en-blog-testpage\",\"html\":\"<p>\\r\\n\\tHi! its my post!<\\/p>\\r\\n\",\"p2_infoId\":\"15\"}'),(24,'User admin deleted P2Cell #5.','DELETE','P2Cell',5,'',1,'2011-08-07 20:33:38','{\"id\":\"5\",\"classPath\":\"p2.widgets.html.P2HtmlWidget\",\"classProps\":\"{\\\"id\\\":\\\"2\\\"}\",\"rank\":\"100\",\"cellId\":\"mainCell\",\"moduleId\":\"p2\",\"controllerId\":\"default\",\"actionName\":\"index\",\"requestParam\":\"\",\"sessionParam\":\"\",\"cookieParam\":\"\",\"applicationParam\":\"\",\"moduleParam\":\"\",\"p2_infoId\":\"19\"}'),(25,'User admin deleted P2Cell #4.','DELETE','P2Cell',4,'',1,'2011-08-07 20:33:46','{\"id\":\"4\",\"classPath\":\"p2.widgets.html.P2HtmlWidget\",\"classProps\":\"{\\\"id\\\":\\\"3\\\"}\",\"rank\":\"100\",\"cellId\":\"mainCell\",\"moduleId\":\"p2\",\"controllerId\":\"default\",\"actionName\":\"index\",\"requestParam\":\"\",\"sessionParam\":\"\",\"cookieParam\":\"\",\"applicationParam\":\"\",\"moduleParam\":\"\",\"p2_infoId\":\"17\"}'),(26,'User admin created P2Cell #6.','CREATE','P2Cell',6,'',1,'2011-08-07 21:22:02',NULL),(27,'User admin changed classProps, for P2Cell #6.','CHANGE','P2Cell',6,'classProps,',1,'2011-08-07 21:23:02','{\"id\":\"6\",\"classPath\":\"p2.widgets.html.P2HtmlWidget\",\"classProps\":\"\",\"rank\":\"100\",\"cellId\":\"mainCell\",\"moduleId\":\"p2\",\"controllerId\":\"default\",\"actionName\":\"index\",\"requestParam\":\"\",\"sessionParam\":\"\",\"cookieParam\":\"\",\"applicationParam\":\"\",\"moduleParam\":\"\",\"p2_infoId\":\"21\"}'),(28,'User admin changed html, for P2Html #1.','CHANGE','P2Html',1,'html,',1,'2011-08-07 21:26:25','{\"id\":\"1\",\"name\":\"welcome\",\"html\":\"<p>\\r\\n\\tThis is the homepage of <em>Website Name<\\/em>. You may modify the following site by logging in as &#39;admin&#39;.<\\/p>\\r\\n<p>\\r\\n\\t<u><big>Thank you for choosing p2!<\\/big><\\/u><\\/p>\\r\\n\",\"p2_infoId\":\"11\"}'),(29,'User admin created P2Cell #7.','CREATE','P2Cell',7,'',1,'2011-08-07 21:27:21',NULL),(30,'User admin deleted P2Cell #7.','DELETE','P2Cell',7,'',1,'2011-08-07 21:29:16','{\"id\":\"7\",\"classPath\":\"p2.widgets.html.P2HtmlWidget\",\"classProps\":\"{\\\"id\\\":\\\"3\\\"}\",\"rank\":\"100\",\"cellId\":\"mainCell\",\"moduleId\":\"p2\",\"controllerId\":\"default\",\"actionName\":\"index\",\"requestParam\":\"\",\"sessionParam\":\"\",\"cookieParam\":\"\",\"applicationParam\":\"\",\"moduleParam\":\"\",\"p2_infoId\":\"22\"}'),(31,'User admin deleted P2Cell #6.','DELETE','P2Cell',6,'',1,'2011-08-07 21:30:36','{\"id\":\"6\",\"classPath\":\"p2.widgets.html.P2HtmlWidget\",\"classProps\":\"{\\\"id\\\":\\\"1\\\"}\",\"rank\":\"100\",\"cellId\":\"mainCell\",\"moduleId\":\"p2\",\"controllerId\":\"default\",\"actionName\":\"index\",\"requestParam\":\"\",\"sessionParam\":\"\",\"cookieParam\":\"\",\"applicationParam\":\"\",\"moduleParam\":\"\",\"p2_infoId\":\"21\"}'),(32,'User admin created P2Cell #8.','CREATE','P2Cell',8,'',1,'2011-08-07 21:34:44',NULL),(33,'User admin deleted P2Cell #8.','DELETE','P2Cell',8,'',1,'2011-08-07 21:53:25','{\"id\":\"8\",\"classPath\":\"p2.widgets.html.P2HtmlWidget\",\"classProps\":\"{\\\"id\\\":\\\"1\\\"}\",\"rank\":\"100\",\"cellId\":\"mainCell\",\"moduleId\":\"p2\",\"controllerId\":\"default\",\"actionName\":\"index\",\"requestParam\":\"\",\"sessionParam\":\"\",\"cookieParam\":\"\",\"applicationParam\":\"\",\"moduleParam\":\"\",\"p2_infoId\":\"23\"}'),(34,'User admin created P2Cell #9.','CREATE','P2Cell',9,'',1,'2011-08-07 21:56:36',NULL),(35,'User admin created P2Cell #10.','CREATE','P2Cell',10,'',1,'2011-08-07 21:58:25',NULL),(36,'User admin created P2Cell #11.','CREATE','P2Cell',11,'',1,'2011-08-07 21:59:41',NULL),(37,'User admin deleted P2Cell #9.','DELETE','P2Cell',9,'',1,'2011-08-07 22:02:32','{\"id\":\"9\",\"classPath\":\"p2.widgets.html.P2HtmlWidget\",\"classProps\":\"{\\\"id\\\":\\\"1\\\"}\",\"rank\":\"100\",\"cellId\":\"mainCell\",\"moduleId\":\"p2\",\"controllerId\":\"default\",\"actionName\":\"index\",\"requestParam\":\"\",\"sessionParam\":\"\",\"cookieParam\":\"\",\"applicationParam\":\"\",\"moduleParam\":\"\",\"p2_infoId\":\"24\"}'),(38,'User admin deleted P2Page #3.','DELETE','P2Page',3,'',1,'2011-08-07 22:06:31','{\"id\":\"3\",\"name\":\"rrrrrrrrrrr\",\"descriptiveName\":\"rrrrrrrrrrrrrrrrr\",\"url\":\"\",\"parentId\":\"1\",\"rank\":\"100\",\"view\":\"\\/cms\\/default\",\"layout\":\"cms\",\"replaceMethod\":null,\"p2_infoId\":\"20\"}'),(39,'User admin deleted P2Page #2.','DELETE','P2Page',2,'',1,'2011-08-07 22:06:33','{\"id\":\"2\",\"name\":\"en--test\",\"descriptiveName\":\"test page\",\"url\":\"\",\"parentId\":\"1\",\"rank\":\"100\",\"view\":\"\\/cms\\/simple\",\"layout\":\"cms\",\"replaceMethod\":null,\"p2_infoId\":\"16\"}'),(40,'User admin created P2Page #4.','CREATE','P2Page',4,'',1,'2011-08-07 22:14:35',NULL),(41,'User admin created P2Page #5.','CREATE','P2Page',5,'',1,'2011-08-07 22:15:33',NULL),(42,'User admin changed url, for P2Page #4.','CHANGE','P2Page',4,'url,',1,'2011-08-07 22:21:14','{\"id\":\"4\",\"name\":\"about\",\"descriptiveName\":\"about\",\"url\":\"\",\"parentId\":\"1\",\"rank\":\"100\",\"view\":\"\\/cms\\/default\",\"layout\":\"cms\",\"replaceMethod\":null,\"p2_infoId\":\"27\"}'),(43,'User admin changed layout, for P2Page #4.','CHANGE','P2Page',4,'layout,',1,'2011-08-07 22:21:29','{\"id\":\"4\",\"name\":\"about\",\"descriptiveName\":\"about\",\"url\":\"{\\\"route\\\":\\\"\\/about\\\",\\\"params\\\":{\\\"lang\\\":\\\"all\\\"}}\",\"parentId\":\"1\",\"rank\":\"100\",\"view\":\"\\/cms\\/default\",\"layout\":\"cms\",\"replaceMethod\":null,\"p2_infoId\":\"27\"}'),(44,'User admin changed url,layout, for P2Page #4.','CHANGE','P2Page',4,'url,layout,',1,'2011-08-07 22:23:36','{\"id\":\"4\",\"name\":\"about\",\"descriptiveName\":\"about\",\"url\":\"{\\\"route\\\":\\\"\\/about\\\",\\\"params\\\":{\\\"lang\\\":\\\"all\\\"}}\",\"parentId\":\"1\",\"rank\":\"100\",\"view\":\"\\/cms\\/default\",\"layout\":\"layouts\",\"replaceMethod\":null,\"p2_infoId\":\"27\"}'),(45,'User admin changed url, for P2Page #5.','CHANGE','P2Page',5,'url,',1,'2011-08-07 22:24:29','{\"id\":\"5\",\"name\":\"price\",\"descriptiveName\":\"price\",\"url\":\"\",\"parentId\":\"1\",\"rank\":\"101\",\"view\":\"\\/cms\\/default\",\"layout\":\"cms\",\"replaceMethod\":null,\"p2_infoId\":\"28\"}'),(46,'User admin changed url, for P2Page #4.','CHANGE','P2Page',4,'url,',1,'2011-08-07 22:25:01','{\"id\":\"4\",\"name\":\"about\",\"descriptiveName\":\"about\",\"url\":\"\\/about\",\"parentId\":\"1\",\"rank\":\"100\",\"view\":\"\\/cms\\/default\",\"layout\":\"cms\",\"replaceMethod\":null,\"p2_infoId\":\"27\"}'),(47,'User admin created P2Html #4.','CREATE','P2Html',4,'',1,'2011-08-07 22:26:30',NULL),(48,'User admin deleted P2Html #3.','DELETE','P2Html',3,'',1,'2011-08-07 22:27:00','{\"id\":\"3\",\"name\":\"qqqq\",\"html\":\"<p>\\r\\n\\tqqqqq<\\/p>\\r\\n\",\"p2_infoId\":\"18\"}'),(49,'User admin deleted P2Cell #11.','DELETE','P2Cell',11,'',1,'2011-08-07 22:27:06','{\"id\":\"11\",\"classPath\":\"p2.widgets.html.P2HtmlWidget\",\"classProps\":\"{\\\"id\\\":\\\"3\\\"}\",\"rank\":\"100\",\"cellId\":\"mainCell\",\"moduleId\":\"\",\"controllerId\":\"site\",\"actionName\":\"index\",\"requestParam\":\"\",\"sessionParam\":\"\",\"cookieParam\":\"\",\"applicationParam\":\"\",\"moduleParam\":\"\",\"p2_infoId\":\"26\"}'),(50,'User admin changed url, for P2Page #4.','CHANGE','P2Page',4,'url,',1,'2011-08-07 22:32:36','{\"id\":\"4\",\"name\":\"about\",\"descriptiveName\":\"about\",\"url\":\"{\\\"route\\\":\\\"\\/about\\\"}\",\"parentId\":\"1\",\"rank\":\"100\",\"view\":\"\\/cms\\/default\",\"layout\":\"cms\",\"replaceMethod\":null,\"p2_infoId\":\"27\"}'),(51,'User admin changed classProps, for P2Cell #10.','CHANGE','P2Cell',10,'classProps,',1,'2011-08-07 22:34:13','{\"id\":\"10\",\"classPath\":\"p2.widgets.html.P2HtmlWidget\",\"classProps\":\"{\\\"id\\\":\\\"1\\\"}\",\"rank\":\"100\",\"cellId\":\"mainCell\",\"moduleId\":\"\",\"controllerId\":\"site\",\"actionName\":\"index\",\"requestParam\":\"\",\"sessionParam\":\"\",\"cookieParam\":\"\",\"applicationParam\":\"\",\"moduleParam\":\"\",\"p2_infoId\":\"25\"}'),(52,'User admin changed requestParam, for P2Cell #10.','CHANGE','P2Cell',10,'requestParam,',1,'2011-08-07 22:35:30','{\"id\":\"10\",\"classPath\":\"p2.widgets.html.P2HtmlWidget\",\"classProps\":\"{\\\"id\\\":\\\"4\\\"}\",\"rank\":\"100\",\"cellId\":\"mainCell\",\"moduleId\":\"\",\"controllerId\":\"site\",\"actionName\":\"index\",\"requestParam\":\"\",\"sessionParam\":\"\",\"cookieParam\":\"\",\"applicationParam\":\"\",\"moduleParam\":\"\",\"p2_infoId\":\"25\"}'),(53,'User admin created P2Cell #12.','CREATE','P2Cell',12,'',1,'2011-08-08 00:03:16',NULL),(54,'User admin changed requestParam, for P2Cell #12.','CHANGE','P2Cell',12,'requestParam,',1,'2011-08-08 00:05:04','{\"id\":\"12\",\"classPath\":\"p2.widgets.html.P2HtmlWidget\",\"classProps\":\"{\\\"id\\\":\\\"4\\\"}\",\"rank\":\"100\",\"cellId\":\"mainCell\",\"moduleId\":\"\",\"controllerId\":\"site\",\"actionName\":\"page\",\"requestParam\":\"\",\"sessionParam\":\"\",\"cookieParam\":\"\",\"applicationParam\":\"\",\"moduleParam\":\"\",\"p2_infoId\":\"30\"}'),(55,'User admin changed name, for P2Page #5.','CHANGE','P2Page',5,'name,',1,'2011-08-08 00:12:28','{\"id\":\"5\",\"name\":\"price\",\"descriptiveName\":\"price\",\"url\":\"{\\\"route\\\":\\\"\\/price\\\"}\",\"parentId\":\"1\",\"rank\":\"101\",\"view\":\"\\/cms\\/default\",\"layout\":\"cms\",\"replaceMethod\":null,\"p2_infoId\":\"28\"}'),(56,'User admin changed url, for P2Page #5.','CHANGE','P2Page',5,'url,',1,'2011-08-08 00:13:11','{\"id\":\"5\",\"name\":\"en--price\",\"descriptiveName\":\"price\",\"url\":\"{\\\"route\\\":\\\"\\/price\\\"}\",\"parentId\":\"1\",\"rank\":\"101\",\"view\":\"\\/cms\\/default\",\"layout\":\"cms\",\"replaceMethod\":null,\"p2_infoId\":\"28\"}'),(57,'User admin changed url, for P2Page #5.','CHANGE','P2Page',5,'url,',1,'2011-08-08 00:14:17','{\"id\":\"5\",\"name\":\"en--price\",\"descriptiveName\":\"price\",\"url\":\"\\/price\",\"parentId\":\"1\",\"rank\":\"101\",\"view\":\"\\/cms\\/default\",\"layout\":\"cms\",\"replaceMethod\":null,\"p2_infoId\":\"28\"}'),(58,'User admin changed layout, for P2Page #5.','CHANGE','P2Page',5,'layout,',1,'2011-08-08 00:17:43','{\"id\":\"5\",\"name\":\"en--price\",\"descriptiveName\":\"price\",\"url\":\"\",\"parentId\":\"1\",\"rank\":\"101\",\"view\":\"\\/cms\\/default\",\"layout\":\"cms\",\"replaceMethod\":null,\"p2_infoId\":\"28\"}'),(59,'User admin changed view, for P2Page #5.','CHANGE','P2Page',5,'view,',1,'2011-08-08 00:19:38','{\"id\":\"5\",\"name\":\"en--price\",\"descriptiveName\":\"price\",\"url\":\"\",\"parentId\":\"1\",\"rank\":\"101\",\"view\":\"\\/cms\\/default\",\"layout\":\"application.views.layouts.main\",\"replaceMethod\":null,\"p2_infoId\":\"28\"}'),(60,'User admin changed name, for P2Page #5.','CHANGE','P2Page',5,'name,',1,'2011-08-08 00:21:04','{\"id\":\"5\",\"name\":\"en--price\",\"descriptiveName\":\"price\",\"url\":\"\",\"parentId\":\"1\",\"rank\":\"101\",\"view\":\"\\/cms\\/simple\",\"layout\":\"application.views.layouts.main\",\"replaceMethod\":null,\"p2_infoId\":\"28\"}'),(61,'User admin changed name, for P2Page #5.','CHANGE','P2Page',5,'name,',1,'2011-08-08 00:22:16','{\"id\":\"5\",\"name\":\"price_main\",\"descriptiveName\":\"price\",\"url\":\"\",\"parentId\":\"1\",\"rank\":\"101\",\"view\":\"\\/cms\\/simple\",\"layout\":\"application.views.layouts.main\",\"replaceMethod\":null,\"p2_infoId\":\"28\"}'),(62,'User admin changed url,layout, for P2Page #4.','CHANGE','P2Page',4,'url,layout,',1,'2011-08-08 00:26:08','{\"id\":\"4\",\"name\":\"about\",\"descriptiveName\":\"about\",\"url\":\"{\\\"route\\\":\\\"\\/site\\/index\\\"}\",\"parentId\":\"1\",\"rank\":\"100\",\"view\":\"\\/cms\\/default\",\"layout\":\"cms\",\"replaceMethod\":null,\"p2_infoId\":\"27\"}'),(63,'User admin changed view, for P2Page #4.','CHANGE','P2Page',4,'view,',1,'2011-08-08 00:26:27','{\"id\":\"4\",\"name\":\"about\",\"descriptiveName\":\"about\",\"url\":\"\",\"parentId\":\"1\",\"rank\":\"100\",\"view\":\"\\/cms\\/default\",\"layout\":\"application.views.layouts.main\",\"replaceMethod\":null,\"p2_infoId\":\"27\"}'),(64,'User admin created P2Cell #13.','CREATE','P2Cell',13,'',1,'2011-08-08 00:27:12',NULL),(65,'User admin changed classProps, for P2Cell #13.','CHANGE','P2Cell',13,'classProps,',1,'2011-08-08 00:27:53','{\"id\":\"13\",\"classPath\":\"p2.widgets.submenu.P2SubMenuWidget\",\"classProps\":\"{\\\"startNode\\\":\\\"\\\",\\\"headline\\\":\\\"price\\\"}\",\"rank\":\"100\",\"cellId\":\"mainCell\",\"moduleId\":\"p2\",\"controllerId\":\"default\",\"actionName\":\"index\",\"requestParam\":\"\",\"sessionParam\":\"\",\"cookieParam\":\"\",\"applicationParam\":\"\",\"moduleParam\":\"\",\"p2_infoId\":\"31\"}'),(66,'User admin changed moduleId,controllerId,actionName, for P2Cell #13.','CHANGE','P2Cell',13,'moduleId,controllerId,actionName,',1,'2011-08-08 00:29:23','{\"id\":\"13\",\"classPath\":\"p2.widgets.submenu.P2SubMenuWidget\",\"classProps\":\"{\\\"startNode\\\":\\\"1\\\",\\\"headline\\\":\\\"root\\\"}\",\"rank\":\"100\",\"cellId\":\"mainCell\",\"moduleId\":\"p2\",\"controllerId\":\"default\",\"actionName\":\"index\",\"requestParam\":\"\",\"sessionParam\":\"\",\"cookieParam\":\"\",\"applicationParam\":\"\",\"moduleParam\":\"\",\"p2_infoId\":\"31\"}'),(67,'User admin created P2Page #6.','CREATE','P2Page',6,'',1,'2011-08-08 00:31:27',NULL),(68,'User admin changed view, for P2Page #6.','CHANGE','P2Page',6,'view,',1,'2011-08-08 00:31:36','{\"id\":\"6\",\"name\":\"main\",\"descriptiveName\":\"\\u0413\\u043b\\u0430\\u0432\\u043d\\u0430\\u044f\",\"url\":\"\",\"parentId\":\"1\",\"rank\":\"100\",\"view\":\"\\/cms\\/default\",\"layout\":\"application.views.layouts.main\",\"replaceMethod\":null,\"p2_infoId\":\"32\"}'),(69,'User admin changed descriptiveName, for P2Page #5.','CHANGE','P2Page',5,'descriptiveName,',1,'2011-08-08 00:32:01','{\"id\":\"5\",\"name\":\"price\",\"descriptiveName\":\"price\",\"url\":\"\",\"parentId\":\"1\",\"rank\":\"101\",\"view\":\"\\/cms\\/simple\",\"layout\":\"application.views.layouts.main\",\"replaceMethod\":null,\"p2_infoId\":\"28\"}'),(70,'User admin changed descriptiveName,rank, for P2Page #4.','CHANGE','P2Page',4,'descriptiveName,rank,',1,'2011-08-08 00:32:49','{\"id\":\"4\",\"name\":\"about\",\"descriptiveName\":\"about\",\"url\":\"\",\"parentId\":\"1\",\"rank\":\"100\",\"view\":\"\\/cms\\/simple\",\"layout\":\"application.views.layouts.main\",\"replaceMethod\":null,\"p2_infoId\":\"27\"}'),(71,'User admin changed url, for P2Page #5.','CHANGE','P2Page',5,'url,',1,'2011-08-08 00:54:04','{\"id\":\"5\",\"name\":\"price\",\"descriptiveName\":\"\\u0426\\u0435\\u043d\\u0430\",\"url\":\"\",\"parentId\":\"1\",\"rank\":\"101\",\"view\":\"\\/cms\\/simple\",\"layout\":\"application.views.layouts.main\",\"replaceMethod\":null,\"p2_infoId\":\"28\"}'),(72,'User admin changed requestParam, for P2Cell #12.','CHANGE','P2Cell',12,'requestParam,',1,'2011-08-08 00:55:47','{\"id\":\"12\",\"classPath\":\"p2.widgets.html.P2HtmlWidget\",\"classProps\":\"{\\\"id\\\":\\\"4\\\"}\",\"rank\":\"100\",\"cellId\":\"mainCell\",\"moduleId\":\"\",\"controllerId\":\"site\",\"actionName\":\"page\",\"requestParam\":\"article\",\"sessionParam\":\"\",\"cookieParam\":\"\",\"applicationParam\":\"\",\"moduleParam\":\"\",\"p2_infoId\":\"30\"}'),(73,'User admin created P2User #2.','CREATE','P2User',2,'',1,'2011-08-08 23:55:47',NULL),(74,'User admin created P2User #3.','CREATE','P2User',3,'',1,'2011-08-09 00:59:24',NULL),(75,'User admin deleted P2Cell #10.','DELETE','P2Cell',10,'',1,'2011-08-09 02:17:52','{\"id\":\"10\",\"classPath\":\"p2.widgets.html.P2HtmlWidget\",\"classProps\":\"{\\\"id\\\":\\\"4\\\"}\",\"rank\":\"100\",\"cellId\":\"mainCell\",\"moduleId\":\"\",\"controllerId\":\"site\",\"actionName\":\"index\",\"requestParam\":\"id=111\",\"sessionParam\":\"\",\"cookieParam\":\"\",\"applicationParam\":\"\",\"moduleParam\":\"\",\"p2_infoId\":\"25\"}');
/*!40000 ALTER TABLE `p2_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p2_page`
--

DROP TABLE IF EXISTS `p2_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `p2_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL COMMENT 'unique name of the page, usage: URLs',
  `descriptiveName` varchar(255) DEFAULT NULL COMMENT 'Full name of the page, usage: menu text, link text',
  `url` varchar(255) DEFAULT NULL COMMENT 'If a string the URL of the link or, if a JSONString {route:'''', params{...}} it will be parsed with createUrl()',
  `parentId` int(11) NOT NULL DEFAULT '1' COMMENT 'The id of this page''s parent',
  `rank` int(11) NOT NULL DEFAULT '100' COMMENT 'Order for menus',
  `view` varchar(64) DEFAULT NULL COMMENT 'View path to render',
  `layout` varchar(64) DEFAULT NULL COMMENT 'Layout path to render',
  `replaceMethod` varchar(128) DEFAULT NULL COMMENT 'Method to replace this node in menus entirely',
  `p2_infoId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uname` (`name`),
  KEY `fk_p2_page_p2_page` (`parentId`),
  CONSTRAINT `fk_p2_page_p2_page` FOREIGN KEY (`parentId`) REFERENCES `p2_page` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p2_page`
--

LOCK TABLES `p2_page` WRITE;
/*!40000 ALTER TABLE `p2_page` DISABLE KEYS */;
INSERT INTO `p2_page` VALUES (1,'root',NULL,NULL,1,100,NULL,NULL,NULL,3),(4,'about','О нас','',1,102,'/cms/simple','application.views.layouts.main',NULL,27),(5,'price','Цена','{\"route\":\"/site/page/pageId/5\",\"params\":{\"lang\":\"ru_ru\"}}',1,101,'/cms/simple','application.views.layouts.main',NULL,28),(6,'main','Главная','',1,100,'/cms/simple','application.views.layouts.main',NULL,32);
/*!40000 ALTER TABLE `p2_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `p2_user`
--

DROP TABLE IF EXISTS `p2_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `p2_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `firstName` varchar(64) NOT NULL COMMENT 'Real name',
  `lastName` varchar(64) NOT NULL COMMENT 'Real name',
  `eMail` varchar(128) NOT NULL COMMENT 'Verification e-mails will be sent to this address',
  `verifyEmail` varchar(128) DEFAULT NULL COMMENT 'Updated e-mail address, to be verified',
  `password` varchar(32) NOT NULL COMMENT 'Hash-value',
  `token` varchar(64) DEFAULT NULL COMMENT 'Random string for authentification',
  `tokenExpires` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '20' COMMENT '0=DELETED, 10=BLOCKED, 20=NEW, 30=VERIFIED, 40=ACTIVE',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uname` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p2_user`
--

LOCK TABLES `p2_user` WRITE;
/*!40000 ALTER TABLE `p2_user` DISABLE KEYS */;
INSERT INTO `p2_user` VALUES (1,'admin','Website','Administrator','sks.develop@gmail.com',NULL,'09cd426c5c8c1d706d88157c4f4061ff',NULL,NULL,40),(2,'ssv','Сергей','Сиденко','ssv@211.ru',NULL,'fab5512cb1359bac726b1991eba6178c',NULL,NULL,40),(3,'test','test','test','admin@t.t',NULL,'1bc95628980bb5add4e5910e41ccaf77',NULL,NULL,40);
/*!40000 ALTER TABLE `p2_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timetable`
--

DROP TABLE IF EXISTS `timetable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timetable` (
  `id` int(10) NOT NULL,
  `name` varchar(512) NOT NULL,
  `time_from` time NOT NULL,
  `time_to` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `time_from_time_to` (`time_from`,`time_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timetable`
--

LOCK TABLES `timetable` WRITE;
/*!40000 ALTER TABLE `timetable` DISABLE KEYS */;
INSERT INTO `timetable` VALUES (1,'Первая смена','07:00:00','15:30:00'),(2,'Вторая смена','15:30:00','23:59:59'),(3,'Третья смена','24:00:00','07:00:00'),(4,'День','00:00:00','23:59:59');
/*!40000 ALTER TABLE `timetable` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-08-18  8:06:24
