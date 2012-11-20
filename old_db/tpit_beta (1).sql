-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 20, 2012 at 07:24 PM
-- Server version: 5.5.24
-- PHP Version: 5.3.10-1ubuntu3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tpit_beta`
--

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

CREATE TABLE IF NOT EXISTS `applicants` (
  `users_id` bigint(20) NOT NULL,
  `jobs_id` bigint(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`users_id`,`jobs_id`),
  KEY `fk_users_has_jobs_jobs1` (`jobs_id`),
  KEY `fk_users_has_jobs_users1` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE IF NOT EXISTS `banks` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) NOT NULL,
  `tag` varchar(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `name`, `tag`) VALUES
(26, 'I General Bank', 'IOOOGB'),
(27, 'II General Bank', 'IIOOGB');

-- --------------------------------------------------------

--
-- Table structure for table `banks_balances`
--

CREATE TABLE IF NOT EXISTS `banks_balances` (
  `banks_id` bigint(20) NOT NULL,
  `currencies_id` bigint(20) NOT NULL,
  `amount` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`banks_id`,`currencies_id`),
  KEY `fk_banks_has_currencies_currencies1` (`currencies_id`),
  KEY `fk_banks_has_currencies_banks1` (`banks_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banks_balances`
--

INSERT INTO `banks_balances` (`banks_id`, `currencies_id`, `amount`) VALUES
(26, 1, 0),
(26, 2, 252000000),
(26, 3, 0),
(27, 1, 0),
(27, 2, 252000000),
(27, 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `is_capital` tinyint(1) NOT NULL DEFAULT '0',
  `countries_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cities_countries1` (`countries_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cities_has_citysettings`
--

CREATE TABLE IF NOT EXISTS `cities_has_citysettings` (
  `cities_id` bigint(20) NOT NULL,
  `citysettings_id` bigint(20) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`cities_id`,`citysettings_id`),
  KEY `fk_cities_has_citysettings_citysettings1` (`citysettings_id`),
  KEY `fk_cities_has_citysettings_cities1` (`cities_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `citysettings`
--

CREATE TABLE IF NOT EXISTS `citysettings` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `is_required` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `clauses`
--

CREATE TABLE IF NOT EXISTS `clauses` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tag` varchar(45) NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `clauses`
--

INSERT INTO `clauses` (`id`, `tag`, `info`) VALUES
(1, 'daily_payment', '%firstparty% will pay %value$amount% RIK daily to %secondparty%'),
(2, 'employment', '%secondparty% agrees to make transaction in the name of %firstparty%. %secondparty% must always make decisions based on the %firstparty% well-being and agrees to always respect and obey decisions of the other employees with higher ranks. '),
(3, 'free_end_of_contract', 'If either parts decided to terminate the contract at any time, it will be declared null and neither of the parts will have to recompensate the other.'),
(4, 'per_played_hour_payment', '%firstparty% agrees to pay %secondparty% the amount of %value$amount% RIK per trading hour.');

-- --------------------------------------------------------

--
-- Table structure for table `clausevalues`
--

CREATE TABLE IF NOT EXISTS `clausevalues` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `contracts_id` bigint(20) NOT NULL,
  `clauses_id` bigint(20) NOT NULL,
  `value` varchar(255) NOT NULL,
  `ord` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_clausevalues_contracts_has_clauses1` (`contracts_id`,`clauses_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `clausevalues`
--

INSERT INTO `clausevalues` (`id`, `contracts_id`, `clauses_id`, `value`, `ord`) VALUES
(7, 35, 4, '10000', 'amount'),
(8, 36, 4, '10000', 'amount'),
(9, 38, 4, '10000', 'amount'),
(10, 39, 4, '10000', 'amount');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`) VALUES
(1, 'Popescu Gelu'),
(2, 'Ion Petcu');

-- --------------------------------------------------------

--
-- Table structure for table `clients_offers`
--

CREATE TABLE IF NOT EXISTS `clients_offers` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` bigint(20) NOT NULL,
  `client_id` bigint(11) NOT NULL,
  `market` varchar(4) NOT NULL,
  `amount` int(11) NOT NULL,
  `currency` int(11) NOT NULL,
  `deal` varchar(10) NOT NULL,
  `period_id` int(11) NOT NULL,
  `pending` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `clients_offers`
--

INSERT INTO `clients_offers` (`id`, `date`, `client_id`, `market`, `amount`, `currency`, `deal`, `period_id`, `pending`) VALUES
(1, 1353360822, 2, 'FX', 4, 1, '1', 1, 0),
(2, 1353360822, 2, 'FX', 5, 1, '2', 1, 0),
(3, 1353360823, 1, 'FX', 3, 1, '2', 1, 0),
(4, 1353360823, 2, 'FX', 10, 2, '2', 1, 0),
(5, 1353360824, 2, 'FX', 2, 2, '2', 1, 0),
(6, 1353360824, 1, 'FX', 1, 2, '1', 1, 1),
(7, 1353360825, 1, 'FX', 5, 2, '1', 1, 0),
(8, 1353360847, 2, 'FX', 9, 2, '1', 1, 1),
(9, 1353360848, 2, 'FX', 7, 2, '2', 1, 1),
(10, 1353360848, 2, 'FX', 6, 1, '2', 1, 1),
(11, 1353360850, 1, 'FX', 8, 2, '2', 1, 1),
(12, 1353360851, 2, 'FX', 7, 1, '1', 1, 1),
(13, 1353360851, 2, 'FX', 4, 1, '2', 1, 0),
(14, 1353360854, 1, 'FX', 2, 1, '2', 1, 0),
(15, 1353360855, 2, 'FX', 10, 1, '1', 1, 0),
(16, 1353360855, 2, 'FX', 1, 2, '2', 1, 0),
(17, 1353406464, 2, 'FX', 8, 2, '2', 1, 1),
(18, 1353406608, 1, 'FX', 4, 1, '1', 1, 1),
(19, 1353407376, 1, 'FX', 1, 2, '2', 1, 0),
(20, 1353407672, 1, 'FX', 2, 2, '2', 1, 1),
(21, 1353408073, 1, 'FX', 7, 2, '2', 1, 1),
(22, 1353408165, 1, 'FX', 6, 1, '2', 1, 0),
(23, 1353408301, 1, 'FX', 1, 1, '1', 1, 1),
(24, 1353408569, 2, 'FX', 9, 1, '2', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE IF NOT EXISTS `contracts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `contracttypes_id` bigint(20) NOT NULL,
  `start_date` bigint(20) DEFAULT NULL,
  `end_date` bigint(20) DEFAULT NULL,
  `signed_firstparty` tinyint(1) NOT NULL DEFAULT '1',
  `signed_secondparty` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_contracts_contracttypes1` (`contracttypes_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `contracts`
--

INSERT INTO `contracts` (`id`, `contracttypes_id`, `start_date`, `end_date`, `signed_firstparty`, `signed_secondparty`) VALUES
(34, 1, NULL, NULL, 1, 0),
(35, 1, NULL, NULL, 1, 1),
(36, 1, NULL, NULL, 1, 1),
(37, 1, NULL, NULL, 1, 0),
(38, 1, NULL, NULL, 1, 1),
(39, 1, NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `contracts_has_clauses`
--

CREATE TABLE IF NOT EXISTS `contracts_has_clauses` (
  `contracts_id` bigint(20) NOT NULL,
  `clauses_id` bigint(20) NOT NULL,
  PRIMARY KEY (`contracts_id`,`clauses_id`),
  KEY `fk_contracts_has_clauses_clauses1` (`clauses_id`),
  KEY `fk_contracts_has_clauses_contracts1` (`contracts_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contracts_has_clauses`
--

INSERT INTO `contracts_has_clauses` (`contracts_id`, `clauses_id`) VALUES
(35, 2),
(36, 2),
(38, 2),
(39, 2),
(35, 3),
(36, 3),
(38, 3),
(39, 3),
(35, 4),
(36, 4),
(38, 4),
(39, 4);

-- --------------------------------------------------------

--
-- Table structure for table `contracttypes`
--

CREATE TABLE IF NOT EXISTS `contracttypes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `contracttypes`
--

INSERT INTO `contracttypes` (`id`, `name`, `info`) VALUES
(1, 'Employment contract', 'To: %secondparty%<br /><br />\r\n<p>%firstparty% is pleased to confirm your appointment as %jobposition%. This document outlines the Terms and Conditions which apply to your contract and other information which is relevant to your employment. The contract will start effectivelly at the time of signing.</p><br />\r\n%clauses%\r\n<br /><br />\r\n<p>Note: This is not a real contract. In this game contracts are a way for players to make agreements between each other or between the player and virtual entities (such as central bank or bot banks). Signing this contract doesn''t imply any legal implications for neither you, the game developers or any other player.</p>');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `timezone` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `currencies_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_countries_currencies1` (`currencies_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `timezone`, `name`, `currencies_id`) VALUES
(2, 0, 'Territory', 1),
(4, 0, 'Rikland', 2),
(5, 0, 'Hattonia', 3);

-- --------------------------------------------------------

--
-- Table structure for table `countries_has_countrysettings`
--

CREATE TABLE IF NOT EXISTS `countries_has_countrysettings` (
  `countries_id` bigint(20) NOT NULL,
  `countrysettings_id` bigint(20) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`countries_id`,`countrysettings_id`),
  KEY `fk_countries_has_countrysettings_countrysettings1` (`countrysettings_id`),
  KEY `fk_countries_has_countrysettings_countries1` (`countries_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `countries_has_econindicators`
--

CREATE TABLE IF NOT EXISTS `countries_has_econindicators` (
  `countries_id` bigint(20) NOT NULL,
  `econindicators_id` bigint(20) NOT NULL,
  `value` bigint(20) NOT NULL,
  PRIMARY KEY (`countries_id`,`econindicators_id`),
  KEY `fk_countries_has_econindicators_econindicators1` (`econindicators_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `countrysettings`
--

CREATE TABLE IF NOT EXISTS `countrysettings` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE IF NOT EXISTS `currencies` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `shortname` varchar(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `shortname`) VALUES
(1, 'Territory', 'TER'),
(2, 'Rikland', 'RIK'),
(3, 'Hattonia ', 'HAT');

-- --------------------------------------------------------

--
-- Table structure for table `currency_pairs`
--

CREATE TABLE IF NOT EXISTS `currency_pairs` (
  `id` bigint(21) NOT NULL AUTO_INCREMENT,
  `currency0` bigint(21) NOT NULL,
  `currency1` bigint(21) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `currency_pairs`
--

INSERT INTO `currency_pairs` (`id`, `currency0`, `currency1`) VALUES
(1, 1, 2),
(2, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `domains`
--

CREATE TABLE IF NOT EXISTS `domains` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `domain` varchar(45) NOT NULL,
  `tld` varchar(45) NOT NULL,
  `modules_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_domains_modules` (`modules_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `domains`
--

INSERT INTO `domains` (`id`, `domain`, `tld`, `modules_id`) VALUES
(1, 'trading', 'pit', 1),
(2, 'news', 'pit', 2),
(3, 'econ', 'pit', 3);

-- --------------------------------------------------------

--
-- Table structure for table `econcategories`
--

CREATE TABLE IF NOT EXISTS `econcategories` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `parent_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_econcategories_econcategories1` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `econcategories`
--

INSERT INTO `econcategories` (`id`, `name`, `parent_id`) VALUES
(1, 'Categorie', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `econforcasts`
--

CREATE TABLE IF NOT EXISTS `econforcasts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` bigint(20) NOT NULL,
  `forecast` bigint(20) NOT NULL,
  `actual` bigint(20) NOT NULL,
  `countries_id` bigint(20) NOT NULL,
  `econindicators_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fk_econforcasts_countries_has_econindicators1` (`countries_id`,`econindicators_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `econindicators`
--

CREATE TABLE IF NOT EXISTS `econindicators` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `unit` int(64) NOT NULL,
  `market_impact` int(11) NOT NULL,
  `impact_power` int(11) NOT NULL,
  `start_date` bigint(20) NOT NULL,
  `countries_id` int(11) NOT NULL,
  `econlevels_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_econindicators_econlevels1` (`econlevels_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `econindicators`
--

INSERT INTO `econindicators` (`id`, `name`, `unit`, `market_impact`, `impact_power`, `start_date`, `countries_id`, `econlevels_id`) VALUES
(1, 'Business Confidence', 0, 0, 0, 0, 0, 1),
(2, 'Economic Climate Indicator', 0, 0, 0, 0, 0, 1),
(3, 'Durable Goods Orders', 0, 0, 0, 0, 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `econlevels`
--

CREATE TABLE IF NOT EXISTS `econlevels` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `ord` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ord_UNIQUE` (`ord`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `econlevels`
--

INSERT INTO `econlevels` (`id`, `name`, `ord`) VALUES
(1, 'Confidence', 10),
(2, 'Prices', 0),
(3, 'Consumer', 20),
(4, 'Labor', 1),
(5, 'Industry', 2),
(6, 'GDP', 3),
(7, 'Monetary', 4),
(8, 'Construction', 7);

-- --------------------------------------------------------

--
-- Table structure for table `enquiries`
--

CREATE TABLE IF NOT EXISTS `enquiries` (
  `id` bigint(21) NOT NULL AUTO_INCREMENT,
  `first_bank` bigint(21) NOT NULL,
  `second_bank` bigint(21) NOT NULL,
  `first_user` bigint(21) NOT NULL,
  `second_user` bigint(21) NOT NULL,
  `currency_pair` bigint(21) NOT NULL,
  `amount` int(11) NOT NULL,
  `price_buy` decimal(20,10) NOT NULL,
  `price_sell` decimal(20,10) NOT NULL,
  `time` bigint(21) NOT NULL,
  `status` int(11) NOT NULL,
  `pair` varchar(255) NOT NULL,
  `first_code` varchar(255) NOT NULL,
  `first_bname` varchar(255) NOT NULL,
  `second_code` varchar(255) NOT NULL,
  `second_bname` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `enquiries`
--

INSERT INTO `enquiries` (`id`, `first_bank`, `second_bank`, `first_user`, `second_user`, `currency_pair`, `amount`, `price_buy`, `price_sell`, `time`, `status`, `pair`, `first_code`, `first_bname`, `second_code`, `second_bname`) VALUES
(26, 26, 26, 15, 16, 1, 1, 3.9230000000, 3.9280000000, 1353009200, 2, 'TER/RIK', 'dinnot', 'I General Bank', 'Itrader', 'I General Bank'),
(25, 26, 27, 15, 19, 1, 1, 3.9230000000, 3.9280000000, 1353009200, 2, 'TER/RIK', 'dinnot', 'I General Bank', 'IItrader', 'II General Bank');

-- --------------------------------------------------------

--
-- Table structure for table `fx_deals`
--

CREATE TABLE IF NOT EXISTS `fx_deals` (
  `period` bigint(20) NOT NULL,
  `ccy_pair` bigint(20) NOT NULL,
  `amount_base_ccy` bigint(20) NOT NULL,
  `price` float NOT NULL,
  `counter_party` bigint(21) NOT NULL,
  `value_date` bigint(20) NOT NULL,
  `trade_date` bigint(20) NOT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(21) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=596 ;

--
-- Dumping data for table `fx_deals`
--

INSERT INTO `fx_deals` (`period`, `ccy_pair`, `amount_base_ccy`, `price`, `counter_party`, `value_date`, `trade_date`, `id`, `user_id`) VALUES
(0, 1, 12232, 2, 0, 1353257048, 1353257048, 1, 20),
(0, 2, 10851, 4, 0, 1353257050, 1353257050, 2, 20),
(0, 1, 7544, 2, 0, 1353257074, 1353257074, 3, 20),
(0, 2, 30887, 4, 0, 1353257071, 1353257071, 4, 20),
(0, 2, -6, 3.4, -1, 1353257031, 1353257031, 5, 20),
(0, 3, 6, 3.5, -1, 1353257032, 1353257032, 6, 20),
(0, 1, 18331, 2, 0, 1353257097, 1353257097, 7, 20),
(0, 2, 11046, 4, 0, 1353257096, 1353257096, 8, 20),
(0, 1, 26033, 2, 0, 1353257118, 1353257118, 9, 20),
(0, 2, 8131, 4, 0, 1353257120, 1353257120, 10, 20),
(0, 1, 16121, 2, 0, 1353257140, 1353257140, 11, 20),
(0, 2, 31270, 4, 0, 1353257143, 1353257143, 12, 20),
(0, 1, 11894, 2, 0, 1353257166, 1353257166, 13, 20),
(0, 2, 21902, 4, 0, 1353257168, 1353257168, 14, 20),
(0, 1, 26481, 2, 0, 1353257186, 1353257186, 15, 20),
(0, 2, 22173, 4, 0, 1353257193, 1353257193, 16, 20),
(0, 1, 3835, 2, 0, 1353257207, 1353257207, 17, 20),
(0, 2, 10534, 4, 0, 1353257218, 1353257218, 18, 20),
(0, 1, 6124, 2, 0, 1353257234, 1353257234, 19, 20),
(0, 2, 16079, 4, 0, 1353257238, 1353257238, 20, 20),
(0, 1, 31959, 2, 0, 1353257258, 1353257258, 21, 20),
(0, 2, 24171, 4, 0, 1353257259, 1353257259, 22, 20),
(0, 2, -3137, 3, 0, 1353257270, 1353257270, 23, 20),
(0, 1, -31435, 1, 0, 1353257274, 1353257274, 24, 20),
(0, 1, 4922, 2, 0, 1353257279, 1353257279, 25, 20),
(0, 2, 14573, 4, 0, 1353257284, 1353257284, 26, 20),
(0, 2, -5877, 3, 0, 1353257290, 1353257290, 27, 20),
(0, 1, -10546, 1, 0, 1353257298, 1353257298, 28, 20),
(0, 1, 6316, 2, 0, 1353257303, 1353257303, 29, 20),
(0, 2, 10808, 4, 0, 1353257306, 1353257306, 30, 20),
(0, 2, -21146, 3, 0, 1353257312, 1353257312, 31, 20),
(0, 1, -25433, 1, 0, 1353257323, 1353257323, 32, 20),
(0, 1, 17187, 2, 0, 1353257325, 1353257325, 33, 20),
(0, 2, 17201, 4, 0, 1353257332, 1353257332, 34, 20),
(0, 2, -13680, 3, 0, 1353257337, 1353257337, 35, 20),
(0, 1, 8595, 2, 0, 1353257345, 1353257345, 36, 20),
(0, 1, -26568, 1, 0, 1353257346, 1353257346, 37, 20),
(0, 2, 2563, 4, 0, 1353257357, 1353257357, 38, 20),
(0, 2, -30727, 3, 0, 1353257359, 1353257359, 39, 20),
(0, 1, 18693, 2, 0, 1353257365, 1353257365, 40, 20),
(0, 1, -24048, 1, 0, 1353257373, 1353257373, 41, 20),
(0, 2, -2730, 3, 0, 1353257381, 1353257381, 42, 20),
(0, 2, 31180, 4, 0, 1353257379, 1353257379, 43, 20),
(0, 1, 26606, 2, 0, 1353257388, 1353257388, 44, 20),
(0, 1, -31830, 1, 0, 1353257394, 1353257394, 45, 20),
(0, 2, 30901, 4, 0, 1353257401, 1353257401, 46, 20),
(0, 2, -24315, 3, 0, 1353257403, 1353257403, 47, 20),
(0, 1, 24113, 2, 0, 1353257410, 1353257410, 48, 20),
(0, 1, -25289, 1, 0, 1353257419, 1353257419, 49, 20),
(0, 2, 11118, 4, 0, 1353257422, 1353257422, 50, 20),
(0, 2, -20650, 3, 0, 1353257428, 1353257428, 51, 20),
(0, 1, 13476, 2, 0, 1353257434, 1353257434, 52, 20),
(0, 1, -10994, 1, 0, 1353257445, 1353257445, 53, 20),
(0, 2, 14022, 4, 0, 1353257445, 1353257445, 54, 20),
(0, 2, -4, 23.23, -2, 1353257449, 1353257449, 55, 20),
(0, 2, -6337, 3, 0, 1353257453, 1353257453, 56, 20),
(0, 1, 19911, 2, 0, 1353257457, 1353257457, 57, 20),
(0, 1, -18081, 1, 0, 1353257465, 1353257465, 58, 20),
(0, 2, 11535, 4, 0, 1353257467, 1353257467, 59, 20),
(0, 2, -11192, 3, 0, 1353257473, 1353257473, 60, 20),
(0, 1, 30843, 2, 0, 1353257478, 1353257478, 61, 20),
(0, 1, -6394, 1, 0, 1353257487, 1353257487, 62, 20),
(0, 2, 31635, 4, 0, 1353257492, 1353257492, 63, 20),
(0, 2, -25706, 3, 0, 1353257494, 1353257494, 64, 20),
(0, 1, 31704, 2, 0, 1353257504, 1353257504, 65, 20),
(0, 1, -2506, 1, 0, 1353257509, 1353257509, 66, 20),
(0, 2, 3235, 4, 0, 1353257517, 1353257517, 67, 20),
(0, 2, -24122, 3, 0, 1353257518, 1353257518, 68, 20),
(0, 1, 10526, 2, 0, 1353257529, 1353257529, 69, 20),
(0, 1, -19721, 1, 0, 1353257531, 1353257531, 70, 20),
(0, 2, 6233, 4, 0, 1353257540, 1353257540, 71, 20),
(0, 2, -5868, 3, 0, 1353257542, 1353257542, 72, 20),
(0, 1, 27578, 2, 0, 1353257551, 1353257551, 73, 20),
(0, 1, -6634, 1, 0, 1353257555, 1353257555, 74, 20),
(0, 2, 25349, 4, 0, 1353257571, 1353257571, 75, 20),
(0, 2, -27584, 3, 0, 1353257573, 1353257573, 76, 20),
(0, 1, 19455, 2, 0, 1353257581, 1353257581, 77, 20),
(0, 1, -24273, 1, 0, 1353257585, 1353257585, 78, 20),
(0, 2, 28560, 4, 0, 1353257601, 1353257601, 79, 20),
(0, 2, -30675, 3, 0, 1353257604, 1353257604, 80, 20),
(0, 1, 10378, 2, 0, 1353257612, 1353257612, 81, 20),
(0, 1, -21949, 1, 0, 1353257616, 1353257616, 82, 20),
(0, 2, 29125, 4, 0, 1353257632, 1353257632, 83, 20),
(0, 2, -8038, 3, 0, 1353257636, 1353257636, 84, 20),
(0, 1, 20164, 2, 0, 1353257644, 1353257644, 85, 20),
(0, 1, -24027, 1, 0, 1353257648, 1353257648, 86, 20),
(0, 2, 12929, 4, 0, 1353257664, 1353257664, 87, 20),
(0, 2, -4570, 3, 0, 1353257668, 1353257668, 88, 20),
(0, 1, 14474, 2, 0, 1353257676, 1353257676, 89, 20),
(0, 1, -23653, 1, 0, 1353257680, 1353257680, 90, 20),
(0, 2, 21423, 4, 0, 1353257696, 1353257696, 91, 20),
(0, 2, -8294, 3, 0, 1353257700, 1353257700, 92, 20),
(0, 1, 1826, 2, 0, 1353257708, 1353257708, 93, 20),
(0, 1, -2221, 1, 0, 1353257712, 1353257712, 94, 20),
(0, 2, 20001, 4, 0, 1353257728, 1353257728, 95, 20),
(0, 2, -3862, 3, 0, 1353257732, 1353257732, 96, 20),
(0, 2, 2940, 4, 0, 1353257758, 1353257758, 97, 20),
(0, 2, -4042, 3, 0, 1353257766, 1353257766, 98, 20),
(0, 1, 14811, 2, 0, 1353257785, 1353257785, 99, 20),
(0, 1, -22457, 1, 0, 1353257789, 1353257789, 100, 20),
(0, 2, 17750, 4, 0, 1353257790, 1353257790, 101, 20),
(0, 2, -28929, 3, 0, 1353257798, 1353257798, 102, 20),
(0, 2, 27519, 4, 0, 1353257822, 1353257822, 103, 20),
(0, 2, -26330, 3, 0, 1353257830, 1353257830, 104, 20),
(0, 2, 2299, 4, 0, 1353257854, 1353257854, 105, 20),
(0, 2, -25241, 3, 0, 1353257862, 1353257862, 106, 20),
(0, 1, 5304, 2, 0, 1353257863, 1353257863, 107, 20),
(0, 1, -18991, 1, 0, 1353257867, 1353257867, 108, 20),
(0, 2, 12317, 4, 0, 1353257886, 1353257886, 109, 20),
(0, 2, -10449, 3, 0, 1353257894, 1353257894, 110, 20),
(0, 2, 31349, 4, 0, 1353257917, 1353257917, 111, 20),
(0, 2, -11312, 3, 0, 1353257925, 1353257925, 112, 20),
(0, 1, 26086, 2, 0, 1353257939, 1353257939, 113, 20),
(0, 1, -19884, 1, 0, 1353257943, 1353257943, 114, 20),
(0, 2, 30968, 4, 0, 1353257949, 1353257949, 115, 20),
(0, 2, -31227, 3, 0, 1353257957, 1353257957, 116, 20),
(0, 2, 26549, 4, 0, 1353257981, 1353257981, 117, 20),
(0, 2, -1773, 3, 0, 1353257989, 1353257989, 118, 20),
(0, 1, 7473, 2, 0, 1353258014, 1353258014, 119, 20),
(0, 1, -2804, 1, 0, 1353258018, 1353258018, 120, 20),
(0, 2, 19627, 4, 0, 1353258058, 1353258058, 121, 20),
(0, 1, -1904, 1, 0, 1353258095, 1353258095, 122, 20),
(0, 2, -3942, 3, 0, 1353258126, 1353258126, 123, 20),
(0, 1, 4058, 2, 0, 1353258091, 1353258091, 124, 20),
(0, 2, 24002, 4, 0, 1353258134, 1353258134, 125, 20),
(0, 2, -10, 2.23, -1, 1353359937, 1353359937, 126, 20),
(0, 1, -8379, 1, 0, 1353359940, 1353359940, 127, 20),
(0, 2, -4770, 3, 0, 1353359941, 1353359941, 128, 20),
(0, 1, 23435, 2, 0, 1353359941, 1353359941, 129, 20),
(0, 2, 23422, 4, 0, 1353359941, 1353359941, 130, 20),
(0, 1, -4211, 1, 0, 1353360017, 1353360017, 131, 20),
(0, 2, -18290, 3, 0, 1353360017, 1353360017, 132, 20),
(0, 1, 4594, 2, 0, 1353360017, 1353360017, 133, 20),
(0, 2, 14892, 4, 0, 1353360017, 1353360017, 134, 20),
(0, 1, -14040, 1, 0, 1353360093, 1353360093, 135, 20),
(0, 2, -20291, 3, 0, 1353360093, 1353360093, 136, 20),
(0, 1, 22823, 2, 0, 1353360093, 1353360093, 137, 20),
(0, 2, 8035, 4, 0, 1353360093, 1353360093, 138, 20),
(0, 1, -24713, 1, 0, 1353360169, 1353360169, 139, 20),
(0, 2, -24381, 3, 0, 1353360169, 1353360169, 140, 20),
(0, 1, 1045, 2, 0, 1353360170, 1353360170, 141, 20),
(0, 2, 8939, 4, 0, 1353360170, 1353360170, 142, 20),
(0, 1, -18046, 1, 0, 1353360483, 1353360483, 143, 20),
(0, 2, -6955, 3, 0, 1353360484, 1353360484, 144, 20),
(0, 1, 12474, 2, 0, 1353360484, 1353360484, 145, 20),
(0, 2, 3055, 4, 0, 1353360484, 1353360484, 146, 20),
(0, 1, -26251, 1, 0, 1353360615, 1353360615, 147, 20),
(0, 2, -18152, 3, 0, 1353360615, 1353360615, 148, 20),
(0, 1, 19172, 2, 0, 1353360616, 1353360616, 149, 20),
(0, 2, 24156, 4, 0, 1353360616, 1353360616, 150, 20),
(0, 1, -8280, 1, 0, 1353360693, 1353360693, 151, 20),
(0, 2, -28268, 3, 0, 1353360693, 1353360693, 152, 20),
(0, 1, 5565, 2, 0, 1353360693, 1353360693, 153, 20),
(0, 2, 10049, 4, 0, 1353360694, 1353360694, 154, 20),
(0, 1, -9803, 1, 0, 1353360771, 1353360771, 155, 20),
(0, 2, -22154, 3, 0, 1353360771, 1353360771, 156, 20),
(0, 1, 2323, 2, 0, 1353360772, 1353360772, 157, 20),
(0, 2, 6892, 4, 0, 1353360772, 1353360772, 158, 20),
(0, 1, -10891, 2.2, 0, 1353360833, 1353360833, 159, 20),
(0, 1, 30184, 3.1, 0, 1353360833, 1353360833, 160, 20),
(0, 2, -10284, 2.3, 0, 1353360843, 1353360843, 161, 20),
(0, 2, 19772, 3.23, 0, 1353360843, 1353360843, 162, 20),
(0, 1, -30612, 2.2, 0, 1353360866, 1353360866, 163, 20),
(0, 1, 24378, 3.1, 0, 1353360866, 1353360866, 164, 20),
(0, 2, -22289, 2.3, 0, 1353360874, 1353360874, 165, 20),
(0, 2, 27859, 3.23, 0, 1353360874, 1353360874, 166, 20),
(0, 1, -4, 1.23, -2, 1353360883, 1353360883, 167, 20),
(0, 1, 5, 2.23, -2, 1353360884, 1353360884, 168, 20),
(0, 1, 3, 1.23, -1, 1353360884, 1353360884, 169, 20),
(0, 2, 10, 23, -2, 1353360884, 1353360884, 170, 20),
(0, 2, 2, 1.23, -2, 1353360887, 1353360887, 171, 20),
(0, 2, -5, 43.12, -1, 1353360887, 1353360887, 172, 20),
(0, 1, -14876, 2.2, 0, 1353360897, 1353360897, 173, 20),
(0, 1, 6254, 3.1, 0, 1353360898, 1353360898, 174, 20),
(0, 2, -9385, 2.3, 0, 1353360906, 1353360906, 175, 20),
(0, 2, 17364, 3.23, 0, 1353360906, 1353360906, 176, 20),
(0, 2, 1, 12.23, -2, 1353360916, 1353360916, 177, 20),
(0, 1, -4123, 2.2, 0, 1353360930, 1353360930, 178, 20),
(0, 1, 26701, 3.1, 0, 1353360930, 1353360930, 179, 20),
(0, 2, -10249, 2.3, 0, 1353360938, 1353360938, 180, 20),
(0, 2, 30039, 3.23, 0, 1353360938, 1353360938, 181, 20),
(0, 1, -3286, 2.2, 0, 1353360961, 1353360961, 182, 20),
(0, 1, 29601, 3.1, 0, 1353360962, 1353360962, 183, 20),
(0, 2, -25095, 2.3, 0, 1353360969, 1353360969, 184, 20),
(0, 2, 17510, 3.23, 0, 1353360970, 1353360970, 185, 20),
(0, 1, -3136, 2.2, 0, 1353360994, 1353360994, 186, 20),
(0, 1, 27991, 3.1, 0, 1353360994, 1353360994, 187, 20),
(0, 2, -7081, 2.3, 0, 1353361002, 1353361002, 188, 20),
(0, 2, 14265, 3.23, 0, 1353361002, 1353361002, 189, 20),
(0, 1, -8358, 2.2, 0, 1353361026, 1353361026, 190, 20),
(0, 1, 21062, 3.1, 0, 1353361026, 1353361026, 191, 20),
(0, 2, -5735, 2.3, 0, 1353361034, 1353361034, 192, 20),
(0, 2, 11816, 3.23, 0, 1353361034, 1353361034, 193, 20),
(0, 1, -16710, 2.2, 0, 1353361058, 1353361058, 194, 20),
(0, 1, 31175, 3.1, 0, 1353361058, 1353361058, 195, 20),
(0, 2, -6689, 2.3, 0, 1353361066, 1353361066, 196, 20),
(0, 2, 16784, 3.23, 0, 1353361066, 1353361066, 197, 20),
(0, 1, -25713, 2.2, 0, 1353361090, 1353361090, 198, 20),
(0, 1, 21395, 3.1, 0, 1353361090, 1353361090, 199, 20),
(0, 2, -9064, 2.3, 0, 1353361098, 1353361098, 200, 20),
(0, 2, 24513, 3.23, 0, 1353361098, 1353361098, 201, 20),
(0, 1, -29398, 2.2, 0, 1353361122, 1353361122, 202, 20),
(0, 1, 13151, 3.1, 0, 1353361122, 1353361122, 203, 20),
(0, 2, -17852, 2.3, 0, 1353361130, 1353361130, 204, 20),
(0, 2, 11310, 3.23, 0, 1353361130, 1353361130, 205, 20),
(0, 1, -29860, 2.2, 0, 1353361154, 1353361154, 206, 20),
(0, 1, 3608, 3.1, 0, 1353361154, 1353361154, 207, 20),
(0, 2, -29049, 2.3, 0, 1353361162, 1353361162, 208, 20),
(0, 2, 3429, 3.23, 0, 1353361162, 1353361162, 209, 20),
(0, 1, -11719, 2.2, 0, 1353361186, 1353361186, 210, 20),
(0, 1, 19686, 3.1, 0, 1353361186, 1353361186, 211, 20),
(0, 2, -14493, 2.3, 0, 1353361194, 1353361194, 212, 20),
(0, 2, 10815, 3.23, 0, 1353361194, 1353361194, 213, 20),
(0, 1, -15461, 2.2, 0, 1353361218, 1353361218, 214, 20),
(0, 1, 15886, 3.1, 0, 1353361218, 1353361218, 215, 20),
(0, 2, -3756, 2.3, 0, 1353361226, 1353361226, 216, 20),
(0, 2, 24105, 3.23, 0, 1353361226, 1353361226, 217, 20),
(0, 1, -12268, 2.2, 0, 1353361250, 1353361250, 218, 20),
(0, 1, 22194, 3.1, 0, 1353361250, 1353361250, 219, 20),
(0, 2, -15102, 2.3, 0, 1353361258, 1353361258, 220, 20),
(0, 2, 13252, 3.23, 0, 1353361258, 1353361258, 221, 20),
(0, 1, -7526, 2.2, 0, 1353361282, 1353361282, 222, 20),
(0, 1, 31123, 3.1, 0, 1353361282, 1353361282, 223, 20),
(0, 2, -17364, 2.3, 0, 1353361290, 1353361290, 224, 20),
(0, 2, 2185, 3.23, 0, 1353361290, 1353361290, 225, 20),
(0, 1, -7648, 2.2, 0, 1353361314, 1353361314, 226, 20),
(0, 1, 23307, 3.1, 0, 1353361314, 1353361314, 227, 20),
(0, 2, -15041, 2.3, 0, 1353361322, 1353361322, 228, 20),
(0, 2, 14756, 3.23, 0, 1353361322, 1353361322, 229, 20),
(0, 1, -19807, 2.2, 0, 1353361346, 1353361346, 230, 20),
(0, 1, 23511, 3.1, 0, 1353361346, 1353361346, 231, 20),
(0, 2, -31209, 2.3, 0, 1353361354, 1353361354, 232, 20),
(0, 2, 28109, 3.23, 0, 1353361354, 1353361354, 233, 20),
(0, 1, -29440, 2.2, 0, 1353361378, 1353361378, 234, 20),
(0, 1, 4295, 3.1, 0, 1353361378, 1353361378, 235, 20),
(0, 2, -29190, 2.3, 0, 1353361386, 1353361386, 236, 20),
(0, 2, 16032, 3.23, 0, 1353361386, 1353361386, 237, 20),
(0, 1, -28691, 2.2, 0, 1353361410, 1353361410, 238, 20),
(0, 1, 20611, 3.1, 0, 1353361410, 1353361410, 239, 20),
(0, 2, -27166, 2.3, 0, 1353361418, 1353361418, 240, 20),
(0, 2, 20536, 3.23, 0, 1353361418, 1353361418, 241, 20),
(0, 1, -19244, 2.2, 0, 1353361442, 1353361442, 242, 20),
(0, 1, 7975, 3.1, 0, 1353361442, 1353361442, 243, 20),
(0, 2, -1791, 2.3, 0, 1353361450, 1353361450, 244, 20),
(0, 2, 31773, 3.23, 0, 1353361450, 1353361450, 245, 20),
(0, 1, -23438, 2.2, 0, 1353361474, 1353361474, 246, 20),
(0, 1, 27695, 3.1, 0, 1353361474, 1353361474, 247, 20),
(0, 2, -24579, 2.3, 0, 1353361482, 1353361482, 248, 20),
(0, 2, 23699, 3.23, 0, 1353361482, 1353361482, 249, 20),
(0, 1, -26436, 2.2, 0, 1353361506, 1353361506, 250, 20),
(0, 1, 14210, 3.1, 0, 1353361506, 1353361506, 251, 20),
(0, 2, -31913, 2.3, 0, 1353361514, 1353361514, 252, 20),
(0, 2, 30740, 3.23, 0, 1353361514, 1353361514, 253, 20),
(0, 1, -29053, 2.2, 0, 1353361538, 1353361538, 254, 20),
(0, 1, 13956, 3.1, 0, 1353361538, 1353361538, 255, 20),
(0, 2, -31833, 2.3, 0, 1353361546, 1353361546, 256, 20),
(0, 2, 29422, 3.23, 0, 1353361546, 1353361546, 257, 20),
(0, 1, -19908, 2.2, 0, 1353361570, 1353361570, 258, 20),
(0, 1, 2723, 3.1, 0, 1353361570, 1353361570, 259, 20),
(0, 2, -30639, 2.3, 0, 1353361578, 1353361578, 260, 20),
(0, 2, 13035, 3.23, 0, 1353361578, 1353361578, 261, 20),
(0, 1, -11884, 2.2, 0, 1353361602, 1353361602, 262, 20),
(0, 1, 12755, 3.1, 0, 1353361602, 1353361602, 263, 20),
(0, 2, -28716, 2.3, 0, 1353361610, 1353361610, 264, 20),
(0, 2, 29545, 3.23, 0, 1353361610, 1353361610, 265, 20),
(0, 1, -13464, 2.2, 0, 1353361634, 1353361634, 266, 20),
(0, 1, 15269, 3.1, 0, 1353361634, 1353361634, 267, 20),
(0, 2, -20190, 2.3, 0, 1353361642, 1353361642, 268, 20),
(0, 2, 21897, 3.23, 0, 1353361642, 1353361642, 269, 20),
(0, 1, -21562, 2.2, 0, 1353361666, 1353361666, 270, 20),
(0, 1, 8035, 3.1, 0, 1353361666, 1353361666, 271, 20),
(0, 2, -29897, 2.3, 0, 1353361674, 1353361674, 272, 20),
(0, 2, 19660, 3.23, 0, 1353361674, 1353361674, 273, 20),
(0, 1, -13363, 2.2, 0, 1353361698, 1353361698, 274, 20),
(0, 1, 26035, 3.1, 0, 1353361698, 1353361698, 275, 20),
(0, 2, -2507, 2.3, 0, 1353361706, 1353361706, 276, 20),
(0, 2, 21089, 3.23, 0, 1353361706, 1353361706, 277, 20),
(0, 1, -25843, 2.2, 0, 1353361730, 1353361730, 278, 20),
(0, 1, 24057, 3.1, 0, 1353361730, 1353361730, 279, 20),
(0, 1, -17850, 2.2, 0, 1353361762, 1353361762, 280, 20),
(0, 2, -16141, 2.3, 0, 1353361738, 1353361738, 281, 20),
(0, 1, 3291, 3.1, 0, 1353361762, 1353361762, 282, 20),
(0, 2, 5982, 3.23, 0, 1353361738, 1353361738, 283, 20),
(0, 1, -12790, 2.2, 0, 1353406178, 1353406178, 284, 20),
(0, 2, -24380, 2.3, 0, 1353406179, 1353406179, 285, 20),
(0, 1, 20227, 3.1, 0, 1353406179, 1353406179, 286, 20),
(0, 2, 14319, 3.23, 0, 1353406179, 1353406179, 287, 20),
(0, 1, -3402, 2.2, 0, 1353406209, 1353406209, 288, 20),
(0, 2, -23343, 2.3, 0, 1353406210, 1353406210, 289, 20),
(0, 1, 28203, 3.1, 0, 1353406210, 1353406210, 290, 20),
(0, 2, 3341, 3.23, 0, 1353406210, 1353406210, 291, 20),
(0, 1, -9894, 2.2, 0, 1353406241, 1353406241, 292, 20),
(0, 2, -2502, 2.3, 0, 1353406242, 1353406242, 293, 20),
(0, 1, 28758, 3.1, 0, 1353406242, 1353406242, 294, 20),
(0, 2, 1773, 3.23, 0, 1353406242, 1353406242, 295, 20),
(0, 1, -17297, 2.2, 0, 1353406273, 1353406273, 296, 20),
(0, 2, -1898, 2.3, 0, 1353406274, 1353406274, 297, 20),
(0, 1, 11268, 3.1, 0, 1353406274, 1353406274, 298, 20),
(0, 2, 2638, 3.23, 0, 1353406274, 1353406274, 299, 20),
(0, 1, -10817, 2.2, 0, 1353406306, 1353406306, 300, 20),
(0, 2, -16231, 2.3, 0, 1353406306, 1353406306, 301, 20),
(0, 1, 14801, 3.1, 0, 1353406306, 1353406306, 302, 20),
(0, 2, 15976, 3.23, 0, 1353406306, 1353406306, 303, 20),
(0, 1, -8976, 2.2, 0, 1353406338, 1353406338, 304, 20),
(0, 2, -2841, 2.3, 0, 1353406338, 1353406338, 305, 20),
(0, 1, 9361, 3.1, 0, 1353406338, 1353406338, 306, 20),
(0, 2, 10735, 3.23, 0, 1353406338, 1353406338, 307, 20),
(0, 1, -13332, 2.2, 0, 1353406369, 1353406369, 308, 20),
(0, 2, -23457, 2.3, 0, 1353406370, 1353406370, 309, 20),
(0, 1, 1952, 3.1, 0, 1353406370, 1353406370, 310, 20),
(0, 2, 5957, 3.23, 0, 1353406370, 1353406370, 311, 20),
(0, 1, -26858, 2.2, 0, 1353406402, 1353406402, 312, 20),
(0, 2, -5888, 2.3, 0, 1353406402, 1353406402, 313, 20),
(0, 1, 22265, 3.1, 0, 1353406402, 1353406402, 314, 20),
(0, 2, 14676, 3.23, 0, 1353406402, 1353406402, 315, 20),
(0, 1, -22134, 2.2, 0, 1353406434, 1353406434, 316, 20),
(0, 2, -12683, 2.3, 0, 1353406434, 1353406434, 317, 20),
(0, 1, 25647, 3.1, 0, 1353406434, 1353406434, 318, 20),
(0, 2, 24760, 3.23, 0, 1353406434, 1353406434, 319, 20),
(0, 1, -30013, 2.2, 0, 1353406466, 1353406466, 320, 20),
(0, 2, -25524, 2.3, 0, 1353406466, 1353406466, 321, 20),
(0, 1, 4999, 3.1, 0, 1353406466, 1353406466, 322, 20),
(0, 2, 23107, 3.23, 0, 1353406466, 1353406466, 323, 20),
(0, 1, -15379, 2.2, 0, 1353406498, 1353406498, 324, 20),
(0, 2, -22704, 2.3, 0, 1353406498, 1353406498, 325, 20),
(0, 1, 17645, 3.1, 0, 1353406498, 1353406498, 326, 20),
(0, 2, 26819, 3.23, 0, 1353406498, 1353406498, 327, 20),
(0, 1, -9454, 2.2, 0, 1353406530, 1353406530, 328, 20),
(0, 2, -13261, 2.3, 0, 1353406530, 1353406530, 329, 20),
(0, 1, 15437, 3.1, 0, 1353406530, 1353406530, 330, 20),
(0, 2, 16125, 3.23, 0, 1353406530, 1353406530, 331, 20),
(0, 1, -12155, 2.2, 0, 1353406562, 1353406562, 332, 20),
(0, 2, -26752, 2.3, 0, 1353406562, 1353406562, 333, 20),
(0, 1, 6795, 3.1, 0, 1353406562, 1353406562, 334, 20),
(0, 2, 24147, 3.23, 0, 1353406563, 1353406563, 335, 20),
(0, 1, -11949, 2.2, 0, 1353406594, 1353406594, 336, 20),
(0, 2, -18369, 2.3, 0, 1353406594, 1353406594, 337, 20),
(0, 1, 6315, 3.1, 0, 1353406594, 1353406594, 338, 20),
(0, 2, 14178, 3.23, 0, 1353406594, 1353406594, 339, 20),
(0, 1, -16065, 2.2, 0, 1353406626, 1353406626, 340, 20),
(0, 2, -5646, 2.3, 0, 1353406626, 1353406626, 341, 20),
(0, 1, 17598, 3.1, 0, 1353406626, 1353406626, 342, 20),
(0, 2, 28728, 3.23, 0, 1353406626, 1353406626, 343, 20),
(0, 1, -3276, 2.2, 0, 1353406658, 1353406658, 344, 20),
(0, 2, -19290, 2.3, 0, 1353406658, 1353406658, 345, 20),
(0, 1, 25281, 3.1, 0, 1353406658, 1353406658, 346, 20),
(0, 2, 10276, 3.23, 0, 1353406658, 1353406658, 347, 20),
(0, 1, -7632, 2.2, 0, 1353406690, 1353406690, 348, 20),
(0, 2, -13084, 2.3, 0, 1353406690, 1353406690, 349, 20),
(0, 1, 16161, 3.1, 0, 1353406690, 1353406690, 350, 20),
(0, 2, 1404, 3.23, 0, 1353406690, 1353406690, 351, 20),
(0, 1, -29229, 2.2, 0, 1353406722, 1353406722, 352, 20),
(0, 2, -3684, 2.3, 0, 1353406722, 1353406722, 353, 20),
(0, 1, 30895, 3.1, 0, 1353406722, 1353406722, 354, 20),
(0, 2, 21495, 3.23, 0, 1353406722, 1353406722, 355, 20),
(0, 1, -24602, 2.2, 0, 1353406754, 1353406754, 356, 20),
(0, 2, -12723, 2.3, 0, 1353406754, 1353406754, 357, 20),
(0, 1, 2590, 3.1, 0, 1353406754, 1353406754, 358, 20),
(0, 2, 1871, 3.23, 0, 1353406754, 1353406754, 359, 20),
(0, 1, -4456, 2.2, 0, 1353406786, 1353406786, 360, 20),
(0, 2, -10752, 2.3, 0, 1353406786, 1353406786, 361, 20),
(0, 1, 3293, 3.1, 0, 1353406786, 1353406786, 362, 20),
(0, 2, 20050, 3.23, 0, 1353406787, 1353406787, 363, 20),
(0, 1, -24324, 2.2, 0, 1353406818, 1353406818, 364, 20),
(0, 2, -25935, 2.3, 0, 1353406818, 1353406818, 365, 20),
(0, 1, 29451, 3.1, 0, 1353406818, 1353406818, 366, 20),
(0, 2, 21111, 3.23, 0, 1353406818, 1353406818, 367, 20),
(0, 1, -2019, 2.2, 0, 1353406850, 1353406850, 368, 20),
(0, 2, -24740, 2.3, 0, 1353406850, 1353406850, 369, 20),
(0, 1, 4672, 3.1, 0, 1353406850, 1353406850, 370, 20),
(0, 2, 6145, 3.23, 0, 1353406850, 1353406850, 371, 20),
(0, 1, -29330, 2.2, 0, 1353406882, 1353406882, 372, 20),
(0, 2, -23489, 2.3, 0, 1353406882, 1353406882, 373, 20),
(0, 1, 29741, 3.1, 0, 1353406882, 1353406882, 374, 20),
(0, 2, 10545, 3.23, 0, 1353406883, 1353406883, 375, 20),
(0, 1, -2688, 2.2, 0, 1353406914, 1353406914, 376, 20),
(0, 2, -30586, 2.3, 0, 1353406914, 1353406914, 377, 20),
(0, 1, 12545, 3.1, 0, 1353406914, 1353406914, 378, 20),
(0, 2, 17000, 3.23, 0, 1353406914, 1353406914, 379, 20),
(0, 1, -12602, 2.2, 0, 1353406946, 1353406946, 380, 20),
(0, 2, -30864, 2.3, 0, 1353406946, 1353406946, 381, 20),
(0, 1, 13365, 3.1, 0, 1353406946, 1353406946, 382, 20),
(0, 2, 10301, 3.23, 0, 1353406946, 1353406946, 383, 20),
(0, 1, -25544, 2.2, 0, 1353406978, 1353406978, 384, 20),
(0, 2, -23352, 2.3, 0, 1353406978, 1353406978, 385, 20),
(0, 1, 26398, 3.1, 0, 1353406978, 1353406978, 386, 20),
(0, 2, 2317, 3.23, 0, 1353406978, 1353406978, 387, 20),
(0, 1, -13132, 2.2, 0, 1353407010, 1353407010, 388, 20),
(0, 2, -15558, 2.3, 0, 1353407010, 1353407010, 389, 20),
(0, 1, 2291, 3.1, 0, 1353407010, 1353407010, 390, 20),
(0, 2, 25741, 3.23, 0, 1353407011, 1353407011, 391, 20),
(0, 1, -19648, 2.2, 0, 1353407042, 1353407042, 392, 20),
(0, 2, -1369, 2.3, 0, 1353407042, 1353407042, 393, 20),
(0, 1, 9719, 3.1, 0, 1353407042, 1353407042, 394, 20),
(0, 2, 26960, 3.23, 0, 1353407043, 1353407043, 395, 20),
(0, 1, -30439, 2.2, 0, 1353407074, 1353407074, 396, 20),
(0, 2, -24307, 2.3, 0, 1353407074, 1353407074, 397, 20),
(0, 1, 31386, 3.1, 0, 1353407074, 1353407074, 398, 20),
(0, 2, 6748, 3.23, 0, 1353407074, 1353407074, 399, 20),
(0, 1, -18853, 2.2, 0, 1353407106, 1353407106, 400, 20),
(0, 2, -10407, 2.3, 0, 1353407106, 1353407106, 401, 20),
(0, 1, 24933, 3.1, 0, 1353407106, 1353407106, 402, 20),
(0, 2, 31552, 3.23, 0, 1353407106, 1353407106, 403, 20),
(0, 1, -5348, 2.2, 0, 1353407138, 1353407138, 404, 20),
(0, 2, -31065, 2.3, 0, 1353407138, 1353407138, 405, 20),
(0, 1, 31023, 3.1, 0, 1353407138, 1353407138, 406, 20),
(0, 2, 25959, 3.23, 0, 1353407138, 1353407138, 407, 20),
(0, 1, -8470, 2.2, 0, 1353407170, 1353407170, 408, 20),
(0, 2, -21396, 2.3, 0, 1353407170, 1353407170, 409, 20),
(0, 1, 30500, 3.1, 0, 1353407170, 1353407170, 410, 20),
(0, 2, 16300, 3.23, 0, 1353407170, 1353407170, 411, 20),
(0, 1, -27297, 2.2, 0, 1353407202, 1353407202, 412, 20),
(0, 2, -2233, 2.3, 0, 1353407202, 1353407202, 413, 20),
(0, 1, 10910, 3.1, 0, 1353407202, 1353407202, 414, 20),
(0, 2, 2266, 3.23, 0, 1353407202, 1353407202, 415, 20),
(0, 1, -21132, 2.2, 0, 1353407234, 1353407234, 416, 20),
(0, 2, -20624, 2.3, 0, 1353407234, 1353407234, 417, 20),
(0, 1, 4071, 3.1, 0, 1353407234, 1353407234, 418, 20),
(0, 2, 25726, 3.23, 0, 1353407234, 1353407234, 419, 20),
(0, 1, -5489, 2.2, 0, 1353407266, 1353407266, 420, 20),
(0, 2, -19800, 2.3, 0, 1353407266, 1353407266, 421, 20),
(0, 1, 3343, 3.1, 0, 1353407266, 1353407266, 422, 20),
(0, 2, 7065, 3.23, 0, 1353407266, 1353407266, 423, 20),
(0, 1, -25531, 2.2, 0, 1353407298, 1353407298, 424, 20),
(0, 2, -9154, 2.3, 0, 1353407298, 1353407298, 425, 20),
(0, 1, 18753, 3.1, 0, 1353407298, 1353407298, 426, 20),
(0, 2, 13914, 3.23, 0, 1353407298, 1353407298, 427, 20),
(0, 1, -22622, 2.2, 0, 1353407330, 1353407330, 428, 20),
(0, 2, -5888, 2.3, 0, 1353407330, 1353407330, 429, 20),
(0, 1, 13418, 3.1, 0, 1353407330, 1353407330, 430, 20),
(0, 2, 2520, 3.23, 0, 1353407331, 1353407331, 431, 20),
(0, 1, -7973, 2.2, 0, 1353407362, 1353407362, 432, 20),
(0, 2, -1170, 2.3, 0, 1353407362, 1353407362, 433, 20),
(0, 1, 9324, 3.1, 0, 1353407362, 1353407362, 434, 20),
(0, 2, 28923, 3.23, 0, 1353407362, 1353407362, 435, 20),
(0, 1, -10768, 2.2, 0, 1353407394, 1353407394, 436, 20),
(0, 2, -2359, 2.3, 0, 1353407394, 1353407394, 437, 20),
(0, 1, 21852, 3.1, 0, 1353407394, 1353407394, 438, 20),
(0, 2, 2183, 3.23, 0, 1353407394, 1353407394, 439, 20),
(0, 1, -22421, 2.2, 0, 1353407426, 1353407426, 440, 20),
(0, 2, -1897, 2.3, 0, 1353407426, 1353407426, 441, 20),
(0, 1, 23575, 3.1, 0, 1353407426, 1353407426, 442, 20),
(0, 2, 2802, 3.23, 0, 1353407427, 1353407427, 443, 20),
(0, 1, -24703, 2.2, 0, 1353407458, 1353407458, 444, 20),
(0, 2, -28839, 2.3, 0, 1353407458, 1353407458, 445, 20),
(0, 1, 17426, 3.1, 0, 1353407459, 1353407459, 446, 20),
(0, 2, 19322, 3.23, 0, 1353407459, 1353407459, 447, 20),
(0, 1, -7272, 2.2, 0, 1353407490, 1353407490, 448, 20),
(0, 2, -12087, 2.3, 0, 1353407490, 1353407490, 449, 20),
(0, 1, 21831, 3.1, 0, 1353407490, 1353407490, 450, 20),
(0, 2, 17351, 3.23, 0, 1353407490, 1353407490, 451, 20),
(0, 1, -30607, 2.2, 0, 1353407522, 1353407522, 452, 20),
(0, 2, -2952, 2.3, 0, 1353407522, 1353407522, 453, 20),
(0, 1, 7420, 3.1, 0, 1353407522, 1353407522, 454, 20),
(0, 2, 2033, 3.23, 0, 1353407522, 1353407522, 455, 20),
(0, 1, -17960, 2.2, 0, 1353407554, 1353407554, 456, 20),
(0, 2, -7149, 2.3, 0, 1353407554, 1353407554, 457, 20),
(0, 1, 9484, 3.1, 0, 1353407554, 1353407554, 458, 20),
(0, 2, 28957, 3.23, 0, 1353407555, 1353407555, 459, 20),
(0, 1, -12109, 2.2, 0, 1353407586, 1353407586, 460, 20),
(0, 2, -12336, 2.3, 0, 1353407586, 1353407586, 461, 20),
(0, 1, 27722, 3.1, 0, 1353407586, 1353407586, 462, 20),
(0, 2, 17481, 3.23, 0, 1353407587, 1353407587, 463, 20),
(0, 1, -12355, 2.2, 0, 1353407618, 1353407618, 464, 20),
(0, 2, -14524, 2.3, 0, 1353407618, 1353407618, 465, 20),
(0, 1, 31145, 3.1, 0, 1353407618, 1353407618, 466, 20),
(0, 2, 9560, 3.23, 0, 1353407618, 1353407618, 467, 20),
(0, 1, -16017, 2.2, 0, 1353407650, 1353407650, 468, 20),
(0, 2, -13241, 2.3, 0, 1353407650, 1353407650, 469, 20),
(0, 1, 14302, 3.1, 0, 1353407650, 1353407650, 470, 20),
(0, 2, 28247, 3.23, 0, 1353407651, 1353407651, 471, 20),
(0, 1, -24045, 2.2, 0, 1353407682, 1353407682, 472, 20),
(0, 2, -24709, 2.3, 0, 1353407682, 1353407682, 473, 20),
(0, 1, 1232, 3.1, 0, 1353407682, 1353407682, 474, 20),
(0, 2, 24487, 3.23, 0, 1353407683, 1353407683, 475, 20),
(0, 1, -29392, 2.2, 0, 1353407714, 1353407714, 476, 20),
(0, 2, -6886, 2.3, 0, 1353407714, 1353407714, 477, 20),
(0, 1, 21350, 3.1, 0, 1353407714, 1353407714, 478, 20),
(0, 2, 20613, 3.23, 0, 1353407714, 1353407714, 479, 20),
(0, 1, -5853, 2.2, 0, 1353407746, 1353407746, 480, 20),
(0, 2, -27319, 2.3, 0, 1353407746, 1353407746, 481, 20),
(0, 1, 15999, 3.1, 0, 1353407746, 1353407746, 482, 20),
(0, 2, 24085, 3.23, 0, 1353407746, 1353407746, 483, 20),
(0, 1, -19993, 2.2, 0, 1353407778, 1353407778, 484, 20),
(0, 2, -7444, 2.3, 0, 1353407778, 1353407778, 485, 20),
(0, 1, 16592, 3.1, 0, 1353407778, 1353407778, 486, 20),
(0, 2, 4678, 3.23, 0, 1353407779, 1353407779, 487, 20),
(0, 1, -21882, 2.2, 0, 1353407810, 1353407810, 488, 20),
(0, 2, -9292, 2.3, 0, 1353407810, 1353407810, 489, 20),
(0, 1, 19711, 3.1, 0, 1353407810, 1353407810, 490, 20),
(0, 2, 22753, 3.23, 0, 1353407811, 1353407811, 491, 20),
(0, 1, -22974, 2.2, 0, 1353407842, 1353407842, 492, 20),
(0, 2, -2133, 2.3, 0, 1353407842, 1353407842, 493, 20),
(0, 1, 11822, 3.1, 0, 1353407842, 1353407842, 494, 20),
(0, 2, 17419, 3.23, 0, 1353407843, 1353407843, 495, 20),
(0, 1, -17066, 2.2, 0, 1353407874, 1353407874, 496, 20),
(0, 2, -3436, 2.3, 0, 1353407874, 1353407874, 497, 20),
(0, 1, 2129, 3.1, 0, 1353407874, 1353407874, 498, 20),
(0, 2, 31706, 3.23, 0, 1353407875, 1353407875, 499, 20),
(0, 1, -7184, 2.2, 0, 1353407906, 1353407906, 500, 20),
(0, 2, -4419, 2.3, 0, 1353407906, 1353407906, 501, 20),
(0, 1, 19236, 3.1, 0, 1353407906, 1353407906, 502, 20),
(0, 2, 24494, 3.23, 0, 1353407907, 1353407907, 503, 20),
(0, 1, -13263, 2.2, 0, 1353407938, 1353407938, 504, 20),
(0, 2, -31813, 2.3, 0, 1353407938, 1353407938, 505, 20),
(0, 1, 1034, 3.1, 0, 1353407938, 1353407938, 506, 20),
(0, 2, 16402, 3.23, 0, 1353407939, 1353407939, 507, 20),
(0, 1, -28186, 2.2, 0, 1353407970, 1353407970, 508, 20),
(0, 2, -7865, 2.3, 0, 1353407970, 1353407970, 509, 20),
(0, 1, 10054, 3.1, 0, 1353407970, 1353407970, 510, 20),
(0, 2, 13529, 3.23, 0, 1353407971, 1353407971, 511, 20),
(0, 1, -23456, 2.2, 0, 1353408002, 1353408002, 512, 20),
(0, 2, -1764, 2.3, 0, 1353408002, 1353408002, 513, 20),
(0, 1, 8722, 3.1, 0, 1353408002, 1353408002, 514, 20),
(0, 2, 12517, 3.23, 0, 1353408003, 1353408003, 515, 20),
(0, 1, -20424, 2.2, 0, 1353408034, 1353408034, 516, 20),
(0, 2, -7587, 2.3, 0, 1353408034, 1353408034, 517, 20),
(0, 1, 23484, 3.1, 0, 1353408034, 1353408034, 518, 20),
(0, 2, 29810, 3.23, 0, 1353408035, 1353408035, 519, 20),
(0, 1, -20622, 2.2, 0, 1353408066, 1353408066, 520, 20),
(0, 2, -28128, 2.3, 0, 1353408066, 1353408066, 521, 20),
(0, 1, 16561, 3.1, 0, 1353408066, 1353408066, 522, 20),
(0, 2, 1379, 3.23, 0, 1353408067, 1353408067, 523, 20),
(0, 1, -6558, 2.2, 0, 1353408098, 1353408098, 524, 20),
(0, 2, -29345, 2.3, 0, 1353408098, 1353408098, 525, 20),
(0, 1, 29240, 3.1, 0, 1353408098, 1353408098, 526, 20),
(0, 2, 13197, 3.23, 0, 1353408099, 1353408099, 527, 20),
(0, 1, -26031, 2.2, 0, 1353408130, 1353408130, 528, 20),
(0, 2, -20336, 2.3, 0, 1353408130, 1353408130, 529, 20),
(0, 1, 20745, 3.1, 0, 1353408130, 1353408130, 530, 20),
(0, 2, 4903, 3.23, 0, 1353408131, 1353408131, 531, 20),
(0, 1, -13059, 2.2, 0, 1353408162, 1353408162, 532, 20),
(0, 2, -5122, 2.3, 0, 1353408162, 1353408162, 533, 20),
(0, 1, 20356, 3.1, 0, 1353408162, 1353408162, 534, 20),
(0, 2, 28080, 3.23, 0, 1353408163, 1353408163, 535, 20),
(0, 1, -9475, 2.2, 0, 1353408194, 1353408194, 536, 20),
(0, 2, -14599, 2.3, 0, 1353408194, 1353408194, 537, 20),
(0, 1, 11052, 3.1, 0, 1353408194, 1353408194, 538, 20),
(0, 2, 17010, 3.23, 0, 1353408195, 1353408195, 539, 20),
(0, 1, -1688, 2.2, 0, 1353408226, 1353408226, 540, 20),
(0, 2, -15492, 2.3, 0, 1353408226, 1353408226, 541, 20),
(0, 1, 11759, 3.1, 0, 1353408226, 1353408226, 542, 20),
(0, 2, 16362, 3.23, 0, 1353408227, 1353408227, 543, 20),
(0, 1, -31870, 2.2, 0, 1353408258, 1353408258, 544, 20),
(0, 2, -21352, 2.3, 0, 1353408258, 1353408258, 545, 20),
(0, 1, 11304, 3.1, 0, 1353408258, 1353408258, 546, 20),
(0, 2, 27470, 3.23, 0, 1353408259, 1353408259, 547, 20),
(0, 1, -13620, 2.2, 0, 1353408290, 1353408290, 548, 20),
(0, 2, -23824, 2.3, 0, 1353408290, 1353408290, 549, 20),
(0, 1, 22473, 3.1, 0, 1353408290, 1353408290, 550, 20),
(0, 2, 1737, 3.23, 0, 1353408291, 1353408291, 551, 20),
(0, 1, -29839, 2.2, 0, 1353408322, 1353408322, 552, 20),
(0, 2, -14034, 2.3, 0, 1353408322, 1353408322, 553, 20),
(0, 1, 30178, 3.1, 0, 1353408322, 1353408322, 554, 20),
(0, 2, 30829, 3.23, 0, 1353408323, 1353408323, 555, 20),
(0, 1, -26665, 2.2, 0, 1353408354, 1353408354, 556, 20),
(0, 2, -28375, 2.3, 0, 1353408354, 1353408354, 557, 20),
(0, 1, 27050, 3.1, 0, 1353408354, 1353408354, 558, 20),
(0, 2, 21579, 3.23, 0, 1353408355, 1353408355, 559, 20),
(0, 1, -3863, 2.2, 0, 1353408386, 1353408386, 560, 20),
(0, 2, -21078, 2.3, 0, 1353408386, 1353408386, 561, 20),
(0, 1, 12734, 3.1, 0, 1353408386, 1353408386, 562, 20),
(0, 2, 24049, 3.23, 0, 1353408387, 1353408387, 563, 20),
(0, 1, -27426, 2.2, 0, 1353408418, 1353408418, 564, 20),
(0, 2, -1696, 2.3, 0, 1353408418, 1353408418, 565, 20),
(0, 1, 7432, 3.1, 0, 1353408418, 1353408418, 566, 20),
(0, 2, 7430, 3.23, 0, 1353408419, 1353408419, 567, 20),
(0, 1, -16657, 2.2, 0, 1353408450, 1353408450, 568, 20),
(0, 2, -13481, 2.3, 0, 1353408450, 1353408450, 569, 20),
(0, 1, 18739, 3.1, 0, 1353408450, 1353408450, 570, 20),
(0, 2, 26922, 3.23, 0, 1353408451, 1353408451, 571, 20),
(0, 1, -12237, 2.2, 0, 1353408482, 1353408482, 572, 20),
(0, 2, -20585, 2.3, 0, 1353408482, 1353408482, 573, 20),
(0, 1, 22954, 3.1, 0, 1353408482, 1353408482, 574, 20),
(0, 2, 25926, 3.23, 0, 1353408483, 1353408483, 575, 20),
(0, 1, -29668, 2.2, 0, 1353408514, 1353408514, 576, 20),
(0, 2, -16933, 2.3, 0, 1353408514, 1353408514, 577, 20),
(0, 1, 8206, 3.1, 0, 1353408514, 1353408514, 578, 20),
(0, 2, 23484, 3.23, 0, 1353408515, 1353408515, 579, 20),
(0, 1, -22521, 2.2, 0, 1353408546, 1353408546, 580, 20),
(0, 2, -4650, 2.3, 0, 1353408546, 1353408546, 581, 20),
(0, 1, 28162, 3.1, 0, 1353408546, 1353408546, 582, 20),
(0, 2, 23098, 3.23, 0, 1353408547, 1353408547, 583, 20),
(0, 1, -11056, 2.2, 0, 1353408578, 1353408578, 584, 20),
(0, 2, -17883, 2.3, 0, 1353408578, 1353408578, 585, 20),
(0, 1, 17371, 3.1, 0, 1353408578, 1353408578, 586, 20),
(0, 2, 25893, 3.23, 0, 1353408579, 1353408579, 587, 20),
(0, 1, -16729, 2.2, 0, 1353408610, 1353408610, 588, 20),
(0, 2, -21897, 2.3, 0, 1353408610, 1353408610, 589, 20),
(0, 1, 15935, 3.1, 0, 1353408610, 1353408610, 590, 20),
(0, 2, 8967, 3.23, 0, 1353408611, 1353408611, 591, 20),
(0, 1, -11175, 2.2, 0, 1353408642, 1353408642, 592, 20),
(0, 2, -4507, 2.3, 0, 1353408642, 1353408642, 593, 20),
(0, 1, 25822, 3.1, 0, 1353408642, 1353408642, 594, 20),
(0, 2, 6630, 3.23, 0, 1353408643, 1353408643, 595, 20);

-- --------------------------------------------------------

--
-- Table structure for table `gamesettings`
--

CREATE TABLE IF NOT EXISTS `gamesettings` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `gamesettings`
--

INSERT INTO `gamesettings` (`id`, `name`, `value`) VALUES
(1, 'default_country', '2'),
(2, 'default_usertype', '1'),
(3, 'default_employment_clauses', '4,2,3'),
(4, 'default_employment_payment', '4,amount=10000'),
(5, 'main_investor', '3'),
(6, 'default_employment_position', '1'),
(7, 'bot_bprice1', '3.9230'),
(8, 'bot_sprice1', '3.9280'),
(9, 'bot_bprice2', '4.1230'),
(10, 'bot_sprice2', '4.1280');

-- --------------------------------------------------------

--
-- Table structure for table `jobpositions`
--

CREATE TABLE IF NOT EXISTS `jobpositions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `tag` varchar(45) NOT NULL,
  `slots` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `jobpositions`
--

INSERT INTO `jobpositions` (`id`, `name`, `tag`, `slots`) VALUES
(1, 'Junior Trader', 'junior trader', 2),
(2, 'Chief Executive Officer', 'CEO', 1),
(3, 'Trader', 'trader', 2),
(4, 'Senior Trader', 'senior trader', 2),
(5, 'Treasurer', 'treasurer', 1);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `banks_id` bigint(20) NOT NULL,
  `contracts_id` bigint(20) NOT NULL,
  `jobpositions_id` bigint(20) NOT NULL,
  `available` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_jobs_banks1` (`banks_id`),
  KEY `fk_jobs_contracts1` (`contracts_id`),
  KEY `fk_jobs_jobpositions1` (`jobpositions_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `banks_id`, `contracts_id`, `jobpositions_id`, `available`) VALUES
(24, 26, 34, 2, 0),
(25, 26, 35, 1, 0),
(26, 26, 36, 1, 0),
(27, 27, 37, 2, 0),
(28, 27, 38, 1, 0),
(29, 27, 39, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `mm_deals`
--

CREATE TABLE IF NOT EXISTS `mm_deals` (
  `period` bigint(20) NOT NULL,
  `ccy_pair` bigint(20) NOT NULL,
  `amount_base_ccy` bigint(20) NOT NULL,
  `price` float NOT NULL,
  `counter_party` varchar(7) NOT NULL,
  `value_date` bigint(20) NOT NULL,
  `trade_date` bigint(20) NOT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(7) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`) VALUES
(1, 'trading'),
(2, 'news'),
(3, 'econ'),
(4, 'dashboard'),
(5, 'clients');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` bigint(20) NOT NULL,
  `headline` text NOT NULL,
  `body` text NOT NULL,
  `countries_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_news_countries1` (`countries_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `periods`
--

CREATE TABLE IF NOT EXISTS `periods` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(7) NOT NULL,
  `time` bigint(20) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `periods`
--

INSERT INTO `periods` (`id`, `name`, `time`) VALUES
(1, 'SPOT', 0);

-- --------------------------------------------------------

--
-- Table structure for table `retail_limits`
--

CREATE TABLE IF NOT EXISTS `retail_limits` (
  `pips_in` int(11) NOT NULL,
  `pips_out` int(11) NOT NULL,
  `max_amount` int(11) NOT NULL,
  `secs` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `retail_limits`
--

INSERT INTO `retail_limits` (`pips_in`, `pips_out`, `max_amount`, `secs`) VALUES
(0, 25, 32000, 30),
(25, 50, 31000, 45),
(50, 75, 30000, 60),
(75, 100, 29000, 75),
(100, 125, 28000, 90),
(125, 150, 27000, 105),
(150, 175, 26000, 120),
(175, 200, 25000, 135),
(200, 225, 24000, 150),
(225, 800, 4000, 465);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE IF NOT EXISTS `rooms` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `min_level` int(11) NOT NULL,
  `max_level` int(11) NOT NULL,
  `min_ticket` int(11) NOT NULL,
  `max_ticket` int(11) NOT NULL,
  `min_players` int(11) NOT NULL,
  `max_players` int(11) NOT NULL,
  `entry_tax` int(11) NOT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `users_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_rooms_users1` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `roomsubscribtions`
--

CREATE TABLE IF NOT EXISTS `roomsubscribtions` (
  `banks_id` bigint(20) NOT NULL,
  `rooms_id` bigint(20) NOT NULL,
  PRIMARY KEY (`banks_id`,`rooms_id`),
  KEY `fk_banks_has_rooms_rooms1` (`rooms_id`),
  KEY `fk_banks_has_rooms_banks1` (`banks_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `shares`
--

CREATE TABLE IF NOT EXISTS `shares` (
  `users_id` bigint(20) NOT NULL,
  `banks_id` bigint(20) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `contracts_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`users_id`,`banks_id`),
  KEY `fk_banks_has_users_users1` (`users_id`),
  KEY `fk_banks_has_users_banks1` (`banks_id`),
  KEY `fk_shares_contracts1` (`contracts_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shares`
--

INSERT INTO `shares` (`users_id`, `banks_id`, `amount`, `contracts_id`) VALUES
(3, 26, 10000, NULL),
(3, 27, 10000, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subsidiaries`
--

CREATE TABLE IF NOT EXISTS `subsidiaries` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `number` int(11) NOT NULL,
  `countries_id` bigint(20) NOT NULL,
  `banks_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_subsidiaries_countries1` (`countries_id`),
  KEY `fk_subsidiaries_banks1` (`banks_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(75) NOT NULL,
  `password` varchar(45) NOT NULL,
  `username` varchar(45) NOT NULL,
  `usertypes_id` bigint(20) NOT NULL,
  `countries_id` bigint(20) NOT NULL,
  `jobs_id` bigint(20) DEFAULT NULL,
  `last_trading` bigint(21) NOT NULL DEFAULT '0',
  `confirm_email` varchar(50) NOT NULL,
  `ukey` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_users_types1` (`usertypes_id`),
  KEY `fk_users_countries1` (`countries_id`),
  KEY `fk_users_jobs1` (`jobs_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `username`, `usertypes_id`, `countries_id`, `jobs_id`, `last_trading`, `confirm_email`, `ukey`) VALUES
(3, 'investor@traderion.com', '07236293ae49ee31f3d2c75b6d1101c9', 'Rikland Governmental Fund', 5, 2, NULL, 0, '54af9bfd318f8ed62ea5db57c03c90be', '2524e8dfc260f29a75697dd62b3b44de'),
(15, 'dinnot21@gmail.com', '1582922021acf878d273fc57c32c5853', 'dinnot', 1, 2, 25, 0, '7f2331a954984ca568f6d86a0aa3b6e2', 'c297b6773edb3ae4791146ac43245712'),
(16, 'I@bots.traderion.com', 'imposible', 'Itrader', 6, 2, 24, 0, '', ''),
(17, 'tester1@traderion.com', 'efd90bdaa88ef02c82d62b3be23192f0', 'tester1', 1, 2, 26, 0, 'd8768311aa91c7bef7945749a7cd23df', '450480ee29285d03e0dc55a2dc740036'),
(18, 'tester2@traderion.com', 'ca6728dc78237201996a57e29f264297', 'tester2', 1, 2, 28, 0, 'b9a2d90a4fdb1756ccc57ecb5731f006', 'e2096650a7255f69afe3f3e25bb71803'),
(19, 'II@bots.traderion.com', 'imposible', 'IItrader', 6, 2, 27, 0, '', ''),
(20, 'alexandru.cazacu92@gmail.com', '6767535d351b5d8102b402cdb5e40d3e', 'alexcazacu', 1, 2, 29, 0, '02fbd1d15f853d63be5365277795268c', 'e8f04e2562f1ed9a52a4d231ef46fb76');

-- --------------------------------------------------------

--
-- Table structure for table `usersettings`
--

CREATE TABLE IF NOT EXISTS `usersettings` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `default` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `usersettings`
--

INSERT INTO `usersettings` (`id`, `name`, `default`) VALUES
(1, 'first_name', '-'),
(2, 'last_name', '-'),
(3, 'dob', '-'),
(4, 'location', '-'),
(5, 'gender', '-');

-- --------------------------------------------------------

--
-- Table structure for table `users_balances`
--

CREATE TABLE IF NOT EXISTS `users_balances` (
  `users_id` bigint(20) NOT NULL,
  `currencies_id` bigint(20) NOT NULL,
  `amount` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`users_id`,`currencies_id`),
  KEY `fk_users_has_currencies_currencies1` (`currencies_id`),
  KEY `fk_users_has_currencies_users1` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_balances`
--

INSERT INTO `users_balances` (`users_id`, `currencies_id`, `amount`) VALUES
(15, 1, 0),
(15, 2, 0),
(15, 3, 0),
(17, 1, 0),
(17, 2, 0),
(17, 3, 0),
(18, 1, 0),
(18, 2, 0),
(18, 3, 0),
(20, 1, 0),
(20, 2, 0),
(20, 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users_fx_positions`
--

CREATE TABLE IF NOT EXISTS `users_fx_positions` (
  `users_id` bigint(20) NOT NULL,
  `currencies_id` bigint(20) NOT NULL,
  `amount` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`users_id`,`currencies_id`),
  KEY `fk_users_has_currencies_currencies1` (`currencies_id`),
  KEY `fk_users_has_currencies_users1` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_fx_positions`
--

INSERT INTO `users_fx_positions` (`users_id`, `currencies_id`, `amount`) VALUES
(15, 1, 0),
(15, 2, 0),
(15, 3, 0),
(17, 1, 0),
(17, 2, 0),
(17, 3, 0),
(18, 1, 0),
(18, 2, 0),
(18, 3, 0),
(20, 1, 1055563),
(20, 2, 1108583),
(20, 3, 44);

-- --------------------------------------------------------

--
-- Table structure for table `users_has_corporate_offers`
--

CREATE TABLE IF NOT EXISTS `users_has_corporate_offers` (
  `user_id` bigint(20) NOT NULL,
  `offer_id` bigint(20) NOT NULL,
  `quote` double NOT NULL,
  `status` int(11) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `offer_id` (`offer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_has_corporate_offers`
--

INSERT INTO `users_has_corporate_offers` (`user_id`, `offer_id`, `quote`, `status`) VALUES
(16, 1, 0, 3),
(20, 1, 1.23, 2),
(20, 2, 2.23, 2),
(15, 2, 0, 3),
(20, 3, 1.23, 2),
(18, 3, 0, 3),
(19, 3, 0, 3),
(20, 4, 23, 2),
(19, 4, 0, 3),
(16, 4, 0, 3),
(18, 4, 0, 3),
(16, 5, 0, 3),
(20, 5, 1.23, 2),
(15, 6, 0, 0),
(3, 6, 0, 0),
(17, 7, 0, 3),
(15, 7, 0, 3),
(20, 7, 43.12, 2),
(19, 7, 0, 3),
(19, 8, 0, 0),
(3, 8, 0, 0),
(16, 9, 0, 0),
(17, 9, 0, 0),
(17, 10, 0, 0),
(15, 10, 0, 0),
(19, 10, 0, 0),
(3, 10, 0, 0),
(18, 11, 0, 0),
(3, 11, 0, 0),
(17, 11, 0, 0),
(16, 12, 0, 0),
(15, 12, 0, 0),
(17, 12, 0, 0),
(20, 13, 0, 3),
(3, 13, 0, 3),
(18, 13, 0, 3),
(17, 13, 0, 3),
(20, 14, 0, 3),
(19, 14, 0, 3),
(3, 14, 0, 3),
(20, 15, 0, 3),
(16, 15, 0, 3),
(18, 15, 0, 3),
(17, 15, 0, 3),
(17, 16, 0, 3),
(19, 16, 0, 3),
(20, 16, 12.23, 2),
(15, 17, 0, 0),
(17, 17, 0, 0),
(15, 18, 0, 0),
(18, 18, 0, 0),
(16, 19, 0, 3),
(18, 19, 0, 3),
(20, 19, 0, 3),
(15, 19, 0, 3),
(3, 20, 0, 0),
(15, 20, 0, 0),
(15, 21, 0, 0),
(17, 21, 0, 0),
(16, 21, 0, 0),
(3, 22, 0, 3),
(15, 22, 0, 3),
(17, 22, 0, 3),
(20, 22, 0, 3),
(16, 23, 0, 0),
(17, 23, 0, 0),
(15, 23, 0, 0),
(15, 24, 0, 3),
(17, 24, 0, 3),
(16, 24, 0, 3),
(20, 24, 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users_has_retail_offers`
--

CREATE TABLE IF NOT EXISTS `users_has_retail_offers` (
  `user_id` bigint(20) NOT NULL,
  `pair_id` int(11) NOT NULL,
  `deal` int(11) NOT NULL,
  `date` bigint(20) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_has_retail_offers`
--

INSERT INTO `users_has_retail_offers` (`user_id`, `pair_id`, `deal`, `date`, `amount`) VALUES
(20, 1, 1, 1353408674, 8580),
(20, 2, 1, 1353408674, 29163),
(20, 1, 2, 1353408674, 13486),
(20, 2, 2, 1353408675, 2393);

-- --------------------------------------------------------

--
-- Table structure for table `users_has_usersettings`
--

CREATE TABLE IF NOT EXISTS `users_has_usersettings` (
  `users_id` bigint(20) NOT NULL,
  `usersettings_id` bigint(20) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`users_id`,`usersettings_id`),
  KEY `fk_users_has_usersettings_usersettings1` (`usersettings_id`),
  KEY `fk_users_has_usersettings_users1` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_has_usersettings`
--

INSERT INTO `users_has_usersettings` (`users_id`, `usersettings_id`, `value`) VALUES
(15, 1, '-'),
(15, 2, '-'),
(15, 3, '-'),
(15, 4, '-'),
(15, 5, '-'),
(17, 1, '-'),
(17, 2, '-'),
(17, 3, '-'),
(17, 4, '-'),
(17, 5, '-'),
(18, 1, '-'),
(18, 2, '-'),
(18, 3, '-'),
(18, 4, '-'),
(18, 5, '-'),
(20, 1, '-'),
(20, 2, '-'),
(20, 3, '-'),
(20, 4, '-'),
(20, 5, '-');

-- --------------------------------------------------------

--
-- Table structure for table `users_mm_positions`
--

CREATE TABLE IF NOT EXISTS `users_mm_positions` (
  `users_id` bigint(20) NOT NULL,
  `currencies_id` bigint(20) NOT NULL,
  `amount` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`users_id`,`currencies_id`),
  KEY `fk_users_has_currencies_currencies1` (`currencies_id`),
  KEY `fk_users_has_currencies_users1` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users_retail_amount`
--

CREATE TABLE IF NOT EXISTS `users_retail_amount` (
  `user_id` int(11) NOT NULL,
  `pair_id` int(11) NOT NULL,
  `sell` int(11) DEFAULT NULL,
  `buy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_retail_amount`
--

INSERT INTO `users_retail_amount` (`user_id`, `pair_id`, `sell`, `buy`) VALUES
(20, 1, 1842408, 1851643),
(20, 2, 1684616, 1820135);

-- --------------------------------------------------------

--
-- Table structure for table `users_retail_rate`
--

CREATE TABLE IF NOT EXISTS `users_retail_rate` (
  `user_id` bigint(20) NOT NULL,
  `pair_id` int(11) NOT NULL,
  `sell` double NOT NULL,
  `buy` double NOT NULL,
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_retail_rate`
--

INSERT INTO `users_retail_rate` (`user_id`, `pair_id`, `sell`, `buy`) VALUES
(20, 1, 2.2, 3.1),
(20, 2, 2.3, 3.23);

-- --------------------------------------------------------

--
-- Table structure for table `usertypes`
--

CREATE TABLE IF NOT EXISTS `usertypes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `usertypes`
--

INSERT INTO `usertypes` (`id`, `name`, `is_required`) VALUES
(1, 'player', 1),
(2, 'admin', 0),
(3, 'developer', 0),
(4, 'central_bank', 0),
(5, 'investor', 1),
(6, 'ceo_bot', 1);

-- --------------------------------------------------------

--
-- Table structure for table `usertypes_has_modules`
--

CREATE TABLE IF NOT EXISTS `usertypes_has_modules` (
  `usertypes_id` bigint(20) NOT NULL,
  `modules_id` bigint(20) NOT NULL,
  PRIMARY KEY (`usertypes_id`,`modules_id`),
  KEY `fk_users_types_has_modules_modules1` (`modules_id`),
  KEY `fk_users_types_has_modules_users_types1` (`usertypes_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usertypes_has_modules`
--

INSERT INTO `usertypes_has_modules` (`usertypes_id`, `modules_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applicants`
--
ALTER TABLE `applicants`
  ADD CONSTRAINT `fk_users_has_jobs_jobs1` FOREIGN KEY (`jobs_id`) REFERENCES `jobs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_has_jobs_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `banks_balances`
--
ALTER TABLE `banks_balances`
  ADD CONSTRAINT `fk_banks_has_currencies_banks1` FOREIGN KEY (`banks_id`) REFERENCES `banks` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_banks_has_currencies_currencies1` FOREIGN KEY (`currencies_id`) REFERENCES `currencies` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `fk_cities_countries1` FOREIGN KEY (`countries_id`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cities_has_citysettings`
--
ALTER TABLE `cities_has_citysettings`
  ADD CONSTRAINT `fk_cities_has_citysettings_cities1` FOREIGN KEY (`cities_id`) REFERENCES `cities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cities_has_citysettings_citysettings1` FOREIGN KEY (`citysettings_id`) REFERENCES `citysettings` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `clausevalues`
--
ALTER TABLE `clausevalues`
  ADD CONSTRAINT `fk_clausevalues_contracts_has_clauses1` FOREIGN KEY (`contracts_id`, `clauses_id`) REFERENCES `contracts_has_clauses` (`contracts_id`, `clauses_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `fk_contracts_contracttypes1` FOREIGN KEY (`contracttypes_id`) REFERENCES `contracttypes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `contracts_has_clauses`
--
ALTER TABLE `contracts_has_clauses`
  ADD CONSTRAINT `fk_contracts_has_clauses_clauses1` FOREIGN KEY (`clauses_id`) REFERENCES `clauses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_contracts_has_clauses_contracts1` FOREIGN KEY (`contracts_id`) REFERENCES `contracts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `countries`
--
ALTER TABLE `countries`
  ADD CONSTRAINT `fk_countries_currencies1` FOREIGN KEY (`currencies_id`) REFERENCES `currencies` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `countries_has_countrysettings`
--
ALTER TABLE `countries_has_countrysettings`
  ADD CONSTRAINT `fk_countries_has_countrysettings_countries1` FOREIGN KEY (`countries_id`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_countries_has_countrysettings_countrysettings1` FOREIGN KEY (`countrysettings_id`) REFERENCES `countrysettings` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `countries_has_econindicators`
--
ALTER TABLE `countries_has_econindicators`
  ADD CONSTRAINT `fk_countries_has_econindicators_countries1` FOREIGN KEY (`countries_id`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_countries_has_econindicators_econindicators1` FOREIGN KEY (`econindicators_id`) REFERENCES `econindicators` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `domains`
--
ALTER TABLE `domains`
  ADD CONSTRAINT `fk_domains_modules` FOREIGN KEY (`modules_id`) REFERENCES `modules` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `econcategories`
--
ALTER TABLE `econcategories`
  ADD CONSTRAINT `fk_econcategories_econcategories1` FOREIGN KEY (`parent_id`) REFERENCES `econcategories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `econforcasts`
--
ALTER TABLE `econforcasts`
  ADD CONSTRAINT `fk_econforcasts_countries_has_econindicators1` FOREIGN KEY (`countries_id`, `econindicators_id`) REFERENCES `countries_has_econindicators` (`countries_id`, `econindicators_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `econindicators`
--
ALTER TABLE `econindicators`
  ADD CONSTRAINT `fk_econindicators_econlevels1` FOREIGN KEY (`econlevels_id`) REFERENCES `econlevels` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `fk_jobs_banks1` FOREIGN KEY (`banks_id`) REFERENCES `banks` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_jobs_contracts1` FOREIGN KEY (`contracts_id`) REFERENCES `contracts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_jobs_jobpositions1` FOREIGN KEY (`jobpositions_id`) REFERENCES `jobpositions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_news_countries1` FOREIGN KEY (`countries_id`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `fk_rooms_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `roomsubscribtions`
--
ALTER TABLE `roomsubscribtions`
  ADD CONSTRAINT `fk_banks_has_rooms_banks1` FOREIGN KEY (`banks_id`) REFERENCES `banks` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_banks_has_rooms_rooms1` FOREIGN KEY (`rooms_id`) REFERENCES `rooms` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `shares`
--
ALTER TABLE `shares`
  ADD CONSTRAINT `fk_banks_has_users_banks1` FOREIGN KEY (`banks_id`) REFERENCES `banks` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_banks_has_users_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_shares_contracts1` FOREIGN KEY (`contracts_id`) REFERENCES `contracts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `subsidiaries`
--
ALTER TABLE `subsidiaries`
  ADD CONSTRAINT `fk_subsidiaries_banks1` FOREIGN KEY (`banks_id`) REFERENCES `banks` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_subsidiaries_countries1` FOREIGN KEY (`countries_id`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_countries1` FOREIGN KEY (`countries_id`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_jobs1` FOREIGN KEY (`jobs_id`) REFERENCES `jobs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_users_types1` FOREIGN KEY (`usertypes_id`) REFERENCES `usertypes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `users_balances`
--
ALTER TABLE `users_balances`
  ADD CONSTRAINT `fk_users_has_currencies_currencies1` FOREIGN KEY (`currencies_id`) REFERENCES `currencies` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_has_currencies_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `users_has_usersettings`
--
ALTER TABLE `users_has_usersettings`
  ADD CONSTRAINT `fk_users_has_usersettings_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_has_usersettings_usersettings1` FOREIGN KEY (`usersettings_id`) REFERENCES `usersettings` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `usertypes_has_modules`
--
ALTER TABLE `usertypes_has_modules`
  ADD CONSTRAINT `fk_users_types_has_modules_modules1` FOREIGN KEY (`modules_id`) REFERENCES `modules` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_types_has_modules_users_types1` FOREIGN KEY (`usertypes_id`) REFERENCES `usertypes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
