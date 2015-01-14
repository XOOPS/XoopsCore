<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\CriteriaCompo;

/**
 * System help page
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Andricq Nicolas (AKA MusS)
 * @package     system
 * @version     $Id$
 */

// Include header
include __DIR__ . '/header.php';

$xoops = Xoops::getInstance();
$system = System::getInstance();

$page = $system->cleanVars($_REQUEST, 'page', '', 'string');
$mid = $system->cleanVars($_REQUEST, 'mid', 0, 'int');

// Define main template
$xoops->header('admin:system/system_help.tpl');
// Define Stylesheet
$xoops->theme()->addStylesheet('modules/system/css/admin.css');
$xoops->theme()->addStylesheet('modules/system/css/help.css');
// Define Breadcrumb and tips
$system_breadcrumb->addLink(XoopsLocale::HELP, 'help.php');

if ($mid > 0) {
    $module = $xoops->getModuleById($mid);

    $system_breadcrumb->addLink($module->getVar('name'), 'help.php?mid=' . $module->getVar('mid', 's'));
    $system_breadcrumb->addLink(system_adminVersion($page, 'name'));
    $system_breadcrumb->render();

    if ($module->getVar('dirname', 'e') == 'system') {

        $admin_dir = XOOPS_ROOT_PATH . '/modules/system/admin';
        $dirlist = XoopsLists::getDirListAsArray($admin_dir);

        foreach ($dirlist as $directory) {
            if (XoopsLoad::fileExists($file = $admin_dir . '/' . $directory . '/xoops_version.php')) {

                require $file;
                unset($file);

                if ($modversion['help']) {
                    $help['name'] = system_adminVersion($directory, 'name');
                    $help['link'] = 'help.php?mid=' . $mid . '&amp;' . system_adminVersion($directory, 'help');

                    $xoops->tpl()->appendByRef('help', $help);
                    unset($help);
                }
                unset($modversion);
            }
        }
        unset($dirlist);
    } else {
        $list_help = array();
        $listed_mods[0] = $module->toArray();
        $helplist = $module->getInfo('helpsection');
        $j=0;
        if (is_array($helplist)) {
            foreach ($helplist as $helpitem) {
                if (($helpitem['name'] != '') && ($helpitem['link'] != '')) {
                    $list_help[$j]['name'] = $helpitem['name'];
                    $list_help[$j]['link'] = 'help.php?mid=' . $mid . '&amp;' . $helpitem['link'];
                    $j++;
                }
            }
            $listed_mods[0]['help_page'] = $list_help;
            $xoopsTpl->assign('list_mods', $listed_mods);
        }
        unset ($helplist);
        if (( $module->getInfo('help') != '' ) && ($j == 0)) {
            $help['name'] = $module->getInfo('name');
            $help['link'] = 'help.php?mid=' . $mid . '&amp;' . $module->getInfo('help');
            $xoopsTpl->appendByRef('help', $help);
        }
        unset($help);
    }

    $xoops->loadLanguage('help', $module->getVar('dirname'));
    $xoops->tpl()->assign('module', $module);
    $xoops->tpl()->assign('modname', $module->getVar('name'));
    $xoops->tpl()->assign('moddirname', $module->getVar('dirname', 'e'));

    if ($page != '') {
        // Call template
        if ($helpfile =
            XoopsLoad::fileExists(
                XOOPS_ROOT_PATH . '/modules/' . $module->getVar('dirname', 'e')
                . '/locale/' . XoopsLocale::getLocale() . '/help/' . $page . '.html'
            )
        ) {
            $helpcontent =
                $xoops->tpl()->fetch(
                    XOOPS_ROOT_PATH . '/modules/' . $module->getVar('dirname', 'e')
                    . '/locale/' . XoopsLocale::getLocale() . '/help/' . $page . '.html'
                );
        } elseif (XoopsLoad::fileExists(
            XOOPS_ROOT_PATH . '/modules/' . $module->getVar('dirname', 'e')
            . '/language/' . $xoopsConfig['language'] . '/help/' . $page . '.html'
        )) {
            $helpcontent = $xoops->tpl()->fetch(
                XOOPS_ROOT_PATH . '/modules/' . $module->getVar('dirname', 'e')
                . '/language/' . $xoopsConfig['language'] . '/help/' . $page . '.html'
            );
        } else {
            if (XoopsLoad::fileExists(
                XOOPS_ROOT_PATH . '/modules/' . $module->getVar('dirname', 'e')
                . '/language/english/help/' . $page . '.html'
            )) {
                $helpcontent = $xoops->tpl()->fetch(
                    XOOPS_ROOT_PATH . '/modules/' . $module->getVar('dirname', 'e')
                    . '/language/english/help/' . $page . '.html'
                );
            } else {
                $xoops->tpl()->assign('load_error', 1);
            }
        }
        if ($module->getVar('dirname', 'e') != 'system') {
            $xoops->tpl()->assign('help_module', true);
        }
        $xoops->tpl()->assign('helpcontent', $helpcontent);
    } else {
        if ($helpfile = XoopsLoad::fileExists(
            XOOPS_ROOT_PATH . '/modules/' . $module->getVar('dirname', 'e')
            . '/locale/' . XoopsLocale::getLocale() . '/help/module_index.html'
        )) {
            $helpcontent = $xoops->tpl()->fetch(
                XOOPS_ROOT_PATH . '/modules/' . $module->getVar('dirname', 'e')
                . '/locale/' . XoopsLocale::getLocale() . '/help/module_index.html'
            );
        } elseif (XoopsLoad::fileExists(
            XOOPS_ROOT_PATH . '/modules/' . $module->getVar('dirname', 'e')
            . '/language/' . $xoops->getConfig('language') . '/help/module_index.html'
        )) {
            $helpcontent = $xoops->tpl()->fetch(
                XOOPS_ROOT_PATH . '/modules/' . $module->getVar('dirname', 'e')
                . '/language/' . $xoops->getConfig('language') . '/help/module_index.html'
            );
        } else {
            $helpcontent = '<p>' . $module->getInfo('description') . '</p>';
        }
        $xoops->tpl()->assign('helpcontent', $helpcontent);
    }
} else {
    $system_breadcrumb->render();
    $criteria = new CriteriaCompo();
    $criteria->setOrder('weight');
    // Get all installed modules
    $installed_mods = $xoops->getHandlerModule()->getObjectsArray($criteria);
    $listed_mods = array();
    $i = 0;
    $j = 0;
    foreach ($installed_mods as $module) {
        /* @var $module XoopsModule */
        $list_help = array();
        $listed_mods[$i] = $module->getValues();
        $listed_mods[$i]['image'] = $module->getInfo('image');
        $listed_mods[$i]['adminindex'] = $module->getInfo('adminindex');
        $listed_mods[$i]['version'] = round($module->getVar('version') / 100, 2);
        $listed_mods[$i]['last_update'] = XoopsLocale::formatTimestamp($module->getVar('last_update'), 'm');
        $listed_mods[$i]['author'] = $module->getInfo('author');
        $listed_mods[$i]['credits'] = $module->getInfo('credits');
        $listed_mods[$i]['license'] = $module->getInfo('license');
        $listed_mods[$i]['description'] = $module->getInfo('description');

        if ($module->getVar('dirname', 'e') == 'system') {
            $admin_dir = XOOPS_ROOT_PATH . '/modules/system/admin';
            $dirlist = XoopsLists::getDirListAsArray($admin_dir);

            foreach ($dirlist as $directory) {
                if (XoopsLoad::fileExists($file = $admin_dir . '/' . $directory . '/xoops_version.php')) {

                    require $file;
                    unset($file);

                    if ($modversion['help']) {
                        $list_help[$j]['name'] = system_adminVersion($directory, 'name');
                        $list_help[$j]['link'] = 'help.php?mid=' . $module->getVar('mid', 'e')
                            . '&amp;' . system_adminVersion($directory, 'help');
                    }
                    unset($modversion);
                    $j++;
                }
            }
            unset($dirlist);
        } else {
            $helplist = $module->getInfo('helpsection');
            $k=0;

            // Only build the list if one has been defined.
            if (is_array($helplist)) {
                foreach ($helplist as $helpitem) {
                    if (($helpitem['name'] != '') && ($helpitem['link'] != '')) {
                        $list_help[$j]['name'] = $helpitem['name'];
                        $list_help[$j]['link'] = 'help.php?mid=' . $module->getVar('mid', 'e')
                            . '&amp;' . $helpitem['link'];
                        $j++;
                        $k++;
                    }
                }
            }
            unset($helplist);

            // If there is no help section ($k=0), and a lone help parameter has been defined.
            if (( $module->getInfo('help') != '' ) && ($k == 0)) {
                $list_help[$j]['name'] = $module->getInfo('name');
                $list_help[$j]['link'] = 'help.php?mid=' . $module->getVar('mid', 'e')
                    . '&amp;' . $module->getInfo('help');
            }
        }
        $listed_mods[$i]['help_page'] = $list_help;
        if ($module->getInfo('help') == '') {
            unset($listed_mods[$i]);
        }
        unset($list_help);
        unset($module);
        $i++;
        $j++;
    }
    $xoops->tpl()->assign('list_mods', $listed_mods);

    if (XoopsLoad::fileExists(
        XOOPS_ROOT_PATH . '/modules/system/language/'
        . $xoops->getConfig('language') . '/help/help_center.html'
    )) {
        $helpcontent = $xoops->tpl()->fetch(
            XOOPS_ROOT_PATH . '/modules/system/language/' . $xoops->getConfig('language')
            . '/help/help_center.html'
        );
    } else {
        $helpcontent = '<div id="non-modhelp">' . SystemLocale::WELCOME_TO_XOOPS_HELP_CENTER . '</div>';
    }

    $xoops->tpl()->assign('helpcontent', $helpcontent);
}
$xoops->footer();
