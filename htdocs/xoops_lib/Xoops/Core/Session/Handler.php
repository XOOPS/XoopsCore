<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Session;

/**
 * Handler for database session storage
 *
 * @category  Xoops\Core\Session
 * @package   Handler
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2000-2015 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Handler implements \SessionHandlerInterface
{
    /**
     * @var \Xoops\Core\Database\Connection
     */
    private $db;

    /**
     * @var string $sessionTable
     */
    private $sessionTable = 'system_session';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = \Xoops::getInstance()->db();
    }

    /**
     * Open a session
     *
     * @param string $save_path not used
     * @param string $name      not used
     *
     * @return bool
     */
    public function open($save_path, $name)
    {
        return true;
    }

    /**
     * Close a session
     *
     * @return bool
     */
    public function close()
    {
        return true;
    }

    /**
     * Read a session from the database
     *
     * @param string $session_id Id of the session
     *
     * @return string Session data
     */
    public function read($session_id)
    {
        $qb = $this->db->createXoopsQueryBuilder();
        $eb = $qb->expr();
        $qb ->select('s.session_data')
            ->fromPrefix($this->sessionTable, 's')
            ->where($eb->eq('s.session_id', ':sessid'))
            ->andWhere($eb->gt('s.expires_at', ':expires'))
            ->setParameter(':sessid', $session_id, \PDO::PARAM_STR)
            ->setParameter(':expires', time(), \PDO::PARAM_INT);

        if ($result = $qb->execute()) {
            if ($row = $result->fetch(\PDO::FETCH_NUM)) {
                list ($session_data) = $row;
                return $session_data;
            }
        }

        // if system is not configured for garbage collect, force it anyway
        if ((ini_get('session.gc_probability') == 0) && (rand(1, 100) <= 5)) {
            $this->gc(0);
        }

        return '';
    }

    /**
     * Write a session to the database
     *
     * @param string $session_id   id of session
     * @param string $session_data data to store
     *
     * @return bool
     **/
    public function write($session_id, $session_data)
    {
        $expires =  (isset($_SESSION['SESSION_MANAGER_EXPIRES']))
            ? intval($_SESSION['SESSION_MANAGER_EXPIRES'])
            : time() + (session_cache_expire() * 60);
        $qb = $this->db->createXoopsQueryBuilder();
        $eb = $qb->expr();
        $qb ->updatePrefix($this->sessionTable)
            ->set('expires_at', ':expires')
            ->set('session_data', ':sessdata')
            ->where($eb->eq('session_id', ':sessid'))
            ->setParameter(':sessid', $session_id, \PDO::PARAM_STR)
            ->setParameter(':expires', $expires, \PDO::PARAM_INT)
            ->setParameter(':sessdata', $session_data, \PDO::PARAM_STR);
        $result = $qb->execute();
        if ($result<=0) {
            $this->db->insertPrefix(
                $this->sessionTable,
                array(
                    'session_id'   => $session_id,
                    'expires_at'   => $expires,
                    'session_data' => $session_data,
                )
            );
        }

        return ($result);
    }

    /**
     * Destroy a session
     *
     * @param string $session_id Id of session
     *
     * @return bool
     */
    public function destroy($session_id)
    {
        $qb = $this->db->createXoopsQueryBuilder();
        $eb = $qb->expr();
        $qb ->deletePrefix($this->sessionTable)
            ->where($eb->eq('session_id', ':sessid'))
            ->setParameter(':sessid', $session_id, \PDO::PARAM_STR);
        return $qb->execute();
    }

    /**
     * Garbage Collector
     *
     * @param string $maxlifetime Time in seconds until a session expires
     *
     * @return bool
     */
    public function gc($maxlifetime)
    {
        $mintime = time();
        $qb = $this->db->createXoopsQueryBuilder();
        $eb = $qb->expr();
        $qb ->deletePrefix($this->sessionTable)
            ->where($eb->lt('expires_at', ':expires'))
            ->setParameter(':expires', $mintime, \PDO::PARAM_INT);
        return $qb->execute();
    }
}
