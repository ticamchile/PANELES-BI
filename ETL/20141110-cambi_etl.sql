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
-- Dumping data for table `GASTO`
--

LOCK TABLES `GASTO` WRITE;
/*!40000 ALTER TABLE `GASTO` DISABLE KEYS */;
/*!40000 ALTER TABLE `GASTO` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `INDICADOR`
--

DROP TABLE IF EXISTS `INDICADOR`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `INDICADOR` (
  `area` varchar(45) DEFAULT NULL,
  `granularidad` varchar(1) DEFAULT 'M',
  `dia` int(11) DEFAULT NULL,
  `mes` int(11) DEFAULT NULL,
  `anno` int(11) NOT NULL,
  `indicador` varchar(100) DEFAULT NULL,
  `valor` double NOT NULL DEFAULT '0',
  KEY `idx_INDICADOR_lookup` (`area`,`granularidad`,`dia`,`mes`,`anno`,`indicador`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `INDICADOR`
--

LOCK TABLES `INDICADOR` WRITE;
/*!40000 ALTER TABLE `INDICADOR` DISABLE KEYS */;
/*!40000 ALTER TABLE `INDICADOR` ENABLE KEYS */;
UNLOCK TABLES;

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

--
-- Dumping data for table `SOLICITUD_SERVICIO`
--

LOCK TABLES `SOLICITUD_SERVICIO` WRITE;
/*!40000 ALTER TABLE `SOLICITUD_SERVICIO` DISABLE KEYS */;
/*!40000 ALTER TABLE `SOLICITUD_SERVICIO` ENABLE KEYS */;
UNLOCK TABLES;
-- Temporary table structure for view `view_gasto`
