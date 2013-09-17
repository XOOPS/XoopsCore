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
 * A Template Set File
 *
 * @author Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000 XOOPS.org
 *
 * @package kernel
 **/
class XoopsTplset extends XoopsObject
{

    /**
     * constructor
     **/
    function __construct()
    {
        $this->initVar('tplset_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('tplset_name', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('tplset_desc', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('tplset_credits', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('tplset_created', XOBJ_DTYPE_INT, 0, false);
    }

    /**
     * @param  string $format
     * @return mixed
     */
    function id($format = 'n')
    {
        return $this->getVar('tplset_id', $format);
    }

    /**
     * @param  string $format
     * @return mixed
     */
    function tplset_id($format = '')
    {
        return $this->getVar('tplset_id', $format);
    }

    /**
     * @param  string $format
     * @return mixed
     */
    function tplset_name($format = '')
    {
        return $this->getVar('tplset_name', $format);
    }

    /**
     * @param  string $format
     * @return mixed
     */
    function tplset_desc($format = '')
    {
        return $this->getVar('tplset_desc', $format);
    }

    /**
     * @param  string $format
     * @return mixed
     */
    function tplset_credits($format = '')
    {
        return $this->getVar('tplset_credits', $format);
    }

    /**
     * @param  string $format
     * @return mixed
     */
    function tplset_created($format = '')
    {
        return $this->getVar('tplset_created', $format);
    }

}

/**
 * XOOPS tplset handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS tplset class objects.
 *
 * @author  Kazumi Ono <onokazu@xoops.org>
 */
class XoopsTplsetHandler extends XoopsPersistableObjectHandler
{

    /**
     * Constructor
     *
     * @param XoopsConnection|null $db {@link XoopsConnection}
     */
    public function __construct(XoopsConnection $db = null)
    {
        parent::__construct($db, 'tplset', 'XoopsTplset', 'tplset_id', 'tplset_name');
    }

    /**
     * @param  string                     $tplset_name of the block to retrieve
     * @return XoopsTplset|falsereference to the tplsets
     */
    public function getByName($tplset_name)
    {
        $tplset = false;
        $tplset_name = trim($tplset_name);
        if ($tplset_name != '') {
            $sql = 'SELECT * FROM ' . $this->db->prefix('tplset') . ' WHERE tplset_name=' . $this->db->quoteString($tplset_name);
            if (!$result = $this->db->query($sql)) {
                return false;
            }
            $numrows = $this->db->getRowsNum($result);
            if ($numrows == 1) {
                $tplset = new XoopsTplset();
                $tplset->assignVars($this->db->fetchArray($result));
            }
        }

        return $tplset;
    }

    /**
     * get a list of tplsets matching certain conditions
     *
     * @param  CriteriaElement|null $criteria conditions to match
     * @return array                array of tplsets matching the conditions
     **/
    public function getNameList(CriteriaElement $criteria = null)
    {
        $ret = array();
        $tplsets = $this->getObjects($criteria, true);
        foreach (array_keys($tplsets) as $i) {
            $ret[$tplsets[$i]->getVar('tplset_name')] = $tplsets[$i]->getVar('tplset_name');
        }

        return $ret;
    }
}
