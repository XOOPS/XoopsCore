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
 * @copyright    XOOPS Project (http://xoops.org)
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package      debugbar
 * @since
 * @author       XOOPS Development Team
 */

$adminmenu=array();

$adminmenu[] = array(
    'title'	=> _MI_LOGGER_ADMENU1 ,
    'link'	=> 'admin/index.php' ,
    'icon'	=> 'home.png'
) ;

$adminmenu[] = array(
    'title'	=> _MI_LOGGER_ADMENU2 ,
    'link'	=> 'admin/about.php' ,
    'icon'	=> 'about.png'
) ;

$adminmenu[] = array(
    'title'	=> _MI_LOGGER_ADMENU3 ,
    'link'	=> 'admin/permissions.php' ,
    'icon'	=> 'permissions.png'
) ;
