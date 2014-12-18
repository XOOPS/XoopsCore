<?php
/**
 * XOOPS kernel class
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

use Xoops\Core\Database\Connection;
use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\CriteriaCompo;
use Xoops\Core\Kernel\CriteriaElement;
use Xoops\Core\Kernel\XoopsObject;
use Xoops\Core\Kernel\XoopsPersistableObjectHandler;

/**
 * A Template File
 *
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000 XOOPS.org
 * @package   kernel
 */
class XoopsTplfile extends XoopsObject
{
    /**
     * Constructor
     *
     * @return XoopsTplfile
     */
    public function __construct()
    {
        $this->initVar('tpl_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('tpl_refid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('tpl_tplset', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('tpl_file', XOBJ_DTYPE_TXTBOX, null, true, 100);
        $this->initVar('tpl_desc', XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('tpl_lastmodified', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('tpl_lastimported', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('tpl_module', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('tpl_type', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('tpl_source', XOBJ_DTYPE_SOURCE, null, false);
    }

    /**
     * id
     *
     * @param string $format
     *
     * @return mixed
     */
    public function id($format = 'n')
    {
        return $this->getVar('tpl_id', $format);
    }

    /**
     * tpl_id
     *
     * @param string $format
     *
     * @return mixed
     */
    public function tpl_id($format = '')
    {
        return $this->getVar('tpl_id', $format);
    }

    /**
     * tpl_refid
     *
     * @param string $format
     *
     * @return mixed
     */
    public function tpl_refid($format = '')
    {
        return $this->getVar('tpl_refid', $format);
    }

    /**
     * tpl_tplset
     *
     * @param string $format
     *
     * @return mixed
     */
    public function tpl_tplset($format = '')
    {
        return $this->getVar('tpl_tplset', $format);
    }

    /**
     * tpl_file
     *
     * @param string $format
     *
     * @return mixed
     */
    public function tpl_file($format = '')
    {
        return $this->getVar('tpl_file', $format);
    }

    /**
     * tpl_desc
     *
     * @param string $format
     *
     * @return mixed
     */
    public function tpl_desc($format = '')
    {
        return $this->getVar('tpl_desc', $format);
    }

    /**
     * tpl_lastmodified
     *
     * @param string $format
     *
     * @return mixed
     */
    public function tpl_lastmodified($format = '')
    {
        return $this->getVar('tpl_lastmodified', $format);
    }

    /**
     * tpl_lastimported
     *
     * @param string $format
     *
     * @return mixed
     */
    public function tpl_lastimported($format = '')
    {
        return $this->getVar('tpl_lastimported', $format);
    }

    /**
     * tpl_module
     *
     * @param string $format
     *
     * @return mixed
     */
    public function tpl_module($format = '')
    {
        return $this->getVar('tpl_module', $format);
    }

    /**
     * tpl_type
     *
     * @param string $format
     *
     * @return mixed
     */
    public function tpl_type($format = '')
    {
        return $this->getVar('tpl_type', $format);
    }

    /**
     * tpl_source
     *
     * @param string $format
     *
     * @return mixed
     */
    public function tpl_source($format = '')
    {
        return $this->getVar('tpl_source', $format);
    }


    /**
     * getSource
     *
     * @return string
     */
    public function getSource()
    {
        return $this->getVar('tpl_source');
    }

    /**
     * getLastModified
     *
     * @return int
     */
    public function getLastModified()
    {
        return $this->getVar('tpl_lastmodified');
    }
}

/**
 * XOOPS template file handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS template file class objects.
 *
 *
 * @author  Kazumi Ono <onokazu@xoops.org>
 */
class XoopsTplfileHandler extends XoopsPersistableObjectHandler
{

    /**
     * Constructor
     *
     * @param Connection|null $db {@link Connection}
     */
    public function __construct(Connection $db = null)
    {
        parent::__construct($db, 'tplfile', 'XoopsTplfile', 'tpl_id', 'tpl_refid');
    }

    /**
     * retrieve a specific {@link XoopsTplfile}
     *
     * @param int  $id        tpl_id of the block to retrieve
     * @param bool $getsource true = also return source
     *
     * @return XoopsTplfile|bool
     */
    public function getById($id, $getsource = false)
    {
        $qb = $this->db2->createXoopsQueryBuilder();
        $eb = $qb->expr();
        $tplfile = false;
        $id = intval($id);
        if ($id > 0) {
            if (!$getsource) {
                $qb->select('*')
                    ->fromPrefix('tplfile', 'f')
                    ->where($eb->eq('f.tpl_id', ':tplid'))
                    ->setParameter(':tplid', $id, \PDO::PARAM_INT);
            } else {
                $qb->select('f.*')
                    ->addSelect('s.tpl_source')
                    ->fromPrefix('tplfile', 'f')
                    ->leftJoinPrefix('f', 'tplsource', 's', $eb->eq('s.tpl_id', 'f.tpl_id'))
                    ->where($eb->eq('f.tpl_id', ':tplid'))
                    ->setParameter(':tplid', $id, \PDO::PARAM_INT);
            }
            $result = $qb->execute();
            if (!$result) {
                return $tplfile;
            }
            $allrows = $result->fetchAll();
            if (count($allrows) == 1) {
                $tplfile = new XoopsTplfile();
                $tplfile->assignVars(reset($allrows));
            }
        }
        return $tplfile;
    }

    /**
     * loadSource
     *
     * @param XoopsTplfile &$tplfile
     *
     * @return bool
     */
    public function loadSource(XoopsTplFile &$tplfile)
    {
        if (!$tplfile->getVar('tpl_source')) {
            $qb = $this->db2->createXoopsQueryBuilder();
            $eb = $qb->expr();
            $qb->select('tpl_source')
                ->fromPrefix('tplsource', null)
                ->where($eb->eq('tpl_id', ':tplid'))
                ->setParameter(':tplid', $tplfile->getVar('tpl_id'), \PDO::PARAM_INT);
            if (!$result = $qb->execute()) {
                return false;
            }
            $myrow = $result->fetch(\PDO::FETCH_ASSOC);
            $tplfile->assignVar('tpl_source', $myrow['tpl_source']);
        }
        return true;
    }

    /**
     * write a new Tplfile into the database
     *
     * @param XoopsTplfile|XoopsObject $tplfile
     *
     * @return bool
     */
    public function insertTpl(XoopsTplfile &$tplfile)
    {
        if (!$tplfile->isDirty()) {
            return true;
        }
        if (!$tplfile->cleanVars(false)) {
            return false;
        }
        foreach ($tplfile->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if ($tplfile->isNew()) {
            $tpl_id = 0;
            $values = array(
                // 'tpl_id' => $tpl_id,
                'tpl_module' => $tpl_module,
                'tpl_refid' => $tpl_refid,
                'tpl_tplset' => $tpl_tplset,
                'tpl_file' => $tpl_file,
                'tpl_desc' => $tpl_desc,
                'tpl_lastmodified' => $tpl_lastmodified,
                'tpl_lastimported' => $tpl_lastimported,
                'tpl_type' => $tpl_type,
            );
            if (!$this->db2->insertPrefix('tplfile', $values)) {
                return false;
            }
            if (empty($tpl_id)) {
                $tpl_id = $this->db2->lastInsertId();
            }
            if (isset($tpl_source) && $tpl_source != '') {
                $values = array(
                    'tpl_id' => $tpl_id,
                    'tpl_source' => $tpl_source,
                );
                if (!$this->db2->insertPrefix('tplsource', $values)) {
                    $this->db2->deletePrefix('tplfile', array('tpl_id' => $tpl_id));
                    return false;
                }
            }
            $tplfile->assignVar('tpl_id', $tpl_id);
        } else {
            $values = array(
                // 'tpl_id' => $tpl_id,
                'tpl_module' => $tpl_module,
                'tpl_refid' => $tpl_refid,
                'tpl_tplset' => $tpl_tplset,
                'tpl_file' => $tpl_file,
                'tpl_desc' => $tpl_desc,
                'tpl_lastmodified' => $tpl_lastmodified,
                'tpl_lastimported' => $tpl_lastimported,
                'tpl_type' => $tpl_type,
            );
            if (!$this->db2->updatePrefix('tplfile', $values, array('tpl_id' => $tpl_id))) {
                return false;
            }

            if (isset($tpl_source) && $tpl_source != '') {
                $values = array(
                    // 'tpl_id' => $tpl_id,
                    'tpl_source' => $tpl_source,
                );
                if ($this->db2->updatePrefix('tplsource', $values, array('tpl_id' => $tpl_id))) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * forceUpdate
     *
     * @param XoopsTplfile $tplfile
     *
     * @return bool
     */
    public function forceUpdate(XoopsTplfile &$tplfile)
    {
        if (!$tplfile->isDirty()) {
            return true;
        }
        if (!$tplfile->cleanVars(false)) {
            return false;
        }
        foreach ($tplfile->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if (!$tplfile->isNew()) {
            $tpl_id = 0;
            $values = array(
                // 'tpl_id' => $tpl_id,
                'tpl_module' => $tpl_module,
                'tpl_refid' => $tpl_refid,
                'tpl_tplset' => $tpl_tplset,
                'tpl_file' => $tpl_file,
                'tpl_desc' => $tpl_desc,
                'tpl_lastmodified' => $tpl_lastmodified,
                'tpl_lastimported' => $tpl_lastimported,
                'tpl_type' => $tpl_type,
            );
            if (!$this->db2->updatePrefix('tplfile', $values, array('tpl_id' => $tpl_id))) {
                return false;
            }

            if (isset($tpl_source) && $tpl_source != '') {
                $tpl_id = 0;
                $values = array(
                    // 'tpl_id' => $tpl_id,
                    'tpl_source' => $tpl_source,
                );
                if ($this->db2->updatePrefix('tplsource', $values, array('tpl_id' => $tpl_id))) {
                    return false;
                }
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * delete a block from the database
     *
     * @param XoopsTplfile $tplfile
     *
     * @return bool
     */
    public function deleteTpl(XoopsTplfile &$tplfile)
    {
        $tpl_id = $tplfile->getVar('tpl_id');
        if (!$this->db2->deletePrefix('tplfile', array('tpl_id' => $tpl_id))) {
            return false;
        }
        $this->db2->deletePrefix('tplsource', array('tpl_id' => $tpl_id));
        return true;
    }

    /**
     * getTplObjects
     *
     * @param CriteriaElement|null $criteria
     * @param bool                 $getsource
     * @param bool                 $id_as_key
     *
     * @return array
     */
    public function getTplObjects(CriteriaElement $criteria = null, $getsource = false, $id_as_key = false)
    {
        $qb = $this->db2->createXoopsQueryBuilder();
        $eb = $qb->expr();

        $ret = array();

        if (!$getsource) {
            $qb->select('*')
                ->fromPrefix('tplfile', 'f');
        } else {
            $qb->select('f.*')
                ->addSelect('s.tpl_source')
                ->fromPrefix('tplfile', 'f')
                ->leftJoinPrefix('f', 'tplsource', 's', $eb->eq('s.tpl_id', 'f.tpl_id'));
        }
        if (isset($criteria) && ($criteria instanceof CriteriaElement)) {
            $criteria->renderQb($qb);
        }
        $result = $qb->execute();
        if (!$result) {
            return $ret;
        }
        while ($myrow = $result->fetch(\PDO::FETCH_ASSOC)) {
            $tplfile = new XoopsTplfile();
            $tplfile->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] = $tplfile;
            } else {
                $ret[$myrow['tpl_id']] = $tplfile;
            }
            unset($tplfile);
        }
        return $ret;
    }

    /**
     * getModuleTplCount
     *
     * @param string $tplset
     *
     * @return array
     */
    public function getModuleTplCount($tplset)
    {
        $qb = $this->db2->createXoopsQueryBuilder();
        $eb = $qb->expr();

        $qb->select('tpl_module')
            ->addSelect('COUNT(tpl_id) AS count')
            ->fromPrefix('tplfile', null)
            ->where($eb->eq('tpl_tplset', ':tpset'))
            ->groupBy('tpl_module')
            ->setParameter(':tpset', $tplset, \PDO::PARAM_STR);

        $ret = array();
        $result = $qb->execute();
        if (!$result) {
            return $ret;
        }
        while ($myrow = $result->fetch(\PDO::FETCH_ASSOC)) {
            if ($myrow['tpl_module'] != '') {
                $ret[$myrow['tpl_module']] = $myrow['count'];
            }
        }
        return $ret;
    }

    /**
     * Find Template File
     *
     * @param string|null $tplset    template set
     * @param string|null $type      template type
     * @param string|null $refid     reference id
     * @param string|null $module    module
     * @param string|null $file      file name
     * @param bool        $getsource include template source
     *
     * @return array
     */
    public function find($tplset = null, $type = null, $refid = null, $module = null, $file = null, $getsource = false)
    {
        $criteria = new CriteriaCompo();
        if (isset($tplset)) {
            $criteria->add(new Criteria('tpl_tplset', $tplset));
        }
        if (isset($module)) {
            $criteria->add(new Criteria('tpl_module', $module));
        }
        if (isset($refid)) {
            $criteria->add(new Criteria('tpl_refid', $refid));
        }
        if (isset($file)) {
            $criteria->add(new Criteria('tpl_file', $file));
        }
        if (isset($type)) {
            if (is_array($type)) {
                $criteria2 = new CriteriaCompo();
                foreach ($type as $t) {
                    $criteria2->add(new Criteria('tpl_type', $t), 'OR');
                }
                $criteria->add($criteria2);
            } else {
                $criteria->add(new Criteria('tpl_type', $type));
            }
        }
        return $this->getTplObjects($criteria, $getsource, false);
    }

    /**
     * Template Exists
     *
     * @param string $tplname     template name
     * @param string $tplset_name set name
     *
     * @return bool
     */
    public function templateExists($tplname, $tplset_name)
    {
        $criteria = new CriteriaCompo(new Criteria('tpl_file', trim($tplname)));
        $criteria->add(new Criteria('tpl_tplset', trim($tplset_name)));
        if ($this->getCount($criteria) > 0) {
            return true;
        }
        return false;
    }
}
