# NAPT SQL Dump
# version 0.2
# http://napt.berlios.de
#
# Generation Time: Jul 31, 2004
# 
# Database : `napt`
# 

USE napt;

# --------------------------------------------------------
# Table structure for table `guestbook_guestbook`
#
CREATE TABLE `guestbook_guestbook` (
  `id` int(11) NOT NULL auto_increment,
  `zeit` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `autor` varchar(100) NOT NULL default '',
  `email` varchar(255) default NULL,
  `webseite` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=BerkeleyDB AUTO_INCREMENT=10 ;
INSERT INTO `guestbook_guestbook` VALUES (1, '1980-02-20 0:42:23', 'Herzlich Willkommen im G&auml;stebuch', 'Admin', '', '');
