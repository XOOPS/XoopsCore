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
 *
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author          Andricq Nicolas (AKA MusS)
 * @package         System
 * @version         $Id$
 */

class SystemExtension extends SystemModule
{

    public function getExtension( $mod = '' )
    {
        $ret = array();
        $extension = self::getExtensionList();
        foreach( $extension as $list ) {
            /* @var $list XoopsModule */
            if ( $list->getInfo('install') ) {
                if ( !is_array( $list->getInfo('extension_module') ) ) {
                    $ret[] = $list;
                } else {
                    if ( array_search( $mod, $list->getInfo('extension_module')) !== false ){
                        $ret[] = $list;
                        //echo $list->getInfo('name') . is_array( $list->getInfo('extension_module') );
                    }
                }
                unset($list);
            }
        }

        return $ret;
    }

    /**
     * Return all extensions
     * @return array
     */
    public function getExtensionList()
    {
        // Get main instance
        $xoops = Xoops::getInstance();
        $module_handler = $xoops->getHandlerModule();
        $moduleperm_handler = $xoops->getHandlerGroupperm();

        $ret = array();
        $i = 0;
        foreach ($this->_list as $file) {
            $file = trim($file);
            if (XoopsLoad::fileExists(XOOPS_ROOT_PATH . '/modules/' . $file . '/xoops_version.php')) {
                clearstatcache();
                /* @var $module XoopsModule */
                $module = $module_handler->create();
                $module->loadInfoAsVar($file);
                if ($module->getInfo('extension')) {
                    if (in_array($file, $this->_mods)) {
                        $module->setInfo('install', true);
                        $extension = $module_handler->getByDirname($module->getInfo('dirname'));
                        $module->setInfo('mid', $extension->getVar('mid'));
                        $module->setInfo('update', XoopsLocale::formatTimestamp($extension->getVar('last_update'), 's'));
                        $module->setInfo('hasconfig', $module->getVar('hasconfig'));
                        if (round($module->getInfo('version'), 2) != $extension->getVar('version')) {
                            $module->setInfo('warning_update', true);
                        }
                        $sadmin = $moduleperm_handler->checkRight('module_admin', $module->getInfo('mid'), $xoops->user->getGroups());
                        if ($sadmin && ($module->getVar('hasnotification') || is_array($module->getInfo('config')) || is_array($module->getInfo('comments')))) {
                            $module->setInfo('link_pref', XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $module->getInfo('mid'));
                        }
                    } else {
                        $module->setInfo('install', false);
                    }
                    $module->setInfo('version', round($module->getInfo('version'), 2));
                    if (XoopsLoad::fileExists(XOOPS_ROOT_PATH . '/modules/' . $module->getInfo('dirname') . '/icons/logo_small.png')) {
                        $module->setInfo('logo_small', XOOPS_URL . '/modules/' . $module->getInfo('dirname') . '/icons/logo_small.png');
                    } else {
                        $module->setInfo('logo_small', XOOPS_URL . '/media/xoops/images/icons/16/default.png');
                    }
                    if (XoopsLoad::fileExists(XOOPS_ROOT_PATH . '/modules/' . $module->getInfo('dirname') . '/icons/logo_large.png')) {
                                            $module->setInfo('logo_large', XOOPS_URL . '/modules/' . $module->getInfo('dirname') . '/icons/logo_large.png');
                                        } else {
                                            $module->setInfo('logo_large', XOOPS_URL . '/media/xoops/images/icons/32/default.png');
                                        }
                    $module->setInfo('link_admin', XOOPS_URL . '/modules/' . $module->getInfo('dirname') . '/' . $module->getInfo('adminindex'));
                    $module->setInfo('options', $module->getAdminMenu());
                    $ret[] = $module;
                    unset($module);
                    $i++;
                }
            }
        }
        return $ret;
    }
    /**
     * @return array
     */
    public function getExtensionInstall()
    {
        // Get main instance
        $xoops = Xoops::getInstance();
        $module_handler = $xoops->getHandlerModule();

        $ret = array();
        $i = 0;
        foreach ($this->_list as $file) {
            if (XoopsLoad::fileExists(XOOPS_ROOT_PATH . '/modules/' . $file . '/xoops_version.php')) {
                clearstatcache();
                $file = trim($file);
                if (!in_array($file, $this->_mods)) {
                    /* @var $module XoopsModule */
                    $module = $module_handler->create();
                    $module->loadInfo($file);
                    if ($module->getInfo('extension')) {
                        $module->setInfo('mid', $i);
                        $module->setInfo('version', round($module->getInfo('version'), 2));
                        $ret[] = $module;
                        unset($module);
                        $i++;
                    }
                }
            }
        }
        return $ret;
    }
}

