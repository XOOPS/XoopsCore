#
# Table structure for table `ranks`
#

CREATE TABLE ranks (
  rank_id smallint(5) unsigned NOT NULL auto_increment,
  rank_title varchar(50) NOT NULL default '',
  rank_min mediumint(8) unsigned NOT NULL default '0',
  rank_max mediumint(8) unsigned NOT NULL default '0',
  rank_special tinyint(1) unsigned NOT NULL default '0',
  rank_image varchar(255) default NULL,
  PRIMARY KEY  (rank_id),
  KEY rank_min (rank_min),
  KEY rank_max (rank_max),
  KEY rankminrankmaxranspecial (rank_min,rank_max,rank_special),
  KEY rankspecial (rank_special)
) ENGINE=MyISAM;
# --------------------------------------------------------

INSERT INTO ranks VALUES (1, 'Just popping in', 0, 20, 0, 'ranks/rank3e632f95e81ca.gif');
INSERT INTO ranks VALUES (2, 'Not too shy to talk', 21, 40, 0, 'ranks/rank3dbf8e94a6f72.gif');
INSERT INTO ranks VALUES (3, 'Quite a regular', 41, 70, 0, 'ranks/rank3dbf8e9e7d88d.gif');
INSERT INTO ranks VALUES (4, 'Just can\'t stay away', 71, 150, 0, 'ranks/rank3dbf8ea81e642.gif');
INSERT INTO ranks VALUES (5, 'Home away from home', 151, 10000, 0, 'ranks/rank3dbf8eb1a72e7.gif');
INSERT INTO ranks VALUES (6, 'Moderator', 0, 0, 1, 'ranks/rank3dbf8edf15093.gif');
INSERT INTO ranks VALUES (7, 'Webmaster', 0, 0, 1, 'ranks/rank3dbf8ee8681cd.gif');

