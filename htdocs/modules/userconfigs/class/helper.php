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
 * Userconfigs
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

class Userconfigs extends Xoops\Module\Helper\HelperAbstract
{
    /**
     * Init the module
     *
     * @return null|void
     */
    public function init()
    {
        $this->setDirname('userconfigs');
    }

    /**
     * @return Userconfigs
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @return UserconfigsConfigHandler
     */
    public function getHandlerConfig()
    {
        return $this->getHandler('config');
    }

    /**
     * @return UserconfigsItemHandler
     */
    public function getHandlerItem()
    {
        return $this->getHandler('item');
    }

    /**
     * @return UserconfigsOptionHandler
     */
    public function getHandlerOption()
    {
        return $this->getHandler('option');
    }
}
