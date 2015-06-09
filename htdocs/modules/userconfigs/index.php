<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Request;

/**
 * User configs
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         userconfigs
 * @version         $Id$
 */

include dirname(dirname(__DIR__)) . '/mainfile.php';

$xoops = Xoops::getInstance();
$helper = Userconfigs::getInstance();

if (!$xoops->isUser()) {
    $xoops->redirect($xoops->url('index.php'), 3, _MD_USERCONFIGS_NOACCESS);
}

$mid = Request::getInt('mid', 0);
$uid = $xoops->user->getVar('uid');
$op = Request::getCmd('op', 'show');

$xoops->header('module:userconfigs/list.tpl');
$xoops->tpl()->assign('welcome', sprintf(_MD_USERCONFIGS_WELCOME, XoopsUserUtility::getUnameFromId($xoops->user->getVar('uid'), true)));

//Display part
switch ($op) {
    case 'showmod':
        if (!$mid) {
            $xoops->redirect($xoops->url('index.php'), 3, _MD_USERCONFIGS_NOMOD);
        }

        $module = $xoops->getModuleById($mid);

        /* @var $plugin UserconfigsPluginInterface */
        if (!$plugin = \Xoops\Module\Plugin::getPlugin($module->getVar('dirname'), 'userconfigs')) {
            $xoops->redirect($xoops->url('index.php'), 3, _MD_USERCONFIGS_NOPLUGIN);
        }
        $config_handler = $helper->getHandlerConfig();
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('conf_modid', $module->getVar('mid')));
        $criteria->add(new Criteria('conf_uid', $uid));
        $configs = $config_handler->getConfigs($criteria);

        if (empty($configs) || (count($configs) != count($plugin->configs()))) {
            foreach (array_keys($configs) as $i) {
                $config_handler->deleteConfig($configs[$i]);
            }
            $config_handler->createDefaultUserConfigs($uid, $module);
            $configs = $config_handler->getConfigs($criteria);
        }

        /* @var $form UserconfigsConfigsForm */
        $form = $helper->getForm(null, 'configs');
        $form->getForm($configs, $module);
        $xoops->tpl()->assign('form', $form->render());
        /* @var $form UserconfigsModulesForm */
        $form = $helper->getForm(null, 'modules');
        $form->getModulesForm($module);
        $xoops->tpl()->assign('modules_form', $form->render());
        break;
    case 'show':
        if (!$mid) {
            $module = null;
        } else {
            $module = $xoops->getModuleById($mid);
        }

        /* @var $form UserconfigsModulesForm */
        $form = $helper->getForm(null, 'modules');
        $form->getModulesForm($module);
        $xoops->tpl()->assign('modules_form', $form->render());
        break;
    case 'save':
        if (!$xoops->security()->check()) {
            $helper->redirect("index.php", 3, implode('<br />', $xoops->security()->getErrors()));
        }

        $conf_ids = array();
        if (isset($_REQUEST)) {
            foreach ($_REQUEST as $k => $v) {
                ${$k} = $v;
            }
        }

        $xoopsTpl = new XoopsTpl();
        $count = count($conf_ids);
        $config_handler = $helper->getHandlerConfig();
        if ($count > 0) {
            for ($i = 0; $i < $count; ++$i) {
                $config = $config_handler->getConfig($conf_ids[$i]);
                $new_value = isset(${$config->getVar('conf_name')}) ? ${$config->getVar('conf_name')} : null;
                if (!is_null($new_value) && (is_array($new_value) || $new_value != $config->getVar('conf_value'))) {
                    $config->setConfValueForInput($new_value);
                    $config_handler->insertConfig($config);
                }
                unset($new_value);
            }
        }
        $xoops->redirect("index.php?mid={$mid}&amp;op=showmod", 2, _MD_USERCONFIGS_UPDATED);
        break;
}
$xoops->footer();
