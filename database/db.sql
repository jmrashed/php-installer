-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Oct 25, 2016 at 05:45 PM
-- Server version: 5.6.33-log
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `Xbit`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_about_us`
--

CREATE TABLE IF NOT EXISTS `tbl_about_us` (
  `about_title` text NOT NULL,
  `about_description` text NOT NULL,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_about_us`
--

INSERT INTO `tbl_about_us` (`about_title`, `about_description`, `image`) VALUES
('About Us', '<p style="text-align: center;">Bismillahir Rahmanir Rahim</p>\r\n\r\n<p style="text-align:justify"><strong>Xbit</strong> -not only a builders company but also a dream maker. Almighty lord has created all of the ingredients that meet the demand of human being to live or to lead in the earth. Get a proper shelter is the basic need of human and naturally we people cherish a dream about it. Xbit helps you to find a&nbsp;proper address that ensures durability and satisfaction.</p>\r\n\r\n<p style="text-align:justify"><strong>Xbit</strong> Builders Ltd. is one of the leading developer company in Bangladesh since 2011, it specialized in developing exclusive apartment complex in prime location of the Dhaka city like Paltan, Motijheel, Mugda, Aftabnagar, Basabo, Uttora, Khilgaon. Our all project is&nbsp;handover to the customer&nbsp;&nbsp; before timeline, that&#39;s why Xbit has become a trusted name in the developing sector.</p>\r\n\r\n<p style="text-align:justify"><strong>Xbit</strong> is a company committed to quality design and construction. All the buildings of Xbit have been designed according to the guidelines stated in the Bangladesh National Building Code and each building is capable of withstanding the code-specified natural forces like Earthquake and wind.</p>\r\n\r\n<p style="text-align:justify">Not only that, each building is equipped with an emergency fire escape to protect the building residents in the unlikely event of a fire. Additionally, at Xbit all construction materials and equipment have a high performance rating and are procured with great care to ensure the highest possible standard.</p>\r\n\r\n<p style="text-align:justify"><strong>Xbit</strong> runs by some successful and efficient management committee, all of the people in xbit are very much cooperative, dedicated and hard worker. In recognition of Xbit&#39;s excellent quality control in the design and construction of its buildings, Xbit has certified by RAJUK. Xbit is also one of the founding members of the Real Estate &amp; Housing Association of Bangladesh (REHAB).In a nutshell, Xbit can met everything that you desire.</p>\r\n', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE IF NOT EXISTS `tbl_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(50) NOT NULL,
  `admin_email` varchar(100) NOT NULL,
  `admin_user` varchar(50) NOT NULL,
  `admin_password` varchar(50) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`admin_id`, `admin_name`, `admin_email`, `admin_user`, `admin_password`) VALUES
(2, 'admin', 'admin@gmail.com', 'xbit_admin', 'bR@mWB'),
(3, 'shaheen', '', 'shaheen', 'shaheen123');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_builders`
--

CREATE TABLE IF NOT EXISTS `tbl_builders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `image` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_builders`
--

INSERT INTO `tbl_builders` (`id`, `title`, `description`, `image`) VALUES
(1, 'Xbit Builders', '<p>Xbit BuildersXbit BuildersXbit BuildersXbit BuildersXbit BuildersXbit Builders</p>\r\n', '03.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_clients`
--

CREATE TABLE IF NOT EXISTS `tbl_clients` (
  `client_id` int(20) NOT NULL AUTO_INCREMENT,
  `prefered_location` varchar(100) NOT NULL,
  `prefered_size` float NOT NULL,
  `min_bedroom` int(3) NOT NULL,
  `min_bathroom` int(3) NOT NULL,
  `name` varchar(100) NOT NULL,
  `profession` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` int(50) NOT NULL,
  `cellphone` int(11) NOT NULL,
  `address` varchar(300) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tbl_clients`
--

INSERT INTO `tbl_clients` (`client_id`, `prefered_location`, `prefered_size`, `min_bedroom`, `min_bathroom`, `name`, `profession`, `email`, `telephone`, `cellphone`, `address`, `datetime`) VALUES
(1, 'dh', 122, 1, 1, 'k', 'kl', 'jm', 8989, 6767, 'dh', '0000-00-00 00:00:00'),
(2, 'MAlibag', 1200, 3, 2, 's', 'Stu', 'sakib@gmail.com', 123456, 3216454, 'wrtertergthdtr', '0000-00-00 00:00:00'),
(3, 'Dhaka', 1000, 5, 4, 'Nazmus Sakib', 'Stu', 'jmrashed@gmail.com', 2147483647, 2147483647, '26/26 GM Road, Fultala, Chourhash, Kushtia', '2016-09-30 15:57:13'),
(4, 'Dhaka', 1200, 4, 4, 'MyPersonal', 'student', 'admin@admin.com', 2147483647, 3213212, 'dfgm ', '2016-10-11 22:13:36');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_completed_project`
--

CREATE TABLE IF NOT EXISTS `tbl_completed_project` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `land_area` varchar(300) NOT NULL,
  `land_position` varchar(300) NOT NULL,
  `city` varchar(50) NOT NULL,
  `project_location` varchar(100) NOT NULL,
  `label` varchar(100) NOT NULL,
  `no_of_apertment` varchar(100) NOT NULL,
  `apertment_size` text NOT NULL,
  `description` text NOT NULL,
  `no_of_lift` int(3) NOT NULL,
  `lift_capacity` int(3) NOT NULL,
  `generator` varchar(300) NOT NULL,
  `started_date` text NOT NULL,
  `ended_date` text NOT NULL,
  `image` text NOT NULL,
  `construction_status` text NOT NULL,
  `viedo_link` text NOT NULL,
  `map` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tbl_completed_project`
--

INSERT INTO `tbl_completed_project` (`id`, `project_name`, `address`, `land_area`, `land_position`, `city`, `project_location`, `label`, `no_of_apertment`, `apertment_size`, `description`, `no_of_lift`, `lift_capacity`, `generator`, `started_date`, `ended_date`, `image`, `construction_status`, `viedo_link`, `map`) VALUES
(1, 'Xbit Khan Villa', '51/9, South Basabo, Dhaka-1214', '5.5 Katha', 'Corner', 'Dhaka', 'Basabo', '9 Storied', '24 nos', '', '', 1, 8, 'Standby', '', '', 'khan.jpg', '', '', ' '),
(2, 'Xbit Rahman Tower', '24, Purana Paltan Line, Dhaka-1000', '12.25 Katha', 'Corner', 'Dhaka', 'Paltan', '10 Storied', '36 nos', '', '', 2, 8, 'Standby', '', '', '01.jpg', '', '', ' ');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contact_us`
--

CREATE TABLE IF NOT EXISTS `tbl_contact_us` (
  `head_office` varchar(100) NOT NULL,
  `h_address` text NOT NULL,
  `local_office` varchar(100) NOT NULL,
  `l_address` text NOT NULL,
  PRIMARY KEY (`head_office`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_contact_us`
--

INSERT INTO `tbl_contact_us` (`head_office`, `h_address`, `local_office`, `l_address`) VALUES
('Head Office', '<p><strong>Jomidar Palace</strong><em>(7th floor)</em></p>\r\n\r\n<p>291, Inner Circuler Road&nbsp;<br />\r\nFakirapool, Motijheel, Dhaka-1000<br />\r\nTel: +88-02-7194396<br />\r\n&nbsp; &nbsp; &nbsp; +8801780014080<br />\r\n<strong>E-mail</strong><br />\r\nxbitgroup@yahoo.com<br />\r\nxbitbuildersltd@gmail.com<br />\r\n<strong>Website</strong><br />\r\nwww.xbit-bd.com</p>\r\n', 'Notice', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_fisheries`
--

CREATE TABLE IF NOT EXISTS `tbl_fisheries` (
  `title` text NOT NULL,
  `description` text NOT NULL,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_fisheries`
--

INSERT INTO `tbl_fisheries` (`title`, `description`, `image`) VALUES
('Fishries', '<p><strong>There has been an increase in the number of dairy farmers getting into the Jersey breed, following the removal of milk quota, that&rsquo;s according to Kevin Brady &ndash; chairman of the Jersey Society of Ireland. Eoin McCarthy reports for TheCattleSite.</strong></p>\r\n\r\n<p>&ldquo;We have noticed over the last number of years, particularly in the last three years, there has been an increase in new people getting into the Jersey breed,&rdquo; Mr Brady said.</p>\r\n\r\n<p>Mr Brady spoke to Eoin McCarthy as members of the World Jersey Cattle Bureau Tour visited Moorepark Research Centre in Ireland to review work being conducted by Teagasc involving Jerseys and Jersey crosses.</p>\r\n', '04.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_graphics`
--

CREATE TABLE IF NOT EXISTS `tbl_graphics` (
  `title` text,
  `description` text,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_graphics`
--

INSERT INTO `tbl_graphics` (`title`, `description`, `image`) VALUES
('Color Graphics', '<p><strong>There has been an increase in the number of dairy farmers getting into the Jersey breed, following the removal of milk quota, that&rsquo;s according to Kevin Brady &ndash; chairman of the Jersey Society of Ireland. Eoin McCarthy reports for TheCattleSite.</strong></p>\r\n\r\n<p>&ldquo;We have noticed over the last number of years, particularly in the last three years, there has been an increase in new people getting into the Jersey breed,&rdquo; Mr Brady said.</p>\r\n\r\n<p>Mr Brady spoke to Eoin McCarthy as members of the World Jersey Cattle Bureau Tour visited Moorepark Research Centre in Ireland to review work being conducted by Teagasc involving Jerseys and Jersey crosses.</p>\r\n\r\n<p>&nbsp;</p>\r\n', 'aboutus.PNG');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_homesliders`
--

CREATE TABLE IF NOT EXISTS `tbl_homesliders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title1` text NOT NULL,
  `img1` text NOT NULL,
  `title2` text NOT NULL,
  `img2` text NOT NULL,
  `title3` text NOT NULL,
  `img3` text NOT NULL,
  `title4` text NOT NULL,
  `img4` text NOT NULL,
  `title5` text NOT NULL,
  `img5` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_homesliders`
--

INSERT INTO `tbl_homesliders` (`id`, `title1`, `img1`, `title2`, `img2`, `title3`, `img3`, `title4`, `img4`, `title5`, `img5`) VALUES
(1, 'Xbit Rahman Tower-2', 'Rahman-Tower-2.png', 'Xbit Lake View', 'lake-view.png', 'Xbit Rose', 'xbit-rose-home.png', 'Xbit Z Center', 'ZC.jpg', 'Xbit Green Homes', 'ggg.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_landowners`
--

CREATE TABLE IF NOT EXISTS `tbl_landowners` (
  `landowner_id` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(300) NOT NULL,
  `land_size` int(5) NOT NULL,
  `quantity` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contactname` varchar(100) NOT NULL,
  `contact_address` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` int(10) NOT NULL,
  `cellphone` int(10) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`landowner_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tbl_landowners`
--

INSERT INTO `tbl_landowners` (`landowner_id`, `address`, `land_size`, `quantity`, `name`, `contactname`, `contact_address`, `email`, `telephone`, `cellphone`, `datetime`) VALUES
(1, '1675 Snyder Avenue', 1256, 'bigha', 'Sakib', 'Nazmus Sakib', '1675 Snyder Avenue', 'sakib@gmail.com', 548963, 32589955, '2016-09-30 16:41:25'),
(2, 'Dhaka', 444, 'bigha', 'MyPersonal', 'fjgdfojg', 'ds;ofkgpdf', 'jmrashed@gmail.com', 85757, 5787, '2016-10-11 22:16:38');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mail`
--

CREATE TABLE IF NOT EXISTS `tbl_mail` (
  `mail_id` int(15) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone_number` int(3) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` varchar(400) NOT NULL,
  `date_time` datetime NOT NULL,
  PRIMARY KEY (`mail_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tbl_mail`
--

INSERT INTO `tbl_mail` (`mail_id`, `first_name`, `last_name`, `phone_number`, `email`, `message`, `date_time`) VALUES
(1, 'Mahedi', 'Hasan', 123, 'mahedi@gmail.com', 'Hello World..!!', '2016-10-01 20:14:11'),
(2, 'Mahedi', 'Hasan', 123, 'mahedi@gmail.com', 'Hello World..!!', '2016-10-01 20:27:48'),
(3, 'Abu', 'Nayem', 123456789, 'nayem@gmail.com', 'This is Nayem', '2016-10-01 20:28:29'),
(4, 'Nazmus', 'Sakib', 123456789, 'SAKIB@GMAIL.COM', 'I am here', '2016-10-01 22:15:34');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ongoing_project`
--

CREATE TABLE IF NOT EXISTS `tbl_ongoing_project` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `land_area` varchar(300) NOT NULL,
  `land_position` varchar(300) NOT NULL,
  `city` varchar(50) NOT NULL,
  `project_location` varchar(100) NOT NULL,
  `label` varchar(100) NOT NULL,
  `no_of_apertment` varchar(100) NOT NULL,
  `apertment_size` text NOT NULL,
  `description` text NOT NULL,
  `no_of_lift` int(3) NOT NULL,
  `lift_capacity` int(3) NOT NULL,
  `generator` varchar(300) NOT NULL,
  `started_date` text NOT NULL,
  `ended_date` text NOT NULL,
  `image` text NOT NULL,
  `construction_status` text NOT NULL,
  `viedo_link` text NOT NULL,
  `map` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tbl_ongoing_project`
--

INSERT INTO `tbl_ongoing_project` (`id`, `project_name`, `address`, `land_area`, `land_position`, `city`, `project_location`, `label`, `no_of_apertment`, `apertment_size`, `description`, `no_of_lift`, `lift_capacity`, `generator`, `started_date`, `ended_date`, `image`, `construction_status`, `viedo_link`, `map`) VALUES
(1, 'Xbit Rahman Tower-2', '24, Purana Paltan Line, Dhaka-1000', '24 Katha', 'South Facing Corner', 'Dhaka', 'Paltan', '10 & 1 Basement', '63', 'Type A=2017 sft.;Type B=2005 sft.;Type C=2060 sft.;Type D=1985 sft.;Type E=1336 sft.;Type F=1515 sft.;Type G=1570 sft.', '<p style="text-align: justify;"><strong>Engineering Features</strong></p>\r\n\r\n<p style="text-align: justify;">The building will be planned and designed by experienced Architects and Structural design engineers. Sub-soil investigation and soil composition shall be tested using latest equipment. Structural Design Parameters will be based on American Concrete Institute (ACI) code and American Standards of Testing Materials (ASTM) and BNBC codes. Structure is capable of consuming Earthquakes up to 6.5 on Richter scale and protection from Cyclonic storms 250 km per hour. All Structural materials including cement, steel, bricks, stone chips, sand and other materials are of good standard and screened for quality including laboratory testing.</p>\r\n\r\n<p style="text-align: justify;">Building Entrance</p>\r\n\r\n<hr />\r\n<p style="text-align: justify;">Secured decorative gate with lamp post as per the elevation &amp; perspective view of the building. Building Name with title &amp; logo in stylish letter on attractive background. security control and guard room. Comfortable internal driveway. Personal mail box.</p>\r\n\r\n<p style="text-align: justify;"><strong>Lift Lobby &amp; Stairs</strong></p>\r\n\r\n<p style="text-align: justify;">Lift door wall designed with decorative tiles. Homogeneous floor tiles in floor of all lift lobby. Designed wooden hand-rail with post throughout the staircase.</p>\r\n\r\n<p style="text-align: justify;"><strong>Kitchen</strong></p>\r\n\r\n<p style="text-align: justify;">Best quality local homogeneous tiles in floor.(RAK or equivalent) Company standard ceramic tiles in wall up to 7&#39; from floor. (RAK or equivalent) Imported stainless steel sink with mixer and drain-board. Double burner gas outlet point ready for connection. Provision for one 8&quot; exhaust fan suitably located near burner.</p>\r\n\r\n<p style="text-align: justify;"><strong>Electrical</strong></p>\r\n\r\n<p style="text-align: justify;">Standard concealed wiring in PVC conduits for light, flushed fan hooks, socket plug point etc. Individual Electric meters &amp; Electrical distribution board with a main circuit breaker.&nbsp; M.K. type switches/sockets in all rooms. Quality local wires (BRB/ Sunshine, etc) Quality circuit breakers in distribution boxes. Provision for air-conditioners in two bedrooms. Provision for two dish TV &amp; two Telephone points in each flat.</p>\r\n\r\n<p style="text-align: justify;"><strong>Bathroom Features</strong></p>\r\n\r\n<p style="text-align: justify;">Company standard commode and basin in all bathrooms. (RAK or equivalent) Company standard ceramic tiles in wall up to false ceiling &amp; matching tiles in floors.(RAK or equivalent) All bathrooms will have mirror and over head light point. Good quality local chrome plated fittings (basin mixer, shower, bib-cock, stop-cock etc.) Good quality bathroom accessories set (towel rail, soap case, Toilet paper holder)</p>\r\n\r\n<p style="text-align: justify;"><strong>Doors</strong></p>\r\n\r\n<p style="text-align: justify;">Soild decorative Mehogoni/Beneor flash door with door lock, door chain, apartment no. calling bell switch &amp; check viewer. Main door frame will be Mehogoni/Beneor/Equivalent. Veneered flush door shutters with French polished in internal rooms and verandah locations.</p>\r\n\r\n<p style="text-align: justify;">(Partex/Equivalent.) All other door frames will be of solid wood (Mehogoni/Equivalent.Toilet doors will be of durable good quality PVC/Equivalent.</p>\r\n\r\n<p style="text-align: justify;"><strong>Paint &amp; Polishing</strong></p>\r\n\r\n<p style="text-align: justify;">Plastic paint in all internal walls and white distemper in all ceiling. All exterior walls will have Durocem/Snowcem French polish in door frames &amp; shutters.</p>\r\n\r\n<p style="text-align: justify;"><strong>Windows</strong></p>\r\n\r\n<p style="text-align: justify;">Sliding aluminum windows as per perspective design. External windows to have rain water protective seal. 5mm glass with rubber channel &amp; mohair lining. Ms flat bar safety grill in all external windows.</p>\r\n\r\n<p style="text-align: justify;"><strong>Walls, Floors &amp; Veranda</strong></p>\r\n\r\n<p style="text-align: justify;">First class bricks in interior &amp; exterior wall. External &amp; Internal walls will be 5&quot; thick brickwork with fine plaster.</p>\r\n\r\n<p style="text-align: justify;">Company standard homogeneous floor tiles, 4&quot; skirting &amp; Bathroom door seal (RAK or equivalent) Comfortable verandas are strategically located at suitable sites to view outside. verandha railing will be as per selected design.</p>\r\n\r\n<p style="text-align: justify;"><strong>Lift &amp; Generator</strong></p>\r\n\r\n<p style="text-align: justify;">One brand new imported latest technology (VVVF) 06 (six) passenger capacity lift. Stainless steel cabin &amp; doors. One brand new imported&nbsp; Emergency Generator of adequate capacity. Capacity to cover Lift, Security and common area lighting, one light point &amp; one fan point in each apartment.</p>\r\n\r\n<p style="text-align: justify;"><strong>General Features</strong></p>\r\n\r\n<p style="text-align: justify;">220/440 Volt main cable electric supply line from PDB/DESCO with distribution board, panel and circuit breakers. All Electrical works will be done in compliance with PDB/ DESCO &amp; by expert 1st class ABC category contractors. Imported brand new Intercom system connecting all apartments to reception through concealed wiring. Main water line connection from WASA with a&nbsp;common meter. Two imported brand new pump one in operation &amp; one stand by.</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n', 3, 8, 'Standby', '2017-01-01', '2020-12-01', 'rt2.jpg', '<p style="text-align: center;"><strong>All Constructions equipment&nbsp;and element are ready&nbsp;to start work. &nbsp;</strong></p>\r\n', '', ' '),
(2, 'Xbit Green Homes', '3/3A,B,C; South Mugdha, Dhaka.', '17 Katha', 'Corner', 'Dhaka', 'Mugdha', '14 Storied+1 Basement', '84 nos', 'Type A=1367 sft.;Type B=1340 sft.;Type C=1166 sft.;Type D=1140 sft.;Type E=1358 sft.;Type F=1230 sft.;Type G=1130 sft.', '<p style="text-align: justify;"><strong>Engineering Features</strong></p>\r\n\r\n<p style="text-align: justify;">The building will be planned and designed by experienced Architects and Structural design engineers. Sub-soil investigation and soil composition shall be tested using latest equipment. Structural Design Parameters will be based on American Concrete Institute (ACI) code and American Standards of Testing Materials (ASTM) and BNBC codes. Structure is capable of consuming Earthquakes up to 6.5 on Richter scale and protection from Cyclonic storms 250 km per hour. All Structural materials including cement, steel, bricks, stone chips, sand and other materials are of good standard and screened for quality including laboratory testing.</p>\r\n\r\n<p style="text-align: justify;">Building Entrance</p>\r\n\r\n<hr />\r\n<p style="text-align: justify;">Secured decorative gate with lamp post as per the elevation &amp; perspective view of the building. Building Name with title &amp; logo in stylish letter on attractive background. security control and guard room. Comfortable internal driveway. Personal mail box.</p>\r\n\r\n<p style="text-align: justify;"><strong>Lift Lobby &amp; Stairs</strong></p>\r\n\r\n<p style="text-align: justify;">Lift door wall designed with decorative tiles. Homogeneous floor tiles in floor of all lift lobby. Designed wooden hand-rail with post throughout the staircase.</p>\r\n\r\n<p style="text-align: justify;"><strong>Kitchen</strong></p>\r\n\r\n<p style="text-align: justify;">Best quality local homogeneous tiles in floor.(RAK or equivalent) Company standard ceramic tiles in wall up to 7&#39; from floor. (RAK or equivalent) Imported stainless steel sink with mixer and drain-board. Double burner gas outlet point ready for connection. Provision for one 8&quot; exhaust fan suitably located near burner.</p>\r\n\r\n<p style="text-align: justify;"><strong>Electrical</strong></p>\r\n\r\n<p style="text-align: justify;">Standard concealed wiring in PVC conduits for light, flushed fan hooks, socket plug point etc. Individual Electric meters &amp; Electrical distribution board with a main circuit breaker.&nbsp; M.K. type switches/sockets in all rooms. Quality local wires (BRB/ Sunshine, etc) Quality circuit breakers in distribution boxes. Provision for air-conditioners in two bedrooms. Provision for two dish TV &amp; two Telephone points in each flat.</p>\r\n\r\n<p style="text-align: justify;"><strong>Bathroom Features</strong></p>\r\n\r\n<p style="text-align: justify;">Company standard commode and basin in all bathrooms. (RAK or equivalent) Company standard ceramic tiles in wall up to false ceiling &amp; matching tiles in floors.(RAK or equivalent) All bathrooms will have mirror and over head light point. Good quality local chrome plated fittings (basin mixer, shower, bib-cock, stop-cock etc.) Good quality bathroom accessories set (towel rail, soap case, Toilet paper holder)</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n\r\n<p style="text-align: justify;"><strong>Doors</strong></p>\r\n\r\n<p style="text-align: justify;">Soild decorative Mehogoni/Beneor flash door with door lock, door chain, apartment no. calling bell switch &amp; check viewer. Main door frame will be Mehogoni/Beneor/Equivalent. Veneered flush door shutters with French polished in internal rooms and verandah locations.</p>\r\n\r\n<p style="text-align: justify;">(Partex/Equivalent.) All other door frames will be of solid wood (Mehogoni/Equivalent.Toilet doors will be of durable good quality PVC/Equivalent.</p>\r\n\r\n<p style="text-align: justify;"><strong>Paint &amp; Polishing</strong></p>\r\n\r\n<p style="text-align: justify;">Plastic paint in all internal walls and white distemper in all ceiling. All exterior walls will have Durocem/Snowcem French polish in door frames &amp; shutters.</p>\r\n\r\n<p style="text-align: justify;"><strong>Windows</strong></p>\r\n\r\n<p style="text-align: justify;">Sliding aluminum windows as per perspective design. External windows to have rain water protective seal. 5mm glass with rubber channel &amp; mohair lining. Ms flat bar safety grill in all external windows.</p>\r\n\r\n<p style="text-align: justify;"><strong>Walls, Floors &amp; Veranda</strong></p>\r\n\r\n<p style="text-align: justify;">First class bricks in interior &amp; exterior wall. External &amp; Internal walls will be 5&quot; thick brickwork with fine plaster.</p>\r\n\r\n<p style="text-align: justify;">Company standard homogeneous floor tiles, 4&quot; skirting &amp; Bathroom door seal (RAK or equivalent) Comfortable verandas are strategically located at suitable sites to view outside. verandha railing will be as per selected design.</p>\r\n\r\n<p style="text-align: justify;"><strong>Lift &amp; Generator</strong></p>\r\n\r\n<p style="text-align: justify;">One brand new imported latest technology (VVVF) 06 (six) passenger capacity lift. Stainless steel cabin &amp; doors. One brand new imported&nbsp; Emergency Generator of adequate capacity. Capacity to cover Lift, Security and common area lighting, one light point &amp; one fan point in each apartment.</p>\r\n\r\n<p style="text-align: justify;"><strong>General Features</strong></p>\r\n\r\n<p style="text-align: justify;">220/440 Volt main cable electric supply line from PDB/DESCO with distribution board, panel and circuit breakers. All Electrical works will be done in compliance with PDB/ DESCO &amp; by expert 1st class ABC category contractors. Imported brand new Intercom system connecting all apartments to reception through concealed wiring. Main water line connection from WASA with common meter. Two imported brand new pump one in operation &amp; one stand by.</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n', 3, 8, 'Standby', '2016-01-01', '2020-01-01', 'green.jpg', '<p style="text-align: center;"><strong>Running Project.</strong></p>\r\n', '', ' '),
(3, 'Xbit Rose', '52/4, South Basabo, Dhaka-1214', '5 Katha', 'Corner', 'Dhaka', 'Basabo', '10 Storied', '18 nos', 'Type A=1250 sft.;Type B=1250 sft.', '<p style="text-align: justify;"><strong>Engineering Features</strong></p>\r\n\r\n<p style="text-align: justify;">The building will be planned and designed by experienced Architects and Structural design engineers. Sub-soil investigation and soil composition shall be tested using latest equipment. Structural Design Parameters will be based on American Concrete Institute (ACI) code and American Standards of Testing Materials (ASTM) and BNBC codes. Structure is capable of consuming Earthquakes up to 6.5 on Richter scale and protection from Cyclonic storms 250 km per hour. All Structural materials including cement, steel, bricks, stone chips, sand and other materials are of good standard and screened for quality including laboratory testing.</p>\r\n\r\n<p style="text-align: justify;">Building Entrance</p>\r\n\r\n<hr />\r\n<p style="text-align: justify;">Secured decorative gate with lamp post as per the elevation &amp; perspective view of the building. Building Name with title &amp; logo in stylish letter on attractive background. security control and guard room. Comfortable internal driveway. Personal mail box.</p>\r\n\r\n<p style="text-align: justify;"><strong>Lift Lobby &amp; Stairs</strong></p>\r\n\r\n<p style="text-align: justify;">Lift door wall designed with decorative tiles. Homogeneous floor tiles in floor of all lift lobby. Designed wooden hand-rail with post throughout the staircase.</p>\r\n\r\n<p style="text-align: justify;"><strong>Kitchen</strong></p>\r\n\r\n<p style="text-align: justify;">Best quality local homogeneous tiles in floor.(RAK or equivalent) Company standard ceramic tiles in wall up to 7&#39; from floor. (RAK or equivalent) Imported stainless steel sink with mixer and drain-board. Double burner gas outlet point ready for connection. Provision for one 8&quot; exhaust fan suitably located near burner.</p>\r\n\r\n<p style="text-align: justify;"><strong>Electrical</strong></p>\r\n\r\n<p style="text-align: justify;">Standard concealed wiring in PVC conduits for light, flushed fan hooks, socket plug point etc. Individual Electric meters &amp; Electrical distribution board with a main circuit breaker.&nbsp; M.K. type switches/sockets in all rooms. Quality local wires (BRB/ Sunshine, etc) Quality circuit breakers in distribution boxes. Provision for air-conditioners in two bedrooms. Provision for two dish TV &amp; two Telephone points in each flat.</p>\r\n\r\n<p style="text-align: justify;"><strong>Bathroom Features</strong></p>\r\n\r\n<p style="text-align: justify;">Company standard commode and basin in all bathrooms. (RAK or equivalent) Company standard ceramic tiles in wall up to false ceiling &amp; matching tiles in floors.(RAK or equivalent) All bathrooms will have mirror and over head light point. Good quality local chrome plated fittings (basin mixer, shower, bib-cock, stop-cock etc.) Good quality bathroom accessories set (towel rail, soap case, Toilet paper holder)</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n\r\n<p style="text-align: justify;"><strong>Doors</strong></p>\r\n\r\n<p style="text-align: justify;">Soild decorative Mehogoni/Beneor flash door with door lock, door chain, apartment no. calling bell switch &amp; check viewer. Main door frame will be Mehogoni/Beneor/Equivalent. Veneered flush door shutters with French polished in internal rooms and verandah locations.</p>\r\n\r\n<p style="text-align: justify;">(Partex/Equivalent.) All other door frames will be of solid wood (Mehogoni/Equivalent.Toilet doors will be of durable good quality PVC/Equivalent.</p>\r\n\r\n<p style="text-align: justify;"><strong>Paint &amp; Polishing</strong></p>\r\n\r\n<p style="text-align: justify;">Plastic paint in all internal walls and white distemper in all ceiling. All exterior walls will have Durocem/Snowcem French polish in door frames &amp; shutters.</p>\r\n\r\n<p style="text-align: justify;"><strong>Windows</strong></p>\r\n\r\n<p style="text-align: justify;">Sliding aluminum windows as per perspective design. External windows to have rain water protective seal. 5mm glass with rubber channel &amp; mohair lining. Ms flat bar safety grill in all external windows.</p>\r\n\r\n<p style="text-align: justify;"><strong>Walls, Floors &amp; Veranda</strong></p>\r\n\r\n<p style="text-align: justify;">First class bricks in interior &amp; exterior wall. External &amp; Internal walls will be 5&quot; thick brickwork with fine plaster.</p>\r\n\r\n<p style="text-align: justify;">Company standard homogeneous floor tiles, 4&quot; skirting &amp; Bathroom door seal (RAK or equivalent) Comfortable verandas are strategically located at suitable sites to view outside. verandha railing will be as per selected design.</p>\r\n\r\n<p style="text-align: justify;"><strong>Lift &amp; Generator</strong></p>\r\n\r\n<p style="text-align: justify;">One brand new imported latest technology (VVVF) 06 (six) passenger capacity lift. Stainless steel cabin &amp; doors. One brand new imported&nbsp; Emergency Generator of adequate capacity. Capacity to cover Lift, Security and common area lighting, one light point &amp; one fan point in each apartment.</p>\r\n\r\n<p style="text-align: justify;"><strong>General Features</strong></p>\r\n\r\n<p style="text-align: justify;">220/440 Volt main cable electric supply line from PDB/DESCO with distribution board, panel and circuit breakers. All Electrical works will be done in compliance with PDB/ DESCO &amp; by expert 1st class ABC category contractors. Imported brand new Intercom system connecting all apartments to reception through concealed wiring. Main water line connection from WASA with common meter. Two imported brand new pump one in operation &amp; one stand by.</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n', 1, 8, 'Standby', '2016-01-01', '2019-12-01', 'rose.jpg', '<p style="text-align: center;"><strong>Al construction equipment and element are ready to start work.</strong></p>\r\n', '', ' '),
(4, 'Xbit Lake View', 'Plot #17, Block #C, Aftabnagar, Rampura, Dhaka.', '5 Katha', 'Corner', 'Dhaka', 'Aftabnagar', '10 Storied', '18 nos', 'Type A=1280 sft.;Type B=1280 sft.', '<p style="text-align: justify;"><strong>Engineering Features</strong></p>\r\n\r\n<p style="text-align: justify;">The building will be planned and designed by experienced Architects and Structural design engineers. Sub-soil investigation and soil composition shall be tested using latest equipment. Structural Design Parameters will be based on American Concrete Institute (ACI) code and American Standards of Testing Materials (ASTM) and BNBC codes. Structure is capable of consuming Earthquakes up to 6.5 on Richter scale and protection from Cyclonic storms 250 km per hour. All Structural materials including cement, steel, bricks, stone chips, sand and other materials are of good standard and screened for quality including laboratory testing.</p>\r\n\r\n<p style="text-align: justify;">Building Entrance</p>\r\n\r\n<hr />\r\n<p style="text-align: justify;">Secured decorative gate with lamp post as per the elevation &amp; perspective view of the building. Building Name with title &amp; logo in stylish letter on attractive background. security control and guard room. Comfortable internal driveway. Personal mail box.</p>\r\n\r\n<p style="text-align: justify;"><strong>Lift Lobby &amp; Stairs</strong></p>\r\n\r\n<p style="text-align: justify;">Lift door wall designed with decorative tiles. Homogeneous floor tiles in floor of all lift lobby. Designed wooden hand-rail with post throughout the staircase.</p>\r\n\r\n<p style="text-align: justify;"><strong>Kitchen</strong></p>\r\n\r\n<p style="text-align: justify;">Best quality local homogeneous tiles in floor.(RAK or equivalent) Company standard ceramic tiles in wall up to 7&#39; from floor. (RAK or equivalent) Imported stainless steel sink with mixer and drain-board. Double burner gas outlet point ready for connection. Provision for one 8&quot; exhaust fan suitably located near burner.</p>\r\n\r\n<p style="text-align: justify;"><strong>Electrical</strong></p>\r\n\r\n<p style="text-align: justify;">Standard concealed wiring in PVC conduits for light, flushed fan hooks, socket plug point etc. Individual Electric meters &amp; Electrical distribution board with a main circuit breaker.&nbsp; M.K. type switches/sockets in all rooms. Quality local wires (BRB/ Sunshine, etc) Quality circuit breakers in distribution boxes. Provision for air-conditioners in two bedrooms. Provision for two dish TV &amp; two Telephone points in each flat.</p>\r\n\r\n<p style="text-align: justify;"><strong>Bathroom Features</strong></p>\r\n\r\n<p style="text-align: justify;">Company standard commode and basin in all bathrooms. (RAK or equivalent) Company standard ceramic tiles in wall up to false ceiling &amp; matching tiles in floors.(RAK or equivalent) All bathrooms will have mirror and over head light point. Good quality local chrome plated fittings (basin mixer, shower, bib-cock, stop-cock etc.) Good quality bathroom accessories set (towel rail, soap case, Toilet paper holder)</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n\r\n<p style="text-align: justify;"><strong>Doors</strong></p>\r\n\r\n<p style="text-align: justify;">Soild decorative Mehogoni/Beneor flash door with door lock, door chain, apartment no. calling bell switch &amp; check viewer. Main door frame will be Mehogoni/Beneor/Equivalent. Veneered flush door shutters with French polished in internal rooms and verandah locations.</p>\r\n\r\n<p style="text-align: justify;">(Partex/Equivalent.) All other door frames will be of solid wood (Mehogoni/Equivalent.Toilet doors will be of durable good quality PVC/Equivalent.</p>\r\n\r\n<p style="text-align: justify;"><strong>Paint &amp; Polishing</strong></p>\r\n\r\n<p style="text-align: justify;">Plastic paint in all internal walls and white distemper in all ceiling. All exterior walls will have Durocem/Snowcem French polish in door frames &amp; shutters.</p>\r\n\r\n<p style="text-align: justify;"><strong>Windows</strong></p>\r\n\r\n<p style="text-align: justify;">Sliding aluminum windows as per perspective design. External windows to have rain water protective seal. 5mm glass with rubber channel &amp; mohair lining. Ms flat bar safety grill in all external windows.</p>\r\n\r\n<p style="text-align: justify;"><strong>Walls, Floors &amp; Veranda</strong></p>\r\n\r\n<p style="text-align: justify;">First class bricks in interior &amp; exterior wall. External &amp; Internal walls will be 5&quot; thick brickwork with fine plaster.</p>\r\n\r\n<p style="text-align: justify;">Company standard homogeneous floor tiles, 4&quot; skirting &amp; Bathroom door seal (RAK or equivalent) Comfortable verandas are strategically located at suitable sites to view outside. verandha railing will be as per selected design.</p>\r\n\r\n<p style="text-align: justify;"><strong>Lift &amp; Generator</strong></p>\r\n\r\n<p style="text-align: justify;">One brand new imported latest technology (VVVF) 06 (six) passenger capacity lift. Stainless steel cabin &amp; doors. One brand new imported&nbsp; Emergency Generator of adequate capacity. Capacity to cover Lift, Security and common area lighting, one light point &amp; one fan point in each apartment.</p>\r\n\r\n<p style="text-align: justify;"><strong>General Features</strong></p>\r\n\r\n<p style="text-align: justify;">220/440 Volt main cable electric supply line from PDB/DESCO with distribution board, panel and circuit breakers. All Electrical works will be done in compliance with PDB/ DESCO &amp; by expert 1st class ABC category contractors. Imported brand new Intercom system connecting all apartments to reception through concealed wiring. Main water line connection from WASA with common meter. Two imported brand new pump one in operation &amp; one stand by.</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n', 1, 8, 'Standby', '2013-11-01', '2017-01-01', 'Untitled-4.jpg', '<p style="text-align: center;"><strong>Construction has already completed. It will complete &nbsp;within January 2017.&nbsp;</strong></p>\r\n', '', ' '),
(5, 'Xbit Z Center', '41/1, Purana Paltan, Dhaka-1000', '6 Katha', 'Facing Corner', 'Dhaka', 'Paltan', '10 Storied', '18 nos', 'Type A=670 sft.;Type B=735 sft.;Type C=735 sft.;Type D=670 sft.', '<p style="text-align: justify;"><strong>Engineering Features</strong></p>\r\n\r\n<p style="text-align: justify;">The building will be planned and designed by experienced Architects and Structural design engineers. Sub-soil investigation and soil composition shall be tested using latest equipment. Structural Design Parameters will be based on American Concrete Institute (ACI) code and American Standards of Testing Materials (ASTM) and BNBC codes. Structure is capable of consuming Earthquakes up to 6.5 on Richter scale and protection from Cyclonic storms 250 km per hour. All Structural materials including cement, steel, bricks, stone chips, sand and other materials are of good standard and screened for quality including laboratory testing.</p>\r\n\r\n<p style="text-align: justify;">Building Entrance</p>\r\n\r\n<hr />\r\n<p style="text-align: justify;">Secured decorative gate with lamp post as per the elevation &amp; perspective view of the building. Building Name with title &amp; logo in stylish letter on attractive background. security control and guard room. Comfortable internal driveway. Personal mail box.</p>\r\n\r\n<p style="text-align: justify;"><strong>Lift Lobby &amp; Stairs</strong></p>\r\n\r\n<p style="text-align: justify;">Lift door wall designed with decorative tiles. Homogeneous floor tiles in floor of all lift lobby. Designed wooden hand-rail with post throughout the staircase.</p>\r\n\r\n<p style="text-align: justify;"><strong>Kitchen</strong></p>\r\n\r\n<p style="text-align: justify;">Best quality local homogeneous tiles in floor.(RAK or equivalent) Company standard ceramic tiles in wall up to 7&#39; from floor. (RAK or equivalent) Imported stainless steel sink with mixer and drain-board. Double burner gas outlet point ready for connection. Provision for one 8&quot; exhaust fan suitably located near burner.</p>\r\n\r\n<p style="text-align: justify;"><strong>Electrical</strong></p>\r\n\r\n<p style="text-align: justify;">Standard concealed wiring in PVC conduits for light, flushed fan hooks, socket plug point etc. Individual Electric meters &amp; Electrical distribution board with a main circuit breaker.&nbsp; M.K. type switches/sockets in all rooms. Quality local wires (BRB/ Sunshine, etc) Quality circuit breakers in distribution boxes. Provision for air-conditioners in two bedrooms. Provision for two dish TV &amp; two Telephone points in each flat.</p>\r\n\r\n<p style="text-align: justify;"><strong>Bathroom Features</strong></p>\r\n\r\n<p style="text-align: justify;">Company standard commode and basin in all bathrooms. (RAK or equivalent) Company standard ceramic tiles in wall up to false ceiling &amp; matching tiles in floors.(RAK or equivalent) All bathrooms will have mirror and over head light point. Good quality local chrome plated fittings (basin mixer, shower, bib-cock, stop-cock etc.) Good quality bathroom accessories set (towel rail, soap case, Toilet paper holder)</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n\r\n<p style="text-align: justify;"><strong>Doors</strong></p>\r\n\r\n<p style="text-align: justify;">Soild decorative Mehogoni/Beneor flash door with door lock, door chain, apartment no. calling bell switch &amp; check viewer. Main door frame will be Mehogoni/Beneor/Equivalent. Veneered flush door shutters with French polished in internal rooms and verandah locations.</p>\r\n\r\n<p style="text-align: justify;">(Partex/Equivalent.) All other door frames will be of solid wood (Mehogoni/Equivalent.Toilet doors will be of durable good quality PVC/Equivalent.</p>\r\n\r\n<p style="text-align: justify;"><strong>Paint &amp; Polishing</strong></p>\r\n\r\n<p style="text-align: justify;">Plastic paint in all internal walls and white distemper in all ceiling. All exterior walls will have Durocem/Snowcem French polish in door frames &amp; shutters.</p>\r\n\r\n<p style="text-align: justify;"><strong>Windows</strong></p>\r\n\r\n<p style="text-align: justify;">Sliding aluminum windows as per perspective design. External windows to have rain water protective seal. 5mm glass with rubber channel &amp; mohair lining. Ms flat bar safety grill in all external windows.</p>\r\n\r\n<p style="text-align: justify;"><strong>Walls, Floors &amp; Veranda</strong></p>\r\n\r\n<p style="text-align: justify;">First class bricks in interior &amp; exterior wall. External &amp; Internal walls will be 5&quot; thick brickwork with fine plaster.</p>\r\n\r\n<p style="text-align: justify;">Company standard homogeneous floor tiles, 4&quot; skirting &amp; Bathroom door seal (RAK or equivalent) Comfortable verandas are strategically located at suitable sites to view outside. verandha railing will be as per selected design.</p>\r\n\r\n<p style="text-align: justify;"><strong>Lift &amp; Generator</strong></p>\r\n\r\n<p style="text-align: justify;">One brand new imported latest technology (VVVF) 06 (six) passenger capacity lift. Stainless steel cabin &amp; doors. One brand new imported&nbsp; Emergency Generator of adequate capacity. Capacity to cover Lift, Security and common area lighting, one light point &amp; one fan point in each apartment.</p>\r\n\r\n<p style="text-align: justify;"><strong>General Features</strong></p>\r\n\r\n<p style="text-align: justify;">220/440 Volt main cable electric supply line from PDB/DESCO with distribution board, panel and circuit breakers. All Electrical works will be done in compliance with PDB/ DESCO &amp; by expert 1st class ABC category contractors. Imported brand new Intercom system connecting all apartments to reception through concealed wiring. Main water line connection from WASA with common meter. Two imported brand new pump one in operation &amp; one stand by.</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n\r\n<p style="text-align: justify;">&nbsp;</p>\r\n', 1, 8, 'Standby', '2013-01-01', '2017-01-01', 'zp.jpg', '<p style="text-align: center;"><strong>It has completed project. Only Two Flats are available for sale.</strong></p>\r\n', '', ' ');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_projects_slider`
--

CREATE TABLE IF NOT EXISTS `tbl_projects_slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_category` varchar(300) NOT NULL,
  `project_name` text NOT NULL,
  `img1` text NOT NULL,
  `img2` text NOT NULL,
  `img3` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `tbl_projects_slider`
--

INSERT INTO `tbl_projects_slider` (`id`, `project_category`, `project_name`, `img1`, `img2`, `img3`) VALUES
(20, 'tbl_ongoing_project', 'Xbit Rahman Tower-2', '12.jpg', '11.jpg', '13.jpg'),
(21, 'tbl_ongoing_project', 'Xbit Green Homes', 'g1.jpg', 'g2.jpg', 'g3.jpg'),
(22, 'tbl_ongoing_project', 'Xbit Rose', 'rp3.jpg', 'rp1.jpg', 'rp2.jpg'),
(23, 'tbl_ongoing_project', 'Xbit Lake View', 'project-1.jpg', 'project-2.jpg', 'project-3.jpg'),
(24, 'tbl_ongoing_project', 'Xbit Z Center', 'z2.jpg', 'z1.jpg', 'z2.jpg'),
(27, 'tbl_completed_project', 'Xbit Rahman Tower', 'rt1.jpg', 'rt1.jpg', 'rt1.jpg'),
(28, 'tbl_completed_project', 'Xbit Khan Villa', 'kv1.jpg', 'kv1.jpg', 'kv1.jpg'),
(29, 'tbl_ongoing_project', 'Xbit Rose', 'rp3.jpg', 'rp1.jpg', 'rp2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_floorplans`
--

CREATE TABLE IF NOT EXISTS `tbl_project_floorplans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poject_category` varchar(100) NOT NULL,
  `project_name` text NOT NULL,
  `img1` text NOT NULL,
  `img2` text NOT NULL,
  `img3` text NOT NULL,
  `img4` text NOT NULL,
  `img5` text NOT NULL,
  `img6` text NOT NULL,
  `img7` text NOT NULL,
  `img8` text NOT NULL,
  `img9` text NOT NULL,
  `img10` text NOT NULL,
  `img11` text NOT NULL,
  `img12` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `tbl_project_floorplans`
--

INSERT INTO `tbl_project_floorplans` (`id`, `poject_category`, `project_name`, `img1`, `img2`, `img3`, `img4`, `img5`, `img6`, `img7`, `img8`, `img9`, `img10`, `img11`, `img12`) VALUES
(1, 'tbl_ongoing_project', 'Xbit Rahman Tower-2', 'tf.jpg', 'a.jpg', 'b.jpg', 'c.jpg', 'd.jpg', 'e.jpg', 'f.jpg', 'g.jpg', 'car2.jpg', 'carr.jpg', '', ''),
(2, 'tbl_ongoing_project', 'Xbit Green Homes', 'tfg.jpg', 'ag.jpg', 'bg.jpg', 'cg.jpg', 'dg.jpg', 'eg.jpg', 'fg.jpg', 'gg.jpg', 'gf.jpg', 'carg.jpg', '', ''),
(3, 'tbl_ongoing_project', 'Xbit Rose', 'floor-plan.jpg', 'floor-1.jpg', 'floor-2.jpg', 'car.jpg', '', '', '', '', '', '', '', ''),
(4, 'tbl_ongoing_project', 'Xbit Lake View', 'ltf.png', 'la.png', 'lb.png', 'lc.png', '', '', '', '', '', '', '', ''),
(5, 'tbl_ongoing_project', 'Xbit Z Center', 'ztf.jpg', 'FP-1.png', 'FP-2.png', 'FP-3.png', 'FP-4.png', 'zcar.jpg', '', '', '', '', '', ''),
(6, 'tbl_completed_project', 'Xbit Khan Villa', '', '', '', '', '', '', '', '', '', '', '', ''),
(7, 'tbl_completed_project', 'Xbit Rahman Tower', '', '', '', '', '', '', '', '', '', '', '', ''),
(8, 'tbl_upcoming_project', 'rose 1', '', '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_proparties`
--

CREATE TABLE IF NOT EXISTS `tbl_proparties` (
  `title` text,
  `description` text,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_proparties`
--

INSERT INTO `tbl_proparties` (`title`, `description`, `image`) VALUES
('Proparties', '<p><strong>There has been an increase in the number of dairy farmers getting into the Jersey breed, following the removal of milk quota, that&rsquo;s according to Kevin Brady &ndash; chairman of the Jersey Society of Ireland. Eoin McCarthy reports for TheCattleSite.</strong></p>\r\n\r\n<p>&ldquo;We have noticed over the last number of years, particularly in the last three years, there has been an increase in new people getting into the Jersey breed,&rdquo; Mr Brady said.</p>\r\n\r\n<p>Mr Brady spoke to Eoin McCarthy as members of the World Jersey Cattle Bureau Tour visited Moorepark Research Centre in Ireland to review work being conducted by Teagasc involving Jerseys and Jersey crosses.</p>\r\n', '05.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_qualitypolicys`
--

CREATE TABLE IF NOT EXISTS `tbl_qualitypolicys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img1` text NOT NULL,
  `img2` text NOT NULL,
  `img3` text NOT NULL,
  `img4` text NOT NULL,
  `img5` text NOT NULL,
  `img6` text NOT NULL,
  `img7` text NOT NULL,
  `img8` text NOT NULL,
  `img9` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_qualitypolicys`
--

INSERT INTO `tbl_qualitypolicys` (`id`, `img1`, `img2`, `img3`, `img4`, `img5`, `img6`, `img7`, `img8`, `img9`) VALUES
(1, 'rehab1.png', 'akbor1.png', 'harun1.png', 'rajuk1.png', 'trade1.png', 'harun11.png', 'bangladesh_govt_logo_0.gif', 'rajuk.jpg', 'rehab.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_social_links`
--

CREATE TABLE IF NOT EXISTS `tbl_social_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facebook` varchar(300) NOT NULL,
  `twitter` varchar(300) NOT NULL,
  `youtube` varchar(300) NOT NULL,
  `googletalk` varchar(300) NOT NULL,
  `linkedin` varchar(300) NOT NULL,
  `instagram` varchar(300) NOT NULL,
  `website` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_social_links`
--

INSERT INTO `tbl_social_links` (`id`, `facebook`, `twitter`, `youtube`, `googletalk`, `linkedin`, `instagram`, `website`) VALUES
(1, 'xbitbuilders', 'xbitbd', 'channel/UC3Ti1qpIt0JCR5I0t4yk9jA', '102239765474053701077', 'xbit', 'xbit', 'xbit-bd.com');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sonargoan`
--

CREATE TABLE IF NOT EXISTS `tbl_sonargoan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `image` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_sonargoan`
--

INSERT INTO `tbl_sonargoan` (`id`, `title`, `description`, `image`) VALUES
(1, 'Soanrgoan City Dhaka', '<p><strong>There has been an increase in the number of dairy farmers getting into the Jersey breed, following the removal of milk quota, that&rsquo;s according to Kevin Brady &ndash; chairman of the Jersey Society of Ireland. Eoin McCarthy reports for TheCattleSite.</strong></p>\r\n\r\n<p>&ldquo;We have noticed over the last number of years, particularly in the last three years, there has been an increase in new people getting into the Jersey breed,&rdquo; Mr Brady said.</p>\r\n\r\n<p>Mr Brady spoke to Eoin McCarthy as members of the World Jersey Cattle Bureau Tour visited Moorepark Research Centre in Ireland to review work being conducted by Teagasc involving Jerseys and Jersey crosses.</p>\r\n', '08.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_team`
--

CREATE TABLE IF NOT EXISTS `tbl_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `designation` varchar(300) NOT NULL,
  `details` text NOT NULL,
  `image` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `tbl_team`
--

INSERT INTO `tbl_team` (`id`, `name`, `designation`, `details`, `image`) VALUES
(15, 'Muhammad Shahjahan Madani', 'Chairman', '', 'CHARMAN  SAR.jpg'),
(16, 'Md. Ali Akbor', 'Managing Director', '', '1.jpg'),
(17, 'Dr. Md. Harun Or Rashid', 'Deputy Managing Director', '', 'DMD SAR.jpg'),
(18, 'Md. Monir Hossain', 'Director, Land', '', '3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_testimonials`
--

CREATE TABLE IF NOT EXISTS `tbl_testimonials` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `address` varchar(300) NOT NULL,
  `description` text NOT NULL,
  `web` varchar(300) NOT NULL,
  `facebook` varchar(300) NOT NULL,
  `twitter` varchar(300) NOT NULL,
  `skype` varchar(100) NOT NULL,
  `image` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `tbl_testimonials`
--

INSERT INTO `tbl_testimonials` (`id`, `name`, `address`, `description`, `web`, `facebook`, `twitter`, `skype`, `image`) VALUES
(20, 'Md. Rahman and his family.', 'Rahman Tower, Paltan, Dhaka', '<p>We trust the name Xbit for their excellent quality in construction and reliability.&nbsp;</p>\r\n', 'www.google.com', 'dacebook.com', 'twitter.com', 'skype.com', 'testimonial.jpg'),
(22, 'Mr. Khan and his family', 'Khan Villa, Basabo, Dhaka', '<p>To the whole Xbit team, Thank You. YTou have done a wonderful job in building our home.</p>\r\n', 'www.google.com', 'dacebook.com', 'twitter.com', 'skype.com', 'testimonial.jpg'),
(23, 'Md. Rahman and his family', 'Dhaka', '', 'Dhaka', 'Dhaka', 'Dhaka', 'Dhaka', 'testimonial.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_upcoming_project`
--

CREATE TABLE IF NOT EXISTS `tbl_upcoming_project` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `land_area` varchar(300) NOT NULL,
  `land_position` varchar(300) NOT NULL,
  `city` varchar(50) NOT NULL,
  `project_location` varchar(100) NOT NULL,
  `label` varchar(100) NOT NULL,
  `no_of_apertment` varchar(100) NOT NULL,
  `apertment_size` text NOT NULL,
  `description` text NOT NULL,
  `no_of_lift` int(3) NOT NULL,
  `lift_capacity` int(3) NOT NULL,
  `generator` varchar(300) NOT NULL,
  `started_date` text NOT NULL,
  `ended_date` text NOT NULL,
  `image` text NOT NULL,
  `construction_status` text NOT NULL,
  `viedo_link` text NOT NULL,
  `map` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_welfare`
--

CREATE TABLE IF NOT EXISTS `tbl_welfare` (
  `title` text,
  `description` text,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_welfare`
--

INSERT INTO `tbl_welfare` (`title`, `description`, `image`) VALUES
('Welfare Foundation', '<p><strong>There has been an increase in the number of dairy farmers getting into the Jersey breed, following the removal of milk quota, that&rsquo;s according to Kevin Brady &ndash; chairman of the Jersey Society of Ireland. Eoin McCarthy reports for TheCattleSite.</strong></p>\r\n\r\n<p>&ldquo;We have noticed over the last number of years, particularly in the last three years, there has been an increase in new people getting into the Jersey breed,&rdquo; Mr Brady said.</p>\r\n\r\n<p>Mr Brady spoke to Eoin McCarthy as members of the World Jersey Cattle Bureau Tour visited Moorepark Research Centre in Ireland to review work being conducted by Teagasc involving Jerseys and Jersey crosses.</p>\r\n', '01.jpg');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
