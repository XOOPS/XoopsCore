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

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * A Template File
 *
 * @author Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000 XOOPS.org
 *
 * @package kernel
 **/
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
     * @param string $format
     * @return mixed
     */
    public function id($format = 'n')
    {
        return $this->getVar('tpl_id', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function tpl_id($format = '')
    {
        return $this->getVar('tpl_id', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function tpl_refid($format = '')
    {
        return $this->getVar('tpl_refid', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function tpl_tplset($format = '')
    {
        return $this->getVar('tpl_tplset', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function tpl_file($format = '')
    {
        return $this->getVar('tpl_file', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function tpl_desc($format = '')
    {
        return $this->getVar('tpl_desc', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function tpl_lastmodified($format = '')
    {
        return $this->getVar('tpl_lastmodified', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function tpl_lastimported($format = '')
    {
        return $this->getVar('tpl_lastimported', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function tpl_module($format = '')
    {
        return $this->getVar('tpl_module', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function tpl_type($format = '')
    {
        return $this->getVar('tpl_type', $format);
    }

    /**
     * @param string $format
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
     * @param XoopsConnection|null $db {@link XoopsConnection}
     */
    public function __construct(XoopsConnection $db = null)
    {
        parent::__construct($db, 'tplfile', 'XoopsTplfile', 'tpl_id', 'tpl_refid');
    }

    /**
     * retrieve a specific {@link XoopsTplfile}
     *
     * @param int $id tpl_id of the block to retrieve
     * @param bool $getsource
     * @return XoopsTplfile|bool
     */
    public function getById($id, $getsource = false)
    {
        $tplfile = false;
        $id = intval($id);
        if ($id > 0) {
            if (!$getsource) {
                $sql = 'SELECT * FROM ' . $this->db->prefix('tplfile') . ' WHERE tpl_id=' . $id;
            } else {
                $sql = 'SELECT f.*, s.tpl_source FROM ' . $this->db->prefix('tplfile') . ' f LEFT JOIN ' . $this->db->prefix('tplsource') . ' s  ON s.tpl_id=f.tpl_id WHERE f.tpl_id=' . $id;
            }
            if (! $result = $this->db->query($sql)) {
                return $tplfile;
            }
            $numrows = $this->db->getRowsNum($result);
            if ($numrows == 1) {
                $tplfile = new XoopsTplfile();
                $tplfile->assignVars($this->db->fetchArray($result));
            }
        }
        return $tplfile;
    }

    /**
     * @param XoopsTplfile $tplfile
     * @return bool
     */
    public function loadSource(XoopsTplFile &$tplfile)
    {
        if (!$tplfile->getVar('tpl_source')) {
            $sql = 'SELECT tpl_source FROM ' . $this->db->prefix('tplsource') . ' WHERE tpl_id=' . $tplfile->getVar('tpl_id');
            if (!$result = $this->db->query($sql)) {
                return false;
            }
            $myrow = $this->db->fetchArray($result);
            $tplfile->assignVar('tpl_source', $myrow['tpl_source']);
        }
        return true;
    }

    /**
     * write a new Tplfile into the database
     *
     * @param XoopsTplfile|XoopsObject $tplfile
     * @return bool
     */
    public function insertTpl(XoopsTplfile &$tplfile)
    {
        if (!$tplfile->isDirty()) {
            return true;
        }
        if (!$tplfile->cleanVars()) {
            return false;
        }
        foreach ($tplfile->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if ($tplfile->isNew()) {
            $tpl_id = $this->db->genId('tpltpl_file_id_seq');
            $sql = sprintf("INSERT INTO %s (tpl_id, tpl_module, tpl_refid, tpl_tplset, tpl_file, tpl_desc, tpl_lastmodified, tpl_lastimported, tpl_type) VALUES (%u, %s, %u, %s, %s, %s, %u, %u, %s)", $this->db->prefix('tplfile'), $tpl_id, $tpl_module, $tpl_refid, $tpl_tplset, $tpl_file, $tpl_desc, $tpl_lastmodified, $tpl_lastimported, $tpl_type);
            if (!$this->db->queryF($sql)) {
                return false;
            }
            if (empty($tpl_id)) {
                $tpl_id = $this->db->getInsertId();
            }
            if (isset($tpl_source) && $tpl_source != '') {
                $sql = sprintf("INSERT INTO %s (tpl_id, tpl_source) VALUES (%u, %s)", $this->db->prefix('tplsource'), $tpl_id, $tpl_source);
                if (!$this->db->queryF($sql)) {
                    $this->db->queryF(sprintf("DELETE FROM %s WHERE tpl_id = %u", $this->db->prefix('tplfile'), $tpl_id));
                    return false;
                }
            }
            $tplfile->assignVar('tpl_id', $tpl_id);
        } else {
            $sql = sprintf("UPDATE %s SET tpl_tplset = %s, tpl_file = %s, tpl_desc = %s, tpl_lastimported = %u, tpl_lastmodified = %u WHERE tpl_id = %u", $this->db->prefix('tplfile'), $tpl_tplset, $tpl_file, $tpl_desc, $tpl_lastimported, $tpl_lastmodified, $tpl_id);
            if (!$this->db->queryF($sql)) {
                return false;
            }
            if (isset($tpl_source) && $tpl_source != '') {
                $sql = sprintf("UPDATE %s SET tpl_source = %s WHERE tpl_id = %u", $this->db->prefix('tplsource'), $tpl_source, $tpl_id);
                if (!$this->db->queryF($sql)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param XoopsTplfile $tplfile
     * @return bool
     */
    public function forceUpdate(XoopsTplfile &$tplfile)
    {
        if (!$tplfile->isDirty()) {
            return true;
        }
        if (!$tplfile->cleanVars()) {
            return false;
        }
        foreach ($tplfile->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if (!$tplfile->isNew()) {
            $sql = sprintf("UPDATE %s SET tpl_tplset = %s, tpl_file = %s, tpl_desc = %s, tpl_lastimported = %u, tpl_lastmodified = %u WHERE tpl_id = %u", $this->db->prefix('tplfile'), $tpl_tplset, $tpl_file, $tpl_desc, $tpl_lastimported, $tpl_lastmodified, $tpl_id);
            if (!$this->db->queryF($sql)) {
                return false;
            }
            if (isset($tpl_source) && $tpl_source != '') {
                $sql = sprintf("UPDATE %s SET tpl_source = %s WHERE tpl_id = %u", $this->db->prefix('tplsource'), $tpl_source, $tpl_id);
                if (!$this->db->queryF($sql)) {
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
     * @return bool
     */
    public function deleteTpl(XoopsTplfile &$tplfile)
    {
        $id = $tplfile->getVar('tpl_id');
        $sql = sprintf("DELETE FROM %s WHERE tpl_id = %u", $this->db->prefix('tplfile'), $id);
        if (!$this->db->query($sql)) {
            return false;
        }
        $sql = sprintf("DELETE FROM %s WHERE tpl_id = %u", $this->db->prefix('tplsource'), $id);
        $this->db->query($sql);
        return true;
    }

    /**
     * @param CriteriaElement|null $criteria
     * @param bool $getsource
     * @param bool $id_as_key
     * @return array
     */
    public function getTplObjects(CriteriaElement $criteria = null, $getsource = false, $id_as_key = false)
    {
        $ret = array();
        $limit = $start = 0;
        if ($getsource) {
            $sql = 'SELECT f.*, s.tpl_source FROM ' . $this->db->prefix('tplfile') . ' f LEFT JOIN ' . $this->db->prefix('tplsource') . ' s ON s.tpl_id=f.tpl_id';
        } else {
            $sql = 'SELECT * FROM ' . $this->db->prefix('tplfile');
        }
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere() . ' ORDER BY tpl_refid';
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
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
     * @return array
     */
    public function getModuleTplCount($tplset)
    {
        $ret = array();
        $sql = "SELECT tpl_module, COUNT(tpl_id) AS count FROM " . $this->db->prefix('tplfile') . " WHERE tpl_tplset='" . $this->db->quoteString($tplset) . "' GROUP BY tpl_module";
        $result = $this->db->query($sql);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            if ($myrow['tpl_module'] != '') {
                $ret[$myrow['tpl_module']] = $myrow['count'];
            }
        }
        return $ret;
    }

    /**
     * Find Template File
     *
     * @param string|null $tplset
     * @param string|null $type
     * @param string|null $refid
     * @param string|null $module
     * @param string|null $file
     * @param bool $getsource
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
     * @param $tplname
     * @param $tplset_name
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