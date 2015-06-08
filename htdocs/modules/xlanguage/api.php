<?php
/**
 * Xlanguage extension module
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       2010-2014 XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         xlanguage
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

$xoops = \Xoops::getInstance();
$helper = \Xoops\Module\Helper::getHelper('xlanguage');

$xlanguage = array();

if (XoopsLoad::fileExists($hnd_file = \XoopsBaseConfig::get('root-path') . '/modules/xlanguage/include/vars.php')) {
    include_once $hnd_file;
}
if (XoopsLoad::fileExists($hnd_file = \XoopsBaseConfig::get('root-path') . '/modules/xlanguage/include/functions.php')) {
    include_once $hnd_file;
}

$cookie_var = $xoops->registry()->get('XLANGUAGE_LANG_TAG');

$xlanguage['action'] = false;
if (!empty($_GET[$xoops->registry()->get('XLANGUAGE_LANG_TAG')])) {
    $cookie_path = '/';
    setcookie($cookie_var, $_GET[$xoops->registry()->get('XLANGUAGE_LANG_TAG')], time() + 3600 * 24 * 30, $cookie_path, '', 0);
    $xlanguage['lang'] = $_GET[$xoops->registry()->get('XLANGUAGE_LANG_TAG')];
} elseif (!empty($_COOKIE[$cookie_var])) {
    $xlanguage['lang'] = $_COOKIE[$cookie_var];
} elseif ($lang = xlanguage_detectLang()) {
    $xlanguage['lang'] = $lang;
} else {
    $xlanguage['lang'] = $helper->getConfig('language');
}

$helper->getHandlerLanguage()->loadConfig();

$lang = $helper->getHandlerLanguage()->getByName($xlanguage['lang']);

if (is_array($lang) && strcasecmp($lang['xlanguage_name'], $helper->getConfig('language'))) {
    $xoops->setConfig('locale', $lang['xlanguage_name']);
    if ($lang['xlanguage_charset']) {
        $xlanguage['charset'] = $lang['xlanguage_charset'];
    }
    if ($lang['xlanguage_code']) {
        $xlanguage['code'] = $lang['xlanguage_code'];
    }
}
unset($lang);

$xoops->registry()->set('XLANGUAGE_HANDLER', $helper->getHandlerLanguage());

if ($xlanguage['action']) {
    //if(CONV_REQUEST && (!empty($_GET)||!empty($_POST))){
    if (!empty($_POST)) {
        $in_charset = $xlanguage['charset'];
        $out_charset = $xlanguage['charset_base'];

        //$CONV_REQUEST_array=array('_GET', '_POST');
        $CONV_REQUEST_array = array('_POST');
        foreach ($CONV_REQUEST_array as $HV) {
            if (!empty(${$HV})) {
                ${$HV} = xlanguage_convert_encoding(${$HV}, $out_charset, $in_charset);
            }
            $GLOBALS['HTTP' . $HV . '_VARS'] = ${$HV};
        }
    }
    ob_start('xlanguage_encoding');
} else {
    ob_start('xlanguage_ml');
}

$xoops->registry()->set('XLANGUAGE', $xlanguage);
