#
# Table structure for table comments
#
CREATE TABLE comments (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  pid mediumint(8) unsigned NOT NULL DEFAULT '0',
  rootid mediumint(8) unsigned NOT NULL DEFAULT '0',
  modid smallint(5) unsigned NOT NULL DEFAULT '0',
  itemid mediumint(8) unsigned NOT NULL DEFAULT '0',
  icon varchar(25) NOT NULL DEFAULT '',
  created int(10) unsigned NOT NULL DEFAULT '0',
  modified int(10) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  ip varchar(15) NOT NULL DEFAULT '',
  title varchar(255) NOT NULL DEFAULT '',
  text text,
  sig tinyint(1) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) unsigned NOT NULL DEFAULT '0',
  exparams varchar(255) NOT NULL DEFAULT '',
  dohtml tinyint(1) unsigned NOT NULL DEFAULT '0',
  dosmiley tinyint(1) unsigned NOT NULL DEFAULT '0',
  doxcode tinyint(1) unsigned NOT NULL DEFAULT '0',
  doimage tinyint(1) unsigned NOT NULL DEFAULT '0',
  dobr tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY pid (pid),
  KEY itemid (itemid),
  KEY uid (uid),
  KEY title (title(40)),
  KEY status (status)
) ENGINE=MyISAM;