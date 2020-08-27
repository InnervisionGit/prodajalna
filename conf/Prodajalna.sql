-- MySQL dump 10.13  Distrib 5.7.23, for Linux (x86_64)
--
-- Host: localhost    Database: Prodajalna
-- ------------------------------------------------------
-- Server version	5.7.23-0ubuntu0.18.04.1

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
-- Table structure for table `Customer`
--

DROP TABLE IF EXISTS `Customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Customer` (
  `Customer_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Customer_firstName` varchar(50) NOT NULL,
  `Customer_lastName` varchar(255) NOT NULL,
  `Customer_email` varchar(100) NOT NULL,
  `Customer_address` varchar(255) NOT NULL,
  `Customer_city` varchar(100) NOT NULL,
  `Customer_postNumber` int(11) NOT NULL,
  `Customer_phoneNumber` varchar(15) NOT NULL,
  `Customer_password` varchar(15) NOT NULL,
  `Customer_isApproved` tinyint(1) NOT NULL,
  PRIMARY KEY (`Customer_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Customer`
--

LOCK TABLES `Customer` WRITE;
/*!40000 ALTER TABLE `Customer` DISABLE KEYS */;
INSERT INTO `Customer` VALUES (1,'Anton','Allan','anton@ep.si','antonova 1','Ankaran',1,'123456789','11111',1),(3,'Beta','Beton','beta@ep.si','Betkina 2','Bled',2222,'234567891','22222',1),(4,'Cene','Celjski','cola@ep.si','Cerknica 3','Celje',3333,'345678912','33333',0);
/*!40000 ALTER TABLE `Customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Item`
--

DROP TABLE IF EXISTS `Item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Item` (
  `Item_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Item_name` varchar(50) NOT NULL,
  `Item_description` text,
  `Item_URL` varchar(255) DEFAULT NULL,
  `Item_price` decimal(10,0) NOT NULL,
  `Item_isApproved` tinyint(1) NOT NULL,
  PRIMARY KEY (`Item_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Item`
--

LOCK TABLES `Item` WRITE;
/*!40000 ALTER TABLE `Item` DISABLE KEYS */;
INSERT INTO `Item` VALUES (31,'Dorans Blade','+8 attack damage; +80 health; +3% life steal','http://pm1.narvii.com/5804/9aafbe7416458c3b93eb57e2a822b2d58fc0bb90_00.jpg',450,1),(32,'Dorans ring','+15 ability power; +60 health','https://vignette.wikia.nocookie.net/leagueoflegends/images/2/2d/Doran%27s_Ring_item.png/revision/latest?cb=20171221063511',400,1),(33,'Dorans shield','+80 health; +6 flat health regeneration per 5 seconds; Basic attacks deal an additional 5 physical damage to minions on hit; After taking damage from an enemy champion, gain health regeneration equal to 0 âˆ’ 30 (based on current missing health) over 10 seconds.','https://d1u5p3l4wpay3k.cloudfront.net/lolesports_gamepedia_en/8/81/Doran%27s_Shield.png?version=0c58f37e50c3541d7f3c5011773eeb26',450,1),(34,'Health potion','Regenerates 5 health every half-second for 15 seconds, restoring a total of 150 health.','https://vignette.wikia.nocookie.net/leagueoflegends/images/1/13/Health_Potion_item.png/revision/latest?cb=20171221184619',50,1),(35,'Hunters machete','+10% life steal vs. monsters','http://ddragon.leagueoflegends.com/cdn/4.11.3/img/item/1039.png',350,1),(36,'Hunters talisman','Damaging a monster by any means set it aflame, dealing an additional 60 magic damage over 5 seconds while causing you to restore 6 health per second for every enemy youre burning.','https://vignette.wikia.nocookie.net/leagueoflegends/images/2/21/Hunter%27s_Talisman_item.png/revision/latest?cb=20171221190028',350,1),(37,'Ancient coin','+5% cooldown reduction; +Gold 2 per 10 seconds; +5 movement speed','https://pm1.narvii.com/5762/d4cdec9e56ce253bfc7e09eff634d23cf5d15b44_128.jpg',400,1),(38,'Spellthiefs edge','+10 ability power; +Gold 2 per 10 seconds; +25% base mana regeneration','https://pbs.twimg.com/profile_images/1030856750621052928/wAf6RO___400x400.jpg',400,1);
/*!40000 ALTER TABLE `Item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Order`
--

DROP TABLE IF EXISTS `Order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Order` (
  `Order_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Staff_ID` int(11) DEFAULT NULL,
  `Customer_ID` int(11) NOT NULL,
  `Order_state` varchar(50) NOT NULL,
  `Order_approvalDate` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Order_ID`),
  KEY `FK_order` (`Customer_ID`),
  KEY `FK_approve` (`Staff_ID`),
  CONSTRAINT `FK_approve` FOREIGN KEY (`Staff_ID`) REFERENCES `Staff` (`Staff_ID`),
  CONSTRAINT `FK_order` FOREIGN KEY (`Customer_ID`) REFERENCES `Customer` (`Customer_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Order`
--

LOCK TABLES `Order` WRITE;
/*!40000 ALTER TABLE `Order` DISABLE KEYS */;
INSERT INTO `Order` VALUES (12,NULL,3,'Shipped',NULL);
/*!40000 ALTER TABLE `Order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Staff`
--

DROP TABLE IF EXISTS `Staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Staff` (
  `Staff_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Staff_firstName` varchar(50) NOT NULL,
  `Staff_lastName` varchar(255) NOT NULL,
  `Staff_email` varchar(100) NOT NULL,
  `Staff_password` varchar(15) NOT NULL,
  `Staff_isAdmin` tinyint(1) NOT NULL,
  `Staff_isApproved` tinyint(1) NOT NULL,
  PRIMARY KEY (`Staff_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Staff`
--

LOCK TABLES `Staff` WRITE;
/*!40000 ALTER TABLE `Staff` DISABLE KEYS */;
INSERT INTO `Staff` VALUES (1,'Ana','Abathur','ana@ep.si','ana1',1,1),(2,'Bojancek','Ban','bojan@ep.si','bojan1',0,1),(3,'Delete','Me','delete@ep.si','delete',0,0);
/*!40000 ALTER TABLE `Staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contains`
--

DROP TABLE IF EXISTS `contains`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contains` (
  `Item_ID` int(11) NOT NULL DEFAULT '0',
  `Order_ID` int(11) NOT NULL,
  `Price` int(11) DEFAULT NULL,
  PRIMARY KEY (`Item_ID`,`Order_ID`),
  KEY `FK_contains2` (`Order_ID`),
  CONSTRAINT `FK_contains` FOREIGN KEY (`Item_ID`) REFERENCES `Item` (`Item_ID`),
  CONSTRAINT `FK_contains2` FOREIGN KEY (`Order_ID`) REFERENCES `Order` (`Order_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contains`
--

LOCK TABLES `contains` WRITE;
/*!40000 ALTER TABLE `contains` DISABLE KEYS */;
INSERT INTO `contains` VALUES (31,12,900),(32,12,800),(34,12,50),(35,12,350),(36,12,350),(37,12,400),(38,12,400);
/*!40000 ALTER TABLE `contains` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-01-13 17:47:31
