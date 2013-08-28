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
 * @package     kernel
 *
 * @author        Kazumi Ono    <onokazu@xoops.org>
 * @author        Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright    copyright (c) The XOOPS project XOOPS.org
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
     * @param XoopsConnection $db reference to the {@link XoopsConnection} object
     *
     */
    public function __construct($db)
    {
        global $xoopsDB;
        $this->db = $xoopsDB;
    }

    /**
     * Open a session
     *
     * @param    string  $save_path
     * @param    string  $session_name
     *
     * @return    bool
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
     * @param    string  $sess_id    ID of the session
     *
     * @return    string   Session data
     */
    public function read($sess_id)
    {
        $sql = sprintf('SELECT sess_data, sess_ip FROM %s WHERE sess_id = %s', $this->db->prefix('session'), $this->db->quoteString($sess_id));
        if (false != $result = $this->db->query($sql)) {
            if (list ($sess_data, $sess_ip) = $this->db->fetchRow($result)) {
                if ($this->securityLevel > 1) {
                    $pos = strpos($sess_ip, ".", $this->securityLevel - 1);
                    if (strncmp($sess_ip, $_SERVER['REMOTE_ADDR'], $pos)) {
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
     * @param   string  $sess_id
     * @param   string  $sess_data
     *
     * @return  bool
     **/
    public function write($sess_id, $sess_data)
    {
        $sess_id = $this->db->quoteString($sess_id);
        $sql = sprintf('UPDATE %s SET sess_updated = %u, sess_data = %s WHERE sess_id = %s', $this->db->prefix('session'), time(), $this->db->quoteString($sess_data), $sess_id);
        $result = $this->db->queryF($sql);
        if ($this->db->getAffectedRows() <= 0) {
            $sql = sprintf('INSERT INTO %s (sess_id, sess_updated, sess_ip, sess_data) VALUES (%s, %u, %s, %s)', $this->db->prefix('session'), $sess_id, time(), $this->db->quoteString($_SERVER['REMOTE_ADDR']), $this->db->quoteString($sess_data));
            $result = $this->db->queryF($sql);
        }

        return (false !== $result);
    }

    /**
     * Destroy a session
     *
     * @param   string  $sess_id
     *
     * @return  bool
     **/
    public function destroy($sess_id)
    {
        $sql = sprintf('DELETE FROM %s WHERE sess_id = %s', $this->db->prefix('session'), $this->db->quoteString($sess_id));
        if (!$this->db->queryF($sql)) {
            return false;
        }
        return true;
    }

    /**
     * Garbage Collector
     *
     * @param   int $expire Time in seconds until a session expires
     * @return  bool
     **/
    public function gc($expire)
    {
        if (empty($expire)) {
            return true;
        }

        $mintime = time() - intval($expire);
        $sql = sprintf('DELETE FROM %s WHERE sess_updated < %u', $this->db->prefix('session'), $mintime);
        return $this->db->queryF($sql);
    }

    /**
     * Force gc for situations where gc is registered but not executed
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
     * @param   bool $delete_old_session
     * @return  bool
     **/
    public function regenerate_id($delete_old_session = false)
    {
        $phpversion = phpversion();

        if (!$this->enableRegenerateId) {
            $success = true;

        // parameter "delete_old_session" only available as of PHP 5.1.0
        } else if (version_compare($phpversion, "5.1.0", ">=")) {
            $success = session_regenerate_id($delete_old_session);

        } else {
            $old_session_id = session_id();
            // session_regenerate_id function available as of PHP 4.3.2
            if (function_exists("session_regenerate_id")) {
                $success = session_regenerate_id();
                if ($success && $delete_old_session) {
                    // Extra step to destroy old session
                    $this->destroy($old_session_id);
                }
                // For PHP prior to 4.3.2
            } else {
                // session_regenerate_id is not defined, create new session ID
                $session_id = md5(uniqid(rand(), true) . @$_SERVER['HTTP_USER_AGENT']);
                // Set the new session ID
                session_id($session_id);
                // Destory old session on request
                if ($delete_old_session) {
                    $this->destroy($old_session_id);
                    // switch old session to new one
                } else {
                    $sql = sprintf('UPDATE %s SET sess_id = %s WHERE sess_id = %s', $this->db->prefix('session'), $this->db->quoteString($session_id), $this->db->quoteString($old_session_id));
                    $this->db->queryF($sql);
                }
                $success = true;
            }
        }

        // Force updating cookie for session cookie is not issued correctly in some IE versions or not automatically issued prior to PHP 4.3.3 for all browsers
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
     * @param   string  $sess_id    session ID
     * @param   int     $expire     Time in seconds until a session expires
     * @return  bool
     **/
    public function update_cookie($sess_id = null, $expire = null)
    {
        $xoops = Xoops::getInstance();
        $session_name = ($xoops->getConfig('use_mysession') && $xoops->getConfig('session_name') != '') ? $xoops->getConfig('session_name') : session_name();
        $session_expire = !is_null($expire) ? intval($expire) : (($xoops->getConfig('use_mysession') && $xoops->getConfig('session_name') != '') ? $xoops->getConfig('session_expire') * 60 : ini_get("session.cookie_lifetime"));
        $session_id = empty($sess_id) ? session_id() : $sess_id;
        if (!headers_sent()) {
            setcookie($session_name, $session_id, $session_expire ? time() + $session_expire : 0, '/', XOOPS_COOKIE_DOMAIN, 0);
        }
    }
}
