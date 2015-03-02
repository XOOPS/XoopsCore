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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

use Xoops\Core\Database\Connection;
use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\CriteriaCompo;
use Xoops\Core\Kernel\CriteriaElement;

/**
 * XOOPS member handler class.
 * This class provides simple interface (a facade class) for handling groups/users/
 * membership data.
 *
 * @author  Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000-2003 XOOPS.org
 * @package kernel
 */
class XoopsMemberHandler
{

    /**#@+
     * holds reference to group handler(DAO) class
     * @var XoopsGrouppermHandler
     * @access private
     */
    private $_gHandler;

    /**
     * holds reference to user handler(DAO) class
     *
     * @var XoopsUserHandler
     */
    private $_uHandler;

    /**
     * holds reference to membership handler(DAO) class
     *
     * @var XoopsMembershipHandler
     */
    private $_mHandler;

    /**
     * holds temporary user objects
     */
    private $_members = array();

    /**#@-*/

    /**
     * Constructor
     *
     * @param Connection|null $db database connection
     */
    public function __construct(Connection $db = null)
    {
        $this->_gHandler = Xoops::getInstance()->getHandlerGroup();
        $this->_uHandler = Xoops::getInstance()->getHandlerUser();
        $this->_mHandler = Xoops::getInstance()->getHandlerMembership();
    }

    /**
     * create a new group
     *
     * @return XoopsGroup reference to the new group
     */
    public function createGroup()
    {
        $inst = $this->_gHandler->create();
        return $inst;
    }

    /**
     * create a new user
     *
     * @return XoopsUser reference to the new user
     */
    public function createUser()
    {
        $inst = $this->_uHandler->create();
        return $inst;
    }

    /**
     * retrieve a group
     *
     * @param int $id ID for the group
     *
     * @return XoopsGroup reference to the group
     */
    public function getGroup($id)
    {
        return $this->_gHandler->get($id);
    }

    /**
     * retrieve a user
     *
     * @param int $id ID for the user
     *
     * @return XoopsUser
     */
    public function getUser($id)
    {
        if (!isset($this->_members[$id])) {
            $this->_members[$id] = $this->_uHandler->get($id);
        }
        return $this->_members[$id];
    }

    /**
     * delete a group
     *
     * @param XoopsGroup &$group reference to the group to delete
     *
     * @return bool FALSE if failed
     */
    public function deleteGroup(XoopsGroup &$group)
    {
        $this->_gHandler->delete($group);
        $this->_mHandler->deleteAll(new Criteria('groupid', $group->getVar('groupid')));
        return true;
    }

    /**
     * delete a user
     *
     * @param XoopsUser &$user reference to the user to delete
     *
     * @return bool FALSE if failed
     */
    public function deleteUser(XoopsUser &$user)
    {
        $this->_uHandler->delete($user);
        $this->_mHandler->deleteAll(new Criteria('uid', $user->getVar('uid')));
        return true;
    }

    /**
     * insert a group into the database
     *
     * @param XoopsGroup &$group reference to the group to insert
     *
     * @return bool TRUE if already in database and unchanged
     * FALSE on failure
     */
    public function insertGroup(XoopsGroup &$group)
    {
        return $this->_gHandler->insert($group);
    }

    /**
     * insert a user into the database
     *
     * @param XoopsUser|XoopsObject &$user reference to the user to insert
     * @param bool                  $force force insert
     *
     * @return bool TRUE if already in database and unchanged
     * FALSE on failure
     */
    public function insertUser(XoopsUser &$user, $force = false)
    {
        return $this->_uHandler->insert($user, $force);
    }

    /**
     * retrieve groups from the database
     *
     * @param CriteriaElement|null $criteria  {@link CriteriaElement}
     * @param bool                 $id_as_key use the group's ID as key for the array?
     *
     * @return array array of {@link XoopsGroup} objects
     */
    public function getGroups(CriteriaElement $criteria = null, $id_as_key = false)
    {
        return $this->_gHandler->getObjects($criteria, $id_as_key);
    }

    /**
     * retrieve users from the database
     *
     * @param CriteriaElement|null $criteria  {@link CriteriaElement}
     * @param bool                 $id_as_key use the group's ID as key for the array?
     *
     * @return array array of {@link XoopsUser} objects
     */
    public function getUsers(CriteriaElement $criteria = null, $id_as_key = false)
    {
        return $this->_uHandler->getObjects($criteria, $id_as_key);
    }

    /**
     * get a list of groupnames and their IDs
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} object
     *
     * @return array associative array of group-IDs and names
     */
    public function getGroupList(CriteriaElement $criteria = null)
    {
        $groups = $this->_gHandler->getObjects($criteria, true);
        $ret = array();
        foreach (array_keys($groups) as $i) {
            $ret[$i] = $groups[$i]->getVar('name');
        }
        return $ret;
    }

    /**
     * get a list of usernames and their IDs
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} object
     *
     * @return array associative array of user-IDs and names
     */
    public function getUserList(CriteriaElement $criteria = null)
    {
        $users = $this->_uHandler->getObjects($criteria, true);
        $ret = array();
        foreach (array_keys($users) as $i) {
            $ret[$i] = $users[$i]->getVar('uname');
        }
        return $ret;
    }

    /**
     * add a user to a group
     *
     * @param int $group_id ID of the group
     * @param int $user_id  ID of the user
     *
     * @return object XoopsMembership
     */
    public function addUserToGroup($group_id, $user_id)
    {
        $mship = $this->_mHandler->create();
        $mship->setVar('groupid', $group_id);
        $mship->setVar('uid', $user_id);
        return $this->_mHandler->insert($mship);
    }

    /**
     * remove a list of users from a group
     *
     * @param int   $group_id ID of the group
     * @param array $user_ids array of user-IDs
     *
     * @return bool success?
     */
    public function removeUsersFromGroup($group_id, $user_ids = array())
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('groupid', $group_id));
        $criteria2 = new CriteriaCompo();
        foreach ($user_ids as $uid) {
            $criteria2->add(new Criteria('uid', $uid), 'OR');
        }
        $criteria->add($criteria2);
        return $this->_mHandler->deleteAll($criteria);
    }

    /**
     * get a list of users belonging to a group
     *
     * @param int  $group_id ID of the group
     * @param bool $asobject return the users as objects?
     * @param int  $limit    number of users to return
     * @param int  $start    index of the first user to return
     *
     * @return array Array of {@link XoopsUser} objects (if $asobject is TRUE)
     * or of associative arrays matching the record structure in the database.
     */
    public function getUsersByGroup($group_id, $asobject = false, $limit = 0, $start = 0)
    {
        $user_ids = $this->_mHandler->getUsersByGroup($group_id, $limit, $start);
        if (!$asobject) {
            return $user_ids;
        } else {
            $ret = array();
            foreach ($user_ids as $u_id) {
                $user = $this->getUser($u_id);
                if (is_object($user)) {
                    $ret[] = $user;
                }
                unset($user);
            }
            return $ret;
        }
    }

    /**
     * get a list of groups that a user is member of
     *
     * @param int  $user_id  ID of the user
     * @param bool $asobject return groups as {@link XoopsGroup} objects or arrays?
     *
     * @return array array of objects or arrays
     */
    public function getGroupsByUser($user_id, $asobject = false)
    {
        $ret = array();
        $group_ids = $this->_mHandler->getGroupsByUser($user_id);
        if (!$asobject) {
            return $group_ids;
        } else {
            foreach ($group_ids as $g_id) {
                $ret[] = $this->getGroup($g_id);
            }
            return $ret;
        }
    }

    /**
     * log in a user
     *
     * @param string $uname username as entered in the login form
     * @param string $pwd   password entered in the login form
     *
     * @return mixed object  XoopsUser reference to the logged in user
     *               boolean FALSE if failed to log in
     *
     * @todo - md5 support should be completely removed eventually
     */
    public function loginUser($uname, $pwd)
    {
        $criteria = new Criteria('uname', $uname);
        //$criteria->add(new Criteria('pass', md5($pwd)));
        $user = $this->_uHandler->getObjects($criteria, false);
        if (!$user || count($user) != 1) {
            return false;
        }
        $rehash = false;
        $hash = $user[0]->pass();
        $type = substr($user[0]->pass(), 0, 1);
        // see if we have a crypt like signature, old md5 hash is just hex digits
        if ($type=='$') {
            if (!password_verify($pwd, $hash)) {
                return false;
            }
            // check if hash uses the best algorithm (i.e. after a PHP upgrade)
            $rehash = password_needs_rehash($hash, PASSWORD_DEFAULT);
        } else {
            if ($hash!=md5($pwd)) {
                return false;
            }
            $rehash = true; // automatically update old style
        }
        // hash used an old algorithm, so make it stronger
        if ($rehash) {
            $user[0]->setVar('pass', password_hash($pwd, PASSWORD_DEFAULT));
            $this->_uHandler->insert($user[0]);
        }
        return $user[0];
    }

    /**
     * logs in a user with an md5 encrypted password
     *
     * @param string $uname  username
     * @param string $md5pwd password encrypted with md5
     *
     * @return object XoopsUser reference to the logged in user. FALSE if failed to log in
     *
     * @deprecated -- this does not appear to be used and should be removed
     */
    public function loginUserMd5($uname, $md5pwd)
    {
        $criteria = new CriteriaCompo(new Criteria('uname', $uname));
        $criteria->add(new Criteria('pass', $md5pwd));
        $user = $this->_uHandler->getObjects($criteria, false);
        if (!$user || count($user) != 1) {
            $user = false;
            return $user;
        }
        return $user[0];
    }

    /**
     * count users matching certain conditions
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} object
     *
     * @return int
     */
    public function getUserCount(CriteriaElement $criteria = null)
    {
        return $this->_uHandler->getCount($criteria);
    }

    /**
     * count users belonging to a group
     *
     * @param int $group_id ID of the group
     *
     * @return int
     */
    public function getUserCountByGroup($group_id)
    {
        return $this->_mHandler->getCount(new Criteria('groupid', $group_id));
    }

    /**
     * updates a single field in a users record
     *
     * @param XoopsUser &$user      reference to the {@link XoopsUser} object
     * @param string    $fieldName  name of the field to update
     * @param string    $fieldValue updated value for the field
     *
     * @return bool TRUE if success or unchanged, FALSE on failure
     */
    public function updateUserByField(XoopsUser &$user, $fieldName, $fieldValue)
    {
        $user->setVar($fieldName, $fieldValue);
        return $this->insertUser($user);
    }

    /**
     * updates a single field in a users record
     *
     * @param string          $fieldName  name of the field to update
     * @param string          $fieldValue updated value for the field
     * @param CriteriaElement $criteria   {@link CriteriaElement} object or null
     *
     * @return bool TRUE if success or unchanged, FALSE on failure
     */
    public function updateUsersByField($fieldName, $fieldValue, CriteriaElement $criteria = null)
    {
        if (is_null($criteria)) {
            $criteria = new Criteria(''); // empty criteria resolves to 'WHERE (1)'
        }
        return $this->_uHandler->updateAll($fieldName, $fieldValue, $criteria);
    }

    /**
     * activate a user
     *
     * @param XoopsUser &$user reference to the {@link XoopsUser} object
     *
     * @return bool successful?
     */
    public function activateUser(XoopsUser &$user)
    {
        if ($user->getVar('level') != 0) {
            return true;
        }
        $user->setVar('level', 1);
        return $this->_uHandler->insert($user, true);
    }

    /**
     * Get a list of users belonging to certain groups and matching criteria
     * Temporary solution
     *
     * @param array           $groups    IDs of groups
     * @param CriteriaElement $criteria  {@link CriteriaElement} object or null
     * @param bool            $asobject  return the users as objects?
     * @param bool            $id_as_key use the UID as key for the array if $asobject is TRUE
     *
     * @return array Array of {@link XoopsUser} objects (if $asobject is TRUE)
     * or of associative arrays matching the record structure in the database.
     */
    public function getUsersByGroupLink(
        $groups,
        CriteriaElement $criteria = null,
        $asobject = false,
        $id_as_key = false
    ) {

        $qb = $this->_uHandler->db2->createXoopsQueryBuilder();
        $eb = $qb->expr();

        $qb ->select('DISTINCT ' . ($asobject ? 'u.*' : 'u.uid'))
            ->fromPrefix('users', 'u')
            ->leftJoinPrefix('u', 'groups_users_link', 'm', 'm.uid = u.uid');

        $where = false;
        if (!empty($groups)) {
            $qb->where($eb->in('m.groupid', $groups));
            $where = true;
        }
        if (isset($criteria) && ($criteria instanceof CriteriaElement)) {
            $whereMode = $where ? 'AND' : '';
            $sql[] = $criteria->renderQb($qb, $whereMode);
        }

        $ret = array();

        if (!$result = $qb->execute()) {
            return $ret;
        }

        while ($myrow = $result->fetch(PDO::FETCH_ASSOC)) {
            if ($asobject) {
                $user = new XoopsUser();
                $user->assignVars($myrow);
                if (!$id_as_key) {
                    $ret[] = $user;
                } else {
                    $ret[$myrow['uid']] = $user;
                }
                unset($user);
            } else {
                $ret[] = $myrow['uid'];
            }
        }
        return $ret;
    }

    /**
     * Get count of users belonging to certain groups and matching criteria
     * Temporary solution
     *
     * @param array                $groups   IDs of groups
     * @param CriteriaElement|null $criteria criteria to match
     *
     * @return int count of users
     */
    public function getUserCountByGroupLink($groups, $criteria = null)
    {
        $qb = $this->_uHandler->db2->createXoopsQueryBuilder();
        $eb = $qb->expr();

        $qb ->select('COUNT(DISTINCT u.uid)')
            ->fromPrefix('users', 'u')
            ->leftJoinPrefix('u', 'groups_users_link', 'm', 'm.uid = u.uid');

        $where = false;
        if (!empty($groups)) {
            $qb->where($eb->in('m.groupid', $groups));
            $where = true;
        }
        if (isset($criteria) && ($criteria instanceof CriteriaElement)) {
            $whereMode = $where ? 'AND' : '';
            $criteria->renderQb($qb, $whereMode);
        }

        $result = $qb->execute();
        $ret = $result->fetchColumn(0);

        return $ret;
    }
}
