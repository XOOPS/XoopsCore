CREATE TABLE `xlanguage` (
  `xlanguage_id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `xlanguage_name` varchar(255) NOT NULL DEFAULT '',
  `xlanguage_description` varchar(255) NOT NULL,
  `xlanguage_code` varchar(255) NOT NULL DEFAULT '',
  `xlanguage_charset` varchar(255) NOT NULL DEFAULT '',
  `xlanguage_image` varchar(255) NOT NULL DEFAULT 'noflag.gif',
  `xlanguage_weight` smallint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`xlanguage_id`),
  KEY `xlanguage_name` (`xlanguage_name`),
  KEY `xlanguage_weight` (`xlanguage_weight`)
) ENGINE=MyISAM;