<?php

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) ) $mydirname = 'protector' ;
$constpref = '_MI_' . strtoupper( $mydirname ) ;

if ( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( '_MI_PROTECTOR_LOADED' ) ) {

// Appended by Xoops Language Checker -GIJOE- in 2009-07-06 05:46:53
define('_MI_PROTECTOR_DBTRAPWOSRV','Never checking _SERVER for anti-SQL-Injection');
define('_MI_PROTECTOR_DBTRAPWOSRVDSC','Some servers always enable DB Layer trapping. It causes wrong detections as SQL Injection attack. If you got such errors, turn this option on. You should know this option weakens the security of DB Layer trapping anti-SQL-Injection.');

// Appended by Xoops Language Checker -GIJOE- in 2009-01-14 11:10:53
define('_MI_PROTECTOR_DBLAYERTRAP','Enable DB Layer trapping anti-SQL-Injection');
define('_MI_PROTECTOR_DBLAYERTRAPDSC','Almost SQL Injection attacks will be canceled by this feature. This feature is required a support from databasefactory. You can check it on Security Advisory page.');

// Appended by Xoops Language Checker -GIJOE- in 2008-11-21 04:44:31
define('_MI_PROTECTOR_DEFAULT_LANG','Default language');
define('_MI_PROTECTOR_DEFAULT_LANGDSC','Specify the language set to display messages before processing common.php');
define('_MI_PROTECTOR_BWLIMIT_COUNT','Bandwidth limitation');
define('_MI_PROTECTOR_BWLIMIT_COUNTDSC','Specify the max access to mainfile.php during watching time. This value should be 0 for normal environments which have enough CPU bandwidth. The number fewer than 10 will be ignored.');

// Appended by Xoops Language Checker -GIJOE- in 2007-07-30 16:31:33
define('_MI_PROTECTOR_BANIP_TIME0','Banned IP suspension time (sec)');
define('_MI_PROTECTOR_OPT_BIPTIME0','Ban the IP (moratorium)');
define('_MI_PROTECTOR_DOSOPT_BIPTIME0','Ban the IP (moratorium)');

// Appended by Xoops Language Checker -GIJOE- in 2007-04-11 05:08:26
//define('_MI_PROTECTOR_ADMENU_MYBLOCKSADMIN','Permissions');

define('_MI_PROTECTOR_LOADED' , 1 ) ;

// The name of this module
define("_MI_PROTECTOR_NAME","����");

// A brief description of this module
define("_MI_PROTECTOR_DESC","���� ������ �������� ��� ���� �� ���� XOOPS �� ���������� ���� ����, ����� ���: DoS, SQL Injection � ����� ����������.");

// Menu
define("_MI_PROTECTOR_ADMININDEX","�������");
define("_MI_PROTECTOR_ADVISORY","���������");
define("_MI_PROTECTOR_PREFIXMANAGER","���������� ��������� ��");

// Configs
define('_MI_PROTECTOR_GLOBAL_DISBL','�������� ��������');
define('_MI_PROTECTOR_GLOBAL_DISBLDSC','��� ������� ������ �������� ���������.<br />�� �������� �������� �� ����� ���������� ����� ������� � �������������');

define('_MI_PROTECTOR_RELIABLE_IPS','���������� ������');
define('_MI_PROTECTOR_RELIABLE_IPSDSC','���������� ������ ��� ������ ��� ������� �������� ������������ �� ����������. ���������� ������ ����� ������ "|". "^" ������������� ������ ������, "$" ������������� ����� ������.');

define('_MI_PROTECTOR_LOG_LEVEL','������ �������');
//define('_MI_PROTECTOR_LOG_LEVELDSC','');

define('_MI_PROTECTOR_LOGLEVEL0','������ ��������');
define('_MI_PROTECTOR_LOGLEVEL15','������� �������');
define('_MI_PROTECTOR_LOGLEVEL63','������� �������');
define('_MI_PROTECTOR_LOGLEVEL255','��� �������');

define('_MI_PROTECTOR_HIJACK_TOPBIT','���������� ���� IP ��� ������');
define('_MI_PROTECTOR_HIJACK_TOPBITDSC','����-����� ������:<br />�������� �� ��������� 32 (���).
 (��� ���� ��������)<br />����� ��� IP �� ��������, ���������� �������� IP ������ �����.<br />(������) ���� ��� IP ����� ��������� � �������� 192.168.0.0-192.168.0.255, ���������� 24 (���) �����');
define('_MI_PROTECTOR_HIJACK_DENYGP','������ ��� ������� ��������� ������ � ������ ����� ������ ���������');
define('_MI_PROTECTOR_HIJACK_DENYGPDSC','������� � ������������ ������:<br />
    �������� ������ ��� ������� ����� � �������� ����� ������ ���������.<br />
    (������������� ������ �������� � ������ ����� ������ ��������������� �����.)');
define('_MI_PROTECTOR_SAN_NULLBYTE','�������� ������ � ������� �����');
define('_MI_PROTECTOR_SAN_NULLBYTEDSC','����������� ������ "\\0" ����� ������������ � ��������� ����� ����.<br />
    ���� ������ ����� ������� �� ������.<br />(������������� ������ �������� ������ ���������)');
define('_MI_PROTECTOR_DIE_NULLBYTE','�������� ������ � ������� �����');
define('_MI_PROTECTOR_DIE_NULLBYTEDSC','����������� ������ "\0" ����� ������������ � ��������� ����� ����.<br />(������������� ������ �������� ������ ���������)');
define('_MI_PROTECTOR_DIE_BADEXT','�������� ���������� ��� �������� �������� �����');
define('_MI_PROTECTOR_DIE_BADEXTDSC','� ������ ����� ���-���� ���������� ��������� �� ���� ���� ������� ������� ���������� (�������� .php) - �������� �������� ����� ��������. ���� ��� ����� ���������� ��������� ����� ����� (�������� ��� ������� B-Wiki ��� PukiWikiMod) - ��������� ������ ��������.');
define('_MI_PROTECTOR_CONTAMI_ACTION','�������� ��� ����������� "�������" ����������');
define('_MI_PROTECTOR_CONTAMI_ACTIONDS','�������� �������� ����������� � ������ ����� ���-���� �������� �������� ������ ������� "�������" ��������� ���������� XOOPS. (�������������: ������ �����)');
define('_MI_PROTECTOR_ISOCOM_ACTION','�������� ��� ����������� �������������� �����������');
define('_MI_PROTECTOR_ISOCOM_ACTIONDSC','�������� �������� ����������� ��� ����������� ������ "/*" ��� ������������.<br />"�������" ������������� ���������� ������������ �������� "*/".<br />(�������������: ��������)');
define('_MI_PROTECTOR_UNION_ACTION','�������� ��� ����������� ��������� ����� UNION');
define('_MI_PROTECTOR_UNION_ACTIONDSC','�������� �������� ����������� ��� ����������� ��������� ����� UNION. "�������" ������������ ��������� ���� ��������� ������� ����� "UNI-ON". (�������������: ��������)');
define('_MI_PROTECTOR_ID_INTVAL','�������������� �������������� ������������ ���������� (�������� id)');
define('_MI_PROTECTOR_ID_INTVALDSC','��� ������� ����: "*id" ����� ���������� ��� ����� �����.<br />���� �������� �������� ��� �� ��������� ����� XSS � SQL Injections ����.<br />
    ������������� �������� ���� �������� � ��������� ������ ��� ������������� ������� � ������������� �����-���� �������.');
define('_MI_PROTECTOR_FILE_DOTDOT','������ �� Directroy Traversals');
define('_MI_PROTECTOR_FILE_DOTDOTDSC','������� ��� ��������� ������������������ ".." �� ���� �������� ���������� ��� Directory Traversals');

define('_MI_PROTECTOR_BF_COUNT','������ �� ������� ������');
define('_MI_PROTECTOR_BF_COUNTDSC','���������� ������������ ���������� ������� ����� ������������ �� 10 �����. � ������ ���� ���-���� ���������� ������������ ������� ��� ������� ���������� ��� - ��� ����� ����� ������� � ������ ������.');

define('_MI_PROTECTOR_DOS_SKIPMODS','���������� ������� �� DoS/Crawler ������');
define('_MI_PROTECTOR_DOS_SKIPMODSDSC','������� ����� ��������� ����������� �������� "|" ��� ������� � ������� ����� ��������� DoS/Crawler ������. ���� �������� � ��������� ������ �������� � ������� ���� � ������ ������� ��� ������� ������ ��������� � ���������� ������� �������� ������.');

define('_MI_PROTECTOR_DOS_EXPIRE','����� �������� ��� ����������� ������� �������� (���)');
define('_MI_PROTECTOR_DOS_EXPIREDSC','������ �������� ��������� ����� �������� �� ��������� �������� �������� �������� ("����� F5" � ������ ������������� ������)');

define('_MI_PROTECTOR_DOS_F5COUNT','������� ��� "����� F5"');
define('_MI_PROTECTOR_DOS_F5COUNTDSC','�������� �� DoS ����.<br />
    ��� �������� ��������� ���������� �������� �������� ���������� �������� �� ������������ ����� ����� �������� ������������ ��� �������������� �����.');
define('_MI_PROTECTOR_DOS_F5ACTION','�������� ��� ����������� ������� ���������� �������');

define('_MI_PROTECTOR_DOS_CRCOUNT','������� ��� �������');
define('_MI_PROTECTOR_DOS_CRCOUNTDSC','������������� ������� �������� ������� �������� ��������� ������. �������� �������� ������ ���������� �������� ���������� �������� �� ������������ ����� ����� �������� ������������ ��� ��������� "������������" �������');
define('_MI_PROTECTOR_DOS_CRACTION','�������� ��� ����������� "������" �������.');

define('_MI_PROTECTOR_DOS_CRSAFE','������ ������������ (User-Agent) �� ������������ ��� "������"');
define('_MI_PROTECTOR_DOS_CRSAFEDSC','���������� ��������� perl ��� ���� ������ ������������ (User-Agent).<br />� ������ ���������� ������ ���������� � �������� ���������� - ����� ������� �� ������������ ��� "������".<br />������: /(msnbot|Googlebot|Yandex|Yahoo! Slurp|StackRambler)/i');

define('_MI_PROTECTOR_OPT_NONE','������ (������ ������ � �������)');
define('_MI_PROTECTOR_OPT_SAN','�������');
define('_MI_PROTECTOR_OPT_EXIT','������ �����');
define('_MI_PROTECTOR_OPT_BIP','�������� ����� � ������ ������');

define('_MI_PROTECTOR_DOSOPT_NONE','������ (������ ������ � �������)');
define('_MI_PROTECTOR_DOSOPT_SLEEP','�������');
define('_MI_PROTECTOR_DOSOPT_EXIT','������ �����');
define('_MI_PROTECTOR_DOSOPT_BIP','�������� ����� � ������ ������');
define('_MI_PROTECTOR_DOSOPT_HTA','��������� ������ ��������� .htaccess (����������������)');

define('_MI_PROTECTOR_BIP_EXCEPT','����� ������������� ������� �� ���������� � ������ ������.');
define('_MI_PROTECTOR_BIP_EXCEPTDSC','������������� ������ ��������� � ���� ������ ������ ��������������� �����.');

define('_MI_PROTECTOR_DISABLES','�������������� ������������ ������� ������� XOOPS');

define('_MI_PROTECTOR_BIGUMBRELLA','�������� anti-XSS (BigUmbrella)');
define('_MI_PROTECTOR_BIGUMBRELLADSC','��� �������� �������� ��� �� ��������� ����� ���������� XSS. �������� �� 100%!!');

define('_MI_PROTECTOR_SPAMURI4U','anti-SPAM: ����������� ������ ��� �������������');
define('_MI_PROTECTOR_SPAMURI4UDSC','���� ����������� ������  � ���������� �� ������������� (����� ���������������), ��������� ���������, ��������� ������������ ��� ����.<br /> 0 - ���������.');
define('_MI_PROTECTOR_SPAMURI4G','anti-SPAM: ����������� ������ ��� ������');
define('_MI_PROTECTOR_SPAMURI4GDSC','���� ����������� ������  � ���������� �� ������, ��������� ���������, ��������� ������������ ��� ����.<br />  0 - ���������.');

}
