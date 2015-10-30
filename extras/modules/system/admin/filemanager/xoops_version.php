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
 * Filemanager settings
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Maxime Cointin (AKA Kraven30)
 * @package     system
 * @version     $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

$modversion['name']        = _AM_SYSTEM_FILEMANAGER;
$modversion['version']     = '1.0';
$modversion['description'] = _AM_SYSTEM_FILEMANAGER_DESC;
$modversion['author']      = 'Maxime Cointin (AKA Kraven30)';
$modversion['credits']     = 'The XOOPS Project';
$modversion['help']        = 'page=filemanager';
$modversion['license']     = 'http://www.fsf.org/copyleft/gpl.html';
$modversion['official']    = 1;
$modversion['image']       = 'filemanager.png';

$modversion['hasAdmin']    = 1;
$modversion['adminpath']   = 'admin.php?fct=filemanager';
$modversion['category']    = XOOPS_SYSTEM_FILEMANAGER;
