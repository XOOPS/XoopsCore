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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Richard Griffith <richard@geekwright.com>
 */

defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

$adminmenu=array();
// Index
$adminmenu[] = array(
    'title' => _MI_SCHEMATOOL_ADMIN_INDEX ,
    'link'  => 'admin/index.php' ,
    'icon'  => 'home.png'
);
// About
$adminmenu[] = array(
    'title' => _MI_SCHEMATOOL_ADMIN_ABOUT ,
    'link'  => 'admin/about.php' ,
    'icon'  => 'about.png'
);
// SchemaTool
$adminmenu[] = array(
    'title' => _MI_SCHEMATOOL_ADMIN_TOOL ,
    'link'  => 'admin/schematool.php' ,
    'icon'  => 'schematool.png'
);
