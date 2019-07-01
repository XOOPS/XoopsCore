<?php
/*
 You may not change or alter any portion of this comment or credits of supporting
 developers from this source code or any supporting source code which is considered
 copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * phpmailer module
 *
 * @copyright XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package   phpmailer
 * @author    Richard Griffith <richard@geekwright.com>
 */
$adminmenu = [];

$adminmenu[] = [
    'title' => XoopsLocale::HOME,
    'link' => 'admin/index.php',
    'icon' => 'home.png',
];

$adminmenu[] = [
    'title' => XoopsLocale::ABOUT,
    'link' => 'admin/about.php',
    'icon' => 'about.png',
];
