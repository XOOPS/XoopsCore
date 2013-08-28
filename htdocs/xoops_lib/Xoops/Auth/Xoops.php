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
 * Authentication class for Native XOOPS
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package class
 * @subpackage auth
 * @since 2.0
 * @author Pierre-Eric MENUET <pemphp@free.fr>
 * @version $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 *
 * @package class
 * @subpackage auth
 * @description Authentication class for Native XOOPS
 * @author Pierre-Eric MENUET <pemphp@free.fr>
 * @copyright copyright (c) 2000-2003 XOOPS.org
 */
class Xoops_Auth_Xoops extends Xoops_Auth
{
    /**
     * Authentication Service constructor
     *
     * @param XoopsConnection|null $dao
     */
    public function __construct(XoopsDatabase $dao = null)
    {
        $this->_dao = $dao;
        $this->auth_method = 'xoops';
    }

    /**
     * Authenticate user
     *
     * @param string $uname
     * @param string $pwd
     * @return bool
     */
    public function authenticate($uname, $pwd = null)
    {
        $xoops = Xoops::getInstance();
        $member_handler = $xoops->getHandlerMember();
        $user = $member_handler->loginUser($uname, $pwd);
        if ($user == false) {
            $this->setErrors(1, XoopsLocale::E_INCORRECT_LOGIN);
        }
        return $user;
    }
}