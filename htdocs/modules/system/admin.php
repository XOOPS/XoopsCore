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

/**
 * System admin
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Kazumi Ono (AKA onokazu)
 * @package     system
 * @version     $Id$
 */

// Include header
include __DIR__ . '/header.php';

// Get main instance
$xoops = Xoops::getInstance();
$system = System::getInstance();
$system_breadcrumb = SystemBreadcrumb::getInstance();

$error = false;
if ($system->checkRight()) {
    if (isset($fct) && $fct != '') {
        $fct = preg_replace("/[^a-z0-9_\-]/i", "", $fct);
        if (XoopsLoad::fileExists($file = $xoops->path('modules/' . $xoopsModule->getVar('dirname', 'n') . '/admin/' . $fct . '/xoops_version.php'))) {
            // Load language file
            //system_loadLanguage($fct, $xoopsModule->getVar('dirname', 'n'));
            // Include Configuration file
            require $file;
            unset($file);

            // Get System permission handler
            $sysperm_handler = $xoops->getHandlerGroupperm();

            $category = !empty($modversion['category']) ? intval($modversion['category']) : 0;
            unset($modversion);

            if ($category > 0) {
                $group = $xoopsUser->getGroups();
                if (in_array(XOOPS_GROUP_ADMIN, $group) || false != $sysperm_handler->checkRight('system_admin', $category, $group, $xoopsModule->getVar('mid'))) {
                    if (XoopsLoad::fileExists($file = $xoops->path('modules/' . $xoopsModule->getVar('dirname', 'n') . '/admin/' . $fct . '/main.php'))) {
                        include_once $file;
                        unset($file);
                    } else {
                        $error = true;
                    }
                } else {
                    $error = true;
                }
            } elseif ($fct == 'version') {
                if (XoopsLoad::fileExists($file = $xoops->path('modules/' . $xoopsModule->getVar('dirname', 'n') . '/admin/version/main.php'))) {
                    include_once $file;
                    unset($file);
                } else {
                    $error = true;
                }
            } else {
                $error = true;
            }
        } else {
            $error = true;
        }
    } else {
        $error = true;
    }
}

if (false != $error) {
    $op = $system->cleanVars($_REQUEST, 'op', '', 'string');
    if ($op == 'system_activate') {
        \Xoops::getInstance()->logger()->quiet();
        $part = $system->cleanVars($_REQUEST, 'type', '', 'string');
        $config_handler = $xoops->getHandlerConfig();

        $criteria = new Criteria('conf_name', 'active_' . $part);
        $configs = $config_handler->getConfigs($criteria);
        foreach ($configs as $conf) {
            /* @var $conf XoopsConfigItem */
            if ($conf->getVar('conf_name') == 'active_' . $part) {
                $conf->setVar('conf_value', !$conf->getVar('conf_value'));
                $config_handler->insertConfig($conf);
            }
        }
        exit;
    }
    // Define main template
    $xoops->header('admin:system/system_index.tpl');
    // Define Stylesheet
    $xoops->theme()->addStylesheet('modules/system/css/admin.css');
    // Define scripts
    $xoops->theme()->addBaseScriptAssets('@jquery.');
    $xoops->theme()->addBaseScriptAssets('modules/system/js/admin.js');
    // Define Breadcrumb and tips
    $admin_page = new \Xoops\Module\Admin();
    $admin_page->addBreadcrumbLink(SystemLocale::CONTROL_PANEL, XOOPS_URL . '/admin.php', true);
    $admin_page->addBreadcrumbLink(SystemLocale::SYSTEM_CONFIGURATION);
    $admin_page->renderBreadcrumb();
    $admin_page->addTips(SystemLocale::TIPS_MAIN);
    $admin_page->renderTips();
    $groups = $xoopsUser->getGroups();
    $all_ok = false;
    if (!in_array(XOOPS_GROUP_ADMIN, $groups)) {
        $sysperm_handler = $xoops->getHandlerGroupperm();
        $ok_syscats = $sysperm_handler->getItemIds('system_admin', $groups);
    } else {
        $all_ok = true;
    }

    $admin_dir = XOOPS_ROOT_PATH . '/modules/system/admin';
    $dirlist = XoopsLists::getDirListAsArray($admin_dir);
    $inactive_section = array('blocksadmin', 'groups', 'modulesadmin', 'preferences', 'tplsets', 'extensions', 'users', 'services');
    foreach ($dirlist as $directory) {
        if (XoopsLoad::fileExists($file = $admin_dir . '/' . $directory . '/xoops_version.php')) {
            require $file;
            unset($file);

            if ($modversion['hasAdmin']) {
                if ($xoops->getModuleConfig('active_' . $directory)) {
                    $category = isset($modversion['category']) ? intval($modversion['category']) : 0;
                    if (false != $all_ok || in_array($modversion['category'], $ok_syscats)) {
                        $menu['file'] = $directory;
                        $menu['title'] = trim($modversion['name']);
                        $menu['desc'] = str_replace('<br />', ' ', $modversion['description']);
                        $menu['icon'] = $modversion['image'];
                        $menu['status'] = true;
                    }
                } else {
                    $category = isset($modversion['category']) ? intval($modversion['category']) : 0;
                    if (false != $all_ok || in_array($modversion['category'], $ok_syscats)) {
                        $menu['file'] = $directory;
                        $menu['title'] = trim($modversion['name']);
                        $menu['desc'] = str_replace('<br />', ' ', $modversion['description']);
                        $menu['icon'] = $modversion['image'];
                        $menu['status'] = false;
                    }
                }
                if (!in_array($directory, $inactive_section)) {
                    $menu['used'] = true;
                }
                switch ($directory) {
                    case 'groups':
                        $groups_Handler = $xoops->getHandlerGroup();
                        $groups = $groups_Handler->getCount();
                        $menu['infos'] = sprintf(SystemLocale::F_GROUPS_SPAN, $groups);
                        break;
                    case 'users':
                        $member_handler = $xoops->getHandlerUser();
                        $member = $member_handler->getCount();
                        $menu['infos'] = sprintf(SystemLocale::F_USERS_SPAN, $member);
                        break;
                }
                $xoops->tpl()->appendByRef('menu', $menu);
                unset($menu);
            }
            unset($modversion);
        }
    }
    unset($dirlist);
    $xoops->footer();
}
