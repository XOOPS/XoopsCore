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
 * XOOPS form element of raw
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.6.0
 * @author          trabis <trabisdementia@gmail.com>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * This class has special treatment by xoopsforms
 * It will be render the row with value provided without wrapping in html
 */
class XoopsFormRaw extends XoopsFormElement
{

    /**
     * @param string $value
     */
    public function __construct($value = '')
    {
        $this->setValue($value);
    }

    /**
     * Prepare HTML for output
     *
     * @return string
     */
    public function render()
    {
        return $this->getValue();
    }
}