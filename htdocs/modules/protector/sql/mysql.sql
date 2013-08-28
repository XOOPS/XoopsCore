CREATE TABLE protector_log (
  lid mediumint(8) unsigned NOT NULL auto_increment,
  uid mediumint(8) unsigned NOT NULL default 0,
  ip varchar(255) NOT NULL default '0.0.0.0',
  type varchar(255) NOT NULL default '',
  agent varchar(255) NOT NULL default '',
  description text,
  extra text,
  timestamp DATETIME,
  PRIMARY KEY (lid) ,
  KEY (uid) ,
  KEY (ip) ,
  KEY (type) ,
  KEY (timestamp)
) ENGINE=MyISAM;

CREATE TABLE protector_access (
  ip varchar(255) NOT NULL default '0.0.0.0',
  request_uri varchar(255) NOT NULL default '',
  malicious_actions varchar(255) NOT NULL default '',
  expire int NOT NULL default 0,
  KEY (ip),
  KEY (request_uri),
  KEY (malicious_actions),
  KEY (expire)
) ENGINE=MyISAM;
