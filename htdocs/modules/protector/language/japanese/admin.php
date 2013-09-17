<?php

// mymenu
define('_MD_A_MYMENU_MYTPLSADMIN','');
define('_MD_A_MYMENU_MYBLOCKSADMIN','������������');
define('_MD_A_MYMENU_MYPREFERENCES','��������');

// index.php
define("_AM_TH_DATETIME","����");
define("_AM_TH_USER","�桼��");
define("_AM_TH_IP","IP");
define("_AM_TH_AGENT","AGENT");
define("_AM_TH_TYPE","����");
define("_AM_TH_DESCRIPTION","�ܺ�");

define("_AM_TH_BADIPS" , '����IP�ꥹ��<br /><br /><span style="font-weight:normal;">���ԣ�IP���ɥ쥹�ǵ��Ҥ��Ƥ����������������סˡ�����ʤ������ġ�</span>' ) ;

define("_AM_TH_GROUP1IPS" , '�����ԥ��롼��(1)�ε���IP<br /><br /><span style="font-weight:normal;">���ԣ�IP���ɥ쥹�ǵ��Ҥ��Ƥ����������������סˡ�<br />192.168. �Ȥ���С�192.168.*����Τߴ����Ԥˤʤ�ޤ�������ʤ������ġ�</span>' ) ;

define("_AM_LABEL_COMPACTLOG" , "����򥳥�ѥ��Ȳ�����" ) ;
define("_AM_BUTTON_COMPACTLOG" , "����ѥ��Ȳ��¹�" ) ;
define("_AM_JS_COMPACTLOGCONFIRM" , "IP�ȼ��̤ν�ʣ�����쥳���ɤ������ޤ�" ) ;
define("_AM_LABEL_REMOVEALL" , "���쥳���ɤ�������:" ) ;
define("_AM_BUTTON_REMOVEALL" , "������¹�" ) ;
define("_AM_JS_REMOVEALLCONFIRM" , "�����̵���Ǻ�����ޤ��������ˤ������Ǥ�����" ) ;
define("_AM_LABEL_REMOVE" , "�����å������쥳���ɤ�������:" ) ;
define("_AM_BUTTON_REMOVE" , "����¹�" ) ;
define("_AM_JS_REMOVECONFIRM" , "�����˺�����Ƥ������Ǥ�����" ) ;
define("_AM_MSG_IPFILESUPDATED" , "IP�ꥹ�ȥե������񤭴����ޤ���" ) ;
define("_AM_MSG_BADIPSCANTOPEN" , "����IP�ꥹ�ȥե����뤬�����ޤ���" ) ;
define("_AM_MSG_GROUP1IPSCANTOPEN" , "��������IP�ꥹ�ȥե����뤬�����ޤ���" ) ;
define("_AM_MSG_REMOVED" , "������ޤ���" ) ;
//define("_AM_FMT_CONFIGSNOTWRITABLE" , "configs�ǥ��쥯�ȥ꤬������Ĥ���Ƥ��ޤ���: %s" ) ;

// prefix_manager.php
define("_AM_H3_PREFIXMAN" , "PREFIX �ޥ͡�����" ) ;
define("_AM_MSG_DBUPDATED" , "�ǡ����١�������������ޤ���" ) ;
define("_AM_CONFIRM_DELETE" , "���ơ��֥뤬�������ޤ����������Ǥ���?" ) ;
define("_AM_TXT_HOWTOCHANGEDB" , "prefix���ѹ�������ϡ�%s/mainfile.php ��ΰʲ�����ʬ��񤭴����Ƥ�������<br /><br />define('XOOPS_DB_PREFIX','<b>%s</b>');" ) ;

// advisory.php
define("_AM_ADV_NOTSECURE","��侩");

define("_AM_ADV_TRUSTPATHPUBLIC","���NG�Ȥ���������ɽ������Ƥ����ꡢ�����ǥ��顼���Фʤ��褦�ʤ�XOOPS_TRUST_PATH��������ˡ�����꤬����ޤ���XOOPS_TRUST_PATH��DocumentRoot�������֤���Τ����ܤǤ����������Ǥ��ʤ����Ǥ�XOOPS_TRUST_PATHľ����DENY FROM ALL�ΰ�Ԥ����.htaccess���ɲä���ʤɤ��ơ�XOOPS_TRUST_PATH���ľ�ܥ��������Ǥ��ʤ��褦�ˤ���ɬ�פ�����ޤ���");
define("_AM_ADV_TRUSTPATHPUBLICLINK","TRUST_PATH���PHP�ե������ľ���������Ǥ��ʤ����Ȥγ�ǧ�ʥ���褬404,403,500���顼�ʤ������");
define("_AM_ADV_REGISTERGLOBALS","��������ϡ��͡����ѿ��������򾷤��ޤ�<br />�⤷��.htaccess���֤��륵���ФǤ���С�XOOPS���󥹥ȡ���ǥ��쥯�ȥ��.htaccess���뤫�Խ����뤫���Ʋ�����");
define("_AM_ADV_ALLOWURLFOPEN","����������ȡ������Ǥ�դΥ�����ץȤ�¹Ԥ��������������ޤ�<br />���������ѹ��ˤϥ����Фδ����Ը��¤�ɬ�פǤ�<br />�����ȤǴ������Ƥ��륵���ФǤ���С�php.ini��httpd.conf���Խ����Ʋ�����<br />�����Ǥʤ����ϡ������д����Ԥˤ��ꤤ���ƤߤƲ�����");
define("_AM_ADV_USETRANSSID","���å����ID����ưŪ�˥�󥯤�ɽ�����������ȤʤäƤ��ޤ���<br />���å����ϥ�����å����ɤ�����ˤ⡢XOOPS���󥹥ȡ���ǥ��쥯�ȥ��.htaccess���뤫�Խ����뤫���Ʋ�����<br /><b>php_flag session.use_trans_sid off</b>");
define("_AM_ADV_DBPREFIX","DB��Ƭ�����ǥե���Ȥ�xoops�ΤޤޤʤΤǡ�SQL Injection�˼夤���֤Ǥ�<br />�ָ�Ω�����Ȥ�̵�����פʤɡ�SQL Injection�к��������ON�ˤ��뤳�Ȥ�˺��ʤ�");
define("_AM_ADV_LINK_TO_PREFIXMAN","PREFIX�ޥ͡������");
define("_AM_ADV_MAINUNPATCHED","README�˵��Ҥ��줿�̤�ˡ�mainfile.php �˥ѥå������ƤƲ�����");
define("_AM_ADV_DBFACTORYPATCHED","�ǡ����١����ե����ȥ���б��ѤߤǤ�");
define("_AM_ADV_DBFACTORYUNPATCHED","�ǡ����١����ե����ȥꥯ�饹�ؤΥѥå��������äƤ��ʤ��Τ�DB�쥤�䡼�ȥ�å�anti-SQL-Injection�ϸ����ޤ���");

define("_AM_ADV_SUBTITLECHECK","Protector��ư������å�");
define("_AM_ADV_CHECKCONTAMI","�ѿ����");
define("_AM_ADV_CHECKISOCOM","��Ω������");
