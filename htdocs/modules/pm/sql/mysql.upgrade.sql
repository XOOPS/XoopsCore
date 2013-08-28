ALTER TABLE `priv_msgs`
  
  ADD `from_delete` tinyint(1) unsigned     NOT NULL default '1',
  ADD `from_save`   tinyint(1) unsigned     NOT NULL default '0',
  ADD `to_delete`   tinyint(1) unsigned     NOT NULL default '0',
  ADD `to_save`     tinyint(1) unsigned     NOT NULL default '0',
  
  ADD INDEX `prune` (`msg_time`, `read_msg`, `from_save`, `to_delete`);