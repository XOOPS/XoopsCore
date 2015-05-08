CREATE TABLE `pm_messages` (
  `msg_id`      int(10) unsigned        NOT NULL auto_increment,
  `msg_image`   varchar(255)            default NULL,
  `subject`     varchar(255)            NOT NULL default '',
  `from_userid` mediumint(8) unsigned   NOT NULL default '0',
  `to_userid`   mediumint(8) unsigned   NOT NULL default '0',
  `msg_time`    int(10) unsigned        NOT NULL default '0',
  `msg_text`    text,  
  `read_msg`    tinyint(1) unsigned     NOT NULL default '0',
  
  `from_delete` tinyint(1) unsigned     NOT NULL default '1',
  `from_save`   tinyint(1) unsigned     NOT NULL default '0',
  `to_delete`   tinyint(1) unsigned     NOT NULL default '0',
  `to_save`     tinyint(1) unsigned     NOT NULL default '0',
  
  PRIMARY KEY  (`msg_id`),
  KEY to_userid (`to_userid`),
  KEY inbox (`to_userid`,`read_msg`),
  KEY outbox (`from_userid`, `read_msg`),
  KEY prune (`msg_time`, `read_msg`, `from_save`, `to_delete`)
) ENGINE=MyISAM;