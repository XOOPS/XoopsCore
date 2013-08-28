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
 * File factory For XOOPS
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package     class
 * @subpackage  file
 * @since       2.3.0
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @version     $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * XoopsFile
 *
 * @package
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @access public
 */
class XoopsFile
{
    /**
     * XoopsFile::getHandler()
     *
     * @param string $name
     * @param mixed $path
     * @param mixed $create
     * @param mixed $mode
     * @return XoopsFileHandler|XoopsFolderHandler|bool
     */
    static function getHandler($name = 'file', $path = false, $create = false, $mode = null)
    {
        $handler = null;
        $name = strtolower(trim($name));
        $class = 'Xoops' . ucfirst($name) . 'Handler';
        if (in_array($name, array('file', 'folder'))) {
            $handler = new $class($path, $create, $mode);
        } else {
            trigger_error('Class ' . $class . ' not exist in File ' . __FILE__ . ' at Line ' . __LINE__, E_USER_WARNING);
            return false;
        }
        return $handler;
    }
}