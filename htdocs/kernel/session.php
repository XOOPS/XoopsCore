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
 * XOOPS session handler
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Handler for a session
 *
 * @package   kernel
 * @author    Kazumi Ono    <onokazu@xoops.org>
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright copyright (c) The XOOPS project XOOPS.org
 */
class XoopsSessionHandler
{
    /**
     * Database connection
     *
     * @var    object
     * @access    private
     */
    private $db;

    /**
     * Security checking level
     *
     * Possible value:
     *    0 - no check;
     *    1 - check browser characteristics (HTTP_USER_AGENT/HTTP_ACCEPT_LANGUAGE), to be implemented in the future now;
     *    2 - check browser and IP A.B;
     *    3 - check browser and IP A.B.C, recommended;
     *    4 - check browser and IP A.B.C.D;
     *
     * @var    int
     * @access    public
     */
    public $securityLevel = 3;

    /**
     * Enable regenerate_id
     *
     * @var    bool
     * @access    public
     */
    public $enableRegenerateId = false;

    /**
     * Constructor
     *
     * @param Xoops\Core\Database\Connection $db database instance
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Open a session
     *
     * @param string $save_path    not used
     * @param string $session_name not used
     *
     * @return bool
     */
    public function open($save_path, $session_name)
    {
        return true;
    }

    /**
     * Close a session
     *
     * @return    bool
     */
    public function close()
    {
        $this->gc_force();
        return true;
    }

    /**
     * Read a session from the database
     *
     * @param string $sess_id Id of the session
     *
     * @return string Session data
     */
    public function read($sess_id)
    {
        $qb = $this->db->createXoopsQueryBuilder();
        $eb = $qb->expr();
        $qb ->select('s.sess_data')
            ->addSelect('s.sess_ip')
            ->fromPrefix('session', 's')
            ->where($eb->eq('s.sess_id', ':sessid'))
            ->setParameter(':sessid', $sess_id, \PDO::PARAM_STR);

        if ($result = $qb->execute()) {
            if (list ($sess_data, $sess_ip) = $result->fetch(\PDO::FETCH_NUM)) {
                if ($this->securityLevel > 1) {
                    $pos = strrpos($sess_ip, '.'); //, $this->securityLevel - 1);
                    if (strncmp($sess_ip, $_SERVER['REMOTE_ADDR'], $pos-1)) {
                        $sess_data = '';
                    }
                }
                return $sess_data;
            }
        }
        return '';
    }

    /**
     * Write a session to the database
     *
     * @param string $sess_id   id of session
     * @param string $sess_data data to store
     *
     * @return bool
     **/
    public function write($sess_id, $sess_data)
    {
        $qb = $this->db->createXoopsQueryBuilder();
        $eb = $qb->expr();
        $qb ->updatePrefix('session')
            ->set('sess_updated', ':sessupd')
            ->set('sess_data', ':sessdata')
            ->where($eb->eq('sess_id', ':sessid'))
            ->setParameter(':sessid', $sess_id, \PDO::PARAM_STR)
            ->setParameter(':sessupd', time(), \PDO::PARAM_INT)
            ->setParameter(':sessdata', $sess_data, \PDO::PARAM_STR);
        $result = $qb->execute();
        if ($result<=0) {
            $this->db->insertPrefix(
                'session',
                array(
                    'sess_id'      => $sess_id,
                    'sess_updated' => time(),
                    'sess_ip'      => $_SERVER['REMOTE_ADDR'],
                    'sess_data'    => $sess_data,
                )
            );
        }

        return ($result);
    }

    /**
     * Destroy a session
     *
     * @param string $sess_id Id of session
     *
     * @return bool
     **/
    public function destroy($sess_id)
    {
        $qb = $this->db->createXoopsQueryBuilder();
        $eb = $qb->expr();
        $qb ->deletePrefix('session', 's')
            ->where($eb->eq('s.sess_id', ':sessid'))
            ->setParameter(':sessid', $sess_id, \PDO::PARAM_STR);
        return $qb->execute();
    }

    /**
     * Garbage Collector
     *
     * @param int $expire Time in seconds until a session expires
     *
     * @return bool
     **/
    public function gc($expire)
    {
        if (empty($expire)) {
            return true;
        }

        $mintime = time() - intval($expire);
        $qb = $this->db->createXoopsQueryBuilder();
        $eb = $qb->expr();
        $qb ->deletePrefix('session', 's')
            ->where($eb->lt('s.sess_updated', ':sessupd'))
            ->setParameter(':sess_updated', $mintime, \PDO::PARAM_INT);
        return $qb->execute();
    }

    /**
     * Force gc for situations where gc is registered but not executed
     *
     * @return void
     **/
    public function gc_force()
    {
        if (rand(1, 100) < 11) {
            $expire = @ini_get('session.gc_maxlifetime');
            $expire = ($expire > 0) ? $expire : 900;
            $this->gc($expire);
        }
    }

    /**
     * Update the current session id with a newly generated one
     *
     * To be refactored
     *
     * @param bool $delete_old_session passed to session_regenerate_id
     *
     * @return bool
     **/
    public function regenerate_id($delete_old_session = false)
    {
        $phpversion = phpversion();

        if (!$this->enableRegenerateId) {
            $success = true;
        } else {
            $success = session_regenerate_id($delete_old_session);
        }

        // Force updating cookie for session cookie is not issued correctly in some IE versions
        if ($success) {
            $this->update_cookie();
        }

        return $success;
    }

    /**
     * Update cookie status for current session
     *
     * To be refactored
     * FIXME: how about $xoopsConfig['use_ssl'] is enabled?
     *
     * @param string $sess_id session ID
     * @param int    $expire  Time in seconds until a session expires
     *
     * @return boolean|null
     **/
    public function update_cookie($sess_id = null, $expire = null)
    {
        $xoops = Xoops::getInstance();
        $session_name = ($xoops->getConfig('use_mysession')
            && $xoops->getConfig('session_name') != '')
            ? $xoops->getConfig('session_name') : session_name();
        $session_expire = !is_null($expire) ? intval($expire) : (($xoops->getConfig('use_mysession')
            && $xoops->getConfig('session_name') != '')
            ? $xoops->getConfig('session_expire') * 60
            : ini_get("session.cookie_lifetime"));
        $session_id = empty($sess_id) ? session_id() : $sess_id;
        if (!headers_sent()) {
            setcookie(
                $session_name,
                $session_id,
                $session_expire ? time() + $session_expire : 0,
                '/',
                XOOPS_COOKIE_DOMAIN,
                false,
                true
            );
        }
    }
}
