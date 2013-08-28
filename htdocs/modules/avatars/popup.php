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
 * avatars extension
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         avatar
 * @since           2.6.0
 * @author          Mage Gr�gory (AKA Mage)
 * @version         $Id$
 */

include dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$xoops->disableErrorReporting();

$xoops->simpleHeader(false);

$criteria = new Criteria('avatar_type', 'S');
$tpl = new XoopsTpl();
$tpl->assign('avatars', Avatars::getInstance()->getHandlerAvatar()->getObjects($criteria, false, false));
$tpl->assign('closebutton', 1);
$tpl->display('module:avatars|avatars_popup.html');

$xoops->simpleFooter();