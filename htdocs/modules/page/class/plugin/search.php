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
 * page module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id: $
 */

class PageSearchPlugin extends Xoops\Module\Plugin\PluginAbstract implements SearchPluginInterface
{
    public function search($queries, $andor, $limit, $start, $uid)
    {
        $xoops = Xoops::getInstance();
        global $xoopsDB;
        $sql = "SELECT content_id, content_title, content_shorttext, content_text, content_author, content_create FROM " . $xoopsDB->prefix("page_content") . " WHERE content_status != 0";

        if ($uid != 0) {
            $sql .= " AND content_author=" . intval($uid);
        }

        if (is_array($queries) && $count = count($queries)) {
            $sql .= " AND ((content_title LIKE '%$queries[0]%' OR content_text LIKE '%$queries[0]%' OR content_shorttext LIKE '%$queries[0]%')";

            for ($i=1; $i < $count; $i++) {
                $sql .= " $andor ";
                $sql .= "(content_title LIKE '%$queries[$i]%' OR content_text LIKE '%$queries[$i]%' OR content_shorttext LIKE '%$queries[$i]%')";
            }
            $sql .= ")";
        }
        $sql .= " ORDER BY content_create DESC";
        $result = $xoopsDB->queryF($sql, $limit, $start);

        $ret = array();
        $i = 0;
        while ($myrow = $xoopsDB->fetchArray($result)) {
            $ret[$i]["image"] = "images/logo_small.png";
            $ret[$i]["link"] = "viewpage.php?id=" . $myrow["content_id"];
            $ret[$i]["title"] = $myrow["content_title"];
            $ret[$i]["time"] = $myrow["content_create"];
            $ret[$i]["content"] = $myrow["content_text"] . $myrow["content_shorttext"];
            $ret[$i]["uid"] = $myrow["content_author"];
            $i++;
        }
        return $ret;
    }
}
