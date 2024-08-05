
CREATE TABLE `categories` (
  `CATID` bigint(20) NOT NULL AUTO_INCREMENT,
  `CATEGORY` varchar(175) NOT NULL,
  PRIMARY KEY (`CATID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=75 ;

INSERT INTO `categories` (`CATID`, `CATEGORY`) VALUES
(1, 'Accounting           '),
(2, 'Administrative, Office Support'),
(3, 'Advertising, Marketing, Public Relations'),
(4, 'Agriculture, Forestry, Fishing'),
(5, 'Architectural Service'),
(6, 'Arts, Entertainment, Media    '),
(7, 'Automotive           '),
(8, 'Banking, Finance'),
(9, 'Biotechnology, Pharmaceutical '),
(10, 'Building, Grounds Maintenance '),
(11, 'Care Giving          '),
(12, 'Childcare Services   '),
(13, 'Construction         '),
(14, 'Custodial, Janitorial Services'),
(15, 'Customer Service     '),
(16, 'Dental               '),
(17, 'Drivers              '),
(18, 'Education            '),
(19, 'Engineering          '),
(20, 'Environmental        '),
(21, 'Executive, Management'),
(22, 'Fitness, Recreation, Sports   '),
(23, 'Food Industries, Restaurant   '),
(24, 'Government           '),
(25, 'Healthcare - Administrative   '),
(26, 'Healthcare - Aide, Assistant  '),
(27, 'Healthcare - General '),
(28, 'Healthcare - Home Healthcare  '),
(29, 'Healthcare - Laboratory, Radiology      '),
(30, 'Healthcare - Nursing '),
(31, 'Healthcare - Pharmacy'),
(32, 'Healthcare - Practitioner     '),
(33, 'Healthcare - Social Services, Mental Health'),
(34, 'Healthcare - Technician       '),
(35, 'Healthcare - Therapy, Rehabilitation    '),
(36, 'Hospitality - Hotel  '),
(37, 'Human Resources      '),
(38, 'Insurance            '),
(39, 'Internet, E-Commerce '),
(40, 'IT, Computers        '),
(41, 'Journalism, News, Publishing, Editing   '),
(42, 'Landscaping Services '),
(43, 'Law Enforcement, Security     '),
(44, 'Legal                '),
(45, 'Manufacturing        '),
(46, 'Marine Industries    '),
(47, 'Municipal            '),
(48, 'Nonprofit            '),
(49, 'Other                '),
(50, 'Professional         '),
(51, 'Real Estate          '),
(52, 'Research, Development'),
(53, 'Retail               '),
(54, 'Sales                '),
(55, 'Salons, Personal Care'),
(56, 'Science              '),
(57, 'Social Services      '),
(58, 'Supply Chain         '),
(59, 'Technical            '),
(60, 'Telecommunications   '),
(61, 'Telemarketing        '),
(62, 'Textile Industries   '),
(63, 'Tourism              '),
(64, 'Trades               '),
(65, 'Training             '),
(66, 'Transportation, Logistics     '),
(67, 'Veterinary Services  '),
(68, 'Warehouse            '),
(69, 'Wholesale            ');

CREATE TABLE `companies` (
  `CID` bigint(20) NOT NULL AUTO_INCREMENT,
  `COMPANYNAME` varchar(200) NOT NULL DEFAULT 'No Name',
  `MAILADDRESS1` varchar(175) NOT NULL,
  `MAILADDRESS2` varchar(175) NOT NULL,
  `CITY` varchar(75) NOT NULL,
  `STATE` varchar(50) NOT NULL,
  `ZIP` varchar(10) NOT NULL,
  `PHONE` varchar(18) NOT NULL,
  `FAX` varchar(18) NOT NULL,
  `CONTACT` varchar(85) NOT NULL,
  `CONTACTEMAIL` varchar(75) NOT NULL,
  `CONTACTTITLE` varchar(150) NOT NULL,
  `WEBSITE` varchar(245) NOT NULL,
  `ABOUT` text NOT NULL,
  `USERNAME` varchar(255) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `APPROVED` varchar(255) NOT NULL DEFAULT '0',
  `TEMP` varchar(255) NOT NULL,
  PRIMARY KEY (`CID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `covers` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `UID` bigint(20) NOT NULL,
  `COVERTITLE` varchar(150) NOT NULL,
  `COVERBODY` text NOT NULL,
  `SEARCHABLE` varchar(7) NOT NULL,
  `DATEPOSTED` date NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `jobs` (
  `JID` bigint(20) NOT NULL AUTO_INCREMENT,
  `CID` bigint(20) NOT NULL,
  `CATID` varchar(65) NOT NULL,
  `JOBTITLE` varchar(255) NOT NULL,
  `JOBLOCATION` varchar(175) NOT NULL,
  `JOBDESCRIPTION` text NOT NULL,
  `JOBREQUIREMENTS` text NOT NULL,
  `EDREQUIREMENTS` text NOT NULL,
  `BENEFITS` text NOT NULL,
  `DATEPOSTED` date NOT NULL,
  `HOWTOAPPLY` varchar(175) NOT NULL,
  `JOBDURATION` varchar(30) NOT NULL,
  `JOBTYPE` varchar(20) NOT NULL,
  `WAGETYPE` varchar(25) NOT NULL,
  `NUMDAYS` int(11) NOT NULL DEFAULT '7',
  `ISENABLED` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`JID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `locations` (
  `LID` int(11) NOT NULL,
  `LOC` varchar(13) NOT NULL,
  `ABBREV` varchar(2) NOT NULL,
  PRIMARY KEY (`LID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `locations` (`LID`, `LOC`, `ABBREV`) VALUES
(1, 'Alabama', 'AL'),
(2, 'Alaska', 'AK'),
(3, 'Arizona', 'AZ'),
(4, 'Arkansas', 'AR'),
(5, 'California', 'CA'),
(6, 'Colorado', 'CO'),
(7, 'Connecticut', 'CT'),
(8, 'Delaware', 'DE'),
(9, 'Florida', 'FL'),
(10, 'Georgia', 'GA'),
(11, 'Hawaii', 'HI'),
(12, 'Idaho', 'ID'),
(13, 'Illinois', 'IL'),
(14, 'Indiana', 'IN'),
(15, 'Iowa', 'IA'),
(16, 'Kansas', 'KS'),
(17, 'Kentucky', 'KY'),
(18, 'Louisiana', 'LA'),
(19, 'Maine', 'ME'),
(20, 'Maryland', 'MD'),
(21, 'Massachusetts', 'MA'),
(22, 'Michigan', 'MI'),
(23, 'Minnesota', 'MN'),
(24, 'Mississippi', 'MS'),
(25, 'Missouri', 'MO'),
(26, 'Montana', 'MT'),
(27, 'Nebraska', 'NE'),
(28, 'Nevada', 'NV'),
(29, 'New Hampshire', 'NH'),
(30, 'New Jersey', 'NJ'),
(31, 'New Mexico', 'NM'),
(32, 'New York', 'NY'),
(33, 'North Carolin', 'NC'),
(34, 'North Dakota', 'ND'),
(35, 'Ohio', 'OH'),
(36, 'Oklahoma', 'OK'),
(37, 'Oregon', 'OR'),
(38, 'Pennsylvania', 'PA'),
(39, 'Rhode Island', 'RI'),
(40, 'South Carolin', 'SC'),
(41, 'South Dakota', 'SD'),
(42, 'Tennessee', 'TN'),
(43, 'Texas', 'TX'),
(44, 'Utah', 'UT'),
(45, 'Vermont', 'VT'),
(46, 'Virginia', 'VA'),
(47, 'Washington', 'WA'),
(48, 'West Virginia', 'WV'),
(49, 'Wisconsin', 'WI'),
(50, 'Wyoming', 'WY');

CREATE TABLE `mail` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `TCID` int(20) NOT NULL,
  `TUID` int(20) NOT NULL,
  `FCID` int(20) NOT NULL,
  `FUID` int(20) NOT NULL,
  `UID` int(20) NOT NULL,
  `CID` int(20) NOT NULL,
  `SUBJECT` varchar(150) NOT NULL,
  `MESSAGE` text NOT NULL,
  `COVER` bigint(20) NOT NULL DEFAULT '-1',
  `RESUME` bigint(20) NOT NULL DEFAULT '-1',
  `SENTDATE` datetime NOT NULL,
  `READDATE` datetime NOT NULL,
  `REPLIED` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `resumes` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `UID` bigint(20) NOT NULL,
  `RESUMETITLE` varchar(150) NOT NULL,
  `RESUMEBODY` text NOT NULL,
  `SEARCHABLE` varchar(7) NOT NULL,
  `DATEPOSTED` date NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `users` (
  `UID` bigint(20) NOT NULL AUTO_INCREMENT,
  `FIRSTNAME` varchar(55) NOT NULL,
  `LASTNAME` varchar(55) NOT NULL,
  `STREETADDRESS1` varchar(150) NOT NULL,
  `STREETADDRESS2` varchar(150) NOT NULL,
  `CITY` varchar(75) NOT NULL,
  `STATE` varchar(2) NOT NULL,
  `ZIP` varchar(10) NOT NULL,
  `PHONE` varchar(17) NOT NULL,
  `EMAIL` varchar(75) NOT NULL,
  `WEBSITE` varchar(150) NOT NULL,
  `USERNAME` varchar(25) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `APPROVED` varchar(255) NOT NULL DEFAULT '0',
  `TEMP` varchar(255) NOT NULL,
  PRIMARY KEY (`UID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;