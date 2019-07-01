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
use Xoops\Core\Kernel\Dtype;
use Xoops\Core\Kernel\XoopsObject;
use Xoops\Core\Kernel\XoopsPersistableObjectHandler;

/**
 * UserrankRank and handler
 *
 * @copyright       2000-2015 XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         userrank
 * @since           2.6.0
 * @author          Gregory Mage (AKA Mage)
 * @author          trabis <lusopoemas@gmail.com>
 */
class UserrankRank extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('rank_id', Dtype::TYPE_INTEGER, null, false, 5);
        $this->initVar('rank_title', Dtype::TYPE_TEXT_BOX, null, false);
        $this->initVar('rank_min', Dtype::TYPE_INTEGER, null, false, 8);
        $this->initVar('rank_max', Dtype::TYPE_INTEGER, null, false, 8);
        $this->initVar('rank_special', Dtype::TYPE_INTEGER, null, false, 1);
        $this->initVar('rank_image', Dtype::TYPE_TEXT_BOX, null, false);
    }

    /**
     * getter for id generic key
     *
     * @param string $format
     *
     * @return mixed
     */
    public function id($format = 'n')
    {
        return $this->rank_id($format);
    }

    /**
     * getter for rank_id field
     *
     * @param string $format
     *
     * @return mixed
     */
    public function rank_id($format = '')
    {
        return $this->getVar('rank_id', $format);
    }

    /**
     * getter for rank_title field
     *
     * @param string $format
     *
     * @return mixed
     */
    public function rank_title($format = '')
    {
        return $this->getVar('rank_title', $format);
    }

    /**
     * getter for rank_min field
     *
     * @param string $format
     *
     * @return mixed
     */
    public function rank_min($format = '')
    {
        return $this->getVar('rank_min', $format);
    }

    /**
     * getter for rank_max field
     *
     * @param string $format
     *
     * @return mixed
     */
    public function rank_max($format = '')
    {
        return $this->getVar('rank_max', $format);
    }

    /**
     * getter for rank_special field
     *
     * @param string $format
     *
     * @return mixed
     */
    public function rank_special($format = '')
    {
        return $this->getVar('rank_special', $format);
    }

    /**
     * getter for rank_image field
     *
     * @param string $format
     *
     * @return mixed
     */
    public function rank_image($format = '')
    {
        return $this->getVar('rank_image', $format);
    }
}

class UserrankRankHandler extends XoopsPersistableObjectHandler
{
    public function __construct(Connection $db)
    {
        parent::__construct($db, 'userrank_rank', 'UserrankRank', 'rank_id', 'rank_title');
    }
}
