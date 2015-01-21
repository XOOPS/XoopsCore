<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Request;

/**
 * XOOPS global search
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package         core
 * @since           2.6.0
 * @author          Kazumi Ono (AKA onokazu)
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 * @todo            Modularize; Both search algorithms and interface will be redesigned
 */

include __DIR__ . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$uri = '';
if ($xoops->isActiveModule('search')) {
    foreach (Request::get() as $k => $v) {
        $uri .= urlencode($k) . '=' . urlencode($v) . '&';
    }
    $xoops->redirect("modules/search/index.php?{$uri}", 0);
} else {
    $xoops->redirect("index.php", 10, 'Oops, Please install search module!!!!');
}
