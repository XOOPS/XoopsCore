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
 * XOOPS Authentication base class
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      auth
 * @since           2.0
 * @author          Pierre-Eric MENUET <pemphp@free.fr>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 *
 * @package class
 * @subpackage auth
 * @description Authentication base class
 * @author Pierre-Eric MENUET <pemphp@free.fr>
 * @copyright copyright (c) 2000-2003 XOOPS.org
 */
class Xoops_Auth
{
    /**
     * @var XoopsConnection|null
     */
    protected $_dao;

    /**
     * @var array
     */
    protected $_errors;

    /**
     * @var string
     */
    protected $auth_method;

    /**
     * Authentication Service constructor
     *
     * @param XoopsConnection|null $dao
     */
    public function __construct($dao)
    {
        $this->_dao = $dao;
    }

    /**
     * need to be write in the derived class
     *
     * @param string $uname
     * @param string|null $pwd
     *
     * @return bool
     */
    public function authenticate($uname, $pwd = null)
    {
        $authenticated = false;
        return $authenticated;
    }

    /**
     * @param int $err_no
     * @param string $err_str
     * @return void
     */
    public function setErrors($err_no, $err_str)
    {
        $this->_errors[$err_no] = trim($err_str);
    }

    /**
     * return the errors for this object as an array
     *
     * @return array an array of errors
     * @access public
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * return the errors for this object as html
     *
     * @return string html listing the errors
     * @access public
     */
    public function getHtmlErrors()
    {
        $xoops = Xoops::getInstance();
        $ret = '<br />';
        if ($xoops->getConfig('debug_mode') == 1 || $xoops->getConfig('debug_mode') == 2) {
            if (!empty($this->_errors)) {
                foreach ($this->_errors as $errstr) {
                    $ret .= $errstr . '<br/>';
                }
            } else {
                $ret .= XoopsLocale::NONE . '<br />';
            }
            $ret .= sprintf(XoopsLocale::F_USING_AUTHENTICATION_METHOD, $this->auth_method);
        } else {
            $ret .= XoopsLocale::E_INCORRECT_LOGIN;
        }
        return $ret;
    }
}