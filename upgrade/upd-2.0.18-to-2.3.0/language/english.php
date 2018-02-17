<?php
// $Id$
// _LANGCODE: en
// _CHARSET : UTF-8
// Translator: XOOPS Translation Team

define("LEGEND_XOOPS_PATHS", "XOOPS Physical paths");
define("LEGEND_DATABASE", "Database Character Set");

define("XOOPS_LIB_PATH_LABEL", "XOOPS library directory");
define("XOOPS_LIB_PATH_HELP", "Physical path to the XOOPS library directory WITHOUT trailing slash, for forward compatibility. Locate the folder out of " . XOOPS_ROOT_PATH . " to make it secure.");
define("XOOPS_DATA_PATH_LABEL", "XOOPS datafiles directory");
define("XOOPS_DATA_PATH_HELP", "Physical path to the XOOPS datafiles (writable) directory WITHOUT trailing slash, for forward compatibility. Locate the folder out of " . XOOPS_ROOT_PATH . " to make it secure.");

define("DB_COLLATION_LABEL", "Database character set and collation");
define("DB_COLLATION_HELP", "As of 4.12 MySQL supports custom character set and collation. However it is more complex than expected, so DON'T make any change unless you are confident with your choice.");
define("DB_COLLATION_NOCHANGE", "Do not change");

define("XOOPS_PATH_FOUND", "Path found.");
define("ERR_COULD_NOT_ACCESS", "Could not access the specified folder. Please verify that it exists and is readable by the server.");
define("CHECKING_PERMISSIONS", "Checking file and directory permissions...");
define("ERR_NEED_WRITE_ACCESS", "The server must be given write access to the following files and folder<br>(i.e. <em>chmod 777 directory_name</em> on a UNIX/LINUX server)");
define("IS_NOT_WRITABLE", "%s is NOT writable.");
define("IS_WRITABLE", "%s is writable.");
define("ERR_COULD_NOT_WRITE_MAINFILE", "Error writing content to mainfile.php, write the content into mainfile.php manually.");
