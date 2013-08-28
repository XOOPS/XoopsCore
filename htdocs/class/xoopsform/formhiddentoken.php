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
 * XOOPS form element of hidden token
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * A hidden token field
 */
class XoopsFormHiddenToken extends XoopsFormHidden
{
    /**
     * Constructor
     *
     * @param string $name
     * @param int $timeout
     */
    public function __construct($name = 'XOOPS_TOKEN', $timeout = 0)
    {
        $xoops = Xoops::getInstance();
        parent::__construct($name . '_REQUEST', $xoops->security()->createToken($timeout, $name));
    }
}