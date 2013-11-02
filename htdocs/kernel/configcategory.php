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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * A category of configs
 *
 * @author    Kazumi Ono    <onokazu@xoops.org>
 * @copyright    copyright (c) 2000-2003 XOOPS.org
 *
 * @package     kernel
 */
class XoopsConfigCategory extends XoopsObject
{
    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->initVar('confcat_id', XOBJ_DTYPE_INT, null);
        $this->initVar('confcat_name', XOBJ_DTYPE_OTHER, null);
        $this->initVar('confcat_order', XOBJ_DTYPE_INT, 0);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function id($format = 'n')
    {
        return $this->getVar('confcat_id', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function confcat_id($format = '')
    {
        return $this->getVar('confcat_id', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function confcat_name($format = '')
    {
        return $this->getVar('confcat_name', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function confcat_order($format = '')
    {
        return $this->getVar('confcat_order', $format);
    }

}

/**
 * XOOPS configuration category handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS configuration category class objects.
 *
 * @author  Kazumi Ono <onokazu@xoops.org>
 * @copyright    copyright (c) 2000-2003 XOOPS.org
 *
 * @package     kernel
 * @subpackage  config
 */
class XoopsConfigCategoryHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param  XoopsConnection|null $db {@link XoopsConnection}
     */
    public function __construct(XoopsConnection $db = null)
    {
        parent::__construct($db, 'configcategory', 'XoopsConfigCategory', 'confcat_id', 'confcat_name');
    }

    /**
     * Get some {@link XoopsConfigCategory}s
     *
     * @param    CriteriaElement|null  $criteria   {@link CriteriaElement}
     * @param    bool    $id_as_key  Use the IDs as keys to the array?
     *
     * @return    array   Array of {@link XoopsConfigCategory}s
     */
    public function getCategoryObjects(CriteriaElement $criteria = null, $id_as_key = false)
    {
        $qb = $this->db->createXoopsQueryBuilder();
        $eb = $qb->expr();

        $qb ->select('*')
            ->fromPrefix('configcategory', null);

        // Original contined this logic - Why can't we trust criteria?
        // $sort = !in_array($criteria->getSort(),
        // array('confcat_id', 'confcat_name', 'confcat_order')) ? 'confcat_order' : $criteria->getSort();

        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $criteria->renderQb($qb);
        }

        $ret = array();

        $result = $qb->execute();
        if (!$result) {
            return $ret;
        }
        while ($myrow = $result->fetch(PDO::FETCH_ASSOC)) {
            $confcat = new XoopsConfigCategory();
            $confcat->assignVars($myrow, false);
            if (!$id_as_key) {
                $ret[] = $confcat;
            } else {
                $ret[$myrow['confcat_id']] = $confcat;
            }
            unset($confcat);
        }
        return $ret;
    }

}