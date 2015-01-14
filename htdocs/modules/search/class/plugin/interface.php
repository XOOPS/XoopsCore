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
 * @copyright 2013-2015 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author    trabis <lusopoemas@gmail.com>
 */

interface SearchPluginInterface
{
    /**
     * search method
     *
     * @param string[] $queries search term strings
     * @param string   $andor   $queries relation, either 'AND' or 'OR'
     * @param int      $limit   maximum number of matches to return
     * @param int      $start   offset in full set of matches
     * @param type     $uid     user id to limit search
     *
     * @return array of items matching criteria
     *               Each item is an associative array with the following keys:
     *                   'title'   => result title
     *                   'content' => content preview
     *                   'link'    => url to full content
     *                   'time'    => unix timestamp of item
     *                   'uid'     => associated user id
     *                   'image'   => icon to display by result
     *
     * @todo the result item should be a defined object
     */
    public function search($queries, $andor, $limit, $start, $uid);
}
