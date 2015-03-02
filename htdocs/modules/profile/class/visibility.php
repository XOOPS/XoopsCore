<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Database\Connection;
use Xoops\Core\Kernel\XoopsObject;
use Xoops\Core\Kernel\XoopsPersistableObjectHandler;

/**
 * Extended User Profile
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         profile
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

class ProfileVisibility extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('field_id', XOBJ_DTYPE_INT);
        $this->initVar('user_group', XOBJ_DTYPE_INT);
        $this->initVar('profile_group', XOBJ_DTYPE_INT);
    }
}

class ProfileVisibilityHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|Connection $db database
     */
    public function __construct(Connection $db = null)
    {
        parent::__construct($db, 'profile_visibility', 'profilevisibility', 'field_id');
    }

    /**
     * Get fields visible to the $user_groups on a $profile_groups profile
     *
     * @param array $profile_groups groups of the user to be accessed
     * @param array $user_groups    groups of the visitor, default as $xoops->user
     *
     * @return array
     */
    public function getVisibleFields($profile_groups, $user_groups = null)
    {
        $profile_groups[] = 0;
        array_walk($profile_groups, 'intval');
        $user_groups[] = 0;
        array_walk($user_groups, 'intval');

        $qb = $this->db2->createXoopsQueryBuilder();
        $eb = $qb->expr();
        $sql = $qb->select('t1.field_id')
            ->from($this->table, 't1')
            ->where($eb->in('t1.profile_group', $profile_groups))
            ->andWhere($eb->in('t1.user_group', $user_groups));

        $result = $sql->execute();
        $field_ids = array();
        while (list($field_id) = $result->fetch(PDO::FETCH_NUM)) {
            $field_ids[] = $field_ids;
        }

        return $field_ids;
    }
}
