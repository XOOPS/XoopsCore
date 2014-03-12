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

//PhpSecInfo
define("_AM_PROTECTOR_ALLOW_URL_INCLUDE", "<h3>Test Description</h3></p><p style='align: left'>This test checks to see if allow_url_include is enabled. Note that this setting is only available since PHP 5.2, so the test will not run if you have an older verion.</p><h3>Security Implications</h3><p>If disabled, allow_url_include bars remote file access via the <code>include</code> and <code>require</code> statements, but leaves it available for other file functions like <code>fopen()</code> and <code>file_get_contents</code>. <code>include</code> and <code>require</code> are the most common attack points for code injection attempts, so this setting plugs that particular hole without affecting the remote file access capabilities of the standard file functions.</p><p>Note that at this point we still recommend disabling allow_url_fopen as well, but developers who are confident in their secure coding practices may want to leave allow_url_fopen enabled.</p><p>By default, allow_url_include is <em>disabled</em>. If allow_url_fopen is disabled, allow_url_include is also disabled.</p><p><h3>Recommendations</h3></p><p>By default, allow_url_include is disabled. We strongly recommend keeping it disabled.</p><p>You can disable allow_url_include in the php.ini file:</p>
<pre>; Disable allow_url_include for security reasons allow_url_include = 'off' </pre><p>The setting can also be disabled in apache's httpd.conf file:</p>
<pre> # Disable allow_url_include for security reasons php_flag  allow_url_include  off </pre><p>For remote file access, consider using the cURL functions that PHP provides.</p><p><h3>More Information</h3></p><ul><li><a href='http://php.net/manual/en/ref.filesystem.php#ini.allow-url-include'>PHP.net manual: allow_url_include</a></li><li><a href='http://www.php.net/~derick/meeting-notes.html#merge-hardened-php-patch-into-php'>Minutes PHP Developers Meeting - November 2005: 6.2 Merge Hardened PHP patch into PHP</a></li></ul>)");

define('_AM_PROTECTOR_ALLOW_URL_INCLUDED_OK', "allow_url_include is disabled, which is the recommended setting");


define("_AM_PROTECTOR_TEST", "Action");
define("_AM_PROTECTOR_RESULT", "Result");
define("_AM_PROTECTOR_NOTE", "Notes");
define("_AM_PROTECTOR_CURRENT_VALUE", "Current Value");
define("_AM_PROTECTOR_RECOMMENDED_VALUE", "Recommended Value");
define("_AM_PROTECTOR_MORE_INFO", "More info »");







