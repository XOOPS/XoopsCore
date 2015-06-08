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

include_once __DIR__ . '/admin_header.php';

$moduleAdmin = new \Xoops\Module\Admin();
$moduleAdmin->displayNavigation('about.php');
$moduleAdmin->displayAbout(true);

include_once __DIR__ . '/admin_footer.php';
