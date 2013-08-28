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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

class Xoops_Module_Helper
{
    /**
     * @param string $dirname
     *
     * @return bool|Xoops_Module_Helper_Abstract
     */
    static function getHelper($dirname = 'system')
    {
        static $modules = array();

        $dirname = strtolower($dirname);
        if (!isset($modules[$dirname])) {
            $modules[$dirname] = false;
            $xoops = Xoops::getInstance();
            if ($xoops->isActiveModule($dirname)) {
                //Load Module helper if available
                if (XoopsLoad::loadFile($xoops->path("modules/{$dirname}/class/helper.php"))) {
                    $className = ucfirst($dirname);
                    if (class_exists($className)) {
                        $class = new $className();
                        if ($class instanceof Xoops_Module_Helper_Abstract) {
                            $modules[$dirname] = $class::getInstance();
                        }
                    }
                } else {
                    //Create Module Helper
                    Xoops::getInstance()->registry()->set('module_helper_id', $dirname);
                    $class = Xoops_Module_Helper_Dummy::getInstance();
                    $class->setDirname($dirname);
                    $modules[$dirname] = $class;
                }
            }
        }
        return $modules[$dirname];
    }
}