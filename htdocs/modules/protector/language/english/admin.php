<?php
// index.php
define("_AM_TH_DATETIME", "Time");
define("_AM_TH_USER", "User");
define("_AM_TH_IP", "IP");
define("_AM_TH_AGENT", "AGENT");
define("_AM_TH_TYPE", "Type");
define("_AM_TH_DESCRIPTION", "Description");

define("_AM_TH_BADIPS", 'Bad IPs<br /><br /><span style="font-weight:normal;">Write each IP a line<br />blank means all IPs are allowed</span>');

define("_AM_TH_GROUP1IPS", 'Allowed IPs for Group=1<br /><br /><span style="font-weight:normal;">Write each IP a line.<br />192.168. means 192.168.*<br />blank means all IPs are allowed</span>');

define("_AM_LABEL_COMPACTLOG", "Compact log");
define("_AM_BUTTON_COMPACTLOG", "Compact it!");
define("_AM_JS_COMPACTLOGCONFIRM", "Duplicated (IP,Type) records will be removed");
define("_AM_LABEL_REMOVEALL", "Remove all records");
define("_AM_BUTTON_REMOVEALL", "Remove all!");
define("_AM_JS_REMOVEALLCONFIRM", "All logs are removed absolutely. Are you really OK?");
define("_AM_LABEL_REMOVE", "Remove the records checked:");
define("_AM_BUTTON_REMOVE", "Remove!");
define("_AM_JS_REMOVECONFIRM", "Remove OK?");
define("_AM_MSG_IPFILESUPDATED", "Files for IPs have been updated");
define("_AM_MSG_BADIPSCANTOPEN", "The file for badip cannot be opened");
define("_AM_MSG_GROUP1IPSCANTOPEN", "The file for allowing group=1 cannot be opened");
define("_AM_MSG_REMOVED", "Records are removed");
//define("_AM_FMT_CONFIGSNOTWRITABLE", "Turn the configs directory writable: %s");

// prefix_manager.php
define("_AM_MSG_DBUPDATED", "Database Updated Successfully!");
define("_AM_CONFIRM_DELETE", "All data will be dropped. OK?");
define("_AM_TXT_HOWTOCHANGEDB", "If you want to change prefix, edit <b>%s</b> manually");

// advisory.php
define("_AM_ADV_NOTSECURE", "Not secure");

define("_AM_ADV_TRUSTPATHPUBLIC", "If you can see an image -NG- or the link returns normal page, your XOOPS_TRUST_PATH is not placed properly. The best place for XOOPS_TRUST_PATH is outside of DocumentRoot. If you cannot do that, you have to put .htaccess (DENY FROM ALL) just under XOOPS_TRUST_PATH as the second best way.");
define("_AM_ADV_TRUSTPATHPUBLICLINK", "Check that PHP files inside TRUST_PATH are set to read-only (it must be 404,403 or 500 error)");
define("_AM_ADV_REGISTERGLOBALS", "If 'ON', this setting invites a variety of injecting attacks. If you can, set 'register_globals off' in php.ini, or if not possible, create or edit .htaccess in your XOOPS directory:");
define("_AM_ADV_ALLOWURLFOPEN", "If 'ON', this setting allows attackers to execute arbitrary scripts on remote servers.<br />Only administrator can change this option.<br />If you are an admin, edit php.ini or httpd.conf.<br /><b>Sample of httpd.conf:<br /> &nbsp; php_admin_flag &nbsp; allow_url_fopen &nbsp; off</b><br />Else, claim it to your administrators.");
define("_AM_ADV_USETRANSSID", "If 'ON', your Session ID will be displayed in anchor tags etc.<br />To prevent session hi-jacking, add a line into .htaccess in XOOPS_ROOT_PATH.<br /><b>php_flag session.use_trans_sid off</b>");
define("_AM_ADV_DBPREFIX", "This setting invites 'SQL Injections'.<br />Don't forget turning 'Force sanitizing *' ON in this module's preferences.");
define("_AM_ADV_LINK_TO_PREFIXMAN", "Go to prefix manager");
define("_AM_ADV_MAINUNPATCHED", "You should edit your mainfile.php like written in README.");
define("_AM_ADV_DBFACTORYPATCHED", "Your databasefactory is ready for DBLayer Trapping anti-SQL-Injection");
define("_AM_ADV_DBFACTORYUNPATCHED", "Your databasefactory is not ready for DBLayer Trapping anti-SQL-Injection. Some patches are required.");

define("_AM_ADV_SUBTITLECHECK", "Check if Protector works well");
define("_AM_ADV_CHECKCONTAMI", "Contaminations");
define("_AM_ADV_CHECKISOCOM", "Isolated Comments");

//XOOPS 2.5.4
define("_AM_ADV_REGISTERGLOBALS2", "and place in it the line below:");

//XOOPS 2.6.0
/**************************************
- Removed
- _AM_H3_PREFIXMAN
- _MD_A_MYMENU_MYTPLSADMIN
- _MD_A_MYMENU_MYBLOCKSADMIN
- _MD_A_MYMENU_MYPREFERENCES

* Modified
* _AM_TXT_HOWTOCHANGEDB

*****************************************/
//index.php
define("_AM_PROTECTOR_NBALERT", "There are %s alerts");

// advisory.php
define("_AM_ADV_ACTION", "Action");
define("_AM_ADV_INFO", "Information");
define("_AM_ADV_STATUS", "Status");
define("_AM_ADV_TYPE", "Type of security");
define("_AM_ADV_VIEW", "Information");

// prefix_manager.php
define("_AM_PROTECTOR_PREFIX", "PREFIX");
define("_AM_PROTECTOR_PREFIX_ACTIONS", "ACTIONS");
define("_AM_PROTECTOR_PREFIX_CHANGEDB", "Change prefix");
define("_AM_PROTECTOR_PREFIX_CHANGEDBLINE", "Line: define('XOOPS_DB_PREFIX', '<b>%s</b>');");
define("_AM_PROTECTOR_PREFIX_COPY", "COPY");
define("_AM_PROTECTOR_PREFIX_ERROR_CT", "error: CREATE TABLE (%s)");
define("_AM_PROTECTOR_PREFIX_ERROR_CTDWT", "You can't drop working tables");
define("_AM_PROTECTOR_PREFIX_ERROR_II", "error: INSERT INTO (%s)");
define("_AM_PROTECTOR_PREFIX_ERROR_NACT", "You are not allowed to copy tables");
define("_AM_PROTECTOR_PREFIX_ERROR_NADT", "You are not allowed to delete tables");
define("_AM_PROTECTOR_PREFIX_ERROR_NPX", "This is not a prefix for XOOPS");
define("_AM_PROTECTOR_PREFIX_ERROR_SCT", "error: SHOW CREATE TABLE (%s)");
define("_AM_PROTECTOR_PREFIX_ERROR_WP", "Wrong prefix");
define("_AM_PROTECTOR_PREFIX_LOG", "Log");
define("_AM_PROTECTOR_PREFIX_TABLES", "TABLES");
define("_AM_PROTECTOR_PREFIX_UPDATED", "UPDATED");