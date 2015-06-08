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
 * XoopsFile
 *
 * File factory For XOOPS
 *
 * PHP 5.3
 *
 * @category  Xoops\Class\Cache\CacheApc
 * @package   CacheApc
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2013 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   $Id$
 * @link      http://xoops.org
 * @since     2.6.0
 */
class XoopsFile
{
    /**
     * XoopsFile::getHandler()
     *
     * @param string $name   name of file
     * @param string  $path   path file is in
     * @param boolean  $create create file if needed
     * @param integer  $mode   mode on file created
     *
     * @return XoopsFileHandler|XoopsFolderHandler|bool
     */
    public static function getHandler($name = 'file', $path = false, $create = false, $mode = null)
    {
        $handler = null;
        $name = strtolower(trim($name));
        $class = 'Xoops' . ucfirst($name) . 'Handler';
        if (in_array($name, array('file', 'folder'))) {
            $handler = new $class($path, $create, $mode);
        } else {
            trigger_error(
                'Class ' . $class . ' not exist in File ' . __FILE__ . ' at Line ' . __LINE__,
                E_USER_WARNING
            );
            return false;
        }
        return $handler;
    }
}
