<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Cache engine For XOOPS
 *
 * PHP 5.3
 *
 * @category   Xoops\Class\Cache\Cache
 * @package    Cache
 * @author     Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright  2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version    $Id$
 * @link       http://xoops.org
 * @since      2.6.0
 * @deprecated
 */
class XoopsCache extends Xoops_Cache
{
    /**
     * Returns a singleton instance
     *
     * @return Xoops_Cache
     * @deprecated
     */
    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new Xoops_Cache();
        }
        return $instance;
    }
}

/**
 * Cache engine For XOOPS
 *
 * PHP 5.3
 *
 * @category   Xoops\Class\Cache\CacheEngine
 * @package    CacheEngine
 * @author     Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright  2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version    $Id$
 * @link       http://xoops.org
 * @since      2.6.0
 * @deprecated
 */
abstract class XoopsCacheEngine extends Xoops_Cache_Abstract
{
}
