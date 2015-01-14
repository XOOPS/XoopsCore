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
 * Users Manager
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Kazumi Ono (AKA onokazu)
 * @package         system
 * @subpackage      users
 * @version         $Id$
 */

$modversion['name']        = XoopsLocale::USERS;
$modversion['version']     = '1.0';
$modversion['description'] = SystemLocale::USERS_DESC;
$modversion['author']      = '';
$modversion['credits']     = 'The XOOPS Project; Francisco Burzi<br>'
    . '( http://phpnuke.org/ ); Kazumi Ono; Maxime Cointin (AKA Kraven30)';
$modversion['help']        = 'page=users';
$modversion['license']     = "GPL see LICENSE";
$modversion['official']    = 1;
$modversion['image']       = 'edituser.png';
$modversion['hasAdmin']    = 1;
$modversion['adminpath']   = 'admin.php?fct=users';
$modversion['category']    = XOOPS_SYSTEM_USER;
