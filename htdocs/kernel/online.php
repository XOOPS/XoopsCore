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
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Online object
 *
 * @author trabis <lusopoemas@gmail.com>
 * @copyright copyright (c) 2000 XOOPS.org
 *
 * @package kernel
 */
class XoopsOnline extends XoopsObject
{
    /**
     * Constructor
     */
    function __construct()
    {
        $this->initVar('online_uid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('online_uname', XOBJ_DTYPE_TXTBOX, null, true);
        $this->initVar('online_updated', XOBJ_DTYPE_INT, null, true);
        $this->initVar('online_module', XOBJ_DTYPE_INT, null, true);
        $this->initVar('online_ip', XOBJ_DTYPE_TXTBOX, null, true);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function online_uid($format = 'n')
    {
        return $this->getVar('online_uid', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function online_uname($format = '')
    {
        return $this->getVar('online_uname', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function online_updated($format = '')
    {
        return $this->getVar('online_updated', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function online_module($format = '')
    {
        return $this->getVar('online_module', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function online_ip($format = '')
    {
        return $this->getVar('online_ip', $format);
    }

}
/**
 * A handler for "Who is Online?" information
 *
 * @package     kernel
 *
 * @author        Kazumi Ono    <onokazu@xoops.org>
 * @copyright    copyright (c) 2000-2003 XOOPS.org
 */
class XoopsOnlineHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param XoopsConnection|null $db {@link XoopsConnection}
     */
    public function __construct(XoopsConnection $db = null)
    {
        parent::__construct($db, 'online', 'XoopsOnline', 'online_uid', 'online_uname');
    }

    /**
     * Write online information to the database
     *
     * @param    int     $uid    UID of the active user
     * @param    string  $uname  Username
     * @param    string  $time
     * @param    string  $module Current module
     * @param    string  $ip     User's IP adress
     * @return    bool    TRUE on success
     */
    public function write($uid, $uname, $time, $module, $ip)
    {
        $uid = intval($uid);
        if ($uid > 0) {
            $sql = "SELECT COUNT(*) FROM " . $this->db->prefix('online') . " WHERE online_uid=" . $uid;
        } else {
            $sql = "SELECT COUNT(*) FROM " . $this->db->prefix('online') . " WHERE online_uid=" . $uid . " AND online_ip='" . $ip . "'";
        }
        list ($count) = $this->db->fetchRow($this->db->queryF($sql));
        if ($count > 0) {
            $sql = "UPDATE " . $this->db->prefix('online') . " SET online_updated=" . $time . ", online_module = " . $module . " WHERE online_uid = " . $uid;
            if ($uid == 0) {
                $sql .= " AND online_ip='" . $ip . "'";
            }
        } else {
            $sql = sprintf("INSERT INTO %s (online_uid, online_uname, online_updated, online_ip, online_module) VALUES (%u, %s, %u, %s, %u)", $this->db->prefix('online'), $uid, $this->db->quoteString($uname), $time, $this->db->quoteString($ip), $module);
        }
        if (!$this->db->queryF($sql)) {
            return false;
        }
        return true;
    }

    /**
     * Delete online information for a user
     *
     * @param    int $uid    UID
     * @return    bool    TRUE on success
     */
    public function destroy($uid)
    {
        $criteria = new Criteria('online_uid', intval($uid));
        if (!$this->deleteAll($criteria)) {
            return false;
        }
        return true;
    }

    /**
     * Garbage Collection
     *
     * Delete all online information that has not been updated for a certain time
     *
     * @param  int $expire Expiration time in seconds
     * @return bool
     */
    public function gc($expire)
    {
        $criteria = new Criteria('online_updated', time() - intval($expire), '<');
        if (!$this->deleteAll($criteria)) {
            return false;
        }
        return true;
    }

}

?>