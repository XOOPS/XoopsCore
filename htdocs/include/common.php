<?php
/**
 * XOOPS common initialization file
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package   kernel
 */

defined('XOOPS_MAINFILE_INCLUDED') or die('Restricted access');

/**
 * Include XoopsLoad - this should have been done in mainfile.php, but there is
 * no update yet, so only only new installs get the change in mainfile.dist.php
 * automatically.
 *
 * Temorarily try and fix, but set up a (delayed) warning
 */
if (!class_exists('XoopsLoad', false)) {
    require_once dirname(__DIR__). '/class/XoopsBaseConfig.php';
    XoopsBaseConfig::bootstrapTransition();
    $delayedWarning = 'Patch mainfile.php for XoopsBaseConfig';
}

global $xoops;
$GLOBALS['xoops'] =& $xoops;

//Legacy support
global $xoopsDB;
$GLOBALS['xoopsDB'] =& $xoopsDB;
/**
 * YOU SHOULD NEVER USE THE FOLLOWING TO CONSTANTS, THEY WILL BE REMOVED
 */
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('NWLINE')or define('NWLINE', "\n");

/**
 * Include files with definitions
 */
include_once XOOPS_ROOT_PATH . DS . 'include' . DS . 'defines.php';
include_once XOOPS_ROOT_PATH . DS . 'include' . DS . 'version.php';

/**
 * We now have autoloader, so start Patchwork\UTF8
 */
\Patchwork\Utf8\Bootup::initAll(); // Enables the portablity layer and configures PHP for UTF-8
\Patchwork\Utf8\Bootup::filterRequestUri(); // Redirects to an UTF-8 encoded URL if it's not already the case
\Patchwork\Utf8\Bootup::filterRequestInputs(); // Normalizes HTTP inputs to UTF-8 NFC

/**
 * Create Instance of Xoops Object
 * Atention, not all methods can be used at this point
 */

$xoops = Xoops::getInstance();

$xoops->option =& $GLOBALS['xoopsOption'];

/**
 * Create Instance Xoops\Core\Logger Object, the logger manager
 */
$xoopsLogger = $xoops->logger();

/**
 * initialize events
 */
$xoops->events()->initializeListeners();
$psr4loader = new \Xoops\Core\Psr4ClassLoader();
$psr4loader->register();
// listeners respond with $arg->addNamespace($namespace, $directory);
$xoops->events()->triggerEvent('core.include.common.psr4loader', $psr4loader);
$xoops->events()->triggerEvent('core.include.common.classmaps');
$xoops->events()->triggerEvent('core.include.common.start');

/**
 * temporary warning message
 */
if (isset($delayedWarning)) {
    trigger_error($delayedWarning);
}

/**
 * Create Instance of xoopsSecurity Object and check super globals
 */
$xoops->events()->triggerEvent('core.include.common.security');
$xoopsSecurity = $xoops->security();

/**
 * Include Required Files not handled by autoload
 */
include_once $xoops->path('include/functions.php');

/**
 * YOU SHOULD NEVER USE THE FOLLOWING CONSTANT, IT WILL BE REMOVED
 */
/**
 * Set cookie domain to highest registerable domain, so cookie will be availabe to all subdomains.
 * Not sure this is the best idea, but how it has always worked. Set includeSubdomain parameter
 * to getBaseDomain to true to include full host with any subdomain(s).
 */
define('XOOPS_COOKIE_DOMAIN', $xoops->getBaseDomain(XOOPS_URL, $includeSubdomain = false));
//define('XOOPS_COOKIE_DOMAIN', null);

/**
 * Check Proxy;
 * Requires functions
 */
if ($_SERVER['REQUEST_METHOD'] != 'POST' || !$xoops->security()->checkReferer(XOOPS_DB_CHKREF)) {
    define('XOOPS_DB_PROXY', 1);
}

/**
 * Get database for making it global
 * Will also setup $xoopsDB for legacy support.
 * Requires XOOPS_DB_PROXY;
 */
$xoops->db();
//For Legacy support
$xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection(true);

/**
 * Get xoops configs
 * Requires functions and database loaded
 */

$xoops->getConfigs();
$xoopsConfig =& $xoops->config;

/**
 * Merge file and db configs.
 */
if (XoopsLoad::fileExists($file = $xoops->path('var/configs/system_configs.php'))) {
    $xoops->addConfigs(include $file);
    unset($file);
} else {
    trigger_error('File Path Error: ' . 'var/configs/system_configs.php' . ' does not exist.');
}

$xoops->events()->triggerEvent('core.include.common.configs.success');

/**
 * Enable Gzip compression,
 * Requires configs loaded and should go before any output
 */
$xoops->gzipCompression();

/**
 * clickjack protection - Add option to HTTP header restricting using site in an iframe
 */
$xFrameOptions =  isset($xoopsConfig['xFrameOptions']) ? $xoopsConfig['xFrameOptions'] : 'sameorigin';
$xoops->events()->triggerEvent('core.include.common.xframeoption');
if (!headers_sent() && !empty($xFrameOptions)) {
    header('X-Frame-Options: ' .$xFrameOptions);
}

/**
 * Check Bad Ip Addressed against database and block bad ones, requires configs loaded
 */
$xoops->security()->checkBadips();

/**
 * Load Language settings and defines
 */
$xoops->loadLocale();
//For legacy
$xoops->setConfig('language', XoopsLocale::getLegacyLanguage());

date_default_timezone_set(XoopsLocale::getTimezone());
setlocale(LC_ALL, XoopsLocale::getLocale());

/**
 * User Sessions
 */
$member_handler = $xoops->getHandlerMember();
$sess_handler = $xoops->getHandlerSession();

if ($xoops->getConfig('use_ssl')
    && isset($_POST[$xoops->getConfig('sslpost_name')])
    && $_POST[$xoops->getConfig('sslpost_name')] != ''
) {
    session_id($_POST[$xoops->getConfig('sslpost_name')]);
} else {
    if ($xoops->getConfig('use_mysession')
        && $xoops->getConfig('session_name') != ''
        && $xoops->getConfig('session_expire') > 0
    ) {
        session_name($xoopsConfig['session_name']);
        session_cache_expire($xoops->getConfig('session_expire'));
        @ini_set('session.gc_maxlifetime', $xoops->getConfig('session_expire') * 60);
    }
}
session_set_save_handler(
    array(&$sess_handler, 'open'),
    array(&$sess_handler, 'close'),
    array(&$sess_handler, 'read'),
    array(&$sess_handler, 'write'),
    array(&$sess_handler, 'destroy'),
    array(&$sess_handler, 'gc')
);

if (function_exists('session_status')) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
} else {
    // this should silently fail if session has already started (for PHP 5.3)
    @session_start();
}

/**
 * Remove expired session for xoopsUserId
 */
if ($xoops->getConfig('use_mysession')
    && $xoops->getConfig('session_name') != ''
    && !isset($_COOKIE[$xoops->getConfig('session_name')])
    && !empty($_SESSION['xoopsUserId'])
) {
    unset($_SESSION['xoopsUserId']);
}

/**
 * Load xoopsUserId from cookie if "Remember me" is enabled.
 */
if (empty($_SESSION['xoopsUserId'])
    && $xoops->getConfig('usercookie') != ''
    && !empty($_COOKIE[$xoops->getConfig('usercookie')])
) {
    $hash_data = @explode("-", $_COOKIE[$xoops->getConfig('usercookie')], 2);
    list($_SESSION['xoopsUserId'], $hash_login) = array($hash_data[0], strval(@$hash_data[1]));
    unset($hash_data);
}

/**
 * Log user in and deal with Sessions and Cookies
 */
if (!empty($_SESSION['xoopsUserId'])) {
    $xoops->user = $member_handler->getUser($_SESSION['xoopsUserId']);
    if (!is_object($xoops->user)
        || (isset($hash_login)
            && md5($xoops->user->getVar('pass') . XOOPS_DB_NAME . XOOPS_DB_PASS . XOOPS_DB_PREFIX) != $hash_login)
    ) {
        $xoops->user = '';
        $_SESSION = array();
        session_destroy();
        setcookie($xoops->getConfig('usercookie'), 0, -1, '/');
    } else {
        if ((intval($xoops->user->getVar('last_login')) + 60 * 5) < time()) {
            $user_handler = $xoops->getHandlerUser();
            $criteria = new Criteria('uid', $_SESSION['xoopsUserId']);
            $user_handler->updateAll('last_login', time(), $criteria, true);
            unset($criteria);
        }
        $sess_handler->update_cookie();
        if (isset($_SESSION['xoopsUserGroups'])) {
            $xoops->user->setGroups($_SESSION['xoopsUserGroups']);
        } else {
            $_SESSION['xoopsUserGroups'] = $xoops->user->getGroups();
        }
        $xoops->userIsAdmin = $xoops->user->isAdmin();
    }
}

$xoops->events()->triggerEvent('core.include.common.auth.success');

/**
 * Theme Selection
 */
$xoops->themeSelect();

/**
 * Closed Site
 */
if ($xoops->getConfig('closesite') == 1) {
    include_once $xoops->path('include/site-closed.php');
}

/**
 * Load Xoops Module
 */
$xoops->moduleDirname = 'system';
if (XoopsLoad::fileExists('./xoops_version.php')) {
    $url_arr = explode('/', strstr($_SERVER['PHP_SELF'], '/modules/'));
    $module_handler = $xoops->getHandlerModule();
    $xoops->module = $xoops->getModuleByDirname($url_arr[2]);
    $xoops->moduleDirname = $url_arr[2];
    unset($url_arr);

    if (!$xoops->module || !$xoops->module->getVar('isactive')) {
        $xoops->redirect(XOOPS_URL, 3, XoopsLocale::E_NO_MODULE);
        exit();
    }
    $moduleperm_handler = $xoops->getHandlerGroupperm();
    if ($xoops->isUser()) {
        if (!$moduleperm_handler->checkRight('module_read', $xoops->module->getVar('mid'), $xoops->user->getGroups())) {
            $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION, false);
        }
        $xoops->userIsAdmin = $xoops->user->isAdmin($xoops->module->getVar('mid'));
    } else {
        if (!$moduleperm_handler->checkRight('module_read', $xoops->module->getVar('mid'), XOOPS_GROUP_ANONYMOUS)) {
            $xoops->redirect(
                XOOPS_URL . '/user.php?from=' . $xoops->module->getVar('dirname', 'n'),
                1,
                XoopsLocale::E_NO_ACCESS_PERMISSION
            );
        }
    }

    if ($xoops->module->getVar('dirname', 'n') != 'system') {
        $xoops->loadLanguage('main', $xoops->module->getVar('dirname', 'n'));
        $xoops->loadLocale($xoops->module->getVar('dirname', 'n'));
    }

    if ($xoops->module->getVar('hasconfig') == 1
        || $xoops->module->getVar('hascomments') == 1
        || $xoops->module->getVar('hasnotification') == 1
    ) {
        $xoops->getModuleConfigs();
    }
} else {
    if ($xoops->isUser()) {
        $xoops->userIsAdmin = $xoops->user->isAdmin(1);
    }
}

$xoopsTpl = $xoops->tpl();
$xoTheme = null;
$xoopsUser =& $xoops->user;
$xoopsModule =& $xoops->module;
$xoopsUserIsAdmin =& $xoops->userIsAdmin;
$xoopsModuleConfig =& $xoops->moduleConfig;

//Creates 'system_modules_active' cache file if it has been deleted.
$xoops->getActiveModules();

$xoops->events()->triggerEvent('core.include.common.end');
