<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Module\Plugin;

/**
 * @copyright   2013-2015 XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      trabis <lusopoemas@gmail.com>
 */
class PluginAbstract
{
    /**
     * __construct
     *
     * @param string $dirname module dirname
     */
    public function __construct($dirname)
    {
        $xoops = \Xoops::getInstance();
        $xoops->loadLanguage('modinfo', $dirname);
    }
}
