<?php
/**
 * XOOPS Kernel Class
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

use Xoops\Core\Database\Connection;
use Xoops\Core\FixedGroups;
use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\CriteriaCompo;
use Xoops\Core\Kernel\XoopsObject;
use Xoops\Core\Kernel\XoopsPersistableObjectHandler;

/**
 * A group permission
 *
 * These permissions are managed through a {@link XoopsGroupPermHandler} object
 *
 * @package     kernel
 *
 * @author      Kazumi Ono  <onokazu@xoops.org>
 * @copyright   copyright (c) 2000-2003 XOOPS.org
 */
class XoopsGroupPerm extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('gperm_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('gperm_groupid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('gperm_itemid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('gperm_modid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('gperm_name', XOBJ_DTYPE_OTHER, null, false);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function id($format = 'n')
    {
        return $this->getVar('gperm_id', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function gperm_id($format = '')
    {
        return $this->getVar('gperm_id', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function gperm_groupid($format = '')
    {
        return $this->getVar('gperm_groupid', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function gperm_itemid($format = '')
    {
        return $this->getVar('gperm_itemid', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function gperm_modid($format = '')
    {
        return $this->getVar('gperm_modid', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function gperm_name($format = '')
    {
        return $this->getVar('gperm_name', $format);
    }

}

/**
 * XOOPS group permission handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS group permission class objects.
 * This class is an abstract class to be implemented by child group permission classes.
 *
 * @see          XoopsGroupPerm
 * @author       Kazumi Ono  <onokazu@xoops.org>
 * @copyright    copyright (c) 2000-2003 XOOPS.org
 */
class XoopsGroupPermHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param Connection|null $db {@link Connection}
     */
    public function __construct(Connection $db = null)
    {
        parent::__construct($db, 'group_permission', 'XoopsGroupPerm', 'gperm_id', 'gperm_name');
    }

    /**
     * Delete all module specific permissions assigned for a group
     *
     * @param   int  $gperm_groupid ID of a group
     * @param   int  $gperm_modid ID of a module
     *
     * @return  bool TRUE on success
     */
    public function deleteByGroup($gperm_groupid, $gperm_modid = null)
    {
        $criteria = new CriteriaCompo(new Criteria('gperm_groupid', (int)($gperm_groupid)));
        if (isset($gperm_modid)) {
            $criteria->add(new Criteria('gperm_modid', (int)($gperm_modid)));
        }
        return $this->deleteAll($criteria);
    }

    /**
     * Delete all module specific permissions
     *
     * @param   int  $gperm_modid ID of a module
     * @param   string  $gperm_name Name of a module permission
     * @param   int  $gperm_itemid ID of a module item
     *
     * @return  bool TRUE on success
     */
    public function deleteByModule($gperm_modid, $gperm_name = null, $gperm_itemid = null)
    {
        $criteria = new CriteriaCompo(new Criteria('gperm_modid', (int)($gperm_modid)));
        if (isset($gperm_name)) {
            $criteria->add(new Criteria('gperm_name', $gperm_name));
            if (isset($gperm_itemid)) {
                $criteria->add(new Criteria('gperm_itemid', (int)($gperm_itemid)));
            }
        }
        return $this->deleteAll($criteria);
    }

    /**
     * Check permission
     *
     * @param   string    $gperm_name       Name of permission
     * @param   int       $gperm_itemid     ID of an item
     * @param   int|array $gperm_groupid    A group ID or an array of group IDs
     * @param   int       $gperm_modid      ID of a module
     * @param   bool      $trueifadmin      Returns true for admin groups
     *
     * @return  bool    TRUE if permission is enabled
     */
    public function checkRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid = 1, $trueifadmin = true)
    {
        if (empty($gperm_groupid)) {
            return false;
        } else {
            if (is_array($gperm_groupid)) {
                if (in_array(FixedGroups::ADMIN, $gperm_groupid) && $trueifadmin) {
                    return true;
                }
                $criteria_group = new CriteriaCompo();
                foreach ($gperm_groupid as $gid) {
                    $criteria_group->add(new Criteria('gperm_groupid', $gid), 'OR');
                }
            } else {
                if (FixedGroups::ADMIN == $gperm_groupid && $trueifadmin) {
                    return true;
                }
                $criteria_group = new CriteriaCompo(new Criteria('gperm_groupid', $gperm_groupid));
            }
        }
        $criteria = new CriteriaCompo(new Criteria('gperm_modid', $gperm_modid));
        $criteria->add($criteria_group);
        $criteria->add(new Criteria('gperm_name', $gperm_name));
        $gperm_itemid = (int)($gperm_itemid);
        if ($gperm_itemid > 0) {
            $criteria->add(new Criteria('gperm_itemid', $gperm_itemid));
        }
        if ($this->getCount($criteria) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Add a permission
     *
     * @param   string  $gperm_name       Name of permission
     * @param   int     $gperm_itemid     ID of an item
     * @param   int     $gperm_groupid    ID of a group
     * @param   int     $gperm_modid      ID of a module
     *
     * @return  bool    TRUE if success
     */
    function addRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid = 1)
    {
        $perm = $this->create();
        $perm->setVar('gperm_name', $gperm_name);
        $perm->setVar('gperm_groupid', $gperm_groupid);
        $perm->setVar('gperm_itemid', $gperm_itemid);
        $perm->setVar('gperm_modid', $gperm_modid);
        return $this->insert($perm);
    }

    /**
     * Get all item IDs that a group is assigned a specific permission
     *
     * @param   string    $gperm_name       Name of permission
     * @param   int|array $gperm_groupid    A group ID or an array of group IDs
     * @param   int       $gperm_modid      ID of a module
     *
     * @return  array     array of item IDs
     */
    public function getItemIds($gperm_name, $gperm_groupid, $gperm_modid = 1)
    {
        $ret = array();
        $criteria = new CriteriaCompo(new Criteria('gperm_name', $gperm_name));
        $criteria->add(new Criteria('gperm_modid', (int)($gperm_modid)));
        if (is_array($gperm_groupid)) {
            $criteria2 = new CriteriaCompo();
            foreach ($gperm_groupid as $gid) {
                $criteria2->add(new Criteria('gperm_groupid', $gid), 'OR');
            }
            $criteria->add($criteria2);
        } else {
            $criteria->add(new Criteria('gperm_groupid', (int)($gperm_groupid)));
        }
        $perms = $this->getObjects($criteria, true);
        foreach (array_keys($perms) as $i) {
            $ret[] = $perms[$i]->getVar('gperm_itemid');
        }
        return array_unique($ret);
    }

    /**
     * Get all group IDs assigned a specific permission for a particular item
     *
     * @param   string  $gperm_name       Name of permission
     * @param   int     $gperm_itemid     ID of an item
     * @param   int     $gperm_modid      ID of a module
     *
     * @return  array   array of group IDs
     */
    public function getGroupIds($gperm_name, $gperm_itemid, $gperm_modid = 1)
    {
        $ret = array();
        $criteria = new CriteriaCompo(new Criteria('gperm_name', $gperm_name));
        $criteria->add(new Criteria('gperm_itemid', (int)($gperm_itemid)));
        $criteria->add(new Criteria('gperm_modid', (int)($gperm_modid)));
        $perms = $this->getObjects($criteria, true);
        foreach (array_keys($perms) as $i) {
            $ret[] = $perms[$i]->getVar('gperm_groupid');
        }
        return $ret;
    }
}
