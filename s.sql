-- MySQL dump 10.13  Distrib 5.5.28, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: xschool
-- ------------------------------------------------------
-- Server version	5.5.28-0ubuntu0.12.04.2

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
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `branches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `update_at` date DEFAULT NULL,
  `address` text,
  `phone_no` varchar(255) DEFAULT NULL,
  `principle_name` varchar(255) DEFAULT NULL,
  `principle_contact_no` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branches`
--

LOCK TABLES `branches` WRITE;
/*!40000 ALTER TABLE `branches` DISABLE KEYS */;
INSERT INTO `branches` VALUES (1,'Swami Nagar','2014-04-01','2014-04-01','Tekri','123456','Dr. Lokesh','123456789'),(3,'R.K. Puram','2014-06-17',NULL,'RK Puram','987654','Nahi pata','987654');
/*!40000 ALTER TABLE `branches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `section` varchar(255) DEFAULT NULL,
  `previous_class_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_branch_id` (`branch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes`
--

LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
INSERT INTO `classes` VALUES (24,1,'X','A',NULL),(23,1,'IX','A',NULL),(22,1,'VIII','A',NULL),(21,1,'VII','A',NULL),(20,1,'VI','A',NULL),(19,1,'V','A',NULL),(6,1,'I','A',4),(7,1,'I','B',5),(8,1,'I','C',NULL),(9,1,'II','A',6),(10,1,'II','B',7),(11,1,'II','C',8),(12,1,'III','A',9),(13,1,'III','B',10),(14,1,'III','C',11),(15,1,'IV','A',12),(16,1,'IV','B',13),(17,1,'IV','C',14),(18,1,'IV','D',NULL),(25,1,'XI Science Bio','A',NULL),(26,1,'XI Commerce','A',NULL),(27,1,'Pre Nursury','A',NULL),(28,1,'Nursury','A',NULL),(29,1,'Infant','A',NULL),(30,1,'Prep','A',NULL);
/*!40000 ALTER TABLE `classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exam_class`
--

DROP TABLE IF EXISTS `exam_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) DEFAULT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_session_id` (`session_id`),
  KEY `fk_exam_id` (`exam_id`),
  KEY `fk_class_id` (`class_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exam_class`
--

LOCK TABLES `exam_class` WRITE;
/*!40000 ALTER TABLE `exam_class` DISABLE KEYS */;
/*!40000 ALTER TABLE `exam_class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exams`
--

DROP TABLE IF EXISTS `exams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exams`
--

LOCK TABLES `exams` WRITE;
/*!40000 ALTER TABLE `exams` DISABLE KEYS */;
INSERT INTO `exams` VALUES (1,'First Test'),(2,'Second Test'),(3,'Third Test'),(4,'Half Yearly'),(5,'Yearly');
/*!40000 ALTER TABLE `exams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fees`
--

DROP TABLE IF EXISTS `fees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `default_amount` varchar(255) DEFAULT NULL,
  `distribution` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fees`
--

LOCK TABLES `fees` WRITE;
/*!40000 ALTER TABLE `fees` DISABLE KEYS */;
INSERT INTO `fees` VALUES (1,'Admission Fees','3200','No'),(3,'Annual Fees','400','in_each_emi'),(4,'Exam Fees','750','in_each_emi'),(5,'Publication Fees','300','in_each_emi'),(6,'Cultural Fees','350','in_each_emi'),(7,'Laboratory & Library Fees','300','in_each_emi'),(8,'Sports & SWF Fees','450','in_each_emi'),(9,'Medical Insurance','150','in_each_emi'),(10,'Caution Money','500','No'),(11,'Computer Fees','1400','in_each_emi'),(12,'Tution Fees Pre. Nur to 2nd','10500','in_each_emi'),(13,'Tution Fee 3rd to 5th','11700','in_each_emi'),(14,'Tution Fees 6th To 8th','14700','in_each_emi'),(15,'Tution Fees 9th to 12th','17100','in_each_emi'),(16,'Computer Science & Physical Education','7200','in_each_emi'),(17,'Science Bio & Maths','2400','in_each_emi'),(18,'Van Fees','6400','in_each_emi'),(19,'Bus or Auto Fees','5400','in_each_emi');
/*!40000 ALTER TABLE `fees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fees_amount_for_student_types`
--

DROP TABLE IF EXISTS `fees_amount_for_student_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fees_amount_for_student_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fees_id` int(11) DEFAULT NULL,
  `studenttype_id` int(11) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_fees_id` (`fees_id`),
  KEY `fk_studenttype_id` (`studenttype_id`),
  KEY `fk_session_id` (`session_id`)
) ENGINE=MyISAM AUTO_INCREMENT=199 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fees_amount_for_student_types`
--

LOCK TABLES `fees_amount_for_student_types` WRITE;
/*!40000 ALTER TABLE `fees_amount_for_student_types` DISABLE KEYS */;
INSERT INTO `fees_amount_for_student_types` VALUES (1,1,1,'3200',1),(2,1,2,'0',1),(3,1,3,'1100',1),(4,1,4,'0',1),(5,1,5,'3200',1),(6,1,6,'0',1),(7,1,7,'0',1),(8,1,8,'0',1),(9,1,9,'0',1),(27,3,9,'0',1),(26,3,8,'400',1),(25,3,7,'0',1),(24,3,6,'400',1),(23,3,5,'0',1),(22,3,4,'400',1),(21,3,3,'0',1),(20,3,2,'400',1),(19,3,1,'0',1),(28,4,1,'750',1),(29,4,2,'750',1),(30,4,3,'750',1),(31,4,4,'750',1),(32,4,5,'750',1),(33,4,6,'750',1),(34,4,7,'750',1),(35,4,8,'750',1),(36,4,9,'750',1),(37,5,1,'300',1),(38,5,2,'300',1),(39,5,3,'300',1),(40,5,4,'300',1),(41,5,5,'300',1),(42,5,6,'300',1),(43,5,7,'300',1),(44,5,8,'300',1),(45,5,9,'300',1),(46,6,1,'350',1),(47,6,2,'350',1),(48,6,3,'350',1),(49,6,4,'350',1),(50,6,5,'350',1),(51,6,6,'350',1),(52,6,7,'350',1),(53,6,8,'350',1),(54,6,9,'350',1),(55,7,1,'300',1),(56,7,2,'300',1),(57,7,3,'300',1),(58,7,4,'300',1),(59,7,5,'300',1),(60,7,6,'300',1),(61,7,7,'300',1),(62,7,8,'300',1),(63,7,9,'300',1),(64,8,1,'450',1),(65,8,2,'450',1),(66,8,3,'450',1),(67,8,4,'450',1),(68,8,5,'450',1),(69,8,6,'450',1),(70,8,7,'450',1),(71,8,8,'450',1),(72,8,9,'450',1),(73,9,1,'150',1),(74,9,2,'150',1),(75,9,3,'150',1),(76,9,4,'150',1),(77,9,5,'150',1),(78,9,6,'150',1),(79,9,7,'150',1),(80,9,8,'150',1),(81,9,9,'150',1),(82,10,1,'500',1),(83,10,2,'500',1),(84,10,3,'500',1),(85,10,4,'500',1),(86,10,5,'500',1),(87,10,6,'500',1),(88,10,7,'500',1),(89,10,8,'500',1),(90,10,9,'500',1),(91,11,1,'1400',1),(92,11,2,'1400',1),(93,11,3,'1400',1),(94,11,4,'1400',1),(95,11,5,'1400',1),(96,11,6,'1400',1),(97,11,7,'1400',1),(98,11,8,'1400',1),(99,11,9,'1400',1),(100,12,1,'10500',1),(101,12,2,'10500',1),(102,12,3,'10500',1),(103,12,4,'10500',1),(104,12,5,'10500',1),(105,12,6,'10500',1),(106,12,7,'10500',1),(107,12,8,'10500',1),(108,12,9,'10500',1),(109,13,1,'11700',1),(110,13,2,'11700',1),(111,13,3,'11700',1),(112,13,4,'11700',1),(113,13,5,'11700',1),(114,13,6,'11700',1),(115,13,7,'11700',1),(116,13,8,'11700',1),(117,13,9,'11700',1),(118,14,1,'14700',1),(119,14,2,'14700',1),(120,14,3,'14700',1),(121,14,4,'14700',1),(122,14,5,'14700',1),(123,14,6,'14700',1),(124,14,7,'14700',1),(125,14,8,'14700',1),(126,14,9,'14700',1),(127,15,1,'17100',1),(128,15,2,'17100',1),(129,15,3,'17100',1),(130,15,4,'17100',1),(131,15,5,'17100',1),(132,15,6,'17100',1),(133,15,7,'17100',1),(134,15,8,'17100',1),(135,15,9,'17100',1),(136,16,1,'7200',1),(137,16,2,'7200',1),(138,16,3,'7200',1),(139,16,4,'7200',1),(140,16,5,'7200',1),(141,16,6,'7200',1),(142,16,7,'7200',1),(143,16,8,'7200',1),(144,16,9,'7200',1),(145,17,1,'2400',1),(146,17,2,'2400',1),(147,17,3,'2400',1),(148,17,4,'2400',1),(149,17,5,'2400',1),(150,17,6,'2400',1),(151,17,7,'2400',1),(152,17,8,'2400',1),(153,17,9,'2400',1),(154,18,1,'6400',1),(155,18,2,'6400',1),(156,18,3,'6400',1),(157,18,4,'6400',1),(158,18,5,'6400',1),(159,18,6,'6400',1),(160,18,7,'6400',1),(161,18,8,'6400',1),(162,18,9,'6400',1),(163,19,1,'5400',1),(164,19,2,'5400',1),(165,19,3,'5400',1),(166,19,4,'5400',1),(167,19,5,'5400',1),(168,19,6,'5400',1),(169,19,7,'5400',1),(170,19,8,'5400',1),(171,19,9,'5400',1),(172,1,10,'3200',1),(173,3,10,'400',1),(174,4,10,'750',1),(175,5,10,'300',1),(176,6,10,'350',1),(177,7,10,'300',1),(178,8,10,'450',1),(179,9,10,'150',1),(180,10,10,'500',1),(181,11,10,'1400',1),(182,12,10,'10500',1),(183,13,10,'11700',1),(184,14,10,'14700',1),(185,15,10,'17100',1),(186,16,10,'7200',1),(187,17,10,'2400',1),(188,18,10,'6400',1),(189,19,10,'5400',1);
/*!40000 ALTER TABLE `fees_amount_for_student_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fees_receipts`
--

DROP TABLE IF EXISTS `fees_receipts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fees_receipts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_branch_id` (`branch_id`),
  KEY `fk_student_id` (`student_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fees_receipts`
--

LOCK TABLES `fees_receipts` WRITE;
/*!40000 ALTER TABLE `fees_receipts` DISABLE KEYS */;
INSERT INTO `fees_receipts` VALUES (7,1,'2',3000.00,'2014-06-18',4),(6,1,'1',6000.00,'2014-06-18',4),(8,1,'3',3000.00,'2014-06-18',4);
/*!40000 ALTER TABLE `fees_receipts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fees_transactions`
--

DROP TABLE IF EXISTS `fees_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fees_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_applied_fees_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `submitted_on` date DEFAULT NULL,
  `fees_receipt_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_student_applied_fees_id` (`student_applied_fees_id`),
  KEY `fk_fees_receipt_id` (`fees_receipt_id`),
  KEY `fk_branch_id` (`branch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=139 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fees_transactions`
--

LOCK TABLES `fees_transactions` WRITE;
/*!40000 ALTER TABLE `fees_transactions` DISABLE KEYS */;
INSERT INTO `fees_transactions` VALUES (100,181,100.00,'2014-06-18',6,1),(101,188,187.50,'2014-06-18',6,1),(102,195,75.00,'2014-06-18',6,1),(103,202,87.50,'2014-06-18',6,1),(104,209,75.00,'2014-06-18',6,1),(105,216,112.50,'2014-06-18',6,1),(106,223,37.50,'2014-06-18',6,1),(107,230,500.00,'2014-06-18',6,1),(108,231,350.00,'2014-06-18',6,1),(109,238,4275.00,'2014-06-18',6,1),(110,182,50.00,'2014-06-18',6,1),(111,189,93.75,'2014-06-18',6,1),(112,196,37.50,'2014-06-18',6,1),(113,203,18.75,'2014-06-18',6,1),(114,203,25.00,'2014-06-18',7,1),(115,210,37.50,'2014-06-18',7,1),(116,217,56.25,'2014-06-18',7,1),(117,224,18.75,'2014-06-18',7,1),(118,232,175.00,'2014-06-18',7,1),(119,239,2137.50,'2014-06-18',7,1),(120,183,50.00,'2014-06-18',7,1),(121,190,93.75,'2014-06-18',7,1),(122,197,37.50,'2014-06-18',7,1),(123,204,43.75,'2014-06-18',7,1),(124,211,37.50,'2014-06-18',7,1),(125,218,56.25,'2014-06-18',7,1),(126,225,18.75,'2014-06-18',7,1),(127,233,175.00,'2014-06-18',7,1),(128,240,37.50,'2014-06-18',7,1),(129,240,2100.00,'2014-06-18',8,1),(130,184,50.00,'2014-06-18',8,1),(131,191,93.75,'2014-06-18',8,1),(132,198,37.50,'2014-06-18',8,1),(133,205,43.75,'2014-06-18',8,1),(134,212,37.50,'2014-06-18',8,1),(135,219,56.25,'2014-06-18',8,1),(136,226,18.75,'2014-06-18',8,1),(137,234,175.00,'2014-06-18',8,1),(138,241,387.50,'2014-06-18',8,1);
/*!40000 ALTER TABLE `fees_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feeses_in_classes`
--

DROP TABLE IF EXISTS `feeses_in_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feeses_in_classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) DEFAULT NULL,
  `fees_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_session_id` (`session_id`),
  KEY `fk_fees_id` (`fees_id`),
  KEY `fk_class_id` (`class_id`)
) ENGINE=MyISAM AUTO_INCREMENT=558 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feeses_in_classes`
--

LOCK TABLES `feeses_in_classes` WRITE;
/*!40000 ALTER TABLE `feeses_in_classes` DISABLE KEYS */;
INSERT INTO `feeses_in_classes` VALUES (1,1,1,24),(2,1,3,24),(3,1,4,24),(4,1,5,24),(5,1,6,24),(6,1,7,24),(7,1,8,24),(8,1,9,24),(9,1,10,24),(10,1,11,24),(11,1,15,24),(13,1,1,23),(14,1,3,23),(15,1,4,23),(16,1,5,23),(17,1,6,23),(18,1,7,23),(19,1,8,23),(20,1,9,23),(21,1,10,23),(22,1,11,23),(24,1,15,23),(25,1,1,22),(26,1,3,22),(27,1,4,22),(28,1,6,22),(29,1,5,22),(30,1,7,22),(31,1,9,22),(32,1,8,22),(33,1,10,22),(34,1,11,22),(35,1,14,22),(36,1,1,21),(37,1,3,21),(38,1,4,21),(39,1,5,21),(40,1,6,21),(41,1,7,21),(42,1,8,21),(43,1,9,21),(44,1,10,21),(45,1,11,21),(46,1,14,21),(47,1,14,20),(48,1,6,20),(49,1,7,20),(50,1,8,20),(51,1,9,20),(52,1,10,20),(53,1,11,20),(54,1,1,20),(55,1,3,20),(56,1,4,20),(57,1,5,20),(58,1,1,19),(59,1,3,19),(60,1,4,19),(61,1,13,19),(62,1,5,19),(63,1,6,19),(64,1,7,19),(65,1,8,19),(68,1,11,19),(67,1,10,19),(69,1,9,19),(70,1,1,6),(71,1,3,6),(72,1,4,6),(73,1,6,6),(74,1,5,6),(75,1,8,6),(76,1,9,6),(77,1,7,6),(78,1,11,6),(79,1,10,6),(80,1,12,6),(81,1,1,7),(82,1,3,7),(83,1,4,7),(84,1,5,7),(85,1,6,7),(86,1,7,7),(87,1,8,7),(88,1,9,7),(89,1,10,7),(90,1,11,7),(91,1,12,7),(92,1,1,8),(93,1,3,8),(94,1,4,8),(95,1,5,8),(96,1,6,8),(97,1,7,8),(98,1,8,8),(99,1,10,8),(100,1,9,8),(101,1,11,8),(102,1,12,8),(103,1,1,9),(104,1,3,9),(105,1,4,9),(106,1,5,9),(107,1,6,9),(108,1,7,9),(109,1,8,9),(110,1,9,9),(111,1,10,9),(112,1,11,9),(113,1,12,9),(114,1,1,10),(115,1,3,10),(116,1,4,10),(117,1,5,10),(118,1,6,10),(119,1,7,10),(120,1,8,10),(121,1,9,10),(122,1,10,10),(123,1,11,10),(124,1,12,10),(125,1,1,11),(126,1,12,11),(127,1,3,11),(128,1,4,11),(129,1,5,11),(130,1,6,11),(131,1,7,11),(132,1,8,11),(133,1,9,11),(134,1,10,11),(135,1,11,11),(136,1,11,12),(137,1,10,12),(138,1,9,12),(139,1,8,12),(140,1,7,12),(141,1,6,12),(142,1,5,12),(143,1,4,12),(144,1,3,12),(145,1,1,12),(147,1,13,12),(148,1,13,13),(149,1,4,13),(150,1,3,13),(151,1,1,13),(152,1,5,13),(153,1,6,13),(154,1,7,13),(155,1,9,13),(156,1,8,13),(159,1,10,13),(158,1,11,13),(160,1,1,14),(161,1,3,14),(162,1,13,14),(163,1,4,14),(164,1,5,14),(165,1,6,14),(166,1,7,14),(167,1,8,14),(168,1,9,14),(169,1,10,14),(170,1,11,14),(171,1,1,15),(172,1,3,15),(173,1,4,15),(174,1,5,15),(175,1,6,15),(176,1,7,15),(177,1,8,15),(178,1,9,15),(179,1,10,15),(180,1,11,15),(181,1,13,15),(182,1,1,16),(183,1,3,16),(184,1,13,16),(185,1,4,16),(186,1,5,16),(187,1,6,16),(188,1,7,16),(189,1,8,16),(190,1,9,16),(191,1,10,16),(192,1,11,16),(193,1,11,17),(194,1,10,17),(195,1,9,17),(196,1,8,17),(197,1,7,17),(198,1,6,17),(199,1,5,17),(200,1,13,17),(201,1,3,17),(202,1,1,17),(203,1,4,17),(204,1,1,18),(205,1,3,18),(206,1,4,18),(207,1,5,18),(208,1,6,18),(209,1,7,18),(210,1,8,18),(211,1,9,18),(212,1,10,18),(213,1,11,18),(214,1,13,18),(215,1,1,25),(216,1,3,25),(217,1,4,25),(218,1,5,25),(219,1,6,25),(220,1,7,25),(221,1,15,25),(222,1,8,25),(223,1,9,25),(224,1,10,25),(225,1,11,25),(226,1,17,25),(227,1,16,25),(228,1,1,26),(229,1,3,26),(230,1,4,26),(231,1,6,26),(232,1,5,26),(233,1,7,26),(234,1,8,26),(235,1,10,26),(236,1,16,26),(237,1,11,26),(238,1,15,26),(239,1,9,26),(240,1,1,27),(241,1,12,27),(242,1,3,27),(243,1,4,27),(244,1,5,27),(245,1,6,27),(246,1,7,27),(247,1,8,27),(249,1,10,27),(250,1,9,27),(251,1,1,28),(252,1,12,28),(253,1,3,28),(254,1,4,28),(255,1,5,28),(256,1,6,28),(257,1,7,28),(258,1,8,28),(259,1,9,28),(260,1,10,28),(261,1,1,29),(262,1,3,29),(263,1,4,29),(264,1,5,29),(265,1,6,29),(266,1,7,29),(267,1,8,29),(268,1,10,29),(269,1,12,29),(270,1,9,29),(271,1,1,30),(272,1,12,30),(273,1,3,30),(274,1,4,30),(275,1,5,30),(276,1,6,30),(277,1,7,30),(278,1,8,30),(279,1,9,30),(280,1,10,30),(282,1,11,29),(283,18,1,24),(284,18,3,24),(285,18,4,24),(286,18,5,24),(287,18,6,24),(288,18,7,24),(289,18,8,24),(290,18,9,24),(291,18,10,24),(292,18,11,24),(293,18,15,24),(294,18,1,23),(295,18,3,23),(296,18,4,23),(297,18,5,23),(298,18,6,23),(299,18,7,23),(300,18,8,23),(301,18,9,23),(302,18,10,23),(303,18,11,23),(304,18,15,23),(305,18,1,22),(306,18,3,22),(307,18,4,22),(308,18,5,22),(309,18,6,22),(310,18,7,22),(311,18,8,22),(312,18,9,22),(313,18,10,22),(314,18,11,22),(315,18,14,22),(316,18,1,21),(317,18,3,21),(318,18,4,21),(319,18,5,21),(320,18,6,21),(321,18,7,21),(322,18,8,21),(323,18,9,21),(324,18,10,21),(325,18,11,21),(326,18,14,21),(327,18,1,20),(328,18,3,20),(329,18,4,20),(330,18,5,20),(331,18,6,20),(332,18,7,20),(333,18,8,20),(334,18,9,20),(335,18,10,20),(336,18,11,20),(337,18,14,20),(338,18,1,19),(339,18,3,19),(340,18,4,19),(341,18,5,19),(342,18,6,19),(343,18,7,19),(344,18,8,19),(345,18,9,19),(346,18,10,19),(347,18,11,19),(348,18,13,19),(349,18,1,6),(350,18,3,6),(351,18,4,6),(352,18,5,6),(353,18,6,6),(354,18,7,6),(355,18,8,6),(356,18,9,6),(357,18,10,6),(358,18,11,6),(359,18,12,6),(360,18,1,7),(361,18,3,7),(362,18,4,7),(363,18,5,7),(364,18,6,7),(365,18,7,7),(366,18,8,7),(367,18,9,7),(368,18,10,7),(369,18,11,7),(370,18,12,7),(371,18,1,8),(372,18,3,8),(373,18,4,8),(374,18,5,8),(375,18,6,8),(376,18,7,8),(377,18,8,8),(378,18,9,8),(379,18,10,8),(380,18,11,8),(381,18,12,8),(382,18,1,9),(383,18,3,9),(384,18,4,9),(385,18,5,9),(386,18,6,9),(387,18,7,9),(388,18,8,9),(389,18,9,9),(390,18,10,9),(391,18,11,9),(392,18,12,9),(393,18,1,10),(394,18,3,10),(395,18,4,10),(396,18,5,10),(397,18,6,10),(398,18,7,10),(399,18,8,10),(400,18,9,10),(401,18,10,10),(402,18,11,10),(403,18,12,10),(404,18,1,11),(405,18,3,11),(406,18,4,11),(407,18,5,11),(408,18,6,11),(409,18,7,11),(410,18,8,11),(411,18,9,11),(412,18,10,11),(413,18,11,11),(414,18,12,11),(415,18,1,12),(416,18,3,12),(417,18,4,12),(418,18,5,12),(419,18,6,12),(420,18,7,12),(421,18,8,12),(422,18,9,12),(423,18,10,12),(424,18,11,12),(425,18,13,12),(426,18,1,13),(427,18,3,13),(428,18,4,13),(429,18,5,13),(430,18,6,13),(431,18,7,13),(432,18,8,13),(433,18,9,13),(434,18,10,13),(435,18,11,13),(436,18,13,13),(437,18,1,14),(438,18,3,14),(439,18,4,14),(440,18,5,14),(441,18,6,14),(442,18,7,14),(443,18,8,14),(444,18,9,14),(445,18,10,14),(446,18,11,14),(447,18,13,14),(448,18,1,15),(449,18,3,15),(450,18,4,15),(451,18,5,15),(452,18,6,15),(453,18,7,15),(454,18,8,15),(455,18,9,15),(456,18,10,15),(457,18,11,15),(458,18,13,15),(459,18,1,16),(460,18,3,16),(461,18,4,16),(462,18,5,16),(463,18,6,16),(464,18,7,16),(465,18,8,16),(466,18,9,16),(467,18,10,16),(468,18,11,16),(469,18,13,16),(470,18,1,17),(471,18,3,17),(472,18,4,17),(473,18,5,17),(474,18,6,17),(475,18,7,17),(476,18,8,17),(477,18,9,17),(478,18,10,17),(479,18,11,17),(480,18,13,17),(481,18,1,18),(482,18,3,18),(483,18,4,18),(484,18,5,18),(485,18,6,18),(486,18,7,18),(487,18,8,18),(488,18,9,18),(489,18,10,18),(490,18,11,18),(491,18,13,18),(492,18,1,25),(493,18,3,25),(494,18,4,25),(495,18,5,25),(496,18,6,25),(497,18,7,25),(498,18,8,25),(499,18,9,25),(500,18,10,25),(501,18,11,25),(502,18,15,25),(503,18,16,25),(504,18,17,25),(505,18,1,26),(506,18,3,26),(507,18,4,26),(508,18,5,26),(509,18,6,26),(510,18,7,26),(511,18,8,26),(512,18,9,26),(513,18,10,26),(514,18,11,26),(515,18,15,26),(516,18,16,26),(517,18,1,27),(518,18,3,27),(519,18,4,27),(520,18,5,27),(521,18,6,27),(522,18,7,27),(523,18,8,27),(524,18,9,27),(525,18,10,27),(526,18,12,27),(527,18,1,28),(528,18,3,28),(529,18,4,28),(530,18,5,28),(531,18,6,28),(532,18,7,28),(533,18,8,28),(534,18,9,28),(535,18,10,28),(536,18,12,28),(537,18,1,29),(538,18,3,29),(539,18,4,29),(540,18,5,29),(541,18,6,29),(542,18,7,29),(543,18,8,29),(544,18,9,29),(545,18,10,29),(546,18,11,29),(547,18,12,29),(548,18,1,30),(549,18,3,30),(550,18,4,30),(551,18,5,30),(552,18,6,30),(553,18,7,30),(554,18,8,30),(555,18,9,30),(556,18,10,30),(557,18,12,30);
/*!40000 ALTER TABLE `feeses_in_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scholars`
--

DROP TABLE IF EXISTS `scholars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scholars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admission_date` date DEFAULT NULL,
  `scholar_no` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `phone_no` varchar(255) DEFAULT NULL,
  `address` text,
  `leaving_date` date DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `cast` varchar(255) DEFAULT NULL,
  `house` varchar(255) DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `brother_sister_name_class` text,
  `form_no` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `previous_school_and_class` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `scholar_no` (`scholar_no`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scholars`
--

LOCK TABLES `scholars` WRITE;
/*!40000 ALTER TABLE `scholars` DISABLE KEYS */;
INSERT INTO `scholars` VALUES (1,'2014-06-17','1','STUDENT 1','F','F','2014-06-26','7766787677','JHHG',NULL,'JHGJHG','','','','','',NULL,''),(2,'2014-06-17','2','t','f','m','2004-06-01','123456789','a',NULL,'OBC','','','','','',NULL,'');
/*!40000 ALTER TABLE `scholars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES (1,'2014-15','2014-04-01','2015-03-31'),(18,'test','2014-06-18','2014-06-18');
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staffs`
--

DROP TABLE IF EXISTS `staffs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staffs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_branch_id` (`branch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staffs`
--

LOCK TABLES `staffs` WRITE;
/*!40000 ALTER TABLE `staffs` DISABLE KEYS */;
INSERT INTO `staffs` VALUES (1,1,'Admin','admin','admin');
/*!40000 ALTER TABLE `staffs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_attendances`
--

DROP TABLE IF EXISTS `student_attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_attendances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_student_id` (`student_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_attendances`
--

LOCK TABLES `student_attendances` WRITE;
/*!40000 ALTER TABLE `student_attendances` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_attendances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_fees_applied`
--

DROP TABLE IF EXISTS `student_fees_applied`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_fees_applied` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `fees_id` int(11) DEFAULT NULL,
  `due_on` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_student_id` (`student_id`),
  KEY `fk_fees_id` (`fees_id`)
) ENGINE=MyISAM AUTO_INCREMENT=245 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_fees_applied`
--

LOCK TABLES `student_fees_applied` WRITE;
/*!40000 ALTER TABLE `student_fees_applied` DISABLE KEYS */;
INSERT INTO `student_fees_applied` VALUES (1,1,'3200',1,'2014-04-01'),(2,1,'187.5',4,'2014-04-01'),(3,1,'93.75',4,'2014-05-01'),(4,1,'93.75',4,'2014-06-01'),(5,1,'93.75',4,'2014-07-01'),(6,1,'93.75',4,'2014-08-01'),(7,1,'93.75',4,'2014-09-01'),(8,1,'93.75',4,'2014-10-01'),(9,1,'75',5,'2014-04-01'),(10,1,'37.5',5,'2014-05-01'),(11,1,'37.5',5,'2014-06-01'),(12,1,'37.5',5,'2014-07-01'),(13,1,'37.5',5,'2014-08-01'),(14,1,'37.5',5,'2014-09-01'),(15,1,'37.5',5,'2014-10-01'),(16,1,'87.5',6,'2014-04-01'),(17,1,'43.75',6,'2014-05-01'),(18,1,'43.75',6,'2014-06-01'),(19,1,'43.75',6,'2014-07-01'),(20,1,'43.75',6,'2014-08-01'),(21,1,'43.75',6,'2014-09-01'),(22,1,'43.75',6,'2014-10-01'),(23,1,'75',7,'2014-04-01'),(24,1,'37.5',7,'2014-05-01'),(25,1,'37.5',7,'2014-06-01'),(26,1,'37.5',7,'2014-07-01'),(27,1,'37.5',7,'2014-08-01'),(28,1,'37.5',7,'2014-09-01'),(29,1,'37.5',7,'2014-10-01'),(30,1,'112.5',8,'2014-04-01'),(31,1,'56.25',8,'2014-05-01'),(32,1,'56.25',8,'2014-06-01'),(33,1,'56.25',8,'2014-07-01'),(34,1,'56.25',8,'2014-08-01'),(35,1,'56.25',8,'2014-09-01'),(36,1,'56.25',8,'2014-10-01'),(37,1,'37.5',9,'2014-04-01'),(38,1,'18.75',9,'2014-05-01'),(39,1,'18.75',9,'2014-06-01'),(40,1,'18.75',9,'2014-07-01'),(41,1,'18.75',9,'2014-08-01'),(42,1,'18.75',9,'2014-09-01'),(43,1,'18.75',9,'2014-10-01'),(44,1,'500',10,'2014-04-01'),(45,1,'350',11,'2014-04-01'),(46,1,'175',11,'2014-05-01'),(47,1,'175',11,'2014-06-01'),(48,1,'175',11,'2014-07-01'),(49,1,'175',11,'2014-08-01'),(50,1,'175',11,'2014-09-01'),(51,1,'175',11,'2014-10-01'),(52,1,'2925',13,'2014-04-01'),(53,1,'1462.5',13,'2014-05-01'),(54,1,'1462.5',13,'2014-06-01'),(55,1,'1462.5',13,'2014-07-01'),(56,1,'1462.5',13,'2014-08-01'),(57,1,'1462.5',13,'2014-09-01'),(58,1,'1462.5',13,'2014-10-01'),(225,4,'18.75',9,'2014-06-01'),(222,4,'56.25',8,'2014-10-01'),(199,4,'37.5',5,'2014-08-01'),(200,4,'37.5',5,'2014-09-01'),(201,4,'37.5',5,'2014-10-01'),(202,4,'87.5',6,'2014-04-01'),(203,4,'43.75',6,'2014-05-01'),(204,4,'43.75',6,'2014-06-01'),(205,4,'43.75',6,'2014-07-01'),(206,4,'43.75',6,'2014-08-01'),(207,4,'43.75',6,'2014-09-01'),(208,4,'43.75',6,'2014-10-01'),(209,4,'75',7,'2014-04-01'),(210,4,'37.5',7,'2014-05-01'),(211,4,'37.5',7,'2014-06-01'),(212,4,'37.5',7,'2014-07-01'),(213,4,'37.5',7,'2014-08-01'),(214,4,'37.5',7,'2014-09-01'),(215,4,'37.5',7,'2014-10-01'),(216,4,'112.5',8,'2014-04-01'),(217,4,'56.25',8,'2014-05-01'),(218,4,'56.25',8,'2014-06-01'),(219,4,'56.25',8,'2014-07-01'),(220,4,'56.25',8,'2014-08-01'),(182,4,'50',3,'2014-05-01'),(226,4,'18.75',9,'2014-07-01'),(185,4,'50',3,'2014-08-01'),(186,4,'50',3,'2014-09-01'),(187,4,'50',3,'2014-10-01'),(188,4,'187.5',4,'2014-04-01'),(189,4,'93.75',4,'2014-05-01'),(190,4,'93.75',4,'2014-06-01'),(191,4,'93.75',4,'2014-07-01'),(192,4,'93.75',4,'2014-08-01'),(193,4,'93.75',4,'2014-09-01'),(194,4,'93.75',4,'2014-10-01'),(195,4,'75',5,'2014-04-01'),(196,4,'37.5',5,'2014-05-01'),(197,4,'37.5',5,'2014-06-01'),(198,4,'37.5',5,'2014-07-01'),(183,4,'50',3,'2014-06-01'),(184,4,'50',3,'2014-07-01'),(224,4,'18.75',9,'2014-05-01'),(221,4,'56.25',8,'2014-09-01'),(229,4,'18.75',9,'2014-10-01'),(228,4,'18.75',9,'2014-09-01'),(227,4,'18.75',9,'2014-08-01'),(223,4,'37.5',9,'2014-04-01'),(181,4,'100',3,'2014-04-01'),(230,4,'500',10,'2014-04-01'),(231,4,'350',11,'2014-04-01'),(232,4,'175',11,'2014-05-01'),(233,4,'175',11,'2014-06-01'),(234,4,'175',11,'2014-07-01'),(235,4,'175',11,'2014-08-01'),(236,4,'175',11,'2014-09-01'),(237,4,'175',11,'2014-10-01'),(238,4,'4275',15,'2014-04-01'),(239,4,'2137.5',15,'2014-05-01'),(240,4,'2137.5',15,'2014-06-01'),(241,4,'2137.5',15,'2014-07-01'),(242,4,'2137.5',15,'2014-08-01'),(243,4,'2137.5',15,'2014-09-01'),(244,4,'2137.5',15,'2014-10-01');
/*!40000 ALTER TABLE `student_fees_applied` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_types`
--

DROP TABLE IF EXISTS `student_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `previouse_studenttype_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_types`
--

LOCK TABLES `student_types` WRITE;
/*!40000 ALTER TABLE `student_types` DISABLE KEYS */;
INSERT INTO `student_types` VALUES (1,'New Student',NULL),(2,'Old Student',1),(3,'Staff Ward New',NULL),(4,'Staff ward Old',3),(5,'III rd Kid New',NULL),(6,'III rd Kid Old',5),(7,'IV th Kid New',NULL),(8,'IV Kid Old',7),(9,'RTE',NULL);
/*!40000 ALTER TABLE `student_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `roll_no` varchar(255) DEFAULT NULL,
  `ishostler` tinyint(1) DEFAULT NULL,
  `isScholared` tinyint(1) DEFAULT NULL,
  `scholar_id` int(11) DEFAULT NULL,
  `studenttype_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_session_id` (`session_id`),
  KEY `fk_class_id` (`class_id`),
  KEY `fk_scholar_id` (`scholar_id`),
  KEY `fk_studenttype_id` (`studenttype_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (1,1,15,NULL,0,NULL,1,1),(4,1,23,NULL,0,NULL,2,6);
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects`
--

LOCK TABLES `subjects` WRITE;
/*!40000 ALTER TABLE `subjects` DISABLE KEYS */;
INSERT INTO `subjects` VALUES (1,'Hindi'),(2,'English'),(3,'Maths I'),(4,'Maths II'),(5,'Sanskrit'),(6,'Science I'),(7,'Science II');
/*!40000 ALTER TABLE `subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subjects_in_classes`
--

DROP TABLE IF EXISTS `subjects_in_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subjects_in_classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_class_id` (`class_id`),
  KEY `fk_session_id` (`session_id`),
  KEY `fk_subject_id` (`subject_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects_in_classes`
--

LOCK TABLES `subjects_in_classes` WRITE;
/*!40000 ALTER TABLE `subjects_in_classes` DISABLE KEYS */;
/*!40000 ALTER TABLE `subjects_in_classes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-06-18 19:23:36
