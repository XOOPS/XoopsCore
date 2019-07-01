<?php
/**
 * Xoops Functions
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.0.0
 * @version         $Id$
 */
use Xoops\Core\Handler\Factory;

/**
 * Standard deprecation warning with trace - consider private
 *
 * @param string $function function that was called
 * @param string $file     file name where called
 * @param int    $line     line number where called
 *
 * @return Xoops
 */
function xoops_functions_php_deprecated($function, $file, $line)
{
    $xoops = \Xoops::getInstance();
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    $xoops->deprecated(
        "{$function} is deprecated. Called from {$trace[1]['file']} line {$trace[1]['line']}."
        . " See how to replace it in file {$file} line {$line}"
    );

    return $xoops;
}

/**
 * Replace function "deprecation" warning with trace - consider private
 *
 * @param string $function function that was called
 * @param string $module   replacing module/service
 *
 * @return Xoops
 */
function xoops_functions_php_replaced_by_mod($function, $module)
{
    $xoops = \Xoops::getInstance();
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    $xoops->deprecated(
        "{$function} is deprecated. Called from {$trace[1]['file']} line {$trace[1]['line']}."
        . " Convert to use {$module}."
    );

    return $xoops;
}

/**
 * @deprecated
 * @param string $name     handler name
 * @param bool   $optional is this optional, causes error if false and no handler is available
 * @return XoopsObjectHandler|XoopsPersistableObjectHandler|null
 */
function xoops_getHandler($name, $optional = false)
{
    xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));
    // Also can use: dedicated alias, i.e. $handler = $xoops->getHandlerConfig($optional);
    $handler = Factory::newSpec()->scheme('kernel')->name($name)->optional((bool) $optional)->build();

    return $handler;
}

/**
 * @deprecated
 * @param string|null $name       handler name
 * @param string|null $module_dir module dirname
 * @param bool        $optional   is this optional, causes error if false and no handler is available
 * @return XoopsObjectHandler|XoopsPersistableObjectHandler|null
 */
function xoops_getModuleHandler($name = null, $module_dir = null, $optional = false)
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->getModuleHandler($name, $module_dir, $optional);
}

/**
 * @deprecated
 * @param string $name Name of class to be loaded
 * @param string $type domain of the class, potential values:
 *                       core - located in /class/;
 *                       framework - located in /Frameworks/;
 *                       other - module class, located in /modules/[$type]/class/
 * @return boolean
 */
function xoops_load($name, $type = 'core')
{
    xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));
    // Note: most classes will autoload (i.e. $nav = new XoopsPageNav();) with no need for xoops_load()
    return XoopsLoad::load($name, $type);
}

/**
 * @deprecated
 * @param string $name     Name of language file to be loaded, without extension
 * @param string $domain   Module dirname; global language file will be loaded if $domain is set to 'global'
 *                          or not specified
 * @param string $language Language to be loaded, current language content will be loaded if not specified
 * @return  boolean
 */
function xoops_loadLanguage($name, $domain = '', $language = null)
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->loadLanguage($name, $domain, $language);
}

/**
 * @deprecated
 * @return array
 */
function xoops_getActiveModules()
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->getActiveModules();
}

/**
 * @deprecated
 * @return array
 */
function xoops_setActiveModules()
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->setActiveModules();
}

/**
 * @deprecated
 * @param string $dirname module directory name
 * @return bool
 */
function xoops_isActiveModule($dirname)
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->isActiveModule($dirname);
}

/**
 * @deprecated
 * @param bool $closehead true to close the header
 * @return void
 */
function xoops_header($closehead = true)
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));
    $xoops->simpleHeader($closehead);
}

/**
 * @deprecated
 * @return void
 */
function xoops_footer()
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));
    $xoops->simpleFooter();
}

/**
 * @deprecated
 * @param mixed  $msg   message
 * @param string $title title for message display
 * @return void
 */
function xoops_error($msg, $title = '')
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));
    echo $xoops->alert('error', $msg, $title);
}

/**
 * @deprecated
 * @param mixed  $msg   message
 * @param string $title title for message display
 * @return void
 */
function xoops_result($msg, $title = '')
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));
    echo $xoops->alert('info', $msg, $title);
}

/**
 * @deprecated
 * @param array   $hiddens  associative array of values used to complete confirmed action
 * @param string  $action   form action (URL)
 * @param string  $msg      message to display
 * @param string  $submit   submit button message
 * @param bool $addtoken true to add CSRF token
 * @return void
 */
function xoops_confirm($hiddens, $action, $msg, $submit = '', $addtoken = true)
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));
    echo $xoops->confirm($hiddens, $action, $msg, $submit, $addtoken);
}

/**
 * @deprecated
 * @param \DateTime|int $time       DateTime object or unix timestamp
 * @param string        $timeoffset unused
 * @return int
 */
function xoops_getUserTimestamp($time, $timeoffset = '')
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->getUserTimestamp($time);
}

/**
 * @deprecated
 * @param int    $time       unix timestamp
 * @param string $format     format code
 * @param string $timeoffset unused
 * @return string
 */
function formatTimestamp($time, $format = 'l', $timeoffset = '')
{
    xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return XoopsLocale::formatTimestamp($time, $format);
}

/**
 * @deprecated
 * @param int  $timestamp time stamp
 * @param null $userTZ    timezone
 * @return int
 */
function userTimeToServerTime($timestamp, $userTZ = null)
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->userTimeToServerTime($timestamp, $userTZ);
}

/**
 * @deprecated
 * @return string
 */
function xoops_makepass()
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->makePass();
}

/**
 * @deprecated
 * @param string $email    email address
 * @param bool   $antispam true to use spam harvester protection
 * @return false|string
 */
function checkEmail($email, $antispam = false)
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->checkEmail($email, $antispam);
}

/**
 * @deprecated
 * @param string $url URL
 * @return string
 */
function formatURL($url)
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->formatURL($url);
}

/**
 * @deprecated
 * @return string
 */
function xoops_getbanner()
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->getBanner();
}

/**
 * @deprecated
 * @param string $url               URL to redirect to
 * @param int    $time              time to wait (to allow reading message display)
 * @param string $message           message to display
 * @param bool   $addredirect       add xoops_redirect parameter with current URL to the redirect
 *                                   URL -  used for return from login redirect
 * @param bool   $allowExternalLink allow redirect to external URL
 * @return void
 */
function redirect_header($url, $time = 3, $message = '', $addredirect = true, $allowExternalLink = false)
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));
    $xoops->redirect($url, $time, $message, $addredirect, $allowExternalLink);
}

/**
 * @deprecated
 * @param string $key key
 * @return string
 */
function xoops_getenv($key)
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->getEnv($key);
}

/**
 * @deprecated
 * @param string $theme theme
 * @return string
 */
function xoops_getcss($theme = '')
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->getCss($theme);
}

/**
 * @deprecated
 * @return XoopsMailer|XoopsMailerLocal
 */
function xoops_getMailer()
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->getMailer();
}

/**
 * @deprecated
 * @param int $rank_id rank
 * @param int $posts   posts
 * @return array
 */
function xoops_getrank($rank_id = 0, $posts = 0)
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->service('userrank')->getUserRank(['rank' => $rank_id, 'posts' => $posts, 'uid' => 0])->getValue();
}

/**
 * @deprecated
 * @param string $str        string
 * @param int    $start      start position
 * @param int    $length     length
 * @param string $trimmarker elipsis
 * @return string
 */
function xoops_substr($str, $start, $length, $trimmarker = '...')
{
    xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return XoopsLocale::substr($str, $start, $length, $trimmarker);
}

/**
 * @deprecated
 * @param int $module_id module
 * @return boolean|null
 */
function xoops_notification_deletebymodule($module_id)
{
    xoops_functions_php_replaced_by_mod(__FUNCTION__, 'Notifications module');
}

/**
 * @deprecated
 * @param int $user_id user
 * @return boolean|null
 */
function xoops_notification_deletebyuser($user_id)
{
    xoops_functions_php_replaced_by_mod(__FUNCTION__, 'Notifications module');
}

/**
 * @deprecated
 * @param int $module_id module
 * @param int $category  category
 * @param int $item_id   item
 * @return boolean|null
 */
function xoops_notification_deletebyitem($module_id, $category, $item_id)
{
    xoops_functions_php_replaced_by_mod(__FUNCTION__, 'Notifications module');
}

/**
 * @deprecated
 * @param int      $module_id module
 * @param int|null $item_id   item
 * @return int
 */
function xoops_comment_count($module_id, $item_id = null)
{
    xoops_functions_php_replaced_by_mod(__FUNCTION__, 'Comments module');
}

/**
 * @deprecated
 * @param int $module_id module
 * @param int $item_id   item
 * @return boolean|null
 */
function xoops_comment_delete($module_id, $item_id)
{
    xoops_functions_php_replaced_by_mod(__FUNCTION__, 'Comments module');
}

/**
 * @deprecated
 * @param int      $module_id module id
 * @param string   $perm_name permission name
 * @param int|null $item_id   item
 * @return bool
 */
function xoops_groupperm_deletebymoditem($module_id, $perm_name, $item_id = null)
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->getHandlerGroupPermission()->deleteByModule($module_id, $perm_name, $item_id);
}

/**
 * @deprecated
 * @param string $text text
 * @return void
 */
function xoops_utf8_encode(&$text)
{
    xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));
    $text = XoopsLocale::utf8_encode($text);
}

/**
 * @deprecated
 * @param string $text text
 * @return void
 */
function xoops_convert_encoding(&$text)
{
    xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));
    $text = XoopsLocale::utf8_encode($text);
}

/**
 * @deprecated
 * @param string $text text
 * @return string
 */
function xoops_trim($text)
{
    xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return XoopsLocale::trim($text);
}

/**
 * @deprecated
 * @param string $option option name
 * @return string
 */
function xoops_getOption($option)
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->getOption($option);
}

/**
 * @deprecated
 * @param string           $option option name
 * @param string|int|array $type   option type
 * @return mixed
 */
function xoops_getConfigOption($option, $type = 'XOOPS_CONF')
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->getConfig($option);
}

/**
 * @deprecated
 * @param string $option config name
 * @param mixed  $new    value
 * @return void
 */
function xoops_setConfigOption($option, $new = null)
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));
    $xoops->setConfig($option, $new);
}

/**
 * @deprecated
 * @param string $option  option name
 * @param string $dirname module directory
 * @return mixed
 */
function xoops_getModuleOption($option, $dirname = '')
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->getModuleConfig($option, $dirname);
}

/**
 * @deprecated
 * @param string $url   URL
 * @param int    $debug unused
 * @return string
 */
function xoops_getBaseDomain($url, $debug = 0)
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->getBaseDomain($url);
}

/**
 * @deprecated
 * @param string $url URL
 * @return string
 */
function xoops_getUrlDomain($url)
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->getBaseDomain($url, true);
}

/**
 * function to update compiled template file in templates_c folder
 *
 * @param string $tpl_id    template id
 * @param bool   $clear_old unused
 * @return boolean
 */
function xoops_template_touch($tpl_id, $clear_old = true)
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));

    return $xoops->templateTouch($tpl_id);
}

/**
 * Clear the module cache
 *
 * @param int $mid Module ID
 * @return void
 */
function xoops_template_clear_module_cache($mid)
{
    $xoops = xoops_functions_php_deprecated(__FUNCTION__, __FILE__, (__LINE__ + 1));
    $xoops->templateClearModuleCache($mid);
}
