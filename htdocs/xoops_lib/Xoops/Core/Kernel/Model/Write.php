<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Kernel\Model;

use Xoops\Core\Kernel\CriteriaElement;
use Xoops\Core\Kernel\Dtype;
use Xoops\Core\Kernel\XoopsObject;
use Xoops\Core\Kernel\XoopsModelAbstract;

/**
 * Object write handler class.
 *
 * @category  Xoops\Core\Kernel\Model\Write
 * @package   Xoops\Core\Kernel
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @author    Simon Roberts <simon@xoops.org>
 * @copyright 2000-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.3.0
 */
class Write extends XoopsModelAbstract
{
    /**
     * Clean values of all variables of the object for storage.
     * also add slashes and quote string wherever needed
     *
     * CleanVars only contains changed and cleaned variables
     * Reference is used for PHP4 compliance
     *
     * @param XoopsObject &$object {@link XoopsObject} reference to object
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
            $object->cleanVars[$k] = Dtype::cleanVar($object, $k, false);
        }
        $object->unsetDirty();
        $errors = $object->getErrors();
        return empty($errors) ? true : false;
        //return $object->cleanVars();
    }

    /**
     * insert an object into the database
     *
     * @param XoopsObject &$object {@link XoopsObject} reference to object
     * @param bool        $force   flag to force the query execution despite security settings
     *
     * @return mixed object ID
     */
    public function insert(XoopsObject &$object, $force = true)
    {
        if (!(class_exists($this->handler->className) && $object instanceof $this->handler->className)) {
            trigger_error(
                "Object '" . get_class($object) . "' is not an instance of '" . $this->handler->className . "'",
                E_USER_NOTICE
            );
            return false;
        }
        if (!$object->isDirty()) {
            trigger_error(
                "Data entry is not inserted - the object '" . get_class($object) . "' is not dirty",
                E_USER_NOTICE
            );
            return false;
        }
        if (!$this->cleanVars($object)) {
            trigger_error(
                "Insert failed in method 'cleanVars' of object '" . get_class($object) . "'" . $object->getHtmlErrors(),
                E_USER_WARNING
            );
            return false;
        }
        //$queryFunc = empty($force) ? "query" : "queryF";

        if ($object->isNew()) {
            if (empty($object->cleanVars)) {
                trigger_error(
                    "Data entry is not inserted - no variable is changed in object of '" . get_class($object) . "'",
                    E_USER_NOTICE
                );
                return false;
            }
            if (!$this->handler->db2->insert($this->handler->table, $object->cleanVars)) {
                return false;
            }
            if (!$object->getVar($this->handler->keyName) && $object_id = $this->handler->db2->lastInsertId()) {
                $object->assignVar($this->handler->keyName, $object_id);
            }
            $object->unsetNew(); // object is no longer New
        } else {
            if (!empty($object->cleanVars)) {
                $result = $this->handler->db2->update(
                    $this->handler->table,
                    $object->cleanVars,
                    array($this->handler->keyName => $object->getVar($this->handler->keyName))
                );
                if (!$result && intval($this->handler->db2->errorCode())) {
                    return false;
                }
            }
        }
        return $object->getVar($this->handler->keyName);
    }

    /**
     * delete an object from the database
     *
     * @param XoopsObject &$object {@link XoopsObject} reference to the object to delete
     * @param bool        $force   force to delete
     *
     * @return bool FALSE if failed.
     */
    public function delete(XoopsObject &$object, $force = false)
    {
        if (!(class_exists($this->handler->className) && $object instanceof $this->handler->className)) {
            trigger_error(
                "Object '" . get_class($object) . "' is not an instance of '" . $this->handler->className . "'",
                E_USER_NOTICE
            );
            return false;
        }

        $qb = $this->handler->db2->createXoopsQueryBuilder();
        $eb = $qb->expr();

        $qb->delete($this->handler->table);
        if (is_array($this->handler->keyName)) {
            for ($i = 0; $i < count($this->handler->keyName); ++$i) {
                if ($i == 0) {
                    $qb->where(
                        $eb->eq(
                            $this->handler->keyName[$i],
                            $qb->createNamedParameter($object->getVar($this->handler->keyName[$i]))
                        )
                    );
                } else {
                    $qb->andWhere(
                        $eb->eq(
                            $this->handler->keyName[$i],
                            $qb->createNamedParameter($object->getVar($this->handler->keyName[$i]))
                        )
                    );
                }
            }
        } else {
            $qb->where(
                $eb->eq(
                    $this->handler->keyName,
                    $qb->createNamedParameter($object->getVar($this->handler->keyName))
                )
            );
        }
        $result = $qb->execute();
        return empty($result) ? false : true;
    }

    /**
     * delete all objects matching the conditions
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} with conditions to meet
     * @param bool                 $force    force to delete
     * @param bool                 $asObject delete in object way: instantiate all objects and delte one by one
     *
     * @return bool
     */
    public function deleteAll(CriteriaElement $criteria = null, $force = false, $asObject = false)
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
        //$queryFunc = empty($force) ? 'query' : 'queryF';
        $qb = $this->handler->db2->createXoopsQueryBuilder();
        $qb->delete($this->handler->table);
        if (isset($criteria)) {
            $qb = $criteria->renderQb($qb);
        }
        return $qb->execute();
    }

    /**
     * Change a field for objects with a certain criteria
     *
     * @param string               $fieldname  Name of the field
     * @param mixed                $fieldvalue Value to write
     * @param CriteriaElement|null $criteria   {@link CriteriaElement}
     * @param bool                 $force      force to query
     *
     * @return bool
     */
    public function updateAll($fieldname, $fieldvalue, CriteriaElement $criteria = null, $force = false)
    {
        $qb = $this->handler->db2->createXoopsQueryBuilder();

        //$queryFunc = empty($force) ? 'query' : 'queryF';
        $qb->update($this->handler->table);
        if (isset($criteria)) {
            $qb = $criteria->renderQb($qb);
        }
        $qb->set($fieldname, $qb->createNamedParameter($fieldvalue));

        return $qb->execute();
    }
}
