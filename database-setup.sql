

--
-- Database: `musikteam`
--

-- --------------------------------------------------------

--
-- Table structure for table `Bruger`
--

CREATE TABLE IF NOT EXISTS `Bruger` (
  `BrugerId` int(11) NOT NULL AUTO_INCREMENT,
  `Brugernavn` varchar(50) DEFAULT NULL,
  `Kode` varchar(50) DEFAULT NULL,
  `Admin` tinyint(1) DEFAULT '0',
  `PersonId` int(11) DEFAULT '0',
  `Email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`BrugerId`),
  KEY `BrugerId` (`BrugerId`),
  KEY `PersonId` (`PersonId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `Bruger`
--

INSERT INTO `Bruger` (`BrugerId`, `Brugernavn`, `Kode`, `Admin`, `PersonId`, `Email`) VALUES
(1, 'admin', 'fcfe90b58d59aabc248600baff58a7e2', 1, -1, 'admin@musikteam.com');

-- --------------------------------------------------------

--
-- Table structure for table `Historik`
--

CREATE TABLE IF NOT EXISTS `Historik` (
  `SangId` int(11) DEFAULT NULL,
  `BrugerId` int(11) DEFAULT NULL,
  `Dato` datetime DEFAULT NULL,
  `Change` varchar(255) DEFAULT NULL,
  KEY `BrugerId` (`BrugerId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `Person`
--

CREATE TABLE IF NOT EXISTS `Person` (
  `PersonID` int(11) NOT NULL AUTO_INCREMENT,
  `Fornavn` varchar(50) DEFAULT NULL,
  `Efternavn` varchar(50) DEFAULT NULL,
  `Adresse1` varchar(50) DEFAULT NULL,
  `Adresse2` varchar(50) DEFAULT NULL,
  `Mail` varchar(50) DEFAULT NULL,
  `Telefon` varchar(50) DEFAULT NULL,
  `Mobil` varchar(50) DEFAULT NULL,
  `Leder` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`PersonID`),
  KEY `PersonID` (`PersonID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `PersonRolle`
--

CREATE TABLE IF NOT EXISTS `PersonRolle` (
  `PersonID` int(11) NOT NULL DEFAULT '0',
  `RolleID` int(11) NOT NULL DEFAULT '0',
  `Primaer` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`PersonID`,`RolleID`),
  KEY `PersonID` (`PersonID`),
  KEY `RolleID` (`RolleID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `Program`
--

CREATE TABLE IF NOT EXISTS `Program` (
  `ProgramID` int(11) NOT NULL AUTO_INCREMENT,
  `Arrangement` varchar(50) DEFAULT NULL,
  `Dato` datetime DEFAULT NULL,
  `LederID` int(11) DEFAULT '0',
  `Kommentarer` longtext,
  `TeamID` int(11) DEFAULT '0',
  PRIMARY KEY (`ProgramID`),
  KEY `LederID` (`LederID`),
  KEY `ProgramID` (`ProgramID`),
  KEY `TeamID` (`TeamID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `ProgramPerson`
--

CREATE TABLE IF NOT EXISTS `ProgramPerson` (
  `ProgramID` int(11) NOT NULL DEFAULT '0',
  `PersonID` int(11) NOT NULL DEFAULT '0',
  `RolleID` int(11) DEFAULT '0',
  `Kommentar` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ProgramID`,`PersonID`),
  KEY `PersonID` (`PersonID`),
  KEY `RolleID` (`RolleID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `ProgramPunkt`
--

CREATE TABLE IF NOT EXISTS `ProgramPunkt` (
  `ProgramID` int(11) DEFAULT '0',
  `Type` int(11) DEFAULT '0',
  `SangID` int(11) DEFAULT '0',
  `Overskrift` varchar(50) DEFAULT NULL,
  `Raekkefoelge` int(11) DEFAULT '0',
  KEY `ProgramID` (`ProgramID`),
  KEY `SangID` (`SangID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `Rolle`
--

CREATE TABLE IF NOT EXISTS `Rolle` (
  `RolleID` int(11) NOT NULL AUTO_INCREMENT,
  `Navn` varchar(50) DEFAULT NULL,
  `Raekkefoelge` int(11) DEFAULT '0',
  PRIMARY KEY (`RolleID`),
  KEY `RolleID` (`RolleID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `Sang`
--

CREATE TABLE IF NOT EXISTS `Sang` (
  `SangId` int(11) NOT NULL AUTO_INCREMENT,
  `Titel` varchar(100) NOT NULL DEFAULT '',
  `Kilde` varchar(100) NOT NULL DEFAULT '',
  `Slides` varchar(50) NOT NULL DEFAULT '',
  `IkkePrint` tinyint(1) NOT NULL DEFAULT '0',
  `Slides2` varchar(50) NOT NULL DEFAULT '',
  `Identifikation` varchar(100) NOT NULL DEFAULT '',
  `Konverteret` tinyint(1) DEFAULT '0',
  `ProTekst` longtext,
  `CommentsOver` longtext,
  `CommentsUnder` longtext,
  `Udgave` varchar(100) DEFAULT NULL,
  `Lydfil` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`SangId`),
  KEY `Identifikation` (`Identifikation`),
  KEY `SangId` (`SangId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `SangBruger`
--

CREATE TABLE IF NOT EXISTS `SangBruger` (
  `BrugerId` int(11) NOT NULL DEFAULT '0',
  `SangId` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`BrugerId`,`SangId`),
  KEY `BrugerId` (`BrugerId`),
  KEY `SangId` (`SangId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `Slide`
--

CREATE TABLE IF NOT EXISTS `Slide` (
  `SangID` int(11) NOT NULL DEFAULT '0',
  `SlideID` varchar(1) NOT NULL DEFAULT '',
  `Slidetekst` longtext NOT NULL,
  PRIMARY KEY (`SangID`,`SlideID`),
  KEY `SangID` (`SangID`),
  KEY `SlideID` (`SlideID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `Slide2`
--

CREATE TABLE IF NOT EXISTS `Slide2` (
  `SangID` int(11) NOT NULL DEFAULT '0',
  `SlideID` varchar(1) NOT NULL DEFAULT '',
  `Slidetekst` longtext NOT NULL,
  PRIMARY KEY (`SangID`,`SlideID`),
  KEY `SangID` (`SangID`),
  KEY `SlideID` (`SlideID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `Speciel`
--

CREATE TABLE IF NOT EXISTS `Speciel` (
  `SpecielID` int(11) NOT NULL DEFAULT '0',
  `Beskrivelse` varchar(50) NOT NULL,
  `Filnavn` varchar(50) NOT NULL,
  `FraSlide` int(11) NOT NULL DEFAULT '0',
  `TilSlide` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`SpecielID`),
  KEY `SpecielID` (`SpecielID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `Team`
--

CREATE TABLE IF NOT EXISTS `Team` (
  `TeamID` int(11) NOT NULL AUTO_INCREMENT,
  `Navn` varchar(50) DEFAULT NULL,
  `Beskrivelse` longtext,
  `Raekkefoelge` int(11) DEFAULT '0',
  PRIMARY KEY (`TeamID`),
  KEY `TeamID` (`TeamID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


--
-- Table structure for table `TeamPerson`
--

CREATE TABLE IF NOT EXISTS `TeamPerson` (
  `TeamID` int(11) NOT NULL DEFAULT '0',
  `PersonID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`TeamID`,`PersonID`),
  KEY `PersonID` (`PersonID`),
  KEY `TeamID` (`TeamID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

