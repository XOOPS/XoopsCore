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
 * avatars module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         pdf
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 */

$adminmenu = array();

$adminmenu[] = array(
    'title' => XoopsLocale::HOME,
    'link'  => 'admin/index.php',
    'icon'  => 'home.png',
);

$adminmenu[] = array(
    'title' => XoopsLocale::ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => 'about.png',
);
