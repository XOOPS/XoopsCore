<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Database\Connection;
use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\CriteriaCompo;
use Xoops\Core\Kernel\XoopsObject;
use Xoops\Core\Kernel\XoopsPersistableObjectHandler;

/**
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         smilies
 * @author
 * @version         $Id$
 */
class SmiliesSmiley extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('smiley_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('smiley_code', XOBJ_DTYPE_TXTBOX, null, true, 100);
        $this->initVar('smiley_url', XOBJ_DTYPE_OTHER, null, false, 30);
        $this->initVar('smiley_emotion', XOBJ_DTYPE_TXTBOX, null, true, 100);
        $this->initVar('smiley_display', XOBJ_DTYPE_INT, 1, false);
    }
}

class SmiliesSmileyHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param Connection|null $db {@link Connection}
     */
    public function __construct(Connection $db = null)
    {
        parent::__construct($db, 'smilies', 'SmiliesSmiley', 'smiley_id', 'smiley_emotion');
    }

    public function getSmilies($start=0, $limit=0, $asobject=true)
    {
        $criteria = new CriteriaCompo();
        $criteria->setSort('smiley_id');
        $criteria->setOrder('ASC');
        $criteria->setStart($start);
        $criteria->setLimit($limit);
        return parent::getall($criteria, false, $asobject);
    }

    public function getActiveSmilies($asobject=true)
    {
        $criteria = new CriteriaCompo(new Criteria('smiley_display', 1));
        return parent::getall($criteria, false, $asobject);
    }
}
