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
 * Blocks admin Manager
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Kazumi Ono (AKA onokazu)
 * @package         system
 * @subpackage      blocksadmin
 * @version         $Id$
 */

$modversion['name']        = XoopsLocale::BLOCKS;
$modversion['version']     = '1.0';
$modversion['description'] = SystemLocale::BLOCKS_DESC;
$modversion['author']      = '';
$modversion['credits']     = 'The XOOPS Project; The MPN SE Project; Andricq Nicolas (AKA MusS)';
$modversion['help']        = 'page=blocksadmin';
$modversion['license']     = "GPL see LICENSE";
$modversion['official']    = 1;
$modversion['image']       = 'blocks.png';

$modversion['hasAdmin']    = 1;
$modversion['adminpath']   = 'admin.php?fct=blocksadmin';
$modversion['category']    = XOOPS_SYSTEM_BLOCK;
