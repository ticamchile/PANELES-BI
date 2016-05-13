-- MySQL dump 10.13  Distrib 5.6.15, for osx10.7 (i386)
--
-- Host: localhost    Database: cambi_etl
-- ------------------------------------------------------
-- Server version	5.6.15

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
-- Table structure for table `GASTO`
--

DROP TABLE IF EXISTS `GASTO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `GASTO` (
  `area` varchar(45) DEFAULT NULL,
  `mes` int(11) DEFAULT NULL,
  `anno` int(11) NOT NULL,
  `tipo` varchar(45) DEFAULT NULL,
  `valor` double NOT NULL DEFAULT '0',
  `categoria` varchar(45) DEFAULT NULL,
  KEY `idx_GASTO_lookup` (`categoria`,`area`,`tipo`,`mes`,`anno`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SOLICITUD_SERVICIO`
--

DROP TABLE IF EXISTS `SOLICITUD_SERVICIO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SOLICITUD_SERVICIO` (
  `id` int(11) DEFAULT NULL,
  `AREA` varchar(45) DEFAULT NULL,
  `tipo` varchar(45) DEFAULT NULL,
  `mes` int(11) NOT NULL,
  `anno` int(11) NOT NULL,
  `tiempo_de_atencion` int(11) DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `categoria` varchar(45) DEFAULT NULL,
  KEY `idx_SOLICITUD_SERVICIO_lookup` (`id`,`AREA`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-10-28 14:59:18
