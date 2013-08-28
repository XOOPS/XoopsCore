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
 * Authentication class factory
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
 * @package kernel
 * @subpackage auth
 * @description Authentication class factory
 * @author Pierre-Eric MENUET <pemphp@free.fr>
 * @copyright copyright (c) 2000-2005 XOOPS.org
 */
class Xoops_Auth_Factory
{
    /**
     * Get a reference to the only instance of authentication class
     *
     * if the class has not been instantiated yet, this will also take
     * care of that
     *
     * @param string $uname
     * @return Xoops_Auth|bool Reference to the only instance of authentication class
     */
    static function getAuthConnection($uname)
    {
        $xoops = Xoops::getInstance();
        static $auth_instance;
        if (!isset($auth_instance)) {
            /* @var $config_handler XoopsConfigHandler */
            $authConfig = $xoops->getConfigs();
            if (empty($authConfig['auth_method'])) { // If there is a config error, we use xoops
                $xoops_auth_method = 'xoops';
            } else {
                $xoops_auth_method = $authConfig['auth_method'];
            }
            // Verify if uname allow to bypass LDAP auth
            if (in_array($uname, $authConfig['ldap_users_bypass'])) {
                $xoops_auth_method = 'xoops';
            }

            if (!XoopsLoad::fileExists($file = dirname(__FILE__) . DIRECTORY_SEPARATOR . ucfirst($xoops_auth_method) . '.php')) {
                return false;
            }
            include_once $file;
            $class = 'Xoops_Auth_' . ucfirst($xoops_auth_method);
            if (!class_exists($class)) {
                trigger_error(XoopsLocale::EF_CLASS_NOT_FOUND, E_USER_ERROR);
                return false;
            }
            $dao = null;
            switch ($xoops_auth_method) {
                case 'xoops':
                    $dao = null;
                    break;
                case 'ldap':
                    $dao = null;
                    break;
                case 'ads':
                    $dao = null;
                    break;
            }
            $auth_instance = new $class($dao);
        }
        return $auth_instance;
    }
}