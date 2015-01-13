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
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

interface CommentsPluginInterface
{
    /**
     * You must return the unique identifier for the item
     * ex: return 'itemid';
     *
     * @return string
     */
    public function itemName();

    /**
     * You must return the page where the comment form is displayed
     * ex: return 'item.php';
     *
     * @return string
     */
    public function pageName();

    /**
     * @return array
     */
    public function extraParams();

    /**
     * This method will be executed upon successful post of an approved comment.
     * This includes comment posts by administrators, and change of comment status from 'pending' to 'active' state.
     * An CommentsComment object that has been approved will be passed as the first and only parameter.
     * This should be useful for example notifying the item submitter of a comment post.
     *
     * @param CommentsComment $comment
     *
     * @return void
     */
    public function approve(CommentsComment $comment);

    /**
     * This method will be executed whenever the total number of 'active' comments for an item is changed.
     *
     * @param int $item_id The unique ID of an item
     * @param int $total_num The total number of active comments
     *
     * @return void
     */
    public function update($item_id, $total_num);

    /**
     * This method will be executed whenever a new comment form is displayed.
     * You can set a default title for the comment and a header to be displayed on top of the form
     * ex: return array(
     *      'title' => 'My Article Title',
     *      'text' => 'Content of the article');
     *      'timestamp' => time(); //Date of the article in unix format
     *      'uid' => Id of the article author
     *
     * @param int $item_id The unique ID of an item
     *
     * @return array
     */
    public function itemInfo($item_id);

}

