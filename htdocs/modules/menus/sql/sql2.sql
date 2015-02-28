CREATE TABLE menus_config (
  id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  skin_id smallint(5) unsigned NOT NULL DEFAULT '0',
  name varchar(25) NOT NULL DEFAULT '',
  title varchar(255) NOT NULL DEFAULT '',
  value text,
  desc varchar(255) NOT NULL DEFAULT '',
  formtype varchar(15) NOT NULL DEFAULT '',
  valuetype varchar(10) NOT NULL DEFAULT '',
  corder smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY order (order)
) ENGINE=MyISAM;

CREATE TABLE menus_configoption (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  config_id smallint(5) unsigned NOT NULL DEFAULT '0',
  name varchar(255) NOT NULL DEFAULT '',
  value varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id),
  KEY config_id (config_id)
) ENGINE=MyISAM;