<?php
/**
 * XOOPS Version Definition
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright      2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         kernel
 * @version         $Id$
 */

/**
 * Define XOOPS version
 * @todo This should be eliminated in favor of \Xoops::VERSION, but it is still required in installer
 */
$XoopsIncludeVersionString  = class_exists('\Xoops', false) ? \Xoops::VERSION : 'XOOPS 2.6.0';
define('XOOPS_VERSION', $XoopsIncludeVersionString);
