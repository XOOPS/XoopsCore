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
 * Extended User Profile
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         profile
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         d$
 */

$adminmenu = array();

$adminmenu[] = array(
    'title' => _PROFILE_MI_HOME,
    'link' => "admin/index.php",
    'icon' => 'home.png',
);
$adminmenu[] = array(
    'title' => _PROFILE_MI_USERS,
    'link' => "admin/user.php",
    'icon' => 'users.png',
);
$adminmenu[] = array(
    'title' => _PROFILE_MI_CATEGORIES,
    'link' => "admin/category.php",
    'icon' => 'category.png',
);
$adminmenu[] = array(
    'title' => _PROFILE_MI_FIELDS,
    'link' => "admin/field.php",
    'icon' => 'index.png',
);
$adminmenu[] = array(
    'title' => _PROFILE_MI_STEPS,
    'link' => "admin/step.php",
    'icon' => 'stats.png',
);
$adminmenu[] = array(
    'title' => _PROFILE_MI_PERMISSIONS,
    'link' => "admin/permissions.php",
    'icon' => 'permissions.png',
);
$adminmenu[] = array(
    'title' => _PROFILE_MI_ABOUT,
    'link' => 'admin/about.php',
    'icon' => 'about.png',
);
