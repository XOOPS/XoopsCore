<?php
if (defined('FOR_XOOPS_LANG_CHECKER') || !defined('_MI_PROTECTOR_LOADED')) {

    define('_MI_PROTECTOR_LOADED', 1);

    // The name of this module
    define("_MI_PROTECTOR_NAME", "Protector");

    // A brief description of this module
    define("_MI_PROTECTOR_DESC", "This module protects your xoops site from various attacks like DoS , SQL Injection , and Variables contaminations.");

    // Menu
    define("_MI_PROTECTOR_ADMININDEX", "Protect Center");
    define("_MI_PROTECTOR_ADVISORY", "Security Advisory");
    define("_MI_PROTECTOR_PREFIXMANAGER", "Prefix Manager");
    //define('_MI_PROTECTOR_ADMENU_MYBLOCKSADMIN', 'Permissions');

    // Configs
    define('_MI_PROTECTOR_GLOBAL_DISBL', 'Temporary disabled');
    define('_MI_PROTECTOR_GLOBAL_DISBLDSC', 'All protections are disabled in temporary.<br />Don\'t forget turn this off after shooting the trouble');

    define('_MI_PROTECTOR_DEFAULT_LANG', 'Default language');
    define('_MI_PROTECTOR_DEFAULT_LANGDSC', 'Specify the language set to display messages before processing common.php');

    define('_MI_PROTECTOR_RELIABLE_IPS', 'Reliable IPs');
    define('_MI_PROTECTOR_RELIABLE_IPSDSC', 'set IPs you can rely separated with | . ^ matches the head of string, $ matches the tail of string.');

    define('_MI_PROTECTOR_LOG_LEVEL', 'Logging level');
    //define('_MI_PROTECTOR_LOG_LEVELDSC', '');

    define('_MI_PROTECTOR_BANIP_TIME0', 'Banned IP suspension time (sec)');

    define('_MI_PROTECTOR_LOGLEVEL0', 'none');
    define('_MI_PROTECTOR_LOGLEVEL15', 'Quiet');
    define('_MI_PROTECTOR_LOGLEVEL63', 'quiet');
    define('_MI_PROTECTOR_LOGLEVEL255', 'full');

    define('_MI_PROTECTOR_HIJACK_TOPBIT', 'Protected IP bits for the session');
    define('_MI_PROTECTOR_HIJACK_TOPBITDSC', 'Anti Session Hi-Jacking:<br />Default 32(bit). (All bits are protected)<br />When your IP is not stable, set the IP range by number of the bits.<br />(eg) If your IP can move in the range of 192.168.0.0-192.168.0.255, set 24(bit) here');
    define('_MI_PROTECTOR_HIJACK_DENYGP', 'Groups disallowed IP moving in a session');
    define('_MI_PROTECTOR_HIJACK_DENYGPDSC', 'Anti Session Hi-Jacking:<br />Select groups which is disallowed to move their IP in a session.<br />(I recommend to turn Administrator on.)');
    define('_MI_PROTECTOR_SAN_NULLBYTE', 'Sanitizing null-bytes');
    define('_MI_PROTECTOR_SAN_NULLBYTEDSC', 'The terminating character "\\0" is often used in malicious attacks.<br />a null-byte will be changed to a space.<br />(highly recommended as On)');
    define('_MI_PROTECTOR_DIE_NULLBYTE', 'Exit if null bytes are found');
    define('_MI_PROTECTOR_DIE_NULLBYTEDSC', 'The terminating character "\\0" is often used in malicious attacks.<br />(highly recommended as On)');
    define('_MI_PROTECTOR_DIE_BADEXT', 'Exit if bad files are uploaded');
    define('_MI_PROTECTOR_DIE_BADEXTDSC', 'If someone tries to upload files which have bad extensions like .php , this module exits your XOOPS.<br />If you often attach php files into B-Wiki or PukiWikiMod, turn this off.');
    define('_MI_PROTECTOR_CONTAMI_ACTION', 'Action if a contamination is found');
    define('_MI_PROTECTOR_CONTAMI_ACTIONDS', 'Select the action when someone tries to contaminate system global variables into your XOOPS.<br />(recommended option is blank screen)');
    define('_MI_PROTECTOR_ISOCOM_ACTION', 'Action if an isolated comment-in is found');
    define('_MI_PROTECTOR_ISOCOM_ACTIONDSC', 'Anti SQL Injection:<br />Select the action when an isolated "/*" is found.<br />"Sanitizing" means adding another "*/" in tail.<br />(recommended option is Sanitizing)');
    define('_MI_PROTECTOR_UNION_ACTION', 'Action if a UNION is found');
    define('_MI_PROTECTOR_UNION_ACTIONDSC', 'Anti SQL Injection:<br />Select the action when some syntax like UNION of SQL.<br />"Sanitizing" means changing "union" to "uni-on".<br />(recommended option is Sanitizing)');
    define('_MI_PROTECTOR_ID_INTVAL', 'Force intval to variables like id');
    define('_MI_PROTECTOR_ID_INTVALDSC', 'All requests named "*id" will be treated as integer.<br />This option protects you from some kind of XSS and SQL Injections.<br />Though I recommend to turn this option on, it can cause problems with some modules.');
    define('_MI_PROTECTOR_FILE_DOTDOT', 'Protection from Directroy Traversals');
    define('_MI_PROTECTOR_FILE_DOTDOTDSC', 'It eliminates ".." from all requests looks like Directory Traversals');

    define('_MI_PROTECTOR_BF_COUNT', 'Anti Brute Force');
    define('_MI_PROTECTOR_BF_COUNTDSC', 'Set count you allow guest try to login within 10 minutes. If someone fails to login more than this number, her/his IP will be banned.');

    define('_MI_PROTECTOR_BWLIMIT_COUNT', 'Bandwidth limitation');
    define('_MI_PROTECTOR_BWLIMIT_COUNTDSC', 'Specify the max access to mainfile.php during watching time. This value should be 0 for normal environments which have enough CPU bandwidth. The number fewer than 10 will be ignored.');

    define('_MI_PROTECTOR_DOS_SKIPMODS', 'Modules out of DoS/Crawler checker');
    define('_MI_PROTECTOR_DOS_SKIPMODSDSC', 'set the dirnames of the modules separated with |. This option will be useful with chatting module etc.');

    define('_MI_PROTECTOR_DOS_EXPIRE', 'Watch time for high loadings (sec)');
    define('_MI_PROTECTOR_DOS_EXPIREDSC', 'This value specifies the watch time for high-frequent reloading (F5 attack) and high loading crawlers.');

    define('_MI_PROTECTOR_DOS_F5COUNT', 'Bad counts for F5 Attack');
    define('_MI_PROTECTOR_DOS_F5COUNTDSC', 'Preventing from DoS attacks.<br />This value specifies the reloading counts to be considered as a malicious attack.');
    define('_MI_PROTECTOR_DOS_F5ACTION', 'Action against F5 Attack');

    define('_MI_PROTECTOR_DOS_CRCOUNT', 'Bad counts for Crawlers');
    define('_MI_PROTECTOR_DOS_CRCOUNTDSC', 'Preventing from high loading crawlers.<br />This value specifies the access counts to be considered as a bad-manner crawler.');
    define('_MI_PROTECTOR_DOS_CRACTION', 'Action against high loading Crawlers');

    define('_MI_PROTECTOR_DOS_CRSAFE', 'Welcomed User-Agent');
    define('_MI_PROTECTOR_DOS_CRSAFEDSC', 'A perl regex pattern for User-Agent.<br />If it matches, the crawler is never considered as a high loading crawler.<br />eg) /(msnbot|Googlebot|Yahoo! Slurp)/i');

    define('_MI_PROTECTOR_OPT_NONE', 'None (only logging)');
    define('_MI_PROTECTOR_OPT_SAN', 'Sanitizing');
    define('_MI_PROTECTOR_OPT_EXIT', 'Blank Screen');
    define('_MI_PROTECTOR_OPT_BIP', 'Ban the IP (No limit)');
    define('_MI_PROTECTOR_OPT_BIPTIME0', 'Ban the IP (moratorium)');

    define('_MI_PROTECTOR_DOSOPT_NONE', 'None (only logging)');
    define('_MI_PROTECTOR_DOSOPT_SLEEP', 'Sleep');
    define('_MI_PROTECTOR_DOSOPT_EXIT', 'Blank Screen');
    define('_MI_PROTECTOR_DOSOPT_BIP', 'Ban the IP (No limit)');
    define('_MI_PROTECTOR_DOSOPT_BIPTIME0', 'Ban the IP (moratorium)');
    define('_MI_PROTECTOR_DOSOPT_HTA', 'DENY by .htaccess(Experimental)');

    define('_MI_PROTECTOR_BIP_EXCEPT', 'Groups never registered as Bad IP');
    define('_MI_PROTECTOR_BIP_EXCEPTDSC', 'A user who belongs to the group specified here will never be banned.<br />(I recommend to turn Administrator on.)');

    define('_MI_PROTECTOR_DISABLES', 'Disable dangerous features in XOOPS');

    define('_MI_PROTECTOR_DBLAYERTRAP', 'Enable DB Layer trapping anti-SQL-Injection');
    define('_MI_PROTECTOR_DBLAYERTRAPDSC', 'Almost SQL Injection attacks will be canceled by this feature. This feature is required a support from databasefactory. You can check it on Security Advisory page. This setting must be on. Never turn it off casually.');
    define('_MI_PROTECTOR_DBTRAPWOSRV', 'Never checking _SERVER for anti-SQL-Injection');
    define('_MI_PROTECTOR_DBTRAPWOSRVDSC', 'Some servers always enable DB Layer trapping. It causes wrong detections as SQL Injection attack. If you got such errors, turn this option on. You should know this option weakens the security of DB Layer trapping anti-SQL-Injection.');

    define('_MI_PROTECTOR_BIGUMBRELLA', 'enable anti-XSS (BigUmbrella)');
    define('_MI_PROTECTOR_BIGUMBRELLADSC', 'This protects you from almost attacks via XSS vulnerabilities. But it is not 100%');

    define('_MI_PROTECTOR_SPAMURI4U', 'anti-SPAM: URLs for normal users');
    define('_MI_PROTECTOR_SPAMURI4UDSC', 'If this number of URLs are found in POST data from users other than admin, the POST is considered as SPAM. 0 means disabling this feature.');
    define('_MI_PROTECTOR_SPAMURI4G', 'anti-SPAM: URLs for guests');
    define('_MI_PROTECTOR_SPAMURI4GDSC', 'If this number of URLs are found in POST data from guests, the POST is considered as SPAM. 0 means disabling this feature.');

    //3.40b
    define("_MI_PROTECTOR_ADMINHOME", "Home");
    define("_MI_PROTECTOR_ADMINABOUT", "About");
    //3.50
    define('_MI_PROTECTOR_STOPFORUMSPAM_ACTION', 'Stop Forum Spam');
    define('_MI_PROTECTOR_STOPFORUMSPAM_ACTIONDSC', 'Checks POST data against spammers registered on www.stopforumspam.com database. Requires php CURL lib.');
}
