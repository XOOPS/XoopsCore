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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         xlanguage
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('XOOPS root path not defined');

class Xlanguage extends Xoops_Module_Helper_Abstract
{
    /**
     * Init the module
     *
     * @return null|void
     */
    public function init()
    {        if (XoopsLoad::fileExists($hnd_file = XOOPS_ROOT_PATH . '/modules/xlanguage/include/vars.php')) {            include_once $hnd_file;
        }

        if (XoopsLoad::fileExists($hnd_file = XOOPS_ROOT_PATH . '/modules/xlanguage/include/functions.php')) {
            include_once $hnd_file;
        }
        $this->setDirname('xlanguage');
    }

    /**
     * @return Xlanguage
     */
    static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @return XlanguageXlanguageHandler
     */
    public function getHandlerLanguage()
    {
        return $this->getHandler('xlanguage');
    }
}