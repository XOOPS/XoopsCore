#
# Table structure for table `plugins_plugin`
#

CREATE TABLE plugins_plugin (
  plugin_id smallint(5) unsigned NOT NULL auto_increment,
  plugin_caller varchar(255) NOT NULL default '',
  plugin_listener varchar(255) NOT NULL default '',
  plugin_status tinyint(1) NOT NULL default '1',
  plugin_order smallint(1) NOT NULL default '0',
  PRIMARY KEY (plugin_id),
  KEY idxcaller (plugin_caller),
  KEY idxlistener (plugin_listener),
  KEY idxevent (plugin_status)
) ENGINE=MyISAM;
