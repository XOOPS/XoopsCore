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
 * Object write handler class.
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
 * Object write handler class.
 *
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @author Simon Roberts <simon@xoops.org>
 *
 * {@link XoopsObjectAbstract}
 */
class XoopsModelWrite extends XoopsModelAbstract
{
    /**
     * Clean values of all variables of the object for storage.
     * also add slashes and quote string wherever needed
     *
     * CleanVars only contains changed and cleaned variables
     * Reference is used for PHP4 compliance
     *
     * @param XoopsObject $object
     *
     * @return bool true if successful
     * @access public
     */
    public function cleanVars(XoopsObject &$object)
    {
        $vars = $object->getVars();
        $object->cleanVars = array();
        foreach ($vars as $k => $v) {
            if (!$v["changed"]) {
                continue;
            }
            $object->cleanVars[$k] = Xoops_Object_Dtype::cleanVar($object, $k);
        }
        $object->unsetDirty();
        $errors = $object->getErrors();
        return empty($errors) ? true : false;
    }

    /**
     * insert an object into the database
     *
     * @param XoopsObject $object {@link XoopsObject} reference to object
     * @param bool $force flag to force the query execution despite security settings
     * @return mixed object ID
     */
    public function insert(XoopsObject &$object, $force = true)
    {
        if (!(class_exists($this->handler->className) && $object instanceof $this->handler->className)) {
            trigger_error("Object '" . get_class($object) . "' is not an instance of '" . $this->handler->className . "'", E_USER_NOTICE);
            return false;
        }
        if (!$object->isDirty()) {
            trigger_error("Data entry is not inserted - the object '" . get_class($object) . "' is not dirty", E_USER_NOTICE);
            return false;
        }
        if (!$this->cleanVars($object)) {
            trigger_error("Insert failed in method 'cleanVars' of object '" . get_class($object) . "'" . $object->getHtmlErrors(), E_USER_WARNING);
            return false;
        }
        $queryFunc = empty($force) ? "query" : "queryF";

        if ($object->isNew()) {
            $sql = "INSERT INTO `" . $this->handler->table . "`";
            if (!empty($object->cleanVars)) {
                $keys = array_keys($object->cleanVars);
                $vals = array_values($object->cleanVars);
                $sql .= " (`" . implode("`, `", $keys) . "`) VALUES (" . implode(",", $vals) . ")";
            } else {
                trigger_error("Data entry is not inserted - no variable is changed in object of '" . get_class($object) . "'", E_USER_NOTICE);
                return false;
            }
            if (!$this->handler->db->$queryFunc($sql)) {
                return false;
            }
            if (!$object->getVar($this->handler->keyName) && $object_id = $this->handler->db->getInsertId()) {
                $object->assignVar($this->handler->keyName, $object_id);
            }
			$object->unsetNew(); // object is no longer New
        } else {
            if (!empty($object->cleanVars)) {
                $keys = array();
                foreach ($object->cleanVars as $k => $v) {
                    $keys[] = " `{$k}` = {$v}";
                }
                $sql = "UPDATE `" . $this->handler->table . "` SET " . implode(",", $keys) . " WHERE `" . $this->handler->keyName . "` = " . $this->handler->db->quote($object->getVar($this->handler->keyName));
                if (!$this->handler->db->$queryFunc($sql)) {
                    return false;
                }
            }
        }
        return $object->getVar($this->handler->keyName);
    }

    /**
     * delete an object from the database
     *
     * @param XoopsObject $object {@link XoopsObject} reference to the object to delete
     * @param bool $force
     * @return bool FALSE if failed.
     */
    public function delete(XoopsObject &$object, $force = false)
    {
        if (!(class_exists($this->handler->className) && $object instanceof $this->handler->className)) {
            trigger_error("Object '" . get_class($object) . "' is not an instance of '" . $this->handler->className . "'", E_USER_NOTICE);
            return $object->getVar($this->handler->keyName);
        }
        if (is_array($this->handler->keyName)) {
            $clause = array();
            for ($i = 0; $i < count($this->handler->keyName); $i++) {
                $clause[] = "`" . $this->handler->keyName[$i] . "` = " . $this->handler->db->quote($object->getVar($this->handler->keyName[$i]));
            }
            $whereclause = implode(" AND ", $clause);
        } else {
            $whereclause = "`" . $this->handler->keyName . "` = " . $this->handler->db->quote($object->getVar($this->handler->keyName));
        }
        $sql = "DELETE FROM `" . $this->handler->table . "` WHERE " . $whereclause;
        $queryFunc = empty($force) ? "query" : "queryF";
        $result = $this->handler->db->$queryFunc($sql);
        return empty($result) ? false : true;
    }

    /**
     * delete all objects matching the conditions
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} with conditions to meet
     * @param bool $force force to delete
     * @param bool $asObject delete in object way: instantiate all objects and delte one by one
     * @return bool
     */
    public function deleteAll(CriteriaElement $criteria = null, $force = true, $asObject = false)
    {
        if ($asObject) {
            $objects = $this->handler->getAll($criteria);
            $num = 0;
            foreach (array_keys($objects) as $key) {
                $num += $this->delete($objects[$key], $force) ? 1 : 0;
            }
            unset($objects);
            return $num;
        }
        $queryFunc = empty($force) ? 'query' : 'queryF';
        $sql = 'DELETE FROM ' . $this->handler->table;
        if (!empty($criteria)) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$this->handler->db->$queryFunc($sql)) {
            return false;
        }
        return $this->handler->db->getAffectedRows();
    }

    /**
     * Change a field for objects with a certain criteria
     *
     * @param string $fieldname Name of the field
     * @param mixed $fieldvalue Value to write
     * @param CriteriaElement|null $criteria {@link CriteriaElement}
     * @param bool $force force to query
     * @return bool
     */
    public function updateAll($fieldname, $fieldvalue, CriteriaElement $criteria = null, $force = false)
    {
        $set_clause = "`{$fieldname}` = ";
        if (is_numeric($fieldvalue)) {
            $set_clause .= $fieldvalue;
        } else {
            if (is_array($fieldvalue)) {
                $set_clause .= $this->handler->db->quote(implode(',', $fieldvalue));
            } else {
                $set_clause .= $this->handler->db->quote($fieldvalue);
            }
        }
        $sql = 'UPDATE `' . $this->handler->table . '` SET ' . $set_clause;
        if (isset($criteria)) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        $queryFunc = empty($force) ? 'query' : 'queryF';
        $result = $this->handler->db->$queryFunc($sql);
        return empty($result) ? false : true;
    }
}