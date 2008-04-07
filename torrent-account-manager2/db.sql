-- phpMyAdmin SQL Dump
-- version 2.10.0.2
-- http://www.phpmyadmin.net
-- 
-- Hoszt: localhost
-- Létrehozás ideje: 2008. Ápr 22. 21:41
-- Szerver verzió: 5.0.27
-- PHP Verzió: 4.3.11RC1-dev

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Adatbázis: `vector`
-- 

-- --------------------------------------------------------

-- 
-- Tábla szerkezet: `tam_users`
-- 

CREATE TABLE `tam_users` (
  `uid` int(10) unsigned NOT NULL auto_increment,
  `username` tinytext collate latin2_hungarian_ci NOT NULL,
  `password` tinytext collate latin2_hungarian_ci NOT NULL,
  `email` tinytext collate latin2_hungarian_ci NOT NULL,
  `notif` tinytext collate latin2_hungarian_ci NOT NULL,
  `status` int(2) unsigned NOT NULL default '1',
  `verif` tinytext collate latin2_hungarian_ci NOT NULL,
  `rights` tinytext collate latin2_hungarian_ci NOT NULL,
  `secret` tinytext collate latin2_hungarian_ci NOT NULL,
  `own_acc` mediumtext collate latin2_hungarian_ci NOT NULL,
  `accounts` mediumtext collate latin2_hungarian_ci NOT NULL,
  `passkeys` mediumtext collate latin2_hungarian_ci NOT NULL,
  `inviter` int(10) unsigned NOT NULL default '0',
  `reg_time` int(10) unsigned NOT NULL default '0',
  `last_login` int(10) unsigned NOT NULL default '0',
  `last_ips` tinytext collate latin2_hungarian_ci NOT NULL,
  `admin_comm` mediumtext collate latin2_hungarian_ci NOT NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin2 COLLATE=latin2_hungarian_ci AUTO_INCREMENT=100 ;

-- --------------------------------------------------------

-- 
-- Tábla szerkezet: `tam_logs`
-- 

CREATE TABLE `tam_logs` (
  `lid` int(10) unsigned NOT NULL auto_increment,
  `type` int(2) unsigned NOT NULL,
  `class` int(2) unsigned NOT NULL,
  `message` mediumtext collate latin2_hungarian_ci NOT NULL,
  `status` int(2) unsigned NOT NULL,
  `log_uid` int(10) unsigned NOT NULL,
  `log_time` int(10) unsigned NOT NULL,
  `log_ip` tinytext collate latin2_hungarian_ci NOT NULL,
  `admin_comm` mediumtext collate latin2_hungarian_ci NOT NULL,
  PRIMARY KEY  (`lid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin2 COLLATE=latin2_hungarian_ci AUTO_INCREMENT=2000 ;


