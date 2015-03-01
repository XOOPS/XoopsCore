#
# Table structure for table `cache_model`
#

CREATE TABLE cache_model (
  `cache_key`     varchar(64)     NOT NULL default '',
  `cache_expires` int(10)         unsigned NOT NULL default '0',
  `cache_data`    text,
  
  PRIMARY KEY  (`cache_key`),
  KEY `cache_expires` (`cache_expires`)
) ENGINE=MyISAM;
# --------------------------------------------------------
