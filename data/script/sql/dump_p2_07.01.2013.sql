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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-01-08 12:57:00
