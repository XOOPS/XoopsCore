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
 * Object stats handler class.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      model
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Object stats handler class.
 *
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 *
 * {@link XoopsObjectAbstract}
 */

class XoopsModelStats extends XoopsModelAbstract
{
    /**
     * count objects matching a condition
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} to match
     * @return int count of objects
     */
    public function getCount(CriteriaElement $criteria = null)
    {
        $field = '';
        $groupby = false;
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            if ($criteria->getGroupby() != '') {
                $groupby = true;
                $field = $criteria->getGroupby() . ", ";
            }
        }
        $sql = "SELECT {$field} COUNT(*) FROM `{$this->handler->table}`";
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ($criteria->getGroupby() != '') {
                $sql .= ' GROUP BY (' . $criteria->getGroupby() . ')';
            }
        }
        $result = $this->handler->db->query($sql);
        if (!$result) {
            return 0;
        }
        if ($groupby == false) {
            list ($count) = $this->handler->db->fetchRow($result);
            return $count;
        } else {
            $ret = array();
            while (list ($id, $count) = $this->handler->db->fetchRow($result)) {
                $ret[$id] = $count;
            }
            return $ret;
        }
    }

    /**
     * get counts matching a condition
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} to match
     * @return array of counts
     */
    public function getCounts(CriteriaElement $criteria = null)
    {
        $ret = array();
        $sql_where = '';
        $limit = null;
        $start = null;
        $groupby_key = $this->handler->keyName;
        if (isset($criteria) && is_subclass_of($criteria, "criteriaelement")) {
            $sql_where = $criteria->renderWhere();
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
            if ($groupby = $criteria->getGroupby()) {
                $groupby_key = $groupby;
            }
        }
        $sql = "SELECT {$groupby_key}, COUNT(*) AS count" . " FROM `{$this->handler->table}`" . " {$sql_where}" . " GROUP BY {$groupby_key}";
        if (!$result = $this->handler->db->query($sql, $limit, $start)) {
            return $ret;
        }
        while (list ($id, $count) = $this->handler->db->fetchRow($result)) {
            $ret[$id] = $count;
        }
        return $ret;
    }
}