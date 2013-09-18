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

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

class Xoops_Module_Helper_Dummy extends Xoops_Module_Helper_Abstract
{
    /**
     * Init the module
     *
     * @return null|void
     */
    public function init()
    {
    }

    /**
     * @return void|Xoops_Module_Helper_Dummy
     */
    static function getInstance() {
        return parent::getInstance();
    }

    /**
     * @param string $dirname dirname of the module
     */
    public function setDirname($dirname) {
        parent::setDirname($dirname);
    }

    /**
     * Set debug option on or off
     *
     * Made public to match Xmf module helper. Since this class is used
     * when a module doesn't have its own helper (yet) this is useful.
     *
     * @param bool $debug
     */
    public function setDebug($debug)
    {
        parent::setDebug($debug);
    }

    /**
     * Add a message to the module log
     *
     * Made public to match Xmf module helper. Since this class is used
     * when a module doesn't have its own helper (yet) this is useful.
     *
     * @param string $log
     */
    public function addLog($log)
    {
        $this->_addLog($log);
    }

}
