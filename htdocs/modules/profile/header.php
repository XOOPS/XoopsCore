<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Html\Menu\ItemList;
use Xoops\Html\Menu\Link;

/**
 * Extended User Profile
 *
 * @copyright       2000-2016 XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         profile
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 */
include dirname(dirname(__DIR__)) . '/mainfile.php';
$xoops = Xoops::getInstance();

$xoops->registry()->set('profile_breadcrumbs', new ItemList());
$xoops->registry()->get('profile_breadcrumbs')->addItem(
    new Link(['caption' => $xoops->module->getVar('name'), 'link' => $xoops->url('modules/profile/')])
);

//disable cache
$xoops->disableModuleCache();
