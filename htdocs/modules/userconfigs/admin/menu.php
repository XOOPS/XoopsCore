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
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version         $Id: menu.php 8065 2011-11-06 02:02:32Z beckmi $
 */

// get path to icons
$pathIcon32='';
if (class_exists('Xmf\Module\Admin', true)) {
    $pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
}

$adminmenu=array();
// Index
$adminmenu[] = array(
    'title' => _MI_USERCONFIGS_ADMENU1 ,
    'link'  => 'admin/index.php' ,
    'icon'  => $pathIcon32.'home.png'
);
// About
$adminmenu[] = array(
    'title' => _MI_USERCONFIGS_ADMENU2 ,
    'link'  => 'admin/about.php' ,
    'icon'  => $pathIcon32.'about.png'
);
