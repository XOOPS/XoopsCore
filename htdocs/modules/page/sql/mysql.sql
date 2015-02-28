CREATE TABLE `page_content` (
  `content_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content_title` varchar(255) NOT NULL DEFAULT '',
  `content_shorttext` text NOT NULL,
  `content_text` text NOT NULL,
  `content_create` int(10) NOT NULL DEFAULT '0',
  `content_author` int(11) NOT NULL DEFAULT '0',
  `content_status` tinyint(1) NOT NULL DEFAULT '0',
  `content_hits` int(11) unsigned NOT NULL DEFAULT '0',
  `content_rating` double(6,4) NOT NULL DEFAULT '0.0000',
  `content_votes` int(11) unsigned NOT NULL DEFAULT '0',
  `content_comments` int(11) unsigned NOT NULL DEFAULT '0',
  `content_mkeyword` text NOT NULL,
  `content_mdescription` text NOT NULL,
  `content_maindisplay` tinyint(1) NOT NULL DEFAULT '0',
  `content_weight` int(5) NOT NULL DEFAULT '0',
  `content_dopdf` tinyint(1) NOT NULL DEFAULT '0',
  `content_doprint` tinyint(1) NOT NULL DEFAULT '0',
  `content_dosocial` tinyint(1) NOT NULL DEFAULT '0',
  `content_doinfo` tinyint(1) NOT NULL DEFAULT '0',
  `content_doauthor` tinyint(1) NOT NULL DEFAULT '0',
  `content_dodate` tinyint(1) NOT NULL DEFAULT '0',
  `content_domail` tinyint(1) NOT NULL DEFAULT '0',
  `content_dohits` tinyint(1) NOT NULL DEFAULT '0',
  `content_dorating` tinyint(1) NOT NULL DEFAULT '0',
  `content_docoms` tinyint(1) NOT NULL DEFAULT '0',
  `content_doncoms` tinyint(1) NOT NULL DEFAULT '0',
  `content_dotitle` tinyint(1) NOT NULL DEFAULT '0',
  `content_donotifications` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`content_id`),
  KEY `content_title` (`content_title`(250)),
  KEY `content_create` (`content_create`),
  KEY `content_author` (`content_author`),
  KEY `content_status` (`content_status`),
  KEY `content_hits` (`content_hits`),
  KEY `content_rating` (`content_rating`)
) ENGINE=MyISAM;

CREATE TABLE `page_rating` (
  `rating_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rating_content_id` int(10) unsigned DEFAULT NULL,
  `rating_uid` int(10) unsigned DEFAULT NULL,
  `rating_rating` int(2) DEFAULT NULL,
  `rating_ip` varchar(60) NOT NULL DEFAULT '',
  `rating_date` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`rating_id`),
  KEY `rating_content_id` (`rating_content_id`),
  KEY `rating_uid` (`rating_uid`)
) ENGINE=MyISAM;

CREATE TABLE `page_related` (
  `related_id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `related_name` varchar(255) NOT NULL DEFAULT '',
  `related_domenu` tinyint(1) NOT NULL DEFAULT '0',
  `related_navigation` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`related_id`),
  KEY `related_name` (`related_name`(40))
) ENGINE=MyISAM;

CREATE TABLE `page_related_link` (
  `link_id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `link_related_id` int(5) unsigned NOT NULL DEFAULT '0',
  `link_content_id` int(5) unsigned NOT NULL DEFAULT '0',
  `link_weight` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`link_id`),
  KEY `link_related_id` (`link_related_id`),
  KEY `link_content_id` (`link_content_id`)
) ENGINE=MyISAM;
