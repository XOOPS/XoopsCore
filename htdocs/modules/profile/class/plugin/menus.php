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
 *  Publisher class
 *
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Class
 * @subpackage      Utils
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 */

class ProfileMenusPlugin extends Xoops\Module\Plugin\PluginAbstract implements MenusPluginInterface
{
    /**
     * expects an array of array containing:
     * name,      Name of the submenu
     * url,       Url of the submenu relative to the module
     * ex: return array(0 => array(
     *      'name' => _MI_PUBLISHER_SUB_SMNAME3;
     *      'url' => "search.php";
     *    ));
     *
     * @return array
     */
    public function subMenus()
    {
        $ret = array();
        if (\Xoops::getInstance()->isUser()) {
            $ret[] = array('name' => _PROFILE_MI_EDITACCOUNT, 'url' => "edituser.php");
            $ret[] = array('name' => _PROFILE_MI_PAGE_SEARCH, 'url' => "search.php");
            $ret[] = array('name' => _PROFILE_MI_CHANGEPASS,  'url' => "changepass.php");
        }
        return $ret;
    }
}
