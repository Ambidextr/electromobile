<?php
require 'config/connect.php';
require 'config/config.php';

mysql_connect ( DB_LOCATION, DB_USER, DB_PASSWORD ) or die( 'Сервер базы данных недоступен' );
mysql_query( 'SET NAMES utf8' );
mysql_query("SET CHARACTER SET 'utf8'");
mysql_select_db ( DB_NAME ) or die( 'В настоящий момент база данных недоступна' );

// Создаем таблицу TABLE_USERS
$query = "CREATE TABLE IF NOT EXISTS `".TABLE_USERS."` (
  `id_author` int(6) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '',
  `passw` varchar(255) NOT NULL default '',
  `email` varchar(64) NOT NULL default '',
  `timezone` tinyint(2) NOT NULL default '0',
  `url` varchar(64) NOT NULL default '',
  `icq` varchar(12) NOT NULL default '',
  `about` tinytext NOT NULL,
  `signature` tinytext NOT NULL,
  `photo` varchar(32) NOT NULL default '',
  `puttime` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_visit` datetime NOT NULL default '0000-00-00 00:00:00',
  `themes` mediumint(8) unsigned NOT NULL default '0',
  `posts` int(10) unsigned NOT NULL default '0',
  `status` enum('user','moderator','admin') NOT NULL default 'user',
  `locked` tinyint(1) NOT NULL default '0',
  `activation` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id_author`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
mysql_query( $query );
$query = "TRUNCATE TABLE `".TABLE_USERS."`;";
mysql_query( $query );
// Добавляем пользователя
$query = "INSERT INTO `".TABLE_USERS."` (`id_author`, `name`, `passw`, `email`, `timezone`, `url`, `icq`, `about`, `signature`, `photo`, `puttime`, `last_visit`, `themes`, `posts`, `status`, `locked`, `activation`) VALUES (1, 'admin', '".md5('admin')."', 'admin@mail.ru', 0, 'http://webmasterschool.ru', '', '', '', '', NOW(), NOW(), 0, 0, 'admin', 0, '');";
mysql_query( $query );

// Создаем таблицу TABLE_FORUMS
$query = "CREATE TABLE IF NOT EXISTS `".TABLE_FORUMS."` (
  `id_forum` int(6) NOT NULL auto_increment,
  `name` text NOT NULL,
  `description` mediumtext NOT NULL,
  `pos` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id_forum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
mysql_query( $query );
$query = "TRUNCATE TABLE `".TABLE_FORUMS."`;";
mysql_query( $query );
// Добавляем несколко форумов
$query = "INSERT INTO `".TABLE_FORUMS."` (`id_forum`, `name`, `description`, `pos`) VALUES
(1, 'PHP', 'Обсуждаем вопросы программирования на PHP', 1),
(2, 'MySQL', 'Работа с базой данных MySQL средствами PHP', 2),
(3, 'Регулярные выражения', 'Использование регулярных выражений', 3),
(4, 'HTML и CSS', 'Верстка HTML и каскадные таблицы стилей', 4),
(5, 'JavaScript и AJAX', 'Программирование на JavaScript и технология AJAX', 5),
(6, 'Разное', 'Обсуждается работа форума и все, что не связано с PHP, MySQL, HTML, CSS', 6);";
mysql_query( $query );

// Создаем таблицу TABLE_MESSAGES
$query = "CREATE TABLE IF NOT EXISTS `".TABLE_MESSAGES."` (
  `id_msg` int(10) unsigned NOT NULL auto_increment,
  `to_user` int(10) unsigned NOT NULL default '0',
  `from_user` int(10) unsigned NOT NULL default '0',
  `sendtime` datetime NOT NULL default '0000-00-00 00:00:00',
  `subject` varchar(255) NOT NULL default '',
  `message` text NOT NULL,
  `id_rmv` int(10) unsigned NOT NULL default '0',
  `viewed` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id_msg`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
mysql_query( $query );
$query = "TRUNCATE TABLE `".TABLE_MESSAGES."`;";
mysql_query( $query );


// Создаем таблицу TABLE_THEMES
$query = "CREATE TABLE IF NOT EXISTS `".TABLE_THEMES."` (
  `id_theme` int(11) NOT NULL auto_increment,
  `name` tinytext NOT NULL,
  `author` tinytext NOT NULL,
  `id_author` int(6) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `id_last_author` int(6) NOT NULL default '0',
  `last_author` varchar(32) NOT NULL,
  `last_post` datetime NOT NULL,
  `id_forum` int(2) NOT NULL default '0',
  `locked` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_theme`),
  FULLTEXT KEY `search` (`name`,`author`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
mysql_query( $query );
$query = "TRUNCATE TABLE `".TABLE_THEMES."`;";
mysql_query( $query );

// Создаем таблицу TABLE_POSTS
$query = "CREATE TABLE IF NOT EXISTS `".TABLE_POSTS."` (
  `id_post` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `putfile` tinytext NOT NULL,
  `author` tinytext NOT NULL,
  `id_author` int(6) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `edittime` datetime NOT NULL default '0000-00-00 00:00:00',
  `id_editor` int(6) unsigned NOT NULL default '0',
  `id_theme` int(11) NOT NULL default '0',
  `locked` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_post`),
  FULLTEXT KEY `search` (`name`,`author`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
mysql_query( $query );
$query = "TRUNCATE TABLE `".TABLE_POSTS."`;";
mysql_query( $query );

echo 'ОК';
?>