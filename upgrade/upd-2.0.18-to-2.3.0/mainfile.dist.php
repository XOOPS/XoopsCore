<?php
/**
 * XOOPS main configuration file
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @version     $Id$
 */

if (!defined("XOOPS_MAINFILE_INCLUDED")) {
    define("XOOPS_MAINFILE_INCLUDED", 1);

    // XOOPS Physical Paths

    // Physical path to the XOOPS documents (served) directory WITHOUT trailing slash
    define("XOOPS_ROOT_PATH", "");

    // For forward compatibility
    // Physical path to the XOOPS library directory WITHOUT trailing slash
    define("XOOPS_PATH", "");
    // Physical path to the XOOPS datafiles (writable) directory WITHOUT trailing slash
    define("XOOPS_VAR_PATH", "");
    // Alias of XOOPS_PATH, for compatibility, temporary solution
    define("XOOPS_TRUST_PATH", XOOPS_PATH);

    // URL Association for SSL and Protocol Compatibility
    $http = 'http://';
    if (!empty($_SERVER['HTTPS'])) {
        $http = ($_SERVER['HTTPS']=='on') ? 'https://' : 'http://';
    }
    define('XOOPS_PROT', $http);

    // XOOPS Virtual Path (URL)
    // Virtual path to your main XOOPS directory WITHOUT trailing slash
    // Example: define("XOOPS_URL", "http://url_to_xoops_directory");
    define("XOOPS_URL", "http://");

    // Shall be handled later, don't forget!
    define("XOOPS_CHECK_PATH", 0);
    // Protect against external scripts execution if safe mode is not enabled
    if (XOOPS_CHECK_PATH && !@ini_get("safe_mode")) {
        if (function_exists("debug_backtrace")) {
            $xoopsScriptPath = debug_backtrace();
            if (!count($xoopsScriptPath)) {
                 die("XOOPS path check: this file cannot be requested directly");
            }
            $xoopsScriptPath = $xoopsScriptPath[0]["file"];
        } else {
            $xoopsScriptPath = isset($_SERVER["PATH_TRANSLATED"]) ? $_SERVER["PATH_TRANSLATED"] :  $_SERVER["SCRIPT_FILENAME"];
        }
        if (DIRECTORY_SEPARATOR != "/") {
            // IIS6 may double the \ chars
            $xoopsScriptPath = str_replace(strpos($xoopsScriptPath, "\\\\", 2) ? "\\\\" : DIRECTORY_SEPARATOR, "/", $xoopsScriptPath);
        }
        if (strcasecmp(substr($xoopsScriptPath, 0, strlen(XOOPS_ROOT_PATH)), str_replace(DIRECTORY_SEPARATOR, "/", XOOPS_ROOT_PATH))) {
             exit("XOOPS path check: Script is not inside XOOPS_ROOT_PATH and cannot run.");
        }
    }

    // Database
    // Choose the database to be used
    define("XOOPS_DB_TYPE", "mysql");

    // Set the database charset if applicable
    if (defined("XOOPS_DB_CHARSET")) die('Restricted Access');
    define("XOOPS_DB_CHARSET", "");

    // Table Prefix
    // This prefix will be added to all new tables created to avoid name conflict in the database. If you are unsure, just use the default "xoops".
    define("XOOPS_DB_PREFIX", "xoops");

    // Database Hostname
    // Hostname of the database server. If you are unsure, "localhost" works in most cases.
    define("XOOPS_DB_HOST", "localhost");

    // Database Username
    // Your database user account on the host
    define("XOOPS_DB_USER", "");

    // Database Password
    // Password for your database user account
    define("XOOPS_DB_PASS", "");

    // Database Name
    // The name of database on the host. The installer will attempt to create the database if not exist
    define("XOOPS_DB_NAME", "");

    // Use persistent connection? (Yes=1 No=0)
    // Default is "Yes". Choose "Yes" if you are unsure.
    define("XOOPS_DB_PCONNECT", 0);

    define("XOOPS_GROUP_ADMIN", "1");
    define("XOOPS_GROUP_USERS", "2");
    define("XOOPS_GROUP_ANONYMOUS", "3");

    if (!isset($xoopsOption["nocommon"]) && XOOPS_ROOT_PATH != "") {
        include XOOPS_ROOT_PATH."/include/common.php";
    }

}

?>