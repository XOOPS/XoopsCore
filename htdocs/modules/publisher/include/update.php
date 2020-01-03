<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Core\Kernel\Handlers\XoopsModule;

/**
 * @copyright      2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 * @param                                         $version
 * @return bool
 */
function xoops_module_update_publisher(XoopsModule $module, $version)
{
    $gpermHandler = Xoops::getInstance()->getHandlerGroupPermission();

    return $gpermHandler->deleteByModule($module->getVar('mid'), 'item_read');
}
