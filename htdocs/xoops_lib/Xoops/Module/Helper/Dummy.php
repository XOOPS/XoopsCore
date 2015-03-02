<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Module\Helper;
/**
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

class Dummy extends HelperAbstract
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
     * @return void|Xoops\Module\Helper\Dummy
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @param string $dirname dirname of the module
     */
    public function setDirname($dirname)
    {
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
