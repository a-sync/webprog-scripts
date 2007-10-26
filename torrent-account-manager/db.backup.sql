-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- Hoszt: vector.extra.sql
-- L�trehoz�s ideje: 2008. Jan 11. 14:47
-- Szerver verzi�: 5.0.45
-- PHP Verzi�: 5.2.1
-- 
-- -------8<-----------8<-----------8<-------
--                [ extra.hu ]               
--                 MySQL Dump                
-- -------8<-----------8<-----------8<-------
-- 
-- 

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Adatb�zis: `vector`
-- 
CREATE DATABASE `vector` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `vector`;

-- --------------------------------------------------------

-- 
-- T�bla szerkezet: `tam_accounts`
-- 
-- L�trehoz�s: 2007. Okt 28. 23:26
-- Utols� m�dos�t�s: 2007. Dec 20. 21:22
-- 

CREATE TABLE IF NOT EXISTS `tam_accounts` (
  `aid` int(11) unsigned NOT NULL auto_increment,
  `site_id` int(11) default NULL,
  `user` tinytext,
  `user_id` tinytext character set latin2 collate latin2_hungarian_ci,
  `password` tinytext character set latin2 collate latin2_hungarian_ci,
  `email` tinytext,
  `email_pass` tinytext character set latin2 collate latin2_hungarian_ci,
  `upload` double default '0',
  `download` double default '0',
  `passkey` varchar(32) default NULL,
  `tam_account` text,
  `tam_passkey` text,
  `tam_status` int(2) default '0',
  `comm` mediumtext character set latin2 collate latin2_hungarian_ci,
  `admin_comm` mediumtext character set latin2 collate latin2_hungarian_ci,
  PRIMARY KEY  (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=82 ;

-- 
-- T�bla adatok: `tam_accounts`
-- 

INSERT INTO `tam_accounts` (`aid`, `site_id`, `user`, `user_id`, `password`, `email`, `email_pass`, `upload`, `download`, `passkey`, `tam_account`, `tam_passkey`, `tam_status`, `comm`, `admin_comm`) VALUES 
(69, 7, '127', '442', '442', '442', '442', 442, 442, '442', '', '', 1, '', ''),
(78, 0, '', '', '', '', '', 0, 0, '11111111111111111111111111111111', '', '', 4, 'sima komemnt te''sz''t\r\n\r\nminden "extr�val"\r\n\r\n">', 'adminkomment\r\n\r\nteszt'),
(79, 7, '', '', '''dfa''+"1', '', '''dfa''+"1', 0, 0, '11111111111111111111111111111111', '', '', 0, 'sima komemnt te''sz''t\r\n\r\nminden "extr�val"\r\n\r\n">', 'adminkomment\r\n\r\nteszt'),
(80, 7, '442', '', '', '', '', 5928.96, 234.76, '22222222222222222222222222222222', '', '', 3, '', ''),
(81, 0, '', '', '', '', '', 0, 0, '32323332323233232323232323232332', '', '', 0, '', '');

-- --------------------------------------------------------

-- 
-- T�bla szerkezet: `tam_requests`
-- 
-- L�trehoz�s: 2007. Okt 28. 16:53
-- Utols� m�dos�t�s: 2007. Okt 28. 16:53
-- 

CREATE TABLE IF NOT EXISTS `tam_requests` (
  `rid` int(11) unsigned NOT NULL auto_increment,
  `class` int(2) default '0',
  `type` varchar(32) default NULL,
  `message` mediumtext character set latin2 collate latin2_hungarian_ci,
  `req_uid` int(11) default NULL,
  `date` datetime default NULL,
  `status` int(2) default '1',
  `comm` mediumtext character set latin2 collate latin2_hungarian_ci,
  `admin_comm` mediumtext character set latin2 collate latin2_hungarian_ci,
  PRIMARY KEY  (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- T�bla adatok: `tam_requests`
-- 


-- --------------------------------------------------------

-- 
-- T�bla szerkezet: `tam_sites`
-- 
-- L�trehoz�s: 2007. Okt 27. 11:20
-- Utols� m�dos�t�s: 2007. Dec 18. 19:43
-- Utols� ellen&#337;rz�s: 2007. Okt 30. 17:54
-- 

CREATE TABLE IF NOT EXISTS `tam_sites` (
  `sid` int(11) unsigned NOT NULL auto_increment,
  `name` tinytext character set latin2 collate latin2_hungarian_ci,
  `domains` text,
  `passkey_link` tinytext,
  `user_link` tinytext,
  `dindata_link` tinytext,
  `restrict_calc` mediumtext character set latin2 collate latin2_hungarian_ci,
  `comm` mediumtext character set latin2 collate latin2_hungarian_ci,
  `admin_comm` mediumtext character set latin2 collate latin2_hungarian_ci,
  PRIMARY KEY  (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- 
-- T�bla adatok: `tam_sites`
-- 

INSERT INTO `tam_sites` (`sid`, `name`, `domains`, `passkey_link`, `user_link`, `dindata_link`, `restrict_calc`, `comm`, `admin_comm`) VALUES 
(0, 'bitHUmen', 'http://bithumen.ath.cx|http://bithumen.1.vg|http://bithumen.mine.nu', '#domain#:11337/announce/#passkey#/announce', '#domain#/userdetails.php?id=#user_id#', '#domain#/getuserdata.php?id=#user_id#', '', '', ''),
(7, 'tesztoldal', 'oldaldomain.1.vg', '', '', '', '', 'pr�ba komm', 'pr�ba admin komm'),
(8, 'pr�ba', 'http://valahol.hu|http://alma.fa.hu:1234', '', '', '', '', '', ''),
(9, 'pr�ba2', 'http://pr�bac�m2.vg', '', '', '', 'if($download > 1 && $upload > 1) {\r\n  if($ratio > 1) {\r\n    echo ''Nincs v�rakoz�s!'';\r\n  }\r\n  elseif($ratio > 0.5) {\r\n    echo ''8 �ra v�rakoz�s!<br> 10 slot!'';\r\n  }\r\n  elseif($ratio > 0.3) {\r\n    echo ''16 �ra v�rakoz�s!<br> 8 slot!'';\r\n  }\r\n  elseif($ratio > 0.2) {\r\n    echo ''24 �ra v�rakoz�s!<br> 5 slot!'';\r\n  }\r\n  elseif($ratio > 0.1) {\r\n    echo ''32 �ra v�rakoz�s!<br> 3 slot!'';\r\n  }\r\n}', '', 'pr�ba: megszor�t�s kalkul�torral...'),
(12, 'nCore', 'http://ncore.hu', '', '', '', '', '', '');

-- --------------------------------------------------------

-- 
-- T�bla szerkezet: `tam_users`
-- 
-- L�trehoz�s: 2007. Nov 06. 19:41
-- Utols� m�dos�t�s: 2008. Jan 11. 14:26
-- 

CREATE TABLE IF NOT EXISTS `tam_users` (
  `uid` int(11) unsigned NOT NULL auto_increment,
  `tam_user` varchar(32) character set latin2 collate latin2_hungarian_ci default NULL,
  `tam_pass` varchar(32) character set latin2 collate latin2_hungarian_ci default NULL,
  `tam_email` tinytext,
  `tam_accounts` text,
  `tam_passkeys` text,
  `tam_class` int(2) default '0',
  `accounts_sites` text,
  `notif` int(2) default '0',
  `verif` varchar(32) character set latin2 collate latin2_hungarian_ci default NULL,
  `reg_time` datetime default NULL,
  `last_ip` varchar(15) default NULL,
  `last_login` datetime default NULL,
  `admin_comm` mediumtext character set latin2 collate latin2_hungarian_ci,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

-- 
-- T�bla adatok: `tam_users`
-- 

INSERT INTO `tam_users` (`uid`, `tam_user`, `tam_pass`, `tam_email`, `tam_accounts`, `tam_passkeys`, `tam_class`, `accounts_sites`, `notif`, `verif`, `reg_time`, `last_ip`, `last_login`, `admin_comm`) VALUES 
(16, 'Vector', '038aae521db4ca64b1c6b6782c054dc1', 'dick.of.the.year@gmail.com', '', '', 3, '0|12', 7, '', '2007-10-09 20:03:38', '80.99.242.93', '2008-01-11 13:59:31', 'rendszergizda\r\ngorillaz\r\n'),
(18, 'alma', '0c672a4007929d3f24bc494a402d28dd', 'Alma12@Alma12.hu', '', '', 0, '7|9|12', 5, '', '2007-10-10 19:40:09', '80.99.242.93', '2007-10-10 19:40:26', ''),
(19, 'Alma123', '4de471403f1bd3b7934a21503ef46a01', 'a@b.hu', '', '', 1, NULL, 4, '', '2007-10-10 19:42:10', '', '0000-00-00 00:00:00', ''),
(30, '', '', '', '', '', 0, '', 0, '9fdc77abc49143f979b06c6d8afb7007', '2007-12-18 19:55:41', '', '0000-00-00 00:00:00', ''),
(31, 'kortE123', '545b862ff1da3e381267c3be98c769d1', '', '', '', 0, '8|7', 1, '', '2007-12-20 23:09:13', '80.99.242.93', '2007-12-21 02:13:03', '');
