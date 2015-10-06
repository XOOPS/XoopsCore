<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Kernel;

/**
 * abstract class object handler
 *
 * @category  Xoops\Core\Kernel\XoopsModelAbstract
 * @package   Xoops\Core\Kernel
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2000-2013 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.3.0
 */
abstract class XoopsModelAbstract
{
    /**
     * holds referenced to handler object
     *
     * @var XoopsPersistableObjectHandler $handler
     */
    protected $handler;

    /**
     * XoopsModelAbstract::setHandler()
     *
     * @param XoopsPersistableObjectHandler $handler handler to use
     *
     * @return boolean
     */
    public function setHandler(XoopsPersistableObjectHandler $handler)
    {
        $this->handler = $handler;
        return true;
    }

    /**
     * XoopsModelAbstract::setVars()
     *
     * @param mixed $args args
     *
     * @return boolean
     */
    public function setVars($args)
    {
        if (!empty($args) && is_array($args)) {
            foreach ($args as $key => $value) {
                $this->$key = $value;
            }
        }
        return true;
    }
}
