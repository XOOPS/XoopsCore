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

namespace Xoops\Core\Kernel\Handlers;

use Xoops\Core\Database\Connection;
use Xoops\Core\Kernel\XoopsPersistableObjectHandler;

/**
 * XOOPS private message handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS private message class objects.
 *
 * @package        kernel
 *
 * @author        Kazumi Ono    <onokazu@xoops.org>
 * @copyright    copyright (c) 2000-2003 XOOPS Project (http://xoops.org)
 *
 * @version        $Revision$ - $Date$
 */
class XoopsPrivateMessageHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param Connection|null $db {@link Connection}
     */
    public function __construct(Connection $db = null)
    {
        parent::__construct($db, 'system_privatemessage', '\Xoops\Core\Kernel\Handlers\XoopsPrivateMessage', 'msg_id', 'subject');
    }

    /**
     * Mark a message as read
     *
     * @param XoopsPrivateMessage $pm XoopsPrivateMessage object
     * @return bool
     **/
    public function setRead(XoopsPrivateMessage $pm)
    {
        $qb = $this->db2->createXoopsQueryBuilder()
            ->update($this->table, 'pm')
            ->set('pm.read_msg', ':readmsg')
            ->where('pm.msg_id = :msgid')
            ->setParameter(':readmsg', 1, \PDO::PARAM_INT)
            ->setParameter(':msgid', (int)$pm->getVar('msg_id'), \PDO::PARAM_INT);
        $result = $qb->execute();

        if (!$result) {
            return false;
        }
        return true;
    }
}
