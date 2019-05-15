<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Core\Kernel\Handlers\XoopsGroupPermHandler;

/**
 *  Publisher class
 *
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

include_once dirname(__DIR__) . '/include/common.php';

class PublisherGroupPermHandler extends XoopsGroupPermHandler
{
    /**
     * Check permission
     *
     * @param string    $gperm_name
     * @param int       $gperm_itemid
     * @param array|int $gperm_groupid
     * @param int       $gperm_modid
     * @param bool      $trueifadmin
     *
     * @return bool
     */
    public function checkRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid = 1, $trueifadmin = true)
    {
        $trueifadmin = false;
        return  parent::checkRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid, $trueifadmin);
    }
}
