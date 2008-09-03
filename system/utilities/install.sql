CREATE TABLE `%DB_PREFIX%feeds` (
  `feed_id` int(11) NOT NULL auto_increment,
  `feed_title` text collate utf8_unicode_ci NOT NULL,
  `feed_icon` varchar(255) collate utf8_unicode_ci NOT NULL,
  `feed_url` text collate utf8_unicode_ci NOT NULL,
  `feed_data` longtext collate utf8_unicode_ci NOT NULL,
  `feed_status` varchar(20) collate utf8_unicode_ci NOT NULL default 'active',
  `feed_domain` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`feed_id`),
  KEY `feed_status` (`feed_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `%DB_PREFIX%items` (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `item_date` bigint(20) NOT NULL,
  `item_content` longtext NOT NULL,
  `item_title` text NOT NULL,
  `item_permalink` varchar(255) NOT NULL,
  `item_status` varchar(20) NOT NULL default 'publish',
  `item_name` varchar(200) NOT NULL default '',
  `item_parent` bigint(20) NOT NULL default '0',
  `item_data` longtext NOT NULL,
  `item_feed_id` int(11) NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `item_name` (`item_name`),
  KEY `type_status_date` (`item_status`,`item_date`,`ID`),
  FULLTEXT KEY `item_title` (`item_title`,`item_content`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `%DB_PREFIX%options` (
  `option_id` bigint(20) NOT NULL auto_increment,
  `option_name` varchar(64) NOT NULL default '',
  `option_value` longtext NOT NULL,
  `autoload` varchar(20) NOT NULL default 'yes',
  PRIMARY KEY  (`option_id`,`option_name`),
  KEY `option_name` (`option_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `%DB_PREFIX%tags` (
  `tag_id` bigint(20) NOT NULL auto_increment,
  `name` varchar(55) NOT NULL default '',
  `slug` varchar(200) NOT NULL default '',
  `count` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`tag_id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `%DB_PREFIX%tag_relationships` (
  `item_id` bigint(20) NOT NULL default '0',
  `tag_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`item_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `%DB_PREFIX%users` (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `user_login` varchar(60) NOT NULL default '',
  `user_pass` varchar(64) NOT NULL default '',
  `user_email` varchar(100) NOT NULL default '',
  `user_activation_key` varchar(60) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `user_login_key` (`user_login`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
