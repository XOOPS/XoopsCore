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
 * Protector
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         protector
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

require_once __DIR__ . '/postcheck_functions.php';

if (!defined('PROTECTOR_PRECHECK_INCLUDED')) {
    require __DIR__ . '/precheck.inc.php';
    return;
}

define('PROTECTOR_POSTCHECK_INCLUDED', 1);
if (!class_exists('\\Xoops\\Core\\Database\\Connection', false)) {
    return;
}
protector_postcheck();
