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
 * Modules admin Manager
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Kazumi Ono (AKA onokazu)
 * @package         system
 * @subpackage      preferences
 * @version         $Id$
 */

// Get main instance
$xoops = Xoops::getInstance();
$system = System::getInstance();
$system_breadcrumb = SystemBreadcrumb::getInstance();

// Check users rights
if (!$xoops->isUser() || !$xoops->isModule() || !$xoops->user->isAdmin($xoops->module->mid())) {
    exit(XoopsLocale::E_NO_ACCESS_PERMISSION);
}

$conf_ids = array();
$session_expire = null;
$session_name = null;

// TODO this needs to go!
if (isset($_REQUEST)) {
    foreach ($_REQUEST as $k => $v) {
        ${$k} = $v;
    }
}
// Get Action type
$op = $system->cleanVars($_REQUEST, 'op', 'showmod', 'string');
// Setting type
$confcat_id = $system->cleanVars($_REQUEST, 'confcat_id', 0, 'int');
// Call Header
$xoops->header('admin:system/system_preferences.tpl');
// Define Stylesheet
$xoops->theme()->addStylesheet('modules/system/css/admin.css');
// Define scripts
$xoops->theme()->addScript('modules/system/js/admin.js');



//Display part
switch ($op) {

    case 'show':
    case 'showmod':
        $mod = $system->cleanVars($_GET, 'mod', 1, 'int');
        if (!$mod) {
            $xoops->redirect('admin.php?fct=preferences', 1);
        }

        $config_handler = $xoops->getHandlerConfig();
        $config = $config_handler->getConfigs(new Criteria('conf_modid', $mod));
        $count = count($config);
        if ($count < 1) {
            $xoops->redirect('admin.php?fct=preferences', 1);
        }

        $module_handler = $xoops->getHandlerModule();
        $module = $xoops->getModuleById($mod);
        $xoops->loadLanguage('modinfo', $module->getVar('dirname'));

        // Define Breadcrumb and tips
        $admin_page = new \Xoops\Module\Admin();
        $admin_page->addBreadcrumbLink(SystemLocale::CONTROL_PANEL, XOOPS_URL . '/admin.php', true);
        $admin_page->addBreadcrumbLink(XoopsLocale::PREFERENCES, $system->adminVersion('extensions', 'adminpath'));
        $admin_page->addBreadcrumbLink($module->getVar('name'));
        $admin_page->renderBreadcrumb();

        /* @var $form SystemPreferencesForm */
        $form = $xoops->getModuleForm(null, 'preferences');
        $form->getForm($config, $module);
        $xoops->tpl()->assign('form', $form->render());
        break;

    case 'save':
        if (!$xoops->security()->check()) {
            $xoops->redirect("admin.php?fct=preferences", 3, implode('<br />', $xoops->security()->getErrors()));
        }
        $xoopsTpl = new XoopsTpl();
        $count = count($conf_ids);
        $tpl_updated = false;
        $theme_updated = false;
        $startmod_updated = false;
        $lang_updated = false;
        $config_handler = $xoops->getHandlerConfig();
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $config = $config_handler->getConfig($conf_ids[$i]);
                $new_value = isset(${$config->getVar('conf_name')}) ? ${$config->getVar('conf_name')} : null;
                if (!is_null($new_value) && (is_array($new_value) || $new_value != $config->getVar('conf_value'))) {
                    // if language has been changed
                    if (!$lang_updated && $config->getVar('conf_catid') == XOOPS_CONF
                        && $config->getVar('conf_name') == 'locale'
                    ) {
                        $xoops->setConfig('locale', ${$config->getVar('conf_name')});
                        $lang_updated = true;
                    }

                    // if default theme has been changed
                    if (!$theme_updated && $config->getVar('conf_catid') == XOOPS_CONF
                        && $config->getVar('conf_name') == 'theme_set'
                    ) {
                        $member_handler = $xoops->getHandlerMember();
                        $member_handler->updateUsersByField('theme', ${$config->getVar('conf_name')});
                        $theme_updated = true;
                    }

                    // add read permission for the start module to all groups
                    if (!$startmod_updated && $new_value != '--'
                        && $config->getVar('conf_catid') == XOOPS_CONF
                        && $config->getVar('conf_name') == 'startpage'
                    ) {
                        $member_handler = $xoops->getHandlerMember();
                        $groups = $member_handler->getGroupList();
                        $moduleperm_handler = $xoops->getHandlerGroupperm();
                        $module_handler = $xoops->getHandlerModule();
                        $module = $xoops->getModuleByDirname($new_value);
                        foreach ($groups as $groupid => $groupname) {
                            if (!$moduleperm_handler->checkRight('module_read', $module->getVar('mid'), $groupid)) {
                                $moduleperm_handler->addRight('module_read', $module->getVar('mid'), $groupid);
                            }
                        }
                        $startmod_updated = true;
                    }

                    $config->setConfValueForInput($new_value);
                    $config_handler->insertConfig($config);
                }
                unset($new_value);
            }
        }

        if (!empty($use_mysession) && $xoops->getConfig('use_mysession') == 0 && $session_name != '') {
            setcookie(
                $session_name,
                session_id(),
                time() + (60 * intval($session_expire)),
                '/',
                XOOPS_COOKIE_DOMAIN,
                false
            );
        }

        // Clean cached files, may take long time
        // User register_shutdown_function to keep running after connection closes
        // so that cleaning cached files can be finished
        // Cache management should be performed on a separate page
        $options = array(1, 2, 3); //1 goes for smarty cache, 3 goes for xoops_cache
        register_shutdown_function(array(&$system, 'CleanCache'), $options);
        $xoops->preload()->triggerEvent('system.preferences.save');
        if (isset($redirect) && $redirect != '') {
            $xoops->redirect($redirect, 2, XoopsLocale::S_DATABASE_UPDATED);
        } else {
            $xoops->redirect("admin.php?fct=preferences", 2, XoopsLocale::S_DATABASE_UPDATED);
        }
        break;
}
// Call Footer
$xoops->footer();
