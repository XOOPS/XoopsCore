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

require_once __DIR__ . '/precheck_functions.php';

define('PROTECTOR_PRECHECK_INCLUDED', 1);
define('PROTECTOR_VERSION', file_get_contents(__DIR__ . '/version.txt'));

// set $_SERVER['REQUEST_URI'] for IIS
if (empty($_SERVER['REQUEST_URI'])) { // Not defined by IIS
    // Under some configs, IIS makes SCRIPT_NAME point to php.exe :-(
    if (!($_SERVER['REQUEST_URI'] = @$_SERVER['PHP_SELF'])) {
        $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
    }
    if (isset($_SERVER['QUERY_STRING'])) {
        $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
    }
}

protector_precheck();
