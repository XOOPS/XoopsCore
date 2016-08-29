<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xmf\Request;
use Xoops\Core\XoopsTpl;

/**
 * smilies module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         smilies
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 */

include dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

$xoops = Xoops::getInstance();
$xoops->logger()->quiet();

$target = Request::getString('target', '');
$xoops->simpleHeader(false);
if ($target && preg_match('/^[0-9a-z_]*$/i', $target)) {
    $tpl = new XoopsTpl();
    $tpl->assign('target', $target);
    $tpl->assign('smileys', $xoops->getModuleHandler('smiley', 'smilies')->getActiveSmilies(false));
    $tpl->assign('closebutton', 1);
    $tpl->display('module:smilies/smilies_smiley.tpl');
}
$xoops->simpleFooter();
