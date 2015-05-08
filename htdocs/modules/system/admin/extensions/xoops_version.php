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
 * Plugin manager config
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Andricq Nicolas (AKA MusS)
 * @package         system
 * @version         $Id$
 */

$modversion['name']        = XoopsLocale::EXTENSIONS;
$modversion['version']     = '1.0';
$modversion['description'] = SystemLocale::EXTENSIONS_DESC;
$modversion['author']      = '';
$modversion['credits']     = 'The XOOPS Project; Andricq Nicolas (AKA MusS)';
$modversion['help']        = 'page=extensions';
$modversion['license']     = "GPL see LICENSE";
$modversion['official']    = 1;
$modversion['image']       = 'extensions.png';
$modversion['hasAdmin']    = 1;
$modversion['adminpath']   = 'admin.php?fct=extensions';
$modversion['category']    = XOOPS_SYSTEM_MODULE;
