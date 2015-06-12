<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Auth;

/**
 * Authentication class for Native XOOPS
 *
 * @category  Xoops
 * @package   Auth
 * @author    Pierre-Eric MENUET <pemphp@free.fr>
 * @copyright 2000-2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0
 */
abstract class AuthAbstract
{
    /**
     * @var use Xoops\Core\Database\Connection|null
     */
    protected $dao;

    /**
     * @var array
     */
    protected $errors;

    /**
     * @var string
     */
    protected $auth_method;

    /**
     * Authentication Service constructor
     *
     * @param \Xoops\Core\Database\Connection|null $dao database
     */
    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    /**
     * authenticate a user
     *
     * @param string      $uname user name
     * @param string|null $pwd   password
     *
     * @return bool true if authenticated, otherwise fales
     */
    abstract public function authenticate($uname, $pwd = null);

    /**
     * setErrors
     *
     * @param int    $err_no  error number
     * @param string $err_str error message
     *
     * @return void
     */
    public function setErrors($err_no, $err_str)
    {
        $this->errors[$err_no] = trim($err_str);
    }

    /**
     * return the errors for this object as an array
     *
     * @return array an array of errors
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * return the errors for this object as html
     *
     * @return string html listing the errors
     */
    public function getHtmlErrors()
    {
        $xoops = \Xoops::getInstance();
        $ret = '<br />';
        if ($xoops->getConfig('debug_mode') == 1 || $xoops->getConfig('debug_mode') == 2) {
            if (!empty($this->errors)) {
                foreach ($this->errors as $errstr) {
                    $ret .= $errstr . '<br/>';
                }
            } else {
                $ret .= \XoopsLocale::NONE . '<br />';
            }
            $ret .= sprintf(\XoopsLocale::F_USING_AUTHENTICATION_METHOD, $this->auth_method);
        } else {
            $ret .= \XoopsLocale::E_INCORRECT_LOGIN;
        }
        return $ret;
    }
}
