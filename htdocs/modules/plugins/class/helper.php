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
 * plugins module
 *
 * @copyright      2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         plugins
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

class Plugins extends Xoops\Module\Helper\HelperAbstract
{
    /**
     * Init the module
     *
     * @return null|void
     */
    public function init()
    {
        $this->setDirname('plugins');
    }

    /**
     * @return Plugins|\Xoops\Module\Helper\HelperAbstract
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @return PluginsPluginHandler
     */
    public function getHandlerPlugin()
    {
        return $this->getHandler('plugin');
    }

}
