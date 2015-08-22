<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Kernel\Handlers;

use Xoops\Core\Database\Connection;
use Xoops\Core\Kernel\CriteriaElement;
use Xoops\Core\Kernel\XoopsPersistableObjectHandler;
use Xoops\Core\Kernel\Handlers\XoopsBlock;

class XoopsBlockHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param Connection|null $db {@link \Xoops\Core\Database\Connection}
     */
    public function __construct(Connection $db = null)
    {
        parent::__construct($db, 'newblocks', '\\Xoops\\Core\\Kernel\\Handlers\\XoopsBlock', 'bid', 'name');
    }

    /**
     * @param XoopsBlock $obj
     * @param bool $force
     * @return mixed
     */
    public function insertBlock(XoopsBlock &$obj, $force = false)
    {
        $obj->setVar('last_modified', time());
        return parent::insert($obj, $force);
    }

    /**
     * Delete a ID from the database
     *
     * @param XoopsBlock $obj
     * @return bool
     */
    public function deleteBlock(XoopsBlock &$obj)
    {
        if (!parent::delete($obj)) {
            return false;
        }
        $qb = $this->db2->createXoopsQueryBuilder();
        $eb = $qb->expr();
        $qb ->deletePrefix('group_permission', null)
            ->where($eb->eq('gperm_name', $eb->literal('block_read')))
            ->andWhere($eb->eq('gperm_itemid', $qb->createNamedParameter($obj->getVar('bid'), \PDO::PARAM_INT)))
            ->andWhere($eb->eq('gperm_modid', $qb->createNamedParameter(1, \PDO::PARAM_INT)))
            ->execute();

        $qb ->deletePrefix('block_module_link', null)
            ->where($eb->eq('block_id', $qb->createNamedParameter($obj->getVar('bid'), \PDO::PARAM_INT)))
            ->execute();

        return true;
    }

    /**
     * retrieve array of {@link XoopsBlock}s meeting certain conditions
     * @param CriteriaElement|null $criteria {@link CriteriaElement} with conditions for the blocks
     * @param bool $id_as_key should the blocks' bid be the key for the returned array?
     * @return array {@link XoopsBlock}s matching the conditions
     **/
    public function getDistinctObjects(CriteriaElement $criteria = null, $id_as_key = false)
    {
        $ret = array();

        $qb = $this->db2->createXoopsQueryBuilder();
        $eb = $qb->expr();
        $qb ->select('DISTINCT(b.bid)')
            ->addSelect('b.*')
            ->fromPrefix('newblocks', 'b')
            ->leftJoinPrefix('b', 'block_module_link', 'l', $eb->eq('b.bid', 'l.block_id'));

        if (isset($criteria) && ($criteria instanceof CriteriaElement)) {
            $criteria->renderQb($qb);
        }

        $result = $qb->execute();
        if (!$result) {
            return $ret;
        }
        while ($myrow = $result->fetch(\PDO::FETCH_ASSOC)) {
            $block = new XoopsBlock();
            $block->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] = $block;
            } else {
                $ret[$myrow['bid']] = $block;
            }
            unset($block);
        }
        return $ret;

    }

    /**
     * get a list of blocks matching certain conditions
     *
     * @param CriteriaElement|null $criteria conditions to match
     * @return array array of blocks matching the conditions
     **/
    public function getNameList(CriteriaElement $criteria = null)
    {
        $blocks = $this->getObjects($criteria, true);
        $ret = array();
        foreach (array_keys($blocks) as $i) {
            $name = (!$blocks[$i]->isCustom()) ? $blocks[$i]->getVar('name') : $blocks[$i]->getVar('title');
            $ret[$i] = $name;
        }
        return $ret;
    }

    /**
     * get all the blocks that match the supplied parameters
     *
     * @param int $side   0: sideblock - left
     *        1: sideblock - right
     *        2: sideblock - left and right
     *        3: centerblock - left
     *        4: centerblock - right
     *        5: centerblock - center
     *        6: centerblock - left, right, center
     * @param bool $asobject
     * @param int|array $groupid   groupid (can be an array)
     * @param int|null $visible   0: not visible 1: visible
     * @param string $orderby   order of the blocks
     * @param int $isactive
     * @return array of block objects
     */
    public function getAllBlocksByGroup($groupid, $asobject = true, $side = null, $visible = null, $orderby = "b.weight,b.bid", $isactive = 1)
    {
        $ret = array();
        $qb = $this->db2->createXoopsQueryBuilder();
        $eb = $qb->expr();
        if ($asobject) {
            $qb ->select('b.*');
        } else {
            $qb ->select('b.bid');
        }
        $qb ->fromPrefix('newblocks', 'b')
            ->leftJoinPrefix('b', 'group_permission', 'l', $eb->eq('b.bid', 'l.gperm_itemid'))
            ->where($eb->eq('gperm_name', $eb->literal('block_read')))
            ->andWhere($eb->eq('gperm_modid', 1));

        if (is_array($groupid)) {
            if (count($groupid) > 1) {
                $in=array();
                foreach ($groupid as $gid) {
                    $in[] = $qb->createNamedParameter($gid, \PDO::PARAM_INT);
                }
                $qb->andWhere($eb->in('l.gperm_groupid', $in));
            }
        } else {
            $qb->andWhere($eb->eq('l.gperm_groupid', $qb->createNamedParameter($groupid, \PDO::PARAM_INT)));
        }
        $qb->andWhere($eb->eq('b.isactive', $qb->createNamedParameter($isactive, \PDO::PARAM_INT)));
        if (isset($side)) {
            // get both sides in sidebox? (some themes need this)
            if ($side == XOOPS_SIDEBLOCK_BOTH) {
                $qb->andWhere($eb->in('b.side', array(0,1)));
            } elseif ($side == XOOPS_CENTERBLOCK_ALL) {
                $qb->andWhere($eb->in('b.side', array(3,4,5,7,8,9)));
            } else {
                $qb->andWhere($eb->eq('b.side', $qb->createNamedParameter($side, \PDO::PARAM_INT)));
            }
        }
        if (isset($visible)) {
            $qb->andWhere($eb->eq('b.visible', $qb->createNamedParameter($visible, \PDO::PARAM_INT)));
        }
        $qb->orderBy($orderby);
        $result = $qb->execute();
        $added = array();
        while ($myrow = $result->fetch(\PDO::FETCH_ASSOC)) {
            if (!in_array($myrow['bid'], $added)) {
                if (!$asobject) {
                    $ret[] = $myrow['bid'];
                } else {
                    $ret[] = new XoopsBlock($myrow);
                }
                array_push($added, $myrow['bid']);
            }
        }
        return $ret;
    }

    /**
     * @param string $rettype
     * @param boolean $side
     * @param null $visible
     * @param string $orderby
     * @param int $isactive
     * @return array
     */
    public function getAllBlocks($rettype = "object", $side = null, $visible = null, $orderby = "side,weight,bid", $isactive = 1)
    {
        $ret = array();
        $qb = $this->db2->createXoopsQueryBuilder();
        $eb = $qb->expr();

        $qb ->fromPrefix('newblocks', null)
            ->where($eb->eq('isactive', $qb->createNamedParameter($isactive, \PDO::PARAM_INT)));
        if (isset($side)) {
            // get both sides in sidebox? (some themes need this)
            if ($side == XOOPS_SIDEBLOCK_BOTH) {
                $qb->andWhere($eb->in('side', array(0,1)));
            } elseif ($side == XOOPS_CENTERBLOCK_ALL) {
                $qb->andWhere($eb->in('side', array(3,4,5,7,8,9)));
            } else {
                $qb->andWhere($eb->eq('side', $qb->createNamedParameter($side, \PDO::PARAM_INT)));
            }
        }
        if (isset($visible)) {
            $qb->andWhere($eb->eq('visible', $qb->createNamedParameter($visible, \PDO::PARAM_INT)));
        }
        $qb->orderBy($orderby);
        switch ($rettype) {
            case "object":
                $qb->select('*');
                $result = $qb->execute();
                while ($myrow = $result->fetch(\PDO::FETCH_ASSOC)) {
                    $ret[] = new XoopsBlock($myrow);
                }
                break;
            case "list":
                $qb->select('*');
                $result = $qb->execute();
                while ($myrow = $result->fetch(\PDO::FETCH_ASSOC)) {
                    $block = new XoopsBlock($myrow);
                    $title = $block->getVar("title");
                    $title = empty($title) ? $block->getVar("name") : $title;
                    $ret[$block->getVar("bid")] = $title;
                }
                break;
            case "id":
                $qb->select('bid');
                $result = $qb->execute();
                while ($myrow = $result->fetch(\PDO::FETCH_ASSOC)) {
                    $ret[] = $myrow['bid'];
                }
                break;
        }

        return $ret;
    }

    /**
     * @param int $moduleid
     * @param bool $asobject
     * @return array
     */
    public function getByModule($moduleid, $asobject = true)
    {
        $qb = $this->db2->createXoopsQueryBuilder();
        $eb = $qb->expr();

        $qb ->fromPrefix('newblocks', null)
            ->where($eb->eq('mid', $qb->createNamedParameter($moduleid, \PDO::PARAM_INT)));
        if ($asobject == true) {
            $qb->select('*');
        } else {
            $qb->select('bid');
        }

        $ret = array();
        $result = $qb->execute();
        while ($myrow = $result->fetch(\PDO::FETCH_ASSOC)) {
            if ($asobject) {
                $ret[] = new XoopsBlock($myrow);
            } else {
                $ret[] = $myrow['bid'];
            }
        }
        return $ret;
    }

    /**
     * XoopsBlock::getAllByGroupModule()
     *
     * @param mixed $groupid
     * @param integer $module_id
     * @param boolean $toponlyblock
     * @param mixed $visible
     * @param string $orderby
     * @param integer $isactive
     * @return array
     */
    public function getAllByGroupModule(
        $groupid,
        $module_id = 0,
        $toponlyblock = false,
        $visible = null,
        $orderby = 'b.weight, m.block_id',
        $isactive = 1
    ) {
        $ret = array();

        $qb = $this->db2->createXoopsQueryBuilder();
        $eb = $qb->expr();

        $blockids=null;
        if (isset($groupid)) {
            $qb ->select('DISTINCT gperm_itemid')
                ->fromPrefix('group_permission', null)
                ->where($eb->eq('gperm_name', $eb->literal('block_read')))
                ->andWhere('gperm_modid=1');

            if (is_array($groupid) AND !empty($groupid)) {
                $qb->andWhere($eb->in('gperm_groupid', $groupid));
            } else {
                if ((int)($groupid) > 0) {
                    $qb->andWhere($eb->eq('gperm_groupid', $groupid));
                }
            }
            $result = $qb->execute();
            $blockids = $result->fetchAll(\PDO::FETCH_COLUMN, 0);
        }

        $qb->resetQueryParts();

        $qb ->select('b.*')
            ->fromPrefix('newblocks', 'b')
            ->where($eb->eq('b.isactive', $qb->createNamedParameter($isactive, \PDO::PARAM_INT)));
        if (isset($visible)) {
            $qb->andWhere($eb->eq('b.visible', $qb->createNamedParameter($visible, \PDO::PARAM_INT)));
        }
        if (isset($module_id)) {
            $qb ->fromPrefix('block_module_link', 'm')
                ->andWhere($eb->eq('m.block_id', 'b.bid'));
            if (!empty($module_id)) {
                $in=array();
                $in[]=0;
                $in[]=(int)($module_id);
                if ($toponlyblock) {
                    $in[]=(int)(-1);
                }
            } else {
                if ($toponlyblock) {
                    $in=array(0, -1);
                } else {
                    $in=0;
                }
            }
            if (is_array($in)) {
                $qb->andWhere($eb->in('m.module_id', $in));
            } else {
                $qb->andWhere($eb->eq('m.module_id', $in));
            }
        }
        if (!empty($blockids)) {
            $qb->andWhere($eb->in('b.bid', $blockids));
        }
        $qb->orderBy($orderby);
        $result = $qb->execute();
        while ($myrow = $result->fetch(\PDO::FETCH_ASSOC)) {
            $block = new XoopsBlock($myrow);
            $ret[$myrow['bid']] = $block;
            unset($block);
        }
        return $ret;
    }

    /**
     * XoopsBlock::getNonGroupedBlocks()
     *
     * @param integer $module_id
     * @param boolean $toponlyblock
     * @param boolean $visible
     * @param string $orderby
     * @param integer $isactive
     * @return array
     */
    public function getNonGroupedBlocks($module_id = 0, $toponlyblock = false, $visible = null, $orderby = 'b.weight, m.block_id', $isactive = 1)
    {
        $ret = array();

        $qb = $this->db2->createXoopsQueryBuilder();
        $eb = $qb->expr();

        $qb ->select('DISTINCT(bid)')
            ->fromPrefix('newblocks', null);
        $result = $qb->execute();
        $bids = $result->fetchAll(\PDO::FETCH_COLUMN, 0);

        $qb->resetQueryParts();

        $qb ->select('DISTINCT(p.gperm_itemid)')
            ->fromPrefix('group_permission', 'p')
            ->fromPrefix('groups', 'g')
            ->where($eb->eq('g.groupid', 'p.gperm_groupid'))
            ->andWhere($eb->eq('p.gperm_name', $eb->literal('block_read')));
        $result = $qb->execute();
        $grouped = $result->fetchAll(\PDO::FETCH_COLUMN, 0);

        $non_grouped = array_diff($bids, $grouped);

        if (!empty($non_grouped)) {
            $qb->resetQueryParts();

            $qb ->select('b.*')
                ->fromPrefix('newblocks', 'b')
                ->where($eb->eq('b.isactive', $qb->createNamedParameter($isactive, \PDO::PARAM_INT)));
            if (isset($visible)) {
                $qb->andWhere($eb->eq('b.visible', $qb->createNamedParameter($visible, \PDO::PARAM_INT)));
            }

            $sql = 'SELECT b.* FROM ' . $this->db2->prefix('newblocks') . ' b, '
            . $this->db2->prefix('block_module_link') . ' m';
            $sql .= ' WHERE b.isactive=' . (int)($isactive);
            if (isset($visible)) {
                $sql .= ' AND b.visible=' . (int)($visible);
            }
            if (isset($module_id)) {
                $qb ->fromPrefix('block_module_link', 'm')
                    ->andWhere($eb->eq('m.block_id', 'b.bid'));
                if (!empty($module_id)) {
                    $in=array();
                    $in[]=0;
                    $in[]=(int)($module_id);
                    if ($toponlyblock) {
                        $in[]=(int)(-1);
                    }
                } else {
                    if ($toponlyblock) {
                        $in=array(0, -1);
                    } else {
                        $in=0;
                    }
                }
                if (is_array($in)) {
                    $qb->andWhere($eb->in('m.module_id', $in));
                } else {
                    $qb->andWhere($eb->eq('m.module_id', $in));
                }
            }
            $qb->andWhere($eb->in('b.bid', $non_grouped));
            $qb->orderBy($orderby);
            $result = $qb->execute();
            while ($myrow = $result->fetch(\PDO::FETCH_ASSOC)) {
                $block = new XoopsBlock($myrow);
                $ret[$myrow['bid']] = $block;
                unset($block);
            }
        }
        return $ret;
    }

    /**
     * XoopsBlock::countSimilarBlocks()
     *
     * @param int $moduleId
     * @param string $funcNum
     * @param string $showFunc
     * @return int
     */
    public function countSimilarBlocks($moduleId, $funcNum, $showFunc = null)
    {
        $funcNum = (int)($funcNum);
        $moduleId = (int)($moduleId);
        if ($funcNum < 1 || $moduleId < 1) {
            // invalid query
            return 0;
        }

        $qb = $this->db2->createXoopsQueryBuilder();
        $eb = $qb->expr();

        $qb ->select('COUNT(*)')
            ->fromPrefix('newblocks', null)
            ->where($eb->eq('mid', $qb->createNamedParameter($moduleId, \PDO::PARAM_INT)))
            ->andWhere($eb->eq('func_num', $qb->createNamedParameter($funcNum, \PDO::PARAM_INT)));

        if (isset($showFunc)) {
            // showFunc is set for more strict comparison
            $qb->andWhere($eb->eq('show_func', $qb->createNamedParameter($showFunc, \PDO::PARAM_STR)));
        }
        if (!$result = $qb->execute()) {
            return 0;
        }
        list ($count) = $result->fetch(\PDO::FETCH_NUM);
        return $count;
    }

    /**
     * Aligns the content of a block
     * If position is 0, content in DB is positioned
     * before the original content
     * If position is 1, content in DB is positioned
     * after the original content
     *
     * @param integer $position
     * @param string $content
     * @param string $contentdb
     * @return string
     */
    public function buildContent($position, $content = "", $contentdb = "")
    {
        $ret = '';
        if ($position == 0) {
            $ret = $contentdb . $content;
        } else {
            if ($position == 1) {
                $ret = $content . $contentdb;
            }
        }
        return $ret;
    }

    /**
     * Enter description here...
     *
     * @param string $originaltitle
     * @param string $newtitle
     * @return string title
     */
    public function buildTitle($originaltitle, $newtitle = '')
    {
        if ($newtitle != '') {
            $ret = $newtitle;
        } else {
            $ret = $originaltitle;
        }
        return $ret;
    }

    /************ system ***************/

    /**
     * @param null|integer $groupid
     * @return array
     */
    public function getBlockByPerm($groupid)
    {
        $ret = array();
        if (isset($groupid)) {
            $qb = $this->db2->createXoopsQueryBuilder();
            $eb = $qb->expr();

            $qb ->select('DISTINCT(gperm_itemid)')
                ->fromPrefix('group_permission', 'p')
                ->fromPrefix('groups', 'g')
                ->where($eb->eq('p.gperm_name', $eb->literal('block_read')))
                ->andWhere('gperm_modid=1');

            if (is_array($groupid)) {
                $qb->andWhere($eb->in('gperm_groupid', $groupid));
            } else {
                if ((int)($groupid) > 0) {
                    $qb->andWhere($eb->eq('gperm_groupid', $groupid));
                }
            }

            $result = $qb->execute();
            $blockids = $result->fetchAll(\PDO::FETCH_COLUMN, 0);
            return $blockids;
        }
        return $ret;
    }
}
