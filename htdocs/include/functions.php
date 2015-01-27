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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.0.0
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * @deprecated
 * @param string $name
 * @param mixed $optional
 * @return XoopsObjectHandler|XoopsPersistableObjectHandler|null
 */
function xoops_getHandler($name, $optional = false)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated('xoops_getHandler(\'' . $name . '\') is deprecated. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    $method = 'getHandler' . ucfirst(strtolower(trim($name)));
    return $xoops->$method($optional);
}

/**
 * @deprecated
 * @param string|null $name
 * @param string|null $module_dir
 * @param bool $optional
 * @return bool
 */
function xoops_getModuleHandler($name = null, $module_dir = null, $optional = false)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->getModuleHandler($name, $module_dir, $optional);
}

/**
 * @deprecated
 * @param string $name Name of class to be loaded
 * @param string $type domain of the class, potential values: core - locaded in /class/; framework - located in /Frameworks/; other - module class, located in /modules/[$type]/class/
 * @return boolean
 */
function xoops_load($name, $type = 'core')
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return XoopsLoad::load($name, $type);
}

/**
 * @deprecated
 * @param   string  $name       Name of language file to be loaded, without extension
 * @param   string  $domain     Module dirname; global language file will be loaded if $domain is set to 'global' or not specified
 * @param   string  $language   Language to be loaded, current language content will be loaded if not specified
 * @return  boolean
 * @todo    expand domain to multiple categories, e.g. module:system, framework:filter, etc.
 *
 */
function xoops_loadLanguage($name, $domain = '', $language = null)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->loadLanguage($name, $domain, $language);
}

/**
 * @deprecated
 * @return array
 */
function xoops_getActiveModules()
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->getActiveModules();
}

/**
 * @deprecated
 * @return array
 */
function xoops_setActiveModules()
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->setActiveModules();
}

/**
 * @deprecated
 * @param string $dirname
 * @return bool
 */
function xoops_isActiveModule($dirname)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->isActiveModule($dirname);
}

/**
 * @deprecated
 * @param bool $closehead
 * @return void
 */
function xoops_header($closehead = true)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    $xoops->simpleHeader($closehead);
}

/**
 * @deprecated
 * @return void
 */
function xoops_footer()
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    $xoops->simpleFooter();
}

/**
 * @deprecated
 * @param mixed $msg
 * @param string $title
 * @return void
 */
function xoops_error($msg, $title = '')
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    echo $xoops->alert('error', $msg, $title);
}

/**
 * @deprecated
 * @param mixed $msg
 * @param string $title
 * @return void
 */
function xoops_result($msg, $title = '')
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    echo $xoops->alert('info', $msg, $title);
}

/**
 * @deprecated
 * @param mixed $hiddens
 * @param mixed $action
 * @param mixed $msg
 * @param string $submit
 * @param bool $addtoken
 * @return void
 */
function xoops_confirm($hiddens, $action, $msg, $submit = '', $addtoken = true)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    $xoops->confirm($hiddens, $action, $msg, $submit, $addtoken);
}

/**
 * @deprecated
 * @param mixed$time
 * @param string $timeoffset
 * @return int
 */
function xoops_getUserTimestamp($time, $timeoffset = '')
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->getUserTimestamp($time, $timeoffset);
}

/**
 * @deprecated
 * @param int $time
 * @param string $format
 * @param string $timeoffset
 * @return string
 */
function formatTimestamp($time, $format = 'l', $timeoffset = '')
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return XoopsLocale::formatTimestamp($time, $format, $timeoffset);
}

/**
 * @deprecated
 * @param int $timestamp
 * @param null $userTZ
 * @return int
 */
function userTimeToServerTime($timestamp, $userTZ = null)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->userTimeToServerTime($timestamp, $userTZ);
}

/**
 * @deprecated
 * @return string
 */
function xoops_makepass()
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->makePass();
}

/**
 * @deprecated
 * @param string $email
 * @param bool $antispam
 * @return false|string
 */
function checkEmail($email, $antispam = false)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->checkEmail($email, $antispam);
}

/**
 * @deprecated
 * @param string $url
 * @return string
 */
function formatURL($url)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->formatURL($url);
}

/**
 * @deprecated
 * @return string
 */
function xoops_getbanner()
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->getBanner();
}

/**
 * @deprecated
 * @param string $url
 * @param int $time
 * @param string $message
 * @param bool $addredirect
 * @param bool $allowExternalLink
 * @return void
 */
function redirect_header($url, $time = 3, $message = '', $addredirect = true, $allowExternalLink = false)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    $xoops->redirect($url, $time, $message, $addredirect, $allowExternalLink);
}

/**
 * @deprecated
 * @param string $key
 * @return string
 */
function xoops_getenv($key)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->getEnv($key);
}

/**
 * @deprecated
 * @param string $theme
 * @return string
 */
function xoops_getcss($theme = '')
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->getCss($theme);
}

/**
 * @deprecated
 * @return XoopsMailer|XoopsMailerLocal
 */
function xoops_getMailer()
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->getMailer();
}

/**
 * @deprecated
 * @param int $rank_id
 * @param int $posts
 * @return array
 */
function xoops_getrank($rank_id = 0, $posts = 0)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->getRank($rank_id, $posts);
}

/**
 * @deprecated
 * @param string $str
 * @param int $start
 * @param int $length
 * @param string $trimmarker
 * @return string
 */
function xoops_substr($str, $start, $length, $trimmarker = '...')
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return XoopsLocale::substr($str, $start, $length, $trimmarker);
}

/**
 * @deprecated
 * @param int $module_id
 * @return boolean|null
 */
function xoops_notification_deletebymodule($module_id)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. Use Notifications module instead.');
}

/**
 * @deprecated
 * @param int $user_id
 * @return boolean|null
 */
function xoops_notification_deletebyuser($user_id)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. Use Notifications module instead.');
}

/**
 * @deprecated
 * @param $module_id
 * @param $category
 * @param $item_id
 * @return boolean|null
 */
function xoops_notification_deletebyitem($module_id, $category, $item_id)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. Use Notifications module instead.');
}

/**
 * @deprecated
 * @param int $module_id
 * @param int|null $item_id
 * @return int
 */
function xoops_comment_count($module_id, $item_id = null)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. Use comments module instead.');
}

/**
 * @deprecated
 * @param int $module_id
 * @param int $item_id
 * @return boolean|null
 */
function xoops_comment_delete($module_id, $item_id)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. Use comments module instead.');
}

/**
 * @deprecated
 * @param int $module_id
 * @param string $perm_name
 * @param int|null $item_id
 * @return bool
 */
function xoops_groupperm_deletebymoditem($module_id, $perm_name, $item_id = null)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->getHandlerGroupperm()->deleteByModule($module_id, $perm_name, $item_id);
}

/**
 * @deprecated
 * @param $text
 * @return void
 */
function xoops_utf8_encode(&$text)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    XoopsLocale::utf8_encode($text);
}

/**
 * @deprecated
 * @param $text
 * @return void
 */
function xoops_convert_encoding(&$text)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    XoopsLocale::utf8_encode($text);
}

/**
 * @deprecated
 * @param $text
 * @return string
 */
function xoops_trim($text)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return XoopsLocale::trim($text);
}

/**
 * @deprecated
 * @param $option
 * @return string
 */
function xoops_getOption($option)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->getOption($option);
}

/**
 * @deprecated
 * @param string $option
 * @param string|int|array $type
 * @return mixed
 */
function xoops_getConfigOption($option, $type = 'XOOPS_CONF')
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->getConfig($option);
}

/**
 * @deprecated
 * @param $option
 * @param null $new
 * @return void
 */
function xoops_setConfigOption($option, $new = null)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    $xoops->setConfig($option, $new);
}

/**
 * @deprecated
 * @param string $option
 * @param string $dirname
 * @return mixed
 */
function xoops_getModuleOption($option, $dirname = '')
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->getModuleConfig($option, $dirname);
}

/**
 * @deprecated
 * @param $url
 * @param int $debug
 * @return string
 */
function xoops_getBaseDomain($url, $debug = 0)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->getBaseDomain($url);
}

/**
 * @deprecated
 * @param string $url
 * @return string
 */
function xoops_getUrlDomain($url)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    return $xoops->getBaseDomain($url, true);
}

/**
 * function to update compiled template file in templates_c folder
 *
 * @param string $tpl_id
 * @param boolean $clear_old
 * @return boolean
 */
function xoops_template_touch($tpl_id, $clear_old = true)
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
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
    $xoops = Xoops::getInstance();
    $xoops->deprecated(__FUNCTION__ . ' is deprecated since XOOPS 2.6.0. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    $xoops->templateClearModuleCache($mid);
}


// general php version compatibility functions

if (!function_exists('http_response_code')) {
    /**
     * http_response_code - conditionally defined for PHP <5.4
     *
     * Taken from stackoverflow answer by "dualed,"  see:
     * http://stackoverflow.com/questions/3258634/php-how-to-send-http-response-code
     *
     * @param int $newcode HTTP response code
     *
     * @return int old status code
     */
    function http_response_code($newcode = null)
    {
        static $code = 200;
        if ($newcode !== null) {
            header('X-PHP-Response-Code: '.$newcode, true, $newcode);
            if (!headers_sent()) {
                $code = $newcode;
            }
        }
        return $code;
    }
}

// ENT_SUBSTITUTE flag for htmlspecialchars() added in PHP 5.4
if (!defined('ENT_SUBSTITUTE')) {
    define('ENT_SUBSTITUTE', 0);
}

/**
 * xhtmlspecialchars - a customized version of PHP htmlspecialchars to set the
 * flags and encoding parameters to the most approriate values for general use
 * in a UTF-8 environment.
 *
 * This function forces UTF-8 encoding, the ENT_QUOTES flag, and will also use
 * the ENT_SUBSTITUTE flag if it is available. This gives the optimal features
 * for 5.3 and in >5.4
 *
 * @param string $string              string to be encoded
 * @param integer  $dummy_flags         ignored - for call compatibility only
 * @param mixed  $dummy_encoding      ignored - for call compatibility only
 * @param mixed  $dummy_double_encode ignored - for call compatibility only
 *
 * @return string with any charachters with special significance in HTML converted to entities
 */
function xhtmlspecialchars($string, $dummy_flags = 0, $dummy_encoding = '', $dummy_double_encode = true)
{
    return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
