-- MySQL dump 10.13  Distrib 5.1.41, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: smto
-- ------------------------------------------------------
-- Server version	5.1.41-3ubuntu12.10-log

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
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `amplitude`
--

LOCK TABLES `amplitude` WRITE;
/*!40000 ALTER TABLE `amplitude` DISABLE KEYS */;
INSERT INTO `amplitude` VALUES (1,1,1,10,'zero','template'),(2,2,1,10,'zero','template'),(3,3,1,10,'zero','template'),(4,4,1,10,'zero','template'),(5,1,1,100,'idle_run','template'),(6,2,1,100,'idle_run','template'),(7,3,1,100,'idle_run','template'),(8,4,1,100,'idle_run','template'),(9,1,2,10,'zero','real'),(10,2,2,10,'zero','real'),(11,3,2,10,'zero','real'),(12,4,2,10,'zero','real'),(13,1,2,100,'idle_run','real'),(14,2,2,100,'idle_run','real'),(15,3,2,100,'idle_run','real'),(16,4,2,100,'idle_run','real'),(17,1,3,10,'zero','real'),(18,2,3,10,'zero','real'),(19,3,3,10,'zero','real'),(20,4,3,10,'zero','real'),(21,1,3,100,'idle_run','real'),(22,2,3,100,'idle_run','real'),(23,3,3,100,'idle_run','real'),(24,4,3,100,'idle_run','real'),(25,1,4,10,'zero','real'),(26,2,4,10,'zero','real'),(27,3,4,10,'zero','real'),(28,4,4,10,'zero','real'),(29,1,4,100,'idle_run','real'),(30,2,4,100,'idle_run','real'),(31,3,4,100,'idle_run','real'),(32,4,4,100,'idle_run','real'),(33,1,5,10,'zero','real'),(34,2,5,10,'zero','real'),(35,3,5,10,'zero','real'),(36,4,5,10,'zero','real'),(37,1,5,100,'idle_run','real'),(38,2,5,100,'idle_run','real'),(39,3,5,100,'idle_run','real'),(40,4,5,100,'idle_run','real'),(41,1,6,10,'zero','real'),(42,2,6,10,'zero','real'),(43,3,6,10,'zero','real'),(44,4,6,10,'zero','real'),(45,1,6,100,'idle_run','real'),(46,2,6,100,'idle_run','real'),(47,3,6,100,'idle_run','real'),(48,4,6,100,'idle_run','real');
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
  `max_k_value` smallint(6) DEFAULT '255',
  `avg_k_value` smallint(6) DEFAULT '255',
  PRIMARY KEY (`id`),
  KEY `machine_id` (`machine_id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detector`
--

LOCK TABLES `detector` WRITE;
/*!40000 ALTER TABLE `detector` DISABLE KEYS */;
INSERT INTO `detector` VALUES (1,1,1,1,'digit','template',128,128),(2,2,1,1,'digit','template',128,128),(3,3,1,0,'digit','template',128,128),(4,4,1,0,'digit','template',128,128),(5,1,1,0,'analog','template',128,128),(6,2,1,0,'analog','template',128,128),(7,3,1,1,'analog','template',128,128),(8,4,1,1,'analog','template',128,128),(9,1,2,1,'digit','real',128,128),(10,2,2,1,'digit','real',128,128),(11,3,2,0,'digit','real',128,128),(12,4,2,0,'digit','real',128,128),(13,1,2,1,'analog','real',128,128),(14,2,2,1,'analog','real',128,128),(15,3,2,0,'analog','real',128,128),(16,4,2,0,'analog','real',128,128),(17,1,3,1,'digit','real',128,128),(18,2,3,1,'digit','real',128,128),(19,3,3,0,'digit','real',128,128),(20,4,3,0,'digit','real',128,128),(21,1,3,0,'analog','real',128,128),(22,2,3,0,'analog','real',128,128),(23,3,3,1,'analog','real',128,128),(24,4,3,1,'analog','real',128,128),(25,1,4,1,'digit','real',128,128),(26,2,4,1,'digit','real',128,128),(27,3,4,0,'digit','real',128,128),(28,4,4,0,'digit','real',128,128),(29,1,4,0,'analog','real',128,128),(30,2,4,0,'analog','real',128,128),(31,3,4,1,'analog','real',128,128),(32,4,4,1,'analog','real',128,128),(33,1,5,1,'digit','real',128,128),(34,2,5,1,'digit','real',128,128),(35,3,5,0,'digit','real',128,128),(36,4,5,0,'digit','real',128,128),(37,1,5,0,'analog','real',128,128),(38,2,5,0,'analog','real',128,128),(39,3,5,1,'analog','real',128,128),(40,4,5,1,'analog','real',128,128),(41,1,6,1,'digit','real',128,128),(42,2,6,1,'digit','real',128,128),(43,3,6,0,'digit','real',128,128),(44,4,6,0,'digit','real',128,128),(45,1,6,0,'analog','real',128,128),(46,2,6,0,'analog','real',128,128),(47,3,6,1,'analog','real',128,128),(48,4,6,1,'analog','real',128,128);
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_color`
--

LOCK TABLES `event_color` WRITE;
/*!40000 ALTER TABLE `event_color` DISABLE KEYS */;
INSERT INTO `event_color` VALUES (1,'time_ignored','Не учтено','#B8B8B8'),(2,'machine_0','Станок выключен','#e8051b'),(3,'machine_1','Станок простаивает','#FA8071'),(4,'machine_2','Станок на холостом ходу','#d3e01d'),(5,'machine_3','Станок работае','#2f940d');
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
  `machine_id` int(10) NOT NULL,
  `machine_event_id` int(10) DEFAULT NULL,
  `code` varchar(128) NOT NULL,
  `name` text NOT NULL,
  `color` varchar(128) NOT NULL DEFAULT '',
  `type` enum('work','valid','not_valid') NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `descr` varchar(1024) NOT NULL DEFAULT '',
  `rec_type` enum('real','template') NOT NULL DEFAULT 'real',
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`machine_id`,`number`),
  KEY `machine_id` (`machine_id`)
) ENGINE=InnoDB AUTO_INCREMENT=625 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fkey`
--

LOCK TABLES `fkey` WRITE;
/*!40000 ALTER TABLE `fkey` DISABLE KEYS */;
INSERT INTO `fkey` VALUES (1,1,1,1,'F-1','','','work',1,'','template'),(2,2,1,2,'F-2','','','work',1,'','template'),(3,3,1,3,'F-3','','','work',1,'','template'),(4,4,1,4,'F-4','','','valid',1,'','template'),(5,5,1,5,'F-5','','','valid',1,'','template'),(6,6,1,6,'F-6','','','valid',1,'','template'),(7,7,1,7,'F-7','','','valid',1,'','template'),(8,8,1,8,'F-8','','','not_valid',1,'','template'),(9,9,1,9,'F-9','','','not_valid',1,'','template'),(10,10,1,10,'F-10','','','not_valid',0,'','template'),(11,11,1,-1,'F-11','','','not_valid',0,'','template'),(12,12,1,-1,'F-12','','','not_valid',0,'','template'),(13,13,1,-1,'F-13','','','not_valid',0,'','template'),(14,14,1,-1,'F-14','','','not_valid',0,'','template'),(15,15,1,-1,'F-15','','','not_valid',0,'','template'),(16,16,1,-1,'F-16','','','not_valid',0,'','template'),(17,1,2,10,'F-1','','','work',1,'','real'),(18,2,2,4,'F-2','','','work',1,'','real'),(19,3,2,11,'F-3','','','work',1,'','real'),(20,4,2,-1,'F-4','','','valid',0,'','real'),(21,5,2,12,'F-5','','','valid',1,'','real'),(22,6,2,-1,'F-6','','','valid',0,'','real'),(23,7,2,1,'F-7','','','valid',1,'','real'),(24,8,2,-1,'F-8','','','not_valid',0,'','real'),(25,9,2,-1,'F-9','','','not_valid',0,'','real'),(26,10,2,-1,'F-10','','','not_valid',0,'','real'),(27,11,2,-1,'F-11','','','not_valid',0,'','real'),(28,12,2,-1,'F-12','','','not_valid',0,'','real'),(29,13,2,-1,'F-13','','','not_valid',0,'','real'),(30,14,2,-1,'F-14','','','not_valid',0,'','real'),(31,15,2,-1,'F-15','','','not_valid',0,'','real'),(32,16,2,-1,'F-16','','','not_valid',0,'','real'),(33,1,3,10,'F-1','','','work',1,'','real'),(34,2,3,4,'F-2','','','work',1,'','real'),(35,3,3,11,'F-3','','','work',1,'','real'),(36,4,3,-1,'F-4','','','valid',0,'','real'),(37,5,3,12,'F-5','','','valid',1,'','real'),(38,6,3,-1,'F-6','','','valid',0,'','real'),(39,7,3,1,'F-7','','','valid',1,'','real'),(40,8,3,-1,'F-8','','','not_valid',0,'','real'),(41,9,3,-1,'F-9','','','not_valid',0,'','real'),(42,10,3,-1,'F-10','','','not_valid',0,'','real'),(43,11,3,-1,'F-11','','','not_valid',0,'','real'),(44,12,3,-1,'F-12','','','not_valid',0,'','real'),(45,13,3,-1,'F-13','','','not_valid',0,'','real'),(46,14,3,-1,'F-14','','','not_valid',0,'','real'),(47,15,3,-1,'F-15','','','not_valid',0,'','real'),(48,16,3,-1,'F-16','','','not_valid',0,'','real'),(49,1,4,10,'F-1','','','work',1,'','real'),(50,2,4,4,'F-2','','','work',1,'','real'),(51,3,4,11,'F-3','','','work',1,'','real'),(52,4,4,-1,'F-4','','','valid',0,'','real'),(53,5,4,12,'F-5','','','valid',1,'','real'),(54,6,4,-1,'F-6','','','valid',0,'','real'),(55,7,4,1,'F-7','','','valid',1,'','real'),(56,8,4,-1,'F-8','','','not_valid',0,'','real'),(57,9,4,-1,'F-9','','','not_valid',0,'','real'),(58,10,4,-1,'F-10','','','not_valid',0,'','real'),(59,11,4,-1,'F-11','','','not_valid',0,'','real'),(60,12,4,-1,'F-12','','','not_valid',0,'','real'),(61,13,4,-1,'F-13','','','not_valid',0,'','real'),(62,14,4,-1,'F-14','','','not_valid',0,'','real'),(63,15,4,-1,'F-15','','','not_valid',0,'','real'),(64,16,4,-1,'F-16','','','not_valid',0,'','real'),(65,1,5,10,'F-1','','','work',1,'','real'),(66,2,5,4,'F-2','','','work',1,'','real'),(67,3,5,11,'F-3','','','work',1,'','real'),(68,4,5,-1,'F-4','','','valid',0,'','real'),(69,5,5,12,'F-5','','','valid',1,'','real'),(70,6,5,-1,'F-6','','','valid',0,'','real'),(71,7,5,1,'F-7','','','valid',1,'','real'),(72,8,5,-1,'F-8','','','not_valid',0,'','real'),(73,9,5,-1,'F-9','','','not_valid',0,'','real'),(74,10,5,-1,'F-10','','','not_valid',0,'','real'),(75,11,5,-1,'F-11','','','not_valid',0,'','real'),(76,12,5,-1,'F-12','','','not_valid',0,'','real'),(77,13,5,-1,'F-13','','','not_valid',0,'','real'),(78,14,5,-1,'F-14','','','not_valid',0,'','real'),(79,15,5,-1,'F-15','','','not_valid',0,'','real'),(80,16,5,-1,'F-16','','','not_valid',0,'','real'),(81,1,6,10,'F-1','','','work',1,'','real'),(82,2,6,4,'F-2','','','work',1,'','real'),(83,3,6,11,'F-3','','','work',1,'','real'),(84,4,6,-1,'F-4','','','valid',0,'','real'),(85,5,6,12,'F-5','','','valid',1,'','real'),(86,6,6,-1,'F-6','','','valid',0,'','real'),(87,7,6,1,'F-7','','','valid',1,'','real'),(88,8,6,-1,'F-8','','','not_valid',0,'','real'),(89,9,6,-1,'F-9','','','not_valid',0,'','real'),(90,10,6,-1,'F-10','','','not_valid',0,'','real'),(91,11,6,-1,'F-11','','','not_valid',0,'','real'),(92,12,6,-1,'F-12','','','not_valid',0,'','real'),(93,13,6,-1,'F-13','','','not_valid',0,'','real'),(94,14,6,-1,'F-14','','','not_valid',0,'','real'),(95,15,6,-1,'F-15','','','not_valid',0,'','real'),(96,16,6,-1,'F-16','','','not_valid',0,'','real'),(97,1,7,1,'F-1','','','work',1,'','real'),(98,2,7,2,'F-2','','','work',1,'','real'),(99,3,7,3,'F-3','','','work',1,'','real'),(100,4,7,4,'F-4','','','valid',1,'','real'),(101,5,7,5,'F-5','','','valid',1,'','real'),(102,6,7,6,'F-6','','','valid',1,'','real'),(103,7,7,7,'F-7','','','valid',1,'','real'),(104,8,7,8,'F-8','','','not_valid',1,'','real'),(105,9,7,9,'F-9','','','not_valid',1,'','real'),(106,10,7,10,'F-10','','','not_valid',0,'','real'),(107,11,7,-1,'F-11','','','not_valid',0,'','real'),(108,12,7,-1,'F-12','','','not_valid',0,'','real'),(109,13,7,-1,'F-13','','','not_valid',0,'','real'),(110,14,7,-1,'F-14','','','not_valid',0,'','real'),(111,15,7,-1,'F-15','','','not_valid',0,'','real'),(112,16,7,-1,'F-16','','','not_valid',0,'','real'),(113,1,8,1,'F-1','','','work',1,'','real'),(114,2,8,2,'F-2','','','work',1,'','real'),(115,3,8,3,'F-3','','','work',1,'','real'),(116,4,8,4,'F-4','','','valid',1,'','real'),(117,5,8,5,'F-5','','','valid',1,'','real'),(118,6,8,6,'F-6','','','valid',1,'','real'),(119,7,8,7,'F-7','','','valid',1,'','real'),(120,8,8,8,'F-8','','','not_valid',1,'','real'),(121,9,8,9,'F-9','','','not_valid',1,'','real'),(122,10,8,10,'F-10','','','not_valid',0,'','real'),(123,11,8,-1,'F-11','','','not_valid',0,'','real'),(124,12,8,-1,'F-12','','','not_valid',0,'','real'),(125,13,8,-1,'F-13','','','not_valid',0,'','real'),(126,14,8,-1,'F-14','','','not_valid',0,'','real'),(127,15,8,-1,'F-15','','','not_valid',0,'','real'),(128,16,8,-1,'F-16','','','not_valid',0,'','real'),(129,1,9,1,'F-1','','','work',1,'','real'),(130,2,9,2,'F-2','','','work',1,'','real'),(131,3,9,3,'F-3','','','work',1,'','real'),(132,4,9,4,'F-4','','','valid',1,'','real'),(133,5,9,5,'F-5','','','valid',1,'','real'),(134,6,9,6,'F-6','','','valid',1,'','real'),(135,7,9,7,'F-7','','','valid',1,'','real'),(136,8,9,8,'F-8','','','not_valid',1,'','real'),(137,9,9,9,'F-9','','','not_valid',1,'','real'),(138,10,9,10,'F-10','','','not_valid',0,'','real'),(139,11,9,-1,'F-11','','','not_valid',0,'','real'),(140,12,9,-1,'F-12','','','not_valid',0,'','real'),(141,13,9,-1,'F-13','','','not_valid',0,'','real'),(142,14,9,-1,'F-14','','','not_valid',0,'','real'),(143,15,9,-1,'F-15','','','not_valid',0,'','real'),(144,16,9,-1,'F-16','','','not_valid',0,'','real'),(145,1,10,10,'F-1','','','work',1,'','real'),(146,2,10,4,'F-2','','','work',1,'','real'),(147,3,10,11,'F-3','','','work',1,'','real'),(148,4,10,-1,'F-4','','','valid',0,'','real'),(149,5,10,12,'F-5','','','valid',1,'','real'),(150,6,10,-1,'F-6','','','valid',0,'','real'),(151,7,10,1,'F-7','','','valid',1,'','real'),(152,8,10,-1,'F-8','','','not_valid',0,'','real'),(153,9,10,-1,'F-9','','','not_valid',0,'','real'),(154,10,10,-1,'F-10','','','not_valid',0,'','real'),(155,11,10,-1,'F-11','','','not_valid',0,'','real'),(156,12,10,-1,'F-12','','','not_valid',0,'','real'),(157,13,10,-1,'F-13','','','not_valid',0,'','real'),(158,14,10,-1,'F-14','','','not_valid',0,'','real'),(159,15,10,-1,'F-15','','','not_valid',0,'','real'),(160,16,10,-1,'F-16','','','not_valid',0,'','real'),(161,1,11,10,'F-1','','','work',1,'','real'),(162,2,11,4,'F-2','','','work',1,'','real'),(163,3,11,11,'F-3','','','work',1,'','real'),(164,4,11,-1,'F-4','','','valid',0,'','real'),(165,5,11,12,'F-5','','','valid',1,'','real'),(166,6,11,-1,'F-6','','','valid',0,'','real'),(167,7,11,1,'F-7','','','valid',1,'','real'),(168,8,11,-1,'F-8','','','not_valid',0,'','real'),(169,9,11,-1,'F-9','','','not_valid',0,'','real'),(170,10,11,-1,'F-10','','','not_valid',0,'','real'),(171,11,11,-1,'F-11','','','not_valid',0,'','real'),(172,12,11,-1,'F-12','','','not_valid',0,'','real'),(173,13,11,-1,'F-13','','','not_valid',0,'','real'),(174,14,11,-1,'F-14','','','not_valid',0,'','real'),(175,15,11,-1,'F-15','','','not_valid',0,'','real'),(176,16,11,-1,'F-16','','','not_valid',0,'','real'),(177,1,12,10,'F-1','','','work',1,'','real'),(178,2,12,4,'F-2','','','work',1,'','real'),(179,3,12,11,'F-3','','','work',1,'','real'),(180,4,12,-1,'F-4','','','valid',0,'','real'),(181,5,12,12,'F-5','','','valid',1,'','real'),(182,6,12,-1,'F-6','','','valid',0,'','real'),(183,7,12,1,'F-7','','','valid',1,'','real'),(184,8,12,-1,'F-8','','','not_valid',0,'','real'),(185,9,12,-1,'F-9','','','not_valid',0,'','real'),(186,10,12,-1,'F-10','','','not_valid',0,'','real'),(187,11,12,-1,'F-11','','','not_valid',0,'','real'),(188,12,12,-1,'F-12','','','not_valid',0,'','real'),(189,13,12,-1,'F-13','','','not_valid',0,'','real'),(190,14,12,-1,'F-14','','','not_valid',0,'','real'),(191,15,12,-1,'F-15','','','not_valid',0,'','real'),(192,16,12,-1,'F-16','','','not_valid',0,'','real'),(193,1,13,10,'F-1','','','work',1,'','real'),(194,2,13,4,'F-2','','','work',1,'','real'),(195,3,13,11,'F-3','','','work',1,'','real'),(196,4,13,-1,'F-4','','','valid',0,'','real'),(197,5,13,12,'F-5','','','valid',1,'','real'),(198,6,13,-1,'F-6','','','valid',0,'','real'),(199,7,13,1,'F-7','','','valid',1,'','real'),(200,8,13,-1,'F-8','','','not_valid',0,'','real'),(201,9,13,-1,'F-9','','','not_valid',0,'','real'),(202,10,13,-1,'F-10','','','not_valid',0,'','real'),(203,11,13,-1,'F-11','','','not_valid',0,'','real'),(204,12,13,-1,'F-12','','','not_valid',0,'','real'),(205,13,13,-1,'F-13','','','not_valid',0,'','real'),(206,14,13,-1,'F-14','','','not_valid',0,'','real'),(207,15,13,-1,'F-15','','','not_valid',0,'','real'),(208,16,13,-1,'F-16','','','not_valid',0,'','real'),(209,1,14,10,'F-1','','','work',1,'','real'),(210,2,14,4,'F-2','','','work',1,'','real'),(211,3,14,11,'F-3','','','work',1,'','real'),(212,4,14,-1,'F-4','','','valid',0,'','real'),(213,5,14,12,'F-5','','','valid',1,'','real'),(214,6,14,-1,'F-6','','','valid',0,'','real'),(215,7,14,1,'F-7','','','valid',1,'','real'),(216,8,14,-1,'F-8','','','not_valid',0,'','real'),(217,9,14,-1,'F-9','','','not_valid',0,'','real'),(218,10,14,-1,'F-10','','','not_valid',0,'','real'),(219,11,14,-1,'F-11','','','not_valid',0,'','real'),(220,12,14,-1,'F-12','','','not_valid',0,'','real'),(221,13,14,-1,'F-13','','','not_valid',0,'','real'),(222,14,14,-1,'F-14','','','not_valid',0,'','real'),(223,15,14,-1,'F-15','','','not_valid',0,'','real'),(224,16,14,-1,'F-16','','','not_valid',0,'','real'),(225,1,15,10,'F-1','','','work',1,'','real'),(226,2,15,4,'F-2','','','work',1,'','real'),(227,3,15,11,'F-3','','','work',1,'','real'),(228,4,15,-1,'F-4','','','valid',0,'','real'),(229,5,15,12,'F-5','','','valid',1,'','real'),(230,6,15,-1,'F-6','','','valid',0,'','real'),(231,7,15,1,'F-7','','','valid',1,'','real'),(232,8,15,-1,'F-8','','','not_valid',0,'','real'),(233,9,15,-1,'F-9','','','not_valid',0,'','real'),(234,10,15,-1,'F-10','','','not_valid',0,'','real'),(235,11,15,-1,'F-11','','','not_valid',0,'','real'),(236,12,15,-1,'F-12','','','not_valid',0,'','real'),(237,13,15,-1,'F-13','','','not_valid',0,'','real'),(238,14,15,-1,'F-14','','','not_valid',0,'','real'),(239,15,15,-1,'F-15','','','not_valid',0,'','real'),(240,16,15,-1,'F-16','','','not_valid',0,'','real'),(241,1,16,10,'F-1','','','work',1,'','real'),(242,2,16,4,'F-2','','','work',1,'','real'),(243,3,16,11,'F-3','','','work',1,'','real'),(244,4,16,-1,'F-4','','','valid',1,'','real'),(245,5,16,12,'F-5','','','valid',1,'','real'),(246,6,16,-1,'F-6','','','valid',1,'','real'),(247,7,16,1,'F-7','','','valid',1,'','real'),(248,8,16,-1,'F-8','','','not_valid',1,'','real'),(249,9,16,-1,'F-9','','','not_valid',1,'','real'),(250,10,16,-1,'F-10','','','not_valid',1,'','real'),(251,11,16,-1,'F-11','','','not_valid',0,'','real'),(252,12,16,-1,'F-12','','','not_valid',0,'','real'),(253,13,16,-1,'F-13','','','not_valid',0,'','real'),(254,14,16,-1,'F-14','','','not_valid',0,'','real'),(255,15,16,-1,'F-15','','','not_valid',0,'','real'),(256,16,16,-1,'F-16','','','not_valid',0,'','real'),(257,1,17,10,'F-1','','','work',1,'','real'),(258,2,17,4,'F-2','','','work',1,'','real'),(259,3,17,11,'F-3','','','work',1,'','real'),(260,4,17,-1,'F-4','','','valid',1,'','real'),(261,5,17,12,'F-5','','','valid',1,'','real'),(262,6,17,-1,'F-6','','','valid',1,'','real'),(263,7,17,1,'F-7','','','valid',1,'','real'),(264,8,17,-1,'F-8','','','not_valid',1,'','real'),(265,9,17,-1,'F-9','','','not_valid',1,'','real'),(266,10,17,-1,'F-10','','','not_valid',1,'','real'),(267,11,17,-1,'F-11','','','not_valid',0,'','real'),(268,12,17,-1,'F-12','','','not_valid',0,'','real'),(269,13,17,-1,'F-13','','','not_valid',0,'','real'),(270,14,17,-1,'F-14','','','not_valid',0,'','real'),(271,15,17,-1,'F-15','','','not_valid',0,'','real'),(272,16,17,-1,'F-16','','','not_valid',0,'','real'),(273,1,18,10,'F-1','','','work',1,'','real'),(274,2,18,4,'F-2','','','work',1,'','real'),(275,3,18,11,'F-3','','','work',1,'','real'),(276,4,18,-1,'F-4','','','valid',0,'','real'),(277,5,18,12,'F-5','','','valid',1,'','real'),(278,6,18,-1,'F-6','','','valid',0,'','real'),(279,7,18,1,'F-7','','','valid',1,'','real'),(280,8,18,-1,'F-8','','','not_valid',0,'','real'),(281,9,18,-1,'F-9','','','not_valid',0,'','real'),(282,10,18,-1,'F-10','','','not_valid',0,'','real'),(283,11,18,-1,'F-11','','','not_valid',0,'','real'),(284,12,18,-1,'F-12','','','not_valid',0,'','real'),(285,13,18,-1,'F-13','','','not_valid',0,'','real'),(286,14,18,-1,'F-14','','','not_valid',0,'','real'),(287,15,18,-1,'F-15','','','not_valid',0,'','real'),(288,16,18,-1,'F-16','','','not_valid',0,'','real'),(289,1,19,10,'F-1','','','work',1,'','real'),(290,2,19,4,'F-2','','','work',1,'','real'),(291,3,19,11,'F-3','','','work',1,'','real'),(292,4,19,-1,'F-4','','','valid',0,'','real'),(293,5,19,12,'F-5','','','valid',1,'','real'),(294,6,19,-1,'F-6','','','valid',0,'','real'),(295,7,19,1,'F-7','','','valid',1,'','real'),(296,8,19,-1,'F-8','','','not_valid',0,'','real'),(297,9,19,-1,'F-9','','','not_valid',0,'','real'),(298,10,19,-1,'F-10','','','not_valid',0,'','real'),(299,11,19,-1,'F-11','','','not_valid',0,'','real'),(300,12,19,-1,'F-12','','','not_valid',0,'','real'),(301,13,19,-1,'F-13','','','not_valid',0,'','real'),(302,14,19,-1,'F-14','','','not_valid',0,'','real'),(303,15,19,-1,'F-15','','','not_valid',0,'','real'),(304,16,19,-1,'F-16','','','not_valid',0,'','real'),(305,1,20,10,'F-1','','','work',1,'','real'),(306,2,20,4,'F-2','','','work',1,'','real'),(307,3,20,11,'F-3','','','work',1,'','real'),(308,4,20,-1,'F-4','','','valid',0,'','real'),(309,5,20,12,'F-5','','','valid',1,'','real'),(310,6,20,-1,'F-6','','','valid',0,'','real'),(311,7,20,1,'F-7','','','valid',1,'','real'),(312,8,20,-1,'F-8','','','not_valid',0,'','real'),(313,9,20,-1,'F-9','','','not_valid',0,'','real'),(314,10,20,-1,'F-10','','','not_valid',0,'','real'),(315,11,20,-1,'F-11','','','not_valid',0,'','real'),(316,12,20,-1,'F-12','','','not_valid',0,'','real'),(317,13,20,-1,'F-13','','','not_valid',0,'','real'),(318,14,20,-1,'F-14','','','not_valid',0,'','real'),(319,15,20,-1,'F-15','','','not_valid',0,'','real'),(320,16,20,-1,'F-16','','','not_valid',0,'','real'),(321,1,21,10,'F-1','','','work',1,'','real'),(322,2,21,8,'F-2','','','work',1,'','real'),(323,3,21,11,'F-3','','','work',1,'','real'),(324,4,21,-1,'F-4','','','valid',0,'','real'),(325,5,21,12,'F-5','','','valid',1,'','real'),(326,6,21,-1,'F-6','','','valid',0,'','real'),(327,7,21,1,'F-7','','','valid',1,'','real'),(328,8,21,-1,'F-8','','','not_valid',0,'','real'),(329,9,21,-1,'F-9','','','not_valid',0,'','real'),(330,10,21,-1,'F-10','','','not_valid',0,'','real'),(331,11,21,-1,'F-11','','','not_valid',0,'','real'),(332,12,21,-1,'F-12','','','not_valid',0,'','real'),(333,13,21,-1,'F-13','','','not_valid',0,'','real'),(334,14,21,-1,'F-14','','','not_valid',0,'','real'),(335,15,21,-1,'F-15','','','not_valid',0,'','real'),(336,16,21,-1,'F-16','','','not_valid',0,'','real'),(337,1,22,10,'F-1','','','work',1,'','real'),(338,2,22,4,'F-2','','','work',1,'','real'),(339,3,22,11,'F-3','','','work',1,'','real'),(340,4,22,-1,'F-4','','','valid',0,'','real'),(341,5,22,12,'F-5','','','valid',1,'','real'),(342,6,22,-1,'F-6','','','valid',0,'','real'),(343,7,22,1,'F-7','','','valid',1,'','real'),(344,8,22,-1,'F-8','','','not_valid',0,'','real'),(345,9,22,-1,'F-9','','','not_valid',0,'','real'),(346,10,22,-1,'F-10','','','not_valid',0,'','real'),(347,11,22,-1,'F-11','','','not_valid',0,'','real'),(348,12,22,-1,'F-12','','','not_valid',0,'','real'),(349,13,22,-1,'F-13','','','not_valid',0,'','real'),(350,14,22,-1,'F-14','','','not_valid',0,'','real'),(351,15,22,-1,'F-15','','','not_valid',0,'','real'),(352,16,22,-1,'F-16','','','not_valid',0,'','real'),(353,1,23,10,'F-1','','','work',1,'','real'),(354,2,23,4,'F-2','','','work',1,'','real'),(355,3,23,11,'F-3','','','work',1,'','real'),(356,4,23,-1,'F-4','','','valid',0,'','real'),(357,5,23,12,'F-5','','','valid',1,'','real'),(358,6,23,-1,'F-6','','','valid',0,'','real'),(359,7,23,1,'F-7','','','valid',1,'','real'),(360,8,23,-1,'F-8','','','not_valid',0,'','real'),(361,9,23,-1,'F-9','','','not_valid',0,'','real'),(362,10,23,-1,'F-10','','','not_valid',0,'','real'),(363,11,23,-1,'F-11','','','not_valid',0,'','real'),(364,12,23,-1,'F-12','','','not_valid',0,'','real'),(365,13,23,-1,'F-13','','','not_valid',0,'','real'),(366,14,23,-1,'F-14','','','not_valid',0,'','real'),(367,15,23,-1,'F-15','','','not_valid',0,'','real'),(368,16,23,-1,'F-16','','','not_valid',0,'','real'),(369,1,24,10,'F-1','','','work',1,'','real'),(370,2,24,4,'F-2','','','work',1,'','real'),(371,3,24,11,'F-3','','','work',1,'','real'),(372,4,24,-1,'F-4','','','valid',0,'','real'),(373,5,24,12,'F-5','','','valid',1,'','real'),(374,6,24,-1,'F-6','','','valid',0,'','real'),(375,7,24,1,'F-7','','','valid',1,'','real'),(376,8,24,-1,'F-8','','','not_valid',0,'','real'),(377,9,24,-1,'F-9','','','not_valid',0,'','real'),(378,10,24,-1,'F-10','','','not_valid',0,'','real'),(379,11,24,-1,'F-11','','','not_valid',0,'','real'),(380,12,24,-1,'F-12','','','not_valid',0,'','real'),(381,13,24,-1,'F-13','','','not_valid',0,'','real'),(382,14,24,-1,'F-14','','','not_valid',0,'','real'),(383,15,24,-1,'F-15','','','not_valid',0,'','real'),(384,16,24,-1,'F-16','','','not_valid',0,'','real'),(385,1,25,10,'F-1','','','work',1,'','real'),(386,2,25,4,'F-2','','','work',1,'','real'),(387,3,25,11,'F-3','','','work',1,'','real'),(388,4,25,-1,'F-4','','','valid',0,'','real'),(389,5,25,12,'F-5','','','valid',1,'','real'),(390,6,25,-1,'F-6','','','valid',0,'','real'),(391,7,25,1,'F-7','','','valid',1,'','real'),(392,8,25,-1,'F-8','','','not_valid',0,'','real'),(393,9,25,-1,'F-9','','','not_valid',0,'','real'),(394,10,25,-1,'F-10','','','not_valid',0,'','real'),(395,11,25,-1,'F-11','','','not_valid',0,'','real'),(396,12,25,-1,'F-12','','','not_valid',0,'','real'),(397,13,25,-1,'F-13','','','not_valid',0,'','real'),(398,14,25,-1,'F-14','','','not_valid',0,'','real'),(399,15,25,-1,'F-15','','','not_valid',0,'','real'),(400,16,25,-1,'F-16','','','not_valid',0,'','real'),(401,1,26,10,'F-1','','','work',1,'','real'),(402,2,26,4,'F-2','','','work',1,'','real'),(403,3,26,11,'F-3','','','work',1,'','real'),(404,4,26,-1,'F-4','','','valid',0,'','real'),(405,5,26,12,'F-5','','','valid',1,'','real'),(406,6,26,-1,'F-6','','','valid',0,'','real'),(407,7,26,1,'F-7','','','valid',1,'','real'),(408,8,26,-1,'F-8','','','not_valid',0,'','real'),(409,9,26,-1,'F-9','','','not_valid',0,'','real'),(410,10,26,-1,'F-10','','','not_valid',0,'','real'),(411,11,26,-1,'F-11','','','not_valid',0,'','real'),(412,12,26,-1,'F-12','','','not_valid',0,'','real'),(413,13,26,-1,'F-13','','','not_valid',0,'','real'),(414,14,26,-1,'F-14','','','not_valid',0,'','real'),(415,15,26,-1,'F-15','','','not_valid',0,'','real'),(416,16,26,-1,'F-16','','','not_valid',0,'','real'),(417,1,27,10,'F-1','','','work',1,'','real'),(418,2,27,4,'F-2','','','work',1,'','real'),(419,3,27,11,'F-3','','','work',1,'','real'),(420,4,27,-1,'F-4','','','valid',0,'','real'),(421,5,27,12,'F-5','','','valid',1,'','real'),(422,6,27,-1,'F-6','','','valid',0,'','real'),(423,7,27,1,'F-7','','','valid',1,'','real'),(424,8,27,-1,'F-8','','','not_valid',0,'','real'),(425,9,27,-1,'F-9','','','not_valid',0,'','real'),(426,10,27,-1,'F-10','','','not_valid',0,'','real'),(427,11,27,-1,'F-11','','','not_valid',0,'','real'),(428,12,27,-1,'F-12','','','not_valid',0,'','real'),(429,13,27,-1,'F-13','','','not_valid',0,'','real'),(430,14,27,-1,'F-14','','','not_valid',0,'','real'),(431,15,27,-1,'F-15','','','not_valid',0,'','real'),(432,16,27,-1,'F-16','','','not_valid',0,'','real'),(433,1,28,10,'F-1','','','work',1,'','real'),(434,2,28,4,'F-2','','','work',1,'','real'),(435,3,28,11,'F-3','','','work',1,'','real'),(436,4,28,-1,'F-4','','','valid',0,'','real'),(437,5,28,12,'F-5','','','valid',1,'','real'),(438,6,28,-1,'F-6','','','valid',0,'','real'),(439,7,28,1,'F-7','','','valid',1,'','real'),(440,8,28,-1,'F-8','','','not_valid',0,'','real'),(441,9,28,-1,'F-9','','','not_valid',0,'','real'),(442,10,28,-1,'F-10','','','not_valid',0,'','real'),(443,11,28,-1,'F-11','','','not_valid',0,'','real'),(444,12,28,-1,'F-12','','','not_valid',0,'','real'),(445,13,28,-1,'F-13','','','not_valid',0,'','real'),(446,14,28,-1,'F-14','','','not_valid',0,'','real'),(447,15,28,-1,'F-15','','','not_valid',0,'','real'),(448,16,28,-1,'F-16','','','not_valid',0,'','real'),(449,1,29,10,'F-1','','','work',1,'','real'),(450,2,29,4,'F-2','','','work',1,'','real'),(451,3,29,11,'F-3','','','work',1,'','real'),(452,4,29,-1,'F-4','','','valid',0,'','real'),(453,5,29,12,'F-5','','','valid',1,'','real'),(454,6,29,-1,'F-6','','','valid',0,'','real'),(455,7,29,1,'F-7','','','valid',1,'','real'),(456,8,29,-1,'F-8','','','not_valid',0,'','real'),(457,9,29,-1,'F-9','','','not_valid',0,'','real'),(458,10,29,-1,'F-10','','','not_valid',0,'','real'),(459,11,29,-1,'F-11','','','not_valid',0,'','real'),(460,12,29,-1,'F-12','','','not_valid',0,'','real'),(461,13,29,-1,'F-13','','','not_valid',0,'','real'),(462,14,29,-1,'F-14','','','not_valid',0,'','real'),(463,15,29,-1,'F-15','','','not_valid',0,'','real'),(464,16,29,-1,'F-16','','','not_valid',0,'','real'),(465,1,30,10,'F-1','','','work',1,'','real'),(466,2,30,4,'F-2','','','work',1,'','real'),(467,3,30,11,'F-3','','','work',1,'','real'),(468,4,30,-1,'F-4','','','valid',0,'','real'),(469,5,30,12,'F-5','','','valid',1,'','real'),(470,6,30,-1,'F-6','','','valid',0,'','real'),(471,7,30,1,'F-7','','','valid',1,'','real'),(472,8,30,-1,'F-8','','','not_valid',0,'','real'),(473,9,30,-1,'F-9','','','not_valid',0,'','real'),(474,10,30,-1,'F-10','','','not_valid',0,'','real'),(475,11,30,-1,'F-11','','','not_valid',0,'','real'),(476,12,30,-1,'F-12','','','not_valid',0,'','real'),(477,13,30,-1,'F-13','','','not_valid',0,'','real'),(478,14,30,-1,'F-14','','','not_valid',0,'','real'),(479,15,30,-1,'F-15','','','not_valid',0,'','real'),(480,16,30,-1,'F-16','','','not_valid',0,'','real'),(481,1,31,6,'F-1','','','work',1,'','real'),(482,2,31,7,'F-2','','','work',1,'','real'),(483,3,31,4,'F-3','','','work',1,'','real'),(484,4,31,10,'F-4','','','valid',1,'','real'),(485,5,31,5,'F-5','','','valid',1,'','real'),(486,6,31,3,'F-6','','','valid',1,'','real'),(487,7,31,-1,'F-7','','','valid',1,'','real'),(488,8,31,1,'F-8','','','not_valid',1,'','real'),(489,9,31,-1,'F-9','','','not_valid',1,'','real'),(490,10,31,-1,'F-10','','','not_valid',0,'','real'),(491,11,31,-1,'F-11','','','not_valid',0,'','real'),(492,12,31,-1,'F-12','','','not_valid',0,'','real'),(493,13,31,-1,'F-13','','','not_valid',0,'','real'),(494,14,31,-1,'F-14','','','not_valid',0,'','real'),(495,15,31,-1,'F-15','','','not_valid',0,'','real'),(496,16,31,-1,'F-16','','','not_valid',0,'','real'),(497,1,32,6,'F-1','','','work',1,'','real'),(498,2,32,7,'F-2','','','work',1,'','real'),(499,3,32,4,'F-3','','','work',1,'','real'),(500,4,32,10,'F-4','','','valid',1,'','real'),(501,5,32,5,'F-5','','','valid',1,'','real'),(502,6,32,3,'F-6','','','valid',1,'','real'),(503,7,32,-1,'F-7','','','valid',1,'','real'),(504,8,32,1,'F-8','','','not_valid',1,'','real'),(505,9,32,-1,'F-9','','','not_valid',1,'','real'),(506,10,32,-1,'F-10','','','not_valid',0,'','real'),(507,11,32,-1,'F-11','','','not_valid',0,'','real'),(508,12,32,-1,'F-12','','','not_valid',0,'','real'),(509,13,32,-1,'F-13','','','not_valid',0,'','real'),(510,14,32,-1,'F-14','','','not_valid',0,'','real'),(511,15,32,-1,'F-15','','','not_valid',0,'','real'),(512,16,32,-1,'F-16','','','not_valid',0,'','real'),(513,1,33,6,'F-1','','','work',1,'','real'),(514,2,33,11,'F-2','','','work',1,'','real'),(515,3,33,4,'F-3','','','work',1,'','real'),(516,4,33,10,'F-4','','','valid',1,'','real'),(517,5,33,5,'F-5','','','valid',1,'','real'),(518,6,33,8,'F-6','','','valid',1,'','real'),(519,7,33,-1,'F-7','','','valid',1,'','real'),(520,8,33,1,'F-8','','','not_valid',1,'','real'),(521,9,33,-1,'F-9','','','not_valid',1,'','real'),(522,10,33,-1,'F-10','','','not_valid',0,'','real'),(523,11,33,-1,'F-11','','','not_valid',0,'','real'),(524,12,33,-1,'F-12','','','not_valid',0,'','real'),(525,13,33,-1,'F-13','','','not_valid',0,'','real'),(526,14,33,-1,'F-14','','','not_valid',0,'','real'),(527,15,33,-1,'F-15','','','not_valid',0,'','real'),(528,16,33,-1,'F-16','','','not_valid',0,'','real'),(529,1,34,6,'F-1','','','work',1,'','real'),(530,2,34,7,'F-2','','','work',1,'','real'),(531,3,34,4,'F-3','','','work',1,'','real'),(532,4,34,10,'F-4','','','valid',1,'','real'),(533,5,34,5,'F-5','','','valid',1,'','real'),(534,6,34,8,'F-6','','','valid',1,'','real'),(535,7,34,11,'F-7','','','valid',1,'','real'),(536,8,34,1,'F-8','','','not_valid',1,'','real'),(537,9,34,-1,'F-9','','','not_valid',1,'','real'),(538,10,34,-1,'F-10','','','not_valid',0,'','real'),(539,11,34,-1,'F-11','','','not_valid',0,'','real'),(540,12,34,-1,'F-12','','','not_valid',0,'','real'),(541,13,34,-1,'F-13','','','not_valid',0,'','real'),(542,14,34,-1,'F-14','','','not_valid',0,'','real'),(543,15,34,-1,'F-15','','','not_valid',0,'','real'),(544,16,34,-1,'F-16','','','not_valid',0,'','real'),(545,1,35,6,'F-1','','','work',1,'','real'),(546,2,35,7,'F-2','','','work',1,'','real'),(547,3,35,4,'F-3','','','work',1,'','real'),(548,4,35,10,'F-4','','','valid',1,'','real'),(549,5,35,5,'F-5','','','valid',1,'','real'),(550,6,35,8,'F-6','','','valid',1,'','real'),(551,7,35,11,'F-7','','','valid',1,'','real'),(552,8,35,1,'F-8','','','not_valid',1,'','real'),(553,9,35,-1,'F-9','','','not_valid',1,'','real'),(554,10,35,-1,'F-10','','','not_valid',0,'','real'),(555,11,35,-1,'F-11','','','not_valid',0,'','real'),(556,12,35,-1,'F-12','','','not_valid',0,'','real'),(557,13,35,-1,'F-13','','','not_valid',0,'','real'),(558,14,35,-1,'F-14','','','not_valid',0,'','real'),(559,15,35,-1,'F-15','','','not_valid',0,'','real'),(560,16,35,-1,'F-16','','','not_valid',0,'','real'),(561,1,36,6,'F-1','','','work',1,'','real'),(562,2,36,11,'F-2','','','work',1,'','real'),(563,3,36,4,'F-3','','','work',1,'','real'),(564,4,36,10,'F-4','','','valid',1,'','real'),(565,5,36,5,'F-5','','','valid',1,'','real'),(566,6,36,8,'F-6','','','valid',1,'','real'),(567,7,36,-1,'F-7','','','valid',1,'','real'),(568,8,36,1,'F-8','','','not_valid',1,'','real'),(569,9,36,-1,'F-9','','','not_valid',1,'','real'),(570,10,36,-1,'F-10','','','not_valid',0,'','real'),(571,11,36,-1,'F-11','','','not_valid',0,'','real'),(572,12,36,-1,'F-12','','','not_valid',0,'','real'),(573,13,36,-1,'F-13','','','not_valid',0,'','real'),(574,14,36,-1,'F-14','','','not_valid',0,'','real'),(575,15,36,-1,'F-15','','','not_valid',0,'','real'),(576,16,36,-1,'F-16','','','not_valid',0,'','real'),(577,1,37,6,'F-1','','','work',1,'','real'),(578,2,37,3,'F-2','','','work',1,'','real'),(579,3,37,4,'F-3','','','work',1,'','real'),(580,4,37,10,'F-4','','','valid',1,'','real'),(581,5,37,5,'F-5','','','valid',1,'','real'),(582,6,37,8,'F-6','','','valid',1,'','real'),(583,7,37,-1,'F-7','','','valid',1,'','real'),(584,8,37,1,'F-8','','','not_valid',1,'','real'),(585,9,37,-1,'F-9','','','not_valid',0,'','real'),(586,10,37,-1,'F-10','','','not_valid',0,'','real'),(587,11,37,-1,'F-11','','','not_valid',0,'','real'),(588,12,37,-1,'F-12','','','not_valid',0,'','real'),(589,13,37,-1,'F-13','','','not_valid',0,'','real'),(590,14,37,-1,'F-14','','','not_valid',0,'','real'),(591,15,37,-1,'F-15','','','not_valid',0,'','real'),(592,16,37,-1,'F-16','','','not_valid',0,'','real'),(593,1,38,6,'F-1','','','work',1,'','real'),(594,2,38,11,'F-2','','','work',1,'','real'),(595,3,38,4,'F-3','','','work',1,'','real'),(596,4,38,10,'F-4','','','valid',1,'','real'),(597,5,38,5,'F-5','','','valid',1,'','real'),(598,6,38,8,'F-6','','','valid',1,'','real'),(599,7,38,2,'F-7','','','valid',1,'','real'),(600,8,38,1,'F-8','','','not_valid',1,'','real'),(601,9,38,-1,'F-9','','','not_valid',0,'','real'),(602,10,38,-1,'F-10','','','not_valid',0,'','real'),(603,11,38,-1,'F-11','','','not_valid',0,'','real'),(604,12,38,-1,'F-12','','','not_valid',0,'','real'),(605,13,38,-1,'F-13','','','not_valid',0,'','real'),(606,14,38,-1,'F-14','','','not_valid',0,'','real'),(607,15,38,-1,'F-15','','','not_valid',0,'','real'),(608,16,38,-1,'F-16','','','not_valid',0,'','real'),(609,1,39,6,'F-1','','','work',1,'','real'),(610,2,39,7,'F-2','','','work',1,'','real'),(611,3,39,4,'F-3','','','work',1,'','real'),(612,4,39,9,'F-4','','','valid',1,'','real'),(613,5,39,5,'F-5','','','valid',1,'','real'),(614,6,39,8,'F-6','','','valid',1,'','real'),(615,7,39,2,'F-7','','','valid',1,'','real'),(616,8,39,1,'F-8','','','not_valid',1,'','real'),(617,9,39,-1,'F-9','','','not_valid',0,'','real'),(618,10,39,-1,'F-10','','','not_valid',0,'','real'),(619,11,39,-1,'F-11','','','not_valid',0,'','real'),(620,12,39,-1,'F-12','','','not_valid',0,'','real'),(621,13,39,-1,'F-13','','','not_valid',0,'','real'),(622,14,39,-1,'F-14','','','not_valid',0,'','real'),(623,15,39,-1,'F-15','','','not_valid',0,'','real'),(624,16,39,-1,'F-16','','','not_valid',0,'','real');
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
  `template_id` int(10) DEFAULT NULL,
  `name` varchar(512) NOT NULL,
  `code` varchar(512) NOT NULL,
  `ip` varchar(32) NOT NULL,
  `port` int(10) NOT NULL DEFAULT '900',
  `local_port` int(10) NOT NULL DEFAULT '0',
  `data_fix_period` int(10) NOT NULL DEFAULT '10',
  `peak_average_period` int(10) NOT NULL DEFAULT '1',
  `pwd` varchar(512) NOT NULL DEFAULT '',
  `mac` varchar(16) NOT NULL,
  `work_type` enum('amplitude','average') NOT NULL,
  `time_idle_run` int(10) NOT NULL DEFAULT '0' COMMENT 'Время холостого хода, сек',
  `rec_type` enum('real','template') NOT NULL DEFAULT 'real',
  `s_values` varchar(128) DEFAULT '1,1,1,1',
  `reasons_timeout_table` varchar(128) DEFAULT '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',
  `main_detector_digit` int(10) NOT NULL DEFAULT '0',
  `main_detector_analog` int(10) NOT NULL DEFAULT '0',
  `full_name` text NOT NULL,
  `number` varchar(128) NOT NULL DEFAULT '',
  `place_number` varchar(128) NOT NULL DEFAULT '',
  `span_number` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `code` (`code`(255)),
  KEY `mac` (`mac`),
  KEY `mac_rec_type` (`mac`,`rec_type`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `machine`
--

LOCK TABLES `machine` WRITE;
/*!40000 ALTER TABLE `machine` DISABLE KEYS */;
INSERT INTO `machine` VALUES (1,NULL,'Станок №','machine_','10.128.132.',900,0,10,1,'PxZt00','','amplitude',0,'template','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',0,0,'','','',''),(2,1,'1м63мф101','№2398','10.128.132.103',900,0,10,1,'PxZt0003','7E4AE2C2875E','amplitude',0,'real','2,2,2,2','0,30,0,0,0,0,0,0,0,0,0,0,0,0,0,0',3,0,'Токарно-винторезный станок','2398','464','XII'),(3,NULL,'16м30ф31','№2404','10.128.132.104',900,0,10,1,'PxZt0004','D61C9CC602DE','amplitude',0,'real','2,2,2,2','0,30,0,0,0,0,0,0,0,0,0,0,0,0,0,0',3,2,'Токарный патронно-центровый станок с ЧПУ','2404','492','XII'),(4,NULL,'1м63мф101','№2592','10.128.132.128',900,0,10,1,'PxZt0028','E6A1E63C2AD6','amplitude',0,'real','2,2,2,2','0,30,0,0,0,0,0,0,0,0,0,0,0,0,0,0',3,3,'Токарно-винторезный станок','2592','463','XII'),(5,NULL,'РТ-569','№2415','10.128.132.134',900,0,10,1,'PxZt0034','F63E29A76554','amplitude',0,'real','2,2,2,2','0,30,0,0,0,0,0,0,0,0,0,0,0,0,0,0',3,3,'Токарно-винторезный станок','2415','425','XI'),(6,NULL,'2а656-ф11','№3032','10.128.132.135',900,0,10,1,'PxZt0035','06A9B6B284E6','amplitude',0,'real','2,2,2,3','0,30,0,0,0,0,0,0,0,0,0,0,0,0,0,0',3,0,'Горизонтально-расточной станок с ЧПУ','3032','431','XI'),(10,NULL,'1К62','№2500','10.128.132.143',900,0,10,1,'PxZt0043','DAC985916776','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',3,3,'Токарно-винторезный станок','2500','168','VII'),(11,NULL,'16к20','№2556','10.128.132.145',900,0,10,1,'PxZt0045','865FB4E9A54B','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',3,3,'Токарно-винторезный станок','2556','171','VII'),(12,NULL,'1К62','№2410(11)','10.128.132.147',900,0,10,1,'PxZt0047','E21E4EDC8217','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',3,0,'Токарно-винторезный станок','2410(11)','170','VII'),(13,NULL,'Мк-6165','№2375','10.128.132.151',900,0,10,1,'PxZt0051','E67C3F93E858','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',3,3,'Токарно-винторезный станок','2375','173','VII'),(14,NULL,'1м63мф101','№2382','10.128.132.149',900,0,10,1,'PxZt0049','3678D7FAE740','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',3,3,'Токарный станок с индикацией','2382','175','VII'),(15,NULL,'16а20ф3с39','№2581','10.128.132.146',900,0,10,1,'PxZt0046','9EB8BB94B5C4','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',3,3,'Токарно-винторезный станок','2581','207','VII'),(16,NULL,'1516Ф3','№2935','10.128.132.121',900,0,10,1,'PxZt0021','C2A9B8E4EE5D','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',3,3,'Токарно-карусельный ст-к с ЧПУ','2935','448','XII'),(17,NULL,'1516Ф3','№2934(33)','10.128.132.118',900,0,10,1,'PxZt0018','72E18D85128A','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',2,2,'Токарно-карусельный ст-к с ЧПУ','2934(33)','449','XII'),(18,NULL,'65А60Ф4-11','№3473','10.128.132.172',900,0,10,1,'PxZt0072','3A0E2D0D930D','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',3,1,'Вертикал.-фрезерный станок с ЧПУ','3473','452','XII'),(19,NULL,'165','№2493','10.128.132.130',900,0,10,1,'PxZt0030','96BE20137AC9','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',3,0,'Токарно-винторезный станок','165','427','XII'),(20,NULL,'1М63МФ101','№2595','10.128.132.154',900,0,10,1,'PxZt0054','46067062CEF0','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',3,3,'Токарно-винторезный станок','2598','174','VII'),(21,NULL,'7Б210','№3214','10.128.132.116',900,0,10,1,'PxZt0016','8E9BD4D73625','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',3,0,'Продольно-строгальный станок','3214','215','VII'),(22,NULL,'С11МТ','№2381','10.128.132.129',900,0,10,1,'PxZt0029','6AFCCB3DE0D2','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',3,3,'Токарно-винторезный станок','2381','172','VII'),(23,NULL,'16А20Ф3С39','№2582','10.128.132.180',900,0,10,1,'PxZt0080','66D5F3A3B9B9','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',3,3,'Токарно-винторезный станок','2582','208','VII'),(24,NULL,'FW400V/2','№3468','10.128.132.136',900,0,10,1,'PxZt0036','62AF8BD24D2D','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',3,0,'Вертик.-фрезерный станок ГДР','3468','470','XII'),(25,NULL,'РТ-2505','№2377','10.128.132.115',900,0,10,1,'PxZt0015','4621E0F0B6B0','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',0,3,'Токарно-винторезный станок','2377','424','XI'),(26,NULL,'Х53К/1','№3369','10.128.132.173',900,0,10,1,'PxZt0073','7ECF02ACE3B6','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',0,3,'Вертик.-фрезерный станок Китай','3369','469','XII'),(27,NULL,'DLZ-800/111','№2539','10.128.132.169',900,0,10,1,'PxZt0069','7ABEBB7AC384','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',0,3,'Токарно-винторезный станок','2539','108','VI'),(28,NULL,'PT755Ф341','№2583','10.128.132.112',900,0,10,1,'PxZt0012','1E955753F29D','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',0,0,'Токарный станок с ЧПУ','2583','0','XII'),(29,NULL,'6М612Ф11','№3474','10.128.132.177',900,0,10,1,'PxZt0077','5239D8D6FE49','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',0,0,'Продольно-фрезерно-расточной станок','1','100','VI'),(30,NULL,'1А671Ф1-34','№2548 (2336)','10.128.132.152',900,0,10,1,'PxZt0052','','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',0,0,'Токарно-винторезный станок спец.','2548 (2336)','145','VI'),(31,NULL,'AJAN','бн','10.128.132.6',0,0,10,1,'old','1','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',0,0,'Плазма газорез с ЧПУ','','549','ЦШ'),(32,NULL,'Maxigraph','бн','10.128.132.5',0,0,10,1,'old','2','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',0,0,'Плазма газорез с ЧПУ','','543','ЦШ'),(33,NULL,'Mynx','бн','10.128.132.7',0,0,10,1,'old','3','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',0,0,'Фрезерный с ЧПУ','','487','XII'),(34,NULL,'VTC 40/50','БН','10.128.132.201',0,0,10,1,'PxZt00','4','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',0,0,'Карусельный токарно фрезерный','','84','V'),(35,NULL,'HFB','БН','10.128.132.202',0,0,10,1,'old','5','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',0,0,'Фрезерный с ЧПУ','','93','V'),(36,NULL,'PUMA2500','БН','10.128.132.9',0,0,10,1,'old','6','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',0,0,'Токарный с ЧПУ','','423','XII'),(37,NULL,'Bystronyc','БН','10.128.132.203',0,0,10,1,'old','7','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',0,0,'Лазерная резка с ЧПУ','','4','ЗП'),(38,NULL,'VM711','БН','10.128.132.12',0,0,10,1,'old','8','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',0,0,'Фрезерный с ЧПУ','','444','XII'),(39,NULL,'VM900','БН','10.128.132.11',0,0,10,1,'old','9','amplitude',0,'real','1,1,1,1','0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0',0,0,'Фрезерный с ЧПУ','','459','XII');
/*!40000 ALTER TABLE `machine` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `machine_2_group`
--

DROP TABLE IF EXISTS `machine_2_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `machine_2_group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `machine_id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `machine_id_group_id` (`machine_id`,`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `machine_2_group`
--

LOCK TABLES `machine_2_group` WRITE;
/*!40000 ALTER TABLE `machine_2_group` DISABLE KEYS */;
INSERT INTO `machine_2_group` VALUES (56,0,2),(1,1,1),(46,2,2),(54,3,2),(45,4,2),(43,5,2),(50,6,4),(35,10,2),(37,11,2),(36,12,2),(39,13,2),(47,14,2),(41,15,2),(13,16,1),(14,17,1),(49,18,3),(44,19,2),(40,20,2),(51,21,5),(38,22,2),(42,23,2),(55,24,3),(57,25,2),(58,26,3),(59,27,2),(60,28,2),(61,29,12),(62,30,2),(70,31,13),(71,32,13),(66,33,7),(65,34,11),(72,35,11),(67,36,7),(73,37,13),(68,38,7),(64,39,7);
/*!40000 ALTER TABLE `machine_2_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `machine_config`
--

DROP TABLE IF EXISTS `machine_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `machine_config` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `machine_id` int(10) NOT NULL,
  `condition_number` int(10) DEFAULT NULL,
  `machine_state_id` int(10) DEFAULT NULL,
  `apply_number` int(10) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `machine_id` (`machine_id`),
  KEY `machine_state_id` (`machine_state_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2126 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `machine_config`
--

LOCK TABLES `machine_config` WRITE;
/*!40000 ALTER TABLE `machine_config` DISABLE KEYS */;
INSERT INTO `machine_config` VALUES (46,1,0,3,3,NULL),(47,1,0,2,3,NULL),(60,8,4,3,3,100),(61,8,4,2,3,20),(62,8,12,3,3,300),(63,8,12,2,3,100),(64,9,4,3,3,100),(1962,39,12,3,3,100),(1963,39,12,2,3,5),(1964,39,12,1,3,2),(1965,34,12,3,3,100),(1966,34,12,2,3,10),(1967,34,12,1,3,5),(1968,33,12,3,3,50),(1969,33,12,2,3,25),(1970,33,12,1,3,15),(1971,36,12,3,3,120),(1972,36,12,2,3,20),(1973,36,12,1,3,2),(1974,38,12,3,3,5),(1975,38,12,2,3,3),(1976,38,12,1,3,2),(1980,31,12,3,3,10),(1981,31,12,2,3,5),(1982,31,12,1,3,1),(1983,32,12,3,3,30),(1984,32,12,2,3,10),(1985,32,12,1,3,5),(1986,35,12,3,3,10),(1987,35,12,2,3,3),(1988,35,12,1,3,2),(1989,37,12,3,3,150),(1990,37,12,2,3,5),(1991,37,12,1,3,2),(1997,16,3,3,3,1),(1998,16,3,2,3,1),(1999,16,3,1,3,1),(2000,16,15,3,3,250),(2001,16,15,2,3,10),(2002,17,2,3,3,1),(2003,17,2,2,3,1),(2004,17,2,1,3,1),(2005,17,14,3,3,38),(2006,17,14,2,3,45),(2007,19,3,3,3,1),(2008,19,3,2,3,1),(2009,19,3,1,3,1),(2010,19,12,3,3,350),(2011,19,12,2,3,10),(2012,15,3,3,3,1),(2013,15,3,2,3,1),(2014,15,3,1,3,1),(2015,15,15,3,3,230),(2016,15,15,2,3,10),(2017,23,3,3,3,1),(2018,23,3,2,3,1),(2019,23,3,1,3,1),(2020,23,15,3,3,178),(2021,23,15,2,3,10),(2022,11,3,3,3,1),(2023,11,3,2,3,1),(2024,11,3,1,3,1),(2025,11,15,3,3,325),(2026,11,15,2,3,10),(2027,3,3,3,3,1),(2028,3,3,2,3,1),(2029,3,3,1,3,1),(2030,3,14,3,3,300),(2031,3,14,2,3,10),(2032,10,3,3,3,1),(2033,10,3,2,3,1),(2034,10,3,1,3,1),(2035,10,15,3,3,300),(2036,10,15,2,3,10),(2037,12,3,3,3,1),(2038,12,3,2,3,1),(2039,12,3,1,3,1),(2040,12,12,3,3,260),(2041,12,12,2,3,10),(2042,2,3,3,3,1),(2043,2,3,2,3,1),(2044,2,3,1,3,1),(2045,2,12,3,3,300),(2046,2,12,2,3,10),(2047,4,3,3,3,1),(2048,4,3,2,3,1),(2049,4,3,1,3,1),(2050,4,15,3,3,300),(2051,4,15,2,3,10),(2052,14,3,3,3,1),(2053,14,3,2,3,1),(2054,14,3,1,3,1),(2055,14,15,3,3,310),(2056,14,15,2,3,10),(2057,20,3,3,3,1),(2058,20,3,2,3,1),(2059,20,3,1,3,1),(2060,20,15,3,3,210),(2061,20,15,2,3,10),(2062,6,3,3,3,1),(2063,6,3,2,3,1),(2064,6,3,1,3,1),(2065,6,12,3,3,270),(2066,6,12,2,3,10),(2067,18,3,3,3,1),(2068,18,3,2,3,1),(2069,18,3,1,3,1),(2070,18,12,3,3,350),(2071,18,12,2,3,10),(2072,18,13,3,3,350),(2073,18,13,2,3,10),(2074,18,14,3,3,350),(2075,18,14,2,3,10),(2076,18,15,3,3,350),(2077,18,15,2,3,10),(2078,29,0,3,3,1),(2079,29,0,2,3,1),(2080,29,0,1,3,1),(2081,21,3,3,3,1),(2082,21,3,2,3,1),(2083,21,3,1,3,1),(2084,21,12,3,3,190),(2085,21,12,2,3,10),(2086,27,0,3,3,1),(2087,27,0,2,3,1),(2088,27,0,1,3,1),(2089,27,15,3,3,330),(2090,27,15,2,3,10),(2091,24,3,3,3,1),(2092,24,3,2,3,1),(2093,24,3,1,3,1),(2094,24,12,3,3,190),(2095,24,12,2,3,10),(2101,25,0,3,3,1),(2102,25,0,2,3,1),(2103,25,0,1,3,1),(2104,25,15,3,3,150),(2105,25,15,2,3,148),(2106,5,3,3,3,1),(2107,5,3,2,3,1),(2108,5,3,1,3,1),(2109,5,15,3,3,291),(2110,5,15,2,3,10),(2111,22,3,3,3,1),(2112,22,3,2,3,1),(2113,22,3,1,3,1),(2114,22,15,3,3,300),(2115,22,15,2,3,10),(2116,26,0,3,3,1),(2117,26,0,2,3,1),(2118,26,0,1,3,1),(2119,26,15,3,3,90),(2120,26,15,2,3,10),(2121,13,3,3,3,1),(2122,13,3,2,3,1),(2123,13,3,1,3,1),(2124,13,15,3,3,340),(2125,13,15,2,3,10);
/*!40000 ALTER TABLE `machine_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `machine_event`
--

DROP TABLE IF EXISTS `machine_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `machine_event` (
  `id` int(10) NOT NULL,
  `code` varchar(512) NOT NULL,
  `name` varchar(512) NOT NULL DEFAULT '',
  `descr` text NOT NULL,
  `color` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `code` (`code`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `machine_event`
--

LOCK TABLES `machine_event` WRITE;
/*!40000 ALTER TABLE `machine_event` DISABLE KEYS */;
INSERT INTO `machine_event` VALUES (1,'crash','Авария','Требуется диагностика','#f50a0a'),(2,'no_instrument','Нет инструмента','Отсутствует инструмент','#6d7373'),(3,'no_gaz','Нет газа','Отсутствует газ','#15cee6'),(4,'no_detail','Нет наряда','Отсутствует наряд','#d3d6cb'),(5,'no_programm','Нет программы','Отсутствует программа','#14e055'),(6,'no_provision','Нет заготовки','Нет материала','#CFAE40'),(7,'wait_crane','Ожидание крана','Требуется кран','#FF8474'),(8,'no_equipment','Нет оснастки','Требуется оснастка','#CF8440'),(9,'setup_workblank','Установка заготовки','','#4069E2'),(10,'PZV','ПЗВ','Подготовительно-заключительное время','#f2ff00'),(11,'chek','Контроль','Контроль заготовки','#1531a1'),(12,'repair','Автономное обслуживание','Автономное обслуживание','#cc2188');
/*!40000 ALTER TABLE `machine_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `machine_group`
--

DROP TABLE IF EXISTS `machine_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `machine_group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(512) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `machine_group`
--

LOCK TABLES `machine_group` WRITE;
/*!40000 ALTER TABLE `machine_group` DISABLE KEYS */;
INSERT INTO `machine_group` VALUES (1,'токарно-карусельная группа'),(2,'токарно-винторезная группа'),(3,'вертикально-фрезерная группа'),(4,'горизонтально-расточная группа'),(5,'продольно-строгальная группа'),(7,'пролет №12'),(8,'пролет №11'),(9,'пролет №7'),(10,'пролет №6'),(11,'пролет №5'),(12,'продольно-фрезерная группа'),(13,'Заготовительное производство');
/*!40000 ALTER TABLE `machine_group` ENABLE KEYS */;
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
INSERT INTO `p2_auth_assignment` VALUES ('admin',1,'','s:0:\"\";'),('admin',3,'','s:0:\"\";'),('editor',1,'','s:0:\"\";'),('editor',2,'','s:0:\"\";'),('editor',3,'','s:0:\"\";'),('member',1,'','s:0:\"\";'),('member',2,'','s:0:\"\";'),('member',3,'','s:0:\"\";'),('user',1,'','s:0:\"\";'),('user',4,'','s:0:\"\";');
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
INSERT INTO `p2_auth_item` VALUES ('admin',2,NULL,NULL,NULL),('createFile',0,NULL,NULL,NULL),('createPage',0,NULL,NULL,NULL),('createUser',0,NULL,NULL,NULL),('createWidget',0,NULL,NULL,NULL),('deleteFile',0,NULL,NULL,NULL),('deletePage',0,NULL,NULL,NULL),('deleteUser',0,NULL,NULL,NULL),('deleteWidget',0,NULL,NULL,NULL),('editFile',0,NULL,NULL,NULL),('editor',2,NULL,NULL,NULL),('editPage',0,NULL,NULL,NULL),('editUser',0,NULL,NULL,NULL),('editWidget',0,NULL,NULL,NULL),('fileManager',1,NULL,NULL,NULL),('member',2,NULL,NULL,NULL),('pageManager',1,NULL,NULL,NULL),('smto-EventColorAdmin',0,NULL,NULL,'N;'),('smto-EventColorAdministrating',1,NULL,NULL,'N;'),('smto-EventColorCreate',0,NULL,NULL,'N;'),('smto-EventColorDelete',0,NULL,NULL,'N;'),('smto-EventColorIndex',0,NULL,NULL,'N;'),('smto-EventColorUpdate',0,NULL,NULL,'N;'),('smto-EventColorView',0,NULL,NULL,'N;'),('smto-EventColorViewing',1,NULL,NULL,'N;'),('smto-MachineAdmin',0,NULL,NULL,'N;'),('smto-MachineAdministrating',1,NULL,NULL,'N;'),('smto-MachineCreate',0,NULL,NULL,'N;'),('smto-MachineDataAdmin',0,NULL,NULL,'N;'),('smto-MachineDataAdministrating',1,NULL,NULL,'N;'),('smto-MachineDataCreate',0,NULL,NULL,'N;'),('smto-MachineDataDelete',0,NULL,NULL,'N;'),('smto-MachineDataIndex',0,NULL,NULL,'N;'),('smto-MachineDataUpdate',0,NULL,NULL,'N;'),('smto-MachineDataView',0,NULL,NULL,'N;'),('smto-MachineDataViewing',1,NULL,NULL,'N;'),('smto-MachineDelete',0,NULL,NULL,'N;'),('smto-MachineEventAdmin',0,NULL,NULL,'N;'),('smto-MachineEventAdministrating',1,NULL,NULL,'N;'),('smto-MachineEventCreate',0,NULL,NULL,'N;'),('smto-MachineEventDelete',0,NULL,NULL,'N;'),('smto-MachineEventIndex',0,NULL,NULL,'N;'),('smto-MachineEventUpdate',0,NULL,NULL,'N;'),('smto-MachineEventView',0,NULL,NULL,'N;'),('smto-MachineEventViewing',1,NULL,NULL,'N;'),('smto-MachineGroupAdmin',0,NULL,NULL,'N;'),('smto-MachineGroupAdministrating',1,NULL,NULL,'N;'),('smto-MachineGroupCreate',0,NULL,NULL,'N;'),('smto-MachineGroupDelete',0,NULL,NULL,'N;'),('smto-MachineGroupIndex',0,NULL,NULL,'N;'),('smto-MachineGroupUpdate',0,NULL,NULL,'N;'),('smto-MachineGroupView',0,NULL,NULL,'N;'),('smto-MachineGroupViewing',1,NULL,NULL,'N;'),('smto-MachineIndex',0,NULL,NULL,'N;'),('smto-MachineUpdate',0,NULL,NULL,'N;'),('smto-MachineView',0,NULL,NULL,'N;'),('smto-MachineViewing',1,NULL,NULL,'N;'),('smto-MonitoringAdministrating',1,NULL,NULL,'N;'),('smto-MonitoringMonitor',0,NULL,NULL,'N;'),('smto-MonitoringViewing',1,NULL,NULL,'N;'),('smto-OperatorAdmin',0,NULL,NULL,'N;'),('smto-OperatorAdministrating',1,NULL,NULL,'N;'),('smto-OperatorCreate',0,NULL,NULL,'N;'),('smto-OperatorDelete',0,NULL,NULL,'N;'),('smto-OperatorIndex',0,NULL,NULL,'N;'),('smto-OperatorUpdate',0,NULL,NULL,'N;'),('smto-OperatorView',0,NULL,NULL,'N;'),('smto-OperatorViewing',1,NULL,NULL,'N;'),('smto-ParamAdmin',0,NULL,NULL,'N;'),('smto-ParamAdministrating',1,NULL,NULL,'N;'),('smto-ParamCreate',0,NULL,NULL,'N;'),('smto-ParamDelete',0,NULL,NULL,'N;'),('smto-ParamIndex',0,NULL,NULL,'N;'),('smto-ParamUpdate',0,NULL,NULL,'N;'),('smto-ParamView',0,NULL,NULL,'N;'),('smto-ParamViewing',1,NULL,NULL,'N;'),('smto-ReportAdministrating',1,NULL,NULL,'N;'),('smto-ReportIndex',0,NULL,NULL,'N;'),('smto-ReportLiniar',0,NULL,NULL,'N;'),('smto-ReportMonitoring',0,NULL,NULL,'N;'),('smto-ReportOnline',0,NULL,NULL,'N;'),('smto-ReportReport',0,NULL,NULL,'N;'),('smto-ReportViewing',1,NULL,NULL,'N;'),('user',2,'','','s:0:\"\";'),('userManager',1,NULL,NULL,NULL),('viewFile',0,NULL,NULL,NULL),('viewPage',0,NULL,NULL,NULL),('viewUser',0,NULL,NULL,NULL),('viewWidget',0,NULL,NULL,NULL),('widgetManager',1,NULL,NULL,NULL);
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
  CONSTRAINT `fk_751FF434-A447-407E-B89F-2A640F3268BD` FOREIGN KEY (`child`) REFERENCES `p2_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_776C1569-3744-451E-96E2-D8F1891CC27A` FOREIGN KEY (`parent`) REFERENCES `p2_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p2_auth_item_child`
--

LOCK TABLES `p2_auth_item_child` WRITE;
/*!40000 ALTER TABLE `p2_auth_item_child` DISABLE KEYS */;
INSERT INTO `p2_auth_item_child` VALUES ('fileManager','createFile'),('pageManager','createPage'),('userManager','createUser'),('widgetManager','createWidget'),('fileManager','deleteFile'),('pageManager','deletePage'),('userManager','deleteUser'),('widgetManager','deleteWidget'),('fileManager','editFile'),('pageManager','editPage'),('userManager','editUser'),('widgetManager','editWidget'),('admin','fileManager'),('editor','fileManager'),('admin','pageManager'),('editor','pageManager'),('smto-EventColorAdministrating','smto-EventColorAdmin'),('admin','smto-EventColorAdministrating'),('smto-EventColorAdministrating','smto-EventColorCreate'),('smto-EventColorAdministrating','smto-EventColorDelete'),('smto-EventColorAdministrating','smto-EventColorIndex'),('smto-EventColorAdministrating','smto-EventColorUpdate'),('smto-EventColorAdministrating','smto-EventColorView'),('admin','smto-EventColorViewing'),('smto-MachineAdministrating','smto-MachineAdmin'),('admin','smto-MachineAdministrating'),('editor','smto-MachineAdministrating'),('smto-MachineAdministrating','smto-MachineCreate'),('smto-MachineDataAdministrating','smto-MachineDataAdmin'),('admin','smto-MachineDataAdministrating'),('smto-MachineDataAdministrating','smto-MachineDataCreate'),('smto-MachineDataAdministrating','smto-MachineDataDelete'),('smto-MachineDataAdministrating','smto-MachineDataIndex'),('smto-MachineDataAdministrating','smto-MachineDataUpdate'),('smto-MachineDataAdministrating','smto-MachineDataView'),('admin','smto-MachineDataViewing'),('smto-MachineAdministrating','smto-MachineDelete'),('smto-MachineEventAdministrating','smto-MachineEventAdmin'),('admin','smto-MachineEventAdministrating'),('smto-MachineEventAdministrating','smto-MachineEventCreate'),('smto-MachineEventAdministrating','smto-MachineEventDelete'),('smto-MachineEventAdministrating','smto-MachineEventIndex'),('smto-MachineEventAdministrating','smto-MachineEventUpdate'),('smto-MachineEventAdministrating','smto-MachineEventView'),('admin','smto-MachineEventViewing'),('smto-MachineGroupAdministrating','smto-MachineGroupAdmin'),('admin','smto-MachineGroupAdministrating'),('smto-MachineGroupAdministrating','smto-MachineGroupCreate'),('smto-MachineGroupAdministrating','smto-MachineGroupDelete'),('smto-MachineGroupAdministrating','smto-MachineGroupIndex'),('smto-MachineGroupAdministrating','smto-MachineGroupUpdate'),('smto-MachineGroupAdministrating','smto-MachineGroupView'),('admin','smto-MachineGroupViewing'),('smto-MachineAdministrating','smto-MachineIndex'),('smto-MachineAdministrating','smto-MachineUpdate'),('smto-MachineAdministrating','smto-MachineView'),('admin','smto-MachineViewing'),('editor','smto-MachineViewing'),('admin','smto-MonitoringAdministrating'),('editor','smto-MonitoringAdministrating'),('smto-MonitoringAdministrating','smto-MonitoringMonitor'),('smto-MonitoringViewing','smto-MonitoringMonitor'),('admin','smto-MonitoringViewing'),('editor','smto-MonitoringViewing'),('user','smto-MonitoringViewing'),('smto-OperatorAdministrating','smto-OperatorAdmin'),('admin','smto-OperatorAdministrating'),('smto-OperatorAdministrating','smto-OperatorCreate'),('smto-OperatorAdministrating','smto-OperatorDelete'),('smto-OperatorAdministrating','smto-OperatorIndex'),('smto-OperatorAdministrating','smto-OperatorUpdate'),('smto-OperatorAdministrating','smto-OperatorView'),('admin','smto-OperatorViewing'),('smto-ParamAdministrating','smto-ParamAdmin'),('admin','smto-ParamAdministrating'),('smto-ParamAdministrating','smto-ParamCreate'),('smto-ParamAdministrating','smto-ParamDelete'),('smto-ParamAdministrating','smto-ParamIndex'),('smto-ParamAdministrating','smto-ParamUpdate'),('smto-ParamAdministrating','smto-ParamView'),('admin','smto-ParamViewing'),('admin','smto-ReportAdministrating'),('smto-ReportAdministrating','smto-ReportIndex'),('smto-ReportViewing','smto-ReportIndex'),('smto-ReportAdministrating','smto-ReportLiniar'),('smto-ReportViewing','smto-ReportLiniar'),('smto-ReportAdministrating','smto-ReportMonitoring'),('smto-ReportAdministrating','smto-ReportOnline'),('smto-ReportAdministrating','smto-ReportReport'),('admin','smto-ReportViewing'),('user','smto-ReportViewing'),('admin','userManager'),('editor','userManager'),('fileManager','viewFile'),('pageManager','viewPage'),('userManager','viewUser'),('widgetManager','viewWidget'),('admin','widgetManager'),('editor','widgetManager');
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p2_cell`
--

LOCK TABLES `p2_cell` WRITE;
/*!40000 ALTER TABLE `p2_cell` DISABLE KEYS */;
INSERT INTO `p2_cell` VALUES (1,'p2.widgets.html.P2HtmlWidget','{\"id\":\"1\"}',200,'mainCell','','site','index','','','','','',12),(12,'p2.widgets.html.P2HtmlWidget','{\"id\":\"4\"}',100,'mainCell','','site','page','pageId','','','','',30),(13,'p2.widgets.submenu.P2SubMenuWidget','{\"startNode\":\"1\",\"headline\":\"root\"}',100,'mainCell','','','','','','','','',31),(14,'p2.widgets.html.P2HtmlWidget','',100,'mainCell','p2','default','index','','','','','',33);
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
  CONSTRAINT `fk_p2_info_p2_info1` FOREIGN KEY (`parentId`) REFERENCES `p2_info` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_p2_info_p2_user` FOREIGN KEY (`createdBy`) REFERENCES `p2_user` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `fk_p2_info_p2_user1` FOREIGN KEY (`modifiedBy`) REFERENCES `p2_user` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p2_info`
--

LOCK TABLES `p2_info` WRITE;
/*!40000 ALTER TABLE `p2_info` DISABLE KEYS */;
INSERT INTO `p2_info` VALUES (3,'P2Page',1,NULL,30,NULL,NULL,1,'1970-01-01 00:00:00',1,'1970-01-01 00:00:00',NULL,NULL,NULL,NULL,NULL),(11,'P2Html',1,NULL,30,'',NULL,1,'2009-11-14 20:09:22',1,'2011-08-07 21:26:25','0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL),(12,'P2Cell',1,'0',30,'',NULL,1,'2009-11-14 20:09:27',1,'2011-08-07 21:54:51','0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL),(27,'P2Page',4,NULL,30,'',NULL,1,'2011-08-07 22:14:35',1,'2011-08-08 00:32:49','0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL),(28,'P2Page',5,NULL,30,'',NULL,1,'2011-08-07 22:15:33',1,'2011-08-08 00:54:04','0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL),(29,'P2Html',4,NULL,30,'',NULL,1,'2011-08-07 22:26:30',1,'2011-08-08 00:35:39','0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL),(30,'P2Cell',12,'en_us',30,'',NULL,1,'2011-08-08 00:03:16',1,'2011-08-08 00:55:47','0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL),(31,'P2Cell',13,'ru_ru',30,'',NULL,1,'2011-08-08 00:27:12',1,'2011-08-08 00:30:26','0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL),(32,'P2Page',6,'ru_ru',30,'',NULL,1,'2011-08-08 00:31:27',1,'2011-08-08 00:31:36','0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL),(33,'P2Cell',14,'en_us',30,'',NULL,1,'2011-11-18 00:29:55',1,'2011-11-18 00:29:55','0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p2_log`
--

LOCK TABLES `p2_log` WRITE;
/*!40000 ALTER TABLE `p2_log` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `p2_user`
--

LOCK TABLES `p2_user` WRITE;
/*!40000 ALTER TABLE `p2_user` DISABLE KEYS */;
INSERT INTO `p2_user` VALUES (1,'admin','Website','Administrator','sks.develop@gmail.com',NULL,'09cd426c5c8c1d706d88157c4f4061ff',NULL,NULL,40),(2,'ssv','Сергей','Сиденко','ssv@211.ru',NULL,'fab5512cb1359bac726b1991eba6178c',NULL,NULL,40),(3,'test','test','test','admin@t.t',NULL,'1bc95628980bb5add4e5910e41ccaf77',NULL,NULL,40),(4,'user','user','user','user@smto.ru',NULL,'4297f44b13955235245b2497399d7a93',NULL,NULL,40);
/*!40000 ALTER TABLE `p2_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `param`
--

DROP TABLE IF EXISTS `param`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `param` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `key` varchar(200) NOT NULL,
  `value` text NOT NULL,
  `descr` text NOT NULL,
  `stable` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `param`
--

LOCK TABLES `param` WRITE;
/*!40000 ALTER TABLE `param` DISABLE KEYS */;
INSERT INTO `param` VALUES (1,'title','СМТО','Заголовок сайта',1),(2,'description','СМТО','Тэг description',1),(3,'keywords','СМТО','ключевые слова',1),(4,'machine_data_path','/var/pw/out','Путь к *.dat файлам',1),(5,'machine_config_data_path','/etc/pw/config','ÐŸÑƒÑ‚ÑŒ Ðº Ñ„Ð°Ð¹Ð»Ð°Ð¼ *.cfg',1),(6,'machine_data_path_curr','/var/pw/cr_out','ÐŸÑƒÑ‚ÑŒ Ðº Ñ„Ð°Ð¹Ð»Ð°Ð¼ Ñ Ñ‚ÐµÐºÑƒÑ‰Ð¸Ð¼Ð¸ Ð·Ð½Ð°Ñ‡ÐµÐ½Ð¸ÑÐ¼Ð¸ ÑÑ‚Ð°Ð½ÐºÐ¾Ð²',1);
/*!40000 ALTER TABLE `param` ENABLE KEYS */;
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
INSERT INTO `timetable` VALUES (1,'Первая смена','07:00:00','15:30:00'),(2,'Вторая смена','15:30:00','23:59:59'),(3,'Третья смена','24:00:00','07:00:00');
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

-- Dump completed on 2013-01-08 12:57:00
