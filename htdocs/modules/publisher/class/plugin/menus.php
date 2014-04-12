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
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
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
        $i = 0;
        // Add the Submit new item button
        if ($helper->isUserAdmin() || ($helper->getConfig('perm_submit') && ($helper->xoops()->isUser() || $helper->getConfig('permissions_anon_post')))) {
            $i++;
            $ret[$i]['name'] = _MI_PUBLISHER_SUB_SMNAME1;
            $ret[$i]['url'] = "submit.php?op=add";
        }

        // Add the Search button
        if ($helper->getConfig('perm_search')) {
            $i++;
            $ret[$i]['name'] = _MI_PUBLISHER_SUB_SMNAME3;
            $ret[$i]['url'] = "search.php";
        }

        // Add the Archive button
        $i++;
        $ret[$i]['name'] = _MI_PUBLISHER_SUB_ARCHIVE;
        $ret[$i]['url'] = "archive.php";

        return $ret;
    }
}
