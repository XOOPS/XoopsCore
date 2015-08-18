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
 * @version         $Id$
 */

namespace Xoops\Core\Kernel\Handlers;

use Xoops\Core\Database\Connection;
use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\CriteriaElement;
use Xoops\Core\Kernel\XoopsPersistableObjectHandler;
use Xoops\Core\Kernel\Handlers\XoopsModule;

/**
 * XOOPS module handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS module class objects.
 *
 * @package kernel
 * @author Kazumi Ono <onokazu@xoops.org>
 * @copyright (c) 2000-2003 XOOPS Project (http://xoops.org)
 */
class XoopsModuleHandler extends XoopsPersistableObjectHandler
{
    /**
     * holds an array of cached module references, indexed by module id
     *
     * @var array
     * @access private
     */
    private $_cachedModule_mid = array();

    /**
     * holds an array of cached module references, indexed by module dirname
     *
     * @var array
     * @access private
     */
    private $_cachedModule_dirname = array();

    /**
     * Constructor
     *
     * @param Connection|null $db {@link Connection}
     */
    public function __construct(Connection $db = null)
    {
        parent::__construct($db, 'modules', 'XoopsModule', 'mid', 'dirname');
    }

    /**
     * Load a module from the database
     *
     * @param int $id ID of the module
     * @return XoopsModule|bool on fail
     */
    function getById($id = null)
    {
        $id = (int)($id);
        if ($id > 0) {
            if (!empty($this->_cachedModule_mid[$id])) {
                return $this->_cachedModule_mid[$id];
            } else {
                $module = parent::get($id);
                if (!is_object($module)) {
                    return false;
                }
                $this->_cachedModule_mid[$id] = $module;
                $this->_cachedModule_dirname[$module->getVar('dirname')] = $module;
                return $module;
            }
        }
        return false;
    }

    /**
     * Load a module by its dirname
     *
     * @param string $dirname module directory name
     *
     * @return XoopsModule|bool FALSE on fail
     */
    public function getByDirname($dirname)
    {
        $dirname = basename(trim($dirname));

        if (!empty($this->_cachedModule_dirname[$dirname])) {
            return $this->_cachedModule_dirname[$dirname];
        } else {
            $criteria = new Criteria('dirname', $dirname);
            $modules = $this->getObjectsArray($criteria);
            if (count($modules) == 1 && is_object($modules[0])) {
                $module = $modules[0];
            } else {
                return false;
            }
            /* @var $module XoopsModule */
            $this->_cachedModule_dirname[$dirname] = $module;
            $this->_cachedModule_mid[$module->getVar('mid')] = $module;
            return $module;
        }
    }

    /**
     * Write a module to the database
     *
     * @param XoopsModule $module reference to a {@link XoopsModule}
     *
     * @return bool
     */
    public function insertModule(XoopsModule &$module)
    {
        if (!parent::insert($module)) {
            return false;
        }

        $dirname = $module->getVar('dirname');
        $mid = $module->getVar('mid');

        if (!empty($this->_cachedModule_dirname[$dirname])) {
            unset($this->_cachedModule_dirname[$dirname]);
        }
        if (!empty($this->_cachedModule_mid[$mid])) {
            unset($this->_cachedModule_mid[$mid]);
        }
        return true;
    }

    /**
     * Delete a module from the database
     *
     * @param XoopsModule &$module
     * @return bool
     */
    public function deleteModule(XoopsModule &$module)
    {
        if (!parent::delete($module)) {
            return false;
        }

        $mid = $module->getVar('mid');
        $dirname = $module->getVar('dirname');

        // delete admin and read permissions assigned for this module
        $qb = $this->db2->createXoopsQueryBuilder();
        $eb = $qb->expr();
        $qb ->deletePrefix('group_permission')
            ->where(
                $eb->orX(
                    $eb->eq('gperm_name', $eb->literal('module_admin')),
                    $eb->eq('gperm_name', $eb->literal('module_read'))
                )
            )
            ->andWhere($eb->eq('gperm_itemid', ':itemid'))
            ->setParameter(':itemid', $mid, \PDO::PARAM_INT);
        $result = $qb->execute();

        $qb->resetQueryParts(); // reset
        $qb ->select('block_id')
            ->fromPrefix('block_module_link', null)
            ->where($eb->eq('module_id', ':mid'))
            ->setParameter(':mid', $mid, \PDO::PARAM_INT);
        $result = $qb->execute();
        $block_id_arr = array();
        while ($myrow = $result->fetch(\PDO::FETCH_ASSOC)) {
            array_push($block_id_arr, $myrow['block_id']);
        }

        foreach ($block_id_arr as $i) {
            $qb->resetQueryParts(); // reset
            $qb ->select('COUNT(*)')
                ->fromPrefix('block_module_link', null)
                ->where($eb->ne('module_id', ':mid'))
                ->setParameter(':mid', $mid, \PDO::PARAM_INT)
                ->andWhere($eb->eq('block_id', ':bid'))
                ->setParameter(':bid', $i, \PDO::PARAM_INT);
            $result = $qb->execute();
            $count = $result->fetchColumn(0);

            if ($count > 0) {
                // this block has other entries, so delete the entry for this module
                $qb->resetQueryParts(); // reset
                $qb ->deletePrefix('block_module_link')
                    ->where($eb->eq('module_id', ':mid'))
                    ->setParameter(':mid', $mid, \PDO::PARAM_INT)
                    ->andWhere($eb->eq('block_id', ':bid'))
                    ->setParameter(':bid', $i, \PDO::PARAM_INT)
                    ->execute();
            } else {
                // this block doesnt have other entries, so disable the block and let it show on top page only. otherwise, this block will not display anymore on block admin page!
                $qb->resetQueryParts(); // reset
                $qb ->updatePrefix('newblocks')
                    ->set('visible', ':notvisible')
                    ->where($eb->eq('bid', ':bid'))
                    ->setParameter(':bid', $i, \PDO::PARAM_INT)
                    ->setParameter(':notvisible', 0, \PDO::PARAM_INT)
                    ->execute();

                $qb->resetQueryParts(); // reset
                $qb ->updatePrefix('block_module_link')
                    ->set('module_id', ':nomid')
                    ->where($eb->eq('module_id', ':mid'))
                    ->setParameter(':mid', $mid, \PDO::PARAM_INT)
                    ->setParameter(':nomid', -1, \PDO::PARAM_INT)
                    ->execute();
            }
        }

        if (!empty($this->_cachedModule_dirname[$dirname])) {
            unset($this->_cachedModule_dirname[$dirname]);
        }
        if (!empty($this->_cachedModule_mid[$mid])) {
            unset($this->_cachedModule_mid[$mid]);
        }
        $cache = \Xoops::getInstance()->cache();
        $cache->delete("system/module/id/{$mid}");
        $cache->delete("system/module/dirname/{$dirname}");
        $cache->delete("module/{$dirname}");
        return true;
    }

    /**
     * Load some modules
     *
     * @param CriteriaElement|null $criteria  {@link CriteriaElement}
     * @param boolean              $id_as_key Use the ID as key into the array
     *
     * @return array
     */
    public function getObjectsArray(CriteriaElement $criteria = null, $id_as_key = false)
    {
        $ret = array();
        $qb = $this->db2->createXoopsQueryBuilder();
        $qb->select('*')->fromPrefix('modules', null);
        if (isset($criteria) && ($criteria instanceof CriteriaElement)) {
            $criteria->setSort('weight');
            $criteria->renderQb($qb);
            $qb->addOrderBy('mid', 'ASC');
        }
        try {
            if (!$result = $qb->execute()) {
                return $ret;
            }
        } catch (Exception $e) {
            return $ret;
        }
        while ($myrow = $result->fetch(\PDO::FETCH_ASSOC)) {
            $module = new XoopsModule();
            $module->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] = $module;
            } else {
                $ret[$myrow['mid']] = $module;
            }
            unset($module);
        }
        return $ret;
    }

    /**
     * returns an array of module names
     *
     * @param CriteriaElement|null $criteria       criteria
     * @param boolean              $dirname_as_key true  = array key is module directory
     *                                             false = array key is module id
     *
     * @return array
     */
    public function getNameList(CriteriaElement $criteria = null, $dirname_as_key = false)
    {
        $ret = array();
        $modules = $this->getObjectsArray($criteria, true);
        foreach (array_keys($modules) as $i) {
            if (!$dirname_as_key) {
                $ret[$i] = $modules[$i]->getVar('name');
            } else {
                $ret[$modules[$i]->getVar('dirname')] = $modules[$i]->getVar('name');
            }
        }
        return $ret;
    }
}
