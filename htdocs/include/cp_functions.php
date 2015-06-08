<?php
/**
 * XOOPS control panel functions
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.0.0
 * @version         $Id$
 */

define('XOOPS_CPFUNC_LOADED', 1);

/**
 * CP Header
 *
 * @return void
 */
function xoops_cp_header()
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated('xoops_cp_header() is deprecated. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    $xoops->header($xoops->getOption('template_main'));
}

/**
 * CP Footer
 *
 * @return void
 */
function xoops_cp_footer()
{
    $xoops = Xoops::getInstance();
    $xoops->deprecated('xoops_cp_footer() is deprecated. See how to replace it in file ' . __FILE__ . ' line ' . __LINE__);
    $xoops->footer();
}
