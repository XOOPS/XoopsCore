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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.6.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

use Doctrine\DBAL\Query\QueryBuilder;

/**
 * membership of a user in a group
 *
 * @author Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000-2003 XOOPS.org
 * @package kernel
 */
class XoopsMembership extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('linkid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('groupid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('uid', XOBJ_DTYPE_INT, null, false);
    }
}

/**
 * XOOPS membership handler class. (Singleton)
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS group membership class objects.
 *
 * @author Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000-2003 XOOPS.org
 * @package kernel
 */
class XoopsMembershipHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param XoopsConnection|null $db {@link XoopsConnection}
     */
    public function __construct(XoopsConnection $db = null)
    {
        parent::__construct($db, 'groups_users_link', 'XoopsMembership', 'linkid', 'groupid');
    }

    /**
     * retrieve groups for a user
     *
     * @param int $uid ID of the user
     * objects? FALSE returns associative array.
     * @return array array of groups the user belongs to
     */
    public function getGroupsByUser($uid)
    {
        $ret = array();
        $qb = $this->db->createXoopsQueryBuilder();
        $eb = $qb->expr();
        $qb ->select('groupid')
            ->fromPrefix('groups_users_link', 'g')
            ->where($eb->eq('g.uid', ':uid'))
            ->setParameter(':uid', $uid, \PDO::PARAM_INT);
        $result = $qb->execute();
        while ($myrow = $result->fetch(PDO::FETCH_ASSOC)) {
            $ret[] = $myrow['groupid'];
        }

        return $ret;
    }

    /**
     * retrieve users belonging to a group
     *
     * @param int $groupid ID of the group
     * FALSE will return arrays
     * @param int $limit number of entries to return
     * @param int $start offset of first entry to return
     *
     * @return array array of users belonging to the group
     */
    public function getUsersByGroup($groupid, $limit = 0, $start = 0)
    {
        $ret = array();
        $qb = $this->db->createXoopsQueryBuilder();
        $eb = $qb->expr();
        $qb ->select('uid')
            ->fromPrefix('groups_users_link', 'g')
            ->where($eb->eq('g.groupid', ':gid'))
            ->setParameter(':gid', $groupid, \PDO::PARAM_INT);
        if ($limit!=0 || $start!=0) {
            $qb->setFirstResult($start)
                ->setMaxResults($limit);
        }
        $result = $qb->execute();
        while ($myrow = $result->fetch(PDO::FETCH_ASSOC)) {
            $ret[] = $myrow['uid'];
        }

        return $ret;
    }
}
