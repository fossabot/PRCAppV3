-- MySQL dump 10.13  Distrib 5.7.20, for Linux (x86_64)
--
-- Host: localhost    Database: hrms
-- ------------------------------------------------------
-- Server version	5.7.20-0ubuntu0.16.04.1-log

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
-- Table structure for table `ADDRESS`
--

DROP TABLE IF EXISTS `ADDRESS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADDRESS` (
  `cd_address` int(11) NOT NULL,
  `cd_address_type` int(11) NOT NULL,
  `ds_address` longtext,
  `ds_address_additional` longtext,
  `cd_city` int(11) DEFAULT NULL,
  `ds_district` longtext,
  `ds_zip_code` longtext,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_address`),
  KEY `FKADDRESS02` (`cd_city`),
  KEY `FKADDRESS03_idx` (`cd_address_type`),
  CONSTRAINT `FKADDRESS02` FOREIGN KEY (`cd_city`) REFERENCES `CITY` (`cd_city`),
  CONSTRAINT `FKADDRESS03` FOREIGN KEY (`cd_address_type`) REFERENCES `ADDRESS_TYPE` (`cd_address_type`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADDRESS`
--

LOCK TABLES `ADDRESS` WRITE;
/*!40000 ALTER TABLE `ADDRESS` DISABLE KEYS */;
INSERT INTO `ADDRESS` VALUES (182,1,'aad',NULL,NULL,NULL,NULL,'2017-11-17 17:47:03'),(316,1,'aaaa','b',NULL,NULL,NULL,'2017-11-21 14:44:51'),(318,1,'a','b',NULL,'AA',NULL,'2017-11-21 15:13:38'),(324,1,'address',NULL,NULL,NULL,NULL,'2017-11-21 16:29:45'),(325,2,'compa',NULL,NULL,NULL,NULL,'2017-11-22 10:13:55'),(331,1,NULL,NULL,NULL,NULL,NULL,'2017-11-22 13:00:17'),(333,1,NULL,NULL,NULL,NULL,NULL,'2017-11-22 13:03:03'),(351,1,NULL,NULL,NULL,NULL,NULL,'2017-11-25 10:21:05'),(354,1,NULL,NULL,NULL,NULL,NULL,'2017-11-25 18:19:43'),(355,1,NULL,NULL,2,NULL,NULL,'2017-12-06 21:26:17'),(356,1,NULL,NULL,NULL,NULL,NULL,'2017-12-06 21:26:33');
/*!40000 ALTER TABLE `ADDRESS` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_ADDRESS BEFORE INSERT ON ADDRESS
FOR EACH ROW
BEGIN
    IF NEW.cd_address IS NULL THEN
        SET NEW.cd_address = nextval('ADDRESS');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `ADDRESS_TYPE`
--

DROP TABLE IF EXISTS `ADDRESS_TYPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADDRESS_TYPE` (
  `cd_address_type` int(11) NOT NULL,
  `ds_address_type` varchar(64) NOT NULL,
  `nr_order` int(11) NOT NULL DEFAULT '0',
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_address_type`),
  UNIQUE KEY `IUNADDRESS_TYPE001` (`ds_address_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADDRESS_TYPE`
--

LOCK TABLES `ADDRESS_TYPE` WRITE;
/*!40000 ALTER TABLE `ADDRESS_TYPE` DISABLE KEYS */;
INSERT INTO `ADDRESS_TYPE` VALUES (1,'HOME',1,NULL,'2017-11-14 16:02:33'),(2,'COMPANY',2,NULL,'2017-11-14 16:19:28');
/*!40000 ALTER TABLE `ADDRESS_TYPE` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_ADDRESS_TYPE BEFORE INSERT ON ADDRESS_TYPE
FOR EACH ROW
BEGIN
    IF NEW.cd_address_type IS NULL THEN
        SET NEW.cd_address_type = nextval('ADDRESS_TYPE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `APPROVAL_STATUS`
--

DROP TABLE IF EXISTS `APPROVAL_STATUS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `APPROVAL_STATUS` (
  `cd_approval_status` int(11) NOT NULL,
  `ds_approval_status` varchar(64) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fl_approved` char(1) DEFAULT 'N',
  PRIMARY KEY (`cd_approval_status`),
  UNIQUE KEY `IUNAPPROVAL_STATUS001` (`ds_approval_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `APPROVAL_STATUS`
--

LOCK TABLES `APPROVAL_STATUS` WRITE;
/*!40000 ALTER TABLE `APPROVAL_STATUS` DISABLE KEYS */;
/*!40000 ALTER TABLE `APPROVAL_STATUS` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_APPROVAL_STATUS BEFORE INSERT ON APPROVAL_STATUS
FOR EACH ROW
BEGIN
    IF NEW.cd_approval_status IS NULL THEN
        SET NEW.cd_approval_status = nextval('APPROVAL_STATUS');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `ASSET_TYPE`
--

DROP TABLE IF EXISTS `ASSET_TYPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ASSET_TYPE` (
  `cd_asset_type` int(11) NOT NULL,
  `ds_asset_type` varchar(64) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_asset_type`),
  UNIQUE KEY `IUNASSET_TYPE001` (`ds_asset_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ASSET_TYPE`
--

LOCK TABLES `ASSET_TYPE` WRITE;
/*!40000 ALTER TABLE `ASSET_TYPE` DISABLE KEYS */;
INSERT INTO `ASSET_TYPE` VALUES (1,'TABLET',NULL,'2017-11-29 20:16:53');
/*!40000 ALTER TABLE `ASSET_TYPE` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_ASSET_TYPE BEFORE INSERT ON ASSET_TYPE
FOR EACH ROW
BEGIN
    IF NEW.cd_asset_type IS NULL THEN
        SET NEW.cd_asset_type = nextval('ASSET_TYPE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `BANK`
--

DROP TABLE IF EXISTS `BANK`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BANK` (
  `cd_bank` int(11) NOT NULL,
  `ds_bank` varchar(64) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_bank`),
  UNIQUE KEY `ds_bank_UNIQUE` (`ds_bank`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BANK`
--

LOCK TABLES `BANK` WRITE;
/*!40000 ALTER TABLE `BANK` DISABLE KEYS */;
INSERT INTO `BANK` VALUES (1,'CHINA BANK',NULL,'2017-11-22 14:42:57');
/*!40000 ALTER TABLE `BANK` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_BANK BEFORE INSERT ON BANK
FOR EACH ROW
BEGIN
    IF NEW.cd_bank IS NULL THEN
        SET NEW.cd_bank = nextval('BANK');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `BANK_BRANCH`
--

DROP TABLE IF EXISTS `BANK_BRANCH`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BANK_BRANCH` (
  `cd_bank_branch` int(11) NOT NULL,
  `ds_bank_branch` varchar(64) DEFAULT NULL,
  `cd_bank` int(11) NOT NULL,
  `cd_currency_default` int(11) DEFAULT NULL,
  `ds_address` longtext,
  `ds_address_additional` longtext,
  `cd_city` int(11) DEFAULT NULL,
  `ds_district` varchar(64) DEFAULT NULL,
  `ds_zip_code` varchar(64) DEFAULT NULL,
  `ds_swift_code` varchar(64) DEFAULT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_bank_branch`),
  KEY `fk_BANK_BRANCH_BANK1_idx` (`cd_bank`),
  KEY `fk_BANK_BRANCH_CITY1_idx` (`cd_city`),
  KEY `fk_BANK_BRANCH_CURRENCT_1_idx` (`cd_currency_default`),
  CONSTRAINT `fk_BANK_BRANCH_BANK1` FOREIGN KEY (`cd_bank`) REFERENCES `BANK` (`cd_bank`),
  CONSTRAINT `fk_BANK_BRANCH_CITY1` FOREIGN KEY (`cd_city`) REFERENCES `CITY` (`cd_city`),
  CONSTRAINT `fk_BANK_BRANCH_CURRENCT_1` FOREIGN KEY (`cd_currency_default`) REFERENCES `CURRENCY` (`cd_currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BANK_BRANCH`
--

LOCK TABLES `BANK_BRANCH` WRITE;
/*!40000 ALTER TABLE `BANK_BRANCH` DISABLE KEYS */;
INSERT INTO `BANK_BRANCH` VALUES (3,'BRANCH 1',1,2,'ADDRESS\naddres 2','ADD\nabb',2,'BB',NULL,'swift1',NULL,'2017-11-22 14:44:34'),(6,'BRANCH 2',1,3,'qaa','vvv',2,'AA',NULL,'swift2',NULL,'2017-11-23 20:59:21');
/*!40000 ALTER TABLE `BANK_BRANCH` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_BANK_BRANCH BEFORE INSERT ON BANK_BRANCH
FOR EACH ROW
BEGIN
    IF NEW.cd_bank_branch IS NULL THEN
        SET NEW.cd_bank_branch = nextval('BANK_BRANCH');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `BENEFIT_FREQUENCY`
--

DROP TABLE IF EXISTS `BENEFIT_FREQUENCY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BENEFIT_FREQUENCY` (
  `cd_benefit_frequency` int(11) NOT NULL,
  `ds_benefit_frequency` varchar(64) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_benefit_frequency`),
  UNIQUE KEY `ds_benefit_frequency_UNIQUE` (`ds_benefit_frequency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BENEFIT_FREQUENCY`
--

LOCK TABLES `BENEFIT_FREQUENCY` WRITE;
/*!40000 ALTER TABLE `BENEFIT_FREQUENCY` DISABLE KEYS */;
INSERT INTO `BENEFIT_FREQUENCY` VALUES (1,'MONTHLY',NULL,'2017-12-05 16:01:59'),(2,'HALF YEARLY',NULL,'2017-12-05 16:01:59'),(3,'YEARLY',NULL,'2017-12-05 16:01:59');
/*!40000 ALTER TABLE `BENEFIT_FREQUENCY` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_BENEFIT_FREQUENCY BEFORE INSERT ON BENEFIT_FREQUENCY
FOR EACH ROW
BEGIN
    IF NEW.cd_benefit_frequency IS NULL THEN
        SET NEW.cd_benefit_frequency = nextval('BENEFIT_FREQUENCY');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `BENEFIT_KIND`
--

DROP TABLE IF EXISTS `BENEFIT_KIND`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BENEFIT_KIND` (
  `cd_benefit_kind` int(11) NOT NULL,
  `ds_benefit_kind` varchar(64) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_benefit_kind`),
  UNIQUE KEY `ds_benefit_kind_UNIQUE` (`ds_benefit_kind`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BENEFIT_KIND`
--

LOCK TABLES `BENEFIT_KIND` WRITE;
/*!40000 ALTER TABLE `BENEFIT_KIND` DISABLE KEYS */;
INSERT INTO `BENEFIT_KIND` VALUES (1,'RECURRING',NULL,'2017-12-09 09:45:48'),(2,'ACCUMULATIVE',NULL,'2017-12-09 09:45:48'),(3,'MANUAL',NULL,'2017-12-11 18:39:35');
/*!40000 ALTER TABLE `BENEFIT_KIND` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_BENEFIT_KIND BEFORE INSERT ON BENEFIT_KIND
FOR EACH ROW
BEGIN
    IF NEW.cd_benefit_kind IS NULL THEN
        SET NEW.cd_benefit_kind = nextval('BENEFIT_KIND');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `BENEFIT_OPERATION`
--

DROP TABLE IF EXISTS `BENEFIT_OPERATION`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BENEFIT_OPERATION` (
  `cd_benefit_operation` int(11) NOT NULL,
  `ds_benefit_operation` varchar(64) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_benefit_operation`),
  UNIQUE KEY `ds_benefit_operation_UNIQUE` (`ds_benefit_operation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BENEFIT_OPERATION`
--

LOCK TABLES `BENEFIT_OPERATION` WRITE;
/*!40000 ALTER TABLE `BENEFIT_OPERATION` DISABLE KEYS */;
INSERT INTO `BENEFIT_OPERATION` VALUES (1,'ADD',NULL,'2017-12-09 09:40:23'),(2,'REMOVE',NULL,'2017-12-09 09:40:23');
/*!40000 ALTER TABLE `BENEFIT_OPERATION` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_BENEFIT_OPERATION BEFORE INSERT ON BENEFIT_OPERATION
FOR EACH ROW
BEGIN
    IF NEW.cd_benefit_operation IS NULL THEN
        SET NEW.cd_benefit_operation = nextval('BENEFIT_OPERATION');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `BENEFIT_TYPE`
--

DROP TABLE IF EXISTS `BENEFIT_TYPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BENEFIT_TYPE` (
  `cd_benefit_type` int(11) NOT NULL,
  `ds_benefit_type` varchar(64) NOT NULL,
  `cd_benefit_operation_default` int(11) DEFAULT NULL,
  `cd_benefit_kind_default` int(11) NOT NULL,
  `cd_benefit_unit` int(11) NOT NULL,
  `cd_currency_default` int(11) DEFAULT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_benefit_type`),
  UNIQUE KEY `ds_benefit_type_UNIQUE` (`ds_benefit_type`),
  KEY `FKBENEFIT_TYPE001_idx` (`cd_benefit_kind_default`),
  KEY `FKBENEFIT_TYPE002_idx` (`cd_benefit_operation_default`),
  KEY `FKBENEFIT_TYPE004_idx` (`cd_currency_default`),
  KEY `cd_benefit_unit_UNIQUE` (`cd_benefit_unit`),
  CONSTRAINT `FKBENEFIT_TYPE001` FOREIGN KEY (`cd_benefit_kind_default`) REFERENCES `BENEFIT_KIND` (`cd_benefit_kind`),
  CONSTRAINT `FKBENEFIT_TYPE002` FOREIGN KEY (`cd_benefit_operation_default`) REFERENCES `BENEFIT_OPERATION` (`cd_benefit_operation`),
  CONSTRAINT `FKBENEFIT_TYPE003` FOREIGN KEY (`cd_benefit_unit`) REFERENCES `BENEFIT_UNIT` (`cd_benefit_unit`),
  CONSTRAINT `FKBENEFIT_TYPE004` FOREIGN KEY (`cd_currency_default`) REFERENCES `CURRENCY` (`cd_currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BENEFIT_TYPE`
--

LOCK TABLES `BENEFIT_TYPE` WRITE;
/*!40000 ALTER TABLE `BENEFIT_TYPE` DISABLE KEYS */;
INSERT INTO `BENEFIT_TYPE` VALUES (1,'HOUSE ALLOWANCE',1,1,1,3,NULL,'2017-11-22 15:04:10'),(2,'HOTEL ALLOWANCE',1,1,1,3,NULL,'2017-11-22 15:04:10'),(3,'FLIGHT TICKET',1,2,4,NULL,NULL,'2017-11-22 15:04:10'),(4,'HEALTH INSURANCE',1,1,1,3,NULL,'2017-11-22 15:04:10'),(5,'SOCIAL INSURANCE',NULL,1,1,3,NULL,'2017-11-22 15:04:10'),(6,'NAIL BEAUTY',1,1,1,3,NULL,'2017-11-22 15:04:10'),(7,'PHONE',1,1,1,3,NULL,'2017-11-22 15:04:10'),(8,'SALARY',1,1,1,3,NULL,'2017-11-22 15:04:10'),(9,'VACATION',NULL,2,2,NULL,NULL,'2017-11-26 11:02:34'),(10,'BANK HOUR',NULL,2,2,NULL,NULL,'2017-11-26 11:02:34'),(11,'REWARD',1,3,1,3,NULL,'2017-12-07 19:00:52');
/*!40000 ALTER TABLE `BENEFIT_TYPE` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_BENEFIT_TYPE BEFORE INSERT ON BENEFIT_TYPE
FOR EACH ROW
BEGIN
    IF NEW.cd_benefit_type IS NULL THEN
        SET NEW.cd_benefit_type = nextval('BENEFIT_TYPE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `BENEFIT_UNIT`
--

DROP TABLE IF EXISTS `BENEFIT_UNIT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BENEFIT_UNIT` (
  `cd_benefit_unit` int(11) NOT NULL,
  `ds_benefit_unit` varchar(64) NOT NULL,
  `fl_currency` varchar(1) NOT NULL DEFAULT 'N',
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_benefit_unit`),
  UNIQUE KEY `ds_benefit_unit_UNIQUE` (`ds_benefit_unit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BENEFIT_UNIT`
--

LOCK TABLES `BENEFIT_UNIT` WRITE;
/*!40000 ALTER TABLE `BENEFIT_UNIT` DISABLE KEYS */;
INSERT INTO `BENEFIT_UNIT` VALUES (1,'MONEY','Y',NULL,'2017-12-11 13:04:48'),(2,'DAYS','N',NULL,'2017-12-11 13:04:48'),(3,'HOURS','N',NULL,'2017-12-11 13:04:48'),(4,'UNIT','N',NULL,'2017-12-11 18:44:02');
/*!40000 ALTER TABLE `BENEFIT_UNIT` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_BENEFIT_UNIT BEFORE INSERT ON BENEFIT_UNIT
FOR EACH ROW
BEGIN
    IF NEW.cd_benefit_unit IS NULL THEN
        SET NEW.cd_benefit_unit = nextval('BENEFIT_UNIT');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `CITY`
--

DROP TABLE IF EXISTS `CITY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CITY` (
  `cd_city` int(11) NOT NULL,
  `ds_city` varchar(64) NOT NULL,
  `cd_province` int(11) NOT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dt_deactivated` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_city`),
  UNIQUE KEY `IUNCITY001` (`ds_city`),
  KEY `fkcity01` (`cd_province`),
  CONSTRAINT `fkcity01` FOREIGN KEY (`cd_province`) REFERENCES `PROVINCE` (`cd_province`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CITY`
--

LOCK TABLES `CITY` WRITE;
/*!40000 ALTER TABLE `CITY` DISABLE KEYS */;
INSERT INTO `CITY` VALUES (2,'CAMPO BOM',5,'2017-11-05 17:52:31',NULL),(3,'NOVO HAMBURGO',5,'2017-11-09 10:48:01',NULL);
/*!40000 ALTER TABLE `CITY` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_CITY BEFORE INSERT ON CITY
FOR EACH ROW
BEGIN
    IF NEW.cd_city IS NULL THEN
        SET NEW.cd_city = nextval('CITY');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `CIVIL_STATUS`
--

DROP TABLE IF EXISTS `CIVIL_STATUS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CIVIL_STATUS` (
  `cd_civil_status` int(11) NOT NULL,
  `ds_civil_status` varchar(64) NOT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dt_deactivated` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_civil_status`),
  UNIQUE KEY `IUNCIVIL_STATUS001` (`ds_civil_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CIVIL_STATUS`
--

LOCK TABLES `CIVIL_STATUS` WRITE;
/*!40000 ALTER TABLE `CIVIL_STATUS` DISABLE KEYS */;
INSERT INTO `CIVIL_STATUS` VALUES (1,'SINGLE','2017-11-05 19:01:23',NULL),(2,'MARRIED','2017-11-11 16:47:55',NULL);
/*!40000 ALTER TABLE `CIVIL_STATUS` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_CIVIL_STATUS BEFORE INSERT ON CIVIL_STATUS
FOR EACH ROW
BEGIN
    IF NEW.cd_civil_status IS NULL THEN
        SET NEW.cd_civil_status = nextval('CIVIL_STATUS');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `CONTACT`
--

DROP TABLE IF EXISTS `CONTACT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CONTACT` (
  `cd_contact` int(11) NOT NULL,
  `ds_e_mail` varchar(128) DEFAULT NULL,
  `ds_phone` varchar(64) DEFAULT NULL,
  `ds_im` varchar(64) DEFAULT NULL,
  `cd_contact_type` int(11) DEFAULT NULL,
  `ds_note` longtext,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_contact`),
  KEY `FKCONTACT01` (`cd_contact_type`),
  CONSTRAINT `FKCONTACT01` FOREIGN KEY (`cd_contact_type`) REFERENCES `CONTACT_TYPE` (`cd_contact_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CONTACT`
--

LOCK TABLES `CONTACT` WRITE;
/*!40000 ALTER TABLE `CONTACT` DISABLE KEYS */;
INSERT INTO `CONTACT` VALUES (69,'12',NULL,NULL,1,NULL,'2017-11-17 17:47:03'),(197,NULL,'jhgjhg',NULL,1,NULL,'2017-11-21 14:44:51'),(199,NULL,NULL,NULL,1,NULL,'2017-11-21 15:13:38'),(201,'carlosgblos@gmail.com',NULL,NULL,1,NULL,'2017-11-21 16:29:45'),(202,'contact',NULL,NULL,1,NULL,'2017-11-22 10:14:04'),(208,NULL,NULL,NULL,1,NULL,'2017-11-22 13:00:17'),(210,NULL,NULL,NULL,1,NULL,'2017-11-22 13:03:03'),(228,NULL,NULL,NULL,1,NULL,'2017-11-25 10:21:05'),(231,NULL,NULL,NULL,1,NULL,'2017-11-25 18:19:43'),(232,NULL,NULL,NULL,1,NULL,'2017-12-06 21:26:17'),(233,NULL,NULL,NULL,1,NULL,'2017-12-06 21:26:33');
/*!40000 ALTER TABLE `CONTACT` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_CONTACT BEFORE INSERT ON CONTACT
FOR EACH ROW
BEGIN
    IF NEW.cd_contact IS NULL THEN
        SET NEW.cd_contact = nextval('CONTACT');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `CONTACT_TYPE`
--

DROP TABLE IF EXISTS `CONTACT_TYPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CONTACT_TYPE` (
  `cd_contact_type` int(11) NOT NULL,
  `ds_contact_type` varchar(64) NOT NULL,
  `nr_order` int(11) NOT NULL DEFAULT '0',
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_contact_type`),
  UNIQUE KEY `IUNCONTACT_TYPE001` (`ds_contact_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CONTACT_TYPE`
--

LOCK TABLES `CONTACT_TYPE` WRITE;
/*!40000 ALTER TABLE `CONTACT_TYPE` DISABLE KEYS */;
INSERT INTO `CONTACT_TYPE` VALUES (1,'PRIVATE',1,NULL,'2017-11-05 19:08:56'),(2,'WORK',2,NULL,'2017-11-05 19:08:56'),(3,'OTHER',3,NULL,'2017-11-05 19:08:56');
/*!40000 ALTER TABLE `CONTACT_TYPE` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_CONTACT_TYPE BEFORE INSERT ON CONTACT_TYPE
FOR EACH ROW
BEGIN
    IF NEW.cd_contact_type IS NULL THEN
        SET NEW.cd_contact_type = nextval('CONTACT_TYPE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `COUNTRY`
--

DROP TABLE IF EXISTS `COUNTRY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `COUNTRY` (
  `cd_country` int(11) NOT NULL,
  `ds_country` varchar(64) NOT NULL,
  `nr_country_number` smallint(6) DEFAULT NULL,
  `ds_iso_alpha2` char(2) NOT NULL,
  `ds_iso_alpha3` char(3) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_country`),
  UNIQUE KEY `IUN_COUNTRY001` (`ds_country`),
  KEY `IDX_COUNTRY001` (`ds_country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `COUNTRY`
--

LOCK TABLES `COUNTRY` WRITE;
/*!40000 ALTER TABLE `COUNTRY` DISABLE KEYS */;
INSERT INTO `COUNTRY` VALUES (44,'CHINA',156,'CN','CHN',NULL),(167,'PHILIPPINES',608,'PH','PHL',NULL),(211,'THAILAND',764,'TH','THA',NULL),(228,'UNITED STATES',840,'US','USA',NULL),(243,'BRAZIL',55,'BR','BRA',NULL),(247,'JAPAN',22,'JP','JAP','2016-10-22 00:00:00'),(251,'VIETNAN',34,'VT','VIE',NULL);
/*!40000 ALTER TABLE `COUNTRY` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_COUNTRY BEFORE INSERT ON COUNTRY
FOR EACH ROW
BEGIN
    IF NEW.cd_country IS NULL THEN
        SET NEW.cd_country = nextval('COUNTRY');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `CURRENCY`
--

DROP TABLE IF EXISTS `CURRENCY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CURRENCY` (
  `cd_currency` int(11) NOT NULL,
  `ds_currency` varchar(128) NOT NULL,
  `ds_currency_symbol` varchar(3) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_currency`),
  UNIQUE KEY `IUNCURRENCY001` (`ds_currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CURRENCY`
--

LOCK TABLES `CURRENCY` WRITE;
/*!40000 ALTER TABLE `CURRENCY` DISABLE KEYS */;
INSERT INTO `CURRENCY` VALUES (2,'US DOLAR','USD',NULL),(3,'RENMINBI','RMB',NULL),(306,'EURO','EU',NULL);
/*!40000 ALTER TABLE `CURRENCY` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_CURRENCY BEFORE INSERT ON CURRENCY
FOR EACH ROW
BEGIN
    IF NEW.cd_currency IS NULL THEN
        SET NEW.cd_currency = nextval('CURRENCY');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `CURRENCY_RATE`
--

DROP TABLE IF EXISTS `CURRENCY_RATE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CURRENCY_RATE` (
  `cd_currency_rate` int(11) NOT NULL,
  `ds_currency_rate` varchar(128) NOT NULL,
  `cd_currency_from` int(11) NOT NULL,
  `cd_currency_to` int(11) NOT NULL,
  `dt_currency_rate` datetime NOT NULL,
  `nr_currency_rate` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_currency_rate`),
  UNIQUE KEY `IUNCURRENCY_RATE001` (`ds_currency_rate`),
  KEY `IDXCURRENCY_RATE001` (`cd_currency_from`),
  KEY `IDXCURRENCY_RATE002` (`cd_currency_to`),
  CONSTRAINT `FKCURRENCY_RATE001` FOREIGN KEY (`cd_currency_from`) REFERENCES `CURRENCY` (`cd_currency`),
  CONSTRAINT `FKCURRENCY_RATE002` FOREIGN KEY (`cd_currency_to`) REFERENCES `CURRENCY` (`cd_currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CURRENCY_RATE`
--

LOCK TABLES `CURRENCY_RATE` WRITE;
/*!40000 ALTER TABLE `CURRENCY_RATE` DISABLE KEYS */;
INSERT INTO `CURRENCY_RATE` VALUES (1,'RENMINBI - US DOLAR - 12/17/2017',3,2,'2017-12-17 00:00:00',0.2000,NULL,'2017-12-18 21:03:16'),(2,'US DOLAR - RENMINBI - 12/18/2017',2,3,'2017-12-18 00:00:00',6.8000,NULL,'2017-12-18 21:29:23');
/*!40000 ALTER TABLE `CURRENCY_RATE` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_CURRENCY_RATE BEFORE INSERT ON CURRENCY_RATE
FOR EACH ROW
BEGIN
    
    DECLARE ds_currency_from varchar(128);
    DECLARE ds_currency_to     varchar(128);
    
    
    IF NEW.cd_currency_rate IS NULL THEN
        SET NEW.cd_currency_rate = nextval('CURRENCY_RATE');
     END IF;
     
     SELECT ds_currency 
			INTO ds_currency_from
		FROM CURRENCY
	  WHERE cd_currency = NEW.cd_currency_from;
      
     SELECT ds_currency 
			INTO ds_currency_to
		FROM CURRENCY
	  WHERE cd_currency = NEW.cd_currency_to;
     
     SET NEW.ds_currency_rate = concat(ds_currency_from, ' - ', ds_currency_to, ' - ' , datedbtogrid(NEW.dt_currency_rate));
     
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER `hrms`.`CURRENCY_RATE_BEFORE_UPDATE` BEFORE UPDATE ON `CURRENCY_RATE` FOR EACH ROW
BEGIN

    DECLARE ds_currency_from varchar(128);
    DECLARE ds_currency_to     varchar(128);
    
    
    IF NEW.cd_currency_rate IS NULL THEN
        SET NEW.cd_currency_rate = nextval('CURRENCY_RATE');
     END IF;
     
     SELECT ds_currency 
			INTO ds_currency_from
		FROM CURRENCY
	  WHERE cd_currency = NEW.cd_currency_from;
      
     SELECT ds_currency 
			INTO ds_currency_to
		FROM CURRENCY
	  WHERE cd_currency = NEW.cd_currency_to;
     
     SET NEW.ds_currency_rate = concat(ds_currency_from, ' - ', ds_currency_to, ' - ' , datedbtogrid(NEW.dt_currency_rate));

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `DEPARTMENT`
--

DROP TABLE IF EXISTS `DEPARTMENT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DEPARTMENT` (
  `cd_department` int(11) NOT NULL,
  `ds_department` varchar(64) NOT NULL,
  `dt_deactivated` date DEFAULT NULL,
  `dt_record` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_department`),
  UNIQUE KEY `IUN_DEPARTMENTS001` (`ds_department`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DEPARTMENT`
--

LOCK TABLES `DEPARTMENT` WRITE;
/*!40000 ALTER TABLE `DEPARTMENT` DISABLE KEYS */;
INSERT INTO `DEPARTMENT` VALUES (3,'IT',NULL,NULL),(5,'SAMPLES',NULL,NULL),(7,'MERCHANDISING',NULL,NULL),(9,'MANAGEMENT',NULL,'2014-07-28 20:24:44'),(15,'DEVELOPMENT',NULL,'2016-12-01 14:08:13');
/*!40000 ALTER TABLE `DEPARTMENT` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_DEPARTMENT BEFORE INSERT ON DEPARTMENT
FOR EACH ROW
BEGIN
    IF NEW.cd_department IS NULL THEN
        SET NEW.cd_department = nextval('DEPARTMENT');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `DEPENDENTS`
--

DROP TABLE IF EXISTS `DEPENDENTS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DEPENDENTS` (
  `cd_dependents` int(11) NOT NULL,
  `cd_employee` int(11) NOT NULL,
  `cd_personal_info` int(11) NOT NULL,
  `cd_relationship_type` int(11) NOT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_dependents`),
  KEY `FKDEPENDENTS03` (`cd_relationship_type`),
  KEY `FKDEPENDENTS02_idx` (`cd_employee`),
  KEY `FKDEPENDENTS03_idx` (`cd_personal_info`),
  CONSTRAINT `FKDEPENDENTS02` FOREIGN KEY (`cd_employee`) REFERENCES `EMPLOYEE` (`cd_employee`) ON DELETE CASCADE,
  CONSTRAINT `FKDEPENDENTS03` FOREIGN KEY (`cd_relationship_type`) REFERENCES `RELATIONSHIP_TYPE` (`cd_relationship_type`) ON DELETE CASCADE,
  CONSTRAINT `FKDEPENDENTS04` FOREIGN KEY (`cd_personal_info`) REFERENCES `PERSONAL_INFO` (`cd_personal_info`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DEPENDENTS`
--

LOCK TABLES `DEPENDENTS` WRITE;
/*!40000 ALTER TABLE `DEPENDENTS` DISABLE KEYS */;
INSERT INTO `DEPENDENTS` VALUES (237,280,607,2,'2017-11-21 15:13:38'),(255,280,646,3,'2017-11-25 18:19:43'),(257,339,652,1,'2017-12-06 21:26:33');
/*!40000 ALTER TABLE `DEPENDENTS` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_DEPENDENTS BEFORE INSERT ON DEPENDENTS
FOR EACH ROW
BEGIN
    IF NEW.cd_dependents IS NULL THEN
        SET NEW.cd_dependents = nextval('DEPENDENTS');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `DEPENDENTS_X_ADDRESS`
--

DROP TABLE IF EXISTS `DEPENDENTS_X_ADDRESS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DEPENDENTS_X_ADDRESS` (
  `cd_dependents_x_address` int(11) NOT NULL,
  `cd_dependents` int(11) NOT NULL,
  `cd_address` int(11) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_dependents_x_address`),
  UNIQUE KEY `DEPENDENTS_ADDRESS_Index01` (`cd_dependents`,`cd_address`),
  KEY `FKDEPENDENTS_X_ADDRESS02` (`cd_address`),
  CONSTRAINT `FKDEPENDENTS_X_ADDRESS01` FOREIGN KEY (`cd_dependents`) REFERENCES `DEPENDENTS` (`cd_dependents`) ON DELETE CASCADE,
  CONSTRAINT `FKDEPENDENTS_X_ADDRESS02` FOREIGN KEY (`cd_address`) REFERENCES `ADDRESS` (`cd_address`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DEPENDENTS_X_ADDRESS`
--

LOCK TABLES `DEPENDENTS_X_ADDRESS` WRITE;
/*!40000 ALTER TABLE `DEPENDENTS_X_ADDRESS` DISABLE KEYS */;
INSERT INTO `DEPENDENTS_X_ADDRESS` VALUES (127,237,318,NULL,'2017-11-21 15:13:38'),(134,237,325,NULL,'2017-11-22 10:13:55'),(140,255,354,NULL,'2017-11-25 18:19:43'),(141,257,356,NULL,'2017-12-06 21:26:33');
/*!40000 ALTER TABLE `DEPENDENTS_X_ADDRESS` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_DEPENDENTS_X_ADDRESS BEFORE INSERT ON DEPENDENTS_X_ADDRESS
FOR EACH ROW
BEGIN
    IF NEW.cd_dependents_x_address IS NULL THEN
        SET NEW.cd_dependents_x_address = nextval('DEPENDENTS_X_ADDRESS');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `DEPENDENTS_X_CONTACT`
--

DROP TABLE IF EXISTS `DEPENDENTS_X_CONTACT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DEPENDENTS_X_CONTACT` (
  `cd_dependents_x_contact` int(11) NOT NULL,
  `cd_dependents` int(11) NOT NULL,
  `cd_contact` int(11) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_dependents_x_contact`),
  UNIQUE KEY `DEPENDENTS_CONTACT_Index01` (`cd_dependents`,`cd_contact`),
  KEY `FKDEPENDENTS_X_CONTACT02` (`cd_contact`),
  CONSTRAINT `FKDEPENDENTS_X_CONTACT01` FOREIGN KEY (`cd_dependents`) REFERENCES `DEPENDENTS` (`cd_dependents`) ON DELETE CASCADE,
  CONSTRAINT `FKDEPENDENTS_X_CONTACT02` FOREIGN KEY (`cd_contact`) REFERENCES `CONTACT` (`cd_contact`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DEPENDENTS_X_CONTACT`
--

LOCK TABLES `DEPENDENTS_X_CONTACT` WRITE;
/*!40000 ALTER TABLE `DEPENDENTS_X_CONTACT` DISABLE KEYS */;
INSERT INTO `DEPENDENTS_X_CONTACT` VALUES (122,237,199,NULL,'2017-11-21 15:13:38'),(125,237,202,NULL,'2017-11-22 10:14:04'),(131,255,231,NULL,'2017-11-25 18:19:43'),(132,257,233,NULL,'2017-12-06 21:26:33');
/*!40000 ALTER TABLE `DEPENDENTS_X_CONTACT` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_DEPENDENTS_X_CONTACT BEFORE INSERT ON DEPENDENTS_X_CONTACT
FOR EACH ROW
BEGIN
    IF NEW.cd_dependents_x_contact IS NULL THEN
        SET NEW.cd_dependents_x_contact = nextval('DEPENDENTS_X_CONTACT');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `DEPENDENTS_X_DOCUMENTS`
--

DROP TABLE IF EXISTS `DEPENDENTS_X_DOCUMENTS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DEPENDENTS_X_DOCUMENTS` (
  `cd_dependents_x_documents` int(11) NOT NULL,
  `cd_dependents` int(11) NOT NULL,
  `cd_document` int(11) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_dependents_x_documents`),
  UNIQUE KEY `DEPENDENTS_X_DOCUMENTS_Index01` (`cd_dependents`,`cd_document`),
  KEY `FKDEPENDENTS_X_DOCUMENTS02` (`cd_document`),
  CONSTRAINT `FKDEPENDENTS_X_DOCUMENTS01` FOREIGN KEY (`cd_dependents`) REFERENCES `DEPENDENTS` (`cd_dependents`) ON DELETE CASCADE,
  CONSTRAINT `FKDEPENDENTS_X_DOCUMENTS02` FOREIGN KEY (`cd_document`) REFERENCES `DOCUMENT` (`cd_document`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DEPENDENTS_X_DOCUMENTS`
--

LOCK TABLES `DEPENDENTS_X_DOCUMENTS` WRITE;
/*!40000 ALTER TABLE `DEPENDENTS_X_DOCUMENTS` DISABLE KEYS */;
INSERT INTO `DEPENDENTS_X_DOCUMENTS` VALUES (121,237,154,NULL,'2017-11-21 15:13:38'),(127,237,160,NULL,'2017-11-22 10:28:07'),(135,255,191,NULL,'2017-11-25 18:20:10'),(136,257,193,NULL,'2017-12-06 21:26:33'),(139,257,200,NULL,'2017-12-14 17:44:58');
/*!40000 ALTER TABLE `DEPENDENTS_X_DOCUMENTS` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_DEPENDENTS_X_DOCUMENTS BEFORE INSERT ON DEPENDENTS_X_DOCUMENTS
FOR EACH ROW
BEGIN
    IF NEW.cd_dependents_x_documents IS NULL THEN
        SET NEW.cd_dependents_x_documents = nextval('DEPENDENTS_X_DOCUMENTS');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `DIVISION`
--

DROP TABLE IF EXISTS `DIVISION`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DIVISION` (
  `cd_division` int(11) NOT NULL,
  `ds_division` varchar(64) NOT NULL,
  `ds_division_short` varchar(6) DEFAULT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_division`),
  UNIQUE KEY `IUN_DIVISION001` (`ds_division`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DIVISION`
--

LOCK TABLES `DIVISION` WRITE;
/*!40000 ALTER TABLE `DIVISION` DISABLE KEYS */;
INSERT INTO `DIVISION` VALUES (6,'TESTE','xx',NULL,'2017-11-10 17:38:08'),(7,'TSTE2',NULL,NULL,'2017-11-11 08:33:36');
/*!40000 ALTER TABLE `DIVISION` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_DIVISION BEFORE INSERT ON DIVISION
FOR EACH ROW
BEGIN
    IF NEW.cd_division IS NULL THEN
        SET NEW.cd_division = nextval('DIVISION');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `DIVISION_BRAND`
--

DROP TABLE IF EXISTS `DIVISION_BRAND`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DIVISION_BRAND` (
  `cd_division_brand` int(11) NOT NULL,
  `ds_division_brand` varchar(64) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_division_brand`),
  UNIQUE KEY `IUN_DIVISION_BRAND001` (`ds_division_brand`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DIVISION_BRAND`
--

LOCK TABLES `DIVISION_BRAND` WRITE;
/*!40000 ALTER TABLE `DIVISION_BRAND` DISABLE KEYS */;
INSERT INTO `DIVISION_BRAND` VALUES (1,'TEST',NULL,'2017-11-10 17:38:25');
/*!40000 ALTER TABLE `DIVISION_BRAND` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_DIVISION_BRAND BEFORE INSERT ON DIVISION_BRAND
FOR EACH ROW
BEGIN
    IF NEW.cd_division_brand IS NULL THEN
        SET NEW.cd_division_brand = nextval('DIVISION_BRAND');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `DIVISION_X_DIVISION_BRAND`
--

DROP TABLE IF EXISTS `DIVISION_X_DIVISION_BRAND`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DIVISION_X_DIVISION_BRAND` (
  `cd_division_x_division_brand` int(11) NOT NULL,
  `cd_division` int(11) NOT NULL,
  `cd_division_brand` int(11) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_division_x_division_brand`),
  UNIQUE KEY `IUNDIVISION_X_DIVISION_BRAND001` (`cd_division`,`cd_division_brand`),
  KEY `FKDIVISION_X_DIVISION_BRAND002` (`cd_division_brand`),
  CONSTRAINT `FKDIVISION_X_DIVISION_BRAND001` FOREIGN KEY (`cd_division`) REFERENCES `DIVISION` (`cd_division`),
  CONSTRAINT `FKDIVISION_X_DIVISION_BRAND002` FOREIGN KEY (`cd_division_brand`) REFERENCES `DIVISION_BRAND` (`cd_division_brand`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DIVISION_X_DIVISION_BRAND`
--

LOCK TABLES `DIVISION_X_DIVISION_BRAND` WRITE;
/*!40000 ALTER TABLE `DIVISION_X_DIVISION_BRAND` DISABLE KEYS */;
INSERT INTO `DIVISION_X_DIVISION_BRAND` VALUES (1,6,1,'2017-11-11 13:53:01'),(2,7,1,'2017-11-11 13:53:01');
/*!40000 ALTER TABLE `DIVISION_X_DIVISION_BRAND` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_DIVISION_X_DIVISION_BRAND BEFORE INSERT ON DIVISION_X_DIVISION_BRAND
FOR EACH ROW
BEGIN
    IF NEW.cd_division_x_division_brand IS NULL THEN
        SET NEW.cd_division_x_division_brand = nextval('DIVISION_X_DIVISION_BRAND');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `DOCUMENT`
--

DROP TABLE IF EXISTS `DOCUMENT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DOCUMENT` (
  `cd_document` int(11) NOT NULL,
  `ds_document` varchar(64) NOT NULL,
  `cd_document_type` int(11) NOT NULL,
  `ds_document_number` varchar(64) NOT NULL,
  `ds_issuer` varchar(128) DEFAULT NULL,
  `dt_issue` datetime DEFAULT NULL,
  `dt_expiring_date` datetime DEFAULT NULL,
  `dt_effective` datetime DEFAULT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_document`),
  KEY `FKDOCUMENT001_idx` (`cd_document_type`),
  CONSTRAINT `FKDOCUMENT001` FOREIGN KEY (`cd_document_type`) REFERENCES `DOCUMENT_TYPE` (`cd_document_type`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DOCUMENT`
--

LOCK TABLES `DOCUMENT` WRITE;
/*!40000 ALTER TABLE `DOCUMENT` DISABLE KEYS */;
INSERT INTO `DOCUMENT` VALUES (24,'11',1,'',NULL,NULL,NULL,NULL,NULL,'2017-11-17 17:47:10'),(33,'AA2',2,'',NULL,NULL,NULL,NULL,NULL,'2017-11-19 22:06:49'),(154,'',1,'',NULL,NULL,NULL,NULL,NULL,'2017-11-21 15:13:38'),(155,'AA',5,'',NULL,NULL,NULL,NULL,NULL,'2017-11-21 15:20:50'),(157,'TEST',5,'',NULL,NULL,NULL,NULL,NULL,'2017-11-22 10:13:38'),(160,'A',5,'',NULL,NULL,NULL,NULL,NULL,'2017-11-22 10:28:07'),(161,'',4,'',NULL,NULL,NULL,NULL,NULL,'2017-11-22 10:37:27'),(167,'',1,'',NULL,NULL,NULL,NULL,NULL,'2017-11-22 13:00:17'),(169,'',1,'',NULL,NULL,NULL,NULL,NULL,'2017-11-22 13:03:03'),(187,'',1,'',NULL,NULL,NULL,NULL,NULL,'2017-11-25 10:21:05'),(191,'AAA',5,'',NULL,NULL,NULL,NULL,NULL,'2017-11-25 18:20:10'),(192,'AAAA',1,'DDDD',NULL,NULL,'2017-01-01 00:00:00',NULL,'2017-12-14 00:00:00','2017-12-06 21:26:17'),(193,'',1,'',NULL,'2017-12-11 00:00:00',NULL,NULL,NULL,'2017-12-06 21:26:33'),(199,'BBB',1,'EEEE',NULL,NULL,'2017-12-15 00:00:00',NULL,NULL,'2017-12-14 17:19:15'),(200,'AA',1,'NN',NULL,'2017-12-05 00:00:00',NULL,NULL,'2017-12-14 00:00:00','2017-12-14 17:44:58'),(215,'AA',1,'BB',NULL,NULL,NULL,NULL,NULL,'2017-12-17 12:15:22'),(216,'WORK',3,'PERMIT',NULL,NULL,NULL,NULL,NULL,'2017-12-18 09:38:01');
/*!40000 ALTER TABLE `DOCUMENT` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_DOCUMENT BEFORE INSERT ON DOCUMENT
FOR EACH ROW
BEGIN
    IF NEW.cd_document IS NULL THEN
        SET NEW.cd_document = nextval('DOCUMENT');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `DOCUMENT_FILE`
--

DROP TABLE IF EXISTS `DOCUMENT_FILE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DOCUMENT_FILE` (
  `cd_document_file` int(11) NOT NULL,
  `ds_document_file_hash` char(32) NOT NULL,
  `ds_document_file_path` longtext,
  `ds_document_file_thumbs_path` longtext,
  `dt_record` datetime DEFAULT CURRENT_TIMESTAMP,
  `ds_file_extension` varchar(16) NOT NULL,
  PRIMARY KEY (`cd_document_file`),
  UNIQUE KEY `IUNDOCUMENT_FILE001` (`ds_document_file_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DOCUMENT_FILE`
--

LOCK TABLES `DOCUMENT_FILE` WRITE;
/*!40000 ALTER TABLE `DOCUMENT_FILE` DISABLE KEYS */;
INSERT INTO `DOCUMENT_FILE` VALUES (3,'c8c17b1bb27d9ce14f6a24b15610126f','/var/www/hrms/document_repository/hrms/c/8/','/var/www/hrms/document_repository/hrms/thumbs/c/8/','2017-11-19 15:13:48','jpg'),(6,'788045ef0d93c03c128c95f6a8289bad','/var/www/hrms/document_repository/hrms/7/8/','/var/www/hrms/document_repository/hrms/thumbs/7/8/','2017-11-19 16:05:33','pdf'),(7,'90d90ac386dab78a1588ca7486ef82d2','/var/www/hrms/document_repository/hrms/9/0/','/var/www/hrms/document_repository/hrms/thumbs/9/0/','2017-11-19 16:06:40','xlsx');
/*!40000 ALTER TABLE `DOCUMENT_FILE` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_DOCUMENT_FILE BEFORE INSERT ON DOCUMENT_FILE
FOR EACH ROW
BEGIN
    IF NEW.cd_document_file IS NULL THEN
        SET NEW.cd_document_file = nextval('DOCUMENT_FILE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `DOCUMENT_REPOSITORY`
--

DROP TABLE IF EXISTS `DOCUMENT_REPOSITORY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DOCUMENT_REPOSITORY` (
  `cd_document_repository` int(11) NOT NULL,
  `ds_document_repository` longtext NOT NULL,
  `ds_original_file` longtext NOT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cd_document_repository_type` int(11) NOT NULL,
  `cd_document_file` int(11) NOT NULL,
  PRIMARY KEY (`cd_document_repository`),
  KEY `FKDOCUMENT_REPOSITORY001` (`cd_document_repository_type`),
  KEY `FKDOCUMENT_REPOSITORY002` (`cd_document_file`),
  CONSTRAINT `FKDOCUMENT_REPOSITORY001` FOREIGN KEY (`cd_document_repository_type`) REFERENCES `DOCUMENT_REPOSITORY_TYPE` (`cd_document_repository_type`),
  CONSTRAINT `FKDOCUMENT_REPOSITORY002` FOREIGN KEY (`cd_document_file`) REFERENCES `DOCUMENT_FILE` (`cd_document_file`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DOCUMENT_REPOSITORY`
--

LOCK TABLES `DOCUMENT_REPOSITORY` WRITE;
/*!40000 ALTER TABLE `DOCUMENT_REPOSITORY` DISABLE KEYS */;
INSERT INTO `DOCUMENT_REPOSITORY` VALUES (1,'frente','BZ1707301638587140.jpg','2017-11-19 15:13:48',6,3),(3,'frente','BZ1707301638587140.jpg','2017-11-19 15:17:02',6,3),(4,'foto','BZ1707301638587140.jpg','2017-11-19 15:18:06',6,3),(5,'foto','BZ1707301638587140.jpg','2017-11-19 15:18:57',6,3),(6,'doc','BZ1707301638587140.jpg','2017-11-19 15:27:57',6,3),(7,'face','BZ1707301638587140.jpg','2017-11-19 15:33:34',6,3),(10,'pedefe','samplecorrectiondefault.pdf','2017-11-19 16:05:33',7,6),(11,'meu lindo','BZ1707301638587140.jpg','2017-11-19 16:06:03',6,3),(12,'aa','Animation List.xlsx','2017-11-19 16:06:40',11,7),(13,'aa','samplecorrectiondefault.pdf','2017-11-21 15:21:02',7,6);
/*!40000 ALTER TABLE `DOCUMENT_REPOSITORY` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_DOCUMENT_REPOSITORY BEFORE INSERT ON DOCUMENT_REPOSITORY
FOR EACH ROW
BEGIN
    IF NEW.cd_document_repository IS NULL THEN
        SET NEW.cd_document_repository = nextval('DOCUMENT_REPOSITORY');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `DOCUMENT_REPOSITORY_CATEGORY`
--

DROP TABLE IF EXISTS `DOCUMENT_REPOSITORY_CATEGORY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DOCUMENT_REPOSITORY_CATEGORY` (
  `cd_document_repository_category` int(11) NOT NULL,
  `ds_document_repository_category` varchar(64) DEFAULT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cd_system_permission` int(11) DEFAULT NULL,
  `fl_specific_purpose` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`cd_document_repository_category`),
  UNIQUE KEY `IUNDOCUMENT_REPOSITORY_CATEGORY001` (`ds_document_repository_category`),
  KEY `FK_DOCUMENT_REPOSITORY_CATEGORY001` (`cd_system_permission`),
  CONSTRAINT `FK_DOCUMENT_REPOSITORY_CATEGORY001` FOREIGN KEY (`cd_system_permission`) REFERENCES `SYSTEM_PERMISSION` (`cd_system_permission`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DOCUMENT_REPOSITORY_CATEGORY`
--

LOCK TABLES `DOCUMENT_REPOSITORY_CATEGORY` WRITE;
/*!40000 ALTER TABLE `DOCUMENT_REPOSITORY_CATEGORY` DISABLE KEYS */;
INSERT INTO `DOCUMENT_REPOSITORY_CATEGORY` VALUES (1,'IMAGES',NULL,'2014-11-21 15:41:46',NULL,'N'),(2,'DOCUMENTS',NULL,'2014-11-21 15:41:46',NULL,'N'),(3,'IMAGES PROTECTED',NULL,'2014-12-01 17:02:21',2,'N'),(4,'AUTOCAD',NULL,'2015-06-18 15:33:59',NULL,'N'),(5,'GENERIC SPECIFICATION IMAGES',NULL,'2015-11-19 11:06:07',NULL,'Y');
/*!40000 ALTER TABLE `DOCUMENT_REPOSITORY_CATEGORY` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_DOCUMENT_REPOSITORY_CATEGORY BEFORE INSERT ON DOCUMENT_REPOSITORY_CATEGORY
FOR EACH ROW
BEGIN
    IF NEW.cd_document_repository_category IS NULL THEN
        SET NEW.cd_document_repository_category = nextval('DOCUMENT_REPOSITORY_CATEGORY');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `DOCUMENT_REPOSITORY_TYPE`
--

DROP TABLE IF EXISTS `DOCUMENT_REPOSITORY_TYPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DOCUMENT_REPOSITORY_TYPE` (
  `cd_document_repository_type` int(11) NOT NULL,
  `ds_document_repository_type` varchar(64) NOT NULL,
  `ds_document_repository_extension` varchar(16) DEFAULT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime DEFAULT CURRENT_TIMESTAMP,
  `cd_document_repository_category` int(11) NOT NULL,
  `fl_generate_thumbs` char(1) NOT NULL DEFAULT 'Y',
  `nr_thumbs_width` smallint(6) DEFAULT '0',
  `nr_thumbs_height` smallint(6) DEFAULT '0',
  `fl_thumbs_two_step` char(1) NOT NULL DEFAULT 'N',
  `ds_icon` varchar(32) DEFAULT NULL,
  `ds_mime_type` longtext,
  `fl_thumbs_high_quality` char(1) NOT NULL DEFAULT 'N',
  `fl_is_image` char(1) DEFAULT 'N',
  `nr_max_size_kb` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cd_document_repository_type`),
  UNIQUE KEY `IUN_DOCUMENT_REPOSITORY_TYPE001` (`ds_document_repository_type`),
  UNIQUE KEY `IUNDOCUMENT_REPOSITORY_TYPE002` (`ds_document_repository_extension`,`cd_document_repository_category`),
  KEY `FKDOCUMENT_REPOSITORY_TYPE001` (`cd_document_repository_category`),
  CONSTRAINT `FKDOCUMENT_REPOSITORY_TYPE001` FOREIGN KEY (`cd_document_repository_category`) REFERENCES `DOCUMENT_REPOSITORY_CATEGORY` (`cd_document_repository_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DOCUMENT_REPOSITORY_TYPE`
--

LOCK TABLES `DOCUMENT_REPOSITORY_TYPE` WRITE;
/*!40000 ALTER TABLE `DOCUMENT_REPOSITORY_TYPE` DISABLE KEYS */;
INSERT INTO `DOCUMENT_REPOSITORY_TYPE` VALUES (6,'JPG FILE','jpg',NULL,'2014-11-21 16:33:11',1,'Y',180,120,'N','fa fa-file-image-o','image/jpeg;image/pjpeg','N','Y',512),(7,'PDF DOCUMENT','pdf',NULL,'2014-11-21 16:37:09',2,'Y',180,120,'N','fa fa-file-pdf-o','application/pdf','N','N',512),(8,'WORD DOCUMENT DOC','doc',NULL,'2014-11-21 16:38:07',2,'Y',180,120,'Y','fa fa-file-word-o','application/msword','N','N',512),(9,'WORD DOCUMENTO DOCX','docx',NULL,'2014-11-21 16:38:07',2,'Y',180,120,'Y','fa fa-file-word-o','application/msword','N','N',512),(10,'EXCEL DOCUMENT XLS 4','xls',NULL,'2014-11-21 16:38:07',2,'Y',180,120,'Y','fa fa-file-excel-o','application/excel','N','N',512),(11,'EXCEL DOCUMENT XLSX 5','xlsx',NULL,'2014-11-21 16:38:07',2,'Y',180,120,'Y','fa fa-file-excel-o','application/excel','N','N',512),(12,'PNG FILE','png',NULL,'2014-11-21 16:56:06',1,'Y',180,120,'N','fa fa-file-image-o','image/png','N','Y',512),(13,'SPECIAL JPG FILE','jpg',NULL,'2014-12-01 17:06:00',3,'Y',180,120,'N','fa fa-file-image-o','image/jpeg;image/pjpeg','N','Y',512),(14,'POWERPOINT PPT','ppt',NULL,'2014-12-12 17:01:10',2,'Y',180,120,'Y','fa fa-file-powerpoint-o','application/mspowerpoint;application/powerpoint;application/vnd.ms-powerpoint;application/x-mspowerpoint','N','N',512),(15,'POWERPOINT PPTX','pptx',NULL,'2014-12-12 17:05:43',2,'Y',180,120,'Y','fa fa-file-powerpoint-o','application/mspowerpoint;application/powerpoint;application/vnd.ms-powerpoint;application/x-mspowerpoint','N','N',512),(16,'JPEG SPEC FILE','jpg',NULL,'2015-11-19 11:19:15',5,'Y',196,196,'N','fa fa-file-image-o','image/jpeg;image/pjpeg','Y','Y',512),(17,'PNG SPEC FILE','png',NULL,'2015-11-19 11:19:15',5,'Y',128,128,'N','fa fa-file-image-o','image/png','Y','Y',512),(19,'TEST','cad',NULL,'2016-10-22 10:47:13',4,'Y',180,120,'Y','fa fa-file-image-o','application/cad','Y','N',512),(21,'JPEG FILE','jpeg',NULL,'2016-11-03 17:47:31',1,'Y',180,120,'Y','fa fa-file-image-o','image/jpeg;image/pjpeg','Y','Y',512);
/*!40000 ALTER TABLE `DOCUMENT_REPOSITORY_TYPE` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_DOCUMENT_REPOSITORY_TYPE BEFORE INSERT ON DOCUMENT_REPOSITORY_TYPE
FOR EACH ROW
BEGIN
    IF NEW.cd_document_repository_type IS NULL THEN
        SET NEW.cd_document_repository_type = nextval('DOCUMENT_REPOSITORY_TYPE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `DOCUMENT_TYPE`
--

DROP TABLE IF EXISTS `DOCUMENT_TYPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DOCUMENT_TYPE` (
  `cd_document_type` int(11) NOT NULL,
  `ds_document_type` varchar(64) NOT NULL,
  `nr_order` int(11) DEFAULT '0',
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_document_type`),
  UNIQUE KEY `IUNDOCUMENT_TYPE001` (`ds_document_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DOCUMENT_TYPE`
--

LOCK TABLES `DOCUMENT_TYPE` WRITE;
/*!40000 ALTER TABLE `DOCUMENT_TYPE` DISABLE KEYS */;
INSERT INTO `DOCUMENT_TYPE` VALUES (1,'PASSPORT',10,NULL,'2017-11-05 19:50:10'),(2,'NATIONAL ID',2,NULL,'2017-11-05 19:50:10'),(3,'WORK PERMIT',8,NULL,'2017-11-05 19:50:10'),(4,'RESIDENCE PERMIT',6,NULL,'2017-11-05 19:50:10'),(5,'TOURIST VISTA',7,NULL,'2017-11-05 19:50:10'),(6,'BUSINESS VISA',4,NULL,'2017-11-05 19:50:10'),(7,'LABOR CONTRACT',3,NULL,'2017-11-05 19:50:10'),(8,'RECEIPT',5,NULL,'2017-11-05 19:50:10'),(9,'PICTURE',0,NULL,'2017-12-07 18:35:30');
/*!40000 ALTER TABLE `DOCUMENT_TYPE` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_DOCUMENT_TYPE BEFORE INSERT ON DOCUMENT_TYPE
FOR EACH ROW
BEGIN
    IF NEW.cd_document_type IS NULL THEN
        SET NEW.cd_document_type = nextval('DOCUMENT_TYPE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `DOCUMENT_X_DOCUMENT_REPOSITORY`
--

DROP TABLE IF EXISTS `DOCUMENT_X_DOCUMENT_REPOSITORY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DOCUMENT_X_DOCUMENT_REPOSITORY` (
  `cd_document_x_document_repository` int(11) NOT NULL,
  `cd_document` int(11) NOT NULL,
  `cd_document_repository` int(11) NOT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_document_x_document_repository`),
  KEY `FKEMPLOYEE_X_DOCUMENT_REPOSITORY02` (`cd_document_repository`),
  KEY `FKEMPLOYEE_X_DOCUMENT_REPOSITORY01_idx` (`cd_document`),
  CONSTRAINT `FKDOCUMENT_X_DOCUMENT_REPOSITORY` FOREIGN KEY (`cd_document`) REFERENCES `DOCUMENT` (`cd_document`) ON DELETE CASCADE,
  CONSTRAINT `FKEMPLOYEE_X_DOCUMENT_REPOSITORY02` FOREIGN KEY (`cd_document_repository`) REFERENCES `DOCUMENT_REPOSITORY` (`cd_document_repository`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DOCUMENT_X_DOCUMENT_REPOSITORY`
--

LOCK TABLES `DOCUMENT_X_DOCUMENT_REPOSITORY` WRITE;
/*!40000 ALTER TABLE `DOCUMENT_X_DOCUMENT_REPOSITORY` DISABLE KEYS */;
INSERT INTO `DOCUMENT_X_DOCUMENT_REPOSITORY` VALUES (2,24,11,'2017-11-19 16:06:03');
/*!40000 ALTER TABLE `DOCUMENT_X_DOCUMENT_REPOSITORY` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_DOCUMENT_X_DOCUMENT_REPOSITORY BEFORE INSERT ON `DOCUMENT_X_DOCUMENT_REPOSITORY` FOR EACH ROW
BEGIN
    IF NEW.cd_document_x_document_repository IS NULL THEN
        SET NEW.cd_document_x_document_repository = nextval('DOCUMENT_X_DOCUMENT_REPOSITORY');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `DUMMY`
--

DROP TABLE IF EXISTS `DUMMY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DUMMY` (
  `cd_dummy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DUMMY`
--

LOCK TABLES `DUMMY` WRITE;
/*!40000 ALTER TABLE `DUMMY` DISABLE KEYS */;
INSERT INTO `DUMMY` VALUES (1);
/*!40000 ALTER TABLE `DUMMY` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `EDUCATION`
--

DROP TABLE IF EXISTS `EDUCATION`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EDUCATION` (
  `cd_education` int(11) NOT NULL,
  `ds_education` varchar(64) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_education`),
  UNIQUE KEY `IUNEDUCATION001` (`ds_education`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EDUCATION`
--

LOCK TABLES `EDUCATION` WRITE;
/*!40000 ALTER TABLE `EDUCATION` DISABLE KEYS */;
INSERT INTO `EDUCATION` VALUES (1,'UNIVERSITY',NULL,'2017-11-11 16:43:31');
/*!40000 ALTER TABLE `EDUCATION` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_EDUCATION BEFORE INSERT ON EDUCATION
FOR EACH ROW
BEGIN
    IF NEW.cd_education IS NULL THEN
        SET NEW.cd_education = nextval('EDUCATION');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `EMPLOYEE`
--

DROP TABLE IF EXISTS `EMPLOYEE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EMPLOYEE` (
  `cd_employee` int(11) NOT NULL,
  `ds_employee` varchar(128) NOT NULL,
  `ds_employee_reference` varchar(64) NOT NULL,
  `dt_join` datetime NOT NULL,
  `cd_human_resource` int(11) DEFAULT NULL,
  `cd_employee_type` int(11) NOT NULL,
  `cd_division` int(11) DEFAULT NULL,
  `cd_department` int(11) DEFAULT NULL,
  `cd_employee_position` int(11) DEFAULT NULL,
  `nr_initial_salary` decimal(18,2) DEFAULT '0.00',
  `nr_current_salary` decimal(18,2) DEFAULT '0.00',
  `nr_hour_bank` int(11) DEFAULT '0',
  `ds_note` longtext,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cd_personal_info` int(11) NOT NULL,
  `ds_e_mail` varchar(64) DEFAULT NULL,
  `cd_location` int(11) DEFAULT NULL,
  PRIMARY KEY (`cd_employee`),
  KEY `EMPLOYEE_Index01` (`cd_personal_info`),
  KEY `EMPLOYEE_Index02` (`cd_human_resource`),
  KEY `FKEMPLOYEE02` (`cd_employee_type`),
  KEY `FKEMPLOYEE03` (`cd_division`),
  KEY `FKEMPLOYEE04` (`cd_department`),
  KEY `FKEMPLOYEE05` (`cd_employee_position`),
  KEY `FKEMPLOYEE07_idx` (`cd_location`),
  CONSTRAINT `FKEMPLOYEE01` FOREIGN KEY (`cd_human_resource`) REFERENCES `HUMAN_RESOURCE` (`cd_human_resource`),
  CONSTRAINT `FKEMPLOYEE02` FOREIGN KEY (`cd_employee_type`) REFERENCES `EMPLOYEE_TYPE` (`cd_employee_type`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FKEMPLOYEE03` FOREIGN KEY (`cd_division`) REFERENCES `DIVISION` (`cd_division`),
  CONSTRAINT `FKEMPLOYEE04` FOREIGN KEY (`cd_department`) REFERENCES `DEPARTMENT` (`cd_department`),
  CONSTRAINT `FKEMPLOYEE05` FOREIGN KEY (`cd_employee_position`) REFERENCES `EMPLOYEE_POSITION` (`cd_employee_position`),
  CONSTRAINT `FKEMPLOYEE06` FOREIGN KEY (`cd_personal_info`) REFERENCES `PERSONAL_INFO` (`cd_personal_info`) ON DELETE CASCADE,
  CONSTRAINT `FKEMPLOYEE07` FOREIGN KEY (`cd_location`) REFERENCES `LOCATION` (`cd_location`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EMPLOYEE`
--

LOCK TABLES `EMPLOYEE` WRITE;
/*!40000 ALTER TABLE `EMPLOYEE` DISABLE KEYS */;
INSERT INTO `EMPLOYEE` VALUES (280,'GABRIEL BLOS','reference','2017-02-16 00:00:00',277,3,6,7,3,0.00,0.00,0,'carlos\ngui','2017-11-15 16:54:03',285,'testa',3),(339,'DAVIDSON NUNES','111','2017-12-07 00:00:00',356,4,6,NULL,3,0.00,0.00,0,NULL,'2017-12-06 21:26:17',650,NULL,3);
/*!40000 ALTER TABLE `EMPLOYEE` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_EMPLOYEE BEFORE INSERT ON EMPLOYEE
FOR EACH ROW
BEGIN
    IF NEW.cd_employee IS NULL THEN
        SET NEW.cd_employee = nextval('EMPLOYEE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `EMPLOYEE_ASSETS`
--

DROP TABLE IF EXISTS `EMPLOYEE_ASSETS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EMPLOYEE_ASSETS` (
  `cd_employee_assets` int(11) NOT NULL,
  `cd_employee` int(11) NOT NULL,
  `cd_asset_type` int(11) NOT NULL,
  `ds_asset_description` varchar(255) NOT NULL,
  `ds_asset_number` varchar(255) NOT NULL,
  `dt_received_date` datetime DEFAULT NULL,
  `dt_returned_date` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_employee_assets`),
  KEY `FKEMPLOYEE_ASSETS01` (`cd_employee`),
  KEY `FKEMPLOYEE_ASSETS02` (`cd_asset_type`),
  CONSTRAINT `FKEMPLOYEE_ASSETS01` FOREIGN KEY (`cd_employee`) REFERENCES `EMPLOYEE` (`cd_employee`) ON DELETE CASCADE,
  CONSTRAINT `FKEMPLOYEE_ASSETS02` FOREIGN KEY (`cd_asset_type`) REFERENCES `ASSET_TYPE` (`cd_asset_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EMPLOYEE_ASSETS`
--

LOCK TABLES `EMPLOYEE_ASSETS` WRITE;
/*!40000 ALTER TABLE `EMPLOYEE_ASSETS` DISABLE KEYS */;
INSERT INTO `EMPLOYEE_ASSETS` VALUES (2,280,1,'OUTRO TABLET','NUMBER2','2017-12-01 00:00:00','2017-12-01 00:00:00','2017-12-01 08:40:43'),(3,280,1,'MAIS UM','1233','2017-12-01 00:00:00',NULL,'2017-12-01 08:44:55'),(4,339,1,'TABLET','11231','2017-12-06 00:00:00',NULL,'2017-12-06 21:28:00');
/*!40000 ALTER TABLE `EMPLOYEE_ASSETS` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_EMPLOYEE_ASSETS BEFORE INSERT ON EMPLOYEE_ASSETS
FOR EACH ROW
BEGIN
    IF NEW.cd_employee_assets IS NULL THEN
        SET NEW.cd_employee_assets = nextval('EMPLOYEE_ASSETS');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `EMPLOYEE_BANK_BRANCH_X_BENEFIT_TYPE`
--

DROP TABLE IF EXISTS `EMPLOYEE_BANK_BRANCH_X_BENEFIT_TYPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EMPLOYEE_BANK_BRANCH_X_BENEFIT_TYPE` (
  `cd_employee_bank_branch_x_benefit_type` int(11) NOT NULL,
  `cd_employee_x_bank_branch` int(11) NOT NULL,
  `cd_benefit_type` int(11) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_employee_bank_branch_x_benefit_type`),
  UNIQUE KEY `EMPLOYEE_EMPLOYEE_TAGS_Index01` (`cd_employee_x_bank_branch`,`cd_benefit_type`),
  KEY `FKEMPLOYEE_BANK_BRANCH_X_BENEFIT_TYPES02` (`cd_employee_bank_branch_x_benefit_type`),
  KEY `FKEMPLOYEE_BANK_BRANCH_X_BENEFIT_TYPE02` (`cd_benefit_type`),
  CONSTRAINT `FKEMPLOYEE_BANK_BRANCH_X_BENEFIT_TYPE01` FOREIGN KEY (`cd_employee_x_bank_branch`) REFERENCES `EMPLOYEE_X_BANK_BRANCH` (`cd_employee_x_bank_branch`) ON DELETE CASCADE,
  CONSTRAINT `FKEMPLOYEE_BANK_BRANCH_X_BENEFIT_TYPE02` FOREIGN KEY (`cd_benefit_type`) REFERENCES `BENEFIT_TYPE` (`cd_benefit_type`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EMPLOYEE_BANK_BRANCH_X_BENEFIT_TYPE`
--

LOCK TABLES `EMPLOYEE_BANK_BRANCH_X_BENEFIT_TYPE` WRITE;
/*!40000 ALTER TABLE `EMPLOYEE_BANK_BRANCH_X_BENEFIT_TYPE` DISABLE KEYS */;
INSERT INTO `EMPLOYEE_BANK_BRANCH_X_BENEFIT_TYPE` VALUES (2,56,7,NULL,'2017-11-24 12:54:11'),(3,56,2,'2017-11-24 13:08:02','2017-11-24 12:54:11'),(4,56,6,'2017-11-24 13:20:18','2017-11-24 13:05:33'),(5,56,4,NULL,'2017-11-24 13:20:18');
/*!40000 ALTER TABLE `EMPLOYEE_BANK_BRANCH_X_BENEFIT_TYPE` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_EMPLOYEE_BANK_BRANCH_X_BENEFIT_TYPE BEFORE INSERT ON EMPLOYEE_BANK_BRANCH_X_BENEFIT_TYPE
FOR EACH ROW
BEGIN
    IF NEW.cd_employee_bank_branch_x_benefit_type IS NULL THEN
        SET NEW.cd_employee_bank_branch_x_benefit_type = nextval('EMPLOYEE_BANK_BRANCH_X_BENEFIT_TYPE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `EMPLOYEE_BENEFIT_TYPE`
--

DROP TABLE IF EXISTS `EMPLOYEE_BENEFIT_TYPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EMPLOYEE_BENEFIT_TYPE` (
  `cd_employee_benefit_type` int(11) NOT NULL,
  `cd_employee` int(11) NOT NULL,
  `cd_benefit_type` int(11) NOT NULL,
  `cd_benefit_frequency` int(11) DEFAULT NULL,
  `nr_inital_value` decimal(12,2) DEFAULT '0.00',
  `nr_last_received` decimal(12,2) DEFAULT '0.00',
  `dt_deactivated` datetime DEFAULT NULL,
  `cd_benefit_operation` int(11) NOT NULL,
  `cd_benefit_kind` int(11) NOT NULL,
  `cd_benefit_unit` int(11) NOT NULL,
  PRIMARY KEY (`cd_employee_benefit_type`),
  UNIQUE KEY `IUN EMPLOYEE_BENEFIT_TYPE001` (`cd_employee`,`cd_benefit_type`),
  KEY `FK EMPLOYEE_BENEFIT_TYPE001_idx` (`cd_benefit_type`),
  KEY `FKEMPLOYEE_BENEFIT_TYPE002_idx` (`cd_benefit_frequency`),
  KEY `FKEMPLOYEE_BENEFIT_TYPE004_idx` (`cd_benefit_kind`),
  KEY `FKEMPLOYEE_BENEFIT_TYPE005_idx` (`cd_benefit_operation`),
  KEY `FKEMPLOYEE_BENEFIT_TYPE006` (`cd_benefit_unit`),
  CONSTRAINT `FKEMPLOYEE_BENEFIT_TYPE001` FOREIGN KEY (`cd_benefit_type`) REFERENCES `BENEFIT_TYPE` (`cd_benefit_type`) ON UPDATE CASCADE,
  CONSTRAINT `FKEMPLOYEE_BENEFIT_TYPE002` FOREIGN KEY (`cd_benefit_frequency`) REFERENCES `BENEFIT_FREQUENCY` (`cd_benefit_frequency`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FKEMPLOYEE_BENEFIT_TYPE003` FOREIGN KEY (`cd_employee`) REFERENCES `EMPLOYEE` (`cd_employee`) ON UPDATE CASCADE,
  CONSTRAINT `FKEMPLOYEE_BENEFIT_TYPE004` FOREIGN KEY (`cd_benefit_kind`) REFERENCES `BENEFIT_KIND` (`cd_benefit_kind`),
  CONSTRAINT `FKEMPLOYEE_BENEFIT_TYPE005` FOREIGN KEY (`cd_benefit_operation`) REFERENCES `BENEFIT_OPERATION` (`cd_benefit_operation`),
  CONSTRAINT `FKEMPLOYEE_BENEFIT_TYPE006` FOREIGN KEY (`cd_benefit_unit`) REFERENCES `BENEFIT_UNIT` (`cd_benefit_unit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EMPLOYEE_BENEFIT_TYPE`
--

LOCK TABLES `EMPLOYEE_BENEFIT_TYPE` WRITE;
/*!40000 ALTER TABLE `EMPLOYEE_BENEFIT_TYPE` DISABLE KEYS */;
INSERT INTO `EMPLOYEE_BENEFIT_TYPE` VALUES (29,339,1,1,1.00,0.00,NULL,1,1,1),(31,339,8,1,1.00,0.00,NULL,1,1,1),(32,280,2,1,100.00,0.00,NULL,1,1,1),(33,280,3,2,1.00,0.00,NULL,1,2,4),(34,280,8,1,30000.00,0.00,NULL,1,1,1),(35,339,3,2,1.00,0.00,NULL,1,2,4),(36,339,6,1,0.00,0.00,NULL,1,1,1),(37,339,5,1,0.00,0.00,NULL,2,1,1);
/*!40000 ALTER TABLE `EMPLOYEE_BENEFIT_TYPE` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_EMPLOYEE_BENEFIT_TYPE BEFORE INSERT ON EMPLOYEE_BENEFIT_TYPE
FOR EACH ROW
BEGIN
    IF NEW.cd_employee_benefit_type IS NULL THEN
        SET NEW.cd_employee_benefit_type = nextval('EMPLOYEE_BENEFIT_TYPE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `EMPLOYEE_POSITION`
--

DROP TABLE IF EXISTS `EMPLOYEE_POSITION`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EMPLOYEE_POSITION` (
  `cd_employee_position` int(11) NOT NULL,
  `ds_employee_position` varchar(64) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nr_market_average` decimal(18,2) DEFAULT '0.00',
  `ds_job_description` longtext,
  PRIMARY KEY (`cd_employee_position`),
  UNIQUE KEY `IUNEMPLOYEE_POSITION001` (`ds_employee_position`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EMPLOYEE_POSITION`
--

LOCK TABLES `EMPLOYEE_POSITION` WRITE;
/*!40000 ALTER TABLE `EMPLOYEE_POSITION` DISABLE KEYS */;
INSERT INTO `EMPLOYEE_POSITION` VALUES (1,'SECRETARY',NULL,'2017-11-05 22:37:53',4000.00,'SECRETARY'),(3,'BOSS',NULL,'2017-12-04 19:58:03',40000.00,NULL);
/*!40000 ALTER TABLE `EMPLOYEE_POSITION` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_EMPLOYEE_POSITION BEFORE INSERT ON EMPLOYEE_POSITION
FOR EACH ROW
BEGIN
    IF NEW.cd_employee_position IS NULL THEN
        SET NEW.cd_employee_position = nextval('EMPLOYEE_POSITION');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `EMPLOYEE_POSITION_X_BENEFIT_TYPE`
--

DROP TABLE IF EXISTS `EMPLOYEE_POSITION_X_BENEFIT_TYPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EMPLOYEE_POSITION_X_BENEFIT_TYPE` (
  `cd_employee_position_x_benefit_type` int(11) NOT NULL,
  `cd_employee_position` int(11) NOT NULL,
  `cd_benefit_type` int(11) NOT NULL,
  `nr_value_min` decimal(10,2) DEFAULT '0.00',
  `nr_value_max` decimal(10,2) DEFAULT '0.00',
  `ds_notes` varchar(255) DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_employee_position_x_benefit_type`),
  UNIQUE KEY `IUN EMPLOYEE_POSITION_X_BENEFIT_TYPE001` (`cd_employee_position`,`cd_benefit_type`),
  KEY `IUN EMPLOYEE_POSITION_X_BENEFIT_TYPE001_idx` (`cd_benefit_type`),
  CONSTRAINT `IUNEMPLOYEE_POSITION_X_BENEFIT_TYPE001` FOREIGN KEY (`cd_benefit_type`) REFERENCES `BENEFIT_TYPE` (`cd_benefit_type`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `IUNEMPLOYEE_POSITION_X_BENEFIT_TYPE002` FOREIGN KEY (`cd_employee_position`) REFERENCES `EMPLOYEE_POSITION` (`cd_employee_position`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EMPLOYEE_POSITION_X_BENEFIT_TYPE`
--

LOCK TABLES `EMPLOYEE_POSITION_X_BENEFIT_TYPE` WRITE;
/*!40000 ALTER TABLE `EMPLOYEE_POSITION_X_BENEFIT_TYPE` DISABLE KEYS */;
INSERT INTO `EMPLOYEE_POSITION_X_BENEFIT_TYPE` VALUES (2,3,2,500.00,600.00,NULL,'2017-12-04 00:00:00'),(4,3,3,100.00,200.00,NULL,'2017-12-04 00:00:00'),(9,3,8,10000.00,20000.00,NULL,'2017-12-05 20:25:08');
/*!40000 ALTER TABLE `EMPLOYEE_POSITION_X_BENEFIT_TYPE` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_EMPLOYEE_POSITION_X_BENEFIT_TYPE BEFORE INSERT ON EMPLOYEE_POSITION_X_BENEFIT_TYPE
FOR EACH ROW
BEGIN
    IF NEW.cd_employee_position_x_benefit_type IS NULL THEN
        SET NEW.cd_employee_position_x_benefit_type = nextval('EMPLOYEE_POSITION_X_BENEFIT_TYPE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `EMPLOYEE_TAGS`
--

DROP TABLE IF EXISTS `EMPLOYEE_TAGS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EMPLOYEE_TAGS` (
  `cd_employee_tags` int(11) NOT NULL,
  `ds_employee_tags` varchar(255) NOT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dt_deactivated` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_employee_tags`),
  UNIQUE KEY `IUNEMPLOYEE_TAGS001` (`ds_employee_tags`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EMPLOYEE_TAGS`
--

LOCK TABLES `EMPLOYEE_TAGS` WRITE;
/*!40000 ALTER TABLE `EMPLOYEE_TAGS` DISABLE KEYS */;
INSERT INTO `EMPLOYEE_TAGS` VALUES (7,'TAG1','2017-11-19 21:42:02',NULL),(8,'TAG2','2017-11-19 21:42:02',NULL),(10,'NOVA','2017-11-20 14:30:15',NULL),(11,'TAG12','2017-11-24 13:14:40',NULL),(12,'CGB','2017-11-24 13:17:48',NULL),(13,'OUTRATAG','2017-11-24 13:20:00',NULL);
/*!40000 ALTER TABLE `EMPLOYEE_TAGS` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_EMPLOYEE_TAGS BEFORE INSERT ON EMPLOYEE_TAGS
FOR EACH ROW
BEGIN
    IF NEW.cd_employee_tags IS NULL THEN
        SET NEW.cd_employee_tags = nextval('EMPLOYEE_TAGS');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `EMPLOYEE_TYPE`
--

DROP TABLE IF EXISTS `EMPLOYEE_TYPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EMPLOYEE_TYPE` (
  `cd_employee_type` int(11) NOT NULL,
  `ds_employee_type` varchar(64) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_employee_type`),
  UNIQUE KEY `IUNEMPLOYEE_TYPE001` (`ds_employee_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EMPLOYEE_TYPE`
--

LOCK TABLES `EMPLOYEE_TYPE` WRITE;
/*!40000 ALTER TABLE `EMPLOYEE_TYPE` DISABLE KEYS */;
INSERT INTO `EMPLOYEE_TYPE` VALUES (2,'3RD PARTY',NULL,'2017-11-05 22:43:54'),(3,'FREE LANCER',NULL,'2017-11-05 22:43:54'),(4,'EMPLOYEE',NULL,'2017-11-05 22:43:54'),(5,'CONTRACTOR',NULL,'2017-11-05 22:43:54');
/*!40000 ALTER TABLE `EMPLOYEE_TYPE` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_EMPLOYEE_TYPE BEFORE INSERT ON EMPLOYEE_TYPE
FOR EACH ROW
BEGIN
    IF NEW.cd_employee_type IS NULL THEN
        SET NEW.cd_employee_type = nextval('EMPLOYEE_TYPE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `EMPLOYEE_X_ADDRESS`
--

DROP TABLE IF EXISTS `EMPLOYEE_X_ADDRESS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EMPLOYEE_X_ADDRESS` (
  `cd_employee_x_address` int(11) NOT NULL,
  `cd_employee` int(11) NOT NULL,
  `cd_address` int(11) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_employee_x_address`),
  UNIQUE KEY `EMPLOYEE_ADDRESS_Index01` (`cd_employee`,`cd_address`),
  KEY `FKEMPLOYEE_X_ADDRESS02` (`cd_address`),
  CONSTRAINT `FKEMPLOYEE_X_ADDRESS01` FOREIGN KEY (`cd_employee`) REFERENCES `EMPLOYEE` (`cd_employee`) ON DELETE CASCADE,
  CONSTRAINT `FKEMPLOYEE_X_ADDRESS02` FOREIGN KEY (`cd_address`) REFERENCES `ADDRESS` (`cd_address`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EMPLOYEE_X_ADDRESS`
--

LOCK TABLES `EMPLOYEE_X_ADDRESS` WRITE;
/*!40000 ALTER TABLE `EMPLOYEE_X_ADDRESS` DISABLE KEYS */;
INSERT INTO `EMPLOYEE_X_ADDRESS` VALUES (181,280,182,NULL,'2017-11-17 17:47:03'),(214,339,355,NULL,'2017-12-06 21:26:17');
/*!40000 ALTER TABLE `EMPLOYEE_X_ADDRESS` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_EMPLOYEE_X_ADDRESS BEFORE INSERT ON EMPLOYEE_X_ADDRESS
FOR EACH ROW
BEGIN
    IF NEW.cd_employee_x_address IS NULL THEN
        SET NEW.cd_employee_x_address = nextval('EMPLOYEE_X_ADDRESS');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `EMPLOYEE_X_BANK_BRANCH`
--

DROP TABLE IF EXISTS `EMPLOYEE_X_BANK_BRANCH`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EMPLOYEE_X_BANK_BRANCH` (
  `cd_employee_x_bank_branch` int(11) NOT NULL,
  `cd_employee` int(11) NOT NULL,
  `cd_bank_branch` int(11) NOT NULL,
  `ds_name_on_bank` varchar(64) NOT NULL,
  `ds_account_number` varchar(64) NOT NULL,
  `cd_currency` int(11) NOT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_employee_x_bank_branch`),
  UNIQUE KEY `IDXUNIQEMPLOYEE` (`cd_employee`,`cd_bank_branch`),
  KEY `FK_EMPLOYEE_X_BANK_BRANCH002_idx` (`cd_bank_branch`),
  KEY `FK_EMPLOYEE_X_BANK_BRANCH003_idx` (`cd_currency`),
  CONSTRAINT `FK EMPLOYEE_X_BANK_BRANCH001` FOREIGN KEY (`cd_employee`) REFERENCES `EMPLOYEE` (`cd_employee`) ON DELETE CASCADE,
  CONSTRAINT `FK_EMPLOYEE_X_BANK_BRANCH002` FOREIGN KEY (`cd_bank_branch`) REFERENCES `BANK_BRANCH` (`cd_bank_branch`) ON DELETE CASCADE,
  CONSTRAINT `FK_EMPLOYEE_X_BANK_BRANCH003` FOREIGN KEY (`cd_currency`) REFERENCES `CURRENCY` (`cd_currency`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EMPLOYEE_X_BANK_BRANCH`
--

LOCK TABLES `EMPLOYEE_X_BANK_BRANCH` WRITE;
/*!40000 ALTER TABLE `EMPLOYEE_X_BANK_BRANCH` DISABLE KEYS */;
INSERT INTO `EMPLOYEE_X_BANK_BRANCH` VALUES (56,280,3,'aab','',2,'2017-11-23 21:26:23'),(63,339,3,'blalal','Numver',2,'2017-12-12 21:46:56');
/*!40000 ALTER TABLE `EMPLOYEE_X_BANK_BRANCH` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_EMPLOYEE_X_BANK_BRANCH BEFORE INSERT ON EMPLOYEE_X_BANK_BRANCH
FOR EACH ROW
BEGIN
    IF NEW.cd_employee_x_bank_branch IS NULL THEN
        SET NEW.cd_employee_x_bank_branch = nextval('EMPLOYEE_X_BANK_BRANCH');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `EMPLOYEE_X_CONTACT`
--

DROP TABLE IF EXISTS `EMPLOYEE_X_CONTACT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EMPLOYEE_X_CONTACT` (
  `cd_employee_x_contact` int(11) NOT NULL,
  `cd_employee` int(11) NOT NULL,
  `cd_contact` int(11) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_employee_x_contact`),
  UNIQUE KEY `EMPLOYEE_CONTACT_Index01` (`cd_employee`,`cd_contact`),
  KEY `FKEMPLOYEE_X_CONTACT02` (`cd_contact`),
  CONSTRAINT `FKEMPLOYEE_X_CONTACT01` FOREIGN KEY (`cd_employee`) REFERENCES `EMPLOYEE` (`cd_employee`) ON DELETE CASCADE,
  CONSTRAINT `FKEMPLOYEE_X_CONTACT02` FOREIGN KEY (`cd_contact`) REFERENCES `CONTACT` (`cd_contact`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EMPLOYEE_X_CONTACT`
--

LOCK TABLES `EMPLOYEE_X_CONTACT` WRITE;
/*!40000 ALTER TABLE `EMPLOYEE_X_CONTACT` DISABLE KEYS */;
INSERT INTO `EMPLOYEE_X_CONTACT` VALUES (69,280,69,NULL,'2017-11-17 17:47:03'),(101,339,232,NULL,'2017-12-06 21:26:17');
/*!40000 ALTER TABLE `EMPLOYEE_X_CONTACT` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_EMPLOYEE_X_CONTACT BEFORE INSERT ON EMPLOYEE_X_CONTACT
FOR EACH ROW
BEGIN
    IF NEW.cd_employee_x_contact IS NULL THEN
        SET NEW.cd_employee_x_contact = nextval('EMPLOYEE_X_CONTACT');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `EMPLOYEE_X_DEPENDENTS`
--

DROP TABLE IF EXISTS `EMPLOYEE_X_DEPENDENTS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EMPLOYEE_X_DEPENDENTS` (
  `cd_dependent_x_employee` int(11) NOT NULL,
  `cd_employee` int(11) NOT NULL,
  `cd_dependent` int(11) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_dependent_x_employee`),
  UNIQUE KEY `EMPLOYEE_X_DEPENDENTS_Index01` (`cd_employee`,`cd_dependent`),
  KEY `FKEMPLOYEE_X_DEPENDENTS02` (`cd_dependent`),
  CONSTRAINT `FKEMPLOYEE_X_DEPENDENTS01` FOREIGN KEY (`cd_employee`) REFERENCES `EMPLOYEE` (`cd_employee`) ON DELETE CASCADE,
  CONSTRAINT `FKEMPLOYEE_X_DEPENDENTS02` FOREIGN KEY (`cd_dependent`) REFERENCES `DEPENDENTS` (`cd_dependents`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EMPLOYEE_X_DEPENDENTS`
--

LOCK TABLES `EMPLOYEE_X_DEPENDENTS` WRITE;
/*!40000 ALTER TABLE `EMPLOYEE_X_DEPENDENTS` DISABLE KEYS */;
/*!40000 ALTER TABLE `EMPLOYEE_X_DEPENDENTS` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_EMPLOYEE_X_DEPENDENTS BEFORE INSERT ON EMPLOYEE_X_DEPENDENTS
FOR EACH ROW
BEGIN
    IF NEW.cd_dependent_x_employee IS NULL THEN
        SET NEW.cd_dependent_x_employee = nextval('EMPLOYEE_X_DEPENDENTS');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `EMPLOYEE_X_DOCUMENTS`
--

DROP TABLE IF EXISTS `EMPLOYEE_X_DOCUMENTS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EMPLOYEE_X_DOCUMENTS` (
  `cd_employee_x_documents` int(11) NOT NULL,
  `cd_employee` int(11) NOT NULL,
  `cd_document` int(11) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_employee_x_documents`),
  UNIQUE KEY `EMPLOYEE_X_DOCUMENTS_Index01` (`cd_employee`,`cd_document`),
  KEY `FKEMPLOYEE_X_DOCUMENTS02` (`cd_document`),
  CONSTRAINT `FKEMPLOYEE_X_DOCUMENTS01` FOREIGN KEY (`cd_employee`) REFERENCES `EMPLOYEE` (`cd_employee`) ON DELETE CASCADE,
  CONSTRAINT `FKEMPLOYEE_X_DOCUMENTS02` FOREIGN KEY (`cd_document`) REFERENCES `DOCUMENT` (`cd_document`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EMPLOYEE_X_DOCUMENTS`
--

LOCK TABLES `EMPLOYEE_X_DOCUMENTS` WRITE;
/*!40000 ALTER TABLE `EMPLOYEE_X_DOCUMENTS` DISABLE KEYS */;
INSERT INTO `EMPLOYEE_X_DOCUMENTS` VALUES (24,280,24,NULL,'2017-11-17 17:47:10'),(33,280,33,NULL,'2017-11-19 22:06:49'),(57,339,192,NULL,'2017-12-06 21:26:17'),(61,339,199,NULL,'2017-12-14 17:19:15');
/*!40000 ALTER TABLE `EMPLOYEE_X_DOCUMENTS` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_EMPLOYEE_X_DOCUMENTS BEFORE INSERT ON EMPLOYEE_X_DOCUMENTS
FOR EACH ROW
BEGIN
    IF NEW.cd_employee_x_documents IS NULL THEN
        SET NEW.cd_employee_x_documents = nextval('EMPLOYEE_X_DOCUMENTS');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `EMPLOYEE_X_EMPLOYEE_TAGS`
--

DROP TABLE IF EXISTS `EMPLOYEE_X_EMPLOYEE_TAGS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EMPLOYEE_X_EMPLOYEE_TAGS` (
  `cd_employee_x_employee_tags` int(11) NOT NULL,
  `cd_employee` int(11) NOT NULL,
  `cd_employee_tags` int(11) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_employee_x_employee_tags`),
  UNIQUE KEY `EMPLOYEE_EMPLOYEE_TAGS_Index01` (`cd_employee`,`cd_employee_tags`),
  KEY `FKEMPLOYEE_X_EMPLOYEE_TAGS02` (`cd_employee_tags`),
  CONSTRAINT `FKEMPLOYEE_X_EMPLOYEE_TAGS01` FOREIGN KEY (`cd_employee`) REFERENCES `EMPLOYEE` (`cd_employee`) ON DELETE CASCADE,
  CONSTRAINT `FKEMPLOYEE_X_EMPLOYEE_TAGS02` FOREIGN KEY (`cd_employee_tags`) REFERENCES `EMPLOYEE_TAGS` (`cd_employee_tags`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EMPLOYEE_X_EMPLOYEE_TAGS`
--

LOCK TABLES `EMPLOYEE_X_EMPLOYEE_TAGS` WRITE;
/*!40000 ALTER TABLE `EMPLOYEE_X_EMPLOYEE_TAGS` DISABLE KEYS */;
INSERT INTO `EMPLOYEE_X_EMPLOYEE_TAGS` VALUES (1,280,7,NULL,'2017-11-19 21:42:02'),(2,280,8,NULL,'2017-11-19 21:42:02'),(3,280,10,NULL,'2017-11-20 14:30:15'),(4,280,11,NULL,'2017-11-24 13:14:40'),(5,280,12,'2017-11-24 13:17:51','2017-11-24 13:17:48'),(6,280,13,NULL,'2017-11-24 13:20:00'),(7,339,12,'2017-12-06 21:27:05','2017-12-06 21:26:17'),(8,339,13,NULL,'2017-12-06 21:27:05');
/*!40000 ALTER TABLE `EMPLOYEE_X_EMPLOYEE_TAGS` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_EMPLOYEE_X_EMPLOYEE_TAGS BEFORE INSERT ON EMPLOYEE_X_EMPLOYEE_TAGS
FOR EACH ROW
BEGIN
    IF NEW.cd_employee_x_employee_tags IS NULL THEN
        SET NEW.cd_employee_x_employee_tags = nextval('EMPLOYEE_X_EMPLOYEE_TAGS');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `EXPENSE`
--

DROP TABLE IF EXISTS `EXPENSE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EXPENSE` (
  `cd_expense` int(11) NOT NULL,
  `dt_submit_date` datetime NOT NULL,
  `dt_approval_date` varchar(45) DEFAULT NULL,
  `cd_employee` int(11) DEFAULT NULL,
  `cd_employee_approval` int(11) DEFAULT NULL,
  `nr_cash_advance` decimal(12,2) DEFAULT '0.00',
  `cd_currency` int(11) DEFAULT NULL,
  `cd_payment_type` int(11) DEFAULT NULL,
  `fl_has_charge_back` varchar(1) DEFAULT NULL,
  `dt_settlement_date` datetime DEFAULT NULL,
  `ds_remarks` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cd_expense`),
  KEY `FKEXPENSE002_idx` (`cd_employee_approval`),
  KEY `FKEXPENSE003_idx` (`cd_payment_type`),
  KEY `FKEXPENSE004_idx` (`cd_currency`),
  KEY `FKEXPENSE005_idx` (`cd_employee`),
  CONSTRAINT `FKEXPENSE002` FOREIGN KEY (`cd_employee_approval`) REFERENCES `EMPLOYEE` (`cd_employee`),
  CONSTRAINT `FKEXPENSE003` FOREIGN KEY (`cd_payment_type`) REFERENCES `PAYMENT_TYPE` (`cd_payment_type`),
  CONSTRAINT `FKEXPENSE004` FOREIGN KEY (`cd_currency`) REFERENCES `CURRENCY` (`cd_currency`),
  CONSTRAINT `FKEXPENSE005` FOREIGN KEY (`cd_employee`) REFERENCES `EMPLOYEE` (`cd_employee`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EXPENSE`
--

LOCK TABLES `EXPENSE` WRITE;
/*!40000 ALTER TABLE `EXPENSE` DISABLE KEYS */;
INSERT INTO `EXPENSE` VALUES (7,'2017-12-17 00:00:00','2017-12-18',280,339,220.00,2,NULL,'Y',NULL,NULL),(8,'2017-12-18 00:00:00','2017-12-18',280,280,0.00,2,1,'Y',NULL,NULL),(25,'2017-12-14 00:00:00','2017-12-15',339,280,1410.00,3,1,'Y','2017-12-15 00:00:00',NULL);
/*!40000 ALTER TABLE `EXPENSE` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_EXPENSE BEFORE INSERT ON EXPENSE
FOR EACH ROW
BEGIN
    IF NEW.cd_expense IS NULL THEN
        SET NEW.cd_expense = nextval('EXPENSE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `EXPENSE_ITEM`
--

DROP TABLE IF EXISTS `EXPENSE_ITEM`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EXPENSE_ITEM` (
  `cd_expense_item` int(11) NOT NULL,
  `cd_expense` int(11) DEFAULT NULL,
  `cd_expense_type` int(11) DEFAULT NULL,
  `ds_expense_item` varchar(64) DEFAULT NULL,
  `dt_expense_item` datetime DEFAULT NULL,
  `nr_amount` decimal(12,2) DEFAULT '0.00',
  `cd_currency` int(11) DEFAULT NULL,
  `nr_currency_rate` decimal(12,4) DEFAULT '0.0000',
  `nr_amount_on_rate` decimal(12,2) DEFAULT '0.00',
  PRIMARY KEY (`cd_expense_item`),
  KEY `FKEXPENSE_ITEM001_idx` (`cd_expense`),
  KEY `FKEXPENSE_ITEM002_idx` (`cd_expense_type`),
  KEY `FKEXPENSE_ITEM003_idx` (`cd_currency`),
  CONSTRAINT `FKEXPENSE_ITEM001` FOREIGN KEY (`cd_expense`) REFERENCES `EXPENSE` (`cd_expense`) ON DELETE CASCADE,
  CONSTRAINT `FKEXPENSE_ITEM002` FOREIGN KEY (`cd_expense_type`) REFERENCES `EXPENSE_TYPE` (`cd_expense_type`),
  CONSTRAINT `FKEXPENSE_ITEM003` FOREIGN KEY (`cd_currency`) REFERENCES `CURRENCY` (`cd_currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EXPENSE_ITEM`
--

LOCK TABLES `EXPENSE_ITEM` WRITE;
/*!40000 ALTER TABLE `EXPENSE_ITEM` DISABLE KEYS */;
INSERT INTO `EXPENSE_ITEM` VALUES (45,25,1,'BUY SHOES','2017-12-16 00:00:00',1300.00,3,1.0000,1300.00),(46,25,2,'TR','2017-12-21 00:00:00',105.00,3,1.0000,105.00),(47,25,1,'DESC',NULL,5.00,2,6.8000,34.00),(52,8,1,'DESCRIP','2017-12-18 00:00:00',100.00,2,1.0000,100.00),(53,7,1,'DESC','2017-12-18 00:00:00',211.00,3,1.0000,211.00);
/*!40000 ALTER TABLE `EXPENSE_ITEM` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_EXPENSE_ITEM BEFORE INSERT ON EXPENSE_ITEM
FOR EACH ROW
BEGIN
    IF NEW.cd_expense_item IS NULL THEN
        SET NEW.cd_expense_item = nextval('EXPENSE_ITEM');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `EXPENSE_ITEM_X_DOCUMENTS`
--

DROP TABLE IF EXISTS `EXPENSE_ITEM_X_DOCUMENTS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EXPENSE_ITEM_X_DOCUMENTS` (
  `cd_expense_item_x_documents` int(11) NOT NULL,
  `cd_expense_item` int(11) NOT NULL,
  `cd_document` int(11) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_expense_item_x_documents`),
  UNIQUE KEY `EXPENSE_ITEM_X_DOCUMENTS_Index01` (`cd_expense_item`,`cd_document`),
  KEY `FKEXPENSE_ITEM_X_DOCUMENTS02` (`cd_document`),
  CONSTRAINT `FKEXPENSE_ITEM_X_DOCUMENTS01` FOREIGN KEY (`cd_expense_item`) REFERENCES `EXPENSE_ITEM` (`cd_expense_item`) ON DELETE CASCADE,
  CONSTRAINT `FKEXPENSE_ITEM_X_DOCUMENTS02` FOREIGN KEY (`cd_document`) REFERENCES `DOCUMENT` (`cd_document`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EXPENSE_ITEM_X_DOCUMENTS`
--

LOCK TABLES `EXPENSE_ITEM_X_DOCUMENTS` WRITE;
/*!40000 ALTER TABLE `EXPENSE_ITEM_X_DOCUMENTS` DISABLE KEYS */;
INSERT INTO `EXPENSE_ITEM_X_DOCUMENTS` VALUES (15,45,215,NULL,'2017-12-17 12:15:22');
/*!40000 ALTER TABLE `EXPENSE_ITEM_X_DOCUMENTS` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_EXPENSE_ITEM_X_DOCUMENTS BEFORE INSERT ON EXPENSE_ITEM_X_DOCUMENTS
FOR EACH ROW
BEGIN
    IF NEW.cd_expense_item_x_documents IS NULL THEN
        SET NEW.cd_expense_item_x_documents = nextval('EXPENSE_ITEM_X_DOCUMENTS');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `EXPENSE_TYPE`
--

DROP TABLE IF EXISTS `EXPENSE_TYPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EXPENSE_TYPE` (
  `cd_expense_type` int(11) NOT NULL,
  `ds_expense_type` varchar(64) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_expense_type`),
  UNIQUE KEY `IUNEXPENSE_TYPE001` (`ds_expense_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EXPENSE_TYPE`
--

LOCK TABLES `EXPENSE_TYPE` WRITE;
/*!40000 ALTER TABLE `EXPENSE_TYPE` DISABLE KEYS */;
INSERT INTO `EXPENSE_TYPE` VALUES (1,'BUY SHOES',NULL,'2017-12-15 16:08:31'),(2,'TRAVEL',NULL,'2017-12-15 16:08:31');
/*!40000 ALTER TABLE `EXPENSE_TYPE` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_EXPENSE_TYPE BEFORE INSERT ON EXPENSE_TYPE
FOR EACH ROW
BEGIN
    IF NEW.cd_expense_type IS NULL THEN
        SET NEW.cd_expense_type = nextval('EXPENSE_TYPE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `FLIGHT_COMPANY`
--

DROP TABLE IF EXISTS `FLIGHT_COMPANY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FLIGHT_COMPANY` (
  `cd_flight_company` int(11) NOT NULL,
  `ds_flight_company` varchar(64) NOT NULL,
  `dt_deactivated` date DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_flight_company`),
  UNIQUE KEY `IUN_FLIGHT_COMPANY001` (`ds_flight_company`),
  KEY `IDXFLIGHT_COMPANY001` (`ds_flight_company`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `FLIGHT_COMPANY`
--

LOCK TABLES `FLIGHT_COMPANY` WRITE;
/*!40000 ALTER TABLE `FLIGHT_COMPANY` DISABLE KEYS */;
INSERT INTO `FLIGHT_COMPANY` VALUES (1,'AIR 1',NULL,'2017-12-15 11:03:34');
/*!40000 ALTER TABLE `FLIGHT_COMPANY` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_FLIGHT_COMPANY BEFORE INSERT ON FLIGHT_COMPANY
FOR EACH ROW
BEGIN
    IF NEW.cd_flight_company IS NULL THEN
        SET NEW.cd_flight_company = nextval('FLIGHT_COMPANY');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `FLIGHT_TICKET`
--

DROP TABLE IF EXISTS `FLIGHT_TICKET`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FLIGHT_TICKET` (
  `cd_flight_ticket` int(11) NOT NULL,
  `cd_employee` int(11) NOT NULL,
  `ds_ticket_number` varchar(64) DEFAULT NULL,
  `ds_class` varchar(45) DEFAULT NULL,
  `nr_amount` decimal(12,2) DEFAULT '0.00',
  `dt_issued_amount` datetime DEFAULT NULL,
  `ds_passanger_name` varchar(128) DEFAULT NULL,
  `cd_flight_company` int(11) DEFAULT NULL,
  `ds_flight_number` varchar(64) DEFAULT NULL,
  `cd_city_departure` int(11) DEFAULT NULL,
  `dt_departure_date` datetime DEFAULT NULL,
  `cd_city_arrival` int(11) DEFAULT NULL,
  `dt_arrival_date` datetime DEFAULT NULL,
  `cd_travel_agency` int(11) DEFAULT NULL,
  `ds_remarks` varchar(128) DEFAULT NULL,
  `cd_movements` int(11) DEFAULT NULL,
  `fl_deduct_from_employee` varchar(1) DEFAULT 'Y',
  PRIMARY KEY (`cd_flight_ticket`),
  KEY `FKFLIGHT_TICKET001_idx` (`cd_employee`),
  KEY `FKFLIGHT_TICKET002_idx` (`cd_city_departure`),
  KEY `FKFLIGHT_TICKET003_idx` (`cd_city_arrival`),
  KEY `FKFLIGHT_TICKET004_idx` (`cd_travel_agency`),
  KEY `FKFLIGHT_TICKET005_idx` (`cd_movements`),
  KEY `FKFLIGHT_TICKET005_idx1` (`cd_flight_company`),
  CONSTRAINT `FKFLIGHT_TICKET001` FOREIGN KEY (`cd_employee`) REFERENCES `EMPLOYEE` (`cd_employee`),
  CONSTRAINT `FKFLIGHT_TICKET002` FOREIGN KEY (`cd_city_departure`) REFERENCES `CITY` (`cd_city`),
  CONSTRAINT `FKFLIGHT_TICKET003` FOREIGN KEY (`cd_city_arrival`) REFERENCES `CITY` (`cd_city`),
  CONSTRAINT `FKFLIGHT_TICKET004` FOREIGN KEY (`cd_travel_agency`) REFERENCES `TRAVEL_AGENCY` (`cd_travel_agency`),
  CONSTRAINT `FKFLIGHT_TICKET005` FOREIGN KEY (`cd_movements`) REFERENCES `MOVEMENTS` (`cd_movements`) ON DELETE SET NULL,
  CONSTRAINT `FKFLIGHT_TICKET006` FOREIGN KEY (`cd_flight_company`) REFERENCES `FLIGHT_COMPANY` (`cd_flight_company`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `FLIGHT_TICKET`
--

LOCK TABLES `FLIGHT_TICKET` WRITE;
/*!40000 ALTER TABLE `FLIGHT_TICKET` DISABLE KEYS */;
INSERT INTO `FLIGHT_TICKET` VALUES (1,280,'121','A',1400.00,'2017-12-15 00:00:00','GABRIEL BLOS',1,'112121',2,'2017-12-15 00:00:00',3,'2017-12-16 00:00:00',1,'Remarks\n1',66,'Y'),(10,339,NULL,'A',0.00,NULL,'DAVIDSON NUNES',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'N');
/*!40000 ALTER TABLE `FLIGHT_TICKET` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_FLIGHT_TICKET BEFORE INSERT ON FLIGHT_TICKET
FOR EACH ROW
BEGIN
	DECLARE cd_movements INT;


    IF NEW.cd_flight_ticket IS NULL THEN
        SET NEW.cd_flight_ticket = nextval('FLIGHT_TICKET');
     END IF;
     

	IF COALESCE(NEW.fl_deduct_from_employee, 'Y') = 'Y' THEN
    
		SET cd_movements = nextval('MOVEMENTS');

    
		INSERT INTO `hrms`.`MOVEMENTS`
		( 
        `cd_movements`,
		`cd_employee`,
		`cd_benefit_type`,
		`cd_human_resource_record`,
		`dt_start_at`,
		`nr_value_to_add`,
		`ds_comments`,
		`cd_benefit_operation`
		)  VALUES (cd_movements,
        NEW.cd_employee,
        3, 
        getvar('cd_human_resource'),
        date(now()),
        1,
        concat('Added by Flight Ticked #' , convert (NEW.cd_flight_ticket, char(10))),
        2
        );
        
        
		SET NEW.cd_movements = cd_movements;
    
    END IF;
     
     
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER `hrms`.`FLIGHT_TICKET_BEFORE_UPDATE` BEFORE UPDATE ON `FLIGHT_TICKET` FOR EACH ROW
BEGIN


DECLARE cd_movements INT;



	IF COALESCE(NEW.fl_deduct_from_employee, 'Y') != COALESCE(OLD.fl_deduct_from_employee, 'Y') THEN
    
		-- IF CHANGED TO USER PAYING, ADD MOVEMNET.
		IF COALESCE(NEW.fl_deduct_from_employee, 'Y') = 'Y' AND NEW.cd_movements IS NULL THEN
    
			SET cd_movements = nextval('MOVEMENTS');

			
			INSERT INTO `hrms`.`MOVEMENTS`
			( 
			`cd_movements`,
			`cd_employee`,
			`cd_benefit_type`,
			`cd_human_resource_record`,
			`dt_start_at`,
			`nr_value_to_add`,
			`ds_comments`,
			`cd_benefit_operation`
			)  VALUES (cd_movements,
			NEW.cd_employee,
			3, 
			getvar('cd_human_resource'),
			date(now()),
			1,
			concat('Added by Flight Ticked #' , convert (NEW.cd_flight_ticket, char(10))),
			2
			);
			
			SET NEW.cd_movements = cd_movements;
		END IF;
        
        
        
        END IF;



END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER `hrms`.`FLIGHT_TICKET_AFTER_UPDATE` AFTER UPDATE ON `FLIGHT_TICKET` FOR EACH ROW
BEGIN

	IF COALESCE(NEW.fl_deduct_from_employee, 'Y') != COALESCE(OLD.fl_deduct_from_employee, 'Y') THEN

        -- IF COMPANY GOING TO PAY, DELETE THE MOVEMENT ALSO
		IF COALESCE(NEW.fl_deduct_from_employee, 'Y') = 'N' AND NEW.cd_movements IS NOT NULL THEN
    		DELETE FROM `hrms`.`MOVEMENTS` WHERE cd_movements = NEW.cd_movements ;
            
            	CALL adjustMovementBalance(NEW.cd_employee, 3);

            
    	END IF;
	END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER `hrms`.`FLIGHT_TICKET_AFTER_DELETE` AFTER DELETE ON `FLIGHT_TICKET` FOR EACH ROW
BEGIN
    		DELETE FROM `hrms`.`MOVEMENTS` WHERE cd_movements = OLD.cd_movements ;
			CALL adjustMovementBalance(OLD.cd_employee, 3);

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `GENDER`
--

DROP TABLE IF EXISTS `GENDER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `GENDER` (
  `cd_gender` int(11) NOT NULL,
  `ds_gender` longtext NOT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dt_deactivated` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_gender`),
  UNIQUE KEY `IUNGENDER001` (`ds_gender`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `GENDER`
--

LOCK TABLES `GENDER` WRITE;
/*!40000 ALTER TABLE `GENDER` DISABLE KEYS */;
INSERT INTO `GENDER` VALUES (1,'MAN','2017-11-05 22:46:51',NULL),(2,'WOMAN','2017-11-05 22:46:51',NULL);
/*!40000 ALTER TABLE `GENDER` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_GENDER BEFORE INSERT ON GENDER
FOR EACH ROW
BEGIN
    IF NEW.cd_gender IS NULL THEN
        SET NEW.cd_gender = nextval('GENDER');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `HR_SYSTEM_DASHBOARD_WIDGET_PARAM`
--

DROP TABLE IF EXISTS `HR_SYSTEM_DASHBOARD_WIDGET_PARAM`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `HR_SYSTEM_DASHBOARD_WIDGET_PARAM` (
  `cd_hm_system_dashboard_widget_param` int(11) NOT NULL,
  `cd_human_resource` int(11) NOT NULL,
  `cd_system_dashboard_widget` int(11) NOT NULL,
  `json_parameters` varchar(255) DEFAULT NULL,
  `nr_order` int(11) NOT NULL DEFAULT '0',
  `cd_system_product_category` int(11) NOT NULL,
  PRIMARY KEY (`cd_hm_system_dashboard_widget_param`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `HR_SYSTEM_DASHBOARD_WIDGET_PARAM`
--

LOCK TABLES `HR_SYSTEM_DASHBOARD_WIDGET_PARAM` WRITE;
/*!40000 ALTER TABLE `HR_SYSTEM_DASHBOARD_WIDGET_PARAM` DISABLE KEYS */;
INSERT INTO `HR_SYSTEM_DASHBOARD_WIDGET_PARAM` VALUES (12,24,1,'{\"cd_season\": \"9\", \"ds_season\": \"WC18 - EINTER A 2018\", \"nr_refresh\": \"120\", \"cd_division\": \"4\", \"ds_division\": \"BRAND 1\", \"ds_processes\": \"26\"}',1,1),(18,24,1,'{\"cd_season\": \"9\", \"ds_season\": \"WC18 - EINTER A 2018\", \"nr_refresh\": \"120\", \"cd_division\": \"4\", \"ds_division\": \"BRAND 1\", \"ds_processes\": \"26,29\"}',2,1),(25,24,2,'{\"cd_season\": \"3\", \"ds_season\": \"FB17 - FALL B 2017\", \"nr_refresh\": \"120\", \"cd_division\": \"4\", \"ds_division\": \"BRAND 1\", \"ds_processes\": \"36,35,37\"}',3,1),(26,24,3,'{\"cd_season\": \"3\", \"ds_season\": \"FB17 - FALL B 2017\", \"nr_refresh\": \"120\", \"cd_division\": \"4\", \"ds_division\": \"BRAND 1\", \"ds_processes\": \"31\"}',4,1),(27,24,1,'{\"cd_season\": \"10\", \"ds_season\": \"NEW - NEW\", \"nr_refresh\": \"120\", \"cd_division\": \"6\", \"ds_division\": \"ELLIOT\", \"ds_processes\": \"61\"}',1,2);
/*!40000 ALTER TABLE `HR_SYSTEM_DASHBOARD_WIDGET_PARAM` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_HR_SYSTEM_DASHBOARD_WIDGET_PARAM BEFORE INSERT ON HR_SYSTEM_DASHBOARD_WIDGET_PARAM
FOR EACH ROW
BEGIN
    IF NEW.cd_hm_system_dashboard_widget_param IS NULL THEN
        SET NEW.cd_hm_system_dashboard_widget_param = nextval('HR_SYSTEM_DASHBOARD_WIDGET_PARAM');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `HR_SYSTEM_SETTINGS_OPTIONS`
--

DROP TABLE IF EXISTS `HR_SYSTEM_SETTINGS_OPTIONS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `HR_SYSTEM_SETTINGS_OPTIONS` (
  `cd_hr_system_settings` int(11) NOT NULL,
  `cd_system_settings_options` int(11) NOT NULL,
  `cd_human_resource` int(11) NOT NULL,
  `cd_system_settings` int(11) NOT NULL,
  PRIMARY KEY (`cd_hr_system_settings`),
  UNIQUE KEY `IUNHR_SYSTEM_SETTINGS_OPTIONS001` (`cd_human_resource`,`cd_system_settings`),
  KEY `IDX_HR_SYSTEM_SETTINGS_OPTIONS001` (`cd_human_resource`),
  KEY `FKHR_SYSTEM_SETTINGS_OPTIONS002` (`cd_system_settings`),
  CONSTRAINT `FKHR_SYSTEM_SETTINGS_OPTIONS002` FOREIGN KEY (`cd_system_settings`) REFERENCES `SYSTEM_SETTINGS` (`cd_system_settings`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `HR_SYSTEM_SETTINGS_OPTIONS`
--

LOCK TABLES `HR_SYSTEM_SETTINGS_OPTIONS` WRITE;
/*!40000 ALTER TABLE `HR_SYSTEM_SETTINGS_OPTIONS` DISABLE KEYS */;
INSERT INTO `HR_SYSTEM_SETTINGS_OPTIONS` VALUES (1,1,24,1),(2,4,24,2),(3,6,24,3),(4,17,24,9),(5,1,27,1),(6,18,24,10),(7,22,24,11),(8,22,37,11),(9,35,24,12),(10,38,24,13),(11,41,24,14),(13,43,24,15),(15,43,43,15),(16,43,47,15),(17,22,43,11),(18,35,43,12),(19,4,43,2),(20,43,277,15);
/*!40000 ALTER TABLE `HR_SYSTEM_SETTINGS_OPTIONS` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_HR_SYSTEM_SETTINGS_OPTIONS BEFORE INSERT ON HR_SYSTEM_SETTINGS_OPTIONS
FOR EACH ROW
BEGIN
    IF NEW.cd_hr_system_settings IS NULL THEN
        SET NEW.cd_hr_system_settings = nextval('HR_SYSTEM_SETTINGS_OPTIONS');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `HR_TYPE`
--

DROP TABLE IF EXISTS `HR_TYPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `HR_TYPE` (
  `cd_hr_type` int(11) NOT NULL,
  `ds_hr_type` varchar(64) NOT NULL,
  `dt_deactivated` date DEFAULT NULL,
  `dt_record` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_hr_type`),
  UNIQUE KEY `IUN_HR_TYPE001` (`ds_hr_type`),
  KEY `IDXHR_TYPE001` (`ds_hr_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `HR_TYPE`
--

LOCK TABLES `HR_TYPE` WRITE;
/*!40000 ALTER TABLE `HR_TYPE` DISABLE KEYS */;
INSERT INTO `HR_TYPE` VALUES (67,'SHIPPING DEPARTMENT',NULL,NULL),(165,'COMMERCIAL DEPARTMENT',NULL,'2014-07-26 22:07:46'),(133413,'IT DEPARTMENT',NULL,'2014-07-30 16:40:43'),(133421,'PRODUCT DEMONSTRATION',NULL,'2016-10-22 09:53:14');
/*!40000 ALTER TABLE `HR_TYPE` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_HR_TYPE BEFORE INSERT ON HR_TYPE
FOR EACH ROW
BEGIN
    IF NEW.cd_hr_type IS NULL THEN
        SET NEW.cd_hr_type = nextval('HR_TYPE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `HUMAN_RESOURCE`
--

DROP TABLE IF EXISTS `HUMAN_RESOURCE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `HUMAN_RESOURCE` (
  `cd_human_resource` int(11) NOT NULL,
  `ds_human_resource_full` varchar(64) NOT NULL,
  `ds_human_resource` varchar(16) NOT NULL,
  `dt_deactivated` date DEFAULT NULL,
  `dt_record` datetime DEFAULT NULL,
  `cd_hr_type` int(11) DEFAULT NULL,
  `ds_password` varchar(32) NOT NULL,
  `ds_e_mail` varchar(64) DEFAULT NULL,
  `fl_super_user` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`cd_human_resource`),
  UNIQUE KEY `IUNHUMAN_RESOURCE001` (`ds_human_resource_full`),
  UNIQUE KEY `IUNHUMAN_RESOURCE002` (`ds_human_resource`),
  KEY `IDX_HUMAN_RESOURCE_001` (`ds_human_resource_full`),
  KEY `fki_XFKHUMAN_RESOURCE001` (`cd_hr_type`),
  CONSTRAINT `XFKHUMAN_RESOURCE001` FOREIGN KEY (`cd_hr_type`) REFERENCES `HR_TYPE` (`cd_hr_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `HUMAN_RESOURCE`
--

LOCK TABLES `HUMAN_RESOURCE` WRITE;
/*!40000 ALTER TABLE `HUMAN_RESOURCE` DISABLE KEYS */;
INSERT INTO `HUMAN_RESOURCE` VALUES (43,'System Administrator','admin',NULL,'2016-11-22 16:07:50',133421,'3d4128e441a2c94e09e477d3549fe2e0','admin@devshoes.com','Y'),(277,'GABRIEL BLOS','carlosgblos',NULL,NULL,NULL,'281d59c6d9c8fe4b4e627a3aa56a9670','testa','N'),(356,'DAVIDSON NUNES','davidsond',NULL,NULL,NULL,'9eb71ab7420eb452a22787ca4fab501b',NULL,'N');
/*!40000 ALTER TABLE `HUMAN_RESOURCE` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_HUMAN_RESOURCE BEFORE INSERT ON HUMAN_RESOURCE
FOR EACH ROW
BEGIN
    IF NEW.cd_human_resource IS NULL THEN
        SET NEW.cd_human_resource = nextval('HUMAN_RESOURCE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `HUMAN_RESOURCE_MENU`
--

DROP TABLE IF EXISTS `HUMAN_RESOURCE_MENU`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `HUMAN_RESOURCE_MENU` (
  `cd_human_resource` int(11) NOT NULL,
  `cd_menu` int(11) NOT NULL,
  `dt_record` datetime DEFAULT NULL,
  `cd_human_resource_menu` int(11) NOT NULL,
  PRIMARY KEY (`cd_human_resource_menu`),
  UNIQUE KEY `IUNHUMAN_RESOURCE_MENU002` (`cd_human_resource`,`cd_menu`),
  CONSTRAINT `FKHUMAN_RESOURCE_MENU001` FOREIGN KEY (`cd_human_resource`) REFERENCES `HUMAN_RESOURCE` (`cd_human_resource`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `HUMAN_RESOURCE_MENU`
--

LOCK TABLES `HUMAN_RESOURCE_MENU` WRITE;
/*!40000 ALTER TABLE `HUMAN_RESOURCE_MENU` DISABLE KEYS */;
INSERT INTO `HUMAN_RESOURCE_MENU` VALUES (43,1,NULL,1);
/*!40000 ALTER TABLE `HUMAN_RESOURCE_MENU` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_HUMAN_RESOURCE_MENU BEFORE INSERT ON HUMAN_RESOURCE_MENU
FOR EACH ROW
BEGIN
    IF NEW.cd_human_resource_menu IS NULL THEN
        SET NEW.cd_human_resource_menu = nextval('HUMAN_RESOURCE_MENU');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `HUMAN_RESOURCE_X_SYSTEM_PRODUCT_CATEGORY`
--

DROP TABLE IF EXISTS `HUMAN_RESOURCE_X_SYSTEM_PRODUCT_CATEGORY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `HUMAN_RESOURCE_X_SYSTEM_PRODUCT_CATEGORY` (
  `cd_human_resource_x_system_product_category` int(11) NOT NULL,
  `cd_human_resource` int(11) NOT NULL,
  `cd_system_product_category` int(11) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_human_resource_x_system_product_category`),
  UNIQUE KEY `IUNHUMAN_RESOURCE_X_SYSTEM_PRODUCT_CATEGORY002` (`cd_human_resource`,`cd_system_product_category`),
  CONSTRAINT `FKHUMAN_RESOURCE_X_SYSTEM_PRODUCT_CATEGORY001` FOREIGN KEY (`cd_human_resource`) REFERENCES `HUMAN_RESOURCE` (`cd_human_resource`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `HUMAN_RESOURCE_X_SYSTEM_PRODUCT_CATEGORY`
--

LOCK TABLES `HUMAN_RESOURCE_X_SYSTEM_PRODUCT_CATEGORY` WRITE;
/*!40000 ALTER TABLE `HUMAN_RESOURCE_X_SYSTEM_PRODUCT_CATEGORY` DISABLE KEYS */;
/*!40000 ALTER TABLE `HUMAN_RESOURCE_X_SYSTEM_PRODUCT_CATEGORY` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_HUMAN_RESOURCE_X_SYSTEM_PRODUCT_CATEGORY BEFORE INSERT ON HUMAN_RESOURCE_X_SYSTEM_PRODUCT_CATEGORY
FOR EACH ROW
BEGIN
    IF NEW.cd_human_resource_x_system_product_category IS NULL THEN
        SET NEW.cd_human_resource_x_system_product_category = nextval('HUMAN_RESOURCE_X_SYSTEM_PRODUCT_CATEGORY');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `JOBS`
--

DROP TABLE IF EXISTS `JOBS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `JOBS` (
  `cd_jobs` int(11) NOT NULL,
  `ds_jobs` varchar(64) NOT NULL,
  `ds_notes` varchar(2044) DEFAULT NULL,
  `dt_deactivated` date DEFAULT NULL,
  `dt_record` time NOT NULL,
  `cd_department` int(11) NOT NULL,
  `cd_jobs_responsible` int(11) DEFAULT NULL,
  PRIMARY KEY (`cd_jobs`),
  UNIQUE KEY `IUN_JOBS001` (`ds_jobs`),
  KEY `XFKJOBS001` (`cd_department`),
  KEY `XFKJOBS002` (`cd_jobs_responsible`),
  CONSTRAINT `XFKJOBS001` FOREIGN KEY (`cd_department`) REFERENCES `DEPARTMENT` (`cd_department`),
  CONSTRAINT `XFKJOBS002` FOREIGN KEY (`cd_jobs_responsible`) REFERENCES `JOBS` (`cd_jobs`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `JOBS`
--

LOCK TABLES `JOBS` WRITE;
/*!40000 ALTER TABLE `JOBS` DISABLE KEYS */;
INSERT INTO `JOBS` VALUES (1,'NICE',NULL,NULL,'00:00:00',5,4),(4,'BOSS','TEST',NULL,'15:04:43',7,5),(5,'SAMPLE ','',NULL,'21:26:06',9,4),(7,'COMMERCIAL','',NULL,'14:22:27',9,4),(8,'IT','',NULL,'16:52:18',3,4),(12,'TESTE JOB','sddasdasd','2016-10-22','15:12:32',3,7),(16,'TRIAL',NULL,NULL,'16:33:14',3,4),(17,'DESIGNER',NULL,NULL,'14:08:30',15,18),(18,'COLLECTION MANAGER',NULL,NULL,'14:08:58',15,NULL),(21,'TEST',NULL,NULL,'12:55:37',3,4);
/*!40000 ALTER TABLE `JOBS` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_JOBS BEFORE INSERT ON JOBS
FOR EACH ROW
BEGIN
    IF NEW.cd_jobs IS NULL THEN
        SET NEW.cd_jobs = nextval('JOBS');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `JOBS_HUMAN_RESOURCE`
--

DROP TABLE IF EXISTS `JOBS_HUMAN_RESOURCE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `JOBS_HUMAN_RESOURCE` (
  `cd_jobs` int(11) NOT NULL,
  `cd_human_resource` int(11) NOT NULL,
  `cd_jobs_hr` int(11) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_jobs_hr`),
  UNIQUE KEY `IUN_JOBS_HR` (`cd_jobs`,`cd_human_resource`),
  KEY `lnk_HUMAN_RESOURCE_MM_JOBS` (`cd_human_resource`),
  CONSTRAINT `lnk_HUMAN_RESOURCE_MM_JOBS` FOREIGN KEY (`cd_human_resource`) REFERENCES `HUMAN_RESOURCE` (`cd_human_resource`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lnk_JOBS_MM_HUMAN_RESOURCE` FOREIGN KEY (`cd_jobs`) REFERENCES `JOBS` (`cd_jobs`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `JOBS_HUMAN_RESOURCE`
--

LOCK TABLES `JOBS_HUMAN_RESOURCE` WRITE;
/*!40000 ALTER TABLE `JOBS_HUMAN_RESOURCE` DISABLE KEYS */;
INSERT INTO `JOBS_HUMAN_RESOURCE` VALUES (7,43,1,'2017-11-12 19:35:35'),(8,43,36,NULL),(4,43,40,NULL),(5,43,43,'2016-12-16 15:24:54'),(16,43,45,NULL),(18,43,55,NULL);
/*!40000 ALTER TABLE `JOBS_HUMAN_RESOURCE` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_JOBS_HUMAN_RESOURCE BEFORE INSERT ON JOBS_HUMAN_RESOURCE
FOR EACH ROW
BEGIN
    IF NEW.cd_jobs_hr IS NULL THEN
        SET NEW.cd_jobs_hr = nextval('JOBS_HUMAN_RESOURCE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `JOBS_MENU`
--

DROP TABLE IF EXISTS `JOBS_MENU`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `JOBS_MENU` (
  `cd_jobs` int(11) NOT NULL,
  `cd_menu` int(11) NOT NULL,
  `dt_record` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_jobs`,`cd_menu`),
  CONSTRAINT `FKJOBS_MENU001` FOREIGN KEY (`cd_jobs`) REFERENCES `JOBS` (`cd_jobs`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `JOBS_MENU`
--

LOCK TABLES `JOBS_MENU` WRITE;
/*!40000 ALTER TABLE `JOBS_MENU` DISABLE KEYS */;
INSERT INTO `JOBS_MENU` VALUES (4,1,'2016-02-29 21:30:23'),(4,2,'2016-02-29 21:30:23'),(4,3,'2016-02-29 21:30:23'),(4,6,'2016-02-29 21:30:23'),(4,7,'2016-02-29 21:30:23'),(4,8,'2016-02-29 21:30:23'),(4,9,'2016-02-29 21:30:23'),(4,10,'2016-02-29 21:30:23'),(4,11,'2016-02-29 21:30:23'),(4,12,'2016-02-29 21:30:23'),(4,13,'2016-02-29 21:30:23'),(4,14,'2016-02-29 21:30:23'),(4,15,'2016-02-29 21:30:23'),(4,16,'2016-02-29 21:30:23'),(4,17,'2016-02-29 21:30:23'),(4,18,'2016-02-29 21:30:23'),(4,19,'2016-02-29 21:30:23'),(4,20,'2016-02-29 21:30:23'),(4,21,'2016-02-29 21:30:23'),(4,22,'2016-02-29 21:30:23'),(4,24,'2016-02-29 21:30:23'),(4,25,'2016-02-29 21:30:23'),(4,26,'2016-02-29 21:30:23'),(4,27,'2016-02-29 21:30:23'),(4,28,'2016-02-29 21:30:23'),(4,29,'2016-02-29 21:30:23'),(4,30,'2016-02-29 21:30:23'),(4,33,'2016-02-29 21:30:23'),(4,34,'2016-02-29 21:30:23'),(4,35,'2016-02-29 21:30:23'),(4,37,'2016-02-29 21:30:23'),(4,38,'2016-02-29 21:30:23'),(4,39,'2016-02-29 21:30:23'),(4,40,'2016-02-29 21:30:23'),(4,41,'2016-02-29 21:30:23'),(4,42,'2016-02-29 21:30:23'),(4,43,'2016-02-29 21:30:23'),(4,44,'2016-02-29 21:30:23'),(4,45,'2016-02-29 21:30:23'),(4,46,'2016-02-29 21:30:23'),(4,48,'2016-02-29 21:30:23'),(4,49,'2016-02-29 21:30:23'),(4,50,'2016-02-29 21:30:23'),(4,52,'2016-02-29 21:30:23'),(4,53,'2016-02-29 21:30:23'),(4,54,'2016-02-29 21:30:23'),(4,55,'2016-02-29 21:30:23'),(4,56,'2016-02-29 21:30:23'),(4,57,'2016-02-29 21:30:23'),(4,58,'2016-02-29 21:30:23'),(4,59,'2016-02-29 21:30:23'),(4,60,'2016-02-29 21:30:23'),(4,61,'2016-02-29 21:30:23'),(4,62,'2016-02-29 21:30:23'),(4,63,'2016-02-29 21:30:23'),(4,64,'2016-02-29 21:30:23'),(4,65,'2016-02-29 21:30:23'),(4,112,'2017-11-05 18:58:21'),(4,115,'2017-11-05 18:58:21'),(4,116,'2017-11-05 18:58:21'),(4,127,'2017-11-05 18:58:21'),(4,1002,'2017-11-05 16:21:49'),(4,1004,'2017-11-05 18:58:21'),(4,1005,'2017-11-05 19:03:31'),(4,1006,'2017-11-05 19:46:17'),(4,1007,'2017-11-05 21:46:09'),(4,1008,'2017-11-05 22:27:22'),(4,1009,'2017-11-05 22:41:44'),(4,1010,'2017-11-05 22:45:57'),(4,1011,'2017-11-05 22:48:16'),(4,1012,'2017-11-05 22:50:34'),(4,1013,'2017-11-05 22:52:44'),(4,1014,'2017-11-06 19:39:15'),(5,1,'2014-07-24 16:49:52'),(5,2,'2014-07-24 16:49:52'),(5,3,'2014-07-24 16:49:52'),(5,6,'2014-07-24 16:49:52'),(5,7,'2014-07-24 16:49:52'),(5,8,'2014-07-24 16:49:52'),(5,9,'2014-07-24 16:49:52'),(5,10,'2014-07-24 16:49:52'),(5,11,'2014-07-24 16:49:52'),(7,1,'2014-07-24 19:17:31'),(7,2,'2014-07-24 19:17:31'),(7,3,'2014-07-24 19:17:31'),(7,6,'2014-07-24 19:17:31'),(7,7,'2014-07-24 19:17:31'),(7,8,'2014-07-24 19:17:31'),(7,9,'2014-07-24 19:17:31'),(7,10,'2014-07-24 19:17:31'),(7,11,'2014-07-24 19:17:31'),(7,12,'2016-02-29 21:29:55'),(8,1,'2014-08-13 16:52:40'),(8,2,'2016-03-04 11:45:06'),(8,3,'2014-08-13 16:52:40'),(8,6,'2014-08-13 16:52:40'),(8,7,'2014-10-26 14:50:02'),(8,8,'2014-08-13 16:52:40'),(8,9,'2016-03-04 11:47:45'),(8,10,'2014-08-13 16:52:40'),(8,11,'2014-10-31 17:46:19'),(8,12,'2014-08-13 16:52:40'),(8,13,'2014-08-16 14:16:37'),(8,14,'2014-08-30 13:12:43'),(8,15,'2014-10-26 19:39:41'),(8,16,'2014-08-30 17:49:46'),(8,17,'2014-09-01 14:54:19'),(8,18,'2014-10-26 19:42:31'),(8,19,'2014-09-01 14:54:19'),(8,20,'2014-09-16 13:24:12'),(8,21,'2014-10-26 19:40:43'),(8,22,'2014-10-16 15:31:27'),(8,24,'2014-10-26 13:56:08'),(8,25,'2014-10-21 12:11:40'),(8,26,'2014-10-21 12:11:40'),(8,27,'2014-10-21 12:11:40'),(8,28,'2014-10-22 16:48:52'),(8,29,'2014-10-23 10:10:08'),(8,30,'2014-10-24 14:43:23'),(8,33,'2014-11-21 15:40:20'),(8,34,'2014-11-21 15:40:20'),(8,35,'2014-11-21 15:58:38'),(8,37,'2014-12-22 17:03:00'),(8,38,'2015-01-06 17:11:28'),(8,39,'2015-01-22 17:03:10'),(8,40,'2015-01-22 17:03:10'),(8,41,'2015-01-22 17:03:10'),(8,42,'2015-04-27 16:58:15'),(8,43,'2016-12-09 16:54:33'),(8,44,'2015-06-01 13:46:45'),(8,45,'2015-06-01 13:46:45'),(8,46,'2015-06-01 13:46:45'),(8,48,'2015-06-12 13:17:18'),(8,49,'2015-06-18 13:10:13'),(8,50,'2015-06-19 14:13:05'),(8,52,'2015-09-08 15:20:29'),(8,53,'2015-09-08 15:24:52'),(8,54,'2015-09-08 16:43:36'),(8,55,'2015-09-08 16:50:46'),(8,56,'2015-09-28 17:35:18'),(8,57,'2015-10-16 15:45:05'),(8,58,'2015-12-01 16:27:21'),(8,59,'2015-12-03 12:56:33'),(8,60,'2016-01-19 17:43:32'),(8,61,'2016-02-01 15:38:21'),(8,62,'2016-02-05 10:59:54'),(8,63,'2016-02-05 14:45:04'),(8,64,'2016-02-05 14:44:59'),(8,65,'2016-02-05 15:08:02'),(8,66,'2016-05-03 13:54:16'),(8,67,'2016-03-10 15:44:33'),(8,68,'2016-04-06 16:02:50'),(8,69,'2016-05-03 12:44:32'),(8,74,'2016-04-18 17:05:32'),(8,75,'2016-05-03 15:29:37'),(8,76,'2016-05-09 16:09:27'),(8,77,'2016-05-09 22:07:45'),(8,78,'2016-05-09 22:48:27'),(8,80,'2016-05-14 10:14:30'),(8,81,'2016-05-20 17:16:34'),(8,82,'2016-05-23 16:50:35'),(8,83,'2016-05-23 17:19:54'),(8,84,'2016-05-25 15:09:58'),(8,85,'2016-05-25 15:09:58'),(8,87,'2016-05-25 17:05:58'),(8,88,'2016-05-25 17:10:22'),(8,89,'2016-05-27 12:57:15'),(8,90,'2016-05-27 20:15:33'),(8,91,'2016-06-08 14:35:34'),(8,92,'2016-06-14 17:49:27'),(8,93,'2016-06-15 14:38:56'),(8,94,'2016-06-16 15:03:16'),(8,95,'2016-07-04 14:00:23'),(8,96,'2016-07-12 15:39:38'),(8,99,'2016-08-11 17:16:46'),(8,100,'2016-08-11 17:31:36'),(8,101,'2016-08-15 17:12:56'),(8,102,'2016-09-06 20:15:47'),(8,103,'2016-09-06 20:15:47'),(8,105,'2016-09-07 20:42:27'),(8,106,'2016-09-26 14:26:02'),(8,107,'2016-10-13 22:01:28'),(8,108,'2016-10-13 22:01:28'),(8,109,'2016-10-13 22:01:28'),(8,110,'2016-10-25 20:57:51'),(8,111,'2016-11-04 15:15:24'),(8,112,'2016-11-19 10:56:17'),(8,113,'2016-11-23 23:02:06'),(8,115,'2016-12-12 22:39:25'),(8,116,'2016-12-16 14:41:44'),(8,117,'2016-12-31 01:55:38'),(8,118,'2017-01-06 01:35:39'),(8,119,'2017-01-07 00:45:26'),(8,120,'2017-02-17 16:19:26'),(8,122,'2017-02-24 22:32:14'),(8,123,'2017-03-07 21:13:07'),(8,124,'2017-04-11 13:20:03'),(8,125,'2017-05-04 16:22:57'),(8,126,'2017-06-30 15:54:39'),(8,127,'2017-05-14 15:38:42'),(8,128,'2017-05-22 10:52:08'),(8,129,'2017-05-22 10:56:41'),(8,130,'2017-06-08 16:56:31'),(8,131,'2017-08-14 17:14:08'),(8,132,'2017-08-14 17:14:08'),(8,133,'2017-08-14 17:14:08'),(8,134,'2017-08-18 15:37:03'),(8,135,'2017-09-14 17:07:36'),(8,136,'2017-09-30 13:59:10'),(8,1002,NULL),(8,1004,NULL),(8,1005,NULL),(8,1006,NULL),(8,1007,NULL),(8,1008,NULL),(8,1009,NULL),(8,1010,NULL),(8,1011,NULL),(8,1012,NULL),(8,1013,NULL),(8,1014,NULL),(8,1015,NULL),(8,1016,NULL),(8,1017,NULL),(8,1018,NULL),(8,1019,NULL),(8,1021,NULL),(8,1022,NULL),(8,1023,NULL),(8,1024,NULL),(8,1025,NULL),(12,1,'2015-01-12 15:18:16'),(12,2,'2015-01-12 15:18:16'),(12,3,'2015-01-12 15:18:16'),(12,6,'2015-01-12 15:18:16'),(12,7,'2015-01-12 15:18:16'),(12,8,'2015-01-12 15:18:16'),(12,9,'2015-01-12 15:18:16'),(12,10,'2015-01-12 15:18:16'),(12,11,'2015-01-12 15:18:16'),(12,12,'2015-01-12 15:18:55'),(12,13,'2015-01-12 15:18:55'),(12,14,'2015-01-12 15:18:55'),(12,15,'2015-01-12 15:18:55'),(12,16,'2015-01-12 15:18:55'),(12,17,'2015-01-12 15:18:55'),(12,18,'2015-01-12 15:18:55'),(12,19,'2015-01-12 15:18:55'),(12,20,'2015-01-12 15:18:55'),(12,21,'2015-01-12 15:18:55'),(12,22,'2015-01-12 15:18:55'),(12,24,'2015-01-12 15:18:55'),(12,25,'2015-01-12 15:18:55'),(12,26,'2015-01-12 15:18:55'),(12,27,'2015-01-12 15:18:55'),(12,28,'2015-01-12 15:18:55'),(12,29,'2015-01-12 15:18:55'),(12,30,'2015-01-12 15:18:55'),(12,33,'2015-01-12 15:18:55'),(12,34,'2015-01-12 15:18:55'),(12,35,'2015-01-12 15:18:55'),(12,37,'2015-01-12 15:18:55'),(12,38,'2015-01-12 15:18:55'),(16,14,'2016-10-22 16:35:55'),(16,16,'2016-10-22 16:35:55'),(16,37,'2016-10-22 16:35:55'),(16,38,'2016-10-22 16:35:55'),(16,42,'2016-10-22 16:35:55'),(16,43,'2016-10-22 16:35:55'),(16,46,'2016-10-22 16:35:55'),(16,48,'2016-10-22 16:35:55'),(16,57,'2016-10-22 16:35:55'),(16,58,'2016-10-22 16:35:55'),(16,59,'2016-10-22 16:35:55'),(16,66,'2017-06-29 22:41:41'),(16,68,'2016-10-22 16:35:55'),(16,69,'2017-06-29 22:41:36'),(16,77,'2016-10-22 16:35:55'),(16,83,'2016-10-22 16:35:55'),(16,84,'2016-10-22 16:35:55'),(16,85,'2016-10-22 16:35:55'),(16,89,'2016-10-22 16:35:55'),(16,94,'2016-10-22 16:35:55'),(16,95,'2016-10-22 16:35:55'),(16,96,'2016-10-22 16:35:55'),(16,102,'2016-10-22 16:35:55'),(16,103,'2016-10-22 16:35:55'),(16,105,'2017-06-29 22:41:50'),(16,107,'2016-10-22 16:35:55'),(16,108,'2016-10-22 16:35:55'),(16,109,'2016-10-22 16:35:55'),(16,126,'2017-06-29 21:47:35');
/*!40000 ALTER TABLE `JOBS_MENU` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_JOBS_MENU BEFORE INSERT ON JOBS_MENU
FOR EACH ROW
BEGIN
    IF NEW.cd_jobs IS NULL THEN
        SET NEW.cd_jobs = nextval('JOBS_MENU');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `JOBS_SYSTEM_PERMISSION`
--

DROP TABLE IF EXISTS `JOBS_SYSTEM_PERMISSION`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `JOBS_SYSTEM_PERMISSION` (
  `cd_jobs_system_permission` int(11) NOT NULL,
  `cd_jobs` int(11) NOT NULL,
  `cd_system_permission` int(11) NOT NULL,
  PRIMARY KEY (`cd_jobs_system_permission`),
  UNIQUE KEY `IUNJOBPERM001` (`cd_jobs`,`cd_system_permission`),
  KEY `SYSTEM_PERMISSION_PK3` (`cd_system_permission`),
  CONSTRAINT `JOBS_PK4` FOREIGN KEY (`cd_jobs`) REFERENCES `JOBS` (`cd_jobs`) ON UPDATE CASCADE,
  CONSTRAINT `SYSTEM_PERMISSION_PK3` FOREIGN KEY (`cd_system_permission`) REFERENCES `SYSTEM_PERMISSION` (`cd_system_permission`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `JOBS_SYSTEM_PERMISSION`
--

LOCK TABLES `JOBS_SYSTEM_PERMISSION` WRITE;
/*!40000 ALTER TABLE `JOBS_SYSTEM_PERMISSION` DISABLE KEYS */;
INSERT INTO `JOBS_SYSTEM_PERMISSION` VALUES (3,4,4),(10,8,6),(12,8,7),(2,17,5);
/*!40000 ALTER TABLE `JOBS_SYSTEM_PERMISSION` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_JOBS_SYSTEM_PERMISSION BEFORE INSERT ON JOBS_SYSTEM_PERMISSION
FOR EACH ROW
BEGIN
    IF NEW.cd_jobs_system_permission IS NULL THEN
        SET NEW.cd_jobs_system_permission = nextval('JOBS_SYSTEM_PERMISSION');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `JOBS_X_SYSTEM_PRODUCT_CATEGORY`
--

DROP TABLE IF EXISTS `JOBS_X_SYSTEM_PRODUCT_CATEGORY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `JOBS_X_SYSTEM_PRODUCT_CATEGORY` (
  `cd_jobs_x_system_product_category` int(11) NOT NULL,
  `cd_jobs` int(11) NOT NULL,
  `cd_system_product_category` int(11) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_jobs_x_system_product_category`),
  UNIQUE KEY `IUNJOBS_X_SYSTEM_PRODUCT_CATEGORY002` (`cd_jobs`,`cd_system_product_category`),
  CONSTRAINT `FKJOBS_X_SYSTEM_PRODUCT_CATEGORY001` FOREIGN KEY (`cd_jobs`) REFERENCES `JOBS` (`cd_jobs`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `JOBS_X_SYSTEM_PRODUCT_CATEGORY`
--

LOCK TABLES `JOBS_X_SYSTEM_PRODUCT_CATEGORY` WRITE;
/*!40000 ALTER TABLE `JOBS_X_SYSTEM_PRODUCT_CATEGORY` DISABLE KEYS */;
INSERT INTO `JOBS_X_SYSTEM_PRODUCT_CATEGORY` VALUES (1,4,1,'2017-06-13 17:53:30'),(3,8,1,NULL),(4,16,1,NULL),(5,16,2,'2017-06-19 20:33:46');
/*!40000 ALTER TABLE `JOBS_X_SYSTEM_PRODUCT_CATEGORY` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_JOBS_X_SYSTEM_PRODUCT_CATEGORY BEFORE INSERT ON JOBS_X_SYSTEM_PRODUCT_CATEGORY
FOR EACH ROW
BEGIN
    IF NEW.cd_jobs_x_system_product_category IS NULL THEN
        SET NEW.cd_jobs_x_system_product_category = nextval('JOBS_X_SYSTEM_PRODUCT_CATEGORY');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `LEAVES`
--

DROP TABLE IF EXISTS `LEAVES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LEAVES` (
  `cd_leaves` int(11) NOT NULL,
  `cd_leave_type` int(11) NOT NULL,
  `cd_employee_requesting` int(11) NOT NULL,
  `cd_employee_requester` int(11) NOT NULL,
  `dt_requested` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dt_start_date` datetime NOT NULL,
  `dt_end_date` datetime NOT NULL,
  `cd_approval_status` int(11) DEFAULT NULL,
  `cd_employee_approver` int(11) DEFAULT NULL,
  `dt_approval_date` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_leaves`),
  KEY `FKLEAVES01` (`cd_leave_type`),
  KEY `FKLEAVES02` (`cd_employee_approver`),
  KEY `FKLEAVES03` (`cd_employee_requester`),
  KEY `FKLEAVES04` (`cd_approval_status`),
  KEY `FKLEAVES05` (`cd_employee_requesting`),
  CONSTRAINT `FKLEAVES01` FOREIGN KEY (`cd_leave_type`) REFERENCES `LEAVE_TYPE` (`cd_leave_type`),
  CONSTRAINT `FKLEAVES02` FOREIGN KEY (`cd_employee_approver`) REFERENCES `EMPLOYEE` (`cd_employee`),
  CONSTRAINT `FKLEAVES03` FOREIGN KEY (`cd_employee_requester`) REFERENCES `EMPLOYEE` (`cd_employee`),
  CONSTRAINT `FKLEAVES04` FOREIGN KEY (`cd_approval_status`) REFERENCES `APPROVAL_STATUS` (`cd_approval_status`),
  CONSTRAINT `FKLEAVES05` FOREIGN KEY (`cd_employee_requesting`) REFERENCES `EMPLOYEE` (`cd_employee`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `LEAVES`
--

LOCK TABLES `LEAVES` WRITE;
/*!40000 ALTER TABLE `LEAVES` DISABLE KEYS */;
/*!40000 ALTER TABLE `LEAVES` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_LEAVES BEFORE INSERT ON LEAVES
FOR EACH ROW
BEGIN
    IF NEW.cd_leaves IS NULL THEN
        SET NEW.cd_leaves = nextval('LEAVES');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `LEAVE_TYPE`
--

DROP TABLE IF EXISTS `LEAVE_TYPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LEAVE_TYPE` (
  `cd_leave_type` int(11) NOT NULL,
  `ds_leave_type` varchar(64) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_leave_type`),
  UNIQUE KEY `IUNLEAVE_TYPE001` (`ds_leave_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `LEAVE_TYPE`
--

LOCK TABLES `LEAVE_TYPE` WRITE;
/*!40000 ALTER TABLE `LEAVE_TYPE` DISABLE KEYS */;
INSERT INTO `LEAVE_TYPE` VALUES (1,'MATERNITY',NULL,'2017-11-05 22:49:14'),(2,'SICK',NULL,'2017-11-05 22:49:14'),(3,'VACATION',NULL,'2017-11-05 22:49:14'),(4,'CASUAL',NULL,'2017-11-05 22:49:14'),(5,'BREAST FEEDING',NULL,'2017-11-05 22:49:14');
/*!40000 ALTER TABLE `LEAVE_TYPE` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_LEAVE_TYPE BEFORE INSERT ON LEAVE_TYPE
FOR EACH ROW
BEGIN
    IF NEW.cd_leave_type IS NULL THEN
        SET NEW.cd_leave_type = nextval('LEAVE_TYPE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `LOCATION`
--

DROP TABLE IF EXISTS `LOCATION`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LOCATION` (
  `cd_location` int(11) NOT NULL,
  `ds_location` varchar(255) NOT NULL,
  `cd_country` int(11) NOT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dt_deactivated` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_location`),
  UNIQUE KEY `IUNLOCATION001` (`ds_location`),
  KEY `FKLOCATION01` (`cd_country`),
  CONSTRAINT `FKLOCATION01` FOREIGN KEY (`cd_country`) REFERENCES `COUNTRY` (`cd_country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `LOCATION`
--

LOCK TABLES `LOCATION` WRITE;
/*!40000 ALTER TABLE `LOCATION` DISABLE KEYS */;
INSERT INTO `LOCATION` VALUES (3,'LOCATION 1',44,'2017-11-19 19:08:23',NULL),(4,'LOCATION 2',251,'2017-11-19 19:08:23',NULL);
/*!40000 ALTER TABLE `LOCATION` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_LOCATION BEFORE INSERT ON LOCATION
FOR EACH ROW
BEGIN
    IF NEW.cd_location IS NULL THEN
        SET NEW.cd_location = nextval('LOCATION');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Temporary table structure for view `MENU`
--

DROP TABLE IF EXISTS `MENU`;
/*!50001 DROP VIEW IF EXISTS `MENU`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `MENU` AS SELECT 
 1 AS `cd_menu`,
 1 AS `ds_menu`,
 1 AS `ds_controller`,
 1 AS `cd_menu_parent`,
 1 AS `dt_deactivated`,
 1 AS `dt_record`,
 1 AS `nr_order`,
 1 AS `ds_image`,
 1 AS `fl_always_available`,
 1 AS `fl_visible`,
 1 AS `fl_only_for_super_users`,
 1 AS `cds_system_product_category_allowed`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `MENU_SPECIFIC`
--

DROP TABLE IF EXISTS `MENU_SPECIFIC`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MENU_SPECIFIC` (
  `cd_menu` int(11) NOT NULL,
  `ds_menu` varchar(32) NOT NULL,
  `ds_controller` varchar(128) NOT NULL,
  `cd_menu_parent` int(11) DEFAULT NULL,
  `dt_deactivated` date DEFAULT NULL,
  `dt_record` datetime DEFAULT NULL,
  `nr_order` int(11) DEFAULT NULL,
  `ds_image` longtext,
  `fl_always_available` char(1) NOT NULL DEFAULT 'N',
  `fl_visible` char(1) NOT NULL DEFAULT 'Y',
  `fl_only_for_super_users` char(1) NOT NULL DEFAULT 'N',
  `cds_system_product_category_allowed` longtext,
  PRIMARY KEY (`cd_menu`),
  KEY `fki_FKMENU001` (`cd_menu_parent`),
  CONSTRAINT `FKMENU001` FOREIGN KEY (`cd_menu_parent`) REFERENCES `MENU_SPECIFIC` (`cd_menu`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MENU_SPECIFIC`
--

LOCK TABLES `MENU_SPECIFIC` WRITE;
/*!40000 ALTER TABLE `MENU_SPECIFIC` DISABLE KEYS */;
/*!40000 ALTER TABLE `MENU_SPECIFIC` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_MENU_SPECIFIC BEFORE INSERT ON MENU_SPECIFIC
FOR EACH ROW
BEGIN
    IF NEW.cd_menu IS NULL THEN
        SET NEW.cd_menu = nextval('MENU_SPECIFIC');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `MOVEMENTS`
--

DROP TABLE IF EXISTS `MOVEMENTS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MOVEMENTS` (
  `cd_movements` int(11) NOT NULL,
  `cd_employee` int(11) NOT NULL,
  `cd_benefit_type` int(11) NOT NULL,
  `cd_human_resource_record` int(11) NOT NULL,
  `dt_start_at` datetime NOT NULL,
  `dt_expires_at` datetime DEFAULT NULL,
  `nr_value_to_add` decimal(12,2) DEFAULT '0.00',
  `nr_value_actual` decimal(12,2) DEFAULT '0.00',
  `ds_movements_position` char(1) DEFAULT 'F' COMMENT 'L -> Means Last Movement\nF -> First Movement\nH -> History',
  `ds_comments` varchar(255) DEFAULT NULL,
  `cd_employee_x_bank_branch` int(11) DEFAULT NULL,
  `cd_benefit_operation` int(11) DEFAULT NULL,
  `dt_record` datetime DEFAULT CURRENT_TIMESTAMP,
  `cd_currency` int(11) DEFAULT NULL,
  PRIMARY KEY (`cd_movements`),
  KEY `FKMOVEMENTS001_idx` (`cd_employee`),
  KEY `FKMOVEMENTS002_idx` (`cd_benefit_type`),
  KEY `FKMOVEMENTS003_idx` (`cd_human_resource_record`),
  KEY `IDXMOVEMENTS003` (`dt_start_at`),
  KEY `IDXMOVEMENTS004` (`dt_expires_at`),
  KEY `IDXMOVEMENTS005` (`ds_movements_position`),
  KEY `FKMOVEMENTS004_idx` (`cd_employee_x_bank_branch`),
  KEY `FKMOVEMENTS005_idx` (`cd_benefit_operation`),
  KEY `FKMOVEMENTS006_idx` (`cd_currency`),
  CONSTRAINT `FKMOVEMENTS001` FOREIGN KEY (`cd_employee`) REFERENCES `EMPLOYEE` (`cd_employee`),
  CONSTRAINT `FKMOVEMENTS002` FOREIGN KEY (`cd_benefit_type`) REFERENCES `BENEFIT_TYPE` (`cd_benefit_type`),
  CONSTRAINT `FKMOVEMENTS003` FOREIGN KEY (`cd_human_resource_record`) REFERENCES `HUMAN_RESOURCE` (`cd_human_resource`),
  CONSTRAINT `FKMOVEMENTS004` FOREIGN KEY (`cd_employee_x_bank_branch`) REFERENCES `EMPLOYEE_X_BANK_BRANCH` (`cd_employee_x_bank_branch`) ON DELETE SET NULL,
  CONSTRAINT `FKMOVEMENTS005` FOREIGN KEY (`cd_benefit_operation`) REFERENCES `BENEFIT_OPERATION` (`cd_benefit_operation`),
  CONSTRAINT `FKMOVEMENTS006` FOREIGN KEY (`cd_currency`) REFERENCES `CURRENCY` (`cd_currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MOVEMENTS`
--

LOCK TABLES `MOVEMENTS` WRITE;
/*!40000 ALTER TABLE `MOVEMENTS` DISABLE KEYS */;
INSERT INTO `MOVEMENTS` VALUES (34,339,3,43,'2017-12-14 00:00:00','2017-12-01 00:00:00',1.00,1.00,'F',NULL,NULL,1,'2017-12-14 15:30:36',NULL),(35,339,3,43,'2017-12-14 00:00:00','2017-12-14 00:00:00',2.00,3.00,'F',NULL,NULL,1,'2017-12-14 15:31:01',NULL),(36,339,3,43,'2017-12-14 00:00:00',NULL,1.00,2.00,'F',NULL,NULL,2,'2017-12-14 15:31:27',NULL),(37,339,3,43,'2017-12-14 00:00:00',NULL,5.00,7.00,'F',NULL,NULL,1,'2017-12-14 15:31:51',NULL),(38,339,8,43,'2017-12-14 00:00:00',NULL,12000.00,12000.00,'F','dd',63,1,'2017-12-14 15:32:37',3),(39,339,8,43,'2017-12-15 00:00:00',NULL,1450.00,13450.00,'F',NULL,NULL,1,'2017-12-14 15:33:00',3),(40,339,3,43,'2017-12-14 00:00:00',NULL,2.00,9.00,'F',NULL,NULL,1,'2017-12-14 15:43:13',NULL),(41,339,3,43,'2017-12-14 00:00:00',NULL,1.00,10.00,'F',NULL,NULL,1,'2017-12-14 15:43:40',NULL),(42,339,8,43,'2017-12-15 00:00:00',NULL,550.00,14000.00,'F',NULL,63,1,'2017-12-14 16:05:01',3),(43,339,8,43,'2017-12-15 00:00:00',NULL,500.00,14500.00,'F',NULL,63,1,'2017-12-14 16:14:50',3),(44,339,8,43,'2017-12-15 00:00:00',NULL,500.00,15000.00,'F',NULL,63,1,'2017-12-14 16:15:13',3),(45,339,3,43,'2017-12-14 00:00:00',NULL,1.00,11.00,'F',NULL,NULL,1,'2017-12-14 16:27:10',NULL),(46,339,1,43,'2017-12-14 00:00:00',NULL,100.00,100.00,'F',NULL,63,1,'2017-12-14 16:28:05',3),(47,339,1,43,'2017-12-15 00:00:00',NULL,20.00,120.00,'F','dd',63,1,'2017-12-14 16:34:10',3),(48,339,6,43,'2017-12-14 00:00:00','2017-12-14 00:00:00',20.00,20.00,'F','a',63,1,'2017-12-14 16:36:36',3),(49,339,6,43,'2017-12-14 00:00:00',NULL,3.00,23.00,'F',NULL,63,1,'2017-12-14 16:37:27',3),(50,339,5,43,'2017-12-14 00:00:00',NULL,2000.00,2000.00,'F','a',63,1,'2017-12-14 16:40:45',3),(58,280,3,43,'2017-12-15 00:00:00',NULL,2.00,2.00,'F',NULL,NULL,1,'2017-12-15 14:20:07',NULL),(66,280,3,43,'2017-12-15 00:00:00',NULL,1.00,1.00,'F','Added by Flight Ticked #1',NULL,2,'2017-12-15 20:43:18',NULL);
/*!40000 ALTER TABLE `MOVEMENTS` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_MOVEMENTS BEFORE INSERT ON MOVEMENTS
FOR EACH ROW
BEGIN
	DECLARE nr_value_to_add_max decimal(12,2);
	DECLARE nr_value_actual_max   decimal(12,2);

	IF EXISTS ( select 1 
                       FROM MOVEMENTS 
					WHERE cd_employee = NEW.cd_employee
						AND cd_benefit_type = NEW.cd_benefit_type
						AND date(dt_start_at) > date(NEW.dt_start_at) ) THEN
		signal sqlstate '45000' set message_text = 'The Start At date cannot be set on a previous date (considering existing Movements)';
    END IF;


    IF NEW.cd_movements IS NULL THEN
        SET NEW.cd_movements = nextval('MOVEMENTS');
     END IF;
     
     IF NEW.cd_human_resource_record IS NULL THEN
		SEt NEW.cd_human_resource_record = getvar('cd_human_resource');
     END IF;
     
     SELECT  nr_value_actual
			INTO nr_value_actual_max
		  FROM MOVEMENTS
		WHERE cd_employee    = NEW.cd_employee
             AND cd_benefit_type = NEW.cd_benefit_type
             ORDER BY dt_start_at DESC, cd_movements DESC
             LIMIT 1;
     
    SET nr_value_actual_max = COALESCE(nr_value_actual_max, 0); 
     
	IF COALESCE(NEW.nr_value_actual, 0) > 0 THEN
	   SET NEW.nr_value_to_add = ABS( NEW.nr_value_actual - nr_value_actual_max );
      
       IF NEW.nr_value_actual - nr_value_actual_max > 0 THEN
			SET NEW.cd_benefit_operation = 1;
       ELSE
			SET NEW.cd_benefit_operation = 2;
       END IF;
 	END IF;

	IF COALESCE(NEW.nr_value_actual, 0) = 0 AND  COALESCE(NEW.nr_value_to_add, 0) > 0  THEN
      
      IF NEW.cd_benefit_operation  = 1 THEN
			SET NEW.nr_value_actual  = nr_value_actual_max + NEW.nr_value_to_add;
      ELSE
			SET NEW.nr_value_actual  = nr_value_actual_max - NEW.nr_value_to_add;      
      END IF;

 	END IF;
     
     
     
     
     
     
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER `hrms`.`MOVEMENTS_AFTER_INSERT` AFTER INSERT ON `MOVEMENTS` FOR EACH ROW
BEGIN

	DECLARE cd_movements_max INT;
    DECLARE cd_movements_min INT;

     SELECT  cd_movements
			INTO cd_movements_max
		  FROM MOVEMENTS
		WHERE cd_employee    = NEW.cd_employee
             AND cd_benefit_type = NEW.cd_benefit_type
             ORDER BY dt_start_at DESC, cd_movements DESC
             LIMIT 1;

     SELECT  cd_movements
			INTO cd_movements_min
		  FROM MOVEMENTS
		WHERE cd_employee    = NEW.cd_employee
             AND cd_benefit_type = NEW.cd_benefit_type
             ORDER BY dt_start_at ASC, cd_movements ASC
             LIMIT 1;


	INSERT INTO MOVEMENT_LAST (cd_employee,cd_benefit_type,cd_movement_last) 
    VALUES (NEW.cd_employee, NEW.cd_benefit_type, cd_movements_max)
	ON DUPLICATE KEY UPDATE cd_employee  		= NEW.cd_employee, 
														cd_benefit_type 	= NEW.cd_benefit_type,
														cd_movement_last 	=cd_movements_max;

	INSERT INTO MOVEMENT_FIRST (cd_employee,cd_benefit_type,cd_movement_first) 
    VALUES (NEW.cd_employee, NEW.cd_benefit_type, cd_movements_min)
	ON DUPLICATE KEY UPDATE cd_employee  		= NEW.cd_employee, 
														cd_benefit_type 	= NEW.cd_benefit_type,
														cd_movement_first	=cd_movements_min;



END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER `hrms`.`MOVEMENTS_AFTER_UPDATE` AFTER UPDATE ON `MOVEMENTS` FOR EACH ROW
BEGIN


	DECLARE cd_movements_max INT;
    DECLARE cd_movements_min INT;

     SELECT  cd_movements
			INTO cd_movements_max
		  FROM MOVEMENTS
		WHERE cd_employee    = NEW.cd_employee
             AND cd_benefit_type = NEW.cd_benefit_type
             ORDER BY dt_start_at DESC, cd_movements DESC
             LIMIT 1;

     SELECT  cd_movements
			INTO cd_movements_min
		  FROM MOVEMENTS
		WHERE cd_employee    = NEW.cd_employee
             AND cd_benefit_type = NEW.cd_benefit_type
             ORDER BY dt_start_at ASC, cd_movements ASC
             LIMIT 1;


	INSERT INTO MOVEMENT_LAST (cd_employee,cd_benefit_type,cd_movement_last) 
    VALUES (NEW.cd_employee, NEW.cd_benefit_type, cd_movements_max)
	ON DUPLICATE KEY UPDATE cd_employee  		= NEW.cd_employee, 
														cd_benefit_type 	= NEW.cd_benefit_type,
														cd_movement_last 	=cd_movements_max;

	INSERT INTO MOVEMENT_FIRST (cd_employee,cd_benefit_type,cd_movement_first) 
    VALUES (NEW.cd_employee, NEW.cd_benefit_type, cd_movements_min)
	ON DUPLICATE KEY UPDATE cd_employee  		= NEW.cd_employee, 
														cd_benefit_type 	= NEW.cd_benefit_type,
														cd_movement_first	=cd_movements_min;


END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER `hrms`.`MOVEMENTS_AFTER_DELETE` AFTER DELETE ON `MOVEMENTS` FOR EACH ROW
BEGIN



	DECLARE cd_movements_max INT;
    DECLARE cd_movements_min INT;

     SELECT  cd_movements
			INTO cd_movements_max
		  FROM MOVEMENTS
		WHERE cd_employee    = OLD.cd_employee
             AND cd_benefit_type = OLD.cd_benefit_type
             ORDER BY dt_start_at DESC, cd_movements DESC
             LIMIT 1;

     SELECT  cd_movements
			INTO cd_movements_min
		  FROM MOVEMENTS
		WHERE cd_employee    = OLD.cd_employee
             AND cd_benefit_type = OLD.cd_benefit_type
             ORDER BY dt_start_at ASC, cd_movements ASC
             LIMIT 1;


	INSERT INTO MOVEMENT_LAST (cd_employee,cd_benefit_type,cd_movement_last) 
    VALUES (OLD.cd_employee, OLD.cd_benefit_type, cd_movements_max)
	ON DUPLICATE KEY UPDATE cd_employee  		= OLD.cd_employee, 
														cd_benefit_type 	= OLD.cd_benefit_type,
														cd_movement_last 	=cd_movements_max;

	INSERT INTO MOVEMENT_FIRST (cd_employee,cd_benefit_type,cd_movement_first) 
    VALUES (OLD.cd_employee, OLD.cd_benefit_type, cd_movements_min)
	ON DUPLICATE KEY UPDATE cd_employee  		= OLD.cd_employee, 
														cd_benefit_type 	= OLD.cd_benefit_type,
														cd_movement_first	=cd_movements_min;




END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `MOVEMENT_FIRST`
--

DROP TABLE IF EXISTS `MOVEMENT_FIRST`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MOVEMENT_FIRST` (
  `cd_employee` int(11) NOT NULL,
  `cd_benefit_type` int(11) NOT NULL,
  `cd_movement_first` int(11) NOT NULL,
  PRIMARY KEY (`cd_employee`,`cd_benefit_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MOVEMENT_FIRST`
--

LOCK TABLES `MOVEMENT_FIRST` WRITE;
/*!40000 ALTER TABLE `MOVEMENT_FIRST` DISABLE KEYS */;
INSERT INTO `MOVEMENT_FIRST` VALUES (280,3,58),(339,1,46),(339,3,34),(339,5,50),(339,6,48),(339,8,38);
/*!40000 ALTER TABLE `MOVEMENT_FIRST` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MOVEMENT_LAST`
--

DROP TABLE IF EXISTS `MOVEMENT_LAST`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MOVEMENT_LAST` (
  `cd_employee` int(11) NOT NULL,
  `cd_benefit_type` int(11) NOT NULL,
  `cd_movement_last` int(11) NOT NULL,
  PRIMARY KEY (`cd_employee`,`cd_benefit_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MOVEMENT_LAST`
--

LOCK TABLES `MOVEMENT_LAST` WRITE;
/*!40000 ALTER TABLE `MOVEMENT_LAST` DISABLE KEYS */;
INSERT INTO `MOVEMENT_LAST` VALUES (280,3,66),(339,1,47),(339,3,45),(339,5,50),(339,6,49),(339,8,44);
/*!40000 ALTER TABLE `MOVEMENT_LAST` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PAYMENT_TYPE`
--

DROP TABLE IF EXISTS `PAYMENT_TYPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PAYMENT_TYPE` (
  `cd_payment_type` int(11) NOT NULL,
  `ds_payment_type` varchar(64) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_payment_type`),
  UNIQUE KEY `IUNPAYMENT_TYPE001` (`ds_payment_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PAYMENT_TYPE`
--

LOCK TABLES `PAYMENT_TYPE` WRITE;
/*!40000 ALTER TABLE `PAYMENT_TYPE` DISABLE KEYS */;
INSERT INTO `PAYMENT_TYPE` VALUES (1,'PAYMENT',NULL,'2017-12-15 17:50:40');
/*!40000 ALTER TABLE `PAYMENT_TYPE` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_PAYMENT_TYPE BEFORE INSERT ON PAYMENT_TYPE
FOR EACH ROW
BEGIN
    IF NEW.cd_payment_type IS NULL THEN
        SET NEW.cd_payment_type = nextval('PAYMENT_TYPE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `PERSONAL_INFO`
--

DROP TABLE IF EXISTS `PERSONAL_INFO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PERSONAL_INFO` (
  `cd_personal_info` int(11) NOT NULL,
  `ds_first_name` varchar(96) NOT NULL,
  `ds_surname` varchar(96) DEFAULT NULL,
  `ds_full_name_non_english` varchar(128) DEFAULT NULL,
  `dt_birthday` datetime DEFAULT NULL,
  `dt_birthday_non_english` datetime DEFAULT NULL,
  `cd_gender` int(11) DEFAULT NULL,
  `cd_civil_status` int(11) DEFAULT NULL,
  `cd_country` int(11) DEFAULT NULL,
  `cd_education` int(11) DEFAULT NULL,
  `cd_residence_type` int(11) DEFAULT NULL,
  `nr_non_prof_working_year` int(11) DEFAULT NULL,
  PRIMARY KEY (`cd_personal_info`),
  KEY `FKPERSONAL_INFO01` (`cd_residence_type`),
  KEY `FKPERSONAL_INFO02` (`cd_gender`),
  KEY `FKPERSONAL_INFO03` (`cd_civil_status`),
  KEY `FKPERSONAL_INFO04` (`cd_country`),
  KEY `FKPERSONAL_INFO05` (`cd_education`),
  CONSTRAINT `FKPERSONAL_INFO01` FOREIGN KEY (`cd_residence_type`) REFERENCES `RESIDENCE_TYPE` (`cd_residence_type`),
  CONSTRAINT `FKPERSONAL_INFO02` FOREIGN KEY (`cd_gender`) REFERENCES `GENDER` (`cd_gender`),
  CONSTRAINT `FKPERSONAL_INFO03` FOREIGN KEY (`cd_civil_status`) REFERENCES `CIVIL_STATUS` (`cd_civil_status`),
  CONSTRAINT `FKPERSONAL_INFO04` FOREIGN KEY (`cd_country`) REFERENCES `COUNTRY` (`cd_country`),
  CONSTRAINT `FKPERSONAL_INFO05` FOREIGN KEY (`cd_education`) REFERENCES `EDUCATION` (`cd_education`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PERSONAL_INFO`
--

LOCK TABLES `PERSONAL_INFO` WRITE;
/*!40000 ALTER TABLE `PERSONAL_INFO` DISABLE KEYS */;
INSERT INTO `PERSONAL_INFO` VALUES (285,'GABRIEL','BLOS','NOME ESTRANHO','2017-09-20 00:00:00','2017-11-16 00:00:00',1,1,44,1,1,12),(290,'OUTRO',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(291,'C','A',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(300,'OUTRO NOME','SOBRE',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL),(301,'NOVO NOME','BLOS',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL),(302,'CGB','CG','A',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL),(303,'SUOPER','EU','XXXX',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(304,'OUTR','OEI','AJAJA',NULL,NULL,2,NULL,NULL,NULL,NULL,NULL),(306,'ALGUMA','COISA',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL),(310,'AA','AA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(311,'AAA','VVVV',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(313,'AA','BB',NULL,NULL,NULL,1,NULL,167,NULL,NULL,NULL),(328,'CARLOS GUILHERME','BLOS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(329,'OUTRUO','USER',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(330,'FIRST','NAME',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(331,'HH','KK',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(332,'VAI MORRER','DELETADO',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(333,'GISELA','LIANG',NULL,'2017-11-19 00:00:00',NULL,2,NULL,NULL,NULL,NULL,NULL),(334,'TES','AA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(335,'AA','AA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(338,'A','B',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(603,'ATB','A',NULL,NULL,NULL,2,1,NULL,NULL,NULL,NULL),(607,'MEU FILHO','PEQUENO LINDO','CARLOS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(609,'GISELA','LIANG',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(613,'CGB','A',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(614,'CGB','BLKOS',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL),(616,'A','B',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(620,'CARLOS','GUILHERME',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(621,'CGB','A',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(622,'CGB','A','B',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(623,'BLA','BLABAS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(624,'CC','AA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(625,'AAA','BBB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(626,'AA','BB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(627,'AA','BB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(628,'AAA','BBB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(629,'AA','BB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(630,'AA','BB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(631,'SADALK','KAKAK',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(632,'ASDKASJL','ASKLDJALK',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(633,'FAZ','GFAZG',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(634,'AA','BBB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(635,'AA','BB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(636,'NOVO','SR',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL),(640,'FIRST','LAST',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(642,'CA','BOM',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(644,'FIRT','NAME',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(646,'VOVO','ZE',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(650,'DAVIDSON','NUNES',NULL,'2017-12-21 00:00:00',NULL,1,NULL,NULL,NULL,1,NULL),(652,'ANGELICA','NUNES',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `PERSONAL_INFO` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_PERSONAL_INFO BEFORE INSERT ON PERSONAL_INFO
FOR EACH ROW
BEGIN
    IF NEW.cd_personal_info IS NULL THEN
        SET NEW.cd_personal_info = nextval('PERSONAL_INFO');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `PROVINCE`
--

DROP TABLE IF EXISTS `PROVINCE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PROVINCE` (
  `cd_province` int(11) NOT NULL,
  `ds_province` varchar(255) NOT NULL,
  `cd_country` int(11) NOT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dt_deactivated` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_province`),
  UNIQUE KEY `IUNPROVINCE001` (`ds_province`),
  KEY `FKPROVINCE01` (`cd_country`),
  CONSTRAINT `FKPROVINCE01` FOREIGN KEY (`cd_country`) REFERENCES `COUNTRY` (`cd_country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PROVINCE`
--

LOCK TABLES `PROVINCE` WRITE;
/*!40000 ALTER TABLE `PROVINCE` DISABLE KEYS */;
INSERT INTO `PROVINCE` VALUES (5,'RIO GRANDE DO SUL',243,'2017-11-05 17:52:19',NULL);
/*!40000 ALTER TABLE `PROVINCE` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_PROVINCE BEFORE INSERT ON PROVINCE
FOR EACH ROW
BEGIN
    IF NEW.cd_province IS NULL THEN
        SET NEW.cd_province = nextval('PROVINCE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `RECORD_GEN`
--

DROP TABLE IF EXISTS `RECORD_GEN`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RECORD_GEN` (
  `cd_record_gen` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `RECORD_GEN`
--

LOCK TABLES `RECORD_GEN` WRITE;
/*!40000 ALTER TABLE `RECORD_GEN` DISABLE KEYS */;
INSERT INTO `RECORD_GEN` VALUES (1);
/*!40000 ALTER TABLE `RECORD_GEN` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RELATIONSHIP_TYPE`
--

DROP TABLE IF EXISTS `RELATIONSHIP_TYPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RELATIONSHIP_TYPE` (
  `cd_relationship_type` int(11) NOT NULL,
  `ds_relationship_type` varchar(64) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_relationship_type`),
  UNIQUE KEY `IUNRELATIONSHIP_TYPE001` (`ds_relationship_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `RELATIONSHIP_TYPE`
--

LOCK TABLES `RELATIONSHIP_TYPE` WRITE;
/*!40000 ALTER TABLE `RELATIONSHIP_TYPE` DISABLE KEYS */;
INSERT INTO `RELATIONSHIP_TYPE` VALUES (1,'SPOUSE',NULL,'2017-11-05 22:51:05'),(2,'CHILD',NULL,'2017-11-05 22:51:52'),(3,'FATHER',NULL,'2017-11-05 22:51:52'),(4,'MOTHER',NULL,'2017-11-05 22:51:52'),(5,'SIMBLING',NULL,'2017-11-05 22:51:52'),(6,'LIFE PARTNET',NULL,'2017-11-05 22:51:52');
/*!40000 ALTER TABLE `RELATIONSHIP_TYPE` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_RELATIONSHIP_TYPE BEFORE INSERT ON RELATIONSHIP_TYPE
FOR EACH ROW
BEGIN
    IF NEW.cd_relationship_type IS NULL THEN
        SET NEW.cd_relationship_type = nextval('RELATIONSHIP_TYPE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `RESIDENCE_TYPE`
--

DROP TABLE IF EXISTS `RESIDENCE_TYPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RESIDENCE_TYPE` (
  `cd_residence_type` int(11) NOT NULL,
  `ds_residence_type` varchar(64) NOT NULL,
  `dt_deactivated` datetime DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_residence_type`),
  UNIQUE KEY `IUNRESIDENCE_TYPE001` (`ds_residence_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `RESIDENCE_TYPE`
--

LOCK TABLES `RESIDENCE_TYPE` WRITE;
/*!40000 ALTER TABLE `RESIDENCE_TYPE` DISABLE KEYS */;
INSERT INTO `RESIDENCE_TYPE` VALUES (1,'RESIDENCE TYPE',NULL,'2017-11-12 19:24:24');
/*!40000 ALTER TABLE `RESIDENCE_TYPE` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_RESIDENCE_TYPE BEFORE INSERT ON RESIDENCE_TYPE
FOR EACH ROW
BEGIN
    IF NEW.cd_residence_type IS NULL THEN
        SET NEW.cd_residence_type = nextval('RESIDENCE_TYPE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SESSION_LOG`
--

DROP TABLE IF EXISTS `SESSION_LOG`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SESSION_LOG` (
  `cd_session_log` int(11) NOT NULL,
  `ds_database` char(64) NOT NULL,
  `ds_session` longtext NOT NULL,
  `ds_username` char(64) NOT NULL,
  `dt_logged` datetime NOT NULL,
  `dt_last_access` datetime NOT NULL,
  `dt_expired` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_session_log`),
  KEY `IDXSESSION_LOG001` (`ds_session`(255)),
  KEY `IDXSESSION_LOG002` (`ds_database`),
  KEY `IDXSESSION_LOG003` (`dt_last_access`),
  KEY `IDXSESSION_LOG004` (`dt_expired`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SESSION_LOG`
--

LOCK TABLES `SESSION_LOG` WRITE;
/*!40000 ALTER TABLE `SESSION_LOG` DISABLE KEYS */;
INSERT INTO `SESSION_LOG` VALUES (1,'hrms','u7eo7cmut2ofas5tm83v69or73lsr41k','admin','2017-11-13 19:18:37','2017-11-13 22:22:52',NULL),(2,'hrms','igup12l22unhoukb57jvqsftern5pljk','admin','2017-11-14 09:47:49','2017-11-14 10:03:12','2017-11-14 22:07:25'),(3,'hrms','igup12l22unhoukb57jvqsftern5pljk','admin','2017-11-14 15:22:38','2017-11-14 17:35:36','2017-11-14 22:07:25'),(4,'hrms','igup12l22unhoukb57jvqsftern5pljk','admin','2017-11-14 20:04:39','2017-11-14 20:17:45','2017-11-14 22:07:25'),(5,'hrms','fckq9sat3heh1ih54bklqqj93qnorbj9','admin','2017-11-09 19:19:54','2017-11-09 19:19:54','2017-11-10 09:27:24'),(6,'hrms','fckq9sat3heh1ih54bklqqj93qnorbj9','admin','2017-11-09 19:23:34','2017-11-09 19:23:34','2017-11-10 09:27:24'),(7,'hrms','fckq9sat3heh1ih54bklqqj93qnorbj9','admin','2017-11-10 09:27:24','2017-11-10 09:27:24','2017-11-10 16:12:29'),(8,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-10 10:52:35','2017-11-10 10:52:35','2017-11-12 19:16:01'),(9,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-10 11:06:37','2017-11-10 11:06:37','2017-11-12 19:16:01'),(10,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-10 11:15:23','2017-11-10 11:15:23','2017-11-12 19:16:01'),(11,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-10 11:24:27','2017-11-10 11:24:27','2017-11-12 19:16:01'),(12,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-10 11:29:26','2017-11-10 11:29:26','2017-11-12 19:16:01'),(13,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-10 12:04:01','2017-11-10 12:04:01','2017-11-12 19:16:01'),(14,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-10 12:09:30','2017-11-10 12:09:30','2017-11-12 19:16:01'),(15,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-10 12:13:05','2017-11-10 12:13:05','2017-11-12 19:16:01'),(16,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-10 12:28:18','2017-11-10 12:28:18','2017-11-12 19:16:01'),(17,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-10 14:00:49','2017-11-10 14:00:49','2017-11-12 19:16:01'),(18,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-10 14:11:08','2017-11-10 14:11:08','2017-11-12 19:16:01'),(19,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-10 15:46:35','2017-11-10 15:46:35','2017-11-12 19:16:01'),(20,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-10 16:48:11','2017-11-10 16:48:11','2017-11-12 19:16:01'),(21,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-11 07:43:56','2017-11-11 07:43:56','2017-11-12 19:16:01'),(22,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-11 08:53:05','2017-11-11 08:53:05','2017-11-12 19:16:01'),(23,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-11 10:19:05','2017-11-11 10:19:05','2017-11-12 19:16:01'),(24,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-11 13:38:55','2017-11-11 13:42:54','2017-11-12 19:16:01'),(25,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-11 13:43:06','2017-11-11 14:10:00','2017-11-12 19:16:01'),(26,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-11 16:00:12','2017-11-11 17:11:50','2017-11-12 19:16:01'),(27,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-12 11:54:05','2017-11-12 13:07:29','2017-11-12 19:16:01'),(28,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-12 16:26:01','2017-11-12 16:54:34','2017-11-12 19:16:01'),(29,'hrms','fc67fv7sca8ccenod935mc6k78387idg','admin','2017-11-12 19:16:01','2017-11-12 19:34:15',NULL),(30,'hrms','u7eo7cmut2ofas5tm83v69or73lsr41k','admin','2017-11-12 21:58:39','2017-11-12 22:04:41','2017-11-13 19:18:37'),(31,'hrms','u7eo7cmut2ofas5tm83v69or73lsr41k','admin','2017-11-13 13:15:23','2017-11-13 17:42:19','2017-11-13 19:18:37'),(32,'hrms','igup12l22unhoukb57jvqsftern5pljk','admin','2017-11-14 22:07:25','2017-11-14 23:11:01',NULL),(33,'hrms','tg9pp7im7505748e102cmr0l5bimejct','admin','2017-11-15 08:45:53','2017-11-15 09:09:01',NULL),(34,'hrms','phshdacgrdt5gmr987gs3oah2djdbv5a','admin','2017-11-15 10:47:33','2017-11-15 10:51:17',NULL),(35,'hrms','u2m2pfvm50dhsnt65hij6m72u78fbohg','admin','2017-11-15 13:56:57','2017-11-15 16:53:44',NULL),(36,'hrms','ua7vp26i32rvm6qkiv1ehi18o82ivhk5','admin','2017-11-15 17:29:47','2017-11-15 17:39:42',NULL),(37,'hrms','3d7d9epe3p4iueduivj98v20kqdhfck1','admin','2017-11-15 19:42:48','2017-11-15 22:08:55','2017-11-16 12:55:37'),(38,'hrms','3d7d9epe3p4iueduivj98v20kqdhfck1','admin','2017-11-16 08:42:53','2017-11-16 09:26:57','2017-11-16 12:55:37'),(39,'hrms','3d7d9epe3p4iueduivj98v20kqdhfck1','admin','2017-11-16 10:49:59','2017-11-16 11:29:38','2017-11-16 12:55:37'),(40,'hrms','3d7d9epe3p4iueduivj98v20kqdhfck1','admin','2017-11-16 12:55:37','2017-11-16 17:39:38',NULL),(41,'hrms','a1stveggd7p4cfd9vu598ffj5ru0pvae','admin','2017-11-16 19:59:52','2017-11-16 19:59:52','2017-11-16 20:05:36'),(42,'hrms','a1stveggd7p4cfd9vu598ffj5ru0pvae','admin','2017-11-16 20:01:17','2017-11-16 20:01:17','2017-11-16 20:05:36'),(43,'hrms','a1stveggd7p4cfd9vu598ffj5ru0pvae','carlosgblos','2017-11-16 20:04:39','2017-11-16 20:04:39','2017-11-16 20:05:32'),(44,'hrms','a1stveggd7p4cfd9vu598ffj5ru0pvae','admin','2017-11-16 20:05:36','2017-11-16 21:54:03',NULL),(45,'hrms','qaiofdba1mjdpi4qs5tvco455ov8u3j3','admin','2017-11-16 22:27:12','2017-11-16 23:00:07','2017-11-17 16:55:04'),(46,'hrms','qaiofdba1mjdpi4qs5tvco455ov8u3j3','admin','2017-11-17 09:26:00','2017-11-17 11:15:11','2017-11-17 16:55:04'),(47,'hrms','qaiofdba1mjdpi4qs5tvco455ov8u3j3','admin','2017-11-17 13:13:39','2017-11-17 14:09:30','2017-11-17 16:55:04'),(48,'hrms','qaiofdba1mjdpi4qs5tvco455ov8u3j3','admin','2017-11-17 16:55:04','2017-11-17 17:46:45','2017-11-17 19:47:48'),(49,'hrms','0l234luh3o40k605negtf5ht8tccarmf','admin','2017-11-19 09:57:38','2017-11-19 10:16:24','2017-11-20 19:59:34'),(50,'hrms','0l234luh3o40k605negtf5ht8tccarmf','admin','2017-11-19 14:48:48','2017-11-19 16:12:39','2017-11-20 19:59:34'),(51,'hrms','0l234luh3o40k605negtf5ht8tccarmf','admin','2017-11-19 18:57:59','2017-11-19 22:06:04','2017-11-20 19:59:34'),(52,'hrms','0l234luh3o40k605negtf5ht8tccarmf','admin','2017-11-20 09:20:56','2017-11-20 09:20:56','2017-11-20 19:59:34'),(53,'hrms','0l234luh3o40k605negtf5ht8tccarmf','admin','2017-11-20 10:39:06','2017-11-20 11:17:15','2017-11-20 19:59:34'),(54,'hrms','0l234luh3o40k605negtf5ht8tccarmf','admin','2017-11-20 14:28:36','2017-11-20 14:28:36','2017-11-20 19:59:34'),(55,'hrms','0l234luh3o40k605negtf5ht8tccarmf','admin','2017-11-20 16:19:09','2017-11-20 17:23:12','2017-11-20 19:59:34'),(56,'hrms','0l234luh3o40k605negtf5ht8tccarmf','admin','2017-11-20 19:59:34','2017-11-20 21:34:25',NULL),(57,'hrms','en6n919ioi30a5175rdka44d37v8qbih','admin','2017-11-21 09:45:58','2017-11-21 11:31:25','2017-11-22 19:27:54'),(58,'hrms','en6n919ioi30a5175rdka44d37v8qbih','admin','2017-11-21 12:57:43','2017-11-21 17:54:24','2017-11-22 19:27:54'),(59,'hrms','en6n919ioi30a5175rdka44d37v8qbih','admin','2017-11-22 09:52:15','2017-11-22 15:02:53','2017-11-22 19:27:54'),(60,'hrms','en6n919ioi30a5175rdka44d37v8qbih','admin','2017-11-22 16:29:05','2017-11-22 17:17:54','2017-11-22 19:27:54'),(61,'hrms','en6n919ioi30a5175rdka44d37v8qbih','admin','2017-11-22 19:27:54','2017-11-22 19:30:57',NULL),(62,'hrms','dqqepapnmnch2prrqgabktevjnhao2l1','admin','2017-11-23 10:04:03','2017-11-23 10:37:27','2017-11-23 20:37:03'),(63,'hrms','dqqepapnmnch2prrqgabktevjnhao2l1','admin','2017-11-23 14:37:37','2017-11-23 17:45:39','2017-11-23 20:37:03'),(64,'hrms','dqqepapnmnch2prrqgabktevjnhao2l1','admin','2017-11-23 20:37:03','2017-11-23 21:34:02',NULL),(65,'hrms','lf56dvev4659368ftilr864fd07jjgij','admin','2017-11-24 11:55:28','2017-11-24 13:37:52',NULL),(66,'hrms','2e38qn96jgus8d4veg7s8nvv65h5c2cl','admin','2017-11-25 10:06:53','2017-11-25 10:21:05','2017-11-25 16:41:58'),(67,'hrms','2e38qn96jgus8d4veg7s8nvv65h5c2cl','admin','2017-11-25 16:41:58','2017-11-25 20:01:35',NULL),(68,'hrms','vn08kn6fgv89ohgqags43mj9ntsjgrmg','admin','2017-11-26 11:02:03','2017-11-26 11:02:03','2017-11-26 17:29:09'),(69,'hrms','vn08kn6fgv89ohgqags43mj9ntsjgrmg','admin','2017-11-26 17:29:09','2017-11-26 17:29:09',NULL),(70,'hrms','o2etpnp867t6ggu93pslk7ts97nbnp2v','admin','2017-11-29 13:44:57','2017-11-29 13:44:57',NULL),(71,'hrms','ebufhj1qvk99vqd0bisq8r4djqld5f5f','admin','2017-11-29 14:49:53','2017-11-29 14:49:53','2017-11-29 19:48:43'),(72,'hrms','ebufhj1qvk99vqd0bisq8r4djqld5f5f','admin','2017-11-29 19:48:43','2017-11-29 20:52:45',NULL),(73,'hrms','5m8fpuhugh3petbln5g839sk1v0khpg9','admin','2017-12-01 08:26:13','2017-12-01 08:44:03','2017-12-01 14:51:28'),(74,'hrms','5m8fpuhugh3petbln5g839sk1v0khpg9','admin','2017-12-01 14:51:28','2017-12-01 14:55:18',NULL),(75,'hrms','ophuo8hsl3ua9n9qb721v06s8nvceg4b','admin','2017-12-03 19:03:36','2017-12-03 19:27:36','2017-12-04 12:57:56'),(76,'hrms','ophuo8hsl3ua9n9qb721v06s8nvceg4b','admin','2017-12-04 12:57:56','2017-12-04 14:45:50',NULL),(77,'hrms','4sqt84bgodff4qq6mphh04ee1dhmc468','admin','2017-12-04 19:56:45','2017-12-04 21:42:26','2017-12-06 14:47:20'),(78,'hrms','4sqt84bgodff4qq6mphh04ee1dhmc468','admin','2017-12-05 16:16:44','2017-12-05 17:41:02','2017-12-06 14:47:20'),(79,'hrms','4sqt84bgodff4qq6mphh04ee1dhmc468','admin','2017-12-05 19:59:49','2017-12-05 20:31:51','2017-12-06 14:47:20'),(80,'hrms','4sqt84bgodff4qq6mphh04ee1dhmc468','admin','2017-12-06 14:47:20','2017-12-06 14:55:50',NULL),(81,'hrms','sjpun082gfrlajejs7hvuro40jbkt1t2','admin','2017-12-06 21:24:43','2017-12-06 21:27:49','2017-12-07 18:31:44'),(82,'hrms','sjpun082gfrlajejs7hvuro40jbkt1t2','admin','2017-12-07 06:25:57','2017-12-07 07:05:12','2017-12-07 18:31:44'),(83,'hrms','sjpun082gfrlajejs7hvuro40jbkt1t2','admin','2017-12-07 12:18:58','2017-12-07 12:25:27','2017-12-07 18:31:44'),(84,'hrms','sjpun082gfrlajejs7hvuro40jbkt1t2','admin','2017-12-07 18:31:44','2017-12-07 21:41:18',NULL),(85,'hrms','sut51qs3n8s0fivkhln9te19cc7aeq9u','admin','2017-12-09 09:42:05','2017-12-09 09:42:05','2017-12-11 13:05:50'),(86,'hrms','sut51qs3n8s0fivkhln9te19cc7aeq9u','admin','2017-12-11 13:05:50','2017-12-11 13:05:50',NULL),(87,'hrms','5qrfge9r59l386pjco7j1gmb8c2asc0v','admin','2017-12-11 18:17:48','2017-12-11 21:19:07',NULL),(88,'hrms','a2kjs7b0lacir13ou9ln1kap3thkg1lr','admin','2017-12-11 21:25:26','2017-12-11 21:25:26','2017-12-12 13:00:11'),(89,'hrms','a2kjs7b0lacir13ou9ln1kap3thkg1lr','admin','2017-12-12 07:19:57','2017-12-12 07:19:57','2017-12-12 13:00:11'),(90,'hrms','a2kjs7b0lacir13ou9ln1kap3thkg1lr','admin','2017-12-12 13:00:11','2017-12-12 13:17:56',NULL),(91,'hrms','nbe4ht9f8994el5a9mrl2bblp5abp4t3','admin','2017-12-12 20:53:46','2017-12-12 22:22:38','2017-12-13 01:00:16'),(92,'hrms','nbe4ht9f8994el5a9mrl2bblp5abp4t3','admin','2017-12-13 01:00:16','2017-12-13 01:03:25',NULL),(93,'hrms','ig4mivvqtotovj2t6aefvh3lr9kkjuna','admin','2017-12-14 06:15:35','2017-12-14 17:44:38','2017-12-14 20:17:02'),(94,'hrms','ig4mivvqtotovj2t6aefvh3lr9kkjuna','admin','2017-12-14 20:17:02','2017-12-14 20:29:31',NULL),(95,'hrms','ud85nsdebcl4vqoe5eqnrq8sd587f0v1','admin','2017-12-15 10:27:03','2017-12-15 17:49:46',NULL),(96,'hrms','rml41k07cjgug82pfj4lcvoosua85f4i','admin','2017-12-15 20:41:47','2017-12-15 22:09:35','2017-12-19 13:18:53'),(97,'hrms','rml41k07cjgug82pfj4lcvoosua85f4i','admin','2017-12-16 10:19:40','2017-12-16 11:26:16','2017-12-19 13:18:53'),(98,'hrms','rml41k07cjgug82pfj4lcvoosua85f4i','admin','2017-12-16 14:23:44','2017-12-16 16:45:14','2017-12-19 13:18:53'),(99,'hrms','rml41k07cjgug82pfj4lcvoosua85f4i','admin','2017-12-17 02:15:22','2017-12-17 02:52:43','2017-12-19 13:18:53'),(100,'hrms','rml41k07cjgug82pfj4lcvoosua85f4i','admin','2017-12-17 10:43:27','2017-12-17 12:36:53','2017-12-19 13:18:53'),(101,'hrms','rml41k07cjgug82pfj4lcvoosua85f4i','admin','2017-12-18 09:28:44','2017-12-18 15:30:19','2017-12-19 13:18:53'),(102,'hrms','rml41k07cjgug82pfj4lcvoosua85f4i','admin','2017-12-18 20:15:59','2017-12-18 21:44:12','2017-12-19 13:18:53'),(103,'hrms','rml41k07cjgug82pfj4lcvoosua85f4i','admin','2017-12-19 13:18:53','2017-12-19 13:18:53',NULL);
/*!40000 ALTER TABLE `SESSION_LOG` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SESSION_LOG BEFORE INSERT ON SESSION_LOG
FOR EACH ROW
BEGIN
    IF NEW.cd_session_log IS NULL THEN
        SET NEW.cd_session_log = nextval('SESSION_LOG');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYSTEM_COMPANY`
--

DROP TABLE IF EXISTS `SYSTEM_COMPANY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYSTEM_COMPANY` (
  `cd_sys_customer` int(11) NOT NULL,
  `ds_name` longtext NOT NULL,
  `ds_address` longtext,
  `nr_max_connections` int(11) NOT NULL DEFAULT '0',
  `ds_timezone` longtext,
  `ds_factoryontime_id` longtext,
  `ds_inspection_id` longtext,
  `cd_system_product_category_allowed` longtext,
  `ds_company_logo_url` longtext,
  PRIMARY KEY (`cd_sys_customer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYSTEM_COMPANY`
--

LOCK TABLES `SYSTEM_COMPANY` WRITE;
/*!40000 ALTER TABLE `SYSTEM_COMPANY` DISABLE KEYS */;
INSERT INTO `SYSTEM_COMPANY` VALUES (1,'Demo','',5,'Asia/Hong_Kong',NULL,NULL,'{1,2}',NULL);
/*!40000 ALTER TABLE `SYSTEM_COMPANY` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYSTEM_COMPANY BEFORE INSERT ON SYSTEM_COMPANY
FOR EACH ROW
BEGIN
    IF NEW.cd_sys_customer IS NULL THEN
        SET NEW.cd_sys_customer = nextval('SYSTEM_COMPANY');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYSTEM_DASHBOARD_WIDGET`
--

DROP TABLE IF EXISTS `SYSTEM_DASHBOARD_WIDGET`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYSTEM_DASHBOARD_WIDGET` (
  `cd_system_dashboard_widget` int(11) NOT NULL,
  `ds_system_dashboard_widget` varchar(64) DEFAULT NULL,
  `ds_comments` longtext,
  `ds_comments_system` longtext,
  `dt_deactivated` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_system_dashboard_widget`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYSTEM_DASHBOARD_WIDGET`
--

LOCK TABLES `SYSTEM_DASHBOARD_WIDGET` WRITE;
/*!40000 ALTER TABLE `SYSTEM_DASHBOARD_WIDGET` DISABLE KEYS */;
/*!40000 ALTER TABLE `SYSTEM_DASHBOARD_WIDGET` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYSTEM_DASHBOARD_WIDGET BEFORE INSERT ON SYSTEM_DASHBOARD_WIDGET
FOR EACH ROW
BEGIN
    IF NEW.cd_system_dashboard_widget IS NULL THEN
        SET NEW.cd_system_dashboard_widget = nextval('SYSTEM_DASHBOARD_WIDGET');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYSTEM_DB_UPDATES`
--

DROP TABLE IF EXISTS `SYSTEM_DB_UPDATES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYSTEM_DB_UPDATES` (
  `cd_system_db_updates` int(11) NOT NULL,
  `ds_system_db_updates` longtext NOT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_system_db_updates`),
  UNIQUE KEY `IDXSYSTEM_DB_UPDATES001` (`ds_system_db_updates`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYSTEM_DB_UPDATES`
--

LOCK TABLES `SYSTEM_DB_UPDATES` WRITE;
/*!40000 ALTER TABLE `SYSTEM_DB_UPDATES` DISABLE KEYS */;
/*!40000 ALTER TABLE `SYSTEM_DB_UPDATES` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYSTEM_DB_UPDATES BEFORE INSERT ON SYSTEM_DB_UPDATES
FOR EACH ROW
BEGIN
    IF NEW.cd_system_db_updates IS NULL THEN
        SET NEW.cd_system_db_updates = nextval('SYSTEM_DB_UPDATES');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Temporary table structure for view `SYSTEM_DICTIONARY_DEFAULT_VIEW`
--

DROP TABLE IF EXISTS `SYSTEM_DICTIONARY_DEFAULT_VIEW`;
/*!50001 DROP VIEW IF EXISTS `SYSTEM_DICTIONARY_DEFAULT_VIEW`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `SYSTEM_DICTIONARY_DEFAULT_VIEW` AS SELECT 
 1 AS `cd_system_dictionary_main`,
 1 AS `ds_system_dictionary_main`,
 1 AS `cd_system_languages`,
 1 AS `ds_translated`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `SYSTEM_DICTIONARY_MAIN`
--

DROP TABLE IF EXISTS `SYSTEM_DICTIONARY_MAIN`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYSTEM_DICTIONARY_MAIN` (
  `cd_system_dictionary_main` int(11) NOT NULL,
  `ds_system_dictionary_main` varchar(1000) NOT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_system_dictionary_main`),
  UNIQUE KEY `IUNSYSDICTIONARY001` (`ds_system_dictionary_main`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYSTEM_DICTIONARY_MAIN`
--

LOCK TABLES `SYSTEM_DICTIONARY_MAIN` WRITE;
/*!40000 ALTER TABLE `SYSTEM_DICTIONARY_MAIN` DISABLE KEYS */;
INSERT INTO `SYSTEM_DICTIONARY_MAIN` VALUES (1231,'Administration','2017-11-13 17:09:06'),(1232,'Users','2017-11-13 17:09:06'),(1233,'Roles','2017-11-13 17:09:06'),(1234,'Type Users','2017-11-13 17:09:06'),(1235,'Users Maintenance','2017-11-13 17:09:06'),(1236,'Role Maintenance','2017-11-13 17:09:06'),(1237,'Department','2017-11-13 17:09:06'),(1238,'Dictionary','2017-11-13 17:09:06'),(1239,'Tables','2017-11-13 17:09:06'),(1240,'Currency','2017-11-13 17:09:06'),(1241,'Document Repository','2017-11-13 17:09:06'),(1242,'Category','2017-11-13 17:09:06'),(1243,'Type','2017-11-13 17:09:06'),(1244,'Division','2017-11-13 17:09:06'),(1245,'Generate Files','2017-11-13 17:09:06'),(1246,'Currencies','2017-11-13 17:09:06'),(1247,'Currency Rate','2017-11-13 17:09:06'),(1248,'Profile','2017-11-13 17:09:06'),(1249,'Sessions','2017-11-13 17:09:06'),(1250,'Users Permissions','2017-11-13 17:09:06'),(1251,'Roles Permissions','2017-11-13 17:09:06'),(1252,'Control Panel','2017-11-13 17:09:06'),(1253,'City','2017-11-13 17:09:06'),(1254,'Civil Status','2017-11-13 17:09:06'),(1255,'Contact Type','2017-11-13 17:09:06'),(1256,'Document Type','2017-11-13 17:09:06'),(1257,'Education','2017-11-13 17:09:06'),(1258,'Employee Position','2017-11-13 17:09:06'),(1259,'Employee Type','2017-11-13 17:09:06'),(1260,'Gender','2017-11-13 17:09:06'),(1261,'Leave Type','2017-11-13 17:09:06'),(1262,'Relationship Type','2017-11-13 17:09:06'),(1263,'Residence Type','2017-11-13 17:09:06'),(1264,'Employee','2017-11-13 17:09:06'),(1265,'System Language','2017-11-13 17:09:06'),(1266,'English','2017-11-13 17:09:06'),(1267,'Portugues','2017-11-13 17:09:06'),(1268,'Chines','2017-11-13 17:09:06'),(1269,'Auto Hide Filter','2017-11-13 17:09:06'),(1270,'Yes','2017-11-13 17:09:06'),(1271,'No','2017-11-13 17:09:06'),(1272,'Skin','2017-11-13 17:09:06'),(1273,'Blue','2017-11-13 17:09:06'),(1274,'Blue Light','2017-11-13 17:09:06'),(1275,'Yellow','2017-11-13 17:09:06'),(1276,'Yellow Light','2017-11-13 17:09:06'),(1277,'Green','2017-11-13 17:09:06'),(1278,'Green Light','2017-11-13 17:09:06'),(1279,'Purple','2017-11-13 17:09:06'),(1280,'Purple Light','2017-11-13 17:09:06'),(1281,'Red','2017-11-13 17:09:06'),(1282,'Red Light','2017-11-13 17:09:06'),(1283,'Black','2017-11-13 17:09:06'),(1284,'Black Light','2017-11-13 17:09:06'),(1285,'Start on DashBoard','2017-11-13 17:09:06'),(1286,'Debug Mode','2017-11-13 17:09:06'),(1287,'Brand','2017-11-13 17:09:42'),(1288,'Having Division','2017-11-13 17:09:42'),(1289,'Active','2017-11-13 17:09:42'),(1290,'Filter','2017-11-13 17:09:42'),(1291,'Code','2017-11-13 17:09:42'),(1292,'Description','2017-11-13 17:09:42'),(1293,'Short','2017-11-13 17:09:42'),(1294,'Deactivated','2017-11-13 17:09:42'),(1295,'Delete Selected Lines','2017-11-13 17:09:42'),(1296,'Export','2017-11-13 17:09:42'),(1297,'Division Brand','2017-11-13 17:09:42'),(1298,'Brand Related','2017-11-13 17:09:42'),(1299,'Division Related','2017-11-13 17:09:42'),(1300,'Province','2017-11-13 17:09:45'),(1301,'Country','2017-11-13 17:09:45'),(1302,'Number','2017-11-13 17:09:45'),(1303,'A2 (ISO)','2017-11-13 17:09:45'),(1304,'A3 (UN)','2017-11-13 17:09:45'),(1305,'Reference','2017-11-13 19:18:45'),(1306,'First Name','2017-11-13 19:18:45'),(1307,'Surname','2017-11-13 19:18:45'),(1308,'Name Non English','2017-11-13 19:18:45'),(1309,'Position','2017-11-13 19:18:45'),(1310,'Name','2017-11-13 19:18:45'),(1311,'Full Name Non English','2017-11-13 19:18:45'),(1312,'Join','2017-11-13 19:18:45'),(1313,'Nationality','2017-11-13 19:18:45'),(1314,'Birthday','2017-11-13 19:18:45'),(1315,'Birthday Non English','2017-11-13 19:18:45'),(1316,'Note','2017-11-13 19:18:45'),(1317,'Browse','2017-11-13 19:18:45'),(1318,'Personal','2017-11-13 19:18:45'),(1319,'Employee Reference','2017-11-13 19:18:48'),(1320,'Initial Salary','2017-11-13 19:18:48'),(1321,'Current Salary','2017-11-13 19:18:48'),(1322,'Hour Bank','2017-11-13 19:18:48'),(1323,'Full Name','2017-11-13 19:18:48'),(1324,'Non Prof Working Year','2017-11-13 19:18:48'),(1325,'Clear Data','2017-11-13 19:32:01'),(1326,'Open Maintenance','2017-11-13 19:32:01'),(1327,'You must select Filter','2017-11-13 19:32:01'),(1328,'Return Selected','2017-11-13 19:32:01'),(1329,'Non English','2017-11-13 19:47:08'),(1330,'Bday Non English','2017-11-13 19:47:08'),(1331,'NonProf Work Year','2017-11-13 19:47:08'),(1332,'Market Avg Salary','2017-11-13 21:50:34'),(1333,'Job Description','2017-11-13 21:50:34'),(1334,'Login','2017-11-13 22:14:19'),(1335,'Password','2017-11-13 22:14:19'),(1336,'Retype Password','2017-11-13 22:14:19'),(1337,'Retype','2017-11-13 22:19:10'),(1338,'User Name','2017-11-13 22:20:37'),(1339,'E-Mail','2017-11-13 22:20:37'),(1340,'Type User','2017-11-13 22:20:37'),(1341,'Menu Options Maintenance','2017-11-13 22:20:37'),(1342,'User Maintenance','2017-11-13 22:20:37'),(1343,'Menu Permission Maintenance','2017-11-13 22:20:37'),(1344,'New Password','2017-11-13 22:20:39'),(1345,'General','2017-11-13 22:20:39'),(1346,'Login Information','2017-11-13 22:20:39'),(1347,'Password Not Matching!','2017-11-13 22:20:39'),(1348,'Invalid Email Address!','2017-11-13 22:20:39'),(1349,'Size cannot be more than','2017-11-13 22:20:39'),(1350,'Jobs','2017-11-14 15:48:44'),(1351,'Responsible','2017-11-14 15:48:44'),(1352,'Copy','2017-11-14 15:48:44'),(1353,'Merge Permissions','2017-11-14 15:48:44'),(1354,'Human Resource','2017-11-14 15:48:44'),(1355,'Factory','2017-11-14 15:48:44'),(1356,'Customer','2017-11-14 15:48:44'),(1357,'Product Category','2017-11-14 15:48:44'),(1358,'No User selected to copy the rights from','2017-11-14 15:48:44'),(1359,'You must select what you want to paste!','2017-11-14 15:48:44'),(1360,'Confirm paste data from ','2017-11-14 15:48:44'),(1361,'to','2017-11-14 15:48:44'),(1362,'Merging','2017-11-14 15:48:44'),(1363,'X','2017-11-14 15:48:47'),(1364,'Menu Option','2017-11-14 15:48:47'),(1365,'Copy/Merge from Selected','2017-11-14 15:48:47'),(1366,'Please Select an option before copy/merge','2017-11-14 15:48:47'),(1367,'Confirm Copy/Merge from','2017-11-14 15:48:47'),(1368,'Copy/Merge From','2017-11-14 15:48:47'),(1369,'User','2017-11-14 15:48:47'),(1370,'Role','2017-11-14 15:48:47'),(1371,'Merge','2017-11-14 15:48:47'),(1372,'Confirm Copy from ','2017-11-14 15:48:47'),(1373,'Confirm Merge from ','2017-11-14 15:48:47'),(1376,'Address Type','2017-11-14 15:49:13'),(1377,'Order','2017-11-14 16:23:30'),(1378,'Database','2017-11-14 20:17:45'),(1379,'Username','2017-11-14 20:17:45'),(1380,'Logged','2017-11-14 20:17:45'),(1381,'Session','2017-11-14 20:17:45'),(1382,'Last Access','2017-11-14 20:17:45'),(1383,'Expired','2017-11-14 20:17:45'),(1384,'Interval','2017-11-14 20:17:45'),(1385,'Expire selected session','2017-11-14 20:17:45'),(1386,'Address','2017-11-14 22:13:42'),(1387,'Address Additional','2017-11-14 22:13:42'),(1388,'District','2017-11-14 22:13:42'),(1389,'Zip Code','2017-11-14 22:13:42'),(1390,'Additional','2017-11-14 22:18:06'),(1391,'Welcome','2017-11-15 10:47:33'),(1392,'Update Done!','2017-11-15 10:47:33'),(1393,'Just a moment...','2017-11-15 10:47:33'),(1394,'There are information changed. Confirm Retrieve ?','2017-11-15 10:47:33'),(1395,'Confirm','2017-11-15 10:47:33'),(1396,'Loading...','2017-11-15 10:47:33'),(1397,'Alert','2017-11-15 10:47:33'),(1398,'Updating...','2017-11-15 10:47:33'),(1399,'Error Updating:','2017-11-15 10:47:33'),(1400,'Inserting...','2017-11-15 10:47:33'),(1401,'Confirm to Delete selected lines ?','2017-11-15 10:47:33'),(1402,'Deleting...','2017-11-15 10:47:33'),(1403,'Delete Done!','2017-11-15 10:47:33'),(1404,'Error Deleting:','2017-11-15 10:47:33'),(1405,'Insert Line','2017-11-15 10:47:33'),(1406,'Update Information','2017-11-15 10:47:33'),(1407,'Delete','2017-11-15 10:47:33'),(1408,'Close Screen','2017-11-15 10:47:33'),(1409,'There are required information missing! Cannot Save!','2017-11-15 10:47:33'),(1410,'Invalid Date','2017-11-15 10:47:33'),(1411,'There are information changed. Confirm Close ?','2017-11-15 10:47:33'),(1412,'ALL','2017-11-15 10:47:33'),(1413,'CHOOSE','2017-11-15 10:47:33'),(1414,'Operator Options','2017-11-15 10:47:33'),(1415,'Like','2017-11-15 10:47:33'),(1416,'Start With','2017-11-15 10:47:33'),(1417,'Show All','2017-11-15 10:47:33'),(1418,'Show Only Active','2017-11-15 10:47:33'),(1419,'Show Only Deactivated','2017-11-15 10:47:33'),(1420,'Clear','2017-11-15 10:47:33'),(1421,'With','2017-11-15 10:47:33'),(1422,'Without','2017-11-15 10:47:33'),(1423,'None','2017-11-15 10:47:33'),(1424,'Any','2017-11-15 10:47:33'),(1425,'Reload','2017-11-15 10:47:33'),(1426,'Edit Selected Line','2017-11-15 10:47:33'),(1427,'Retriving Data','2017-11-15 10:47:33'),(1428,'Please Save First','2017-11-15 10:47:33'),(1429,'Retrieve Information','2017-11-15 10:47:33'),(1430,'Okay','2017-11-15 10:47:33'),(1431,'Cancel','2017-11-15 10:47:33'),(1432,'Error','2017-11-15 10:47:33'),(1433,'There are information changed. Confirm Action ?','2017-11-15 10:47:33'),(1434,'ON','2017-11-15 10:47:33'),(1435,'OFF','2017-11-15 10:47:33'),(1436,'Toggle Filter','2017-11-15 10:47:33'),(1437,'Cannot Update! There are missing information.','2017-11-15 10:47:33'),(1438,'Please select a Line','2017-11-15 10:47:33'),(1439,'Equal','2017-11-15 10:47:33'),(1440,'Between','2017-11-15 10:47:33'),(1441,'Select All','2017-11-15 10:47:33'),(1442,'Remove Selection','2017-11-15 10:47:33'),(1443,'Default','2017-11-15 10:47:33'),(1444,'Cannot Filter','2017-11-15 10:47:33'),(1445,'Demanded Filter Missing','2017-11-15 10:47:33'),(1446,'Group Filter Missing (selecting one is enough)','2017-11-15 10:47:33'),(1447,'Show/Hide Information','2017-11-15 10:47:33'),(1448,'Preset','2017-11-15 10:47:33'),(1449,'Please inform the Description','2017-11-15 10:47:33'),(1450,'Confirm replace existing information ?','2017-11-15 10:47:33'),(1451,'Hide','2017-11-15 10:47:33'),(1452,'Share','2017-11-15 10:47:33'),(1453,'You must select an User to share','2017-11-15 10:47:33'),(1454,'Select User','2017-11-15 10:47:33'),(1455,'Choose User to Share','2017-11-15 10:47:33'),(1456,'The file %1 is bigger than allowed: <br> Max: %2 <br>File: %3 ','2017-11-15 10:47:33'),(1457,'Required Field','2017-11-15 10:47:33'),(1458,'Home','2017-11-15 10:47:33'),(1459,'Sign Out','2017-11-15 10:47:33'),(1460,'Parameters','2017-11-15 16:28:12'),(1461,'Id','2017-11-15 16:28:12'),(1462,'Obs','2017-11-15 16:28:12'),(1463,'Value','2017-11-15 16:28:12'),(1464,'Language','2017-11-15 16:28:13'),(1465,'Translation Status','2017-11-15 16:28:13'),(1466,'PENDING','2017-11-15 16:28:13'),(1467,'DONE','2017-11-15 16:28:13'),(1468,'Text English','2017-11-15 16:28:13'),(1469,'Translated','2017-11-15 16:28:13'),(1470,'Apply Changes to Everybody','2017-11-15 16:28:13'),(1471,'Password Not Matching','2017-11-16 09:27:19'),(1472,'E Mail','2017-11-16 15:53:28'),(1473,'Phone','2017-11-16 15:53:28'),(1474,'IM','2017-11-16 15:53:28'),(1475,'Document','2017-11-17 10:31:04'),(1476,'Document Number','2017-11-17 10:31:04'),(1477,'Issuer','2017-11-17 10:31:04'),(1478,'Issue','2017-11-17 10:31:04'),(1479,'Expiring Date','2017-11-17 10:31:04'),(1480,'Effective','2017-11-17 10:31:04'),(1481,'Record','2017-11-17 10:31:04'),(1482,'Expiring ','2017-11-17 10:42:00'),(1483,'You must choose Title before upload','2017-11-19 15:04:28'),(1484,'You must choose Type Document before upload','2017-11-19 15:04:28'),(1485,'Edit','2017-11-19 15:04:28'),(1486,'Download','2017-11-19 15:04:28'),(1487,'File Type','2017-11-19 15:04:28'),(1488,'Initializing UI','2017-11-19 15:04:28'),(1489,'Retrieving','2017-11-19 15:04:28'),(1490,'Uploading Files','2017-11-19 15:04:28'),(1491,'File','2017-11-19 15:04:28'),(1492,'Title','2017-11-19 15:04:28'),(1493,'Upload','2017-11-19 15:04:28'),(1494,'Close','2017-11-19 15:04:28'),(1497,'Location','2017-11-19 19:06:10'),(1498,'Dependents','2017-11-20 16:58:47'),(1501,'Bank','2017-11-22 14:37:31'),(1502,'Branch','2017-11-22 14:37:35'),(1503,'Currency Default','2017-11-22 14:37:35'),(1504,'Swift Code','2017-11-22 14:37:35'),(1505,'Bank/Branch','2017-11-22 14:51:05'),(1506,'Role Responsible','2017-11-22 15:01:29'),(1507,'Responsible Role','2017-11-22 15:01:29'),(1508,'Notes','2017-11-22 15:01:29'),(1509,'Undo Record Changes','2017-11-22 15:01:29'),(1510,'System Permission','2017-11-22 15:01:29'),(1511,'Type Permission','2017-11-22 15:01:29'),(1512,'There are changes on User and/or Permission. Continue action (you might lose the changed information) ?','2017-11-22 15:01:29'),(1513,'Retrieving User/Permission information...','2017-11-22 15:01:29'),(1514,'Updating Permission Area.','2017-11-22 15:01:29'),(1517,'Benefit Type','2017-11-22 15:01:41'),(1518,'Bank Branch','2017-11-23 15:00:17'),(1519,'Name On Bank','2017-11-23 15:00:17'),(1520,'Account Number','2017-11-23 15:00:17'),(1521,'Account','2017-11-23 16:15:42'),(1522,'Benefit','2017-11-24 12:07:43'),(1525,'Asset Type','2017-11-29 20:16:25'),(1526,'Asset Description','2017-11-29 20:37:55'),(1527,'Asset Number','2017-11-29 20:37:55'),(1528,'Received','2017-11-29 20:37:55'),(1529,'Returned','2017-11-29 20:37:55'),(1530,'Min','2017-12-03 19:27:47'),(1531,'Max','2017-12-03 19:27:47'),(1532,'Frequency','2017-12-05 16:54:52'),(1533,'Inital Value','2017-12-05 16:54:52'),(1534,'Last Received','2017-12-05 16:54:52'),(1535,'Inital','2017-12-05 20:01:13'),(1536,'Actual','2017-12-05 20:01:13'),(1537,'Current','2017-12-07 20:29:13'),(1538,'Kind','2017-12-11 18:26:56'),(1539,'Unit','2017-12-11 18:26:56'),(1540,'Operation Default','2017-12-11 18:26:56'),(1541,'Kind Default','2017-12-11 18:26:56'),(1542,'Benefit Operation','2017-12-11 20:28:04'),(1543,'Benefit Kind','2017-12-11 20:28:04'),(1544,'Benefit Unit','2017-12-11 20:28:04'),(1545,'Employee X Bank Branch','2017-12-12 22:23:06'),(1546,'Benefit Frequency','2017-12-14 10:44:48'),(1547,'Human Resource Record','2017-12-14 12:06:04'),(1548,'Movements Position','2017-12-14 12:06:04'),(1549,'Comments','2017-12-14 12:06:04'),(1550,'Start At','2017-12-14 12:06:04'),(1551,'Expires At','2017-12-14 12:06:04'),(1552,'Value To Add','2017-12-14 12:06:04'),(1553,'Value Actual','2017-12-14 12:06:04'),(1554,'Operation','2017-12-14 12:07:21'),(1555,'Difference','2017-12-14 12:07:21'),(1556,'Total Current','2017-12-14 12:07:21'),(1557,'By','2017-12-14 12:07:21'),(1558,'Quantity','2017-12-14 13:55:11'),(1559,'#','2017-12-14 14:09:18'),(1560,'Currenct','2017-12-14 16:31:52'),(1561,'Role Permission','2017-12-15 10:34:26'),(1571,'Travel Agency','2017-12-15 10:35:18'),(1572,'Flight Company','2017-12-15 10:35:18'),(1573,'Flight Ticket','2017-12-15 10:35:18'),(1574,'Ticket Number','2017-12-15 10:38:56'),(1575,'Class','2017-12-15 10:38:56'),(1576,'Passanger Name','2017-12-15 10:38:56'),(1577,'Flight Number','2017-12-15 10:38:56'),(1578,'City Departure','2017-12-15 10:38:56'),(1579,'City Arrival','2017-12-15 10:38:56'),(1580,'Remarks','2017-12-15 10:38:56'),(1581,'Movements','2017-12-15 10:38:56'),(1582,'Amount','2017-12-15 10:38:56'),(1583,'Issued Amount','2017-12-15 10:38:56'),(1584,'Departure Date','2017-12-15 10:38:56'),(1585,'Arrival Date','2017-12-15 10:38:56'),(1586,'Payed By','2017-12-15 11:25:24'),(1591,'Expense Type','2017-12-15 16:07:54'),(1592,'Expense','2017-12-15 16:07:54'),(1593,'Employee Approval','2017-12-15 16:09:30'),(1594,'Payment Type','2017-12-15 16:09:30'),(1595,'Submit Date','2017-12-15 16:09:30'),(1596,'Approved','2017-12-15 16:09:30'),(1597,'Approval Date','2017-12-15 16:09:30'),(1598,'Cash Advance','2017-12-15 16:09:30'),(1599,'Has Change Back','2017-12-15 16:09:30'),(1600,'Settlement Date','2017-12-15 16:09:30'),(1601,'Details','2017-12-15 16:09:30'),(1602,'App Date','2017-12-15 16:10:57'),(1603,'Approval','2017-12-15 16:10:57'),(1604,'Approved By','2017-12-15 16:50:20'),(1605,'Settlement','2017-12-15 16:50:53'),(1606,'Expense Item','2017-12-15 17:17:25'),(1607,'Date','2017-12-15 17:22:53'),(1608,'Change Back','2017-12-18 10:25:38'),(1609,'Charge Back','2017-12-18 10:53:44'),(1610,'Rate','2017-12-18 11:15:37'),(1611,'Amount on Rate','2017-12-18 11:25:51'),(1612,'on Rate','2017-12-18 11:26:00'),(1613,'Total Items','2017-12-18 15:15:24'),(1614,'Submit','2017-12-18 15:22:54'),(1615,'Status','2017-12-18 20:30:49'),(1616,'Currency From','2017-12-18 20:58:17'),(1617,'Currency To','2017-12-18 20:58:17'),(1618,'From','2017-12-18 20:58:17'),(1619,'Target Price','2017-12-18 21:24:44'),(1620,'Cost Sheet','2017-12-18 21:24:44');
/*!40000 ALTER TABLE `SYSTEM_DICTIONARY_MAIN` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYSTEM_DICTIONARY_MAIN BEFORE INSERT ON SYSTEM_DICTIONARY_MAIN
FOR EACH ROW
BEGIN
    IF NEW.cd_system_dictionary_main IS NULL THEN
        SET NEW.cd_system_dictionary_main = nextval('SYSTEM_DICTIONARY_MAIN');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYSTEM_DICTIONARY_TRANSLATION`
--

DROP TABLE IF EXISTS `SYSTEM_DICTIONARY_TRANSLATION`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYSTEM_DICTIONARY_TRANSLATION` (
  `cd_system_dictionary_translation` int(11) NOT NULL,
  `ds_system_dictionary_translation` longtext NOT NULL,
  `cd_system_dictionary_main` int(11) NOT NULL,
  `cd_system_languages` int(11) NOT NULL,
  `dt_record` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_system_dictionary_translation`),
  UNIQUE KEY `IUNSYSDICTIONARYTRANS001` (`cd_system_dictionary_main`,`cd_system_languages`),
  KEY `FKSYSDICTTRANS002` (`cd_system_languages`),
  CONSTRAINT `FKSYSDICTTRANS001` FOREIGN KEY (`cd_system_dictionary_main`) REFERENCES `SYSTEM_DICTIONARY_MAIN` (`cd_system_dictionary_main`),
  CONSTRAINT `FKSYSDICTTRANS002` FOREIGN KEY (`cd_system_languages`) REFERENCES `SYSTEM_LANGUAGES` (`cd_system_languages`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYSTEM_DICTIONARY_TRANSLATION`
--

LOCK TABLES `SYSTEM_DICTIONARY_TRANSLATION` WRITE;
/*!40000 ALTER TABLE `SYSTEM_DICTIONARY_TRANSLATION` DISABLE KEYS */;
/*!40000 ALTER TABLE `SYSTEM_DICTIONARY_TRANSLATION` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYSTEM_DICTIONARY_TRANSLATION BEFORE INSERT ON SYSTEM_DICTIONARY_TRANSLATION
FOR EACH ROW
BEGIN
    IF NEW.cd_system_dictionary_translation IS NULL THEN
        SET NEW.cd_system_dictionary_translation = nextval('SYSTEM_DICTIONARY_TRANSLATION');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYSTEM_DICTIONARY_USERDEFINED`
--

DROP TABLE IF EXISTS `SYSTEM_DICTIONARY_USERDEFINED`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYSTEM_DICTIONARY_USERDEFINED` (
  `cd_system_dictionary_userdefined` int(11) NOT NULL,
  `cd_system_dictionary_main` int(11) NOT NULL,
  `cd_system_languages` int(11) DEFAULT NULL,
  `ds_system_dictionary_text` longtext,
  PRIMARY KEY (`cd_system_dictionary_userdefined`),
  UNIQUE KEY `IUNSYSDICTMAINUSER001` (`cd_system_dictionary_main`,`cd_system_languages`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYSTEM_DICTIONARY_USERDEFINED`
--

LOCK TABLES `SYSTEM_DICTIONARY_USERDEFINED` WRITE;
/*!40000 ALTER TABLE `SYSTEM_DICTIONARY_USERDEFINED` DISABLE KEYS */;
/*!40000 ALTER TABLE `SYSTEM_DICTIONARY_USERDEFINED` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYSTEM_DICTIONARY_USERDEFINED BEFORE INSERT ON SYSTEM_DICTIONARY_USERDEFINED
FOR EACH ROW
BEGIN
    IF NEW.cd_system_dictionary_userdefined IS NULL THEN
        SET NEW.cd_system_dictionary_userdefined = nextval('SYSTEM_DICTIONARY_USERDEFINED');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Temporary table structure for view `SYSTEM_DICTIONARY_VIEW`
--

DROP TABLE IF EXISTS `SYSTEM_DICTIONARY_VIEW`;
/*!50001 DROP VIEW IF EXISTS `SYSTEM_DICTIONARY_VIEW`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `SYSTEM_DICTIONARY_VIEW` AS SELECT 
 1 AS `cd_system_dictionary_main`,
 1 AS `ds_system_dictionary_main`,
 1 AS `cd_system_languages`,
 1 AS `ds_translated`,
 1 AS `fl_update_main`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `SYSTEM_LABEL_TYPE`
--

DROP TABLE IF EXISTS `SYSTEM_LABEL_TYPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYSTEM_LABEL_TYPE` (
  `cd_system_label_type` int(11) NOT NULL,
  `ds_system_label_type` varchar(64) NOT NULL,
  `ds_system_identifier` varchar(8) NOT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dt_deactivated` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_system_label_type`),
  UNIQUE KEY `IUNSYSTEM_LABEL_TYPE001` (`ds_system_label_type`),
  UNIQUE KEY `IUNSYSTEM_LABEL_TYPE002` (`ds_system_identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYSTEM_LABEL_TYPE`
--

LOCK TABLES `SYSTEM_LABEL_TYPE` WRITE;
/*!40000 ALTER TABLE `SYSTEM_LABEL_TYPE` DISABLE KEYS */;
INSERT INTO `SYSTEM_LABEL_TYPE` VALUES (1,'INLINE','I','2016-02-07 16:57:39','2016-02-16 16:14:22'),(2,'TOP','T','2016-02-07 16:57:44',NULL),(3,'HIDDEN','H','2016-02-07 16:57:49',NULL);
/*!40000 ALTER TABLE `SYSTEM_LABEL_TYPE` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYSTEM_LABEL_TYPE BEFORE INSERT ON SYSTEM_LABEL_TYPE
FOR EACH ROW
BEGIN
    IF NEW.cd_system_label_type IS NULL THEN
        SET NEW.cd_system_label_type = nextval('SYSTEM_LABEL_TYPE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYSTEM_LANGUAGES`
--

DROP TABLE IF EXISTS `SYSTEM_LANGUAGES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYSTEM_LANGUAGES` (
  `cd_system_languages` int(11) NOT NULL,
  `ds_system_language` varchar(64) DEFAULT NULL,
  `ds_system_language_code` varchar(10) DEFAULT NULL,
  `fl_default` char(1) NOT NULL DEFAULT 'N',
  `dt_deactivated` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_system_languages`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYSTEM_LANGUAGES`
--

LOCK TABLES `SYSTEM_LANGUAGES` WRITE;
/*!40000 ALTER TABLE `SYSTEM_LANGUAGES` DISABLE KEYS */;
INSERT INTO `SYSTEM_LANGUAGES` VALUES (1,'English','en','Y',NULL),(2,'Português do Brasil','pt-br','N',NULL),(3,'Chines','cn','N',NULL);
/*!40000 ALTER TABLE `SYSTEM_LANGUAGES` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYSTEM_LANGUAGES BEFORE INSERT ON SYSTEM_LANGUAGES
FOR EACH ROW
BEGIN
    IF NEW.cd_system_languages IS NULL THEN
        SET NEW.cd_system_languages = nextval('SYSTEM_LANGUAGES');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYSTEM_MENU`
--

DROP TABLE IF EXISTS `SYSTEM_MENU`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYSTEM_MENU` (
  `cd_system_menu` int(11) NOT NULL,
  `ds_system_menu` varchar(32) NOT NULL,
  `ds_controller` varchar(128) NOT NULL,
  `cd_system_menu_parent` int(11) DEFAULT NULL,
  `dt_deactivated` date DEFAULT NULL,
  `dt_record` datetime DEFAULT NULL,
  `nr_order` int(11) DEFAULT NULL,
  `ds_image` varchar(255) DEFAULT NULL,
  `fl_always_available` char(1) NOT NULL DEFAULT 'N',
  `fl_visible` char(1) NOT NULL DEFAULT 'Y',
  `fl_only_for_super_users` char(1) NOT NULL DEFAULT 'N',
  `cds_system_product_category_allowed` longtext,
  PRIMARY KEY (`cd_system_menu`),
  KEY `fki_FKSYSTEM_MENU001` (`cd_system_menu_parent`),
  CONSTRAINT `FKSYSTEM_MENU001` FOREIGN KEY (`cd_system_menu_parent`) REFERENCES `SYSTEM_MENU` (`cd_system_menu`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYSTEM_MENU`
--

LOCK TABLES `SYSTEM_MENU` WRITE;
/*!40000 ALTER TABLE `SYSTEM_MENU` DISABLE KEYS */;
INSERT INTO `SYSTEM_MENU` VALUES (1,'Administration','#',NULL,NULL,'2014-08-30 13:11:43',10,'<i class=\"fa fa-gears\"></i>','N','Y','N',NULL),(2,'Users','#',1,NULL,'2014-05-18 12:32:04',1,'<i class=\"fa fa-users\"></i>','N','Y','N',NULL),(3,'Roles','#',1,NULL,'2014-05-18 12:33:35',2,'<i class=\"fa fa-briefcase\"></i>\n','N','Y','N',NULL),(8,'Type Users','type_users_maint',2,NULL,'2014-05-20 16:27:36',4,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(9,'Users Maintenance','users_maint',2,NULL,'2014-05-20 16:27:56',1,'<i class=\"fa fa-external-link-square\"></i>','N','Y','Y',NULL),(10,'Role Maintenance','jobs_maint',3,NULL,'2014-05-20 16:42:31',1,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(11,'Department','job_department',3,NULL,'2014-05-20 16:42:59',4,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(12,'Dictionary','dictionary',1,NULL,'2014-07-28 13:40:22',999999,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(14,'Tables','#',NULL,NULL,'2014-08-30 13:11:43',20,'<i class=\"fa fa-table\"></i>','N','Y','N',NULL),(16,'Currency','currency',58,NULL,'2014-08-30 17:49:12',1,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(33,'Document Repository','#',1,NULL,'2014-11-21 15:39:06',300,'<i class=\"fa fa-file-archive-o\"></i>','N','Y','Y',NULL),(34,'Category','docrep/document_repository_category',33,NULL,'2014-11-21 15:39:58',10,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(35,'Type','docrep/document_repository_type',33,NULL,'2014-11-21 15:58:25',5,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(37,'Division','division_full',14,NULL,'2014-12-22 17:02:46',1,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(50,'Generate Files','generator_tabajara_mysql',1,NULL,'2015-06-19 14:12:40',500,'<i class=\"fa fa-external-link-square\"></i>','N','Y','Y',NULL),(58,'Currencies','#',14,NULL,'2015-12-01 16:25:40',200,'<i class=\"fa fa-money\"></i>','N','Y','N',NULL),(59,'Currency Rate','currency_rate',58,NULL,'2015-12-03 12:56:04',2,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(61,'Profile','users_maint/profile',NULL,NULL,'2016-02-01 15:37:55',999,'<i class=\"fa fa-external-link-square\"></i>','N','N','N',NULL),(112,'Sessions','session_log',1,NULL,'2016-11-14 13:50:52',8,'<i class=\"fa fa-external-link-square\"></i>','N','Y','Y',NULL),(115,'Users Permissions','users_maint/openRightsScreen',2,NULL,'2016-12-12 22:39:11',2,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(116,'Roles Permissions','jobs_maint/openRightsScreen',3,NULL,'2016-12-16 14:41:20',2,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(127,'Control Panel','control_panel',1,NULL,'2017-05-14 15:37:02',0,'<i class=\"fa fa-gears\"></i>','N','Y','Y',NULL),(1002,'City','hrms/city',14,NULL,'2017-11-05 16:21:39',100,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1004,'Civil Status','hrms/civil_status',14,NULL,'2017-11-05 18:57:40',100,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1005,'Contact Type','hrms/contact_type',14,NULL,'2017-11-05 19:03:03',100,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1006,'Document Type','hrms/document_type',14,NULL,'2017-11-05 19:46:10',100,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1007,'Education','hrms/education',14,NULL,'2017-11-05 20:14:07',100,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1008,'Employee Position','hrms/employee_position',14,NULL,'2017-11-05 22:26:56',100,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1009,'Employee Type','hrms/employee_type',14,NULL,'2017-11-05 22:40:33',100,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1010,'Gender','hrms/gender',14,NULL,'2017-11-05 22:45:36',100,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1011,'Leave Type','hrms/leave_type',14,NULL,'2017-11-05 22:47:41',100,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1012,'Relationship Type','hrms/relationship_type',14,NULL,'2017-11-05 22:50:00',100,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1013,'Residence Type','hrms/residence_type',14,NULL,'2017-11-05 22:52:35',100,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1014,'Employee','hrms/employee',14,NULL,'2017-11-06 19:38:34',100,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1015,'Address Type','hrms/address_type',14,NULL,NULL,1,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1016,'Location','hrms/location',14,NULL,NULL,100,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1017,'Bank/Branch','hrms/bank',14,NULL,NULL,1,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1018,'Benefit Type','hrms/benefit_type',14,NULL,NULL,100,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1019,'Asset Type','hrms/asset_type',14,NULL,NULL,100,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1021,'Travel Agency','hrms/travel_agency',14,NULL,NULL,100,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1022,'Flight Company','hrms/flight_company',14,NULL,NULL,100,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1023,'Flight Ticket','hrms/flight_ticket',14,NULL,NULL,100,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1024,'Expense Type','hrms/expense_type',14,NULL,NULL,100,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL),(1025,'Expense','hrms/expense',14,NULL,NULL,100,'<i class=\"fa fa-external-link-square\"></i>','N','Y','N',NULL);
/*!40000 ALTER TABLE `SYSTEM_MENU` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYSTEM_MENU BEFORE INSERT ON SYSTEM_MENU
FOR EACH ROW
BEGIN
    IF NEW.cd_system_menu IS NULL THEN
        SET NEW.cd_system_menu = nextval('SYSTEM_MENU');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYSTEM_PARAMETERS`
--

DROP TABLE IF EXISTS `SYSTEM_PARAMETERS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYSTEM_PARAMETERS` (
  `cd_system_parameters` int(11) NOT NULL,
  `ds_system_parameters` varchar(64) NOT NULL,
  `ds_system_parameters_id` varchar(32) NOT NULL,
  `ds_system_parameters_obs` varchar(255) DEFAULT NULL,
  `ds_system_parameters_value` varchar(64) NOT NULL,
  PRIMARY KEY (`cd_system_parameters`),
  UNIQUE KEY `IUNSYSTEM_PARAMETER001` (`ds_system_parameters`),
  UNIQUE KEY `UNSYSTEM_PARAMETERS001` (`ds_system_parameters_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYSTEM_PARAMETERS`
--

LOCK TABLES `SYSTEM_PARAMETERS` WRITE;
/*!40000 ALTER TABLE `SYSTEM_PARAMETERS` DISABLE KEYS */;
INSERT INTO `SYSTEM_PARAMETERS` VALUES (7,'English Language','SYSTEM_LANGUAGE_ENGLISH_CODE','Code for English Language on SYSTEM LANGUAGE','1'),(8,'IP Considered Local','LOCAL_IP','IP that will be considered Local (OFFICE) by system','172.16.47.1'),(14,'TEMP_PATH_SAVE_REPORTS','TEMP_PATH_SAVE_REPORTS','Where The Reports will be Saved','/var/www/hrms/mboardReports/tmp/'),(28,'Path to Save Users Pictures','PATH_USER_PICTURES','','/var/www/hrms/document_repository/hrms/userImage/'),(30,'Full App Path','FULL_RESOURCE_PATH','','/var/www/hrms/htmldev/resources/'),(31,'Temp Path','TEMP_PATH','','/tmp/'),(32,'Allow Change User Profile','USER_PROFILE_CHANGEABLE','','Y'),(39,'System Abbreviation Name','SYSTEM_ABRREV_NAME','','D<strong>S</strong>'),(42,'System Full Name','SYSTEM_FULL_NAME','','DevShoes DVLP');
/*!40000 ALTER TABLE `SYSTEM_PARAMETERS` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYSTEM_PARAMETERS BEFORE INSERT ON SYSTEM_PARAMETERS
FOR EACH ROW
BEGIN
    IF NEW.cd_system_parameters IS NULL THEN
        SET NEW.cd_system_parameters = nextval('SYSTEM_PARAMETERS');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYSTEM_PERMISSION`
--

DROP TABLE IF EXISTS `SYSTEM_PERMISSION`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYSTEM_PERMISSION` (
  `cd_system_permission` int(11) NOT NULL,
  `ds_system_permission` varchar(64) NOT NULL,
  `ds_system_permission_id` varchar(64) NOT NULL,
  `ds_system_parameter_obs` varchar(64) DEFAULT NULL,
  `cd_type_sys_permission` int(11) NOT NULL,
  PRIMARY KEY (`cd_system_permission`),
  UNIQUE KEY `UNSYSTEM_PERMISSION01` (`ds_system_permission`),
  UNIQUE KEY `UNSYSTEM_PERMISSION02` (`ds_system_permission_id`),
  KEY `FKSYSTEM_PERMISSION01` (`cd_type_sys_permission`),
  CONSTRAINT `FKSYSTEM_PERMISSION01` FOREIGN KEY (`cd_type_sys_permission`) REFERENCES `TYPE_SYS_PERMISSION` (`cd_type_sys_permission`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYSTEM_PERMISSION`
--

LOCK TABLES `SYSTEM_PERMISSION` WRITE;
/*!40000 ALTER TABLE `SYSTEM_PERMISSION` DISABLE KEYS */;
INSERT INTO `SYSTEM_PERMISSION` VALUES (1,'ALLOW UPDATE SOMETHING','fl_allow_change_smt','Allow user to update something',1),(2,'ALLOW SEND SPECIAL DOCUMENT REPOSITORY','fl_allow_special_docrep','Allow to Send Special Images',1),(4,'ALLOW CONNECT REMOTELY','fl_allow_connect_remotely','Allow to Connect from Outside the Office',1),(5,'DESIGNER','fl_designer','User is a Designer',2),(6,'ALLOW PRICE MAINTENANCE','fl_allow_price','Allow to Update/Maintain Price',1),(7,'ALLOW EDIT GROUP AND COMPONENT','fl_allow_edit_group_component','Allow Edit Group and Component',3);
/*!40000 ALTER TABLE `SYSTEM_PERMISSION` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYSTEM_PERMISSION BEFORE INSERT ON SYSTEM_PERMISSION
FOR EACH ROW
BEGIN
    IF NEW.cd_system_permission IS NULL THEN
        SET NEW.cd_system_permission = nextval('SYSTEM_PERMISSION');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYSTEM_PRODUCT_CATEGORY`
--

DROP TABLE IF EXISTS `SYSTEM_PRODUCT_CATEGORY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYSTEM_PRODUCT_CATEGORY` (
  `cd_system_product_category` int(11) NOT NULL,
  `ds_system_product_category` longtext NOT NULL,
  `ds_icon` longtext,
  `nr_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cd_system_product_category`),
  UNIQUE KEY `IUNSYSTEM_PRODUCT_CATEGORY001` (`ds_system_product_category`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYSTEM_PRODUCT_CATEGORY`
--

LOCK TABLES `SYSTEM_PRODUCT_CATEGORY` WRITE;
/*!40000 ALTER TABLE `SYSTEM_PRODUCT_CATEGORY` DISABLE KEYS */;
INSERT INTO `SYSTEM_PRODUCT_CATEGORY` VALUES (1,'HRMS',NULL,0);
/*!40000 ALTER TABLE `SYSTEM_PRODUCT_CATEGORY` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYSTEM_PRODUCT_CATEGORY BEFORE INSERT ON SYSTEM_PRODUCT_CATEGORY
FOR EACH ROW
BEGIN
    IF NEW.cd_system_product_category IS NULL THEN
        SET NEW.cd_system_product_category = nextval('SYSTEM_PRODUCT_CATEGORY');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Temporary table structure for view `SYSTEM_PRODUCT_CATEGORY_VIEW`
--

DROP TABLE IF EXISTS `SYSTEM_PRODUCT_CATEGORY_VIEW`;
/*!50001 DROP VIEW IF EXISTS `SYSTEM_PRODUCT_CATEGORY_VIEW`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `SYSTEM_PRODUCT_CATEGORY_VIEW` AS SELECT 
 1 AS `cd_system_product_category`,
 1 AS `ds_system_product_category`,
 1 AS `ds_icon`,
 1 AS `nr_order`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `SYSTEM_RELATIONS`
--

DROP TABLE IF EXISTS `SYSTEM_RELATIONS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYSTEM_RELATIONS` (
  `cd_system_relation` int(11) NOT NULL,
  `ds_table_name` varchar(64) NOT NULL,
  `ds_column_name` varchar(64) NOT NULL,
  `ds_foreign_table_name` varchar(64) NOT NULL,
  `ds_foreign_column_name` varchar(64) DEFAULT NULL,
  `ds_foreign_desc_column_name` varchar(64) NOT NULL,
  PRIMARY KEY (`cd_system_relation`),
  UNIQUE KEY `UINSYSTEM_RELATIONS001` (`ds_table_name`,`ds_column_name`),
  KEY `IUNSYSTEM_RELATION002` (`ds_table_name`,`ds_column_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYSTEM_RELATIONS`
--

LOCK TABLES `SYSTEM_RELATIONS` WRITE;
/*!40000 ALTER TABLE `SYSTEM_RELATIONS` DISABLE KEYS */;
INSERT INTO `SYSTEM_RELATIONS` VALUES (4,'JOBS_HUMAN_RESOURCE','cd_human_resource','HUMAN_RESOURCE','cd_human_resource','ds_human_resource_full'),(5,'JOBS_HUMAN_RESOURCE','cd_jobs','JOBS','cd_jobs','ds_jobs'),(6,'JOBS_MENU','cd_jobs','JOBS','cd_jobs','ds_jobs'),(7,'HR_SYSTEM_PARAMETERS','cd_human_resource','HUMAN_RESOURCE','cd_human_resource','ds_human_resource_full'),(8,'JOBS_SYSTEM_PERMISSION','cd_jobs','JOBS','cd_jobs','ds_jobs'),(9,'HUMAN_RESOURCE_MENU','cd_human_resource','HUMAN_RESOURCE','cd_human_resource','ds_human_resource_full'),(10,'HUMAN_RESOURCE','cd_hr_type','HR_TYPE','cd_hr_type','ds_hr_type'),(11,'JOBS','cd_department','DEPARTMENT','cd_department','ds_department'),(12,'JOBS','cd_jobs_responsible','JOBS','cd_jobs','ds_jobs'),(14,'HR_SYSTEM_SETTINGS_OPTIONS','cd_system_settings','SYSTEM_SETTINGS','cd_system_settings','ds_system_settings'),(15,'SYSTEM_SETTINGS_OPTIONS','cd_system_settings','SYSTEM_SETTINGS','cd_system_settings','ds_system_settings'),(16,'PRODUCT_TYPE_X_PRODUCT_SUB_TYPE','cd_product_type','PRODUCT_TYPE','cd_product_type','ds_product_type'),(17,'PRODUCT_GROUP_X_PRODUCT_TYPE','cd_product_type','PRODUCT_TYPE','cd_product_type','ds_product_type'),(18,'PRODUCT_GROUP_X_PRODUCT_TYPE','cd_product_group','PRODUCT_GROUP','cd_product_group','ds_product_group'),(19,'PRODUCT_GROUP_X_PRODUCT_COMPONENT','cd_product_component','PRODUCT_COMPONENT','cd_product_component','ds_product_component'),(20,'PRODUCT_GROUP_X_PRODUCT_COMPONENT','cd_product_group','PRODUCT_GROUP','cd_product_group','ds_product_group'),(21,'PRODUCT_GROUP','cd_product_supplier_details','PRODUCT_SUPPLIER_DETAILS','cd_product_supplier_details','ds_product_supplier_details'),(22,'PRODUCT_TYPE_X_PRODUCT_SUB_TYPE','cd_product_sub_type','PRODUCT_SUB_TYPE','cd_product_sub_type','ds_product_sub_type'),(24,'UNIT_MEASURE','cd_unit_measure_lenght_base','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(25,'UNIT_MEASURE','cd_unit_measure_type','UNIT_MEASURE_TYPE','cd_unit_measure_type','ds_unit_measure_type'),(26,'CURRENCY_FULL','cd_country','COUNTRY','cd_country','ds_country'),(30,'UNIT_MEASURE_TYPE','cd_unit_measure_reference','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(31,'PRODUCT_X_ATTRIBUTE_ITEMS','cd_product','PRODUCT','cd_product','ds_product'),(32,'PRODUCT_X_PRODUCT_TAGS','cd_product_tags','PRODUCT_TAGS','cd_product_tags','ds_product_tags'),(33,'PRODUCT_X_PRODUCT_TAGS','cd_product','PRODUCT','cd_product','ds_product'),(34,'PRODUCT_X_PRODUCT_COMPONENT','cd_product_component','PRODUCT_COMPONENT','cd_product_component','ds_product_component'),(35,'PRODUCT_X_PRODUCT_COMPONENT','cd_product','PRODUCT','cd_product','ds_product'),(36,'PRODUCT','cd_product_sub_type','PRODUCT_SUB_TYPE','cd_product_sub_type','ds_product_sub_type'),(37,'PRODUCT','cd_product_type','PRODUCT_TYPE','cd_product_type','ds_product_type'),(38,'PRODUCT_TYPE_X_PRODUCT_ATTRIBUTES','cd_product_attributes','PRODUCT_ATTRIBUTES','cd_product_attributes','ds_product_attributes'),(39,'PRODUCT_TYPE_X_PRODUCT_ATTRIBUTES','cd_product_type','PRODUCT_TYPE','cd_product_type','ds_product_type'),(40,'PRODUCT_GROUP_X_PRODUCT_ATTRIBUTES','cd_product_attributes','PRODUCT_ATTRIBUTES','cd_product_attributes','ds_product_attributes'),(41,'PRODUCT_GROUP_X_PRODUCT_ATTRIBUTES','cd_product_group','PRODUCT_GROUP','cd_product_group','ds_product_group'),(42,'PRODUCT_ATTRIBUTES_X_ITEMS','cd_product_attribute_items','PRODUCT_ATTRIBUTE_ITEMS','cd_product_attribute_items','ds_product_attribute_items'),(43,'PRODUCT_ATTRIBUTES_X_ITEMS','cd_product_attributes','PRODUCT_ATTRIBUTES','cd_product_attributes','ds_product_attributes'),(44,'PRODUCT_QUOTATION_GENERIC','cd_country','COUNTRY','cd_country','ds_country'),(45,'PRODUCT_QUOTATION_GENERIC','cd_unit_measure_width_min','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(46,'PRODUCT_QUOTATION_GENERIC','cd_unit_measure_length_min','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(47,'PRODUCT_QUOTATION_GENERIC','cd_unit_measure_width','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(48,'PRODUCT_QUOTATION_GENERIC','cd_unit_measure_length','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(49,'PRODUCT_QUOTATION_GENERIC','cd_product','PRODUCT','cd_product','ds_product'),(50,'PRODUCT_QUOTATION_GENERIC','cd_supplier','SUPPLIER','cd_supplier','ds_supplier'),(51,'SUPPLIER_CONTACT','cd_supplier','SUPPLIER','cd_supplier','ds_supplier'),(52,'SUPPLIER','cd_country','COUNTRY','cd_country','ds_country'),(53,'SUPPLIER_X_SUPPLIER_TAGS','cd_supplier_tags','SUPPLIER_TAGS','cd_supplier_tags','ds_supplier_tags'),(54,'SUPPLIER_X_SUPPLIER_TAGS','cd_supplier','SUPPLIER','cd_supplier','ds_supplier'),(55,'SUPPLIER','cd_supplier_rank','SUPPLIER_RANK','cd_supplier_rank','ds_supplier_rank'),(56,'PRODUCT','cd_product_group','PRODUCT_GROUP','cd_product_group','ds_product_group'),(57,'PRODUCT_QUOTATION_GENERIC','cd_currency_freight','CURRENCY','cd_currency','ds_currency'),(58,'PRODUCT_QUOTATION_GENERIC','cd_currency','CURRENCY','cd_currency','ds_currency'),(59,'PRODUCT_QUOTATION_GENERIC','cd_payment_term','PAYMENT_TERMS','cd_payment_terms','ds_payment_terms'),(60,'PRODUCT_QUOTATION_GENERIC','cd_payment_terms','PAYMENT_TERMS','cd_payment_terms','ds_payment_terms'),(61,'PRODUCT_DOCUMENT_REPOSITORY','cd_product','PRODUCT','cd_product','ds_product'),(62,'SUPPLIER_DOCUMENT_REPOSITORY','cd_supplier','SUPPLIER','cd_supplier','ds_supplier'),(63,'DOCUMENT_REPOSITORY','cd_document_file','DOCUMENT_FILE','cd_document_file','ds_document_file_hash'),(64,'DOCUMENT_REPOSITORY_TYPE','cd_document_repository_category','DOCUMENT_REPOSITORY_CATEGORY','cd_document_repository_category','ds_document_repository_category'),(65,'DOCUMENT_REPOSITORY','cd_document_repository_type','DOCUMENT_REPOSITORY_TYPE','cd_document_repository_type','ds_document_repository_type'),(66,'CUSTOMER','cd_customer_group','CUSTOMER_GROUP','cd_customer_group','ds_customer_group'),(67,'CUSTOMER','cd_country','COUNTRY','cd_country','ds_country'),(68,'DIVISION_X_DIVISION_BRAND','cd_division_brand','DIVISION_BRAND','cd_division_brand','ds_division_brand'),(69,'DIVISION_X_DIVISION_BRAND','cd_division','DIVISION','cd_division','ds_division'),(70,'CUSTOMER_GROUP','cd_division','DIVISION','cd_division','ds_division'),(71,'COLOR','cd_color_group','COLOR_GROUP','cd_color_group','ds_color_group'),(72,'PRODUCT_SUB_TYPE_X_PRODUCT_ATTRIBUTES','cd_product_sub_type','PRODUCT_SUB_TYPE','cd_product_sub_type','ds_product_sub_type'),(73,'PRODUCT_SUB_TYPE_X_PRODUCT_ATTRIBUTES','cd_product_attributes','PRODUCT_ATTRIBUTES','cd_product_attributes','ds_product_attributes'),(74,'CONSTRUCTION','cd_product_sole_material','PRODUCT','cd_product','ds_product'),(75,'CONSTRUCTION','cd_product_mid_sole','PRODUCT','cd_product','ds_product'),(76,'CONSTRUCTION','cd_product_welt','PRODUCT','cd_product','ds_product'),(77,'CONSTRUCTION','cd_product_heel','PRODUCT','cd_product','ds_product'),(78,'CONSTRUCTION','cd_sole_edge_shape','SOLE_EDGE_SHAPE','cd_sole_edge_shape','ds_sole_edge_shape'),(79,'CONSTRUCTION','cd_type_gender','TYPE_GENDER','cd_type_gender','ds_type_gender'),(80,'CONSTRUCTION_X_LAST','cd_last','LAST','cd_last','ds_last_description'),(81,'LAST','cd_last_type','LAST_TYPE','cd_last_type','ds_last_type'),(82,'PRODUCT_COMPONENT_X_PRODUCT_ATTRIBUTES','cd_product_component','PRODUCT_COMPONENT','cd_product_component','ds_product_component'),(83,'PRODUCT_COMPONENT_X_PRODUCT_ATTRIBUTES','cd_product_attributes','PRODUCT_ATTRIBUTES','cd_product_attributes','ds_product_attributes'),(84,'PRODUCT_COMPOSITION','cd_product_compound','PRODUCT','cd_product','ds_product'),(85,'PRODUCT_COMPOSITION','cd_product','PRODUCT','cd_product','ds_product'),(86,'GENERIC_SHOE_SPECIFICATION','cd_generic_shoe_specification_master','GENERIC_SHOE_SPECIFICATION','cd_generic_shoe_specification','ds_generic_shoe_specification'),(87,'SHOE_SPECIFICATION','cd_generic_shoe_specification','GENERIC_SHOE_SPECIFICATION','cd_generic_shoe_specification','ds_generic_shoe_specification'),(88,'SHOE_SPECIFICATION','cd_product_counter_pocket','PRODUCT','cd_product','ds_product'),(89,'SHOE_SPECIFICATION','cd_product_foam','PRODUCT','cd_product','ds_product'),(90,'SHOE_SPECIFICATION','cd_product_toe_piece','PRODUCT','cd_product','ds_product'),(91,'SHOE_SPECIFICATION','cd_product_toe_box','PRODUCT','cd_product','ds_product'),(92,'SHOE_SPECIFICATION','cd_product_top_lift','PRODUCT','cd_product','ds_product'),(93,'SHOE_SPECIFICATION','cd_product_ornament','PRODUCT','cd_product','ds_product'),(94,'SHOE_SPECIFICATION','cd_product_type_upper','PRODUCT_TYPE','cd_product_type','ds_product_type'),(95,'SHOE_SPECIFICATION','cd_product_type_sock_lining','PRODUCT_TYPE','cd_product_type','ds_product_type'),(96,'SHOE_SPECIFICATION','cd_product_type_lining_material','PRODUCT_TYPE','cd_product_type','ds_product_type'),(97,'SHOE_SPECIFICATION','cd_last','LAST','cd_last','ds_last_description'),(98,'SYSTEM_SETTINGS','cd_system_settings_group','SYSTEM_SETTINGS_GROUP','cd_system_settings_group','ds_system_settings_group'),(99,'GEN_SHOE_SPEC_DOCUMENT_REPOSITORY','cd_generic_shoe_specification','GENERIC_SHOE_SPECIFICATION','cd_generic_shoe_specification','ds_generic_shoe_specification'),(100,'GEN_SHOE_SPEC_DOCUMENT_REPOSITORY','cd_spec_picture_type','SPEC_PICTURE_TYPE','cd_spec_picture_type','ds_spec_picture_type'),(101,'CURRENCY_RATE','cd_currency_from','CURRENCY','cd_currency','ds_currency'),(102,'CURRENCY_RATE','cd_currency_to','CURRENCY','cd_currency','ds_currency'),(103,'CONSTRUCTION','cd_construction_build','CONSTRUCTION_BUILD','cd_construction_build','ds_construction'),(104,'SHOE_LEVEL_X_PRODUCT_COMPONENT','cd_product_component','PRODUCT_COMPONENT','cd_product_component','ds_product_component'),(105,'SHOE_LEVEL_X_PRODUCT_COMPONENT','cd_shoe_level','SHOE_LEVEL','cd_shoe_level','ds_shoe_level'),(106,'SHOE_COMPONENT_ATTRIBUTES_X_ITEMS','cd_shoe_component_attributes','SHOE_COMPONENT_ATTRIBUTES','cd_shoe_component_attributes','ds_shoe_component_attributes'),(107,'SHOE_COMPONENT_ATTRIBUTES_X_ITEMS','cd_shoe_component_attribute_items','SHOE_COMPONENT_ATTRIBUTE_ITEMS','cd_shoe_component_attribute_items','ds_shoe_component_attribute_items'),(108,'SHOE_LEVEL_X_PRODUCT_COMPONENT','cd_product_group','PRODUCT_GROUP','cd_product_group','ds_product_group'),(109,'SHOE_LEVEL_PRD_COMPONENT_X_SHOE_ATTRIBUTES','cd_shoe_component_attributes','SHOE_COMPONENT_ATTRIBUTES','cd_shoe_component_attributes','ds_shoe_component_attributes'),(110,'CONSTRUCTION_COMPONENT_ATTRIBUTES','cd_shoe_component_attribute_items','SHOE_COMPONENT_ATTRIBUTE_ITEMS','cd_shoe_component_attribute_items','ds_shoe_component_attribute_items'),(111,'CONSTRUCTION_COMPONENT','cd_color','COLOR','cd_color','ds_color'),(112,'CONSTRUCTION_COMPONENT','cd_product','PRODUCT','cd_product','ds_product'),(113,'SHOE_SPECIFICATION_COMPONENT','cd_color','COLOR','cd_color','ds_color'),(114,'SHOE_SPECIFICATION_COMPONENT','cd_product','PRODUCT','cd_product','ds_product'),(115,'SHOE_SPECIFICATION_COMPONENT_ATTRIBUTES','cd_shoe_component_attribute_items','SHOE_COMPONENT_ATTRIBUTE_ITEMS','cd_shoe_component_attribute_items','ds_shoe_component_attribute_items'),(116,'SHOE_LEVEL_X_PRODUCT_COMPONENT','cd_shoe_level_inherit','SHOE_LEVEL','cd_shoe_level','ds_shoe_level'),(117,'PRODUCT_TYPE','cd_product_component_main','PRODUCT_COMPONENT','cd_product_component','ds_product_component'),(118,'PRODUCT_GROUP','cd_product_component_main','PRODUCT_COMPONENT','cd_product_component','ds_product_component'),(119,'SHOE_SKU','cd_color_stitch_upper','COLOR','cd_color','ds_color'),(120,'SHOE_SKU','cd_ifi','IFI','cd_ifi','ds_ifi'),(121,'SHOE_SKU','cd_color_stitch_sock','COLOR','cd_color','ds_color'),(122,'SHOE_SKU','cd_color_stitch_sole','COLOR','cd_color','ds_color'),(123,'SHOE_SKU','cd_color_sole_edge','COLOR','cd_color','ds_color'),(124,'SHOE_SKU_COMPONENT','cd_shoe_sku','SHOE_SKU','cd_shoe_sku','ds_shoe_sku'),(125,'SHOE_SKU_COMPONENT','cd_color','COLOR','cd_color','ds_color'),(126,'SHOE_SKU_COMPONENT','cd_product','PRODUCT','cd_product','ds_product'),(127,'SHOE_SKU_COMPONENT_ATTRIBUTES','cd_shoe_component_attribute_items','SHOE_COMPONENT_ATTRIBUTE_ITEMS','cd_shoe_component_attribute_items','ds_shoe_component_attribute_items'),(128,'CONSTRUCTION_COMPONENT','cd_unit_measure_length','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(129,'CONSTRUCTION_COMPONENT','cd_unit_measure_width','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(130,'SHOE_SKU_COMPONENT','cd_unit_measure_length','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(131,'SHOE_SKU_COMPONENT','cd_unit_measure_width','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(132,'SHOE_SPECIFICATION_COMPONENT','cd_unit_measure_length','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(133,'SHOE_SPECIFICATION_COMPONENT','cd_unit_measure_width','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(134,'SHOE_LEVEL_X_SPEC_PICTURE_TYPE','cd_spec_picture_type','SPEC_PICTURE_TYPE','cd_spec_picture_type','ds_spec_picture_type'),(135,'SHOE_LEVEL_X_SPEC_PICTURE_TYPE','cd_shoe_level','SHOE_LEVEL','cd_shoe_level','ds_shoe_level'),(136,'CONSTRUCTION_DOCUMENT_REPOSITORY','cd_spec_picture_type','SPEC_PICTURE_TYPE','cd_spec_picture_type','ds_spec_picture_type'),(137,'SHOE_SKU_DOCUMENT_REPOSITORY','cd_shoe_sku','SHOE_SKU','cd_shoe_sku','ds_shoe_sku'),(138,'SHOE_SKU_DOCUMENT_REPOSITORY','cd_spec_picture_type','SPEC_PICTURE_TYPE','cd_spec_picture_type','ds_spec_picture_type'),(139,'FACTORY','cd_country','COUNTRY','cd_country','ds_country'),(140,'SHOE_DIVISION_STYLE_NAME','cd_division','DIVISION','cd_division','ds_division'),(141,'SHOE_SAMPLE_REQUEST','cd_sample_type','SAMPLE_TYPE','cd_sample_type','ds_sample_type'),(142,'SHOE_SAMPLE_REQUEST','cd_division_brand','DIVISION_BRAND','cd_division_brand','ds_division_brand'),(143,'SHOE_SAMPLE_REQUEST','cd_customer','CUSTOMER','cd_customer','ds_customer'),(144,'SHOE_SAMPLE_REQUEST','cd_division','DIVISION','cd_division','ds_division'),(145,'SHOE_SIZE','cd_shoe_size_width','SHOE_SIZE_WIDTH','cd_shoe_size_width','ds_shoe_size_width'),(146,'SHOE_SIZE','cd_shoe_size_type','SHOE_SIZE_TYPE','cd_shoe_size_type','ds_shoe_size_type'),(147,'SHOE_SIZE','cd_shoe_size_size','SHOE_SIZE_SIZE','cd_shoe_size_size','ds_shoe_size_size'),(148,'SHOE_SIZE','cd_type_gender','TYPE_GENDER','cd_type_gender','ds_type_gender'),(149,'SHOE_SIZE','cd_shoe_size_length','SHOE_SIZE_LENGTH','cd_shoe_size_length','ds_shoe_size_length'),(150,'DIVISION','cd_shoe_size_type','SHOE_SIZE_TYPE','cd_shoe_size_type','ds_shoe_size_type'),(151,'SHOE_SAMPLE_REQUEST','cd_factory','FACTORY','cd_factory','ds_factory'),(152,'SHOE_SAMPLE_REQUEST_SKU','cd_shoe_sku','SHOE_SKU','cd_shoe_sku','ds_shoe_sku'),(153,'DIVISION_SHOE_SETUP','cd_shoe_size_type','SHOE_SIZE_TYPE','cd_shoe_size_type','ds_shoe_size_type'),(154,'DIVISION_SHOE_SETUP','cd_division','DIVISION','cd_division','ds_division'),(155,'ADDRESS_PACKING','cd_division','DIVISION','cd_division','ds_division'),(156,'ADDRESS_PACKING','cd_country','COUNTRY','cd_country','ds_country'),(157,'DIVISION_SHOE_SETUP','cd_address_packing','ADDRESS_PACKING','cd_address_packing','ds_address_packing'),(158,'DIVISION_SHOE_SETUP','cd_shoe_sample_shipping_type','SHOE_SAMPLE_SHIPPING_TYPE','cd_shoe_sample_shipping_type','ds_shoe_sample_shipping_type'),(159,'SHOE_SAMPLE_REQUEST_SKU_DELIVERY','cd_address_packing','ADDRESS_PACKING','cd_address_packing','ds_address_packing'),(160,'SHOE_SAMPLE_REQUEST_SKU_DELIVERY','cd_shoe_sample_shipping_type','SHOE_SAMPLE_SHIPPING_TYPE','cd_shoe_sample_shipping_type','ds_shoe_sample_shipping_type'),(161,'SHOE_SAMPLE_REQUEST_SKU_DELIVERY','cd_customer_to_bill','CUSTOMER','cd_customer','ds_customer'),(162,'SHOE_SMP_REQ_SKU_DELIVERY_PENALTY','cd_shoe_sample_penalty_type','SHOE_SAMPLE_PENALTY_TYPE','cd_shoe_sample_penalty_type','ds_shoe_sample_penalty_type'),(163,'SHOE_SAMPLE_REQUEST','cd_season','SEASON','cd_season','ds_season_short'),(164,'SHOE_TYPE_X_SHOE_TYPE_CATEGORY','cd_shoe_type_category','SHOE_TYPE_CATEGORY','cd_shoe_type_category','ds_shoe_type_category'),(165,'SHOE_TYPE_X_SHOE_TYPE_CATEGORY','cd_shoe_type','SHOE_TYPE','cd_shoe_type','ds_shoe_type'),(166,'SHOE_SPECIFICATION','cd_shoe_type','SHOE_TYPE','cd_shoe_type','ds_shoe_type'),(167,'SHOE_SPECIFICATION','cd_shoe_type_category','SHOE_TYPE_CATEGORY','cd_shoe_type_category','ds_shoe_type_category'),(168,'SHOE_SAMPLE_PRICE','cd_season','SEASON','cd_season','ds_season_short'),(169,'SHOE_SAMPLE_PRICE','cd_division','DIVISION','cd_division','ds_division'),(170,'SHOE_SAMPLE_PRICE','cd_shoe_type','SHOE_TYPE','cd_shoe_type','ds_shoe_type'),(171,'SHOE_SAMPLE_PRICE','cd_shoe_type_category','SHOE_TYPE_CATEGORY','cd_shoe_type_category','ds_shoe_type_category'),(172,'SHOE_SAMPLE_PRICE','cd_product_type_upper','PRODUCT_TYPE','cd_product_type','ds_product_type'),(173,'SHOE_SAMPLE_PRICE','cd_product_type_sole','PRODUCT_TYPE','cd_product_type','ds_product_type'),(174,'SHOE_SAMPLE_PRICE','cd_customer','CUSTOMER','cd_customer','ds_customer'),(175,'SHOE_SAMPLE_PRICE_X_COMMISSION','cd_shoe_sample_price_comission','SHOE_SAMPLE_PRICE_COMMISSION','cd_shoe_sample_price_comission','ds_shoe_sample_price_comission'),(176,'SHOE_SAMPLE_PRICE','cd_payment_terms_factory','PAYMENT_TERMS','cd_payment_terms','ds_payment_terms'),(177,'SHOE_SAMPLE_PRICE','cd_payment_terms_customer','PAYMENT_TERMS','cd_payment_terms','ds_payment_terms'),(178,'SHOE_SAMPLE_PACKING_LIST','cd_carrier','CARRIER','cd_carrier','ds_carrier'),(179,'SHOE_SAMPLE_PACKING_LIST','cd_via','VIA','cd_via','ds_via'),(180,'SHOE_SAMPLE_PACKING_LIST','cd_address_packing','ADDRESS_PACKING','cd_address_packing','ds_address_packing'),(181,'SHOE_SAMPLE_PACKING_LIST_CASE','cd_shoe_sample_packing_list','SHOE_SAMPLE_PACKING_LIST','cd_shoe_sample_packing_list','ds_shoe_sample_packing_list'),(182,'SHOE_SAMPLE_PACKING_LIST_CASE','cd_shoe_case','SHOE_CASE','cd_shoe_case','ds_shoe_case'),(183,'SHOE_SAMPLE_PL_CASE_DELIVERY','cd_shoe_box','SHOE_BOX','cd_shoe_box','ds_shoe_box'),(184,'SHOE_PURCHASE_ORDER','cd_division_brand','DIVISION_BRAND','cd_division_brand','ds_division_brand'),(185,'CUSTOMER_SHOE_SETUP','cd_trading_for_samples','TRADING','cd_trading','ds_trading'),(186,'SHOE_COST_SHEET_SKU_ADDITIONAL_COST','cd_unit_measure_width','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(187,'SHOE_COST_SHEET_SKU_COMPONENT','cd_unit_measure_yield','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(188,'SHOE_COST_SHEET_SKU_ADDITIONAL_COST','cd_shoe_cs_pairs_based','SHOE_CS_PAIRS_BASED','cd_shoe_cs_pairs_based','ds_shoe_cs_pairs_based'),(189,'SHOE_PRICE_COMMISSION_OPTIONS','cd_shoe_price_commission_options_related_helper','SHOE_PRICE_COMMISSION_OPTIONS','cd_shoe_price_commission_options','ds_shoe_price_commission_options'),(190,'SHOE_COST_SHEET_SKU_COMPONENT','cd_supplier','SUPPLIER','cd_supplier','ds_supplier'),(191,'BILLING_TO_X_PARTS','cd_billing_to','BILLING_TO','cd_billing_to','ds_billing_to'),(192,'SHOE_PURCHASE_ORDER_SKU','cd_carrier','CARRIER','cd_carrier','ds_carrier'),(193,'SHOE_COST_SHEET_SKU_ADDITIONAL_COST','cd_currency_supplier','CURRENCY','cd_currency','ds_currency'),(194,'SHOE_COST_SHEET_SKU_ADDITIONAL_COST','cd_currency_freight_supplier','CURRENCY','cd_currency','ds_currency'),(195,'SHOE_PURCHASE_ORDER','cd_division','DIVISION','cd_division','ds_division'),(196,'SHOE_PURCHASE_ORDER','cd_factory','FACTORY','cd_factory','ds_factory'),(197,'SHOE_SAMPLE_INVOICE','cd_trading','TRADING','cd_trading','ds_trading'),(198,'CARRIER','cd_via','VIA','cd_via','ds_via'),(199,'SHOE_COST_SHEET_SKU_VERSION','cd_shoe_sku','SHOE_SKU','cd_shoe_sku','ds_shoe_sku'),(200,'SHOE_PURCHASE_ORDER_SKU','cd_shoe_sku','SHOE_SKU','cd_shoe_sku','ds_shoe_sku'),(201,'SHOE_CS_ADDITIONAL_COST_BASE','cd_supplier_default','SUPPLIER','cd_supplier','ds_supplier'),(202,'CUSTOMER_SHOE_SETUP','cd_billing_to_for_samples','BILLING_TO','cd_billing_to','ds_billing_to'),(203,'SHOE_COST_SHEET_SKU','cd_currency_base','CURRENCY','cd_currency','ds_currency'),(204,'SHOE_PURCHASE_ORDER','cd_customer','CUSTOMER','cd_customer','ds_customer'),(205,'SHOE_PURCHASE_ORDER_SKU','cd_destination','DESTINATION','cd_destination','ds_destination'),(206,'SHOE_PRICE','cd_division','DIVISION','cd_division','ds_division'),(207,'SHOE_SAMPLE_INVOICE','cd_shipper','SHIPPER','cd_shipper','ds_shipper'),(208,'SHOE_COST_SHEET_SKU','cd_unit_measure_base','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(209,'SHOE_COST_SHEET_SKU_COMPONENT','cd_unit_measure_width_supplier','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(210,'SHOE_CS_ADDITIONAL_COST_BASE','cd_unit_measure_width','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(211,'SHOE_SAMPLE_INVOICE_CANCELLED_DELIVERY_PENALTY','cd_shoe_sample_invoice_cancelled','SHOE_SAMPLE_INVOICE_CANCELLED','cd_shoe_sample_invoice_cancelled','ds_shoe_sample_invoice_cancelled'),(212,'SHOE_SAMPLE_INVOICE_PACKING_LIST','cd_shoe_sample_packing_list','SHOE_SAMPLE_PACKING_LIST','cd_shoe_sample_packing_list','ds_shoe_sample_packing_list'),(213,'BILLING_TO_X_PARTS','cd_billing_to_parts','BILLING_TO_PARTS','cd_billing_to_parts','ds_billing_to_parts'),(214,'DESTINATION','cd_country','COUNTRY','cd_country','ds_country'),(215,'SHOE_CS_ADDITIONAL_COST_BASE','cd_currency_unit_price_default','CURRENCY','cd_currency','ds_currency'),(216,'SHOE_PRICE','cd_factory','FACTORY','cd_factory','ds_factory'),(217,'SHOE_PRICE','cd_season','SEASON','cd_season','ds_season_short'),(218,'SHOE_PURCHASE_ORDER_INVOICE','cd_shipper','SHIPPER','cd_shipper','ds_shipper'),(219,'SHOE_COST_SHEET_SKU_COMPONENT','cd_unit_measure_width','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(220,'SHOE_COST_SHEET_SKU_COMPONENT','cd_unit_measure_length_supplier','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(221,'SHOE_CS_ADDITIONAL_COST_BASE','cd_shoe_cs_pairs_based','SHOE_CS_PAIRS_BASED','cd_shoe_cs_pairs_based','ds_shoe_cs_pairs_based'),(222,'SHOE_CS_ADDITIONAL_COST_BASE','cd_shoe_level','SHOE_LEVEL','cd_shoe_level','ds_shoe_level'),(223,'SHOE_PRICE_X_COMMISSION','cd_shoe_price_commission_options_related_helper','SHOE_PRICE_COMMISSION_OPTIONS','cd_shoe_price_commission_options','ds_shoe_price_commission_options'),(224,'SHOE_SAMPLE_INVOICE_PACKING_LIST','cd_shoe_sample_invoice','SHOE_SAMPLE_INVOICE','cd_shoe_sample_invoice','ds_shoe_sample_invoice'),(225,'SHOE_COST_SHEET_SKU_COMPONENT','cd_color','COLOR','cd_color','ds_color'),(226,'SHOE_COST_SHEET_SKU_ADDITIONAL_COST','cd_supplier','SUPPLIER','cd_supplier','ds_supplier'),(227,'SHOE_COST_SHEET_SKU_CURRENCY_RATE','cd_currency_from','CURRENCY','cd_currency','ds_currency'),(228,'SHOE_PROCESS','cd_factory','FACTORY','cd_factory','ds_factory'),(229,'SHOE_SPECIFICATION','cd_season','SEASON','cd_season','ds_season_short'),(230,'SHOE_SAMPLE_INVOICE_CANCELLED','cd_trading','TRADING','cd_trading','ds_trading'),(231,'SHOE_COST_SHEET_SKU_ADDITIONAL_COST','cd_unit_measure_yield','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(232,'SHOE_PRICE_COMMISSION_OPTIONS','cd_shoe_price_commission_options_type','SHOE_PRICE_COMMISSION_OPTIONS_TYPE','cd_shoe_price_commission_options_type','ds_shoe_price_commission_options_type'),(233,'SHOE_PROCESS_GROUP_ITEM','cd_shoe_process_item_type_data','SHOE_PROCESS_ITEM_TYPE_DATA','cd_shoe_process_item_data','ds_shoe_process_item_data'),(234,'SHOE_PURCHASE_ORDER_INVOICE_SKU','cd_shoe_purchase_order_invoice','SHOE_PURCHASE_ORDER_INVOICE','cd_shoe_purchase_order_invoice','ds_shoe_purchase_order_invoice'),(235,'SHOE_COST_SHEET_SKU_COMPONENT','cd_currency_supplier','CURRENCY','cd_currency','ds_currency'),(236,'SHOE_PRICE','cd_currency','CURRENCY','cd_currency','ds_currency'),(237,'SHOE_PURCHASE_ORDER_INVOICE','cd_trading','TRADING','cd_trading','ds_trading'),(238,'SHOE_PURCHASE_ORDER_SKU','cd_via','VIA','cd_via','ds_via'),(239,'SHOE_PRICE_X_COMMISSION','cd_shoe_price_commission_options','SHOE_PRICE_COMMISSION_OPTIONS','cd_shoe_price_commission_options','ds_shoe_price_commission_options'),(240,'SHOE_SAMPLE_PL_CASE_DELIVERY','cd_shoe_sample_packing_type','SHOE_SAMPLE_PACKING_TYPE','cd_shoe_sample_packing_type','ds_shoe_sample_packing_type'),(241,'SHOE_COST_SHEET_SKU_COMPONENT','cd_product','PRODUCT','cd_product','ds_product'),(242,'SHOE_COST_SHEET_SKU_COMPONENT','cd_currency_freight_supplier','CURRENCY','cd_currency','ds_currency'),(243,'SHOE_PRICE','cd_customer','CUSTOMER','cd_customer','ds_customer'),(244,'SHOE_PURCHASE_ORDER_INVOICE','cd_factory','FACTORY','cd_factory','ds_factory'),(245,'SHOE_PURCHASE_ORDER_SKU','cd_loading','LOADING','cd_loading','ds_loading'),(246,'SHOE_CS_ADDITIONAL_COST_BASE','cd_unit_measure_length','UNIT_MEASURE','cd_unit_measure','ds_unit_measure'),(247,'SHOE_COST_SHEET_SKU_PAIRS_BASED','cd_shoe_cs_pairs_based','SHOE_CS_PAIRS_BASED','cd_shoe_cs_pairs_based','ds_shoe_cs_pairs_based'),(248,'SHOE_PRICE_COMMISSION_OPTIONS','cd_shoe_price_commission_options_related','SHOE_PRICE_COMMISSION_OPTIONS','cd_shoe_price_commission_options','ds_shoe_price_commission_options'),(249,'SHOE_PRICE_X_COMMISSION','cd_shoe_price_commission_options_related','SHOE_PRICE_COMMISSION_OPTIONS','cd_shoe_price_commission_options','ds_shoe_price_commission_options'),(250,'SHOE_PRICE_X_COMMISSION','cd_shoe_price_commission_options_type','SHOE_PRICE_COMMISSION_OPTIONS_TYPE','cd_shoe_price_commission_options_type','ds_shoe_price_commission_options_type'),(251,'SHOE_COST_SHEET_SKU','cd_shoe_sku','SHOE_SKU','cd_shoe_sku','ds_shoe_sku'),(252,'SHOE_PRICE_X_SHOE_SKU','cd_shoe_sku','SHOE_SKU','cd_shoe_sku','ds_shoe_sku'),(253,'LOADING','cd_country','COUNTRY','cd_country','ds_country'),(254,'SHOE_PURCHASE_ORDER_INVOICE','cd_customer','CUSTOMER','cd_customer','ds_customer'),(255,'SHOE_PURCHASE_ORDER','cd_season','SEASON','cd_season','ds_season_short'),(256,'SHOE_PURCHASE_ORDER_SKU','cd_shoe_purchase_order','SHOE_PURCHASE_ORDER','cd_shoe_purchase_order','ds_shoe_purchase_order');
/*!40000 ALTER TABLE `SYSTEM_RELATIONS` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYSTEM_RELATIONS BEFORE INSERT ON SYSTEM_RELATIONS
FOR EACH ROW
BEGIN
    IF NEW.cd_system_relation IS NULL THEN
        SET NEW.cd_system_relation = nextval('SYSTEM_RELATIONS');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYSTEM_REPORTS`
--

DROP TABLE IF EXISTS `SYSTEM_REPORTS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYSTEM_REPORTS` (
  `cd_system_reports` int(11) NOT NULL,
  `ds_system_reports` varchar(255) NOT NULL,
  `ds_system_reports_table_join` varchar(100) DEFAULT NULL,
  `ds_system_reports_title` varchar(64) NOT NULL,
  PRIMARY KEY (`cd_system_reports`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYSTEM_REPORTS`
--

LOCK TABLES `SYSTEM_REPORTS` WRITE;
/*!40000 ALTER TABLE `SYSTEM_REPORTS` DISABLE KEYS */;
INSERT INTO `SYSTEM_REPORTS` VALUES (75,'specsheetdefault.rptdesign','SHOE_SKU','Specification Sheet'),(76,'sample_invoice_simple.rptdesign','Show Sample Invoice','Invoice Report Factory'),(77,'inqtofactory.rptdesign','SHOE_INQUIRY','Inquiry Report'),(78,'poreportdefault.rptdesign','SHOE_PURCHASE_ORDER_SKU','PO Report'),(79,'supplier_list.rptdesign','SUPPLIER','Supplier Report'),(80,'sampledefault.rptdesign','SHOE_SAMPLE_REQUEST','PO Report'),(81,'seasonbudgetyeardivision.rptdesign','SHOE_SEASON_BUDGET','Season Budget Report'),(82,'samplecorrectiondefault.rptdesign','SHOE_SAMPLE_REQUEST','PO Report'),(83,'packing_list_generic.rptdesign','Sample Packing List','Sample Packing List'),(84,'sample_invoice_cancelled_simple.rptdesign','Show Sample Invoice','Invoice Report Factory');
/*!40000 ALTER TABLE `SYSTEM_REPORTS` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYSTEM_REPORTS BEFORE INSERT ON SYSTEM_REPORTS
FOR EACH ROW
BEGIN
    IF NEW.cd_system_reports IS NULL THEN
        SET NEW.cd_system_reports = nextval('SYSTEM_REPORTS');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYSTEM_REPORTS_AUTHORIZATION`
--

DROP TABLE IF EXISTS `SYSTEM_REPORTS_AUTHORIZATION`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYSTEM_REPORTS_AUTHORIZATION` (
  `cd_system_reports_authorization` int(11) NOT NULL,
  `cd_system_reports` int(11) NOT NULL,
  `ds_authorization` varchar(64) NOT NULL,
  `ds_where` longtext,
  `nr_file_type` int(11) NOT NULL DEFAULT '1',
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cd_system_languages` int(11) DEFAULT NULL,
  `ds_sys_report_auth_username` varchar(32) NOT NULL,
  `ds_sys_report_auth_filename` longtext NOT NULL,
  `ds_sys_report_auth_extension` varchar(8) NOT NULL,
  `ds_json_more_parms` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cd_system_reports_authorization`),
  UNIQUE KEY `IUN_REPORT_AUTH` (`cd_system_reports`,`ds_authorization`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYSTEM_REPORTS_AUTHORIZATION`
--

LOCK TABLES `SYSTEM_REPORTS_AUTHORIZATION` WRITE;
/*!40000 ALTER TABLE `SYSTEM_REPORTS_AUTHORIZATION` DISABLE KEYS */;
INSERT INTO `SYSTEM_REPORTS_AUTHORIZATION` VALUES (904,75,'b0c8ee3c85d723743a853378935e246c',' AND sku.cd_shoe_sku in ( 305)',1,'2017-08-04 12:20:45',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/b0c8ee3c85d723743a853378935e246c.pdf','pdf','{\"showArticle\": \"Y\"}'),(905,79,'c03516fa827ac714ee53f7b75b59fd35',' and dt_deactivated IS NULL',1,'2017-08-04 12:22:01',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/c03516fa827ac714ee53f7b75b59fd35.pdf','pdf','[]'),(906,77,'dcea048125ba5655b8c99aa704c6d6fc',' AND i.cd_shoe_inquiry in ( 48)',1,'2017-08-04 12:23:01',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/dcea048125ba5655b8c99aa704c6d6fc.pdf','pdf','[]'),(907,77,'d421dc13143629d65255eaa0d036e7e2',' AND i.cd_shoe_inquiry in ( 48,1)',1,'2017-08-04 12:23:15',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/d421dc13143629d65255eaa0d036e7e2.pdf','pdf','[]'),(908,75,'95a5b7fa96584798fde19b0251b68812',' AND sku.cd_shoe_sku in ( 267,264,266)',1,'2017-08-05 12:08:29',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/95a5b7fa96584798fde19b0251b68812.pdf','pdf','{\"showArticle\": \"N\"}'),(909,75,'067311854ff48d581fb918dc3dc8eb39',' AND sku.cd_shoe_sku in ( 305)',1,'2017-08-22 15:23:01',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/067311854ff48d581fb918dc3dc8eb39.pdf','pdf','{\"showArticle\": \"N\"}'),(910,80,'0a9abc84a5afd6b18726a5e23a31925c',' AND i.cd_shoe_sample_request in ( 128)',1,'2017-08-22 15:23:35',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/0a9abc84a5afd6b18726a5e23a31925c.pdf','pdf','[]'),(911,75,'2255bd88c3d4443d5547459ff9896a9a',' AND sku.cd_shoe_sku in ( 171,263)',1,'2017-08-22 15:24:44',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/2255bd88c3d4443d5547459ff9896a9a.pdf','pdf','{\"showArticle\": \"Y\"}'),(912,80,'cce06bd460602d8a26bcf87ae4a76f99',' AND i.cd_shoe_sample_request in ( 128)',1,'2017-08-22 15:25:53',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/cce06bd460602d8a26bcf87ae4a76f99.pdf','pdf','[]'),(913,80,'60ef7d857e49f7120fee9c46191e76ef',' AND i.cd_shoe_sample_request in ( 128)',1,'2017-08-22 15:51:47',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/60ef7d857e49f7120fee9c46191e76ef.pdf','pdf','[]'),(914,80,'b8fdd61294f0c78b69a80c16caa6c069',' AND i.cd_shoe_sample_request in ( 64)',1,'2017-08-25 16:05:48',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/b8fdd61294f0c78b69a80c16caa6c069.pdf','pdf','[]'),(915,75,'c0824f23c568c4f541c031a377188279',' AND sku.cd_shoe_sku in ( )',1,'2017-08-25 16:06:09',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/c0824f23c568c4f541c031a377188279.pdf','pdf','{\"showArticle\": \"Y\"}'),(916,75,'6c6bab12193690ea9556c3d304a34fbc',' AND sku.cd_shoe_sku in ( )',1,'2017-08-25 16:07:16',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/6c6bab12193690ea9556c3d304a34fbc.pdf','pdf','{\"showArticle\": \"Y\"}'),(917,80,'49b6af42abc868af911fa64eac234850',' AND i.cd_shoe_sample_request in ( 128,152)',1,'2017-08-25 16:08:16',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/49b6af42abc868af911fa64eac234850.pdf','pdf','[]'),(918,80,'7d0f9c046bf0fb52b43a6269f9df38dd',' AND i.cd_shoe_sample_request in ( 128,152)',1,'2017-08-25 16:08:32',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/7d0f9c046bf0fb52b43a6269f9df38dd.pdf','pdf','[]'),(919,75,'9bfe5a03863c307355bae37009e6d6b4',' AND sku.cd_shoe_sku in ( 171,263,171)',1,'2017-08-25 16:08:45',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/9bfe5a03863c307355bae37009e6d6b4.pdf','pdf','{\"showArticle\": \"Y\"}'),(920,80,'52dd34550da0b4c8f0ff459f020a138c',' AND i.cd_shoe_sample_request in ( 128,152)',1,'2017-08-25 16:09:47',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/52dd34550da0b4c8f0ff459f020a138c.pdf','pdf','[]'),(921,75,'9956d7cb4c4582461a4b56faa5b26dcc',' AND sku.cd_shoe_sku in ( 171,263,171)',1,'2017-08-25 16:09:55',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/9956d7cb4c4582461a4b56faa5b26dcc.pdf','pdf','{\"showArticle\": \"Y\"}'),(922,80,'4b2d38876f112dc4da7f76e21e95391a',' AND i.cd_shoe_sample_request in ( 128,152)',1,'2017-08-25 16:10:05',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/4b2d38876f112dc4da7f76e21e95391a.pdf','pdf','[]'),(923,82,'129ed2a5f6b8a42ec2b6f3e608d2b163',' AND i.cd_shoe_sample_request_correction in ( 64)',1,'2017-08-25 16:19:01',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/129ed2a5f6b8a42ec2b6f3e608d2b163.pdf','pdf','[]'),(924,82,'17e53a37c8d097b32ef7573c6c817c54',' AND i.cd_shoe_sample_request_correction in ( 64)',1,'2017-08-25 16:19:18',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/17e53a37c8d097b32ef7573c6c817c54.pdf','pdf','[]'),(925,82,'93204dc218fba640bb4166363c1aee19',' AND i.cd_shoe_sample_request_sku in ( 64)',1,'2017-08-25 16:25:47',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/93204dc218fba640bb4166363c1aee19.pdf','pdf','[]'),(926,82,'9d88aa98b2a9662d790611c7d9c9d8f8',' AND g.cd_shoe_sample_request_sku in ( 64)',1,'2017-08-25 16:27:58',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/9d88aa98b2a9662d790611c7d9c9d8f8.pdf','pdf','[]'),(927,82,'ff72f6162f15e0727240f205bf2438e3',' AND g.cd_shoe_sample_request_sku in ( 64,109)',1,'2017-08-25 16:28:10',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/ff72f6162f15e0727240f205bf2438e3.pdf','pdf','[]'),(928,80,'ec0e609663010a7b7bd4f119e87c56bf',' AND i.cd_shoe_sample_request in ( 128,152)',1,'2017-08-25 16:28:47',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/ec0e609663010a7b7bd4f119e87c56bf.pdf','pdf','[]'),(929,75,'c954268955a09c8c80e7e1792f13ac06',' AND sku.cd_shoe_sku in ( 171,263,171)',1,'2017-08-25 16:28:56',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/c954268955a09c8c80e7e1792f13ac06.pdf','pdf','{\"showArticle\": \"Y\"}'),(930,82,'b7898f8fb315933ec8bdfcf18617cbe9',' AND g.cd_shoe_sample_request_sku in ( 64,109)',1,'2017-08-25 16:35:19',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/b7898f8fb315933ec8bdfcf18617cbe9.pdf','pdf','[]'),(931,80,'09d512d3839d5748ada91dbcddf330eb',' AND i.cd_shoe_sample_request in ( 128,152)',1,'2017-08-25 16:36:26',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/09d512d3839d5748ada91dbcddf330eb.pdf','pdf','[]'),(932,75,'0a220a251304ea4a5727956990dce8c5',' AND sku.cd_shoe_sku in ( 171,263,171)',1,'2017-08-25 16:36:35',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/0a220a251304ea4a5727956990dce8c5.pdf','pdf','{\"showArticle\": \"Y\"}'),(933,82,'bea2fe57dc594d5483bb77b27c6dfdc1',' AND g.cd_shoe_sample_request_sku in ( 64,109)',1,'2017-08-25 16:36:41',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/bea2fe57dc594d5483bb77b27c6dfdc1.pdf','pdf','[]'),(934,80,'b2bdff38ea35622f8f65718f61220b27',' AND i.cd_shoe_sample_request in ( 128,152)',1,'2017-08-25 16:38:10',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/b2bdff38ea35622f8f65718f61220b27.pdf','pdf','[]'),(935,75,'fc9c5faa0d593d52523f863f9bce39c8',' AND sku.cd_shoe_sku in ( 171,263,171)',1,'2017-08-25 16:38:17',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/fc9c5faa0d593d52523f863f9bce39c8.pdf','pdf','{\"showArticle\": \"Y\"}'),(936,82,'e318f7bb7b6f94fd710d65bae8dc8eed',' AND g.cd_shoe_sample_request_sku in ( 64,109)',1,'2017-08-25 16:39:07',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/e318f7bb7b6f94fd710d65bae8dc8eed.pdf','pdf','[]'),(937,80,'a11c9bfa7cd2247498844e0a3e22541c',' AND i.cd_shoe_sample_request in ( 128,152)',1,'2017-08-25 16:47:39',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/a11c9bfa7cd2247498844e0a3e22541c.pdf','pdf','[]'),(938,75,'19c597a91540160f181e5a25613fb14d',' AND sku.cd_shoe_sku in ( 171,263,171)',1,'2017-08-25 16:47:46',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/19c597a91540160f181e5a25613fb14d.pdf','pdf','{\"showArticle\": \"N\"}'),(939,82,'dfa8f0ffcd011c92a7d0abe625753b63',' AND g.cd_shoe_sample_request_sku in ( 64,109)',1,'2017-08-25 16:47:51',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/dfa8f0ffcd011c92a7d0abe625753b63.pdf','pdf','[]'),(940,82,'0f4e815980cd08d08bcf71aa2ebe09db',' AND g.cd_shoe_sample_request_sku in ( 64,109)',1,'2017-08-25 16:48:47',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/0f4e815980cd08d08bcf71aa2ebe09db.pdf','pdf','[]'),(941,80,'4cea734797c042f496369d3a0c19675c',' AND i.cd_shoe_sample_request in ( 128,152)',1,'2017-08-25 16:48:55',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/4cea734797c042f496369d3a0c19675c.pdf','pdf','[]'),(942,82,'f172e9ccc952e5b61c5a9871b1737446',' AND g.cd_shoe_sample_request_sku in ( 64,109)',1,'2017-08-25 16:49:32',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/f172e9ccc952e5b61c5a9871b1737446.pdf','pdf','[]'),(943,75,'ebce83416b9a9ff43e1415b6514e35ad',' AND sku.cd_shoe_sku in ( 171,263,171)',1,'2017-08-25 16:49:41',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/ebce83416b9a9ff43e1415b6514e35ad.pdf','pdf','{\"showArticle\": \"N\"}'),(944,82,'442aa01d70e8b3f0f4f6fb40a5ea2b5c',' AND g.cd_shoe_sample_request_sku in ( 64,109)',1,'2017-08-26 14:49:48',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/442aa01d70e8b3f0f4f6fb40a5ea2b5c.pdf','pdf','[]'),(945,82,'c70c4f01f96b261f9f7d569559d0b3ff',' AND g.cd_shoe_sample_request_sku in ( 64,109)',1,'2017-08-26 14:51:15',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/c70c4f01f96b261f9f7d569559d0b3ff.pdf','pdf','[]'),(946,82,'855c4046045417f08e1694ee0b6a65b0',' AND g.cd_shoe_sample_request_sku in ( 64,109)',1,'2017-08-26 15:07:08',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/855c4046045417f08e1694ee0b6a65b0.pdf','pdf','[]'),(947,80,'6d0b257568ad5919cd66acb3fbb41272',' AND i.cd_shoe_sample_request in ( 128,152)',1,'2017-08-26 15:07:17',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/6d0b257568ad5919cd66acb3fbb41272.pdf','pdf','[]'),(948,75,'b19b3fe8cb7f18e254f9fddf345f5742',' AND sku.cd_shoe_sku in ( 171,263,171)',1,'2017-08-26 15:07:21',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/b19b3fe8cb7f18e254f9fddf345f5742.pdf','pdf','{\"showArticle\": \"N\"}'),(949,75,'80fa98a89613d0b3f1f11c58c6fef8ac',' AND sku.cd_shoe_sku in ( 171,263,171)',1,'2017-08-26 15:07:30',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/80fa98a89613d0b3f1f11c58c6fef8ac.pdf','pdf','{\"showArticle\": \"Y\"}'),(950,75,'97b6f41fc69998d22080bad0203f6a54',' AND sku.cd_shoe_sku in ( 305)',1,'2017-09-12 21:13:32',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/97b6f41fc69998d22080bad0203f6a54.pdf','pdf','{\"showArticle\": \"Y\"}'),(951,82,'51ed2dded331af1891e2f3f5a0142c51',' AND g.cd_shoe_sample_request_sku in ( 64)',1,'2017-10-07 14:40:58',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/51ed2dded331af1891e2f3f5a0142c51.pdf','pdf','[]'),(952,80,'3c128c8533bfb6323d52610d773cca91',' AND i.cd_shoe_sample_request in ( 128)',1,'2017-10-07 14:41:23',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/3c128c8533bfb6323d52610d773cca91.pdf','pdf','[]'),(953,75,'344355715a95728496e66f78cbcfd5da',' AND sku.cd_shoe_sku in ( 171,263)',1,'2017-10-07 14:41:32',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/344355715a95728496e66f78cbcfd5da.pdf','pdf','{\"showArticle\": \"N\"}'),(954,75,'abd3c365c9a1762881b7f73d47d7d195',' AND sku.cd_shoe_sku in ( 218,10,242,171,18,242,18,18,171,171,18,10,18,171,18,171,171,219,171,220,220,220,171,242,242,242,242,171,18,171,171,171,242,171,171,171,171,171,171,171,171,263,171,171,171,280,267,267,241,171,171,171,171,263,171,171,171,171,171,171,171,171,171,171,171,171,171,171,171,171,171,171,171,171,171,171,171,171,171,171,171,171,171,171,171,274,275,274,275,276,276,264,264,264,264,263,242,171,264,264,264,272,171,171,242,242,271,264,242,171,171,171,171,171,171,171,171,321,321)',1,'2017-10-23 15:24:15',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/abd3c365c9a1762881b7f73d47d7d195.pdf','pdf','{\"showArticle\": \"N\"}'),(955,78,'dc087bbca760c70256ff4b3eb8e1ac90',' AND po.cd_shoe_purchase_order in ( 260,261,262,263,265,254,223,250,241,43,58,84,42,239,41,59,266,267,346,347,348,349,350,351,238,264,319,306,268,318,352,353,308,309,310,232,97,344,307,317,338,340,341,270,342,345,277,278,280,281,282,283,284,285,286,287,288,289,290,291,292,293,294,295,296,297,298,299,300,301,302,303,304,332,333,354,355,316,225,305,255,251,235,240,236,237)',1,'2017-10-23 15:24:59',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/dc087bbca760c70256ff4b3eb8e1ac90.pdf','pdf','[]'),(956,80,'9e2e8442810a08d1158a1e9c692954f2',' AND i.cd_shoe_sample_request in ( 146)',1,'2017-10-23 17:34:36',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/9e2e8442810a08d1158a1e9c692954f2.pdf','pdf','[]'),(957,80,'b7f3fe4152e7894810b8948f0cdfee83',' AND i.cd_shoe_sample_request in ( 36,145,146,147,148,149,150,151,154,162,163,164,165,166,168,169,170,171,173,174,175,176,177,178,179,180,181,182,185,186,187,188,193,194,196,203)',1,'2017-10-23 17:34:56',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/b7f3fe4152e7894810b8948f0cdfee83.pdf','pdf','[]'),(958,83,'b9ea107c0d1bd37379089e9a396dc706',' AND pl.cd_shoe_sample_packing_list = 17',1,'2017-10-23 17:37:11',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/b9ea107c0d1bd37379089e9a396dc706.pdf','pdf','{\"type\": 1, \"recid\": 17}'),(959,83,'8945421366dec489902edbd31743bcea',' AND pl.cd_shoe_sample_packing_list = 17',1,'2017-10-23 17:38:37',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/8945421366dec489902edbd31743bcea.pdf','pdf','{\"type\": 1, \"recid\": 17}'),(960,76,'a0f3c4e278c798c76da1bb72ea3ee2f2',' AND i.cd_shoe_sample_invoice = 13',1,'2017-10-23 17:40:27',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/a0f3c4e278c798c76da1bb72ea3ee2f2.pdf','pdf','{\"type\": 1, \"recid\": 13}'),(961,75,'535e3dc3b8fb38984b2dd4e0d9803f60',' AND sku.cd_shoe_sku in ( 171)',1,'2017-10-27 16:50:55',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/535e3dc3b8fb38984b2dd4e0d9803f60.pdf','pdf','{\"showArticle\": \"N\"}'),(962,75,'440499a85fb8781ba2318f92aa10cebf',' AND sku.cd_shoe_sku in ( 171,267)',1,'2017-10-27 16:51:13',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/440499a85fb8781ba2318f92aa10cebf.pdf','pdf','{\"showArticle\": \"N\"}'),(963,75,'169da3960c2f5d8809086641370f0b54',' AND sku.cd_shoe_sku in ( 171,267)',1,'2017-10-27 16:51:41',1,'cblos','/var/www/devshoes.com/mboardReports/tmp/169da3960c2f5d8809086641370f0b54.pdf','pdf','{\"showArticle\": \"Y\"}');
/*!40000 ALTER TABLE `SYSTEM_REPORTS_AUTHORIZATION` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYSTEM_REPORTS_AUTHORIZATION BEFORE INSERT ON SYSTEM_REPORTS_AUTHORIZATION
FOR EACH ROW
BEGIN
    IF NEW.cd_system_reports_authorization IS NULL THEN
        SET NEW.cd_system_reports_authorization = nextval('SYSTEM_REPORTS_AUTHORIZATION');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYSTEM_SETTINGS`
--

DROP TABLE IF EXISTS `SYSTEM_SETTINGS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYSTEM_SETTINGS` (
  `cd_system_settings` int(11) NOT NULL,
  `ds_system_settings` varchar(64) NOT NULL,
  `ds_system_settings_id` varchar(64) DEFAULT NULL,
  `fl_initialize_on_db` char(1) NOT NULL DEFAULT 'N',
  `fl_changeable_by_user` char(1) NOT NULL DEFAULT 'Y',
  `cd_system_settings_group` int(11) DEFAULT NULL,
  `nr_order` int(11) DEFAULT NULL,
  `fl_type_selection` char(1) DEFAULT NULL,
  `fl_translate_options` char(1) NOT NULL DEFAULT 'N',
  `fl_only_for_super_users` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`cd_system_settings`),
  UNIQUE KEY `IUN_SYSTEM_SETTINGS001` (`ds_system_settings`),
  KEY `IDX_SYSTEM_SETTINGS_001` (`ds_system_settings_id`),
  KEY `FKSYSTEM_SETTINGS001` (`cd_system_settings_group`),
  CONSTRAINT `FKSYSTEM_SETTINGS001` FOREIGN KEY (`cd_system_settings_group`) REFERENCES `SYSTEM_SETTINGS_GROUP` (`cd_system_settings_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYSTEM_SETTINGS`
--

LOCK TABLES `SYSTEM_SETTINGS` WRITE;
/*!40000 ALTER TABLE `SYSTEM_SETTINGS` DISABLE KEYS */;
INSERT INTO `SYSTEM_SETTINGS` VALUES (1,'System Language','cd_system_languages','Y','Y',1,2,'D','Y','N'),(2,'Auto Hide Filter','fl_autohide_filter','N','Y',1,3,'C','Y','N'),(3,'Date Format','fl_date_format','N','N',1,NULL,NULL,'N','N'),(4,'Document Repository Root Path','document_repository_path','Y','N',1,NULL,NULL,'N','N'),(5,'Document Repository Temp Path','document_repository_temp_path','N','N',1,NULL,NULL,'N','N'),(6,'Document Repository Thumbs Path','document_repository_thumbs_path','N','N',1,NULL,NULL,'N','N'),(7,'Mboard BIRT Report Folder','mboard_birt_folder','N','N',1,NULL,NULL,'N','N'),(8,'Tomcat BIRT Webserver','mboard_birt_webserver','N','N',1,NULL,NULL,'N','N'),(9,'Local IP Control','local_ip_control','N','N',1,NULL,NULL,'N','N'),(10,'Report Default Format','report_default_format','N','N',1,7,'D','Y','N'),(11,'Skin','system_theme','N','Y',1,1,'D','Y','N'),(12,'Debug Mode','fl_debug_mode','Y','Y',2,10,'C','N','Y'),(13,'Collapse Components by Default','fl_collapse_components','N','N',1,4,'C','Y','N'),(14,'Default View','fl_default_view','N','N',1,5,'D','Y','N'),(15,'Start on DashBoard','fl_start_on_dashboard','N','Y',1,11,'C','Y','N');
/*!40000 ALTER TABLE `SYSTEM_SETTINGS` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYSTEM_SETTINGS BEFORE INSERT ON SYSTEM_SETTINGS
FOR EACH ROW
BEGIN
    IF NEW.cd_system_settings IS NULL THEN
        SET NEW.cd_system_settings = nextval('SYSTEM_SETTINGS');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYSTEM_SETTINGS_GROUP`
--

DROP TABLE IF EXISTS `SYSTEM_SETTINGS_GROUP`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYSTEM_SETTINGS_GROUP` (
  `cd_system_settings_group` int(11) NOT NULL,
  `ds_system_settings_group` varchar(32) NOT NULL,
  `ds_notes` longtext,
  `nr_order` int(11) NOT NULL DEFAULT '999999',
  PRIMARY KEY (`cd_system_settings_group`),
  UNIQUE KEY `IUNSYSTEM_SETTINGS_GROUP001` (`ds_system_settings_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYSTEM_SETTINGS_GROUP`
--

LOCK TABLES `SYSTEM_SETTINGS_GROUP` WRITE;
/*!40000 ALTER TABLE `SYSTEM_SETTINGS_GROUP` DISABLE KEYS */;
INSERT INTO `SYSTEM_SETTINGS_GROUP` VALUES (1,'General',NULL,1),(2,'Super User','Only for Super Users',999);
/*!40000 ALTER TABLE `SYSTEM_SETTINGS_GROUP` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYSTEM_SETTINGS_GROUP BEFORE INSERT ON SYSTEM_SETTINGS_GROUP
FOR EACH ROW
BEGIN
    IF NEW.cd_system_settings_group IS NULL THEN
        SET NEW.cd_system_settings_group = nextval('SYSTEM_SETTINGS_GROUP');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYSTEM_SETTINGS_OPTIONS`
--

DROP TABLE IF EXISTS `SYSTEM_SETTINGS_OPTIONS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYSTEM_SETTINGS_OPTIONS` (
  `cd_system_settings_options` int(11) NOT NULL,
  `ds_system_settings_options` varchar(64) NOT NULL,
  `ds_option_id` varchar(255) NOT NULL,
  `fl_default` char(1) NOT NULL DEFAULT 'N',
  `cd_system_settings` int(11) NOT NULL,
  PRIMARY KEY (`cd_system_settings_options`),
  UNIQUE KEY `IUN_SYSTEM_SETTINGS_OPTONS001` (`ds_option_id`,`cd_system_settings`),
  KEY `FK_SYSTEM_SETTINGS_OPTIONS001` (`cd_system_settings`),
  CONSTRAINT `FK_SYSTEM_SETTINGS_OPTIONS001` FOREIGN KEY (`cd_system_settings`) REFERENCES `SYSTEM_SETTINGS` (`cd_system_settings`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYSTEM_SETTINGS_OPTIONS`
--

LOCK TABLES `SYSTEM_SETTINGS_OPTIONS` WRITE;
/*!40000 ALTER TABLE `SYSTEM_SETTINGS_OPTIONS` DISABLE KEYS */;
INSERT INTO `SYSTEM_SETTINGS_OPTIONS` VALUES (1,'English','1','Y',1),(2,'Portugues','2','N',1),(3,'Chines','3','N',1),(4,'Yes','Y','Y',2),(5,'No','N','N',2),(6,'American (m/d/yyyy)','mm/dd/yyyy;m/d/Y','Y',3),(8,'Europe (d/m/yyyy)','dd/mm/yyyy;d/m/Y','N',3),(10,'Basic Root','/var/www/hrms/document_repository/hrms/','Y',4),(11,'Basic Temp Root','/var/www/hrms/document_repository/hrms/temp/','Y',5),(12,'Basic Thumbs','/var/www/hrms/document_repository/hrms/thumbs/','Y',6),(13,'Basic Path','hrms/','Y',7),(14,'Basic String','http://127.0.0.1:8080/birt-report/run?__report=%1&__format=%2&rep_id=%3&rep_auth=%4','Y',8),(16,'ON','Y','N',9),(17,'OFF','N','Y',9),(18,'PDF','1','Y',10),(19,'XLS','2','N',10),(20,'DOC','3','N',10),(22,'Blue','skin-blue','Y',11),(23,'Blue Light','skin-blue-light','N',11),(24,'Yellow','skin-yellow','N',11),(25,'Yellow Light','skin-yellow-light','N',11),(26,'Green','skin-green','N',11),(27,'Green Light','skin-green-light','N',11),(28,'Purple','skin-purple','N',11),(29,'Purple Light','skin-purple-light','N',11),(31,'Red','skin-red','N',11),(32,'Red Light','skin-red-light','N',11),(33,'Black','skin-black','N',11),(34,'Black Light','skin-black-light','N',11),(35,'Yes','Y','N',12),(36,'No','N','Y',12),(37,'Yes','Y','N',13),(38,'No','N','Y',13),(39,'Grid','G','N',14),(40,'Card','C','N',14),(41,'Thumbs','T','Y',14),(42,'Yes','Y','Y',15),(43,'No','N','N',15);
/*!40000 ALTER TABLE `SYSTEM_SETTINGS_OPTIONS` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYSTEM_SETTINGS_OPTIONS BEFORE INSERT ON SYSTEM_SETTINGS_OPTIONS
FOR EACH ROW
BEGIN
    IF NEW.cd_system_settings_options IS NULL THEN
        SET NEW.cd_system_settings_options = nextval('SYSTEM_SETTINGS_OPTIONS');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYS_COLUMN_FILTER_PRESET`
--

DROP TABLE IF EXISTS `SYS_COLUMN_FILTER_PRESET`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYS_COLUMN_FILTER_PRESET` (
  `cd_sys_column_filter_preset` int(11) NOT NULL,
  `cd_system_product_category` int(11) NOT NULL,
  `cd_human_resource` int(11) NOT NULL,
  `ds_grid_id` longtext NOT NULL,
  `ds_sys_column_filter_preset` longtext NOT NULL,
  `jsonb_column_filter_data` varchar(255) NOT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fl_default` char(1) NOT NULL DEFAULT 'N',
  `cd_human_resource_shared_from` int(11) DEFAULT NULL,
  PRIMARY KEY (`cd_sys_column_filter_preset`),
  UNIQUE KEY `IUNSYS_COLUMN_FILTER_PRESET002` (`cd_system_product_category`,`cd_human_resource`,`ds_grid_id`(255),`ds_sys_column_filter_preset`(255)),
  KEY `FKSYS_COLUMN_FILTER_PRESET001` (`cd_human_resource_shared_from`),
  CONSTRAINT `FKSYS_COLUMN_FILTER_PRESET001` FOREIGN KEY (`cd_human_resource_shared_from`) REFERENCES `HUMAN_RESOURCE` (`cd_human_resource`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYS_COLUMN_FILTER_PRESET`
--

LOCK TABLES `SYS_COLUMN_FILTER_PRESET` WRITE;
/*!40000 ALTER TABLE `SYS_COLUMN_FILTER_PRESET` DISABLE KEYS */;
/*!40000 ALTER TABLE `SYS_COLUMN_FILTER_PRESET` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYS_COLUMN_FILTER_PRESET BEFORE INSERT ON SYS_COLUMN_FILTER_PRESET
FOR EACH ROW
BEGIN
    IF NEW.cd_sys_column_filter_preset IS NULL THEN
        SET NEW.cd_sys_column_filter_preset = nextval('SYS_COLUMN_FILTER_PRESET');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYS_DB_MESSAGES`
--

DROP TABLE IF EXISTS `SYS_DB_MESSAGES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYS_DB_MESSAGES` (
  `cd_sys_db_messages` int(11) NOT NULL,
  `ds_sys_db_messages` longtext NOT NULL,
  `ds_table` varchar(64) NOT NULL,
  `dt_record` time NOT NULL,
  PRIMARY KEY (`cd_sys_db_messages`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYS_DB_MESSAGES`
--

LOCK TABLES `SYS_DB_MESSAGES` WRITE;
/*!40000 ALTER TABLE `SYS_DB_MESSAGES` DISABLE KEYS */;
INSERT INTO `SYS_DB_MESSAGES` VALUES (500002,'Cannot Save Because the Length is area, and cannot have Width information','PRODUCT_QUOTATION_GENERIC','21:49:16'),(500003,'The Product for SOLE MATERIAL does not belong to this Component','CONSTRUCTION','21:49:16'),(500004,'The Product for MID SOLE does not belong to this Component','CONSTRUCTION','21:49:16'),(500005,'The Product for WELT does not belong to this Component','CONSTRUCTION','21:49:16'),(500006,'The Product for HEEL does not belong to this Component','CONSTRUCTION','21:49:16'),(500007,'Product Must be Authorized<br>','...','21:49:16'),(500008,'The Composition is Out of Order','PRODUCT','21:49:16'),(500009,'The Product for INSOLE BINDING does not belong to this Component','PRODUCT','21:49:16'),(500010,'The Product for INSOLE BOARD does not belong to this Component','PRODUCT','21:49:16'),(500011,'The Product for FOAM does not belong to this Component','PRODUCT\n','21:49:16'),(500012,'The Product for TOE PIECE does not belong to this Component','PRODUCT','21:49:16'),(500013,'The Product for TOE BOX does not belong to this Component','PRODUCT','21:49:16'),(500014,'The Product for TOP LIFT does not belong to this Component','PRODUCT','21:49:16'),(500015,'The Product for  COUNTER POCKET does not belong to this Component','PRODUCT','21:49:16'),(500016,'Missing Sole Type Information','SHOE_SAMPLE_PRICE','21:49:16'),(500017,'Missing Upper Type Information','SHOE_SAMPLE_PRICE','21:49:16'),(500018,'Missing Customer Information','SHOE_SAMPLE_PRICE','21:49:16'),(500019,'Missing Division Brand Information','SHOE_SAMPLE_PRICE','21:49:16'),(500020,'Missing Shoe Category Information','SHOE_SAMPLE_PRICE','21:49:16'),(500021,'Pairs Packed and Pairs Cancel cannot be greater than Total Distrubtuion Pairs','SHOE_SAMPLE_REQUEST_SKU_DELIVERY','21:49:16'),(500022,'Pairs Defined cannot be greater than Pairs Cancel','SHOE_SAMPLE_REQUEST_SKU_DELIVERY','21:49:16'),(500023,'Pairs Packed cannot be greater than Pairs Available','SHOE_SAMPLE_REQUEST_SKU_DELIVERY','21:49:16'),(500024,'Cannot change information because the Packing List is done','SHOE_SAMPLE_PL_CASE_DELIVERY','21:49:16'),(500025,'Cannot change Case Number it has Pairs and the Packing is Done. Please remove the Pairs first','SHOE_SAMPLE_PL_CASE_DELIVERY','21:49:16'),(500026,'Division from Customer is mismatching with the one selected on Sample Request','SHOE_SAMPLE_REQUEST','21:49:16'),(500027,'Cannot change information because the Sample Invoice is done','SHOE_SAMPLE_INVOICE','21:49:16'),(500028,'Cannot remove Done from Packing List because it is related to Invoice','SHOE_SAMPLE_PACKING_LIST','21:49:16'),(500029,'Cannot change information because the Packing List is done','SHOE_SAMPLE_PACKING_LIST','21:49:16'),(500030,'Cannot change information because this Sample Price is already related to Invoice. Create a new Build','SHOE_SAMPLE_PRICE','21:49:16'),(500031,'Cannot change information because this Cancellation is already attached to Invoice ','SHOE_SMP_REQ_SKU_DELIVERY_PENALTY','21:49:16'),(500032,'Cannot change Component because it is currently related to an existing Level','SKUs','21:49:16'),(500033,'Division from Customer is mismatching with the one selected on Purchase Order','SHOE_PURCHASE_ORDER','21:49:16'),(500034,'Division Brand not related to Division','ANY','21:49:16'),(500035,'Cannot Delete SKU because it is already related to a PO','SHOE_PRICE_X_SHOE_SKU','21:49:16'),(500036,'Cannot change Price because it is already related to a PO','SHOE_PRICE','21:49:16'),(500037,'Cannot delete SKU because it is already Sent to FACTORY ON TIME','SHOE_PURCHASE_ORDER_SKU','21:49:16'),(500038,'CANNOT delete this Process because it is System Related','SHOE_PROCESS_ITEM','21:49:16'),(500039,'The Currency FROM and TO cannot be the same','CURRENCY_RATE','21:49:16'),(500040,'The End Date must be Greater than Start Date','SEASON','21:49:16'),(500041,'Cannot change information because the Invoice is done','SHOE_PURCHASE_ORDER_SKU_INVOICE','21:49:16'),(500042,'This Cost Sheet Type demands Factory','SHOE_COST_SHEET_SKU','21:49:16'),(500043,'Cannot save Sample because the Division does not have Shipping Type default','SHOE_SAMPLE_REQUEST	','21:49:16');
/*!40000 ALTER TABLE `SYS_DB_MESSAGES` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYS_DB_MESSAGES BEFORE INSERT ON SYS_DB_MESSAGES
FOR EACH ROW
BEGIN
    IF NEW.cd_sys_db_messages IS NULL THEN
        SET NEW.cd_sys_db_messages = nextval('SYS_DB_MESSAGES');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYS_DB_MESSAGES_LANGUAGES`
--

DROP TABLE IF EXISTS `SYS_DB_MESSAGES_LANGUAGES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYS_DB_MESSAGES_LANGUAGES` (
  `cd_sys_db_messages_languages` int(11) NOT NULL,
  `cd_sys_db_messages` int(11) NOT NULL,
  `cd_system_languages` int(11) NOT NULL,
  `ds_sys_db_messages_languages` longtext NOT NULL,
  `dt_record` time NOT NULL,
  PRIMARY KEY (`cd_sys_db_messages_languages`),
  UNIQUE KEY `IUN_DB_SYS_DB_MESSAGES_LANGUAGE001` (`cd_sys_db_messages`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYS_DB_MESSAGES_LANGUAGES`
--

LOCK TABLES `SYS_DB_MESSAGES_LANGUAGES` WRITE;
/*!40000 ALTER TABLE `SYS_DB_MESSAGES_LANGUAGES` DISABLE KEYS */;
/*!40000 ALTER TABLE `SYS_DB_MESSAGES_LANGUAGES` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYS_DB_MESSAGES_LANGUAGES BEFORE INSERT ON SYS_DB_MESSAGES_LANGUAGES
FOR EACH ROW
BEGIN
    IF NEW.cd_sys_db_messages_languages IS NULL THEN
        SET NEW.cd_sys_db_messages_languages = nextval('SYS_DB_MESSAGES_LANGUAGES');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYS_DB_MESSAGES_LANGUAGES_LOCAL`
--

DROP TABLE IF EXISTS `SYS_DB_MESSAGES_LANGUAGES_LOCAL`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYS_DB_MESSAGES_LANGUAGES_LOCAL` (
  `cd_sys_db_messages_languages_local` int(11) NOT NULL,
  `cd_sys_db_messages_local` int(11) NOT NULL,
  `cd_system_languages` int(11) NOT NULL,
  `ds_sys_db_messages_languages` longtext NOT NULL,
  `dt_record` time NOT NULL,
  PRIMARY KEY (`cd_sys_db_messages_languages_local`),
  UNIQUE KEY `IUN_DB_SYS_DB_MESSAGES_LANGUAGE001` (`cd_sys_db_messages_local`,`cd_system_languages`),
  CONSTRAINT `FK_SYS_DB_MESSAGES_LANGUAGUES001_1` FOREIGN KEY (`cd_sys_db_messages_local`) REFERENCES `SYS_DB_MESSAGES_LOCAL` (`cd_sys_db_messages_local`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYS_DB_MESSAGES_LANGUAGES_LOCAL`
--

LOCK TABLES `SYS_DB_MESSAGES_LANGUAGES_LOCAL` WRITE;
/*!40000 ALTER TABLE `SYS_DB_MESSAGES_LANGUAGES_LOCAL` DISABLE KEYS */;
/*!40000 ALTER TABLE `SYS_DB_MESSAGES_LANGUAGES_LOCAL` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYS_DB_MESSAGES_LANGUAGES_LOCAL BEFORE INSERT ON SYS_DB_MESSAGES_LANGUAGES_LOCAL
FOR EACH ROW
BEGIN
    IF NEW.cd_sys_db_messages_languages_local IS NULL THEN
        SET NEW.cd_sys_db_messages_languages_local = nextval('SYS_DB_MESSAGES_LANGUAGES_LOCAL');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYS_DB_MESSAGES_LOCAL`
--

DROP TABLE IF EXISTS `SYS_DB_MESSAGES_LOCAL`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYS_DB_MESSAGES_LOCAL` (
  `cd_sys_db_messages_local` int(11) NOT NULL,
  `ds_sys_db_messages_local` longtext NOT NULL,
  `ds_table` varchar(64) NOT NULL,
  `dt_record` time NOT NULL,
  PRIMARY KEY (`cd_sys_db_messages_local`),
  UNIQUE KEY `IUN_SYS_DB_MESSAGES001` (`cd_sys_db_messages_local`,`ds_sys_db_messages_local`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYS_DB_MESSAGES_LOCAL`
--

LOCK TABLES `SYS_DB_MESSAGES_LOCAL` WRITE;
/*!40000 ALTER TABLE `SYS_DB_MESSAGES_LOCAL` DISABLE KEYS */;
/*!40000 ALTER TABLE `SYS_DB_MESSAGES_LOCAL` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYS_DB_MESSAGES_LOCAL BEFORE INSERT ON SYS_DB_MESSAGES_LOCAL
FOR EACH ROW
BEGIN
    IF NEW.cd_sys_db_messages_local IS NULL THEN
        SET NEW.cd_sys_db_messages_local = nextval('SYS_DB_MESSAGES_LOCAL');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `SYS_FILTER_QUERIES`
--

DROP TABLE IF EXISTS `SYS_FILTER_QUERIES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYS_FILTER_QUERIES` (
  `cd_sys_filter_queries` int(11) NOT NULL,
  `ds_sys_filter_queries` longtext NOT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `IDX_SYS_FILTER_QUERIES001` (`ds_sys_filter_queries`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYS_FILTER_QUERIES`
--

LOCK TABLES `SYS_FILTER_QUERIES` WRITE;
/*!40000 ALTER TABLE `SYS_FILTER_QUERIES` DISABLE KEYS */;
/*!40000 ALTER TABLE `SYS_FILTER_QUERIES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SYS_FLAG_USED`
--

DROP TABLE IF EXISTS `SYS_FLAG_USED`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SYS_FLAG_USED` (
  `cd_sys_flag_used` int(11) NOT NULL,
  `ds_sys_flag_used` char(16) NOT NULL,
  PRIMARY KEY (`cd_sys_flag_used`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SYS_FLAG_USED`
--

LOCK TABLES `SYS_FLAG_USED` WRITE;
/*!40000 ALTER TABLE `SYS_FLAG_USED` DISABLE KEYS */;
INSERT INTO `SYS_FLAG_USED` VALUES (1,'USED'),(2,'NOT USED'),(3,'MANDATORY');
/*!40000 ALTER TABLE `SYS_FLAG_USED` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_SYS_FLAG_USED BEFORE INSERT ON SYS_FLAG_USED
FOR EACH ROW
BEGIN
    IF NEW.cd_sys_flag_used IS NULL THEN
        SET NEW.cd_sys_flag_used = nextval('SYS_FLAG_USED');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `TABLES_LOG`
--

DROP TABLE IF EXISTS `TABLES_LOG`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TABLES_LOG` (
  `cd_tables_log` bigint(20) NOT NULL,
  `cd_tables_log_oid` varchar(255) NOT NULL,
  `ds_table_name` varchar(64) NOT NULL,
  `cd_type_log` varchar(1) NOT NULL,
  `cd_pk` int(11) NOT NULL,
  `ds_column_name` varchar(64) NOT NULL,
  `dt_record` datetime NOT NULL,
  `ds_data_before` varchar(255) DEFAULT NULL,
  `ds_data_after` varchar(255) DEFAULT NULL,
  `ds_username` varchar(64) NOT NULL,
  `ds_hstore_ins_del` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cd_tables_log`),
  UNIQUE KEY `IUNTABLES_LOG001` (`cd_tables_log_oid`,`cd_pk`,`dt_record`),
  KEY `IDXTABLES_LOG` (`cd_tables_log_oid`,`cd_pk`,`dt_record`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TABLES_LOG`
--

LOCK TABLES `TABLES_LOG` WRITE;
/*!40000 ALTER TABLE `TABLES_LOG` DISABLE KEYS */;
/*!40000 ALTER TABLE `TABLES_LOG` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_TABLES_LOG BEFORE INSERT ON TABLES_LOG
FOR EACH ROW
BEGIN
    IF NEW.cd_tables_log IS NULL THEN
        SET NEW.cd_tables_log = nextval('TABLES_LOG');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `TABLES_LOG_MASTER`
--

DROP TABLE IF EXISTS `TABLES_LOG_MASTER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TABLES_LOG_MASTER` (
  `cd_tables_log` bigint(20) NOT NULL,
  `cd_tables_log_oid` varchar(255) NOT NULL,
  `ds_table_name` varchar(64) NOT NULL,
  `cd_type_log` varchar(1) NOT NULL,
  `cd_pk` int(11) NOT NULL,
  `ds_column_name` varchar(64) NOT NULL,
  `dt_record` datetime NOT NULL,
  `ds_data_before` longtext,
  `ds_data_after` longtext,
  `ds_username` varchar(64) NOT NULL,
  `ds_hstore_ins_del` varchar(255) DEFAULT NULL,
  `cd_fk_code_old` int(11) DEFAULT NULL,
  `cd_fk_code_new` int(11) DEFAULT NULL,
  PRIMARY KEY (`cd_tables_log`),
  UNIQUE KEY `IUNTABLES_LOG_MASTER_LOG001` (`cd_tables_log_oid`,`cd_pk`,`dt_record`),
  KEY `IDX_TABLES_LOG_MASTER001` (`ds_data_before`(255)),
  KEY `IDX_TABLES_LOG_MASTER002` (`ds_data_after`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TABLES_LOG_MASTER`
--

LOCK TABLES `TABLES_LOG_MASTER` WRITE;
/*!40000 ALTER TABLE `TABLES_LOG_MASTER` DISABLE KEYS */;
/*!40000 ALTER TABLE `TABLES_LOG_MASTER` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_TABLES_LOG_MASTER BEFORE INSERT ON TABLES_LOG_MASTER
FOR EACH ROW
BEGIN
    IF NEW.cd_tables_log IS NULL THEN
        SET NEW.cd_tables_log = nextval('TABLES_LOG_MASTER');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `TRAVEL_AGENCY`
--

DROP TABLE IF EXISTS `TRAVEL_AGENCY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TRAVEL_AGENCY` (
  `cd_travel_agency` int(11) NOT NULL,
  `ds_travel_agency` varchar(64) NOT NULL,
  `dt_deactivated` date DEFAULT NULL,
  `dt_record` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cd_travel_agency`),
  UNIQUE KEY `IUN_TRAVEL_AGENCY001` (`ds_travel_agency`),
  KEY `IDXTRAVEL_AGENCY001` (`ds_travel_agency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TRAVEL_AGENCY`
--

LOCK TABLES `TRAVEL_AGENCY` WRITE;
/*!40000 ALTER TABLE `TRAVEL_AGENCY` DISABLE KEYS */;
INSERT INTO `TRAVEL_AGENCY` VALUES (1,'AGENCY 1',NULL,'2017-12-15 10:48:13'),(2,'AGENCY 2',NULL,'2017-12-15 10:48:13');
/*!40000 ALTER TABLE `TRAVEL_AGENCY` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_TRAVEL_AGENCY BEFORE INSERT ON TRAVEL_AGENCY
FOR EACH ROW
BEGIN
    IF NEW.cd_travel_agency IS NULL THEN
        SET NEW.cd_travel_agency = nextval('TRAVEL_AGENCY');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `TYPE_SYS_PERMISSION`
--

DROP TABLE IF EXISTS `TYPE_SYS_PERMISSION`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TYPE_SYS_PERMISSION` (
  `cd_type_sys_permission` int(11) NOT NULL,
  `ds_type_sys_permission` varchar(64) NOT NULL,
  `dt_deactivated` date DEFAULT NULL,
  PRIMARY KEY (`cd_type_sys_permission`),
  UNIQUE KEY `UNTYPE_SYS_PARAMETER01` (`ds_type_sys_permission`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TYPE_SYS_PERMISSION`
--

LOCK TABLES `TYPE_SYS_PERMISSION` WRITE;
/*!40000 ALTER TABLE `TYPE_SYS_PERMISSION` DISABLE KEYS */;
INSERT INTO `TYPE_SYS_PERMISSION` VALUES (1,'PRICE',NULL),(2,'DEVELOPMENT',NULL),(3,'ARTICLE',NULL);
/*!40000 ALTER TABLE `TYPE_SYS_PERMISSION` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_TYPE_SYS_PERMISSION BEFORE INSERT ON TYPE_SYS_PERMISSION
FOR EACH ROW
BEGIN
    IF NEW.cd_type_sys_permission IS NULL THEN
        SET NEW.cd_type_sys_permission = nextval('TYPE_SYS_PERMISSION');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `UNIT_MEASURE`
--

DROP TABLE IF EXISTS `UNIT_MEASURE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UNIT_MEASURE` (
  `cd_unit_measure` int(11) NOT NULL,
  `ds_unit_measure` varchar(64) NOT NULL,
  `cd_unit_measure_type` int(11) NOT NULL,
  `ds_unit_measure_short` varchar(8) NOT NULL,
  `ds_unit_measure_symbol` varchar(8) NOT NULL,
  `nr_factor_for_convertion` decimal(16,8) DEFAULT '0.00000000',
  `cd_unit_measure_lenght_base` int(11) DEFAULT NULL,
  PRIMARY KEY (`cd_unit_measure`),
  UNIQUE KEY `IUNUNIT_MEASURE001` (`ds_unit_measure`),
  UNIQUE KEY `IUNUNIT_MEASURE002` (`ds_unit_measure_short`),
  KEY `FKUNIT_MEASURE001` (`cd_unit_measure_type`),
  KEY `FKUNIT_MEASURE002` (`cd_unit_measure_lenght_base`),
  CONSTRAINT `FKUNIT_MEASURE001` FOREIGN KEY (`cd_unit_measure_type`) REFERENCES `UNIT_MEASURE_TYPE` (`cd_unit_measure_type`),
  CONSTRAINT `FKUNIT_MEASURE002` FOREIGN KEY (`cd_unit_measure_lenght_base`) REFERENCES `UNIT_MEASURE` (`cd_unit_measure`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UNIT_MEASURE`
--

LOCK TABLES `UNIT_MEASURE` WRITE;
/*!40000 ALTER TABLE `UNIT_MEASURE` DISABLE KEYS */;
INSERT INTO `UNIT_MEASURE` VALUES (0,'UNKNOWN',4,'UNKNOWN','UNKNOWN',0.00000000,NULL),(1,'PAIRS',3,'PAIR','PR',2.00000000,NULL),(2,'CORRUGATED CARTONS',3,'CARTON','CARTON',1.00000000,NULL),(3,'UNIT',3,'UNIT','UNIT',1.00000000,NULL),(4,'SQUARE FEET',4,'SQFT','FT2',1.00000000,6),(5,'CENTIMETER',1,'CM','CM',30.48000000,NULL),(6,'FOOT',1,'FOOT','FT',1.00000000,NULL),(7,'INCH',1,'INCH','INCH',12.00000000,NULL),(8,'METER',1,'MT','MT',0.30480000,NULL),(9,'YARD',1,'YARD','YD',0.33330000,NULL),(10,'GRAMS',2,'GRAMS','G',1.00000000,NULL),(11,'PIECE',3,'PIECE','PC',1.00000000,NULL),(12,'SET',3,'SET','SET',1.00000000,NULL),(13,'SQUARE INCHES',4,'SQIN','IN2',144.00000000,7),(14,'SQUARE METERS',4,'SQMT','MT2',0.09290304,8),(15,'SQUARE YARD',4,'SQYD','YD2',0.11110000,9),(16,'SQUARE CENTIMETER',4,'SQCM','CM2',929.03040000,5);
/*!40000 ALTER TABLE `UNIT_MEASURE` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_UNIT_MEASURE BEFORE INSERT ON UNIT_MEASURE
FOR EACH ROW
BEGIN
    IF NEW.cd_unit_measure IS NULL THEN
        SET NEW.cd_unit_measure = nextval('UNIT_MEASURE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `UNIT_MEASURE_TYPE`
--

DROP TABLE IF EXISTS `UNIT_MEASURE_TYPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UNIT_MEASURE_TYPE` (
  `cd_unit_measure_type` int(11) NOT NULL,
  `ds_unit_measure_type` varchar(64) NOT NULL,
  `fl_is_length` varchar(1) DEFAULT NULL,
  `cd_unit_measure_reference` int(11) DEFAULT NULL,
  PRIMARY KEY (`cd_unit_measure_type`),
  UNIQUE KEY `IUNUNIT_MEASURE_TYPE001` (`ds_unit_measure_type`),
  KEY `FKUNIT_MEASURE_TYPE001` (`cd_unit_measure_reference`),
  CONSTRAINT `FKUNIT_MEASURE_TYPE001` FOREIGN KEY (`cd_unit_measure_reference`) REFERENCES `UNIT_MEASURE` (`cd_unit_measure`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UNIT_MEASURE_TYPE`
--

LOCK TABLES `UNIT_MEASURE_TYPE` WRITE;
/*!40000 ALTER TABLE `UNIT_MEASURE_TYPE` DISABLE KEYS */;
INSERT INTO `UNIT_MEASURE_TYPE` VALUES (1,'LENGTH','Y',6),(2,'WEIGHT 1','N',10),(3,'UNIT 1','N',11),(4,'AREA','N',4),(11,'TEST',NULL,2);
/*!40000 ALTER TABLE `UNIT_MEASURE_TYPE` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_UNIT_MEASURE_TYPE BEFORE INSERT ON UNIT_MEASURE_TYPE
FOR EACH ROW
BEGIN
    IF NEW.cd_unit_measure_type IS NULL THEN
        SET NEW.cd_unit_measure_type = nextval('UNIT_MEASURE_TYPE');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Table structure for table `sequence_data`
--

DROP TABLE IF EXISTS `sequence_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sequence_data` (
  `sequence_name` varchar(100) NOT NULL,
  `sequence_increment` int(11) unsigned NOT NULL DEFAULT '1',
  `sequence_min_value` int(11) unsigned NOT NULL DEFAULT '1',
  `sequence_max_value` bigint(20) unsigned NOT NULL DEFAULT '18446744073709551615',
  `sequence_cur_value` bigint(20) unsigned DEFAULT '1',
  `sequence_cycle` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sequence_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sequence_data`
--

LOCK TABLES `sequence_data` WRITE;
/*!40000 ALTER TABLE `sequence_data` DISABLE KEYS */;
INSERT INTO `sequence_data` VALUES ('ADDRESS',1,1,18446744073709551615,362,0),('APPROVAL_STATUS',1,1,18446744073709551615,1,0),('CITY',1,1,18446744073709551615,5,0),('CIVIL_STATUS',1,1,18446744073709551615,3,0),('CONTACT',1,1,18446744073709551615,237,0),('CONTACT_TYPE',1,1,18446744073709551615,10,0),('COUNTRY',1,1,18446744073709551615,252,0),('CURRENCY',1,1,18446744073709551615,308,0),('CURRENCY_RATE',1,1,18446744073709551615,3,0),('DEPARTMENT',1,1,18446744073709551615,16,0),('DEPENDENTS',1,1,18446744073709551615,258,0),('DIVISION',1,1,18446744073709551615,8,0),('DIVISION_BRAND',1,1,18446744073709551615,2,0),('DIVISION_X_DIVISION_BRAND',1,1,18446744073709551615,3,0),('DOCUMENT',1,1,18446744073709551615,217,0),('DOCUMENT_FILE',1,1,18446744073709551615,1,0),('DOCUMENT_REPOSITORY',1,1,18446744073709551615,1,0),('DOCUMENT_REPOSITORY_CATEGORY',1,1,18446744073709551615,6,0),('DOCUMENT_REPOSITORY_TYPE',1,1,18446744073709551615,22,0),('DOCUMENT_TYPE',1,1,18446744073709551615,10,0),('DUMMY',1,1,18446744073709551615,NULL,0),('EDUCATION',1,1,18446744073709551615,2,0),('EMPLOYEE',1,1,18446744073709551615,341,0),('EMPLOYEE_POSITION',1,1,18446744073709551615,8,0),('EMPLOYEE_TYPE',1,1,18446744073709551615,6,0),('EMPLOYEE_X_CONTACT',1,1,18446744073709551615,104,0),('EMPLOYEE_X_DEPENDENTS',1,1,18446744073709551615,1,0),('EMPLOYEE_X_DOCUMENTS',1,1,18446744073709551615,62,0),('GENDER',1,1,18446744073709551615,3,0),('HR_SYSTEM_DASHBOARD_WIDGET_PARAM',1,1,18446744073709551615,28,0),('HR_SYSTEM_SETTINGS_OPTIONS',1,1,18446744073709551615,21,0),('HR_TYPE',1,1,18446744073709551615,133422,0),('HUMAN_RESOURCE',1,1,18446744073709551615,359,0),('HUMAN_RESOURCE_MENU',1,1,18446744073709551615,2,0),('HUMAN_RESOURCE_X_SYSTEM_PRODUCT_CATEGORY',1,1,18446744073709551615,1,0),('JOBS',1,1,18446744073709551615,22,0),('JOBS_HUMAN_RESOURCE',1,1,18446744073709551615,57,0),('JOBS_MENU',1,1,18446744073709551615,1015,0),('JOBS_SYSTEM_PERMISSION',1,1,18446744073709551615,13,0),('JOBS_X_SYSTEM_PRODUCT_CATEGORY',1,1,18446744073709551615,6,0),('LEAVES',1,1,18446744073709551615,1,0),('LEAVE_TYPE',1,1,18446744073709551615,6,0),('MENU_SPECIFIC',1,1,18446744073709551615,1,0),('PERSONAL_INFO',1,1,18446744073709551615,655,0),('PROVINCE',1,1,18446744073709551615,6,0),('RECORD_GEN',1,1,18446744073709551615,NULL,0),('RELATIONSHIP_TYPE',1,1,18446744073709551615,7,0),('RESIDENCE_TYPE',1,1,18446744073709551615,2,0),('SESSION_LOG',1,1,18446744073709551615,104,0),('SYSTEM_COMPANY',1,1,18446744073709551615,2,0),('SYSTEM_DASHBOARD_WIDGET',1,1,18446744073709551615,1,0),('SYSTEM_DB_UPDATES',1,1,18446744073709551615,1,0),('SYSTEM_DICTIONARY_MAIN',1,1,18446744073709551615,1621,0),('SYSTEM_DICTIONARY_TRANSLATION',1,1,18446744073709551615,1,0),('SYSTEM_DICTIONARY_USERDEFINED',1,1,18446744073709551615,1,0),('SYSTEM_LABEL_TYPE',1,1,18446744073709551615,4,0),('SYSTEM_LANGUAGES',1,1,18446744073709551615,4,0),('SYSTEM_MENU',1,1,18446744073709551615,1026,0),('SYSTEM_PARAMETERS',1,1,18446744073709551615,43,0),('SYSTEM_PERMISSION',1,1,18446744073709551615,8,0),('SYSTEM_PRODUCT_CATEGORY',1,1,18446744073709551615,2,0),('SYSTEM_RELATIONS',1,1,18446744073709551615,257,0),('SYSTEM_REPORTS',1,1,18446744073709551615,85,0),('SYSTEM_REPORTS_AUTHORIZATION',1,1,18446744073709551615,964,0),('SYSTEM_SETTINGS',1,1,18446744073709551615,16,0),('SYSTEM_SETTINGS_GROUP',1,1,18446744073709551615,3,0),('SYSTEM_SETTINGS_OPTIONS',1,1,18446744073709551615,44,0),('SYS_COLUMN_FILTER_PRESET',1,1,18446744073709551615,1,0),('SYS_DB_MESSAGES',1,1,18446744073709551615,500044,0),('SYS_DB_MESSAGES_LANGUAGES',1,1,18446744073709551615,1,0),('SYS_DB_MESSAGES_LANGUAGES_LOCAL',1,1,18446744073709551615,1,0),('SYS_DB_MESSAGES_LOCAL',1,1,18446744073709551615,1,0),('SYS_FILTER_QUERIES',1,1,18446744073709551615,NULL,0),('SYS_FLAG_USED',1,1,18446744073709551615,4,0),('TABLES_LOG',1,1,18446744073709551615,1,0),('TABLES_LOG_MASTER',1,1,18446744073709551615,1,0),('TYPE_SYS_PERMISSION',1,1,18446744073709551615,4,0),('UNIT_MEASURE',1,1,18446744073709551615,17,0),('UNIT_MEASURE_TYPE',1,1,18446744073709551615,12,0),('ADDRESS_TYPE',1,1,18446744073709551615,3,0),('\"SESSION_LOG_cd_session_log_seq\"',1,1,18446744073709551615,8,0),('EMPLOYEE_X_ADDRESS',1,1,18446744073709551615,220,0),('\"DOCUMENT_FILE_cd_document_file_seq\"',1,1,18446744073709551615,8,0),('\"DOCUMENT_REPOSITORY_cd_document_repository_seq\"',1,1,18446744073709551615,14,0),('EMPLOYEE_X_DOCUMENT_REPOSITORY',1,1,18446744073709551615,7,0),('DOCUMENT_X_DOCUMENT_REPOSITORY',1,1,18446744073709551615,5,0),('LOCATION',1,1,18446744073709551615,5,0),('EMPLOYEE_TAGS',1,1,18446744073709551615,15,0),('EMPLOYEE_X_EMPLOYEE_TAGS',1,1,18446744073709551615,10,0),('DEPENDENTS_X_ADDRESS',1,1,18446744073709551615,142,0),('DEPENDENTS_X_CONTACT',1,1,18446744073709551615,134,0),('DEPENDENTS_X_DOCUMENTS',1,1,18446744073709551615,140,0),('BANK',1,1,18446744073709551615,2,0),('BANK_BRANCH',1,1,18446744073709551615,7,0),('BENEFIT_TYPE',1,1,18446744073709551615,12,0),('EMPLOYEE_X_BANK_BRANCH',1,1,18446744073709551615,65,0),('EMPLOYEE_BANK_BRANCH_X_BENEFIT_TYPE',1,1,18446744073709551615,9,0),('ASSET_TYPE',1,1,18446744073709551615,2,0),('EMPLOYEE_ASSETS',1,1,18446744073709551615,5,0),('EMPLOYEE_POSITION_X_BENEFIT_TYPE',1,1,18446744073709551615,10,0),('EMPLOYEE_BENEFIT_TYPE',1,1,18446744073709551615,38,0),('MOVEMENTS',1,1,18446744073709551615,68,0),('FLIGHT_TICKET',1,1,18446744073709551615,11,0),('TRAVEL_AGENCY',1,1,18446744073709551615,3,0),('FLIGHT_COMPANY',1,1,18446744073709551615,2,0),('EXPENSE_TYPE',1,1,18446744073709551615,3,0),('EXPENSE',1,1,18446744073709551615,9,0),('EXPENSE_ITEM',1,1,18446744073709551615,54,0),('PAYMENT_TYPE',1,1,18446744073709551615,2,0),('EXPENSE_ITEM_X_DOCUMENTS',1,1,18446744073709551615,17,0);
/*!40000 ALTER TABLE `sequence_data` ENABLE KEYS */;
UNLOCK TABLES;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'PIPES_AS_CONCAT' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`hrms_admin`@`%`*/ /*!50003 TRIGGER ins_before_sequence_data BEFORE INSERT ON sequence_data
FOR EACH ROW
BEGIN
    IF NEW.sequence_name IS NULL THEN
        SET NEW.sequence_name = nextval('sequence_data');
     END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Dumping routines for database 'hrms'
--
/*!50003 DROP FUNCTION IF EXISTS `checkMenuPermission` */;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`hrms_admin`@`%` FUNCTION `checkMenuPermission`(
    p_var_controller varchar(60)
) RETURNS varchar(60) CHARSET latin1
BEGIN
    DECLARE v_cd_human_resource integer;
    DECLARE v_cd_menu integer;
    DECLARE v_result  varchar(60);
    DECLARE v_fl_always_available char(1);
    DECLARE v_fl_super_user char(1);
   
   SELECT getvar('cd_human_resource') INTO v_cd_human_resource;

   SELECT fl_super_user
     INTO v_fl_super_user
    FROM HUMAN_RESOURCE
   WHERE cd_human_resource = v_cd_human_resource;

   SELECT min(cd_menu), min(fl_always_available)
     INTO v_cd_menu, v_fl_always_available
     FROM MENU
    WHERE ds_controller = p_var_controller
       AND ( fl_only_for_super_users = 'N' OR v_fl_super_user = 'Y' )
       OR ds_controller like '%' || p_var_controller || '%';

    IF v_fl_always_available = 'Y' THEN
      RETURN 'Y';
    END IF;

   SELECT CASE 
          WHEN EXISTS ( SELECT 1 
                         FROM HUMAN_RESOURCE h
                         WHERE h.cd_human_resource = v_cd_human_resource 
                           AND EXISTS ( SELECT 1
                                          FROM HUMAN_RESOURCE_MENU hm
                                         WHERE hm.cd_human_resource = v_cd_human_resource
                                           AND hm.cd_menu              = v_cd_menu
                                       )
                           OR EXISTS ( SELECT 1
                                         FROM JOBS_HUMAN_RESOURCE jh,
                                              JOBS_MENU jm,
                                              JOBS j
                                        WHERE jh.cd_human_resource = v_cd_human_resource
                                          AND jm.cd_jobs           = jh.cd_jobs
                                          AND jm.cd_menu           = v_cd_menu
                                          AND j.cd_jobs            = jh.cd_jobs
                                          AND j.dt_deactivated IS NULL
                                     )

                         ) THEN 'Y' ELSE retDescTranslated ('You have no rights to this Option', null) END
    INTO v_result;





return v_result;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;
/*!50003 DROP FUNCTION IF EXISTS `datedbtogrid` */;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`hrms_admin`@`%` FUNCTION `datedbtogrid`(
    p_var_name datetime
) RETURNS varchar(10) CHARSET latin1
BEGIN
    DECLARE v_result varchar (10);


    IF p_var_name IS NULL THEN
       SET v_result = '';
    ELSE
      SET v_result = date_format(p_var_name, '%m/%d/%Y');
    END IF;

return v_result;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;
/*!50003 DROP FUNCTION IF EXISTS `getFakeId` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`hrms_admin`@`%` FUNCTION `getFakeId`() RETURNS int(11)
    DETERMINISTIC
begin
return if(@fakeId, @fakeId:=@fakeId+1, @fakeId:=1);
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `getUserPermission` */;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`hrms_admin`@`%` FUNCTION `getUserPermission`(
    p_parameter   text,
    p_cd_username integer
) RETURNS text CHARSET latin1
BEGIN

    DECLARE v_result text;

    SELECT 
     CASE WHEN EXISTS ( SELECT 1
                        FROM JOBS_HUMAN_RESOURCE h,
                             JOBS_SYSTEM_PERMISSION p,
                             SYSTEM_PERMISSION s

   WHERE h.cd_human_resource = p_cd_username
    AND p.cd_jobs = h.cd_jobs
    AND s.cd_system_permission = p.cd_system_permission
    AND s.ds_system_permission_id = p_parameter
   ) THEN 'Y' ELSE 'N' END
   INTO v_result;   

 
   return v_result;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;
/*!50003 DROP FUNCTION IF EXISTS `getvar` */;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`hrms_admin`@`%` FUNCTION `getvar`(
    p_var_name varchar(100)
) RETURNS varchar(100) CHARSET latin1
BEGIN
   DECLARE v_result varchar (100);

    select var_value
    into v_result
    from session_var_tbl
    where var_name = p_var_name;

return v_result;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;
/*!50003 DROP FUNCTION IF EXISTS `nextval` */;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`hrms_admin`@`%` FUNCTION `nextval`(`seq_name` varchar(100)) RETURNS bigint(20)
BEGIN
    DECLARE cur_val bigint(20);
 
    SELECT
        sequence_cur_value INTO cur_val
    FROM
        sequence_data
    WHERE
        sequence_name = seq_name
    ;

    if ROW_COUNT() = 0 then
        insert into sequence_data (sequence_name)
        values (seq_name);
        SET cur_val = 1;
    end if;
    
    
 
    IF cur_val IS NOT NULL THEN
        UPDATE
            sequence_data
        SET
            sequence_cur_value = IF (
                (sequence_cur_value + sequence_increment) > sequence_max_value,
                IF (
                    sequence_cycle = TRUE,
                    sequence_min_value,
                    NULL
                ),
                sequence_cur_value + sequence_increment
            )
        WHERE
            sequence_name = seq_name
        ;
    END IF;
 
    RETURN cur_val;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;
/*!50003 DROP FUNCTION IF EXISTS `retDescTranslated` */;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`hrms_admin`@`%` FUNCTION `retDescTranslated`(
    p_description text,
    p_cd_system_languages integer
) RETURNS varchar(1000) CHARSET latin1
BEGIN
    DECLARE
    v_cd_system_language integer;
    DECLARE
    v_result varchar(1000);

   return retDescTranslatedNew(p_description, p_cd_system_languages);

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;
/*!50003 DROP FUNCTION IF EXISTS `retDescTranslatedNew` */;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`hrms_admin`@`%` FUNCTION `retDescTranslatedNew`(
    p_description varchar(1000),
    p_cd_system_languages integer
) RETURNS varchar(1000) CHARSET latin1
BEGIN
DECLARE v_cd_system_language integer;
DECLARE v_result varchar(1000);
DECLARE v_cd_system_dictionary_main  bigint;
DECLARE v_fl_default                 char(1);
DECLARE vmsg varchar(1000);

   IF p_description = '' THEN
      RETURN '';
   END IF;

   SET v_cd_system_dictionary_main = NULL;

   SELECT cd_system_dictionary_main
     INTO v_cd_system_dictionary_main
     FROM SYSTEM_DICTIONARY_MAIN
    WHERE ds_system_dictionary_main = p_description;

   IF v_cd_system_dictionary_main IS NULL THEN

        SET vmsg = CONCAT('70001 dentro ',  coalesce(v_cd_system_dictionary_main, -1), ' - ', p_description);

          -- SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = vmsg;

        -- SET v_cd_system_dictionary_main = nextval('SYSTEM_DICTIONARY_MAIN');
      
      INSERT INTO SYSTEM_DICTIONARY_MAIN (ds_system_dictionary_main) values (p_description )  ;


       return p_description;
   END IF;

   IF p_cd_system_languages IS NULL THEN
      SELECT getvar('cd_system_languages')
        INTO v_cd_system_language;
   ELSE
        SET v_cd_system_language = p_cd_system_languages;
   END IF;

   IF v_cd_system_language IS NULL THEN
      return p_description;
   END IF;


   -- Busco mensagem de usuarios!
   SELECT d.ds_system_dictionary_text
     INTO v_result
     FROM SYSTEM_DICTIONARY_USERDEFINED d
    WHERE d.cd_system_dictionary_main = v_cd_system_dictionary_main
      AND d.cd_system_languages       = v_cd_system_language;

   IF v_result IS NOT NULL THEN
       return v_result;
   END IF;


   -- busca traducao geral !
   SELECT d.ds_system_dictionary_translation
     INTO v_result
     FROM SYSTEM_DICTIONARY_TRANSLATION d
    WHERE d.cd_system_dictionary_main = v_cd_system_dictionary_main
      AND d.cd_system_languages       = v_cd_system_language;

   IF v_result IS NULL THEN
       SET v_result = p_description;
   END IF;


   return v_result;
    END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;
/*!50003 DROP FUNCTION IF EXISTS `setvar` */;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`hrms_admin`@`%` FUNCTION `setvar`( p_var_name varchar(100), p_var_value varchar(100)) RETURNS decimal(10,0)
BEGIN
    DECLARE v_cnt integer;
        
        CREATE TEMPORARY TABLE IF NOT EXISTS session_var_tbl (var_name varchar (100) not null, var_value varchar (100)) ENGINE = MEMORY;

        IF EXISTS ( select 1 from session_var_tbl where var_name = p_var_name ) THEN
            update session_var_tbl set var_value = p_var_value where var_name = p_var_name;
        ELSE 
            insert into session_var_tbl (var_name, var_value)
            values (p_var_name, p_var_value);
        END IF;
        
        return 1;

    END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;
/*!50003 DROP PROCEDURE IF EXISTS `adjustMovementBalance` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`hrms_admin`@`%` PROCEDURE `adjustMovementBalance`( PAR_cd_employee INT, 
                                        PAR_cd_benefit_type INT
                                      )
BEGIN

    DECLARE done integer default 0;
    
    DECLARE cur_cd_movements integer;
    DECLARE cur_cd_benefit_operation integer;

    DECLARE cur_nr_value_to_add decimal(12,2);
    DECLARE cur_nr_value_actual decimal(12,2);

    DECLARE calc_nr_value_to_add decimal(12,2);
    DECLARE calc_nr_value_actual decimal(12,2);

    
    DECLARE cur1 CURSOR FOR
    SELECT  m.cd_movements, 
            m.nr_value_to_add, 
            m.nr_value_actual,
            m.cd_benefit_operation
      FROM MOVEMENTS m
     WHERE m.cd_employee     = PAR_cd_employee
       AND m.cd_benefit_type = PAR_cd_benefit_type
    ORDER BY dt_start_at ASC, cd_movements ASC ;


    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    SET calc_nr_value_actual = 0;


    OPEN cur1;


    FETCH cur1 INTO cur_cd_movements, 
                    cur_nr_value_to_add, 
                    cur_nr_value_actual,
                    cur_cd_benefit_operation;

    WHILE done != 1 DO

        IF cur_cd_benefit_operation = 1 THEN
            SET calc_nr_value_actual = calc_nr_value_actual + cur_nr_value_to_add;
        ELSE 
            SET calc_nr_value_actual = calc_nr_value_actual - cur_nr_value_to_add;
        END IF;

        UPDATE MOVEMENTS 
           SET nr_value_actual = calc_nr_value_actual 
         WHERE cd_movements = cur_cd_movements;


        FETCH cur1 INTO cur_cd_movements, 
                        cur_nr_value_to_add, 
                        cur_nr_value_actual,
                        cur_cd_benefit_operation;


    END WHILE;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getMenuOptions` */;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`hrms_admin`@`%` PROCEDURE `getMenuOptions`(fl_job_or_hm character(1), cd_code integer)
BEGIN
    DECLARE done integer default 0;
    
    DECLARE cur_cd_menu integer;
    DECLARE cur_ds_menu varchar(1000);
    DECLARE tmpdesc varchar(1000);


    DECLARE cur_cd_menu_parent integer;

    DECLARE v_fl_checked character(1);
 


    DECLARE cur1 CURSOR FOR
    SELECT  m.cd_menu, retDescTranslated(m.ds_menu, null) as ds_menu, m.cd_menu_parent
      FROM MENU m;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    drop table if exists tmpmenu;

    create temporary table tmpmenu (cd_menu_key integer,  
                                    ds_menu_key varchar(1000), 
                                    fl_checked character(1)
                                   );


    OPEN cur1;


    FETCH cur1 INTO cur_cd_menu, 
                    cur_ds_menu, 
                    cur_cd_menu_parent;

    WHILE done != 1 DO

        IF cur_cd_menu_parent IS NOT NULL THEN
            CALL retParentMenu (cur_cd_menu_parent, tmpdesc);
            set cur_ds_menu = concat (tmpdesc, ' => ', cur_ds_menu );
        END IF;
        


        IF fl_job_or_hm = 'J'THEN
            IF EXISTS (select 1 
                         from JOBS_MENU
                        where cd_menu = cur_cd_menu
                          and cd_jobs = cd_code
                        ) THEN
                set v_fl_checked ='Y';
            ELSE
                set v_fl_checked ='N';
            END IF;

        ELSE 
        -- if do job ou hm

            IF EXISTS (select 1 
                         from HUMAN_RESOURCE_MENU
                        where cd_menu = cur_cd_menu
                          and cd_human_resource = cd_code
                        ) THEN
                set v_fl_checked ='Y';
            ELSE
                set v_fl_checked ='N';
            END IF;
        END IF;

        
        insert into tmpmenu 
        values (cur_cd_menu, cur_ds_menu, v_fl_checked);
        
    
        FETCH cur1 INTO cur_cd_menu, 
                        cur_ds_menu, 
                        cur_cd_menu_parent;


    END WHILE;

    select * from tmpmenu order by 2;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;
/*!50003 DROP PROCEDURE IF EXISTS `retParentMenu` */;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`hrms_admin`@`%` PROCEDURE `retParentMenu`(IN cd_menu_par integer, OUT ds_menu varchar(1000))
BEGIN
    declare r text;
    declare i integer;
    declare x varchar(1000);

    

    SELECT retDescTranslated(m.ds_menu, null), cd_menu_parent
      INTO r, i
      FROM MENU m
     WHERE cd_menu = cd_menu_par;

     IF i IS NOT NULL THEN
        call retParentMenu(i, x);
        SET r = concat (x, ' => ', r);
     END IF;


     set ds_menu = r;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;
/*!50003 DROP PROCEDURE IF EXISTS `retPerHMbyJobs` */;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`hrms_admin`@`%` PROCEDURE `retPerHMbyJobs`(fl_permission_or_hm character(1), cd_jobs_par integer)
BEGIN

    DECLARE cur_cd_key integer;
    DECLARE cur_ds_key varchar(255);
    DECLARE cur_ds_other_info  varchar(255);
    DECLARE cur_dt_deactivated datetime;
    DECLARE cur_fl_checked     char(1);
    DECLARE done INTEGER DEFAULT 0;
    DECLARE vcd_hmresource_logged integer;

    SET vcd_hmresource_logged =getvar('cd_human_resource') ;

    drop table if exists tmpjobs;
     
    CREATE TEMPORARY TABLE tmpjobs ( cd_key integer,  
                             ds_key varchar(255), 
                             ds_other_info varchar(255), 
                             dt_deactivated date, 
                             fl_checked char(1)
                           );

    IF fl_permission_or_hm = 'H' THEN
        BEGIN
            DECLARE cur1 CURSOR FOR
            SELECT h.cd_human_resource, 
                   h.ds_human_resource,
                   h.ds_human_resource_full, 
                   h.dt_deactivated
              FROM HUMAN_RESOURCE h
             WHERE h.dt_deactivated IS NULL
              AND ( EXISTS ( select 1 
                              from HUMAN_RESOURCE x 
                             WHERE x.cd_human_resource = vcd_hmresource_logged 
                               AND x.fl_super_user = 'Y' 
                               AND h.fl_super_user = 'Y'  
                            ) 
                    OR  h.fl_super_user = 'N'
                  )

;


            DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
                
            OPEN cur1;

            FETCH cur1 INTO cur_cd_key, 
            cur_ds_key, 
            cur_ds_other_info,
            cur_dt_deactivated;
            
            WHILE done != 1 DO
				
                IF EXISTS (select 1 
                            from JOBS_HUMAN_RESOURCE
                           where cd_human_resource = cur_cd_key
                             and cd_jobs           = cd_jobs_par
                           ) THEN
                    SET cur_fl_checked ='Y';
                ELSE
                    SET cur_fl_checked ='N';
                END IF;

                INSERT INTO tmpjobs ( cd_key,  
                               ds_key, 
                               ds_other_info, 
                               dt_deactivated, 
                               fl_checked 
                             )

                 VALUES (cur_cd_key,  
                         cur_ds_key, 
                         cur_ds_other_info, 
                         cur_dt_deactivated, 
                         cur_fl_checked );

                FETCH cur1 INTO cur_cd_key, 
                                cur_ds_key, 
                                cur_ds_other_info,
                                cur_dt_deactivated;
                        
            END WHILE;
                

        END;

    ELSE 
        BEGIN
			
            DECLARE cur1 CURSOR FOR
            SELECT h.cd_system_permission, 
                   h.ds_system_permission,
                   ( select ds_type_sys_permission from TYPE_SYS_PERMISSION where cd_type_sys_permission = h.cd_type_sys_permission ) as ds_type_sys_permission,
                   null
              FROM SYSTEM_PERMISSION h;
            
            DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
                
            OPEN cur1;

            FETCH cur1 INTO cur_cd_key, 
            cur_ds_key, 
            cur_ds_other_info,
            cur_dt_deactivated;
            
            WHILE done != 1 DO
				
                IF EXISTS (select 1 
                         from JOBS_SYSTEM_PERMISSION
                        where cd_system_permission = cur_cd_key
                          and cd_jobs              = cd_jobs_par
                      ) THEN

                    SET cur_fl_checked ='Y';
                ELSE
                    SET cur_fl_checked ='N';
                END IF;

                INSERT INTO tmpjobs ( cd_key,  
                               ds_key, 
                               ds_other_info, 
                               dt_deactivated, 
                               fl_checked 
                             )

                 VALUES (cur_cd_key,  
                         cur_ds_key, 
                         cur_ds_other_info, 
                         cur_dt_deactivated, 
                         cur_fl_checked );

                FETCH cur1 INTO cur_cd_key, 
                                cur_ds_key, 
                                cur_ds_other_info,
                                cur_dt_deactivated;
                        
            END WHILE;
        END;
        
    END IF;


    select * from tmpjobs ORDER BY fl_checked desc, ds_key;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;
/*!50003 DROP PROCEDURE IF EXISTS `returnMenuMb` */;
ALTER DATABASE `hrms` CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`hrms_admin`@`%` PROCEDURE `returnMenuMb`(p_cd_human_resource integer)
BEGIN 

    DECLARE row1   integer;
    DECLARE row2   integer;
    DECLARE row3   integer;

    DECLARE v_nr_global_order integer DEFAULT 0;

    DECLARE v_fl_super_user char(1);

    DECLARE cur_cd_menu_l1 integer;
    DECLARE cur_ds_menu_l1 varchar(32);
    DECLARE cur_ds_controller_l1 varchar(128); 
    DECLARE cur_cd_menu_parent_l1 integer;
    DECLARE cur_nr_order_l1 integer;
    DECLARE cur_ds_image_l1 text;
    DECLARE cur_fl_always_available_l1 char(1);
    DECLARE cur_fl_visible_l1 char(1);
    DECLARE cur_nr_count_l1 integer;

    DECLARE cur_cd_menu_l2 integer;
    DECLARE cur_ds_menu_l2 varchar(32);
    DECLARE cur_ds_controller_l2 varchar(128); 
    DECLARE cur_cd_menu_parent_l2 integer;
    DECLARE cur_nr_order_l2 integer;
    DECLARE cur_ds_image_l2 text;
    DECLARE cur_fl_always_available_l2 char(1);
    DECLARE cur_fl_visible_l2 char(1);
    DECLARE cur_nr_count_l2 integer;

    DECLARE cur_cd_menu_l3 integer;
    DECLARE cur_ds_menu_l3 varchar(32);
    DECLARE cur_ds_controller_l3 varchar(128); 
    DECLARE cur_cd_menu_parent_l3 integer;
    DECLARE cur_nr_order_l3 integer;
    DECLARE cur_ds_image_l3 text;
    DECLARE cur_fl_always_available_l3 char(1);
    DECLARE cur_fl_visible_l3 char(1);
    DECLARE cur_nr_count_l3 integer;
    DECLARE v_fl_has_sub      char(2);

    DECLARE done_l1 integer default 0;
    DECLARE done_l2 integer default 0;
    DECLARE done_l3 integer default 0;

    
    DECLARE cur1 CURSOR FOR
    SELECT xa.cd_menu, 
           xa.ds_menu, 
           xa.ds_controller, 
           ( select count(1) from menuUsersExists x where x.cd_menu_parent = xa.cd_menu ) as countparent, 
           xa.ds_image,  
           xa.fl_always_available, 
           xa.fl_visible
    FROM menuUsers xa
    WHERE xa.cd_menu_parent IS NULL
    ORDER BY xa.nr_order, xa.ds_menu;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done_l1 = 1;

    drop table if exists tmpReturn;
    drop table if exists menuUsers;
    drop table if exists menuUsersExists;


    CREATE TEMPORARY TABLE tmpReturn (nr_order integer,
                            ds_menu varchar(32), 
                            ds_controller varchar(128), 
                            fl_has_sub char(2), 
                            ds_image varchar(255), 
                            cd_menu integer, 
                            fl_always_available char(1), 
                            fl_visible char(1) 
                           ) ENGINE = MEMORY;


   create temporary table menuUsers (cd_menu integer, 
                                     ds_menu varchar(32),  
                                     ds_controller varchar(128), 
                                     cd_menu_parent integer,
                                     nr_order integer,
                                     ds_image varchar(255),
                                     fl_always_available char(1),
                                     fl_visible char(1)
                                   )  ENGINE = MEMORY;

   SELECT fl_super_user
     INTO v_fl_super_user
    FROM HUMAN_RESOURCE 
   WHERE cd_human_resource = p_cd_human_resource;

   
   INSERT INTO menuUsers
   SELECT  MENU.cd_menu, retDescTranslatedNew(MENU.ds_menu, null), MENU.ds_controller, MENU.cd_menu_parent, MENU.nr_order, coalesce (MENU.ds_image, '') as ds_image, MENU.fl_always_available, MENU.fl_visible
     FROM HUMAN_RESOURCE_MENU,
          MENU
    WHERE  HUMAN_RESOURCE_MENU.cd_human_resource = p_cd_human_resource
      AND MENU.cd_menu  = HUMAN_RESOURCE_MENU.cd_menu
      AND ( MENU.fl_only_for_super_users = 'N' OR v_fl_super_user = 'Y' )

    UNION

       SELECT  MENU.cd_menu, retDescTranslatedNew(MENU.ds_menu, null), MENU.ds_controller, MENU.cd_menu_parent, MENU.nr_order,  coalesce (MENU.ds_image, '') as ds_image, MENU.fl_always_available, MENU.fl_visible
         FROM JOBS_HUMAN_RESOURCE jh,
               JOBS_MENU jm,
               JOBS j,
               MENU
         WHERE jh.cd_human_resource = P_cd_human_resource
           AND jm.cd_jobs           = jh.cd_jobs
           AND j.cd_jobs            = jh.cd_jobs
           AND MENU.cd_menu       = jm.cd_menu
           AND j.dt_deactivated IS NULL
          AND ( MENU.fl_only_for_super_users = 'N' OR v_fl_super_user = 'Y' )

    UNION 
    SELECT  MENU.cd_menu, retDescTranslatedNew(MENU.ds_menu, null), MENU.ds_controller, MENU.cd_menu_parent, MENU.nr_order, coalesce (MENU.ds_image, '') as ds_image, MENU.fl_always_available, MENU.fl_visible
         FROM MENU
        WHERE MENU.fl_always_available = 'Y';


    -- crio outras temporarias por conta de limitacoes do MySql
    create temporary table menuUsersExists as 
    (select * from menuUsers) ;



    -- comeco os cursores.    
    -- level 1



    OPEN cur1;


    FETCH cur1 INTO cur_cd_menu_l1, 
                    cur_ds_menu_l1, 
                    cur_ds_controller_l1, 
                    cur_nr_count_l1, 
                    cur_ds_image_l1, 
                    cur_fl_always_available_l1, 
                    cur_fl_visible_l1;

        WHILE done_l1 != 1 DO

            SET v_nr_global_order = v_nr_global_order + 1;

            if cur_nr_count_l1 > 0 THEN
               set v_fl_has_sub = 'B1';
            ELSE 
               set v_fl_has_sub = 'L';
            end if;    

            insert into tmpReturn (nr_order,
                            ds_menu, 
                            ds_controller, 
                            fl_has_sub, 
                            ds_image, 
                            cd_menu, 
                            fl_always_available, 
                            fl_visible 
                           )
            values (v_nr_global_order, 
                    cur_ds_menu_l1, 
                    cur_ds_controller_l1,
                    v_fl_has_sub,
                    cur_ds_image_l1, 
                    cur_cd_menu_l1,
                    cur_fl_always_available_l1, 
                    cur_fl_visible_l1); 

            /* 2222 *************************************************** */

                -- CURSOR 2:
            BEGIN
                DECLARE cur2 CURSOR FOR
                SELECT xb.cd_menu, 
                       xb.ds_menu, 
                       xb.ds_controller, 
                       ( select count(1) from menuUsersExists x where x.cd_menu_parent = xb.cd_menu ) as countparent, 
                       xb.ds_image,  
                       xb.fl_always_available, 
                       xb.fl_visible
                FROM menuUsers xb
                WHERE xb.cd_menu_parent = cur_cd_menu_l1
                ORDER BY xb.nr_order, xb.ds_menu;

                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done_l2 = 1;

                SET done_l2 = 0;


                OPEN cur2;

                FETCH cur2 INTO cur_cd_menu_l2, 
                                cur_ds_menu_l2, 
                                cur_ds_controller_l2, 
                                cur_nr_count_l2, 
                                cur_ds_image_l2, 
                                cur_fl_always_available_l2, 
                                cur_fl_visible_l2;


                WHILE done_l2 != 1 DO


                    if cur_nr_count_l2 > 0 THEN
                       SET v_fl_has_sub = 'B2';
                    ELSE 
                       SET v_fl_has_sub = 'L';
                    end if;    

                    SET v_nr_global_order = v_nr_global_order + 1;

                    insert into tmpReturn (nr_order,
                                    ds_menu, 
                                    ds_controller, 
                                    fl_has_sub, 
                                    ds_image, 
                                    cd_menu, 
                                    fl_always_available, 
                                    fl_visible 
                                   )
                    values (v_nr_global_order, 
                            cur_ds_menu_l2, 
                            cur_ds_controller_l2,
                            v_fl_has_sub,
                            cur_ds_image_l2, 
                            cur_cd_menu_l2,
                            cur_fl_always_available_l2, 
                            cur_fl_visible_l2); 


                    /* 3333 *************************************************** */
                    BEGIN

                        DECLARE cur3 CURSOR FOR
                        SELECT xb.cd_menu, 
                               xb.ds_menu, 
                               xb.ds_controller, 
                               ( select count(1) from menuUsersExists x where x.cd_menu_parent = xb.cd_menu ) as countparent, 
                               xb.ds_image,  
                               xb.fl_always_available, 
                               xb.fl_visible
                        FROM menuUsers xb
                        WHERE xb.cd_menu_parent = cur_cd_menu_l2
                        ORDER BY xb.nr_order, xb.ds_menu;

                        
                        DECLARE CONTINUE HANDLER FOR NOT FOUND SET done_l3 = 1;

                        SET done_l3 = 0;

                        OPEN cur3;

                        FETCH cur3 INTO cur_cd_menu_l3, 
                                        cur_ds_menu_l3, 
                                        cur_ds_controller_l3, 
                                        cur_nr_count_l3, 
                                        cur_ds_image_l3, 
                                        cur_fl_always_available_l3, 
                                        cur_fl_visible_l3;


                        WHILE done_l3 != 1 DO

                            SET v_fl_has_sub = 'L';
                            SET v_nr_global_order = v_nr_global_order + 1;

                            insert into tmpReturn (nr_order,
                                            ds_menu, 
                                            ds_controller, 
                                            fl_has_sub, 
                                            ds_image, 
                                            cd_menu, 
                                            fl_always_available, 
                                            fl_visible 
                                           )
                            values (v_nr_global_order, 
                                    cur_ds_menu_l3, 
                                    cur_ds_controller_l3,
                                    v_fl_has_sub,
                                    cur_ds_image_l3, 
                                    cur_cd_menu_l3,
                                    cur_fl_always_available_l3, 
                                    cur_fl_visible_l3); 


                            FETCH cur3 INTO cur_cd_menu_l3, 
                                            cur_ds_menu_l3, 
                                            cur_ds_controller_l3, 
                                            cur_nr_count_l3, 
                                            cur_ds_image_l3, 
                                            cur_fl_always_available_l3, 
                                            cur_fl_visible_l3;
                        END WHILE;

                        CLOSE cur3;

                    END;

            /* 3333 *************************************************** */

            if cur_nr_count_l2 > 0 THEN
                SET cur_ds_menu_l2 = 'SUB END';
                SET cur_ds_controller_l2 = null;
                SET v_fl_has_sub = 'E2';

                SET v_nr_global_order = v_nr_global_order + 1;

                insert into tmpReturn (nr_order,
                                ds_menu, 
                                ds_controller, 
                                fl_has_sub, 
                                ds_image, 
                                cd_menu, 
                                fl_always_available, 
                                fl_visible 
                               )
                values (v_nr_global_order, 
                        cur_ds_menu_l2, 
                        cur_ds_controller_l2,
                        v_fl_has_sub,
                        cur_ds_image_l2, 
                        cur_cd_menu_l2,
                        cur_fl_always_available_l2, 
                        cur_fl_visible_l2); 
                END IF;

                FETCH cur2 INTO cur_cd_menu_l2, 
                                    cur_ds_menu_l2, 
                                    cur_ds_controller_l2, 
                                    cur_nr_count_l2, 
                                    cur_ds_image_l2, 
                                    cur_fl_always_available_l2, 
                                    cur_fl_visible_l2;

                END WHILE;
                CLOSE cur2;

            END;



    /* 2222 *************************************************** */

        if cur_nr_count_l1 > 0 THEN
            SET cur_ds_menu_l1 = 'SUB END';
            SET cur_ds_controller_l1 = null;
            SET v_fl_has_sub = 'E1';

            SET v_nr_global_order = v_nr_global_order + 1;


            insert into tmpReturn (nr_order,
                            ds_menu, 
                            ds_controller, 
                            fl_has_sub, 
                            ds_image, 
                            cd_menu, 
                            fl_always_available, 
                            fl_visible 
                           )
            values (v_nr_global_order, 
                    cur_ds_menu_l1, 
                    cur_ds_controller_l1,
                    v_fl_has_sub,
                    cur_ds_image_l1, 
                    cur_cd_menu_l1,
                    cur_fl_always_available_l1, 
                    cur_fl_visible_l1); 
        END IF;



        FETCH cur1 INTO cur_cd_menu_l1, 
                        cur_ds_menu_l1, 
                        cur_ds_controller_l1, 
                        cur_nr_count_l1, 
                        cur_ds_image_l1, 
                        cur_fl_always_available_l1, 
                        cur_fl_visible_l1;

        END WHILE;
        
        CLOSE cur1;



        select * from tmpReturn ORDER by nr_order;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
ALTER DATABASE `hrms` CHARACTER SET utf8 COLLATE utf8_general_ci ;

--
-- Final view structure for view `MENU`
--

/*!50001 DROP VIEW IF EXISTS `MENU`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`hrms_admin`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `MENU` AS select `SYSTEM_MENU`.`cd_system_menu` AS `cd_menu`,`SYSTEM_MENU`.`ds_system_menu` AS `ds_menu`,`SYSTEM_MENU`.`ds_controller` AS `ds_controller`,`SYSTEM_MENU`.`cd_system_menu_parent` AS `cd_menu_parent`,`SYSTEM_MENU`.`dt_deactivated` AS `dt_deactivated`,`SYSTEM_MENU`.`dt_record` AS `dt_record`,`SYSTEM_MENU`.`nr_order` AS `nr_order`,`SYSTEM_MENU`.`ds_image` AS `ds_image`,`SYSTEM_MENU`.`fl_always_available` AS `fl_always_available`,`SYSTEM_MENU`.`fl_visible` AS `fl_visible`,`SYSTEM_MENU`.`fl_only_for_super_users` AS `fl_only_for_super_users`,`SYSTEM_MENU`.`cds_system_product_category_allowed` AS `cds_system_product_category_allowed` from `SYSTEM_MENU` where (`SYSTEM_MENU`.`cds_system_product_category_allowed` in (select `SYSTEM_COMPANY`.`cd_system_product_category_allowed` from `SYSTEM_COMPANY`) or isnull(`SYSTEM_MENU`.`cds_system_product_category_allowed`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `SYSTEM_DICTIONARY_DEFAULT_VIEW`
--

/*!50001 DROP VIEW IF EXISTS `SYSTEM_DICTIONARY_DEFAULT_VIEW`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`hrms_admin`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `SYSTEM_DICTIONARY_DEFAULT_VIEW` AS select `a`.`cd_system_dictionary_main` AS `cd_system_dictionary_main`,`a`.`ds_system_dictionary_main` AS `ds_system_dictionary_main`,`c`.`cd_system_languages` AS `cd_system_languages`,coalesce((select `b`.`ds_system_dictionary_translation` from `SYSTEM_DICTIONARY_TRANSLATION` `b` where ((`a`.`cd_system_dictionary_main` = `b`.`cd_system_dictionary_main`) and (`b`.`cd_system_languages` = `c`.`cd_system_languages`))),`a`.`ds_system_dictionary_main`) AS `ds_translated` from (`SYSTEM_DICTIONARY_MAIN` `a` join `SYSTEM_LANGUAGES` `c`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `SYSTEM_DICTIONARY_VIEW`
--

/*!50001 DROP VIEW IF EXISTS `SYSTEM_DICTIONARY_VIEW`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`hrms_admin`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `SYSTEM_DICTIONARY_VIEW` AS select `a`.`cd_system_dictionary_main` AS `cd_system_dictionary_main`,`a`.`ds_system_dictionary_main` AS `ds_system_dictionary_main`,`a`.`cd_system_languages` AS `cd_system_languages`,coalesce((select `b`.`ds_system_dictionary_text` from `SYSTEM_DICTIONARY_USERDEFINED` `b` where ((`b`.`cd_system_dictionary_main` = `a`.`cd_system_dictionary_main`) and (`b`.`cd_system_languages` = `a`.`cd_system_languages`))),`a`.`ds_translated`) AS `ds_translated`,'N' AS `fl_update_main` from `SYSTEM_DICTIONARY_DEFAULT_VIEW` `a` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `SYSTEM_PRODUCT_CATEGORY_VIEW`
--

/*!50001 DROP VIEW IF EXISTS `SYSTEM_PRODUCT_CATEGORY_VIEW`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`hrms_admin`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `SYSTEM_PRODUCT_CATEGORY_VIEW` AS select `a`.`cd_system_product_category` AS `cd_system_product_category`,`a`.`ds_system_product_category` AS `ds_system_product_category`,`a`.`ds_icon` AS `ds_icon`,`a`.`nr_order` AS `nr_order` from `SYSTEM_PRODUCT_CATEGORY` `a` order by `a`.`nr_order` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-12-19 13:22:23
