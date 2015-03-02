<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package     installer
 * @since       2.3.0
 * @author      Haruki Setoyama  <haruki@planewave.org>
 * @author      Kazumi Ono <webmaster@myweb.ne.jp>
 * @author      Skalpa Keo <skalpa@xoops.org>
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @author      DuGris (aka L. JEN) <dugris@frxoops.org>
 * @version     $Id$
 **/

defined('XOOPS_INSTALL') or die('XOOPS Custom Installation die');

/**
 * @param $config
 *
 * @return array
 */
function createConfigform($config)
{
    $xoops = Xoops::getInstance();
    $config_handler = $xoops->getHandlerConfig();
    //$xoops->config = $config_handler->getConfigsByCat(XOOPS_CONF);
    //$config =& $xoops->config;

    $ret = array();
    $confcount = count($config);

    for ($i = 0; $i < $confcount; ++$i) {
        $conf_catid = $config[$i]->getVar('conf_catid');
        if (!isset($ret[$conf_catid])) {
            $ret[$conf_catid] = new Xoops\Form\ThemeForm('', 'configs', 'index.php', 'post');
        }

        $title = Xoops_Locale::translate($config[$i]->getVar('conf_title'), 'system');

        switch ($config[$i]->getVar('conf_formtype')) {

            case 'textarea':
                $myts = MyTextSanitizer::getInstance();
                if ($config[$i]->getVar('conf_valuetype') == 'array') {
                    // this is exceptional.. only when value type is arrayneed a smarter way for this
                    $ele = ($config[$i]->getVar('conf_value') != '') ? new Xoops\Form\TextArea($title, $config[$i]->getVar('conf_name'), $myts->htmlspecialchars(implode('|', $config[$i]->getConfValueForOutput())), 5, 50) : new Xoops\Form\TextArea($title, $config[$i]->getVar('conf_name'), '', 5, 50);
                } else {
                    $ele = new Xoops\Form\TextArea($title, $config[$i]->getVar('conf_name'), $myts->htmlspecialchars($config[$i]->getConfValueForOutput()), 5, 100);
                }
                break;

            case 'select':
                $ele = new Xoops\Form\Select($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput());
                $options =& $config_handler->getConfigOptions(new Criteria('conf_id', $config[$i]->getVar('conf_id')));
                $opcount = count($options);
                for ($j = 0; $j < $opcount; ++$j) {
                    $optval = Xoops_Locale::translate($options[$j]->getVar('confop_value'), 'system');
                    $optkey = Xoops_Locale::translate($options[$j]->getVar('confop_name'), 'system');
                    $ele->addOption($optval, $optkey);
                }
                break;

            case 'select_multi':
                $ele = new Xoops\Form\Select($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput(), 5, true);
                $options =& $config_handler->getConfigOptions(new Criteria('conf_id', $config[$i]->getVar('conf_id')));
                $opcount = count($options);
                for ($j = 0; $j < $opcount; ++$j) {
                    $optval = Xoops_Locale::translate($options[$j]->getVar('confop_value'), 'system');
                    $optkey = Xoops_Locale::translate($options[$j]->getVar('confop_name'), 'system');
                    $ele->addOption($optval, $optkey);
                }
                break;

            case 'yesno':
                $ele = new Xoops\Form\RadioYesNo($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput(), XoopsLocale::YES, XoopsLocale::NO);
                break;

            case 'theme':
            case 'theme_multi':
                $ele = ($config[$i]->getVar('conf_formtype') != 'theme_multi')
                    ? new Xoops\Form\Select($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput())
                    : new Xoops\Form\Select($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput(), 5, true);
                $dirlist = XoopsLists::getThemesList();
                if (!empty($dirlist)) {
                    asort($dirlist);
                    $ele->addOptionArray($dirlist);
                }
                break;

            case 'tplset':
                $ele = new Xoops\Form\Select($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput());
                $tplset_handler = $xoops->getHandlerTplset();
                $tplsetlist = $tplset_handler->getNameList();
                asort($tplsetlist);
                foreach ($tplsetlist as $key => $name) {
                    $ele->addOption($key, $name);
                }
                break;

            case 'timezone':
                $ele = new Xoops\Form\SelectTimeZone($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput());
                break;

            case 'language':
                $ele = new Xoops\Form\SelectLanguage($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput());
                break;

            case 'locale':
                $ele = new Xoops\Form\SelectLocale($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput());
                break;

            case 'startpage':
                $ele = new Xoops\Form\Select($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput());
                $module_handler = $xoops->getHandlerModule();
                $criteria = new CriteriaCompo(new Criteria('hasmain', 1));
                $criteria->add(new Criteria('isactive', 1));
                $moduleslist =& $module_handler->getNameList($criteria, true);
                $moduleslist['--'] = XoopsLocale::NONE;
                $ele->addOptionArray($moduleslist);
                break;

            case 'group':
                $ele = new Xoops\Form\SelectGroup($title, $config[$i]->getVar('conf_name'), false, $config[$i]->getConfValueForOutput(), 1, false);
                break;

            case 'group_multi':
                $ele = new Xoops\Form\SelectGroup($title, $config[$i]->getVar('conf_name'), false, $config[$i]->getConfValueForOutput(), 5, true);
                break;

            // RMV-NOTIFY - added 'user' and 'user_multi'
            case 'user':
                $ele = new Xoops\Form\SelectUser($title, $config[$i]->getVar('conf_name'), false, $config[$i]->getConfValueForOutput(), 1, false);
                break;

            case 'user_multi':
                $ele = new Xoops\Form\SelectUser($title, $config[$i]->getVar('conf_name'), false, $config[$i]->getConfValueForOutput(), 5, true);
                break;

            case 'module_cache':
                $module_handler = $xoops->getHandlerModule();
                $modules = $module_handler->getObjectsArray(new Criteria('hasmain', 1), true);
                $currrent_val = $config[$i]->getConfValueForOutput();
                $cache_options = array(
                    '0'       => XoopsLocale::NO_CACHE,
                    '30'      => sprintf(XoopsLocale::F_SECONDS, 30),
                    '60'      => XoopsLocale::ONE_MINUTE,
                    '300'     => sprintf(XoopsLocale::F_MINUTES, 5),
                    '1800'    => sprintf(XoopsLocale::F_MINUTES, 30),
                    '3600'    => XoopsLocale::ONE_HOUR,
                    '18000'   => sprintf(XoopsLocale::F_HOURS, 5),
                    '86400'   => XoopsLocale::ONE_DAY,
                    '259200'  => sprintf(XoopsLocale::F_DAYS, 3),
                    '604800'  => XoopsLocale::ONE_WEEK,
                    '2592000' => XoopsLocale::ONE_MONTH
                );
                if (count($modules) > 0) {
                    $ele = new Xoops\Form\ElementTray($title, '<br />');
                    foreach (array_keys($modules) as $mid) {
                        $c_val = isset($currrent_val[$mid]) ? intval($currrent_val[$mid]) : null;
                        $selform = new Xoops\Form\Select($modules[$mid]->getVar('name'), $config[$i]->getVar('conf_name') . "[$mid]", $c_val);
                        $selform->addOptionArray($cache_options);
                        $ele->addElement($selform);
                        unset($selform);
                    }
                } else {
                    $ele = new Xoops\Form\Label($title, SystemLocale::NO_MODULE_TO_CACHE);
                }
                break;

            case 'site_cache':
                $ele = new Xoops\Form\Select($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput());
                $ele->addOptionArray(array(
                    '0'       => XoopsLocale::NO_CACHE,
                    '30'      => sprintf(XoopsLocale::F_SECONDS, 30),
                    '60'      => XoopsLocale::ONE_MINUTE,
                    '300'     => sprintf(XoopsLocale::F_MINUTES, 5),
                    '1800'    => sprintf(XoopsLocale::F_MINUTES, 30),
                    '3600'    => XoopsLocale::ONE_HOUR,
                    '18000'   => sprintf(XoopsLocale::F_HOURS, 5),
                    '86400'   => XoopsLocale::ONE_DAY,
                    '259200'  => sprintf(XoopsLocale::F_DAYS, 3),
                    '604800'  => XoopsLocale::ONE_WEEK,
                    '2592000' => XoopsLocale::ONE_MONTH
                ));
                break;

            case 'password':
                $myts = MyTextSanitizer::getInstance();
                $ele = new Xoops\Form\Password($title, $config[$i]->getVar('conf_name'), 50, 255, $myts->htmlspecialchars($config[$i]->getConfValueForOutput()));
                break;

            case 'color':
                $myts = MyTextSanitizer::getInstance();
                $ele = new Xoops\Form\ColorPicker($title, $config[$i]->getVar('conf_name'), $myts->htmlspecialchars($config[$i]->getConfValueForOutput()));
                break;

            case 'hidden':
                $myts = MyTextSanitizer::getInstance();
                $ele = new Xoops\Form\Hidden($config[$i]->getVar('conf_name'), $myts->htmlspecialchars($config[$i]->getConfValueForOutput()));
                break;

            case 'textbox':
            default:
                $myts = MyTextSanitizer::getInstance();
                $ele = new Xoops\Form\Text($title, $config[$i]->getVar('conf_name'), 50, 255, $myts->htmlspecialchars($config[$i]->getConfValueForOutput()));
                break;
        }

        if ($config[$i]->getVar('conf_desc') != '') {
            $ele->setDescription(Xoops_Locale::translate($config[$i]->getVar('conf_desc'), 'system'));
        }
        $ret[$conf_catid]->addElement($ele);

        $hidden = new Xoops\Form\Hidden('conf_ids[]', $config[$i]->getVar('conf_id'));
        $ret[$conf_catid]->addElement($hidden);

        unset($ele);
        unset($hidden);
    }
    return $ret;
}

/**
 * @param XoopsConfigItem $config
 *
 * @return Xoops\Form\ThemeForm[]
 */
function createThemeform(XoopsConfigItem $config)
{
    $title = $config->getVar('conf_desc') == '' ? Xoops_Locale::translate($config->getVar('conf_title'), 'system') : Xoops_Locale::translate($config->getVar('conf_title'), 'system') . '<br /><br /><span>' . Xoops_Locale::translate($config->getVar('conf_desc'), 'system') . '</span>';
    $form_theme_set = new Xoops\Form\Select('', $config->getVar('conf_name'), $config->getConfValueForOutput(), 1, false);
    $dirlist = XoopsLists::getThemesList();
    if (!empty($dirlist)) {
        asort($dirlist);
        $form_theme_set->addOptionArray($dirlist);
    }

    $label_content = "";

    // read ini file for each theme
    foreach ($dirlist as $theme) {
        // set default value
        $theme_ini = array(
            'Name'        => $theme,
            'Description' => '',
            'Version'     => '',
            'Format'      => '',
            'Author'      => '',
            'Demo'        => '',
            'Url'         => '',
            'Download'    => '',
            'W3C'         => '',
            'Licence'     => '',
            'thumbnail'   => 'screenshot.gif',
            'screenshot'  => 'screenshot.png',
        );

        if ($theme == $config->getConfValueForOutput()) {
            $label_content .= "<div id='$theme' rel='theme' style='display:block;'>";
        } else {
            $label_content .= "<div id='$theme' rel='theme' style='display:none;'>";
        }
        if (file_exists(XOOPS_ROOT_PATH . "/themes/$theme/theme.ini")) {
            $theme_ini = parse_ini_file(XOOPS_ROOT_PATH . "/themes/$theme/theme.ini");
            if ($theme_ini['screenshot'] == '') {
                $theme_ini['screenshot'] = 'screenshot.png';
                $theme_ini['thumbnail'] = 'thumbnail.png';
            }
        }

        if ($theme_ini['screenshot'] != '' && file_exists(XOOPS_ROOT_PATH . "/themes/$theme/" . $theme_ini['screenshot'])) {
            $label_content .= "<img src='" . XOOPS_URL . "/themes/" . $theme . "/" . $theme_ini['screenshot'] . "' alt='Screenshot' />";
        } elseif ($theme_ini['thumbnail'] != '' && file_exists(XOOPS_ROOT_PATH . "/themes/$theme/" . $theme_ini['thumbnail'])) {
            $label_content .= "<img src='" . XOOPS_URL . "/themes/" . $theme . "/" . $theme_ini['thumbnail'] . "' alt='$theme' />";
        } else {
            $label_content .= THEME_NO_SCREENSHOT;
        }
        $label_content .= "</div>";
    }
    // read ini file for each theme

    $form_theme_set->setExtra("onchange='showThemeSelected(this)'");

    $form = new Xoops\Form\ThemeForm($title, 'themes', 'index.php', 'post');
    $form->addElement($form_theme_set);
    $form->addElement(new Xoops\Form\Label('', "<div id='screenshot'>" . $label_content . "</div>"));

    $form->addElement(new Xoops\Form\Hidden('conf_ids[]', $config->getVar('conf_id')));
    return array($form);
}
