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
 * Object joint handler class.
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
 * Object joint handler class.
 *
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 *
 * {@link XoopsObjectAbstract}
 *
 * Usage of methods provided by XoopsModelJoint:
 *
 * Step #1: set linked table and adjoint fields through XoopsPersistableObjectHandler:
 *             $handler->table_link = $handler->db->prefix("the_linked_table"); // full name of the linked table that is used for the query
 *             $handler->field_link = "the_linked_field"; // name of field in linked table that will be used to link the linked table with current table
 *             $handler->field_object = "the_object_field"; // name of field in current table that will be used to link the linked table with current table; linked field name will be used if the field name is not set
 * Step #2: fetch data
 */
class XoopsModelJoint extends XoopsModelAbstract
{
    /**
     * Validate information for the linkship
     *
     * @access private
     * @return bool|null
     */
    private function validateLinks()
    {
        if (empty($this->handler->table_link) || empty($this->handler->field_link)) {
            trigger_error("The linked table is not set yet.", E_USER_WARNING);
            return null;
        }
        if (empty($this->handler->field_object)) {
            $this->handler->field_object = $this->handler->field_link;
        }
        return true;
    }

    /**
     * get a list of objects matching a condition joint with another related object
     *
     * @param CriteriaElement|null $criteria     {@link CriteriaElement} to match
     * @param array                $fields       variables to fetch
     * @param bool                 $asObject     flag indicating as object, otherwise as array
     * @param string               $field_link   field of linked object for JOIN; deprecated, for backward compat
     * @param string               $field_object field of current object for JOIN; deprecated, for backward compat
     *
     * @return array of objects {@link XoopsObject}
     */
    public function getByLink(
        CriteriaElement $criteria = null,
        $fields = null,
        $asObject = true,
        $field_link = null,
        $field_object = null
    ) {
        if (!empty($field_link)) {
            $this->handler->field_link = $field_link;
        }
        if (!empty($field_object)) {
            $this->handler->field_object = $field_object;
        }
        if (!$this->validateLinks()) {
            return null;
        }

        $qb = $this->handler->db->createXoopsQueryBuilder();
        if (is_array($fields) && count($fields)) {
            if (!in_array("o." . $this->handler->keyName, $fields)) {
                $fields[] = "o." . $this->handler->keyName;
            }
            $first = true;
            foreach ($fields as $field) {
                if ($first) {
                    $first = false;
                    $qb->select($field);
                } else {
                    $qb->addSelect($field);
                }
            }
        } else {
            $qb ->select('*', 'o')
                ->addSelect('*', 'l');
        }
        $qb ->from($this->handler->table, 'o')
            ->leftJoin('o', $this->handler->table_link, 'l',
                "o.{$this->handler->field_object} = l.{$this->handler->field_link}"
            );
        if (isset($criteria) && is_subclass_of($criteria, "criteriaelement")) {
            $qb = $criteria->renderQb($qb);
        }
        $result = $this->handler->db->executeQuery($sql);
        $ret = array();
        if ($asObject) {
            while ($myrow = $result->fetch(PDO::FETCH_ASSOC)) {
                $object = $this->handler->create(false);
                $object->assignVars($myrow);
                $ret[$myrow[$this->handler->keyName]] = $object;
                unset($object);
            }
        } else {
            $object = $this->handler->create(false);
            while ($myrow = $result->fetch(PDO::FETCH_ASSOC)) {
                $object->assignVars($myrow);
                $ret[$myrow[$this->handler->keyName]] = $object->getValues();
            }
            unset($object);
        }
        return $ret;
    }

    /**
     * Count of objects matching a condition
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} to match
     *
     * @return int count of objects
     */
    public function getCountByLink(CriteriaElement $criteria = null)
    {
        if (!$this->validateLinks()) {
            return null;
        }

        $sql = " SELECT COUNT(DISTINCT {$this->handler->keyName}) AS count"
            . " FROM {$this->handler->table} AS o"
            . " LEFT JOIN {$this->handler->table_link} AS l "
            . "ON o.{$this->handler->field_object} = l.{$this->handler->field_link}";
        if (isset($criteria) && is_subclass_of($criteria, "criteriaelement")) {
            $sql .= " " . $criteria->renderWhere();
            if ($criteria->getGroupby() != '') {
                $sql .= ' GROUP BY (' . $criteria->getGroupby() . ')';
            }
        }
        return $this->handler->db->fetchColumn($sql);
    }

    /**
     * array of count of objects matching a condition of, groupby linked object keyname
     *
     * @param CriteriaElement $criteria {@link CriteriaElement} to match
     *
     * @return int count of objects
     */
    public function getCountsByLink(CriteriaElement $criteria = null)
    {
        if (!$this->validateLinks()) {
            return null;
        }
        $sql = " SELECT l.{$this->handler->keyName_link}, COUNT(*)"
            . " FROM {$this->handler->table} AS o"
            . " LEFT JOIN {$this->handler->table_link} AS l "
            . "ON o.{$this->handler->field_object} = l.{$this->handler->field_link}";
        if (isset($criteria) && is_subclass_of($criteria, "criteriaelement")) {
            $sql .= " " . $criteria->renderWhere();
        }
        $sql .= " GROUP BY l.{$this->handler->keyName_link}";
        if (!$result = $this->handler->db->executeQuery($sql)) {
            return false;
        }
        $ret = array();
        while (list ($id, $count) = $result->fetch(PDO::FETCH_NUM)) {
            $ret[$id] = $count;
        }
        return $ret;
    }

    /**
     * update objects matching a condition against linked objects
     *
     * @param array                $data     array of key => value
     * @param CriteriaElement|null $criteria {@link CriteriaElement} to match
     *
     * @return int count of objects
     */
    public function updateByLink($data, CriteriaElement $criteria = null)
    {
        if (!$this->validateLinks()) {
            return null;
        }
        $set = array();
        foreach ($data as $key => $val) {
            $set[] = "o.{$key}=" . $this->handler->db->quoteString($val);
        }
        $sql = " UPDATE {$this->handler->table} AS o" . " SET " . implode(", ", $set)
            . " LEFT JOIN {$this->handler->table_link} AS l "
            . "ON o.{$this->handler->field_object} = l.{$this->handler->field_link}";
        if (isset($criteria) && is_subclass_of($criteria, "criteriaelement")) {
            $sql .= " " . $criteria->renderWhere();
        }
        return $this->handler->db->executeUpdate($sql);
    }

    /**
     * Delete objects matching a condition against linked objects
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} to match
     *
     * @return int count of objects
     */
    public function deleteByLink(CriteriaElement $criteria = null)
    {
        if (!$this->validateLinks()) {
            return null;
        }
        $sql = "DELETE FROM {$this->handler->table} AS o "
            . "LEFT JOIN {$this->handler->table_link} AS l "
            . "ON o.{$this->handler->field_object} = l.{$this->handler->field_link}";
        if (isset($criteria) && is_subclass_of($criteria, "criteriaelement")) {
            $sql .= " " . $criteria->renderWhere();
        }
        return $this->handler->db->executeUpdate($sql);
    }
}
