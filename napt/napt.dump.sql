-- MySQL dump 8.23
--
-- Host: localhost    Database: napt
---------------------------------------------------------
-- Server version	3.23.58

--
-- Current Database: napt
--

DROP DATABASE IF EXISTS napt;
CREATE DATABASE napt;

USE napt;

--
-- Table structure for table `group_members`
--

CREATE TABLE group_members (
  id int(11) NOT NULL auto_increment PRIMARY KEY,
  `user` int(11) NOT NULL,
  `group` int(11) NOT NULL
) TYPE=MyISAM;

--
-- Dumping data for table `group_members`
--


INSERT INTO group_members (id, `user`, `group`) VALUES (1,2,2);
INSERT INTO group_members (id, `user`, `group`) VALUES (2,2,3);
INSERT INTO group_members (id, `user`, `group`) VALUES (3,2,4);
INSERT INTO group_members (id, `user`, `group`) VALUES (4,1,1);

--
-- Table structure for table `groups`
--

CREATE TABLE groups (
  id int(11) NOT NULL auto_increment PRIMARY KEY,
  `group` varchar(10) default NULL,
  description varchar(255) default NULL,
  UNIQUE KEY `group` (`group`)
) TYPE=MyISAM;

--
-- Dumping data for table `groups`
--


INSERT INTO groups (id, `group`, description) VALUES (1,'root','Super-Administratoren');
INSERT INTO groups (id, `group`, description) VALUES (2,'admin','Administratoren');
INSERT INTO groups (id, `group`, description) VALUES (3,'user','Benutzer');
INSERT INTO groups (id, `group`, description) VALUES (4,'guest','Gäste');

--
-- Table structure for table `users`
--

CREATE TABLE users (
  id int(11) NOT NULL auto_increment,
  `user` varchar(10) default NULL,
  passwd varchar(35) default NULL,
  name varchar(50) default NULL,
  email varchar(50) default NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY user (user)
) TYPE=MyISAM;

--
-- Dumping data for table `users`
--


INSERT INTO users (id, `user`, passwd, name, email) VALUES (1,'root','63a9f0ea7bb98050796b649e85481845','root','root@localhost');
INSERT INTO users (id, `user`, passwd, name, email) VALUES (2,'admin','21232f297a57a5a743894a0e4a801fc3','Administrator','admin@localhost');

