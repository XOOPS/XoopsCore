#
# Table structure for table `banners_banner`
#

CREATE TABLE banners_banner (
  banner_bid smallint(5) unsigned NOT NULL auto_increment,
  banner_cid tinyint(3) unsigned NOT NULL default '0',
  banner_imptotal int(10) unsigned NOT NULL default '0',
  banner_impmade mediumint(8) unsigned NOT NULL default '0',
  banner_clicks mediumint(8) unsigned NOT NULL default '0',
  banner_imageurl varchar(255) NOT NULL default '',
  banner_clickurl varchar(255) NOT NULL default '',
  banner_datestart int(10) NOT NULL default '0',
  banner_dateend int(10) NOT NULL default '0',
  banner_htmlbanner tinyint(1) NOT NULL default '0',
  banner_htmlcode text,
  banner_status tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (banner_bid),
  KEY idxbannercid (banner_cid),
  KEY idxbannerbidcid (banner_bid,banner_cid)
) ENGINE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `banners_bannerclient`
#

CREATE TABLE banners_bannerclient (
  bannerclient_cid smallint(5) unsigned NOT NULL auto_increment,
  bannerclient_uid mediumint(8) unsigned NOT NULL default '0',
  bannerclient_name varchar(60) NOT NULL default '',
  bannerclient_extrainfo text,
  PRIMARY KEY  (bannerclient_cid),
  KEY name (bannerclient_name)
) ENGINE=MyISAM;