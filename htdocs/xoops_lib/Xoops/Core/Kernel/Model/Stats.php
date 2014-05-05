<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Kernel\Model;

use Xoops\Core\Kernel\CriteriaElement;
use Xoops\Core\Kernel\XoopsModelAbstract;

/**
 * Object stats handler class.
 *
 * @category  Xoops\Core\Kernel\Model\Stats
 * @package   Xoops\Core\Kernel
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2000-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.3.0
 */
class Stats extends XoopsModelAbstract
{
    /**
     * count objects matching a condition
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} to match
     *
     * @return int count of objects
     */
    public function getCount(CriteriaElement $criteria = null)
    {
        $qb = \Xoops::getInstance()->db()->createXoopsQueryBuilder();
        $eb = $qb->expr();

        $field = '';
        $groupby = false;
        if (isset($criteria) && ($criteria instanceof CriteriaElement)) {
            $temp = $criteria->getGroupby();
            if (!empty($temp)) {
                $qb->select($temp);
                $groupby = true;
            }
        }
        if (!$groupby) {
            $qb->select('COUNT(*)');
        } else {
            $qb->addSelect('COUNT(*)');
        }

        $qb->from($this->handler->table, null);
        if (isset($criteria) && ($criteria instanceof CriteriaElement)) {
            $qb = $criteria->renderQb($qb);
        }
        try {
            $result = $qb->execute();
            if (!$result) {
                return 0;
            }
        } catch (\Exception $e) {
            \Xoops::getInstance()->events()->triggerEvent('core.exception', $e);
            return 0;
        }

        if ($groupby == false) {
            list ($count) = $result->fetch(\PDO::FETCH_NUM);
            return $count;
        } else {
            $ret = array();
            while (list ($id, $count) = $result->fetch(\PDO::FETCH_NUM)) {
                $ret[$id] = $count;
            }
            return $ret;
        }
    }

    /**
     * get counts matching a condition
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement} to match
     *
     * @return array of counts
     */
    public function getCounts(CriteriaElement $criteria = null)
    {
        $qb = \Xoops::getInstance()->db()->createXoopsQueryBuilder();
        $eb = $qb->expr();

        $ret = array();
        $sql_where = '';
        $limit = null;
        $start = null;
        $groupby_key = $this->handler->keyName;
        if (isset($criteria) && ($criteria instanceof CriteriaElement)) {
            if ($groupby = $criteria->getGroupby()) {
                $groupby_key = $groupby;
            }
        }
        $qb->select($groupby_key)
            ->addSelect('COUNT(*)')
            ->from($this->handler->table, null);

        if (isset($criteria) && ($criteria instanceof CriteriaElement)) {
            $qb = $criteria->renderQb($qb);
        }
        $result = $qb->execute();
        if (!$result) {
            return $ret;
        }
        while (list ($id, $count) = $result->fetch(\PDO::FETCH_NUM)) {
            $ret[$id] = $count;
        }
        return $ret;
    }
}
