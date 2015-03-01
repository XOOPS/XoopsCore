#
# Table structure for table notifications
#
CREATE TABLE notifications (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  modid smallint(5) unsigned NOT NULL DEFAULT '0',
  itemid mediumint(8) unsigned NOT NULL DEFAULT '0',
  category varchar(30) NOT NULL DEFAULT '',
  event varchar(30) NOT NULL DEFAULT '',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  mode tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY modid (modid),
  KEY itemid (itemid),
  KEY class (category),
  KEY uid (uid),
  KEY event (event)
) ENGINE=MyISAM;