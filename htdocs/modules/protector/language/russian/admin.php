<?php

// mymenu

// Appended by Xoops Language Checker -GIJOE- in 2009-01-14 11:10:53
define('_AM_ADV_DBFACTORYPATCHED','Your databasefactory is ready for DBLayer Trapping anti-SQL-Injection');
define('_AM_ADV_DBFACTORYUNPATCHED','Your databasefactory is not ready for DBLayer Trapping anti-SQL-Injection. Some patches are required.');

// Appended by Xoops Language Checker -GIJOE- in 2008-12-03 11:47:20
define('_AM_ADV_TRUSTPATHPUBLIC','If you can look an image -NG- or the link returns normal page, your XOOPS_TRUST_PATH is not placed properly. The best place for XOOPS_TRUST_PATH is outside of DocumentRoot. If you cannot do that, you have to put .htaccess (DENY FROM ALL) just under XOOPS_TRUST_PATH as the second best way.');
define('_AM_ADV_TRUSTPATHPUBLICLINK','Check php files inside TRUST_PATH are private (it must be 404,403 or 500 error');

// Appended by Xoops Language Checker -GIJOE- in 2007-10-18 05:36:25
define('_AM_LABEL_COMPACTLOG','Compact log');
define('_AM_BUTTON_COMPACTLOG','Compact it!');
define('_AM_JS_COMPACTLOGCONFIRM','Duplicated (IP,Type) records will be removed');
define('_AM_LABEL_REMOVEALL','Remove all records');
define('_AM_BUTTON_REMOVEALL','Remove all!');
define('_AM_JS_REMOVEALLCONFIRM','All logs are removed absolutely. Are you really OK?');

// Appended by Xoops Language Checker -GIJOE- in 2007-07-30 05:37:52
//define('_AM_FMT_CONFIGSNOTWRITABLE','Turn the configs directory writable: %s');

define('_MD_A_MYMENU_MYTPLSADMIN','');
define('_MD_A_MYMENU_MYBLOCKSADMIN','����� �������');
define('_MD_A_MYMENU_MYPREFERENCES','���������');

// index.php
define("_AM_TH_DATETIME","�����");
define("_AM_TH_USER","������������");
define("_AM_TH_IP","IP");
define("_AM_TH_AGENT","User-Agent");
define("_AM_TH_TYPE","���");
define("_AM_TH_DESCRIPTION","��������");

define("_AM_TH_BADIPS" , "����������� IP" ) ;

define("_AM_TH_GROUP1IPS" , '����������� IP ���  Group=1 (�������������)<br /><br /><span style="font-weight:normal;">������ ����������� IP �� ������ �� ������.<br />192.168. means 192.168.*<br />������������ ����� ���� ��������, ��� ��� IP ����� ������</span>' ) ;

//define("_AM_TH_ENABLEIPBANS" , "�������� �������� ����� �� IP?" ) ;
define("_AM_LABEL_REMOVE" , "������� ��������� ������:" ) ;
define("_AM_BUTTON_REMOVE" , "�������" ) ;
define("_AM_JS_REMOVECONFIRM" , "�������?" ) ;
define("_AM_MSG_IPFILESUPDATED" , "����� ��� IP ���� ���������" ) ;
define("_AM_MSG_BADIPSCANTOPEN" , "���� ��� ������� IP �� ����� ���� ������" ) ;
define("_AM_MSG_GROUP1IPSCANTOPEN" , "���� ��� ������� group=1 �� ����� ���� ������" ) ;
define("_AM_MSG_PRUPDATED" , "��������� ������� ���������!" ) ;
define("_AM_MSG_REMOVED" , "������ �������" ) ;

// prefix_manager.php
define("_AM_H3_PREFIXMAN" , "���������� ��������� ������" ) ;
define("_AM_MSG_DBUPDATED" , "���� ������ ������� ���������!" ) ;
define("_AM_CONFIRM_DELETE" , "��� ������ ����� ����������. ����������?" ) ;
define("_AM_TXT_HOWTOCHANGEDB" , "���� �� ������ �������� ������� ������, �������������� ��� ���������������� ���� %s/mainfile.php ������� ����� ��������� � ������ �������� ����.<br /><br />define('XOOPS_DB_PREFIX','<b>%s</b>');" ) ;

// advisory.php
define("_AM_ADV_NOTSECURE","���� �� �������");

define("_AM_ADV_REGISTERGLOBALS","������� ������������ ��������� ��������� ����� ������ ���� � ���������� �������� ����������.<br />���� �� ������ ������ � ����� .htaccess �������� ��� ��� �������������� ���� ���� ������� � ���� ������ �������� ����.");
define("_AM_ADV_ALLOWURLFOPEN","������� ������������ ��������� ��������� ��������� ������������ ������� �� ��������� �������.<br />������ ������������� ������� ����� �������� ��� �����.<br />���� �� ��������� ��������������� �������������� ���� php.ini ��� httpd.conf.<br /><b>������ ��� httpd.conf:<br /> &nbsp; php_admin_flag &nbsp; allow_url_fopen &nbsp; off</b><br />��� ��������� �� ���� ������ ��������������.");
define("_AM_ADV_USETRANSSID","ID ����� ������ ������������ � ����� ������ � ��.<br />
    �� ��������� ������������� ID ����� ������ �������� ������ �������� ��������� ������ � ��� ���� .htaccess ������������ � ��������: 'XOOPS_ROOT_PATH<br /><b>php_flag session.use_trans_sid off</b>");
define("_AM_ADV_DBPREFIX","������� �������� �������� ������ �� ��������� ��������� ����� �.�. 'SQL Injecting' ����.<br />�� �������� �������� '�������������� ������� ���������� *' � ���������������� ������� ����� ������.");
define("_AM_ADV_LINK_TO_PREFIXMAN","������� � ������� ���������� ���������� ��.");
define("_AM_ADV_MAINUNPATCHED","�� ������ ��������������� ��� ���� mainfile.php ���, ��� ��� ������� � README.");
//define("_AM_ADV_RESCUEPASSWORD","������ ��� ������ ���� � ������ IP");
//define("_AM_ADV_RESCUEPASSWORDUNSET","�� ����������");
//define("_AM_ADV_RESCUEPASSWORDSHORT","������� �������� (����������� ����� 6 ��������)");

define("_AM_ADV_SUBTITLECHECK","�������� �����������������");
//define("_AM_ADV_AT1STSETPASSWORD","���������� ��� ��������� ������ ����� ���������.");
define("_AM_ADV_CHECKCONTAMI","����� ����������");
define("_AM_ADV_CHECKISOCOM","������������ �����������");
