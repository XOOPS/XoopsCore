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

/**
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Avatars
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

/**
 * A Avatar
 *
 * @author Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000 XOOPS.org
 *
 * @package kernel
 */
class AvatarsAvatar extends XoopsObject
{
    /**
     * @var int
     */
    private $_userCount;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('avatar_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('avatar_file', XOBJ_DTYPE_OTHER, null, false, 30);
        $this->initVar('avatar_name', XOBJ_DTYPE_TXTBOX, null, true, 100);
        $this->initVar('avatar_mimetype', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('avatar_created', XOBJ_DTYPE_INT, null, false);
        $this->initVar('avatar_display', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('avatar_weight', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('avatar_type', XOBJ_DTYPE_OTHER, 0, false);
    }

    /**
     * get avatar_id
     *
     * @param string $format return format code
     *
     * @return mixed
     */
    public function id($format = 'n')
    {
        return $this->getVar('avatar_id', $format);
    }

    /**
     * get avatar_id
     *
     * @param string $format return format code
     *
     * @return mixed
     */
    public function avatar_id($format = '')
    {
        return $this->getVar('avatar_id', $format);
    }

    /**
     * get avatar_file
     *
     * @param string $format return format code
     *
     * @return mixed
     */
    public function avatar_file($format = '')
    {
        return $this->getVar('avatar_file', $format);
    }

    /**
     * get avatar_name
     *
     * @param string $format return format code
     *
     * @return mixed
     */
    public function avatar_name($format = '')
    {
        return $this->getVar('avatar_name', $format);
    }

    /**
     * get avatar_mimetype
     *
     * @param string $format return format code
     *
     * @return mixed
     */
    public function avatar_mimetype($format = '')
    {
        return $this->getVar('avatar_mimetype', $format);
    }

    /**
     * get avatar_created
     *
     * @param string $format return format code
     *
     * @return mixed
     */
    public function avatar_created($format = '')
    {
        return $this->getVar('avatar_created', $format);
    }

    /**
     * get avatar_display
     *
     * @param string $format return format code
     *
     * @return mixed
     */
    public function avatar_display($format = '')
    {
        return $this->getVar('avatar_display', $format);
    }

    /**
     * get avatar_weight
     *
     * @param string $format return format code
     *
     * @return mixed
     */
    public function avatar_weight($format = '')
    {
        return $this->getVar('avatar_weight', $format);
    }

    /**
     * get avatar_type
     *
     * @param string $format return format code
     *
     * @return mixed
     */
    public function avatar_type($format = '')
    {
        return $this->getVar('avatar_type', $format);
    }

    /**
     * Set User Count
     *
     * @param int $value user count
     *
     * @return void
     */
    public function setUserCount($value)
    {
        $this->_userCount = intval($value);
    }

    /**
     * Get User Count
     *
     * @return int
     */
    public function getUserCount()
    {
        return $this->_userCount;
    }
}

/**
 * @author  Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000 XOOPS.org
 * @package kernel
 */
class AvatarsAvatarHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param Connection|null $db {@link Connection}
     */
    public function __construct(Connection $db = null)
    {
        parent::__construct($db, 'avatars_avatar', 'AvatarsAvatar', 'avatar_id', 'avatar_name');
    }

    /**
     * Fetch a row of objects from the database
     *
     * @param null|CriteriaElement $criteria  criteria object
     * @param bool                 $id_as_key if true, use avatar_id as array key
     *
     * @return array
     */
    public function getObjectsWithCount(CriteriaElement $criteria = null, $id_as_key = false)
    {
        $ret = array();
        $limit = $start = 0;
        $sql = 'SELECT a.*, COUNT(u.user_id) AS count FROM '
            . $this->db->prefix('avatars_avatar') . ' a LEFT JOIN '
            . $this->db->prefix('avatars_user_link')
            . ' u ON u.avatar_id=a.avatar_id';
        if (isset($criteria)) {
            $sql .= ' ' . $criteria->renderWhere();
            $sql .= ' GROUP BY a.avatar_id ORDER BY avatar_weight, avatar_id';
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $avatar = new AvatarsAvatar();
            $avatar->assignVars($myrow);
            $avatar->setUserCount($myrow['count']);
            if (!$id_as_key) {
                $ret[] = $avatar;
            } else {
                $ret[$myrow['avatar_id']] = $avatar;
            }
            unset($avatar);
        }
        return $ret;
    }

    /**
     * Add user to avatars_user_link
     *
     * @param int $avatar_id avatar id
     * @param int $user_id   user id
     *
     * @return bool
     */
    public function addUser($avatar_id, $user_id)
    {
        $avatar_id = intval($avatar_id);
        $user_id = intval($user_id);
        if ($avatar_id < 1 || $user_id < 1) {
            return false;
        }
        $sql = sprintf("DELETE FROM %s WHERE user_id = %u", $this->db->prefix('avatars_user_link'), $user_id);
        $this->db->query($sql);
        $sql = sprintf(
            "INSERT INTO %s (avatar_id, user_id) VALUES (%u, %u)",
            $this->db->prefix('avatars_user_link'),
            $avatar_id,
            $user_id
        );
        if (!$this->db->query($sql)) {
            return false;
        }
        return true;
    }

    /**
     * getUser - get avatars_user_link for an avatar
     *
     * @param AvatarsAvatar $avatar avatar object
     *
     * @return array
     */
    public function getUser(AvatarsAvatar $avatar)
    {
        $ret = array();
        $sql = 'SELECT user_id FROM ' . $this->db->prefix('avatars_user_link')
        . ' WHERE avatar_id=' . $avatar->getVar('avatar_id');
        if (!$result = $this->db->query($sql)) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $ret[] = $myrow['user_id'];
        }
        return $ret;
    }

    /**
     * Get a list of Avatars
     *
     * @param string $avatar_type    'C' for custom, 'S' for system
     * @param bool   $avatar_display display avatar
     *
     * @return array
     */
    public function getListByType($avatar_type = null, $avatar_display = null)
    {
        $criteria = new CriteriaCompo();
        if (isset($avatar_type)) {
            $avatar_type = ($avatar_type == 'C') ? 'C' : 'S';
            $criteria->add(new Criteria('avatar_type', $avatar_type));
        }
        if (isset($avatar_display)) {
            $criteria->add(new Criteria('avatar_display', intval($avatar_display)));
        }
        $avatars = $this->getObjects($criteria, true);
        $ret = array(
            'avatars/blank.gif' => XoopsLocale::NONE
        );
        foreach (array_keys($avatars) as $i) {
            $ret[$avatars[$i]->getVar('avatar_file')] = $avatars[$i]->getVar('avatar_name');
        }
        return $ret;
    }
}
