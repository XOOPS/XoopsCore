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
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Class
 * @subpackage      Utils
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

class PublisherMenusPlugin extends Xoops\Module\Plugin\PluginAbstract implements MenusPluginInterface
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
        $helper = Publisher::getInstance();

        // Add the Submit new item button
        if ($helper->isUserAdmin() || ($helper->getConfig('perm_submit') && ($helper->xoops()->isUser() || $helper->getConfig('permissions_anon_post')))) {
            $ret[] = array(
                'name' => _MI_PUBLISHER_SUB_SMNAME1,
                'url' => "submit.php?op=add",
            );
        }

        // DISABLED since the internal search doesn't work
        // Add the Search button
        if (false && $helper->getConfig('perm_search')) {
            $ret[] = array(
                'name' => _MI_PUBLISHER_SUB_SMNAME3,
                'url' => "search.php",
            );
        }

        // Add the Archive button
        $ret[] = array(
            'name' => _MI_PUBLISHER_SUB_ARCHIVE,
            'url' => "archive.php",
        );
        return $ret;
    }
}
