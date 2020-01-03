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
 * usermenu module
 *
 * @copyright      2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         usermenu
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

interface UsermenuPluginInterface
{

    /**
     * Used to populate the Usermenu Block
     *
     * Your module can return several usermenu items
     *
     * Expects an array of arrays containing:
     *    name  : Name for the usermenu items, ex: Profile
     *    link  : Link for the usermenu items, ex: Xoops::getInstance()->url('modules/profile/userinfo.php');
     *
     * Optional:
     *    see http://getbootstrap.com/components/#glyphicons-glyphs
     *    icon    : A css class for glyphicon,  ex: glyphicon-sunglasses
     *    subMenu : Supports sub menus, just return an array of arrays containing name, link, and optionally an icon
     *
     * Example
     *    return [
     *              [
     *                  'name' => 'Profile',
     *                  'link' => '#'
     *              ],
     *              [
     *                  'name' => 'Edit profile',
     *                  'link' => '#',
     *                  'icon' => 'glyphicon-sunglasses',
     *                  'subMenu' => [
     *                                  [
     *                                      'name' => 'Edit password',
     *                                      'link' => '#'
     *                                  ],
     *                                  [
     *                                      'name' => 'Change Avatar',
     *                                      'link' => '#'
     *                                  ],
     *                               ]
     *              ],
     *          ];
     *
     * @return array
     */
    public function usermenu();
}
