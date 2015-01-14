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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Laurent JEN (aka DuGris)
 * @version         $Id$
 */

interface SystemPluginInterface
{
    /**
     * Used to synchronize a user number of posts
     * Please return the number of posts the user as made in your module
     *
     * @param int $uid The uid of the user
     *
     * @return int Number of posts
     */
    public function userPosts($uid);

    /**
     * Used to populate the Waiting Block
     *
     * Expects an array containing:
     *    count : Number of waiting items,    ex: 3
     *    name  : Name for the waiting items, ex: Pending approval
     *    link  : Link for the waiting items, ex: Xoops::getInstance()->url('modules/comments/admin/main.php');
     *
     * @return array
     */
    public function waiting();

    /**
     * Used to populate backend
     *
     * @param int $limit : Number of item for backend
     *
     * Expects an array containing:
     *    title   : Title for the backend items
     *    link    : Link for the backend items
     *    content : content for the backend items
     *    date    : Date of the backend items
     *
     * @return array
     */
    public function backend($limit);

    /**
     * Used to populate the User Block
     *
     * Expects an array containing:
     *    name  : Name for the Link
     *    link  : Link relative to module
     *    image : Url of image to display, please use 16px*16px image
     *
     * @return array
     */
    public function userMenus();
}
