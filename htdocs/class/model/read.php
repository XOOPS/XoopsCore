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
 * Object render handler class.
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
 * Object render handler class.
 *
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 *
 * {@link XoopsObjectAbstract}
 */
class XoopsModelRead extends XoopsModelAbstract
{
    /**
     * get all objects matching a condition
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} to match
     * @param array $fields variables to fetch
     * @param bool $asObject flag indicating as object, otherwise as array
     * @param bool $id_as_key use the ID as key for the array
     * @return array of objects/array {@link XoopsObject}
     */
    public function getAll(CriteriaElement $criteria = null, $fields = null, $asObject = true, $id_as_key = true)
    {
        if (is_array($fields) && count($fields) > 0) {
            if (!in_array($this->handler->keyName, $fields)) {
                $fields[] = $this->handler->keyName;
            }
            $select = "`" . implode("`, `", $fields) . "`";
        } else {
            $select = "*";
        }
        $limit = null;
        $start = null;
        $sql = "SELECT {$select} FROM `{$this->handler->table}`";
        if (isset($criteria)) {
            $sql .= " " . $criteria->renderWhere();
            if ($groupby = $criteria->getGroupby()) {
                $sql .= ' GROUP BY (' . $groupby . ')';
            }
            if ($sort = $criteria->getSort()) {
                $sql .= " ORDER BY {$sort} " . $criteria->getOrder();
                $orderSet = true;
            } else if ($order = $criteria->getOrder()) {
                $sql .= " ORDER BY {$this->handler->keyName} " . $order;
                $orderSet = true;
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        if (empty($orderSet)) {
            //$sql .= " ORDER BY `{$this->handler->keyName}` DESC";
        }
        $result = $this->handler->db->query($sql, $limit, $start);
        $ret = array();
        if ($asObject) {
            while ($myrow = $this->handler->db->fetchArray($result)) {
                $object = $this->handler->create(false);
                $object->assignVars($myrow);
                if ($id_as_key) {
                    $ret[$myrow[$this->handler->keyName]] = $object;
                } else {
                    $ret[] = $object;
                }
                unset($object);
            }
        } else {
            $object = $this->handler->create(false);
            while ($myrow = $this->handler->db->fetchArray($result)) {
                $object->assignVars($myrow);
                if ($id_as_key) {
                    $ret[$myrow[$this->handler->keyName]] = $object->getValues();
                } else {
                    $ret[] = $object->getValues();
                }
            }
            unset($object);
        }
        return $ret;
    }

    /**
     * retrieve objects from the database
     *
     * For performance consideration, getAll() is recommended
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} conditions to be met
     * @param bool $id_as_key use the ID as key for the array
     * @param bool $as_object return an array of objects?
     * @return array
     */
    public function getObjects(CriteriaElement $criteria = null, $id_as_key = false, $as_object = true)
    {
        $objects = $this->getAll($criteria, null, $as_object, $id_as_key);
        return $objects;
    }

    /**
     * Retrieve a list of objects data
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} conditions to be met
     * @param int $limit Max number of objects to fetch
     * @param int $start Which record to start at
     * @return array
     */
    public function getList(CriteriaElement $criteria = null, $limit = 0, $start = 0)
    {
        $ret = array();
        if ($criteria == null) {
            $criteria = new CriteriaCompo();
        }

        $sql = "SELECT `{$this->handler->keyName}`";
        if (!empty($this->handler->identifierName)) {
            $sql .= ", `{$this->handler->identifierName}`";
        }
        $sql .= " FROM `{$this->handler->table}`";
        if (isset($criteria)) {
            $sql .= ' ' . $criteria->renderWhere();
            if ($groupby = $criteria->getGroupby()) {
                $sql .= ' GROUP BY (' . $groupby . ')';
            }
            if ($sort = $criteria->getSort()) {
                $sql .= ' ORDER BY ' . $sort . ' ' . $criteria->getOrder();
            } else if ($order = $criteria->getOrder()) {
                $sql .= " ORDER BY {$this->handler->keyName} " . $order;
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->handler->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }

        $myts = MyTextSanitizer::getInstance();
        while ($myrow = $this->handler->db->fetchArray($result)) {
            // identifiers should be textboxes, so sanitize them like that
            $ret[$myrow[$this->handler->keyName]] = empty($this->handler->identifierName) ? 1
                : $myts->htmlSpecialChars($myrow[$this->handler->identifierName]);
        }
        return $ret;
    }

    /**
     * get IDs of objects matching a condition
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} to match
     * @return array of object IDs
     */
    function getIds(CriteriaElement $criteria = null)
    {
        $ret = array();
        $sql = "SELECT `{$this->handler->keyName}` FROM `{$this->handler->table}`";
        $limit = $start = null;
        if (isset($criteria)) {
            $sql .= ' ' . $criteria->renderWhere();
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        if (!$result = $this->handler->db->query($sql, $limit, $start)) {
            return $ret;
        }
        while ($myrow = $this->handler->db->fetchArray($result)) {
            $ret[] = $myrow[$this->handler->keyName];
        }
        return $ret;
    }
}