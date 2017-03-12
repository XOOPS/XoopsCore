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
 * waiting module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         waiting
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

interface WaitingPluginInterface
{
    /**
     * Used to populate the Waiting Block
     *
     * Your module can return several waiting items
     *
     * Expects an array of arrays containing:
     *    count : Number of waiting items,    ex: 3
     *    name  : Name for the waiting items, ex: Pending approval
     *    link  : Link for the waiting items, ex: Xoops::getInstance()->url('modules/comments/admin/main.php');
     *
     * Optional:
     *    see http://getbootstrap.com/components/#glyphicons-glyphs
     *    icon  : A css class for glyphicon,  ex: glyphicon-sunglasses
     *
     * Example
     *    return [
     *              [
     *                  'count' => 1,
     *                  'name' => 'Inactive users',
     *                  'link' => '#'
     *              ],
     *              [
     *                  'count' => 2,
     *                  'name' => 'Pending Approval',
     *                  'link' => '#',
     *                  'icon' => 'glyphicon-sunglasses'
     *              ],
     *          ];
     *
     * @return array
     */
    public function waiting();
}
