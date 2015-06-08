<?php
/**
 * Xcaptcha extension module
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         xcaptcha
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

include __DIR__ . '/header.php';

$xoops = Xoops::getInstance();

switch ($op) {
    case 'save':
        if (!$xoops->security()->check()) {
            $xoops->redirect('index.php', 5, implode(',', $xoops->security()->getErrors()));
        }
        if ($type == 'config') {
            $config = $xcaptcha_handler->VerifyData();
            $xcaptcha_handler->writeConfig('captcha.config', $config);
            $xoops->redirect('index.php?type=config', 5, _AM_XCAPTCHA_SAVED);
        } else {
            if ($xcaptcha_handler->loadPluginHandler($type)) {
                $config = $xcaptcha_handler->Pluginhandler->VerifyData();
                $xcaptcha_handler->writeConfig('captcha.config.' . $type, $config);
                $xoops->redirect('index.php?type=' . $type, 5, _AM_XCAPTCHA_SAVED);
            }
        }
        break;

    case 'default':
    default:
        $type = isset($type) ? $type : 'config';

        $xoops->header();
        $xoops->theme()->addStylesheet('modules/xcaptcha/css/moduladmin.css');

        $admin_page = new \Xoops\Module\Admin();
        if ($type == 'config') {
            $admin_page->displayNavigation('index.php?type=config');
            $admin_page->addInfoBox(_AM_XCAPTCHA_FORM);
            $form = $xoops->getModuleForm($xcaptcha_handler, 'captcha', 'xcaptcha');
            $admin_page->addInfoBoxLine($form->render());
        } else {
            if ($plugin = $xcaptcha_handler->loadPluginHandler($type)) {
                $title = constant('_XCAPTCHA_FORM_' . strtoupper($type));
                $form = $xoops->getModuleForm($plugin, $type, 'xcaptcha');
                $admin_page->addInfoBox($title);
                $admin_page->addInfoBoxLine($form->render());
            } else {
                $xoops->redirect('index.php', 5, _AM_XCAPTCHA_ERROR);
            }
        }
        $admin_page->displayIndex();

        break;
}
include __DIR__ . '/footer.php';
