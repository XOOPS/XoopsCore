#
# Table structure for table `smilies`
#

CREATE TABLE smilies (
  smiley_id smallint(5) unsigned NOT NULL auto_increment,
  smiley_code varchar(50) NOT NULL default '',
  smiley_url varchar(100) NOT NULL default 'blank.gif',
  smiley_emotion varchar(75) NOT NULL default '',
  smiley_display tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (smiley_id)
) ENGINE=MyISAM;
# --------------------------------------------------------

#
# Dumping data for table `smilies`
#

INSERT INTO smilies VALUES (1, ':-D', 'smilies/smil3dbd4d4e4c4f2.gif', 'Very Happy', 1);
INSERT INTO smilies VALUES (2, ':-)', 'smilies/smil3dbd4d6422f04.gif', 'Smile', 1);
INSERT INTO smilies VALUES (3, ':-(', 'smilies/smil3dbd4d75edb5e.gif', 'Sad', 1);
INSERT INTO smilies VALUES (4, ':-o', 'smilies/smil3dbd4d8676346.gif', 'Surprised', 1);
INSERT INTO smilies VALUES (5, ':-?', 'smilies/smil3dbd4d99c6eaa.gif', 'Confused', 1);
INSERT INTO smilies VALUES (6, '8-)', 'smilies/smil3dbd4daabd491.gif', 'Cool', 1);
INSERT INTO smilies VALUES (7, ':lol:', 'smilies/smil3dbd4dbc14f3f.gif', 'Laughing', 1);
INSERT INTO smilies VALUES (8, ':-x', 'smilies/smil3dbd4dcd7b9f4.gif', 'Mad', 1);
INSERT INTO smilies VALUES (9, ':-P', 'smilies/smil3dbd4ddd6835f.gif', 'Razz', 1);
INSERT INTO smilies VALUES (10, ':oops:', 'smilies/smil3dbd4df1944ee.gif', 'Embaressed', 0);
INSERT INTO smilies VALUES (11, ':cry:', 'smilies/smil3dbd4e02c5440.gif', 'Crying (very sad)', 0);
INSERT INTO smilies VALUES (12, ':evil:', 'smilies/smil3dbd4e1748cc9.gif', 'Evil or Very Mad', 0);
INSERT INTO smilies VALUES (13, ':roll:', 'smilies/smil3dbd4e29bbcc7.gif', 'Rolling Eyes', 0);
INSERT INTO smilies VALUES (14, ';-)', 'smilies/smil3dbd4e398ff7b.gif', 'Wink', 0);
INSERT INTO smilies VALUES (15, ':pint:', 'smilies/smil3dbd4e4c2e742.gif', 'Another pint of beer', 0);
INSERT INTO smilies VALUES (16, ':hammer:', 'smilies/smil3dbd4e5e7563a.gif', 'ToolTimes at work', 0);
INSERT INTO smilies VALUES (17, ':idea:', 'smilies/smil3dbd4e7853679.gif', 'I have an idea', 0);
