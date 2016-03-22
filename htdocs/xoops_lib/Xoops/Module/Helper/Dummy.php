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
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2011-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
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
     * @return void|Dummy
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @param string $dirname dirname of the module
     * @return void
     */
    public function setDirname($dirname)
    {
        parent::setDirname($dirname);
    }
}
