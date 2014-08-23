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
INSERT INTO `branches` VALUES (3,'Admin',NULL,NULL,NULL,NULL,NULL,NULL);
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
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes`
--

LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
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
  `term_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_term_id` (`term_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exams`
--

LOCK TABLES `exams` WRITE;
/*!40000 ALTER TABLE `exams` DISABLE KEYS */;
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
  `default_amount` decimal(10,2) DEFAULT NULL,
  `distribution` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fees`
--

LOCK TABLES `fees` WRITE;
/*!40000 ALTER TABLE `fees` DISABLE KEYS */;
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
) ENGINE=MyISAM AUTO_INCREMENT=262 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fees_amount_for_student_types`
--

LOCK TABLES `fees_amount_for_student_types` WRITE;
/*!40000 ALTER TABLE `fees_amount_for_student_types` DISABLE KEYS */;
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
  `name` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `months` text,
  `mode` varchar(255) DEFAULT NULL,
  `narration` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `receipt_name_number` (`name`) USING BTREE,
  KEY `fk_branch_id` (`branch_id`),
  KEY `fk_student_id` (`student_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1324 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fees_receipts`
--

LOCK TABLES `fees_receipts` WRITE;
/*!40000 ALTER TABLE `fees_receipts` DISABLE KEYS */;
/*!40000 ALTER TABLE `fees_receipts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fees_temp`
--

DROP TABLE IF EXISTS `fees_temp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fees_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scholar_no` varchar(255) DEFAULT NULL,
  `receipt_no` varchar(255) DEFAULT NULL,
  `submit_date` date DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fees_temp`
--

LOCK TABLES `fees_temp` WRITE;
/*!40000 ALTER TABLE `fees_temp` DISABLE KEYS */;
/*!40000 ALTER TABLE `fees_temp` ENABLE KEYS */;
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
  `student_id` int(11) DEFAULT NULL,
  `by_consession` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_student_applied_fees_id` (`student_applied_fees_id`),
  KEY `fk_fees_receipt_id` (`fees_receipt_id`),
  KEY `fk_branch_id` (`branch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19103 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fees_transactions`
--

LOCK TABLES `fees_transactions` WRITE;
/*!40000 ALTER TABLE `fees_transactions` DISABLE KEYS */;
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
) ENGINE=MyISAM AUTO_INCREMENT=521 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feeses_in_classes`
--

LOCK TABLES `feeses_in_classes` WRITE;
/*!40000 ALTER TABLE `feeses_in_classes` DISABLE KEYS */;
/*!40000 ALTER TABLE `feeses_in_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `library_categories`
--

DROP TABLE IF EXISTS `library_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `library_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_branch_id` (`branch_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `library_categories`
--

LOCK TABLES `library_categories` WRITE;
/*!40000 ALTER TABLE `library_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `library_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `library_items`
--

DROP TABLE IF EXISTS `library_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `library_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `book_no` varchar(255) DEFAULT NULL,
  `publishe_year` varchar(255) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `no_of_pages` int(11) DEFAULT NULL,
  `edition` varchar(255) DEFAULT NULL,
  `volume` varchar(255) DEFAULT NULL,
  `ISBN` varchar(255) DEFAULT NULL,
  `bill_no` varchar(255) DEFAULT NULL,
  `rate` varchar(255) DEFAULT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `is_issued` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_title_id` (`title_id`),
  KEY `fk_branch_id` (`branch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=404 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `library_items`
--

LOCK TABLES `library_items` WRITE;
/*!40000 ALTER TABLE `library_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `library_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `library_stocktransactions`
--

DROP TABLE IF EXISTS `library_stocktransactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `library_stocktransactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `narration` text,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_title_id` (`title_id`),
  KEY `fk_supplier_id` (`supplier_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `library_stocktransactions`
--

LOCK TABLES `library_stocktransactions` WRITE;
/*!40000 ALTER TABLE `library_stocktransactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `library_stocktransactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `library_subjects`
--

DROP TABLE IF EXISTS `library_subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `library_subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `library_subjects`
--

LOCK TABLES `library_subjects` WRITE;
/*!40000 ALTER TABLE `library_subjects` DISABLE KEYS */;
/*!40000 ALTER TABLE `library_subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `library_suppliers`
--

DROP TABLE IF EXISTS `library_suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `library_suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_address` text,
  `mobile_number` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `library_suppliers`
--

LOCK TABLES `library_suppliers` WRITE;
/*!40000 ALTER TABLE `library_suppliers` DISABLE KEYS */;
/*!40000 ALTER TABLE `library_suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `library_titles`
--

DROP TABLE IF EXISTS `library_titles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `library_titles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_subject_id` (`subject_id`)
) ENGINE=MyISAM AUTO_INCREMENT=370 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `library_titles`
--

LOCK TABLES `library_titles` WRITE;
/*!40000 ALTER TABLE `library_titles` DISABLE KEYS */;
/*!40000 ALTER TABLE `library_titles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `library_transactions`
--

DROP TABLE IF EXISTS `library_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `library_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `issue_on` date DEFAULT NULL,
  `submitted_on` date DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `narration` text,
  `no_of_day_late_submission` varchar(255) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_item_id` (`item_id`),
  KEY `fk_student_id` (`student_id`),
  KEY `fk_staff_id` (`staff_id`),
  KEY `fk_branch_id` (`branch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `library_transactions`
--

LOCK TABLES `library_transactions` WRITE;
/*!40000 ALTER TABLE `library_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `library_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marks`
--

DROP TABLE IF EXISTS `marks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `marks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_student_id` (`student_id`),
  KEY `fk_class_id` (`class_id`),
  KEY `fk_exam_id` (`exam_id`),
  KEY `fk_subject_id` (`subject_id`),
  KEY `fk_session_id` (`session_id`)
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marks`
--

LOCK TABLES `marks` WRITE;
/*!40000 ALTER TABLE `marks` DISABLE KEYS */;
/*!40000 ALTER TABLE `marks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_transactions`
--

DROP TABLE IF EXISTS `payment_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fees_receipt_id` int(11) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `transaction_type` varchar(255) DEFAULT NULL,
  `narration` varchar(255) DEFAULT NULL,
  `mode` varchar(255) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_fees_receipt_id` (`fees_receipt_id`),
  KEY `fk_branch_id` (`branch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2337 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_transactions`
--

LOCK TABLES `payment_transactions` WRITE;
/*!40000 ALTER TABLE `payment_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_transactions` ENABLE KEYS */;
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
  `scholar_no` int(11) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=3168 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scholars`
--

LOCK TABLES `scholars` WRITE;
/*!40000 ALTER TABLE `scholars` DISABLE KEYS */;
/*!40000 ALTER TABLE `scholars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scholars_copy`
--

DROP TABLE IF EXISTS `scholars_copy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scholars_copy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admission_date` varchar(255) DEFAULT NULL,
  `scholar_no` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `dob` varchar(255) DEFAULT NULL,
  `phone_no` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `leaving_date` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `cast` varchar(255) DEFAULT NULL,
  `house` varchar(255) DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `brother_sister_name_class` varchar(255) DEFAULT NULL,
  `form_no` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `previous_school_and_class` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `section` varchar(255) DEFAULT NULL,
  `student_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1394 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scholars_copy`
--

LOCK TABLES `scholars_copy` WRITE;
/*!40000 ALTER TABLE `scholars_copy` DISABLE KEYS */;
/*!40000 ALTER TABLE `scholars_copy` ENABLE KEYS */;
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
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
  `is_application_user` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_branch_id` (`branch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staffs`
--

LOCK TABLES `staffs` WRITE;
/*!40000 ALTER TABLE `staffs` DISABLE KEYS */;
INSERT INTO `staffs` VALUES (3,3,'Admin','admin','admin',1);
/*!40000 ALTER TABLE `staffs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_category`
--

DROP TABLE IF EXISTS `stock_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_category`
--

LOCK TABLES `stock_category` WRITE;
/*!40000 ALTER TABLE `stock_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_items`
--

DROP TABLE IF EXISTS `stock_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_category_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_items`
--

LOCK TABLES `stock_items` WRITE;
/*!40000 ALTER TABLE `stock_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_suppliers`
--

DROP TABLE IF EXISTS `stock_suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `ph_no` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_suppliers`
--

LOCK TABLES `stock_suppliers` WRITE;
/*!40000 ALTER TABLE `stock_suppliers` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_transactions`
--

DROP TABLE IF EXISTS `stock_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `remark` text,
  PRIMARY KEY (`id`),
  KEY `fk_item_id` (`item_id`),
  KEY `fk_session_id` (`session_id`),
  KEY `fk_branch_id` (`branch_id`),
  KEY `fk_supplier_id` (`supplier_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_transactions`
--

LOCK TABLES `stock_transactions` WRITE;
/*!40000 ALTER TABLE `stock_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_attendance`
--

DROP TABLE IF EXISTS `student_attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `month` varchar(255) DEFAULT NULL,
  `total_attendance` varchar(255) DEFAULT NULL,
  `present` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_class_id` (`class_id`),
  KEY `fk_student_id` (`student_id`),
  KEY `fk_session_id` (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_attendance`
--

LOCK TABLES `student_attendance` WRITE;
/*!40000 ALTER TABLE `student_attendance` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_attendance` ENABLE KEYS */;
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
) ENGINE=MyISAM AUTO_INCREMENT=100837 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_fees_applied`
--

LOCK TABLES `student_fees_applied` WRITE;
/*!40000 ALTER TABLE `student_fees_applied` DISABLE KEYS */;
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
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_types`
--

LOCK TABLES `student_types` WRITE;
/*!40000 ALTER TABLE `student_types` DISABLE KEYS */;
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
  `given_consession` decimal(10,2) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_session_id` (`session_id`),
  KEY `fk_class_id` (`class_id`),
  KEY `fk_scholar_id` (`scholar_id`),
  KEY `fk_studenttype_id` (`studenttype_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1572 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
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
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects_in_classes`
--

LOCK TABLES `subjects_in_classes` WRITE;
/*!40000 ALTER TABLE `subjects_in_classes` DISABLE KEYS */;
/*!40000 ALTER TABLE `subjects_in_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subjects_in_exam_class`
--

DROP TABLE IF EXISTS `subjects_in_exam_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subjects_in_exam_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) DEFAULT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `max_marks` varchar(255) DEFAULT NULL,
  `min_marks` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_class_id` (`class_id`),
  KEY `fk_exam_id` (`exam_id`),
  KEY `fk_subject_id` (`subject_id`),
  KEY `fk_session_id` (`session_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects_in_exam_class`
--

LOCK TABLES `subjects_in_exam_class` WRITE;
/*!40000 ALTER TABLE `subjects_in_exam_class` DISABLE KEYS */;
/*!40000 ALTER TABLE `subjects_in_exam_class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_libray_data`
--

DROP TABLE IF EXISTS `temp_libray_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `temp_libray_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `book_no` varchar(255) DEFAULT NULL,
  `publishing_year` varchar(255) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `no_of_pages` varchar(255) DEFAULT NULL,
  `edition` varchar(255) DEFAULT NULL,
  `volume` varchar(255) DEFAULT NULL,
  `isbn` varchar(255) DEFAULT NULL,
  `bill_no` varchar(255) DEFAULT NULL,
  `rate` varchar(255) DEFAULT NULL,
  `accession_no` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `branch_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=404 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_libray_data`
--

LOCK TABLES `temp_libray_data` WRITE;
/*!40000 ALTER TABLE `temp_libray_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_libray_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `terms`
--

DROP TABLE IF EXISTS `terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `terms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `terms`
--

LOCK TABLES `terms` WRITE;
/*!40000 ALTER TABLE `terms` DISABLE KEYS */;
/*!40000 ALTER TABLE `terms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicle_types`
--

DROP TABLE IF EXISTS `vehicle_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicle_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_types`
--

LOCK TABLES `vehicle_types` WRITE;
/*!40000 ALTER TABLE `vehicle_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `vehicle_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_type_id` int(11) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `capcity` varchar(255) DEFAULT NULL,
  `driver_name` varchar(255) DEFAULT NULL,
  `driver_number` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_vehicle_type_id` (`vehicle_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicles`
--

LOCK TABLES `vehicles` WRITE;
/*!40000 ALTER TABLE `vehicles` DISABLE KEYS */;
/*!40000 ALTER TABLE `vehicles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-07-25 16:29:37
